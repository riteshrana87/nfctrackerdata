<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AdminAAI extends CI_Controller {

    function __construct() {
        parent::__construct();

        check_admin_login();
        $this->type = "Admin";
        $this->viewname = $this->uri->segment(2);
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Dhara Bhalala
      @Desc   : Index Page
      @Date   : 17/12/2018
     */

//    public function index() { }
    
    /*
      @Author : Dhara Bhalala
      @Desc   : bamboo hr users Listing
      @Input  :
      @Output :
      @Date   : 31-12-2018
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
            $this->session->unset_userdata('users_data');
        }

        $searchsort_session = $this->session->userdata('users_data');
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
                $sortfield = 'user_id';
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
        $table = BAMBOOHR_USERS . ' as ch';
        $wehere_not_in = array();
        $fields = array("ch.*");
        $where = array();
        $join_tables = array();

        if (!empty($searchtext)) {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search = '(ch.employee_json_data LIKE "%' . $searchtext . '%" 
                                    OR ch.first_name LIKE "%' . $searchtext . '%" 
                                    OR ch.last_name LIKE "%' . $searchtext . '%" 
                                    OR ch.email LIKE "%' . $searchtext . '%" 
                                )';

            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search, '', '', '', '', $wehere_not_in);

            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1', '', $wehere_not_in);
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where, '', '', '', '', $wehere_not_in);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1', '', $wehere_not_in);
        }

        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
//        $data['footerJs'][0] = base_url('uploads/custom/js/ai_dropdown/ai_dropdown.js');
        $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
        $sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uri_segment,
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('users_data', $sortsearchpage_data);
        setActiveSession('users_data');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->type . '/' . $this->viewname . '/ajax_list', $data);
        } else {
            $data['main_content'] = $this->type . '/' . $this->viewname . '/list';
            $this->load->view($this->type . '/assets/template', $data);
        }
    }
    
    public function viewUser($id) {
        
    }
    
    /*
      @Author : Dhara Bhalala
      @Desc   : Sync bambooHr Details
      @Input  :
      @Output :
      @Date   : 31-12-2018
     */
    public function syncBambooData() {
        $loggedInUser = $this->session->userdata['nfc_admin_session']['admin_id'];
       $apiKey = '4cf2fa4db6a143e49617b6c8c35d2a2f47354405';
        $url = 'https://api.bamboohr.com/api/gateway.php/nfccmetricsandbox/v1/employees/directory';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERPWD, "$apiKey" . ":" . "x");
        $result = curl_exec($ch);
        curl_close($ch);
        $obj = new SimpleXMLElement($result);
        $arr = json_decode(json_encode($obj), TRUE);
        
        $empDataArray = array();
        foreach ($arr['employees']['employee'] as $key => $employees_data) {

            $empDataArray[$key] = array_combine($arr['fieldset']['field'], $employees_data['field']);
            $empDataArray[$key]['emp_id'] = $employees_data['@attributes']['id'];
        }
        $this->common_model->delete(BAMBOOHR_USERS,'user_id > 0');
        $empArray = array();
        foreach ($empDataArray as $key => $value) {
            $empArray[$key+1]['user_id'] = $key+1;
            $empArray[$key+1]['employee_id'] = $value['emp_id'];
            $empArray[$key+1]['first_name'] = $value['First name'];
            $empArray[$key+1]['last_name'] = $value['Last name'];
            $empArray[$key+1]['email'] = $value['Work Email'];
            $empArray[$key+1]['work_location'] = !is_array($value['Division'])? $value['Division']:'';
            $empArray[$key+1]['job_title'] = !is_array($value['Job title'])? $value['Job title']:'';
            $empArray[$key+1]['employee_json_data'] = json_encode($value);
            $empArray[$key+1]['created_by'] = $loggedInUser;
            $empArray[$key+1]['created_date'] = datetimeformat(); 
        }
        $this->common_model->insert_batch(BAMBOOHR_USERS, $empArray);
        redirect(ADMIN_SITE . '/' . $this->viewname . '/index/');
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
      @Desc   : ADD Page
      @Date   : 17/12/2018
     */

    public function add() {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['crnt_view'] = $this->viewname;
            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            //$data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            //$data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
            $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['button_header'] = array('menu_module' => 'entry_form');
            $data['form_data'] = array('button_data' => 'active');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/add';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/add';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertModule();
        }
    }

   
/*
      @Author : Ritesh Rana
      @Desc   : Insert New Module Details
      @Input  :
      @Output :
      @Date   : 16/02/2019
     */

    public function insertModule() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = $jsonDataUpdated = array();
        $fields = array(
            'entry_form_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'incident_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE),
            'reporting_user' => array('type' => 'VARCHAR', 'constraint' => 10, 'unsigned' => TRUE),
            'created_by' => array('type' => 'INT', 'constraint' => 5, 'NOT NULL' => TRUE, 'unsigned' => TRUE),
            'created_date' => array('type' => 'DATETIME', 'unsigned' => TRUE),
            'modified_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE),
            'modified_date' => array('type' => 'DATETIME', 'unsigned' => TRUE));

        $n = 0;
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }

                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            $n++;
        }

       
        foreach ($jsonDataUpdated as $value) {
            if ($value['type'] != 'header') {
                if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                    if (isset($value['description'])) {
                        $fields += array(
                            $value['name'] => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE)
                        );
                    } else {
                        $fields += array(
                            $value['name'] => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE)
                        );
                    }
                } else if ($value['type'] == 'date') {
                    $fields += array(
                        $value['name'] => array('type' => 'DATE', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'number') {
                    $fields += array(
                        $value['name'] => array('type' => 'INT', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'text') {
                    if ($value['subtype'] == 'time') {
                        $fields += array(
                            $value['name'] => array('type' => 'TIME', 'unsigned' => TRUE, 'null' => TRUE),
                        );
                    } else {
                        $fields += array(
                            $value['name'] => array('type' => 'text', 'null' => TRUE),
                        );
                    }
                } else {
                    $fields += array(
                        $value['name'] => array('type' => 'text', 'null' => TRUE),
                    );
                }
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('entry_form_id', TRUE);
        if (!$this->db->table_exists('aai_entry_form')) {
            $this->dbforge->create_table('aai_entry_form');
        }
        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(AAI_FORM, $data_list)) {
            $aai_main_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/edit/' . $aai_main_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }



    /*
      @Author : Dhara Bhalala
      @Desc   : Form Edit Page
      @Date   : 17/12/2018
     */

    public function edit($id) {
        $formInfo = $this->common_model->get_records(AAI_FORM, '', '', '', array('form_id' => $id));
        if (!empty($formInfo)) {
            $this->formValidation();
            if ($this->form_validation->run() == FALSE) {
                if (is_numeric($id)) {
                    $data['id'] = $id;
                    $data['crnt_view'] = $this->viewname;
                    $table = AAI_FORM;
                    $match = "";
                    $fields = array("*");
                    $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
                    $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
                    $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
                    $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
                    $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
                    $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
                    $data['getFormData'] = $this->common_model->get_records($table, $fields, '', '', $match);
                    $data['button_header'] = array('menu_module' => 'entry_form');
                    $data['form_data'] = array('button_data' => 'active');
                    $data['form_action_path'] = $this->type . '/' . $this->viewname . '/edit/' . $id;
                    $data['main_content'] = $this->type . '/' . $this->viewname . '/edit';
                    $this->parser->parse($this->type . '/assets/template', $data);
                } else {
                    show_404();
                }
            } else {
                $this->updateModule();
            }
        } else {
            show_404();
        }
    }


     /*
      @Author : Ritesh Rana
      @Desc   : Module Master update Query
      @Input  : Post record from Module Master List
      @Output : Update data in database and redirect
      @Date   : 01/04/2019
     */

    public function updateModule() {
        $this->load->dbforge();
        $id = $this->input->post('form_id');
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $jsonDataUpdated = array();
        $n = 0;
        $fields = array();
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }
                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            if ($row['type'] != 'header') {
                $fields[] = str_replace('-', '_', $row['name']);
            }
            $n++;
        }

        $fields_data = array('entry_form_id','incident_id','reporting_user', 'created_by', 'created_date', 'modified_by', 'modified_date');

        $result = array_merge($fields_data, $fields);

        $val_data = $this->db->list_fields('aai_entry_form');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();

        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                foreach ($jsonData as $value) {
                    $field_name = str_replace('-', '_', $value['name']);
                    if ($diff_fields == $field_name) {
                        if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                            if (isset($value['description'])) {
                                $updated_fields += array($diff_fields => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE,),);
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                            }
                        } else if ($value['type'] == 'date') {
                            $updated_fields += array($diff_fields => array('type' => 'DATE', 'null' => TRUE,),);
                        } else if ($value['type'] == 'number') {
                            $updated_fields += array($diff_fields => array('type' => 'INT', 'null' => TRUE,),);
                        } else if ($value['type'] == 'text') {
                            if ($value['subtype'] == 'time') {
                                $updated_fields += array($diff_fields => array('type' => 'TIME', 'null' => TRUE,),);
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                            }
                        } else {
                            $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                        }
                    }
                }
            }
        }

        if ($this->db->table_exists('aai_entry_form')) {
            $this->dbforge->add_column('aai_entry_form', $updated_fields);
        }

        $valaa_data = $this->db->list_fields('aai_entry_form');
        $delete = array();
        foreach ($valaa_data as $val) {
            if (!in_array($val, $result)) {
                $delete[] = $val;
            }
        }

        foreach ($delete as $delete_val) {
            if ($this->db->field_exists($delete_val, 'aai_entry_form')) {
                $this->dbforge->drop_column('aai_entry_form', $delete_val);
            }
        }

        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        
        //Update Record in Database
        $where = array('form_id' => $id);
        if ($this->common_model->update(AAI_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/edit/' . $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/edit/' . $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname . '/edit/' . $id);
    }
    
    /*
      @Author : Dhara Bhalala
      @Desc   : ADD Page
      @Date   : 17/12/2018
     */

    public function addL1() {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['crnt_view'] = $this->viewname;
            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['button_header'] = array('menu_module' => 'l1');
            $data['form_data'] = array('button_data' => 'active');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addL1';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/addL1';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertL1Module();
        }
    }

    
    /*
      @Author : Ritesh Rana
      @Desc   : Insert New Module Details
      @Input  :
      @Output :
      @Date   : 16/02/2019
     */

    public function insertL1Module() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = $jsonDataUpdated = array();
        $fields = array(
            'l1_form_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'incident_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE),
            'l1_reference_number' => array('type' => 'VARCHAR', 'constraint' => 20, 'unsigned' => TRUE),
            'l1_total_duration' => array('type' => 'TIME', 'unsigned' => TRUE, 'null' => TRUE),
            'created_by' => array('type' => 'INT', 'constraint' => 5, 'NOT NULL' => TRUE, 'unsigned' => TRUE),
            'created_date' => array('type' => 'DATETIME', 'unsigned' => TRUE),
            'modified_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE),
            'modified_date' => array('type' => 'DATETIME', 'unsigned' => TRUE));
         
        $n = 0;
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }

                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            $n++;
        }

       
        foreach ($jsonDataUpdated as $value) {
            if ($value['type'] != 'header') {
                if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                    if (isset($value['description'])) {
                                if($value['className'] == 'bamboo_lookup_multiple'){
                                    $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);    
                                }else if($value['className'] == 'multiple'){
                                    $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                                }else{
                                    $fields += array($diff_fields => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE,),);    
                                }
                            } else {
                                $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                            }
                } else if ($value['type'] == 'date') {
                    $fields += array(
                        $value['name'] => array('type' => 'DATE', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'number') {
                    $fields += array(
                        $value['name'] => array('type' => 'INT', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'text') {
                    if ($value['subtype'] == 'time') {
                        $fields += array(
                            $value['name'] => array('type' => 'TIME', 'unsigned' => TRUE, 'null' => TRUE),
                        );
                    } else {
                        $fields += array(
                            $value['name'] => array('type' => 'text', 'null' => TRUE),
                        );
                    }
                } else {
                    $fields += array(
                        $value['name'] => array('type' => 'text', 'null' => TRUE),
                    );
                }
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('l1_form_id', TRUE);
        if (!$this->db->table_exists('aai_l1_form')) {
            $this->dbforge->create_table('aai_l1_form');
        }
        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(AAI_FORM, $data_list)) {
            $aai_main_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL1/' . $aai_main_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Dhara Bhalala
      @Desc   : Form Edit Page
      @Date   : 17/12/2018
     */

    public function editL1($id) {
        $formInfo = $this->common_model->get_records(AAI_FORM, '', '', '', array('form_id' => $id));
        if (!empty($formInfo)) {
            $this->formValidation();
            if ($this->form_validation->run() == FALSE) {
                if (is_numeric($id)) {
                    $data['id'] = $id;
                    $data['crnt_view'] = $this->viewname;
                    $table = AAI_FORM;
                    $match = "";
                    $fields = array("*");
                    $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
                    $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
                    $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
                    $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
                    $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
                    $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
                    $data['getFormData'] = $this->common_model->get_records($table, $fields, '', '', $match);
                    $data['button_header'] = array('menu_module' => 'l1');
                    $data['form_data'] = array('button_data' => 'active');
                    $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editL1/' . $id;
                    $data['main_content'] = $this->type . '/' . $this->viewname . '/editL1';
                    $this->parser->parse($this->type . '/assets/template', $data);
                } else {
                    show_404();
                }
            } else {
                $this->updateL1Module();
            }
        } else {
            show_404();
        }
    }

    
    /*
      @Author : Ritesh Rana
      @Desc   : Module Master update Query
      @Input  : Post record from Module Master List
      @Output : Update data in database and redirect
      @Date   : 01/04/2019
     */

    public function updateL1Module() {
        $this->load->dbforge();
        $id = $this->input->post('form_id');
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $jsonDataUpdated = array();
        $n = 0;
        $fields = array();
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }
                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            if ($row['type'] != 'header') {
                $fields[] = str_replace('-', '_', $row['name']);
            }
            $n++;
        }

        $fields_data = array('l1_form_id','incident_id','l1_reference_number', 'l1_total_duration', 'created_by', 'created_date', 'modified_by', 'modified_date');

        $result = array_merge($fields_data, $fields);

        $val_data = $this->db->list_fields('aai_l1_form');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();

        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                foreach ($jsonData as $value) {
                    $field_name = str_replace('-', '_', $value['name']);
                    if ($diff_fields == $field_name) {
                        if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                             if (isset($value['description'])) {
                                if($value['className'] == 'bamboo_lookup_multiple'){
                                    $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);    
                                }else if($value['className'] == 'multiple'){
                                    $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                                }else{
                                    $updated_fields += array($diff_fields => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE,),);    
                                }
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                            }
                        } else if ($value['type'] == 'date') {
                            $updated_fields += array($diff_fields => array('type' => 'DATE', 'null' => TRUE,),);
                        } else if ($value['type'] == 'number') {
                            $updated_fields += array($diff_fields => array('type' => 'INT', 'null' => TRUE,),);
                        } else if ($value['type'] == 'text') {
                            if ($value['subtype'] == 'time') {
                                $updated_fields += array($diff_fields => array('type' => 'TIME', 'null' => TRUE,),);
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                            }
                        } else {
                            $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                        }
                    }
                }
            }
        }

        if ($this->db->table_exists('aai_l1_form')) {
            $this->dbforge->add_column('aai_l1_form', $updated_fields);
        }

        $valaa_data = $this->db->list_fields('aai_l1_form');
        $delete = array();
        foreach ($valaa_data as $val) {
            if (!in_array($val, $result)) {
                $delete[] = $val;
            }
        }

        foreach ($delete as $delete_val) {
            if ($this->db->field_exists($delete_val, 'aai_l1_form')) {
                $this->dbforge->drop_column('aai_l1_form', $delete_val);
            }
        }

        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        
        //Update Record in Database
        $where = array('form_id' => $id);
        if ($this->common_model->update(AAI_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL1/' . $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL1/' . $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname . '/editL1/' . $id);
    }
    
    /*
      @Author : Dhara Bhalala
      @Desc   : ADD Page
      @Date   : 17/12/2018
     */

    public function addL2nL3() {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['crnt_view'] = $this->viewname;
            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            //$data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            //$data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
            $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['button_header'] = array('menu_module' => 'L2nL3');
            $data['form_data'] = array('button_data' => 'active');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addL2nL3';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/addL2nL3';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertL2nL3Module();
        }
    }
   
    /*
      @Author : Ritesh Rana
      @Desc   : Insert New Module Details
      @Input  :
      @Output :
      @Date   : 16/02/2019
     */

    public function insertL2nL3Module() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = $jsonDataUpdated = array();
        $fields = array(
            'l2_l3_form_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'incident_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE),
            'l2_l3_reference_number' => array('type' => 'VARCHAR', 'constraint' => 20, 'unsigned' => TRUE),
            'l2_total_duration' => array('type' => 'TIME', 'unsigned' => TRUE, 'null' => TRUE),
            'created_by' => array('type' => 'INT', 'constraint' => 5, 'NOT NULL' => TRUE, 'unsigned' => TRUE),
            'created_date' => array('type' => 'DATETIME', 'unsigned' => TRUE),
            'modified_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE),
            'modified_date' => array('type' => 'DATETIME', 'unsigned' => TRUE));

        $n = 0;
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }

                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            $n++;
        }

       
        foreach ($jsonDataUpdated as $value) {
            if ($value['type'] != 'header') {
                if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                     if (isset($value['description'])) {
                                if($value['className'] == 'bamboo_lookup_multiple'){
                                    $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);    
                                }else if($value['className'] == 'multiple'){
                                    $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                                }else{
                                    $fields += array($diff_fields => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE,),);    
                                }
                            } else {
                                $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                            }
                } else if ($value['type'] == 'date') {
                    $fields += array(
                        $value['name'] => array('type' => 'DATE', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'number') {
                    $fields += array(
                        $value['name'] => array('type' => 'INT', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'text') {
                    if ($value['subtype'] == 'time') {
                        $fields += array(
                            $value['name'] => array('type' => 'TIME', 'unsigned' => TRUE, 'null' => TRUE),
                        );
                    } else {
                        $fields += array(
                            $value['name'] => array('type' => 'text', 'null' => TRUE),
                        );
                    }
                } else {
                    $fields += array(
                        $value['name'] => array('type' => 'text', 'null' => TRUE),
                    );
                }
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('l2_l3_form_id', TRUE);
        if (!$this->db->table_exists('aai_l2_l3_form')) {
            $this->dbforge->create_table('aai_l2_l3_form');
        }
        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(AAI_FORM, $data_list)) {
            $aai_main_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL2nL3/' . $aai_main_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Dhara Bhalala
      @Desc   : Form Edit Page
      @Date   : 17/12/2018
     */

    public function editL2nL3($id) {
        $formInfo = $this->common_model->get_records(AAI_FORM, '', '', '', array('form_id' => $id));
        if (!empty($formInfo)) {
            $this->formValidation();
            if ($this->form_validation->run() == FALSE) {
                if (is_numeric($id)) {
                    $data['id'] = $id;
                    $data['crnt_view'] = $this->viewname;
                    $table = AAI_FORM;
                    $match = "";
                    $fields = array("*");
                    $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
                    $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
                    $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
                    $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
                    $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
                    $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
                    $data['getFormData'] = $this->common_model->get_records($table, $fields, '', '', $match);

                    $data['button_header'] = array('menu_module' => 'L2nL3');
                    $data['form_data'] = array('button_data' => 'active');
                    $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editL2nL3/' . $id;
                    $data['main_content'] = $this->type . '/' . $this->viewname . '/editL2nL3';
                    $this->parser->parse($this->type . '/assets/template', $data);
                } else {
                    show_404();
                }
            } else {
                $this->updateL2nL3Module();
            }
        } else {
            show_404();
        }
    }

    
     /*
      @Author : Ritesh Rana
      @Desc   : Module Master update Query
      @Input  : Post record from Module Master List
      @Output : Update data in database and redirect
      @Date   : 01/04/2019
     */

    public function updateL2nL3Module() {
        $this->load->dbforge();
        $id = $this->input->post('form_id');
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $jsonDataUpdated = array();
        $n = 0;
        $fields = array();
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }
                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            if ($row['type'] != 'header') {
                $fields[] = str_replace('-', '_', $row['name']);
            }
            $n++;
        }

        $fields_data = array('l2_l3_form_id','incident_id','l2_l3_reference_number','l2_total_duration','created_by','created_date','modified_by','modified_date');

        $result = array_merge($fields_data, $fields);

        $val_data = $this->db->list_fields('aai_l2_l3_form');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();

        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                foreach ($jsonData as $value) {
                    $field_name = str_replace('-', '_', $value['name']);
                    if ($diff_fields == $field_name) {
                        if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                            if (isset($value['description'])) {
                                if($value['className'] == 'bamboo_lookup_multiple'){
                                    $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);    
                                }else if($value['className'] == 'multiple'){
                                    $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                                }else{
                                    $updated_fields += array($diff_fields => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE,),);    
                                }
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                            }
                        } else if ($value['type'] == 'date') {
                            $updated_fields += array($diff_fields => array('type' => 'DATE', 'null' => TRUE,),);
                        } else if ($value['type'] == 'number') {
                            $updated_fields += array($diff_fields => array('type' => 'INT', 'null' => TRUE,),);
                        } else if ($value['type'] == 'text') {
                            if ($value['subtype'] == 'time') {
                                $updated_fields += array($diff_fields => array('type' => 'TIME', 'null' => TRUE,),);
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                            }
                        } else {
                            $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                        }
                    }
                }
            }
        }

        if ($this->db->table_exists('aai_l2_l3_form')) {
            $this->dbforge->add_column('aai_l2_l3_form', $updated_fields);
        }

        $valaa_data = $this->db->list_fields('aai_l2_l3_form');
        $delete = array();
        foreach ($valaa_data as $val) {
            if (!in_array($val, $result)) {
                $delete[] = $val;
            }
        }

        foreach ($delete as $delete_val) {
            if ($this->db->field_exists($delete_val, 'aai_l2_l3_form')) {
                $this->dbforge->drop_column('aai_l2_l3_form', $delete_val);
            }
        }

        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        
        //Update Record in Database
        $where = array('form_id' => $id);
        if ($this->common_model->update(AAI_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL2nL3/' . $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL2nL3/' . $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname . '/editL2nL3/' . $id);
    }
    
    /*
      @Author : Dhara Bhalala
      @Desc   : ADD Page
      @Date   : 17/12/2018
     */

    public function addL4() {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['crnt_view'] = $this->viewname;
            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            //$data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            //$data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
            $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['button_header'] = array('menu_module' => 'l4');
            $data['form_data'] = array('button_data' => 'active');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addL4';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/addL4';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertL4Module();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert Form Details
      @Input  :
      @Output :
      @Date   : 01/04/2019
     */

    
    public function insertL4Module() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = $jsonDataUpdated = array();
        $fields = array(
            'l4_form_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'incident_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE),
            'l4_reference_number' => array('type' => 'VARCHAR', 'constraint' => 20, 'unsigned' => TRUE),
            'l4_total_duration' => array('type' => 'VARCHAR','constraint' => 10,'unsigned' => TRUE),
            'calculate_notification_worker' => array('type' => 'VARCHAR', 'constraint' => 30, 'unsigned' => TRUE),
            'calculate_notification_missing' => array('type' => 'VARCHAR', 'constraint' => 30, 'unsigned' => TRUE),
            'created_by' => array('type' => 'INT', 'constraint' => 5, 'NOT NULL' => TRUE, 'unsigned' => TRUE),
            'created_date' => array('type' => 'DATETIME', 'unsigned' => TRUE),
            'modified_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE),
            'modified_date' => array('type' => 'DATETIME', 'unsigned' => TRUE));

        $n = 0;
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }

                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            $n++;
        }

       
        foreach ($jsonDataUpdated as $value) {
            if ($value['type'] != 'header') {
                if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                    if (isset($value['description'])) {
                                if($value['className'] == 'bamboo_lookup_multiple'){
                                    $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);    
                                }else if($value['className'] == 'multiple'){
                                    $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                                }else{
                                    $fields += array($diff_fields => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE,),);    
                                }
                            } else {
                                $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                            }
                } else if ($value['type'] == 'date') {
                    $fields += array(
                        $value['name'] => array('type' => 'DATE', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'number') {
                    $fields += array(
                        $value['name'] => array('type' => 'INT', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'text') {
                    if ($value['subtype'] == 'time') {
                        $fields += array(
                            $value['name'] => array('type' => 'TIME', 'unsigned' => TRUE, 'null' => TRUE),
                        );
                    } else {
                        $fields += array(
                            $value['name'] => array('type' => 'text', 'null' => TRUE),
                        );
                    }
                } else {
                    $fields += array(
                        $value['name'] => array('type' => 'text', 'null' => TRUE),
                    );
                }
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('l4_form_id', TRUE);
        if (!$this->db->table_exists('aai_l4_form')) {
            $this->dbforge->create_table('aai_l4_form');
        }
        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(AAI_FORM, $data_list)) {
            $aai_main_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL4/' . $aai_main_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Dhara Bhalala
      @Desc   : Form Edit Page
      @Date   : 17/12/2018
     */

    public function editL4($id) {
        $formInfo = $this->common_model->get_records(AAI_FORM, '', '', '', array('form_id' => $id));
        if (!empty($formInfo)) {
            $this->formValidation();
            if ($this->form_validation->run() == FALSE) {
                if (is_numeric($id)) {
                    $data['id'] = $id;
                    $data['crnt_view'] = $this->viewname;
                    $table = AAI_FORM;
                    $match = "";
                    $fields = array("*");
                    $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
                    $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
                    $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
                    $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
                    $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
                    $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
                    $data['getFormData'] = $this->common_model->get_records($table, $fields, '', '', $match);
                    $data['button_header'] = array('menu_module' => 'l4');
                    $data['form_data'] = array('button_data' => 'active');
                    $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editL4/' . $id;
                    $data['main_content'] = $this->type . '/' . $this->viewname . '/editL4';
                    $this->parser->parse($this->type . '/assets/template', $data);
                } else {
                    show_404();
                }
            } else {
                $this->updateL4Module();
            }
        } else {
            show_404();
        }
    }

   
     /*
      @Author : Ritesh Rana
      @Desc   : Module Master update Query
      @Input  : Post record from Module Master List
      @Output : Update data in database and redirect
      @Date   : 01/04/2019
     */

    public function updateL4Module() {
        $this->load->dbforge();
        $id = $this->input->post('form_id');
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $jsonDataUpdated = array();
        $n = 0;
        $fields = array();
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }
                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            if ($row['type'] != 'header') {
                $fields[] = str_replace('-', '_', $row['name']);
            }
            $n++;
        }
        
        $fields_data = array('l4_form_id','incident_id','l4_reference_number','l4_total_duration','calculate_notification_worker',
            'calculate_notification_missing', 'created_by', 'created_date', 'modified_by', 'modified_date');

        $result = array_merge($fields_data, $fields);

        $val_data = $this->db->list_fields('aai_l4_form');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();

        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                foreach ($jsonData as $value) {
                    $field_name = str_replace('-', '_', $value['name']);
                    if ($diff_fields == $field_name) {
                        if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                            if (isset($value['description'])) {
                                if($value['className'] == 'bamboo_lookup_multiple'){
                                    $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);    
                                }else if($value['className'] == 'multiple'){
                                    $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                                }else{
                                    $updated_fields += array($diff_fields => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE,),);    
                                }
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                            }
                        } else if ($value['type'] == 'date') {
                            $updated_fields += array($diff_fields => array('type' => 'DATE', 'null' => TRUE,),);
                        } else if ($value['type'] == 'number') {
                            $updated_fields += array($diff_fields => array('type' => 'INT', 'null' => TRUE,),);
                        } else if ($value['type'] == 'text') {
                            if ($value['subtype'] == 'time') {
                                $updated_fields += array($diff_fields => array('type' => 'TIME', 'null' => TRUE,),);
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                            }
                        } else {
                            $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                        }
                    }
                }
            }
        }

        if ($this->db->table_exists('aai_l4_form')) {
            $this->dbforge->add_column('aai_l4_form', $updated_fields);
        }

        $valaa_data = $this->db->list_fields('aai_l4_form');
        $delete = array();
        foreach ($valaa_data as $val) {
            if (!in_array($val, $result)) {
                $delete[] = $val;
            }
        }

        foreach ($delete as $delete_val) {
            if ($this->db->field_exists($delete_val, 'aai_l4_form')) {
                $this->dbforge->drop_column('aai_l4_form', $delete_val);
            }
        }

        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        
        //Update Record in Database
        $where = array('form_id' => $id);
        if ($this->common_model->update(AAI_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL4/' . $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL4/' . $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname . '/editL4/' . $id);
    }
	 /*
      @Author : Nikunj Ghelani
      @Desc   : ADD Page
      @Date   : 05/01/2019
     */

    public function addL5() {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['crnt_view'] = $this->viewname;
            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            //$data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            //$data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
            $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['button_header'] = array('menu_module' => 'l5');
            $data['form_data'] = array('button_data' => 'active');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addL5';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/addL5';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertL5Module();
        }
    }
	
	
	 /*
      @Author : Ritesh Rana
      @Desc   : Insert Form Details
      @Input  :
      @Output :
      @Date   : 01/04/2019
     */

  
    public function insertL5Module() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = $jsonDataUpdated = array();
        $fields = array(
            'l5_form_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'incident_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE),
            'l5_reference_number' => array('type' => 'VARCHAR', 'constraint' => 20, 'unsigned' => TRUE),
            'l5_is_complaint' => array('type' => 'text', 'null' => TRUE),
            'fm_head'            => array('type' => 'text', 'null' => TRUE),
            'fm_nose'            => array('type' => 'text', 'null' => TRUE),
            'fm_left_eye'        => array('type' => 'text', 'null' => TRUE),
            'fm_right_eye'       => array('type' => 'text', 'null' => TRUE),
            'fm_mouth'           => array('type' => 'text', 'null' => TRUE),
            'fm_left_ear'        => array('type' => 'text', 'null' => TRUE),
            'fm_right_ear'       => array('type' => 'text', 'null' => TRUE),
            'fm_neck'            => array('type' => 'text', 'null' => TRUE),
            'fm_chest'           => array('type' => 'text', 'null' => TRUE),
            'fm_abdomen'         => array('type' => 'text', 'null' => TRUE),
            'fm_pelvis'          => array('type' => 'text', 'null' => TRUE),
            'fm_pubis'           => array('type' => 'text', 'null' => TRUE),
            'fm_left_thigh'      => array('type' => 'text', 'null' => TRUE),
            'fm_left_knee'       => array('type' => 'text', 'null' => TRUE),
            'fm_right_knee'      => array('type' => 'text', 'null' => TRUE),
            'fm_left_leg'        => array('type' => 'text', 'null' => TRUE),
            'fm_right_leg'       => array('type' => 'text', 'null' => TRUE),
            'fm_left_ankle'      => array('type' => 'text', 'null' => TRUE),
            'fm_right_ankle'     => array('type' => 'text', 'null' => TRUE),
            'fm_left_foot'       => array('type' => 'text', 'null' => TRUE),
            'fm_right_foot'      => array('type' => 'text', 'null' => TRUE),
            'fm_left_shoulder'   => array('type' => 'text', 'null' => TRUE),
            'fm_right_shoulder'  => array('type' => 'text', 'null' => TRUE),
            'fm_left_arm'        => array('type' => 'text', 'null' => TRUE),
            'fm_right_arm'       => array('type' => 'text', 'null' => TRUE),
            'fm_left_albow'      => array('type' => 'text', 'null' => TRUE),
            'fm_right_albow'     => array('type' => 'text', 'null' => TRUE),
            'fm_left_forearm'    => array('type' => 'text', 'null' => TRUE),
            'fm_right_forearm'   => array('type' => 'text', 'null' => TRUE),
            'fm_left_wrist'      => array('type' => 'text', 'null' => TRUE),
            'fm_right_wrist'     => array('type' => 'text', 'null' => TRUE),
            'fm_left_hand'       => array('type' => 'text', 'null' => TRUE),
            'fm_right_hand'      => array('type' => 'text', 'null' => TRUE),
            'bm_head'            => array('type' => 'text', 'null' => TRUE),
            'bm_neck'            => array('type' => 'text', 'null' => TRUE),
            'bm_back'            => array('type' => 'text', 'null' => TRUE),
            'bm_loin'            => array('type' => 'text', 'null' => TRUE),
            'bm_buttocks'        => array('type' => 'text', 'null' => TRUE),
            'bm_left_hrmstring'  => array('type' => 'text', 'null' => TRUE),
            'bm_right_hrmstring' => array('type' => 'text', 'null' => TRUE),
            'bm_left_knee'       => array('type' => 'text', 'null' => TRUE),
            'bm_right_knee'      => array('type' => 'text', 'null' => TRUE),
            'bm_left_kalf'       => array('type' => 'text', 'null' => TRUE),
            'bm_right_kalf'      => array('type' => 'text', 'null' => TRUE),
            'bm_left_ankle'      => array('type' => 'text', 'null' => TRUE),
            'bm_right_ankle'     => array('type' => 'text', 'null' => TRUE),
            'bm_left_sole'       => array('type' => 'text', 'null' => TRUE),
            'bm_right_sole'      => array('type' => 'text', 'null' => TRUE),
            'bm_left_foot'       => array('type' => 'text', 'null' => TRUE),
            'bm_right_foot'      => array('type' => 'text', 'null' => TRUE),
            'bm_left_shoulder'   => array('type' => 'text', 'null' => TRUE),
            'bm_right_shoulder'  => array('type' => 'text', 'null' => TRUE),
            'bm_left_arm'        => array('type' => 'text', 'null' => TRUE),
            'bm_right_arm'       => array('type' => 'text', 'null' => TRUE),
            'bm_left_albow'      => array('type' => 'text', 'null' => TRUE),
            'bm_right_albow'     => array('type' => 'text', 'null' => TRUE),
            'bm_left_forearm'    => array('type' => 'text', 'null' => TRUE),
            'bm_right_forearm'   => array('type' => 'text', 'null' => TRUE),
            'bm_left_wrist'      => array('type' => 'text', 'null' => TRUE),
            'bm_right_wrist'     => array('type' => 'text', 'null' => TRUE),
            'bm_left_hand'       => array('type' => 'text', 'null' => TRUE),
            'bm_right_hand'      => array('type' => 'text', 'null' => TRUE),
            'how_did_accident'   => array('type' => 'text', 'null' => TRUE),
            'created_by' => array('type' => 'INT', 'constraint' => 5, 'NOT NULL' => TRUE, 'unsigned' => TRUE),
            'created_date' => array('type' => 'DATETIME', 'unsigned' => TRUE),
            'modified_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE),
            'modified_date' => array('type' => 'DATETIME', 'unsigned' => TRUE));

        $n = 0;
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }

                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            $n++;
        }

       
        foreach ($jsonDataUpdated as $value) {
            if ($value['type'] != 'header') {
                if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                     if (isset($value['description'])) {
                                if($value['className'] == 'bamboo_lookup_multiple'){
                                    $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);    
                                }else if($value['className'] == 'multiple'){
                                    $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                                }else{
                                    $fields += array($diff_fields => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE,),);    
                                }
                            } else {
                                $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                            }
                } else if ($value['type'] == 'date') {
                    $fields += array(
                        $value['name'] => array('type' => 'DATE', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'number') {
                    $fields += array(
                        $value['name'] => array('type' => 'INT', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'text') {
                    if ($value['subtype'] == 'time') {
                        $fields += array(
                            $value['name'] => array('type' => 'TIME', 'unsigned' => TRUE, 'null' => TRUE),
                        );
                    } else {
                        $fields += array(
                            $value['name'] => array('type' => 'text', 'null' => TRUE),
                        );
                    }
                } else {
                    $fields += array(
                        $value['name'] => array('type' => 'text', 'null' => TRUE),
                    );
                }
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('l5_form_id', TRUE);
        if (!$this->db->table_exists('aai_l5_form')) {
            $this->dbforge->create_table('aai_l5_form');
        }
        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(AAI_FORM, $data_list)) {
            $aai_main_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL5/' . $aai_main_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }
	/*
      @Author : Nikunj Ghelani
      @Desc   : Form Edit Page
      @Date   : 05/01/2019
     */

    public function editL5($id) {
        $formInfo = $this->common_model->get_records(AAI_FORM, '', '', '', array('form_id' => $id));
        if (!empty($formInfo)) {
            $this->formValidation();
            if ($this->form_validation->run() == FALSE) {
                if (is_numeric($id)) {
                    $data['id'] = $id;
                    $data['crnt_view'] = $this->viewname;
                    $table = AAI_FORM;
                    $match = "";
                    $fields = array("*");
                    $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
                    //$data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

                    $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
                    $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
                    $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
                    //$data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
                    $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
                    $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
                    $data['getFormData'] = $this->common_model->get_records($table, $fields, '', '', $match);
                    $data['button_header'] = array('menu_module' => 'l5');
                    $data['form_data'] = array('button_data' => 'active');
                    $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editL5/' . $id;
                    $data['main_content'] = $this->type . '/' . $this->viewname . '/editL5';
                    $this->parser->parse($this->type . '/assets/template', $data);
                } else {
                    show_404();
                }
            } else {
                $this->updateL5Module();
            }
        } else {
            show_404();
        }
    }
	
	 /*
      @Author : Ritesh Rana
      @Desc   : Module Master update Query
      @Input  : Post record from Module Master List
      @Output : Update data in database and redirect
      @Date   : 01/04/2019
     */

    public function updateL5Module() {
        $this->load->dbforge();
        $id = $this->input->post('form_id');
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $jsonDataUpdated = array();
        $n = 0;
        $fields = array();
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }
                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            if ($row['type'] != 'header') {
                $fields[] = str_replace('-', '_', $row['name']);
            }
            $n++;
        }

        $fields_data = array('l5_form_id','incident_id','l5_reference_number','l5_is_complaint','fm_head','fm_nose','fm_left_eye','fm_right_eye','fm_mouth','fm_left_ear','fm_right_ear','fm_neck','fm_chest','fm_abdomen','fm_pelvis','fm_pubis','fm_left_thigh','fm_left_knee','fm_right_knee','fm_left_leg','fm_right_leg','fm_left_ankle','fm_right_ankle','fm_left_foot','fm_right_foot','fm_left_shoulder','fm_right_shoulder','fm_left_arm','fm_right_arm','fm_left_albow','fm_right_albow','fm_left_forearm','fm_right_forearm','fm_left_wrist','fm_right_wrist','fm_left_hand','fm_right_hand','bm_head','bm_neck','bm_back','bm_loin','bm_buttocks','bm_left_hrmstring','bm_right_hrmstring','bm_left_knee','bm_right_knee','bm_left_kalf','bm_right_kalf','bm_left_ankle','bm_right_ankle','bm_left_sole','bm_right_sole','bm_left_foot','bm_right_foot','bm_left_shoulder','bm_right_shoulder','bm_left_arm','bm_right_arm','bm_left_albow','bm_right_albow','bm_left_forearm','bm_right_forearm','bm_left_wrist','bm_right_wrist','bm_left_hand','bm_right_hand','how_did_accident','created_by', 'created_date', 'modified_by', 'modified_date');

        $result = array_merge($fields_data, $fields);

        $val_data = $this->db->list_fields('aai_l5_form');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();

        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                foreach ($jsonData as $value) {
                    $field_name = str_replace('-', '_', $value['name']);
                    if ($diff_fields == $field_name) {
                        if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                             if (isset($value['description'])) {
                                if($value['className'] == 'bamboo_lookup_multiple'){
                                    $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);    
                                }else if($value['className'] == 'multiple'){
                                    $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                                }else{
                                    $updated_fields += array($diff_fields => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE,),);    
                                }
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                            }
                        } else if ($value['type'] == 'date') {
                            $updated_fields += array($diff_fields => array('type' => 'DATE', 'null' => TRUE,),);
                        } else if ($value['type'] == 'number') {
                            $updated_fields += array($diff_fields => array('type' => 'INT', 'null' => TRUE,),);
                        } else if ($value['type'] == 'text') {
                            if ($value['subtype'] == 'time') {
                                $updated_fields += array($diff_fields => array('type' => 'TIME', 'null' => TRUE,),);
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                            }
                        } else {
                            $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                        }
                    }
                }
            }
        }

        if ($this->db->table_exists('aai_l5_form')) {
            $this->dbforge->add_column('aai_l5_form', $updated_fields);
        }

        $valaa_data = $this->db->list_fields('aai_l5_form');
        $delete = array();
        foreach ($valaa_data as $val) {
            if (!in_array($val, $result)) {
                $delete[] = $val;
            }
        }

        foreach ($delete as $delete_val) {
            if ($this->db->field_exists($delete_val, 'aai_l5_form')) {
                $this->dbforge->drop_column('aai_l5_form', $delete_val);
            }
        }

        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
            
        //Update Record in Database
        $where = array('form_id' => $id);
        if ($this->common_model->update(AAI_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL5/' . $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL5/' . $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname . '/editL5/' . $id);
    }
	
	/*
      @Author : Nikunj Ghelani
      @Desc   : ADD Page
      @Date   : 05/01/2019
     */

    public function addL6() {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['crnt_view'] = $this->viewname;
            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            //$data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            //$data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
            $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['button_header'] = array('menu_module' => 'l6');
            $data['form_data'] = array('button_data' => 'active');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addL6';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/addL6';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertL6Module();
        }
    }
	
	
	 /*
      @Author : Ritesh rana
      @Desc   : Insert Form Details
      @Input  :
      @Output :
      @Date   : 01/04/2019
     */

    public function insertL6Module() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = $jsonDataUpdated = array();
        $fields = array(
            'l6_form_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'incident_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE),
            'l6_reference_number' => array('type' => 'VARCHAR', 'constraint' => 20, 'unsigned' => TRUE),
            'created_by' => array('type' => 'INT', 'constraint' => 5, 'NOT NULL' => TRUE, 'unsigned' => TRUE),
            'created_date' => array('type' => 'DATETIME', 'unsigned' => TRUE),
            'modified_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE),
            'modified_date' => array('type' => 'DATETIME', 'unsigned' => TRUE));

        $n = 0;
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }

                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            $n++;
        }

       
        foreach ($jsonDataUpdated as $value) {
            if ($value['type'] != 'header') {
                if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                   if (isset($value['description'])) {
                                if($value['className'] == 'bamboo_lookup_multiple'){
                                    $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);    
                                }else if($value['className'] == 'multiple'){
                                    $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                                }else{
                                    $fields += array($diff_fields => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE,),);    
                                }
                            } else {
                                $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                            }
                } else if ($value['type'] == 'date') {
                    $fields += array(
                        $value['name'] => array('type' => 'DATE', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'number') {
                    $fields += array(
                        $value['name'] => array('type' => 'INT', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'text') {
                    if ($value['subtype'] == 'time') {
                        $fields += array(
                            $value['name'] => array('type' => 'TIME', 'unsigned' => TRUE, 'null' => TRUE),
                        );
                    } else {
                        $fields += array(
                            $value['name'] => array('type' => 'text', 'null' => TRUE),
                        );
                    }
                } else {
                    $fields += array(
                        $value['name'] => array('type' => 'text', 'null' => TRUE),
                    );
                }
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('l6_form_id', TRUE);
        if (!$this->db->table_exists('aai_l6_form')) {
            $this->dbforge->create_table('aai_l6_form');
        }
        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(AAI_FORM, $data_list)) {
            $aai_main_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL6/' . $aai_main_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }
	
	/*
      @Author : Nikunj Ghelani
      @Desc   : Form Edit Page
      @Date   : 05/01/2019
     */

    public function editL6($id) {
        $formInfo = $this->common_model->get_records(AAI_FORM, '', '', '', array('form_id' => $id));
        if (!empty($formInfo)) {
            $this->formValidation();
            if ($this->form_validation->run() == FALSE) {
                if (is_numeric($id)) {
                    $data['id'] = $id;
                    $data['crnt_view'] = $this->viewname;
                    $table = AAI_FORM;
                    $match = "";
                    $fields = array("*");
                    $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
                    //$data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

                    $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
                    $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
                    $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
                    //$data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
                    $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
                    $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
                    $data['getFormData'] = $this->common_model->get_records($table, $fields, '', '', $match);
                    $data['button_header'] = array('menu_module' => 'l6');
                    $data['form_data'] = array('button_data' => 'active');
                    $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editL6/' . $id;
                    $data['main_content'] = $this->type . '/' . $this->viewname . '/editL6';
                    $this->parser->parse($this->type . '/assets/template', $data);
                } else {
                    show_404();
                }
            } else {
                $this->updateL6Module();
            }
        } else {
            show_404();
        }
    }
	
	 /*
      @Author : Ritesh Rana
      @Desc   : Module Master update Query
      @Input  : Post record from Module Master List
      @Output : Update data in database and redirect
      @Date   : 01/04/2019
     */

    public function updateL6Module() {
        $this->load->dbforge();
        $id = $this->input->post('form_id');
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $jsonDataUpdated = array();
        $n = 0;
        $fields = array();
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }
                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            if ($row['type'] != 'header') {
                $fields[] = str_replace('-', '_', $row['name']);
            }
            $n++;
        }

        $fields_data = array('l6_form_id','incident_id','l6_reference_number', 'created_by', 'created_date', 'modified_by', 'modified_date');

        $result = array_merge($fields_data, $fields);

        $val_data = $this->db->list_fields('aai_l6_form');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();

        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                foreach ($jsonData as $value) {
                    $field_name = str_replace('-', '_', $value['name']);
                    if ($diff_fields == $field_name) {
                        if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                            if (isset($value['description'])) {
                                if($value['className'] == 'bamboo_lookup_multiple'){
                                    $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);    
                                }else if($value['className'] == 'multiple'){
                                    $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                                }else{
                                    $updated_fields += array($diff_fields => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE,),);    
                                }
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                            }
                        } else if ($value['type'] == 'date') {
                            $updated_fields += array($diff_fields => array('type' => 'DATE', 'null' => TRUE,),);
                        } else if ($value['type'] == 'number') {
                            $updated_fields += array($diff_fields => array('type' => 'INT', 'null' => TRUE,),);
                        } else if ($value['type'] == 'text') {
                            if ($value['subtype'] == 'time') {
                                $updated_fields += array($diff_fields => array('type' => 'TIME', 'null' => TRUE,),);
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                            }
                        } else {
                            $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                        }
                    }
                }
            }
        }

        if ($this->db->table_exists('aai_l6_form')) {
            $this->dbforge->add_column('aai_l6_form', $updated_fields);
        }

        $valaa_data = $this->db->list_fields('aai_l6_form');
        $delete = array();
        foreach ($valaa_data as $val) {
            if (!in_array($val, $result)) {
                $delete[] = $val;
            }
        }

        foreach ($delete as $delete_val) {
            if ($this->db->field_exists($delete_val, 'aai_l6_form')) {
                $this->dbforge->drop_column('aai_l6_form', $delete_val);
            }
        }

        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        
        //Update Record in Database
        $where = array('form_id' => $id);
        if ($this->common_model->update(AAI_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL6/' . $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL6/' . $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname . '/editL6/' . $id);
    }
	
	
	/*
      @Author : Nikunj Ghelani
      @Desc   : ADD Page
      @Date   : 05/01/2019
     */

    public function addL7() {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['crnt_view'] = $this->viewname;
            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            //$data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            //$data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
            $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['button_header'] = array('menu_module' => 'l7');
            $data['form_data'] = array('button_data' => 'active');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addL7';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/addL7';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertL7Module();
        }
    }


     /*
      @Author : Ritesh rana
      @Desc   : Insert Form Details
      @Input  :
      @Output :
      @Date   : 01/04/2019
     */

    
    public function insertL7Module() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = $jsonDataUpdated = array();
        $fields = array(
            'l7_form_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'incident_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE),
            'l7_reference_number' => array('type' => 'VARCHAR', 'constraint' => 20, 'unsigned' => TRUE),
            'created_by' => array('type' => 'INT', 'constraint' => 5, 'NOT NULL' => TRUE, 'unsigned' => TRUE),
            'created_date' => array('type' => 'DATETIME', 'unsigned' => TRUE),
            'modified_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE),
            'modified_date' => array('type' => 'DATETIME', 'unsigned' => TRUE));

        $n = 0;
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }

                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            $n++;
        }

       
        foreach ($jsonDataUpdated as $value) {
            if ($value['type'] != 'header') {
                if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                    if (isset($value['description'])) {
                                if($value['className'] == 'bamboo_lookup_multiple'){
                                    $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);    
                                }else if($value['className'] == 'multiple'){
                                    $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                                }else{
                                    $fields += array($diff_fields => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE,),);    
                                }
                            } else {
                                $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                            }
                } else if ($value['type'] == 'date') {
                    $fields += array(
                        $value['name'] => array('type' => 'DATE', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'number') {
                    $fields += array(
                        $value['name'] => array('type' => 'INT', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'text') {
                    if ($value['subtype'] == 'time') {
                        $fields += array(
                            $value['name'] => array('type' => 'TIME', 'unsigned' => TRUE, 'null' => TRUE),
                        );
                    } else {
                        $fields += array(
                            $value['name'] => array('type' => 'text', 'null' => TRUE),
                        );
                    }
                } else {
                    $fields += array(
                        $value['name'] => array('type' => 'text', 'null' => TRUE),
                    );
                }
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('l7_form_id', TRUE);
        if (!$this->db->table_exists('aai_l7_form')) {
            $this->dbforge->create_table('aai_l7_form');
        }
        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(AAI_FORM, $data_list)) {
            $aai_main_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL7/' . $aai_main_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }


    /*
      @Author : Nikunj Ghelani
      @Desc   : Form Edit Page
      @Date   : 05/01/2019
     */

    public function editL7($id) {
        $formInfo = $this->common_model->get_records(AAI_FORM, '', '', '', array('form_id' => $id));
        if (!empty($formInfo)) {
            $this->formValidation();
            if ($this->form_validation->run() == FALSE) {
                if (is_numeric($id)) {
                    $data['id'] = $id;
                    $data['crnt_view'] = $this->viewname;
                    $table = AAI_FORM;
                    $match = "";
                    $fields = array("*");
                    $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
                    $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
                    $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
                    $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
                    $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
                    $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
                    $data['getFormData'] = $this->common_model->get_records($table, $fields, '', '', $match);
                    $data['button_header'] = array('menu_module' => 'L7');
                    $data['form_data'] = array('button_data' => 'active');
                    $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editL7/' . $id;
                    $data['main_content'] = $this->type . '/' . $this->viewname . '/editL7';
                    $this->parser->parse($this->type . '/assets/template', $data);
                } else {
                    show_404();
                }
            } else {
                $this->updateL7Module();
            }
        } else {
            show_404();
        }
    }
    
    /*
      @Author : Ritesh Rana
      @Desc   : Module Master update Query
      @Input  : Post record from Module Master List
      @Output : Update data in database and redirect
      @Date   : 01/04/2019
     */

    public function updateL7Module() {
        $this->load->dbforge();
        $id = $this->input->post('form_id');
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $jsonDataUpdated = array();
        $n = 0;
        $fields = array();
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }
                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            if ($row['type'] != 'header') {
                $fields[] = str_replace('-', '_', $row['name']);
            }
            $n++;
        }

        $fields_data = array('l7_form_id','incident_id','l7_reference_number', 'created_by', 'created_date', 'modified_by', 'modified_date');

        $result = array_merge($fields_data, $fields);

        $val_data = $this->db->list_fields('aai_l7_form');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();

        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                foreach ($jsonData as $value) {
                    $field_name = str_replace('-', '_', $value['name']);
                    if ($diff_fields == $field_name) {
                        if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                            if (isset($value['description'])) {
                                if($value['className'] == 'bamboo_lookup_multiple'){
                                    $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);    
                                }else if($value['className'] == 'multiple'){
                                    $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                                }else{
                                    $updated_fields += array($diff_fields => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE,),);    
                                }
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                            }
                        } else if ($value['type'] == 'date') {
                            $updated_fields += array($diff_fields => array('type' => 'DATE', 'null' => TRUE,),);
                        } else if ($value['type'] == 'number') {
                            $updated_fields += array($diff_fields => array('type' => 'INT', 'null' => TRUE,),);
                        } else if ($value['type'] == 'text') {
                            if ($value['subtype'] == 'time') {
                                $updated_fields += array($diff_fields => array('type' => 'TIME', 'null' => TRUE,),);
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                            }
                        } else {
                            $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                        }
                    }
                }
            }
        }

        if ($this->db->table_exists('aai_l7_form')) {
            $this->dbforge->add_column('aai_l7_form', $updated_fields);
        }

        $valaa_data = $this->db->list_fields('aai_l7_form');
        $delete = array();
        foreach ($valaa_data as $val) {
            if (!in_array($val, $result)) {
                $delete[] = $val;
            }
        }

        foreach ($delete as $delete_val) {
            if ($this->db->field_exists($delete_val, 'aai_l7_form')) {
                $this->dbforge->drop_column('aai_l7_form', $delete_val);
            }
        }

        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        
        //Update Record in Database
        $where = array('form_id' => $id);
        if ($this->common_model->update(AAI_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL7/' . $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL7/' . $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname . '/editL7/' . $id);
    }
	
	/*
      @Author : Nikunj Ghelani
      @Desc   : ADD Page
      @Date   : 29/01/2019
     */

    public function addL8() {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['crnt_view'] = $this->viewname;
            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            //$data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            //$data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
            $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['button_header'] = array('menu_module' => 'l8');
            $data['form_data'] = array('button_data' => 'active');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addL8';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/addL8';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertL8Module();
        }
    }
	/*
      @Author : Nikunj Ghelani
      @Desc   : Insert Form Details
      @Input  :
      @Output :
      @Date   : 05/01/2019
     */

   
    public function insertL8Module() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = $jsonDataUpdated = array();
        $fields = array(
            'l8_form_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'incident_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE),
            'l8_reference_number' => array('type' => 'VARCHAR', 'constraint' => 20, 'unsigned' => TRUE),
            'created_by' => array('type' => 'INT', 'constraint' => 5, 'NOT NULL' => TRUE, 'unsigned' => TRUE),
            'created_date' => array('type' => 'DATETIME', 'unsigned' => TRUE),
            'modified_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE),
            'modified_date' => array('type' => 'DATETIME', 'unsigned' => TRUE));

        $n = 0;
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }

                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            $n++;
        }

       
        foreach ($jsonDataUpdated as $value) {
            if ($value['type'] != 'header') {
                if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                    if (isset($value['description'])) {
                                if($value['className'] == 'bamboo_lookup_multiple'){
                                    $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);    
                                }else if($value['className'] == 'multiple'){
                                    $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                                }else{
                                    $fields += array($diff_fields => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE,),);    
                                }
                            } else {
                                $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                            }
                } else if ($value['type'] == 'date') {
                    $fields += array(
                        $value['name'] => array('type' => 'DATE', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'number') {
                    $fields += array(
                        $value['name'] => array('type' => 'INT', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'text') {
                    if ($value['subtype'] == 'time') {
                        $fields += array(
                            $value['name'] => array('type' => 'TIME', 'unsigned' => TRUE, 'null' => TRUE),
                        );
                    } else {
                        $fields += array(
                            $value['name'] => array('type' => 'text', 'null' => TRUE),
                        );
                    }
                } else {
                    $fields += array(
                        $value['name'] => array('type' => 'text', 'null' => TRUE),
                    );
                }
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('l8_form_id', TRUE);
        if (!$this->db->table_exists('aai_l8_form')) {
            $this->dbforge->create_table('aai_l8_form');
        }

        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(AAI_FORM, $data_list)) {
            $aai_main_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL8/' . $aai_main_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }
	
	
	/*
      @Author : Nikunj Ghelani
      @Desc   : Form Edit Page
      @Date   : 05/01/2019
     */

    public function editL8($id) {
        $formInfo = $this->common_model->get_records(AAI_FORM, '', '', '', array('form_id' => $id));
		
        if (!empty($formInfo)) {
            $this->formValidation();
            if ($this->form_validation->run() == FALSE) {
                if (is_numeric($id)) {
                    $data['id'] = $id;
                    $data['crnt_view'] = $this->viewname;
                    $table = AAI_FORM;
                    $match = "";
                    $fields = array("*");
                    $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
                    //$data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

                    $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
                    $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
                    $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
                    //$data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
                    $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
                    $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
                    $data['getFormData'] = $this->common_model->get_records($table, $fields, '', '', $match);
                    $data['button_header'] = array('menu_module' => 'L8');
                    $data['form_data'] = array('button_data' => 'active');
                    $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editL8/' . $id;
                    $data['main_content'] = $this->type . '/' . $this->viewname . '/editL8';
                    $this->parser->parse($this->type . '/assets/template', $data);
                } else {
                    show_404();
                }
            } else {
                $this->updateL8Module();
            }
        } else {
            show_404();
        }
    }
	
	
	 /*
      @Author : Ritesh Rana
      @Desc   : Module Master update Query
      @Input  : Post record from Module Master List
      @Output : Update data in database and redirect
      @Date   : 01/04/2019
     */

    public function updateL8Module() {
        $this->load->dbforge();
        $id = $this->input->post('form_id');
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $jsonDataUpdated = array();
        $n = 0;
        $fields = array();
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }
                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            if ($row['type'] != 'header') {
                $fields[] = str_replace('-', '_', $row['name']);
            }
            $n++;
        }

        $fields_data = array('l8_form_id','incident_id','l8_reference_number', 'created_by', 'created_date', 'modified_by', 'modified_date');

        $result = array_merge($fields_data, $fields);

        $val_data = $this->db->list_fields('aai_l8_form');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();

        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                foreach ($jsonData as $value) {
                    $field_name = str_replace('-', '_', $value['name']);
                    if ($diff_fields == $field_name) {
                        if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                            if (isset($value['description'])) {
                                if($value['className'] == 'bamboo_lookup_multiple'){
                                    $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);    
                                }else if($value['className'] == 'multiple'){
                                    $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                                }else{
                                    $updated_fields += array($diff_fields => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE,),);    
                                }
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                            }
                        } else if ($value['type'] == 'date') {
                            $updated_fields += array($diff_fields => array('type' => 'DATE', 'null' => TRUE,),);
                        } else if ($value['type'] == 'number') {
                            $updated_fields += array($diff_fields => array('type' => 'INT', 'null' => TRUE,),);
                        } else if ($value['type'] == 'text') {
                            if ($value['subtype'] == 'time') {
                                $updated_fields += array($diff_fields => array('type' => 'TIME', 'null' => TRUE,),);
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                            }
                        } else {
                            $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                        }
                    }
                }
            }
        }

        if ($this->db->table_exists('aai_l8_form')) {
            $this->dbforge->add_column('aai_l8_form', $updated_fields);
        }

        $valaa_data = $this->db->list_fields('aai_l8_form');
        $delete = array();
        foreach ($valaa_data as $val) {
            if (!in_array($val, $result)) {
                $delete[] = $val;
            }
        }

        foreach ($delete as $delete_val) {
            if ($this->db->field_exists($delete_val, 'aai_l8_form')) {
                $this->dbforge->drop_column('aai_l8_form', $delete_val);
            }
        }

        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        
        //Update Record in Database
        $where = array('form_id' => $id);
        if ($this->common_model->update(AAI_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL8/' . $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL8/' . $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname . '/editL8/' . $id);
    }
	
	
	public function addL9() {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['crnt_view'] = $this->viewname;
            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            //$data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            //$data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
            $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['button_header'] = array('menu_module' => 'l9');
            $data['form_data'] = array('button_data' => 'active');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addL9';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/addL9';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertL9Module();
        }
    }
	/*
      @Author : Ritesh Rana
      @Desc   : Insert Form Details
      @Input  :
      @Output :
      @Date   : 01/04/2019
     */

   
    public function insertL9Module() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = $jsonDataUpdated = array();
        $fields = array(
            'l9_form_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'incident_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE),
            'l9_reference_number' => array('type' => 'VARCHAR', 'constraint' => 20, 'unsigned' => TRUE),
            'created_by' => array('type' => 'INT', 'constraint' => 5, 'NOT NULL' => TRUE, 'unsigned' => TRUE),
            'created_date' => array('type' => 'DATETIME', 'unsigned' => TRUE),
            'modified_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE),
            'modified_date' => array('type' => 'DATETIME', 'unsigned' => TRUE));

        $n = 0;
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }

                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            $n++;
        }

       
        foreach ($jsonDataUpdated as $value) {
            if ($value['type'] != 'header') {
                if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                    if (isset($value['description'])) {
                                if($value['className'] == 'bamboo_lookup_multiple'){
                                    $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);    
                                }else if($value['className'] == 'multiple'){
                                    $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                                }else{
                                    $fields += array($diff_fields => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE,),);    
                                }
                            } else {
                                $fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                            }
                } else if ($value['type'] == 'date') {
                    $fields += array(
                        $value['name'] => array('type' => 'DATE', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'number') {
                    $fields += array(
                        $value['name'] => array('type' => 'INT', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'text') {
                    if ($value['subtype'] == 'time') {
                        $fields += array(
                            $value['name'] => array('type' => 'TIME', 'unsigned' => TRUE, 'null' => TRUE),
                        );
                    } else {
                        $fields += array(
                            $value['name'] => array('type' => 'text', 'null' => TRUE),
                        );
                    }
                } else {
                    $fields += array(
                        $value['name'] => array('type' => 'text', 'null' => TRUE),
                    );
                }
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('l9_form_id', TRUE);
        if (!$this->db->table_exists('aai_l9_form')) {
            $this->dbforge->create_table('aai_l9_form');
        }
        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(AAI_FORM, $data_list)) {
            $aai_main_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL9/' . $aai_main_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }
	
	
	/*
      @Author : Nikunj Ghelani
      @Desc   : Form Edit Page
      @Date   : 05/01/2019
     */

    public function editL9($id) {
        $formInfo = $this->common_model->get_records(AAI_FORM, '', '', '', array('form_id' => $id));
		
        if (!empty($formInfo)) {
            $this->formValidation();
            if ($this->form_validation->run() == FALSE) {
                if (is_numeric($id)) {
                    $data['id'] = $id;
                    $data['crnt_view'] = $this->viewname;
                    $table = AAI_FORM;
                    $match = "";
                    $fields = array("*");
                    $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
                    //$data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

                    $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
                    $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
                    $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
                    //$data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
                    $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
                    $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
                    $data['getFormData'] = $this->common_model->get_records($table, $fields, '', '', $match);
                    $data['button_header'] = array('menu_module' => 'L9');
                    $data['form_data'] = array('button_data' => 'active');
                    $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editL9/' . $id;
                    $data['main_content'] = $this->type . '/' . $this->viewname . '/editL9';
                    $this->parser->parse($this->type . '/assets/template', $data);
                } else {
                    show_404();
                }
            } else {
                $this->updateL9Module();
            }
        } else {
            show_404();
        }
    }
	
	
	
	 /*
      @Author : Ritesh Rana
      @Desc   : Module Master update Query
      @Input  : Post record from Module Master List
      @Output : Update data in database and redirect
      @Date   : 01/04/2019
     */

    public function updateL9Module() {
        $this->load->dbforge();
        $id = $this->input->post('form_id');
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $jsonDataUpdated = array();
        $n = 0;
        $fields = array();
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }
                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            if ($row['type'] != 'header') {
                $fields[] = str_replace('-', '_', $row['name']);
            }
            $n++;
        }

        $fields_data = array('l9_form_id','incident_id','l9_reference_number', 'created_by', 'created_date', 'modified_by', 'modified_date');

        $result = array_merge($fields_data, $fields);

        $val_data = $this->db->list_fields('aai_l9_form');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();

        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                foreach ($jsonData as $value) {
                    $field_name = str_replace('-', '_', $value['name']);
                    if ($diff_fields == $field_name) {
                        if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                            if (isset($value['description'])) {
                                if($value['className'] == 'bamboo_lookup_multiple'){
                                    $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);    
                                }else if($value['className'] == 'multiple'){
                                    $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                                }else{
                                    $updated_fields += array($diff_fields => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE,),);    
                                }
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                            }
                        } else if ($value['type'] == 'date') {
                            $updated_fields += array($diff_fields => array('type' => 'DATE', 'null' => TRUE,),);
                        } else if ($value['type'] == 'number') {
                            $updated_fields += array($diff_fields => array('type' => 'INT', 'null' => TRUE,),);
                        } else if ($value['type'] == 'text') {
                            if ($value['subtype'] == 'time') {
                                $updated_fields += array($diff_fields => array('type' => 'TIME', 'null' => TRUE,),);
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                            }
                        } else {
                            $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                        }
                    }
                }
            }
        }

        if ($this->db->table_exists('aai_l9_form')) {
            $this->dbforge->add_column('aai_l9_form', $updated_fields);
        }

        $valaa_data = $this->db->list_fields('aai_l9_form');
        $delete = array();
        foreach ($valaa_data as $val) {
            if (!in_array($val, $result)) {
                $delete[] = $val;
            }
        }

        foreach ($delete as $delete_val) {
            if ($this->db->field_exists($delete_val, 'aai_l9_form')) {
                $this->dbforge->drop_column('aai_l9_form', $delete_val);
            }
        }

        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        
        //Update Record in Database
        $where = array('form_id' => $id);
        if ($this->common_model->update(AAI_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL9/' . $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editL9/' . $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname . '/editL9/' . $id);
    }
	
	
	
	
	
    
    
    /*
      @Author : Ritesh rana
      @Desc   : Sanction list view Page
      @Input    :
      @Output   :
      @Date   : 31/01/2019
     */

    public function sanctionAdd() {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['crnt_view'] = $this->viewname;

            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['button_header'] = array('menu_module' => 'sanctions');
            $data['form_data'] = array('button_data' => 'active');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/sanctionadd';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/sanctionadd';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->sanctionInsertModule();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert New Module Details
      @Input  :
      @Output :
      @Date   : 31/01/2019
     */

    public function sanctionInsertModule() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = $jsonDataUpdated = array();
        $fields = array(
            'sanctions_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE), 'incident_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'yp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'created_date' => array('type' => 'DATE', 'unsigned' => TRUE), 'modified_date' => array('type' => 'DATE', 'unsigned' => TRUE), 'created_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'modified_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'signoff' => array('type' => 'TINYINT', 'constraint' => 1, 'NOT NULL' => TRUE, 'DEFAULT' => 0), 'draft' => array('type' => 'TINYINT', 'constraint' => 1, 'NOT NULL' => TRUE, 'DEFAULT' => 0), 'care_home_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'sanction_reference_number' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE, 'unsigned' => TRUE));
        $n = 0;

        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }
                
                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            $n++;
        }

        foreach ($jsonDataUpdated as $value) {
            if ($value['type'] != 'header') {
                if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                    if (isset($value['description'])) {
                        $fields += array(
                            $value['name'] => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE)
                        );
                    } else {
                        $fields += array(
                            $value['name'] => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE)
                        );
                    }
                } else if ($value['type'] == 'date') {
                    $fields += array(
                        $value['name'] => array('type' => 'DATE', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'number') {
                    $fields += array(
                        $value['name'] => array('type' => 'INT', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'text') {
                    if ($value['subtype'] == 'time') {
                        $fields += array(
                            $value['name'] => array('type' => 'TIME', 'unsigned' => TRUE, 'null' => TRUE),
                        );
                    } else {
                        $fields += array(
                            $value['name'] => array('type' => 'text', 'null' => TRUE),
                        );
                    }
                } else {
                    $fields += array(
                        $value['name'] => array('type' => 'text', 'null' => TRUE),
                    );
                }
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('sanctions_id', TRUE);
        if (!$this->db->table_exists('aai_sanctions')) {
            $this->dbforge->create_table('aai_sanctions');
        }
        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(SANCTION_FORM, $data_list)) {
            $pp_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/sanctionEdit/' . $pp_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Sanctions Edit Page
      @Input    :
      @Output   :
      @Date   : 21/01/2019
     */

    public function sanctionEdit($id) {
        $formInfo = $this->common_model->get_records(SANCTION_FORM, '', '', '', array('sanction_form_id' => $id));
        if (!empty($formInfo)) {
            $this->formValidation();
            if ($this->form_validation->run() == FALSE) {
                if (is_numeric($id)) {
                    $data['id'] = $id;
                    $data['crnt_view'] = $this->viewname;
                    $table = SANCTION_FORM;
                    $match = "";
                    $fields = array("*");
                    $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
                    $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
                    $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
                    $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
                    $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
                    $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
                    $data['getFormData'] = $this->common_model->get_records($table, $fields, '', '', $match);
                    $data['button_header'] = array('menu_module' => 'sanctions');
                    $data['form_data'] = array('button_data' => 'active');
                    $data['form_action_path'] = $this->type . '/' . $this->viewname . '/sanctionedit/' . $id;
                    $data['main_content'] = $this->type . '/' . $this->viewname . '/sanctionedit';
                    $this->parser->parse($this->type . '/assets/template', $data);
                } else {
                    show_404();
                }
            } else {
                $this->sanctionUpdateModule();
            }
        } else {
            show_404();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Sanctions update Query
      @Input  : Post record from Sanctions List
      @Output : Update data in database and redirect
      @Date   : 31/01/2019
     */

    public function sanctionUpdateModule() {
        $id = $this->input->post('sanction_form_id');
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $jsonDataUpdated = array();
        $n = 0;
        $fields = array();
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }
                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            if ($row['type'] != 'header') {
                $fields[] = str_replace('-', '_', $row['name']);
            }
            $n++;
        }
        $fields_data = array('sanctions_id', 'incident_id', 'yp_id', 'created_date', 'modified_date', 'created_by', 'modified_by', 'signoff', 'draft', 'care_home_id', 'sanction_reference_number');

        $result = array_merge($fields_data, $fields);
        $val_data = $this->db->list_fields('aai_sanctions');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();

        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }

        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                foreach ($jsonData as $value) {
                    $field_name = str_replace('-', '_', $value['name']);
                    if ($diff_fields == $field_name) {
                        if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                            if (isset($value['description'])) {
                                $updated_fields += array($diff_fields => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE,),);
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                            }
                        } else if ($value['type'] == 'date') {
                            $updated_fields += array($diff_fields => array('type' => 'DATE', 'null' => TRUE,),);
                        } else if ($value['type'] == 'number') {
                            $updated_fields += array($diff_fields => array('type' => 'INT', 'null' => TRUE,),);
                        } else if ($value['type'] == 'text') {
                            if ($value['subtype'] == 'time') {
                                $updated_fields += array($diff_fields => array('type' => 'TIME', 'null' => TRUE,),);
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                            }
                        } else {
                            $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                        }
                    }
                }
            }
        }
        
        if ($this->db->table_exists('aai_sanctions')) {
            $this->dbforge->add_column('aai_sanctions', $updated_fields);
        }

        $valaa_data = $this->db->list_fields('aai_sanctions');
        $delete = array();
        foreach ($valaa_data as $val) {
            if (!in_array($val, $result)) {
                $delete[] = $val;
            }
        }

        foreach ($delete as $delete_val) {
            if ($this->db->field_exists($delete_val, 'aai_sanctions')) {
                $this->dbforge->drop_column('aai_sanctions', $delete_val);
            }
        }

        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //Update Record in Database

        $where = array('sanction_form_id' => $id);
        if ($this->common_model->update(SANCTION_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/sanctionEdit/' . $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/sanctionEdit/' . $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname . '/sanctionEdit/' . $id);
    }
 /* start code for Review by YP with senior staff */       

    /*
      @Author : Ritesh rana
      @Desc   : Review by YP with senior staff view Page
      @Input    :
      @Output   :
      @Date   : 04/02/2019
     */

    public function reviewadd() {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['crnt_view'] = $this->viewname;

            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['button_header'] = array('menu_module' => 'review');
            $data['form_data'] = array('button_data' => 'active');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/reviewadd';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/reviewadd';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->reviewInsertModule();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert review AAI Module Details
      @Input  :
      @Output :
      @Date   : 04/02/2019
     */

    public function reviewInsertModule() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = $jsonDataUpdated = array();
        $fields = array(
            'review_form_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'incident_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE),
            'created_by' => array('type' => 'INT', 'constraint' => 5, 'NOT NULL' => TRUE, 'unsigned' => TRUE),
            'created_date' => array('type' => 'DATETIME', 'unsigned' => TRUE),
            'modified_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE),
            'modified_date' => array('type' => 'DATETIME', 'unsigned' => TRUE));

        $n = 0;
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }

                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            $n++;
        }

       
        foreach ($jsonDataUpdated as $value) {
            if ($value['type'] != 'header') {
                if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                    if (isset($value['description'])) {
                        $fields += array(
                            $value['name'] => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE)
                        );
                    } else {
                        $fields += array(
                            $value['name'] => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE)
                        );
                    }
                } else if ($value['type'] == 'date') {
                    $fields += array(
                        $value['name'] => array('type' => 'DATE', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'number') {
                    $fields += array(
                        $value['name'] => array('type' => 'INT', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'text') {
                    if ($value['subtype'] == 'time') {
                        $fields += array(
                            $value['name'] => array('type' => 'TIME', 'unsigned' => TRUE, 'null' => TRUE),
                        );
                    } else {
                        $fields += array(
                            $value['name'] => array('type' => 'text', 'null' => TRUE),
                        );
                    }
                } else {
                    $fields += array(
                        $value['name'] => array('type' => 'text', 'null' => TRUE),
                    );
                }
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('review_form_id', TRUE);
        if (!$this->db->table_exists('aai_review_form')) {
            $this->dbforge->create_table('aai_review_form');
        }
        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(AAI_FORM, $data_list)) {
            $aai_main_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/reviewEdit/' . $aai_main_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Review AAI Edit Page
      @Input    :
      @Output   :
      @Date   : 04/02/2019
     */

     public function reviewEdit($id) {
        $formInfo = $this->common_model->get_records(AAI_FORM, '', '', '', array('form_id' => $id));
        if (!empty($formInfo)) {
            $this->formValidation();
            if ($this->form_validation->run() == FALSE) {
                if (is_numeric($id)) {
                    $data['id'] = $id;
                    $data['crnt_view'] = $this->viewname;
                    $table = AAI_FORM;
                    $match = "";
                    $fields = array("*");
                    $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
                    //$data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

                    $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
                    $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
                    $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
                    //$data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
                    $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
                    $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
                    $data['getFormData'] = $this->common_model->get_records($table, $fields, '', '', $match);

                    $data['button_header'] = array('menu_module' => 'review');
                    $data['form_data'] = array('button_data' => 'active');
                    $data['form_action_path'] = $this->type . '/' . $this->viewname . '/reviewEdit/' . $id;
                    $data['main_content'] = $this->type . '/' . $this->viewname . '/reviewEdit';
                    $this->parser->parse($this->type . '/assets/template', $data);
                } else {
                    show_404();
                }
            } else {
                $this->reviewUpdateModule();
            }
        } else {
            show_404();
        }
    }

     /*
      @Author : Ritesh Rana
      @Desc   : Module Master update Query
      @Input  : Post record from Module Master List
      @Output : Update data in database and redirect
      @Date   : 01/04/2019
     */

    public function reviewUpdateModule() {
        $this->load->dbforge();
        $id = $this->input->post('form_id');
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $jsonDataUpdated = array();
        $n = 0;
        $fields = array();
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }
                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            if ($row['type'] != 'header') {
                $fields[] = str_replace('-', '_', $row['name']);
            }
            $n++;
        }

        $fields_data = array('review_form_id','incident_id', 'created_by', 'created_date', 'modified_by', 'modified_date');

        $result = array_merge($fields_data, $fields);

        $val_data = $this->db->list_fields('aai_review_form');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();

        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                foreach ($jsonData as $value) {
                    $field_name = str_replace('-', '_', $value['name']);
                    if ($diff_fields == $field_name) {
                        if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                            if (isset($value['description'])) {
                                $updated_fields += array($diff_fields => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE,),);
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                            }
                        } else if ($value['type'] == 'date') {
                            $updated_fields += array($diff_fields => array('type' => 'DATE', 'null' => TRUE,),);
                        } else if ($value['type'] == 'number') {
                            $updated_fields += array($diff_fields => array('type' => 'INT', 'null' => TRUE,),);
                        } else if ($value['type'] == 'text') {
                            if ($value['subtype'] == 'time') {
                                $updated_fields += array($diff_fields => array('type' => 'TIME', 'null' => TRUE,),);
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                            }
                        } else {
                            $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                        }
                    }
                }
            }
        }

        if ($this->db->table_exists('aai_review_form')) {
            $this->dbforge->add_column('aai_review_form', $updated_fields);
        }

        $valaa_data = $this->db->list_fields('aai_review_form');
        $delete = array();
        foreach ($valaa_data as $val) {
            if (!in_array($val, $result)) {
                $delete[] = $val;
            }
        }

        foreach ($delete as $delete_val) {
            if ($this->db->field_exists($delete_val, 'aai_review_form')) {
                $this->dbforge->drop_column('aai_review_form', $delete_val);
            }
        }

        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        
        //Update Record in Database
        $where = array('form_id' => $id);
        if ($this->common_model->update(AAI_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/reviewEdit/' . $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/reviewEdit/' . $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname . '/reviewEdit/' . $id);
    }
    

/* End code for Review by YP with senior staff */       



/* start code for manager Review*/       

    /*
      @Author : Ritesh rana
      @Desc   : Review by YP with senior staff view Page
      @Input    :
      @Output   :
      @Date   : 04/02/2019
     */

    public function manageradd() {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['crnt_view'] = $this->viewname;

            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['button_header'] = array('menu_module' => 'manager');
            $data['form_data'] = array('button_data' => 'active');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/manageradd';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/manageradd';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->managerInsertModule();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert review AAI Module Details
      @Input  :
      @Output :
      @Date   : 04/02/2019
     */

    
    public function managerInsertModule() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = $jsonDataUpdated = array();
        $fields = array(
        'mreview_form_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
        'incident_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE),
        'created_by' => array('type' => 'INT', 'constraint' => 5, 'NOT NULL' => TRUE, 'unsigned' => TRUE),
        'created_date' => array('type' => 'DATETIME', 'unsigned' => TRUE),
        'modified_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE),
        'modified_date' => array('type' => 'DATETIME', 'unsigned' => TRUE));

        $n = 0;
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }

                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            $n++;
        }

       
        foreach ($jsonDataUpdated as $value) {
            if ($value['type'] != 'header') {
                if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                    if (isset($value['description'])) {
                        $fields += array(
                            $value['name'] => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE)
                        );
                    } else {
                        $fields += array(
                            $value['name'] => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE)
                        );
                    }
                } else if ($value['type'] == 'date') {
                    $fields += array(
                        $value['name'] => array('type' => 'DATE', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'number') {
                    $fields += array(
                        $value['name'] => array('type' => 'INT', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'text') {
                    if ($value['subtype'] == 'time') {
                        $fields += array(
                            $value['name'] => array('type' => 'TIME', 'unsigned' => TRUE, 'null' => TRUE),
                        );
                    } else {
                        $fields += array(
                            $value['name'] => array('type' => 'text', 'null' => TRUE),
                        );
                    }
                } else {
                    $fields += array(
                        $value['name'] => array('type' => 'text', 'null' => TRUE),
                    );
                }
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('mreview_form_id', TRUE);
        if (!$this->db->table_exists('aai_manager_review_form')) {
            $this->dbforge->create_table('aai_manager_review_form');
        }
        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(AAI_FORM, $data_list)) {
            $aai_main_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/managerEdit/' . $aai_main_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Review AAI Edit Page
      @Input    :
      @Output   :
      @Date   : 04/02/2019
     */

     public function managerEdit($id) {
        $formInfo = $this->common_model->get_records(AAI_FORM, '', '', '', array('form_id' => $id));
        if (!empty($formInfo)) {
            $this->formValidation();
            if ($this->form_validation->run() == FALSE) {
                if (is_numeric($id)) {
                    $data['id'] = $id;
                    $data['crnt_view'] = $this->viewname;
                    $table = AAI_FORM;
                    $match = "";
                    $fields = array("*");
                    $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
                    $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
                    $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
                    $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
                    $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
                    $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
                    $data['getFormData'] = $this->common_model->get_records($table, $fields, '', '', $match);
                    
                    $data['button_header'] = array('menu_module' => 'manager');
                    $data['form_data'] = array('button_data' => 'active');
                    $data['form_action_path'] = $this->type . '/' . $this->viewname . '/managerEdit/' . $id;
                    $data['main_content'] = $this->type . '/' . $this->viewname . '/managerEdit';
                    $this->parser->parse($this->type . '/assets/template', $data);
                } else {
                    show_404();
                }
            } else {
                $this->managerUpdateModule();
            }
        } else {
            show_404();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Review update Query
      @Input  : Post record from review List
      @Output : Update data in database and redirect
      @Date   : 04/02/2019
     */

    public function managerUpdateModule() {
        $this->load->dbforge();
        $id = $this->input->post('form_id');
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $jsonDataUpdated = array();
        $n = 0;
        $fields = array();
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }
                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            if ($row['type'] != 'header') {
                $fields[] = str_replace('-', '_', $row['name']);
            }
            $n++;
        }

        $fields_data = array('mreview_form_id','incident_id', 'created_by', 'created_date', 'modified_by', 'modified_date');

        $result = array_merge($fields_data, $fields);

        $val_data = $this->db->list_fields('aai_manager_review_form');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();

        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                foreach ($jsonData as $value) {
                    $field_name = str_replace('-', '_', $value['name']);
                    if ($diff_fields == $field_name) {
                        if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                            if (isset($value['description'])) {
                                $updated_fields += array($diff_fields => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE,),);
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                            }
                        } else if ($value['type'] == 'date') {
                            $updated_fields += array($diff_fields => array('type' => 'DATE', 'null' => TRUE,),);
                        } else if ($value['type'] == 'number') {
                            $updated_fields += array($diff_fields => array('type' => 'INT', 'null' => TRUE,),);
                        } else if ($value['type'] == 'text') {
                            if ($value['subtype'] == 'time') {
                                $updated_fields += array($diff_fields => array('type' => 'TIME', 'null' => TRUE,),);
                            } else {
                                $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                            }
                        } else {
                            $updated_fields += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                        }
                    }
                }
            }
        }

        if ($this->db->table_exists('aai_manager_review_form')) {
            $this->dbforge->add_column('aai_manager_review_form', $updated_fields);
        }

        $valaa_data = $this->db->list_fields('aai_manager_review_form');
        $delete = array();
        foreach ($valaa_data as $val) {
            if (!in_array($val, $result)) {
                $delete[] = $val;
            }
        }

        foreach ($delete as $delete_val) {
            if ($this->db->field_exists($delete_val, 'aai_manager_review_form')) {
                $this->dbforge->drop_column('aai_manager_review_form', $delete_val);
            }
        }

        $data['crnt_view'] = $this->viewname;
        $data_list = array(
            'form_data' => $this->input->post('form_data'),
            'form_json_data' => json_encode($jsonDataUpdated),
            'created_date' => datetimeformat(),
            'created_by' => $this->session->userdata['nfc_admin_session']['admin_id'],
        );
        
        //Update Record in Database
        $where = array('form_id' => $id);
        if ($this->common_model->update(AAI_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/managerEdit/' . $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/managerEdit/' . $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname . '/managerEdit/' . $id);
    }
    
/* End code for Manager Review */   

    public function formValidation($id = null) {
        $this->form_validation->set_rules('form_data', 'Form data ', 'trim|required|xss_clean');
    }

}
