<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ArchivePlacementPlan extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->viewname = $this->router->fetch_class ();
        $this->method   = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Niral Patel
      @Desc   : ArchivePlacementPlan Index Page
      @Input 	: yp id
      @Output	:
      @Date   : 18/08/2017
     */

    public function index($ypid,$care_home_id=0,$past_care_id=0) {

      
      if($past_care_id!== 0){
            $temp_match=array("yp_id"=>$ypid,"past_carehome"=>$care_home_id);
            $temp=$this->common_model->get_records(PAST_CARE_HOME_INFO,array('move_date'), '', '',$temp_match);         
            $last_date=$temp[0]['move_date'];
            $match = array("yp_id"=>$ypid,"move_date <= "=>$last_date);
            $data_care_home_detail['care_home_detail'] = $this->common_model->get_records(PAST_CARE_HOME_INFO, array("*"), '', '', $match);

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
      
		$search_date = $this->input->post('search_date');
		//die;
		//$formated_date=date("Y-d-m",strtotime($search_date));
    $formated_date = dateformat($search_date);
		
		//die;
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
       $match = array("yp_id"=>$ypid);
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
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
            $this->session->unset_userdata('archive_pp_data');
        }

        $searchsort_session = $this->session->userdata('archive_pp_data');
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
                $sortfield = 'pp_archive_id';
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
         if($past_care_id == 0){
        $config['base_url'] = base_url() . $this->viewname . '/index/'.$ypid;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        //Query

        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = PLACEMENT_PLAN_ARCHIVE . ' as do';
        //$where = array("do.yp_id"=>$ypid,'do.status'=>1);
		$where = "do.yp_id='".$ypid."' AND do.status=1";
		 if (!empty($search_date)) {
                $where .= ' AND do.created_date LIKE "%' . $formated_date.'%"';
        }
        if (!empty($search_start_time)) { 
                $where .= ' AND do.modified_time BETWEEN  "' . $search_start_time.'" AND "'.$search_end_time.'"';
        }
        $fields = array("do.*,CONCAT(`firstname`,' ', `lastname`) as create_name,care_home_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = do.care_home_id');
		
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
        }
      }else{
        $config['base_url'] = base_url() . $this->viewname . '/index/'.$ypid.'/'.$care_home_id.'/'.$past_care_id;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 6;
            $uri_segment = $this->uri->segment(6);
        }
        //Query


        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = PLACEMENT_PLAN_ARCHIVE . ' as do';
        //$where = array("do.yp_id"=>$ypid,'do.status'=>1);
    $where = "do.yp_id='".$ypid."'";
    $where_date = "STR_TO_DATE( do.created_date, '%Y-%m-%d' ) BETWEEN  '".$created_date."' AND '".$movedate."'";
     if (!empty($search_date)) {
                $where .= ' AND do.created_date LIKE "%' . $formated_date.'%"';
        }
        if (!empty($search_start_time)) { 
                $where .= ' AND do.modified_time BETWEEN  "' . $search_start_time.'" AND "'.$search_end_time.'"';
        }
       $fields = array("do.*,CONCAT(`firstname`,' ', `lastname`) as create_name,care_home_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = do.care_home_id');
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where,'','','','','',$where_date);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1','','',$where_date);
        }
      }
      
        $match = array('yp_id'=> $ypid,'is_previous_version'=>0);
        $edit_data = $this->common_model->get_records(PLACEMENT_PLAN,'', '', '', $match);
        $data['placement_plan_id'] = $edit_data['0']['placement_plan_id'];
        
        $data['ypid'] = $ypid;
        $data['past_care_id'] = $past_care_id;
        $data['care_home_id'] = $care_home_id;
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

        $this->session->set_userdata('archive_pp_data', $sortsearchpage_data);
        setActiveSession('archive_pp_data'); // set current Session active
        $data['header'] = array('menu_module' => 'YoungPerson');

        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/placementplan/placementplan.js');
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
      @Desc   : Create archive
      @Input    :
      @Output   :
      @Date   : 18/08/2017
     */
    public function createArchive($id)
    {
      if(is_numeric($id))
      {
         //get pp form
         $match = array('pp_form_id'=> 1);
         $formsdata = $this->common_model->get_records(PP_FORM,'', '', '', $match);
        
         //get pp yp data
         $match = array('yp_id'=> $id);
         $pp_yp_data = $this->common_model->get_records(PLACEMENT_PLAN,'', '', '', $match);

         if(!empty($formsdata) && !empty($pp_yp_data))
         {
              $pp_form_data = json_decode($formsdata[0]['form_json_data'], TRUE);
              $data = array();
              $i=0;
              foreach ($pp_form_data as $row) {
                  if(isset($row['name']))
                  {
                      if($row['type'] != 'button')
                      {
                          if($row['type'] == 'checkbox-group')
                          {
                            $pp_form_data[$i]['value'] = implode(',',$pp_yp_data[0][$row['name']]);
                          }else{
                            //$pp_form_data[$i]['value'] = strip_slashes($pp_yp_data[0][$row['name']]);
                            $pp_form_data[$i]['value'] = str_replace("'", '"', $pp_yp_data[0][$row['name']]);
                          }
                      }
                  }
                  $i++;
              }
              $archive = array(
                  'yp_id' => $id,
                  'form_json_data' =>json_encode($pp_form_data, TRUE),
                  'created_by'=>$this->session->userdata('LOGGED_IN')['ID'],
                  'created_date'=>datetimeformat(),
                  'status'=>0
              );

             //get pp yp data
              $match = array('form_json_data'=> json_encode($pp_form_data, TRUE));
              $wherestring = " form_json_data = '".str_replace("\\","\\\\", json_encode($pp_form_data, TRUE))."'";
              $pp_archive_data = $this->common_model->get_records(PLACEMENT_PLAN_ARCHIVE,array('yp_id'), '', '', '','','','','','','',$wherestring);
             
             if(empty($pp_archive_data))
             {
                  //get pp yp data
                 $match = array('yp_id'=> $id);
                 $archive_data = $this->common_model->get_records(PLACEMENT_PLAN_ARCHIVE,'', '', '', $match);
                 
                 
                 if(empty($archive_data))
                 {
                    $archive_insert = array(
                        'yp_id' => $id,
                        'form_json_data' =>json_encode($pp_form_data, TRUE),
                        'created_by'=>$this->session->userdata('LOGGED_IN')['ID'],
                        'created_date'=>datetimeformat(),
                        'status'=>1
                    );
                    //insert archive for next time
                    $this->common_model->insert(PLACEMENT_PLAN_ARCHIVE, $archive_insert);
                   
                 }
                 else
                 {
                    //update status to archive
                    $update_archive = array(
                        'created_date'=>datetimeformat(),
                        'status'=>1
                    );
                    $where = array('status'=>0,'yp_id'=>$id);
                    $this->common_model->update(PLACEMENT_PLAN_ARCHIVE, $update_archive,$where);
                 }

                 //insert archive data for next time
                 $this->common_model->insert(PLACEMENT_PLAN_ARCHIVE, $archive);

                 $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>Archive data sucessfully.</div>");
                redirect('/' . $this->viewname .'/index/'. $id);
             }
             else
             {
                  $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>No updated data for archive.</div>");
                  redirect('/ArchivePlacementPlan/index/'. $id);
             }
             
              
         }
         else
         {
              $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>No data for archive.</div>");
              redirect('/PlacementPlan/index/'. $id);
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
        
         //get archive pp data
         $match = array('pp_archive_id'=> $id,'yp_id'=> $ypid);
         $formsdata = $this->common_model->get_records(PLACEMENT_PLAN_ARCHIVE,'', '', '', $match,'',1,'','pp_archive_id','desc');
		
		  $data['formsdata'] = $formsdata;
         if(!empty($formsdata))
         {
              $data['pp_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
         }


          $match_report = array('m.pp_archive_id'=>$id,'m.yp_id'=> $ypid,'m.status'=>1);
          $join_tables_report = array(LOGIN . ' as l' => 'l.login_id= m.created_by');
          $fields_report = array("m.*");
          $data['edit_data'] = $this->common_model->get_records(PLACEMENT_PLAN_ARCHIVE.' as m',$fields_report,$join_tables_report, 'left', $match_report,'1','','pp_archive_id','desc');
          //get ra old yp data
         

          if(!empty($data['edit_data'])){

          $mdt_archive_id = $data['edit_data'][0]['pp_archive_id'];
          
		  $where1 = array('pp_aim_archive_id' => $mdt_archive_id,'yp_id'=> $ypid);
          $fields_cpt = array("*");
          $data['edit_data_pp_aim_archive'] = $this->common_model->get_records(archive_pp_aims_of_placement, $fields_cpt, '', '', $where1, '','','','','','','');
		  
		  $where1 = array('pp_lac_archive_id' => $mdt_archive_id,'yp_id'=> $ypid);
          $fields_cpt = array("*");
          $data['edit_data_pp_lac_archive'] = $this->common_model->get_records(pp_archive_actions_from_lac_review, $fields_cpt, '', '', $where1, '','','','','','','');
		  
		  
		  $where1 = array('pp_health_archive_id' => $mdt_archive_id,'yp_id'=> $ypid);
          $fields_cpt = array("*");
          $data['edit_data_pp_health_archive'] = $this->common_model->get_records(pp_health_archive, $fields_cpt, '', '', $where1, '','','','','','','');

          $where1 = array('pp_edu_archive_id' => $mdt_archive_id,'yp_id'=> $ypid);
          $fields_cpt = array("*");
          $data['edit_data_pp_edu_archive'] = $this->common_model->get_records(pp_edu_archive, $fields_cpt, '', '', $where1, '','','','','','','');

          //get pp tra archive data
          $where1 = array('pp_tra_archive_id' => $mdt_archive_id,'yp_id'=> $ypid);
          $fields_tra = array("*");
          $data['edit_data_pp_tra_archive'] = $this->common_model->get_records(pp_tra_archive, $fields_tra, '', '', $where1, '','','','','','','');


          $where1 = array('pp_con_archive_id' => $mdt_archive_id,'yp_id'=> $ypid);
          $data['edit_data_pp_con_archive'] = $this->common_model->get_records(pp_con_archive, '', '', '', $where1, '','','','','','','');

          $where1 = array('pp_ft_archive_id' => $mdt_archive_id,'yp_id'=> $ypid);
            $data['edit_data_pp_ft_archive'] = $this->common_model->get_records(pp_ft_archive, '', '', '', $where1, '','','','','','','');

           $where1 = array('pp_mgi_archive_id' => $mdt_archive_id,'yp_id'=> $ypid);
            $data['edit_data_pp_mgi_archive'] = $this->common_model->get_records(pp_mgi_archive, '', '', '', $where1, '','','','','','',''); 
//get pp pr data
            $where1 = array('pp_pr_archive_id' => $mdt_archive_id,'yp_id'=> $ypid);
            $data['edit_data_pp_pr_archive'] = $this->common_model->get_records(pp_pr_archive, '', '', '', $where1, '','','','','','','');

             $where1 = array('pp_bc_archive_id' => $mdt_archive_id,'yp_id'=> $ypid);
            $data['edit_data_pp_bc_archive'] = $this->common_model->get_records(pp_bc_archive, '', '', '', $where1, '','','','','','',''); 
            

        }

           $match = array('m.pp_archive_id <'=> $id,'m.yp_id'=> $ypid,'m.status'=>1);
          $join_tables_report = array(LOGIN . ' as l' => 'l.login_id= m.created_by');
          $fields_report = array("m.*");
          $data['prev_edit_data'] = $this->common_model->get_records(PLACEMENT_PLAN_ARCHIVE.' as m',$fields_report, $join_tables_report, 'left', $match,'','1','','pp_archive_id','desc');

          if(!empty($data['prev_edit_data']))
          {
            $pp_archive_id = $data['prev_edit_data'][0]['pp_archive_id'];
            //get pp health archive id 
            
			$where1 = array('pp_aim_archive_id' => $pp_archive_id,'yp_id' => $ypid);
            $data['pp_aim_archve_data'] = $this->common_model->get_records(archive_pp_aims_of_placement, '', '', '', $where1, '','','','','','','');
			
			$where1 = array('pp_lac_archive_id' => $pp_archive_id,'yp_id' => $ypid);
            $data['pp_lac_archve_data'] = $this->common_model->get_records(pp_archive_actions_from_lac_review, '', '', '', $where1, '','','','','','','');
			
			$where1 = array('pp_health_archive_id' => $pp_archive_id,'yp_id' => $ypid);
            $data['pp_health_archve_data'] = $this->common_model->get_records(pp_health_archive, '', '', '', $where1, '','','','','','','');
          
            //get pp health archive id 
            $where1 = array('pp_edu_archive_id' => $pp_archive_id,'yp_id'=> $ypid);
            $data['pp_edu_archve_data'] = $this->common_model->get_records(pp_edu_archive, '', '', '', $where1, '','','','','','','');

            //get pp tra data
            $where1 = array('pp_tra_archive_id' => $pp_archive_id,'yp_id'=> $ypid);
            $data['pp_tra_archve_data'] = $this->common_model->get_records(pp_tra_archive, '', '', '', $where1, '','','','','','','');


            $where1 = array('pp_con_archive_id' => $pp_archive_id,'yp_id'=> $ypid);
            $data['pp_con_archve_data'] = $this->common_model->get_records(pp_con_archive, '', '', '', $where1, '','','','','','','');


            $where1 = array('pp_ft_archive_id' => $pp_archive_id,'yp_id'=> $ypid);
            $data['pp_ft_archve_data'] = $this->common_model->get_records(pp_ft_archive, '', '', '', $where1, '','','','','','','');

               $where1 = array('pp_mgi_archive_id' => $pp_archive_id,'yp_id'=> $ypid);
            $data['pp_mgi_archve_data'] = $this->common_model->get_records(pp_mgi_archive, '', '', '', $where1, '','','','','','',''); 
            //get pp pr archive data
             $where1 = array('pp_pr_archive_id' => $pp_archive_id,'yp_id'=> $ypid);
            $data['pp_pr_archve_data'] = $this->common_model->get_records(pp_pr_archive, '', '', '', $where1, '','','','','','','');
            
             $where1 = array('pp_bc_archive_id' => $pp_archive_id,'yp_id'=> $ypid);
            $data['pp_bc_archve_data'] = $this->common_model->get_records(pp_bc_archive, '', '', '', $where1, '','','','','','','');

        }
				  		
						
		
			
         if(!empty($oldformsdata))
         {
              $data['form_old_data'] = json_decode($oldformsdata[0]['form_json_data'], TRUE);
         }
          //get YP information
          $match = array("yp_id"=>$ypid);
          $fields = array("*");
          $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson');
          }
          // signoff data
        $table = NFC_ARCHIVE_PLACEMENT_PLAN_SIGNOFF.' as pps';
        $where = array("l.is_delete"=> "0","pps.yp_id" => $ypid,"pps.archive_pp_id"=>$id);
        $fields = array("pps.created_by,pps.created_date,pps.yp_id,pps.archive_pp_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id=pps.created_by');
        $group_by = array('created_by');
        $data['signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);
        
          $data['ypid'] = $ypid;
          $data['care_home_id'] = $care_home_id;
          $data['past_care_id'] = $past_care_id;

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
