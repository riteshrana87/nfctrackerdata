<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Masteradmin extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->viewname = $this->uri->segment(1);
        $this->load->helper(array('form'));
        //This method will have the credentials validation
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->config('aad_auth');
    }

    public function index() {
        $this->login();
    }

    public function removed_session() {
        $session = $this->input->post('session_id');
        $where = array('id' => $session);
        $this->common_model->delete(CI_SESSION, $where);
    }

  //   public function login() {
  //       $data['error'] = $this->session->flashdata('error');  //Pass Error message
  //       $data['main_content'] = '/login';      //Pass Content
  //       $data['session_id'] = session_id();
        
  //       //$this->parser->parse('layouts/LoginTemplate', $data);
		// $this->parser->parse('layouts/DefaultTemplate', $data);
  //   }

public function login()
    {   
        
        //pr($this->session->userdata('access_token'));exit;
        if ($this->session->userdata('access_token') == '' || $this->session->userdata('access_token') == null){
                  $authorizationURL = $this->getAuthorizatonURL();
                  
                  redirect($authorizationURL);exit;
        }else{

        $user_info = $this->getMeEntry();
        $group_array=array();
        $group_array_data =array();
         $object_id=$user_info->{'objectId'};
            if($object_id!=''){
                $group_data = (array)$this->getGroupNameFromUserObject($object_id);
                if(!empty($group_data['value'])){
                     foreach($group_data['value'] as $key=>$val) {
                        if($group_data['value'][$key]->{'securityEnabled'}){
                          $group_array[]= $group_data['value'][$key]->{'displayName'};   
                        }
                }
                foreach ($group_array as $value_data) {
                        $group_valo[] = explode('-', $value_data);
                    }

                    foreach ($group_valo as $value_data) {
                        $grop_val[] = $value_data[1];
                    }
              
pr($group_valo);
                
        $table = GROUP_NAME . ' as gn';
        $where = array("gn.status" => "1");
        $fields = array("gn.group_unique_name");
        $GroupNameData = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where, '', $where_in);
        foreach ($GroupNameData as $value) {
            $check_domen_name[] = $value['group_unique_name'];            
        }

$find_appname = array_intersect($check_domen_name, $grop_val);       
pr($find_appname);
        pr($check_domen_name);

//$check_domen_name = array('NFCTracker-Development','NFCTracker-PreProduction','NFCTracker-Production');
//pr($check_domen_name);exit;
foreach ($find_appname as $group_value) {
    echo in_array($group_value, $check_domen_name);
    $key = in_array($group_value, $check_domen_name);   
}

if (in_array($group_value, $check_domen_name)){
        $key = in_array($group_value, $check_domen_name);   
        unset($group_array[$key]);
        $data_group = array_values($group_array);
        
        $match = "rm.status = 1 && rm.is_delete = 0 && rgt.group_name = 'NFCTracker-Development' &&";
             //   $selectedGroup=$group_array[count($group_array)-1];
                $table = ROLE_MASTER.' as rm';
                if(count($data_group)>0){
                    $c=1;
                     $match .='(';
                    for($i=0;$i<count($data_group);$i++){
                        if((count($data_group)-1)==$i)
                    {
                      $match .= "rm.role_name = '" . $data_group[$i] . "'";
                    }else
                    {
                      $match .= "rm.role_name = '" . $data_group[$i] . "' or ";  
                    }
                   }
                    $match .=')';
                }

        $join_tables = array(ROLE_GROUP_TRANS . ' as rgt' => 'rgt.role_id=rm.role_id');
        $role_result = $this->common_model->get_records($table, array("rm.role_id,rm.role_name,rm.group_ref_id,rgt.group_name"), $join_tables,'left', $match,'','','','rm.group_ref_id','ASC');
  }else{
        $this->session->unset_userdata('access_token');
                    $this->session->unset_userdata('token_type');
                    $return_to = base_url('Masteradmin/error');
                    $feedURL = "https://login.microsoftonline.com/".$this->config->item('directory_identifier')."/oauth2/logout?post_logout_redirect_uri=". urlencode($return_to);
                    redirect($feedURL);
  }

                if(!empty($role_result)){
                    $role_name = $role_result[0]['role_name'];
                }else{
                    $this->session->unset_userdata('access_token');
                    $this->session->unset_userdata('token_type');
                    $return_to = base_url('Masteradmin/error');
                    $feedURL = "https://login.microsoftonline.com/".$this->config->item('directory_identifier')."/oauth2/logout?post_logout_redirect_uri=". urlencode($return_to);
                    redirect($feedURL);
                }
          }else{
                    $this->session->unset_userdata('access_token');
                    $this->session->unset_userdata('token_type');
                    $return_to = base_url('Masteradmin/error');
                    $feedURL = "https://login.microsoftonline.com/".$this->config->item('directory_identifier')."/oauth2/logout?post_logout_redirect_uri=". urlencode($return_to);
                    redirect($feedURL);
            }
}
        $email = $user_info->{'userPrincipalName'};
        //$role_name = $user_info->{'jobTitle'};
        
        $table = ROLE_MASTER.' as rm';
        $match = "rm.role_name = '" . $role_name . "' && rm.status = 1 && rm.is_delete = 0";
        $role_result = $this->common_model->get_records($table, array("rm.role_id,rm.role_name"), '', '', $match);
        if(!empty($role_result)){
        $table = LOGIN.' as l';
        $match = "l.email = '" . $email . "' && l.status = 'active' && l.is_delete = 0";
        $user_data = $this->common_model->get_records($table, array("l.login_id, l.firstname, l.lastname, l.email, l.role_id"), '', '', $match);
        if(empty($user_data)){
            $data = array(
                'firstname' => $user_info->{'givenName'},
                'lastname' => $user_info->{'surname'},
                'email' => $user_info->{'userPrincipalName'},
                'address' => $user_info->{'streetAddress'},
                'mobile_number' => $user_info->{'mobile'},
                'role_id' => $role_result[0]['role_id'],
                'created_date' => datetimeformat(),
                'modified_date' => datetimeformat()
            );
             $this->common_model->insert(LOGIN, $data);
             $user_id = $this->db->insert_id();
             $sess_array = array(
                    'ID' => $user_id,
                    'EMAIL' => $user_info->{'userPrincipalName'},
                    'FIRSTNAME' => $user_info->{'givenName'},
                    'LASTNAME' => $user_info->{'surname'},
                    'ROLE_TYPE' => $role_result[0]['role_id'],
                    'TIMEZONE' => 'timezone',
                );
             $this->session->set_userdata('LOGGED_IN', $sess_array);
            $session_id = session_id();
              $newdata = array(
                            'name' => !empty($user_info->{'givenName'}) ? $user_info->{'givenName'}.' '.$user_info->{'surname'} : '',
                            'admin_id' => $user_id,
                            'admin_email' => $user_info->{'userPrincipalName'},
                            'admin_type' => $role_result[0]['role_id'],
                            'admin_image' => '',
                            'session_id' => $session_id,
                            'active' => TRUE);
                        $this->session->set_userdata('nfc_admin_session', $newdata);

            redirect(base_url('Dashboard'));
        }else{
            $data = array(
                'firstname' => $user_info->{'givenName'},
                'lastname' => $user_info->{'surname'},
                'email' => $user_info->{'userPrincipalName'},
                'address' => $user_info->{'streetAddress'},
                'mobile_number' => $user_info->{'mobile'},
                'role_id' => $role_result[0]['role_id'],
                'created_date' => datetimeformat(),
                'modified_date' => datetimeformat()
            );
    //pr($data);exit;
    $where = array('login_id' => $user_data[0]['login_id']);
    $success_update = $this->common_model->update(LOGIN, $data, $where);

             $sess_array = array(
                    'ID' => $user_data[0]['login_id'],
                    'EMAIL' => $user_info->{'userPrincipalName'},
                    'FIRSTNAME' => $user_info->{'givenName'},
                    'LASTNAME' => $user_info->{'surname'},
                    //'ROLE_TYPE' => $user_data[0]['role_id'],
                    'ROLE_TYPE' => $role_result[0]['role_id'],
                    'TIMEZONE' => 'timezone',
                );
            $this->session->set_userdata('LOGGED_IN', $sess_array);

            $session_id = session_id();
              $newdata = array(
                            'name' => !empty($user_info->{'givenName'}) ? $user_info->{'givenName'}.' '.$user_info->{'surname'} : '',
                            'admin_id' => $user_data[0]['login_id'],
                            'admin_email' => $user_info->{'userPrincipalName'},
                            'admin_type' => $role_result[0]['role_id'],
                            'admin_image' => '',
                            'session_id' => $session_id,
                            'active' => TRUE);
                        $this->session->set_userdata('nfc_admin_session', $newdata);

            redirect(base_url('Dashboard'));
        }
       }else{
                $this->session->unset_userdata('access_token');
                $this->session->unset_userdata('token_type');
                $return_to = base_url('Masteradmin/error');
                $feedURL = "https://login.microsoftonline.com/".$this->config->item('directory_identifier')."/oauth2/logout?post_logout_redirect_uri=". urlencode($return_to);
                    redirect($feedURL);
       }
        }
        
    }

public function getAuthorizatonURL(){

        $authUrl = "https://login.windows.net/common/oauth2/authorize". "?" .
                   "response_type=code" . "&" .
                   "client_id=" . $this->config->item('client_id') . "&" .
                   "resource=" . $this->config->item('resource_uri') . "&" .
                   "redirect_uri=" . $this->config->item('redirect_uri_segment');
        return $authUrl;
    }

public function getMeEntry(){
            // initiaze curl which is used to make the http request
            $ch = curl_init();
            
            // Add authorization and other headers. Also set some common settings.
            self::AddRequiredHeadersAndSettings($ch);
            ////echo $feedURL = "https://graph.windows.net/".$this->config->item('directory_identifier')."/users/b7bfc877-f6b0-46d8-9b09-47da3deed9b6/memberOf?api-version=1.6";
            // Create url for the entry based on the feedname and the key value
            $feedURL = "https://graph.windows.net/".$this->config->item('directory_identifier')."/me/";
            $feedURL = $feedURL."?".$this->config->item('apiVersion');
            curl_setopt($ch, CURLOPT_URL, $feedURL);             
            //Enable fiddler to capture request
            //curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888');
            // $output contains the output string 
            $output = curl_exec($ch);
            
            // close curl resource to free up system resources 
            curl_close($ch);      
            $jsonOutput = json_decode($output);
            return $jsonOutput;
        }

public function getGroupNameFromUserObject($objId){
            // initiaze curl which is used to make the http request
            $ch = curl_init();
            
            // Add authorization and other headers. Also set some common settings.
            self::AddRequiredHeadersAndSettings($ch);
             $feedURL = "https://graph.windows.net/".$this->config->item('directory_identifier')."/users/".$objId."/memberOf?api-version=1.6";
            // Create url for the entry based on the feedname and the key value
            //$feedURL = "https://graph.windows.net/".$this->config->item('directory_identifier')."/me/";
            //$feedURL = $feedURL."?".$this->config->item('apiVersion');
            curl_setopt($ch, CURLOPT_URL, $feedURL);             
            //Enable fiddler to capture request
            //curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888');
            // $output contains the output string 
            $output = curl_exec($ch);
            
            // close curl resource to free up system resources 
            curl_close($ch);      
            $jsonOutput = json_decode($output);
            return $jsonOutput;
        }

         public function AddRequiredHeadersAndSettings($ch)
        {
            //Generate the authentication header
            $authHeader = 'Authorization:' . $_SESSION['token_type'].' '.$_SESSION['access_token'];
            curl_setopt($ch, CURLOPT_HTTPHEADER, array($authHeader, 'Accept:application/json;odata=minimalmetadata',
                                                       'Content-Type:application/json'));           
            // Set the option to recieve the response back as string.
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            // By default https does not work for CURL.
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
    /*
      Author : Rupesh Jorkar(RJ)
      Desc   : Verify login information
      Input  : Post User Email and password for verify
      Output : If login then redirect on Home page and if not then redirect on login page
      Date   : 18/01/2016
     */

    public function verifylogin() {
        $this->form_validation->set_error_delimiters(ERROR_START_DIV, ERROR_END_DIV);
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_database');

        if ($this->form_validation->run() == FALSE) {
            //Field validation failed.  User redirected to login page
            //$error_msg = ERROR_START_DIV_NEW . lang('ERROR_MSG_LOGIN') . ERROR_END_DIV;
            $error_msg = ERROR_START_DIV_NEW . lang('invalid_us_pass') . ERROR_END_DIV;
            //echo $error_msg;exit;
            //$this->session->set_userdata('ERRORMSG', $error_msg);
            $this->session->set_flashdata('error', $error_msg);
            redirect($this->viewname);
        } else {
            //Login sucessfully done so now redirect on Dashboard page
            $data['user_info'] = $this->session->userdata('LOGGED_IN');  //Current Login information

            $master_user_id = $this->config->item('master_user_id');
            //$master_user_id = $data['user_info']['ID'];
            redirect(base_url('Dashboard')); //Redirect on Dashboard
        }
    }

    /*
      Author : Rupesh Jorkar(RJ)
      Desc   : This function is Call back function
      Input  : $password
      Output : Return false and true
      Date   : 18/01/2016
     */

    function check_database() {
        $browser = $_SERVER['HTTP_USER_AGENT'];
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $session_id = session_id();
        //PROFILE_PHOTO added by sanket on 29_02_2016
        
        $email = quotes_to_entities($this->input->post('email'));
        $password = quotes_to_entities($this->input->post('password'));
        //timezone added by maulik suthar 15-03-16
        $timezone = $this->input->post('timezone');
        //Compare Email and password from database
        $table = LOGIN.' as l';
        $match = "l.email = '" . $email . "' && l.password = '" . md5($password) . "' && l.status = 'active' && l.is_delete = 0";
         $join_tables = array(ROLE_MASTER . ' as rm' => 'rm.role_id=l.role_id');
        $result = $this->common_model->get_records($table, array("l.login_id, l.firstname, l.lastname, l.email, l.role_id,rm.role_name"), $join_tables, 'left', $match);
        if ($result) {
            $sess_array = array();
            foreach ($result as $row) {
                $sess_array = array(
                    'ID' => $row['login_id'],
                    'EMAIL' => $row['email'],
                    'FIRSTNAME' => $row['firstname'],
                    'LASTNAME' => $row['lastname'],
                    'ROLE_TYPE' => $row['role_id'],
                    'TIMEZONE' => $timezone,
                    //'session_id' => $session_id
                );
                
                $this->session->set_userdata('LOGGED_IN', $sess_array);
            }

            return TRUE;
        } else {
            $this->form_validation->set_message('check_database', $this->lang->line('ERROR_INVALID_CREDENTIALS'));
            return false;
        }
    }

    /*
      Author : Ritesh Rana
      Desc   : Forgotpassword page redirect
      Input  :
      Output :
      Date   : 14/03/2017
     */

    public function forgotpassword() {


        $data['main_content'] = '/forgotpassword';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }

    /*
      Author : Ritesh Rana
      Desc   : resetpassword prepare template and sent to requester
      Input  :
      Output :
      Date   : 15/04/2017
     */

    public function resetpassword() {

        $this->form_validation->set_error_delimiters(ERROR_START_DIV, ERROR_END_DIV);
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

        if ($this->form_validation->run() == FALSE) {
            $msg = validation_errors();
            $this->session->set_flashdata('msg', $msg);
            redirect('Masteradmin/forgotpassword');
        } else {
            $exitEmailId = $this->checkEmailId($this->input->post('email'));
            if (empty($exitEmailId)) {
                // error
                $msg = $this->lang->line('email_id_not_exit');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                //redirect('user/register');
                redirect('Masteradmin/forgotpassword');
            } else {
                if ($this->input->post('email')) {

                    $token = md5($this->input->post('email') . date("Y-m-d H:i:s"));
                    $newpasswordlink = "<a href='" . base_url() . "/Masteradmin/updatepassword?token=" . $token . "'>" . "Click Here" . "</a>";

                    // Get Template from Template Master
                    $table = EMAIL_TEMPLATE_MASTER . ' as et';
                    // $match = "et.subject ='Forgot Password' ";
                    $match = "et.template_id =1";
                    $fields = array("et.subject,et.body");
                    $template = $this->common_model->get_records($table, $fields, '', '', $match);

                    $body1 = str_replace("{PASS_KEY_URL}", $newpasswordlink, $template[0]['body']);

                    $to = $this->input->post('email');
                    $body = str_replace("{SITE_NAME}", base_url(), $body1);
                    $subject = "NFC Tracker :: " . $template[0]['subject'];

                    $data = array('reset_password_token' => $token, 'modified_date' => datetimeformat());
                    $where = array('email' => $this->input->post('email'));

                    if ($this->common_model->update(LOGIN, $data, $where)) {
                        //send_mail($to, $subject, $body);
                        if (send_mail($to, $subject, $body)) {
                            $msg = $this->lang->line('new_password_sent');
                        } else {

                            $msg = $this->lang->line('FAIL_WITH_SENDING_EMAILS');
                        }

                        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                        redirect('Masteradmin/forgotpassword');
                    } else {
                        // error
                        $msg = $this->lang->line('error_msg');
                        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                        //redirect('user/register');
                        redirect('Masteradmin/forgotpassword');
                    }
                }
            }

            redirect('Masteradmin/forgotpassword');
        }
    }

    /*
      Author : Ritesh Rana
      Desc   : Update Password Page
      Input  :
      Output :
      Date   : 15/03/2017
     */

    public function updatepassword() {
    	$token_ID = $this->input->get('token');
    	if($token_ID != ""){

    		$table1 = LOGIN . ' as l';
    		$match1 = "l.reset_password_token = '".$token_ID."'";
    		$fields1 = array("l.login_id");
    		$checkTokenexist = $this->common_model->get_records($table1, $fields1, '', '', $match1);
    		if(isset($checkTokenexist[0]['login_id']) && $checkTokenexist[0]['login_id'] !="" ){
    			$data['main_content'] = '/updatepassword';

                        $this->parser->parse('layouts/DefaultTemplate', $data);
                        
    		}else{
    			 
    			redirect('Masteradmin');
    		}

    	}else{
			
			redirect('Masteradmin');
    	}

    }

    /*
      Author : Ritesh Rana
      Desc   : Update Password to requested person redirect to updatepassword page
      Input  :
      Output :
      Date   : 16/03/2017
     */

    public function updatePasswords() {
        $this->form_validation->set_rules('password', 'New Password', 'trim|required|md5');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|md5|matches[password]');

        if ($this->form_validation->run() == FALSE) {

            $msg = validation_errors();
            $this->session->set_flashdata('msgs', "<div class='alert alert-danger text-center'>$msg</div>");

            $redirect_to = str_replace(base_url(), '', $_SERVER['HTTP_REFERER']);
            redirect($redirect_to);
        } else {

            $tokenID = $this->input->post('tokenID');
            $password = $this->input->post('password');

            if($tokenID !=""){
            	
	           	
            	
            	$data = array('password' => $password, 'modified_date' => datetimeformat());
            	$where = array('reset_password_token' => $tokenID);

            	$affectedrow = $this->common_model->update(LOGIN, $data, $where);
            	//$affectedrow = $this->db->affected_rows();
			
            	if ($affectedrow) {
            		$msg = $this->lang->line('newpasswordupdated');
            		$this->session->set_flashdata('msgs', "<div class='alert alert-success text-center'>$msg</div>");
            		// Once Requester update the password with token then here Token will be remove from db.
            		$data1 = array('reset_password_token' => '', 'modified_date' => datetimeformat());
            		$where1 = array('reset_password_token' => $tokenID);
            		$this->common_model->update(LOGIN, $data1, $where1);

            		redirect('Masteradmin');
            	} else {
            		// error
            		$msg = $this->lang->line('change_password_token_expired');
            		$this->session->set_flashdata('msgs', "<div class='alert alert-danger text-center'>$msg</div>");
            		//redirect('user/register');
            		redirect('Masteradmin/updatepassword');
            	}
            }else{
					// error
            		$msg = $this->lang->line('change_password_token_expired');
            		$this->session->set_flashdata('msgs', "<div class='alert alert-danger text-center'>$msg</div>");
            		redirect('user/register');
            			
			}
            
            
            
        }
    }

    /*
      Author : Ritesh Rana
      Desc   : Check Email id is exist into DB or not
      Input  :
      Output :
      Date   : 16/03/2017
     */

    public function checkEmailId($emailID) {
        $table = LOGIN . ' as l';
        $match = "l.email = '" . $emailID . "' AND l.is_delete = 0";
        $fields = array("l.login_id,l.status");
        $data['duplicateEmail'] = $this->common_model->get_records($table, $fields, '', '', $match);
        return $data['duplicateEmail'];
    }

    public function error(){
            $data['heading'] = '404 Page Not Found';
            $data['message'] = 'You are not authorised to access this site';
        $this->load->view('error_404',$data);
    }
}
