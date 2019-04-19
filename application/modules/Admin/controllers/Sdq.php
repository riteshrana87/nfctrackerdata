<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sdq extends CI_Controller {

    function __construct() {
        parent::__construct();
        check_admin_login();
        $this->type = "Admin";
        $this->viewname = $this->uri->segment(2);
        $this->load->library('Ajax_pagination');
        $this->perPage = 10;
        $this->load->library('excel');
    }

    /*
      @Author : Ishani Dave
      @Desc   : SDQ index
      @Input  :
      @Output :
      @Date   : 12th Oct 2017
     */

    public function index($page = '') {
        $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/sdq/sdq.js');
        $perpage = '';
        //$cur_uri = @explode('/', $_SERVER['PATH_INFO']);
        $cur_uri = @explode('/', $_SERVER['REQUEST_URI']);
        $cur_uri_segment = array_search($page, $cur_uri);
        $perpage = RECORD_PER_PAGE;
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        if (!empty($sortfield) && !empty($sortby)) {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        } else {
            $sortfield = 's.sdq_que_id';
            $sortby = 'asc';
        }
        if (!empty($perpage) && $perpage != 'null') {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        } else {
            if (!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = RECORD_PER_PAGE;
                $data['perpage'] = RECORD_PER_PAGE;
            }
        }
        //pagination configuration
        $config['first_link'] = 'First';
        $config['base_url'] = base_url() . $data['crnt_view'] . '/index';
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            /* $config['uri_segment'] = $cur_uri_segment;
              $uri_segment = $page; */
            $segment_array = $this->uri->segment_array();
            $uri_segment = end($segment_array);
            $config['uri_segment'] = key(array_slice($segment_array, -1, 1, TRUE));
        }

        $table = NFC_ADMIN_SDQ . ' as s';
        $match = "is_delete = 0";
        $fields = array("s.*");
        $data['sdq_data'] = $this->common_model->get_records($table, $fields, '', '', $match, '', $config['per_page'], $uri_segment, $sortfield, $sortby);
        $config['total_rows'] = $this->common_model->get_records($table, $fields, '', '', $match, '', '', '', $sortfield, $sortby, '', '', '', '', '1');

        $this->ajax_pagination->initialize($config);

        $data['pagination'] = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        setActiveSession();
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->type . '/' . $this->viewname . '/ajaxlist', $data);
        } else {
            $data['main_content'] = $this->type . '/' . $this->viewname . '/sdq';
            $this->parser->parse($this->type . '/assets/template', $data);
        }
    }

    /*
      @Author : Ishani dave
      @Desc   : Add sdq questions
     */

    public function add() {
        $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;

        //Pass data In View
        $data['main_content'] = $this->type . '/' . $this->viewname . '/add_sdq';
        $this->parser->parse($this->type . '/assets/template', $data);
    }

    /*
      @Author : Ishani Dave
      @Desc   : save strength & difficulties questions
      @Date   : 13th Oct 2017
     */

    public function savequedata() {
        $sdq_id = $this->input->post('sdq_id');
        $submit = $this->input->post('submit_btn');

        $addData['que'] = $this->input->post('que');
        $addData['status'] = $this->input->post('status');
        $addData['modified_date'] = date("Y-m-d H:i:s");

        $table = NFC_ADMIN_SDQ;
        if ($submit == 'Add') {
            $addData['is_delete'] = 0;
            $addData['created_date'] = date("Y-m-d H:i:s");
            // save data in nfc_admin_sdq table 
            $data['add_data'] = $this->common_model->insert($table, $addData);
            $parent_id = $data['add_data'];
        } else {
            // update data in nfc_admin_sdq table 
            $strWhere = "is_delete = 0 and sdq_que_id =" . $sdq_id;
            $data['update_record'] = $this->common_model->update($table, $addData, $strWhere);
            // update status in nfc_admin_sdq_subque table 
            $updateData['is_delete'] = 1;
            $tableSub = NFC_ADMIN_SDQ_SUB;
            $strWhereSub = "is_delete = 0 and parent_que_id =" . $sdq_id;
            $data['update_sub_que'] = $this->common_model->update($tableSub, $updateData, $strWhereSub);
        }

        $parent_que_id = ($sdq_id) ? $sdq_id : $parent_id;
        $arrSubque = $this->input->post('sub_que');
        if (count($arrSubque) > 0) {
            foreach ($arrSubque as $row) {
                $saveData['sub_que'] = $row;
                $saveData['parent_que_id'] = $parent_que_id;
                $saveData['status'] = $this->input->post('status');
                $saveData['is_delete'] = '0';

                $subQueTable = NFC_ADMIN_SDQ_SUB;
                $data['add_data'] = $this->common_model->insert($subQueTable, $saveData);
            }
        }
        if ($submit == 'Add') {
            $msg = $this->lang->line('add_data');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {
            $msg = $this->lang->line('edit_data');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
        redirect($this->type . '/' . $this->viewname . '/index');
    }

    /*
      @Author : Ishani Dave
      @Desc   : strength & difficulties questions Edit page
     */

    public function edit($id) {
        if (is_numeric($id)) {
            //Get Records From  nfc_admin_sdq Table
            $tableSdq = NFC_ADMIN_SDQ;
            $strWhere = "is_delete = 0 and sdq_que_id = " . $id;
            $arrFields = array("*");
            $data['editRecord'] = $this->common_model->get_records($tableSdq, $arrFields, '', '', $strWhere);
            if (empty($data['editRecord'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect(ADMIN_SITE . '/' . $this->viewname);
            }
            $tblSub = NFC_ADMIN_SDQ_SUB;
            $strWhereSub = "is_delete = 0 and parent_que_id = " . $id;
            $arrFieldsSub = array("*");
            $data['sub_que_data'] = $this->common_model->get_records($tblSub, $arrFieldsSub, '', '', $strWhereSub);
            $data['id'] = $id;
            $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
            $data['main_content'] = $this->type . '/' . $this->viewname . '/add_sdq';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            show_404();
        }
    }

    /*
      @Author : Ishani Dave
      @Desc   : delete sdq
     */

    public function delete($id) {
        if (!empty($id)) {
            $updateData['is_delete'] = 1;
            $table = NFC_ADMIN_SDQ;
            $strWhere = "is_delete = 0 and sdq_que_id =" . $id;
            $data['update_data'] = $this->common_model->update($table, $updateData, $strWhere);

            $tableSub = NFC_ADMIN_SDQ_SUB;
            $strWhereSub = "is_delete = 0 and parent_que_id =" . $id;
            $data['update_data'] = $this->common_model->delete($tableSub, $strWhereSub);

            $msg = $this->lang->line('delete_data');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        } else {
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

}
