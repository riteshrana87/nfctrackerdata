<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PocketMoneySaving extends CI_Controller {

    function __construct() {
        parent::__construct();
        check_admin_login();
        $this->type = "Admin";
        $this->viewname = $this->uri->segment(2);
        $this->load->library('Ajax_pagination');
        $this->perPage = 10;
    }

    /*
      @Author : Niral Patel
      @Desc   : PocketMoneySaving index
      @Input  :
      @Output :
      @Date   : 24th may 2018
     */

    public function index($page = '') {
        $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/PocketMoneySaving/PocketMoneySaving.js');
        //get YP information
        $match = array("is_deleted" => '0', "is_archive" => 0);
        $fields = array("yp.yp_id,yp.yp_fname,yp.yp_lname,pm.total_balance,pm.saving_balance");
        $join_tables = array(YP_POCKET_MONEY . ' as pm' => 'pm.yp_id = yp.yp_id');
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS . ' as yp', $fields, $join_tables, 'left', $match);
        $data['main_content'] = $this->type . '/' . $this->viewname . '/index';
        $this->parser->parse($this->type . '/assets/template', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : insertdata index
      @Input  :
      @Output :
      @Date   : 24th may 2018
     */

    public function insertdata() {
        $postData = $this->input->post();
        //get total balance
        $match = array('yp_id' => $postData['yp_id']);
        $yp_pocket_money = $this->common_model->get_records(YP_POCKET_MONEY, '', '', '', $match);
        if (empty($yp_pocket_money)) {
            $this->common_model->insert(YP_POCKET_MONEY, array('yp_id' => $postData['yp_id'], 'updated_at' => datetimeformat()));
        }
        //get total balance
        $match = array('yp_id' => $postData['yp_id']);
        $yp_pocket_money = $this->common_model->get_records(YP_POCKET_MONEY, '', '', '', $match);

        $bal = $yp_pocket_money[0]['total_balance'];
        $saving_balance = $yp_pocket_money[0]['saving_balance'];
        $add_subtract = $postData['add_subtract'];
        if ($add_subtract == 'Subtract') {
            if ($saving_balance >= $postData['money_in']) {
                $update_pocket_blance = array(
                    'saving_balance' => ($yp_pocket_money[0]['saving_balance'] - $postData['money_in']),
                    'updated_at' => datetimeformat());

                //update total balance
                $this->common_model->update(YP_POCKET_MONEY, $update_pocket_blance, array('yp_id' => $postData['yp_id']));
                //insert in pocket money
                $data['balance'] = $bal;
                $data['yp_id'] = $postData['yp_id'];
                $data['reason'] = $postData['reason'];
                $data['money_type'] = 1;
                $data['money_out'] = $postData['money_in'];
                $data['created_date'] = datetimeformat();
                $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];

                $this->common_model->insert(POCKET_MONEY, $data);
                $data['pocket_money_id'] = $this->db->insert_id();
                $this->session->set_flashdata('verify_msg', "<div class='alert alert-success text-center'>Pocket money saving subtracted successfully.</div>");
            } else {
                $this->session->set_flashdata('verify_msg', "<div class='alert alert-danger text-center'>There is no enough saving balance.</div>");
                redirect('Admin/' . $this->viewname);
            }
        } else {
            $update_pocket_blance = array(
                'saving_balance' => ($yp_pocket_money[0]['saving_balance'] + $postData['money_in']),
                'updated_at' => datetimeformat());

            //update total balance
            $this->common_model->update(YP_POCKET_MONEY, $update_pocket_blance, array('yp_id' => $postData['yp_id']));
            //insert in pocket money
            $data['balance'] = $bal;
            $data['yp_id'] = $postData['yp_id'];
            $data['reason'] = $postData['reason'];
            $data['money_in'] = $postData['money_in'];
            $data['money_type'] = 1;
            $data['created_date'] = datetimeformat();
            $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];

            $this->common_model->insert(POCKET_MONEY, $data);
            $data['pocket_money_id'] = $this->db->insert_id();
            $this->session->set_flashdata('verify_msg', "<div class='alert alert-success text-center'>Pocket money saving added successfully.</div>");
        }
        //Insert log activity
        $activity = array(
            'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
            'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
            'module_name' => POCKET_MONEY_MODULE,
            'module_field_name' => '',
            'type' => 1
        );
        log_activity($activity);

        redirect('Admin/' . $this->viewname);
    }

}
