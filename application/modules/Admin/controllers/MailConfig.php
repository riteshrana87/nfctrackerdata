<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MailConfig extends CI_Controller {

    function __construct() {
        parent::__construct();
        check_admin_login();
        $this->type = "Admin";
        $this->viewname = $this->uri->segment(2);
        $this->load->library(array('form_validation'));
        $this->load->library('imap');
    }

    /*
      @Author : Dhara Bhalala
      @Desc   : Yp listing
      @Input  :
      @Output :
      @Date   : 1/11/2018
     */

    public function index($page = '') {
        $searchtext = $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = $this->input->post('perpage');
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('mail_config_data');
        }
        $searchsort_session = $this->session->userdata('mail_config_data');
        //Sorting
        if (!empty($sortfield) && !empty($sortby)) {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        } else {
            if (!empty($searchsort_session['sortfield'])) {
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby'] = $searchsort_session['sortby'];
                $sortfield = $searchsort_session['sortfield'];
                $sortby = $searchsort_session['sortby'];
            } else {
                $sortfield = 'yp.yp_id';
                $sortby = 'desc';
                $data['sortfield'] = $sortfield;
                $data['sortby'] = $sortby;
            }
        }
        //Search text
        if (!empty($searchtext)) {
            $data['searchtext'] = $searchtext;
        } else {
            if (empty($allflag) && !empty($searchsort_session['searchtext'])) {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext = $data['searchtext'];
            } else {
                $data['searchtext'] = '';
            }
        }

        if (!empty($perpage) && $perpage != 'null') {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        } else {
            if (!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
                $data['perpage'] = '10';
            }
        }
        //pagination configuration
        $config['first_link'] = 'First';
        $config['base_url'] = base_url() . $this->type . '/' . $this->viewname . '/index';
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        //Query
        $user_data = $this->session->userdata('nfc_admin_session');
        $table = EMAIL_CONFIG . ' as ec';
        $fields = array("yp.yp_fname,yp.yp_lname,yp.yp_id,ch.care_home_name");
        $join_tables = array(
            YP_DETAILS . ' as yp' => 'yp.yp_id=ec.user_id',
            CARE_HOME . ' as ch' => 'ch.care_home_id = yp.care_home'
        );
        if (!empty($searchtext)) {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search = '((ch.care_home_name LIKE "%' . $searchtext . '%" 
                            OR yp.yp_fname LIKE "%' . $searchtext . '%" 
                            OR yp.yp_lname LIKE "%' . $searchtext . '%" 
                            OR ch.status LIKE "%' . $searchtext . '%")	
                            AND ch.is_delete = "0")';

            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1', '');
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', '', '', '', '1');
        }

        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        $data['footerJs'][0] = base_url('uploads/custom/js/mail_config/mail_config.js');
        $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
        $sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uri_segment,
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('mail_config_data', $sortsearchpage_data);
        setActiveSession('mail_config_data');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->type . '/' . $this->viewname . '/ajax_list', $data);
        } else {
            $data['main_content'] = $this->type . '/' . $this->viewname . '/list';
            $this->load->view($this->type . '/assets/template', $data);
        }
    }

    /*
      @Author : Dhara Bhalala
      @Desc   : User Form validation
      @Input 	:
      @Output	:
      @Date   : 1/11/2018
     */

    public function formValidation($id = null) {
        $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[3]|max_length[30]|xss_clean');
    }

    /*
      @Author : Dhara Bhalala
      @Desc   : password Edit Function
      @Input  : User
      @Output :
      @Date   : 1/11/2018
     */

    public function edit($id) {
        $this->formValidation($id);
        if ($this->form_validation->run() == FALSE) {
            $data['footerJs'][0] = base_url('uploads/custom/js/mail_config/mail_config.js');
            $this->session->unset_userdata('FORM_SECRET', '');
            $table = EMAIL_CONFIG . ' as ec';
            $match = "ec.user_id = '" . $id . "'";
            $fields = array("ec.email_id,ec.email_pass,ec.email_server,ec.email_port,ec.email_encryption,ec.email_smtp,ec.email_smtp_port,yp.yp_fname,yp.yp_lname,yp.yp_id,ch.care_home_name");
            $join_tables = array(
                YP_DETAILS . ' as yp' => 'yp.yp_id=ec.user_id',
                CARE_HOME . ' as ch' => 'ch.care_home_id = yp.care_home'
            );
            $data['editRecord'] = $this->common_model->get_records($table, $fields, $join_tables, '', $match);
            $data['id'] = $id;
            $main_user_data = $this->session->userdata('nfc_admin_session');
            $data['userType'] = getUserType($main_user_data['admin_type']);

            $data['validation'] = validation_errors();
            $data['header'] = array('menu_module' => 'masters', 'menu_child' => 'user');
            $data['crnt_view'] = $this->viewname;
            $data['form_action_path'] = ADMIN_SITE . '/' . $this->viewname . '/edit/' . $id;
            $data['main_content'] = $this->viewname . '/addEdit';
            if (isset($data['editRecord'][0]['role_id'])) {
                $roleName = getRoleName($data['editRecord'][0]['role_id']);
                $data['roleName'] = $roleName[0]['role_name'];
            }
            $this->parser->parse(ADMIN_SITE . '/assets/template', $data);
        } else {
            $this->updatedata();
        }
    }

    /*
      @Author : Dhara Bhalala
      @Desc   : Update mail config Data
      @Input 	: Yp_id
      @Output	:
      @Date   : 1/11/2018
     */

    public function updatedata() {
        set_time_limit(0);
        $unreadMessages = $responseData = array();
        $id = $this->input->post('yp_id');
        $data = array(
            'email_pass' => base64_encode($this->input->post('password')),
            /* 'email_server' => $this->input->post('email_server'),
              'email_port' => $this->input->post('email_port'),
              'email_encryption' => $this->input->post('email_encryption'),
              'email_smtp' => $this->input->post('email_smtp'),
              'email_smtp_port' => $this->input->post('email_smtp_port'), */
            'modified_date' => datetimeformat(),
        );
        /* $isUpdateAllPwd = $this->input->post('updateAllPassword');
          if(!isset($isUpdateAllPwd) || $isUpdateAllPwd == '') */
        $where = array('user_id' => $id);
        /* else
          $where = array(); */

        if (isset($id) && $this->common_model->update(EMAIL_CONFIG, $data, $where)) {
            $emailData = $this->getEmailConfigData($id);
            if (!empty($emailData[0]['email_id']) && !empty($emailData[0]['email_pass']) && !empty($emailData[0]['email_server']) && !empty($emailData[0]['email_port']) && !empty($emailData[0]['email_encryption']) && !empty($emailData[0]['email_smtp']) && !empty($emailData[0]['email_smtp_port'])) {
                $emailAccountDetails = array(
                    'email_server' => $emailData[0]['email_server'],
                    'email_port' => $emailData[0]['email_port'],
                    'email_id' => $emailData[0]['email_id'],
                    'email_pass' => $emailData[0]['email_pass'],
                    'email_encryption' => $emailData[0]['email_encryption']
                );
                if ($this->imap->isConnected()) {
                    $this->imap->close();
                }
                if ($this->checkImapConnection($emailAccountDetails)) {
                    $folderlist = $this->imap->getFolders();
                    if (!empty($folderlist)) {
                        foreach ($folderlist as $valf) {
                            $this->imap->selectFolder($valf);
                            $unreadMessages[$valf] = $this->imap->countUnreadMessages();
                        }
                        $emailDatafolder['account_folder'] = json_encode($unreadMessages);
                    }
                    $where = array("user_id" => $this->input->post('yp_id'));
                    $emailConfigData = $this->common_model->get_records(EMAIL_CONFIG, '', '', '', '', '', '', '', '', '', '', $where, '', '');
                    if (!empty($emailConfigData)) {
                        $success_update = $this->common_model->update(EMAIL_CONFIG, $emailDatafolder, $where);
                        $this->common_model->delete(EMAIL_CLIENT_MASTER, $where);
                        $this->common_model->delete(EMAIL_CLIENT_ATTACHMENTS, $where);
                        //$this->pagingMails(1, 10, 'INBOX',$id);
                    }
                    $msg = $this->lang->line('mail_config_success_update');
                    $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                } else {
                    error_reporting(0);
                    $msg = (string) $this->imap->getError();
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                    redirect(ADMIN_SITE . '/' . $this->viewname);
                }
            } else {
                $msg = $this->lang->line('mail_config_error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
        } else {
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }
        redirect(ADMIN_SITE . '/' . $this->viewname);
    }

    public function getEmailConfigData($userId = '', $fields = '') {

        $configData = array();

        if (empty($userId)) {

            return $configData;
        }
        $where = "user_id = " . $userId;
        $configData = $this->common_model->get_records(EMAIL_CONFIG, $fields, '', '', '', '', '1', '', '', '', '', $where, '', '', '', '', '', '');


        return $configData;
    }

    public function checkImapConnection($configEmailData = array()) {

        if (empty($configEmailData)) {
            return false;
        }

        $mailbox = $configEmailData['email_server'] . ':' . $configEmailData['email_port'] . '/';

        $username = $configEmailData['email_id'];
        $password = base64_decode($configEmailData['email_pass']);
        $encryption = $configEmailData['email_encryption'];
        if ($this->imap->connect($mailbox, $username, $password, $encryption)) {

            return true;
        } else {
            return false;
        }
    }

}
