<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DropDownController extends CI_Controller {

    function __construct() {
        parent::__construct();
        check_admin_login();
        $this->type = "Admin";
        $this->viewname = $this->uri->segment(2);
        $this->load->library(array('form_validation'));
    }

    /*
      @Author : Dhara Bhalala
      @Desc   : Listing form
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
            $this->session->unset_userdata('dropdown_data');
        }

        $searchsort_session = $this->session->userdata('dropdown_data');
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
                $sortfield = 'dropdown_id';
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
        $table = AAI_DROPDOWN . ' as ch';
        $where = array("ch.status !=" => "2");
        $wehere_not_in = array();
        $fields = array("l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname,ch.dropdown_id,ch.title,ch.prefix,ch.status");
        $join_tables = array(LOGIN . ' as l' => 'ch.created_by=l.login_id');

        if (!empty($searchtext)) {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search = '(
                                (CONCAT(`firstname`, \' \', `lastname`) LIKE "%' . $searchtext . '%" 
                                    OR ch.title LIKE "%' . $searchtext . '%" 
                                    OR ch.prefix LIKE "%' . $searchtext . '%" 
                                    OR l.firstname LIKE "%' . $searchtext . '%" 
                                    OR l.lastname LIKE "%' . $searchtext . '%" 
                                    OR ch.status LIKE "%' . $searchtext . '%"
                                )
                            AND ch.status != "2")';

            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search, '', '', '', '', $wehere_not_in);

            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1', '', $wehere_not_in);
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where, '', '', '', '', $wehere_not_in);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1', '', $wehere_not_in);
        }

        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        $data['footerJs'][0] = base_url('uploads/custom/js/ai_dropdown/ai_dropdown.js');
        $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
        $sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uri_segment,
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('dropdown_data', $sortsearchpage_data);
        setActiveSession('dropdown_data');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->type . '/' . $this->viewname . '/ajax_list', $data);
        } else {
            $data['main_content'] = $this->type . '/' . $this->viewname . '/list';
            $this->load->view($this->type . '/assets/template', $data);
        }
    }

    public function view($id) {
        if (is_numeric($id)) {
            $data['footerJs'][0] = base_url('uploads/custom/js/ai_dropdown/ai_dropdown.js');
            
            $this->session->unset_userdata('FORM_SECRET', '');
            $table = AAI_DROPDOWN . ' as ch';
            $match = "ch.dropdown_id = '" . $id . "' AND ch.status !=2 ";
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

    /*
      @Author : Dhara Bhalala
      @Desc   : User Form validation
      @Input 	:
      @Output	:
      @Date   : 28-11-2018
     */

    public function formValidation($id = null) {
        $this->form_validation->set_rules('title', 'title', 'trim|required|min_length[2]|max_length[100]|xss_clean');
    }

    /*
      Author : Dhara Bhalala
      Desc  : Add Template
      Input  :
      Output :
      Date   :28-11-2018
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
            $data['footerJs'][0] = base_url('uploads/custom/js/ai_dropdown/ai_dropdown.js');
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
      @Author : Dhara Bhalala
      @Desc   : Insert Data
      @Input 	:
      @Output	:
      @Date   : 28-11-2018
     */

    public function insertData() {
        $data = array(
            'title' => $this->input->post('title'),
            'prefix' => $this->input->post('prefix'),
            'status' => $this->input->post('status'),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id']
        );
        // Insert query
        if ($this->common_model->insert(AAI_DROPDOWN, $data)) {
            //if (true) {
            $msg = 'Dropdown has been added successfully.';
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {
            // error
            $msg = 'Something went wrong. Please try after sometime.'; //$this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }

        redirect(ADMIN_SITE . '/' . $this->viewname);
    }

    /*
      @Author : Dhara Bhalala
      @Desc   : Edit Function
      @Input  : template id
      @Output :
      @Date   : 28-11-2018
     */

    public function edit($id) {
        $this->formValidation($id);
        if ($this->form_validation->run() == FALSE) {
            $data['footerJs'][0] = base_url('uploads/custom/js/ai_dropdown/ai_dropdown.js');            
            
            $this->session->unset_userdata('FORM_SECRET', '');
            $table = AAI_DROPDOWN . ' as ch';
            $match = "ch.dropdown_id = '" . $id . "' AND ch.status !=2 ";
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
      @Author : Dhara Bhalala
      @Desc   : Update Data
      @Input 	: Template id
      @Output	:
      @Date   : 28-11-2018
     */

    public function updatedata($dropdown_id) {
        $status = "";
        if ($this->input->post('status') == "") {
            $status = $this->input->post('selected_status');
        } else {
            $status = $this->input->post('status');
        }

        $data = array(
            'title' => $this->input->post('title'),
            'prefix' => $this->input->post('prefix'),
            'status' => $this->input->post('status'),
            'created_date' => datetimeformat(),
            'modified_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
            'modified_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );

        $id = $this->input->post('dropdown_id');
        //Update Record in Database
        $where = array('dropdown_id' => $id);

        // Update form data into database
        if ($this->common_model->update(AAI_DROPDOWN, $data, $where)) {
            //$msg = $this->lang->line('user_update_msg');
            $msg = 'Dropdown has been updated successfully.';
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {    // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }
        redirect(ADMIN_SITE . '/' . $this->viewname);
    }

    /*
      @Author : Dhara Bhalala
      @Desc   : Delete Data
      @Input 	: Template id
      @Output	:
      @Date   : 28-11-2018
     */

    public function deletedata($id) {

        if (!empty($id)) {
            $updateData['status'] = 2;
            $table = AAI_DROPDOWN;
            $strWhere = "dropdown_id =" . $id;
            $data['update_data'] = $this->common_model->update($table, $updateData, $strWhere);
            $msg = 'Dropdown Deleted Successfully';
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        } else {
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

}
