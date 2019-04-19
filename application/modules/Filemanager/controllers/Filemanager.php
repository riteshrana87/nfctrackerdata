<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Filemanager extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->viewname = $this->router->fetch_class();
        $this->method   = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Index Page loads view of the file manager
      @Input 	:
      @Output	:
      @Date   : 29/01/2017
     */
    public function index($id,$care_home_id=0,$past_care_id=0) {
            $this->viewname = 'Filemanager';
            $data['cur_viewname'] = 'Filemanager';
        
        $data['project_view'] = $this->viewname;
        // Make sure we have the correct directory
        $data['refresh'] = $data['parent'] = $directory = 'uploads/filemanager/';
        $directory = 'uploads/filemanager/';

        
            if ($this->input->get('dir') == 'uploads/') {
                $dir = 'uploads/filemanager/';
            } else {
                $dir = $this->input->get('dir');
            }

            if ($this->input->get('modal') && $this->input->get('dir') == 'uploads/') {
                if ($dir && $dir != '' && $dir != 'uploads/filemanager' && $dir != 'uploads/filemanager/' && $dir != '.') {
                    $directory = $dir . '/';
                    $data['refresh'] = $directory = $dir . '/';
                    $data['parent'] = dirname($directory);
                }
            } else {
                if (($this->input->get('dir')) && $this->input->get('dir') != '' && $this->input->get('dir') != 'uploads/filemanager' && $this->input->get('dir') != 'uploads/filemanager/' && $this->input->get('dir') != '.') {

                    $data['refresh'] = $directory = $this->input->get('dir') . '/';
                    $data['parent'] = dirname($directory);
                }
            }
        
        $data['module'] = 'filemanager';
            if ($this->input->get('module') && $this->input->get('module') == 'filemanager') {
                $data['module'] = $this->input->get('module');
                $cond = 1;
                $data['refresh'] = $data['parent'] = $directory = 'uploads/';
                $directory = 'uploads/';
                if (($this->input->get('dir')) && $this->input->get('dir') != 'uploads' && $this->input->get('dir') != 'uploads/filemanager' && $this->input->get('dir') != "'uploads/filemanager/Project0' . $id . '/'" && $this->input->get('dir') != '.') {
                    $directory = $this->input->get('dir') . '/';
                    $data['refresh'] = $directory = $this->input->get('dir') . '/';
                    $data['parent'] = dirname($directory);
                }
            } else {
                $data['refresh'] = $data['parent'] = $directory = 'uploads/filemanager/Project0' . $id . '/';
                $directory = 'uploads/filemanager/Project0' . $id . '/';
                if (($this->input->get('dir')) && $this->input->get('dir') != 'uploads' && $this->input->get('dir') != 'uploads/filemanager' && $this->input->get('dir') != "'uploads/filemanager/Project0' . $id . '/'" && $this->input->get('dir') != '.') {
                    $directory = $this->input->get('dir') . '/';
                    $data['refresh'] = $directory = $this->input->get('dir') . '/';
                    $data['parent'] = dirname($directory);
                }
            }
        
        $ignoreFolders = array('uploads//assets', 'uploads//css', 'uploads//custom', 'uploads//dist', 'uploads//font-awesome-4.5.0', 'uploads/filemanager//index.html', 'uploads///index.html');
        $filter_name = null;
        $data['images'] = array();
        // Get directories
        $directory = rawurldecode($directory);
        $directories = glob($directory . '/' . '*', GLOB_ONLYDIR);
        if (!$directories) {
            $directories = array();
        }
        // Get files
        $files = glob($directory . '/' . $filter_name . '*.{*}', GLOB_BRACE);
        if (!$files) {
            $files = array();
        }
        // Merge directories and files
        $images = array_merge($directories, $files);
        // Get total number of files and directories
        $image_total = count($images);
        if (count($images)) {

            foreach ($images as $image) {
                if (!in_array($image, $ignoreFolders)) {
                    $ext = pathinfo($image, PATHINFO_EXTENSION);
                    if ($ext != NULL) {
                        $data['images'][] = array('name' => basename($image), 'path' => $directory, 'href' => base_url($directory . basename($image)), 'type' => 'image', 'ext' => $ext);
                    } else {
                        $data['images'][] = array('name' => basename($image), 'path' => $directory . basename($image), 'href' => base_url($directory . basename($image)), 'type' => 'directory', 'ext' => $ext);
                    }
                }
            }
        }
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($id,$fields);
        $data['yp_id'] = $id;
        $data['care_home_id'] = $care_home_id;
        $data['past_care_id'] = $past_care_id;
		
		$data['footerCss']= array( 
								'0' => base_url('uploads/assets/css/simplelightbox.min.css'),
							);
        $data['footerJs']= array( 
								'0' => base_url('uploads/custom/js/jquery-ui.min.js'),
								'1' => base_url('uploads/assets/js/simple-lightbox.min.js'),
							);
        
        $data['header'] = array('menu_module' => 'YoungPerson');
        if ($this->input->get('modal')) {
            $this->load->view('Imagemanagerpopup', $data);
        } else {
            $data['drag'] = true;
            $data['main_content'] = '/Imagemanager.php';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Used to create new directory
      @Input 	:
      @Output	: make Dir
      @Date   : 02/02/2017
    */
    public function makeDir() {
        if (!$this->input->is_ajax_request()) {
            exit('no direct scripts are allowed');
        }
        $dir = $this->input->post('name') . '/';
        $path = $this->input->post('path');

        if (!empty($dir) && !empty($path)) {

            if (is_dir(BASEPATH . '../' . $path . $dir) === false) {

                mkdir(BASEPATH . '../' . $path . $dir, 0777, true);
                echo json_encode(['status' => 1]);
                die;
            } else {
                echo json_encode(['status' => 0]);
                die;
            }
        } else {
            echo json_encode(['status' => 0]);
            die;
        }
    }
    /*
      @Author : Ritesh rana
      @Desc   : Image Upload function
      @Input 	:
      @Output	: upload image 
      @Date   : 02/02/2017
    */
    public function upload() {

        $json = array();
        if (!$this->input->is_ajax_request()) {
            exit('no direct scripts allowed');
        }

        if ($this->input->get('path')) {

            if(!is_dir($this->input->get('path'))){
                mkdir($this->input->get('path'), 0777, true);
            }
            // Make sure we have the correct directory
            if (($this->input->get('path'))) {
                $directory = $this->input->get('path') . '/';
            } else {
                $directory = 'uploads/';
            }


            // Check its a directory
            if (!is_dir($directory)) {
                $json['error'] = $this->language->get('error_directory');
            }


            if (!$json) {
				
                $files = $_FILES;
                $config['upload_path'] = $directory;
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $tmpFile = (isset($_FILES['file'])) ? count($_FILES['file']['name']) : 0;

                if ($tmpFile > 0) {
                    for ($i = 0; $i < $tmpFile; $i++) {
                        $_FILES['file']['name'] = $files['file']['name'][$i];
                        $_FILES['file']['type'] = $files['file']['type'][$i];
                        $_FILES['file']['tmp_name'] = $files['file']['tmp_name'][$i];
                        $_FILES['file']['error'] = $files['file']['error'][$i];
                        $_FILES['file']['size'] = $files['file']['size'][$i];

                        $this->load->library('upload', $config);
						
                        if ($this->upload->do_upload('file')) {
                            $json['success'] = 'Uploaded';
                        } else {
                            $json['error'] = $this->upload->display_errors();
                        }
                    }
                } else {
                    $json['error'] = lang('fail_file_upload');
                }
            }
            echo json_encode($json);
        }
    }
/*
      @Author : Ritesh rana
      @Desc   : Image Delete function
      @Input    :
      @Output   : delete image
      @Date   : 02/02/2017
    */
    public function deleteImage() {
        if (!$this->input->is_ajax_request()) {
            exit('no direct scripts allowed');
        } else {
			
            if ($this->input->post('name')) {
				
                $images = $this->input->post('name');
				
                if (count($images) > 0) {
					
                    $this->load->helper("file");
					
                    foreach ($images as $img) {
						
                        if (unlink($img)) {
                            $stat = 1;
                        } else if (is_dir($img)) {
							
                            $this->emptyDir($img);
							
                            rmdir($img);
							
                            $stat = 1;
                        }else{
                            $stat = 0;
                        }
                    }
                    if ($stat == 1) {
                        echo json_encode(array('status' => 1, 'error' => ''));
                        die;
                    } else {
                        echo json_encode(array('status' => 0, 'error' => 'Error While Deletion!'));
                        die;
                    }
                }
            }
        }
    }

    /*
      @Author : Ritesh rana
      @Desc   : empty directory function
      @Input    : 
      @Output   : remove directory
      @Date   : 02/02/2017
    */
    function emptyDir($dir) {
    if (is_dir($dir)) {
        $scn = scandir($dir);
        foreach ($scn as $files) {
            if ($files !== '.') {
                if ($files !== '..') {
                    if (!is_dir($dir . '/' . $files)) {
                        unlink($dir . '/' . $files);
                    } else {
                        emptyDir($dir . '/' . $files);
                        rmdir($dir . '/' . $files);
                    }
                }
            }
        }
    }
}

/*
      @Author : Ritesh rana
      @Desc   : loadAjax View function
      @Input    :
      @Output   :
      @Date   : 02/02/2017
    */
    function loadAjaxView() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct scripts are allowed');
        }
        $ignoreFolders = array('uploads//assets', 'uploads//css', 'uploads//custom', 'uploads//dist', 'uploads//font-awesome-4.5.0', 'uploads/filemanager//index.html');

        $data['yp_id'] = $this->input->get('yp_id');
        $getData = $this->input->get();
        if(isset($getData['care_home_id']) && isset($getData['care_home_id'])){
            $data['care_home_id'] = $getData['care_home_id'];
            $data['past_care_id'] = $getData['past_care_id'];    
        }

        $data['project_view'] = $this->viewname;
        // Make sure we have the correct directory
        $data['refresh'] = $data['parent'] = $directory = 'uploads/filemanager/';
        $directory = 'uploads/filemanager/';
        if ($this->input->get('dir') == 'uploads/') {
            $dir = 'uploads/filemanager';
        } else {
            $dir = $this->input->get('dir');
        }
        if ($dir && $dir != '' && $dir != 'uploads/filemanager' && $dir != 'uploads/filemanager/' && $dir != '.') {
            $directory = $dir . '/';
            $data['refresh'] = $directory = $dir . '/';
            $data['parent'] = dirname($directory);
        }
            $data['refresh'] = $data['parent'] = $directory = 'uploads/filemanager/Project0' . $data['yp_id'] . '/';
            $directory = 'uploads/filemanager/Project0' . $data['yp_id'] . '/';
            if (($this->input->get('dir')) && $this->input->get('dir') != 'uploads' && $this->input->get('dir') != 'uploads/filemanager' && $this->input->get('dir') != 'uploads/filemanager/Project0' . $data['yp_id'] . '/' && $this->input->get('dir') != '.') {
                $directory = $this->input->get('dir') . '/';
                $data['refresh'] = $directory = $this->input->get('dir') . '/';
                $data['parent'] = dirname($directory);
            }
        $filter_name = null;
        $data['images'] = array();
        $directory = rawurldecode($directory);
        // Get directories
        $directories = glob($directory . '/' . '*', GLOB_ONLYDIR);
        if (!$directories) {
            $directories = array();
        }
        // Get files
        $files = glob($directory . '/' . $filter_name . '*.{*}', GLOB_BRACE);
        if (!$files) {
            $files = array();
        }

        // Merge directories and files

        $images = array_merge($directories, $files);
        // Get total number of files and directories
        $image_total = count($images);
        if (count($images)) {
            foreach ($images as $image) {
                if (!in_array($image, $ignoreFolders)) {
                    $ext = pathinfo($image, PATHINFO_EXTENSION);
                    if ($ext != NULL) {
                        $data['images'][] = array('name' => basename($image), 'path' => $directory, 'href' => base_url($directory . basename($image)), 'type' => 'image', 'ext' => $ext);
                    } else {
                        $data['images'][] = array('name' => basename($image), 'path' => $directory . basename($image), 'href' => base_url($directory . basename($image)), 'type' => 'directory', 'ext' => $ext);
                    }
                }
            }
        }
        $this->load->view('Filemanager/Ajaxview', $data);
    }

 /*
      @Author : Ritesh Rana
      @Desc   : Download all image functionality
      @Input    : ypid
      @Output   : download file
      @Date   : 21/03/2018
     */

function download($yp_id) {
        $rootPath = FCPATH . 'uploads/filemanager/Project0' . $yp_id;
        if (!file_exists($rootPath)) {
          
        $msg = "There is a no file.";
        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
     
        redirect('YoungPerson/view/' . $yp_id);
}else{
        // Initialize archive object
        $zip = new ZipArchive();
        $zip->open('file.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
        
        // Create recursive directory iterator
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
       
        $data = array();
        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                array_push($data, $filePath);
            }
        }
        
        if (!empty($data)) {
                        $this->load->library('zip');

                        foreach ($data as $file) {
                            $this->zip->read_file($file);
                        }
                        $this->zip->download('images.zip');
                    }

            }
        }
}
