<?php
/*
@Author : Ritesh rana
@Desc   : Campaign Group Create/Update
@Date   : 06/04/2018
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class ChangeThemeColor extends CI_Controller 
{
    function __construct()
    {
        parent::__construct();
        $this->module = $this->uri->segment(1);
        $this->viewname = $this->uri->segment(1);
        $this->load->library(array('form_validation','Session'));
        $this->load->model('ChangeThemeColor_model');
    }

    public function index()
    {
        $data['footerJs'][0] = base_url('uploads/custom/js/ChangeThemeColor/ChangeThemeColor.js');
        $data['footerJs'][1] = 'http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.js';
        $data['footerJs'][2] = base_url('uploads/assets/front/js/colorpicker/jquery.simplecolorpicker.js');        

        $data['headerCss'][0] = 'http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css';
        $data['headerCss'][1] = base_url('uploads/assets/front/js/colorpicker/jquery.simplecolorpicker.css');
        $data['headerCss'][2] = base_url('uploads/assets/front/js/colorpicker/jquery.simplecolorpicker-regularfont.css');
        $data['headerCss'][3] = base_url('uploads/assets/front/js/colorpicker/jquery.simplecolorpicker-glyphicons.css');
        $data['headerCss'][4] = base_url('uploads/assets/front/js/colorpicker/jquery.simplecolorpicker-fontawesome.css');

        $user_id =  $this->session->userdata('LOGGED_IN')['ID'];
        
        $table = USER_THEME_COLOR . ' as utc';
        $match = "utc.login_user_id = '" . $user_id . "'";
        $fields = array("utc.*");
        $data['information'] = $this->common_model->get_records($table, $fields, '', '', $match);

        $data['main_content'] = '/'.$this->viewname;
        $user_id =  $this->session->userdata('LOGGED_IN')['ID'];
        $data['profile_data'] = $this->ChangeThemeColor_model->getUserData($user_id);
        
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
        $data = array(
            'background_color' => $this->input->post('background_color'),
            'body_font_color' => $this->input->post('body_font_color'),
            'panel_color' => $this->input->post('panel_color'),
            'title_color' => $this->input->post('title_color'),
            'header_color' => $this->input->post('header_color'),
            'footer_color' => $this->input->post('footer_color'),
            'created_date' => datetimeformat(),
            'modified_date' => datetimeformat(),
            'login_user_id' => $this->session->userdata('LOGGED_IN')['ID']
        );
        if($this->input->post('user_theme_id')!=''){
            $user_id =  $this->session->userdata('LOGGED_IN')['ID'];
            $where = $this->db->where('login_user_id', $user_id);
            $updateTheme = $this->common_model->update(USER_THEME_COLOR, $data,$where); 
            if($updateTheme)
            {
                $msg = $this->lang->line('USERPROFILE_UPDATED');
                $this->session->set_flashdata('msg',"<div class='alert alert-success text-center'>$msg</div>");
            }else{
                $msg = $this->lang->line('FAIL_USERPROFILE_UPDATED');
                $this->session->set_flashdata('msg',"<div class='alert alert-danger text-center'>$msg</div>");
            }
        }else{
            if ($this->common_model->insert(USER_THEME_COLOR, $data)) {
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
            $user_id = $this->session->userdata('LOGGED_IN')['ID'];
            $where = $this->db->where('login_user_id', $user_id);
            $updateTheme = $this->common_model->delete(USER_THEME_COLOR, $where);
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
