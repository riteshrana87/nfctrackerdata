<?php
/*
@Author : Ritesh rana
@Desc   : Campaign Group Create/Update
@Date   : 06/04/2018
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class SetLogoutTime extends CI_Controller 
{
    function __construct()
    {
        parent::__construct();
        $this->module = $this->uri->segment(1);
        $this->viewname = $this->uri->segment(1);
        $this->load->library(array('form_validation','Session'));
    }

    public function index()
    {
        $user_id =  $this->session->userdata('LOGGED_IN')['ID'];
        $table = USER_SET_LOGOUT_TIME . ' as utc';
        $match = "utc.login_user_id = '" . $user_id . "'";
        $fields = array("utc.*");
        $data['information'] = $this->common_model->get_records($table, $fields, '', '', $match);
        $data['main_content'] = '/'.$this->viewname;
        $user_id =  $this->session->userdata('LOGGED_IN')['ID'];
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
    
    /*
     @Author : Ritesh Rana
     @Desc   : insert/update 
     @Input     :
     @Output    :
     @Date   : 03/11/2017
     */
    public function insert_theme_data()
    {
        $min = $this->input->post('set_logout_time');
        $set_time_data = ($min*60 + seconds) * 1000;
        
        $data = array(
            'set_time' => $this->input->post('set_logout_time'),
            'milliseconds' => $set_time_data,
            'created_date' => datetimeformat(),
            'modified_date' => datetimeformat(),
            'login_user_id' => $this->session->userdata('LOGGED_IN')['ID']
        );
        if($this->input->post('user_set_logout_id')!=''){
            $user_id =  $this->session->userdata('LOGGED_IN')['ID'];
            $where = $this->db->where('login_user_id', $user_id);
            $updateTheme = $this->common_model->update(USER_SET_LOGOUT_TIME, $data,$where); 
            if($updateTheme)
            {
                $msg = $this->lang->line('USERPROFILE_UPDATED');
                $this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
            }else{
                $msg = $this->lang->line('FAIL_USERPROFILE_UPDATED');
                $this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
            }
        }else{
            if ($this->common_model->insert(USER_SET_LOGOUT_TIME, $data)) {
                $msg = $this->lang->line('user_add_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            } else {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

            }
        }
               redirect($this->viewname);
    }


    /*
     @Author : Ritesh Rana
     @Desc   : insert/update 
     @Input     :
     @Output    :
     @Date   : 03/11/2017
     */
    public function reset_theme()
    {
            $user_id =  $this->session->userdata('LOGGED_IN')['ID'];
            $where = $this->db->where('login_user_id', $user_id);
            $updateTheme =  $this->common_model->delete(USER_THEME_COLOR, $where);
            if($updateTheme)
            {
                $msg = $this->lang->line('USERPROFILE_UPDATED');
                $this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
            }else{
                $msg = $this->lang->line('FAIL_USERPROFILE_UPDATED');
                $this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
            }
               redirect($this->viewname);
    }
}
