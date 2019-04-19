<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class KeySessionView extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Niral Patel
      @Desc   : View ks
      @Input    : id
      @Output   :
      @Date   : 28/09/2017
     */

    public function view($ks_id, $yp_id) {
        if (is_numeric($ks_id) && is_numeric($yp_id)) {
            //get ks form
            $match = array('ks_form_id' => 1);
            $ks_forms = $this->common_model->get_records(KS_FORM, '', '', '', $match);
            if (!empty($ks_forms)) {
                $data['ks_form_data'] = json_decode($ks_forms[0]['form_json_data'], TRUE);
            }
            //get YP information
            $match = array("yp_id" => $yp_id);
            $fields = array("yp_id,care_home,yp_fname,yp_lname,date_of_birth");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

            //get ks yp data
            $match = array('ks_id' => $ks_id);
            $data['edit_data'] = $this->common_model->get_records(KEY_SESSION, '', '', '', $match);

            //check data exist or not
            if (empty($data['YP_details']) || empty($data['edit_data'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('Admin/Reports/KS');
            }
            $data['ypid'] = $yp_id;
            $data['footerJs'][0] = base_url('uploads/custom/js/keysession/keysession.js');
            $data['crnt_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/KeySessionView/view';
            $this->parser->parse('/assets/reporttemplate', $data);
        } else {

            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Read more
      @Input    : yp id
      @Output   :
      @Date   : 29/09/2017
     */

    public function readmore($id, $field) {
        $params['fields'] = [$field];
        $params['table'] = KEY_SESSION;
        $params['match_and'] = 'ks_id=' . $id . '';
        $data['documents'] = $this->common_model->get_records_array($params);
        $data['field'] = $field;
        $this->load->view($this->viewname . '/readmore', $data);
    }

}
