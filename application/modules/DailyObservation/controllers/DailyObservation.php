<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DailyObservation extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session','m_pdf'));
    }

    /*
      @Author : Niral Patel
      @Desc   : DO LIst Page
      @Input    :
      @Output   :
      @Date   : 13/07/2017
     */

    public function index($ypid,$care_home_id=0,$past_care_id=0) {
                /*
                Ritesh Rana
                for past care id inserted for archive full functionality
                */
         if ($past_care_id !== 0) {
            $temp = $this->common_model->get_records(PAST_CARE_HOME_INFO, array('move_date'), '', '', array("yp_id" => $ypid, "past_carehome" => $care_home_id));
            $data_care_home_detail = $this->common_model->get_records(PAST_CARE_HOME_INFO, array("enter_date,move_date"), '', '', array("yp_id" => $ypid, "move_date <= " => $temp[0]['move_date']));
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
       
        if (is_numeric($ypid)) {
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($ypid,$fields);
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/'.$ypid);
            }
            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = 10;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('daily_observation_data');
            }

            $searchsort_session = $this->session->userdata('daily_observation_data');
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

                    $sortfield = 'todaydate,daily_observation_date';
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
                    $config['per_page'] = '10';
                    $data['perpage'] = '10';
                }
            }
            //pagination configuration
        $config['first_link'] = 'First';
        if($past_care_id == 0){
            $config['base_url'] = base_url() . $this->viewname . '/index/' . $ypid;
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 4;
                $uri_segment = $this->uri->segment(4);
            }

                $today_date = date('Y-m-d');
                $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
                $table = DAILY_OBSERVATIONS . ' as do';
                $where = array("do.yp_id" => $ypid);
                $fields = array("do.do_id,do.yp_id,do.is_filled_field,do.is_filled_field_overview,If(do.daily_observation_date = '".$today_date."',1,0) as todaydate,do.daily_observation_date,CONCAT(`firstname`,' ', `lastname`) as create_name,ch.care_home_name");
                $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = do.care_home_id');
                if (!empty($searchtext)) {
                    
                } else {
                    $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
                    
                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
                }

            }else{
                $config['base_url'] = base_url() . $this->viewname . '/index/' . $ypid .'/'. $care_home_id .'/'.$past_care_id;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 6;
                $uri_segment = $this->uri->segment(6);
            }
            //Query
                $today_date = date('Y-m-d');
                $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
                $table = DAILY_OBSERVATIONS . ' as do';
                $where = array("do.yp_id" => $ypid);
                $where_date = "do.created_date BETWEEN  '".$created_date."' AND '".$movedate."'";
                $fields = array("do.do_id,do.yp_id,do.is_filled_field,do.is_filled_field_overview,If(do.daily_observation_date = '".$today_date."',1,0) as todaydate,do.daily_observation_date,CONCAT(`firstname`,' ', `lastname`) as create_name,ch.care_home_name");
                $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = do.care_home_id');
                if (!empty($searchtext)) {
                    
                } else {
                    $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where,'','','','','',$where_date);

                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1','','',$where_date);
                }
            }
            $data['ypid'] = $ypid;
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

            $this->session->set_userdata('daily_observation_data', $sortsearchpage_data);
            setActiveSession('daily_observation_data'); // set current Session active
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['care_home_id'] = $care_home_id;
            $data['past_care_id'] = $past_care_id;
            //get YP information
            $data['crnt_view'] = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/ajaxlist', $data);
            } else {
                $data['main_content'] = '/list';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : create do page
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */

    public function createDo($ypid) {
        if (is_numeric($ypid)) {
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($ypid,$fields);
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('/' . $this->viewname . '/index/' . $ypid);
            }
            $data['care_home_id'] = $data['YP_details'][0]['care_home'];
            $data['ypid'] = $ypid;
            $data['main_content'] = '/create_do';
            $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
            $data['header'] = array('menu_module' => 'YoungPerson');
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Insert select date
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */

    public function checkDo() {
        $postData = $this->input->post();
        //get daily observation data
        $table = DAILY_OBSERVATIONS . ' as do';
        $where = array("do.daily_observation_date" => "'".dateformat($postData['create_date'])."'", "do.yp_id" => $postData['yp_id']);
        $fields = array("do.do_id,do.yp_id,do.daily_observation_date");
        $information = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
        $data['ypid'] = $postData['yp_id'];
        $data['created_date'] = $postData['create_date'];
        if (empty($information)) {
            //insert data of do
            $insertdata = array(
                'yp_id' => $postData['yp_id'],
                'daily_observation_date' => dateformat($postData['create_date']),
                'created_by' => !empty($this->session->userdata('LOGGED_IN')['ID']) ? $this->session->userdata('LOGGED_IN')['ID'] : '',
                'created_date' => datetimeformat(),
                'care_home_id' => $postData['care_home_id'],
            );
            $data['do_id'] = $this->common_model->insert(DAILY_OBSERVATIONS, $insertdata);
            $data['is_created'] = true;
            //Insert log activity
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                'module_name' => DO_MODULE,
                'module_field_name' => '',
                'type' => 1
            );
            log_activity($activity);
            
        } else {
            $data['is_created'] = false;
            $data['do_id'] = !empty($information[0]['do_id']) ? $information[0]['do_id'] : '';
        }
        $data['main_content'] = '/verify_create_do';
        $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
        $data['header'] = array('menu_module' => 'YoungPerson');
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : View Page
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */

    public function view($id, $ypid, $care_home_id=0,$past_care_id=0) {
        if (is_numeric($id) && is_numeric($ypid)) {

            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($ypid,$fields);
            
            $table = OB_COMMENTS . ' as com';
            $where = array("com.doid" => $id, "com.yp_id" => $ypid);
            $fields = array("com.overview_comments,com.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
            $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
            
            //get daily observation data
            $data['dodata'] = $this->DailyObservations($ypid,$id);
           
            $data['NextAndPreDodata'] = $this->getNextDailyObservations($ypid,$id,$data['dodata'][0]['daily_observation_date']);
            
            //get last day do
            $table = DAILY_OBSERVATIONS . ' as do';
            $where = array("do.daily_observation_date" => "'".date('Y-m-d',(strtotime ( '-1 day' , strtotime ($data['dodata'][0]['daily_observation_date']) )))."'", "do.yp_id" => $ypid);
            $fields = array("do.do_id,do.handover_next_day");
            $data['lastDayData'] = $this->common_model->get_records($table, $fields,'', '', '', '', '', '', '', '', '', $where);
            //get appointment data and medical appointment data
            $data['do_professionals_data'] = $this->MedicalProfessionalsAppointment($ypid,$data['dodata'][0]['daily_observation_date']);
            //Appointment View Comments
            $data['appointment_view_comments'] = $this->AppointmentViewComments($ypid);
            //get planner appointment
            $table = PLANNER . ' as dpa';
            $where = array( "dpa.yp_id" => $ypid, 
                            'appointment_date' => "'".$data['dodata'][0]['daily_observation_date']."'",
                            'is_delete' => 0
                            );
            $fields = array("dpa.appointment_date,dpa.appointment_time,dpa.appointment_type,dpa.comments");
            $data['do_planner_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

            $daily_observation_date = $data['dodata'][0]['daily_observation_date'];
            //get adminstry medication
            $table = ADMINISTER_MEDICATION . ' as mc';
            $fields = array("mc.*,md.stock");
            $where = array("mc.yp_id"=>$ypid ,'mc.date_given' => "'".$daily_observation_date."'");
            $join_tables = array(MEDICATION . ' as md' => 'md.medication_id=mc.select_medication');
            $data['administer_medication'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '','', 'administer_medication_id','desc', '', $where);

            //get am form
            $match = array('am_form_id'=> 1);
            $formsdata = $this->common_model->get_records(AM_FORM,array("form_json_data"), '', '', $match);
            if(!empty($formsdata))
            {
                $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }
            $match = array('yp_id'=> $ypid);
            $medication_data = $this->common_model->get_records(MEDICATION, array('*') , '', '', $match);
                
            $table = DO_PREVIOUS_VERSION . ' as do';
            $where = array("do.do_id" => $id, "do.yp_id" => $ypid);
            $fields = array("do.*");
            $data['do_prev_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            if (empty($data['dodata'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('/' . $this->viewname . '/index/' . $ypid);
            }
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            /* this block updated by Dhara Bhalala to display overall signoff(current+archive) as per client requirement(google sheet point-403)*/
            $table = DO_SIGNOFF . ' as do';
            $where = array("l.is_delete" => "0", "do.yp_id" => $ypid, "do_id" => $id);
            $fields = array("do.created_by,do.created_date,do.yp_id,do.do_id, CONCAT(`firstname`,' ', `lastname`) as name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id=do.created_by');
            $group_by = array('created_by');
            $data['do_signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);

            $table = DO_SIGNOFF.' as do';
            $where = array("do.yp_id" => $ypid,"do_id" => $id,"do.created_by" => $login_user_id);
            $fields = array("do.do_id");
            $data['check_do_signoff_data'] = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);
            
            //get staff details
            $table = DO_STAFF_TRANSECTION . ' as do';
            $where = array("do.do_id" => $id);
            $fields = array("do.do_id,do.is_new,CONCAT(l.firstname,' ', l.lastname) as staff_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.user_id');
            $data['do_staff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);

            //get food form
            $match = array('food_form_id' => 1);
            $food_forms = $this->common_model->get_records(FOOD_FORM, array("form_json_data"), '', '', $match);
            if (!empty($food_forms)) {
                $data['food_form_data'] = json_decode($food_forms[0]['form_json_data'], TRUE);
            }

            //get food data
            $match = array('do_id' => $id, 'is_previous_version' => 0);
            $data['food_data'] = $this->common_model->get_records(DO_FOODCONSUMED, '', '', '', $match);

            //get food data
            $match = array('do_id' => $id, 'is_previous_version' => 1);
            $data['food_previous_data'] = $this->common_model->get_records(DO_FOODCONSUMED, '', '', '', $match);

         //get external approve
            $table = NFC_DO_SIGNOFF_DETAILS;
            $fields = array('key_data,do_signoff_details_id,do_id');
            $where = array('do_id' => $id, 'yp_id' => $ypid);
            $data['check_external_signoff_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            //get SUMMARIES form
            $match = array('do_form_id' => 1);
            $food_forms = $this->common_model->get_records(DO_FORM, array("form_json_data"), '', '', $match);
            if (!empty($food_forms)) {
                $data['summary_form_data'] = json_decode($food_forms[0]['form_json_data'], TRUE);
            }
            $data['past_care_id'] = $past_care_id;
            $data['care_home_id'] = $care_home_id;
            $data['do_id'] = $id;
            $data['ypid'] = !empty($data['dodata'][0]['yp_id']) ? $data['dodata'][0]['yp_id'] : '';

            $data['main_content'] = '/view';
            $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
            $data['header'] = array('menu_module' => 'YoungPerson');
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }

    //ishani dave
    //edit form data 
    public function edit_do($id, $ypid) {

        if (is_numeric($id) && is_numeric($ypid)) {

            //get do form data
            $match = array('do_form_id' => 1);
            $fields = array("form_json_data");
            $do_forms = $this->common_model->get_records(DO_FORM, $fields, '', '', $match);
            if (!empty($do_forms)) {
                $data['do_form_data'] = json_decode($do_forms[0]['form_json_data'], TRUE);
            }
            //get food form
            $match = array('food_form_id' => 1);
            $food_forms = $this->common_model->get_records(FOOD_FORM, $fields, '', '', $match);
            if (!empty($food_forms)) {
                $data['food_form_data'] = json_decode($food_forms[0]['form_json_data'], TRUE);
            }
            //get food data
            $fields = array("*");
            $match = array('do_id' => $id, 'is_previous_version' => 0);
            $data['food_data'] = $this->common_model->get_records(DO_FOODCONSUMED, $fields, '', '', $match);

            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($ypid,$fields);
            //get do yp data
            $data['edit_data'] = $this->DailyObservations($ypid,$id);
            //get pp yp data
            $url_data = base_url('DailyObservation/edit_do/' . $id.'/'.$ypid);
            $match = array('url_data' => $url_data);
            $fields = array("*");
            $data['check_edit_permission'] = $this->common_model->get_records(CHECK_EDIT_URL, array("*"), '', '', $match);

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
                    $data['care_home_id'] =  $data['YP_details'][0]['care_home'];    
                    $data['ypid'] = $ypid;
                    $data['do_id'] = $id;

                    $data['pass_yp_id'] = $ypid; 
                    // Mantis issue #7390, Ritesh rana, Dt: 6th Dec 2017
                    $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
                    $data['crnt_view'] = $this->viewname;
                    $data['header'] = array('menu_module' => 'YoungPerson');
                    $data['main_content'] = '/edit';
                    $this->parser->parse('layouts/DefaultTemplate', $data);
                } else {
                    $msg = $this->lang->line('check_do_user_update_data');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                    redirect('/' . $this->viewname . '/view/' . $id.'/'.$ypid);
                }
            } else {
                $data['pass_yp_id'] = $ypid;
                $data['care_home_id'] = $data['YP_details'][0]['care_home'];
                $data['ypid'] = $ypid;
                $data['do_id'] = $id;
                 // Mantis issue #7390, Ritesh rana, Dt: 6th Dec 2017
                $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
                $data['crnt_view'] = $this->viewname;
                $data['header'] = array('menu_module' => 'YoungPerson');
                $data['main_content'] = '/edit';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }

    /*Function updated by Dhara Bhalala to add 1 field to ignore list from count as per client requirement 

      @Author : Ishani Dave
      @Desc   : Update do form
      @Input    :
      @Output   :
      @Date   : 21/11/2017
     */

    public function update() {
        if (!validateFormSecret()) {
            redirect($_SERVER['HTTP_REFERER']);  //Redirect On Listing page
        }
        $postData = $this->input->post();
        unset($postData['submitdoform']);
        //update food consumed data
        //get food data
        
        $match_foodata = array('do_id' => $postData['do_id'], 'yp_id' => $postData['yp_id'], 'is_previous_version' => 0);
                    $food_data = $this->common_model->get_records(DO_FOODCONSUMED, array("*"), '', '', $match_foodata);

        $food_yp_data = $food_data;

        //get food data
        $food_prev_data = $this->common_model->get_records(DO_FOODCONSUMED, array("*"), '', '', array('do_id' => $postData['do_id'], 'is_previous_version' => 1));

        //get food form
        $form_data = $this->common_model->get_records(FOOD_FORM, array("form_json_data"), '', '', array('food_form_id' => 1));
        $food_form_data = json_decode($form_data[0]['form_json_data'], TRUE);
        $is_filled = 1;

                         $match_foodata_with = array('do_id' => $postData['do_id']);
                                        $old_data = $this->common_model->get_records(DO_FOODCONSUMED, array("*"), '', '', $match_foodata_with);

                                        
        if (!empty($form_data)) {
            $data = array();
            foreach ($food_form_data as $row) {
                if (isset($row['name'])) {
                    if (empty($food_data[0][$row['name']]) && empty($postData[$row['name']]) && $postData[$row['name']] != '0') {
                        $is_filled = 0;
                    }
                    if ($row['type'] == 'file') {
                        $filename = $row['name'];
                        //get image previous image
                        
                        //delete img
                        if (!empty($postData['hidden_' . $row['name']])) {
                            $delete_img = explode(',', $postData['hidden_' . $row['name']]);
                            $db_images = explode(',', $food_yp_data[0][$filename]);
                            $differentedImage = array_diff($db_images, $delete_img);
                            $food_yp_data[0][$filename] = !empty($differentedImage) ? implode(',', $differentedImage) : '';
                        }
                        if (!empty($_FILES[$filename]['name'][0])) {
                            /* common function replaced by Dhara Bhalala on 29/09/2018 */                            
                            createDirectory(array($this->config->item('do_base_url'),$this->config->item('do_base_big_url'),$this->config->item('do_base_big_url') . '/' . $postData['yp_id']));
                           
                            $file_view = $this->config->item('do_img_url') . $postData['yp_id'];
                            //upload big image
                            $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/index/' . $postData['yp_id']);

                            //upload small image
                            $insertImagesData = array();
                            if (!empty($upload_data)) {
                                foreach ($upload_data as $imageFiles) {
                                    /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                    createDirectory(array($this->config->item('do_base_small_url'),$this->config->item('do_base_small_url') . '/' . $postData['yp_id']));
                                    
                                    /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                    if ($imageFiles['is_image'])
                                        $a = do_resize($this->config->item('do_img_url') . $postData['yp_id'], $this->config->item('do_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                    array_push($insertImagesData, $imageFiles['file_name']);
                                    if (!empty($insertImagesData)) {
                                        $images = implode(',', $insertImagesData);
                                    }
                                }
                                if (!empty($food_yp_data[0][$filename])) {
                                    $images .= ',' . $food_yp_data[0][$filename];
                                }
                                $data[$row['name']] = !empty($images) ? $images : '';
                            }
                        } else {
                            $data[$row['name']] = !empty($food_yp_data[0][$filename]) ? $food_yp_data[0][$filename] : '';
                        }
                    } else {
                        $jdata = array();
                        if ($row['type'] != 'button') {
                            if ($row['type'] == 'checkbox-group') {
                                $data[$row['name']] = !empty($postData[$row['name']]) ? implode(',', $postData[$row['name']]) : '';
                            } elseif ($row['type'] == 'textarea' && $row['subtype'] == 'tinymce') {

                                if (checkPermission('DailyObservation', 'external_edit')) {
                                    if (!empty($postData[$row['name']])) {
                                        $jdata[] = array('date' => datetimeformat(), 'content' => strip_slashes($postData[$row['name']]), 'user_id' => $this->session->userdata['LOGGED_IN']['ID']);
                                    }
                                    $data[$row['name']] = json_encode($jdata);
                                } else {
                                    if (!empty($postData[$row['name']])) {
                                        //get image previous image
                                       

                                        if (!empty($old_data[0][$row['name']])) {
                                            $odata = $old_data[0][$row['name']];

                                            $json_data = json_decode($odata);
                                            if (!empty($postData[$row['name']])) {
                                                $newdata[] = array('date' => datetimeformat(), 'content' => strip_slashes($postData[$row['name']]), 'user_id' => $this->session->userdata['LOGGED_IN']['ID']);
                                                $new_json = array_merge($json_data, $newdata);
                                                $data[$row['name']] = json_encode($new_json);
                                            } else {
                                                $data[$row['name']] = $odata;
                                            }
                                        } else {
                                            $jdata[] = array('date' => datetimeformat(), 'content' => strip_slashes($postData[$row['name']]), 'user_id' => $this->session->userdata['LOGGED_IN']['ID']);

                                            $data[$row['name']] = json_encode($jdata);
                                        }
                                    }
                                }
                            } elseif ($row['type'] == 'select') {
                                $data[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                            }elseif($row['subtype'] == 'time'){
                                    $data[$row['name']] = dbtimeformat($postData[$row['name']]);
                            } elseif ($row['type'] == 'date') {
                                $data[$row['name']] = dateformat($postData[$row['name']]);
                            } else {

                                if (checkPermission('DailyObservation', 'external_edit')) {
                                    if (!empty($postData[$row['name']])) {
                                        $jdata[] = array('date' => datetimeformat(), 'content' => strip_slashes($postData[$row['name']]), 'user_id' => $this->session->userdata['LOGGED_IN']['ID']);
                                    }
                                    $data[$row['name']] = json_encode($jdata);
                                } else {
                                    if (!empty($postData[$row['name']])) {
                                        //get image previous image
                                        if (!empty($old_data[0][$row['name']]) && is_json($old_data[0][$row['name']])) {
                                            $odata = $old_data[0][$row['name']];

                                            $json_data = json_decode($odata);
                                            if (!empty($postData[$row['name']])) {
                                                $newdata = array();
                                                $newdata[] = array('date' => datetimeformat(), 'content' => strip_slashes($postData[$row['name']]), 'user_id' => $this->session->userdata['LOGGED_IN']['ID']);
                                                $new_json = array_merge($json_data, $newdata);
                                                $data[$row['name']] = json_encode($new_json);
                                            } else {
                                                $data[$row['name']] = $odata;
                                            }
                                        } else {
                                            $jdata[] = array('date' => datetimeformat(), 'content' => strip_slashes($postData[$row['name']]), 'user_id' => $this->session->userdata['LOGGED_IN']['ID']);

                                            $data[$row['name']] = json_encode($jdata);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if (!empty($food_data)) {
            $update_pre_data = array();
            $updated_field = array();
            $n = 0;
            if (!empty($food_form_data)) {
                foreach ($food_form_data as $row) {
                    if (isset($row['name'])) {
                        if ($row['type'] != 'button') {
                            if (!empty($food_data)) {
                                if ($postData[$row['name']] != $food_data[0][$row['name']]) {
                                    $updated_field[] = $row['label'];
                                    $n++;
                                }
                            }
                            $update_pre_data[$row['name']] = strip_slashes($food_data[0][$row['name']]);
                        }
                    }
                }

                $update_pre_data['do_id'] = $postData['do_id'];
                $update_pre_data['yp_id'] = $postData['yp_id'];
                $update_pre_data['created_date'] = $food_data[0]['created_date'];
                $update_pre_data['created_by'] = $food_data[0]['created_by'];
                $update_pre_data['modified_by'] = $food_data[0]['modified_by'];
                $update_pre_data['modified_date'] = $food_data[0]['modified_date'];
                $update_pre_data['is_previous_version'] = 1;
            }
            
            if (!empty($food_prev_data)) {
                $this->common_model->update(DO_FOODCONSUMED, $update_pre_data, array('do_id' => $postData['do_id'], 'yp_id' => $postData['yp_id'], 'is_previous_version' => 1));
            } else {
                /*
                  Ritesh Rana
                  for care home id inserted for archive full functionality
                 */
                $update_pre_data['care_home_id'] = $postData['care_home_id'];
                $this->common_model->insert(DO_FOODCONSUMED, $update_pre_data);
            }
        }

        if (!empty($food_data)) {

            $data['modified_date'] = datetimeformat();
            $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            $this->common_model->update(DO_FOODCONSUMED, $data, array('do_id' => $postData['do_id'], 'is_previous_version' => 0));
            if (!empty($updated_field)) {
                foreach ($updated_field as $fields) {
                    //Insert log activity
                    $activity = array(
                        'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                        'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                        'module_name' => DO_FOOD_MODULE,
                        'module_field_name' => $fields,
                        'type' => 2
                    );
                    log_activity($activity);
                }
            }
        } else {
            $data['do_id'] = $postData['do_id'];
            $data['yp_id'] = $postData['yp_id'];
            $data['created_date'] = datetimeformat();
            $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            /*
              Ritesh Rana
              for care home id inserted for archive full functionality
             */
            $data['care_home_id'] = $postData['care_home_id'];
            $this->common_model->insert(DO_FOODCONSUMED, $data);
           
            //Insert log activity
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                'module_name' => DO_FOOD_MODULE,
                'module_field_name' => '',
                'type' => 1
            );
            log_activity($activity);
        }
        //food consumed end
        //get do previous
        $match = array('yp_id' => $postData['yp_id'], 'do_id' => $postData['do_id']);
        $do_new_data = $this->common_model->get_records(DAILY_OBSERVATIONS, array('*'), '', '', $match);

        $do_yp_data = $do_new_data;
        
        //get do yp data
        $match = array('yp_id' => $postData['yp_id'], 'do_id' => $postData['do_id']);
        $previous_data = $this->common_model->get_records(DO_PREVIOUS_VERSION, array('*'), '', '', $match);
        //get do form
        $match = array('do_form_id' => 1);
        $do_forms = $this->common_model->get_records(DO_FORM, array("form_json_data"), '', '', $match);

        if (!empty($do_forms)) {
            $do_form_data = json_decode($do_forms[0]['form_json_data'], TRUE);

            $match_do_data = array('do_id' => $postData['do_id']);
                                        $old_data = $this->common_model->get_records(DAILY_OBSERVATIONS, array("*"), '', '', $match_do_data);

                                    

            $data = array();
            foreach ($do_form_data as $row) {

                $jdata = array();
                if (isset($row['name'])) {
                    if ($row['name'] != 'yp_comments' && $row['name'] != 'appointments_handover_information' && $row['name'] != 'textarea_1536145487813') {
                        if (empty($do_new_data[0][$row['name']]) && empty($postData[$row['name']])) {
                            $is_filled = 0;
                        }
                    }
                    if ($row['type'] == 'file') {
                        $filename = $row['name'];
                        //get image previous image
                      

                        //delete img
                        if (!empty($postData['hidden_' . $row['name']])) {
                            $delete_img = explode(',', $postData['hidden_' . $row['name']]);
                            $db_images = explode(',', $do_yp_data[0][$filename]);
                            $differentedImage = array_diff($db_images, $delete_img);
                            $do_yp_data[0][$filename] = !empty($differentedImage) ? implode(',', $differentedImage) : '';                            
                        }

                        if (!empty($_FILES[$filename]['name'][0])) {
                            //create dir and give permission
                            /* common function replaced by Dhara Bhalala on 29/09/2018 */
                            createDirectory(array($this->config->item('do_base_url'),$this->config->item('do_base_big_url'),$this->config->item('do_base_big_url') . '/' . $postData['yp_id']));

                            $file_view = $this->config->item('do_img_url') . $postData['yp_id'];
                            //upload big image
                            $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/index/' . $postData['yp_id']);


                            //upload small image
                            $insertImagesData = array();
                            if (!empty($upload_data)) {
                                foreach ($upload_data as $imageFiles) {
                                    /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                    createDirectory(array($this->config->item('do_base_small_url'),$this->config->item('do_base_small_url') . '/' . $postData['yp_id']));
                                    
                                    /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                    if ($imageFiles['is_image'])
                                        $a = do_resize($this->config->item('do_img_url') . $postData['yp_id'], $this->config->item('do_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                    array_push($insertImagesData, $imageFiles['file_name']);
                                    if (!empty($insertImagesData)) {
                                        $images = implode(',', $insertImagesData);
                                    }
                                }
                                if (!empty($do_yp_data[0][$filename])) {
                                    $images .=',' . $do_yp_data[0][$filename];
                                }
                                $data[$row['name']] = !empty($images) ? $images : '';
                            }
                        } else {
                            $data[$row['name']] = !empty($do_yp_data[0][$filename]) ? $do_yp_data[0][$filename] : '';
                        }
                    } else {
                        if ($row['type'] != 'button') {
                            if ($row['type'] == 'checkbox-group') {
                                $data[$row['name']] = !empty($postData[$row['name']]) ? implode(',', $postData[$row['name']]) : '';
                            } elseif ($row['type'] == 'textarea' && $row['subtype'] == 'tinymce') {

                                if (checkPermission('DailyObservation', 'external_edit')) {
                                    if (!empty($postData[$row['name']])) {
                                        $jdata[] = array('date' => datetimeformat(), 'content' => strip_slashes($postData[$row['name']]), 'user_id' => $this->session->userdata['LOGGED_IN']['ID']);
                                    }
                                    $data[$row['name']] = json_encode($jdata);
                                } else {
                                    if (!empty($postData[$row['name']])) {
                                        //get image previous image
                                        

                                        if (!empty($old_data[0][$row['name']])) {
                                            $odata = $old_data[0][$row['name']];

                                            $json_data = json_decode($odata);
                                            if (!empty($postData[$row['name']])) {
                                                $newdata[] = array('date' => datetimeformat(), 'content' => strip_slashes($postData[$row['name']]), 'user_id' => $this->session->userdata['LOGGED_IN']['ID']);
                                                $new_json = array_merge($json_data, $newdata);
                                                $data[$row['name']] = json_encode($new_json);
                                            } else {
                                                $data[$row['name']] = $odata;
                                            }
                                        } else {
                                            $jdata[] = array('date' => datetimeformat(), 'content' => strip_slashes($postData[$row['name']]), 'user_id' => $this->session->userdata['LOGGED_IN']['ID']);

                                            $data[$row['name']] = json_encode($jdata);
                                        }
                                    }
                                }

                            }elseif($row['subtype'] == 'time'){
                                $data[$row['name']] = dbtimeformat($postData[$row['name']]);
                            } elseif ($row['type'] == 'date') {
                                $data[$row['name']] = dateformat($postData[$row['name']]);
                            } elseif ($row['type'] == 'select') {
                                $data[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                            } else {
                                if (checkPermission('DailyObservation', 'external_edit')) {
                                    if (!empty($postData[$row['name']])) {
                                        $jdata[] = array('date' => datetimeformat(), 'content' => strip_slashes($postData[$row['name']]), 'user_id' => $this->session->userdata['LOGGED_IN']['ID']);
                                    }
                                    $data[$row['name']] = json_encode($jdata);

                                } else {
                                    if (!empty($postData[$row['name']])) {
                                        //get image previous image
                                        if (!empty($old_data[0][$row['name']]) && is_json($old_data[0][$row['name']])) {
                                            $odata = $old_data[0][$row['name']];

                                            $json_data = json_decode($odata);
                                            if (!empty($postData[$row['name']])) {
                                                $newdata = array();
                                                $newdata[] = array('date' => datetimeformat(), 'content' => strip_slashes($postData[$row['name']]), 'user_id' => $this->session->userdata['LOGGED_IN']['ID']);
                                                $new_json = array_merge($json_data, $newdata);
                                                $data[$row['name']] = json_encode($new_json);
                                            } else {
                                                $data[$row['name']] = $odata;
                                            }
                                        } else {
                                            $jdata[] = array('date' => datetimeformat(), 'content' => strip_slashes($postData[$row['name']]), 'user_id' => $this->session->userdata['LOGGED_IN']['ID']);

                                            $data[$row['name']] = json_encode($jdata);
                                        }
                                    }
                                }

                            }
                        }
                    }
                }
            }
        }
        $submit_status = $postData['hdn_submit_status'];

        if (!empty($do_new_data)) {
            $update_pre_data = array();
            $updated_field = array();
            $n = 0;
            if (!empty($do_form_data)) {
                foreach ($do_form_data as $row) {

                    if (isset($row['name'])) {
                        if (isset($row['type']) && $row['type'] != 'button') {
                            if (!empty($do_form_data)) {
                                if (!empty($postData[$row['name']])) {
                                     if(is_string($postData[$row['name']])){
                                        $postData[$row['name']]=array($postData[$row['name']]);
                                    }
                                    foreach ($postData[$row['name']] as $key => $value) {
                                        if (empty($value)) {
                                            continue;
                                        } else {
                                            if ($value != $do_new_data[0][$row['name']]) {
                                                $updated_field[] = $row['label'];
                                            }
                                        }

                                        $n++;
                                    }
                                }
                            }
                            $update_pre_data[$row['name']] = $do_new_data[0][$row['name']];
                        }
                    }
                }
                $update_pre_data['do_id'] = $postData['do_id'];
                $update_pre_data['yp_id'] = $postData['yp_id'];
                $update_pre_data['signoff'] = $do_new_data[0]['signoff'];
                $update_pre_data['daily_observation_date'] = $do_new_data[0]['daily_observation_date'];
                $update_pre_data['created_date'] = $do_new_data[0]['created_date'];
                $update_pre_data['created_by'] = $do_new_data[0]['created_by'];
                $update_pre_data['modified_by'] = $do_new_data[0]['modified_by'];
                $update_pre_data['modified_date'] = $do_new_data[0]['modified_date'];
            }
            if (!empty($previous_data)) {
                $this->common_model->update(DO_PREVIOUS_VERSION, $update_pre_data, array('yp_id' => $postData['yp_id'], 'do_id' => $postData['do_id']));
            } else {
                /*
                  Ritesh Rana
                  for care home id inserted for archive full functionality
                 */
                $update_pre_data['care_home_id'] = $postData['care_home_id'];
                $this->common_model->insert(DO_PREVIOUS_VERSION, $update_pre_data);
            }
        }

        if (!empty($postData['do_id'])) {
            $data['do_id'] = $postData['do_id'];
            $data['yp_id'] = $postData['yp_id'];
            $data['is_filled_field'] = $is_filled;

            $data['modified_date'] = datetimeformat();
            if (!empty($postData['signoff'])) {
                $data['signoff'] = $postData['signoff'];
            }

            $data['signoff'] = 0;
            $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            $this->common_model->update(DAILY_OBSERVATIONS, $data, array('do_id' => $postData['do_id']));

            if (!empty($updated_field)) {
                foreach ($updated_field as $fields) {
                    //Insert log activity
                    $activity = array(
                        'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                        'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                        'module_name' => DO_OVERVIEW_MODULE,
                        'module_field_name' => $fields,
                        'type' => 2
                    );
                    log_activity($activity);

                }
            }
        } else {
            $data['yp_id'] = $postData['yp_id'];
            $data['is_filled_field'] = $is_filled;
            $data['created_date'] = datetimeformat();
            /*
              Ritesh Rana
              for care home id inserted for archive full functionality
             */
            $data['care_home_id'] = $postData['care_home_id'];
            $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];

            $this->common_model->insert(DAILY_OBSERVATIONS, $data);
            $do_insert_id = $this->db->insert_id();
            //Insert log activity
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                'module_name' => DO_OVERVIEW_MODULE,
                'module_field_name' => '',
                'type' => 1
            );
            log_activity($activity);
        }
        $this->createArchive($postData['do_id'], $postData['yp_id'], $postData['care_home_id']);
        $msg = 'The Daily Observation Data is successfully updated. Please check your editing.';
        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        redirect('/' . $this->viewname . '/view/' . $postData['do_id'] . '/' . $postData['yp_id'] . '/' . $postData['care_home_id']);
    }

    /*
      @Author : Niral Patel
      @Desc   : Add overview Page
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */

    public function add_overview($id, $ypid) {
        if (is_numeric($id) && is_numeric($ypid)) {
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($ypid,$fields);
            //get daily observation data
            $table = DAILY_OBSERVATIONS . ' as do';
            $where = array("do.do_id" => $id, "do.yp_id" => $ypid);
            $fields = array("do.do_id,do.yp_id,do.awake_time,do.bed_time,do.contact");
            $data['dodata'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            $url_data = base_url('DailyObservation/add_overview/' . $id . '/' . $ypid);
            $match = array('url_data' => $url_data);
            $data['check_edit_permission'] = $this->common_model->get_records(CHECK_EDIT_URL, array("*"), '', '', $match);
            if (count($data['check_edit_permission']) > 0) {
                $in_time = date('Y-m-d h:i:s', strtotime($data['check_edit_permission'][0]['datetime']));
                $currnt_time = date('Y-m-d h:i:s');

                if (strtotime($in_time) > strtotime($currnt_time)) {
                    $now = strtotime($in_time) - strtotime($currnt_time);
                } else {
                    $now = strtotime($currnt_time) - strtotime($in_time);
                }
                $secs = floor($now % 60);

                if ($secs >= 10) {
                    //check data exist or not
                    if (empty($data['dodata'])) {
                        $msg = $this->lang->line('common_no_record_found');
                        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                        redirect('/' . $this->viewname . '/view/' . $id.'/'.$ypid);
                    }
                    $data['care_home_id'] = $data['YP_details'][0]['care_home'];
                    $data['main_content'] = '/add_overview';
                    $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
                    $data['header'] = array('menu_module' => 'YoungPerson');
                    $this->parser->parse('layouts/DefaultTemplate', $data);
                } else {
                    $msg = $this->lang->line('check_ov_user_update_data');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                    redirect('/' . $this->viewname . '/view/' . $id . '/' . $ypid);
                }
            } else {
                //check data exist or not
                if (empty($data['dodata'])) {
                    $msg = $this->lang->line('common_no_record_found');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                    redirect('/' . $this->viewname . '/view/' . $id.'/'.$ypid);
                }
                
                $data['care_home_id'] = $data['YP_details'][0]['care_home'];
                $data['main_content'] = '/add_overview';
                $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
                $data['header'] = array('menu_module' => 'YoungPerson');
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Insert overview
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */

    public function insert_overview() {
        $postData = $this->input->post();
        //update staff
        $this->common_model->update(DO_STAFF_TRANSECTION, array('is_new' => 0), array('do_id' => $postData['do_id']));
        //get do previous version
        $table = DO_PREVIOUS_VERSION . ' as do';
        $where = array("do.do_id" => $postData['do_id'], "do.yp_id" => $postData['yp_id']);
        $fields = array("do.*");
        $do_prev_data = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
        $table = DAILY_OBSERVATIONS . ' as do';
        $where = array("do.do_id" => $postData['do_id'], "do.yp_id" => $postData['yp_id']);
        $fields = array("do.*");
        $do_data = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

        if (!empty($do_data)) {
            $update_data = array();
            foreach ($do_data as $key => $value) {
                $update_data[$key] = $value;
            }
        }
        if (!empty($do_prev_data)) {
            if ($do_prev_data[0]['awake_time'] != $postData['awake_time'] || $do_prev_data[0]['bed_time'] != $postData['bed_time'] || $do_prev_data[0]['contact'] != $postData['contact']) {
                $this->common_model->update(DO_PREVIOUS_VERSION, $update_data[0], array('do_id' => $postData['do_id'], 'yp_id' => $postData['yp_id']));
            }
        } else {
            $do_data = $this->common_model->insert_do_previous_version($postData['do_id'], $postData['yp_id']);
        }
        //get staff details
        $table = DO_STAFF_TRANSECTION . ' as do';
        $where = array("do.do_id" => $postData['do_id']);
        $fields = array("do.do_id");
        $do_staff_data = $this->common_model->get_records($table, $fields,'', '', '', '', '', '', '', '', '', $where);
        $is_filled_field = 0;
        if(!empty($postData['awake_time']) && !empty($postData['bed_time']) && !empty($postData['contact']))
        {
            $is_filled_field = 1;
        }
        //update data of do
        $updatedata = array(
            'awake_time' => !empty($postData['awake_time']) ? date('H:i:s', strtotime($postData['awake_time'])) : '',
            'bed_time' => !empty($postData['bed_time']) ? date('H:i:s', strtotime($postData['bed_time'])) : '',
            'contact' => $postData['contact'],
            'modified_by' => !empty($this->session->userdata('LOGGED_IN')['ID']) ? $this->session->userdata('LOGGED_IN')['ID'] : '',
            'modified_date' => datetimeformat(),
            'is_filled_field_overview'=> $is_filled_field
        );
        $this->common_model->update(DAILY_OBSERVATIONS, $updatedata, array('do_id' => $postData['do_id']));
        //Insert log activity 
        $activity = array(
            'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
            'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
            'module_name' => DO_OVERVIEW_MODULE,
            'module_field_name' => '',
            'type' => 2
        );
        log_activity($activity);

        $this->createArchive($postData['do_id'], $postData['yp_id'],$postData['care_home_id']);
        redirect('/' . $this->viewname . '/save_overview/' . $postData['do_id'] . '/' . $postData['yp_id']);
    }

    /*
      @Author : Niral Patel
      @Desc   : save data overview
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */

    public function save_overview($id, $ypid) {

        if (is_numeric($id) && is_numeric($ypid)) {

            $table = DAILY_OBSERVATIONS . ' as do';
            $where = array("do.do_id" => $id, "do.yp_id" => $ypid);
            $fields = array("do.do_id, yp.yp_fname, yp.yp_lname, yp.date_of_birth");
            $join_tables = array(YP_DETAILS . ' as yp' => 'yp.yp_id= do.yp_id');
            $dodata = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
            //check data exist or not
            if (empty($dodata)) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('/' . $this->viewname . '/index/'.$ypid);
            }
            //get daily observation data
            $data = array(
                'header' => 'Overview Updated',
                'detail' => 'The Overview part of the Daily Observation is now updated. Please check your editing carefully.',
            );
            
            $data['do_id'] = $id;
            $data['ypid'] = $ypid;
            $data['YP_details'] = $dodata;
            $data['main_content'] = '/save_data';
            $data['header'] = array('menu_module' => 'YoungPerson');
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Add staff
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */

    public function add_staff($id, $ypid) {
        if (is_numeric($id) && is_numeric($ypid)) {
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($ypid,$fields);

            //get daily information
            $table = DAILY_OBSERVATIONS . ' as do';
            $where = array("do.do_id" => $id, "do.yp_id" => $ypid);
            $fields = array("do.do_id,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name,group_concat(ds.user_id) as staff");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= do.yp_id', DO_STAFF_TRANSECTION . ' as ds' => 'ds.do_id = do.do_id');

            $data['dodata'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', 'do.do_id', $where);

            //check data exist or not
            if (empty($data['dodata'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('/' . $this->viewname . '/index/'.$ypid);
            }
            //get YP information
            $user_not_in = !empty($data['dodata'][0]['staff']) ? $data['dodata'][0]['staff'] : '';
            if (!empty($user_not_in)) {
                $wherestring = "l.login_id not in ('" . str_replace(",", "','", $user_not_in) . "')";
            }
            $table = LOGIN . ' as l';
            $match = array('role_id !=' => '1','is_delete'=>0);
            $fields = array("l.login_id,concat(l.firstname,' ',l.lastname) as username");
            $data['userdata'] = $this->common_model->get_records($table, $fields, '', '', $match,'','','','','','',$wherestring);

            $data['do_id'] = $id;
            $data['ypid'] = $ypid;
            $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
            $data['main_content'] = '/add_staff';
            $data['header'] = array('menu_module' => 'YoungPerson');
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Insert staff
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */

    public function insert_staff() {
        $postData = $this->input->post();

        $this->common_model->update(DO_STAFF_TRANSECTION, array('is_new' => 0), array('do_id' => $postData['do_id']));

        $table = DAILY_OBSERVATIONS . ' as do';
        $where = array("do.do_id" => $postData['do_id']);
        $fields = array("do.*");
        $dodata = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
        $is_filled_field = 0;
        if(!empty($dodata[0]['awake_time']) && !empty($dodata[0]['bed_time']) && !empty($dodata[0]['contact']) && !empty($postData['staff']))
        {
            $is_filled_field = 1;
        }
        if(!empty($postData['staff']))
        {
            foreach ($postData['staff'] as $value) {
                //update data of do
                $updatedata = array(
                    'do_id' => $postData['do_id'],
                    'user_id' => $value,
                    'created_date' => datetimeformat(),
                    'is_new' => 1
                );
                $this->common_model->insert(DO_STAFF_TRANSECTION, $updatedata);
            }
        }
       
        //get do previous version
        $table = DO_PREVIOUS_VERSION . ' as do';
        $where = array("do.do_id" => $postData['do_id'], "do.yp_id" => $postData['yp_id']);
        $fields = array("do.*");
        $do_prev_data = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
        if (!empty($do_prev_data)) {
            $table = DAILY_OBSERVATIONS . ' as do';
            $where = array("do.do_id" => $postData['do_id'], "do.yp_id" => $postData['yp_id']);
            $fields = array("do.*");
            $do_data = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            

            if (!empty($do_data)) {
                $update_data = array();
                foreach ($do_data as $key => $value) {
                    $update_data[$key] = $value;
                }
            }
           $this->common_model->update(DO_PREVIOUS_VERSION, $update_data[0], array('do_id' => $postData['do_id'], 'yp_id' => $postData['yp_id']));
        }

        $this->createArchive($postData['do_id'], $postData['yp_id']);
        $data['header'] = array('menu_module' => 'YoungPerson');
        redirect('/' . $this->viewname . '/save_staff/' . $postData['do_id'] . '/' . $postData['yp_id']);
    }
    /*
      @Author : Niral Patel
      @Desc   : Delete staff
      @Input    :
      @Output   :
      @Date   : 08/05/2018
     */
       public function delete_staff($do_staff_id,$do_id,$yp_id) {
            $postData = $this->input->post();
            $this->common_model->update(DO_STAFF_TRANSECTION, array('is_new' => 0), array('do_id' => $do_id));
            $this->common_model->delete(DO_STAFF_TRANSECTION,array('do_staff_id' => $do_staff_id));
            $msg = 'Staff deleted successfully.';
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect('/' . $this->viewname . '/view/' . $do_id . '/' . $yp_id);
       }
    /*
      @Author : Niral Patel
      @Desc   : save data staff
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */

    public function save_staff($id, $ypid) {

        if (is_numeric($id) && is_numeric($ypid)) {

            //get daily information
            $table = DAILY_OBSERVATIONS . ' as do';
            $where = array("do.do_id" => $id, "do.yp_id" => $ypid);
            $fields = array("do.do_id,do.daily_observation_date");
            $data['dodata'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            //check data exist or not
            if (empty($data['dodata'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('/' . $this->viewname . '/view/' . $id.'/'.$ypid);
            }
            //get daily observation data
            $data = array(
                'header_msg' => 'Daily Obs Updated',
                'detail' => 'The staff member has been added to the Daily Observation..',
            );
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($ypid,$fields);
            $data['care_home_id'] = $data['YP_details'][0]['care_home'];    
            $data['do_id'] = $id;
            $data['ypid'] = $ypid;
            $data['main_content'] = '/save_data';
            $data['header'] = array('menu_module' => 'YoungPerson');
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }

/*
      @Author : Ritesh Rana
      @Desc   : DO Print functionality
      @Input    :
      @Output   :
      @Date   : 17/07/2017
     */
    public function DownloadPrint($do_id, $yp_id, $section = NULL) {
        $data = [];
        $match = array('do_form_id' => 1);
        $do_forms = $this->common_model->get_records(DO_FORM, array("form_json_data"), '', '', $match);
        if (!empty($do_forms)) {
            $data['do_form_data'] = json_decode($do_forms[0]['form_json_data'], TRUE);
        }

        $match = array('food_form_id' => 1);
        $food_forms = $this->common_model->get_records(FOOD_FORM, array("form_json_data"), '', '', $match);
        if (!empty($food_forms)) {
            $data['food_form_data'] = json_decode($food_forms[0]['form_json_data'], TRUE);
        }
        //get YP information
        $table = YP_DETAILS . ' as yp';
        $match = array("yp.yp_id" => $yp_id);
        $fields = array("yp.yp_id,yp.care_home,yp.yp_fname,yp.yp_lname,yp.date_of_birth,pa.placing_authority_id,pa.authority,pa.address_1,pa.town,pa.county,pa.postcode,sd.mobile,sd.email,ch.care_home_name");
        $join_tables = array(PLACING_AUTHORITY . ' as pa' => 'pa.yp_id=yp.yp_id', SOCIAL_WORKER_DETAILS . ' as sd' => 'sd.yp_id=yp.yp_id',CARE_HOME . ' as ch' => 'ch.care_home_id = yp.care_home');
        $data['YP_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', '', '', '', '');

        //get daily observation data
        $data['dodata'] = $this->DailyObservations($yp_id,$do_id);
        //get food data
        $match = array('do_id' => $do_id, 'is_previous_version' => 0);
        $data['food_data'] = $this->common_model->get_records(DO_FOODCONSUMED, '', '', '', $match);
        $table = DO_STAFF_TRANSECTION . ' as do';
        $where = array("do.do_id" => $do_id);
        $fields = array("do.*,CONCAT(l.firstname,' ', l.lastname) as staff_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.user_id');

        $data['do_staff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
        //get last day do
            $table = DAILY_OBSERVATIONS . ' as do';
            $where = array("do.daily_observation_date" => "'".date('Y-m-d',(strtotime ( '-1 day' , strtotime ($data['dodata'][0]['daily_observation_date']) )))."'", "do.yp_id" => $yp_id);
            $fields = array("do.do_id,do.handover_next_day");
            $data['lastDayData'] = $this->common_model->get_records($table, $fields,'', '', '', '', '', '', '', '', '', $where);
            
            $data['do_professionals_data'] = $this->MedicalProfessionalsAppointment($yp_id,$data['dodata'][0]['daily_observation_date']);
           
            $data['appointment_view_comments'] = $this->AppointmentViewComments($yp_id);
            //get planner appointment
            $table = PLANNER . ' as dpa';
            $where = array( "dpa.yp_id" => $yp_id, 
                            'appointment_date' => "'".$data['dodata'][0]['daily_observation_date']."'",
                            'is_delete' => 0
                            );
            $fields = array("dpa.appointment_date,dpa.appointment_time,dpa.appointment_type,dpa.comments");
            $data['do_planner_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

            $daily_observation_date = $data['dodata'][0]['daily_observation_date'];
            //get adminstry medication
            $table = ADMINISTER_MEDICATION . ' as mc';
            $fields = array("mc.*,md.stock");
            $where = array("mc.yp_id"=>$yp_id ,'mc.date_given' => "'".$daily_observation_date."'");
            $join_tables = array(MEDICATION . ' as md' => 'md.medication_id=mc.select_medication');
            $data['administer_medication'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '','', 'administer_medication_id','desc', '', $where);
            //get am form
            $match = array('am_form_id'=> 1);
            $formsdata = $this->common_model->get_records(AM_FORM,'', '', '', $match);
            if(!empty($formsdata))
            {
                $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }

        $data['crnt_view'] = $this->viewname;
        $data['ypid'] = $yp_id;

        $data['main_content'] = '/dopdf';
        $data['section'] = $section;
        $html = $this->parser->parse('layouts/PDFTemplate', $data);
        $pdfFileName = "docs" . $do_id . ".pdf";
        $pdfFilePath = FCPATH . 'uploads/ra/';
        if (!is_dir(FCPATH . 'uploads/ra/')) {
            @mkdir(FCPATH . 'uploads/ra/', 0777, TRUE);
        }
        if (file_exists($pdfFilePath . $pdfFileName)) {
            unlink($pdfFilePath . $pdfFileName);
        }
        
        $this->load->library('m_pdf');

        if ($section == 'StorePDF') {
            ob_clean();
            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($pdfFilePath . $pdfFileName, 'F');
            return 1;
            die;
        } elseif ($section == 'print') {
            echo $html;
            exit;
        } else {
            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($pdfFileName, "D");
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : check duplicate DO
      @Input    :
      @Output   :
      @Date   : 17/07/2017
     */
    public function isDuplicateDo() {
        $ypId = $this->input->post('ypId');
        $do_date = dateformat($this->input->post('do_date'));
        $table = DAILY_OBSERVATIONS . ' as do';
        $where = array("do.daily_observation_date" => "'$do_date'", "do.yp_id" => "'$ypId'");
        $fields = array("do.do_id,do.yp_id,do.daily_observation_date");
        $duplicatedo = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
        $count = count($duplicatedo);
        if (isset($duplicatedo) && empty($duplicatedo) && $count == 0) {
            echo "1";
            die;
        } else {
            echo "0";
            die;
        }

    }

/*
      @Author : Ritesh Rana
      @Desc   : add commnts functionality
      @Input    :
      @Output   :
      @Date   : 17/07/2017
     */
    public function add_commnts() {
        $main_user_data = $this->session->userdata('LOGGED_IN');
        $do_id = $this->input->post('doid');
        $yp_id = $this->input->post('yp_id');
        $care_home_id = $this->input->post('care_home_id');
        $data = array(
            'overview_comments' => $this->input->post('overview_comments'),
            'yp_id' => $this->input->post('yp_id'),
            'doid' => $this->input->post('doid'),
            'created_date' => datetimeformat(),
            'created_by' => $main_user_data['ID'],
            /*
                Ritesh Rana
                for care home id inserted for archive full functionality
            */
            'care_home_id' => $care_home_id,
        );
        //Insert Record in Database
        if ($this->common_model->insert(OB_COMMENTS, $data)) {
            $msg = $this->lang->line('comments_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }
        redirect('/' . $this->viewname . '/view/' . $do_id . '/' . $yp_id);
    }

    /*
      @Author : Ritesh Rana
      @Desc   : check user edit functionality
      @Input    :
      @Output   :
      @Date   : 17/07/2017
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
      @Desc   : user signoff functionality
      @Input    :
      @Output   :
      @Date   : 17/07/2017
     */
    public function manager_review($yp_id, $do_id) {
        if (!empty($yp_id) && !empty($do_id)) {
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $match = array('yp_id' => $yp_id, 'do_id' => $do_id, 'created_by' => $login_user_id,'is_delete' => '0');
            $check_signoff_data = $this->common_model->get_records(DO_SIGNOFF, array("*"), '', '', $match);
            if (empty($check_signoff_data) > 0) {
                $update_pre_data['do_id'] = $do_id;
                $update_pre_data['yp_id'] = $yp_id;
                $update_pre_data['created_date'] = datetimeformat();
                $update_pre_data['created_by'] = $login_user_id;
                /*
                Ritesh Rana
                for care home id inserted for archive full functionality
                */
                $update_pre_data['care_home_id'] = $care_home_id;
                if ($this->common_model->insert(DO_SIGNOFF, $update_pre_data)) {
                    $u_data['signoff'] = '1';
                    $this->common_model->update(DAILY_OBSERVATIONS, $u_data, array('do_id' => $do_id, 'yp_id' => $yp_id));

                    $msg = $this->lang->line('successfully_do_review');
                    $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                } else {
                    // error
                    $msg = $this->lang->line('error_msg');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                }
            } else {
                $msg = $this->lang->line('already_do_review');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
        } else {
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }

        redirect('/' . $this->viewname . '/view/' . $do_id . '/' . $yp_id);
    }

    /*
      @Author : Niral Patel
      @Desc   : Create archive
      @Input    :
      @Output   :
      @Date   : 18/08/2017
     */

    public function createArchive($id, $ypid) {
        if (is_numeric($id) && is_numeric($ypid)) {

            //get do form
            $match = array('do_form_id' => 1);
            $formsdata = $this->common_model->get_records(DO_FORM, array("*"), '', '', $match);

            //get do yp data
            $match = array('do_id' => $id);
            $pp_yp_data = $this->common_model->get_records(DAILY_OBSERVATIONS, array("*"), '', '', $match);
            //get do yp data
            //Summary add 
            if (!empty($formsdata) && !empty($pp_yp_data)) {
                $form_data = json_decode($formsdata[0]['form_json_data'], TRUE);
                $data = array();
                $i = 0;
                foreach ($form_data as $row) {
                    if (isset($row['name'])) {
                        if ($row['type'] != 'button') {
                            if ($row['type'] == 'checkbox-group') {
                                $form_data[$i]['value'] = implode(',', $pp_yp_data[0][$row['name']]);
                            } else {
                                
                                $form_data[$i]['value'] = strip_slashes($pp_yp_data[0][$row['name']]);
                                //$form_data[$i]['value'] = str_replace("'", '"', $pp_yp_data[0][$row['name']]);
                                
                            }
                        }
                    }
                    $i++;
                }
            }
            //Food add
            //get food form
            $match = array('food_form_id' => 1);
            $food_forms = $this->common_model->get_records(FOOD_FORM, array("*"), '', '', $match);

            //get food data
            $match = array('do_id' => $id);
            $food_data = $this->common_model->get_records(DO_FOODCONSUMED, array("*"), '', '', $match);

            if (!empty($food_forms) && !empty($pp_yp_data)) {
                $form_data_food = json_decode($food_forms[0]['form_json_data'], TRUE);
                $data = array();
                $i = 0;
                foreach ($form_data_food as $row) {
                    if (isset($row['name'])) {
                        if ($row['type'] != 'button') {
                            if ($row['type'] == 'checkbox-group') {
                                $form_data_food[$i]['value'] = implode(',', $food_data[0][$row['name']]);
                            } else {
                                $form_data_food[$i]['value'] = strip_slashes($food_data[0][$row['name']]);
                                //$form_data_food[$i]['value'] = str_replace("'", '"', $food_data[0][$row['name']]);
                            }
                        }
                    }
                    $i++;
                }
            }
            
            //Staff
            //get food data
            $match = array('do_id' => $id);
            $staff_data = $this->common_model->get_records(DO_STAFF_TRANSECTION, array("*"), '', '', $match);
            if (!empty($staff_data)) {
                foreach ($staff_data as $row) {
                    $sdata[] = $row['user_id'];
                }
            }

            //get YP information
             $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
             $data['YP_details'] = YpDetails($ypid,$fields);

            $archive = array(
                'yp_id' => $ypid,
                'do_id' => $id,
                'daily_observation_date' => !empty($pp_yp_data[0]['daily_observation_date']) ? $pp_yp_data[0]['daily_observation_date'] : '',
                'awake_time' => !empty($pp_yp_data[0]['awake_time']) ? $pp_yp_data[0]['awake_time'] : '',
                'bed_time' => !empty($pp_yp_data[0]['bed_time']) ? $pp_yp_data[0]['bed_time'] : '',
                'contact' => !empty($pp_yp_data[0]['contact']) ? $pp_yp_data[0]['contact'] : '',
                'staff_data' => !empty($sdata) ? implode(',', $sdata) : '',
                'food_json_data' => !empty($form_data_food) ? json_encode($form_data_food, TRUE) : '',
                'summary_json_data' => !empty($form_data) ? json_encode($form_data, TRUE) : '',
                'created_by' => $this->session->userdata('LOGGED_IN')['ID'],
                'care_home_id' => $data['YP_details'][0]['care_home'],
                'created_date' => datetimeformat(),
                'signoff' => !empty($pp_yp_data[0]['signoff']) ? $pp_yp_data[0]['signoff'] : '0',
                'status' => 0,
            );
                //get do yp data
                $match = array('do_id' => $id);
                $archive_data = $this->common_model->get_records(DO_ARCHIVE, array("*"), '', '', $match);
               
                    //update status to archive
                     $update_archive = array(
                    'created_date'=>datetimeformat(),
                    'status'=>1
                );
                    $where = array('status' => 0, 'do_id' => $id);
                    $this->common_model->update(DO_ARCHIVE, $update_archive, $where);
                
                //insert archive data for next time
                $this->common_model->insert(DO_ARCHIVE, $archive);
    
                $archive_do_id = $this->db->insert_id();
                  if($archive_do_id > 1){
                    $archive_do_id = $archive_do_id - 1 ;
                  }
                 
               //  echo $archive_plan_id;exit;

              $table = DO_SIGNOFF.' as do';
              $where = array("do.yp_id" => $ypid,"do.do_id" => $id,"do.is_delete"=> "0");
              $fields = array("do.created_by,do.yp_id,do.do_id,do.created_date");
              $group_by = array('created_by');
              $signoff_data = $this->common_model->get_records($table,$fields,'','','','','','','','',$group_by,$where);

          if(!empty($signoff_data)){
              foreach ($signoff_data as $archive_value) {
                  $update_arc_data['archive_do_id'] = $archive_do_id;
                  $update_arc_data['yp_id'] = $archive_value['yp_id'];
                  $update_arc_data['created_date'] = $archive_value['created_date'];
                  $update_arc_data['created_by'] = $archive_value['created_by'];
                  $this->common_model->insert(NFC_ARCHIVE_DO_SIGNOFF,$update_arc_data);      
              }
                $archive = array('is_delete'=>1);
                $where = array('yp_id'=>$ypid,"do_id" => $id);
                $this->common_model->update(DO_SIGNOFF, $archive,$where);
          }

           $table = OB_COMMENTS.' as do';
              $where = array("do.yp_id" => $ypid,"do.doid" => $id);
              $fields = array("do.overview_comments,do.yp_id,do.created_date,do.created_by,do.ob_comments_id");
              $comment_data = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);
          if(!empty($comment_data)){
              foreach ($comment_data as $archive_value) {
                  $update_arc_data['archive_do_id'] = $archive_do_id;
                  $update_arc_data['overview_comments'] = $archive_value['overview_comments'];
                  $update_arc_data['yp_id'] = $archive_value['yp_id'];
                  $update_arc_data['created_date'] = $archive_value['created_date'];
                  $update_arc_data['created_by'] = $archive_value['created_by'];
                  $this->common_model->insert(NFC_ARCHIVE_OB_COMMENTS,$update_arc_data);      
              }
          }

                return true;
            
        } else {
            show_404();
        }
    }

/*
      @Author : Ritesh Rana
      @Desc   : open popup for Signoff / Approval
      @Input    :
      @Output   :
      @Date   : 17/07/2017
     */
public function signoff($yp_id='',$do_id='') {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {

            $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
            $data['crnt_view'] = $this->viewname;
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($yp_id,$fields);

            $data['care_home_id'] = $data['YP_details'][0]['care_home'];    
            $data['ypid']= $yp_id;
            $data['do_id']= $do_id;
             //get social info
            $data['social_worker_data'] = SocialWorkerData($yp_id);
            //get parent info
            $data['parent_data'] = ParentData($yp_id);

            $data['form_action_path'] = $this->viewname . '/signoff';
            $data['header'] = array('menu_module' => 'YoungPerson');
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
      @Desc   : insert Signoff / Approval
      @Input    :
      @Output   :
      @Date   : 17/07/2017
     */
    public function insertdata() {
        
        $postdata = $this->input->post();
        
        $ypid = $postdata['ypid'];
        $do_id = $postdata['do_id'];
        
        $user_type = $postdata['user_type'];
        if($user_type == 'parent')
        {
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
          /*
                Ritesh Rana
                for care home id inserted for archive full functionality
         */
          $parent_data['care_home_id'] = $postdata['care_home_id'];
          
          $success = $this->common_model->insert(PARENT_CARER_INFORMATION, $parent_data);
          //Insert log activity
          $activity = array(
              'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
              'yp_id' => !empty($postdata['ypid']) ? $postdata['ypid'] : '',
              'module_name' => DO_PARENT_CARER_DETAILS_YP,
              'module_field_name' => '',
              'type' => 1
          );
          log_activity($activity);
        }
            //Current Login detail
        $main_user_data = $this->session->userdata('LOGGED_IN');
        
            if (!validateFormSecret()) {
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
                redirect('YoungPerson/view/'.$ypid);
            }

            //start get do data
            $match = array('do_form_id' => 1);
            $formsdata = $this->common_model->get_records(DO_FORM, array("form_json_data"), '', '', $match);

            //get do yp data
            $match = array('do_id' => $do_id);
            $pp_yp_data = $this->common_model->get_records(DAILY_OBSERVATIONS, array("*"), '', '', $match);

            //get do yp data

            //Summary add 
            if (!empty($formsdata) && !empty($pp_yp_data)) {
                $form_data = json_decode($formsdata[0]['form_json_data'], TRUE);
                $data = array();
                $i = 0;
                foreach ($form_data as $row) {
                    if (isset($row['name'])) {
                        if ($row['type'] != 'button') {
                            if ($row['type'] == 'checkbox-group') {
                                $form_data[$i]['value'] = implode(',', $pp_yp_data[0][$row['name']]);
                            } else {

                                $form_data[$i]['value'] = strip_slashes($pp_yp_data[0][$row['name']]);
                                //$form_data[$i]['value'] = str_replace("'", '"', $pp_yp_data[0][$row['name']]);

                            }
                        }
                    }
                    $i++;
                }
            }
            //Food add
            //get food form
            $match = array('food_form_id' => 1);
            $food_forms = $this->common_model->get_records(FOOD_FORM, array("form_json_data"), '', '', $match);

            //get food data
            $match = array('do_id' => $do_id);
            $food_data = $this->common_model->get_records(DO_FOODCONSUMED, array("*"), '', '', $match);

            if (!empty($food_forms) && !empty($pp_yp_data)) {
                $form_data_food = json_decode($food_forms[0]['form_json_data'], TRUE);
                $data = array();
                $i = 0;
                foreach ($form_data_food as $row) {
                    if (isset($row['name'])) {
                        if ($row['type'] != 'button') {
                            if ($row['type'] == 'checkbox-group') {
                                $form_data_food[$i]['value'] = implode(',', $food_data[0][$row['name']]);
                            } else {
                                $form_data_food[$i]['value'] = strip_slashes($food_data[0][$row['name']]);
                                //$form_data_food[$i]['value'] = str_replace("'", '"', $food_data[0][$row['name']]);
                                 
                            }
                        }
                    }
                    $i++;
                }
            }
            //Staff
            //get food data
            $match = array('do_id' => $do_id);
            $staff_data = $this->common_model->get_records(DO_STAFF_TRANSECTION, array("*"), '', '', $match);
            if (!empty($staff_data)) {
                foreach ($staff_data as $row) {
                    $sdata[] = $row['user_id'];
                }
            }

            $data['crnt_view'] = $this->viewname;
            $data = array(
                'user_type' => ucfirst($postdata['user_type']),
                'yp_id' => ucfirst($postdata['ypid']),
                'do_id' => ucfirst($postdata['do_id']),
                 'daily_observation_date' => !empty($pp_yp_data[0]['daily_observation_date']) ? $pp_yp_data[0]['daily_observation_date'] : '',
                'awake_time' => !empty($pp_yp_data[0]['awake_time']) ? $pp_yp_data[0]['awake_time'] : '',
                'bed_time' => !empty($pp_yp_data[0]['bed_time']) ? $pp_yp_data[0]['bed_time'] : '',
                'contact' => !empty($pp_yp_data[0]['contact']) ? $pp_yp_data[0]['contact'] : '',
                'staff_data' => !empty($sdata) ? implode(',', $sdata) : '',
                'food_json_data' => !empty($form_data_food) ? json_encode($form_data_food, TRUE) : '',
                'summary_json_data' => !empty($form_data) ? json_encode($form_data, TRUE) : '',
                'fname' => ucfirst($postdata['fname']),
                'lname' => ucfirst($postdata['lname']),
                'email' => $postdata['email'],
                'key_data' => md5($postdata['email']),
                'created_date' => datetimeformat(),
                'created_by' => $main_user_data['ID'],
                'updated_by' => $main_user_data['ID'],
                'modified_date' => '',
                /*
                Ritesh Rana
                for care home id inserted for archive full functionality
                */
                'care_home_id' => $postdata['care_home_id'],
            );
            //Insert Record in Database
            if ($this->common_model->insert(NFC_DO_SIGNOFF_DETAILS, $data)) {

                $signoff_id = $this->db->insert_id();

              $table = DO_SIGNOFF.' as do';
              $where = array("do.yp_id" => $ypid,"do.do_id" => $do_id);
              $fields = array("do.created_by,do.yp_id,do.do_id,do.created_date,do.care_home_id");
              $group_by = array('created_by');
              $signoff_data = $this->common_model->get_records($table,$fields,'','','','','','','','',$group_by,$where);
          if(!empty($signoff_data)){
              foreach ($signoff_data as $archive_value) {
                  $update_arc_data['approval_do_id'] = $signoff_id;
                  $update_arc_data['yp_id'] = $archive_value['yp_id'];
                  $update_arc_data['created_date'] = $archive_value['created_date'];
                  $update_arc_data['created_by'] = $archive_value['created_by'];
                  /*
                    Ritesh Rana
                    for care home id inserted for archive full functionality
                */
                  $update_arc_data['care_home_id'] = $archive_value['care_home_id'];
                  $this->common_model->insert(NFC_APPROVAL_DO_SIGNOFF,$update_arc_data);      
              }
          }

           $table = OB_COMMENTS.' as do';
              $where = array("do.yp_id" => $ypid,"do.doid" => $do_id);
              $fields = array("do.ob_comments_id,do.overview_comments,do.yp_id,do.created_date,do.created_by,do.care_home_id");
              $comment_data = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);
          if(!empty($comment_data)){
              foreach ($comment_data as $archive_value) {
                  $update_arc_data['approval_do_id'] = $signoff_id;
                  $update_arc_data['overview_comments'] = $archive_value['overview_comments'];
                  $update_arc_data['yp_id'] = $archive_value['yp_id'];
                  $update_arc_data['created_date'] = $archive_value['created_date'];
                  $update_arc_data['created_by'] = $archive_value['created_by'];
                  /*
                Ritesh Rana
                for care home id inserted for archive full functionality
                */
                  $update_arc_data['care_home_id'] = $archive_value['care_home_id'];
                  $this->common_model->insert(NFC_APPROVAL_OB_COMMENTS,$update_arc_data);      
              }
          }


                $this->sendMailToRelation($data,$signoff_id); // send mail
                //Insert log activity
                $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($ypid) ? $ypid : '',
                    'module_name' => NFC_DO_SIGNOFF_LOG,
                    'module_field_name' => '',
                    'type' => 1
                );
                log_activity($activity);
                $msg = $this->lang->line('successfully_sign_off');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            } else {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                
            }
        
        redirect('DailyObservation/view/'.$do_id.'/'.$ypid);
    }


/*
      @Author : Ritesh Rana
      @Desc   : send email for Signoff / Approval functionality
      @Input    :
      @Output   :
      @Date   : 17/07/2017
     */
    private function sendMailToRelation($data = array(),$signoff_id) {

        if (!empty($data)) {
            if(!empty($data['yp_id']))
            {
                $fields = array("concat(yp_fname,' ',yp_lname) as yp_name");
                $YP_details = YpDetails($data['yp_id'],$fields);
            }
            $yp_name = !empty($YP_details[0]['yp_name'])?$YP_details[0]['yp_name']:"";
            /* Send Created Customer password with Link */
            $toEmailId = $data['email'];
            $customerName = $data['fname'] . ' ' . $data['lname'];

            $email = md5($toEmailId);
            $loginLink = base_url('DailyObservation/signoffData/' . $data['yp_id'] . '/' . $signoff_id . '/' . $email);

            $find = array('{NAME}','{LINK}');

            $replace = array(
                'NAME' => $customerName,
                'LINK' => $loginLink,
            );
            
            $emailSubject = 'Welcome to NFCTracker';
                    $emailBody = '<div>'
                    . '<p>Hello {NAME} ,</p> '
                    . '<p>Please find Daily Observation for '.$yp_name.' for your approval.</p> '
                    . "<p>For security purposes, Please do not forward this email on to any other person. It is for the recipient only and if this is sent in error please advise itsupport@newforestcare.co.uk and delete this email. This link is only valid for ".REPORT_EXPAIRED_HOUR.", should this not be signed off within ".REPORT_EXPAIRED_HOUR." of recieving then please request again</p>"
                    . '<p> <a href="{LINK}">click here</a> </p> '
                    . '<div>';        

            $finalEmailBody = str_replace($find,$replace,$emailBody);

            return $this->common_model->sendEmail($toEmailId, $emailSubject, $finalEmailBody, FROM_EMAIL_ID);
        }
        return true;
    }

    /*
      @Author : Ritesh Rana
      @Desc   : sign off formValidation
      @Input  :
      @Output :
      @Date   : 28/03/2018
     */
     public function formValidation() {
        $this->form_validation->set_rules('fname', 'Firstname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('lname', 'Lastname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
    }

/*
      @Author : Ritesh Rana
      @Desc   : View Signoff / Approval functionality
      @Input    :
      @Output   :
      @Date   : 17/07/2017
     */
    public function signoffData($ypid,$signoff_id,$email) {

    if(is_numeric($ypid) && is_numeric($signoff_id) && !empty($email))
       {

          $match = array('yp_id'=> $ypid,'do_signoff_details_id'=>$signoff_id,'key_data'=> $email,'status'=>'inactive');
          $check_signoff_data = $this->common_model->get_records(NFC_DO_SIGNOFF_DETAILS,array("*"), '', '', $match);
          
        if(!empty($check_signoff_data)){
                $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'].REPORT_EXPAIRED_DAYS));
                if(strtotime(datetimeformat()) <= strtotime($expairedDate))
                {
                //get YP information
                $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
                $data['YP_details'] = YpDetails($ypid,$fields);

                //get comments deta
                $table = NFC_APPROVAL_OB_COMMENTS . ' as com';
                $where = array("com.approval_do_id" => $signoff_id, "com.yp_id" => $ypid);
                $fields = array("com.overview_comments,com.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");

                $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
                $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
                
                //get comments deta
                $table = NFC_DO_SIGNOFF_DETAILS . ' as do';
                $where = array("do.do_signoff_details_id"=>$signoff_id,"do.yp_id"=>$ypid);
                $fields = array("do.*,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
                $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by',YP_DETAILS . ' as yp' => 'yp.yp_id= do.yp_id');

                $data['dodata'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '','','', '', $where);
            //get last day do
            $table = DAILY_OBSERVATIONS . ' as do';
            $where = array("do.daily_observation_date" => "'".date('Y-m-d',(strtotime ( '-1 day' , strtotime ($data['dodata'][0]['daily_observation_date']) )))."'", "do.yp_id" => $ypid);
            $fields = array("do.do_id,do.handover_next_day");
            $data['lastDayData'] = $this->common_model->get_records($table, $fields,'', '', '', '', '', '', '', '', '', $where);

            $data['do_professionals_data'] = $this->MedicalProfessionalsAppointment($ypid,$data['dodata'][0]['daily_observation_date']);

            $data['appointment_view_comments'] = $this->AppointmentViewComments($ypid);
            //get planner appointment
            $table = PLANNER . ' as dpa';
            $where = array( "dpa.yp_id" => $ypid, 
                            'appointment_date' => "'".$data['dodata'][0]['daily_observation_date']."'",
                            'is_delete' => 0
                            );
            $fields = array("dpa.appointment_date,dpa.appointment_time,dpa.appointment_type,dpa.comments");
            $data['do_planner_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
                //get archive food data
                if(!empty($data['dodata']))
                {
                  $data['food_form_data'] = json_decode($data['dodata'][0]['food_json_data'], TRUE);
                }
                
                //get archive food data
                if(!empty($data['dodata']))
                {
                  $data['summary_form_data'] = json_decode($data['dodata'][0]['summary_json_data'], TRUE);
                }


                if(!empty($data['dodata'][0]['staff_data']))
                {
                $wherein = "login_id in (".$data['dodata'][0]['staff_data'].")";
                $fields = array("concat(firstname,' ', lastname) as staff_name");
                $data['do_staff_data'] = $this->common_model->get_records(LOGIN, $fields, '', '', '','','','','','','',$wherein,'','');
                }

                $table = NFC_APPROVAL_DO_SIGNOFF.' as do';
                $where = array("l.is_delete"=> "0","do.yp_id" => $ypid,"do.approval_do_id"=>$signoff_id);
                $fields = array("do.created_by,do.created_date,do.yp_id,do.approval_do_id, CONCAT(`firstname`,' ', `lastname`) as name");
                $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by');
                $group_by = array('created_by');
                $data['do_signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);

                $data['signoff_id'] = $signoff_id;
                $data['key_data']= $email;
                $data['ypid'] = !empty($data['dodata'][0]['yp_id']) ? $data['dodata'][0]['yp_id'] : '';

                $data['main_content'] = '/signoff_view';
                $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
                $data['header'] = array('menu_module' => 'YoungPerson');
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
            else
            {
                $msg = lang('link_expired');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>")
                ;
                $this->load->view('successfully_message');
            }
        }else{
            
            $msg = $this->lang->line('already_do_review');
      $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
 $this->load->view('successfully_message');
        }

        } else {
            show_404();
        }
    }

/*
      @Author : Ritesh Rana
      @Desc   : Review for Signoff / Approval functionality
      @Input    :
      @Output   :
      @Date   : 17/07/2017
     */
public function signoff_review_data($yp_id,$signoff_id,$email) {
    if (!empty($yp_id) && !empty($signoff_id) && !empty($email)) {
          $match = array('yp_id'=> $yp_id,'do_signoff_details_id'=>$signoff_id,'key_data'=> $email,'status'=>'inactive');
          $check_signoff_data = $this->common_model->get_records(NFC_DO_SIGNOFF_DETAILS,array("created_date"), '', '', $match);
        if(!empty($check_signoff_data)){
            $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'].REPORT_EXPAIRED_DAYS));
            if(strtotime(datetimeformat()) <= strtotime($expairedDate))
            {
              $u_data['status'] = 'active';
              $u_data['modified_date'] = datetimeformat();
                $success =$this->common_model->update(NFC_DO_SIGNOFF_DETAILS,$u_data,array('do_signoff_details_id'=> $signoff_id,'yp_id'=> $yp_id,'key_data'=> $email));
            if ($success) {
                $msg = $this->lang->line('successfully_do_review');
                $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-success text-center'>$msg</div>");
                } else {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>");
                }
            }
            else
            {
              $msg = lang('link_expired');
        $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
      }else{
        $msg = $this->lang->line('already_do_review');
      $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>");

      }
    }else{      
      $msg = $this->lang->line('error_msg');
      $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>");
    }
     $this->load->view('successfully_message');
  }
 
    /*
      @Author : Ritesh Rana
      @Desc   : get User Type Detail
      @Input    :
      @Output   :
      @Date   : 17/07/2017
    */
    public function getUserTypeDetail() {
      $postdata  = $this->input->post();
      $user_type = !empty($postdata['user_type'])?$postdata['user_type']:''; 
      $id = $postdata['id']; 
      $ypid      = $postdata['ypid']; 
      $table = YP_DETAILS . ' as yp';
      $match = "yp.yp_id = " . $ypid;
      $fields = array("yp.*,swd.social_worker_firstname,swd.social_worker_surname,swd.landline,swd.mobile,swd.email,swd.senior_social_worker_firstname,swd.senior_social_worker_surname,oyp.pen_portrait,swd.other,pc.firstname,pc.surname,pc.relationship,pc.address,pc.contact_number,pc.email_address,pc.yp_authorised_communication,pc.carer_authorised_communication,pc.comments");
      $join_tables = array(SOCIAL_WORKER_DETAILS . ' as swd' => 'swd.yp_id= yp.yp_id', PARENT_CARER_INFORMATION . ' as pc' => 'pc.yp_id= yp.yp_id', OVERVIEW_OF_YOUNG_PERSON . ' as oyp' => 'oyp.yp_id= yp.yp_id');

      $data['editRecord'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
      if($user_type == 'parent_data')
      {
          //get parent info
          $table = PARENT_CARER_INFORMATION . ' as pc';
          $match = array("pc.parent_carer_id" => $id,'pc.is_deleted' => 0);
          $fields = array("pc.*");
          $data['parent_data'] = $this->common_model->get_records($table, $fields, '', '', $match);
          $data['editRecord'][0]['fname'] = $data['editRecord'][0]['firstname'];
          $data['editRecord'][0]['lname'] = $data['editRecord'][0]['surname'];
          $data['editRecord'][0]['email_id'] = $data['editRecord'][0]['email_address'];
      }
      else if($user_type == 'social_data')
      {
          //get social info
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
      @Desc   : delete DO data
      @Input  :
      @Output :
      @Date   : 13/04/2018
     */

  public function deleteDo($yp_id,$do_id) {
            //Delete Record From Database
        if (!empty($yp_id) && !empty($do_id)) {
                      $where = array('do_id' => $do_id);
                        if ($this->common_model->delete(DAILY_OBSERVATIONS, $where)) {
                                    //Insert log activity
                                $activity = array(
                                  'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
                                  'module_name'         => DAILYOBSERVATION_MODULE,
                                  'module_field_name'   => '',
                                  'yp_id'   => $yp_id,
                                  'type'                => 3
                                );
                    log_activity($activity);
                    $msg = $this->lang->line('Deleted_DO_Successfully');
                    $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                    unset($docs_id);
                    
                } else {
                    // error
                    $msg = $this->lang->line('error_msg');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                    
                }
            }
        redirect('DailyObservation/index/'.$yp_id);
    }

/*
      @Author : Ritesh Rana
      @Desc   : DO external approval list data
      @Input  :
      @Output :
      @Date   : 13/04/2018
     */
    public function external_approval_list($ypid,$do_id,$care_home_id=0,$past_care_id=0) {
              /*
                Ritesh Rana
                for past care id inserted for archive full functionality
                */
        if ($past_care_id !== 0) {
            $temp = $this->common_model->get_records(PAST_CARE_HOME_INFO, array('move_date'), '', '', array("yp_id" => $ypid, "past_carehome" => $care_home_id));
            $data_care_home_detail = $this->common_model->get_records(PAST_CARE_HOME_INFO, array("enter_date,move_date"), '', '', array("yp_id" => $ypid, "move_date <= " => $temp[0]['move_date']));
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

        if(is_numeric($ypid) && is_numeric($do_id) && is_numeric($care_home_id)){
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($ypid,$fields);
        if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$ypid);
        }
        
        $searchtext = $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('do_approval_session_data');
        }

        $searchsort_session = $this->session->userdata('do_approval_session_data');
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
                $sortfield = 'do_signoff_details_id';
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
                $config['per_page'] = '10';
                $data['perpage'] = '10';
            }
        }
        //pagination configuration
        $config['first_link'] = 'First';

        if($past_care_id == 0){
            $config['base_url'] = base_url() . $this->viewname . '/external_approval_list/'.$ypid.'/'.$do_id;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 5;
            $uri_segment = $this->uri->segment(5);
        }

        //Query
        $table = NFC_DO_SIGNOFF_DETAILS . ' as dos';
        $where = array("dos.yp_id"=>$ypid,"dos.do_id"=>$do_id);
        $fields = array("dos.user_type,dos.fname,dos.lname,dos.created_date,dos.modified_date,dos.status,dos.do_signoff_details_id,dos.yp_id,CONCAT(`firstname`,' ', `lastname`) as create_name ,CONCAT(`fname`,' ', `lname`) as user_name,ch.care_home_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= dos.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = dos.care_home_id');
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
        }
            
        }else{
            $config['base_url'] = base_url() . $this->viewname . '/external_approval_list/'.$ypid.'/'.$do_id.'/'.$care_home_id.'/'.$past_care_id;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 7;
            $uri_segment = $this->uri->segment(7);
        }

         //Query
        $table = NFC_DO_SIGNOFF_DETAILS . ' as dos';
        $where = array("dos.yp_id"=>$ypid,"dos.do_id"=>$do_id);
        $where_date = "dos.created_date BETWEEN  '".$created_date."' AND '".$movedate."'";
        $fields = array("dos.user_type,dos.fname,dos.lname,dos.created_date,dos.modified_date,dos.status,dos.do_signoff_details_id,dos.yp_id,CONCAT(`firstname`,' ', `lastname`) as create_name ,CONCAT(`fname`,' ', `lname`) as user_name,ch.care_home_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= dos.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = dos.care_home_id');
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where,'','','','','',$where_date);
        
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1','','',$where_date);
        }
        }

        $data['ypid'] = $ypid;
        $data['do_id'] = $do_id;
            
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

        $this->session->set_userdata('do_approval_session_data', $sortsearchpage_data);
        setActiveSession('do_approval_session_data'); // set current Session active
        $data['header'] = array('menu_module' => 'Communication');
        $data['care_home_id'] = $care_home_id;
        $data['past_care_id'] = $past_care_id;

        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
        $data['header'] = array('menu_module' => 'YoungPerson');

        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/approval_ajaxlist', $data);
        } else {
            $data['main_content'] = '/do_list';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
        }else{
              show_404 ();
        } 
    }

/*
      @Author : Ritesh rana
      @Desc   : approve view data
      @Input  :
      @Output :
      @Date   : 13/04/2018
     */
public function viewApprovalDo($id,$ypid)                         
    {
        
      if(is_numeric($id) && is_numeric($ypid))
       {
         //get approve DO data
        $table = NFC_DO_SIGNOFF_DETAILS . ' as do';
                $where = array("do.do_signoff_details_id"=>$id,"do.yp_id"=>$ypid);
                $fields = array("do.*,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
                $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by',YP_DETAILS . ' as yp' => 'yp.yp_id= do.yp_id');

                $data['dodata'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '','','', '', $where);
                //get appointment data and medical appointment data
                $data['do_professionals_data'] = $this->MedicalProfessionalsAppointment($ypid,$data['dodata'][0]['daily_observation_date']);
            
                $data['appointment_view_comments'] = $this->AppointmentViewComments($ypid);

              //get archive food data
                if(!empty($data['dodata']))
                {
                  $data['food_form_data'] = json_decode($data['dodata'][0]['food_json_data'], TRUE);
                }
                
                //get archive food data
                if(!empty($data['dodata']))
                {
                  $data['summary_form_data'] = json_decode($data['dodata'][0]['summary_json_data'], TRUE);
                }
            if(!empty($data['dodata'][0]['staff_data']))
                {
                $wherein = "login_id in (".$data['dodata'][0]['staff_data'].")";
                $fields = array("concat(firstname,' ', lastname) as staff_name");
                $data['do_staff_data'] = $this->common_model->get_records(LOGIN, $fields, '', '', '','','','','','','',$wherein,'','');
                }


          //get YP information
          $fields = array("yp_id,care_home,yp_fname,yp_lname,date_of_birth");
          $data['YP_details'] = YpDetails($ypid,$fields);
          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$ypid);
          }

                $table = NFC_APPROVAL_OB_COMMENTS . ' as do';
                $where = array("do.approval_do_id" => $id, "do.yp_id" => $ypid);
                $fields = array("do.overview_comments,do.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");

                $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= do.yp_id');
                $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
                
                $table = NFC_APPROVAL_DO_SIGNOFF.' as do';
                $where = array("l.is_delete"=> "0","do.yp_id" => $ypid,"do.approval_do_id"=>$id);
                $fields = array("do.created_by,do.created_date,do.yp_id,do.approval_do_id, CONCAT(`firstname`,' ', `lastname`) as name");
                $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by');
                $group_by = array('created_by');
                $data['do_signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where); 

          $data['ypid'] = $ypid;
          $data['do_id'] = $data['dodata'][0]['do_id'];

          $data['footerJs'][0] = base_url('uploads/custom/js/communication/communication.js');
          $data['crnt_view'] = $this->viewname;
          $data['header'] = array('menu_module' => 'YoungPerson');
          $data['main_content'] = '/approval_view';
          $this->parser->parse('layouts/DefaultTemplate', $data);
        }
        else
        {
            show_404 ();
        }
    }
    /*
      @Author : Ritesh rana
      @Desc   : external approve view data
      @Input  :
      @Output :
      @Date   : 13/04/2018
     */
    
     public function resend_external_approval($signoff_id,$ypid,$doid) {
      $match = array('do_signoff_details_id'=>$signoff_id);
      $signoff_data = $this->common_model->get_records(NFC_DO_SIGNOFF_DETAILS,array("yp_id,fname,lname,email"), '', '', $match);
      if(!empty($signoff_data))
      {
        $data = array(
                'yp_id' => ucfirst($signoff_data[0]['yp_id']),
                'fname' => ucfirst($signoff_data[0]['fname']),
                'lname' => ucfirst($signoff_data[0]['lname']),
                'email' => $signoff_data[0]['email']
            );
        $sent = $this->sendMailToRelation($data,$signoff_id); // send mail
        if($sent)
        {
          $u_data['created_date'] = datetimeformat();
          $u_data['modified_date'] = NULL;
          $success =$this->common_model->update(NFC_DO_SIGNOFF_DETAILS,$u_data,array('do_signoff_details_id'=> $signoff_id));
          $msg = $this->lang->line('mail_sent_successfully');
          $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
        else
        {
          $msg = $this->lang->line('error');
          $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
      }
     
      redirect($this->viewname.'/external_approval_list/' . $ypid.'/'.$doid);
     }
     
     /*
      @Author : Nikunj Ghelani
      @Desc   : PDF data
      @Input  :
      @Output :
      @Date   : 26/06/2018
     */
public function DownloadPdf($id,$signoff_id) {
    if(is_numeric($id) && is_numeric($signoff_id))
       {
        $table = NFC_DO_SIGNOFF_DETAILS . ' as do';
        $where = array("do.do_signoff_details_id"=>$signoff_id,"do.yp_id"=>$id);
        $fields = array("do.*,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by',YP_DETAILS . ' as yp' => 'yp.yp_id= do.yp_id');
        $data['dodata'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '','','', '', $where);

        if(!empty($data['dodata'])){
                $expairedDate = date('Y-m-d H:i:s', strtotime($data['dodata'][0]['created_date'].REPORT_EXPAIRED_DAYS));
                if(strtotime(datetimeformat()) <= strtotime($expairedDate))
                {
                //get YP information
                $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
                $data['YP_details'] = YpDetails($id,$fields);
                
                $table = NFC_APPROVAL_OB_COMMENTS . ' as com';
                $where = array("com.approval_do_id" => $signoff_id, "com.yp_id" => $id);
                $fields = array("com.*,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");

                $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
                $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
               
            //get last day do
            $table = DAILY_OBSERVATIONS . ' as do';
            $where = array("do.daily_observation_date" => "'".date('Y-m-d',(strtotime ( '-1 day' , strtotime ($data['dodata'][0]['daily_observation_date']) )))."'", "do.yp_id" => $id);
            $fields = array("do.do_id,do.handover_next_day");
            $data['lastDayData'] = $this->common_model->get_records($table, $fields,'', '', '', '', '', '', '', '', '', $where);
            
            //get Medical Professionals Appointment data   
            $data['do_professionals_data'] = $this->MedicalProfessionalsAppointment($id,$data['dodata'][0]['daily_observation_date']);

            $data['appointment_view_comments'] = $this->AppointmentViewComments($id);
            
            //get planner appointment
            $table = PLANNER . ' as dpa';
            $where = array( "dpa.yp_id" => $id, 
                            'appointment_date' => "'".$data['dodata'][0]['daily_observation_date']."'",
                            'is_delete' => 0
                            );
            $fields = array("dpa.*");
            $data['do_planner_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
                
                //get archive food data
                if(!empty($data['dodata']))
                {
                  $data['food_form_data'] = json_decode($data['dodata'][0]['food_json_data'], TRUE);
                }
                
                //get archive food data
                if(!empty($data['dodata']))
                {
                  $data['summary_form_data'] = json_decode($data['dodata'][0]['summary_json_data'], TRUE);
                }


                if(!empty($data['dodata'][0]['staff_data']))
                {
                $wherein = "login_id in (".$data['dodata'][0]['staff_data'].")";
                $fields = array("concat(firstname,' ', lastname) as staff_name");
                $data['do_staff_data'] = $this->common_model->get_records(LOGIN, $fields, '', '', '','','','','','','',$wherein,'','');
                }

                $table = NFC_APPROVAL_DO_SIGNOFF.' as do';
                $where = array("l.is_delete"=> "0","do.yp_id" => $id,"do.approval_do_id"=>$signoff_id);
                $fields = array("do.created_by,do.created_date,do.yp_id,do.approval_do_id, CONCAT(`firstname`,' ', `lastname`) as name");
                $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by');
                $group_by = array('created_by');
                $data['do_signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);

                $data['signoff_id'] = $signoff_id;
                $data['key_data']= $email;
                $data['ypid'] = !empty($data['dodata'][0]['yp_id']) ? $data['dodata'][0]['yp_id'] : '';

                $data['main_content'] = '/signoff_view';
                $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
                $data['crnt_view'] = $this->viewname;
            
           //new
            $pdfFileName = "dailyobservation.pdf";
            $PDFInformation['yp_details'] = $data['YP_details'][0];
            $PDFInformation['edit_data'] = $data['edit_data'][0]['modified_date'];
            $PDFInformation['edit_date'] = $data['dodata'][0]['modified_date'];
            
            $PDFHeaderHTML  = $this->load->view('dailyobservationpdfHeader', $PDFInformation,true);
            
            $PDFFooterHTML  = $this->load->view('dailyobservationpdfFooter', $PDFInformation,true);
            
            //Set Header Footer and Content For PDF
            $this->m_pdf->pdf->mPDF('utf-8','A4','','','10','10','45','25');
    
            $this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
            $this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);                    
            $data['main_content'] = '/dailyobservationpdf';
            $html = $this->parser->parse('layouts/PdfDataTemplate', $data);
            
            /*remove*/
            $this->m_pdf->pdf->WriteHTML($html);
            
            //Store PDF in individual_strategies Folder
            $this->m_pdf->pdf->Output($pdfFileName, "D");
            
            }
            else
            {
                $msg = lang('link_expired');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>")
                ;
                $this->load->view('successfully_message');
            }
        }else{
            
            $msg = $this->lang->line('already_do_review');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        $this->load->view('successfully_message');
        }

        } else {
            show_404();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : get appointment data and medical appointment data
      @Input  :
      @Output :
      @Date   : 13/12/2018
    */
    public function MedicalProfessionalsAppointment($ypid,$daily_observation_date){
         $table = MEDICAL_PROFESSIONALS_APPOINTMENT . ' as dpa';
            $where = array( "dpa.yp_id" => $ypid, 
                            'appointment_date' => "'".$daily_observation_date."'",
                            'is_delete' => 0
                            );
            $join_tables = array(MEDICAL_PROFESSIONALS . ' as mp' => 'dpa.mp_id=mp.mp_id');
            $fields = array("dpa.appointment_date,dpa.appointment_time,dpa.comments,dpa.appointment_id,concat(mp.title,' ',mp.first_name,' ',mp.surname,' - ',mp.professional) as mp_name");
            $do_professionals_data = $this->common_model->get_records($table, $fields, $join_tables, '', '', '', '', '', '', '', '', $where);
            return $do_professionals_data;
    }

/*
      @Author : Ritesh Rana
      @Desc   : get Daily Observations data
      @Input  :
      @Output :
      @Date   : 13/12/2018
    */
   public function DailyObservations($ypid,$doid){
      $table = DAILY_OBSERVATIONS . ' as do';
            $where = array("do.do_id" => $doid, "do.yp_id" => $ypid);
            $fields = array("do.*,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= do.yp_id');
            $dodata = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
            return $dodata;
   } 


   /*
      @Author : Ritesh Rana
      @Desc   : get next Daily Observations data
      @Input  :
      @Output :
      @Date   : 27/03/2019
    */
   public function getNextDailyObservations($ypid,$doid,$date){
        $today_date = date('Y-m-d');
        $table = DAILY_OBSERVATIONS . ' as do';
                $where = array("do.yp_id" => $ypid);
                $fields = array("do.do_id,do.yp_id,do.is_filled_field,do.is_filled_field_overview,If(do.daily_observation_date = '".$today_date."',1,0) as todaydate,do.daily_observation_date");
                $sortfield = 'do.daily_observation_date';
                $sortby = 'desc';
                $information = $this->common_model->get_records($table, $fields,'', '', '', '', '', '', $sortfield, $sortby, '', $where);
               
                $info = array();
                foreach ($information as $value) {
                     $info[$value['do_id']] = $value['daily_observation_date'];
                } 

                    $i = array_search($doid, array_keys($info));

                    $do_pre_and_next['next'] = $information[$i-1]['do_id']; 
                    $do_pre_and_next['pre'] = $information[$i+1]['do_id'];
        return $do_pre_and_next;
   } 

   /*
      @Author : Ritesh Rana
      @Desc   : get Appointment View Comments data
      @Input  :
      @Output :
      @Date   : 13/12/2018
    */
   public function AppointmentViewComments($ypid){
        
              $table = MD_COMMENTS . ' as com';
              $where = array("com.yp_id" => $ypid);
              $fields = array("com.*,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
              $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
              $appointment_view_comments = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);

            return $appointment_view_comments;
   } 

}
