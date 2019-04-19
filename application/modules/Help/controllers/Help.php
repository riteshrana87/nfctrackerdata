<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Help extends CI_Controller {

	//public $viewname;

	function __construct() {
		parent::__construct();
		$this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
		$this->load->library(array('form_validation', 'Session'));
	}
	/*
	 @Author : Niral Patel
	 @Desc   : add function for help
	 @Input 	:
	 @Output	:
	 @Date   : 1/03/2016
	*/
	public function add() {
		$data = array();
		$data['project_view'] = $this->viewname;
		$redirect_link=$this->input->post('redirect_link');
		$data['main_content'] = '/Lead';
		$data['url'] = base_url("Filemanager/index/?dir=uploads/&modal=true");
		$data['modal_title'] = $this->lang->line('create_new_lead');
		$data['submit_button_title'] = $this->lang->line('create_lead');
		$data['sales_view'] =$this->viewname;
		$this->load->view('AddFinal',$data);
	}

	/*
	 @Author : Niral Patel
	 @Desc   : Add Help to database
	 @Input 	:
	 @Output	:
	 @Date   : 26/02/2016
	 */

    public function saveHelpData() {
        if (!validateFormSecret()) {
            redirect($_SERVER['HTTP_REFERER']);  //Redirect On Listing page
        }
        $this->form_validation->set_rules('name', 'Name', 'required');
        $contact_implode = '';
        $team_member_implode = '';
        $redirect_link = $_SERVER['HTTP_REFERER'];
        if ($this->input->post('help_desc')) {

            $helpdata['description'] = strip_slashes($this->input->post('help_desc'));
        }

        $helpdata['firstname'] = $this->session->userdata('LOGGED_IN')['FIRSTNAME'];
        $helpdata['lastname'] = $this->session->userdata('LOGGED_IN')['LASTNAME'];
        $helpdata['email'] = $this->session->userdata('LOGGED_IN')['EMAIL'];
        $helpdata['subject'] = $this->input->post('subject');
        $helpdata['created_date'] = datetimeformat();
        $helpdata['created_by'] = $this->input->post('update_id');

        //Insert Record in Database
        $success_insert = $this->common_model->insert(HELP, $helpdata);
        /* for email body template */

        $toEmailId = $helpdata['email'];
        $customerName = $helpdata['firstname'] . ' ' . $helpdata['lastname'];
        $find = array('{NAME}');
        $replace = array(
            'NAME' => $customerName,
        );

        $emailSubject = 'NFCTracker Support Desk';
        $emailBody = '<div>'
                . '<p>Hello {NAME} ,</p> '
                . '<p>Thank you for your message, we will look to respond as soon as possible.</p><br>'
                . "<p><b>Thank You</b></p>"
                . "<p><b>Team NFCTracker.</b></p>"
                . '<div>';

        $finalEmailBody = str_replace($find, $replace, $emailBody);
        $mail = $this->common_model->sendEmail($toEmailId, $emailSubject, $finalEmailBody, FROM_EMAIL_ID);

        //$toEmailId = FROM_EMAIL_ID;
        $toEmailId ='tracker@newforestcare.co.uk';
        $from_email = $helpdata['email'];
        $finalEmailBody = strip_slashes($this->input->post('help_desc'));
        $find = array('{MESSAGE}', '{NAME}', '{EMAIL}');
        $replace = array(
            'MESSAGE' => $finalEmailBody,
            'NAME' => $customerName,
            'EMAIL' => $helpdata['email'],
        );

        $emailSubject = $this->input->post('subject');
        $emailBody = '<div>'
                . '<p>Name: {NAME} </p> '
                . '<p>Email: {EMAIL} </p>'
                . '<p>{MESSAGE}</p> '
                . "<p><b>Team NFCTracker.</b></p>"
                . '<div>';

        $finalEmailBody = str_replace($find, $replace, $emailBody);
        $mail = $this->common_model->sendEmail($toEmailId, $emailSubject, $finalEmailBody, FROM_EMAIL_ID);
        $insert_id = $this->db->insert_id();
        echo json_encode(array('status' => 1));
        exit;
    }

}
