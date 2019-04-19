<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PlacementPlan extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->viewname = $this->router->fetch_class ();
        $this->method   = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session','m_pdf'));
    }

    /*
      @Author : Niral Patel
      @Desc   : PlacementPlan Index Page
      @Input 	: yp id
      @Output	:
      @Date   : 7/07/2017
    */
      
    public function index($id,$care_home_id = 0, $past_care_id = 0) {
			
		//get pp aim data
		
		$match = array('yp_id'=> $id);
		
    $data['edit_data_pp_aim'] = $this->common_model->get_records(pp_aims_of_placement,'', '', '', $match);
	//get pp lac data
		
		$match = array('yp_id'=> $id);
    $data['edit_data_pp_lac'] = $this->common_model->get_records(pp_actions_from_lac_review,'', '', '', $match);
	//get pp health data
		$match = array('yp_id'=> $id);
    $data['edit_data_pp_health'] = $this->common_model->get_records(pp_health,'', '', '', $match);

    //get pp edu data
    $match = array('yp_id'=> $id);
        $data['edit_data_pp_edu'] = $this->common_model->get_records(pp_edu,'', '', '', $match);
        
        //get pp tra data
    $match = array('yp_id'=> $id);
        $data['edit_data_pp_tra'] = $this->common_model->get_records(pp_tra,'', '', '', $match);
       //get pp con data
    $match = array('yp_id'=> $id);
        $data['edit_data_pp_con'] = $this->common_model->get_records(pp_con,'', '', '', $match);
        
        //get pp ft data
    $match = array('yp_id'=> $id);
        $data['edit_data_pp_ft'] = $this->common_model->get_records(pp_ft,'', '', '', $match); 

        //get pp mgi data
    $match = array('yp_id'=> $id);
        $data['edit_data_pp_mgi'] = $this->common_model->get_records(pp_mgi,'', '', '', $match);

        //get pp pr data
    $match = array('yp_id'=> $id);
        $data['edit_data_pp_pr'] = $this->common_model->get_records(pp_pr,'', '', '', $match);

        //get pp bc data
      $match = array('yp_id'=> $id);
      $data['edit_data_pp_bc'] = $this->common_model->get_records(pp_bc,'', '', '', $match);

     /* pr($data['edit_data_pp_health']);
		die; */
		 
      $match = array('m.yp_id'=> $id,'m.status'=>1);
          $join_tables_report = array(LOGIN . ' as l' => 'l.login_id= m.created_by');
          $fields_report = array("m.*,l.login_id,concat(l.firstname,' ',l.lastname) as case_manager_name");
          $data['prev_archive_edit'] = $this->common_model->get_records(PLACEMENT_PLAN_ARCHIVE.' as m',$fields_report, $join_tables_report, 'left', $match,'','1','','pp_archive_id','desc');

		//get pp health archive data
           if(!empty($data['prev_archive_edit']))
          {

            $pp_archive_id = $data['prev_archive_edit'][0]['pp_archive_id'];
            //get MDT_CARE_PLAN_TARGET_ARCHIVE 
            $where1 = array('pp_health_archive_id' => $pp_archive_id);
            $data['pp_health_archve_data'] = $this->common_model->get_records(pp_health_archive, '', '', '', $where1, '','','','','','','');
			
			//get pp aim archive data
            $where1 = array('pp_aim_archive_id' => $pp_archive_id);
            $data['pp_aim_archve_data'] = $this->common_model->get_records(archive_pp_aims_of_placement, '', '', '', $where1, '','','','','','','');
			
			//get pp lac archive data
            $where1 = array('pp_lac_archive_id' => $pp_archive_id);
            $data['pp_lac_archve_data'] = $this->common_model->get_records(pp_archive_actions_from_lac_review, '', '', '', $where1, '','','','','','','');

            //get pp edu archive data
            $where1 = array('pp_edu_archive_id' => $pp_archive_id);
            $data['pp_edu_archve_data'] = $this->common_model->get_records(pp_edu_archive, '', '', '', $where1, '','','','','','','');
            
          //get pp tra archive data
             $where1 = array('pp_tra_archive_id' => $pp_archive_id);
            $data['pp_tra_archve_data'] = $this->common_model->get_records(pp_tra_archive, '', '', '', $where1, '','','','','','','');
            
    //get pp con archive data
      $where1 = array('pp_con_archive_id' => $pp_archive_id);
            $data['pp_con_archve_data'] = $this->common_model->get_records(pp_con_archive, '', '', '', $where1, '','','','','','','');      
      

    //get pp ft archive data
      $where1 = array('pp_ft_archive_id' => $pp_archive_id);
      $data['pp_ft_archve_data'] = $this->common_model->get_records(pp_ft_archive, '', '', '', $where1, '','','','','','','');  


    //get pp ft archive data
       $where1 = array('pp_mgi_archive_id' => $pp_archive_id);
      $data['pp_mgi_archve_data'] = $this->common_model->get_records(pp_mgi_archive, '', '', '', $where1, '','','','','','',''); 

    //get pp pr archive data
     $where1 = array('pp_pr_archive_id' => $pp_archive_id);
      $data['pp_pr_archve_data'] = $this->common_model->get_records(pp_pr_archive, '', '', '', $where1, '','','','','','',''); 
    
    //get pp bc archive data
     $where1 = array('pp_bc_archive_id' => $pp_archive_id);
      $data['pp_bc_archve_data'] = $this->common_model->get_records(pp_bc_archive, '', '', '', $where1, '','','','','','',''); 
        }
    
        
       if(is_numeric($id))
       {
         //get pp form
         $match = array('pp_form_id'=> 1);
         $pp_forms = $this->common_model->get_records(PP_FORM,'', '', '', $match);
         if(!empty($pp_forms))
         {
              $data['pp_form_data'] = json_decode($pp_forms[0]['form_json_data'], TRUE);
         }
         
          $match = array('yp_id'=> $id,'is_previous_version'=>0);
          $data['edit_data'] = $this->common_model->get_records(PLACEMENT_PLAN,'', '', '', $match);
		 /*  pr($data['edit_data']);
		  die; */
		  $data['pp_id']=$data['edit_data'][0]['placement_plan_id']; 
		      
          //get YP information
          $match = array("yp_id"=>$id);
          $fields = array("*");
          $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
         if(!empty($data['edit_data'][0]['modified_by'])){
             $data['updated_by'] = getUserName($data['edit_data'][0]['modified_by']);
         }else{
             $data['updated_by'] = "";
         } 
          
          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$id);
          }
           //get pp yp data
          $match = array('yp_id'=> $id,'is_previous_version'=>1);
          $data['prev_edit_data'] = $this->common_model->get_records(PLACEMENT_PLAN,'', '', '', $match);
        
		  $match = array('m.yp_id'=> $id,'m.status'=>1);
          $join_tables_report = array(LOGIN . ' as l' => 'l.login_id= m.created_by');
          $fields_report = array("m.*,l.login_id,concat(l.firstname,' ',l.lastname) as case_manager_name");
          $data['prev_archive_edit_data'] = $this->common_model->get_records(PLACEMENT_PLAN_ARCHIVE.' as m',$fields_report, $join_tables_report, 'left', $match,'','1','','pp_archive_id','desc');
		 
		 $mdt_archive_id = $data['prev_archive_edit_data'][0]['pp_archive_id'];
          
          $match = array('yp_id'=> $id,'is_previous_version'=>1);
          $data['cpt_item_archive'] = $this->common_model->get_records(PLACEMENT_PLAN,'', '', '', $match);
        
        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = PLACEMENT_PLAN_SIGNOFF.' as pps';
        $where = array("l.is_delete"=> "0","pps.yp_id" => $id,"pps.is_delete"=> "0");
        $fields = array("pps.created_by,pps.created_date,pps.yp_id,pps.pp_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id=pps.created_by');
        $group_by = array('created_by');
        $data['signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);



        $table = PLACEMENT_PLAN_SIGNOFF.' as pps';
        $where = array("pps.yp_id" => $id,"pps.created_by" => $login_user_id,"pps.is_delete"=> "0");
        $fields = array("pps.*");
        $data['check_signoff_data'] = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);
        //get external approve
        $table = NFC_SIGNOFF_DETAILS;
        $fields = array('*');
        $where = array('yp_id' => $id);
        $data['check_external_signoff_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
        
          $data['ypid'] = $id;
          $data['footerJs'][0] = base_url('uploads/custom/js/placementplan/placementplan.js');
          
          $data['crnt_view'] = $this->viewname;
          $data['header'] = array('menu_module' => 'YoungPerson');
          $data['main_content'] = '/placementplan';
          $this->parser->parse('layouts/DefaultTemplate', $data);
        }
        else
        {
            show_404 ();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : create yp edit page
      @Input 	:
      @Output	:
      @Date   : 07/07/2017
     */

    public function edit($id) {
      if(is_numeric($id))
      {
    //get pp form
        $match = array('pp_form_id'=> 1);
        $pp_forms = $this->common_model->get_records(PP_FORM,'', '', '', $match);
        if(!empty($pp_forms))
        {
            $data['pp_form_data'] = json_decode($pp_forms[0]['form_json_data'], TRUE);
        }
		/* pr($data['pp_form_data']);
		die; */
        //get YP information
        $match = array("yp_id"=>$id);
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

        //get pp yp data
        $match = array('yp_id'=> $id,'is_previous_version'=>0);
        $data['edit_data'] = $this->common_model->get_records(PLACEMENT_PLAN,'', '', '', $match);
		
		//get pp health data
		$match = array('yp_id'=> $id);
        $data['edit_data_pp_health'] = $this->common_model->get_records(pp_health,'', '', '', $match);
		/*pr($data['edit_data_pp_health']);
		die;*/
		
		$match = array('yp_id'=> $id);
        $data['edit_data_pp_aims'] = $this->common_model->get_records(pp_aims_of_placement,'', '', '', $match);
		
		$match = array('yp_id'=> $id);
        $data['edit_data_pp_lac'] = $this->common_model->get_records(pp_actions_from_lac_review,'', '', '', $match);
		
		//get pp edu data
		$match = array('yp_id'=> $id);
        $data['edit_data_pp_edu'] = $this->common_model->get_records(pp_edu,'', '', '', $match);
		/*pr($data['edit_data_pp_health']);
		die;*/
        
		//get pp tra data
		$match = array('yp_id'=> $id);
        $data['edit_data_pp_tra'] = $this->common_model->get_records(pp_tra,'', '', '', $match);
		/*pr($data['edit_data_pp_health']);
		die;*/
        
		//get pp con data
		$match = array('yp_id'=> $id);
        $data['edit_data_pp_con'] = $this->common_model->get_records(pp_con,'', '', '', $match);
		/*pr($data['edit_data_pp_health']);
		die;*/
		
		//get pp ft data
		$match = array('yp_id'=> $id);
        $data['edit_data_pp_ft'] = $this->common_model->get_records(pp_ft,'', '', '', $match);
		/*pr($data['edit_data_pp_health']);
		die;*/
		
		//get pp mgi data
		$match = array('yp_id'=> $id);
        $data['edit_data_pp_mgi'] = $this->common_model->get_records(pp_mgi,'', '', '', $match);
		/*pr($data['edit_data_pp_health']);
		die;*/
        
		//get pp pr data
		$match = array('yp_id'=> $id);
        $data['edit_data_pp_pr'] = $this->common_model->get_records(pp_pr,'', '', '', $match);
		/*pr($data['edit_data_pp_health']);
		die;*/
        
		//get pp bc data
		$match = array('yp_id'=> $id);
        $data['edit_data_pp_bc'] = $this->common_model->get_records(pp_bc,'', '', '', $match);
		/*pr($data['edit_data_pp_health']);
		die;*/
        
        // signoff data
        $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
        $table = PLACEMENT_PLAN_SIGNOFF.' as pps';
        $where = array("l.is_delete"=> "0","pps.yp_id" => $id,"pps.is_delete"=> "0");
        $fields = array("pps.created_by,pps.created_date,pps.yp_id,pps.pp_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id=pps.created_by');
        $group_by = array('created_by');
        $data['signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);

        //get pp yp data
        $url_data =  base_url('PlacementPlan/edit/'.$id);
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

          $secs = floor($now % 60);
         
          if($secs >= 10)
          {
            $data['ypid'] = $id;
            $data['footerJs'][0] = base_url('uploads/custom/js/placementplan/placementplan.js');
            $data['footerJs'][1] = base_url('uploads/custom/js/formbuilder/formbuilder.js');

            $data['crnt_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/edit';
            $this->parser->parse('layouts/DefaultTemplate', $data);
          }
          else
          {
            $msg = $this->lang->line('check_pp_user_update_data');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('/' . $this->viewname .'/index/'. $id);
          }
        }else{
         $data['ypid'] = $id;
            $data['footerJs'][0] = base_url('uploads/custom/js/placementplan/placementplan.js');
            $data['footerJs'][1] = base_url('uploads/custom/js/formbuilder/formbuilder.js');

            $data['crnt_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'YoungPerson');
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
      @Desc   : Insert pp form
      @Input    :
      @Output   :
      @Date   : 10/07/2017
     */
    public function insert() {

        $postData = $this->input->post();
		
        unset($postData['submit_ppform']);
        //get pp form
        $match = array('pp_form_id' => 1);
        $pp_forms = $this->common_model->get_records(PP_FORM, '', '', '', $match);

        
        $submit_status = $postData['hdn_submit_status'];
        //get pp previous
        $match = array('yp_id' => $postData['yp_id'], 'is_previous_version' => 0);
        $pp_new_data = $this->common_model->get_records(PLACEMENT_PLAN, array('*'), '', '', $match);
		
        //get pp yp data
        $match = array('yp_id' => $postData['yp_id'], 'is_previous_version' => 1);
        $previous_data = $this->common_model->get_records(PLACEMENT_PLAN, array('*'), '', '', $match);
		
		if(!empty($pp_forms))
       {
            $pp_form_data = json_decode($pp_forms[0]['form_json_data'], TRUE);
            $data = array();
            foreach ($pp_form_data as $row) {
                if(isset($row['name']))
                {
                    if($row['type'] == 'file')
                    {
                      $filename = $row['name'];
                       //get image previous image
                        $match = array('yp_id'=> $postData['yp_id'],'is_previous_version'=>0);
                        $pp_yp_data = $this->common_model->get_records(PLACEMENT_PLAN,array('`'.$row['name'].'`'), '', '', $match);
                      
                      //delete img
                      if(!empty($postData['hidden_'.$row['name']]))
                      {
                          $delete_img = explode(',', $postData['hidden_'.$row['name']]);
                          $db_images = explode(',',$pp_yp_data[0][$filename]);
                          $differentedImage = array_diff ($db_images, $delete_img);
                          $pp_yp_data[0][$filename] = !empty($differentedImage)?implode(',',$differentedImage):'';
                          if(!empty($delete_img))
                          {
                              foreach ($delete_img as $img) {

                                if (file_exists ($this->config->item ('pp_img_url') .$postData['yp_id'].'/'.$img)) { 
                                    unlink ($this->config->item ('pp_img_url') .$postData['yp_id'].'/'.$img);
                                }
                                if (file_exists ($this->config->item ('pp_img_url_small') .$postData['yp_id'].'/'.$img)) {
                                    unlink ($this->config->item ('pp_img_url_small') .$postData['yp_id'].'/'.$img);
                                }
                              } 
                          }
                      }
                     
                      if(!empty($_FILES[$filename]['name'][0]))                     
                      {
                        //create dir and give permission
                        if (!is_dir($this->config->item('pp_base_url'))) {
                                mkdir($this->config->item('pp_base_url'), 0777, TRUE);
                        }

                        if (!is_dir($this->config->item('pp_base_big_url'))) {                                
                            mkdir($this->config->item('pp_base_big_url'), 0777, TRUE);
                        }
                        
                        
                        if (!is_dir($this->config->item('pp_base_big_url') . '/' . $postData['yp_id'])) {
                            mkdir($this->config->item('pp_base_big_url') . '/' . $postData['yp_id'], 0777, TRUE);
                        }
                       
                        $file_view = $this->config->item ('pp_img_url').$postData['yp_id'];
                        //upload big image
                        $upload_data  = uploadImage ($filename, $file_view,'/' . $this->viewname.'/index/'.$postData['yp_id']);

                        //upload small image
                        $insertImagesData = array();
                        if(!empty($upload_data))
                        {
                          foreach ($upload_data as $imageFiles) {
                              if (!is_dir($this->config->item('pp_base_small_url'))) {                                        
                                    mkdir($this->config->item('pp_base_small_url'), 0777, TRUE);
                                }
                                
                                if (!is_dir($this->config->item('pp_base_small_url') . '/' . $postData['yp_id'])) {                                        
                                    mkdir($this->config->item('pp_base_small_url') . '/' . $postData['yp_id'], 0777, TRUE);
                                }
                                /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                if($imageFiles['is_image'])
                                    $a = do_resize ($this->config->item ('pp_img_url') . $postData['yp_id'], $this->config->item ('pp_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);                              
                                
                              array_push($insertImagesData,$imageFiles['file_name']);
                              if(!empty($insertImagesData))
                              {
                                $images = implode(',',$insertImagesData);
                              }
                          }
                          if(!empty($pp_yp_data[0][$filename]))
                          {
                            $images .=','.$pp_yp_data[0][$filename];
                          }
                          $data[$row['name']] = !empty($images)?$images:'';
                        }


                        }
                        else
                        {
                          $data[$row['name']] = !empty($pp_yp_data[0][$filename])?$pp_yp_data[0][$filename]:'';
                        }
                    }
                    else{
                          if($row['type'] != 'button')
                          {
                              if($row['type'] == 'checkbox-group')
                              {$data[$row['name']] = !empty($postData[$row['name']])?implode(',',$postData[$row['name']]):'';}
                              elseif($row['type'] == 'textarea' && $row['subtype'] == 'tinymce')
                              {$data[$row['name']] = strip_slashes($postData[$row['name']]);}
                              else{$data[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));}
                              //pr($data);exit;
                          }
                      }
                    
                }
            }
       }
		
		
		  
        if (!empty($pp_new_data)) {
            $update_pre_data = array();
            $updated_field = array();
            $n = 0;
            if (!empty($postData)) {
                $update_pre_data['yp_id'] = $postData['yp_id'];
                $update_pre_data['pre_placement_info'] = $pp_new_data[0]['pre_placement_info'];
                $update_pre_data['pre_placement_family'] = $pp_new_data[0]['pre_placement_family'];
                $update_pre_data['pre_placement_edu'] = $pp_new_data[0]['pre_placement_edu'];
                $update_pre_data['pre_placement_relation'] = $pp_new_data[0]['pre_placement_relation'];
                $update_pre_data['pre_placement_school'] = $pp_new_data[0]['pre_placement_school'];
                $update_pre_data['pre_placement_contact'] = $pp_new_data[0]['pre_placement_contact'];
                $update_pre_data['pre_placement_therapy'] = $pp_new_data[0]['pre_placement_therapy'];
                $update_pre_data['pre_placement_appointment'] = $pp_new_data[0]['pre_placement_appointment'];
                $update_pre_data['created_date'] = $pp_new_data[0]['created_date'];
                $update_pre_data['created_by'] = $pp_new_data[0]['created_by'];
                $update_pre_data['signoff'] = $pp_new_data[0]['signoff'];
                $update_pre_data['modified_by'] = $pp_new_data[0]['modified_by'];
                $update_pre_data['modified_date'] = $pp_new_data[0]['modified_date'];
                $update_pre_data['is_previous_version'] = 1;
            }
            if (!empty($previous_data)) {
                if ($n != 0) {
				
                    $this->common_model->update(PLACEMENT_PLAN, $update_pre_data, array('yp_id' => $postData['yp_id'], 'is_previous_version' => 1));
                } else {
                    
                }
            } else {
				
                $update_pre_data['care_home_id'] = $postData['care_home_id'];
				
                $this->common_model->insert(PLACEMENT_PLAN, $update_pre_data);
				$pp_insert_id = $this->db->insert_id();
            }
        }

        if (!empty($postData['placement_plan_id'])) {

            $data['placement_plan_id'] = $postData['placement_plan_id'];
			      $data['pre_placement_info'] = $postData['pre_placement_info'];
            $data['pre_placement_family'] = $postData['pre_placement_family'];
            $data['pre_placement_edu'] = $postData['pre_placement_edu'];
            $data['pre_placement_relation'] = $postData['pre_placement_relation'];
            $data['pre_placement_school'] = $postData['pre_placement_school'];
            $data['pre_placement_contact'] = $postData['pre_placement_contact'];
            $data['pre_placement_therapy'] = $postData['pre_placement_therapy'];
            $data['pre_placement_appointment'] = $postData['pre_placement_appointment'];
            $data['yp_id'] = $postData['yp_id'];
            $data['signoff'] = 0;
            $data['modified_date'] = datetimeformat();
            $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];

			      $this->common_model->update(PLACEMENT_PLAN, $data, array('placement_plan_id' => $postData['placement_plan_id']));
		
                    //Insert log activity
                    $activity = array(
                        'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                        'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                        'module_name' => PP_MODULE,
                        'module_field_name' => '',
                        'type' => 2
                    );
                    log_activity($activity);
               
        } else {
            $data['care_home_id'] = $postData['care_home_id'];
            $data['yp_id'] = $postData['yp_id'];
			$data['pre_placement_info'] = $postData['pre_placement_info'];
                $data['pre_placement_family'] = $postData['pre_placement_family'];
                $data['pre_placement_edu'] = $postData['pre_placement_edu'];
                $data['pre_placement_relation'] = $postData['pre_placement_relation'];
                $data['pre_placement_school'] = $postData['pre_placement_school'];
                $data['pre_placement_contact'] = $postData['pre_placement_contact'];
                $data['pre_placement_therapy'] = $postData['pre_placement_therapy'];
                $data['pre_placement_appointment'] = $postData['pre_placement_appointment'];
            $data['created_date'] = datetimeformat();
            $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            $data['modified_date'] = datetimeformat();
			
            $this->common_model->insert(PLACEMENT_PLAN, $data);
            $pp_insert_id = $this->db->insert_id();

            //Insert log activity
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                'module_name' => PP_MODULE,
                'module_field_name' => '',
                'type' => 1
            );
            log_activity($activity);
        }
		 $new_change = 0;
         $new_update = 0;
		
		/*for other table
		nikunj ghelani
		21-11-2018
		for pp health table data insert
		*/
		
		if(!empty($postData['placement_plan_id'])){
			
			 $pp_insert_id = $postData['placement_plan_id'];
			
		}else{
			 $pp_insert_id = $pp_insert_id;
		}
		/*placement plan aims data saved for normal and archive both*/
		
		if(!empty($pp_insert_id) && $pp_insert_id!=''){
			
			$aims_of_placement_data = $this->input->post('aims_of_placement_data');
			
			$delete_aims_of_placement = $this->input->post('delete_aims_of_placement');
		/* 	pr($delete_aims_of_placement);
			die; */
				
                if (!empty($delete_aims_of_placement)) {
					
                    $new_update++;
                    $delete_item = substr($delete_aims_of_placement, 0, -1);
                    $delete_aims_of_placement = explode(',', $delete_item);
					
                    $where1 = array('placement_plan_id' => $pp_insert_id);
                    $this->common_model->delete_where_in(pp_aims_of_placement, $where1, 'aims_of_placement_id', $delete_aims_of_placement);
					      }
				/* echo $this->db->last_query();
				die; */
				        $where1 = array('placement_plan_id' => $pp_insert_id);
				        $pp_aim_item = $this->common_model->get_records(pp_aims_of_placement, array('aims_of_placement_id'), '', '', $where1, '');

                if (!empty($pp_aim_item)) {
					
					
                    for ($i = 0; $i < count($pp_aim_item); $i++) {
						 
                        if (!empty($postData['aims_of_placement_data'. $pp_aim_item[$i]['aims_of_placement_id']])) {
						
                            //get latest archive
                            $where1 = "placement_plan_id =" . $pp_insert_id . " and aims_of_placement_id =" . $pp_aim_item[$i]['aims_of_placement_id'];
                            $aim_item_archive = $this->common_model->get_records(archive_pp_aims_of_placement, array('*'), '', '', $where1, '', '1', '', 'aims_of_placement_id', 'desc', '', '');
							              if (!empty($aim_item_archive)) {
                                $old_cpt_title = str_replace(array("\r", "\n"), "", $aim_item_archive[0]['aims_of_placement_data']);

							$post_cpt_title = str_replace(array("\r", "\n"), "", $postData['aims_of_placement_data'. $pp_aim_item[$i]['aims_of_placement_id']]);
                                if (($old_cpt_title != $post_cpt_title)) {
                                    $new_update++;
                                }
                            }
							 
                            
                            $update_item_aim['aims_of_placement_data'] = $postData['aims_of_placement_data'.$pp_aim_item[$i]['aims_of_placement_id']];
                           
                            $update_item_aim['modified_date'] = datetimeformat();
							//die('update'); 
                            $where = array('aims_of_placement_id' => $pp_aim_item[$i]['aims_of_placement_id']);
							
							
                            $success_update = $this->common_model->update(pp_aims_of_placement, $update_item_aim, $where);
							
                        }
                    } 
                } 
			
			
				
				$aims_of_placement_data = $this->input->post('aims_of_placement_data');
               
               for ($i = 0; $i < count($aims_of_placement_data); $i++) {
                    if (!empty($aims_of_placement_data[$i])) {
                        $new_change ++;
                        $aim_item_data['placement_plan_id'] = $pp_insert_id;
                        $aim_item_data['yp_id'] = $postData['yp_id'];
                        
                        $aim_item_data['aims_of_placement_data'] = $aims_of_placement_data[$i];
                       
                        $aim_item_data['created_date'] = datetimeformat();
                        $aim_item_data['modified_date'] = datetimeformat();
						    
                        $this->common_model->insert(pp_aims_of_placement, $aim_item_data);
						
                    }
                }
				
				
			}
			 
		
		/*end*/
		
		/*placement plan lac data saved for normal and archive both*/
		
		if(!empty($pp_insert_id) && $pp_insert_id!=''){
			
			$lac_review_data = $this->input->post('lac_review_data');
			$delete_lac_of_placement = $this->input->post('delete_lac_of_placement');
				
                if (!empty($delete_lac_of_placement)) {
                    $new_update++;
                    $delete_item = substr($delete_lac_of_placement, 0, -1);
                    $delete_lac_of_placement = explode(',', $delete_item);
                    $where1 = array('placement_plan_id' => $pp_insert_id);
                    $this->common_model->delete_where_in(pp_actions_from_lac_review, $where1, 'lac_review_id', $delete_lac_of_placement);
					      }
				
				        $where1 = array('placement_plan_id' => $pp_insert_id);
				        $pp_lac_item = $this->common_model->get_records(pp_actions_from_lac_review, array('lac_review_id'), '', '', $where1, '');

                if (!empty($pp_lac_item)) {
					
					
                    for ($i = 0; $i < count($pp_lac_item); $i++) {
						 
                        if (!empty($postData['lac_review_data'. $pp_lac_item[$i]['lac_review_id']])) {
						
                            //get latest archive
                            $where1 = "placement_plan_id =" . $pp_insert_id . " and lac_review_id =" . $pp_lac_item[$i]['lac_review_id'];
                            $lac_item_archive = $this->common_model->get_records(pp_archive_actions_from_lac_review, array('*'), '', '', $where1, '', '1', '', 'lac_review_id', 'desc', '', '');
							              if (!empty($lac_item_archive)) {
                                $old_cpt_title = str_replace(array("\r", "\n"), "", $lac_item_archive[0]['lac_review_data']);

							$post_cpt_title = str_replace(array("\r", "\n"), "", $postData['lac_review_data'. $pp_lac_item[$i]['lac_review_id']]);
                                if (($old_cpt_title != $post_cpt_title)) {
                                    $new_update++;
                                }
                            }
							 
                            
                            $update_item_lac['lac_review_data'] = $postData['lac_review_data'.$pp_lac_item[$i]['lac_review_id']];
                           
                            $update_item_lac['modified_date'] = datetimeformat();
							//die('update'); 
                            $where = array('lac_review_id' => $pp_lac_item[$i]['lac_review_id']);
							
                            $success_update = $this->common_model->update(pp_actions_from_lac_review, $update_item_lac, $where);
							
                        }
                    } 
                } 
			
			
				
				$lac_review_data = $this->input->post('lac_review_data');
               
               for ($i = 0; $i < count($lac_review_data); $i++) {
                    if (!empty($lac_review_data[$i])) {
                        $new_change ++;
                        $lac_item_data['placement_plan_id'] = $pp_insert_id;
                        $lac_item_data['yp_id'] = $postData['yp_id'];
                        
                        $lac_item_data['lac_review_data'] = $lac_review_data[$i];
                       
                        $lac_item_data['created_date'] = datetimeformat();
                        $lac_item_data['modified_date'] = datetimeformat();
						        
                        $this->common_model->insert(pp_actions_from_lac_review, $lac_item_data);
						
                    }
                }
				
				
			}
			 
		
		/*end*/

		
		 	if(!empty($pp_insert_id) && $pp_insert_id!=''){
				$pre_placement = $this->input->post('pre_placement');
        $risk_assesment = $this->input->post('risk_assesment');
        $individual_strategies = $this->input->post('individual_strategies');
        $health_name = $this->input->post('health_name');
				$delete_cpt_review_id = $this->input->post('delete_cpt_review_id');
				
                if (!empty($delete_cpt_review_id)) {
					
                    $new_update++;
                    $delete_item = substr($delete_cpt_review_id, 0, -1);
                    $delete_cpt_review_id = explode(',', $delete_item);
                    $where1 = array('placement_plan_id' => $pp_insert_id);
                    $this->common_model->delete_where_in(pp_health, $where1, 'pp_health_id', $delete_cpt_review_id);
					      }
				
				        $where1 = array('placement_plan_id' => $pp_insert_id);
				        $pp_health_item = $this->common_model->get_records(pp_health, array('pp_health_id'), '', '', $where1, '');

                if (!empty($pp_health_item)) {
					
                    for ($i = 0; $i < count($pp_health_item); $i++) {
						 
                        if (!empty($postData['pre_placement'. $pp_health_item[$i]['pp_health_id']]) || !empty($postData['risk_assesment'. $pp_health_item[$i]['pp_health_id']]) || !empty($postData['individual_strategies'. $pp_health_item[$i]['pp_health_id']])|| !empty($postData['health_name'. $pp_health_item[$i]['pp_health_id']])) {
						
                            //get latest archive
                            $where1 = "placement_plan_id =" . $pp_insert_id . " and pp_health_id =" . $pp_health_item[$i]['pp_health_id'];
                            $cpt_item_archive = $this->common_model->get_records(pp_health_archive, array('*'), '', '', $where1, '', '1', '', 'pp_health_id', 'desc', '', '');
							              if (!empty($cpt_item_archive)) {
                                $old_cpt_title = str_replace(array("\r", "\n"), "", $cpt_item_archive[0]['pre_placement']);

							$post_cpt_title = str_replace(array("\r", "\n"), "", $postData['pre_placement'. $pp_health_item[$i]['pp_health_id']]);

                                $old_cpt_reason = str_replace(array("\r", "\n"), "", $cpt_item_archive[0]['risk_assesment']);

                                $post_cpt_reason = str_replace(array("\r", "\n"), "", $postData['risk_assesment'. $pp_health_item[$i]['pp_health_id']]);
								
								$old_cpt_is = str_replace(array("\r", "\n"), "", $cpt_item_archive[0]['individual_strategies']);

                                $post_cpt_is = str_replace(array("\r", "\n"), "", $postData['individual_strategies'. $pp_health_item[$i]['pp_health_id']]);
								
								$old_cpt_heading = str_replace(array("\r", "\n"), "", $cpt_item_archive[0]['heading']);

                                $post_cpt_heading = str_replace(array("\r", "\n"), "", $postData['health_name'. $pp_health_item[$i]['pp_health_id']]);

                                if (($old_cpt_title != $post_cpt_title) || ($old_cpt_reason != $post_cpt_reason)|| ($old_cpt_is != $post_cpt_is)) {
                                    $new_update++;
                                }
                            }
							 
                            $update_item['heading'] = $postData['health_name'.$pp_health_item[$i]['pp_health_id']];
                            $update_item['pre_placement'] = $postData['pre_placement'.$pp_health_item[$i]['pp_health_id']];
                            $update_item['risk_assesment'] = $postData['risk_assesment'.$pp_health_item[$i]['pp_health_id']];
                            $update_item['individual_strategies'] = $postData['individual_strategies'.$pp_health_item[$i]['pp_health_id']];
                            $update_item['modified_date'] = datetimeformat();
							//die('update'); 
                            $where = array('pp_health_id' => $pp_health_item[$i]['pp_health_id']);
                            $success_update = $this->common_model->update(pp_health, $update_item, $where);
							
                        }
                    } 
                } 
			/* echo $this->db->last_query();
			die; */
			
				        $pre_placement = $this->input->post('pre_placement');
                $risk_assesment = $this->input->post('risk_assesment');
                $individual_strategies = $this->input->post('individual_strategies');
                $health_name = $this->input->post('health_name');
               for ($i = 0; $i < count($pre_placement); $i++) {
                    if (!empty($pre_placement[$i]) || !empty($risk_assesment[$i]) || !empty($individual_strategies[$i])|| !empty($health_name[$i])) {
                        $new_change ++;
                        $item_data['placement_plan_id'] = $pp_insert_id;
                        $item_data['yp_id'] = $postData['yp_id'];
                        $item_data['heading'] = $health_name[$i];
                        $item_data['pre_placement'] = $pre_placement[$i];
                        $item_data['risk_assesment'] = $risk_assesment[$i];
                        $item_data['individual_strategies'] = $individual_strategies[$i];
                        $item_data['created_date'] = datetimeformat();
                        $item_data['modified_date'] = datetimeformat();
						        
                        $this->common_model->insert(pp_health, $item_data);
						
                    }
                }
				
				
			}
			
		/*for other table
		nikunj ghelani
		21-11-2018
		for pp edu table data insert
		*/
		if(!empty($pp_insert_id) && $pp_insert_id!=''){
				
				$pp_insert_id=$pp_insert_id;
				$pre_placement_edu_sub = $this->input->post('pre_placement_edu_sub');
                $risk_assesment_edu = $this->input->post('risk_assesment_edu');
                $individual_strategies_edu = $this->input->post('individual_strategies_edu');
                $title_education = $this->input->post('title_education');
				$delete_cpt_review_edu_id = $this->input->post('delete_cpt_review_edu_id');
				
				 if (!empty($delete_cpt_review_edu_id)) {
					
                    $new_update++;
                    $delete_item = substr($delete_cpt_review_edu_id, 0, -1);
                    $delete_cpt_review_edu_id = explode(',', $delete_item);
                    $where1 = array('placement_plan_id' => $pp_insert_id);
                    $this->common_model->delete_where_in(pp_edu ,$where1, 'pp_edu_id', $delete_cpt_review_edu_id);
					
                }
			
				
				        $where1 = array('placement_plan_id' => $pp_insert_id);
                $pp_edu_item = $this->common_model->get_records(pp_edu, array('pp_edu_id'), '', '', $where1, '');
				  
				 if (!empty($pp_edu_item)) {
					
                    for ($i = 0; $i < count($pp_edu_item); $i++) {
						 
                        if (!empty($postData['title_education'.$pp_edu_item[$i]['pp_edu_id']]) || !empty($postData['pre_placement_edu_sub'.$pp_edu_item[$i]['pp_edu_id']]) || !empty($postData['risk_assesment_edu'.$pp_edu_item[$i]['pp_edu_id']]) || !empty($postData['individual_strategies_edu'.$pp_edu_item[$i]['pp_edu_id']])) {
						
                            //get latest archive
                            $where1 = "placement_plan_id =" . $pp_insert_id . " and pp_edu_id =" . $pp_edu_item[$i]['pp_edu_id'];
                            $cpt_item_archive = $this->common_model->get_records(pp_edu_archive, array('*'), '', '', $where1, '', '1', '', 'pp_edu_id', 'desc', '', '');
							/* pr($cpt_item_archive);
							die; */
                            if (!empty($cpt_item_archive)) {
                                $old_cpt_title = str_replace(array("\r", "\n"), "", $cpt_item_archive[0]['pre_placement_edu_sub']);

                                $post_cpt_title = str_replace(array("\r", "\n"), "", $postData['pre_placement_edu_sub'.$pp_edu_item[$i]['pp_edu_id']]);

                                $old_cpt_reason = str_replace(array("\r", "\n"), "", $cpt_item_archive[0]['risk_assesment_edu']);

                                $post_cpt_reason = str_replace(array("\r", "\n"), "", $postData['risk_assesment_edu'.$pp_edu_item[$i]['pp_edu_id']]);
								
								$old_cpt_is = str_replace(array("\r", "\n"), "", $cpt_item_archive[0]['individual_strategies_edu']);

                                $post_cpt_is = str_replace(array("\r", "\n"), "", $postData['individual_strategies_edu'.$pp_edu_item[$i]['pp_edu_id']]);
								
								$old_cpt_heading = str_replace(array("\r", "\n"), "", $cpt_item_archive[0]['heading_edu']);

                                $post_cpt_heading = str_replace(array("\r", "\n"), "", $postData['title_education'.$pp_edu_item[$i]['pp_edu_id']]);

                                if (($old_cpt_title != $post_cpt_title) || ($old_cpt_reason != $post_cpt_reason)|| ($old_cpt_is != $post_cpt_is)|| ($old_cpt_heading != $post_cpt_heading)) {
                                    $new_update++;
                                }
                            }
							
                            $update_item_edu['heading_edu'] = $postData['title_education'.$pp_edu_item[$i]['pp_edu_id']];
                            $update_item_edu['pre_placement_edu_sub'] = $postData['pre_placement_edu_sub'.$pp_edu_item[$i]['pp_edu_id']];
                            $update_item_edu['risk_assesment_edu'] = $postData['risk_assesment_edu'.$pp_edu_item[$i]['pp_edu_id']];
                            $update_item_edu['individual_strategies_edu'] = $postData['individual_strategies_edu'.$pp_edu_item[$i]['pp_edu_id']];
                            $update_item_edu['modified_date'] = datetimeformat();
							
                            $where = array('pp_edu_id' => $pp_edu_item[$i]['pp_edu_id']);
                            $success_update = $this->common_model->update(pp_edu, $update_item_edu, $where);
                        }
                    }
                }
			/* echo $this->db->last_query();
			die; */
			
				$pre_placement_edu_sub = $this->input->post('pre_placement_edu_sub');
                $risk_assesment_edu = $this->input->post('risk_assesment_edu');
                $individual_strategies_edu = $this->input->post('individual_strategies_edu');
                $heading_edu = $this->input->post('title_education');
               for ($i = 0; $i < count($pre_placement_edu_sub); $i++) {
                    if (!empty($pre_placement_edu_sub[$i]) || !empty($risk_assesment_edu[$i]) || !empty($individual_strategies_edu[$i])|| !empty($heading_edu[$i])) {
                        $new_change ++;
                        $item_data_edu['placement_plan_id'] = $pp_insert_id;
                        $item_data_edu['yp_id'] = $postData['yp_id'];
                        $item_data_edu['heading_edu'] = $title_education[$i];
                        $item_data_edu['pre_placement_edu_sub'] = $pre_placement_edu_sub[$i];
                        $item_data_edu['risk_assesment_edu'] = $risk_assesment_edu[$i];
                        $item_data_edu['individual_strategies_edu'] = $individual_strategies_edu[$i];
                        $item_data_edu['created_date'] = datetimeformat();
                        $item_data_edu['modified_date'] = datetimeformat();
                        $this->common_model->insert(pp_edu, $item_data_edu);
                    }
                }
				
                
		}
				
		/*for other table
		nikunj ghelani
		23-11-2018
		for pp tra table data insert
		*/
		if(!empty($pp_insert_id) && $pp_insert_id!=''){
				$pp_insert_id=$pp_insert_id;
				$title_tra = $this->input->post('title_tra');
				$pre_placement_tra = $this->input->post('pre_placement_tra');
                $risk_assesment_tra = $this->input->post('risk_assesment_tra');
                $individual_strategies_tra = $this->input->post('individual_strategies_tra');
				$delete_cpt_review_tra_id = $this->input->post('delete_cpt_review_tra_id');
				
				
				 if (!empty($delete_cpt_review_tra_id)) {
                    $new_update++;
                    $delete_item = substr($delete_cpt_review_tra_id, 0, -1);
                    $delete_cpt_review_tra_id = explode(',', $delete_item);
                    $where1 = array('placement_plan_id' => $pp_insert_id);
                    $this->common_model->delete_where_in(pp_tra ,$where1, 'pp_tra_id', $delete_cpt_review_tra_id);
					
                }
				
				$where1 = array('placement_plan_id' => $pp_insert_id);
                $pp_tra_item = $this->common_model->get_records(pp_tra, array('pp_tra_id'), '', '', $where1, '');
				
				
				 if (!empty($pp_tra_item)) {
					
                    for ($i = 0; $i < count($pp_tra_item); $i++) {
						 
                        if (!empty($postData['title_tra'.$pp_tra_item[$i]['pp_tra_id']]) || !empty($postData['pre_placement_tra'.$pp_tra_item[$i]['pp_tra_id']]) || !empty($postData['risk_assesment_tra'.$pp_tra_item[$i]['pp_tra_id']]) || !empty($postData['individual_strategies_tra'.$pp_tra_item[$i]['pp_tra_id']])) {
						
                            //get latest archive
                            $where1 = "placement_plan_id =" . $pp_insert_id . " and pp_tra_id =" . $pp_tra_item[$i]['pp_tra_id'];
                            $pp_tra_archive = $this->common_model->get_records(pp_tra_archive, array('*'), '', '', $where1, '', '1', '', 'pp_tra_id', 'desc', '', '');
							/* pr($cpt_item_archive);
							die; */
                            if (!empty($pp_tra_archive)) {
                                $old_cpt_title = str_replace(array("\r", "\n"), "", $pp_tra_archive[0]['pre_placement_tra']);

                                $post_cpt_title = str_replace(array("\r", "\n"), "", $postData['pre_placement_tra'.$pp_tra_item[$i]['pp_tra_id']]);

                                $old_cpt_reason = str_replace(array("\r", "\n"), "", $pp_tra_archive[0]['risk_assesment_tra']);

                                $post_cpt_reason = str_replace(array("\r", "\n"), "", $postData['risk_assesment_tra'.$pp_tra_item[$i]['pp_tra_id']]);
								
								$old_cpt_is = str_replace(array("\r", "\n"), "", $pp_tra_archive[0]['individual_strategies_tra']);

                                $post_cpt_is = str_replace(array("\r", "\n"), "", $postData['individual_strategies_tra'.$pp_tra_item[$i]['pp_tra_id']]);
								
								$old_cpt_heading = str_replace(array("\r", "\n"), "", $pp_tra_archive[0]['heading_tra']);

                                $post_cpt_heading = str_replace(array("\r", "\n"), "", $postData['title_tra'.$pp_tra_item[$i]['pp_tra_id']]);

                                if (($old_cpt_title != $post_cpt_title) || ($old_cpt_reason != $post_cpt_reason) || ($old_cpt_is != $post_cpt_is)|| ($old_cpt_heading != $post_cpt_heading)) {
                                    $new_update++;
                                }
                            }
							
                            $update_item_tra['heading_tra'] = $postData['title_tra'.$pp_tra_item[$i]['pp_tra_id']];
                            $update_item_tra['pre_placement_tra'] = $postData['pre_placement_tra'.$pp_tra_item[$i]['pp_tra_id']];
                            $update_item_tra['risk_assesment_tra'] = $postData['risk_assesment_tra'.$pp_tra_item[$i]['pp_tra_id']];
                            $update_item_tra['individual_strategies_tra'] = $postData['individual_strategies_tra'.$pp_tra_item[$i]['pp_tra_id']];
                            $update_item_tra['modified_date'] = datetimeformat();
							/* pr($update_item);
							die; */
                            $where = array('pp_tra_id' => $pp_tra_item[$i]['pp_tra_id']);
                            $success_update = $this->common_model->update(pp_tra, $update_item_tra, $where);
                        }
                    }
                }
			/* echo $this->db->last_query();
			die; */
			
				$title_tra = $this->input->post('title_tra');
				$pre_placement_tra = $this->input->post('pre_placement_tra');
                $risk_assesment_tra = $this->input->post('risk_assesment_tra');
                $individual_strategies_tra = $this->input->post('individual_strategies_tra');
               for ($i = 0; $i < count($pre_placement_tra); $i++) {
                    if (!empty($pre_placement_tra[$i]) || !empty($risk_assesment_tra[$i]) || !empty($individual_strategies_tra[$i])|| !empty($title_tra[$i])) {
                        $new_change ++;
                        $item_data_tra['placement_plan_id'] = $pp_insert_id;
                        $item_data_tra['yp_id'] = $postData['yp_id'];
                        $item_data_tra['heading_tra'] = $title_tra[$i];
                        $item_data_tra['pre_placement_tra'] = $pre_placement_tra[$i];
                        $item_data_tra['risk_assesment_tra'] = $risk_assesment_tra[$i];
                        $item_data_tra['individual_strategies_tra'] = $individual_strategies_tra[$i];
                        $item_data_tra['created_date'] = datetimeformat();
                        $item_data_tra['modified_date'] = datetimeformat();
                        $this->common_model->insert(pp_tra, $item_data_tra);
                    }
                }
				
				
		}
				
		/*for other table
		nikunj ghelani
		23-11-2018
		for pp con table data insert
		*/
		if(!empty($pp_insert_id) && $pp_insert_id!=''){
				$pp_insert_id=$pp_insert_id;
				$title_con = $this->input->post('title_con');
				$pre_placement_con = $this->input->post('pre_placement_con');
                $risk_assesment_con = $this->input->post('risk_assesment_con');
                $individual_strategies_con = $this->input->post('individual_strategies_con');
				$delete_cpt_review_con_id = $this->input->post('delete_cpt_review_con_id');
				
				 if (!empty($delete_cpt_review_con_id)) {
                    $new_update++;
                    $delete_item = substr($delete_cpt_review_con_id, 0, -1);
                    $delete_cpt_review_con_id = explode(',', $delete_item);
                    $where1 = array('placement_plan_id' => $pp_insert_id);
                    $this->common_model->delete_where_in(pp_con ,$where1, 'pp_con_id', $delete_cpt_review_con_id);
					
                }
				$where1 = array('placement_plan_id' => $pp_insert_id);
                $pp_con_item = $this->common_model->get_records(pp_con, array('pp_con_id'), '', '', $where1, '');
				if (!empty($pp_con_item)) {
					
                    for ($i = 0; $i < count($pp_con_item); $i++) {
						 
                        if (!empty($postData['title_con'.$pp_con_item[$i]['pp_con_id']]) ||!empty($postData['pre_placement_con'.$pp_con_item[$i]['pp_con_id']]) || !empty($postData['risk_assesment_con'.$pp_con_item[$i]['pp_con_id']]) || !empty($postData['individual_strategies_con'.$pp_con_item[$i]['pp_con_id']])) {
						
                            //get latest archive
                            $where1 = "placement_plan_id =" . $pp_insert_id . " and pp_con_id =" . $pp_con_item[$i]['pp_con_id'];
                            $pp_con_archive = $this->common_model->get_records(pp_con_archive, array('*'), '', '', $where1, '', '1', '', 'pp_con_id', 'desc', '', '');
							/* pr($cpt_item_archive);
							die; */
                            if (!empty($pp_con_archive)) {
                                $old_cpt_title = str_replace(array("\r", "\n"), "", $pp_con_archive[0]['pre_placement_con']);

                                $post_cpt_title = str_replace(array("\r", "\n"), "", $postData['pre_placement_con'.$pp_con_item[$i]['pp_con_id']]);

                                $old_cpt_reason = str_replace(array("\r", "\n"), "", $pp_con_archive[0]['risk_assesment_con']);

                                $post_cpt_reason = str_replace(array("\r", "\n"), "", $postData['risk_assesment_con'.$pp_con_item[$i]['pp_con_id']]);
								
								$old_cpt_is = str_replace(array("\r", "\n"), "", $pp_con_archive[0]['individual_strategies_con']);

                                $post_cpt_is = str_replace(array("\r", "\n"), "", $postData['individual_strategies_con'.$pp_con_item[$i]['pp_con_id']]);
								
								$old_cpt_heading = str_replace(array("\r", "\n"), "", $pp_con_archive[0]['heading_con']);

                                $post_cpt_heading = str_replace(array("\r", "\n"), "", $postData['title_con'.$pp_con_item[$i]['pp_con_id']]);

                                if (($old_cpt_title != $post_cpt_title) || ($old_cpt_reason != $post_cpt_reason) || ($old_cpt_is != $post_cpt_is) || ($old_cpt_heading != $post_cpt_heading)) {
                                    $new_update++;
                                }
                            }
							
                            $update_item_con['heading_con'] = $postData['title_con'.$pp_con_item[$i]['pp_con_id']];
                            $update_item_con['pre_placement_con'] = $postData['pre_placement_con'.$pp_con_item[$i]['pp_con_id']];
                            $update_item_con['risk_assesment_con'] = $postData['risk_assesment_con'.$pp_con_item[$i]['pp_con_id']];
                            $update_item_con['individual_strategies_con'] = $postData['individual_strategies_con'.$pp_con_item[$i]['pp_con_id']];
                            $update_item_con['modified_date'] = datetimeformat();
							/* pr($update_item);
							die; */
                            $where = array('pp_con_id' => $pp_con_item[$i]['pp_con_id']);
                            $success_update = $this->common_model->update(pp_con, $update_item_con, $where);
                        }
                    }
                }
				$pre_placement_con = $this->input->post('pre_placement_con');
                $risk_assesment_con = $this->input->post('risk_assesment_con');
                $individual_strategies_con = $this->input->post('individual_strategies_con');
                $title_con = $this->input->post('title_con');
               for ($i = 0; $i < count($pre_placement_con); $i++) {
                    if (!empty($pre_placement_con[$i]) || !empty($risk_assesment_con[$i]) || !empty($individual_strategies_con[$i])|| !empty($title_con[$i])) {
                        $new_change ++;
                        $item_data_con['placement_plan_id'] = $pp_insert_id;
                        $item_data_con['yp_id'] = $postData['yp_id'];
                        $item_data_con['heading_con'] = $title_con[$i];
                        $item_data_con['pre_placement_con'] = $pre_placement_con[$i];
                        $item_data_con['risk_assesment_con'] = $risk_assesment_con[$i];
                        $item_data_con['individual_strategies_con'] = $individual_strategies_con[$i];
                        $item_data_con['created_date'] = datetimeformat();
                        $item_data_con['modified_date'] = datetimeformat();
                        $this->common_model->insert(pp_con, $item_data_con);
                    }
                }
				
				
		}	
				
		/*for other table
		nikunj ghelani
		23-11-2018
		for pp ft table data insert
		*/
		if(!empty($pp_insert_id) && $pp_insert_id!=''){
				$pp_insert_id=$pp_insert_id;
				$title_ft = $this->input->post('title_ft');
				$pre_placement_ft = $this->input->post('pre_placement_ft');
                $risk_assesment_ft = $this->input->post('risk_assesment_ft');
                $individual_strategies_ft = $this->input->post('individual_strategies_ft');
                $delete_cpt_review_ft_id = $this->input->post('delete_cpt_review_ft_id');
				
				
				 if (!empty($delete_cpt_review_ft_id)) {
				   $new_update++;
                    $delete_item = substr($delete_cpt_review_ft_id, 0, -1);
                    $delete_cpt_review_ft_id = explode(',', $delete_item);
                    $where1 = array('placement_plan_id' => $pp_insert_id);
                    $this->common_model->delete_where_in(pp_ft ,$where1, 'pp_ft_id', $delete_cpt_review_ft_id);
					
                }
				$where1 = array('placement_plan_id' => $pp_insert_id);
                $pp_ft_item = $this->common_model->get_records(pp_ft, array('pp_ft_id'), '', '', $where1, '');
				if (!empty($pp_ft_item)) {
					
                    for ($i = 0; $i < count($pp_ft_item); $i++) {
						 
                        if (!empty($postData['pre_placement_ft'.$pp_ft_item[$i]['pp_ft_id']]) || !empty($postData['risk_assesment_ft'.$pp_ft_item[$i]['pp_ft_id']]) || !empty($postData['individual_strategies_ft'.$pp_ft_item[$i]['pp_ft_id']])|| !empty($postData['title_ft'.$pp_ft_item[$i]['pp_ft_id']])) {
						
                            //get latest archive
                            $where1 = "placement_plan_id =" . $pp_insert_id . " and pp_ft_id =" . $pp_ft_item[$i]['pp_ft_id'];
                            $pp_ft_archive = $this->common_model->get_records(pp_ft_archive, array('*'), '', '', $where1, '', '1', '', 'pp_ft_id', 'desc', '', '');
							/* pr($cpt_item_archive);
							die; */
                            if (!empty($pp_ft_archive)) {
                                $old_cpt_title = str_replace(array("\r", "\n"), "", $pp_ft_archive[0]['pre_placement_ft']);

                                $post_cpt_title = str_replace(array("\r", "\n"), "", $postData['pre_placement_ft'.$pp_ft_item[$i]['pp_ft_id']]);

                                $old_cpt_reason = str_replace(array("\r", "\n"), "", $pp_ft_archive[0]['risk_assesment_ft']);

                                $post_cpt_reason = str_replace(array("\r", "\n"), "", $postData['risk_assesment_ft'.$pp_ft_item[$i]['pp_ft_id']]);
								
								$old_cpt_is = str_replace(array("\r", "\n"), "", $pp_ft_archive[0]['individual_strategies_ft']);

                                $post_cpt_is = str_replace(array("\r", "\n"), "", $postData['individual_strategies_ft'.$pp_ft_item[$i]['pp_ft_id']]);
								
								$old_cpt_heading = str_replace(array("\r", "\n"), "", $pp_ft_archive[0]['heading_ft']);

                                $post_cpt_heading = str_replace(array("\r", "\n"), "", $postData['title_ft'.$pp_ft_item[$i]['pp_ft_id']]);

                                if (($old_cpt_title != $post_cpt_title) || ($old_cpt_reason != $post_cpt_reason)|| ($old_cpt_is != $post_cpt_is)|| ($old_cpt_heading != $post_cpt_heading)) {
                                    $new_update++;
                                }
                            }
							
                            $update_item_ft['heading_ft'] = $postData['title_ft'.$pp_ft_item[$i]['pp_ft_id']];
                            $update_item_ft['pre_placement_ft'] = $postData['pre_placement_ft'.$pp_ft_item[$i]['pp_ft_id']];
                            $update_item_ft['risk_assesment_ft'] = $postData['risk_assesment_ft'.$pp_ft_item[$i]['pp_ft_id']];
                            $update_item_ft['individual_strategies_ft'] = $postData['individual_strategies_ft'.$pp_ft_item[$i]['pp_ft_id']];
                            $update_item_ft['modified_date'] = datetimeformat();
							/* pr($update_item);
							die; */
                            $where = array('pp_ft_id' => $pp_ft_item[$i]['pp_ft_id']);
                            $success_update = $this->common_model->update(pp_ft, $update_item_ft, $where);
                        }
                    }
                }
				$title_ft = $this->input->post('title_ft');
				$pre_placement_ft = $this->input->post('pre_placement_ft');
                $risk_assesment_ft = $this->input->post('risk_assesment_ft');
                $individual_strategies_ft = $this->input->post('individual_strategies_ft');
               for ($i = 0; $i < count($pre_placement_ft); $i++) {
                    if (!empty($pre_placement_ft[$i]) || !empty($risk_assesment_ft[$i]) || !empty($individual_strategies_ft[$i])|| !empty($title_ft[$i])) {
                        $new_change ++;
                        $item_data_ft['placement_plan_id'] = $pp_insert_id;
                        $item_data_ft['yp_id'] = $postData['yp_id'];
                        $item_data_ft['heading_ft'] = $title_ft[$i];
                        $item_data_ft['pre_placement_ft'] = $pre_placement_ft[$i];
                        $item_data_ft['risk_assesment_ft'] = $risk_assesment_ft[$i];
                        $item_data_ft['individual_strategies_ft'] = $individual_strategies_ft[$i];
                        $item_data_ft['created_date'] = datetimeformat();
                        $item_data_ft['modified_date'] = datetimeformat();
						
                        $this->common_model->insert(pp_ft, $item_data_ft);
                    }
                }
		}
		
		/*for other table
		nikunj ghelani
		23-11-2018
		for pp mgi table data insert
		*/
		if(!empty($pp_insert_id) && $pp_insert_id!=''){
				$pp_insert_id=$pp_insert_id;
				$title_mgi = $this->input->post('title_mgi');
				$pre_placement_mgi = $this->input->post('pre_placement_mgi');
                $risk_assesment_mgi = $this->input->post('risk_assesment_mgi');
                $individual_strategies_mgi = $this->input->post('individual_strategies_mgi');
                $delete_cpt_review_mgi_id = $this->input->post('delete_cpt_review_mgi_id');
				
				
				 if (!empty($delete_cpt_review_mgi_id)) {
				   $new_update++;
                    $delete_item = substr($delete_cpt_review_mgi_id, 0, -1);
                    $delete_cpt_review_mgi_id = explode(',', $delete_item);
                    $where1 = array('placement_plan_id' => $pp_insert_id);
                    $this->common_model->delete_where_in(pp_mgi ,$where1, 'pp_mgi_id', $delete_cpt_review_mgi_id);
					
                }
				$where1 = array('placement_plan_id' => $pp_insert_id);
                $pp_mgi_item = $this->common_model->get_records(pp_mgi, array('pp_mgi_id'), '', '', $where1, '');
				if (!empty($pp_mgi_item)) {
					
                    for ($i = 0; $i < count($pp_mgi_item); $i++) {
						 
                        if (!empty($postData['pre_placement_mgi'. $pp_mgi_item[$i]['pp_mgi_id']]) || !empty($postData['risk_assesment_mgi'. $pp_mgi_item[$i]['pp_mgi_id']]) || !empty($postData['individual_strategies_mgi'. $pp_mgi_item[$i]['pp_mgi_id']])|| !empty($postData['title_mgi'. $pp_mgi_item[$i]['pp_mgi_id']])) {
						
                            //get latest archive
                            $where1 = "placement_plan_id =" . $pp_insert_id . " and pp_mgi_id =" . $pp_mgi_item[$i]['pp_mgi_id'];
                            $pp_mgi_archive = $this->common_model->get_records(pp_mgi_archive, array('*'), '', '', $where1, '', '1', '', 'pp_mgi_id', 'desc', '', '');
							/* pr($cpt_item_archive);
							die; */
                            if (!empty($pp_mgi_archive)) {
                                $old_cpt_title = str_replace(array("\r", "\n"), "", $pp_mgi_archive[0]['pre_placement_mgi']);

                                $post_cpt_title = str_replace(array("\r", "\n"), "", $postData['pre_placement_mgi'. $pp_mgi_item[$i]['pp_mgi_id']]);

                                $old_cpt_reason = str_replace(array("\r", "\n"), "", $pp_mgi_archive[0]['risk_assesment_mgi']);

                                $post_cpt_reason = str_replace(array("\r", "\n"), "", $postData['risk_assesment_mgi'. $pp_mgi_item[$i]['pp_mgi_id']]);

								$old_cpt_is = str_replace(array("\r", "\n"), "", $pp_mgi_archive[0]['individual_strategies_mgi']);

                                $post_cpt_is = str_replace(array("\r", "\n"), "", $postData['individual_strategies_mgi'. $pp_mgi_item[$i]['pp_mgi_id']]);
								
								$old_cpt_heading = str_replace(array("\r", "\n"), "", $pp_mgi_archive[0]['heading_mgi']);

                                $post_cpt_heading = str_replace(array("\r", "\n"), "", $postData['title_mgi'. $pp_mgi_item[$i]['pp_mgi_id']]);

                                if (($old_cpt_title != $post_cpt_title) || ($old_cpt_reason != $post_cpt_reason) || ($old_cpt_is != $post_cpt_is)) {
                                    $new_update++;
                                }
                            }
							
                            $update_item_mgi['heading_mgi'] = $postData['title_mgi'. $pp_mgi_item[$i]['pp_mgi_id']];
                            $update_item_mgi['pre_placement_mgi'] = $postData['pre_placement_mgi'. $pp_mgi_item[$i]['pp_mgi_id']];
                            $update_item_mgi['risk_assesment_mgi'] = $postData['risk_assesment_mgi'. $pp_mgi_item[$i]['pp_mgi_id']];
                            $update_item_mgi['individual_strategies_mgi'] = $postData['individual_strategies_mgi'. $pp_mgi_item[$i]['pp_mgi_id']];
                            $update_item_mgi['modified_date'] = datetimeformat();
							/* pr($update_item);
							die; */
                            $where = array('pp_mgi_id' => $pp_mgi_item[$i]['pp_mgi_id']);
                            $success_update = $this->common_model->update(pp_mgi, $update_item_mgi, $where);
                        }
                    }
                }
				$title_mgi = $this->input->post('title_mgi');
				$pre_placement_ft = $this->input->post('pre_placement_ft');
                $risk_assesment_ft = $this->input->post('risk_assesment_ft');
                $individual_strategies_ft = $this->input->post('individual_strategies_ft');
              for ($i = 0; $i < count($pre_placement_mgi); $i++) {
                    if (!empty($pre_placement_mgi[$i]) || !empty($risk_assesment_mgi[$i]) || !empty($individual_strategies_mgi[$i])|| !empty($title_mgi[$i])) {
                        $new_change ++;
                        $item_data_mgi['placement_plan_id'] = $pp_insert_id;
                        $item_data_mgi['yp_id'] = $postData['yp_id'];
                        $item_data_mgi['heading_mgi'] = $title_mgi[$i];
                        $item_data_mgi['pre_placement_mgi'] = $pre_placement_mgi[$i];
                        $item_data_mgi['risk_assesment_mgi'] = $risk_assesment_mgi[$i];
                        $item_data_mgi['individual_strategies_mgi'] = $individual_strategies_mgi[$i];
                        $item_data_mgi['created_date'] = datetimeformat();
                        $item_data_mgi['modified_date'] = datetimeformat();
                        $this->common_model->insert(pp_mgi, $item_data_mgi);
                    }
                }
		}
		/*for other table
		nikunj ghelani
		23-11-2018
		for pp pr table data insert
		*/
		if(!empty($pp_insert_id) && $pp_insert_id!=''){
				$pp_insert_id=$pp_insert_id;
				$title_pr = $this->input->post('title_pr');
				$pre_placement_pr = $this->input->post('pre_placement_pr');
                $risk_assesment_pr = $this->input->post('risk_assesment_pr');
                $individual_strategies_pr = $this->input->post('individual_strategies_pr');
                $delete_cpt_review_pr_id = $this->input->post('delete_cpt_review_pr_id');
				
				
				 if (!empty($delete_cpt_review_pr_id)) {
				   $new_update++;
                    $delete_item = substr($delete_cpt_review_pr_id, 0, -1);
                    $delete_cpt_review_pr_id = explode(',', $delete_item);
                    $where1 = array('placement_plan_id' => $pp_insert_id);
                    $this->common_model->delete_where_in(pp_pr ,$where1, 'pp_pr_id', $delete_cpt_review_pr_id);
					
                }
				$where1 = array('placement_plan_id' => $pp_insert_id);
                $pp_pr_item = $this->common_model->get_records(pp_pr, array('pp_pr_id'), '', '', $where1, '');
				if (!empty($pp_pr_item)) {
					
                    for ($i = 0; $i < count($pp_pr_item); $i++) {
						 
                        if (!empty($postData['pre_placement_pr'. $pp_pr_item[$i]['pp_pr_id']]) || !empty($postData['risk_assesment_pr'. $pp_pr_item[$i]['pp_pr_id']]) || !empty($postData['individual_strategies_pr'. $pp_pr_item[$i]['pp_pr_id']])|| !empty($postData['title_pr'. $pp_pr_item[$i]['pp_pr_id']])) {
						
                            //get latest archive
                            $where1 = "placement_plan_id =" . $pp_insert_id . " and pp_pr_id =" . $pp_pr_item[$i]['pp_pr_id'];
                            $pp_pr_archive = $this->common_model->get_records(pp_pr_archive, array('*'), '', '', $where1, '', '1', '', 'pp_pr_id', 'desc', '', '');
							/* pr($cpt_item_archive);
							die; */
                            if (!empty($pp_pr_archive)) {
                                $old_cpt_title = str_replace(array("\r", "\n"), "", $pp_pr_archive[0]['pre_placement_pr']);

                                $post_cpt_title = str_replace(array("\r", "\n"), "", $postData['pre_placement_pr'. $pp_pr_item[$i]['pp_pr_id']]);

                                $old_cpt_reason = str_replace(array("\r", "\n"), "", $pp_pr_archive[0]['risk_assesment_pr']);

                                $post_cpt_reason = str_replace(array("\r", "\n"), "", $postData['risk_assesment_pr'. $pp_pr_item[$i]['pp_pr_id']]);

								$old_cpt_is = str_replace(array("\r", "\n"), "", $pp_pr_archive[0]['individual_strategies_pr']);

                                $post_cpt_is = str_replace(array("\r", "\n"), "", $postData['individual_strategies_pr'. $pp_pr_item[$i]['pp_pr_id']]);
								
								$old_cpt_heading = str_replace(array("\r", "\n"), "", $pp_pr_archive[0]['heading_pr']);

                                $post_cpt_heading = str_replace(array("\r", "\n"), "", $postData['title_pr'. $pp_pr_item[$i]['pp_pr_id']]);

                                if (($old_cpt_title != $post_cpt_title) || ($old_cpt_reason != $post_cpt_reason) || ($old_cpt_is != $post_cpt_is)|| ($old_cpt_heading != $post_cpt_heading)) {
                                    $new_update++;
                                }
                            }
							
                            $update_item_pr['heading_pr'] = $postData['title_pr'. $pp_pr_item[$i]['pp_pr_id']];
                            $update_item_pr['pre_placement_pr'] = $postData['pre_placement_pr'. $pp_pr_item[$i]['pp_pr_id']];
                            $update_item_pr['risk_assesment_pr'] = $postData['risk_assesment_pr'. $pp_pr_item[$i]['pp_pr_id']];
                            $update_item_pr['individual_strategies_pr'] = $postData['individual_strategies_pr'. $pp_pr_item[$i]['pp_pr_id']];
                            $update_item_pr['modified_date'] = datetimeformat();
							/* pr($update_item);
							die; */
                            $where = array('pp_pr_id' => $pp_pr_item[$i]['pp_pr_id']);
                            $success_update = $this->common_model->update(pp_pr, $update_item_pr, $where);
                        }
                    }
                }
			/* echo $this->db->last_query();
			die; */
			
				$title_pr = $this->input->post('title_pr');
				$pre_placement_ft = $this->input->post('pre_placement_ft');
                $risk_assesment_ft = $this->input->post('risk_assesment_ft');
                $individual_strategies_ft = $this->input->post('individual_strategies_ft');
				 for ($i = 0; $i < count($pre_placement_pr); $i++) {
						if (!empty($pre_placement_pr[$i]) || !empty($risk_assesment_pr[$i]) || !empty($individual_strategies_pr[$i])|| !empty($title_pr[$i])) {
							$new_change ++;
							$item_data_pr['placement_plan_id'] = $pp_insert_id;
							$item_data_pr['yp_id'] = $postData['yp_id'];
							$item_data_pr['heading_pr'] = $title_pr[$i];
							$item_data_pr['pre_placement_pr'] = $pre_placement_pr[$i];
							$item_data_pr['risk_assesment_pr'] = $risk_assesment_pr[$i];
							$item_data_pr['individual_strategies_pr'] = $individual_strategies_pr[$i];
							$item_data_pr['created_date'] = datetimeformat();
							$item_data_pr['modified_date'] = datetimeformat();
							$this->common_model->insert(pp_pr, $item_data_pr);
						}
					}		
		}
				
		/*for other table
		nikunj ghelani
		23-11-2018
		for bc pr table data insert
		*/
		if(!empty($pp_insert_id) && $pp_insert_id!=''){
				$pp_insert_id=$pp_insert_id;
				$title_bc = $this->input->post('title_bc');
				$pre_placement_bc = $this->input->post('pre_placement_bc');
                $risk_assesment_bc = $this->input->post('risk_assesment_bc');
                $individual_strategies_bc = $this->input->post('individual_strategies_bc');
                $delete_cpt_review_bc_id = $this->input->post('delete_cpt_review_bc_id');
				if (!empty($delete_cpt_review_bc_id)) {
				   $new_update++;
                    $delete_item = substr($delete_cpt_review_bc_id, 0, -1);
                    $delete_cpt_review_bc_id = explode(',', $delete_item);
                    $where1 = array('placement_plan_id' => $pp_insert_id);
                    $this->common_model->delete_where_in(pp_bc ,$where1, 'pp_bc_id', $delete_cpt_review_bc_id);
					
                }
				$where1 = array('placement_plan_id' => $pp_insert_id);
                $pp_bc_item = $this->common_model->get_records(pp_bc, array('pp_bc_id'), '', '', $where1, '');
				if (!empty($pp_bc_item)) {
					
                    for ($i = 0; $i < count($pp_bc_item); $i++) {
						 
                        if (!empty($postData['pre_placement_bc'. $pp_bc_item[$i]['pp_bc_id']]) || !empty($postData['risk_assesment_bc'. $pp_bc_item[$i]['pp_bc_id']]) || !empty($postData['individual_strategies_bc'. $pp_bc_item[$i]['pp_bc_id']])|| !empty($postData['title_bc'. $pp_bc_item[$i]['pp_bc_id']])) {
						
                            //get latest archive
                            $where1 = "placement_plan_id =" . $pp_insert_id . " and pp_bc_id =" . $pp_bc_item[$i]['pp_bc_id'];
                            $pp_bc_archive = $this->common_model->get_records(pp_bc_archive, array('*'), '', '', $where1, '', '1', '', 'pp_bc_id', 'desc', '', '');
							/* pr($cpt_item_archive);
							die; */
                            if (!empty($pp_bc_archive)) {
                                $old_cpt_title = str_replace(array("\r", "\n"), "", $pp_bc_archive[0]['pre_placement_bc']);

                                $post_cpt_title = str_replace(array("\r", "\n"), "", $postData['pre_placement_bc'. $pp_bc_item[$i]['pp_bc_id']]);

                                $old_cpt_reason = str_replace(array("\r", "\n"), "", $pp_bc_archive[0]['risk_assesment_bc']);

                                $post_cpt_reason = str_replace(array("\r", "\n"), "", $postData['risk_assesment_bc'. $pp_bc_item[$i]['pp_bc_id']]);

								$old_cpt_is = str_replace(array("\r", "\n"), "", $pp_bc_archive[0]['individual_strategies_bc']);

                                $post_cpt_is = str_replace(array("\r", "\n"), "", $postData['individual_strategies_bc'. $pp_bc_item[$i]['pp_bc_id']]);
								
								$old_cpt_heading = str_replace(array("\r", "\n"), "", $pp_bc_archive[0]['heading_bc']);

                                $post_cpt_heading = str_replace(array("\r", "\n"), "", $postData['title_bc'. $pp_bc_item[$i]['pp_bc_id']]);

                                if (($old_cpt_title != $post_cpt_title) || ($old_cpt_reason != $post_cpt_reason) || ($old_cpt_is != $post_cpt_is)|| ($old_cpt_heading != $post_cpt_heading)) {
                                    $new_update++;
                                }
                            }
							
                            $update_item_bc['heading_bc'] = $postData['title_bc'. $pp_bc_item[$i]['pp_bc_id']];
                            $update_item_bc['pre_placement_bc'] = $postData['pre_placement_bc'. $pp_bc_item[$i]['pp_bc_id']];
                            $update_item_bc['risk_assesment_bc'] = $postData['risk_assesment_bc'. $pp_bc_item[$i]['pp_bc_id']];
                            $update_item_bc['individual_strategies_bc'] = $postData['individual_strategies_bc'. $pp_bc_item[$i]['pp_bc_id']];
                            $update_item_bc['modified_date'] = datetimeformat();
							$where = array('pp_bc_id' => $pp_bc_item[$i]['pp_bc_id']);
                            $success_update = $this->common_model->update(pp_bc, $update_item_bc, $where);
                        }
                    }
                }
				$title_bc = $this->input->post('title_bc');
				$pre_placement_bc = $this->input->post('pre_placement_bc');
                $risk_assesment_bc = $this->input->post('risk_assesment_bc');
                $individual_strategies_bc = $this->input->post('individual_strategies_bc');

				for ($i = 0; $i < count($pre_placement_bc); $i++) {
                    if (!empty($pre_placement_bc[$i]) || !empty($risk_assesment_bc[$i]) || !empty($individual_strategies_bc[$i]) || !empty($title_bc[$i] )) {
                        $new_change ++;
                        $item_data_bc['placement_plan_id'] = $pp_insert_id;
                        $item_data_bc['yp_id'] = $postData['yp_id'];
                        $item_data_bc['heading_bc'] = $title_bc[$i];
                        $item_data_bc['pre_placement_bc'] = $pre_placement_bc[$i];
                        $item_data_bc['risk_assesment_bc'] = $risk_assesment_bc[$i];
                        $item_data_bc['individual_strategies_bc'] = $individual_strategies_bc[$i];
                        $item_data_bc['created_date'] = datetimeformat();
                        $item_data_bc['modified_date'] = datetimeformat();
                        $this->common_model->insert(pp_bc, $item_data_bc);
                    }
                }
		}
				
		
		
        $this->createArchive($postData['yp_id'],$pp_insert_id,$new_change, $new_update);
        redirect('/' . $this->viewname . '/save_pp/' . $data['yp_id']);
    }

    /*
      @Author : Niral Patel
      @Desc   : Save pp form
      @Input    :
      @Output   :
      @Date   : 12/07/2017
     */
   public function save_pp($id)
   {
      if(is_numeric($id))
      {
        //get YP information
          $match = array("yp_id"=>$id);
          $fields = array("*");
          $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
          if(empty($data['YP_details']))
          {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson/view/'.$id);
          }
        $data['id'] = $id;
        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['main_content'] = '/save_pp';
        $this->parser->parse('layouts/DefaultTemplate', $data);
      }
      else
      {
          show_404 ();
      }
   }

   public function DownloadPrint($pp_id,$yp_id,$section = NULL) {
	   
        $data = [];
        $match = array('pp_form_id'=> 1);
        $pp_forms = $this->common_model->get_records(PP_FORM,'', '', '', $match);
        if(!empty($pp_forms))
        {
            $data['pp_form_data'] = json_decode($pp_forms[0]['form_json_data'], TRUE);
        }
        //get YP information
        $table = YP_DETAILS . ' as yp';
        $match = array("yp.yp_id" => $yp_id);
        $fields = array("yp.*,pa.placing_authority_id,pa.authority,pa.address_1,pa.town,pa.county,pa.postcode,sd.mobile,sd.email,ch.care_home_name");
        $join_tables = array(PLACING_AUTHORITY . ' as pa' => 'pa.yp_id=yp.yp_id', SOCIAL_WORKER_DETAILS . ' as sd' => 'sd.yp_id=yp.yp_id',CARE_HOME . ' as ch' => 'ch.care_home_id = yp.care_home');
        $data['YP_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', '', '', '', '');
		
		    //get pp yp data
        $match = array('yp_id'=> $yp_id,'placement_plan_id'=> $pp_id,'is_previous_version'=>0);
        $data['edit_data'] = $this->common_model->get_records(PLACEMENT_PLAN,'', '', '', $match);

        
        $data['ypid'] = $yp_id;
		$match = array('yp_id'=> $yp_id);
        $data['edit_data_pp_health'] = $this->common_model->get_records(pp_health,'', '', '', $match);

		$data['ypid'] = $yp_id;
		$match = array('yp_id'=> $yp_id);
        $data['edit_data_pp_aim'] = $this->common_model->get_records(pp_aims_of_placement,'', '', '', $match);
		$match = array('yp_id'=> $yp_id,'placement_plan_id'=>$data['edit_data_pp_aim'][0]['placement_plan_id']);
        $data['edit_data_pp_aim_archive'] = $this->common_model->get_records(archive_pp_aims_of_placement,'', '', '', $match);
		$data['pp_aim_archve_data']=$data['edit_data_pp_aim_archive'][count($data['edit_data_pp_aim_archive'])-2];
		
		$data['ypid'] = $yp_id;
		$match = array('yp_id'=> $yp_id);
        $data['edit_data_pp_lac'] = $this->common_model->get_records(pp_actions_from_lac_review,'', '', '', $match);
		
		$match = array('yp_id'=> $yp_id,'placement_plan_id'=>$data['edit_data_pp_lac'][0]['placement_plan_id']);
        $data['edit_data_pp_lac_archive'] = $this->common_model->get_records(pp_archive_actions_from_lac_review,'', '', '', $match);
		$data['pp_lac_archve_data']=$data['edit_data_pp_lac_archive'][count($data['edit_data_pp_lac_archive'])-2];
		/* pr($data['edit_data_pp_health']);
		die; */
		
		//get pp health archive data
		$match = array('yp_id'=> $yp_id,'placement_plan_id'=>$data['edit_data_pp_health'][0]['placement_plan_id']);
        $data['edit_data_pp_health_archive'] = $this->common_model->get_records(pp_health_archive,'', '', '', $match);
		$data['pp_health_archve_data']=$data['edit_data_pp_health_archive'][count($data['edit_data_pp_health_archive'])-2];
		
		//get pp edu data
		$match = array('yp_id'=> $yp_id);
        $data['edit_data_pp_edu'] = $this->common_model->get_records(pp_edu,'', '', '', $match);
		/*pr($data['edit_data_pp_health']);
		die;*/
		
		
		//get pp edu archive data
		$match = array('yp_id'=> $yp_id,'placement_plan_id'=>$data['edit_data_pp_edu'][0]['placement_plan_id']);
        $data['edit_data_pp_edu_archive'] = $this->common_model->get_records(pp_edu_archive,'', '', '', $match);
		$data['pp_edu_archve_data']=$data['edit_data_pp_edu_archive'][count($data['edit_data_pp_edu_archive'])-2];

		 /*  pr($data['pp_edu_archve_data']);
		die;   */
        
		//get pp tra data
		$match = array('yp_id'=> $yp_id);
        $data['edit_data_pp_tra'] = $this->common_model->get_records(pp_tra,'', '', '', $match);
		/*pr($data['edit_data_pp_health']);
		die;*/
		
		//get pp edu archive data
		$match = array('yp_id'=> $yp_id,'placement_plan_id'=>$data['edit_data_pp_tra'][0]['placement_plan_id']);
        $data['edit_data_pp_tra_archive'] = $this->common_model->get_records(pp_tra_archive,'', '', '', $match);
		$data['pp_tra_archve_data']=$data['edit_data_pp_tra_archive'][count($data['edit_data_pp_tra_archive'])-2];

		

		 /*  pr($data['pp_edu_archve_data']);
		die;   */
        
		//get pp con data
		$match = array('yp_id'=> $yp_id);
        $data['edit_data_pp_con'] = $this->common_model->get_records(pp_con,'', '', '', $match);
		/*pr($data['edit_data_pp_health']);
		die;*/
		
		//get pp con archive data
		$match = array('yp_id'=> $yp_id,'placement_plan_id'=>$data['edit_data_pp_con'][0]['placement_plan_id']);
        $data['edit_data_pp_con_archive'] = $this->common_model->get_records(pp_con_archive,'', '', '', $match);
		$data['pp_con_archve_data']=$data['edit_data_pp_con_archive'][count($data['edit_data_pp_con_archive'])-2];

		 /*  pr($data['pp_edu_archve_data']);
		die;   */
		
		
		
		//get pp ft data
		$match = array('yp_id'=> $yp_id);
        $data['edit_data_pp_ft'] = $this->common_model->get_records(pp_ft,'', '', '', $match);
		/*pr($data['edit_data_pp_health']);
		die;*/
		//get pp ft archive data
		$match = array('yp_id'=> $yp_id,'placement_plan_id'=>$data['edit_data_pp_ft'][0]['placement_plan_id']);
        $data['edit_data_pp_ft_archive'] = $this->common_model->get_records(pp_ft_archive,'', '', '', $match);
		$data['pp_ft_archve_data']=$data['edit_data_pp_ft_archive'][count($data['edit_data_pp_ft_archive'])-2];

		 /*  pr($data['pp_edu_archve_data']);
		die;   */
		
		//get pp mgi data
		$match = array('yp_id'=> $yp_id);
        $data['edit_data_pp_mgi'] = $this->common_model->get_records(pp_mgi,'', '', '', $match);
		/*pr($data['edit_data_pp_health']);
		die;*/
		
		//get pp ft archive data
		$match = array('yp_id'=> $yp_id,'placement_plan_id'=>$data['edit_data_pp_mgi'][0]['placement_plan_id']);
        $data['edit_data_pp_mgi_archive'] = $this->common_model->get_records(pp_mgi_archive,'', '', '', $match);
		$data['pp_mgi_archve_data']=$data['edit_data_pp_mgi_archive'][count($data['edit_data_pp_mgi_archive'])-2];

		 /*  pr($data['pp_edu_archve_data']);
		die;   */
		
		
        
		//get pp pr data
		$match = array('yp_id'=> $yp_id);
        $data['edit_data_pp_pr'] = $this->common_model->get_records(pp_pr,'', '', '', $match);
		/*pr($data['edit_data_pp_health']);
		die;*/
		
		//get pp pr archive data
		$match = array('yp_id'=> $yp_id,'placement_plan_id'=>$data['edit_data_pp_pr'][0]['placement_plan_id']);
        $data['edit_data_pp_pr_archive'] = $this->common_model->get_records(pp_pr_archive,'', '', '', $match);
		$data['pp_pr_archve_data']=$data['edit_data_pp_pr_archive'][count($data['edit_data_pp_pr_archive'])-2];

		 /*  pr($data['pp_edu_archve_data']);
		die;   */
        
			//get pp bc data
			$match = array('yp_id'=> $yp_id);
			$data['edit_data_pp_bc'] = $this->common_model->get_records(pp_bc,'', '', '', $match);
			/*pr($data['edit_data_pp_health']);
			die;*/
		
		//get pp bc archive data
		$match = array('yp_id'=> $yp_id,'placement_plan_id'=>$data['edit_data_pp_bc'][0]['placement_plan_id']);
        $data['edit_data_pp_bc_archive'] = $this->common_model->get_records(pp_bc_archive,'', '', '', $match);
		$data['pp_bc_archve_data']=$data['edit_data_pp_bc_archive'][count($data['edit_data_pp_bc_archive'])-2];

        
        $data['main_content'] = '/pppdf';
        $data['section'] = $section;
        $html = $this->parser->parse('layouts/PDFTemplate', $data);
        $pdfFileName = "placementplan" . $pp_id . ".pdf";
        $pdfFilePath = FCPATH . 'uploads/placementplan/';
        if (!is_dir(FCPATH . 'uploads/placementplan/')) {
            @mkdir(FCPATH . 'uploads/placementplan/', 0777, TRUE);
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
            $html;
        } else {
            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($pdfFileName, "D");
        }
    }
   
   public function manager_review($yp_id,$pp_id) {
    if (!empty($yp_id)) {

      //get YP information
          $match = array("yp_id"=>$yp_id);
          $fields = array("*");
          $YP_details = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
          
          $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
          $match = array('yp_id'=> $yp_id,'pp_id'=>$pp_id,'created_by'=>$login_user_id,'is_delete'=> '0');
          $check_signoff_data = $this->common_model->get_records(PLACEMENT_PLAN_SIGNOFF,'', '', '', $match);
        if(empty($check_signoff_data) > 0){
          
          $update_pre_data['care_home_id'] = $YP_details[0]['care_home'];
          $update_pre_data['pp_id'] = $pp_id;
          $update_pre_data['yp_id'] = $yp_id;
          $update_pre_data['created_date'] = datetimeformat();
          $update_pre_data['created_by'] = $login_user_id;
        if ($this->common_model->insert(PLACEMENT_PLAN_SIGNOFF,$update_pre_data)) {
          $u_data['signoff'] = '1';
          $this->common_model->update(PLACEMENT_PLAN,$u_data,array('placement_plan_id'=> $pp_id,'yp_id'=> $yp_id));
          $msg = $this->lang->line('successfully_pp_review');
          $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
          //Redirect On Listing page
        } else {
          // error
          $msg = $this->lang->line('error_msg');
          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

        }
      }else{
        $msg = $this->lang->line('already_pp_review');
      $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

      }
    }else{      
      $msg = $this->lang->line('error_msg');
      $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
      
    }
    redirect('/' . $this->viewname .'/index/'.$yp_id);
  }


  public function update_slug() {
          $postData = $this->input->post();
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
    public function createArchive($id,$pp_insert_id,$new_change, $new_update)
    {
		
      if(is_numeric($id))
      {
         //get pp form
         $match = array('pp_form_id'=> 1);
         $formsdata = $this->common_model->get_records(PP_FORM,array("*"), '', '', $match);
        
         //get pp yp data
         $match = array('yp_id'=> $id);
         $pp_yp_data = $this->common_model->get_records(PLACEMENT_PLAN,array("*"), '', '', $match);
		  
		      
           //get YP information
          $match = array("yp_id"=>$id);
          $fields = array("*");
          $YP_details = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
         if(!empty($pp_yp_data))
         {
			  
			 $match = array('yp_id'=> $id,'is_previous_version'=>1);
			 $data['prev_edit_data'] = $this->common_model->get_records(PLACEMENT_PLAN,'', '', '', $match);
					 
				$last_modified_date=$data['prev_edit_data'][0]['modified_date'];
			
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
                            echo $pp_yp_data[0][$row['name']];
                          }else{
                            $pp_form_data[$i]['value'] = str_replace("'", '"', $pp_yp_data[0][$row['name']]);
                          }
                      }
                  }
                  $i++;
              }
        //strip_slashes($pp_yp_data[0]['mdt_report_id']);
              $archive = array(
                  'yp_id' => $id,
                  'signoff' => $pp_yp_data[0]['signoff'],
                  'form_json_data' =>json_encode($pp_form_data, TRUE),
                  'created_by'=>$this->session->userdata('LOGGED_IN')['ID'],
                  'created_date'=>datetimeformat(),
                  'modified_date'=>$last_modified_date,
                  'modified_time'=>date("H:i:s"),
                  'care_home_id' => $YP_details[0]['care_home'],
                  'pre_placement_info' => strip_slashes($pp_yp_data[0]['pre_placement_info']),
                  'pre_placement_family' => strip_slashes($pp_yp_data[0]['pre_placement_family']),
                  'pre_placement_edu' => strip_slashes($pp_yp_data[0]['pre_placement_edu']),
                  'pre_placement_relation' => strip_slashes($pp_yp_data[0]['pre_placement_relation']),
                  'pre_placement_school' => strip_slashes($pp_yp_data[0]['pre_placement_school']),
                  'pre_placement_contact' => strip_slashes($pp_yp_data[0]['pre_placement_contact']),
                  'pre_placement_therapy' => strip_slashes($pp_yp_data[0]['pre_placement_therapy']),
                  'pre_placement_appointment' => strip_slashes($pp_yp_data[0]['pre_placement_appointment']),
                  'status'=>0
              );
            
             //get pp yp data
              $match = array('form_json_data'=> json_encode($pp_form_data, TRUE));
              $wherestring = " form_json_data = '".str_replace("\\","\\\\", json_encode($pp_form_data, TRUE))."'";

              $pp_archive_data = $this->common_model->get_records(PLACEMENT_PLAN_ARCHIVE,array('yp_id'), '', '', '','','','','','','',$wherestring);
			  
			  $match = array('yp_id'=> $id,'status'=>0);
               $archive_old_data = $this->common_model->get_records(PLACEMENT_PLAN_ARCHIVE,'', '', '', $match);
			 //pr($pp_yp_data);
			 
			  if(!empty($archive_old_data))
                 {
					 if((strip_tags($pp_yp_data[0]['pre_placement_info']) != strip_tags($archive_old_data[0]['pre_placement_info'])) || (strip_tags($pp_yp_data[0]['pre_placement_family']) != strip_tags($archive_old_data[0]['pre_placement_family'])) || (strip_tags($pp_yp_data[0]['pre_placement_edu']) != strip_tags($archive_old_data[0]['pre_placement_edu'])) || (strip_tags($pp_yp_data[0]['pre_placement_relation']) != strip_tags($archive_old_data[0]['pre_placement_relation'])) || (strip_tags($pp_yp_data[0]['pre_placement_school']) != strip_tags($archive_old_data[0]['pre_placement_school'])) || (strip_tags($pp_yp_data[0]['pre_placement_contact']) != strip_tags($archive_old_data[0]['pre_placement_contact']))|| (strip_tags($pp_yp_data[0]['pre_placement_therapy']) != strip_tags($archive_old_data[0]['pre_placement_therapy'])) || (strip_tags($pp_yp_data[0]['pre_placement_appointment']) != strip_tags($archive_old_data[0]['pre_placement_appointment'])))
                    {
                      $new_change++;
                    }
				 }
             
             if(empty($pp_archive_data)  || $new_change > 0 ||  $new_update > 0)
             {
                  //get pp yp data
                 $match = array('yp_id'=> $id);
                 $archive_data = $this->common_model->get_records(PLACEMENT_PLAN_ARCHIVE,array("*"), '', '', $match);
                 
                 
                 if(empty($archive_data))
                 {
                    $archive_insert = array(
                        'yp_id' => $id,
                        'form_json_data' =>json_encode($pp_form_data, TRUE),
                        'created_by'=>$this->session->userdata('LOGGED_IN')['ID'],
                        'created_date'=>datetimeformat(),
						            'modified_date'=>$last_modified_date,
						            'modified_time'=>date("H:i:s"),
                        'status'=>1
                    );
                    //insert archive for next time
                 }
                 else
                 {
					
                    //update status to archive
                    $update_archive = array(
      						'modified_date'=>$last_modified_date,
			       			'modified_time'=>date("H:i:s"),
                        'status'=>1,
                        'signoff' => $pp_yp_data[1]['signoff']
                    );
                    $where = array('status'=>0,'yp_id'=>$id);
                    $this->common_model->update(PLACEMENT_PLAN_ARCHIVE, $update_archive,$where);
                 }

                 //insert archive data for next time
                 $this->common_model->insert(PLACEMENT_PLAN_ARCHIVE, $archive);
                 $archive_plan_id = $this->db->insert_id();
				 //echo $id;
				 
				 /*MDT_CARE_PLAN_TARGET*/
				 
                  $table = pp_aims_of_placement . ' as pa';
                  $match = array('pa.placement_plan_id	'=> $pp_insert_id);
                  $fields = array("pa.*");
                  $pp_aim_target = $this->common_model->get_records($table, $fields,'', '', $match); 
                 
				          if(!empty($pp_aim_target))
                  {
                    foreach ($pp_aim_target as $row) {
                      
                        foreach ($row as $key => $value) {
                          $aim_ar[$key] = $value;
                        }
                        $aim_ar['pp_aim_archive_id'] = $archive_plan_id;
                        $this->common_model->insert(archive_pp_aims_of_placement, $aim_ar);
                    }
                    
                  }
                  /*end MDT_CARE_PLAN_TARGET*/
				  
				  /*LAC FOR PLACEMENT PLAN*/
				 
                  $table = pp_actions_from_lac_review . ' as pLC';
                  $match = array('pLC.placement_plan_id	'=> $pp_insert_id);
                  $fields = array("pLC.*");
                  $pp_lac_target = $this->common_model->get_records($table, $fields,'', '', $match); 
                 
				          if(!empty($pp_lac_target))
                  {
                    foreach ($pp_lac_target as $row) {
                      
                        foreach ($row as $key => $value) {
                          $lac_ar[$key] = $value;
                        }
                        $lac_ar['pp_lac_archive_id'] = $archive_plan_id;
                        $this->common_model->insert(pp_archive_actions_from_lac_review, $lac_ar);
                    }
                    
                  }
                  /*end LAC OF PLACEMENT OF PLAN*/
				  
				  /*MDT_CARE_PLAN_TARGET*/
				 
                  $table = pp_health . ' as ph';
                  $match = array('ph.placement_plan_id	'=> $pp_insert_id);
                  $fields = array("ph.*");
                  $care_plan_target = $this->common_model->get_records($table, $fields,'', '', $match); 
                 
				          if(!empty($care_plan_target))
                  {
                    foreach ($care_plan_target as $row) {
                      
                        foreach ($row as $key => $value) {
                          $cpt_ar[$key] = $value;
                        }
                        $cpt_ar['pp_health_archive_id'] = $archive_plan_id;
                        $this->common_model->insert(pp_health_archive, $cpt_ar);
                    }
                    
                  }
                  /*end MDT_CARE_PLAN_TARGET*/
				  
				  
				   /*EDU archvie table start*/
                  $table = pp_edu . ' as pe';
                  $match = array('pe.placement_plan_id	'=> $pp_insert_id);
                  $fields = array("pe.*");
                  $edu_data= $this->common_model->get_records($table, $fields,'', '', $match);
                  if(!empty($edu_data))
                  {
                    foreach ($edu_data as $row) {
                        foreach ($row as $key => $value) {
                          $cpt_ar_edu[$key] = $value;
                        }
                        $cpt_ar_edu['pp_edu_archive_id'] = $archive_plan_id;
                        $this->common_model->insert(pp_edu_archive, $cpt_ar_edu);

                    }
                  }

                  /*EDU archvie table end*/

				  /*TRA archvie table start*/
                  $table = pp_tra . ' as pr';
                  $match = array('pr.placement_plan_id	'=> $pp_insert_id);
                  $fields = array("pr.*");
                  $tra_data= $this->common_model->get_records($table, $fields,'', '', $match);
                  
                  if(!empty($tra_data))
                  {
                    foreach ($tra_data as $row) {
                        $row['pp_tra_archive_id'] = $archive_plan_id;
                        $this->common_model->insert(pp_tra_archive, $row);
                    }
                    
                  }
                  /*TRA archvie table end*/

				  /*CON archvie table start*/
                  $table = pp_con . ' as pc';
                  $match = array('pc.placement_plan_id	'=> $pp_insert_id);
                  $fields = array("pc.*");
                  $con_data= $this->common_model->get_records($table, $fields,'', '', $match);
                  
                  if(!empty($con_data))
                  {
                    foreach ($con_data as $row) {
                        $row['pp_con_archive_id'] = $archive_plan_id;
                        $this->common_model->insert(pp_con_archive, $row);
                    }
                    
                  }
                  /*CON archvie table end*/
				  
				  /*FT archvie table start*/
                  $table = pp_ft . ' as pft';
                  $match = array('pft.placement_plan_id	'=> $pp_insert_id);
                  $fields = array("pft.*");
                  $ft_data= $this->common_model->get_records($table, $fields,'', '', $match);
                  
                  if(!empty($ft_data))
                  {
                    foreach ($ft_data as $row) {
                        $row['pp_ft_archive_id'] = $archive_plan_id;
                        $this->common_model->insert(pp_ft_archive, $row);
                    }
                    
                  }
                  /*FT archvie table end*/
				  
				  /*MGI archvie table start*/
                  $table = pp_mgi . ' as pmgi';
                  $match = array('pmgi.placement_plan_id	'=> $pp_insert_id);
                  $fields = array("pmgi.*");
                  $mgi_data= $this->common_model->get_records($table, $fields,'', '', $match);
                  
                  if(!empty($mgi_data))
                  {
                    foreach ($mgi_data as $row) {
                        $row['pp_mgi_archive_id'] = $archive_plan_id;
                        $this->common_model->insert(pp_mgi_archive, $row);
                    }
                    
                  }
                  /*MGI archvie table end*/
				  
				  /*FT archvie table end*/
				  
				  /*pr archvie table start*/
                  $table = pp_pr . ' as pr';
                  $match = array('pr.placement_plan_id	'=> $pp_insert_id);
                  $fields = array("pr.*");
                  $pr_data= $this->common_model->get_records($table, $fields,'', '', $match);
                  
                  if(!empty($pr_data))
                  {
                    foreach ($pr_data as $row) {
                        $row['pp_pr_archive_id'] = $archive_plan_id;
                        $this->common_model->insert(pp_pr_archive, $row);
                    }
                    
                  }
                  /*pr archvie table end*/
				  
				  /*bc archvie table start*/
                  $table = pp_bc . ' as pbc';
                  $match = array('pbc.placement_plan_id	'=> $pp_insert_id);
                  $fields = array("pbc.*");
                  $bc_data= $this->common_model->get_records($table, $fields,'', '', $match);
                  
                  if(!empty($bc_data))
                  {
                    foreach ($bc_data as $row) {
                        $row['pp_bc_archive_id'] = $archive_plan_id;
                        $this->common_model->insert(pp_bc_archive, $row);
                    }
                    
                  }
                  /*pr archvie table end*/
				  
				  
                  if($archive_plan_id > 1){
                    $archive_plan_id = $archive_plan_id - 1 ;
                  }
                 
               //  echo $archive_plan_id;exit;

              $table = PLACEMENT_PLAN_SIGNOFF.' as pps';
              $where = array("pps.yp_id" => $id,"pps.is_delete"=> "0");
              $fields = array("pps.created_by,pps.yp_id,pps.pp_id,pps.created_date");
              $group_by = array('created_by');
              $signoff_data = $this->common_model->get_records($table,$fields,'','','','','','','','',$group_by,$where);

          if(!empty($signoff_data)){
              foreach ($signoff_data as $archive_value) {
                  $update_arc_data['archive_pp_id'] = $archive_plan_id;
                  $update_arc_data['yp_id'] = $archive_value['yp_id'];
                  $update_arc_data['created_date'] = $archive_value['created_date'];
                  $update_arc_data['created_by'] = $archive_value['created_by'];
                  $this->common_model->insert(NFC_ARCHIVE_PLACEMENT_PLAN_SIGNOFF,$update_arc_data);      
              }
              
                   $archive = array('is_delete'=>1);
                    $where = array('yp_id'=>$id);
                    $this->common_model->update(PLACEMENT_PLAN_SIGNOFF, $archive,$where);
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
      @Author : Ritesh Rana
      @Desc   : open popup for sign off functionality
      @Input    :
      @Output   :
      @Date   : 18/08/2017
     */

public function signoff($yp_id,$pp_id) {
        $this->formValidation();

        $main_user_data = $this->session->userdata('LOGGED_IN');
        if ($this->form_validation->run() == FALSE) {

            $data['footerJs'][0] = base_url('uploads/custom/js/placementplan/placementplan.js');
            $data['crnt_view'] = $this->viewname;

            //get YP information
            $match = array("yp_id"=>$yp_id);
            $fields = array("*");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

            
            $data['care_home_id'] = $data['YP_details'][0]['care_home'];
            $data['ypid']= $yp_id;
            $data['pp_id']= $pp_id;

          $match = array('m.yp_id'=> $yp_id,'m.status'=>0);
          $join_tables_report = array(LOGIN . ' as l' => 'l.login_id= m.created_by');
          $fields_report = array("m.*");
          $prev_archive_edit = $this->common_model->get_records(PLACEMENT_PLAN_ARCHIVE.' as m',$fields_report, $join_tables_report, 'left', $match,'','1','','pp_archive_id','desc');
          
          $data['pp_archive_id'] = !empty($prev_archive_edit[0]['pp_archive_id'])?$prev_archive_edit[0]['pp_archive_id']:'';

            //Get Records From Login Table
            $data['userType'] = getUserType($main_user_data['ROLE_TYPE']);
            $data['initialsId'] = $this->common_model->initialsId();
            //get social info
            $table = SOCIAL_WORKER_DETAILS . ' as sw';
            $match = array("sw.yp_id" => $yp_id);
            $fields = array("sw.*");
            $data['social_worker_data'] = $this->common_model->get_records($table, $fields, '', '', $match);

            //get parent info
            $table = PARENT_CARER_INFORMATION . ' as pc';
            $match = array("pc.yp_id" => $yp_id,'pc.is_deleted' => 0);
            $fields = array("pc.*");
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
      @Author : Ritesh Rana
      @Desc   : insert signoff data
      @Input    :
      @Output   :
      @Date   : 18/08/2017
     */

    public function insertdata() {
        $postdata = $this->input->post();
        
        $ypid = $postdata['ypid'];
        $pp_id = $postdata['pp_id'];
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
          $success = $this->common_model->insert(PARENT_CARER_INFORMATION, $parent_data);
          //Insert log activity
          $activity = array(
              'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
              'yp_id' => !empty($postdata['ypid']) ? $postdata['ypid'] : '',
              'module_name' => PP_PARENT_CARER_DETAILS_YP,
              'module_field_name' => '',
              'type' => 1
          );
          log_activity($activity);
        }
        $table = YP_DETAILS . ' as yp';
        $match = "yp.is_deleted= '0' AND yp.yp_id = '" . $ypid . "'";
        $fields = array("yp.yp_id,yp.status");
        $duplicateEmail = $this->common_model->get_records($table, $fields, '', '', $match);
            //Current Login detail
        $main_user_data = $this->session->userdata('LOGGED_IN');
        
            if (!validateFormSecret()) {
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
                redirect('YoungPerson/view/'.$ypid);
            }
            $data['crnt_view'] = $this->viewname;

            $match = array('pp_form_id'=> 1);
         $formsdata = $this->common_model->get_records(PP_FORM,array("*"), '', '', $match);
        
         //get pp yp data
         $match = array('yp_id'=> $ypid);
         $pp_yp_data = $this->common_model->get_records(PLACEMENT_PLAN,array("*"), '', '', $match);
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
                            echo $pp_yp_data[0][$row['name']];
                          }else{
                            $pp_form_data[$i]['value'] = str_replace("'", '"', $pp_yp_data[0][$row['name']]);
                          }
                      }
                  }
                  $i++;
              }
        
             } 
            $data = array(
                'user_type' => ucfirst($postdata['user_type']),
                'yp_id' => ucfirst($postdata['ypid']),
                'care_home_id' => $postdata['care_home_id'],
                'pp_id' => ucfirst($postdata['pp_id']),
                'pp_archive_id' => ucfirst($postdata['pp_archive_id']),
                'form_json_data' =>json_encode($pp_form_data, TRUE),
                'fname' => ucfirst($postdata['fname']),
                'lname' => ucfirst($postdata['lname']),
                'email' => $postdata['email'],
                'key_data' => md5($postdata['email']),
                'created_date' => datetimeformat(),
                'created_by' => $main_user_data['ID'],
                'updated_by' => $main_user_data['ID'],
                'pre_placement_info' => $pp_yp_data[0]['pre_placement_info'],
                'pre_placement_family' => $pp_yp_data[0]['pre_placement_family'],
                'pre_placement_edu' => $pp_yp_data[0]['pre_placement_edu'],
                'pre_placement_relation' => $pp_yp_data[0]['pre_placement_relation'],
                'pre_placement_school' => $pp_yp_data[0]['pre_placement_school'],
                'pre_placement_contact' => $pp_yp_data[0]['pre_placement_contact'],
                'pre_placement_therapy' => $pp_yp_data[0]['pre_placement_therapy'],
                'pre_placement_appointment' => $pp_yp_data[0]['pre_placement_appointment'],
            );
            
            //Insert Record in Database
            if ($this->common_model->insert(NFC_SIGNOFF_DETAILS, $data)) {

            $signoff_id = $this->db->insert_id();

                 $table = PLACEMENT_PLAN_SIGNOFF.' as pps';
              $where = array("pps.yp_id" => $ypid,"pps.is_delete"=> "0");
              $fields = array("pps.created_by,pps.yp_id,pps.pp_id,pps.created_date");
              $group_by = array('created_by');
              $signoff_data = $this->common_model->get_records($table,$fields,'','','','','','','','',$group_by,$where);
              
          if(!empty($signoff_data)){
              foreach ($signoff_data as $archive_value) {
                  $update_arc_data['approval_pp_id'] = $signoff_id;
                  $update_arc_data['yp_id'] = $archive_value['yp_id'];
                  $update_arc_data['created_date'] = $archive_value['created_date'];
                  $update_arc_data['created_by'] = $archive_value['created_by'];
                  $this->common_model->insert(NFC_APPROVAL_PLACEMENT_PLAN_SIGNOFF,$update_arc_data);
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
        
        redirect('PlacementPlan/index/' . $ypid);
    }


/*
      @Author : Ritesh Rana
      @Desc   : send email with signoff approval link page
      @Input    :
      @Output   :
      @Date   : 18/08/2017
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
            $loginLink = base_url('PlacementPlan/signoffData/' . $data['yp_id'] . '/' . $signoff_id .'/'. $email);

            $find = array('{NAME}','{LINK}');

            $replace = array(
                'NAME' => $customerName,
                'LINK' => $loginLink,
            );
            
            $emailSubject = 'Welcome to NFCTracker';
                    $emailBody = '<div>'
                    . '<p>Hello {NAME} ,</p> '
                    . '<p>Please find Placement Plan for '.$yp_name.' for your approval.</p> '
                    . "<p>For security purposes, Please do not forward this email on to any other person. It is for the recipient only and if this is sent in error please advise itsupport@newforestcare.co.uk and delete this email. This link is only valid for ".REPORT_EXPAIRED_HOUR.", should this not be signed off within ".REPORT_EXPAIRED_HOUR." of recieving then please request again</p>"
                    . '<p> <a href="{LINK}">click here</a> </p> '
                    . '<div>';

            $finalEmailBody = str_replace($find,$replace,$emailBody);
            
            return $this->common_model->sendEmail($toEmailId, $emailSubject, $finalEmailBody, FROM_EMAIL_ID);
        }
        return true;
    }

     public function formValidation($id = null) {
        $this->form_validation->set_rules('fname', 'Firstname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('lname', 'Lastname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
        
    }


/*
      @Author : Ritesh Rana
      @Desc   : view signoff approval link page
      @Input    :
      @Output   :
      @Date   : 18/08/2017
     */

     public function signoffData($id,$signoff_id,$email) {
		   if(is_numeric($id) && is_numeric($signoff_id) && !empty($email))
       {
          $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
          $match = array('yp_id'=> $id,'key_data'=> $email,'status'=>'inactive',' signoff_details_id'=>$signoff_id);
          $check_signoff_data = $this->common_model->get_records(NFC_SIGNOFF_DETAILS,array("*"), '', '', $match);
        if(!empty($check_signoff_data)){ 
           $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'].REPORT_EXPAIRED_DAYS));
          if(strtotime(datetimeformat()) <= strtotime($expairedDate))
          {
            
             $pp_id = $check_signoff_data[0]['pp_id'];
             $pp_archive_id = $check_signoff_data[0]['pp_archive_id'];

         $match = array('signoff_details_id'=> $signoff_id);
         $formsdata = $this->common_model->get_records(NFC_SIGNOFF_DETAILS,'', '', '', $match);
		 
		 $data['formsdata'] = $formsdata;
         
         if(!empty($formsdata))
         {
              $data['pp_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
         }
          
          //get YP information
          $match = array("yp_id"=>$id);
          $fields = array("*");
          $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
         if(!empty($data['edit_data'][0]['modified_by'])){
             $data['updated_by'] = getUserName($data['edit_data'][0]['modified_by']);
         }else{
             $data['updated_by'] = "";
         } 
          
          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$id);
          }
           //get pp yp data
              $match = array('pp_archive_id'=>$pp_archive_id,'yp_id'=> $id);
              $join_tables = array(LOGIN . ' as l' => 'l.login_id= m.created_by');
              $fields = array("m.*,l.login_id,concat(l.firstname,' ',l.lastname) as case_manager_name");
              $data['edit_data'] = $this->common_model->get_records(PLACEMENT_PLAN_ARCHIVE.' as m',$fields,$join_tables, 'left', $match);


            //get MDT_CARE_PLAN_TARGET_ARCHIVE 
            $where1 = array('pp_health_archive_id' => $pp_archive_id,'yp_id'=>$id);
            $data['edit_data_pp_health'] = $this->common_model->get_records(pp_health_archive, '', '', '', $where1, '','','','','','','');
			
			$where1 = array('pp_aim_archive_id' => $pp_archive_id,'yp_id'=>$id);
            $data['edit_data_pp_aim'] = $this->common_model->get_records(archive_pp_aims_of_placement, '', '', '', $where1, '','','','','','','');
			
			$where1 = array('pp_lac_archive_id' => $pp_archive_id,'yp_id'=>$id);
            $data['edit_data_pp_lac'] = $this->common_model->get_records(pp_archive_actions_from_lac_review, '', '', '', $where1, '','','','','','','');
            //get pp edu archive data
            $where1 = array('pp_edu_archive_id' => $pp_archive_id,'yp_id'=>$id);
            $data['edit_data_pp_edu'] = $this->common_model->get_records(pp_edu_archive, '', '', '', $where1, '','','','','','','');
            
          //get pp tra archive data
             $where1 = array('pp_tra_archive_id' => $pp_archive_id,'yp_id'=>$id);
            $data['edit_data_pp_tra'] = $this->common_model->get_records(pp_tra_archive, '', '', '', $where1, '','','','','','','');
            
    //get pp con archive data
      $where1 = array('pp_con_archive_id' => $pp_archive_id,'yp_id'=>$id);
            $data['edit_data_pp_con'] = $this->common_model->get_records(pp_con_archive, '', '', '', $where1, '','','','','','','');      
      

    //get pp ft archive data
      $where1 = array('pp_ft_archive_id' => $pp_archive_id,'yp_id'=>$id);
      $data['edit_data_pp_ft'] = $this->common_model->get_records(pp_ft_archive, '', '', '', $where1, '','','','','','','');  


    //get pp ft archive data
       $where1 = array('pp_mgi_archive_id' => $pp_archive_id,'yp_id'=>$id);
      $data['edit_data_pp_mgi'] = $this->common_model->get_records(pp_mgi_archive, '', '', '', $where1, '','','','','','',''); 

    //get pp pr archive data
     $where1 = array('pp_pr_archive_id' => $pp_archive_id,'yp_id'=>$id);
      $data['edit_data_pp_pr'] = $this->common_model->get_records(pp_pr_archive, '', '', '', $where1, '','','','','','',''); 
    
    //get pp bc archive data
     $where1 = array('pp_bc_archive_id' => $pp_archive_id,'yp_id'=>$id);
     $data['edit_data_pp_bc'] = $this->common_model->get_records(pp_bc_archive, '', '', '', $where1, '','','','','','',''); 
        

         
        $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
        $table = NFC_APPROVAL_PLACEMENT_PLAN_SIGNOFF.' as pps';
      $where = array("l.is_delete"=> "0","pps.yp_id" => $id,"pps.is_delete"=> "0","pps.approval_pp_id" =>$signoff_id);
        $fields = array("pps.created_by,pps.created_date,pps.yp_id,pps.approval_pp_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id=pps.created_by');
        $group_by = array('created_by');
        $data['signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);

          $data['signoff_id'] = $signoff_id;
          $data['key_data'] = $email;
          $data['ypid'] = $id;
          $data['footerJs'][0] = base_url('uploads/custom/js/placementplan/placementplan.js');
          
          $data['crnt_view'] = $this->viewname;
          $data['header'] = array('menu_module' => 'YoungPerson');
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
              //show_404 (); 
              $msg = $this->lang->line('already_pp_review');
      $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
 $this->load->view('successfully_message');
        }
         //get pp form
        }else
        {
            show_404 ();
        }
    }

/*
      @Author : Ritesh Rana
      @Desc   : signoff review functionality
      @Input    :
      @Output   :
      @Date   : 18/08/2017
     */

public function signoff_review_data($yp_id,$signoff_id,$email) {
    if (!empty($yp_id) && !empty($signoff_id) && !empty($email)) {
          $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
          $match = array('yp_id'=> $yp_id,'key_data'=> $email,'status'=>'inactive',' signoff_details_id'=>$signoff_id);
          $check_signoff_data = $this->common_model->get_records(NFC_SIGNOFF_DETAILS,array("*"), '', '', $match);
        if(!empty($check_signoff_data)){
          $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'].REPORT_EXPAIRED_DAYS));
          if(strtotime(datetimeformat()) <= strtotime($expairedDate))
          {
              $u_data['status'] = 'active';
              $u_data['modified_date'] = datetimeformat();
              $success =$this->common_model->update(NFC_SIGNOFF_DETAILS,$u_data,array('yp_id'=> $yp_id,'key_data'=> $email,'signoff_details_id'=>$signoff_id));
            if ($success) {
              
              $msg = $this->lang->line('successfully_pp_review');
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
        $msg = $this->lang->line('already_pp_review');
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
      @Desc   : view mc data
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */
    public function external_approval_list($ypid,$pp_id,$care_home_id=0,$past_care_id=0) {

       /*
                Ritesh Rana
                for past care id inserted for archive full functionality
                */
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

        if(is_numeric($ypid) && is_numeric($pp_id)){
        $match = "yp_id = " . $ypid;
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

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
            $this->session->unset_userdata('pp_approval_session_data');
        }

        $searchsort_session = $this->session->userdata('pp_approval_session_data');
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
                $sortfield = 'signoff_details_id';
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
        /* condition added by Ritesh Ranan on 02/10/2018 to archive functionality */
        if($past_care_id == 0){
           $config['base_url'] = base_url() . $this->viewname . '/external_approval_list/'.$ypid.'/'.$pp_id;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 5;
            $uri_segment = $this->uri->segment(5);
        }
        //Query
 
        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = NFC_SIGNOFF_DETAILS . ' as pp';
        $where = array("pp.yp_id"=>$ypid,"pp.pp_id"=>$pp_id);
        $fields = array("pp.*,CONCAT(`firstname`,' ', `lastname`) as create_name ,CONCAT(`fname`,' ', `lname`) as user_name,ch.care_home_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= pp.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = pp.care_home_id');
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
        }
        }else{

           $config['base_url'] = base_url() . $this->viewname . '/external_approval_list/'.$ypid.'/'.$pp_id.'/'.$care_home_id.'/'.$past_care_id;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 7;
            $uri_segment = $this->uri->segment(7);
        }
        //Query

        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = NFC_SIGNOFF_DETAILS . ' as pp';
        $where = array("pp.yp_id"=>$ypid,"pp.pp_id"=>$pp_id);
        $where_date = "pp.created_date BETWEEN  '".$created_date."' AND '".$movedate."'";
        $fields = array("pp.*,CONCAT(`firstname`,' ', `lastname`) as create_name ,CONCAT(`fname`,' ', `lname`) as user_name,ch.care_home_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= pp.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = pp.care_home_id');
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where,'','','','','',$where_date);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1','','',$where_date);
        }
        }

        $data['ypid'] = $ypid;
        $data['pp_id'] = $pp_id;
        /* below parameter added by Ritesh Rana on 02/10/2018 to archive functionality */
        $data['care_home_id'] = $care_home_id;
        $data['past_care_id'] = $past_care_id;

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

        $this->session->set_userdata('pp_approval_session_data', $sortsearchpage_data);
        setActiveSession('pp_approval_session_data'); // set current Session active
        $data['header'] = array('menu_module' => 'Communication');

        //get YP information
       
        //get communication form

        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/placementplan/placementplan.js');
        $data['header'] = array('menu_module' => 'YoungPerson');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/pp_ajaxlist', $data);
        } else {
            $data['main_content'] = '/pp_list';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
        }else{
              show_404 ();
        } 
    }

    /*
      @Author : Ritesh Rana
      @Desc   : view approval page
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */

public function view($id,$ypid,$care_home_id=0,$past_care_id=0)                         
    {
	 
        $match = array('yp_id'=> $ypid,'signoff_details_id'=>$id);
        $signoff_data = $this->common_model->get_records(NFC_SIGNOFF_DETAILS,array("*"), '', '', $match);
        $pp_archive_id = $signoff_data[0]['pp_archive_id'];

   //get pp yp data
              $match = array('pp_archive_id'=>$pp_archive_id,'yp_id'=> $ypid);
              $join_tables = array(LOGIN . ' as l' => 'l.login_id= m.created_by');
              $fields = array("m.*,l.login_id,concat(l.firstname,' ',l.lastname) as case_manager_name");
              $data['edit_data'] = $this->common_model->get_records(PLACEMENT_PLAN_ARCHIVE.' as m',$fields,$join_tables, 'left', $match);
			 
            //get MDT_CARE_PLAN_TARGET_ARCHIVE 
            $where1 = array('pp_health_archive_id' => $pp_archive_id,'yp_id'=>$ypid);
            $data['edit_data_pp_health'] = $this->common_model->get_records(pp_health_archive, '', '', '', $where1, '','','','','','','');
			//get MDT_CARE_PLAN_TARGET_ARCHIVE 
            $where1 = array('pp_aim_archive_id' => $pp_archive_id,'yp_id'=>$ypid);
            $data['edit_data_pp_aim'] = $this->common_model->get_records(archive_pp_aims_of_placement, '', '', '', $where1, '','','','','','','');
			
			//get MDT_CARE_PLAN_TARGET_ARCHIVE 
            $where1 = array('pp_lac_archive_id' => $pp_archive_id,'yp_id'=>$ypid);
            $data['edit_data_pp_lac'] = $this->common_model->get_records(pp_archive_actions_from_lac_review, '', '', '', $where1, '','','','','','','');

            //get pp edu archive data
            $where1 = array('pp_edu_archive_id' => $pp_archive_id,'yp_id'=>$ypid);
            $data['edit_data_pp_edu'] = $this->common_model->get_records(pp_edu_archive, '', '', '', $where1, '','','','','','','');
            
          //get pp tra archive data
             $where1 = array('pp_tra_archive_id' => $pp_archive_id,'yp_id'=>$ypid);
            $data['edit_data_pp_tra'] = $this->common_model->get_records(pp_tra_archive, '', '', '', $where1, '','','','','','','');
            
    //get pp con archive data
      $where1 = array('pp_con_archive_id' => $pp_archive_id,'yp_id'=>$ypid);
            $data['edit_data_pp_con'] = $this->common_model->get_records(pp_con_archive, '', '', '', $where1, '','','','','','','');      
      

    //get pp ft archive data
      $where1 = array('pp_ft_archive_id' => $pp_archive_id,'yp_id'=>$ypid);
      $data['edit_data_pp_ft'] = $this->common_model->get_records(pp_ft_archive, '', '', '', $where1, '','','','','','','');  


    //get pp ft archive data
       $where1 = array('pp_mgi_archive_id' => $pp_archive_id,'yp_id'=>$ypid);
      $data['edit_data_pp_mgi'] = $this->common_model->get_records(pp_mgi_archive, '', '', '', $where1, '','','','','','',''); 

    //get pp pr archive data
     $where1 = array('pp_pr_archive_id' => $pp_archive_id,'yp_id'=>$ypid);
      $data['edit_data_pp_pr'] = $this->common_model->get_records(pp_pr_archive, '', '', '', $where1, '','','','','','',''); 
    
    //get pp bc archive data
     $where1 = array('pp_bc_archive_id' => $pp_archive_id,'yp_id'=>$ypid);
      $data['edit_data_pp_bc'] = $this->common_model->get_records(pp_bc_archive, '', '', '', $where1, '','','','','','',''); 
  	
      if(is_numeric($id) && is_numeric($ypid))
       {
         //get archive pp data
         $match = array('signoff_details_id'=> $id);
         $formsdata = $this->common_model->get_records(NFC_SIGNOFF_DETAILS,'', '', '', $match);

         $data['formsdata'] = $formsdata;
         if(!empty($formsdata))
         {
              $data['pp_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
         }
		
          //get YP information
          $match = array("yp_id"=>$ypid);
          $fields = array("*");
          $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$ypid);
          }
       

      $table = NFC_APPROVAL_PLACEMENT_PLAN_SIGNOFF.' as pps';
      $where = array("l.is_delete"=> "0","pps.yp_id" => $ypid,"pps.is_delete"=> "0","pps.approval_pp_id" =>$id);
        $fields = array("pps.created_by,pps.created_date,pps.yp_id,pps.approval_pp_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id=pps.created_by');
        $group_by = array('created_by');
        $data['signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);
		
          $data['ypid'] = $ypid;
          /* below parameter added by Ritesh Rana on 02/10/2018 to archive functionality */
          $data['past_care_id'] = $past_care_id;
          $data['care_home_id'] = $care_home_id;
          $data['ppid'] = $formsdata[0]['pp_id'];
          
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
    /*
      @Author : Niral Patel
      @Desc   : external approve view data
      @Input  :
      @Output :
      @Date   : 12/04/2018
     */
    
     public function resend_external_approval($signoff_id,$ypid,$ppid) {
      $match = array('signoff_details_id'=>$signoff_id);
      $signoff_data = $this->common_model->get_records(NFC_SIGNOFF_DETAILS,array("*"), '', '', $match);
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
          $success =$this->common_model->update(NFC_SIGNOFF_DETAILS,$u_data,array('signoff_details_id'=> $signoff_id));
          $msg = $this->lang->line('mail_sent_successfully');
          $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
        else
        {
          $msg = $this->lang->line('error');
          $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
      }

      redirect($this->viewname.'/external_approval_list/' . $ypid.'/'.$ppid);
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
		   
		   
          $match = array('yp_id'=> $id,'signoff_details_id'=>$signoff_id);
          $check_signoff_data = $this->common_model->get_records(NFC_SIGNOFF_DETAILS,array("*"), '', '', $match);
		    if(!empty($check_signoff_data)){
          $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'].REPORT_EXPAIRED_DAYS));
          if(strtotime(datetimeformat()) <= strtotime($expairedDate))
          {
			  $match = array('signoff_details_id'=> $signoff_id);
         $formsdata = $this->common_model->get_records(NFC_SIGNOFF_DETAILS,'', '', '', $match);
		 $data['formsdata'] = $formsdata;
         
         if(!empty($formsdata))
         {
              $data['pp_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
         }
		 
          //get YP information
          $match = array("yp_id"=>$id);
          $fields = array("*");
          $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
		  
         if(!empty($data['edit_data'][0]['modified_by'])){
             $data['updated_by'] = getUserName($data['edit_data'][0]['modified_by']);
         }else{
             $data['updated_by'] = "";
         } 
          
          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$id);
          }
          $pp_archive_id = $check_signoff_data[0]['pp_archive_id'];
           //get pp yp data
              $match = array('pp_archive_id'=>$pp_archive_id,'yp_id'=> $id);
              $join_tables = array(LOGIN . ' as l' => 'l.login_id= m.created_by');
              $fields = array("m.*,l.login_id,concat(l.firstname,' ',l.lastname) as case_manager_name");
              $data['edit_data'] = $this->common_model->get_records(PLACEMENT_PLAN_ARCHIVE.' as m',$fields,$join_tables, 'left', $match);
			  
			  $data['ypid'] = $id;
				$match = array('yp_id'=> $id);
				$data['edit_data_pp_aim'] = $this->common_model->get_records(pp_aims_of_placement,'', '', '', $match);
				$match = array('yp_id'=> $id,'placement_plan_id'=>$data['edit_data_pp_aim'][0]['placement_plan_id']);
				$data['edit_data_pp_aim_archive'] = $this->common_model->get_records(archive_pp_aims_of_placement,'', '', '', $match);
				$data['pp_aim_archve_data']=$data['edit_data_pp_aim_archive'][count($data['edit_data_pp_aim_archive'])-2];
				
				$data['ypid'] = $id;
				$match = array('yp_id'=> $id);
				$data['edit_data_pp_lac'] = $this->common_model->get_records(pp_actions_from_lac_review,'', '', '', $match);
				
				$match = array('yp_id'=> $id,'placement_plan_id'=>$data['edit_data_pp_lac'][0]['placement_plan_id']);
				$data['edit_data_pp_lac_archive'] = $this->common_model->get_records(pp_archive_actions_from_lac_review,'', '', '', $match);
				$data['pp_lac_archve_data']=$data['edit_data_pp_lac_archive'][count($data['edit_data_pp_lac_archive'])-2];
		
		
            //get MDT_CARE_PLAN_TARGET_ARCHIVE 
            $where1 = array('yp_id'=> $id);
            $data['edit_data_pp_health'] = $this->common_model->get_records(pp_health_archive, '', '', '', $where1, '','','','','','','');
            //get pp edu archive data
            $where1 = array('pp_edu_archive_id' => $pp_archive_id,'yp_id'=> $id);
            $data['edit_data_pp_edu'] = $this->common_model->get_records(pp_edu_archive, '', '', '', $where1, '','','','','','','');
            
          //get pp tra archive data
             $where1 = array('pp_tra_archive_id' => $pp_archive_id);
            $data['edit_data_pp_tra'] = $this->common_model->get_records(pp_tra_archive, '', '', '', $where1, '','','','','','','');
            
    //get pp con archive data
      $where1 = array('pp_con_archive_id' => $pp_archive_id);
            $data['edit_data_pp_con'] = $this->common_model->get_records(pp_con_archive, '', '', '', $where1, '','','','','','','');      
      

    //get pp ft archive data
      $where1 = array('pp_ft_archive_id' => $pp_archive_id);
      $data['edit_data_pp_ft'] = $this->common_model->get_records(pp_ft_archive, '', '', '', $where1, '','','','','','','');  


    //get pp ft archive data
       $where1 = array('pp_mgi_archive_id' => $pp_archive_id);
      $data['edit_data_pp_mgi'] = $this->common_model->get_records(pp_mgi_archive, '', '', '', $where1, '','','','','','',''); 

    //get pp pr archive data
     $where1 = array('pp_pr_archive_id' => $pp_archive_id);
      $data['edit_data_pp_pr'] = $this->common_model->get_records(pp_pr_archive, '', '', '', $where1, '','','','','','',''); 
    
    //get pp bc archive data
     $where1 = array('pp_bc_archive_id' => $pp_archive_id);
      $data['edit_data_pp_bc'] = $this->common_model->get_records(pp_bc_archive, '', '', '', $where1, '','','','','','',''); 

        $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
        $table = NFC_APPROVAL_PLACEMENT_PLAN_SIGNOFF.' as pps';
      $where = array("l.is_delete"=> "0","pps.yp_id" => $id,"pps.is_delete"=> "0","pps.approval_pp_id" =>$signoff_id);
	    $fields = array("pps.created_by,pps.created_date,pps.yp_id,pps.approval_pp_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id=pps.created_by');
        $group_by = array('created_by');
        $data['signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);
		
	
          $data['signoff_id'] = $signoff_id;
          $data['key_data'] = $email;
          $data['ypid'] = $id;
          $data['footerJs'][0] = base_url('uploads/custom/js/placementplan/placementplan.js');
          
          $data['crnt_view'] = $this->viewname;
			
		   //new
            $pdfFileName = "pp.pdf";
            $PDFInformation['yp_details'] = $data['YP_details'][0];
            $PDFInformation['edit_data'] = $data['edit_data'][0]['modified_date'];
            $PDFInformation['edit_date'] = $data['prev_edit_data'][0]['modified_date'];
			
            $PDFHeaderHTML  = $this->load->view('placement_plan_pdfHeader', $PDFInformation,true);
			
            $PDFFooterHTML  = $this->load->view('placement_plan_pdfFooter', $PDFInformation,true);
			
			
            //Set Header Footer and Content For PDF
            $this->m_pdf->pdf->mPDF('utf-8','A4','','','10','10','45','25');
	
            $this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
            $this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);                    
            $data['main_content'] = '/placement_plan_pdf';
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
              $msg = $this->lang->line('already_pp_review');
      $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
 $this->load->view('successfully_message');
        }
         //get pp form
        }else
        {
            show_404 ();
        }

    }
}

