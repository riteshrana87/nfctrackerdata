<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DocumentsView extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Niral Patel
      @Desc   : View documents
      @Input    : id
      @Output   :
      @Date   : 28/09/2017
     */

    public function view($id, $yp_id) {
        if (is_numeric($id) && is_numeric($yp_id)) {
            //get docs form
            $match = array('docs_form_id' => 1);
            $docs_forms = $this->common_model->get_records(DOCS_FORM, '', '', '', $match);
            if (!empty($docs_forms)) {
                $data['form_data'] = json_decode($docs_forms[0]['form_json_data'], TRUE);
            }
            //get YP information
            $match = array("yp_id" => $yp_id);
            $fields = array("yp_id,care_home,yp_fname,yp_lname,date_of_birth");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson');
            }
            //get YP docs
            $match = array("docs_id" => $id, "yp_id" => $yp_id);
            $fields = array("*");
            $data['edit_data'] = $this->common_model->get_records(YP_DOCUMENTS, $fields, '', '', $match);
            if (empty($data['edit_data'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('Admin/Reports/DOCS');
            }
            $data['ypid'] = $data['edit_data'][0]['yp_id'];
            $data['footerJs'][0] = base_url('uploads/custom/js/documents/documents.js');
            $data['crnt_view'] = $this->viewname;
            $data['main_content'] = '/view';
            $data['main_content'] = '/DocumentsView/view';
            $this->parser->parse('/assets/reporttemplate', $data);
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral patel
      @Desc   : Download Documents functionality
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */

    function download($docs_id, $image_name, $yp_id) {
        //get docs form
        $match = array('docs_form_id' => 1);
        $docs_forms = $this->common_model->get_records(DOCS_FORM, '', '', '', $match);
        if (!empty($docs_forms)) {
            $form_data = json_decode($docs_forms[0]['form_json_data'], TRUE);
        }
        $params['fields'] = ['*'];
        $params['table'] = YP_DOCUMENTS . ' as docs';
        $params['match_and'] = 'docs.docs_id=' . $docs_id . '';
        $cost_files = $this->common_model->get_records_array($params);
        if (count($cost_files) > 0) {

            $pth = file_get_contents($this->config->item('docs_img_base_url') . $yp_id . '/' . $image_name);
            $this->load->helper('download');
            force_download($image_name, $pth);
        }
        redirect($this->module);
    }

}
