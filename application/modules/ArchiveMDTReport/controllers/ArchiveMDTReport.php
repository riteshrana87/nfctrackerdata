<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ArchiveMDTReport extends CI_Controller {

    function __construct() {

        parent::__construct();
        if(checkPermission('ArchiveMDTReport','view') == false)
        {
            redirect('/Dashboard');
        }
        $this->viewname = $this->router->fetch_class ();
        $this->method   = $this->router->fetch_method();
    }

    /*
      @Author : Niral Patel
      @Desc   : ArchiveMDTReport Index Page
      @Input 	: yp id
      @Output	:
      @Date   : 30/03/2018
     */

    public function index($id,$careHomeId=0,$isArchive=0) {
		
       if(is_numeric($id))
       {
        //get YP information
        $match = array("mdt_report_id"=>$id);
        $fields = array("yp_id");
        $data['MDT_report'] = $this->common_model->get_records(MDT_REPORT, $fields, '', '', $match);
        if(empty($data['MDT_report']))
        {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson');
        }
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($data['MDT_report'][0]['yp_id'],$fields);
        
        $searchtext = $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('archive_mdt_data');
        }

        $searchsort_session = $this->session->userdata('archive_mdt_data');
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
                $sortfield = 'mdt_archive_id';
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
                $config['per_page'] = $perpage;
                $data['perpage'] = $perpage;
            }
        }
        //pagination configuration
        $config['first_link'] = 'First';
        $config['base_url'] = base_url() . $this->viewname . '/index/'.$id;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
			if($isArchive==0){
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
			}else{
			$config['uri_segment'] = 6;
            $uri_segment = $this->uri->segment(6);	
			}
        }
        //Query

        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = MDT_REPORT_ARCHIVE . ' as do';
        $where = array("do.mdt_report_id"=>$id,'do.status'=>1);
        $fields = array("c.care_home_name,do.*,CONCAT(`firstname`,' ', `lastname`) as create_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by',CARE_HOME . ' as c' => 'c.care_home_id= do.care_home_id');
		
		
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
		
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
        }

        $data['ypid'] = $data['MDT_report'][0]['yp_id'];
        $data['id'] = $id;
		
		/*18-9-18
		ghelani nikunj
		this 2 variable passed because need to add condition for button hide
		*/
        $data['is_archive_page'] = $isArchive;
        $data['careHomeId'] = $careHomeId;
		/******************************************************************/
		
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

        $this->session->set_userdata('archive_mdt_data', $sortsearchpage_data);
        setActiveSession('archive_mdt_data'); // set current Session active
        $data['header'] = array('menu_module' => 'YoungPerson');

        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/mdtreporthome/mdtreporthome.js');
        if ($this->input->is_ajax_request ()) {
            $this->load->view($this->viewname . '/archive_ajax', $data);
        } else {
            $data['main_content'] = '/archive';
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
      @Desc   : View archive
      @Input    :
      @Output   :
      @Date   : 30/03/2018
     */
    public function view($id,$ypid,$careHomeId=0,$isArchive=0)                         
    {
      if(is_numeric($id) && is_numeric($ypid))
       {
         
          //get YP information
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($ypid,$fields);
          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson');
          }
          //get pp form
         $match = array('mdt_form_id'=> 1);
         $formsdata = $this->common_model->get_records(MDT_FORM,array("form_json_data"), '', '', $match);
         if(!empty($formsdata))
         {
              $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
         }
           //get report data
          $match_report = array('m.mdt_archive_id'=>$id,'m.yp_id'=> $ypid,'m.status'=>1);
          $join_tables_report = array(LOGIN . ' as l' => 'l.login_id= m.case_manager');
          $fields_report = array("m.*,l.login_id,concat(l.firstname,' ',l.lastname) as case_manager_name");
          $data['edit_data'] = $this->common_model->get_records(MDT_REPORT_ARCHIVE.' as m',$fields_report,$join_tables_report, 'left', $match_report,'1','','mdt_archive_id','desc');
          
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
          if(!empty($data['edit_data']))
          {
            $mdt_archive_id = $data['edit_data'][0]['mdt_archive_id'];
            $mdt_report_id = $data['edit_data'][0]['mdt_report_id'];
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
            $fields_in = array("incident_summary,level,incident_id");
            $data['incident_data'] = $this->common_model->get_records(MDT_INCIDENT_ARCHIVE, $fields_in, '', '', $where1, '','','','','','','');
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
            /*medical approintment query start*/
            $table_ap = MEDICAL_PROFESSIONALS_APPOINTMENT . ' as mc';
              $where_ap = array("mc.yp_id" => $ypid,"mc.is_delete"=>0,"mc.appointment_date >=" =>$data['edit_data'][0]['report_start_date'],"mc.appointment_date <=" =>$data['edit_data'][0]['report_end_date']);
              $fields_ap = array("mc.*,concat(if(mp.title != '',mp.title,''),' ',if(mp.first_name != '',mp.first_name,''),' ',if(mp.surname != '',mp.surname,''),' - ',if(mp.professional != '',mp.professional,'')) as mp_name, concat(mp.house_namenumber,',',mp.street,' ',mp.town,',',mp.city,',',mp.postcode) as address");
              $join_tables_ap = array(MEDICAL_PROFESSIONALS . ' as mp' => 'mc.mp_id= mp.mp_id',YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id');
              $data['appointments'] = $this->common_model->get_records($table_ap, $fields_ap, $join_tables_ap, 'left', $where_ap, '', '', '', 'appointment_id', 'desc', '');
              
              /*medical approintment query start*/
          }
          //get ra old yp data
          $match = array('m.mdt_archive_id <'=> $id,'m.mdt_report_id'=>$mdt_report_id,'m.yp_id'=> $ypid,'m.status'=>1);
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

            $table = MDT_ARCHIVE_MDT_REPORT_SIGNOFF . ' as aims';
            $match = array('aims.yp_id'=> $ypid,'aims.archive_mdt_report_id' => $id);
            $fields = array("aims.*,CONCAT(l.firstname,' ', l.lastname) as create_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= aims.created_by');
            $data['signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
           
          $data['ypid'] = $ypid;
          $data['id'] = $id;
          $data['is_archive_page'] = $isArchive;
          $data['careHomeId'] = $careHomeId;
          $data['mdt_report_id'] = $data['edit_data'][0]['mdt_report_id'];
          $data['footerJs'][0] = base_url('uploads/custom/js/placementplan/placementplan.js');
          $data['crnt_view'] = $this->viewname;
          $data['header'] = array('menu_module' => 'YoungPerson');
          $data['main_content'] = '/view';
          $this->parser->parse('layouts/DefaultTemplate', $data);
        }
        else
        {
            show_404 ();
        }
    }
}
