<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class IbpView extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Niral Patel
      @Desc   : IbpView Index Page
      @Input 	: yp id
      @Output	:
      @Date   : 28/08/2017
     */

    public function index($id) {
        if (is_numeric($id)) {
            //get pp form
            $match = array('ibp_form_id' => 1);
            $formsdata = $this->common_model->get_records(IBP_FORM, '', '', '', $match);
            if (!empty($formsdata)) {
                $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }
            //get YP information
            $match = array("yp_id" => $id);
            $fields = array("yp_id,care_home,yp_fname,yp_lname,date_of_birth");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('Admin/Reports/IBP');
            }
            //get ibp yp data
            $match = array('yp_id' => $id);
            $data['edit_data'] = $this->common_model->get_records(INDIVIDUAL_BEHAVIOUR_PLAN, '', '', '', $match);
            $data['ypid'] = $id;
            $data['footerJs'][0] = base_url('uploads/custom/js/placementplan/placementplan.js');
            $data['crnt_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/IbpView/Ibp';
            $this->parser->parse('/assets/reporttemplate', $data);
        } else {
            show_404();
        }
    }

}
