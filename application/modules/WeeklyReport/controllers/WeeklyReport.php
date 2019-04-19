<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
  @Author : Ritesh Rana
  @Desc   : Save weekly report
  @Input    :
  @Output   :
  @Date   : 19/03/2018
 */

class WeeklyReport extends CI_Controller {

    function __construct() {

        parent::__construct();
        if (checkPermission('WeeklyReport', 'view') == false) {
            redirect('/Dashboard');
        }        
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Weekly report index page
      @Input  : yp id
      @Output :
      @Date   : 16/03/2018
     */

    public function index($id,$care_home_id=0,$past_care_id=0) {
         /*
                Ritesh Rana
                for past care id inserted for archive full functionality
                */
        if ($past_care_id !== 0) {
            $temp = $this->common_model->get_records(PAST_CARE_HOME_INFO, array('move_date'), '', '', array("yp_id" => $id, "past_carehome" => $care_home_id));
            $data_care_home_detail = $this->common_model->get_records(PAST_CARE_HOME_INFO, array("enter_date,move_date"), '', '', array("yp_id" => $id, "move_date <= " => $temp[0]['move_date']));
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

        if (is_numeric($id)) {
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($id,$fields);
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson');
            }
            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = RECORD_PER_PAGE;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('wr_data');
            }

            $searchsort_session = $this->session->userdata('wr_data');
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
                    $sortfield = 'wr.created_date';
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
                    $config['per_page'] = RECORD_PER_PAGE;
                    $data['perpage'] = RECORD_PER_PAGE;
                }
            }
            //pagination configuration
            $config['first_link'] = 'First';
        if($past_care_id == 0){
                $config['base_url'] = base_url() . $this->viewname . '/index/' . $id;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 4;
                $uri_segment = $this->uri->segment(4);
            }

            //Query
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $table = WEEKLY_REPORT . ' as wr';
            $where = array("wr.yp_id" => $id,"wr.is_archive"=> 0,"wr.is_delete"=> 0);
            
            $fields = array("l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, ch.care_home_name, l.firstname, l.lastname, wr.*,count(wrs.weekly_report_id) as sent_report");

            $join_tables = array(LOGIN . ' as l' => 'l.login_id = wr.created_by',WR_REPORT_SENT . ' as wrs' => 'wrs.weekly_report_id= wr.weekly_report_id',CARE_HOME . ' as ch' => 'ch.care_home_id = wr.care_home_id');
            if (!empty($searchtext)) {
                $searchtext = html_entity_decode(trim(addslashes($searchtext)));
                $match = array("wr.yp_id" => $id,"wr.is_archive"=> 0,"wr.is_delete"=> 0);
                $where_search = '((CONCAT(`firstname`, \' \', `lastname`) LIKE "%' . $searchtext . '%" OR l.firstname LIKE "%' . $searchtext . '%" OR l.lastname LIKE "%' . $searchtext . '%" OR wr.start_date LIKE "%' . $searchtext . '%" OR wr.end_date LIKE "%' . $searchtext . '%"  OR DATE_FORMAT(wr.start_date, "%d/%m/%Y") = "' . $searchtext . '"
                OR DATE_FORMAT(wr.end_date, "%d/%m/%Y") = "' . $searchtext . '"
                OR DATE_FORMAT(wr.created_date, "%d/%m/%Y") = "' . $searchtext . '"
                OR l.status LIKE "%' . $searchtext . '%")AND l.is_delete = "0")';

                $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match,'' , $config['per_page'], $uri_segment, $sortfield, $sortby, 'wr.weekly_report_id', $where_search);
                
                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match, '', '', '', $sortfield, $sortby, 'wr.weekly_report_id', $where_search, '', '', '1');
            } else {
                $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, 'wr.weekly_report_id', $where);
                
                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, 'wr.weekly_report_id', $where, '', '', '1');
            }
            }else{

             $config['base_url'] = base_url() . $this->viewname . '/index/' . $id.'/'.$care_home_id.'/'.$past_care_id;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 6;
                $uri_segment = $this->uri->segment(6);
            }

            //Query
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $table = WEEKLY_REPORT . ' as wr';
            $where = array("wr.yp_id" => $id,"wr.is_archive"=> 0,"wr.is_delete"=> 0);
            $where_date = "wr.created_date BETWEEN  '".$created_date."' AND '".$movedate."'";
            
            $fields = array("l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, ch.care_home_name, l.firstname, l.lastname, wr.*,count(wrs.weekly_report_id) as sent_report");

            $join_tables = array(LOGIN . ' as l' => 'l.login_id = wr.created_by',WR_REPORT_SENT . ' as wrs' => 'wrs.weekly_report_id= wr.weekly_report_id',CARE_HOME . ' as ch' => 'ch.care_home_id = wr.care_home_id');
            if (!empty($searchtext)) {
                $searchtext = html_entity_decode(trim(addslashes($searchtext)));
                $match = array("wr.yp_id" => $id,"wr.is_archive"=> 0,"wr.is_delete"=> 0);
                $where_search = '((CONCAT(`firstname`, \' \', `lastname`) LIKE "%' . $searchtext . '%" OR l.firstname LIKE "%' . $searchtext . '%" OR l.lastname LIKE "%' . $searchtext . '%" OR wr.start_date LIKE "%' . $searchtext . '%" OR wr.end_date LIKE "%' . $searchtext . '%"  OR DATE_FORMAT(wr.start_date, "%d/%m/%Y") = "' . $searchtext . '"
                OR DATE_FORMAT(wr.end_date, "%d/%m/%Y") = "' . $searchtext . '"
                OR DATE_FORMAT(wr.created_date, "%d/%m/%Y") = "' . $searchtext . '"
                OR l.status LIKE "%' . $searchtext . '%")AND l.is_delete = "0")';

                $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match,'' , $config['per_page'], $uri_segment, $sortfield, $sortby, 'wr.weekly_report_id', $where_search,'','','','','',$where_date);
                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match, '', '', '', $sortfield, $sortby, 'wr.weekly_report_id', $where_search, '', '', '1','','',$where_date);
            } else {
                $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, 'wr.weekly_report_id', $where,'','','','','',$where_date);
                    
                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, 'wr.weekly_report_id', $where, '', '', '1','','',$where_date);
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

            $data['ypid'] = $id;
            $data['care_home_id'] = $care_home_id;
            $data['past_care_id'] = $past_care_id;

            //get WR form
            $match = array('wr_form_id' => 1);
            $wr_forms = $this->common_model->get_records(WR_FORM, array("form_json_data"), '', '', $match);

            if (!empty($wr_forms)) {
                $data['form_data'] = json_decode($wr_forms[0]['form_json_data'], TRUE);
            }
            $this->session->set_userdata('wr_data', $sortsearchpage_data);
            setActiveSession('wr_data'); // set current Session active
            
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['crnt_view'] = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/wr/wr.js');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/ajaxlist', $data);
            } else {
                $data['main_content'] = '/WeeklyReport';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : create Weekly report edit page
      @Input 	:
      @Output	:
      @Date   : 16/03/2018
     */

    public function edit($id, $ypid) {
        if (is_numeric($ypid) && is_numeric($id)) {
            //get pp form
            $match = array('wr_form_id' => 1);
            $wr_forms = $this->common_model->get_records(WR_FORM, array("form_json_data"), '', '', $match);
            if (!empty($wr_forms)) {
                $data['form_data'] = json_decode($wr_forms[0]['form_json_data'], TRUE);
                foreach ($data['form_data'] as $form_data) {
                    if(!empty($form_data['name'])){
                             $form_field[] = $form_data['name'];
                    }
                }
           }
           $data['form_field_data_name'] = $form_field;
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($ypid,$fields);
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('errormsg', $msg);
                redirect('YoungPerson');
            }
            //get pp yp data
            
            $match = array('yp_id' => $ypid, 'weekly_report_id' => $id);
            $data['edit_data'] = $this->common_model->get_records(WEEKLY_REPORT, array("*"), '', '', $match);
            // signoff data
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $table = WEEKLY_REPORT_SIGNOFF . ' as wr';
            $where = array("l.is_delete" => "0", "wr.yp_id" => $ypid);
            $fields = array("wr.created_by,wr.yp_id,CONCAT(`firstname`,' ', `lastname`) as name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id=wr.created_by');
            $group_by = array('created_by');
            $data['signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);

            $data['url'] = base_url("Filemanager/index/$ypid/?dir=uploads/filemanager/Project0" . $ypid . "/&modal=true");
            $data['drag'] = true;
            $url_data = base_url('WeeklyReport/edit/' . $id . '/' . $ypid);
            $match = array('url_data' => $url_data);
            $data['check_edit_permission'] = $this->common_model->get_records(CHECK_EDIT_URL, '*', '', '', $match);
            if (count($data['check_edit_permission']) > 0) {
                $in_time = date('Y-m-d h:i:s', strtotime($data['check_edit_permission'][0]['datetime']));
                $currnt_time = date('Y-m-d h:i:s');
                if (strtotime($in_time) > strtotime($currnt_time)) {
                    $now = strtotime($in_time) - strtotime($currnt_time);
                } else {
                    $now = strtotime($currnt_time) - strtotime($in_time);
                }
                //  die;
                $secs = floor($now % 60);
                if ($secs >= 10) {
                    $data['ypid'] = $ypid;
                    $data['footerJs'][0] = base_url('uploads/custom/js/wr/wr.js');
                    $data['crnt_view'] = $this->viewname;
                    $data['main_content'] = '/edit';
                    $data['header'] = array('menu_module' => 'YoungPerson');
                    $this->parser->parse('layouts/DefaultTemplate', $data);
                } else {
                    $msg = $this->lang->line('check_wr_user_update_data');
                    $this->session->set_flashdata('errormsg', $msg);
                    redirect('/' . $this->viewname . '/index/' . $ypid);
                }
            } else {
                $data['ypid'] = $ypid;
                $data['footerJs'][0] = base_url('uploads/custom/js/wr/wr.js');
                $data['crnt_view'] = $this->viewname;
                $data['main_content'] = '/edit';
                $data['header'] = array('menu_module' => 'YoungPerson');
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : view wr form
      @Input    :
      @Output   :
      @Date   : 19/03/2018
     */

    public function view($wr_id, $yp_id,$care_home_id=0,$past_care_id=0) {
        if (is_numeric($wr_id) && is_numeric($yp_id)) { 
            //get ks form
            $match = array('wr_form_id' => 1);
            $wr_forms = $this->common_model->get_records(WR_FORM, '', '', '', $match);
            if (!empty($wr_forms)) {
                $data['form_data'] = json_decode($wr_forms[0]['form_json_data'], TRUE);
            }
            
            //get YP information
             $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
             $data['YP_details'] = YpDetails($yp_id,$fields);

            //get ks yp data
            $match = array('weekly_report_id' => $wr_id);
            $data['edit_data'] = $this->common_model->get_records(WEEKLY_REPORT, array("*"), '', '', $match);

            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $table = WEEKLY_REPORT_SIGNOFF . ' as wr';
            $where = array("l.is_delete" => "0", "wr.yp_id" => $yp_id, "wr.weekly_report_id" => $wr_id, "wr.is_delete" => "0");
            $fields = array("wr.created_by,wr.created_date,wr.yp_id,wr.weekly_report_id, CONCAT(`firstname`,' ', `lastname`) as name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id=wr.created_by');
            $group_by = array('created_by');
            $data['wr_signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);

            $table = WEEKLY_REPORT_SIGNOFF . ' as wr';
            $where = array("wr.yp_id" => $yp_id, "wr.created_by" => $login_user_id, "wr.is_delete" => "0", "wr.weekly_report_id" => $wr_id);
            $fields = array("wr.*");
            $data['check_wr_signoff_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

            //check data exist or not
            if (empty($data['YP_details']) || empty($data['edit_data'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson');
            }
            
            $table_ap = MEDICAL_PROFESSIONALS_APPOINTMENT . ' as mc';
            $where_ap = array("mc.yp_id" => $yp_id,"mc.is_delete"=>0,"mc.appointment_date >=" =>$data['edit_data'][0]['start_date'],"mc.appointment_date <=" =>$data['edit_data'][0]['end_date']);
                $fields_ap = array("mc.*,concat(if(mp.title != '',mp.title,''),' ',if(mp.first_name != '',mp.first_name,''),' ',if(mp.surname != '',mp.surname,''),' - ',if(mp.professional != '',mp.professional,'')) as mp_name, concat(mp.house_namenumber,',',mp.street,' ',mp.town,',',mp.city,',',mp.postcode) as address");
            $join_tables_ap = array(MEDICAL_PROFESSIONALS . ' as mp' => 'mc.mp_id= mp.mp_id',YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id');
            $data['appointments'] = $this->common_model->get_records($table_ap, $fields_ap, $join_tables_ap, 'left', $where_ap, '', '', '', 'appointment_id', 'desc', '');

             $table = MD_COMMENTS . ' as com';
              $where = array("com.yp_id" => $yp_id);
              $fields = array("com.md_comment,com.created_date,com.md_appoint_id,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
              $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
              $data['appointment_view_comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
              
            $table = WR_REPORT_SENT . ' AS wr';
            $fields = array('wr.*');
            $where = array('wr.weekly_report_id' => $wr_id, 'wr.yp_id' => $yp_id);
            $data['check_external_signoff_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            
            $data['ypid'] = $yp_id;
            $data['care_home_id'] = $care_home_id;
            $data['past_care_id'] = $past_care_id;
            $data['weekly_report_id'] = $wr_id;
            $data['footerJs'][0] = base_url('uploads/custom/js/wr/wr.js');
            $data['crnt_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/view';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }
    /*
      @Author : Ritesh rana
      @Desc   : Create Weekly report
      @Input 	: 
      @Output	:
      @Date   : 19/03/2018
     */
    public function create($id) {
      if(is_numeric($id))
      {
       //get ks form
       $match = array('wr_form_id'=> 1);
       $wr_forms = $this->common_model->get_records(WR_FORM,array("form_json_data"), '', '', $match);
       $form_field = array();
       if(!empty($wr_forms))
       {
            $data['form_data'] = json_decode($wr_forms[0]['form_json_data'], TRUE);
            foreach ($data['form_data'] as $form_data) {
                if(!empty($form_data['name'])){
                         $form_field[] = $form_data['name'];
                }
            }
       }
       $data['form_field_data_name'] = $form_field;
       
       //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($id,$fields);
        if(empty($data['YP_details']))
        {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson');
        }
        $data['url'] = base_url("Filemanager/index/$id/?dir=uploads/filemanager/Project0" . $id . "/&modal=true");

        $data['ypid'] = $id;
        $data['care_home_id'] = $data['YP_details'][0]['care_home'];
        $data['drag'] = true;
        $data['footerJs'][0] = base_url('uploads/custom/js/wr/wr.js');
        
        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['crnt_view'] = $this->viewname;
        $data['main_content'] = '/edit';
        $this->parser->parse('layouts/DefaultTemplate', $data);
      }
      else
      {
          show_404 ();
      }
    }
    /*
      @Author : Ritesh Rana
      @Desc   : insert Weekly report in db
      @Input 	: yp id, weekly_report_id(edit)
      @Output	:
      @Date   : 20/03/2018
     */
    public function insert() {
        $postData = $this->input->post();
		$draftdata = (isset($postData['saveAsDraft'])) ? $postData['saveAsDraft'] : 0;
		if($this->input->is_ajax_request()){
			$draftdata=1;
		}
        //get pp form
        $match = array('wr_form_id' => 1);
        $wr_forms = $this->common_model->get_records(WR_FORM, array("form_json_data"), '', '', $match);
        if (!empty($wr_forms)) {
            $wr_form_data = json_decode($wr_forms[0]['form_json_data'], TRUE);
            $data = array();
            foreach ($wr_form_data as $row) {
                if (isset($row['name'])) {
                    if ($row['type'] == 'file') {
                        $filename = $row['name'];
                        //get image previous image
                        $match = array('yp_id' => $postData['yp_id'], 'weekly_report_id' => $postData['weekly_report_id']);
                        $pp_yp_data = $this->common_model->get_records(WEEKLY_REPORT, array('`' . $row['name'] . '`'), '', '', $match);


                    if ($this->input->post('gallery_path')) {
                            $gallery_path = $this->input->post('gallery_path');
                            $est_files = $this->input->post('gallery_files');
                            if (count($gallery_path) > 0) {
                                for ($i = 0; $i < count($gallery_path); $i++) {
                                    $data[$row['name']] =  $gallery_path[$i].''.$est_files[$i];
                                }
                            }
                        } else {
                            $data[$row['name']] = !empty($pp_yp_data[0][$filename]) ? $pp_yp_data[0][$filename] : '';
                        }
                    } else {
                        if ($row['type'] != 'button') {
                            if ($row['type'] == 'date') {
                                $data[$row['name']] = dateformat($postData[$row['name']]);
                            } elseif ($row['subtype'] == 'time') {
                                $data[$row['name']] = dbtimeformat($postData[$row['name']]);
                            } else if ($row['type'] == 'checkbox-group') {
                                $data[$row['name']] = !empty($postData[$row['name']]) ? implode(',', $postData[$row['name']]) : '';
                            } elseif ($row['type'] == 'textarea' && $row['subtype'] == 'tinymce') {
                                $data[$row['name']] = strip_slashes($postData[$row['name']]);
                            } else {
                                $data[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                            }
                        }
                    }
                }
            }
        }
        if(!empty($postData['weekly_report_id']))
        {
             $data['draft'] = $draftdata; 
             $data['start_date'] = dateformat($postData['start_date']);
             $data['end_date'] = dateformat($postData['end_date']);
             $data['weekly_report_id'] = $postData['weekly_report_id'];
             $data['yp_id'] = $postData['yp_id'];
             $data['modified_date'] = datetimeformat();
             $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            
             $where=array('weekly_report_id'=>$postData['weekly_report_id']);
             $this->common_model->update(WEEKLY_REPORT,$data,$where);
            
             //Insert log activity
               $activity = array(
                'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
                'module_name'         => WR_MODULE,
                'module_field_name'   => '',
                'type'                => 2
              );
              log_activity($activity);
        }
		
        else
        {
            if(!empty($data))
            {
               $data['draft'] = $draftdata; 
               $data['yp_id'] = $postData['yp_id'];
               $data['care_home_id'] = $postData['care_home_id'];
               $data['start_date'] = dateformat($postData['start_date']);
               $data['end_date'] = dateformat($postData['end_date']);
               $data['created_date'] = datetimeformat();
               $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
               $this->common_model->insert(WEEKLY_REPORT, $data);
               $data['weekly_report_id'] = $this->db->insert_id();
               //Insert log activity
               $activity = array(
                'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
                'module_name'         => WR_MODULE,
                'module_field_name'   => '',
                'type'                => 1
              );
              log_activity($activity);
            }
             
        }
        if(!empty($data))
        {
          redirect('/' . $this->viewname .'/save_weekly_report/'. $data['weekly_report_id'].'/'.$data['yp_id']);
        }
        else
        {
          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>Please  insert weekly report details.</div>");
          redirect('/' . $this->viewname .'/create/'.$postData['yp_id']);
        }
        /*===============*/
    }

    /*
      @Author : Ritesh Rana
      @Desc   : save weekly report
      @Input 	: yp id, weekly_report_id(edit)
      @Output	:
      @Date   : 21/03/2018
     */
    public function save_weekly_report($id, $ypid) {
        if (is_numeric($id) && is_numeric($ypid)) {
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($ypid,$fields);
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('errormsg', $msg);
                redirect('YoungPerson');
            }
            $data['id'] = $ypid;
            $data['main_content'] = '/save_weekly_report';

            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }
/*
      @Author : Ritesh Rana
      @Desc   : download weekly report pdf
      @Input 	: yp id, weekly_report_id(edit)
      @Output	:
      @Date   : 20/03/2018
     */
    public function DownloadPDF($weekly_report_id, $yp_id, $section = NULL) {
        $data = [];
        $match = array('wr_form_id' => 1);
        $wr_forms = $this->common_model->get_records(WR_FORM, array("form_json_data"), '', '', $match);
        if (!empty($wr_forms)) {
            $data['wr_form_data'] = json_decode($wr_forms[0]['form_json_data'], TRUE);
        }
        
        //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($yp_id,$fields);

        //get RA yp data
        $match = array('yp_id' => $yp_id, 'weekly_report_id' => $weekly_report_id);
        $data['edit_data'] = $this->common_model->get_records(WEEKLY_REPORT, '', '', '', $match);
        $data['ypid'] = $yp_id;
		
		//get medical appoinyment data
		 $table_ap = MEDICAL_PROFESSIONALS_APPOINTMENT . ' as mc';
            $where_ap = array("mc.yp_id" => $yp_id,"mc.is_delete"=>0,"mc.appointment_date >=" =>$data['edit_data'][0]['start_date'],"mc.appointment_date <=" =>$data['edit_data'][0]['end_date']);
                $fields_ap = array("mc.appointment_date,mc.comments,concat(if(mp.title != '',mp.title,''),' ',if(mp.first_name != '',mp.first_name,''),' ',if(mp.surname != '',mp.surname,''),' - ',if(mp.professional != '',mp.professional,'')) as mp_name, concat(mp.house_namenumber,',',mp.street,' ',mp.town,',',mp.city,',',mp.postcode) as address");
            $join_tables_ap = array(MEDICAL_PROFESSIONALS . ' as mp' => 'mc.mp_id= mp.mp_id',YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id');
            $data['appointments'] = $this->common_model->get_records($table_ap, $fields_ap, $join_tables_ap, 'left', $where_ap, '', '', '', 'appointment_id', 'desc', '');

		    //get medical appoinyment comments data
            $table = MD_COMMENTS . ' as com';
              $where = array("com.yp_id" => $yp_id);
              $fields = array("com.md_appoint_id,com.md_comment,com.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
              $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
              $data['appointment_view_comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);

        $table = WR_REPORT_SENT . ' as wr';
        $match_social = array('wr.yp_id'=> $yp_id,'wr.weekly_report_id' => $weekly_report_id);
        $social_where_in = array("wr.user_type" => ['Social_worker', 'Social_data']);
        $match_parent = array('wr.yp_id'=> $yp_id,'wr.weekly_report_id' => $weekly_report_id);
        $parent_where_in = array("wr.user_type" => ['Parent', 'Parent_data']);
        $match_other = array('wr.yp_id'=> $yp_id,'wr.weekly_report_id' => $weekly_report_id,"wr.user_type" => "Other");
        $fields = array("wr.*,CONCAT(wr.fname,' ', wr.lname) as staff_name");
        $data['social_worker_signoff'] = $this->common_model->get_records($table, $fields, '', '', $match_social,'','','','','','','','',$social_where_in);
        $data['parent_signoff'] = $this->common_model->get_records($table, $fields, '', '', $match_parent,'','','','','','','','',$parent_where_in);
        $data['social_worker_signoff'] = $this->common_model->get_records($table, $fields, '', '', $match_social,'','','','','','','','',$social_where_in);
        $data['other_signoff'] = $this->common_model->get_records($table, $fields, '', '', $match_other);
 
        $data['main_content'] = '/wrpdf';
        $data['section'] = $section;
        $html = $this->parser->parse('layouts/PDFTemplate', $data);
        $pdfFileName = "weekly_report" . $weekly_report_id . ".pdf";
        $pdfFilePath = FCPATH . 'uploads/weeklyreport/';
        if (!is_dir(FCPATH . 'uploads/weeklyreport/')) {
            @mkdir(FCPATH . 'uploads/weeklyreport/', 0777, TRUE);
        }
        if (file_exists($pdfFilePath . $pdfFileName)) {
            unlink($pdfFilePath . $pdfFileName);
        }
        
        $this->load->library('m_pdf');

        $PDFInformation['yp_details'] = $data['YP_details'][0];
        
        $PDFInformation['edit_data'] = $data['edit_data'];
        
        $PDFHeaderHTML = $this->load->view('wrpdfHeader', $PDFInformation, true);
        $PDFFooterHTML = $this->load->view('wrpdfFooter', $PDFInformation, true);

        //Set Header Footer and Content For PDF
        $this->m_pdf->pdf->mPDF('utf-8','A4','','','15','15','57','25');
        $this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
        $data['main_content'] = '/wrpdf';
        $html = $this->parser->parse('layouts/PdfDataTemplate', $data);
           /*remove*/
        $this->m_pdf->pdf->WriteHTML($html);
        //Store PDF in individual_strategies Folder
                
        if(!empty($section) && $section == 'download'){
            $this->m_pdf->pdf->Output($pdfFileName, "D");
        } else {
            if (!is_dir($this->config->item('wr_pdf_base_path'))) {
                mkdir($this->config->item('wr_pdf_base_path'), 0777, TRUE);
            }
            $pdfFileSavePath = $this->config->item('wr_pdf_base_path') . $pdfFileName;
            $this->m_pdf->pdf->Output($pdfFileSavePath, "F");
            return $pdfFileSavePath;
        }
    }
    /*
      @Author : Ritesh Rana
      @Desc   : signoff
      @Input 	: yp id, weekly_report_id(edit)
      @Output	:
      @Date   : 20/03/2018
     */
    public function manager_review($yp_id, $weekly_report_id) {

        if (!empty($yp_id)) {

            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $YP_details = YpDetails($yp_id,$fields);

            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $match = array('yp_id' => $yp_id, 'created_by' => $login_user_id, 'is_delete' => '0', 'weekly_report_id' => $weekly_report_id);
            $check_signoff_data = $this->common_model->get_records(WEEKLY_REPORT_SIGNOFF, array("*"), '', '', $match);
            if (empty($check_signoff_data) > 0) {
                $update_pre_data['care_home_id'] = $YP_details[0]['care_home'];
                $update_pre_data['yp_id'] = $yp_id;
                $update_pre_data['weekly_report_id'] = $weekly_report_id;
                $update_pre_data['created_date'] = datetimeformat();
                $update_pre_data['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
                if ($this->common_model->insert(WEEKLY_REPORT_SIGNOFF, $update_pre_data)) {
                    $u_data['signoff'] = '1';
                    $this->common_model->update(WEEKLY_REPORT, $u_data, array('weekly_report_id' => $weekly_report_id, 'yp_id' => $yp_id));
                    $msg = $this->lang->line('successfully_wr_review');
                    $this->session->set_flashdata('successmsg', $msg);
                } else {
                    // error
                    $msg = $this->lang->line('error_msg');
                    $this->session->set_flashdata('errormsg', $msg);
                }
            } else {
                $msg = $this->lang->line('already_wr_review');
                $this->session->set_flashdata('errormsg', $msg);
            }
        } else {
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('errormsg', $msg);
        }


        redirect('/' . $this->viewname . '/index/' . $yp_id);
    }
/*
      @Author : Rtiesh Rana
      @Desc   : update slug
      @Input 	: 
      @Output	:
      @Date   : 20/03/2018
     */
    public function update_slug() {
        $postData = $this->input->post();
        $where = array('url_data' => $postData['url_data']);
        $this->common_model->delete(CHECK_EDIT_URL, $where);

        $update_pre_data['datetime'] = date('Y-m-d H:i:s');
        $update_pre_data['url_data'] = $postData['url_data'];
        $this->common_model->insert(CHECK_EDIT_URL, $update_pre_data);
        return TRUE;
        
    }

/*
      @Author : Ritesh Rana
      @Desc   : send weekly report to support worker
      @Input 	: yp id, weekly_report_id(edit)
      @Output	:
      @Date   : 22/03/2018
     */
    public function send_report($yp_id, $weekly_report_id, $checkSocialWorker = NULL) {
        
            
            $this->formValidation();
            
            if ($this->form_validation->run() == FALSE) {
                $data['crnt_view'] = $this->viewname;

                //get YP information

            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $YP_details = YpDetails($yp_id,$fields);

                $data['care_home_id'] = $YP_details[0]['care_home']; 
                $data['ypid'] = $yp_id;
                $data['weekly_report_id'] = $weekly_report_id;

                $data['form_action_path'] = $this->viewname . '/send_report/' . $weekly_report_id . '/' . $yp_id;
                $data['header'] = array('menu_module' => 'YoungPerson');
               
                $table = PARENT_CARER_INFORMATION;
                $match = array("yp_id" => $yp_id,'is_deleted' => 0);
                $fields = array("parent_carer_id,firstname,surname");
                $data['parentRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
                
                $table = SOCIAL_WORKER_DETAILS;
                $match = "yp_id = " . $yp_id;
                $fields = array("social_worker_id,social_worker_firstname,social_worker_surname");
                $data['socialWorkerRecord'] = $this->common_model->get_records($table, $fields, '', 'left', $match);
                
                $data['validation'] = validation_errors();
                if (!empty($data['validation'])) {
                    $data['main_content'] = '/signoff';
                    $this->parser->parse('layouts/DefaultTemplate', $data);
                } else {
                    $this->load->view('/signoff', $data);
                }
            } else { 
                $this->insertdata();
            }
    }
/*
      @Author : Ritesh Rana
      @Desc   : insert data of sent weekly report
      @Input 	: yp id, weekly_report_id(edit)
      @Output	:
      @Date   : 22/03/2018
     */
    public function insertdata($social_worker = array(), $yp_id = '', $weekly_report_id = '') {
        $postdata = $this->input->post();
        //pr($postdata);exit;
        $ypid = !empty($yp_id) ? $yp_id : $postdata['ypid'];
        $pp_id = !empty($weekly_report_id) ? $weekly_report_id : $postdata['weekly_report_id'];
        $user_type = $postdata['user_type'];
        if ($user_type == 'parent') {
              $parent_data['firstname'] = strip_tags($postdata['fname']);
              $parent_data['surname'] = strip_tags($postdata['lname']);
              $parent_data['relationship'] = strip_tags($postdata['relationship']);
              $parent_data['address'] = strip_tags($postdata['address']);
              $parent_data['contact_number'] = strip_tags($postdata['contact_number']);
              $parent_data['email_address'] = strip_tags($postdata['email']);
              $parent_data['yp_authorised_communication'] = strip_tags($postdata['yp_authorised_communication']);
              $parent_data['carer_authorised_communication'] = strip_tags($postdata['carer_authorised_communication']);
              $parent_data['yp_authorised_communication'] = strip_tags($postdata['yp_authorised_communication']);
              $parent_data['comments'] = strip_tags($postdata['comments']);
              $parent_data['created_date'] = datetimeformat();
              $parent_data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
              $parent_data['yp_id'] = $this->input->post('ypid');
              $parent_data['care_home_id'] = $this->input->post('care_home_id');
              $success = $this->common_model->insert(PARENT_CARER_INFORMATION, $parent_data);
              //Insert log activity
              $activity = array(
                  'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                  'yp_id' => !empty($postdata['ypid']) ? $postdata['ypid'] : '',
                  'module_name' => WR_PARENT_CARER_DETAILS_YP,
                  'module_field_name' => '',
                  'type' => 1
              );
            log_activity($activity);
        }
        $table = YP_DETAILS . ' as yp';
        $match = "yp.is_deleted= '0' AND yp.yp_id = '" . $ypid . "'";
        $fields = array("yp.yp_id,yp.status,yp.yp_fname,yp.yp_lname");
        $duplicateEmail = $this->common_model->get_records($table, $fields, '', '', $match);
        //Current Login detail
        $main_user_data = $this->session->userdata('LOGGED_IN');

        if(empty($social_worker)){
            if (!validateFormSecret()) {
                $this->session->set_flashdata('errormsg', lang('error'));
                redirect('Dashboard'); //Redirect On Listing page
            }
        }
        $data['crnt_view'] = $this->viewname;
        if(!empty($social_worker)){
            $fname = $social_worker[0]['social_worker_firstname'];
            $lname = $social_worker[0]['social_worker_surname'];			
            $email = $social_worker[0]['email'];
        } else {
            $fname = $postdata['fname'];
            $lname = $postdata['lname'];
            $email = $postdata['email'];
        }
		$fullname = $fname. " " .$lname;
        $data = array(
            'care_home_id' => $postdata['care_home_id'],
            'yp_id' => ucfirst($ypid),
            'weekly_report_id' => ucfirst($pp_id),
            'fname' => ucfirst($fname),
            'lname' => ucfirst($lname),
            'user_type' => ucfirst($postdata['user_type']),
            'email' => $email,
            'key_data' => md5($email),
            'created_date' => datetimeformat(),
            'modified_date' => datetimeformat(),
            'created_by' => $main_user_data['ID'],
            'updated_by' => $main_user_data['ID'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(WR_REPORT_SENT, $data)) {

            $signoff_id = $this->db->insert_id();

            $savePdfPath = $this->DownloadPDF($pp_id,$ypid); // send mail
            
            $this->sendMailToRelation($data, $savePdfPath, $fullname, $duplicateEmail); // send mail
            //Insert log activity
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($ypid) ? $ypid : '',
                'module_name' => WR_MODULE,
                'module_field_name' => '',
                'type' => 1
            );
            log_activity($activity);
            $msg = $this->lang->line('wr_report_sent_msg');
            $this->session->set_flashdata('successmsg', $msg);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('errormsg', $msg);
        }

        redirect('WeeklyReport/index/' . $ypid);
    }
    /*
      @Author : Ritesh Rana
      @Desc   : send email to support worker
      @Input 	: email data
      @Output	:
      @Date   : 24/03/2018
     */
    private function sendMailToRelation($data = array(), $pdfPath = '', $fullname = '', $yp_data) {

        if (!empty($data) && !empty($pdfPath)) {

            $getEmailTemplateData = $this->common_model->get_records(EMAIL_TEMPLATE_MASTER, array('*'), '', '', ['template_id' => '2']);

            if (!empty($getEmailTemplateData)) {

                $subject = $getEmailTemplateData[0]['subject'];
                $emailBody = $getEmailTemplateData[0]['body'];

                $finalSubject = str_replace('{SITE_NAME}', SITE_NAME, $subject);

                $finalEmailBody = str_replace(array('{USERNAME}','{YP_NAME}'), array($fullname, $yp_data[0]['yp_fname'] . " " . $yp_data[0]['yp_lname']), $emailBody);
                // End body
                $this->common_model->sendEmail($data['email'], $finalSubject, $finalEmailBody, FROM_EMAIL_ID, '', '', '', $pdfPath);
            }
            unlink($pdfPath);       
        }
        return true;
    }
/*
      @Author : Ritesh Rana
      @Desc   : form validation rules
      @Input 	: 
      @Output	:
      @Date   : 24/03/2018
     */
    public function formValidation($id = null) {
        $this->form_validation->set_rules('fname', 'Firstname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('lname', 'Lastname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
    }
    /*
      @Author : Ritesh Rana
      @Desc   : delete weekly report
      @Input 	: yp_id, weekly report id
      @Output	:
      @Date   : 26/03/2018
     */
    public function deletedata($id, $ypid){
        if(is_numeric($id) && is_numeric($ypid))
        {
           $match = array('weekly_report_id'=> $id);
           $wr_data = $this->common_model->get_records(WEEKLY_REPORT,array("*"), '', '', $match);

           if(!empty($wr_data))
           {
                $update_arr = array(
                    'is_delete'=> 1
                );
                $where = array('weekly_report_id'=>$id);
                $this->common_model->update(WEEKLY_REPORT, $update_arr,$where);
                //Insert log activity
                $activity = array(
                'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
                'module_name'         => WR_MODULE,
                'module_field_name'   => '',
                'yp_id'   => $ypid,
                'type'                => 3
                );
                log_activity($activity);
                redirect('/WeeklyReport/index/'. $ypid);
           }
           else
           {
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>No data to delete.</div>");
                redirect('/WeeklyReport/index/'. $ypid);
           }
        }
        else
        {
            show_404 ();
        }
    }
    /*
      @Author : Ritesh Rana
      @Desc   : get social worker detial
      @Input 	: yp_id, user type
      @Output	:
      @Date   : 04/04/2018
     */
    public function getStaffData(){
        $user_type = !empty($this->input->post("user_type"))?$this->input->post("user_type"):''; 
        $yp_id = $this->input->post("yp_id");
        $id = $this->input->post('id'); 
        $table = YP_DETAILS . ' as yp';
        $match = "yp.yp_id = " . $yp_id;
        $fields = array("yp.*,swd.social_worker_firstname,swd.social_worker_surname,swd.landline,swd.mobile,swd.email,swd.senior_social_worker_firstname,swd.senior_social_worker_surname,swd.other,pc.parent_carer_id,pc.firstname,pc.surname,pc.relationship,pc.address,pc.contact_number,pc.email_address,pc.yp_authorised_communication,pc.carer_authorised_communication,pc.comments");
        $join_tables = array(SOCIAL_WORKER_DETAILS . ' as swd' => 'swd.yp_id= yp.yp_id', PARENT_CARER_INFORMATION . ' as pc' => 'pc.yp_id= yp.yp_id');

        $data['editRecord'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);

        if($user_type == 'parent_data'){
            $table = PARENT_CARER_INFORMATION . ' as pc';
            $match = array("pc.parent_carer_id" => $id,'pc.is_deleted' => 0);
            $fields = array("pc.*");
            $data['parent_data'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['editRecord'][0]['fname'] = $data['editRecord'][0]['firstname'];
            $data['editRecord'][0]['lname'] = $data['editRecord'][0]['surname'];
            $data['editRecord'][0]['email_id'] = $data['editRecord'][0]['email_address'];
            
        } else if($user_type == 'social_data'){
            $table = SOCIAL_WORKER_DETAILS . ' as sw';
            $match = array("sw.social_worker_id" => $id);
            $fields = array("sw.*");
            $data['social_worker_data'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['editRecord'][0]['fname'] = $data['editRecord'][0]['social_worker_firstname'];
            $data['editRecord'][0]['lname'] = $data['editRecord'][0]['social_worker_surname'];
            $data['editRecord'][0]['email_id'] = $data['editRecord'][0]['email'];
        }
        $data['user_type'] = $user_type;
        $this->load->view($this->viewname . '/signoff_ajax', $data);
    }
    /*
      @Author : Ritesh Rana
      @Desc   : get list of external approver
      @Input 	: yp_id, wr_id
      @Output	:
      @Date   : 12/04/2018
     */
    public function reportReviewedBy($wr_id, $yp_id,$care_home_id=0,$past_care_id=0) {

 /*
                Ritesh Rana
                for past care id inserted for archive full functionality
                */
        if ($past_care_id !== 0) {
            $temp = $this->common_model->get_records(PAST_CARE_HOME_INFO, array('move_date'), '', '', array("yp_id" => $yp_id, "past_carehome" => $care_home_id));
            $data_care_home_detail = $this->common_model->get_records(PAST_CARE_HOME_INFO, array("enter_date,move_date"), '', '', array("yp_id" => $yp_id, "move_date <= " => $temp[0]['move_date']));
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

        if (is_numeric($wr_id) && is_numeric($yp_id)) { 
            
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($yp_id,$fields);

            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = RECORD_PER_PAGE;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('wr_data');
            }

            $searchsort_session = $this->session->userdata('wr_data');
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
                    $sortfield = 'wr.created_date';
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
                    $config['per_page'] = RECORD_PER_PAGE;
                    $data['perpage'] = RECORD_PER_PAGE;
                }
            }
            //pagination configuration
            $config['first_link'] = 'First';
            if($past_care_id == 0){
                $config['base_url'] = base_url() . $this->viewname . '/reportReviewedBy/' . $yp_id . '/' . $wr_id;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 5;
                $uri_segment = $this->uri->segment(5);
            }

            $table = WR_REPORT_SENT . ' AS wr';
            $fields = array('wr.user_type,wr.created_date,CONCAT(l.firstname," ", l.lastname) as sent_by, CONCAT(wr.fname," ", wr.lname) as review_by,ch.care_home_name');
            $where = array('wr.weekly_report_id' => $wr_id, 'wr.yp_id' => $yp_id);
            $join_tables = array(LOGIN . ' as l' => 'l.login_id=wr.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = wr.care_home_id');
            
            if (!empty($searchtext)) {
                $searchtext = html_entity_decode(trim(addslashes($searchtext)));
                $match = '';
                $where_search = '((l.firstname LIKE "%' . $searchtext . '%" OR l.lastname LIKE "%' . $searchtext . '%" OR wr.fname LIKE "%' . $searchtext . '%" OR wr.lname LIKE "%' . $searchtext . '%" OR wr.created_date LIKE "%' . $searchtext . '%") AND l.is_delete = "0" AND wr.weekly_report_id = "'. $wr_id . '" AND wr.yp_id = "' .$yp_id .'")';

                $data['wr_signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);

                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1');
            } else {
                $data['wr_signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);

                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
            }
            }else{
             $config['base_url'] = base_url() . $this->viewname . '/reportReviewedBy/' . $yp_id . '/' . $wr_id.'/'.$care_home_id.'/'.$past_care_id;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 7;
                $uri_segment = $this->uri->segment(7);
            }
            
            $table = WR_REPORT_SENT . ' AS wr';
            $fields = array('wr.user_type,wr.created_date,wr.sent_by,CONCAT(l.firstname," ", l.lastname) as sent_by, CONCAT(wr.fname," ", wr.lname) as review_by,ch.care_home_name');
            $where = array('wr.weekly_report_id' => $wr_id, 'wr.yp_id' => $yp_id);
            $where_date = "wr.created_date BETWEEN  '".$created_date."' AND '".$movedate."'";
            $join_tables = array(LOGIN . ' as l' => 'l.login_id=wr.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = wr.care_home_id');
            
            if (!empty($searchtext)) {
                $searchtext = html_entity_decode(trim(addslashes($searchtext)));
                $match = '';
                $where_search = '((l.firstname LIKE "%' . $searchtext . '%" OR l.lastname LIKE "%' . $searchtext . '%" OR wr.fname LIKE "%' . $searchtext . '%" OR wr.lname LIKE "%' . $searchtext . '%" OR wr.created_date LIKE "%' . $searchtext . '%") AND l.is_delete = "0" AND wr.weekly_report_id = "'. $wr_id . '" AND wr.yp_id = "' .$yp_id .'")';

                $data['wr_signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search,'','','','','',$where_date);

                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1','','',$where_date);
            } else {
                $data['wr_signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where,'','','','','',$where_date);
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
            
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson');
            }
            $data['ypid'] = $yp_id;
            $data['care_home_id'] = $care_home_id;
            $data['past_care_id'] = $past_care_id;
            $data['weekly_report_id'] = $wr_id;
            $data['footerJs'][0] = base_url('uploads/custom/js/wr/wr.js');
            $data['crnt_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'YoungPerson');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/reviewed_ajaxlist', $data);
            } else {
                $data['main_content'] = '/reviewed_by';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }
    
}
