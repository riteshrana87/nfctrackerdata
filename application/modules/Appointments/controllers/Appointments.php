<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Appointments extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Niral Patel
      @Desc   : Appointments Index Page
      @Input  : yp id
      @Output :
      @Date   : 08/05/2018
     */

    public function index($ypid, $careHomeId = 0, $isArchive = 0) {
        /* for care to care data by ghelani nikunj on 18/9/2018 for care to  care data get with the all previous care home */
        if ($isArchive !== 0) {
            $temp = $this->common_model->get_records(PAST_CARE_HOME_INFO, array('move_date'), '', '', array("yp_id" => $ypid, "past_carehome" => $careHomeId));
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

        if (is_numeric($ypid)) {
            $match = array("yp_id" => $ypid);
            $fields = array("yp_id,care_home,yp_fname,yp_lname,date_of_birth");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson');
            }
            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $professional_name = $this->input->post('professional_name');
            $search_date = $this->input->post('search_date');
            $search_time = $this->input->post('search_time');
            $perpage = 10;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('planner_appointment_session_data');
            }
            $searchsort_session = $this->session->userdata('planner_appointment_session_data');
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
                    $sortfield = 'planner_id';
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
                $config['base_url'] = base_url() . $this->viewname . '/index/' . $ypid;
                if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 4;
                    $uri_segment = $this->uri->segment(4);
                }
            } else {
                $config['base_url'] = base_url() . $this->viewname . '/index/' . $ypid . '/' . $careHomeId . '/' . $isArchive;
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
            $table = PLANNER . ' as mc';
            $fields = array("c.care_home_name,mc.*");
            $join_tables = array(YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id', CARE_HOME . ' as c' => 'c.care_home_id= mc.care_home_id');
            if ($isArchive == 0) {
                $whereCond = 'mc.yp_id = ' . $ypid . ' AND mc.is_delete = "0" ';
                if (!empty($search_date)) {
                    $whereCond .= ' AND mc.appointment_date = "' . dateformat($search_date) . '"';
                }
                if (!empty($search_time)) {
                    $whereCond .= ' AND mc.appointment_time = "' . dbtimeformat($search_time) . '"';
                }
                if (!empty($searchtext)) {
                    
                } else {
                    $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $whereCond);
                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $whereCond, '', '', '1');
                }
            } else {
                $whereCond = 'mc.yp_id = ' . $ypid . ' AND mc.is_delete = "0" AND mc.care_home_id!=' . $data['YP_details'][0]['care_home'];
                $where_date = "mc.created_at BETWEEN  '" . $created_date . "' AND '" . $movedate . "'";
                if (!empty($searchtext)) {
                    
                } else {
                    $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], '', $sortfield, $sortby, '', '', '', '', '', '', '', $where_date);

                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $whereCond, '', '', '1');
                }
            }
            $data['ypid'] = $ypid;
            $data['is_archive_page'] = $isArchive;
            $data['careHomeId'] = $careHomeId;
            $this->ajax_pagination->initialize($config);
            $data['pagination'] = $this->ajax_pagination->create_links();
            $data['uri_segment'] = $uri_segment;
            $sortsearchpage_data = array(
                'sortfield' => $data['sortfield'],
                'sortby' => $data['sortby'],
                'searchtext' => $data['searchtext'],
                'perpage' => trim($data['perpage']),
                'uri_segment' => $uri_segment,
                'total_rows' => $config['total_rows'],
                'professional_name' => $professional_name,
                'search_date' => $search_date,
                'search_time' => $search_time);

            $this->session->set_userdata('planner_appointment_session_data', $sortsearchpage_data);
            setActiveSession('planner_appointment_session_data'); // set current Session active
            $data['header'] = array('menu_module' => 'Appointments');            
            $data['crnt_view'] = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/Appointments/Appointments.js');
            $data['header'] = array('menu_module' => 'YoungPerson');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/appointment_ajaxlist', $data);
            } else {
                $data['main_content'] = '/appointment';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Add plabber appointment data
      @Input  :
      @Output :
      @Date   : 16/05/2018
     */

    public function add_appointment($ypid) {
        if (is_numeric($ypid)) {
            $match = array("yp_id" => $ypid);
            /* ghelani nikunj on 21-9-018 here i changed * to field name */
            $fields = array("yp_fname,yp_lname,date_of_birth,care_home");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            if (empty($data['YP_details'])) {
                show_404();
            }
            $data['ypid'] = $ypid;
            $data['care_home_id'] = $data['YP_details'][0]['care_home'];
            $data['footerJs'][0] = base_url('uploads/custom/js/Appointments/Appointments.js');
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/add_appointment';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : edit appointment
      @Input    : mp id
      @Output   :
      @Date   : 16/05/2018
     */

    public function appointment_edit($id, $ypid) {
        if (is_numeric($id) && is_numeric($ypid)) {
            $match = array("yp_id" => $ypid);
            $fields = array("yp_fname,yp_lname,date_of_birth");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            //get planner data
            $table = PLANNER . ' as mc';
            $match = array('mc.planner_id' => $id);
            $fields = array("mc.*");
            $join_tables = array(YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id');
            $data['mp_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
            if (empty($data['mp_data'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson');
            }
            $url_data = base_url('Appointments/appointment_edit/' . $id . '/' . $ypid);
            $match = array('url_data' => $url_data);
            $data['check_edit_permission'] = $this->common_model->get_records(CHECK_EDIT_URL, '*', '', '', $match);
            if (count($data['check_edit_permission']) > 0) {
                $in_time = date('Y-m-d h:i:s', strtotime($data['check_edit_permission'][0]['datetime']));
                $currnt_time = date('Y-m-d h:i:s');
                if (strtotime($in_time) > strtotime($currnt_time)) {
                    $now = strtotime($in_time) - strtotime($currnt_time);
                } else {
                    $now = strtotime($currnt_time) - strtotime($in_time);
                }

                $secs = floor($now % 60);
                if ($secs >= 10) {
                    $data['footerJs'][0] = base_url('uploads/custom/js/Appointments/Appointments.js');
                    $data['main_content'] = '/add_appointment';
                    $data['edit'] = true;
                    $data['palnner_id'] = $id;
                    $data['ypid'] = $ypid;
                    $this->parser->parse('layouts/DefaultTemplate', $data);
                } else {
                    $msg = $this->lang->line('check_ap_user_update_data');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                    redirect('/' . $this->viewname . '/index/' . $ypid);
                }
            } else {
                $data['footerJs'][0] = base_url('uploads/custom/js/Appointments/Appointments.js');
                $data['main_content'] = '/add_appointment';
                $data['edit'] = true;
                $data['palnner_id'] = $id;
                $data['ypid'] = $ypid;
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Insert planner appointment data
      @Input  :
      @Output :
      @Date   : 16/05/2018
     */

    public function insert_appointment() {
        $postData = $this->input->post();
        if (!empty($postData['repeat'])) {
            $repeat = $postData['repeat'];
            //check weekday selected or not
            if (!isset($postData['weekday'])) {
                $postData['weekday'] = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
            }
            //get date range
            $postData['appointment_start_date'] = dateformat($postData['appointment_start_date']);
            $postData['appointment_end_date'] = dateformat($postData['appointment_end_date']);
            $dates = $this->createDateRangeArray($postData['appointment_start_date'], $postData['appointment_end_date'], $postData['weekday']);
            if (!empty($dates)) {
                $cnt = $new = 0;
                foreach ($dates as $date) {
                    $match = array("yp_id" => $postData['yp_id'], 'is_delete' => 0, 'appointment_date' => $date, 'appointment_time' => dbtimeformat($postData['repeat_appointment_time']));
                    $appointmentdata = $this->common_model->get_records(PLANNER, '', '', '', $match);
                    if (empty($appointmentdata)) {
                        $data = array(
                            'yp_id' => $postData['yp_id'],
                            'appointment_date' => $date,
                            'appointment_time' => dbtimeformat($postData['repeat_appointment_time']),
                            'comments' => $postData['comments'],
                            'care_home_id' => $postData['care_home_id'],
                            'appointment_type' => $postData['appointment_type'],
                            'is_repeat' => 1,
                            'created_at' => datetimeformat(),
                            'created_by' => $this->session->userdata['LOGGED_IN']['ID']
                        );
                        $id = $this->common_model->insert(PLANNER, $data);                        
                    } else {
                        $cnt++;
                    }
                }
            }

            if (!empty($new)) {
                //Insert log activity
                $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                    'module_name' => PLANNER_APP_MODULE,
                    'module_field_name' => '',
                    'type' => 1
                );
                log_activity($activity);
            }
            if (!empty($cnt)) {
                $msg = $this->lang->line('some_appointment_already_exist');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('/' . $this->viewname . '/index/' . $postData['yp_id']);
            } else {
                redirect('/' . $this->viewname . '/save_appointment/' . $postData['yp_id']);
            }
        } else {
            $match = array("yp_id" => $postData['yp_id'], 'is_delete' => 0, 'appointment_date' => dateformat($postData['appointment_date']), 'appointment_time' => dbtimeformat($postData['appointment_time']));
            $appointmentdata = $this->common_model->get_records(PLANNER, '', '', '', $match);

            if (empty($appointmentdata)) {
                $data = array(
                    'yp_id' => $postData['yp_id'],
                    'appointment_date' => dateformat($postData['appointment_date']),
                    'appointment_time' => dbtimeformat($postData['appointment_time']),
                    'comments' => $postData['comments'],
                    'care_home_id' => $postData['care_home_id'],
                    'appointment_type' => $postData['appointment_type'],
                    'created_at' => datetimeformat(),
                    'created_by' => $this->session->userdata['LOGGED_IN']['ID']
                );
                $id = $this->common_model->insert(PLANNER, $data);

                //Insert log activity
                $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                    'module_name' => PLANNER_APP_MODULE,
                    'module_field_name' => '',
                    'type' => 1
                );
                log_activity($activity);
                redirect('/' . $this->viewname . '/save_appointment/' . $postData['yp_id']);
            } else {
                $msg = $this->lang->line('appointment_event_already_exist');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('/' . $this->viewname . '/index/' . $postData['yp_id']);
            }
        }
    }

    //create date range
    function createDateRangeArray($strDateFrom, $strDateTo, $days, $format = "Y-m-d") {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.
        // could test validity of dates here but I'm already doing
        // that in the main script

        $strDateTo = date("Y-m-d", strtotime("$strDateTo +1 day"));
        $begin = new DateTime($strDateFrom);
        $end = new DateTime($strDateTo);

        $interval = new DateInterval('P1D'); // 1 Day
        $dateRange = new DatePeriod($begin, $interval, $end);

        $range = [];
        foreach ($dateRange as $date) {
            $date = $date->format($format);
            $day = date('l', strtotime($date));
            if (in_array($day, $days)) {
                $range[] = $date;
            }
        }

        return $range;
    }

    /*
      @Author : Niral Patel
      @Desc   : view appointment
      @Input    : mp id
      @Output   :
      @Date   : 08/09/2017
     */

    public function appointment_view($id, $ypid, $careHomeId = 0, $isArchive = 0) {
        $data['is_archive_page'] = $isArchive;
        $data['careHomeId'] = $careHomeId;
        if (is_numeric($id) && is_numeric($ypid)) {

            $match = array("yp_id" => $ypid);
            $fields = array("*");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

            $table = PLANNER . ' as mc';

            $match = array('mc.planner_id' => $id);
            $fields = array("mc.*");
            $join_tables = array(YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id');
            $data['mp_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
            if (empty($data['mp_data'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson');
            }
            $data['main_content'] = '/view_appointment';
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
      @Date   : 16/05/2018
     */

    public function readmore_appointment($id, $field) {
        if (is_numeric($id)) {
            $params['fields'] = [$field];
            $params['table'] = PLANNER;
            $params['match_and'] = 'planner_id=' . $id . '';
            $data['documents'] = $this->common_model->get_records_array($params);
            $data['field'] = $field;
            $this->load->view($this->viewname . '/readmore', $data);
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : update mi appointment data
      @Input  :
      @Output :
      @Date   : 08/09/2017
     */

    public function update_appointment() {

        $postData = $this->input->post();
        $match = array("yp_id" => $postData['yp_id'], 'planner_id !=' => $postData['planner_id'], 'is_delete' => 0, 'appointment_date' => dateformat($postData['appointment_date']), 'appointment_time' => dbtimeformat($postData['appointment_time']));
        $appointmentdata = $this->common_model->get_records(PLANNER, array('planner_id'), '', '', $match);

        //pr($appointmentdata); exit;
        if (empty($appointmentdata)) {
            $updatedata = array(
                'appointment_date' => dateformat($postData['appointment_date']),
                'appointment_time' => dbtimeformat($postData['appointment_time']),
                'appointment_type' => $postData['appointment_type'],
                'comments' => $postData['comments'],
                'is_repeat' => isset($postData['repeat']) ? 1 : 0,
                'modified_by' => !empty($this->session->userdata('LOGGED_IN')['ID']) ? $this->session->userdata('LOGGED_IN')['ID'] : '',
                'modified_date' => datetimeformat()
            );
            $id = $this->common_model->update(PLANNER, $updatedata, array('planner_id' => $postData['planner_id']));

            //Insert log activity
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                'module_name' => PLANNER_APP_MODULE,
                'module_field_name' => '',
                'type' => 2
            );
            log_activity($activity);
            $msg = $this->lang->line('update_appointment_event_sucessfully');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect('/' . $this->viewname . '/index/' . $postData['yp_id']);
        } else {
            $msg = $this->lang->line('appointment_event_already_exist');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('/' . $this->viewname . '/appointment_edit/' . $postData['planner_id'] . '/' . $postData['yp_id']);
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : save data appointment
      @Input  :
      @Output :
      @Date   : 20/07/2017
     */

    public function save_appointment($id) {
        if (is_numeric($id)) {
            $data = array(
                'header_data' => 'New Appointment / Event Added',
                'detail' => 'You have added a new Appointment. Please check your editing carefully.',
            );
            $match = array("yp_id" => $id);
            $fields = array("*");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson');
            }

            $data['yp_id'] = $id;
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/save_appointment_data';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }

    /*
      @Description: Function for delete data
      @Author: Niral Patel
      @Input: - Delete id
      @Output: - New list after record is deleted.
      @Date: 8/09/2016
     */

    public function ajax_delete_all() {
        $id = $this->input->post('single_remove_id');
        //get palnner data
        $table = PLANNER . ' as mc';
        $match = array('mc.planner_id' => $id);
        $fields = array("mc.yp_id");
        $join_tables = array(YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id');
        $data['mp_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);

        if (!empty($id)) {
            $whereImg = array('planner_id' => $id);
            $cdata['is_delete'] = 1;
            $this->common_model->update(PLANNER, $cdata, $whereImg);
            
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($data['mp_data'][0]['yp_id']) ? $data['mp_data'][0]['yp_id'] : '',
                'module_name' => PLANNER_APP_MODULE,
                'module_field_name' => '',
                'type' => 3
            );
            log_activity($activity);
            unset($id);
        }

        $array_data = $this->input->post('myarray');
        $delete_all_flag = $cnt = 0;
        for ($i = 0; $i < count($array_data); $i++) {
            $whereImg = array('planner_id' => $array_data[$i]);
            $cdata['is_delete'] = 1;
            $this->common_model->update(PLANNER, $cdata, $whereImg);
            $delete_all_flag = 1;
            $cnt++;
        }
        //pagingation
        $searchsortSession = $this->session->userdata('planner_appointment_session_data');
        if (!empty($searchsortSession['uri_segment']))
            $pagingid = $searchsortSession['uri_segment'];
        else
            $pagingid = 0;
        $perpage = !empty($searchsortSession['perpage']) ? $searchsortSession['perpage'] : '10';
        $total_rows = $searchsortSession['total_rows'];
        if ($delete_all_flag == 1) {
            $total_rows -= $cnt;
            $pagingid * $perpage;
            if ($pagingid * $perpage > $total_rows) {
                if ($total_rows % $perpage == 0) { // if all record delete
                    $pagingid -= $perpage;
                }
            }
        } else {
            if ($total_rows % $perpage == 1)
                $pagingid -= $perpage;
        }
        if ($pagingid < 0)
            $pagingid = 0;
        echo $pagingid;
    }

    public function DownloadAppointment($yp_id, $section = NULL) {
        $data = [];
        //get YP information
        $table = YP_DETAILS . ' as yp';
        $match = array("yp.yp_id" => $yp_id);
        $fields = array("yp.yp_fname,yp.yp_lname,yp.date_of_birth,pa.placing_authority_id,pa.authority,pa.address_1,pa.town,pa.county,pa.postcode,sd.mobile,sd.email,ch.care_home_name");
        $join_tables = array(PLACING_AUTHORITY . ' as pa' => 'pa.yp_id=yp.yp_id', SOCIAL_WORKER_DETAILS . ' as sd' => 'sd.yp_id=yp.yp_id', CARE_HOME . ' as ch' => 'ch.care_home_id = yp.care_home');
        $data['YP_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', '', '', '', '');
        $searchsort_session = $this->session->userdata('planner_appointment_session_data');
        $professional_name = $searchsort_session['professional_name'];
        $search_date = $searchsort_session['search_date'];
        $search_time = $searchsort_session['search_time'];
        //Sorting
        if (!empty($searchsort_session['sortfield'])) {
            $data['sortfield'] = $searchsort_session['sortfield'];
            $data['sortby'] = $searchsort_session['sortby'];
            $sortfield = $searchsort_session['sortfield'];
            $sortby = $searchsort_session['sortby'];
        } else {
            $sortfield = 'planner_id';
            $sortby = 'desc';
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        }
        //Search text
        if (empty($allflag) && !empty($searchsort_session['searchtext'])) {
            $data['searchtext'] = $searchsort_session['searchtext'];
            $searchtext = $data['searchtext'];
        } else {
            $data['searchtext'] = '';
        }
        //Query
        $table = PLANNER . ' as mc';
        $whereCond = 'mc.yp_id = ' . $yp_id . ' AND mc.is_delete = "0" ';
        $fields = array("mc.*");
        $join_tables = array(YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id');

        if (!empty($search_date)) {
            $whereCond .= ' AND mc.appointment_date = "' . dateformat($search_date) . '"';
        }
        if (!empty($search_time)) {
            $whereCond .= ' AND mc.appointment_time = "' . dbtimeformat($search_time) . '"';
        }
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $whereCond);
        }
        //get mac form
        $match = array('mp_form_id' => 1);
        $formsdata = $this->common_model->get_records(MP_FORM, '', '', '', $match);
        if (!empty($formsdata)) {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        $data['crnt_view'] = $this->viewname;
        $data['ypid'] = $yp_id;
        $data['main_content'] = '/appointment_pdf';
        $data['section'] = $section;
        $html = $this->parser->parse('layouts/PDFTemplate', $data);
        $pdfFileName = "mc" . $yp_id . ".pdf";
        $pdfFilePath = FCPATH . 'uploads/Appointments/';
        if (!is_dir(FCPATH . 'uploads/Appointments/')) {
            @mkdir(FCPATH . 'uploads/Appointments/', 0777, TRUE);
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

}
