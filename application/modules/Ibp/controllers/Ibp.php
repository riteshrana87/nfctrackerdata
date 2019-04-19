<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ibp extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->viewname = $this->router->fetch_class ();
        $this->method   = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session','m_pdf'));
    }

    /*
      @Author : Niral Patel
      @Desc   : Ibp Index Page
      @Input 	: yp id
      @Output	:
      @Date   : 11/07/2017
     */

    public function index($id) {
     if(is_numeric($id))
       {
         //get pp form
         $match = array('ibp_form_id'=> 1);
         $formsdata = $this->common_model->get_records(IBP_FORM,array("form_json_data"), '', '', $match);
         if(!empty($formsdata))
         {
              $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
         }
          //get YP information
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($id,$fields);
          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$id);
          }
          //get ibp yp data
          $match = array('yp_id'=> $id,'is_previous_version'=>0);
          $data['edit_data'] = $this->common_model->get_records(INDIVIDUAL_BEHAVIOUR_PLAN,array("*"), '', '', $match);

        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        //get signoff data
        $table = INDIVIDUAL_BEHAVIOUR_PLAN_SIGNOFF.' as ibp';
        $where = array("l.is_delete"=> "0","ibp.yp_id" => $id,"ibp.is_delete"=> "0");
        $fields = array("ibp.created_by,ibp.created_date,ibp.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id=ibp.created_by');
        $group_by = array('created_by');
        $data['ibp_signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);

        //check data exist or not
        $table = INDIVIDUAL_BEHAVIOUR_PLAN_SIGNOFF;
        $where = array("yp_id" => $id,"created_by" => $login_user_id,"is_delete"=> "0");
        $fields = array('count(*) as count');
        $check_ibp_signoff_data = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);
        $data['check_ibp_signoff_data'] = $check_ibp_signoff_data[0]['count'];
        //get external approve
        $table = NFC_IBP_SIGNOFF_DETAILS;
        $fields = array('count(*) as count');
        $where = array('yp_id' => $id);
        $external_signoff_data = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
        $data['check_external_signoff_data'] =  $external_signoff_data[0]['count'];
          //get ibp old yp data
          $match = array('yp_id'=> $id,'is_previous_version'=>1);
          $data['prev_edit_data'] = $this->common_model->get_records(INDIVIDUAL_BEHAVIOUR_PLAN,'', '', '', $match);
          $data['ypid'] = $id;
          
          $data['footerJs'][0] = base_url('uploads/custom/js/ibp/ibp.js');
          $data['crnt_view'] = $this->viewname;
          $data['main_content'] = '/Ibp';
          $data['header'] = array('menu_module' => 'YoungPerson');
          $this->parser->parse('layouts/DefaultTemplate', $data);
        }
        else
        {
            show_404 ();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : create Ibp edit page
      @Input 	:
      @Output	:
      @Date   : 07/07/2017
     */

    public function edit($id) {
      if(is_numeric($id))
      {
        //get pp form
        $match = array('ibp_form_id'=> 1);
        $pp_forms = $this->common_model->get_records(IBP_FORM,array("form_json_data"), '', '', $match);
        if(!empty($pp_forms))
        {
            $data['form_data'] = json_decode($pp_forms[0]['form_json_data'], TRUE);
        }
        //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($id,$fields);
        if(empty($data['YP_details']))
        {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson/view/'.$id);
        }
        //get ibp yp data
        $match = array('yp_id'=> $id,'is_previous_version'=>0);
        $data['edit_data'] = $this->common_model->get_records(INDIVIDUAL_BEHAVIOUR_PLAN,array("*"), '', '', $match);
        
         // signoff data
        $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
        $table = INDIVIDUAL_BEHAVIOUR_PLAN_SIGNOFF.' as ibp';
        $where = array("l.is_delete"=> "0","ibp.yp_id" => $id,"ibp.is_delete"=> "0");
        $fields = array("ibp.created_by,ibp.yp_id,CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id=ibp.created_by');
        $group_by = array('created_by');
        $data['signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);
		  
        $data['care_home_id']= $data['YP_details'][0]['care_home'];
        $url_data =  base_url('Ibp/edit/'.$id);
        //check data exist or not
        $match = array('url_data'=>$url_data);
        $data['check_edit_permission'] = $this->common_model->get_records(CHECK_EDIT_URL,array("*"), '', '', $match);

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
            $data['ypid'] = $id;
            $data['footerJs'][0] = base_url('uploads/custom/js/ibp/ibp.js');
            $data['crnt_view'] = $this->viewname;
            $data['main_content'] = '/edit';
            $data['header'] = array('menu_module' => 'YoungPerson');
            $this->parser->parse('layouts/DefaultTemplate', $data);
          }else{
             $msg = $this->lang->line('check_ibp_user_update_data');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('/' . $this->viewname .'/index/'. $id);
          }
        }else{
            $data['ypid'] = $id;
            $data['footerJs'][0] = base_url('uploads/custom/js/ibp/ibp.js');
            $data['crnt_view'] = $this->viewname;
            $data['main_content'] = '/edit';
            $data['header'] = array('menu_module' => 'YoungPerson');
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
   public function insert()
   {
		if (!validateFormSecret()) {
			redirect($_SERVER['HTTP_REFERER']);  //Redirect On Listing page
	    }
        $postData = $this->input->post ();
        unset($postData['submit_ibpform']);
        //get pp form
        $match = array('ibp_form_id'=> 1);
        $pp_forms = $this->common_model->get_records(IBP_FORM,array("form_json_data"), '', '', $match);
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
                      $pp_yp_data = $this->common_model->get_records(INDIVIDUAL_BEHAVIOUR_PLAN,array('`'.$row['name'].'`'), '', '', $match);
                      //delete img
                      if(!empty($postData['hidden_'.$row['name']]))
                      {
                          $delete_img = explode(',', $postData['hidden_'.$row['name']]);
                          $db_images = explode(',',$pp_yp_data[0][$filename]);
                          $differentedImage = array_diff ($db_images, $delete_img);
                          $pp_yp_data[0][$filename] = !empty($differentedImage)?implode(',',$differentedImage):'';
                      }
                     
                      if(!empty($_FILES[$filename]['name'][0]))                     
                      {
                          //create dir and give permission
                          /* common function replaced by Dhara Bhalala on 29/09/2018 */
                          createDirectory(array($this->config->item('ibp_base_url'),$this->config->item('ibp_base_big_url'),$this->config->item('ibp_base_big_url') . '/' . $postData['yp_id']));
                           
                          $file_view = $this->config->item ('ibp_img_url').$postData['yp_id'];
                          //upload big image
                          $upload_data = uploadImage ($filename, $file_view,'/' . $this->viewname.'/index/'.$postData['yp_id']);

                          //upload small image
                          $insertImagesData = array();
                          if(!empty($upload_data))
                          {
                            foreach ($upload_data as $imageFiles) {
                                /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                createDirectory(array($this->config->item('ibp_base_small_url'),$this->config->item('ibp_base_small_url') . '/' . $postData['yp_id']));
                                
                                /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                if($imageFiles['is_image'])
                                    $a = do_resize ($this->config->item ('ibp_img_url') . $postData['yp_id'], $this->config->item ('ibp_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
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
         
      //get IBP yp data
       $match = array('yp_id'=> $postData['yp_id'],'is_previous_version'=>0);
       $pp_new_data = $this->common_model->get_records(INDIVIDUAL_BEHAVIOUR_PLAN,array('*'), '', '', $match);
        
      //get previous version IBP yp data
      $match = array('yp_id'=> $postData['yp_id'],'is_previous_version'=>1);
      $previous_data = $this->common_model->get_records(INDIVIDUAL_BEHAVIOUR_PLAN,array('*'), '', '', $match);

       if(!empty($pp_new_data))
       {
          $update_pre_data = array();
          $updated_field =array();
          $n=0;
          if(!empty($pp_form_data))
          {
          foreach ($pp_form_data as $row) {
              if(isset($row['name']))
              {
                  if($row['type'] != 'button')
                  {
                    if(!empty($pp_new_data))
                    {
                      if($postData[$row['name']] != $pp_new_data[0][$row['name']])
                      {
                        $updated_field[]= $row['label'];
                        $n++;
                      }
                    }
                    $update_pre_data[$row['name']] = strip_slashes($pp_new_data[0][$row['name']]);
                  }
              }
          }
          $update_pre_data['yp_id']         = $postData['yp_id'];
          $update_pre_data['created_date']  = $pp_new_data[0]['created_date'];
          $update_pre_data['created_by']    = $pp_new_data[0]['created_by'];
          $update_pre_data['signoff']    = $pp_new_data[0]['signoff'];
          $update_pre_data['modified_by']   = $pp_new_data[0]['modified_by'];
          $update_pre_data['modified_date'] = $pp_new_data[0]['modified_date'];
          $update_pre_data['is_previous_version'] = 1;
          }
        
          if(!empty($previous_data))
          {
             if($n != 0)
             {
              $this->common_model->update(INDIVIDUAL_BEHAVIOUR_PLAN,$update_pre_data,array('yp_id'=> $postData['yp_id'],'is_previous_version'=>1));
             }
          }
          else
          {
            $update_pre_data['care_home_id'] = $postData['care_home_id'];
            $this->common_model->insert(INDIVIDUAL_BEHAVIOUR_PLAN,$update_pre_data);
          }
       }
        
        if(!empty($postData['ibp_id']))
        {
             if(!empty($postData['ibp_signoff'])){
                $data['signoff'] = $postData['ibp_signoff']; 
             }
             $data['ibp_id'] = $postData['ibp_id'];
             $data['yp_id'] = $postData['yp_id'];
             $data['signoff'] = 0;
             $data['modified_date'] = datetimeformat();
             $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
             $this->common_model->update(INDIVIDUAL_BEHAVIOUR_PLAN,$data,array('ibp_id'=>$postData['ibp_id']));
             if(!empty($updated_field))
             {
              foreach ($updated_field as $fields) {
                //Insert log activity
                $activity = array(
                  'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
                  'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
                  'module_name'         => IBP_MODULE,
                  'module_field_name'   => $fields,
                  'type'                => 2
                );
                log_activity($activity);
              }
                
             }

        }
        else
        {
            if(!empty($postData['ibp_signoff'])){
                $data['signoff'] = $postData['ibp_signoff']; 
             }
             $data['yp_id'] = $postData['yp_id'];
             $data['care_home_id'] = $postData['care_home_id'];
             $data['created_date'] = datetimeformat();
             $data['modified_date'] = datetimeformat();
             $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
             $this->common_model->insert(INDIVIDUAL_BEHAVIOUR_PLAN, $data);
             //Insert log activity
              $activity = array(
              'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
              'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
              'module_name'         => IBP_MODULE,
              'module_field_name'   => '',
              'type'                => 1
            );
            log_activity($activity);
        }
        $this->createArchive($postData['yp_id']);
        redirect('/' . $this->viewname .'/save_ibp/'. $data['yp_id']);
   }
   /*
      @Author : Niral Patel
      @Desc   : Save ibp form
      @Input    :
      @Output   :
      @Date   : 12/07/2017
     */
   public function save_ibp($id)
   {
    if(is_numeric($id))
    {
        //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($id,$fields);
        if(empty($data['YP_details']))
        {
          $msg = $this->lang->line('common_no_record_found');
          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
          redirect('YoungPerson/view/'.$id);
        }
        $data['id'] = $id;
        $data['main_content'] = '/save_ibp';
        
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
    else
    {
        show_404 ();
    }
   }
   
   /*
      @Author : Niral Patel
      @Desc   : Print ibp form
      @Input    :
      @Output   :
      @Date   : 12/07/2017
     */
   public function DownloadPrint($ibp_id,$yp_id,$section = NULL) {
        $data = [];
        $match = array('ibp_form_id'=> 1);
        $ibp_forms = $this->common_model->get_records(IBP_FORM,array("form_json_data"), '', '', $match);
        if(!empty($ibp_forms))
        {
            $data['ibp_form_data'] = json_decode($ibp_forms[0]['form_json_data'], TRUE);
        }
        //get YP information
        $table = YP_DETAILS.' as yp';
        $match = array("yp.yp_id"=>$yp_id);
        $fields = array("yp.yp_fname,yp.yp_lname,pa.placing_authority_id,pa.authority,pa.address_1,pa.town,pa.county,pa.postcode,sd.mobile,sd.email");
        $join_tables = array(PLACING_AUTHORITY . ' as pa' => 'pa.yp_id=yp.yp_id',SOCIAL_WORKER_DETAILS . ' as sd' => 'sd.yp_id=yp.yp_id');
        $data['YP_details']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,'','','','','','');

        if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$id);
          }
        //get ibp yp data
        $match = array('yp_id'=> $yp_id,'is_previous_version'=>0);
        $data['edit_data_lastedit_data'] = $this->common_model->get_records(INDIVIDUAL_BEHAVIOUR_PLAN,array("*"), '', '', $match);
        
        //get IBP yp data
        $match = array('yp_id'=> $yp_id,'ibp_id'=> $ibp_id);
        $data['edit_data'] = $this->common_model->get_records(INDIVIDUAL_BEHAVIOUR_PLAN,array("*"), '', '', $match);
        $data['ypid'] = $yp_id;
        
        $data['main_content'] = '/ibppdf';
        $data['section'] = $section;
        $html = $this->parser->parse('layouts/PDFTemplate', $data);
        $pdfFileName = "ibp" . $ibp_id . ".pdf";
        $pdfFilePath = FCPATH . 'uploads/ibp/';
        if (!is_dir(FCPATH . 'uploads/ibp/')) {
            @mkdir(FCPATH . 'uploads/ibp/', 0777, TRUE);
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
            echo $html;exit;
        } else {
            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($pdfFileName, "D");
        }
    }
  
 /*
      @Author : Ritesh Rana
      @Desc   : User sign off functionality
      @Input    :
      @Output   :
      @Date   : 12/07/2017
     */

public function manager_review($yp_id,$ibp_id) {
 if (!empty($yp_id)) {
          $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
          $match = array('yp_id'=> $yp_id,'created_by'=>$login_user_id,'is_delete'=> '0');
          $check_signoff_data = $this->common_model->get_records(INDIVIDUAL_BEHAVIOUR_PLAN_SIGNOFF,'', '', '', $match);
      if(empty($check_signoff_data) > 0){
          $update_pre_data['yp_id'] = $yp_id;
          $update_pre_data['ibp_id'] = $ibp_id;
          $update_pre_data['created_date'] = datetimeformat();
          $update_pre_data['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
        if ($this->common_model->insert(INDIVIDUAL_BEHAVIOUR_PLAN_SIGNOFF,$update_pre_data)) {
         $u_data['signoff'] = '1';
          $this->common_model->update(INDIVIDUAL_BEHAVIOUR_PLAN,$u_data,array('ibp_id'=> $ibp_id,'yp_id'=> $yp_id));
          $msg = $this->lang->line('successfully_ibp_review');
          $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
          
        } else {
          // error
          $msg = $this->lang->line('error_msg');
          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
          
        }
    }else{
        $msg = $this->lang->line('already_ibp_review');
      $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

    }
  }else{      
      $msg = $this->lang->line('error_msg');
      $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
      
  }


    redirect('/' . $this->viewname .'/index/'.$yp_id);
  }

/*
      @Author : Ritesh Rana
      @Desc   : ckeck User edit functionality
      @Input    :
      @Output   :
      @Date   : 12/07/2017
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
    public function createArchive($id) {
        if (is_numeric($id)) {
            //get ibp form
            $match = array('ibp_form_id' => 1);
            $formsdata = $this->common_model->get_records(IBP_FORM, array("form_json_data"), '', '', $match);
            //get ibp yp data
            $match = array('yp_id' => $id);
            $pp_yp_data = $this->common_model->get_records(INDIVIDUAL_BEHAVIOUR_PLAN, array("*"), '', '', $match);

            if (!empty($formsdata) && !empty($pp_yp_data)) {
                /* for last edit date print at view archive file */
                $match = array('yp_id' => $id, 'is_previous_version' => 1);
                $data['prev_edit_data'] = $this->common_model->get_records(INDIVIDUAL_BEHAVIOUR_PLAN, array("modified_date"), '', '', $match);
                $last_modified_date = $data['prev_edit_data'][0]['modified_date'];

                $form_data = json_decode($formsdata[0]['form_json_data'], TRUE);
                $data = array();
                $i = 0;
                foreach ($form_data as $row) {
                    if (isset($row['name'])) {
                        if ($row['type'] != 'button') {
                            if ($row['type'] == 'checkbox-group') {
                                $form_data[$i]['value'] = implode(',', $pp_yp_data[0][$row['name']]);
                            } else {
                                /*changes done by Dhara Bhalala to manage single quote in json*/
                                $form_data[$i]['value'] = str_replace("'", "\'", $pp_yp_data[0][$row['name']]);
//                                $form_data[$i]['value'] = str_replace("'", '"', $pp_yp_data[0][$row['name']]);
                            }
                        }
                    }
                    $i++;
                }

                $archive = array(
                    'yp_id' => $id,
                    'form_json_data' => json_encode($form_data, TRUE),
                    'created_by' => $this->session->userdata('LOGGED_IN')['ID'],
                    'created_date' => datetimeformat(),
                    'modified_date' => $last_modified_date,
                    'modified_time' => date("H:i:s"),
                    'status' => 0,
                    'care_home_id' => $pp_yp_data[0]['care_home_id'],
                    'signoff' => $pp_yp_data[0]['signoff']
                );
                //get ibp yp data
//                $wherestring = " form_json_data = '".str_replace("\\","\\\\", json_encode($form_data, TRUE))."'";                
//                $pp_archive_data = $this->common_model->get_records(IBP_ARCHIVE,array('yp_id'), '', '', '','','','','','','',$wherestring);
                
                /*changes done by Dhara Bhalala to prevent auto archive when no updation changes*/
                $match = array('form_json_data' => json_encode($form_data, TRUE));
                $pp_archive_data = $this->common_model->get_records(IBP_ARCHIVE, array('yp_id'), '', '', $match);
                if (empty($pp_archive_data)) {
                    //get ibp yp data
                    $match = array('yp_id' => $id);
                    $archive_data = $this->common_model->get_records(IBP_ARCHIVE, array("*"), '', '', $match);
                    if (!empty($archive_data)) {
                        //update status to archive
                        $update_archive = array(
                            'modified_date' => $last_modified_date,
                            'modified_time' => date("H:i:s"),
                            'status' => 1,
                            'signoff' => $pp_yp_data[1]['signoff']
                        );
                        $where = array('status' => 0, 'yp_id' => $id);
                        $this->common_model->update(IBP_ARCHIVE, $update_archive, $where);
                    }
                    //insert archive data for next time
                    $this->common_model->insert(IBP_ARCHIVE, $archive);

                    $archive_ibp_id = $this->db->insert_id();
                    if ($archive_ibp_id > 1) {
                        $archive_ibp_id = $archive_ibp_id - 1;
                    }
                    //individual behaviour plan signoff
                    $table = INDIVIDUAL_BEHAVIOUR_PLAN_SIGNOFF . ' as ibp';
                    $where = array("ibp.yp_id" => $id, "ibp.is_delete" => "0");
                    $fields = array("ibp.created_by,ibp.yp_id,ibp.ibp_id");
                    $group_by = array('created_by');
                    $signoff_data = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', $group_by, $where);

                    if (!empty($signoff_data)) {
                        foreach ($signoff_data as $archive_value) {
                            $update_arc_data['archive_ibp_id'] = $archive_ibp_id;
                            $update_arc_data['yp_id'] = $archive_value['yp_id'];
                            $update_arc_data['created_date'] = datetimeformat();
                            $update_arc_data['created_by'] = $archive_value['created_by'];
                            $this->common_model->insert(NFC_ARCHIVE_INDIVIDUAL_BEHAVIOUR_PLAN_SIGNOFF, $update_arc_data);
                        }

                        $archive = array('is_delete' => 1);
                        $where = array('yp_id' => $id);
                        $this->common_model->update(INDIVIDUAL_BEHAVIOUR_PLAN_SIGNOFF, $archive, $where);
                    }
                    return TRUE;
                } else {
                    return TRUE;
                }
            } else {
                return TRUE;
            }
        } else {
            show_404();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Open popup Send for Signoff / Approval page
      @Input    :
      @Output   :
      @Date   : 12/07/2017
     */ 

      public function signoff($yp_id='',$ibp_id='') {
        $this->formValidation();

        $main_user_data = $this->session->userdata('LOGGED_IN');
        if ($this->form_validation->run() == FALSE) {

            $data['crnt_view'] = $this->viewname;
            $data['ypid']= $yp_id;
            $data['ibp_id']= $ibp_id;

            //get YP information
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $YP_details = YpDetails($yp_id,$fields);

          $data['care_home_id'] = $YP_details[0]['care_home'];
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
      @Author : Ritesh Rana
      @Desc   : insert Signoff / Approval data
      @Input    :
      @Output   :
      @Date   : 12/07/2017
     */ 
    public function insertdata() {
        $postdata = $this->input->post();
        $ypid = $postdata['ypid'];
        $pp_id = $postdata['ibp_id'];
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
              'module_name' => IBP_PARENT_CARER_DETAILS_YP,
              'module_field_name' => '',
              'type' => 1
          );
          log_activity($activity);
        }
            //Current Login detail
        $main_user_data = $this->session->userdata('LOGGED_IN');
        
            if (!validateFormSecret()) {
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
                redirect('Dashboard'); //Redirect On Listing page
            }
            $data['crnt_view'] = $this->viewname;

             $match = array('ibp_form_id'=> 1);
           $formsdata = $this->common_model->get_records(IBP_FORM,array("form_json_data"), '', '', $match);
           //get ibp yp data
           $match = array('yp_id'=> $ypid);
           $pp_yp_data = $this->common_model->get_records(INDIVIDUAL_BEHAVIOUR_PLAN,'', '', '', $match);

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
                            }else{
                              $form_data[$i]['value'] = str_replace("'", '"', $pp_yp_data[0][$row['name']]);
                            }
                        }
                    }
                    $i++;
                }
              }
            $data = array(
                'user_type' => ucfirst($postdata['user_type']),
                'care_home_id' => $postdata['care_home_id'],
                'yp_id' => ucfirst($postdata['ypid']),
                'ibp_id' => ucfirst($postdata['ibp_id']),
                'form_json_data' =>json_encode($form_data, TRUE),
                'fname' => ucfirst($postdata['fname']),
                'lname' => ucfirst($postdata['lname']),
                'email' => $postdata['email'],
                'key_data' => md5($postdata['email']),
                'created_date' => datetimeformat(),
                'created_by' => $main_user_data['ID'],
                'updated_by' => $main_user_data['ID'],
            );
            //Insert Record in Database
            if ($this->common_model->insert(NFC_IBP_SIGNOFF_DETAILS, $data)) {

                $signoff_id = $this->db->insert_id();

                $table = INDIVIDUAL_BEHAVIOUR_PLAN_SIGNOFF.' as ibp';
              $where = array("ibp.yp_id" => $ypid,"ibp.is_delete"=> "0");
              $fields = array("ibp.created_by,ibp.yp_id,ibp.ibp_id,ibp.created_date");
              $group_by = array('created_by');
              $signoff_data = $this->common_model->get_records($table,$fields,'','','','','','','','',$group_by,$where);

          if(!empty($signoff_data)){
              foreach ($signoff_data as $archive_value) {
                  $update_arc_data['approval_ibp_id'] = $signoff_id;
                  $update_arc_data['yp_id'] = $archive_value['yp_id'];
                  $update_arc_data['created_date'] = $archive_value['created_date'];
                  $update_arc_data['created_by'] = $archive_value['created_by'];
                  $this->common_model->insert(NFC_APPROVAL_INDIVIDUAL_BEHAVIOUR_PLAN_SIGNOFF,$update_arc_data);      
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
        redirect('Ibp/index/' . $ypid);
    }

    /*
      @Author : Ritesh Rana
      @Desc   : send email with url for Signoff / Approval page
      @Input    :
      @Output   :
      @Date   : 12/07/2017
     */ 

    private function sendMailToRelation($data = array(),$signoff_id) {

        if (!empty($data) && is_numeric($signoff_id)) {
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
            $loginLink = base_url('Ibp/signoffData/' . $data['yp_id'] . '/' . $signoff_id . '/' . $email);

            $find = array('{NAME}','{LINK}');

            $replace = array('NAME' => $customerName,'LINK' => $loginLink);
            
            $emailSubject = 'Welcome to NFCTracker';
                    $emailBody = '<div>'
                    . '<p>Hello {NAME} ,</p> '
                    . '<p>Please find IBP for '.$yp_name.' for your approval.</p> '
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
      @Desc   : validation 
      @Input    :
      @Output   :
      @Date   : 12/07/2017
     */ 

     public function formValidation($id = null) {
        $this->form_validation->set_rules('fname', 'Firstname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('lname', 'Lastname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
        
    }

    /*
      @Author : Ritesh Rana
      @Desc   : view for Signoff / Approval data
      @Input    :
      @Output   :
      @Date   : 12/07/2017
     */ 
     
    public function signoffData($id,$signoff_id,$email) {
      
      if(is_numeric($id) && is_numeric($signoff_id) && !empty($email))
       {
          $match = array('yp_id'=> $id,'ibp_signoff_details_id'=>$signoff_id,'key_data'=> $email,'status'=>'inactive');
          $check_signoff_data = $this->common_model->get_records(NFC_IBP_SIGNOFF_DETAILS,array("created_date,form_json_data"), '', '', $match);

        if(!empty($check_signoff_data)){
         $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'].REPORT_EXPAIRED_DAYS));
          if(strtotime(datetimeformat()) <= strtotime($expairedDate))
          {
          $data['form_data'] = json_decode($check_signoff_data[0]['form_json_data'], TRUE);
          //get YP information
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($id,$fields);
          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$id);
          }
          //get ibp yp data
        
        $table = NFC_APPROVAL_INDIVIDUAL_BEHAVIOUR_PLAN_SIGNOFF.' as ibp';
        $where = array("l.is_delete"=> "0","ibp.yp_id" => $id,"ibp.is_delete"=> "0","ibp.approval_ibp_id" => $signoff_id);
        $fields = array("ibp.created_by,ibp.created_date,ibp.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id=ibp.created_by');
        $group_by = array('created_by');
        $data['ibp_signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);
          //get ibp old yp data
          $data['signoff_id']= $signoff_id;
          $data['key_data']= $email;
          $data['ypid'] = $id;
          $data['footerJs'][0] = base_url('uploads/custom/js/ibp/ibp.js');
          $data['crnt_view'] = $this->viewname;
          $data['main_content'] = '/signoff_view';
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
           
           $msg = $this->lang->line('already_ibp_review');
           $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
           $this->load->view('successfully_message');
        }
        }
        else
        {
            show_404 ();
        }
    }

/*
      @Author : Ritesh Rana
      @Desc   : Signoff Review for Signoff / Approval data
      @Input    :
      @Output   :
      @Date   : 12/07/2017
     */

    public function signoff_review_data($yp_id,$signoff_id,$email) {
    if (!empty($yp_id) && !empty($signoff_id) && !empty($email)) {
          $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
          $match = array('yp_id'=> $yp_id,'ibp_signoff_details_id'=>$signoff_id,'key_data'=> $email,'status'=>'inactive');
          $check_signoff_data = $this->common_model->get_records(NFC_IBP_SIGNOFF_DETAILS,array("created_date"), '', '', $match);
        if(!empty($check_signoff_data)){
          $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'].REPORT_EXPAIRED_DAYS));
          if(strtotime(datetimeformat()) <= strtotime($expairedDate))
          {
              $u_data['status'] = 'active';
              $u_data['modified_date'] = datetimeformat();
              $success =$this->common_model->update(NFC_IBP_SIGNOFF_DETAILS,$u_data,array('ibp_signoff_details_id'=> $signoff_id,'yp_id'=> $yp_id,'key_data'=> $email));
            if ($success) {
              
              $msg = $this->lang->line('successfully_ibp_review');
              $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-success text-center'>$msg</div>");
            } else {
              // error
              $msg = $this->lang->line('error_msg');
              $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>");
              //Redirect On Listing page
            }
          }
            else
            {
              $msg = lang('link_expired');
              $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>")
              ;
            }
      }else{
        $msg = $this->lang->line('already_ibp_review');
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
      @Desc   : view ks data
      @Input  :
      @Output :
      @Date   : 12/04/2018
     */
    public function external_approval_list($ypid,$ibp_id,$care_home_id=0,$past_care_id=0) {
                /*
                Ritesh Rana
                for past care id inserted for archive full functionality
                */
      if ($past_care_id !== 0) {
            $temp = $this->common_model->get_records(PAST_CARE_HOME_INFO, array('move_date'), '', '', array("yp_id" => $ypid, "past_carehome" => $care_home_id));
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
  if(is_numeric($ypid) && is_numeric($ibp_id)){
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
            $this->session->unset_userdata('ibp_approval_session_data');
        }

        $searchsort_session = $this->session->userdata('ibp_approval_session_data');
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
                $sortfield = 'ibp_signoff_details_id';
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
        $config['base_url'] = base_url() . $this->viewname . '/external_approval_list/'.$ypid.'/'.$ibp_id;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 5;
            $uri_segment = $this->uri->segment(5);
        }
        //Query

        $table = NFC_IBP_SIGNOFF_DETAILS . ' as ibp';
        $where = array("ibp.yp_id"=>$ypid,"ibp.ibp_id"=>$ibp_id);
        $fields = array("ibp.*,CONCAT(`firstname`,' ', `lastname`) as create_name ,CONCAT(`fname`,' ', `lname`) as user_name,ch.care_home_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= ibp.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = ibp.care_home_id');
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);

            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
        }
      }else{
        $config['base_url'] = base_url() . $this->viewname . '/external_approval_list/'.$ypid.'/'.$ibp_id.'/'.$care_home_id.'/'.$past_care_id;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 7;
            $uri_segment = $this->uri->segment(7);
        }
        //Query

        $table = NFC_IBP_SIGNOFF_DETAILS . ' as ibp';
        $where = array("ibp.yp_id"=>$ypid,"ibp.ibp_id"=>$ibp_id);
        $where_date = "ibp.created_date BETWEEN  '".$created_date."' AND '".$movedate."'";
        $fields = array("ibp.*,CONCAT(`firstname`,' ', `lastname`) as create_name ,CONCAT(`fname`,' ', `lname`) as user_name,ch.care_home_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= ibp.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = ibp.care_home_id');
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where,'','','','','',$where_date);

            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1','','',$where_date);
        }
      }

        $data['ypid'] = $ypid;
        $data['ibp_id'] = $ibp_id;
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

        $this->session->set_userdata('ibp_approval_session_data', $sortsearchpage_data);
        setActiveSession('ibp_approval_session_data'); // set current Session active
        $data['header'] = array('menu_module' => 'Communication');
       
        //get communication form
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/ibp/ibp.js');
        $data['header'] = array('menu_module' => 'YoungPerson');

        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/approval_ajaxlist', $data);
        } else {
            $data['main_content'] = '/ibp_list';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
        }else{
              show_404 ();
        } 
    }

    /*
      @Author : Ritesh Rana
      @Desc   : view Approval Ibp
      @Input    :
      @Output   :
      @Date   : 12/07/2017
     */

public function viewApprovalIbp($id,$ypid,$care_home_id=0,$past_care_id=0)                         
    {
      if(is_numeric($id) && is_numeric($ypid))
       {
         //get archive pp data
         $match = array('ibp_signoff_details_id'=> $id);
         $formsdata = $this->common_model->get_records(NFC_IBP_SIGNOFF_DETAILS,array("form_json_data,ibp_id"), '', '', $match);
         $data['formsdata'] = $formsdata;
         if(!empty($formsdata))
         {
              $data['pp_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
         }
          //get YP information
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($ypid,$fields);

          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$ypid);
          }

        $table = NFC_APPROVAL_INDIVIDUAL_BEHAVIOUR_PLAN_SIGNOFF.' as ibp';
        $where = array("l.is_delete"=> "0","ibp.yp_id" => $ypid,"ibp.is_delete"=> "0","approval_ibp_id"=>$id);
        $fields = array("ibp.created_by,ibp.created_date,ibp.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id=ibp.created_by');
        $group_by = array('created_by');
        $data['signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);

          $data['ypid'] = $ypid;
          $data['care_home_id'] = $care_home_id;
          $data['past_care_id'] = $past_care_id;

          $data['ibp_id'] = $formsdata[0]['ibp_id'];
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
      @Author : Niral Patel
      @Desc   : external approve view data
      @Input  :
      @Output :
      @Date   : 12/04/2018
     */
    
     public function resend_external_approval($signoff_id,$ypid,$ksid) {
      $match = array('ibp_signoff_details_id'=>$signoff_id);
      $signoff_data = $this->common_model->get_records(NFC_IBP_SIGNOFF_DETAILS,array("yp_id,fname,lname,email"), '', '', $match);
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
          $success =$this->common_model->update(NFC_IBP_SIGNOFF_DETAILS,$u_data,array('ibp_signoff_details_id'=> $signoff_id));
          $msg = $this->lang->line('mail_sent_successfully');
          $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
        else
        {
          $msg = $this->lang->line('error');
          $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
      }

      redirect($this->viewname.'/external_approval_list/' . $ypid.'/'.$ksid);
     }
	  /*
      @Author : Nikunj Ghelani
      @Desc   : PDF data
      @Input  :
      @Output :
      @Date   : 02/07/2018
     */
	 
	 public function DownloadPdf($id,$signoff_id) {
		 
		 if(is_numeric($id) && is_numeric($signoff_id))
       {
        
          $match = array('yp_id'=> $id,'ibp_signoff_details_id'=>$signoff_id,'status'=>'inactive');
          $check_signoff_data = $this->common_model->get_records(NFC_IBP_SIGNOFF_DETAILS,array("form_json_data,created_date"), '', '', $match);
		    if(!empty($check_signoff_data)){
         $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'].REPORT_EXPAIRED_DAYS));
          if(strtotime(datetimeformat()) <= strtotime($expairedDate))
          {
          $data['form_data'] = json_decode($check_signoff_data[0]['form_json_data'], TRUE);

		      //get YP information
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($id,$fields);

          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$id);
          }
          //get ibp yp data
		  
		  $match = array('yp_id'=> $id,'is_previous_version'=>1);
          $data['ibp_edit_data'] = $this->common_model->get_records(NFC_INDIVIDUAL_BEHAVIOUR_PLAN,array("*"), '', '', $match);
        
        $table = NFC_APPROVAL_INDIVIDUAL_BEHAVIOUR_PLAN_SIGNOFF.' as ibp';
        $where = array("l.is_delete"=> "0","ibp.yp_id" => $id,"ibp.is_delete"=> "0");
        $fields = array("ibp.created_by,ibp.created_date,ibp.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id=ibp.created_by');
        $group_by = array('created_by');
        $data['ibp_signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);
          //get ibp old yp data
          $data['signoff_id']= $signoff_id;
          $data['key_data']= $email;
          $data['ypid'] = $id;
          $data['footerJs'][0] = base_url('uploads/custom/js/ibp/ibp.js');
          $data['crnt_view'] = $this->viewname;
          $data['main_content'] = '/signoff_view';
          $pdfFileName = "ibp.pdf";
            $PDFInformation['yp_details'] = $data['YP_details'][0];
            $PDFInformation['edit_data'] = $data['edit_data'][0]['modified_date'];
            $PDFInformation['edit_date'] = $data['ibp_edit_data'][0]['modified_date'];

			      $PDFHeaderHTML  = $this->load->view('ibp_pdfHeader', $PDFInformation,true);
			
            $PDFFooterHTML  = $this->load->view('ibp_pdfFooter', $PDFInformation,true);
			
			      //Set Header Footer and Content For PDF
            $this->m_pdf->pdf->mPDF('utf-8','A4','','','10','10','45','25');
	
            $this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
            $this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);                    
            $data['main_content'] = '/ibp_pdf';
            $html = $this->parser->parse('layouts/PdfDataTemplate', $data);
			
               /*remove*/
            $this->m_pdf->pdf->WriteHTML($html);
            //Store PDF in IBP Folder
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
           
           $msg = $this->lang->line('already_ibp_review');
           $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
           $this->load->view('successfully_message');
        }
        }
        else
        {
            show_404 ();
        }
		 
		 
	 }
}
