<?php

/*
  @Author : Ishani dave
  @Desc   : sdq report
  @Date   : 17/10/2017
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class SdqReport extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->module = $this->uri->segment(1);
        $this->viewname = $this->uri->segment(1);
        $this->load->library(array('form_validation', 'Session'));
        $this->load->model('SdqReport_model');
    }

// index page
    public function index($id,$careHomeId=0,$isArchive=0) {
		
		 /*for care to care data by ghelani nikunj on 18/9/2018 for care to  care data get with the all previous care home*/
        if ($isArchive !== 0) {
            $temp = $this->common_model->get_records(PAST_CARE_HOME_INFO, array('move_date'), '', '', array("yp_id" => $id, "past_carehome" => $careHomeId));
            $data_care_home_detail = $this->common_model->get_records(PAST_CARE_HOME_INFO, array("*"), '', '', array("yp_id" => $id, "move_date <= " => $temp[0]['move_date']));
            $created_date = $movedate = '';
            $count_care = count($data_care_home_detail);
            if ($count_care >= 1) {
                $created_date = $data_care_home_detail[0]['enter_date'];
                $movedate = $data_care_home_detail[$count_care - 1]['move_date'];
            } elseif ($count_care == 0) {
                $created_date = $data_care_home_detail[0]['enter_date'];
                $movedate = $data_care_home_detail[0]['move_date'];
            }
        }

        /***********************/
		
		
        //get YP information
      $match = array("yp_id"=>$id);
      $fields = array("*");
      $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
	 
		
		//pr($date['current_care_home_id']);die;
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
       if($isArchive==0){
                //need to change when client will approved functionality
            $config['base_url'] = base_url() . $this->viewname . '/index/' . $id;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 4;
                $uri_segment = $this->uri->segment(4);
            }
            }else{
                
                $config['base_url'] = base_url() . $this->viewname . '/index/' . $id.'/'.$careHomeId.'/'.$isArchive;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 6;
                $uri_segment = $this->uri->segment(6);
            }
                
               
            }
       //$uri_segment_a = $page;
	  if($isArchive==0){
		  
        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = NFC_SDQ_RECORD . ' as sr';
        $where = array('sr.is_delete' => '0', 'sr.status' => '1', 'sr.yp_id' => $id);
        $fields = array("c.care_home_name,sr.*, yp.yp_fname, yp.yp_lname, yp.gender, yp.profile_img");
        $join_tables = array(YP_DETAILS . ' as yp' => 'yp.yp_id = sr.yp_id',CARE_HOME . ' as c' => 'c.care_home_id= sr.care_home_id');
        if (!empty($searchtext)) {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $where_search = '(
				sr.user_type LIKE "%' . $searchtext . '%"
                                OR DATE_FORMAT(sr.created_date, "%d/%m/%Y") = "' . $searchtext . '"
				OR DATE_FORMAT(sr.modified_date, "%d/%m/%Y") = "' . $searchtext . '"
                            )';
            $data['sdq_record_list'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $where, '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);
			

            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $where, '', '', '', $sortfield, $sortby, '', $where_search, '', '', '1');
        } else {

            $data['sdq_record_list'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
			
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
        }
		}else{
			
			$login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
			$table = NFC_SDQ_RECORD . ' as sr';
			$where = array('sr.is_delete' => '0','sr.yp_id' => $id);
			$where_date="sr.created_date BETWEEN  '".$created_date."' AND '".$movedate."'";
			$fields = array("c.care_home_name,sr.*, yp.yp_fname, yp.yp_lname, yp.gender, yp.profile_img");
			$join_tables = array(YP_DETAILS . ' as yp' => 'yp.yp_id = sr.yp_id',CARE_HOME . ' as c' => 'c.care_home_id= sr.care_home_id');
			
			if (!empty($searchtext)) {
				$searchtext = html_entity_decode(trim(addslashes($searchtext)));
				$where_search = '(
					sr.user_type LIKE "%' . $searchtext . '%"
									OR DATE_FORMAT(sr.created_date, "%d/%m/%Y") = "' . $searchtext . '"
					OR DATE_FORMAT(sr.modified_date, "%d/%m/%Y") = "' . $searchtext . '"
								)';
				$data['sdq_record_list'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $where, '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);

				$config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $where, '', '', '', $sortfield, $sortby, '', $where_search, '', '', '1');
			} else {

				$data['sdq_record_list'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where,'','','','','',$where_date);
				
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
		 $data['is_archive_page'] = $isArchive;
          $data['careHomeId'] = $careHomeId;
		  $data['current_care_home_id']=$data['YP_details'][0]['care_home'];
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/ajaxlist', $data);
        } else {
            $data['main_content'] = '/' . $this->viewname;
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
    }

    //SDQ questionnaire
    function sdqQuestion($id) {

        $data['footerJs'][0] = base_url('uploads/custom/js/sdq/sdq_report.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');


        //get YP information
        $match = array("yp_id" => $id);
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
        if (empty($data['YP_details'])) {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson');
        }

        // get sd questionnaire data

        $matchSd = "is_delete = 0 and status = 1";
        $fieldsSd = array("*");
        $data['sdq_que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsSd, '', '', $matchSd);


        $data['ypid'] = !empty($id) ? $id : '';
        $data['crnt_view'] = $this->viewname;
        $user_id = $this->session->userdata('LOGGED_IN')['ID'];

        $data['main_content'] = '/sdq_question';
        $data['header'] = array('menu_module' => 'YoungPerson');
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }

    // save sdq report data

    function savedata() {

        $yp_id = $this->input->post('yp_id');
		/*ghelani nikunj
		25/9/2018
		care to care archive need get data from ypid
		*/
		$match = array("yp_id" => $yp_id);
		$fields = array("care_home");
		$data_yp_detail['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
		
        $user_type = $this->input->post('user_type');
        $created_date = date("Y-m-d");

        $check_record = $this->SdqReport_model->checkRecord($yp_id, $user_type, $created_date);
        if (count($check_record) > 0) {
            $msg = $this->lang->line('record_exist');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect('/' . $this->viewname . '/index/' . $yp_id);
        } else {
            $addData['yp_id'] = $yp_id;
            $addData['user_type'] = $this->input->post('user_type');
            $addData['total_score'] = $this->input->post('total_score');
            $addData['created_date'] = date("Y-m-d H:i:s");
            $addData['modified_date'] = date("Y-m-d H:i:s");
            $addData['status'] = 1;
            $addData['is_delete'] = 0;
			$addData['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];

            $table = NFC_SDQ_RECORD;
            $data['add_data'] = $this->common_model->insert($table, $addData);
            //Insert log activity
            $activity = array(
            'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
            'module_name'         => SDQ_MODULE,
            'module_field_name'   => '',
            'yp_id'   => $yp_id,
            'type'                => 1
            );
            log_activity($activity);
            $record_id = $data['add_data'];
            $sub_que_id = $_POST['sub_que_id'];

            // save assesment record to nfc_record_ans table
            foreach ($sub_que_id as $row) {

                $tableSub = NFC_ADMIN_SDQ_SUB . ' as su';
                $matchSub = "is_delete = 0 and status = 1 and sdq_sub_que_id =" . $row;
                $fieldsSub = array("su.parent_que_id");
                $data['sdq_sub_que_details'] = $this->common_model->get_records($tableSub, $fieldsSub, '', '', $matchSub);

                $sub_que_data = $data['sdq_sub_que_details'];
                $que_id = $sub_que_data[0]['parent_que_id'];

                $savedata['record_id'] = $record_id;
                $savedata['que_id'] = $que_id;
                $savedata['sub_que_id'] = $row;
                $ans_data = ($this->input->post('ans_' . $row)) ? $this->input->post('ans_' . $row) : '';
                $savedata['ans'] = $ans_data;
                $savedata['is_delete'] = 0;
                $savedata['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];

                $table = NFC_SDQ_RECORD_ANS;
                $data['save_data'] = $this->common_model->insert($table, $savedata);
            }
            $data['total_score'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS . ' as sa', array('sum(sa.ans) as sum'), '', '', "sa.que_id != '5' and sa.record_id=" . $record_id . "");
            $total_score = $data['total_score'];
            //echo $total_score[0]['sum'];
            $msg = $this->lang->line('record_submit');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect('/' . $this->viewname . '/index/' . $yp_id);
        }
    }

    function sdqsample() {
        $data['main_content'] = '/' . $this->viewname . '/SdqReport_sample';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }

    // SDQ Report page 

    function SdqRecordReport($id,$careHomeId=0,$isArchive=0) {
		//,$careHomeId=0,$isArchive=0
		$data['is_archive_page'] = $isArchive;
        $data['careHomeId'] = $careHomeId;
        $data['footerJs'][0] = base_url('uploads/custom/js/sdq/sdq_report.js');
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
        $year = !empty($getYear) ? $getYear : $selected_year;
        $data['year'] = $year;


        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['main_content'] = '/sdqrecord_report';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }

    // Edit sdq questionnaire

    function edit($yp_id, $id) {
        $data['footerJs'][0] = base_url('uploads/custom/js/sdq/sdq_report.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');
        $data['footerJs'][0] = base_url('uploads/custom/js/sdq/sdq_report.js');
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

            // get sd questionnaire data

            $matchSd = "is_delete = 0 and status = 1";
            $fieldsSd = array("*");
            $data['sdq_que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsSd, '', '', $matchSd);

            // get assesment data
            $tableRecord = NFC_SDQ_RECORD . ' as sr';
            $matchR = "sr.is_delete = '0' and sr.id = " . $id;
            $fieldsR = array("sr.user_type, sr.id, sr.total_score");
            $data['editRecord'] = $this->common_model->get_records($tableRecord, $fieldsR, '', '', $matchR);

            $record_id = $data['editRecord'][0]['id'];

            $tableAns = NFC_SDQ_RECORD_ANS;
            $matchA = "is_delete = '0' and record_id = " . $record_id;
            $fieldsA = array("*");
            $data['sdq_record_ans'] = $this->common_model->get_records($tableAns, $fieldsA, '', '', $matchA);


            $data['ypid'] = !empty($yp_id) ? $yp_id : '';
            $data['crnt_view'] = $this->viewname;
            $user_id = $this->session->userdata('LOGGED_IN')['ID'];

            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/sdq_question';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
    }

    //update sdq report data
    function updatedata() {
        $yp_Id = $this->input->post('yp_id');
        $yp_id = isset($yp_Id) ? $yp_Id : '';
        $record_id = $this->input->post('record_id');
        $updateData['user_type'] = $this->input->post('user_type');
        $updateData['total_score'] = $this->input->post('total_score');
        $updateData['modified_date'] = date("Y-m-d H:i:s");

        //update nfc_sdq_recordsheetdata
        $table = NFC_SDQ_RECORD;
        $strWhere = "is_delete = 0 and yp_id = '" . $yp_id . "' and id = '" . $record_id . "' ";
        $data['update_sub_que'] = $this->common_model->update($table, $updateData, $strWhere);
        //Insert log activity
        $activity = array(
        'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
        'module_name'         => SDQ_MODULE,
        'module_field_name'   => '',
        'yp_id'   => $yp_id,
        'type'                => 2
        );
        log_activity($activity);
        $sub_que_id = $_POST['sub_que_id'];

        //delete sub que data
        $tableSub = NFC_SDQ_RECORD_ANS;
        $strWhereSub = "is_delete = 0 and record_id =" . $record_id;
        $data['update_data'] = $this->common_model->delete($tableSub, $strWhereSub);

        // save assesment record to nfc_record_ans table
        foreach ($sub_que_id as $row) {

            $tableSub = NFC_ADMIN_SDQ_SUB . ' as su';
            $matchSub = "is_delete = 0 and status = 1 and sdq_sub_que_id =" . $row;
            $fieldsSub = array("su.parent_que_id");
            $data['sdq_sub_que_details'] = $this->common_model->get_records($tableSub, $fieldsSub, '', '', $matchSub);

            $sub_que_data = $data['sdq_sub_que_details'];
            $que_id = $sub_que_data[0]['parent_que_id'];

            $savedata['record_id'] = $record_id;
            $savedata['que_id'] = $que_id;
            $savedata['sub_que_id'] = $row;
            $ans_data = ($this->input->post('ans_' . $row)) ? $this->input->post('ans_' . $row) : '';
            $savedata['ans'] = $ans_data;
            $savedata['is_delete'] = 0;

            $table = NFC_SDQ_RECORD_ANS;
            $data['save_data'] = $this->common_model->insert($table, $savedata);
        }
        $data['total_score'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS . ' as sa', array('sum(sa.ans) as sum'), '', '', "sa.que_id != '5' and sa.record_id=" . $record_id . "");
        $total_score = $data['total_score'];
        //echo $total_score[0]['sum'];
        $msg = $this->lang->line('record_edit');
        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        redirect('/' . $this->viewname . '/index/' . $yp_id);
    }

//get overall total score
    function getValsum() {

        $sub_que_id = $_POST['sub_que_id'];

        $sum = 0;
        foreach ($sub_que_id as $row) {
            $ans_data = ($this->input->post('ans_' . $row)) ? $this->input->post('ans_' . $row) : '';
            $sum+= $ans_data;
        }
        echo $sum;
        exit;
    }

    //total difficulty graph data
    function getTotalDiffData() {
        $data['footerJs'][0] = base_url('uploads/custom/js/sdq/sdq_report.js');
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
        $year = !empty($getYear) ? $getYear : $selected_year;
        $data['year'] = $year;
        // total difficulty graph data
        // get data from nfc_sdq_recordsheet table 
        $matchRecord = "is_delete = 0 and yp_id = " . $yp_id;
        $fieldsRecord = array("id");
        $data['record_details'] = $this->common_model->get_records(NFC_SDQ_RECORD, $fieldsRecord, '', '', $matchRecord);
        $record_details = $data['record_details'];

        //get prosocial scale id from nfc_admin_sdq table
        $matchQuePros = "is_delete = 0 and que = 'Prosocial Scale'";
        $fieldsQuePros = array("sdq_que_id");
        $data['pro_que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsQuePros, '', '', $matchQuePros);
        $queDetailsPros = $data['pro_que_details'];
        $pros_que_id = $queDetailsPros[0]['sdq_que_id'];

        // total difficulty graph data
        $total_diff_score = array();
        foreach ($record_details as $result) {

            $total_diff_score[] = $this->SdqReport_model->getTotDifData($result['id'], $year, $pros_que_id);
        }

        foreach ($total_diff_score as $key => $score) {
            $monthNum[] = $score[0]['date'];
            $monthName = $monthNum;
            $month_name = array_keys(array_flip($monthName));
            if (!empty($score[0]['user_type'])) {
                foreach ($month_name as $month) {
                    if (!isset($total_diffarr1[$score[0]['user_type']][$month])) {
                        $total_diffarr1[$score[0]['user_type']][$month] = 0;
                    }
                }
            }
        }
        foreach ($total_diff_score as $key => $score) {
            if (!empty($score[0]['user_type'])) {
                $total_diffarr1[$score[0]['user_type']][$score[0]['date']] = $total_diffarr1[$score[0]['user_type']][$score[0]['date']] + $score[0]['total_score'];
                $month = $score[0]['date'];
                $monthNameData [] = date('d-m', strtotime($month));
                $monthData = array_keys(array_flip($monthNameData));
            }
        }
        $data['total_diff_scoredata'] = isset($total_diffarr1) ? $total_diffarr1 : '';
        $total_diff_scoredata = $data['total_diff_scoredata'];

        if (!empty($total_diff_scoredata)) {
            foreach ($total_diff_scoredata as $user_type => $total_score) {

                $high['series'][] = array('name' => $user_type, 'data' => array_values($total_score));
            }
        }
        //total deficulties scale y & x series data
        $totaldifficulties_series = isset($high['series']) ? $high['series'] : '';
        $totaldifficulties_xaxis = isset($monthData) ? $monthData : '';

        $variable = array('xaxisdata' => $totaldifficulties_xaxis,
            'seriesdata' => $totaldifficulties_series);

        echo json_encode($variable);
    }

    //emotional symptoms graph data
    function getEmoSymData() {
        $data['footerJs'][0] = base_url('uploads/custom/js/sdq/sdq_report.js');
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
        $year = !empty($getYear) ? $getYear : $selected_year;
        $data['year'] = $year;

        // get data from nfc_sdq_recordsheet table 
        $matchRecord = "is_delete = 0 and yp_id = " . $yp_id;
        $fieldsRecord = array("id");
        $data['record_details'] = $this->common_model->get_records(NFC_SDQ_RECORD, $fieldsRecord, '', '', $matchRecord);
        $record_details = $data['record_details'];

        // emotional symptoms graph data
        //get question id from nfc_admin_sdq table
        $matchQue = "is_delete = 0 and que = 'Emotional Symptoms Scale'";
        $fieldsQue = array("sdq_que_id");
        $data['que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsQue, '', '', $matchQue);
        $queDetails = $data['que_details'];
        $que_id = $queDetails[0]['sdq_que_id'];

        $emotional_symptoms_scale = array();
        foreach ($record_details as $result) {

            $emotional_symptoms_scale [] = $this->SdqReport_model->getEmoSympData($result['id'], $que_id, $year);
        }

        foreach ($emotional_symptoms_scale as $key => $score) {
            $monthNumE = $score[0]['date'];
            $monthNumEmo [] = $monthNumE;
            $month_name_emo = array_keys(array_flip($monthNumEmo));
            if (!empty($score[0]['user_type'])) {
                foreach ($month_name_emo as $month) {
                    if (!isset($total_emo_diffarr1[$score[0]['user_type']][$month])) {
                        $total_emo_diffarr1[$score[0]['user_type']][$month] = 0;
                    }
                }
            }
        }

        foreach ($emotional_symptoms_scale as $key => $score) {
            if (!empty($score[0]['user_type'])) {
                $total_emo_diffarr1[$score[0]['user_type']][$score[0]['date']] = $total_emo_diffarr1[$score[0]['user_type']][$score[0]['date']] + $score[0]['emo_score'];
                $monthE = $score[0]['date'];
                $monthNameDataE [] = date('d-m', strtotime($monthE));
                $monthDataEmo = array_keys(array_flip($monthNameDataE));
            }
        }

        $data['emo_scale_scoredata'] = isset($total_emo_diffarr1) ? $total_emo_diffarr1 : '';
        $emo_scale_scoredata = $data['emo_scale_scoredata'];
        if (!empty($emo_scale_scoredata)) {
            foreach ($emo_scale_scoredata as $user_type => $emo_score) {
                $emo_series[] = array('name' => $user_type, 'data' => array_values($emo_score));
            }
        }
        //emotional scale y & x series data

        $emotionalscale_series = isset($emo_series) ? $emo_series : '';
        $emotionalscale_xaxis = isset($monthDataEmo) ? $monthDataEmo : '';

        $variable = array('xaxisdata' => $emotionalscale_xaxis,
            'seriesdata' => $emotionalscale_series);
        echo json_encode($variable);
    }

    //conduct scale grapg data
    function getConScaleData() {
        $data['footerJs'][0] = base_url('uploads/custom/js/sdq/sdq_report.js');
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
        $year = !empty($getYear) ? $getYear : $selected_year;
        $data['year'] = $year;

        // get data from nfc_sdq_recordsheet table 
        $matchRecord = "is_delete = 0 and yp_id = " . $yp_id;
        $fieldsRecord = array("id");
        $data['record_details'] = $this->common_model->get_records(NFC_SDQ_RECORD, $fieldsRecord, '', '', $matchRecord);
        $record_details = $data['record_details'];

        // conduct problem scale graph data
        //get question id from nfc_admin_sdq table
        $matchQueCon = "is_delete = 0 and que = 'Conduct Problem Scale'";
        $fieldsQueCon = array("sdq_que_id");
        $data['con_que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsQueCon, '', '', $matchQueCon);
        $queDetailsCon = $data['con_que_details'];
        $con_que_id = $queDetailsCon[0]['sdq_que_id'];

        $conduct_problem_scale = array();
        foreach ($record_details as $result) {

            $conduct_problem_scale [] = $this->SdqReport_model->getEmoSympData($result['id'], $con_que_id, $year);
        }

        foreach ($conduct_problem_scale as $key => $score) {
            $monthNumC = $score[0]['date'];
            $monthNumCon [] = $monthNumC;
            $month_name_con = array_keys(array_flip($monthNumCon));
            if (!empty($score[0]['user_type'])) {
                foreach ($month_name_con as $month) {
                    if (!isset($total_con_diffarr1[$score[0]['user_type']][$month])) {
                        $total_con_diffarr1[$score[0]['user_type']][$month] = 0;
                    }
                }
            }
        }

        foreach ($conduct_problem_scale as $key => $score) {
            if (!empty($score[0]['user_type'])) {
                $total_con_diffarr1[$score[0]['user_type']][$score[0]['date']] = $total_con_diffarr1[$score[0]['user_type']][$score[0]['date']] + $score[0]['emo_score'];
                $monthC = $score[0]['date'];
                $monthNameDataC [] = date('d-m', strtotime($monthC));
                $monthDataCon = array_keys(array_flip($monthNameDataC));
            }
        }

        $data['con_scale_scoredata'] = isset($total_con_diffarr1) ? $total_con_diffarr1 : '';
        $con_scale_scoredata = $data['con_scale_scoredata'];
        if (!empty($con_scale_scoredata)) {
            foreach ($con_scale_scoredata as $user_type => $con_score) {
                $con_series[] = array('name' => $user_type, 'data' => array_values($con_score));
            }
        }
        //conduct problem scale y & x series data
        $conductscale_series = isset($con_series) ? $con_series : '';
        $conductscale_xaxis = isset($monthDataCon) ? $monthDataCon : '';

        $variable = array('xaxisdata' => $conductscale_xaxis,
            'seriesdata' => $conductscale_series);
        echo json_encode($variable);
    }

    //hyperscale graph data
    function getHypScaleData() {
        $data['footerJs'][0] = base_url('uploads/custom/js/sdq/sdq_report.js');
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
        $year = !empty($getYear) ? $getYear : $selected_year;
        $data['year'] = $year;

        // get data from nfc_sdq_recordsheet table 
        $matchRecord = "is_delete = 0 and yp_id = " . $yp_id;
        $fieldsRecord = array("id");
        $data['record_details'] = $this->common_model->get_records(NFC_SDQ_RECORD, $fieldsRecord, '', '', $matchRecord);
        $record_details = $data['record_details'];

        // hyperactivity graph data
        //get question id from nfc_admin_sdq table
        $matchQueHyp = "is_delete = 0 and que = 'Hyperactivity Scale'";
        $fieldsQueHyp = array("sdq_que_id");
        $data['hyp_que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsQueHyp, '', '', $matchQueHyp);
        $queDetailsHyp = $data['hyp_que_details'];
        $hyp_que_id = $queDetailsHyp[0]['sdq_que_id'];

        $hyperactive_scale = array();
        foreach ($record_details as $result) {

            $hyperactive_scale [] = $this->SdqReport_model->getEmoSympData($result['id'], $hyp_que_id, $year);
        }

        foreach ($hyperactive_scale as $key => $score) {
            $monthNumH = $score[0]['date'];
            $monthNumHyp [] = $monthNumH;
            $month_name_hyp = array_keys(array_flip($monthNumHyp));
            if (!empty($score[0]['user_type'])) {
                foreach ($month_name_hyp as $month) {
                    if (!isset($total_hyp_diffarr1[$score[0]['user_type']][$month])) {
                        $total_hyp_diffarr1[$score[0]['user_type']][$month] = 0;
                    }
                }
            }
        }

        foreach ($hyperactive_scale as $key => $score) {
            if (!empty($score[0]['user_type'])) {
                $total_hyp_diffarr1[$score[0]['user_type']][$score[0]['date']] = $total_hyp_diffarr1[$score[0]['user_type']][$score[0]['date']] + $score[0]['emo_score'];
                $monthH = $score[0]['date'];
                $monthNameDataH [] = date('d-m', strtotime($monthH));
                $monthDataHyp = array_keys(array_flip($monthNameDataH));
            }
        }

        $data['hyperactive_scoredata'] = isset($total_hyp_diffarr1) ? $total_hyp_diffarr1 : '';
        $hyperactive_scoredata = $data['hyperactive_scoredata'];
        if (!empty($hyperactive_scoredata)) {
            foreach ($hyperactive_scoredata as $user_type => $hyp_score) {
                $hyp_series[] = array('name' => $user_type, 'data' => array_values($hyp_score));
            }
        }
        //hyperactivity scale y & x series data

        $hypscale_series = isset($hyp_series) ? $hyp_series : '';
        $hypscale_xaxis = isset($monthDataHyp) ? $monthDataHyp : '';

        $variable = array('xaxisdata' => $hypscale_xaxis,
            'seriesdata' => $hypscale_series);
        echo json_encode($variable);
    }

    //peerscale graph data
    function getPeerScaleData() {
        $data['footerJs'][0] = base_url('uploads/custom/js/sdq/sdq_report.js');
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
        $year = !empty($getYear) ? $getYear : $selected_year;
        $data['year'] = $year;

        // get data from nfc_sdq_recordsheet table 
        $matchRecord = "is_delete = 0 and yp_id = " . $yp_id;
        $fieldsRecord = array("id");
        $data['record_details'] = $this->common_model->get_records(NFC_SDQ_RECORD, $fieldsRecord, '', '', $matchRecord);
        $record_details = $data['record_details'];

        // peer problem graph data
        //get question id from nfc_admin_sdq table
        $matchQuePeer = "is_delete = 0 and que = 'Peer Problems Scale'";
        $fieldsQuePeer = array("sdq_que_id");
        $data['peer_que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsQuePeer, '', '', $matchQuePeer);
        $queDetailsPeer = $data['peer_que_details'];
        $peer_que_id = $queDetailsPeer[0]['sdq_que_id'];

        $peer_prob_scale = array();
        foreach ($record_details as $result) {

            $peer_prob_scale [] = $this->SdqReport_model->getEmoSympData($result['id'], $peer_que_id, $year);
        }

        foreach ($peer_prob_scale as $key => $score) {
            $monthNumP = $score[0]['date'];
            $monthNumPeer [] = $monthNumP;
            $month_name_peer = array_keys(array_flip($monthNumPeer));
            if (!empty($score[0]['user_type'])) {
                foreach ($month_name_peer as $month) {
                    if (!isset($total_peer_diffarr1[$score[0]['user_type']][$month])) {
                        $total_peer_diffarr1[$score[0]['user_type']][$month] = 0;
                    }
                }
            }
        }

        foreach ($peer_prob_scale as $key => $score) {
            if (!empty($score[0]['user_type'])) {
                $total_peer_diffarr1[$score[0]['user_type']][$score[0]['date']] = $total_peer_diffarr1[$score[0]['user_type']][$score[0]['date']] + $score[0]['emo_score'];
                $monthP = $score[0]['date'];
                $monthNameDataP [] = date('d-m', strtotime($monthP));
                $monthDataPeer = array_keys(array_flip($monthNameDataP));
            }
        }

        $data['peerprob_scoredata'] = isset($total_peer_diffarr1) ? $total_peer_diffarr1 : '';
        $peerprob_scoredata = $data['peerprob_scoredata'];

        if (!empty($peerprob_scoredata)) {
            foreach ($peerprob_scoredata as $user_type => $peer_score) {
                $peer_series[] = array('name' => $user_type, 'data' => array_values($peer_score));
            }
        }
        //peer problem scale y & x series data

        $peerscale_series = isset($peer_series) ? $peer_series : '';
        $peerscale_xaxis = isset($monthDataPeer) ? $monthDataPeer : '';

        $variable = array('xaxisdata' => $peerscale_xaxis,
            'seriesdata' => $peerscale_series);
        echo json_encode($variable);
    }

    //PROSOCIAL graph
    function getProScaleData() {
        $data['footerJs'][0] = base_url('uploads/custom/js/sdq/sdq_report.js');
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
        $year = !empty($getYear) ? $getYear : $selected_year;
        $data['year'] = $year;

        // get data from nfc_sdq_recordsheet table 
        $matchRecord = "is_delete = 0 and yp_id = " . $yp_id;
        $fieldsRecord = array("id");
        $data['record_details'] = $this->common_model->get_records(NFC_SDQ_RECORD, $fieldsRecord, '', '', $matchRecord);
        $record_details = $data['record_details'];

        // prosocial behavior graph data
        //get question id from nfc_admin_sdq table
        $matchQuePro = "is_delete = 0 and que = 'Prosocial Scale'";
        $fieldsQuePro = array("sdq_que_id");
        $data['pro_que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsQuePro, '', '', $matchQuePro);
        $queDetailsPro = $data['pro_que_details'];
        $pro_que_id = $queDetailsPro[0]['sdq_que_id'];

        $pro_behav_scale = array();
        foreach ($record_details as $result) {

            $pro_behav_scale [] = $this->SdqReport_model->getEmoSympData($result['id'], $pro_que_id, $year);
        }

        foreach ($pro_behav_scale as $key => $score) {
            $monthNumPr = $score[0]['date'];
            $monthNumPro [] = $monthNumPr;
            $month_name_pro = array_keys(array_flip($monthNumPro));
            if (!empty($score[0]['user_type'])) {
                foreach ($month_name_pro as $month) {
                    if (!isset($total_pro_diffarr1[$score[0]['user_type']][$month])) {
                        $total_pro_diffarr1[$score[0]['user_type']][$month] = 0;
                    }
                }
            }
        }

        foreach ($pro_behav_scale as $key => $score) {
            if (!empty($score[0]['user_type'])) {
                $total_pro_diffarr1[$score[0]['user_type']][$score[0]['date']] = $total_pro_diffarr1[$score[0]['user_type']][$score[0]['date']] + $score[0]['emo_score'];
                $monthPr = $score[0]['date'];
                $monthNameDataPr [] = date('d-m', strtotime($monthPr));
                $monthDataPro = array_keys(array_flip($monthNameDataPr));
            }
        }

        $data['probehav_scoredata'] = isset($total_pro_diffarr1) ? $total_pro_diffarr1 : '';
        $probehav_scoredata = $data['probehav_scoredata'];

        if (!empty($probehav_scoredata)) {
            foreach ($probehav_scoredata as $user_type => $pro_score) {
                $pro_series[] = array('name' => $user_type, 'data' => array_values($pro_score));
            }
        }
        //prosocial behav. scale y & x series data

        $proscale_series = isset($pro_series) ? $pro_series : '';
        $proscale_xaxis = isset($monthDataPro) ? $monthDataPro : '';

        $variable = array('xaxisdata' => $proscale_xaxis,
            'seriesdata' => $proscale_series);
        echo json_encode($variable);
    }

    // SDQ trend Report page 
    function SdqTrendReport($id,$careHomeId=0,$isArchive=0) {
		
		$data['is_archive_page'] = $isArchive;
        $data['careHomeId'] = $careHomeId;
        $data['footerJs'][0] = base_url('uploads/custom/js/sdq/sdq_trend.js');
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
        $year = !empty($getYear) ? $getYear : $selected_year;
        $data['year'] = $year;

        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['main_content'] = '/sdqrecord_trend_report';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }

    // get total difficulty trend dat
    function getTotalDifTrendData() {
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

        $year = date('Y');
        $data['year'] = $year;

        // get data from nfc_sdq_recordsheet table 
        $matchRecord = "is_delete = 0 and yp_id = " . $yp_id . " and year(created_date) = " . $year;
        ;
        $fieldsRecord = array("id");
        $data['record_details'] = $this->common_model->get_records(NFC_SDQ_RECORD, $fieldsRecord, '', '', $matchRecord);
        $record_details = $data['record_details'];

        // total difficulty graph data
        //get question id from nfc_admin_sdq table
        $matchQue = "is_delete = 0 and que = 'Prosocial Scale'";
        $fieldsQue = array("sdq_que_id");
        $data['que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsQue, '', '', $matchQue);
        $queDetails = $data['que_details'];
        $que_id = $queDetails[0]['sdq_que_id'];

        foreach ($record_details as $result) {
            $matchData = "is_delete = 0 and ans = 0 and record_id = '" . $result['id'] . "' and que_id != " . $que_id;
            $fieldsData = array("count(ans) as total");
            $data['ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsData, '', '', $matchData);
            $total_not_true[] = $data['ans_details'][0]['total'];

            $matchDataS = "is_delete = 0 and ans = 1 and record_id = '" . $result['id'] . "' and que_id != " . $que_id;
            $fieldsDataS = array("count(ans) as som_total");
            $data['some_ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsDataS, '', '', $matchDataS);
            $total_some_true[] = $data['some_ans_details'][0]['som_total'];

            $matchDataC = "is_delete = 0 and ans = 2 and record_id = '" . $result['id'] . "' and que_id != " . $que_id;
            $fieldsDataC = array("count(ans) as cer_total");
            $data['certain_ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsDataC, '', '', $matchDataC);
            $total_certainly_true[] = $data['certain_ans_details'][0]['cer_total'];
        }
        //total difficulty scale series data
        $notTrueTotal = !empty($total_not_true) ? array_sum($total_not_true) : '';
        $someTrueTotal = !empty($total_some_true) ? array_sum($total_some_true) : '';
        $certainlyTrueTotal = !empty($total_certainly_true) ? array_sum($total_certainly_true) : '';

        echo $notTrueTotal . "," . $someTrueTotal . "," . $certainlyTrueTotal;
        exit;
    }

    // get emotional symptoms trend data
    function getEmoSymTrendData() {
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

        $year = date('Y');
        $data['year'] = $year;
        // get data from nfc_sdq_recordsheet table 
        $matchRecord = "is_delete = 0 and yp_id = " . $yp_id . " and year(created_date) = " . $year;
        $fieldsRecord = array("id");
        $data['record_details'] = $this->common_model->get_records(NFC_SDQ_RECORD, $fieldsRecord, '', '', $matchRecord);
        $record_details = $data['record_details'];

        // emotional symptoms graph data
        //get question id from nfc_admin_sdq table
        $matchQue = "is_delete = 0 and que = 'Emotional Symptoms Scale'";
        $fieldsQue = array("sdq_que_id");
        $data['que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsQue, '', '', $matchQue);
        $queDetails = $data['que_details'];
        $que_id = $queDetails[0]['sdq_que_id'];

        foreach ($record_details as $result) {
            $matchData = "is_delete = 0 and ans = 0 and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsData = array("count(ans) as total");
            $data['ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsData, '', '', $matchData);
            $not_true[] = $data['ans_details'][0]['total'];

            $matchDataS = "is_delete = 0 and ans = 1 and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsDataS = array("count(ans) as som_total");
            $data['some_ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsDataS, '', '', $matchDataS);
            $some_true[] = $data['some_ans_details'][0]['som_total'];

            $matchDataC = "is_delete = 0 and ans = 2 and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
            $fieldsDataC = array("count(ans) as cer_total");
            $data['certain_ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsDataC, '', '', $matchDataC);
            $certainly_true[] = $data['certain_ans_details'][0]['cer_total'];
        }
        //emotional scale series data
        $notTrue = !empty($not_true) ? array_sum($not_true) : '';
        $someTrue = !empty($some_true) ? array_sum($some_true) : '';
        $certainlyTrue = !empty($certainly_true) ? array_sum($certainly_true) : '';

        echo $notTrue . "," . $someTrue . "," . $certainlyTrue;
        exit;
    }

    // get econduct scale trend data
    function getConScaleTrend() {
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

        $year = date('Y');
        $data['year'] = $year;
        // get data from nfc_sdq_recordsheet table 
        $matchRecord = "is_delete = 0 and yp_id = " . $yp_id . " and year(created_date) = " . $year;
        ;
        $fieldsRecord = array("id");
        $data['record_details'] = $this->common_model->get_records(NFC_SDQ_RECORD, $fieldsRecord, '', '', $matchRecord);
        $record_details = $data['record_details'];

        // conduct problem scale graph data
        //get question id from nfc_admin_sdq table
        $matchCon = "is_delete = 0 and que = 'Conduct Problem Scale'";
        $fieldsCon = array("sdq_que_id");
        $data['con_que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsCon, '', '', $matchCon);
        $conqueDetails = $data['con_que_details'];
        $con_que_id = $conqueDetails[0]['sdq_que_id'];

        foreach ($record_details as $result) {
            $matchData = "is_delete = 0 and ans = 0 and record_id = '" . $result['id'] . "' and que_id = " . $con_que_id;
            $fieldsData = array("count(ans) as total");
            $data['ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsData, '', '', $matchData);
            $con_not_true[] = $data['ans_details'][0]['total'];

            $matchDataS = "is_delete = 0 and ans = 1 and record_id = '" . $result['id'] . "' and que_id = " . $con_que_id;
            $fieldsDataS = array("count(ans) as som_total");
            $data['some_ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsDataS, '', '', $matchDataS);
            $con_some_what_true[] = $data['some_ans_details'][0]['som_total'];

            $matchDataC = "is_delete = 0 and ans = 2 and record_id = '" . $result['id'] . "' and que_id = " . $con_que_id;
            $fieldsDataC = array("count(ans) as cer_total");
            $data['certain_ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsDataC, '', '', $matchDataC);
            $con_certainly_true[] = $data['certain_ans_details'][0]['cer_total'];
        }
        //Conduct problem scale series data
        $notTrueCon = !empty($con_not_true) ? array_sum($con_not_true) : '';
        $someTrueCon = !empty($con_some_what_true) ? array_sum($con_some_what_true) : '';
        $certainlyTrueCon = !empty($con_certainly_true) ? array_sum($con_certainly_true) : '';
        echo $notTrueCon . "," . $someTrueCon . "," . $certainlyTrueCon;
        exit;
    }

    // get hyper scale trend data
    function getHypScaleTrend() {
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
        $year = date('Y');
        $data['year'] = $year;
        // get data from nfc_sdq_recordsheet table 
        $matchRecord = "is_delete = 0 and yp_id = " . $yp_id . " and year(created_date) = " . $year;
        ;
        $fieldsRecord = array("id");
        $data['record_details'] = $this->common_model->get_records(NFC_SDQ_RECORD, $fieldsRecord, '', '', $matchRecord);
        $record_details = $data['record_details'];

        // hyperactivity graph data
        //get question id from nfc_admin_sdq table
        $matchHyp = "is_delete = 0 and que = 'Hyperactivity Scale'";
        $fieldsHyp = array("sdq_que_id");
        $data['hyp_que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsHyp, '', '', $matchHyp);
        $hypqueDetails = $data['hyp_que_details'];
        $hyp_que_id = $hypqueDetails[0]['sdq_que_id'];

        foreach ($record_details as $result) {
            $matchData = "is_delete = 0 and ans = 0 and record_id = '" . $result['id'] . "' and que_id = " . $hyp_que_id;
            $fieldsData = array("count(ans) as total");
            $data['ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsData, '', '', $matchData);
            $hyp_not_true[] = $data['ans_details'][0]['total'];

            $matchDataS = "is_delete = 0 and ans = 1 and record_id = '" . $result['id'] . "' and que_id = " . $hyp_que_id;
            $fieldsDataS = array("count(ans) as som_total");
            $data['some_ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsDataS, '', '', $matchDataS);
            $hyp_some_what_true[] = $data['some_ans_details'][0]['som_total'];

            $matchDataC = "is_delete = 0 and ans = 2 and record_id = '" . $result['id'] . "' and que_id = " . $hyp_que_id;
            $fieldsDataC = array("count(ans) as cer_total");
            $data['certain_ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsDataC, '', '', $matchDataC);
            $hyp_certainly_true[] = $data['certain_ans_details'][0]['cer_total'];
        }

        //Hyperactivity scale series data
        $notTrueHyp = !empty($hyp_not_true) ? array_sum($hyp_not_true) : '';
        $someTrueHyp = !empty($hyp_some_what_true) ? array_sum($hyp_some_what_true) : '';
        $certainlyTrueHyp = !empty($hyp_certainly_true) ? array_sum($hyp_certainly_true) : '';

        echo $notTrueHyp . "," . $someTrueHyp . "," . $certainlyTrueHyp;
        exit;
    }

    // get peer scale trend data
    function getPeerScaleTrend() {
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
        $year = date('Y');
        $data['year'] = $year;
        // get data from nfc_sdq_recordsheet table 
        $matchRecord = "is_delete = 0 and yp_id = " . $yp_id . " and year(created_date) = " . $year;
        ;
        $fieldsRecord = array("id");
        $data['record_details'] = $this->common_model->get_records(NFC_SDQ_RECORD, $fieldsRecord, '', '', $matchRecord);
        $record_details = $data['record_details'];

        // peer problem graph data
        //get question id from nfc_admin_sdq table
        $matchPeer = "is_delete = 0 and que = 'Peer Problems Scale'";
        $fieldsPeer = array("sdq_que_id");
        $data['peer_que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsPeer, '', '', $matchPeer);
        $peerqueDetails = $data['peer_que_details'];
        $peer_que_id = $peerqueDetails[0]['sdq_que_id'];

        foreach ($record_details as $result) {
            $matchData = "is_delete = 0 and ans = 0 and record_id = '" . $result['id'] . "' and que_id = " . $peer_que_id;
            $fieldsData = array("count(ans) as total");
            $data['ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsData, '', '', $matchData);
            $peer_not_true[] = $data['ans_details'][0]['total'];

            $matchDataS = "is_delete = 0 and ans = 1 and record_id = '" . $result['id'] . "' and que_id = " . $peer_que_id;
            $fieldsDataS = array("count(ans) as som_total");
            $data['some_ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsDataS, '', '', $matchDataS);
            $peer_some_what_true[] = $data['some_ans_details'][0]['som_total'];

            $matchDataC = "is_delete = 0 and ans = 2 and record_id = '" . $result['id'] . "' and que_id = " . $peer_que_id;
            $fieldsDataC = array("count(ans) as cer_total");
            $data['certain_ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsDataC, '', '', $matchDataC);
            $peer_certainly_true[] = $data['certain_ans_details'][0]['cer_total'];
        }

        //peer problem scale series data
        $notTruePeer = !empty($peer_not_true) ? array_sum($peer_not_true) : '';
        $someTruePeer = !empty($peer_some_what_true) ? array_sum($peer_some_what_true) : '';
        $certainlyTruePeer = !empty($peer_certainly_true) ? array_sum($peer_certainly_true) : '';

        echo $notTruePeer . "," . $someTruePeer . "," . $certainlyTruePeer;
        exit;
    }

    // get pro social scale trend data
    function getProScaleTrend() {
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
        $year = date('Y');
        $data['year'] = $year;
        // get data from nfc_sdq_recordsheet table 
        $matchRecord = "is_delete = 0 and yp_id = " . $yp_id . " and year(created_date) = " . $year;
        ;
        $fieldsRecord = array("id");
        $data['record_details'] = $this->common_model->get_records(NFC_SDQ_RECORD, $fieldsRecord, '', '', $matchRecord);
        $record_details = $data['record_details'];

        // prosocial behavior graph data
        //get question id from nfc_admin_sdq table
        $matchPro = "is_delete = 0 and que = 'Prosocial Scale'";
        $fieldsPro = array("sdq_que_id");
        $data['pro_que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsPro, '', '', $matchPro);
        $proqueDetails = $data['pro_que_details'];
        $pro_que_id = $proqueDetails[0]['sdq_que_id'];

        foreach ($record_details as $result) {
            $matchData = "is_delete = 0 and ans = 0 and record_id = '" . $result['id'] . "' and que_id = " . $pro_que_id;
            $fieldsData = array("count(ans) as total");
            $data['ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsData, '', '', $matchData);
            $pro_not_true[] = $data['ans_details'][0]['total'];

            $matchDataS = "is_delete = 0 and ans = 1 and record_id = '" . $result['id'] . "' and que_id = " . $pro_que_id;
            $fieldsDataS = array("count(ans) as som_total");
            $data['some_ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsDataS, '', '', $matchDataS);
            $pro_some_what_true[] = $data['some_ans_details'][0]['som_total'];

            $matchDataC = "is_delete = 0 and ans = 2 and record_id = '" . $result['id'] . "' and que_id = " . $pro_que_id;
            $fieldsDataC = array("count(ans) as cer_total");
            $data['certain_ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsDataC, '', '', $matchDataC);
            $pro_certainly_true[] = $data['certain_ans_details'][0]['cer_total'];
        }

        //prosocial behavior scale series data
        $notTruePro = !empty($pro_not_true) ? array_sum($pro_not_true) : '';
        $someTruePro = !empty($pro_some_what_true) ? array_sum($pro_some_what_true) : '';
        $certainlyTruePro = !empty($pro_certainly_true) ? array_sum($pro_certainly_true) : '';

        echo $notTruePro . "," . $someTruePro . "," . $certainlyTruePro;
        exit;
    }

    // generate pdf code
    function generatePDF($id) {

        $data['footerJs'][0] = base_url('uploads/custom/js/sdq/sdq_report.js');
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
        $year = !empty($getYear) ? $getYear : $selected_year;
        $data['year'] = $year;

        // total difficulty graph data
        // get data from nfc_sdq_recordsheet table 
        $matchRecord = "is_delete = 0 and yp_id = '" . $yp_id . "' and YEAR(created_date) =" . $year;
        $fieldsRecord = array("id");
        $data['record_details'] = $this->common_model->get_records(NFC_SDQ_RECORD, $fieldsRecord, '', '', $matchRecord);
        $record_details = $data['record_details'];

        if (!empty($record_details)) {
            //get prosocial scale id from nfc_admin_sdq table
            $matchQuePros = "is_delete = 0 and que = 'Prosocial Scale'";
            $fieldsQuePros = array("sdq_que_id");
            $data['pro_que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsQuePros, '', '', $matchQuePros);
            $queDetailsPros = $data['pro_que_details'];
            $pros_que_id = $queDetailsPros[0]['sdq_que_id'];

            // total difficulty graph data
            $total_diff_score = array();
            foreach ($record_details as $result) {

                $total_diff_score[] = $this->SdqReport_model->getTotDifData($result['id'], $year, $pros_que_id);
            }
            foreach ($total_diff_score as $key => $score) {
                $monthNum = $score[0]['date'];
                $monthName [] = $monthNum;
                $month_name = array_keys(array_flip($monthName));
                if (!empty($score[0]['user_type'])) {
                    foreach ($month_name as $month) {
                        if (!isset($total_diffarr1[$score[0]['user_type']][$month])) {
                            $total_diffarr1[$score[0]['user_type']][$month] = 0;
                        }
                    }
                }
            }
            foreach ($total_diff_score as $key => $score) {
                if (!empty($score[0]['user_type'])) {
                    $total_diffarr1[$score[0]['user_type']][$score[0]['date']] = $total_diffarr1[$score[0]['user_type']][$score[0]['date']] + $score[0]['total_score'];
                    $month = $score[0]['date'];
                    $monthNameData [] = date('d-m', strtotime($month));
                    $monthData = array_keys(array_flip($monthNameData));
                }
            }
            //total difficulties series data
            $total_diff_scoredata = isset($total_diffarr1) ? $total_diffarr1 : '';
            if (!empty($total_diff_scoredata)) {
                foreach ($total_diff_scoredata as $user_type => $total_score) {

                    $seriesData[] = array('name' => $user_type, 'data' => array_values($total_score));
                }
            }
            $chartTitle = 'Total Difficulties';
            $data['chartName'] = 'total_difficulties';
            //  etotal difficulties graph data for export pdf
            $this->pdfExportCollumGraph($chartTitle, $monthData, $seriesData, $data['chartName']);



            // emotional symptoms graph data
            //get question id from nfc_admin_sdq table
            $matchQue = "is_delete = 0 and que = 'Emotional Symptoms Scale'";
            $fieldsQue = array("sdq_que_id");
            $data['que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsQue, '', '', $matchQue);
            $queDetails = $data['que_details'];
            $que_id = $queDetails[0]['sdq_que_id'];

            $emotional_symptoms_scale = array();
            foreach ($record_details as $result) {

                $emotional_symptoms_scale [] = $this->SdqReport_model->getEmoSympData($result['id'], $que_id, $year);
            }
            foreach ($emotional_symptoms_scale as $key => $score) {
                $monthNumE = $score[0]['date'];
                $monthNumEmo [] = $monthNumE;
                $month_name_emo = array_keys(array_flip($monthNumEmo));
                foreach ($month_name_emo as $month) {
                    if (!isset($total_emo_diffarr1[$score[0]['user_type']][$month])) {
                        $total_emo_diffarr1[$score[0]['user_type']][$month] = 0;
                    }
                }
            }
            foreach ($emotional_symptoms_scale as $key => $score) {
                if (!empty($score[0]['user_type'])) {
                    $total_emo_diffarr1[$score[0]['user_type']][$score[0]['date']] = $total_emo_diffarr1[$score[0]['user_type']][$score[0]['date']] + $score[0]['emo_score'];
                    $monthE = $score[0]['date'];
                    $monthNameDataE [] = date('d-m', strtotime($monthE));
                    $monthDataEmo = array_keys(array_flip($monthNameDataE));
                }
            }

            $emo_scale_scoredata = isset($total_emo_diffarr1) ? $total_emo_diffarr1 : '';
            //emotional symptoms series data
            if (!empty($emo_scale_scoredata)) {
                foreach ($emo_scale_scoredata as $user_type => $emo_score) {
                    $seriesDataEmo[] = array('name' => $user_type, 'data' => array_values($emo_score));
                }
            }
            $chartTitleEmo = 'Emotional Symptoms Scale';
            $data['emo_chartName'] = 'emotional_symptoms_score';
            //  emotional symptoms graph data for export pdf
            $this->pdfExportCollumGraph($chartTitleEmo, $monthDataEmo, $seriesDataEmo, $data['emo_chartName']);

            // conduct problem scale graph data
            //get question id from nfc_admin_sdq table
            $matchQueCon = "is_delete = 0 and que = 'Conduct Problem Scale'";
            $fieldsQueCon = array("sdq_que_id");
            $data['con_que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsQueCon, '', '', $matchQueCon);
            $queDetailsCon = $data['con_que_details'];
            $con_que_id = $queDetailsCon[0]['sdq_que_id'];

            $conduct_problem_scale = array();
            foreach ($record_details as $result) {

                $conduct_problem_scale [] = $this->SdqReport_model->getEmoSympData($result['id'], $con_que_id, $year);
            }
            foreach ($conduct_problem_scale as $key => $score) {
                $monthNumC = $score[0]['date'];
                $monthNumCon [] = $monthNumC;
                $month_name_con = array_keys(array_flip($monthNumCon));
                foreach ($month_name_con as $month) {
                    if (!isset($total_con_diffarr1[$score[0]['user_type']][$month])) {
                        $total_con_diffarr1[$score[0]['user_type']][$month] = 0;
                    }
                }
            }
            foreach ($conduct_problem_scale as $key => $score) {
                if (!empty($score[0]['user_type'])) {
                    $total_con_diffarr1[$score[0]['user_type']][$score[0]['date']] = $total_con_diffarr1[$score[0]['user_type']][$score[0]['date']] + $score[0]['emo_score'];
                    $monthC = $score[0]['date'];
                    $monthNameDataC [] = date('d-m', strtotime($monthC));
                    $monthDataCon = array_keys(array_flip($monthNameDataC));
                }
            }

            $con_scale_scoredata = isset($total_con_diffarr1) ? $total_con_diffarr1 : '';
            //conduct problem scale series data
            if (!empty($con_scale_scoredata)) {
                foreach ($con_scale_scoredata as $user_type => $con_score) {

                    $seriesDataCon[] = array('name' => $user_type, 'data' => array_values($con_score));
                }
            }
            $chartTitleCon = 'Conduct Problem Scale';
            $data['con_chartName'] = 'conduct_problem_score';
            //  conduct problem scale data for export pdf
            $this->pdfExportCollumGraph($chartTitleCon, $monthDataCon, $seriesDataCon, $data['con_chartName']);

            // hyperactivity graph data
            //get question id from nfc_admin_sdq table
            $matchQueHyp = "is_delete = 0 and que = 'Hyperactivity Scale'";
            $fieldsQueHyp = array("sdq_que_id");
            $data['hyp_que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsQueHyp, '', '', $matchQueHyp);
            $queDetailsHyp = $data['hyp_que_details'];
            $hyp_que_id = $queDetailsHyp[0]['sdq_que_id'];
            $hyperactive_scale = array();
            foreach ($record_details as $result) {
                $hyperactive_scale [] = $this->SdqReport_model->getEmoSympData($result['id'], $hyp_que_id, $year);
            }
            foreach ($hyperactive_scale as $key => $score) {
                $monthNumH = $score[0]['date'];
                $monthNumHyp [] = $monthNumH;
                $month_name_hyp = array_keys(array_flip($monthNumHyp));
                foreach ($month_name_hyp as $month) {
                    if (!isset($total_hyp_diffarr1[$score[0]['user_type']][$month])) {
                        $total_hyp_diffarr1[$score[0]['user_type']][$month] = 0;
                    }
                }
            }
            foreach ($hyperactive_scale as $key => $score) {
                if (!empty($score[0]['user_type'])) {
                    $total_hyp_diffarr1[$score[0]['user_type']][$score[0]['date']] = $total_hyp_diffarr1[$score[0]['user_type']][$score[0]['date']] + $score[0]['emo_score'];
                    $monthH = $score[0]['date'];
                    $monthNameDataH [] = date('d-m', strtotime($monthH));
                    $monthDataHyp = array_keys(array_flip($monthNameDataH));
                }
            }
            $hyperactive_scoredata = isset($total_hyp_diffarr1) ? $total_hyp_diffarr1 : '';
            //hyperactivity scale series data
            if (!empty($hyperactive_scoredata)) {
                foreach ($hyperactive_scoredata as $user_type => $hyp_score) {
                    $seriesDataHyp[] = array('name' => $user_type, 'data' => array_values($hyp_score));
                }
            }
            $chartTitleHyp = 'Hyperactivity Scale';
            $data['hyp_chartName'] = 'hyperactivity_score';
            //  hyperactivity scale data for export pdf
            $this->pdfExportCollumGraph($chartTitleHyp, $monthDataHyp, $seriesDataHyp, $data['hyp_chartName']);

            // peer problem graph data
            //get question id from nfc_admin_sdq table
            $matchQuePeer = "is_delete = 0 and que = 'Peer Problems Scale'";
            $fieldsQuePeer = array("sdq_que_id");
            $data['peer_que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsQuePeer, '', '', $matchQuePeer);
            $queDetailsPeer = $data['peer_que_details'];
            $peer_que_id = $queDetailsPeer[0]['sdq_que_id'];
            $peer_prob_scale = array();
            foreach ($record_details as $result) {
                $peer_prob_scale [] = $this->SdqReport_model->getEmoSympData($result['id'], $peer_que_id, $year);
            }
            foreach ($peer_prob_scale as $key => $score) {
                $monthNumP = $score[0]['date'];
                $monthNumPeer [] = $monthNumP;
                $month_name_peer = array_keys(array_flip($monthNumPeer));
                foreach ($month_name_peer as $month) {
                    if (!isset($total_peer_diffarr1[$score[0]['user_type']][$month])) {
                        $total_peer_diffarr1[$score[0]['user_type']][$month] = 0;
                    }
                }
            }
            foreach ($peer_prob_scale as $key => $score) {
                if (!empty($score[0]['user_type'])) {
                    $total_peer_diffarr1[$score[0]['user_type']][$score[0]['date']] = $total_peer_diffarr1[$score[0]['user_type']][$score[0]['date']] + $score[0]['emo_score'];
                    $monthP = $score[0]['date'];
                    $monthNameDataP [] = date('d-m', strtotime($monthP));
                    $monthDataPeer = array_keys(array_flip($monthNameDataP));
                }
            }
            $peerprob_scoredata = isset($total_peer_diffarr1) ? $total_peer_diffarr1 : '';
            //peer problem scale series data
            if (!empty($peerprob_scoredata)) {
                foreach ($peerprob_scoredata as $user_type => $peer_score) {
                    $seriesDataPeer[] = array('name' => $user_type, 'data' => array_values($peer_score));
                }
            }
            $chartTitlePeer = 'Peer Problems Scale';
            $data['peer_chartName'] = 'peer_prob_score';
            //  peer problem scale data for export pdf
            $this->pdfExportCollumGraph($chartTitlePeer, $monthDataPeer, $seriesDataPeer, $data['peer_chartName']);

            // prosocial behavior graph data
            //get question id from nfc_admin_sdq table
            $matchQuePro = "is_delete = 0 and que = 'Prosocial Scale'";
            $fieldsQuePro = array("sdq_que_id");
            $data['pro_que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsQuePro, '', '', $matchQuePro);
            $queDetailsPro = $data['pro_que_details'];
            $pro_que_id = $queDetailsPro[0]['sdq_que_id'];

            $pro_behav_scale = array();
            foreach ($record_details as $result) {
                $pro_behav_scale [] = $this->SdqReport_model->getEmoSympData($result['id'], $pro_que_id, $year);
            }
            foreach ($pro_behav_scale as $key => $score) {
                $monthNumPr = $score[0]['date'];
                $monthNumPro [] = $monthNumPr;
                $month_name_pro = array_keys(array_flip($monthNumPro));
                foreach ($month_name_pro as $month) {
                    if (!isset($total_pro_diffarr1[$score[0]['user_type']][$month])) {
                        $total_pro_diffarr1[$score[0]['user_type']][$month] = 0;
                    }
                }
            }
            foreach ($pro_behav_scale as $key => $score) {
                if (!empty($score[0]['user_type'])) {
                    $total_pro_diffarr1[$score[0]['user_type']][$score[0]['date']] = $total_pro_diffarr1[$score[0]['user_type']][$score[0]['date']] + $score[0]['emo_score'];
                    $monthPr = $score[0]['date'];
                    $monthNameDataPr [] = date('d-m', strtotime($monthPr));
                    $monthDataPro = array_keys(array_flip($monthNameDataPr));
                }
            }
            $probehav_scoredata = isset($total_pro_diffarr1) ? $total_pro_diffarr1 : '';

            //prosocial behavior scale series data
            if (!empty($probehav_scoredata)) {
                foreach ($probehav_scoredata as $user_type => $pro_score) {
                    $seriesDataPro[] = array('name' => $user_type, 'data' => array_values($pro_score));
                }
            }
            $chartTitlePro = 'Prosocial Scale';
            $data['pro_chartName'] = 'prosocial_score';
            //  prosocial behavior scale data for export pdf
            $this->pdfExportCollumGraph($chartTitlePro, $monthDataPro, $seriesDataPro, $data['pro_chartName']);

            //record report trend chart
            // emotional symptoms graph data
            //get question id from nfc_admin_sdq table
            $matchQue = "is_delete = 0 and que = 'Emotional Symptoms Scale'";
            $fieldsQue = array("sdq_que_id");
            $data['que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsQue, '', '', $matchQue);
            $queDetails = $data['que_details'];
            $que_id = $queDetails[0]['sdq_que_id'];

            foreach ($record_details as $result) {
                $matchData = "is_delete = 0 and ans = 0 and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsData = array("count(ans) as total");
                $data['ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsData, '', '', $matchData);
                $not_true[] = $data['ans_details'][0]['total'];

                $matchDataS = "is_delete = 0 and ans = 1 and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsDataS = array("count(ans) as som_total");
                $data['some_ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsDataS, '', '', $matchDataS);
                $some_true[] = $data['some_ans_details'][0]['som_total'];

                $matchDataC = "is_delete = 0 and ans = 2 and record_id = '" . $result['id'] . "' and que_id = " . $que_id;
                $fieldsDataC = array("count(ans) as cer_total");
                $data['certain_ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsDataC, '', '', $matchDataC);
                $certainly_true[] = $data['certain_ans_details'][0]['cer_total'];
            }
            //emotional scale series data
            $notTrue = array_sum($not_true);
            $someTrue = array_sum($some_true);
            $certainlyTrue = array_sum($certainly_true);
            $emo_pie_data[] = array("name" => "true", "colorByPoint" => "true", "data" => array(array("name" => "Not True", "y" => $notTrue), array("name" => "Some What True", "y" => $someTrue), array("name" => "Certainly True", "y" => $certainlyTrue)));
            // emotional symptoms scale graph data for export pdf
            $chartTitleEmoPie = "Emotional Symptoms Scale";
            $data['pie_emo_chartName'] = "pie_emo_score";
            $this->pdfExportPieGraph($chartTitleEmoPie, $emo_pie_data, $data['pie_emo_chartName']);

            // conduct problem scale graph data
            //get question id from nfc_admin_sdq table
            $matchCon = "is_delete = 0 and que = 'Conduct Problem Scale'";
            $fieldsCon = array("sdq_que_id");
            $data['con_que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsCon, '', '', $matchCon);
            $conqueDetails = $data['con_que_details'];
            $con_que_id = $conqueDetails[0]['sdq_que_id'];

            foreach ($record_details as $result) {
                $matchData = "is_delete = 0 and ans = 0 and record_id = '" . $result['id'] . "' and que_id = " . $con_que_id;
                $fieldsData = array("count(ans) as total");
                $data['ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsData, '', '', $matchData);
                $con_not_true[] = $data['ans_details'][0]['total'];

                $matchDataS = "is_delete = 0 and ans = 1 and record_id = '" . $result['id'] . "' and que_id = " . $con_que_id;
                $fieldsDataS = array("count(ans) as som_total");
                $data['some_ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsDataS, '', '', $matchDataS);
                $con_some_what_true[] = $data['some_ans_details'][0]['som_total'];

                $matchDataC = "is_delete = 0 and ans = 2 and record_id = '" . $result['id'] . "' and que_id = " . $con_que_id;
                $fieldsDataC = array("count(ans) as cer_total");
                $data['certain_ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsDataC, '', '', $matchDataC);
                $con_certainly_true[] = $data['certain_ans_details'][0]['cer_total'];
            }
            //Conduct problem scale series data
            $notTrueCon = array_sum($con_not_true);
            $someTrueCon = array_sum($con_some_what_true);
            $certainlyTrueCon = array_sum($con_certainly_true);
            $con_pie_data[] = array("name" => "true", "colorByPoint" => "true", "data" => array(array("name" => "Not True", "y" => $notTrueCon), array("name" => "Some What True", "y" => $someTrueCon), array("name" => "Certainly True", "y" => $certainlyTrueCon)));
            // conduct problem scale graph data for export pdf
            $chartTitleConPie = "Conduct Problem Scale";
            $data['pie_con_chartName'] = "pie_con_score";
            $this->pdfExportPieGraph($chartTitleConPie, $con_pie_data, $data['pie_con_chartName']);

            // hyperactivity graph data
            //get question id from nfc_admin_sdq table
            $matchHyp = "is_delete = 0 and que = 'Hyperactivity Scale'";
            $fieldsHyp = array("sdq_que_id");
            $data['hyp_que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsHyp, '', '', $matchHyp);
            $hypqueDetails = $data['hyp_que_details'];
            $hyp_que_id = $hypqueDetails[0]['sdq_que_id'];

            foreach ($record_details as $result) {
                $matchData = "is_delete = 0 and ans = 0 and record_id = '" . $result['id'] . "' and que_id = " . $hyp_que_id;
                $fieldsData = array("count(ans) as total");
                $data['ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsData, '', '', $matchData);
                $hyp_not_true[] = $data['ans_details'][0]['total'];

                $matchDataS = "is_delete = 0 and ans = 1 and record_id = '" . $result['id'] . "' and que_id = " . $hyp_que_id;
                $fieldsDataS = array("count(ans) as som_total");
                $data['some_ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsDataS, '', '', $matchDataS);
                $hyp_some_what_true[] = $data['some_ans_details'][0]['som_total'];

                $matchDataC = "is_delete = 0 and ans = 2 and record_id = '" . $result['id'] . "' and que_id = " . $hyp_que_id;
                $fieldsDataC = array("count(ans) as cer_total");
                $data['certain_ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsDataC, '', '', $matchDataC);
                $hyp_certainly_true[] = $data['certain_ans_details'][0]['cer_total'];
            }

            //Hyperactivity scale series data
            $notTrueHyp = array_sum($hyp_not_true);
            $someTrueHyp = array_sum($hyp_some_what_true);
            $certainlyTrueHyp = array_sum($hyp_certainly_true);
            $hyp_pie_data[] = array("name" => "true", "colorByPoint" => "true", "data" => array(array("name" => "Not True", "y" => $notTrueHyp), array("name" => "Some What True", "y" => $someTrueHyp), array("name" => "Certainly True", "y" => $certainlyTrueHyp)));
            // Hyperactivity scale graph data for export pdf
            $chartTitleHypPie = "Hyperactivity Scale";
            $data['pie_hyp_chartName'] = "pie_hyp_score";
            $this->pdfExportPieGraph($chartTitleHypPie, $hyp_pie_data, $data['pie_hyp_chartName']);

            // peer problem graph data
            //get question id from nfc_admin_sdq table
            $matchPeer = "is_delete = 0 and que = 'Peer Problems Scale'";
            $fieldsPeer = array("sdq_que_id");
            $data['peer_que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsPeer, '', '', $matchPeer);
            $peerqueDetails = $data['peer_que_details'];
            $peer_que_id = $peerqueDetails[0]['sdq_que_id'];

            foreach ($record_details as $result) {
                $matchData = "is_delete = 0 and ans = 0 and record_id = '" . $result['id'] . "' and que_id = " . $peer_que_id;
                $fieldsData = array("count(ans) as total");
                $data['ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsData, '', '', $matchData);
                $peer_not_true[] = $data['ans_details'][0]['total'];

                $matchDataS = "is_delete = 0 and ans = 1 and record_id = '" . $result['id'] . "' and que_id = " . $peer_que_id;
                $fieldsDataS = array("count(ans) as som_total");
                $data['some_ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsDataS, '', '', $matchDataS);
                $peer_some_what_true[] = $data['some_ans_details'][0]['som_total'];

                $matchDataC = "is_delete = 0 and ans = 2 and record_id = '" . $result['id'] . "' and que_id = " . $peer_que_id;
                $fieldsDataC = array("count(ans) as cer_total");
                $data['certain_ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsDataC, '', '', $matchDataC);
                $peer_certainly_true[] = $data['certain_ans_details'][0]['cer_total'];
            }

            //peer problem scale series data
            $notTruePeer = array_sum($peer_not_true);
            $someTruePeer = array_sum($peer_some_what_true);
            $certainlyTruePeer = array_sum($peer_certainly_true);
            $peer_pie_data[] = array("name" => "true", "colorByPoint" => "true", "data" => array(array("name" => "Not True", "y" => $notTruePeer), array("name" => "Some What True", "y" => $someTruePeer), array("name" => "Certainly True", "y" => $certainlyTruePeer)));
            // peer problem scale graph data for export pdf
            $chartTitlePeerPie = "Peer Problems Scale";
            $data['pie_peer_chartName'] = "pie_peer_score";
            $this->pdfExportPieGraph($chartTitlePeerPie, $peer_pie_data, $data['pie_peer_chartName']);

            // prosocial behavior graph data
            //get question id from nfc_admin_sdq table
            $matchPro = "is_delete = 0 and que = 'Prosocial Scale'";
            $fieldsPro = array("sdq_que_id");
            $data['pro_que_details'] = $this->common_model->get_records(NFC_ADMIN_SDQ, $fieldsPro, '', '', $matchPro);
            $proqueDetails = $data['pro_que_details'];
            $pro_que_id = $proqueDetails[0]['sdq_que_id'];

            foreach ($record_details as $result) {
                $matchData = "is_delete = 0 and ans = 0 and record_id = '" . $result['id'] . "' and que_id = " . $pro_que_id;
                $fieldsData = array("count(ans) as total");
                $data['ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsData, '', '', $matchData);
                $pro_not_true[] = $data['ans_details'][0]['total'];

                $matchDataS = "is_delete = 0 and ans = 1 and record_id = '" . $result['id'] . "' and que_id = " . $pro_que_id;
                $fieldsDataS = array("count(ans) as som_total");
                $data['some_ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsDataS, '', '', $matchDataS);
                $pro_some_what_true[] = $data['some_ans_details'][0]['som_total'];

                $matchDataC = "is_delete = 0 and ans = 2 and record_id = '" . $result['id'] . "' and que_id = " . $pro_que_id;
                $fieldsDataC = array("count(ans) as cer_total");
                $data['certain_ans_details'] = $this->common_model->get_records(NFC_SDQ_RECORD_ANS, $fieldsDataC, '', '', $matchDataC);
                $pro_certainly_true[] = $data['certain_ans_details'][0]['cer_total'];
            }

            //prosocial behavior scale series data
            $notTruePro = array_sum($pro_not_true);
            $someTruePro = array_sum($pro_some_what_true);
            $certainlyTruePro = array_sum($pro_certainly_true);
            $pro_pie_data[] = array("name" => "true", "colorByPoint" => "true", "data" => array(array("name" => "Not True", "y" => $notTruePro), array("name" => "Some What True", "y" => $someTruePro), array("name" => "Certainly True", "y" => $certainlyTruePro)));
            // prosocial behavior scale graph data for export pdf
            $chartTitleProPie = "Prosocial Scale";
            $data['pie_pro_chartName'] = "pie_pro_score";
            $this->pdfExportPieGraph($chartTitleProPie, $pro_pie_data, $data['pie_pro_chartName']);

            //total difficulties scale series data
            $notTrueTot = $notTrue + $notTrueCon + $notTrueHyp + $notTruePeer;
            $someTrueTot = $someTrue + $someTrueCon + $someTrueHyp + $someTruePeer;
            $certainlyTrueTot = $certainlyTrue + $certainlyTrueCon + $certainlyTrueHyp + $certainlyTruePeer;
            $tot_pie_data[] = array("name" => "true", "colorByPoint" => "true", "data" => array(array("name" => "Not True", "y" => $notTrueTot), array("name" => "Some What True", "y" => $someTrueTot), array("name" => "Certainly True", "y" => $certainlyTrueTot)));
            // total difficulties scale graph data for export pdf
            $chartTitleTotPie = "Total Difficulties";
            $data['pie_tot_chartName'] = "pie_tot_score";
            $this->pdfExportPieGraph($chartTitleTotPie, $tot_pie_data, $data['pie_tot_chartName']);

            //line trend pdf

            $total_indicators_year_score = $this->SdqReport_model->getlineYear($yp_id);
            $total_indicators_month_score = $this->SdqReport_model->getlineMonth($yp_id, $year);

            $start = $total_indicators_year_score[0]['min_year'];
            $end = $total_indicators_year_score[0]['max_year'];
            $getRangeYear = range(date('Y', strtotime($start)), date('Y', strtotime($end)));

            if (!empty($year)) {
                foreach ($total_indicators_month_score as $key => $score) {
                    if (!empty($score['user_type'])) {
                        $total_score = ($score['total_score']);
                        $dayData = gmmktime(0, 0, 0, $score['month'], $score['day'], $score['year']);
                        $dayVal = $dayData * 1000;
                        $total_indicators1[$score['user_type']][$score['day']] = [$dayVal, (int) $total_score];
                    }
                }
            }
            $total_indicators_data = isset($total_indicators1) ? $total_indicators1 : '';

            if (!empty($total_indicators_data)) {
                foreach ($total_indicators_data as $user_type => $total_score) {
                    $high['series'][] = array('name' => $user_type, 'data' => array_values($total_score));
                }
            }
            $totalindicators_series = isset($high['series']) ? $high['series'] : '';
            $data['line_chartName'] = 'line_score';
            
            //export pdf line graph
            $this->pdfExportLineGraph($totalindicators_series, $data['line_chartName']);
           
            
            //tred chart export pdf
            //load mPDF library
            $this->load->library('m_pdf');
            //this the the PDF filename that user will get to download
             //Set Header Footer and Content For PDF
            $PDFHeaderHTML  = $this->load->view('header_pdf', $data, true);
            $PDFFooterHTML  = $this->load->view('footer_pdf', $data, true);
            $this->m_pdf->pdf->mPDF('utf-8','A4','','','0','0','25','0','0','5');

            $this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML);
            $this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);
            //load the view and saved it into $html variable
            $data['main_content'] = '/sdqrecord_report_pdf';
            $html = $this->parser->parse('layouts/PdfDataTemplate', $data);
    
            $pdfFilePath = "SDQ Record Report.pdf";

            //generate the PDF from the given html
            $this->m_pdf->pdf->WriteHTML($html);

            //download it.
            $this->m_pdf->pdf->Output($pdfFilePath, "D");
        } else {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('SdqReport/index/' . $yp_id);
        }
    }

    //pdf collum graph
    function pdfExportCollumGraph($chartTitle, $monthData, $seriesData, $chartname) {

        $dirPath = $this->config->item('directory_root') . "application/modules/SdqReport";
        $outputPath = $this->config->item('directory_root') . "uploads/pdf_export/";
        $phantomPath = $this->config->item('directory_root') . "uploads/phantomjs";
        //  graph data for export pdf
        $high = array();
        $high['chart']['type'] = "column";
        $high['title']['text'] = $chartTitle;
        $high['xAxis']['categories'] = $monthData;
        $high['yAxis']['title']['text'] = 'Total Score';
        $high['credits']['enabled'] = false;
        $high['legend']['shadow'] = false;
        $high['tooltip']['headerFormat'] = '<span style="font-size:10px">{point.key}</span><table>';
        $high['tooltip']['pointFormat'] = '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:1f} </b></td></tr>';
       // $high['plotOptions']['column']['dataLabels']['enabled'] = 'true';
        $high['plotOptions']['column']['pointPadding'] = '0.2';
        $high['colors'] = ['#2196f3', '#63b1f0', '#1766a6', '#264e6e', '#0355fd', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'];
        $high['series'] = $seriesData;
        $chartName = $chartname;

        $myfile = fopen($outputPath . "/inrep_$chartName.json", "w") or die("Unable to open file!");
        $txt = json_encode($high);
        fwrite($myfile, $txt);
        fclose($myfile);
        //local path
        //$command = "C:/phantomjs/bin/phantomjs.exe $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$chartName.json -outfile " . $outputPath . "/inrep_$chartName.png -scale 2.5 -width 700 -constr Chart -callback $phantomPath/callback.js 2>&1";

        //demo server path
        //$command = "/usr/local/bin/phantomjs $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$chartName.json -outfile " . $outputPath . "/inrep_$chartName.png -scale 2.5 -width 700 -constr Chart -callback $phantomPath/callback.js 2>&1";
        // client server path
        $command = "/usr/bin/phantomjs $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$chartName.json -outfile " . $outputPath . "/inrep_$chartName.png -scale 2.5 -width 700 -constr Chart -callback $phantomPath/callback.js 2>&1";
        exec($command);
    }

    //pdf pie graph
    function pdfExportPieGraph($chartTitlePie, $seriesDataPie, $chartnamePie) {
        $dirPath = $this->config->item('directory_root') . "application/modules/SdqReport";
        $outputPath = $this->config->item('directory_root') . "uploads/pdf_export/";
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
        $high['colors'] = ['#5cb85c', '#FA8128', '#d9534f', '#264e6e', '#0355fd', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'];
        $high['series'] = $seriesDataPie;
        $piechartName = $chartnamePie;

        $myfile = fopen($outputPath . "/inrep_$piechartName.json", "w") or die("Unable to open file!");
        $txt = json_encode($high);
        fwrite($myfile, $txt);
        fclose($myfile);
        //local path
        //$command = "C:/phantomjs/bin/phantomjs.exe $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$piechartName.json -outfile " . $outputPath . "/inrep_$piechartName.png -scale 2.5 -width 700 -constr Chart -callback $phantomPath/callback.js 2>&1";

        //demo server path
        //$command = "/usr/local/bin/phantomjs $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$chartName.json -outfile " . $outputPath . "/inrep_$chartName.png -scale 2.5 -width 700 -constr Chart -callback $phantomPath/callback.js 2>&1";
        // client server path
       $command = "/usr/bin/phantomjs $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$piechartName.json -outfile " . $outputPath . "/inrep_$piechartName.png -scale 2.5 -width 700 -constr Chart -callback $phantomPath/callback.js 2>&1";
        exec($command);
    }
    
    //pdf line graph
    function pdfExportLineGraph($totalindicators_series, $chartnameLine) {
        $dirPath = $this->config->item('directory_root') . "application/modules/SdqReport";
        $outputPath = $this->config->item('directory_root') . "uploads/pdf_export/";
        $phantomPath = $this->config->item('directory_root') . "uploads/phantomjs";
        //  graph data for export pdf
            $high = array();
            $high['chart']['type'] = "spline";
            $high['title']['text'] = 'SDQ Report Graph';
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
             $high['colors'] = ['#5cb85c', '#FA8128', '#d9534f', '#264e6e', '#0355fd', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'];
            $high['series'] = $totalindicators_series;
            $line_chartName = $chartnameLine;
            

            $myfile = fopen($outputPath . "/inrep_$line_chartName.json", "w") or die("Unable to open file!");
            $txt = json_encode($high);
            fwrite($myfile, $txt);
            fclose($myfile);
            //local path
            //$command = "C:/phantomjs/bin/phantomjs.exe $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$line_chartName.json -outfile " . $outputPath . "/inrep_$line_chartName.png -scale 2.5 -width 700 -constr Chart -callback $phantomPath/callback.js 2>&1";
            //demo server path
            //$command = "/usr/local/bin/phantomjs $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$chartName.json -outfile " . $outputPath . "/inrep_$chartName.png -scale 2.5 -width 700 -constr Chart -callback $phantomPath/callback.js 2>&1";
            // client server path
            $command = "/usr/bin/phantomjs $phantomPath/highcharts-convert.js -infile " . $outputPath . "/inrep_$line_chartName.json -outfile " . $outputPath . "/inrep_$line_chartName.png -scale 2.5 -width 700 -constr Chart -callback $phantomPath/callback.js 2>&1";
            exec($command);
    }

    //get line graph data
    function getLineGraphData() {

        $yp_id = $this->input->post('yp_id');
        $year_post_data = date('Y');

        if (!empty($yp_id)) {

            // get data from nfc_sdq_recordsheet table 

            $matchRecord = array(
                'is_delete' => 0,
                'yp_id' => $yp_id
            );

            $fieldsRecord = array("*");
            $recordList = $this->common_model->get_records(NFC_SDQ_RECORD, $fieldsRecord, '', '', $matchRecord);


            $total_indicators_score = $this->SdqReport_model->getlineYearData($yp_id);
            $total_indicators_year_score = $this->SdqReport_model->getlineYear($yp_id);
            $total_indicators_month_score = $this->SdqReport_model->getlineMonth($yp_id, $year_post_data);

            $start = $total_indicators_year_score[0]['min_year'];
            $end = $total_indicators_year_score[0]['max_year'];
            $getRangeYear = range(date('Y', strtotime($start)), date('Y', strtotime($end)));
           
            if (!empty($year_post_data)) {
                foreach ($total_indicators_month_score as $key => $score) {
                    if (!empty($score['user_type'])) {
                        $total_score = ($score['total_score']);
                        $dayData = gmmktime(0, 0, 0, $score['month'], $score['day'], $score['year']);
                        $dayVal = $dayData * 1000;
                        $total_indicators1[$score['user_type']][$score['day']] = [$dayVal, (int) $total_score];
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
