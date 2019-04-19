<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ArchiveIndividualStrategies extends CI_Controller {

    function __construct() {

        parent::__construct();
        if(checkPermission('ArchiveIndividualStrategies','view') == false)
        {
            redirect('/Dashboard');
        }
        $this->viewname = $this->router->fetch_class ();
        $this->method   = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Niral Patel
      @Desc   : ArchiveRiskAssesment Index Page
      @Input 	: yp id
      @Output	:
      @Date   : 18/08/2017
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
		$search_date = $this->input->post('search_date');
		$formated_date = dateformat($search_date);
		if($this->input->post('search_start_time')!=''){
         $search_start_time = date("H:i", strtotime($this->input->post('search_start_time')));
		}else{
			$search_start_time='';
		}
		if($this->input->post('search_end_time')!=''){
         $search_end_time = date("H:i", strtotime($this->input->post('search_end_time')));
		}else{
			$search_end_time='';
		}
		
       if(is_numeric($ypid))
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
        $searchtext = $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('archive_is_data');
        }

        $searchsort_session = $this->session->userdata('archive_is_data');
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
                $sortfield = 'is_archive_id';
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
        if($isArchive==0){
        $config['base_url'] = base_url() . $this->viewname . '/index/'.$ypid;

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
        $table = INDIVIDUAL_STRATEGIES_ARCHIVE . ' as do';
		if($isArchive==0){
			
        $where = "do.yp_id='".$ypid."' AND do.status = 1";
		 if (!empty($search_date)) {
                $where .= ' AND do.created_date LIKE "%' . $formated_date.'%"';
        }
        if (!empty($search_start_time)) { 
                $where .= ' AND do.modified_time BETWEEN  "' . $search_start_time.'" AND "'.$search_end_time.'"';
        }
        $fields = array("c.care_home_name,do.*,CONCAT(`firstname`,' ', `lastname`) as create_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by',CARE_HOME . ' as c' => 'c.care_home_id= do.care_home_id');
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
        }
		}else{
			
			$where = "do.yp_id='".$ypid."'";
			$where_date="do.created_date BETWEEN  '".$created_date."' AND '".$movedate."'";
				 if (!empty($search_date)) {
						$where .= ' AND do.created_date LIKE "%' . $formated_date.'%"';
				}
				if (!empty($search_start_time)) { 
						$where .= ' AND do.modified_time BETWEEN  "' . $search_start_time.'" AND "'.$search_end_time.'"';
				}
				$fields = array("c.care_home_name,do.*,CONCAT(`firstname`,' ', `lastname`) as create_name");
				$join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by',CARE_HOME . ' as c' => 'c.care_home_id= do.care_home_id');
				if (!empty($searchtext)) {
					
				} else {
					$data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where,'','','','','',$where_date);
					$config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1','','',$where_date);
				}
			
		}

        $data['ypid'] = $ypid;
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

        $this->session->set_userdata('archive_is_data', $sortsearchpage_data);
        setActiveSession('archive_is_data'); // set current Session active
        $data['header'] = array('menu_module' => 'YoungPerson');

        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/ArchiveIndividualStrategies/ArchiveIndividualStrategies.js');
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
      @Date   : 18/08/2017
     */
    public function view($id,$ypid,$careHomeId=0,$isArchive=0)                         
    {
		
		$data['is_archive_page'] = $isArchive;
            $data['careHomeId'] = $careHomeId;
      if(is_numeric($id) && is_numeric($ypid))
       {
         //get archive ra data
         $match = array('is_archive_id'=> $id,'yp_id'=> $ypid);
         $formsdata = $this->common_model->get_records(INDIVIDUAL_STRATEGIES_ARCHIVE,'', '', '', $match);
		     $data['formsdata'] = $formsdata;
		 
         if(!empty($formsdata))
         {  
            //get archive amendments
			        $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
              $is_archive_id = $formsdata[0]['is_archive_id'];
              $table = NFC_ARCHIVE_AMENDMENTS . ' as do';
              $match = array('do.yp_id'=> $ypid,'do.archive_individual_meeting_id' => $is_archive_id);
              $fields = array("do.amendments,do.created_date,do.amendments_modified_date,do.amendments_id,CONCAT(l.firstname,' ', l.lastname) as create_name");
              $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.staff_initials');
              $data['item_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
              
              //get archive current protocols in place data
            $table = NFC_ARCHIVE_CURRENT_PROTOCOLS_IN_PLACE . ' as cpip';
            $match = array('cpip.yp_id'=> $ypid,'cpip.archive_individual_meeting_id' => $is_archive_id);
            $fields = array("cpip.current_protocols_in_place,cpip.protocols_in_place_id,cpip.protocol_modified_date,CONCAT(l.firstname,' ', l.lastname) as create_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= cpip.staff_initials');
            $data['protocols_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
          }
         //get previous version
         $match = array('is_archive_id <'=> $id,'yp_id'=> $ypid,'status'=>1);
         $oldformsdata = $this->common_model->get_records(INDIVIDUAL_STRATEGIES_ARCHIVE,'', '', '', $match,'',1,'','is_archive_id','desc');
        if(!empty($oldformsdata))
         {
          //get old archive amendments
			        $data['form_old_data'] = json_decode($oldformsdata[0]['form_json_data'], TRUE);
              $is_archive_id = $oldformsdata[0]['is_archive_id'];
              $table = NFC_ARCHIVE_AMENDMENTS . ' as do';
              $match = array('do.yp_id'=> $ypid,'do.archive_individual_meeting_id' => $is_archive_id);
              $fields = array("do.amendments,do.created_date,do.amendments_modified_date,do.amendments_id,CONCAT(l.firstname,' ', l.lastname) as create_name");
              $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.staff_initials');
              $data['item_details_archive'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
              //get old archive current protocols in place data
              $table = NFC_ARCHIVE_CURRENT_PROTOCOLS_IN_PLACE . ' as cpip';
              $match = array('cpip.yp_id'=> $ypid,'cpip.archive_individual_meeting_id' => $is_archive_id);
              $fields = array("cpip.current_protocols_in_place,cpip.protocols_in_place_id,cpip.protocol_modified_date,CONCAT(l.firstname,' ', l.lastname) as create_name");
              $join_tables = array(LOGIN . ' as l' => 'l.login_id= cpip.staff_initials');
              $data['protocols_details_archive'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match); 
         }
          //get YP information
           $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
           $data['YP_details'] = YpDetails($ypid,$fields);
           
          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson');
          }
          $table = NFC_ARCHIVE_INDIVIDUAL_MEETING_SIGNOFF . ' as aims';
          $match = array('aims.yp_id'=> $ypid,'aims.archive_individual_meeting_id' => $id);
          $fields = array("aims.*,CONCAT(l.firstname,' ', l.lastname) as create_name");
          $join_tables = array(LOGIN . ' as l' => 'l.login_id= aims.created_by');
          $data['signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);

          $data['ypid'] = $ypid;
		      $data['id'] = $id;
		      $data['is_archive_page'] = $isArchive;
          $data['careHomeId'] = $careHomeId;
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
