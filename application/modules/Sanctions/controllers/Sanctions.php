<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sanctions extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session', 'm_pdf'));
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Main Listing Page
      @Date   : 29/01/2019
     */
public function index($ypId = 0) {
        if (is_numeric($ypId)) {
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($ypId, $fields);
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/' . $ypId);
            }

            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = RECORD_PER_PAGE;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('aai_sanction_data');
            }

            $searchsort_session = $this->session->userdata('aai_sanction_data');
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
                    $sortfield = 'date_sanction_issued';
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
                    $config['per_page'] = RECORD_PER_PAGE;
                    $data['perpage'] = RECORD_PER_PAGE;
                }
            }
            //pagination configuration
            $config['first_link'] = 'First';
            $config['base_url'] = base_url() . $this->viewname . '/index/' . $ypId;
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 4;
                $uri_segment = $this->uri->segment(4);
            }

            //Query
            $table = AAI_SANCTIONS . ' as san';
            $where = array("san.yp_id" => $ypId);
            $fields = array("san.date_sanction_issued,san.sanction_reference_number,san.reason_for_sanction,CONCAT(`firstname`,' ', `lastname`) as name,alm.reference_number,san.sanctions_id,san.yp_id");
            $join_tables = array(SANCTIONS_PROCESS . ' as sp' => 'sp.sanctions_id=san.sanctions_id',AAI_LIST_MAIN . ' as alm' => 'alm.list_main_incident_id = sp.list_main_incident_id',LOGIN . ' as l' => 'l.login_id=san.created_by');

            if (!empty($searchtext)) {
                $searchtext = html_entity_decode(trim(addslashes($searchtext)));
                $match = array("san.yp_id" => $ypId);
                $formated_date = dateformat($searchtext);
                $where_search = '('
                        . 'alm.reference_number LIKE "%' . $searchtext . '%" OR '
                        . 'san.date_sanction_issued LIKE "%' . $formated_date . '%" OR '
                        . 'san.sanction_reference_number LIKE "%' . $searchtext . '%" OR '
                        . 'alm.reference_number LIKE "%' . $searchtext . '%" OR '
                        . 'san.reason_for_sanction LIKE "%' . $searchtext . '%")';

                $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match, '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);

                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match, '', '', '', $sortfield, $sortby, '', $where_search, '', '', '1');
            } else {
                $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);

                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
            }

            $this->ajax_pagination->initialize($config);
            $data['pagination'] = $this->ajax_pagination->create_links();
            $data['uri_segment'] = $uri_segment;
            $sortsearchpage_data = array(
                'sortfield' => $data['sortfield'],
                'sortby' => $data['sortby'],
                'searchtext' => $data['searchtext'],
                'perpage' => trim($data['perpage']),
                'uri_segment' => $uri_segment,
                'total_rows' => $config['total_rows']);
            $this->session->set_userdata('aai_sanction_data', $sortsearchpage_data);
            setActiveSession('aai_sanction_data'); // set current Session active
            $data['ypId'] = $ypId;
            $data['isCareIncident'] = 1;
            
            $data['crnt_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'Sanction');
            $data['footerJs'][0] = base_url('uploads/custom/js/Sanctions/Sanctions.js');
            if ($this->input->is_ajax_request()) {
                $this->load->view($this->viewname . '/ajaxlist', $data);
            } else {
                $data['main_content'] = '/sanctions_accident_and_incident';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }


/*
      @Author : Ritesh Rana
      @Desc   : Add page
      @Input    :
      @Output   :
      @Date   : 31/01/2019
     */

    public function add($ypid) {
      if(is_numeric($ypid))
      {

           $table = AAI_LIST_MAIN . ' as ai';
            $where = 'date(ai.created_date) >= CURDATE() - INTERVAL 14 DAY AND sanctions = 0  AND yp_id = '.$ypid.'';
            $fields = array("ai.*,ch.care_home_name,sta.title");
            $join_tables = array(CARE_HOME . ' as ch' => 'ch.care_home_id = ai.care_home_id', AAI_DROPDOWN_OPTION . ' as sta' => 'sta.option_id = ai.form_status');
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $where);
            
        $incidentData = $this->common_model->get_records(AAI_MAIN, array("manager_review_form_data"), '', '', array('incident_id' => $incident_id));
        $incidentData = $incidentData[0];
        $editManagerReviewData = json_decode($incidentData['manager_review_form_data'], true);
            
        //get sanctions form
        $match = array('sanction_form_id'=> 1);
        $sn_forms = $this->common_model->get_records(SANCTION_FORM,array("form_json_data"), '', '', $match);

        if(!empty($sn_forms))
        {
            $data['form_data'] = json_decode($sn_forms[0]['form_json_data'], TRUE);
        }
        //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($ypid, $fields);

        if(empty($data['YP_details']))
        {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson/view/'.$ypid);
        }

        $emailMatch = '(email LIKE "%_@__%.__%")';
        $nfcUsers = $this->common_model->get_records(LOGIN, array('login_id as user_id', 'firstname as first_name', 'lastname as last_name', 'email'), '', '', '', '', '', '', '', '', '', $emailMatch);

        function appendNFCType($n) {
            $n ['user_type'] = 'N';
            $n ['job_title'] = '';
            $n ['work_location'] = '';
            return $n;
        }

        $nfcUsers = array_map("appendNFCType", $nfcUsers);
        $bambooUsers = $this->common_model->get_records(BAMBOOHR_USERS, array('user_id', 'first_name', 'last_name', 'email', 'job_title', 'work_location'), '', '', '', '', '', '', '', '', '', $emailMatch);

        function appendBambooType($n) {
            $n ['user_type'] = 'B';
            return $n;
        }

        $bambooUsers = array_map("appendBambooType", $bambooUsers);
        $data['bambooNfcUsers'] = array_merge($bambooUsers, $nfcUsers);
       
        //get sanctions yp data
            $table = AAI_SANCTIONS . ' as sanc';
            $where = array("sanc.incident_id" => $incident_id, "sanc.yp_id" => $ypid);
            $fields = array("sanc.*,am.reference_number");
            $join_tables = array(AAI_MAIN . ' as am' => 'am.incident_id= sanc.incident_id');
            $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);

        /*end query sanctions*/
        $url_data =  base_url('Sanctions/add/'.$incident_id.'/'.$ypid);
        $match = array('url_data'=>$url_data);
        $data['check_edit_permission'] = $this->common_model->get_records(CHECK_EDIT_URL,'*', '', '', $match);
        if(count($data['check_edit_permission']) > 0){ 
        $in_time = date('Y-m-d h:i:s',strtotime($data['check_edit_permission'][0]['datetime']));
        $currnt_time = date('Y-m-d h:i:s');
        if(strtotime($in_time)>strtotime($currnt_time))
         {
            $now = strtotime($in_time) - strtotime($currnt_time);        
         } 
          else
         {
            $now = strtotime($currnt_time) - strtotime($in_time);
         }
        //  die;
         $secs = floor($now % 60);
        
        if($secs >= 10)
        {
          $data['ypid'] = $ypid;
          $data['footerJs'][0] = base_url('uploads/custom/js/Sanctions/Sanctions.js');
          $data['header'] = array('menu_module' => 'YoungPerson');
          $data['crnt_view'] = $this->viewname;
          $data['main_content'] = '/edit';
          $this->parser->parse('layouts/DefaultTemplate', $data);
        }
        else
        {
          $msg = $this->lang->line('check_is_user_update_data');
          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
          redirect('/' . $this->viewname .'/view/'.$id.'/'.$ypid);
        }
      }else{
                $data['ypid'] = $ypid;
                $data['footerJs'][0] = base_url('uploads/custom/js/Sanctions/Sanctions.js');
                $data['header'] = array('menu_module' => 'YoungPerson');
                $data['crnt_view'] = $this->viewname;
                $data['main_content'] = '/edit';
                $this->parser->parse('layouts/DefaultTemplate', $data);
      }
       
      }
      else
      {
          show_404 ();
      }
    }


   
/*
      @Author : Ritesh Rana
      @Desc   : Edit page
      @Input    :
      @Output   :
      @Date   : 31/01/2019
     */

    public function edit($ypid,$sanctions_id) {
      if(is_numeric($ypid))
      {

        //get sanctions form
        $match = array('sanction_form_id'=> 1);
        $sn_forms = $this->common_model->get_records(SANCTION_FORM,array("form_json_data"), '', '', $match);

        if(!empty($sn_forms))
        {
            $data['form_data'] = json_decode($sn_forms[0]['form_json_data'], TRUE);
        }
        //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($ypid, $fields);

        if(empty($data['YP_details']))
        {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson/view/'.$ypid);
        }

        $emailMatch = '(email LIKE "%_@__%.__%")';
        $nfcUsers = $this->common_model->get_records(LOGIN, array('login_id as user_id', 'firstname as first_name', 'lastname as last_name', 'email'), '', '', '', '', '', '', '', '', '', $emailMatch);

        function appendNFCType($n) {
            $n ['user_type'] = 'N';
            $n ['job_title'] = '';
            $n ['work_location'] = '';
            return $n;
        }

        $nfcUsers = array_map("appendNFCType", $nfcUsers);
        $bambooUsers = $this->common_model->get_records(BAMBOOHR_USERS, array('user_id', 'first_name', 'last_name', 'email', 'job_title', 'work_location'), '', '', '', '', '', '', '', '', '', $emailMatch);

        function appendBambooType($n) {
            $n ['user_type'] = 'B';
            return $n;
        }

        $bambooUsers = array_map("appendBambooType", $bambooUsers);
        $data['bambooNfcUsers'] = array_merge($bambooUsers, $nfcUsers);
       
        //get sanctions yp data
            $table = AAI_SANCTIONS . ' as sanc';
            $where = array("sanc.sanctions_id" => $sanctions_id, "sanc.yp_id" => $ypid);
            $fields = array("sanc.*,am.reference_number");
            $join_tables = array(AAI_MAIN . ' as am' => 'am.incident_id= sanc.incident_id');
            $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
             //pr($data['edit_data']);exit;   

            $table = AAI_LIST_MAIN . ' as ai';
            //$where = 'date(ai.created_date) >= CURDATE() - INTERVAL 14 DAY AND ai.ypid = '.$ypid.'';
            $where = 'date(ai.created_date) >= CURDATE() - INTERVAL 14 DAY AND ai.yp_id = '.$ypid.'';
            $fields = array("ai.*,ch.care_home_name,sta.title");
            $join_tables = array(CARE_HOME . ' as ch' => 'ch.care_home_id = ai.care_home_id', AAI_DROPDOWN_OPTION . ' as sta' => 'sta.option_id = ai.form_status');

               $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $where);


            $table = SANCTIONS_PROCESS . ' as sp';
            $where = array("sp.sanctions_id" => $sanctions_id, "sp.yp_id" => $ypid);
            $fields = array("sp.list_main_incident_id");
            $sanction_process_id = $this->common_model->get_records($table, $fields, '', '', $where);
                $list = array();
                foreach ($sanction_process_id as $value) {
                       $list[] = $value['list_main_incident_id'];     
                }
        $data['sanction_incident'] = $list;

        /*end query sanctions*/
        $url_data =  base_url('Sanctions/edit/'.$sanctions_id.'/'.$ypid);
        $match = array('url_data'=>$url_data);
        $data['check_edit_permission'] = $this->common_model->get_records(CHECK_EDIT_URL,'*', '', '', $match);
        if(count($data['check_edit_permission']) > 0){ 
        $in_time = date('Y-m-d h:i:s',strtotime($data['check_edit_permission'][0]['datetime']));
        $currnt_time = date('Y-m-d h:i:s');
        if(strtotime($in_time)>strtotime($currnt_time))
         {
            $now = strtotime($in_time) - strtotime($currnt_time);        
         } 
          else
         {
            $now = strtotime($currnt_time) - strtotime($in_time);
         }
        //  die;
         $secs = floor($now % 60);
        
        if($secs >= 10)
        {
          $data['ypid'] = $ypid;
          $data['sanctions_id'] = $sanctions_id;
          
          $data['footerJs'][0] = base_url('uploads/custom/js/Sanctions/Sanctions.js');
          $data['header'] = array('menu_module' => 'YoungPerson');
          $data['crnt_view'] = $this->viewname;
          $data['main_content'] = '/edit';
          $this->parser->parse('layouts/DefaultTemplate', $data);
        }
        else
        {
          $msg = $this->lang->line('check_is_user_update_data');
          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
          redirect('/' . $this->viewname .'/view/'.$id.'/'.$ypid);
        }
      }else{
                $data['ypid'] = $ypid;
                $data['sanctions_id'] = $sanctions_id;
                $data['footerJs'][0] = base_url('uploads/custom/js/Sanctions/Sanctions.js');
                $data['header'] = array('menu_module' => 'YoungPerson');
                $data['crnt_view'] = $this->viewname;
                $data['main_content'] = '/edit';
                $this->parser->parse('layouts/DefaultTemplate', $data);
      }
       
      }
      else
      {
          show_404 ();
      }
    }


    /*
      @Author : Ritesh Rana
      @Desc   : Edit page
      @Input    :
      @Output   :
      @Date   : 31/01/2019
     */

    public function sanctionsView($ypid,$sanctions_id) {
      if(is_numeric($sanctions_id) && is_numeric($ypid))
      {
        //get sanctions form
        $match = array('sanction_form_id'=> 1);
        $sn_forms = $this->common_model->get_records(SANCTION_FORM,array("form_json_data"), '', '', $match);

        if(!empty($sn_forms))
        {
            $data['form_data'] = json_decode($sn_forms[0]['form_json_data'], TRUE);
        }
        //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($ypid, $fields);

        if(empty($data['YP_details']))
        {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson/view/'.$ypid);
        }

        $emailMatch = '(email LIKE "%_@__%.__%")';
        $nfcUsers = $this->common_model->get_records(LOGIN, array('login_id as user_id', 'firstname as first_name', 'lastname as last_name', 'email'), '', '', '', '', '', '', '', '', '', $emailMatch);

        function appendNFCType($n) {
            $n ['user_type'] = 'N';
            $n ['job_title'] = '';
            $n ['work_location'] = '';
            return $n;
        }

        $nfcUsers = array_map("appendNFCType", $nfcUsers);
        $bambooUsers = $this->common_model->get_records(BAMBOOHR_USERS, array('user_id', 'first_name', 'last_name', 'email', 'job_title', 'work_location'), '', '', '', '', '', '', '', '', '', $emailMatch);

        function appendBambooType($n) {
            $n ['user_type'] = 'B';
            return $n;
        }

        $bambooUsers = array_map("appendBambooType", $bambooUsers);
        $data['bambooNfcUsers'] = array_merge($bambooUsers, $nfcUsers);
       
         //get sanctions yp data
            $table = AAI_SANCTIONS . ' as sanc';
            $where = array("sanc.sanctions_id" => $sanctions_id, "sanc.yp_id" => $ypid);
            $fields = array("sanc.*,am.reference_number");
            $join_tables = array(AAI_MAIN . ' as am' => 'am.incident_id= sanc.incident_id');
            $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);

            $table = AAI_LIST_MAIN . ' as ai';
            //$where = 'date(ai.created_date) >= CURDATE() - INTERVAL 14 DAY AND ai.ypid = '.$ypid.'';
            $where = 'date(ai.created_date) >= CURDATE() - INTERVAL 14 DAY AND ai.yp_id = '.$ypid.'';
            $fields = array("ai.*,ch.care_home_name,sta.title");
            $join_tables = array(CARE_HOME . ' as ch' => 'ch.care_home_id = ai.care_home_id', AAI_DROPDOWN_OPTION . ' as sta' => 'sta.option_id = ai.form_status');

               $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $where);


            $table = SANCTIONS_PROCESS . ' as sp';
            $where = array("sp.sanctions_id" => $sanctions_id, "sp.yp_id" => $ypid);
            $fields = array("sp.list_main_incident_id");
            $sanction_process_id = $this->common_model->get_records($table, $fields, '', '', $where);
                $list = array();
                foreach ($sanction_process_id as $value) {
                       $list[] = $value['list_main_incident_id'];     
                }
        $data['sanction_incident'] = $list;
        $data['ypid'] = $ypid;
        $data['incident_id'] = $incident_id;
        $data['footerJs'][0] = base_url('uploads/custom/js/Sanctions/Sanctions.js');
        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['crnt_view'] = $this->viewname;
        $data['main_content'] = '/sanctionsView';
        $this->parser->parse('layouts/DefaultTemplate', $data);
      }
      else
      {
          show_404 ();
      }
    }

 /*
      @Author : Ritesh Rana
      @Desc   : Insert report
      @Input    :
      @Output   :
      @Date   : 01/02/2019
     */
    public function insert() {
        if (!validateFormSecret()) {
            redirect($_SERVER['HTTP_REFERER']);  //Redirect On Listing page
        }
        $postData = $this->input->post();
        unset($postData['submit_mdtform']);
            //get sanctions form
            $match = array('sanction_form_id' => 1);
            $sn_forms = $this->common_model->get_records(SANCTION_FORM, array("form_json_data"), '', '', $match);
            if (!empty($sn_forms)) {
                $sn_form_data = json_decode($sn_forms[0]['form_json_data'], TRUE);
                $data = array();
                foreach ($sn_form_data as $row) {
                    if (isset($row['name'])) {
                        if ($row['type'] == 'file') {
                            $filename = $row['name'];
                            //get image previous image
                            $match = array('yp_id' => $postData['yp_id']);
                            $sanc_yp_data = $this->common_model->get_records(AAI_SANCTIONS, array('`' . $row['name'] . '`'), '', '', $match);
                            //delete img
                            if (!empty($postData['hidden_' . $row['name']])) {
                                $delete_img = explode(',', $postData['hidden_' . $row['name']]);
                                $db_images = explode(',', $sanc_yp_data[0][$filename]);
                                $differentedImage = array_diff($db_images, $delete_img);
                                $sanc_yp_data[0][$filename] = !empty($differentedImage) ? implode(',', $differentedImage) : '';
                            }

                            if (!empty($_FILES[$filename]['name'][0])) {
                                createDirectory(array($this->config->item('sn_base_url'), $this->config->item('sn_base_big_url'), $this->config->item('sn_base_big_url') . '/' . $postData['yp_id']));

                                $file_view = $this->config->item('sn_img_url') . $postData['yp_id'];
                                //upload big image
                                $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/index/' . $postData['yp_id']);
                                //upload small image
                                $insertImagesData = array();
                                if (!empty($upload_data)) {
                                    foreach ($upload_data as $imageFiles) {
                                        /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                        createDirectory(array($this->config->item('sn_base_small_url'), $this->config->item('sn_base_small_url') . '/' . $postData['yp_id']));

                                        /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                        if ($imageFiles['is_image'])
                                            $a = do_resize($this->config->item('sn_img_url') . $postData['yp_id'], $this->config->item('sn_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                        array_push($insertImagesData, $imageFiles['file_name']);
                                        if (!empty($insertImagesData)) {
                                            $images = implode(',', $insertImagesData);
                                        }
                                    }
                                    if (!empty($san_yp_data[0][$filename])) {
                                        $images .=',' . $san_yp_data[0][$filename];
                                    }
                                    $data[$row['name']] = !empty($images) ? $images : '';
                                }
                            } else {
                               $data[$row['name']] = !empty($san_yp_data[0][$filename]) ? $san_yp_data[0][$filename] : '';
                            }
                        } else {
                            if ($row['type'] != 'button') {
                            if ($row['type'] == 'checkbox-group') {
                                $data[$row['name']] = !empty($postData[$row['name']]) ? implode(',', $postData[$row['name']]) : '';
                            } elseif ($row['type'] == 'textarea' && $row['subtype'] == 'tinymce') {
                                $data[$row['name']] = strip_slashes($postData[$row['name']]);
                            } elseif ($row['type'] == 'date') {
                                $data[$row['name']] = dateformat($postData[$row['name']]);
                            } elseif ($row['type'] == 'text') {
                              if($row['subtype'] == 'time'){
                                $data[$row['name']] = dbtimeformat($postData[$row['name']]);
                              }else{
                                $data[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                              }
                            } else {
                                $data[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                            }
                        }

                        }
                    }
                }
            
             //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data_care_home_id['YP_details_care_home_id'] = YpDetails($postData['yp_id'], $fields);    

            $data['yp_id'] = $postData['yp_id'];
            //$data['incident_id'] = $postData['incident_id'];
            

            if (!empty($postData['sanctions_id'])) {
                $sanctions_id = $postData['sanctions_id'];
                $data['care_home_id'] = $data_care_home_id['YP_details_care_home_id'][0]['care_home']; //get carehome id
                $data['modified_date'] = datetimeformat();
                $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
                $this->common_model->update(AAI_SANCTIONS, $data, array('sanctions_id' => $postData['sanctions_id']));

                //Insert log activity
                $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                    'module_name' => SANCTION,
                    'module_field_name' => '',
                    'type' => 2
                );
                log_activity($activity);

                $sanction_process = $postData['sanction_incident'];
                if(!empty($sanction_process)){
                 $this->common_model->delete(SANCTIONS_PROCESS, array('sanctions_id' => $postData['sanctions_id']));
                foreach ($sanction_process as $value_data) {
                    $updateData = array(
                        'sanctions' => 1,
                    );
                $this->common_model->update(AAI_LIST_MAIN, $updateData, array('list_main_incident_id' => $value_data));
                    $data_sanctions['sanctions_id'] = $postData['sanctions_id']; 
                    $data_sanctions['yp_id'] = $postData['yp_id'];
                    $data_sanctions['list_main_incident_id'] = $value_data;
                    $data_sanctions['created_date'] = datetimeformat();
                    $data_sanctions['modified_date'] = datetimeformat();
                    $data_sanctions['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
                    $this->common_model->insert(SANCTIONS_PROCESS, $data_sanctions);  

                }
            }
        

            } else {
                $data['care_home_id'] = $data_care_home_id['YP_details_care_home_id'][0]['care_home']; //get carehome id
                $data['yp_id'] = $postData['yp_id'];
                $data['created_date'] = datetimeformat();
                $data['modified_date'] = datetimeformat();
                $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];

                $this->common_model->insert(AAI_SANCTIONS, $data);
                $sanctions_id = $this->db->insert_id();

                $yp_details       = YpDetails($postData['yp_id'], array("yp_initials"));
                $refIncidentId    = str_pad($sanctions_id, 8, '0', STR_PAD_LEFT);
                $sanction_reference_number = 'SA' . substr($yp_details[0]['yp_initials'], 0, 3) . date('dmy') . $refIncidentId;

                $this->common_model->update(AAI_SANCTIONS, array('sanction_reference_number' => $sanction_reference_number), array('sanctions_id' => $sanctions_id));


                $updateData = array(
                    'sanctions' => 1,
                );

                $this->common_model->update(AAI_MAIN, $updateData, array('incident_id' => $data['incident_id']));

                //Insert log activity
                $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                    'module_name' => SANCTION,
                    'module_field_name' => '',
                    'type' => 1
                );
                log_activity($activity);
            }

            if (!empty($sanctions_id)) {
                $sanction_process = $postData['sanction_incident'];
                if(!empty($sanction_process)){
                 $this->common_model->delete(SANCTIONS_PROCESS, array('sanctions_id' => $sanctions_id));
                foreach ($sanction_process as $value_data) {
                    $updateData = array(
                        'sanctions' => 1,
                    );
                $this->common_model->update(AAI_LIST_MAIN, $updateData, array('list_main_incident_id' => $value_data));
                    $data_sanctions['sanctions_id'] = $sanctions_id; 
                    $data_sanctions['yp_id'] = $postData['yp_id'];
                    $data_sanctions['list_main_incident_id'] = $value_data;
                    $data_sanctions['created_date'] = datetimeformat();
                    $data_sanctions['modified_date'] = datetimeformat();
                    $data_sanctions['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
                    $this->common_model->insert(SANCTIONS_PROCESS, $data_sanctions);  

                }
            }
        }

            redirect('/' . $this->viewname . '/sanctionsView/' . $postData['yp_id'].'/'.$sanctions_id);
        }
    }


    /*
      @Author : Ritesh Rana
      @Desc   : Read more
      @Input  : incident_id
      @Output :
      @Date   : 26/02/2019
     */

    function readmore($list_main_incident_id, $reference_number, $field) {
        $incidentData = $this->common_model->get_records(AAI_LIST_MAIN, $field, '', '', array('list_main_incident_id' => $list_main_incident_id,'reference_number' => $reference_number));
        $data['field_val'] = $incidentData[0]['description'];
        $this->load->view($this->viewname . '/readmore', $data);
    }

   }
