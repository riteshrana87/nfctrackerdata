<?php

/*
  @Author : Ishani dave
  @Desc   : cse report
  @Date   : 17/10/2017
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class CseReport extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (checkPermission('CseReport', 'view') == false) {
            redirect('/Dashboard');
        }
        $this->module = $this->uri->segment(1);
        $this->viewname = $this->uri->segment(1);
        $this->load->library(array('form_validation', 'Session'));
        $this->load->model('CseReport_model');
    }

// index page
    public function index($id,$care_home_id=0,$past_care_id=0, $page = '') {
        if($past_care_id!== 0){
            $temp_match=array("yp_id"=>$id,"past_carehome"=>$care_home_id);
            $temp=$this->common_model->get_records(PAST_CARE_HOME_INFO,array('move_date'), '', '',$temp_match);         
            $last_date=$temp[0]['move_date'];
            $match = array("yp_id"=>$id,"move_date <= "=>$last_date);
            $data_care_home_detail['care_home_detail'] = $this->common_model->get_records(PAST_CARE_HOME_INFO, array("*"), '', '', $match);
            
            /* 
            echo $this->db->last_query();
            die; 
            */
            $created_date=$movedate='';         
            
            $count_care=count($data_care_home_detail['care_home_detail']);
                
            
            if($count_care >= 1){
                
                $created_date=$data_care_home_detail['care_home_detail'][0]['enter_date'];
                $movedate=$data_care_home_detail['care_home_detail'][$count_care-1]['move_date'];
                
            }
            elseif($count_care==0){
                
                $created_date=$data_care_home_detail['care_home_detail'][0]['enter_date'];
                $movedate=$data_care_home_detail['care_home_detail'][0]['move_date'];
                
            }
            else{
                
                $created_date='';
                $movedate='';
            }
        }
        

        $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
        $searchtext = $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $yp_list_type = $this->input->post('yp_list_type');
        $data['yp_list_type'] = !empty($yp_list_type) ? $yp_list_type : 'display-table';
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('yp_data');
        }
        //get YP information
        $match = array("yp_id" => $id);
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
        $searchsort_session = $this->session->userdata('yp_data');
        //Sorting
        if (!empty($sortfield) && !empty($sortby)) {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        } else {
            if (!empty($searchsort_session['sortfield'])) {

                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby'] = $searchsort_session['sortby'];
                $sortfield = $searchsort_session['sortfield'];
                $sortby = $searchsort_session['sortby'];
            } else {

                $sortfield = 'sr.id';
                $sortby = 'desc';
                $data['sortfield'] = $sortfield;
                $data['sortby'] = $sortby;
            }
        }
        //Search text
        if (!empty($searchtext)) {

            $data['searchtext'] = $searchtext;
        } else {
            if (empty($allflag) && !empty($searchsort_session['searchtext'])) {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext = $data['searchtext'];
            } else {

                $data['searchtext'] = '';
            }
        }

        if (!empty($perpage) && $perpage != 'null') {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        } else {
            if (!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = 10;
                $data['perpage'] = 10;
            }
        }
        //pagination configuration
        $config['first_link'] = 'First';

        if($past_care_id == 0){

        $config['base_url'] = base_url() . $this->viewname . '/index/' . $id;
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $page = 0;
        }
        $uri_segment = $page;
        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = NFC_CSE_RECORD . ' as sr';
        $where = array('sr.is_delete' => '0', 'sr.status' => '1', 'sr.yp_id' => $id);
        $fields = array("sr.*, yp.yp_fname, yp.yp_lname, yp.gender, yp.profile_img,ch.care_home_name");
        $join_tables = array(YP_DETAILS . ' as yp' => 'yp.yp_id = sr.yp_id',CARE_HOME . ' as ch' => 'ch.care_home_id = sr.care_home_id');
        if (!empty($searchtext)) {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $where_search = '(
				sr.user_type LIKE "%' . $searchtext . '%"
                                OR DATE_FORMAT(sr.created_date, "%d/%m/%Y") = "' . $searchtext . '"
				OR DATE_FORMAT(sr.modified_date, "%d/%m/%Y") = "' . $searchtext . '"
                            )';
            $data['cse_record_list'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $where, '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);

            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $where, '', '', '', $sortfield, $sortby, '', $where_search, '', '', '1');
        } else {
            $data['cse_record_list'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
        }
    }else{
        $config['base_url'] = base_url() . $this->viewname . '/index/' . $id .'/'. $care_home_id .'/'. $past_care_id;
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $page = 0;
        }
        $uri_segment = $page;
        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = NFC_CSE_RECORD . ' as sr';
        $where = array('sr.is_delete' => '0', 'sr.status' => '1', 'sr.yp_id' => $id);
        $where_date = "STR_TO_DATE( sr.created_date, '%Y-%m-%d' ) BETWEEN  '".$created_date."' AND '".$movedate."'";
        $fields = array("sr.*, yp.yp_fname, yp.yp_lname, yp.gender, yp.profile_img,ch.care_home_name");
        $join_tables = array(YP_DETAILS . ' as yp' => 'yp.yp_id = sr.yp_id',CARE_HOME . ' as ch' => 'ch.care_home_id = sr.care_home_id');
        if (!empty($searchtext)) {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $where_search = '(
                sr.user_type LIKE "%' . $searchtext . '%"
                                OR DATE_FORMAT(sr.created_date, "%d/%m/%Y") = "' . $searchtext . '"
                OR DATE_FORMAT(sr.modified_date, "%d/%m/%Y") = "' . $searchtext . '"
                            )';
            $data['cse_record_list'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $where, '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search,'','','','','',$where_date);

            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $where, '', '', '', $sortfield, $sortby, '', $where_search, '', '', '1','','',$where_date);
        } else {
            $data['cse_record_list'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where,'','','','','',$where_date);

            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1','','',$where_date);

        }

    }

        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links();

        $data['uri_segment'] = $uri_segment;
        $sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uri_segment,
            'total_rows' => $config['total_rows']);

        $this->session->set_userdata('sdq_data', $sortsearchpage_data);
        setActiveSession('yp_data'); // set current Session active
        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['crnt_view'] = $this->viewname;
        $data['ypid'] = !empty($id) ? $id : '';
        $data['care_home_id'] = $care_home_id;
        $data['past_care_id'] = $past_care_id;
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/ajaxlist', $data);
        } else {
            $data['main_content'] = '/' . $this->viewname;
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
    }

    //ishani dave
    //CSE questionnaire
    function cseQuestion($id) {

        $data['footerJs'][0] = base_url('uploads/custom/js/cse/cse_ques.js');
//        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
//        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');
        //get YP information
        $match = array("yp_id" => $id);
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

        if (empty($data['YP_details'])) {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson');
        }

        // get cse questionnaire data

        $matchSd = "is_delete = '0' and status = '1'";
        $fieldsSd = array("*");
        $data['cse_que_details'] = $this->common_model->get_records(NFC_ADMIN_CSE, $fieldsSd, '', '', $matchSd);


        $data['ypid'] = !empty($id) ? $id : '';
        $data['care_home_id'] = $data['YP_details'][0]['care_home'];
        
        $data['crnt_view'] = $this->viewname;
        $user_id = $this->session->userdata('LOGGED_IN')['ID'];

        $data['main_content'] = '/cse_question';
        $data['header'] = array('menu_module' => 'YoungPerson');
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }

    // ishani dave
    // save sdq report data

    function savedata() {

        $yp_id = $this->input->post('yp_id');
        $user_type = $this->input->post('user_type');
        $createdDate = $this->input->post('created_date');
        $created_date = dateformat($createdDate);

        $check_record = $this->CseReport_model->checkRecord($yp_id, $user_type, $created_date);
        if (count($check_record) > 0) {
            $msg = $this->lang->line('record_exist');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect('/' . $this->viewname . '/index/' . $yp_id);
        } else {
            $addData['yp_id'] = $yp_id;
            $addData['user_type'] = $this->input->post('user_type');
            $addData['total_score_h'] = $this->input->post('total_score_h');
            $addData['total_score_m'] = $this->input->post('total_score_m');
            $addData['total_score_l'] = $this->input->post('total_score_l');
            $addData['total_score_n'] = $this->input->post('total_score_n');
            $addData['comment'] = $this->input->post('comment');
            $addData['created_date'] = $created_date;
            $addData['modified_date'] = $created_date;
            $addData['status'] = '1';
            $addData['is_delete'] = '0';
            $addData['care_home_id'] = $this->input->post('care_home_id');

            $table = NFC_CSE_RECORD;
            $data['add_data'] = $this->common_model->insert($table, $addData);
            //Insert log activity
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'module_name' => CSE_MODULE,
                'module_field_name' => '',
                'yp_id' => $yp_id,
                'type' => 1
            );
            log_activity($activity);
            $record_id = $data['add_data'];
            $sub_que_id = $_POST['sub_que_id'];

            // save assesment record to nfc_record_ans table
            foreach ($sub_que_id as $row) {

                $tableSub = NFC_ADMIN_CSE_SUB . ' as su';
                $matchSub = "is_delete = '0' and status = '1' and cse_sub_que_id =" . $row;
                $fieldsSub = array("su.parent_que_id");
                $data['cse_sub_que_details'] = $this->common_model->get_records($tableSub, $fieldsSub, '', '', $matchSub);

                $sub_que_data = $data['cse_sub_que_details'];
                $que_id = $sub_que_data[0]['parent_que_id'];

                $savedata['record_id'] = $record_id;
                $savedata['que_id'] = $que_id;
                $savedata['sub_que_id'] = $row;
                $ans_data = ($this->input->post('ans_' . $row)) ? $this->input->post('ans_' . $row) : '';
                $savedata['ans'] = $ans_data;
                $savedata['is_delete'] = '0';

                $table = NFC_CSE_RECORD_ANS;
                $data['save_data'] = $this->common_model->insert($table, $savedata);
            }
            $msg = $this->lang->line('record_submit');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect('/' . $this->viewname . '/index/' . $yp_id);
        }
    }

    // CSE Report page 

    function CseRecordReport($id,$care_home_id=0,$past_care_id=0) {

        $data['footerJs'][0] = base_url('uploads/custom/js/cse/cse_report.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');

        $yp_ID = $this->input->post('yp_id');
        if (!empty($id)) {
            $yp_id = !empty($yp_ID) ? $yp_ID : $id;
        } else {
            $yp_id = !empty($$yp_ID) ? $yp_ID : '';
        }
        $data['yp_id'] = $yp_id;

        //get YP information
        $match = array("yp_id" => $yp_id);
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

        if (empty($data['YP_details'])) {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson');
        }

        $selected_year = date('Y');
        $getYear = $this->input->post('year');
        $year = !empty($getYear) ? $getYear : '';
        $data['year'] = $year;

        $selected_month = date('m');
        $getMonth = $this->input->post('month');
        $month = !empty($getMonth) ? $getMonth : '';
        $data['month'] = $month;

        //get cse information
        if (!empty($year) && !empty($month)) {
            $matchC = "yp_id =" . $yp_id . " and year(created_date) = " . $year . " and month(created_date) = " . $month;
        } else if (!empty($year)) {
            $matchC = "yp_id =" . $yp_id . " and year(created_date) = " . $year;
        } else if (!empty($month)) {
            $matchC = "yp_id =" . $yp_id . " and month(created_date) = " . $month;
        } else {
            $matchC = "yp_id =" . $yp_id;
        }


        if($past_care_id!== 0){
            $temp_match=array("yp_id"=>$yp_id,"past_carehome"=>$care_home_id);
            $temp=$this->common_model->get_records(PAST_CARE_HOME_INFO,array('move_date'), '', '',$temp_match);         
            $last_date=$temp[0]['move_date'];
            $match = array("yp_id"=>$yp_id,"move_date <= "=>$last_date);
            $data_care_home_detail['care_home_detail'] = $this->common_model->get_records(PAST_CARE_HOME_INFO, array("*"), '', '', $match);

            /* echo $this->db->last_query();
            die; */
            $created_date=$movedate='';         
            
            $count_care=count($data_care_home_detail['care_home_detail']);
                
            
            if($count_care >= 1){
                
                $created_date=$data_care_home_detail['care_home_detail'][0]['enter_date'];
                $movedate=$data_care_home_detail['care_home_detail'][$count_care-1]['move_date'];
                
            }
            elseif($count_care==0){
                
                $created_date=$data_care_home_detail['care_home_detail'][0]['enter_date'];
                $movedate=$data_care_home_detail['care_home_detail'][0]['move_date'];
                
            }
            else{
                
                $created_date='';
                $movedate='';
            }
        }

        $data['created_date'] = $created_date;
        $data['movedate'] = $movedate;
        $data['care_home_id'] = $care_home_id;
        $data['past_care_id'] = $past_care_id;


        
                /*
  @Author : Ritesh rana
  @Desc   : Get past care home data As per create date and move date
  @Input  : create date and pmove data
  @Output : CSE report data
  @Date   : 25/09/2018
 */
  $fieldsC = array("comment,user_type,created_date");
    if($past_care_id > 0){
        $where_date = "created_date BETWEEN  '".$created_date."' AND '".$movedate."'";
        $data['comment_details'] = $this->common_model->get_records(NFC_CSE_RECORD, $fieldsC, '', '', $matchC,'','','','','','','','','','','','',$where_date);
    }else{
        $data['comment_details'] = $this->common_model->get_records(NFC_CSE_RECORD, $fieldsC, '', '', $matchC);
    }
    /*end*/

        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['main_content'] = '/cserecord_report';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }

    //get comment detail
    function getCommentData() {
        $getYear = $this->input->post('year');
        $year = !empty($getYear) ? $getYear : '';

        $getMonth = $this->input->post('month');
        $month = !empty($getMonth) ? $getMonth : '';

        $ypId = $this->input->post('yp_id');
        $yp_id = !empty($ypId) ? $ypId : '';


        //get cse information
        if (!empty($year) && !empty($month)) {
            $matchC = "yp_id =" . $yp_id . " and year(created_date) = " . $year . " and month(created_date) = " . $month;
        } else if (!empty($year)) {
            $matchC = "yp_id =" . $yp_id . " and year(created_date) = " . $year;
        } else if (!empty($month)) {
            $matchC = "yp_id =" . $yp_id . " and month(created_date) = " . $month;
        } else {
            $matchC = "yp_id =" . $yp_id;
        }

        $fieldsC = array("comment,user_type,created_date");

                /*
  @Author : Ritesh rana
  @Desc   : Get past care home data As per create date and move date
  @Input  : create date and pmove data
  @Output : CSE report data
  @Date   : 26/09/2018
 */
    if($this->input->post('PastCareId') > 0){
        $movedate = $this->input->post('movedate');
        $CreatedDate = $this->input->post('CreatedDate');
        $where_date = "created_date BETWEEN  '".$CreatedDate."' AND '".$movedate."'";
        $data['comment_details'] = $this->common_model->get_records(NFC_CSE_RECORD, $fieldsC, '', '', $matchC,'','','','','','','','','','','','',$where_date);
    }else{
        $fieldsRecord = array("id");
        $data['comment_details'] = $this->common_model->get_records(NFC_CSE_RECORD, $fieldsC, '', '', $matchC);
    }

        $this->load->view($this->viewname . '/comment_section', $data);
    }

    // Edit cse questionnaire

    function edit($yp_id, $id) {
        $data['footerJs'][0] = base_url('uploads/custom/js/cse/cse_ques.js');
//        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
//        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');

        if (is_numeric($id)) {

            //get YP information
            $match = array("yp_id" => $yp_id);
            $fields = array("*");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson');
            }

            // get cse questionnaire data

            $matchSd = "is_delete = '0' and status = '1'";
            $fieldsSd = array("*");
            $data['cse_que_details'] = $this->common_model->get_records(NFC_ADMIN_CSE, $fieldsSd, '', '', $matchSd);

            // get assesment data
            $tableRecord = NFC_CSE_RECORD . ' as cr';
            $matchR = "cr.is_delete = '0' and cr.id = " . $id;
            $fieldsR = array("cr.*");
            $data['editRecord'] = $this->common_model->get_records($tableRecord, $fieldsR, '', '', $matchR);

            $record_id = $data['editRecord'][0]['id'];

            $tableAns = NFC_CSE_RECORD_ANS;
            $matchA = "is_delete = '0' and record_id = " . $record_id;
            $fieldsA = array("*");
            $data['cse_record_ans'] = $this->common_model->get_records($tableAns, $fieldsA, '', '', $matchA);


            $data['ypid'] = !empty($yp_id) ? $yp_id : '';
            $data['crnt_view'] = $this->viewname;
            $user_id = $this->session->userdata('LOGGED_IN')['ID'];

            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/cse_question';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
    }


    // Edit cse questionnaire

    function view($yp_id, $id,$care_home_id=0,$past_care_id) {
        $data['footerJs'][0] = base_url('uploads/custom/js/cse/cse_ques.js');
//        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
//        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');

        if (is_numeric($id)) {

            //get YP information
            $match = array("yp_id" => $yp_id);
            $fields = array("*");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson');
            }

            // get cse questionnaire data

            $matchSd = "is_delete = '0' and status = '1'";
            $fieldsSd = array("*");
            $data['cse_que_details'] = $this->common_model->get_records(NFC_ADMIN_CSE, $fieldsSd, '', '', $matchSd);

            // get assesment data
            $tableRecord = NFC_CSE_RECORD . ' as cr';
            $matchR = "cr.is_delete = '0' and cr.id = " . $id;
            $fieldsR = array("cr.*");
            $data['editRecord'] = $this->common_model->get_records($tableRecord, $fieldsR, '', '', $matchR);

            $record_id = $data['editRecord'][0]['id'];

            $tableAns = NFC_CSE_RECORD_ANS;
            $matchA = "is_delete = '0' and record_id = " . $record_id;
            $fieldsA = array("*");
            $data['cse_record_ans'] = $this->common_model->get_records($tableAns, $fieldsA, '', '', $matchA);


            $data['ypid'] = !empty($yp_id) ? $yp_id : '';
            $data['care_home_id'] = $care_home_id;
            $data['past_care_id'] = $past_care_id;
            
            $data['crnt_view'] = $this->viewname;
            $user_id = $this->session->userdata('LOGGED_IN')['ID'];

            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/cse_view';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
    }

    //update cse report data
    function updatedata() {
        $yp_Id = $this->input->post('yp_id');
        $yp_id = isset($yp_Id) ? $yp_Id : '';
        $record_id = $this->input->post('record_id');
        $updateData['user_type'] = $this->input->post('user_type');
        $updateData['total_score_h'] = $this->input->post('total_score_h');
        $updateData['total_score_m'] = $this->input->post('total_score_m');
        $updateData['total_score_l'] = $this->input->post('total_score_l');
        $updateData['total_score_n'] = $this->input->post('total_score_n');
        $updateData['modified_date'] = datetimeformat();
        $updateData['comment'] = $this->input->post('comment');

        //update nfc_cse_recordsheetdata
        $table = NFC_CSE_RECORD;
        $strWhere = "is_delete = '0' and yp_id = '" . $yp_id . "' and id = '" . $record_id . "' ";
        $data['update_sub_que'] = $this->common_model->update($table, $updateData, $strWhere);

        $sub_que_id = $_POST['sub_que_id'];

        //delete sub que data
        $tableSub = NFC_CSE_RECORD_ANS;
        $strWhereSub = "is_delete = '0' and record_id =" . $record_id;
        $data['update_data'] = $this->common_model->delete($tableSub, $strWhereSub);
        //Insert log activity
        $activity = array(
            'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
            'module_name' => CSE_MODULE,
            'module_field_name' => '',
            'yp_id' => $yp_id,
            'type' => 2
        );
        log_activity($activity);
        // save assesment record to nfc_record_ans table
        foreach ($sub_que_id as $row) {

            $tableSub = NFC_ADMIN_CSE_SUB . ' as su';
            $matchSub = "is_delete = '0' and status = '1' and cse_sub_que_id =" . $row;
            $fieldsSub = array("su.parent_que_id");
            $data['cse_sub_que_details'] = $this->common_model->get_records($tableSub, $fieldsSub, '', '', $matchSub);

            $sub_que_data = $data['cse_sub_que_details'];
            $que_id = $sub_que_data[0]['parent_que_id'];

            $savedata['record_id'] = $record_id;
            $savedata['que_id'] = $que_id;
            $savedata['sub_que_id'] = $row;
            $ans_data = ($this->input->post('ans_' . $row)) ? $this->input->post('ans_' . $row) : '';
            $savedata['ans'] = $ans_data;
            $savedata['is_delete'] = '0';

            $table = NFC_CSE_RECORD_ANS;
            $data['save_data'] = $this->common_model->insert($table, $savedata);
        }

        $msg = $this->lang->line('record_edit');
        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        redirect('/' . $this->viewname . '/index/' . $yp_id);
    }

//get overall total score
    function getValsum() {

        $sub_que_id = $_POST['sub_que_id'];

        $sum = 0;
        $h_data = array();
        $m_data = array();
        $l_data = array();
        $n_data = array();
        foreach ($sub_que_id as $row) {
            $ans_data = ($this->input->post('ans_' . $row)) ? $this->input->post('ans_' . $row) : '';
            if ($ans_data == 'h') {
                $h_data[] = count($ans_data);
            }
            if ($ans_data == 'm') {
                $m_data[] = count($ans_data);
            }
            if ($ans_data == 'l') {
                $l_data[] = count($ans_data);
            }
            if ($ans_data == 'n') {
                $n_data[] = count($ans_data);
            }
        }

        $h_count_data = count($h_data);
        $m_count_data = count($m_data);
        $l_count_data = count($l_data);
        $n_count_data = count($n_data);
        $total_h = $h_count_data * 3;
        $total_m = $m_count_data * 2;
        $total_l = $l_count_data * 1;
        $total_n = $n_count_data * 0;
        $final_total = $total_h + $total_m + $total_l + $total_n;

        echo $h_count_data . "," . $m_count_data . "," . $l_count_data . "," . $n_count_data . "," . $total_h . "," . $total_m . "," . $total_l . "," . $total_n . "," . $final_total;
        exit;
    }

// total graph

    function getTotalData() {
        $data['footerJs'][0] = base_url('uploads/custom/js/cse/cse_report.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');

        $yp_ID = $this->input->post('yp_id');
        $yp_id = !empty($yp_ID) ? $yp_ID : '';
        $data['yp_id'] = $yp_id;

        //get YP information
        $match = array("yp_id" => $yp_id);
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
        if (empty($data['YP_details'])) {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson');
        }

        $selected_year = date('Y');
        $getYear = $this->input->post('year');
        $year = !empty($getYear) ? $getYear : '';
        $data['year'] = $year;

        $selected_month = date('m');
        $getMonth = $this->input->post('month');
        $month = !empty($getMonth) ? $getMonth : '';
        $data['month'] = $month;

        // get data from nfc_cse_recordsheet table 
        if (!empty($month) && !empty($year)) {
            $matchRecord = "is_delete = '0' and YEAR(created_date) = '" . $year . "' and MONTH(created_date) = '" . $month . "' and yp_id = " . $yp_id;
        } else if (!empty($year)) {
            $matchRecord = "is_delete = '0' and YEAR(created_date) = '" . $year . "' and yp_id = " . $yp_id;
        } else if (!empty($month)) {
            $matchRecord = "is_delete = '0' and MONTH(created_date) = '" . $month . "' and yp_id = " . $yp_id;
        } else {
            $matchRecord = "is_delete = '0'  and yp_id = " . $yp_id;
        }

           /*
  @Author : Ritesh rana
  @Desc   : Get past care home data As per create date and move date
  @Input  : create date and pmove data
  @Output : CSE report data
  @Date   : 25/09/2018
 */
     if($this->input->post('PastCareId') > 0){
        $movedate = $this->input->post('movedate');
        $CreatedDate = $this->input->post('CreatedDate');
        $where_date = "created_date BETWEEN  '".$CreatedDate."' AND '".$movedate."'";
        $fieldsRecord = array("id");
        $data['record_details'] = $this->common_model->get_records(NFC_CSE_RECORD, $fieldsRecord, '', '', $matchRecord,'','','','','','','','','','','','',$where_date);
    }else{
        $fieldsRecord = array("id");
        $data['record_details'] = $this->common_model->get_records(NFC_CSE_RECORD, $fieldsRecord, '', '', $matchRecord);
    }
    /*end*/

        $record_details = $data['record_details'];

        // total indicators data
        //get question id from nfc_admin_cse table
        $matchQue = "is_delete = '0'";
        $fieldsQue = array("cse_que_id");
        $data['que_details'] = $this->common_model->get_records(NFC_ADMIN_CSE, $fieldsQue, '', '', $matchQue);


        foreach ($record_details as $result) {
            $matchData = "is_delete = 0 and ans = 'h' and record_id = " . $result['id'];
            $fieldsData = array("count(ans) as total");
            $data['ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsData, '', '', $matchData);
            $high[] = $data['ans_details'][0]['total'];

            $matchDataM = "is_delete = 0 and ans = 'm' and record_id = " . $result['id'];
            $fieldsDataM = array("count(ans) as med_total");
            $data['m_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataM, '', '', $matchDataM);
            $medium[] = $data['m_ans_details'][0]['med_total'];

            $matchDataL = "is_delete = 0 and ans = 'l' and record_id = " . $result['id'];
            $fieldsDataL = array("count(ans) as low_total");
            $data['l_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataL, '', '', $matchDataL);
            $low[] = $data['l_ans_details'][0]['low_total'];

            $matchDataN = "is_delete = 0 and ans = 'n' and record_id = " . $result['id'];
            $fieldsDataN = array("count(ans) as no_known_total");
            $data['n_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataN, '', '', $matchDataN);
            $no_known[] = $data['n_ans_details'][0]['no_known_total'];
        }
        //total series data
        $highVal = array_sum($high);
        $medVal = array_sum($medium);
        $lowVal = array_sum($low);
        $no_knownVal = array_sum($no_known);


        echo $highVal . "," . $medVal . "," . $lowVal . "," . $no_knownVal;
        exit;
    }

// health graph
    function getHealthData() {
        $data['footerJs'][0] = base_url('uploads/custom/js/cse/cse_report.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');

        $yp_ID = $this->input->post('yp_id');
        $yp_id = !empty($yp_ID) ? $yp_ID : '';
        $data['yp_id'] = $yp_id;

        //get YP information
        $match = array("yp_id" => $yp_id);
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
        if (empty($data['YP_details'])) {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson');
        }

        $selected_year = date('Y');
        $getYear = $this->input->post('year');
        $year = !empty($getYear) ? $getYear : '';
        $data['year'] = $year;

        $selected_month = date('m');
        $getMonth = $this->input->post('month');
        $month = !empty($getMonth) ? $getMonth : '';
        $data['month'] = $month;

        // get data from nfc_cse_recordsheet table 
        if (!empty($month) && !empty($year)) {
            $matchRecord = "is_delete = '0' and YEAR(created_date) = '" . $year . "' and MONTH(created_date) = '" . $month . "' and yp_id = " . $yp_id;
        } else if (!empty($year)) {
            $matchRecord = "is_delete = '0' and YEAR(created_date) = '" . $year . "' and yp_id = " . $yp_id;
        } else if (!empty($month)) {
            $matchRecord = "is_delete = '0' and MONTH(created_date) = '" . $month . "' and yp_id = " . $yp_id;
        } else {
            $matchRecord = "is_delete = '0'  and yp_id = " . $yp_id;
        }


/*
  @Author : Ritesh rana
  @Desc   : Get past care home data As per create date and move date
  @Input  : create date and pmove data
  @Output : CSE report data
  @Date   : 26/09/2018
*/
     if($this->input->post('PastCareId') > 0){
        $movedate = $this->input->post('movedate');
        $CreatedDate = $this->input->post('CreatedDate');
        $where_date = "created_date BETWEEN  '".$CreatedDate."' AND '".$movedate."'";
        $fieldsRecord = array("id");
        $data['record_details'] = $this->common_model->get_records(NFC_CSE_RECORD, $fieldsRecord, '', '', $matchRecord,'','','','','','','','','','','','',$where_date);
    }else{
        $fieldsRecord = array("id");
        $data['record_details'] = $this->common_model->get_records(NFC_CSE_RECORD, $fieldsRecord, '', '', $matchRecord);
    }

        $record_details = $data['record_details'];

        // Health graph data
        //get question id from nfc_admin_cse table
        $matchQue = "is_delete = '0' and que = 'Health'";
        $fieldsQue = array("cse_que_id");
        $data['que_details'] = $this->common_model->get_records(NFC_ADMIN_CSE, $fieldsQue, '', '', $matchQue);
        $queDetails = $data['que_details'];
        $que_id = $queDetails[0]['cse_que_id'];

        foreach ($record_details as $result) {
            $matchData = "is_delete = 0 and ans = 'h' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsData = array("count(ans) as total");
            $data['ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsData, '', '', $matchData);
            $high[] = $data['ans_details'][0]['total'];


            $matchDataM = "is_delete = 0 and ans = 'm' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsDataM = array("count(ans) as med_total");
            $data['m_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataM, '', '', $matchDataM);
            $medium[] = $data['m_ans_details'][0]['med_total'];

            $matchDataL = "is_delete = 0 and ans = 'l' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsDataL = array("count(ans) as low_total");
            $data['l_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataL, '', '', $matchDataL);
            $low[] = $data['l_ans_details'][0]['low_total'];

            $matchDataN = "is_delete = 0 and ans = 'n' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsDataN = array("count(ans) as no_known_total");
            $data['n_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataN, '', '', $matchDataN);
            $no_known[] = $data['n_ans_details'][0]['no_known_total'];
        }
        //health series data
        $highVal = array_sum($high);
        $medVal = array_sum($medium);
        $lowVal = array_sum($low);
        $no_knownVal = array_sum($no_known);
        $total = $highVal + $medVal + $lowVal + $no_knownVal;
        echo $highVal . "," . $medVal . "," . $lowVal . "," . $no_knownVal . "," . $total;
        exit;
    }

    // behaviour graph
    function getBehaviourData() {
        $data['footerJs'][0] = base_url('uploads/custom/js/cse/cse_report.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');

        $yp_ID = $this->input->post('yp_id');
        $yp_id = !empty($yp_ID) ? $yp_ID : '';
        $data['yp_id'] = $yp_id;

        //get YP information
        $match = array("yp_id" => $yp_id);
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
        if (empty($data['YP_details'])) {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson');
        }

        $selected_year = date('Y');
        $getYear = $this->input->post('year');
        $year = !empty($getYear) ? $getYear : '';
        $data['year'] = $year;

        $selected_month = date('m');
        $getMonth = $this->input->post('month');
        $month = !empty($getMonth) ? $getMonth : '';
        $data['month'] = $month;

        // get data from nfc_cse_recordsheet table 
        if (!empty($month) && !empty($year)) {
            $matchRecord = "is_delete = '0' and YEAR(created_date) = '" . $year . "' and MONTH(created_date) = '" . $month . "' and yp_id = " . $yp_id;
        } else if (!empty($year)) {
            $matchRecord = "is_delete = '0' and YEAR(created_date) = '" . $year . "' and yp_id = " . $yp_id;
        } else if (!empty($month)) {
            $matchRecord = "is_delete = '0' and MONTH(created_date) = '" . $month . "' and yp_id = " . $yp_id;
        } else {
            $matchRecord = "is_delete = '0'  and yp_id = " . $yp_id;
        }

        /*
  @Author : Ritesh rana
  @Desc   : Get past care home data As per create date and move date
  @Input  : create date and pmove data
  @Output : CSE report data
  @Date   : 26/09/2018
*/
        if($this->input->post('PastCareId') > 0){
            $movedate = $this->input->post('movedate');
            $CreatedDate = $this->input->post('CreatedDate');
            $where_date = "created_date BETWEEN  '".$CreatedDate."' AND '".$movedate."'";
            $fieldsRecord = array("id");
            $data['record_details'] = $this->common_model->get_records(NFC_CSE_RECORD, $fieldsRecord, '', '', $matchRecord,'','','','','','','','','','','','',$where_date);
        }else{
            $fieldsRecord = array("id");
            $data['record_details'] = $this->common_model->get_records(NFC_CSE_RECORD, $fieldsRecord, '', '', $matchRecord);
        }

        $record_details = $data['record_details'];

        // Behaviour graph data
        //get question id from nfc_admin_cse table
        $matchQue = "is_delete = '0' and que = 'Behaviour'";
        $fieldsQue = array("cse_que_id");
        $data['que_details'] = $this->common_model->get_records(NFC_ADMIN_CSE, $fieldsQue, '', '', $matchQue);
        $queDetails = $data['que_details'];
        $que_id = $queDetails[0]['cse_que_id'];

        foreach ($record_details as $result) {
            $matchData = "is_delete = 0 and ans = 'h' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsData = array("count(ans) as total");
            $data['ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsData, '', '', $matchData);
            $high[] = $data['ans_details'][0]['total'];

            $matchDataM = "is_delete = 0 and ans = 'm' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsDataM = array("count(ans) as med_total");
            $data['m_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataM, '', '', $matchDataM);
            $medium[] = $data['m_ans_details'][0]['med_total'];

            $matchDataL = "is_delete = 0 and ans = 'l' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsDataL = array("count(ans) as low_total");
            $data['l_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataL, '', '', $matchDataL);
            $low[] = $data['l_ans_details'][0]['low_total'];

            $matchDataN = "is_delete = 0 and ans = 'n' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsDataN = array("count(ans) as no_known_total");
            $data['n_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataN, '', '', $matchDataN);
            $no_known[] = $data['n_ans_details'][0]['no_known_total'];
        }
        //behaviour series data

        $highVal = array_sum($high);
        $medVal = array_sum($medium);
        $lowVal = array_sum($low);
        $no_knownVal = array_sum($no_known);
        $total = $highVal + $medVal + $lowVal + $no_knownVal;

        echo $highVal . "," . $medVal . "," . $lowVal . "," . $no_knownVal . "," . $total;
        exit;
    }

    // Grooming graph
    function getGroomData() {
        $data['footerJs'][0] = base_url('uploads/custom/js/cse/cse_report.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');

        $yp_ID = $this->input->post('yp_id');
        $yp_id = !empty($yp_ID) ? $yp_ID : '';
        $data['yp_id'] = $yp_id;

        //get YP information
        $match = array("yp_id" => $yp_id);
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
        if (empty($data['YP_details'])) {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson');
        }

        $selected_year = date('Y');
        $getYear = $this->input->post('year');
        $year = !empty($getYear) ? $getYear : '';
        $data['year'] = $year;

        $selected_month = date('m');
        $getMonth = $this->input->post('month');
        $month = !empty($getMonth) ? $getMonth : '';
        $data['month'] = $month;

        // get data from nfc_cse_recordsheet table 
        if (!empty($month) && !empty($year)) {
            $matchRecord = "is_delete = '0' and YEAR(created_date) = '" . $year . "' and MONTH(created_date) = '" . $month . "' and yp_id = " . $yp_id;
        } else if (!empty($year)) {
            $matchRecord = "is_delete = '0' and YEAR(created_date) = '" . $year . "' and yp_id = " . $yp_id;
        } else if (!empty($month)) {
            $matchRecord = "is_delete = '0' and MONTH(created_date) = '" . $month . "' and yp_id = " . $yp_id;
        } else {
            $matchRecord = "is_delete = '0'  and yp_id = " . $yp_id;
        }


              /*
  @Author : Ritesh rana
  @Desc   : Get past care home data As per create date and move date
  @Input  : create date and pmove data
  @Output : CSE report data
  @Date   : 26/09/2018
*/
        if($this->input->post('PastCareId') > 0){
            $movedate = $this->input->post('movedate');
            $CreatedDate = $this->input->post('CreatedDate');
            $where_date = "created_date BETWEEN  '".$CreatedDate."' AND '".$movedate."'";
            $fieldsRecord = array("id");
            $data['record_details'] = $this->common_model->get_records(NFC_CSE_RECORD, $fieldsRecord, '', '', $matchRecord,'','','','','','','','','','','','',$where_date);
        }else{
            $fieldsRecord = array("id");
            $data['record_details'] = $this->common_model->get_records(NFC_CSE_RECORD, $fieldsRecord, '', '', $matchRecord);
        }

        $record_details = $data['record_details'];

        // Groom graph data
        //get question id from nfc_admin_cse table
        $matchQue = "is_delete = '0' and que = 'Grooming'";
        $fieldsQue = array("cse_que_id");
        $data['que_details'] = $this->common_model->get_records(NFC_ADMIN_CSE, $fieldsQue, '', '', $matchQue);
        $queDetails = $data['que_details'];
        $que_id = $queDetails[0]['cse_que_id'];

        foreach ($record_details as $result) {
            $matchData = "is_delete = 0 and ans = 'h' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsData = array("count(ans) as total");
            $data['ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsData, '', '', $matchData);
            $high[] = $data['ans_details'][0]['total'];

            $matchDataM = "is_delete = 0 and ans = 'm' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsDataM = array("count(ans) as med_total");
            $data['m_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataM, '', '', $matchDataM);
            $medium[] = $data['m_ans_details'][0]['med_total'];

            $matchDataL = "is_delete = 0 and ans = 'l' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsDataL = array("count(ans) as low_total");
            $data['l_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataL, '', '', $matchDataL);
            $low[] = $data['l_ans_details'][0]['low_total'];

            $matchDataN = "is_delete = 0 and ans = 'n' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsDataN = array("count(ans) as no_known_total");
            $data['n_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataN, '', '', $matchDataN);
            $no_known[] = $data['n_ans_details'][0]['no_known_total'];
        }
        //Grooming series data
        $highVal = array_sum($high);
        $medVal = array_sum($medium);
        $lowVal = array_sum($low);
        $no_knownVal = array_sum($no_known);
        $total = $highVal + $medVal + $lowVal + $no_knownVal;

        echo $highVal . "," . $medVal . "," . $lowVal . "," . $no_knownVal . "," . $total;
        exit;
    }

    // looked after children graph
    function getLookChildData() {
        $data['footerJs'][0] = base_url('uploads/custom/js/cse/cse_report.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');

        $yp_ID = $this->input->post('yp_id');
        $yp_id = !empty($yp_ID) ? $yp_ID : '';
        $data['yp_id'] = $yp_id;

        //get YP information
        $match = array("yp_id" => $yp_id);
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
        if (empty($data['YP_details'])) {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson');
        }

        $selected_year = date('Y');
        $getYear = $this->input->post('year');
        $year = !empty($getYear) ? $getYear : '';
        $data['year'] = $year;

        $selected_month = date('m');
        $getMonth = $this->input->post('month');
        $month = !empty($getMonth) ? $getMonth : '';
        $data['month'] = $month;

        // get data from nfc_cse_recordsheet table 
        if (!empty($month) && !empty($year)) {
            $matchRecord = "is_delete = '0' and YEAR(created_date) = '" . $year . "' and MONTH(created_date) = '" . $month . "' and yp_id = " . $yp_id;
        } else if (!empty($year)) {
            $matchRecord = "is_delete = '0' and YEAR(created_date) = '" . $year . "' and yp_id = " . $yp_id;
        } else if (!empty($month)) {
            $matchRecord = "is_delete = '0' and MONTH(created_date) = '" . $month . "' and yp_id = " . $yp_id;
        } else {
            $matchRecord = "is_delete = '0'  and yp_id = " . $yp_id;
        }


              /*
  @Author : Ritesh rana
  @Desc   : Get past care home data As per create date and move date
  @Input  : create date and pmove data
  @Output : CSE report data
  @Date   : 26/09/2018
*/
        if($this->input->post('PastCareId') > 0){
            $movedate = $this->input->post('movedate');
            $CreatedDate = $this->input->post('CreatedDate');
            $where_date = "created_date BETWEEN  '".$CreatedDate."' AND '".$movedate."'";
            $fieldsRecord = array("id");
            $data['record_details'] = $this->common_model->get_records(NFC_CSE_RECORD, $fieldsRecord, '', '', $matchRecord,'','','','','','','','','','','','',$where_date);
        }else{
            $fieldsRecord = array("id");
            $data['record_details'] = $this->common_model->get_records(NFC_CSE_RECORD, $fieldsRecord, '', '', $matchRecord);
        }

        $record_details = $data['record_details'];

        // look after children graph data
        //get question id from nfc_admin_cse table
        $matchQue = "is_delete = '0' and que = 'Looked After Children'";
        $fieldsQue = array("cse_que_id");
        $data['que_details'] = $this->common_model->get_records(NFC_ADMIN_CSE, $fieldsQue, '', '', $matchQue);
        $queDetails = $data['que_details'];
        $que_id = $queDetails[0]['cse_que_id'];

        foreach ($record_details as $result) {
            $matchData = "is_delete = 0 and ans = 'h' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsData = array("count(ans) as total");
            $data['ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsData, '', '', $matchData);
            $high[] = $data['ans_details'][0]['total'];

            $matchDataM = "is_delete = 0 and ans = 'm' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsDataM = array("count(ans) as med_total");
            $data['m_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataM, '', '', $matchDataM);
            $medium[] = $data['m_ans_details'][0]['med_total'];

            $matchDataL = "is_delete = 0 and ans = 'l' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsDataL = array("count(ans) as low_total");
            $data['l_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataL, '', '', $matchDataL);
            $low[] = $data['l_ans_details'][0]['low_total'];

            $matchDataN = "is_delete = 0 and ans = 'n' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsDataN = array("count(ans) as no_known_total");
            $data['n_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataN, '', '', $matchDataN);
            $no_known[] = $data['n_ans_details'][0]['no_known_total'];
        }
        //look after children series data
        $highVal = array_sum($high);
        $medVal = array_sum($medium);
        $lowVal = array_sum($low);
        $no_knownVal = array_sum($no_known);
        $total = $highVal + $medVal + $lowVal + $no_knownVal;

        echo $highVal . "," . $medVal . "," . $lowVal . "," . $no_knownVal . "," . $total;
        exit;
    }

    // Family and social  graph
    function getFamilySocialdData() {
        $data['footerJs'][0] = base_url('uploads/custom/js/cse/cse_report.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');

        $yp_ID = $this->input->post('yp_id');
        $yp_id = !empty($yp_ID) ? $yp_ID : '';
        $data['yp_id'] = $yp_id;

        //get YP information
        $match = array("yp_id" => $yp_id);
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
        if (empty($data['YP_details'])) {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson');
        }

        $selected_year = date('Y');
        $getYear = $this->input->post('year');
        $year = !empty($getYear) ? $getYear : '';
        $data['year'] = $year;

        $selected_month = date('m');
        $getMonth = $this->input->post('month');
        $month = !empty($getMonth) ? $getMonth : '';
        $data['month'] = $month;

        // get data from nfc_cse_recordsheet table 
        if (!empty($month) && !empty($year)) {
            $matchRecord = "is_delete = '0' and YEAR(created_date) = '" . $year . "' and MONTH(created_date) = '" . $month . "' and yp_id = " . $yp_id;
        } else if (!empty($year)) {
            $matchRecord = "is_delete = '0' and YEAR(created_date) = '" . $year . "' and yp_id = " . $yp_id;
        } else if (!empty($month)) {
            $matchRecord = "is_delete = '0' and MONTH(created_date) = '" . $month . "' and yp_id = " . $yp_id;
        } else {
            $matchRecord = "is_delete = '0'  and yp_id = " . $yp_id;
        }

        /*
          @Author : Ritesh rana
          @Desc   : Get past care home data As per create date and move date
          @Input  : create date and pmove data
          @Output : CSE report data
          @Date   : 26/09/2018
        */
        if($this->input->post('PastCareId') > 0){
            $movedate = $this->input->post('movedate');
            $CreatedDate = $this->input->post('CreatedDate');
            $where_date = "created_date BETWEEN  '".$CreatedDate."' AND '".$movedate."'";
            $fieldsRecord = array("id");
            $data['record_details'] = $this->common_model->get_records(NFC_CSE_RECORD, $fieldsRecord, '', '', $matchRecord,'','','','','','','','','','','','',$where_date);
        }else{
            $fieldsRecord = array("id");
            $data['record_details'] = $this->common_model->get_records(NFC_CSE_RECORD, $fieldsRecord, '', '', $matchRecord);
        }

        $record_details = $data['record_details'];

        // family and social graph data
        //get question id from nfc_admin_cse table
        $matchQue = "is_delete = '0' and que = 'Family and Social'";
        $fieldsQue = array("cse_que_id");
        $data['que_details'] = $this->common_model->get_records(NFC_ADMIN_CSE, $fieldsQue, '', '', $matchQue);
        $queDetails = $data['que_details'];
        $que_id = $queDetails[0]['cse_que_id'];

        foreach ($record_details as $result) {
            $matchData = "is_delete = 0 and ans = 'h' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsData = array("count(ans) as total");
            $data['ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsData, '', '', $matchData);
            $high[] = $data['ans_details'][0]['total'];

            $matchDataM = "is_delete = 0 and ans = 'm' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsDataM = array("count(ans) as med_total");
            $data['m_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataM, '', '', $matchDataM);
            $medium[] = $data['m_ans_details'][0]['med_total'];

            $matchDataL = "is_delete = 0 and ans = 'l' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsDataL = array("count(ans) as low_total");
            $data['l_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataL, '', '', $matchDataL);
            $low[] = $data['l_ans_details'][0]['low_total'];

            $matchDataN = "is_delete = 0 and ans = 'n' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsDataN = array("count(ans) as no_known_total");
            $data['n_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataN, '', '', $matchDataN);
            $no_known[] = $data['n_ans_details'][0]['no_known_total'];
        }
        //family and social series data
        $highVal = array_sum($high);
        $medVal = array_sum($medium);
        $lowVal = array_sum($low);
        $no_knownVal = array_sum($no_known);
        $total = $highVal + $medVal + $lowVal + $no_knownVal;

        echo $highVal . "," . $medVal . "," . $lowVal . "," . $no_knownVal . "," . $total;
        ;
        exit;
    }

    // E safety graph
    function getEsafetyData() {
        $data['footerJs'][0] = base_url('uploads/custom/js/cse/cse_report.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');

        $yp_ID = $this->input->post('yp_id');
        $yp_id = !empty($yp_ID) ? $yp_ID : '';
        $data['yp_id'] = $yp_id;

        //get YP information
        $match = array("yp_id" => $yp_id);
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
        if (empty($data['YP_details'])) {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson');
        }

        $selected_year = date('Y');
        $getYear = $this->input->post('year');
        $year = !empty($getYear) ? $getYear : '';
        $data['year'] = $year;

        $selected_month = date('m');
        $getMonth = $this->input->post('month');
        $month = !empty($getMonth) ? $getMonth : '';
        $data['month'] = $month;

        // get data from nfc_cse_recordsheet table 
        if (!empty($month) && !empty($year)) {
            $matchRecord = "is_delete = '0' and YEAR(created_date) = '" . $year . "' and MONTH(created_date) = '" . $month . "' and yp_id = " . $yp_id;
        } else if (!empty($year)) {
            $matchRecord = "is_delete = '0' and YEAR(created_date) = '" . $year . "' and yp_id = " . $yp_id;
        } else if (!empty($month)) {
            $matchRecord = "is_delete = '0' and MONTH(created_date) = '" . $month . "' and yp_id = " . $yp_id;
        } else {
            $matchRecord = "is_delete = '0'  and yp_id = " . $yp_id;
        }

        /*
          @Author : Ritesh rana
          @Desc   : Get past care home data As per create date and move date
          @Input  : create date and pmove data
          @Output : CSE report data
          @Date   : 26/09/2018
        */
        if($this->input->post('PastCareId') > 0){
            $movedate = $this->input->post('movedate');
            $CreatedDate = $this->input->post('CreatedDate');
            $where_date = "created_date BETWEEN  '".$CreatedDate."' AND '".$movedate."'";
            $fieldsRecord = array("id");
            $data['record_details'] = $this->common_model->get_records(NFC_CSE_RECORD, $fieldsRecord, '', '', $matchRecord,'','','','','','','','','','','','',$where_date);
        }else{
            $fieldsRecord = array("id");
            $data['record_details'] = $this->common_model->get_records(NFC_CSE_RECORD, $fieldsRecord, '', '', $matchRecord);
        }

         $fieldsRecord = array("id");
            $data['record_details'] = $this->common_model->get_records(NFC_CSE_RECORD, $fieldsRecord, '', '', $matchRecord);
        $record_details = $data['record_details'];

        // E safety graph data
        //get question id from nfc_admin_cse table
        $matchQue = "is_delete = '0' and que = 'E Safety'";
        $fieldsQue = array("cse_que_id");
        $data['que_details'] = $this->common_model->get_records(NFC_ADMIN_CSE, $fieldsQue, '', '', $matchQue);
        $queDetails = $data['que_details'];
        $que_id = $queDetails[0]['cse_que_id'];

        foreach ($record_details as $result) {
            $matchData = "is_delete = 0 and ans = 'h' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsData = array("count(ans) as total");
            $data['ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsData, '', '', $matchData);
            $high[] = $data['ans_details'][0]['total'];

            $matchDataM = "is_delete = 0 and ans = 'm' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsDataM = array("count(ans) as med_total");
            $data['m_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataM, '', '', $matchDataM);
            $medium[] = $data['m_ans_details'][0]['med_total'];

            $matchDataL = "is_delete = 0 and ans = 'l' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsDataL = array("count(ans) as low_total");
            $data['l_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataL, '', '', $matchDataL);
            $low[] = $data['l_ans_details'][0]['low_total'];

            $matchDataN = "is_delete = 0 and ans = 'n' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsDataN = array("count(ans) as no_known_total");
            $data['n_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataN, '', '', $matchDataN);
            $no_known[] = $data['n_ans_details'][0]['no_known_total'];
        }
        //E Safety series data
        $highVal = array_sum($high);
        $medVal = array_sum($medium);
        $lowVal = array_sum($low);
        $no_knownVal = array_sum($no_known);
        $total = $highVal + $medVal + $lowVal + $no_knownVal;

        echo $highVal . "," . $medVal . "," . $lowVal . "," . $no_knownVal . "," . $total;
        exit;
    }

// generate pdf
    function generatePDF($id) {
        $data['footerJs'][0] = base_url('uploads/custom/js/cse/cse_report.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');

        $yp_ID = $this->input->post('yp_id');
        if (!empty($id)) {
            $yp_id = !empty($yp_ID) ? $yp_ID : $id;
        } else {
            $yp_id = !empty($yp_ID) ? $yp_ID : '';
        }
        $data['yp_id'] = $yp_id;

        //get YP information
        $match = array("yp_id" => $yp_id);
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
        if (empty($data['YP_details'])) {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson');
        }


        $selected_year = date('Y');
        $getYear = $this->input->post('year');
        $year = !empty($getYear) ? $getYear : '';
        $data['year'] = $year;
        $data['year_pdf'] = $year;

        $selected_month = date('m');
        $getMonth = $this->input->post('month');
        $month = !empty($getMonth) ? $getMonth : '';
        $data['month'] = $month;

        


        // get data from nfc_cse_recordsheet table 
        if (!empty($month) && !empty($year)) {

            $matchRecord = "is_delete = '0' and YEAR(created_date) = '" . $year . "' and MONTH(created_date) = '" . $month . "' and yp_id = " . $yp_id;
        } else if (!empty($year)) {

            $matchRecord = "is_delete = '0' and YEAR(created_date) = '" . $year . "' and yp_id = " . $yp_id;
        } else if (!empty($month)) {

            $matchRecord = "is_delete = '0' and MONTH(created_date) = '" . $month . "' and yp_id = " . $yp_id;
        } else {

            $matchRecord = "is_delete = '0'  and yp_id = " . $yp_id;
        }

/*
  @Author : Ritesh rana
  @Desc   : Get past care home data As per create date and move date
  @Input  : create date and pmove data
  @Output : CSE report data
  @Date   : 25/09/2018
 */
     if($this->input->post('past_care_id') > 0){
        $movedate = $this->input->post('movedate');
        $CreatedDate = $this->input->post('created_date');
        $where_date = "created_date BETWEEN  '".$CreatedDate."' AND '".$movedate."'";
        $fieldsRecord = array("id");
        $data['record_details'] = $this->common_model->get_records(NFC_CSE_RECORD, $fieldsRecord, '', '', $matchRecord,'','','','','','','','','','','','',$where_date);
    }else{
        $fieldsRecord = array("id");
        $data['record_details'] = $this->common_model->get_records(NFC_CSE_RECORD, $fieldsRecord, '', '', $matchRecord);
    }
 $record_details = $data['record_details'];


        if (!empty($record_details)) {
            //line chart 
            $total_indicators_year_score = $this->CseReport_model->getlineYear($yp_id);
            $total_indicators_month_score = $this->CseReport_model->getlineMonth($yp_id, $year);

            $start = $total_indicators_year_score[0]['min_year'];
            $end = $total_indicators_year_score[0]['max_year'];
            $getRangeYear = range(date('Y', strtotime($start)), date('Y', strtotime($end)));

            if (!empty($getYear)) {
                foreach ($total_indicators_month_score as $key => $score) {
                    if (!empty($score['user_type'])) {
                        $total_score = ($score['total_score_h'] * 3) + ($score['total_score_m'] * 2) + ($score['total_score_l'] * 1) + ($score['total_score_n'] * 0);

                        $dayData = gmmktime(0, 0, 0, $score['month'], $score['day'], $score['year']);
                        $dayVal = $dayData * 1000;
                        $total_indicators1[$score['user_type']][$score['day']] = [$dayVal, $total_score];
                    }
                }
            } else {

                foreach ($total_indicators_year_score as $key => $data) {
                    if (!empty($data['user_type'])) {
                        $total_indicators_score = $this->CseReport_model->getlineYearData($yp_id, $data['year'], $data['user_type']);
                        foreach ($total_indicators_score as $key => $score) {
                            $total_score_data = ($score['h_score'] * 3) + ($score['m_score'] * 2) + ($score['l_score'] * 1) + ($score['n_score'] * 0);
                            $total_score = $total_score_data / $score['total_count'];
                            $total_indicators1[$data['user_type']][$data['year']] = [(int) $data['year'], $total_score];
                        }
                    }
                }
            }

            $data['total_indicators_data'] = isset($total_indicators1) ? $total_indicators1 : '';
            $total_indicators_data = $data['total_indicators_data'];

            if (!empty($total_indicators_data)) {
                foreach ($total_indicators_data as $user_type => $total_score) {
                    $high['series'][] = array('name' => $user_type, 'data' => array_values($total_score));
                }
            }
            $totalindicators_series = isset($high['series']) ? $high['series'] : '';
            $data['line_cse_chartName'] = 'cse_line_score';

            //export pdf line graph
            if (!empty($getYear)) {
                $this->pdfExportLineGraph($totalindicators_series, $data['line_cse_chartName']);
            } else {
                $this->pdfExportYearLineGraph($totalindicators_series, $data['line_cse_chartName']);
            }


            //record report chart
            // total graph data
            //get question id from nfc_admin_cse table
            foreach ($record_details as $result) {
                $matchData = "is_delete = 0 and ans = 'h' and record_id = " . $result['id'];
                $fieldsData = array("count(ans) as total");
                $data['ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsData, '', '', $matchData);
                $highT[] = $data['ans_details'][0]['total'];

                $matchDataM = "is_delete = 0 and ans = 'm' and record_id = " . $result['id'];
                $fieldsDataM = array("count(ans) as med_total");
                $data['m_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataM, '', '', $matchDataM);
                $mediumT[] = $data['m_ans_details'][0]['med_total'];

                $matchDataL = "is_delete = 0 and ans = 'l' and record_id = " . $result['id'];
                $fieldsDataL = array("count(ans) as low_total");
                $data['l_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataL, '', '', $matchDataL);
                $lowT[] = $data['l_ans_details'][0]['low_total'];

                $matchDataN = "is_delete = 0 and ans = 'n' and record_id = " . $result['id'];
                $fieldsDataN = array("count(ans) as no_known_total");
                $data['n_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataN, '', '', $matchDataN);
                $no_knownT[] = $data['n_ans_details'][0]['no_known_total'];
            }
            //total indicators series data
            $highValT = array_sum($highT);
            $medValT = array_sum($mediumT);
            $lowValT = array_sum($lowT);
            $no_knownValT = array_sum($no_knownT);
            $total_pie_data = array("name" => "true", "colorByPoint" => "true", "data" => array(array("name" => "High Risk", "y" => $highValT), array("name" => "Medium Risk", "y" => $medValT), array("name" => "Low Risk", "y" => $lowValT), array("name" => "No Known Risk", "y" => $no_knownValT)));
            // total indicators graph data for export pdf
            $chartTitleTotal = "Total Indicators";
            $data['pie_total_chartName'] = "pie_total_score";
            $this->pdfExportPieGraph($chartTitleTotal, $total_pie_data, $data['pie_total_chartName']);

            // Health graph data
            //get question id from nfc_admin_cse table
            $matchQue = "is_delete = '0' and que = 'Health'";
            $fieldsQue = array("cse_que_id");
            $data['que_details'] = $this->common_model->get_records(NFC_ADMIN_CSE, $fieldsQue, '', '', $matchQue);
            $queDetails = $data['que_details'];
            $que_id = $queDetails[0]['cse_que_id'];

            foreach ($record_details as $result) {
                $matchData = "is_delete = 0 and ans = 'h' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsData = array("count(ans) as total");
                $data['ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsData, '', '', $matchData);
                $high[] = $data['ans_details'][0]['total'];


                $matchDataM = "is_delete = 0 and ans = 'm' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsDataM = array("count(ans) as med_total");
                $data['m_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataM, '', '', $matchDataM);
                $medium[] = $data['m_ans_details'][0]['med_total'];

                $matchDataL = "is_delete = 0 and ans = 'l' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsDataL = array("count(ans) as low_total");
                $data['l_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataL, '', '', $matchDataL);
                $low[] = $data['l_ans_details'][0]['low_total'];

                $matchDataN = "is_delete = 0 and ans = 'n' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsDataN = array("count(ans) as no_known_total");
                $data['n_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataN, '', '', $matchDataN);
                $no_known[] = $data['n_ans_details'][0]['no_known_total'];
            }
            //health series data
            $highVal = array_sum($high);
            $medVal = array_sum($medium);
            $lowVal = array_sum($low);
            $no_knownVal = array_sum($no_known);
            $total = $highVal + $medVal + $lowVal + $no_knownVal;
            $health_pie_data = array("name" => "true", "colorByPoint" => "true", "data" => array(array("name" => "High Risk", "y" => $highVal), array("name" => "Medium Risk", "y" => $medVal), array("name" => "Low Risk", "y" => $lowVal), array("name" => "No Known Risk", "y" => $no_knownVal)));
            // Health indicators graph data for export pdf
            $chartTitleHealth = "Health";
            $data['pie_health_chartName'] = "pie_health_score";
            $this->pdfExportPieGraph($chartTitleHealth, $health_pie_data, $data['pie_health_chartName']);

            //Behaviour indicator graph data
            //get question id from nfc_admin_sdq table
            $matchQue = "is_delete = '0' and que = 'Behaviour'";
            $fieldsQue = array("cse_que_id");
            $data['que_details'] = $this->common_model->get_records(NFC_ADMIN_CSE, $fieldsQue, '', '', $matchQue);
            $queDetails = $data['que_details'];
            $que_id = $queDetails[0]['cse_que_id'];

            foreach ($record_details as $result) {
                $matchData = "is_delete = 0 and ans = 'h' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsData = array("count(ans) as total");
                $data['ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsData, '', '', $matchData);
                $highB[] = $data['ans_details'][0]['total'];

                $matchDataM = "is_delete = 0 and ans = 'm' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsDataM = array("count(ans) as med_total");
                $data['m_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataM, '', '', $matchDataM);
                $mediumB[] = $data['m_ans_details'][0]['med_total'];

                $matchDataL = "is_delete = 0 and ans = 'l' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsDataL = array("count(ans) as low_total");
                $data['l_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataL, '', '', $matchDataL);
                $lowB[] = $data['l_ans_details'][0]['low_total'];

                $matchDataN = "is_delete = 0 and ans = 'n' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsDataN = array("count(ans) as no_known_total");
                $data['n_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataN, '', '', $matchDataN);
                $no_knownB[] = $data['n_ans_details'][0]['no_known_total'];
            }
            //behaviour series data
            $highValB = array_sum($highB);
            $medValB = array_sum($mediumB);
            $lowValB = array_sum($lowB);
            $no_knownValB = array_sum($no_knownB);
            $totalB = $highValB + $medValB + $lowValB + $no_knownValB;
            $behav_pie_data = array("name" => "true", "colorByPoint" => "true", "data" => array(array("name" => "High Risk", "y" => $highValB), array("name" => "Medium Risk", "y" => $medValB), array("name" => "Low Risk", "y" => $lowValB), array("name" => "No Known Risk", "y" => $no_knownValB)));
            //behaviour indicator graph data for export pdf
            $chartTitleBehav = "Behaviour";
            $data['pie_behav_chartName'] = "pie_behav_score";
            $this->pdfExportPieGraph($chartTitleBehav, $behav_pie_data, $data['pie_behav_chartName']);

            // Grooming graph data
            //get question id from nfc_admin_cse table
            $matchQue = "is_delete = '0' and que = 'Grooming'";
            $fieldsQue = array("cse_que_id");
            $data['que_details'] = $this->common_model->get_records(NFC_ADMIN_CSE, $fieldsQue, '', '', $matchQue);
            $queDetails = $data['que_details'];
            $que_id = $queDetails[0]['cse_que_id'];

            foreach ($record_details as $result) {
                $matchData = "is_delete = 0 and ans = 'h' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsData = array("count(ans) as total");
                $data['ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsData, '', '', $matchData);
                $highG[] = $data['ans_details'][0]['total'];

                $matchDataM = "is_delete = 0 and ans = 'm' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsDataM = array("count(ans) as med_total");
                $data['m_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataM, '', '', $matchDataM);
                $mediumG[] = $data['m_ans_details'][0]['med_total'];

                $matchDataL = "is_delete = 0 and ans = 'l' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsDataL = array("count(ans) as low_total");
                $data['l_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataL, '', '', $matchDataL);
                $lowG[] = $data['l_ans_details'][0]['low_total'];

                $matchDataN = "is_delete = 0 and ans = 'n' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsDataN = array("count(ans) as no_known_total");
                $data['n_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataN, '', '', $matchDataN);
                $no_knownG[] = $data['n_ans_details'][0]['no_known_total'];
            }
            //Grooming series data
            $highValG = array_sum($highG);
            $medValG = array_sum($mediumG);
            $lowValG = array_sum($lowG);
            $no_knownValG = array_sum($no_knownG);
            $totalG = $highValG + $medValG + $lowValG + $no_knownValG;
            $groom_pie_data = array("name" => "true", "colorByPoint" => "true", "data" => array(array("name" => "High Risk", "y" => $highValG), array("name" => "Medium Risk", "y" => $medValG), array("name" => "Low Risk", "y" => $lowValG), array("name" => "No Known Risk", "y" => $no_knownValG)));

            // grooming indicators graph data for export pdf
            $chartTitleGroom = "Grooming";
            $data['pie_groom_chartName'] = "pie_groom_score";
            $this->pdfExportPieGraph($chartTitleGroom, $groom_pie_data, $data['pie_groom_chartName']);

            // look after children graph data
            //get question id from nfc_admin_cse table
            $matchQue = "is_delete = '0' and que = 'Looked After Children'";
            $fieldsQue = array("cse_que_id");
            $data['que_details'] = $this->common_model->get_records(NFC_ADMIN_CSE, $fieldsQue, '', '', $matchQue);
            $queDetails = $data['que_details'];
            $que_id = $queDetails[0]['cse_que_id'];

            foreach ($record_details as $result) {
                $matchData = "is_delete = 0 and ans = 'h' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsData = array("count(ans) as total");
                $data['ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsData, '', '', $matchData);
                $highC[] = $data['ans_details'][0]['total'];

                $matchDataM = "is_delete = 0 and ans = 'm' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsDataM = array("count(ans) as med_total");
                $data['m_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataM, '', '', $matchDataM);
                $mediumC[] = $data['m_ans_details'][0]['med_total'];

                $matchDataL = "is_delete = 0 and ans = 'l' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsDataL = array("count(ans) as low_total");
                $data['l_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataL, '', '', $matchDataL);
                $lowC[] = $data['l_ans_details'][0]['low_total'];

                $matchDataN = "is_delete = 0 and ans = 'n' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsDataN = array("count(ans) as no_known_total");
                $data['n_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataN, '', '', $matchDataN);
                $no_knownC[] = $data['n_ans_details'][0]['no_known_total'];
            }
            //look after children series data
            $highValC = array_sum($highC);
            $medValC = array_sum($mediumC);
            $lowValC = array_sum($lowC);
            $no_knownValC = array_sum($no_knownC);
            $totalC = $highValC + $medValC + $lowValC + $no_knownValC;
            $child_pie_data = array("name" => "true", "colorByPoint" => "true", "data" => array(array("name" => "High Risk", "y" => $highValC), array("name" => "Medium Risk", "y" => $medValC), array("name" => "Low Risk", "y" => $lowValC), array("name" => "No Known Risk", "y" => $no_knownValC)));

            // Looked After Children indicator graph data for export pdf
            $chartTitleChild = "Looked After Children";
            $data['pie_child_chartName'] = "pie_child_score";
            $this->pdfExportPieGraph($chartTitleChild, $child_pie_data, $data['pie_child_chartName']);

            // family and social graph data
            //get question id from nfc_admin_cse table
            $matchQue = "is_delete = '0' and que = 'Family and Social'";
            $fieldsQue = array("cse_que_id");
            $data['que_details'] = $this->common_model->get_records(NFC_ADMIN_CSE, $fieldsQue, '', '', $matchQue);
            $queDetails = $data['que_details'];
            $que_id = $queDetails[0]['cse_que_id'];

            foreach ($record_details as $result) {
                $matchData = "is_delete = 0 and ans = 'h' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsData = array("count(ans) as total");
                $data['ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsData, '', '', $matchData);
                $highS[] = $data['ans_details'][0]['total'];

                $matchDataM = "is_delete = 0 and ans = 'm' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsDataM = array("count(ans) as med_total");
                $data['m_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataM, '', '', $matchDataM);
                $mediumS[] = $data['m_ans_details'][0]['med_total'];

                $matchDataL = "is_delete = 0 and ans = 'l' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsDataL = array("count(ans) as low_total");
                $data['l_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataL, '', '', $matchDataL);
                $lowS[] = $data['l_ans_details'][0]['low_total'];

                $matchDataN = "is_delete = 0 and ans = 'n' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsDataN = array("count(ans) as no_known_total");
                $data['n_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataN, '', '', $matchDataN);
                $no_knownS[] = $data['n_ans_details'][0]['no_known_total'];
            }
            //family and social series data
            $highValS = array_sum($highS);
            $medValS = array_sum($mediumS);
            $lowValS = array_sum($lowS);
            $no_knownValS = array_sum($no_knownS);
            $totalS = $highValS + $medValS + $lowValS + $no_knownValS;
            $social_pie_data = array("name" => "true", "colorByPoint" => "true", "data" => array(array("name" => "High Risk", "y" => $highValS), array("name" => "Medium Risk", "y" => $medValS), array("name" => "Low Risk", "y" => $lowValS), array("name" => "No Known Risk", "y" => $no_knownValS)));

            // family & social indicator graph data for export pdf
            $chartTitleSoc = "Family and Social";
            $data['pie_soc_chartName'] = "pie_soc_score";
            $this->pdfExportPieGraph($chartTitleSoc, $social_pie_data, $data['pie_soc_chartName']);

            // E safety graph data
            //get question id from nfc_admin_cse table
            $matchQue = "is_delete = '0' and que = 'E Safety'";
            $fieldsQue = array("cse_que_id");
            $data['que_details'] = $this->common_model->get_records(NFC_ADMIN_CSE, $fieldsQue, '', '', $matchQue);
            $queDetails = $data['que_details'];
            $que_id = $queDetails[0]['cse_que_id'];

            foreach ($record_details as $result) {
                $matchData = "is_delete = 0 and ans = 'h' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsData = array("count(ans) as total");
                $data['ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsData, '', '', $matchData);
                $highE[] = $data['ans_details'][0]['total'];

                $matchDataM = "is_delete = 0 and ans = 'm' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsDataM = array("count(ans) as med_total");
                $data['m_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataM, '', '', $matchDataM);
                $mediumE[] = $data['m_ans_details'][0]['med_total'];

                $matchDataL = "is_delete = 0 and ans = 'l' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsDataL = array("count(ans) as low_total");
                $data['l_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataL, '', '', $matchDataL);
                $lowE[] = $data['l_ans_details'][0]['low_total'];

                $matchDataN = "is_delete = 0 and ans = 'n' and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsDataN = array("count(ans) as no_known_total");
                $data['n_ans_details'] = $this->common_model->get_records(NFC_CSE_RECORD_ANS, $fieldsDataN, '', '', $matchDataN);
                $no_knownE[] = $data['n_ans_details'][0]['no_known_total'];
            }

            //get cse comment information
        if (!empty($year) && !empty($month)) {
            $matchC = "yp_id =" . $yp_id . " and year(created_date) = " . $year . " and month(created_date) = " . $month;
        } else if (!empty($year)) {
            $matchC = "yp_id =" . $yp_id . " and year(created_date) = " . $year;
        } else if (!empty($month)) {
            $matchC = "yp_id =" . $yp_id . " and month(created_date) = " . $month;
        } else {
            $matchC = "yp_id =" . $yp_id;
        }

  /*
  @Author : Ritesh rana
  @Desc   : Get past care home data As per create date and move date
  @Input  : create date and pmove data
  @Output : CSE report comment details
  @Date   : 26/09/2018
 */
     $fieldsC = array("comment,user_type,created_date");
    if($this->input->post('past_care_id') > 0){
        $movedate = $this->input->post('movedate');
        $CreatedDate = $this->input->post('created_date');
        $where_date = "created_date BETWEEN  '".$CreatedDate."' AND '".$movedate."'";
        $data['comment_details'] = $this->common_model->get_records(NFC_CSE_RECORD, $fieldsC, '', '', $matchC,'','','','','','','','','','','','',$where_date);
        
    }else{
        $fieldsRecord = array("id");
        $data['comment_details'] = $this->common_model->get_records(NFC_CSE_RECORD, $fieldsC, '', '', $matchC);
    }


            //E Safety series data
            $highValE = array_sum($highE);
            $medValE = array_sum($mediumE);
            $lowValE = array_sum($lowE);
            $no_knownValE = array_sum($no_knownE);
            $totalE = $highValE + $medValE + $lowValE + $no_knownValE;
            $safe_pie_data = array("name" => "true", "colorByPoint" => "true", "data" => array(array("name" => "High Risk", "y" => $highValE), array("name" => "Medium Risk", "y" => $medValE), array("name" => "Low Risk", "y" => $lowValE), array("name" => "NO Known Risk", "y" => $no_knownValE)));

            // E safety indicator graph data for export pdf
            $chartTitleSafe = "E Safety";
            $data['pie_safe_chartName'] = "pie_safe_score";
            $this->pdfExportPieGraph($chartTitleSafe, $safe_pie_data, $data['pie_safe_chartName']);

            //this the the PDF filename that user will get to download
            //tred chart export pdf
            //load mPDF library
            $this->load->library('m_pdf');
            //this the the PDF filename that user will get to download
            //Set Header Footer and Content For PDF
            $PDFHeaderHTML = $this->load->view('header_pdf', $data, true);
            $PDFFooterHTML = $this->load->view('footer_pdf', $data, true);
            $this->m_pdf->pdf->mPDF('utf-8', 'A4', '', '', '0', '0', '25', '0', '0', '5');

            $this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML);
            $this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);

            //get YP information
            $match = array("yp_id" => $yp_id);
            $fields = array("*");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            //load the view and saved it into $html variable
            $data['main_content'] = '/cserecord_report_pdf';
            $html = $this->parser->parse('layouts/PdfDataTemplate', $data);
            $pdfFilePath = "CSE Record Report.pdf";

            //generate the PDF from the given html
            $this->m_pdf->pdf->WriteHTML($html);

            //download it.
            $this->m_pdf->pdf->Output($pdfFilePath, "D");
        } else {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('CseReport/index/' . $id);
        }
    }

    //pdf pie graph
    function pdfExportPieGraph($chartTitlePie, $seriesDataPie, $chartnamePie) {
        $dirPath = $this->config->item('directory_root') . "application/modules/CseReport";
        $outputPath = $this->config->item('directory_root') . "uploads/cse_pdf_export/";
        $phantomPath = $this->config->item('directory_root') . "uploads/phantomjs";
        //  graph data for export pdf
        $high = array();
        $high['chart']['type'] = "pie";
        $high['title']['text'] = $chartTitlePie;
        $high['tooltip']['pointFormat'] = '{series.name}: <b>{point.percentage:.1f}%</b>';
        $high['credits']['enabled'] = false;
        $high['plotOptions']['pie']['dataLabels']['enabled'] = 'true';
        $high['plotOptions']['pie']['dataLabels']['format'] = '{point.percentage:.1f} %';
        $high['plotOptions']['pie']['showInLegend'] = 'true';
        $high['colors'] = ['#d9534f', '#FA8128', '#5cb85c', '#4f9b4f', '#0355fd', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'];
        $high['series'][] = $seriesDataPie;
        $pie_total_chartName = $chartnamePie;

        $myfile = fopen($outputPath . "/inrep_$pie_total_chartName.json", "w") or die("Unable to open file!");
        $txt = json_encode($high);
        fwrite($myfile, $txt);
        fclose($myfile);
        $command = "C:/phantomjs/bin/phantomjs.exe $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$pie_total_chartName.json -outfile " . $outputPath . "/inrep_$pie_total_chartName.png -scale 2.5 -width 700 -constr Chart -callback $phantomPath/callback.js 2>&1";
        //$command = "/usr/local/bin/phantomjs $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$pie_total_chartName.json -outfile " . $outputPath . "/inrep_$pie_total_chartName.png -scale 2.5 -width 700 -constr Chart -callback $phantomPath/callback.js 2>&1";
        //client server path
        //$command = "/usr/bin/phantomjs $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$pie_total_chartName.json -outfile " . $outputPath . "/inrep_$pie_total_chartName.png -scale 2.5 -width 700 -constr Chart -callback $phantomPath/callback.js 2>&1";
        exec($command);
    }

    //pdf line graph
    function pdfExportLineGraph($totalindicators_series, $chartnameLine) {
        $dirPath = $this->config->item('directory_root') . "application/modules/CseReport";
        $outputPath = $this->config->item('directory_root') . "uploads/cse_pdf_export/";
        $phantomPath = $this->config->item('directory_root') . "uploads/phantomjs";
        //  graph data for export pdf
        $high = array();
        $high['chart']['type'] = "spline";
        $high['title']['text'] = 'CSE Report Graph';
        $high['xAxis']['type'] = 'datetime';
        $high['xAxis']['dateTimeLabelFormats']['month'] = '%e. %b';
        $high['xAxis']['dateTimeLabelFormats']['year'] = '%b';
        $high['xAxis']['title']['text'] = 'Month';
        $high['xAxis']['yAxis']['title']['text'] = 'Numbers';
        $high['xAxis']['yAxis']['min'] = '0';
        $high['tooltip']['headerFormat'] = '<b>{series.name}</b><br>';
        $high['tooltip']['pointFormat'] = '{point.x:%e. %b}: {point.y:.2f} m';
        $high['credits']['enabled'] = false;
        $high['plotOptions']['spline']['marker']['enabled'] = 'true';
        $high['plotOptions']['spline']['dataLabels']['enabled'] = 'true';
        $high['colors'] = ['#d9534f', '#FA8128', '#5cb85c', '#264e6e', '#0355fd', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'];
        $high['series'] = $totalindicators_series;
        $line_chartName = $chartnameLine;

        $myfile = fopen($outputPath . "/inrep_$line_chartName.json", "w") or die("Unable to open file!");
        $txt = json_encode($high);
        fwrite($myfile, $txt);
        fclose($myfile);
        //local path
        $command = "C:/phantomjs/bin/phantomjs.exe $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$line_chartName.json -outfile " . $outputPath . "/inrep_$line_chartName.png -scale 2.5 -width 700 -constr Chart -callback $phantomPath/callback.js 2>&1";
        //demo server path
        //$command = "/usr/local/bin/phantomjs $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$line_chartName.json -outfile " . $outputPath . "/inrep_$line_chartName.png -scale 2.5 -width 700 -constr Chart -callback $phantomPath/callback.js 2>&1";
        // client server path
        //$command = "/usr/bin/phantomjs $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$line_chartName.json -outfile " . $outputPath . "/inrep_$line_chartName.png -scale 2.5 -width 700 -constr Chart -callback $phantomPath/callback.js 2>&1";
        exec($command);
    }

    //year line graph
    function pdfExportYearLineGraph($totalindicators_series, $chartnameLine) {
        $dirPath = $this->config->item('directory_root') . "application/modules/CseReport";
        $outputPath = $this->config->item('directory_root') . "uploads/cse_pdf_export/";
        $phantomPath = $this->config->item('directory_root') . "uploads/phantomjs";
        //  graph data for export pdf
        $high = array();
        $high['chart']['type'] = "spline";
        $high['title']['text'] = 'CSE Report Graph';
        $high['xAxis']['type'] = 'year';
        $high['xAxis']['dateTimeLabelFormats']['year'] = '%b';
        $high['xAxis']['title']['text'] = 'Year';
        $high['xAxis']['yAxis']['title']['text'] = 'Numbers';
        $high['xAxis']['yAxis']['min'] = '0';
        $high['tooltip']['headerFormat'] = '<b>{series.name}</b><br>';
        $high['tooltip']['pointFormat'] = '{point.y:.2f}';
        $high['credits']['enabled'] = false;
        $high['plotOptions']['spline']['marker']['enabled'] = 'true';
        $high['plotOptions']['spline']['dataLabels']['enabled'] = 'true';
        $high['colors'] = ['#d9534f', '#FA8128', '#5cb85c', '#264e6e', '#0355fd', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'];
        $high['series'] = $totalindicators_series;
        $line_chartNameYear = $chartnameLine;

        $myfile = fopen($outputPath . "/inrep_$line_chartNameYear.json", "w") or die("Unable to open file!");
        $txt = json_encode($high);
        fwrite($myfile, $txt);
        fclose($myfile);
        //local path
        $command = "C:/phantomjs/bin/phantomjs.exe $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$line_chartNameYear.json -outfile " . $outputPath . "/inrep_$line_chartNameYear.png -scale 2.5 -width 700 -constr Chart -callback $phantomPath/callback.js 2>&1";
        //demo server path
        //$command = "/usr/local/bin/phantomjs $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$line_chartNameYear.json -outfile " . $outputPath . "/inrep_$line_chartNameYear -scale 2.5 -width 700 -constr Chart -callback $phantomPath/callback.js 2>&1";
        // client server path
        //$command = "/usr/bin/phantomjs $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$line_chartNameYear.json -outfile " . $outputPath . "/inrep_$line_chartNameYear.png -scale 2.5 -width 700 -constr Chart -callback $phantomPath/callback.js 2>&1";
        exec($command);
    }

    //get line graph data
    function getLineGraphData() {
        $yp_id = $this->input->post('yp_id');
        $year_post_data = $this->input->post('year');

        if (!empty($yp_id)) {

            $total_indicators_year_score = $this->CseReport_model->getlineYear($yp_id);
            $total_indicators_month_score = $this->CseReport_model->getlineMonth($yp_id, $year_post_data);

            $start = $total_indicators_year_score[0]['min_year'];
            $end = $total_indicators_year_score[0]['max_year'];
            $getRangeYear = range(date('Y', strtotime($start)), date('Y', strtotime($end)));

            if (!empty($year_post_data)) {
                foreach ($total_indicators_month_score as $key => $score) {
                    if (!empty($score['user_type'])) {
                        $total_score = ($score['total_score_h'] * 3) + ($score['total_score_m'] * 2) + ($score['total_score_l'] * 1) + ($score['total_score_n'] * 0);

                        $dayData = gmmktime(0, 0, 0, $score['month'], $score['day'], $score['year']);
                        $dayVal = $dayData * 1000;
                        $total_indicators1[$score['user_type']][$score['day']] = [$dayVal, $total_score];
                    }
                }
            } else {
                foreach ($total_indicators_year_score as $key => $data) {
                    if (!empty($data['user_type'])) {
                        $total_indicators_score = $this->CseReport_model->getlineYearData($yp_id, $data['year'], $data['user_type']);
                        foreach ($total_indicators_score as $key => $score) {
                            $total_score_data = ($score['h_score'] * 3) + ($score['m_score'] * 2) + ($score['l_score'] * 1) + ($score['n_score'] * 0);
                            $total_score = $total_score_data / $score['total_count'];
                            $total_indicators1[$data['user_type']][$data['year']] = [(int) $data['year'], $total_score];
                        }
                    }
                }
            }

            $data['total_indicators_data'] = isset($total_indicators1) ? $total_indicators1 : '';
            $total_indicators_data = $data['total_indicators_data'];

            if (!empty($total_indicators_data)) {
                foreach ($total_indicators_data as $user_type => $total_score) {
                    $high['series'][] = array('name' => $user_type, 'data' => array_values($total_score));
                }
            }
            $totalindicators_series = isset($high['series']) ? $high['series'] : '';
            //$totalindicators_xaxis = isset($yearData) ? $yearData : '';

            $returnData = array(
                // 'category' => $totalindicators_xaxis,
                'series' => $totalindicators_series,
            );

            echo json_encode($returnData);
            exit;
        }
    }

}
