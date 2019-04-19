<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MedicalView extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Niral Patel
      @Desc   : MedicalView Index Page
      @Input 	: yp id
      @Output	:
      @Date   : 29/08/2017
     */

    public function index($id) {
        //get YP information
        if (is_numeric($id)) {
            //get YP information
            $match = array("yp_id" => $id);
            $fields = array("*");
            $data['YP_data'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            if (empty($data['YP_data'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('Admin/Reports/MEDS');
            }

            $match = "yp_id = " . $id;
            $fields = array("*");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            //get mi details
            $match = "yp_id = " . $id;
            $fields = array("*");
            $data['mi_details'] = $this->common_model->get_records(MEDICAL_INFORMATION, $fields, '', '', $match);
            //get mac details
            $match = "yp_id = " . $id;
            $fields = array("*");
            $data['mac_details'] = $this->common_model->get_records(MEDICAL_AUTHORISATIONS_CONSENTS, $fields, '', '', $match);

            //get mac form
            $match = array('mac_form_id' => 1);
            $formsdata = $this->common_model->get_records(MAC_FORM, '', '', '', $match);
            if (!empty($formsdata)) {
                $data['mac_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }

            //get mp form
            $match = array('mp_form_id' => 1);
            $formsdata = $this->common_model->get_records(MP_FORM, '', '', '', $match);
            if (!empty($formsdata)) {
                $data['mp_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }
            //get omi details
            $match = "yp_id = " . $id;
            $fields = array("*");
            $data['omi_details'] = $this->common_model->get_records(OTHER_MEDICAL_INFORMATION, $fields, '', '', $match);

            //get omi form
            $match = array('omi_form_id' => 1);
            $formsdata = $this->common_model->get_records(OMI_FORM, '', '', '', $match);
            if (!empty($formsdata)) {
                $data['omi_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }
            //get mi details
            $match = "yp_id = " . $id;
            $fields = array("*");
            $data['miform_details'] = $this->common_model->get_records(MEDICAL_INOCULATIONS, $fields, '', '', $match);

            //get mi form
            $match = array('mi_form_id' => 1);
            $formsdata = $this->common_model->get_records(MI_FORM, '', '', '', $match);
            if (!empty($formsdata)) {
                $data['mi_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }
            /* mp data start */
            $config['per_page'] = '10';
            $data['perpage'] = '10';
            $data['searchtext'] = '';
            $sortfield = 'mp_id';
            $sortby = 'desc';
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
            $config['uri_segment'] = 5;
            $uri_segment = $this->uri->segment(5);
            $config['first_link'] = 'First';
            $config['base_url'] = base_url('Admin') . '/' . $this->viewname . '/mp_ajax/' . $id;
            $table = MEDICAL_PROFESSIONALS . ' as mc';
            $where = array("mc.yp_id" => $id);
            $fields = array("mc.*");

            if (!empty($searchtext)) {
                
            } else {
                $data['mp_details'] = $this->common_model->get_records($table, $fields, '', '', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
                $config['total_rows'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
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

            $this->session->set_userdata('professional_medication_session_data', $sortsearchpage_data);
            setActiveSession('professional_medication_session_data'); // set current Session active
            /* end mp data */
            $data['ypid'] = $id;
            //$data['footerJs'][0] = base_url('uploads/custom/js/medical/medical.js');
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['crnt_view'] = $this->viewname;
            $data['main_content'] = '/MedicalView/medical';
            $this->parser->parse('/assets/reporttemplate', $data);
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : ajax mp data
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */

    public function mp_ajax($ypid) {
        $searchtext = $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('professional_medication_session_data');
        }

        $searchsort_session = $this->session->userdata('professional_medication_session_data');
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
                $sortfield = 'administer_medication_id';
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
        if (is_numeric($ypid)) {
            //get YP information
            $match = array("yp_id" => $ypid);
            $fields = array("*");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('Admin/Reports/MEDS');
            }
            $config['first_link'] = 'First';
            $config['base_url'] = base_url('Admin') . '/' . $this->viewname . '/mp_ajax/' . $ypid;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 5;
                $uri_segment = $this->uri->segment(5);
            }
            //Query
            $table = MEDICAL_PROFESSIONALS . ' as mc';
            $where = array("mc.yp_id" => $ypid);
            $fields = array("mc.*");
            //$join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by');
            if (!empty($searchtext)) {
                
            } else {
                $data['mp_details'] = $this->common_model->get_records($table, $fields, '', '', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);

                $config['total_rows'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
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

            $this->session->set_userdata('professional_medication_session_data', $sortsearchpage_data);
            setActiveSession('professional_medication_session_data'); // set current Session active
            //$data['header'] = array('menu_module' => 'Communication');
            $data['header'] = array('menu_module' => 'YoungPerson');

            //get communication form
            $match = array('mp_form_id' => 1);
            $formsdata = $this->common_model->get_records(MP_FORM, '', '', '', $match);
            if (!empty($formsdata)) {
                $data['mp_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }

            $data['crnt_view'] = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
            $this->load->view($this->viewname . '/mp_ajaxlist', $data);
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : view mc data
      @Input  :
      @Output :
      @Date   : 19/07/2017
     */

    public function view_mc($ypid) {
        if (is_numeric($ypid)) {
            $match = array("yp_id" => $ypid);
            $fields = array("*");
            $data['YPData'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            if (empty($data['YPData'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('Admin/Reports/MEDS');
            }

            $searchtext =  $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = 10;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('mc_session_data');
            }

            $searchsort_session = $this->session->userdata('mc_session_data');
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
                    $sortfield = 'communication_id';
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
            $config['base_url'] = base_url() . 'Admin/' . $this->viewname . '/view_mc/' . $ypid;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 5;
                $uri_segment = $this->uri->segment(5);
            }
            //Query
            $table = MEDICAL_COMMUNICATION . ' as mc';
            $where = array("mc.yp_id" => $ypid);
            $fields = array("mc.*");
            //$join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by');
            if (!empty($searchtext)) {
                
            } else {
                $data['information'] = $this->common_model->get_records($table, $fields, '', '', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
                $config['total_rows'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
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

            $this->session->set_userdata('mc_session_data', $sortsearchpage_data);
            setActiveSession('mc_session_data'); // set current Session active
            $data['header'] = array('menu_module' => 'Communication');

            //get YP information
            $match = "yp_id = " . $ypid;
            $fields = array("*");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            //get communication form
            $match = array('mc_form_id' => 1);
            $formsdata = $this->common_model->get_records(MC_FORM, '', '', '', $match);
            if (!empty($formsdata)) {
                $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }

            $data['crnt_view'] = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
            $data['header'] = array('menu_module' => 'YoungPerson');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/mc_ajaxlist', $data);
            } else {
                $data['main_content'] = 'MedicalView/mc_list';
                $this->parser->parse('/assets/reporttemplate', $data);
            }
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : view medication data
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */

    public function medication($ypid) {
        if (is_numeric($ypid)) {
            $match = array("yp_id" => $ypid);
            $fields = array("*");
            $data['YPData'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            if (empty($data['YPData'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('Admin/Reports/MEDS');
            }
            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = 10;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('medication_session_data');
            }

            $searchsort_session = $this->session->userdata('medication_session_data');
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
            $config['base_url'] = base_url() . 'Admin/' . $this->viewname . '/medication/' . $ypid;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 5;
                $uri_segment = $this->uri->segment(5);
            }
            //Query
            $table = MEDICATION . ' as mc';
            $where = array("mc.yp_id" => $ypid);
            $fields = array("mc.*");
            //$join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by');
            if (!empty($searchtext)) {
                
            } else {
                $data['information'] = $this->common_model->get_records($table, $fields, '', '', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
                $config['total_rows'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
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

            $this->session->set_userdata('medication_session_data', $sortsearchpage_data);
            setActiveSession('medication_session_data'); // set current Session active
            $data['header'] = array('menu_module' => 'Communication');

            //get YP information
            $match = "yp_id = " . $ypid;
            $fields = array("*");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            //get communication form
            $match = array('m_form_id' => 1);
            $formsdata = $this->common_model->get_records(M_FORM, '', '', '', $match);
            if (!empty($formsdata)) {
                $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }

            $data['crnt_view'] = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
            $data['header'] = array('menu_module' => 'YoungPerson');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/mc_ajaxlist', $data);
            } else {
                $data['main_content'] = 'MedicalView/medication';
                $this->parser->parse('/assets/reporttemplate', $data);
            }
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : view /add administer medication data
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */

    public function log_administer_medication($ypid) {
        if (is_numeric($ypid)) {
            $match = array("yp_id" => $ypid);
            $fields = array("*");
            $data['YPData'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            if (empty($data['YPData'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('Admin/Reports/MEDS');
            }

            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = 10;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('administer_medication_session_data');
            }

            $searchsort_session = $this->session->userdata('administer_medication_session_data');
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
                    $sortfield = 'administer_medication_id';
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
            $config['base_url'] = base_url() . 'Admin/' . $this->viewname . '/log_administer_medication/' . $ypid;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 5;
                $uri_segment = $this->uri->segment(5);
            }
            //Query
            $table = ADMINISTER_MEDICATION . ' as mc';
            $where = array("mc.yp_id" => $ypid);
            $fields = array("mc.*");
            //$join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by');
            if (!empty($searchtext)) {
                
            } else {
                $data['information'] = $this->common_model->get_records($table, $fields, '', '', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
                $config['total_rows'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
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

            $this->session->set_userdata('administer_medication_session_data', $sortsearchpage_data);
            setActiveSession('administer_medication_session_data'); // set current Session active
            $data['header'] = array('menu_module' => 'Communication');

            //get YP information
            $match = "yp_id = " . $ypid;
            $fields = array("*");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            //get communication form
            $match = array('am_form_id' => 1);
            $formsdata = $this->common_model->get_records(AM_FORM, '', '', '', $match);
            if (!empty($formsdata)) {
                $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }

            $data['crnt_view'] = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
            $data['header'] = array('menu_module' => 'YoungPerson');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/administer_ajaxlist', $data);
            } else {
                $data['main_content'] = 'MedicalView/log_administer_medication';
                $this->parser->parse('/assets/reporttemplate', $data);
            }
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : view /add administer medication data
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */

    public function administer_medication($ypid) {
        if (is_numeric($ypid)) {
            $match = array("yp_id" => $ypid, "created_by" => $this->session->userdata['LOGGED_IN']['ID']);
            $fields = array("*");
            $data['YPData'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            if (empty($data['YPData'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('Admin/Reports/MEDS');
            }
            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = 10;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('administer_medication_session_data');
            }

            $searchsort_session = $this->session->userdata('administer_medication_session_data');
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
                    $sortfield = 'administer_medication_id';
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
            $config['base_url'] = base_url() . $this->viewname . '/administer_medication/' . $ypid;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 5;
                $uri_segment = $this->uri->segment(5);
            }
            //Query
            $table = ADMINISTER_MEDICATION . ' as mc';
            $where = array("mc.yp_id" => $ypid);
            $fields = array("mc.*");
            //$join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by');
            if (!empty($searchtext)) {
                
            } else {
                $data['information'] = $this->common_model->get_records($table, $fields, '', '', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
                $config['total_rows'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
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

            $this->session->set_userdata('administer_medication_session_data', $sortsearchpage_data);
            setActiveSession('administer_medication_session_data'); // set current Session active
            $data['header'] = array('menu_module' => 'Communication');

            //get YP information
            $match = "yp_id = " . $ypid;
            $fields = array("*");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            //get communication form
            $match = array('am_form_id' => 1);
            $formsdata = $this->common_model->get_records(AM_FORM, '', '', '', $match);
            if (!empty($formsdata)) {
                $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }
            $data['crnt_view'] = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
            $data['header'] = array('menu_module' => 'YoungPerson');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/administer_ajaxlist', $data);
            } else {
                $data['main_content'] = '/administer_medication';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            $msg = $this->lang->line('error');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('Admin/Reports/MEDS');
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : view /add Health Assessment data
      @Input  :
      @Output :
      @Date   : 22/08/2017
     */

    public function healthAssessment($ypid) {
        if (is_numeric($ypid)) {
            $match = array("yp_id" => $ypid, "created_by" => $this->session->userdata['LOGGED_IN']['ID']);
            $fields = array("*");
            $data['YPData'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            if (empty($data['YPData'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('Admin/Reports/MEDS');
            }
            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = 10;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('health_assessment_session_data');
            }

            $searchsort_session = $this->session->userdata('health_assessment_session_data');
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
                    $sortfield = 'health_assessment_id';
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
            $config['base_url'] = base_url() . $this->viewname . '/administer_medication/' . $ypid;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 5;
                $uri_segment = $this->uri->segment(5);
            }
            //Query
            $table = HEALTH_ASSESSMENT . ' as mc';
            $where = array("mc.yp_id" => $ypid);
            $fields = array("mc.*");
            //$join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by');
            if (!empty($searchtext)) {
                
            } else {
                $data['information'] = $this->common_model->get_records($table, $fields, '', '', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
                $config['total_rows'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
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

            $this->session->set_userdata('health_assessment_session_data', $sortsearchpage_data);
            setActiveSession('health_assessment_session_data'); // set current Session active
            //get YP information
            $match = "yp_id = " . $ypid;
            $fields = array("*");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            //get communication form
            $match = array('ha_form_id' => 1);
            $formsdata = $this->common_model->get_records(HA_FORM, '', '', '', $match);
            
            if (!empty($formsdata)) {
                $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }
            
            $data['crnt_view'] = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
            $data['header'] = array('menu_module' => 'YoungPerson');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/health_assessment_ajaxlist', $data);
            } else {
                $data['main_content'] = '/health_assessment';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            $msg = $this->lang->line('error');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('Admin/Reports/MEDS');
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : view /add health assessment data
      @Input  :
      @Output :
      @Date   : 21/07/2017
     */

    public function log_health_assessment($ypid) {
        if (is_numeric($ypid)) {
            $match = array("yp_id" => $ypid);
            $fields = array("*");
            $data['YPData'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            if (empty($data['YPData'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('Admin/Reports/MEDS');
            }

            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = 10;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('health_assessment_session_data');
            }

            $searchsort_session = $this->session->userdata('health_assessment_session_data');
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
                    $sortfield = 'health_assessment_id';
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
            $config['base_url'] = base_url() . 'Admin/' . $this->viewname . '/log_health_assessment/' . $ypid;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 5;
                $uri_segment = $this->uri->segment(5);
            }
            //Query
            $table = HEALTH_ASSESSMENT . ' as mc';
            $where = array("mc.yp_id" => $ypid);
            $fields = array("mc.*");
            //$join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by');
            if (!empty($searchtext)) {
                
            } else {
                $data['information'] = $this->common_model->get_records($table, $fields, '', '', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
                $config['total_rows'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
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

            $this->session->set_userdata('health_assessment_session_data', $sortsearchpage_data);
            setActiveSession('health_assessment_session_data'); // set current Session active
            $data['header'] = array('menu_module' => 'Communication');

            //get YP information
            $match = "yp_id = " . $ypid;
            $fields = array("*");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            //get communication form
            $match = array('ha_form_id' => 1);
            $formsdata = $this->common_model->get_records(HA_FORM, '', '', '', $match);
            if (!empty($formsdata)) {
                $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }

            $data['crnt_view'] = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
            $data['header'] = array('menu_module' => 'YoungPerson');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/administer_ajaxlist', $data);
            } else {
                $data['main_content'] = 'MedicalView/log_health_assessment';
                $this->parser->parse('/assets/reporttemplate', $data);
            }
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : view appointment data
      @Input  :
      @Output :
      @Date   : 5/09/2017
     */

    public function appointment($ypid) {
        if (is_numeric($ypid)) {
            $match = array("yp_id" => $ypid);
            $fields = array("*");
            $data['YPData'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            if (empty($data['YPData'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('Admin/Reports/MEDS');
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
                $this->session->unset_userdata('appointment_session_data');
            }

            $searchsort_session = $this->session->userdata('appointment_session_data');
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
                    $sortfield = 'appointment_id';
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
            $config['base_url'] = base_url() . 'Admin/' . $this->viewname . '/appointment/' . $ypid;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 5;
                $uri_segment = $this->uri->segment(5);
            }
            //Query
            $table = MEDICAL_PROFESSIONALS_APPOINTMENT . ' as mc';

            $whereCond = 'mc.yp_id = ' . $ypid . ' AND mc.is_delete = "0" ';
            $fields = array("mc.*,concat(mp.title,' ',mp.first_name,' ',mp.surname,' - ',mp.professional) as mp_name");
            $join_tables = array(MEDICAL_PROFESSIONALS . ' as mp' => 'mc.mp_id= mp.mp_id', YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id');
            if (!empty($professional_name)) {
                $whereCond .= ' AND mc.mp_id = ' . $professional_name;
            }

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
            //get mi details
            $match = array('yp_id' => $ypid);
            $data['mp_yp_data'] = $this->common_model->get_records(MEDICAL_PROFESSIONALS, '', '', '', $match);

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

            $this->session->set_userdata('appointment_session_data', $sortsearchpage_data);
            setActiveSession('appointment_session_data'); // set current Session active
            $data['header'] = array('menu_module' => 'Medical');

            //get YP information
            $match = "yp_id = " . $ypid;
            $fields = array("*");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            //get mac form
            $match = array('mp_form_id' => 1);
            $formsdata = $this->common_model->get_records(MP_FORM, '', '', '', $match);
            if (!empty($formsdata)) {
                $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }

            $data['crnt_view'] = $this->viewname;
            $data['headerCss'] = array(
                '0' => base_url() . 'uploads/assets/js/bootstrap-timepicker/css/bootstrap-timepicker.min.css',
            );
            $data['footerJs'] = array(
                '0' => base_url() . 'uploads/assets/js/bootstrap-datetimepicker.min.js',
                '1' => base_url() . 'uploads/assets/js/bootstrap-timepicker/js/bootstrap-timepicker.min.js',
                '2' => base_url() . 'uploads/custom/js/adminMedical/adminMedical.js'
            );
            $data['header'] = array('menu_module' => 'YoungPerson');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/appointment_ajaxlist', $data);
            } else {
                $data['main_content'] = 'Admin/MedicalView/appointment';
                $this->parser->parse('/assets/reporttemplate', $data);
            }
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Read more
      @Input    : yp id
      @Output   :
      @Date   : 05/09/2017
     */
    /*
      @Author : Niral Patel
      @Desc   : view appointment
      @Input    : mp id
      @Output   :
      @Date   : 08/09/2017
     */

    public function appointment_view($id) {
        if (is_numeric($id)) {
            //get mi details
            $match = array('mp_form_id' => 1);
            $formsdata = $this->common_model->get_records(MP_FORM, '', '', '', $match);
            if (!empty($formsdata)) {
                $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }

            $table = MEDICAL_PROFESSIONALS_APPOINTMENT . ' as mc';

            $match = array('mc.appointment_id' => $id);
            $fields = array("mc.*,concat(mp.title,' ',mp.first_name,' ',mp.surname,' - ',mp.professional) as mp_name,mp.yp_id");
            $join_tables = array(MEDICAL_PROFESSIONALS . ' as mp' => 'mc.mp_id= mp.mp_id', YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id');
            $data['mp_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);

            $data['main_content'] = 'Admin/MedicalView/view_appointment';
            $this->parser->parse('/assets/reporttemplate', $data);
        } else {
            show_404();
        }
    }

    public function readmore_appointment($id, $field) {
        if (is_numeric($id)) {
            $params['fields'] = [$field];
            $params['table'] = MEDICAL_PROFESSIONALS_APPOINTMENT;
            $params['match_and'] = 'appointment_id=' . $id . '';
            $data['documents'] = $this->common_model->get_records_array($params);
            $data['field'] = $field;
            $this->load->view($this->viewname . '/readmore', $data);
        } else {
            show_404();
        }
    }

    function export($ypid = '') {
        $this->load->library('excel');
        $filterFinalData = array(
            'yp_id' => $ypid,
            'reportType' => 'MEDS',
            'perPage' => '',
            'uri_segment' => '',
        );
        $recordData = $this->getMEDSData($filterFinalData, $totalRows = false, '', $Fields = true, $is_excel = true); // get coms List Data
        $exceldataHeader = "";
        $exceldataValue = "";
        $headerCount = 1;
        //medical information
        foreach ($recordData['informationData'] as $index => $rowValue) {
            $rowArray = array();
            foreach ($rowValue as $rowindex => $rowData) {

                if (!empty($rowData) && is_json($rowData)) {
                    $jdata = json_decode($rowData);
                    $st = '';
                    if (!empty($jdata)) {

                        $t = 1;
                        foreach ($jdata as $row) {
                            $st .= ' ' . $t . ') ' . nl2br(strip_tags($row->content)) . ' Date :' . configDateTimeFormat($row->date);
                            $t++;
                        }
                    }
                }
                $rowArray[$rowindex] = strip_tags(!empty($st) ? $st : $rowData);
            }
            if ($headerCount === 1) {
                $exceldataHeader[] = $recordData['headerData'];  // Set Header of the generated Excel File
            }
            $exceldataValue[] = $rowArray; // Set values
            $headerCount++;
        }

        // mac content

        $BlankArray = array('');
        //get mac details
        $match = "yp_id = " . $ypid;
        $fields = array("*");
        $mac_details = $this->common_model->get_records(MEDICAL_AUTHORISATIONS_CONSENTS, $fields, '', '', $match);
        //get mac form
        $match = array('mac_form_id' => 1);
        $formsdata = $this->common_model->get_records(MAC_FORM, '', '', '', $match);
        if (!empty($formsdata)) {
            $mac_form_data = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        if (!empty($mac_form_data)) {
            foreach ($mac_form_data as $row) {
                $macHeader[] = $row['label'];
                $macvalue[] = isset($row['name']) ? !empty($mac_details[0][$row['name']]) ? nl2br(strip_tags($mac_details[0][$row['name']])) : '' : '';
            }
        }
        //get omi details
        $match = "yp_id = " . $ypid;
        $fields = array("*");
        $omi_details = $this->common_model->get_records(OTHER_MEDICAL_INFORMATION, $fields, '', '', $match);

        //get omi form
        $match = array('omi_form_id' => 1);
        $formsdata = $this->common_model->get_records(OMI_FORM, '', '', '', $match);
        if (!empty($formsdata)) {
            $omi_form_data = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        if (!empty($omi_form_data)) {
            foreach ($omi_form_data as $row) {
                $omiHeader[] = $row['label'];
                $omivalue[] = isset($row['name']) ? !empty($omi_details[0][$row['name']]) ? nl2br(strip_tags($omi_details[0][$row['name']])) : '' : '';
            }
        }
        //get mi details
        $match = "yp_id = " . $ypid;
        $fields = array("*");
        $miform_details = $this->common_model->get_records(MEDICAL_INOCULATIONS, $fields, '', '', $match);

        //get mi form
        $match = array('mi_form_id' => 1);
        $formsdata = $this->common_model->get_records(MI_FORM, '', '', '', $match);
        if (!empty($formsdata)) {
            $mi_form_data = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        if (!empty($mi_form_data)) {
            foreach ($mi_form_data as $row) {
                $miHeader[] = $row['label'];
                $mivalue[] = isset($row['name']) ? !empty($miform_details[0][$row['name']]) ? nl2br(strip_tags($miform_details[0][$row['name']])) : '' : '';
            }
        }
        //get mp form
        $match = array('mp_form_id' => 1);
        $formsdata = $this->common_model->get_records(MP_FORM, '', '', '', $match);
        if (!empty($formsdata)) {
            $mp_form_data = json_decode($formsdata[0]['form_json_data'], TRUE);
        }

        //get mp details        
        $mp_details = $this->common_model->get_records(MEDICAL_PROFESSIONALS . ' as mp', 'mp.*', '', '', '', '', '', '', '', '', '', array("mp.yp_id" => $ypid));
        $mpHeader = array();
        $mpvalue = array();
        if (!empty($mp_form_data)) {
            foreach ($mp_form_data as $row) {
                $mpHeader[] = $row['label'];
            }
        }
        if (!empty($mp_details)) {
            foreach ($mp_details as $index => $data) {
                if (!empty($mp_form_data)) {
                    foreach ($mp_form_data as $row) {
                        $mpvalue[$index][] = isset($row['name']) ? !empty($data[$row['name']]) ? nl2br(strip_tags($data[$row['name']])) : '' : '';
                    }
                }
            }
        }

        //get mc form
        $match = array('mc_form_id' => 1);
        $formsdata = $this->common_model->get_records(MC_FORM, '', '', '', $match);
        if (!empty($formsdata)) {
            $mc_form_data = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        //get mc details        
        $mc_details = $this->common_model->get_records(MEDICAL_COMMUNICATION . ' as mc', 'mc.*', '', '', '', '', '', '', '', '', '', array("mc.yp_id" => $ypid));
        $mcHeader = array();
        $mcvalue = array();
        if (!empty($mc_form_data)) {
            foreach ($mc_form_data as $row) {
                $mcHeader[] = $row['label'];
            }
        }
        if (!empty($mc_details)) {
            foreach ($mc_details as $index => $data) {
                if (!empty($mc_form_data)) {
                    foreach ($mc_form_data as $row) {
                        $mcvalue[$index][] = isset($row['name']) ? !empty($data[$row['name']]) ? nl2br(strip_tags($data[$row['name']])) : '' : '';
                    }
                }
            }
        }
        //get med form
        $match = array('m_form_id' => 1);
        $formsdata = $this->common_model->get_records(M_FORM, '', '', '', $match);
        if (!empty($formsdata)) {
            $m_form_data = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        //get mc details        
        $m_details = $this->common_model->get_records(MEDICATION . ' as mc', 'mc.*', '', '', '', '', '', '', '', '', '', array("mc.yp_id" => $ypid));
        $mHeader = array();
        $mvalue = array();
        if (!empty($m_form_data)) {
            foreach ($m_form_data as $row) {
                $mHeader[] = $row['label'];
            }
        }
        if (!empty($m_details)) {
            foreach ($m_details as $index => $data) {
                if (!empty($m_form_data)) {
                    foreach ($m_form_data as $row) {
                        $mvalue[$index][] = isset($row['name']) ? !empty($data[$row['name']]) ? nl2br(strip_tags($data[$row['name']])) : '' : '';
                    }
                }
            }
        }

        //get am form
        $match = array('am_form_id' => 1);
        $formsdata = $this->common_model->get_records(AM_FORM, '', '', '', $match);
        if (!empty($formsdata)) {
            $am_form_data = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        //get am details   
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= mc.staff1', LOGIN . ' as l1' => 'l1.login_id= mc.staff2');

        $am_details = $this->common_model->get_records(ADMINISTER_MEDICATION . ' as mc', array('mc.*,concat(l.firstname," ",l.lastname) as staff1,concat(l.firstname," ",l.lastname) as staff2'), $join_tables, 'left', '', '', '', '', '', '', '', array("mc.yp_id" => $ypid));

        $amHeader = array();
        $amvalue = array();
        if (!empty($am_form_data)) {
            foreach ($am_form_data as $row) {
                $amHeader[] = $row['label'];
            }
        }
        if (!empty($am_details)) {
            foreach ($am_details as $index => $data) {
                if (!empty($am_form_data)) {
                    foreach ($am_form_data as $row) {
                        $amvalue[$index][] = isset($row['name']) ? !empty($data[$row['name']]) ? nl2br(strip_tags($data[$row['name']])) : '' : '';
                    }
                }
            }
        }
        //get ha form
        $match = array('ha_form_id' => 1);
        $formsdata = $this->common_model->get_records(HA_FORM, '', '', '', $match);
        if (!empty($formsdata)) {
            $ha_form_data = json_decode($formsdata[0]['form_json_data'], TRUE);
        }
        //get ha details        
        $ha_details = $this->common_model->get_records(HEALTH_ASSESSMENT . ' as mc', 'mc.*', '', '', '', '', '', '', '', '', '', array("mc.yp_id" => $ypid));
        $haHeader = array();
        $havalue = array();
        if (!empty($ha_form_data)) {
            foreach ($ha_form_data as $row) {
                $haHeader[] = $row['label'];
            }
        }

        if (!empty($ha_details)) {
            foreach ($ha_details as $index => $data) {
                if (!empty($ha_form_data)) {
                    foreach ($ha_form_data as $row) {
                        $havalue[$index][] = isset($row['name']) ? !empty($data[$row['name']]) ? nl2br(strip_tags($data[$row['name']])) : '' : '';
                    }
                }
            }
        }
        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
        /* $titles = array('title 1', 'title 2');
          $sheet = 0;
          foreach($titles as $value){
          if($sheet > 0){
          $this->excel->createSheet();
          $sheet = $this->excel->setActiveSheetIndex($sheet);
          $sheet->setTitle("$value");
          $sheet->getStyle('A1:Z1')->getFont()->setBold(true);
          $sheet->fromArray($exceldataHeader, Null, 'A1')->getStyle('A1')->getFont()->setBold(true); // Set Header Data
          
          $sheet->fromArray($exceldataValue, Null, 'A2'); // Set Fetch Data
          $sheet->fromArray($BlankArray, Null, 'A3'); // Set blank Data
          $sheet->fromArray(array('Medical Authorisations & Consents'), Null, 'A4')->getStyle('A4')->getFont()->setBold(true);
          $sheet->mergeCells('A4:F4');
          $sheet->getStyle('A4')->getAlignment()->applyFromArray(
          array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT));

          $sheet->fromArray($macHeader, Null, 'A5'); // Set mac Header Data
          $sheet->getStyle('A5:Z5')->getFont()->setBold(true);

          $sheet->fromArray($macvalue, Null, 'A6'); // Set mac Fetch Data
          $sheet->fromArray($BlankArray, Null, 'A7'); // Set blank Data

          $sheet->fromArray(array('Other Medical Information'), Null, 'A8')->getStyle('A8')->getFont()->setBold(true);
          $sheet->mergeCells('A8:F8');
          $sheet->getStyle('A8')->getAlignment()->applyFromArray(
          array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT));
          $sheet->fromArray($omiHeader, Null, 'A9'); // Set omi Header Data
          $sheet->getStyle('A9:Z9')->getFont()->setBold(true);
          
          $sheet->fromArray($omivalue, Null, 'A10'); // Set omi Fetch Data
          $sheet->fromArray($BlankArray, Null, 'A11'); // Set blank Data
          $sheet->fromArray(array('Inoculations'), Null, 'A12')->getStyle('A12')->getFont()->setBold(true);
          $sheet->mergeCells('A12:F12');
          $sheet->getStyle('A12')->getAlignment()->applyFromArray(
          array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT));

          $sheet->fromArray($miHeader, Null, 'A13'); // Set omi Header Data
          $sheet->getStyle('A13:Z13')->getFont()->setBold(true);
          
          $sheet->fromArray($mivalue, Null, 'A14'); // Set omi Fetch Data
          //exit;
          //$this->activeSheetIndex =  $sheet->x(0);
          //Do you want something more here
          }else{
          $this->excel->setActiveSheetIndex(0)->setTitle("$value");
          $this->excel->getActiveSheet()->getStyle('A1:Z1')->getFont()->setBold(true);
          $this->excel->getActiveSheet()->fromArray($exceldataHeader, Null, 'A1')->getStyle('A1')->getFont()->setBold(true); // Set Header Data
          
          $this->excel->getActiveSheet()->fromArray($exceldataValue, Null, 'A2'); // Set Fetch Data
          $this->excel->getActiveSheet()->fromArray($BlankArray, Null, 'A3'); // Set blank Data
          $this->excel->getActiveSheet()->fromArray(array('Medical Authorisations & Consents'), Null, 'A4')->getStyle('A4')->getFont()->setBold(true);
          $this->excel->getActiveSheet()->mergeCells('A4:F4');
          $this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->applyFromArray(
          array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT));

          $this->excel->getActiveSheet()->fromArray($macHeader, Null, 'A5'); // Set mac Header Data
          $this->excel->getActiveSheet()->getStyle('A5:Z5')->getFont()->setBold(true);

          $this->excel->getActiveSheet()->fromArray($macvalue, Null, 'A6'); // Set mac Fetch Data
          $this->excel->getActiveSheet()->fromArray($BlankArray, Null, 'A7'); // Set blank Data

          $this->excel->getActiveSheet()->fromArray(array('Other Medical Information'), Null, 'A8')->getStyle('A8')->getFont()->setBold(true);
          $this->excel->getActiveSheet()->mergeCells('A8:F8');
          $this->excel->getActiveSheet()->getStyle('A8')->getAlignment()->applyFromArray(
          array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT));
          $this->excel->getActiveSheet()->fromArray($omiHeader, Null, 'A9'); // Set omi Header Data
          $this->excel->getActiveSheet()->getStyle('A9:Z9')->getFont()->setBold(true);
          
          $this->excel->getActiveSheet()->fromArray($omivalue, Null, 'A10'); // Set omi Fetch Data
          $this->excel->getActiveSheet()->fromArray($BlankArray, Null, 'A11'); // Set blank Data
          $this->excel->getActiveSheet()->fromArray(array('Inoculations'), Null, 'A12')->getStyle('A12')->getFont()->setBold(true);
          $this->excel->getActiveSheet()->mergeCells('A12:F12');
          $this->excel->getActiveSheet()->getStyle('A12')->getAlignment()->applyFromArray(
          array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT));

          $this->excel->getActiveSheet()->fromArray($miHeader, Null, 'A13'); // Set omi Header Data
          $this->excel->getActiveSheet()->getStyle('A13:Z13')->getFont()->setBold(true);
          
          $this->excel->getActiveSheet()->fromArray($mivalue, Null, 'A14'); // Set omi Fetch Data
          
          $this->activeSheetIndex = $this->excel->setActiveSheetIndex(0);
          }
          $sheet++;
          } */

        //Fill data 

        $this->excel->getActiveSheet()->getStyle('A1:Z1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->fromArray($exceldataHeader, Null, 'A1')->getStyle('A1')->getFont()->setBold(true); // Set Header Data
        $this->excel->getActiveSheet()->fromArray($exceldataValue, Null, 'A2'); // Set Fetch Data
        $this->excel->getActiveSheet()->fromArray($BlankArray, Null, 'A3'); // Set blank Data
        $this->excel->getActiveSheet()->fromArray(array('Medical Authorisations & Consents'), Null, 'A4')->getStyle('A4')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A4:F4');
        $this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->applyFromArray(
                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT));

        $this->excel->getActiveSheet()->fromArray($macHeader, Null, 'A5'); // Set mac Header Data
        $this->excel->getActiveSheet()->getStyle('A5:Z5')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->fromArray($macvalue, Null, 'A6'); // Set mac Fetch Data
        $this->excel->getActiveSheet()->fromArray($BlankArray, Null, 'A7'); // Set blank Data

        $this->excel->getActiveSheet()->fromArray(array('Other Medical Information'), Null, 'A8')->getStyle('A8')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A8:F8');
        $this->excel->getActiveSheet()->getStyle('A8')->getAlignment()->applyFromArray(
                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT));
        $this->excel->getActiveSheet()->fromArray($omiHeader, Null, 'A9'); // Set omi Header Data
        $this->excel->getActiveSheet()->getStyle('A9:Z9')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->fromArray($omivalue, Null, 'A10'); // Set omi Fetch Data
        $this->excel->getActiveSheet()->fromArray($BlankArray, Null, 'A11'); // Set blank Data
        $this->excel->getActiveSheet()->fromArray(array('Inoculations'), Null, 'A12')->getStyle('A12')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A12:F12');
        $this->excel->getActiveSheet()->getStyle('A12')->getAlignment()->applyFromArray(
                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT));

        $this->excel->getActiveSheet()->fromArray($miHeader, Null, 'A13'); // Set omi Header Data
        $this->excel->getActiveSheet()->getStyle('A13:Z13')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->fromArray($mivalue, Null, 'A14'); // Set omi Fetch Data

        $this->excel->getActiveSheet()->fromArray($BlankArray, Null, 'A15'); // Set blank Data
        $this->excel->getActiveSheet()->fromArray(array('Medical Professionals'), Null, 'A16')->getStyle('A16')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A16:F16');
        $this->excel->getActiveSheet()->getStyle('A16')->getAlignment()->applyFromArray(
                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT));

        $this->excel->getActiveSheet()->fromArray($mpHeader, Null, 'A17'); // Set omi Header Data
        $this->excel->getActiveSheet()->getStyle('A17:Z17')->getFont()->setBold(true);
        $cellNo = '18';
        if (!empty($mpvalue)) {

            foreach ($mpvalue as $mpRow) {
                $this->excel->getActiveSheet()->fromArray($mpRow, Null, 'A' . $cellNo);
                $cellNo++;
            }
        }
        //mc data
        $this->excel->getActiveSheet()->fromArray($BlankArray, Null, 'A' . $cellNo++); // Set blank Data
        $this->excel->getActiveSheet()->fromArray(array('Medical Communication Log'), Null, 'A' . $cellNo)->getStyle('A' . $cellNo)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A' . $cellNo . ':F' . $cellNo);
        $this->excel->getActiveSheet()->getStyle('A' . $cellNo++)->getAlignment()->applyFromArray(
                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT));

        $this->excel->getActiveSheet()->fromArray($mcHeader, Null, 'A' . $cellNo); // Set omi Header Data
        $this->excel->getActiveSheet()->getStyle('A' . $cellNo . ':Z' . $cellNo++)->getFont()->setBold(true);
        if (!empty($mcvalue)) {

            foreach ($mcvalue as $mpRow) {
                $this->excel->getActiveSheet()->fromArray($mpRow, Null, 'A' . $cellNo);
                $cellNo++;
            }
        }

        //m data
        $this->excel->getActiveSheet()->fromArray($BlankArray, Null, 'A' . $cellNo++); // Set blank Data
        $this->excel->getActiveSheet()->fromArray(array('Medication'), Null, 'A' . $cellNo)->getStyle('A' . $cellNo)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A' . $cellNo . ':F' . $cellNo);
        $this->excel->getActiveSheet()->getStyle('A' . $cellNo++)->getAlignment()->applyFromArray(
                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT));

        $this->excel->getActiveSheet()->fromArray($mHeader, Null, 'A' . $cellNo); // Set omi Header Data
        $this->excel->getActiveSheet()->getStyle('A' . $cellNo . ':Z' . $cellNo++)->getFont()->setBold(true);
        if (!empty($mvalue)) {

            foreach ($mvalue as $mpRow) {
                $this->excel->getActiveSheet()->fromArray($mpRow, Null, 'A' . $cellNo);
                $cellNo++;
            }
        }

        //m data
        $this->excel->getActiveSheet()->fromArray($BlankArray, Null, 'A' . $cellNo++); // Set blank Data
        $this->excel->getActiveSheet()->fromArray(array('Administration History'), Null, 'A' . $cellNo)->getStyle('A' . $cellNo)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A' . $cellNo . ':F' . $cellNo);
        $this->excel->getActiveSheet()->getStyle('A' . $cellNo++)->getAlignment()->applyFromArray(
                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT));

        $this->excel->getActiveSheet()->fromArray($amHeader, Null, 'A' . $cellNo); // Set omi Header Data
        $this->excel->getActiveSheet()->getStyle('A' . $cellNo . ':Z' . $cellNo++)->getFont()->setBold(true);
        if (!empty($amvalue)) {

            foreach ($amvalue as $mpRow) {
                $this->excel->getActiveSheet()->fromArray($mpRow, Null, 'A' . $cellNo);
                $cellNo++;
            }
        }

        //ha data
        $this->excel->getActiveSheet()->fromArray($BlankArray, Null, 'A' . $cellNo++); // Set blank Data
        $this->excel->getActiveSheet()->fromArray(array('Health Assessment'), Null, 'A' . $cellNo)->getStyle('A' . $cellNo)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A' . $cellNo . ':F' . $cellNo);
        $this->excel->getActiveSheet()->getStyle('A' . $cellNo++)->getAlignment()->applyFromArray(
                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT));

        $this->excel->getActiveSheet()->fromArray($haHeader, Null, 'A' . $cellNo); // Set omi Header Data
        $this->excel->getActiveSheet()->getStyle('A' . $cellNo . ':Z' . $cellNo++)->getFont()->setBold(true);
        if (!empty($havalue)) {

            foreach ($havalue as $mpRow) {
                $this->excel->getActiveSheet()->fromArray($mpRow, Null, 'A' . $cellNo);
                $cellNo++;
            }
        }

        $this->activeSheetIndex = $this->excel->setActiveSheetIndex(0);


        $fileName = $reportType . date('Y-m-d H:i:s') . '.xls'; // Generate file name

        $this->downloadExcelFile($this->excel, $fileName); // download function Xls file function call
    }

    /**
     * @Autor Niral Patel
     * @Desc downloadExcelfile
     * @param type $excel     
     * @return type
     * @Date 31th June 2017
     */
    Protected function downloadExcelFile($objExcel, $fileName) {
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

    /**
     * @Author : Niral Patel
     * @param type $filterData
     * @param type $totalRows
     * @return type
     * @Date   : 24th August 2017
     */
    private function getMEDSData($filterData, $totalRows = false, $postData, $Fields = true, $is_excel = false) {

        $headerAndFieldData = $this->getHeaderMedsDefaultFieldName();



        if (!empty($headerAndFieldData)) {
            //start - Query
            $tableName = MEDICAL_INFORMATION . ' as me';

            $fieldString = !empty($headerAndFieldData['passField']) ? $headerAndFieldData['passField'] : '';

            if ($Fields) {
                if ($is_excel) {
                    $field_id = '';
                } else {
                    $field_id = ',me.yp_id as id';
                }
                $fields = array($fieldString . '       
                        ypd.yp_fname, ypd.yp_lname, ypd.created_date, ypd.date_of_birth,me.medical_number,me.date_received,me.allergies_and_meds_not_to_be_used' . $field_id);
                $flds = array($fieldString . '       
                        ypd.yp_fname, ypd.yp_lname, ypd.date_of_birth, ypd.created_date,me.medical_number,me.date_received,me.allergies_and_meds_not_to_be_used,id');
            }
            $join_tables = array(
                YP_DETAILS . ' as ypd' => 'me.yp_id = ypd.yp_id',
                LOGIN . ' as l' => 'l.login_id = ypd.created_by',
            );

            $whereCond = 'ypd.status = "active" AND ypd.is_deleted = "0" ';

            if (!empty($filterData['yp_id'])) {
                $whereCond .= ' AND me.yp_id = ' . $filterData['yp_id'];
            }


            if (!$totalRows) {

                $informationData = $this->common_model->get_records($tableName, $fields, $join_tables, 'left', $whereCond, '', $filterData['perPage'], $filterData['uri_segment'], !empty($postData['sortfield']) ? $postData['sortfield'] : '', !empty($postData['sortby']) ? $postData['sortby'] : '', '', '');

                return $returnData = array(
                    'headerData' => $headerAndFieldData['headerName'],
                    'fieldData' => explode(',', $flds[0]),
                    'informationData' => $informationData
                );
            }

            if ($totalRows) {
                return $this->common_model->get_records($tableName, $fields, $join_tables, 'left', $whereCond, '', '', '', '', '', '', '', '', '', '1');
            }
        } else {
            return '';
        }
    }

    private function getHeaderMedsDefaultFieldName($tableName = '', $match_Id = '', $type = '', $is_excel = false) {

        $returnArray = array();


        $passHeaderArray[] = 'First Name';
        $passHeaderArray[] = 'last Name';
        $passHeaderArray[] = 'Create Date';
        $passHeaderArray[] = 'Date Of Birth';
        $passHeaderArray[] = 'Medical Number';
        $passHeaderArray[] = 'Date Recived';
        $passHeaderArray[] = 'Allergies & Meds not to be Used';

        $returnArray = array(
            'headerName' => $passHeaderArray,
            'passField' => !empty($passRow) ? $passRow : ''
        );

        return $returnArray;
    }

}
