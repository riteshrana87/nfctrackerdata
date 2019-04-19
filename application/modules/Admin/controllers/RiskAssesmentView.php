<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RiskAssesmentView extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Niral Patel
      @Desc   : RiskAssesmentView Index Page
      @Input 	: yp id
      @Output	:
      @Date   : 28/08/2017
     */

    public function index($id) {
        if (is_numeric($id)) {
            //get pp form
            $match = array('ra_form_id' => 1);
            $formsdata = $this->common_model->get_records(RA_FORM, '', '', '', $match);
            if (!empty($formsdata)) {
                $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }
            //get YP information
            $match = array("yp_id" => $id);
            $fields = array("*");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('Admin/Reports/RA');
            }
            //get ibp yp data
            $match = array('yp_id' => $id);
            $data['edit_data'] = $this->common_model->get_records(RISK_ASSESSMENT, '', '', '', $match);

            $data['ypid'] = $id;
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['footerJs'][0] = base_url('uploads/custom/js/riskassesment/riskassesment.js');
            $data['crnt_view'] = $this->viewname;
            $data['main_content'] = '/RiskAssesmentView/view';
            $this->parser->parse('/assets/reporttemplate', $data);
        } else {
            show_404();
        }
    }

}
