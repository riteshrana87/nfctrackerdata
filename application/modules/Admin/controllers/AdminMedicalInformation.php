<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AdminMedicalInformation extends CI_Controller {

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
      @Desc   : MI Index Page
      @Input 	:
      @Output	:
      @Date   : 19/06/2017
     */

    public function index() {
        
    }

    /*
      @Author : ritesh rana
      @Desc   : MI list view Page
      @Input 	:
      @Output	:
      @Date   : 19/06/2017
     */

    public function addAuthorisations() {
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
            $data['button_header'] = array('menu_module' => 'mac');
            $data['form_data'] = array('button_data' => 'active');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addmedicalauthorisations';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/addAuthorisations';
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
      @Date   : 19/06/2017
     */

    public function insertModule() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = array();
        $fields = array(
            'mac_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE), 'yp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'created_date' => array('type' => 'DATE', 'unsigned' => TRUE), 'modified_date' => array('type' => 'DATE', 'unsigned' => TRUE), 'created_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'modified_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'is_previous_version' => array('type' => 'TINYINT', 'constraint' => 1, 'NOT NULL' => TRUE, 'DEFAULT' => 0), 'edit_slug' => array('type' => 'TINYINT', 'constraint' => 1, 'NOT NULL' => TRUE, 'DEFAULT' => 0), 'care_home_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'NULL' => TRUE));

        $jsonDataUpdated = array();
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

        foreach ($jsonDataUpdated as $value) {
            if ($value['type'] != 'header') {
                $fields += array($value['name'] => array('type' => 'text', 'null' => TRUE,),
                );
            }
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
        $this->dbforge->add_key('mac_id', TRUE);
        if (!$this->db->table_exists('medical_authorisations_consents')) {
            $this->dbforge->create_table('medical_authorisations_consents');
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
        if ($this->common_model->insert(MAC_FORM, $data_list)) {
            $pp_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editAuthorisations/' . $pp_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Ingredient Type Edit Page
      @Input 	:
      @Output	:
      @Date   : 19/06/2017
     */

    public function editAuthorisations($id) {
        $formInfo = $this->common_model->get_records(MAC_FORM, '', '', '', array('mac_form_id' => $id));
        if (!empty($formInfo)) {
            $this->formValidation();
            if ($this->form_validation->run() == FALSE) {
                if (is_numeric($id)) {
                    $data['id'] = $id;
                    $data['crnt_view'] = $this->viewname;
                    $table = MAC_FORM;
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
                    $data['button_header'] = array('menu_module' => 'mac');
                    $data['form_data'] = array('button_data' => 'active');
                    $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editAuthorisations/' . $id;
                    $data['main_content'] = $this->type . '/' . $this->viewname . '/editmedicalauthorisations';
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
      @Date   : 19/06/2017
     */

    public function updateModule() {
        $id = $this->input->post('mac_form_id');
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
                /* added by Dhara Bhalala for solving invalid json data issue on 28/09/2018 */
                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            if ($row['type'] != 'header') {
                $fields[] = str_replace('-', '_', $row['name']);
            }
            $n++;
        }
        $fields_data = array('mac_id', 'yp_id', 'created_date', 'modified_date', 'created_by', 'modified_by', 'is_previous_version', 'edit_slug', 'care_home_id');
        $result = array_merge($fields_data, $fields);

        $val_data = $this->db->list_fields('medical_authorisations_consents');
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

        if ($this->db->table_exists('medical_authorisations_consents')) {
            $this->dbforge->add_column('medical_authorisations_consents', $updated_fields);
        }

        $valaa_data = $this->db->list_fields('medical_authorisations_consents');
        $delete = array();
        foreach ($valaa_data as $val) {
            if (!in_array($val, $result)) {
                $delete[] = $val;
            }
        }

        foreach ($delete as $delete_val) {
            if ($this->db->field_exists($delete_val, 'medical_authorisations_consents')) {
                $this->dbforge->drop_column('medical_authorisations_consents', $delete_val);
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
        $where = array('mac_form_id' => $id);
        if ($this->common_model->update(MAC_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editAuthorisations/' . $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editAuthorisations/' . $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname . '/editAuthorisations/' . $id);
    }

    public function addProfessionals() {
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
            $data['button_header'] = array('menu_module' => 'mp');
            $data['form_data'] = array('button_data' => 'active');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addprofessionals';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/addProfessionals';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertProfessionals();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert New Module Details
      @Input  :
      @Output :
      @Date   : 19/06/2017
     */

    public function insertProfessionals() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = array();
        $fields = array(
            'mp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE), 'yp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'created_date' => array('type' => 'DATE', 'unsigned' => TRUE), 'modified_date' => array('type' => 'DATE', 'NOT NULL' => TRUE, 'unsigned' => TRUE), 'created_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'modified_by' => array('type' => 'INT', 'constraint' => 5, 'NOT NULL' => TRUE, 'unsigned' => TRUE), 'care_home_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'NULL' => TRUE));

        $jsonDataUpdated = array();
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


        foreach ($jsonDataUpdated as $value) {
            if ($value['type'] != 'header') {
                $fields += array($value['name'] => array('type' => 'text', 'null' => TRUE,),
                );
            }
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
        $this->dbforge->add_key('mp_id', TRUE);
        if (!$this->db->table_exists('medical_professionals')) {
            $this->dbforge->create_table('medical_professionals');
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
        if ($this->common_model->insert(MP_FORM, $data_list)) {
            $pp_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editProfessionals/' . $pp_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : MI Edit Page
      @Input 	:
      @Output	:
      @Date   : 19/06/2017
     */

    public function editProfessionals($id) {
        $formInfo = $this->common_model->get_records(MP_FORM, '', '', '', array('mp_form_id' => $id));
        if (!empty($formInfo)) {
            $this->formValidation();
            if ($this->form_validation->run() == FALSE) {
                if (is_numeric($id)) {
                    $data['id'] = $id;
                    $data['crnt_view'] = $this->viewname;
                    $table = MP_FORM;
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
                    $data['button_header'] = array('menu_module' => 'mp');
                    $data['form_data'] = array('button_data' => 'active');
                    $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editProfessionals/' . $id;
                    $data['main_content'] = $this->type . '/' . $this->viewname . '/editprofessionals';
                    $this->parser->parse($this->type . '/assets/template', $data);
                } else {
                    show_404();
                }
            } else {
                $this->updateProfessionals();
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
      @Date   : 19/06/2017
     */

    public function updateProfessionals() {
        $id = $this->input->post('mp_form_id');
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
                /* added by Dhara Bhalala for solving invalid json data issue on 28/09/2018 */
                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            if ($row['type'] != 'header') {
                $fields[] = str_replace('-', '_', $row['name']);
            }
            $n++;
        }
        $fields_data = array('mp_id', 'yp_id', 'created_date', 'modified_date', 'created_by', 'modified_by', 'care_home_id');
        $result = array_merge($fields_data, $fields);

        $val_data = $this->db->list_fields('medical_professionals');
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

        if ($this->db->table_exists('medical_professionals')) {
            $this->dbforge->add_column('medical_professionals', $updated_fields);
        }

        $valaa_data = $this->db->list_fields('medical_professionals');
        $delete = array();
        foreach ($valaa_data as $val) {
            if (!in_array($val, $result)) {
                $delete[] = $val;
            }
        }

        foreach ($delete as $delete_val) {
            if ($this->db->field_exists($delete_val, 'medical_professionals')) {
                $this->dbforge->drop_column('medical_professionals', $delete_val);
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
        $where = array('mp_form_id' => $id);
        if ($this->common_model->update(MP_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editProfessionals/' . $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editProfessionals/' . $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname . '/editProfessionals/' . $id);
    }

    public function addOtherInformation() {
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
            $data['button_header'] = array('menu_module' => 'omi');
            $data['form_data'] = array('button_data' => 'active');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addotherinformation';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/insertOtherInformation';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertOtherInformation();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert New Module Details
      @Input  :
      @Output :
      @Date   : 19/06/2017
     */

    public function insertOtherInformation() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = array();
        $fields = array(
            'omi_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE), 'yp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'created_date' => array('type' => 'DATE', 'unsigned' => TRUE), 'modified_date' => array('type' => 'DATE', 'unsigned' => TRUE), 'created_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'modified_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'is_previous_version' => array('type' => 'TINYINT', 'constraint' => 1, 'NOT NULL' => TRUE, 'DEFAULT' => 0), 'edit_slug' => array('type' => 'TINYINT', 'constraint' => 1, 'NOT NULL' => TRUE, 'DEFAULT' => 0), 'care_home_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'NULL' => TRUE));

        $jsonDataUpdated = array();
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
        foreach ($jsonDataUpdated as $value) {
            if ($value['type'] != 'header') {
                $fields += array($value['name'] => array('type' => 'text', 'null' => TRUE,),
                );
            }
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
        $this->dbforge->add_key('omi_id', TRUE);
        if (!$this->db->table_exists('other_medical_information')) {
            $this->dbforge->create_table('other_medical_information');
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
        if ($this->common_model->insert(OMI_FORM, $data_list)) {
            $pp_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editOtherInformation/' . $pp_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Ingredient Type Edit Page
      @Input 	:
      @Output	:
      @Date   : 20/06/2017
     */

    public function editOtherInformation($id) {
        $formInfo = $this->common_model->get_records(OMI_FORM, '', '', '', array('omi_form_id' => $id));
        if (!empty($formInfo)) {
            $this->formValidation();
            if ($this->form_validation->run() == FALSE) {
                if (is_numeric($id)) {
                    $data['id'] = $id;
                    $data['crnt_view'] = $this->viewname;
                    $table = OMI_FORM;
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
                    $data['button_header'] = array('menu_module' => 'omi');
                    $data['form_data'] = array('button_data' => 'active');
                    $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editOtherInformation/' . $id;
                    $data['main_content'] = $this->type . '/' . $this->viewname . '/editotherinformation';
                    $this->parser->parse($this->type . '/assets/template', $data);
                } else {
                    show_404();
                }
            } else {
                $this->updateOtherInformation();
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
      @Date   : 20/06/2017
     */

    public function updateOtherInformation() {
        $id = $this->input->post('omi_form_id');
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
                /* added by Dhara Bhalala for solving invalid json data issue on 28/09/2018 */
                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            if ($row['type'] != 'header') {
                $fields[] = str_replace('-', '_', $row['name']);
            }
            $n++;
        }
        $fields_data = array('omi_id', 'yp_id', 'created_date', 'modified_date', 'created_by', 'modified_by', 'is_previous_version', 'edit_slug', 'care_home_id');
        $result = array_merge($fields_data, $fields);

        $val_data = $this->db->list_fields('other_medical_information');
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

        if ($this->db->table_exists('other_medical_information')) {
            $this->dbforge->add_column('other_medical_information', $updated_fields);
        }

        $valaa_data = $this->db->list_fields('other_medical_information');
        $delete = array();
        foreach ($valaa_data as $val) {
            if (!in_array($val, $result)) {
                $delete[] = $val;
            }
        }

        foreach ($delete as $delete_val) {
            if ($this->db->field_exists($delete_val, 'other_medical_information')) {
                $this->dbforge->drop_column('other_medical_information', $delete_val);
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
        $where = array('omi_form_id' => $id);
        if ($this->common_model->update(OMI_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editOtherInformation/' . $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editOtherInformation/' . $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname . '/editOtherInformation/' . $id);
    }

    public function addInoculations() {

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
            $data['button_header'] = array('menu_module' => 'ino');
            $data['form_data'] = array('button_data' => 'active');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addinoculations';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/insertInoculations';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertInoculations();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert New Module Details
      @Input  :
      @Output :
      @Date   : 20/06/2017
     */

    public function insertInoculations() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = array();
        $fields = array(
            'inoculations_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE), 'yp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'created_date' => array('type' => 'DATE', 'unsigned' => TRUE), 'modified_date' => array('type' => 'DATE', 'unsigned' => TRUE), 'created_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'modified_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'is_previous_version' => array('type' => 'TINYINT', 'constraint' => 1, 'NOT NULL' => TRUE, 'DEFAULT' => 0), 'edit_slug' => array('type' => 'TINYINT', 'constraint' => 1, 'NOT NULL' => TRUE, 'DEFAULT' => 0), 'care_home_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'NULL' => TRUE));

        $jsonDataUpdated = array();
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

        foreach ($jsonDataUpdated as $value) {
            if ($value['type'] != 'header') {
                $fields += array($value['name'] => array('type' => 'text', 'null' => TRUE,),
                );
            }
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
        $this->dbforge->add_key('inoculations_id', TRUE);
        if (!$this->db->table_exists('medical_inoculations')) {
            $this->dbforge->create_table('medical_inoculations');
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
        if ($this->common_model->insert(MI_FORM, $data_list)) {
            $pp_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editInoculations/' . $pp_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : MI Edit Page
      @Input 	:
      @Output	:
      @Date   : 19/06/2017
     */

    public function editInoculations($id) {
        $formInfo = $this->common_model->get_records(MI_FORM, '', '', '', array('mi_form_id' => $id));
        if (!empty($formInfo)) {
            $this->formValidation();
            if ($this->form_validation->run() == FALSE) {
                if (is_numeric($id)) {
                    $data['id'] = $id;
                    $data['crnt_view'] = $this->viewname;
                    $table = MI_FORM;
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
                    $data['button_header'] = array('menu_module' => 'ino');
                    $data['form_data'] = array('button_data' => 'active');
                    $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editInoculations/' . $id;
                    $data['main_content'] = $this->type . '/' . $this->viewname . '/editinoculations';
                    $this->parser->parse($this->type . '/assets/template', $data);
                } else {
                    show_404();
                }
            } else {
                $this->updateInoculations();
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
      @Date   : 20/06/2017
     */

    public function updateInoculations() {
        $id = $this->input->post('mi_form_id');
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
                /* added by Dhara Bhalala for solving invalid json data issue on 28/09/2018 */
                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            if ($row['type'] != 'header') {
                $fields[] = str_replace('-', '_', $row['name']);
            }
            $n++;
        }
        $fields_data = array('inoculations_id', 'yp_id', 'created_date', 'modified_date', 'created_by', 'modified_by', 'is_previous_version', 'edit_slug', 'care_home_id');
        $result = array_merge($fields_data, $fields);

        $val_data = $this->db->list_fields('medical_inoculations');
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

        if ($this->db->table_exists('medical_inoculations')) {
            $this->dbforge->add_column('medical_inoculations', $updated_fields);
        }

        $valaa_data = $this->db->list_fields('medical_inoculations');
        $delete = array();
        foreach ($valaa_data as $val) {
            if (!in_array($val, $result)) {
                $delete[] = $val;
            }
        }

        foreach ($delete as $delete_val) {
            if ($this->db->field_exists($delete_val, 'medical_inoculations')) {
                $this->dbforge->drop_column('medical_inoculations', $delete_val);
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
        $where = array('mi_form_id' => $id);
        if ($this->common_model->update(MI_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editInoculations/' . $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editInoculations/' . $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname . '/editInoculations/' . $id);
    }

    public function addCommunication() {
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
            $data['button_header'] = array('menu_module' => 'mc');
            $data['form_data'] = array('button_data' => 'active');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addcommunication';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/insertCommunication';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertCommunication();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert New Module Details
      @Input  :
      @Output :
      @Date   : 20/06/2017
     */

    public function insertCommunication() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = array();
        $fields = array(
            'communication_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE), 'yp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'created_date' => array('type' => 'DATE', 'unsigned' => TRUE), 'modified_date' => array('type' => 'DATE', 'unsigned' => TRUE), 'created_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'modified_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'appointment' => array('type' => 'TINYINT', 'constraint' => 1, 'NOT NULL' => TRUE, 'DEFAULT' => 0), 'care_home_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'NULL' => TRUE), 'appointment_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'NULL' => TRUE));

        $jsonDataUpdated = array();
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

        foreach ($jsonDataUpdated as $value) {
            if ($value['type'] != 'header') {
                $fields += array($value['name'] => array('type' => 'text', 'null' => TRUE,),
                );
            }
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
        $this->dbforge->add_key('communication_id', TRUE);
        if (!$this->db->table_exists('medical_communication')) {
            $this->dbforge->create_table('medical_communication');
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
        if ($this->common_model->insert(MC_FORM, $data_list)) {
            $pp_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editCommunication/' . $pp_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Communication Edit Page
      @Input 	:
      @Output	:
      @Date   : 20/06/2017
     */

    public function editCommunication($id) {
        $formInfo = $this->common_model->get_records(MC_FORM, '', '', '', array('mc_form_id' => $id));
        if (!empty($formInfo)) {
            $this->formValidation();
            if ($this->form_validation->run() == FALSE) {
                if (is_numeric($id)) {
                    $data['id'] = $id;
                    $data['crnt_view'] = $this->viewname;
                    $table = MC_FORM;
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
                    $data['button_header'] = array('menu_module' => 'mc');
                    $data['form_data'] = array('button_data' => 'active');
                    $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editCommunication/' . $id;
                    $data['main_content'] = $this->type . '/' . $this->viewname . '/editcommunication';
                    $this->parser->parse($this->type . '/assets/template', $data);
                } else {
                    show_404();
                }
            } else {
                $this->updateCommunication();
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
      @Date   : 20/06/2017
     */

    public function updateCommunication() {
        $id = $this->input->post('mc_form_id');
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
                /* added by Dhara Bhalala for solving invalid json data issue on 28/09/2018 */
                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            if ($row['type'] != 'header') {
                $fields[] = str_replace('-', '_', $row['name']);
            }
            $n++;
        }
        $fields_data = array('communication_id', 'yp_id', 'created_date', 'modified_date', 'created_by', 'modified_by', 'appointment', 'care_home_id', 'appointment_id');
        $result = array_merge($fields_data, $fields);

        $val_data = $this->db->list_fields('medical_communication');
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

        if ($this->db->table_exists('medical_communication')) {
            $this->dbforge->add_column('medical_communication', $updated_fields);
        }

        $valaa_data = $this->db->list_fields('medical_communication');
        $delete = array();
        foreach ($valaa_data as $val) {
            if (!in_array($val, $result)) {
                $delete[] = $val;
            }
        }

        foreach ($delete as $delete_val) {
            if ($this->db->field_exists($delete_val, 'medical_communication')) {
                $this->dbforge->drop_column('medical_communication', $delete_val);
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
        $where = array('mc_form_id' => $id);
        if ($this->common_model->update(MC_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editCommunication/' . $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editCommunication/' . $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname . '/editCommunication/' . $id);
    }

    public function addMedication() {
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
            $data['button_header'] = array('menu_module' => 'mm');
            $data['form_data'] = array('button_data' => 'active');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addmedication';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/insertMedication';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertMedication();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert New Module Details
      @Input  :
      @Output :
      @Date   : 20/06/2017
     */

    public function insertMedication() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = array();
        $fields = array(
            'medication_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE), 'yp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'created_date' => array('type' => 'DATE', 'unsigned' => TRUE), 'modified_date' => array('type' => 'DATE', 'unsigned' => TRUE), 'created_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'modified_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'edit_slug' => array('type' => 'TINYINT', 'constraint' => 1, 'NOT NULL' => TRUE, 'DEFAULT' => 0), 'is_archive' => array('type' => 'TINYINT', 'constraint' => 1, 'NOT NULL' => TRUE, 'DEFAULT' => 0, 'COMMENT' => '0-not archive,1- archive'), 'total_stock' => array('type' => 'TEXT'), 'care_home_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'NULL' => TRUE));

        $jsonDataUpdated = array();
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

        foreach ($jsonDataUpdated as $value) {
            if ($value['type'] != 'header') {
                $fields += array($value['name'] => array('type' => 'text', 'null' => TRUE,),
                );
            }
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('medication_id', TRUE);
        if (!$this->db->table_exists('medication')) {
            $this->dbforge->create_table('medication');
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
        if ($this->common_model->insert(M_FORM, $data_list)) {
            $pp_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editMedication/' . $pp_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Medication Type Edit Page
      @Input 	:
      @Output	:
      @Date   : 20/06/2017
     */

    public function editMedication($id) {
        $formInfo = $this->common_model->get_records(M_FORM, '', '', '', array('m_form_id' => $id));
        if (!empty($formInfo)) {
            $this->formValidation();
            if ($this->form_validation->run() == FALSE) {
                if (is_numeric($id)) {
                    $data['id'] = $id;
                    $data['crnt_view'] = $this->viewname;
                    $table = M_FORM;
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
                    $data['button_header'] = array('menu_module' => 'mm');
                    $data['form_data'] = array('button_data' => 'active');
                    $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editMedication/' . $id;
                    $data['main_content'] = $this->type . '/' . $this->viewname . '/editmedication';
                    $this->parser->parse($this->type . '/assets/template', $data);
                } else {
                    show_404();
                }
            } else {
                $this->updateMedication();
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
      @Date   : 21/06/2017
     */

    public function updateMedication() {
        $id = $this->input->post('m_form_id');
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
                /* added by Dhara Bhalala for solving invalid json data issue on 28/09/2018 */
                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            if ($row['type'] != 'header') {
                $fields[] = str_replace('-', '_', $row['name']);
            }
            $n++;
        }
        $fields_data = array('medication_id', 'yp_id', 'created_date', 'modified_date', 'created_by', 'modified_by', 'edit_slug', 'stock_checked_by', 'stock_checked_date', 'stock_checked_by_second', 'stock_checked_date_second', 'comment_first', 'comment_second', 'stock_check_count', 'stock', 'care_home_id', 'is_archive', 'total_stock', 'care_home_id');
        $result = array_merge($fields_data, $fields);

        $val_data = $this->db->list_fields('medication');
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



        $valaa_data = $this->db->list_fields('medication');
        $delete = array();
        foreach ($valaa_data as $val) {
            if (!in_array($val, $result)) {
                $delete[] = $val;
            }
        }
        $madetory_field = array('medication_name', 'date_prescribed', 'length_of_treatment', 'doseage', 'stock', 'reason');
        $del = 0;
        foreach ($delete as $delete_val) {

            if (in_array($delete_val, $madetory_field)) {

                $del++;
            } else {
                if ($this->db->field_exists($delete_val, 'medication')) {
                    $this->dbforge->drop_column('medication', $delete_val);
                }
            }
        }
        if ($del > 0) {
            $msg = 'You can not delete these fields "Medication Name,Date Prescribed,Length Of Treatment,Doseage,Stock,Reason"<br>';
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editMedication/' . $id);
        }
        if ($this->db->table_exists('medication')) {
            $this->dbforge->add_column('medication', $updated_fields);
        }
        foreach ($delete as $delete_val) {
            if ($this->db->field_exists($delete_val, 'medication')) {
                $this->dbforge->drop_column('medication', $delete_val);
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
        $where = array('m_form_id' => $id);
        if ($this->common_model->update(M_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editMedication/' . $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editMedication/' . $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname . '/editMedication/' . $id);
    }

    public function addAdministerMedication() {
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
            $data['button_header'] = array('menu_module' => 'am');
            $data['form_data'] = array('button_data' => 'active');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addadministermedication';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/insertAdministerMedication';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertAdministerMedication();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert New Module Details
      @Input  :
      @Output :
      @Date   : 20/06/2017
     */

    public function insertAdministerMedication() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = array();
        $fields = array(
            'administer_medication_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE), 'yp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'available_stock' => array('type' => 'TEXT', 'unsigned' => TRUE), 'created_date' => array('type' => 'DATE', 'unsigned' => TRUE), 'modified_date' => array('type' => 'DATE', 'unsigned' => TRUE), 'created_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'modified_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'care_home_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'NULL' => TRUE));

        $jsonDataUpdated = array();
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

        foreach ($jsonDataUpdated as $value) {
            if ($value['type'] != 'header') {
                $fields += array($value['name'] => array('type' => 'text', 'null' => TRUE,),
                );
            }
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
        $this->dbforge->add_key('administer_medication_id', TRUE);
        if (!$this->db->table_exists('administer_medication')) {
            $this->dbforge->create_table('administer_medication');
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
        if ($this->common_model->insert(AM_FORM, $data_list)) {
            $pp_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editAdministerMedication/' . $pp_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Medication Edit Page
      @Input 	:
      @Output	:
      @Date   : 20/06/2017
     */

    public function editAdministerMedication($id) {
        $formInfo = $this->common_model->get_records(AM_FORM, '', '', '', array('am_form_id' => $id));
        if (!empty($formInfo)) {
            $this->formValidation();
            if ($this->form_validation->run() == FALSE) {
                if (is_numeric($id)) {
                    $data['id'] = $id;
                    $data['crnt_view'] = $this->viewname;
                    $table = AM_FORM;
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
                    $data['button_header'] = array('menu_module' => 'am');
                    $data['form_data'] = array('button_data' => 'active');
                    $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editAdministerMedication/' . $id;
                    $data['main_content'] = $this->type . '/' . $this->viewname . '/editadministermedication';
                    $this->parser->parse($this->type . '/assets/template', $data);
                } else {
                    show_404();
                }
            } else {
                $this->updateAdministerMedication();
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
      @Date   : 21/06/2017
     */

    public function updateAdministerMedication() {
        $id = $this->input->post('am_form_id');
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
                /* added by Dhara Bhalala for solving invalid json data issue on 28/09/2018 */
                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            if ($row['type'] != 'header') {
                $fields[] = str_replace('-', '_', $row['name']);
            }
            $n++;
        }
        $fields_data = array('administer_medication_id', 'yp_id', 'available_stock', 'created_date', 'modified_date', 'created_by', 'modified_by', 'stock_checked_by', 'stock_checked_date', 'stock_check_count', 'care_home_id');
        $result = array_merge($fields_data, $fields);

        $val_data = $this->db->list_fields('administer_medication');
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

        if ($this->db->table_exists('administer_medication')) {
            $this->dbforge->add_column('administer_medication', $updated_fields);
        }

        $valaa_data = $this->db->list_fields('administer_medication');
        $delete = array();
        foreach ($valaa_data as $val) {
            if (!in_array($val, $result)) {
                $delete[] = $val;
            }
        }

        foreach ($delete as $delete_val) {
            if ($this->db->field_exists($delete_val, 'administer_medication')) {
                $this->dbforge->drop_column('administer_medication', $delete_val);
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
        $where = array('am_form_id' => $id);
        if ($this->common_model->update(AM_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editAdministerMedication/' . $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editAdministerMedication/' . $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname . '/editAdministerMedication/' . $id);
    }

    /*  Add Health Assessment */

    public function addHealthAssessment() {
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
            $data['button_header'] = array('menu_module' => 'ha');
            $data['form_data'] = array('button_data' => 'active');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/addhealthassessment';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/inserthealthassessment';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->inserthealthassessment();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert New Module Details
      @Input  :
      @Output :
      @Date   : 20/06/2017
     */

    public function inserthealthassessment() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);
        $fields = array();
        $fields = array(
            'health_assessment_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE), 'yp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'created_date' => array('type' => 'DATE', 'unsigned' => TRUE), 'modified_date' => array('type' => 'DATE', 'unsigned' => TRUE), 'created_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'modified_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'care_home_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'NULL' => TRUE));

        $jsonDataUpdated = array();
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

        foreach ($jsonDataUpdated as $value) {
            if ($value['type'] != 'header') {
                $fields += array($value['name'] => array('type' => 'text', 'null' => TRUE,),
                );
            }
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
        $this->dbforge->add_key('health_assessment_id', TRUE);
        if (!$this->db->table_exists('health_assessment')) {
            $this->dbforge->create_table('health_assessment');
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
        if ($this->common_model->insert(HA_FORM, $data_list)) {
            $pp_id = $this->db->insert_id();
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editHealthAssessment/' . $pp_id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Health Assessment Edit Page
      @Input 	:
      @Output	:
      @Date   : 21/08/2017
     */

    public function editHealthAssessment($id) {
        $formInfo = $this->common_model->get_records(HA_FORM, '', '', '', array('ha_form_id' => $id));
        if (!empty($formInfo)) {
            $this->formValidation();
            if ($this->form_validation->run() == FALSE) {
                if (is_numeric($id)) {
                    $data['id'] = $id;
                    $data['crnt_view'] = $this->viewname;
                    $table = HA_FORM;
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
                    //pr($data['getFormData']);exit;
                    $data['button_header'] = array('menu_module' => 'ha');
                    $data['form_data'] = array('button_data' => 'active');
                    $data['form_action_path'] = $this->type . '/' . $this->viewname . '/editHealthAssessment/' . $id;
                    $data['main_content'] = $this->type . '/' . $this->viewname . '/edithealthassessment';
                    $this->parser->parse($this->type . '/assets/template', $data);
                } else {
                    show_404();
                }
            } else {
                $this->updateHealthAssessment();
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
      @Date   : 21/06/2017
     */

    public function updateHealthAssessment() {
        $id = $this->input->post('ha_form_id');
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
                /* added by Dhara Bhalala for solving invalid json data issue on 28/09/2018 */
                $jsonDataUpdated[$n][$key] = str_replace(array("'", '"'), "", $jsonDataUpdated[$n][$key]);
            }
            if ($row['type'] != 'header') {
                $fields[] = str_replace('-', '_', $row['name']);
            }
            $n++;
        }
        $fields_data = array('health_assessment_id', 'yp_id', 'created_date', 'modified_date', 'created_by', 'modified_by', 'care_home_id');
        $result = array_merge($fields_data, $fields);

        $val_data = $this->db->list_fields('health_assessment');
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

        if ($this->db->table_exists('health_assessment')) {
            $this->dbforge->add_column('health_assessment', $updated_fields);
        }

        $valaa_data = $this->db->list_fields('health_assessment');
        $delete = array();
        foreach ($valaa_data as $val) {
            if (!in_array($val, $result)) {
                $delete[] = $val;
            }
        }

        foreach ($delete as $delete_val) {
            if ($this->db->field_exists($delete_val, 'health_assessment')) {
                $this->dbforge->drop_column('health_assessment', $delete_val);
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
        $where = array('ha_form_id' => $id);
        if ($this->common_model->update(HA_FORM, $data_list, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editHealthAssessment/' . $id);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname . '/editHealthAssessment/' . $id);
        }
        redirect(ADMIN_SITE . '/' . $this->viewname . '/editHealthAssessment/' . $id);
    }

    public function formValidation($id = null) {
        $this->form_validation->set_rules('form_data', 'Form data ', 'trim|required|xss_clean');
    }

}
