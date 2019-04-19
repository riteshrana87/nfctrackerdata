<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ArchiveRMP extends CI_Controller {

    function __construct() {
        parent::__construct();
        if(checkPermission('ArchiveRMP','view') == false)
        {
            redirect('/Dashboard');
        }
        $this->viewname = $this->router->fetch_class ();
        $this->method   = $this->router->fetch_method();
    }

    /*
      @Author : Niral Patel
      @Desc   : ArchiveRMP Index Page
      @Input 	: yp id
      @Output	:
      @Date   : 25/09/2017
     */

    public function index($id,$care_home_id=0,$past_care_id=0) {
       /*
                Ritesh Rana
                for past care id inserted for archive full functionality
                */
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
        $search_date = $this->input->post('search_date');
        $formated_date = dateformat($search_date);
        $data['search_date'] = $search_date;

		if($this->input->post('search_start_time')!=''){
         $search_start_time = date("H:i", strtotime($this->input->post('search_start_time')));
        $data['start_time_data'] = $this->input->post('search_start_time');
		}else{
			$search_start_time='';
      $data['start_time_data']='';
		}
		if($this->input->post('search_end_time')!=''){
         $search_end_time = date("H:i", strtotime($this->input->post('search_end_time')));
         $data['end_time_data'] = $this->input->post('search_end_time');
		}else{
			$search_end_time='';
      $data['end_time_data']='';
		}

      if(is_numeric($id))
      {
        //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($id,$fields);
        if(empty($data['YP_details']))
        {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson');
        }
        $searchtext='';
        $perpage='';
        $searchtext = $this->input->post('searchtext');
        $sortfield  = $this->input->post('sortfield');
        $sortby     = $this->input->post('sortby');
        $perpage    = 10;
        $allflag    = $this->input->post('allflag');
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('rmp_archive_data');
        }
        $searchsort_session = $this->session->userdata('rmp_archive_data');
        //Sorting
        if(!empty($sortfield) && !empty($sortby))
        {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        }
        else
        {
            if(!empty($searchsort_session['sortfield']))
            {
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby'] = $searchsort_session['sortby'];
                $sortfield = $searchsort_session['sortfield'];
                $sortby = $searchsort_session['sortby'];
            }
            else
            {
                $sortfield = 'rmp_id';
                $sortby = 'desc';
                $data['sortfield']    = $sortfield;
                $data['sortby']     = $sortby;
            }
        }
        //Search text
        if(!empty($searchtext))
        {
            $data['searchtext'] = $searchtext;
        } else
        {
            if(empty($allflag) && !empty($searchsort_session['searchtext']))
            {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext =  $data['searchtext'];
            }
            else
            {
                $data['searchtext'] = '';
            }
        }

        if(!empty($perpage) && $perpage != 'null')
        {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        }
        else
        {
            if(!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
                $data['perpage'] = '10';
            }
        }
        //pagination configuration
        $config['first_link']  = 'First';

        if($past_care_id == 0){
          $config['base_url']    = base_url().$this->viewname.'/index';
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        
        //Query
        $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
        $table = RMP.' as ks';
      if(checkPermission('ArchiveRMP','hidden_archive')){ 
          $where = "ks.yp_id='".$id."' AND ks.is_archive !=0";
      if (!empty($search_date)) {
                $where .= ' AND ks.created_date LIKE "%' . $formated_date.'%"';
        }
        if (!empty($search_start_time)) { 
                $where .= ' AND ks.modified_time BETWEEN  "' . $search_start_time.'" AND "'.$search_end_time.'"';
        }
        }
        else
        {
      $where = "ks.yp_id='".$id."' AND ks.is_archive =1";
     if (!empty($search_date)) {
                $where .= ' AND ks.created_date LIKE "%' . $formated_date.'%"';
        }
        if (!empty($search_start_time)) { 
                $where .= ' AND ks.modified_time BETWEEN  "' . $search_start_time.'" AND "'.$search_end_time.'"';
        }
      
      }
        
        $fields = array("l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname, ks.*, STR_TO_DATE( ks.created_date , '%d/%m/%Y' ) AS date, ch.care_home_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id = ks.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = ks.care_home_id');
       if(!empty($searchtext))
        {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search='((CONCAT(`firstname`, \' \', `lastname`) LIKE "%'.$searchtext.'%" OR l.firstname LIKE "%'.$searchtext.'%" OR l.lastname LIKE "%'.$searchtext.'%" OR ks.date LIKE "%'.$searchtext.'%" OR ks.time LIKE "%'.$searchtext.'%" OR l.status LIKE "%'.$searchtext.'%")AND l.is_delete = "0")';

            $data['edit_data']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where_search);

            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,'','',$sortfield,$sortby,'',$where_search,'','','1');
        }
        else
        {
            $data['edit_data']      = $this->common_model->get_records($table,$fields,$join_tables,'left','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where);
         
            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','',$sortfield,$sortby,'',$where,'','','1');
        }
        }else{
          $config['base_url']    = base_url().$this->viewname.'/index'.'/'.$id.'/'.$care_home_id.'/'.$past_care_id;
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 7;
            $uri_segment = $this->uri->segment(7);
        }
        
        //Query
        $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
        $table = RMP.' as ks';
      if(checkPermission('ArchiveRMP','hidden_archive')){ 
        $where = "ks.yp_id='".$id."' AND ks.is_archive !=0";
      if (!empty($search_date)) {
                $where .= ' AND ks.created_date LIKE "%' . $formated_date.'%"';
        }
        if (!empty($search_start_time)) { 
                $where .= ' AND ks.modified_time BETWEEN  "' . $search_start_time.'" AND "'.$search_end_time.'"';
        }
        } else {
          $where = "ks.yp_id='".$id."' AND ks.is_archive =1";
     if (!empty($search_date)) {
                $where .= ' AND ks.created_date LIKE "%' . $formated_date.'%"';
        }
        if (!empty($search_start_time)) { 
                $where .= ' AND ks.modified_time BETWEEN  "' . $search_start_time.'" AND "'.$search_end_time.'"';
        }
      
      }
         $where_date = "ks.created_date BETWEEN  '".$created_date."' AND '".$movedate."'";
        $fields = array("l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname, ks.*, STR_TO_DATE( ks.created_date , '%d/%m/%Y' ) AS date, ch.care_home_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id = ks.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = ks.care_home_id');
       if(!empty($searchtext))
        {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search='((CONCAT(`firstname`, \' \', `lastname`) LIKE "%'.$searchtext.'%" OR l.firstname LIKE "%'.$searchtext.'%" OR l.lastname LIKE "%'.$searchtext.'%" OR ks.date LIKE "%'.$searchtext.'%" OR ks.time LIKE "%'.$searchtext.'%" OR l.status LIKE "%'.$searchtext.'%")AND l.is_delete = "0")';

            $data['edit_data']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where_search,'','','','','',$where_date);

            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,'','',$sortfield,$sortby,'',$where_search,'','','1','','',$where_date);
        }
        else
        {
            $data['edit_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where,'','','','','',$where_date);
            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','',$sortfield,$sortby,'',$where,'','','1','','',$where_date);
        }
        }
        $this->ajax_pagination->initialize($config);
        $data['pagination']  = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        $sortsearchpage_data = array(
            'sortfield'  => $data['sortfield'],
            'sortby'     => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage'    => trim($data['perpage']),
            'uri_segment'=> $uri_segment,
            'total_rows' => $config['total_rows']);

        $data['ypid'] = $id;
        $data['care_home_id'] = $care_home_id;
        $data['past_care_id'] = $past_care_id;

        //get rmp form
        $match = array('rmp_form_id'=> 1);
        $ks_forms = $this->common_model->get_records(RMP_FORM,'', '', '', $match);
        if(!empty($ks_forms))
        {
            $data['form_data'] = json_decode($ks_forms[0]['form_json_data'], TRUE);
        }
        $this->session->set_userdata('rmp_archive_data', $sortsearchpage_data);
        setActiveSession('rmp_archive_data'); // set current Session active
        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/RiskManagementPlan/RiskManagementPlan.js');
        if($this->input->post('result_type') == 'ajax'){
            $this->load->view($this->viewname . '/ajaxlist', $data);
        } else {
            $data['main_content'] = '/RiskManagementPlan';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
      }
      else
      {
          show_404 ();
      }
    }

    /*
      @Author : Niral Patel
      @Desc   : Create archive
      @Input    :
      @Output   :
      @Date   : 25/09/2017
     */
      public function createArchive($id,$ypid)
      {
        if(is_numeric($id) && is_numeric($ypid))
        {
           //get RMP yp data
           $match = array('rmp_id'=> $id);
           $rmp_archive_data = $this->common_model->get_records(RMP,array("*"), '', '', $match);
           if(!empty($rmp_archive_data))
           {
                if(checkPermission('ArchiveRMP','hidden_archive')){ 
                  $update_archive = array(
                      'is_archive'=>2
                  );
                }
                else
                {
                  $update_archive = array(
                    'is_archive'=>1
                  );
                }
                $where = array('rmp_id'=>$id);
                $this->common_model->update(RMP, $update_archive,$where);
                 redirect('/ArchiveRMP/index/'. $ypid);
           }
           else
           {
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>No data for archive.</div>");
                redirect('/ArchiveRMP/index/'. $ypid);
           }
        }
        else
        {
            show_404 ();
        }
      } 
      /*
      @Author : Niral Patel
      @Desc   : Undo archive
      @Input    :
      @Output   :
      @Date   : 25/09/2017
     */
      public function undoArchive($id,$ypid)
      {
        if(is_numeric($id) && is_numeric($ypid))
        {
           //get RMP yp data
           $match = array('rmp_id'=> $id);
           $rmp_archive_data = $this->common_model->get_records(RMP,array("*"), '', '', $match);
           if(!empty($rmp_archive_data))
           {
                $update_archive = array(
                    'is_archive'=>0
                );
                $where = array('rmp_id'=>$id);
                $this->common_model->update(RMP, $update_archive,$where);
                 redirect('/ArchiveRMP/index/'. $ypid);
           }
           else
           {
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>No data for archive.</div>");
                redirect('/ArchiveRMP/index/'. $ypid);
           }
        }
        else
        {
            show_404 ();
        }
      }     
    /*
      @Author : Niral Patel
      @Desc   : View archive
      @Input    :
      @Output   :
      @Date   : 25/09/2017
     */
   public function view($rmp_id,$yp_id,$care_home_id=0,$past_care_id=0) {
    if(is_numeric($rmp_id) && is_numeric($yp_id))
    {
       //get ks form
       $match = array('rmp_form_id'=> 1);
       $ks_forms = $this->common_model->get_records(RMP_FORM,array("form_json_data"), '', '', $match);
       if(!empty($ks_forms))
       {
            $data['ks_form_data'] = json_decode($ks_forms[0]['form_json_data'], TRUE);
       }
        //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($yp_id,$fields);  

        //get RMP yp data
        $match = array('rmp_id'=> $rmp_id);
        $data['edit_data'] = $this->common_model->get_records(RMP,array("*"), '', '', $match);

        $table = RMP_SIGNOFF.' as ks';
        $where = array("l.is_delete"=> "0","ks.yp_id" => $yp_id,"ks.rmp_id"=>$rmp_id,"ks.is_delete"=> "0");
        $fields = array("ks.created_by,ks.created_date,ks.yp_id,ks.rmp_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id=ks.created_by');
        $group_by = array('created_by');
        $data['ks_signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);
        
         $table = RMP_COMMENTS . ' as com';
        $where = array("com.rmp_id" => $rmp_id, "com.yp_id" => $yp_id);
        $fields = array("com.rmp_comments,com.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
        $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
        
        //check data exist or not
        if(empty($data['YP_details']) || empty($data['edit_data']))
        {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson');
        }
        $data['ypid'] = $yp_id;
        $data['care_home_id'] = $care_home_id;
        $data['past_care_id'] = $past_care_id;
        $data['footerJs'][0] = base_url('uploads/custom/js/RiskManagementPlan/RiskManagementPlan.js');
        $data['crnt_view'] = $this->viewname;
        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['main_content'] = '/view';
        $this->parser->parse('layouts/DefaultTemplate', $data);
      }
      else
      {
          show_404 ();
      }
    }
}
