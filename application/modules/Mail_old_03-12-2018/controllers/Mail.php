<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mail extends CI_Controller {

    public $viewname;
    public $userId;

    function __construct() {
        parent::__construct();
        $this->viewname = $this->router->fetch_class();
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
      @Date   : 16/07/2017
     */

    public function index($yp_id) {
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
        $boxtype = ($this->input->post('boxtype')) ? $this->input->post('boxtype') : 'INBOX';  
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
                $data['box_types'] = $searchsort_session['box_types'];
                $data['sortby'] = $searchsort_session['sortby'];
                $sortfield = $searchsort_session['sortfield'];
                $sortby = $searchsort_session['sortby'];
            } else {
                $sortfield = 'uid';
                $sortby = 'desc';
                $data['sortfield'] = $sortfield;
                $data['sortby'] = $sortby;
                $data['box_types'] = $data['box_types'];
            }
        }

      if ($data['box_types'] == '[Gmail]/Sent Mail' || $data['box_types'] == 'Sent' || $data['box_types'] == 'Send' || $data['box_types'] == 'Sent Items') {
           $this->getLastSentMail($yp_id,$boxtype);
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

        $config['first_link']  = 'First';
        $config['base_url'] = base_url() . 'Mail/index/'.$yp_id;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uriSegment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uriSegment = $this->uri->segment(4);
        }
        $group_by = 'ec.uid';
        $where = "ec.boxtype='" . $data['box_types'] . "' and ec.user_id =" . $yp_id;
        if ($data['box_types'] != 'Trash'):
            $where .= ' && ec.is_deleted =0';
        endif;

    $fields = array("ec.mail_subject,ec.is_flagged,ec.uid,ec.boxtype,ec.is_unread,ec.from_mail,ec.header_data,ec.to_mail,ec.send_date,ec.email_unq_id,sum(file_size) as file_size");
    $join = array(EMAIL_CLIENT_ATTACHMENTS . ' as ema' => 'ema.email_id=ec.email_unq_id');

        if (!empty($searchtext)) {

//If have any search text
            $searchtext = html_entity_decode(trim($searchtext));
            $whereSearch = '(to_mail LIKE "%' . $searchtext . '%" OR send_date LIKE "%' . $searchtext . '%" OR mail_subject LIKE "%' . $searchtext . '%" OR mail_body LIKE "%' . $searchtext . '%")';
            $data['mail_data'] = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', $fields, $join, 'left', '', '', $config['per_page'], $uriSegment, $sortfield, $sortby, $group_by, $where, '', '', '', '', '', $whereSearch);

            $config['total_rows'] = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', $fields, $join, 'left', '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1', '', '', $whereSearch);
        } else {
//Not have any search input
            $data['mail_data'] = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', $fields, $join, 'left', '', '', $config['per_page'], $uriSegment, $sortfield, $sortby, $group_by, $where, '', '', '', '', '', '');
            $config['total_rows'] = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', $fields, $join, 'left', '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1', '', '', '');
        }
        
        //get YP information
          $data['YP_details'] = getYpDetails($yp_id);

          $data['care_home_name_data'] = getCareHomeName($data['YP_details'][0]['care_home']);
          $data['yp_mail_id'] = $configArr[0]['email_id'];

        /*
         * first check argument is within db 
         * 
         */
        $data['inbox_count'] = $this->YPmailCount($yp_id);
        
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

    /*
      @Author : Ritesh Rana
      @Desc   : This function is used to compose or reply or fordward email address
      @Input 	:
      @Output	:
      @Date   : 19/07/2017
     */

    function ComposeMail($id = 0) {
        $configArr = $this->getEmailConfigData($id);
        if (empty($configArr)) {
             $msg = $this->lang->line('mailconfig_message');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$id);
            die;
        }

          //get YP information
          $YP_details =  getYpDetails($id);

          $data['YP_details'] = $YP_details;

          $data['care_home_name_data'] = getCareHomeName($YP_details[0]['care_home']);
          $data['yp_mail_id'] = $configArr[0]['email_id'];

          $data['YP_details'] = $YP_details;
          $yp_initials = $YP_details[0]['yp_initials'];

          $yp_init = substr($yp_initials,0,3);
        
          $data['email_signature'] = (isset($configArr[0]['email_signature'])) ? $configArr[0]['email_signature'] : '';
          $data['fromMail'] = (isset($configArr[0]['email_id'])) ? $configArr[0]['email_id'] : '';

          $data['fromMail_data'] = YpInitialsWithEmail($yp_init,$data['fromMail']);

        /*parent carer and social worker email list */
        $data['contacts'] = ParentCarerAndSocialWorkerEmail($id);
        /*end*/
        $data['defolt_cc'] = $this->session->userdata('LOGGED_IN')['EMAIL'];
        // get Unique Mail lists
        $data['mailtype'] = 'ComposeMail';
        $data['ypid'] = $id;
        $data['header'] = array('menu_module' => 'MyProfile');
        $data['js_content'] = '/jquery/ComposeEmailJsFiles';
        $data['main_content'] = 'ComposeEmail';
        $this->parser->parse('layouts/MailTemplate', $data);
    }

    /*
      @Author : Ritesh Rana
      @Desc   : This function is used to Download ks PDF
      @Input    :keysession id and yp id
      @Output   :
      @Date   : 05/07/2018
     */
    function ComposeMailYp($ks_id=0,$id=0) {
          ob_start();
        $pdfFilePath = FCPATH.'uploads/Mail/';
        $ks_pdf_Filename = $this->DownloadKsPdf($ks_id,$id);
        $data['ks_file_path'] = $pdfFilePath.$ks_pdf_Filename;
        ob_end_clean();

        $emailConfigData = $this->getEmailConfigData($id);
        if ($this->checkImapConnection($emailConfigData[0]) == false) {
            $msg = $this->lang->line('mailconfig_message');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$id);
            die;
        }

          //get YP information
          $YP_details = getYpDetails($id);
          $data['YP_details'] = $YP_details;

          $data['care_home_name_data'] = getCareHomeName($YP_details[0]['care_home']);
          $data['yp_mail_id'] = $emailConfigData[0]['email_id']; 

          $yp_initials = $YP_details[0]['yp_initials'];
          $yp_init = substr($yp_initials,0,3);

        $data['defolt_cc'] = $this->session->userdata('LOGGED_IN')['EMAIL'];

        $data['email_signature'] = (isset($emailConfigData[0]['email_signature'])) ? $emailConfigData[0]['email_signature'] : '';
        $data['fromMail'] = (isset($emailConfigData[0]['email_id'])) ? $emailConfigData[0]['email_id'] : '';

        $data['fromMail_data'] = YpInitialsWithEmail($yp_init,$data['fromMail']);

        $searchContactFields = array('contact_id', 'contact_name', 'email'); 

        /*parent carer and social worker email list */
        $data['contacts'] = ParentCarerAndSocialWorkerEmail($id);
        /*end*/
        
        $data['ks_pdf_file'] = $pdfFilePath.$ks_pdf_Filename; 
        $data['mailtype'] = 'ComposeMail';
         $data['ypid'] = $id;
        $data['header'] = array('menu_module' => 'MyProfile');
        $data['js_content'] = '/jquery/ComposeEmailJsFiles';
        $data['main_content'] = 'KsComposeEmail';
        $this->parser->parse('layouts/MailTemplate', $data);
    } 
/*
      @Author : Ritesh Rana
      @Desc   : This function is used to Download ks PDF
      @Input    :keysession id and yp id
      @Output   :
      @Date   : 04/07/2018
     */

    public function DownloadKsPdf($ks_id,$yp_id) {
       //get ks form
       $match = array('ks_form_id'=> 1);
       $ks_forms = $this->common_model->get_records(KS_FORM,'', '', '', $match);
       if(!empty($ks_forms))
       {
            $ks_data['form_data'] = json_decode($ks_forms[0]['form_json_data'], TRUE);
       }
            // get ks comments data
            $table = KS_COMMENTS . ' as com';
            $where = array("com.ks_id" => $ks_id, "com.yp_id" => $yp_id);
            $fields = array("com.*,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
        $ks_data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
            
        //get YP information
        $ks_data['YP_details'] = getYpDetails($yp_id);

        //get ks yp data
        $match = array('ks_id'=> $ks_id);
        $ks_data['edit_data'] = $this->common_model->get_records(KEY_SESSION,array("*"), '', '', $match);
        
        $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
        $table = KEYSESSION_SIGNOFF.' as ks';
        $where = array("l.is_delete"=> "0","ks.yp_id" => $yp_id,"ks.ks_id"=>$ks_id,"ks.is_delete"=> "0");
        $fields = array("ks.created_by,ks.created_date,ks.yp_id,ks.ks_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id=ks.created_by');
        $group_by = array('created_by');
        $ks_data['signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);


        //check data exist or not
        if(empty($ks_data['YP_details']) || empty($ks_data['edit_data']))
        {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson/view/'.$yp_id);
        }
        $ks_data['ypid'] = $yp_id;
        $ks_data['ks_id'] = $ks_id;
        $ks_data['footerJs'][0] = base_url('uploads/custom/js/keysession/keysession.js');
        $ks_data['crnt_view'] = $this->viewname;
        
            $PDFInformation['yp_details'] = $ks_data['YP_details'][0];
            $PDFInformation['edit_data'] = $ks_data['edit_data'][0]['modified_date'];

            $PDFHeaderHTML  = $this->load->view('Keysession/ks_pdfHeader', $PDFInformation,true);
            
            $PDFFooterHTML  = $this->load->view('Keysession/ks_pdfFooter', $PDFInformation,true);
            
            //Set Header Footer and Content For PDF
            $this->m_pdf->pdf->mPDF('utf-8','A4','','','10','10','45','25');
    
            $this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
            $this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);                    
            $ks_data['main_content'] = 'Keysession/ks';
            $html = $this->parser->parse('layouts/PdfDataTemplate', $ks_data,TRUE);
            $pdfFileName = time() . uniqid() .".pdf";
            $pdfFilePath = FCPATH.'uploads/Mail/';

            /*remove*/
            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($pdfFilePath.$pdfFileName, 'F');
            $ks_pdf_file = $pdfFileName; 
            return $ks_pdf_file;exit;
    }



    /*
      @Author : Ritesh Rana
      @Desc   : This function is used to Download YPC PDF
      @Input    : YPC id and yp id
      @Output   : send email with pdf
      @Date   : 01/08/2018
     */
    function ComposeMailYPC($ypc_id=0,$id=0) {
          ob_start();
        $pdfFilePath = FCPATH.'uploads/Mail/';
        $ypc_pdf_Filename = $this->DownloadYPCPdf($ypc_id,$id);
        $data['ypc_file_path'] = $pdfFilePath.$ypc_pdf_Filename;
        ob_end_clean();
         $emailConfigData = $this->getEmailConfigData($id);
        if ($this->checkImapConnection($emailConfigData[0]) == false) {
            $msg = $this->lang->line('mailconfig_message');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$id);
            die;
        }
          //get YP information
          $YP_details = getYpDetails($id);

          $data['YP_details'] = $YP_details;

          $data['care_home_name_data'] = getCareHomeName($YP_details[0]['care_home']);
          $data['yp_mail_id'] = $emailConfigData[0]['email_id']; 

          $yp_initials = $YP_details[0]['yp_initials'];
          $yp_init = substr($yp_initials,0,3);
          $data['defolt_cc'] = $this->session->userdata('LOGGED_IN')['EMAIL'];
        
        $userId = $id;
        $data['email_signature'] = (isset($emailConfigData[0]['email_signature'])) ? $emailConfigData[0]['email_signature'] : '';
        $data['fromMail'] = (isset($emailConfigData[0]['email_id'])) ? $emailConfigData[0]['email_id'] : '';
        $data['fromMail_data'] = YpInitialsWithEmail($yp_init,$data['fromMail']);

        /*parent carer and social worker email list */
        $data['contacts'] = ParentCarerAndSocialWorkerEmail($id);
        /*end*/
      
        $data['ypc_pdf_file'] = $pdfFilePath.$ypc_pdf_Filename; 
        $data['mailtype'] = 'ComposeMail';
        $data['ypid'] = $id;
        $data['ypc_id'] = $ypc_id;
        $data['header'] = array('menu_module' => 'MyProfile');
        $data['js_content'] = '/jquery/ComposeEmailJsFiles';
        $data['main_content'] = 'YpcComposeEmail';
        $this->parser->parse('layouts/MailTemplate', $data);
    } 
/*
      @Author : Ritesh Rana
      @Desc   : This function is used to Download ks PDF
      @Input    : ypc id and yp id
      @Output   : send email with pdf
      @Date   : 04/07/2018
     */

    public function DownloadYPCPdf($ypc_id,$yp_id) {
       //get YPC form
            $match = array('ypc_form_id' => 1);
            $ypc_forms = $this->common_model->get_records(YPC_FORM, '', '', '', $match);
            if (!empty($ypc_forms)) {
                $ks_data['form_data'] = json_decode($ypc_forms[0]['form_json_data'], TRUE);
            }

        // get YPC comments data
            $table = YPC_COMMENTS . ' as com';
            $where = array("com.ypc_id" => $ypc_id, "com.yp_id" => $yp_id);
            $fields = array("com.*,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
            $ks_data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);

            //get YP information
            $ks_data['YP_details'] = getYpDetails($yp_id);

            //get ypc yp data
            $match = array('ypc_id' => $ypc_id);
            $ks_data['edit_data'] = $this->common_model->get_records(YP_CONCERNS, array("*"), '', '', $match);

            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $table = CONCERNS_SIGNOFF . ' as cs';
            $where = array("l.is_delete" => "0", "cs.yp_id" => $yp_id, "cs.ypc_id" => $ypc_id, "cs.is_delete" => "0");
            $fields = array("cs.created_by,cs.created_date,cs.yp_id,cs.ypc_id, CONCAT(`firstname`,' ', `lastname`) as name");

            $join_tables = array(LOGIN . ' as l' => 'l.login_id=cs.created_by');
            $group_by = array('created_by');
            $ks_data['signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);

            //check data exist or not
            if (empty($ks_data['YP_details']) || empty($ks_data['edit_data'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/'.$yp_id);
            }

        $ks_data['ypid'] = $yp_id;
        $ks_data['ypc_id'] = $ypc_id;
        $ks_data['footerJs'][0] = base_url('uploads/custom/js/concerns/concerns.js');
        $ks_data['crnt_view'] = $this->viewname;
        
            $PDFInformation['yp_details'] = $ks_data['YP_details'][0];
            $PDFInformation['edit_data'] = $ks_data['edit_data'][0]['modified_date'];

            $PDFHeaderHTML  = $this->load->view('Concerns/concerns_pdfHeader', $PDFInformation,true);
            
            $PDFFooterHTML  = $this->load->view('Concerns/concerns_pdfFooter', $PDFInformation,true);

            //Set Header Footer and Content For PDF
            $this->m_pdf->pdf->mPDF('utf-8','A4','','','10','10','45','25');
    
            $this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
            $this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);                    
            $ks_data['main_content'] = 'Concerns/concerns';
            $html = $this->parser->parse('layouts/PdfDataTemplate', $ks_data,TRUE);
            $pdfFileName = time() . uniqid() .".pdf";
            $pdfFilePath = FCPATH.'uploads/Mail/';

            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($pdfFilePath.$pdfFileName, 'F');
            $ks_pdf_file = $pdfFileName; 
            return $ks_pdf_file;exit;
    }

    /*
      @Author : Ritesh Rana
      @Desc   : This function is used to Download RMP PDF
      @Input    : Rmp id and yp id
      @Output   : send email with pdf
      @Date   : 02/08/2018
     */
    function ComposeMailRMP($rmp_id=0,$id=0) {
          ob_start();
        $pdfFilePath = FCPATH.'uploads/Mail/';
        $ypc_pdf_Filename = $this->DownloadRMPPdf($rmp_id,$id);
        $data['rmp_file_path'] = $pdfFilePath.$ypc_pdf_Filename;
        ob_end_clean();
        $emailConfigData = $this->getEmailConfigData($id);
        if ($this->checkImapConnection($emailConfigData[0]) == false) {
            $msg = $this->lang->line('mailconfig_message');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$id);
            die;
        }

        //get YP information
          $YP_details = getYpDetails($id);
          $data['YP_details'] = $YP_details;

          $data['care_home_name_data'] = getCareHomeName($YP_details[0]['care_home']);
          $data['yp_mail_id'] = $emailConfigData[0]['email_id']; 

          $yp_initials = $YP_details[0]['yp_initials'];
          $yp_init = substr($yp_initials,0,3);

        $data['defolt_cc'] = $this->session->userdata('LOGGED_IN')['EMAIL'];
        $userId = $id;
        $data['email_signature'] = (isset($emailConfigData[0]['email_signature'])) ? $emailConfigData[0]['email_signature'] : '';
        $data['fromMail'] = (isset($emailConfigData[0]['email_id'])) ? $emailConfigData[0]['email_id'] : '';

        $data['fromMail_data'] = YpInitialsWithEmail($yp_init,$data['fromMail']);
        
        /*parent carer and social worker email list */
        $data['contacts'] = ParentCarerAndSocialWorkerEmail($id);
        /*end*/
      
        $data['ypc_pdf_file'] = $pdfFilePath.$ypc_pdf_Filename; 
        $data['mailtype'] = 'ComposeMail';
        $data['ypid'] = $id;
        $data['rmp_id'] = $rmp_id; 
        $data['header'] = array('menu_module' => 'MyProfile');
        $data['js_content'] = '/jquery/ComposeEmailJsFiles';
        $data['main_content'] = 'RmpComposeEmail';
        $this->parser->parse('layouts/MailTemplate', $data);
    } 
/*
      @Author : Ritesh Rana
      @Desc   : This function is used to Download RMP PDF
      @Input    : Rmp id and yp id
      @Output   : send email with pdf
      @Date   : 02/08/2018
     */

    public function DownloadRMPPdf($rmp_id,$yp_id) {
       //get YPC form
        $data = [];
        $match = array('rmp_form_id'=> 1);
        $ks_forms = $this->common_model->get_records(RMP_FORM,'', '', '', $match);
        if(!empty($ks_forms))
        {
            $data['form_data'] = json_decode($ks_forms[0]['form_json_data'], TRUE);
        }


    // get rmp comments data
      $table = RMP_COMMENTS . ' as com';
      $where = array("com.rmp_id" => $rmp_id, "com.yp_id" => $yp_id);
      $fields = array("com.*,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
      $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
      $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);

            //get YP information
            $data['YP_details'] = getYpDetails($yp_id);
            
            //get rmp yp data
            $match = array('yp_id'=> $yp_id,'rmp_id'=> $rmp_id);
            $join_tables = array(LOGIN . ' as l' => 'l.login_id=rmp.created_by');
            $data['edit_data'] = $this->common_model->get_records(RMP.' as rmp',array("rmp.*, CONCAT(`firstname`,' ', `lastname`) as name"), $join_tables, 'left', $match);

      //get rmp signoff data
        $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
        $table = RMP_SIGNOFF.' as ks';
        $where = array("l.is_delete"=> "0","ks.yp_id" => $yp_id,"ks.rmp_id"=>$rmp_id,"ks.is_delete"=> "0");
        $fields = array("ks.created_by,ks.created_date,ks.yp_id,ks.rmp_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id=ks.created_by');
        $group_by = array('created_by');
        $data['ks_signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);
        
            //check data exist or not
            if (empty($data['YP_details']) || empty($data['edit_data'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/'.$yp_id);
            }

        $data['ypid'] = $yp_id;
        $data['rmp_id'] = $rmp_id;
        $data['footerJs'][0] = base_url('uploads/custom/js/RiskManagementPlan/RiskManagementPlan.js');
        $data['crnt_view'] = $this->viewname;

        //Download RMP pdf 
            $PDFInformation['yp_details'] = $data['YP_details'][0];
            $PDFInformation['edit_data'] = $data['edit_data'][0]['modified_date'];

            $PDFHeaderHTML  = $this->load->view('RiskManagementPlan/rmppdfHeader', $PDFInformation,true);
            
            $PDFFooterHTML  = $this->load->view('RiskManagementPlan/rmppdfFooter', $PDFInformation,true);
            
            //Set Header Footer and Content For PDF
            $this->m_pdf->pdf->mPDF('utf-8','A4','','','10','10','45','25');
    
            $this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
            $this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);                    
            $data['main_content'] = 'RiskManagementPlan/rmppdf';
            $html = $this->parser->parse('layouts/PdfDataTemplate', $data,TRUE);
            $pdfFileName = time() . uniqid() .".pdf";
            $pdfFilePath = FCPATH.'uploads/Mail/';

            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($pdfFilePath.$pdfFileName, 'F');
            $ks_pdf_file = $pdfFileName; 
            return $ks_pdf_file;exit;
    }




 /*
      @Author : Ritesh Rana
      @Desc   : This function is used to Download RMP PDF
      @Input    : do id and yp id
      @Output   : send email with pdf
      @Date   : 03/08/2018
     */
    function ComposeMailDO($do_id=0,$yp_id=0) {
          ob_start();
        $pdfFilePath = FCPATH.'uploads/Mail/';
        $do_pdf_Filename = $this->DownloadDOPdf($do_id,$yp_id);
        $data['do_file_path'] = $pdfFilePath.$do_pdf_Filename;
        ob_end_clean();

        $emailConfigData = $this->getEmailConfigData($yp_id);
         if ($this->checkImapConnection($emailConfigData[0]) == false) {
            $msg = $this->lang->line('mailconfig_message');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$yp_id);
            die;
        }

        //get YP information
          $YP_details = getYpDetails($yp_id);

          $data['YP_details'] = $YP_details;

          $data['care_home_name_data'] = getCareHomeName($YP_details[0]['care_home']);
          $data['yp_mail_id'] = $emailConfigData[0]['email_id'];          

          $yp_initials = $YP_details[0]['yp_initials'];
          $yp_init = substr($yp_initials,0,3);

          $data['defolt_cc'] = $this->session->userdata('LOGGED_IN')['EMAIL'];
        $userId = $yp_id;
       
        $data['email_signature'] = (isset($emailConfigData[0]['email_signature'])) ? $emailConfigData[0]['email_signature'] : '';
        $data['fromMail'] = (isset($emailConfigData[0]['email_id'])) ? $emailConfigData[0]['email_id'] : '';
         $data['fromMail_data'] = YpInitialsWithEmail($yp_init,$data['fromMail']);
        
        /*parent carer and social worker email list */
        $data['contacts'] = ParentCarerAndSocialWorkerEmail($yp_id);
        /*end*/
      
        $data['ypc_pdf_file'] = $pdfFilePath.$ypc_pdf_Filename; 
        $data['mailtype'] = 'ComposeMail';
        $data['ypid'] = $yp_id;
        $data['header'] = array('menu_module' => 'MyProfile');
        $data['js_content'] = '/jquery/ComposeEmailJsFiles';
        $data['main_content'] = 'DoComposeEmail';
        $this->parser->parse('layouts/MailTemplate', $data);
    } 



/*
      @Author : Ritesh rana
      @Desc   : Daily Observation PDF data
      @Input  : DO id and ypid
      @Output : send email with pdf
      @Date   : 03/08/2018
     */
     
     public function DownloadDOPdf($id, $ypid) {
        if(is_numeric($id) && is_numeric($ypid)){
        
        //get YP information
            $data['YP_details'] = getYpDetails($ypid);

            $table = OB_COMMENTS . ' as com';
            $where = array("com.doid" => $id, "com.yp_id" => $ypid);
            $fields = array("com.*,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");

            $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
            $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);


            //get daily observation data
            $table = DAILY_OBSERVATIONS . ' as do';
            $where = array("do.do_id" => $id, "do.yp_id" => $ypid);
            $fields = array("do.*,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= do.yp_id');
            $data['dodata'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);

            //get last day do
            $table = DAILY_OBSERVATIONS . ' as do';
            $where = array("do.daily_observation_date" => "'".date('Y-m-d',(strtotime ( '-1 day' , strtotime ($data['dodata'][0]['daily_observation_date']) )))."'", "do.yp_id" => $ypid);
            $fields = array("do.do_id,do.handover_next_day");
            $data['lastDayData'] = $this->common_model->get_records($table, $fields,'', '', '', '', '', '', '', '', '', $where);
            
            $table = MEDICAL_PROFESSIONALS_APPOINTMENT . ' as dpa';
            $where = array( "dpa.yp_id" => $ypid, 
                            'appointment_date' => "'".$data['dodata'][0]['daily_observation_date']."'",
                            'is_delete' => 0
                            );
            $fields = array("dpa.*");
            $data['do_professionals_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            
            //get planner appointment
            $table = PLANNER . ' as dpa';
            $where = array( "dpa.yp_id" => $ypid, 
                            'appointment_date' => "'".$data['dodata'][0]['daily_observation_date']."'",
                            'is_delete' => 0
                            );
            $fields = array("dpa.*");
            $data['do_planner_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            //get adminstry medication
            $table = ADMINISTER_MEDICATION . ' as mc';
            $fields = array("mc.*,md.stock");
            $where = array("mc.yp_id"=>$ypid ,'mc.date_given' => "'".date('d/m/Y',strtotime($data['dodata'][0]['daily_observation_date']))."'");
            $join_tables = array(MEDICATION . ' as md' => 'md.medication_id=mc.select_medication');
            $data['administer_medication'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '','', 'administer_medication_id','desc', '', $where);
            //get am form
            $match = array('am_form_id'=> 1);
            $formsdata = $this->common_model->get_records(AM_FORM,'', '', '', $match);
            if(!empty($formsdata))
            {
                $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }
            $match = array('yp_id'=> $ypid);
            $medication_data = $this->common_model->get_records(MEDICATION, array('*') , '', '', $match);
             
            $table = DO_PREVIOUS_VERSION . ' as do';
            $where = array("do.do_id" => $id, "do.yp_id" => $ypid);
            $fields = array("do.*");
            $data['oldformsdata'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            if (empty($data['dodata'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('/' . $this->viewname . '/index/' . $ypid);
            }

            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $table = DO_SIGNOFF . ' as do';
            $where = array("l.is_delete" => "0", "do.yp_id" => $ypid, "do_id" => $id,"do.is_delete"=> "0");
            $fields = array("do.created_by,do.created_date,do.yp_id,do.do_id, CONCAT(`firstname`,' ', `lastname`) as name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id=do.created_by');
            $group_by = array('created_by');
            $data['do_signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);

            $table = DO_SIGNOFF.' as do';
            $where = array("do.yp_id" => $ypid,"do_id" => $id,"do.created_by" => $login_user_id,"do.is_delete"=> "0");
            $fields = array("do.*");
            $data['check_do_signoff_data'] = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);


            //get staff details
            $table = DO_STAFF_TRANSECTION . ' as do';
            $where = array("do.do_id" => $id);
            $fields = array("do.*,CONCAT(l.firstname,' ', l.lastname) as staff_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.user_id');

            $data['do_staff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
            //get food form
            $match = array('food_form_id' => 1);
            $food_forms = $this->common_model->get_records(FOOD_FORM, '', '', '', $match);
            if (!empty($food_forms)) {
                $data['food_form_data'] = json_decode($food_forms[0]['form_json_data'], TRUE);
            }

            //get food data
            $match = array('do_id' => $id, 'is_previous_version' => 0);
            $data['food_data'] = $this->common_model->get_records(DO_FOODCONSUMED, '', '', '', $match);

            //get food data
            $match = array('do_id' => $id, 'is_previous_version' => 1);
            $data['food_previous_data'] = $this->common_model->get_records(DO_FOODCONSUMED, '', '', '', $match);

         //get external approve
            $table = NFC_DO_SIGNOFF_DETAILS;
            $fields = array('*');
            $where = array('do_id' => $id, 'yp_id' => $ypid);
            $data['check_external_signoff_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

            //get SUMMARIES form
            $match = array('do_form_id' => 1);
            $food_forms = $this->common_model->get_records(DO_FORM, '', '', '', $match);
            if (!empty($food_forms)) {
                $data['summary_form_data'] = json_decode($food_forms[0]['form_json_data'], TRUE);
            }

                //check data exist or not
            if (empty($data['YP_details']) || empty($data['dodata'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/'.$yp_id);
            }

        $data['ypid'] = $yp_id;
        $data['do_id'] = $do_id;
        $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
        $data['crnt_view'] = $this->viewname;

        //Download RMP pdf 
            $PDFInformation['yp_details'] = $data['YP_details'][0];
            $PDFInformation['edit_data'] = $data['edit_data'][0]['modified_date'];

            $PDFHeaderHTML  = $this->load->view('DailyObservation/dailyobservationpdfHeader', $PDFInformation,true);
            
            $PDFFooterHTML  = $this->load->view('DailyObservation/dailyobservationpdfFooter', $PDFInformation,true);
            
            //Set Header Footer and Content For PDF
            $this->m_pdf->pdf->mPDF('utf-8','A4','','','10','10','45','25');
    
            $this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
            $this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);                    
            $data['main_content'] = 'DailyObservation/dosendpdf';
            $html = $this->parser->parse('layouts/PdfDataTemplate', $data,TRUE);
            
            $pdfFileName = time() . uniqid() .".pdf";
            $pdfFilePath = FCPATH.'uploads/Mail/';

            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($pdfFilePath.$pdfFileName, 'F');
            $ks_pdf_file = $pdfFileName; 
            return $ks_pdf_file;exit;
        }else{
             show_404();
        }
    }


    /*
      @Author : Ritesh Rana
      @Desc   : This function is used to fetch emails from the account
      @Input 	:
      @Output	:
      @Date   : 30/07/2018
     */

    function pagingMailsAll($offset, $nopage, $folder,$ypid){
            $this->Common_model->delete(EMAIL_CLIENT_MASTER, array('boxType' => $folder, 'user_id' => $ypid));

            $this->Common_model->delete(EMAIL_CLIENT_ATTACHMENTS, array('boxtype_file' => $folder, 'user_id' => $ypid));                         
        
        $userId = $ypid;
        $configEmailData = $this->getEmailConfigData($userId)[0];
        $mailbox = $configEmailData['email_server'] . ':' . $configEmailData['email_port'] . '/';
        $username = $configEmailData['email_id'];
        $password = base64_decode($configEmailData['email_pass']);
        $encryption = $configEmailData['email_encryption'];
        $connect = $this->imap->connect($mailbox, $username, $password, $encryption);
        $this->imap->selectFolder($folder);
        
        $emails = $this->imap->listMessages($offset, $nopage, $sort = null, $ypid);
        if (count($emails) > 0) {
          //insert new email data  
          $this->InsertEmailData($emails,$ypid,$folder);
        } else {
            
        }
    }

    

/*
      @Author : Ritesh Rana
      @Desc   : This function is used to fetch emails from the account
      @Input  :
      @Output :
      @Date   : 30/07/2018
     */
    function getEmails_data() {

        set_time_limit(0);
        $folderName = $this->input->post('folderName');
        $ypid = $this->input->post('yp_id');
        if (!empty($folderName)) {
            $where = "ec.is_deleted = 0 AND ec.boxtype='" . $folderName . "' AND ec.user_id =" . $ypid;
            $fields = array("email_unq_id");
            $countEmails = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', $fields, '', '', '', '', '', '', '', '', '', $where, '', '', '1', '', '', '');
            if($countEmails == 0){
                $count = $this->getTotalMailCount($folderName,$ypid);
                $this->pagingMailsAll(1, $count, $folderName,$ypid);                
            }else{
              $this->GetNewMailsAll($ypid,$folderName);
            }
              
        }
        echo "done";
        exit;
    }

/*
      @Author : Ritesh Rana
      @Desc   : This function is used to fetch sent emails from the account
      @Input  :
      @Output :
      @Date   : 30/07/2018
     */
    function getLastSentMail($ypid,$folderName) {

        set_time_limit(0);
        if (!empty($folderName)) {
            $where = "ec.is_deleted = 0 AND ec.boxtype='" . $folderName . "' AND ec.user_id =" . $ypid;
            $fields = array("email_unq_id");
            $countEmails = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', $fields, '', '', '', '', '', '', '', '', '', $where, '', '', '1', '', '', '');
            
            if($countEmails == 0){
                $count = $this->getTotalMailCount($folderName,$ypid);
                $this->pagingMailsAll(1, $count, $folderName,$ypid);                
            }else{
              $this->GetNewMailsAll($ypid,$folderName);
            }
              
        }
        return true;
    }


/*
      @Author : Ritesh Rana
      @Desc   : This function is used to fetch inbox/sent emails from the account
      @Input  :
      @Output :
      @Date   : 30/07/2018
     */

    function getEmailsRefresh() {
            set_time_limit(0);

            $inbox_box = $this->input->post('inbox_box');
            $sent_box = $this->input->post('sent_box');
            $yp_id = $this->input->post('yp_id');

               
            /*get inbox count */   
            $where = "ec.is_deleted = 0 AND ec.boxtype='" . $inbox_box . "' AND ec.user_id =" . $yp_id;
            $fields = array("email_unq_id");
            $countinbox = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', $fields, '', '', '', '', '', '', '', '', '', $where, '', '', '1', '', '', '');

            /*get sent item count */   
            $where = "ec.is_deleted = 0 AND ec.boxtype='" . $sent_box . "' AND ec.user_id =" . $yp_id;
            $fields = array("email_unq_id");
            $countsent = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', $fields, '', '', '', '', '', '', '', '', '', $where, '', '', '1', '', '', '');

            // get index mail
             if($countinbox == 0){
               $count = $this->getTotalMailCount($inbox_box,$yp_id);
               $this->pagingMailsAll(1, $count, $inbox_box,$yp_id);
             }else{
                $this->GetNewMailsAll($yp_id,$inbox_box);
             }
             //get sent mail 
             if($countsent == 0){
                $count = $this->getTotalMailCount($sent_box,$yp_id);
                $this->pagingMailsAll(1, $count, $sent_box,$yp_id);
              }else{
                $this->GetNewMailsAll($yp_id,$sent_box);
              }

              echo "done";
              exit;
        
    }

    /*
      @Author : Ritesh Rana
      @Desc   : This function is used to compose or reply or fordward email address
      @Input  :
      @Output :
      @Date   : 03/07/2018
     */

    function sendEmail() {
        $to = $this->input->post('to');
        $uid = $this->input->post('uid');
        $msg_no = $this->input->post('msg_no');
        $subject = $this->input->post('subject');
        $from = $from_name = $this->input->post('from');
        $cc = $this->input->post('cc');
        $bcc = $this->input->post('bcc');
        $message = $this->input->post('message', false);
        $fileToUpload = $this->input->post('fileToUpload');
        $ypid = $this->input->post('ypid');
        $userConfigData = $this->getEmailConfigData($ypid);
        
        $YP_details = getYpDetails($ypid);

        $yp_initials = $YP_details[0]['yp_initials'];

        $yp_init = substr($yp_initials,0,3);

        $fromMail_name = EmailFromName($yp_init,$from);
        
        if ($this->input->post('mailtype') && $this->input->post('mailtype') == 'forward') {
            if (count($userConfigData) > 0) {
                $server = "{" . $userConfigData[0]['email_server'] . ":" . $userConfigData[0]['email_port'] . "/ssl}INBOX";
                $username = $userConfigData[0]['email_id'];
                $password = base64_decode($userConfigData[0]['email_pass']);
                $userid = $ypid;
                $mailbox = $server;
                $folder = '';
                $encryption = 'tls'; // or ssl or ''
                $inbox = imap_open($server, $username, $password) or die('Cannot connect to Gmail: ' . imap_last_error());
                 $boxtype_data = $this->input->post('boxtype');
// open connection
                $forwardMailData = $this->Message_Parse($uid,$ypid,$boxtype_data);
                $headers = imap_fetchheader($inbox, $msg_no);
                preg_match_all('/([^: ]+): (.+?(?:\r\n\s(?:.+?))*)\r\n/m', $headers, $matches);
                $heads = $matches;
                if (count($forwardMailData) > 0) {
                    $headers.= "From: $from";
                    $files = array();
                    for ($x = 0; $x < count($forwardMailData); $x++) {
                        $files[] = $dirpth = FCPATH . "uploads/Mail/$userid/$uid/" . $uid . '-' . $forwardMailData[$x]['file_name'];
                    }
                    $folder = "uploads/Mail";
                    $path = FCPATH . $folder . '/';
                    if (count($fileToUpload) > 0) {
                        foreach ($fileToUpload as $file):
                            $files[] = $path . $file;
                        endforeach;
                    }
                    $config['protocol'] = 'smtp';
                    $config['smtp_host'] = $userConfigData[0]['email_smtp']; //change this
                    $config['smtp_port'] = $userConfigData[0]['email_smtp_port'];
                    $config['smtp_user'] = $userConfigData[0]['email_id']; //change this
                    $config['smtp_pass'] = base64_decode($userConfigData[0]['email_pass']); //change this
                    $sendmail = send_mail_imap($to, $subject, $message, $files, $from, $fromMail_name, $cc, $bcc, $headers, $config);
                    
                }
                if ($sendmail) {
                    $this->imap->saveMessageInSent($headers, $message);
                    $msg = 'Email has been sent!';
                    $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                    redirect('Mail/index/'.$ypid);
                }else{
                  $msg = 'Error While sending message';
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('Mail/index/'.$ypid);
                }
            }
        } else if ($this->input->post('mailtype') && $this->input->post('mailtype') == 'reply') {
            $headers = array();
            $body = $message;
            $headers[] = "From:" . $from . "\r\n";
            $headers[] = "Date: " . date("r") . "\r\n";
            $headers[] = "MIME-Version: 1.0\r\n";
            $headers[] = "In-Reply-To:" . $uid . "";
            $fileToUpload = $this->input->post('fileToUpload');
            $files = array();
            $folder = "/uploads/Mail/";

            $path = FCPATH . $folder . '/';
            if (count($fileToUpload) > 0) {
                foreach ($fileToUpload as $file):
                    $files[] = $path . $file;
                endforeach;
            }

            $config['protocol'] = 'smtp';
            $config['smtp_host'] = $userConfigData[0]['email_smtp']; //change this
            $config['smtp_port'] = $userConfigData[0]['email_smtp_port'];
            $config['smtp_user'] = $userConfigData[0]['email_id']; //change this
            $config['smtp_pass'] = base64_decode($userConfigData[0]['email_pass']); //change this
            $sendmail = send_mail_imap($to, $subject, $body, $files, $from, $fromMail_name, $cc, $bcc, $headers, $config);
            if ($sendmail) {
                $this->imap->saveMessageInSent($headers, $message);
                $msg = 'Email has been sent!';
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                redirect('Mail/index/'.$ypid);
            } else {
                $msg = 'Error While sending message';
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('Mail/index/'.$ypid);
            }
        } else {

            if ($this->input->post('to') != '' && $this->input->post('subject') != '' && $this->input->post('message') != '') {
                $to = $this->input->post('to');
                $subject = $this->input->post('subject');
                $from = $from_name = $this->input->post('from');
                $cc = $this->input->post('cc');
                $bcc = $this->input->post('bcc');
                $message = $_POST['message'];
                $fileToUpload = $this->input->post('fileToUpload');
                $files = array();
                $folder = "uploads/Mail";

                $path = FCPATH . $folder . '/';
                if (count($fileToUpload) > 0) {
                    foreach ($fileToUpload as $file):
                        $files[] = $path . $file;
                    endforeach;
                }

                $ks_file_path = $this->input->post('ks_file_path');
                if(!empty($ks_file_path)){
                    $ks_file_path_data = array($ks_file_path);
                    $files = array_merge($files,$ks_file_path_data);
                    }

                $fromEmail= htmlentities($fromMail_name.'<'.$from.'>');

                 $headers = array('MIME-Version'=>'1.0\r\n', 'Content-Type'=>'image/png; charset=utf-8\r\n', 'X-Priority'=> '1\r\n', 'Content-Transfer-Encoding'=> 'base64','From'=> "$fromEmail");

                $config['protocol'] = 'smtp';
                $config['smtp_host'] = $userConfigData[0]['email_smtp']; //change this
                $config['smtp_port'] = $userConfigData[0]['email_smtp_port'];
                $config['smtp_user'] = $userConfigData[0]['email_id']; //change this
                $config['smtp_pass'] = base64_decode($userConfigData[0]['email_pass']); //change this
                $config['from'] = $userConfigData[0]['email_id']; //change this
                $send = send_mail_imap($to, $subject, $message, $files, $from, $fromMail_name, $cc, $bcc, $headers, $config);

                if ($send) {

                    $this->imap->saveMessageInSent($headers, $message);

                    $msg = 'Email has been sent!';
                    $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                    redirect('Mail/index/'.$ypid);
                } else {

                    $msg = 'Error While sending message';
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                    redirect('Mail/index/'.$ypid);
                }
            }
        }
    }

/*
      @Author : Ritesh Rana
      @Desc   : uploaded inline attachment use summernote
      @Input  :
      @Output :
      @Date   : 03/07/2018
     */

    function uploadFromEditor() {

        $allowed = array('png', 'jpg', 'gif', 'zip', 'jpeg');

        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {

            $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            if (!in_array(strtolower($extension), $allowed)) {
                echo '{"status":"error"}';
                exit;
            }
            if (move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/Mail/' . time() . "_" . $_FILES['file']['name'])) {
                $tmp = 'uploads/Mail/' . time() . "_" . preg_replace('/\s+/', '', $_FILES['file']['name']);
                $path = 'uploads/Mail/' . time() . "_" . preg_replace('/\s+/', '', $_FILES['file']['name']);
                echo base_url($path);
            }
        } else {
            echo '{"status":"error"}';
            exit;
        }
    }


/*
      @Author : Ritesh Rana
      @Desc   : forward Email functionality
      @Input  :
      @Output :
      @Date   : 03/07/2018
     */
    function forwardEmail($uid,$boxtype,$ypid) {
        $configArr = $this->getEmailConfigData($ypid);
        if (empty($configArr)) {
            $msg = $this->lang->line('mailconfig_message');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$ypid);
            die;
        }
        $userId = $ypid;

        $emailConfigData = $this->getEmailConfigData($userId);
        $data['email_signature'] = (isset($emailConfigData[0]['email_signature'])) ? $emailConfigData[0]['email_signature'] : '';
        $data['fromMail'] = (isset($emailConfigData[0]['email_id'])) ? $emailConfigData[0]['email_id'] : '';

       /*parent carer and social worker email list */
        $data['contacts'] = ParentCarerAndSocialWorkerEmail($ypid);
        /*end*/

        $data['defolt_cc'] = $this->session->userdata('LOGGED_IN')['EMAIL'];  

        $data['header'] = array('menu_module' => 'crm');
        $data['mailtype'] = "forward";
        $data['uid'] = $uid;
        $unreadMessages = array();

        $data['main_content'] = 'ComposeEmail';
        $emailData = $this->Message_Parse($uid,$ypid,$boxtype);
        if (count($emailData) > 0) {
            $frmAddr = ($emailData[0]['from_mail_address']);

            $frmDate = $emailData[0]['send_date'];
            $frmSubject = $emailData[0]['mail_subject'];
            $frmTo = $emailData[0]['to_mail'];
            $frmCC = $emailData[0]['cc_email'];
            $frmEmail = $emailData[0]['from_mail'];
            $data['to'] = '';
            $space = "<br/>";
            $defaultBody = '';
            $defaultBody.='---------- Forwarded message ----------' . $space;
            $defaultBody.='From:' . $frmAddr . '[mailto:' . $frmEmail . ']' . $space;
            $defaultBody.='Date:' . $frmDate . '' . $space;
            $defaultBody.='Subject:' . $frmSubject . '' . $space;
            $defaultBody.='To:' . $frmTo . '' . $space;
            $defaultBody.='Cc:' . $frmCC . '' . $space;
            $data['subject'] = 'Fwd:' . $frmSubject;
            $body = '';
            $cidArr = [];
            $vp = [];
            $mail_files = array();
            $userid = $ypid;
            foreach ($emailData as $files) {
                $dirpth = FCPATH . "uploads/Mail/$userid/$uid/" . $uid . '-' . $files['file_name'];
                $fp = "uploads/Mail/$userid/$uid/" . $uid . '-' . $files['file_name'];
                $vp[] = base_url("uploads/Mail/$userid/$uid/" . $uid . '-' . $files['file_name']);
                $mail_files[] = array('file_name' => $uid . '-' . $files['file_name'], 'file_path' => $dirpth, 'auto_id' => $files['auto_id'], 'file_path_abs' => $fp, 'file_name_app' => $files['file_name_app']);
                if ($files['file_name_app'] != '') {
                    $cidArr[] = 'cid:' . $files['file_name_app'];
                }
            }
             $body_detail = $emailData[0]['mail_body'];
                if($this->isHTML($body_detail) == true){
                     $Body_data = $body_detail;
                }else{
                     $Body_data = base64_decode($body_detail);
                }

            $body = str_replace($cidArr, $vp, $Body_data);
            $defaultBody.=$body;
            $data['defaultBody'] = $defaultBody;
        }

        //get YP information
        $data['YP_details'] = getYpDetails($ypid);

        $data['care_home_name_data'] = getCareHomeName($data['YP_details'][0]['care_home']);
              $data['yp_mail_id'] = $emailConfigData[0]['email_id'];

        $yp_initials = $data['YP_details'][0]['yp_initials'];
        $yp_init = substr($yp_initials,0,3);
        $data['fromMail_data'] = YpInitialsWithEmail($yp_init,$data['fromMail']);
        $data['boxtype'] = $boxtype;  
        $data['ypid'] = $ypid;
        $data['mail_files'] = $mail_files;
        $data['emailData'] = $emailData;
        $data['js_content'] = '/jquery/ComposeEmailJsFiles';
        $this->parser->parse('layouts/MailTemplate', $data);
    }


    /*
      @Author : Ritesh Rana
      @Desc   : reply Email functionality
      @Input  :
      @Output :
      @Date   : 03/07/2018
     */
    function replyEmail($uid,$boxtype,$ypid) {
        $configArr = $this->getEmailConfigData($ypid);
        if (empty($configArr)) {
            $msg = $this->lang->line('mailconfig_message');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$ypid);
            die;
        }
        $table = EMAIL_CONFIG . ' as ec';
        $where = array("user_id" => $ypid);
        $userId = $ypid;
        $emailConfigData = $this->getEmailConfigData($userId);
        $data['email_signature'] = (isset($emailConfigData[0]['email_signature'])) ? $emailConfigData[0]['email_signature'] : '';
        $data['fromMail'] = (isset($emailConfigData[0]['email_id'])) ? $emailConfigData[0]['email_id'] : '';
        $searchContactFields = array('contact_id', 'contact_name', 'email');

         /*parent carer and social worker email list */
        $data['contacts'] = ParentCarerAndSocialWorkerEmail($ypid);
        /*end*/
        
        $data['header'] = array('menu_module' => 'MyProfile');

        $data['mailtype'] = "reply";
        $data['uid'] = $uid;
        $match = array("user_id"=>$ypid,"uid"=>$uid,"boxtype"=>urldecode($boxtype));
        $emailData = $this->common_model->get_records(EMAIL_CLIENT_MASTER, '', '', '', $match, '', '', '', '', '', '', '', '', '', '', '', '', '');

        $data['emailData'] = $emailData;

        $data['main_content'] = 'ComposeEmail';
        $data['to'] = count(($emailData) > 0) ? $emailData[0]['from_mail'] : '';
        $data['subject'] = 'Re:' . $emailData[0]['mail_subject'];

        $body_data = $emailData[0]['mail_body'];
        if($this->isHTML($body_data) == true){
                     $data['defaultBody'] = $body_data;
                }else{
                    $data['defaultBody'] = base64_decode($body_data);
                }

        $data['defolt_cc'] = $this->session->userdata('LOGGED_IN')['EMAIL'];        

        //get YP information
        $data['YP_details'] = getYpDetails($ypid);

        $data['care_home_name_data'] = getCareHomeName($data['YP_details'][0]['care_home']);
        $data['yp_mail_id'] = $emailConfigData[0]['email_id'];

        $yp_initials = $data['YP_details'][0]['yp_initials'];
        $yp_init = substr($yp_initials,0,3);
        $data['fromMail_data'] = YpInitialsWithEmail($yp_init,$data['fromMail']);          

        $data['ypid'] = $ypid;
        $data['js_content'] = '/jquery/ComposeEmailJsFiles';
        $this->parser->parse('layouts/MailTemplate', $data);
    }


/*
      @Author : Ritesh Rana
      @Desc   : save Attachment functionality
      @Input  :
      @Output :
      @Date   : 03/07/2018
     */
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
      @Desc   : This function is to get unreaded mail count
      @Input  :
      @Output :
      @Date   : 23/08/2018
     */

    public function getUnreadMailCount($yp_id) {
        $user_id = $yp_id;
        $userConfigData = $this->getEmailConfigData($user_id);
        if ($userConfigData) {
            if ($this->checkImapConnection($userConfigData[0])) {
                $this->imap->selectFolder('INBOX');
                return $this->imap->countUnreadMessages();
            } else {
                return 0;
            }
        }
    }


     /*
      @Author : Ritesh Rana
      @Desc   : This function is to get total mail count
      @Input  : folder name and yp id
      @Output :
      @Date   : 23/08/2018
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

    /*
      @Author : Ritesh Rana
      @Desc   : dragDropImgSave function
      @Input 	:
      @Output	:
      @Date   : 23/07/2018
     */

    public function dragDropImgSave($fileext = '') {
        //create dir and give permission
        if (!is_dir($this->config->item('mail_base_url'))) {
            mkdir($this->config->item('mail_base_url'), 0777, TRUE);
        }
        $str = file_get_contents('php://input');
        echo $filename = $fileext;
        file_put_contents(FCPATH . 'uploads/Mail/' . $filename, $str);
    }

/*
      @Author : Ritesh Rana
      @Desc   : get email message body
      @Input  : uid,ypid,boxtype
      @Output :
      @Date   : 24/07/2018
     */

    function Message_Parse($id,$ypid,$boxType) {
        $userid = $ypid;
        $boxType_data = urldecode($boxType);

        $join = array(EMAIL_CLIENT_ATTACHMENTS . ' as ema' => 'ema.email_id=ec.email_unq_id');
        $where = "uid=$id and ec.user_id=$userid and ec.boxtype='$boxType_data'";
        $result = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', '', $join, 'left', '', '', '', '', '', '', '', $where, '', '', '', '', '', '');
        return $result;
    }

    /*
      @Author : Ritesh Rana
      @Desc   : reomve / move to trash folder function
      @Input 	:
      @Output	:
      @Date   : 24/07/2018
     */

    public function movetoTrash($ypid) {
        error_reporting(0);
        $checkedList = $this->input->post('ids');
        $explodeList = explode(",", $checkedList);
        if (empty($checkedList)) {
            echo json_encode(array('status' => 0));
            exit;
        }

        $userId = $ypid;
        $userConfigData = $this->getEmailConfigData($userId);

        if ($userConfigData) {

            if ($this->checkImapConnection($userConfigData[0])) {

                foreach ($explodeList as $listId) {
                    $dataArray = array('is_deleted' => 1);
                    $this->Common_model->update(EMAIL_CLIENT_MASTER, $dataArray, array('uid' => $listId,'user_id'=>$ypid));
                }
                  
                $this->imap->deleteMessages($explodeList); // move gmail trash folder
                echo json_encode(array('status' => 1));
                exit;
            }
        }
    }

   
    /* public function test() {

      $this->load->library('imap');
      $server = "{imap.gmail.com:993/ssl}INBOX";
      $username = "cmswtest101@gmail.com";
      $password = "inf0city";
      $mailbox = $server;
      //$folder = '[Gmail]/Important';
      $encryption = 'tls'; // or ssl or ''

      $imap = $this->imap->connect('imap.gmail.com:993/', $username, $password, $encryption);

      $test = $this->imap->getMailboxStatistics();
      echo "<pre>";
      print_r($test);

      $sent = $this->imap->getSent();
      echo "<pre>";
      print_r($sent);
      exit;
      } */

    /*
      @Author : Ritesh Rana
      @Desc   : viewThread page
      @Input 	:
      @Output	:
      @Date   : 25-08-2018
     */

    function viewThread($uid,$ypid,$boxType) { 
            $table = EMAIL_CONFIG . ' as ec';
            $userId = $ypid;
            $emailData_data = $this->getEmailConfigData($ypid)[0];

          //get YP information
          $data['YP_details'] = getYpDetails($ypid);

          $data['care_home_name_data'] = getCareHomeName($data['YP_details'][0]['care_home']);
          $data['yp_mail_id'] = $emailData_data['email_id'];


            $data['email_signature'] = (isset($emailData_data['email_signature'])) ? $emailData_data['email_signature'] : '';
            $data['fromMail'] = (isset($emailData_data['email_id'])) ? $emailData_data['email_id'] : '';
          
            $data['header'] = array('menu_module' => 'crm');
            $data['mailtype'] = "forward";
            $data['uid'] = $uid;
            $data['main_content'] = 'ComposeEmail';
            $emailData = $this->Message_Parse($uid,$ypid,$boxType);

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

            $data['fromMail_data'] = $fromMail;
            $data['emailData'] = $emailData;
            $data['mail_files'] = $mail_files;
            $data['emailData'] = $emailData;
            $data['ypid'] = $ypid;
            $data['main_content'] = '/ViewMail';
            $this->parser->parse('layouts/MailTemplate', $data);
    }


    /*
      @Author : Ritesh Rana
      @Desc   : check html body
      @Input  : body part
      @Output :
      @Date   : 26-07-2018
     */
function isHTML($text){
   $processed = htmlentities($text);
   if($processed == $text) return false;
   return true; 
}

    /*
      @Author : Ritesh Rana
      @Desc   : Reply All
      @Input 	: uid ,boxtype,ypid
      @Output	:
      @Date   : 26-07-2018
     */

    function replyEmailAll($uid,$boxtype,$ypid) { 
            $table = EMAIL_CONFIG . ' as ec';

            $userId = $ypid;
            $emailConfigData = $this->getEmailConfigData($userId, array('email_signature', 'email_id'));

            $data['email_signature'] = (isset($emailConfigData[0]['email_signature'])) ? $emailConfigData[0]['email_signature'] : '';
            $data['fromMail'] = (isset($emailConfigData[0]['email_id'])) ? $emailConfigData[0]['email_id'] : '';

             /*parent carer and social worker email list */
              $data['contacts'] = ParentCarerAndSocialWorkerEmail($ypid);
             /*end*/

            $data['header'] = array('menu_module' => 'MyProfile');
            $data['mailtype'] = "replyall";
            $data['uid'] = $uid;
            
            $data['main_content'] = 'ComposeEmail';
            $emailData = $this->Message_Parse($uid,$ypid,$boxtype);
            $data_to_id = explode(",", $emailData[0]['to_mail']);

                if (in_array($data['fromMail'],array_map("strtolower", $data_to_id))) {
                    $key_data = array_search($data['fromMail'], array_map("strtolower", $data_to_id));
                    unset($data_to_id[$key_data]);
                    $data_to_data = implode(";",$data_to_id); 
                }else{
                    $data_to_data = str_replace(",",";",$emailData[0]['to_mail']);
                }   

            $body_detail = $emailData[0]['mail_body'];
                if($this->isHTML($body_detail) == true){
                     $Body_data = $body_detail;
                }else{
                     $Body_data = base64_decode($body_detail);
                }

            if (count($emailData) > 0) {
                $frmAddr = ($emailData[0]['from_mail_address']);

                $frmDate = $emailData[0]['send_date'];
                $frmSubject = $emailData[0]['mail_subject'];
                $frmTo = $emailData[0]['to_mail'];
                $frmCC = $emailData[0]['cc_email'];
                $frmEmail = $emailData[0]['from_mail'];
                if(!empty($data_to_data)){
                    $data['to'] = $frmEmail.';'.$data_to_data;    
                }else{
                    $data['to'] = $frmEmail;
                }
                $space = "<br/>";
                $defaultBody = '';
                $defaultBody.='---------- Forwarded message ----------' . $space;
                $defaultBody.='From:' . $frmAddr . '[mailto:' . $frmEmail . ']' . $space;
                $defaultBody.='Date:' . $frmDate . '' . $space;
                $defaultBody.='Subject:' . $frmSubject . '' . $space;
                $defaultBody.='To:' . $frmTo . '' . $space;
                $defaultBody.='Cc:' . $frmCC . '' . $space;
                $defaultBody.=($Body_data) . $space;
                $data['subject'] = 'RE:' . $frmSubject;
                //$data['defaultBody'] = htmlentities($defaultBody);
                $data['defaultBody'] = $defaultBody;
                $mail_files = array();
                $userid = $ypid;
                foreach ($emailData as $files) {
                    $dirpth = FCPATH . "uploads/Mail/$userid/$uid/" . $uid . '-' . $files['file_name'];
                    $fp = base_url() . "uploads/Mail/$userid/$uid/" . $uid . '-' . $files['file_name'];
                    $vp[] = base_url("uploads/Mail/$userid/$uid/" . $uid . '-' . $files['file_name']);
                    $mail_files[] = array('file_name' => $uid . '-' . $files['file_name'], 'file_path' => $dirpth, 'auto_id' => $files['auto_id'], 'file_path_abs' => $fp, 'file_name_app' => $files['file_name_app']);
                    if ($files['file_name_app'] != '') {
                        $cidArr[] = 'cid:' . $files['file_name_app'];
                    }
                }
            }

          $data['defolt_cc'] = $this->session->userdata('LOGGED_IN')['EMAIL'];
          $cc_mail = explode(',', strtolower($emailData[0]['cc_email']));
            
          if (in_array(strtolower($this->session->userdata('LOGGED_IN')['EMAIL']), $cc_mail))
            {
            $data['defolt_cc'] = '';
            }
          else
            {
              $data['defolt_cc'] = $this->session->userdata('LOGGED_IN')['EMAIL'];
            }

                    //get YP information
                $data['YP_details'] = getYpDetails($ypid);

                $data['care_home_name_data'] = getCareHomeName($data['YP_details'][0]['care_home']);
              $data['yp_mail_id'] = $emailConfigData[0]['email_id'];

        $yp_initials = $data['YP_details'][0]['yp_initials'];
        $yp_init = substr($yp_initials,0,3);
        $data['fromMail_data'] = YpInitialsWithEmail($yp_init,$data['fromMail']);


            $data['ypid'] = $ypid;
            $data['mail_files'] = $mail_files;
            $data['emailData'] = $emailData;
            $data['js_content'] = '/jquery/ComposeEmailJsFiles';
            $this->parser->parse('layouts/MailTemplate', $data);
        
    }

    /*
      @Author : Ritesh Rana
      @Desc   : download attachment function
      @Input 	:
      @Output	:
      @Date   : 26/08/2018
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
      @Desc   : mail unread and read functionality
      @Input  : uid and ypid
      @Output :
      @Date   : 26/08/2018
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
      @Desc   : Search Contacts
      @Input 	:
      @Output	:
      @Date   : 25/08/2018
     */

    public function searchContacts() {

        $this->load->library('session');
        $currentUserId = ($this->input->post('ypid')) ? $this->input->post('ypid') : '';
        $searchValue = ($this->input->post('searchValue')) ? $this->input->post('searchValue') : '';

        //get parents details
        $group_by = 'email';
        $is_delete = 0;
        $where = "is_deleted='" . $is_delete . "' and yp_id =" . $currentUserId;
        $whereSearch = '';
        if (!empty($searchValue)) {
            $whereSearch = '(relationship LIKE "%' . $searchValue . '%" OR email_address LIKE "%' . $searchValue . '%" )';
        }
        $searchFields = array('relationship', 'email_address as email');
        $contacts = $this->common_model->get_records(PARENT_CARER_INFORMATION, $searchFields, '', '', $where, '', '', '', '', '', $group_by, $whereSearch, '', '', '', '', '', '');

        $whereSearch = '';
        if (!empty($searchValue)) {
            $whereSearch = '(email LIKE "%' . $searchValue . '%" )';
        }

        // get Social worker details
        $match = "yp_id = " . $currentUserId;
        $searchFields = array('email');
        $socialRecord = $this->common_model->get_records(SOCIAL_WORKER_DETAILS, $searchFields, '', '', $match, '', '', '', '', '', $group_by, $whereSearch, '', '', '', '', '', '');


        $social_data = array();
        if(!empty($socialRecord)){
          foreach ($socialRecord as $value) {
            $a= array();
                $a['email'] = $value['email'];   
                $a['relationship'] = 'Social Worker'; 
                array_push($social_data,$a);  
          } 
        }
        $data['contacts'] = array_merge($contacts, $social_data);
        return $this->load->view('searchContactsList', $data);
    }

    /*
     * Function Updated by Dhara Bhalala on 14 Sep 2018
     *  for solving inline attachment issue.
      @Author : Ritesh Rana
      @Desc   : inline mail's image src changes (from Body)
      @Input  :
      @Output :
      @Date   : 27/07/2018
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
      @Date   : 27/08/2018
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
      @Date   : 27/07/2018
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


/*
      @Author : Ritesh Rana
      @Desc   : refresh left menu 
      @Input  : 
      @Output :
      @Date   : 27/07/2018
     * 
     */
    function leftBarCount($yp_id) {
        
        $data['inbox_count'] = $this->YPmailCount($yp_id);
        $data['ypid']= $yp_id;
        $this->load->view('leftbar', $data);
    }

/*
      @Author : Ritesh Rana
      @Desc   : Count email
      @Input  : ypid
      @Output :
      @Date   : 27/07/2018
     * 
     */

function YPmailCount($yp_id){
      $fields = array("ec.is_unread");
        $join = array(EMAIL_CLIENT_ATTACHMENTS . ' as ema' => 'ema.email_id=ec.email_unq_id');
        $group_by = 'ec.uid';
        $boxtype = 'INBOX';
        $where = "ec.boxtype='" . $boxtype . "' and ec.user_id ='" . $yp_id ."'and ec.is_unread = '1' and ec.   is_deleted = '0'";
        $data['mail_data'] = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', $fields, $join, 'left', '', '', '', '', '', '', $group_by, $where);

        $data['inbox_count'] = count($data['mail_data']);
        return $data['inbox_count'];
}

/*
      @Author : Ritesh Rana
      @Desc   : YP Add Imap setting in db
      @Input    :
      @Output   :
      @Date   : 01/11/2018
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
/*
      @Author : Ritesh Rana
      @Desc   : This function is to get inbox/sent new email only
      @Input  : folder name and yp id
      @Output :
      @Date   : 23/08/2018
     */
function GetNewMailsAll($yp_id,$folderName){
        $userId = $yp_id;
        $configEmailData = $this->getEmailConfigData($userId)[0];
        $mailbox = $configEmailData['email_server'] . ':' . $configEmailData['email_port'] . '/';
        $username = $configEmailData['email_id'];
        $password = base64_decode($configEmailData['email_pass']);
        $encryption = $configEmailData['email_encryption'];

        $withbody = true;
        
        $sort = 'DESC';
        $server = "{" . $configEmailData['email_server'] . ":" . $configEmailData['email_port'] . "/ssl}".$folderName;
        $imap = imap_open($server, $username, $password) or die('Cannot connect to Gmail: ' . imap_last_error());

      if ($imap) {
        $result['status'] = 'success';
        $result['email']  = $username;

         //get YP information
        $match = array("is_deleted"=>0,"boxtype"=>$folderName,"user_id"=>$yp_id);
        $fields = array("uid");
        $get_mail_data = $this->common_model->get_records(EMAIL_CLIENT_MASTER, $fields, '', '', $match);

        $mail1=array();
        foreach ($get_mail_data as $mail_data_val) {
         $mail1[] = $mail_data_val['uid'];
        }
     
        $offset = 0;  
        $read = imap_search($imap, 'ALL');
        $num = count($read); 

        for ($i = $offset; $i < $num; $i++) {
            $uid[] = imap_uid($imap, $read[$i]);
        }
      
     $data_diff = array_diff($mail1,$uid);
     $delete_data = array_merge($data_diff);
     
     foreach ($delete_data as $listId) {
          $dataArray = array('is_deleted' => 1);
          $this->Common_model->update(EMAIL_CLIENT_MASTER, $dataArray, array('uid' => $listId,'user_id'=>$yp_id));
      }
     
      $where = "ec.is_deleted = 0 AND ec.boxtype='" . $folderName . "' AND ec.user_id =" . $yp_id;
      $fields = array("email_unq_id");
      $countEmails = $this->common_model->get_records(EMAIL_CLIENT_MASTER . ' as ec', $fields, '', '', '', '', '', '', '', '', '', $where, '', '', '1', '', '', '');
        
          if($sort == 'DESC'){
            rsort($read);
          }
          
      $limit = $num-$countEmails;
      $result['count'] = $num;
      $stop = $limit + $offset;
      if($stop > $num){
        $stop = $num;
      }
      //get all new mail 
      for ($i = $offset; $i < $stop; $i++) {
        $emails[] = $this->imap->getNewMessage($imap, $read[$i],true,$userId);
      }

      if (count($emails) > 0) {
          $this->InsertEmailData($emails,$yp_id,$folderName);
        } else {
            
        }

    } else {
              $msg = $this->lang->line('mailconfig_message');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$yp_id);
             die;
    }
    
    }


/*
      @Author : Ritesh Rana
      @Desc   : This function is used to insert emails from the account
      @Input  :
      @Output :
      @Date   : 28/11/2018
     */
    function InsertEmailData($emails,$ypid,$folder){

          $YP_details = getYpDetails($ypid);

          $care_home = $YP_details[0]['care_home'];

            $dirpth = FCPATH . "uploads/Mail/$userId/";
            if (!is_dir($dirpth)) {
                mkdir($dirpth, 0777);
            }

            foreach ($emails as $row) {

                $relPath = base_url("uploads/Mail/$userId/" . $row['uid'] . '/');
                $absPath = FCPATH . "uploads/Mail/$userId/" . $row['uid'] . '/';

                if (!is_dir($absPath)) {
                    mkdir($absPath, 0777);
                }

                $attachmentArr = (isset($row['attachments'])) ? $row['attachments'] : array();
                $bodyContent = $this->inlineBodyImageConversion($row['uid'],$row['body'], $attachmentArr, $relPath, $absPath);
                // insert email data
                $data = array(
                    'to_mail' => isset($row['to_email']) ? implode(',', $row['to_email']) : '',
                    'from_mail' => htmlentities($row['from_email']),
                    'send_date' => datetimeformat($row['date']),
                    'mail_subject' => $row['subject'],
                    'uid' => $row['uid'],
                    'is_unread' => $row['unread'],
                    'is_answered' => $row['answered'],
                    'is_deleted' => $row['deleted'],
                    'mail_body' => htmlentities($bodyContent),
                    'is_html' => $row['html'],
                    'user_id' => $ypid,
                    'creation_date' => datetimeformat(),
                    'sync_on' => datetimeformat(),
                    'cc_email' => isset($row['cc']) ? implode(',', $row['cc']) : '',
                    'bcc_email' => isset($row['bcc']) ? implode(',', $row['bcc']) : '',
                    'reply_to_address' => (isset($row['reply_to_address']) && $row['reply_to_address'] != '') ? implode(',', $row['reply_to_address']) : '',
                    'reply_to_email' => (isset($row['reply_to_email']) && $row['reply_to_email'] != '') ? implode(',', $row['reply_to_email']) : '',
                    'from_mail_address' => $row['from'],
                    'to_email_address' => isset($row['to']) ? implode(',', $row['to']) : '',
                    'header_data' => json_encode(array($row['header'])),
                    'msg_no' => $row['message_no'],
                    'care_home_id' => $care_home,
                    'boxtype' => $folder
                );
                $this->db->insert(EMAIL_CLIENT_MASTER, $data);
                $mailId = $this->db->insert_id();
                
// start - insert attachment  data
                if (isset($row['attachments'])) {
                    foreach ($row['attachments'] as $attch) {
                        if (isset($attch['is_attachment']) && $attch['is_attachment']==1) {
                            $attcharr = array(
                                'email_id' => $mailId,
                                'file_name' => $attch['filename'],
                                'file_size' => $attch['size'],
                                'user_id' => $userId,
                                'boxtype_file' => $folder,
                                'care_home_id' => $care_home,
                                'file_name_app' => $attch['cid']
                            );
                            $this->db->insert('email_client_attachments', $attcharr);
                        }
                    }
                }
            }
            return true;
    }

}
