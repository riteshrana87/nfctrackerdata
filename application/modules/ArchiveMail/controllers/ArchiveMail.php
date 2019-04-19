<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ArchiveMail extends CI_Controller {

    public $viewname;
    public $userId;

    function __construct() {
        parent::__construct();
        //   error_reporting(0);
        $this->viewname = $this->router->fetch_class();
        //$this->load->library(array('form_validation', 'Encryption','Session','m_pdf'));
        $this->load->library(array('form_validation', 'Session','m_pdf'));
        $this->load->model('Mail_model');
        $this->load->library('imap');
        $this->load->model('Common_model');
        $this->userId = $this->session->userdata('LOGGED_IN')['ID'];
        /*
         * it redirects to config page if user havent updated email config 
         */
    }

    /*
      @Author : Ritesh Rana
      @Desc   : This module is for email management
      @Input  :
      @Output :
      @Date   : 17/11/2017
     */

   public function index($yp_id, $boxtype='INBOX', $care_home_id = 0, $past_care_id = 0, $perpage = 0) {

      /*
          Ritesh Rana
          for past care id inserted for archive full functionality
         */
        if ($past_care_id !== 0) {
            $temp_match = array("yp_id" => $yp_id, "past_carehome" => $care_home_id);
            $temp = $this->common_model->get_records(PAST_CARE_HOME_INFO, array('move_date'), '', '', $temp_match);
            $last_date = $temp[0]['move_date'];
            $match = array("yp_id" => $yp_id, "move_date <= " => $last_date);
            $data_care_home_detail['care_home_detail'] = $this->common_model->get_records(PAST_CARE_HOME_INFO, array("*"), '', '', $match);
            $created_date = $movedate = '';

            $count_care = count($data_care_home_detail['care_home_detail']);

            if ($count_care >= 1) {
                $created_date = $data_care_home_detail['care_home_detail'][0]['enter_date'];
                $movedate = $data_care_home_detail['care_home_detail'][$count_care - 1]['move_date'];
            } elseif ($count_care == 0) {
                $created_date = $data_care_home_detail['care_home_detail'][0]['enter_date'];
                $movedate = $data_care_home_detail['care_home_detail'][0]['move_date'];
            }
        }
    if(is_numeric($yp_id))
       {
        $this->load->library('ajax_pagination_mail');
        $configArr = $this->getEmailConfigData($yp_id);
         if ($this->checkImapConnection($configArr[0]) == false) {
              $msg = $this->lang->line('mailconfig_message');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$yp_id);
             die;
        }

      if($this->input->get('boxtype')){
        $boxtype = $this->input->get('boxtype');
      }else{
        $boxtype = urldecode($boxtype);  
      }

    $data['box_types'] = $boxtype;

        $searchtext = ($this->input->post('searchtext')) ? $this->input->post('searchtext') : '';
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('mail_data');
        }

        $searchsort_session = $this->session->userdata('mail_data');
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
                $sortfield = 'email_unq_id,send_date';
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
        
        /*$config['first_link'] = '<i class="fa fa-backward"></i>';
        $config['last_link'] = '<i class=" fa fa-forward"></i>';
        $config['next_link'] = '<i class="fa fa-caret-right "></i>';
        $config['prev_link'] = '<i class="fa fa-caret-left"></i>';
        */

        $config['first_link']  = 'First';
       //$config['num_links'] = 0;
        $config['base_url'] = base_url() . 'ArchiveMail/index/'.$yp_id .'/'. $data['box_types'] . '/' . $care_home_id . '/' . $past_care_id;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uriSegment = 0;
        } else {
            $config['uri_segment'] = 7;
            $uriSegment = $this->uri->segment(7);
        }
        $group_by = 'ec.uid';
        $where = "ec.boxtype='" . $boxtype . "' and ec.user_id =" . $yp_id;
          //$where = "ec.boxtype='" . $data['box_types'] . "' AND ec.user_id =" . $yp_id ."' AND ec.created_date BETWEEN  '" . $created_date . "' AND '" . $movedate;

        if ($data['box_types'] != 'Trash'):
            $where .= ' && ec.is_deleted =0';
        endif;

    $fields = array("ec.*,sum(file_size) as file_size");
    $join = array(EMAIL_CLIENT_ATTACHMENTS . ' as ema' => 'ema.email_id=ec.email_unq_id');

        if (!empty($searchtext)) {
//If have any search text
            $searchtext = html_entity_decode(trim($searchtext));
            $whereSearch = '(to_mail LIKE "%' . $searchtext . '%" OR send_date LIKE "%' . $searchtext . '%" OR mail_subject LIKE "%' . $searchtext . '%" OR mail_body LIKE "%' . $searchtext . '%")';

            $whereSearch .= " AND ec.send_date BETWEEN  '" . $created_date . "' AND '" . $movedate . "'";

            $data['mail_data'] = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', $fields, $join, 'left', '', '', $config['per_page'], $uriSegment, $sortfield, $sortby, $group_by, $where, '', '', '', '', '', $whereSearch);

            $config['total_rows'] = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', $fields, $join, 'left', '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1', '', '', $whereSearch);
        } else {
          $where_data = "ec.send_date BETWEEN  '" . $created_date . "' AND '" . $movedate . "'";
//Not have any search input
            $data['mail_data'] = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', $fields, $join, 'left', '', '', $config['per_page'], $uriSegment, $sortfield, $sortby, $group_by, $where, '', '', '', '', '', $where_data);
            $config['total_rows'] = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', $fields, $join, 'left', '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1', '', '', $where_data);
        }
        
        //get YP information
          $match = array("yp_id"=>$yp_id);
          $fields = array("*");
          $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

        $liveCount = $this->getTotalMailCount($boxtype,$yp_id);
        $dbCount = $config['total_rows'];
        
        $where_data = "ec.send_date BETWEEN  '" . $created_date . "' AND '" . $movedate . "'";
        $data['inbox_count'] = $this->YPmailCount($yp_id);

         $fields = array("ec.is_unread");
        $join = array(EMAIL_CLIENT_ATTACHMENTS . ' as ema' => 'ema.email_id=ec.email_unq_id');
        $group_by = 'ec.uid';
        $boxtype = 'INBOX';
        $where = "ec.boxtype='" . $boxtype . "' and ec.user_id ='" . $yp_id ."'and ec.is_unread = '1'";
        $mail_count = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', $fields, $join, 'left', '', '', '', '', '', '', $group_by, $where,'','','','','',$where_data);

        $data['inbox_count'] = count($mail_count);
        

        $data['liveCount'] = $liveCount;
        $data['dbCount'] = $dbCount;
        // build query
        $config['use_page_numbers'] = true;
        $this->ajax_pagination_mail->initialize($config);
        $data['pagination'] = $this->ajax_pagination_mail->create_links();
        $data['uri_segment'] = $uriSegment;

        $sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'box_types' => $data['box_types'],
            'searchtext' => $data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uriSegment,
            'total_rows' => $config['total_rows']);
        
        $this->session->set_userdata('mail_data', $sortsearchpage_data);
        $data['drag'] = true;
        $data['account_view'] = $this->viewname;
        $data['sales_view'] = $this->viewname;
        $data['header'] = array('menu_module' => 'MyProfile');
        $unreadMessages = array();
        $emailData = $this->getEmailConfigData($yp_id)[0];

        $emailAccountDetails = array(
            'email_server' => $emailData['email_server'],
            'email_port' => $emailData['email_port'],
            'email_id' => $emailData['email_id'],
            'email_pass' => base64_decode($emailData['email_pass']),
            'email_encryption' => $emailData['email_encryption']
        );

        $data['yp_email'] = $emailData['email_id'];

        $unreadMessagesData = $this->getAccounFolderList($emailAccountDetails, false,$yp_id); // get Folder lists

        $data['care_home_id'] = $care_home_id;
        $data['past_care_id'] = $past_care_id;
        $data['leftMenuFolderCount'] = $unreadMessagesData;
        $data['js_content'] = '/jquery/loadJsFiles';
         $data['ypid'] = $yp_id;
        if (isset($_REQUEST['type']) && $_REQUEST['type'] == 'full') {
            $this->load->view('mailMainThread', $data);
        } else if ($this->input->post('result_type') == 'ajax') {
            $this->load->view('MailAjaxList', $data);
        } else {

            $data['main_content'] = 'Mail';
            $this->parser->parse('layouts/MailTemplate', $data);
        }
    }
        else
        {
            show_404 ();
        }
    }
   

    function saveAttachment($id,$ypid) {

        $this->load->library('zip');

        $folder = "/uploads/Mail/" . $ypid;
        $dataAttachment = $this->common_model->get_records(EMAIL_CLIENT_ATTACHMENTS . ' as eca', '', array(EMAIL_CLIENT_MASTER . ' as ecm' => 'ecm.email_unq_id=eca.email_id'), 'LEFT', 'email_id=' . $id . '', '', '', '', '', '', '', '', '', '', '', '', '', '');

        if (count($dataAttachment) > 1) {
            $path = FCPATH . $folder . '/' . $dataAttachment[0]['uid'] . '/';
            $this->zip->read_dir($path, false);
            $zip_name = 'attachment-' . $dataAttachment[0]['uid'];
            $this->zip->download($zip_name);
        }else if(count($dataAttachment) == 1){
            $absPath = base_url() . "uploads/Mail/".$ypid."/".$dataAttachment[0]['uid']."/" . $dataAttachment[0]['uid'] . '-' . $dataAttachment[0]['file_name'];
            $pathrel = htmlentities($absPath);
            redirect(base_url('CommonController/downloadFile/?file_name=' . urlencode(base64_encode($pathrel))));
        }
    }


    /*
      @Author : Ritesh Rana
      @Desc   : This function is use to get header
      @Input 	:
      @Output	:
      @Date   : 17/11/2018
     */

    public function getHeader($param = NULL) {

        $data['header'] = array('menu_module' => 'crm');
        $this->parser->parse('layouts/HeaderTemplate', $data);
    }

    /*
      @Author : Ritesh Rana
      @Desc   : This function is to get unreaded mail count
      @Input  :
      @Output :
      @Date   : 17/11/2018
     */

    public function getTotalMailCount($folder,$yp_id) {
        $user_id = $yp_id;
        $userConfigData = $this->getEmailConfigData($user_id);
        if ($userConfigData) {
            if ($this->checkImapConnection($userConfigData[0])) {
                $this->imap->selectFolder($folder);
                return $this->imap->countMessages();
            } else {
                return 0;
            }
        }
    }

    function Message_Parse($id,$ypid) {
        $userid = $ypid;

        $join = array(EMAIL_CLIENT_ATTACHMENTS . ' as ema' => 'ema.email_id=ec.email_unq_id');
        $where = "uid=$id and ec.user_id=$userid";
        $result = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', '', $join, 'left', '', '', '', '', '', '', '', $where, '', '', '', '', '', '');
        return $result;
    }
     /*
      @Author : Ritesh Rana
      @Desc   : viewThread page
      @Input 	:
      @Output	:
      @Date   : 17-11-2018
     */

    function viewThread($uid,$ypid,$care_home_id,$past_care_id) { {
            $table = EMAIL_CONFIG . ' as ec';

            $userId = $ypid;
            $emailData_data = $this->getEmailConfigData($ypid)[0];

                         //get YP information
          $match = array("yp_id"=>$ypid);
          $fields = array("*");
          $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

        $emailAccountDetails = array(
            'email_server' => $emailData_data['email_server'],
            'email_port' => $emailData_data['email_port'],
            'email_id' => $emailData_data['email_id'],
            'email_pass' => base64_decode($emailData_data['email_pass']),
            'email_encryption' => $emailData_data['email_encryption']
        );

        $unreadMessagesData = $this->getAccounFolderList($emailAccountDetails, false,$ypid); // get Folder lists
        $data['leftMenuFolderCount'] = $unreadMessagesData;

            $emailConfigData = $this->getEmailConfigData($userId, array('email_signature', 'email_id'));
            $data['email_signature'] = (isset($emailConfigData[0]['email_signature'])) ? $emailConfigData[0]['email_signature'] : '';
            $data['fromMail'] = (isset($emailConfigData[0]['email_id'])) ? $emailConfigData[0]['email_id'] : '';



            $data['header'] = array('menu_module' => 'crm');
            $data['mailtype'] = "forward";
            $data['care_home_id'] = $care_home_id;
            $data['past_care_id'] = $past_care_id;
            $data['uid'] = $uid;
            $data['main_content'] = 'ComposeEmail';
            $emailData = $this->Message_Parse($uid,$ypid);

            if($emailData[0]['from_mail'] == $emailData_data['email_id']){
                  $yp_initials = $data['YP_details'][0]['yp_initials'];
                  $yp_init = substr($yp_initials,0,3);
                  $fromMail = YpInitialsWithEmail($yp_init,$data['fromMail']);
            }else{
              
              $obj = json_decode($emailData[0]['header_data']);
              $fromMail = str_replace('"','',$obj[0]->fromaddress); 
            }
            



            if (count($emailData) > 0) {
                $frmAddr = ($emailData[0]['from_mail_address']);

                $frmDate = $emailData[0]['send_date'];
                $frmSubject = $emailData[0]['mail_subject'];
                $frmTo = $emailData[0]['to_mail'];
                $frmCC = $emailData[0]['cc_email'];
                $frmEmail = $emailData[0]['from_mail'];
                $data['to'] = $frmTo;
                $body_data = $emailData[0]['mail_body'];
                if($this->isHTML($body_data) == true){
                     $defaultBody = $emailData[0]['mail_body'];
                }else{
                     $defaultBody = base64_decode($emailData[0]['mail_body']);
                }
                $data['subject'] = $frmSubject;
                $data['defaultBody'] = $defaultBody;
                $mail_files = array();
                $userid = $ypid;
                
                foreach ($emailData as $files) {

                    $dirpth = FCPATH . "uploads/Mail/$userid/$uid/" . $uid . '-' . $files['file_name'];
                    $fp = base_url() . "uploads/Mail/$userid/$uid/" . $uid . '-' . $files['file_name'];
                    $mail_files[] = array('file_name' => $uid . '-' . $files['file_name'], 'file_path' => $dirpth, 'auto_id' => $files['auto_id'], 'file_path_abs' => $fp, 'file_name_app' => $files['file_name_app']);
                }
            }


             //get YP information
          $match = array("yp_id"=>$ypid);
          $fields = array("*");
          $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

            $data['fromMail_data'] = $fromMail;
            $data['emailData'] = $emailData;
            $data['mail_files'] = $mail_files;
            $data['emailData'] = $emailData;
            $data['ypid'] = $ypid;
            $data['main_content'] = '/ViewMail';
            //$data['js_content'] = '/jquery/ViewMailJsFiles';
            $this->parser->parse('layouts/MailTemplate', $data);
        }
    }

function isHTML($text){
   $processed = htmlentities($text);
   if($processed == $text) return false;
   return true; 
}

    
    /*
      @Author : Ritesh Rana
      @Desc   : download attachment function
      @Input 	:
      @Output	:
      @Date   : 17/11/2018
     */

    function download($id) {
        if ($id > 0) {

            $this->load->library('zip');
            $params['fields'] = ['*'];
            $params['table'] = EMAIL_CLIENT_ATTACHMENTS . ' as CM';
            $params['match_and'] = 'CM.auto_id=' . $id . '';
            $params['join_tables'] = array(EMAIL_CLIENT_MASTER . ' as ecm' => 'ecm.email_unq_id=CM.email_id');
            $params['join_type'] = 'inner';
            $cost_files = $this->common_model->get_records_array($params);
            if (count($cost_files) > 0) {
                $folder = "/uploads/Mail/" . $this->session->userdata('LOGGED_IN')['ID'] . '/' . $cost_files[0]['uid'];

                $pth = file_get_contents(base_url($folder . '/' . $cost_files[0]['uid'] . '-' . $cost_files[0]['file_name']));

                $this->load->helper('download');
                force_download($cost_files[0]['file_name'], $pth);
            }
        }
    }

     /*
      @Author : Ritesh Rana
      @Desc   : read message
      @Input  :
      @Output :
      @Date   : 17/11/2018
     */

    function markasRead() {

        if (!$this->input->is_ajax_request()) {
            exit('No Direct Scripts are allowed');
        }

        $this->load->model('Common_model');
        $uid = $this->input->post('uid');
        $ypid = $this->input->post('ypid');
        $userId = $ypid;

        $where = " uid = $uid and ec.is_unread = 1 and ec.user_id = " . $userId;
        $result = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', '', '', '', '', '', '', '', '', '', '', $where, '', '', '', '', '', '');

        if (count($result) > 0) {

            $this->Common_model->update(EMAIL_CLIENT_MASTER, array('is_unread' => 0), array('uid' => $uid, 'user_id' => $userId));

            $userConfigData = $this->getEmailConfigData($userId);

            if ($this->checkImapConnection($userConfigData[0])) {
                $this->imap->setUnseenMessage($uid);
            }
        }
        return true;
    }
    /*
      @Author : Ritesh Rana
      @Desc   : get Conneted folder list
      @Input  : array parameters : mailbox, username, password, encryption
      @Output : return folder lists
      @Date   : 17/11/2018
     * 
     */

    public function getAccounFolderList($emailAccountDetails = array(), $manualReferesh = true,$ypid) {

        if (empty($emailAccountDetails)) {
            return $emailAccountDetails;
        }
        set_time_limit(0);
        $userId = $ypid;
        $unreadMessages = array();

        /*
         * firsts checks whether data is there in the array then wont call imap count
         */

        $configArr = $this->getEmailConfigData($userId);
        if (count($configArr) > 0 && $manualReferesh == false) {
            $folders = (array) json_decode($configArr[0]['account_folder']);
            $unreadMessages = $folders;
        } else {
            if ($this->checkImapConnection($emailAccountDetails)) {
                $folderlist = $this->imap->getFolders();
                foreach ($folderlist as $valf) {
                    $where = "ec.boxType='" . $valf . "' AND ec.is_unread = 1 AND ec.user_id='" . $userId . "' "; // unread message count
                    $fields = array("email_unq_id");
                    $countEmails = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', $fields, '', '', '', '', '', '', '', '', '', $where, '', '', '1', '', '', '');
                    if (!empty($countEmails)) {
                        $unreadMessages[$valf] = $countEmails;
                    } else {
                        $this->imap->selectFolder($valf);
                        $unreadMessages[$valf] = $this->imap->countUnreadMessages();
                    }
                }
            }
        }
        return $unreadMessages;
    }

    public function getAccounFolderListManual($emailAccountDetails = array(), $manualReferesh = true) {

        if (empty($emailAccountDetails)) {
            return $emailAccountDetails;
        }
        set_time_limit(0);
        $userId = $this->session->userdata('LOGGED_IN')['ID'];
        $unreadMessages = array();

        /*
         * firsts checks whether data is there in the array then wont call imap count
         */

        $configArr = $this->getEmailConfigData($this->userId);
        if ($this->checkImapConnection($emailAccountDetails)) {
//            $emailData['mail_quota'] = $this->getLeftMenuMailSize();
            $where = array("user_id" => $this->session->userdata('LOGGED_IN')['ID']);
            $folderlist = $this->imap->getFolders();
            foreach ($folderlist as $valf) {
                $this->imap->selectFolder($valf);
                $unreadMessages[$valf] = $this->imap->countUnreadMessages();
            }
            $emailData['account_folder'] = json_encode($unreadMessages);
            $success_update = $this->common_model->update(EMAIL_CONFIG, $emailData, $where);
        }


        return $unreadMessages;
    }

    /*
     * Function Updated by Dhara Bhalala on 14 Sep 2018
     *  for solving inline attachment issue.
      @Author : Ritesh Rana
      @Desc   : inline mail's image src changes (from Body)
      @Input  :
      @Output :
      @Date   : 17/11/2018
     * 
     */

   public function inlineBodyImageConversion($uid ,$body = '', $attachmentArray = array(), $relpath = '', $abspath = '') {
        $returnBody = '';
        if (empty($body)) {
            return $returnBody;
        }
        if (!empty($attachmentArray)) {

            preg_match_all('/src="cid:(.*)"/Uims', $body, $matches);
            if (count($matches)) {
                $search = $replace = array();
                $i = 0;
                foreach ($matches[1] as $key => $match) {
                    if($i==0){ 
                        ++$key; 
                        $i=1;                         
                    } ++$key;
                    $file_name = explode("@", $match);
                    if (isset($attachmentArray[$key]['is_attachment'])) {
                        //$uniqueFilename = $uid.'-'.$attachmentArray[$key]['name'];
                        $uniqueFilename = $uid.'-'.$file_name[0];
                        $search[] = "src=\"cid:$match\"";
                        $replace[] = "src=\"$relpath/$uniqueFilename\"";
                    }
                }

                $returnBody = str_replace($search, $replace, $body);
            }
            return $returnBody;
        } else {
            return $body;
        }
    }


    /*
      @Author : Ritesh Rana
      @Desc   : get data from email config data
      @Input 	: userId , fields
      @Output	: return config data array
      @Date   : 17/11/2018
     * 
     */

    public function getEmailConfigData($userId = '', $fields = '') {

        $configData = array();

        if (empty($userId)) {

            return $configData;
        }
        $where = "user_id = " . $userId;
        $configData = $this->common_model->get_records(EMAIL_CONFIG, $fields, '', '', '', '', '1', '', '', '', '', $where, '', '', '', '', '', '');


        return $configData;
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Check Imap connection
      @Input 	: config data
      @Output	:
      @Date   : 17/11/2018
     * 
     */

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

    function leftBarCount($yp_id) {
        $emailConfigData = $this->getEmailConfigData($yp_id);
        $unreadMessagesData = $this->getAccounFolderList($emailConfigData[0], false); // get Folder lists
        $data['leftMenuFolderCount'] = $unreadMessagesData;
        
        $fields = array("ec.is_unread");
        $join = array(EMAIL_CLIENT_ATTACHMENTS . ' as ema' => 'ema.email_id=ec.email_unq_id');
        $group_by = 'ec.uid';
        $boxtype = 'INBOX';
        $where = "ec.boxtype='" . $boxtype . "' and ec.user_id ='" . $yp_id ."'and ec.is_unread = '1'";
        $data['mail_data'] = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', $fields, $join, 'left', '', '', '', '', '', '', $group_by, $where);

        $data['inbox_count'] = $this->YPmailCount($yp_id);
  
        $data['ypid']= $yp_id;
        $this->load->view('leftbar', $data);
    }



function YPmailCount($yp_id){
      $fields = array("ec.is_unread");
        $join = array(EMAIL_CLIENT_ATTACHMENTS . ' as ema' => 'ema.email_id=ec.email_unq_id');
        $group_by = 'ec.uid';
        //$boxtype = ($this->input->post('boxtype')) ? $this->input->post('boxtype') : 'INBOX';
        $boxtype = 'INBOX';
        $where = "ec.boxtype='" . $boxtype . "' and ec.user_id ='" . $yp_id ."'and ec.is_unread = '1'";
        $data['mail_data'] = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', $fields, $join, 'left', '', '', '', '', '', '', $group_by, $where);

        $data['inbox_count'] = count($data['mail_data']);
        return $data['inbox_count'];
}



    public function getNewEmails() {
        $userId = $this->userId;
        $configEmailData = $this->getEmailConfigData($this->userId)[0];
        $mailbox = $configEmailData['email_server'] . ':' . $configEmailData['email_port'] . '/';
        $username = $configEmailData['email_id'];
        $password = $configEmailData['email_pass'];
        $encryption = $configEmailData['email_encryption'];
        $connect = $this->imap->connect($mailbox, $username, $password, $encryption);
        $this->imap->selectFolder('INBOX');

        $emails = $this->imap->pagingMails();
        die;
    }

/*
      @Author : Ritesh Rana
      @Desc   : YP Add Imap setting in db
      @Input    :
      @Output   :
      @Date   : 17/11/2018
*/

function YpMailConfig() {

        set_time_limit(0);

        //get YP information
          $fields = array("*");
          $YP_details = $this->common_model->get_records(YP_DETAILS, $fields, '', '', '');
          foreach ($YP_details as $yp_initials) {
              $yp_initial = $yp_initials['yp_initials'];
              $yp_init = substr($yp_initial,0,3);
              $yp_fromMail = $yp_init.'-'.YP_EMAIL;

            $emailData['email_id'] = ($yp_fromMail != '') ? $yp_fromMail : '';
            $emailData['email_pass'] = '';
            $emailData['email_server'] = 'outlook.office365.com';
            $emailData['email_port'] = '993';
            $emailData['email_encryption'] = 'TLS';
            $emailData['email_smtp'] = 'smtp.office365.com';
            $emailData['email_smtp_port'] = '587';
            $emailData['modified_date'] = datetimeformat();
            $emailData['user_id'] = $yp_initials['yp_id'];
            $emailData['created_date'] = datetimeformat();
            $success_insert = $this->common_model->insert(EMAIL_CONFIG, $emailData);
          }
            $msg = $this->lang->line('mail_config_success_insert');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YpCareHome');
    }

}
