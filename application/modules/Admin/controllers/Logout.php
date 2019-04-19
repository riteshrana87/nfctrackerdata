<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {

	public function index()
	{
        $this->type = ADMIN_SITE;
        $this->viewname = $this->uri->segment(2);
		$admin_session = $this->session->userdata('nfc_admin_session');
                
        if($admin_session['active']==TRUE)
        {
            $this->session->unset_userdata('nfc_admin_session');
            redirect(base_url($this->type));
        }
        else
            redirect(base_url($this->type));
	}
}
