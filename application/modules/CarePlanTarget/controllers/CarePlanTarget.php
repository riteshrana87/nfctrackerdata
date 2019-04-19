<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CarePlanTarget extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session', 'm_pdf'));
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Care Plan Target Index Page
      @Input  : yp id
      @Output :
      @Date   : 19/02/2019
     */

    public function index($id, $care_home_id = 0, $past_care_id = 0) {
        
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


        if (is_numeric($id)) {
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($id, $fields);

            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/' . $id);
            }

            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = 10;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('cpt_data');
            }

            $searchsort_session = $this->session->userdata('cpt_data');
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
                    $sortfield = 'cpt_date';
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

            if ($past_care_id == 0) {

                $config['base_url'] = base_url() . $this->viewname . '/index/' . $id;

                if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 4;
                    $uri_segment = $this->uri->segment(4);
                }

                //Query
                $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
                $table = CAREPLANTARGET . ' as cpt';
                $where = array("cpt.yp_id" => $id, "cpt.is_archive" => 0, "cpt.is_delete" => 0);
                $fields = array("l.login_id", "CONCAT(`firstname`,' ', `lastname`) as name",
                    "l.firstname", "l.lastname", "ch.care_home_name", "cpt.*",
                    "count(cc.cpt_comments_id) as total_comments",
                    "substring_index(GROUP_CONCAT( DISTINCT CONCAT(cc.created_by,'|',cc.created_date,'|',cc.cpt_comments_id) ORDER BY cc.cpt_comments_id DESC SEPARATOR ';'), ';', 1) as Comment_details");
                $join_tables = array(LOGIN . ' as l' => 'l.login_id = cpt.created_by', CARE_HOME . ' as ch' => 'ch.care_home_id = cpt.care_home_id', CPT_COMMENTS . ' as cc' => 'cc.cpt_id = cpt.cpt_id');
                $group_by = "cpt.cpt_id";

                if (!empty($searchtext)) {
                    $searchtext = html_entity_decode(trim(addslashes($searchtext)));
                    $match = '';
                    $where_search = '((CONCAT(`firstname`, \' \', `lastname`) LIKE "%' . $searchtext . '%" OR l.firstname LIKE "%' . $searchtext . '%" OR ch.care_home_name LIKE "%' . $searchtext . '%" OR l.lastname LIKE "%' . $searchtext . '%" OR cpt.cpt_date LIKE "%' . $searchtext . '%" OR l.status LIKE "%' . $searchtext . '%")AND l.is_delete = "0")';
                    $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, $group_by, $where_search);

                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, $group_by, $where_search, '', '', '1');
                } else {
                    $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, $group_by, $where);

                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, $group_by, $where, '', '', '1');
                }

                $loginData = $this->common_model->customQuery("select login_id,CONCAT(`firstname`,' ', `lastname`) as name from nfc_" . LOGIN);
                foreach ($data['edit_data'] as $key => $value) {
                    if ($value['total_comments'] > 0) {
                        $commentPerson = explode('|', $value['Comment_details']);
                        $arrayKey = array_search($commentPerson[0], array_column($loginData, 'login_id'));
                        $data['edit_data'][$key]['last_comment_added_by'] = $loginData[$arrayKey]['name'];
                        $data['edit_data'][$key]['last_comment_added_on'] = $commentPerson[1];
                    }
                }
            } else {
                $config['base_url'] = base_url() . $this->viewname . '/index/' . $id . '/' . $care_home_id . '/' . $past_care_id;

                if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 6;
                    $uri_segment = $this->uri->segment(6);
                }

                //Query
                $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
                $table = CAREPLANTARGET . ' as cpt';
                $where = array("cpt.yp_id" => $id, "cpt.is_archive" => 0, "cpt.is_delete" => 0);
                $where_date = "cpt.created_date BETWEEN  '" . $created_date . "' AND '" . $movedate . "'";
                $fields = array("l.login_id, CONCAT (`firstname`,' ', `lastname`) as name, l.firstname, l.lastname, ch.care_home_name, cpt.*,STR_TO_DATE( cpt.cpt_date , '%d/%m/%Y' ) as date");

                $join_tables = array(LOGIN . ' as l' => 'l.login_id = cpt.created_by', CARE_HOME . ' as ch' => 'ch.care_home_id = cpt.care_home_id');
                if (!empty($searchtext)) {
                    $searchtext = html_entity_decode(trim(addslashes($searchtext)));
                    $match = '';
                    $where_search = '((CONCAT(`firstname`, \' \', `lastname`) LIKE "%' . $searchtext . '%" OR l.firstname LIKE "%' . $searchtext . '%" OR ch.care_home_name LIKE "%' . $searchtext . '%" OR l.lastname LIKE "%' . $searchtext . '%" OR cpt.cpt_date LIKE "%' . $searchtext . '%" OR l.status LIKE "%' . $searchtext . '%")AND l.is_delete = "0")';

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
                'total_rows' => $config['total_rows']);

            $data['ypid'] = $id;
            $data['care_home_id'] = $care_home_id;
            $data['past_care_id'] = $past_care_id;
            //get cpt form
            $match = array('cpt_form_id' => 1);
            $cpt_forms = $this->common_model->get_records(CPT_FORM, '', '', '', $match);
            if (!empty($cpt_forms)) {
                $data['form_data'] = json_decode($cpt_forms[0]['form_json_data'], TRUE);
            }
            $this->session->set_userdata('cpt_data', $sortsearchpage_data);
            setActiveSession('cpt_data'); // set current Session active
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['crnt_view'] = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/careplantarget/careplantarget.js');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/ajaxlist', $data);
            } else {
                $data['main_content'] = '/careplantarget';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }

    
    public function concludeCPT() {
        $postData = $this->input->post();
        $data = array('is_concluded' => 1);
        $this->common_model->update(CAREPLANTARGET, $data, array('cpt_id' => $postData['cpt_id'], 'yp_id' => $postData['yp_id']));
    }

    /*
      @Author : Ritesh Rana
      @Desc   : create care plan target
      @Input  : yp id
      @Output :
      @Date   : 06/03/2019
     */

    public function create($id) {
        if (is_numeric($id)) {
            //get cpt form
            $match = array('cpt_form_id' => 1);
            $cpt_forms = $this->common_model->get_records(CPT_FORM, '', '', '', $match);
            $form_field = array();
            if (!empty($cpt_forms)) {
                $data['cpt_form_data'] = json_decode($cpt_forms[0]['form_json_data'], TRUE);

                foreach ($data['cpt_form_data'] as $form_data) {
                    $form_field[] = $form_data['name'];
                }
            }

            $data['form_field_data_name'] = $form_field;
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($id, $fields);

            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/' . $id);
            }

            $data['care_home_id'] = $data['YP_details'][0]['care_home'];
            $data['ypid'] = $id;
            $data['footerJs'][0] = base_url('uploads/custom/js/careplantarget/careplantarget.js');
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
      @Desc   : edit save as draft functionality
      @Input  : yp id
      @Output :
      @Date   : 13/07/2017
     */

    public function edit_draft($cpt_id, $yp_id) {
        if (is_numeric($cpt_id) && is_numeric($yp_id)) {
            //get cpt form
            $match = array('cpt_form_id' => 1);
            $cpt_forms = $this->common_model->get_records(CPT_FORM, '', '', '', $match);

            if (!empty($cpt_forms)) {
                $data['cpt_form_data'] = json_decode($cpt_forms[0]['form_json_data'], TRUE);

                foreach ($data['cpt_form_data'] as $form_data) {
                    $form_field[] = $form_data['name'];
                }
            }

            $data['form_field_data_name'] = $form_field;

            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($yp_id, $fields);

            //get cpt yp data
            $match = array('cpt_id' => $cpt_id);
            $data['edit_data'] = $this->common_model->get_records(CAREPLANTARGET, '', '', '', $match);

            //check data exist or not
            if (empty($data['YP_details']) || empty($data['edit_data'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/' . $yp_id);
            }
            $data['ypid'] = $yp_id;
            $data['cpt_id'] = $cpt_id;
            $data['footerJs'][0] = base_url('uploads/custom/js/careplantarget/careplantarget.js');
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
      @Desc   : Insert cpt form
      @Input    :
      @Output   :
      @Date   : 02/03/2019
     */

    public function insert() {
        if (!validateFormSecret()) {
            redirect($_SERVER['HTTP_REFERER']);  //Redirect On Listing page
        }
        $postData = $this->input->post();

        $draftdata = (isset($postData['saveAsDraft'])) ? $postData['saveAsDraft'] : '';
        if ($this->input->is_ajax_request()) {
            $draftdata = 1;
        }
        unset($postData['submit_ksform']);
        //get ks form
        $match = array('cpt_form_id' => 1);
        $cpt_forms = $this->common_model->get_records(CPT_FORM, array("*"), '', '', $match);
        if (!empty($cpt_forms)) {
            $cpt_form_data = json_decode($cpt_forms[0]['form_json_data'], TRUE);
            $data = array();
            foreach ($cpt_form_data as $row) {
                if (isset($row['name'])) {
                    if ($row['type'] == 'file') {
                        $filename = $row['name'];
                        //get image previous image
                        $match = array('cpt_id' => $postData['cpt_id'], 'yp_id' => $postData['yp_id']);
                        $cpt_yp_data = $this->common_model->get_records(CAREPLANTARGET, array('`' . $row['name'] . '`'), '', '', $match);
                        //delete img
                        if (!empty($postData['hidden_' . $row['name']])) {
                            $delete_img = explode(',', $postData['hidden_' . $row['name']]);
                            $db_images = explode(',', $cpt_yp_data[0][$filename]);
                            $differentedImage = array_diff($db_images, $delete_img);
                            $cpt_yp_data[0][$filename] = !empty($differentedImage) ? implode(',', $differentedImage) : '';
                            if (!empty($delete_img)) {
                                foreach ($delete_img as $img) {
                                    if (file_exists($this->config->item('cpt_img_url') . $postData['yp_id'] . '/' . $img)) {
                                        unlink($this->config->item('cpt_img_url') . $postData['yp_id'] . '/' . $img);
                                    }
                                    if (file_exists($this->config->item('cpt_img_url_small') . $postData['yp_id'] . '/' . $img)) {
                                        unlink($this->config->item('cpt_img_url_small') . $postData['yp_id'] . '/' . $img);
                                    }
                                }
                            }
                        }

                        if (!empty($_FILES[$filename]['name'][0])) {
                            //create dir and give permission
                            /* common function replaced by Dhara Bhalala on 29/09/2018 */
                            createDirectory(array($this->config->item('cpt_base_url'), $this->config->item('cpt_base_big_url'), $this->config->item('cpt_base_big_url') . '/' . $postData['yp_id']));

                            $file_view = $this->config->item('cpt_img_url') . $postData['yp_id'];
                            //upload big image
                            $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/index/' . $postData['yp_id']);
                            //upload small image
                            $insertImagesData = array();
                            if (!empty($upload_data)) {
                                foreach ($upload_data as $imageFiles) {
                                    /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                    createDirectory(array($this->config->item('cpt_base_small_url'), $this->config->item('cpt_base_small_url') . '/' . $postData['yp_id']));

                                    /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                    if ($imageFiles['is_image'])
                                        $a = do_resize($this->config->item('cpt_img_url') . $postData['yp_id'], $this->config->item('cpt_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                    array_push($insertImagesData, $imageFiles['file_name']);
                                    if (!empty($insertImagesData)) {
                                        $images = implode(',', $insertImagesData);
                                    }
                                }
                                if (!empty($cpt_yp_data[0][$filename])) {
                                    $images .=',' . $cpt_yp_data[0][$filename];
                                }
                                if (!empty($images)) {
                                    $data[$row['name']] = !empty($images) ? $images : '';
                                }
                            }
                        } else {
                            if (!empty($cpt_yp_data[0][$filename])) {
                                $data[$row['name']] = !empty($cpt_yp_data[0][$filename]) ? $cpt_yp_data[0][$filename] : '';
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

        if (!empty($postData['cpt_id'])) {
            //update cpt data
            $data['draft'] = $draftdata;
            $data['cpt_id'] = $postData['cpt_id'];
            $data['yp_id'] = $postData['yp_id'];
            $data['modified_date'] = datetimeformat();
            $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            $this->common_model->update(CAREPLANTARGET, $data, array('cpt_id' => $postData['cpt_id']));
            //Insert log activity
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                'module_name' => CPT_MODULE,
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
                $this->common_model->insert(CAREPLANTARGET, $data);
                $data['cpt_id'] = $this->db->insert_id();
                //Insert log activity
                $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                    'module_name' => CPT_MODULE,
                    'module_field_name' => '',
                    'type' => 1
                );
                log_activity($activity);
            }
        }
        if (!empty($data)) {
            redirect('/' . $this->viewname . '/save_cpt/' . $data['cpt_id'] . '/' . $data['yp_id']);
        } else {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>Please  insert key session details.</div>");
            redirect('/' . $this->viewname . '/create/' . $postData['yp_id']);
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Save cpt form
      @Input    :
      @Output   :
      @Date   : 28/02/2019
     */

    public function save_cpt($cpt_id, $yp_id) {
        if (is_numeric($cpt_id) && is_numeric($yp_id)) {
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($yp_id, $fields);

            //get cpt yp data
            $match = array('cpt_id' => $cpt_id);
            $data['edit_data'] = $this->common_model->get_records(CAREPLANTARGET, array("*"), '', '', $match);
            //check data exist or not
            if (empty($data['YP_details']) || empty($data['edit_data'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/' . $yp_id);
            }
            $data['yp_id'] = $yp_id;
            $data['cpt_id'] = $cpt_id;
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/save_cpt';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : view cpt form
      @Input    :
      @Output   :
      @Date   : 04/03/2019
     */

    public function view($cpt_id, $yp_id, $care_home_id = 0, $past_care_id = 0) {
        if (is_numeric($cpt_id) && is_numeric($yp_id)) {
            //get cpt form
            $match = array('cpt_form_id' => 1);
            $cpt_forms = $this->common_model->get_records(CPT_FORM, '', '', '', $match);
            if (!empty($cpt_forms)) {
                $data['cpt_form_data'] = json_decode($cpt_forms[0]['form_json_data'], TRUE);
            }
            // get cpt comments data
            $table = CPT_COMMENTS . ' as com';
            $where = array("com.cpt_id" => $cpt_id, "com.yp_id" => $yp_id);
            $fields = array("com.cpt_comments,com.cpt_attacchment,com.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
            $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
            //get YP information

            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($yp_id, $fields);

            //get cpt yp data
            $match = array('cpt_id' => $cpt_id);
            $data['edit_data'] = $this->common_model->get_records(CAREPLANTARGET, array("*"), '', '', $match);

            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $table = CPT_SIGNOFF . ' as cs';
            $where = array("l.is_delete" => "0", "cs.yp_id" => $yp_id, "cs.cpt_id" => $cpt_id, "cs.is_delete" => "0");
            $fields = array("cs.created_by,cs.created_date,cs.yp_id,cs.cpt_id, CONCAT(`firstname`,' ', `lastname`) as name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id = cs.created_by');
            $group_by = array('created_by');
            $data['cpt_signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);

            //check cpt signoff data exist or not
            $table = CPT_SIGNOFF . ' as cs';
            $where = array("cs.yp_id" => $yp_id, "cs.created_by" => $login_user_id, "cs.is_delete" => "0", "cs.cpt_id" => $cpt_id);
            $fields = array("cs.cpt_id,cs.yp_id,cs.created_by,cs.created_date,cs.care_home_id");
            $data['check_cpt_signoff_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

            //check cpt external approval data exist or not
            $table = CPT_SIGNOFF_DETAILS;
            $fields = array('form_json_data,cpt_id,yp_id,cpt_signoff_details_id,user_type');
            $where = array('cpt_id' => $cpt_id, 'yp_id' => $yp_id);
            $data['check_external_signoff_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

            //check data exist or not
            if (empty($data['YP_details']) || empty($data['edit_data'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/' . $yp_id);
            }
            $data['care_home_id'] = $care_home_id;
            $data['past_care_id'] = $past_care_id;
            $data['ypid'] = $yp_id;
            $data['cpt_id'] = $cpt_id;
            $data['footerJs'][0] = base_url('uploads/custom/js/careplantarget/careplantarget.js');
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
      @Desc   : Read more
      @Input    : yp id
      @Output   :
      @Date   : 27/07/2017
     */

    public function readmore($id, $field) {
        $params['fields'] = [$field];
        $params['table'] = CAREPLANTARGET;
        $params['match_and'] = 'cpt_id=' . $id . '';
        $data['documents'] = $this->common_model->get_records_array($params);
        $data['field'] = $field;
        $this->load->view($this->viewname . '/readmore', $data);
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Download Print functionality
      @Input    : yp id,cpt_id
      @Output   :
      @Date   : 27/07/2017
     */

    public function DownloadPrint($cpt_id, $yp_id, $section = NULL) {
        $data = [];
        $match = array('cpt_form_id' => 1);
        $cpt_forms = $this->common_model->get_records(CPT_FORM, '', '', '', $match);
        if (!empty($cpt_forms)) {
            $data['cpt_form_data'] = json_decode($cpt_forms[0]['form_json_data'], TRUE);
        }

        // get cpt comments data
        $table = CPT_COMMENTS . ' as com';
        $where = array("com.cpt_id" => $cpt_id, "com.yp_id" => $yp_id);
        $fields = array("com.cpt_comments,com.cpt_attacchment,com.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
        $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);


        // get Cpt signoff data    
        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = CPT_SIGNOFF . ' as cs';
        $where = array("l.is_delete" => "0", "cs.yp_id" => $yp_id, "cs.cpt_id" => $cpt_id, "cs.is_delete" => "0");
        $fields = array("cs.created_by,cs.created_date,cs.yp_id,cs.cpt_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id=cs.created_by');
        $group_by = array('created_by');
        $data['cpt_signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);


        //get YP information
        $table = YP_DETAILS . ' as yp';
        $match = array("yp.yp_id" => $yp_id);
        $fields = array("yp.yp_fname,yp.yp_lname,pa.placing_authority_id,pa.authority,pa.address_1,pa.town,pa.county,pa.postcode,sd.mobile,sd.email");
        $join_tables = array(PLACING_AUTHORITY . ' as pa' => 'pa.yp_id=yp.yp_id', SOCIAL_WORKER_DETAILS . ' as sd' => 'sd.yp_id=yp.yp_id');
        $data['YP_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match, '', '', '', '', '', '', '');

        //get cpt yp data
        $match = array('yp_id' => $yp_id, 'cpt_id' => $cpt_id);
        $data['edit_data'] = $this->common_model->get_records(CAREPLANTARGET, '', '', '', $match);

        $data['ypid'] = $yp_id;

        $data['main_content'] = '/cptpdf';
        $data['section'] = $section;
        $html = $this->parser->parse('layouts/PDFTemplate', $data);
        $pdfFileName = "cpt" . $cpt_id . ".pdf";
        $pdfFilePath = FCPATH . 'uploads/cpt/';
        if (!is_dir(FCPATH . 'uploads/cpt/')) {
            @mkdir(FCPATH . 'uploads/cpt/', 0777, TRUE);
        }
        if (file_exists($pdfFilePath . $pdfFileName)) {
            unlink($pdfFilePath . $pdfFileName);
        }

        $this->load->library('m_pdf');
        if ($section == 'StorePDF') {
            //create pdf functionality
            ob_clean();
            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($pdfFilePath . $pdfFileName, 'F');
            return 1;
            die;
        } elseif ($section == 'print') {
            //create print functionality
            echo $html;
            exit;
        } else {
            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($pdfFileName, "D");
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : add new user signoff data
      @Input    : yp id,cpt_id
      @Output   :
      @Date   : 27/07/2017
     */

    public function manager_review($yp_id, $cpt_id) {
        if (!empty($yp_id) && !empty($cpt_id)) {
            /* added by Ritesh Ranan on 28/09/2018 to archive functionality */

            //get YP information
            $fields = array("care_home");
            $YP_details = YpDetails($yp_id, $fields);
            //check data exist or not
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $match = array('yp_id' => $yp_id, 'cpt_id' => $cpt_id, 'created_by' => $login_user_id, 'is_delete' => '0');
            $check_signoff_data = $this->common_model->get_records(CPT_SIGNOFF, array("*"), '', '', $match);

            if (empty($check_signoff_data) > 0) {
                $update_pre_data['care_home_id'] = $YP_details[0]['care_home'];
                $update_pre_data['cpt_id'] = $cpt_id;
                $update_pre_data['yp_id'] = $yp_id;
                $update_pre_data['created_date'] = datetimeformat();
                $update_pre_data['created_by'] = $login_user_id;

                // insert user signoff data  
                if ($this->common_model->insert(CPT_SIGNOFF, $update_pre_data)) {
                    $msg = $this->lang->line('successfully_cpt_review');
                    $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                } else {
                    // error
                    $msg = $this->lang->line('error_msg');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                }
            } else {
                $msg = $this->lang->line('already_cpt_review');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
        } else {
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }
        redirect('/' . $this->viewname . '/view/' . $cpt_id . '/' . $yp_id);
    }

    /*
      @Author : Ritesh Rana
      @Desc   : add new commnts functionality
      @Input    : post data
      @Output   :
      @Date   : 27/07/2017
     */

    public function add_commnts() {
        $main_user_data = $this->session->userdata('LOGGED_IN');
        $cpt_id = $this->input->post('cpt_id');
        $yp_id = $this->input->post('yp_id');
        $postData = $this->input->post();

                $imagesDoc = '';
                $filename_doc = 'cpt_attacchment';
                if (!empty($_FILES[$filename_doc]['name'][0])) {
                    createDirectory(array($this->config->item('cpt_base_url'), $this->config->item('cpt_base_big_url'), $this->config->item('cpt_base_big_url') . '/' . $yp_id));

                    $file_view = $this->config->item('cpt_img_url') . $yp_id;
                    //upload big image
                     $upload_data = uploadImage($filename_doc, $file_view, '/' . $this->viewname . '/index/' . $postData['yp_id']);
                    //upload small image
                    $insertImagesDataDoc = array();
                    if (!empty($upload_data)) {
                        foreach ($upload_data as $imageFiles) {
                            createDirectory(array($this->config->item('cpt_base_small_url'), $this->config->item('cpt_base_small_url') . '/' . $postData['yp_id']));
                            if ($imageFiles['is_image']) {
                                $a = do_resize($this->config->item('cpt_img_url') . $postData['yp_id'], $this->config->item('cpt_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                            }

                            array_push($insertImagesDataDoc, $imageFiles['file_name']);
                            if (!empty($insertImagesDataDoc)) {
                                $imagesDoc = implode(',', $insertImagesDataDoc);
                            }
                        }
                        
                    }
                }

        $data = array(
            'cpt_attacchment' => $imagesDoc,    
            'cpt_comments' => $this->input->post('cpt_comments'),
            'yp_id' => $this->input->post('yp_id'),
            'cpt_id' => $this->input->post('cpt_id'),
            'created_date' => datetimeformat(),
            'created_by' => $main_user_data['ID'],
        );
        //Insert Record in Database
        if ($this->common_model->insert(CPT_COMMENTS, $data)) {
            $msg = $this->lang->line('comments_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            // redirect($this->viewname);
        }
        redirect('/' . $this->viewname . '/view/' . $cpt_id . '/' . $yp_id);
    }

    /*
      @Author : Ritesh Ranna
      @Desc   : User List Delete Query
      @Input  : Post id from List page
      @Output : Delete data from database and redirect
      @Date   : 13/06/2017
     */

    public function deletedata($cpt_id, $yp_id) {
        //Delete Record From Database
        if (!empty($cpt_id) && !empty($yp_id)) {
            $data = array('is_delete' => 1);
            $where = array('cpt_id' => $cpt_id, 'yp_id' => $yp_id);

            if ($this->common_model->update(CAREPLANTARGET, $data, $where)) {
                //Insert log activity
                $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'module_name' => CPT_MODULE,
                    'module_field_name' => '',
                    'yp_id' => $yp_id,
                    'type' => 3
                );
                log_activity($activity);
                $msg = $this->lang->line('cpt_delete_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                unset($cpt_id);
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
      @Desc   : user send to parent/social worker url sign off functionality
      @Input  : Post id from List page
      @Output : Delete data from database and redirect
      @Date   : 13/06/2017
     */

    public function signoff($yp_id = '', $cpt_id = '') {
        $this->formValidation();

        $main_user_data = $this->session->userdata('LOGGED_IN');
        if ($this->form_validation->run() == FALSE) {

            //get YP information

            $fields = array("care_home");
            $data['YP_details'] = YpDetails($yp_id, $fields);

            $data['care_home_id'] = $data['YP_details'][0]['care_home'];

            $data['footerJs'][0] = base_url('uploads/custom/js/careplantarget/careplantarget.js');
            $data['crnt_view'] = $this->viewname;

            $data['ypid'] = $yp_id;
            $data['cpt_id'] = $cpt_id;
            //get social info
            $table = SOCIAL_WORKER_DETAILS . ' as sw';
            $match = array("sw.yp_id" => $yp_id);
            $fields = array("sw.social_worker_id,sw.social_worker_firstname,sw.social_worker_surname");
            $data['social_worker_data'] = $this->common_model->get_records($table, $fields, '', '', $match);

            //get parent info
            $table = PARENT_CARER_INFORMATION . ' as pc';
            $match = array("pc.yp_id" => $yp_id, 'pc.is_deleted' => 0);
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
      @Desc   : insert data
      @Input  : Post id from List page
      @Output :
      @Date   : 13/06/2017
     */

    public function insertdata() {
        $postdata = $this->input->post();
        $ypid = $postdata['ypid'];
        $cpt_id = $postdata['cpt_id'];
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
                'module_name' => CPT_PARENT_CARER_DETAILS_YP,
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

        $match = array('cpt_form_id' => 1);
        $formsdata = $this->common_model->get_records(CPT_FORM, array("*"), '', '', $match);

        //get pp yp data
        $match = array('yp_id' => $ypid, 'cpt_id' => $cpt_id);
        $ks_yp_data = $this->common_model->get_records(CAREPLANTARGET, array("*"), '', '', $match);

        if (!empty($formsdata) && !empty($ks_yp_data)) {
            $cpt_form_data = json_decode($formsdata[0]['form_json_data'], TRUE);
            $data = array();
            $i = 0;
            foreach ($cpt_form_data as $row) {
                if (isset($row['name'])) {
                    if ($row['type'] != 'button') {
                        if ($row['type'] == 'checkbox-group') {
                            $cpt_form_data[$i]['value'] = implode(',', $ks_yp_data[0][$row['name']]);
                            echo $ks_yp_data[0][$row['name']];
                        } else {
                            $cpt_form_data[$i]['value'] = str_replace("'", '"', $ks_yp_data[0][$row['name']]);
                        }
                    }
                }
                $i++;
            }
        }

        $data = array(
            'user_type' => ucfirst($postdata['user_type']),
            'yp_id' => ucfirst($postdata['ypid']),
            'cpt_id' => ucfirst($postdata['cpt_id']),
            'form_json_data' => json_encode($cpt_form_data, TRUE),
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
        if ($this->common_model->insert(CPT_SIGNOFF_DETAILS, $data)) {

            $signoff_id = $this->db->insert_id();

            $table = CPT_SIGNOFF;
            $where = array("yp_id" => $ypid, "cpt_id" => $cpt_id, "is_delete" => "0");
            $fields = array("created_by,yp_id,cpt_id,created_date");
            $group_by = array('created_by');
            $signoff_data = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', $group_by, $where);

            if (!empty($signoff_data)) {
                foreach ($signoff_data as $archive_value) {
                    $update_arc_data['approval_cpt_id'] = $signoff_id;
                    $update_arc_data['yp_id'] = $archive_value['yp_id'];
                    $update_arc_data['created_date'] = $archive_value['created_date'];
                    $update_arc_data['created_by'] = $archive_value['created_by'];
                    $this->common_model->insert(APPROVAL_CPT_SIGNOFF, $update_arc_data);
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
        redirect('CarePlanTarget/view/' . $cpt_id . '/' . $ypid);
    }

    /*
      @Author : Ritesh Ranna
      @Desc   : send Mail To Relation
      @Input  :
      @Output :
      @Date   : 13/06/2017
     */

    private function sendMailToRelation($data = array(), $signoff_id) {

        if (!empty($data)) {
            if (!empty($data['yp_id'])) {
                $match = array("yp_id" => $data['yp_id']);
                $fields = array("concat(yp_fname,' ',yp_lname) as yp_name");
                $YP_details = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            }
            $yp_name = !empty($YP_details[0]['yp_name']) ? $YP_details[0]['yp_name'] : "";
            /* Send Created Customer password with Link */
            $toEmailId = $data['email'];
            $customerName = $data['fname'] . ' ' . $data['lname'];

            $email = md5($toEmailId);
            $loginLink = base_url('CarePlanTarget/signoffData/' . $data['yp_id'] . '/' . $signoff_id . '/' . $email);

            $find = array('{NAME}', '{LINK}');

            $replace = array(
                'NAME' => $customerName,
                'LINK' => $loginLink,
            );

            $emailSubject = 'Welcome to NFCTracker';
            $emailBody = '<div>'
                    . '<p>Hello {NAME} ,</p> '
                    . '<p>Please find Care Plan Target for ' . $yp_name . ' for your approval.</p> '
                    . "<p>For security purposes, Please do not forward this email on to any other person. It is for the recipient only and if this is sent in error please advise itsupport@newforestcare.co.uk and delete this email. This link is only valid for " . REPORT_EXPAIRED_HOUR . ", should this not be signed off within " . REPORT_EXPAIRED_HOUR . " of recieving then please request again</p>"
                    . '<p> <a href="{LINK}">click here</a> </p> '
                    . '<div>';

            $finalEmailBody = str_replace($find, $replace, $emailBody);

            return $this->common_model->sendEmail($toEmailId, $emailSubject, $finalEmailBody, FROM_EMAIL_ID);
        }
        return true;
    }

    /*
      @Author : Ritesh Ranna
      @Desc   : Validation
      @Input  :
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
      @Desc   : view link for send to parent and social worker
      @Input  :
      @Output :
      @Date   : 13/06/2017
     */

    public function signoffData($yp_id, $id, $email) {
        if (is_numeric($id) && is_numeric($yp_id) && !empty($email)) {
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $match = array('yp_id' => $yp_id, 'cpt_signoff_details_id' => $id, 'key_data' => $email, 'status' => 'inactive');
            $check_signoff_data = $this->common_model->get_records(CPT_SIGNOFF_DETAILS, array("created_date,form_json_data,cpt_id"), '', '', $match);
            // set link expiry time 
                $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'] . REPORT_EXPAIRED_DAYS));
                if (strtotime(datetimeformat()) <= strtotime($expairedDate)) {
                    $data['form_data'] = json_decode($check_signoff_data[0]['form_json_data'], TRUE);
                    //get YP information
                    $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
                    $data['YP_details'] = YpDetails($yp_id, $fields);

                    if (empty($data['YP_details'])) {
                        $msg = $this->lang->line('common_no_record_found');
                        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                        redirect('YoungPerson/view/' . $ypid);
                    }

                    $table = APPROVAL_CPT_SIGNOFF . ' as ras';
                    $where = array("l.is_delete" => "0", "ras.yp_id" => $yp_id, "ras.is_delete" => "0", "approval_cpt_id" => $id);
                    $fields = array("ras.created_by,ras.created_date,ras.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
                    $join_tables = array(LOGIN . ' as l' => 'l.login_id=ras.created_by');
                    $group_by = array('created_by');
                    $data['signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);


                    // get cpt comments data
                    $table = CPT_COMMENTS . ' as com';
                    $where = array("com.cpt_id" => $check_signoff_data[0]['cpt_id'], "com.yp_id" => $yp_id);
                    $fields = array("com.cpt_comments,com.cpt_attacchment,com.created_date,com.cpt_comments_id,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
                    $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
                    $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);

                    $data['key_data'] = $email;
                    $data['ypid'] = $yp_id;
                    $data['cpt_id'] = $check_signoff_data[0]['cpt_id'];
                    $data['signoff_id'] = $id;
                    $data['footerJs'][0] = base_url('uploads/custom/js/careplantarget/careplantarget.js');
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

    public function signoff_review_data($yp_id, $cpt_id, $email) {
        if (!empty($yp_id) && !empty($cpt_id) && !empty($email)) {
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $match = array('yp_id' => $yp_id, 'cpt_signoff_details_id' => $cpt_id, 'key_data' => $email, 'status' => 'inactive');
            $check_signoff_data = $this->common_model->get_records(CPT_SIGNOFF_DETAILS, array("created_date,form_json_data,cpt_id"), '', '', $match);

            if (!empty($check_signoff_data)) {
                $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'] . REPORT_EXPAIRED_DAYS));
                if (strtotime(datetimeformat()) <= strtotime($expairedDate)) {
                    $u_data['status'] = 'active';
                    $u_data['modified_date'] = datetimeformat();
                    $success = $this->common_model->update(CPT_SIGNOFF_DETAILS, $u_data, array('cpt_signoff_details_id' => $cpt_id, 'yp_id' => $yp_id, 'key_data' => $email));
                    if ($success) {

                        $msg = $this->lang->line('successfully_cpt_review');
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
                $msg = $this->lang->line('already_cpt_review');
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

    public function getUserTypeDetail(){
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
      @Author : Niral Patel
      @Desc   : view cpt external approval list
      @Input  :
      @Output :
      @Date   : 21/02/2019
     */

    public function external_approval_list($ypid, $cpt_id, $care_home_id = 0, $past_care_id = 0) {
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

        if (is_numeric($ypid) && is_numeric($cpt_id)) {
            //get YP information 
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($ypid, $fields);

            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/' . $ypid);
            }

            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = 10;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('cpt_approval_session_data');
            }

            $searchsort_session = $this->session->userdata('cpt_approval_session_data');
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
                    $sortfield = 'cpt_signoff_details_id';
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
            if ($past_care_id == 0) {
                $config['base_url'] = base_url() . $this->viewname . '/external_approval_list/' . $ypid . '/' . $cpt_id;

                if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 5;
                    $uri_segment = $this->uri->segment(5);
                }
                //Query

                $table = CPT_SIGNOFF_DETAILS . ' as csd';
                $where = array("csd.yp_id" => $ypid, "csd.cpt_id" => $cpt_id);
                $fields = array("csd.*,CONCAT(`firstname`,' ', `lastname`) as create_name ,CONCAT(`fname`,' ', `lname`) as user_name,ch.care_home_name");
                $join_tables = array(LOGIN . ' as l' => 'l.login_id= csd.created_by', CARE_HOME . ' as ch' => 'ch.care_home_id = csd.care_home_id');
                if (!empty($searchtext)) {
                    
                } else {
                    $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);

                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
                }
            } else {
                $config['base_url'] = base_url() . $this->viewname . '/external_approval_list/' . $ypid . '/' . $cpt_id . '/' . $care_home_id . '/' . $past_care_id;

                if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 7;
                    $uri_segment = $this->uri->segment(7);
                }
                //Query

                $table = CPT_SIGNOFF_DETAILS . ' as csd';
                $where = array("csd.yp_id" => $ypid, "csd.cpt_id" => $cpt_id);
                $where_date = "csd.created_date BETWEEN  '" . $created_date . "' AND '" . $movedate . "'";
                $fields = array("csd.*,CONCAT(`firstname`,' ', `lastname`) as create_name ,CONCAT(`fname`,' ', `lname`) as user_name,ch.care_home_name");
                $join_tables = array(LOGIN . ' as l' => 'l.login_id= csd.created_by', CARE_HOME . ' as ch' => 'ch.care_home_id = csd.care_home_id');
                if (!empty($searchtext)) {
                    
                } else {
                    $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where, '', '', '', '', '', $where_date);

                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1', '', '', $where_date);
                }
            }

            $data['care_home_id'] = $care_home_id;
            $data['past_care_id'] = $past_care_id;
            $data['ypid'] = $ypid;
            $data['cpt_id'] = $cpt_id;

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

            $this->session->set_userdata('cpt_approval_session_data', $sortsearchpage_data);
            setActiveSession('cpt_approval_session_data'); // set current Session active
            $data['header'] = array('menu_module' => 'Communication');

            //get communication form
            $data['crnt_view'] = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/careplantarget/careplantarget.js');
            $data['header'] = array('menu_module' => 'YoungPerson');

            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/approval_ajaxlist', $data);
            } else {
                $data['main_content'] = '/cpt_list';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
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

    public function viewApprovalCpt($id, $ypid, $care_home_id = 0, $past_care_id = 0) {
        if (is_numeric($id) && is_numeric($ypid)) {
            //get archive cpt data
            $match = array('cpt_signoff_details_id' => $id);
            $formsdata = $this->common_model->get_records(CPT_SIGNOFF_DETAILS, '', '', '', $match);

            $data['formsdata'] = $formsdata;
            if (!empty($formsdata)) {
                $data['cpt_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }

            //get YP information

            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($ypid, $fields);
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/' . $ypid);
            }

            $table = APPROVAL_CPT_SIGNOFF . ' as acpt';
            $where = array("l.is_delete" => "0", "acpt.yp_id" => $ypid, "acpt.is_delete" => "0", "approval_cpt_id" => $id);
            $fields = array("acpt.created_by,acpt.created_date,acpt.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id=acpt.created_by');
            $group_by = array('created_by');
            $data['signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);


            $table = CPT_COMMENTS . ' as com';
            $where = array("com.cpt_id" => $formsdata[0]['cpt_id'], "com.yp_id" => $ypid);
            $fields = array("com.cpt_comments,com.cpt_attacchment,com.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
            $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);

            $data['ypid'] = $ypid;
            $data['care_home_id'] = $care_home_id;
            $data['past_care_id'] = $past_care_id;
            $data['cpt_id'] = $formsdata[0]['cpt_id'];

            $data['footerJs'][0] = base_url('uploads/custom/js/careplantarget/careplantarget.js');
            $data['crnt_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/approval_view';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : resend external approve data
      @Input  :
      @Output :
      @Date   : 01/03/2019
     */

    public function resend_external_approval($signoff_id, $ypid, $cptid) {
        $match = array('cpt_signoff_details_id' => $signoff_id);
        $signoff_data = $this->common_model->get_records(CPT_SIGNOFF_DETAILS, array("yp_id,fname,lname,email"), '', '', $match);

        if (!empty($signoff_data)) {
            $data = array(
                'yp_id' => ucfirst($signoff_data[0]['yp_id']),
                'fname' => ucfirst($signoff_data[0]['fname']),
                'lname' => ucfirst($signoff_data[0]['lname']),
                'email' => $signoff_data[0]['email']
            );
            $sent = $this->sendMailToRelation($data, $signoff_id); // send mail
            if ($sent) {
                $u_data['created_date'] = datetimeformat();
                $u_data['modified_date'] = NULL;
                $success = $this->common_model->update(CPT_SIGNOFF_DETAILS, $u_data, array('cpt_signoff_details_id' => $signoff_id));
                $msg = $this->lang->line('mail_sent_successfully');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            }
        }

        redirect($this->viewname . '/external_approval_list/' . $ypid . '/' . $cptid);
    }

    /*
      @Author : Ritesh Rana
      @Desc   : PDF data
      @Input  :
      @Output :
      @Date   : 27/02/2019
     */

    public function DownloadPdf($id, $signoff_id) {

        if (is_numeric($id) && is_numeric($signoff_id)) {
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $match = array('yp_id' => $id, 'cpt_signoff_details_id' => $signoff_id, 'status' => 'inactive');
            $check_signoff_data = $this->common_model->get_records(CPT_SIGNOFF_DETAILS, array("form_json_data,cpt_signoff_details_id,cpt_id,user_type,created_date,cpt_id"), '', '', $match);

            if (!empty($check_signoff_data)) {
                $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'] . REPORT_EXPAIRED_DAYS));
                if (strtotime(datetimeformat()) <= strtotime($expairedDate)) {
                    $data['form_data'] = json_decode($check_signoff_data[0]['form_json_data'], TRUE);
                    //get YP information
                    $fields = array("yp_fname,yp_lname,date_of_birth");

                    $data['YP_details'] = YpDetails($id, $fields);

                    if (empty($data['YP_details'])) {
                        $msg = $this->lang->line('common_no_record_found');
                        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                        redirect('YoungPerson/view/' . $ypid);
                    }

                    $table = APPROVAL_CPT_SIGNOFF . ' as ras';
                    $where = array("l.is_delete" => "0", "ras.yp_id" => $id, "ras.is_delete" => "0", "approval_cpt_id" => $signoff_id);
                    $fields = array("ras.created_by,ras.created_date,ras.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
                    $join_tables = array(LOGIN . ' as l' => 'l.login_id=ras.created_by');
                    $group_by = array('created_by');
                    $data['signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);

                    // get Cpt comments data
                    $table = CPT_COMMENTS . ' as com';
                    $where = array("com.cpt_id" => $check_signoff_data[0]['cpt_id'], "com.yp_id" => $id);
                    $fields = array("com.cpt_comments,com.cpt_attacchment,com.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
                    $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
                    $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
                    $data['key_data'] = $email;
                    $data['ypid'] = $id;
                    $data['cpt_id'] = $check_signoff_data[0]['cpt_id'];
                    $data['signoff_id'] = $signoff_id;
                    $data['footerJs'][0] = base_url('uploads/custom/js/careplantarget/careplantarget.js');
                    $data['crnt_view'] = $this->viewname;
                    $pdfFileName = "cpt.pdf";
                    $PDFInformation['yp_details'] = $data['YP_details'][0];
                    $PDFInformation['edit_data'] = $data['edit_data'][0]['modified_date'];


                    $PDFHeaderHTML = $this->load->view('cpt_pdfHeader', $PDFInformation, true);

                    $PDFFooterHTML = $this->load->view('cpt_pdfFooter', $PDFInformation, true);

                    //Set Header Footer and Content For PDF
                    $this->m_pdf->pdf->mPDF('utf-8', 'A4', '', '', '10', '10', '45', '25');

                    $this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
                    $this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);
                    $data['main_content'] = '/cpt';
                    $html = $this->parser->parse('layouts/PdfDataTemplate', $data);

                    /* remove */
                    $this->m_pdf->pdf->WriteHTML($html);
                    //Store PDF in individual_strategies Folder
                    $this->m_pdf->pdf->Output($pdfFileName, "D");
                } else {
                    $msg = lang('link_expired');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                    $this->load->view('successfully_message');
                }
            } else {
                $msg = $this->lang->line('already_cpt_review');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                $this->load->view('successfully_message');
            }
        } else {
            show_404();
        }
    }

}
