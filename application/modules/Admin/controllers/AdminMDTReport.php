<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AdminMDTReport extends CI_Controller {

    function __construct() {
        parent::__construct();

        check_admin_login();
        $this->type = "Admin";
        $this->viewname = $this->uri->segment(2);
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Niral Patel
      @Desc   : Ingredient Category Index Page
      @Input 	:
      @Output	:
      @Date   : 23/03/2018
     */

    public function index() {
        
    }

    /*
      @Author : Niral Patel
      @Desc   : Ingredient Type list view Page
      @Input 	:
      @Output	:
      @Date   : 23/03/2018
     */

    public function add() {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['crnt_view'] = $this->viewname;

            $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
            $data['headerCss'][1] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css";

            $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
            $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
            $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
            // $data['footerJs'][3] = "https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js";
            $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
            $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
            $data['button_header'] = array('menu_module' => 'mdt');
            $data['form_data'] = array('button_data' => 'active');
            $data['main_content'] = $this->type . '/' . $this->viewname . '/add';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/add';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertModule();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Insert New Module Details
      @Input  :
      @Output :
      @Date   : 23/03/2018
     */

    public function insertModule() {
        $this->load->dbforge();
        $form_json_data = strip_tags($this->input->post('form_json_data'));
        $jsonData = json_decode($form_json_data, TRUE);

        $fields = array(
            'mdt_report_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE), 'yp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE), 'created_date' => array('type' => 'DATE', 'unsigned' => TRUE, 'NULL' => TRUE), 'modified_date' => array('type' => 'DATE', 'unsigned' => TRUE, 'NULL' => TRUE), 'created_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'NULL' => TRUE), 'modified_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'NULL' => TRUE), 'is_previous_version' => array('type' => 'TINYINT', 'constraint' => 1, 'NOT NULL' => TRUE, 'DEFAULT' => 0), 'edit_slug' => array('type' => 'TINYINT', 'constraint' => 1, 'NOT NULL' => TRUE, 'DEFAULT' => 0), 'report_start_date' => array('type' => 'DATETIME', 'NULL' => TRUE),
            'report_end_date' => array('type' => 'DATETIME', 'NULL' => TRUE),
            'average_days_consumed' => array('type' => 'TEXT', 'NULL' => TRUE),
            'comments_points' => array('type' => 'TEXT', 'NULL' => TRUE),
            'safeguarding' => array('type' => 'TEXT', 'NULL' => TRUE),
            'general_behaviour' => array('type' => 'TEXT', 'NULL' => TRUE),
            'concerns' => array('type' => 'TEXT', 'NULL' => TRUE),
            'bullying_issues' => array('type' => 'TEXT', 'NULL' => TRUE),
            'significant_events' => array('type' => 'TEXT', 'NULL' => TRUE),
            'per_of_attendance' => array('type' => 'VARCHAR', 'NULL' => TRUE, 'constraint' => 50),
            'number_of_referrals' => array('type' => 'VARCHAR', 'NULL' => TRUE, 'constraint' => 500),
            'achievements' => array('type' => 'VARCHAR', 'NULL' => TRUE, 'constraint' => 500),
            'average_pocket' => array('type' => 'VARCHAR', 'NULL' => TRUE, 'constraint' => 500),
            'emotional' => array('type' => 'TEXT', 'NULL' => TRUE),
            'positive_relationships' => array('type' => 'TEXT', 'NULL' => TRUE),
            'contact' => array('type' => 'TEXT', 'NULL' => TRUE),
            'peer_relationships' => array('type' => 'TEXT', 'NULL' => TRUE),
            'cultural_needs' => array('type' => 'TEXT', 'NULL' => TRUE),
            'positive_decision' => array('type' => 'TEXT', 'NULL' => TRUE),
            'school_clubs' => array('type' => 'TEXT', 'NULL' => TRUE),
            'evidencing_curriculum' => array('type' => 'TEXT', 'NULL' => TRUE),
            'voluntary_work' => array('type' => 'TEXT', 'NULL' => TRUE),
            'care_summary' => array('type' => 'TEXT', 'NULL' => TRUE),
            'attendance' => array('type' => 'TEXT', 'NULL' => TRUE),
            'engagement' => array('type' => 'TEXT', 'NULL' => TRUE),
            'areas_of_focus' => array('type' => 'TEXT', 'NULL' => TRUE),
            'progress' => array('type' => 'TEXT', 'NULL' => TRUE),
            'care_plan_targets' => array('type' => 'TEXT', 'NULL' => TRUE),
            'placing_authority' => array('type' => 'VARCHAR', 'NULL' => TRUE, 'constraint' => 255),
            'social_worker' => array('type' => 'VARCHAR', 'NULL' => TRUE, 'constraint' => 255),
            'case_manager' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE),
            'draft' => array('type' => 'TINYINT', 'constraint' => 1, 'DEFAULT' => 0), 'care_home_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'NULL' => TRUE));

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
        $this->dbforge->add_key('mdt_report_id', TRUE);

        if (!$this->db->table_exists('mdt_report')) {
            $this->dbforge->create_table('mdt_report');
        }

        //insert archive mdt table
        $fieldsarchive = array(
            'mdt_archive_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'form_json_data' => array('type' => 'TEXT', 'NULL' => TRUE),
            'yp_id' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE),
            'mdt_report_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE),
            'created_date' => array('type' => 'DATE', 'unsigned' => TRUE, 'NULL' => TRUE),
            'modified_date' => array('type' => 'DATE', 'unsigned' => TRUE, 'NULL' => TRUE),
            'created_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'NULL' => TRUE),
            'modified_by' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE, 'NULL' => TRUE), 'is_previous_version' => array('type' => 'TINYINT', 'constraint' => 1, 'NOT NULL' => TRUE, 'DEFAULT' => 0),
            'edit_slug' => array('type' => 'TINYINT', 'constraint' => 1, 'NOT NULL' => TRUE, 'DEFAULT' => 0),
            'report_start_date' => array('type' => 'DATETIME', 'NULL' => TRUE),
            'report_end_date' => array('type' => 'DATETIME', 'NULL' => TRUE),
            'average_days_consumed' => array('type' => 'TEXT', 'NULL' => TRUE),
            'comments_points' => array('type' => 'TEXT', 'NULL' => TRUE),
            'safeguarding' => array('type' => 'TEXT', 'NULL' => TRUE),
            'general_behaviour' => array('type' => 'TEXT', 'NULL' => TRUE),
            'concerns' => array('type' => 'TEXT', 'NULL' => TRUE),
            'bullying_issues' => array('type' => 'TEXT', 'NULL' => TRUE),
            'significant_events' => array('type' => 'TEXT', 'NULL' => TRUE),
            'per_of_attendance' => array('type' => 'VARCHAR', 'NULL' => TRUE, 'constraint' => 50),
            'number_of_referrals' => array('type' => 'VARCHAR', 'NULL' => TRUE, 'constraint' => 500),
            'achievements' => array('type' => 'VARCHAR', 'NULL' => TRUE, 'constraint' => 500),
            'average_pocket' => array('type' => 'VARCHAR', 'NULL' => TRUE, 'constraint' => 500),
            'emotional' => array('type' => 'TEXT', 'NULL' => TRUE),
            'positive_relationships' => array('type' => 'TEXT', 'NULL' => TRUE),
            'contact' => array('type' => 'TEXT', 'NULL' => TRUE),
            'peer_relationships' => array('type' => 'TEXT', 'NULL' => TRUE),
            'cultural_needs' => array('type' => 'TEXT', 'NULL' => TRUE),
            'positive_decision' => array('type' => 'TEXT', 'NULL' => TRUE),
            'school_clubs' => array('type' => 'TEXT', 'NULL' => TRUE),
            'evidencing_curriculum' => array('type' => 'TEXT', 'NULL' => TRUE),
            'voluntary_work' => array('type' => 'TEXT', 'NULL' => TRUE),
            'care_summary' => array('type' => 'TEXT', 'NULL' => TRUE),
            'attendance' => array('type' => 'TEXT', 'NULL' => TRUE),
            'engagement' => array('type' => 'TEXT', 'NULL' => TRUE),
            'areas_of_focus' => array('type' => 'TEXT', 'NULL' => TRUE),
            'progress' => array('type' => 'TEXT', 'NULL' => TRUE),
            'care_plan_targets' => array('type' => 'TEXT', 'NULL' => TRUE),
            'placing_authority' => array('type' => 'VARCHAR', 'NULL' => TRUE, 'constraint' => 255),
            'social_worker' => array('type' => 'VARCHAR', 'NULL' => TRUE, 'constraint' => 255),
            'case_manager' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE),
            'status' => array('type' => 'INT', 'constraint' => 5, 'unsigned' => TRUE),
            'care_home_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'NULL' => TRUE));

        $jsonDataUpdatedArchive = array();
        $n = 0;

        foreach ($jsonData as $row) {
            foreach ($row as $key => $value) {
                if ($key == 'name') {
                    $jsonDataUpdatedArchive[$n][$key] = str_replace('-', '_', $value);
                } else {
                    $jsonDataUpdatedArchive[$n][$key] = $value;
                }
            }
            $n++;
        }

        /* added by Ritesh Rana for solving invalid datatype issue on 06/10/2018 */
        foreach ($jsonDataUpdatedArchive as $value) {
            if ($value['type'] != 'header') {
                if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                    if (isset($value['description'])) {
                        $fieldsarchive += array(
                            $value['name'] => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE)
                        );
                    } else {
                        $fieldsarchive += array(
                            $value['name'] => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE)
                        );
                    }
                } else if ($value['type'] == 'date') {
                    $fieldsarchive += array(
                        $value['name'] => array('type' => 'DATE', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'number') {
                    $fieldsarchive += array(
                        $value['name'] => array('type' => 'INT', 'unsigned' => TRUE, 'null' => TRUE),
                    );
                } else if ($value['type'] == 'text') {
                    if ($value['subtype'] == 'time') {
                        $fieldsarchive += array(
                            $value['name'] => array('type' => 'TIME', 'unsigned' => TRUE, 'null' => TRUE),
                        );
                    } else {
                        $fieldsarchive += array(
                            $value['name'] => array('type' => 'text', 'null' => TRUE),
                        );
                    }
                } else {
                    $fieldsarchive += array(
                        $value['name'] => array('type' => 'text', 'null' => TRUE),
                    );
                }
            }
        }

        $this->dbforge->add_field($fieldsarchive);
        $this->dbforge->add_key('mdt_archive_id', TRUE);

        if (!$this->db->table_exists('mdt_report_archive')) {
            $this->dbforge->create_table('mdt_report_archive');
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
        if ($this->common_model->insert(MDT_FORM, $data_list)) {
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
      @Author : Niral Patel
      @Desc   : Ingredient Type Edit Page
      @Input 	:
      @Output	:
      @Date   : 23/03/2018
     */

    public function edit($id) {
        $formInfo = $this->common_model->get_records(MDT_FORM, '', '', '', array('mdt_form_id' => $id));
        if (!empty($formInfo)) {
            $this->formValidation();
            if ($this->form_validation->run() == FALSE) {
                if (is_numeric($id)) {
                    $data['id'] = $id;
                    $data['crnt_view'] = $this->viewname;
                    $table = MDT_FORM;
                    $match = "";
                    $fields = array("*");
                    $data['headerCss'][0] = base_url('uploads/formbuilder/assets/css/demo.css');
                    //$data['headerCss'][1] = base_url('uploads/formbuilder/assets/css/jquery.rateyo.min.css');

                    $data['footerJs'][0] = base_url('uploads/formbuilder/assets/js/vendor.js');
                    $data['footerJs'][1] = base_url('uploads/formbuilder/assets/js/form-builder.min.js');
                    $data['footerJs'][2] = base_url('uploads/formbuilder/assets/js/form-render.min.js');
                    // $data['footerJs'][3] = base_url('"uploads/formbuilder/assets/js/jquery.rateyo.min.js');
                    $data['footerJs'][3] = base_url('uploads/formbuilder/assets/js/demo.js');
                    $data['footerJs'][4] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
                    $data['getFormData'] = $this->common_model->get_records($table, $fields, '', '', $match);
                    $data['button_header'] = array('menu_module' => 'mdt');
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
      @Author : Niral Patel
      @Desc   : Module Master update Query
      @Input  : Post record from Module Master List
      @Output : Update data in database and redirect
      @Date   : 23/03/2018
     */

    public function updateModule() {
        $id = $this->input->post('mdt_form_id');
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


        $fields_data = array('mdt_report_id', 'yp_id', 'created_date', 'modified_date', 'created_by', 'modified_by', 'is_previous_version', 'edit_slug', 'report_start_date', 'report_end_date', 'average_days_consumed', 'comments_points', 'safeguarding', 'general_behaviour', 'concerns', 'bullying_issues', 'significant_events', 'per_of_attendance', 'number_of_referrals', 'achievements', 'average_pocket', 'emotional', 'positive_relationships', 'contact', 'peer_relationships', 'cultural_needs', 'positive_decision', 'school_clubs', 'evidencing_curriculum', 'voluntary_work', 'care_summary', 'attendance', 'engagement', 'areas_of_focus', 'progress', 'care_plan_targets', 'placing_authority', 'social_worker', 'case_manager', 'draft', 'care_home_id');
        $fields_data_archive = array('mdt_archive_id', 'form_json_data', 'mdt_report_id', 'yp_id', 'created_date', 'modified_date', 'created_by', 'modified_by', 'is_previous_version', 'edit_slug', 'report_start_date', 'report_end_date', 'average_days_consumed', 'comments_points', 'safeguarding', 'general_behaviour', 'concerns', 'bullying_issues', 'significant_events', 'per_of_attendance', 'number_of_referrals', 'achievements', 'average_pocket', 'emotional', 'positive_relationships', 'contact', 'peer_relationships', 'cultural_needs', 'positive_decision', 'school_clubs', 'evidencing_curriculum', 'voluntary_work', 'care_summary', 'attendance', 'engagement', 'areas_of_focus', 'progress', 'care_plan_targets', 'placing_authority', 'social_worker', 'case_manager', 'status', 'care_home_id');



        $result = array_merge($fields_data, $fields);

        $val_data = $this->db->list_fields('mdt_report');
        $diff_fields_result = array_merge(array_diff($result, $val_data));
        $updated_fields = array();


        /* added by Ritesh Rana for solving invalid datatype issue on 06/10/2018 */
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

        //archive
        $result_archive = array_merge($fields_data_archive, $fields);

        $val_data_archive = $this->db->list_fields('mdt_report_archive');
        $diff_fields_result_archive = array_merge(array_diff($result_archive, $val_data_archive));
        $updated_fields_archive = array();


        /* added by Ritesh Rana for solving invalid datatype issue on 06/10/2018 */
        if (count($diff_fields_result_archive) > 0 && !empty($diff_fields_result_archive)) {
            foreach ($diff_fields_result_archive as $diff_fields) {
                foreach ($jsonData as $value) {
                    $field_name = str_replace('-', '_', $value['name']);
                    if ($diff_fields == $field_name) {
                        if ($value['type'] == 'select' || $value['type'] == 'checkbox-group' || $value['type'] == 'radio-group') {
                            if (isset($value['description'])) {
                                $updated_fields_archive += array($diff_fields => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE,),);
                            } else {
                                $updated_fields_archive += array($diff_fields => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE,),);
                            }
                        } else if ($value['type'] == 'date') {
                            $updated_fields_archive += array($diff_fields => array('type' => 'DATE', 'null' => TRUE,),);
                        } else if ($value['type'] == 'number') {
                            $updated_fields_archive += array($diff_fields => array('type' => 'INT', 'null' => TRUE,),);
                        } else if ($value['type'] == 'text') {
                            if ($value['subtype'] == 'time') {
                                $updated_fields_archive += array($diff_fields => array('type' => 'TIME', 'null' => TRUE,),);
                            } else {
                                $updated_fields_archive += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                            }
                        } else {
                            $updated_fields_archive += array($diff_fields => array('type' => 'text', 'null' => TRUE,),);
                        }
                    }
                }
            }
        }

        if ($this->db->table_exists('mdt_report')) {
            $this->dbforge->add_column('mdt_report', $updated_fields);
        }
        if ($this->db->table_exists('mdt_report_archive')) {
            $this->dbforge->add_column('mdt_report_archive', $updated_fields_archive);
        }

        $valaa_data = $this->db->list_fields('mdt_report');
        $delete = array();
        foreach ($valaa_data as $val) {
            if (!in_array($val, $result)) {
                $delete[] = $val;
            }
        }

        foreach ($delete as $delete_val) {
            if ($this->db->field_exists($delete_val, 'mdt_report')) {
                $this->dbforge->drop_column('mdt_report', $delete_val);
            }
        }
        /* archive */
        $valaa_data_ar = $this->db->list_fields('mdt_report_archive');
        $delete_ar = array();
        foreach ($valaa_data_ar as $val) {
            if (!in_array($val, $result_archive)) {
                $delete_ar[] = $val;
            }
        }
        foreach ($delete_ar as $delete_val) {
            if ($this->db->field_exists($delete_val, 'mdt_report_archive')) {
                $this->dbforge->drop_column('mdt_report_archive', $delete_val);
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
        $where = array('mdt_form_id' => $id);
        if ($this->common_model->update(MDT_FORM, $data_list, $where)) {
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
