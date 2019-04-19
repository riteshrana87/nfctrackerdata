<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AdminDocuments extends CI_Controller {

    function __construct() {
        parent::__construct();

        check_admin_login();
        $this->type = "Admin";
        $this->viewname = $this->uri->segment(2);
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Ritesh rana
      @Desc   : Ingredient Category Index Page
      @Input 	:
      @Output	:
      @Date   : 10/03/2017
     */

    public function index() { }

    /*
      @Author : ritesh rana
      @Desc   : Documents list view Page
      @Input 	:
      @Output	:
      @Date   : 13/06/2017
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
            $data['button_header'] = array('menu_module' => 'docs');
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
      @Date   : 13/06/2017
     */

    public function insertModule() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = $jsonDataUpdated = array();
        $fields = array(
            'docs_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE), 'yp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'created_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'created_date' => array('type' => 'DATE', 'unsigned' => TRUE), 'signoff' => array('type' => 'TINYINT', 'constraint' => 1, 'NOT NULL' => TRUE, 'DEFAULT' => 0), 'care_home_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'NULL' => TRUE));
        $n = 0;
        
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }
                /* added by Dhara Bhalala for solving invalid json data issue on 28/09/2018 */
                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            $n++;
        }

        /* added by Ritesh Rana for solving invalid datatype issue on 08/10/2018 */
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
        $this->dbforge->add_key('docs_id', TRUE);
        if (!$this->db->table_exists('yp_documents')) {
            $this->dbforge->create_table('yp_documents');
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
        if ($this->common_model->insert(DOCS_FORM, $data_list)) {
            $pp_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/edit/' . $pp_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Documents Edit Page
      @Input 	:
      @Output	:
      @Date   : 13/06/2017
     */

    public function edit($id) {
        $formInfo = $this->common_model->get_records(DOCS_FORM, '', '', '', array('docs_form_id' => $id));
        if (!empty($formInfo)) {
            $this->formValidation();
            if ($this->form_validation->run() == FALSE) {
                if (is_numeric($id)) {
                    $data['id'] = $id;
                    $data['crnt_view'] = $this->viewname;
                    $table = DOCS_FORM;
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
                    $data['button_header'] = array('menu_module' => 'docs');
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
      @Date   : 13/06/2017
     */

    public function updateModule() {
        $id = $this->input->post('docs_form_id');
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $jsonDataUpdated = $fields = array();
        $n = 0;        
        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdated[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdated[$n][$key] = $value;
                }
                /* added by Dhara Bhalala for solving invalid json data issue on 28/09/2018 */
                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            if ($row['type'] != 'header') {
                $fields[] = str_replace('-', '_', $row['name']);
            }
            $n++;
        }
        $fields_data = array('docs_id', 'yp_id', 'created_by', 'created_date', 'signoff', 'care_home_id');
        $result = array_merge($fields_data, $fields);

        $val_data = $this->db->list_fields('yp_documents');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();

        /* added by Ritesh Rana for solving invalid datatype issue on 06/10/2018 */
        if (count($diff_fields_result) > 0 && !empty($diff_fields_result)) {
            foreach ($diff_fields_result as $diff_fields) {
                foreach ($jsonData as $value) {
                    
                    if(isset($value['name']) && !empty($value['name'])){
                      $field_name = str_replace('-', '_', $value['name']);
                    }else{
                        $field_name = '';
                    }
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

        if ($this->db->table_exists('yp_documents')) {
            $this->dbforge->add_column('yp_documents', $updated_fields);
        }

        $valaa_data = $this->db->list_fields('yp_documents');
        $delete = array();
        foreach ($valaa_data as $val) {
            if (!in_array($val, $result)) {
                $delete[] = $val;
            }
        }

        foreach ($delete as $delete_val) {
            if ($this->db->field_exists($delete_val, 'yp_documents')) {
                $this->dbforge->drop_column('yp_documents', $delete_val);
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
        $where = array('docs_form_id' => $id);
        if ($this->common_model->update(DOCS_FORM, $data_list, $where)) {
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

    public function formValidation($id = null) {
        $this->form_validation->set_rules('form_data', 'Form data ', 'trim|required|xss_clean');
    }

}
