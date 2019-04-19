<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class LocationRegister extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->viewname = $this->router->fetch_class ();
        $this->method   = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session','m_pdf'));
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Location Register Index Page
      @Input  : yp_id , care_home_id,past_care_id
      @Output :
      @Date   : 26/09/2018
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
            $this->session->unset_userdata('ls_data');
        }
        
        $searchsort_session = $this->session->userdata('ls_data');
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
                $sortfield = 'location_register_id';
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

      /* condition added by Ritesh Ranan on 27/09/2018 to archive functionality */
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
        
        $table = LOCATION_REGISTER.' as lr';
        $where = array("lr.yp_id"=> $id,"lr.is_delete"=> 0);
        $fields = array("l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname,ch.care_home_name, lr.*");

        $join_tables = array(LOGIN . ' as l' => 'l.login_id = lr.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = lr.care_home_id');

        if(!empty($searchtext))
        {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search='((CONCAT(`firstname`, \' \', `lastname`) LIKE "%'.$searchtext.'%" OR l.firstname LIKE "%'.$searchtext.'%" OR l.lastname LIKE "%'.$searchtext.'%" OR lr.date LIKE "%'.$searchtext.'%" OR lr.time LIKE "%'.$searchtext.'%" OR l.status LIKE "%'.$searchtext.'%")AND l.is_delete = "0")';

            $data['edit_data']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where_search);

            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,'','',$sortfield,$sortby,'',$where_search,'','','1');
        }
        else
        {
            $data['edit_data']      = $this->common_model->get_records($table,$fields,$join_tables,'left','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where);
          
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
        
        $table = LOCATION_REGISTER.' as lr';
        $where = array("lr.yp_id"=> $id,"lr.is_delete"=> 0);
        $fields = array("l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname,ch.care_home_name, lr.*");

        $join_tables = array(LOGIN . ' as l' => 'l.login_id = lr.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = lr.care_home_id');
        $where_date = "lr.created_date BETWEEN  '".$created_date."' AND '".$movedate."'";
        if(!empty($searchtext))
        {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search='((CONCAT(`firstname`, \' \', `lastname`) LIKE "%'.$searchtext.'%" OR l.firstname LIKE "%'.$searchtext.'%" OR l.lastname LIKE "%'.$searchtext.'%" OR lr.date LIKE "%'.$searchtext.'%" OR lr.time LIKE "%'.$searchtext.'%" OR l.status LIKE "%'.$searchtext.'%")AND l.is_delete = "0")';

            $data['edit_data']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where_search,'','','','','',$where_date);

            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,'','',$sortfield,$sortby,'',$where_search,'','','1','','',$where_date);
        }
        else
        {
            $data['edit_data']      = $this->common_model->get_records($table,$fields,$join_tables,'left','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where,'','','','','',$where_date);
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
         /* added by Ritesh Ranan on 27/09/2018 to archive functionality */
        $data['care_home_id'] = $care_home_id;
        $data['past_care_id'] = $past_care_id;
        //get lr form
        $match = array('lr_form_id'=> 1);
        $lr_forms = $this->common_model->get_records(LR_FORM,array("form_json_data"), '', '', $match);
        if(!empty($lr_forms))
        {
            $data['form_data'] = json_decode($lr_forms[0]['form_json_data'], TRUE);
        }
        $this->session->set_userdata('ls_data', $sortsearchpage_data);
        setActiveSession('ls_data'); // set current Session active
        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/locationregister/locationregister.js');
        if($this->input->post('result_type') == 'ajax'){
            $this->load->view($this->viewname . '/ajaxlist', $data);
        } else {
            $data['main_content'] = '/locationregister';
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
      @Desc   : create yp edit page
      @Input  :
      @Output :
      @Date   : 13/07/2017
     */

    public function create($id) {
      if(is_numeric($id))
      {
       //get ks form
       $match = array('lr_form_id'=> 1);
       $ks_forms = $this->common_model->get_records(LR_FORM,array("form_json_data"), '', '', $match);
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
         /* added by Ritesh Ranan on 27/09/2018 to archive functionality */
        $data['care_home_id'] = $data['YP_details'][0]['care_home'];

        $data['footerJs'][0] = base_url('uploads/custom/js/locationregister/locationregister.js');
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


    

public function edit_draft($location_register_id,$yp_id) {
    if(is_numeric($location_register_id) && is_numeric($yp_id))
    {
       //get lr form
       $match = array('lr_form_id'=> 1);
       $ks_forms = $this->common_model->get_records(LR_FORM,array("form_json_data"), '', '', $match);
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
        //get lr yp data
        $match = array('location_register_id'=> $location_register_id);
        $data['edit_data'] = $this->common_model->get_records(LOCATION_REGISTER,'', '', '', $match);
        //check data exist or not
        if(empty($data['YP_details']) || empty($data['edit_data']))
        {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson/view/'.$yp_id);
        }
        $data['ypid'] = $yp_id;
        $data['location_register_id'] = $location_register_id;
        $data['footerJs'][0] = base_url('uploads/custom/js/locationregister/locationregister.js');
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
      @Author : Ritesh Rana
      @Desc   : Insert LocationRegister form
      @Input    :
      @Output   :
      @Date   : 02/05/2018
     */
    public function insert() {
        if (!validateFormSecret()) {
            redirect($_SERVER['HTTP_REFERER']);  //Redirect On Listing page
        }
        $postData = $this->input->post();
        unset($postData['submit_lrform']);
        //get lr form
        $match = array('lr_form_id' => 1);
        $ks_forms = $this->common_model->get_records(LR_FORM, array("form_json_data"), '', '', $match);
        if (!empty($ks_forms)) {
            $ks_form_data = json_decode($ks_forms[0]['form_json_data'], TRUE);
            $data = array();
            foreach ($ks_form_data as $row){
                if (isset($row['name'])) {
                    if ($row['type'] == 'file') {
                        $filename = $row['name'];
                        //get image previous image
                        $match = array('location_register_id' => $postData['location_register_id'], 'yp_id' => $postData['yp_id']);
                        $ks_yp_data = $this->common_model->get_records(LOCATION_REGISTER, array('`' . $row['name'] . '`'), '', '', $match);
                        //delete img
                        if (!empty($postData['hidden_' . $row['name']])) {
                            $delete_img = explode(',', $postData['hidden_' . $row['name']]);
                            $db_images = explode(',', $ks_yp_data[0][$filename]);
                            $differentedImage = array_diff($db_images, $delete_img);
                            $ks_yp_data[0][$filename] = !empty($differentedImage) ? implode(',', $differentedImage) : '';
                            if (!empty($delete_img)) {
                                foreach ($delete_img as $img) {
                                    if (file_exists($this->config->item('lr_img_url') . $postData['yp_id'] . '/' . $img)) {
                                        unlink($this->config->item('lr_img_url') . $postData['yp_id'] . '/' . $img);
                                    }
                                    if (file_exists($this->config->item('lr_img_url_small') . $postData['yp_id'] . '/' . $img)) {
                                        unlink($this->config->item('lr_img_url_small') . $postData['yp_id'] . '/' . $img);
                                    }
                                }
                            }
                        }

                        if (!empty($_FILES[$filename]['name'][0])) {
                            //create dir and give permission
                            /* common function replaced by Dhara Bhalala on 29/09/2018 */
                            createDirectory(array($this->config->item('lr_base_url'), $this->config->item('lr_base_big_url'), $this->config->item('lr_base_big_url') . '/' . $postData['yp_id']));

                            $file_view = $this->config->item('lr_img_url') . $postData['yp_id'];
                            //upload big image
                            $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/index/' . $postData['yp_id']);

                            //upload small image
                            $insertImagesData = array();
                            if (!empty($upload_data)) {
                                foreach ($upload_data as $imageFiles) {
                                    /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                    createDirectory(array($this->config->item('lr_base_small_url'), $this->config->item('lr_base_small_url') . '/' . $postData['yp_id']));

                                    /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                    if ($imageFiles['is_image'])
                                        $a = do_resize($this->config->item('lr_img_url') . $postData['yp_id'], $this->config->item('lr_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
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
                                if ($row['type'] == 'date') {
                                    $data[$row['name']] = dateformat($postData[$row['name']]);
                                } elseif ($row['subtype'] == 'time') {
                                    $data[$row['name']] = dbtimeformat($postData[$row['name']]);
                                } else if ($row['type'] == 'checkbox-group') {
                                    $data[$row['name']] = !empty($postData[$row['name']]) ? implode(',', $postData[$row['name']]) : '';
                                } elseif ($row['type'] == 'textarea' && $row['subtype'] == 'tinymce') {
                                    $data[$row['name']] = strip_slashes($postData[$row['name']]);
                                } else {
                                    $data[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                                }
                            }
                        }
                    }
                }
            }
        }

        if (!empty($postData['location_register_id'])) {
            $data['location_register_id'] = $postData['location_register_id'];
            $data['yp_id'] = $postData['yp_id'];
            $data['modified_date'] = datetimeformat();
            $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            $this->common_model->update(LOCATION_REGISTER, $data, array('location_register_id' => $postData['location_register_id']));
            //Insert log activity
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                'module_name' => LR_MODULE,
                'module_field_name' => '',
                'type' => 2
            );
            log_activity($activity);
        } else {
            if (!empty($data)) {
                $data['yp_id'] = $postData['yp_id'];
                $data['created_date'] = datetimeformat();
                $data['modified_date'] = datetimeformat();
                $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
                $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
                /* added by Ritesh Ranan on 27/09/2018 to archive functionality */
                $data['care_home_id'] = $postData['care_home_id'];
                $this->common_model->insert(LOCATION_REGISTER, $data);

                $data['location_register_id'] = $this->db->insert_id();

                //Insert log activity
                $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                    'module_name' => LR_MODULE,
                    'module_field_name' => '',
                    'type' => 1
                );
                log_activity($activity);
            } else {

                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>Please  insert key session details.</div>");
                redirect('/' . $this->viewname . '/create/' . $postData['yp_id']);
            }
        }

        if (!empty($data)) {
            redirect('/' . $this->viewname . '/save_lr/' . $data['location_register_id'] . '/' . $data['yp_id']);
        } else {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>Please  insert key session details.</div>");
            redirect('/' . $this->viewname . '/create/' . $postData['yp_id']);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Save lr form
      @Input    :
      @Output   :
      @Date   : 13/07/2017
     */
   public function save_lr($location_register_id,$yp_id)
   {
	   
    if(is_numeric($location_register_id) && is_numeric($yp_id))
    {
        //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($yp_id,$fields);
        
        //get ks yp data
        $match = array('location_register_id'=> $location_register_id);
        $data['edit_data'] = $this->common_model->get_records(LOCATION_REGISTER,array("*"), '', '', $match);
        //check data exist or not
        if(empty($data['YP_details']) || empty($data['edit_data']))
        {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson/view/'.$yp_id);
        }
        $data['yp_id'] = $yp_id;
        $data['location_register_id'] = $location_register_id;
        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['main_content'] = '/save_ks';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
      else
      {
          show_404 ();
      }
   }
   
   /*
      @Author : Ritesh Rana
      @Desc   : View
      @Input    :
      @Output   :
      @Date   : 13/07/2017
     */ 
   public function view($location_register_id,$yp_id,$care_home_id=0,$past_care_id=0) {
    if(is_numeric($location_register_id) && is_numeric($yp_id))
    {
       //get lr form
       $match = array('lr_form_id'=> 1);
       $ks_forms = $this->common_model->get_records(LR_FORM,array("form_json_data"), '', '', $match);
       if(!empty($ks_forms))
       {
            $data['ks_form_data'] = json_decode($ks_forms[0]['form_json_data'], TRUE);
       }
        //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($yp_id,$fields);
        
        //get LR yp data
        $match = array('location_register_id'=> $location_register_id);
        $data['edit_data'] = $this->common_model->get_records(LOCATION_REGISTER,array("*"), '', '', $match);
        //get signoff data
        $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
        $table = LOCATION_REGISTER_SIGNOFF.' as lr';
        $where = array("l.is_delete"=> "0","lr.yp_id" => $yp_id,"lr.lr_id"=>$location_register_id,"lr.is_delete"=> "0");
        $fields = array("lr.created_by,lr.created_date,lr.yp_id,lr.lr_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id=lr.created_by');
        $group_by = array('created_by');
        $data['ks_signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);

        //check signoff data
        $table = LOCATION_REGISTER_SIGNOFF.' as lr';
        $where = array("lr.yp_id" => $yp_id,"lr.created_by" => $login_user_id,"lr.is_delete"=> "0","lr.lr_id"=>$location_register_id);
        $fields = array("lr.*");
        $data['check_ks_signoff_data'] = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);

        //check external signoff data
        $table = LR_SIGNOFF_DETAILS;
        $fields = array('key_data,form_json_data,lr_signoff_details_id,lr_id,yp_id');
        $where = array('lr_id' => $location_register_id, 'yp_id' => $yp_id);
        $data['check_external_signoff_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
        //check data exist or not
        if(empty($data['YP_details']) || empty($data['edit_data']))
        {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson/view/'.$yp_id);
        }
         /* added by Ritesh Ranan on 27/09/2018 to archive functionality */
        $data['care_home_id'] = $care_home_id;
        $data['past_care_id'] = $past_care_id;
        $data['ypid'] = $yp_id;
        $data['location_register_id'] = $location_register_id;
        $data['footerJs'][0] = base_url('uploads/custom/js/locationregister/locationregister.js');
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
            $params['table'] = LOCATION_REGISTER;
            $params['match_and'] = 'location_register_id=' . $id . '';
            $data['documents'] = $this->common_model->get_records_array($params);
            $data['field'] = $field;
            $this->load->view($this->viewname . '/readmore', $data);
      }        

/*
      @Author : Niral Patel
      @Desc   : insert signoff data
      @Input    : yp id
      @Output   :
      @Date   : 27/07/2017
     */
    public function manager_review($yp_id,$location_register_id) {
     if (!empty($yp_id) && !empty($location_register_id)) {

        //get YP information
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $YP_details = YpDetails($yp_id,$fields);

          $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
          $match = array('yp_id'=> $yp_id,'lr_id'=>$location_register_id,'created_by'=>$login_user_id,'is_delete'=> '0');
          $check_signoff_data = $this->common_model->get_records(LOCATION_REGISTER_SIGNOFF,array("*"), '', '', $match);
          if(empty($check_signoff_data) > 0){
          $update_pre_data['lr_id'] = $location_register_id;
          $update_pre_data['yp_id'] = $yp_id;
          $update_pre_data['created_date'] = datetimeformat();
          $update_pre_data['created_by'] = $login_user_id;
           /* added by Ritesh Ranan on 27/09/2018 to archive functionality */
          $update_pre_data['care_home_id'] = $YP_details['0']['care_home'];
          
        if ($this->common_model->insert(LOCATION_REGISTER_SIGNOFF,$update_pre_data)) {
          $msg = $this->lang->line('successfully_lr_review');
          $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {
          // error
          $msg = $this->lang->line('error_msg');
          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }
      }else{
        $msg = $this->lang->line('already_lr_review');
        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
      }
    }else{      
      $msg = $this->lang->line('error_msg');
      $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
    }
    redirect('/' . $this->viewname .'/view/'.$location_register_id.'/'.$yp_id);
  }
 
/*
      @Author : Ritesh Ranna
      @Desc   : User List Delete Query
      @Input  : Post id from List page
      @Output : Delete data from database and redirect
      @Date   : 13/06/2017
     */

    public function deletedata($location_register_id,$yp_id) {
                //Delete Record From Database
                if (!empty($location_register_id) && !empty($yp_id)) {
                    $data = array('is_delete' => 1);
                    $where = array('location_register_id' => $location_register_id , 'yp_id' => $yp_id);

                    if ($this->common_model->update(LOCATION_REGISTER, $data, $where)) {
                       //Insert log activity
                              $activity = array(
                                'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
                                'module_name'         => LR_MODULE,
                                'module_field_name'   => '',
                                'yp_id'   => $yp_id,
                                'type'                => 3
                              );
                  log_activity($activity);
                        $msg = $this->lang->line('lr_delete_msg');
                        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                        unset($location_register_id);
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
      @Author : Ritesh Ranna
      @Desc   : Send for Signoff / Approval form
      @Input  : 
      @Output : 
      @Date   : 13/06/2017
     */
    public function signoff($yp_id = '',$location_register_id = '') {
        $this->formValidation();
        $main_user_data = $this->session->userdata('LOGGED_IN');
        if ($this->form_validation->run() == FALSE) {
            $data['footerJs'][0] = base_url('uploads/custom/js/locationregister/locationregister.js');
            $data['crnt_view'] = $this->viewname;

            $data['ypid']= $yp_id;
            $data['location_register_id']= $location_register_id;

             /* added by Ritesh Ranan on 27/09/2018 to archive functionality */
             //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $YP_details = YpDetails($yp_id,$fields);
            $data['care_home_id'] = $YP_details[0]['care_home'];
            //get social info
            $table = SOCIAL_WORKER_DETAILS . ' as sw';
            $match = array("sw.yp_id" => $yp_id);
            $fields = array("sw.social_worker_id,sw.social_worker_firstname,sw.social_worker_surname");
            $data['social_worker_data'] = $this->common_model->get_records($table, $fields, '', '', $match);
            //get parent info
            $table = PARENT_CARER_INFORMATION . ' as pc';
            $match = array("pc.yp_id" => $yp_id,'pc.is_deleted' => 0);
            $fields = array("pc.parent_carer_id,pc.firstname,pc.surname");
            $data['parent_data'] = $this->common_model->get_records($table, $fields, '', '', $match);
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
      @Author : Ritesh Ranna
      @Desc   : Send for Signoff / Approval form
      @Input  : 
      @Output : 
      @Date   : 13/06/2017
     */
    public function insertdata() {
        $postdata = $this->input->post();
        $ypid = $postdata['ypid'];
        $location_register_id = $postdata['lr_id'];
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
              'module_name' => LR_PARENT_CARER_DETAILS_YP,
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
			
			$match = array('lr_form_id'=> 1);
			 $formsdata = $this->common_model->get_records(LR_FORM,array("form_json_data"), '', '', $match);
             //get lr yp data
			 $match = array('yp_id'=> $ypid,'location_register_id'=>$location_register_id);
       $ks_yp_data = $this->common_model->get_records(LOCATION_REGISTER,array("*"), '', '', $match);
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
								echo $ks_yp_data[0][$row['name']];
							  }else{
								$ks_form_data[$i]['value'] = str_replace("'", '"', $ks_yp_data[0][$row['name']]);
							  }
						  }
					  }
					  $i++;
				  }
			
				 }
				 
            $data = array(
                'user_type' => ucfirst($postdata['user_type']),
                'yp_id' => ucfirst($postdata['ypid']),
                'lr_id' => ucfirst($postdata['lr_id']),
				        'form_json_data' =>json_encode($ks_form_data, TRUE),
                'fname' => ucfirst($postdata['fname']),
                'lname' => ucfirst($postdata['lname']),
                'email' => $postdata['email'],
                'key_data' => md5($postdata['email']),
                 /* added by Ritesh Ranan on 27/09/2018 to archive functionality */
                'care_home_id' => $postdata['care_home_id'],
                'created_date' => datetimeformat(),
                'created_by' => $main_user_data['ID'],
                'updated_by' => $main_user_data['ID'],
            );
            //Insert Record in Database
            if ($this->common_model->insert(LR_SIGNOFF_DETAILS, $data)) {

                $signoff_id = $this->db->insert_id();

				$table = LOCATION_REGISTER_SIGNOFF;
				$where = array("yp_id" => $ypid,"lr_id" => $location_register_id,"is_delete"=> "0");
				$fields = array("created_by,yp_id,lr_id,created_date");
				$group_by = array('created_by');
				$signoff_data = $this->common_model->get_records($table,$fields,'','','','','','','','',$group_by,$where);

				if(!empty($signoff_data)){
					foreach ($signoff_data as $archive_value) {
						$update_arc_data['approval_lr_id'] = $signoff_id;
						$update_arc_data['yp_id'] = $archive_value['yp_id'];
						$update_arc_data['created_date'] = $archive_value['created_date'];
						$update_arc_data['created_by'] = $archive_value['created_by'];
						$this->common_model->insert(APPROVAL_LOCATION_REGISTER_SIGNOFF,$update_arc_data);
					}
				}
			     
                $this->sendMailToRelation($data, $signoff_id); // send mail
                //Insert log activity
                $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($ypid) ? $ypid : '',
                    'module_name' => LP_NEW_ADD,
                    'module_field_name' => '',
                    'type' => 1
                );
                log_activity($activity);
                $msg = $this->lang->line('successfully_sign_off');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            } else {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
        
        redirect('LocationRegister/view/'.$location_register_id.'/'.$ypid);
    }

    /*
      @Author : Ritesh Ranna
      @Desc   : Send to user for Signoff / Approval form
      @Input  : 
      @Output : 
      @Date   : 13/06/2017
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
            $loginLink = base_url('LocationRegister/signoffData/' . $data['yp_id'] . '/' . $signoff_id . '/' . $email);

            $find = array('{NAME}','{LINK}');

            $replace = array(
                'NAME' => $customerName,
                'LINK' => $loginLink,
            );
            
            $emailSubject = 'Welcome to NFCTracker';
                    $emailBody = '<div>'
                    . '<p>Hello {NAME} ,</p> '
                    . '<p>Please find Location Register for '.$yp_name.' for your approval.</p> '
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
      @Desc   : sign off view data
      @Input  :
      @Output :
      @Date   : 28/03/2018
     */
     public function signoffData($yp_id,$id,$email) {

     if(is_numeric($id) && is_numeric($yp_id) && !empty($email))
       {
          $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
          $match = array('yp_id'=> $yp_id,'lr_signoff_details_id'=>$id,'key_data'=> $email,'status'=>'inactive');
          $check_signoff_data = $this->common_model->get_records(LR_SIGNOFF_DETAILS,array("created_date,form_json_data,lr_id"), '', '', $match);
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

              $table = APPROVAL_LOCATION_REGISTER_SIGNOFF.' as ras';
              $where = array("l.is_delete"=> "0","ras.yp_id" => $yp_id,"ras.is_delete"=> "0","approval_lr_id"=>$id);
              $fields = array("ras.created_by,ras.created_date,ras.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
              $join_tables = array(LOGIN . ' as l' => 'l.login_id=ras.created_by');
              $group_by = array('created_by');
              $data['signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);
              $data['key_data']= $email;
              $data['ypid'] = $yp_id;
              $data['lr_id'] = $check_signoff_data[0]['lr_id'];
              $data['signoff_id'] = $id;
              $data['footerJs'][0] = base_url('uploads/custom/js/locationregister/locationregister.js');
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
         //show_404 (); 
		$msg = $this->lang->line('already_lr_review');
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
      @Desc   : signoff review data
      @Input  :
      @Output :
      @Date   : 28/03/2018
     */
public function signoff_review_data($yp_id,$location_register_id,$email) {
    if (!empty($yp_id) && !empty($location_register_id) && !empty($email)) {
          $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
          $match = array('yp_id'=> $yp_id,'lr_signoff_details_id'=>$location_register_id,'key_data'=> $email,'status'=>'inactive');
          $check_signoff_data = $this->common_model->get_records(LR_SIGNOFF_DETAILS,array("form_json_data,created_date"), '', '', $match);

          if(!empty($check_signoff_data)){ 
          $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'].REPORT_EXPAIRED_DAYS));
          if(strtotime(datetimeformat()) <= strtotime($expairedDate))
          {
            $u_data['status'] = 'active';
            $u_data['modified_date'] = datetimeformat();
            $success = $this->common_model->update(LR_SIGNOFF_DETAILS,$u_data,array('lr_signoff_details_id'=> $location_register_id,'yp_id'=> $yp_id,'key_data'=> $email));
            if ($success) {
            $msg = $this->lang->line('successfully_lr_review');
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
        $msg = $this->lang->line('already_lr_review');
      $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>");
    }
    }else{      
      $msg = $this->lang->line('error_msg');
      $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>");
    }
	$this->load->view('successfully_message');
  }
  /*
      @Author : Ritesh Rana
      @Desc   : get user type detail
      @Input  :
      @Output :
      @Date   : 14/05/2018
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
    public function external_approval_list($ypid,$location_register_id,$care_home_id=0,$past_care_id=0) {
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

        if(is_numeric($ypid) && is_numeric($location_register_id)){
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
            $this->session->unset_userdata('ks_approval_session_data');
        }

        $searchsort_session = $this->session->userdata('ks_approval_session_data');
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
                $sortfield = 'lr_signoff_details_id';
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

  $config['base_url'] = base_url() . $this->viewname . '/external_approval_list/'.$ypid.'/'.$location_register_id;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 5;
            $uri_segment = $this->uri->segment(5);
        }
        //Query

        $table = LR_SIGNOFF_DETAILS . ' as lr';
        $where = array("lr.yp_id"=>$ypid,"lr.lr_id"=>$location_register_id);
        $fields = array("lr.*,CONCAT(`firstname`,' ', `lastname`) as create_name ,CONCAT(`fname`,' ', `lname`) as user_name,ch.care_home_name");
         $join_tables = array(LOGIN . ' as l' => 'l.login_id= lr.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = lr.care_home_id');
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
        }
}else{

  $config['base_url'] = base_url() . $this->viewname . '/external_approval_list/'.$ypid.'/'.$location_register_id.'/'.$care_home_id.'/'.$past_care_id;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 7;
            $uri_segment = $this->uri->segment(7);
        }
        //Query

        $table = LR_SIGNOFF_DETAILS . ' as lr';
        $where = array("lr.yp_id"=>$ypid,"lr.lr_id"=>$location_register_id);
        $fields = array("lr.*,CONCAT(`firstname`,' ', `lastname`) as create_name ,CONCAT(`fname`,' ', `lname`) as user_name,ch.care_home_name");
        $where_date = "lr.created_date BETWEEN  '".$created_date."' AND '".$movedate."'";
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= lr.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = lr.care_home_id');
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where,'','','','','',$where_date);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1','','',$where_date);
        }
}

        $data['care_home_id'] = $care_home_id;
        $data['past_care_id'] = $past_care_id;  
        $data['ypid'] = $ypid;
        $data['lr_id'] = $location_register_id;
        
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

        $this->session->set_userdata('ks_approval_session_data', $sortsearchpage_data);
        setActiveSession('ks_approval_session_data'); // set current Session active
        $data['header'] = array('menu_module' => 'Communication');
       
        //get communication form
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/locationregister/locationregister.js');
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
      @Desc   : view Approval Lr data
      @Input  :
      @Output :
      @Date   : 12/04/2018
    */
public function viewApprovalLr($id,$ypid,$care_home_id=0,$past_care_id=0)                         
    {
      if(is_numeric($id) && is_numeric($ypid))
       {
         //get archive lr data
        $match = array('lr_signoff_details_id'=> $id);
        $formsdata = $this->common_model->get_records(LR_SIGNOFF_DETAILS,array("form_json_data,lr_id"), '', '', $match);
        $data['formsdata'] = $formsdata;
        if(!empty($formsdata))
        {
            $data['pp_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
          //get YP information
          $match = array("yp_id"=>$ypid);
          $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($ypid,$fields);
          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$ypid);
          }
        $table = APPROVAL_LOCATION_REGISTER_SIGNOFF.' as aks';
              $where = array("l.is_delete"=> "0","aks.yp_id" => $ypid,"aks.is_delete"=> "0","approval_lr_id"=>$id);
              $fields = array("aks.created_by,aks.created_date,aks.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
              $join_tables = array(LOGIN . ' as l' => 'l.login_id=aks.created_by');
              $group_by = array('created_by');
              $data['signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);
          $data['ypid'] = $ypid;
          $data['care_home_id'] = $care_home_id;
          $data['past_care_id'] = $past_care_id;
          $data['lr_id'] = $formsdata[0]['lr_id'];
          $data['footerJs'][0] = base_url('uploads/custom/js/locationregister/locationregister.js');
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
      @Author : Ritesh Rana
      @Desc   : external approve view data
      @Input  :
      @Output :
      @Date   : 14/05/2018
     */
    
     public function resend_external_approval($signoff_id,$ypid,$lrid) {
      $match = array('lr_signoff_details_id'=>$signoff_id);
      $signoff_data = $this->common_model->get_records(LR_SIGNOFF_DETAILS,array("yp_id,fname,lname,email"), '', '', $match);
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
          $success =$this->common_model->update(LR_SIGNOFF_DETAILS,$u_data,array('ks_signoff_details_id'=> $signoff_id));
          $msg = $this->lang->line('mail_sent_successfully');
          $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
        else
        {
          $msg = $this->lang->line('error');
          $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
      }
      redirect($this->viewname.'/external_approval_list/' . $ypid.'/'.$lrid);
     }
	 
	 /*
      @Author : Nikunj Ghelani
      @Desc   : PDF data
      @Input  :
      @Output :
      @Date   : 16/07/2018
     */
	 
	 public function DownloadPdf($yp_id,$id) {
		 
		 if(is_numeric($id) && is_numeric($yp_id))
       {
          $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
          $match = array('yp_id'=> $yp_id,'lr_signoff_details_id'=>$id,'status'=>'inactive');
          $check_signoff_data = $this->common_model->get_records(LR_SIGNOFF_DETAILS,array("created_date,form_json_data,lr_id"), '', '', $match);
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

              $table = APPROVAL_LOCATION_REGISTER_SIGNOFF.' as ras';
              $where = array("l.is_delete"=> "0","ras.yp_id" => $yp_id,"ras.is_delete"=> "0","approval_lr_id"=>$id);
              $fields = array("ras.created_by,ras.created_date,ras.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
              $join_tables = array(LOGIN . ' as l' => 'l.login_id=ras.created_by');
              $group_by = array('created_by');
              $data['signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);

              $data['key_data']= $email;
              $data['ypid'] = $yp_id;
              $data['lr_id'] = $check_signoff_data[0]['lr_id'];
              $data['signoff_id'] = $id;
              $data['footerJs'][0] = base_url('uploads/custom/js/locationregister/locationregister.js');
              $data['crnt_view'] = $this->viewname;
             $pdfFileName = "location_register_pdf.pdf";
					$PDFInformation['yp_details'] = $data['YP_details'][0];
					$PDFInformation['edit_data'] = $data['edit_data'][0]['modified_date'];

					$PDFHeaderHTML  = $this->load->view('location_register_pdfHeader', $PDFInformation,true);
					$PDFFooterHTML  = $this->load->view('location_register_pdfFooter', $PDFInformation,true);
					//Set Header Footer and Content For PDF
					$this->m_pdf->pdf->mPDF('utf-8','A4','','','10','10','45','25');
			
					$this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
					$this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);                    
					$data['main_content'] = '/location_register_pdf';
					$html = $this->parser->parse('layouts/PdfDataTemplate', $data);
					
					/*remove*/
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
         //show_404 (); 
		 $msg = $this->lang->line('already_lr_review');
      $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
 $this->load->view('successfully_message');
      }
      }
      else
      {
          show_404 ();
      }
		 
		 
		 
	 }
}
