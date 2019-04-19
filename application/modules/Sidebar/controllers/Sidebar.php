<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sidebar extends CI_Controller {

    function __construct() {
        $this->CI = & get_instance();
        $system_lang = $this->CI->common_model->get_lang();
  		  $this->selectedLang = $system_lang;
    }

	
	/*
      Author : Ritesh Rana
      Desc   : Call Head area
      Input  : Bunch of Array
      Output : All CSS and JS
      Date   : 27th Feb 2017
     */

    public function defaultHeader($param = NULL) {
		$data['param'] = $param;           //Default Parameter 
        $data['cur_viewname'] = $this->CI->router->fetch_class();     //Current View 
        $data['selected_language'] = $this->selectedLang;  //get Selected Language file 
        $data['user_info'] = $this->CI->session->userdata('LOGGED_IN');
        $login_id = $data['user_info']['ID'];
        
        $table = USER_THEME_COLOR . ' as utc';
        $match = "utc.login_user_id = '" . $login_id . "'";
        $fields = array("utc.*");
        $data['user_theme_color'] = $this->CI->common_model->get_records($table, $fields, '', '', $match);

        $table = USER_SET_LOGOUT_TIME . ' as uslt';
        $match = "uslt.login_user_id = '" . $login_id . "'";
        $fields = array("uslt.*");
        $data['user_set_logout_time'] = $this->CI->common_model->get_records($table, $fields, '', '', $match);
        $this->CI->load->view('Sidebar/defaultHeader', $data);
    }
	
	/*
      Author : Ritesh Rana
      Desc   : Call Head area
      Input  : Bunch of Array
      Output : All CSS and JS
      Date   : 27th Feb 2017
     */

    public function defaultLogoHeader($param = NULL) {
	$data['param'] = $param;           //Default Parameter 
        $data['cur_viewname'] = $this->CI->router->fetch_class();     //Current View 
        $data['selected_language'] = $this->selectedLang;  //get Selected Language file 
        $data['user_info'] = $this->CI->session->userdata('LOGGED_IN');
        $role_id = $data['user_info']['ROLE_TYPE'];
        $login_id = $data['user_info']['ID'];
        $table = ROLE_MASTER . ' as rm';
        $where = "rm.role_id= '" . $role_id . "' ";
        $fieldsn = array("rm.role_name");
        $data['user_role_data'] = $this->CI->common_model->get_records($table, $fieldsn, '', '', '', '', '', '', '', '', '', $where, '', '');
        
        $usertable = LOGIN . ' as l';
        $userwhere = "l.login_id = '" . $login_id . "' ";
        $userfieldsn = array("l.*");
        $data['user_data'] = $this->CI->common_model->get_records($usertable, $userfieldsn, '', '', '', '', '', '', '', '', '', $userwhere, '', '');

        $this->CI->load->view('Sidebar/defaultLogoHeader', $data);
    }
	
	/*
      Author : Ritesh Rana
      Desc   : Call Head area
      Input  : Bunch of Array
      Output : All CSS and JS
      Date   : 27th Feb 2017
     */
    public function defaultMenuHeader($param = NULL) {
		$data['param'] = $param;           //Default Parameter 
        $data['cur_viewname'] = $this->CI->router->fetch_class();     //Current View 
        $data['selected_language'] = $this->selectedLang;  //get Selected Language file 

        $this->CI->load->view('Sidebar/defaultMenuHeader', $data);
    }
	
	/*
      Author : Ritesh Rana
      Desc   : Call Head area
      Input  : Bunch of Array
      Output : All CSS and JS
      Date   : 27th Feb 2017
     */

    public function defaultFooter($param = NULL) {
		$data['param'] = $param;           //Default Parameter 
        $data['cur_viewname'] = $this->CI->router->fetch_class();     //Current View 
        $data['selected_language'] = $this->selectedLang;  //get Selected Language file 
        $this->CI->load->view('Sidebar/defaultFooter', $data);
    }

    /*
      Author : Rupesh Jorkar(RJ)
      Desc   : Unset Error Message Variable for all Form
      Input  :
      Output : Unset Error Session
      Date   : 18/01/2016
     */

    public function unseterror() {
        $error = $this->CI->session->userdata('ERRORMSG');
        if (isset($error) && !empty($error)) {
            $this->CI->session->unset_userdata('ERRORMSG');
        }
    }

    public function messageCount(){
        $this->CI->load->library('Encryption');  // this library is for encoding/decoding password
        $converter = new Encryption;
        $user_id= $this->CI->session->userdata('LOGGED_IN')['ID'];
        return;
    }
}
