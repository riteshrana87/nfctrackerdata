<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ArchiveConcerns extends CI_Controller {

    function __construct() {

        parent::__construct();
        if(checkPermission('ArchiveConcerns','view') == false)
        {
            redirect('/Dashboard');
        }
        $this->viewname = $this->router->fetch_class ();
        $this->method   = $this->router->fetch_method();
    }

    /*
      @Author : Niral Patel
      @Desc   : ArchiveYPC Index Page
      @Input 	: yp id
      @Output	:
      @Date   : 25/09/2017
     */
    /* function updated by Dhara Bhalala for carehome YP archive */
    public function index($id, $care_home_id = 0, $isArchiveCareHomePage = 0) {
        if ($isArchiveCareHomePage !== 0) {
            $temp = $this->common_model->get_records(PAST_CARE_HOME_INFO, array('move_date'), '', '', array("yp_id" => $id, "past_carehome" => $care_home_id));
            $careHomeDetails = $this->common_model->get_records(PAST_CARE_HOME_INFO, array('enter_date','move_date'), '', '', array("yp_id" => $id, "move_date <= " => $temp[0]['move_date']));
            $enterDate = $moveDate = '';
            $totalCareHome = count($careHomeDetails);
            if ($totalCareHome >= 1) {
                $enterDate = $careHomeDetails[0]['enter_date'];
                $moveDate = $careHomeDetails[$totalCareHome - 1]['move_date'];
            } elseif ($totalCareHome == 0) {
                $enterDate = $careHomeDetails[0]['enter_date'];
                $moveDate = $careHomeDetails[0]['move_date'];
            }
        }
        if (is_numeric($id)) {
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($id,$fields);
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson');
            }
            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = 10;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('aypc_data');
            }
            $searchsort_session = $this->session->userdata('aypc_data');
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
                    $sortfield = 'ypc_id';
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
            $table = YP_CONCERNS . ' as ypc';
            if (checkPermission('ArchiveConcerns', 'hidden_archive')) {
                $where = array("ypc.yp_id" => $id, "ypc.is_archive !=" => 0);
            } else {
                $where = array("ypc.yp_id" => $id, "ypc.is_archive" => 1);
            }
            $fields = array("l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, ch.care_home_name, l.firstname, l.lastname, ypc.*,ypc.date as date,ypc.time as time");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id = ypc.created_by', CARE_HOME . ' as ch' => 'ch.care_home_id = ypc.care_home_id');
            if (!empty($searchtext)) {
                $searchtext = html_entity_decode(trim(addslashes($searchtext)));
                $match = '';
                $where_search = '((CONCAT(`firstname`, \' \', `lastname`) LIKE "%' . $searchtext . '%" OR l.firstname LIKE "%' . $searchtext . '%" OR ch.care_home_name LIKE "%' . $searchtext . '%" OR l.lastname LIKE "%' . $searchtext . '%" OR ypc.date LIKE "%' . $searchtext . '%" OR ypc.time LIKE "%' . $searchtext . '%" OR l.status LIKE "%' . $searchtext . '%")AND l.is_delete = "0")';
            }
            if ($isArchiveCareHomePage == 0) {
                $config['base_url'] = base_url() . $this->viewname . '/index' . '/' . $id;

                if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 4;
                    $uri_segment = $this->uri->segment(4);
                }

                if (!empty($searchtext)) {
                    $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);

                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1');
                } else {
                    $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);

                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
                }
            } else {
                $config['base_url'] = base_url() . $this->viewname . '/index' . '/' . $id . '/' . $care_home_id . '/' . $isArchiveCareHomePage;

                if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                    $config['uri_segment'] = 0;
                    $uri_segment = 0;
                } else {
                    $config['uri_segment'] = 6;
                    $uri_segment = $this->uri->segment(6);
                }

                $where_date = "ypc.created_date BETWEEN  '" . $enterDate . "' AND '" . $moveDate . "'";

                if (!empty($searchtext)) {
                    $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search, '', '', '', '', '', $where_date);

                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1', '', '', $where_date);
                } else {
                    $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where, '', '', '', '', '', $where_date);

                    $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1', '', '', $where_date);
                }
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
                'total_rows' => $config['total_rows']
            );            
            
            $data['ypid'] = $id;
            $data['care_home_id'] = $care_home_id;
            $data['isArchiveCareHomePage'] = $isArchiveCareHomePage;
            //get ks form
            $match = array('ypc_form_id' => 1);
            $ypc_forms = $this->common_model->get_records(YPC_FORM, '', '', '', $match);
            if (!empty($ypc_forms)) {
                $data['form_data'] = json_decode($ypc_forms[0]['form_json_data'], TRUE);
            }
            $this->session->set_userdata('aypc_data', $sortsearchpage_data);
            setActiveSession('aypc_data'); // set current Session active
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['crnt_view'] = $this->viewname;
            // add this js because reset function call twice with single click
            $data['footerJs'][1] = base_url('uploads/custom/js/concerns/concerns.js');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/ajaxlist', $data);
            } else {
                $data['main_content'] = '/keysession';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
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
           //get ks yp data

           $match = array('ypc_id'=> $id);
           $ypc_data = $this->common_model->get_records(YP_CONCERNS,array("ypc_id,created_date"), '', '', $match);
           
           if(!empty($ypc_data))
           {
                if(checkPermission('ArchiveConcerns','hidden_archive')){ 
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
                $where = array('ypc_id'=>$id);
                $this->common_model->update(YP_CONCERNS, $update_archive,$where);
                 redirect('/ArchiveConcerns/index/'. $ypid);
           }
           else
           {
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>No data for archive.</div>");
                redirect('/Concerns/index/'. $ypid);
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
      public function undoArchive($id,$ypid)
      {
        if(is_numeric($id) && is_numeric($ypid))
        {
           //get ks yp data

           $match = array('ypc_id'=> $id);
           $ypc_data = $this->common_model->get_records(YP_CONCERNS,array("ypc_id,created_date"), '', '', $match);
           
           if(!empty($ypc_data))
           {
                $update_archive = array(
                    'is_archive'=>0
                );
                $where = array('ypc_id'=>$id);
                $this->common_model->update(YP_CONCERNS, $update_archive,$where);
                 redirect('/ArchiveConcerns/index/'. $ypid);
           }
           else
           {
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>No data for archive.</div>");
                redirect('/Concerns/index/'. $ypid);
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
    /* function updated by Dhara Bhalala for carehome YP archive */
    public function view($ypc_id, $yp_id, $care_home_id = 0, $isArchiveCareHomePage = 0) {
        if (is_numeric($ypc_id) && is_numeric($yp_id)) {
            //get ypc form
            $match = array('ypc_form_id' => 1);
            $ypc_forms = $this->common_model->get_records(YPC_FORM, '', '', '', $match);
            if (!empty($ypc_forms)) {
                $data['ks_form_data'] = json_decode($ypc_forms[0]['form_json_data'], TRUE);
            }
            // get ypc comments data
            $table = YPC_COMMENTS . ' as com';
            $where = array("com.ypc_id" => $ypc_id, "com.yp_id" => $yp_id);
            $fields = array("com.ypc_comments,com.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= com.yp_id');
            $data['comments'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);

            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($yp_id,$fields);

            //get concern data
            $match = array('ypc_id' => $ypc_id);
            $data['edit_data'] = $this->common_model->get_records(YP_CONCERNS, '', '', '', $match);
            //get concern signoff data
            $table = CONCERNS_SIGNOFF . ' as ypc';
            $where = array("l.is_delete" => "0", "ypc.yp_id" => $yp_id, "ypc.ypc_id" => $ypc_id, "ypc.is_delete" => "0");
            $fields = array("ypc.created_by,ypc.created_date,ypc.yp_id,ypc.ypc_id, CONCAT(`firstname`,' ', `lastname`) as name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id=ypc.created_by');
            $group_by = array('created_by');
            $data['ks_signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);

            $data['care_home_id'] = $care_home_id;
            $data['isArchiveCareHomePage'] = $isArchiveCareHomePage;

            //check data exist or not
            if (empty($data['YP_details']) || empty($data['edit_data'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson');
            }
            $data['ypid'] = $yp_id;
            $data['footerJs'][0] = base_url('uploads/custom/js/concerns/concerns.js');

            $data['crnt_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/view';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }

}
