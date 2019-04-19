<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ActivityLog extends CI_Controller {

    function __construct() {

        parent::__construct();
        if (checkPermission('ActivityLog', 'view') == false) {
            redirect('/Dashboard');
        }
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
    }

    /*
      @Author : Ritesh rana
      @Desc   : Registration Index Page
      @Input 	:
      @Output	:
      @Date   : 25/06/2017
     */

    public function index() {

        $searchtext = $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $yp_list_type = $this->input->post('yp_list_type');

        /* for filter data get staff name by nikunj ghelani on 21/8/2018  */

        $table = ACTIVITY_LOG . ' as a';
        $fields = array("a.user_id,CONCAT(`firstname`,' ', `lastname`) as user_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= a.user_id');
        $data['staffdata'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', 'a.user_id', '', '');

        /* for filter data get staff name by nikunj ghelani on 21/8/2018  */

        $table = ACTIVITY_LOG . ' as a';
        $fields = array("a.yp_id,CONCAT(`yp_fname`,' ', `yp_lname`) as yp_name");
        $join_tables = array(YP_DETAILS . ' as yp' => 'a.yp_id= yp.yp_id');
        $data['yp_name'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', 'a.yp_id', '', '');
        
        /* for filter data get Module name by nikunj ghelani on 21/8/2018 */
        $table = ACTIVITY_LOG . ' as a';
        $fields = array("a.module_name");
        $data['module_name'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', 'a.module_name', '', '');
        
        $data['yp_list_type'] = !empty($yp_list_type) ? $yp_list_type : 'display-table';
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('activity_log_session_data');
        }

        $searchsort_session = $this->session->userdata('activity_log_session_data');
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
                $sortfield = 'a.activity_id';
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
        $config['base_url'] = base_url() . $this->viewname . '/index';

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 3;
            $uri_segment = $this->uri->segment(3);
        }
        //Query
        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = ACTIVITY_LOG . ' as a';
        $fields = array("a.*, CONCAT(`yp_fname`,' ', `yp_lname`) as yp_name,CONCAT(`firstname`,' ', `lastname`) as user_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= a.user_id', YP_DETAILS . ' as yp' => 'a.yp_id= yp.yp_id');
        $search_array = array();
        $where = 'a.is_delete=0';
        $where_like = '';

        if (isset($_REQUEST)) {
            $data['staff_name'] = $this->input->post('staff_name');
            $data['search_yp_name'] = $this->input->post('yp_name');
            $data['search_module_name'] = $this->input->post('module_name');
            $data['filter_search_start_date'] = $this->input->post('search_start_date');
            $data['filter_search_end_date'] = $this->input->post('search_end_date');
            if ($this->input->post('staff_name')) {
                $where .= " AND a.user_id = " . $this->input->post('staff_name');
            }
            if ($this->input->post('yp_name')) {
                $where .= " AND a.yp_id = " . $this->input->post('yp_name');
            }
            if ($this->input->post('module_name')) {
                $mname = $this->input->post('module_name');
                $where .= " AND a.module_name = '" . $this->input->post('module_name') . "'";
            }

            if ($this->input->post('search_start_date') && $this->input->post('search_end_date')) {
                $stdate = $this->input->post('search_start_date');
                $edate = $this->input->post('search_end_date');

                $finalstartdate = explode('/', $stdate);
                $finalenddate = explode('/', $edate);

                $search_start_date = $finalstartdate[2] . '-' . $finalstartdate[1] . '-' . $finalstartdate[0];
                $search_end_date = $finalenddate[2] . '-' . $finalenddate[1] . '-' . $finalenddate[0];

                $where .=" AND DATE_FORMAT(a.activity_date, '%Y-%m-%d') >=  '" . $search_start_date . "' and DATE_FORMAT(a.activity_date, '%Y-%m-%d') <='" . $search_end_date . "'";
            }
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', '', '', '', '', '', '', $where);

            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', '', '', '', '1', '', '', $where);
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', '', '', '', '', '', '', $where);

            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', '', '', '', '1');
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

        $this->session->set_userdata('activity_log_session_data', $sortsearchpage_data);
        setActiveSession('activity_log_session_data'); // set current Session active
        $data['header'] = array('menu_module' => 'ActivityLog');
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/AuditLog/AuditLog.js');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/ajaxlist', $data);
        } else {
            $data['main_content'] = '/list';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
    }

    /*
      @Author : ritesh rana
      @Desc   : yp insert data
      @Input 	:
      @Output	:
      @Date   : 27/06/2017
     */

    public function insertdata() {
        $postdata = $this->input->post();
        $checkAvalibility = "";
        $initialsID = $postdata['initials'];
        $table = YP_DETAILS . ' as yp';
        $match = "yp.is_deleted= '0' AND yp.yp_initials = '" . $initialsID . "'";
        $fields = array("yp.yp_id,yp.status");
        $duplicateEmail = $this->common_model->get_records($table, $fields, '', '', $match);
        $count = count($duplicateEmail);
        if (isset($duplicateEmail) && empty($duplicateEmail) && $count == 0) {
            //Current Login detail
            $main_user_data = $this->session->userdata('LOGGED_IN');

            if (!validateFormSecret()) {
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
                redirect('ActivityLog'); //Redirect On Listing page
            }
            $data['crnt_view'] = $this->viewname;
            $data = array(
                'yp_fname' => ucfirst($postdata['fname']),
                'yp_lname' => ucfirst($postdata['lname']),
                'yp_initials' => $postdata['initials'],
                'care_home' => $postdata['care_home'],
                'created_date' => datetimeformat(),
                'modified_date' => datetimeformat(),
                'created_by' => $main_user_data['ID'],
                'updated_by' => $main_user_data['ID'],
            );
            //Insert Record in Database
            if ($this->common_model->insert(YP_DETAILS, $data)) {
                $yp_id = $this->db->insert_id();
                $meddata = array(
                    'yp_id' => $yp_id,
                    'medical_number' => $this->common_model->medicalnumber(),
                    'created_by' => !empty($this->session->userdata('LOGGED_IN')['ID']) ? $this->session->userdata('LOGGED_IN')['ID'] : '',
                    'created_date' => datetimeformat()
                );
                $this->common_model->insert(MEDICAL_INFORMATION, $meddata);
                $msg = $this->lang->line('yp_add_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            } else {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
        } else {
            // error
            $msg = 'Entered InitialsID is already exist.';
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }
        redirect('ActivityLog');
    } 
}
