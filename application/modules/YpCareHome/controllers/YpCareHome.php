<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class YpCareHome extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (checkPermission('YoungPerson', 'view') == false) {
            redirect('/Dashboard');
        }
        $this->load->model('imageupload_model');
        $this->viewname = $this->router->fetch_class ();
        $this->method   = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Ritesh rana
      @Desc   : Registration Index Page
      @Input 	:
      @Output	: care home list
      @Date   : 25/06/2017
     */

public function index($page = 0) {
        $searchtext = $searchtextyp = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $searchtextyp = $this->input->post('searchtextyp');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $yp_list_type = $this->input->post('yp_list_type');
        $data['yp_list_type'] = !empty($yp_list_type) ? $yp_list_type : 'display-table';
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('yp_home_data');
        }

        $searchsort_session = $this->session->userdata('yp_home_data');
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
                $sortfield = 'yp_id';
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

        if (!empty($searchtextyp)) {
            $data['searchtextyp'] = $searchtextyp;
        } else {
            if (empty($allflag) && !empty($searchsort_session['searchtextyp'])) {
                $data['searchtextyp'] = $searchsort_session['searchtextyp'];
                $searchtextyp = $data['searchtextyp'];
            } else {
                $data['searchtextyp'] = '';
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
        
        $config['base_url'] = base_url() . $this->viewname;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 1;
            $uri_segment = $this->uri->segment(1);
        }
        
        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = YP_DETAILS . ' as yp';
        $where = array("yp.is_deleted" => "'0'", "yp.is_archive" => '0');
        $fields = array("yp.yp_id, CONCAT(`yp_fname`,' ', `yp_lname`) as name,yp.yp_fname,yp.yp_lname,yp.created_date,yp.yp_initials,yp.gender,yp.date_of_birth,yp.staffing_ratio,yp.profile_img,yp.age,ch.care_home_name");
        $join_tables = array(CARE_HOME . ' as ch' => 'ch.care_home_id= yp.care_home');
        if (!empty($searchtext)) {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
               $where_search = '
                            (
                                (
                                    CONCAT(`yp_fname`, \' \', `yp_lname`) LIKE "%' . $searchtext . '%" 
                                    OR yp.yp_fname LIKE "%' . $searchtext . '%" 
                                    OR yp.yp_lname LIKE "%' . $searchtext . '%"
                                )
                                OR yp.age LIKE "%' . $searchtext . '%" 
                                OR yp.gender LIKE "%' . $searchtext . '%" 
                                OR yp.staffing_ratio LIKE "%' . $searchtext . '%" 
                                OR yp.yp_initials LIKE "%' . $searchtext . '%" 
                                OR ch.care_home_name LIKE "%' . $searchtext . '%"
                            )
                            
                            AND yp.is_deleted = "0"
                            AND yp.is_archive = "0"
                            AND ch.status = "active"
                            AND ch.is_delete ="0"
                            ';

            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1');
       }elseif(!empty($searchtextyp)){     
$searchtextyp = html_entity_decode(trim(addslashes($searchtextyp)));
            $match = '';
               $where_search = '
                            (
                                (
                                    CONCAT(`yp_fname`, \' \', `yp_lname`) LIKE "%' . $searchtextyp . '%" 
                                    OR yp.yp_fname LIKE "%' . $searchtextyp . '%" 
                                    OR yp.yp_lname LIKE "%' . $searchtextyp . '%"
                                )
                                OR yp.age LIKE "%' . $searchtextyp . '%" 
                                OR yp.gender LIKE "%' . $searchtextyp . '%" 
                                OR yp.staffing_ratio LIKE "%' . $searchtextyp . '%" 
                                OR yp.yp_initials LIKE "%' . $searchtextyp . '%" 
                                OR ch.care_home_name LIKE "%' . $searchtextyp . '%"
                            )
                            
                            AND yp.is_deleted = "0"
                            AND yp.is_archive = "1"
                            AND ch.status = "active"
                            AND ch.is_delete ="0"
                            ';

            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);

            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1');
        } else  {
            
        $table = CARE_HOME . ' as ch';
        $where = array("ch.is_delete" => "'0'","ch.status" => "'active'");
        $fields = array("ch.care_home_name, ch.care_home_id,ch.profile_img");
        $data['care_home_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            $config['total_rows'] = "";
        }


        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
         if (!empty($searchtext)) {
        $sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uri_segment,
            'total_rows' => $config['total_rows']);
    }elseif(!empty($searchtextyp)){
        $sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'searchtext' => $data['searchtextyp'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uri_segment,
            'total_rows' => $config['total_rows']);
    }else{
        $sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uri_segment,
            'total_rows' => $config['total_rows']);
    }

        $this->session->set_userdata('yp_home_data', $sortsearchpage_data);
        setActiveSession(); // set current Session active
       $data['header'] = array('menu_module' => 'YoungPerson');
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/ypcarehome/ypcarehome.js');

        if ($this->input->post('result_type') == 'ajax') {
            if(!empty(!empty($searchtext))){
             $this->load->view($this->viewname . '/ajaxlist', $data);
            }else{
                $this->load->view($this->viewname . '/ajaxlistyp', $data);
            }
        } else {
            $data['main_content'] = '/ypcarehome';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
    }

}
