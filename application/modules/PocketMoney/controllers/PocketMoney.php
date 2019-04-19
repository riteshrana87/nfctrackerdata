<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PocketMoney extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Niral Patel
      @Desc   : Clothing Money Index Page
      @Input  : yp id
      @Output :
      @Date   : 04/05/2018
     */

    public function index($id, $careHomeId = 0, $isArchive = 0) {
        /* for care to care data by ghelani nikunj on 18/9/2018 for care to  care data get with the all previous care home */
        if ($isArchive !== 0) {
            $temp = $this->common_model->get_records(PAST_CARE_HOME_INFO, array('move_date'), '', '', array("yp_id" => $id, "past_carehome" => $careHomeId));
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
            $match = array("yp_id" => $id);
            $fields = array("yp_id,care_home,yp_fname,yp_lname,date_of_birth");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
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
                $this->session->unset_userdata('pm_data');
            }

            $searchsort_session = $this->session->userdata('pm_data');
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
                    $sortfield = 'pocket_money_id';
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
            if ($isArchive == 0) {
                //need to change when client will approved functionality
                $config['base_url'] = base_url() . $this->viewname . '/index/' . $id;
                if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 4;
                    $uri_segment = $this->uri->segment(4);
                }
            } else {
                $config['base_url'] = base_url() . $this->viewname . '/index/' . $id . '/' . $careHomeId . '/' . $isArchive;
                if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 6;
                    $uri_segment = $this->uri->segment(6);
                }
            }

            //Query
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $table = POCKET_MONEY . ' as ks';
            if ($isArchive == 0) {
                $where = array("ks.yp_id" => $id, "ks.is_deleted" => 0);
                //$where = array("ks.yp_id"=> $id,"ks.is_archive"=> 0,"ks.created_by"=> $login_user_id,"ks.is_delete"=> 0);
                $fields = array("c.care_home_name,l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname, ks.*");

                $join_tables = array(LOGIN . ' as l' => 'l.login_id = ks.created_by', CARE_HOME . ' as c' => 'c.care_home_id= ks.care_home_id');
                if (!empty($searchtext)) {
                    $searchtext = html_entity_decode(trim(addslashes($searchtext)));
                    $match = array("ks.is_deleted" => 0);
                    $where_search = '((CONCAT(`firstname`, \' \', `lastname`) LIKE "%' . $searchtext . '%" OR l.firstname LIKE "%' . $searchtext . '%" OR l.lastname LIKE "%' . $searchtext . '%" OR ks.date LIKE "%' . $searchtext . '%" OR ks.time LIKE "%' . $searchtext . '%" OR l.status LIKE "%' . $searchtext . '%")AND l.is_delete = "0")';

                    $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);

                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1');
                } else {
                    $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);

                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
                }
            } else {
                $where = array("ks.yp_id" => $id, "ks.is_deleted" => 0);
                $where_date = "ks.created_date BETWEEN  '" . $created_date . "' AND '" . $movedate . "'";
                $fields = array("c.care_home_name,l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname, ks.*");
                $join_tables = array(LOGIN . ' as l' => 'l.login_id = ks.created_by', CARE_HOME . ' as c' => 'c.care_home_id= ks.care_home_id');

                if (!empty($searchtext)) {
                    $searchtext = html_entity_decode(trim(addslashes($searchtext)));
                    $match = array("ks.is_deleted" => 0);
                    $where_search = '((CONCAT(`firstname`, \' \', `lastname`) LIKE "%' . $searchtext . '%" OR l.firstname LIKE "%' . $searchtext . '%" OR l.lastname LIKE "%' . $searchtext . '%" OR ks.date LIKE "%' . $searchtext . '%" OR ks.time LIKE "%' . $searchtext . '%" OR l.status LIKE "%' . $searchtext . '%")AND l.is_delete = "0")';

                    $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search, '', '', '', '', '', $where_date);

                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1', '', '', $where_date);
                } else {
                    $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where, '', '', '', '', '', $where_date);

                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1', '', '', $where_date);
                }
            }
            if ($isArchive == 0) {
                //get total balance
                $match = array('yp_id' => $id);
                $yp_pocket_money = $this->common_model->get_records(YP_POCKET_MONEY, '', '', '', $match);
                if (!empty($yp_pocket_money)) {
                    $data['total_balance'] = $yp_pocket_money[0]['total_balance'];
                }
            } else {
                $match = array('yp_id' => $id);
                $where_date = "created_date BETWEEN  '" . $created_date . "' AND '" . $movedate . "'";
                $yp_pocket_money = $this->common_model->get_records(POCKET_MONEY, '', '', '', $match);
                $balance_archive = end($yp_pocket_money);
                if (!empty($balance_archive)) {
                    $data['total_balance'] = $balance_archive['balance'];
                }
            }

            $this->ajax_pagination->initialize($config);
            $data['pagination'] = $this->ajax_pagination->create_links();
            //pr($data['pagination']);exit;
            $data['uri_segment'] = $uri_segment;
            $sortsearchpage_data = array(
                'sortfield' => $data['sortfield'],
                'sortby' => $data['sortby'],
                'searchtext' => $data['searchtext'],
                'perpage' => trim($data['perpage']),
                'uri_segment' => $uri_segment,
                'total_rows' => $config['total_rows']);

            $data['ypid'] = $id;
            $data['is_archive_page'] = $isArchive;
            $data['careHomeId'] = $careHomeId;
            //get ks form
            $match = array('ypm_form_id' => 1);
            $ks_forms = $this->common_model->get_records(YPM_FORM, '', '', '', $match);
            if (!empty($ks_forms)) {
                $data['form_data'] = json_decode($ks_forms[0]['form_json_data'], TRUE);
            }
            $this->session->set_userdata('pm_data', $sortsearchpage_data);
            setActiveSession('pm_data'); // set current Session active
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['crnt_view'] = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/PocketMoney/PocketMoney.js');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/ajaxlist', $data);
            } else {
                $data['main_content'] = '/index';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : create yp edit page
      @Input  :
      @Output :
      @Date   : 03/05/2018
     */

    public function add($id) {
        if (is_numeric($id)) {
            //get ks form
            $match = array('ypm_form_id' => 1);
            $ks_forms = $this->common_model->get_records(YPM_FORM, '', '', '', $match);
            $form_field = array();
            if (!empty($ks_forms)) {
                $data['ks_form_data'] = json_decode($ks_forms[0]['form_json_data'], TRUE);

                foreach ($data['ks_form_data'] as $form_data) {
                    $form_field[] = $form_data['name'];
                }
            }
            $data['form_field_data_name'] = $form_field;
            //get YP information
            $match = array("yp_id" => $id);
            $fields = array("yp_id,care_home,yp_fname,yp_lname,date_of_birth");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/' . $id);
            }
            //get total balance
            $data['total_balance'] = 0;
            $match = array('yp_id' => $id);
            $yp_pocket_money = $this->common_model->get_records(YP_POCKET_MONEY, '', '', '', $match);
            if (!empty($yp_pocket_money)) {
                $data['total_balance'] = $yp_pocket_money[0]['total_balance'];
            }

            $data['ypid'] = $id;
            $data['footerJs'][0] = base_url('uploads/custom/js/PocketMoney/PocketMoney.js');
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['crnt_view'] = $this->viewname;
            $data['main_content'] = '/edit';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral patel
      @Desc   : Insert PM form
      @Input    :
      @Output   :
      @Date   : 05/03/2018
     */

    public function insert() {
        $postData = $this->input->post();
        $match = array("yp_id" => $postData['yp_id']);
        $fields = array("care_home");
        $data_yp_detail['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
        unset($postData['submit_pmform']);
        //get pp form
        $match = array('ypm_form_id' => 1);
        $ks_forms = $this->common_model->get_records(YPM_FORM, array("*"), '', '', $match);
        if (!empty($ks_forms)) {
            $ks_form_data = json_decode($ks_forms[0]['form_json_data'], TRUE);
            $data = array();
            foreach ($ks_form_data as $row) {
                if (isset($row['name'])) {
                    if ($row['type'] == 'file') {
                        $filename = $row['name'];
                        if (!empty($_FILES[$filename]['name'][0])) {
                            //create dir and give permission
                            /* common function replaced by Dhara Bhalala on 29/09/2018 */
                            createDirectory(array($this->config->item('pm_base_url'), $this->config->item('pm_base_big_url'), $this->config->item('pm_base_big_url') . '/' . $postData['yp_id']));

                            $file_view = $this->config->item('pm_img_url') . $postData['yp_id'];
                            //upload big image
                            $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/index/' . $postData['yp_id']);
                            //upload small image
                            $insertImagesData = array();
                            if (!empty($upload_data)) {
                                foreach ($upload_data as $imageFiles) {
                                    /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                    createDirectory(array($this->config->item('pm_base_small_url'), $this->config->item('pm_base_small_url') . '/' . $postData['yp_id']));

                                    /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                    if ($imageFiles['is_image'])
                                        $a = do_resize($this->config->item('pm_img_url') . $postData['yp_id'], $this->config->item('pm_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                    array_push($insertImagesData, $imageFiles['file_name']);
                                    if (!empty($insertImagesData)) {
                                        $images = implode(',', $insertImagesData);
                                    }
                                }

                                if (!empty($images)) {
                                    $data[$row['name']] = !empty($images) ? $images : '';
                                }
                            }
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
                                if ($row['subtype'] == 'time') {
                                    $data[$row['name']] = dbtimeformat($postData[$row['name']]);
                                } else {
                                    $data[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                                }
                            } else {
                                $data[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                            }
                        }
                    }
                }
            }
        }

        //get total balance
        $match = array('yp_id' => $postData['yp_id']);
        $yp_pocket_money = $this->common_model->get_records(YP_POCKET_MONEY, '', '', '', $match);

        if (!empty($yp_pocket_money)) {
            $bal = $yp_pocket_money[0]['total_balance'];
            $total_balance = ($bal + $postData['money_in']) - $postData['money_out'];
            $update_pocket_blance = array('total_balance' => $total_balance, 'updated_at' => datetimeformat());
            //update total balance
            $this->common_model->update(YP_POCKET_MONEY, $update_pocket_blance, array('yp_id' => $postData['yp_id']));
        } else {
            $total_balance = ($postData['money_in']) - $postData['money_out'];
            $update_pocket_blance = array(
                'yp_id' => $postData['yp_id'],
                'total_balance' => $total_balance,
                'updated_at' => datetimeformat()
            );
            //update total balance
            $this->common_model->insert(YP_POCKET_MONEY, $update_pocket_blance);
        }

        if (!empty($postData['pocket_money_id'])) {
            $data['balance'] = $total_balance;
            $data['pocket_money_id'] = $postData['pocket_money_id'];
            $data['yp_id'] = $postData['yp_id'];
            $data['modified_date'] = datetimeformat();
            $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            $data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
            
            $this->common_model->update(POCKET_MONEY, $data, array('pocket_money_id' => $postData['pocket_money_id']));
            //Insert log activity
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                'module_name' => POCKET_MONEY_MODULE,
                'module_field_name' => '',
                'type' => 2
            );
            log_activity($activity);
        } else {
            if (!empty($data)) {
                $data['balance'] = $total_balance;
                $data['yp_id'] = $postData['yp_id'];
                $data['created_date'] = datetimeformat();
                $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
                $data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
                $this->common_model->insert(POCKET_MONEY, $data);
                $data['pocket_money_id'] = $this->db->insert_id();
                //Insert log activity
                $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                    'module_name' => POCKET_MONEY_MODULE,
                    'module_field_name' => '',
                    'type' => 1
                );
                log_activity($activity);
            }
        }
        //get total balance
        $match = array('yp_id' => $postData['yp_id']);
        $yp_pocket_money_saving = $this->common_model->get_records(YP_POCKET_MONEY, '', '', '', $match);
        if (!empty($yp_pocket_money_saving)) {
            if ($postData['reason'] == 'Earnt from Banding'/* && !empty($postData['money_in']) */) {

                $bal = $yp_pocket_money_saving[0]['total_balance'];
                if ($bal >= 5) {
                    $update_data = array('total_balance' => ($yp_pocket_money_saving[0]['total_balance'] - 5),
                        'saving_balance' => ($yp_pocket_money_saving[0]['saving_balance'] + 5),
                        'updated_at' => datetimeformat());
                    //update total balance
                    $this->common_model->update(YP_POCKET_MONEY, $update_data, array('yp_id' => $postData['yp_id']));
                    //update pocket money
                    $newdata['balance'] = ($bal - 5);
                    $newdata['yp_id'] = $postData['yp_id'];
                    $newdata['staff'] = $this->session->userdata['LOGGED_IN']['ID'];
                    $newdata['money_out'] = '5';
                    $newdata['reason'] = 'Saving Pocket Money';
                    $newdata['money_type'] = 1;
                    $newdata['created_date'] = datetimeformat();
                    $newdata['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
                    $newdata['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
                    $this->common_model->insert(POCKET_MONEY, $newdata);
                }
            }
        }
        if (!empty($data)) {
            redirect('/' . $this->viewname . '/index/' . $data['yp_id']);
        } else {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>Please  insert pocket money details.</div>");
            redirect('/' . $this->viewname . '/add/' . $postData['yp_id']);
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Read more
      @Input    : yp id
      @Output   :
      @Date   : 03/05/2018
     */

    public function readmore($id, $field) {
        $params['fields'] = [$field];
        $params['table'] = POCKET_MONEY;
        $params['match_and'] = 'pocket_money_id=' . $id . '';
        $data['documents'] = $this->common_model->get_records_array($params);
        $data['field'] = $field;
        $this->load->view($this->viewname . '/readmore', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Read more
      @Input    : yp id
      @Output   :
      @Date   : 03/05/2018
     */

    public function DownloadPrint($yp_id, $section = NULL) {
        $data = [];
        $match = array('ypm_form_id' => 1);
        $ks_forms = $this->common_model->get_records(YPM_FORM, '', '', '', $match);
        if (!empty($ks_forms)) {
            $data['form_data'] = json_decode($ks_forms[0]['form_json_data'], TRUE);
        }
        //get YP information
        $table = YP_DETAILS . ' as yp';
        $match = array("yp.yp_id" => $yp_id);
        $fields = array("yp.yp_fname,yp.yp_lname,pa.placing_authority_id,pa.authority,pa.address_1,pa.town,pa.county,pa.postcode,sd.mobile,sd.email");
        $join_tables = array(PLACING_AUTHORITY . ' as pa' => 'pa.yp_id=yp.yp_id', SOCIAL_WORKER_DETAILS . ' as sd' => 'sd.yp_id=yp.yp_id');
        $data['YP_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', '', '', '', '');

        //get total balance pocket money
        $match = array('yp_id' => $yp_id);
        $data['yp_pocket_money'] = $this->common_model->get_records(YP_POCKET_MONEY, '', '', '', $match);

        $table = POCKET_MONEY . ' as ks';
        $where = array("ks.yp_id" => $yp_id, "ks.is_deleted" => 0);
        //$where = array("ks.yp_id"=> $id,"ks.is_archive"=> 0,"ks.created_by"=> $login_user_id,"ks.is_delete"=> 0);
        $fields = array("l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname, ks.*");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id = ks.created_by');
        $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', 'ks.pocket_money_id', 'desc', '', $where);
        $data['ypid'] = $yp_id;

        $data['main_content'] = '/pmpdf';
        $data['section'] = $section;
        $html = $this->parser->parse('layouts/PDFTemplate', $data);
        $pdfFileName = "ypf-pm" . $yp_id . ".pdf";
        $pdfFilePath = FCPATH . 'uploads/ypf/';
        if (!is_dir(FCPATH . 'uploads/ypf/')) {
            @mkdir(FCPATH . 'uploads/ypf/', 0777, TRUE);
        }
        if (file_exists($pdfFilePath . $pdfFileName)) {
            unlink($pdfFilePath . $pdfFileName);
        }
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

    /*
     * @Author : Niral Patel
     * @Desc   : Generate Report based on the data
     * @Input   :
     * @Output  :
     * @Date   : 11-5-2018
     */

    public function generateExcelFile($ypid) {
        $this->load->library('excel');
        $this->activeSheetIndex = $this->excel->setActiveSheetIndex(0);

        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Pocket Money');
        $exceldataHeader = "";
        $exceldataValue = "";
        $headerCount = 1;
        if (!empty($ypid)) {
            $table = POCKET_MONEY . ' as ks';
            $where = array("ks.yp_id" => $ypid, "ks.is_deleted" => 0);
            //$where = array("ks.yp_id"=> $id,"ks.is_archive"=> 0,"ks.created_by"=> $login_user_id,"ks.is_delete"=> 0);
            $fields = array("CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname, ks.*");

            $joinTables = array(LOGIN . ' as l' => 'l.login_id = ks.created_by');
            $recordData = $this->common_model->get_records($table, $fields, $joinTables, 'left', '', '', '', '', 'pocket_money_id', 'desc', '', $where);

            //get total balance pocket money
            $match = array('yp_id' => $ypid);
            $yp_pocket_money = $this->common_model->get_records(YP_POCKET_MONEY, '', '', '', $match);

            //get pm form
            $match = array('ypm_form_id' => 1);
            $pmformsdata = $this->common_model->get_records(YPM_FORM, '', '', '', $match);

            if (!empty($pmformsdata)) {
                $formsdata = json_decode($pmformsdata[0]['form_json_data'], TRUE);
            }
            $form_field = array();
            $exceldataHeader = array();
            if (!empty($pmformsdata)) {
                $exceldataHeader[] .= 'Date/Time';
                foreach ($formsdata as $row) {
                    $exceldataHeader[] .=html_entity_decode($row['label']);
                }
                $exceldataHeader[] .= 'Create By';
            }

            if (!empty($formsdata)) {
                $sheet = $this->excel->getActiveSheet();
                $this->excel->setActiveSheetIndex(0)->setTitle('Pocket Money');
                $sheet->getStyle('A1:Z1')->getFont()->setBold(true);
                $sheet->setCellValue('A1', !empty($yp_pocket_money[0]['total_balance']) ? ' Pocket Money Balance : ' . $yp_pocket_money[0]['total_balance'] : ' Pocket Money Balance : ' . '0');
                $sheet->getStyle('A3:Z3')->getFont()->setBold(true);
                $sheet->fromArray($exceldataHeader, Null, 'A3')->getStyle('A3')->getFont()->setBold(true); // Set Header Data
                if (!empty($recordData)) {
                    $col = 4;
                    foreach ($recordData as $data) {
                        if (!empty($formsdata)) {
                            $exceldataValue = array();
                            $exceldataValue[] = !empty($data['created_date']) ? configDateTimeFormat($data['created_date']) : '';
                            foreach ($formsdata as $row) {
                                if ($row['type'] == 'date') {
                                    if ((!empty($data[$row['name']]) && $data[$row['name']] != '0000-00-00')) {
                                        $exceldataValue[] .= date('d/m/Y', strtotime($data[$row['name']]));
                                    }
                                } else if ($row['subtype'] == 'time') {
                                    if ((!empty($data[$row['name']]) && $data[$row['name']] != '0000-00-00')) {
                                        $exceldataValue[] .= timeformat($data[$row['name']]);
                                    }
                                } else if ($row['type'] == 'select') {
                                    if (!empty($data[$row['name']])) {
                                        if (!empty($row['description']) && $row['description'] == 'get_user') {

                                            $get_data = $this->common_model->get_single_user($data[$row['name']]);
                                            $exceldataValue[] .=!empty($get_data[0]['username']) ? $get_data[0]['username'] : '';
                                        } else {
                                            $exceldataValue[] .=!empty($data[$row['name']]) ? $data[$row['name']] : '';
                                        }
                                    } else {
                                        $exceldataValue[] .=!empty($data[$row['name']]) ? $data[$row['name']] : '';
                                    }
                                } else {
                                    $exceldataValue[] .=!empty($data[$row['name']]) ? $data[$row['name']] : '';
                                }
                            }
                            $exceldataValue[] .=!empty($data['name']) ? $data['name'] : '';
                        }

                        $sheet->fromArray($exceldataValue, Null, 'A' . $col)->getStyle('A' . $col)->getFont()->setBold(false);
                        $col ++;
                    } // end recordData foreach
                }
            }
        }
        $fileName = 'Pocket Money' . date('Y-m-d H:i:s') . '.xls'; // Generate file name
        $this->downloadExcelFile($this->excel, $fileName); // download function Xls file function call
    }

    /*
     * @Author : Niral Patel
     * @Desc   : Download Report based on the data
     * @Input   :
     * @Output  :
     * @Date   : 11-5-2018
     */

    public function downloadExcelFile($objExcel, $fileName) {
        $this->excel = $objExcel;
        //$fileName = 'PHPExcelDemo.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment; filename = "' . $fileName . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        ob_end_clean();
        ob_start();
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
        exit;
    }

}
