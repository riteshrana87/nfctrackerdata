<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ArchiveCarehome extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->viewname = $this->router->fetch_class ();
        $this->method   = $this->router->fetch_method();
    }
    /*
      @Author : Ritesh Rana
      @Desc   : ArchiveCareHome Index Page
      @Input 	:
      @Output	:
      @Date   : 13-09-2018
     */

 public function index($care_home_id, $page = 0) {
        $searchtext = $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $yp_list_type = $this->input->post('yp_list_type');
        $data['yp_list_type'] = !empty($yp_list_type) ? $yp_list_type : 'display-table';
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('archive_carehome');
        }
        $searchsort_session = $this->session->userdata('archive_carehome');
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
        $config['base_url'] = base_url() . $this->viewname . '/index/' . $care_home_id;
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }

        $table = PAST_CARE_HOME_INFO . ' as pc';
        $where = array("pc.past_carehome" => $care_home_id, "ch.status" => "'active'", "ch.is_delete" => "'0'", "pc.is_delete" => "'0'");
        $fields = array("pc.id,,yp.yp_id, CONCAT(`yp_fname`,' ', `yp_lname`) as name,yp.yp_fname,yp.yp_lname,yp.yp_initials,yp.gender,yp.date_of_birth,yp.staffing_ratio,yp.profile_img,yp.age,ch.care_home_name,ch.care_home_id,pc.current_carehome,pc.past_carehome,pc.move_date,pc.enter_date,pc.created_date");
        $join_tables = array(YP_DETAILS . ' as yp' => 'yp.yp_id = pc.yp_id',CARE_HOME . ' as ch' => 'ch.care_home_id = pc.past_carehome');
        if (!empty($searchtext)) {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = "";
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
                                OR ch.care_home_name LIKE "%' . $searchtext . '%"
                            )
                            
                            AND yp.is_deleted = "0"
                            AND yp.is_archive = "0"
                            AND pc.past_carehome ="'.$care_home_id.'"
                            AND ch.status = "active"
                            AND ch.is_delete ="0"
                            AND ch.is_delete ="0"
                            ';
            
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1');
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
			$config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
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

        $this->session->set_userdata('archive_carehome', $sortsearchpage_data);
        $data['care_home_id'] = $care_home_id;
        setActiveSession('archive_carehome'); // set current Session active
        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/archivecarehome/archivecarehome.js');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/ajaxlist', $data);
        } else {
            $data['main_content'] = '/youngperson';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
    }

   
    /*
      @Author : Ritesh Rana
      @Desc   : YP view profile page
      @Input 	: 
      @Output	: 
      @Date   : 08/10/2018
     */
    public function view($id,$care_home_id,$past_care_home_id) {
		$table = CARE_HOME . ' as ch';
        $match = "ch.status = 'active' AND ch.is_delete=0 AND ch.care_home_id='".$care_home_id."'";
        $fields = array("ch.care_home_name,ch.care_home_id");
        $data['care_home_data'] = $this->common_model->get_records($table, $fields, '', '', $match);
       if (is_numeric($id)) {
            //Get Records From Login Table
            $data['footerJs'][0] = base_url('uploads/custom/js/archivecarehome/archivecarehome.js');
            $table = YP_DETAILS . ' as yp';
            $match = "yp.yp_id = " . $id;
            $fields = array("yp.*,pa.authority,pa.address_1,pa.address_2,pa.town,pa.county,pa.postcode,swd.social_worker_firstname,swd.social_worker_surname,swd.landline,swd.mobile,swd.email,swd.senior_social_worker_firstname,swd.senior_social_worker_surname,oyp.pen_portrait,swd.other");
            $join_tables = array(PLACING_AUTHORITY . ' as pa' => 'pa.yp_id= yp.yp_id', SOCIAL_WORKER_DETAILS . ' as swd' => 'swd.yp_id= yp.yp_id', OVERVIEW_OF_YOUNG_PERSON . ' as oyp' => 'oyp.yp_id= yp.yp_id');

            $data['editRecord'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
            $table = PARENT_CARER_INFORMATION . ' as pc';
            $match = array("pc.yp_id" => $id,'pc.is_deleted' => 0);
            $fields = array("pc.*");
            $data['parentData'] = $this->common_model->get_records($table, $fields, '', '', $match,'','','','parent_carer_id','desc');
            if (empty($data['editRecord'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect($this->viewname);
            }

            $data['past_care_home_id'] = $past_care_home_id;    

            $tableCare = PAST_CARE_HOME_INFO . ' as pc';
            $matchCare = "pc.yp_id = '" . $id . "' AND pc.is_delete= '0'";
            $fieldsCare = array("*");
            $data['care_homeRecord'] = $this->common_model->get_records($tableCare, $fieldsCare, '', '', $matchCare,'','','','pc.id','desc');
            $data['id'] = $id;
            $data['care_home_id'] = $care_home_id;
            $data['userType'] = getUserType();
            $data['crnt_view'] = $this->viewname;
            $data['main_content'] = '/ArchiveProfile';
            $data['header'] = array('menu_module' => 'YoungPerson');
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }
    
}
