<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CareHome extends CI_Controller {

    function __construct() {
        parent::__construct();
        check_admin_login();
        $this->type = "Admin";
        $this->viewname = $this->uri->segment(2);
        $this->load->library(array('form_validation'));
        $this->load->model('imageupload_model');
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Customers Listing form
      @Input  :
      @Output :
      @Date   : 12-06-2017
     */

    public function index($page = '') {

        $cur_uri = explode('/', $_SERVER['PATH_INFO']);
        $cur_uri_segment = array_search($page, $cur_uri);
        $searchtext = $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = $this->input->post('perpage');
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('care_home_data');
        }

        $searchsort_session = $this->session->userdata('care_home_data');
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
                $sortfield = 'login_id';
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
        $config['base_url'] = base_url() . $this->type . '/' . $this->viewname . '/index';
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        //Query
        $user_data = $this->session->userdata('nfc_admin_session');
        $table = CARE_HOME . ' as ch';
        $where = array("ch.is_delete" => "0");
        $wehere_not_in = array();
        $fields = array("l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname, ch.status,ch.care_home_name,ch.care_home_id");
        $join_tables = array(LOGIN . ' as l' => 'ch.created_by=l.login_id');

        if (!empty($searchtext)) {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search = '
					(
						(CONCAT(`firstname`, \' \', `lastname`) LIKE "%' . $searchtext . '%" 
							OR ch.care_home_name LIKE "%' . $searchtext . '%" 
							OR l.firstname LIKE "%' . $searchtext . '%" 
							OR l.lastname LIKE "%' . $searchtext . '%" 
							OR ch.status LIKE "%' . $searchtext . '%"
						)
					
					AND ch.is_delete = "0")';

            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search, '', '', '', '', $wehere_not_in);

            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1', '', $wehere_not_in);
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where, '', '', '', '', $wehere_not_in);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1', '', $wehere_not_in);
        }
        
        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        $data['footerJs'][0] = base_url('uploads/custom/js/care_home/care_home.js');
        $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
        $sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uri_segment,
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('care_home_data', $sortsearchpage_data);
        setActiveSession('care_home_data');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->type . '/' . $this->viewname . '/ajax_list', $data);
        } else {
            $data['main_content'] = $this->type . '/' . $this->viewname . '/list';
            $this->load->view($this->type . '/assets/template', $data);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : User Form validation
      @Input 	:
      @Output	:
      @Date   : 11-06-2017
     */

    public function formValidation($id = null) {
        $this->form_validation->set_rules('care_home_name', 'care home name', 'trim|required|min_length[2]|max_length[100]|xss_clean');
    }

    function phone_valid($str) {
        return preg_match('/^[\d\+\-\.\(\)\/\s]*$/', $str);
    }

    /*
      Author : Ritesh Rana
      Desc  : Add cutosmer
      Input  :
      Output :
      Date   :11-06-2017
     */

    public function add() {
        $this->formValidation(''); // form Validation fields

        if ($this->form_validation->run() == FALSE) {

            $data['validation'] = validation_errors();

            $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
            $data['form_action_path'] = ADMIN_SITE . '/' . $this->viewname . '/add';
            $data['main_content'] = $this->viewname . '/addEdit';
            $data['screenType'] = 'add';
            $data['id'] = '';
            $data['footerJs'][0] = base_url('uploads/assets/js/bootstrap-datetimepicker.min.js');
            $data['footerJs'][1] = base_url('uploads/custom/js/care_home/care_home.js');
            $main_user_data = $this->session->userdata('nfc_admin_session');
            $data['userType'] = getUserType($main_user_data['admin_type']);
            //$this->load->view(ADMIN_SITE . '/assets/template', $data);
            $this->parser->parse(ADMIN_SITE . '/assets/template', $data);
        } else {
            //success form
            $this->insertData();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Customer Insert Data
      @Input 	:
      @Output	:
      @Date   : 11-06-2017
     */

    public function insertData() {
        $upload_dir = $this->config->item('yp_care_home_img_url');
        if (!is_dir($upload_dir)) {
            //create directory
            mkdir($upload_dir, 0777, TRUE);
        }
        $estFIles['profile_img'] = '';
        if (is_dir($upload_dir)) {
            /* image upload code */
            $file_name = $_FILES['fileUpload']['name'];

            if (!empty($_FILES['fileUpload']['name'])) {
                $oldbookimg = $this->input->post('fileUpload'); //new add
                $bgImgPath = $this->config->item('yp_care_home_img_url');
                $uploadFile = 'fileUpload';
                $thumb = "thumb";
                $hiddenImage = !empty($oldbookimg) ? $oldbookimg : '';
                $estFIles['profile_img'] = $this->imageupload_model->upload_image($uploadFile, $bgImgPath, $thumb, TRUE, 'gif|jpg|png|jpeg');
            }
        }

        $data = array(
            'ofsted_inspection_grade' => $this->input->post('ofsted_inspection_grade'),
            'last_ofsted_inspection_date' => dateformat($this->input->post('last_ofsted_inspection_date')),
            'sc_number' => $this->input->post('sc_number'),
            'managers_name' => $this->input->post('managers_name'),
            'managers_email' => $this->input->post('managers_email'),
            'team_leaders_name' => $this->input->post('team_leaders_name'),
            'tls_email' => $this->input->post('tls_email'),
            'care_home_name' => $this->input->post('care_home_name'),
            'address' => $this->input->post('address'),
            'town' => $this->input->post('town'),
            'profile_img' => $estFIles['profile_img'],
            'county' => $this->input->post('county'),
            'postcode' => $this->input->post('postcode'),
            'city' => $this->input->post('city'),
            'contact_number' => $this->input->post('contact_number'),
            'care_home_email' => $this->input->post('care_home_email'),
            'created_date' => datetimeformat(),
            'modified_date' => datetimeformat(),
            'status' => $this->input->post('status'),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
            'updated_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );

        // Insert query
        if ($this->common_model->insert(CARE_HOME, $data)) {
            //if (true) {
            $msg = 'Care home has been added successfully.';
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {
            // error
            $msg = 'Something went wrong. Please try after sometime.'; //$this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }

        redirect(ADMIN_SITE . '/' . $this->viewname);
    }

    /*
      @Author : Ritesh Rana
      @Desc   : User Edit Function
      @Input  : User
      @Output :
      @Date   : 11-06-2017
     */

    public function edit($id) {
        $this->formValidation($id);
        if ($this->form_validation->run() == FALSE) {
            $data['footerJs'][0] = base_url('uploads/assets/js/bootstrap-datetimepicker.min.js');
            $data['footerJs'][1] = base_url('uploads/custom/js/care_home/care_home.js');
            //Get Records From Login Table
            // Put your form submission code here after processing the form data, unset the secret key from the session /
            $this->session->unset_userdata('FORM_SECRET', '');
            $table = CARE_HOME . ' as ch';
            $match = "ch.care_home_id = '" . $id . "' AND ch.is_delete=0 ";
            $fields = array("ch.*");
            $data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);


            $data['id'] = $id;
            $main_user_data = $this->session->userdata('nfc_admin_session');
            $data['userType'] = getUserType($main_user_data['admin_type']);

            $data['validation'] = validation_errors();
            $data['header'] = array('menu_module' => 'masters', 'menu_child' => 'user');
            $data['crnt_view'] = $this->viewname;
            $data['form_action_path'] = ADMIN_SITE . '/' . $this->viewname . '/edit/' . $id;
            $data['main_content'] = $this->viewname . '/addEdit';
            if (isset($data['editRecord'][0]['role_id'])) {
                $roleName = getRoleName($data['editRecord'][0]['role_id']);
                $data['roleName'] = $roleName[0]['role_name'];
            }
            $this->parser->parse(ADMIN_SITE . '/assets/template', $data);
        } else {
            $this->updatedata($id);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Customer Update Data
      @Input 	: CustomerId
      @Output	:
      @Date   : 11-06-2017
     */

    public function updatedata($care_home_id) {
        $status = "";
        if ($this->input->post('status') == "") {
            $status = $this->input->post('selected_status');
        } else {
            $status = $this->input->post('status');
        }

        $upload_dir = $this->config->item('yp_care_home_img_url');
        if (!is_dir($upload_dir)) {
            //create directory
            mkdir($upload_dir, 0777, TRUE);
        }

        $data = array(
            'care_home_name' => $this->input->post('care_home_name'),
            'address' => $this->input->post('address'),
            'town' => $this->input->post('town'),
            //'profile_img' => $estFIles['profile_img'],
            'county' => $this->input->post('county'),
            'postcode' => $this->input->post('postcode'),
            'city' => $this->input->post('city'),
            'contact_number' => $this->input->post('contact_number'),
            'care_home_email' => $this->input->post('care_home_email'),
            'created_date' => datetimeformat(),
            'modified_date' => datetimeformat(),
            'status' => $this->input->post('status'),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
            'updated_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        if (is_dir($upload_dir)) {
            /* image upload code */
            $file_name = $_FILES['fileUpload']['name'];
            if (!empty($_FILES['fileUpload']['name'])) {
                $oldbookimg = $this->input->post('fileUpload'); //new add
                $bgImgPath = $this->config->item('yp_care_home_img_url');
                $uploadFile = 'fileUpload';
                $thumb = "thumb";
                $hiddenImage = !empty($oldbookimg) ? $oldbookimg : '';
                $estFIles['profile_img'] = $this->imageupload_model->upload_image($uploadFile, $bgImgPath, $thumb, TRUE, 'gif|jpg|png|jpeg');
                $data['profile_img'] = $estFIles['profile_img'];
            }
        }

        $id = $this->input->post('care_home_id');

        /*
          $data['id'] = $care_home_id;
          $data['userType'] = getUserType();
          $data['crnt_view'] = $this->viewname;
         */
        //Update Record in Database
        $where = array('care_home_id' => $id);

        // Update form data into database
        if ($this->common_model->update(CARE_HOME, $data, $where)) {
            //$msg = $this->lang->line('user_update_msg');
            $msg = 'Care Home has been updated successfully.';
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {    // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }
        redirect(ADMIN_SITE . '/' . $this->viewname);
    }

    public function view($id) {
        if (is_numeric($id)) {
            $data['footerJs'][0] = base_url('uploads/assets/js/bootstrap-datetimepicker.min.js');
            $data['footerJs'][1] = base_url('uploads/custom/js/care_home/care_home.js');
            //Get Records From Login Table
            // Put your form submission code here after processing the form data, unset the secret key from the session /
            $this->session->unset_userdata('FORM_SECRET', '');
            $table = CARE_HOME . ' as ch';
            $match = "ch.care_home_id = '" . $id . "' AND ch.is_delete=0 ";
            $fields = array("ch.*");
            $data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
            if (empty($data['editRecord'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect(ADMIN_SITE . '/' . $this->viewname);
            }
            $data['id'] = $id;
            $data['validation'] = validation_errors();
            $data['header'] = array('menu_module' => 'masters', 'menu_child' => 'user');
            $data['crnt_view'] = $this->viewname;
            $data['form_action_path'] = ADMIN_SITE . '/' . $this->viewname . '/edit/' . $id;
            $data['main_content'] = $this->viewname . '/addEdit';
            $data['readonly'] = array("disabled" => "disabled");
            $this->parser->parse(ADMIN_SITE . '/assets/template', $data);
        } else {
            show_404();
        }
    }

    private function sendMailToCustomer($data = array(), $randomPassword = '') {

        if (!empty($data) && !empty($randomPassword)) {

            /* Send Created Customer password with Link */
            $toEmailId = $data['email'];
            $customerName = $data['first_name'] . ' ' . $data['last_name'];
            $loginLink = base_url('login/');

            $find = array(
                '{NAME}',
                '{EMAIL}',
                '{PASSWORD}',
                '{LINK}',
            );

            $replace = array(
                'NAME' => $customerName,
                'EMAIL' => $toEmailId,
                'PASSWORD' => $randomPassword,
                'LINK' => $loginLink,
            );

            $emailSubject = 'Welcome to NFCTracker';
            $emailBody = '<div>'
                    . '<p>Hello {NAME} ,</p> '
                    . '<p>Your credentials are as below:</p> '
                    . '<p>Email : {EMAIL} </p> '
                    . '<p>Password : {PASSWORD}</p> '
                    . '<p>Please click onm below link:</p> '
                    . '<p><a herf= "{LINK}">{LINK} </a> </p> '
                    . '<div>';


            $finalEmailBody = str_replace($find, $replace, $emailBody);

            return $this->common_model->sendEmail($toEmailId, $emailSubject, $finalEmailBody, FROM_EMAIL_ID);
        }
        return true;
    }

    public function UserJobInfo($page = '') {
        $cur_uri = explode('/', $_SERVER['PATH_INFO']);
        $cur_uri_segment = array_search($page, $cur_uri);
        $searchtext = $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = $this->input->post('perpage');
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('user_jobinfo_data');
        }

        $searchsort_session = $this->session->userdata('user_jobinfo_data');
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
                $sortfield = 'login_id';
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
        $config['base_url'] = base_url() . $this->type . '/' . $this->viewname . '/index';
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 5;
            $uri_segment = $this->uri->segment(5);
        }
        //Query
        $user_data = $this->session->userdata('nfc_admin_session');
        $table = LOGIN . ' as l';
        $where = array("l.is_delete" => "0");
        $wehere_not_in = array("l.role_id" => $user_data['admin_type']);
        $fields = array("l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname, l.email, l.password, l.address, l.mobile_number, rm.role_name, l.created_date, l.status,l.role_id as role_type");
        $join_tables = array(ROLE_MASTER . ' as rm' => 'rm.role_id=l.role_id');

        if (!empty($searchtext)) {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search = '((CONCAT(`firstname`, \' \', `lastname`) LIKE "%' . $searchtext . '%" OR l.firstname LIKE "%' . $searchtext . '%" OR l.lastname LIKE "%' . $searchtext . '%" OR l.email LIKE "%' . $searchtext . '%" OR l.mobile_number LIKE "%' . $searchtext . '%" OR rm.role_name LIKE "%' . $searchtext . '%" OR l.status LIKE "%' . $searchtext . '%")AND l.is_delete = "0")';

            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search, '', '', '', '', $wehere_not_in);

            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1', '', $wehere_not_in);
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where, '', '', '', '', $wehere_not_in);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1', '', $wehere_not_in);
        }
        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        $data['footerJs'][0] = base_url('uploads/custom/js/care_home/care_home.js');
        $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
        $sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uri_segment,
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('user_jobinfo_data', $sortsearchpage_data);

        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->type . '/' . $this->viewname . '/jobinfo_ajax_list', $data);
        } else {
            $data['main_content'] = $this->type . '/' . $this->viewname . '/jobinfo_list';
            $this->load->view($this->type . '/assets/template', $data);
        }
    }

    public function upload_file($fileext = '') {
        $str = file_get_contents('php://input');
        echo $filename = time() . uniqid() . "." . $fileext;
        file_put_contents($this->config->item('yp_care_home_img_url') . '/' . $filename, $str);
    }

    // delete care home
    public function deletedata($id) {

        if (!empty($id)) {
            $updateData['is_delete'] = 1;
            $table = CARE_HOME;
            $strWhere = "is_delete = 0 and care_home_id =" . $id;
            $data['update_data'] = $this->common_model->update($table, $updateData, $strWhere);
            $msg = $this->lang->line('delete_data');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        } else {
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Check Duplicate Care home name allow
      @Input 	:
      @Output	:
      @Date   : 6th Dec 2017
     */

    public function isDuplicateCareHomeName() {

        $isduplicate = 0;
        $carehomeName = trim($this->input->post('care_home_name'));
        $edit_id = trim($this->input->post('edit_id'));

        if (!empty($carehomeName)) {

            $tableName = CARE_HOME;
            $fields = array('COUNT(care_home_id) AS cntData');
            if (!empty($edit_id)) {
                $match = array('care_home_name' => $carehomeName, 'care_home_id <>' => $edit_id);
            } else {
                $match = array('care_home_name' => $carehomeName);
            }
            $duplicateEmail = $this->common_model->get_records($tableName, $fields, '', '', $match);

            if ($duplicateEmail[0]['cntData'] > 0) {
                $isduplicate = 1;
            } else {
                $isduplicate = 0;
            }
        }

        echo $isduplicate;
    }

}
