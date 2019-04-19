<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class YoungPerson extends CI_Controller {

    function __construct() {

        parent::__construct();
        if (checkPermission('YoungPerson', 'view') == false) {
            redirect('/Dashboard');
        }
        $this->load->model('imageupload_model');
        $this->load->model('YP_model');
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Ritesh rana
      @Desc   : Registration Index Page
      @Input 	:
      @Output	:
      @Date   : 25/06/2017
     */

    public function index($care_home_id, $page = 0) {

        $searchtext = $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $yp_list_type = $this->input->post('yp_list_type');
        $data['yp_list_type'] = !empty($yp_list_type) ? $yp_list_type : 'display-list';
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('yp_data');
        }
$data['currentDate'] = dateformat(date('Y-m-d'));
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
                $sortfield = 'yp_id';
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
		$config['base_url'] = base_url() . $this->viewname . '/index/' . $care_home_id;
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
		$table = CARE_HOME . ' as ch';
        $where = array("yp.is_deleted" => "'0'", "yp.is_archive" => '0', "ch.care_home_id" => $care_home_id, "ch.status" => "'active'", "ch.is_delete" => "'0'");
        $fields = array("yp.date_of_placement,yp.yp_id, CONCAT(`yp_fname`,' ', `yp_lname`) as name,yp.yp_fname,yp.yp_lname,yp.created_date,yp.yp_initials,yp.gender,yp.date_of_birth,yp.staffing_ratio,yp.profile_img,yp.age,ch.care_home_name,ch.care_home_id");
        $join_tables = array(YP_DETAILS . ' as yp' => 'yp.care_home = ch.care_home_id');

        if (!empty($searchtext)) {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = "";
			$where_search = '
							(
								(
									CONCAT(`yp_fname`, \' \', `yp_lname`) LIKE "%' . $searchtext . '%" 
									OR yp.yp_fname LIKE "%' . $searchtext . '%" 
									OR yp.yp_lname LIKE "%' . $searchtext . '%"
								)
								OR yp.age LIKE "%' . $searchtext . '%" 
								OR yp.gender LIKE "%' . $searchtext . '%" 
								OR yp.staffing_ratio LIKE "%' . $searchtext . '%" 
								OR ch.care_home_name LIKE "%' . $searchtext . '%"
							)
							
							AND yp.is_deleted = "0"
							AND yp.is_archive = "0"
							AND ch.care_home_id ="'.$care_home_id.'"
							AND ch.status = "active"
							AND ch.is_delete ="0"
							';
			
			$data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);

            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1');
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
        
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
        }


        $data['care_home_name_data'] = getCareHomeData($care_home_id);
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

        $this->session->set_userdata('yp_data', $sortsearchpage_data);
        $data['care_home_id'] = $care_home_id;
        setActiveSession('yp_data'); // set current Session active
        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/youngperson/youngperson.js');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/ajaxlist', $data);
        } else {
            $data['main_content'] = '/youngperson';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
    }

    /*
      @Author : Ritesh rana
      @Desc   : create yp Registration page
      @Input 	:
      @Output	:
      @Date   : 27/06/2017
     */

    public function registration($care_home_id = 0) {
        $this->formValidation();
        $main_user_data = $this->session->userdata('LOGGED_IN');
        if ($this->form_validation->run() == FALSE) {

            $data['footerJs'][0] = base_url('uploads/custom/js/youngperson/youngperson.js');
            $data['crnt_view'] = $this->viewname;

            $table = CARE_HOME . ' as ch';
            $match = "ch.status = 'active' AND ch.is_delete=0 ";
            $fields = array("ch.status,ch.care_home_name,ch.care_home_id");
            $data['care_home_data'] = $this->common_model->get_records($table, $fields, '', '', $match);

            //Get Records From Login Table
            $data['userType'] = getUserType($main_user_data['ROLE_TYPE']);
            $data['initialsId'] = $this->common_model->initialsId();

            $data['form_action_path'] = $this->viewname . '/registration';
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['validation'] = validation_errors();
            $data['care_home_id'] = $care_home_id;
            
            if (!empty($data['validation'])) {
                $data['main_content'] = '/registration';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            } else {
                $this->load->view('/registration', $data);
            }
        } else {
            $this->insertdata();
        }
    }

    /*
      @Author : ritesh rana
      @Desc   : yp insert data
      @Input 	:
      @Output	:
      @Date   : 27/06/2017
     */

    public function insertdata() {
		
        $postdata = $this->input->post();
        $checkAvalibility = "";

        $initialsID = $postdata['initials'];
        $table = YP_DETAILS . ' as yp';
        $match = "yp.is_deleted= '0' AND yp.yp_initials = '" . $initialsID . "'";
        $fields = array("yp.yp_id,yp.status");
        $duplicateEmail = $this->common_model->get_records($table, $fields, '', '', $match);
        $count = count($duplicateEmail);
        if (isset($duplicateEmail) && empty($duplicateEmail) && $count == 0) {
            //Current Login detail
            $main_user_data = $this->session->userdata('LOGGED_IN');
            

            if (!validateFormSecret()) {
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
                redirect('YoungPerson'); //Redirect On Listing page
            }
            $data['crnt_view'] = $this->viewname;

            $care_home_date = dateformat($postdata['care_home_admission_date']);
			$admissionDate = dateformat($postdata['care_home_admission_date']);
            $currentDate = dateformat(date('Y-m-d'));
			
			if (strtotime($currentDate) < strtotime($admissionDate)) {
				$data = array(
                'yp_fname' => ucfirst($postdata['fname']),
                'yp_lname' => ucfirst($postdata['lname']),
                'yp_initials' => $postdata['initials'],
                'date_of_placement' => dateformat($care_home_date),
                'care_home_admission_date' => dateformat($care_home_date),  
                'care_home' => $postdata['care_home'],
                'created_date' => datetimeformat(),
                'modified_date' => datetimeformat(),
                'created_by' => $main_user_data['ID'],
                'updated_by' => $main_user_data['ID'],
                'is_move' => 0,
            );
			$this->common_model->insert(YP_DETAILS_CRON, $data);
			 $msg = "Your YP Will On Admisstion Date";
             $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
			redirect('YoungPerson/index/' . $postdata['care_home']);
				
			}elseif(strtotime($currentDate) == strtotime($admissionDate)){
				$data = array(
                'yp_fname' => ucfirst($postdata['fname']),
                'yp_lname' => ucfirst($postdata['lname']),
                'yp_initials' => $postdata['initials'],
                'date_of_placement' => dateformat($care_home_date),
                'care_home_admission_date' => dateformat($care_home_date),  
                'care_home' => $postdata['care_home'],
                'created_date' => datetimeformat(),
                'modified_date' => datetimeformat(),
                'created_by' => $main_user_data['ID'],
                'updated_by' => $main_user_data['ID'],
				'is_move' => 1,
            );
		}
            //Insert Record in Database
        if ($this->common_model->insert(YP_DETAILS, $data)) {
                $yp_id = $this->db->insert_id();

              $yp_initial = $postdata['initials'];
              $yp_init = substr($yp_initial,0,3);
              $yp_fromMail = $yp_init.'-'.YP_EMAIL;
            $emailData['email_id'] = ($yp_fromMail != '') ? $yp_fromMail : '';
            $emailData['email_pass'] = '';
            $emailData['email_server'] = 'outlook.office365.com';
            $emailData['email_port'] = '993';
            $emailData['email_encryption'] = 'TLS';
            $emailData['email_smtp'] = 'smtp.office365.com';
            $emailData['email_smtp_port'] = '587';
            $emailData['modified_date'] = datetimeformat();
            $emailData['user_id'] = $yp_id;
            $emailData['created_date'] = datetimeformat();
            $this->common_model->insert(EMAIL_CONFIG, $emailData);
                $meddata = array(
                    'yp_id' => $yp_id,
                    'medical_number' => $this->common_model->medicalnumber(),
                    'created_by' => !empty($this->session->userdata('LOGGED_IN')['ID']) ? $this->session->userdata('LOGGED_IN')['ID'] : '',
                    'created_date' => datetimeformat()
                );
                $this->common_model->insert(MEDICAL_INFORMATION, $meddata);
                //Insert log activity
                $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($yp_id) ? $yp_id : '',
                    'module_name' => YP_NEW_ADD,
                    'module_field_name' => '',
                    'type' => 1
                );
                log_activity($activity);
                $msg = $this->lang->line('yp_add_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            } else {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
        } else {
            // error
            $msg = 'Entered InitialsID is already exist.';
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }
        redirect('YoungPerson/index/' . $postdata['care_home']);
    }

    /*
      @Author : Ritesh Rana
      @Desc   : UserList Edit Page
      @Input 	:
      @Output	:
      @Date   : 07/03/2017
     */

    public function edit($id) {
        $this->formValidation($id);
        if ($this->form_validation->run() == FALSE) {
            $data['footerJs'][0] = base_url('uploads/custom/js/youngperson/youngperson.js');
            //Get Records From Login Table
            // Put your form submission code here after processing the form data, unset the secret key from the session /

            $table = CARE_HOME . ' as ch';
            $match = "ch.status = 'active' AND ch.is_delete=0 ";
            $fields = array("ch.status,ch.care_home_name,ch.care_home_id");
            $data['care_home_data'] = $this->common_model->get_records($table, $fields, '', '', $match);

            $table = MOVE_TO_CAREHOME . ' as mvtc';
            $match = "mvtc.yp_id = " . $id . " AND mvtc.status=1";
            $fields = array("mvtc.*");
            $data['move_care_home_data'] = $this->common_model->get_records($table, $fields, '', '', $match);

            $this->session->unset_userdata('FORM_SECRET', '');
            $table = YP_DETAILS . ' as yp';
            $match = "yp.yp_id = '" . $id . "' AND yp.is_deleted= '0' ";
            $fields = array("yp.*");
            $data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['id'] = $id;
            $data['current_Care_home'] = $data['editRecord'][0]['care_home'];
            $data['readonly'] = array("disabled" => "disabled");
            $data['validation'] = validation_errors();
            $data['crnt_view'] = $this->viewname;
            $data['form_action_path'] = $this->viewname . '/edit/' . $id;
            $data['header'] = array('menu_module' => 'YoungPerson');

            if (!empty($data['validation'])) {
                $data['main_content'] = '/yp_edit';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            } else {
                $this->load->view('/yp_edit', $data);
            }
        } else {
            /* all the condition is added by Dhara Bhalala for move yp to date wise */
            $postdata = $this->input->post();
            $admissionDate = dateformat($postdata['care_home_admission_date']);
            $currentDate = dateformat(date('Y-m-d'));
            if (strtotime($currentDate) < strtotime($admissionDate)) {
                /* if future date */
               $this->move_updatedata();
            } elseif (strtotime($currentDate) > strtotime($admissionDate)) {
                /* if past date */
                 $this->movePastYPInfo();
            } elseif (strtotime($currentDate) == strtotime($admissionDate)) {
                /* if today's date */
                $this->updatedata();
            }            
        }
    }
	/*nikunj ghelani
	30-10-2018
	for crone job setting
	*/
	public function CronJob(){
		$table = MOVE_TO_CAREHOME . ' as mvtc';
        $match = "mvtc.status = 1";
        $fields = array("mvtc.*");
        $data['move_care_home_data'] = $this->common_model->get_records($table, $fields, '', '', $match);
		foreach($data['move_care_home_data'] as $move_data){
			$today_date=explode('-',$move_data['move_date']);
			$final_today_date=$today_date['2'].'/'.$today_date['1'].'/'.$today_date['0'];
			$data = array(
					'status' => 1,
            	);
			$where = array('id' => $move_data['id']);
			$this->common_model->update(MOVE_TO_CAREHOME, $data, $where);
		if (date("d/m/Y") == $final_today_date) {
			$match = array("yp_id" =>$move_data['yp_id']);
            $fields = array("*");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
			
			  $postdata = array(
					'yp_id' => ucfirst($data['YP_details'][0]['yp_id']),
					'yp_fname' => ucfirst($data['YP_details'][0]['yp_fname']),
					'yp_lname' => ucfirst($data['YP_details'][0]['yp_lname']),
					'care_home' => $move_data['move_care_home'],
					'care_home_admission_date' => dateformat($move_data['move_date']),  
					'modified_date' => datetimeformat(),
					'updated_by' => $this->session->userdata['LOGGED_IN']['ID'],
				);
				$is_cron=1;
				$this->updatedata($postdata,$is_cron);
			
		}else{
			return false;
		}
		}
		
		
	
		
	}
	
	/*nikunj ghelani
	5-11-2018
	for crone job yp add
	*/
	public function CronJobYPADD(){
		//die('here');
		
		$table = YP_DETAILS_CRON . ' as ypdc';
        $match = "ypdc.is_move = 0";
        $fields = array("ypdc.*");
        $data['move_care_home_data_yp_detail'] = $this->common_model->get_records($table, $fields, '', '', $match);
		// pr($data['move_care_home_data_yp_detail']);
		// die;
		foreach($data['move_care_home_data_yp_detail'] as $move_data){
			
			$today_date=explode('-',$move_data['care_home_admission_date']);
			$final_today_date=$today_date['2'].'/'.$today_date['1'].'/'.$today_date['0'];
			
		if (date("d/m/Y") == $final_today_date) {
			$data = array(
					'is_move' => 1,
					
				);
			$where = array('yp_id' => $move_data['yp_id']);
			$this->common_model->update(YP_DETAILS_CRON, $data, $where);
			
			
		
			 $data = array(
                'yp_fname' => ucfirst($move_data['yp_fname']),
                'yp_lname' => ucfirst($move_data['yp_lname']),
                'yp_initials' => $move_data['yp_initials'],
                'date_of_placement' => $move_data['date_of_placement'],
                'care_home_admission_date' => $move_data['care_home_admission_date'],  
                'care_home' => $move_data['care_home'],
                'created_date' => datetimeformat(),
                'modified_date' => datetimeformat(),
				/*this both are comment becaue this cron job and we dont have session.
				nikunj ghelani
				05/11/2018
				*/
               /*  'created_by' => $main_user_data['ID'],
                'updated_by' => $main_user_data['ID'], */
                
            );
			
			$this->common_model->insert(YP_DETAILS, $data); 
			$is_cron=1;
		}else{
			//return false;
		}
	}		
	}
	
	/*nikunj ghelani
	31-10-2018
	cancle cron if user want
	*/
	public function canclecron($ypid){
		/*fatch data for ypid based*/
		$table = MOVE_TO_CAREHOME . ' as mvtc';
        $match = "mvtc.yp_id = " . $ypid;
        $fields = array("mvtc.*");
        $data['care_home_data'] = $this->common_model->get_records($table, $fields, '', '', $match);
		
		/*delete data from table because user cancled cron job*/
		$where="yp_id=".$data['care_home_data'][0]['yp_id'];
		$this->common_model->delete(MOVE_TO_CAREHOME,$where); 
		echo base_url().'YoungPerson/index/' . $data['care_home_data'][0]['current_care_home'];
		exit;
	}
    
    /*function created by Dhara Bhalala to move YP's past info*/
    public function movePastYPInfo() {
        $postdata = $this->input->post(); 
        $yp_id = isset($postdata['yp_id']) ? $postdata['yp_id'] : '';
        $pastCareHomeData = $this->common_model->get_records(PAST_CARE_HOME_INFO, array('move_date'), '', '', array("yp_id" => $yp_id));
       
        $check_carehome = $this->YP_model->check_carehome($yp_id);
        if($check_carehome[0]['care_home'] != $postdata['care_home'] || (isset($pastCareHomeData) && !empty($pastCareHomeData))){
            $match = array("yp_id"=>$yp_id,"is_deleted" => '0', "is_archive" => '0');
            $fields = array("*");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

            //SELECT THOSE TABLE WHICH HAS CARE_HOME_ID COLOUMN
            $database = $this->db->database;
            $sql = $this->db->query("Select * From INFORMATION_SCHEMA.COLUMNS Where column_name = 'care_home_id' AND TABLE_SCHEMA = '".$database."'");
            $result = $sql->result_array();
            $admissionDate = dateformat($postdata['care_home_admission_date']);
            $todayDate = dateformat(date('Y-m-d'));
            $where_date = "created_date BETWEEN  '" . $admissionDate . "' AND '" . $todayDate . "'";
            $this->db->query("SET SQL_SAFE_UPDATES = 0;");  
            foreach ($data['YP_details'] as $yp_detail) {
                foreach ($result as $table_name) {
                    if (($table_name['TABLE_NAME'] != 'nfc_care_home') && ($table_name['TABLE_NAME'] != 'nfc_medical_care_home_transection') && ($table_name['TABLE_NAME'] != 'nfc_record_ans')) {
                        $update = $this->db->query("UPDATE " . $table_name['TABLE_NAME'] . " SET care_home_id = '" . intval($postdata['care_home']) . "' WHERE yp_id='" . $yp_detail['yp_id'] . "' AND " .$where_date ."");                       
                    }
                }
            }
            $this->db->query("SET SQL_SAFE_UPDATES = 1;"); 
        }    
        /*first moved all data. now move yp to new care home */
        $this->updatedata();
    }

    /* nikunj ghelani on 26-10-2018 for care to care move if date is not today */
    public function move_updatedata() {
        $postdata = $this->input->post();
        $main_user_data = $this->session->userdata('LOGGED_IN');
        $yp_id = isset($postdata['yp_id']) ? $postdata['yp_id'] : '';
        /* for future and past data care to care home archive data move functionality */

        $table = MOVE_TO_CAREHOME . ' as mvtc';
        $match = "mvtc.yp_id = " . $yp_id;
        $fields = array("mvtc.*");
        $data['care_home_data'] = $this->common_model->get_records($table, $fields, '', '', $match);
		$admissionDate = dateformat($postdata['care_home_admission_date']);
        $currentDate = dateformat(date('Y-m-d'));
          
         if (strtotime($currentDate) < strtotime($admissionDate)) {
			//die('here');
            $insert_data = array(
                'yp_id' => $yp_id,
                'current_care_home' => $postdata['current_Care_home'],
                'move_care_home' => $postdata['care_home'],
                'move_date' => dateformat($postdata['care_home_admission_date']),
                'created_date' => datetimeformat(),
                'created_by' => $main_user_data['ID'],
                'status' => '1'
            );
            if (isset($data['care_home_data']) && !empty($data['care_home_data'])) {
                $where = array('yp_id' => $yp_id);
                $this->common_model->update(MOVE_TO_CAREHOME, $insert_data, $where);
            } else {
                $this->common_model->insert(MOVE_TO_CAREHOME, $insert_data);
            }

            redirect('YoungPerson/index/' . $postdata['current_Care_home']);
        }
    }

// ishani dave
    public function updatedata($postdata=null,$is_cron=0) {
		if($is_cron>0){
		
		}else{
			$postdata = $this->input->post();
		}
	
        $data['crnt_view'] = $this->viewname;
        
        //Current Login detail
        $main_user_data = $this->session->userdata('LOGGED_IN');
        $yp_id = isset($postdata['yp_id']) ? $postdata['yp_id'] : '';
        
        // get yp details 
        $check_carehome = $this->YP_model->check_carehome($yp_id);
        if($check_carehome[0]['care_home'] != $postdata['care_home']){
            
            //get all medicine of that yp
            $table = MEDICATION . ' as m';
            $where = array("mch.care_home_id"=>$check_carehome[0]['care_home'],'m.yp_id'=>$yp_id/*,'m.is_archive'=>0*/);
            $fields = array("m.medication_id,m.yp_id,m.medication_name,mch.care_home_id,mch.medical_care_home_id");
            $joinTables = array(MEDICAL_CARE_HOME_TRANSECTION . ' as mch' => 'm.medication_id= mch.medication_id');
            $prevMedicationTran = $this->common_model->get_records($table, $fields, $joinTables, 'left', '', '', '','','m.medication_id','asc', '', $where);

            if(!empty($prevMedicationTran))
            {
                foreach ($prevMedicationTran as $row) {
                    //get medication
                    $table = MEDICATION . ' as m';
                    $where = array("m.medication_id"=>$row['medication_id'],'mc.record_medication_offered_but_refused'=>NULL);
                    $fields = array("m.medication_id,sum(quantity_left) as total_given,m.stock");
                    $join_tables = array(YP_DETAILS . ' as yp' => 'yp.yp_id= m.yp_id',ADMINISTER_MEDICATION . ' as mc' => 'm.medication_id= mc.select_medication',LOGIN . ' as l' => 'l.login_id=m.stock_checked_by');
                   
                    $medicationDataCare = $this->common_model->get_records($table, $fields, $join_tables, 'left',$where);
                    
                    //update old care home medication stock
                    $updateArchivedata['is_archive'] = 1;
                    $updateArchivedata['quntity_given'] = !empty($medicationDataCare[0]['total_given'])?$medicationDataCare[0]['total_given']:0;
                    $updateArchivedata['quntity_remaining'] = !empty($medicationDataCare[0]['stock'])?$medicationDataCare[0]['stock']:0;
                    $this->common_model->update(MEDICAL_CARE_HOME_TRANSECTION,$updateArchivedata,array('medical_care_home_id'=>$row['medical_care_home_id']));
                    //insert medication stock to new care home id
                    $medicationData['medication_id'] = $row['medication_id'];
                    $medicationData['care_home_id']  = $postdata['care_home'];
                    $medical_care_home_id = $this->common_model->insert(MEDICAL_CARE_HOME_TRANSECTION, $medicationData);
                }
            }
            
            // check past carehome exist  
            $check_yp_carehome = $this->YP_model->check_pastcarehome($yp_id);
            if(!empty($check_yp_carehome)){
            $enter_date = $check_carehome[0]['care_home_admission_date'];
            //update past_carehome
            $u_data['is_move'] = '1';
            $where = array('yp_id' => $yp_id);
            $data['update_data'] = $this->common_model->update(PAST_CARE_HOME_INFO, $u_data, $where);
            }else{
                $enter_date = $check_carehome[0]['care_home_admission_date'];
            }
            $insert_data = array(
                'yp_id' => $yp_id,
                'past_carehome' => ucfirst($check_carehome[0]['care_home']),
                'current_carehome' => $postdata['care_home'],
                'move_date' =>  dateformat($postdata['care_home_admission_date']),
                'created_date' => datetimeformat(),
                'modified_date' => datetimeformat(),
                'created_by' => $main_user_data['ID'],
                'modified_by' => $main_user_data['ID'],
                'is_move' => '0'
            );
            $insert_data['enter_date'] = $enter_date;
            $this->common_model->insert(PAST_CARE_HOME_INFO, $insert_data);

            
        }
        $care_home_date = dateformat($this->input->post('care_home_admission_date'));
		if($is_cron>0){
        $data = array(
            'yp_fname' => ucfirst($postdata['yp_fname']),
            'yp_lname' => ucfirst($postdata['yp_lname']),
            'care_home' => $postdata['care_home'],
            'care_home_admission_date' => dateformat($postdata['care_home_admission_date']),  
            'modified_date' => datetimeformat(),
            'updated_by' => $this->session->userdata['LOGGED_IN']['ID'],
        );
		}else{
			
			 $data = array(
					'yp_fname' => ucfirst($postdata['fname']),
					'yp_lname' => ucfirst($postdata['lname']),
					'care_home' => $postdata['care_home'],
					'care_home_admission_date' => dateformat($care_home_date),  
					'modified_date' => datetimeformat(),
					'updated_by' => $this->session->userdata['LOGGED_IN']['ID'],
				);
		}
	
        if($check_carehome[0]['care_home'] != $postdata['care_home']){
        $data['move_date'] = datetimeformat();
        }
		 

        //Update Record in Database
        $where = array('yp_id' => $yp_id);
        // Update form data into database
        if ($this->common_model->update(YP_DETAILS, $data, $where)) {
            $msg = $this->lang->line('yp_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {    // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }
        redirect('YoungPerson/index/' . $postdata['care_home']);
    }

    /*
      @Author : Ritesh rana
      @Desc   : User List Update Query
      @Input 	: Post record from userlist
      @Output	: Update data in database and redirect
      @Date   : 07/03/2017
     */

    public function view($id) {
		
        if (is_numeric($id)) {
			
            //Get Records From Login Table
            $data['footerJs'][0] = base_url('uploads/custom/js/youngperson/youngperson.js');
            $table = YP_DETAILS . ' as yp';
            $match = "yp.yp_id = " . $id;
            $fields = array("yp.*,pa.authority,pa.out_of_hours,pa.address_1,pa.address_2,pa.town,pa.county,pa.postcode,swd.social_worker_firstname,swd.social_worker_surname,swd.landline,swd.mobile,swd.email,swd.senior_social_worker_firstname,swd.senior_social_worker_surname,oyp.pen_portrait,swd.other,pc.firstname,pc.surname,pc.relationship,pc.address,pc.contact_number,pc.email_address,pc.yp_authorised_communication,pc.carer_authorised_communication,pc.comments");
            $join_tables = array(PLACING_AUTHORITY . ' as pa' => 'pa.yp_id= yp.yp_id', SOCIAL_WORKER_DETAILS . ' as swd' => 'swd.yp_id= yp.yp_id', PARENT_CARER_INFORMATION . ' as pc' => 'pc.yp_id= yp.yp_id', OVERVIEW_OF_YOUNG_PERSON . ' as oyp' => 'oyp.yp_id= yp.yp_id');

            $data['editRecord'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
            $table = PARENT_CARER_INFORMATION . ' as pc';
            $match = array("pc.yp_id" => $id,'pc.is_deleted' => 0);
            $fields = array("pc.*");
            $data['parentData'] = $this->common_model->get_records($table, $fields, '', '', $match,'','','','parent_carer_id','desc');
            
            if (empty($data['editRecord'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect($this->viewname);
            }
            $tableCare = PAST_CARE_HOME_INFO . ' as pc';
            $matchCare = "pc.yp_id = '" . $id . "' AND pc.is_delete= '0'";
            $fieldsCare = array("*");
            /* query order updated by Dhara Bhalala to display descending records */
            $data['care_homeRecord'] = $this->common_model->get_records($tableCare, $fieldsCare, '', '', $matchCare,'','','','pc.id','desc');
            $data['id'] = $id;
            $data['care_home_id'] = $data['editRecord'][0]['care_home'];
            $data['userType'] = getUserType();
            $data['crnt_view'] = $this->viewname;

                     //get YP information
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id,yp_initials");
          $YP_details = YpDetails($id,$fields);
          $yp_initials = $YP_details[0]['yp_initials'];

          $yp_init = substr($yp_initials,0,3);
          $data['yp_fromMail'] = $yp_init.'-'.YP_EMAIL;
          
            $countMails = $this->YPmailCount($id);
        
            $data['countMails'] = $countMails;            
            $data['main_content'] = '/profile';
            $data['header'] = array('menu_module' => 'YoungPerson');
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }

    /*
      @Author : Ritesh rana
      @Desc   : yp mail count 
      @Input    : ypid 
      @Output   : number of count unread mail 
      @Date   : 07/03/2017
     */
    function YPmailCount($yp_id){
      $fields = array("ec.is_unread,ec.uid");
        $join = array(EMAIL_CLIENT_ATTACHMENTS . ' as ema' => 'ema.email_id=ec.email_unq_id');
        $group_by = 'ec.uid';
        $boxtype = 'INBOX';
        $where = "ec.boxtype='" . $boxtype . "' and ec.user_id ='" . $yp_id ."'and ec.is_unread = '1' and ec.is_deleted = '0'";
        $data['mail_data'] = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', $fields, $join, 'left', '', '', '', '', '', '', $group_by, $where);
        $data['inbox_count'] = count($data['mail_data']);
        return $data['inbox_count'];
}

/*
      @Author : Ritesh rana
      @Desc   : yp personal inforamation
      @Input    : ypid 
      @Output   : 
      @Date   : 07/03/2017
     */
    public function personal_info($id) {
        //Get Records From Login Table
        $data['footerJs'][0] = base_url('uploads/custom/js/youngperson/youngperson.js');
        $table = YP_DETAILS . ' as yp';
        $match = "yp.yp_id = " . $id;
        $fields = array("yp.*");
        $data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
        $data['id'] = $id;
        $data['crnt_view'] = $this->viewname;
        $data['form_action_path'] = $this->viewname . '/updatePersonalInfo/' . $id;
        $data['main_content'] = '/personal_info';
        $this->load->view('/personal_info', $data);
    }

    /*
      @Author : Ritesh rana
      @Desc   : update yp personal information
      @Input    : 
      @Output   : 
      @Date   : 07/03/2017
     */
    public function updatePersonalInfo() {
        $postdata = $this->input->post();
		
        $id = $this->input->post('yp_id');
      
        $sess_array = array('setting_current_tab' => 'setting_user');
        $this->session->set_userdata($sess_array);
        $dob = dateformat($this->input->post('date_of_birth'));
        $dp = $this->input->post('date_of_placement');
        $adt = $this->input->post('assessment_date_start');
        $ard = $this->input->post('assessment_review_date');
        $eop = $this->input->post('end_of_placement');
        $ade = $this->input->post('assessment_date_end');

        if(!empty($postdata['end_of_placement'])){
                $data['end_of_placement'] = dateformat($eop);
            }
        if(!empty($dp)){
                $data['date_of_placement'] = dateformat($dp);
            }

            if(!empty($adt)){
                $data['assessment_date_start'] = dateformat($adt);
            }
            if(!empty($ade)){
                $data['assessment_date_end'] = dateformat($ade);
            }

            if(!empty($ard)){
                $data['assessment_review_date'] = dateformat($ard);
            }
            
            $data['date_of_birth'] = $dob;
            $data['gender'] = strip_tags($postdata['gender']);
            $data['age'] = date_diff(date_create($dob), date_create('today'))->y;
            $data['place_of_birth'] = trim(strip_tags($postdata['place_of_birth']));
            $data['legal_status'] = strip_tags($postdata['legal_status']);
            $data['staffing_ratio'] = strip_tags($postdata['staffing_ratio']);
            $data['updated_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            $data['modified_date'] = datetimeformat();
    

        //Update Record in Database
        $where = array('yp_id' => $id);
        // Update form data into database
        if ($this->common_model->update(YP_DETAILS, $data, $where)) {
            //Insert log activity
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($id) ? $id : '',
                'module_name' => PERSONAL_INFO_YP,
                'module_field_name' => '',
                'type' => 2
            );

            log_activity($activity);
            $msg = $this->lang->line('yp_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {    // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

        }
        redirect('YoungPerson/view/' . $id); //Redirect On Listing page
    }

    /*
      @Author : Ritesh rana
      @Desc   : placing Authority information
      @Input    : ypid
      @Output   : 
      @Date   : 07/03/2017
     */
    public function placingAuthority($id) {
        //Get Records From Login Table
        $data['footerJs'][0] = base_url('uploads/custom/js/youngperson/youngperson.js');
        $table = PLACING_AUTHORITY . ' as pa';
        $match = "pa.yp_id = " . $id;
        $fields = array("pa.*");
        $data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
        $data['id'] = $id;
        $data['crnt_view'] = $this->viewname;
        $data['form_action_path'] = $this->viewname . '/updatePlacingAuthority/' . $id;
        $data['main_content'] = '/placingauthority';
        $this->load->view('/placingauthority', $data);
    }

    /*
      @Author : Ritesh rana
      @Desc   : update placing Authority information
      @Input    : ypid
      @Output   : 
      @Date   : 07/03/2017
     */
    public function updatePlacingAuthority() {
        $postdata = $this->input->post();
        $id = $this->input->post('yp_id');
        $table = PLACING_AUTHORITY . ' as pa';
        $match = "pa.yp_id = " . $id;
        $fields = array("pa.*");
        $placingRecord = $this->common_model->get_records($table, $fields, '', '', $match);

        $data['authority'] = strip_tags($postdata['authority']);
        $data['authority'] = strip_tags($postdata['authority']);
        $data['address_1'] = strip_tags($postdata['address_1']);
        $data['address_2'] = strip_tags($postdata['address_2']);
        $data['town'] = strip_tags($postdata['town']);
        $data['county'] = strip_tags($postdata['county']);
        $data['postcode'] = strip_tags($postdata['postcode']);
        $data['out_of_hours'] = strip_tags($postdata['out_of_hours']);
        $where = array('yp_id' => $id);
        if (empty($placingRecord)) {
            $data['yp_id'] = $postdata['yp_id'];
            $data['created_date'] = datetimeformat();
            $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            $success = $this->common_model->insert(PLACING_AUTHORITY, $data);
            //Insert log activity
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($postdata['yp_id']) ? $postdata['yp_id'] : '',
                'module_name' => PLACING_AUTHORITY_YP,
                'module_field_name' => '',
                'type' => 1
            );
            log_activity($activity);
        } else {
            $data['modified_date'] = datetimeformat();
            $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            $success = $this->common_model->update(PLACING_AUTHORITY, $data, $where);
            //Insert log activity
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($postdata['yp_id']) ? $postdata['yp_id'] : '',
                'module_name' => PLACING_AUTHORITY_YP,
                'module_field_name' => '',
                'type' => 2
            );
            log_activity($activity);
        }
        $data['crnt_view'] = $this->viewname;
        if ($success) {
            $msg = $this->lang->line('yp_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {    // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }
        redirect('YoungPerson/view/' . $id); //Redirect On Listing page
    }
    
    /*
      @Author : Ritesh rana
      @Desc   : social Worker Details function 
      @Input    : ypid
      @Output   : 
      @Date   : 07/03/2017
     */
    public function socialWorkerDetails($id) {
        //Get Records From Login Table
        $data['footerJs'][0] = base_url('uploads/custom/js/youngperson/youngperson.js');
        $table = SOCIAL_WORKER_DETAILS . ' as swd';
        $match = "swd.yp_id = " . $id;
        $fields = array("swd.*");
        $data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
        $data['id'] = $id;
        $data['crnt_view'] = $this->viewname;
        $data['form_action_path'] = $this->viewname . '/updateSocialWorkerDetails/' . $id;
        $data['main_content'] = '/socialworkerdetails';
        $this->load->view('/socialworkerdetails', $data);
    }
    /*
      @Author : Ritesh rana
      @Desc   : update social Worker Details function 
      @Input    : 
      @Output   : 
      @Date   : 07/03/2017
    */
    public function updateSocialWorkerDetails() {
        $postdata = $this->input->post();
        $id = $this->input->post('yp_id');
        $table = SOCIAL_WORKER_DETAILS . ' as pa';
        $match = "pa.yp_id = " . $id;
        $fields = array("pa.*");
        $socialRecord = $this->common_model->get_records($table, $fields, '', '', $match);

        $data['social_worker_firstname'] = strip_tags($postdata['social_worker_firstname']);
        $data['social_worker_surname'] = strip_tags($postdata['social_worker_surname']);
        $data['landline'] = strip_tags($postdata['landline']);
        $data['mobile'] = strip_tags($postdata['mobile']);
        $data['other'] = strip_tags($postdata['other']);
        $data['email'] = strip_tags($postdata['email']);
        $data['senior_social_worker_firstname'] = strip_tags($postdata['senior_social_worker_firstname']);
        $data['senior_social_worker_surname'] = strip_tags($postdata['senior_social_worker_surname']);

        $where = array('yp_id' => $id);
        if (empty($socialRecord)) {
            $data['created_date'] = datetimeformat();
            $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            $data['yp_id'] = $this->input->post('yp_id');
            $success = $this->common_model->insert(SOCIAL_WORKER_DETAILS, $data);
            //Insert log activity
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($postdata['yp_id']) ? $postdata['yp_id'] : '',
                'module_name' => SOCIAL_WORKER_DETAILS_YP,
                'module_field_name' => '',
                'type' => 1
            );
            log_activity($activity);
        } else {
            $data['modified_date'] = datetimeformat();
            $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            $success = $this->common_model->update(SOCIAL_WORKER_DETAILS, $data, $where);
            //Insert log activity
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($postdata['yp_id']) ? $postdata['yp_id'] : '',
                'module_name' => SOCIAL_WORKER_DETAILS_YP,
                'module_field_name' => '',
                'type' => 2
            );
            log_activity($activity);
        }
        $data['crnt_view'] = $this->viewname;
        if ($success) {
            $msg = $this->lang->line('yp_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {    // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }
        redirect('YoungPerson/view/' . $id); //Redirect On Listing page
    }

    /*
      @Author : Ritesh rana
      @Desc   : update social Worker Details function 
      @Input    : 
      @Output   : 
      @Date   : 07/03/2017
    */
    public function overviewOfYoungPerson($id) {
        //Get Records From Login Table
        $data['footerJs'][0] = base_url('uploads/assets/js/tinymce/js/tinymce/tinymce.min.js');

        $table = OVERVIEW_OF_YOUNG_PERSON . ' as oyp';
        $match = "oyp.yp_id = " . $id;
        $fields = array("oyp.*");
        $data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
        $data['id'] = $id;
        $data['crnt_view'] = $this->viewname;
        $data['form_action_path'] = $this->viewname . '/updateOverviewOfYoungPerson/' . $id;
        $data['main_content'] = '/overviewofyoungperson';
        $this->load->view('/overviewofyoungperson', $data);
    }

     /*
      @Author : Ritesh rana
      @Desc   : update Over view Of YoungPerson function 
      @Input    : 
      @Output   : 
      @Date   : 07/03/2017
    */
    public function updateOverviewOfYoungPerson() {

        $id = $this->input->post('yp_id');
        $table = OVERVIEW_OF_YOUNG_PERSON . ' as oyp';
        $match = "oyp.yp_id = " . $id;
        $fields = array("oyp.*");
        $socialRecord = $this->common_model->get_records($table, $fields, '', '', $match);
        $pen_portrait = $this->input->post('pen_portrait');
        $data['pen_portrait'] = $pen_portrait;
        $where = array('yp_id' => $id);
        if (empty($socialRecord)) {
            $data['yp_id'] = $this->input->post('yp_id');
            $data['created_date'] = datetimeformat();
            $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            $success = $this->common_model->insert(OVERVIEW_OF_YOUNG_PERSON, $data);
            //Insert log activity
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($id) ? $id : '',
                'module_name' => PEN_PORTRAIT_YP,
                'module_field_name' => '',
                'type' => 1
            );
            log_activity($activity);
        } else {
            $data['modified_date'] = datetimeformat();
            $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            $success = $this->common_model->update(OVERVIEW_OF_YOUNG_PERSON, $data, $where);
            //Insert log activity
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($id) ? $id : '',
                'module_name' => PEN_PORTRAIT_YP,
                'module_field_name' => '',
                'type' => 2
            );
            log_activity($activity);
        }
        $data['crnt_view'] = $this->viewname;
        if ($success) {
            $msg = $this->lang->line('yp_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {    // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }
        redirect('YoungPerson/view/' . $id); //Redirect On Listing page
    }

     /*
      @Author : Ritesh rana
      @Desc   : YoungPerson profile information function 
      @Input    : 
      @Output   : 
      @Date   : 07/03/2017
    */
    public function ProfileInfo($id) {
        //Get Records From Login Table
        $data['footerJs'][0] = base_url('uploads/custom/js/youngperson/youngperson.js');
        $table = YP_DETAILS . ' as yp';
        $match = "yp.yp_id = " . $id;
        $fields = array("yp.profile_img,yp.yp_id");
        $data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
        $data['id'] = $id;
        $data['crnt_view'] = $this->viewname;
        $data['form_action_path'] = $this->viewname . '/updateProfileInfo/' . $id;
        $data['main_content'] = '/profileinfo';
        $this->load->view('/profileinfo', $data);
    }

    /*
      @Author : Ritesh rana
      @Desc   : update YoungPerson profile information function 
      @Input    : 
      @Output   : 
      @Date   : 07/03/2017
    */
    public function updateProfileInfo() {
        $id = $this->input->post('yp_id');
        $upload_dir = $this->config->item('yp_profile_img_url');
        if (!is_dir($upload_dir)) {
            //create directory
            mkdir($upload_dir, 0777, TRUE);
        }
        if (is_dir($upload_dir)) {
            /* image upload code */
            $file_name = $_FILES['fileUpload']['name'];

            $file_array_delete = $this->input->post('deleted_images');
            if (!empty($file_array_delete)) {
                        $files = $_FILES;
                      $FileDataArr = array(); 

                         $input='fileUpload';

    $tmpFile = count($_FILES[$input]['name']);
            foreach ($file_array_delete as $value) {
                 if($_FILES[$input]['name'] == $value){
                 unset($_FILES[$input]['name']);
                 unset($_FILES[$input]['type']);
                 unset($_FILES[$input]['tmp_name']);
                 unset($_FILES[$input]['error']);
                 unset($_FILES[$input]['size']);
                 }        
            }
        }

            if (!empty($_FILES['fileUpload']['name'])) {
                $oldbookimg = $this->input->post('fileUpload'); //new add
                $bgImgPath = $this->config->item('yp_profile_img_url');
                $uploadFile = 'fileUpload';
                $thumb = "thumb";
                $hiddenImage = !empty($oldbookimg) ? $oldbookimg : '';
                $estFIles['profile_img'] = $this->imageupload_model->upload_image($uploadFile, $bgImgPath, $thumb, TRUE, 'gif|jpg|png|jpeg');
                $where = array('yp_id' => $id);
                $sucsses = $this->common_model->update(YP_DETAILS, $estFIles, $where);
                //Insert log activity
                $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($id) ? $id : '',
                    'module_name' => YP_PHOTO_UPDATED,
                    'module_field_name' => '',
                    'type' => 2
                );
                log_activity($activity);
            } else {
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>Please upload image.</div>");
                redirect('YoungPerson/view/' . $id); //Redirect On Listing page
            }
        }
        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>YP Profile Successfully Updated.</div>");
        $data['crnt_view'] = $this->viewname;
        redirect('YoungPerson/view/' . $id); //Redirect On Listing page
    }

    /*
      @Author : ritesh rana
      @Desc   : check Duplicate name
      @Input 	:
      @Output	:
      @Date   : 21/07/2017
     */

    public function isDuplicateEmail() {
        $email = $this->input->post('emailID');
        $user_id = $this->input->post('userID');
        $table = LOGIN . ' as l';
        if (NULL !== $user_id && $user_id != "") {
            $match = "l.is_delete=0 AND l.email = '" . $email . "' and l.login_id <> '" . $user_id . "'";
        } else {
            $match = "l.email = '" . $email . "' AND l.is_delete=0 ";
        }

        $fields = array("l.login_id,l.status");
        $duplicateEmail = $this->common_model->get_records($table, $fields, '', '', $match);
        $count = count($duplicateEmail);
        if (isset($duplicateEmail) && empty($duplicateEmail) && $count == 0) {
            echo "true";
        } else {
            echo "false";
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : check Duplicate initials
      @Input 	:
      @Output	:
      @Date   : 22/09/2017
     */

    public function isDuplicateInitials() {
        /* function updated by Dhara Bhalala for initial changes */
        $initialsID = substr($this->input->post('initialsID'),0,3);
        $table = YP_DETAILS . ' as yp';
        $match = "yp.is_deleted= '0' AND yp.yp_initials LIKE '" . $initialsID . "%'";
        $fields = array("yp.yp_id,yp.status");
        $duplicateEmail = $this->common_model->get_records($table, $fields, '', '', $match);
		
        $count = count($duplicateEmail);
        if (isset($duplicateEmail) && empty($duplicateEmail) && $count == 0) {
			$result = 1;
           
        } else {
			$result = 0;
            
        }
		echo $result;
		exit;
    }
    
    /*
      @Author : Ritesh Rana
      @Desc   : this function useing for file upload 
      @Input    :
      @Output   :
      @Date   : 22/09/2017
     */
    public function upload_file($fileext = '') {
        $str = file_get_contents('php://input');
        echo $filename = time() . uniqid() . "." . $fileext;
        file_put_contents($this->config->item('yp_profile_img_url') . '/' . $filename, $str);
    }

    /*
      @Author : Ritesh Rana
      @Desc   : this function useing for form validation
      @Input  :
      @Output :
      @Date   : 20/03/2017
     */
    public function formValidation($id = null) {
        $this->form_validation->set_rules('fname', 'Firstname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('lname', 'Lastname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        if (empty($id)) {
            $this->form_validation->set_rules('initials', 'initials', 'trim|required|xss_clean');
        }
    }

    /*
     @Author : Ritesh rana
     @Desc   : past care home Delete Query
     @Input     : Post id from List page
     @Output    : Delete data from database and redirect
     @Date   : 26/03/2018
    */
    public function delete_past_carehome($id,$yp_id) {
            //Delete Record From Database
            if (!empty($id)) {
                                $data = array('is_delete' => '1');
                                $where = array('id' => $id);
                                $success = $this->common_model->update(PAST_CARE_HOME_INFO, $data, $where);
                                if ($success) {
                                    //Insert log activity
                                $activity = array(
                                  'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
                                  'module_name'         => PAST_CARE_HOME,
                                  'module_field_name'   => '',
                                  'yp_id' => !empty($yp_id) ? $yp_id : '',
                                  'type'                => 3
                                );
                                
                                log_activity($activity);
                    $msg = $this->lang->line('Deleted_past_carehome_Successfully');
                    $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                    unset($id);
                    
                } else {
                    // error
                    $msg = $this->lang->line('error_msg');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                    
                }
            }
        
        redirect('YoungPerson/view/'.$yp_id);
    }

    
}
