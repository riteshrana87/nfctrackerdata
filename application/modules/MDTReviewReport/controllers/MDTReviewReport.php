<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MDTReviewReport extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->viewname = $this->router->fetch_class ();
        $this->method   = $this->router->fetch_method();
		$this->load->library(array('form_validation', 'Session','m_pdf'));
        $this->perPage = NO_OF_RECORDS_PER_PAGE; // Number of records per page.
    }
    /*
      @Author : Niral Patel
      @Desc   : MDT Report LIst Page
      @Input  :
      @Output :
      @Date   : 26/03/2018
     */

    public function index($ypid,$careHomeId=0,$isArchive=0) {
        /*for care to care data by ghelani nikunj on 18/9/2018 for care to  care data get with the all previous care home*/
        if ($isArchive !== 0) {
            $temp = $this->common_model->get_records(PAST_CARE_HOME_INFO, array('move_date'), '', '', array("yp_id" => $ypid, "past_carehome" => $careHomeId));
            $data_care_home_detail = $this->common_model->get_records(PAST_CARE_HOME_INFO, array("*"), '', '', array("yp_id" => $ypid, "move_date <= " => $temp[0]['move_date']));
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
            $perpage = $this->perPage;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('mdt_report_data');
            }

            $searchsort_session = $this->session->userdata('mdt_report_data');
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
					if($isArchive==0){
						$sortfield = 'mdt_report_id';
					}
					else{
						$sortfield = 'mdt_report_id';
					}
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
                    $config['per_page'] = $this->perPage;
                    $data['perpage'] = $this->perPage;
                }
            }
            //pagination configuration
            $config['first_link'] = 'First';
            if($isArchive==0){
                //need to change when client will approved functionality
            $config['base_url'] = base_url() . $this->viewname . '/index/' . $ypid;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 4;
                $uri_segment = $this->uri->segment(4);
            }
            }else{
                
                $config['base_url'] = base_url() . $this->viewname . '/index/' . $ypid.'/'.$careHomeId.'/'.$isArchive;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
				
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
				
                $config['uri_segment'] = 6;
				
                $uri_segment = $this->uri->segment(6);
            }
                
               
            }
            //Query
			$login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
			$table = MDT_REPORT . ' as m';                        
			if($isArchive==0){
				$where = array("m.yp_id" => $ypid);
				$fields = array("c.care_home_name,m.care_home_id,m.mdt_report_id,m.yp_id,m.created_date,m.report_start_date,m.report_end_date,CONCAT(`firstname`,' ', `lastname`) as create_name,m.draft,count(ms.mdt_report_id) as sent_report");
				$join_tables = array(LOGIN . ' as l' => 'l.login_id= m.created_by',MDT_SIGNOFF_DETAILS . ' as ms' => 'ms.mdt_report_id= m.mdt_report_id',CARE_HOME . ' as c' => 'c.care_home_id= m.care_home_id');
				
				if (!empty($searchtext)) {
                
				} else {
					$data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, 'm.mdt_report_id', $where);
				
					$config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, 'm.mdt_report_id', $where, '', '', '1');
				}
			}else{
				
                $where = array("m.yp_id"=>$ypid,"m.care_home_id!="=>$data['YP_details'][0]['care_home']);
				$where_date="m.created_date BETWEEN  '".$created_date."' AND '".$movedate."'";
				$fields = array("c.care_home_name,m.*,CONCAT(`firstname`,' ', `lastname`) as create_name");
				$join_tables = array(LOGIN . ' as l' => 'l.login_id= m.created_by',CARE_HOME . ' as c' => 'c.care_home_id= m.care_home_id');
				
				if (!empty($searchtext)) {
					
				} else {
					$data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], '', $sortfield, $sortby, '', $where,'','','','','',$where_date);
					
					$config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1','','',$where_date);
					
				}
				
				
			}
           

            $data['ypid'] = $ypid;
            $data['is_archive_page'] = $isArchive;
            $data['careHomeId'] = $careHomeId;
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

            $this->session->set_userdata('mdt_report_data', $sortsearchpage_data);
            setActiveSession('mdt_report_data'); // set current Session active
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['crnt_view'] = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/mdtreporthome/mdtreporthome.js');
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
      @Desc   : Check Report
      @Input  :
      @Output :
      @Date   : 26/03/2018
     */

    public function checkReport() {
        $postData = $this->input->post();

        //get daily observation data
        $table       = MDT_REPORT . ' as m';
        $where       = array("m.report_start_date" => $postData['report_start_date'],"m.report_end_date" => $postData['report_end_date']);
        $fields      = array("m.mdt_report_id,m.yp_id,m.report_start_date,m.report_end_date");
        $information = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
        //insert data of do
        $insertdata = array(
          'yp_id'              => $postData['yp_id'],
          'report_start_date'  => dateformat($postData['report_start_date']),
          'report_end_date'    => dateformat($postData['report_end_date']),
          'created_by'         => !empty($this->session->userdata('LOGGED_IN')['ID']) ? $this->session->userdata('LOGGED_IN')['ID'] : '',
          'created_date'       => datetimeformat()
        );

        $data['mdt_report_id'] = $this->common_model->insert(MDT_REPORT, $insertdata);
        $data['is_created'] = true;
        //Insert log activity
        $activity = array(
          'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
          'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
          'module_name' => MDT_MODULE,/*updated by Dhara Bhalala as naming conflicts bug on mantis tick. 9924*/
          'module_field_name' => '',
          'type' => 1
        );
        log_activity($activity);

        $data['ypid'] = $postData['yp_id'];
        $data['report_start_date'] = $postData['report_start_date'];
        $data['report_end_date'] = $postData['report_end_date'];
        $data['main_content'] = '/verify_create_mdt';
        $data['footerJs'][0] = base_url('uploads/custom/js/mdtreporthome/mdtreporthome.js');
        $data['header'] = array('menu_module' => 'YoungPerson');
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
    /*
      @Author : Niral Patel
      @Desc   : View report
      @Input 	: yp id
      @Output	:
      @Date   : 27/03/2018
     */

    public function view($id,$ypid,$careHomeId=0,$isArchive=0) {
      if(is_numeric($id) && is_numeric($ypid))
      {
         //get pp form
         $match = array('mdt_form_id'=> 1);
         $formsdata = $this->common_model->get_records(MDT_FORM,'', '', '', $match);
         if(!empty($formsdata))
         {
              $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
         }
          //get YP information
          $table = YP_DETAILS . ' as yp';
          $match = "yp.yp_id = " . $ypid;
          $fields = array("yp.care_home,yp.yp_fname,yp.yp_lname,yp.date_of_birth,pa.authority,swd.social_worker_firstname,swd.social_worker_surname");
          $join_tables = array(PLACING_AUTHORITY . ' as pa' => 'pa.yp_id= yp.yp_id', SOCIAL_WORKER_DETAILS . ' as swd' => 'swd.yp_id= yp.yp_id', OVERVIEW_OF_YOUNG_PERSON . ' as oyp' => 'oyp.yp_id= yp.yp_id');

          $data['YP_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$ypid);
          }
          
          //get report data
          $match_report = array('mdt_report_id'=>$id,'yp_id'=> $ypid,'is_previous_version'=>0);
          $join_tables_report = array(LOGIN . ' as l' => 'l.login_id= m.case_manager');
          $fields_report = array("m.*,l.login_id,concat(l.firstname,' ',l.lastname) as case_manager_name");
          $data['edit_data'] = $this->common_model->get_records(MDT_REPORT.' as m',$fields_report,$join_tables_report, 'left', $match_report);
          
          //do average day
          $table_do = DAILY_OBSERVATIONS . ' as do';
          $where_do = array("do.yp_id" => $ypid,"do.daily_observation_date >=" =>$data['edit_data'][0]['report_start_date'],"do.daily_observation_date <=" =>$data['edit_data'][0]['report_end_date']);
          $fields_do = array("do.do_id,do.yp_id,do.daily_observation_date,d.day");
          $join_tables_do = array(DO_FOODCONSUMED . ' as d' => 'd.do_id= do.do_id');
          $dodata = $this->common_model->get_records($table_do, $fields_do, $join_tables_do, 'left', $where_do,'','','','','','d.do_id');
          if(!empty($dodata))
          {
            $sum = 0;
            foreach ($dodata as $row) {
              $sum +=$row['day'];
            }
            $diet_avg = round($sum/count($dodata));
          }
          $data['diet_avg'] = !empty($diet_avg)?$diet_avg:'0';
        /*MDT_CARE_PLAN_TARGET*/
        $table_cpt = MDT_CARE_PLAN_TARGET . ' as mdt';
        $match_cpt = array('mdt.mdt_report_id'=> $id);
        $fields_cpt = array("mdt.cpt_id,mdt.care_plan_target_title,mdt.care_plan_target_select,mdt.care_plan_target_reason");
        $data['care_plan_target'] = $this->common_model->get_records($table_cpt, $fields_cpt, '', 'left', $match_cpt); 
        /*end MDT_CARE_PLAN_TARGET*/
         /*MDT_REGULAR_HOBBIES*/
        $table_rb = MDT_REGULAR_HOBBIES . ' as mdt';
        $match_rb = array('mdt_report_id'=>$id,'mdt.yp_id'=> $ypid);
        $fields_rb = array("mdt.regular_hobby_id,mdt.regular_hobbies,mdt.regular_hobbies_duration");
        $data['hobbies_data'] = $this->common_model->get_records($table_rb, $fields_rb, '', 'left', $match_rb); 
        /*end MDT_REGULAR_HOBBIES*/
         /*MDT_PHYSICAL_EXERCISE*/
        $table_pe = MDT_PHYSICAL_EXERCISE . ' as mdt';
        $match_pe = array('mdt_report_id'=>$id,'mdt.yp_id'=> $ypid);
        $fields_pe = array("mdt.physical_exercise_id,mdt.physical_exercise,mdt.physical_exercise_duration");
        $data['physical_exercise_data'] = $this->common_model->get_records($table_pe, $fields_pe, '', 'left', $match_pe); 
        /*end MDT_PHYSICAL_EXERCISE*/
         /*MDT_INCIDENT*/
        $table_in = MDT_INCIDENT . ' as mdt';
        $match_in = array('mdt_report_id'=>$id,'mdt.yp_id'=> $ypid);
        $fields_in = array("mdt.incident_summary,mdt.level,mdt.incident_id");
        $data['incident_data'] = $this->common_model->get_records($table_in, $fields_in, '', 'left', $match_in); 
        /*end MDT_INCIDENT*/
         /*MDT_INCIDENT Level*/
        $table_in = MDT_INCIDENT . ' as mdt';
        $match_in = array('mdt_report_id'=>$id,'mdt.yp_id'=> $ypid);
        $fields_in = array("SUM(CASE WHEN mdt.level = 1 THEN 1 ELSE 0 END) as level1,SUM(CASE WHEN mdt.level = 2 THEN 1 ELSE 0 END) as level2,SUM(CASE WHEN mdt.level = 3 THEN 1 ELSE 0 END) as level3,SUM(CASE WHEN mdt.level = 4 THEN 1 ELSE 0 END) as level4");
        $join_tables_in = array(LOGIN . ' as l' => 'l.login_id= mdt.staff_initials');
        $data['incident_level'] = $this->common_model->get_records($table_in, $fields_in, $join_tables_in, 'left', $match_in,'','','','','','mdt.mdt_report_id'); 
        /*end MDT_INCIDENT*/
         /*MDT_SANCTION*/
        $table_sa = MDT_SANCTION . ' as mdt';
        $match_sa = array('mdt_report_id'=>$id,'mdt.yp_id'=> $ypid);
        $fields_sa = array("mdt.sanction_id,mdt.reason_sanction,mdt.imposed_sanction,mdt.date_sanction");
        $data['sanction_data'] = $this->common_model->get_records($table_sa, $fields_sa, '', 'left', $match_sa); 
        /*end MDT_SANCTION*/
        /*MDT_LIFE_SKILLS*/
        $table_ls = MDT_LIFE_SKILLS . ' as mdt';
        $match_ls = array('mdt_report_id'=>$id,'mdt.yp_id'=> $ypid);
        $fields_ls = array("mdt.life_skills_id,mdt.area_of_development,mdt.progress_achieved");
        $data['life_skills_data'] = $this->common_model->get_records($table_ls, $fields_ls, '', 'left', $match_ls); 
        /*end MDT_LIFE_SKILLS*/
        /*MDT_CARE_PLAN_TARGET_WEEK*/
        $table = MDT_CARE_PLAN_TARGET_WEEK . ' as mdt';
        $match = array('mdt.mdt_report_id'=> $id);
        $fields = array("mdt.cpt_week_id,mdt.care_plan_target_title,mdt.care_plan_target_select,mdt.care_plan_target_reason");
        $data['care_plan_target_week'] = $this->common_model->get_records($table, $fields, '', 'left', $match); 
        /*end MDT_CARE_PLAN_TARGET_WEEK*/
        /*MDT_CARE_PLAN_TARGET_PREVIOUS*/
        $table = MDT_CARE_PLAN_TARGET_PREVIOUS . ' as mdt';
        $match = array('mdt.mdt_report_id'=> $id);
        $fields = array("mdt.cpt_previous_id,mdt.care_plan_target_title,mdt.care_plan_target_select,mdt.care_plan_target_reason");
        $data['care_plan_target_previous'] = $this->common_model->get_records($table, $fields, '', 'left', $match); 
        /*end MDT_CARE_PLAN_TARGET_PREVIOUS*/
         /*medical approintment query start*/
        $table_ap = MEDICAL_PROFESSIONALS_APPOINTMENT . ' as mc';
        
        //$where_ap = 'mc.yp_id = '.$ypid.' AND mc.is_delete = "0" ';
        $where_ap = array("mc.yp_id" => $ypid,"mc.is_delete"=>0,"mc.appointment_date >=" =>$data['edit_data'][0]['report_start_date'],"mc.appointment_date <=" =>$data['edit_data'][0]['report_end_date']);
        $fields_ap = array("mc.*,concat(if(mp.title != '',mp.title,''),' ',if(mp.first_name != '',mp.first_name,''),' ',if(mp.surname != '',mp.surname,''),' - ',if(mp.professional != '',mp.professional,'')) as mp_name, concat(mp.house_namenumber,',',mp.street,' ',mp.town,',',mp.city,',',mp.postcode) as address");
        $join_tables_ap = array(MEDICAL_PROFESSIONALS . ' as mp' => 'mc.mp_id= mp.mp_id',YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id');
        $data['appointments'] = $this->common_model->get_records($table_ap, $fields_ap, $join_tables_ap, 'left', $where_ap, '', '', '', 'appointment_id', 'desc', '');
        /*end query medical appointment*/
        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table_rs = MDT_REPORT_SIGNOFF.' as ims';
        $where_rs = array("l.is_delete"=> "0","ims.yp_id" => $ypid,"ims.is_delete"=> "0","ims.mdt_report_id"=>$id);
        $fields_rs = array("ims.created_by,ims.created_date,ims.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables_rs = array(LOGIN . ' as l' => 'l.login_id=ims.created_by');
        $group_by = array('created_by');
        $data['ra_signoff_data'] = $this->common_model->get_records($table_rs,$fields_rs,$join_tables_rs,'left','','','','','','',$group_by,$where_rs);


        $table_si = MDT_REPORT_SIGNOFF.' as ims';
        $where_si = array("ims.yp_id" => $ypid,"ims.created_by" => $login_user_id,"ims.is_delete"=> "0","ims.mdt_report_id"=>$id);
        $fields_si = array("ims.*");  
        $data['check_signoff_data'] = $this->common_model->get_records($table_si,$fields_si,'','','','','','','','','',$where_si);
        
        //get external approve
        $table = MDT_SIGNOFF_DETAILS;
        $fields = array('*');
        $where = array('mdt_report_id' => $id, 'yp_id' => $ypid);
        $data['check_external_signoff_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

          //get ra old yp data
          $match = array('m.mdt_report_id'=>$id,'m.yp_id'=> $ypid,'m.status'=>1);
          $join_tables_report = array(LOGIN . ' as l' => 'l.login_id= m.case_manager');
          $fields_report = array("m.*,l.login_id,concat(l.firstname,' ',l.lastname) as case_manager_name");
          $data['prev_edit_data'] = $this->common_model->get_records(MDT_REPORT_ARCHIVE.' as m',$fields_report, $join_tables_report, 'left', $match,'','1','','mdt_archive_id','desc');
          
          if(!empty($data['prev_edit_data']))
          {
            $mdt_archive_id = $data['prev_edit_data'][0]['mdt_archive_id'];
            //get MDT_CARE_PLAN_TARGET_ARCHIVE 
            $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
            $data['cpt_item_archive'] = $this->common_model->get_records(MDT_CARE_PLAN_TARGET_ARCHIVE, '', '', '', $where1, '','','','','','','');
            
            //get MDT_REGULAR_HOBBIES_ARCHIVE 
            $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
            $data['hobbies_item_archive'] = $this->common_model->get_records(MDT_REGULAR_HOBBIES_ARCHIVE, '', '', '', $where1, '','','','','','','');
            //get MDT_PHYSICAL_EXERCISE_ARCHIVE 
            $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
            $data['exercise_item_archive'] = $this->common_model->get_records(MDT_PHYSICAL_EXERCISE_ARCHIVE, '', '', '', $where1, '','','','','','','');
            //get MDT_INCIDENT_ARCHIVE 
            $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
            $data['incident_item_archive'] = $this->common_model->get_records(MDT_INCIDENT_ARCHIVE, '', '', '', $where1, '','','','','','','');
            //get MDT_SANCTION_ARCHIVE 
            $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
            $data['sanction_item_archive'] = $this->common_model->get_records(MDT_SANCTION_ARCHIVE, '', '', '', $where1, '','','','','','','');
            //get MDT_LIFE_SKILLS_ARCHIVE 
            $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
            $data['likeskills_item_archive'] = $this->common_model->get_records(MDT_LIFE_SKILLS_ARCHIVE, '', '', '', $where1, '','','','','','','');
             //get MDT_CARE_PLAN_TARGET_WEEK_ARCHIVE 
            $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
            $data['cpt_item_week_archive'] = $this->common_model->get_records(MDT_CARE_PLAN_TARGET_WEEK_ARCHIVE, '', '', '', $where1, '','','','','','','');
            //get MDT_CARE_PLAN_TARGET_PREVIOUS_ARCHIVE 
            $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
            $data['cpt_item_previous_archive'] = $this->common_model->get_records(MDT_CARE_PLAN_TARGET_PREVIOUS_ARCHIVE, '', '', '', $where1, '','','','','','','');
          }

          $data['ypid'] = $ypid;
          $data['is_archive_page'] = $isArchive;
          $data['careHomeId'] = $careHomeId;
          $data['mdt_report_id'] = $id;
          $data['header'] = array('menu_module' => 'YoungPerson');
          $data['footerJs'][0] = base_url('uploads/custom/js/MDTReport/MDTReport.js');
          $data['crnt_view'] = $this->viewname;
          $data['main_content'] = '/view';
          $this->parser->parse('layouts/DefaultTemplate', $data);
        }
        else
        {
            show_404 ();
        }
    }
    /*
      @Author : Niral Patel
      @Desc   : Add page
      @Input  :
      @Output :
      @Date   : 29/03/2018
     */

    public function add($ypid) {
      if(is_numeric($ypid))
      {
        //get pp form
        $match = array('mdt_form_id'=> 1);
        $pp_forms = $this->common_model->get_records(MDT_FORM,array("form_json_data"), '', '', $match);
        $form_field = array();
        if(!empty($pp_forms))
        {
            $data['form_data'] = json_decode($pp_forms[0]['form_json_data'], TRUE);
            foreach ($data['form_data'] as $form_data) {
                         $form_field[] = $form_data['name'];
            }
        }
        $data['form_field_data_name'] = $form_field;
        //get YP information
        $table = YP_DETAILS . ' as yp';
        $match = "yp.yp_id = " . $ypid;
        $fields = array("yp.*,pa.authority,pa.address_1,pa.address_2,pa.town,pa.county,pa.postcode,swd.social_worker_firstname,swd.social_worker_surname,swd.landline,swd.mobile,swd.email,swd.senior_social_worker_firstname,swd.senior_social_worker_surname,oyp.pen_portrait,swd.other");
        $join_tables = array(PLACING_AUTHORITY . ' as pa' => 'pa.yp_id= yp.yp_id', SOCIAL_WORKER_DETAILS . ' as swd' => 'swd.yp_id= yp.yp_id', OVERVIEW_OF_YOUNG_PERSON . ' as oyp' => 'oyp.yp_id= yp.yp_id');
        $data['YP_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);

        if(empty($data['YP_details']))
        {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson/view/'.$ypid);
        }
         //get YP information
        $table = LOGIN . ' as l';
        $match = array('role_id !=' => '1','is_delete'=>0);
        $fields = array("l.login_id,concat(l.firstname,' ',l.lastname) as name");
        $data['case_managers'] = $this->common_model->get_records($table, $fields, '', '', $match);

        $data['diet_avg'] = '5';
        
        
        $url_data =  base_url('MDTReviewReport/add/'.$ypid);
        $match = array('url_data'=>$url_data);
        $data['check_edit_permission'] = $this->common_model->get_records(CHECK_EDIT_URL,'*', '', '', $match);
        if(count($data['check_edit_permission']) > 0){ 
        $in_time = date('Y-m-d h:i:s',strtotime($data['check_edit_permission'][0]['datetime']));
        $currnt_time = date('Y-m-d h:i:s');
        if(strtotime($in_time)>strtotime($currnt_time))
         {
            $now = strtotime($in_time) - strtotime($currnt_time);        
         } 
          else
         {
            $now = strtotime($currnt_time) - strtotime($in_time);
         }
        //  die;
         $secs = floor($now % 60);
        
          if($secs >= 10)
        {
          $data['ypid'] = $ypid;
          $data['footerJs'][0] = base_url('uploads/custom/js/MDTReport/MDTReport.js');
          $data['header'] = array('menu_module' => 'YoungPerson');
          $data['crnt_view'] = $this->viewname;
          $data['main_content'] = '/edit';
          $this->parser->parse('layouts/DefaultTemplate', $data);
        }
        else
        {
          $msg = $this->lang->line('check_is_user_update_data');
          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
          redirect('/' . $this->viewname .'/view/'.$id.'/'.$ypid);
        }
      }else{
                $data['ypid'] = $ypid;
                $data['footerJs'][0] = base_url('uploads/custom/js/MDTReport/MDTReport.js');
                $data['header'] = array('menu_module' => 'YoungPerson');
                $data['crnt_view'] = $this->viewname;
                $data['main_content'] = '/edit';
                $this->parser->parse('layouts/DefaultTemplate', $data);
      }
       
      }
      else
      {
          show_404 ();
      }
    }
    /*
      @Author : Niral Patel
      @Desc   : Edit page
      @Input 	:
      @Output	:
      @Date   : 27/03/2018
     */

    public function edit($id,$ypid) {
      if(is_numeric($id) && is_numeric($ypid))
      {
        //get pp form
        $match = array('mdt_form_id'=> 1);
        $pp_forms = $this->common_model->get_records(MDT_FORM,array("form_json_data"), '', '', $match);
        if(!empty($pp_forms))
        {
            $data['form_data'] = json_decode($pp_forms[0]['form_json_data'], TRUE);
        }
        //get YP information
        $table = YP_DETAILS . ' as yp';
        $match = "yp.yp_id = " . $ypid;
        $fields = array("yp.care_home,yp.yp_fname,yp.yp_lname,yp.date_of_birth,pa.authority,pa.address_1,pa.address_2,pa.town,pa.county,pa.postcode,swd.social_worker_firstname,swd.social_worker_surname,swd.landline,swd.mobile,swd.email,swd.senior_social_worker_firstname,swd.senior_social_worker_surname");
        $join_tables = array(PLACING_AUTHORITY . ' as pa' => 'pa.yp_id= yp.yp_id', SOCIAL_WORKER_DETAILS . ' as swd' => 'swd.yp_id= yp.yp_id', OVERVIEW_OF_YOUNG_PERSON . ' as oyp' => 'oyp.yp_id= yp.yp_id');
        $data['YP_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
        if(empty($data['YP_details']))
        {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson/view/'.$ypid);
        }
         //get USER information
        $table_lo = LOGIN . ' as l';
        $match_lo = array('role_id !=' => '1','is_delete'=>0);
        $fields_lo = array("l.login_id,concat(l.firstname,' ',l.lastname) as name");
        $data['case_managers'] = $this->common_model->get_records($table_lo, $fields_lo, '', '', $match_lo);
        //get MDT yp data
        $match_re = array('mdt_report_id'=>$id,'yp_id'=> $ypid,'is_previous_version'=>0);
        $data['edit_data'] = $this->common_model->get_records(MDT_REPORT,'', '', '', $match_re);

        //do average day
        $table_do = DAILY_OBSERVATIONS . ' as do';
        $where_do = array("do.yp_id" => $ypid,"do.daily_observation_date >=" =>$data['edit_data'][0]['report_start_date'],"do.daily_observation_date <=" =>$data['edit_data'][0]['report_end_date']);
        $fields_do = array("do.do_id,do.yp_id,do.daily_observation_date,d.day");
        $join_tables_do = array(DO_FOODCONSUMED . ' as d' => 'd.do_id= do.do_id');
        $dodata = $this->common_model->get_records($table_do, $fields_do, $join_tables_do, 'left', $where_do,'','','','','','d.do_id');
        if(!empty($dodata))
        {
          $sum = 0;
          foreach ($dodata as $row) {
            $sum +=$row['day'];
          }
          $diet_avg = round($sum/count($dodata));
        }
        $data['diet_avg'] = !empty($diet_avg)?$diet_avg:'0';
        /*MDT_CARE_PLAN_TARGET*/
        $table_cpt = MDT_CARE_PLAN_TARGET . ' as mdt';
        $match_cpt = array('mdt.mdt_report_id'=> $id);
        $fields_cpt = array("mdt.cpt_id,mdt.care_plan_target_title,mdt.care_plan_target_select,mdt.care_plan_target_reason");
        $data['care_plan_target'] = $this->common_model->get_records($table_cpt, $fields_cpt, '', 'left', $match_cpt); 
        /*end MDT_CARE_PLAN_TARGET*/
         /*MDT_REGULAR_HOBBIES*/
        $table_rb = MDT_REGULAR_HOBBIES . ' as mdt';
        $match_rb = array('mdt_report_id'=>$id,'mdt.yp_id'=> $ypid);
        $fields_rb = array("mdt.regular_hobby_id,mdt.regular_hobbies,mdt.regular_hobbies_duration");
        $data['hobbies_data'] = $this->common_model->get_records($table_rb, $fields_rb, '', 'left', $match_rb);
        /*end MDT_REGULAR_HOBBIES*/
         /*MDT_PHYSICAL_EXERCISE*/
        $table_pe = MDT_PHYSICAL_EXERCISE . ' as mdt';
        $match_pe = array('mdt_report_id'=>$id,'mdt.yp_id'=> $ypid);
        $fields_pe = array("mdt.physical_exercise_id,mdt.physical_exercise,mdt.physical_exercise_duration");
        $data['physical_exercise_data'] = $this->common_model->get_records($table_pe, $fields_pe, '', 'left', $match_pe); 
        /*end MDT_PHYSICAL_EXERCISE*/
         /*MDT_INCIDENT*/
        $table_in = MDT_INCIDENT . ' as mdt';
        $match_in = array('mdt_report_id'=>$id,'mdt.yp_id'=> $ypid);
        $fields_in = array("mdt.incident_summary,mdt.level,mdt.incident_id");
        $data['incident_data'] = $this->common_model->get_records($table_in, $fields_in, '', 'left', $match_in); 
         /*MDT_INCIDENT Level*/
        $table_in = MDT_INCIDENT . ' as mdt';
        $match_in = array('mdt_report_id'=>$id,'mdt.yp_id'=> $ypid);
        $fields_in = array("SUM(CASE WHEN mdt.level = 1 THEN 1 ELSE 0 END) as level1,SUM(CASE WHEN mdt.level = 2 THEN 1 ELSE 0 END) as level2,SUM(CASE WHEN mdt.level = 3 THEN 1 ELSE 0 END) as level3,SUM(CASE WHEN mdt.level = 4 THEN 1 ELSE 0 END) as level4");
        $join_tables_in = array(LOGIN . ' as l' => 'l.login_id= mdt.staff_initials');
        $data['incident_level'] = $this->common_model->get_records($table_in, $fields_in, $join_tables_in, 'left', $match_in,'','','','','','mdt.mdt_report_id'); 
        /*end MDT_INCIDENT*/
         /*MDT_SANCTION*/
        $table_sa = MDT_SANCTION . ' as mdt';
        $match_sa = array('mdt_report_id'=>$id,'mdt.yp_id'=> $ypid);
        $fields_sa = array("mdt.sanction_id,mdt.reason_sanction,mdt.imposed_sanction,mdt.date_sanction");
        $data['sanction_data'] = $this->common_model->get_records($table_sa, $fields_sa, '', 'left', $match_sa); 
        /*end MDT_SANCTION*/
        /*MDT_LIFE_SKILLS*/
        $table_ls = MDT_LIFE_SKILLS . ' as mdt';
        $match_ls = array('mdt_report_id'=>$id,'mdt.yp_id'=> $ypid);
        $fields_ls = array("mdt.life_skills_id,mdt.area_of_development,mdt.progress_achieved");
        $data['life_skills_data'] = $this->common_model->get_records($table_ls, $fields_ls, '', 'left', $match_ls); 
        
        /*end MDT_LIFE_SKILLS*/
         /*MDT_CARE_PLAN_TARGET_WEEK*/
        $table = MDT_CARE_PLAN_TARGET_WEEK . ' as mdt';
        $match = array('mdt.mdt_report_id'=> $id);
        $fields = array("mdt.cpt_week_id,mdt.care_plan_target_title,mdt.care_plan_target_select,mdt.care_plan_target_reason");
        $data['care_plan_target_week'] = $this->common_model->get_records($table, $fields, '', 'left', $match); 
        /*end MDT_CARE_PLAN_TARGET_WEEK*/
        /*MDT_CARE_PLAN_TARGET_PREVIOUS*/
        $table = MDT_CARE_PLAN_TARGET_PREVIOUS . ' as mdt';
        $match = array('mdt.mdt_report_id'=> $id);
        $fields = array("mdt.cpt_previous_id,mdt.care_plan_target_title,mdt.care_plan_target_select,mdt.care_plan_target_reason");
        $data['care_plan_target_previous'] = $this->common_model->get_records($table, $fields, '', 'left', $match); 
        /*end MDT_CARE_PLAN_TARGET_PREVIOUS*/
        /*medical approintment query start*/
        $table_ap = MEDICAL_PROFESSIONALS_APPOINTMENT . ' as mc';
        $where_ap = array("mc.yp_id" => $ypid,"mc.is_delete"=>0,"mc.appointment_date >=" =>$data['edit_data'][0]['report_start_date'],"mc.appointment_date <=" =>$data['edit_data'][0]['report_end_date']);
        $fields_ap = array("mc.*,concat(if(mp.title != '',mp.title,''),' ',if(mp.first_name != '',mp.first_name,''),' ',if(mp.surname != '',mp.surname,''),' - ',if(mp.professional != '',mp.professional,'')) as mp_name, concat(mp.house_namenumber,',',mp.street,' ',mp.town,',',mp.city,',',mp.postcode) as address");
        $join_tables_ap = array(MEDICAL_PROFESSIONALS . ' as mp' => 'mc.mp_id= mp.mp_id',YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id');
        $data['appointments'] = $this->common_model->get_records($table_ap, $fields_ap, $join_tables_ap, 'left', $where_ap, '', '', '', 'appointment_id', 'desc', '');
        /*end query medical appointment*/
        $url_data =  base_url('MDTReviewReport/edit/'.$id.'/'.$ypid);
        $match = array('url_data'=>$url_data);
        $data['check_edit_permission'] = $this->common_model->get_records(CHECK_EDIT_URL,'*', '', '', $match);
        if(count($data['check_edit_permission']) > 0){ 
        $in_time = date('Y-m-d h:i:s',strtotime($data['check_edit_permission'][0]['datetime']));
        $currnt_time = date('Y-m-d h:i:s');
        if(strtotime($in_time)>strtotime($currnt_time))
         {
            $now = strtotime($in_time) - strtotime($currnt_time);        
         } 
          else
         {
            $now = strtotime($currnt_time) - strtotime($in_time);
         }
        //  die;
         $secs = floor($now % 60);
        
          if($secs >= 10)
        {
          $data['ypid'] = $ypid;
          $data['mdt_report_id'] = $id;
          $data['footerJs'][0] = base_url('uploads/custom/js/MDTReport/MDTReport.js');
          $data['header'] = array('menu_module' => 'YoungPerson');
          $data['crnt_view'] = $this->viewname;
          $data['main_content'] = '/edit';
          $this->parser->parse('layouts/DefaultTemplate', $data);
        }
        else
        {
          $msg = $this->lang->line('check_is_user_update_data');
          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
          redirect('/' . $this->viewname .'/view/'.$id.'/'.$ypid);
        }
      }else{
                $data['ypid'] = $ypid;
                $data['footerJs'][0] = base_url('uploads/custom/js/MDTReport/MDTReport.js');
                $data['header'] = array('menu_module' => 'YoungPerson');
                $data['crnt_view'] = $this->viewname;
                $data['main_content'] = '/edit';
                $this->parser->parse('layouts/DefaultTemplate', $data);
      }
       
      }
      else
      {
          show_404 ();
      }
    }
    /*
      @Author : Niral Patel
      @Desc   : Edit page
      @Input  :
      @Output :
      @Date   : 27/03/2018
     */

    public function edit_draft($id,$ypid) {
      if(is_numeric($id) && is_numeric($ypid))
      {
        //get pp form
        $match = array('mdt_form_id'=> 1);
        $pp_forms = $this->common_model->get_records(MDT_FORM,'', '', '', $match);
        if(!empty($pp_forms))
        {
            $data['form_data'] = json_decode($pp_forms[0]['form_json_data'], TRUE);
        }
        //get YP information
        $table = YP_DETAILS . ' as yp';
        $match = "yp.yp_id = " . $ypid;
        $fields = array("yp.care_home,yp.yp_fname,yp.yp_lname,yp.date_of_birth,swd.social_worker_firstname,swd.social_worker_surname,pa.authority");
        $join_tables = array(PLACING_AUTHORITY . ' as pa' => 'pa.yp_id= yp.yp_id', SOCIAL_WORKER_DETAILS . ' as swd' => 'swd.yp_id= yp.yp_id', OVERVIEW_OF_YOUNG_PERSON . ' as oyp' => 'oyp.yp_id= yp.yp_id');
        $data['YP_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);

        if(empty($data['YP_details']))
        {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson/view/'.$ypid);
        }
         //get YP information
        $table_lo = LOGIN . ' as l';
        $match_lo = array('role_id !=' => '1','is_delete'=>0);
        $fields_lo = array("l.login_id,concat(l.firstname,' ',l.lastname) as name");
        $data['case_managers'] = $this->common_model->get_records($table_lo, $fields_lo, '', '', $match_lo);
        //get mdt yp data
        $match_re = array('mdt_report_id'=>$id,'yp_id'=> $ypid,'is_previous_version'=>0);
        $data['edit_data'] = $this->common_model->get_records(MDT_REPORT,'', '', '', $match_re);
        //do average day
        $table_do = DAILY_OBSERVATIONS . ' as do';
        $where_do = array("do.yp_id" => $ypid,"do.daily_observation_date >=" =>$data['edit_data'][0]['report_start_date'],"do.daily_observation_date <=" =>$data['edit_data'][0]['report_end_date']);
        $fields_do = array("do.do_id,do.yp_id,do.daily_observation_date,d.day");
        $join_tables_do = array(DO_FOODCONSUMED . ' as d' => 'd.do_id= do.do_id');
        $dodata = $this->common_model->get_records($table_do, $fields_do, $join_tables_do, 'left', $where_do,'','','','','','d.do_id');
        if(!empty($dodata))
        {
          $sum = 0;
          foreach ($dodata as $row) {
            $sum +=$row['day'];
          }
          $diet_avg = round($sum/count($dodata));
        }
        $data['diet_avg'] = !empty($diet_avg)?$diet_avg:'0';
        /*MDT_CARE_PLAN_TARGET*/
        $table_cpt = MDT_CARE_PLAN_TARGET . ' as mdt';
        $match_cpt = array('mdt.mdt_report_id'=> $id);
        $fields_cpt = array("mdt.cpt_id,mdt.care_plan_target_title,mdt.care_plan_target_select,mdt.care_plan_target_reason");
        $data['care_plan_target'] = $this->common_model->get_records($table_cpt, $fields_cpt, '', 'left', $match_cpt);
        /*end MDT_CARE_PLAN_TARGET*/
         /*MDT_REGULAR_HOBBIES*/
        $table_rb = MDT_REGULAR_HOBBIES . ' as mdt';
        $match_rb = array('mdt_report_id'=>$id,'mdt.yp_id'=> $ypid);
        $fields_rb = array("mdt.regular_hobby_id,mdt.regular_hobbies,mdt.regular_hobbies_duration");
        $data['hobbies_data'] = $this->common_model->get_records($table_rb, $fields_rb, '', 'left', $match_rb); 
        /*end MDT_REGULAR_HOBBIES*/
         /*MDT_PHYSICAL_EXERCISE*/
        $table_pe = MDT_PHYSICAL_EXERCISE . ' as mdt';
        $match_pe = array('mdt_report_id'=>$id,'mdt.yp_id'=> $ypid);
        $fields_pe = array("mdt.physical_exercise_id,mdt.physical_exercise,mdt.physical_exercise_duration");
        $data['physical_exercise_data'] = $this->common_model->get_records($table_pe, $fields_pe, '', 'left', $match_pe); 
        /*end MDT_PHYSICAL_EXERCISE*/
         /*MDT_INCIDENT*/
        $table_in = MDT_INCIDENT . ' as mdt';
        $match_in = array('mdt_report_id'=>$id,'mdt.yp_id'=> $ypid);
        $fields_in = array("mdt.incident_summary,mdt.level,mdt.incident_id");
        $data['incident_data'] = $this->common_model->get_records($table_in, $fields_in, '', 'left', $match_in); 
        /*end MDT_INCIDENT*/
         /*MDT_SANCTION*/
        $table_sa = MDT_SANCTION . ' as mdt';
        $match_sa = array('mdt_report_id'=>$id,'mdt.yp_id'=> $ypid);
        $fields_sa = array("mdt.sanction_id,mdt.reason_sanction,mdt.imposed_sanction,mdt.date_sanction");
        $data['sanction_data'] = $this->common_model->get_records($table_sa, $fields_sa, $join_tables_sa, 'left', $match_sa); 
        /*end MDT_SANCTION*/
        /*MDT_LIFE_SKILLS*/
        $table_ls = MDT_LIFE_SKILLS . ' as mdt';
        $match_ls = array('mdt_report_id'=>$id,'mdt.yp_id'=> $ypid);
        $fields_ls = array("mdt.life_skills_id,mdt.area_of_development,mdt.progress_achieved");
        $data['life_skills_data'] = $this->common_model->get_records($table_ls, $fields_ls, '', 'left', $match_ls); 
        /*end MDT_LIFE_SKILLS*/
         /*MDT_CARE_PLAN_TARGET_WEEK*/
        $table = MDT_CARE_PLAN_TARGET_WEEK . ' as mdt';
        $match = array('mdt.mdt_report_id'=> $id);
        $fields = array("mdt.cpt_week_id,mdt.care_plan_target_title,mdt.care_plan_target_select,mdt.care_plan_target_reason");
        $data['care_plan_target_week'] = $this->common_model->get_records($table, $fields, '', 'left', $match); 
        /*end MDT_CARE_PLAN_TARGET_WEEK*/
         /*MDT_CARE_PLAN_TARGET_PREVIOUS*/
        $table = MDT_CARE_PLAN_TARGET_PREVIOUS . ' as mdt';
        $match = array('mdt.mdt_report_id'=> $id);
        $fields = array("mdt.cpt_previous_id,mdt.care_plan_target_title,mdt.care_plan_target_select,mdt.care_plan_target_reason");
        $data['care_plan_target_previous'] = $this->common_model->get_records($table, $fields, '', 'left', $match); 
        /*end MDT_CARE_PLAN_TARGET_PREVIOUS*/
        /*medical approintment query start*/
        if(!empty($data['edit_data'][0]['report_start_date']) && !empty($data['edit_data'][0]['report_end_date']))
        {
          $table_ap = MEDICAL_PROFESSIONALS_APPOINTMENT . ' as mc';
          $where_ap = array("mc.yp_id" => $ypid,"mc.is_delete"=>0,"mc.appointment_date >=" =>$data['edit_data'][0]['report_start_date'],"mc.appointment_date <=" =>$data['edit_data'][0]['report_end_date']);
          $fields_ap = array("mc.*,concat(if(mp.title != '',mp.title,''),' ',if(mp.first_name != '',mp.first_name,''),' ',if(mp.surname != '',mp.surname,''),' - ',if(mp.professional != '',mp.professional,'')) as mp_name, concat(mp.house_namenumber,',',mp.street,' ',mp.town,',',mp.city,',',mp.postcode) as address");
          $join_tables_ap = array(MEDICAL_PROFESSIONALS . ' as mp' => 'mc.mp_id= mp.mp_id',YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id');
          $data['appointments'] = $this->common_model->get_records($table_ap, $fields_ap, $join_tables_ap, 'left', $where_ap, '', '', '', 'appointment_id', 'desc', '');
        }
        /*end query medical appointment*/
        $url_data =  base_url('MDTReviewReport/edit/'.$id.'/'.$ypid);
        $match = array('url_data'=>$url_data);
        $data['check_edit_permission'] = $this->common_model->get_records(CHECK_EDIT_URL,'*', '', '', $match);
        if(count($data['check_edit_permission']) > 0){ 
        $in_time = date('Y-m-d h:i:s',strtotime($data['check_edit_permission'][0]['datetime']));
        $currnt_time = date('Y-m-d h:i:s');
        if(strtotime($in_time)>strtotime($currnt_time))
         {
            $now = strtotime($in_time) - strtotime($currnt_time);        
         } 
          else
         {
            $now = strtotime($currnt_time) - strtotime($in_time);
         }
        //  die;
         $secs = floor($now % 60);
        
          if($secs >= 10)
        {
          $data['ypid'] = $ypid;
          $data['mdt_report_id'] = $id;
          $data['footerJs'][0] = base_url('uploads/custom/js/MDTReport/MDTReport.js');
          $data['header'] = array('menu_module' => 'YoungPerson');
          $data['crnt_view'] = $this->viewname;
          $data['main_content'] = '/edit';
          $this->parser->parse('layouts/DefaultTemplate', $data);
        }
        else
        {
          $msg = $this->lang->line('check_is_user_update_data');
          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
          redirect('/' . $this->viewname .'/view/'.$id.'/'.$ypid);
        }
      }else{
                $data['ypid'] = $ypid;
                $data['footerJs'][0] = base_url('uploads/custom/js/MDTReport/MDTReport.js');
                $data['header'] = array('menu_module' => 'YoungPerson');
                $data['crnt_view'] = $this->viewname;
                $data['main_content'] = '/edit';
                $this->parser->parse('layouts/DefaultTemplate', $data);
      }
       
      }
      else
      {
          show_404 ();
      }
    }
    /*
      @Author : Niral patel
      @Desc   : Insert report
      @Input    :
      @Output   :
      @Date   : 27/03/2018
     */
    public function insert() {
        if (!validateFormSecret()) {
            redirect($_SERVER['HTTP_REFERER']);  //Redirect On Listing page
        }
        $postData = $this->input->post();
        $error = false;
        $postData1 = $this->input->post();
        unset($postData1['yp_id']);
        unset($postData1['draft_data']);
        unset($postData1['mdt_report_id']);
        foreach ($postData1 as $key => $val) {
            if (is_array($val)) {
                if (!empty(trim($val[0]))) {
                    $error = true;
                }
            } else if (!empty(trim($val))) {
                $error = true;
            }
        }
        if (!empty($error)) {
            unset($postData['submit_mdtform']);
            //get pp form
            $match = array('mdt_form_id' => 1);
            $pp_forms = $this->common_model->get_records(MDT_FORM, array("form_json_data"), '', '', $match);
            if (!empty($pp_forms)) {
                $pp_form_data = json_decode($pp_forms[0]['form_json_data'], TRUE);
                $data = array();
                foreach ($pp_form_data as $row) {
                    if (isset($row['name'])) {
                        if ($row['type'] == 'file') {
                            $filename = $row['name'];
                            //get image previous image
                            $match = array('mdt_report_id' => $postData['mdt_report_id'], 'yp_id' => $postData['yp_id'], 'is_previous_version' => 0);
                            $pp_yp_data = $this->common_model->get_records(MDT_REPORT, array('`' . $row['name'] . '`'), '', '', $match);
                            //delete img
                            if (!empty($postData['hidden_' . $row['name']])) {
                                $delete_img = explode(',', $postData['hidden_' . $row['name']]);
                                $db_images = explode(',', $pp_yp_data[0][$filename]);
                                $differentedImage = array_diff($db_images, $delete_img);
                                $pp_yp_data[0][$filename] = !empty($differentedImage) ? implode(',', $differentedImage) : '';
                            }

                            if (!empty($_FILES[$filename]['name'][0])) {
                                /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                createDirectory(array($this->config->item('mdt_base_url'), $this->config->item('mdt_base_big_url'), $this->config->item('mdt_base_big_url') . '/' . $postData['yp_id']));

                                $file_view = $this->config->item('mdt_img_url') . $postData['yp_id'];
                                //upload big image
                                $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/index/' . $postData['yp_id']);
                                //upload small image
                                $insertImagesData = array();
                                if (!empty($upload_data)) {
                                    foreach ($upload_data as $imageFiles) {
                                        /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                        createDirectory(array($this->config->item('mdt_base_small_url'), $this->config->item('mdt_base_small_url') . '/' . $postData['yp_id']));

                                        /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                        if ($imageFiles['is_image'])
                                            $a = do_resize($this->config->item('mdt_img_url') . $postData['yp_id'], $this->config->item('mdt_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                        array_push($insertImagesData, $imageFiles['file_name']);
                                        if (!empty($insertImagesData)) {
                                            $images = implode(',', $insertImagesData);
                                        }
                                    }
                                    if (!empty($pp_yp_data[0][$filename])) {
                                        $images .=',' . $pp_yp_data[0][$filename];
                                    }
                                    $data[$row['name']] = !empty($images) ? $images : '';
                                }
                            } else {
                                $data[$row['name']] = !empty($pp_yp_data[0][$filename]) ? $pp_yp_data[0][$filename] : '';
                            }
                        } else {
                            if ($row['type'] != 'button') {
                            if ($row['type'] == 'checkbox-group') {
                                $data[$row['name']] = !empty($postData[$row['name']]) ? implode(',', $postData[$row['name']]) : '';
                            } elseif ($row['type'] == 'textarea' && $row['subtype'] == 'tinymce') {
                                $data[$row['name']] = strip_slashes($postData[$row['name']]);
                            } elseif ($row['type'] == 'date') {
                                $data[$row['name']] = dateformat($postData[$row['name']]);
                            } elseif ($row['type'] == 'text') {
                              if($row['subtype'] == 'time'){
                                $data[$row['name']] = dbtimeformat($postData[$row['name']]);
                              }else{
                                $data[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                              }
                            } else {
                                $data[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                            }
                        }


                        }
                    }
                }
            }
            /*
              ghelani nikunj on 14/9/2018
              for geting carehome id for yp profile
             */
            $match = array("yp_id" => $postData['yp_id']);
            $fields = array("*");
            $data_care_home_id['YP_details_care_home_id'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

            /*             * ***************************************************************************************** */

            $data['yp_id'] = $postData['yp_id'];
            $data['draft'] = $postData['draft_data'];
            $data['report_start_date'] = dateformat($postData['report_start_date']);
            $data['report_end_date'] = dateformat($postData['report_end_date']);
            $data['average_days_consumed'] = strip_slashes($postData['average_days_consumed']);
            $data['comments_points'] = strip_slashes($postData['comments_points']);
            $data['safeguarding'] = strip_slashes($postData['safeguarding']);
            $data['general_behaviour'] = strip_slashes($postData['general_behaviour']);
            $data['concerns'] = strip_slashes($postData['concerns']);
            $data['bullying_issues'] = strip_slashes($postData['bullying_issues']);
            $data['significant_events'] = strip_slashes($postData['significant_events']);
            $data['per_of_attendance'] = strip_slashes($postData['per_of_attendance']);
            $data['number_of_referrals'] = strip_slashes($postData['number_of_referrals']);
            $data['achievements'] = strip_slashes($postData['achievements']);
            $data['average_pocket'] = strip_slashes($postData['average_pocket']);
            $data['emotional'] = strip_slashes($postData['emotional']);
            $data['positive_relationships'] = strip_slashes($postData['positive_relationships']);
            $data['contact'] = strip_slashes($postData['contact']);
            $data['peer_relationships'] = strip_slashes($postData['peer_relationships']);
            $data['cultural_needs'] = strip_slashes($postData['cultural_needs']);
            $data['positive_decision'] = strip_slashes($postData['positive_decision']);
            $data['school_clubs'] = strip_slashes($postData['school_clubs']);
            $data['evidencing_curriculum'] = strip_slashes($postData['evidencing_curriculum']);
            $data['voluntary_work'] = strip_slashes($postData['voluntary_work']);
            $data['care_summary'] = strip_slashes($postData['care_summary']);
            $data['attendance'] = strip_slashes($postData['attendance']);
            $data['engagement'] = strip_slashes($postData['engagement']);
            $data['areas_of_focus'] = strip_slashes($postData['areas_of_focus']);
            $data['progress'] = strip_slashes($postData['progress']);
            $data['social_worker'] = strip_slashes($postData['social_worker']);
            $data['placing_authority'] = strip_slashes($postData['placing_authority']);
            $data['case_manager'] = !empty($postData['case_manager']) ? $postData['case_manager'] : 0;

            if (!empty($postData['mdt_report_id'])) {
                $mdt_report_id = $postData['mdt_report_id'];
                /* Ghelani nikunj
                  for care home id and transfer date inserted for archive full functionality
                 */
                $data['care_home_id'] = $data_care_home_id['YP_details_care_home_id'][0]['care_home']; //get carehome id

                /*                 * ******************************************************************** */
                $data['modified_date'] = datetimeformat();
                $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
                $this->common_model->update(MDT_REPORT, $data, array('mdt_report_id' => $postData['mdt_report_id']));
            } else {

                /* Ghelani nikunj
                  for care home id and transfer date inserted for archive full functionality
                 */
                $data['care_home_id'] = $data_care_home_id['YP_details_care_home_id'][0]['care_home']; //get carehome id

                /*                 * ******************************************************************** */
                $data['yp_id'] = $postData['yp_id'];
                $data['created_date'] = datetimeformat();
                $data['modified_date'] = datetimeformat();
                $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];

                $this->common_model->insert(MDT_REPORT, $data);
                $mdt_report_id = $this->db->insert_id();
                //Insert log activity
                $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                    'module_name' => MDT_MODULE, /* updated by Dhara Bhalala as naming conflicts bug on mantis tick. 9924 */
                    'module_field_name' => '',
                    'type' => 1
                );
                log_activity($activity);
            }
            $new_change = 0;
            $new_update = 0;
            if (!empty($mdt_report_id)) {
                // start cpt insert & update 
                //Delete item

                $delete_cpt_review_id = $this->input->post('delete_cpt_review_id');

                if (!empty($delete_cpt_review_id)) {
                    $new_update++;
                    $delete_item = substr($delete_cpt_review_id, 0, -1);
                    $delete_cpt_review_id = explode(',', $delete_item);
                    $where1 = array('mdt_report_id' => $mdt_report_id);
                    $this->common_model->delete_where_in(MDT_CARE_PLAN_TARGET, $where1, 'cpt_id', $delete_cpt_review_id);
                }
                //update cpt item
                $where1 = array('mdt_report_id' => $mdt_report_id);
                $cpt_item = $this->common_model->get_records(MDT_CARE_PLAN_TARGET, array('cpt_id'), '', '', $where1, '');

                if (!empty($cpt_item)) {
                    for ($i = 0; $i < count($cpt_item); $i++) {
                        if (!empty($postData['cpt_title_' . $cpt_item[$i]['cpt_id']]) || !empty($postData['cpt_select_' . $cpt_item[$i]['cpt_id']]) || !empty($postData['cpt_reason_' . $cpt_item[$i]['cpt_id']])) {

                            //get latest archive
                            $where1 = "mdt_report_id =" . $mdt_report_id . " and cpt_id =" . $cpt_item[$i]['cpt_id'];
                            $cpt_item_archive = $this->common_model->get_records(MDT_CARE_PLAN_TARGET_ARCHIVE, array('cpt_id,care_plan_target_title,care_plan_target_reason'), '', '', $where1, '', '1', '', 'archive_cpt_id', 'desc', '', '');

                            if (!empty($cpt_item_archive)) {
                                $old_cpt_title = str_replace(array("\r", "\n"), "", $cpt_item_archive[0]['care_plan_target_title']);

                                $post_cpt_title = str_replace(array("\r", "\n"), "", $postData['cpt_title_' . $cpt_item[$i]['cpt_id']]);

                                $old_cpt_reason = str_replace(array("\r", "\n"), "", $cpt_item_archive[0]['care_plan_target_reason']);

                                $post_cpt_reason = str_replace(array("\r", "\n"), "", $postData['cpt_reason_' . $cpt_item[$i]['cpt_id']]);

                                if (($old_cpt_title != $post_cpt_title) || ($old_cpt_reason != $post_cpt_reason)) {
                                    $new_update++;
                                }
                            }
                            $update_item['care_plan_target_title'] = $postData['cpt_title_' . $cpt_item[$i]['cpt_id']];
                            $update_item['care_plan_target_select'] = $postData['cpt_select_' . $cpt_item[$i]['cpt_id']];
                            $update_item['care_plan_target_reason'] = $postData['cpt_reason_' . $cpt_item[$i]['cpt_id']];
                            $update_item['modified_date'] = datetimeformat();
                            $where = array('cpt_id' => $cpt_item[$i]['cpt_id']);
                            $success_update = $this->common_model->update(MDT_CARE_PLAN_TARGET, $update_item, $where);
                        }
                    }
                }

                //Insert new cpt
                $cpt_title = $this->input->post('cpt_title');
                $cpt_select = $this->input->post('cpt_select');
                $cpt_reason = $this->input->post('cpt_reason');
                for ($i = 0; $i < count($cpt_title); $i++) {
                    if (!empty($cpt_title[$i]) || !empty($cpt_select[$i]) || !empty($cpt_reason[$i])) {
                        $new_change ++;
                        $item_data['mdt_report_id'] = $mdt_report_id;
                        $item_data['yp_id'] = $postData['yp_id'];
                        $item_data['care_plan_target_title'] = $cpt_title[$i];
                        $item_data['care_plan_target_select'] = $cpt_select[$i];
                        $item_data['care_plan_target_reason'] = $cpt_reason[$i];
                        $item_data['staff_initials'] = $this->session->userdata['LOGGED_IN']['ID'];
                        $item_data['created_date'] = datetimeformat();
                        $item_data['modified_date'] = datetimeformat();
                        $this->common_model->insert(MDT_CARE_PLAN_TARGET, $item_data);
                    }
                }

                /* end cpt functionality */
                // start hobbies insert & update 
                //Delete item
                $delete_hobbies_id = $this->input->post('delete_hobbies_id');

                if (!empty($delete_hobbies_id)) {
                    $new_update++;
                    $delete_item = substr($delete_hobbies_id, 0, -1);
                    $delete_hobbies_id = explode(',', $delete_item);
                    $where1 = array('mdt_report_id' => $mdt_report_id);
                    $this->common_model->delete_where_in(MDT_REGULAR_HOBBIES, $where1, 'regular_hobby_id', $delete_hobbies_id);
                }
                //update amendments item
                $where1 = array('mdt_report_id' => $mdt_report_id);
                $hobbyies_item = $this->common_model->get_records(MDT_REGULAR_HOBBIES, array('regular_hobby_id'), '', '', $where1, '');

                if (!empty($hobbyies_item)) {
                    for ($i = 0; $i < count($hobbyies_item); $i++) {
                        if (!empty($postData['regular_hobbies_' . $hobbyies_item[$i]['regular_hobby_id']]) || !empty($postData['regular_hobbies_duration' . $hobbyies_item[$i]['regular_hobby_id']])) {
                            //check record already exist in archive
                            $where1 = array('mdt_report_id' => $mdt_report_id, 'regular_hobbies' => $postData['regular_hobbies_' . $hobbyies_item[$i]['regular_hobby_id']], 'regular_hobbies_duration' => $postData['regular_hobbies_duration_' . $hobbyies_item[$i]['regular_hobby_id']]);
                            $hobby_item_archive = $this->common_model->get_records(MDT_REGULAR_HOBBIES_ARCHIVE, array('archive_hobby_id'), '', '', $where1, '', '', '', '', '', '', '');
                            if (empty($hobby_item_archive)) {
                                $new_update++;
                            }
                            $update_hobby_item['regular_hobbies'] = $postData['regular_hobbies_' . $hobbyies_item[$i]['regular_hobby_id']];

                            $update_hobby_item['regular_hobbies_duration'] = $postData['regular_hobbies_duration_' . $hobbyies_item[$i]['regular_hobby_id']];
                            $update_hobby_item['modified_date'] = datetimeformat();
                            $where = array('regular_hobby_id' => $hobbyies_item[$i]['regular_hobby_id']);
                            $success_update = $this->common_model->update(MDT_REGULAR_HOBBIES, $update_hobby_item, $where);
                        }
                    }
                }

                //Insert new hobbies
                $regular_hobbies = $this->input->post('regular_hobbies');
                $regular_hobbies_duration = $this->input->post('regular_hobbies_duration');
                for ($i = 0; $i < count($regular_hobbies); $i++) {

                    if (!empty($regular_hobbies[$i]) || !empty($regular_hobbies_duration[$i])) {
                        $new_change ++;
                        $reg_item_data['mdt_report_id'] = $mdt_report_id;
                        $reg_item_data['yp_id'] = $postData['yp_id'];
                        $reg_item_data['regular_hobbies'] = $regular_hobbies[$i];
                        $reg_item_data['regular_hobbies_duration'] = $regular_hobbies_duration[$i];
                        $reg_item_data['staff_initials'] = $this->session->userdata['LOGGED_IN']['ID'];
                        $reg_item_data['created_date'] = datetimeformat();
                        $reg_item_data['modified_date'] = datetimeformat();
                        $this->common_model->insert(MDT_REGULAR_HOBBIES, $reg_item_data);
                    }
                }

                /* end hobbies functionality */
                // start physical exercise insert & update 
                //Delete item
                $delete_physical_exercise_id = $this->input->post('delete_physical_exercise_id');

                if (!empty($delete_physical_exercise_id)) {
                    $new_update++;
                    $delete_item = substr($delete_physical_exercise_id, 0, -1);
                    $delete_physical_exercise_id = explode(',', $delete_item);
                    $where1 = array('mdt_report_id' => $mdt_report_id);
                    $this->common_model->delete_where_in(MDT_PHYSICAL_EXERCISE, $where1, 'physical_exercise_id', $delete_physical_exercise_id);
                }
                //update physical exercise item
                $where1 = array('mdt_report_id' => $mdt_report_id);
                $physical_item = $this->common_model->get_records(MDT_PHYSICAL_EXERCISE, array('physical_exercise_id'), '', '', $where1, '');

                if (!empty($physical_item)) {
                    for ($i = 0; $i < count($physical_item); $i++) {
                        if (!empty($postData['physical_exercise_' . $physical_item[$i]['physical_exercise_id']]) || !empty($postData['physical_exercise_duration_' . $physical_item[$i]['physical_exercise_id']])) {
                            //check record already exist in archive
                            $where1 = array('mdt_report_id' => $mdt_report_id, 'physical_exercise' => $postData['physical_exercise_' . $physical_item[$i]['physical_exercise_id']], 'physical_exercise_duration' => $postData['physical_exercise_duration_' . $physical_item[$i]['physical_exercise_id']]);
                            $phy_item_archive = $this->common_model->get_records(MDT_PHYSICAL_EXERCISE_ARCHIVE, array('archive_id'), '', '', $where1, '', '', '', '', '', '', '');
                            if (empty($phy_item_archive)) {
                                $new_update++;
                            }
                            $update_pe_item['physical_exercise'] = $postData['physical_exercise_' . $physical_item[$i]['physical_exercise_id']];
                            $update_pe_item['physical_exercise_duration'] = $postData['physical_exercise_duration_' . $physical_item[$i]['physical_exercise_id']];
                            $update_pe_item['modified_date'] = datetimeformat();
                            $where = array('physical_exercise_id' => $physical_item[$i]['physical_exercise_id']);
                            $success_update = $this->common_model->update(MDT_PHYSICAL_EXERCISE, $update_pe_item, $where);
                        }
                    }
                }

                //Insert new hobbies
                $physical_exercise = $this->input->post('physical_exercise');
                $physical_exercise_duration = $this->input->post('physical_exercise_duration');
                for ($i = 0; $i < count($physical_exercise); $i++) {
                    if (!empty($physical_exercise[$i]) || !empty($physical_exercise_duration[$i])) {
                        $new_change ++;
                        $phy_item_data['mdt_report_id'] = $mdt_report_id;
                        $phy_item_data['yp_id'] = $postData['yp_id'];
                        $phy_item_data['physical_exercise'] = $physical_exercise[$i];
                        $phy_item_data['physical_exercise_duration'] = $physical_exercise_duration[$i];
                        $phy_item_data['staff_initials'] = $this->session->userdata['LOGGED_IN']['ID'];
                        $phy_item_data['created_date'] = datetimeformat();
                        $phy_item_data['modified_date'] = datetimeformat();
                        $this->common_model->insert(MDT_PHYSICAL_EXERCISE, $phy_item_data);
                    }
                }

                /* end physical exercise functionality */
                // start incident insert & update 
                //Delete item
                $delete_incident_id = $this->input->post('delete_incident_id');

                if (!empty($delete_incident_id)) {
                    $new_update++;
                    $delete_item = substr($delete_incident_id, 0, -1);
                    $delete_incident_id = explode(',', $delete_item);
                    $where1 = array('mdt_report_id' => $mdt_report_id);
                    $this->common_model->delete_where_in(MDT_INCIDENT, $where1, 'incident_id', $delete_incident_id);
                }
                //update physical exercise item
                $where1 = array('mdt_report_id' => $mdt_report_id);
                $incident_item = $this->common_model->get_records(MDT_INCIDENT, array('incident_id'), '', '', $where1, '');

                if (!empty($incident_item)) {
                    for ($i = 0; $i < count($incident_item); $i++) {
                        if (!empty($postData['incident_summary_' . $incident_item[$i]['incident_id']]) || !empty($postData['level_' . $incident_item[$i]['incident_id']])) {

                            //get latest archive
                            $where1 = "mdt_report_id =" . $mdt_report_id . " and incident_id =" . $incident_item[$i]['incident_id'];
                            $in_item_archive = $this->common_model->get_records(MDT_INCIDENT_ARCHIVE, array('incident_id,incident_summary'), '', '', $where1, '', '1', '', 'archive_id', 'desc', '', '');

                            if (!empty($in_item_archive)) {
                                $old_summery = str_replace(array("\r", "\n"), "", $in_item_archive[0]['incident_summary']);
                                $post_summery = str_replace(array("\r", "\n"), "", $postData['incident_summary_' . $incident_item[$i]['incident_id']]);

                                if ($old_summery != $post_summery) {
                                    $new_update++;
                                }
                            }
                            $update_in_item['incident_summary'] = ucfirst($postData['incident_summary_' . $incident_item[$i]['incident_id']]);

                            $update_in_item['level'] = ucfirst($postData['level_' . $incident_item[$i]['incident_id']]);
                            $update_in_item['modified_date'] = datetimeformat();

                            $where = array('incident_id' => $incident_item[$i]['incident_id']);
                            $success_update = $this->common_model->update(MDT_INCIDENT, $update_in_item, $where);
                        }
                    }
                }

                //Insert new hobbies
                $incident_summary = $this->input->post('incident_summary');
                $level = $this->input->post('level');

                for ($i = 0; $i < count($incident_summary); $i++) {
                    if (!empty($incident_summary[$i]) || !empty($level[$i])) {
                        $new_change ++;
                        $item_in_data['mdt_report_id'] = $mdt_report_id;
                        $item_in_data['yp_id'] = $postData['yp_id'];
                        $item_in_data['incident_summary'] = ucfirst($incident_summary[$i]);
                        $item_in_data['level'] = isset($postData['level' . $i]) ? $postData['level' . $i] : '';
                        $item_in_data['staff_initials'] = $this->session->userdata['LOGGED_IN']['ID'];
                        $item_in_data['created_date'] = datetimeformat();
                        $item_in_data['modified_date'] = datetimeformat();
                        $this->common_model->insert(MDT_INCIDENT, $item_in_data);
                    }
                }

                /* end incident functionality */
                // start sanction insert & update 
                //Delete item
                $delete_sanction_id = $this->input->post('delete_sanction_id');

                if (!empty($delete_sanction_id)) {
                    $new_update++;
                    $delete_item = substr($delete_sanction_id, 0, -1);
                    $delete_sanction_id = explode(',', $delete_item);
                    $where1 = array('mdt_report_id' => $mdt_report_id);
                    $this->common_model->delete_where_in(MDT_SANCTION, $where1, 'sanction_id', $delete_sanction_id);
                }
                //update physical exercise item
                $where1 = array('mdt_report_id' => $mdt_report_id);
                $sanction_item = $this->common_model->get_records(MDT_SANCTION, array('sanction_id'), '', '', $where1, '');

                if (!empty($sanction_item)) {
                    for ($i = 0; $i < count($sanction_item); $i++) {
                        if (!empty($postData['reason_sanction_' . $sanction_item[$i]['sanction_id']]) || !empty($postData['date_sanction_' . $sanction_item[$i]['sanction_id']]) || !empty($postData['imposed_sanction_' . $sanction_item[$i]['sanction_id']])) {

                            //check record already exist in archive
                            $where1 = array('mdt_report_id' => $mdt_report_id, 'reason_sanction' => $postData['reason_sanction_' . $sanction_item[$i]['sanction_id']], 'date_sanction' => dateformat($postData['date_sanction_' . $sanction_item[$i]['sanction_id']]), 'imposed_sanction' => $postData['imposed_sanction_' . $sanction_item[$i]['sanction_id']]);
                            $sa_item_archive = $this->common_model->get_records(MDT_SANCTION_ARCHIVE, array('archive_id'), '', '', $where1, '', '', '', '', '', '', '');

                            if (empty($sa_item_archive)) {
                                $new_update++;
                            }
                            $update_sac_item['reason_sanction'] = $postData['reason_sanction_' . $sanction_item[$i]['sanction_id']];

                            $update_sac_item['date_sanction'] = dateformat($postData['date_sanction_' . $sanction_item[$i]['sanction_id']]);
                            $update_sac_item['imposed_sanction'] = $postData['imposed_sanction_' . $sanction_item[$i]['sanction_id']];
                            $update_sac_item['modified_date'] = datetimeformat();
                            $where = array('sanction_id' => $sanction_item[$i]['sanction_id']);
                            $success_update = $this->common_model->update(MDT_SANCTION, $update_sac_item, $where);
                        }
                    }
                }

                //Insert new sanction
                $reason_sanction = $this->input->post('reason_sanction');
                $date_sanction = $this->input->post('date_sanction');
                $imposed_sanction = $this->input->post('imposed_sanction');

                for ($i = 0; $i < count($reason_sanction); $i++) {
                    if (!empty($reason_sanction[$i]) || !empty($date_sanction[$i]) || !empty($imposed_sanction[$i])) {
                        $new_change ++;
                        $item_sac_data['mdt_report_id'] = $mdt_report_id;
                        $item_sac_data['yp_id'] = $postData['yp_id'];
                        $item_sac_data['reason_sanction'] = $reason_sanction[$i];
                        $item_sac_data['date_sanction'] = dateformat($date_sanction[$i]);
                        $item_sac_data['imposed_sanction'] = $imposed_sanction[$i];
                        $item_sac_data['staff_initials'] = $this->session->userdata['LOGGED_IN']['ID'];
                        $item_sac_data['created_date'] = datetimeformat();
                        $item_sac_data['modified_date'] = datetimeformat();
                        $this->common_model->insert(MDT_SANCTION, $item_sac_data);
                    }
                }

                /* end sanction functionality */
                // start life_skills insert & update 
                //Delete item
                $delete_life_skills_id = $this->input->post('delete_life_skills_id');

                if (!empty($delete_life_skills_id)) {
                    $new_update++;
                    $delete_item = substr($delete_life_skills_id, 0, -1);
                    $delete_life_skills_id = explode(',', $delete_item);
                    $where1 = array('mdt_report_id' => $mdt_report_id);
                    $this->common_model->delete_where_in(MDT_LIFE_SKILLS, $where1, 'life_skills_id', $delete_life_skills_id);
                }
                //update life skills item
                $where1 = array('mdt_report_id' => $mdt_report_id);
                $life_skills_item = $this->common_model->get_records(MDT_LIFE_SKILLS, array('life_skills_id'), '', '', $where1, '');

                if (!empty($life_skills_item)) {
                    for ($i = 0; $i < count($life_skills_item); $i++) {
                        if (!empty($postData['area_of_development_' . $life_skills_item[$i]['life_skills_id']]) || !empty($postData['progress_achieved_' . $life_skills_item[$i]['life_skills_id']])) {

                            //get latest archive
                            $where1 = "mdt_report_id =" . $mdt_report_id . " and life_skills_id =" . $life_skills_item[$i]['life_skills_id'];
                            $life_item_archive = $this->common_model->get_records(MDT_LIFE_SKILLS_ARCHIVE, array('life_skills_id,area_of_development,progress_achieved'), '', '', $where1, '', '1', '', 'archive_id', 'desc', '', '');

                            if (!empty($life_item_archive)) {
                                $old_area = str_replace(array("\r", "\n"), "", $life_item_archive[0]['area_of_development']);

                                $post_area = str_replace(array("\r", "\n"), "", $postData['area_of_development_' . $life_skills_item[$i]['life_skills_id']]);

                                $old_progress = str_replace(array("\r", "\n"), "", $life_item_archive[0]['progress_achieved']);

                                $post_progress = str_replace(array("\r", "\n"), "", $postData['progress_achieved_' . $life_skills_item[$i]['life_skills_id']]);

                                if (($old_area != $post_area) || ($old_progress != $post_progress)) {
                                    $new_update++;
                                }
                            }
                            $update_life_item['area_of_development'] = $postData['area_of_development_' . $life_skills_item[$i]['life_skills_id']];

                            $update_life_item['progress_achieved'] = $postData['progress_achieved_' . $life_skills_item[$i]['life_skills_id']];
                            $update_life_item['modified_date'] = datetimeformat();
                            $where = array('life_skills_id' => $life_skills_item[$i]['life_skills_id']);
                            $success_update = $this->common_model->update(MDT_LIFE_SKILLS, $update_life_item, $where);
                        }
                    }
                }

                //Insert new life_skills
                $area_of_development = $this->input->post('area_of_development');
                $progress_achieved = $this->input->post('progress_achieved');

                for ($i = 0; $i < count($area_of_development); $i++) {
                    if (!empty($area_of_development[$i]) || !empty($progress_achieved[$i])) {
                        $new_change ++;
                        $item_life_data['mdt_report_id'] = $mdt_report_id;
                        $item_life_data['yp_id'] = $postData['yp_id'];
                        $item_life_data['area_of_development'] = $area_of_development[$i];
                        $item_life_data['progress_achieved'] = $progress_achieved[$i];
                        $item_life_data['staff_initials'] = $this->session->userdata['LOGGED_IN']['ID'];
                        $item_life_data['created_date'] = datetimeformat();
                        $item_life_data['modified_date'] = datetimeformat();
                        $this->common_model->insert(MDT_LIFE_SKILLS, $item_life_data);
                    }
                }

                /* end life_skills functionality */
                // start cpt week insert & update 
                //Delete item

                $delete_cpt_weeks_id = $this->input->post('delete_cpt_weeks_id');

                if (!empty($delete_cpt_weeks_id)) {
                    $new_update++;
                    $delete_item = substr($delete_cpt_weeks_id, 0, -1);
                    $delete_cpt_weeks_id = explode(',', $delete_item);
                    $where1 = array('mdt_report_id' => $mdt_report_id);
                    $this->common_model->delete_where_in(MDT_CARE_PLAN_TARGET_WEEK, $where1, 'cpt_week_id', $delete_cpt_weeks_id);
                }
                //update cpt item
                $where1 = array('mdt_report_id' => $mdt_report_id);
                $cpt_week_item = $this->common_model->get_records(MDT_CARE_PLAN_TARGET_WEEK, array('cpt_week_id'), '', '', $where1, '');

                if (!empty($cpt_week_item)) {
                    for ($i = 0; $i < count($cpt_week_item); $i++) {
                        if (!empty($postData['cpt_week_title_' . $cpt_week_item[$i]['cpt_week_id']]) || !empty($postData['cpt_week_select_' . $cpt_week_item[$i]['cpt_week_id']]) || !empty($postData['cpt_week_reason_' . $cpt_week_item[$i]['cpt_week_id']])) {
                            //get latest archive
                            $where1 = "mdt_report_id =" . $mdt_report_id . " and cpt_week_id =" . $cpt_week_item[$i]['cpt_week_id'];
                            $current_protocol_archive = $this->common_model->get_records(MDT_CARE_PLAN_TARGET_WEEK_ARCHIVE, array('cpt_week_id,care_plan_target_title,care_plan_target_reason'), '', '', $where1, '', '1', '', 'archive_cpt_id', 'desc', '', '');

                            if (!empty($current_protocol_archive)) {
                                $old_title = str_replace(array("\r", "\n"), "", $current_protocol_archive[0]['care_plan_target_title']);

                                $post_title = str_replace(array("\r", "\n"), "", $postData['cpt_week_title_' . $cpt_week_item[$i]['cpt_week_id']]);

                                $old_reason = str_replace(array("\r", "\n"), "", $current_protocol_archive[0]['care_plan_target_reason']);

                                $post_reason = str_replace(array("\r", "\n"), "", $postData['cpt_week_reason_' . $cpt_week_item[$i]['cpt_week_id']]);

                                if (($old_title != $post_title) || ($old_reason != $post_reason)) {
                                    $new_update++;
                                }
                            }

                            $update_week_item['care_plan_target_title'] = strip_tags($postData['cpt_week_title_' . $cpt_week_item[$i]['cpt_week_id']]);
                            $update_week_item['care_plan_target_select'] = strip_tags($postData['cpt_week_select_' . $cpt_week_item[$i]['cpt_week_id']]);
                            $update_week_item['care_plan_target_reason'] = strip_tags($postData['cpt_week_reason_' . $cpt_week_item[$i]['cpt_week_id']]);
                            $update_week_item['modified_date'] = datetimeformat();
                            $where = array('cpt_week_id' => $cpt_week_item[$i]['cpt_week_id']);
                            $success_update = $this->common_model->update(MDT_CARE_PLAN_TARGET_WEEK, $update_week_item, $where);
                        }
                    }
                }

                //Insert new cpt
                $cpt_week_title = $this->input->post('cpt_week_title');
                $cpt_week_select = $this->input->post('cpt_week_select');
                $cpt_week_reason = $this->input->post('cpt_week_reason');
                for ($i = 0; $i < count($cpt_week_title); $i++) {
                    if (!empty($cpt_week_title[$i]) || !empty($cpt_week_select[$i]) || !empty($cpt_week_reason[$i])) {
                        $new_change ++;
                        $item_week_data['mdt_report_id'] = $mdt_report_id;
                        $item_week_data['yp_id'] = $postData['yp_id'];
                        $item_week_data['care_plan_target_title'] = $cpt_week_title[$i];
                        $item_week_data['care_plan_target_select'] = $cpt_week_select[$i];
                        $item_week_data['care_plan_target_reason'] = $cpt_week_reason[$i];
                        $item_week_data['staff_initials'] = $this->session->userdata['LOGGED_IN']['ID'];
                        $item_week_data['created_date'] = datetimeformat();
                        $item_week_data['modified_date'] = datetimeformat();
                        $this->common_model->insert(MDT_CARE_PLAN_TARGET_WEEK, $item_week_data);
                    }
                }

                /* end cpt week functionality */
                // start cpt previous insert & update 
                //Delete item

                $delete_cpt_previous_id = $this->input->post('delete_cpt_previous_id');

                if (!empty($delete_cpt_previous_id)) {
                    $new_update++;
                    $delete_item = substr($delete_cpt_previous_id, 0, -1);
                    $delete_cpt_previous_id = explode(',', $delete_item);
                    $where1 = array('mdt_report_id' => $mdt_report_id);
                    $this->common_model->delete_where_in(MDT_CARE_PLAN_TARGET_PREVIOUS, $where1, 'cpt_previous_id', $delete_cpt_previous_id);
                }
                //update cpt item
                $where1 = array('mdt_report_id' => $mdt_report_id);
                $cpt_previous_item = $this->common_model->get_records(MDT_CARE_PLAN_TARGET_PREVIOUS, array('cpt_previous_id'), '', '', $where1, '');

                if (!empty($cpt_previous_item)) {
                    for ($i = 0; $i < count($cpt_previous_item); $i++) {
                        if (!empty($postData['cpt_previous_title_' . $cpt_previous_item[$i]['cpt_previous_id']]) || !empty($postData['cpt_previous_select_' . $cpt_previous_item[$i]['cpt_previous_id']]) || !empty($postData['cpt_previous_reason_' . $cpt_previous_item[$i]['cpt_previous_id']])) {
                            //get latest archive
                            $where1 = "mdt_report_id =" . $mdt_report_id . " and cpt_previous_id =" . $cpt_previous_item[$i]['cpt_previous_id'];
                            $current_protocol_archive = $this->common_model->get_records(MDT_CARE_PLAN_TARGET_PREVIOUS_ARCHIVE, array('cpt_previous_id,care_plan_target_title,care_plan_target_reason'), '', '', $where1, '', '1', '', 'archive_cpt_id', 'desc', '', '');

                            if (!empty($current_protocol_archive)) {
                                $old_title = str_replace(array("\r", "\n"), "", $current_protocol_archive[0]['care_plan_target_title']);

                                $post_title = str_replace(array("\r", "\n"), "", $postData['cpt_previous_title_' . $cpt_previous_item[$i]['cpt_previous_id']]);

                                $old_reason = str_replace(array("\r", "\n"), "", $current_protocol_archive[0]['care_plan_target_reason']);

                                $post_reason = str_replace(array("\r", "\n"), "", $postData['cpt_previous_reason_' . $cpt_previous_item[$i]['cpt_previous_id']]);

                                if (($old_title != $post_title) || ($old_reason != $post_reason)) {
                                    $new_update++;
                                }
                            }

                            $update_previous_item['care_plan_target_title'] = strip_tags($postData['cpt_previous_title_' . $cpt_previous_item[$i]['cpt_previous_id']]);
                            $update_previous_item['care_plan_target_select'] = strip_tags($postData['cpt_previous_select_' . $cpt_previous_item[$i]['cpt_previous_id']]);
                            $update_previous_item['care_plan_target_reason'] = strip_tags($postData['cpt_previous_reason_' . $cpt_previous_item[$i]['cpt_previous_id']]);
                            $update_previous_item['modified_date'] = datetimeformat();
                            $where = array('cpt_previous_id' => $cpt_previous_item[$i]['cpt_previous_id']);
                            $success_update = $this->common_model->update(MDT_CARE_PLAN_TARGET_PREVIOUS, $update_previous_item, $where);
                        }
                    }
                }

                //Insert new cpt
                $cpt_previous_title = $this->input->post('cpt_previous_title');
                $cpt_previous_select = $this->input->post('cpt_previous_select');
                $cpt_previous_reason = $this->input->post('cpt_previous_reason');
                for ($i = 0; $i < count($cpt_previous_title); $i++) {
                    if (!empty($cpt_previous_title[$i]) || !empty($cpt_previous_select[$i]) || !empty($cpt_previous_reason[$i])) {
                        $new_change ++;
                        $item_previous_data['mdt_report_id'] = $mdt_report_id;
                        $item_previous_data['yp_id'] = $postData['yp_id'];
                        $item_previous_data['care_plan_target_title'] = $cpt_previous_title[$i];
                        $item_previous_data['care_plan_target_select'] = $cpt_previous_select[$i];
                        $item_previous_data['care_plan_target_reason'] = $cpt_previous_reason[$i];
                        $item_previous_data['staff_initials'] = $this->session->userdata['LOGGED_IN']['ID'];
                        $item_previous_data['created_date'] = datetimeformat();
                        $item_previous_data['modified_date'] = datetimeformat();
                        $this->common_model->insert(MDT_CARE_PLAN_TARGET_PREVIOUS, $item_previous_data);
                    }
                }

                /* end cpt previous functionality */
            }
            if (empty($postData['draft_data'])) {
                $this->createArchive($postData['yp_id'], $mdt_report_id, $new_change, $new_update);
            }
            redirect('/' . $this->viewname . '/view/' . $mdt_report_id . '/' . $postData['yp_id']);
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : MDT Report delete
      @Input  :
      @Output :
      @Date   : 27/03/2018
     */
   public function deleteMDT($yp_id,$id) 
   {
            //Delete Record From Database
        if (!empty($yp_id) && !empty($id)) {
                    $where = array('mdt_report_id' => $id);
                      if ($this->common_model->delete(MDT_REPORT, $where)) {
                                  //Insert log activity
                              $activity = array(
                                'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
                                'module_name'         => MDT_MODULE,/*updated by Dhara Bhalala as naming conflicts bug on mantis tick. 9924*/
                                'module_field_name'   => '',
                                'yp_id'   => $yp_id,
                                'type'                => 3
                              );
                  log_activity($activity);
                  $msg = $this->lang->line('Deleted_MDT_Successfully');
                  $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                  unset($docs_id);
                  
              } else {
                  // error
                  $msg = $this->lang->line('error_msg');
                  $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                  
              }
          }
        redirect('MDTReviewReport/index/'.$yp_id);
    }
   /*
      @Author : Niral Patel
      @Desc   : MDT Report delete
      @Input  :
      @Output :
      @Date   : 28/03/2018
     */
   public function DownloadPdf($mdt_report_id,$yp_id) {
        $this->load->library('m_pdf');
        $data = [];
        $match = array('mdt_form_id'=> 1);
        $ra_forms = $this->common_model->get_records(MDT_FORM,'', '', '', $match);
        if(!empty($ra_forms))
        {
            $data['ra_form_data'] = json_decode($ra_forms[0]['form_json_data'], TRUE);
        }
        $id = $mdt_report_id;
        //get YP information
        $data['id'] = $yp_id;
       
        $table = YP_DETAILS . ' as yp';
        $match = "yp.yp_id = " . $yp_id;
        $fields = array("yp.care_home,yp.yp_fname,yp.yp_lname,yp.date_of_birth,swd.social_worker_firstname,swd.social_worker_surname,pa.authority");
        $join_tables = array(PLACING_AUTHORITY . ' as pa' => 'pa.yp_id= yp.yp_id', SOCIAL_WORKER_DETAILS . ' as swd' => 'swd.yp_id= yp.yp_id', OVERVIEW_OF_YOUNG_PERSON . ' as oyp' => 'oyp.yp_id= yp.yp_id');
        $data['YP_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
         //get Mdt yp data
        $table = MDT_REPORT . ' as cpm';
        $match = array('cpm.yp_id'=> $yp_id,'cpm.mdt_report_id' => $mdt_report_id);
        $fields = array("cpm.*,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(l1.firstname,' ', l1.lastname) as case_manager_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id = cpm.created_by',LOGIN . ' as l1' => 'l1.login_id = cpm.case_manager');
        
        $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);

         /*MDT_CARE_PLAN_TARGET*/
        $table = MDT_CARE_PLAN_TARGET . ' as mdt';
        $match = array('mdt.mdt_report_id'=> $id);
        $fields = array("mdt.cpt_id,mdt.care_plan_target_title,mdt.care_plan_target_select,mdt.care_plan_target_reason");
        $data['care_plan_target'] = $this->common_model->get_records($table, $fields, '', 'left', $match); 

        /*end MDT_CARE_PLAN_TARGET*/

        /*MDT_CARE_PLAN_TARGET_PREVIOUS*/
        $table = MDT_CARE_PLAN_TARGET_PREVIOUS . ' as mdt';
        $match = array('mdt.mdt_report_id'=> $id);
        $fields = array("mdt.cpt_previous_id,mdt.care_plan_target_title,mdt.care_plan_target_select,mdt.care_plan_target_reason");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= mdt.staff_initials');
        $data['care_plan_target_previous'] = $this->common_model->get_records($table, $fields, '', 'left', $match); 
        /*end MDT_CARE_PLAN_TARGET_PREVIOUS*/
       
         /*MDT_REGULAR_HOBBIES*/
        $table = MDT_REGULAR_HOBBIES . ' as mdt';
        $match = array('mdt_report_id'=>$id,'mdt.yp_id'=> $yp_id);
        $fields = array("mdt.regular_hobby_id,mdt.regular_hobbies,mdt.regular_hobbies_duration");
        
        $data['hobbies_data'] = $this->common_model->get_records($table, $fields, '', 'left', $match); 
        /*end MDT_REGULAR_HOBBIES*/
         /*MDT_PHYSICAL_EXERCISE*/
        $table = MDT_PHYSICAL_EXERCISE . ' as mdt';
        $match = array('mdt_report_id'=>$id,'mdt.yp_id'=> $yp_id);
        $fields = array("mdt.physical_exercise_id,mdt.physical_exercise,mdt.physical_exercise_duration");
        $data['physical_exercise_data'] = $this->common_model->get_records($table, $fields, '', 'left', $match); 
        /*end MDT_PHYSICAL_EXERCISE*/
         /*MDT_INCIDENT*/
        $table = MDT_INCIDENT . ' as mdt';
        $match = array('mdt_report_id'=>$id,'mdt.yp_id'=> $yp_id);
        $fields = array("mdt.incident_summary,mdt.level,mdt.incident_id");
        $data['incident_data'] = $this->common_model->get_records($table, $fields, '', 'left', $match); 
        /*end MDT_INCIDENT*/
         /*MDT_INCIDENT Level*/
        $table_in = MDT_INCIDENT . ' as mdt';
        $match_in = array('mdt_report_id'=>$id,'mdt.yp_id'=> $yp_id);
        $fields_in = array("SUM(CASE WHEN mdt.level = 1 THEN 1 ELSE 0 END) as level1,SUM(CASE WHEN mdt.level = 2 THEN 1 ELSE 0 END) as level2,SUM(CASE WHEN mdt.level = 3 THEN 1 ELSE 0 END) as level3,SUM(CASE WHEN mdt.level = 4 THEN 1 ELSE 0 END) as level4");
        $join_tables_in = array(LOGIN . ' as l' => 'l.login_id= mdt.staff_initials');
        $data['incident_level'] = $this->common_model->get_records($table_in, $fields_in, $join_tables_in, 'left', $match_in,'','','','','','mdt.mdt_report_id'); 
        /*end MDT_INCIDENT*/
         /*MDT_SANCTION*/
        $table = MDT_SANCTION . ' as mdt';
        $match = array('mdt_report_id'=>$id,'mdt.yp_id'=> $yp_id);
        $fields = array("mdt.sanction_id,mdt.reason_sanction,mdt.imposed_sanction,mdt.date_sanction");
        $data['sanction_data'] = $this->common_model->get_records($table, $fields, '', 'left', $match); 
        /*end MDT_SANCTION*/
        /*MDT_LIFE_SKILLS*/
        $table = MDT_LIFE_SKILLS . ' as mdt';
        $match = array('mdt_report_id'=>$id,'mdt.yp_id'=> $yp_id);
        $fields = array("mdt.life_skills_id,mdt.area_of_development,mdt.progress_achieved");
        $data['life_skills_data'] = $this->common_model->get_records($table, $fields, '', 'left', $match); 
        /*end MDT_LIFE_SKILLS*/
         /*MDT_CARE_PLAN_TARGET_WEEK*/
        $table = MDT_CARE_PLAN_TARGET_WEEK . ' as mdt';
        $match = array('mdt.mdt_report_id'=> $id);
        $fields = array("mdt.cpt_week_id,mdt.care_plan_target_title,mdt.care_plan_target_select,mdt.care_plan_target_reason");
        $data['care_plan_target_week'] = $this->common_model->get_records($table, $fields, '', 'left', $match); 
        /*end MDT_CARE_PLAN_TARGET_WEEK*/
        /*MDT_CARE_PLAN_TARGET_PREVIOUS*/
        $table = MDT_CARE_PLAN_TARGET_PREVIOUS . ' as mdt';
        $match = array('mdt.mdt_report_id'=> $id);
        $fields = array("mdt.cpt_previous_id,mdt.care_plan_target_title,mdt.care_plan_target_select,mdt.care_plan_target_reason");
        $data['care_plan_target_previous'] = $this->common_model->get_records($table, $fields, '', 'left', $match); 
        /*end MDT_CARE_PLAN_TARGET_PREVIOUS*/
         /*medical approintment query start*/
       
        $table_ap = MEDICAL_PROFESSIONALS_APPOINTMENT . ' as mc';
        $where_ap = array("mc.yp_id" => $yp_id,"mc.is_delete"=>0,"mc.appointment_date >=" =>$data['edit_data'][0]['report_start_date'],"mc.appointment_date <=" =>$data['edit_data'][0]['report_end_date']);
        $fields_ap = array("mc.*,concat(if(mp.title != '',mp.title,''),' ',if(mp.first_name != '',mp.first_name,''),' ',if(mp.surname != '',mp.surname,''),' - ',if(mp.professional != '',mp.professional,'')) as mp_name, concat(mp.house_namenumber,',',mp.street,' ',mp.town,',',mp.city,',',mp.postcode) as address");
        $join_tables_ap = array(MEDICAL_PROFESSIONALS . ' as mp' => 'mc.mp_id= mp.mp_id',YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id');
        $data['appointments'] = $this->common_model->get_records($table_ap, $fields_ap, $join_tables_ap, 'left', $where_ap, '', '', '', 'appointment_id', 'desc', '');
        $table = MDT_REPORT_SIGNOFF.' as ims';
        $where = array("l.is_delete"=> "0","ims.yp_id" => $yp_id,"ims.mdt_report_id" => $mdt_report_id);

        $fields = array("ims.created_by,ims.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id=ims.created_by');
        $group_by = array('created_by');
        $data['signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);

        //do average day
        $table = DAILY_OBSERVATIONS . ' as do';
        $where = array("do.yp_id" => $yp_id,"do.daily_observation_date >=" =>$data['edit_data'][0]['report_start_date'],"do.daily_observation_date <=" =>$data['edit_data'][0]['report_end_date']);
        $fields = array("do.do_id,do.yp_id,do.daily_observation_date,d.day");
         $join_tables = array(DO_FOODCONSUMED . ' as d' => 'd.do_id= do.do_id');
        $dodata = $this->common_model->get_records($table, $fields, $join_tables, 'left', $where,'','','','','','d.do_id');
        if(!empty($dodata))
        {
          $sum = 0;
          foreach ($dodata as $row) {
            $sum +=$row['day'];
          }
          $diet_avg = round($sum/count($dodata));
        }
        $data['diet_avg'] = !empty($diet_avg)?$diet_avg:'0';
        $data['crnt_view'] = $this->viewname;
        $data['ypid'] = $yp_id;

        //new
        $pdfFileName = "mdt_report.pdf";
        $PDFInformation['yp_details'] = $data['YP_details'][0];
        $PDFInformation['edit_data'] = $data['edit_data'];

        $PDFHeaderHTML  = $this->load->view('mdtpdfHeader', $PDFInformation,true);
        $PDFFooterHTML  = $this->load->view('mdtpdfFooter', $PDFInformation,true);
            
        //Set Header Footer and Content For PDF
        $this->m_pdf->pdf->mPDF('utf-8','A4','','','15','15','70','25');
        $this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
        $this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);                    
        $data['main_content'] = '/mdtpdf';
        $html = $this->parser->parse('layouts/PdfDataTemplate', $data);
        /*remove*/
        $this->m_pdf->pdf->WriteHTML($html);
        //Store PDF in individual_strategies Folder
        $this->m_pdf->pdf->Output($pdfFileName, "D");

    }
    /*
      @Author : Niral Patel
      @Desc   : manage review
      @Input  :
      @Output :
      @Date   : 28/03/2018
     */
    public function manager_review($yp_id,$mdt_report_id) {   
      if (!empty($yp_id) && !empty($mdt_report_id)) {
      $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
      $match = array('yp_id'=> $yp_id,'mdt_report_id'=> $mdt_report_id,'created_by'=>$login_user_id,'is_delete'=> '0');

      $check_signoff_data = $this->common_model->get_records(MDT_REPORT_SIGNOFF,'', '', '', $match);
      if(empty($check_signoff_data) > 0){
          $update_pre_data['yp_id'] = $yp_id;
          $update_pre_data['mdt_report_id'] = $mdt_report_id;
          $update_pre_data['created_date'] = datetimeformat();
          $update_pre_data['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
      if ($this->common_model->insert(MDT_REPORT_SIGNOFF,$update_pre_data)) {
      $msg = $this->lang->line('successfully_mdt_review');
      $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
      } else {
      // error
      $msg = $this->lang->line('error_msg');
      $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
      }
      }else{
      $msg = $this->lang->line('already_mdt_review');
      $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
      }
      }else{      
      $msg = $this->lang->line('error_msg');
      $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
      }

      redirect('/' . $this->viewname .'/index/'.$yp_id);
    }

    /*
      @Author : Niral Patel
      @Desc   : Save url
      @Input  :
      @Output :
      @Date   : 28/03/2018
     */
    public function update_slug() {
          $postData = $this->input->post ();
          $where = array('url_data' => $postData['url_data']);
          $this->common_model->delete(CHECK_EDIT_URL, $where);

          $update_pre_data['datetime'] =date('Y-m-d H:i:s');
          $update_pre_data['url_data'] = $postData['url_data'];
          $this->common_model->insert(CHECK_EDIT_URL,$update_pre_data);
         return TRUE;
    }

    /*
      @Author : Niral Patel
      @Desc   : Create archive
      @Input    :
      @Output   :
      @Date   : 18/08/2017
     */
    public function createArchive($id,$mdt_report_id,$new_change,$new_update)
    {
          $fields = array("care_home");
          $data_yp_detail['YP_details'] = YpDetails($id,$fields);

			if(is_numeric($id))
      {
         //get ra form
         $match_fo = array('mdt_form_id'=> 1);
         $formsdata = $this->common_model->get_records(MDT_FORM,array("form_json_data"), '', '', $match_fo);
         //get MDT yp data
         $match = array('yp_id'=> $id,'mdt_report_id'=> $mdt_report_id);
         $pp_yp_data = $this->common_model->get_records(MDT_REPORT,'', '', '', $match);

         if(!empty($formsdata) && !empty($pp_yp_data))
         {
              $form_data = json_decode($formsdata[0]['form_json_data'], TRUE);
              $data = array();
              $i=0;
              foreach ($form_data as $row) {
                  if(isset($row['name']))
                  {
                      if($row['type'] != 'button')
                      {
                          if($row['type'] == 'checkbox-group')
                          {
                            $form_data[$i]['value'] = implode(',',$pp_yp_data[0][$row['name']]);
                            $archive[$row['name']]        = implode(',',$pp_yp_data[0][$row['name']]);
                          }else{
                            $form_data[$i]['value'] = str_replace("'", '"', $pp_yp_data[0][$row['name']]);
                            $archive[$row['name']]        = str_replace("'", '"', $pp_yp_data[0][$row['name']]);
                          }
                      }
                  }
                  $i++;
              }
               
               $archive['yp_id']             = $id;
               $archive['form_json_data'] = json_encode($form_data, TRUE);
               $archive['created_by']        = $this->session->userdata('LOGGED_IN')['ID'];
			   
			   /*18-9-2018
			   ghelani nikunj
			   this field added because archived data functionality 
			   */
               $archive['care_home_id']      =  $data_yp_detail['YP_details'][0]['care_home'];
			   /****************************************************/
			   
               $archive['created_date']      = datetimeformat();
               $archive['status']            = 0;
               $archive['mdt_report_id']             = strip_slashes($pp_yp_data[0]['mdt_report_id']);
               $archive['report_start_date'] = dateformat($pp_yp_data[0]['report_start_date']);
               $archive['report_end_date'] = dateformat($pp_yp_data[0]['report_end_date']);
               $archive['average_days_consumed'] = strip_slashes($pp_yp_data[0]['average_days_consumed']);
               $archive['comments_points'] = strip_slashes($pp_yp_data[0]['comments_points']);
               $archive['safeguarding'] = strip_slashes($pp_yp_data[0]['safeguarding']);
               $archive['general_behaviour'] = strip_slashes($pp_yp_data[0]['general_behaviour']);
               $archive['concerns'] = strip_slashes($pp_yp_data[0]['concerns']);
               $archive['bullying_issues'] = strip_slashes($pp_yp_data[0]['bullying_issues']);
               $archive['significant_events'] = strip_slashes($pp_yp_data[0]['significant_events']);
               $archive['per_of_attendance'] = strip_slashes($pp_yp_data[0]['per_of_attendance']);
               $archive['number_of_referrals'] = strip_slashes($pp_yp_data[0]['number_of_referrals']);
               $archive['achievements'] = strip_slashes($pp_yp_data[0]['achievements']);
               $archive['average_pocket'] = strip_slashes($pp_yp_data[0]['average_pocket']);
               $archive['emotional'] = strip_slashes($pp_yp_data[0]['emotional']);
               $archive['positive_relationships'] = strip_slashes($pp_yp_data[0]['positive_relationships']);
               $archive['contact'] = strip_slashes($pp_yp_data[0]['contact']);
               $archive['peer_relationships'] = strip_slashes($pp_yp_data[0]['peer_relationships']);
               $archive['cultural_needs'] = strip_slashes($pp_yp_data[0]['cultural_needs']);
               $archive['positive_decision'] = strip_slashes($pp_yp_data[0]['positive_decision']);
               $archive['school_clubs'] = strip_slashes($pp_yp_data[0]['school_clubs']);
               $archive['evidencing_curriculum'] = strip_slashes($pp_yp_data[0]['evidencing_curriculum']);
               $archive['voluntary_work'] = strip_slashes($pp_yp_data[0]['voluntary_work']);
               $archive['care_summary'] = strip_slashes($pp_yp_data[0]['care_summary']);
               $archive['attendance'] = strip_slashes($pp_yp_data[0]['attendance']);
               $archive['engagement'] = strip_slashes($pp_yp_data[0]['engagement']);
               $archive['areas_of_focus'] = strip_slashes($pp_yp_data[0]['areas_of_focus']);
               $archive['progress'] = strip_slashes($pp_yp_data[0]['progress']);
               $archive['care_plan_targets'] = strip_slashes($pp_yp_data[0]['care_plan_targets']);
               $archive['social_worker'] = strip_slashes($pp_yp_data[0]['social_worker']);
               $archive['placing_authority'] = strip_slashes($pp_yp_data[0]['placing_authority']);
               $archive['case_manager'] = strip_slashes($pp_yp_data[0]['case_manager']);

              //get ra yp archive
               $wherestring = " form_json_data = '".str_replace("\\","\\\\", json_encode($form_data, TRUE))."'";

               $match_rep['report_start_date'] = dateformat($pp_yp_data[0]['report_start_date']);
               $match_rep['report_end_date'] = dateformat($pp_yp_data[0]['report_end_date']);
               
               $match_rep['social_worker'] = addslashes($pp_yp_data[0]['social_worker']);
               $match_rep['placing_authority'] = addslashes($pp_yp_data[0]['placing_authority']);
               $match_rep['case_manager'] = addslashes($pp_yp_data[0]['case_manager']);
               
               $pp_archive_data = $this->common_model->get_records(MDT_REPORT_ARCHIVE,array('yp_id'), '', '', $match_rep,'','','','','','',$wherestring);
               
               //get ra yp data
               $match = array('yp_id'=> $id,'mdt_report_id'=> $mdt_report_id,'status'=>0);
               $archive_old_data = $this->common_model->get_records(MDT_REPORT_ARCHIVE,'', '', '', $match);
               if(!empty($archive_old_data))
                 {
                    if((strip_tags($pp_yp_data[0]['average_days_consumed']) != strip_tags($archive_old_data[0]['average_days_consumed'])) || (strip_tags($pp_yp_data[0]['comments_points']) != strip_tags($archive_old_data[0]['comments_points'])) || (strip_tags($pp_yp_data[0]['safeguarding']) != strip_tags($archive_old_data[0]['safeguarding'])) || (strip_tags($pp_yp_data[0]['general_behaviour']) != strip_tags($archive_old_data[0]['general_behaviour'])) || (strip_tags($pp_yp_data[0]['concerns']) != strip_tags($archive_old_data[0]['concerns'])) || (strip_tags($pp_yp_data[0]['bullying_issues']) != strip_tags($archive_old_data[0]['bullying_issues']))|| (strip_tags($pp_yp_data[0]['significant_events']) != strip_tags($archive_old_data[0]['significant_events'])) || (strip_tags($pp_yp_data[0]['per_of_attendance']) != strip_tags($archive_old_data[0]['per_of_attendance'])) || (strip_tags($pp_yp_data[0]['number_of_referrals']) != strip_tags($archive_old_data[0]['number_of_referrals'])) || (strip_tags($pp_yp_data[0]['achievements']) != strip_tags($archive_old_data[0]['achievements'])) || (strip_tags($pp_yp_data[0]['average_pocket']) != strip_tags($archive_old_data[0]['average_pocket'])) || (strip_tags($pp_yp_data[0]['emotional']) != strip_tags($archive_old_data[0]['emotional'])) || (strip_tags($pp_yp_data[0]['positive_relationships']) != strip_tags($archive_old_data[0]['positive_relationships'])) || (strip_tags($pp_yp_data[0]['contact']) != strip_tags($archive_old_data[0]['contact'])) || (strip_tags($pp_yp_data[0]['peer_relationships']) != strip_tags($archive_old_data[0]['peer_relationships'])) || (strip_tags($pp_yp_data[0]['cultural_needs']) != strip_tags($archive_old_data[0]['cultural_needs'])) || (strip_tags($pp_yp_data[0]['positive_decision']) != strip_tags($archive_old_data[0]['positive_decision'])) || (strip_tags($pp_yp_data[0]['school_clubs']) != strip_tags($archive_old_data[0]['school_clubs'])) || (strip_tags($pp_yp_data[0]['evidencing_curriculum']) != strip_tags($archive_old_data[0]['evidencing_curriculum'])) || (strip_tags($pp_yp_data[0]['voluntary_work']) != strip_tags($archive_old_data[0]['voluntary_work'])) || (strip_tags($pp_yp_data[0]['care_summary']) != strip_tags($archive_old_data[0]['care_summary']))  || (strip_tags($pp_yp_data[0]['attendance']) != strip_tags($archive_old_data[0]['attendance']))  || (strip_tags($pp_yp_data[0]['engagement']) != strip_tags($archive_old_data[0]['engagement']))  || (strip_tags($pp_yp_data[0]['areas_of_focus']) != strip_tags($archive_old_data[0]['areas_of_focus']))  || (strip_tags($pp_yp_data[0]['progress']) != strip_tags($archive_old_data[0]['progress'])))
                    {
                      $new_change++;
                    }
                 }

               if(empty($pp_archive_data) || $new_change > 0 ||  $new_update > 0)
               {
                  //Insert log activity
                $activity = array(
                  'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
                  'yp_id'               => !empty($id)?$id:'',
                  'module_name'         => MDT_MODULE,/*updated by Dhara Bhalala as naming conflicts bug on mantis tick. 9924*/
                  'module_field_name'   => '',
                  'type'                => 2
                );
                log_activity($activity);
                 //get ra yp data
                 $match = array('yp_id'=> $id,'mdt_report_id'=> $mdt_report_id);
                 $archive_data = $this->common_model->get_records(MDT_REPORT_ARCHIVE,'', '', '', $match);
                 
                 if(!empty($archive_data))
                 {
                    //update status to archive
                     $update_archive = array(
                        'created_date'=>datetimeformat(),
                        'status'=>1
                    );
                    $where = array('status'=>0,'yp_id'=>$id,'mdt_report_id'=> $mdt_report_id);
                    $this->common_model->update(MDT_REPORT_ARCHIVE, $update_archive,$where);
                   
                 }
                 
                 //insert archive data for next time
                 $this->common_model->insert(MDT_REPORT_ARCHIVE, $archive);

                 $archive_mdt_report_id = $this->db->insert_id();
                  

                   /*MDT_CARE_PLAN_TARGET*/
                  $table = MDT_CARE_PLAN_TARGET . ' as mdt';
                  $match = array('mdt.mdt_report_id'=> $mdt_report_id);
                  $fields = array("mdt.*");
                  $care_plan_target = $this->common_model->get_records($table, $fields,'', '', $match); 
                  
                  if(!empty($care_plan_target))
                  {
                    foreach ($care_plan_target as $row) {
                      
                        foreach ($row as $key => $value) {
                          $cpt_ar[$key] = $value;
                        }
                        $cpt_ar['archive_mdt_report_id'] = $archive_mdt_report_id;
                        $this->common_model->insert(MDT_CARE_PLAN_TARGET_ARCHIVE, $cpt_ar);
                    }
                    
                  }
                  /*end MDT_CARE_PLAN_TARGET*/
                  /*MDT_REGULAR_HOBBIES_ARCHIVE*/
                  $table = MDT_REGULAR_HOBBIES . ' as mdt';
                  $match = array('mdt.mdt_report_id'=> $mdt_report_id);
                  $fields = array("mdt.*");
                  $hobbies = $this->common_model->get_records($table, $fields,'', '', $match); 
                  
                  if(!empty($hobbies))
                  {
                    foreach ($hobbies as $row) {
                      
                        foreach ($row as $key => $value) {
                          $hobby_ar[$key] = $value;
                        }
                        $hobby_ar['archive_mdt_report_id'] = $archive_mdt_report_id;
                        $this->common_model->insert(MDT_REGULAR_HOBBIES_ARCHIVE, $hobby_ar);
                    }
                    
                  }
                  /*end MDT_REGULAR_HOBBIES_ARCHIVE*/
                  /*MDT_PHYSICAL_EXERCISE_ARCHIVE*/
                  $table = MDT_PHYSICAL_EXERCISE . ' as mdt';
                  $match = array('mdt.mdt_report_id'=> $mdt_report_id);
                  $fields = array("mdt.*");
                  $exercise = $this->common_model->get_records($table, $fields,'', '', $match); 
                  
                  if(!empty($exercise))
                  {
                    foreach ($exercise as $row) {
                      
                        foreach ($row as $key => $value) {
                          $exercise_ar[$key] = $value;
                        }
                        $exercise_ar['archive_mdt_report_id'] = $archive_mdt_report_id;
                        $this->common_model->insert(MDT_PHYSICAL_EXERCISE_ARCHIVE, $exercise_ar);
                    }
                    
                  }
                  /*end MDT_PHYSICAL_EXERCISE_ARCHIVE*/
                  /*MDT_INCIDENT_ARCHIVE*/
                  $table = MDT_INCIDENT . ' as mdt';
                  $match = array('mdt.mdt_report_id'=> $mdt_report_id);
                  $fields = array("mdt.*");
                  $incidents = $this->common_model->get_records($table, $fields,'', '', $match); 
                  
                  if(!empty($incidents))
                  {
                    foreach ($incidents as $row) {
                      
                        foreach ($row as $key => $value) {
                          $incidents_ar[$key] = $value;
                        }
                        $incidents_ar['archive_mdt_report_id'] = $archive_mdt_report_id;
                        $this->common_model->insert(MDT_INCIDENT_ARCHIVE, $incidents_ar);
                    }
                    
                  }
                  /*end MDT_INCIDENT_ARCHIVE*/
                  /*MDT_SANCTION_ARCHIVE*/
                  $table = MDT_SANCTION . ' as mdt';
                  $match = array('mdt.mdt_report_id'=> $mdt_report_id);
                  $fields = array("mdt.*");
                  $sanctions = $this->common_model->get_records($table, $fields,'', '', $match); 
                  
                  if(!empty($sanctions))
                  {
                    foreach ($sanctions as $row) {
                      
                        foreach ($row as $key => $value) {
                          $sanctions_ar[$key] = $value;
                        }
                        $sanctions_ar['archive_mdt_report_id'] = $archive_mdt_report_id;
                        $this->common_model->insert(MDT_SANCTION_ARCHIVE, $sanctions_ar);
                    }
                    
                  }
                  /*end MDT_SANCTION_ARCHIVE*/
                  /*MDT_LIFE_SKILLS_ARCHIVE*/
                  $table = MDT_LIFE_SKILLS . ' as mdt';
                  $match = array('mdt.mdt_report_id'=> $mdt_report_id);
                  $fields = array("mdt.*");
                  $lifeskills = $this->common_model->get_records($table, $fields,'', '', $match); 
                  
                  if(!empty($lifeskills))
                  {
                    foreach ($lifeskills as $row) {
                      
                        foreach ($row as $key => $value) {
                          $lifeskills_ar[$key] = $value;
                        }
                        $lifeskills_ar['archive_mdt_report_id'] = $archive_mdt_report_id;
                        $this->common_model->insert(MDT_LIFE_SKILLS_ARCHIVE, $lifeskills_ar);
                    }
                    
                  }
                  /*end MDT_LIFE_SKILLS_ARCHIVE*/
                  /*MDT_CARE_PLAN_TARGET_WEEK*/
                  $table = MDT_CARE_PLAN_TARGET_WEEK . ' as mdt';
                  $match = array('mdt.mdt_report_id'=> $mdt_report_id);
                  $fields = array("mdt.*");
                  $care_plan_target_week = $this->common_model->get_records($table, $fields,'', '', $match); 
                  
                  if(!empty($care_plan_target_week))
                  {
                    foreach ($care_plan_target_week as $row) {
                      
                        foreach ($row as $key => $value) {
                          $cpt_week_ar[$key] = $value;
                        }
                        $cpt_week_ar['archive_mdt_report_id'] = $archive_mdt_report_id;
                        $this->common_model->insert(MDT_CARE_PLAN_TARGET_WEEK_ARCHIVE, $cpt_week_ar);
                    }
                    
                  }
				  
                  /*end MDT_CARE_PLAN_TARGET_WEEK*/
                  /*MDT_CARE_PLAN_TARGET_PREVIOUS*/
                  $table = MDT_CARE_PLAN_TARGET_PREVIOUS . ' as mdt';
                  $match = array('mdt.mdt_report_id'=> $mdt_report_id);
                  $fields = array("mdt.*");
                  $care_plan_target_previous = $this->common_model->get_records($table, $fields,'', '', $match); 
                  
                  if(!empty($care_plan_target_previous))
                  {
                    foreach ($care_plan_target_previous as $row) {
                      
                        foreach ($row as $key => $value) {
                          $cpt_previous_ar[$key] = $value;
                        }
                        $cpt_previous_ar['archive_mdt_report_id'] = $archive_mdt_report_id;
                        $this->common_model->insert(MDT_CARE_PLAN_TARGET_PREVIOUS_ARCHIVE, $cpt_previous_ar);
                    }
                    
                  }
                  /*end MDT_CARE_PLAN_TARGET_PREVIOUS*/
                  $table_sa = MDT_REPORT_SIGNOFF;
                  $match_sa = array('yp_id'=> $id,'mdt_report_id'=> $mdt_report_id,"is_delete"=> "0");
                  $field_sa = array('*');
                  $signoff_details = $this->common_model->get_records($table_sa, $field_sa, '', '', $match_sa);
                  if($archive_mdt_report_id > 1){
                    $archive_mdt_report_id = $archive_mdt_report_id - 1 ;
                  }

                  if(!empty($signoff_details)){
                  foreach ($signoff_details as $signoff_data) {
                     
                        $signoff['yp_id'] = $signoff_data['yp_id'];
                        $signoff['archive_mdt_report_id'] = $archive_mdt_report_id;
                        $signoff['mdt_report_id'] = ucfirst($signoff_data['mdt_report_id']);
                        $signoff['created_by'] = $signoff_data['created_by'];
                        $signoff['created_date'] = $signoff_data['created_date'];
                        $this->common_model->insert(MDT_ARCHIVE_MDT_REPORT_SIGNOFF, $signoff);
                    }

                  $archive = array('is_delete'=>1);
                  $where = array('yp_id'=>$id);
                  $this->common_model->update(MDT_REPORT_SIGNOFF, $archive,$where);
               }

                   return TRUE;
              }
              else
              {
                    return TRUE;
              }
              
         }
         else
         {
               return TRUE;
         }
      }
      else
      {
          show_404 ();
      }
    }    
   
    /*
      @Author : Niral Patel
      @Desc   : sign off report
      @Input  :
      @Output :
      @Date   : 28/03/2018
     */
    public function signoff($yp_id='',$mdt_report_id='') {
        $this->formValidation();
		    $fields = array("care_home");
        $data['YP_details'] = YpDetails($yp_id,$fields);

        $data['care_home_id'] = $data['YP_details'][0]['care_home'];
        if ($this->form_validation->run() == FALSE) {

            $data['footerJs'][0] = base_url('uploads/custom/js/MDTReport/MDTReport.js');
            $data['crnt_view'] = $this->viewname;

            $data['ypid']= $yp_id;
            $data['mdt_report_id']= $mdt_report_id;
            //get current version
            $match = array('m.mdt_report_id'=>$mdt_report_id,'m.yp_id'=> $yp_id,'m.status'=>0);
            $fields_report = array("m.mdt_archive_id");
            $current_mtd_data = $this->common_model->get_records(MDT_REPORT_ARCHIVE.' as m',$fields_report,'', '', $match,'','1','','mdt_archive_id','desc');
            $data['mdt_archive_id'] = !empty($current_mtd_data[0]['mdt_archive_id'])?$current_mtd_data[0]['mdt_archive_id']:'';
            //Get Records From Login Table
            
            //get social info
            $table = SOCIAL_WORKER_DETAILS . ' as sw';
            $match = array("sw.yp_id" => $yp_id);
            $fields = array("sw.social_worker_id,sw.social_worker_firstname,sw.social_worker_surname");
            $data['social_worker_data'] = $this->common_model->get_records($table, $fields, '', '', $match);
            //get parent info
            $table = PARENT_CARER_INFORMATION . ' as pc';
            $match = array("pc.yp_id" => $yp_id,'pc.is_deleted' => 0);
            $fields = array("pc.parent_carer_id,pc.firstname,pc.surname");
            $data['parent_data'] = $this->common_model->get_records($table, $fields, '', '', $match);

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
      @Author : Niral Patel
      @Desc   : sign off report insert
      @Input  :
      @Output :
      @Date   : 28/03/2018
     */
    public function insertdata() {
        $postdata = $this->input->post();
        
        $ypid = $postdata['ypid'];
        $mdt_report_id = $postdata['mdt_report_id'];
        $mdt_archive_id = $postdata['mdt_archive_id'];
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
          $parent_data['care_home_id'] = $this->input->post('care_home_id');
          $success = $this->common_model->insert(PARENT_CARER_INFORMATION, $parent_data);
          //Insert log activity
          $activity = array(
              'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
              'yp_id' => !empty($postdata['ypid']) ? $postdata['ypid'] : '',
              'module_name' => MDT_PARENT_CARER_DETAILS_YP,
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
            $data['crnt_view'] = $this->viewname;
            $data = array(
                'user_type' => ucfirst($postdata['user_type']),
                'yp_id' => ucfirst($postdata['ypid']),
                'mdt_report_id' => ucfirst($postdata['mdt_report_id']),
                'mdt_archive_id' => ucfirst($postdata['mdt_archive_id']),
                'fname' => ucfirst($postdata['fname']),
                'lname' => ucfirst($postdata['lname']),
                'email' => $postdata['email'],
                'key_data' => md5($postdata['email']),
                'created_date' => datetimeformat(),
                'created_by' => $main_user_data['ID'],
                'updated_by' => $main_user_data['ID'],
                'care_home_id' => $postdata['care_home_id'],
            );
            
            //Insert Record in Database
            if ($this->common_model->insert(MDT_SIGNOFF_DETAILS, $data)) {

                $signoff_id = $this->db->insert_id();
                 $table = MDT_REPORT_SIGNOFF.' as pps';
                  $where =  array('pps.yp_id'=> $postdata['ypid'],'pps.mdt_report_id'=> $postdata['mdt_report_id'],"pps.is_delete"=> "0");
                  $fields = array("pps.created_by,pps.yp_id,pps.created_date");
                  $group_by = array('created_by');
                  $signoff_data = $this->common_model->get_records($table,$fields,'','','','','','','','',$group_by,$where);
                  
              if(!empty($signoff_data)){
                  foreach ($signoff_data as $archive_value) {
                      $update_arc_data['approval_mdt_id'] = $signoff_id;
                      $update_arc_data['yp_id'] = $archive_value['yp_id'];
                      $update_arc_data['created_date'] = $archive_value['created_date'];
                      $update_arc_data['created_by'] = $archive_value['created_by'];
                      $this->common_model->insert(MDT_APPROVAL_SIGNOFF,$update_arc_data);
                  }
              }

                $this->sendMailToRelation($data,$signoff_id); // send mail
                
                $msg = $this->lang->line('successfully_sign_off');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            } else {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
        
        redirect('MDTReviewReport/index/' . $ypid);
    }

    /*
      @Author : Niral Patel
      @Desc   : sign off report send mail
      @Input  :
      @Output :
      @Date   : 28/03/2018
     */
    private function sendMailToRelation($data = array(),$signoff_id) {

        if (!empty($data)) {
            if(!empty($data['yp_id']))
            {
                $match = array("yp_id" => $data['yp_id']);
                $fields = array("concat(yp_fname,' ',yp_lname) as yp_name");
                $YP_details = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
                
            }
            $yp_name = !empty($YP_details[0]['yp_name'])?$YP_details[0]['yp_name']:"";
            /* Send Created Customer password with Link */
            $toEmailId = $data['email'];
            $customerName = $data['fname'] . ' ' . $data['lname'];
            $email = md5($toEmailId);
            $loginLink = base_url('MDTReviewReport/signoffData/' . $data['yp_id'] . '/' . $signoff_id . '/' . $email);

            $find = array('{NAME}','{EMAIL}','{LINK}');

            $replace = array(
                'NAME' => $customerName,
                'EMAIL' => $toEmailId,
                'LINK' => $loginLink,
            );
            
            $emailSubject = 'Welcome to NFCTracker';
           
            $emailBody = '<div>'
                    . '<p>Hello {NAME} ,</p> '
                    . '<p>Please find MDT Report for '.$yp_name.' for your approval.</p> '
                    . "<p>For security purposes, Please do not forward this email on to any other person. It is for the recipient only and if this is sent in error please advise itsupport@newforestcare.co.uk and delete this email. This link is only valid for ".REPORT_EXPAIRED_HOUR.", should this not be signed off within ".REPORT_EXPAIRED_HOUR." of recieving then please request again</p>"
                    . '<p> <a href="{LINK}">click here</a> </p> '
                    . '<div>';
            $finalEmailBody = str_replace($find,$replace,$emailBody);

            return $this->common_model->sendEmail($toEmailId, $emailSubject, $finalEmailBody, FROM_EMAIL_ID);
        }
        return true;
    }
    /*
      @Author : Niral Patel
      @Desc   : sign off formValidation
      @Input  :
      @Output :
      @Date   : 28/03/2018
     */
     public function formValidation($id = null) {
        $this->form_validation->set_rules('fname', 'Firstname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('lname', 'Lastname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
        
    }
    /*
      @Author : Niral Patel
      @Desc   : sign off view data
      @Input  :
      @Output :
      @Date   : 28/03/2018
     */
    public function signoffData($id,$signoff_id,$email) {
        
       if(is_numeric($id) && is_numeric($signoff_id) && !empty($email))
       {
        $ypid = $id;
        
          $match = array('yp_id'=> $id,'status'=>'inactive','mdt_signoff_details_id'=>$signoff_id,'key_data'=> $email);
          $check_signoff_data = $this->common_model->get_records(MDT_SIGNOFF_DETAILS,array("*"), '', '', $match);

        if(!empty($check_signoff_data))
        {
          $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'].REPORT_EXPAIRED_DAYS));
          if(strtotime(datetimeformat()) <= strtotime($expairedDate))
          {
              $mdt_report_id = $check_signoff_data[0]['mdt_report_id'];
              $mdt_archive_id = $check_signoff_data[0]['mdt_archive_id'];
              $match = array('mdt_form_id'=> 1);
              $formsdata = $this->common_model->get_records(MDT_FORM,'', '', '', $match);
              if(!empty($formsdata))
              {
                  $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
              }
              //get YP information
               $table = YP_DETAILS . ' as yp';
              $match = "yp.yp_id = " . $id;
              $fields = array("yp.care_home,yp.yp_fname,yp.yp_lname,yp.date_of_birth,swd.social_worker_firstname,swd.social_worker_surname,pa.authority");
              $join_tables = array(PLACING_AUTHORITY . ' as pa' => 'pa.yp_id= yp.yp_id', SOCIAL_WORKER_DETAILS . ' as swd' => 'swd.yp_id= yp.yp_id', OVERVIEW_OF_YOUNG_PERSON . ' as oyp' => 'oyp.yp_id= yp.yp_id');
              $data['YP_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
              if(empty($data['YP_details']))
              {
                  $msg = $this->lang->line('common_no_record_found');
                  $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                  redirect('YoungPerson/view/'.$id);
              }
              
              //get report data
              $match = array('mdt_archive_id'=>$mdt_archive_id,'yp_id'=> $ypid);
              $join_tables = array(LOGIN . ' as l' => 'l.login_id= m.case_manager');
              $fields = array("m.*,l.login_id,concat(l.firstname,' ',l.lastname) as case_manager_name");
              $data['edit_data'] = $this->common_model->get_records(MDT_REPORT_ARCHIVE.' as m',$fields,$join_tables, 'left', $match);
              if(!empty($data['edit_data']))
              {
              //do average day
              $table = DAILY_OBSERVATIONS . ' as do';
              $where = array("do.yp_id" => $ypid,"do.daily_observation_date >=" =>$data['edit_data'][0]['report_start_date'],"do.daily_observation_date <=" =>$data['edit_data'][0]['report_end_date']);
              $fields = array("do.do_id,do.yp_id,do.daily_observation_date,d.day");
               $join_tables = array(DO_FOODCONSUMED . ' as d' => 'd.do_id= do.do_id');
              $dodata = $this->common_model->get_records($table, $fields, $join_tables, 'left', $where,'','','','','','d.do_id');
              if(!empty($dodata))
              {
                $sum = 0;
                foreach ($dodata as $row) {
                  $sum +=$row['day'];
                }
                $diet_avg = round($sum/count($dodata));
              }
              $data['diet_avg'] = !empty($diet_avg)?$diet_avg:'0';
              //get MDT_CARE_PLAN_TARGET_ARCHIVE 
              $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
              $data['care_plan_target'] = $this->common_model->get_records(MDT_CARE_PLAN_TARGET_ARCHIVE, array("cpt_id,care_plan_target_title,care_plan_target_select,care_plan_target_reason"), '', '', $where1, '','','','','','','');
              //get MDT_REGULAR_HOBBIES_ARCHIVE 
              $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
              $data['hobbies_data'] = $this->common_model->get_records(MDT_REGULAR_HOBBIES_ARCHIVE, array("regular_hobby_id,regular_hobbies,regular_hobbies_duration,"), '', '', $where1, '','','','','','','');
              //get MDT_PHYSICAL_EXERCISE_ARCHIVE 
              $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
              $data['physical_exercise_data'] = $this->common_model->get_records(MDT_PHYSICAL_EXERCISE_ARCHIVE, array("physical_exercise_id,physical_exercise,physical_exercise_duration"), '', '', $where1, '','','','','','','');
              //get MDT_INCIDENT_ARCHIVE 
              $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
              $data['incident_data'] = $this->common_model->get_records(MDT_INCIDENT_ARCHIVE, array("incident_id,incident_summary,level"), '', '', $where1, '','','','','','','');
              
              /*MDT_INCIDENT Level*/
              $table_in = MDT_INCIDENT_ARCHIVE . ' as mdt';
              $match_in = array('archive_mdt_report_id'=>$mdt_archive_id);
              $fields_in = array("SUM(CASE WHEN mdt.level = 1 THEN 1 ELSE 0 END) as level1,SUM(CASE WHEN mdt.level = 2 THEN 1 ELSE 0 END) as level2,SUM(CASE WHEN mdt.level = 3 THEN 1 ELSE 0 END) as level3,SUM(CASE WHEN mdt.level = 4 THEN 1 ELSE 0 END) as level4");
              $join_tables_in = array(LOGIN . ' as l' => 'l.login_id= mdt.staff_initials');
              $data['incident_level'] = $this->common_model->get_records($table_in, $fields_in, $join_tables_in, 'left', $match_in,'','','','','','mdt.mdt_report_id'); 
             
              /*end MDT_INCIDENT*/
              //get MDT_SANCTION_ARCHIVE 
              $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
              $data['sanction_data'] = $this->common_model->get_records(MDT_SANCTION_ARCHIVE, array("sanction_id,reason_sanction,date_sanction,imposed_sanction"), '', '', $where1, '','','','','','','');
              
              //get MDT_LIFE_SKILLS_ARCHIVE 
              $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
              $data['life_skills_data'] = $this->common_model->get_records(MDT_LIFE_SKILLS_ARCHIVE, array("life_skills_id,area_of_development,progress_achieved,"), '', '', $where1, '','','','','','','');
               //get MDT_CARE_PLAN_TARGET_WEEK_ARCHIVE 
              $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
              $data['care_plan_target_week'] = $this->common_model->get_records(MDT_CARE_PLAN_TARGET_WEEK_ARCHIVE, array("cpt_week_id,care_plan_target_title,care_plan_target_select,care_plan_target_reason"), '', '', $where1, '','','','','','','');
              //get MDT_CARE_PLAN_TARGET_PREVIOUS_ARCHIVE 
              $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
              $data['care_plan_target_previous'] = $this->common_model->get_records(MDT_CARE_PLAN_TARGET_PREVIOUS_ARCHIVE, array("cpt_previous_id,care_plan_target_title,care_plan_target_select,care_plan_target_reason"), '', '', $where1, '','','','','','','');
              //get medical appointment
              $table_ap = MEDICAL_PROFESSIONALS_APPOINTMENT . ' as mc';
              $where_ap = array("mc.yp_id" => $ypid,"mc.is_delete"=>0,"mc.appointment_date >=" =>$data['edit_data'][0]['report_start_date'],"mc.appointment_date <=" =>$data['edit_data'][0]['report_end_date']);
              $fields_ap = array("mc.*,concat(if(mp.title != '',mp.title,''),' ',if(mp.first_name != '',mp.first_name,''),' ',if(mp.surname != '',mp.surname,''),' - ',if(mp.professional != '',mp.professional,'')) as mp_name, concat(mp.house_namenumber,',',mp.street,' ',mp.town,',',mp.city,',',mp.postcode) as address");
              $join_tables_ap = array(MEDICAL_PROFESSIONALS . ' as mp' => 'mc.mp_id= mp.mp_id',YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id');
              $data['appointments'] = $this->common_model->get_records($table_ap, $fields_ap, $join_tables_ap, 'left', $where_ap, '', '', '', 'appointment_id', 'desc', '');
              }
              /*end MDT_LIFE_SKILLS*/
              
               $table = MDT_APPROVAL_SIGNOFF.' as pps';
               $where = array("l.is_delete"=> "0","pps.yp_id" => $ypid,"pps.is_delete"=> "0","pps.approval_mdt_id" =>$signoff_id);
                  $fields = array("pps.created_by,pps.created_date,pps.yp_id,pps.approval_mdt_id, CONCAT(`firstname`,' ', `lastname`) as name");
                  $join_tables = array(LOGIN . ' as l' => 'l.login_id=pps.created_by');
                  $group_by = array('created_by');
                  $data['signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);
              $table_si = MDT_REPORT_SIGNOFF.' as ims';
              $where_si = array("ims.yp_id" => $ypid,"ims.is_delete"=> "0","ims.mdt_report_id"=> $mdt_report_id);
              $fields_si = array("ims.*");  
              $data['check_signoff_data'] = $this->common_model->get_records($table_si,$fields_si,'','','','','','','','','',$where_si);

              //get ra old yp data
              $data['signoff_id'] = $signoff_id;
              $data['key_data']= $email;
              $data['ypid'] = $id;
              $data['mdt_report_id'] = $mdt_report_id;
              
              $data['header'] = array('menu_module' => 'YoungPerson');
              $data['footerJs'][0] = base_url('uploads/custom/js/MDTReport/MDTReport.js');
              $data['crnt_view'] = $this->viewname;
              $data['main_content'] = '/signoff_view';
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
              
              $msg = $this->lang->line('already_mdt_review');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              $this->load->view('successfully_message');
        }
         //get pp form
        }else
        {
              show_404();exit;
        }
    }
    
    /*
      @Author : Niral Patel
      @Desc   : sign off review data
      @Input  :
      @Output :
      @Date   : 28/03/2018
     */
    public function signoff_review_data($yp_id,$signoff_id,$email) {
      if (!empty($yp_id) && !empty($signoff_id) && !empty($email)) {
          $match = array('yp_id'=> $yp_id,'status'=>'inactive','mdt_signoff_details_id'=>$signoff_id,'key_data'=> $email);
          $check_signoff_data = $this->common_model->get_records(MDT_SIGNOFF_DETAILS,array("created_date,key_data,mdt_signoff_details_id,created_by"), '', '', $match);
          if(!empty($check_signoff_data))
          {
              $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'].REPORT_EXPAIRED_DAYS));
              if(strtotime(datetimeformat()) <= strtotime($expairedDate))
              {
                  $u_data['status'] = 'active';
                  $u_data['modified_date'] = datetimeformat();
                  $success =$this->common_model->update(MDT_SIGNOFF_DETAILS,$u_data,array('mdt_signoff_details_id'=> $signoff_id,'yp_id'=> $yp_id,'key_data'=> $email));
                if ($success) {
                  
                  $msg = $this->lang->line('successfully_mdt_review');
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
              $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>")
              ;
            }
      }else{
        $msg = $this->lang->line('already_mdt_review');
      $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>");

      }
      }else{      
      $msg = $this->lang->line('error_msg');
      $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>");

      }
      $this->load->view('successfully_message');
    }
    /*
      @Author : Niral Patel
      @Desc   : get user type detail
      @Input  :
      @Output :
      @Date   : 28/03/2018
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
      @Author : Niral Patel
      @Desc   : External Approve LIst Page
      @Input  :
      @Output :
      @Date   : 12/04/2018
     */

    public function external_approve($mdt_report_id) {
		
        if (is_numeric($mdt_report_id)) {
            //get YP information
            $match = array('mdt_report_id'=> $mdt_report_id);
            $mdt_data = $this->common_model->get_records(MDT_REPORT,array("yp_id"), '', '', $match);
            if (empty($mdt_data)) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect($_SERVER['HTTP_REFERER']);
            }
            $ypid = !empty($mdt_data[0]['yp_id'])?$mdt_data[0]['yp_id']:'';
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
            $perpage = $this->perPage;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('mdt_external_approve_data');
            }

            $searchsort_session = $this->session->userdata('mdt_external_approve_data');
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
                    $sortfield = 'mdt_signoff_details_id';
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
                    $config['per_page'] = $this->perPage;
                    $data['perpage'] = $this->perPage;
                }
            }
            //pagination configuration
            $config['first_link'] = 'First';
            $config['base_url'] = base_url() . $this->viewname . '/external_approve/' . $mdt_report_id;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 4;
                $uri_segment = $this->uri->segment(4);
            }
            //Query

            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $table = MDT_SIGNOFF_DETAILS . ' as m';
            $where = array("m.mdt_report_id" => $mdt_report_id);
            $fields = array("c.care_home_name,m.user_type,m.fname,m.lname,m.created_date,m.modified_date,m.status,m.mdt_archive_id,m.mdt_report_id,m.mdt_signoff_details_id,CONCAT(`firstname`,' ', `lastname`) as create_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= m.created_by',CARE_HOME . ' as c' => 'c.care_home_id= m.care_home_id');
			//MDT_REPORT
            if (!empty($searchtext)) {
                
            } else {
                $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
				        $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
            }
          
            $data['mdt_report_id'] = $mdt_report_id;
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

            $this->session->set_userdata('mdt_external_approve_data', $sortsearchpage_data);
            setActiveSession('mdt_external_approve_data'); // set current Session active
            $data['header'] = array('menu_module' => 'YoungPerson');

            
            $data['crnt_view'] = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/mdtreporthome/mdtreporthome.js');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/external_ajaxlist', $data);
            } else {
                $data['main_content'] = '/external_list';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }
    /*
      @Author : Niral Patel
      @Desc   : external approve view data
      @Input  :
      @Output :
      @Date   : 12/04/2018
     */
    public function external_view($mdt_archive_id,$signoff_id) {
        
       if(is_numeric($mdt_archive_id) && is_numeric($signoff_id))
       { 
          //get report data
          $match = array('mdt_archive_id'=>$mdt_archive_id);
          $join_tables = array(LOGIN . ' as l' => 'l.login_id= m.case_manager');
          $fields = array("m.*,l.login_id,concat(l.firstname,' ',l.lastname) as case_manager_name");
          $data['edit_data'] = $this->common_model->get_records(MDT_REPORT_ARCHIVE.' as m',$fields,$join_tables, 'left', $match);

          if(!empty($data['edit_data']))
          {
            $ypid = $data['edit_data'][0]['yp_id'];
             //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($ypid,$fields);
            $mdt_report_id = $data['edit_data'][0]['mdt_report_id'];
            //do average day
            $table = DAILY_OBSERVATIONS . ' as do';
            $where = array("do.daily_observation_date >=" =>$data['edit_data'][0]['report_start_date'],"do.daily_observation_date <=" =>$data['edit_data'][0]['report_end_date']);
            $fields = array("do.do_id,do.yp_id,do.daily_observation_date,d.day");
             $join_tables = array(DO_FOODCONSUMED . ' as d' => 'd.do_id= do.do_id');
            $dodata = $this->common_model->get_records($table, $fields, $join_tables, 'left', $where,'','','','','','d.do_id');
            if(!empty($dodata))
            {
              $sum = 0;
              foreach ($dodata as $row) {
                $sum +=$row['day'];
              }
              $diet_avg = round($sum/count($dodata));
            }
            $data['diet_avg'] = !empty($diet_avg)?$diet_avg:'0';
            //get MDT_CARE_PLAN_TARGET_ARCHIVE 
            $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
            $fields = array("cpt_id,care_plan_target_title,care_plan_target_select,care_plan_target_reason");
            $data['care_plan_target'] = $this->common_model->get_records(MDT_CARE_PLAN_TARGET_ARCHIVE, $fields, '', '', $where1, '','','','','','','');
            //get MDT_REGULAR_HOBBIES_ARCHIVE 
            $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
            $fields_rb = array("regular_hobby_id,regular_hobbies,regular_hobbies_duration");
            $data['hobbies_data'] = $this->common_model->get_records(MDT_REGULAR_HOBBIES_ARCHIVE, $fields_rb, '', '', $where1, '','','','','','','');

            //get MDT_PHYSICAL_EXERCISE_ARCHIVE 
            $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
            $fields_pe = array("physical_exercise_id,physical_exercise,physical_exercise_duration");
            $data['physical_exercise_data'] = $this->common_model->get_records(MDT_PHYSICAL_EXERCISE_ARCHIVE, $fields_pe, '', '', $where1, '','','','','','','');
            //get MDT_INCIDENT_ARCHIVE 
            $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
            $data['incident_data'] = $this->common_model->get_records(MDT_INCIDENT_ARCHIVE, array("incident_id,incident_summary,level"), '', '', $where1, '','','','','','','');
            /*MDT_INCIDENT Level*/
              $table_in = MDT_INCIDENT_ARCHIVE . ' as mdt';
              $match_in = array('archive_mdt_report_id'=>$mdt_archive_id);
              $fields_in = array("SUM(CASE WHEN mdt.level = 1 THEN 1 ELSE 0 END) as level1,SUM(CASE WHEN mdt.level = 2 THEN 1 ELSE 0 END) as level2,SUM(CASE WHEN mdt.level = 3 THEN 1 ELSE 0 END) as level3,SUM(CASE WHEN mdt.level = 4 THEN 1 ELSE 0 END) as level4");
              $join_tables_in = array(LOGIN . ' as l' => 'l.login_id= mdt.staff_initials');
              $data['incident_level'] = $this->common_model->get_records($table_in, $fields_in, $join_tables_in, 'left', $match_in,'','','','','','mdt.mdt_report_id'); 
             
              /*end MDT_INCIDENT*/
            //get MDT_SANCTION_ARCHIVE 
            $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
            $fields_sa = array("sanction_id,reason_sanction,imposed_sanction,date_sanction");
            $data['sanction_data'] = $this->common_model->get_records(MDT_SANCTION_ARCHIVE, $fields_sa, '', '', $where1, '','','','','','','');
            
            //get MDT_LIFE_SKILLS_ARCHIVE 
            $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
            $fields_ls = array("life_skills_id,area_of_development,progress_achieved");
            $data['life_skills_data'] = $this->common_model->get_records(MDT_LIFE_SKILLS_ARCHIVE, $fields_ls, '', '', $where1, '','','','','','','');
             //get MDT_CARE_PLAN_TARGET_WEEK_ARCHIVE 
            $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
            $fields = array("cpt_week_id,care_plan_target_title,care_plan_target_select,care_plan_target_reason");
            $data['care_plan_target_week'] = $this->common_model->get_records(MDT_CARE_PLAN_TARGET_WEEK_ARCHIVE, $fields, '', '', $where1, '','','','','','','');

            //get MDT_CARE_PLAN_TARGET_PREVIOUS_ARCHIVE 
            $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
            $fields = array("cpt_previous_id,care_plan_target_title,care_plan_target_select,care_plan_target_reason");
            $data['care_plan_target_previous'] = $this->common_model->get_records(MDT_CARE_PLAN_TARGET_PREVIOUS_ARCHIVE, $fields, '', '', $where1, '','','','','','','');
            //get medical appointment
            /*medical approintment query start*/
              $table_ap = MEDICAL_PROFESSIONALS_APPOINTMENT . ' as mc';
              $where_ap = array("mc.yp_id" => $ypid,"mc.is_delete"=>0,"mc.appointment_date >=" =>$data['edit_data'][0]['report_start_date'],"mc.appointment_date <=" =>$data['edit_data'][0]['report_end_date']);
              $fields_ap = array("mc.*,concat(if(mp.title != '',mp.title,''),' ',if(mp.first_name != '',mp.first_name,''),' ',if(mp.surname != '',mp.surname,''),' - ',if(mp.professional != '',mp.professional,'')) as mp_name, concat(mp.house_namenumber,',',mp.street,' ',mp.town,',',mp.city,',',mp.postcode) as address");
              $join_tables_ap = array(MEDICAL_PROFESSIONALS . ' as mp' => 'mc.mp_id= mp.mp_id',YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id');
              $data['appointments'] = $this->common_model->get_records($table_ap, $fields_ap, $join_tables_ap, 'left', $where_ap, '', '', '', 'appointment_id', 'desc', '');
              /*end query medical appointment*/
          }
          /*end MDT_LIFE_SKILLS*/

           $match = array('mdt_form_id'=> 1);
              $formsdata = $this->common_model->get_records(MDT_FORM,array("form_json_data"), '', '', $match);
            
              if(!empty($formsdata))
              {
                  $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
              }
          
          //get ra old yp data
           $table = MDT_APPROVAL_SIGNOFF.' as pps';
          $where = array("l.is_delete"=> "0","pps.yp_id" => $ypid,"pps.is_delete"=> "0","pps.approval_mdt_id" =>$signoff_id);
            $fields = array("pps.created_by,pps.created_date,pps.yp_id,pps.approval_mdt_id, CONCAT(`firstname`,' ', `lastname`) as name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id=pps.created_by');
            $group_by = array('created_by');
            $data['signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);
          $data['ypid'] = $ypid;
          $data['mdt_report_id'] = $mdt_report_id;
          $data['mdt_archive_id'] = $mdt_archive_id;
          
          $data['header'] = array('menu_module' => 'YoungPerson');
          $data['footerJs'][0] = base_url('uploads/custom/js/MDTReport/MDTReport.js');
          $data['crnt_view'] = $this->viewname;
          $data['external_view'] = 1;
		      
          $data['main_content'] = '/signoff_view';
          $this->parser->parse('layouts/DefaultTemplate', $data);
              
         //get pp form
        }else
        {
              show_404();exit;
        }
    }
    /*
      @Author : Niral Patel
      @Desc   : external approve view data
      @Input  :
      @Output :
      @Date   : 12/04/2018
     */
    
     public function resend_external_approval($signoff_id,$ypid) {
      $match = array('mdt_signoff_details_id'=>$signoff_id);
      $signoff_data = $this->common_model->get_records(MDT_SIGNOFF_DETAILS,array("*"), '', '', $match);
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
          $success =$this->common_model->update(MDT_SIGNOFF_DETAILS,$u_data,array('mdt_signoff_details_id'=> $signoff_id));
          $msg = $this->lang->line('mail_sent_successfully');
          $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
        else
        {
          $msg = $this->lang->line('error');
          $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
      }
     
      redirect($this->viewname.'/external_approve/' . $ypid);
     }
	 
	 /*
      @Author : Nikunj Ghelani
      @Desc   : MDT Report delete
      @Input  :
      @Output :
      @Date   : 18/07/2018
     */
   public function DownloadPDF_after_mail($id,$signoff_id) {
	  
	  
	   if(is_numeric($id) && is_numeric($signoff_id))
       {
        $ypid = $id;
        
          $match = array('yp_id'=> $id,'status'=>'inactive','mdt_signoff_details_id'=>$signoff_id);
          $check_signoff_data = $this->common_model->get_records(MDT_SIGNOFF_DETAILS,array("created_date,mdt_report_id,key_data,mdt_archive_id"), '', '', $match);
        if(!empty($check_signoff_data))
        {
			
          $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'].REPORT_EXPAIRED_DAYS));
		  
          if(strtotime(datetimeformat()) <= strtotime($expairedDate))
          {
              $mdt_report_id = $check_signoff_data[0]['mdt_report_id'];
              $mdt_archive_id = $check_signoff_data[0]['mdt_archive_id'];
              $match = array('mdt_form_id'=> 1);
              $formsdata = $this->common_model->get_records(MDT_FORM,array("form_json_data"), '', '', $match);
			        if(!empty($formsdata))
              {
                  $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
              }
			        //get YP information
              $table = YP_DETAILS . ' as yp';
              $match = "yp.yp_id = " . $id;
              $fields = array("yp.care_home,yp.yp_fname,yp.yp_lname,yp.date_of_birth,pa.authority,swd.social_worker_firstname,swd.social_worker_surname");
              $join_tables = array(PLACING_AUTHORITY . ' as pa' => 'pa.yp_id= yp.yp_id', SOCIAL_WORKER_DETAILS . ' as swd' => 'swd.yp_id= yp.yp_id', OVERVIEW_OF_YOUNG_PERSON . ' as oyp' => 'oyp.yp_id= yp.yp_id');
              $data['YP_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
              
              if(empty($data['YP_details']))
              {
                  $msg = $this->lang->line('common_no_record_found');
                  $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                  redirect('YoungPerson/view/'.$id);
              }
              
              //get report data
              $match = array('mdt_archive_id'=>$mdt_archive_id,'yp_id'=> $ypid);
              $join_tables = array(LOGIN . ' as l' => 'l.login_id= m.case_manager');
              $fields = array("m.*,l.login_id,concat(l.firstname,' ',l.lastname) as case_manager_name");
              $data['edit_data'] = $this->common_model->get_records(MDT_REPORT_ARCHIVE.' as m',$fields,$join_tables, 'left', $match);

              if(!empty($data['edit_data']))
              {
              //do average day
              $table = DAILY_OBSERVATIONS . ' as do';
              $where = array("do.yp_id" => $ypid,"do.daily_observation_date >=" =>$data['edit_data'][0]['report_start_date'],"do.daily_observation_date <=" =>$data['edit_data'][0]['report_end_date']);
              $fields = array("do.do_id,do.yp_id,do.daily_observation_date,d.day");
               $join_tables = array(DO_FOODCONSUMED . ' as d' => 'd.do_id= do.do_id');
              $dodata = $this->common_model->get_records($table, $fields, $join_tables, 'left', $where,'','','','','','d.do_id');
              if(!empty($dodata))
              {
                $sum = 0;
                foreach ($dodata as $row) {
                  $sum +=$row['day'];
                }
                $diet_avg = round($sum/count($dodata));
              }
              $data['diet_avg'] = !empty($diet_avg)?$diet_avg:'0';
              //get MDT_CARE_PLAN_TARGET_ARCHIVE 
              $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
              $fields_cpt = array("cpt_id,care_plan_target_title,care_plan_target_select,care_plan_target_reason");
              $data['care_plan_target'] = $this->common_model->get_records(MDT_CARE_PLAN_TARGET_ARCHIVE, $fields_cpt, '', '', $where1, '','','','','','','');
              //get MDT_REGULAR_HOBBIES_ARCHIVE 
              $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
              $fields_rb = array("regular_hobby_id,regular_hobbies,regular_hobbies_duration");
              $data['hobbies_data'] = $this->common_model->get_records(MDT_REGULAR_HOBBIES_ARCHIVE, $fields_rb, '', '', $where1, '','','','','','','');
              //get MDT_PHYSICAL_EXERCISE_ARCHIVE 
              $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
              $fields_pe = array("physical_exercise_id,physical_exercise,physical_exercise_duration");
              $data['physical_exercise_data'] = $this->common_model->get_records(MDT_PHYSICAL_EXERCISE_ARCHIVE, $fields_pe, '', '', $where1, '','','','','','','');
              
              //get MDT_INCIDENT_ARCHIVE 
              $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
              $fields = array("incident_summary,level,incident_id");
              $data['incident_data'] = $this->common_model->get_records(MDT_INCIDENT_ARCHIVE, $fields, '', '', $where1, '','','','','','','');

              /*MDT_INCIDENT Level*/
              $table_in = MDT_INCIDENT_ARCHIVE . ' as mdt';
              $match_in = array('archive_mdt_report_id'=>$mdt_archive_id);
              $fields_in = array("SUM(CASE WHEN mdt.level = 1 THEN 1 ELSE 0 END) as level1,SUM(CASE WHEN mdt.level = 2 THEN 1 ELSE 0 END) as level2,SUM(CASE WHEN mdt.level = 3 THEN 1 ELSE 0 END) as level3,SUM(CASE WHEN mdt.level = 4 THEN 1 ELSE 0 END) as level4");
              $join_tables_in = array(LOGIN . ' as l' => 'l.login_id= mdt.staff_initials');
              $data['incident_level'] = $this->common_model->get_records($table_in, $fields_in, $join_tables_in, 'left', $match_in,'','','','','','mdt.mdt_report_id'); 
               
              /*end MDT_INCIDENT*/
              //get MDT_SANCTION_ARCHIVE 
              $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
              $fields_sa = array("sanction_id,reason_sanction,imposed_sanction,date_sanction");
              $data['sanction_data'] = $this->common_model->get_records(MDT_SANCTION_ARCHIVE, $fields_sa, '', '', $where1, '','','','','','','');

              //get MDT_LIFE_SKILLS_ARCHIVE 
              $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
              $fields_ls = array("life_skills_id,area_of_development,progress_achieved");
              $data['life_skills_data'] = $this->common_model->get_records(MDT_LIFE_SKILLS_ARCHIVE, $fields_ls, '', '', $where1, '','','','','','','');

               //get MDT_CARE_PLAN_TARGET_WEEK_ARCHIVE 
              $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
              $fields = array("cpt_week_id,care_plan_target_title,care_plan_target_select,care_plan_target_reason");
              $data['care_plan_target_week'] = $this->common_model->get_records(MDT_CARE_PLAN_TARGET_WEEK_ARCHIVE, $fields, '', '', $where1, '','','','','','','');
              //get MDT_CARE_PLAN_TARGET_PREVIOUS_ARCHIVE 
              $where1 = array('archive_mdt_report_id' => $mdt_archive_id);
              $data['care_plan_target_previous'] = $this->common_model->get_records(MDT_CARE_PLAN_TARGET_PREVIOUS_ARCHIVE, '', '', '', $where1, '','','','','','','');
              //get medical appointment
              $table_ap = MEDICAL_PROFESSIONALS_APPOINTMENT . ' as mc';
              $where_ap = array("mc.yp_id" => $ypid,"mc.is_delete"=>0,"mc.appointment_date >=" =>$data['edit_data'][0]['report_start_date'],"mc.appointment_date <=" =>$data['edit_data'][0]['report_end_date']);
              $fields_ap = array("mc.*,concat(if(mp.title != '',mp.title,''),' ',if(mp.first_name != '',mp.first_name,''),' ',if(mp.surname != '',mp.surname,''),' - ',if(mp.professional != '',mp.professional,'')) as mp_name, concat(mp.house_namenumber,',',mp.street,' ',mp.town,',',mp.city,',',mp.postcode) as address");
              $join_tables_ap = array(MEDICAL_PROFESSIONALS . ' as mp' => 'mc.mp_id= mp.mp_id',YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id');
              $data['appointments'] = $this->common_model->get_records($table_ap, $fields_ap, $join_tables_ap, 'left', $where_ap, '', '', '', 'appointment_id', 'desc', '');
              }

              /*end MDT_LIFE_SKILLS*/
              
               $table = MDT_APPROVAL_SIGNOFF.' as pps';
                $where = array("l.is_delete"=> "0","pps.yp_id" => $ypid,"pps.is_delete"=> "0","pps.approval_mdt_id" =>$signoff_id);
                  $fields = array("pps.created_by,pps.created_date,pps.yp_id,pps.approval_mdt_id, CONCAT(`firstname`,' ', `lastname`) as name");
                  $join_tables = array(LOGIN . ' as l' => 'l.login_id=pps.created_by');
                  $group_by = array('created_by');
                  $data['signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);

              $table_si = MDT_REPORT_SIGNOFF.' as ims';
              $where_si = array("ims.yp_id" => $ypid,"ims.is_delete"=> "0","ims.mdt_report_id"=> $mdt_report_id);
              $fields_si = array("ims.*");  
              $data['check_signoff_data'] = $this->common_model->get_records($table_si,$fields_si,'','','','','','','','','',$where_si);


              //get ra old yp data
              $data['signoff_id'] = $signoff_id;
              $data['key_data']= $email;
              $data['ypid'] = $id;
              $data['mdt_report_id'] = $mdt_report_id;
              
              $data['header'] = array('menu_module' => 'YoungPerson');
              $data['footerJs'][0] = base_url('uploads/custom/js/MDTReport/MDTReport.js');
              $data['crnt_view'] = $this->viewname;
			 
               $pdfFileName = "mdtreview.pdf";
            $PDFInformation['yp_details'] = $data['YP_details'][0];
            $PDFInformation['edit_data'] = $data['edit_data'][0]['modified_date'];
			
			
            $PDFHeaderHTML  = $this->load->view('mdtreview_pdfHeader', $PDFInformation,true);
			
            $PDFFooterHTML  = $this->load->view('mdtreview_pdfFooter', $PDFInformation,true);
			
			
            //Set Header Footer and Content For PDF
            $this->m_pdf->pdf->mPDF('utf-8','A4','','','10','10','45','25');
	
            $this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
            $this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);                    
            $data['main_content'] = '/mdtreview';
            $html = $this->parser->parse('layouts/PdfDataTemplate', $data);
			
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
              
              $msg = $this->lang->line('already_mdt_review');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              $this->load->view('successfully_message');
        }
         //get pp form
        }else
        {
              show_404();exit;
        }
		 
   }
	 
}
