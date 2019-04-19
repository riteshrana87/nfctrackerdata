<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Concerns extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session','m_pdf'));
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Concerns Index Page
      @Input  : yp id
      @Output :
      @Date   : 16/03/2018
     */
    /* function updated by Dhara Bhalala for carehome YP archive */
    public function index($id, $care_home_id = 0, $isArchiveCareHomePage = 0) {
        if ($isArchiveCareHomePage !== 0) {
            $temp = $this->common_model->get_records(PAST_CARE_HOME_INFO, array('move_date'), '', '', array("yp_id" => $id, "past_carehome" => $care_home_id));
            $careHomeDetails = $this->common_model->get_records(PAST_CARE_HOME_INFO, array("*"), '', '', array("yp_id" => $id, "move_date <= " => $temp[0]['move_date']));
            $enterDate = $moveDate = '';
            $totalCareHome = count($careHomeDetails);
            if ($totalCareHome >= 1) {
                $enterDate = $careHomeDetails[0]['enter_date'];
                $moveDate = $careHomeDetails[$totalCareHome - 1]['move_date'];
            } elseif ($totalCareHome == 0) {
                $enterDate = $careHomeDetails[0]['enter_date'];
                $moveDate = $careHomeDetails[0]['move_date'];
            }
        }
        if (is_numeric($id)) {
            //get YP information
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, array("care_home,yp_fname,yp_lname,date_of_birth,yp_id"), '', '', array("yp_id" => $id));

            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/' . $id);
            }
            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = RECORD_PER_PAGE;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('ypc_data');
            }
            $searchsort_session = $this->session->userdata('ypc_data');
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
                    $sortfield = 'date';
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
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $table = YP_CONCERNS . ' as ypc';
            $where = array("ypc.yp_id" => $id, "ypc.is_archive" => 0, "ypc.is_delete" => 0);
            /* changes done by Dhara Bhalala for conclude option and get other details */
			$fields = array("l.login_id", "CONCAT(`firstname`,' ', `lastname`) as name", 
                "l.firstname"," l.lastname","ch.care_home_name", "ypc.*",
                "ypc.date as date","ypc.time as time",
                "count(yc.ypc_comments_id) as total_comments",
                "substring_index(GROUP_CONCAT( DISTINCT CONCAT(yc.created_by,'|',yc.created_date,'|',yc.ypc_comments_id) ORDER BY yc.ypc_comments_id DESC SEPARATOR ';'), ';', 1) as Comment_details");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id = ypc.created_by', CARE_HOME . ' as ch' => 'ch.care_home_id = ypc.care_home_id', YPC_COMMENTS . ' as yc' => 'yc.ypc_id = ypc.ypc_id');    
            $group_by = "ypc.ypc_id";
            if (!empty($searchtext)) {
                $searchtext = html_entity_decode(trim(addslashes($searchtext)));
                $match = '';
                $where_search = '((CONCAT(`firstname`, \' \', `lastname`) LIKE "%' . $searchtext . '%" OR l.firstname LIKE "%' . $searchtext . '%" OR l.lastname LIKE "%' . $searchtext . '%" OR ch.care_home_name LIKE "%' . $searchtext . '%" OR ypc.date LIKE "%' . $searchtext . '%" OR ypc.time LIKE "%' . $searchtext . '%" OR l.status LIKE "%' . $searchtext . '%")AND l.is_delete = "0")';
            }
            if ($isArchiveCareHomePage == 0) {
                $config['base_url'] = base_url() . $this->viewname . '/index/' . $id;
                if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 4;
                    $uri_segment = $this->uri->segment(4);
                }
                
                if (!empty($searchtext)) {                    
                    $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, $group_by, $where_search);

                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, $group_by, $where_search, '', '', '1');
                } else {
                    $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, $group_by, $where);

                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1');
                }
                $loginData = $this->common_model->customQuery("select login_id,CONCAT(`firstname`,' ', `lastname`) as name from nfc_".LOGIN);
                foreach ($data['edit_data'] as $key => $value) {
                    if($value['total_comments'] > 0){
                        $commentPerson = explode('|', $value['Comment_details']);
                        $arrayKey = array_search($commentPerson[0], array_column($loginData, 'login_id'));
                        $data['edit_data'][$key]['last_comment_added_by'] = $loginData[$arrayKey]['name'];
                        $data['edit_data'][$key]['last_comment_added_on'] = $commentPerson[1];
                    }
                }
            } else {
                $config['base_url'] = base_url() . $this->viewname . '/index/' . $id . '/' . $care_home_id . '/' .$isArchiveCareHomePage;
                if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 6;
                    $uri_segment = $this->uri->segment(6);
                }

                $where_date = "ypc.created_date BETWEEN  '" . $enterDate . "' AND '" . $moveDate . "'";
                
                if (!empty($searchtext)) {
                    $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search, '', '', '', '', '', $where_date);

                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1', '', '', $where_date);
                } else {
                    $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where, '', '', '', '', '', $where_date);

                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1', '', '', $where_date);
                }
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
                'total_rows' => $config['total_rows']
            );
            
            $data['ypid'] = $id;
            $data['care_home_id'] = $care_home_id;
            $data['isArchiveCareHomePage'] = $isArchiveCareHomePage;
            //get ks form
            $match = array('ypc_form_id' => 1);
            $ks_forms = $this->common_model->get_records(YPC_FORM, '', '', '', $match);
            if (!empty($ks_forms)) {
                $data['form_data'] = json_decode($ks_forms[0]['form_json_data'], TRUE);
            }
            $this->session->set_userdata('ypc_data', $sortsearchpage_data);
            setActiveSession('ypc_data'); // set current Session active
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['crnt_view'] = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/concerns/concerns.js');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/ajaxlist', $data);
            } else {
                $data['main_content'] = '/keysession';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }
    /* function added by Dhara Bhalala for conclude option */
    public function concludeYPC() {
        $postData = $this->input->post();
        $data = array('is_concluded'=>1);
        $this->common_model->update(YP_CONCERNS, $data, array('ypc_id' => $postData['ypc_id'],'yp_id'=> $postData['yp_id']));        
    }

    /*
      @Author : Ritesh Rana
      @Desc   : create yp edit page
      @Input  :
      @Output :
      @Date   : 13/07/2017
     */

    public function create($id) {
        if (is_numeric($id)) {
            //get ks form
            $match = array('ypc_form_id' => 1);
            $ks_forms = $this->common_model->get_records(YPC_FORM, '', '', '', $match);
            $form_field = array();
            if (!empty($ks_forms)) {
                $data['ks_form_data'] = json_decode($ks_forms[0]['form_json_data'], TRUE);

                foreach ($data['ks_form_data'] as $form_data) {
                    $form_field[] = $form_data['name'];
                }
            }

            $data['form_field_data_name'] = $form_field;
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($id,$fields);
            
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/'.$id);
            }
            $data['care_home_id'] = $data['YP_details'][0]['care_home'];
            $data['ypid'] = $id;
            $data['footerJs'][0] = base_url('uploads/custom/js/concerns/concerns.js');
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['crnt_view'] = $this->viewname;
            $data['main_content'] = '/edit';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : edit YPC 
      @Input    :
      @Output   :
      @Date   : 13/07/2017
    */
    public function edit_draft($ypc_id, $yp_id) {
        if (is_numeric($ypc_id) && is_numeric($yp_id)) {
            //get ks form
            $match = array('ypc_form_id' => 1);
            $ks_forms = $this->common_model->get_records(YPC_FORM, '', '', '', $match);

            if (!empty($ks_forms)) {
                $data['ks_form_data'] = json_decode($ks_forms[0]['form_json_data'], TRUE);

                foreach ($data['ks_form_data'] as $form_data) {
                    $form_field[] = $form_data['name'];
                }
            }

            $data['form_field_data_name'] = $form_field;

            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($yp_id,$fields);
            //get ks yp data
            $match = array('ypc_id' => $ypc_id);
            $data['edit_data'] = $this->common_model->get_records(YP_CONCERNS, array('*'), '', '', $match);
            //check data exist or not
            if (empty($data['YP_details']) || empty($data['edit_data'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/'.$yp_id);
            }
            $data['ypid'] = $yp_id;
            $data['ypc_id'] = $ypc_id;
            $data['footerJs'][0] = base_url('uploads/custom/js/concerns/concerns.js');
            $data['crnt_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/edit';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert ypc form
      @Input    :
      @Output   :
      @Date   : 13/07/2017
     */

    public function insert() {
        if (!validateFormSecret()) {
            redirect($_SERVER['HTTP_REFERER']);  //Redirect On Listing page
        }
        $postData = $this->input->post();
        $draftdata = (isset($postData['saveAsDraft'])) ? $postData['saveAsDraft'] : 0;
        if ($this->input->is_ajax_request()) {
            $draftdata = 1;
        }
        unset($postData1['submit_ypcform']);
        //get pp form
        $match = array('ypc_form_id' => 1);
        $ks_forms = $this->common_model->get_records(YPC_FORM, array("*"), '', '', $match);
        if (!empty($ks_forms)) {
            $ks_form_data = json_decode($ks_forms[0]['form_json_data'], TRUE);
            $data = array();
            foreach ($ks_form_data as $row) {
                if (isset($row['name'])) {
                    if ($row['type'] == 'file') {
                        $filename = $row['name'];
                        //get image previous image
                        $match = array('ypc_id' => $postData['ypc_id'], 'yp_id' => $postData['yp_id']);
                        $ks_yp_data = $this->common_model->get_records(YP_CONCERNS, array('`' . $row['name'] . '`'), '', '', $match);
                        //delete img
                        if (!empty($postData['hidden_' . $row['name']])) {
                            $delete_img = explode(',', $postData['hidden_' . $row['name']]);
                            $db_images = explode(',', $ks_yp_data[0][$filename]);
                            $differentedImage = array_diff($db_images, $delete_img);
                            $ks_yp_data[0][$filename] = !empty($differentedImage) ? implode(',', $differentedImage) : '';
                            if (!empty($delete_img)) {
                                foreach ($delete_img as $img) {

                                    if (file_exists($this->config->item('yps_img_url') . $postData['yp_id'] . '/' . $img)) {
                                        unlink($this->config->item('yps_img_url') . $postData['yp_id'] . '/' . $img);
                                    }
                                    if (file_exists($this->config->item('yps_img_url_small') . $postData['yp_id'] . '/' . $img)) {
                                        unlink($this->config->item('yps_img_url_small') . $postData['yp_id'] . '/' . $img);
                                    }
                                }
                            }
                        }

                        if (!empty($_FILES[$filename]['name'][0])) {
                            //create dir and give permission
                            /* common function replaced by Dhara Bhalala on 29/09/2018 */
                            createDirectory(array($this->config->item('yps_base_url'), $this->config->item('yps_base_big_url'), $this->config->item('yps_base_big_url') . '/' . $postData['yp_id']));

                            $file_view = $this->config->item('yps_img_url') . $postData['yp_id'];
                            //upload big image
                            $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/index/' . $postData['yp_id']);
                            //upload small image
                            $insertImagesData = array();
                            if (!empty($upload_data)) {
                                foreach ($upload_data as $imageFiles) {
                                    /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                    createDirectory(array($this->config->item('yps_base_small_url'), $this->config->item('yps_base_small_url') . '/' . $postData['yp_id']));

                                    /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                    if ($imageFiles['is_image'])
                                        $a = do_resize($this->config->item('yps_img_url') . $postData['yp_id'], $this->config->item('yps_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
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

        if (!empty($postData['ypc_id'])) {
            $data['draft'] = $draftdata;
            $data['ypc_id'] = $postData['ypc_id'];
            $data['yp_id'] = $postData['yp_id'];
            $data['modified_date'] = datetimeformat();
            $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            $this->common_model->update(YP_CONCERNS, $data, array('ypc_id' => $postData['ypc_id']));
            //Insert log activity
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                'module_name' => YPC_CONCERNS_MODULE,
                'module_field_name' => '',
                'type' => 2
            );
            log_activity($activity);
        } else {
            if (!empty($data)) {
                $data['draft'] = $draftdata;
                $data['yp_id'] = $postData['yp_id'];
                $data['care_home_id'] = $postData['care_home_id'];
                $data['created_date'] = datetimeformat();
                $data['modified_date'] = datetimeformat();
                $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
                $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];

                $this->common_model->insert(YP_CONCERNS, $data);
                $data['ypc_id'] = $this->db->insert_id();
                //Insert log activity
                $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                    'module_name' => YPC_CONCERNS_MODULE,
                    'module_field_name' => '',
                    'type' => 1
                );
                log_activity($activity);
            }
        }
        if (!empty($data)) {
            redirect('/' . $this->viewname . '/saveConcerns/' . $data['ypc_id'] . '/' . $data['yp_id']);
        } else {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>Please  insert Concerns details.</div>");
            redirect('/' . $this->viewname . '/create/' . $postData['yp_id']);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Save ks form
      @Input    :
      @Output   :
      @Date   : 13/07/2017
     */

    public function saveConcerns($ypc_id, $yp_id) {
        if (is_numeric($ypc_id) && is_numeric($yp_id)) {
            //get YP information
             $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
             $data['YP_details'] = YpDetails($yp_id,$fields);

            //get ks yp data
            $match = array('ypc_id' => $ypc_id);
            $data['edit_data'] = $this->common_model->get_records(YP_CONCERNS, array("*"), '', '', $match);
            //check data exist or not
            if (empty($data['YP_details']) || empty($data['edit_data'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/'.$yp_id);
            }
            $data['yp_id'] = $yp_id;
            $data['ypc_id'] = $ypc_id;
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/save_ks';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }

    /* function updated by Dhara Bhalala for carehome YP archive */
    public function view($ypc_id, $yp_id, $care_home_id = 0, $isArchiveCareHomePage = 0) {
        if (is_numeric($ypc_id) && is_numeric($yp_id)) {
            //get ypc form
            $match = array('ypc_form_id' => 1);
            $ypc_forms = $this->common_model->get_records(YPC_FORM, '', '', '', $match);
            if (!empty($ypc_forms)) {
                $data['ks_form_data'] = json_decode($ypc_forms[0]['form_json_data'], TRUE);
            }
            // get ypc comments data
            $table = YPC_COMMENTS . ' as com';
            $where = array("com.ypc_id" => $ypc_id, "com.yp_id" => $yp_id);
            $fields = array("com.ypc_comments,com.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
            $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
            //get yp details    
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($yp_id,$fields);
            //get ks yp data
            $match = array('ypc_id' => $ypc_id);
            $data['edit_data'] = $this->common_model->get_records(YP_CONCERNS, array("*"), '', '', $match);

            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $table = CONCERNS_SIGNOFF . ' as cs';
            $where = array("l.is_delete" => "0", "cs.yp_id" => $yp_id, "cs.ypc_id" => $ypc_id, "cs.is_delete" => "0");
            $fields = array("cs.created_by,cs.created_date,cs.yp_id,cs.ypc_id, CONCAT(`firstname`,' ', `lastname`) as name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id=cs.created_by');
            $group_by = array('created_by');
            $data['ks_signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);

            //check data exist or not
            $table = CONCERNS_SIGNOFF . ' as cs';
            $where = array("cs.yp_id" => $yp_id, "cs.created_by" => $login_user_id, "cs.is_delete" => "0", "cs.ypc_id" => $ypc_id);
            $fields = array("cs.ypc_signoff_id,cs.created_date");
            $data['check_ks_signoff_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

            //check external signoff data exist or not
            $table = NFC_YPC_SIGNOFF_DETAILS;
            $fields = array('form_json_data,user_type,key_data');
            $where = array('ypc_id' => $ypc_id, 'yp_id' => $yp_id);
            $data['check_external_signoff_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            
            //check data exist or not
            if (empty($data['YP_details']) || empty($data['edit_data'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/' . $yp_id);
            }
            $data['care_home_id'] = $care_home_id;
            $data['isArchiveCareHomePage'] = $isArchiveCareHomePage;
            $data['ypid'] = $yp_id;
            $data['ypc_id'] = $ypc_id;
            $data['footerJs'][0] = base_url('uploads/custom/js/concerns/concerns.js');
            $data['crnt_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/view';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Move to ypc
      @Input    :
      @Output   :
      @Date   : 28/06/2018
     */
    /* function updated by Dhara Bhalala for carehome YP archive */
    public function moveToKS($ypc_id, $yp_id) {
        if (is_numeric($ypc_id) && is_numeric($yp_id)) {
            //get ks form
            $match = array('ks_form_id' => 1);
            $ks_forms = $this->common_model->get_records(KS_FORM, '', '', '', $match);
            if (!empty($ks_forms)) {
                $ks_form_data = json_decode($ks_forms[0]['form_json_data'], TRUE);
            }

            //get ypc form
            $match = array('ypc_form_id' => 1);
            $ypc_forms = $this->common_model->get_records(YPC_FORM, '', '', '', $match);
            if (!empty($ypc_forms)) {
                $ypc_form_data = json_decode($ypc_forms[0]['form_json_data'], TRUE);
            }
            $ypcGetFields = array();
            if (!empty($ypc_form_data)) {
                foreach ($ypc_form_data as $row) {
                    $ypcGetFields[] = $row['name'];
                }
            }
            $ksFields = array();
            if (!empty($ks_form_data)) {
                foreach ($ks_form_data as $row) {
                    if (in_array($row['name'], $ypcGetFields)) {
                        $ksFields[] = $row['name'];
                    }
                }
            }
            //get ks yp data
            $ksFields[] = 'yp_id';
            $ksFields[] = 'created_by';
            $ksFields[] = 'created_date';
            $ksFields[] = 'is_archive';
            $ksFields[] = 'modified_by';
            $ksFields[] = 'modified_date';
            $ksFields[] = 'signoff';
            $ksFields[] = 'draft';
            $ksFields[] = 'is_delete';
            $ksFields[] = 'care_home_id';

            $match = array('ypc_id' => $ypc_id);
            $currentData = $this->common_model->get_records(YP_CONCERNS, $ksFields, '', '', $match);

            if (!empty($currentData)) {
                $insertData = array();
                foreach ($currentData as $row) {
                    $insertData = $row;
                }
            } else {
                $msg = 'YPC moved successfully.';
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                redirect('KeySession/index/' . $yp_id);
            }
            $ks_id = $this->common_model->insert(KEY_SESSION, $insertData);
            // get ks comments data
            $table = YPC_COMMENTS . ' as com';
            $where = array("com.ypc_id" => $ypc_id, "com.yp_id" => $yp_id);
            $fields = array("com.ypc_comments,yp_id,created_date,created_date,created_by");
            $ksComments = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

            if (!empty($ksComments)) {
                $commentData = array();
                foreach ($ksComments as $row) {
                    $commentData['ks_comments'] = $row['ypc_comments'];
                    $commentData['yp_id'] = $row['yp_id'];
                    $commentData['ks_id'] = $ks_id;
                    $commentData['created_date'] = $row['created_date'];
                    $commentData['created_by'] = $row['created_by'];
                    $this->common_model->insert(KS_COMMENTS, $commentData);
                }
            }

            //get ks sign off data
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $table = CONCERNS_SIGNOFF . ' as ks';
            $where = array("ks.yp_id" => $yp_id, "ks.ypc_id" => $ypc_id);
            $fields = array("*");
            $signoffInfo = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            if (!empty($signoffInfo)) {
                $signoffData = array();
                foreach ($signoffInfo as $row) {
                    $signoffData['yp_id'] = $row['yp_id'];
                    $signoffData['ks_id'] = $ks_id;
                    $signoffData['created_by'] = $row['created_by'];
                    $signoffData['created_date'] = $row['created_date'];
                    $signoffData['is_delete'] = $row['is_delete'];

                    $this->common_model->insert(KEYSESSION_SIGNOFF, $signoffData);
                }
            }

            //NFC_KS_SIGNOFF_DETAILS
            $table = NFC_YPC_SIGNOFF_DETAILS;
            $fields = array('*');
            $where = array('ypc_id' => $ypc_id, 'yp_id' => $yp_id);
            $check_external_signoff_data = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

            if (!empty($check_external_signoff_data)) {
                foreach ($check_external_signoff_data as $row) {
                    $signoffDetails['ypc_signoff_details_id'] = $row['ypc_signoff_details_id'];
                    $signoffDetails['yp_id'] = $row['yp_id'];
                    $signoffDetails['ks_id'] = $ks_id;
                    $signoffDetails['form_json_data'] = $row['form_json_data'];
                    $signoffDetails['user_type'] = $row['user_type'];
                    $signoffDetails['fname'] = $row['fname'];
                    $signoffDetails['lname'] = $row['lname'];
                    $signoffDetails['email'] = $row['email'];
                    $signoffDetails['key_data'] = $row['key_data'];
                    $signoffDetails['updated_by'] = $row['updated_by'];
                    $signoffDetails['modified_date'] = $row['modified_date'];
                    $signoffDetails['status'] = $row['status'];
                    $signoffDetails['created_by'] = $row['created_by'];
                    $signoffDetails['created_date'] = $row['created_date'];

                    $signoffId = $this->common_model->insert(NFC_KS_SIGNOFF_DETAILS, $signoffDetails);
                    //NFC_APPROVAL_KEYSESSION_SIGNOFF
                    $table = NFC_APPROVAL_YPC_SIGNOFF . ' as ras';
                    $where = array("ras.yp_id" => $yp_id, "ras.is_delete" => "0", "approval_ypc_id" => $row['ypc_signoff_details_id']);
                    $fields = array("ras.*");

                    $approvalSignoffData = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
                    if (!empty($approvalSignoffData)) {
                        foreach ($approvalSignoffData as $row) {
                            $signoffApproval['approval_ks_id'] = $signoffId;
                            $signoffApproval['yp_id'] = $row['yp_id'];
                            $signoffApproval['created_by'] = $row['created_by'];
                            $signoffApproval['created_date'] = $row['created_date'];
                            $signoffApproval['is_delete'] = $row['is_delete'];
                            $this->common_model->insert(NFC_APPROVAL_KEYSESSION_SIGNOFF, $signoffApproval);
                            $this->common_model->delete(NFC_APPROVAL_YPC_SIGNOFF, array('approval_ypc_signoff_id' => $row['approval_ypc_signoff_id']));
                        }
                    }
                }
            }
            //delete ks
            $this->common_model->delete(YP_CONCERNS, array('ypc_id' => $ypc_id));
            $this->common_model->delete(YPC_COMMENTS, array('ypc_id' => $ypc_id));
            $this->common_model->delete(CONCERNS_SIGNOFF, array('ypc_id' => $ypc_id));
            $this->common_model->delete(NFC_YPC_SIGNOFF_DETAILS, array('ypc_id' => $ypc_id));

            //Insert log activity
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($yp_id) ? $yp_id : '',
                'module_name' => KS_MODULE,
                'module_field_name' => '',
                'type' => 1
            );
            log_activity($activity);

            $msg = 'YPC moved successfully.';
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect('KeySession/index/' . $yp_id);
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Read more
      @Input    : yp id
      @Output   :
      @Date   : 27/07/2017
     */

    public function readmore($id, $field) {
        $params['fields'] = [$field];
        $params['table'] = YP_CONCERNS;
        $params['match_and'] = 'ypc_id=' . $id . '';
        $data['documents'] = $this->common_model->get_records_array($params);
        $data['field'] = $field;
        $this->load->view($this->viewname . '/readmore', $data);
    }


    /*
      @Author : Niral Patel
      @Desc   : Download Print
      @Input    : yp id,ypc id
      @Output   :
      @Date   : 27/07/2017
    */
    public function DownloadPrint($ypc_id, $yp_id, $section = NULL) {
        $data = [];
        $match = array('ypc_form_id' => 1);
        $ks_forms = $this->common_model->get_records(YPC_FORM, '', '', '', $match);
        if (!empty($ks_forms)) {
            $data['ks_form_data'] = json_decode($ks_forms[0]['form_json_data'], TRUE);
        }
         // get ypc comments data
            $table = YPC_COMMENTS . ' as com';
            $where = array("com.ypc_id" => $ypc_id, "com.yp_id" => $yp_id);
            $fields = array("com.ypc_comments,com.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
            $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
            

             $table = CONCERNS_SIGNOFF . ' as cs';
            $where = array("l.is_delete" => "0", "cs.yp_id" => $yp_id, "cs.ypc_id" => $ypc_id, "cs.is_delete" => "0");
            $fields = array("cs.created_by,cs.created_date,cs.yp_id,cs.ypc_id, CONCAT(`firstname`,' ', `lastname`) as name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id=cs.created_by');
            $group_by = array('created_by');
            $data['ks_signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);
        //get YP information
        $table = YP_DETAILS . ' as yp';
        $match = array("yp.yp_id" => $yp_id);
        $fields = array("yp.*,pa.placing_authority_id,pa.authority,pa.address_1,pa.town,pa.county,pa.postcode,sd.mobile,sd.email");
        $join_tables = array(PLACING_AUTHORITY . ' as pa' => 'pa.yp_id=yp.yp_id', SOCIAL_WORKER_DETAILS . ' as sd' => 'sd.yp_id=yp.yp_id');
        $data['YP_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', '', '', '', '');

        //get pp yp data
        $match = array('yp_id' => $yp_id, 'ypc_id' => $ypc_id);
        $data['edit_data'] = $this->common_model->get_records(YP_CONCERNS, '', '', '', $match);

        $data['ypid'] = $yp_id;
        $data['main_content'] = '/kspdf';
        $data['section'] = $section;
        $html = $this->parser->parse('layouts/PDFTemplate', $data);
        $pdfFileName = "concerns" . $ypc_id . ".pdf";
        $pdfFilePath = FCPATH . 'uploads/concerns/';
        if (!is_dir(FCPATH . 'uploads/concerns/')) {
            @mkdir(FCPATH . 'uploads/concerns/', 0777, TRUE);
        }
        if (file_exists($pdfFilePath . $pdfFileName)) {
            unlink($pdfFilePath . $pdfFileName);
        }
        //echo $html;exit;
        $this->load->library('m_pdf');

        if ($section == 'StorePDF') {
            ob_clean();
            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($pdfFilePath . $pdfFileName, 'F');
            return 1;
            die;
        } elseif ($section == 'print') {
            echo $html;
            exit;
        } else {
            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($pdfFileName, "D");
        }
    }

    /* function updated by Dhara Bhalala for carehome YP archive */
    public function manager_review($yp_id, $ypc_id) {
        if (!empty($yp_id) && !empty($ypc_id)) {
            $YP_details = $this->common_model->get_records(YP_DETAILS, array("care_home"), '', '', array("yp_id"=>$yp_id));        
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $match = array('yp_id' => $yp_id, 'ypc_id' => $ypc_id, 'created_by' => $login_user_id, 'is_delete' => '0');
            $check_signoff_data = $this->common_model->get_records(CONCERNS_SIGNOFF, array("*"), '', '', $match);
            if (empty($check_signoff_data) > 0) {
                $update_pre_data['care_home_id'] = $YP_details[0]['care_home'];
                $update_pre_data['ypc_id'] = $ypc_id;
                $update_pre_data['yp_id'] = $yp_id;
                $update_pre_data['created_date'] = datetimeformat();
                $update_pre_data['created_by'] = $login_user_id;
                if ($this->common_model->insert(CONCERNS_SIGNOFF, $update_pre_data)) {
                    $msg = $this->lang->line('successfully_concern_review');
                    $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                } else {
                    // error
                    $msg = $this->lang->line('error_msg');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                }
            } else {
                $msg = $this->lang->line('already_concern_review');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
        } else {
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }
        redirect('/' . $this->viewname . '/view/' . $ypc_id . '/' . $yp_id);
    }


    /*
      @Author : Niral Patel
      @Desc   : add commnts
      @Input    : yp id,ypc id
      @Output   :
      @Date   : 27/07/2017
    */
    public function add_commnts() {
        $main_user_data = $this->session->userdata('LOGGED_IN');
        $ypc_id = $this->input->post('ypc_id');
        $yp_id = $this->input->post('yp_id');

        $data = array(
            'ypc_comments' => $this->input->post('ypc_comments'),
            'yp_id' => $this->input->post('yp_id'),
            'ypc_id' => $this->input->post('ypc_id'),
            'created_date' => datetimeformat(),
            'created_by' => $main_user_data['ID'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(YPC_COMMENTS, $data)) {
            $msg = $this->lang->line('comments_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }
        redirect('/' . $this->viewname . '/view/' . $ypc_id . '/' . $yp_id);
    }

    /*
      @Author : Ritesh Ranna
      @Desc   : User List Delete Query
      @Input  : Post id from List page
      @Output : Delete data from database and redirect
      @Date   : 13/06/2017
     */

    public function deletedata($ypc_id, $yp_id) {
        //Delete Record From Database
        if (!empty($ypc_id) && !empty($yp_id)) {
            $data = array('is_delete' => 1);
            $where = array('ypc_id' => $ypc_id, 'yp_id' => $yp_id);

            if ($this->common_model->update(YP_CONCERNS, $data, $where)) {
                //Insert log activity
                  $activity = array(
                    'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
                    'module_name'         => YPC_CONCERNS_MODULE,
                    'module_field_name'   => '',
                    'yp_id'   => $yp_id,
                    'type'                => 3
                  );
                log_activity($activity);
                $msg = $this->lang->line('concern_delete_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                unset($ypc_id);
            } else {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('/' . $this->viewname . '/index/' . $yp_id);
            }
        }
        redirect('/' . $this->viewname . '/index/' . $yp_id);
    }

    //sign off functionality
    /* function updated by Dhara Bhalala for carehome YP archive */
    public function signoff($yp_id = '', $ypc_id = '') {
        $this->formValidation();
        $main_user_data = $this->session->userdata('LOGGED_IN');
        if ($this->form_validation->run() == FALSE) {
            //get YP information
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, array("*"), '', '', array("yp_id" => $yp_id));
            $data['care_home_id'] = $data['YP_details'][0]['care_home'];

            $data['footerJs'][0] = base_url('uploads/custom/js/placementplan/placementplan.js');
            $data['crnt_view'] = $this->viewname;

            $data['ypid'] = $yp_id;
            $data['ypc_id'] = $ypc_id;
            //get social info
            $table = SOCIAL_WORKER_DETAILS . ' as sw';
            $match = array("sw.yp_id" => $yp_id);
            $fields = array("sw.*");
            $data['social_worker_data'] = $this->common_model->get_records($table, $fields, '', '', $match);

            //get parent info
            $table = PARENT_CARER_INFORMATION . ' as pc';
            $match = array("pc.yp_id" => $yp_id, 'pc.is_deleted' => 0);
            $fields = array("pc.*");
            $data['parent_data'] = $this->common_model->get_records($table, $fields, '', '', $match);

            //Get Records From Login Table
            $data['userType'] = getUserType($main_user_data['ROLE_TYPE']);
            $data['initialsId'] = $this->common_model->initialsId();

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
      @Desc   : insert new parent data
      @Input  : Post data
      @Output : insert data in database and redirect
      @Date   : 13/06/2017
     */
    public function insertdata() {
        $postdata = $this->input->post();
        $ypid = $postdata['ypid'];
        $ypc_id = $postdata['ypc_id'];
        $user_type = $postdata['user_type'];
        if ($user_type == 'parent') {
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
                'module_name' => YPC_PARENT_CARER_DETAILS_YP,
                'module_field_name' => '',
                'type' => 1
            );
            log_activity($activity);
        }
        //Current Login detail
        $main_user_data = $this->session->userdata('LOGGED_IN');

        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect('YoungPerson/view/' . $ypid);
        }
        $data['crnt_view'] = $this->viewname;
        $match = array('ypc_form_id' => 1);
        $formsdata = $this->common_model->get_records(YPC_FORM, array("*"), '', '', $match);

        //get pp yp data
        $match = array('yp_id' => $ypid, 'ypc_id' => $ypc_id);
        $ypc_yp_data = $this->common_model->get_records(YP_CONCERNS, array("*"), '', '', $match);

        if (!empty($formsdata) && !empty($ypc_yp_data)) {
            $ypc_form_data = json_decode($formsdata[0]['form_json_data'], TRUE);
            $data = array();
            $i = 0;
            foreach ($ypc_form_data as $row) {
                if (isset($row['name'])) {
                    if ($row['type'] != 'button') {
                        if ($row['type'] == 'checkbox-group') {
                            $ypc_form_data[$i]['value'] = implode(',', $ypc_yp_data[0][$row['name']]);
                            echo $ypc_yp_data[0][$row['name']];
                        } else {
                            $ypc_form_data[$i]['value'] = str_replace("'", '"', $ypc_yp_data[0][$row['name']]);
                        }
                    }
                }
                $i++;
            }
        }
        /* array updated by Dhara Bhalala for adding carehome */
        $data = array(
            'user_type' => ucfirst($postdata['user_type']),
            'yp_id' => ucfirst($postdata['ypid']),
            'ypc_id' => ucfirst($postdata['ypc_id']),
            'form_json_data' => json_encode($ypc_form_data, TRUE),
            'fname' => ucfirst($postdata['fname']),
            'lname' => ucfirst($postdata['lname']),
            'email' => $postdata['email'],
            'care_home_id' => $postdata['care_home_id'],
            'key_data' => md5($postdata['email']),
            'created_date' => datetimeformat(),
            'created_by' => $main_user_data['ID'],
            'updated_by' => $main_user_data['ID'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(NFC_YPC_SIGNOFF_DETAILS, $data)) {

            $signoff_id = $this->db->insert_id();

            $table = CONCERNS_SIGNOFF;
            $where = array("yp_id" => $ypid, 'ypc_id' => $ypc_id, "is_delete" => "0");
            $fields = array("created_by,yp_id,ypc_id,created_date");
            $group_by = array('created_by');
            $signoff_data = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', $group_by, $where);

            if (!empty($signoff_data)) {
                foreach ($signoff_data as $archive_value) {
                    $update_arc_data['approval_ypc_id'] = $signoff_id;
                    $update_arc_data['yp_id'] = $archive_value['yp_id'];
                    $update_arc_data['created_date'] = $archive_value['created_date'];
                    $update_arc_data['created_by'] = $archive_value['created_by'];
                    $this->common_model->insert(NFC_APPROVAL_YPC_SIGNOFF, $update_arc_data);
                }
            }

            $this->sendMailToRelation($data, $signoff_id); // send mail
            //Insert log activity
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($ypid) ? $ypid : '',
                'module_name' => YP_NEW_ADD,
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

        redirect('Concerns/view/' . $ypc_id . '/' . $ypid);
    }

     /*
      @Author : Ritesh Ranna
      @Desc   : send Mail To Relation
      @Input  : data
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
            $loginLink = base_url('Concerns/signoffData/' . $data['yp_id'] . '/' . $signoff_id . '/' . $email);

            $find = array('{NAME}', '{LINK}');

            $replace = array(
                'NAME' => $customerName,
                'LINK' => $loginLink,
            );

            $emailSubject = 'Welcome to NFCTracker';
            $emailBody = '<div>'
                    . '<p>Hello {NAME} ,</p> '
                    . '<p>Please find YPC for '.$yp_name.' for your approval.</p> '
                    . "<p>For security purposes, Please do not forward this email on to any other person. It is for the recipient only and if this is sent in error please advise itsupport@newforestcare.co.uk and delete this email. This link is only valid for ".REPORT_EXPAIRED_HOUR.", should this not be signed off within ".REPORT_EXPAIRED_HOUR." of recieving then please request again</p>"
                    . '<p> <a href="{LINK}">click here</a> </p> '
                    . '<div>';

            $finalEmailBody = str_replace($find, $replace, $emailBody);

            return $this->common_model->sendEmail($toEmailId, $emailSubject, $finalEmailBody, FROM_EMAIL_ID);
        }
        return true;
    }

    /*
      @Author : Ritesh Ranna
      @Desc   : form Validation
      @Input  : data
      @Output : 
      @Date   : 13/06/2017
     */
    public function formValidation($id = null) {
        $this->form_validation->set_rules('fname', 'Firstname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('lname', 'Lastname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
    }

    /*
      @Author : Ritesh Ranna
      @Desc   : view send url to parent
      @Output : 
      @Date   : 13/06/2017
     */
    public function signoffData($yp_id, $id, $email) {
        if (is_numeric($id) && is_numeric($yp_id) && !empty($email)) {
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $match = array('yp_id' => $yp_id, 'ypc_signoff_details_id' => $id, 'key_data' => $email, 'status' => 'inactive');
            $check_signoff_data = $this->common_model->get_records(NFC_YPC_SIGNOFF_DETAILS, array("form_json_data,user_type,ypc_signoff_details_id,created_date,ypc_id,ypc_signoff_details_id"), '', '', $match);
            
            if (!empty($check_signoff_data)) {
                $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'] .REPORT_EXPAIRED_DAYS));
                if (strtotime(datetimeformat()) <= strtotime($expairedDate)) {
                    $data['form_data'] = json_decode($check_signoff_data[0]['form_json_data'], TRUE);
                    //get YP information
                    $match = array("yp_id" => $yp_id);
                    $fields = array("*");
                    $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
                    if (empty($data['YP_details'])) {
                        $msg = $this->lang->line('common_no_record_found');
                        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                        redirect('YoungPerson/view/'.$yp_id);
                    }
                    // get yp comments data
                    $table = YPC_COMMENTS . ' as com';
                    $where = array("com.ypc_id" => $check_signoff_data[0]['ypc_id'], "com.yp_id" => $yp_id);
                    $fields = array("com.ypc_comments,com.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
                    $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
                    $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);

                    // get ypc signoff data
                    $table = NFC_APPROVAL_YPC_SIGNOFF . ' as ras';
                    $where = array("l.is_delete" => "0", "ras.yp_id" => $yp_id, "ras.is_delete" => "0", "approval_ypc_id" => $id);
                    $fields = array("ras.created_by,ras.created_date,ras.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
                    $join_tables = array(LOGIN . ' as l' => 'l.login_id=ras.created_by');
                    $group_by = array('created_by');
                    $data['signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);

                    $data['key_data'] = $email;
                    $data['ypid'] = $yp_id;
                    $data['ypc_id'] = $check_signoff_data[0]['ypc_id'];
                    $data['signoff_id'] = $id;
                    $data['footerJs'][0] = base_url('uploads/custom/js/concerns/concerns.js');
                    $data['crnt_view'] = $this->viewname;
                    $data['header'] = array('menu_module' => 'YoungPerson');
                    $data['main_content'] = '/signoff_view';
                    $this->parser->parse('layouts/DefaultTemplate', $data);
                } else {
                    $msg = lang('link_expired');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>")
                    ;
                    $this->load->view('successfully_message');
                }
            } else {
                $match = array('yp_id'=> $yp_id,'ypc_signoff_details_id'=>$id);
                $check_signoff_data_exist = $this->common_model->get_records(NFC_KS_SIGNOFF_DETAILS,array("*"), '', '', $match);
                
                if(!empty($check_signoff_data_exist))
                {
                   redirect('KeySession/signoffData/' . $yp_id . '/' . $check_signoff_data_exist[0]['ks_signoff_details_id'] . '/' . $email);
                }
                else
                {
                    $msg = $this->lang->line('already_concern_review');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                    $this->load->view('successfully_message');
                }
                
            }
        } else {
            show_404();
        }
    }

    /*
      @Author : Ritesh Ranna
      @Desc   : Send for Signoff / Approval review data
      @Input  : 
      @Output : 
      @Date   : 13/06/2017
     */ 
    public function signoff_review_data($yp_id, $ypc_id, $email) {
        if (!empty($yp_id) && !empty($ypc_id) && !empty($email)) {
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $match = array('yp_id' => $yp_id, 'ypc_signoff_details_id' => $ypc_id, 'key_data' => $email, 'status' => 'inactive');
            $check_signoff_data = $this->common_model->get_records(NFC_YPC_SIGNOFF_DETAILS, array("form_json_data,ypc_signoff_details_id,user_type,created_date"), '', '', $match);
            
            if (!empty($check_signoff_data)) {
                $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'] .REPORT_EXPAIRED_DAYS));

                if (strtotime(datetimeformat()) <= strtotime($expairedDate)) {
                    $u_data['status'] = 'active';
                    $u_data['modified_date'] = datetimeformat();
                    $success = $this->common_model->update(NFC_YPC_SIGNOFF_DETAILS, $u_data, array('ypc_signoff_details_id' => $ypc_id, 'yp_id' => $yp_id, 'key_data' => $email));

                    if ($success) {

                        $msg = $this->lang->line('successfully_concern_review');
                        $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-success text-center'>$msg</div>");
                    } else {
                        // error
                        $msg = $this->lang->line('error_msg');
                        $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>");
                    }
                } else {
                    $msg = lang('link_expired');
                    $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>")
                    ;
                }
            } else {
                $msg = $this->lang->line('already_concern_review');
                $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
        } else {
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
        $postdata = $this->input->post();
        $user_type = !empty($postdata['user_type']) ? $postdata['user_type'] : '';
        $id = $postdata['id'];
        $ypid = $postdata['ypid'];
        $table = YP_DETAILS . ' as yp';
        $match = "yp.yp_id = " . $ypid;
        $fields = array("yp.*,swd.social_worker_firstname,swd.social_worker_surname,swd.landline,swd.mobile,swd.email,swd.senior_social_worker_firstname,swd.senior_social_worker_surname,oyp.pen_portrait,swd.other,pc.firstname,pc.surname,pc.relationship,pc.address,pc.contact_number,pc.email_address,pc.yp_authorised_communication,pc.carer_authorised_communication,pc.comments");
        $join_tables = array(SOCIAL_WORKER_DETAILS . ' as swd' => 'swd.yp_id= yp.yp_id', PARENT_CARER_INFORMATION . ' as pc' => 'pc.yp_id= yp.yp_id', OVERVIEW_OF_YOUNG_PERSON . ' as oyp' => 'oyp.yp_id= yp.yp_id');

        $data['editRecord'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
        if ($user_type == 'parent_data') {

            //get parent info
            $table = PARENT_CARER_INFORMATION . ' as pc';
            $match = array("pc.parent_carer_id" => $id, 'pc.is_deleted' => 0);
            $fields = array("pc.*");
            $data['parent_data'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['editRecord'][0]['fname'] = $data['editRecord'][0]['firstname'];
            $data['editRecord'][0]['lname'] = $data['editRecord'][0]['surname'];
            $data['editRecord'][0]['email_id'] = $data['editRecord'][0]['email_address'];
        } else if ($user_type == 'social_data') {
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
      @Author : Tithi Patel
      @Desc   : get list of external approver
      @Input 	: yp_id, wr_id
      @Output	:
      @Date   : 12/04/2018
     */
    /* function updated by Dhara Bhalala for carehome YP archive */
    public function reportReviewedBy($ypc_id, $yp_id, $care_home_id = 0, $isArchiveCareHomePage = 0) {
        if ($isArchiveCareHomePage !== 0) {
            $temp = $this->common_model->get_records(PAST_CARE_HOME_INFO, array('move_date'), '', '', array("yp_id" => $yp_id, "past_carehome" => $care_home_id));
            $careHomeDetails = $this->common_model->get_records(PAST_CARE_HOME_INFO, array("*"), '', '', array("yp_id" => $yp_id, "move_date <= " => $temp[0]['move_date']));
            $enterDate = $moveDate = '';
            $totalCareHome = count($careHomeDetails);
            if ($totalCareHome >= 1) {
                $enterDate = $careHomeDetails[0]['enter_date'];
                $moveDate = $careHomeDetails[$totalCareHome - 1]['move_date'];
            } elseif ($totalCareHome == 0) {
                $enterDate = $careHomeDetails[0]['enter_date'];
                $moveDate = $careHomeDetails[0]['move_date'];
            }
        }
        if (is_numeric($ypc_id) && is_numeric($yp_id)) {
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($yp_id,$fields);

            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = RECORD_PER_PAGE;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('wr_data');
            }

            $searchsort_session = $this->session->userdata('wr_data');
            
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
                    $sortfield = 'ypc.created_date';
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
            $table = NFC_YPC_SIGNOFF_DETAILS . ' AS ypc';
            $fields = array('ypc.*, CONCAT(l.firstname," ", l.lastname) as sent_by, CONCAT(ypc.fname," ", ypc.lname) as review_by');
            $where = array('ypc.ypc_id' => $ypc_id, 'ypc.yp_id' => $yp_id);
            $join_tables = array(LOGIN . ' as l' => 'l.login_id=ypc.created_by');
            if (!empty($searchtext)) {
                $searchtext = html_entity_decode(trim(addslashes($searchtext)));
                $match = '';
                $where_search = '((l.firstname LIKE "%' . $searchtext . '%" OR l.lastname LIKE "%' . $searchtext . '%" OR ypc.fname LIKE "%' . $searchtext . '%" OR ypc.lname LIKE "%' . $searchtext . '%" OR ypc.created_date LIKE "%' . $searchtext . '%") AND l.is_delete = "0" AND ypc.ypc_id = "' . $ypc_id . '" AND ypc.yp_id = "' . $yp_id . '")';
            }

            if ($isArchiveCareHomePage == 0) {
                $config['base_url'] = base_url() . $this->viewname . '/reportReviewedBy/' . $ypc_id . '/' . $yp_id;

                if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 5;
                    $uri_segment = $this->uri->segment(5);
                }
                
                if (!empty($searchtext)) {                    
                    $data['ypc_signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);

                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1');
                } else {
                    $data['ypc_signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);

                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
                }
            } else {
                $config['base_url'] = base_url() . $this->viewname . '/reportReviewedBy/' . $ypc_id . '/' . $yp_id . '/' . $care_home_id . '/' . $isArchiveCareHomePage;

                if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 7;
                    $uri_segment = $this->uri->segment(7);
                }
                
                $where_date = "ypc.created_date BETWEEN  '" . $enterDate . "' AND '" . $moveDate . "'";
                
                if (!empty($searchtext)) {                    
                    $data['ypc_signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);

                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1');
                } else {
                    $data['ypc_signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);

                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
                }
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
                'total_rows' => $config['total_rows']
            );
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/'.$yp_id);
            }
            $data['ypid'] = $yp_id;
            $data['ypc_id'] = $ypc_id;
            $data['care_home_id'] = $care_home_id;
            $data['isArchiveCareHomePage'] = $isArchiveCareHomePage;
            $data['footerJs'][0] = base_url('uploads/custom/js/concerns/concerns.js');
            $data['crnt_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'YoungPerson');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/reviewed_ajaxlist', $data);
            } else {
                $data['main_content'] = '/reviewed_by';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }
    /* function updated by Dhara Bhalala for carehome YP archive */
    public function reviewedConcern($ypc_id, $yp_id,$signoffid, $care_home_id = 0, $isArchiveCareHomePage = 0) {
        if (is_numeric($ypc_id) && is_numeric($yp_id) && is_numeric($signoffid)) {
             $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
             $data['YP_details'] = YpDetails($yp_id,$fields);

            $table = NFC_YPC_SIGNOFF_DETAILS;
            $fields = array('form_json_data,user_type,key_data,created_date');
            $where = array('ypc_id' => $ypc_id, 'yp_id' => $yp_id);
            $data['external_signoff_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
              
            $table = NFC_APPROVAL_YPC_SIGNOFF . ' as ras';
            $where = array("l.is_delete" => "0", "ras.yp_id" => $yp_id, "ras.is_delete" => "0", "approval_ypc_id" => $signoffid);
            $fields = array("ras.created_by,ras.created_date,ras.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id=ras.created_by');
            $group_by = array('created_by');
            $data['signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);
           
            if (!empty($data['external_signoff_data'])) {
                $data['ypc_form_data'] = json_decode($data['external_signoff_data'][0]['form_json_data'], TRUE);
            }

            $table = YPC_COMMENTS . ' as com';
            $where = array("com.ypc_id" => $ypc_id, "com.yp_id" => $yp_id);
            $fields = array("com.ypc_comments,com.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
            $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
            //check data exist or not
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/'.$yp_id);
            }
            $data['ypid'] = $yp_id;
            $data['ypc_id'] = $ypc_id;
            $data['care_home_id'] = $care_home_id;
            $data['isArchiveCareHomePage'] = $isArchiveCareHomePage;
            $data['footerJs'][0] = base_url('uploads/custom/js/concerns/concerns.js');
            $data['crnt_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/view_reviewed_ypc';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }
    /*
      @Author : Niral Patel
      @Desc   : external approve view data
      @Input  :
      @Output :
      @Date   : 12/04/2018
     */
    
     public function resend_external_approval($signoff_id,$ypid,$ypcid) {
      $match = array('ypc_signoff_details_id'=>$signoff_id);
      $signoff_data = $this->common_model->get_records(NFC_YPC_SIGNOFF_DETAILS,array("yp_id,fname,lname,email,ypc_signoff_details_id"), '', '', $match);
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
          $success =$this->common_model->update(NFC_YPC_SIGNOFF_DETAILS,$u_data,array('ypc_signoff_details_id'=> $signoff_id));
          $msg = $this->lang->line('mail_sent_successfully');
          $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
        else
        {
          $msg = $this->lang->line('error');
          $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
      }

      redirect($this->viewname.'/reportReviewedBy/' . $ypcid.'/'.$ypid);
     }
	 
	 /*
      @Author : Nikunj Ghelani
      @Desc   : PDF data
      @Input  :
      @Output :
      @Date   : 03/07/2018
     */
	 
	 public function DownloadPdf($yp_id,$id) {
			
		  if (is_numeric($id) && is_numeric($yp_id)) {
			
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $match = array('yp_id' => $yp_id, 'ypc_signoff_details_id' => $id,'status' => 'inactive');
            $check_signoff_data = $this->common_model->get_records(NFC_YPC_SIGNOFF_DETAILS, array("form_json_data,created_date,ypc_id"), '', '', $match);

            if (!empty($check_signoff_data)) {
                $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'] .REPORT_EXPAIRED_DAYS));
                if (strtotime(datetimeformat()) <= strtotime($expairedDate)) {
                    $data['form_data'] = json_decode($check_signoff_data[0]['form_json_data'], TRUE);
                    //get YP information
                    $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
                    $data['YP_details'] = YpDetails($yp_id,$fields);
					if (empty($data['YP_details'])) {
                        $msg = $this->lang->line('common_no_record_found');
                        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                        redirect('YoungPerson/view/'.$yp_id);
                    }
                    // get yp comments data
                    $table = YPC_COMMENTS . ' as com';
                    $where = array("com.ypc_id" => $check_signoff_data[0]['ypc_id'], "com.yp_id" => $yp_id);
                    $fields = array("com.ypc_comments,com.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
                    $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
                    $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
    				
                    $table = NFC_APPROVAL_YPC_SIGNOFF . ' as ras';
                    $where = array("l.is_delete" => "0", "ras.yp_id" => $yp_id, "ras.is_delete" => "0", "approval_ypc_id" => $id);
                    $fields = array("ras.created_by,ras.created_date,ras.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
                    $join_tables = array(LOGIN . ' as l' => 'l.login_id=ras.created_by');
                    $group_by = array('created_by');
                    $data['signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);

                    $data['key_data'] = $email;
                    $data['ypid'] = $yp_id;
                    $data['ypc_id'] = $check_signoff_data[0]['ypc_id'];
                    $data['signoff_id'] = $id;
                    $data['footerJs'][0] = base_url('uploads/custom/js/concerns/concerns.js');
                    $data['crnt_view'] = $this->viewname;
                    $pdfFileName = "concerns.pdf";
					$PDFInformation['yp_details'] = $data['YP_details'][0];
					$PDFInformation['edit_data'] = $data['edit_data'][0]['modified_date'];
					$PDFInformation['edit_date'] = $data['YP_details'][0]['modified_date'];
					
					
					$PDFHeaderHTML  = $this->load->view('concerns_pdfHeader', $PDFInformation,true);
					
					$PDFFooterHTML  = $this->load->view('concerns_pdfFooter', $PDFInformation,true);
					
					//die('aa');
					//Set Header Footer and Content For PDF
					$this->m_pdf->pdf->mPDF('utf-8','A4','','','10','10','45','25');
			
					$this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
					$this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);                    
					$data['main_content'] = '/concerns';
					$html = $this->parser->parse('layouts/PdfDataTemplate', $data);
					
					//echo $html;exit;    /*remove*/
					$this->m_pdf->pdf->WriteHTML($html);
					//Store PDF in individual_strategies Folder
					$this->m_pdf->pdf->Output($pdfFileName, "D");
                } else {
                    $msg = lang('link_expired');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>")
                    ;
                    $this->load->view('successfully_message');
                }
            } 
                else
                {
                    $msg = $this->lang->line('already_concern_review');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                    $this->load->view('successfully_message');
                }
                
            
        } else {
            show_404();
        }
		 
		 
	 }

}
