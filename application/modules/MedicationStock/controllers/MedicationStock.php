<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MedicationStock extends CI_Controller {

    function __construct() {

        parent::__construct();
        if (checkPermission('MedicationStock', 'view') == false) {
            redirect('/Dashboard');
        }
        $this->load->model('imageupload_model');
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
    }

    /*
     @Author : Niral Patel
     @Desc   : Medication stock check
     @Input     : Post id from List page
     @Output    : Delete data from database and redirect
     @Date   : 16/05/2018
    */
    public function medicationStockCheck($care_home_id){
		$data['care_home_id']=$care_home_id;

        if(is_numeric($care_home_id)){
        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
		$data['ypid']=$searchtext;
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('medication_stock_check');
        }

        $searchsort_session = $this->session->userdata('medication_stock_check');
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
                $sortfield = 'medication_id';
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
        $config['base_url'] = base_url() . $this->viewname . '/medicationStockCheck/'.$care_home_id;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        //Query
        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = MEDICATION . ' as m';
        $where_seaech = array("mch.care_home_id"=>$care_home_id,'mch.is_archive'=>0,"yp.is_archive" => '0');
        $fields = array("m.medication_id,m.medication_type,m.reason,m.doseage,m.medication_name,CONCAT(`yp_fname`,' ', `yp_lname`) as name,yp.yp_fname,m.care_home_id,yp.yp_lname,m.created_date,CONCAT(l.firstname,' ', l.lastname) as stock_checked_name_first,CONCAT(l1.firstname,' ', l1.lastname) as stock_checked_name_second,sum(quantity_left) as total_given,m.stock,m.comment_first,m.comment_second,m.stock_check_count,m.stock_checked_date,m.stock_checked_date_second,(select count(stock_check_id) from nfc_daily_stock_check where (quantity_type = 3 or quantity_type is null) and medical_care_home_id = mch.medical_care_home_id and DATE_FORMAT(stock_checked_date, '%Y-%m-%d') = '".date('Y-m-d')."') as totalcheck,mch.medical_care_home_id,mch.care_home_id,mch.medical_care_home_id");
        $join_tables = array(YP_DETAILS . ' as yp' => 'yp.yp_id= m.yp_id',ADMINISTER_MEDICATION . ' as mc' => 'm.medication_id= mc.select_medication',LOGIN . ' as l' => 'l.login_id=m.stock_checked_by',LOGIN . ' as l1' => 'l1.login_id=m.stock_checked_by_second',
            MEDICAL_CARE_HOME_TRANSECTION . ' as mch' => 'm.medication_id= mch.medication_id');
        if (!empty($searchtext)) {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = array('m.yp_id'=>$searchtext);
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match, '', $config['per_page'], $uri_segment, $sortfield, $sortby, 'm.medication_id', $where_seaech);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match, '', '', '', $sortfield, $sortby, 'm.medication_id', $where_seaech, '', '', '1');
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, 'm.medication_id', $where_seaech);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, 'm.medication_id', $where_seaech, '', '', '1');
        }
        //get YP information
        $match = array("is_deleted"=>'0',"care_home"=>$care_home_id, "is_archive" => '0');
        $fields = array("yp.yp_id,yp.yp_fname,yp.yp_lname");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS.' as yp', $fields,'', '', $match);

        $data['care_home_id'] = $care_home_id;
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

        $this->session->set_userdata('medication_stock_check', $sortsearchpage_data);
        setActiveSession('medication_stock_check'); // set current Session active
        $data['header'] = array('menu_module' => 'Communication');

        //get communication form
        $match = array('m_form_id'=> 1);
        $formsdata = $this->common_model->get_records(M_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/medical_stock/medical_stock.js');
        $data['header'] = array('menu_module' => 'MedicationStock');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/medication_ajaxlist', $data);
        } else {
            $data['main_content'] = '/medication';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
       }else{
            show_404 ();
       } 
    }
    /*
     @Author : Niral Patel
     @Desc   : Medication archive stock check
     @Input     : Post id from List page
     @Output    : Delete data from database and redirect
     @Date   : 16/05/2018
     */
    public function medicationArchiveStock($care_home_id) {
        if(is_numeric($care_home_id)){
        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('medication_arstock_check');
        }

        $searchsort_session = $this->session->userdata('medication_arstock_check');
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
                $sortfield = 'medication_id';
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
        $config['base_url'] = base_url() . $this->viewname . '/medicationArchiveStock/'.$care_home_id;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        //Query

        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        
         $table = MEDICATION . ' as m';
        $where = array("mch.care_home_id"=>$care_home_id,'mc.record_medication_offered_but_refused'=>NULL,'mch.is_archive'=>1);
        $fields = array("m.medication_id,m.medication_type,m.reason,m.doseage,m.medication_name,CONCAT(`yp_fname`,' ', `yp_lname`) as name,yp.yp_fname,m.care_home_id,yp.yp_lname,m.created_date,CONCAT(l.firstname,' ', l.lastname) as stock_checked_name_first,CONCAT(l1.firstname,' ', l1.lastname) as stock_checked_name_second,sum(quantity_left) as total_given,m.stock,m.comment_first,m.comment_second,m.stock_check_count,m.stock_checked_date,m.stock_checked_date_second,(select count(stock_check_id) from nfc_daily_stock_check where (quantity_type = 3 or quantity_type is null) and medication_id = m.medication_id and DATE_FORMAT(stock_checked_date, '%Y-%m-%d') = '".date('Y-m-d')."') as totalcheck,mch.medical_care_home_id,mch.care_home_id,mch.quntity_given,mch.quntity_remaining,mch.medical_care_home_id");
        $join_tables = array(YP_DETAILS . ' as yp' => 'yp.yp_id= m.yp_id',ADMINISTER_MEDICATION . ' as mc' => 'm.medication_id= mc.select_medication',LOGIN . ' as l' => 'l.login_id=m.stock_checked_by',LOGIN . ' as l1' => 'l1.login_id=m.stock_checked_by_second',
            MEDICAL_CARE_HOME_TRANSECTION . ' as mch' => 'm.medication_id= mch.medication_id');
        if (!empty($searchtext)) {
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, 'm.medication_id', $where);
            
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, 'm.medication_id', $where, '', '', '1');
        }
            
        $data['care_home_id'] = $care_home_id;
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

        $this->session->set_userdata('medication_arstock_check', $sortsearchpage_data);
        setActiveSession('medication_arstock_check'); // set current Session active
        $data['header'] = array('menu_module' => 'Communication');
        //get communication form
        $match = array('m_form_id'=> 1);
        $formsdata = $this->common_model->get_records(M_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        $data['ypid'] = '';
        $data['crnt_view'] = $this->viewname;
        $data['header'] = array('menu_module' => 'MedicationStock');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/medication_archive_ajaxlist', $data);
        } else {
            $data['main_content'] = '/medication_archive';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
       }else{
              show_404 ();
        } 
    }
    /*
     @Author : Niral Patel
     @Desc   : Create Medication archive stock check
     @Input     : Post id from List page
     @Output    : Delete data from database and redirect
     @Date   : 16/05/2018
     */
    public function createArchive($medical_care_home_id,$care_home_id)
    {
        //get medicine id
        $match = array('medical_care_home_id'=> $medical_care_home_id);
        $medDaata = $this->common_model->get_records(MEDICAL_CARE_HOME_TRANSECTION,'', '', '', $match);

        //get medication
        $table = MEDICATION . ' as m';
        $where = array("m.medication_id"=>$medDaata[0]['medication_id'],'mc.record_medication_offered_but_refused'=>NULL);
        $fields = array("m.medication_id,sum(quantity_left) as total_given,m.stock");
        $join_tables = array(YP_DETAILS . ' as yp' => 'yp.yp_id= m.yp_id',ADMINISTER_MEDICATION . ' as mc' => 'm.medication_id= mc.select_medication',LOGIN . ' as l' => 'l.login_id=m.stock_checked_by');
       
        $medicationData = $this->common_model->get_records($table, $fields, $join_tables, 'left',$where);
        
        $data['is_archive'] = 1;
        $data['quntity_given'] = number_format($medicationData[0]['total_given'],2);
        $data['quntity_remaining'] = number_format($medicationData[0]['stock'],2);
        $this->common_model->update(MEDICAL_CARE_HOME_TRANSECTION,$data,array('medical_care_home_id'=>$medical_care_home_id));
        $msg = 'Medication Stock Archived successfully.';
        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        redirect('MedicationStock/medicationArchiveStock/'.$care_home_id);
    }
    /*
     @Author : Niral Patel
     @Desc   : Create Medication archive stock check
     @Input     : Post id from List page
     @Output    : Delete data from database and redirect
     @Date   : 16/05/2018
     */
    public function undoArchive($medication_id,$care_home_id)
    {
        $data['is_archive'] = 0;
        $this->common_model->update(MEDICATION,$data,array('medication_id'=>$medication_id));
        $msg = 'Medication Stock Unarchived successfully.';
        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        redirect('MedicationStock/medicationStockCheck/'.$care_home_id);
    }
   
    /*
     @Author : Niral Patel
     @Desc   : checked stock
     @Input     : Post id from List page
     @Output    : 
     @Date   : 20/04/2018
     */
     public function stockCheckLog($medical_care_home_id,$care_home_id) {
        if(is_numeric($medical_care_home_id)){
        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('medication_stock_checked');
        }

        $searchsort_session = $this->session->userdata('medication_stock_checked');
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
                $sortfield = 'mc.stock_check_id';
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
        $config['base_url'] = base_url() . $this->viewname . '/stockCheckLog/'.$medical_care_home_id.'/'.$care_home_id;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 5;
            $uri_segment = $this->uri->segment(5);
        }
        //Query
        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        $table = NFC_DAILY_STOCK_CHECK . ' as mc';
        $where = array("mc.medical_care_home_id"=>$medical_care_home_id);
        $fields = array("mc.medication_id,mc.stock_check_id,m.medication_type,m.medication_name,CONCAT(`yp_fname`,' ', `yp_lname`) as name,yp.yp_fname,m.care_home_id,yp.yp_lname,m.created_date,CONCAT(l.firstname,' ', l.lastname) as stock_checked_name,mc.stock_checked_date,mc.remaing_stock,mc.comment,mc.quantity_type,mc.stock_adjust_value");
        $join_tables = array(YP_DETAILS . ' as yp' => 'yp.yp_id= mc.yp_id',MEDICATION . ' as m' => 'm.medication_id= mc.medication_id',LOGIN . ' as l' => 'l.login_id=mc.stock_checked_by');
        if (!empty($searchtext)){
            
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);

            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
        }
                
        $data['medication_id'] = $medical_care_home_id;
         $data['care_home_id'] = $care_home_id;
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

        $this->session->set_userdata('medication_stock_checked', $sortsearchpage_data);
        setActiveSession('medication_stock_checked'); // set current Session active
        $data['header'] = array('menu_module' => 'Communication');

        
        $data['crnt_view'] = $this->viewname;
        $data['header'] = array('menu_module' => 'MedicationStock');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->viewname . '/checkedstock_ajaxlist', $data);
        } else {
            $data['main_content'] = '/checkedstock';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
       }else{
              show_404 ();
        } 
    }
    /*
     @Author : Niral Patel
     @Desc   : updated checked stock
     @Input     : Post id from List page
     @Output    : 
     @Date   : 17/04/2018
     */
     public function stockComment($medical_care_home_id='')
     {
        $table = MEDICAL_CARE_HOME_TRANSECTION . ' as mch';
    $fields = array('m.medication_name,m.reason,m.stock,m.yp_id,m.medication_id,mch.medical_care_home_id,mch.care_home_id');
        $where = array("mch.medical_care_home_id"=>$medical_care_home_id);
        $joinTables = array(MEDICATION . ' as m' => 'm.medication_id= mch.medication_id');
        $data['medication_data'] = $this->common_model->get_records($table,$fields,$joinTables, 'left', $where, '');
        $data['form_action_path'] = $this->viewname . '/checkedStock';
        $data['header'] = array('menu_module' => 'MedicationStock');
        $data['is_stock_check'] = 1;
        $data['main_content'] = '/addStockComment';
        $this->load->view('/addStockComment', $data);
     }
     /*
     @Author : Niral Patel
     @Desc   : updated checked stock
     @Input     : Post id from List page
     @Output    : 
     @Date   : 17/04/2018
     */
     public function stockAdjustment($medical_care_home_id='')
     {
        $table = MEDICAL_CARE_HOME_TRANSECTION . ' as mch';
        $where = array("mch.medical_care_home_id"=>$medical_care_home_id);
        $fields = array('m.medication_name,m.reason,m.stock,m.yp_id,m.medication_id,mch.medical_care_home_id,mch.care_home_id');
        $joinTables = array(MEDICATION . ' as m' => 'm.medication_id= mch.medication_id');
        $data['medication_data'] = $this->common_model->get_records($table,$fields,$joinTables, 'left', $where, '');
        $data['form_action_path'] = $this->viewname . '/checkedStock';
        $data['header'] = array('menu_module' => 'MedicationStock');
        $data['is_stock_check'] = 0;
        $data['main_content'] = '/addStockComment';
        $this->load->view('/addStockComment', $data);
     }
     /*
     @Author : Niral Patel
     @Desc   : updated checked stock
     @Input     : Post id from List page
     @Output    : 
     @Date   : 17/04/2018
     */
     public function checkedStock()
     {
        //get medication data
        $postdata = $this->input->post();
        $medication_id = $postdata['medication_id'];
        $medical_care_home_id = $postdata['medical_care_home_id'];
        $care_home_id = $postdata['care_home_id'];
        
        $data['remaing_stock'] = (isset($postdata['new_remaing_stock']) && $postdata['new_remaing_stock'] >= 0)?$postdata['new_remaing_stock']:$postdata['remaing_stock'];
        
        $data['comment']            = $postdata['comments'];
        $data['medication_id']      = $postdata['medication_id'];
        $data['medical_care_home_id']      = $postdata['medical_care_home_id'];
        $data['yp_id']              = $postdata['yp_id'];
        $data['stock_checked_date'] = datetimeformat();
        $data['stock_checked_by']   = $this->session->userdata['LOGGED_IN']['ID'];
        
        if(empty($postdata['is_stock_check']))
        {
            if(!empty($postdata['new_quntity']))
            {
                $data['quantity_type']      = $postdata['quantity_type'];
                $data['stock_adjust_value'] = $postdata['new_quntity'];
                $msg = 'Stock adjustment successfully.';
                $this->common_model->insert(NFC_DAILY_STOCK_CHECK,$data);
            }
            else
            {
                $msg = 'You can not stock adjustment with 0 quantity.';
            }
        }
        else
        {
            $data['quantity_type']      = 3;
            $msg = 'Stock checked successfully.';
            $this->common_model->insert(NFC_DAILY_STOCK_CHECK,$data);
        }
        if(isset($postdata['new_remaing_stock']) && $postdata['new_remaing_stock'] >= 0 && !empty($postdata['new_remaing_stock']))
        {
            //update new stock
            $meddata = array('stock' => $postdata['new_remaing_stock']);
            $where = array('medication_id' => $postdata['medication_id']);
            
            $success = $this->common_model->update(MEDICATION, $meddata, $where);
            //get am data
            $match = array('select_medication' => $postdata['medication_id']);
        $administer_medication_last_data = $this->common_model->get_records(ADMINISTER_MEDICATION,array('administer_medication_id,available_stock,quantity_left,record_medication_offered_but_refused'), '', '', $match,'','2','','administer_medication_id','desc');
        
            if(!empty($administer_medication_last_data))
            {
                $amdata['available_stock'] = $postdata['new_remaing_stock'];
                $this->common_model->update(ADMINISTER_MEDICATION,$amdata,array('administer_medication_id'=>$administer_medication_last_data[0]['administer_medication_id']));
                if(isset($administer_medication_last_data[1]['administer_medication_id']) && ($postdata['new_remaing_stock'] != $administer_medication_last_data[1]['available_stock']) && $administer_medication_last_data[1]['record_medication_offered_but_refused'] == 'yes')
                {
                    $amdata['available_stock'] = $postdata['new_remaing_stock'];
                    $this->common_model->update(ADMINISTER_MEDICATION,$amdata,array('administer_medication_id'=>$administer_medication_last_data[1]['administer_medication_id']));
                }
            }

        }
        
        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        redirect('MedicationStock/medicationStockCheck/'.$care_home_id);
    }
     /*
      @Author : Niral Patel
      @Desc   : Read more
      @Input    : yp id
      @Output   :
      @Date   : 05/09/2017
     */
      public function readmore_comment($id,$field)
      {
          if(is_numeric($id)){
            $params['fields'] = [$field];
            $params['table'] = NFC_DAILY_STOCK_CHECK;
            $params['match_and'] = 'stock_check_id=' . $id . '';
            $data['documents'] = $this->common_model->get_records_array($params);
            $data['field'] = $field;
            $this->load->view($this->viewname . '/readmore', $data);
          }else{
              show_404 ();
          }
      }
    /*
      @Author : Niral Patel
      @Desc   : Read more medication comment
      @Input  : medication id
      @Output : 
      @Date   : 05/09/2017
    */
 public function readmore_medication_comment($medication_id)
      {
            $params['fields'] = ['reason'];
            $params['table'] = MEDICATION;
            $params['match_and'] = 'medication_id=' . $medication_id . '';
            $data['documents'] = $this->common_model->get_records_array($params);
            $data['field'] = 'reason';
            $this->load->view($this->viewname . '/readmore', $data);
      } 
      /*
      @Author : Niral Patel
      @Desc   : generateExcel File
      @Input  : 
      @Output : 
      @Date   : 05/09/2017
    */
public function generateExcelFile(){
	 $this->load->library('excel');
        $this->activeSheetIndex = $this->excel->setActiveSheetIndex(0);
		
        //name the worksheet
       $this->excel->getActiveSheet()->setTitle('Medical Stock');
	   $care_home_id = $this->input->get('care_home_id');
	
	 if(is_numeric($care_home_id)){
        $searchtext = '';
        $perpage = '';
        $searchtext =$this->input->post('searchtext');
		$data['ypid']=$searchtext;
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('medication_stock_check');
        }

        $searchsort_session = $this->session->userdata('medication_stock_check');
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
                $sortfield = 'medication_id';
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
        $config['base_url'] = base_url() . $this->viewname . '/medicationStockCheck/'.$care_home_id;

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        //Query

        $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
        
         $table = MEDICATION . ' as m';
        $where = array("mch.care_home_id"=>$care_home_id,'mc.record_medication_offered_but_refused'=>NULL,'mch.is_archive'=>0,"yp.is_archive" => '0');
        $fields = array("m.medication_id,m.medication_type,m.reason,m.doseage,m.medication_name,CONCAT(`yp_fname`,' ', `yp_lname`) as name,yp.yp_fname,m.care_home_id,yp.yp_lname,m.created_date,CONCAT(l.firstname,' ', l.lastname) as stock_checked_name_first,CONCAT(l1.firstname,' ', l1.lastname) as stock_checked_name_second,sum(quantity_left) as total_given,m.stock,m.comment_first,m.comment_second,m.stock_check_count,m.stock_checked_date,m.stock_checked_date_second,(select count(stock_check_id) from nfc_daily_stock_check where (quantity_type = 3 or quantity_type is null) and medical_care_home_id = mch.medical_care_home_id and DATE_FORMAT(stock_checked_date, '%Y-%m-%d') = '".date('Y-m-d')."') as totalcheck,mch.medical_care_home_id,mch.care_home_id,mch.medical_care_home_id");
        $join_tables = array(YP_DETAILS . ' as yp' => 'yp.yp_id= m.yp_id',ADMINISTER_MEDICATION . ' as mc' => 'm.medication_id= mc.select_medication',LOGIN . ' as l' => 'l.login_id=m.stock_checked_by',LOGIN . ' as l1' => 'l1.login_id=m.stock_checked_by_second',
            MEDICAL_CARE_HOME_TRANSECTION . ' as mch' => 'm.medication_id= mch.medication_id');
        if (!empty($searchtext)) {
			
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = array('m.yp_id'=>$searchtext);
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match, '', '', $uri_segment, $sortfield, $sortby, 'm.medication_id', $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match, '', '', '', $sortfield, $sortby, 'm.medication_id', $where, '', '', '1');
        } else {
			
            $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '','', $uri_segment, $sortfield, $sortby, 'm.medication_id', $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, 'm.medication_id', $where, '', '', '1');
        }
		//get YP information
        $match = array("is_deleted"=>'0',"care_home"=>$care_home_id, "is_archive" => '0');
        $fields = array("yp.yp_id,yp.yp_fname,yp.yp_lname");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS.' as yp', $fields,'', '', $match);
        $data['care_home_id'] = $care_home_id;
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

        $this->session->set_userdata('medication_stock_check', $sortsearchpage_data);
        setActiveSession('medication_stock_check'); // set current Session active
        $data['header'] = array('menu_module' => 'Communication');

        
        //get communication form
        $match = array('m_form_id'=> 1);
        $formsdata = $this->common_model->get_records(M_FORM,array("form_json_data"), '', '', $match);
        if(!empty($formsdata))
        {
            $formsdata_expr = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        $data['crnt_view'] = $this->viewname;
        $form_field = array();
              $exceldataHeader = array();
              if(!empty($formsdata_expr))
              {
                  $exceldataHeader[] .= 'Medication Name';
                  $exceldataHeader[] .= 'Medication Type';
                  $exceldataHeader[] .= 'Details';
                  $exceldataHeader[] .= 'Dosage';
                  $exceldataHeader[] .= 'Prescribed to or Purchased for';
                  $exceldataHeader[] .= 'Historical Meds Stock';
                  $exceldataHeader[] .= 'Quantity Given';
                  $exceldataHeader[] .= 'Quantity Remaining';
              }
              if(!empty($formsdata))
              {
			      $sheet =$this->excel->getActiveSheet();
                  $this->excel->setActiveSheetIndex(0)->setTitle('MedicationStock');  
                  $sheet->getStyle('A1:Z1')->getFont()->setBold(true);
                  $sheet->fromArray($exceldataHeader, Null, 'A1')->getStyle('A1')->getFont()->setBold(true); // Set Header Data
                  if(!empty($data['information']))
                  {
			       $col = 2;
                    foreach ($data['information'] as $data) {
			              $exceldataValue = array();
                         $exceldataValue[] .= !empty($data['medication_name'])? wordwrap($data['medication_name'],15,"<br>\n"):'';
            			 $exceldataValue[] .= !empty($data['medication_type'])?wordwrap($data['medication_type'],15,"<br>\n"):'';
			             $exceldataValue[] .= $data['reason'];
						 
						 $exceldataValue[] .= !empty($data['doseage'])?wordwrap($data['doseage'],15,"<br>\n"):'';
						 
						 $exceldataValue[] .= !empty($data['name'])?wordwrap($data['name'],15,"<br>\n"):'';
						 
						 $exceldataValue[] .= $data['stock']+$data['total_given'];
						 
						 $exceldataValue[] .= !empty($data['total_given'])?$data['total_given']:'0';
						 
						 $exceldataValue[] .= !empty($data['stock'])?$data['stock']:0;
					   $sheet->fromArray($exceldataValue, Null, 'A'.$col)->getStyle('A'.$col)->getFont()->setBold(false);
                       $col ++;
                   } // end recordData foreach
                 }
            }
       }
	    $fileName = 'MedicationStock' . date('Y-m-d H:i:s') . '.xls'; // Generate file name
        $this->downloadExcelFile($this->excel, $fileName); // download function Xls file 
}

/*
     * @Author : Nikunj Ghelani
     * @Desc   : Download Report based on the data
     * @Input   :
     * @Output  :
     * @Date   : 11-7-2018
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
