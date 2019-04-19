<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ArchiveWr extends CI_Controller {

    function __construct() {

        parent::__construct();
        if (checkPermission('ArchiveWr', 'view') == false) {
            redirect('/Dashboard');
        }
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
    }

    /*
      @Author : Ritesh Rana
      @Desc   : ArchiveIbp Index Page
      @Input 	: yp id
      @Output	:
      @Date   : 19/03/2018
     */

    public function index($ypid) {
        if (is_numeric($ypid)) {

            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($ypid,$fields);
            
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('errormsg', $msg);
                redirect('YoungPerson');
            }
            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = RECORD_PER_PAGE;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('archive_wr_data');
				$config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 4;
                $uri_segment = $this->uri->segment(4);
            }

            $searchsort_session = $this->session->userdata('archive_wr_data');
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
                    $sortfield = 'wr.created_date';
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
                    $config['per_page'] = $perpage;
                    $data['perpage'] = $perpage;
                }
            }
            //pagination configuration
            $config['first_link'] = 'First';
            $config['base_url'] = base_url() . $this->viewname . '/index/'.$ypid;
            //Query
            $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
            $table = WEEKLY_REPORT.' as wr';
            if(checkPermission('ArchiveWr','hidden_archive')){ 
              $where = array("wr.yp_id"=> $ypid,"wr.is_archive !="=> 0,"wr.is_delete"=> 0);
			  $appenStr = 'wr.is_archive != "0"';
            }
            else
            {
				$where = array("wr.yp_id"=> $ypid,"wr.is_archive"=> 1,"wr.is_delete"=> 0);
				$appenStr = 'wr.is_archive = "1"';
			}
            $fields = array("l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname,ch.care_home_name,wr.weekly_report_id,wr.yp_id,wr.end_date,wr.start_date,wr.created_date,wr.is_archive,wr.is_delete");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id = wr.created_by',CARE_HOME . ' as ch' => 'ch.care_home_id = wr.care_home_id');
           if(!empty($searchtext))
            {
                $searchtext = html_entity_decode(trim(addslashes($searchtext)));
                $match = '';
                $where_search = '(CONCAT(`firstname`, \' \', `lastname`) LIKE "%' . $searchtext . '%" 
                OR l.firstname LIKE "%' . $searchtext . '%" 
								OR l.lastname LIKE "%' . $searchtext . '%" 
                OR ch.care_home_name LIKE "%' . $searchtext . '%" 
                OR DATE_FORMAT(wr.start_date, "%d/%m/%Y") LIKE "%' . $searchtext . '%" 
								OR DATE_FORMAT(wr.end_date, "%d/%m/%Y") LIKE "%' . $searchtext . '%" 
								OR DATE_FORMAT(wr.created_date, "%d/%m/%Y") LIKE "%' . $searchtext . '%") 
								AND l.is_delete = "0" AND wr.yp_id = "'.$ypid.'" AND '.$appenStr.' AND wr.is_delete = "0"';

                $data['edit_data']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where_search);

                $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,'','',$sortfield,$sortby,'',$where_search,'','','1');
            }
            else
            {
                $data['edit_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where);

                $config['total_rows']  = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','',$sortfield,$sortby,'',$where,'','','1');
            }
			       
            $this->ajax_pagination->initialize($config);
            $data['pagination'] = $this->ajax_pagination->create_links();
            $data['uri_segment'] = $uri_segment;
            $sortsearchpage_data = array(
                'sortfield'  => $data['sortfield'],
                'sortby'     => $data['sortby'],
                'searchtext' => $data['searchtext'],
                'perpage'    => trim($data['perpage']),
                'uri_segment'=> $uri_segment,
                'total_rows' => $config['total_rows']);

            $data['ypid'] = $ypid;
            //get wr form
            $match = array('wr_form_id'=> 1);
            $wr_forms = $this->common_model->get_records(WR_FORM,array("form_json_data"), '', '', $match);
            if(!empty($wr_forms))
            {
                $data['form_data'] = json_decode($wr_forms[0]['form_json_data'], TRUE);
            }
            $this->session->set_userdata('archive_wr_data', $sortsearchpage_data);
            setActiveSession('archive_wr_data'); // set current Session active
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['crnt_view'] = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/wr/wr.js');
            if($this->input->post('result_type') == 'ajax'){
                $this->load->view($this->viewname . '/archive_ajax', $data);
            } else {
                $data['main_content'] = '/archive';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Create archive
      @Input    :
      @Output   :
      @Date   : 19/03/2018
     */

    public function createArchive($id, $ypid) {
       if(is_numeric($id) && is_numeric($ypid))
        {
           //get WR yp data
           $match = array('weekly_report_id'=> $id);
           $wr_data = $this->common_model->get_records(WEEKLY_REPORT,array("*"), '', '', $match);
           if(!empty($wr_data))
           {
                if(checkPermission('ArchiveWr','hidden_archive')){ 
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
                $where = array('weekly_report_id'=>$id);
                $this->common_model->update(WEEKLY_REPORT, $update_archive,$where);
                 redirect('/ArchiveWr/index/'. $ypid);
           }
           else
           {
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>No data for archive.</div>");
                redirect('/WeeklyReport/index/'. $ypid);
           }
        }
        else
        {
            show_404 ();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : View archive
      @Input    :
      @Output   :
      @Date   : 19/03/2018
    */
    public function view($id, $ypid) {
        if (is_numeric($id) && is_numeric($ypid)) {
            //get archive ibp data
            $match = array('weekly_report_id' => $id);
            $data['edit_data'] = $this->common_model->get_records(WEEKLY_REPORT, '', '', '', $match);
        
            $match = array('wr_form_id'=> 1);
            $wr_forms = $this->common_model->get_records(WR_FORM,array("form_json_data"), '', '', $match);
            if(!empty($wr_forms))
            {
                 $data['form_data'] = json_decode($wr_forms[0]['form_json_data'], TRUE);
            }
            
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($ypid,$fields);
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('errormsg', $msg);
                redirect('YoungPerson');
            }
            // signoff data
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $table = WEEKLY_REPORT_SIGNOFF . ' as wr';
            $where = array("l.is_delete" => "0", "wr.yp_id" => $ypid,'wr.weekly_report_id' => $id);
            $fields = array("wr.created_date, wr.created_by,wr.yp_id,CONCAT(`firstname`,' ', `lastname`) as name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id=wr.created_by');
            $group_by = array('created_by');
            $data['signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);


             $table_ap = MEDICAL_PROFESSIONALS_APPOINTMENT . ' as mc';
            $where_ap = array("mc.yp_id" => $ypid,"mc.is_delete"=>0,"mc.appointment_date >=" =>$data['edit_data'][0]['start_date'],"mc.appointment_date <=" =>$data['edit_data'][0]['end_date']);
                $fields_ap = array("mc.*,concat(if(mp.title != '',mp.title,''),' ',if(mp.first_name != '',mp.first_name,''),' ',if(mp.surname != '',mp.surname,''),' - ',if(mp.professional != '',mp.professional,'')) as mp_name, concat(mp.house_namenumber,',',mp.street,' ',mp.town,',',mp.city,',',mp.postcode) as address");
            $join_tables_ap = array(MEDICAL_PROFESSIONALS . ' as mp' => 'mc.mp_id= mp.mp_id',YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id');
            $data['appointments'] = $this->common_model->get_records($table_ap, $fields_ap, $join_tables_ap, 'left', $where_ap, '', '', '', 'appointment_id', 'desc', '');

             $table = MD_COMMENTS . ' as com';
              $where = array("com.yp_id" => $ypid);
              $fields = array("com.md_comment,com.created_date,com.md_appoint_id,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
              $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
              $data['appointment_view_comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);

              $table = WR_REPORT_SENT . ' AS wr';
            $fields = array('wr.key_data,wr.wr_report_sent_id,wr.user_type');
            $where = array('wr.weekly_report_id' => $id, 'wr.yp_id' => $ypid);
            $data['check_external_signoff_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            $data['ypid'] = $ypid;
            $data['footerJs'][0] = base_url('uploads/custom/js/archivewr/archivewr.js');
            $data['crnt_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/view';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }
    /*
      @Author : Ritesh Rana
      @Desc   : Unarchive
      @Input    :
      @Output   :
      @Date   : 27/03/2018
     */
      public function undoArchive($id,$ypid)
      {
        if(is_numeric($id) && is_numeric($ypid))
        {
           //get wr yp data
           $match = array('weekly_report_id'=> $id);
           $wr_data = $this->common_model->get_records(WEEKLY_REPORT,'', '', '', $match);
           if(!empty($wr_data))
           {
                $update_archive = array(
                    'is_archive'=>0
                );
                $where = array('weekly_report_id'=>$id);
                $this->common_model->update(WEEKLY_REPORT, $update_archive,$where);
                 redirect('/ArchiveWr/index/'. $ypid);
           }
           else
           {
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>No data for archive.</div>");
                redirect('/WeeklyReport/index/'. $ypid);
           }
        }
        else
        {
            show_404 ();
        }
      }  
}
