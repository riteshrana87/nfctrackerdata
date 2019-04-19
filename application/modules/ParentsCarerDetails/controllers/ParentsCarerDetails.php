<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ParentsCarerDetails extends CI_Controller {

	function __construct() {
		
		parent::__construct();
		if(checkPermission('ParentsCarerDetails','view') == false)
           {
               redirect('/Dashboard');
           }
        $this->viewname = $this->uri->segment(1);
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('form_validation', 'Session'));
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
    }
	/*
	 @Author : Ritesh rana
	 @Desc   : Registration Index Page
	 @Input 	:
	 @Output	:
	 @Date   : 23/02/2017
	*/
    public function index($ypid,$careHomeId=0,$isArchive=0) {
		$data['is_archive_page'] = $isArchive;
        $data['careHomeId'] = $careHomeId;
		/*for care to care data
		ghelani nikunj
		05/10/2018
		care to  care data get with the all previous care home*/
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
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($ypid,$fields);
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/'.$ypid);
            }
            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = 10;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('parents_data');
            }
            $searchsort_session = $this->session->userdata('parents_data');
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
                    $sortfield = 'parent_carer_id';
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
           if($isArchive==0){
                //need to change when client will approved functionality
            $config['base_url'] = base_url() . $this->viewname . '/index/' . $ypid;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 4;
                $uri_segment = $this->uri->segment(4);
            }
            }else{
                $config['base_url'] = base_url() . $this->viewname . '/index/' . $ypid.'/'.$careHomeId.'/'.$isArchive;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
				$config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
				$config['uri_segment'] = 6;
				$uri_segment = $this->uri->segment(6);
            }
            }
            //Query
        $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
        $table = PARENT_CARER_INFORMATION.' as pc';
		if($isArchive==0){
        $where = array("pc.is_deleted"=> "0","pc.yp_id"=> $ypid);
        $fields = array("c.care_home_name,CONCAT(`firstname`,' ', `surname`) as name, pc.firstname, pc.surname, pc.email_address, pc.contact_number, pc.relationship, pc.yp_id, pc.parent_carer_id,pc.landline_number");
		$join_tables = array(CARE_HOME . ' as c' => 'c.care_home_id= pc.care_home_id');
        
       if(!empty($searchtext))
        {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search='((CONCAT(`firstname`, \' \', `surname`) LIKE "%'.$searchtext.'%" OR pc.firstname LIKE "%'.$searchtext.'%" OR pc.surname LIKE "%'.$searchtext.'%" OR pc.email_address LIKE "%'.$searchtext.'%" OR pc.contact_number LIKE "%'.$searchtext.'%" OR pc.landline_number LIKE "%'.$searchtext.'%" OR ch.care_home_name LIKE "%'.$searchtext.'%" OR pc.relationship LIKE "%'.$searchtext.'%")AND pc.is_deleted = "0" AND pc.yp_id = "'.$ypid.'")';

            $data['information']  = $this->common_model->get_records($table,$fields,$join_tables, 'left','',$match,$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where_search);

            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables, 'left','',$match,'','',$sortfield,$sortby,'',$where_search,'','','1');
        }
        else
        {
            $data['information']      = $this->common_model->get_records($table,$fields,$join_tables, 'left','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where);
            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables, 'left','','','','',$sortfield,$sortby,'',$where,'','','1');
        }
		}else{
			$where = array("pc.is_deleted"=> "0","pc.yp_id"=> $ypid);
			$where_date="pc.created_date BETWEEN  '".$created_date."' AND '".$movedate."'";
			$fields = array("c.care_home_name,CONCAT(`firstname`,' ', `surname`) as name, pc.firstname, pc.surname, pc.email_address, pc.contact_number, pc.relationship, pc.yp_id, pc.parent_carer_id,pc.landline_number");
			$join_tables = array(CARE_HOME . ' as c' => 'c.care_home_id= pc.care_home_id');
		  if(!empty($searchtext))
        {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search='((CONCAT(`firstname`, \' \', `surname`) LIKE "%'.$searchtext.'%" OR pc.firstname LIKE "%'.$searchtext.'%" OR pc.surname LIKE "%'.$searchtext.'%" OR pc.email_address LIKE "%'.$searchtext.'%" OR pc.contact_number LIKE "%'.$searchtext.'%" OR pc.landline_number LIKE "%'.$searchtext.'%" OR pc.relationship LIKE "%'.$searchtext.'%")AND pc.is_deleted = "0" AND pc.yp_id = "'.$ypid.'")';

            $data['information']  = $this->common_model->get_records($table,$fields,$join_tables, 'left','',$match,$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where_search,'','','','','',$where_date);

            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables, 'left','',$match,'','',$sortfield,$sortby,'',$where_search,'','','1','','',$where_date);
		}
        else
        {
            $data['information']      = $this->common_model->get_records($table,$fields,$join_tables, 'left','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where);
            
            $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables, 'left','','','','',$sortfield,$sortby,'',$where,'','','1');
        }
		}
            
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

            $this->session->set_userdata('parents_data', $sortsearchpage_data);
            setActiveSession('parents_data'); // set current Session active
            $data['header'] = array('menu_module' => 'YoungPerson');
            //get YP information
            $data['crnt_view'] = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/youngperson/youngperson.js');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/ajaxlist', $data);
            } else {
                $data['main_content'] = '/userlist';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }

	/*
	 @Author : Ritesh rana
	 @Desc   : create Registration page
	 @Input 	:
	 @Output	:
	 @Date   : 25/06/2017
	 */

    public function addParentCarerInformation($yp_id) {
            //Get Records From Login Table
            $data['footerJs'][0] = base_url('uploads/custom/js/youngperson/youngperson.js');
            $data['yp_id'] = $yp_id;
            $data['crnt_view'] = $this->viewname;
            $data['form_action_path'] = $this->viewname . '/updateParentCarerDetails/';
            $data['main_content'] = '/parentcarerdetails';
            $this->load->view('/parentcarerdetails', $data);
        }

        /*
     @Author : Ritesh Rana
     @Desc   : ParentList Edit Page
     @Input     :
     @Output    :
     @Date   : 25/06/2017
     */

    public function editParentCarerInformation($yp_id='',$parent_carer_id='') {
            //Get Records From Login Table
            $data['footerJs'][0] = base_url('uploads/custom/js/youngperson/youngperson.js');
            $table = PARENT_CARER_INFORMATION . ' as swd';
            $match = "swd.parent_carer_id = " . $parent_carer_id;
            $fields = array("swd.*");
            $data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['yp_id'] = $yp_id;
            $data['crnt_view'] = $this->viewname;
            $data['form_action_path'] = $this->viewname . '/updateParentCarerDetails/';
            $data['main_content'] = '/parentcarerdetails';
            $this->load->view('/parentcarerdetails', $data);
        }
		
/*
     @Author : nikunj ghelani
     @Desc   : ParentList view Page
     @Input     :
     @Output    :
     @Date   : 05/10/2018
     */

    public function viewParentCarerInformation($yp_id='',$parent_carer_id='') {
            //Get Records From Login Table
            $data['footerJs'][0] = base_url('uploads/custom/js/youngperson/youngperson.js');
            $table = PARENT_CARER_INFORMATION . ' as swd';
            $match = "swd.parent_carer_id = " . $parent_carer_id;
            $fields = array("swd.*");
            $data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['yp_id'] = $yp_id;
            $data['crnt_view'] = $this->viewname;
            $data['form_action_path'] = $this->viewname . '/updateParentCarerDetails/';
            $data['main_content'] = '/parentcarerdetails_archive_view';
            $this->load->view('/parentcarerdetails_archive_view', $data);
        }

        /*
     @Author : Ritesh Rana
     @Desc   : ParentList update Page
     @Input     :
     @Output    :
     @Date   : 25/06/2017
     */

    public function updateParentCarerDetails() {
    if (!validateFormSecret()) {
        redirect($_SERVER['HTTP_REFERER']);  //Redirect On Listing page
    }
    $postdata = $this->input->post();
    $yp_id = $this->input->post('yp_id');
	//get YP information
	/*ghelani nikunj
		get yp information for save care_home_id
		4/10/2018
	*/
    $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
    $data_detail['YP_details'] = YpDetails($yp_id,$fields);
    $parent_carer_id = $this->input->post('parent_carer_id');
    
    $data['firstname'] = strip_tags($postdata['firstname']);
    $data['surname'] = strip_tags($postdata['surname']);
    $data['relationship'] = strip_tags($postdata['relationship']);
    $data['address'] = strip_tags($postdata['address']);
    $data['contact_number'] = strip_tags($postdata['contact_number']);
    $data['landline_number'] = strip_tags($postdata['landline_number']);
    $data['email_address'] = strip_tags($postdata['email_address']);
    $data['yp_authorised_communication'] = strip_tags($postdata['yp_authorised_communication']);
    $data['carer_authorised_communication'] = strip_tags($postdata['carer_authorised_communication']);
    $data['yp_authorised_communication'] = strip_tags($postdata['yp_authorised_communication']);
    $data['comments'] = strip_tags($postdata['comments']);
    $data['care_home_id'] = $data_detail['YP_details'][0]['care_home'];
    $table = PARENT_CARER_INFORMATION . ' as pa';
    $match = array("pa.yp_id" =>$yp_id,"pa.parent_carer_id" =>$parent_carer_id);
    $fields = array("pa.*");
    $socialRecord = $this->common_model->get_records($table, $fields, '', '', $match);
    if (empty($socialRecord)) {
        $data['created_date'] = datetimeformat();
        $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
        $data['yp_id'] = $this->input->post('yp_id');
        $success = $this->common_model->insert(PARENT_CARER_INFORMATION, $data);
        //Insert log activity
        $activity = array(
            'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
            'yp_id' => !empty($postdata['yp_id']) ? $postdata['yp_id'] : '',
            'module_name' => PARENT_CARER_DETAILS_YP,
            'module_field_name' => '',
            'type' => 1
        );
        log_activity($activity);
        $msg = $this->lang->line('parent_add_msg');
    } else {
        $data['modified_date'] = datetimeformat();
        $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
        $where = array('parent_carer_id' => $parent_carer_id);
        $success = $this->common_model->update(PARENT_CARER_INFORMATION, $data, $where);
        //Insert log activity
        $activity = array(
            'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
            'yp_id' => !empty($postdata['yp_id']) ? $postdata['yp_id'] : '',
            'module_name' => PARENT_CARER_DETAILS_YP,
            'module_field_name' => '',
            'type' => 2
        );
        log_activity($activity);
        $msg = $this->lang->line('parent_update_msg');
    }
    $data['crnt_view'] = $this->viewname;
    if ($success) {
        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
    } else {    // error
        $msg = $this->lang->line('error_msg');
        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
    }
    redirect('ParentsCarerDetails/index/' . $yp_id); //Redirect On Listing page
}

/*
     @Author : Ritesh Rana
     @Desc   : UserList Delete Page
     @Input     :
     @Output    :
     @Date   : 25/06/2017
     */
    public function removeParentCarerInformation($yp_id='',$parent_carer_id='')
    {
        $data['is_deleted'] = 1;
        $where = array('parent_carer_id' => $parent_carer_id);
        $success = $this->common_model->update(PARENT_CARER_INFORMATION, $data, $where);
        //Insert log activity
        $activity = array(
            'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
            'yp_id' => !empty($yp_id) ? $yp_id : '',
            'module_name' => PARENT_CARER_DETAILS_YP,
            'module_field_name' => '',
            'type' => 3
        );
        log_activity($activity);
        $msg = $this->lang->line('delete_parent');
        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        redirect('ParentsCarerDetails/index/' . $yp_id); //Redirect On Listing page
    }
}
