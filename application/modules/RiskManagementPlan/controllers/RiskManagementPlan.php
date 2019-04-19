<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RiskManagementPlan extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->viewname = $this->router->fetch_class ();
        $this->method   = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Niral Patel
      @Desc   : RiskManagementPlan Index Page
      @Input  : yp id
      @Output :
      @Date   : 15/05/2018
     */
    public function index($id,$care_home_id=0,$past_care_id=0) {
      /*
        Ritesh Rana
        for past care id inserted for archive full functionality
      */
         if ($past_care_id !== 0) {
            $temp = $this->common_model->get_records(PAST_CARE_HOME_INFO, array('move_date'), '', '', array("yp_id" => $id, "past_carehome" => $care_home_id));
            $data_care_home_detail = $this->common_model->get_records(PAST_CARE_HOME_INFO, array("enter_date,move_date"), '', '', array("yp_id" => $id, "move_date <= " => $temp[0]['move_date']));
            $created_date = $movedate = '';
            $count_care = count($data_care_home_detail);
            if ($count_care >= 1) {
                $created_date = $data_care_home_detail[0]['enter_date'];
                $movedate = $data_care_home_detail[$count_care - 1]['move_date'];
            } elseif ($count_care == 0) {
                $created_date = $data_care_home_detail[0]['enter_date'];
                $movedate = $data_care_home_detail[0]['move_date'];
            }
        }
      if(is_numeric($id))
      {
        //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($id,$fields);
        if(empty($data['YP_details']))
        {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson/view/'.$id);
        }
        $searchtext='';
        $perpage='';
        $searchtext = $this->input->post('searchtext');
        $sortfield  = $this->input->post('sortfield');
        $sortby     = $this->input->post('sortby');
        $perpage    = 10;
        $allflag    = $this->input->post('allflag');
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('rmp_data');
        }
        
        $searchsort_session = $this->session->userdata('rmp_data');
        //Sorting
        if(!empty($sortfield) && !empty($sortby))
        {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        }
        else
        {
            if(!empty($searchsort_session['sortfield']))
            {
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby'] = $searchsort_session['sortby'];
                $sortfield = $searchsort_session['sortfield'];
                $sortby = $searchsort_session['sortby'];
            }
            else
            {
                $sortfield = 'rmp_id';
                $sortby = 'desc';
                $data['sortfield']    = $sortfield;
                $data['sortby']     = $sortby;
            }
        }
        //Search text
        if(!empty($searchtext))
        {
            $data['searchtext'] = $searchtext;
        } else
        {
            if(empty($allflag) && !empty($searchsort_session['searchtext']))
            {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext =  $data['searchtext'];
            }
            else
            {
                $data['searchtext'] = '';
            }
        }
        
        if(!empty($perpage) && $perpage != 'null')
        {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        }
        else
        {
            if(!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
                $data['perpage'] = '10';
            }
        }
        //pagination configuration
        $config['first_link']  = 'First';
  /* condition added by Ritesh Rana on 03/10/2018 to archive functionaity */
        if($past_care_id == 0){
          $config['base_url']    = base_url().$this->viewname.'/index/'.$id;

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        
        //Query
        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = RMP.' as rmp';
        $where = array("rmp.yp_id"=> $id,"rmp.is_archive"=> 0,"rmp.is_delete"=> 0);
        $fields = array("l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, ch.care_home_name, l.lastname, rmp.*,STR_TO_DATE( rmp.created_date , '%d/%m/%Y' ) as date");

        $join_tables = array(LOGIN . ' as l' => 'l.login_id = rmp.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = rmp.care_home_id');
       if(!empty($searchtext))
        {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search='((CONCAT(`firstname`, \' \', `lastname`) LIKE "%'.$searchtext.'%" OR l.firstname LIKE "%'.$searchtext.'%" OR l.lastname LIKE "%'.$searchtext.'%" OR rmp.date LIKE "%'.$searchtext.'%" OR rmp.time LIKE "%'.$searchtext.'%" OR l.status LIKE "%'.$searchtext.'%")AND l.is_delete = "0")';

            $data['edit_data']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where_search);

            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,'','',$sortfield,$sortby,'',$where_search,'','','1');
        }
        else
        {
            $data['edit_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where);

            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','',$sortfield,$sortby,'',$where,'','','1');
        }
        }else{
          $config['base_url']    = base_url().$this->viewname.'/index/'.$id.'/'.$care_home_id.'/'.$past_care_id;

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 6;
            $uri_segment = $this->uri->segment(6);
        }
        //Query
        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = RMP.' as rmp';
        $where = array("rmp.yp_id"=> $id,"rmp.is_archive"=> 0,"rmp.is_delete"=> 0);
        $where_date = "rmp.created_date BETWEEN  '".$created_date."' AND '".$movedate."'";
       $fields = array("l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, ch.care_home_name, l.lastname, rmp.*,STR_TO_DATE( rmp.created_date , '%d/%m/%Y' ) as date");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id = rmp.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = rmp.care_home_id');
       if(!empty($searchtext))
        {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search='((CONCAT(`firstname`, \' \', `lastname`) LIKE "%'.$searchtext.'%" OR l.firstname LIKE "%'.$searchtext.'%" OR l.lastname LIKE "%'.$searchtext.'%" OR rmp.date LIKE "%'.$searchtext.'%" OR rmp.time LIKE "%'.$searchtext.'%" OR l.status LIKE "%'.$searchtext.'%")AND l.is_delete = "0")';

            $data['edit_data']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where_search,'','','','','',$where_date);

            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,'','',$sortfield,$sortby,'',$where_search,'','','1','','',$where_date);
        }
        else
        {
            $data['edit_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where,'','','','','',$where_date);
            
            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','',$sortfield,$sortby,'',$where,'','','1','','',$where_date);
        }
        }

        $this->ajax_pagination->initialize($config);
        $data['pagination']  = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        $sortsearchpage_data = array(
            'sortfield'  => $data['sortfield'],
            'sortby'     => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage'    => trim($data['perpage']),
            'uri_segment'=> $uri_segment,
            'total_rows' => $config['total_rows']);
        
        
        $data['ypid'] = $id;
        $data['care_home_id'] = $care_home_id;
        $data['past_care_id'] = $past_care_id;
        //get rmp form
        $ks_forms = $this->getRmpFormData();
        if(!empty($ks_forms))
        {
            $data['form_data'] = json_decode($ks_forms[0]['form_json_data'], TRUE);
        }
        $this->session->set_userdata('rmp_data', $sortsearchpage_data);
        setActiveSession('rmp_data'); 
        // set current Session active
        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/RiskManagementPlan/RiskManagementPlan.js');
        if($this->input->post('result_type') == 'ajax'){
            $this->load->view($this->viewname . '/ajaxlist', $data);
        } else {
            $data['main_content'] = '/RiskManagementPlan';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
      }
      else
      {
          show_404 ();
      }
    }
    
    /*
      @Author : Niral Patel
      @Desc   : create RiskManagementPlan
      @Input  :
      @Output :
      @Date   : 15/05/2018
     */

    public function create($id) {
      if(is_numeric($id))
      {
       //get rmp form
       $ks_forms = $this->getRmpFormData();
       $form_field = array();
       if(!empty($ks_forms))
       {
            $data['ks_form_data'] = json_decode($ks_forms[0]['form_json_data'], TRUE);
            foreach ($data['ks_form_data'] as $form_data) {
                         $form_field[] = $form_data['name'];
            }
       }
       $data['form_field_data_name'] = $form_field;
       //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($id,$fields);
        if(empty($data['YP_details']))
        {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson/view/'.$id);
        }
        $data['ypid'] = $id;
        $data['footerJs'][0] = base_url('uploads/custom/js/RiskManagementPlan/RiskManagementPlan.js');
        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['crnt_view'] = $this->viewname;
        $data['main_content'] = '/edit';
        $this->parser->parse('layouts/DefaultTemplate', $data);
      }
      else
      {
          show_404 ();
      }
    }

/*
      @Author : Niral Patel
      @Desc   : edit rmp form
      @Input    :
      @Output   :
      @Date   : 17/05/2018
     */
public function edit_draft($rmp_id,$yp_id) {
    if(is_numeric($rmp_id) && is_numeric($yp_id))
    {
       //get rmp form
       $ks_forms = $this->getRmpFormData();
       if(!empty($ks_forms))
       {
            $data['ks_form_data'] = json_decode($ks_forms[0]['form_json_data'], TRUE);
            foreach ($data['ks_form_data'] as $form_data) {
                         $form_field[] = $form_data['name'];
            }
       }
       $data['form_field_data_name'] = $form_field;
        //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($yp_id,$fields); 
        //get RMP yp data
        $match = array('rmp_id'=> $rmp_id);
        $data['edit_data'] = $this->common_model->get_records(RMP,'', '', '', $match);
        //check data exist or not
        if(empty($data['YP_details']) || empty($data['edit_data']))
        {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson/view/'.$yp_id);
        }
        $data['ypid'] = $yp_id;
        $data['rmp_id'] = $rmp_id;
        $data['footerJs'][0] = base_url('uploads/custom/js/RiskManagementPlan/RiskManagementPlan.js');
        $data['crnt_view'] = $this->viewname;
        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['main_content'] = '/edit';
        $this->parser->parse('layouts/DefaultTemplate', $data);
      }
      else
      {
          show_404 ();
      }
    }
    /*
      @Author : Niral Patel
      @Desc   : copy rmp form
      @Input    :
      @Output   :
      @Date   : 17/05/2018
     */
public function copy($rmp_id,$yp_id) {
    if(is_numeric($rmp_id) && is_numeric($yp_id))
    {
       //get rmp form
       $ks_forms = $this->getRmpFormData();
       if(!empty($ks_forms))
       {
            $data['ks_form_data'] = json_decode($ks_forms[0]['form_json_data'], TRUE);
            foreach ($data['ks_form_data'] as $form_data) {
                         $form_field[] = $form_data['name'];
            }
       }
       $data['form_field_data_name'] = $form_field;
      //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($yp_id,$fields);  
        //get RMP yp data
        $match = array('rmp_id'=> $rmp_id);
        $data['edit_data'] = $this->common_model->get_records(RMP,'', '', '', $match);

        //check data exist or not
        if(empty($data['YP_details']) || empty($data['edit_data']))
        {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson/view/'.$yp_id);
        }
        $data['ypid'] = $yp_id;
        $data['is_copy'] = $rmp_id;
        //$data['rmp_id'] = $rmp_id;
        $data['footerJs'][0] = base_url('uploads/custom/js/RiskManagementPlan/RiskManagementPlan.js');
        $data['crnt_view'] = $this->viewname;
        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['main_content'] = '/edit';
        $this->parser->parse('layouts/DefaultTemplate', $data);
      }
      else
      {
          show_404 ();
      }
    }
    /*
      @Author : Niral Patel
      @Desc   : Insert rmp form
      @Input    :
      @Output   :
      @Date   : 15/05/2018
     */
    public function insert() {
        $postData = $this->input->post();
        $draftdata = (isset($postData['saveAsDraft'])) ? $postData['saveAsDraft'] : 0;
        if ($this->input->is_ajax_request()) {
            $draftdata = 1;
        }
        $is_copy = $postData['is_copy'];
        //get rmp data
        if (!empty($postData['activity_date_from']) && $postData['activity_date_to']) {
            $match = array('yp_id' => $postData['yp_id'], 'activity_date_from' => dateformat($postData['activity_date_from']), 'activity_date_to' => dateformat($postData['activity_date_to']));
            $where = array();
            $rmpdata = $this->common_model->get_records(RMP, 'rmp_id', '', '', $match, '', '', '', '', '', '', '', $where);
            $is_delete_flag = 0;
            $is_draft = 0;
            if ((!empty($rmpdata))) {
                foreach ($rmpdata as $rmp_data) {
                    if ($rmp_data['yp_id'] == $postData['yp_id'] && $rmp_data['is_delete'] == 1) {
                        $is_delete_flag = 1;
                        $is_draft = $rmp_data['draft'];
                    } else {
                        $is_delete_flag = 0;
                        $is_draft = 0;
                    }
                }
            }
            unset($postData['submit_rmpform']);
            //get RMP form
            $ks_forms = $this->getRmpFormData();
            if (!empty($ks_forms)) {
                $ks_form_data = json_decode($ks_forms[0]['form_json_data'], TRUE);
                $data = array();
                foreach ($ks_form_data as $row) {
                    if (isset($row['name'])) {
                        if ($row['type'] == 'file') {
                            $filename = $row['name'];
                            //get image previous image
                            $match = array('rmp_id' => $postData['rmp_id'], 'yp_id' => $postData['yp_id']);
                            $ks_yp_data = $this->common_model->get_records(RMP, array('`' . $row['name'] . '`'), '', '', $match);
                            //delete img
                            if (!empty($postData['hidden_' . $row['name']])) {
                                $delete_img = explode(',', $postData['hidden_' . $row['name']]);
                                $db_images = explode(',', $ks_yp_data[0][$filename]);
                                $differentedImage = array_diff($db_images, $delete_img);
                                $ks_yp_data[0][$filename] = !empty($differentedImage) ? implode(',', $differentedImage) : '';
                                if (!empty($delete_img)) {
                                    foreach ($delete_img as $img) {
                                        if (file_exists($this->config->item('rmp_img_url') . $postData['yp_id'] . '/' . $img)) {
                                            unlink($this->config->item('rmp_img_url') . $postData['yp_id'] . '/' . $img);
                                        }
                                        if (file_exists($this->config->item('rmp_img_url_small') . $postData['yp_id'] . '/' . $img)) {
                                            unlink($this->config->item('rmp_img_url_small') . $postData['yp_id'] . '/' . $img);
                                        }
                                    }
                                }
                            }

                            if (!empty($_FILES[$filename]['name'][0])) {
                                //create dir and give permission

                                /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                createDirectory(array($this->config->item('rmp_base_url'), $this->config->item('rmp_base_big_url'), $this->config->item('rmp_base_big_url') . '/' . $postData['yp_id']));

                                $file_view = $this->config->item('rmp_img_url') . $postData['yp_id'];
                                //upload big image
                                $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/index/' . $postData['yp_id']);


                                //upload small image
                                $insertImagesData = array();
                                if (!empty($upload_data)) {
                                    foreach ($upload_data as $imageFiles) {
                                        /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                        createDirectory(array($this->config->item('rmp_base_small_url'), $this->config->item('rmp_base_small_url') . '/' . $postData['yp_id']));

                                        /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                        if ($imageFiles['is_image'])
                                            $a = do_resize($this->config->item('rmp_img_url') . $postData['yp_id'], $this->config->item('rmp_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                        array_push($insertImagesData, $imageFiles['file_name']);
                                        if (!empty($insertImagesData)) {
                                            $images = implode(',', $insertImagesData);
                                        }
                                    }
                                    if (!empty($ks_yp_data[0][$filename])) {
                                        $images .=',' . $ks_yp_data[0][$filename];
                                    }
                                    if (!empty($images)) {
                                        $data[$row['name']] = !empty($images) ? $images : '';
                                    }
                                }
                            } else {
                                if (!empty($ks_yp_data[0][$filename])) {
                                    $data[$row['name']] = !empty($ks_yp_data[0][$filename]) ? $ks_yp_data[0][$filename] : '';
                                }
                            }
                        } else {
                            if ($row['type'] != 'button') {
                                if (!empty($postData[$row['name']])) {
									if($row['type'] == 'date'){
                                        $data[$row['name']] = dateformat($postData[$row['name']]);                                    
                                    } elseif($row['subtype'] == 'time'){
                                        $data[$row['name']] = dbtimeformat($postData[$row['name']]);
                                    } else if ($row['type'] == 'checkbox-group') {
                                        $data[$row['name']] = !empty($postData[$row['name']]) ? implode(',', $postData[$row['name']]) : '';
                                    } elseif ($row['type'] == 'textarea' && $row['subtype'] == 'tinymce') {
                                        $data[$row['name']] = strip_slashes($postData[$row['name']]);
                                    } elseif ($row['type'] == 'select' && ($row['description'] == 'select_multiple_user' || $row['description'] == 'get_user')) {
                                        if (is_array($postData[$row['name']])) {
                                            $data[$row['name']] = implode(',', $postData[$row['name']]);
                                        } else {
                                            $data[$row['name']] = strip_slashes($postData[$row['name']]);
                                        }
                                    } else {
                                        $data[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                                    }
                                } else {
                                    $data[$row['name']] = !empty($postData[$row['name']]) ? $postData[$row['name']] : '';
                                }
                            }
                        }
                    }
                }
            }

            if (!empty($postData['rmp_id'])) {
                $data['draft'] = $draftdata;
                $data['rmp_id'] = $postData['rmp_id'];
                $data['yp_id'] = $postData['yp_id'];
                $data['modified_date'] = datetimeformat();
                $data['modified_time'] = date("H:i:s");
                
                $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
                $this->common_model->update(RMP, $data, array('rmp_id' => $postData['rmp_id']));
                //Insert log activity
                $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                    'module_name' => RMP_MODULE,
                    'module_field_name' => '',
                    'type' => 2
                );
                log_activity($activity);
            } else {
                if (!empty($data)) {
                    $data['draft'] = $draftdata;
                    $data['care_home_id'] = $postData['care_home_id'];
                    $data['yp_id'] = $postData['yp_id'];
                    $data['created_date'] = datetimeformat();
                    $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
					          $data['modified_time'] = date("H:i:s");

                    $this->common_model->insert(RMP, $data);
                    $data['rmp_id'] = $this->db->insert_id();
                    //Insert log activity
                    $activity = array(
                        'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                        'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                        'module_name' => RMP_MODULE,
                        'module_field_name' => '',
                        'type' => 1
                    );
                    log_activity($activity);
                }
            }
            if (!empty($data)) {
                redirect('/' . $this->viewname . '/save_rmp/' . $data['rmp_id'] . '/' . $data['yp_id']);
            } else {
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>Please  insert RMP details.</div>");
                if (!empty($is_copy)) {
                    redirect('/' . $this->viewname . '/copy/' . $is_copy . '/' . $postData['yp_id']);
                } else {
                    redirect('/' . $this->viewname . '/create/' . $postData['yp_id']);
                }
            }
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Save rmp form
      @Input    :
      @Output   :
      @Date   : 15/05/2018
     */
   public function save_rmp($rmp_id,$yp_id)
   {
    if(is_numeric($rmp_id) && is_numeric($yp_id))
    {
        //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($yp_id,$fields);
        //get Rmp yp data
        $match = array('rmp_id'=> $rmp_id);
        $data['edit_data'] = $this->common_model->get_records(RMP,array("*"), '', '', $match);
        
        //check data exist or not
        if(empty($data['YP_details']) || empty($data['edit_data']))
        {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson/view/'.$yp_id);
        }
        $data['yp_id'] = $yp_id;
        $data['rmp_id'] = $rmp_id;
        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['main_content'] = '/save_rmp';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
      else
      {
          show_404 ();
      }
   }
   
   /*
      @Author : Niral Patel
      @Desc   : View rmp form
      @Input    :
      @Output   :
      @Date   : 15/05/2018
     */
   public function view($rmp_id,$yp_id,$care_home_id=0,$past_care_id=0) {
    if(is_numeric($rmp_id) && is_numeric($yp_id))
    {
       //get RMP data
       $ks_forms = $this->getRmpFormData();
       if(!empty($ks_forms))
       {
            $data['ks_form_data'] = json_decode($ks_forms[0]['form_json_data'], TRUE);
       }
        // get rmp comments data
        $table = RMP_COMMENTS . ' as com';
        $where = array("com.rmp_id" => $rmp_id, "com.yp_id" => $yp_id);
        $fields = array("com.rmp_comments,com.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
        $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
        //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($yp_id,$fields);  
        //get rmp yp data
        $match = array('rmp_id'=> $rmp_id);
        $data['edit_data'] = $this->common_model->get_records(RMP,array("*"), '', '', $match);
        
        $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
        $table = RMP_SIGNOFF.' as ks';
        $where = array("l.is_delete"=> "0","ks.yp_id" => $yp_id,"ks.rmp_id"=>$rmp_id,"ks.is_delete"=> "0");
        $fields = array("ks.created_by,ks.created_date,ks.yp_id,ks.rmp_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id=ks.created_by');
        $group_by = array('created_by');
        $data['ks_signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);


        $table = RMP_SIGNOFF.' as ks';
        $where = array("ks.yp_id" => $yp_id,"ks.created_by" => $login_user_id,"ks.is_delete"=> "0","ks.rmp_id"=>$rmp_id);
        $fields = array("ks.*");
        $data['check_ks_signoff_data'] = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);
        
        $table = RMP_SIGNOFF_DETAILS;
        $fields = array('key_data,rmp_signoff_details_id,user_type,created_date');
        $where = array('rmp_id' => $rmp_id, 'yp_id' => $yp_id);
        $data['check_external_signoff_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
        //check data exist or not
        if(empty($data['YP_details']) || empty($data['edit_data']))
        {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson/view/'.$yp_id);
        }
        $data['ypid'] = $yp_id;
        $data['care_home_id'] = $care_home_id;
        $data['past_care_id'] = $past_care_id;
        $data['rmp_id'] = $rmp_id;
        $data['footerJs'][0] = base_url('uploads/custom/js/RiskManagementPlan/RiskManagementPlan.js');
        $data['crnt_view'] = $this->viewname;
        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['main_content'] = '/view';
        $this->parser->parse('layouts/DefaultTemplate', $data);
      }
      else
      {
          show_404 ();
      }
    }
     /*
      @Author : Niral Patel
      @Desc   : Read more
      @Input    : yp id
      @Output   :
      @Date   : 27/07/2017
     */
      public function readmore($id,$field)
      {
            $params['fields'] = [$field];
            $params['table'] = RMP;
            $params['match_and'] = 'rmp_id=' . $id . '';
            $data['documents'] = $this->common_model->get_records_array($params);
            $data['field'] = $field;
            $this->load->view($this->viewname . '/readmore', $data);
      }        
      /*
      @Author : Niral Patel
      @Desc   : Download PDF
      @Input    : yp id
      @Output   :
      @Date   : 27/07/2017
     */
   public function DownloadPDF($rmp_id,$yp_id) {
        $this->load->library('m_pdf');
        $data = [];
        //get RMP data
        $ks_forms = $this->getRmpFormData();
        if(!empty($ks_forms))
        {
            $data['form_data'] = json_decode($ks_forms[0]['form_json_data'], TRUE);
        }
        //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($yp_id,$fields);
        //get signoff data
        $table = RMP_SIGNOFF.' as ks';
        $where = array("l.is_delete"=> "0","ks.yp_id" => $yp_id,"ks.rmp_id"=>$rmp_id,"ks.is_delete"=> "0");
        $fields = array("ks.created_by,ks.created_date,ks.yp_id,ks.rmp_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id=ks.created_by');
        $group_by = array('created_by');
        $data['ks_signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);
        //get RMP yp data
        $match = array('yp_id'=> $yp_id,'rmp_id'=> $rmp_id);
        $join_tables = array(LOGIN . ' as l' => 'l.login_id=rmp.created_by');
        $data['edit_data'] = $this->common_model->get_records(RMP.' as rmp',array("rmp.*, CONCAT(`firstname`,' ', `lastname`) as name"), $join_tables, 'left', $match);
        $data['ypid'] = $yp_id;
        //new
        $pdfFileName = "riskmanagementplan.pdf";
        $PDFInformation['yp_details'] = $data['YP_details'][0];
        $PDFInformation['edit_data'] = $data['edit_data'];

        $PDFHeaderHTML  = $this->load->view('rmppdfHeader', $PDFInformation,true);
        $PDFFooterHTML  = $this->load->view('rmppdfFooter', $PDFInformation,true);
            
        //Set Header Footer and Content For PDF
        $this->m_pdf->pdf->mPDF('utf-8','A4','','','15','15','30','25');

        $this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
        $data['main_content'] = '/rmppdf';
        $html = $this->parser->parse('layouts/PdfDataTemplate', $data);

        /*remove*/
        $this->m_pdf->pdf->WriteHTML($html);
        //Store PDF in individual_strategies Folder
        $this->m_pdf->pdf->Output($pdfFileName, "D");
    } 

    /*
      @Author : Niral Patel
      @Desc   : Signoff data
      @Input    : yp id
      @Output   :
      @Date   : 27/07/2017
    */
    public function manager_review($yp_id,$rmp_id) {
     if (!empty($yp_id) && !empty($rmp_id)) {
          //get YP information
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($yp_id,$fields);

          $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
          $match = array('yp_id'=> $yp_id,'rmp_id'=>$rmp_id,'created_by'=>$login_user_id,'is_delete'=> '0');
          $check_signoff_data = $this->common_model->get_records(RMP_SIGNOFF,array("*"), '', '', $match);
          if(empty($check_signoff_data) > 0){
          $update_pre_data['rmp_id'] = $rmp_id;
          $update_pre_data['care_home_id'] = $data['YP_details'][0]['care_home'];
          $update_pre_data['yp_id'] = $yp_id;
          $update_pre_data['created_date'] = datetimeformat();
          $update_pre_data['created_by'] = $login_user_id;
        if ($this->common_model->insert(RMP_SIGNOFF,$update_pre_data)) {
          $msg = $this->lang->line('successfully_rmp_review');
          $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {
          // error
          $msg = $this->lang->line('error_msg');
          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }
      }else{
        $msg = $this->lang->line('already_rmp_review');
        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
      }
    }else{      
      $msg = $this->lang->line('error_msg');
      $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
    }
    redirect('/' . $this->viewname .'/view/'.$rmp_id.'/'.$yp_id);
  }
/*
      @Author : Ritesh Ranna
      @Desc   : add commnts
      @Input  : 
      @Output : 
      @Date   : 13/06/2017
     */
 public function add_commnts() {
        $main_user_data = $this->session->userdata('LOGGED_IN');
        $rmp_id = $this->input->post('rmp_id');
        $yp_id = $this->input->post('yp_id');
        
        $data = array(
                'rmp_comments' => $this->input->post('rmp_comments'),
                'yp_id' => $this->input->post('yp_id'),
                'rmp_id' => $this->input->post('rmp_id'),
                'created_date' => datetimeformat(),
                'created_by' => $main_user_data['ID'],
            );  
            //Insert Record in Database
            if ($this->common_model->insert(RMP_COMMENTS, $data)) {
                $msg = $this->lang->line('comments_add_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            } else {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
         redirect('/' . $this->viewname . '/view/' . $rmp_id . '/' . $yp_id);
    }

/*
      @Author : Ritesh Ranna
      @Desc   : User List Delete Query
      @Input  : Post id from List page
      @Output : Delete data from database and redirect
      @Date   : 13/06/2017
     */

    public function deletedata($rmp_id,$yp_id) {
                //Delete Record From Database
                if (!empty($rmp_id) && !empty($yp_id)) {
                    $data = array('is_delete' => 1);
                    $where = array('rmp_id' => $rmp_id , 'yp_id' => $yp_id);

                    if ($this->common_model->update(RMP, $data, $where)) {
                      //Insert log activity
                          $activity = array(
                            'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
                            'module_name'         => RMP_MODULE,
                            'module_field_name'   => '',
                            'yp_id'   => $yp_id,
                            'type'                => 3
                          );
                        log_activity($activity);
                        $msg = $this->lang->line('rmp_delete_msg');
                        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                        unset($rmp_id);
                    } else {
                        // error
                        $msg = $this->lang->line('error_msg');
                        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                        redirect('/' . $this->viewname . '/index/' . $yp_id);
                    }
                }
        redirect('/' . $this->viewname . '/index/' . $yp_id);
    }

/*
      @Author : Ritesh Rana
      @Desc   : sign off functionality
      @Input    :
      @Output   :
      @Date   : 15/05/2018
     */
public function signoff($yp_id = '',$rmp_id = '') {
        $this->formValidation();
      $main_user_data = $this->session->userdata('LOGGED_IN');
        if ($this->form_validation->run() == FALSE) {
          $data['footerJs'][0] = base_url('uploads/custom/js/RiskManagementPlan/RiskManagementPlan.js');
          $data['crnt_view'] = $this->viewname;
        //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($yp_id,$fields);

        $data['ypid']= $yp_id;
        $data['rmp_id']= $rmp_id;
        //get yp social info
          $data['social_worker_data'] = SocialWorkerData($yp_id);
        //get yp parent info
          $data['parent_data'] = ParentData($yp_id);

          $data['form_action_path'] = $this->viewname . '/signoff';
          $data['header'] = array('menu_module' => 'YoungPerson');
          $data['validation'] = validation_errors();
          if (!empty($data['validation'])) {
              $data['main_content'] = '/signoff';
              $this->parser->parse('layouts/DefaultTemplate', $data);
            } else {
              $this->load->view('/signoff', $data);
            }
        } else {
            $this->insertdata();
        }
    }
/*
      @Author : Ritesh Rana
      @Desc   : insert sign off functionality
      @Input    :
      @Output   :
      @Date   : 15/05/2018
     */
    public function insertdata() {
        $postdata = $this->input->post();
        $ypid = $postdata['ypid'];
        $rmp_id = $postdata['rmp_id'];
        $user_type = $postdata['user_type'];
        if($user_type == 'parent')
        {
          $parent_data['firstname'] = strip_tags($postdata['fname']);
          $parent_data['surname'] = strip_tags($postdata['lname']);
          $parent_data['relationship'] = strip_tags($postdata['relationship']);
          $parent_data['address'] = strip_tags($postdata['address']);
          $parent_data['contact_number'] = strip_tags($postdata['contact_number']);
          $parent_data['email_address'] = strip_tags($postdata['email']);
          $parent_data['yp_authorised_communication'] = strip_tags($postdata['yp_authorised_communication']);
          $parent_data['carer_authorised_communication'] = strip_tags($postdata['carer_authorised_communication']);
          $parent_data['yp_authorised_communication'] = strip_tags($postdata['yp_authorised_communication']);
          $parent_data['comments'] = strip_tags($postdata['comments']);
          $parent_data['created_date'] = datetimeformat();
          $parent_data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
          $parent_data['yp_id'] = $this->input->post('ypid');
          $parent_data['care_home_id'] = $this->input->post('care_home_id');
          $success = $this->common_model->insert(PARENT_CARER_INFORMATION, $parent_data);
          //Insert log activity
          $activity = array(
              'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
              'yp_id' => !empty($postdata['ypid']) ? $postdata['ypid'] : '',
              'module_name' => PARENT_CARER_DETAILS_YP,
              'module_field_name' => '',
              'type' => 1
          );
          log_activity($activity);
        }
            //Current Login detail
        $main_user_data = $this->session->userdata('LOGGED_IN');
        
            if (!validateFormSecret()) {
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
                redirect('YoungPerson/view/'.$ypid);
            }
            $data['crnt_view'] = $this->viewname;
			
			//get RMP data
      $formsdata = $this->getRmpFormData();
      //get pp yp data
			$match = array('yp_id'=> $ypid,'rmp_id'=>$rmp_id);
			$ks_yp_data = $this->common_model->get_records(RMP,array("*"), '', '', $match);

      if(!empty($formsdata) && !empty($ks_yp_data))
			 {
				  $ks_form_data = json_decode($formsdata[0]['form_json_data'], TRUE);
				  $data = array();
				  $i=0;
				  foreach ($ks_form_data as $row) {
					  if(isset($row['name']))
					  {
						  if($row['type'] != 'button')
						  {
							  if($row['type'] == 'checkbox-group')
							  {
								  $ks_form_data[$i]['value'] = implode(',',$ks_yp_data[0][$row['name']]);
								}else if($row['type'] == 'select')
                {
                 $ks_form_data[$i]['values'] = $ks_yp_data[0][$row['name']];
                }else{
								  //$ks_form_data[$i]['value'] = str_replace("'", '"', $ks_yp_data[0][$row['name']]);
                  $ks_form_data[$i]['value'] = strip_slashes($ks_yp_data[0][$row['name']]);
							  }
						  }
					  }
					  $i++;
				  }
			
				 }
				 
            $data = array(
                'user_type' => ucfirst($postdata['user_type']),
                'yp_id' => ucfirst($postdata['ypid']),
                'care_home_id' => $postdata['care_home_id'],
                'rmp_id' => ucfirst($postdata['rmp_id']),
				        'form_json_data' =>json_encode($ks_form_data, TRUE),
                'fname' => ucfirst($postdata['fname']),
                'lname' => ucfirst($postdata['lname']),
                'email' => $postdata['email'],
                'key_data' => md5($postdata['email']),
                'created_date' => datetimeformat(),
                'created_by' => $main_user_data['ID'],
                'updated_by' => $main_user_data['ID'],
            );
            //Insert Record in Database
            if ($this->common_model->insert(RMP_SIGNOFF_DETAILS, $data)) {

                $signoff_id = $this->db->insert_id();

				$table = RMP_SIGNOFF;
				$where = array("yp_id" => $ypid,"rmp_id" => $rmp_id,"is_delete"=> "0");
				$fields = array("created_by,yp_id,rmp_id,created_date");
				$group_by = array('created_by');
				$signoff_data = $this->common_model->get_records($table,$fields,'','','','','','','','',$group_by,$where);
			  
				if(!empty($signoff_data)){
					foreach ($signoff_data as $archive_value) {
						$update_arc_data['approval_rmp_id'] = $signoff_id;
						$update_arc_data['yp_id'] = $archive_value['yp_id'];
						$update_arc_data['created_date'] = $archive_value['created_date'];
						$update_arc_data['created_by'] = $archive_value['created_by'];
						$this->common_model->insert(NFC_APPROVAL_RMP_SIGNOFF,$update_arc_data);
					}
				}
			          $this->sendMailToRelation($data, $signoff_id); // send mail
                $msg = $this->lang->line('successfully_sign_off');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            } else {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
        redirect('RiskManagementPlan/view/'.$rmp_id.'/'.$ypid);
    }

/*
      @Author : Ritesh Rana
      @Desc   : send Mail To Relation functionality
      @Input    :
      @Output   :
      @Date   : 15/05/2018
     */
    private function sendMailToRelation($data = array(), $signoff_id) {

        if (!empty($data)) {
            if(!empty($data['yp_id']))
            {
                $match = array("yp_id" => $data['yp_id']);
                $fields = array("concat(yp_fname,' ',yp_lname) as yp_name");
                $YP_details = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
                
            }
            $yp_name = !empty($YP_details[0]['yp_name'])?$YP_details[0]['yp_name']:"";
            /* Send Created Customer password with Link */
            $toEmailId = $data['email'];
            $customerName = $data['fname'] . ' ' . $data['lname'];

            $email = md5($toEmailId);
            $loginLink = base_url('RiskManagementPlan/signoffData/' . $data['yp_id'] . '/' . $signoff_id . '/' . $email);

            $find = array('{NAME}','{LINK}');

            $replace = array(
                'NAME' => $customerName,
                'LINK' => $loginLink,
            );
            
            $emailSubject = 'Welcome to NFCTracker';
                    $emailBody = '<div>'
                    . '<p>Hello {NAME} ,</p> '
                    . '<p>Please find Risk Management Plan for '.$yp_name.' for your approval.</p> '
                    . "<p>For security purposes, Please do not forward this email on to any other person. It is for the recipient only and if this is sent in error please advise itsupport@newforestcare.co.uk and delete this email. This link is only valid for ".REPORT_EXPAIRED_HOUR.", should this not be signed off within ".REPORT_EXPAIRED_HOUR." of recieving then please request again</p>"
                    . '<p> <a href="{LINK}">click here</a> </p> '
                    . '<div>';       

            $finalEmailBody = str_replace($find,$replace,$emailBody);

            return $this->common_model->sendEmail($toEmailId, $emailSubject, $finalEmailBody, FROM_EMAIL_ID);
        }
        return true;
    }
    /*
      @Author : Ritesh Rana
      @Desc   : sign off formValidation
      @Input  :
      @Output :
      @Date   : 28/03/2018
     */
     public function formValidation($id = null) {
        $this->form_validation->set_rules('fname', 'Firstname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('lname', 'Lastname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
        
    }
    /*
      @Author : Ritesh Rana
      @Desc   : view Send for Signoff / Approval data
      @Input  :
      @Output :
      @Date   : 28/03/2018
     */
     public function signoffData($yp_id,$id,$email) {

     if(is_numeric($id) && is_numeric($yp_id) && !empty($email))
       {
        $match = array('yp_id'=> $yp_id,'rmp_signoff_details_id'=>$id,'key_data'=> $email,'status'=>'inactive');
        $check_signoff_data = $this->common_model->get_records(RMP_SIGNOFF_DETAILS,array("created_date,form_json_data,rmp_id"), '', '', $match);
        if(!empty($check_signoff_data)){
          $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'].REPORT_EXPAIRED_DAYS));
          if(strtotime(datetimeformat()) <= strtotime($expairedDate))
          {
              $data['form_data'] = json_decode($check_signoff_data[0]['form_json_data'], TRUE);
              //get YP information
              $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
              $data['YP_details'] = YpDetails($yp_id,$fields);
              if(empty($data['YP_details']))
              {
                  $msg = $this->lang->line('common_no_record_found');
                  $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                  redirect('YoungPerson/view/'.$ypid);
              }
              $table = NFC_APPROVAL_RMP_SIGNOFF.' as ras';
              $where = array("l.is_delete"=> "0","ras.yp_id" => $yp_id,"ras.is_delete"=> "0","approval_rmp_id"=>$id);
              $fields = array("ras.created_by,ras.created_date,ras.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
              $join_tables = array(LOGIN . ' as l' => 'l.login_id=ras.created_by');
              $group_by = array('created_by');
              $data['signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);
             
              // get rmp comments data
              $table = RMP_COMMENTS . ' as com';
              $where = array("com.rmp_id" => $check_signoff_data[0]['rmp_id'], "com.yp_id" => $yp_id);
              $fields = array("com.rmp_comments,com.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
              $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
              $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);

              $data['key_data']= $email;
              $data['ypid'] = $yp_id;
              $data['rmp_id'] = $check_signoff_data[0]['rmp_id'];
              $data['signoff_id'] = $id;
              $data['footerJs'][0] = base_url('uploads/custom/js/RiskManagementPlan/RiskManagementPlan.js');
              $data['crnt_view'] = $this->viewname;
              $data['header'] = array('menu_module' => 'YoungPerson');
              $data['main_content'] = '/signoff_view';
              $this->parser->parse('layouts/DefaultTemplate', $data);
          }
          else
          {
            $msg = lang('link_expired');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>")
            ;
            $this->load->view('successfully_message');
          }
      }else{
         
		 $msg = $this->lang->line('already_rmp_review');
      $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
 $this->load->view('successfully_message');
      }
      }
      else
      {
          show_404 ();
      }
    } 

/*
      @Author : Ritesh Rana
      @Desc   : Review for Signoff / Approval functionality
      @Input    :
      @Output   :
      @Date   : 17/07/2017
     */
public function signoff_review_data($yp_id,$rmp_id,$email) {
    if (!empty($yp_id) && !empty($rmp_id) && !empty($email)) {
          
        $match = array('yp_id'=> $yp_id,'rmp_signoff_details_id'=>$rmp_id,'key_data'=> $email,'status'=>'inactive');
        $check_signoff_data = $this->common_model->get_records(RMP_SIGNOFF_DETAILS,array("key_data,form_json_data,created_date"), '', '', $match);
		    if(!empty($check_signoff_data)){ 
          $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'].REPORT_EXPAIRED_DAYS));
          if(strtotime(datetimeformat()) <= strtotime($expairedDate))
          {
            $u_data['status'] = 'active';
            $u_data['modified_date'] = datetimeformat();
            $success =$this->common_model->update(RMP_SIGNOFF_DETAILS,$u_data,array('rmp_signoff_details_id'=> $rmp_id,'yp_id'=> $yp_id,'key_data'=> $email));
            if ($success) {

            $msg = $this->lang->line('successfully_rmp_review');
            $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-success text-center'>$msg</div>");
            } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
          }
          else
          {
            $msg = lang('link_expired');
            $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>")
            ;
          }
      }else{
        $msg = $this->lang->line('already_rmp_review');
      $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>");

      }
    }else{      
      $msg = $this->lang->line('error_msg');
      $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>");
      
    }
  	$this->load->view('successfully_message');
  }
  /*
      @Author : Niral Patel
      @Desc   : get user type detail
      @Input  :
      @Output :
      @Date   : 28/03/2018
     */
    public function getUserTypeDetail() {
      $postdata  = $this->input->post();
      $user_type = !empty($postdata['user_type'])?$postdata['user_type']:''; 
      $id = $postdata['id']; 
      $ypid      = $postdata['ypid']; 
      $table = YP_DETAILS . ' as yp';
      $match = "yp.yp_id = " . $ypid;
      $fields = array("yp.*,swd.social_worker_firstname,swd.social_worker_surname,swd.landline,swd.mobile,swd.email,swd.senior_social_worker_firstname,swd.senior_social_worker_surname,oyp.pen_portrait,swd.other,pc.firstname,pc.surname,pc.relationship,pc.address,pc.contact_number,pc.email_address,pc.yp_authorised_communication,pc.carer_authorised_communication,pc.comments");
      $join_tables = array(SOCIAL_WORKER_DETAILS . ' as swd' => 'swd.yp_id= yp.yp_id', PARENT_CARER_INFORMATION . ' as pc' => 'pc.yp_id= yp.yp_id', OVERVIEW_OF_YOUNG_PERSON . ' as oyp' => 'oyp.yp_id= yp.yp_id');

      $data['editRecord'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
      if($user_type == 'parent_data')
      {
          //get parent info
          $table = PARENT_CARER_INFORMATION . ' as pc';
          $match = array("pc.parent_carer_id" => $id,'pc.is_deleted' => 0);
          $fields = array("pc.*");
          $data['parent_data'] = $this->common_model->get_records($table, $fields, '', '', $match);
          $data['editRecord'][0]['fname'] = $data['editRecord'][0]['firstname'];
          $data['editRecord'][0]['lname'] = $data['editRecord'][0]['surname'];
          $data['editRecord'][0]['email_id'] = $data['editRecord'][0]['email_address'];
      }
      else if($user_type == 'social_data')
      {
          //get social info
          $table = SOCIAL_WORKER_DETAILS . ' as sw';
          $match = array("sw.social_worker_id" => $id);
          $fields = array("sw.*");
          $data['social_worker_data'] = $this->common_model->get_records($table, $fields, '', '', $match);
          $data['editRecord'][0]['fname'] = $data['editRecord'][0]['social_worker_firstname'];
          $data['editRecord'][0]['lname'] = $data['editRecord'][0]['social_worker_surname'];
          $data['editRecord'][0]['email_id'] = $data['editRecord'][0]['email'];
      }
      $data['user_type'] = $user_type;
      $this->load->view($this->viewname . '/signoff_ajax', $data);
    }
/*
      @Author : Niral Patel
      @Desc   : view ks data
      @Input  :
      @Output :
      @Date   : 12/04/2018
     */
    public function external_approval_list($ypid,$rmp_id,$care_home_id=0,$past_care_id=0) {
   /*
                Ritesh Rana
                for past care id inserted for archive full functionality
                */
        if ($past_care_id !== 0) {
            $temp = $this->common_model->get_records(PAST_CARE_HOME_INFO, array('move_date'), '', '', array("yp_id" => $ypid, "past_carehome" => $care_home_id));
            $data_care_home_detail = $this->common_model->get_records(PAST_CARE_HOME_INFO, array("enter_date,move_date"), '', '', array("yp_id" => $ypid, "move_date <= " => $temp[0]['move_date']));
            $created_date = $movedate = '';
            $count_care = count($data_care_home_detail);
            if ($count_care >= 1) {
                $created_date = $data_care_home_detail[0]['enter_date'];
                $movedate = $data_care_home_detail[$count_care - 1]['move_date'];
            } elseif ($count_care == 0) {
                $created_date = $data_care_home_detail[0]['enter_date'];
                $movedate = $data_care_home_detail[0]['move_date'];
            }
        }

        if(is_numeric($ypid) && is_numeric($rmp_id)){
        $match = "yp_id = " . $ypid;

        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($ypid,$fields);
        if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$ypid);
          }
        $searchtext = $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('rmp_approval_session_data');
        }
        $searchsort_session = $this->session->userdata('rmp_approval_session_data');
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
                $sortfield = 'rmp_signoff_details_id';
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
        if($past_care_id == 0){
          $config['base_url'] = base_url() . $this->viewname . '/external_approval_list/'.$ypid.'/'.$rmp_id;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 5;
            $uri_segment = $this->uri->segment(5);
        }
        //Query

        $table = RMP_SIGNOFF_DETAILS . ' as ks';
        $where = array("ks.yp_id"=>$ypid,"ks.rmp_id"=>$rmp_id);
        $fields = array("ks.status,ks.modified_date,ks.rmp_signoff_details_id,ks.yp_id,ks.user_type,ks.fname,ks.lname,ks.created_date,CONCAT(`firstname`,' ', `lastname`) as create_name ,CONCAT(`fname`,' ', `lname`) as user_name,ch.care_home_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id = ks.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = ks.care_home_id');

        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);

            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
        }
        }else{
          $config['base_url'] = base_url() . $this->viewname . '/external_approval_list/'.$ypid.'/'.$rmp_id.'/'.$care_home_id.'/'.$past_care_id;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 7;
            $uri_segment = $this->uri->segment(7);
        }
        //Query

        $table = RMP_SIGNOFF_DETAILS . ' as ks';
        $where = array("ks.yp_id"=>$ypid,"ks.rmp_id"=>$rmp_id);
        $fields = array("ks.status,ks.modified_date,ks.rmp_signoff_details_id,ks.yp_id,ks.user_type,ks.fname,ks.lname,ks.created_date,CONCAT(`firstname`,' ', `lastname`) as create_name ,CONCAT(`fname`,' ', `lname`) as user_name,ch.care_home_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id = ks.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = ks.care_home_id');
        $where_date = "ks.created_date BETWEEN  '".$created_date."' AND '".$movedate."'";
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where,'','','','','',$where_date);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1','','',$where_date);
        }
      }

        $data['ypid'] = $ypid;
        $data['rmp_id'] = $rmp_id;
        $data['care_home_id'] = $care_home_id;
        $data['past_care_id'] = $past_care_id;
        
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

        $this->session->set_userdata('rmp_approval_session_data', $sortsearchpage_data);
        setActiveSession('rmp_approval_session_data'); // set current Session active
        $data['header'] = array('menu_module' => 'Communication');
       
        //get communication form
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/RiskManagementPlan/RiskManagementPlan.js');
        $data['header'] = array('menu_module' => 'YoungPerson');

        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/approval_ajaxlist', $data);
        } else {
            $data['main_content'] = '/ks_list';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
        }else{
              show_404 ();
        } 
    }

      /*
      @Author : Niral Patel
      @Desc   : view Approval RMP
      @Input  :
      @Output :
      @Date   : 12/04/2018
      */
public function viewApprovalRMP($id,$ypid,$care_home_id=0,$past_care_id=0)                         
    {
      if(is_numeric($id) && is_numeric($ypid))
       {
         //get archive pp data
         $match = array('rmp_signoff_details_id'=> $id);
         $formsdata = $this->common_model->get_records(RMP_SIGNOFF_DETAILS,array("form_json_data,rmp_id"), '', '', $match);
         $data['formsdata'] = $formsdata;
         if(!empty($formsdata))
         {
              $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
         }
          //get YP information
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($ypid,$fields);
          
          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$ypid);
          }
              $table = NFC_APPROVAL_RMP_SIGNOFF.' as aks';
              $where = array("l.is_delete"=> "0","aks.yp_id" => $ypid,"aks.is_delete"=> "0","approval_rmp_id"=>$id);
              $fields = array("aks.created_by,aks.created_date,aks.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
              $join_tables = array(LOGIN . ' as l' => 'l.login_id=aks.created_by');
              $group_by = array('created_by');
              $data['signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);
              
              $table = RMP_COMMENTS . ' as com';
              $where = array("com.rmp_id" => $formsdata[0]['rmp_id'], "com.yp_id" => $ypid);
              $fields = array("com.rmp_comments,com.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
              $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
              $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);    

          $data['ypid'] = $ypid;
          $data['care_home_id'] = $care_home_id;
          $data['past_care_id'] = $past_care_id;
          $data['rmp_id'] = $formsdata[0]['rmp_id'];

          $data['footerJs'][0] = base_url('uploads/custom/js/RiskManagementPlan/RiskManagementPlan.js');
          $data['crnt_view'] = $this->viewname;
          $data['header'] = array('menu_module' => 'YoungPerson');
          $data['main_content'] = '/approval_view';
          $this->parser->parse('layouts/DefaultTemplate', $data);
        }
        else
        {
            show_404 ();
        }
    }
    /*
      @Author : Niral Patel
      @Desc   : external approve view data
      @Input  :
      @Output :
      @Date   : 12/04/2018
     */
    
     public function resend_external_approval($signoff_id,$ypid,$ksid) {
      $match = array('rmp_signoff_details_id'=>$signoff_id);
      $signoff_data = $this->common_model->get_records(RMP_SIGNOFF_DETAILS,array("yp_id,fname,lname,email"), '', '', $match);
      if(!empty($signoff_data))
      {
        $data = array(
                'yp_id' => ucfirst($signoff_data[0]['yp_id']),
                'fname' => ucfirst($signoff_data[0]['fname']),
                'lname' => ucfirst($signoff_data[0]['lname']),
                'email' => $signoff_data[0]['email']
            );
        $sent = $this->sendMailToRelation($data,$signoff_id); // send mail
        if($sent)
        {
          $u_data['created_date'] = datetimeformat();
          $u_data['modified_date'] = NULL;
          $success =$this->common_model->update(RMP_SIGNOFF_DETAILS,$u_data,array('rmp_signoff_details_id'=> $signoff_id));
          $msg = $this->lang->line('mail_sent_successfully');
          $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
        else
        {
          $msg = $this->lang->line('error');
          $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
      }

      redirect($this->viewname.'/external_approval_list/' . $ypid.'/'.$ksid);
     }

     /*
      @Author : Niral Patel
      @Desc   : external approvel DownloadPDF after mail
      @Input  :
      @Output :
      @Date   : 12/04/2018
     */
	 public function DownloadPDF_after_mail($yp_id,$id) {
		 $this->load->library('m_pdf');
		 if(is_numeric($id) && is_numeric($yp_id))
       {
          $match = array('yp_id'=> $yp_id,'rmp_signoff_details_id'=>$id,'status'=>'inactive');
          $check_signoff_data = $this->common_model->get_records(RMP_SIGNOFF_DETAILS,array("created_date,rmp_id,form_json_data,modified_date"), '', '', $match);

        if(!empty($check_signoff_data)){
          $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'].REPORT_EXPAIRED_DAYS));
          if(strtotime(datetimeformat()) <= strtotime($expairedDate))
          {
              $data['form_data'] = json_decode($check_signoff_data[0]['form_json_data'], TRUE);
			    //get YP information
              $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
              $data['YP_details'] = YpDetails($yp_id,$fields);
              if(empty($data['YP_details']))
              {
                  $msg = $this->lang->line('common_no_record_found');
                  $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                  redirect('YoungPerson/view/'.$ypid);
              }
              $table = NFC_APPROVAL_RMP_SIGNOFF.' as ras';
              $where = array("l.is_delete"=> "0","ras.yp_id" => $yp_id,"ras.is_delete"=> "0","approval_rmp_id"=>$id);
              $fields = array("ras.created_by,ras.created_date,ras.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
              $join_tables = array(LOGIN . ' as l' => 'l.login_id=ras.created_by');
              $group_by = array('created_by');
              $data['signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);
             
              // get rmp comments data
              $table = RMP_COMMENTS . ' as com';
              $where = array("com.rmp_id" => $check_signoff_data[0]['rmp_id'], "com.yp_id" => $yp_id);
              $fields = array("com.rmp_comments,com.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
              $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
              $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
			        $data['key_data']= $email;
              $data['ypid'] = $yp_id;
              $data['rmp_id'] = $check_signoff_data[0]['rmp_id'];
              $data['signoff_id'] = $id;
              $data['footerJs'][0] = base_url('uploads/custom/js/RiskManagementPlan/RiskManagementPlan.js');
              $data['crnt_view'] = $this->viewname;
             $pdfFileName = "riskmanagement_pdf.pdf";
            $PDFInformation['yp_details'] = $data['YP_details'][0];
            $PDFInformation['edit_data'] = $check_signoff_data[0]['modified_date'];

			      $PDFHeaderHTML  = $this->load->view('riskmanagement_plan_pdfHeader', $PDFInformation,true);
			      $PDFFooterHTML  = $this->load->view('riskmanagement_plan_pdfFooter', $PDFInformation,true);
			 
            //Set Header Footer and Content For PDF
            $this->m_pdf->pdf->mPDF('utf-8','A4','','','10','10','45','25');
            $this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
            $this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);                    
            $data['main_content'] = '/riskmanagement_pdf';
			       $html = $this->parser->parse('layouts/PdfDataTemplate', $data);
			       $html=utf8_decode($html);
			     
           $this->m_pdf->pdf->WriteHTML($html);
            //Store PDF in individual_strategies Folder
            $this->m_pdf->pdf->Output($pdfFileName, "D");
          }
          else
          {
            $msg = lang('link_expired');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>")
            ;
            $this->load->view('successfully_message');
          }
      }else{
        $msg = $this->lang->line('already_rmp_review');
        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        $this->load->view('successfully_message');
      }
      }
      else
      {
          show_404 ();
      }
	 }


   /*
      @Author : Ritesh Rana
      @Desc   : get Rmp form data
      @Input  :
      @Output :
      @Date   : 12/04/2018
     */
   public function getRmpFormData(){
        $match = array('rmp_form_id'=> 1);
        $ks_forms = $this->common_model->get_records(RMP_FORM,array("form_json_data"), '', '', $match);
        return $ks_forms;
   }
}
