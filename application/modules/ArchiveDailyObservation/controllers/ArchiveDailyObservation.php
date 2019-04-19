<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ArchiveDailyObservation extends CI_Controller {

    function __construct() {

        parent::__construct();
        if(checkPermission('ArchiveDailyObservation','view') == false)
        {
            redirect('/Dashboard');
        }
        $this->viewname = $this->router->fetch_class ();
        $this->method   = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Niral Patel
      @Desc   : ArchiveDailyObservation Index Page
      @Input 	: yp id
      @Output	:
      @Date   : 18/08/2017
     */

    public function index($id,$ypid,$care_home_id=0,$past_care_id=0) {
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
        $searchtext = $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('archive_do_data');
        }

        $searchsort_session = $this->session->userdata('archive_do_data');
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
                $sortfield = 'do_archive_id';
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
        /*
          Ritesh Rana
          for past care id inserted for archive full functionality
        */
        if($past_care_id == 0){
        $config['base_url'] = base_url() . $this->viewname . '/index/'.$id.'/'.$ypid;
        
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 5;
            $uri_segment = $this->uri->segment(5);
        }

        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = DO_ARCHIVE . ' as do';
        $where = array("do.do_id"=>$id,'do.status'=>1);
        $fields = array("do.created_date,do.do_archive_id,do.yp_id,CONCAT(`firstname`,' ', `lastname`) as create_name,ch.care_home_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = do.care_home_id');
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
        }


      }else{
        
        $config['base_url'] = base_url() . $this->viewname . '/index/'.$id.'/'.$ypid.'/'.$care_home_id.'/'.$past_care_id;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 7;
            $uri_segment = $this->uri->segment(7);
        }
        //Query

        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = DO_ARCHIVE . ' as do';
        $where = array("do.do_id"=>$id,'do.status'=>1);
        $where_date = "do.created_date BETWEEN  '".$created_date."' AND '".$movedate."'";
        $fields = array("do.created_date,do.do_archive_id,do.yp_id,CONCAT(`firstname`,' ', `lastname`) as create_name,ch.care_home_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = do.care_home_id');
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where,'','','','','',$where_date);

            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1','','',$where_date);
        }

      }
        
        $data['do_id'] = $id;
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

        $this->session->set_userdata('archive_do_data', $sortsearchpage_data);
        setActiveSession('archive_do_data'); // set current Session active
        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['care_home_id'] = $care_home_id;
        $data['past_care_id'] = $past_care_id;  
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
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
    public function view($id,$ypid,$care_home_id=0,$past_care_id=0)                         
    {
      if(is_numeric($id) && is_numeric($ypid))
       {
        //get daily observation data
        $table = DO_ARCHIVE . ' as do';
        $where = array("do.do_archive_id"=>$id,"do.yp_id"=>$ypid);
        $fields = array("do.*,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by',YP_DETAILS . ' as yp' => 'yp.yp_id= do.yp_id');
        
        $data['dodata'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '','','', '', $where);
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
         //get previous version
         $match = array('do_archive_id <'=> $id);
         $oldformsdata = $this->common_model->get_records(DO_ARCHIVE,'', '', '', $match,'',1,'','do_archive_id','desc');
         if(!empty($oldformsdata))
         {
              $data['food_form_old_data'] = json_decode($oldformsdata[0]['food_json_data'], TRUE);
              $data['summary_form_old_data'] = json_decode($oldformsdata[0]['summary_json_data'], TRUE);
              $data['oldformsdata'] = $oldformsdata;
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
          if(!empty($data['dodata'][0]['staff_data']))
          {
            $wherein = "login_id in (".$data['dodata'][0]['staff_data'].")";
            $fields = array("concat(firstname,' ', lastname) as staff_name");
            $data['do_staff_data'] = $this->common_model->get_records(LOGIN, $fields, '', '', '','','','','','','',$wherein,'','');
          }
          if(!empty($data['oldformsdata'][0]['staff_data']))
          {
            $wherein = "login_id in (".$data['oldformsdata'][0]['staff_data'].")";
            $fields = array("concat(firstname,' ', lastname) as staff_name");
            $data['do_staff_old_data'] = $this->common_model->get_records(LOGIN, $fields, '', '', '','','','','','','',$wherein,'','');
          } 
        

        $table = NFC_ARCHIVE_DO_SIGNOFF.' as do';
        $where = array("l.is_delete"=> "0","do.yp_id" => $ypid,"do.archive_do_id"=>$id);
        $fields = array("do.created_by,do.created_date,do.yp_id,do.archive_do_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by');
        $group_by = array('created_by');
        $data['do_signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);
        
        $table = NFC_ARCHIVE_OB_COMMENTS . ' as com';
            $where = array("com.archive_do_id" => $id, "com.yp_id" => $ypid);
            $fields = array("com.overview_comments,com.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
            $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);

          $data['care_home_id'] = $care_home_id;
          $data['past_care_id'] = $past_care_id;
          $data['ypid'] = $ypid;
          $data['do_id'] = $data['dodata'][0]['do_id'];
          
          $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
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

/*
      @Author : Niral Patel
      @Desc   : Delete archive
      @Input    :
      @Output   :
      @Date   : 18/08/2017
     */
    public function deleteArchiveDo($yp_id,$do_archive_id,$do_id) {
            //Delete Record From Database
        if (!empty($yp_id) && !empty($do_archive_id)) {
                      $where = array('do_archive_id' => $do_archive_id);
                        if ($this->common_model->delete(DO_ARCHIVE, $where)) {
                                    //Insert log activity
                                $activity = array(
                                  'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
                                  'module_name'         => ARCHIVE_DAILYOBSERVATION,
                                  'module_field_name'   => '',
                                  'yp_id'               => $yp_id,
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
        redirect('ArchiveDailyObservation/index/'.$do_id.'/'.$yp_id);
    }
}
