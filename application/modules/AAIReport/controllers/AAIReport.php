<?php

defined('BASEPATH') or exit('No direct script access allowed');

class AAIReport extends CI_Controller
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
    @Author : Dhara Bhalala
    @Desc   : Main Listing Page
    @Date   : 17/12/2018
     */

    public function index()
    {
        //die('here');
        $ypId=1;
        if (is_numeric($ypId)) {
            //get YP information
            $fields             = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
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
            $table         = AAI_MAIN . ' as ai';
            $where         = array("ai.yp_id" => $ypId);
            $fields        = array("ai.incident_id,ai.reference_number,ai.yp_id,ai.is_care_incident,ai.care_home_id,ai.entry_form_data,ai.is_pi,CONCAT(`firstname`,' ', `lastname`) as create_name,ch.care_home_name");
            $join_tables   = array(LOGIN . ' as l' => 'l.login_id= ai.created_by', CARE_HOME . ' as ch' => 'ch.care_home_id = ai.care_home_id');
            if (!empty($searchtext)) {
                $searchtext   = html_entity_decode(trim(addslashes($searchtext)));
                $match        = array("ai.yp_id" => $ypId);
                $where_search = '('
                    . '(CONCAT(`firstname`, \' \', `lastname`) LIKE "%' . $searchtext . '%" OR '
                    . 'l.firstname LIKE "%' . $searchtext . '%" OR '
                    . 'l.lastname LIKE "%' . $searchtext . '%" OR '
                    . 'ai.reference_number LIKE "%' . $searchtext . '%" OR '
                    . 'ai.is_pi LIKE "%' . $searchtext . '%" OR '
                    . 'ai.incident_id LIKE "%' . $searchtext . '%" OR '
                    . 'ch.care_home_name LIKE "%' . $searchtext . '%")AND l.is_delete = "0")';

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
            $data['ypId']           = $ypId;
            $data['isCareIncident'] = 1;
            $data['header']         = array('menu_module' => 'AAIReport');
            
            if ($this->input->is_ajax_request()) {
                $this->load->view($this->viewname . '/ajaxlist', $data);
            } else {
                $data['main_content'] = '/Dashboard';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }
    
    public function Dashboard(){
        
        /*for Number of Incidents by Type (Level)*/
        /*x-axis: Number of incidents  y-axis: Type of Incidents*/
        /*get all the carehome name for filter data*/
        $table = CARE_HOME . ' as ch';
        $where = array("ch.is_delete" => "'0'","ch.status" => "'active'");
        $fields = array("ch.care_home_name, ch.care_home_id,ch.profile_img");
        $data['care_home_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
        /*get all the YP name for filter data*/
        $tableName = YP_DETAILS . ' as yp';
        $fields = array('yp.yp_id, CONCAT(yp.yp_fname," ",yp.yp_lname) as ypName');
        $whereCond = array('yp.status' => 'active', 'yp.is_deleted' => '0');
        $data['all_yp'] = $this->common_model->get_records($tableName, $fields, '', '', $whereCond, '', '', '', '', '', '', '');
        
        
        $table         = AAI_DROPDOWN . ' as ad';
        $where         = "ad.prefix='pre_incident_location'";
        $fields        = array("ad.*,ado.*");
        $join_tables   = array(AAI_DROPDOWN_OPTION . ' as ado' => 'ado.dropdown_id = ad.dropdown_id');
        $data['location'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where, '', '', '');
        //echo $this->db->last_query();
        /*  pr($data['location']);
        die;  */
        
        
        $data['footerJs'][0]    = base_url('uploads/custom/js/AAIReport/AAIReport.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');
        $data['main_content'] = '/Dashboard_report';
        $this->parser->parse('layouts/DefaultTemplate', $data);
        
        
        
    }
	
	public function Management(){
        
        /*for Number of Incidents by Type (Level)*/
        /*x-axis: Number of incidents  y-axis: Type of Incidents*/
        /*get all the carehome name for filter data*/
        $table = CARE_HOME . ' as ch';
        $where = array("ch.is_delete" => "'0'","ch.status" => "'active'");
        $fields = array("ch.care_home_name, ch.care_home_id,ch.profile_img");
        $data['care_home_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
        /*get all the YP name for filter data*/
        $tableName = YP_DETAILS . ' as yp';
        $fields = array('yp.yp_id, CONCAT(yp.yp_fname," ",yp.yp_lname) as ypName');
        $whereCond = array('yp.status' => 'active', 'yp.is_deleted' => '0');
        $data['all_yp'] = $this->common_model->get_records($tableName, $fields, '', '', $whereCond, '', '', '', '', '', '', '');
		
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
        $data['all_staff_data'] = array_merge($bambooUsers, $nfcUsers);
		 /* pr($data['all_staff_data']);
		die;  */
        
        
        $table         = AAI_DROPDOWN . ' as ad';
        $where         = "ad.prefix='pre_incident_location'";
        $fields        = array("ad.*,ado.*");
        $join_tables   = array(AAI_DROPDOWN_OPTION . ' as ado' => 'ado.dropdown_id = ad.dropdown_id');
        $data['location'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where, '', '', '');
        //echo $this->db->last_query();
        /*  pr($data['location']);
        die;  */
        
        
        $data['footerJs'][0]    = base_url('uploads/custom/js/AAIReport/AAIReportManagement.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');
        $data['main_content'] = '/Management_report';
        $this->parser->parse('layouts/DefaultTemplate', $data);
        
        
        
    }

    public function DashboardReport(){
        
        /*for Number of Incidents by Type (Level)*/
        /*x-axis: Number of incidents  y-axis: Type of Incidents*/
        $care_home=$this->input->post('care_home');
        $yp_id=$this->input->post('yp_id');
        $locationdata=$this->input->post('locationdata');
        $from_date=$this->input->post('from_date');
        $to_date=$this->input->post('to_date');
        
        $data['footerJs'][0]    = base_url('uploads/custom/js/AAIReport/AAIReport.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');
        /*
        {xaxisdata: ["31-01"], seriesdata: [{name: "parent", data: [49]}]}
        seriesdata: [{name: "parent", data: [49]}]
        xaxisdata: ["31-01"]
        */
        
        /*
        {xaxisdata: ["25", "35"], seriesdata: ["50", "60"]}
        seriesdata: ["50", "60"]
        xaxisdata: ["25", "35"]
        */
        /*this count is for L2&3(Was physical intervention used?)*/
        $whereCond_pi = 'ai.is_deleted = "0" AND ai.is_pi =1';
        $whereCond_yp_injured ='ai.is_deleted = "0" AND ai.is_yp_injured =1';
        $where_is_yp_safeguarding= 'ai.is_deleted = "0" AND ai.is_yp_safeguarding =1';
        $where_is_other_injured= 'ai.is_deleted = "0" AND ai.is_other_injured =1';
        $where_is_yp_missing= 'ai.is_deleted = "0" AND ai.is_yp_missing =1';
        $where_is_yp_complaint ='ai.is_deleted = "0" AND ai.is_yp_complaint =1';
        $where_is_staff_injured= 'ai.is_deleted = "0" AND ai.is_staff_injured =1';
        
        if (!empty($yp_id)) {
                $whereCond_pi .= ' AND ai.yp_id = ' . $yp_id;
                $whereCond_yp_injured .= ' AND ai.yp_id = ' . $yp_id;
                $where_is_yp_safeguarding .= ' AND ai.yp_id = ' . $yp_id;
                $where_is_other_injured .= ' AND ai.yp_id = ' . $yp_id;
                $where_is_yp_missing .= ' AND ai.yp_id = ' . $yp_id;
                $where_is_yp_complaint .= ' AND ai.yp_id = ' . $yp_id;
                $where_is_staff_injured .= ' AND ai.yp_id = ' . $yp_id;
            }
            if (!empty($from_date)) {
                $whereCond_pi .= ' AND ai.created_date >= "' . $from_date . '"';
                $whereCond_yp_injured .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_yp_safeguarding .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_other_injured .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_yp_missing .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_yp_complaint .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_staff_injured .= ' AND ai.created_date >= "' . $from_date . '"';
            }
            if (!empty($to_date)) {
                $whereCond_pi .= ' AND ai.created_date <= "' . $to_date . '"';
                $whereCond_yp_injured .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_yp_safeguarding .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_other_injured .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_yp_missing .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_yp_complaint .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_staff_injured .= ' AND ai.created_date <= "' . $to_date . '"';
            }
            if (!empty($care_home)) {
                $whereCond_pi .= ' AND ai.care_home_id = "' . $care_home . '"';
                $whereCond_yp_injured .= ' AND ai.care_home_id = "' . $care_home . '"';
                $where_is_yp_safeguarding .= ' AND ai.care_home_id = "' . $care_home . '"';
                $where_is_other_injured .= ' AND ai.care_home_id = "' . $care_home . '"';
                $where_is_yp_missing .= ' AND ai.care_home_id = "' . $care_home . '"';
                $where_is_yp_complaint .= ' AND ai.care_home_id = "' . $care_home . '"';
                $where_is_staff_injured .= ' AND ai.care_home_id = "' . $care_home . '"';
            }
        
        $table         = AAI_MAIN . ' as ai';
        //$where                          = "ai.is_pi =1";  
        $fields                         = array('count(*) as count');
        $l1count_data         = $this->common_model->get_records($table, $fields, '', '', $whereCond_pi, '', '', '', '', '', '', '');
        
        /*this count is for L5(Was the YP injured?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_yp_injured" => "1");
        $fields                         = array('count(*) as count');
        $l5count_data         = $this->common_model->get_records($table, $fields, '', '', $whereCond_yp_injured, '', '', '', '', '', '', '');
        
        /*this count is for L7(Is any part of this incident a safeguarding concern?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_yp_safeguarding    " => "1");
        $fields                         = array('count(*) as count');
        $l7count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_yp_safeguarding, '', '', '', '', '', '', '');
        
        /*this count is for L9(Was anyone else injured?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_other_injured" => "1");
        $fields                         = array('count(*) as count');
        $l9count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_other_injured, '', '', '', '', '', '', '');
        
        /*this count is for L4(Did YP go missing?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_yp_missing" => "1");
        $fields                         = array('count(*) as count');
        $l4count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_yp_missing, '', '', '', '', '', '', '');
        
        /*this count is for L6(Has a complaint been made either by the YP or on behalf of the YP?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_yp_complaint" => "1");
        $fields                         = array('count(*) as count');
        $l6count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_yp_complaint, '', '', '', '', '', '', '');
        
        /*this count is for L8(Was a staff member injured?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_staff_injured" => "1");
        $fields                         = array('count(*) as count');
        $l8count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_staff_injured, '', '', '', '', '', '', '');
        
        $countdata=array((int)$l1count_data[0]['count'],(int)$l5count_data[0]['count'],(int)$l7count_data[0]['count'],(int)$l9count_data[0]['count'],(int)$l4count_data[0]['count'],(int)$l6count_data[0]['count'],(int)$l8count_data[0]['count']);
        
        //$countdata=array(41,31,1,12,11,1,91);
         
        $value[]=array('name'=>'Count','data'=>$countdata);
        
        $variable = array('xaxisdata' => array("Was physical intervention used?
","Was the YP injured?
","Is any part of this incident a safeguarding concern?
",
"Was anyone else injured?",
"Did YP go missing?",
"Has a complaint been made either by the YP or on behalf of the YP?",
"Was a staff member injured?"
),
            'seriesdata' => $value);
            /* pr($variable);
            die; */

        echo json_encode($variable);
        
        
    }


/*
    @Author : Rana Ritesh
    @Desc   : Main Listing Page
    @Date   : 18/02/2019
*/
    function getNumberOfIncident() {
        $data['footerJs'][0]    = base_url('uploads/custom/js/AAIReport/AAIReport.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');

        $yp_ID = $this->input->post('yp_id');
        $yp_id = !empty($yp_ID) ? $yp_ID : '';
        $data['yp_id'] = $yp_id;

        $getFromDate = $this->input->post('from_date');
        $from_date = !empty($getFromDate) ? $getFromDate : '';
        $data['from_date'] = $from_date;

        $getToDate = $this->input->post('to_date');
        $ToDate = !empty($getToDate) ? $getToDate : '';
        $data['to_date'] = $ToDate;

        $careHome = $this->input->post('care_home');
        $care_home = !empty($careHome) ? $careHome : '';
        $data['care_home'] = $care_home;
                
        $location = $this->input->post('location');
        $location_data = !empty($location) ? $location : '';
        $data['location'] = $location_data;

        $tableName = AAI_MAIN . ' as aai';

       

        // if (!empty($data['yp_id'])) {
        //         $whereCond .= ' AND aai.yp_id = ' . $data['yp_id'];
        //     }

        //     if (!empty($data['from_date'])) {
        //         $whereCond .= ' AND aai.created_date >= "' . $data['from_date'] . '"';
        //     }

        //     if (!empty($data['to_date'])) {
        //         $whereCond .= ' AND aai.created_date <= "' . $data['to_date'] . '"';
        //     }

        //     if (!empty($data['location'])) {
        //         $whereCond .= ' AND aai.created_date <= "' . $data['location'] . '"';
        //     }

     $data['care_home_data'] = 0;       
    if($this->input->post('care_home') > 0){
         $whereCond = 'aai.is_deleted = "0" ';
        $whereCond .= ' AND aai.care_home_id = ' . $data['care_home'];
        $fieldsRecord = array("count(*) as count,ch.care_home_name");
         $join_tables = array(CARE_HOME . ' as ch' => 'ch.care_home_id = aai.care_home_id');
        $data['care_home_data'] = $this->common_model->get_records($tableName, $fieldsRecord, $join_tables, 'left', $whereCond);
    }

    $whereCond_data = 'aai.is_deleted = "0" ';
    $fieldsRecord = array("count(*) as count");
    $data['record_details'] = $this->common_model->get_records($tableName, $fieldsRecord, '', '', $whereCond_data);
    
    $record_details = $data['record_details'][0]['count'];

        $care_home = $data['care_home_data'][0]['count'];
        if($care_home  == ''){
            $care_home = 0;
        }

        $count_total_data = $record_details - $care_home;
		

        $care_home_name = $data['care_home_data'][0]['care_home_name'];

        echo json_encode(array('count_data' => $count_total_data,'care_home' =>$care_home,'care_home_name'=>$care_home_name));
    }


/*
    @Author : Rana Ritesh
    @Desc   : get Related To L2 graph
    @Date   : 18/02/2019
*/
    function getRelatedToL2() {
        $data['footerJs'][0]    = base_url('uploads/custom/js/AAIReport/AAIReport.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');

        $yp_ID = $this->input->post('yp_id');
        $yp_id = !empty($yp_ID) ? $yp_ID : '';
        $data['yp_id'] = $yp_id;

        $getFromDate = $this->input->post('from_date');
        $from_date = !empty($getFromDate) ? $getFromDate : '';
        $data['from_date'] = $from_date;

        $getToDate = $this->input->post('to_date');
        $ToDate = !empty($getToDate) ? $getToDate : '';
        $data['to_date'] = $ToDate;

        $careHome = $this->input->post('care_home');
        $care_home = !empty($careHome) ? $careHome : '';
        $data['care_home'] = $care_home;
                
        $location = $this->input->post('location');
        $location_data = !empty($location) ? $location : '';
        $data['location'] = $location_data;


        $tableName = AAI_MAIN . ' as aai';

        $whereCond = 'aai.is_deleted = "0" ';

        if (!empty($data['yp_id'])) {
                $whereCond .= ' AND aai.yp_id = ' . $data['yp_id'];
            }



            if (!empty($data['from_date'])) {
                $whereCond .= ' AND aai.created_date >= "' . dateformat($data['from_date']) . '"';
            }

            if (!empty($data['to_date'])) {
                $whereCond .= ' AND aai.created_date <= "' . dateformat($data['to_date']) . '"';
            }

            // if(!empty($data['to_date']) && !empty($data['from_date'])){
            //     $whereCond .= ' AND aai.created_date BETWEEN  "' . dateformat($data['from_date']).'" AND "'.dateformat($data['to_date']).'"';
            // }

            if (!empty($data['location'])) {
                $whereCond .= ' AND aai.created_date <= "' . $data['location'] . '"';
            }



     $data['care_home_data'] = array();       
    if($this->input->post('care_home') > 0){
        $whereCond .= ' AND aai.care_home_id = ' . $data['care_home'];
        $fieldsRecord = array("incident_id,l2_l3_form_data,l2_l3_reference_number,ch.care_home_name");
        $join_tables = array(CARE_HOME . ' as ch' => 'ch.care_home_id = aai.care_home_id');
        $data['care_home_data'] = $this->common_model->get_records($tableName, $fieldsRecord, $join_tables, 'left', $whereCond);
    }

        $whereCond_data = 'aai.is_deleted = "0" ';
        $fieldsRecord = array("l1_form_data,l2_l3_form_data,l2_l3_reference_number");
        $data['record_details'] = $this->common_model->get_records($tableName, $fieldsRecord, '', '', $whereCond_data);

        foreach ($data['record_details'] as $value) {
            if(!empty($value['l2_l3_form_data']) && !empty($value['l2_l3_reference_number'])){
                    $l2_data = $value['l2_l3_reference_number'];
                    $editl2Data = json_decode($value['l2_l3_form_data'], true);
                    $total_count2[] = $editl2Data['l2_total_duration'];        
            }

            if(!empty($value['l1_form_data'])){
                    $l1_data = $value['l1_form_data'];
                    $editl1Data = json_decode($value['l1_form_data'], true);
                    $total_count1[] = $editl1Data['l1_total_duration'];        
            }
        }

$data_count = array();
    if(!empty($data['care_home_data'])){
    foreach ($data['care_home_data'] as $value) {
            if(!empty($value['l2_l3_form_data']) && !empty($value['l2_l3_reference_number'])){
                    $l2_data = $value['l2_l3_reference_number'];
                    $l2_proc = substr($l2_data,0,2);
                    if($l2_proc == 'L2'){
                        $editl2Data = json_decode($value['l2_l3_form_data'], true);
                        $data_count[] = $editl2Data['l2_total_duration'];        
                    }
            }
        }
    }
    

        $total_durationl2 = array_sum($total_count2);
        $total_durationl1 = array_sum($total_count1);

        $record_details = $total_durationl2 + $total_durationl1;

        $care_home = array_sum($data_count);

        $count_total_duration = $record_details - $care_home;

        $care_home_name = $data['care_home_data'][0]['care_home_name'];

        echo json_encode(array('count_data' => $count_total_duration,'care_home' =>$care_home,'care_home_name'=>$care_home_name));
    }
    

    /*
    @Author : Rana Ritesh
    @Desc   : get Related To L2 & L3 graph
    @Date   : 19/02/2019
*/
    function getRelatedToL2AndL3() {
        $data['footerJs'][0]    = base_url('uploads/custom/js/AAIReport/AAIReport.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');

        $yp_ID = $this->input->post('yp_id');
        $yp_id = !empty($yp_ID) ? $yp_ID : '';
        $data['yp_id'] = $yp_id;

        $getFromDate = $this->input->post('from_date');
        $from_date = !empty($getFromDate) ? $getFromDate : '';
        $data['from_date'] = $from_date;

        $getToDate = $this->input->post('to_date');
        $ToDate = !empty($getToDate) ? $getToDate : '';
        $data['to_date'] = $ToDate;

        $careHome = $this->input->post('care_home');
        $care_home = !empty($careHome) ? $careHome : '';
        $data['care_home'] = $care_home;
                
        $location = $this->input->post('location');
        $location_data = !empty($location) ? $location : '';
        $data['location'] = $location_data;


        $tableName = AAI_MAIN . ' as aai';

        $whereCond = 'aai.is_deleted = "0" ';

        if (!empty($data['yp_id'])) {
                $whereCond .= ' AND aai.yp_id = ' . $data['yp_id'];
            }



            if (!empty($data['from_date'])) {
                $whereCond .= ' AND aai.created_date >= "' . dateformat($data['from_date']) . '"';
            }

            if (!empty($data['to_date'])) {
                $whereCond .= ' AND aai.created_date <= "' . dateformat($data['to_date']) . '"';
            }

            // if(!empty($data['to_date']) && !empty($data['from_date'])){
            //     $whereCond .= ' AND aai.created_date BETWEEN  "' . dateformat($data['from_date']).'" AND "'.dateformat($data['to_date']).'"';
            // }

            if (!empty($data['location'])) {
                $whereCond .= ' AND aai.created_date <= "' . $data['location'] . '"';
            }



     $data['care_home_data'] = array();       
    if($this->input->post('care_home') > 0){
        $whereCond .= ' AND aai.care_home_id = ' . $data['care_home'];
        $fieldsRecord = array("incident_id,l2_l3_form_data,l2_l3_reference_number,ch.care_home_name");
        $join_tables = array(CARE_HOME . ' as ch' => 'ch.care_home_id = aai.care_home_id');
        $data['care_home_data'] = $this->common_model->get_records($tableName, $fieldsRecord, $join_tables, 'left', $whereCond);
    }

        $whereCond_data = 'aai.is_deleted = "0" ';
        $fieldsRecord = array("l1_form_data,l2_l3_form_data,l2_l3_reference_number");
        $data['record_details'] = $this->common_model->get_records($tableName, $fieldsRecord, '', '', $whereCond_data);

        foreach ($data['record_details'] as $value) {
            if(!empty($value['l2_l3_form_data']) && !empty($value['l2_l3_reference_number'])){
                    $l2_data = $value['l2_l3_reference_number'];
                    $editl2Data = json_decode($value['l2_l3_form_data'], true);
                    $total_count2[] = $editl2Data['l2_total_duration'];        
            }

            if(!empty($value['l1_form_data'])){
                    $l1_data = $value['l1_form_data'];
                    $editl1Data = json_decode($value['l1_form_data'], true);
                    $total_count1[] = $editl1Data['l1_total_duration'];        
            }
        }



$data_count = array();
    if(!empty($data['care_home_data'])){
    foreach ($data['care_home_data'] as $value) {
            if(!empty($value['l2_l3_form_data']) && !empty($value['l2_l3_reference_number'])){
                    $l2_data = $value['l2_l3_reference_number'];
                        $editl2Data = json_decode($value['l2_l3_form_data'], true);
                        $data_count[] = $editl2Data['l2_total_duration'];        
            }
        }
    }
    
        $total_durationl2 = array_sum($total_count2);
        $total_durationl1 = array_sum($total_count1);

        $record_details = $total_durationl2 + $total_durationl1;
        $care_home = array_sum($data_count);

        $count_total_duration = $record_details - $care_home;
        $care_home_name = $data['care_home_data'][0]['care_home_name'];
        echo json_encode(array('count_data' => $count_total_duration,'care_home' =>$care_home,'care_home_name' => $care_home_name));
    }



/*
    @Author : Rana Ritesh
    @Desc   : get Related To L2 graph
    @Date   : 18/02/2019
*/
    function getRelatedToL3() {
        $data['footerJs'][0]    = base_url('uploads/custom/js/AAIReport/AAIReport.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');

        $yp_ID = $this->input->post('yp_id');
        $yp_id = !empty($yp_ID) ? $yp_ID : '';
        $data['yp_id'] = $yp_id;

        $getFromDate = $this->input->post('from_date');
        $from_date = !empty($getFromDate) ? $getFromDate : '';
        $data['from_date'] = $from_date;

        $getToDate = $this->input->post('to_date');
        $ToDate = !empty($getToDate) ? $getToDate : '';
        $data['to_date'] = $ToDate;

        $careHome = $this->input->post('care_home');
        $care_home = !empty($careHome) ? $careHome : '';
        $data['care_home'] = $care_home;
                
        $location = $this->input->post('location');
        $location_data = !empty($location) ? $location : '';
        $data['location'] = $location_data;


        $tableName = AAI_MAIN . ' as aai';

        $whereCond = 'aai.is_deleted = "0" ';

        if (!empty($data['yp_id'])) {
                $whereCond .= ' AND aai.yp_id = ' . $data['yp_id'];
            }



            if (!empty($data['from_date'])) {
                $whereCond .= ' AND aai.created_date >= "' . dateformat($data['from_date']) . '"';
            }

            if (!empty($data['to_date'])) {
                $whereCond .= ' AND aai.created_date <= "' . dateformat($data['to_date']) . '"';
            }

            // if(!empty($data['to_date']) && !empty($data['from_date'])){
            //     $whereCond .= ' AND aai.created_date BETWEEN  "' . dateformat($data['from_date']).'" AND "'.dateformat($data['to_date']).'"';
            // }

            if (!empty($data['location'])) {
                $whereCond .= ' AND aai.created_date <= "' . $data['location'] . '"';
            }

     $data['care_home_data'] = array();       
    if($this->input->post('care_home') > 0){
        $whereCond .= ' AND aai.care_home_id = ' . $data['care_home'];
        $fieldsRecord = array("incident_id,l2_l3_form_data,l2_l3_reference_number,ch.care_home_name");
        $join_tables = array(CARE_HOME . ' as ch' => 'ch.care_home_id = aai.care_home_id');
        $data['care_home_data'] = $this->common_model->get_records($tableName, $fieldsRecord, $join_tables, 'left', $whereCond);

    }

        $whereCond_data = 'aai.is_deleted = "0" ';
        $fieldsRecord = array("incident_id,l1_form_data,l2_l3_form_data,l2_l3_reference_number");
        $data['record_details'] = $this->common_model->get_records($tableName, $fieldsRecord, '', '', $whereCond_data);
        foreach ($data['record_details'] as $value) {
            if(!empty($value['l2_l3_form_data']) && !empty($value['l2_l3_reference_number'])){
                    $l2_data = $value['l2_l3_reference_number'];
                    $editl2Data = json_decode($value['l2_l3_form_data'], true);
                    $total_count2[] = $editl2Data['l2_total_duration'];        
            }

            if(!empty($value['l1_form_data'])){
                    $l1_data = $value['l1_form_data'];
                    $editl1Data = json_decode($value['l1_form_data'], true);
                    $total_count1[] = $editl1Data['l1_total_duration'];        
            }
        }

$data_count = array();
    if(!empty($data['care_home_data'])){
    foreach ($data['care_home_data'] as $value) {
            if(!empty($value['l2_l3_form_data']) && !empty($value['l2_l3_reference_number'])){
                    $l3_data = $value['l2_l3_reference_number'];
                    $l3_proc = substr($l3_data,0,2);
                    if($l3_proc == 'L3'){
                        $editl3Data = json_decode($value['l2_l3_form_data'], true);
                        $data_count[] = $editl3Data['l2_total_duration'];   
                    }
            }
        }
    }
        $total_durationl2 = array_sum($total_count2);
        $total_durationl1 = array_sum($total_count1);
        $record_details = $total_durationl2 + $total_durationl1;
        $care_home = array_sum($data_count);

         $count_total_duration = $record_details - $care_home;

         $care_home_name = $data['care_home_data'][0]['care_home_name'];

        echo json_encode(array('count_data' => $count_total_duration,'care_home' =>$care_home,'care_home_name'=>$care_home_name));
    }



/*
    @Author : Rana Ritesh
    @Desc   : get Related To L1 graph
    @Date   : 19/02/2019
*/
    function getRelatedToL1() {
        $data['footerJs'][0]    = base_url('uploads/custom/js/AAIReport/AAIReport.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');

        $yp_ID = $this->input->post('yp_id');
        $yp_id = !empty($yp_ID) ? $yp_ID : '';
        $data['yp_id'] = $yp_id;

        $getFromDate = $this->input->post('from_date');
        $from_date = !empty($getFromDate) ? $getFromDate : '';
        $data['from_date'] = $from_date;

        $getToDate = $this->input->post('to_date');
        $ToDate = !empty($getToDate) ? $getToDate : '';
        $data['to_date'] = $ToDate;

        $careHome = $this->input->post('care_home');
        $care_home = !empty($careHome) ? $careHome : '';
        $data['care_home'] = $care_home;
                
        $location = $this->input->post('location');
        $location_data = !empty($location) ? $location : '';
        $data['location'] = $location_data;


        $tableName = AAI_MAIN . ' as aai';

        $whereCond = 'aai.is_deleted = "0" ';

        if (!empty($data['yp_id'])) {
                $whereCond .= ' AND aai.yp_id = ' . $data['yp_id'];
            }

            if (!empty($data['from_date'])) {
                $whereCond .= ' AND aai.created_date >= "' . dateformat($data['from_date']) . '"';
            }

            if (!empty($data['to_date'])) {
                $whereCond .= ' AND aai.created_date <= "' . dateformat($data['to_date']) . '"';
            }

            if (!empty($data['location'])) {
                $whereCond .= ' AND aai.created_date <= "' . $data['location'] . '"';
            }

     $data['care_home_data'] = array();       
    if($this->input->post('care_home') > 0){
        $whereCond .= ' AND aai.care_home_id = ' . $data['care_home'];
        $fieldsRecord = array("incident_id,l1_form_data,l1_reference_number,ch.care_home_name");
        $join_tables = array(CARE_HOME . ' as ch' => 'ch.care_home_id = aai.care_home_id');
        $data['care_home_data'] = $this->common_model->get_records($tableName, $fieldsRecord, $join_tables, 'left', $whereCond);
    }

        $whereCond_data = 'aai.is_deleted = "0" ';
        $fieldsRecord = array("incident_id,l1_form_data,l2_l3_form_data,l2_l3_reference_number");
        $data['record_details'] = $this->common_model->get_records($tableName, $fieldsRecord, '', '', $whereCond_data);
        foreach ($data['record_details'] as $value) {
            if(!empty($value['l2_l3_form_data']) && !empty($value['l2_l3_reference_number'])){
                    $l2_data = $value['l2_l3_reference_number'];
                    $editl2Data = json_decode($value['l2_l3_form_data'], true);
                    $total_count2[] = $editl2Data['l2_total_duration'];        
            }

            if(!empty($value['l1_form_data'])){
                    $l1_data = $value['l1_form_data'];
                    $editl1Data = json_decode($value['l1_form_data'], true);
                    $total_count1[] = $editl1Data['l1_total_duration'];        
            }
        }

$data_count = array();
    if(!empty($data['care_home_data'])){
    foreach ($data['care_home_data'] as $value) {
            if(!empty($value['l1_form_data'])){
                $editl3Data = json_decode($value['l1_form_data'], true);
                $data_count[] = $editl3Data['l1_total_duration'];   
            }
        }   
    }

        $total_durationl2 = array_sum($total_count2);
        $total_durationl1 = array_sum($total_count1);
        $record_details = $total_durationl2 + $total_durationl1;
        $care_home = array_sum($data_count);

        $count_total_duration = $record_details - $care_home;

        $care_home_name = $data['care_home_data'][0]['care_home_name'];

        echo json_encode(array('count_data' => $count_total_duration,'care_home' =>$care_home,'care_home_name'=>$care_home_name));
    }


    /*
    @Author : Rana Ritesh
    @Desc   : get Related To REG40 graph
    @Date   : 20/02/2019
*/
    function getRelatedToREG40() {
        $data['footerJs'][0]    = base_url('uploads/custom/js/AAIReport/AAIReport.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');

        $yp_ID = $this->input->post('yp_id');
        $yp_id = !empty($yp_ID) ? $yp_ID : '';
        $data['yp_id'] = $yp_id;

        $getFromDate = $this->input->post('from_date');
        $from_date = !empty($getFromDate) ? $getFromDate : '';
        $data['from_date'] = $from_date;

        $getToDate = $this->input->post('to_date');
        $ToDate = !empty($getToDate) ? $getToDate : '';
        $data['to_date'] = $ToDate;

        $careHome = $this->input->post('care_home');
        $care_home = !empty($careHome) ? $careHome : '';
        $data['care_home'] = $care_home;
                
        $location = $this->input->post('location');
        $location_data = !empty($location) ? $location : '';
        $data['location'] = $location_data;


        $tableName = AAI_MAIN . ' as aai';

        $whereCond = 'aai.is_deleted = "0" ';

        if (!empty($data['yp_id'])) {
                $whereCond .= ' AND aai.yp_id = ' . $data['yp_id'];
            }

            if (!empty($data['from_date'])) {
                $whereCond .= ' AND aai.created_date >= "' . dateformat($data['from_date']) . '"';
            }

            if (!empty($data['to_date'])) {
                $whereCond .= ' AND aai.created_date <= "' . dateformat($data['to_date']) . '"';
            }

            if (!empty($data['location'])) {
                $whereCond .= ' AND aai.created_date <= "' . $data['location'] . '"';
            }

     $data['care_home_data'] = array();       
    if($this->input->post('care_home') > 0){
        $whereCond .= ' AND aai.care_home_id = ' . $data['care_home'];
        $fieldsRecord = array("aai.*,ch.care_home_name");
        $join_tables = array(CARE_HOME . ' as ch' => 'ch.care_home_id = aai.care_home_id');
        $data['care_home_data'] = $this->common_model->get_records($tableName, $fieldsRecord, $join_tables, 'left', $whereCond);
    }

        $whereCond_data = 'aai.is_deleted = "0" ';
        $fieldsRecord = array("aai.*");
        $data['record_details'] = $this->common_model->get_records($tableName, $fieldsRecord, '', '', $whereCond_data);
        $data_val = array();
        $listed_dta = array();
        foreach ($data['record_details'] as $value) {
            if(!empty($value['l1_form_data'])){
                    $editl1Data = json_decode($value['l1_form_data'], true);
                    foreach ($editl1Data as $data_type) {
                        if($data_type['type'] == 'checkbox-group' && $data_type['name'] == 'l1_reg40'){
                            $data_val1[$value['incident_id']] = $data_type['value'];
                            $listed_dta[$value['incident_id']]['l1']=1;
                        }
                    }
                }
            
            if(!empty($value['l2_l3_form_data'])){
                    $editl2Data = json_decode($value['l2_l3_form_data'], true);
                    foreach ($editl2Data as $data_type) {
                        if($data_type['type'] == 'checkbox-group' && $data_type['name'] == 'l2_reg40' && $data_type['value'] == 'Yes'){
                            $data_val2[$value['incident_id']] = $data_type['value'];
                            $listed_dta[$value['incident_id']]['l2']=1;
                    }
                }
            }

            if(!empty($value['l4_form_data'])){
                    $editl4Data = json_decode($value['l4_form_data'], true);
                    foreach ($editl4Data as $data_type) {
                        if($data_type['type'] == 'checkbox-group' && $data_type['name'] == 'is_reg_40' && $data_type['value'] == 'Yes'){
                            $data_val4[$value['incident_id']] = $data_type['value'];
                            $listed_dta[$value['incident_id']]['l4']=1;
                    }
                }
            }


            if(!empty($value['l5_form_data'])){
                    $editl5Data = json_decode($value['l5_form_data'], true);
                    foreach ($editl5Data as $data_type) {
                        if($data_type['type'] == 'checkbox-group' && $data_type['name'] == 'l5_reg_40' && $data_type['value'] == 'Yes'){
                            $data_val5[$value['incident_id']] = $data_type['value'];
                            $listed_dta[$value['incident_id']]['l5']=1;
                    }
                }
            }


            if(!empty($value['l6_form_data'])){
                    $editl6Data = json_decode($value['l6_form_data'], true);
                    foreach ($editl6Data as $data_type) {
                        if($data_type['type'] == 'checkbox-group' && $data_type['name'] == 'l6_reg40' && $data_type['value'] == 'Yes'){
                            $data_val6[$value['incident_id']] = $data_type['value'];
                            $listed_dta[$value['incident_id']]['l6']=1;
                    }
                }
            }

            if(!empty($value['l7_form_data'])){
                    $editl7Data = json_decode($value['l7_form_data'], true);
                    foreach ($editl7Data as $data_type) {
                        if($data_type['type'] == 'radio-group' && $data_type['name'] == 'l7_reg_40_ofsted_informed_reg_40' && $data_type['value'] == 'Yes'){
                            $data_val7[$value['incident_id']] = $data_type['value'];
                            $listed_dta[$value['incident_id']]['l7']=1;
                    }
                }
            }
        }

// get search data 
        $listed_care_home_data = array();
        foreach ($data['care_home_data'] as $value) {
            if(!empty($value['l1_form_data'])){
                    $editl1Data = json_decode($value['l1_form_data'], true);
                    foreach ($editl1Data as $data_type) {
                        if($data_type['type'] == 'checkbox-group' && $data_type['name'] == 'l1_reg40'){
                            $data_val1[$value['incident_id']] = $data_type['value'];
                            $listed_care_home_data[$value['incident_id']]['l1']=1;
                        }
                    }
                }
            
            if(!empty($value['l2_l3_form_data'])){
                    $editl2Data = json_decode($value['l2_l3_form_data'], true);
                    foreach ($editl2Data as $data_type) {
                        if($data_type['type'] == 'checkbox-group' && $data_type['name'] == 'l2_reg40' && $data_type['value'] == 'Yes'){
                            $data_val2[$value['incident_id']] = $data_type['value'];
                            $listed_care_home_data[$value['incident_id']]['l2']=1;
                    }
                }
            }

            if(!empty($value['l4_form_data'])){
                    $editl4Data = json_decode($value['l4_form_data'], true);
                    foreach ($editl4Data as $data_type) {
                        if($data_type['type'] == 'checkbox-group' && $data_type['name'] == 'is_reg_40' && $data_type['value'] == 'Yes'){
                            $data_val4[$value['incident_id']] = $data_type['value'];
                            $listed_care_home_data[$value['incident_id']]['l4']=1;
                    }
                }
            }


            if(!empty($value['l5_form_data'])){
                    $editl5Data = json_decode($value['l5_form_data'], true);
                    foreach ($editl5Data as $data_type) {
                        if($data_type['type'] == 'checkbox-group' && $data_type['name'] == 'l5_reg_40' && $data_type['value'] == 'Yes'){
                            $data_val5[$value['incident_id']] = $data_type['value'];
                            $listed_care_home_data[$value['incident_id']]['l5']=1;
                    }
                }
            }


            if(!empty($value['l6_form_data'])){
                    $editl6Data = json_decode($value['l6_form_data'], true);
                    foreach ($editl6Data as $data_type) {
                        if($data_type['type'] == 'checkbox-group' && $data_type['name'] == 'l6_reg40' && $data_type['value'] == 'Yes'){
                            $data_val6[$value['incident_id']] = $data_type['value'];
                            $listed_care_home_data[$value['incident_id']]['l6']=1;
                    }
                }
            }

            if(!empty($value['l7_form_data'])){
                    $editl7Data = json_decode($value['l7_form_data'], true);
                    foreach ($editl7Data as $data_type) {
                        if($data_type['type'] == 'radio-group' && $data_type['name'] == 'l7_reg_40_ofsted_informed_reg_40' && $data_type['value'] == 'Yes'){
                            $data_val7[$value['incident_id']] = $data_type['value'];
                            $listed_care_home_data[$value['incident_id']]['l7']=1;
                    }
                }
            }
        }
        
        $record_details = count($listed_dta);
        $care_home = count($listed_care_home_data);

        $count_total_duration = $record_details - $care_home;

        $care_home_name = $data['care_home_data'][0]['care_home_name'];

        echo json_encode(array('count_data' => $count_total_duration,'care_home' =>$care_home,'care_home_name'=>$care_home_name));
    }

 /*
    @Author : Rana Ritesh
    @Desc   : get Related To Police Involvement graph
    @Date   : 20/02/2019
*/
    function getRelatedToPoliceInvolvement() {
        $data['footerJs'][0]    = base_url('uploads/custom/js/AAIReport/AAIReport.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');

        $yp_ID = $this->input->post('yp_id');
        $yp_id = !empty($yp_ID) ? $yp_ID : '';
        $data['yp_id'] = $yp_id;

        $getFromDate = $this->input->post('from_date');
        $from_date = !empty($getFromDate) ? $getFromDate : '';
        $data['from_date'] = $from_date;

        $getToDate = $this->input->post('to_date');
        $ToDate = !empty($getToDate) ? $getToDate : '';
        $data['to_date'] = $ToDate;

        $careHome = $this->input->post('care_home');
        $care_home = !empty($careHome) ? $careHome : '';
        $data['care_home'] = $care_home;
                
        $location = $this->input->post('location');
        $location_data = !empty($location) ? $location : '';
        $data['location'] = $location_data;


        $tableName = AAI_MAIN . ' as aai';

        $whereCond = 'aai.is_deleted = "0" ';

        if (!empty($data['yp_id'])) {
                $whereCond .= ' AND aai.yp_id = ' . $data['yp_id'];
            }

            if (!empty($data['from_date'])) {
                $whereCond .= ' AND aai.created_date >= "' . dateformat($data['from_date']) . '"';
            }

            if (!empty($data['to_date'])) {
                $whereCond .= ' AND aai.created_date <= "' . dateformat($data['to_date']) . '"';
            }

            if (!empty($data['location'])) {
                $whereCond .= ' AND aai.created_date <= "' . $data['location'] . '"';
            }

     $data['care_home_data'] = array();       
    if($this->input->post('care_home') > 0){
        $whereCond .= ' AND aai.care_home_id = ' . $data['care_home'];
        $fieldsRecord = array("aai.*,ch.care_home_name");
        $join_tables = array(CARE_HOME . ' as ch' => 'ch.care_home_id = aai.care_home_id');
        $data['care_home_data'] = $this->common_model->get_records($tableName, $fieldsRecord, $join_tables, 'left', $whereCond);
    }

        $whereCond_data = 'aai.is_deleted = "0" ';
        $fieldsRecord = array("aai.*");
        $data['record_details'] = $this->common_model->get_records($tableName, $fieldsRecord, '', '', $whereCond_data);
        $data_val = array();
        $listed_dta = array();
        foreach ($data['record_details'] as $value) {
            if(!empty($value['l1_form_data'])){
                    $editl1Data = json_decode($value['l1_form_data'], true);
                    foreach ($editl1Data as $data_type) {
                        if($data_type['type'] == 'radio-group' && $data_type['name'] == 'l1_police_informed_pi'){
                            $listed_dta[$value['incident_id']]['l1']=1;
                        }
                    }
                }
            
            if(!empty($value['l2_l3_form_data'])){
                    $editl2Data = json_decode($value['l2_l3_form_data'], true);
                    foreach ($editl2Data as $data_type) {
                        if($data_type['type'] == 'radio-group' && $data_type['name'] == 'l2_police_informed_pi' && $data_type['value'] == 'Yes'){
                            $listed_dta[$value['incident_id']]['l2']=1;
                    }
                }
            }

            if(!empty($value['l4_form_data'])){
                    $editl4Data = json_decode($value['l4_form_data'], true);
                    foreach ($editl4Data as $data_type) {
                        if($data_type['type'] == 'radio-group' && $data_type['name'] == 'l4_police_informed_pi' && $data_type['value'] == 'Yes'){
                            $listed_dta[$value['incident_id']]['l4']=1;
                    }
                }
            }

            if(!empty($value['l7_form_data'])){
                    $editl7Data = json_decode($value['l7_form_data'], true);
                    foreach ($editl7Data as $data_type) {
                        if($data_type['type'] == 'radio-group' && $data_type['name'] == 'l7_police_informed_pi' && $data_type['value'] == 'Yes'){
                            $listed_dta[$value['incident_id']]['l7']=1;
                    }
                }
            }
        }

// get search data 
        $listed_care_home_data = array();
        foreach ($data['care_home_data'] as $value) {
            if(!empty($value['l1_form_data'])){
                    $editl1Data = json_decode($value['l1_form_data'], true);
                    foreach ($editl1Data as $data_type) {
                        if($data_type['type'] == 'radio-group' && $data_type['name'] == 'l1_police_informed_pi'){
                            $data_val1[$value['incident_id']] = $data_type['value'];
                            $listed_care_home_data[$value['incident_id']]['l1']=1;
                        }
                    }
                }
            
            if(!empty($value['l2_l3_form_data'])){
                    $editl2Data = json_decode($value['l2_l3_form_data'], true);
                    foreach ($editl2Data as $data_type) {
                        if($data_type['type'] == 'radio-group' && $data_type['name'] == 'l2_police_informed_pi' && $data_type['value'] == 'Yes'){
                            $data_val2[$value['incident_id']] = $data_type['value'];
                            $listed_care_home_data[$value['incident_id']]['l2']=1;
                    }
                }
            }

            if(!empty($value['l4_form_data'])){
                    $editl4Data = json_decode($value['l4_form_data'], true);
                    foreach ($editl4Data as $data_type) {
                        if($data_type['type'] == 'radio-group' && $data_type['name'] == 'l4_police_informed_pi' && $data_type['value'] == 'Yes'){
                            $data_val4[$value['incident_id']] = $data_type['value'];
                            $listed_care_home_data[$value['incident_id']]['l4']=1;
                    }
                }
            }


            if(!empty($value['l7_form_data'])){
                    $editl7Data = json_decode($value['l7_form_data'], true);
                    foreach ($editl7Data as $data_type) {
                        if($data_type['type'] == 'radio-group' && $data_type['name'] == 'l7_police_informed_pi' && $data_type['value'] == 'Yes'){
                            $data_val7[$value['incident_id']] = $data_type['value'];
                            $listed_care_home_data[$value['incident_id']]['l7']=1;
                    }
                }
            }
        }
        
        
        $record_details = count($listed_dta);
        $care_home = count($listed_care_home_data);

        $count_total_duration = $record_details - $care_home;

        $care_home_name = $data['care_home_data'][0]['care_home_name'];

        echo json_encode(array('count_data' => $count_total_duration,'care_home' =>$care_home,'care_home_name'=>$care_home_name));
    }


/*
    @Author : Nikunj Ghelani
    @Desc   : get Related To l7 LADO graph
    @Date   : 19/02/2019
*/
    function getRelatedTol7lado() {
        $data['footerJs'][0]    = base_url('uploads/custom/js/AAIReport/AAIReport.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');

        $yp_ID = $this->input->post('yp_id');
        $yp_id = !empty($yp_ID) ? $yp_ID : '';
        $data['yp_id'] = $yp_id;

        $getFromDate = $this->input->post('from_date');
        $from_date = !empty($getFromDate) ? $getFromDate : '';
        $data['from_date'] = $from_date;

        $getToDate = $this->input->post('to_date');
        $ToDate = !empty($getToDate) ? $getToDate : '';
        $data['to_date'] = $ToDate;

        $careHome = $this->input->post('care_home');
        $care_home = !empty($careHome) ? $careHome : '';
        $data['care_home'] = $care_home;
                
        $location = $this->input->post('location');
        $location_data = !empty($location) ? $location : '';
        $data['location'] = $location_data;


        $tableName = AAI_MAIN . ' as aai';

        $whereCond = 'aai.is_deleted = "0" ';

        if (!empty($data['yp_id'])) {
                $whereCond .= ' AND aai.yp_id = ' . $data['yp_id'];
            }



            if (!empty($data['from_date'])) {
                $whereCond .= ' AND aai.created_date >= "' . dateformat($data['from_date']) . '"';
            }

            if (!empty($data['to_date'])) {
                $whereCond .= ' AND aai.created_date <= "' . dateformat($data['to_date']) . '"';
            }

            // if(!empty($data['to_date']) && !empty($data['from_date'])){
            //     $whereCond .= ' AND aai.created_date BETWEEN  "' . dateformat($data['from_date']).'" AND "'.dateformat($data['to_date']).'"';
            // }

            if (!empty($data['location'])) {
                $whereCond .= ' AND aai.created_date <= "' . $data['location'] . '"';
            }



     $data['care_home_data'] = array();       
    /* if($this->input->post('care_home') > 0){
        $whereCond .= ' AND aai.care_home_id = ' . $data['care_home'];
        $fieldsRecord = array("incident_id,l7_form_data,l7_reference_number");
        $data['care_home_data'] = $this->common_model->get_records($tableName, $fieldsRecord, '', '', $whereCond);
    } */

        $whereCond_data = 'aai.is_deleted = "0" ';
        $fieldsRecord = array("l7_form_data");
        $data['record_details'] = $this->common_model->get_records($tableName, $fieldsRecord, '', '', $whereCond_data);
		
	
	$total_count2 = array();
        foreach ($data['record_details'] as $value) {
            if(!empty($value['l7_form_data'])){
                    
                    $editl7Data = json_decode($value['l7_form_data'], true);
					//pr($editl7Data);
					
					 foreach($editl7Data as $l7data){
						//pr($l7data);
					if(isset($l7data['type']) && $l7data['type'] == 'radio-group' && $l7data['name'] == 'l7_lado_informed_li' && $l7data['value'] == 'Yes' ){
						
						$total_count2[] = $l7data['value']; 
					}
					} 
					
					/* pr($total_count2);
					die; */	
					
                           
            }
			
        }
		 /* pr($total_count2);
					die;  */
		



$data_count = array();
    if(!empty($data['care_home_data'])){
    foreach ($data['care_home_data'] as $value) {
            if(!empty($value['l7_form_data'])){
                    
                        $editl7Data = json_decode($value['l7_form_data'], true);
                        $data_count[] = $editl7Data['l7_total_duration'];        
            }
        }
    }
        
        $total_durationl7 = count($total_count2);     
        $record_details = $total_durationl7;

        $care_home = array_sum($data_count);
        echo json_encode(array('count_data' => $record_details,'care_home' =>$care_home));
    }
	
	
	/*@Author : Nikunj Ghelani
    @Desc   : get Related To getRelated_status_panding_for_each_incident graph
    @Date   : 19/02/2019
*/
    function getRelated_status_panding_for_each_incident() {
        $data['footerJs'][0]    = base_url('uploads/custom/js/AAIReport/AAIReport.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');

        $yp_ID = $this->input->post('yp_id');
        $yp_id = !empty($yp_ID) ? $yp_ID : '';
        $data['yp_id'] = $yp_id;

        $getFromDate = $this->input->post('from_date');
        $from_date = !empty($getFromDate) ? $getFromDate : '';
        $data['from_date'] = $from_date;

        $getToDate = $this->input->post('to_date');
        $ToDate = !empty($getToDate) ? $getToDate : '';
        $data['to_date'] = $ToDate;

        $careHome = $this->input->post('care_home');
        $care_home = !empty($careHome) ? $careHome : '';
        $data['care_home'] = $care_home;
                
        $location = $this->input->post('location');
        $location_data = !empty($location) ? $location : '';
        $data['location'] = $location_data;


        $tableName = AAI_MAIN . ' as aai';

        $whereCond = 'aai.is_deleted = "0" ';

			if (!empty($data['yp_id'])) {
                $whereCond .= ' AND aai.yp_id = ' . $data['yp_id'];
            }
			
			if (!empty($data['care_home'])) {
                $whereCond .= ' AND aai.care_home_id = ' . $data['care_home'];
            }



            if (!empty($data['from_date'])) {
                $whereCond .= ' AND aai.created_date >= "' . dateformat($data['from_date']) . '"';
            }

            if (!empty($data['to_date'])) {
                $whereCond .= ' AND aai.created_date <= "' . dateformat($data['to_date']) . '"';
            }

            // if(!empty($data['to_date']) && !empty($data['from_date'])){
            //     $whereCond .= ' AND aai.created_date BETWEEN  "' . dateformat($data['from_date']).'" AND "'.dateformat($data['to_date']).'"';
            // }

            if (!empty($data['location'])) {
                $whereCond .= ' AND aai.created_date <= "' . $data['location'] . '"';
            }



     $data['care_home_data'] = array();       
    /* if($this->input->post('care_home') > 0){
        $whereCond .= ' AND aai.care_home_id = ' . $data['care_home'];
        $fieldsRecord = array("incident_id,l7_form_data,l7_reference_number");
        $data['care_home_data'] = $this->common_model->get_records($tableName, $fieldsRecord, '', '', $whereCond);
    } */

        //$whereCond_data = 'aai.is_deleted = "0" ';
        //$fieldsRecord = array("l7_form_data");
        $data['record_details'] = $this->common_model->get_records($tableName, '', '', '', $whereCond);
		
	
	$total_count2 = array();
	$l1_form_status=array();
	$l2_3_form_status=array();
	$l4_form_status=array();
	$l5_form_status=array();
	$l6_form_status=array();
	$l7_form_status=array();
	$l8_form_status=array();
	$l9_form_status=array();
	$listed_dta=array();
        foreach ($data['record_details'] as $value) {
			
			//echo $value['incident_id']."</br>";
			$each_entry_data_l1 = json_decode($value['l1_form_data'], true);
			if(!empty($each_entry_data_l1)){
			foreach($each_entry_data_l1 as $key=>$l1data){
				
				//pr($l4data['name']);
				if(isset($l1data['type']) && $l1data['type']=='select' && $l1data['name']=='form_status' && $l1data['value']=='11'){
					//pr($l4data['value']);
					//echo $l1data['name'];
					//echo "l1 1</br>";
					$l1_form_status[$value['incident_id']]=1;
					$listed_dta[$value['incident_id']]['l1']=1;
				}
				
			}}
			
			$each_entry_data_l2_3 = json_decode($value['l2_l3_form_data'], true);
			if(!empty($each_entry_data_l2_3)){
			foreach($each_entry_data_l2_3 as $key=>$l2_3data){
				
				//pr($l4data['name']);
				if(isset($l2_3data['type']) && $l2_3data['type']=='select' && $l2_3data['name']=='form_status' && $l2_3data['value']=='11'){
					//pr($l4data['value']);
					//echo "l2_3data 1</br>";
					$l2_3_form_status[]=$l2_3data['value'];
					$listed_dta[$value['incident_id']]['l2_3']=1;
				}
				
			}}
			
			
			$each_entry_data_l4 = json_decode($value['l4_form_data'], true);
			
			//pr($each_entry_data);
			if(!empty($each_entry_data_l4)){
			foreach($each_entry_data_l4 as $key=>$l4data){
				
				//pr($l4data['name']);
				if(isset($l4data['type']) && $l4data['type']=='select' && $l4data['name']=='form_status' && $l4data['value']=='11'){
					//pr($l4data['value']);
					//echo "l4 1</br>";
					$l4_form_status[]=$l4data['value'];
					$listed_dta[$value['incident_id']]['l4']=1;
				}
				
			}}
			$each_entry_data_l5 = json_decode($value['l5_form_data'], true);
			if(!empty($each_entry_data_l5)){
			foreach($each_entry_data_l5 as $key=>$l5data){
				
				//pr($l4data['name']);
				if(isset($l5data['type']) && $l5data['type']=='select' && $l5data['name']=='form_status' && $l5data['value']=='11'){
					//pr($l4data['value']);
					//echo "each_entry_data_l5 1 </br>";
					$l5_form_status[]=$l5data['value'];
					$listed_dta[$value['incident_id']]['l5']=1;
				}
				
			}}
			
			$each_entry_data_l6 = json_decode($value['l6_form_data'], true);
			if(!empty($each_entry_data_l6)){
			foreach($each_entry_data_l6 as $key=>$l6data){
				
				//pr($l4data['name']);
				if(isset($l6data['type']) && $l6data['type']=='select' && $l6data['name']=='form_status' && $l6data['value']=='11'){
					//pr($l4data['value']);
					//echo "each_entry_data_l6 1 </br>";
					$l6_form_status[]=$l6data['value'];
					$listed_dta[$value['incident_id']]['l6']=1;
				}
				
			}}
			$each_entry_data_l7 = json_decode($value['l7_form_data'], true);
			if(!empty($each_entry_data_l7)){
			foreach($each_entry_data_l7 as $key=>$l7data){
				
				//pr($l4data['name']);
				if(isset($l7data['type']) && $l7data['type']=='select' && $l7data['name']=='form_status' && $l7data['value']=='11'){
					//pr($l4data['value']);
					//echo "each_entry_data_l7 1</br>";
					$l7_form_status[]=$l7data['value'];
					$listed_dta[$value['incident_id']]['l7']=1;
				}
				
			}}
			
			
			$each_entry_data_l8 = json_decode($value['l8_form_data'], true);
			if(!empty($each_entry_data_l8)){
			foreach($each_entry_data_l8 as $key=>$l8data){
				
				//pr($l4data['name']);
				if(isset($l8data['type']) && $l8data['type']=='select' && $l8data['name']=='form_status' && $l8data['value']=='11'){
					//pr($l4data['value']);
					//echo "l8data 1 </br>";
					$l8_form_status[]=$l8data['value'];
					$listed_dta[$value['incident_id']]['l8']=1;
				}
				
			}}
			
			$each_entry_data_l9 = json_decode($value['l9_form_data'], true);
				if(!empty($each_entry_data_l9)){
			foreach($each_entry_data_l9 as $key=>$l9data){
				
				//pr($l4data['name']);
				if(isset($l9data['type']) && $l9data['type']=='select' && $l9data['name']=='form_status' && $l9data['value']=='11'){
					//pr($l4data['value']);
					//echo "each_entry_data_l9 1</br>";
					$l9_form_status[]=$l9data['value'];
					$listed_dta[$value['incident_id']]['l9']=1;
				}
				
				}}
            
        }
		
		
		 /* pr($total_count2);
					die;  */
		



$data_count = array();
    if(!empty($data['care_home_data'])){
    foreach ($data['care_home_data'] as $value) {
            if(!empty($value['l7_form_data'])){
                    
                        $editl7Data = json_decode($value['l7_form_data'], true);
                        $data_count[] = $editl7Data['l7_total_duration'];        
            }
        }
    }
        
        $total_durationl7 = count($total_count2);     
        $record_details = $total_durationl7;

        $care_home = array_sum($data_count);
        echo json_encode(array('count_data' => count($listed_dta),'care_home' =>$care_home));
    }
	
	/*Management report start*/
	public function comparison_of_number_of_incident_between_care_homes(){
        
		
		$care_home=$this->input->post('care_home');
       if(count($care_home)>0)
	   {
		   foreach($care_home as $cdata)
		   {
			   $variable[]=$this->generateGraphData($cdata);
		   }
	   }
	   $xaxis=array();
	   $series=array();
	   if(isset($variable))
             foreach($variable as $vdata)
			 {
				 $xaxis[]=$vdata['xaxisdata'];
				 $series[]=$vdata['seriesdata'][0];
				
			 }
			
			$newvar=array('xaxisdata'=>$xaxis,'seriesdata'=>$series);
			//	pr($newvar);
				
        echo json_encode($newvar);
        
        
    }
	
	public function generateGraphData($cdata)
	{
		 /*for Number of Incidents by Type (Level)*/
        /*x-axis: Number of incidents  y-axis: Type of Incidents*/
		
        $care_home=$this->input->post('care_home');
		
		
        $from_date=$this->input->post('from_date');
        $to_date=$this->input->post('to_date');
        //pr($_POST);
        $data['footerJs'][0]    = base_url('uploads/custom/js/AAIReport/AAIReportManagement.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');
        /*
        {xaxisdata: ["31-01"], seriesdata: [{name: "parent", data: [49]}]}
        seriesdata: [{name: "parent", data: [49]}]
        xaxisdata: ["31-01"]
        */
        
        /*
        {xaxisdata: ["25", "35"], seriesdata: ["50", "60"]}
        seriesdata: ["50", "60"]
        xaxisdata: ["25", "35"]
        */
        /*this count is for L2&3(Was physical intervention used?)*/
        $whereCond_pi = 'ai.is_deleted = "0"';
        $whereCond_yp_injured ='ai.is_deleted = "0" AND ai.is_yp_injured =1';
        $where_is_yp_safeguarding= 'ai.is_deleted = "0" AND ai.is_yp_safeguarding =1';
        $where_is_other_injured= 'ai.is_deleted = "0" AND ai.is_other_injured =1';
        $where_is_yp_missing= 'ai.is_deleted = "0" AND ai.is_yp_missing =1';
        $where_is_yp_complaint ='ai.is_deleted = "0" AND ai.is_yp_complaint =1';
        $where_is_staff_injured= 'ai.is_deleted = "0" AND ai.is_staff_injured =1';
            if (!empty($from_date)) {
                $whereCond_pi .= ' AND ai.created_date >= "' . $from_date . '"';
                $whereCond_yp_injured .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_yp_safeguarding .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_other_injured .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_yp_missing .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_yp_complaint .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_staff_injured .= ' AND ai.created_date >= "' . $from_date . '"';
            }
            if (!empty($to_date)) {
                $whereCond_pi .= ' AND ai.created_date <= "' . $to_date . '"';
                $whereCond_yp_injured .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_yp_safeguarding .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_other_injured .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_yp_missing .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_yp_complaint .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_staff_injured .= ' AND ai.created_date <= "' . $to_date . '"';
            }
            if (!empty($cdata)) {
				
					$whereCond_pi .= ' AND ai.care_home_id = "' . $cdata . '"';
                $whereCond_yp_injured .= ' AND ai.care_home_id = "' . $cdata . '"';
                $where_is_yp_safeguarding .= ' AND ai.care_home_id = "' . $cdata . '"';
                $where_is_other_injured .= ' AND ai.care_home_id = "' . $cdata . '"';
                $where_is_yp_missing .= ' AND ai.care_home_id = "' . $cdata . '"';
                $where_is_yp_complaint .= ' AND ai.care_home_id = "' . $cdata . '"';
                $where_is_staff_injured .= ' AND ai.care_home_id = "' . $cdata . '"';
					
				
                
            }
        
        $table         = AAI_MAIN . ' as ai';
        //$where                          = "ai.is_pi =1";  
        $fields                         = array('count(*) as count');
        $l1count_data         = $this->common_model->get_records($table, $fields, '', '', $whereCond_pi, '', '', '', '', '', '', '');
		
		/* echo $this->db->last_query();
		die; */
        
        /*this count is for L5(Was the YP injured?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_yp_injured" => "1");
        $fields                         = array('count(*) as count');
        $l5count_data         = $this->common_model->get_records($table, $fields, '', '', $whereCond_yp_injured, '', '', '', '', '', '', '');
        
        /*this count is for L7(Is any part of this incident a safeguarding concern?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_yp_safeguarding    " => "1");
        $fields                         = array('count(*) as count');
        $l7count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_yp_safeguarding, '', '', '', '', '', '', '');
        
        /*this count is for L9(Was anyone else injured?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_other_injured" => "1");
        $fields                         = array('count(*) as count');
        $l9count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_other_injured, '', '', '', '', '', '', '');
        
        /*this count is for L4(Did YP go missing?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_yp_missing" => "1");
        $fields                         = array('count(*) as count');
        $l4count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_yp_missing, '', '', '', '', '', '', '');
        
        /*this count is for L6(Has a complaint been made either by the YP or on behalf of the YP?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_yp_complaint" => "1");
        $fields                         = array('count(*) as count');
        $l6count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_yp_complaint, '', '', '', '', '', '', '');
        
        /*this count is for L8(Was a staff member injured?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_staff_injured" => "1");
        $fields                         = array('count(*) as count');
        $l8count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_staff_injured, '', '', '', '', '', '', '');
        
        $countdata=array((int)$l1count_data[0]['count'],(int)$l5count_data[0]['count'],(int)$l7count_data[0]['count'],(int)$l9count_data[0]['count'],(int)$l4count_data[0]['count'],(int)$l6count_data[0]['count'],(int)$l8count_data[0]['count']);
        
        //$countdata=array(41,31,1,12,11,1,91);
         
        $value[]=array('name'=>$cdata,'data'=>$countdata);
        
        $variable = array('xaxisdata' => $cdata,
            'seriesdata' => $value);
			return $variable ; 
	}
	
	public function number_of_type_of_incident_by_yp_over(){
        
        /*for Number of Incidents by Type (Level)*/
        /*x-axis: Number of incidents  y-axis: Type of Incidents*/
        $care_home=$this->input->post('care_home');
        $yp_id=$this->input->post('yp_id');
        $locationdata=$this->input->post('locationdata');
        $from_date=$this->input->post('from_date');
        $to_date=$this->input->post('to_date');
        
        $data['footerJs'][0]    = base_url('uploads/custom/js/AAIReport/AAIReportManagement.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');
        /*
        {xaxisdata: ["31-01"], seriesdata: [{name: "parent", data: [49]}]}
        seriesdata: [{name: "parent", data: [49]}]
        xaxisdata: ["31-01"]
        */
        
        /*
        {xaxisdata: ["25", "35"], seriesdata: ["50", "60"]}
        seriesdata: ["50", "60"]
        xaxisdata: ["25", "35"]
        */
        /*this count is for L2&3(Was physical intervention used?)*/
        $whereCond_pi = 'ai.is_deleted = "0" AND ai.is_pi =1';
        $whereCond_yp_injured ='ai.is_deleted = "0" AND ai.is_yp_injured =1';
        $where_is_yp_safeguarding= 'ai.is_deleted = "0" AND ai.is_yp_safeguarding =1';
        $where_is_other_injured= 'ai.is_deleted = "0" AND ai.is_other_injured =1';
        $where_is_yp_missing= 'ai.is_deleted = "0" AND ai.is_yp_missing =1';
        $where_is_yp_complaint ='ai.is_deleted = "0" AND ai.is_yp_complaint =1';
        $where_is_staff_injured= 'ai.is_deleted = "0" AND ai.is_staff_injured =1';
        
        if (!empty($yp_id)) {
                $whereCond_pi .= ' AND ai.yp_id = ' . $yp_id;
                $whereCond_yp_injured .= ' AND ai.yp_id = ' . $yp_id;
                $where_is_yp_safeguarding .= ' AND ai.yp_id = ' . $yp_id;
                $where_is_other_injured .= ' AND ai.yp_id = ' . $yp_id;
                $where_is_yp_missing .= ' AND ai.yp_id = ' . $yp_id;
                $where_is_yp_complaint .= ' AND ai.yp_id = ' . $yp_id;
                $where_is_staff_injured .= ' AND ai.yp_id = ' . $yp_id;
            }
            if (!empty($from_date)) {
                $whereCond_pi .= ' AND ai.created_date >= "' . $from_date . '"';
                $whereCond_yp_injured .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_yp_safeguarding .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_other_injured .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_yp_missing .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_yp_complaint .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_staff_injured .= ' AND ai.created_date >= "' . $from_date . '"';
            }
            if (!empty($to_date)) {
                $whereCond_pi .= ' AND ai.created_date <= "' . $to_date . '"';
                $whereCond_yp_injured .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_yp_safeguarding .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_other_injured .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_yp_missing .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_yp_complaint .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_staff_injured .= ' AND ai.created_date <= "' . $to_date . '"';
            }
            if (!empty($care_home)) {
                $whereCond_pi .= ' AND ai.care_home_id = "' . $care_home . '"';
                $whereCond_yp_injured .= ' AND ai.care_home_id = "' . $care_home . '"';
                $where_is_yp_safeguarding .= ' AND ai.care_home_id = "' . $care_home . '"';
                $where_is_other_injured .= ' AND ai.care_home_id = "' . $care_home . '"';
                $where_is_yp_missing .= ' AND ai.care_home_id = "' . $care_home . '"';
                $where_is_yp_complaint .= ' AND ai.care_home_id = "' . $care_home . '"';
                $where_is_staff_injured .= ' AND ai.care_home_id = "' . $care_home . '"';
            }
        
        $table         = AAI_MAIN . ' as ai';
        //$where                          = "ai.is_pi =1";  
        $fields                         = array('count(*) as count');
        $l1count_data         = $this->common_model->get_records($table, $fields, '', '', $whereCond_pi, '', '', '', '', '', '', '');
		
        /*this count is for L5(Was the YP injured?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_yp_injured" => "1");
        $fields                         = array('count(*) as count');
        $l5count_data         = $this->common_model->get_records($table, $fields, '', '', $whereCond_yp_injured, '', '', '', '', '', '', '');
        
        /*this count is for L7(Is any part of this incident a safeguarding concern?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_yp_safeguarding    " => "1");
        $fields                         = array('count(*) as count');
        $l7count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_yp_safeguarding, '', '', '', '', '', '', '');
        
        /*this count is for L9(Was anyone else injured?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_other_injured" => "1");
        $fields                         = array('count(*) as count');
        $l9count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_other_injured, '', '', '', '', '', '', '');
        
        /*this count is for L4(Did YP go missing?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_yp_missing" => "1");
        $fields                         = array('count(*) as count');
        $l4count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_yp_missing, '', '', '', '', '', '', '');
        
        /*this count is for L6(Has a complaint been made either by the YP or on behalf of the YP?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_yp_complaint" => "1");
        $fields                         = array('count(*) as count');
        $l6count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_yp_complaint, '', '', '', '', '', '', '');
        
        /*this count is for L8(Was a staff member injured?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_staff_injured" => "1");
        $fields                         = array('count(*) as count');
        $l8count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_staff_injured, '', '', '', '', '', '', '');
        
        $countdata=array((int)$l1count_data[0]['count'],(int)$l5count_data[0]['count'],(int)$l7count_data[0]['count'],(int)$l9count_data[0]['count'],(int)$l4count_data[0]['count'],(int)$l6count_data[0]['count'],(int)$l8count_data[0]['count']);
        
        //$countdata=array(41,31,1,12,11,1,91);
         
        $value[]=array('name'=>'Count','data'=>$countdata);
        
        $variable = array('xaxisdata' => array("Was physical intervention used?
","Was the YP injured?
","Is any part of this incident a safeguarding concern?
",
"Was anyone else injured?",
"Did YP go missing?",
"Has a complaint been made either by the YP or on behalf of the YP?",
"Was a staff member injured?"
),
            'seriesdata' => $value);
            /* pr($variable);
            die; */

        echo json_encode($variable);
        
        
    }
	
	public function numner_and_level_of_incidents_by_staff_member_over_time(){
        
        /*for Number of Incidents by Type (Level)*/
        /*x-axis: Number of incidents  y-axis: Type of Incidents*/
        $staff_member=$this->input->post('staff_member');
        $from_date=$this->input->post('from_date');
        $to_date=$this->input->post('to_date');
        
        $data['footerJs'][0]    = base_url('uploads/custom/js/AAIReport/AAIReportManagement.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');
        /*
        {xaxisdata: ["31-01"], seriesdata: [{name: "parent", data: [49]}]}
        seriesdata: [{name: "parent", data: [49]}]
        xaxisdata: ["31-01"]
        */
        
        /*
        {xaxisdata: ["25", "35"], seriesdata: ["50", "60"]}
        seriesdata: ["50", "60"]
        xaxisdata: ["25", "35"]
        */
        /*this count is for L2&3(Was physical intervention used?)*/
        $whereCond_pi = 'ai.is_deleted = "0" AND ai.is_pi =1';
        $whereCond_yp_injured ='ai.is_deleted = "0" AND ai.is_yp_injured =1';
        $where_is_yp_safeguarding= 'ai.is_deleted = "0" AND ai.is_yp_safeguarding =1';
        $where_is_other_injured= 'ai.is_deleted = "0" AND ai.is_other_injured =1';
        $where_is_yp_missing= 'ai.is_deleted = "0" AND ai.is_yp_missing =1';
        $where_is_yp_complaint ='ai.is_deleted = "0" AND ai.is_yp_complaint =1';
        $where_is_staff_injured= 'ai.is_deleted = "0" AND ai.is_staff_injured =1';
        
       
            if (!empty($from_date)) {
                $whereCond_pi .= ' AND ai.created_date >= "' . $from_date . '"';
                $whereCond_yp_injured .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_yp_safeguarding .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_other_injured .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_yp_missing .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_yp_complaint .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_staff_injured .= ' AND ai.created_date >= "' . $from_date . '"';
            }
            if (!empty($to_date)) {
                $whereCond_pi .= ' AND ai.created_date <= "' . $to_date . '"';
                $whereCond_yp_injured .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_yp_safeguarding .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_other_injured .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_yp_missing .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_yp_complaint .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_staff_injured .= ' AND ai.created_date <= "' . $to_date . '"';
            }
            if (!empty($staff_member)) {
                $whereCond_pi .= ' AND ai.created_by = "' . $staff_member . '"';
                $whereCond_yp_injured .= ' AND ai.created_by = "' . $staff_member . '"';
                $where_is_yp_safeguarding .= ' AND ai.created_by = "' . $staff_member . '"';
                $where_is_other_injured .= ' AND ai.created_by = "' . $staff_member . '"';
                $where_is_yp_missing .= ' AND ai.created_by = "' . $staff_member . '"';
                $where_is_yp_complaint .= ' AND ai.created_by = "' . $staff_member . '"';
                $where_is_staff_injured .= ' AND ai.created_by = "' . $staff_member . '"';
            }
        
        $table         = AAI_MAIN . ' as ai';
        //$where                          = "ai.is_pi =1";  
        $fields                         = array('count(*) as count');
        $l1count_data         = $this->common_model->get_records($table, $fields, '', '', $whereCond_pi, '', '', '', '', '', '', '');
		
        /*this count is for L5(Was the YP injured?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_yp_injured" => "1");
        $fields                         = array('count(*) as count');
        $l5count_data         = $this->common_model->get_records($table, $fields, '', '', $whereCond_yp_injured, '', '', '', '', '', '', '');
        
        /*this count is for L7(Is any part of this incident a safeguarding concern?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_yp_safeguarding    " => "1");
        $fields                         = array('count(*) as count');
        $l7count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_yp_safeguarding, '', '', '', '', '', '', '');
        
        /*this count is for L9(Was anyone else injured?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_other_injured" => "1");
        $fields                         = array('count(*) as count');
        $l9count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_other_injured, '', '', '', '', '', '', '');
        
        /*this count is for L4(Did YP go missing?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_yp_missing" => "1");
        $fields                         = array('count(*) as count');
        $l4count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_yp_missing, '', '', '', '', '', '', '');
        
        /*this count is for L6(Has a complaint been made either by the YP or on behalf of the YP?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_yp_complaint" => "1");
        $fields                         = array('count(*) as count');
        $l6count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_yp_complaint, '', '', '', '', '', '', '');
        
        /*this count is for L8(Was a staff member injured?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_staff_injured" => "1");
        $fields                         = array('count(*) as count');
        $l8count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_staff_injured, '', '', '', '', '', '', '');
        
        $countdata=array((int)$l1count_data[0]['count'],(int)$l5count_data[0]['count'],(int)$l7count_data[0]['count'],(int)$l9count_data[0]['count'],(int)$l4count_data[0]['count'],(int)$l6count_data[0]['count'],(int)$l8count_data[0]['count']);
        
        //$countdata=array(41,31,1,12,11,1,91);
         
        $value[]=array('name'=>'Count','data'=>$countdata);
        
        $variable = array('xaxisdata' => array("Was physical intervention used?
","Was the YP injured?
","Is any part of this incident a safeguarding concern?
",
"Was anyone else injured?",
"Did YP go missing?",
"Has a complaint been made either by the YP or on behalf of the YP?",
"Was a staff member injured?"
),
            'seriesdata' => $value);
            /* pr($variable);
            die; */

        echo json_encode($variable);
        
        
    }
	
	public function number_of_sactions(){
		
		$data['footerJs'][0]    = base_url('uploads/custom/js/AAIReport/AAIReportManagement.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');

        $yp_ID = $this->input->post('yp_id');
        $yp_id = !empty($yp_ID) ? $yp_ID : '';
        $data['yp_id'] = $yp_id;

        $getFromDate = $this->input->post('from_date');
        $from_date = !empty($getFromDate) ? $getFromDate : '';
        $data['from_date'] = $from_date;

        $getToDate = $this->input->post('to_date');
        $ToDate = !empty($getToDate) ? $getToDate : '';
        $data['to_date'] = $ToDate;

        $careHome = $this->input->post('care_home');
        $care_home = !empty($careHome) ? $careHome : '';
        $data['care_home'] = $care_home;
		 if($this->input->post('care_home') > 0){
			 $tableName         = AAI_SANCTIONS . ' as as';
         //$whereCond = 'aai.is_deleted = "0" ';
        $whereCond .= ' AND as.care_home_id = ' . $data['care_home'];
        $fieldsRecord = array("count(*) as count,ch.care_home_name");
         $join_tables = array(CARE_HOME . ' as ch' => 'ch.care_home_id = as.care_home_id');
        $data['care_home_data'] = $this->common_model->get_records($tableName, $fieldsRecord, $join_tables, 'left', '');
			}
			
        $care_home_name = $data['care_home_data'][0]['care_home_name'];
		
		$where_sanction='as.is_deleted=0';
		
		if (!empty($careHome)) {
               
                $where_sanction .= ' AND as.care_home_id = "' . $careHome . '"';
            }
			
			if (!empty($yp_ID)) {
               
                $where_sanction .= ' AND as.yp_id = "' . $yp_ID . '"';
            }
			if (!empty($from_date)) {
                
                $where_sanction .= ' AND as.created_date >= "' . $from_date . '"';
            }
            if (!empty($to_date)) {
                
                $where_sanction .= ' AND as.created_date <= "' . $to_date . '"';
            }
		
		$table         = AAI_SANCTIONS . ' as as';
        //$where                          = array("as.is_staff_injured" => "1");
        $fields                         = array('count(*) as count');
        $aai_sanction_Data         = $this->common_model->get_records($table, $fields, '', '', $where_sanction, '', '', '', '', '', '', '');
		$data_count=$aai_sanction_Data[0]['count'];
		
		 echo json_encode(array('count_data' => $data_count,'care_home' =>$care_home,'care_home_name'=>$care_home_name));
		
		
		
	}
	
	public function number_of_complaints_by_yp(){
        
        /*for Number of Incidents by Type (Level)*/
        /*x-axis: Number of incidents  y-axis: Type of Incidents*/
        $staff_member=$this->input->post('staff_member');
        $from_date=$this->input->post('from_date');
        $to_date=$this->input->post('to_date');
        
        $data['footerJs'][0]    = base_url('uploads/custom/js/AAIReport/AAIReportManagement.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');
        /*
        {xaxisdata: ["31-01"], seriesdata: [{name: "parent", data: [49]}]}
        seriesdata: [{name: "parent", data: [49]}]
        xaxisdata: ["31-01"]
        */
        
        /*
        {xaxisdata: ["25", "35"], seriesdata: ["50", "60"]}
        seriesdata: ["50", "60"]
        xaxisdata: ["25", "35"]
        */
        /*this count is for L2&3(Was physical intervention used?)*/
        $whereCond_pi = 'ai.is_deleted = "0" AND ai.is_pi =1';
        $whereCond_yp_injured ='ai.is_deleted = "0" AND ai.is_yp_injured =1';
        $where_is_yp_safeguarding= 'ai.is_deleted = "0" AND ai.is_yp_safeguarding =1';
        $where_is_other_injured= 'ai.is_deleted = "0" AND ai.is_other_injured =1';
        $where_is_yp_missing= 'ai.is_deleted = "0" AND ai.is_yp_missing =1';
        $where_is_yp_complaint ='ai.is_deleted = "0" AND ai.is_yp_complaint =1';
        $where_is_staff_injured= 'ai.is_deleted = "0" AND ai.is_staff_injured =1';
        
       
            if (!empty($from_date)) {
                $whereCond_pi .= ' AND ai.created_date >= "' . $from_date . '"';
                $whereCond_yp_injured .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_yp_safeguarding .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_other_injured .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_yp_missing .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_yp_complaint .= ' AND ai.created_date >= "' . $from_date . '"';
                $where_is_staff_injured .= ' AND ai.created_date >= "' . $from_date . '"';
            }
            if (!empty($to_date)) {
                $whereCond_pi .= ' AND ai.created_date <= "' . $to_date . '"';
                $whereCond_yp_injured .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_yp_safeguarding .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_other_injured .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_yp_missing .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_yp_complaint .= ' AND ai.created_date <= "' . $to_date . '"';
                $where_is_staff_injured .= ' AND ai.created_date <= "' . $to_date . '"';
            }
            if (!empty($staff_member)) {
                $whereCond_pi .= ' AND ai.created_by = "' . $staff_member . '"';
                $whereCond_yp_injured .= ' AND ai.created_by = "' . $staff_member . '"';
                $where_is_yp_safeguarding .= ' AND ai.created_by = "' . $staff_member . '"';
                $where_is_other_injured .= ' AND ai.created_by = "' . $staff_member . '"';
                $where_is_yp_missing .= ' AND ai.created_by = "' . $staff_member . '"';
                $where_is_yp_complaint .= ' AND ai.created_by = "' . $staff_member . '"';
                $where_is_staff_injured .= ' AND ai.created_by = "' . $staff_member . '"';
            }
        
        $table         = AAI_MAIN . ' as ai';
        //$where                          = "ai.is_pi =1";  
        $fields                         = array('count(*) as count');
        $l1count_data         = $this->common_model->get_records($table, $fields, '', '', $whereCond_pi, '', '', '', '', '', '', '');
		
        /*this count is for L5(Was the YP injured?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_yp_injured" => "1");
        $fields                         = array('count(*) as count');
        $l5count_data         = $this->common_model->get_records($table, $fields, '', '', $whereCond_yp_injured, '', '', '', '', '', '', '');
        
        /*this count is for L7(Is any part of this incident a safeguarding concern?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_yp_safeguarding    " => "1");
        $fields                         = array('count(*) as count');
        $l7count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_yp_safeguarding, '', '', '', '', '', '', '');
        
        /*this count is for L9(Was anyone else injured?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_other_injured" => "1");
        $fields                         = array('count(*) as count');
        $l9count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_other_injured, '', '', '', '', '', '', '');
        
        /*this count is for L4(Did YP go missing?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_yp_missing" => "1");
        $fields                         = array('count(*) as count');
        $l4count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_yp_missing, '', '', '', '', '', '', '');
        
        /*this count is for L6(Has a complaint been made either by the YP or on behalf of the YP?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_yp_complaint" => "1");
        $fields                         = array('count(*) as count');
        $l6count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_yp_complaint, '', '', '', '', '', '', '');
        
        /*this count is for L8(Was a staff member injured?)*/
        $table         = AAI_MAIN . ' as ai';
        //$where                          = array("ai.is_staff_injured" => "1");
        $fields                         = array('count(*) as count');
        $l8count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_staff_injured, '', '', '', '', '', '', '');
        
        $countdata=array((int)$l1count_data[0]['count'],(int)$l5count_data[0]['count'],(int)$l7count_data[0]['count'],(int)$l9count_data[0]['count'],(int)$l4count_data[0]['count'],(int)$l6count_data[0]['count'],(int)$l8count_data[0]['count']);
        
        //$countdata=array(41,31,1,12,11,1,91);
         
        $value[]=array('name'=>'Count','data'=>$countdata);
        
        $variable = array('xaxisdata' => array("Was physical intervention used?
","Was the YP injured?
","Is any part of this incident a safeguarding concern?
",
"Was anyone else injured?",
"Did YP go missing?",
"Has a complaint been made either by the YP or on behalf of the YP?",
"Was a staff member injured?"
),
            'seriesdata' => $value);
            /* pr($variable);
            die; */

        echo json_encode($variable);
        
        
    }
	
	
	public function number_of_complaints_by_carehome(){
        
        /*for Number of Incidents by Type (Level)*/
        /*x-axis: Number of incidents  y-axis: Type of Incidents*/
        $care_home=$this->input->post('care_home');
        $from_date=$this->input->post('from_date');
        $to_date=$this->input->post('to_date');
        
        $data['footerJs'][0]    = base_url('uploads/custom/js/AAIReport/AAIReportManagement.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');
        /*
        {xaxisdata: ["31-01"], seriesdata: [{name: "parent", data: [49]}]}
        seriesdata: [{name: "parent", data: [49]}]
        xaxisdata: ["31-01"]
        */
        
        /*
        {xaxisdata: ["25", "35"], seriesdata: ["50", "60"]}
        seriesdata: ["50", "60"]
        xaxisdata: ["25", "35"]
        */
        /*this count is for L2&3(Was physical intervention used?)*/
        
        $where_is_yp_complaint ='ai.is_deleted = "0" AND ai.is_yp_complaint =1';
      
        
       
            if (!empty($from_date)) {
                
                $where_is_yp_complaint .= ' AND ai.created_date >= "' . $from_date . '"';
                
            }
            if (!empty($to_date)) {
                
                $where_is_yp_complaint .= ' AND ai.created_date <= "' . $to_date . '"';
                
            }
            if (!empty($care_home)) {
                
                $where_is_yp_complaint .= ' AND ai.care_home_id = "' . $care_home . '"';
                
            }
			$tableName = AAI_MAIN . ' as aai';
			$whereCond = 'aai.is_deleted = "0" ';
        $whereCond .= ' AND aai.care_home_id = ' . $care_home;
        $fieldsRecord = array("count(*) as count,ch.care_home_name");
         $join_tables = array(CARE_HOME . ' as ch' => 'ch.care_home_id = aai.care_home_id');
        $data['care_home_data'] = $this->common_model->get_records($tableName, $fieldsRecord, $join_tables, 'left', $whereCond);
        
        $table         = AAI_MAIN . ' as ai';
        //$where                          = "ai.is_pi =1";  
        $fields                         = array('count(*) as count');
        $l1count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_yp_complaint, '', '', '', '', '', '', '');
		
        
        
        $countdata=array((int)$l1count_data[0]['count']);
        
        //$countdata=array(41,31,1,12,11,1,91);
         $care_home_name=$data['care_home_data']['0']['care_home_name'];
        $value[]=array('name'=>'Count','data'=>$countdata);
        
        $variable = array('xaxisdata' => array($care_home_name),
            'seriesdata' => $value);
            /* pr($variable);
            die; */

        echo json_encode($variable);
        
        
    }
	
	public function numer_of_safeguarding_occurences_by_yp_and_carehome(){
        
        /*for Number of Incidents by Type (Level)*/
        /*x-axis: Number of incidents  y-axis: Type of Incidents*/
        $care_home=$this->input->post('care_home');
        $yp_ID=$this->input->post('yp_id');
        $from_date=$this->input->post('from_date');
        $to_date=$this->input->post('to_date');
        
        $data['footerJs'][0]    = base_url('uploads/custom/js/AAIReport/AAIReportManagement.js');
        $data['footerJs'][1] = base_url('uploads/highchart/highcharts.js');
        $data['footerJs'][2] = base_url('uploads/highchart/exporting.js');
        /*
        {xaxisdata: ["31-01"], seriesdata: [{name: "parent", data: [49]}]}
        seriesdata: [{name: "parent", data: [49]}]
        xaxisdata: ["31-01"]
        */
        
        /*
        {xaxisdata: ["25", "35"], seriesdata: ["50", "60"]}
        seriesdata: ["50", "60"]
        xaxisdata: ["25", "35"]
        */
        /*this count is for L2&3(Was physical intervention used?)*/
        
        
		$where_is_yp_safeguarding= 'ai.is_deleted = "0" AND ai.is_yp_safeguarding =1';
      
        
       
            if (!empty($from_date)) {
                
                $where_is_yp_safeguarding .= ' AND ai.created_date >= "' . $from_date . '"';
                
            }
            if (!empty($to_date)) {
                
                $where_is_yp_safeguarding .= ' AND ai.created_date <= "' . $to_date . '"';
                
            }
            if (!empty($care_home)) {
                
                $where_is_yp_safeguarding .= ' AND ai.care_home_id = "' . $care_home . '"';
                
            }
			if (!empty($yp_ID)) {
                
                $where_is_yp_safeguarding .= ' AND ai.yp_id = "' . $yp_ID . '"';
                
            }
			$tableName = AAI_MAIN . ' as aai';
			$whereCond = 'aai.is_deleted = "0" ';
        $whereCond .= ' AND aai.care_home_id = ' . $care_home;
        $fieldsRecord = array("count(*) as count,ch.care_home_name");
         $join_tables = array(CARE_HOME . ' as ch' => 'ch.care_home_id = aai.care_home_id');
        $data['care_home_data'] = $this->common_model->get_records($tableName, $fieldsRecord, $join_tables, 'left', $whereCond);
        
        $table         = AAI_MAIN . ' as ai';
        //$where                          = "ai.is_pi =1";  
        $fields                         = array('count(*) as count');
        $l1count_data         = $this->common_model->get_records($table, $fields, '', '', $where_is_yp_safeguarding, '', '', '', '', '', '', '');
		
      
        $countdata=array((int)$l1count_data[0]['count']);
        
        //$countdata=array(41,31,1,12,11,1,91);
         $care_home_name=$data['care_home_data']['0']['care_home_name'];
        $value[]=array('name'=>'Count','data'=>$countdata);
        
        $variable = array('xaxisdata' => array($care_home_name),
            'seriesdata' => $value);
            /* pr($variable);
            die; */

        echo json_encode($variable);
        
        
    }



}