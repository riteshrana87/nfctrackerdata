<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Communication extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session', 'm_pdf'));
    }

    /*
      @Author : Niral Patel
      @Desc   : Communication List Page
      @Input 	:
      @Output	:
      @Date   : 17/07/2017
     */

    public function index($ypid, $care_home_id = 0, $past_care_id = 0) {
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
        $perpage = RECORD_PER_PAGE;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('coms_data');
        }

        $searchsort_session = $this->session->userdata('coms_data');
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
                $sortfield = 'date_of_communication';
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
        if (is_numeric($ypid)) {
            //pagination configuration
            $config['first_link'] = 'First';
            if ($past_care_id == 0) {
                $config['base_url'] = base_url() . $this->viewname . '/index/' . $ypid;

                if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 4;
                    $uri_segment = $this->uri->segment(4);
                }

                $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
                $table = COMMUNICATION_LOG . ' as mc';
                $where = array("mc.yp_id" => $ypid);
                $fields = array("mc.*,CONCAT(`firstname`,' ', `lastname`) as name,ch.care_home_name");
                $join_tables = array(LOGIN . ' as l' => 'l.login_id=mc.created_by', CARE_HOME . ' as ch' => 'ch.care_home_id = mc.care_home_id');
                if (!empty($searchtext)) {
                    
                } else {
                    $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
                }
            } else {

                $config['base_url'] = base_url() . $this->viewname . '/index/' . $ypid . '/' . $care_home_id . '/' . $past_care_id;
                if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 6;
                    $uri_segment = $this->uri->segment(6);
                }

                $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
                $table = COMMUNICATION_LOG . ' as mc';
                $where = array("mc.yp_id" => $ypid);
                $where_date = "mc.created_date BETWEEN  '" . $created_date . "' AND '" . $movedate . "'";
                $fields = array("mc.*,CONCAT(`firstname`,' ', `lastname`) as name,ch.care_home_name");
                $join_tables = array(LOGIN . ' as l' => 'l.login_id=mc.created_by', CARE_HOME . ' as ch' => 'ch.care_home_id = mc.care_home_id');
                if (!empty($searchtext)) {
                    
                } else {
                    $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where, '', '', '', '', '', $where_date);
                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1', '', '', $where_date);
                }
            }

            $data['care_home_id'] = $care_home_id;
            $data['past_care_id'] = $past_care_id;
            $data['ypid'] = $ypid;
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

            $this->session->set_userdata('coms_data', $sortsearchpage_data);
            setActiveSession('coms_data'); // set current Session active
            //get communication form
            $match = array('coms_form_id' => 1);
            $formsdata = $this->common_model->get_records(COMS_FORM, '', '', '', $match);
            if (!empty($formsdata)) {
                $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }

            $data['crnt_view'] = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/communication/communication.js');
            $data['header'] = array('menu_module' => 'YoungPerson');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/ajaxlist', $data);
            } else {
                $data['main_content'] = '/list';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : View Page
      @Input  :
      @Output :
      @Date   : 17/07/2017
     */

    public function view($id, $care_home_id = 0, $past_care_id = 0) {
        //get communication data
        if (is_numeric($id)) {
            $table = COMMUNICATION_LOG . ' as do';
            $where = array("do.communication_log_id" => $id);
            $fields = array("do.*");
            $data['medical_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            $data['ypid'] = !empty($data['medical_data'][0]['yp_id']) ? $data['medical_data'][0]['yp_id'] : '';
            if (empty($data['medical_data'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/' . $data['ypid']);
            }
            //get communication form
            $match = array('coms_form_id' => 1);
            $formsdata = $this->common_model->get_records(COMS_FORM, array("form_json_data,"), '', '', $match);
            if (!empty($formsdata)) {
                $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }
            $data['co_id'] = $id;
            $data['ypid'] = !empty($data['medical_data'][0]['yp_id']) ? $data['medical_data'][0]['yp_id'] : '';

            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $table = COMS_SIGNOFF . ' as coms';
            $where = array("l.is_delete" => "0", "coms.coms_id" => $id);
            $fields = array("coms.created_by,coms.created_date,coms.yp_id,coms.coms_id, CONCAT(`firstname`,' ', `lastname`) as name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id=coms.created_by');
            $group_by = array('created_by');
            $data['coms_signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);

            //get external approve
            $table = NFC_COMS_SIGNOFF_DETAILS;
            $fields = array('coms_signoff_details_id,form_json_data,key_data');
            $where = array('coms_id' => $id, 'yp_id' => $data['ypid']);
            $data['check_external_signoff_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

            $table = COMS_SIGNOFF . ' as coms';
            $where = array("coms.created_by" => $login_user_id, "coms.is_delete" => "0", "coms.coms_id" => $id);
            $fields = array("coms.*");
            $data['check_signoff_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($data['ypid'], $fields);

            $data['care_home_id'] = $care_home_id;
            $data['past_care_id'] = $past_care_id;

            $data['main_content'] = '/view';
            $data['footerJs'][0] = base_url('uploads/custom/js/communication/communication.js');
            $data['header'] = array('menu_module' => 'YoungPerson');
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : create add page
      @Input 	:
      @Output	:
      @Date   : 24/07/2017
     */

    public function add_communication($ypid) {
        //get communication form
        if (is_numeric($ypid)) {
            $match = array('coms_form_id' => 1);
            $formsdata = $this->common_model->get_records(COMS_FORM, '', '', '', $match);
            if (!empty($formsdata)) {
                $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }
            //get YP information
            $fields = array("care_home");
            $data['YP_details'] = YpDetails($ypid, $fields);

            $data['care_home_id'] = $data['YP_details'][0]['care_home'];
            $data['ypid'] = $ypid;
            $data['main_content'] = '/add_communication';
            $data['footerJs'][0] = base_url('uploads/custom/js/communication/communication.js');
            $data['header'] = array('menu_module' => 'YoungPerson');
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Insert communication form
      @Input    :
      @Output   :
      @Date   : 24/07/2017
     */

    public function insert() {
        if (!validateFormSecret()) {
            redirect($_SERVER['HTTP_REFERER']);  //Redirect On Listing page
        }
        $postData = $this->input->post();
        unset($postData['submit_comform']);
        //get pp form
        $match = array('coms_form_id' => 1);
        $pp_forms = $this->common_model->get_records(COMS_FORM, array("form_json_data"), '', '', $match);
        if (!empty($pp_forms)) {
            $pp_form_data = json_decode($pp_forms[0]['form_json_data'], TRUE);
            $data = array();
            foreach ($pp_form_data as $row) {
                if (isset($row['name'])) {
                    if ($row['type'] == 'file') {
                        $filename = $row['name'];
                        //get image previous image
                        $match = array('communication_log_id' => $postData['communication_log_id'], 'yp_id' => $postData['yp_id']);
                        $pp_yp_data = $this->common_model->get_records(COMMUNICATION_LOG, array('`' . $row['name'] . '`'), '', '', $match);
                        //delete img
                        if (!empty($postData['hidden_' . $row['name']])) {
                            $delete_img = explode(',', $postData['hidden_' . $row['name']]);
                            $db_images = explode(',', $pp_yp_data[0][$filename]);
                            $differentedImage = array_diff($db_images, $delete_img);
                            $pp_yp_data[0][$filename] = !empty($differentedImage) ? implode(',', $differentedImage) : '';
                            if (!empty($delete_img)) {
                                foreach ($delete_img as $img) {
                                    if (file_exists($this->config->item('communication_img_url') . $postData['yp_id'] . '/' . $img)) {
                                        unlink($this->config->item('communication_img_url') . $postData['yp_id'] . '/' . $img);
                                    }
                                    if (file_exists($this->config->item('communication_img_url_small') . $postData['yp_id'] . '/' . $img)) {
                                        unlink($this->config->item('communication_img_url_small') . $postData['yp_id'] . '/' . $img);
                                    }
                                }
                            }
                        }

                        if (!empty($_FILES[$filename]['name'][0])) {
                            //create dir and give permission
                            /* common function replaced by Dhara Bhalala on 29/09/2018 */
                            createDirectory(array($this->config->item('communication_base_url'), $this->config->item('communication_base_big_url'), $this->config->item('communication_base_big_url') . '/' . $postData['yp_id']));

                            $file_view = $this->config->item('communication_img_url') . $postData['yp_id'];
                            //upload big image
                            $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/index/' . $postData['yp_id']);
                            //upload small image
                            $insertImagesData = array();
                            if (!empty($upload_data)) {
                                foreach ($upload_data as $imageFiles) {
                                    /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                    createDirectory(array($this->config->item('communication_base_small_url'), $this->config->item('communication_base_small_url') . '/' . $postData['yp_id']));

                                    /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                    if ($imageFiles['is_image'])
                                        $a = do_resize($this->config->item('communication_img_url') . $postData['yp_id'], $this->config->item('communication_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                    array_push($insertImagesData, $imageFiles['file_name']);
                                    if (!empty($insertImagesData)) {
                                        $images = implode(',', $insertImagesData);
                                    }
                                }
                                if (!empty($pp_yp_data[0][$filename])) {
                                    $images .=',' . $pp_yp_data[0][$filename];
                                }
                                $data[$row['name']] = !empty($images) ? $images : '';
                            }
                        } else {
                            $data[$row['name']] = !empty($pp_yp_data[0][$filename]) ? $pp_yp_data[0][$filename] : '';
                        }
                    } else {
                        if ($row['type'] != 'button') {
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

        if (!empty($postData['communication_log_id'])) {
            $data['communication_log_id'] = $postData['communication_log_id'];
            $data['yp_id'] = $postData['yp_id'];
            $data['modified_date'] = datetimeformat();
            $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            $this->common_model->update(COMMUNICATION_LOG, $data, array('communication_log_id' => $postData['communication_log_id']));
        } else {
            $data['yp_id'] = $postData['yp_id'];
            $data['care_home_id'] = $postData['care_home_id'];
            $data['created_date'] = datetimeformat();
            $data['modified_date'] = datetimeformat();
            $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            $this->common_model->insert(COMMUNICATION_LOG, $data);
            //Insert log activity
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                'module_name' => COMS_MODULE,
                'module_field_name' => '',
                'type' => 1
            );
            log_activity($activity);
        }
        redirect('/' . $this->viewname . '/save_comm/' . $postData['yp_id']);
    }

    /*
      @Author : Niral Patel
      @Desc   : save data communication
      @Input  :
      @Output :
      @Date   : 24/07/2017
     */

    public function save_comm($id) {
        if (is_numeric($id)) {
            $data = array(
                'header_data' => 'You have added a new Communication:',
                'detail' => 'New Communication has been updated. Please check your added carefully.',
            );
            $data['do_id'] = $id;
            $data['main_content'] = '/save_data';
            $data['header'] = array('menu_module' => 'YoungPerson');
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
      @Date   : 26/07/2017
     */

    public function readmore($id, $field) {
        if (is_numeric($id)) {
            $params['fields'] = [$field];
            $params['table'] = COMMUNICATION_LOG;
            $params['match_and'] = 'communication_log_id=' . $id . '';
            $data['documents'] = $this->common_model->get_records_array($params);
            $data['field'] = $field;
            $this->load->view($this->viewname . '/readmore', $data);
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : print functionality
      @Input    :
      @Output   :
      @Date   : 26/07/2017
     */

    public function DownloadPrint($co_id, $yp_id, $section = NULL) {
        $data = [];
        $table = COMMUNICATION_LOG . ' as do';
        $where = array("do.communication_log_id" => $co_id);
        $fields = array("do.*");
        $data['edit_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

        if (empty($data['edit_data'])) {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson/view/' . $yp_id);
        }
        //get communication form
        $match = array('coms_form_id' => 1);
        $formsdata = $this->common_model->get_records(COMS_FORM, array("form_json_data"), '', '', $match);
        if (!empty($formsdata)) {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        $data['co_id'] = $co_id;

        //get YP information
        $table = YP_DETAILS . ' as yp';
        $match = array("yp.yp_id" => $yp_id);
        $fields = array("yp.yp_fname,yp.yp_lname,pa.placing_authority_id,pa.authority,pa.address_1,pa.town,pa.county,pa.postcode,sd.mobile,sd.email");
        $join_tables = array(PLACING_AUTHORITY . ' as pa' => 'pa.yp_id=yp.yp_id', SOCIAL_WORKER_DETAILS . ' as sd' => 'sd.yp_id=yp.yp_id');
        $data['YP_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match, '', '', '', '', '', '', '');

        $data['ypid'] = $yp_id;
        $data['main_content'] = '/communication_pdf';
        $data['section'] = $section;
        $html = $this->parser->parse('layouts/PDFTemplate', $data);
        $pdfFileName = "coms" . $co_id . ".pdf";
        $pdfFilePath = FCPATH . 'uploads/communication/';
        if (!is_dir(FCPATH . 'uploads/communication/')) {
            @mkdir(FCPATH . 'uploads/communication/', 0777, TRUE);
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
            $html;
        } else {
            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($pdfFileName, "D");
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : signoff functionality
      @Input    : yp id,co_id
      @Output   :
      @Date   : 26/07/2017
     */

    public function manager_review($yp_id, $co_id) {
        if (!empty($yp_id) && !empty($co_id)) {
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $match = array('yp_id' => $yp_id, 'coms_id' => $co_id, 'created_by' => $login_user_id);
            $check_signoff_data = $this->common_model->get_records(COMS_SIGNOFF, array("*"), '', '', $match);
            //get YP information
            $fields = array("care_home");
            $YP_details = YpDetails($yp_id, $fields);

            $data['care_home_id'] = $YP_details[0]['care_home'];

            if (empty($check_signoff_data) > 0) {
                $update_pre_data['coms_id'] = $co_id;
                $update_pre_data['care_home_id'] = $data['care_home_id'];
                $update_pre_data['yp_id'] = $yp_id;
                $update_pre_data['created_date'] = datetimeformat();
                $update_pre_data['created_by'] = $login_user_id;
                if ($this->common_model->insert(COMS_SIGNOFF, $update_pre_data)) {
                    $msg = $this->lang->line('successfully_coms_review');
                    $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                } else {
                    // error
                    $msg = $this->lang->line('error_msg');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                }
            } else {
                $msg = $this->lang->line('already_coms_review');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
        } else {
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }
        redirect('/' . $this->viewname . '/view/' . $co_id);
    }

    /*
      @Author : Niral Patel
      @Desc   : Send for Signoff / Approval functionality
      @Input    : yp id,co_id
      @Output   :
      @Date   : 26/07/2017
     */

    public function signoff($yp_id = '', $co_id = '') {
        $this->formValidation();

        $main_user_data = $this->session->userdata('LOGGED_IN');
        if ($this->form_validation->run() == FALSE) {

            $data['footerJs'][0] = base_url('uploads/custom/js/communication/communication.js');
            $data['crnt_view'] = $this->viewname;

            $data['ypid'] = $yp_id;
            $data['co_id'] = $co_id;

            //get YP information
            $fields = array("care_home");
            $YP_details = YpDetails($yp_id, $fields);
            $data['care_home_id'] = $YP_details[0]['care_home'];
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
      @Author : Niral Patel
      @Desc   : insertdata functionality
      @Input    :
      @Output   :
      @Date   : 26/07/2017
     */

    public function insertdata() {
        $postdata = $this->input->post();
        $ypid = $postdata['ypid'];
        $co_id = $postdata['co_id'];
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
                'module_name' => COMS_PARENT_CARER_DETAILS_YP,
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

        $match = array('coms_form_id' => 1);
        $formsdata = $this->common_model->get_records(COMS_FORM, array("*"), '', '', $match);

        //get pp yp data
        $match = array('yp_id' => $ypid, 'communication_log_id' => $co_id);
        $coms_yp_data = $this->common_model->get_records(COMMUNICATION_LOG, array("*"), '', '', $match);

        if (!empty($formsdata) && !empty($coms_yp_data)) {
            $coms_form_data = json_decode($formsdata[0]['form_json_data'], TRUE);
            $data = array();
            $i = 0;
            foreach ($coms_form_data as $row) {
                if (isset($row['name'])) {
                    if ($row['type'] != 'button') {
                        if ($row['type'] == 'checkbox-group') {
                            $coms_form_data[$i]['value'] = implode(',', $coms_yp_data[0][$row['name']]);
                            echo $coms_yp_data[0][$row['name']];
                        } else {
                            $coms_form_data[$i]['value'] = str_replace("'", '"', $coms_yp_data[0][$row['name']]);
                        }
                    }
                }
                $i++;
            }
        }

        $data = array(
            'user_type' => ucfirst($postdata['user_type']),
            'yp_id' => ucfirst($postdata['ypid']),
            'coms_id' => ucfirst($postdata['co_id']),
            'form_json_data' => json_encode($coms_form_data, TRUE),
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
        if ($this->common_model->insert(NFC_COMS_SIGNOFF_DETAILS, $data)) {

            $signoff_id = $this->db->insert_id();

            $table = COMS_SIGNOFF;
            $where = array("yp_id" => $ypid, "coms_id" => $co_id, "is_delete" => "0");
            $fields = array("created_by,yp_id,coms_id,created_date");
            $group_by = array('created_by');
            $signoff_data = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', $group_by, $where);

            if (!empty($signoff_data)) {
                foreach ($signoff_data as $archive_value) {
                    $update_arc_data['approval_coms_id'] = $signoff_id;
                    $update_arc_data['yp_id'] = $archive_value['yp_id'];
                    $update_arc_data['created_date'] = $archive_value['created_date'];
                    $update_arc_data['created_by'] = $archive_value['created_by'];
                    $this->common_model->insert(NFC_APPROVAL_COMS_SIGNOFF, $update_arc_data);
                }
            }

            $this->sendMailToRelation($data, $signoff_id); // send mail
            //Insert log activity
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($ypid) ? $ypid : '',
                'module_name' => COMS_YP_NEW_ADD,
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

        redirect($this->viewname . '/view/' . $co_id . '/' . $ypid);
    }

    /*
      @Author : Niral Patel
      @Desc   : send Mail To Relation functionality
      @Input    :
      @Output   :
      @Date   : 26/07/2017
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
            $loginLink = base_url('Communication/signoffData/' . $data['yp_id'] . '/' . $signoff_id . '/' . $email);

            $find = array('{NAME}', '{LINK}');

            $replace = array(
                'NAME' => $customerName,
                'LINK' => $loginLink,
            );

            $emailSubject = 'Welcome to NFCTracker';
            $emailBody = '<div>'
                    . '<p>Hello {NAME} ,</p> '
                    . '<p>Please find Communication for ' . $yp_name . ' for your approval.</p> '
                    . "<p>For security purposes, Please do not forward this email on to any other person. It is for the recipient only and if this is sent in error please advise itsupport@newforestcare.co.uk and delete this email. This link is only valid for " . REPORT_EXPAIRED_HOUR . ", should this not be signed off within " . REPORT_EXPAIRED_HOUR . " of recieving then please request again</p>"
                    . '<p> <a href="{LINK}">click here</a> </p> '
                    . '<div>';

            $finalEmailBody = str_replace($find, $replace, $emailBody);

            return $this->common_model->sendEmail($toEmailId, $emailSubject, $finalEmailBody, FROM_EMAIL_ID);
        }
        return true;
    }

    /*
      @Author : Niral Patel
      @Desc   : form Validation functionality
      @Input    :
      @Output   :
      @Date   : 26/07/2017
     */

    public function formValidation($id = null) {
        $this->form_validation->set_rules('fname', 'Firstname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('lname', 'Lastname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
    }

    /*
      @Author : Niral Patel
      @Desc   : view send Mail To Relation Url
      @Input    :
      @Output   :
      @Date   : 26/07/2017
     */

    public function signoffData($yp_id, $id, $email) {

        if (is_numeric($id) && is_numeric($yp_id) && !empty($email)) {
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $match = array('yp_id' => $yp_id, 'coms_signoff_details_id' => $id, 'key_data' => $email, 'status' => 'inactive');
            $check_signoff_data = $this->common_model->get_records(NFC_COMS_SIGNOFF_DETAILS, array("form_json_data,created_date"), '', '', $match);
            if (!empty($check_signoff_data)) {
                $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'] . REPORT_EXPAIRED_DAYS));
                if (strtotime(datetimeformat()) <= strtotime($expairedDate)) {
                    $data['form_data'] = json_decode($check_signoff_data[0]['form_json_data'], TRUE);
                    //get YP information
                    $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
                    $data['YP_details'] = YpDetails($yp_id, $fields);
                    if (empty($data['YP_details'])) {
                        $msg = $this->lang->line('common_no_record_found');
                        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                        redirect('YoungPerson/view/' . $yp_id);
                    }

                    $table = NFC_APPROVAL_COMS_SIGNOFF . ' as ras';
                    $where = array("l.is_delete" => "0", "ras.yp_id" => $yp_id, "ras.is_delete" => "0", "approval_coms_id" => $id);
                    $fields = array("ras.created_by,ras.created_date,ras.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
                    $join_tables = array(LOGIN . ' as l' => 'l.login_id=ras.created_by');
                    $group_by = array('created_by');
                    $data['signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);


                    $data['key_data'] = $email;
                    $data['ypid'] = $yp_id;
                    $data['co_id'] = $check_signoff_data[0]['coms_id'];
                    $data['signoff_id'] = $id;

                    $data['footerJs'][0] = base_url('uploads/custom/js/communication/communication.js');
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
                //show_404 (); 
                $msg = $this->lang->line('already_coms_review');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                $this->load->view('successfully_message');
            }
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : review Signoff / Approval History
      @Input    :
      @Output   :
      @Date   : 26/07/2017
     */

    public function signoff_review_data($yp_id, $co_id, $email) {
        if (!empty($yp_id) && !empty($co_id) && !empty($email)) {
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $match = array('yp_id' => $yp_id, 'coms_signoff_details_id' => $co_id, 'key_data' => $email, 'status' => 'inactive');
            $check_signoff_data = $this->common_model->get_records(NFC_COMS_SIGNOFF_DETAILS, array("created_date"), '', '', $match);

            if (!empty($check_signoff_data)) {
                $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'] . REPORT_EXPAIRED_DAYS));
                if (strtotime(datetimeformat()) <= strtotime($expairedDate)) {
                    $u_data['status'] = 'active';
                    $u_data['modified_date'] = datetimeformat();
                    $success = $this->common_model->update(NFC_COMS_SIGNOFF_DETAILS, $u_data, array('coms_signoff_details_id' => $co_id, 'yp_id' => $yp_id, 'key_data' => $email));
                    if ($success) {

                        $msg = $this->lang->line('successfully_coms_review');
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
                $msg = $this->lang->line('already_coms_review');
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
      @Author : Niral Patel
      @Desc   : view ks data
      @Input  :
      @Output :
      @Date   : 12/04/2018
     */

    public function external_approval_list($ypid, $co_id, $care_home_id = 0, $past_care_id = 0) {

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
        if (is_numeric($ypid) && is_numeric($co_id)) {

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
            $perpage = RECORD_PER_PAGE;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('com_approval_session_data');
            }

            $searchsort_session = $this->session->userdata('com_approval_session_data');
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
                    $sortfield = 'coms_signoff_details_id';
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
            if ($past_care_id == 0) {
                $config['base_url'] = base_url() . $this->viewname . '/external_approval_list/' . $ypid . '/' . $co_id;

                if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 5;
                    $uri_segment = $this->uri->segment(5);
                }
                //Query

                $table = NFC_COMS_SIGNOFF_DETAILS . ' as com';
                $where = array("com.yp_id" => $ypid, "com.coms_id" => $co_id);
                $fields = array("com.*,CONCAT(`firstname`,' ', `lastname`) as create_name ,CONCAT(`fname`,' ', `lname`) as user_name,care_home_name");
                $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', CARE_HOME . ' as ch' => 'ch.care_home_id = com.care_home_id');
                if (!empty($searchtext)) {
                    
                } else {
                    $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);

                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
                }
            } else {
                $config['base_url'] = base_url() . $this->viewname . '/external_approval_list/' . $ypid . '/' . $co_id . '/' . $care_home_id . '/' . $past_care_id;

                if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 7;
                    $uri_segment = $this->uri->segment(7);
                }
                //Query

                $table = NFC_COMS_SIGNOFF_DETAILS . ' as com';
                $where = array("com.yp_id" => $ypid, "com.coms_id" => $co_id);
                $where_date = "com.created_date BETWEEN  '" . $created_date . "' AND '" . $movedate . "'";
                $fields = array("com.*,CONCAT(`firstname`,' ', `lastname`) as create_name ,CONCAT(`fname`,' ', `lname`) as user_name,care_home_name");
                $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', CARE_HOME . ' as ch' => 'ch.care_home_id = com.care_home_id');
                if (!empty($searchtext)) {
                    
                } else {
                    $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where, '', '', '', '', '', $where_date);
                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1', '', '', $where_date);
                }
            }

            $data['ypid'] = $ypid;
            $data['coms_id'] = $co_id;
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

            $this->session->set_userdata('com_approval_session_data', $sortsearchpage_data);
            setActiveSession('com_approval_session_data'); // set current Session active
            $data['header'] = array('menu_module' => 'Communication');

            //get communication form
            $data['crnt_view'] = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/communication/communication.js');
            $data['header'] = array('menu_module' => 'YoungPerson');

            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/approval_ajaxlist', $data);
            } else {
                $data['main_content'] = '/coms_list';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }

    public function viewApprovalCom($id, $ypid, $care_home_id = 0, $past_care_id = 0) {
        if (is_numeric($id) && is_numeric($ypid)) {
            //get archive pp data
            $match = array('coms_signoff_details_id' => $id);
            $formsdata = $this->common_model->get_records(NFC_COMS_SIGNOFF_DETAILS, array("form_json_data,coms_id"), '', '', $match);
            if (!empty($formsdata)) {
                $data['pp_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($ypid, $fields);
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/' . $ypid);
            }


            $table = NFC_APPROVAL_COMS_SIGNOFF . ' as ras';
            $where = array("l.is_delete" => "0", "ras.yp_id" => $ypid, "ras.is_delete" => "0", "approval_coms_id" => $id);
            $fields = array("ras.created_by,ras.created_date,ras.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id=ras.created_by');
            $group_by = array('created_by');
            $data['signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);

            $data['ypid'] = $ypid;
            $data['care_home_id'] = $care_home_id;
            $data['past_care_id'] = $past_care_id;
            $data['coms_id'] = $formsdata[0]['coms_id'];

            $data['footerJs'][0] = base_url('uploads/custom/js/communication/communication.js');
            $data['crnt_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/approval_view';
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

    public function resend_external_approval($signoff_id, $ypid, $comsid) {
        $match = array('coms_signoff_details_id' => $signoff_id);
        $signoff_data = $this->common_model->get_records(NFC_COMS_SIGNOFF_DETAILS, array("yp_id,fname,lname,email"), '', '', $match);
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
                $success = $this->common_model->update(NFC_COMS_SIGNOFF_DETAILS, $u_data, array('coms_signoff_details_id' => $signoff_id));
                $msg = $this->lang->line('mail_sent_successfully');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            }
        }

        redirect($this->viewname . '/external_approval_list/' . $ypid . '/' . $comsid);
    }

    /*
      @Author : Nikunj Ghelani
      @Desc   : PDF data
      @Input  :
      @Output :
      @Date   : 16/07/2018
     */

    public function DownloadPdf($yp_id, $id) {
        if (is_numeric($id) && is_numeric($yp_id)) {
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $match = array('yp_id' => $yp_id, 'coms_signoff_details_id' => $id, 'status' => 'inactive');
            $check_signoff_data = $this->common_model->get_records(NFC_COMS_SIGNOFF_DETAILS, array("created_date,form_json_data,coms_id"), '', '', $match);

            if (!empty($check_signoff_data)) {
                $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'] . REPORT_EXPAIRED_DAYS));
                if (strtotime(datetimeformat()) <= strtotime($expairedDate)) {
                    $data['form_data'] = json_decode($check_signoff_data[0]['form_json_data'], TRUE);
                    //get YP information
                    $match = array("yp_id" => $yp_id);
                    $fields = array("*");
                    $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
                    if (empty($data['YP_details'])) {
                        $msg = $this->lang->line('common_no_record_found');
                        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                        redirect('YoungPerson/view/' . $yp_id);
                    }

                    $table = NFC_APPROVAL_COMS_SIGNOFF . ' as ras';
                    $where = array("l.is_delete" => "0", "ras.yp_id" => $yp_id, "ras.is_delete" => "0", "approval_coms_id" => $id);
                    $fields = array("ras.created_by,ras.created_date,ras.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
                    $join_tables = array(LOGIN . ' as l' => 'l.login_id=ras.created_by');
                    $group_by = array('created_by');
                    $data['signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);

                    $data['key_data'] = $email;
                    $data['ypid'] = $yp_id;
                    $data['co_id'] = $check_signoff_data[0]['coms_id'];
                    $data['signoff_id'] = $id;

                    $data['footerJs'][0] = base_url('uploads/custom/js/communication/communication.js');
                    $data['crnt_view'] = $this->viewname;
                    $pdfFileName = "communication.pdf";
                    $PDFInformation['yp_details'] = $data['YP_details'][0];
                    $PDFInformation['edit_data'] = $data['edit_data'][0]['modified_date'];


                    $PDFHeaderHTML = $this->load->view('communication_pdfHeader', $PDFInformation, true);

                    $PDFFooterHTML = $this->load->view('communication_pdfFooter', $PDFInformation, true);
                    //Set Header Footer and Content For PDF
                    $this->m_pdf->pdf->mPDF('utf-8', 'A4', '', '', '10', '10', '45', '25');

                    $this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
                    $this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);
                    $data['main_content'] = '/communication';
                    $html = $this->parser->parse('layouts/PdfDataTemplate', $data);

                    /* remove */
                    $this->m_pdf->pdf->WriteHTML($html);
                    //Store PDF in individual_strategies Folder
                    $this->m_pdf->pdf->Output($pdfFileName, "D");
                } else {
                    $msg = lang('link_expired');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>")
                    ;
                    $this->load->view('successfully_message');
                }
            } else {
                $msg = $this->lang->line('already_coms_review');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                $this->load->view('successfully_message');
            }
        } else {
            show_404();
        }
    }

}
