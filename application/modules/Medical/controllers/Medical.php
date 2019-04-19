<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Medical extends CI_Controller {

    function __construct() {
        parent::__construct();
        if(checkPermission('Medical','view') == false)
        {
            redirect('/Dashboard');
        }
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Niral Patel
      @Desc   : Medical Index Page
      @Input  : yp id
      @Output :
      @Date   : 18/07/2017
     */
    public function index($id,$careHomeId=0,$isArchive=0) {
		/*NEED TO CHANGE MOVE DATE WHEN IN USE*/
		//get YP information
        if(is_numeric($id)){
            //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($id,$fields);

          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson');
          }
    if($isArchive==0){
        //get mi details
        $match = array("yp_id"=>$id,'is_previous_version'=>0);
        $fields = array("mi_id,allergies_and_meds_not_to_be_used,medical_number,date_received");
        $data['mi_details'] = $this->common_model->get_records(MEDICAL_INFORMATION, $fields, '', '', $match);
        //get mi details
        $match = array("yp_id"=>$id,'is_previous_version'=>1);
        $data['mi_prevous_details'] = $this->common_model->get_records(MEDICAL_INFORMATION, $fields, '', '', $match);

        //get mac details
        $match = array("yp_id"=>$id,'is_previous_version'=>0);
        $fields = array("*");
        $data['mac_details'] = $this->common_model->get_records(MEDICAL_AUTHORISATIONS_CONSENTS, $fields, '', '', $match);
        //get previous mac details
        $match = array("yp_id"=>$id,'is_previous_version'=>1);
        $data['mac_prevous_details'] = $this->common_model->get_records(MEDICAL_AUTHORISATIONS_CONSENTS, $fields, '', '', $match);

        //get mac form
        $match = array('mac_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MAC_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['mac_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        
        //get mp form
        $match = array('mp_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MP_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['mp_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }		
        //get omi details
		
        $match = array("yp_id"=>$id,'is_previous_version'=>0,'care_home_id'=>$data['YP_details'][0]['care_home']);
        $fields = array("*");
        $data['omi_details'] = $this->common_model->get_records(OTHER_MEDICAL_INFORMATION, $fields, '', '', $match);
         //get previous omi details
        $match = array("yp_id"=>$id,'is_previous_version'=>1,'care_home_id'=>$data['YP_details'][0]['care_home']);
        $fields = array("*");
        $data['omi_previous_details'] = $this->common_model->get_records(OTHER_MEDICAL_INFORMATION, $fields, '', '', $match);

        //get omi form
        $match = array('omi_form_id'=> 1);
        $formsdata = $this->common_model->get_records(OMI_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['omi_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        //get mi details
        $match = array("yp_id"=>$id,'is_previous_version'=>0,'care_home_id'=>$data['YP_details'][0]['care_home']);
        $fields = array("*");
        $data['miform_details'] = $this->common_model->get_records(MEDICAL_INOCULATIONS, $fields, '', '', $match);
         //get mi previous details
        $match = array("yp_id"=>$id,'is_previous_version'=>1,'care_home_id'=>$data['YP_details'][0]['care_home']);
        $data['miform_prevous_details'] = $this->common_model->get_records(MEDICAL_INOCULATIONS, $fields, '', '', $match);
        
        //get mi form
        $match = array('mi_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MI_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['mi_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
	}else{
        $match = array("yp_id"=>$id,'is_previous_version'=>0);
        $fields = array("mi_id,allergies_and_meds_not_to_be_used,medical_number,date_received");
        $data['mi_details'] = $this->common_model->get_records(MEDICAL_INFORMATION, $fields, '', '', $match);
        //get mi details
        $match = array("yp_id"=>$id,'is_previous_version'=>1);
        $data['mi_prevous_details'] = $this->common_model->get_records(MEDICAL_INFORMATION, $fields, '', '', $match);
        //get mac details
        $match = array("yp_id"=>$id,'is_previous_version'=>0);
        $fields = array("*");
        $data['mac_details'] = $this->common_model->get_records(MEDICAL_AUTHORISATIONS_CONSENTS, $fields, '', '', $match);
        //get previous mac details
        $match = array("yp_id"=>$id,'is_previous_version'=>1);
        $data['mac_prevous_details'] = $this->common_model->get_records(MEDICAL_AUTHORISATIONS_CONSENTS, $fields, '', '', $match);

        //get mac form
        $match = array('mac_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MAC_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['mac_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        //get mp form
        $match = array('mp_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MP_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['mp_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
		//get omi details
		$match = array("yp_id"=>$id,'is_previous_version'=>0);
        $fields = array("*");
        $data['omi_details'] = $this->common_model->get_records(OTHER_MEDICAL_INFORMATION, $fields, '', '', $match);
         //get previous omi details
        $match = array("yp_id"=>$id,'is_previous_version'=>1);
        $data['omi_previous_details'] = $this->common_model->get_records(OTHER_MEDICAL_INFORMATION, $fields, '', '', $match);
        //get omi form
        $match = array('omi_form_id'=> 1);
        $formsdata = $this->common_model->get_records(OMI_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['omi_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        //get mi details
        $match = array("yp_id"=>$id,'is_previous_version'=>0);
        $fields = array("*");
        $data['miform_details'] = $this->common_model->get_records(MEDICAL_INOCULATIONS, $fields, '', '', $match);
        //get mi previous details
        $match = array("yp_id"=>$id,'is_previous_version'=>1);
        $data['miform_prevous_details'] = $this->common_model->get_records(MEDICAL_INOCULATIONS, $fields, '', '', $match);
        //get mi form
        $match = array('mi_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MI_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['mi_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
	}   
        /*mp data start*/
        $config['per_page'] = '10';
        $data['perpage'] = '10';
        $data['searchtext'] = '';
        $sortfield = 'mp_id';
        $sortby = 'desc';
        $data['sortfield'] = $sortfield;
        $data['sortby'] = $sortby;
        $config['uri_segment'] = 6;
        $uri_segment = $this->uri->segment(6);
        $config['first_link'] = 'First';
        $config['base_url'] = base_url() . $this->viewname . '/mp_ajax/'.$id;
        $table = MEDICAL_PROFESSIONALS . ' as mc';
        $where = array("mc.yp_id"=>$id);
        $fields = array("c.care_home_name,mc.*");
        $join_tables = array(CARE_HOME . ' as c' => 'c.care_home_id= mc.care_home_id');
        if (!empty($searchtext)) {
            
        } else {
            $data['mp_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
			
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
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

        $this->session->set_userdata('professional_medication_session_data', $sortsearchpage_data);
        setActiveSession('professional_medication_session_data'); // set current Session active
        /*end mp data*/
        $data['ypid'] = $id;
        $data['is_archive_page'] = $isArchive;
        $data['careHomeId'] = $careHomeId;
        $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['crnt_view'] = $this->viewname;
        $data['main_content'] = '/medical';
        $this->parser->parse('layouts/DefaultTemplate', $data);
        }else{
            show_404 ();
        }
    }
    /*
      @Author : Niral Patel
      @Desc   : ajax mp data
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */
    public function mp_ajax($ypid,$careHomeId=0,$isArchive=0) {
        $searchtext = $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('professional_medication_session_data');
        }

        $searchsort_session = $this->session->userdata('professional_medication_session_data');
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
                $sortfield = 'administer_medication_id';
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
        if(is_numeric($ypid)){
            //get YP information
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($ypid,$fields);
          
          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson');
          }
        $config['first_link'] = 'First';
		  if($isArchive==0){
        $config['base_url'] = base_url() . $this->viewname . '/mp_ajax/'.$ypid;

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
        $table = MEDICAL_PROFESSIONALS . ' as mc';
        $where = array("mc.yp_id"=>$ypid);
        $fields = array("mc.*");
        if (!empty($searchtext)) {
            
        } else {
            $data['mp_details'] = $this->common_model->get_records($table, $fields, '', '', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            
            $config['total_rows'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
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

        $this->session->set_userdata('professional_medication_session_data', $sortsearchpage_data);
        setActiveSession('professional_medication_session_data'); // set current Session active
        $data['header'] = array('menu_module' => 'YoungPerson');
         //get communication form
        $match = array('mp_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MP_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['mp_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
        $this->load->view($this->viewname . '/mp_ajaxlist', $data);
        }else{
             show_404 ();
        }
    }
    /*
      @Author : Niral Patel
      @Desc   : Add mi data
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */

    public function add_mi($ypid) {
        if(is_numeric($ypid)){
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($ypid,$fields);
          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson');
          }
       //get mi form
        $match = array('mi_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MI_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        //get mi details
        $match = array('yp_id'=> $ypid,'is_previous_version'=>0,'care_home_id'=>$data['YP_details'][0]['care_home']);
        $fields = array("*");
        $data['edit_data'] = $this->common_model->get_records(MEDICAL_INOCULATIONS, $fields, '', '', $match);

        $url_data =  base_url('Medical/add_mi/'.$ypid);
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
            $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/add_mi';
        $this->parser->parse('layouts/DefaultTemplate', $data);
          }
          else
          {
            $msg = $this->lang->line('check_mi_user_update_data');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('/' . $this->viewname .'/index/'. $ypid);
          }
        }else{
          $data['ypid'] = $ypid;
          $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
          $data['header'] = array('menu_module' => 'YoungPerson');
          $data['main_content'] = '/add_mi';
        $this->parser->parse('layouts/DefaultTemplate', $data);
        }
       
        }else{
            show_404 ();
        }
    }
    /*
      @Author : Niral Patel
      @Desc   : Insert mi data
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */
    public function insert_mi()
    {
        $postData = $this->input->post ();
		/*ghelani nikunj
		25/9/2018
		care to care archive need get data from ypid
		*/
		$fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data_yp_detail['YP_details'] = YpDetails($postData['yp_id'],$fields);
		unset($postData['submit_medform']);
        //get mi form
       $match = array('mi_form_id'=> 1);
       $form_data = $this->common_model->get_records(MI_FORM,array("form_json_data"), '', '', $match);
       if(!empty($form_data))
       {
            $pp_form_data = json_decode($form_data[0]['form_json_data'], TRUE);
            $data = array();
            foreach ($pp_form_data as $row) {
                if(isset($row['name']))
                {
                    if($row['type'] == 'file')
                    { 
                      $filename = $row['name'];
                      //get image previous image
                      $match = array('yp_id'=> $postData['yp_id'],'is_previous_version'=>0);
                      $pp_yp_data = $this->common_model->get_records(MEDICAL_INOCULATIONS,array('`'.$row['name'].'`'), '', '', $match);
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

                                if (file_exists ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img)) { 
                                    unlink ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img);
                                }
                                if (file_exists ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img)) {
                                    unlink ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img);
                                }
                              } 
                          }
                      }
                     
                      if(!empty($_FILES[$filename]['name'][0]))                     
                      {
                          //create dir and give permission
                          if (!is_dir($this->config->item('medical_base_url'))) {
                                  mkdir($this->config->item('medical_base_url'), 0777, TRUE);
                          }

                          if (!is_dir($this->config->item('medical_base_big_url'))) {                                
                              mkdir($this->config->item('medical_base_big_url'), 0777, TRUE);
                          }
                          
                          
                          if (!is_dir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'])) {
                              mkdir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'], 0777, TRUE);
                          }
                          $file_view = $this->config->item ('medical_img_url').$postData['yp_id'];
                          //upload big image
                          $upload_data       = uploadImage ($filename, $file_view,'/' . $this->viewname.'/index/'.$postData['yp_id']);


                          //upload small image
                          $insertImagesData = array();
                          if(!empty($upload_data))
                          {
                            foreach ($upload_data as $imageFiles) {
                                if (!is_dir($this->config->item('medical_base_small_url'))) {                                        
                                    mkdir($this->config->item('medical_base_small_url'), 0777, TRUE);
                                }
                                
                                if (!is_dir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'])) {                                        
                                    mkdir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'], 0777, TRUE);
                                }
                                /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                if($imageFiles['is_image'])
                                    $a = do_resize ($this->config->item ('medical_img_url') . $postData['yp_id'], $this->config->item ('medical_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
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

       //get food data
      $match = array('yp_id'=> $postData['yp_id'],'is_previous_version'=>0);
      $check_data = $this->common_model->get_records(MEDICAL_INOCULATIONS,array('*'), '', '', $match);
      //get mac prev data
      $match = array('yp_id'=> $postData['yp_id'],'is_previous_version'=>1);
      $previous_data = $this->common_model->get_records(MEDICAL_INOCULATIONS,array('*'), '', '', $match);

       if(!empty($check_data))
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
                      if(!empty($check_data))
                      {
                        if($postData[$row['name']] != $check_data[0][$row['name']])
                        {
                          $updated_field[]= $row['label'];
                          $n++;
                        }
                      }
                      $update_pre_data[$row['name']] = strip_slashes($check_data[0][$row['name']]);
                    }
                }
            }
          
          $update_pre_data['yp_id']         = $postData['yp_id'];
          $update_pre_data['created_date']  = $check_data[0]['created_date'];
          $update_pre_data['created_by']    = $check_data[0]['created_by'];
          $update_pre_data['modified_by']   = $check_data[0]['modified_by'];
          $update_pre_data['modified_date'] = $check_data[0]['modified_date'];
		  $update_pre_data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
          $update_pre_data['is_previous_version'] = 1;
          }
        
          if(!empty($previous_data))
          {
             if($n != 0)
             {
                $this->common_model->update(MEDICAL_INOCULATIONS,$update_pre_data,array('yp_id'=> $postData['yp_id'],'is_previous_version'=>1,'care_home_id'=>$data_yp_detail['YP_details'][0]['care_home']));
             }
         
          }
          else
          {
			  $update_pre_data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
            $this->common_model->insert(MEDICAL_INOCULATIONS,$update_pre_data);
          }
       }
        if(!empty($check_data))
        {

             $data['modified_date'] = datetimeformat();
             $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
			 $data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
             $this->common_model->update(MEDICAL_INOCULATIONS,$data,array('yp_id'=>$postData['yp_id'],'is_previous_version'=>0,'care_home_id'=>$data_yp_detail['YP_details'][0]['care_home']));
             if(!empty($updated_field))
             {
              foreach (array_unique($updated_field) as $fields) {
                //Insert log activity
                $activity = array(
                  'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
                  'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
                  'module_name'         => MEDS_MEDS_MI_MODULE,
                  'module_field_name'   => $fields,
                  'type'                => 2
                );
                log_activity($activity);
              }
                
             }
           
            
        }
        else
        {
            $data['created_date'] = datetimeformat();
            $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
			$data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
            $data['yp_id'] = $postData['yp_id'];
            $this->common_model->insert(MEDICAL_INOCULATIONS, $data);
            //Insert log activity
             $activity = array(
              'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
              'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
              'module_name'         => MEDS_MEDS_MI_MODULE,
              'module_field_name'   => '',
              'type'                => 1
            );
            log_activity($activity);
        }
        
        redirect('/' . $this->viewname .'/save_mi/'.$postData['yp_id']);
   }
   /*
      @Author : Niral Patel
      @Desc   : save data mi
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */
      public function save_mi($id) {
          //get daily observation data
          if(is_numeric($id)){
              $data = array(
              'header_data' => 'INOCULATIONS Updated',
              'detail' =>'The inoculations section of the Medical Information is now updated. Please check your editing carefully.',
              );
              $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
              $data['YP_details'] = YpDetails($id,$fields);
              if(empty($data['YP_details']))
              {
                  $msg = $this->lang->line('common_no_record_found');
                  $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                  redirect('YoungPerson');
              }
          $data['yp_id'] = $id;
          $data['main_content'] = '/save_data';
          $data['header'] = array('menu_module' => 'YoungPerson');
          $this->parser->parse('layouts/DefaultTemplate', $data);
          }else{
             show_404 ();
          }
    }
    /*
  @Author : Niral Patel
  @Desc   : Add omi data
  @Input  :
  @Output :
  @Date   : 19/07/2017
 */

public function add_omi($ypid) {
    if(is_numeric($ypid)){
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($ypid,$fields);
        if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson');
          }
  //get omi form
    $match = array('omi_form_id'=> 1);
    $formsdata = $this->common_model->get_records(OMI_FORM,array("form_json_data"), '', '', $match);
    if(!empty($formsdata))
    {
        $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
    }

    //get omi details
      $fields = array("*");
      $match = array('yp_id'=> $ypid,'is_previous_version'=>0,'care_home_id'=>$data['YP_details'][0]['care_home']);
      $data['edit_data'] = $this->common_model->get_records(OTHER_MEDICAL_INFORMATION, $fields, '', '', $match);

        $url_data =  base_url('Medical/add_omi/'.$ypid);
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
            $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
            $data['main_content'] = '/add_omi';
            $data['header'] = array('menu_module' => 'YoungPerson');
            $this->parser->parse('layouts/DefaultTemplate', $data);
          }
          else
          {
            $msg = $this->lang->line('check_omi_user_update_data');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('/' . $this->viewname .'/index/'. $ypid);
          }
        }else{
            $data['ypid'] = $ypid;
            $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
            $data['main_content'] = '/add_omi';
            $data['header'] = array('menu_module' => 'YoungPerson');
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
    
    }else{
        show_404 ();
    }
}
/*
  @Author : Niral Patel
  @Desc   : Insert omi data
  @Input  :
  @Output :
  @Date   : 19/07/2017
 */
public function insert_omi()
{
    $postData = $this->input->post();
	//get YP information
	/*ghelani nikunj
	25/9/2018
	care to care archive need get data from ypid
	*/
    $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
    $data_yp_detail['YP_details'] = YpDetails($postData['yp_id'],$fields);
    unset($postData['submit_medform']);
    //get pp form
   $match = array('omi_form_id'=> 1);
   $form_data = $this->common_model->get_records(OMI_FORM,array("form_json_data"), '', '', $match);
   if(!empty($form_data))
   {
        $pp_form_data = json_decode($form_data[0]['form_json_data'], TRUE);
        $data = array();
        foreach ($pp_form_data as $row) {
            if(isset($row['name']))
            {
                if($row['type'] == 'file')
                { 
                  $filename = $row['name'];
                  //get image previous image
                  $match = array('yp_id'=> $postData['yp_id'],'is_previous_version'=>0);
                  $pp_yp_data = $this->common_model->get_records(OTHER_MEDICAL_INFORMATION,array('`'.$row['name'].'`'), '', '', $match);
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

                            if (file_exists ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img)) { 
                                unlink ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img);
                            }
                            if (file_exists ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img)) {
                                unlink ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img);
                            }
                          } 
                      }
                  }
                 
                  if(!empty($_FILES[$filename]['name'][0]))                     
                  {
                      //create dir and give permission
                      if (!is_dir($this->config->item('medical_base_url'))) {
                              mkdir($this->config->item('medical_base_url'), 0777, TRUE);
                      }

                      if (!is_dir($this->config->item('medical_base_big_url'))) {                                
                          mkdir($this->config->item('medical_base_big_url'), 0777, TRUE);
                      }

                      if (!is_dir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'])) {
                          mkdir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'], 0777, TRUE);
                      }
                      $file_view = $this->config->item ('medical_img_url').$postData['yp_id'];
                      //upload big image
                      $upload_data       = uploadImage ($filename, $file_view,'/' . $this->viewname.'/index/'.$postData['yp_id']);


                      //upload small image
                      $insertImagesData = array();
                      if(!empty($upload_data))
                      {
                        foreach ($upload_data as $imageFiles) {
                            if (!is_dir($this->config->item('medical_base_small_url'))) {                                        
                                    mkdir($this->config->item('medical_base_small_url'), 0777, TRUE);
                                }
                                
                                if (!is_dir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'])) {                                        
                                    mkdir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'], 0777, TRUE);
                                }
                                /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                if($imageFiles['is_image'])
                                    $a = do_resize ($this->config->item ('medical_img_url') . $postData['yp_id'], $this->config->item ('medical_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
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
   //get omi data
  $match = array('yp_id'=> $postData['yp_id'],'is_previous_version'=>0,'care_home_id'=>$data_yp_detail['YP_details'][0]['care_home']);
  $check_data = $this->common_model->get_records(OTHER_MEDICAL_INFORMATION,'', '', '', $match);
 
  //get previous omi data
    $match = array('yp_id'=> $postData['yp_id'],'is_previous_version'=>1,'care_home_id'=>$data_yp_detail['YP_details'][0]['care_home']);
    $previous_data = $this->common_model->get_records(OTHER_MEDICAL_INFORMATION,array('*'), '', '', $match);

     if(!empty($check_data))
     {
		 
        $update_pre_data = array();
        $updated_field = array();
        $n=0;
        if(!empty($pp_form_data))
        {
          foreach ($pp_form_data as $row) {
              if(isset($row['name']))
              {
                  if($row['type'] != 'button')
                  {
                    if(!empty($check_data))
                    {
                      if($postData[$row['name']] != $check_data[0][$row['name']])
                      {
                        $updated_field[]= $row['label'];
                        $n++;
                      }
                    }
                    $update_pre_data[$row['name']] = strip_slashes($check_data[0][$row['name']]);
                  }
              }
          }
        
        $update_pre_data['yp_id']         = $postData['yp_id'];
        $update_pre_data['created_date']  = $check_data[0]['created_date'];
        $update_pre_data['created_by']    = $check_data[0]['created_by'];
        $update_pre_data['modified_by']   = $check_data[0]['modified_by'];
        $update_pre_data['modified_date'] = $check_data[0]['modified_date'];
        $update_pre_data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
        $update_pre_data['is_previous_version'] = 1;
        }
		
		
      
        if(!empty($previous_data))
        {
		   if($n != 0)
           {
		      $this->common_model->update(OTHER_MEDICAL_INFORMATION,$update_pre_data,array('yp_id'=> $postData['yp_id'],'is_previous_version'=>1,'care_home_id'=>$data_yp_detail['YP_details'][0]['care_home']));
           }
       }
        else
        {
		  $update_pre_data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
          $this->common_model->insert(OTHER_MEDICAL_INFORMATION,$update_pre_data);
        }
     }
    if(!empty($check_data))
    {
		 $data['modified_date'] = datetimeformat();
         $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
		 $this->common_model->update(OTHER_MEDICAL_INFORMATION,$data,array('yp_id'=>$postData['yp_id'],'is_previous_version'=>0,'care_home_id'=>$data_yp_detail['YP_details'][0]['care_home']));
         if(!empty($updated_field))
             {
              foreach ($updated_field as $fields) {
                //Insert log activity
                $activity = array(
                  'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
                  'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
                  'module_name'         => MEDS_MEDS_OMI_MODULE,
                  'module_field_name'   => $fields,
                  'type'                => 2
                );
                log_activity($activity);
              }
                
             }
    }
    else
    {
		$data['yp_id'] = $postData['yp_id'];
        $data['created_date'] = datetimeformat();
        $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
		$data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
        $this->common_model->insert(OTHER_MEDICAL_INFORMATION, $data);
         //Insert log activity
           $activity = array(
            'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
            'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
            'module_name'         => MEDS_MEDS_OMI_MODULE,
            'module_field_name'   => '',
            'type'                => 1
          );
          log_activity($activity);
    }
    redirect('/' . $this->viewname .'/save_omi/'.$postData['yp_id']);
}
/*
  @Author : Niral Patel
  @Desc   : save data omi
  @Input  :
  @Output :
  @Date   : 19/07/2017
 */
  public function save_omi($id) {
      if(is_numeric($id)){
           $data = array(
            'header_data' => 'OTHER MEDICAL INFO Updated',
            'detail' =>'The other medical information section of the Medical Information is now updated. Please check your editing carefully.',
            );
              $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
              $data['YP_details'] = YpDetails($id,$fields);
              if(empty($data['YP_details']))
              {
                  $msg = $this->lang->line('common_no_record_found');
                  $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                  redirect('YoungPerson');
              }
          $data['yp_id'] = $id;
          $data['main_content'] = '/save_data';
          $data['header'] = array('menu_module' => 'YoungPerson');
          $this->parser->parse('layouts/DefaultTemplate', $data);
          }else{
              show_404 ();
          }
}
    /*
      @Author : Niral Patel
      @Desc   : Add mac data
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */

    public function add_mp($ypid) {
        if(is_numeric($ypid)){
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($ypid,$fields);
          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson');
          }
      //get mac form
        $match = array('mp_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MP_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        $data['ypid'] = $ypid;
        $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
        $data['main_content'] = '/add_mp';
        $data['header'] = array('menu_module' => 'YoungPerson');
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }else{
         show_404 ();
        }
    }
    
    /*
      @Author : Niral Patel
      @Desc   : Insert mp data
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */
    public function insert_mp()
    {
        $postData = $this->input->post();
		/*ghelani nikunj
		25/9/2018
		care to care archive need get data from ypid
		*/
		$fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data_yp_detail['YP_details'] = YpDetails($postData['yp_id'],$fields);
        unset($postData['submit_medform']);
        //get pp form
       $match = array('mp_form_id'=> 1);
       $form_data = $this->common_model->get_records(MP_FORM,'', '', '', $match);
       if(!empty($form_data))
       {
            $pp_form_data = json_decode($form_data[0]['form_json_data'], TRUE);
            $data = array();
            foreach ($pp_form_data as $row) {
                if(isset($row['name']))
                {
                    if($row['type'] == 'file')
                    { 
                      $filename = $row['name'];
                      //get image previous image
                      $match = array('yp_id'=> $postData['yp_id']);
                      $pp_yp_data = $this->common_model->get_records(MEDICAL_PROFESSIONALS,array('`'.$row['name'].'`'), '', '', $match);
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

                                if (file_exists ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img)) { 
                                    unlink ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img);
                                }
                                if (file_exists ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img)) {
                                    unlink ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img);
                                }
                              } 
                          }
                      }
                     
                      if(!empty($_FILES[$filename]['name'][0]))                     
                      {
                          //create dir and give permission
                          if (!is_dir($this->config->item('medical_base_url'))) {
                                  mkdir($this->config->item('medical_base_url'), 0777, TRUE);
                          }

                          if (!is_dir($this->config->item('medical_base_big_url'))) {                                
                              mkdir($this->config->item('medical_base_big_url'), 0777, TRUE);
                          }
                          
                          
                          if (!is_dir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'])) {
                              mkdir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'], 0777, TRUE);
                          }
                          $file_view = $this->config->item ('medical_img_url').$postData['yp_id'];
                          //upload big image
                          $upload_data       = uploadImage ($filename, $file_view,'/' . $this->viewname.'/index/'.$postData['yp_id']);


                          //upload small image
                          $insertImagesData = array();
                          if(!empty($upload_data))
                          {
                            foreach ($upload_data as $imageFiles) {
                                if (!is_dir($this->config->item('medical_base_small_url'))) {                                        
                                    mkdir($this->config->item('medical_base_small_url'), 0777, TRUE);
                                }
                                
                                if (!is_dir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'])) {                                        
                                    mkdir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'], 0777, TRUE);
                                }
                                /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                if($imageFiles['is_image'])
                                    $a = do_resize ($this->config->item ('medical_img_url') . $postData['yp_id'], $this->config->item ('medical_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                array_push($insertImagesData,$imageFiles['file_name']);
                                if(!empty($insertImagesData))
                                {
                                  $images = implode(',',$insertImagesData);
                                }
                            }
                            
                            $data[$row['name']] = !empty($images)?$images:'';
                          }
                        }
                        
                    }
                    else{
                        if ($row['type'] != 'button') {
                            if($row['type'] == 'date'){
                                $data[$row['name']] = dateformat($postData[$row['name']]);                                    
                            } elseif($row['subtype'] == 'time'){
                                $data[$row['name']] = dbtimeformat($postData[$row['name']]);
                            } else if (!empty($postData[$row['name']])) {
                                if ($row['type'] == 'checkbox-group') {
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
       }

      
      if(!empty($data))
        {
          $data['yp_id'] = $postData['yp_id'];
          $data['created_date'] = datetimeformat();
          $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
		  $data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
          $this->common_model->insert(MEDICAL_PROFESSIONALS, $data);
          //Insert log activity
           $activity = array(
            'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
            'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
            'module_name'         => MEDS_MEDS_MP_MODULE,
            'module_field_name'   => '',
            'type'                => 1
          );
          log_activity($activity);
          redirect('/' . $this->viewname .'/save_mp/'.$postData['yp_id']);
        }
        else
        {
          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>Please  insert medical professional details.</div>");
          redirect('/' . $this->viewname .'/add_mp/'.$postData['yp_id']);
        }
        
   }
   /*
      @Author : Niral Patel
      @Desc   : save data mp
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */
      public function save_mp($id) {
          //get daily observation data
          if(is_numeric($id)){
            $data = array(
              'header_data' => 'MEDICAL PROFESSIONAL Updated',
              'detail' =>'The consents part of the Medical Information is now updated. Please check your editing carefully.',
              );
              //get YP information
              $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
              $data['YP_details'] = YpDetails($id,$fields);
              if(empty($data['YP_details']))
              {
                  $msg = $this->lang->line('common_no_record_found');
                  $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                  redirect('YoungPerson');
              }
              $data['yp_id'] = $id;
              $data['header'] = array('menu_module' => 'YoungPerson');
              $data['main_content'] = '/save_data';
              $this->parser->parse('layouts/DefaultTemplate', $data);
    }else{
              show_404 ();
              
    }
    
    }
    /*
      @Author : Niral Patel
      @Desc   : view appointment data
      @Input  :
      @Output :
      @Date   : 5/09/2017
     */
    public function appointment($ypid) {
        if(is_numeric($ypid)){
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
        $professional_name = $this->input->post('professional_name');
        $search_date = $this->input->post('search_date');
        $search_time = $this->input->post('search_time');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('appointment_session_data');
        }

        $searchsort_session = $this->session->userdata('appointment_session_data');
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
                $sortfield = 'appointment_id';
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
        $config['base_url'] = base_url() . $this->viewname . '/appointment/'.$ypid;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        //Query

        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = MEDICAL_PROFESSIONALS_APPOINTMENT . ' as mc';
        
        $whereCond = 'mc.yp_id = '.$ypid.' AND mc.is_delete = "0" ';
        $fields = array("mc.*,mp.*,concat(if(mp.title != '',mp.title,''),' ',if(mp.first_name != '',mp.first_name,''),' ',if(mp.surname != '',mp.surname,''),' - ',if(mp.professional != '',mp.professional,'')) as mp_name, concat(mp.house_namenumber,',',mp.street,' ',mp.town,',',mp.city,',',mp.postcode) as address");
        $join_tables = array(MEDICAL_PROFESSIONALS . ' as mp' => 'mc.mp_id= mp.mp_id',YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id');
        if (!empty($professional_name)) {
                $whereCond .= ' AND mc.mp_id = ' . $professional_name;
        }

        if (!empty($search_date)) {
                $whereCond .= ' AND mc.appointment_date = "' . dateformat($search_date).'"';
        }
        if (!empty($search_time)) {
                $whereCond .= ' AND mc.appointment_time = "' . dbtimeformat($search_time).'"';
        }
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $whereCond);
            
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $whereCond, '', '', '1');
        }

        //get mac form
        $match = array('mp_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MP_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        //get mi details
        $match = array('yp_id'=> $ypid);
        $data['mp_yp_data'] = $this->common_model->get_records(MEDICAL_PROFESSIONALS,'', '', '', $match);

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
            'total_rows' => $config['total_rows'],
            'professional_name' => $professional_name,
            'search_date' => $search_date,
            'search_time' => $search_time);

        $this->session->set_userdata('appointment_session_data', $sortsearchpage_data);
        setActiveSession('appointment_session_data'); // set current Session active
        $data['header'] = array('menu_module' => 'Medical');
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
        $data['header'] = array('menu_module' => 'YoungPerson');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/appointment_ajaxlist', $data);
        } else {
            $data['main_content'] = '/appointment';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
       }else{
              show_404 ();
        } 
    }
    /*
      @Author : Niral Patel
      @Desc   : view appointment
      @Input    : mp id
      @Output   :
      @Date   : 08/09/2017
     */
    public function appointment_view($id,$ypid)
    {
		$data['md_appoint_id']=$id;
		$data['ypid']=$ypid;
      if(is_numeric($id) && is_numeric($ypid)){
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($ypid,$fields);
        /*for medical appointment comment data
		nikunj ghelani 
		20/8/2018
		*/
		$table = MD_COMMENTS . ' as com';
              $where = array("com.yp_id" => $ypid,"com.md_appoint_id"=>$id);
              $fields = array("com.md_comment,com.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name");
              $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by');
              $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
 		//get mi details
        $match = array('mp_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MP_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        $table = MEDICAL_PROFESSIONALS_APPOINTMENT . ' as mc';
        $match = array('mc.appointment_id'=> $id);
        $fields = array("mc.*,concat(mp.title,' ',mp.first_name,' ',mp.surname,' - ',mp.professional) as mp_name,mp.yp_id");
        $join_tables = array(MEDICAL_PROFESSIONALS . ' as mp' => 'mc.mp_id= mp.mp_id',YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id');
        $data['mp_data'] = $this->common_model->get_records($table,$fields,$join_tables, 'left', $match);
       
        if(empty($data['mp_data'])){
          $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson');
        }
        $data['main_content'] = '/view_appointment';
        $this->parser->parse('layouts/DefaultTemplate', $data);
      }else{
            show_404 ();
        }
    }
    /*
      @Author : Niral Patel
      @Desc   : view appointment
      @Input    : mp id
      @Output   :
      @Date   : 08/09/2017
    */
    public function appointment_edit($id,$ypid)
    {
      if(is_numeric($id) && is_numeric($ypid)){
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($ypid,$fields);
        //get mi details
        $match = array('mp_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MP_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        //get medical professionals data
        $table = MEDICAL_PROFESSIONALS_APPOINTMENT . ' as mc';
        $match = array('mc.appointment_id'=> $id);
        $fields = array("mc.*,concat(mp.title,' ',mp.first_name,' ',mp.surname,' - ',mp.professional) as mp_name,mp.yp_id");
        $join_tables = array(MEDICAL_PROFESSIONALS . ' as mp' => 'mc.mp_id= mp.mp_id',YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id');
        $data['mp_data'] = $this->common_model->get_records($table,$fields,$join_tables, 'left', $match);
        if(empty($data['mp_data'])){
          $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson');
        }

        $url_data =  base_url('Medical/appointment_edit/'.$id.'/'.$ypid);
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
             //get mi details
            $match = array('yp_id'=> $ypid);
            $data['mp_yp_data'] = $this->common_model->get_records(MEDICAL_PROFESSIONALS,'', '', '', $match);
            $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
            $data['main_content'] = '/add_appointment';
            $data['edit'] = true;
            $data['mp_id'] = $data['mp_data'][0]['mp_id'];
            $data['ypid'] = $ypid;
            $this->parser->parse('layouts/DefaultTemplate', $data);
          }
          else
          {
              $msg = $this->lang->line('check_ap_user_update_data');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('/' . $this->viewname .'/appointment/'. $ypid);
          }
        }else{
            //get mi details
            $match = array('yp_id'=> $ypid);
            $data['mp_yp_data'] = $this->common_model->get_records(MEDICAL_PROFESSIONALS,'', '', '', $match);
            $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
            $data['main_content'] = '/add_appointment';
            $data['edit'] = true;
            $data['mp_id'] = $data['mp_data'][0]['mp_id'];
            $data['ypid'] = $ypid;
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
      }else{
            show_404 ();
        }
    }
     /*
      @Author : Niral Patel
      @Desc   : Read more
      @Input    : yp id
      @Output   :
      @Date   : 05/09/2017
     */
      public function readmore_appointment($id,$field)
      {
          if(is_numeric($id)){
            $params['fields'] = [$field];
            $params['table'] = MEDICAL_PROFESSIONALS_APPOINTMENT;
            $params['match_and'] = 'appointment_id=' . $id . '';
            $data['documents'] = $this->common_model->get_records_array($params);
            $data['field'] = $field;
            $this->load->view($this->viewname . '/readmore', $data);
          }else{
              show_404 ();
          }
      }
     /*
      @Author : Niral Patel
      @Desc   : Add mi appointment data
      @Input  :
      @Output :
      @Date   : 20/07/2017
     */

    public function add_appointment($mp_id,$ypid) {
      if(is_numeric($mp_id) && is_numeric($ypid)){
       $match = array("yp_id"=>$ypid);
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($ypid,$fields);
          if(empty($data['YP_details']))
          {
              show_404 ();
          }   
      
      //get mi details
      $match = array('mp_id'=> $mp_id);
      $data['mp_data'] = $this->common_model->get_records(MEDICAL_PROFESSIONALS,'', '', '', $match);

      //get mi details
      $match = array('yp_id'=> $ypid);
      $data['mp_yp_data'] = $this->common_model->get_records(MEDICAL_PROFESSIONALS,'', '', '', $match);
      
      //get mac form
      $match = array('mp_form_id'=> 1);
      $formsdata = $this->common_model->get_records(MP_FORM,array("form_json_data"), '', '', $match);
      if(!empty($formsdata))
      {
          $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
      }
      $data['ypid'] = $ypid;
      $data['mp_id'] = $mp_id;
      $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
      $data['header'] = array('menu_module' => 'YoungPerson');
      $data['main_content'] = '/add_appointment';
      $this->parser->parse('layouts/DefaultTemplate', $data);
    }else{
              show_404 ();
    }
 }
     /*
      @Author : Niral Patel
      @Desc   : Insert mi appointment data
      @Input  :
      @Output :
      @Date   : 20/07/2017
     */
     public function insert_appointment() {
        $postData = $this->input->post();
		/*ghelani nikunj
		25/9/2018
		care to care archive need get data from ypid
		*/
		$fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data_yp_detail['YP_details'] = YpDetails($postData['yp_id'],$fields);
        if(!empty($postData['repeat']))
        {
            $repeat = $postData['repeat'];
            //check weekday selected or not
            if (!isset($postData['weekday'])) {
                $postData['weekday'] = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
            }
            //get date range
              $postData['appointment_start_date'] = dateformat($postData['appointment_start_date']);
              $postData['appointment_end_date'] = dateformat($postData['appointment_end_date']);
              $dates = $this->createDateRangeArray($postData['appointment_start_date'], $postData['appointment_end_date'], $postData['weekday']);
              if(!empty($dates))
              {
                foreach ($dates as $date) {
                  if(!empty($postData['dr_name']))
                  {
                    $cnt=0;
                    $new=0;
                    foreach ($postData['dr_name'] as $mpid) {

                      $match = array('is_delete'=>0,'mp_id'=> $mpid,'appointment_date'=>$date,'appointment_time'=>dbtimeformat($postData['repeat_appointment_time']));
                      $appointmentdata = $this->common_model->get_records(MEDICAL_PROFESSIONALS_APPOINTMENT,'', '', '', $match);
                      
                      if(empty($appointmentdata))
                      {
                            $new++;
                            $data = array(
                            'mp_id'            => $mpid,
                            'yp_id'            => $postData['yp_id'],
                            'appointment_date' => $date,
                            'appointment_time' => dbtimeformat($postData['repeat_appointment_time']),
                            'comments'         => $postData['comments'],
                            
                            'is_repeat'        => 1,
                            'created_at'       => datetimeformat(),
                            'created_by'       => $this->session->userdata['LOGGED_IN']['ID'],
                            'care_home_id'       => $data_yp_detail['YP_details'][0]['care_home']
							
                        );
                          $id = $this->common_model->insert(MEDICAL_PROFESSIONALS_APPOINTMENT, $data);

                          //get mi details
                          $match = array('mp_id'=> $mpid);
                          $fields = array("mp_id,concat(title,' ',first_name,' ',surname,' - ',professional) as mp_name");
                          $mp_data = $this->common_model->get_records(MEDICAL_PROFESSIONALS,$fields, '', '', $match);

                            $comdata = array(
                            'who_was_communication_with' => $mp_data[0]['mp_name'],
                            'appointment_id' => $id,
                            'yp_id' => $postData['yp_id'],
                            'date_of_communication' => dateformat($postData['appointment_date']),
                            'time' => dbtimeformat($postData['repeat_appointment_time']),
                            'type' => 'Face to Face Meeting',
                            'comments' => $postData['comments'],
							
                            'created_date' => datetimeformat(),
                            'created_by' => $this->session->userdata['LOGGED_IN']['ID'],
                            'appointment' => '1',
							'care_home_id'       => $data_yp_detail['YP_details'][0]['care_home']
                        );

                          $this->common_model->insert(MEDICAL_COMMUNICATION, $comdata); 
                      }
                          else
                          {
                            $cnt++;
                          }
                          
                      }
                       
                  }
                  
                }//end date foreach
                 if(!empty($new))
                    {
                  //Insert log activity
                  $activity = array(
                    'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
                    'module_name'         => MEDS_MEDS_APP_MODULE,
                    'module_field_name'   => $mp_data[0]['mp_name'],
                    'type'                => 1
                  );
                  log_activity($activity);
                 }
                if(!empty($cnt))
                  {
                    $msg = $this->lang->line('some_appointment_already_exist');
                        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

                    redirect('/' . $this->viewname .'/appointment/'.$postData['yp_id']);
                  }
                  else
                  {
                    redirect('/' . $this->viewname .'/save_appointment/'.$postData['yp_id']);
                  }
              }
        }
        else
        {
            if(!empty($postData['dr_name']))
            {
              $cnt=0;
              foreach ($postData['dr_name'] as $mpid) {
                $match = array('is_delete'=>0,'mp_id'=> $mpid,'appointment_date'=>dateformat($postData['appointment_date']),'appointment_time'=>dbtimeformat($postData['appointment_time']));
                $appointmentdata = $this->common_model->get_records(MEDICAL_PROFESSIONALS_APPOINTMENT,'', '', '', $match);
                if(empty($appointmentdata))
                {
                      $data = array(
                      'mp_id'            => $mpid,
                      'yp_id'            => $postData['yp_id'],
                      'appointment_date' => dateformat($postData['appointment_date']),
                      'appointment_time' => dbtimeformat($postData['appointment_time']),
                      'comments'         => $postData['comments'],
                      
                      'created_at'       => datetimeformat(),
                      'created_by'       => $this->session->userdata['LOGGED_IN']['ID'],
					  'care_home_id'       => $data_yp_detail['YP_details'][0]['care_home']
                  );
                    $id = $this->common_model->insert(MEDICAL_PROFESSIONALS_APPOINTMENT, $data);


                    //get mi details
                    $match = array('mp_id'=> $mpid);
                    $fields = array("mp_id,concat(title,' ',first_name,' ',surname,' - ',professional) as mp_name");
                    $mp_data = $this->common_model->get_records(MEDICAL_PROFESSIONALS,$fields, '', '', $match);

                      $comdata = array(
                      'who_was_communication_with' => $mp_data[0]['mp_name'],
                      'appointment_id' => $id,
                      'yp_id' => $postData['yp_id'],
                      'date_of_communication' => dateformat($postData['appointment_date']),
                      'time' => dbtimeformat($postData['appointment_time']),
                      'type' => 'Face to Face Meeting',
                      'comments' => $postData['comments'],
					   
                      'created_date' => datetimeformat(),
                      'created_by' => $this->session->userdata['LOGGED_IN']['ID'],
                      'appointment' => '1',
					  'care_home_id'       => $data_yp_detail['YP_details'][0]['care_home']
                  );

                    $this->common_model->insert(MEDICAL_COMMUNICATION, $comdata); 
                    //Insert log activity
                        $activity = array(
                          'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
                          'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
                          'module_name'         => MEDS_MEDS_APP_MODULE,
                          'module_field_name'   => $mp_data[0]['mp_name'],
                          'type'                => 1
                        );
                        log_activity($activity);
                    }
                    else
                    {
                      $cnt++;
                    }
                }
            }
            if(!empty($cnt))
            {
              if(count($postData['dr_name']) == 1)
              {$msg = $this->lang->line('appointment_already_exist');
                  $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");}
              else
              {$msg = $this->lang->line('some_appointment_already_exist');
                  $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");}
              redirect('/' . $this->viewname .'/appointment/'.$postData['yp_id']);
            }
            else
            {
              redirect('/' . $this->viewname .'/save_appointment/'.$postData['yp_id']);
            }
        }
        
    }
    //create date range
    function createDateRangeArray($strDateFrom, $strDateTo, $days, $format = "Y-m-d") {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.
        // could test validity of dates here but I'm already doing
        // that in the main script

        $strDateTo = date("Y-m-d", strtotime("$strDateTo +1 day"));
        $begin = new DateTime($strDateFrom);
        $end = new DateTime($strDateTo);

        $interval = new DateInterval('P1D'); // 1 Day
        $dateRange = new DatePeriod($begin, $interval, $end);

        $range = [];
        foreach ($dateRange as $date) {
            $date = $date->format($format);
            $day = date('l', strtotime($date));
            if (in_array($day, $days)) {
                $range[] = $date;
            }
        }

        return $range;
    }
    /*
      @Author : Niral Patel
      @Desc   : update mi appointment data
      @Input  :
      @Output :
      @Date   : 08/09/2017
     */
     public function update_appointment() {
     
        $postData = $this->input->post();
    
        if(!empty($postData['dr_name'][0])) {
      
          $match = array('appointment_id !='=>$postData['appointment_id'],'is_delete'=>0,'mp_id'=> $postData['dr_name'][0],'appointment_date'=>dateformat($postData['appointment_date']),'appointment_time'=>dbtimeformat($postData['appointment_time']));
          $appointmentdata = $this->common_model->get_records(MEDICAL_PROFESSIONALS_APPOINTMENT,array('appointment_id'), '', '', $match);
          
      
          if(empty($appointmentdata))
          {
                $updatedata = array(
                'mp_id'            => $postData['dr_name'][0],
                'appointment_date' => dateformat($postData['appointment_date']),
                'appointment_time' => dbtimeformat($postData['appointment_time']),
                'comments'         => $postData['comments'],
				
                'is_repeat'        => isset($postData['repeat'])?1:0,
                'modified_by'             => !empty($this->session->userdata('LOGGED_IN')['ID'])?$this->session->userdata('LOGGED_IN')['ID']:'',
                'modified_date'           => datetimeformat()
            );

              $id = $this->common_model->update(MEDICAL_PROFESSIONALS_APPOINTMENT, $updatedata,array('appointment_id'=>$postData['appointment_id']));
              //get mi details
              $match = array('mp_id'=> $postData['dr_name'][0]);
              $fields = array("mp_id,concat(title,' ',first_name,' ',surname,' - ',professional) as mp_name");
              $mp_data = $this->common_model->get_records(MEDICAL_PROFESSIONALS,$fields, '', '', $match);
              $comdata = array(
                  'who_was_communication_with' => $mp_data[0]['mp_name'],
                  'yp_id' => $postData['yp_id'],
                  'date_of_communication' => dateformat($postData['appointment_date']),
                  'time' => dbtimeformat($postData['appointment_time']),
                  'type' => 'Face to Face Meeting',
                  'comments' => $postData['comments'],
                  'modified_date' => datetimeformat(),
                  'modified_by' => $this->session->userdata['LOGGED_IN']['ID'],
                  'appointment' => '1'
              );

              $this->common_model->update(MEDICAL_COMMUNICATION, $comdata,array('appointment_id'=>$postData['appointment_id']));
            
              //get mi details
                $match = array('mp_id'=> $postData['dr_name'][0]);
                $fields = array("mp_id,concat(title,' ',first_name,' ',surname,' - ',professional) as mp_name");
                $mp_data = $this->common_model->get_records(MEDICAL_PROFESSIONALS,$fields, '', '', $match);
              //Insert log activity
                $activity = array(
                  'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
                  'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
                  'module_name'         => MEDS_MEDS_APP_MODULE,
                  'module_field_name'   => $mp_data[0]['mp_name'],
                  'type'                => 2
                );
               log_activity($activity);
               $msg = $this->lang->line('update_appointment_sucessfully');
               $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
               redirect('/' . $this->viewname .'/appointment/'.$postData['yp_id']);
          }
          else
          {

              $msg = $this->lang->line('appointment_already_exist');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('/' . $this->viewname .'/appointment/'.$postData['yp_id']);
          }
       }
       else
       {
              $msg = $this->lang->line('select_appointment_fields');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('/' . $this->viewname .'/appointment_edit/'.$postData['appointment_id'].'/'.$postData['yp_id']);
       }
    }
    /*
      @Author : Niral Patel
      @Desc   : save data appointment
      @Input  :
      @Output :
      @Date   : 20/07/2017
     */
      public function save_appointment($id) {
          if(is_numeric($id)){
          $data = array(
            'header_data' => 'New Appointment Medical Appointment Added',
            'detail' => 'You have added a new Medical Appointment. Please check your editing carefully.',
          );
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($id,$fields);
          if(empty($data['YP_details']))
          {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson');
          }

          $data['yp_id'] = $id;
          $data['header'] = array('menu_module' => 'YoungPerson');
          $data['main_content'] = '/save_appointment_data';
          $this->parser->parse('layouts/DefaultTemplate', $data);
        }else{
              show_404 ();
        }
    }
    /*
    @Description: Function for delete data
    @Author: Niral Patel
    @Input: - Delete id
    @Output: - New list after record is deleted.
    @Date: 8/09/2016
    */
    public function ajax_delete_all() {
        $id = $this->input->post ('single_remove_id');
        $table = MEDICAL_PROFESSIONALS_APPOINTMENT . ' as mc';
        $match = array('mc.appointment_id'=> $id);
        $fields = array("mc.*,concat(mp.title,' ',mp.first_name,' ',mp.surname,' - ',mp.professional) as mp_name,mp.yp_id");
        $join_tables = array(MEDICAL_PROFESSIONALS . ' as mp' => 'mc.mp_id= mp.mp_id',YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id');
        $data['mp_data'] = $this->common_model->get_records($table,$fields,$join_tables, 'left', $match);

        if (!empty($id)) {
            $whereImg        = array('appointment_id' => $id);
            $cdata['is_delete'] = 1;
            $this->common_model->update (MEDICAL_PROFESSIONALS_APPOINTMENT, $cdata, $whereImg);

            $where = array('appointment_id' => $id);
            $this->common_model->delete(MEDICAL_COMMUNICATION, $where);

                   $activity = array(
                  'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
                  'yp_id'               => !empty($data['mp_data'][0]['yp_id'])?$data['mp_data'][0]['yp_id']:'',
                  'module_name'         => MEDS_MEDS_APP_MODULE,
                  'module_field_name'   => '',
                  'type'                => 3
                );
                log_activity($activity);
            unset($id);
        }
        $array_data      = $this->input->post ('myarray');
        $delete_all_flag = 0;
        $cnt             = 0;
        for ($i = 0; $i < count ($array_data); $i++) {
            $whereImg        = array('appointment_id' => $array_data[$i]);
            $cdata['is_delete'] = 1;
            $this->common_model->update (MEDICAL_PROFESSIONALS_APPOINTMENT, $cdata, $whereImg);
            $delete_all_flag = 1;
            $cnt++;
        }
        //pagingation
        $searchsortSession = $this->session->userdata ('appointment_session_data');

        if (!empty($searchsortSession['uri_segment']))
            $pagingid = $searchsortSession['uri_segment'];
        else
            $pagingid = 0;
        $perpage    = !empty($searchsortSession['perpage']) ? $searchsortSession['perpage'] : '10';
        $total_rows = $searchsortSession['total_rows'];
        if ($delete_all_flag == 1) {
            $total_rows -= $cnt;
            $pagingid * $perpage;
            if ($pagingid * $perpage > $total_rows) {
                if ($total_rows % $perpage == 0) // if all record delete
                {
                    $pagingid -= $perpage;
                }
            }
        } else {
            if ($total_rows % $perpage == 1)
                $pagingid -= $perpage;
        }

        if ($pagingid < 0)
            $pagingid = 0;
        echo $pagingid;
    }
    /*
      @Author : Niral Patel
      @Desc   : Add mac data
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */

    public function add_mac($ypid) {
        if(is_numeric($ypid)){
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($ypid,$fields);
          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson');
          } 
      //get mac form
        $match = array('mac_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MAC_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        //get mac details
        $match = array('yp_id'=> $ypid,'is_previous_version'=>0,'care_home_id'=>$data['YP_details'][0]['care_home']);
        $fields = array("*");
        $data['edit_data'] = $this->common_model->get_records(MEDICAL_AUTHORISATIONS_CONSENTS, $fields, '', '', $match);

        $url_data =  base_url('Medical/add_mac/'.$ypid);
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
              $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
              $data['main_content'] = '/add_mac';
              $this->parser->parse('layouts/DefaultTemplate', $data);
          }
          else
          {
              $msg = $this->lang->line('check_mac_user_update_data');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('/' . $this->viewname .'/index/'. $ypid);
          }
        }else{
              $data['ypid'] = $ypid;
              $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
              $data['main_content'] = '/add_mac';
              $this->parser->parse('layouts/DefaultTemplate', $data);
        }
        
    }else{
              show_404 ();
    }}

    /*
      @Author : Niral Patel
      @Desc   : insert mac data
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */
    public function insert_mac()
    {
        $postData = $this->input->post ();
		/*ghelani nikunj
		25/9/2018
		care to care archive need get data from ypid
		*/
		$fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data_yp_detail['YP_details'] = YpDetails($postData['yp_id'],$fields);
        unset($postData['submit_medform']);
        //get pp form
       $match = array('mac_form_id'=> 1);
       $form_data = $this->common_model->get_records(MAC_FORM,array("form_json_data"), '', '', $match);
       if(!empty($form_data))
       {
            $pp_form_data = json_decode($form_data[0]['form_json_data'], TRUE);
            $data = array();
            foreach ($pp_form_data as $row){
                if(isset($row['name']))
                {
                    if($row['type'] == 'file')
                    { 
                      $filename = $row['name'];
                      //get image previous image
                      $match = array('yp_id'=> $postData['yp_id'],'is_previous_version'=>0);
                      $pp_yp_data = $this->common_model->get_records(MEDICAL_AUTHORISATIONS_CONSENTS,array('`'.$row['name'].'`'), '', '', $match);
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

                                if (file_exists ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img)) { 
                                    unlink ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img);
                                }
                                if (file_exists ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img)) {
                                    unlink ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img);
                                }
                              } 
                          }
                      }
                     
                      if(!empty($_FILES[$filename]['name'][0]))                     
                      {
                          //create dir and give permission
                          if (!is_dir($this->config->item('medical_base_url'))) {
                                  mkdir($this->config->item('medical_base_url'), 0777, TRUE);
                          }

                          if (!is_dir($this->config->item('medical_base_big_url'))) {                                
                              mkdir($this->config->item('medical_base_big_url'), 0777, TRUE);
                          }
                          
                          
                          if (!is_dir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'])) {
                              mkdir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'], 0777, TRUE);
                          }
                          $file_view = $this->config->item ('medical_img_url').$postData['yp_id'];
                          //upload big image
                          $upload_data       = uploadImage ($filename, $file_view,'/' . $this->viewname.'/index/'.$postData['yp_id']);

                          //upload small image
                          $insertImagesData = array();
                          if(!empty($upload_data))
                          {
                            foreach ($upload_data as $imageFiles) {
                                if (!is_dir($this->config->item('medical_base_small_url'))) {                                        
                                    mkdir($this->config->item('medical_base_small_url'), 0777, TRUE);
                                }

                                if (!is_dir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'])) {
                                    mkdir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'], 0777, TRUE);
                                }
                                /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                if($imageFiles['is_image'])
                                    $a = do_resize ($this->config->item ('medical_img_url') . $postData['yp_id'], $this->config->item ('medical_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
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

       //get mac data
      $match = array('yp_id'=> $postData['yp_id'],'is_previous_version'=>0,'care_home_id'=>$data_yp_detail['YP_details'][0]['care_home']);
      $check_data = $this->common_model->get_records(MEDICAL_AUTHORISATIONS_CONSENTS,'', '', '', $match);

      //get mac prev data
      $match = array('yp_id'=> $postData['yp_id'],'is_previous_version'=>1,'care_home_id'=>$data_yp_detail['YP_details'][0]['care_home']);
      $previous_data = $this->common_model->get_records(MEDICAL_AUTHORISATIONS_CONSENTS,array('*'), '', '', $match);

       if(!empty($check_data))
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
                      if(!empty($check_data))
                      {
                        if($postData[$row['name']] != $check_data[0][$row['name']])
                        {
                          $updated_field[]= $row['label'];
                          $n++;
                        }
                      }
                      $update_pre_data[$row['name']] = strip_slashes($check_data[0][$row['name']]);
                    }
                }
            }
          
          $update_pre_data['yp_id']         = $postData['yp_id'];
          $update_pre_data['created_date']  = $check_data[0]['created_date'];
          $update_pre_data['created_by']    = $check_data[0]['created_by'];
          $update_pre_data['modified_by']   = $check_data[0]['modified_by'];
          $update_pre_data['modified_date'] = $check_data[0]['modified_date'];
		  $update_pre_data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
          $update_pre_data['is_previous_version'] = 1;
          }

          if(!empty($previous_data))
          {
             if($n != 0)
             {
                $this->common_model->update(MEDICAL_AUTHORISATIONS_CONSENTS,$update_pre_data,array('yp_id'=> $postData['yp_id'],'is_previous_version'=>1,'care_home_id'=>$data_yp_detail['YP_details'][0]['care_home']));
             }
         }
          else
          {
			  $update_pre_data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
            $this->common_model->insert(MEDICAL_AUTHORISATIONS_CONSENTS,$update_pre_data);
          }
       }
        if(!empty($check_data))
        {

             $data['modified_date'] = datetimeformat();
             $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
             $this->common_model->update(MEDICAL_AUTHORISATIONS_CONSENTS,$data,array('yp_id'=>$postData['yp_id'],'is_previous_version'=>0,'care_home_id'=>$data_yp_detail['YP_details'][0]['care_home']));
             if(!empty($updated_field))
             {
              foreach (array_unique($updated_field) as $fields) {
                //Insert log activity
                $activity = array(
                  'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
                  'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
                  'module_name'         => MEDS_MEDS_MAC_MODULE,
                  'module_field_name'   => $fields,
                  'type'                => 2
                );
                log_activity($activity);
              }
            }
        }
        else
        {
            $data['yp_id'] = $postData['yp_id'];
            $data['created_date'] = datetimeformat();
            $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
			$data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
            $this->common_model->insert(MEDICAL_AUTHORISATIONS_CONSENTS, $data);
            //Insert log activity
             $activity = array(
              'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
              'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
              'module_name'         => MEDS_MEDS_MAC_MODULE,
              'module_field_name'   => '',
              'type'                => 1
            );
            log_activity($activity);
        }
        redirect('/' . $this->viewname .'/save_mac/'.$postData['yp_id']);
   }
   /*
      @Author : Niral Patel
      @Desc   : save data mac
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */
      public function save_mac($id) {
          //get daily observation data
          if(is_numeric($id)){
             $data = array(
              'header_data' => 'MEDICAL AUTHORISATIONS & CONSENTS Updated',
              'detail' =>'The consents part of the Medical Information is now updated. Please check your editing carefully.',
              );
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($id,$fields);
          if(empty($data['YP_details']))
          {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson');
          }
          $data['yp_id'] = $id;
          $data['header'] = array('menu_module' => 'YoungPerson');
          $data['main_content'] = '/save_data';
          $this->parser->parse('layouts/DefaultTemplate', $data);
          }else{
              show_404 ();
          }
    }
    /*
      @Author : Ritesh Rana
      @Desc   : create yp edit page
      @Input  :
      @Output :
      @Date   : 13/07/2017
    */
    public function create($id) {
        //get YP information
        if(is_numeric($id)){
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($id,$fields);
          if(empty($data['YP_details']))
          {
              show_404 ();
          }
        $data['ypid'] = $id;
        $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
        $data['medicalNumberId'] = $this->common_model->medicalnumber();
        $data['crnt_view'] = $this->viewname;
        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['main_content'] = '/addinformation';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }else{
         show_404 ();
    }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert medical
      @Input    :
      @Output   :
      @Date   : 19/07/2017
     */

    public function insert() {
        $postData = $this->input->post();
        //get do previous version
        $where = array("yp_id" => $postData['yp_id'],'is_previous_version'=>0);
        $fields = array("*");
        $med_data = $this->common_model->get_records(MEDICAL_INFORMATION, $fields, '', '', '', '', '', '', '', '', '', $where);

        $where = array("yp_id" => $postData['yp_id'],'is_previous_version'=>1);
        $fields = array("*");
        $med_prev_data = $this->common_model->get_records(MEDICAL_INFORMATION, $fields, '', '', '', '', '', '', '', '', '', $where);

        if(!empty($med_data))
        {
            $update_data = array();
			$update_data['yp_id'] = $postData['yp_id'];
            foreach ($med_data as $key => $value) {
                $update_data[$key] = $value;
            }
            unset($update_data[0]['mi_id']);
        }
       if(!empty($med_prev_data))
       {
             $update_data[0]['is_previous_version'] = 1;
             $this->common_model->update(MEDICAL_INFORMATION, $update_data[0], array('yp_id' => $postData['yp_id'],'is_previous_version' => 1));
       }
       else
       {
		    $update_data['yp_id'] = $postData['yp_id'];
            $update_data[0]['is_previous_version'] = 1;
		    $this->common_model->insert(MEDICAL_INFORMATION, $update_data[0]);
       }

        $data = array(
            'medical_number' => $this->input->post('medical_number'),
            'date_received' => !empty($postData['date_received'])?dateformat($postData['date_received']):'',
        );
        $updated_field = array();
        if($this->input->post('medical_number') != $update_data[0]['medical_number']){
            $updated_field['medical_number'] = $this->input->post('medical_number'); 
        }
      if($this->input->post('date_received') != $update_data[0]['date_received']){
            $updated_field['date_received'] = $this->input->post('date_received'); 
        }
      if (!empty($postData['mi_id'])) { 
            $data['mi_id'] = $postData['mi_id'];
            $data['yp_id'] = $postData['yp_id'];
            $data['modified_date'] = datetimeformat();
            $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            $this->common_model->update(MEDICAL_INFORMATION, $data, array('mi_id' => $postData['mi_id']));
            if(!empty($updated_field))
            {
              foreach (array_unique($updated_field) as $key => $fields) {
                //Insert log activity
                $activity = array(
                  'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
                  'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
                  'module_name'         => MEDS_MEDS_YP_DETAILS,
                  'module_field_name'   => ucfirst(str_replace("_", ' ', $key)),
                  'type'                => 2
                );
                log_activity($activity);
              }
            }
        } else { 
		
            $data['yp_id'] = $postData['yp_id'];
            $data['created_date'] = datetimeformat();
            $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            //$this->common_model->insert(MEDICAL_INFORMATION, $data);
            $activity = array(
              'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
              'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
              'module_name'         => MEDS_MEDS_YP_DETAILS,
              'module_field_name'   => '',
              'type'                => 1
            );
            log_activity($activity);
        }
        redirect('/' . $this->viewname .'/index/'. $data['yp_id']);
    }
    
    /*
      @Author : Ritesh Rana
      @Desc   : Insert Allergies
      @Input    :
      @Output   :
      @Date   : 19/07/2017
    */
    public function insertAllergies() {
        $postData = $this->input->post();
        $where = array("yp_id" => $postData['yp_id'],'is_previous_version'=>0);
        $fields = array("allergies_and_meds_not_to_be_used,mi_id");
        $med_data = $this->common_model->get_records(MEDICAL_INFORMATION, $fields, '', '', '', '', '', '', '', '', '', $where);

        $where = array("yp_id" => $postData['yp_id'],'is_previous_version'=>1);
        $med_prev_data = $this->common_model->get_records(MEDICAL_INFORMATION, $fields, '', '', '', '', '', '', '', '', '', $where);
        if(!empty($med_data))
        {
            $update_data = array();
            foreach ($med_data as $key => $value) {
                $update_data[$key] = $value;
            }
            unset($update_data[0]['mi_id']);
        }
        if(!empty($med_prev_data))
        {
            if($med_prev_data[0]['allergies_and_meds_not_to_be_used'] != $postData['allergies_and_meds_not_to_be_used'])
            {
             $this->common_model->update(MEDICAL_INFORMATION, $update_data[0], array('yp_id' => $postData['yp_id'],'is_previous_version' => 1));
            }
        }
        else
        {
            $update_data[0]['is_previous_version'] = 1;
            $this->common_model->insert(MEDICAL_INFORMATION, $update_data[0]);
        }
        $data = array(
            'allergies_and_meds_not_to_be_used' =>!empty($postData['allergies_and_meds_not_to_be_used'])?strip_tags($postData['allergies_and_meds_not_to_be_used']):'',
        );
        
        if (!empty($postData['mi_id'])) {
            $data['mi_id'] = $postData['mi_id'];
            $data['yp_id'] = $postData['yp_id'];
            $data['modified_date'] = datetimeformat();
            $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            $this->common_model->update(MEDICAL_INFORMATION, $data, array('mi_id' => $postData['mi_id']));
            //Insert log activity
             $activity = array(
              'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
              'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
              'module_name'         => MEDS_MEDS_ALLERGIES_MODULE,
              'module_field_name'   => '',
              'type'                => 2
            );
            log_activity($activity);
        } else {
            $data['yp_id'] = $postData['yp_id'];
            $data['created_date'] = datetimeformat();
            $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            $this->common_model->insert(MEDICAL_INFORMATION, $data);
            $data['mi_id'] = $this->db->insert_id();
            //Insert log activity
             $activity = array(
              'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
              'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
              'module_name'         => MEDS_MEDS_ALLERGIES_MODULE,
              'module_field_name'   => '',
              'type'                => 1
            );
            log_activity($activity);
        }
        redirect('/' . $this->viewname .'/index/'. $data['yp_id']);
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert Allergies
      @Input    :
      @Output   :
      @Date   : 19/07/2017
    */
    public function editMi($yp_id,$mi_id){
        //get YP information
         if(is_numeric($yp_id) && is_numeric($mi_id)){
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($yp_id,$fields);
          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson');
          }
        $match = "mi_id = " . $mi_id;
        $fields = array("*");
        $data['edit_record'] = $this->common_model->get_records(MEDICAL_INFORMATION, $fields, '', '', $match);

        $url_data =  base_url('Medical/editMi/'.$yp_id.'/'.$mi_id);
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
              $data['ypid'] = $yp_id;
              $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
              $data['medicalNumberId'] = $this->common_model->medicalnumber();
              $data['crnt_view'] = $this->viewname;
              $data['header'] = array('menu_module' => 'YoungPerson');
              $data['main_content'] = '/addinformation';
              $this->parser->parse('layouts/DefaultTemplate', $data);
          }
          else
          {
              $msg = $this->lang->line('check_ypd_user_update_data');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('/' . $this->viewname .'/index/'. $yp_id);
          }
        }else{
              $data['ypid'] = $yp_id;
              $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
              $data['medicalNumberId'] = $this->common_model->medicalnumber();
              $data['crnt_view'] = $this->viewname;
              $data['header'] = array('menu_module' => 'YoungPerson');
              $data['main_content'] = '/addinformation';
              $this->parser->parse('layouts/DefaultTemplate', $data);
        }
    }else{
        show_404 ();
     }
    }
    
     /*
      @Author : Ritesh Rana
      @Desc   : add edit allergies
      @Input    :
      @Output   :
      @Date   : 19/07/2017
    */
     public function add_edit_allergies($yp_id,$mi_id) {
        //get YP information
         if(is_numeric($yp_id) && is_numeric($mi_id)){
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($yp_id,$fields);
          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson');
          }

        $match = "mi_id = " . $mi_id;
        $fields = array("*");
        $data['edit_record'] = $this->common_model->get_records(MEDICAL_INFORMATION, $fields, '', '', $match);
        
        $url_data =  base_url('Medical/add_edit_allergies/'.$yp_id.'/'.$mi_id);
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
              $data['ypid'] = $yp_id;
              $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
              $data['medicalNumberId'] = $this->common_model->medicalnumber();
              $data['crnt_view'] = $this->viewname;
              $data['header'] = array('menu_module' => 'YoungPerson');
              $data['main_content'] = '/add_allergies_information';
              $this->parser->parse('layouts/DefaultTemplate', $data);
          }
          else
          {
            $msg = $this->lang->line('check_ypd_user_update_data');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('/' . $this->viewname .'/index/'. $yp_id);
          }
        }else{
              $data['ypid'] = $yp_id;
              $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
              $data['medicalNumberId'] = $this->common_model->medicalnumber();
              $data['crnt_view'] = $this->viewname;
              $data['header'] = array('menu_module' => 'YoungPerson');
              $data['main_content'] = '/add_allergies_information';
              $this->parser->parse('layouts/DefaultTemplate', $data);
        }

    }else{
        show_404 ();
     }
    }
    
    
    /*
      @Author : Niral Patel
      @Desc   : view mc data
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */
    public function view_mc($ypid,$careHomeId=0,$isArchive=0) {
        $data['is_archive_page'] = $isArchive;
        $data['careHomeId'] = $careHomeId;
        if(is_numeric($ypid)){
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
            $this->session->unset_userdata('mc_session_data');
        }

        $searchsort_session = $this->session->userdata('mc_session_data');
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
                $sortfield = 'communication_id';
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
        $config['base_url'] = base_url() . $this->viewname . '/view_mc/'.$ypid;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        //Query

        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = MEDICAL_COMMUNICATION . ' as mc';
        $where = array("mc.yp_id"=>$ypid);
        $fields = array("mc.*,time as time");
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, '', '', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
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

        $this->session->set_userdata('mc_session_data', $sortsearchpage_data);
        setActiveSession('mc_session_data'); // set current Session active
        $data['header'] = array('menu_module' => 'Communication');

        //get communication form
        $match = array('mc_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MC_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
        $data['header'] = array('menu_module' => 'YoungPerson');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/mc_ajaxlist', $data);
        } else {
            $data['main_content'] = '/mc_list';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
        }else{
              show_404 ();
        } 
    }
    /*
      @Author : Niral Patel
      @Desc   : Add mc data
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */

    public function add_mc($ypid) {
        if(is_numeric($ypid)){
          $match = array("yp_id"=>$ypid);
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($ypid,$fields);
          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson');
          }
        //get mc form
        $match = array('mc_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MC_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        $data['ypid'] = $ypid;
        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
        $data['main_content'] = '/add_mc';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }else{
              show_404 ();
    }
    }

    /*
      @Author : Niral Patel
      @Desc   : Insert mc data
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */
    public function insert_mc()
    {
        $postData = $this->input->post ();
	    /*ghelani nikunj
		25/9/2018
		care to care archive need get data from ypid
		*/
		$fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data_yp_detail['YP_details'] = YpDetails($postData['yp_id'],$fields);
        unset($postData['submit_medform']);
        //get pp form
       $match = array('mc_form_id'=> 1);
       $form_data = $this->common_model->get_records(MC_FORM,array("form_json_data"), '', '', $match);
       if(!empty($form_data))
       {
            $pp_form_data = json_decode($form_data[0]['form_json_data'], TRUE);
            $data = array();
            foreach ($pp_form_data as $row) {
                if(isset($row['name']))
                {
                    if($row['type'] == 'file')
                    { 
                      $filename = $row['name'];
                      //get image previous image
                      $match = array('yp_id'=> $postData['yp_id']);
                      $pp_yp_data = $this->common_model->get_records(MEDICAL_COMMUNICATION,array('`'.$row['name'].'`'), '', '', $match);
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

                                if (file_exists ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img)) { 
                                    unlink ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img);
                                }
                                if (file_exists ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img)) {
                                    unlink ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img);
                                }
                              } 
                          }
                      }
                     
                      if(!empty($_FILES[$filename]['name'][0]))                     
                      {
                          //create dir and give permission
                          if (!is_dir($this->config->item('medical_base_url'))) {
                                  mkdir($this->config->item('medical_base_url'), 0777, TRUE);
                          }

                          if (!is_dir($this->config->item('medical_base_big_url'))) {                                
                              mkdir($this->config->item('medical_base_big_url'), 0777, TRUE);
                          }
                          
                          
                          if (!is_dir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'])) {
                              mkdir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'], 0777, TRUE);
                          }
                          $file_view = $this->config->item ('medical_img_url').$postData['yp_id'];
                          //upload big image
                          $upload_data       = uploadImage ($filename, $file_view,'/' . $this->viewname.'/index/'.$postData['yp_id']);


                          //upload small image
                          $insertImagesData = array();
                          if(!empty($upload_data))
                          {
                            foreach ($upload_data as $imageFiles) {
                               if (!is_dir($this->config->item('medical_base_small_url'))) {                                        
                                    mkdir($this->config->item('medical_base_small_url'), 0777, TRUE);
                                }
                                
                                if (!is_dir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'])) {                                        
                                    mkdir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'], 0777, TRUE);
                                }
                                /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                if($imageFiles['is_image'])
                                    $a = do_resize ($this->config->item ('medical_img_url') . $postData['yp_id'], $this->config->item ('medical_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                array_push($insertImagesData,$imageFiles['file_name']);
                                if(!empty($insertImagesData))
                                {
                                  $images = implode(',',$insertImagesData);
                                }
                            }
                            $data[$row['name']] = !empty($images)?$images:'';
                          }
                        }
                    }
                    else{
                        if ($row['type'] != 'button') {
                            if ($row['type'] == 'date') {
                                $data[$row['name']] = dateformat($postData[$row['name']]);
                            } elseif ($row['subtype'] == 'time') {
                                $data[$row['name']] = dbtimeformat($postData[$row['name']]);
                            } else if (!empty($postData[$row['name']])) {
                                if ($row['type'] == 'checkbox-group') {
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
       }

      if(!empty($data))
      {
        $data['yp_id'] = $postData['yp_id'];
        $data['created_date'] = datetimeformat();
        $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
        $data['appointment'] = '0';
        $data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
        $insert_id = $this->common_model->insert(MEDICAL_COMMUNICATION, $data);
        //Insert log activity
        $activity = array(
          'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
          'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
          'module_name'         => MEDS_MEDS_MC_MODULE,
          'module_field_name'   => '',
          'type'                => 1
        );
        log_activity($activity);
        redirect('/' . $this->viewname .'/save_mc/'.$insert_id.'/'.$postData['yp_id']);
        
      }
      else
      {
        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>Please  insert medical communication details.</div>");
        redirect('/' . $this->viewname .'/add_mc/'.$postData['yp_id']);
      }
   }
   /*
      @Author : Niral Patel
      @Desc   : save data mc
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */
      public function save_mc($id,$ypid) {
          //get daily observation data
          if(is_numeric($id) && is_numeric($ypid)){
               
          
          $data = array(
          'header_data' => 'MEDICAL COMMUNICATION Updated',
          'detail' =>'The consents part of the Medical Information is now updated. Please check your editing carefully.',
          );
          //get yp details
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($ypid,$fields);

          //get mc form
          $match = array('mc_form_id'=> 1);
          $formsdata = $this->common_model->get_records(MC_FORM,array("form_json_data"), '', '', $match);
          if(!empty($formsdata))
          {
              $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
          }
          //get mc details
          $match = array('communication_id'=> $id,'yp_id'=>$ypid);
          $fields = array("*");
          $data['edit_data'] = $this->common_model->get_records(MEDICAL_COMMUNICATION, $fields, '', '', $match);
          if(empty($data['edit_data']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson');
          } 
          $data['yp_id'] = !empty($edit_data[0]['yp_id'])?$edit_data[0]['yp_id']:'';
          $data['header'] = array('menu_module' => 'YoungPerson');
          $data['main_content'] = '/save_mc';
          $this->parser->parse('layouts/DefaultTemplate', $data);
    }else{
              show_404 ();
      }}
    /*
      @Author : Niral Patel
      @Desc   : view medication data
      @Input  :
      @Output :
      @Date   : 21/07/2017
    */
    public function medication($ypid) {
        if(is_numeric($ypid)){
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
            $this->session->unset_userdata('medication_session_data');
        }
        $searchsort_session = $this->session->userdata('medication_session_data');
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
                $sortfield = 'medication_id';
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
        $config['base_url'] = base_url() . $this->viewname . '/medication/'.$ypid;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        //Query

        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = MEDICATION . ' as mc';
        $where = array("mc.yp_id"=>$ypid);
        $fields = array("mc.*");
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, '', '', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
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

        $this->session->set_userdata('medication_session_data', $sortsearchpage_data);
        setActiveSession('medication_session_data'); // set current Session active
        $data['header'] = array('menu_module' => 'Communication');
        //get communication form
        $match = array('m_form_id'=> 1);
        $formsdata = $this->common_model->get_records(M_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
        $data['header'] = array('menu_module' => 'YoungPerson');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/medication_ajaxlist', $data);
        } else {
            $data['main_content'] = '/medication';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
       }else{
              show_404 ();
       } 
    }
    
    /*
      @Author : Niral Patel
      @Desc   : medication list data
      @Input  :
      @Output :
      @Date   : 21/07/2017
    */
    public function medication_list($ypid) {
        if(is_numeric($ypid)){
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
            $this->session->unset_userdata('medication_session_data');
        }

        $searchsort_session = $this->session->userdata('medication_session_data');
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
                $sortfield = 'medication_id';
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
        $config['base_url'] = base_url() . $this->viewname . '/medication/'.$ypid;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        //Query

        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = MEDICATION . ' as mc';
        $where = array("mc.yp_id"=>$ypid);
        $fields = array("mc.*");
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, '', '', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
        }
        
        $data['ypid'] = $ypid;
        $data['redirect_flag'] = 1;
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

        $this->session->set_userdata('medication_session_data', $sortsearchpage_data);
        setActiveSession('medication_session_data'); // set current Session active
        $data['header'] = array('menu_module' => 'Communication');
        //get communication form
        $match = array('m_form_id'=> 1);
        $formsdata = $this->common_model->get_records(M_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
        $data['header'] = array('menu_module' => 'YoungPerson');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/medication_ajaxlist', $data);
        } else {
            $data['main_content'] = '/medication_list';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
       }else{
              show_404 ();
        } 
    }

/*
      @Author : Ritesh Rana
      @Desc   : edit medication page
      @Input  :
      @Output :
      @Date   : 13/10/2017
     */
    public function edit_medication($medication_id,$id,$redirect_flag) {
	  if(is_numeric($id) && is_numeric($medication_id))
      {
        //get pp form
        $match = array('m_form_id'=> 1);
        $m_forms = $this->common_model->get_records(M_FORM,array("form_json_data"), '', '', $match);
        if(!empty($m_forms))
        {
            $data['m_form_data'] = json_decode($m_forms[0]['form_json_data'], TRUE);
        }
        //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($id,$fields);
        //get medication yp data
        $match = array('yp_id'=> $id,'medication_id' => $medication_id);
        $data['edit_data'] = $this->common_model->get_records(MEDICATION,'', '', '', $match);
        $data['redirect_flag'] = $redirect_flag;

        $url_data =  base_url('Medical/edit_medication/'.$medication_id.'/'.$id);
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
            $data['ypid'] = $id;
            $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
            $data['crnt_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/edit_medication';
            $this->parser->parse('layouts/DefaultTemplate', $data);
          }
          else
          {
            $msg = $this->lang->line('check_me_user_update_data');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('/' . $this->viewname .'/medication/'. $id);
          }
        }else{
          $data['ypid'] = $id;
          $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
          $data['crnt_view'] = $this->viewname;
          $data['header'] = array('menu_module' => 'YoungPerson');
          $data['main_content'] = '/edit_medication';
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
      @Desc   : edit administer medication
      @Input  :
      @Output :
      @Date   : 25/06/2018
     */

    public function edit_administer_medication($administer_medication_id,$id,$redirect_flag='') {
      if(is_numeric($id) && is_numeric($administer_medication_id))
      {
        //get pp form
        $match = array('am_form_id'=> 1);
        $m_forms = $this->common_model->get_records(AM_FORM,array("form_json_data"), '', '', $match);
        if(!empty($m_forms))
        {
            $data['form_data'] = json_decode($m_forms[0]['form_json_data'], TRUE);
        }
        //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($id,$fields);
        //get administer medication yp data
        $match = array('yp_id'=> $id,'administer_medication_id' => $administer_medication_id);
        $data['edit_data'] = $this->common_model->get_records(ADMINISTER_MEDICATION,'', '', '', $match);    

        $url_data =  base_url('Medical/edit_administer_medication/'.$administer_medication_id.'/'.$id);
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
            $data['ypid'] = $id;
            $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
            $data['crnt_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/edit_administer_medication';
            $this->parser->parse('layouts/DefaultTemplate', $data);
          }
          else
          {
            $msg = $this->lang->line('check_me_user_update_data');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('/' . $this->viewname .'/medication/'. $id);
          }
        }else{
          $data['redirect_flag'] = $redirect_flag;
          $data['ypid'] = $id;
          $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
          $data['crnt_view'] = $this->viewname;
          $data['header'] = array('menu_module' => 'YoungPerson');
          $data['main_content'] = '/edit_administer_medication';
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
      @Desc   : Insert medication data
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */
    public function insert_medication()
    {
        $postData = $this->input->post();
		/*ghelani nikunj
    		25/9/2018
    		care to care archive need get data from ypid
		*/
		$fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data_yp_detail['YP_details'] = YpDetails($postData['yp_id'],$fields);
        unset($postData['submit_medform']);
        //get pp form
       $match = array('m_form_id'=> 1);
       $form_data = $this->common_model->get_records(M_FORM,array("form_json_data"), '', '', $match);
       if(!empty($form_data))
       {
            $pp_form_data = json_decode($form_data[0]['form_json_data'], TRUE);
            $data = array();
            foreach ($pp_form_data as $row) {
                if(isset($row['name']))
                {
                    if($row['type'] == 'file')
                    { 
                      $filename = $row['name'];
                      //get image previous image
                      $match = array('yp_id'=> $postData['yp_id']);
                      $pp_yp_data = $this->common_model->get_records(MEDICATION,array('`'.$row['name'].'`'), '', '', $match);
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

                                if (file_exists ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img)) { 
                                    unlink ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img);
                                }
                                if (file_exists ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img)) {
                                    unlink ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img);
                                }
                              } 
                          }
                      }
                    if(!empty($_FILES[$filename]['name'][0]))                     
                      {
                          //create dir and give permission
                          if (!is_dir($this->config->item('medical_base_url'))) {
                                  mkdir($this->config->item('medical_base_url'), 0777, TRUE);
                          }

                          if (!is_dir($this->config->item('medical_base_big_url'))) {                                
                              mkdir($this->config->item('medical_base_big_url'), 0777, TRUE);
                          }
                          
                          
                          if (!is_dir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'])) {
                              mkdir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'], 0777, TRUE);
                          }
                          $file_view = $this->config->item ('medical_img_url').$postData['yp_id'];
                          //upload big image
                          $upload_data = uploadImage($filename, $file_view,'/' . $this->viewname.'/index/'.$postData['yp_id']);
                          //upload small image
                          $insertImagesData = array();
                          if(!empty($upload_data))
                          {
                            foreach ($upload_data as $imageFiles) {
                               if (!is_dir($this->config->item('medical_base_small_url'))) {                                        
                                    mkdir($this->config->item('medical_base_small_url'), 0777, TRUE);
                                }
                                
                                if (!is_dir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'])) {                                        
                                    mkdir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'], 0777, TRUE);
                                }
                                /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                if($imageFiles['is_image'])
                                    $a = do_resize ($this->config->item ('medical_img_url') . $postData['yp_id'], $this->config->item ('medical_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                array_push($insertImagesData,$imageFiles['file_name']);
                                if(!empty($insertImagesData))
                                {
                                  $images = implode(',',$insertImagesData);
                                }
                            }
                            $data[$row['name']] = !empty($images)?$images:'';
                          }
                        }
                        
                    }
                    else{
                          if ($row['type'] != 'button') {
                            if (!empty($postData[$row['name']])) {
                                if($row['type'] == 'date'){
                                    $data[$row['name']] = dateformat($postData[$row['name']]);
                                } elseif($row['subtype'] == 'time'){
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
       }
        if(!empty($data))
        {
            if(!empty($postData['medication_id'])){
             $data['medication_id'] = $postData['medication_id'];
             $data['yp_id'] = $postData['yp_id'];
             $data['modified_date'] = datetimeformat();
             $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
			 $data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
             $this->common_model->update(MEDICATION,$data,array('medication_id'=>$postData['medication_id']));
             //get am data
            $match = array('select_medication' => $postData['medication_id']);
            $administer_medication_last_data = $this->common_model->get_records(ADMINISTER_MEDICATION,array('administer_medication_id,available_stock,quantity_left,record_medication_offered_but_refused'), '', '', $match,'','2','','administer_medication_id','desc');
            
                if(!empty($administer_medication_last_data))
                {
                    $amdata['available_stock'] = $postData['stock'];
                    $this->common_model->update(ADMINISTER_MEDICATION,$amdata,array('administer_medication_id'=>$administer_medication_last_data[0]['administer_medication_id']));
                    if(isset($administer_medication_last_data[1]['administer_medication_id']) && ($postData['stock'] != $administer_medication_last_data[1]['available_stock']) && $administer_medication_last_data[1]['record_medication_offered_but_refused'] == 'yes')
                    {
                        $amdata['available_stock'] = $postData['stock'];
                        $this->common_model->update(ADMINISTER_MEDICATION,$amdata,array('administer_medication_id'=>$administer_medication_last_data[1]['administer_medication_id']));
                    }
                }
           //Insert log activity
          $activity = array(
            'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
            'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
            'module_name'         => MEDS_MEDS_M_MODULE,
            'module_field_name'   => '',
            'type'                => 2
          );
          log_activity($activity);
          $redirect_flag = !empty($postData['redirect_flag'])?$postData['redirect_flag']:'0';
          redirect('/' . $this->viewname .'/save_medication/'.$postData['medication_id'].'/'.$postData['yp_id'].'/'.$redirect_flag);
        
        }else{
          $data['yp_id'] = $postData['yp_id'];
          $data['total_stock']   = isset($postData['stock'])?$postData['stock']:'';
          $data['created_date'] = datetimeformat();
          $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
		  $data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
           $insert_id = $this->common_model->insert(MEDICATION, $data);
          //insert medication stock to new care home id
          $medicationData['medication_id'] = $insert_id;
          $medicationData['care_home_id']  = $postData['care_home_id'];
          $medical_care_home_id = $this->common_model->insert(MEDICAL_CARE_HOME_TRANSECTION, $medicationData);
           //Insert log activity
          $activity = array(
            'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
            'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
            'module_name'         => MEDS_MEDS_M_MODULE,
            'module_field_name'   => '',
            'type'                => 1
          );
          log_activity($activity);
          redirect('/' . $this->viewname .'/medication_list/'.$postData['yp_id']);
        }
    }else{
          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>Please  insert medication details.</div>");
          redirect('/' . $this->viewname .'/medication/'.$postData['yp_id']);
        }
   }
   /*
      @Author : Niral Patel
      @Desc   : save data medication
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */
      public function save_medication($id,$ypid,$redirect_flag) {
          if(is_numeric($id) && is_numeric($ypid)){
           //get daily observation data
          $data = array(
          'header_data' => 'MEDICATION Updated',
          'detail' =>'The consents part of the Medical Information is now updated. Please check your editing carefully.',
          );
           //get yp details
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($ypid,$fields);
          //get medication form
          $match = array('m_form_id'=> 1);
          $formsdata = $this->common_model->get_records(M_FORM,array("form_json_data"), '', '', $match);
          if(!empty($formsdata))
          {
              $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
          }
          //get medication details
          $match = array('medication_id'=> $id,'yp_id'=>$ypid);
          $fields = array("*");
          $data['edit_data'] = $this->common_model->get_records(MEDICATION, $fields, '', '', $match);
          if(empty($data['edit_data']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$ypid);
          } 
          $data['redirect_flag'] = $redirect_flag;
          $data['yp_id'] = !empty($edit_data[0]['yp_id'])?$edit_data[0]['yp_id']:'';
          $data['header'] = array('menu_module' => 'YoungPerson');
          $data['main_content'] = '/save_medication';
          $this->parser->parse('layouts/DefaultTemplate', $data);
          }else{
              show_404 ();
          }
    }
     /*
      @Author : Niral Patel
      @Desc   : view /add administer medication data
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */
    public function log_administer_medication($ypid) {
        $medication_name = $this->input->post('professional_name');
        $search_date = $this->input->post('search_date');
        $search_time = $this->input->post('search_time');

		if(is_numeric($ypid)){
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
            $this->session->unset_userdata('administer_medication_session_data');
        }
        $searchsort_session_newdata = $this->session->userdata('administer_medication_session_data');
		//Sorting
        if (!empty($sortfield) && !empty($sortby)) {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        } else {
            if (!empty($searchsort_session_newdata['sortfield'])) {
				$data['sortfield'] = $searchsort_session_newdata['sortfield'];
                $data['sortby'] = $searchsort_session_newdata['sortby'];
                $sortfield = $searchsort_session_newdata['sortfield'];
                $sortby = $searchsort_session_newdata['sortby'];
            } else {
                $sortfield = 'date_given,time_given';
                $sortby = 'desc';
                $data['sortfield'] = $sortfield;
                $data['sortby'] = $sortby;
            }
        }
		 
        //Search text
        if (!empty($searchtext)) {
            $data['searchtext'] = $searchtext;
        } else {
            if (empty($allflag) && !empty($searchsort_session_newdata['searchtext'])) {
                $data['searchtext'] = $searchsort_session_newdata['searchtext'];
                $searchtext = $data['searchtext'];
            } else {
                $data['searchtext'] = '';
            }
        }

        if (!empty($perpage) && $perpage != 'null') {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        } else {
            if (!empty($searchsort_session_newdata['perpage'])) {
                $data['perpage'] = trim($searchsort_session_newdata['perpage']);
                $config['per_page'] = trim($searchsort_session_newdata['perpage']);
            } else {
                $config['per_page'] = '10';
                $data['perpage'] = '10';
            }
        }
        //pagination configuration
        $config['first_link'] = 'First';
        $config['base_url'] = base_url() . $this->viewname . '/log_administer_medication/'.$ypid;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        //Query
        $match = array('yp_id'=> $ypid);
        $medication_data = $this->common_model->get_records(MEDICATION,'', '', '', $match);

        $data['stock'] = '';
        if(!empty($medication_data)){
         $data['stock']= $medication_data[0]['stock'];
        }
        $data['medication_data'] = $medication_data;
        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = ADMINISTER_MEDICATION . ' as mc';
        //$where = array("mc.yp_id"=>$ypid);
		$whereCond = 'mc.yp_id = '.$ypid.' ';
		
		if (!empty($medication_name)) {
                $whereCond .= ' AND mc.select_medication = ' . $medication_name;
        }

        if (!empty($search_date)) {
                $search_date = dateformat($search_date);
                $whereCond .= ' AND mc.date_given = "' . $search_date . '"';
        }
        if (!empty($search_time)) {
                $search_time = dbtimeformat($search_time);
                $whereCond .= ' AND mc.time_given = "' . $search_time . '"';
        }

		if(isset($medication_data[0]['stock']) && !empty($medication_data[0]['stock'])){
          $fields = array("mc.*, md.stock,mc.date_given as date_given,time_given as time_given");
        }else{
          $fields = array("mc.*,mc.date_given as date_given,time_given as time_given");
        }

        $join_tables = array(MEDICATION . ' as md' => 'md.medication_id=mc.select_medication');
        if (!empty($searchtext)) {
            
        } else {
				
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $whereCond);
			$config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $whereCond, '', '', '1');
        }
        $data['ypid'] = $ypid;
        $data['redirect_flag'] = 1;
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

        $this->session->set_userdata('administer_medication_session_data', $sortsearchpage_data);
        setActiveSession('administer_medication_session_data'); // set current Session active
        $data['header'] = array('menu_module' => 'Communication');
        //get communication form
        $match = array('am_form_id'=> 1);
        $formsdata = $this->common_model->get_records(AM_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
        $data['header'] = array('menu_module' => 'YoungPerson');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/log_administer_ajaxlist', $data);
        } else {
            $data['main_content'] = '/log_administer_medication';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
    }else{
            show_404 ();
         }    
    }
    /*
      @Author : Niral Patel
      @Desc   : view /add administer medication data
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */
    public function administer_medication($ypid) {
        if(is_numeric($ypid)){
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
            $this->session->unset_userdata('administer_medication_session_data_meds');
        }
        $searchsort_session = $this->session->userdata('administer_medication_session_data_meds');
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
                $sortfield = 'date_given,time_given';
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
        $config['base_url'] = base_url() . $this->viewname . '/administer_medication/'.$ypid;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        //Query
        $match = array('yp_id'=> $ypid);
        $medication_data = $this->common_model->get_records(MEDICATION, array('*') , '', '', $match);
        
        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = ADMINISTER_MEDICATION . ' as mc';
        $where = array("mc.yp_id"=>$ypid);
        if(isset($medication_data[0]['stock']) && !empty($medication_data[0]['stock'])){
          $fields = array("mc.*, md.stock,mc.date_given as date_given,time_given as time_given");
      
        $data['stock']= $medication_data[0]['stock']; //remove from bottom and paste here, (Mantis issue # 7460 , Ritesh Rana, Dt: 8th Dec 2017 )
        }else{
            $fields = array("mc.*,mc.date_given as date_given,time_given as time_given");
        }

        $join_tables = array(MEDICATION . ' as md' => 'md.medication_id=mc.select_medication');
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            //pr $this->db->last_query();exit;
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
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

        $this->session->set_userdata('administer_medication_session_data_meds', $sortsearchpage_data);
        setActiveSession('administer_medication_session_data_meds'); // set current Session active
        $data['header'] = array('menu_module' => 'Communication');

        //get communication form
        $match = array('am_form_id'=> 1);
        $formsdata = $this->common_model->get_records(AM_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
        $data['header'] = array('menu_module' => 'YoungPerson');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/administer_ajaxlist', $data);
        } else {
            $data['main_content'] = '/administer_medication';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
      }else{
              show_404 ();
          }  
    }

    /*
      @Author : Niral Patel
      @Desc   : Insert medication data
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */
    public function insert_administer_medication()
    {
         $postData = $this->input->post ();
		 /*ghelani nikunj
		25/9/2018
		care to care archive need get data from ypid
		*/
		$fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data_yp_detail['YP_details'] = YpDetails($postData['yp_id'],$fields);
         $medication_id = $postData['select_medication'];
         $administer_medication_id = $postData['administer_medication_id'];
         $yp_id = $postData['yp_id'];
         $match = array('yp_id'=> $yp_id,'medication_id' => $medication_id);
         $medication_data = $this->common_model->get_records(MEDICATION,'', '', '', $match);
         //
         if(!empty($postData['administer_medication_id'])){ 
          //get pp yp data
            $match = array('yp_id'=> $yp_id,'administer_medication_id' => $postData['administer_medication_id']);
            $administer_medication_data = $this->common_model->get_records(ADMINISTER_MEDICATION,'', '', '', $match);
            if($administer_medication_data[0]['quantity_left'] != $postData['quantity_left'])
            { 
                //get am data
                $match = array('administer_medication_id >=' => $administer_medication_id,'select_medication' => $medication_id);
                $administer_medication_last_data = $this->common_model->get_records(ADMINISTER_MEDICATION,array('administer_medication_id,available_stock,quantity_left'), '', '', $match,'','','','administer_medication_id','desc');
               
                 if(isset($postData['record_medication_offered_but_refused'][0]) &&  $postData['record_medication_offered_but_refused'][0] == "yes")
                  { 
                    if(!empty($administer_medication_data[0]['quantity_left']))
                      {
                        $quntity_given =  $administer_medication_data[0]['quantity_left'];
                        if(!empty($administer_medication_last_data))
                        {
                          foreach ($administer_medication_last_data as $row) {
                              $amdata['available_stock'] = $row['available_stock'] + $quntity_given;
                              $amdata['available_stock'] = number_format($amdata['available_stock'],2);
                              $amdata['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
                              $this->common_model->update(ADMINISTER_MEDICATION,$amdata,array('administer_medication_id'=>$row['administer_medication_id']));
                          }
                        }
                        
                             $total_stock = $medication_data[0]['stock'] + $quntity_given;
                              $data['stock'] = number_format($total_stock,2);
                              $data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
                             $this->common_model->update(MEDICATION,$data,array('medication_id'=>$medication_id));
                      }
                    
                  }
                  else
                  { 
                     
                     if(!empty($administer_medication_data[0]['quantity_left']) && $administer_medication_data[0]['record_medication_offered_but_refused'] != 'yes')
                      {
                        
                          if($administer_medication_data[0]['quantity_left'] > $postData['quantity_left'])
                          { 
                            if(!empty($administer_medication_last_data))
                            {
                              $quntitygive = $administer_medication_data[0]['quantity_left'] - $postData['quantity_left']; 
                              foreach ($administer_medication_last_data as $row) {
                                  $amdata['available_stock'] = $row['available_stock'] + $quntitygive;
                                  $amdata['available_stock'] = number_format($amdata['available_stock'],2);
								  $amdata['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
                                  $this->common_model->update(ADMINISTER_MEDICATION,$amdata,array('administer_medication_id'=>$row['administer_medication_id']));
                              }
                            }
                          }
                          else
                          {
                            if(!empty($administer_medication_last_data))
                            {
                             $quntitygive = $postData['quantity_left'] -$administer_medication_data[0]['quantity_left'];
                              foreach ($administer_medication_last_data as $row) {
                                  $amdata['available_stock'] = $row['available_stock'] - $quntitygive;
                                  $amdata['available_stock'] = number_format($amdata['available_stock'],2);
								  $amdata['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
                                  $this->common_model->update(ADMINISTER_MEDICATION,$amdata,array('administer_medication_id'=>$row['administer_medication_id']));
                              }
                            }
                          }
                           $total_stock = ($medication_data[0]['stock'] + $administer_medication_data[0]['quantity_left']);
                           $stock = $total_stock - $postData['quantity_left'];
                           $data['stock'] = number_format($stock,2);
                           $data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
                           $this->common_model->update(MEDICATION,$data,array('medication_id'=>$medication_id));
                      }
                      else
                      {
                         if(!empty($postData['quantity_left']) && $administer_medication_data[0]['record_medication_offered_but_refused'] == 'yes' && !isset($postData['record_medication_offered_but_refused']))
                          {
                            $quntity_given =  $postData['quantity_left'];
                              if(!empty($administer_medication_last_data))
                              {
                                foreach ($administer_medication_last_data as $row) {
                                    $amdata['available_stock'] = $row['available_stock'] - $quntity_given;
                                    $amdata['available_stock'] = number_format($amdata['available_stock'],2);
                                    $amdata['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
                                    $this->common_model->update(ADMINISTER_MEDICATION,$amdata,array('administer_medication_id'=>$row['administer_medication_id']));
                                }
                              }
                              
                                   $total_stock = $medication_data[0]['stock'] - $quntity_given;
                                   $data['stock'] = number_format($total_stock,2);
								   $data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
                                   $this->common_model->update(MEDICATION,$data,array('medication_id'=>$medication_id));
                          }
                      }
                  }
            }
         }
         else
         {
            if(empty($postData['record_medication_offered_but_refused'][0]) &&  $postData['record_medication_offered_but_refused'][0] != "yes"){
                if(!empty($postData['quantity_left']) && isset($postData['quantity_left'])){
                 if(!empty($medication_data[0]['stock']) && isset($medication_data[0]['stock'])){
                     $total_stock = $medication_data[0]['stock'] - $postData['quantity_left'];
                     $data['stock'] = number_format($total_stock,2);
					 $data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
                     $this->common_model->update(MEDICATION,$data,array('medication_id'=>$medication_id));
                 } 
                 }
             
            }
        }
        unset($postData['submit_medform']);

        //get pp form
       $match = array('am_form_id'=> 1);
       $form_data = $this->common_model->get_records(AM_FORM,array("form_json_data"), '', '', $match);
       if(!empty($form_data))
       {
            $pp_form_data = json_decode($form_data[0]['form_json_data'], TRUE);
            $data = array();
            foreach ($pp_form_data as $row) {
                if(isset($row['name']))
                {
                    if($row['type'] == 'file')
                    { 
                      $filename = $row['name'];
                      //get image previous image
                      $match = array('yp_id'=> $postData['yp_id']);
                      $pp_yp_data = $this->common_model->get_records(ADMINISTER_MEDICATION,array('`'.$row['name'].'`'), '', '', $match);
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

                                if (file_exists ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img)) { 
                                    unlink ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img);
                                }
                                if (file_exists ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img)) {
                                    unlink ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img);
                                }
                              } 
                          }
                      }
                     
                      if(!empty($_FILES[$filename]['name'][0]))                     
                      {
                          //create dir and give permission
                          if (!is_dir($this->config->item('medical_base_url'))) {
                                  mkdir($this->config->item('medical_base_url'), 0777, TRUE);
                          }
                          if (!is_dir($this->config->item('medical_base_big_url'))) {                                
                              mkdir($this->config->item('medical_base_big_url'), 0777, TRUE);
                          }
                          if (!is_dir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'])) {
                              mkdir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'], 0777, TRUE);
                          }
                          $file_view = $this->config->item ('medical_img_url').$postData['yp_id'];
                          //upload big image
                          $upload_data       = uploadImage ($filename, $file_view,'/' . $this->viewname.'/index/'.$postData['yp_id']);
                          //upload small image
                          $insertImagesData = array();
                          if(!empty($upload_data))
                          {
                            foreach ($upload_data as $imageFiles) {
                                if (!is_dir($this->config->item('medical_base_small_url'))) {                                        
                                    mkdir($this->config->item('medical_base_small_url'), 0777, TRUE);
                                }
                                
                                if (!is_dir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'])) {                                        
                                    mkdir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'], 0777, TRUE);
                                }
                                /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                if($imageFiles['is_image'])
                                    $a = do_resize ($this->config->item ('medical_img_url') . $postData['yp_id'], $this->config->item ('medical_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                array_push($insertImagesData,$imageFiles['file_name']);
                                if(!empty($insertImagesData))
                                {
                                  $images = implode(',',$insertImagesData);
                                }
                            }
                            $data[$row['name']] = !empty($images)?$images:'';
                          }
                        }
                        
                    }
                    else{
                        if ($row['type'] != 'button') {
                            if (isset($postData[$row['name']])) {
                                if($row['type'] == 'date'){
                                    $data[$row['name']] = dateformat($postData[$row['name']]);                                    
                                } elseif($row['subtype'] == 'time'){
                                    $data[$row['name']] = dbtimeformat($postData[$row['name']]);
                                } else if ($row['type'] == 'checkbox-group') {
                                    $data[$row['name']] = isset($postData[$row['name']]) ? implode(',', $postData[$row['name']]) : '';
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
       }
        
      if(!empty($data))
      {
        
        if(!empty($postData['administer_medication_id'])){
          $administer_medication_id = $postData['administer_medication_id'];
          $yp_id = $postData['yp_id'];
          $match = array('yp_id'=> $yp_id,'medication_id' => $medication_id);
          $medication_data = $this->common_model->get_records(MEDICATION,'', '', '', $match);
          
          $data['yp_id'] = $postData['yp_id'];
          $data['quantity_left'] = !isset($postData['record_medication_offered_but_refused'])?number_format($postData['quantity_left'],2):0;
  
          $data['record_medication_offered_but_refused'] = isset($postData['record_medication_offered_but_refused'])?$postData['record_medication_offered_but_refused'][0]:null;
          //$data['available_stock'] = $medication_data[0]['stock'];
          
          $data['modified_date'] = datetimeformat();
          $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
          $data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
          $this->common_model->update(ADMINISTER_MEDICATION, $data,array('administer_medication_id'=>$postData['administer_medication_id']));
          
          //Insert log activity
          $activity = array(
            'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
            'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
            'module_name'         => MEDS_MEDS_AM_MODULE,
            'module_field_name'   => '',
            'type'                => 2
          );
          log_activity($activity);
          $redirect_flag = !empty($postData['redirect_flag'])?$postData['redirect_flag']:'0';
          redirect('/' . $this->viewname .'/save_administer_medication/'.$postData['administer_medication_id'].'/'.$postData['yp_id'].'/'.$redirect_flag);
        }
        else
        {
         
        $medication_id = $postData['select_medication'];
        $yp_id = $postData['yp_id'];
        $match = array('yp_id'=> $yp_id,'medication_id' => $medication_id);
        $medication_data = $this->common_model->get_records(MEDICATION,'', '', '', $match);
        
        $data['yp_id'] = $postData['yp_id'];
        $data['quantity_left'] = number_format($data['quantity_left'],2);
        $data['available_stock'] = $medication_data[0]['stock'];
        $data['available_stock'] = number_format($data['available_stock'],2);
        $data['created_date'] = datetimeformat();
        $data['modified_date'] = datetimeformat();
        $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
		$data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
        $insert_id = $this->common_model->insert(ADMINISTER_MEDICATION, $data);
        
        //Insert log activity
        $activity = array(
          'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
          'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
          'module_name'         => MEDS_MEDS_AM_MODULE,
          'module_field_name'   => '',
          'type'                => 1
        );
        log_activity($activity);
        $redirect_flag = !empty($postData['redirect_flag'])?$postData['redirect_flag']:'0';
        redirect('/' . $this->viewname .'/save_administer_medication/'.$insert_id.'/'.$postData['yp_id'].'/'.$redirect_flag);
        }
      }
      else
      {
        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>Please  insert administer medication details.</div>");
        redirect('/' . $this->viewname .'/administer_medication/'.$postData['yp_id']);
      }
   }
   /*
     @Author : Niral Patel
     @Desc   : administer_medication
     @Input     : Post id from List page
     @Output    : Delete data from database and redirect
     @Date   : 25/06/2018
     */

    public function delete_administer_medication($administer_medication_id,$medication_id,$yp_id,$redirect_flag) {
            //Delete Record From Database
            if (!empty($administer_medication_id)) {
                    //get am data
                    $match = array('administer_medication_id' => $administer_medication_id);
                    $administer_medication_data = $this->common_model->get_records(ADMINISTER_MEDICATION,'', '', '', $match);
                    //get medication yp data
                    $match = array('medication_id' => $medication_id);
                    $medication_data = $this->common_model->get_records(MEDICATION,'', '', '', $match);

                    //get am data
                    $match = array('administer_medication_id >' => $administer_medication_id,'select_medication' => $medication_id);
                    $administer_medication_last_data = $this->common_model->get_records(ADMINISTER_MEDICATION,array('administer_medication_id,available_stock'), '', '', $match,'','','','administer_medication_id','desc');
                    
                    $data['stock'] = $medication_data[0]['stock']+$administer_medication_data[0]['quantity_left'];
                    $this->common_model->update(MEDICATION,$data,array('medication_id'=>$medication_id));
                    
                    //delete
                    $where = array('administer_medication_id' => $administer_medication_id);
                    $success = $this->common_model->delete(ADMINISTER_MEDICATION,$where);
                    if ($success) {
                    
                    if(!empty($administer_medication_last_data))
                    {
                      foreach ($administer_medication_last_data as $row) {
                        $amdata['available_stock'] = $row['available_stock']+$administer_medication_data[0]['quantity_left'];
                        $this->common_model->update(ADMINISTER_MEDICATION,$amdata,array('administer_medication_id'=>$row['administer_medication_id']));
                      }
                    }
                    //get am data
                    $match = array('select_medication' => $medication_id);
                    $administer_medication_last_record = $this->common_model->get_records(ADMINISTER_MEDICATION,array('administer_medication_id,available_stock,quantity_left,record_medication_offered_but_refused'), '', '', $match,'','2','','administer_medication_id','desc');
                    
                    if(!empty($administer_medication_last_record))
                    {
                        $amdata['available_stock'] = $data['stock'];
                        $this->common_model->update(ADMINISTER_MEDICATION,$amdata,array('administer_medication_id'=>$administer_medication_last_record[0]['administer_medication_id']));
                        if(isset($administer_medication_last_record[1]['administer_medication_id']) && ($data['stock'] != $administer_medication_last_record[1]['available_stock']) && $administer_medication_last_record[1]['record_medication_offered_but_refused'] == 'yes')
                        {
                            $amdata['available_stock'] = $data['stock'];
                            $this->common_model->update(ADMINISTER_MEDICATION,$amdata,array('administer_medication_id'=>$administer_medication_last_record[1]['administer_medication_id']));
                        }
                    }
                    //Insert log activity
                    $activity = array(
                      'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
                      'module_name'         => MEDS_MEDS_AM_MODULE,
                      'module_field_name'   => '',
                      'yp_id' => !empty($yp_id) ? $yp_id : '',
                      'type'                => 3
                    );
                    
                    log_activity($activity);
                    $msg = 'Administration Record Deleted successfully';
                    $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                    unset($id);
                    
                } else {
                    // error
                    $msg = $this->lang->line('error_msg');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                    
                }
            }
        if($redirect_flag == 1)
        {
          redirect('Medical/log_administer_medication/'.$yp_id);  
        }
        else
        {
          redirect('Medical/administer_medication/'.$yp_id);  
        }
        
    }
   /*
      @Author : Niral Patel
      @Desc   : save data medication
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */
      public function save_administer_medication($id,$ypid,$redirect_flag) {
        if(is_numeric($id) && is_numeric($ypid)){
          //get daily observation data
          $data = array(
          'header' => 'ADMINISTER MEDICATION Updated',
          'detail' =>'The consents part of the Medical Information is now updated. Please check your editing carefully.',
          );
          //get yp details
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($ypid,$fields);
          //get medication form
          $match = array('am_form_id'=> 1);
          $formsdata = $this->common_model->get_records(AM_FORM,array("form_json_data"), '', '', $match);
          if(!empty($formsdata))
          {
              $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
          }
          //get medication details
          $match = array('administer_medication_id'=> $id,'yp_id'=>$ypid);
          $fields = array("*");
          $data['edit_data'] = $this->common_model->get_records(ADMINISTER_MEDICATION, $fields, '', '', $match);
          if(empty($data['edit_data']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson');
          } 
          $data['redirect_flag'] = $redirect_flag;
          $data['yp_id'] = !empty($edit_data[0]['yp_id'])?$edit_data[0]['yp_id']:'';
          $data['header'] = array('menu_module' => 'YoungPerson');
          $data['main_content'] = '/save_administer_medication';
          $this->parser->parse('layouts/DefaultTemplate', $data);
          }else{
               show_404 ();
          }
    }
    /*
      @Author : Ritesh Rana
      @Desc   : view /add Health Assessment data
      @Input  :
      @Output :
      @Date   : 22/08/2017
    */
    public function healthAssessment($ypid) {
        if(is_numeric($ypid)){
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
            $this->session->unset_userdata('health_assessment_session_data');
        }

        $searchsort_session = $this->session->userdata('health_assessment_session_data');
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
                $sortfield = 'health_assessment_id';
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
        $config['base_url'] = base_url() . $this->viewname . '/administer_medication/'.$ypid;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        //Query
        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = HEALTH_ASSESSMENT . ' as mc';
        $where = array("mc.yp_id"=>$ypid);
        $fields = array("mc.*,CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= mc.created_by');
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
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

        $this->session->set_userdata('health_assessment_session_data', $sortsearchpage_data);
        setActiveSession('health_assessment_session_data'); // set current Session active
        //get communication form
        $match = array('ha_form_id'=> 1);
        $formsdata = $this->common_model->get_records(HA_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
        $data['header'] = array('menu_module' => 'YoungPerson');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/health_assessment_ajaxlist', $data);
        } else {
            $data['main_content'] = '/health_assessment';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
      }else{
              show_404 ();
          }  
    }

/*
      @Author : Niral Patel
      @Desc   : Insert medication data
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */
    public function insert_health_assessment()
    {
        $postData = $this->input->post();
		/*ghelani nikunj
		25/9/2018
		care to care archive need get data from ypid
		*/
		$fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data_yp_detail['YP_details'] = YpDetails($postData['yp_id'],$fields);
        unset($postData['submit_medform']);
        //get pp form
       $match = array('ha_form_id'=> 1);
       $form_data = $this->common_model->get_records(HA_FORM,array("form_json_data"), '', '', $match);
       if(!empty($form_data))
       {
            $pp_form_data = json_decode($form_data[0]['form_json_data'], TRUE);
            $data = array();
            foreach ($pp_form_data as $row) {
                if(isset($row['name']))
                {
                    if($row['type'] == 'file')
                    { 
                      $filename = $row['name'];
                      //get image previous image
                      $match = array('yp_id'=> $postData['yp_id']);
                      $pp_yp_data = $this->common_model->get_records(HEALTH_ASSESSMENT,array('`'.$row['name'].'`'), '', '', $match);
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

                                if (file_exists ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img)) { 
                                    unlink ($this->config->item ('medical_img_url') .$postData['yp_id'].'/'.$img);
                                }
                                if (file_exists ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img)) {
                                    unlink ($this->config->item ('medical_img_url_small') .$postData['yp_id'].'/'.$img);
                                }
                              } 
                          }
                      }
                     
                      if(!empty($_FILES[$filename]['name'][0]))                     
                      {
                          //create dir and give permission
                          if (!is_dir($this->config->item('medical_base_url'))) {
                                  mkdir($this->config->item('medical_base_url'), 0777, TRUE);
                          }

                          if (!is_dir($this->config->item('medical_base_big_url'))) {                                
                              mkdir($this->config->item('medical_base_big_url'), 0777, TRUE);
                          }
                          
                          
                          if (!is_dir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'])) {
                              mkdir($this->config->item('medical_base_big_url') . '/' . $postData['yp_id'], 0777, TRUE);
                          }
                          $file_view = $this->config->item ('medical_img_url').$postData['yp_id'];
                          //upload big image
                          $upload_data       = uploadImage ($filename, $file_view,'/' . $this->viewname.'/index/'.$postData['yp_id']);

                          //upload small image
                          $insertImagesData = array();
                          if(!empty($upload_data))
                          {
                            foreach ($upload_data as $imageFiles) {
                                if (!is_dir($this->config->item('medical_base_small_url'))) {                                        
                                    mkdir($this->config->item('medical_base_small_url'), 0777, TRUE);
                                }
                                
                                if (!is_dir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'])) {                                        
                                    mkdir($this->config->item('medical_base_small_url') . '/' . $postData['yp_id'], 0777, TRUE);
                                }
                                /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                if($imageFiles['is_image'])
                                    $a = do_resize ($this->config->item ('medical_img_url') . $postData['yp_id'], $this->config->item ('medical_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                array_push($insertImagesData,$imageFiles['file_name']);
                                if(!empty($insertImagesData))
                                {
                                  $images = implode(',',$insertImagesData);
                                }
                            }
                            $data[$row['name']] = !empty($images)?$images:'';
                          }
                        }
                    }
                    else{
                        if ($row['type'] != 'button') {
                            if (!empty($postData[$row['name']])) {
                                if($row['type'] == 'date'){
                                    $data[$row['name']] = dateformat($postData[$row['name']]);                                    
                                } elseif($row['subtype'] == 'time'){
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
      }
      if(!empty($data))
      {
        $data['yp_id'] = $postData['yp_id'];
        $data['created_date'] = datetimeformat();
        $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
		$data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
        $insert_id = $this->common_model->insert(HEALTH_ASSESSMENT, $data);
        //Insert log activity
        $activity = array(
          'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
          'yp_id'               => !empty($postData['yp_id'])?$postData['yp_id']:'',
          'module_name'         => MEDS_MEDS_HA_MODULE,
          'module_field_name'   => '',
          'type'                => 1
        );
        log_activity($activity);
        redirect('/' . $this->viewname .'/save_health_assessment/'.$insert_id.'/'.$postData['yp_id']);
      }
      else
      {
        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>Please  insert administer medication details.</div>");
        redirect('/' . $this->viewname .'/healthAssessment/'.$postData['yp_id']);
      }
   }
    /*
      @Author : Ritesh Rana
      @Desc   : save data health assessment
      @Input  :
      @Output :
      @Date   : 22/08/2017
     */
      public function save_health_assessment($id,$ypid) {
        if(is_numeric($id) && is_numeric($ypid)){
          //get daily observation data
          $data = array(
          'header' => 'HEALTH ASSESSMENT Updated',
          'detail' =>'The consents part of the Medical Information is now updated. Please check your editing carefully.',
          );
          //get yp details
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($ypid,$fields);
           //get medication form
          $match = array('ha_form_id'=> 1);
          $formsdata = $this->common_model->get_records(HA_FORM,array("form_json_data"), '', '', $match);
          if(!empty($formsdata))
          {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
          }
          //get medication details
          $match = "health_assessment_id = " . $id;
          $fields = array("*");
          $data['edit_data'] = $this->common_model->get_records(HEALTH_ASSESSMENT, $fields, '', '', $match);
          if(empty($data['edit_data']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson');
          } 
          $data['yp_id'] = !empty($ypid)?$ypid:'';
          $data['header'] = array('menu_module' => 'YoungPerson');
          $data['main_content'] = '/save_health_assessment';
          $this->parser->parse('layouts/DefaultTemplate', $data);
          }else{
               show_404 ();
          }
    }

/*
      @Author : Ritesh Rana
      @Desc   : view health assessment
      @Input  :
      @Output :
      @Date   : 20/11/2017
     */


public function view_healthAssessment($health_assessment_id,$yp_id) {
    if(is_numeric($health_assessment_id) && is_numeric($yp_id))
    {
       //get HA form
       $match = array('ha_form_id'=> 1);
       $form_data = $this->common_model->get_records(HA_FORM,array("form_json_data"), '', '', $match);
       if(!empty($form_data))
       {
            $data['form_data'] = json_decode($form_data[0]['form_json_data'], TRUE);
       }
       // get ha comments data
          $table = HA_COMMENTS . ' as ha';
          $where = array("ha.health_assessment_id" => $health_assessment_id, "ha.yp_id" => $yp_id);
          $fields = array("ha.ha_comments,ha.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name");
          $join_tables = array(LOGIN . ' as l' => 'l.login_id= ha.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= ha.yp_id');
          $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
          // get ha recommendations data
          $table = HA_RECOMMENDATIONS . ' as hr';
          $where = array("hr.health_assessment_id" => $health_assessment_id, "hr.yp_id" => $yp_id);
          $fields = array("hr.ha_recommendations,hr.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
          $join_tables = array(LOGIN . ' as l' => 'l.login_id= hr.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= hr.yp_id');
          $data['recommendations'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
          //get health assessment yp data
          $match = "health_assessment_id = " . $health_assessment_id;
          $fields = array("*");
          $data['edit_data'] = $this->common_model->get_records(HEALTH_ASSESSMENT, $fields, '', '', $match);
          if(empty($data['edit_data']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson');
          } 
        //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($yp_id,$fields);
        if(empty($data['YP_details']) || empty($data['edit_data']))
        {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson');
        }
        $data['ypid'] = $yp_id;
        $data['health_assessment_id'] = $health_assessment_id;
        $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
        $data['crnt_view'] = $this->viewname;
        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['main_content'] = '/view_health_assessment';
        $this->parser->parse('layouts/DefaultTemplate', $data);
      }
      else
      {
          show_404 ();
      }
    }
/*
      @Author : Ritesh Rana
      @Desc   : health assessment comments
      @Input  :
      @Output :
      @Date   : 20/11/2017
     */
     public function add_health_assessment_commnts() {
        $main_user_data = $this->session->userdata('LOGGED_IN');
        $health_assessment_id = $this->input->post('health_assessment_id');
        $yp_id = $this->input->post('yp_id');
        $data = array(
                'ha_comments' => $this->input->post('ha_comments'),
                'yp_id' => $this->input->post('yp_id'),
                'health_assessment_id' => $this->input->post('health_assessment_id'),
                'created_date' => datetimeformat(),
                'created_by' => $main_user_data['ID'],
            );  
            //Insert Record in Database
            if ($this->common_model->insert(HA_COMMENTS, $data)) {
                $msg = $this->lang->line('comments_add_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            } else {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
         redirect('/' . $this->viewname . '/view_healthAssessment/' . $health_assessment_id . '/' . $yp_id);
    }
    /*
      @Author : Ritesh Rana
      @Desc   : health assessment comments
      @Input  :
      @Output :
      @Date   : 20/11/2017
     */
     public function add_recommendations() {
        $main_user_data = $this->session->userdata('LOGGED_IN');
        $health_assessment_id = $this->input->post('health_assessment_id');
        $yp_id = $this->input->post('yp_id');
        $data = array(
                'ha_recommendations' => $this->input->post('ha_recommendations'),
                'yp_id' => $this->input->post('yp_id'),
                'health_assessment_id' => $this->input->post('health_assessment_id'),
                'created_date' => datetimeformat(),
                'created_by' => $main_user_data['ID'],
            );  
            //Insert Record in Database
            if ($this->common_model->insert(HA_RECOMMENDATIONS, $data)) {
                $msg = $this->lang->line('recommendations_add_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            } else {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
         redirect('/' . $this->viewname . '/view_healthAssessment/' . $health_assessment_id . '/' . $yp_id);
    }

    /*
      @Author : Niral Patel
      @Desc   : view /add health assessment data
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */
    public function log_health_assessment($ypid) {
        if(is_numeric($ypid)){
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
            $this->session->unset_userdata('health_assessment_session_data');
        }
        $searchsort_session = $this->session->userdata('health_assessment_session_data');
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
                $sortfield = 'health_assessment_id';
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
        $config['base_url'] = base_url() . $this->viewname . '/log_health_assessment/'.$ypid;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        //Query

        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = HEALTH_ASSESSMENT . ' as mc';
        $where = array("mc.yp_id"=>$ypid);
        $fields = array("mc.*,CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id = mc.created_by');
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
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

        $this->session->set_userdata('health_assessment_session_data', $sortsearchpage_data);
        setActiveSession('health_assessment_session_data'); // set current Session active
        $data['header'] = array('menu_module' => 'Communication');

        //get communication form
        $match = array('ha_form_id'=> 1);
        $formsdata = $this->common_model->get_records(HA_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        $data['health_assessment'] = "health_assessment";
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
        $data['header'] = array('menu_module' => 'YoungPerson');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/health_assessment_ajaxlist', $data);
        } else {
            $data['main_content'] = '/log_health_assessment';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
    }else{
              show_404 ();
          }    
    }

    /*
      @Author : Niral Patel
      @Desc   : print health assessment data
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */
    public function DownloadPrint($yp_id,$section = NULL) {
        $data = [];
        //get YP information
        $table = YP_DETAILS.' as yp';
        $match = array("yp.yp_id"=>$yp_id);
        $fields = array("yp.yp_fname,yp.yp_lname,yp.date_of_birth,sd.email,ch.care_home_name");
        $join_tables = array(SOCIAL_WORKER_DETAILS . ' as sd' => 'sd.yp_id=yp.yp_id',CARE_HOME . ' as ch' => 'ch.care_home_id = yp.care_home');
        $data['YP_details'] = $this->common_model->get_records($table,$fields,$join_tables,'left',$match,'','','','','','','');
        //get mi details
        $match = "yp_id = " . $yp_id;
        $fields = array("*");
        $data['mi_details'] = $this->common_model->get_records(MEDICAL_INFORMATION, $fields, '', '', $match);

		//get mac details
        $match = array('yp_id'=> $yp_id,'is_previous_version'=>0);
        $fields = array("*");
        $data['mac_details'] = $this->common_model->get_records(MEDICAL_AUTHORISATIONS_CONSENTS, $fields, '', '', $match);

		$table = MEDICAL_PROFESSIONALS . ' as mc';
        $where = array("mc.yp_id"=>$yp_id);
        $fields = array("mc.*");
        $data['mp_details'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
        
        //get mac form
        $match = array('mac_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MAC_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['mac_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        
        //get mp form
        $match = array('mp_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MP_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['mp_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        //get omi details
        $match = array('yp_id'=> $yp_id,'is_previous_version'=>0);
        $fields = array("*");
        $data['omi_details'] = $this->common_model->get_records(OTHER_MEDICAL_INFORMATION, $fields, '', '', $match);
        
        //get omi form
        $match = array('omi_form_id'=> 1);
        $formsdata = $this->common_model->get_records(OMI_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['omi_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        //get mi details
        $match = array('yp_id'=> $yp_id,'is_previous_version'=>0);
        $fields = array("*");
        $data['miform_details'] = $this->common_model->get_records(MEDICAL_INOCULATIONS, $fields, '', '', $match);
        
        //get mi form
        $match = array('mi_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MI_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['mi_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        
         $data['crnt_view'] = $this->viewname;
        $data['ypid'] = $yp_id;
        
        $data['main_content'] = '/medspdf';
        $data['section'] = $section;
        $html = $this->parser->parse('layouts/PDFTemplate', $data);
        $pdfFileName = "meds" . $yp_id . ".pdf";
        $pdfFilePath = FCPATH . 'uploads/medical/';
        if (!is_dir(FCPATH . 'uploads/medical/')){
            @mkdir(FCPATH . 'uploads/medical/', 0777, TRUE);
        }
        if (file_exists($pdfFilePath . $pdfFileName)) {
            unlink($pdfFilePath . $pdfFileName);
        }
        //echo $html;exit;
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
      @Author : Niral Patel
      @Desc   : print health assessment data
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */
    public function DownloadViewMc($yp_id,$section = NULL) {
            
        $data = [];
       
        //get YP information
        $table = YP_DETAILS.' as yp';
        $match = array("yp.yp_id"=>$yp_id);
        $fields = array("yp.yp_fname,yp.yp_lname,yp.date_of_birth,sd.email,ch.care_home_name");
        $join_tables = array(SOCIAL_WORKER_DETAILS . ' as sd' => 'sd.yp_id=yp.yp_id',CARE_HOME . ' as ch' => 'ch.care_home_id = yp.care_home');
        $data['YP_details']  = $this->common_model->get_records($table,$fields,$join_tables,'left',$match,'','','','','','','');

        $match = array('mc_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MC_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        
        $table = MEDICAL_COMMUNICATION . ' as mc';
        $where = array("mc.yp_id"=>$yp_id);
        $fields = array("mc.*,time as time");
        $sortfield = 'communication_id';
                $sortby = 'desc';
            $data['information'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where);

        $data['crnt_view'] = $this->viewname;
        $data['ypid'] = $yp_id;
        $data['main_content'] = '/mcpdf';
        $data['section'] = $section;
        $html = $this->parser->parse('layouts/PDFTemplate', $data);
        $pdfFileName = "mc" . $yp_id . ".pdf";
        $pdfFilePath = FCPATH . 'uploads/medical/';
        if (!is_dir(FCPATH . 'uploads/medical/')){
            @mkdir(FCPATH . 'uploads/medical/', 0777, TRUE);
        }
        if (file_exists($pdfFilePath . $pdfFileName)) {
            unlink($pdfFilePath . $pdfFileName);
        }
        //echo $html;exit;
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
      @Author : Niral Patel
      @Desc   : print Download Medicaltion data
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */
     public function DownloadMedicaltion($yp_id,$section = NULL) {
        $data = [];
        //get YP information
        $table = YP_DETAILS.' as yp';
        $match = array("yp.yp_id"=>$yp_id);
        $fields = array("yp.yp_fname,yp.yp_lname,yp.date_of_birth,sd.email,ch.care_home_name");
        $join_tables = array(SOCIAL_WORKER_DETAILS . ' as sd' => 'sd.yp_id=yp.yp_id',CARE_HOME . ' as ch' => 'ch.care_home_id = yp.care_home');
        $data['YP_details']  = $this->common_model->get_records($table,$fields,$join_tables,'left',$match,'','','','','','','');

        $table = MEDICATION . ' as mc';
        $where = array("mc.yp_id"=>$yp_id);
        $fields = array("mc.*");
        $data['information'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
        
        $match = array('m_form_id'=> 1);
        $formsdata = $this->common_model->get_records(M_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }

        $data['crnt_view'] = $this->viewname;
        $data['ypid'] = $yp_id;
        $data['main_content'] = '/medicationpdf';
        $data['section'] = $section;
        $html = $this->parser->parse('layouts/PDFTemplate', $data);
        $pdfFileName = "mc" . $yp_id . ".pdf";
        $pdfFilePath = FCPATH . 'uploads/medical/';
        if (!is_dir(FCPATH . 'uploads/medical/')){
            @mkdir(FCPATH . 'uploads/medical/', 0777, TRUE);
        }
        if (file_exists($pdfFilePath . $pdfFileName)) {
            unlink($pdfFilePath . $pdfFileName);
        }
        //echo $html;exit;
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
      @Author : Niral Patel
      @Desc   : print Download Download Administer Medication
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */
    public function DownloadAdministerMedication($yp_id,$section = NULL) {
		$medication_name = $this->input->get('professional_name');
        $search_date = $this->input->get('search_date');
        $search_time = $this->input->get('search_time');
        $search = $this->input->get('search');
        $data = [];
        //get YP information
        $table = YP_DETAILS.' as yp';
        $match = array("yp.yp_id"=>$yp_id);
        $fields = array("yp.yp_fname,yp.yp_lname,yp.date_of_birth,sd.email,ch.care_home_name");
        $join_tables = array(SOCIAL_WORKER_DETAILS . ' as sd' => 'sd.yp_id=yp.yp_id',CARE_HOME . ' as ch' => 'ch.care_home_id = yp.care_home');
        $data['YP_details']  = $this->common_model->get_records($table,$fields,$join_tables,'left',$match,'','','','','','','');

        $match = array('am_form_id'=> 1);
        $formsdata = $this->common_model->get_records(AM_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }

        $match = array('yp_id'=> $yp_id);
        $medication_data = $this->common_model->get_records(MEDICATION,'', '', '', $match);
        $data['stock'] = '';
        if(!empty($medication_data)){
         $data['stock']= $medication_data[0]['stock'];
        }
        $where = "mc.yp_id = ".$yp_id;
		
		if($search==1){
			
		if (!empty($medication_name)) {
                $where .= ' AND mc.select_medication = ' . $medication_name;
        }

        if (!empty($search_date)) {
            $search_date = dateformat($search_date);
                $where .= ' AND mc.date_given = "' . $search_date.'"';
        }
        if (!empty($search_time)) {
            $search_time = dbtimeformat($search_time);
                $where .= ' AND mc.time_given = "' . $search_time.'"';
        }
		}
		$login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = ADMINISTER_MEDICATION . ' as mc';
        $fields = array("mc.*,md.stock,mc.date_given as date_given,time_given as time_given");
        $join_tables = array(MEDICATION . ' as md' => 'md.medication_id=mc.select_medication');
        $sortfield = 'date_given,time_given';
        $sortby = 'desc';
        $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where);

		$data['crnt_view'] = $this->viewname;
        $data['ypid'] = $yp_id;
        $pp_id=$yp_id;
        $data['main_content'] = '/administar_medication_pdf';
        $data['section'] = $section;
        $html = $this->parser->parse('layouts/PDFTemplate', $data);
        $pdfFileName = "mc" . $pp_id . ".pdf";
        $pdfFilePath = FCPATH . 'uploads/medical/';
        if (!is_dir(FCPATH . 'uploads/medical/')){
            @mkdir(FCPATH . 'uploads/medical/', 0777, TRUE);
        }
        if (file_exists($pdfFilePath . $pdfFileName)) {
            unlink($pdfFilePath . $pdfFileName);
        }
        //echo $html;exit;
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
      @Author : Niral Patel
      @Desc   : print Download Health Assessment
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */
    public function DownloadHealthAssessment($yp_id,$section = NULL) {
        $data = [];
        //get YP information
        $table = YP_DETAILS.' as yp';
        $match = array("yp.yp_id"=>$yp_id);
        $fields = array("yp.yp_fname,yp.yp_lname,yp.date_of_birth,sd.email,ch.care_home_name");
        $join_tables = array(SOCIAL_WORKER_DETAILS . ' as sd' => 'sd.yp_id=yp.yp_id',CARE_HOME . ' as ch' => 'ch.care_home_id = yp.care_home');
        $data['YP_details']  = $this->common_model->get_records($table,$fields,$join_tables,'left',$match,'','','','','','','');    

        $match = array('ha_form_id'=> 1);
        $formsdata = $this->common_model->get_records(HA_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }

        $table = HEALTH_ASSESSMENT . ' as mc';
        $where = array("mc.yp_id"=>$yp_id);
        $sortfield = 'health_assessment_id';
        $sortby = 'desc';
        $fields = array("mc.*,CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id = mc.created_by');
        $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where);
        
        $data['crnt_view'] = $this->viewname;
        $data['ypid'] = $yp_id;
        
        $data['main_content'] = '/health_assessment_pdf';
        $data['section'] = $section;
        $html = $this->parser->parse('layouts/PDFTemplate', $data);
        $pdfFileName = "mc" . $yp_id . ".pdf";
        $pdfFilePath = FCPATH . 'uploads/medical/';
        if (!is_dir(FCPATH . 'uploads/medical/')){
            @mkdir(FCPATH . 'uploads/medical/', 0777, TRUE);
        }
        if (file_exists($pdfFilePath . $pdfFileName)) {
            unlink($pdfFilePath . $pdfFileName);
        }
        //echo $html;exit;
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
      @Author : Niral Patel
      @Desc   : print Download Appointment data
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */
    public function DownloadAppointment($yp_id,$section = NULL) {
        $data = [];
        //get YP information
        $table = YP_DETAILS.' as yp';
        $match = array("yp.yp_id"=>$yp_id);
        $fields = array("yp.yp_fname,yp.yp_lname,yp.date_of_birth,sd.email,ch.care_home_name");
        $join_tables = array(SOCIAL_WORKER_DETAILS . ' as sd' => 'sd.yp_id=yp.yp_id',CARE_HOME . ' as ch' => 'ch.care_home_id = yp.care_home');
        $data['YP_details']  = $this->common_model->get_records($table,$fields,$join_tables,'left',$match,'','','','','','','');
        
         $searchsort_session = $this->session->userdata('appointment_session_data');
         
         $professional_name = $searchsort_session['professional_name'];
         $search_date = $searchsort_session['search_date'];
         $search_time = $searchsort_session['search_time'];
         //Sorting
        
            if (!empty($searchsort_session['sortfield'])) {
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby'] = $searchsort_session['sortby'];
                $sortfield = $searchsort_session['sortfield'];
                $sortby = $searchsort_session['sortby'];
            } else {
                $sortfield = 'appointment_id';
                $sortby = 'desc';
                $data['sortfield'] = $sortfield;
                $data['sortby'] = $sortby;
            }
        
        //Search text
        
            if (empty($allflag) && !empty($searchsort_session['searchtext'])) {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext = $data['searchtext'];
            } else {
                $data['searchtext'] = '';
            }
        
        //Query
        $table = MEDICAL_PROFESSIONALS_APPOINTMENT . ' as mc';
        $whereCond = 'mc.yp_id = '.$yp_id.' AND mc.is_delete = "0" ';
        $fields = array("mc.*,concat(mp.title,' ',mp.first_name,' ',mp.surname,' - ',mp.professional) as mp_name, concat(mp.house_namenumber,',',mp.street,' ',mp.town,',',mp.city,',',mp.postcode) as address");
        $join_tables = array(MEDICAL_PROFESSIONALS . ' as mp' => 'mc.mp_id= mp.mp_id',YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id');
        if (!empty($professional_name)) {
                $whereCond .= ' AND mc.mp_id = ' . $professional_name;
        }
        
        if (!empty($search_date)) {
                $whereCond .= ' AND mc.appointment_date = "' . dateformat($search_date).'"';
        }
        if (!empty($search_time)) {
                $whereCond .= ' AND mc.appointment_time = "' . dbtimeformat($search_time).'"';
        }
        if (!empty($searchtext)) {
            
        } else {
        $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $whereCond);
        }
        //get mac form
        $match = array('mp_form_id'=> 1);
        $formsdata = $this->common_model->get_records(MP_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        
        $data['crnt_view'] = $this->viewname;
        $data['ypid'] = $yp_id;
        
        $data['main_content'] = '/appointment_pdf';
        $data['section'] = $section;
        $html = $this->parser->parse('layouts/PDFTemplate', $data);
        $pdfFileName = "mc" . $yp_id . ".pdf";
        $pdfFilePath = FCPATH . 'uploads/medical/';
        if (!is_dir(FCPATH . 'uploads/medical/')){
            @mkdir(FCPATH . 'uploads/medical/', 0777, TRUE);
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
   @Author : Ritesh rana
   @Desc   : User signoff
   @Input   : 
   @Output  : 
   @Date   : 12/03/2017
   */

  public function manager_review($yp_id) {
if (!empty($yp_id)) {
          $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
          $match = array('yp_id'=> $yp_id,'created_by'=>$login_user_id);
          $check_signoff_data = $this->common_model->get_records(MEDS_SIGNOFF,'', '', '', $match);
        if(empty($check_signoff_data) > 0){
          $update_pre_data['yp_id'] = $yp_id;
          $update_pre_data['created_date'] = datetimeformat();
          $update_pre_data['created_by'] = $login_user_id;
        if ($this->common_model->insert(MEDS_SIGNOFF,$update_pre_data)) {
          $msg = $this->lang->line('successfully_meds_review');
          $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {
          // error
          $msg = $this->lang->line('error_msg');
          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }
      }else{
        $msg = $this->lang->line('already_meds_review');
      $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
      }
    }else{      
      $msg = $this->lang->line('error_msg');
      $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
    }
    redirect('/' . $this->viewname .'/index/'.$yp_id);
  }

/*
   @Author : Ritesh rana
   @Desc   : check edit url 
   @Input   : 
   @Output  : 
   @Date   : 12/03/2017
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
   @Author : Ritesh rana
   @Desc   : check check qut
   @Input   : 
   @Output  : 
   @Date   : 12/03/2017
   */
public function check_qut() {
        $ypId = $this->input->post('ypId');
        $medication_id = $this->input->post('medication_id');
        $quantity_left = $this->input->post('quantity_left');

        $table = MEDICATION . ' as m';
        $where = array("m.medication_id"=>$medication_id);
        $fields = array("m.stock");
        $stock_qut = $this->common_model->get_records($table, $fields,'', '', '', '', '', '','','', '', $where);
        
        if(($stock_qut[0]['stock']) >= $quantity_left){
            echo "true";
        }else{
            echo "false";
        }
        exit;
    }

     /*
      @Author : Niral Patel
      @Desc   : Add mi appointment data
      @Input  :
      @Output :
      @Date   : 20/07/2017
     */

    public function add_appointment_data($ypid) {
      if(is_numeric($ypid)){
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($ypid,$fields);
          if(empty($data['YP_details']))
          {
              show_404 ();
          }   
      //get mi details
      $match = array('yp_id'=> $ypid);
      $data['mp_yp_data'] = $this->common_model->get_records(MEDICAL_PROFESSIONALS,'', '', '', $match);
      
      //get mac form
      $match = array('mp_form_id'=> 1);
      $formsdata = $this->common_model->get_records(MP_FORM,array("form_json_data"), '', '', $match);
      if(!empty($formsdata))
      {
          $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
      }
      $data['ypid'] = $ypid;
      $data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
      $data['header'] = array('menu_module' => 'YoungPerson');
      $data['main_content'] = '/add_appointment';
      $this->parser->parse('layouts/DefaultTemplate', $data);
    }else{
              show_404 ();
    }
    
    }
    /*
      @Author : Niral Patel
      @Desc   : Add mi appointment data
      @Input  :
      @Output :
      @Date   : 20/07/2017
     */
      public function getMedicalDetail() {
        $postdata = $this->input->post();
        $medication_id = $postdata['medication_id'];
        //get medication
        $fields = array("m.*");
        $match = array('m.medication_id' => $medication_id);
        $medication_data = $this->common_model->get_records(MEDICATION.' as m',$fields, '', '', $match, '', '', '','','', '');
        $datePrescribed = configDateTime($medication_data[0]['date_prescribed']);
        $medication_data[0]['date_prescribed'] = $datePrescribed;
        echo json_encode($medication_data[0]);
        exit;
      }

      /*
      @Author : Niral Patel
      @Desc   : Read more medication data
      @Input    :medaication id
      @Output   :
      @Date   : 05/07/2018
     */
      public function readmore_medication($id,$field)
      {
            $params['fields'] = [$field];
            $params['table'] = MEDICATION;
            $params['match_and'] = 'medication_id=' . $id . '';
            $data['documents'] = $this->common_model->get_records_array($params);
            $data['field'] = $field;
            $this->load->view($this->viewname . '/readmore', $data);
      }

      /*
      @Author : Niral Patel
      @Desc   : readmore administer medication data
      @Input    :medaication id
      @Output   :
      @Date   : 05/07/2018
     */
      public function readmore_administer_medication($id,$field)
      {
            $params['fields'] = [$field];
            $params['table'] = ADMINISTER_MEDICATION;
            $params['match_and'] = 'administer_medication_id=' . $id . '';
            $data['documents'] = $this->common_model->get_records_array($params);
            $data['field'] = $field;
            $this->load->view($this->viewname . '/readmore', $data);
      }

      /*
      @Author : Niral Patel
      @Desc   : readmore health assessment
      @Input    :medaication id
      @Output   :
      @Date   : 05/07/2018
     */
      public function readmore_health_assessment($id,$field)
      {
            $params['fields'] = [$field];
            $params['table'] = HEALTH_ASSESSMENT;
            $params['match_and'] = 'health_assessment_id=' . $id . '';
            $data['documents'] = $this->common_model->get_records_array($params);
            $data['field'] = $field;
            $this->load->view($this->viewname . '/readmore', $data);
      }

      /*
      @Author : Niral Patel
      @Desc   : readmore medical communication
      @Input    :medaication id
      @Output   :
      @Date   : 05/07/2018
     */
      public function readmore_medical_communication($id,$field)
      {
            $params['fields'] = [$field];
            $params['table'] = MEDICAL_COMMUNICATION;
            $params['match_and'] = 'communication_id=' . $id . '';
            $data['documents'] = $this->common_model->get_records_array($params);
            $data['field'] = $field;
            $this->load->view($this->viewname . '/readmore', $data);
      }
	  
    /* Updated By Dhara Bhalala on 17/09/2018 
     * to apply sorting filter format in excel sheet
     * @Author : Niral Patel
     * @Desc   : Generate Report based on the data
     * @Input   :
     * @Output  :
     * @Date   : 11-5-2018
     */

    public function generateExcelFile($yp_id) {
        $this->load->library('excel');
        $this->activeSheetIndex = $this->excel->setActiveSheetIndex(0);

        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Pocket Money');
        $exceldataHeader = "";
        $exceldataValue = "";
        $headerCount = 1;
        if (!empty($yp_id)) {
            $medication_name = $this->input->get('professional_name');
            $search_date = $this->input->get('search_date');
            $search_time = $this->input->get('search_time');
            $search = $this->input->get('search');
            $sortfield = $this->input->get('sortfield');
            $sortby = $this->input->get('sortby');
            $data = [];
            //get YP information
            $table = YP_DETAILS . ' as yp';
            $match = array("yp.yp_id" => $yp_id);
            $fields = array("yp.yp_fname,yp.yp_lname,yp.date_of_birth,sd.email,ch.care_home_name");
            $join_tables = array(SOCIAL_WORKER_DETAILS . ' as sd' => 'sd.yp_id=yp.yp_id', CARE_HOME . ' as ch' => 'ch.care_home_id = yp.care_home');
            $data['YP_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match, '', '', '', '', '', '', '');
            $match = array('am_form_id' => 1);
            $formsdata = $this->common_model->get_records(AM_FORM, array("form_json_data"), '', '', $match);
            if (!empty($formsdata)) {
                $formsdata_expr = json_decode($formsdata[0]['form_json_data'], TRUE);
            }

            $match = array('yp_id' => $yp_id);
            $medication_data = $this->common_model->get_records(MEDICATION, '', '', '', $match);
            $data['stock'] = '';
            if (!empty($medication_data)) {
                $data['stock'] = $medication_data[0]['stock'];
            }
            $where = "mc.yp_id = " . $yp_id;

            if ($search == 1) {
                if (!empty($medication_name)) {
                    $where .= ' AND mc.select_medication = ' . $medication_name;
                }
                if (!empty($search_date)) {
                    $search_date = dateformat($search_date);
                    $where .= ' AND mc.date_given = "' . $search_date . '"';
                }
                if (!empty($search_time)) {
                    $search_time = dbtimeformat($search_time);
                    $where .= ' AND mc.time_given = "' . $search_time . '"';
                }
            
            }

            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $table = ADMINISTER_MEDICATION . ' as mc';
            $fields = array("mc.*, md.stock,mc.date_given as date_given,time_given as time_given");
            $join_tables = array(MEDICATION . ' as md' => 'md.medication_id=mc.select_medication');
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where);

            $data['crnt_view'] = $this->viewname;
            $data['ypid'] = $yp_id;
            $form_field = array();
            $exceldataHeader = array();
            if (!empty($formsdata_expr)) {
                foreach ($formsdata_expr as $row) {
                    $exceldataHeader[] .=html_entity_decode($row['label']);
                }
                $exceldataHeader[] .= 'Quantity Remaining';
            }

            if (!empty($formsdata)) {
                $sheet = $this->excel->getActiveSheet();
                $this->excel->setActiveSheetIndex(0)->setTitle('ADMINISTRATION HISTORY LOG');
                $sheet->getStyle('A1:Z1')->getFont()->setBold(true);
                $sheet->getColumnDimension('A')->setWidth(35);
                $sheet->getColumnDimension('B')->setWidth(12);
                $sheet->getColumnDimension('C')->setWidth(10);
                $sheet->getColumnDimension('D')->setWidth(15);
                $sheet->getColumnDimension('E')->setWidth(8);
                $sheet->getColumnDimension('F')->setWidth(25);
                $sheet->getColumnDimension('G')->setWidth(15);
                $sheet->getColumnDimension('H')->setWidth(20);
                $sheet->getColumnDimension('I')->setWidth(20);
                $sheet->getColumnDimension('J')->setWidth(15);
                $sheet->getColumnDimension('K')->setWidth(15);
                $sheet->fromArray($exceldataHeader, Null, 'A1')->getStyle('A1')->getFont()->setBold(true); // Set Header Data

                if (!empty($data['information'])) {
                    $col = 2;
                    foreach ($data['information'] as $data) {
                        if (!empty($formsdata_expr)) {
                            $exceldataValue = array();
                            foreach ($formsdata_expr as $row) {
                                if ($row['type'] == 'date') {
                                    if ((!empty($data[$row['name']]) && $data[$row['name']] != '0000-00-00')) {
                                        $exceldataValue[] .= configDateTime($data[$row['name']]);
                                    }
                                } else if($row['type'] == 'text' && $row['subtype'] == 'time'){
                                    $exceldataValue[] .= timeformat($data[$row['name']]);                                    
                                } else if ($row['type'] == 'select') {
                                    if (!empty($data[$row['name']])) {
                                        if (!empty($row['description']) && $row['description'] == 'get_user') {

                                            $get_data = $this->common_model->get_single_user($data[$row['name']]);
                                            $exceldataValue[] .=!empty($get_data[0]['username']) ? $get_data[0]['username'] : '';
                                        } else if (!empty($row['description']) && $row['description'] == 'get_medication') {

                                            $get_medication_data = $this->common_model->get_single_medication($data[$row['name']]);
                                            $exceldataValue[] .= !empty($get_medication_data[0]['medication_name']) ? $get_medication_data[0]['medication_name'] : '';
                                        } else {
                                            $exceldataValue[] .= !empty($data[$row['name']]) ? $data[$row['name']] : '';
                                        }
                                    } else {
                                        $exceldataValue[] .= !empty($data[$row['name']]) ? $data[$row['name']] : '';
                                    }
                                } else {
                                    $exceldataValue[] .= !empty($data[$row['name']]) ? $data[$row['name']] : '';
                                }
                            }
                            $exceldataValue[] .= !empty($data['available_stock']) ? $data['available_stock'] : '';
                        }
                        $sheet->fromArray($exceldataValue, Null, 'A' . $col)->getStyle('A' . $col)->getFont()->setBold(false);
                        $col ++;
                    } // end recordData foreach
                }
            }
        }
        $fileName = 'ADMINISTRATION HISTORY LOG' . date('Y-m-d H:i:s') . '.xls'; // Generate file name
        $this->downloadExcelFile($this->excel, $fileName); // download function Xls file function call
    }

    /*
     * @Author : Nikunj Ghelani
     * @Desc   : Download Report based on the data
     * @Input   :
     * @Output  :
     * @Date   : 10-7-2018
     */
    public function downloadExcelFile($objExcel, $fileName) {
        $this->excel = $objExcel;
        //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment; filename = "' . $fileName . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        ob_end_clean();
        ob_start();
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
        exit;
    }

/*
     * @Author : Nikunj Ghelani
     * @Desc   : readmore medical professionals data
     * @Input   :
     * @Output  :
     * @Date   : 10-7-2018
     */
 public function readmore_medical_professionals($id,$field)
      {
            $params['fields'] = [$field];
            $params['table'] = MEDICAL_PROFESSIONALS;
            $params['match_and'] = 'mp_id=' . $id . '';
            $data['documents'] = $this->common_model->get_records_array($params);
            $data['field'] = $field;
            $this->load->view($this->viewname . '/readmore', $data);
      }
	  
    /*
     * @Author : Nikunj Ghelani
     * @Desc   : add comment for medical appoinment
     * @Input   :
     * @Output  :
     * @Date   : 20/8/2018
    */
	   public function add_commnts() {
	    $main_user_data = $this->session->userdata('LOGGED_IN');
        $md_appoint_id = $this->input->post('md_appoint_id');
        $yp_id = $this->input->post('yp_id');
        $data = array(
                'md_comment' => $this->input->post('md_comment'),
                'yp_id' => $this->input->post('yp_id'),
                'md_appoint_id' => $this->input->post('md_appoint_id'),
                'created_date' => datetimeformat(),
                'created_by' => $main_user_data['ID'],
            );  
            //Insert Record in Database
            if ($this->common_model->insert(MD_COMMENTS, $data)) {
                $msg = $this->lang->line('comments_add_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            } else {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
         redirect('/' . $this->viewname . '/appointment_view/' . $md_appoint_id . '/' . $yp_id);
    }
          
}


