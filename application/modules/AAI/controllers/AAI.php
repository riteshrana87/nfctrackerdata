<?php

defined('BASEPATH') or exit('No direct script access allowed');

class AAI extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->viewname = $this->router->fetch_class();
        $this->method   = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session', 'm_pdf'));
//        $this->load->model('AAI_model');
    }

    /*
    @Author : Ritesh Rana
    @Desc   : Main Listing Page
    @Date   : 11/03/2019
    */

    public function index($ypId = 0)
    {
        if (is_numeric($ypId)) {
            //get YP information
            $fields             = array("care_home,yp_fname,yp_lname,DATE_FORMAT(date_of_birth, '%d/%m/%Y') as date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($ypId, $fields);
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/' . $ypId);
            }
            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield  = $this->input->post('sortfield');
            $sortby     = $this->input->post('sortby');
            $perpage    = RECORD_PER_PAGE;
            $allflag    = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('aai_data');
            }

            $searchsort_session = $this->session->userdata('aai_data');
            //Sorting
            if (!empty($sortfield) && !empty($sortby)) {
                $data['sortfield'] = $sortfield;
                $data['sortby']    = $sortby;
            } else {
                if (!empty($searchsort_session['sortfield'])) {
                    $data['sortfield'] = $searchsort_session['sortfield'];
                    $data['sortby']    = $searchsort_session['sortby'];
                    $sortfield         = $searchsort_session['sortfield'];
                    $sortby            = $searchsort_session['sortby'];
                } else {
                    $sortfield         = 'incident_id';
                    $sortby            = 'desc';
                    $data['sortfield'] = $sortfield;
                    $data['sortby']    = $sortby;
                }
            }
            //Search text
            if (!empty($searchtext)) {
                $data['searchtext'] = $searchtext;
            } else {
                if (empty($allflag) && !empty($searchsort_session['searchtext'])) {
                    $data['searchtext'] = $searchsort_session['searchtext'];
                    $searchtext         = $data['searchtext'];
                } else {
                    $data['searchtext'] = '';
                }
            }

            if (!empty($perpage) && $perpage != 'null') {
                $data['perpage']    = $perpage;
                $config['per_page'] = $perpage;
            } else {
                if (!empty($searchsort_session['perpage'])) {
                    $data['perpage']    = trim($searchsort_session['perpage']);
                    $config['per_page'] = trim($searchsort_session['perpage']);
                } else {
                    $config['per_page'] = RECORD_PER_PAGE;
                    $data['perpage']    = RECORD_PER_PAGE;
                }
            }
            //pagination configuration
            $config['first_link'] = 'First';
            $config['base_url']   = base_url() . $this->viewname . '/index/' . $ypId;
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment           = 0;
            } else {
                $config['uri_segment'] = 4;
                $uri_segment           = $this->uri->segment(4);
            }

            //Query
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
           $table = AAI_LIST_MAIN . ' as ai';
            $where = array("ai.yp_id" => $ypId,"ai.yp_id" => $ypId);
            $fields = array("ai.*,ch.care_home_name,sta.title");
            $join_tables = array(CARE_HOME . ' as ch' => 'ch.care_home_id = ai.care_home_id', AAI_DROPDOWN_OPTION . ' as sta' => 'sta.option_id = ai.form_status');
            if (!empty($searchtext)) {
                $searchtext = html_entity_decode(trim(addslashes($searchtext)));
                $match = array("ai.yp_id" => $ypId);
                $formated_date = dateformat($searchtext);
                $where_search = '('
                        . 'ai.reference_number LIKE "%' . $searchtext . '%" OR '
                        . 'sta.title LIKE "%' . $searchtext . '%" OR '
                        . 'ai.date_of_incident LIKE "%' . $formated_date . '%" OR '
                        . 'ai.description LIKE "%' . $searchtext . '%" OR '
                        . 'ai.incident_id LIKE "%' . $searchtext . '%" OR '
                        . 'ch.care_home_name LIKE "%' . $searchtext . '%")';

                $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match, '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);

                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match, '', '', '', $sortfield, $sortby, '', $where_search, '', '', '1');
            } else {
                $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);

                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
            }

            $this->ajax_pagination->initialize($config);
            $data['pagination']  = $this->ajax_pagination->create_links();
            $data['uri_segment'] = $uri_segment;
            $sortsearchpage_data = array(
                'sortfield'   => $data['sortfield'],
                'sortby'      => $data['sortby'],
                'searchtext'  => $data['searchtext'],
                'perpage'     => trim($data['perpage']),
                'uri_segment' => $uri_segment,
                'total_rows'  => $config['total_rows']);
            $this->session->set_userdata('aai_data', $sortsearchpage_data);
            setActiveSession('aai_data'); // set current Session active
            $data['crnt_view'] = $this->viewname;
            $data['ypId']           = $ypId;
            $data['isCareIncident'] = 1;
            $data['header']         = array('menu_module' => 'AAI');
            $data['footerJs'][0]    = base_url('uploads/custom/js/AAI/AAI.js');
            $data['footerJs'][1]    = base_url('uploads/custom/js/jquery.blockUI.js');
            if ($this->input->is_ajax_request()) {
                $this->load->view($this->viewname . '/ajaxlist', $data);
            } else {
                $data['main_content'] = '/accident_and_incident';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }

    /*
    @Author : Dhara Bhalala
    @Desc   : Create Incident Page
    @Date   : 17/12/2018
     */

    public function create($isCareIncident, $ypId = 0, $careHomeId = 0)
    {
        if ($ypId == 0) {
            if ($careHomeId > 0) {
                $match                  = array("is_archive" => 0, "care_home" => $careHomeId);
                $data['backButtonLink'] = base_url('YoungPerson/index/' . $careHomeId);
            } else {
                $match                  = array('is_archive' => 0);
                $data['backButtonLink'] = base_url('AAI/checkPlaceType');
            }
            $table           = YP_DETAILS . ' as a';
            $fields          = array("a.yp_id,CONCAT(`yp_fname`,' ', `yp_lname`) as yp_name");
            $data['yp_name'] = $this->common_model->get_records($table, $fields, '', '', $match);
        } else {
            $match = array('yp_id' => $ypId);
            $table = YP_DETAILS . ' as a';

            $join_tables        = array(EMAIL_CONFIG . ' as ec' => 'a.yp_id=ec.user_id', CARE_HOME . ' as c' => 'c.care_home_id=a.care_home');
            $data['yp_details'] = $this->common_model->get_records($table, '', $join_tables, '', $match);

            //pr($data['yp_details']);exit;
            $table                  = AAI_MAIN . ' as a';
            $join_tables            = array(YP_DETAILS . ' as yp' => 'a.yp_id=yp.yp_id', CARE_HOME . ' as c' => 'c.care_home_id=yp.care_home');
            $dateQuery              = 'date(a.created_date) >= CURDATE() - INTERVAL 14 DAY ';
            $data['YPIncidentData'] = $this->common_model->get_records($table, '', $join_tables, '', array('a.yp_id' => $ypId), '', '', '', '', '', '', $dateQuery);
            $YPHistoryData = $this->common_model->get_records($table, '', $join_tables, '', array('a.yp_id' => $ypId));
            $data['isHistoryAvailable'] = 0;
            if ($YPHistoryData > $data['YPIncidentData']) {
                $data['isHistoryAvailable'] = 1;
            }
        }

        $emailMatch = '(email LIKE "%_@__%.__%")';
        $nfcUsers   = $this->common_model->get_records(LOGIN, array('login_id as user_id', 'firstname as first_name', 'lastname as last_name', 'email'), '', '', '', '', '', '', '', '', '', $emailMatch);

        function appendNFCType($n)
        {
            $n['user_type']     = 'N';
            $n['job_title']     = '';
            $n['work_location'] = '';
            return $n;
        }

        $nfcUsers    = array_map("appendNFCType", $nfcUsers);
        $bambooUsers = $this->common_model->get_records(BAMBOOHR_USERS, array('user_id', 'first_name', 'last_name', 'email', 'job_title', 'work_location'), '', '', '', '', '', '', '', '', '', $emailMatch);

        function appendBambooType($n)
        {
            $n['user_type'] = 'B';
            return $n;
        }

        $bambooUsers            = array_map("appendBambooType", $bambooUsers);
        $data['bambooNfcUsers'] = array_merge($bambooUsers, $nfcUsers);
        $data['loggedInUser']   = $this->session->userdata['LOGGED_IN'];
        $entryForm              = $this->common_model->get_records(AAI_FORM, '', '', '', array('form_id' => AAI_MAIN_ENTRY_FORM_ID));
        if (!empty($entryForm)) {
            $data['entry_form_data'] = json_decode($entryForm[0]['form_json_data'], true);
        }

        $table                 = AAI_DROPDOWN . ' as dr';
        $where                 = array("d.status" => "1", "dr.status" => "1");
        $fields                = array("dr.dropdown_id", "dr.title", "dr.prefix", "count(d.option_id) as total_options", "GROUP_CONCAT( DISTINCT CONCAT(d.option_id,'|',d.title) ORDER BY d.dropdown_id DESC SEPARATOR ';') as dropdown_options");
        $joinTables            = array(AAI_DROPDOWN_OPTION . ' as d' => 'd.dropdown_id = dr.dropdown_id');
        $groupBy               = "dr.dropdown_id";
        $data['dropdown_data'] = $this->common_model->get_records($table, $fields, $joinTables, 'left', '', '', '', '', '', '', $groupBy, $where);
        foreach ($data['entry_form_data'] as $key => $value) {
            if (isset($value['type']) && $value['type'] == 'select') {
                foreach ($data['dropdown_data'] as $key1 => $value1) {
                    if ($value['description'] == $value1['prefix']) {
                        if ($value1['total_options'] > 0) {
                            $optionsArray = explode(';', $value1['dropdown_options']);
                            foreach ($optionsArray as $op => $v) {
                                $OpArray                                               = explode('|', $v);
                                $finalOptionsArray[$OpArray[0]]                        = $OpArray[1];
                                $data['entry_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                $data['entry_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                            }
                        }
                    }
                }
            }
        }

        $data['YP_details']     = $this->common_model->get_records(YP_DETAILS, array("yp_id,care_home,yp_fname,yp_lname,DATE_FORMAT(date_of_birth, '%d/%m/%Y') as date_of_birth"), '', '', array("yp_id" => $ypId));
        
        $data['crnt_view']      = $this->module;
        $data['ypId']           = $ypId;
        $data['isCareIncident'] = $isCareIncident;
        $data['createMode']     = 'main';
        $data['footerJs'][0]    = base_url('uploads/custom/js/AAI/AAI.js');
        $data['footerJs'][1]    = base_url('uploads/custom/js/jquery.blockUI.js');
        $data['main_content']   = '/create_ai';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }

/*
    @Author : Dhara Bhalala
    @Desc   : check incident Place
    @Date   : 17/12/2018
     */
    public function checkPlaceType()
    {
            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield  = $this->input->post('sortfield');
            $sortby     = $this->input->post('sortby');
            $perpage    = RECORD_PER_PAGE;
            $allflag    = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('aai_main_data');
            }

            $searchsort_session = $this->session->userdata('aai_main_data');
            //Sorting
            if (!empty($sortfield) && !empty($sortby)) {
                $data['sortfield'] = $sortfield;
                $data['sortby']    = $sortby;
            } else {
                if (!empty($searchsort_session['sortfield'])) {
                    $data['sortfield'] = $searchsort_session['sortfield'];
                    $data['sortby']    = $searchsort_session['sortby'];
                    $sortfield         = $searchsort_session['sortfield'];
                    $sortby            = $searchsort_session['sortby'];
                } else {
                    $sortfield         = 'incident_id';
                    $sortby            = 'desc';
                    $data['sortfield'] = $sortfield;
                    $data['sortby']    = $sortby;
                }
            }
            //Search text
            if (!empty($searchtext)) {
                $data['searchtext'] = $searchtext;
            } else {
                if (empty($allflag) && !empty($searchsort_session['searchtext'])) {
                    $data['searchtext'] = $searchsort_session['searchtext'];
                    $searchtext         = $data['searchtext'];
                } else {
                    $data['searchtext'] = '';
                }
            }

            if (!empty($perpage) && $perpage != 'null') {
                $data['perpage']    = $perpage;
                $config['per_page'] = $perpage;
            } else {
                if (!empty($searchsort_session['perpage'])) {
                    $data['perpage']    = trim($searchsort_session['perpage']);
                    $config['per_page'] = trim($searchsort_session['perpage']);
                } else {
                    $config['per_page'] = RECORD_PER_PAGE;
                    $data['perpage']    = RECORD_PER_PAGE;
                }
            }
            //pagination configuration
            $config['first_link'] = 'First';
            $config['base_url']   = base_url() . $this->viewname . '/checkPlaceType';
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment           = 0;
            } else {
                $config['uri_segment'] = 3;
                $uri_segment           = $this->uri->segment(3);
            }

            //Query
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
           $table = AAI_LIST_MAIN . ' as ai';
            $where = 'date(ai.created_date) >= CURDATE() - INTERVAL 14 DAY ';
            $fields = array("ai.*,ch.care_home_name,sta.title");
            $join_tables = array(CARE_HOME . ' as ch' => 'ch.care_home_id = ai.care_home_id', AAI_DROPDOWN_OPTION . ' as sta' => 'sta.option_id = ai.form_status');
            if (!empty($searchtext)) {
                $searchtext = html_entity_decode(trim(addslashes($searchtext)));
                $match = array("ai.yp_id" => $ypId);
                $formated_date = dateformat($searchtext);
                $where_search = '('
                        . 'ai.reference_number LIKE "%' . $searchtext . '%" OR '
                        . 'sta.title LIKE "%' . $searchtext . '%" OR '
                        . 'ai.date_of_incident LIKE "%' . $formated_date . '%" OR '
                        . 'ai.description LIKE "%' . $searchtext . '%" OR '
                        . 'ai.incident_id LIKE "%' . $searchtext . '%" OR '
                        . 'ch.care_home_name LIKE "%' . $searchtext . '%")';

                $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match, '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);
                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match, '', '', '', $sortfield, $sortby, '', $where_search, '', '', '1');
            } else {
                $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
            }
            
            $this->ajax_pagination->initialize($config);
            $data['pagination']  = $this->ajax_pagination->create_links();
            $data['uri_segment'] = $uri_segment;
            $sortsearchpage_data = array(
                'sortfield'   => $data['sortfield'],
                'sortby'      => $data['sortby'],
                'searchtext'  => $data['searchtext'],
                'perpage'     => trim($data['perpage']),
                'uri_segment' => $uri_segment,
                'total_rows'  => $config['total_rows']);
            $this->session->set_userdata('aai_main_data', $sortsearchpage_data);
            setActiveSession('aai_main_data'); // set current Session active
            $data['crnt_view'] = $this->viewname;
            $data['isCareIncident'] = 1;
            $data['header']         = array('menu_module' => 'AAI');
            $data['footerJs'][0]    = base_url('uploads/custom/js/AAI/AAI.js');
            $data['footerJs'][1]    = base_url('uploads/custom/js/jquery.blockUI.js');
            if ($this->input->is_ajax_request()) {
                $this->load->view($this->viewname . '/aai_main_ajaxlist', $data);
            } else {
                $data['main_content'] = '/check_incident_place';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
       
    }


    /*
    @Author : Dhara Bhalala
    @Desc   : Insert/Update Incident Data of Main Entry Form
    @Date   : 17/12/2018
     */
    public function updateMainForm($incidentId = 0) {
        if ($incidentId > 0) {
            $incidentData = $this->common_model->get_records(AAI_MAIN, '', '', '', array('incident_id' => $incidentId));
            if (isset($incidentData) && !empty($incidentData)) {
                $incidentData = $incidentData[0];
            }
        }
        $postData = $this->input->post();
        /* start update sign off data */
        $this->updateSignoffData($incidentId);
        /* end update sign off data */
        $entryForm = $this->common_model->get_records(AAI_FORM, '', '', '', array('form_id' => AAI_MAIN_ENTRY_FORM_ID));
        if (!empty($entryForm)) {
            $entryFormDataArray = json_decode($entryForm[0]['form_json_data'], true);
            $entryFormData = array();
            $data = array();
            $i = 0;
            foreach ($entryFormDataArray as $row) {
                if (isset($row['name'])) {
                    if ($row['type'] == 'file') {
                        $filename = $row['name'];
                        //get image previous image
                        $match = array('incident_id' => $postData['incident_id']);
                        $entryImageData = $this->common_model->get_records(AAI_ENTRY_FORM_DATA, array('`' . $row['name'] . '`'), '', '', $match);
                        //delete img
                        if (!empty($postData['hidden_' . $row['name']])) {
                            $delete_img = explode(',', $postData['hidden_' . $row['name']]);
                            $db_images = explode(',', $entryImageData[0][$filename]);
                            $differentedImage = array_diff($db_images, $delete_img);
                            $entryImageData[0][$filename] = !empty($differentedImage) ? implode(',', $differentedImage) : '';
                            if (!empty($delete_img)) {
                                foreach ($delete_img as $img) {
                                    if (file_exists($this->config->item('aai_img_url') . $postData['yp_id'] . '/' . $img)) {
                                        unlink($this->config->item('aai_img_url') . $postData['yp_id'] . '/' . $img);
                                    }
                                    if (file_exists($this->config->item('aai_img_url_small') . $postData['yp_id'] . '/' . $img)) {
                                        unlink($this->config->item('aai_img_url_small') . $postData['yp_id'] . '/' . $img);
                                    }
                                }
                            }
                        }

                        if (!empty($_FILES[$filename]['name'][0])) {
                            //create dir and give permission
                            /* common function replaced by Dhara Bhalala on 29/09/2018 */
                            createDirectory(array($this->config->item('aai_base_url'), $this->config->item('aai_base_big_url'), $this->config->item('aai_base_big_url') . '/' . $postData['yp_id']));

                            $file_view = $this->config->item('aai_img_url') . $postData['yp_id'];
                            //upload big image
                            $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/index/' . $postData['yp_id']);
                            //upload small image
                            $insertImagesData = array();
                            if (!empty($upload_data)) {
                                foreach ($upload_data as $imageFiles) {
                                    /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                    createDirectory(array($this->config->item('aai_base_small_url'), $this->config->item('aai_base_small_url') . '/' . $postData['yp_id']));

                                    /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                    if ($imageFiles['is_image'])
                                        $a = do_resize($this->config->item('aai_img_url') . $postData['yp_id'], $this->config->item('aai_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                    array_push($insertImagesData, $imageFiles['file_name']);
                                    if (!empty($insertImagesData)) {
                                        $images = implode(',', $insertImagesData);
                                    }
                                }
                                if (!empty($entryImageData[0][$filename])) {
                                    $images .=',' . $entryImageData[0][$filename];
                                }
                                if (!empty($images)) {
                                    $entryFormData[$row['name']] = !empty($images) ? $images : '';
                                }
                            }
                        } else {
                            if (!empty($entryImageData[0][$filename])) {
                                $entryFormData[$row['name']] = !empty($entryImageData[0][$filename]) ? $entryImageData[0][$filename] : '';
                            }
                        }
                    } else {
                        if ($row['type'] != 'button') {
                            if (!empty($postData[$row['name']])) {
                                if ($row['type'] == 'date') {
                                    $entryFormData[$row['name']] = dateformat($postData[$row['name']]);
                                } elseif ($row['subtype'] == 'time') {
                                    $entryFormData[$row['name']] = dbtimeformat($postData[$row['name']]);
                                } else if ($row['type'] == 'checkbox-group') {
                                    $entryFormData[$row['name']] = !empty($postData[$row['name']]) ? implode(',', $postData[$row['name']]) : '';
                                } elseif ($row['type'] == 'textarea' && $row['subtype'] == 'tinymce') {
                                    $entryFormData[$row['name']] = strip_slashes($postData[$row['name']]);
                                } elseif ($row['type'] == 'select') {
                                    if ($row['className'] == 'multiple') {
                                        $entryFormData[$row['name']] = implode(',', $postData[$row['name']]);
                                    } elseif ($row['className'] == 'bamboo_lookup_multiple') {
                                        $entryFormData[$row['name']] = implode(',', $postData[$row['name']]);
                                    } else {
                                        $entryFormData[$row['name']] = $postData[$row['name']];
                                    }
                                } else {
                                    $entryFormData[$row['name']] = strip_tags(strip_slashes(trim($postData[$row['name']])));
                                }
                            } else {
                                $entryFormData[$row['name']] = '';
                            }
                        }
                    }
                }
                $i++;
            }
        }
        $entryFormData['reporting_user'] = $postData['reporting_user'];
        $entryFormData['modified_by'] = $this->session->userdata('LOGGED_IN')['ID'];
        $entryFormData['modified_date'] = datetimeformat();
        $entryFormData['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
        $entryFormData['created_date'] = datetimeformat();

        /* save/update incident start */
        if ($incidentId > 0) {
            $reference_number = $incidentData['incident_reference_number'];
            $updateData = array(
                'is_care_incident' => $postData['educarecheck'],
                'related_incident' => $postData['related_incident'],
                'modified_by' => $this->session->userdata('LOGGED_IN')['ID'],
                'modified_date' => datetimeformat(),
            );
            $this->common_model->update(AAI_MAIN, $updateData, array('incident_id' => $incidentId));

            
            $this->common_model->update(AAI_ENTRY_FORM_DATA, $entryFormData, array('entry_form_id' => $postData['entry_form_id']));

            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                'module_name' => AAI_MODULE_ENTRY_FORM,
                'module_field_name' => '',
                'type' => 2
            );
            log_activity($activity);
        } else {
            $insertData = array(
                'yp_id' => $postData['yp_id'],
                'is_care_incident' => $postData['is_care_incident'],
                'care_home_id' => $postData['care_home_id'],
                'related_incident' => $postData['related_incident'],
                'created_by' => $this->session->userdata('LOGGED_IN')['ID'],
                'created_date' => datetimeformat(),
            );
            $this->common_model->insert(AAI_MAIN, $insertData);
            $incidentId = $this->db->insert_id();

            $yp_details = YpDetails($postData['yp_id'], array("yp_initials"));
            $refIncidentId = str_pad($incidentId, 8, '0', STR_PAD_LEFT);
            $reference_number = 'XX' . substr($yp_details[0]['yp_initials'], 0, 3) . date('dmy') . $refIncidentId;
            $this->common_model->update(AAI_MAIN, array('incident_reference_number' => $reference_number), array('incident_id' => $incidentId));

            $entryFormData['incident_id'] = $incidentId;
            $this->common_model->insert(AAI_ENTRY_FORM_DATA, $entryFormData);

            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                'module_name' => AAI_MODULE_ENTRY_FORM,
                'module_field_name' => '',
                'type' => 1
            );
            log_activity($activity);
        }
        /* save/update incident end */


        $match = array('form_type' => 'main_form', 'status' => 0, 'incident_id' => $incidentId);
        $archive_data = $this->common_model->get_records(AAI_ARCHIVE, '', '', '', $match);

        if (!empty($archive_data)) {
            //update status to archive
            $update_archive = array(
                'created_date' => datetimeformat(),
                'status' => 1
            );

            $where = array('form_type' => 'main_form', 'status' => 0, 'incident_id' => $incidentId);
            $this->common_model->update(AAI_ARCHIVE, $update_archive, $where);
        }
        $ArchiveincidentData['status'] = 0;
        $ArchiveincidentData['yp_id'] = $this->input->post('yp_id');
        $ArchiveincidentData['incident_id'] = $incidentId;
        $ArchiveincidentData['form_json_data'] = !empty($entryFormData) ? json_encode($entryFormData, true) : '';
        $ArchiveincidentData['form_type'] = 'main_form';
        $ArchiveincidentData['archive_created_date'] = datetimeformat();
        $ArchiveincidentData['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
        $ArchiveincidentData['created_date'] = datetimeformat();
        //insert archive data for next time
        $this->common_model->insert(AAI_ARCHIVE, $ArchiveincidentData);

        $report_compiler = $this->session->userdata('LOGGED_IN')['ID'];
        $aai_report_com = $this->common_model->get_records(AAI_REPORT_COMPILER, array("*"), '', '', array('incident_id' => $incidentId, 'reference_number' => $reference_number, 'report_compiler_id' => $report_compiler, 'process_id' => 'mainform'));

        if (empty($aai_report_com)) {
            $updateData = array(
                'reference_number' => $reference_number,
                'yp_id' => $postData['yp_id'],
                'incident_id' => $incidentId,
                'care_home_id' => $postData['care_home_id'],
                'report_compiler_id' => $report_compiler,
                'process_id' => 'mainform',
                'created_by' => $this->session->userdata('LOGGED_IN')['ID'],
                'created_date' => datetimeformat(),
                'modified_by' => $this->session->userdata('LOGGED_IN')['ID'],
                'modified_date' => datetimeformat(),
            );
            $this->common_model->insert(AAI_REPORT_COMPILER, $updateData);
        }

        redirect('AAI/edit/' . $incidentId . '/' . AAI_INCIDENT_TYPE_FORM);
    }

    /*
    @Author : Dhara Bhalala
    @Desc   : Update Type of Incident Form
    @Date   : 17/12/2018
     */

    public function updateTypeForm($incidentId) {
        $postData = $this->input->post();
        if ($postData['incident_id'] == $incidentId) {
            $incidentData = $this->common_model->get_records(AAI_MAIN, '', '', '', array('incident_id' => $incidentId));
            $incidentData = $incidentData[0];
            
            $updateData = array(
                'is_pi' => $postData['is_pi'],
                'is_yp_missing' => $postData['is_yp_missing'],
                'is_yp_injured' => $postData['is_yp_injured'],
                'is_yp_complaint' => $postData['is_yp_complaint'],
                'is_yp_safeguarding' => $postData['is_yp_safeguarding'],
                'is_staff_injured' => $postData['is_staff_injured'],
                'is_other_injured' => $postData['is_other_injured'],
                'is_l1' => $postData['is_l1'],
            );
            $updateData['incident_id'] = $incidentId;
            $updateData['modified_by'] = $this->session->userdata('LOGGED_IN')['ID'];
            $updateData['modified_date'] = datetimeformat();
            $incident_type_id = $postData['incident_type_id'];
            if (!empty($incident_type_id)) {
                $this->common_model->update(AAI_INCIDENT_TYPE_DATA, $updateData, array('incident_type_id' => $incident_type_id));
                $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($incidentData['yp_id']) ? $incidentData['yp_id'] : '',
                    'module_name' => AAI_MODULE_TYPE_FORM,
                    'module_field_name' => '',
                    'type' => 2
                );
                log_activity($activity);
            } else {
                $updateData['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
                $updateData['created_date'] = datetimeformat();
                $this->common_model->insert(AAI_INCIDENT_TYPE_DATA, $updateData);
                $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($incidentData['yp_id']) ? $incidentData['yp_id'] : '',
                    'module_name' => AAI_MODULE_TYPE_FORM,
                    'module_field_name' => '',
                    'type' => 1
                );
                log_activity($activity);
            }

            $match = array('form_type' => 'type_of_incident', 'status' => 0, 'incident_id' => $incidentId);
            $archive_data = $this->common_model->get_records(AAI_ARCHIVE, '', '', '', $match);

            if (!empty($archive_data)) {
                //update status to archive
                $update_archive = array(
                    'created_date' => datetimeformat(),
                    'status' => 1
                );

                $where = array('form_type' => 'type_of_incident', 'status' => 0, 'incident_id' => $incidentId);
                $this->common_model->update(AAI_ARCHIVE, $update_archive, $where);
            }
            $ArchiveincidentData['status'] = 0;
            $ArchiveincidentData['yp_id'] = $this->input->post('yp_id');
            $ArchiveincidentData['incident_id'] = $incidentId;
            $ArchiveincidentData['form_json_data'] = !empty($updateData) ? json_encode($updateData, true) : '';
            $ArchiveincidentData['form_type'] = 'type_of_incident';
            $ArchiveincidentData['archive_created_date'] = datetimeformat();
            $ArchiveincidentData['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
            $ArchiveincidentData['created_date'] = datetimeformat();
            //insert archive data for next time
            $this->common_model->insert(AAI_ARCHIVE, $ArchiveincidentData);

            if ($incidentId > 0) {
                if ($postData['is_l1'] == 1) {
                    $redirectForm = AAI_L1_FORM;
                } else if ($postData['is_pi'] == 1) {
                    $redirectForm = AAI_L2NL3_FORM;
                } else if ($postData['is_yp_missing'] == 1) {
                    $redirectForm = AAI_L4_FORM;
                } else if ($postData['is_yp_injured'] == 1) {
                    $redirectForm = AAI_L5_FORM;
                } else if ($postData['is_yp_complaint'] == 1) {
                    $redirectForm = AAI_L6_FORM;
                } else if ($postData['is_yp_safeguarding'] == 1) {
                    $redirectForm = AAI_L7_FORM;
                } else if ($postData['is_staff_injured'] == 1 || $postData['is_other_injured'] == 1) {
                    $redirectForm = AAI_L8_FORM;
                }
                redirect('AAI/edit/' . $incidentId . '/' . $redirectForm);
            }
        } else {
            show_404();
        }
    }

    /*
    @Author : Ritesh Rana
    @Desc   : Insert/Update Incident Data of L1 Form
    @Date   : 02/04/2019
    */

    public function updateL1Form($incidentId = 0)
    {
        $incidentData = $this->common_model->get_records(AAI_MAIN, '', '', '', array('incident_id' => $incidentId));
        $incidentData = $incidentData[0];

        $postData = $this->input->post();

        $draftdata = (isset($postData['saveAsDraftL1'])) ? $postData['saveAsDraftL1'] : '';
        if ($this->input->is_ajax_request()) {
            $draftdata = 1;
        }
        /*start update sign off data*/
        $this->updateSignoffData($incidentId);
        /*end update sign off data*/
        $l1Form = $this->common_model->get_records(AAI_FORM, '', '', '', array('form_id' => AAI_L1_FORM_ID));
        if (!empty($l1Form)) {
            $l1FormDataArray = json_decode($l1Form[0]['form_json_data'], true);
            $data       = array();
            $i          = 0;
            foreach ($l1FormDataArray as $row) {
                if (isset($row['name'])) {
                    if ($row['type'] == 'file') {
                           $filename = $row['name'];
                        //get image previous image
                        $match = array('incident_id' => $postData['incident_id']);
                        $l1ImageData = $this->common_model->get_records(AAI_L1_FORM_DATA, array('`' . $row['name'] . '`'), '', '', $match);
                        //delete img
                        if (!empty($postData['hidden_' . $row['name']])) {
                            $delete_img = explode(',', $postData['hidden_' . $row['name']]);
                            $db_images = explode(',', $l1ImageData[0][$filename]);
                            $differentedImage = array_diff($db_images, $delete_img);
                            $l1ImageData[0][$filename] = !empty($differentedImage) ? implode(',', $differentedImage) : '';
                            if (!empty($delete_img)) {
                                foreach ($delete_img as $img) {
                                    if (file_exists($this->config->item('aai_img_url') . $postData['yp_id'] . '/' . $img)) {
                                        unlink($this->config->item('aai_img_url') . $postData['yp_id'] . '/' . $img);
                                    }
                                    if (file_exists($this->config->item('aai_img_url_small') . $postData['yp_id'] . '/' . $img)) {
                                        unlink($this->config->item('aai_img_url_small') . $postData['yp_id'] . '/' . $img);
                                    }
                                }
                            }
                        }

                        if (!empty($_FILES[$filename]['name'][0])) {
                            //create dir and give permission
                            /* common function replaced by Dhara Bhalala on 29/09/2018 */
                            createDirectory(array($this->config->item('aai_base_url'), $this->config->item('aai_base_big_url'), $this->config->item('aai_base_big_url') . '/' . $postData['yp_id']));

                            $file_view = $this->config->item('aai_img_url') . $postData['yp_id'];
                            //upload big image
                            $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/index/' . $postData['yp_id']);
                            //upload small image
                            $insertImagesData = array();
                            if (!empty($upload_data)) {
                                foreach ($upload_data as $imageFiles) {
                                    /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                    createDirectory(array($this->config->item('aai_base_small_url'), $this->config->item('aai_base_small_url') . '/' . $postData['yp_id']));

                                    /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                    if ($imageFiles['is_image'])
                                        $a = do_resize($this->config->item('aai_img_url') . $postData['yp_id'], $this->config->item('aai_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                    array_push($insertImagesData, $imageFiles['file_name']);
                                    if (!empty($insertImagesData)) {
                                        $images = implode(',', $insertImagesData);
                                    }
                                }
                                if (!empty($l1ImageData[0][$filename])) {
                                    $images .=',' . $l1ImageData[0][$filename];
                                }
                                if (!empty($images)) {
                                    $l1FormData[$row['name']] = !empty($images) ? $images : '';
                                }
                            }
                        } else {
                            if (!empty($l1ImageData[0][$filename])) {
                                $l1FormData[$row['name']] = !empty($l1ImageData[0][$filename]) ? $l1ImageData[0][$filename] : '';
                            }
                        }
                    } else {
                        if ($row['type'] != 'button') {
                            if ($row['type'] == 'checkbox-group') {
                                $l1FormData[$row['name']] = !empty($postData[$row['name']]) ? implode(',', $postData[$row['name']]) : '';
                            } elseif ($row['type'] == 'textarea' && $row['subtype'] == 'tinymce') {
                                $l1FormData[$row['name']] = strip_slashes($postData[$row['name']]);
                            } elseif ($row['type'] == 'date') {
                                $l1FormData[$row['name']] = dateformat($postData[$row['name']]);
                            } elseif ($row['type'] == 'text') {
                              if($row['subtype'] == 'time'){
                                $l1FormData[$row['name']] = dbtimeformat($postData[$row['name']]);
                              }else{
                                $l1FormData[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                              }
                            }elseif (isset($row['type']) && $row['type'] == 'select') {
                                if($row['className'] == 'multiple'){
                                    $l1FormData[$row['name']] = implode(',', $postData[$row['name']]);    
                                }elseif($row['className'] == 'bamboo_lookup_multiple'){
                                    $l1FormData[$row['name']] = implode(',', $postData[$row['name']]);    
                                }else{
                                    $l1FormData[$row['name']] = $postData[$row['name']];    
                                }
                                
                            } else {
                                $l1FormData[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                            }
                        }
                        }
                    
                }
                $i++;
            }

            $l1FormData['l1_total_duration'] = $postData['l1_total_duration'];
        }
      

        if(!empty($postData['l1_is_the_yp_making_complaint']) && $postData['l1_is_the_yp_making_complaint'] == 'Yes'){
            $complaint = 1;
        }else{
            $complaint = 0;
        }

        if(!empty($postData['l1_was_the_yp_injured']) && $postData['l1_was_the_yp_injured'] == 'Yes'){
            $yp_injured = 1;
        }else{
            $yp_injured = 0;
        }


        if(!empty($postData['l1_was_a_staff_member_injured']) && $postData['l1_was_a_staff_member_injured'] == 'Yes'){
            $staff_member_injured = 1;
        }else{
            $staff_member_injured = 0;
        }
            
        if(!empty($postData['l1_was_a_anyone_else_injured']) && $postData['l1_was_a_anyone_else_injured'] == 'Yes'){
            $anyone_else_injured = 1;
        }else{
            $anyone_else_injured = 0;
        }    

        if ($incidentId > 0) {
            $reference_number = $postData['l1_reference_number'];
            $updateData       = array(
                'is_yp_injured'      => $yp_injured,
                'is_yp_complaint'    => $complaint,
                'is_staff_injured'   => $staff_member_injured,
                'is_other_injured'   => $anyone_else_injured,
            );

       $this->common_model->update(AAI_INCIDENT_TYPE_DATA, $updateData, array('incident_id' => $incidentId));        

        $l1FormData['incident_id'] = $incidentId;    
        $l1FormData['l1_reference_number'] = $reference_number;
        $l1FormData['modified_by'] = $this->session->userdata('LOGGED_IN')['ID'];
        $l1FormData['modified_date'] = datetimeformat();

        $l1_form_id = $postData['l1_form_id'];   
    if(!empty($l1_form_id)){
        $this->common_model->update(AAI_L1_FORM_DATA, $l1FormData, array('l1_form_id' => $l1_form_id));    
    }else{
        $this->common_model->insert(AAI_L1_FORM_DATA, $l1FormData);
    }

     //get L1 data
                 $match = array('form_type'=>'L1','status'=>0,'incident_id'=> $incidentId);
                 $archive_data = $this->common_model->get_records(AAI_ARCHIVE,'', '', '', $match);
                 
                 if(!empty($archive_data))
                 {
                 //update status to archive
                     $update_archive = array(
                        'created_date'=>datetimeformat(),
                        'status'=>1
                    );
                     
                    $where = array('form_type'=>'L1','status'=>0,'incident_id'=> $incidentId);
                    $this->common_model->update(AAI_ARCHIVE, $update_archive,$where);
                }
                $ArchiveincidentData['status'] = 0;
                $ArchiveincidentData['yp_id'] = $this->input->post('yp_id');
                $ArchiveincidentData['incident_id'] = $incidentId;
                $ArchiveincidentData['reference_number'] = $reference_number;
                $ArchiveincidentData['form_json_data'] = !empty($l1FormData) ? json_encode($l1FormData, true) : '';
                $ArchiveincidentData['form_type'] = 'L1';
                $ArchiveincidentData['archive_created_date'] = datetimeformat();
                $ArchiveincidentData['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
                $ArchiveincidentData['created_date'] = datetimeformat();
                //insert archive data for next time
                $this->common_model->insert(AAI_ARCHIVE, $ArchiveincidentData);
                
    
 if($draftdata == 1){
        $form_status = AAI_FORM_SAVE_AS_DRAFT;
    }else{
        $form_status = AAI_FORM_COMPLETED;
    }     
    $updateData = array(
        'draft' => $draftdata,
      'reference_number' =>  $reference_number,
      'yp_id' => $incidentData['yp_id'],
      'incident_id' => $incidentId,
      'care_home_id' =>  $incidentData['care_home_id'],
      'date_of_incident' => dateformat($postData['l1_start_date']),
      'form_status' => $form_status,
      'description' => $postData['l1_describe_leading'],
      'process_id' => 'L1',
      'created_by' => $this->session->userdata('LOGGED_IN')['ID'],
      'created_date' => datetimeformat(),
      'modified_by' => $this->session->userdata('LOGGED_IN')['ID'],
      'modified_date' => datetimeformat(),
    );

    $aai_main_incident = $this->common_model->get_records(AAI_LIST_MAIN, array("*"), '', '', array('incident_id' => $incidentId,'reference_number' => $reference_number)); 

       if(!empty($aai_main_incident)){
         $this->common_model->update(AAI_LIST_MAIN, $updateData, array('incident_id' => $incidentId,'reference_number' => $reference_number));
         $incident_id = $aai_main_incident[0]['list_main_incident_id'];
       }else{
            $this->common_model->insert(AAI_LIST_MAIN, $updateData);
            $incident_id       = $this->db->insert_id();
       }

/*start add report compiler */
     $report_compiler = $this->session->userdata('LOGGED_IN')['ID'];
    $updateData = array(
      'reference_number' =>  $reference_number,
      'yp_id' => $incidentData['yp_id'],
      'incident_id' => $incidentId,
      'list_main_incident_id' => $incident_id,
      'care_home_id' =>  $incidentData['care_home_id'],
      'report_compiler_id' =>  $report_compiler,
      'process_id' => 'L1',
      'created_by' => $this->session->userdata('LOGGED_IN')['ID'],
      'created_date' => datetimeformat(),
      'modified_by' => $this->session->userdata('LOGGED_IN')['ID'],
      'modified_date' => datetimeformat(),
    );

    $aai_report_com = $this->common_model->get_records(AAI_REPORT_COMPILER, array("*"), '', '', array('incident_id' => $incidentId,'reference_number' =>$reference_number, 'report_compiler_id' => $report_compiler,'process_id'=>'L1')); 
       if(empty($aai_report_com)){
           $this->common_model->insert(AAI_REPORT_COMPILER, $updateData);
       }
/*start add report compiler */
            $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($incidentData['yp_id']) ? $incidentData['yp_id'] : '',
                    'module_name' => AAI_MODULE,
                    'module_field_name' => '',
                    'type' => 2
                );
            log_activity($activity);

            $incidentTypeData = $this->common_model->get_records(AAI_INCIDENT_TYPE_DATA, '', '', '', array('incident_id' => $incidentId));
            $incidentTypeData = $incidentTypeData[0];
            if ($incidentTypeData['is_yp_missing'] == 1) {
                $redirectForm = AAI_L4_FORM;
            } elseif ($incidentTypeData['is_yp_injured'] == 1) {
                $redirectForm = AAI_L5_FORM;
            } elseif ($incidentTypeData['is_yp_complaint'] == 1) {
                $redirectForm = AAI_L6_FORM;
            } elseif ($incidentTypeData['is_yp_safeguarding'] == 1) {
                $redirectForm = AAI_L7_FORM;
            } elseif ($incidentTypeData['is_staff_injured'] == 1 || $incidentTypeData['is_other_injured'] == 1) {
                $redirectForm = AAI_L8_FORM;
            } else {
                $redirectForm = AAI_L9_FORM;
            }
            redirect('AAI/edit/' . $incidentId . '/' . $redirectForm);
        }
    }

    /*
    @Author : Ritesh Rana
    @Desc   : Insert/Update Incident Data of L2 - L3 Form
    @Date   : 03/04/2019
     */

    public function updateL2nL3Form($incidentId = 0)
    {
        $incidentData = $this->common_model->get_records(AAI_MAIN, '', '', '', array('incident_id' => $incidentId));
        $incidentData = $incidentData[0];

    
        $postData = $this->input->post();
        $draftdata = (isset($postData['saveAsDraftL2'])) ? $postData['saveAsDraftL2'] : '';
        if ($this->input->is_ajax_request()) {
            $draftdata = 1;
        }
        
        /*start update sign off data*/
        $this->updateSignoffData($incidentId);
        /*end update sign off data*/
        $l2Form = $this->common_model->get_records(AAI_FORM, '', '', '', array('form_id' => AAI_L2NL3_FORM_ID));
        if (!empty($l2Form)) {
            $l2FormDataArray = json_decode($l2Form[0]['form_json_data'], true);
            $data       = array();
            $i          = 0;
            foreach ($l2FormDataArray as $row) {
                if (isset($row['name'])) {
                        if ($row['type'] == 'file') {
                            $filename = $row['name'];
                        //get image previous image
                        $match = array('incident_id' => $postData['incident_id']);
                        $l2ImageData = $this->common_model->get_records(AAI_L2_L3_FORM_DATA, array('`' . $row['name'] . '`'), '', '', $match);
                        //delete img
                        if (!empty($postData['hidden_' . $row['name']])) {
                            $delete_img = explode(',', $postData['hidden_' . $row['name']]);
                            $db_images = explode(',', $l2ImageData[0][$filename]);
                            $differentedImage = array_diff($db_images, $delete_img);
                            $l2ImageData[0][$filename] = !empty($differentedImage) ? implode(',', $differentedImage) : '';
                            if (!empty($delete_img)) {
                                foreach ($delete_img as $img) {
                                    if (file_exists($this->config->item('aai_img_url') . $postData['yp_id'] . '/' . $img)) {
                                        unlink($this->config->item('aai_img_url') . $postData['yp_id'] . '/' . $img);
                                    }
                                    if (file_exists($this->config->item('aai_img_url_small') . $postData['yp_id'] . '/' . $img)) {
                                        unlink($this->config->item('aai_img_url_small') . $postData['yp_id'] . '/' . $img);
                                    }
                                }
                            }
                        }

                        if (!empty($_FILES[$filename]['name'][0])) {
                            //create dir and give permission
                            /* common function replaced by Dhara Bhalala on 29/09/2018 */
                            createDirectory(array($this->config->item('aai_base_url'), $this->config->item('aai_base_big_url'), $this->config->item('aai_base_big_url') . '/' . $postData['yp_id']));

                            $file_view = $this->config->item('aai_img_url') . $postData['yp_id'];
                            //upload big image
                            $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/index/' . $postData['yp_id']);
                            //upload small image
                            $insertImagesData = array();
                            if (!empty($upload_data)) {
                                foreach ($upload_data as $imageFiles) {
                                    /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                    createDirectory(array($this->config->item('aai_base_small_url'), $this->config->item('aai_base_small_url') . '/' . $postData['yp_id']));

                                    /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                    if ($imageFiles['is_image'])
                                        $a = do_resize($this->config->item('aai_img_url') . $postData['yp_id'], $this->config->item('aai_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                    array_push($insertImagesData, $imageFiles['file_name']);
                                    if (!empty($insertImagesData)) {
                                        $images = implode(',', $insertImagesData);
                                    }
                                }
                                if (!empty($l2ImageData[0][$filename])) {
                                    $images .=',' . $l2ImageData[0][$filename];
                                }
                                if (!empty($images)) {
                                    $l2FormData[$row['name']] = !empty($images) ? $images : '';
                                }
                            }
                        } else {
                            if (!empty($l2ImageData[0][$filename])) {
                                $l2FormData[$row['name']] = !empty($l2ImageData[0][$filename]) ? $l2ImageData[0][$filename] : '';
                            }
                        }
                    } else {
                            if ($row['type'] != 'button') {
                            if ($row['type'] == 'checkbox-group') {
                                $l2FormData[$row['name']] = !empty($postData[$row['name']]) ? implode(',', $postData[$row['name']]) : '';
                            } elseif ($row['type'] == 'textarea' && $row['subtype'] == 'tinymce') {
                                $l2FormData[$row['name']] = strip_slashes($postData[$row['name']]);
                            } elseif ($row['type'] == 'date') {
                                $l2FormData[$row['name']] = dateformat($postData[$row['name']]);
                            } elseif ($row['type'] == 'text') {
                              if($row['subtype'] == 'time'){
                                $l2FormData[$row['name']] = dbtimeformat($postData[$row['name']]);
                              }else{
                                $l2FormData[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                              }
                            }elseif (isset($row['type']) && $row['type'] == 'select') {
                                if($row['className'] == 'multiple'){
                                    $l2FormData[$row['name']] = implode(',', $postData[$row['name']]);    
                                }elseif($row['className'] == 'bamboo_lookup_multiple'){
                                    $l2FormData[$row['name']] = implode(',', $postData[$row['name']]);    
                                }else{
                                    $l2FormData[$row['name']] = $postData[$row['name']];    
                                }
                                
                            } else {
                                $l2FormData[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                            }
                        }
                        }
                }
                $i++;
            }
            $l2FormData['l2_total_duration'] = $postData['l2_total_duration'];
        }

            
        if ($incidentId > 0) {
            $yp_details     = YpDetails($incidentData['yp_id'], array("yp_initials"));
            $refIncidentId  = str_pad($incidentId, 8, '0', STR_PAD_LEFT);
            $dateOfIncident = date('dmy', strtotime($postData['l2_start_date']));

            if (in_array(LAYING_ID, $l2FormData['l2position'])) {
                $reference_number = 'L3' . substr($yp_details[0]['yp_initials'], 0, 3) . $dateOfIncident . $refIncidentId;
            } else {
                $reference_number = $postData['l2_l3_reference_number'];
            }


            if(!empty($postData['l2_is_the_yp_making_complaint']) && $postData['l2_is_the_yp_making_complaint'] == 'Yes'){
            $complaint = 1;
        }else{
            $complaint = 0;
        }

        if(!empty($postData['l2_was_the_yp_injured']) && $postData['l2_was_the_yp_injured'] == 'Yes'){
            $yp_injured = 1;
        }else{
            $yp_injured = 0;
        }


        if(!empty($postData['l2_was_staff_member_injured']) && $postData['l2_was_staff_member_injured'] == 'Yes'){
            $staff_member_injured = 1;
        }else{
            $staff_member_injured = 0;
        }
            
        if(!empty($postData['l2_was_anyone_else_injured']) && $postData['l2_was_anyone_else_injured'] == 'Yes'){
            $anyone_else_injured = 1;
        }else{
            $anyone_else_injured = 0;
        } 

        $updateData = array(
                'is_yp_injured'      => $yp_injured,
                'is_yp_complaint'    => $complaint,
                'is_staff_injured'   => $staff_member_injured,
                'is_other_injured'   => $anyone_else_injured,
            );
            
        $this->common_model->update(AAI_INCIDENT_TYPE_DATA, $updateData, array('incident_id' => $incidentId));

                $l2FormData['incident_id'] = $incidentId;
                $l2FormData['l2_l3_reference_number'] = $reference_number;
                $l2FormData['created_by']            = $this->session->userdata('LOGGED_IN')['ID'];
                $l2FormData['created_date']          = datetimeformat();
                $l2FormData['modified_by']            = $this->session->userdata('LOGGED_IN')['ID'];
                $l2FormData['modified_date']          = datetimeformat();
                $l2_form_id = $postData['l2_form_id'];   
              
    if(!empty($l2_form_id)){
        $this->common_model->update(AAI_L2_L3_FORM_DATA, $l2FormData, array('l2_l3_form_id' => $l2_form_id));    
    }else{
        $this->common_model->insert(AAI_L2_L3_FORM_DATA, $l2FormData);
        $l2_form_id = $this->db->insert_id();
    }



        if(!empty($l2_form_id)){

            //get L2 data
                 $match = array('form_type'=>'L2_L3','status'=>0,'incident_id'=> $incidentId);
                 $archive_data = $this->common_model->get_records(AAI_ARCHIVE,'', '', '', $match);

                 if(!empty($archive_data))
                 {
                 //update status to archive
                     $update_archive = array(
                        'created_date'=>datetimeformat(),
                        'status'=>1
                    );
                     
                    $where = array('form_type'=>'L2_L3','status'=>0,'incident_id'=> $incidentId);
                    $this->common_model->update(AAI_ARCHIVE, $update_archive,$where);
                }

            /*store archive data*/
                $ArchiveincidentData['status'] = 0;
                $ArchiveincidentData['yp_id'] = $this->input->post('yp_id');
                $ArchiveincidentData['incident_id'] = $incidentId;
                $ArchiveincidentData['reference_number'] = $reference_number;
                $ArchiveincidentData['form_json_data'] = !empty($l2FormData) ? json_encode($l2FormData, true) : '';
                $ArchiveincidentData['form_type'] = 'L2_L3';
                $ArchiveincidentData['archive_created_date'] = datetimeformat();
                $ArchiveincidentData['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
                $ArchiveincidentData['created_date'] = datetimeformat();
                $this->common_model->insert(AAI_ARCHIVE, $ArchiveincidentData);
                $l2_form_archive_id = $this->db->insert_id();


                $l2sequence_number = $postData['l2sequence_number'];
               for ($i = 0; $i < count($l2sequence_number); $i++) {
                    if (!empty($l2sequence_number[$i])) {
                        $l2sequenceArchive['archive_id'] = $l2_form_archive_id;
                        $l2sequenceArchive['incident_id'] = $incidentId;
                        $l2sequenceArchive['sequence_number'] = $postData['l2sequence_number'][$i];
                        $l2sequenceArchive['position']        = $postData['l2position'][$i];
                        $l2sequenceArchive['type']            = $postData['l2type'][$i];
                        $l2sequenceArchive['comments']        = $postData['l2comments'][$i];
                        $l2sequenceArchive['time']   = $postData['l2time_sequence'][$i];
                        $l2sequenceArchive['created_date'] = datetimeformat();
                        $l2sequenceArchive['modified_date'] = datetimeformat();
                        $this->common_model->insert(AAI_L2_L3_SEQUENCE_ARCHIVE, $l2sequenceArchive);
                        $l2_form_seq_id = $this->db->insert_id();


                    $sq = $i+1;
                    if(!empty($postData['l2Who_was_involved_in_incident' . $sq])){
                         $Whowasdata = $postData['l2Who_was_involved_in_incident' . $sq];
                         foreach ($Whowasdata as $value) {
                            $l2Archivewho['incident_id'] = $incidentId;
                            $l2Archivewho['l2_arcive_sequence_event_id']= $l2_form_seq_id;
                            $l2Archivewho['l2Who_was_involved_in_incident'] = $value;         
                            $l2Archivewho['created_date'] = datetimeformat();
                            $l2Archivewho['modified_date'] = datetimeformat();
                            $this->common_model->insert(AAI_L2_WHO_WAS_INVOLVED_ARCHIVE, $l2Archivewho);
                        }
                    }
                    }
                }

                $count_number_mo = count($postData['l2medical_observations_after_minutes']);
            for ($mo = 0; $mo < $count_number_mo; $mo++) {
                $l2MoArchive['archive_id'] = $l2_form_archive_id;
                $l2MoArchive['incident_id'] = $incidentId;
                $l2MoArchive['observation_taken_by']       = $postData['l2Observation_taken_by'][$mo];
                $l2MoArchive['medical_observations_after_xx_minutes'] = $postData['l2medical_observations_after_minutes'][$mo];
                $l2MoArchive['time']                       = $postData['l2time_medical'][$mo];
                $l2MoArchive['comments']                        = $postData['l2comments_mo'][$mo];
                $motaken = $mo+1;
                $medical = $postData['l2_medical_observation_taken' . $motaken];
                $l2MoArchive['l2_medical_observation_taken'] = $medical[0];
                $l2MoArchive['created_date'] = datetimeformat();
                $l2MoArchive['modified_date'] = datetimeformat();
                $this->common_model->insert(AAI_L2_L3_MEDOBS_ARCHIVE, $l2MoArchive);
             //   echo $this->db->last_query();exit;
            /*end store data */
        }
    }

    /* Sequence of events start */
            /*Start delete Sequence of events data */
                $where1 = array('incident_id' => $incidentId);
                $this->common_model->delete(AAI_L2_L3_SEQUENCE_EVENT, $where1);
                $this->common_model->delete(AAI_L2_WHO_WAS_INVOLVED, $where1);
                $this->common_model->delete(AAI_L2_L3_MEDICAL_OBSERVATION, $where1);
                $this->common_model->delete(AAI_L2_MEDICAL_OBSERVATION_TAKEN, $where1);
            /*End Delete Sequence of events data */
            
            $l2sequence_number = $postData['l2sequence_number'];
               for ($i = 0; $i < count($l2sequence_number); $i++) {
                    if (!empty($l2sequence_number[$i])) {
                        $new_change ++;
                        $l2sequence['incident_id'] = $incidentId;
                        $l2sequence['sequence_number'] = $postData['l2sequence_number'][$i];
                        $l2sequence['position']        = $postData['l2position'][$i];
                        $l2sequence['type']            = $postData['l2type'][$i];
                        $l2sequence['comments']        = $postData['l2comments'][$i];
                        $l2sequence['time']   = $postData['l2time_sequence'][$i];
                        $l2sequence['created_date'] = datetimeformat();
                        $l2sequence['modified_date'] = datetimeformat();
                        $this->common_model->insert(AAI_L2_L3_SEQUENCE_EVENT, $l2sequence);
                        $l2_form_seq_id = $this->db->insert_id();


                    $sq = $i+1;
                    if(!empty($postData['l2Who_was_involved_in_incident' . $sq])){
                         $Whowasdata = $postData['l2Who_was_involved_in_incident' . $sq];
                         foreach ($Whowasdata as $value) {
                            $l2sequencewho['incident_id'] = $incidentId;
                            $l2sequencewho['l2_l3_sequence_event_id']= $l2_form_seq_id;
                            $l2sequencewho['l2Who_was_involved_in_incident'] = $value;         
                            $l2sequencewho['created_date'] = datetimeformat();
                            $l2sequencewho['modified_date'] = datetimeformat();
                            $this->common_model->insert(AAI_L2_WHO_WAS_INVOLVED, $l2sequencewho);

                         }
                    }
                    }
                }
            /* Sequence of events end */

            /* Medical Observations start */
            $count_number_mo = count($postData['l2medical_observations_after_minutes']);
            for ($mo = 0; $mo < $count_number_mo; $mo++) {
                $l2FormDataMo['incident_id'] = $incidentId;
                $l2FormDataMo['observation_taken_by']       = $postData['l2Observation_taken_by'][$mo];
            $l2FormDataMo['medical_observations_after_xx_minutes'] = $postData['l2medical_observations_after_minutes'][$mo];
                $l2FormDataMo['time']                       = $postData['l2time_medical'][$mo];
                $l2FormDataMo['comments']                        = $postData['l2comments_mo'][$mo];
                $motaken = $mo+1;
                $medical = $postData['l2_medical_observation_taken' . $motaken];
                $l2FormDataMo['l2_medical_observation_taken'] = $medical[0];
                $this->common_model->insert(AAI_L2_L3_MEDICAL_OBSERVATION, $l2FormDataMo);
            }
        /* Medical Observations end */

 if($draftdata == 1){
        $form_status = AAI_FORM_SAVE_AS_DRAFT;
    }else{
        $form_status = AAI_FORM_COMPLETED;
    }     
   $updateData = array(
       'draft' => $draftdata,
      'reference_number' =>  $reference_number,
      'yp_id' => $incidentData['yp_id'],
      'incident_id' => $incidentId,
      'care_home_id' =>  $incidentData['care_home_id'],
      'date_of_incident' => dateformat($postData['l2_start_date']),
      'form_status' => $form_status,
      'description' => $postData['l2_describe_leading'],
      'process_id' => 'L2',
      'created_by' => $this->session->userdata('LOGGED_IN')['ID'],
      'created_date' => datetimeformat(),
      'modified_by' => $this->session->userdata('LOGGED_IN')['ID'],
      'modified_date' => datetimeformat(),
    );

    $aai_main_incident = $this->common_model->get_records(AAI_LIST_MAIN, array("*"), '', '', array('incident_id' => $incidentId,'reference_number' => $reference_number)); 

       if(!empty($aai_main_incident)){
         $this->common_model->update(AAI_LIST_MAIN, $updateData, array('incident_id' => $incidentId,'reference_number' => $reference_number));
         $incident_id = $aai_main_incident[0]['list_main_incident_id'];
       }else{
            $this->common_model->insert(AAI_LIST_MAIN, $updateData);
            $incident_id       = $this->db->insert_id();
       }



/*start add report compiler */
     $report_compiler = $this->session->userdata('LOGGED_IN')['ID'];
    $updateData = array(
      'reference_number' =>  $reference_number,
      'yp_id' => $incidentData['yp_id'],
      'incident_id' => $incidentId,
      'list_main_incident_id' => $incident_id,
      'care_home_id' =>  $incidentData['care_home_id'],
      'report_compiler_id' =>  $report_compiler,
      'process_id' => 'L2',
      'created_by' => $this->session->userdata('LOGGED_IN')['ID'],
      'created_date' => datetimeformat(),
      'modified_by' => $this->session->userdata('LOGGED_IN')['ID'],
      'modified_date' => datetimeformat(),
    );

    $aai_report_com = $this->common_model->get_records(AAI_REPORT_COMPILER, array("*"), '', '', array('incident_id' => $incidentId,'reference_number' =>$reference_number, 'report_compiler_id' => $report_compiler,'process_id'=>'L2')); 
       if(empty($aai_report_com)){
           $this->common_model->insert(AAI_REPORT_COMPILER, $updateData);
       }
/*start add report compiler */

            $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($incidentData['yp_id']) ? $incidentData['yp_id'] : '',
                    'module_name' => AAI_MODULE,
                    'module_field_name' => '',
                    'type' => 2
                );
            log_activity($activity);

            $incidentTypeData = $this->common_model->get_records(AAI_INCIDENT_TYPE_DATA, '', '', '', array('incident_id' => $incidentId));
            $incidentTypeData = $incidentTypeData[0];
            if ($incidentTypeData['is_yp_missing'] == 1) {
                $redirectForm = AAI_L4_FORM;
            } elseif ($incidentTypeData['is_yp_injured'] == 1) {
                $redirectForm = AAI_L5_FORM;
            } elseif ($incidentTypeData['is_yp_complaint'] == 1) {
                $redirectForm = AAI_L6_FORM;
            } elseif ($incidentTypeData['is_yp_safeguarding'] == 1) {
                $redirectForm = AAI_L7_FORM;
            } elseif ($incidentTypeData['is_staff_injured'] == 1 || $incidentTypeData['is_other_injured'] == 1) {
                $redirectForm = AAI_L8_FORM;
            } else {
                $redirectForm = AAI_L9_FORM;
            }
            redirect('AAI/edit/' . $incidentId . '/' . $redirectForm);
        }
    }

    /*
    @Author : Nikunj Ghelani
    @Desc   : Update L4 Form
    @Date   : 02/01/2019
     */

    public function updateL4Form($incidentId = 0) {
        $postData = $this->input->post();
        $incidentData = $this->common_model->get_records(AAI_MAIN, '', '', '', array('incident_id' => $incidentId));
        $incidentData = $incidentData[0];        

        $draftdata = (isset($postData['saveAsDraftL4'])) ? $postData['saveAsDraftL4'] : '';
        if ($this->input->is_ajax_request()) {
            $draftdata = 1;
        }

        $l4Form = $this->common_model->get_records(AAI_FORM, '', '', '', array('form_id' => AAI_L4_FORM_ID));
        if (!empty($l4Form)) {
            $l4FormDataArray = json_decode($l4Form[0]['form_json_data'], true);
            $l4FormData = array();
            $i = 0;
            foreach ($l4FormDataArray as $row) {
                if (isset($row['name'])) {
                    if ($row['type'] == 'file') {
                        $filename = $row['name'];
                        //get image previous image
                        $match = array('incident_id' => $postData['incident_id']);
                        $l4ImageData = $this->common_model->get_records(AAI_L4_FORM_DATA, array('`' . $row['name'] . '`'), '', '', $match);
                        //delete img
                        if (!empty($postData['hidden_' . $row['name']])) {
                            $delete_img = explode(',', $postData['hidden_' . $row['name']]);
                            $db_images = explode(',', $l4ImageData[0][$filename]);
                            $differentedImage = array_diff($db_images, $delete_img);
                            $l4ImageData[0][$filename] = !empty($differentedImage) ? implode(',', $differentedImage) : '';
                            if (!empty($delete_img)) {
                                foreach ($delete_img as $img) {
                                    if (file_exists($this->config->item('aai_img_url') . $postData['yp_id'] . '/' . $img)) {
                                        unlink($this->config->item('aai_img_url') . $postData['yp_id'] . '/' . $img);
                                    }
                                    if (file_exists($this->config->item('aai_img_url_small') . $postData['yp_id'] . '/' . $img)) {
                                        unlink($this->config->item('aai_img_url_small') . $postData['yp_id'] . '/' . $img);
                                    }
                                }
                            }
                        }

                        if (!empty($_FILES[$filename]['name'][0])) {
                            //create dir and give permission
                            /* common function replaced by Dhara Bhalala on 29/09/2018 */
                            createDirectory(array($this->config->item('aai_base_url'), $this->config->item('aai_base_big_url'), $this->config->item('aai_base_big_url') . '/' . $postData['yp_id']));

                            $file_view = $this->config->item('aai_img_url') . $postData['yp_id'];
                            //upload big image
                            $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/index/' . $postData['yp_id']);
                            //upload small image
                            $insertImagesData = array();
                            if (!empty($upload_data)) {
                                foreach ($upload_data as $imageFiles) {
                                    /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                    createDirectory(array($this->config->item('aai_base_small_url'), $this->config->item('aai_base_small_url') . '/' . $postData['yp_id']));

                                    /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                    if ($imageFiles['is_image'])
                                        $a = do_resize($this->config->item('aai_img_url') . $postData['yp_id'], $this->config->item('aai_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                    array_push($insertImagesData, $imageFiles['file_name']);
                                    if (!empty($insertImagesData)) {
                                        $images = implode(',', $insertImagesData);
                                    }
                                }
                                if (!empty($l4ImageData[0][$filename])) {
                                    $images .=',' . $l4ImageData[0][$filename];
                                }
                                if (!empty($images)) {
                                    $l4FormData[$row['name']] = !empty($images) ? $images : '';
                                }
                            }
                        } else {
                            if (!empty($l4ImageData[0][$filename])) {
                                $l4FormData[$row['name']] = !empty($l4ImageData[0][$filename]) ? $l4ImageData[0][$filename] : '';
                            }
                        }
                    } else {
                        if ($row['type'] != 'button') {
                            if (!empty($postData[$row['name']])) {
                                if ($row['type'] == 'date') {
                                    $l4FormData[$row['name']] = dateformat($postData[$row['name']]);
                                } elseif ($row['subtype'] == 'time') {
                                    $l4FormData[$row['name']] = dbtimeformat($postData[$row['name']]);
                                } else if ($row['type'] == 'checkbox-group') {
                                    $l4FormData[$row['name']] = !empty($postData[$row['name']]) ? implode(',', $postData[$row['name']]) : '';
                                } elseif ($row['type'] == 'textarea' && $row['subtype'] == 'tinymce') {
                                    $l4FormData[$row['name']] = strip_slashes($postData[$row['name']]);
                                } elseif ($row['type'] == 'select') {
                                    if ($row['className'] == 'multiple') {
                                        $l4FormData[$row['name']] = implode(',', $postData[$row['name']]);
                                    } elseif ($row['className'] == 'bamboo_lookup_multiple') {
                                        $l4FormData[$row['name']] = implode(',', $postData[$row['name']]);
                                    } else {
                                        $l4FormData[$row['name']] = $postData[$row['name']];
                                    }
                                } else {
                                    $l4FormData[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                                }
                            } else {
                                $l4FormData[$row['name']] = !empty($postData[$row['name']]) ? $postData[$row['name']] : '';
                            }
                        }
                    }
                }
                $i++;
            }
            //normal data
            $l4FormData['calculate_notification_worker'] = $postData['calculate_notification_worker'];
            $l4FormData['calculate_notification_missing'] = $postData['calculate_notification_missing'];            
            $l4FormData['l4_total_duration'] = $postData['l4_total_duration'];            
            
            $where1 = array('incident_id' => $incidentId);
                $this->common_model->delete(AAI_L4_PERSON_INFORMED_MISSING, $where1);
                $this->common_model->delete(AAI_L4_PERSON_INFORMED_RETURN, $where1);
                $this->common_model->delete(AAI_L4_SEQUENCE_EVENT, $where1);
                
            $countPersonInformedYpMissing = count($postData['person_informed_missing_team']);
            for ($mo = 0; $mo < $countPersonInformedYpMissing; $mo++) {
                $l4FormPersonInformedYpMissing = array();
                $l4FormPersonInformedYpMissing['incident_id'] = $incidentId;
                $l4FormPersonInformedYpMissing['person_informed'] = $postData['person_informed_missing_team'][$mo];
                $l4FormPersonInformedYpMissing['person_name'] = $postData['name_of_person_informed_missing'][$mo];
                $l4FormPersonInformedYpMissing['badge_number'] = $postData['badge_number_person_missing'][$mo];
                $l4FormPersonInformedYpMissing['contact_number'] = $postData['contact_number_person_missing'][$mo];
                $l4FormPersonInformedYpMissing['contact_email'] = $postData['contact_email_person_missing'][$mo];
                $l4FormPersonInformedYpMissing['informed_by'] = $postData['informed_by_person_missing'][$mo];
                $l4FormPersonInformedYpMissing['date_informed'] = dateformat($postData['date_event'][$mo]);
                $l4FormPersonInformedYpMissing['time_informed'] = dbtimeformat($postData['time_event'][$mo]);
                $l4FormPersonInformedYpMissing['created_date'] = datetimeformat();
            
                $this->common_model->insert(AAI_L4_PERSON_INFORMED_MISSING, $l4FormPersonInformedYpMissing);
            }

            $countPersonInformedYpReturn = count($postData['person_informed_return_team']);
            for ($mo = 0; $mo < $countPersonInformedYpReturn; $mo++) {
                $l4FormPersonInformedYpReturn = array();
                $l4FormPersonInformedYpReturn['incident_id'] = $incidentId;
                $l4FormPersonInformedYpReturn['person_informed'] = $postData['person_informed_return_team'][$mo];
                $l4FormPersonInformedYpReturn['person_name'] = $postData['name_of_person_informed_return'][$mo];
                $l4FormPersonInformedYpReturn['badge_number'] = $postData['badge_number_person_return'][$mo];
                $l4FormPersonInformedYpReturn['contact_number'] = $postData['contact_number_person_return'][$mo];
                $l4FormPersonInformedYpReturn['contact_email'] = $postData['contact_email_person_return'][$mo];
                $l4FormPersonInformedYpReturn['informed_by'] = $postData['informed_by_person_return'][$mo];
                $l4FormPersonInformedYpReturn['date_informed'] = dateformat($postData['person_return_date_event'][$mo]);
                $l4FormPersonInformedYpReturn['time_informed'] = dbtimeformat($postData['person_return_time_event'][$mo]);
                $l4FormPersonInformedYpReturn['created_date'] = datetimeformat();
            
                $this->common_model->insert(AAI_L4_PERSON_INFORMED_RETURN, $l4FormPersonInformedYpReturn);
            }
            
            $countSequenceEvents = count($postData['l4_sequence_who']);
            for ($mo = 0; $mo < $countSequenceEvents; $mo++) {
                $l4FormSequenceEvents = array();
                $l4FormSequenceEvents['incident_id'] = $incidentId;
                $l4FormSequenceEvents['sequence_number'] = $postData['l4seq_sequence_number'][$mo];
                $l4FormSequenceEvents['who_raised'] = $postData['l4_sequence_who'][$mo];
                $l4FormSequenceEvents['What_happened'] = $postData['l4seq_what_happned'][$mo];
                $l4FormSequenceEvents['communication_details'] = $postData['l4seq_communication'][$mo];
                $l4FormSequenceEvents['date'] = dateformat($postData['l4seq_date_event'][$mo]);
                $l4FormSequenceEvents['time'] = dbtimeformat($postData['l4seq_time_event'][$mo]);
                $l4FormSequenceEvents['created_date'] = datetimeformat();
            
                $this->common_model->insert(AAI_L4_SEQUENCE_EVENT, $l4FormSequenceEvents);
            }
        }


        if ($incidentId > 0) {

            if (!empty($postData['l4_is_the_yp_making_complaint']) && $postData['l4_is_the_yp_making_complaint'] == 'Yes') {
                $complaint = 1;
            } else {
                $complaint = 0;
            }

            if (!empty($postData['l4_was_the_yp_injured']) && $postData['l4_was_the_yp_injured'] == 'Yes') {
                $yp_injured = 1;
            } else {
                $yp_injured = 0;
            }

            if (!empty($postData['l4_was_staff_member_injured']) && $postData['l4_was_staff_member_injured'] == 'Yes') {
                $staff_member_injured = 1;
            } else {
                $staff_member_injured = 0;
            }

            if (!empty($postData['l4_was_anyone_else_injured']) && $postData['l4_was_anyone_else_injured'] == 'Yes') {
                $anyone_else_injured = 1;
            } else {
                $anyone_else_injured = 0;
            }


            $reference_number = $postData['l4_reference_number'];
            $updateData = array(
                'is_yp_injured' => $yp_injured,
                'is_yp_complaint' => $complaint,
                'is_staff_injured' => $staff_member_injured,
                'is_other_injured' => $anyone_else_injured,
            );

            $this->common_model->update(AAI_INCIDENT_TYPE_DATA, $updateData, array('incident_id' => $incidentId));
            $l4FormData['incident_id'] = $incidentId;
            $l4FormData['l4_reference_number'] = $reference_number;
            $l4FormData['modified_by'] = $this->session->userdata('LOGGED_IN')['ID'];
            $l4FormData['modified_date'] = datetimeformat();
            $l4_form_id = $postData['l4_form_id'];
            if (!empty($l4_form_id)) {
                $this->common_model->update(AAI_L4_FORM_DATA, $l4FormData, array('l4_form_id' => $l4_form_id));
            } else {
                $l4FormData['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
                $l4FormData['created_date'] = datetimeformat();
                $this->common_model->insert(AAI_L4_FORM_DATA, $l4FormData);
            }
            
            $match = array('form_type' => 'L4', 'status' => 0, 'incident_id' => $incidentId);
            $archive_data = $this->common_model->get_records(AAI_ARCHIVE, '', '', '', $match);

            if (!empty($archive_data)) {
                //update status to archive
                $update_archive = array(
                    'created_date' => datetimeformat(),
                    'status' => 1
                );

                $where = array('form_type' => 'L4', 'status' => 0, 'incident_id' => $incidentId);
                $this->common_model->update(AAI_ARCHIVE, $update_archive, $where);
            }

            /* store archive data */
            $ArchiveincidentData['status'] = 0;
            $ArchiveincidentData['yp_id'] = $this->input->post('yp_id');
            $ArchiveincidentData['incident_id'] = $incidentId;
            $ArchiveincidentData['reference_number'] = $reference_number;
            $ArchiveincidentData['form_json_data'] = !empty($l4FormData) ? json_encode($l4FormData, true) : '';
            $ArchiveincidentData['form_type'] = 'L4';
            $ArchiveincidentData['archive_created_date'] = datetimeformat();
            $ArchiveincidentData['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
            $ArchiveincidentData['created_date'] = datetimeformat();
            $this->common_model->insert(AAI_ARCHIVE, $ArchiveincidentData);
            $l4_form_archive_id = $this->db->insert_id();
            
            $countPersonInformedYpMissing = count($postData['person_informed_missing_team']);
            for ($mo = 0; $mo < $countPersonInformedYpMissing; $mo++) {
                $l4FormPersonInformedYpMissing = array();
                $l4FormPersonInformedYpMissing['archive_id'] = $l4_form_archive_id;
                $l4FormPersonInformedYpMissing['incident_id'] = $incidentId;
                $l4FormPersonInformedYpMissing['person_informed'] = $postData['person_informed_missing_team'][$mo];
                $l4FormPersonInformedYpMissing['person_name'] = $postData['name_of_person_informed_missing'][$mo];
                $l4FormPersonInformedYpMissing['badge_number'] = $postData['badge_number_person_missing'][$mo];
                $l4FormPersonInformedYpMissing['contact_number'] = $postData['contact_number_person_missing'][$mo];
                $l4FormPersonInformedYpMissing['contact_email'] = $postData['contact_email_person_missing'][$mo];
                $l4FormPersonInformedYpMissing['informed_by'] = $postData['informed_by_person_missing'][$mo];
                $l4FormPersonInformedYpMissing['date_informed'] = dateformat($postData['date_event'][$mo]);
                $l4FormPersonInformedYpMissing['time_informed'] = dbtimeformat($postData['time_event'][$mo]);
                $l4FormPersonInformedYpMissing['created_date'] = datetimeformat();
            
                $this->common_model->insert(AAI_L4_PERSON_INFORMED_MISSING_ARCHIVE, $l4FormPersonInformedYpMissing);
            }

            $countPersonInformedYpReturn = count($postData['person_informed_return_team']);
            for ($mo = 0; $mo < $countPersonInformedYpReturn; $mo++) {
                $l4FormPersonInformedYpReturn = array();
                $l4FormPersonInformedYpReturn['archive_id'] = $l4_form_archive_id;
                $l4FormPersonInformedYpReturn['incident_id'] = $incidentId;
                $l4FormPersonInformedYpReturn['person_informed'] = $postData['person_informed_return_team'][$mo];
                $l4FormPersonInformedYpReturn['person_name'] = $postData['name_of_person_informed_return'][$mo];
                $l4FormPersonInformedYpReturn['badge_number'] = $postData['badge_number_person_return'][$mo];
                $l4FormPersonInformedYpReturn['contact_number'] = $postData['contact_number_person_return'][$mo];
                $l4FormPersonInformedYpReturn['contact_email'] = $postData['contact_email_person_return'][$mo];
                $l4FormPersonInformedYpReturn['informed_by'] = $postData['informed_by_person_return'][$mo];
                $l4FormPersonInformedYpReturn['date_informed'] = dateformat($postData['person_return_date_event'][$mo]);
                $l4FormPersonInformedYpReturn['time_informed'] = dbtimeformat($postData['person_return_time_event'][$mo]);
                $l4FormPersonInformedYpReturn['created_date'] = datetimeformat();
            
                $this->common_model->insert(AAI_L4_PERSON_INFORMED_RETURN_ARCHIVE, $l4FormPersonInformedYpReturn);
            }
            
            $countSequenceEvents = count($postData['l4_sequence_who']);
            for ($mo = 0; $mo < $countSequenceEvents; $mo++) {
                $l4FormSequenceEvents = array();
                $l4FormSequenceEvents['archive_id'] = $l4_form_archive_id;
                $l4FormSequenceEvents['incident_id'] = $incidentId;
                $l4FormSequenceEvents['sequence_number'] = $postData['l4seq_sequence_number'][$mo];
                $l4FormSequenceEvents['who_raised'] = $postData['l4_sequence_who'][$mo];
                $l4FormSequenceEvents['What_happened'] = $postData['l4seq_what_happned'][$mo];
                $l4FormSequenceEvents['communication_details'] = $postData['l4seq_communication'][$mo];
                $l4FormSequenceEvents['date'] = dateformat($postData['l4seq_date_event'][$mo]);
                $l4FormSequenceEvents['time'] = dbtimeformat($postData['l4seq_time_event'][$mo]);
                $l4FormSequenceEvents['created_date'] = datetimeformat();
            
                $this->common_model->insert(AAI_L4_SEQUENCE_EVENT_ARCHIVE, $l4FormSequenceEvents);
            }



            if ($draftdata == 1) {
                $form_status = AAI_FORM_SAVE_AS_DRAFT;
            } else {
                $form_status = AAI_FORM_COMPLETED;
            }
            $updateData = array(
                'draft' => $draftdata,
                'reference_number' => $reference_number,
                'yp_id' => $incidentData['yp_id'],
                'incident_id' => $incidentId,
                'care_home_id' => $incidentData['care_home_id'],
                'date_of_incident' => dateformat($postData['date_yp_missing']),
                'form_status' => $form_status,
                'description' => $postData['what_happened'],
                'process_id' => 'L4',
                'created_by' => $this->session->userdata('LOGGED_IN')['ID'],
                'created_date' => datetimeformat(),
                'modified_by' => $this->session->userdata('LOGGED_IN')['ID'],
                'modified_date' => datetimeformat(),
            );

            $aai_main_incident = $this->common_model->get_records(AAI_LIST_MAIN, array("*"), '', '', array('incident_id' => $incidentId, 'reference_number' => $reference_number));

            if (!empty($aai_main_incident)) {
                $this->common_model->update(AAI_LIST_MAIN, $updateData, array('incident_id' => $incidentId, 'reference_number' => $reference_number));
                $list_incident_id = $aai_main_incident[0]['list_main_incident_id'];
            } else {
                $this->common_model->insert(AAI_LIST_MAIN, $updateData);
                $list_incident_id = $this->db->insert_id();
            }



            /* start add report compiler */
            $report_compiler = $this->session->userdata('LOGGED_IN')['ID'];
            $updateData = array(
                'reference_number' => $reference_number,
                'yp_id' => $incidentData['yp_id'],
                'incident_id' => $incidentId,
                'list_main_incident_id' => $list_incident_id,
                'care_home_id' => $incidentData['care_home_id'],
                'report_compiler_id' => $report_compiler,
                'process_id' => 'L4',
                'created_by' => $this->session->userdata('LOGGED_IN')['ID'],
                'created_date' => datetimeformat(),
                'modified_by' => $this->session->userdata('LOGGED_IN')['ID'],
                'modified_date' => datetimeformat(),
            );

            $aai_report_com = $this->common_model->get_records(AAI_REPORT_COMPILER, array("*"), '', '', array('incident_id' => $incidentId, 'reference_number' => $reference_number, 'report_compiler_id' => $report_compiler, 'process_id' => 'L4'));
            if (empty($aai_report_com)) {
                $this->common_model->insert(AAI_REPORT_COMPILER, $updateData);
            }
            /* start add report compiler */

            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($incidentData['yp_id']) ? $incidentData['yp_id'] : '',
                'module_name' => AAI_MODULE,
                'module_field_name' => '',
                'type' => 2
            );
            log_activity($activity);



//            $PDFInformation['yp_details'] = $data['YP_details'][0];
//            $PDFInformation['edit_data'] = $incidentData['modified_date'];
//
//            $PDFHeaderHTML = $this->load->view('aai_pdfHeader', $PDFInformation, true);
//            $PDFFooterHTML = $this->load->view('aai_pdfFooter', $PDFInformation, true);
//
//            //Set Header Footer and Content For PDF
//            $this->m_pdf->pdf->mPDF('utf-8', 'A4', '', '', '10', '10', '45', '25');
//
//            $this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
//            $this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);
//            $data['main_content'] = 'AAI/pdf_incident_l4_process';
//            //$l4FormData=$data['l4FormData'];
//            $data['l4FormData'] = $l4FormData;
//            /* pr($l4FormData);
//              die; */
//            $html = $this->parser->parse('layouts/AAIDataTemplate', $data);
//            $pdfFileName = time() . uniqid() . ".pdf";
//            $pdfFilePath = FCPATH . 'uploads/AAI_notification_form_wise/';
            //die;
            /* remove */


//            $this->m_pdf->pdf->WriteHTML($html);
//            $this->m_pdf->pdf->Output($pdfFilePath . $pdfFileName, 'F');
//            $cpt_pdf_file = $pdfFileName;
            //Store PDF in IBP Folder
            //$this->m_pdf->pdf->Output($pdfFileName, "D");
        }
        
        $incidentTypeData = $this->common_model->get_records(AAI_INCIDENT_TYPE_DATA, '', '', '', array('incident_id' => $incidentId));
        $incidentTypeData = $incidentTypeData[0];
        if ($incidentTypeData['is_yp_injured'] == 1) {
            $redirectForm = AAI_L5_FORM;
        } elseif ($incidentTypeData['is_yp_complaint'] == 1) {
            $redirectForm = AAI_L6_FORM;
        } elseif ($incidentTypeData['is_yp_safeguarding'] == 1) {
            $redirectForm = AAI_L7_FORM;
        } elseif ($incidentTypeData['is_staff_injured'] == 1 || $incidentTypeData['is_other_injured'] == 1) {
            $redirectForm = AAI_L8_FORM;
        } else {
            $redirectForm = AAI_L9_FORM;
        }

        redirect('AAI/edit/' . $incidentId . '/' . $redirectForm);
    }

    /*
    @Author : Ritesh Rana
    @Desc   : Update L5 Form
    @Date   : 10/04/2019
     */

    public function updateL5Form($incidentId = 0)
    {
        $incidentData = $this->common_model->get_records(AAI_MAIN, '', '', '', array('incident_id' => $incidentId));
        $incidentData = $incidentData[0];  

        $postData = $this->input->post();
        $draftdata = (isset($postData['saveAsDraftL5'])) ? $postData['saveAsDraftL5'] : '';
        if ($this->input->is_ajax_request()) {
            $draftdata = 1;
        }
        /*start update sign off data*/
        $this->updateSignoffData($incidentId);
        /*end update sign off data*/
        $l5Form = $this->common_model->get_records(AAI_FORM, '', '', '', array('form_id' => 6));
        if (!empty($l5Form)) {
            $l5FormDataArray = json_decode($l5Form[0]['form_json_data'], true);
            $data       = array();
            $i          = 0;
            foreach ($l5FormDataArray as $row) {
                if (isset($row['name'])) {
                        if ($row['type'] == 'file') {
                            $filename = $row['name'];
                            //get image previous image
                            $match = array('incident_id' => $postData['incident_id']);
                            $l5ImageData = $this->common_model->get_records(AAI_L5_FORM_DATA, array('`' . $row['name'] . '`'), '', '', $match);
                            //delete img
                            if (!empty($postData['hidden_' . $row['name']])) {
                                $delete_img = explode(',', $postData['hidden_' . $row['name']]);
                                $db_images = explode(',', $l5ImageData[0][$filename]);
                                $differentedImage = array_diff($db_images, $delete_img);
                                $l5ImageData[0][$filename] = !empty($differentedImage) ? implode(',', $differentedImage) : '';
                                if (!empty($delete_img)) {
                                    foreach ($delete_img as $img) {
                                        if (file_exists($this->config->item('aai_img_url') . $postData['yp_id'] . '/' . $img)) {
                                            unlink($this->config->item('aai_img_url') . $postData['yp_id'] . '/' . $img);
                                        }
                                        if (file_exists($this->config->item('aai_img_url_small') . $postData['yp_id'] . '/' . $img)) {
                                            unlink($this->config->item('aai_img_url_small') . $postData['yp_id'] . '/' . $img);
                                        }
                                    }
                                }
                            }

                            if (!empty($_FILES[$filename]['name'][0])) {
                                //create dir and give permission
                                /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                createDirectory(array($this->config->item('aai_base_url'), $this->config->item('aai_base_big_url'), $this->config->item('aai_base_big_url') . '/' . $postData['yp_id']));

                                $file_view = $this->config->item('aai_img_url') . $postData['yp_id'];
                                //upload big image
                                $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/index/' . $postData['yp_id']);
                                //upload small image
                                $insertImagesData = array();
                                if (!empty($upload_data)) {
                                    foreach ($upload_data as $imageFiles) {
                                        /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                        createDirectory(array($this->config->item('aai_base_small_url'), $this->config->item('aai_base_small_url') . '/' . $postData['yp_id']));

                                        /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                        if ($imageFiles['is_image'])
                                            $a = do_resize($this->config->item('aai_img_url') . $postData['yp_id'], $this->config->item('aai_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                        array_push($insertImagesData, $imageFiles['file_name']);
                                        if (!empty($insertImagesData)) {
                                            $images = implode(',', $insertImagesData);
                                        }
                                    }
                                    if (!empty($l5ImageData[0][$filename])) {
                                        $images .=',' . $l5ImageData[0][$filename];
                                    }
                                    if (!empty($images)) {
                                        $l5FormData[$row['name']] = !empty($images) ? $images : '';
                                    }
                                }
                            } else {
                                if (!empty($l5ImageData[0][$filename])) {
                                    $l5FormData[$row['name']] = !empty($l5ImageData[0][$filename]) ? $l5ImageData[0][$filename] : '';
                                }
                            }
                        } else {
                            if ($row['type'] != 'button') {
                            if ($row['type'] == 'checkbox-group') {
                                $l5FormData[$row['name']] = !empty($postData[$row['name']]) ? implode(',', $postData[$row['name']]) : '';
                            } elseif ($row['type'] == 'textarea' && $row['subtype'] == 'tinymce') {
                                $l5FormData[$row['name']] = strip_slashes($postData[$row['name']]);
                            } elseif ($row['type'] == 'date') {
                                $l5FormData[$row['name']] = dateformat($postData[$row['name']]);
                            } elseif ($row['type'] == 'text') {
                              if($row['subtype'] == 'time'){
                                $l5FormData[$row['name']] = dbtimeformat($postData[$row['name']]);
                              }else{
                                $l5FormData[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                              }
                            }elseif (isset($row['type']) && $row['type'] == 'select') {
                                if($row['className'] == 'multiple'){
                                    $l5FormData[$row['name']] = implode(',', $postData[$row['name']]);    
                                }elseif($row['className'] == 'bamboo_lookup_multiple'){
                                    $l5FormData[$row['name']] = implode(',', $postData[$row['name']]);    
                                }else{
                                    $l5FormData[$row['name']] = $postData[$row['name']];    
                                }
                                
                            } else {
                                $l5FormData[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                            }
                        }
                    }
                    
                }
                $i++;
            }
            //normal data
            //$l4FormData['person_informed_missing_team']=$postData['person_informed_missing_team'];
            //$l5FormData['l5_report_compiler'] = $postData['l5_report_compiler'];
            $l5FormData['l5_is_complaint']    = $postData['l5_is_complaint'];
            $l5FormData['fm_head']            = $postData['fm_head'];
            $l5FormData['fm_nose']            = $postData['fm_nose'];
            $l5FormData['fm_left_eye']        = $postData['fm_left_eye'];
            $l5FormData['fm_right_eye']       = $postData['fm_right_eye'];
            $l5FormData['fm_mouth']           = $postData['fm_mouth'];
            $l5FormData['fm_left_ear']        = $postData['fm_left_ear'];
            $l5FormData['fm_right_ear']       = $postData['fm_right_ear'];
            $l5FormData['fm_neck']            = $postData['fm_neck'];
            $l5FormData['fm_chest']           = $postData['fm_chest'];
            $l5FormData['fm_abdomen']         = $postData['fm_abdomen'];
            $l5FormData['fm_pelvis']          = $postData['fm_pelvis'];
            $l5FormData['fm_pubis']           = $postData['fm_pubis'];
            $l5FormData['fm_left_thigh']      = $postData['fm_left_thigh'];
            $l5FormData['fm_left_knee']       = $postData['fm_left_knee'];
            $l5FormData['fm_right_knee']      = $postData['fm_right_knee'];
            $l5FormData['fm_left_leg']        = $postData['fm_left_leg'];
            $l5FormData['fm_right_leg']       = $postData['fm_right_leg'];
            $l5FormData['fm_left_ankle']      = $postData['fm_left_ankle'];
            $l5FormData['fm_right_ankle']     = $postData['fm_right_ankle'];
            $l5FormData['fm_left_foot']       = $postData['fm_left_foot'];
            $l5FormData['fm_right_foot']      = $postData['fm_right_foot'];
            $l5FormData['fm_left_shoulder']   = $postData['fm_left_shoulder'];
            $l5FormData['fm_right_shoulder']  = $postData['fm_right_shoulder'];
            $l5FormData['fm_left_arm']        = $postData['fm_left_arm'];
            $l5FormData['fm_right_arm']       = $postData['fm_right_arm'];
            $l5FormData['fm_left_albow']      = $postData['fm_left_albow'];
            $l5FormData['fm_right_albow']     = $postData['fm_right_albow'];
            $l5FormData['fm_left_forearm']    = $postData['fm_left_forearm'];
            $l5FormData['fm_right_forearm']   = $postData['fm_right_forearm'];
            $l5FormData['fm_left_wrist']      = $postData['fm_left_wrist'];
            $l5FormData['fm_right_wrist']     = $postData['fm_right_wrist'];
            $l5FormData['fm_left_hand']       = $postData['fm_left_hand'];
            $l5FormData['fm_right_hand']      = $postData['fm_right_hand'];
            $l5FormData['bm_head']            = $postData['bm_head'];
            $l5FormData['bm_neck']            = $postData['bm_neck'];
            $l5FormData['bm_back']            = $postData['bm_back'];
            $l5FormData['bm_loin']            = $postData['bm_loin'];
            $l5FormData['bm_buttocks']        = $postData['bm_buttocks'];
            $l5FormData['bm_left_hrmstring']  = $postData['bm_left_hrmstring'];
            $l5FormData['bm_right_hrmstring'] = $postData['bm_right_hrmstring'];
            $l5FormData['bm_left_knee']       = $postData['bm_left_knee'];
            $l5FormData['bm_right_knee']      = $postData['bm_right_knee'];
            $l5FormData['bm_left_kalf']       = $postData['bm_left_kalf'];
            $l5FormData['bm_right_kalf']      = $postData['bm_right_kalf'];
            $l5FormData['bm_left_ankle']      = $postData['bm_left_ankle'];
            $l5FormData['bm_right_ankle']     = $postData['bm_right_ankle'];
            $l5FormData['bm_left_sole']       = $postData['bm_left_sole'];
            $l5FormData['bm_right_sole']      = $postData['bm_right_sole'];
            $l5FormData['bm_left_foot']       = $postData['bm_left_foot'];
            $l5FormData['bm_right_foot']      = $postData['bm_right_foot'];
            $l5FormData['bm_left_shoulder']   = $postData['bm_left_shoulder'];
            $l5FormData['bm_right_shoulder']  = $postData['bm_right_shoulder'];
            $l5FormData['bm_left_arm']        = $postData['bm_left_arm'];
            $l5FormData['bm_right_arm']       = $postData['bm_right_arm'];
            $l5FormData['bm_left_albow']      = $postData['bm_left_albow'];
            $l5FormData['bm_right_albow']     = $postData['bm_right_albow'];
            $l5FormData['bm_left_forearm']    = $postData['bm_left_forearm'];
            $l5FormData['bm_right_forearm']   = $postData['bm_right_forearm'];
            $l5FormData['bm_left_wrist']      = $postData['bm_left_wrist'];
            $l5FormData['bm_right_wrist']     = $postData['bm_right_wrist'];
            $l5FormData['bm_left_hand']       = $postData['bm_left_hand'];
            $l5FormData['bm_right_hand']      = $postData['bm_right_hand'];
            $l5FormData['how_did_accident']   = $postData['how_did_accident'];
        }
                $reference_number = $postData['l5_reference_number'];

                $l5FormData['incident_id'] = $incidentId;
                $l5FormData['l5_reference_number'] = $reference_number;
                $l5FormData['created_by']            = $this->session->userdata('LOGGED_IN')['ID'];
                $l5FormData['created_date']          = datetimeformat();
                $l5FormData['modified_by']            = $this->session->userdata('LOGGED_IN')['ID'];
                $l5FormData['modified_date']          = datetimeformat();
                $l5_form_id = $postData['l5_form_id'];   
            //pr($l5_form_id);exit;
    if(!empty($l5_form_id)){
        $this->common_model->update(AAI_L5_FORM_DATA, $l5FormData, array('l5_form_id' => $l5_form_id));    
    }else{
        $this->common_model->insert(AAI_L5_FORM_DATA, $l5FormData);
        $l5_form_id = $this->db->insert_id();
    }


        if(!empty($l5_form_id)){
            //get L5 data
                 $match = array('form_type'=>'L5','status'=>0,'incident_id'=> $incidentId);
                 $archive_data = $this->common_model->get_records(AAI_ARCHIVE,'', '', '', $match);
                 if(!empty($archive_data))
                 {
                 //update status to archive
                     $update_archive = array(
                        'created_date'=>datetimeformat(),
                        'status'=>1
                    );

                    $where = array('form_type'=>'L5','status'=>0,'incident_id'=> $incidentId);
                    $this->common_model->update(AAI_ARCHIVE, $update_archive,$where);
                }
            /*store archive data*/
                $ArchiveincidentData['status'] = 0;
                $ArchiveincidentData['yp_id'] = $this->input->post('yp_id');
                $ArchiveincidentData['incident_id'] = $incidentId;
                $ArchiveincidentData['reference_number'] = $reference_number;
                $ArchiveincidentData['form_json_data'] = !empty($l5FormData) ? json_encode($l5FormData, true) : '';
                $ArchiveincidentData['form_type'] = 'L5';
                $ArchiveincidentData['archive_created_date'] = datetimeformat();
                $ArchiveincidentData['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
                $ArchiveincidentData['created_date'] = datetimeformat();
                $this->common_model->insert(AAI_ARCHIVE, $ArchiveincidentData);
            /*end store data */
        }
        
if ($incidentId > 0) {
 if($draftdata == 1){
        $form_status = AAI_FORM_SAVE_AS_DRAFT;
    }else{
        $form_status = AAI_FORM_COMPLETED;
    }     
    $updateData = array(
      'draft' => $draftdata,
      'reference_number' =>  $reference_number,
      'yp_id' => $incidentData['yp_id'],
      'incident_id' => $incidentId,
      'care_home_id' =>  $incidentData['care_home_id'],
      'date_of_incident' => dateformat($postData['date_injured']),
      'form_status' => $form_status,
      'description' => $postData['how_did_accident'],
      'process_id' => 'L5',
      'created_by' => $this->session->userdata('LOGGED_IN')['ID'],
      'created_date' => datetimeformat(),
      'modified_by' => $this->session->userdata('LOGGED_IN')['ID'],
      'modified_date' => datetimeformat(),
    );

    $aai_main_incident = $this->common_model->get_records(AAI_LIST_MAIN, array("*"), '', '', array('incident_id' => $incidentId,'reference_number' => $reference_number)); 

       if(!empty($aai_main_incident)){
         $this->common_model->update(AAI_LIST_MAIN, $updateData, array('incident_id' => $incidentId,'reference_number' => $reference_number));
         $incident_id = $aai_main_incident[0]['list_main_incident_id'];
       }else{
            $this->common_model->insert(AAI_LIST_MAIN, $updateData);
            $incident_id       = $this->db->insert_id();
       }

/*start add report compiler */
     $report_compiler = $this->session->userdata('LOGGED_IN')['ID'];
    $updateData = array(
      'reference_number' =>  $reference_number,
      'yp_id' => $incidentData['yp_id'],
      'incident_id' => $incidentId,
      'list_main_incident_id' => $incident_id,
      'care_home_id' =>  $incidentData['care_home_id'],
      'report_compiler_id' =>  $report_compiler,
      'process_id' => 'L5',
      'created_by' => $this->session->userdata('LOGGED_IN')['ID'],
      'created_date' => datetimeformat(),
      'modified_by' => $this->session->userdata('LOGGED_IN')['ID'],
      'modified_date' => datetimeformat(),
    );

    $aai_report_com = $this->common_model->get_records(AAI_REPORT_COMPILER, array("*"), '', '', array('incident_id' => $incidentId,'reference_number' =>$reference_number, 'report_compiler_id' => $report_compiler,'process_id'=>'L5')); 
       if(empty($aai_report_com)){
           $this->common_model->insert(AAI_REPORT_COMPILER, $updateData);
       }
/*start add report compiler */
             $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($incidentData['yp_id']) ? $incidentData['yp_id'] : '',
                    'module_name' => AAI_MODULE,
                    'module_field_name' => '',
                    'type' => 2
                );
            log_activity($activity);
        }
        
        $incidentTypeData = $this->common_model->get_records(AAI_INCIDENT_TYPE_DATA, '', '', '', array('incident_id' => $incidentId));
        $incidentTypeData = $incidentTypeData[0];
        if ($incidentTypeData['is_yp_complaint'] == 1) {
            $redirectForm = AAI_L6_FORM;
        } elseif ($incidentTypeData['is_yp_safeguarding'] == 1) {
            $redirectForm = AAI_L7_FORM;
        } elseif ($incidentTypeData['is_staff_injured'] == 1 || $incidentTypeData['is_other_injured'] == 1) {
            $redirectForm = AAI_L8_FORM;
        } else {
            $redirectForm = AAI_L9_FORM;
        }

        redirect('AAI/edit/' . $incidentId . '/' . $redirectForm);
    }

    /*
    @Author : Ritesh Rana
    @Desc   : Insert/Update Incident Data of L1 Form
    @Date   : 12/04/2019
     */

    public function updateL6Form($incidentId = 0)
    {   
        $incidentData = $this->common_model->get_records(AAI_MAIN, '', '', '', array('incident_id' => $incidentId));
        $incidentData = $incidentData[0];    

        $postData = $this->input->post();
        $draftdata = (isset($postData['saveAsDraftL6'])) ? $postData['saveAsDraftL6'] : '';
        if ($this->input->is_ajax_request()) {
            $draftdata = 1;
        }
        /*start update sign off data*/
        $this->updateSignoffData($incidentId);
        /*end update sign off data*/
        $l6Form = $this->common_model->get_records(AAI_FORM, '', '', '', array('form_id' => AAI_L6_FORM_ID));
        if (!empty($l6Form)) {
            $l6FormDataArray = json_decode($l6Form[0]['form_json_data'], true);
            $data       = array();
            $i          = 0;
            foreach ($l6FormDataArray as $row) {
                if (isset($row['name'])) {
                        if ($row['type'] == 'file') {
                               $filename = $row['name'];
                            //get image previous image
                            $match = array('incident_id' => $postData['incident_id']);
                            $l6ImageData = $this->common_model->get_records(AAI_L6_FORM_DATA, array('`' . $row['name'] . '`'), '', '', $match);
                            //delete img
                            if (!empty($postData['hidden_' . $row['name']])) {
                                $delete_img = explode(',', $postData['hidden_' . $row['name']]);
                                $db_images = explode(',', $l6ImageData[0][$filename]);
                                $differentedImage = array_diff($db_images, $delete_img);
                                $l6ImageData[0][$filename] = !empty($differentedImage) ? implode(',', $differentedImage) : '';
                                if (!empty($delete_img)) {
                                    foreach ($delete_img as $img) {
                                        if (file_exists($this->config->item('aai_img_url') . $postData['yp_id'] . '/' . $img)) {
                                            unlink($this->config->item('aai_img_url') . $postData['yp_id'] . '/' . $img);
                                        }
                                        if (file_exists($this->config->item('aai_img_url_small') . $postData['yp_id'] . '/' . $img)) {
                                            unlink($this->config->item('aai_img_url_small') . $postData['yp_id'] . '/' . $img);
                                        }
                                    }
                                }
                            }

                            if (!empty($_FILES[$filename]['name'][0])) {
                                //create dir and give permission
                                /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                createDirectory(array($this->config->item('aai_base_url'), $this->config->item('aai_base_big_url'), $this->config->item('aai_base_big_url') . '/' . $postData['yp_id']));

                                $file_view = $this->config->item('aai_img_url') . $postData['yp_id'];
                                //upload big image
                                $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/index/' . $postData['yp_id']);
                                //upload small image
                                $insertImagesData = array();
                                if (!empty($upload_data)) {
                                    foreach ($upload_data as $imageFiles) {
                                        /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                        createDirectory(array($this->config->item('aai_base_small_url'), $this->config->item('aai_base_small_url') . '/' . $postData['yp_id']));

                                        /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                        if ($imageFiles['is_image'])
                                            $a = do_resize($this->config->item('aai_img_url') . $postData['yp_id'], $this->config->item('aai_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                        array_push($insertImagesData, $imageFiles['file_name']);
                                        if (!empty($insertImagesData)) {
                                            $images = implode(',', $insertImagesData);
                                        }
                                    }
                                    if (!empty($l6ImageData[0][$filename])) {
                                        $images .=',' . $l6ImageData[0][$filename];
                                    }
                                    if (!empty($images)) {
                                        $l6FormData[$row['name']] = !empty($images) ? $images : '';
                                    }
                                }
                            } else {
                                if (!empty($l6ImageData[0][$filename])) {
                                    $l6FormData[$row['name']] = !empty($l6ImageData[0][$filename]) ? $l6ImageData[0][$filename] : '';
                                }
                            }
                        } else {
                            if ($row['type'] != 'button') {
                            if ($row['type'] == 'checkbox-group') {
                                $l6FormData[$row['name']] = !empty($postData[$row['name']]) ? implode(',', $postData[$row['name']]) : '';
                            } elseif ($row['type'] == 'textarea' && $row['subtype'] == 'tinymce') {
                                $l6FormData[$row['name']] = strip_slashes($postData[$row['name']]);
                            } elseif ($row['type'] == 'date') {
                                $l6FormData[$row['name']] = dateformat($postData[$row['name']]);
                            } elseif ($row['type'] == 'text') {
                              if($row['subtype'] == 'time'){
                                $l6FormData[$row['name']] = dbtimeformat($postData[$row['name']]);
                              }else{
                                $l6FormData[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                              }
                            }elseif (isset($row['type']) && $row['type'] == 'select') {
                                if($row['className'] == 'multiple'){
                                    $l6FormData[$row['name']] = implode(',', $postData[$row['name']]);    
                                }elseif($row['className'] == 'bamboo_lookup_multiple'){
                                    $l6FormData[$row['name']] = implode(',', $postData[$row['name']]);    
                                }else{
                                    $l6FormData[$row['name']] = $postData[$row['name']];    
                                }
                                
                            } else {
                                $l6FormData[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                            }
                        }
                        }
                }
                $i++;
            }

                $reference_number = $postData['l6_reference_number'];
                $l6FormData['incident_id'] = $incidentId;
                $l6FormData['l6_reference_number'] = $reference_number;
                $l6FormData['created_by']            = $this->session->userdata('LOGGED_IN')['ID'];
                $l6FormData['created_date']          = datetimeformat();
                $l6FormData['modified_by']            = $this->session->userdata('LOGGED_IN')['ID'];
                $l6FormData['modified_date']          = datetimeformat();
                $l6_form_id = $postData['l6_form_id'];   

    if(!empty($l6_form_id)){
        $this->common_model->update(AAI_L6_FORM_DATA, $l6FormData, array('l6_form_id' => $l6_form_id));    
    }else{
        $this->common_model->insert(AAI_L6_FORM_DATA, $l6FormData);
        $l6_form_id = $this->db->insert_id();
    }
             //get L6 data
                 $match = array('form_type'=>'L6','status'=>0,'incident_id'=> $incidentId);
                 $archive_data = $this->common_model->get_records(AAI_ARCHIVE,'', '', '', $match);
                 if(!empty($archive_data))
                 {
                 //update status to archive
                     $update_archive = array(
                        'created_date'=>datetimeformat(),
                        'status'=>1
                    );
                    $where = array('form_type'=>'L6','status'=>0,'incident_id'=> $incidentId);
                    $this->common_model->update(AAI_ARCHIVE, $update_archive,$where);
                }
            /*store archive data*/
                $ArchiveincidentData['status'] = 0;
                $ArchiveincidentData['yp_id'] = $this->input->post('yp_id');
                $ArchiveincidentData['incident_id'] = $incidentId;
                $ArchiveincidentData['reference_number'] = $reference_number;
                $ArchiveincidentData['form_json_data'] = !empty($l6FormData) ? json_encode($l6FormData, true) : '';
                $ArchiveincidentData['form_type'] = 'L6';
                $ArchiveincidentData['archive_created_date'] = datetimeformat();
                $ArchiveincidentData['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
                $ArchiveincidentData['created_date'] = datetimeformat();
                $this->common_model->insert(AAI_ARCHIVE, $ArchiveincidentData);
                $l6_form_archive_id = $this->db->insert_id();
            /*start Delete Sequence of events data */
            $where1 = array('incident_id' => $incidentId);
            $this->common_model->delete(AAI_L6_SEQUENCE_EVENT, $where1);
            /*End Delete Sequence of events data */
            $l6sequence_number = $postData['l6sequence_number'];

               for ($i = 0; $i < count($l6sequence_number); $i++) {
                    if (!empty($l6sequence_number[$i])) {
                        $l6sequence['incident_id'] = $incidentId;
                        $l6sequence['l6_sequence_number'] = $postData['l6sequence_number'][$i];
                        $l6sequence['who_raised']        = $postData['l6who_raised_complaint'][$i];
                        $l6sequence['What_happened']            = $postData['l6what_happened'][$i];
                        $l6sequence['date']        = dateformat($postData['l6sequence_date'][$i]);
                        $l6sequence['time']   = $postData['l6time_sequence'][$i];
                        $l6sequence['created_date'] = datetimeformat();
                        $l6sequence['modified_date'] = datetimeformat();
                        $this->common_model->insert(AAI_L6_SEQUENCE_EVENT, $l6sequence);

                    /* store data in archive table*/
                        $l6sequenceArchive['archive_id'] = $l6_form_archive_id;
                        $l6sequenceArchive['incident_id'] = $incidentId;
                        $l6sequenceArchive['l6_sequence_number'] = $postData['l6sequence_number'][$i];
                        $l6sequenceArchive['who_raised']        = $postData['l6who_raised_complaint'][$i];
                        $l6sequenceArchive['What_happened']            = $postData['l6what_happened'][$i];
                        $l6sequenceArchive['date']        = dateformat($postData['l6sequence_date'][$i]);
                        $l6sequenceArchive['time']   = $postData['l6time_sequence'][$i];
                        $l6sequenceArchive['created_date'] = datetimeformat();
                        $l6sequenceArchive['modified_date'] = datetimeformat();
                        $this->common_model->insert(AAI_L6_SEQUENCE_ARCHIVE, $l6sequenceArchive);
                    /* end store data in archive table*/
                    }
                }
            /* Sequence of events end */
        }

        
        if ($incidentId > 0) {
            $reference_number = $postData['l6_reference_number'];

        if(!empty($postData['l6_was_the_yp_injured']) && $postData['l6_was_the_yp_injured'] == 'Yes'){
            $yp_injured = 1;
        }else{
            $yp_injured = 0;
        }
        
        if(!empty($postData['l6_was_staff_member_injured']) && $postData['l6_was_staff_member_injured'] == 'Yes'){
            $staff_member_injured = 1;
        }else{
            $staff_member_injured = 0;
        }
            
        if(!empty($postData['l6_was_anyone_else_injured']) && $postData['l6_was_anyone_else_injured'] == 'Yes'){
            $anyone_else_injured = 1;
        }else{
            $anyone_else_injured = 0;
        } 

           
            $updateData   = array(
                'is_yp_injured'      => $yp_injured,
                'is_staff_injured'   => $staff_member_injured,
                'is_other_injured'   => $anyone_else_injured,
            );
            $this->common_model->update(AAI_INCIDENT_TYPE_DATA, $updateData, array('incident_id' => $incidentId));

 if($draftdata == 1){
        $form_status = AAI_FORM_SAVE_AS_DRAFT;
    }else{
        $form_status = AAI_FORM_COMPLETED;
    }     
    $updateData = array(
      'draft' => $draftdata,
      'reference_number' =>  $reference_number,
      'yp_id' => $incidentData['yp_id'],
      'incident_id' => $incidentId,
      'care_home_id' =>  $incidentData['care_home_id'],
      'date_of_incident' => dateformat($postData['l6_date_of_complaint']),
      'form_status' => $form_status,
      'description' => $postData['l6_complaint_detail'],
      'process_id' => 'L6',
      'created_by' => $this->session->userdata('LOGGED_IN')['ID'],
      'created_date' => datetimeformat(),
      'modified_by' => $this->session->userdata('LOGGED_IN')['ID'],
      'modified_date' => datetimeformat(),
    );

    $aai_main_incident = $this->common_model->get_records(AAI_LIST_MAIN, array("*"), '', '', array('incident_id' => $incidentId,'reference_number' => $reference_number)); 

       if(!empty($aai_main_incident)){
         $this->common_model->update(AAI_LIST_MAIN, $updateData, array('incident_id' => $incidentId,'reference_number' => $reference_number));
         $incident_id = $aai_main_incident[0]['list_main_incident_id'];
       }else{
            $this->common_model->insert(AAI_LIST_MAIN, $updateData);
            $incident_id       = $this->db->insert_id();
       }



/*start add report compiler */
     $report_compiler = $this->session->userdata('LOGGED_IN')['ID'];
    $updateData = array(
      'reference_number' =>  $reference_number,
      'yp_id' => $incidentData['yp_id'],
      'incident_id' => $incidentId,
      'list_main_incident_id' => $incident_id,
      'care_home_id' =>  $incidentData['care_home_id'],
      'report_compiler_id' =>  $report_compiler,
      'process_id' => 'L6',
      'created_by' => $this->session->userdata('LOGGED_IN')['ID'],
      'created_date' => datetimeformat(),
      'modified_by' => $this->session->userdata('LOGGED_IN')['ID'],
      'modified_date' => datetimeformat(),
    );

    $aai_report_com = $this->common_model->get_records(AAI_REPORT_COMPILER, array("*"), '', '', array('incident_id' => $incidentId,'reference_number' =>$reference_number, 'report_compiler_id' => $report_compiler,'process_id'=>'L6')); 
       if(empty($aai_report_com)){
           $this->common_model->insert(AAI_REPORT_COMPILER, $updateData);
       }
/*start add report compiler */

            $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($incidentData['yp_id']) ? $incidentData['yp_id'] : '',
                    'module_name' => AAI_MODULE,
                    'module_field_name' => '',
                    'type' => 2
                );
            log_activity($activity);

            $incidentTypeData = $this->common_model->get_records(AAI_INCIDENT_TYPE_DATA, '', '', '', array('incident_id' => $incidentId));
            $incidentTypeData = $incidentTypeData[0];
            if ($incidentTypeData['is_yp_safeguarding'] == 1) {
                $redirectForm = AAI_L7_FORM;
            } elseif ($incidentTypeData['is_staff_injured'] == 1 || $incidentTypeData['is_other_injured'] == 1) {
                $redirectForm = AAI_L8_FORM;
            } else {
                $redirectForm = AAI_L9_FORM;
            }
            redirect('AAI/edit/' . $incidentId . '/' . $redirectForm);
        }
    }

    /*
    @Author : Ritesh Rana
    @Desc   : Insert/Update Incident Data of L7 Form
    @Date   : 17/01/2019
     */

    public function updateL7Form($incidentId = 0)
    {
        $incidentData = $this->common_model->get_records(AAI_MAIN, '', '', '', array('incident_id' => $incidentId));
        $incidentData = $incidentData[0];

        /*end Archive data*/
        $postData = $this->input->post();
        $draftdata = (isset($postData['saveAsDraftL7'])) ? $postData['saveAsDraftL7'] : '';
        if ($this->input->is_ajax_request()) {
            $draftdata = 1;
        }
        /*start update sign off data*/
        $this->updateSignoffData($incidentId);
        /*end update sign off data*/

        $l7Form = $this->common_model->get_records(AAI_FORM, '', '', '', array('form_id' => AAI_L7_FORM_ID));
        if (!empty($l7Form)) {
            $l7FormDataArray = json_decode($l7Form[0]['form_json_data'], true);
            $data       = array();
            $i          = 0;
            foreach ($l7FormDataArray as $row) {
                if (isset($row['name'])) {
                        if ($row['type'] == 'file') {
                               $filename = $row['name'];
                            //get image previous image
                            $match = array('incident_id' => $postData['incident_id']);
                            $l7ImageData = $this->common_model->get_records(AAI_L7_FORM_DATA, array('`' . $row['name'] . '`'), '', '', $match);
                            //delete img
                            if (!empty($postData['hidden_' . $row['name']])) {
                                $delete_img = explode(',', $postData['hidden_' . $row['name']]);
                                $db_images = explode(',', $l7ImageData[0][$filename]);
                                $differentedImage = array_diff($db_images, $delete_img);
                                $l7ImageData[0][$filename] = !empty($differentedImage) ? implode(',', $differentedImage) : '';
                                if (!empty($delete_img)) {
                                    foreach ($delete_img as $img) {
                                        if (file_exists($this->config->item('aai_img_url') . $postData['yp_id'] . '/' . $img)) {
                                            unlink($this->config->item('aai_img_url') . $postData['yp_id'] . '/' . $img);
                                        }
                                        if (file_exists($this->config->item('aai_img_url_small') . $postData['yp_id'] . '/' . $img)) {
                                            unlink($this->config->item('aai_img_url_small') . $postData['yp_id'] . '/' . $img);
                                        }
                                    }
                                }
                            }

                            if (!empty($_FILES[$filename]['name'][0])) {
                                //create dir and give permission
                                /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                createDirectory(array($this->config->item('aai_base_url'), $this->config->item('aai_base_big_url'), $this->config->item('aai_base_big_url') . '/' . $postData['yp_id']));

                                $file_view = $this->config->item('aai_img_url') . $postData['yp_id'];
                                //upload big image
                                $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/index/' . $postData['yp_id']);
                                //upload small image
                                $insertImagesData = array();
                                if (!empty($upload_data)) {
                                    foreach ($upload_data as $imageFiles) {
                                        /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                        createDirectory(array($this->config->item('aai_base_small_url'), $this->config->item('aai_base_small_url') . '/' . $postData['yp_id']));

                                        /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                        if ($imageFiles['is_image'])
                                            $a = do_resize($this->config->item('aai_img_url') . $postData['yp_id'], $this->config->item('aai_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                        array_push($insertImagesData, $imageFiles['file_name']);
                                        if (!empty($insertImagesData)) {
                                            $images = implode(',', $insertImagesData);
                                        }
                                    }
                                    if (!empty($l7ImageData[0][$filename])) {
                                        $images .=',' . $l7ImageData[0][$filename];
                                    }
                                    if (!empty($images)) {
                                        $l7FormData[$row['name']] = !empty($images) ? $images : '';
                                    }
                                }
                            } else {
                                if (!empty($l7ImageData[0][$filename])) {
                                    $l7FormData[$row['name']] = !empty($l7ImageData[0][$filename]) ? $l7ImageData[0][$filename] : '';
                                }
                            }
                        } else {
                            if ($row['type'] != 'button') {
                            if ($row['type'] == 'checkbox-group') {
                                $l7FormData[$row['name']] = !empty($postData[$row['name']]) ? implode(',', $postData[$row['name']]) : '';
                            } elseif ($row['type'] == 'textarea' && $row['subtype'] == 'tinymce') {
                                $l7FormData[$row['name']] = strip_slashes($postData[$row['name']]);
                            } elseif ($row['type'] == 'date') {
                                $l7FormData[$row['name']] = dateformat($postData[$row['name']]);
                            } elseif ($row['type'] == 'text') {
                              if($row['subtype'] == 'time'){
                                $l7FormData[$row['name']] = dbtimeformat($postData[$row['name']]);
                              }else{
                                $l7FormData[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                              }
                            }elseif (isset($row['type']) && $row['type'] == 'select') {
                                if($row['className'] == 'multiple'){
                                    $l7FormData[$row['name']] = implode(',', $postData[$row['name']]);    
                                }elseif($row['className'] == 'bamboo_lookup_multiple'){
                                    $l7FormData[$row['name']] = implode(',', $postData[$row['name']]);    
                                }else{
                                    $l7FormData[$row['name']] = $postData[$row['name']];    
                                }
                                
                            } else {
                                $l7FormData[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                            }
                        }
                        }
                    
                }
                $i++;
            }

                $reference_number = $postData['l7_reference_number'];
                $l7FormData['incident_id'] = $incidentId;
                $l7FormData['l7_reference_number'] = $reference_number;
                $l7FormData['created_by']            = $this->session->userdata('LOGGED_IN')['ID'];
                $l7FormData['created_date']          = datetimeformat();
                $l7FormData['modified_by']            = $this->session->userdata('LOGGED_IN')['ID'];
                $l7FormData['modified_date']          = datetimeformat();
                $l7_form_id = $postData['l7_form_id'];   

    if(!empty($l7_form_id)){
        $this->common_model->update(AAI_L7_FORM_DATA, $l7FormData, array('l7_form_id' => $l7_form_id));    
    }else{
        $this->common_model->insert(AAI_L7_FORM_DATA, $l7FormData);
        $l7_form_id = $this->db->insert_id();
    }


         //get L7 data
                 $match = array('form_type'=>'L7','status'=>0,'incident_id'=> $incidentId);
                 $archive_data = $this->common_model->get_records(AAI_ARCHIVE,'', '', '', $match);
                 if(!empty($archive_data))
                 {
                 //update status to archive
                     $update_archive = array(
                        'created_date'=>datetimeformat(),
                        'status'=>1
                    );
                    $where = array('form_type'=>'L7','status'=>0,'incident_id'=> $incidentId);
                    $this->common_model->update(AAI_ARCHIVE, $update_archive,$where);
                }

            /*store archive data*/
                $ArchiveincidentData['status'] = 0;
                $ArchiveincidentData['yp_id'] = $this->input->post('yp_id');
                $ArchiveincidentData['incident_id'] = $incidentId;
                $ArchiveincidentData['reference_number'] = $reference_number;
                $ArchiveincidentData['form_json_data'] = !empty($l7FormData) ? json_encode($l7FormData, true) : '';
                $ArchiveincidentData['form_type'] = 'L7';
                $ArchiveincidentData['archive_created_date'] = datetimeformat();
                $ArchiveincidentData['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
                $ArchiveincidentData['created_date'] = datetimeformat();
                $this->common_model->insert(AAI_ARCHIVE, $ArchiveincidentData);
                $l7_form_archive_id = $this->db->insert_id();
            /*end store data */
        
            //Repeating section start
            $count_number = count($postData['l7sequence_number']) + 1;

            for ($sd = 1; $sd < $count_number; $sd++) {
                $filename_doc = 'l7supporting_documents' . $sd;
                //get image previous image
                //$l7ArrayDataDoc = json_decode($incidentData['l7_form_data'], true);
                //get image previous image
                $match = array('incident_id' => $postData['incident_id']);
                $l7ArrayDataDoc = $this->common_model->get_records(AAI_L7_SAFEGUARDING_UPDATES, array("l7supporting_documents"), '', '', $match);
                //pr($l7ArrayDataDoc);exit;
                $delete_doc = $sd-1;
                $l7FileDataDoc  = $l7ArrayDataDoc[$delete_doc]['l7supporting_documents'];
                
                //delete imgs
                if (!empty($postData['hidden_' . 'l7supporting_documents' . $sd])) {
                    $delete_img_doc      = explode(',', $postData['hidden_' . 'l7supporting_documents' . $sd]);
                    $db_images_doc       = explode(',', $l7FileDataDoc);
                    $differentedImageDoc = array_diff($db_images_doc, $delete_img_doc);
                    $l7FileDataDoc       = !empty($differentedImageDoc) ? implode(',', $differentedImageDoc) : '';
                    if (!empty($delete_img_doc)) {
                        foreach ($delete_img_doc as $img_doc) {
                            if (file_exists($this->config->item('aai_img_url') . $incidentData['yp_id'] . '/' . $img_doc)) {
                                unlink($this->config->item('aai_img_url') . $incidentData['yp_id'] . '/' . $img_doc);
                            }
                            if (file_exists($this->config->item('aai_img_url_small') . $incidentData['yp_id'] . '/' . $img_doc)) {
                                unlink($this->config->item('aai_img_url_small') . $incidentData['yp_id'] . '/' . $img_doc);
                            }
                        }
                    }
                }

                if (!empty($_FILES[$filename_doc]['name'][0])) {
                    createDirectory(array($this->config->item('aai_base_url'), $this->config->item('aai_base_big_url'), $this->config->item('aai_base_big_url') . '/' . $incidentData['yp_id']));

                    $file_view = $this->config->item('aai_img_url') . $incidentData['yp_id'];
                    //upload big image
                    $upload_data = uploadImage($filename_doc, $file_view, '/' . $this->viewname . '/edit/' . $incidentId . AAI_L7_FORM);
                    //upload small image
                    $insertImagesDataDoc = array();
                    if (!empty($upload_data)) {
                        foreach ($upload_data as $imageFiles) {
                            /* common function replaced by Dhara Bhalala on 29/09/2018 */
                            createDirectory(array($this->config->item('aai_base_small_url'), $this->config->item('aai_base_small_url') . '/' . $incidentData['yp_id']));
                            /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                            if ($imageFiles['is_image']) {
                                $a = do_resize($this->config->item('aai_img_url') . $incidentData['yp_id'], $this->config->item('aai_img_url_small') . $incidentData['yp_id'], $imageFiles['file_name']);
                            }

                            array_push($insertImagesDataDoc, $imageFiles['file_name']);
                            if (!empty($insertImagesDataDoc)) {
                                $imagesDoc = implode(',', $insertImagesDataDoc);
                            }
                        }
                        if ($l7FileDataDoc !== '') {
                            $imagesDoc .= ',' . $l7FileDataDoc;
                        }
                    }
                } else {
                    $imagesDoc = ($l7FileDataDoc !== '') ? $l7FileDataDoc : '';
                }
                $l7SupportingDoc['l7supporting_documents' . $sd] = $imagesDoc;
            }
            /* Sequence of events start */
            /*Start delete Sequence of events data */
                $where1 = array('incident_id' => $incidentId);
                $this->common_model->delete(AAI_L7_SAFEGUARDING_UPDATES, $where1);
            /*End Delete Sequence of events data */
            
            $l7sequence_number = $postData['l7sequence_number'];
               for ($i = 0; $i < count($l7sequence_number); $i++) {
                    if (!empty($l7sequence_number[$i])) {
                        $new_change ++;
                        $l7Sequence['incident_id'] = $incidentId;
                        $l7Sequence['l7_sequence_number'] = $postData['l7sequence_number'][$i];
                        $l7Sequence['l7update_by']        = $postData['l7update_by'][$i];
                        $l7Sequence['l7daily_action_taken']            = $postData['l7daily_action_taken'][$i];
                        $l7Sequence['l7daily_action_outcome']        = $postData['l7daily_action_outcome'][$i];
                        $l7Sequence['l7date_safeguarding']   = dateformat($postData['l7date_safeguarding'][$i]);
                        $l7Sequence['l7time_safeguard']       = $postData['l7time_safeguard'][$i];
                        $l7Sequence['created_date'] = datetimeformat();
                        $l7Sequence['modified_date'] = datetimeformat();
                        $sq = $i+1;
                        $support_doc_data = $l7SupportingDoc['l7supporting_documents' . $sq];
                        $l7Sequence['l7supporting_documents'] = $support_doc_data;    
                        $this->common_model->insert(AAI_L7_SAFEGUARDING_UPDATES, $l7Sequence);

                    /* store L7 Sequence event data in archive*/
                        $l7sequenceArchive['l7archive_id'] = $l7_form_archive_id;
                        $l7sequenceArchive['incident_id'] = $incidentId;
                        $l7sequenceArchive['l7_sequence_number'] = $postData['l7sequence_number'][$i];
                        $l7sequenceArchive['l7update_by']        = $postData['l7update_by'][$i];
                        $l7sequenceArchive['l7daily_action_taken']            = $postData['l7daily_action_taken'][$i];
                        $l7sequenceArchive['l7daily_action_outcome']        = $postData['l7daily_action_outcome'][$i];
                        $l7sequenceArchive['l7supporting_documents']   = $support_doc_data;
                        $l7sequenceArchive['l7date_safeguarding']   = dateformat($postData['l7date_safeguarding'][$i]);
                        $l7sequenceArchive['l7time_safeguard']   = $postData['l7time_safeguard'][$i];
                        $l7sequenceArchive['created_date'] = datetimeformat();
                        $l7sequenceArchive['modified_date'] = datetimeformat();
                        $this->common_model->insert(AAI_L7_SAFEGUARDING_ARCHIVE, $l7sequenceArchive);  
                    /* end store L7 Sequence event data in archive*/    
                }
            }
            /* Sequence of events end */
        }

        //Repeating section end
    if ($incidentId > 0) {
         if($draftdata == 1){
                $form_status = AAI_FORM_SAVE_AS_DRAFT;
            }else{
                $form_status = AAI_FORM_COMPLETED;
            }     
             $updateData = array(
              'draft' => $draftdata,
              'reference_number' =>  $reference_number,
              'yp_id' => $incidentData['yp_id'],
              'incident_id' => $incidentId,
              'care_home_id' =>  $incidentData['care_home_id'],
              'date_of_incident' => dateformat($postData['date_of_disclosure']),
              'form_status' => $form_status,
              'description' => $postData['detail_concern'],
              'process_id' => 'L7',
              'created_by' => $this->session->userdata('LOGGED_IN')['ID'],
              'created_date' => datetimeformat(),
              'modified_by' => $this->session->userdata('LOGGED_IN')['ID'],
              'modified_date' => datetimeformat(),
            );

            $aai_main_incident = $this->common_model->get_records(AAI_LIST_MAIN, array("*"), '', '', array('incident_id' => $incidentId,'reference_number' => $reference_number)); 
               if(!empty($aai_main_incident)){
                $this->common_model->update(AAI_LIST_MAIN, $updateData, array('incident_id' => $incidentId,'reference_number' => $reference_number));
                $incident_id = $aai_main_incident[0]['list_main_incident_id'];
               }else{
                     $this->common_model->insert(AAI_LIST_MAIN, $updateData);
                     $incident_id       = $this->db->insert_id();
               }

        /*start add report compiler */
             $report_compiler = $this->session->userdata('LOGGED_IN')['ID'];
            $updateData = array(
              'reference_number' =>  $reference_number,
              'yp_id' => $incidentData['yp_id'],
              'incident_id' => $incidentId,
              'list_main_incident_id' => $incident_id,
              'care_home_id' =>  $incidentData['care_home_id'],
              'report_compiler_id' =>  $report_compiler,
              'process_id' => 'L7',
              'created_by' => $this->session->userdata('LOGGED_IN')['ID'],
              'created_date' => datetimeformat(),
              'modified_by' => $this->session->userdata('LOGGED_IN')['ID'],
              'modified_date' => datetimeformat(),
            );

            $aai_report_com = $this->common_model->get_records(AAI_REPORT_COMPILER, array("*"), '', '', array('incident_id' => $incidentId,'reference_number' =>$reference_number, 'report_compiler_id' => $report_compiler,'process_id'=>'L7'));   
               if(empty($aai_report_com)){
                   $this->common_model->insert(AAI_REPORT_COMPILER, $updateData);
               }
        /*start add report compiler */

                    /* audit log start*/
                    $activity = array(
                            'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                            'yp_id' => !empty($incidentData['yp_id']) ? $incidentData['yp_id'] : '',
                            'module_name' => AAI_MODULE,
                            'module_field_name' => '',
                            'type' => 2
                        );
                    log_activity($activity);
                    /* audit log end*/
                    
            $incidentTypeData = $this->common_model->get_records(AAI_INCIDENT_TYPE_DATA, '', '', '', array('incident_id' => $incidentId));
            $incidentTypeData = $incidentTypeData[0];
            if ($incidentTypeData['is_staff_injured'] == 1 || $incidentTypeData['is_other_injured'] == 1) {
                $redirectForm = AAI_L8_FORM;
            } else {
                $redirectForm = AAI_L9_FORM;
            }

            redirect('AAI/edit/' . $incidentId . '/' . $redirectForm);
        }
                
                
    }
	
	 /*
      @Author : Nikunj Ghelani
      @Desc   : Update L5 Form
      @Date   : 06/02/2019
     */

    public function updateL8Form($incidentId = 0) {

        $incidentData = $this->common_model->get_records(AAI_MAIN, '', '', '', array('incident_id' => $incidentId));
        $incidentData = $incidentData[0];

        $postData = $this->input->post();
        $draftdata = (isset($postData['saveAsDraftL8'])) ? $postData['saveAsDraftL8'] : '';
        if ($this->input->is_ajax_request()) {
            $draftdata = 1;
        }
        /*  pr($postData);
         die;  */
         /*start update sign off data*/
        $this->updateSignoffData($incidentId);
        /*end update sign off data*/ 
        $l8Form = $this->common_model->get_records(AAI_FORM, '', '', '', array('form_id' => AAI_L8_FORM_ID));
        if (!empty($l8Form)) {
            $l8FormDataArray = json_decode($l8Form[0]['form_json_data'], true);
            $data       = array();
            $i          = 0;
            foreach ($l8FormDataArray as $row) {
                if (isset($row['name'])) {
                        if ($row['type'] == 'file') {
                               $filename = $row['name'];
                            //get image previous image
                            $match = array('incident_id' => $postData['incident_id']);
                            $l8ImageData = $this->common_model->get_records(AAI_L7_FORM_DATA, array('`' . $row['name'] . '`'), '', '', $match);
                            //delete img
                            if (!empty($postData['hidden_' . $row['name']])) {
                                $delete_img = explode(',', $postData['hidden_' . $row['name']]);
                                $db_images = explode(',', $l8ImageData[0][$filename]);
                                $differentedImage = array_diff($db_images, $delete_img);
                                $l8ImageData[0][$filename] = !empty($differentedImage) ? implode(',', $differentedImage) : '';
                                if (!empty($delete_img)) {
                                    foreach ($delete_img as $img) {
                                        if (file_exists($this->config->item('aai_img_url') . $postData['yp_id'] . '/' . $img)) {
                                            unlink($this->config->item('aai_img_url') . $postData['yp_id'] . '/' . $img);
                                        }
                                        if (file_exists($this->config->item('aai_img_url_small') . $postData['yp_id'] . '/' . $img)) {
                                            unlink($this->config->item('aai_img_url_small') . $postData['yp_id'] . '/' . $img);
                                        }
                                    }
                                }
                            }

                            if (!empty($_FILES[$filename]['name'][0])) {
                                //create dir and give permission
                                /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                createDirectory(array($this->config->item('aai_base_url'), $this->config->item('aai_base_big_url'), $this->config->item('aai_base_big_url') . '/' . $postData['yp_id']));

                                $file_view = $this->config->item('aai_img_url') . $postData['yp_id'];
                                //upload big image
                                $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/index/' . $postData['yp_id']);
                                //upload small image
                                $insertImagesData = array();
                                if (!empty($upload_data)) {
                                    foreach ($upload_data as $imageFiles) {
                                        /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                        createDirectory(array($this->config->item('aai_base_small_url'), $this->config->item('aai_base_small_url') . '/' . $postData['yp_id']));

                                        /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                        if ($imageFiles['is_image'])
                                            $a = do_resize($this->config->item('aai_img_url') . $postData['yp_id'], $this->config->item('aai_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                        array_push($insertImagesData, $imageFiles['file_name']);
                                        if (!empty($insertImagesData)) {
                                            $images = implode(',', $insertImagesData);
                                        }
                                    }
                                    if (!empty($l8ImageData[0][$filename])) {
                                        $images .=',' . $l8ImageData[0][$filename];
                                    }
                                    if (!empty($images)) {
                                        $l8FormData[$row['name']] = !empty($images) ? $images : '';
                                    }
                                }
                            } else {
                                if (!empty($l8ImageData[0][$filename])) {
                                    $l8FormData[$row['name']] = !empty($l8ImageData[0][$filename]) ? $l8ImageData[0][$filename] : '';
                                }
                            }
                        } else {
                            if ($row['type'] != 'button') {
                            if ($row['type'] == 'checkbox-group') {
                                $l8FormData[$row['name']] = !empty($postData[$row['name']]) ? implode(',', $postData[$row['name']]) : '';
                            } elseif ($row['type'] == 'textarea' && $row['subtype'] == 'tinymce') {
                                $l8FormData[$row['name']] = strip_slashes($postData[$row['name']]);
                            } elseif ($row['type'] == 'date') {
                                $l8FormData[$row['name']] = dateformat($postData[$row['name']]);
                            } elseif ($row['type'] == 'text') {
                              if($row['subtype'] == 'time'){
                                $l8FormData[$row['name']] = dbtimeformat($postData[$row['name']]);
                              }else{
                                $l8FormData[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                              }
                            }elseif (isset($row['type']) && $row['type'] == 'select') {
                                if($row['className'] == 'multiple'){
                                    $l8FormData[$row['name']] = implode(',', $postData[$row['name']]);    
                                }elseif($row['className'] == 'bamboo_lookup_multiple'){
                                    $l8FormData[$row['name']] = implode(',', $postData[$row['name']]);    
                                }else{
                                    $l8FormData[$row['name']] = $postData[$row['name']];    
                                }
                                
                            } else {
                                $l8FormData[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                            }
                        }
                        }
                    
                }
                $i++;
            }

                $reference_number = $postData['l8_reference_number'];
                $l8FormData['incident_id'] = $incidentId;
                $l8FormData['l8_reference_number'] = $reference_number;
                $l8FormData['created_by']            = $this->session->userdata('LOGGED_IN')['ID'];
                $l8FormData['created_date']          = datetimeformat();
                $l8FormData['modified_by']            = $this->session->userdata('LOGGED_IN')['ID'];
                $l8FormData['modified_date']          = datetimeformat();
                $l8_form_id = $postData['l8_form_id'];   

    if(!empty($l8_form_id)){
        $this->common_model->update(AAI_L8_FORM_DATA, $l8FormData, array('l8_form_id' => $l8_form_id));    
    }else{
        $this->common_model->insert(AAI_L8_FORM_DATA, $l8FormData);
        $l8_form_id = $this->db->insert_id();
    }
}
            
            //get L8 data
                 $match = array('form_type'=>'L8','status'=>0,'incident_id'=> $incidentId);
                 $archive_data = $this->common_model->get_records(AAI_ARCHIVE,'', '', '', $match);
                 if(!empty($archive_data))
                 {
                 //update status to archive
                     $update_archive = array(
                        'created_date'=>datetimeformat(),
                        'status'=>1
                    );
                    $where = array('form_type'=>'L8','status'=>0,'incident_id'=> $incidentId);
                    $this->common_model->update(AAI_ARCHIVE, $update_archive,$where);
                }
            /*store archive data*/
                $ArchiveincidentData['status'] = 0;
                $ArchiveincidentData['yp_id'] = $this->input->post('yp_id');
                $ArchiveincidentData['incident_id'] = $incidentId;
                $ArchiveincidentData['reference_number'] = $reference_number;
                $ArchiveincidentData['form_json_data'] = !empty($l8FormData) ? json_encode($l8FormData, true) : '';
                $ArchiveincidentData['form_type'] = 'L8';
                $ArchiveincidentData['archive_created_date'] = datetimeformat();
                $ArchiveincidentData['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
                $ArchiveincidentData['created_date'] = datetimeformat();
                $this->common_model->insert(AAI_ARCHIVE, $ArchiveincidentData);
            /*end store data in archive table */

    if($draftdata == 1){
        $form_status = AAI_FORM_SAVE_AS_DRAFT;
    }else{
        $form_status = AAI_FORM_COMPLETED;
    }         
             $updateData = array(
      'draft' => $draftdata,
      'reference_number' =>  $reference_number,
      'yp_id' => $incidentData['yp_id'],
      'incident_id' => $incidentId,
      'care_home_id' =>  $incidentData['care_home_id'],
      'date_of_incident' => dateformat($postData['l8_date_of_incident']),
      'form_status' => $form_status,
      'description' => '',
      'process_id' => 'L8',
      'created_by' => $this->session->userdata('LOGGED_IN')['ID'],
      'created_date' => datetimeformat(),
      'modified_by' => $this->session->userdata('LOGGED_IN')['ID'],
      'modified_date' => datetimeformat(),
    );

    $aai_main_incident = $this->common_model->get_records(AAI_LIST_MAIN, array("*"), '', '', array('incident_id' => $incidentId,'reference_number' => $reference_number)); 

       if(!empty($aai_main_incident)){
         $this->common_model->update(AAI_LIST_MAIN, $updateData, array('incident_id' => $incidentId,'reference_number' => $reference_number));
         $incident_id = $aai_main_incident[0]['list_main_incident_id'];
       }else{
            $this->common_model->insert(AAI_LIST_MAIN, $updateData);
            $incident_id       = $this->db->insert_id();
       }


/*start add report compiler */
     $report_compiler = $this->session->userdata('LOGGED_IN')['ID'];
    $updateData = array(
      'reference_number' =>  $reference_number,
      'yp_id' => $incidentData['yp_id'],
      'incident_id' => $incidentId,
      'list_main_incident_id' => $incident_id,
      'care_home_id' =>  $incidentData['care_home_id'],
      'report_compiler_id' =>  $report_compiler,
      'process_id' => 'L8',
      'created_by' => $this->session->userdata('LOGGED_IN')['ID'],
      'created_date' => datetimeformat(),
      'modified_by' => $this->session->userdata('LOGGED_IN')['ID'],
      'modified_date' => datetimeformat(),
    );

    $aai_report_com = $this->common_model->get_records(AAI_REPORT_COMPILER, array("*"), '', '', array('incident_id' => $incidentId,'reference_number' =>$reference_number, 'report_compiler_id' => $report_compiler,'process_id'=>'L8'));   
       if(empty($aai_report_com)){
           $this->common_model->insert(AAI_REPORT_COMPILER, $updateData);
       }
/*start add report compiler */

            /* audit log start*/
            $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($incidentData['yp_id']) ? $incidentData['yp_id'] : '',
                    'module_name' => AAI_MODULE,
                    'module_field_name' => '',
                    'type' => 2
                );
            log_activity($activity);
            /* audit log end*/
//            $table = AAI_EMAIL_TEMPLATE . ' as aae';
//        $match = "aae.status !=2 AND aae.subject='l8formnotification'";
//        $fields = array("aae.*,ar.*");
//        $join_tables = array(AAI_RECEIPT . ' as ar' => 'ar.receipt_id=aae.recipient_type');
//        $data['template_type'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $match, '', '', '', '', '');
//
//
//
//        //pr($data['template_type']);
//        //echo sizeof($data['template_type']);
//        if (sizeof($data['template_type'] > 1)) {
//
//            $tomail = explode(',', $data['template_type'][0]['receipt_email']);
//            //pr($tomail);
//            foreach ($tomail as $mailto) {
//                //pr($mailto);
//                $toEmailId = $mailto;
//                $email = md5($toEmailId);
//                $find = array('{NAME}', '{LINK}');
//                $replace = array(
//                    'NAME' => 'nikunj',
//                    'LINK' => 'ghelani',);
//                $emailSubject = $data['template_type'][0]['subject'];
//                $emailBody = $data['template_type'][0]['body'];
//
//                $finalEmailBody = str_replace($find, $replace, $emailBody);
//
//                $this->common_model->sendEmail($toEmailId, $emailSubject, $emailBody, FROM_EMAIL_ID);
//            }
//        } else {
//
//            $toEmailId = $data['template_type'][0]['receipt_email'];
//            $email = md5($toEmailId);
//            $find = array('{NAME}', '{LINK}');
//            $replace = array(
//                'NAME' => 'nikunj',
//                'LINK' => 'ghelani',);
//            $emailSubject = $data['template_type'][0]['subject'];
//            $emailBody = $data['template_type'][0]['body'];
//
//            $finalEmailBody = str_replace($find, $replace, $emailBody);
//
//            $this->common_model->sendEmail($toEmailId, $emailSubject, $emailBody, FROM_EMAIL_ID);
//        }
        redirect('AAI/edit/' . $incidentId . '/' . AAI_L9_FORM);
    }
	
	 /*
      @Author : Nikunj Ghelani
      @Desc   : Update L5 Form
      @Date   : 18/01/2019
     */

    public function updateL9Form($incidentId = 0) {

        $incidentData = $this->common_model->get_records(AAI_MAIN, '', '', '', array('incident_id' => $incidentId));
        $incidentData = $incidentData[0];
        $postData = $this->input->post();
        $draftdata = (isset($postData['saveAsDraftL9'])) ? $postData['saveAsDraftL9'] : '';
        if ($this->input->is_ajax_request()) {
            $draftdata = 1;
        }
        /* pr($postData);
          die; */
         /*start update sign off data*/
        $this->updateSignoffData($incidentId);
        /*end update sign off data*/ 
        $l9Form = $this->common_model->get_records(AAI_FORM, '', '', '', array('form_id' => AAI_L9_FORM_ID));
        if (!empty($l9Form)) {
            $l9FormDataArray = json_decode($l9Form[0]['form_json_data'], TRUE);
            $l9FormData = array();
            $i = 0;
            foreach ($l9FormDataArray as $row) {
                if (isset($row['name'])) {
                    if ($row['type'] == 'file') {
                        $filename = $row['name'];
                        //get image previous image
                        $match = array('incident_id' => $postData['incident_id']);
                        $l9ImageData = $this->common_model->get_records(AAI_L9_FORM_DATA, array('`' . $row['name'] . '`'), '', '', $match);
                        //delete img
                        if (!empty($postData['hidden_' . $row['name']])) {
                            $delete_img = explode(',', $postData['hidden_' . $row['name']]);
                            $db_images = explode(',', $l9ImageData[0][$filename]);
                            $differentedImage = array_diff($db_images, $delete_img);
                            $l9ImageData[0][$filename] = !empty($differentedImage) ? implode(',', $differentedImage) : '';
                            if (!empty($delete_img)) {
                                foreach ($delete_img as $img) {
                                    if (file_exists($this->config->item('aai_img_url') . $postData['yp_id'] . '/' . $img)) {
                                        unlink($this->config->item('aai_img_url') . $postData['yp_id'] . '/' . $img);
                                    }
                                    if (file_exists($this->config->item('aai_img_url_small') . $postData['yp_id'] . '/' . $img)) {
                                        unlink($this->config->item('aai_img_url_small') . $postData['yp_id'] . '/' . $img);
                                    }
                                }
                            }
                        }

                        if (!empty($_FILES[$filename]['name'][0])) {
                            //create dir and give permission
                            /* common function replaced by Dhara Bhalala on 29/09/2018 */
                            createDirectory(array($this->config->item('aai_base_url'), $this->config->item('aai_base_big_url'), $this->config->item('aai_base_big_url') . '/' . $postData['yp_id']));

                            $file_view = $this->config->item('aai_img_url') . $postData['yp_id'];
                            //upload big image
                            $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/index/' . $postData['yp_id']);
                            //upload small image
                            $insertImagesData = array();
                            if (!empty($upload_data)) {
                                foreach ($upload_data as $imageFiles) {
                                    /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                    createDirectory(array($this->config->item('aai_base_small_url'), $this->config->item('aai_base_small_url') . '/' . $postData['yp_id']));

                                    /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                    if ($imageFiles['is_image'])
                                        $a = do_resize($this->config->item('aai_img_url') . $postData['yp_id'], $this->config->item('aai_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                    array_push($insertImagesData, $imageFiles['file_name']);
                                    if (!empty($insertImagesData)) {
                                        $images = implode(',', $insertImagesData);
                                    }
                                }
                                if (!empty($l9ImageData[0][$filename])) {
                                    $images .=',' . $l9ImageData[0][$filename];
                                }
                                if (!empty($images)) {
                                    $l9FormData[$row['name']] = !empty($images) ? $images : '';
                                }
                            }
                        } else {
                            if (!empty($l9ImageData[0][$filename])) {
                                $l9FormData[$row['name']] = !empty($l9ImageData[0][$filename]) ? $l9ImageData[0][$filename] : '';
                            }
                        }
                    } else {
                        if ($row['type'] != 'button') {
                            if (!empty($postData[$row['name']])) {
                                if ($row['type'] == 'date') {
                                    $l9FormData[$row['name']] = dateformat($postData[$row['name']]);
                                } elseif ($row['subtype'] == 'time') {
                                    $l9FormData[$row['name']] = dbtimeformat($postData[$row['name']]);
                                } else if ($row['type'] == 'checkbox-group') {
                                    $l9FormData[$row['name']] = !empty($postData[$row['name']]) ? implode(',', $postData[$row['name']]) : '';
                                } elseif ($row['type'] == 'textarea' && $row['subtype'] == 'tinymce') {
                                    $l9FormData[$row['name']] = strip_slashes($postData[$row['name']]);
                                } elseif ($row['type'] == 'select') {
                                    if ($row['className'] == 'multiple') {
                                        $l9FormData[$row['name']] = implode(',', $postData[$row['name']]);
                                    } elseif ($row['className'] == 'bamboo_lookup_multiple') {
                                        $l9FormData[$row['name']] = implode(',', $postData[$row['name']]);
                                    } else {
                                        $l9FormData[$row['name']] = $postData[$row['name']];
                                    }
                                } else {
                                    $l9FormData[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                                }
                            } else {
                                $l9FormData[$row['name']] = !empty($postData[$row['name']]) ? $postData[$row['name']] : '';
                            }
                        }
                    }
                }
                $i++;
            }
            //normal data
            $reference_number = $postData['l9_reference_number'];
            $l9FormData['incident_id'] = $incidentId;
            $l9FormData['l9_reference_number'] = $reference_number;
            $l9FormData['modified_by'] = $this->session->userdata('LOGGED_IN')['ID'];
            $l9FormData['modified_date'] = datetimeformat();
            $l9_form_id = $postData['l9_form_id'];
            
            if (!empty($l9_form_id)) {
                $this->common_model->update(AAI_L9_FORM_DATA, $l9FormData, array('l9_form_id' => $l9_form_id));
                /* audit log start */
                $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($incidentData['yp_id']) ? $incidentData['yp_id'] : '',
                    'module_name' => AAI_MODULE_L9_FORM,
                    'module_field_name' => '',
                    'type' => 2
                );
                log_activity($activity);
                /* audit log end */
            } else {
                $l9FormData['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
                $l9FormData['created_date'] = datetimeformat();
                $this->common_model->insert(AAI_L9_FORM_DATA, $l9FormData);
                /* audit log start */
                $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($incidentData['yp_id']) ? $incidentData['yp_id'] : '',
                    'module_name' => AAI_MODULE_L9_FORM,
                    'module_field_name' => '',
                    'type' => 1
                );
                log_activity($activity);
                /* audit log end */
            }
            
            $match = array('form_type' => 'L9', 'status' => 0, 'incident_id' => $incidentId);
            $archive_data = $this->common_model->get_records(AAI_ARCHIVE, '', '', '', $match);

            if (!empty($archive_data)) {
                //update status to archive
                $update_archive = array(
                    'created_date' => datetimeformat(),
                    'status' => 1
                );

                $where = array('form_type' => 'L9', 'status' => 0, 'incident_id' => $incidentId);
                $this->common_model->update(AAI_ARCHIVE, $update_archive, $where);
            }

            /* store archive data */
            $ArchiveincidentData['status'] = 0;
            $ArchiveincidentData['yp_id'] = $this->input->post('yp_id');
            $ArchiveincidentData['incident_id'] = $incidentId;
            $ArchiveincidentData['reference_number'] = $reference_number;
            $ArchiveincidentData['form_json_data'] = !empty($l9FormData) ? json_encode($l9FormData, true) : '';
            $ArchiveincidentData['form_type'] = 'L9';
            $ArchiveincidentData['archive_created_date'] = datetimeformat();
            $ArchiveincidentData['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
            $ArchiveincidentData['created_date'] = datetimeformat();
            $this->common_model->insert(AAI_ARCHIVE, $ArchiveincidentData);
        }
        
        if ($incidentId > 0) {

            if ($draftdata == 1) {
                $form_status = AAI_FORM_SAVE_AS_DRAFT;
            } else {
                $form_status = AAI_FORM_COMPLETED;
            }

            $updateData = array(
                'draft' => $draftdata,
                'reference_number' => $reference_number,
                'yp_id' => $incidentData['yp_id'],
                'incident_id' => $incidentId,
                'care_home_id' => $incidentData['care_home_id'],
                'date_of_incident' => dateformat($postData['l9_date_of_incident']),
                'form_status' => $form_status,
                'description' => '',
                'process_id' => 'L9',
                'created_by' => $this->session->userdata('LOGGED_IN')['ID'],
                'created_date' => datetimeformat(),
                'modified_by' => $this->session->userdata('LOGGED_IN')['ID'],
                'modified_date' => datetimeformat(),
            );
            $aai_main_incident = $this->common_model->get_records(AAI_LIST_MAIN, array("*"), '', '', array('incident_id' => $incidentId, 'reference_number' => $reference_number));
            if (!empty($aai_main_incident)) {
                $this->common_model->update(AAI_LIST_MAIN, $updateData, array('incident_id' => $incidentId, 'reference_number' => $reference_number));
                $incident_id = $aai_main_incident[0]['list_main_incident_id'];
            } else {
                $this->common_model->insert(AAI_LIST_MAIN, $updateData);
                $incident_id = $this->db->insert_id();
            }

            /* start add report compiler */
            $report_compiler = $this->session->userdata('LOGGED_IN')['ID'];
            $updateData = array(
                'reference_number' => $reference_number,
                'yp_id' => $incidentData['yp_id'],
                'incident_id' => $incidentId,
                'list_main_incident_id' => $incident_id,
                'care_home_id' => $incidentData['care_home_id'],
                'report_compiler_id' => $report_compiler,
                'process_id' => 'L9',
                'created_by' => $this->session->userdata('LOGGED_IN')['ID'],
                'created_date' => datetimeformat(),
                'modified_by' => $this->session->userdata('LOGGED_IN')['ID'],
                'modified_date' => datetimeformat(),
            );

            $aai_report_com = $this->common_model->get_records(AAI_REPORT_COMPILER, array("*"), '', '', array('incident_id' => $incidentId, 'reference_number' => $reference_number, 'report_compiler_id' => $report_compiler, 'process_id' => 'L9'));
            if (empty($aai_report_com)) {
                $this->common_model->insert(AAI_REPORT_COMPILER, $updateData);
            }
            /* start add report compiler */            

//            $table = AAI_EMAIL_TEMPLATE . ' as aae';
//            $match = "aae.status !=2 AND aae.subject='l9formnotification'";
//            $fields = array("aae.*,ar.*");
//            $join_tables = array(AAI_RECEIPT . ' as ar' => 'ar.receipt_id=aae.recipient_type');
//            $data['template_type'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $match, '', '', '', '', '');
//
//
//
//
//            $size_of_template = sizeof($data['template_type']);
//            //echo sizeof($data['template_type']);
//            if ($size_of_template > 1) {
//
//
//                $tomail = explode(',', $data['template_type'][0]['receipt_email']);
//                //pr($tomail);
//                foreach ($tomail as $mailto) {
//                    //pr($mailto);
//                    $toEmailId = $mailto;
//                    $email = md5($toEmailId);
//                    $find = array('{NAME}', '{LINK}');
//                    $replace = array(
//                        'NAME' => 'nikunj',
//                        'LINK' => 'ghelani',);
//                    $emailSubject = $data['template_type'][0]['subject'];
//                    $emailBody = $data['template_type'][0]['body'];
//
//                    $finalEmailBody = str_replace($find, $replace, $emailBody);
//
//                    $this->common_model->sendEmail($toEmailId, $emailSubject, $emailBody, FROM_EMAIL_ID);
//                }
//            } else {
//                //die('no');
//
//                $toEmailId = $data['template_type'][0]['receipt_email'];
//                $email = md5($toEmailId);
//                $find = array('{NAME}', '{LINK}');
//                $replace = array(
//                    'NAME' => 'nikunj',
//                    'LINK' => 'ghelani',);
//                //$emailSubject = $data['template_type'][0]['subject'];
//                $emailSubject = 'l9formnotification';
//                $emailBody = $data['template_type'][0]['body'];
//
//                $finalEmailBody = str_replace($find, $replace, $emailBody);
//
//                $this->common_model->sendEmail($toEmailId, $emailSubject, $emailBody, FROM_EMAIL_ID);
//            }
        }
        redirect('AAI/view/' . $incidentId);
    }

    /*
    @Author : Ritesh Rana
    @Desc   : Insert/Update Incident Data of L7 Form
    @Date   : 17/01/2019
     */

    public function updateReviewForm($incidentId = 0)
    {
        $incidentData = $this->common_model->get_records(AAI_MAIN, '', '', '', array('incident_id' => $incidentId));
        $incidentData = $incidentData[0];

        $postData   = $this->input->post();
        $reviewForm = $this->common_model->get_records(AAI_FORM, '', '', '', array('form_id' => REVIEW));
        if (!empty($reviewForm)) {
            $reviewFormData = json_decode($reviewForm[0]['form_json_data'], true);
            $data           = array();
            $i              = 0;
            foreach ($reviewFormData as $row) {
                if (isset($row['name'])) {
                    if ($row['type'] != 'button') {
                        if ($row['type'] == 'checkbox-group') {
                            $reviewFormData[$i]['value'] = (isset($postData[$row['name']]) && !empty($postData[$row['name']])) ? implode(',', $postData[$row['name']]) : '';
                        } else if ($row['type'] == 'file') {
                            $filename = $row['name'];
                            //get image previous image
                            $ReviewArrayData = json_decode($incidentData['review_form_data'], true);
                            foreach ($ReviewArrayData as $value) {
                                if ($value['type'] == 'header') {
                                    $header_count[] = $value;
                                }
                            }
                            $header_data    = count($header_count);
                            $columm_no      = array_column($ReviewArrayData, 'name');
                            $arrayKey       = array_search($row['name'], $columm_no);
                            $arrayCount     = $arrayKey + $header_data;
                            $ReviewFileData = $ReviewArrayData[$arrayCount]['value'];
                            //delete img
                            if (!empty($postData['hidden_' . $row['name']])) {
                                $delete_img       = explode(',', $postData['hidden_' . $row['name']]);
                                $db_images        = explode(',', $ReviewFileData);
                                $differentedImage = array_diff($db_images, $delete_img);
                                $ReviewFileData   = !empty($differentedImage) ? implode(',', $differentedImage) : '';
                                if (!empty($delete_img)) {
                                    foreach ($delete_img as $img) {
                                        if (file_exists($this->config->item('aai_img_url') . $incidentData['yp_id'] . '/' . $img)) {
                                            unlink($this->config->item('aai_img_url') . $incidentData['yp_id'] . '/' . $img);
                                        }
                                        if (file_exists($this->config->item('aai_img_url_small') . $incidentData['yp_id'] . '/' . $img)) {
                                            unlink($this->config->item('aai_img_url_small') . $incidentData['yp_id'] . '/' . $img);
                                        }
                                    }
                                }
                            }

                            if (!empty($_FILES[$filename]['name'][0])) {
                                createDirectory(array($this->config->item('aai_base_url'), $this->config->item('aai_base_big_url'), $this->config->item('aai_base_big_url') . '/' . $incidentData['yp_id']));

                                $file_view = $this->config->item('aai_img_url') . $incidentData['yp_id'];
                                //upload big image
                                $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/edit/' . $incidentId . REVIEW);
                                //upload small image
                                $insertImagesData = array();
                                if (!empty($upload_data)) {
                                    foreach ($upload_data as $imageFiles) {
                                        /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                        createDirectory(array($this->config->item('aai_base_small_url'), $this->config->item('aai_base_small_url') . '/' . $incidentData['yp_id']));

                                        /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                        if ($imageFiles['is_image']) {
                                            $a = do_resize($this->config->item('aai_img_url') . $incidentData['yp_id'], $this->config->item('aai_img_url_small') . $incidentData['yp_id'], $imageFiles['file_name']);
                                        }

                                        array_push($insertImagesData, $imageFiles['file_name']);
                                        if (!empty($insertImagesData)) {
                                            $images = implode(',', $insertImagesData);
                                        }
                                    }
                                    if ($ReviewFileData !== '') {
                                        $images .= ',' . $ReviewFileData;
                                    }
                                }
                            } else {
                                $images = ($ReviewFileData !== '') ? $ReviewFileData : '';
                            }
                            $reviewFormData[$i]['value'] = $images;
                        } else {
                            $reviewFormData[$i]['value'] = str_replace("'", "\'", $postData[$row['name']]);
                        }
                    }
                }
                $i++;
            }

        }
        //Repeating section end
        $table                 = AAI_DROPDOWN . ' as dr';
        $where                 = array("d.status" => "1", "dr.status" => "1");
        $fields                = array("dr.dropdown_id", "dr.title", "dr.prefix", "count(d.option_id) as total_options", "GROUP_CONCAT( DISTINCT CONCAT(d.option_id,'|',d.title) ORDER BY d.dropdown_id DESC SEPARATOR ';') as dropdown_options");
        $joinTables            = array(AAI_DROPDOWN_OPTION . ' as d' => 'd.dropdown_id = dr.dropdown_id');
        $groupBy               = "dr.dropdown_id";
        $data['dropdown_data'] = $this->common_model->get_records($table, $fields, $joinTables, 'left', '', '', '', '', '', '', $groupBy, $where);
        foreach ($reviewFormData as $key => $value) {
            if (isset($value['type']) && $value['type'] == 'select') {
                foreach ($data['dropdown_data'] as $key1 => $value1) {
                    if ($value['description'] == $value1['prefix']) {
                        if ($value1['total_options'] > 0) {
                            $optionsArray = explode(';', $value1['dropdown_options']);
                            foreach ($optionsArray as $op => $v) {
                                $OpArray                                      = explode('|', $v);
                                $finalOptionsArray[$OpArray[0]]               = $OpArray[1];
                                $reviewFormData[$key]['values'][$op]['label'] = $OpArray[1];
                                $reviewFormData[$key]['values'][$op]['value'] = $OpArray[0];
                            }
                        }
                    }
                }
            }
        }

        if(!empty($postData['is_the_yp_making_a_complaint']) && $postData['is_the_yp_making_a_complaint'] == 'Yes'){
            $complaint = 1;
        }else{
            $complaint = 0;
        }


        if(!empty($postData['staff_safeguarding_concern']) && $postData['staff_safeguarding_concern'] == 'Yes'){
            $safeguarding = 1;
        }else{
            $safeguarding = 0;
        }


        if ($incidentId > 0) {
            $updateData = array(
                'review_form_data' => !empty($reviewFormData) ? json_encode($reviewFormData, true) : '',
                'review_status'    => 1,
                'modified_by'      => $this->session->userdata('LOGGED_IN')['ID'],
                'modified_date'    => datetimeformat(),
                 'is_yp_complaint'    => $complaint,
                 'is_yp_safeguarding' => $safeguarding,                  
            );
            $this->common_model->update(AAI_MAIN, $updateData, array('incident_id' => $incidentId));
            $redirectForm = REVIEW;
            redirect('AAI/view/' . $incidentId . '/' . $redirectForm);
        }
    }

    /*
    @Author : Ritesh Rana
    @Desc   : Insert/Update Incident Data of Manager review Form
    @Date   : 04/02/2019
     */

    public function updateManagerReviewForm($incidentId = 0)
    {
        $incidentData = $this->common_model->get_records(AAI_MAIN, '', '', '', array('incident_id' => $incidentId));
        $incidentData = $incidentData[0];

        $postData          = $this->input->post();
        $managerreviewForm = $this->common_model->get_records(AAI_FORM, '', '', '', array('form_id' => MANAGER_REVIEW));
        if (!empty($managerreviewForm)) {
            $ManagersreviewFormData = json_decode($managerreviewForm[0]['form_json_data'], true);
            $data                   = array();
            $i                      = 0;
            foreach ($ManagersreviewFormData as $row) {
                if (isset($row['name'])) {
                    if ($row['type'] != 'button') {
                        if ($row['type'] == 'checkbox-group') {
                            $ManagersreviewFormData[$i]['value'] = (isset($postData[$row['name']]) && !empty($postData[$row['name']])) ? implode(',', $postData[$row['name']]) : '';
                        } else if ($row['type'] == 'file') {
                            $filename = $row['name'];
                            //get image previous image
                            $MangerReviewArrayData = json_decode($incidentData['review_form_data'], true);
                            foreach ($MangerReviewArrayData as $value) {
                                if ($value['type'] == 'header') {
                                    $header_count[] = $value;
                                }
                            }
                            $header_data    = count($header_count);
                            $columm_no      = array_column($MangerReviewArrayData, 'name');
                            $arrayKey       = array_search($row['name'], $columm_no);
                            $arrayCount     = $arrayKey + $header_data;
                            $ReviewFileData = $MangerReviewArrayData[$arrayCount]['value'];
                            //delete img
                            if (!empty($postData['hidden_' . $row['name']])) {
                                $delete_img       = explode(',', $postData['hidden_' . $row['name']]);
                                $db_images        = explode(',', $ReviewFileData);
                                $differentedImage = array_diff($db_images, $delete_img);
                                $ReviewFileData   = !empty($differentedImage) ? implode(',', $differentedImage) : '';
                                if (!empty($delete_img)) {
                                    foreach ($delete_img as $img) {
                                        if (file_exists($this->config->item('aai_img_url') . $incidentData['yp_id'] . '/' . $img)) {
                                            unlink($this->config->item('aai_img_url') . $incidentData['yp_id'] . '/' . $img);
                                        }
                                        if (file_exists($this->config->item('aai_img_url_small') . $incidentData['yp_id'] . '/' . $img)) {
                                            unlink($this->config->item('aai_img_url_small') . $incidentData['yp_id'] . '/' . $img);
                                        }
                                    }
                                }
                            }

                            if (!empty($_FILES[$filename]['name'][0])) {
                                createDirectory(array($this->config->item('aai_base_url'), $this->config->item('aai_base_big_url'), $this->config->item('aai_base_big_url') . '/' . $incidentData['yp_id']));

                                $file_view = $this->config->item('aai_img_url') . $incidentData['yp_id'];
                                //upload big image
                                $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/edit/' . $incidentId . REVIEW);
                                //upload small image
                                $insertImagesData = array();
                                if (!empty($upload_data)) {
                                    foreach ($upload_data as $imageFiles) {
                                        /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                        createDirectory(array($this->config->item('aai_base_small_url'), $this->config->item('aai_base_small_url') . '/' . $incidentData['yp_id']));

                                        /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                        if ($imageFiles['is_image']) {
                                            $a = do_resize($this->config->item('aai_img_url') . $incidentData['yp_id'], $this->config->item('aai_img_url_small') . $incidentData['yp_id'], $imageFiles['file_name']);
                                        }

                                        array_push($insertImagesData, $imageFiles['file_name']);
                                        if (!empty($insertImagesData)) {
                                            $images = implode(',', $insertImagesData);
                                        }
                                    }
                                    if ($ReviewFileData !== '') {
                                        $images .= ',' . $ReviewFileData;
                                    }
                                }
                            } else {
                                $images = ($ReviewFileData !== '') ? $ReviewFileData : '';
                            }
                            $ManagersreviewFormData[$i]['value'] = $images;
                        } else {
                            $ManagersreviewFormData[$i]['value'] = str_replace("'", "\'", $postData[$row['name']]);
                        }
                    }
                }
                $i++;
            }

        }
        //Repeating section end
        $table                 = AAI_DROPDOWN . ' as dr';
        $where                 = array("d.status" => "1", "dr.status" => "1");
        $fields                = array("dr.dropdown_id", "dr.title", "dr.prefix", "count(d.option_id) as total_options", "GROUP_CONCAT( DISTINCT CONCAT(d.option_id,'|',d.title) ORDER BY d.dropdown_id DESC SEPARATOR ';') as dropdown_options");
        $joinTables            = array(AAI_DROPDOWN_OPTION . ' as d' => 'd.dropdown_id = dr.dropdown_id');
        $groupBy               = "dr.dropdown_id";
        $data['dropdown_data'] = $this->common_model->get_records($table, $fields, $joinTables, 'left', '', '', '', '', '', '', $groupBy, $where);
        foreach ($ManagersreviewFormData as $key => $value) {
            if (isset($value['type']) && $value['type'] == 'select') {
                foreach ($data['dropdown_data'] as $key1 => $value1) {
                    if ($value['description'] == $value1['prefix']) {
                        if ($value1['total_options'] > 0) {
                            $optionsArray = explode(';', $value1['dropdown_options']);
                            foreach ($optionsArray as $op => $v) {
                                $OpArray                                              = explode('|', $v);
                                $finalOptionsArray[$OpArray[0]]                       = $OpArray[1];
                                $ManagersreviewFormData[$key]['values'][$op]['label'] = $OpArray[1];
                                $ManagersreviewFormData[$key]['values'][$op]['value'] = $OpArray[0];
                            }
                        }
                    }
                }
            }
        }

        if(!empty($postData['manager_safeguarding_concern']) && $postData['manager_safeguarding_concern'] == 'Yes'){
            $safeguarding = 1;
        }else{
            $safeguarding = 0;
        }

        if ($incidentId > 0) {
            $updateData = array(
                'manager_review_form_data' => !empty($ManagersreviewFormData) ? json_encode($ManagersreviewFormData, true) : '',
                'manager_review_status'    => 1,
                'modified_by'              => $this->session->userdata('LOGGED_IN')['ID'],
                'modified_date'            => datetimeformat(),
                'is_yp_safeguarding' => $safeguarding,
            );
            $this->common_model->update(AAI_MAIN, $updateData, array('incident_id' => $incidentId));
            $redirectForm = REVIEW;
            redirect('AAI/view/' . $incidentId . '/' . $redirectForm);
        }
    }

    /*
    @Author : Dhara Bhalala
    @Desc   : Edit Incident Form
    @Date   : 17/12/2018
    */

    public function edit($incidentId, $formNumber = 1)
    {
        $incidentData = $this->common_model->get_records(AAI_MAIN, '', '', '', array('incident_id' => $incidentId));        
        $incidentData = $incidentData[0];
        
        $allForms     = $this->common_model->get_records(AAI_FORM, '', '', '');
        $allForms     = array_combine(range(1, count($allForms)), array_values($allForms));
        /* type of incident form start */
        $incidentTypeData = $this->common_model->get_records(AAI_INCIDENT_TYPE_DATA, '', '', '', array('incident_id' => $incidentId));
        $incidentTypeData = $incidentTypeData[0];
        $data = array(
            'is_pi'              => $incidentTypeData['is_pi'],
            'is_yp_missing'      => $incidentTypeData['is_yp_missing'],
            'is_yp_injured'      => $incidentTypeData['is_yp_injured'],
            'is_yp_complaint'    => $incidentTypeData['is_yp_complaint'],
            'is_yp_safeguarding' => $incidentTypeData['is_yp_safeguarding'],
            'is_staff_injured'   => $incidentTypeData['is_staff_injured'],
            'is_other_injured'   => $incidentTypeData['is_other_injured'],
            'is_l1'   				=> $incidentTypeData['is_l1'],
            'incident_type_id'   				=> $incidentTypeData['incident_type_id'],
        );
        

        /* type of incident form start */

        /* entry form start */
        $entryForm = $allForms[AAI_MAIN_ENTRY_FORM_ID];
        if (!empty($entryForm)) {
            $data['entry_form_data'] = json_decode($entryForm['form_json_data'], true);
        }
        $table                 = AAI_DROPDOWN . ' as dr';
        $where                 = array("d.status" => "1", "dr.status" => "1");
        $fields                = array("dr.dropdown_id", "dr.title", "dr.prefix", "count(d.option_id) as total_options", "GROUP_CONCAT( DISTINCT CONCAT(d.option_id,'|',d.title) ORDER BY d.option_id SEPARATOR ';') as dropdown_options");
        $joinTables            = array(AAI_DROPDOWN_OPTION . ' as d' => 'd.dropdown_id = dr.dropdown_id');
        $groupBy               = "dr.dropdown_id";
        $data['dropdown_data'] = $this->common_model->get_records($table, $fields, $joinTables, 'left', '', '', '', '', '', '', $groupBy, $where);

        foreach ($data['entry_form_data'] as $key => $value) {
            if (isset($value['type']) && $value['type'] == 'select') {
                foreach ($data['dropdown_data'] as $key1 => $value1) {
                    if ($value['description'] == $value1['prefix']) {
                        if ($value1['total_options'] > 0) {
                            $optionsArray = explode(';', $value1['dropdown_options']);
                            foreach ($optionsArray as $op => $v) {
                                $OpArray                                               = explode('|', $v);
                                $finalOptionsArray[$OpArray[0]]                        = $OpArray[1];
                                $data['entry_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                $data['entry_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                            }
                        }
                    }
                }
            }
        }

        //prefix for type data
        foreach ($data['dropdown_data'] as $key1 => $value1) {
            if ($value1['prefix'] == 'pre_outside_agency') {
                if ($value1['total_options'] > 0) {
                    $optionsArray = explode(';', $value1['dropdown_options']);
                    foreach ($optionsArray as $op => $v) {
                        $OpArray                                  = explode('|', $v);
                        $finalOptionsArray[$OpArray[0]]           = $OpArray[1];
                        $data['pre_outside_agency'][$op]['label'] = $OpArray[1];
                        $data['pre_outside_agency'][$op]['value'] = $OpArray[0];
                    }
                }
            }
        }

        //prefix for pre_outside_agency data
        foreach ($data['dropdown_data'] as $key1 => $value1) {
            if ($value1['prefix'] == 'type') {
                if ($value1['total_options'] > 0) {
                    $optionsArray = explode(';', $value1['dropdown_options']);
                    foreach ($optionsArray as $op => $v) {
                        $OpArray                        = explode('|', $v);
                        $finalOptionsArray[$OpArray[0]] = $OpArray[1];
                        $data['type'][$op]['label']     = $OpArray[1];
                        $data['type'][$op]['value']     = $OpArray[0];
                    }
                }
            }
        }

        //prefix for Position_of_yp data
        foreach ($data['dropdown_data'] as $key1 => $value1) {
            if ($value1['prefix'] == 'position_of_yp') {
                if ($value1['total_options'] > 0) {
                    $optionsArray = explode(';', $value1['dropdown_options']);
                    foreach ($optionsArray as $op => $v) {
                        $OpArray                              = explode('|', $v);
                        $finalOptionsArray[$OpArray[0]]       = $OpArray[1];
                        $data['position_of_yp'][$op]['label'] = $OpArray[1];
                        $data['position_of_yp'][$op]['value'] = $OpArray[0];
                    }
                }
            }
        }


        //prefix for Position_of_yp data
        foreach ($data['dropdown_data'] as $key1 => $value1) {
            if ($value1['prefix'] == 'persons_infromed') {
                if ($value1['total_options'] > 0) {
                    $optionsArray = explode(';', $value1['dropdown_options']);
                    foreach ($optionsArray as $op => $v) {
                        $OpArray                              = explode('|', $v);
                        $finalOptionsArray[$OpArray[0]]       = $OpArray[1];
                        $data['persons_infromed'][$op]['label'] = $OpArray[1];
                        $data['persons_infromed'][$op]['value'] = $OpArray[0];
                    }
                }
            }
        }


        $mainEntryIncident = $this->common_model->get_records(AAI_ENTRY_FORM_DATA, '', '', '', array('incident_id' => $incidentId));
        if (isset($mainEntryIncident) && !empty($mainEntryIncident)) {
            $editMainEntryData = $mainEntryIncident[0];
            foreach ($data['entry_form_data'] as $key => $value) {
                if (isset($value['name'])) {
                    $data['entry_form_data'][$key]['value'] = str_replace("\'", "'", $editMainEntryData[$value['name']]);
                }
            }
            $data['entry_form_id'] = $editMainEntryData['entry_form_id'];
            $data['reporting_user']        = $editMainEntryData['reporting_user'];
        }
        

        $emailMatch = '(email LIKE "%_@__%.__%")';
        $nfcUsers   = $this->common_model->get_records(LOGIN, array('login_id as user_id', 'firstname as first_name', 'lastname as last_name', 'email'), '', '', '', '', '', '', '', '', '', $emailMatch);

        function appendNFCType1($n)
        {
            $n['user_type']     = 'N';
            $n['job_title']     = '';
            $n['work_location'] = '';
            return $n;
        }
        $nfcUsers    = array_map("appendNFCType1", $nfcUsers);
        $bambooUsers = $this->common_model->get_records(BAMBOOHR_USERS, array('user_id', 'first_name', 'last_name', 'email', 'job_title', 'work_location'), '', '', '', '', '', '', '', '', '', $emailMatch);

        function appendBambooType1($n)
        {
            $n['user_type'] = 'B';
            return $n;
        }
        $bambooUsers            = array_map("appendBambooType1", $bambooUsers);
        $data['bambooNfcUsers'] = array_merge($bambooUsers, $nfcUsers);
        $data['loggedInUser']   = $this->session->userdata['LOGGED_IN'];
        $table                  = AAI_MAIN . ' as a';
        $join_tables            = array(YP_DETAILS . ' as yp' => 'a.yp_id=yp.yp_id', CARE_HOME . ' as c' => 'c.care_home_id=yp.care_home');
        $dateQuery              = 'date(a.created_date) >= CURDATE() - INTERVAL 14 DAY ';
        $data['YPIncidentData'] = $this->common_model->get_records($table, '', $join_tables, '', array('a.yp_id' => $incidentData['yp_id'], 'a.incident_id !=' => $incidentId), '', '', '', '', '', '', $dateQuery);

        /*make reference number start */
            $yp_details     = YpDetails($incidentData['yp_id'], array("yp_initials"));
            $refIncidentId  = str_pad($incidentId, 8, '0', STR_PAD_LEFT);
            $dateOfIncident = date('dmy');
            
        /*end*/

        /* entry form end */
        /* L1 start */
        $l1Form = $allForms[AAI_L1_FORM_ID];
        if (!empty($l1Form)) {
            $data['l1_form_data'] = json_decode($l1Form['form_json_data'], true);
            foreach ($data['l1_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l1_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l1_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }

            $L1incident = $this->common_model->get_records(AAI_L1_FORM_DATA, '', '', '', array('incident_id' => $incidentId));
            $L1incidentData = $L1incident[0];
            $data['l1_data'] = $L1incidentData;
            if (isset($L1incidentData) && !empty($L1incidentData)) {
                $editl1Data = $L1incidentData;
                foreach ($data['l1_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l1_form_data'][$key]['value'] = str_replace("\'", "'", $editl1Data[$value['name']]);
                    }
                }
                
                $data['l1_total_duration']  = $editl1Data['l1_total_duration'];
                $data['l1_form_id']  = $editl1Data['l1_form_id'];
                $data['l1reference_number'] = $editl1Data['l1_reference_number'];
                
            }else{
                $data['l1reference_number'] = 'L1' . substr($yp_details[0]['yp_initials'], 0, 3) . $dateOfIncident . $refIncidentId;
            }
        }
        /* L1 end */
        /* L2 start */
        $l2Form = $allForms[AAI_L2NL3_FORM_ID];
        if (!empty($l2Form)) {
            $data['l2_form_data'] = json_decode($l2Form['form_json_data'], true);
            foreach ($data['l2_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l2_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l2_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }

             $L2incident = $this->common_model->get_records(AAI_L2_L3_FORM_DATA, '', '', '', array('incident_id' => $incidentId));
            $L2incidentData = $L2incident[0];
                $data['l2_data'] = $L2incidentData;
                $data['l2_form_id'] = $L2incidentData['l2_l3_form_id'];
            if (isset($L2incidentData) && !empty($L2incidentData)) {
                $editl2Data = $L2incidentData;
                foreach ($data['l2_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l2_form_data'][$key]['value'] = str_replace("\'", "'", $editl2Data[$value['name']]);
                    }
                }

                $data['l2reference_number'] = $editl2Data['l2_l3_reference_number'];
                $data['l2_total_duration']  = $editl2Data['l2_total_duration'];
                
        /* start code by ritesh Rana */
        /* start Sequence of events */
            $sortfield = 'time';
            $sortby    = 'asc';
            $table = AAI_L2_L3_SEQUENCE_EVENT . ' as sq';
            $where = array("sq.incident_id" => $incidentId);
            $fields = array("sq.*");
            $l2seqresult = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where);
            $data['l2sequence_events'] = $l2seqresult;
        /* end Sequence of events */
        /* start Medical Observations */
            $table = AAI_L2_L3_MEDICAL_OBSERVATION . ' as mo';
            $where = array("mo.incident_id" => $incidentId);
            $fields = array("mo.*");
            $l2seqresult_mo = $this->common_model->get_records($table, $fields, '', '', $where);
            $data['l2medical_observations'] = $l2seqresult_mo;
        /* End Medical Observations */
        /* end code by ritesh Rana */
        }else{
                $data['l2reference_number'] = 'L2' . substr($yp_details[0]['yp_initials'], 0, 3) . $dateOfIncident . $refIncidentId;
            }
        }
        /* L2 end */
        
        if ($data['is_l1'] == 1) {
            $data['l1date_of_incident'] = $editl1Data['l1_start_date'];
            $data['l1time_of_incident'] = $editl1Data['l1_start_time'];
        }else if($data['is_pi'] == 1){
            $data['l1date_of_incident'] = $editl2Data['l2_start_date'];
            $data['l1time_of_incident'] = $editl2Data['l2_start_time'];
        }

        /* L4 form start */
        $l4Form = $allForms[AAI_L4_FORM_ID];
        if (!empty($l4Form)) {
            $data['l4_form_data'] = json_decode($l4Form['form_json_data'], true);
            foreach ($data['l4_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l4_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l4_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }
            $L4incident = $this->common_model->get_records(AAI_L4_FORM_DATA, '', '', '', array('incident_id' => $incidentId));

            if (isset($L4incident) && !empty($L4incident)) {
                $editl4Data = $L4incident[0];
                $data['l4_data'] = $editl4Data;
                foreach ($data['l4_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l4_form_data'][$key]['value'] = str_replace("\'", "'", $editl4Data[$value['name']]);
                    }
                }

                $data['l4calculate_notification_worker'] = $editl4Data['calculate_notification_worker'];
                $data['l4calculate_notification_missing'] = $editl4Data['calculate_notification_missing'];
                $data['l4_total_duration'] = $editl4Data['l4_total_duration'];
                
                /* start person informed yp missing */
                $table = AAI_L4_PERSON_INFORMED_MISSING . ' as mo';
                $where = array("mo.incident_id" => $incidentId);
                $fields = array("mo.*");
                $l4missing_yp = $this->common_model->get_records($table, $fields, '', '', $where);
                $data['l4missing_yp'] = $l4missing_yp;
                /* End person informed yp missing */
                
                /* start person informed yp return */
                $table = AAI_L4_PERSON_INFORMED_RETURN . ' as mo';
                $where = array("mo.incident_id" => $incidentId);
                $fields = array("mo.*");
                $l4return_data = $this->common_model->get_records($table, $fields, '', '', $where);
                $data['l4return_data'] = $l4return_data;
                /* End person informed yp return */
                
                /* start person informed yp return */
                $table = AAI_L4_SEQUENCE_EVENT . ' as mo';
                $where = array("mo.incident_id" => $incidentId);
                $fields = array("mo.*");
                $l4sequenceEvents = $this->common_model->get_records($table, $fields, '', '', $where,'','','','date,time','ASC');
                $data['l4sequence_data'] = $l4sequenceEvents;
                /* End person informed yp return */
                
                $data['l4_form_id'] = $editl4Data['l4_form_id'];
                $data['l4reference_number'] = ($editl4Data['l4_reference_number'] !=='')? $editl4Data['l4_reference_number']:'L4' . substr($yp_details[0]['yp_initials'], 0, 3) . $dateOfIncident . $refIncidentId;
            } else {
                $data['l4reference_number'] = 'L4' . substr($yp_details[0]['yp_initials'], 0, 3) . $dateOfIncident . $refIncidentId;
            }
        }

        /* L4 form end */

        /* L5 form start */
        $l5Form = $allForms[AAI_L5_FORM_ID];
        if (!empty($l5Form)) {
            $data['l5_form_data'] = json_decode($l5Form['form_json_data'], true);

            foreach ($data['l5_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l5_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l5_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }


             $L5incident = $this->common_model->get_records(AAI_L5_FORM_DATA, '', '', '', array('incident_id' => $incidentId));
            $L5incidentData = $L5incident[0];

            $data['l5_form_id'] = $L5incidentData['l5_form_id'];
            if (isset($L5incidentData) && !empty($L5incidentData)) {
                $data['l5_data'] = $L5incidentData;
                $editl5Data = $L5incidentData;
                foreach ($data['l5_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l5_form_data'][$key]['value'] = str_replace("\'", "'", $editl5Data[$value['name']]);
                    }
                }

                $data['l5reference_number'] = $L5incidentData['l5_reference_number'];
                $data['l5_body_map']        = $editl5Data;

            }else{
                $data['l5reference_number'] = 'L5' . substr($yp_details[0]['yp_initials'], 0, 3) . $dateOfIncident . $refIncidentId;
            }
        }

        /* L5 form end */

        /* L6 start */
        $l6Form = $allForms[AAI_L6_FORM_ID];
        if (!empty($l6Form)) {
            $data['l6_form_data'] = json_decode($l6Form['form_json_data'], true);
            foreach ($data['l6_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l6_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l6_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }


             $L6incident = $this->common_model->get_records(AAI_L6_FORM_DATA, '', '', '', array('incident_id' => $incidentId));

            $L6incidentData = $L6incident[0];
            $data['l6_form_id'] = $L6incidentData['l6_form_id'];
            if (isset($L6incidentData) && !empty($L6incidentData)) {
                $editl6Data = $L6incidentData;
                $data['l6_data'] = $L6incidentData;
                foreach ($data['l6_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l6_form_data'][$key]['value'] = str_replace("\'", "'", $editl6Data[$value['name']]);
                    }
                }
                $data['l6reference_number'] = $editl6Data['l6_reference_number'];
            }else{
                 $data['l6reference_number'] = 'L6' . substr($yp_details[0]['yp_initials'], 0, 3) . $dateOfIncident . $refIncidentId;
            }
        /* start code by ritesh Rana */
        /* start Sequence of events */
            $sortfield = 'date,time';
            $sortby    = 'asc';
            $table = AAI_L6_SEQUENCE_EVENT . ' as sq';
            $where = array("sq.incident_id" => $incidentId);
            $fields = array("sq.*");
            $l6seqresult = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where);
            $data['l6sequence_data'] = $l6seqresult;
        /* end Sequence of events */
    }
        /* L6 end */

        /* L7 start */
        $l7Form = $allForms[AAI_L7_FORM_ID];
        if (!empty($l7Form)) {
            $data['l7_form_data'] = json_decode($l7Form['form_json_data'], true);
            foreach ($data['l7_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l7_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l7_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }

        /* start code by ritesh Rana */
            $L7incident = $this->common_model->get_records(AAI_L7_FORM_DATA, '', '', '', array('incident_id' => $incidentId));
            $L7incidentData = $L7incident[0];
            $data['l7_form_id'] = $L7incidentData['l7_form_id'];
            if (isset($L7incidentData) && !empty($L7incidentData)) {
                $editl7Data = $L7incidentData;
                $data['l7_data'] = $L7incidentData;
                foreach ($data['l7_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l7_form_data'][$key]['value'] = str_replace("\'", "'", $editl7Data[$value['name']]);
                    }
                }
            $data['l7reference_number'] = $editl7Data['l7_reference_number'];
        /* start Sequence of events */
            $sortfield = 'l7date_safeguarding,l7time_safeguard';
            $sortby    = 'asc';
            $table = AAI_L7_SAFEGUARDING_UPDATES . ' as sq';
            $where = array("sq.incident_id" => $incidentId);
            $fields = array("sq.*");
            $l7seqresult = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where);
            $data['l7sequence_data'] = $l7seqresult;
        /* end Sequence of events */
        }else{
               $data['l7reference_number'] = 'L7' . substr($yp_details[0]['yp_initials'], 0, 3) . $dateOfIncident . $refIncidentId;
            }
        }
        /* L7 end */
         /* end code by ritesh Rana */

        /* nikunj ghelani L8 form start */
        $l8Form = $allForms[AAI_L8_FORM_ID];
        if (!empty($l8Form)) {
            $data['l8_form_data'] = json_decode($l8Form['form_json_data'], true);

            foreach ($data['l8_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l8_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l8_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }


             $L8incident = $this->common_model->get_records(AAI_L8_FORM_DATA, '', '', '', array('incident_id' => $incidentId));
            $L8incidentData = $L8incident[0];
            $data['l8_form_id'] = $L8incidentData['l8_form_id'];
            if (isset($L8incidentData) && !empty($L8incidentData)) {
                $editl8Data = $L8incidentData;
                foreach ($data['l8_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l8_form_data'][$key]['value'] = str_replace("\'", "'", $editl8Data[$value['name']]);
                    }
                }
            $data['l8reference_number'] = $editl8Data['l8_reference_number'];
        }else{
               $data['l8reference_number'] = 'L8' . substr($yp_details[0]['yp_initials'], 0, 3) . $dateOfIncident . $refIncidentId;
            }
        }

        /* L8 form end */

        /* L9 form start */
        $l9Form = $allForms[AAI_L9_FORM_ID];
        if (!empty($l9Form)) {
            $data['l9_form_data'] = json_decode($l9Form['form_json_data'], true);
            foreach ($data['l9_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l9_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l9_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }
            
            $L9incident = $this->common_model->get_records(AAI_L9_FORM_DATA, '', '', '', array('incident_id' => $incidentId));
            if (isset($L9incident) && !empty($L9incident)) {
                $editl9Data = $L9incident[0];
                foreach ($data['l9_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l9_form_data'][$key]['value'] = str_replace("\'", "'", $editl9Data[$value['name']]);
                    }
                }
                $data['l9_form_id'] = $editl9Data['l9_form_id'];
                $data['l9reference_number'] = $editl9Data['l9_reference_number'];
            }else{
                $data['l9reference_number'] = 'L9' . substr($yp_details[0]['yp_initials'], 0, 3) . $dateOfIncident . $refIncidentId;
            }

//            if (isset($incidentData['l9_form_data']) && !empty($incidentData['l9_form_data'])) {
//                $editl9Data = json_decode($incidentData['l9_form_data'], true);
//                $editl1Data = json_decode($incidentData['l1_form_data'], true);
//
//                foreach ($editl9Data as $key => $value) {
//                    //pr($key);
//                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
//                        $editl9Data[$value['name']] = $value;
//                    }
//                }
//                foreach ($data['l9_form_data'] as $key => $value) {
//                    if (isset($value['name']) && $value['name'] == $editl9Data[$value['name']]['name']) {
//                        $data['l9_form_data'][$key]['value'] = str_replace("\'", "'", $editl9Data[$value['name']]['value']);
//                    }
//                }
//
////                $data['l9_report_compiler']  = $editl9Data['l9_report_compiler'];
////                $data['l9_date_of_incident'] = $editl9Data['l9_date_of_incident'];
////                $data['l9_time_of_incident'] = $editl9Data['l9_time_of_incident'];
////                $data['l9reference_number'] = $incidentData['l9_reference_number'];
//
//            }else{
//                $data['l9reference_number'] = 'L9' . substr($yp_details[0]['yp_initials'], 0, 3) . $dateOfIncident . $refIncidentId;
//            }
            
        }

        /* L8 form end */
        
        
        $data['YP_details']     = $this->common_model->get_records(YP_DETAILS, array("yp_id,care_home,yp_fname,yp_lname,DATE_FORMAT(date_of_birth, '%d/%m/%Y') as date_of_birth,DATE_FORMAT(date_of_placement, '%d/%m/%Y') as date_of_placement"), '', '', array("yp_id" => $incidentData['yp_id']));
        $data['crnt_view']      = $this->module;
        $data['editMode']       = $formNumber;
        $data['ypId']           = $incidentData['yp_id'];
        $data['isCareIncident'] = $incidentData['is_care_incident'];
        $data['incidentId']     = $incidentId;
        $data['incidentData']   = $incidentData;
        $data['relatedIncident']  = $incidentData['related_incident'];
        $data['footerJs'][0]    = base_url('uploads/custom/js/AAI/AAI.js');
        $data['footerJs'][1]    = base_url('uploads/custom/js/jquery.blockUI.js');
        $data['main_content']   = '/create_ai';
//        pre($incidentData);
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }

    /*

    @Author : Ritesh Rana
    @Desc   : Edit Incident Form
    @Date   : 21/01/2018

     */

    public function view($incidentId, $formNumber = 'main')
    {
        $incidentData = $this->common_model->get_records(AAI_MAIN, '', '', '', array('incident_id' => $incidentId));
        $incidentData = $incidentData[0];
        $allForms = $this->common_model->get_records(AAI_FORM, '', '', '');
        $allForms = array_combine(range(1, count($allForms)), array_values($allForms));

        /* type of incident form start */
        $incidentTypeData = $this->common_model->get_records(AAI_INCIDENT_TYPE_DATA, '', '', '', array('incident_id' => $incidentId));
        $incidentTypeData = $incidentTypeData[0];
        $data = array(
            'is_pi' => $incidentTypeData['is_pi'],
            'is_yp_missing' => $incidentTypeData['is_yp_missing'],
            'is_yp_injured' => $incidentTypeData['is_yp_injured'],
            'is_yp_complaint' => $incidentTypeData['is_yp_complaint'],
            'is_yp_safeguarding' => $incidentTypeData['is_yp_safeguarding'],
            'is_staff_injured' => $incidentTypeData['is_staff_injured'],
            'is_other_injured' => $incidentTypeData['is_other_injured'],
            'is_l1' => $incidentTypeData['is_l1'],
            'incident_type_id' => $incidentTypeData['incident_type_id'],
        );

        /* type of incident form start */

        /* entry form start */
        $entryForm = $allForms[AAI_MAIN_ENTRY_FORM_ID];
        if (!empty($entryForm)) {
            $data['entry_form_data'] = json_decode($entryForm['form_json_data'], true);
        }
        $table                 = AAI_DROPDOWN . ' as dr';
        $where                 = array("d.status" => "1", "dr.status" => "1");
        $fields                = array("dr.dropdown_id", "dr.title", "dr.prefix", "count(d.option_id) as total_options", "GROUP_CONCAT( DISTINCT CONCAT(d.option_id,'|',d.title) ORDER BY d.option_id SEPARATOR ';') as dropdown_options");
        $joinTables            = array(AAI_DROPDOWN_OPTION . ' as d' => 'd.dropdown_id = dr.dropdown_id');
        $groupBy               = "dr.dropdown_id";
        $data['dropdown_data'] = $this->common_model->get_records($table, $fields, $joinTables, 'left', '', '', '', '', '', '', $groupBy, $where);
        foreach ($data['entry_form_data'] as $key => $value) {
            if (isset($value['type']) && $value['type'] == 'select') {
                foreach ($data['dropdown_data'] as $key1 => $value1) {
                    if ($value['description'] == $value1['prefix']) {
                        if ($value1['total_options'] > 0) {
                            $optionsArray = explode(';', $value1['dropdown_options']);
                            foreach ($optionsArray as $op => $v) {
                                $OpArray                                               = explode('|', $v);
                                $finalOptionsArray[$OpArray[0]]                        = $OpArray[1];
                                $data['entry_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                $data['entry_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                            }
                        }
                    }
                }
            }
        }

        //prefix for type data
        foreach ($data['dropdown_data'] as $key1 => $value1) {
            if ($value1['prefix'] == 'pre_outside_agency') {
                if ($value1['total_options'] > 0) {
                    $optionsArray = explode(';', $value1['dropdown_options']);
                    foreach ($optionsArray as $op => $v) {
                        $OpArray                                  = explode('|', $v);
                        $finalOptionsArray[$OpArray[0]]           = $OpArray[1];
                        $data['pre_outside_agency'][$op]['label'] = $OpArray[1];
                        $data['pre_outside_agency'][$op]['value'] = $OpArray[0];
                    }
                }
            }
        }

        //prefix for pre_outside_agency data
        foreach ($data['dropdown_data'] as $key1 => $value1) {
            if ($value1['prefix'] == 'type') {
                if ($value1['total_options'] > 0) {
                    $optionsArray = explode(';', $value1['dropdown_options']);
                    foreach ($optionsArray as $op => $v) {
                        $OpArray                        = explode('|', $v);
                        $finalOptionsArray[$OpArray[0]] = $OpArray[1];
                        $data['type'][$op]['label']     = $OpArray[1];
                        $data['type'][$op]['value']     = $OpArray[0];
                    }
                }
            }
        }

        //prefix for Position_of_yp data
        foreach ($data['dropdown_data'] as $key1 => $value1) {
            if ($value1['prefix'] == 'position_of_yp') {
                if ($value1['total_options'] > 0) {
                    $optionsArray = explode(';', $value1['dropdown_options']);
                    foreach ($optionsArray as $op => $v) {
                        $OpArray                              = explode('|', $v);
                        $finalOptionsArray[$OpArray[0]]       = $OpArray[1];
                        $data['position_of_yp'][$op]['label'] = $OpArray[1];
                        $data['position_of_yp'][$op]['value'] = $OpArray[0];
                    }
                }
            }
        }

        //prefix for Position_of_yp data
        foreach ($data['dropdown_data'] as $key1 => $value1) {
            if ($value1['prefix'] == 'persons_infromed') {
                if ($value1['total_options'] > 0) {
                    $optionsArray = explode(';', $value1['dropdown_options']);
                    foreach ($optionsArray as $op => $v) {
                        $OpArray                              = explode('|', $v);
                        $finalOptionsArray[$OpArray[0]]       = $OpArray[1];
                        $data['persons_infromed'][$op]['label'] = $OpArray[1];
                        $data['persons_infromed'][$op]['value'] = $OpArray[0];
                    }
                }
            }
        }

        
        $mainEntryIncident = $this->common_model->get_records(AAI_ENTRY_FORM_DATA, '', '', '', array('incident_id' => $incidentId));
        if (isset($mainEntryIncident) && !empty($mainEntryIncident)) {
            $editMainEntryData = $mainEntryIncident[0];
            foreach ($data['entry_form_data'] as $key => $value) {
                if (isset($value['name'])) {
                    $data['entry_form_data'][$key]['value'] = str_replace("\'", "'", $editMainEntryData[$value['name']]);
                }
            }
            $data['entry_form_id'] = $editMainEntryData['entry_form_id'];
            $data['reporting_user']        = $editMainEntryData['reporting_user'];
        }
        /* code by Ritesh rana */
        $match                       = array('aam.incident_id' => $incidentId, 'aam.process_id' => 'mainform');
        $fields_report               = array("aam.*");
        $prev_incidentData_main_form = $this->common_model->get_records(ARCHIVE_AAI_MAIN . ' as aam', $fields_report, '', '', $match, '', '1', '', 'archive_incident_id', 'desc');

        /* prev incidentData data start */
        $prev_incidentData_main_form = $prev_incidentData_main_form[0];
        if (!empty($prev_incidentData_main_form['entry_form_data'])) {
            $prevmainData = json_decode($prev_incidentData_main_form['entry_form_data'], true);
            foreach ($prevmainData as $key => $prevalue) {
                if (isset($prevalue['type']) && $prevalue['type'] != 'button' && $prevalue['type'] != 'header') {
                    $preveditMainData[$prevalue['name']] = $prevalue;
                }
            }

            $data['pre_reporting_user'] = $prevmainData['reporting_user'];
        }
        $data['prevedit_entry_form_data'] = $preveditMainData;

        /* prev incidentData data end */
        $emailMatch = '(email LIKE "%_@__%.__%")';
        $nfcUsers   = $this->common_model->get_records(LOGIN, array('login_id as user_id', 'firstname as first_name', 'lastname as last_name', 'email'), '', '', '', '', '', '', '', '', '', $emailMatch);

        function appendNFCType2($n)
        {
            $n['user_type']     = 'N';
            $n['job_title']     = '';
            $n['work_location'] = '';
            return $n;
        }

        $nfcUsers    = array_map("appendNFCType2", $nfcUsers);
        $bambooUsers = $this->common_model->get_records(BAMBOOHR_USERS, array('user_id', 'first_name', 'last_name', 'email', 'job_title', 'work_location'), '', '', '', '', '', '', '', '', '', $emailMatch);

        function appendBambooType2($n)
        {
            $n['user_type'] = 'B';
            return $n;
        }
        $bambooUsers            = array_map("appendBambooType2", $bambooUsers);
        $data['bambooNfcUsers'] = array_merge($bambooUsers, $nfcUsers);

        $data['loggedInUser']   = $this->session->userdata['LOGGED_IN'];
        $table                  = AAI_MAIN . ' as a';
        $join_tables            = array(YP_DETAILS . ' as yp' => 'a.yp_id=yp.yp_id', CARE_HOME . ' as c' => 'c.care_home_id=yp.care_home');
        $dateQuery              = 'date(a.created_date) >= CURDATE() - INTERVAL 14 DAY ';
        $data['YPIncidentData'] = $this->common_model->get_records($table, '', $join_tables, '', array('a.yp_id' => $incidentData['yp_id'], 'a.incident_id !=' => $incidentId), '', '', '', '', '', '', $dateQuery);
        /* entry form end */

        
        /* L1 start */
        $l1Form = $allForms[AAI_L1_FORM_ID];
        if (!empty($l1Form)) {
            $data['l1_form_data'] = json_decode($l1Form['form_json_data'], true);
            foreach ($data['l1_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l1_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l1_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }

            $L1incident = $this->common_model->get_records(AAI_L1_FORM_DATA, '', '', '', array('incident_id' => $incidentId));
            $L1incidentData = $L1incident[0];
            if (isset($L1incidentData) && !empty($L1incidentData)) {
                $editl1Data = $L1incidentData;
                $data['l1_data'] = $editl1Data;
                foreach ($data['l1_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l1_form_data'][$key]['value'] = str_replace("\'", "'", $editl1Data[$value['name']]);
                    }
                }
                $data['l1_total_duration']  = $editl1Data['l1_total_duration'];
                $data['l1_form_id']  = $editl1Data['l1_form_id'];
                $data['l1reference_number'] = $editl1Data['l1_reference_number'];
            }else{
                $data['l1reference_number'] = '';
            }

            /* code by Ritesh Rana */
            /* L1 PREV DATA */
            $match                     = array('aam.incident_id' => $incidentId, 'aam.form_type' => 'L1');
            $fields_report             = array("aam.*");
            $prev_incidentData_l1_data = $this->common_model->get_records(AAI_ARCHIVE . ' as aam', $fields_report, '', '', $match, '', '1', '', 'archive_id', 'desc');

            $prev_incidentData_l1 = $prev_incidentData_l1_data[0];
            if (!empty($prev_incidentData_l1['form_json_data'])) {
                $prevl1Data = json_decode($prev_incidentData_l1['form_json_data'], true);
                $preveditl1Data = $prevl1Data;
                $data['l1_prev_report_compiler'] = $prevl1Data['report_compiler'];
                $data['l1_prev_total_duration']  = $prevl1Data['l1_total_duration'];
            }
            $data['preveditl1Data'] = $preveditl1Data;
        }
        /* code end ritesh Rana */
        /* L1 end */

        /* code by ritesh rana */
        /* L2 start */
        $l2Form = $allForms[AAI_L2NL3_FORM_ID];
        if (!empty($l2Form)) {
            $data['l2_form_data'] = json_decode($l2Form['form_json_data'], true);
            foreach ($data['l2_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l2_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l2_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }

            $L2incident = $this->common_model->get_records(AAI_L2_L3_FORM_DATA, '', '', '', array('incident_id' => $incidentId));
            $L2incidentData = $L2incident[0];

            $data['l2_form_id'] = $L2incidentData['l2_l3_form_id'];
            if (isset($L2incidentData) && !empty($L2incidentData)) {
                $editl2Data = $L2incidentData;
                $data['l2_data'] = $editl2Data;
                foreach ($data['l2_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l2_form_data'][$key]['value'] = str_replace("\'", "'", $editl2Data[$value['name']]);
                    }
                }
                
                $data['l2_total_duration']  = $editl2Data['l2_total_duration'];

                
               /* start code by ritesh Rana */
        /* start Sequence of events */
            $sortfield = 'time';
            $sortby    = 'asc';
            $table = AAI_L2_L3_SEQUENCE_EVENT . ' as sq';
            $where = array("sq.incident_id" => $incidentId);
            $fields = array("sq.*");
            $l2seqresult = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where);
            $data['l2sequence_events'] = $l2seqresult;


             $sortfield = 'time';
            $sortby    = 'asc';
            $table = AAI_L2_L3_SEQUENCE_EVENT . ' as sq';
            $sqnm='S'.$squ_num;
            $where = array("sq.incident_id" => $incidentId);
            $fields = array("sq.*,wi.l2Who_was_involved_in_incident");
             $joinTables            = array(AAI_L2_WHO_WAS_INVOLVED . ' as wi' => 'wi.l2_l3_sequence_event_id = sq.l2_l3_sequence_event_id');
            $l2seqresult2 = $this->common_model->get_records($table, $fields, $joinTables, 'left', '', '', '', '', $sortfield, $sortby, '', $where);

            $seqwise_l2_l3_sequence_events=array();
            
            if(count($l2seqresult2)>0)
            {
                    foreach($l2seqresult2 as $sqdata)
                        {
                            $seqwise_l2_l3_sequence_events[$sqdata['l2_l3_sequence_event_id']][]=$sqdata['l2Who_was_involved_in_incident'];
                        }

            }
            $data['seqwise_l2_l3_sequence_events'] = $seqwise_l2_l3_sequence_events;
           

        /* end Sequence of events */
        /* start Medical Observations */
            $table = AAI_L2_L3_MEDICAL_OBSERVATION . ' as mo';
            $where = array("mo.incident_id" => $incidentId);
            $fields = array("mo.*");
            $l2seqresult_mo = $this->common_model->get_records($table, $fields, '', '', $where);
            $data['l2medical_observations'] = $l2seqresult_mo;
        /* End Medical Observations */
        /* end code by ritesh Rana */

                /* L2 PREV DATA */
                $match                = array('aam.incident_id' => $incidentId, 'aam.process_id' => 'l2');
                $fields_report        = array("aam.*");
                $prev_incidentData_l2 = $this->common_model->get_records(ARCHIVE_AAI_MAIN . ' as aam', $fields_report, '', '', $match, '', '1', '', 'archive_incident_id', 'desc');
                $prev_incidentData_l2 = $prev_incidentData_l2[0];

                if (!empty($prev_incidentData_l2['l2_l3_form_data'])) {
                    $prevl2Data = json_decode($prev_incidentData_l2['l2_l3_form_data'], true);
                    foreach ($prevl2Data as $key => $prevalue) {
                        if (isset($prevalue['type']) && $prevalue['type'] != 'button' && $prevalue['type'] != 'header') {
                            $preveditl2Data[$prevalue['name']] = $prevalue;
                        }
                    }
                    $data['l2_prev_report_compiler'] = $prevl2Data['report_compiler'];
                    $data['l2_prev_total_duration']  = $prevl2Data['l2_total_duration'];
                }
                $data['preveditl2Data'] = $preveditl2Data;

                /* prev l2 sequence data start */
                $l2sequence_prev_data                      = array();
                $l2sequence_prev_data['l2sequence_number'] = $prevl2Data['l2sequence_number'];
                $l2sequence_prev_data['l2position']        = $prevl2Data['l2position'];
                $l2sequence_prev_data['l2type']            = $prevl2Data['l2type'];
                $l2sequence_prev_data['l2comments']        = $prevl2Data['l2comments'];
                $l2sequence_prev_data['l2time_sequence']   = $prevl2Data['l2time_sequence'];

                $l2seqresult_prev = array();
                $count            = count($prevl2Data['l2sequence_number']);
                foreach ($l2sequence_prev_data as $kk => $vv) {
                    for ($i = 0; $i < $count; $i++) {

                        if($kk == 'l2time_sequence'){
                            $l2seqresult_prev[$i][$kk] = $l2sequence_prev_data[$kk][$i];    
                            $l2seqresult_prev[$i]['l2time_sequence_str']   = strtotime($l2sequence_prev_data[$kk][$i]);
                         }else{
                            $l2seqresult_prev[$i][$kk] = $l2sequence_prev_data[$kk][$i];
                         }   
                        $doc = $i + 1;
                        $l2seqresult_prev[$i]['l2Who_was_involved_in_incident' . $doc] = $prevl2Data['l2Who_was_involved_in_incident' . $doc];
                    }
                }
                $data['l2seqresult_prev'] = $l2seqresult_prev;
                $data['l2seqresult_prev'] = array_sort($l2seqresult_prev,'l2time_sequence_str',SORT_ASC);


                /* prev l2 sequence data end */

                /* Prev start Medical Observations */
                $l2prev_sequence_data_mo                                         = array();
                $l2prev_sequence_data_mo['l2medical_observations_after_minutes'] = $prevl2Data['l2medical_observations_after_minutes'];
                $l2prev_sequence_data_mo['l2time_medical']                       = $prevl2Data['l2time_medical'];
                $l2prev_sequence_data_mo['l2comments_mo']                        = $prevl2Data['l2comments_mo'];

                $l2seqresult_prev_mo = array();
                $count               = count($prevl2Data['l2medical_observations_after_minutes']);
                foreach ($l2prev_sequence_data_mo as $kk => $vv) {
                    for ($i = 0; $i < $count; $i++) {
                        $l2seqresult_prev_mo[$i][$kk]                                   = $l2prev_sequence_data_mo[$kk][$i];
                        $doc                                                            = $i + 1;
                        $l2seqresult_prev_mo[$i]['l2_medical_observation_taken' . $doc] = $prevl2Data['l2_medical_observation_taken' . $doc];
                        $l2seqresult_prev_mo[$i]['l2Observation_taken_by' . $doc]       = $prevl2Data['l2Observation_taken_by' . $doc];
                    }
                }

                $data['l2_prev_medical_observations'] = $l2seqresult_prev_mo;
                $data['l2reference_number'] = $editl2Data['l2_l3_reference_number'];
                /* Prev end Medical Observations */
                /* end code by ritesh Rana */
            }else{
                 $data['l2reference_number'] = '';
            }
        }
        /* L2 end */
        
        if ($data['is_l1'] == 1) {
            $data['l1date_of_incident'] = $editl1Data['l1_start_date'];
            $data['l1time_of_incident'] = $editl1Data['l1_start_time'];
        }else if($data['is_pi'] == 1){
            $data['l1date_of_incident'] = $editl2Data['l2_start_date'];
            $data['l1time_of_incident'] = $editl2Data['l2_start_time'];
        }

        /* L4 form start */
        $l4Form = $allForms[AAI_L4_FORM_ID];
        $L4incident = $this->common_model->get_records(AAI_L4_FORM_DATA, '', '', '', array('incident_id' => $incidentId));
        if (!empty($l4Form) && isset($L4incident) && !empty($L4incident)) {
            $data['l4_form_data'] = json_decode($l4Form['form_json_data'], true);
            foreach ($data['l4_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l4_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l4_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }
            if (isset($L4incident) && !empty($L4incident)) {
                $editl4Data = $L4incident[0];
                $data['l4_data'] = $editl4Data;
                foreach ($data['l4_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l4_form_data'][$key]['value'] = str_replace("\'", "'", $editl4Data[$value['name']]);
                    }
                }
                $data['l4calculate_notification_worker'] = $editl4Data['calculate_notification_worker'];

                $data['l4calculate_notification_missing'] = $editl4Data['calculate_notification_missing'];
                $data['l4reference_number'] = $editl4Data['l4_reference_number'];
                $data['l4_total_duration'] = $editl4Data['l4_total_duration'];
                $data['l4_report_compiler']              = $editl4Data['l4_report_compiler'];
                
                $table = AAI_L4_PERSON_INFORMED_MISSING . ' as mo';
                $where = array("mo.incident_id" => $incidentId);
                $fields = array("mo.*");
                $data['l4missing_yp'] = $this->common_model->get_records($table, $fields, '', '', $where);
                
                $table = AAI_L4_PERSON_INFORMED_RETURN . ' as mo';
                $where = array("mo.incident_id" => $incidentId);
                $fields = array("mo.*");
                $data['l4return_data'] = $this->common_model->get_records($table, $fields, '', '', $where);
                
                $table = AAI_L4_SEQUENCE_EVENT . ' as mo';
                $where = array("mo.incident_id" => $incidentId);
                $fields = array("mo.*");
                $data['l4sequence_data'] = $this->common_model->get_records($table, $fields, '', '', $where,'','','','date,time','ASC');
                
            }
        }

        /* L4 form end */
        /* L5 form start */

         $l5Form = $allForms[AAI_L5_FORM_ID];
        if (!empty($l5Form)) {
            $data['l5_form_data'] = json_decode($l5Form['form_json_data'], true);

            foreach ($data['l5_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l5_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l5_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }


             $L5incident = $this->common_model->get_records(AAI_L5_FORM_DATA, '', '', '', array('incident_id' => $incidentId));
            $L5incidentData = $L5incident[0];

            $data['l5_form_id'] = $L5incidentData['l5_form_id'];
            if (isset($L5incidentData) && !empty($L5incidentData)) {
                $editl5Data = $L5incidentData;
                foreach ($data['l5_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l5_form_data'][$key]['value'] = str_replace("\'", "'", $editl5Data[$value['name']]);
                    }
                }

                $data['l5reference_number'] = $L5incidentData['l5_reference_number'];
                $data['l5_body_map']        = $editl5Data;

            }else{
                $data['l5reference_number'] = 'L5' . substr($yp_details[0]['yp_initials'], 0, 3) . $dateOfIncident . $refIncidentId;
            }


            /* Start L5 PREV DATA */
            $match                     = array('aam.incident_id' => $incidentId, 'aam.form_type' => 'L5');
            $fields_report             = array("aam.*");
            $prev_incidentData_l5_data = $this->common_model->get_records(AAI_ARCHIVE . ' as aam', $fields_report, '', '', $match, '', '1', '', 'archive_id', 'desc');
            
            $prev_incidentData_l5 = $prev_incidentData_l5_data[0];
            if (!empty($prev_incidentData_l5['form_json_data'])) {
                $prevl5Data = json_decode($prev_incidentData_l5['form_json_data'], true);
                $preveditl5Data = $prevl5Data;
            }
            $data['preveditl5Data'] = $preveditl5Data;
            /* End L5 PREV DATA */
        }


        /* L5 end */

        /* L6 start */
        $l6Form = $allForms[AAI_L6_FORM_ID];
        if (!empty($l6Form)) {
            $data['l6_form_data'] = json_decode($l6Form['form_json_data'], true);
            foreach ($data['l6_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l6_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l6_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }


             $L6incident = $this->common_model->get_records(AAI_L6_FORM_DATA, '', '', '', array('incident_id' => $incidentId));
            $L6incidentData = $L6incident[0];
            $data['l6_form_id'] = $L6incidentData['l6_form_id'];
            if (isset($L6incidentData) && !empty($L6incidentData)) {
                $editl6Data = $L6incidentData;
                $data['l6_data'] = $editl6Data;
                //pr($editl6Data);exit;
                foreach ($data['l6_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l6_form_data'][$key]['value'] = str_replace("\'", "'", $editl6Data[$value['name']]);
                    }
                }
                $data['l6reference_number'] = $editl6Data['l6_reference_number'];
            }else{
                 $data['l6reference_number'] = 'L6' . substr($yp_details[0]['yp_initials'], 0, 3) . $dateOfIncident . $refIncidentId;
            }
        /* start code by ritesh Rana */
        /* start Sequence of events */
            $sortfield = 'date,time';
            $sortby    = 'asc';
            $table = AAI_L6_SEQUENCE_EVENT . ' as sq';
            $where = array("sq.incident_id" => $incidentId);
            $fields = array("sq.*");
            $l6seqresult = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where);
            $data['l6sequence_data'] = $l6seqresult;
        /* end Sequence of events */
    
        /* L6 end */

                /* L6 PREV DATA */
                
                /* end */
            }
        
        /* L6 end */
         /* start code by ritesh Rana */
        /* L7 start */
        $l7Form = $allForms[AAI_L7_FORM_ID];
        if (!empty($l7Form)) {
            $data['l7_form_data'] = json_decode($l7Form['form_json_data'], true);
            foreach ($data['l7_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l7_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l7_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }

       
            $L7incident = $this->common_model->get_records(AAI_L7_FORM_DATA, '', '', '', array('incident_id' => $incidentId));
            $L7incidentData = $L7incident[0];

            $data['l7_form_id'] = $L7incidentData['l7_form_id'];
            if (isset($L7incidentData) && !empty($L7incidentData)) {
                $editl7Data = $L7incidentData;
                foreach ($data['l7_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l7_form_data'][$key]['value'] = str_replace("\'", "'", $editl7Data[$value['name']]);
                    }
                }
            $data['l7reference_number'] = $editl7Data['l7_reference_number'];

             /*foreach ($editl7Data as $key => $value) {
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editl7Data[$value['name']] = $value;
                        if($value['name'] == 'allowed_access'){
                                $allowed_access[] = $str = $value;                             
                        }
                    }
                }

                $allowed_access = $allowed_access[0]['value'];
                foreach ($allowed_access as $value_access) {
                    $access_data = substr($value_access,0,2);
                    if($access_data == 'N_'){
                       $access_data_val[] = substr($value_access,2);     
                    } 
                }

                $data['access_data_val'] = $access_data_val;*/


        /* start Sequence of events */
            $sortfield = 'l7date_safeguarding,l7time_safeguard';
            $sortby    = 'asc';
            $table = AAI_L7_SAFEGUARDING_UPDATES . ' as sq';
            $where = array("sq.incident_id" => $incidentId);
            $fields = array("sq.*");
            $l7seqresult = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where);
            $data['l7sequence_data'] = $l7seqresult;
        /* end Sequence of events */
        }else{
            $data['l7reference_number'] = 'L7' . substr($yp_details[0]['yp_initials'], 0, 3) . $dateOfIncident . $refIncidentId;
        }
        /* prev l7 */
            /*
                $match                = array('aam.incident_id' => $incidentId, 'aam.process_id' => 'l7');
                $fields_report        = array("aam.*");
                $prev_incidentData_l7 = $this->common_model->get_records(ARCHIVE_AAI_MAIN . ' as aam', $fields_report, '', '', $match, '', '1', '', 'archive_incident_id', 'desc');

                $prev_incidentData_l7 = $prev_incidentData_l7[0];
                if (!empty($prev_incidentData_l7['l7_form_data'])) {
                    $prevl7Data = json_decode($prev_incidentData_l7['l7_form_data'], true);
                    foreach ($prevl7Data as $key => $prevalue) {
                        if (isset($prevalue['type']) && $prevalue['type'] != 'button' && $prevalue['type'] != 'header') {
                            $preveditl7Data[$prevalue['name']] = $prevalue;
                        }
                    }
                }
                $data['preveditl7Data'] = $preveditl7Data;

                // pr($data['preveditl7Data']);exit;

                $l7sequence_prev_data                           = array();
                $l7sequence_prev_data['l7sequence_number']      = $prevl7Data['l7sequence_number'];
                $l7sequence_prev_data['l7update_by']            = $prevl7Data['l7update_by'];
                $l7sequence_prev_data['l7daily_action_taken']   = $prevl7Data['l7daily_action_taken'];
                $l7sequence_prev_data['l7daily_action_outcome'] = $prevl7Data['l7daily_action_outcome'];
                $l7sequence_prev_data['l7date_safeguarding']    = $prevl7Data['l7date_safeguarding'];
                $l7sequence_prev_data['l7time_safeguard']       = $prevl7Data['l7time_safeguard'];

                $l7seqresult_prev = array();
                $count            = count($prevl7Data['l7sequence_number']);
                foreach ($l7sequence_prev_data as $kk => $vv) {
                    for ($i = 0; $i < $count; $i++) {

                    if($kk == 'l7time_safeguard' || $kk =='l7date_safeguarding'){
                            $l7seqresult_prev[$i][$kk] = $l7sequence_prev_data[$kk][$i];
                            $date_date_event = dateformat($l7sequence_prev_data['l7date_safeguarding'][$i]);

                            $l7seqresult_prev[$i]['l7seq_time_event_str']   = strtotime($date_date_event .' '. $l7sequence_prev_data['l7time_safeguard'][$i]);
                    }else{
                            $l7seqresult_prev[$i][$kk] = $l7sequence_prev_data[$kk][$i];
                    }
                     $doc = $i + 1;
                        $l7seqresult_prev[$i]['l7supporting_documents' . $doc] = $prevl7Data['l7supporting_documents' . $doc];
                    }
                }
                $data['l7seqresult_prev']   = $l7seqresult_prev;
                $data['l7seqresult_prev'] = array_sort($l7seqresult_prev,'l7seq_time_event_str',SORT_ASC,false);

                $data['l7_report_compiler'] = $editl7Data['report_compiler'];
                $data['l7reference_number'] = $incidentData['l7_reference_number'];
*/

        }
        /* L7 end */
         /* end code by ritesh Rana */

        
        
		/* nikunj ghelani L8 form start */
        $l8Form = $allForms[AAI_L8_FORM_ID];
        if (!empty($l8Form)) {
            $data['l8_form_data'] = json_decode($l8Form['form_json_data'], TRUE);

            foreach ($data['l8_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]] = $OpArray[1];
                                    $data['l8_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l8_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }
			  $L8incident = $this->common_model->get_records(AAI_L8_FORM_DATA, '', '', '', array('incident_id' => $incidentId));
            $L8incidentData = $L8incident[0];

            $data['l8_form_id'] = $L8incidentData['l8_form_id'];
            if (isset($L8incidentData) && !empty($L8incidentData)) {
                $editl8Data = $L8incidentData;
                foreach ($data['l8_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l8_form_data'][$key]['value'] = str_replace("\'", "'", $editl8Data[$value['name']]);
                    }
                }
            $data['l8reference_number'] = $editl8Data['l8_reference_number'];
        }
    }

        /* L8 form end */
		
		/* nikunj ghelani L9 form start */
        $l9Form = $allForms[AAI_L9_FORM_ID];
        $L9incident = $this->common_model->get_records(AAI_L9_FORM_DATA, '', '', '', array('incident_id' => $incidentId));
        if (!empty($l9Form) && isset($L9incident) && !empty($L9incident)) {
            $data['l9_form_data'] = json_decode($l9Form['form_json_data'], TRUE);

            foreach ($data['l9_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]] = $OpArray[1];
                                    $data['l9_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l9_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }
                $editl9Data = $L9incident[0];
                foreach ($data['l9_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l9_form_data'][$key]['value'] = str_replace("\'", "'", $editl9Data[$value['name']]);
                    }
                }
                $data['l9reference_number'] = $editl9Data['l9_reference_number'];
            
        }

        /* L9 form end */

        $reviewForm = $allForms[REVIEW];
        if (!empty($reviewForm)) {
            $data['review_form_data'] = json_decode($reviewForm['form_json_data'], true);
            foreach ($data['review_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                                = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                         = $OpArray[1];
                                    $data['review_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['review_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }

            if (isset($incidentData['review_form_data']) && !empty($incidentData['review_form_data'])) {
                $editReviewData = json_decode($incidentData['review_form_data'], true);
                foreach ($editReviewData as $key => $value) {
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editReviewData[$value['name']] = $value;
                    }
                }

                foreach ($data['review_form_data'] as $key => $value) {
                    if (isset($value['name']) && $value['name'] == $editReviewData[$value['name']]['name']) {
                        $data['review_form_data'][$key]['value'] = str_replace("\'", "'", $editReviewData[$value['name']]['value']);
                    }
                }

            }
        }
        /* Review end */

        /* Manager review start */

        $ManagersReviewForm = $allForms[MANAGER_REVIEW];
        if (!empty($ManagersReviewForm)) {
            $data['manager_review_form_data'] = json_decode($ManagersReviewForm['form_json_data'], true);
            foreach ($data['manager_review_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                                        = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                                 = $OpArray[1];
                                    $data['manager_review_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['manager_review_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }

            if (isset($incidentData['manager_review_form_data']) && !empty($incidentData['manager_review_form_data'])) {
                $editManagerReviewData = json_decode($incidentData['manager_review_form_data'], true);
                foreach ($editManagerReviewData as $key => $value) {
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editManagerReviewData[$value['name']] = $value;
                    }
                }

                foreach ($data['manager_review_form_data'] as $key => $value) {
                    if (isset($value['name']) && $value['name'] == $editManagerReviewData[$value['name']]['name']) {
                        $data['manager_review_form_data'][$key]['value'] = str_replace("\'", "'", $editManagerReviewData[$value['name']]['value']);
                    }
                }
            }
        }
        /* Manager Review end */

        /*sign off functionality start */
        //check data exist or not
        $login_user_id                  = $this->session->userdata['LOGGED_IN']['ID'];
        $table                          = AAI_SIGNOFF;
        $where                          = array("yp_id" => $incidentData['yp_id'], "incident_id" => $incidentId, "created_by" => $login_user_id, "is_delete" => "0");
        $fields                         = array('count(*) as count');
        $check_aai_signoff_data         = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
        $data['check_aai_signoff_data'] = $check_aai_signoff_data[0]['count'];
        /*sign off functionality end*/

        //get signoff data
        $table                = AAI_SIGNOFF . ' as aai';
        $where                = array("l.is_delete" => "0", "aai.yp_id" => $incidentData['yp_id'], "aai.incident_id" => $incidentId, "aai.is_delete" => "0");
        $fields               = array("aai.created_by,aai.created_date,aai.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables          = array(LOGIN . ' as l' => 'l.login_id=aai.created_by');
        $group_by             = array('aai.created_by');
        $data['signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);
        
        //get external approve
        $table                               = AAI_SIGNOFF_APPROVAL;
        $fields                              = array('incident_id,signoff_approval_id');
        $where                               = array('incident_id' => $incidentId);
        $data['check_external_signoff_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, array("yp_id,care_home,yp_fname,yp_lname,DATE_FORMAT(date_of_birth, '%d/%m/%Y') as date_of_birth,DATE_FORMAT(date_of_placement, '%d/%m/%Y') as date_of_placement"), '', '', array("yp_id" => $incidentData['yp_id']));

        $data['crnt_view']      = $this->module;
        $data['editMode']       = $formNumber;
        $data['ypId']           = $incidentData['yp_id'];
        $data['isCareIncident'] = $incidentData['is_care_incident'];
        $data['incidentId']     = $incidentId;
        $data['incidentData']   = $incidentData;
        $data['relatedIncident']  = $incidentData['related_incident'];
        $data['footerJs'][0]    = base_url('uploads/custom/js/AAI/AAI_view.js');
        $data['main_content']   = '/view_ai';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }

/*
@Author : Niral Patel
@Desc   : view mc data
@Input  :
@Output :
@Date   : 19/07/2017
 */
    public function external_approval_list($ypid, $incident_id)
    {
        if (is_numeric($ypid) && is_numeric($incident_id)) {
            $match              = "yp_id = " . $ypid;
            $fields             = array("*");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/' . $ypid);
            }

            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield  = $this->input->post('sortfield');
            $sortby     = $this->input->post('sortby');
            $perpage    = 10;
            $allflag    = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('aai_approval_session_data');
            }

            $searchsort_session = $this->session->userdata('aai_approval_session_data');
            //Sorting
            if (!empty($sortfield) && !empty($sortby)) {
                $data['sortfield'] = $sortfield;
                $data['sortby']    = $sortby;
            } else {
                if (!empty($searchsort_session['sortfield'])) {
                    $data['sortfield'] = $searchsort_session['sortfield'];
                    $data['sortby']    = $searchsort_session['sortby'];
                    $sortfield         = $searchsort_session['sortfield'];
                    $sortby            = $searchsort_session['sortby'];
                } else {
                    $sortfield         = 'signoff_approval_id';
                    $sortby            = 'desc';
                    $data['sortfield'] = $sortfield;
                    $data['sortby']    = $sortby;
                }
            }
            //Search text
            if (!empty($searchtext)) {
                $data['searchtext'] = $searchtext;
            } else {
                if (empty($allflag) && !empty($searchsort_session['searchtext'])) {
                    $data['searchtext'] = $searchsort_session['searchtext'];
                    $searchtext         = $data['searchtext'];
                } else {
                    $data['searchtext'] = '';
                }
            }

            if (!empty($perpage) && $perpage != 'null') {
                $data['perpage']    = $perpage;
                $config['per_page'] = $perpage;
            } else {
                if (!empty($searchsort_session['perpage'])) {
                    $data['perpage']    = trim($searchsort_session['perpage']);
                    $config['per_page'] = trim($searchsort_session['perpage']);
                } else {
                    $config['per_page'] = '10';
                    $data['perpage']    = '10';
                }
            }
            //pagination configuration
            $config['first_link'] = 'First';
            $config['base_url']   = base_url() . $this->viewname . '/external_approval_list/' . $ypid . '/' . $incident_id;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment           = 0;
            } else {
                $config['uri_segment'] = 5;
                $uri_segment           = $this->uri->segment(5);
            }
            //Query

            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $table         = AAI_SIGNOFF_APPROVAL . ' as aisp';
            $where         = array("aisp.yp_id" => $ypid, "aisp.incident_id" => $incident_id);
            $fields        = array("aisp.*,CONCAT(`firstname`,' ', `lastname`) as create_name ,CONCAT(`fname`,' ', `lname`) as user_name,ch.care_home_name");
            $join_tables   = array(LOGIN . ' as l' => 'l.login_id= aisp.created_by', CARE_HOME . ' as ch' => 'ch.care_home_id = aisp.care_home_id');
            if (!empty($searchtext)) {

            } else {
                $data['information']  = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
            }

            $data['ypid']        = $ypid;
            $data['incident_id'] = $incident_id;

            $this->ajax_pagination->initialize($config);
            $data['pagination']  = $this->ajax_pagination->create_links();
            $data['uri_segment'] = $uri_segment;
            $sortsearchpage_data = array(
                'sortfield'   => $data['sortfield'],
                'sortby'      => $data['sortby'],
                'searchtext'  => $data['searchtext'],
                'perpage'     => trim($data['perpage']),
                'uri_segment' => $uri_segment,
                'total_rows'  => $config['total_rows']);

            $this->session->set_userdata('aai_approval_session_data', $sortsearchpage_data);
            setActiveSession('aai_approval_session_data'); // set current Session active
            $data['header'] = array('menu_module' => 'AAI');
            //get YP information

            //get communication form

            $data['crnt_view']   = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/AAI/AAI.js');
            $data['footerJs'][1]    = base_url('uploads/custom/js/jquery.blockUI.js');
            $data['header']      = array('menu_module' => 'YoungPerson');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/approval_ajaxlist', $data);
            } else {
                $data['main_content'] = '/approval_list';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }

    /*
    @Author : Dhara Bhalala
    @Desc   : Bamboohr Temperory Function
    @Date   : 17/12/2018
     */

    public function bamboohrDetails()
    {
        $apiKey = '4cf2fa4db6a143e49617b6c8c35d2a2f47354405';
        $url    = 'https://api.bamboohr.com/api/gateway.php/nfccmetricsandbox/v1/employees/directory';
        $ch     = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERPWD, "$apiKey" . ":" . "x");
        $result = curl_exec($ch);
        curl_close($ch);
        $obj = new SimpleXMLElement($result);
        $arr = json_decode(json_encode($obj), true);

        $empDataArray = array();
        foreach ($arr['employees']['employee'] as $key => $employees_data) {

            $empDataArray[$key]           = array_combine($arr['fieldset']['field'], $employees_data['field']);
            $empDataArray[$key]['emp_id'] = $employees_data['@attributes']['id'];
        }
        pr($empDataArray);
        exit;
    }

    /*
    @Author : Nikunj Ghelani
    @Desc   : send notification mail to social worker
    @Date   : 11/1/2019
     */

    public function send_notification_social_worker()
    {
        $l4_form_id = $this->input->post('l4_form_id');
        $incidentId = $this->input->post('incident_id');

        $table                 = AAI_EMAIL_TEMPLATE . ' as aae';
        $match                 = "aae.status !=2 AND aae.subject='l4notificationtosocialworker'";
        $fields                = array("aae.*,ar.*");
        $join_tables           = array(AAI_RECEIPT . ' as ar' => 'ar.receipt_id=aae.recipient_type');
        $data['template_type'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $match, '', '', '', '', '');

        if (sizeof($data['template_type'] > 1)) {

            $tomail = explode(',', $data['template_type'][0]['receipt_email']);
            
            foreach ($tomail as $mailto) {
                
                $toEmailId = $mailto;
                $email     = md5($toEmailId);
                $find      = array('{NAME}', '{LINK}');
                $replace   = array(
                    'NAME' => 'nikunj',
                    'LINK' => 'ghelani');
                $emailSubject = $data['template_type'][0]['subject'];
                $emailBody    = $data['template_type'][0]['body'];

                $finalEmailBody = str_replace($find, $replace, $emailBody);

                $this->common_model->sendEmail($toEmailId, $emailSubject, $emailBody, FROM_EMAIL_ID);
            }
        } else {

            $toEmailId = $data['template_type'][0]['receipt_email'];
            $email     = md5($toEmailId);
            $find      = array('{NAME}', '{LINK}');
            $replace   = array(
                'NAME' => 'nikunj',
                'LINK' => 'ghelani');
            $emailSubject = $data['template_type'][0]['subject'];
            $emailBody    = $data['template_type'][0]['body'];

            $finalEmailBody = str_replace($find, $replace, $emailBody);

            $this->common_model->sendEmail($toEmailId, $emailSubject, $emailBody, FROM_EMAIL_ID);
        }
        $l4FormData['calculate_notification_worker'] = configDateTimeFormatAi('');
        if (!empty($l4_form_id) && $l4_form_id !== '') {
            $this->common_model->update(AAI_L4_FORM_DATA, $l4FormData, array('l4_form_id' => $l4_form_id));
        } else {
            $l4FormData['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
            $l4FormData['created_date'] = datetimeformat();
            $l4FormData['incident_id'] = $incidentId;
            $l4_form_id = $this->common_model->insert(AAI_L4_FORM_DATA, $l4FormData);
        }
        $returnData = array(
            'l4_form_id' => $l4_form_id,
            'notification_date' => $l4FormData['calculate_notification_worker'],
            'status' => 'success',
        );
        echo json_encode($returnData);
        exit;
    }

    /*
    @Author : Nikunj Ghelani
    @Desc   : send notification mail to missing Team
    @Date   : 11/1/2019
     */

    public function send_notification_missing_team()
    {
        $l4_form_id = $this->input->post('l4_form_id');
        $incidentId = $this->input->post('incident_id');

        $table                 = AAI_EMAIL_TEMPLATE . ' as aae';
        $match                 = "aae.status !=2 AND aae.subject='Send Missing YP Alert to missing team'";
        $fields                = array("aae.*,ar.*");
        $join_tables           = array(AAI_RECEIPT . ' as ar' => 'ar.receipt_id=aae.recipient_type');
        $data['template_type'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $match, '', '', '', '', '');
        
        if (sizeof($data['template_type'] > 1)) {

            $tomail = explode(',', $data['template_type'][0]['receipt_email']);
            //pr($tomail);
            foreach ($tomail as $mailto) {
                //pr($mailto);
                $toEmailId = $mailto;
                $email     = md5($toEmailId);
                $find      = array('{NAME}', '{LINK}');
                $replace   = array(
                    'NAME' => 'nikunj',
                    'LINK' => 'ghelani');
                $emailSubject = $data['template_type'][0]['subject'];
                $emailBody    = $data['template_type'][0]['body'];

                $finalEmailBody = str_replace($find, $replace, $emailBody);

                $this->common_model->sendEmail($toEmailId, $emailSubject, $emailBody, FROM_EMAIL_ID);
            }
        } else {

            $toEmailId = $data['template_type'][0]['receipt_email'];
            $email     = md5($toEmailId);
            $find      = array('{NAME}', '{LINK}');
            $replace   = array(
                'NAME' => 'nikunj',
                'LINK' => 'ghelani');
            $emailSubject = $data['template_type'][0]['subject'];
            $emailBody    = $data['template_type'][0]['body'];

            $finalEmailBody = str_replace($find, $replace, $emailBody);

            $this->common_model->sendEmail($toEmailId, $emailSubject, $emailBody, FROM_EMAIL_ID);
        }
        $l4FormData['calculate_notification_missing'] = configDateTimeFormatAi('');
        if (!empty($l4_form_id) && $l4_form_id !== '') {
            $this->common_model->update(AAI_L4_FORM_DATA, $l4FormData, array('l4_form_id' => $l4_form_id));
        } else {
            $l4FormData['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
            $l4FormData['created_date'] = datetimeformat();
            $l4FormData['incident_id'] = $incidentId;
            $l4_form_id = $this->common_model->insert(AAI_L4_FORM_DATA, $l4FormData);
        }
        $returnData = array(
            'l4_form_id' => $l4_form_id,
            'notification_date' => $l4FormData['calculate_notification_missing'],
            'status' => 'success',
        );
        echo json_encode($returnData);
        exit;
    }

    /*
    @Author : Ritesh rana
    @Desc   : Main Listing Page
    @Date   : 29/01/2019
     */

    public function who_was_involved_in_incident($squ_num,$pid=0)
    {
        $incidentId = $this->input->post('incident_id');
        $incidentData = $this->common_model->get_records(AAI_MAIN, array("*"), '', '', array('incident_id' => $incidentId));
        $incidentData = $incidentData[0];
       
        $table                 = AAI_DROPDOWN . ' as dr';
        $where                 = array("d.status" => "1", "dr.status" => "1");
        $fields                = array("dr.dropdown_id", "dr.title", "dr.prefix", "count(d.option_id) as total_options", "GROUP_CONCAT( DISTINCT CONCAT(d.option_id,'|',d.title) ORDER BY d.option_id SEPARATOR ';') as dropdown_options");
        $joinTables            = array(AAI_DROPDOWN_OPTION . ' as d' => 'd.dropdown_id = dr.dropdown_id');
        $groupBy               = "dr.dropdown_id";
        $data['dropdown_data'] = $this->common_model->get_records($table, $fields, $joinTables, 'left', '', '', '', '', '', '', $groupBy, $where);

        //prefix for type data
        foreach ($data['dropdown_data'] as $key1 => $value1) {
            if ($value1['prefix'] == 'pre_outside_agency') {
                if ($value1['total_options'] > 0) {
                    $optionsArray = explode(';', $value1['dropdown_options']);
                    foreach ($optionsArray as $op => $v) {
                        $OpArray                        = explode('|', $v);
                        $finalOptionsArray[$OpArray[0]] = $OpArray[1];
                        // $data['pre_outside_agency'][$op]['label'] = $OpArray[1];
                        //$data['pre_outside_agency'][$op]['value'] = $OpArray[0];
                        $pre_outside_agency[$op]['label'] = $OpArray[1];
                        $pre_outside_agency[$op]['value'] = $OpArray[0];
                    }
                }
            }
        }

        $involved_agency = $this->input->post('involved_agency');
        if (!empty($pre_outside_agency)) {
            foreach ($pre_outside_agency as $key => $select_data) {
                if (in_array($select_data['value'], $involved_agency)) {
                    $pre_outside_agency_data[$key]['value'] = $select_data['value'];
                    $pre_outside_agency_data[$key]['label'] = $select_data['label'];
                }
            }
        }

        $data['pre_outside_agency'] = $pre_outside_agency_data;

        $emailMatch = '(email LIKE "%_@__%.__%")';
        $nfcUsers   = $this->common_model->get_records(LOGIN, array('login_id as user_id', 'firstname as first_name', 'lastname as last_name', 'email'), '', '', '', '', '', '', '', '', '', $emailMatch);

        function appendNFCType3($n)
        {
            $n['user_type']     = 'N';
            $n['job_title']     = '';
            $n['work_location'] = '';
            return $n;
        }

        $nfcUsers    = array_map("appendNFCType3", $nfcUsers);
        $bambooUsers = $this->common_model->get_records(BAMBOOHR_USERS, array('user_id', 'first_name', 'last_name', 'email', 'job_title', 'work_location'), '', '', '', '', '', '', '', '', '', $emailMatch);

        function appendBambooType3($n)
        {
            $n['user_type'] = 'B';
            return $n;
        }
        $bambooUsers       = array_map("appendBambooType3", $bambooUsers);
        $bambooUsersData   = array_merge($bambooUsers, $nfcUsers);
        $involved_employee = $this->input->post('involved_employee');
        if (!empty($bambooUsersData)) {
            foreach ($bambooUsersData as $key => $select) {
                if (in_array($select['user_type'] . '_' . $select['user_id'], $involved_employee)) {
                    $bambooNfcUsers[$key]['user_type']  = $select['user_type'];
                    $bambooNfcUsers[$key]['user_id']    = $select['user_id'];
                    $bambooNfcUsers[$key]['first_name'] = $select['first_name'];
                    $bambooNfcUsers[$key]['last_name']  = $select['last_name'];
                    $bambooNfcUsers[$key]['email']      = $select['email'];
                }
            }
        }

        $data['bambooNfcUsers'] = $bambooNfcUsers;


            $sortfield = 'time';
            $sortby    = 'asc';
            $table = AAI_L2_L3_SEQUENCE_EVENT . ' as sq';
            $sqnm='S'.$squ_num;
            $where = array("sq.incident_id" => $incidentId,'sq.l2_l3_sequence_event_id'=>"'$pid'");

            $fields = array("sq.*,wi.l2Who_was_involved_in_incident");
             $joinTables            = array(AAI_L2_WHO_WAS_INVOLVED . ' as wi' => 'wi.l2_l3_sequence_event_id = sq.l2_l3_sequence_event_id');
            $l2seqresult = $this->common_model->get_records($table, $fields, $joinTables, 'left', '', '', '', '', $sortfield, $sortby, '', $where);
          //  echo "XX";
          //  echo $this->db->last_query();die;
            $data['l2sequence_events'] = $l2seqresult;
            $seqwise_l2_l3_sequence_events=array();
           // pr($l2seqresult);die;
            if(count($l2seqresult)>0)
            {
                    foreach($l2seqresult as $sqdata)
                        {
                            $seqwise_l2_l3_sequence_events[]=$sqdata['l2Who_was_involved_in_incident'];
                        }

            }
            $data['seqwise_l2_l3_sequence_events'] = $seqwise_l2_l3_sequence_events;
            $data['other'] = $this->input->post('other');
            $option_data = $this->load->view($this->viewname . '/l2_repeating_section', $data, true);
            echo json_encode(array('table' => $option_data, 'squ_num' => $squ_num));
            exit;

    }


    /*
    @Author : Ritesh Rana
    @Desc   : User sign off functionality
    @Input    :
    @Output   :
    @Date   : 04/02/2019
     */

    public function manager_review($yp_id, $incident_id)
    {
        if (!empty($yp_id)) {
            $login_user_id      = $this->session->userdata['LOGGED_IN']['ID'];
            $match              = array('incident_id' => $incident_id, 'yp_id' => $yp_id, 'created_by' => $login_user_id, 'is_delete' => '0');
            $check_signoff_data = $this->common_model->get_records(AAI_SIGNOFF, '', '', '', $match);
            if (empty($check_signoff_data) > 0) {
                $update_pre_data['yp_id']        = $yp_id;
                $update_pre_data['incident_id']  = $incident_id;
                $update_pre_data['created_date'] = datetimeformat();
                $update_pre_data['created_by']   = $this->session->userdata('LOGGED_IN')['ID'];
                if ($this->common_model->insert(AAI_SIGNOFF, $update_pre_data)) {
                    /* $u_data['signoff'] = '1';
                    $this->common_model->update(AAI_MAIN,$u_data,array('incident_id'=> $incident_id,'yp_id'=> $yp_id));
                     */
                    $msg = $this->lang->line('successfully_accident_and_incident_record_review');
                    $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");

                } else {
                    // error
                    $msg = $this->lang->line('error_msg');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

                }
            } else {
                $msg = $this->lang->line('already_accident_and_incident_record_review');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

            }
        } else {
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

        }

        redirect('/' . $this->viewname . '/index/' . $yp_id);
    }

    /*
    @Author : Ritesh Rana
    @Desc   : sign off report
    @Input  :
    @Output :
    @Date   : 28/03/2018
     */
    public function signoff($yp_id = '', $incident_id = '')
    {
        $this->formValidation();
        $fields             = array("care_home");
        $data['YP_details'] = YpDetails($yp_id, $fields);

        $data['care_home_id'] = $data['YP_details'][0]['care_home'];
        if ($this->form_validation->run() == false) {

            $data['footerJs'][0] = base_url('uploads/custom/js/AAI/AAI.js');
            $data['footerJs'][1]    = base_url('uploads/custom/js/jquery.blockUI.js');
            $data['crnt_view']   = $this->viewname;

            $data['ypid']        = $yp_id;
            $data['incident_id'] = $incident_id;
            //get current version
            $match                  = array('aai.incident_id' => $incident_id, 'aai.yp_id' => $yp_id);
            $fields_report          = array("aai.archive_incident_id");
            $current_aai_data       = $this->common_model->get_records(ARCHIVE_AAI_MAIN . ' as aai', $fields_report, '', '', $match, '', '1', '', 'archive_incident_id', 'desc');
            $data['aai_archive_id'] = !empty($current_aai_data[0]['archive_incident_id']) ? $current_aai_data[0]['archive_incident_id'] : '';

            //Get Records From Login Table

            //get social info
            $table                      = SOCIAL_WORKER_DETAILS . ' as sw';
            $match                      = array("sw.yp_id" => $yp_id);
            $fields                     = array("sw.social_worker_id,sw.social_worker_firstname,sw.social_worker_surname");
            $data['social_worker_data'] = $this->common_model->get_records($table, $fields, '', '', $match);
            //get parent info
            $table               = PARENT_CARER_INFORMATION . ' as pc';
            $match               = array("pc.yp_id" => $yp_id, 'pc.is_deleted' => 0);
            $fields              = array("pc.parent_carer_id,pc.firstname,pc.surname");
            $data['parent_data'] = $this->common_model->get_records($table, $fields, '', '', $match);

            $data['form_action_path'] = $this->viewname . '/signoff';
            $data['header']           = array('menu_module' => 'YoungPerson');
            $data['validation']       = validation_errors();
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
    @Desc   : insert signoff data
    @Input    :
    @Output   :
    @Date   : 05/02/2019
     */

    public function insertdata()
    {
        $postdata = $this->input->post();
        $ypid           = $postdata['ypid'];
        $incident_id    = $postdata['incident_id'];
        $aai_archive_id = $postdata['aai_archive_id'];
        $user_type      = $postdata['user_type'];
        if ($user_type == 'parent') {
            $parent_data['firstname']                      = strip_tags($postdata['fname']);
            $parent_data['surname']                        = strip_tags($postdata['lname']);
            $parent_data['relationship']                   = strip_tags($postdata['relationship']);
            $parent_data['address']                        = strip_tags($postdata['address']);
            $parent_data['contact_number']                 = strip_tags($postdata['contact_number']);
            $parent_data['email_address']                  = strip_tags($postdata['email']);
            $parent_data['yp_authorised_communication']    = strip_tags($postdata['yp_authorised_communication']);
            $parent_data['carer_authorised_communication'] = strip_tags($postdata['carer_authorised_communication']);
            $parent_data['yp_authorised_communication']    = strip_tags($postdata['yp_authorised_communication']);
            $parent_data['comments']                       = strip_tags($postdata['comments']);
            $parent_data['created_date']                   = datetimeformat();
            $parent_data['created_by']                     = $this->session->userdata['LOGGED_IN']['ID'];
            $parent_data['yp_id']                          = $this->input->post('ypid');
            $success                                       = $this->common_model->insert(PARENT_CARER_INFORMATION, $parent_data);
            //Insert log activity
            $activity = array(
                'user_id'           => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id'             => !empty($postdata['ypid']) ? $postdata['ypid'] : '',
                'module_name'       => PP_PARENT_CARER_DETAILS_YP,
                'module_field_name' => '',
                'type'              => 1,
            );
            log_activity($activity);
        }
        $table          = YP_DETAILS . ' as yp';
        $match          = "yp.is_deleted= '0' AND yp.yp_id = '" . $ypid . "'";
        $fields         = array("yp.yp_id,yp.status");
        $duplicateEmail = $this->common_model->get_records($table, $fields, '', '', $match);
        //Current Login detail
        $main_user_data = $this->session->userdata('LOGGED_IN');

        /*if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect('YoungPerson/view/' . $ypid);
        }*/
        $data['crnt_view'] = $this->viewname;

        $incidentData = $this->common_model->get_records(AAI_MAIN, '', '', '', array('incident_id' => $incident_id));
        $incidentData = $incidentData[0];
        //get signoff data
        $table        = AAI_SIGNOFF . ' as aai';
        $where        = array("aai.incident_id" => $incident_id, "aai.is_delete" => "0");
        $fields       = array("aai.aai_signoff_id");
        $group_by     = array('aai.created_by');
        $signoff_data = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', $group_by, $where);
        
        $archive_signoff = array();
        foreach ($signoff_data as $value) {
            $archive_signoff[] = $value['aai_signoff_id'];
        }
        $signoff_archive = implode(",", $archive_signoff);

        $match = array('status'=>0,'incident_id'=> $incident_id);
        $archive_data = $this->common_model->get_records(AAI_ARCHIVE,'', '', '', $match);
        $SignoffData =array();
        if(!empty($archive_data)){
            foreach ($archive_data as $value) {
                if($value['form_type'] == 'main_form'){
                    $SignoffData['entry_form_data']         = $value['archive_id']; 
                }elseif($value['form_type'] == 'type_of_incident'){
                    $SignoffData['type_of_incident']         = $value['archive_id']; 
                }elseif($value['form_type'] == 'L1'){
                    $SignoffData['l1_form_data']         = $value['archive_id']; 
                }elseif($value['form_type'] == 'L2_L3'){
                    $SignoffData['l2_l3_form_data']         = $value['archive_id']; 
                }elseif($value['form_type'] == 'L4'){
                    $SignoffData['l4_form_data']         = $value['archive_id']; 
                }elseif($value['form_type'] == 'L5'){
                    $SignoffData['l5_form_data']         = $value['archive_id']; 
                }elseif($value['form_type'] == 'L6'){
                    $SignoffData['l6_form_data']         = $value['archive_id']; 
                }elseif($value['form_type'] == 'L7'){
                    $SignoffData['l7_form_data']         = $value['archive_id']; 
                }elseif($value['form_type'] == 'L8'){
                    $SignoffData['l8_form_data']         = $value['archive_id']; 
                }elseif($value['form_type'] == 'L9'){
                    $SignoffData['l9_form_data']         = $value['archive_id']; 
                }elseif($value['form_type'] == 'review'){
                    $SignoffData['review_form_data']         = $value['archive_id']; 
                }elseif($value['form_type'] == 'manager_review'){
                    $SignoffData['manager_review_form_data']         = $value['archive_id']; 
                } 
                
            }
        }
        /*start Archive data*/
        $SignoffData['yp_id']         = $incidentData['yp_id'];        
        $SignoffData['care_home_id']         = $incidentData['care_home_id'];        
        $SignoffData['incident_id']         = $incidentData['incident_id'];        
        $SignoffData['reference_number']         = $incidentData['incident_reference_number'];    
        $SignoffData['date_of_incident']     = $incidentData['created_date'];
        $SignoffData['signoff_data']         = $signoff_archive;
        $SignoffData['user_type'] = ucfirst($postdata['user_type']);
        $SignoffData['fname']     = ucfirst($postdata['fname']);
        $SignoffData['lname']     = ucfirst($postdata['lname']);
        $SignoffData['email']     = $postdata['email'];
        $SignoffData['key_data']  = md5($postdata['email']);
        $SignoffData['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
        $SignoffData['created_date'] = datetimeformat();
        $SignoffData['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
        $SignoffData['modified_date'] = datetimeformat();
        $SignoffData['signoff_created_date'] = datetimeformat();

        //pr($SignoffData);exit;
        //Insert Record in Database
        if ($this->common_model->insert(AAI_SIGNOFF_APPROVAL, $SignoffData)) {
            $signoff_id = $this->db->insert_id();
            $this->sendMailToRelation($SignoffData, $signoff_id); // send mail

            $msg = $this->lang->line('successfully_sign_off');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

        }

        redirect('AAI/index/' . $ypid);
    }

    /*
    @Author : Ritesh Rana
    @Desc   : send email with signoff approval link page
    @Input    :
    @Output   :
    @Date   : 18/08/2017
     */

    private function sendMailToRelation($data = array(), $signoff_id)
    {

        if (!empty($data)) {
            if (!empty($data['yp_id'])) {
                $match      = array("yp_id" => $data['yp_id']);
                $fields     = array("concat(yp_fname,' ',yp_lname) as yp_name");
                $YP_details = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

            }
            $yp_name = !empty($YP_details[0]['yp_name']) ? $YP_details[0]['yp_name'] : "";
            /* Send Created Customer password with Link */
            $toEmailId    = $data['email'];
            $customerName = $data['fname'] . ' ' . $data['lname'];

            $email     = md5($toEmailId);
            $loginLink = base_url('AAI/signoffData/' . $data['yp_id'] . '/' . $signoff_id . '/' . $email);

            $find = array('{NAME}', '{LINK}');

            $replace = array(
                'NAME' => $customerName,
                'LINK' => $loginLink,
            );

            $emailSubject = 'Welcome to NFCTracker';
            $emailBody    = '<div>'
                . '<p>Hello {NAME} ,</p> '
                . '<p>Please find AAI for ' . $yp_name . ' for your approval.</p> '
                . "<p>For security purposes, Please do not forward this email on to any other person. It is for the recipient only and if this is sent in error please advise itsupport@newforestcare.co.uk and delete this email. This link is only valid for " . REPORT_EXPAIRED_HOUR . ", should this not be signed off within " . REPORT_EXPAIRED_HOUR . " of recieving then please request again</p>"
                . '<p> <a href="{LINK}">click here</a> </p> '
                . '<div>';

            $finalEmailBody = str_replace($find, $replace, $emailBody);

            return $this->common_model->sendEmail($toEmailId, $emailSubject, $finalEmailBody, FROM_EMAIL_ID);
        }
        return true;
    }

    /*
    @Author : Ritesh Rana
    @Desc   : sign off formValidation
    @Input  :
    @Output :
    @Date   : 05/02/2019
     */
    public function formValidation($id = null)
    {
        $this->form_validation->set_rules('fname', 'Firstname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('lname', 'Lastname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');

    }

    /*
    @Author : Ritesh Rana
    @Desc   : get user type detail
    @Input  :
    @Output :
    @Date   : 05/02/2019
     */
    public function getUserTypeDetail()
    {
        $postdata    = $this->input->post();
        $user_type   = !empty($postdata['user_type']) ? $postdata['user_type'] : '';
        $id          = $postdata['id'];
        $ypid        = $postdata['ypid'];
        $table       = YP_DETAILS . ' as yp';
        $match       = "yp.yp_id = " . $ypid;
        $fields      = array("yp.*,swd.social_worker_firstname,swd.social_worker_surname,swd.landline,swd.mobile,swd.email,swd.senior_social_worker_firstname,swd.senior_social_worker_surname,oyp.pen_portrait,swd.other,pc.firstname,pc.surname,pc.relationship,pc.address,pc.contact_number,pc.email_address,pc.yp_authorised_communication,pc.carer_authorised_communication,pc.comments");
        $join_tables = array(SOCIAL_WORKER_DETAILS . ' as swd' => 'swd.yp_id= yp.yp_id', PARENT_CARER_INFORMATION . ' as pc' => 'pc.yp_id= yp.yp_id', OVERVIEW_OF_YOUNG_PERSON . ' as oyp' => 'oyp.yp_id= yp.yp_id');

        $data['editRecord'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
        if ($user_type == 'parent_data') {

            //get parent info
            $table                             = PARENT_CARER_INFORMATION . ' as pc';
            $match                             = array("pc.parent_carer_id" => $id, 'pc.is_deleted' => 0);
            $fields                            = array("pc.*");
            $data['parent_data']               = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['editRecord'][0]['fname']    = $data['editRecord'][0]['firstname'];
            $data['editRecord'][0]['lname']    = $data['editRecord'][0]['surname'];
            $data['editRecord'][0]['email_id'] = $data['editRecord'][0]['email_address'];
        } else if ($user_type == 'social_data') {
            //get social info
            $table                             = SOCIAL_WORKER_DETAILS . ' as sw';
            $match                             = array("sw.social_worker_id" => $id);
            $fields                            = array("sw.*");
            $data['social_worker_data']        = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['editRecord'][0]['fname']    = $data['editRecord'][0]['social_worker_firstname'];
            $data['editRecord'][0]['lname']    = $data['editRecord'][0]['social_worker_surname'];
            $data['editRecord'][0]['email_id'] = $data['editRecord'][0]['email'];
        }

        $data['user_type'] = $user_type;
        $this->load->view($this->viewname . '/signoff_ajax', $data);

    }

    /*
    @Author : Ritesh Rana
    @Desc   : Edit Incident Form
    @Date   : 21/01/2018
     */

    public function approvalView($signoff_approval_id, $incident_id, $formNumber = 1)
    {
        $incidentData = $this->common_model->get_records(AAI_SIGNOFF_APPROVAL, '', '', '', array('signoff_approval_id' => $signoff_approval_id));
        $incidentData = $incidentData[0];

        $allForms = $this->common_model->get_records(AAI_FORM, '', '', '');
        $allForms = array_combine(range(1, count($allForms)), array_values($allForms));

        /* type of incident form start */
        $data = array(
            'is_pi'                 => $incidentData['is_pi'],
            'is_yp_missing'         => $incidentData['is_yp_missing'],
            'is_yp_injured'         => $incidentData['is_yp_injured'],
            'is_yp_complaint'       => $incidentData['is_yp_complaint'],
            'is_yp_safeguarding'    => $incidentData['is_yp_safeguarding'],
            'is_staff_injured'      => $incidentData['is_staff_injured'],
            'is_other_injured'      => $incidentData['is_other_injured'],
            'review_status'         => $incidentData['review_status'],
            'manager_review_status' => $incidentData['manager_review_status'],
        );
        
        /* type of incident form start */

        /* entry form start */
        $entryForm = $allForms[AAI_MAIN_ENTRY_FORM_ID];
        if (!empty($entryForm)) {
            $data['entry_form_data'] = json_decode($entryForm['form_json_data'], true);
        }

        $table                 = AAI_DROPDOWN . ' as dr';
        $where                 = array("d.status" => "1", "dr.status" => "1");
        $fields                = array("dr.dropdown_id", "dr.title", "dr.prefix", "count(d.option_id) as total_options", "GROUP_CONCAT( DISTINCT CONCAT(d.option_id,'|',d.title) ORDER BY d.option_id SEPARATOR ';') as dropdown_options");
        $joinTables            = array(AAI_DROPDOWN_OPTION . ' as d' => 'd.dropdown_id = dr.dropdown_id');
        $groupBy               = "dr.dropdown_id";
        $data['dropdown_data'] = $this->common_model->get_records($table, $fields, $joinTables, 'left', '', '', '', '', '', '', $groupBy, $where);
        foreach ($data['entry_form_data'] as $key => $value) {
            if (isset($value['type']) && $value['type'] == 'select') {
                foreach ($data['dropdown_data'] as $key1 => $value1) {
                    if ($value['description'] == $value1['prefix']) {
                        if ($value1['total_options'] > 0) {
                            $optionsArray = explode(';', $value1['dropdown_options']);
                            foreach ($optionsArray as $op => $v) {
                                $OpArray                                               = explode('|', $v);
                                $finalOptionsArray[$OpArray[0]]                        = $OpArray[1];
                                $data['entry_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                $data['entry_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                            }
                        }
                    }
                }
            }
        }

        //prefix for type data
        foreach ($data['dropdown_data'] as $key1 => $value1) {
            if ($value1['prefix'] == 'pre_outside_agency') {
                if ($value1['total_options'] > 0) {
                    $optionsArray = explode(';', $value1['dropdown_options']);
                    foreach ($optionsArray as $op => $v) {
                        $OpArray                                  = explode('|', $v);
                        $finalOptionsArray[$OpArray[0]]           = $OpArray[1];
                        $data['pre_outside_agency'][$op]['label'] = $OpArray[1];
                        $data['pre_outside_agency'][$op]['value'] = $OpArray[0];
                    }
                }
            }
        }

        //prefix for pre_outside_agency data
        foreach ($data['dropdown_data'] as $key1 => $value1) {
            if ($value1['prefix'] == 'type') {
                if ($value1['total_options'] > 0) {
                    $optionsArray = explode(';', $value1['dropdown_options']);
                    foreach ($optionsArray as $op => $v) {
                        $OpArray                        = explode('|', $v);
                        $finalOptionsArray[$OpArray[0]] = $OpArray[1];
                        $data['type'][$op]['label']     = $OpArray[1];
                        $data['type'][$op]['value']     = $OpArray[0];
                    }
                }
            }
        }

        //prefix for Position_of_yp data
        foreach ($data['dropdown_data'] as $key1 => $value1) {
            if ($value1['prefix'] == 'position_of_yp') {
                if ($value1['total_options'] > 0) {
                    $optionsArray = explode(';', $value1['dropdown_options']);
                    foreach ($optionsArray as $op => $v) {
                        $OpArray                              = explode('|', $v);
                        $finalOptionsArray[$OpArray[0]]       = $OpArray[1];
                        $data['position_of_yp'][$op]['label'] = $OpArray[1];
                        $data['position_of_yp'][$op]['value'] = $OpArray[0];
                    }
                }
            }
        }

        /*code by Ritesh rana*/

        $editMainEntryData = json_decode($incidentData['entry_form_data'], true);
        foreach ($editMainEntryData as $key => $value) {
            if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                $editData[$value['name']] = $value;
            }
        }
        foreach ($data['entry_form_data'] as $key => $value) {
            if (isset($value['name']) && $value['name'] == $editData[$value['name']]['name']) {
                $data['entry_form_data'][$key]['value'] = str_replace("\'", "'", $editData[$value['name']]['value']);
            }
        }
        $data['entry_report_compiler'] = $editMainEntryData['report_compiler'];
        $data['relatedIncident']       = $editMainEntryData['related_incident'];
        $data['reporting_user']        = $editMainEntryData['reporting_user'];

        $emailMatch = '(email LIKE "%_@__%.__%")';
        $nfcUsers   = $this->common_model->get_records(LOGIN, array('login_id as user_id', 'firstname as first_name', 'lastname as last_name', 'email'), '', '', '', '', '', '', '', '', '', $emailMatch);

        function appendNFCType4($n)
        {
            $n['user_type']     = 'N';
            $n['job_title']     = '';
            $n['work_location'] = '';
            return $n;
        }

        $nfcUsers    = array_map("appendNFCType4", $nfcUsers);
        $bambooUsers = $this->common_model->get_records(BAMBOOHR_USERS, array('user_id', 'first_name', 'last_name', 'email', 'job_title', 'work_location'), '', '', '', '', '', '', '', '', '', $emailMatch);

        function appendBambooType4($n)
        {
            $n['user_type'] = 'B';
            return $n;
        }
        $bambooUsers            = array_map("appendBambooType4", $bambooUsers);
        $data['bambooNfcUsers'] = array_merge($bambooUsers, $nfcUsers);
        $data['loggedInUser']   = $this->session->userdata['LOGGED_IN'];
        $table                  = AAI_MAIN . ' as a';
        $join_tables            = array(YP_DETAILS . ' as yp' => 'a.yp_id=yp.yp_id', CARE_HOME . ' as c' => 'c.care_home_id=yp.care_home');
        $dateQuery              = 'date(a.created_date) >= CURDATE() - INTERVAL 14 DAY ';
        $data['YPIncidentData'] = $this->common_model->get_records($table, '', $join_tables, '', array('a.yp_id' => $incidentData['yp_id'], 'a.incident_id !=' => $incidentId), '', '', '', '', '', '', $dateQuery);
        /* entry form end */

        /* code by Ritesh Rana */
        /* L1 start */
        $l1Form = $allForms[AAI_L1_FORM_ID];
        if (!empty($l1Form)) {
            $data['l1_form_data'] = json_decode($l1Form['form_json_data'], true);
            foreach ($data['l1_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l1_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l1_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }

            if (isset($incidentData['l1_form_data']) && !empty($incidentData['l1_form_data'])) {
                $editl1Data = json_decode($incidentData['l1_form_data'], true);
                foreach ($editl1Data as $key => $value) {
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editl1Data[$value['name']] = $value;
                    }
                }
                foreach ($data['l1_form_data'] as $key => $value) {
                    if (isset($value['name']) && $value['name'] == $editl1Data[$value['name']]['name']) {
                        $data['l1_form_data'][$key]['value'] = str_replace("\'", "'", $editl1Data[$value['name']]['value']);
                    }
                }
                $data['l1_report_compiler'] = $editl1Data['report_compiler'];
                $data['l1_total_duration']  = $editl1Data['l1_total_duration'];
                $data['l1reference_number'] = $incidentData['l1_reference_number'];
            }else{
                $data['l1reference_number'] = '';
            }

            /* L1 PREV DATA */
        }
        /* code end ritesh Rana */
        /* L1 end */

        /*cide by ritesh Rana*/
        /* L2 start */
        $l2Form = $allForms[AAI_L2NL3_FORM_ID];
        if (!empty($l2Form)) {
            $data['l2_form_data'] = json_decode($l2Form['form_json_data'], true);
            foreach ($data['l2_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l2_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l2_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }

            if (isset($incidentData['l2_l3_form_data']) && !empty($incidentData['l2_l3_form_data'])) {
                $editl2Data = json_decode($incidentData['l2_l3_form_data'], true);

                foreach ($editl2Data as $key => $value) {
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editl2Data[$value['name']] = $value;
                    }
                }
                foreach ($data['l2_form_data'] as $key => $value) {
                    if (isset($value['name']) && $value['name'] == $editl2Data[$value['name']]['name']) {
                        $data['l2_form_data'][$key]['value'] = str_replace("\'", "'", $editl2Data[$value['name']]['value']);
                    }
                    if (isset($value) && $value['name'] == 'l2_involved_other') {
                        $data['l2_involved_other_data'] = str_replace("\'", "'", $editl2Data[$value['name']]['value']);
                    }
                }
                $data['l2_report_compiler'] = $editl2Data['report_compiler'];
                $data['l2_total_duration']  = $editl2Data['l2_total_duration'];

                /* start code by ritesh Rana*/
                /*start Sequence of events */
                $l2sequence_data                      = array();
                $l2sequence_data['l2sequence_number'] = $editl2Data['l2sequence_number'];
                $l2sequence_data['l2position']        = $editl2Data['l2position'];
                $l2sequence_data['l2type']            = $editl2Data['l2type'];
                $l2sequence_data['l2comments']        = $editl2Data['l2comments'];
                $l2sequence_data['l2time_sequence']   = $editl2Data['l2time_sequence'];

                $l2seqresult = array();
                $count_se    = count($editl2Data['l2sequence_number']);
                foreach ($l2sequence_data as $kk => $vv) {
                    for ($se = 0; $se < $count_se; $se++) {
                        $l2seqresult[$se][$kk]                                        = $l2sequence_data[$kk][$se];
                        $doc_se                                                       = $se + 1;
                        $l2seqresult[$se]['l2Who_was_involved_in_incident' . $doc_se] = $editl2Data['l2Who_was_involved_in_incident' . $doc_se];
                    }

                }
                $data['l2sequence_events'] = $l2seqresult;

                /*end Sequence of events */
                /*start Medical Observations*/
                $l2sequence_data_mo                                         = array();
                $l2sequence_data_mo['l2medical_observations_after_minutes'] = $editl2Data['l2medical_observations_after_minutes'];
                $l2sequence_data_mo['l2time_medical']                       = $editl2Data['l2time_medical'];
                $l2sequence_data_mo['l2comments_mo']                        = $editl2Data['l2comments_mo'];

                $l2seqresult_mo = array();
                $count_mo       = count($editl2Data['l2medical_observations_after_minutes']);
                foreach ($l2sequence_data_mo as $kk => $vv) {
                    for ($mo = 0; $mo < $count_mo; $mo++) {
                        $l2seqresult_mo[$mo][$kk]                                      = $l2sequence_data_mo[$kk][$mo];
                        $doc_mo                                                        = $mo + 1;
                        $l2seqresult_mo[$mo]['l2_medical_observation_taken' . $doc_mo] = $editl2Data['l2_medical_observation_taken' . $doc_mo];
                        $l2seqresult_mo[$mo]['l2Observation_taken_by' . $doc_mo]       = $editl2Data['l2Observation_taken_by' . $doc_mo];
                    }
                }
                $data['l2medical_observations'] = $l2seqresult_mo;
                /*End Medical Observations*/
                /* end code by ritesh Rana*/
                $data['l2reference_number'] = $incidentData['l2_l3_reference_number'];
            }else{
                $data['l2reference_number'] = '';
            }
        }
        /* L2 end */

        /* L4 form start */
        $l4Form = $allForms[AAI_L4_FORM_ID];
        if (!empty($l4Form)) {
            $data['l4_form_data'] = json_decode($l4Form['form_json_data'], true);
            foreach ($data['l4_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l4_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l4_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }
            if (isset($incidentData['l4_form_data']) && !empty($incidentData['l4_form_data'])) {
                $editl4Data = json_decode($incidentData['l4_form_data'], true);
                foreach ($editl4Data as $key => $value) {
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editl4Data[$value['name']] = $value;
                    }
                }
                foreach ($data['l4_form_data'] as $key => $value) {
                    if (isset($value['name']) && $value['name'] == $editl4Data[$value['name']]['name']) {
                        $data['l4_form_data'][$key]['value'] = str_replace("\'", "'", $editl4Data[$value['name']]['value']);
                    }
                }
                $data['l4reference_number'] = $incidentData['l4_reference_number'];
            }else{
                $data['l4reference_number'] = '';
            }
        }

        /* L4 form end */
        /* L5 form start */
        $l5Form = $allForms[AAI_L5_FORM_ID];
        if (!empty($l5Form)) {
            $data['l5_form_data'] = json_decode($l5Form['form_json_data'], true);

            foreach ($data['l5_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l5_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l5_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }
            if (isset($incidentData['l5_form_data']) && !empty($incidentData['l5_form_data'])) {
                $editl5Data = json_decode($incidentData['l5_form_data'], true);

                foreach ($editl5Data as $key => $value) {
                    //pr($key);
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editl5Data[$value['name']] = $value;
                    }
                }
                foreach ($data['l5_form_data'] as $key => $value) {
                    if (isset($value['name']) && $value['name'] == $editl5Data[$value['name']]['name']) {
                        $data['l5_form_data'][$key]['value'] = str_replace("\'", "'", $editl5Data[$value['name']]['value']);
                    }
                }

                if (!empty($prev_incidentData['l5_form_data'])) {
                    $prevl5Data = json_decode($prev_incidentData['l5_form_data'], true);
                    foreach ($prevl5Data as $key => $prevalue) {
                        if (isset($prevalue['type']) && $prevalue['type'] != 'button' && $prevalue['type'] != 'header') {
                            $preveditl5Data[$prevalue['name']] = $prevalue;
                        }
                    }
                }

                $data['preveditl5Data']     = $preveditl5Data;
                $data['l5_report_compiler'] = $editl5Data['l5_report_compiler'];
                $data['l5_body_map']        = $editl5Data;
                $data['l5reference_number'] = $incidentData['l5_reference_number'];
            }else{
                $data['l5reference_number'] = '';
            }
        }
        /* L5 end */
        /*code by Ritesh rana*/
        /* L6 start */
        $l6Form = $allForms[AAI_L6_FORM_ID];
        if (!empty($l6Form)) {
            $data['l6_form_data'] = json_decode($l6Form['form_json_data'], true);
            foreach ($data['l6_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l6_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l6_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }

            if (isset($incidentData['l6_form_data']) && !empty($incidentData['l6_form_data'])) {
                $editl6Data = json_decode($incidentData['l6_form_data'], true);
                foreach ($editl6Data as $key => $value) {
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editl6Data[$value['name']] = $value;
                    }
                }
                foreach ($data['l6_form_data'] as $key => $value) {
                    if (isset($value['name']) && $value['name'] == $editl6Data[$value['name']]['name']) {
                        $data['l6_form_data'][$key]['value'] = str_replace("\'", "'", $editl6Data[$value['name']]['value']);
                    }
                }
                $data['l6_report_compiler'] = $editl6Data['report_compiler'];
                /* start code by ritesh Rana*/
                /*start Sequence of events */
                $l6sequence_data['l6sequence_number']      = $editl6Data['l6sequence_number'];
                $l6sequence_data['l6who_raised_complaint'] = $editl6Data['l6who_raised_complaint'];
                $l6sequence_data['l6what_happened']        = $editl6Data['l6what_happened'];
                $l6sequence_data['l6sequence_date']        = $editl6Data['l6sequence_date'];
                $l6sequence_data['l6time_sequence']        = $editl6Data['l6time_sequence'];
                $l6seqresult                               = array();
                $count_sq                                  = count($editl6Data['l6sequence_number']);
                foreach ($l6sequence_data as $kk => $vv) {
                    for ($sq = 0; $sq < $count_sq; $sq++) {
                        $l6seqresult[$sq][$kk] = $l6sequence_data[$kk][$sq];
                    }
                }
                $data['l6sequence_data'] = $l6seqresult;
                $data['l6reference_number'] = $incidentData['l6_reference_number'];
                /*start Sequence of events end */
            }else{
                $data['l6reference_number'] = '';
            }
        }
        /*code end by ritesh rana*/

        /* L7 start */
        /*code by Ritesh Rana*/
        $l7Form = $allForms[AAI_L7_FORM_ID];
        if (!empty($l7Form)) {
            $data['l7_form_data'] = json_decode($l7Form['form_json_data'], true);
            foreach ($data['l7_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l7_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l7_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }

            if (isset($incidentData['l7_form_data']) && !empty($incidentData['l7_form_data'])) {
                $editl7Data = json_decode($incidentData['l7_form_data'], true);
                foreach ($editl7Data as $key => $value) {
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editl7Data[$value['name']] = $value;
                    }
                }
                $l7sequence_data                           = array();
                $l7sequence_data['l7sequence_number']      = $editl7Data['l7sequence_number'];
                $l7sequence_data['l7update_by']            = $editl7Data['l7update_by'];
                $l7sequence_data['l7daily_action_taken']   = $editl7Data['l7daily_action_taken'];
                $l7sequence_data['l7daily_action_outcome'] = $editl7Data['l7daily_action_outcome'];
                $l7sequence_data['l7date_safeguarding']    = $editl7Data['l7date_safeguarding'];
                $l7sequence_data['l7time_safeguard']       = $editl7Data['l7time_safeguard'];

                $l7seqresult = array();
                $count       = count($editl7Data['l7sequence_number']);
                foreach ($l7sequence_data as $kk => $vv) {
                    for ($i = 0; $i < $count; $i++) {
                        $l7seqresult[$i][$kk]                             = $l7sequence_data[$kk][$i];
                        $doc                                              = $i + 1;
                        $l7seqresult[$i]['l7supporting_documents' . $doc] = $editl7Data['l7supporting_documents' . $doc];
                    }
                }

                $data['l7sequence_data'] = $l7seqresult;

                foreach ($data['l7_form_data'] as $key => $value) {
                    if (isset($value['name']) && $value['name'] == $editl7Data[$value['name']]['name']) {
                        $data['l7_form_data'][$key]['value'] = str_replace("\'", "'", $editl7Data[$value['name']]['value']);
                    }
                }
                $data['l7reference_number'] = $incidentData['l7_reference_number'];
            }else{
                $data['l7reference_number'] = '';
            }
        }
        /*end*/
        /* L7 end */
		
		/* L8 form start */
                    $l8Form = $allForms[AAI_L8_FORM_ID];
                    if (!empty($l8Form)) {
                        $data['l8_form_data'] = json_decode($l8Form['form_json_data'], true);

                        foreach ($data['l8_form_data'] as $key => $value) {
                            if (isset($value['type']) && $value['type'] == 'select') {
                                foreach ($data['dropdown_data'] as $key1 => $value1) {
                                    if ($value['description'] == $value1['prefix']) {
                                        if ($value1['total_options'] > 0) {
                                            $optionsArray = explode(';', $value1['dropdown_options']);
                                            foreach ($optionsArray as $op => $v) {
                                                $OpArray                                            = explode('|', $v);
                                                $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                                $data['l8_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                                $data['l8_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                            }
                                        }
                                    }
                                }
                            }
                        }
						 if(!empty($incidentData['l1_form_data'])){
                            $editl1Data = json_decode($incidentData['l1_form_data'], true);
                            $data['date_of_incident'] = $editl1Data[1]['value'];
                            $data['time_of_incident'] = $editl1Data[2]['value'];
                        }else{
                            $editl2Data = json_decode($incidentData['l2_l3_form_data'], true);
                            $data['date_of_incident'] = $editl2Data[1]['value'];
                            $data['time_of_incident'] = $editl2Data[2]['value'];
                        }
                        if (isset($incidentData['l8_form_data']) && !empty($incidentData['l8_form_data'])) {
                            $editl8Data = json_decode($incidentData['l8_form_data'], true);
							$editl1Data = json_decode($incidentData['l1_form_data'], true);

                            foreach ($editl8Data as $key => $value) {
                                //pr($key);
                                if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                                    $editl8Data[$value['name']] = $value;
                                }
                            }
                            foreach ($data['l8_form_data'] as $key => $value) {
                                if (isset($value['name']) && $value['name'] == $editl8Data[$value['name']]['name']) {
                                    $data['l8_form_data'][$key]['value'] = str_replace("\'", "'", $editl8Data[$value['name']]['value']);
                                }
                            }

                            if (!empty($prev_incidentData['l8_form_data'])) {
                                $prevl8Data = json_decode($prev_incidentData['l8_form_data'], true);
                                foreach ($prevl8Data as $key => $prevalue) {
                                    if (isset($prevalue['type']) && $prevalue['type'] != 'button' && $prevalue['type'] != 'header') {
                                        $preveditl8Data[$prevalue['name']] = $prevalue;
                                    }
                                }
                            }

                            $data['preveditl8Data']     = $preveditl8Data;
                             $data['l8_report_compiler'] = $editl8Data['l8_report_compiler'];
							 $data['l8_date_of_incident'] = $editl8Data['l8_date_of_incident'];
							 $data['l8_time_of_incident'] = $editl8Data['l8_time_of_incident'];
                             $data['l8reference_number'] = $incidentData['l8_reference_number'];
                           
                        }else{
                            $data['l8reference_number'] = '';
                        }
                    }
                    /* L8 end */
					
					/* L9 form start */
                    $l9Form = $allForms[AAI_L9_FORM_ID];
                    if (!empty($l9Form)) {
                        $data['l9_form_data'] = json_decode($l9Form['form_json_data'], true);

                        foreach ($data['l9_form_data'] as $key => $value) {
                            if (isset($value['type']) && $value['type'] == 'select') {
                                foreach ($data['dropdown_data'] as $key1 => $value1) {
                                    if ($value['description'] == $value1['prefix']) {
                                        if ($value1['total_options'] > 0) {
                                            $optionsArray = explode(';', $value1['dropdown_options']);
                                            foreach ($optionsArray as $op => $v) {
                                                $OpArray                                            = explode('|', $v);
                                                $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                                $data['l9_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                                $data['l9_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if (isset($incidentData['l9_form_data']) && !empty($incidentData['l9_form_data'])) {
                            $editl9Data = json_decode($incidentData['l9_form_data'], true);
							$editl1Data = json_decode($incidentData['l1_form_data'], true);

                            foreach ($editl9Data as $key => $value) {
                                //pr($key);
                                if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                                    $editl9Data[$value['name']] = $value;
                                }
                            }
                            foreach ($data['l9_form_data'] as $key => $value) {
                                if (isset($value['name']) && $value['name'] == $editl9Data[$value['name']]['name']) {
                                    $data['l9_form_data'][$key]['value'] = str_replace("\'", "'", $editl9Data[$value['name']]['value']);
                                }
                            }

                            if (!empty($prev_incidentData['l9_form_data'])) {
                                $prevl9Data = json_decode($prev_incidentData['l9_form_data'], true);
                                foreach ($prevl9Data as $key => $prevalue) {
                                    if (isset($prevalue['type']) && $prevalue['type'] != 'button' && $prevalue['type'] != 'header') {
                                        $preveditl9Data[$prevalue['name']] = $prevalue;
                                    }
                                }
                            }

                            $data['preveditl9Data']     = $preveditl9Data;
                             $data['l9_report_compiler'] = $editl9Data['l9_report_compiler'];
							 $data['l9_date_of_incident'] = $editl9Data['l9_date_of_incident'];
							 $data['l9_time_of_incident'] = $editl9Data['l9_time_of_incident'];
                             $data['l9reference_number'] = $incidentData['l9_reference_number'];
                        }else{
                             $data['l9reference_number'] = '';
                        }
                    }
                    /* L9 end */
					
					$data['editl1Data']         = $editl1Data;
        $data['l1date_of_incident'] = $data['editl1Data'][1]['value'];
        $data['l1time_of_incident'] = $data['editl1Data'][2]['value'];

        $reviewForm = $allForms[REVIEW];
        if (!empty($reviewForm)) {
            $data['review_form_data'] = json_decode($reviewForm['form_json_data'], true);
            foreach ($data['review_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                                = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                         = $OpArray[1];
                                    $data['review_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['review_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }

            if (isset($incidentData['review_form_data']) && !empty($incidentData['review_form_data'])) {
                $editReviewData = json_decode($incidentData['review_form_data'], true);
                foreach ($editReviewData as $key => $value) {
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editReviewData[$value['name']] = $value;
                    }
                }

                foreach ($data['review_form_data'] as $key => $value) {
                    if (isset($value['name']) && $value['name'] == $editReviewData[$value['name']]['name']) {
                        $data['review_form_data'][$key]['value'] = str_replace("\'", "'", $editReviewData[$value['name']]['value']);
                    }
                }

            }
        }
        /* Review end */

        /* Manager review start */

        $ManagersReviewForm = $allForms[MANAGER_REVIEW];
        if (!empty($ManagersReviewForm)) {
            $data['manager_review_form_data'] = json_decode($ManagersReviewForm['form_json_data'], true);
            foreach ($data['manager_review_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                                        = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                                 = $OpArray[1];
                                    $data['manager_review_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['manager_review_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }

            if (isset($incidentData['manager_review_form_data']) && !empty($incidentData['manager_review_form_data'])) {
                $editManagerReviewData = json_decode($incidentData['manager_review_form_data'], true);
                foreach ($editManagerReviewData as $key => $value) {
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editManagerReviewData[$value['name']] = $value;
                    }
                }

                foreach ($data['manager_review_form_data'] as $key => $value) {
                    if (isset($value['name']) && $value['name'] == $editManagerReviewData[$value['name']]['name']) {
                        $data['manager_review_form_data'][$key]['value'] = str_replace("\'", "'", $editManagerReviewData[$value['name']]['value']);
                    }
                }
            }
        }
        /* Manager Review end */

        //get signoff data
		

        $array_data = explode(',', $incidentData['signoff_data']);

        $table                = AAI_SIGNOFF . ' as aai';
        $where                = array("l.is_delete" => "0");
        $where_in             = array("aai.aai_signoff_id" => $array_data);
        $fields               = array("aai.aai_signoff_id,aai.created_by,aai.created_date,aai.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables          = array(LOGIN . ' as l' => 'l.login_id=aai.created_by');
        $group_by             = array('aai.created_by');
        $data['signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where, '', $where_in);

        $data['YP_details']     = $this->common_model->get_records(YP_DETAILS, array("yp_id,care_home,yp_fname,yp_lname,DATE_FORMAT(date_of_birth, '%d/%m/%Y') as date_of_birth"), '', '', array("yp_id" => $incidentData['yp_id']));
        $data['crnt_view']      = $this->module;
        $data['editMode']       = $formNumber;
        $data['ypId']           = $incidentData['yp_id'];
        $data['isCareIncident'] = $incidentData['is_care_incident'];
        $data['signoff_approval_id'] = $signoff_approval_id;
        $data['key_data']            = $incidentData['key_data'];

        $data['incidentId']     = $incidentId;
        $data['incidentData']   = $incidentData;
        $data['footerJs'][0]    = base_url('uploads/custom/js/AAI/AAI.js');
        $data['footerJs'][1]    = base_url('uploads/custom/js/jquery.blockUI.js');
        $data['main_content']   = '/approval_view_aai.php';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }

    /*
    @Author : Ritesh Rana
    @Desc   : view signoff approval link page
    @Input    :
    @Output   :
    @Date   : 06/02/2019
     */

    public function signoffData($yp_id, $signoff_approval_id, $email, $pdfdata='')
    {

        if (is_numeric($yp_id) && is_numeric($signoff_approval_id) && !empty($email)) {

            $match        = array('yp_id' => $yp_id, 'key_data' => $email, 'status' => 'inactive', ' signoff_approval_id' => $signoff_approval_id);
            $incidentData_val = $this->common_model->get_records(AAI_SIGNOFF_APPROVAL, array("*"), '', '', $match);
            $incidentData = $incidentData_val[0];

            if (!empty($incidentData)) {
                $expairedDate = date('Y-m-d H:i:s', strtotime($incidentData['signoff_created_date'] . REPORT_EXPAIRED_DAYS));
                if (strtotime(datetimeformat()) <= strtotime($expairedDate)) {
                    $allForms = $this->common_model->get_records(AAI_FORM, '', '', '');
                    $allForms = array_combine(range(1, count($allForms)), array_values($allForms));

                $arcive_val = array();    
                foreach ($incidentData_val as $value) {
                           $arcive_val[] = $value['entry_form_data'];
                           $arcive_val[] = $value['type_of_incident'];
                           $arcive_val[] = $value['l1_form_data'];
                           $arcive_val[] = $value['l2_l3_form_data'];                 
                           $arcive_val[] = $value['l4_form_data'];
                           $arcive_val[] = $value['l5_form_data'];
                           $arcive_val[] = $value['l6_form_data'];
                           $arcive_val[] = $value['l7_form_data'];
                           $arcive_val[] = $value['l8_form_data'];
                           $arcive_val[] = $value['l9_form_data'];
                } 
                
                    $in_array = array('archive_id' => $arcive_val);
                   $match = array('status'=>0,'incident_id'=> $incidentData['incident_id']);
                   $archive_data = $this->common_model->get_records(AAI_ARCHIVE,'', '', '', $match,'','','','','','','','',$in_array);

                    $aai_array=array();
                   foreach ($archive_data as $value_data) {
                      $aai_array[$value_data['form_type']]=$value_data;
                   }
                   //pr($aai_array);exit;
                    $type_of_incident = $aai_array['type_of_incident'];
                    $TypeofIncidentData = json_decode($type_of_incident['form_json_data'], true);
                    /* type of incident form start */
                    $data = array(
                        'is_pi'                 => $TypeofIncidentData['is_pi'],
                        'is_yp_missing'         => $TypeofIncidentData['is_yp_missing'],
                        'is_yp_injured'         => $TypeofIncidentData['is_yp_injured'],
                        'is_yp_complaint'       => $TypeofIncidentData['is_yp_complaint'],
                        'is_yp_safeguarding'    => $TypeofIncidentData['is_yp_safeguarding'],
                        'is_staff_injured'      => $TypeofIncidentData['is_staff_injured'],
                        'is_other_injured'      => $TypeofIncidentData['is_other_injured'],
                        'review_status'         => $TypeofIncidentData['review_status'],
                        'manager_review_status' => $TypeofIncidentData['manager_review_status'],
                    );
                    //pr($data);exit;

                    /* type of incident form start */

                    /* entry form start */
                    $entryForm = $allForms[AAI_MAIN_ENTRY_FORM_ID];
                    if (!empty($entryForm)) {
                        $data['entry_form_data'] = json_decode($entryForm['form_json_data'], true);
                    }

                    $table                 = AAI_DROPDOWN . ' as dr';
                    $where                 = array("d.status" => "1", "dr.status" => "1");
                    $fields                = array("dr.dropdown_id", "dr.title", "dr.prefix", "count(d.option_id) as total_options", "GROUP_CONCAT( DISTINCT CONCAT(d.option_id,'|',d.title) ORDER BY d.option_id SEPARATOR ';') as dropdown_options");
                    $joinTables            = array(AAI_DROPDOWN_OPTION . ' as d' => 'd.dropdown_id = dr.dropdown_id');
                    $groupBy               = "dr.dropdown_id";
                    $data['dropdown_data'] = $this->common_model->get_records($table, $fields, $joinTables, 'left', '', '', '', '', '', '', $groupBy, $where);
                    foreach ($data['entry_form_data'] as $key => $value) {
                        if (isset($value['type']) && $value['type'] == 'select') {
                            foreach ($data['dropdown_data'] as $key1 => $value1) {
                                if ($value['description'] == $value1['prefix']) {
                                    if ($value1['total_options'] > 0) {
                                        $optionsArray = explode(';', $value1['dropdown_options']);
                                        foreach ($optionsArray as $op => $v) {
                                            $OpArray                                               = explode('|', $v);
                                            $finalOptionsArray[$OpArray[0]]                        = $OpArray[1];
                                            $data['entry_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                            $data['entry_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                        }
                                    }
                                }
                            }
                        }
                    }

                    //prefix for type data
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value1['prefix'] == 'pre_outside_agency') {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                  = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]           = $OpArray[1];
                                    $data['pre_outside_agency'][$op]['label'] = $OpArray[1];
                                    $data['pre_outside_agency'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }

                    //prefix for pre_outside_agency data
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value1['prefix'] == 'type') {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                        = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]] = $OpArray[1];
                                    $data['type'][$op]['label']     = $OpArray[1];
                                    $data['type'][$op]['value']     = $OpArray[0];
                                }
                            }
                        }
                    }

                    //prefix for Position_of_yp data
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value1['prefix'] == 'position_of_yp') {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                              = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]       = $OpArray[1];
                                    $data['position_of_yp'][$op]['label'] = $OpArray[1];
                                    $data['position_of_yp'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }

                     //prefix for Position_of_yp data
        foreach ($data['dropdown_data'] as $key1 => $value1) {
            if ($value1['prefix'] == 'persons_infromed') {
                if ($value1['total_options'] > 0) {
                    $optionsArray = explode(';', $value1['dropdown_options']);
                    foreach ($optionsArray as $op => $v) {
                        $OpArray                              = explode('|', $v);
                        $finalOptionsArray[$OpArray[0]]       = $OpArray[1];
                        $data['persons_infromed'][$op]['label'] = $OpArray[1];
                        $data['persons_infromed'][$op]['value'] = $OpArray[0];
                    }
                }
            }
        }

                    /*code by Ritesh rana*/

                    /*start main form data*/
                    $main_form = $aai_array['main_form'];
                    $mainEntryIncident = json_decode($main_form['form_json_data'], true);
                    if (isset($mainEntryIncident) && !empty($mainEntryIncident)) {
                        $editMainEntryData = $mainEntryIncident;
                        foreach ($data['entry_form_data'] as $key => $value) {
                            if (isset($value['name'])) {
                                $data['entry_form_data'][$key]['value'] = str_replace("\'", "'", $editMainEntryData[$value['name']]);
                            }
                        }
                        $data['entry_form_id'] = $editMainEntryData['entry_form_id'];
                        $data['reporting_user']        = $editMainEntryData['reporting_user'];
                    }
                    /*end main form data*/

                    $emailMatch = '(email LIKE "%_@__%.__%")';
                    $nfcUsers   = $this->common_model->get_records(LOGIN, array('login_id as user_id', 'firstname as first_name', 'lastname as last_name', 'email'), '', '', '', '', '', '', '', '', '', $emailMatch);

                    function appendNFCType5($n)
                    {
                        $n['user_type']     = 'N';
                        $n['job_title']     = '';
                        $n['work_location'] = '';
                        return $n;
                    }

                    $nfcUsers    = array_map("appendNFCType5", $nfcUsers);
                    $bambooUsers = $this->common_model->get_records(BAMBOOHR_USERS, array('user_id', 'first_name', 'last_name', 'email', 'job_title', 'work_location'), '', '', '', '', '', '', '', '', '', $emailMatch);

                    function appendBambooType5($n)
                    {
                        $n['user_type'] = 'B';
                        return $n;
                    }
                    $bambooUsers            = array_map("appendBambooType5", $bambooUsers);
                    $data['bambooNfcUsers'] = array_merge($bambooUsers, $nfcUsers);
                    $data['loggedInUser']   = $this->session->userdata['LOGGED_IN'];
                    $table                  = AAI_MAIN . ' as a';
                    $join_tables            = array(YP_DETAILS . ' as yp' => 'a.yp_id=yp.yp_id', CARE_HOME . ' as c' => 'c.care_home_id=yp.care_home');
                    $dateQuery              = 'date(a.created_date) >= CURDATE() - INTERVAL 14 DAY ';
                    $data['YPIncidentData'] = $this->common_model->get_records($table, '', $join_tables, '', array('a.yp_id' => $incidentData['yp_id'], 'a.incident_id !=' => $incidentId), '', '', '', '', '', '', $dateQuery);
                    /* entry form end */

                    /* code by Ritesh Rana */
                    /* L1 start */
                    $l1Form = $allForms[AAI_L1_FORM_ID];
                    if (!empty($l1Form)) {
                        $data['l1_form_data'] = json_decode($l1Form['form_json_data'], true);
                        foreach ($data['l1_form_data'] as $key => $value) {
                            if (isset($value['type']) && $value['type'] == 'select') {
                                foreach ($data['dropdown_data'] as $key1 => $value1) {
                                    if ($value['description'] == $value1['prefix']) {
                                        if ($value1['total_options'] > 0) {
                                            $optionsArray = explode(';', $value1['dropdown_options']);
                                            foreach ($optionsArray as $op => $v) {
                                                $OpArray                                            = explode('|', $v);
                                                $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                                $data['l1_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                                $data['l1_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                            }
                                        }
                                    }
                                }
                            }
                        }

                    if (isset($aai_array['L1']) && !empty($aai_array['L1'])) {
                            $L1form = $aai_array['L1'];
                            $editl1Data = json_decode($L1form['form_json_data'], true);

                    $data['l1_data'] = $editl1Data;
                foreach ($data['l1_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l1_form_data'][$key]['value'] = str_replace("\'", "'", $editl1Data[$value['name']]);
                    }
                }
                $data['l1_total_duration']  = $editl1Data['l1_total_duration'];
                $data['l1_form_id']  = $editl1Data['l1_form_id'];
                $data['l1reference_number'] = $editl1Data['l1_reference_number'];
            }else{
                $data['l1reference_number'] = '';
            }
        }
                    /* code end ritesh Rana */
                    /* L1 end */

                    /*cide by ritesh Rana*/
                    /* L2 start */
                    $l2Form = $allForms[AAI_L2NL3_FORM_ID];
                    if (!empty($l2Form)) {
                        $data['l2_form_data'] = json_decode($l2Form['form_json_data'], true);
                        foreach ($data['l2_form_data'] as $key => $value) {
                            if (isset($value['type']) && $value['type'] == 'select') {
                                foreach ($data['dropdown_data'] as $key1 => $value1) {
                                    if ($value['description'] == $value1['prefix']) {
                                        if ($value1['total_options'] > 0) {
                                            $optionsArray = explode(';', $value1['dropdown_options']);
                                            foreach ($optionsArray as $op => $v) {
                                                $OpArray                                            = explode('|', $v);
                                                $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                                $data['l2_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                                $data['l2_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                            }
                                        }
                                    }
                                }
                            }
                        }

            if (isset($aai_array['L2_L3']) && !empty($aai_array['L2_L3'])) {
                $L2form = $aai_array['L2_L3'];
                $editl2Data = json_decode($L2form['form_json_data'], true);
                $data['l2_data'] = $editl2Data;
                foreach ($data['l2_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l2_form_data'][$key]['value'] = str_replace("\'", "'", $editl2Data[$value['name']]);
                    }
                }
                $data['l2_total_duration']  = $editl2Data['l2_total_duration'];


                  /* start code by ritesh Rana */
        /* start Sequence of events */
            $data['l2_archive_id']= $L2form['archive_id'];
            $sortfield = 'time';
            $sortby    = 'asc';
            $table = AAI_L2_L3_SEQUENCE_ARCHIVE . ' as sq';
            $where = array("sq.archive_id" => $L2form['archive_id']);
            $fields = array("sq.*");
            $l2seqresult = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where);
            $data['l2sequence_events'] = $l2seqresult;

            $sortfield = 'time';
            $sortby    = 'asc';
            $table = AAI_L2_L3_SEQUENCE_ARCHIVE . ' as sq';
            $sqnm='S'.$squ_num;
            $where = array("sq.archive_id" => $L2form['archive_id']);
            $fields = array("sq.*,wi.l2Who_was_involved_in_incident");
            $joinTables            = array(AAI_L2_WHO_WAS_INVOLVED_ARCHIVE . ' as wi' => 'wi.l2_arcive_sequence_event_id = sq.l2_l3_sequence_archive_id');
            $l2seqresult2 = $this->common_model->get_records($table, $fields, $joinTables, 'left', '', '', '', '', $sortfield, $sortby, '', $where);
            $seqwise_l2_l3_sequence_events=array();
            if(count($l2seqresult2)>0)
            {
                    foreach($l2seqresult2 as $sqdata)
                        {
                            $seqwise_l2_l3_sequence_events[$sqdata['l2_l3_sequence_archive_id']][]=$sqdata['l2Who_was_involved_in_incident'];
                        }

            }
            $data['seqwise_l2_l3_sequence_events_sign_off'] = $seqwise_l2_l3_sequence_events;
            

            /* start Medical Observations */
            $table = AAI_L2_L3_MEDOBS_ARCHIVE . ' as mo';
            $where = array("mo.archive_id" => $L2form['archive_id']);
            $fields = array("mo.*");
            $l2seqresult_mo = $this->common_model->get_records($table, $fields, '', '', $where);
            $data['l2medical_observations'] = $l2seqresult_mo;
            //pr($data['l2medical_observations']);exit;
        /* End Medical Observations */
            }
        $data['l2reference_number'] = $editl2Data['l2_l3_reference_number'];
        }else{
            $data['l2reference_number'] = '';
        }
                    /* L2 end */

        /* L4 form start */
        $l4Form = $allForms[AAI_L4_FORM_ID];
        if (!empty($l4Form)) {
            $data['l4_form_data'] = json_decode($l4Form['form_json_data'], true);
            foreach ($data['l4_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l4_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l4_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }
            if (isset($aai_array['L4']) && !empty($aai_array['L4'])) {
               $l4_data = $aai_array['L4'];
                $editl4Data = json_decode($l4_data['form_json_data'], true);
                $data['l4_data'] = $editl4Data;
                foreach ($data['l4_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l4_form_data'][$key]['value'] = str_replace("\'", "'", $editl4Data[$value['name']]);
                    }
                }
                $data['l4calculate_notification_worker'] = $editl4Data['calculate_notification_worker'];
                $data['l4calculate_notification_missing'] = $editl4Data['calculate_notification_missing'];
                $data['l4reference_number'] = $editl4Data['l4_reference_number'];
                $data['l4_total_duration'] = $editl4Data['l4_total_duration'];
                $data['l4_report_compiler']              = $editl4Data['l4_report_compiler'];
                
                $table = AAI_L4_PERSON_INFORMED_MISSING_ARCHIVE . ' as mo';
                $where = array("mo.archive_id" => $l4_data['archive_id']);
                $fields = array("mo.*");
                $data['l4missing_yp'] = $this->common_model->get_records($table, $fields, '', '', $where);
                
                $table = AAI_L4_PERSON_INFORMED_RETURN_ARCHIVE . ' as mo';
                $where = array("mo.archive_id" => $l4_data['archive_id']);
                $fields = array("mo.*");
                $data['l4return_data'] = $this->common_model->get_records($table, $fields, '', '', $where);
                 
                $table = AAI_L4_SEQUENCE_EVENT_ARCHIVE . ' as mo';
                $where = array("mo.archive_id" => $l4_data['archive_id']);
                $fields = array("mo.*");
                $data['l4sequence_data'] = $this->common_model->get_records($table, $fields, '', '', $where,'','','','date,time','ASC');
            }
        }
        
        /* L4 form end */
                     /* L5 form start */
        $l5Form = $allForms[AAI_L5_FORM_ID];
        if (!empty($l5Form)) {
            $data['l5_form_data'] = json_decode($l5Form['form_json_data'], true);
            foreach ($data['l5_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l5_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l5_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }

            if (isset($aai_array['L5']) && !empty($aai_array['L5'])) {
                 $l5_data = $aai_array['L5'];
                $editl5Data = json_decode($l5_data['form_json_data'], true);
                foreach ($data['l5_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l5_form_data'][$key]['value'] = str_replace("\'", "'", $editl5Data[$value['name']]);
                    }
                }
                $data['l5reference_number'] = $editl5Data['l5_reference_number'];
                $data['l5_body_map']        = $editl5Data;
            }else{
                $data['l5reference_number'] = 'L5' . substr($yp_details[0]['yp_initials'], 0, 3) . $dateOfIncident . $refIncidentId;
            }
        }
        /* L5 end */
                    /*code by Ritesh rana*/
                    /* L6 start */
                    $l6Form = $allForms[AAI_L6_FORM_ID];
                    if (!empty($l6Form)) {
                        $data['l6_form_data'] = json_decode($l6Form['form_json_data'], true);
                        foreach ($data['l6_form_data'] as $key => $value) {
                            if (isset($value['type']) && $value['type'] == 'select') {
                                foreach ($data['dropdown_data'] as $key1 => $value1) {
                                    if ($value['description'] == $value1['prefix']) {
                                        if ($value1['total_options'] > 0) {
                                            $optionsArray = explode(';', $value1['dropdown_options']);
                                            foreach ($optionsArray as $op => $v) {
                                                $OpArray                                            = explode('|', $v);
                                                $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                                $data['l6_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                                $data['l6_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        
                if (isset($aai_array['L6']) && !empty($aai_array['L6'])) {
                $l6_data = $aai_array['L6'];
                $editl6Data = json_decode($l6_data['form_json_data'], true);
                $data['l6_data'] = $editl6Data;
                //pr($editl6Data);exit;
                foreach ($data['l6_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l6_form_data'][$key]['value'] = str_replace("\'", "'", $editl6Data[$value['name']]);
                    }
                }
                $data['l6reference_number'] = $editl6Data['l6_reference_number'];
            }else{
                 $data['l6reference_number'] = 'L6' . substr($yp_details[0]['yp_initials'], 0, 3) . $dateOfIncident . $refIncidentId;
            }
        /* start code by ritesh Rana */
        /* start Sequence of events */
            $sortfield = 'date,time';
            $sortby    = 'asc';
            $table = AAI_L6_SEQUENCE_ARCHIVE . ' as sq';
            $where = array("sq.archive_id" => $l6_data['archive_id']);
            $fields = array("sq.*");
            $l6seqresult = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where);
            $data['l6sequence_data'] = $l6seqresult;
        }
                    /*code end by ritesh rana*/

                    /* L7 start */
                    /*code by Ritesh Rana*/
        $l7Form = $allForms[AAI_L7_FORM_ID];
        if (!empty($l7Form)) {
            $data['l7_form_data'] = json_decode($l7Form['form_json_data'], true);
            foreach ($data['l7_form_data'] as $key => $value) {
                if (isset($value['type']) && $value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l7_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l7_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }

       
            

            if (isset($aai_array['L7']) && !empty($aai_array['L7'])) {
                $l7_data = $aai_array['L7'];
                $editl7Data = json_decode($l7_data['form_json_data'], true);
                foreach ($data['l7_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l7_form_data'][$key]['value'] = str_replace("\'", "'", $editl7Data[$value['name']]);
                    }
                }
            $data['l7reference_number'] = $editl7Data['l7_reference_number'];

        /* start Sequence of events */
            $sortfield = 'l7date_safeguarding,l7time_safeguard';
            $sortby    = 'asc';
            $table = AAI_L7_SAFEGUARDING_ARCHIVE . ' as sq';
            $where = array("sq.l7archive_id" => $l7_data['archive_id']);
            $fields = array("sq.*");
            $l7seqresult = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where);
            $data['l7sequence_data'] = $l7seqresult;

        /* end Sequence of events */
        }else{
            $data['l7reference_number'] = 'L7' . substr($yp_details[0]['yp_initials'], 0, 3) . $dateOfIncident . $refIncidentId;
        }
        }
                    /*end*/
                    /* L7 end */
					
					/* L8 form start */
                    $l8Form = $allForms[AAI_L8_FORM_ID];
                    if (!empty($l8Form)) {
                        $data['l8_form_data'] = json_decode($l8Form['form_json_data'], true);
                        foreach ($data['l8_form_data'] as $key => $value) {
                            if (isset($value['type']) && $value['type'] == 'select') {
                                foreach ($data['dropdown_data'] as $key1 => $value1) {
                                    if ($value['description'] == $value1['prefix']) {
                                        if ($value1['total_options'] > 0) {
                                            $optionsArray = explode(';', $value1['dropdown_options']);
                                            foreach ($optionsArray as $op => $v) {
                                                $OpArray                                            = explode('|', $v);
                                                $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                                $data['l8_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                                $data['l8_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                            }
                                        }
                                    }
                                }
                            }
                        }


            if (isset($aai_array['L8']) && !empty($aai_array['L8'])) {
                $l8_data = $aai_array['L8'];
                $editl8Data = json_decode($l8_data['form_json_data'], true);
                foreach ($data['l8_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l8_form_data'][$key]['value'] = str_replace("\'", "'", $editl8Data[$value['name']]);
                    }
                }
            $data['l8reference_number'] = $editl8Data['l8_reference_number'];
        }
                    }

                    /* L8 end */
					
					/* L9 form start */
                    $l9Form = $allForms[AAI_L9_FORM_ID];
                    if (!empty($l9Form)) {
                        $data['l9_form_data'] = json_decode($l9Form['form_json_data'], true);

                        foreach ($data['l9_form_data'] as $key => $value) {
                            if (isset($value['type']) && $value['type'] == 'select') {
                                foreach ($data['dropdown_data'] as $key1 => $value1) {
                                    if ($value['description'] == $value1['prefix']) {
                                        if ($value1['total_options'] > 0) {
                                            $optionsArray = explode(';', $value1['dropdown_options']);
                                            foreach ($optionsArray as $op => $v) {
                                                $OpArray                                            = explode('|', $v);
                                                $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                                $data['l9_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                                $data['l9_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                            }
                                        }
                                    }
                                }
                            }
                        }
            if (isset($aai_array['L9']) && !empty($aai_array['L9'])) {
                $l9_data = $aai_array['L9'];
                $editl9Data = json_decode($l9_data['form_json_data'], true);
                foreach ($data['l9_form_data'] as $key => $value) {
                    if (isset($value['name'])) {
                        $data['l9_form_data'][$key]['value'] = str_replace("\'", "'", $editl9Data[$value['name']]);
                    }
                }
            $data['l9reference_number'] = $editl9Data['l9_reference_number'];
            }
        }
                    /* L9 end */
                    /*start Review*/
                    $reviewForm = $allForms[REVIEW];
                    if (!empty($reviewForm)) {
                        $data['review_form_data'] = json_decode($reviewForm['form_json_data'], true);
                        foreach ($data['review_form_data'] as $key => $value) {
                            if (isset($value['type']) && $value['type'] == 'select') {
                                foreach ($data['dropdown_data'] as $key1 => $value1) {
                                    if ($value['description'] == $value1['prefix']) {
                                        if ($value1['total_options'] > 0) {
                                            $optionsArray = explode(';', $value1['dropdown_options']);
                                            foreach ($optionsArray as $op => $v) {
                                                $OpArray                                                = explode('|', $v);
                                                $finalOptionsArray[$OpArray[0]]                         = $OpArray[1];
                                                $data['review_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                                $data['review_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if (isset($incidentData['review_form_data']) && !empty($incidentData['review_form_data'])) {
                            $editReviewData = json_decode($incidentData['review_form_data'], true);
                            foreach ($editReviewData as $key => $value) {
                                if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                                    $editReviewData[$value['name']] = $value;
                                }
                            }

                            foreach ($data['review_form_data'] as $key => $value) {
                                if (isset($value['name']) && $value['name'] == $editReviewData[$value['name']]['name']) {
                                    $data['review_form_data'][$key]['value'] = str_replace("\'", "'", $editReviewData[$value['name']]['value']);
                                }
                            }

                        }
                    }
                    /* Review end */

                    /* Manager review start */
                    $ManagersReviewForm = $allForms[MANAGER_REVIEW];
                    if (!empty($ManagersReviewForm)) {
                        $data['manager_review_form_data'] = json_decode($ManagersReviewForm['form_json_data'], true);
                        foreach ($data['manager_review_form_data'] as $key => $value) {
                            if (isset($value['type']) && $value['type'] == 'select') {
                                foreach ($data['dropdown_data'] as $key1 => $value1) {
                                    if ($value['description'] == $value1['prefix']) {
                                        if ($value1['total_options'] > 0) {
                                            $optionsArray = explode(';', $value1['dropdown_options']);
                                            foreach ($optionsArray as $op => $v) {
                                                $OpArray                                                        = explode('|', $v);
                                                $finalOptionsArray[$OpArray[0]]                                 = $OpArray[1];
                                                $data['manager_review_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                                $data['manager_review_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if (isset($incidentData['manager_review_form_data']) && !empty($incidentData['manager_review_form_data'])) {
                            $editManagerReviewData = json_decode($incidentData['manager_review_form_data'], true);
                            foreach ($editManagerReviewData as $key => $value) {
                                if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                                    $editManagerReviewData[$value['name']] = $value;
                                }
                            }

                            foreach ($data['manager_review_form_data'] as $key => $value) {
                                if (isset($value['name']) && $value['name'] == $editManagerReviewData[$value['name']]['name']) {
                                    $data['manager_review_form_data'][$key]['value'] = str_replace("\'", "'", $editManagerReviewData[$value['name']]['value']);
                                }
                            }
                        }
                    }
                    /* Manager Review end */

                    //get signoff data

                    $array_data = explode(',', $incidentData['signoff_data']);

                    $table                = AAI_SIGNOFF . ' as aai';
                    $where                = array("l.is_delete" => "0");
                    $where_in             = array("aai.aai_signoff_id" => $array_data);
                    $fields               = array("aai.aai_signoff_id,aai.created_by,aai.created_date,aai.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
                    $join_tables          = array(LOGIN . ' as l' => 'l.login_id=aai.created_by');
                    $group_by             = array('aai.created_by');
                    $data['signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where, '', $where_in);

                    $data['YP_details']          = $this->common_model->get_records(YP_DETAILS, array("yp_id,care_home,yp_fname,yp_lname,DATE_FORMAT(date_of_birth, '%d/%m/%Y') as date_of_birth"), '', '', array("yp_id" => $incidentData['yp_id']));
                    $data['crnt_view']           = $this->module;
                    $data['editMode']            = $formNumber;
                    $data['ypId']                = $incidentData['yp_id'];
                    $data['signoff_approval_id'] = $incidentData['signoff_approval_id'];
                    $data['key_data']            = $email;
                    $data['isCareIncident']      = $incidentData['is_care_incident'];
                    $data['incidentId']          = $incidentId;
                    $data['incidentData']        = $incidentData;
                    $data['user_signoff']        = 1;
                   


                    if(!empty($pdfdata) && $pdfdata == "pdf"){
                         //$data['footerJs'][0]         = base_url('uploads/custom/js/AAI/AAIPdf.js');
                        $PDFInformation['yp_details'] = $data['YP_details'][0];
                        $PDFInformation['edit_data'] = $incidentData['modified_date'];
                        
                        $PDFHeaderHTML  = $this->load->view('aai_pdfHeader', $PDFInformation,true);
                        $PDFFooterHTML  = $this->load->view('aai_pdfFooter', $PDFInformation,true);
                        
                              //Set Header Footer and Content For PDF
                        $this->m_pdf->pdf->mPDF('utf-8','A4','','','10','10','45','25');
                
                        $this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
                        $this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);                    
                        $data['main_content'] = '/aai_pdf_page';
                        $html = $this->parser->parse('layouts/AAIDataTemplate', $data);
                        echo $html;exit;
                        /*remove*/
                        $this->m_pdf->pdf->WriteHTML($html);
                        //Store PDF in IBP Folder
                        $this->m_pdf->pdf->Output($pdfFileName, "D");

                    }else{
                    $data['footerJs'][0]         = base_url('uploads/custom/js/AAI/AAI_sign_off.js');
                    $data['footerJs'][1]    = base_url('uploads/custom/js/jquery.blockUI.js');
                    $data['main_content']        = '/approval_view_aai.php';
                    $this->parser->parse('layouts/DefaultTemplate', $data);
                    }

                } else {
                    $msg = lang('link_expired');
                    $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>")
                    ;
                    $this->load->view('successfully_message');
                }
            } else {
                //show_404 ();
                $msg = $this->lang->line('already_aai_review');
                $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>");
                $this->load->view('successfully_message');
            }
        } else {
            show_404();
        }
    }

    /*
    @Author : Ritesh Rana
    @Desc   : signoff review functionality
    @Input    :
    @Output   :
    @Date   : 06/02/2019
     */

    public function signoff_review_data($yp_id, $signoff_approval_id, $email = 0)
    {
        if (!empty($yp_id) && !empty($signoff_approval_id) && !empty($email)) {
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            /*get AAI approval sign off data */
            $match              = array('yp_id' => $yp_id, 'key_data' => $email, 'status' => 'inactive', ' signoff_approval_id' => $signoff_approval_id);
            $check_signoff_data = $this->common_model->get_records(AAI_SIGNOFF_APPROVAL, array("*"), '', '', $match);
            /*check expiry URL */
            if (!empty($check_signoff_data)) {
                $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['signoff_created_date'] . REPORT_EXPAIRED_DAYS));
                if (strtotime(datetimeformat()) <= strtotime($expairedDate)) {
                    /*user approval sign off */
                    $u_data['status']        = 'active';
                    $u_data['signoff_modified_date'] = datetimeformat();
                    $success                 = $this->common_model->update(AAI_SIGNOFF_APPROVAL, $u_data, array('yp_id' => $yp_id, 'key_data' => $email, 'signoff_approval_id' => $signoff_approval_id));
                    if ($success) {

                        $msg = $this->lang->line('successfully_aai_review');
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
                $msg = $this->lang->line('already_aai_review');
                $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>");

            }
        } else {
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>");

        }
        $this->load->view('successfully_message');
    }

/*
      @Author : Ritesh Rana
      @Desc   : PDF data
      @Input  :
      @Output :
      @Date   : 11/02/2019
     */
    public function DownloadPdf($ypid,$signoff_approval_id) {
         
    if(is_numeric($id) && is_numeric($signoff_id))
       {
        
          $match = array('yp_id'=> $id,'ibp_signoff_details_id'=>$signoff_id,'status'=>'inactive');
          $check_signoff_data = $this->common_model->get_records(NFC_IBP_SIGNOFF_DETAILS,array("form_json_data,created_date"), '', '', $match);
            if(!empty($check_signoff_data)){
         $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'].REPORT_EXPAIRED_DAYS));
          if(strtotime(datetimeformat()) <= strtotime($expairedDate))
          {
          $data['form_data'] = json_decode($check_signoff_data[0]['form_json_data'], TRUE);

              //get YP information
          $fields = array("care_home,yp_fname,yp_lname,DATE_FORMAT(date_of_birth, '%d/%m/%Y') as date_of_birth,yp_id");
          $data['YP_details'] = YpDetails($id,$fields);

          if(empty($data['YP_details']))
          {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$id);
          }
          //get ibp yp data
          
          $match = array('yp_id'=> $id,'is_previous_version'=>1);
          $data['ibp_edit_data'] = $this->common_model->get_records(NFC_INDIVIDUAL_BEHAVIOUR_PLAN,array("*"), '', '', $match);
        
        $table = NFC_APPROVAL_INDIVIDUAL_BEHAVIOUR_PLAN_SIGNOFF.' as ibp';
        $where = array("l.is_delete"=> "0","ibp.yp_id" => $id,"ibp.is_delete"=> "0");
        $fields = array("ibp.created_by,ibp.created_date,ibp.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id=ibp.created_by');
        $group_by = array('created_by');
        $data['ibp_signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);
          //get ibp old yp data
          $data['signoff_id']= $signoff_id;
          $data['key_data']= $email;
          $data['ypid'] = $id;
          $data['footerJs'][0] = base_url('uploads/custom/js/ibp/ibp.js');
          $data['crnt_view'] = $this->viewname;
          $data['main_content'] = '/signoff_view';
          $pdfFileName = "ibp.pdf";

            $PDFInformation['yp_details'] = $data['YP_details'][0];
            $PDFInformation['edit_data'] = $data['edit_data'][0]['modified_date'];
            $PDFInformation['edit_date'] = $data['ibp_edit_data'][0]['modified_date'];

            $PDFHeaderHTML  = $this->load->view('ibp_pdfHeader', $PDFInformation,true);
            $PDFFooterHTML  = $this->load->view('ibp_pdfFooter', $PDFInformation,true);
            
                  //Set Header Footer and Content For PDF
            $this->m_pdf->pdf->mPDF('utf-8','A4','','','10','10','45','25');
    
            $this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
            $this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);                    
            $data['main_content'] = '/ibp_pdf';
            $html = $this->parser->parse('layouts/PdfDataTemplate', $data);
            
               /*remove*/
            $this->m_pdf->pdf->WriteHTML($html);
            //Store PDF in IBP Folder
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
           
           $msg = $this->lang->line('already_ibp_review');
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
    @Desc   : old record update for user signoff functionality
    @Input    :
    @Output   :
    @Date   : 06/02/2019
     */

    public function updateSignoffData($incident_id)
    {
        $archive = array('is_delete' => 1);
        $where   = array('incident_id' => $incident_id);
        $this->common_model->update(AAI_SIGNOFF, $archive, $where);
        return true;
    }


    /*
      @Author : Ritesh Rana
      @Desc   : Read more
      @Input  : incident_id
      @Output :
      @Date   : 26/02/2019
     */

    function readmore($incident_id, $reference_number, $field) {
        $incidentData = $this->common_model->get_records(AAI_LIST_MAIN, $field, '', '', array('incident_id' => $incident_id,'reference_number' => $reference_number));
        $data['field_val'] = $incidentData[0]['description'];
        $this->load->view($this->viewname . '/readmore', $data);
    }

}
