<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->module = $this->uri->segment(1);
        $this->viewname = $this->uri->segment(2);
        $this->load->library(array('form_validation', 'Session'));
        $this->load->config('aad_auth');
    }
 /*
      @Author : Ritesh Rana
      @Desc   : Dashboard Page
      @Input  :
      @Output :
      @Date   : 26/03/2017
     */
    public function index() {
        $data['main_content'] = '/Dashboard';
        $data['footerJs'][0] = base_url('uploads/custom/js/dashboard/dashboard.js');
        /*
         * logged in user data
         */
        if ($this->session->userdata('LOGGED_IN')) {
            $login_id = $this->session->userdata('LOGGED_IN')['ID'];
            $table = LOGIN . ' as l';
            $where = array("l.login_id" => $login_id);
            $fields = array("l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname, l.email, l.password, l.address,l.position, l.mobile_number, l.created_date, l.status,l.role_id,rm.role_name,l.profile_img");
            $join_tables = array(ROLE_MASTER . ' as rm' => 'rm.role_id = l.role_id');
            $data['logged_user'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
            /*
             * logged in user data ends
             */
            //get staff notices data
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $table = STAFF_NOTICES . ' as sn';
            $where = array("sn.is_delete" => "0");
            $fields = array("sn.staff_notices_id,sn.title,sn.notice,sn.created_by,l.firstname,l.lastname,sn.created_date");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= sn.created_by');
            $data['staff_no_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
            //get school handover data
            $table = SCHOOL_HANDOVER . ' as sh';
            $where = array();
            $fields = array("sh.school_handover_id,sh.title,sh.notice,sh.created_by,l.firstname,l.lastname,sh.created_date");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id = sh.created_by');
            $data['school_hand_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
            
            //get crisis handover data
            $table = CRISIS_HANDOVER . ' as ch';
            $where = array();
            $fields = array("ch.crisis_handover_id,ch.title,ch.notice,ch.created_by,l.firstname,l.lastname,ch.created_date");
            $join_tables_data = array(LOGIN . ' as l' => 'l.login_id = ch.created_by');
            $data['crisis_hand_data'] = $this->common_model->get_records($table, $fields, $join_tables_data, 'left', '', '', '', '', '', '', '', $where);
            
            $data['header'] = array('menu_module' => 'Dashboard');
            
            if ($this->input->is_ajax_request()) {
                $this->load->view('Dashboard', $data);
            } else {
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            redirect('Masteradmin');
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : open popup for staff notices
      @Input  :
      @Output :
      @Date   : 26/03/2017
     */
    public function staffNotices() {
    //Get Records From Login Table
        $data['footerJs'][0] = base_url('uploads/custom/js/dashboard/dashboard.js');
        $data['crnt_view'] = $this->module;
        $data['form_action_path'] = $this->module . '/insertStaffNotices/';
        $data['main_content'] = '/staffnotices';
        $this->load->view('/staffnotices', $data);
    }


    /*
      @Author : Ritesh Rana
      @Desc   : upload file
      @Input  :
      @Output :
      @Date   : 26/03/2017
    */
    public function upload_file($fileext = '') {
        $str = file_get_contents('php://input');
        echo $filename = time() . uniqid() . "." . $fileext;
        file_put_contents($this->config->item('staff_notices_img_url') . '/' . $filename, $str);
    }
    /*
      @Author : Ritesh Rana
      @Desc   : insert staff notices data
      @Input  :
      @Output :
      @Date   : 26/03/2017
    */
    public function insertStaffNotices() {
        //STAFF_NOTICES_UPLOADS 
        $main_user_data = $this->session->userdata('LOGGED_IN');
        $data['title'] = $this->input->post('title');
        $data['notice'] = $this->input->post('notice');
        $data['created_by'] = $main_user_data['ID'];
        $data['created_date'] = datetimeformat();
        $success = $this->common_model->insert(STAFF_NOTICES, $data);
        $insert_id = $this->db->insert_id();
        //Insert log activity
        $activity = array(
          'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
          'module_name'         => STAFF_NOTICE_MODULE,
          'module_field_name'   => '',
          'type'                => 1
        );
        log_activity($activity);
        $upload_dir = $this->config->item('staff_notices_img_url');
        if (!is_dir($upload_dir)) {
            //create directory
            mkdir($upload_dir, 0777, TRUE);
        }
        if (is_dir($upload_dir)) {
            /* image upload code */
            $file_name = array();
            $file_array1 = $this->input->post('file_data');
            $file_array_delete = $this->input->post('deleted_images');
                 
            $file_name = $_FILES['fileUpload']['name'];
            if (count($file_name) > 0 && count($file_array1) > 0) {
                $differentedImage = array_diff($file_name, $file_array1);
                foreach ($file_name as $file) {
                    if (in_array($file, $differentedImage)) {
                        $key_data[] = array_search($file, $file_name); // $key = 2;
                    }
                }
                if (!empty($key_data)) {
                    foreach ($key_data as $key) {
                        unset($_FILES['fileUpload']['name'][$key]);
                        unset($_FILES['fileUpload']['type'][$key]);
                        unset($_FILES['fileUpload']['tmp_name'][$key]);
                        unset($_FILES['fileUpload']['error'][$key]);
                        unset($_FILES['fileUpload']['size'][$key]);
                    }
                }
            }
        $file_array_delete = $this->input->post('deleted_images');
        
            if (!empty($file_array_delete)) {
                        $files = $_FILES;
                      $FileDataArr = array(); 
                         $input='fileUpload';

    $tmpFile = count($_FILES[$input]['name']);
    if ($tmpFile > 0 && $_FILES[$input]['name'][0] != NULL) {
        for ($i = 0; $i < $tmpFile; $i++) {
            $imgname = strtolower(end((explode(".",$files[$input]['name'][$i]))));
              $files[$input]['name'][$i];
            if(in_array($_FILES[$input]['name'][$i],$file_array_delete)){
                 $files[$input]['name'][$i];
                
                 unset($_FILES[$input]['name'][$i]);
                 unset($_FILES[$input]['type'][$i]);
                 unset($_FILES[$input]['tmp_name'][$i]);
                 unset($_FILES[$input]['error'][$i]);
                 unset($_FILES[$input]['size'][$i]);

            }
        
       }
   }
}
   
            $_FILES['fileUpload'] = $arr = array_map('array_values', $_FILES['fileUpload']);
            $data['lead_view'] = $this->module;
            $uploadData = uploadImage('fileUpload', $this->config->item('staff_notices_img_url'), $data['lead_view']);
            $Marketingfiles = array();
            foreach ($uploadData as $dataname) {
                $Marketingfiles[] = $dataname['file_name'];
            }
            $marketing_file_str = implode(",", $Marketingfiles);
            $file2 = $this->input->post('fileToUpload');
            if (!(empty($file2))) {
                $file_data = implode(",", $file2);
            } else {
                $file_data = '';
            }
            if (!empty($marketing_file_str) && !empty($file_data)) {
                $marketingdata['file'] = $marketing_file_str . ',' . $file_data;
            } else if (!empty($marketing_file_str)) {
                $marketingdata['file'] = $marketing_file_str;
            } else {
                $marketingdata['file'] = $file_data;
            }
            $marketingdata['file_name'] = $file_data;
            if ($marketingdata['file_name'] != '') {
                $explodedData = explode(',', $marketingdata['file_name']);

                foreach ($explodedData as $img) {
                    array_push($uploadData, array('file_name' => $img));
                }
            }

            $estFIles = array();

            if ($this->input->post('gallery_path')) {
                $gallery_path = $this->input->post('gallery_path');
                $est_files = $this->input->post('gallery_files');
                if (count($gallery_path) > 0) {
                    for ($i = 0; $i < count($gallery_path); $i++) {
                        $estFIles[] = ['file_name' => $est_files[$i], 'file_path' => $gallery_path[$i], 'staff_notices_id' => $insert_id];
                    }
                }
            }


            if (count($uploadData) > 0) {
                foreach ($uploadData as $files) {
                    $estFIles[] = ['file_name' => $files['file_name'], 'file_path' => $this->config->item('staff_notices_img_url'), 'staff_notices_id' => $insert_id];
                }
            }
            if (count($estFIles) > 0) {
                $where = array('staff_notices_id' => $insert_id);
                if (!$this->common_model->insert_batch(STAFF_NOTICES_UPLOADS, $estFIles)) {
                    $this->session->set_flashdata('msg', lang('error'));
                    redirect($this->module); //Redirect On Listing page
                }
            }

            /**
             * SOFT DELETION CODE STARTS MAULIK SUTHAR
             */
            $softDeleteImagesArr = $this->input->post('softDeletedImages');
            $softDeleteImagesUrlsArr = $this->input->post('softDeletedImagesUrls');
            if (count($softDeleteImagesUrlsArr) > 0) {
                foreach ($softDeleteImagesUrlsArr as $urls) {
                    unlink(BASEPATH . '../' . $urls);
                }
            }

            if (count($softDeleteImagesUrlsArr) > 0) {
                $dlStr = implode(',', $softDeleteImagesArr);
                $this->common_model->delete(STAFF_NOTICES_UPLOADS, 'file_id IN(' . $dlStr . ')');
            }
        }
        
        /*
         * SOFT DELETION CODE ENDS
         */
        $data['crnt_view'] = $this->viewname;
        if ($success) {
            $msg = $this->lang->line('Added_Staff_Notices_Successfully');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {    // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }
        redirect('Dashboard'); //Redirect On Listing page
    }

/*
      @Author : Ritesh Rana
      @Desc   : user logout functionality
      @Input  :
      @Output :
      @Date   : 26/03/2017
    */
    public function logout() {
            $this->session->unset_userdata('LOGGED_IN');
            $this->session->unset_userdata('nfc_admin_session');
            $this->session->unset_userdata('access_token');
            $this->session->unset_userdata('token_type');
            $return_to = base_url('Masteradmin/login');
            $feedURL = "https://login.microsoftonline.com/".$this->config->item('directory_identifier')."/oauth2/logout?post_logout_redirect_uri=". urlencode($return_to);
            redirect($feedURL);
    }

    /*
      @Author : Ritesh Rana
      @Desc   : download staff notices file functionality
      @Input  :
      @Output :
      @Date   : 26/03/2017
    */
function download($id) {
        if ($id > 0) {
            $params['fields'] = ['*'];
            $params['table'] = STAFF_NOTICES_UPLOADS . ' as CM';
            $params['match_and'] = 'CM.staff_notices_id=' . $id . '';
            $cost_files = $this->common_model->get_records_array($params);
          if (count($cost_files) > 1) {
           $path = array();
              foreach ($cost_files as $value) {
                $files[] = $value['file_path'] . '' . $value['file_name'];  
              }
            $this->load->library('zip');
            foreach ($files as $file) {
                $this->zip->read_file($file);
            }
            $this->zip->download('attachment.zip');
            }else{
                $pth = file_get_contents($cost_files[0]['file_path'] . '/' . $cost_files[0]['file_name']);
                $this->load->helper('download');
                force_download($cost_files[0]['file_name'], $pth);
            }
            redirect($this->module);
      }
  }

//school handover for crisis
    /*
      @Author : Ritesh Rana
      @Desc   : opn popup for scholl Handover functionality
      @Input  :
      @Output :
      @Date   : 26/03/2017
    */
    public function schollHandover() {
        $data['footerJs'][0] = base_url('uploads/custom/js/dashboard/dashboard.js');
        $data['crnt_view'] = $this->module;
        $data['form_action_path'] = $this->module . '/insertSchollHandover/';
        $data['main_content'] = '/schollhandover';
        $this->load->view('/schollhandover', $data);
    }
    
    /*
      @Author : Ritesh Rana
      @Desc   : scholl Handover Upload File functionality
      @Input  :
      @Output :
      @Date   : 26/03/2017
    */
    public function schollHandoverUploadFile($fileext = '') {
        $str = file_get_contents('php://input');
        echo $filename = time() . uniqid() . "." . $fileext;
        file_put_contents($this->config->item('school_handover_img_url') . '/' . $filename, $str);
    }

    /*
      @Author : Ritesh Rana
      @Desc   : insert Scholl Handover functionality
      @Input  :
      @Output :
      @Date   : 26/03/2017
    */
    public function insertSchollHandover() {
        //STAFF_NOTICES_UPLOADS 
        $main_user_data = $this->session->userdata('LOGGED_IN');
        $data['title'] = $this->input->post('title');
        $data['notice'] = $this->input->post('notice');
        $data['created_by'] = $main_user_data['ID'];
        $data['created_date'] = datetimeformat();

        $success = $this->common_model->insert(SCHOOL_HANDOVER, $data);
        $insert_id = $this->db->insert_id();
        //Insert log activity
        $activity = array(
          'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
          'module_name'         => SCHOOL_HANDOVER_MODULE,
          'module_field_name'   => '',
          'type'                => 1
        );
        
        log_activity($activity);
        $upload_dir = $this->config->item('school_handover_img_url');
        if (!is_dir($upload_dir)) {
            //create directory
            mkdir($upload_dir, 0777, TRUE);
        }
        if (is_dir($upload_dir)) {
            /* image upload code */
            $file_name = array();
            $file_array1 = $this->input->post('file_data');

            $file_name = $_FILES['fileUpload']['name'];
            if (count($file_name) > 0 && count($file_array1) > 0) {
                $differentedImage = array_diff($file_name, $file_array1);
                foreach ($file_name as $file) {
                    if (in_array($file, $differentedImage)) {
                        $key_data[] = array_search($file, $file_name); // $key = 2;
                    }
                }
                if (!empty($key_data)) {
                    foreach ($key_data as $key) {
                        unset($_FILES['fileUpload']['name'][$key]);
                        unset($_FILES['fileUpload']['type'][$key]);
                        unset($_FILES['fileUpload']['tmp_name'][$key]);
                        unset($_FILES['fileUpload']['error'][$key]);
                        unset($_FILES['fileUpload']['size'][$key]);
                    }
                }
            }

            $file_array_delete = $this->input->post('deleted_images');
            if (!empty($file_array_delete)) {
                        $files = $_FILES;
                      $FileDataArr = array(); 

                         $input='fileUpload';

    $tmpFile = count($_FILES[$input]['name']);
    if ($tmpFile > 0 && $_FILES[$input]['name'][0] != NULL) {
        for ($i = 0; $i < $tmpFile; $i++) {
            $imgname = strtolower(end((explode(".",$files[$input]['name'][$i]))));
              $files[$input]['name'][$i];
            if(in_array($_FILES[$input]['name'][$i],$file_array_delete)){
                 $files[$input]['name'][$i];
                
                 unset($_FILES[$input]['name'][$i]);
                 unset($_FILES[$input]['type'][$i]);
                 unset($_FILES[$input]['tmp_name'][$i]);
                 unset($_FILES[$input]['error'][$i]);
                 unset($_FILES[$input]['size'][$i]);

            }
        
       }
   }

}

            $_FILES['fileUpload'] = $arr = array_map('array_values', $_FILES['fileUpload']);
            $data['lead_view'] = $this->module;
            $uploadData = uploadImage('fileUpload', $this->config->item('school_handover_img_url'), $data['lead_view']);
            $Marketingfiles = array();
            foreach ($uploadData as $dataname) {
                $Marketingfiles[] = $dataname['file_name'];
            }
            $marketing_file_str = implode(",", $Marketingfiles);
            $file2 = $this->input->post('fileToUpload');
            if (!(empty($file2))) {
                $file_data = implode(",", $file2);
            } else {
                $file_data = '';
            }
            if (!empty($marketing_file_str) && !empty($file_data)) {
                $marketingdata['file'] = $marketing_file_str . ',' . $file_data;
            } else if (!empty($marketing_file_str)) {
                $marketingdata['file'] = $marketing_file_str;
            } else {
                $marketingdata['file'] = $file_data;
            }
            $marketingdata['file_name'] = $file_data;
            if ($marketingdata['file_name'] != '') {
                $explodedData = explode(',', $marketingdata['file_name']);

                foreach ($explodedData as $img) {
                    array_push($uploadData, array('file_name' => $img));
                }
            }

            $estFIles = array();

            if ($this->input->post('gallery_path')) {
                $gallery_path = $this->input->post('gallery_path');
                $est_files = $this->input->post('gallery_files');
                if (count($gallery_path) > 0) {
                    for ($i = 0; $i < count($gallery_path); $i++) {
                        $estFIles[] = ['file_name' => $est_files[$i], 'file_path' => $gallery_path[$i], 'school_handover_id' => $insert_id];
                    }
                }
            }


            if (count($uploadData) > 0) {
                foreach ($uploadData as $files) {
                    $estFIles[] = ['file_name' => $files['file_name'], 'file_path' => $this->config->item('school_handover_img_url'), 'school_handover_id' => $insert_id];
                }
            }
            if (count($estFIles) > 0) {
                $where = array('school_handover_id' => $insert_id);
                if (!$this->common_model->insert_batch(SCHOOL_HANDOVER_FILE, $estFIles)) {
                    $this->session->set_flashdata('msg', lang('error'));
                    redirect($this->module); //Redirect On Listing page
                }
            }

            /**
             * SOFT DELETION CODE STARTS MAULIK SUTHAR
             */
            $softDeleteImagesArr = $this->input->post('softDeletedImages');
            $softDeleteImagesUrlsArr = $this->input->post('softDeletedImagesUrls');
            if (count($softDeleteImagesUrlsArr) > 0) {
                foreach ($softDeleteImagesUrlsArr as $urls) {
                    unlink(BASEPATH . '../' . $urls);
                }
            }

            if (count($softDeleteImagesUrlsArr) > 0) {
                $dlStr = implode(',', $softDeleteImagesArr);
                $this->common_model->delete(SCHOOL_HANDOVER_FILE, 'file_id IN(' . $dlStr . ')');
            }
        }
        /*
         * SOFT DELETION CODE ENDS
         */
        $data['crnt_view'] = $this->viewname;
        if ($success) {
            $msg = $this->lang->line('Added_School_Handover_Successfully');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {    // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            // redirect($this->viewname);
        }
        redirect('Dashboard'); //Redirect On Listing page
    }

    /*crisis handover functionality start*/
     /*
      @Author : Ritesh Rana
      @Desc   : crisis handover functionality
      @Input  :
      @Output :
      @Date   : 26/03/2017
    */
     public function CrisisHandover() {//Get Records From Login Table
        $data['footerJs'][0] = base_url('uploads/custom/js/dashboard/dashboard.js');
        $data['crnt_view'] = $this->module;
        $data['form_action_path'] = $this->module . '/insertCrisisHandover/';
        $data['main_content'] = '/crisishandover';
        $this->load->view('/crisishandover', $data);
    }
    
    /*
      @Author : Ritesh Rana
      @Desc   : Crisis Handover Upload File functionality
      @Input  :
      @Output :
      @Date   : 26/03/2017
    */
     public function CrisisHandoverUploadFile($fileext = '') {
        $str = file_get_contents('php://input');
        echo $filename = time() . uniqid() . "." . $fileext;
        file_put_contents($this->config->item('school_handover_img_url') . '/' . $filename, $str);
    }

    /*
      @Author : Ritesh Rana
      @Desc   : insert Crisis Handover functionality
      @Input  :
      @Output :
      @Date   : 26/03/2017
    */
    public function insertCrisisHandover() {
        //STAFF_NOTICES_UPLOADS 
        $main_user_data = $this->session->userdata('LOGGED_IN');
        $data['title'] = $this->input->post('title');
        $data['notice'] = $this->input->post('notice');
        $data['created_by'] = $main_user_data['ID'];
        $data['created_date'] = datetimeformat();

        $success = $this->common_model->insert(CRISIS_HANDOVER, $data);
        $insert_id = $this->db->insert_id();
        //Insert log activity
        $activity = array(
          'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
          'module_name'         => CRISIS_HANDOVER_MODULE,
          'module_field_name'   => '',
          'type'                => 1
        );

        log_activity($activity);
        $upload_dir = $this->config->item('crisis_handover_img_url');
        if (!is_dir($upload_dir)) {
            //create directory
            mkdir($upload_dir, 0777, TRUE);
        }
        if (is_dir($upload_dir)) {
            /* image upload code */
            $file_name = array();
            $file_array1 = $this->input->post('file_data');

            $file_name = $_FILES['fileUpload']['name'];
            if (count($file_name) > 0 && count($file_array1) > 0) {
                $differentedImage = array_diff($file_name, $file_array1);
                foreach ($file_name as $file) {
                    if (in_array($file, $differentedImage)) {
                        $key_data[] = array_search($file, $file_name); // $key = 2;
                    }
                }
                if (!empty($key_data)) {
                    foreach ($key_data as $key) {
                        unset($_FILES['fileUpload']['name'][$key]);
                        unset($_FILES['fileUpload']['type'][$key]);
                        unset($_FILES['fileUpload']['tmp_name'][$key]);
                        unset($_FILES['fileUpload']['error'][$key]);
                        unset($_FILES['fileUpload']['size'][$key]);
                    }
                }
            }


            $file_array_delete = $this->input->post('deleted_images');
            if (!empty($file_array_delete)) {
                        $files = $_FILES;
                      $FileDataArr = array(); 

                         $input='fileUpload';

    $tmpFile = count($_FILES[$input]['name']);
    if ($tmpFile > 0 && $_FILES[$input]['name'][0] != NULL) {
        for ($i = 0; $i < $tmpFile; $i++) {
            $imgname = strtolower(end((explode(".",$files[$input]['name'][$i]))));
              $files[$input]['name'][$i];
            if(in_array($_FILES[$input]['name'][$i],$file_array_delete)){
                 $files[$input]['name'][$i];
                
                 unset($_FILES[$input]['name'][$i]);
                 unset($_FILES[$input]['type'][$i]);
                 unset($_FILES[$input]['tmp_name'][$i]);
                 unset($_FILES[$input]['error'][$i]);
                 unset($_FILES[$input]['size'][$i]);

            }
        
       }
   }

}


            $_FILES['fileUpload'] = $arr = array_map('array_values', $_FILES['fileUpload']);
            $data['lead_view'] = $this->module;
            $uploadData = uploadImage('fileUpload', $this->config->item('crisis_handover_img_url'), $data['lead_view']);
            $Marketingfiles = array();
            foreach ($uploadData as $dataname) {
                $Marketingfiles[] = $dataname['file_name'];
            }
            $marketing_file_str = implode(",", $Marketingfiles);
            $file2 = $this->input->post('fileToUpload');
            if (!(empty($file2))) {
                $file_data = implode(",", $file2);
            } else {
                $file_data = '';
            }
            if (!empty($marketing_file_str) && !empty($file_data)) {
                $marketingdata['file'] = $marketing_file_str . ',' . $file_data;
            } else if (!empty($marketing_file_str)) {
                $marketingdata['file'] = $marketing_file_str;
            } else {
                $marketingdata['file'] = $file_data;
            }
            $marketingdata['file_name'] = $file_data;
            if ($marketingdata['file_name'] != '') {
                $explodedData = explode(',', $marketingdata['file_name']);

                foreach ($explodedData as $img) {
                    array_push($uploadData, array('file_name' => $img));
                }
            }

            $estFIles = array();

            if ($this->input->post('gallery_path')) {
                $gallery_path = $this->input->post('gallery_path');
                $est_files = $this->input->post('gallery_files');
                if (count($gallery_path) > 0) {
                    for ($i = 0; $i < count($gallery_path); $i++) {
                        $estFIles[] = ['file_name' => $est_files[$i], 'file_path' => $gallery_path[$i], 'crisis_handover_id' => $insert_id];
                    }
                }
            }

            if (count($uploadData) > 0) {
                foreach ($uploadData as $files) {
                    $estFIles[] = ['file_name' => $files['file_name'], 'file_path' => $this->config->item('crisis_handover_img_url'), 'crisis_handover_id' => $insert_id];
                }
            }
            if (count($estFIles) > 0) {
                $where = array('crisis_handover_id' => $insert_id);
                if (!$this->common_model->insert_batch(CRISIS_HANDOVER_FILE, $estFIles)) {
                    $this->session->set_flashdata('msg', lang('error'));
                    redirect($this->module); //Redirect On Listing page
                }
            }

            /**
             * SOFT DELETION CODE STARTS MAULIK SUTHAR
             */
            $softDeleteImagesArr = $this->input->post('softDeletedImages');
            $softDeleteImagesUrlsArr = $this->input->post('softDeletedImagesUrls');
            if (count($softDeleteImagesUrlsArr) > 0) {
                foreach ($softDeleteImagesUrlsArr as $urls) {
                    unlink(BASEPATH . '../' . $urls);
                }
            }

            if (count($softDeleteImagesUrlsArr) > 0) {
                $dlStr = implode(',', $softDeleteImagesArr);
                $this->common_model->delete(CRISIS_HANDOVER_FILE, 'file_id IN(' . $dlStr . ')');
            }
        }
        /*
         * SOFT DELETION CODE ENDS
         */
        $data['crnt_view'] = $this->viewname;
        if ($success) {
            $msg = $this->lang->line('Added_Crisis_Handover_Successfully');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {    // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }
        redirect('Dashboard'); //Redirect On Listing page
    }
    
    /*crisis handover functionality end*/
    /*
      @Author : Ritesh Rana
      @Desc   : Hand overFile download functionality
      @Input  :
      @Output :
      @Date   : 26/03/2017
    */
function HandoverFiledownload($id) {
        if ($id > 0) {
            $params['fields'] = ['*'];
            $params['table'] = SCHOOL_HANDOVER_FILE . ' as shf';
            $params['match_and'] = 'shf.school_handover_id=' . $id . '';
            $cost_files = $this->common_model->get_records_array($params);
          if (count($cost_files) > 1) {
           $path = array();
              foreach ($cost_files as $value) {
                $files[] = $value['file_path'] . '' . $value['file_name'];  
              }
            $this->load->library('zip');
            foreach ($files as $file) {
                $this->zip->read_file($file);
            }
            $this->zip->download('attachment.zip');
            }else{
                $pth = file_get_contents($cost_files[0]['file_path'] . '/' . $cost_files[0]['file_name']);
                $this->load->helper('download');
                force_download($cost_files[0]['file_name'], $pth);
            }
            redirect($this->module);
      }
  }

    /*
      @Author : Ritesh Rana
      @Desc   : Crisis Handover File download functionality
      @Input  :
      @Output :
      @Date   : 26/03/2017
    */
  function CrisisHandoverFiledownload($id) {
        if ($id > 0) {
            $params['fields'] = ['*'];
            $params['table'] = CRISIS_HANDOVER_FILE . ' as shf';
            $params['match_and'] = 'shf.crisis_handover_id=' . $id . '';
            $cost_files = $this->common_model->get_records_array($params);
          if (count($cost_files) > 1) {
           $path = array();
              foreach ($cost_files as $value) {
                $files[] = $value['file_path'] . '' . $value['file_name'];  
              }
            $this->load->library('zip');
            foreach ($files as $file) {
                $this->zip->read_file($file);
            }
            $this->zip->download('attachment.zip');
            }else{
                $pth = file_get_contents($cost_files[0]['file_path'] . '/' . $cost_files[0]['file_name']);
                $this->load->helper('download');
                force_download($cost_files[0]['file_name'], $pth);
            }
            redirect($this->module);
      }
  }
    
     /*
      @Author : Ritesh Rana
      @Desc   : edit staff functionality
      @Input  :
      @Output :
      @Date   : 26/03/2017
    */
    public function editstaff($staff_id) {
        $data['footerJs'][0] = base_url('uploads/custom/js/dashboard/dashboard.js');
        $table = STAFF_NOTICES . ' as sn';
        $where = array("sn.staff_notices_id" => $staff_id);
        $fields = array("sn.staff_notices_id,sn.title,sn.notice,sn.created_by,sn.created_date,snu.file_path,snu.file_name,snu.file_id");
        $join_tables = array(STAFF_NOTICES_UPLOADS . ' as snu' => 'snu.staff_notices_id= sn.staff_notices_id');
        $data['editRecord'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
        $data['crnt_view'] = $this->module;
        $data['form_action_path'] = $this->module . '/updateStaffNotices/';
        $data['main_content'] = '/editStaff';
        $this->load->view('/editStaff', $data);
    }

    /*
	 @Author : Ritesh rana
	 @Desc   : Staff Notices List Delete Query
	 @Input 	: Post id from List page
	 @Output	: Delete data from database and redirect
	 @Date   : 26/09/2017
	 */
	public function deleteStaff($id) {
            //Delete Record From Database
			if (!empty($id)) {
                                $data = array('is_delete' => 1);
                                $where = array('staff_notices_id' => $id);
                                if ($this->common_model->update(STAFF_NOTICES, $data, $where)) {
                                    //Insert log activity
                                $activity = array(
                                  'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
                                  'module_name'         => STAFF_NOTICE_MODULE,
                                  'module_field_name'   => '',
                                  'type'                => 3
                                );
                                
                    log_activity($activity);
					$msg = $this->lang->line('Deleted_Staff_Notices_Successfully');
					$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
					unset($id);
				} else {
					// error
					$msg = $this->lang->line('error_msg');
					$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
				}
			}
		redirect('Dashboard');
	}
    
    
    /*
     @Author : Ritesh rana
     @Desc   : update Staff Notices functionality
     @Input     : Post id from List page
     @Output    : Delete data from database and redirect
     @Date   : 26/09/2017
     */
    public function updateStaffNotices() {
        //STAFF_NOTICES_UPLOADS 
        $main_user_data = $this->session->userdata('LOGGED_IN');
        $staff_id = $this->input->post('staff_notices_id');
        $data['title'] = $this->input->post('title');
        $data['notice'] = $this->input->post('notice');
        $data['created_by'] = $main_user_data['ID'];
        $data['created_date'] = datetimeformat();
        $where = array('staff_notices_id' => $staff_id);
        // Update form data into database
       $success = $this->common_model->update(STAFF_NOTICES, $data, $where);
        //Insert log activity
        $activity = array(
          'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
          'module_name'         => STAFF_NOTICE_MODULE,
          'module_field_name'   => '',
          'type'                => 2
        );
        
        log_activity($activity);
        $upload_dir = $this->config->item('staff_notices_img_url');
        if (!is_dir($upload_dir)) {
            //create directory
            mkdir($upload_dir, 0777, TRUE);
        }
        if (is_dir($upload_dir)) {
            /* image upload code */
            $file_name = array();
            $file_array1 = $this->input->post('file_data');

            $file_name = $_FILES['fileUpload']['name'];
            if (count($file_name) > 0 && count($file_array1) > 0) {
                $differentedImage = array_diff($file_name, $file_array1);
                foreach ($file_name as $file) {
                    if (in_array($file, $differentedImage)) {
                        $key_data[] = array_search($file, $file_name); // $key = 2;
                    }
                }
                if (!empty($key_data)) {
                    foreach ($key_data as $key) {
                        unset($_FILES['fileUpload']['name'][$key]);
                        unset($_FILES['fileUpload']['type'][$key]);
                        unset($_FILES['fileUpload']['tmp_name'][$key]);
                        unset($_FILES['fileUpload']['error'][$key]);
                        unset($_FILES['fileUpload']['size'][$key]);
                    }
                }
            }

            $file_array_delete = $this->input->post('deleted_images');
            if (!empty($file_array_delete)) {
                        $files = $_FILES;
                      $FileDataArr = array(); 

                         $input='fileUpload';

    $tmpFile = count($_FILES[$input]['name']);
    if ($tmpFile > 0 && $_FILES[$input]['name'][0] != NULL) {
        for ($i = 0; $i < $tmpFile; $i++) {
            $imgname = strtolower(end((explode(".",$files[$input]['name'][$i]))));
              $files[$input]['name'][$i];
            if(in_array($_FILES[$input]['name'][$i],$file_array_delete)){
                 $files[$input]['name'][$i];
                
                 unset($_FILES[$input]['name'][$i]);
                 unset($_FILES[$input]['type'][$i]);
                 unset($_FILES[$input]['tmp_name'][$i]);
                 unset($_FILES[$input]['error'][$i]);
                 unset($_FILES[$input]['size'][$i]);

            }
        
       }
   }

}

            $_FILES['fileUpload'] = $arr = array_map('array_values', $_FILES['fileUpload']);
            $data['lead_view'] = $this->viewname;
            $uploadData = uploadImage('fileUpload', $this->config->item('staff_notices_img_url'), $data['lead_view']);
            $Marketingfiles = array();
            foreach ($uploadData as $dataname) {
                $Marketingfiles[] = $dataname['file_name'];
            }
            $marketing_file_str = implode(",", $Marketingfiles);

            $file2 = $this->input->post('fileToUpload');
            if (!(empty($file2))) {
                $file_data = implode(",", $file2);
            } else {
                $file_data = '';
            }
            if (!empty($marketing_file_str) && !empty($file_data)) {
                $marketingdata['file'] = $marketing_file_str . ',' . $file_data;
            } else if (!empty($marketing_file_str)) {
                $marketingdata['file'] = $marketing_file_str;
            } else {
                $marketingdata['file'] = $file_data;
            }
            $marketingdata['file_name'] = $file_data;
            if ($marketingdata['file_name'] != '') {
                $explodedData = explode(',', $marketingdata['file_name']);

                foreach ($explodedData as $img) {
                    array_push($uploadData, array('file_name' => $img));
                }
            }
            $estFIles = array();
            if (count($uploadData) > 0) {
                foreach ($uploadData as $files) {
                    $estFIles[] = ['file_name' => $files['file_name'], 'file_path' => $this->config->item('staff_notices_img_url'), 'staff_notices_id' => $staff_id];
                }
            }
            if (count($estFIles) > 0) {
                if (!$this->common_model->insert_batch(STAFF_NOTICES_UPLOADS, $estFIles)) {
                    $this->session->set_flashdata('msg', lang('error'));
                    redirect($this->module); //Redirect On Listing page
                }
            }
            /**
             * SOFT DELETION CODE STARTS MAULIK SUTHAR
            */
            $softDeleteImagesArr = $this->input->post('softDeletedImages');
            $softDeleteImagesUrlsArr = $this->input->post('softDeletedImagesUrls');
            if (count($softDeleteImagesUrlsArr) > 0) {
                foreach ($softDeleteImagesUrlsArr as $urls) {
                    unlink(BASEPATH . '../' . $urls);
                }
            }

            if (count($softDeleteImagesUrlsArr) > 0) {
                $dlStr = implode(',', $softDeleteImagesArr);
                $this->common_model->delete(STAFF_NOTICES_UPLOADS, 'file_id IN(' . $dlStr . ')');
            }
        }
        /*
         * SOFT DELETION CODE ENDS
         */
        $data['crnt_view'] = $this->viewname;
        if ($success) {
            $msg = $this->lang->line('Edit_Staff_Notices_Successfully');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {    // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }
        redirect('Dashboard'); //Redirect On Listing page
    }

}
