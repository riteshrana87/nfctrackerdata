<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MyProfile extends CI_Controller 
{
    function __construct()
    {
        parent::__construct();
        $this->module = $this->uri->segment(1);
        $this->viewname = $this->uri->segment(1);
        $this->load->library(array('form_validation','Session'));
        $this->load->model('MyProfile_model');
    }

     /*
     @Author : Ritesh Rana
     @Desc   : view user profile
     @Input     :
     @Output    :
     @Date   : 22/03/2017
     */
    public function index()
    {
        $data['footerJs'][0] = base_url('uploads/custom/js/MyProfile/MyProfile.js');
        $data['main_content'] = '/'.$this->viewname;
        $user_id =  $this->session->userdata('LOGGED_IN')['ID'];
        $data['profile_data'] = $this->MyProfile_model->getUserData($user_id);
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
}
