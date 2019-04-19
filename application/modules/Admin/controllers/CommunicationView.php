<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CommunicationView extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
    }

    /*
      @Author : Niral Patel
      @Desc   : View Page
      @Input  :
      @Output :
      @Date   : 17/07/2017
     */

    public function view($id) {
        //get communication data
        if (is_numeric($id)) {
            $table = COMMUNICATION_LOG . ' as do';
            $where = array("do.communication_log_id" => $id);
            $fields = array("do.*");
            $data['medical_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            if (empty($data['medical_data'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('Admin/Reports/COMS');
            }
            //get communication form
            $match = array('coms_form_id' => 1);
            $formsdata = $this->common_model->get_records(COMS_FORM, '', '', '', $match);
            if (!empty($formsdata)) {
                $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }
            $data['do_id'] = $id;
            $data['ypid'] = !empty($data['medical_data'][0]['yp_id']) ? $data['medical_data'][0]['yp_id'] : '';
            $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/CommunicationView/view';
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
      @Date   : 26/07/2017
     */

    public function readmore($id, $field) {
        if (is_numeric($id)) {
            $params['fields'] = [$field];
            $params['table'] = COMMUNICATION_LOG;
            $params['match_and'] = 'communication_log_id=' . $id . '';
            $data['documents'] = $this->common_model->get_records_array($params);
            $data['field'] = $field;
            $this->load->view($this->viewname . '/readmore', $data);
        } else {
            show_404();
        }
    }

}
