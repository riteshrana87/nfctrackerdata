<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ArchiveAAI extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session', 'm_pdf'));
//        $this->load->model('AAI_model');
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Main Listing Page
      @Date   : 21/01/2019
     */

    public function index($incident_id,$ypId = 0) {
        if (is_numeric($ypId) && is_numeric($incident_id)) {
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($ypId, $fields);
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/' . $ypId);
            }
            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = RECORD_PER_PAGE;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('archive_aai_data');
            }

            $searchsort_session = $this->session->userdata('archive_aai_data');
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
                    $sortfield = 'archive_incident_id';
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
                    $config['per_page'] = RECORD_PER_PAGE;
                    $data['perpage'] = RECORD_PER_PAGE;
                }
            }
            //pagination configuration
            $config['first_link'] = 'First';
            $config['base_url'] = base_url() . $this->viewname . '/index/' .$incident_id.'/'. $ypId;
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 5;
                $uri_segment = $this->uri->segment(5);
            }

            //Query
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
           $table = ARCHIVE_AAI_MAIN . ' as ai';
            $where = array("ai.yp_id" => $ypId);
            $fields = array("ai.*,ch.care_home_name");
            $join_tables = array(CARE_HOME . ' as ch' => 'ch.care_home_id = ai.care_home_id');

            if (!empty($searchtext)) {
                $searchtext = html_entity_decode(trim(addslashes($searchtext)));
                $match = array("ai.yp_id" => $ypId);
                $formated_date = dateformat($searchtext);
                $where_search = '('
                        . 'ai.reference_number LIKE "%' . $searchtext . '%" OR '
                        . 'ai.date_of_incident LIKE "%' . $formated_date . '%" OR '
                        . 'ai.incident_id LIKE "%' . $searchtext . '%" OR '
                        . 'ch.care_home_name LIKE "%' . $searchtext . '%")';

                $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match, '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);

                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match, '', '', '', $sortfield, $sortby, '', $where_search, '', '', '1');
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
            $this->session->set_userdata('archive_aai_data', $sortsearchpage_data);
            setActiveSession('archive_aai_data'); // set current Session active
            $data['ypId'] = $ypId;
            $data['incident_id'] = $incident_id;
            $data['crnt_view'] = $this->viewname;
            $data['isCareIncident'] = 1;
            $data['header'] = array('menu_module' => 'AAI');
            $data['footerJs'][0] = base_url('uploads/custom/js/AAI/AAI.js');
            if ($this->input->is_ajax_request()) {
                $this->load->view($this->viewname . '/ajaxlist', $data);
            } else {
                $data['main_content'] = '/archive_accident_and_incident';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Edit Incident Form
      @Date   : 21/01/2018
    */

    public function view($incidentId,$archive_incidentId, $formNumber = 1) {
        $incidentData = $this->common_model->get_records(ARCHIVE_AAI_MAIN, '', '', '', array('incident_id' => $incidentId,'archive_incident_id' => $archive_incidentId));
        $incidentData = $incidentData[0];
        $allForms = $this->common_model->get_records(AAI_FORM, '', '', '');
        $allForms = array_combine(range(1, count($allForms)), array_values($allForms));
        /* type of incident form start */
        $data = array(
            'is_pi' => $incidentData['is_pi'],
            'is_yp_missing' => $incidentData['is_yp_missing'],
            'is_yp_injured' => $incidentData['is_yp_injured'],
            'is_yp_complaint' => $incidentData['is_yp_complaint'],
            'is_yp_safeguarding' => $incidentData['is_yp_safeguarding'],
            'is_staff_injured' => $incidentData['is_staff_injured'],
            'is_other_injured' => $incidentData['is_other_injured'],
            'review_status' => $incidentData['review_status'],
            'manager_review_status' => $incidentData['manager_review_status'],
        );

        /* type of incident form start */

        /* entry form start */
        $entryForm = $allForms[AAI_MAIN_ENTRY_FORM_ID];
        if (!empty($entryForm)) {
            $data['entry_form_data'] = json_decode($entryForm['form_json_data'], TRUE);
        }

        $table = AAI_DROPDOWN . ' as dr';
        $where = array("d.status" => "1", "dr.status" => "1");
        $fields = array("dr.dropdown_id", "dr.title", "dr.prefix", "count(d.option_id) as total_options", "GROUP_CONCAT( DISTINCT CONCAT(d.option_id,'|',d.title) ORDER BY d.option_id SEPARATOR ';') as dropdown_options");
        $joinTables = array(AAI_DROPDOWN_OPTION . ' as d' => 'd.dropdown_id = dr.dropdown_id');
        $groupBy = "dr.dropdown_id";
        $data['dropdown_data'] = $this->common_model->get_records($table, $fields, $joinTables, 'left', '', '', '', '', '', '', $groupBy, $where);
        foreach ($data['entry_form_data'] as $key => $value) {
            if ($value['type'] == 'select') {
                foreach ($data['dropdown_data'] as $key1 => $value1) {
                    if ($value['description'] == $value1['prefix']) {
                        if ($value1['total_options'] > 0) {
                            $optionsArray = explode(';', $value1['dropdown_options']);
                            foreach ($optionsArray as $op => $v) {
                                $OpArray = explode('|', $v);
                                $finalOptionsArray[$OpArray[0]] = $OpArray[1];
                                $data['entry_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                $data['entry_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                            }
                        }
                    }
                }
            }
        }


        //prefix for type data
        foreach ($data['dropdown_data'] as $key1 => $value1) {
            if ($value1['prefix'] == 'pre_outside_agency') {
                if ($value1['total_options'] > 0) {
                    $optionsArray = explode(';', $value1['dropdown_options']);
                    foreach ($optionsArray as $op => $v) {
                        $OpArray = explode('|', $v);
                        $finalOptionsArray[$OpArray[0]] = $OpArray[1];
                        $data['pre_outside_agency'][$op]['label'] = $OpArray[1];
                        $data['pre_outside_agency'][$op]['value'] = $OpArray[0];
                    }
                }
            }
        }

        //prefix for pre_outside_agency data
        foreach ($data['dropdown_data'] as $key1 => $value1) {
            if ($value1['prefix'] == 'type') {
                if ($value1['total_options'] > 0) {
                    $optionsArray = explode(';', $value1['dropdown_options']);
                    foreach ($optionsArray as $op => $v) {
                        $OpArray = explode('|', $v);
                        $finalOptionsArray[$OpArray[0]] = $OpArray[1];
                        $data['type'][$op]['label'] = $OpArray[1];
                        $data['type'][$op]['value'] = $OpArray[0];
                    }
                }
            }
        }

        //prefix for Position_of_yp data
        foreach ($data['dropdown_data'] as $key1 => $value1) {
            if ($value1['prefix'] == 'position_of_yp') {
                if ($value1['total_options'] > 0) {
                    $optionsArray = explode(';', $value1['dropdown_options']);
                    foreach ($optionsArray as $op => $v) {
                        $OpArray = explode('|', $v);
                        $finalOptionsArray[$OpArray[0]] = $OpArray[1];
                        $data['position_of_yp'][$op]['label'] = $OpArray[1];
                        $data['position_of_yp'][$op]['value'] = $OpArray[0];
                    }
                }
            }
        }
        
        /*code by Ritesh rana*/
        $match = array('aam.incident_id'=> $incidentId,'aam.process_id'=> 'mainform');
        $fields_report = array("aam.*");
        $archive_incidentData_main_form = $this->common_model->get_records(ARCHIVE_AAI_MAIN.' as aam',$fields_report, '', '', $match,'','2','','archive_incident_id','desc');

        $editMainEntryData = json_decode($archive_incidentData_main_form[0]['entry_form_data'], true);
        foreach ($editMainEntryData as $key => $value) {
            if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                $editData[$value['name']] = $value;
            }
        }
        foreach ($data['entry_form_data'] as $key => $value) {
            if (isset($value['name']) && $value['name'] == $editData[$value['name']]['name']) {
                $data['entry_form_data'][$key]['value'] = str_replace("\'","'",$editData[$value['name']]['value']); 
            }
        }
        $data['entry_report_compiler'] = $editMainEntryData['report_compiler'];
        $data['relatedIncident'] = $editMainEntryData['related_incident'];
        $data['reporting_user'] = $editMainEntryData['reporting_user'];
            
        /*prev incidentData data start*/
            if(!empty($archive_incidentData_main_form[1]['entry_form_data'])){
                $prevmainData = json_decode($archive_incidentData_main_form[1]['entry_form_data'], true);
                    foreach ($prevmainData as $key => $prevalue) {
                        if (isset($prevalue['type']) && $prevalue['type'] != 'button' && $prevalue['type'] != 'header') {
                            $preveditMainData[$prevalue['name']] = $prevalue;
                        }
                    }

                    $data['pre_reporting_user'] = $prevmainData['reporting_user'];
                }
                $data['prevedit_entry_form_data'] = $preveditMainData;
        /*prev incidentData data end*/

        $emailMatch = '(email LIKE "%_@__%.__%")';
        $nfcUsers = $this->common_model->get_records(LOGIN, array('login_id as user_id', 'firstname as first_name', 'lastname as last_name', 'email'), '', '', '', '', '', '', '', '', '', $emailMatch);

        function appendNFCType($n) {
            $n ['user_type'] = 'N';
            $n ['job_title'] = '';
            $n ['work_location'] = '';
            return $n;
        }

        $nfcUsers = array_map("appendNFCType", $nfcUsers);
        $bambooUsers = $this->common_model->get_records(BAMBOOHR_USERS, array('user_id', 'first_name', 'last_name', 'email', 'job_title', 'work_location'), '', '', '', '', '', '', '', '', '', $emailMatch);

        function appendBambooType($n) {
            $n ['user_type'] = 'B';
            return $n;
        }
        $bambooUsers = array_map("appendBambooType", $bambooUsers);
        $data['bambooNfcUsers'] = array_merge($bambooUsers, $nfcUsers); 
        $data['loggedInUser'] = $this->session->userdata['LOGGED_IN'];
        $table = AAI_MAIN . ' as a';
        $join_tables = array(YP_DETAILS . ' as yp' => 'a.yp_id=yp.yp_id', CARE_HOME . ' as c' => 'c.care_home_id=yp.care_home');
        $dateQuery = 'date(a.created_date) >= CURDATE() - INTERVAL 14 DAY ';
        $data['YPIncidentData'] = $this->common_model->get_records($table, '', $join_tables, '', array('a.yp_id' => $incidentData['yp_id'],'a.incident_id !=' => $incidentId), '', '', '', '', '', '', $dateQuery);
        /* entry form end */

        /* code by Ritesh Rana */
         /* L1 start */
        $l1Form = $allForms[AAI_L1_FORM_ID];
        if (!empty($l1Form)) {
            $data['l1_form_data'] = json_decode($l1Form['form_json_data'], TRUE);
            foreach ($data['l1_form_data'] as $key => $value) {
                if ($value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]] = $OpArray[1];
                                    $data['l1_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l1_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }
             $match = array('aam.incident_id' => $incidentId, 'aam.process_id' => 'l1');
            $fields_report = array("aam.l1_form_data");
            $incidentData_l1_data = $this->common_model->get_records(ARCHIVE_AAI_MAIN . ' as aam', $fields_report, '', '', $match, '', '2', '', 'archive_incident_id', 'desc');
            if (isset($incidentData_l1_data[0]['l1_form_data']) && !empty($incidentData_l1_data[0]['l1_form_data'])) {
                $editl1Data = json_decode($incidentData_l1_data[0]['l1_form_data'], true);
                foreach ($editl1Data as $key => $value) {
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editl1Data[$value['name']] = $value;
                    }
                }
                foreach ($data['l1_form_data'] as $key => $value) {
                    if (isset($value['name']) && $value['name'] == $editl1Data[$value['name']]['name']) {
                        $data['l1_form_data'][$key]['value'] = str_replace("\'", "'", $editl1Data[$value['name']]['value']);
                    }
                }
                $data['l1_report_compiler'] = $editl1Data['report_compiler'];
                $data['l1_total_duration'] = $editl1Data['l1_total_duration'];
            }
            
            /* L1 PREV DATA */
            
            $prev_incidentData_l1 = $incidentData_l1_data[1];
            if (!empty($prev_incidentData_l1['l1_form_data'])) {
                $prevl1Data = json_decode($prev_incidentData_l1['l1_form_data'], true);
                foreach ($prevl1Data as $key => $prevalue) {
                    if (isset($prevalue['type']) && $prevalue['type'] != 'button' && $prevalue['type'] != 'header') {
                        $preveditl1Data[$prevalue['name']] = $prevalue;
                    }
                }
                $data['l1_prev_report_compiler'] = $prevl1Data['report_compiler'];
                $data['l1_prev_total_duration'] = $prevl1Data['l1_total_duration'];
            }
            $data['preveditl1Data'] = $preveditl1Data;
        }
        /* code end ritesh Rana */
        /* L1 end */

        /*cide by ritesh Rana*/
        /* L2 start */
        $l2Form = $allForms[AAI_L2NL3_FORM_ID];
        if (!empty($l2Form)) {
            $data['l2_form_data'] = json_decode($l2Form['form_json_data'], TRUE);
            foreach ($data['l2_form_data'] as $key => $value) {
                if ($value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]] = $OpArray[1];
                                    $data['l2_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l2_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }

            $match = array('aam.incident_id'=> $incidentId,'aam.process_id'=> 'l2');
                $fields_report = array("aam.l2_l3_form_data");
                $incidentData_l2 = $this->common_model->get_records(ARCHIVE_AAI_MAIN.' as aam',$fields_report, '', '', $match,'','2','','archive_incident_id','desc');

            if (isset($incidentData_l2[0]['l2_l3_form_data']) && !empty($incidentData_l2[0]['l2_l3_form_data'])) {
                $editl2Data = json_decode($incidentData_l2[0]['l2_l3_form_data'], true);
                
                foreach ($editl2Data as $key => $value) {
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editl2Data[$value['name']] = $value;
                    }
                }
                foreach ($data['l2_form_data'] as $key => $value) {
                    if (isset($value['name']) && $value['name'] == $editl2Data[$value['name']]['name']) {
                        $data['l2_form_data'][$key]['value'] = str_replace("\'","'",$editl2Data[$value['name']]['value']);
                    }
                    if(isset($value) && $value['name'] == 'l2_involved_other'){
                        $data['l2_involved_other_data'] = str_replace("\'", "'", $editl2Data[$value['name']]['value']);
                    }
                }
                $data['l2_report_compiler'] = $editl2Data['report_compiler'];
                $data['l2_total_duration'] = $editl2Data['l2_total_duration'];

                /* start code by ritesh Rana*/
                /*start Sequence of events */
               
                $l2sequence_data                      = array();
                $l2sequence_data['l2sequence_number'] = $editl2Data['l2sequence_number'];
                $l2sequence_data['l2position']        = $editl2Data['l2position'];
                $l2sequence_data['l2type']            = $editl2Data['l2type'];
                $l2sequence_data['l2comments']        = $editl2Data['l2comments'];
                $l2sequence_data['l2time_sequence']   = $editl2Data['l2time_sequence'];
                
                $l2seqresult = array();
                $count_se    = count($editl2Data['l2sequence_number']);
                foreach ($l2sequence_data as $kk => $vv) {
                    for ($se = 0; $se < $count_se; $se++) {
                        if($kk == 'l2time_sequence'){
                            $l2seqresult[$se][$kk] = $l2sequence_data[$kk][$se];
                            $l2seqresult[$se]['l2time_sequence_str']   = strtotime($l2sequence_data[$kk][$se]);
                        }else{
                            $l2seqresult[$se][$kk]                                        = $l2sequence_data[$kk][$se];
                        }
                        
                        $doc_se                                                       = $se + 1;
                        $l2seqresult[$se]['l2Who_was_involved_in_incident' . $doc_se] = $editl2Data['l2Who_was_involved_in_incident' . $doc_se];
                    }
                }
                $data['l2sequence_events'] = $l2seqresult;
                $data['l2sequence_events'] = array_sort($l2seqresult,'l2time_sequence_str',SORT_ASC);

                /*end Sequence of events */
                /*start Medical Observations*/
                $l2sequence_data_mo = array();
                $l2sequence_data_mo['l2medical_observations_after_minutes']=$editl2Data['l2medical_observations_after_minutes'];
                $l2sequence_data_mo['l2time_medical']=$editl2Data['l2time_medical'];
                $l2sequence_data_mo['l2comments_mo']=$editl2Data['l2comments_mo'];
                
                $l2seqresult_mo = array();
                $count_mo = count($editl2Data['l2medical_observations_after_minutes']);
                foreach($l2sequence_data_mo as $kk => $vv){
                    for($mo=0; $mo<$count_mo;$mo++){
                        $l2seqresult_mo[$mo][$kk] = $l2sequence_data_mo[$kk][$mo];
                        $doc_mo = $mo+1;
                        $l2seqresult_mo[$mo]['l2_medical_observation_taken'.$doc_mo] = $editl2Data['l2_medical_observation_taken'.$doc_mo];
                        $l2seqresult_mo[$mo]['l2Observation_taken_by'.$doc_mo] = $editl2Data['l2Observation_taken_by'.$doc_mo];
                    }
                }
                $data['l2medical_observations'] = $l2seqresult_mo;
                /*End Medical Observations*/

                /*L2 PREV DATA*/
            if(!empty($incidentData_l2[1]['l2_l3_form_data'])){
                $prevl2Data = json_decode($incidentData_l2[1]['l2_l3_form_data'], true);
                    foreach ($prevl2Data as $key => $prevalue) {
                        if (isset($prevalue['type']) && $prevalue['type'] != 'button' && $prevalue['type'] != 'header') {
                            $preveditl2Data[$prevalue['name']] = $prevalue;
                        }
                    }
                $data['l2_prev_report_compiler'] = $prevl2Data['report_compiler'];
                $data['l2_prev_total_duration'] = $prevl2Data['l2_total_duration'];
                }
                $data['preveditl2Data'] = $preveditl2Data;

                /*prev l2 sequence data start*/
                $l2sequence_prev_data = array();
                $l2sequence_prev_data['l2sequence_number']=$prevl2Data['l2sequence_number'];
                $l2sequence_prev_data['l2position']=$prevl2Data['l2position'];
                $l2sequence_prev_data['l2type']=$prevl2Data['l2type'];
                $l2sequence_prev_data['l2comments']=$prevl2Data['l2comments'];
                $l2sequence_prev_data['l2time_sequence']=$prevl2Data['l2time_sequence'];

                $l2seqresult_prev = array();
                $count            = count($prevl2Data['l2sequence_number']);
                foreach ($l2sequence_prev_data as $kk => $vv) {
                    for ($i = 0; $i < $count; $i++) {

                        if($kk == 'l2time_sequence'){
                            $l2seqresult_prev[$i][$kk] = $l2sequence_prev_data[$kk][$i];    
                            $l2seqresult_prev[$i]['l2time_sequence_str']   = strtotime($l2sequence_prev_data[$kk][$i]);
                         }else{
                            $l2seqresult_prev[$i][$kk] = $l2sequence_prev_data[$kk][$i];
                         }   
                        $doc = $i + 1;
                        $l2seqresult_prev[$i]['l2Who_was_involved_in_incident' . $doc] = $prevl2Data['l2Who_was_involved_in_incident' . $doc];
                    }
                }
                $data['l2seqresult_prev'] = $l2seqresult_prev;
                $data['l2seqresult_prev'] = array_sort($l2seqresult_prev,'l2time_sequence_str',SORT_ASC);
                
                /*prev l2 sequence data end*/

                /*Prev start Medical Observations*/
                $l2prev_sequence_data_mo = array();
                $l2prev_sequence_data_mo['l2medical_observations_after_minutes']=$prevl2Data['l2medical_observations_after_minutes'];
                $l2prev_sequence_data_mo['l2time_medical']= $prevl2Data['l2time_medical'];
                $l2prev_sequence_data_mo['l2comments_mo']= $prevl2Data['l2comments_mo'];

                $l2seqresult_prev_mo = array();
                $count = count($prevl2Data['l2medical_observations_after_minutes']);
                foreach($l2prev_sequence_data_mo as $kk => $vv){
                    for($i=0; $i<$count;$i++){
                        $l2seqresult_prev_mo[$i][$kk] = $l2prev_sequence_data_mo[$kk][$i];
                        $doc = $i+1;
                        $l2seqresult_prev_mo[$i]['l2_medical_observation_taken'.$doc] = $prevl2Data['l2_medical_observation_taken'.$doc];
                        $l2seqresult_prev_mo[$i]['l2Observation_taken_by'.$doc] = $prevl2Data['l2Observation_taken_by'.$doc];
                    }
                }

                $data['l2_prev_medical_observations'] = $l2seqresult_prev_mo;
                /*Prev end Medical Observations*/
                /* end code by ritesh Rana*/
            }
        }
        /* L2 end */
        
         /* L4 form start */
        $l4Form = $allForms[AAI_L4_FORM_ID];
        if (!empty($l4Form)) {
            $data['l4_form_data'] = json_decode($l4Form['form_json_data'], true);
            foreach ($data['l4_form_data'] as $key => $value) {
                if ($value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray                                            = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]]                     = $OpArray[1];
                                    $data['l4_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l4_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }
			 $match = array('aam.incident_id'=> $incidentId,'aam.process_id'=> 'l4');
                $fields_report = array("aam.l4_form_data");
                $incidentData_l4 = $this->common_model->get_records(ARCHIVE_AAI_MAIN.' as aam',$fields_report, '', '', $match,'','2','','archive_incident_id','desc');
				
            if (isset($incidentData_l4[0]['l4_form_data']) && !empty($incidentData_l4[0]['l4_form_data'])) {
                $editl4Data = json_decode($incidentData_l4[0]['l4_form_data'], true);
				
                foreach ($editl4Data as $key => $value) {
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editl4Data[$value['name']] = $value;
                    }
                }
                foreach ($data['l4_form_data'] as $key => $value) {
                    if (isset($value['name']) && $value['name'] == $editl4Data[$value['name']]['name']) {
                        $data['l4_form_data'][$key]['value'] = str_replace("\'", "'", $editl4Data[$value['name']]['value']);
                    }
                }
				$data['l4calculate_notification_worker']=$editl4Data['calculate_notification_worker'];
				
				$data['l4calculate_notification_missing']=$editl4Data['calculate_notification_missing'];
                $l4missing_yp               = array();
                $l4return_yp                = array();
                $l4sequence_data            = array();
                $l4missing_yp[]             = $editl4Data['person_informed_missing_team'];
                $l4missing_yp[]             = $editl4Data['name_of_person_informed_missing'];
                $l4missing_yp[]             = $editl4Data['badge_number_person_missing'];
                $l4missing_yp[]             = $editl4Data['contact_number_person_missing'];
                $l4missing_yp[]             = $editl4Data['contact_email_person_missing'];
                $l4missing_yp[]             = $editl4Data['informed_by_person_missing'];
                $l4missing_yp[]             = $editl4Data['date_event'];
                $l4missing_yp[]             = $editl4Data['time_event'];
                $l4return_yp[]              = $editl4Data['person_informed_return_team'];
                $l4return_yp[]              = $editl4Data['name_of_person_informed_return'];
                $l4return_yp[]              = $editl4Data['badge_number_person_return'];
                $l4return_yp[]              = $editl4Data['contact_number_person_return'];
                $l4return_yp[]              = $editl4Data['contact_email_person_return'];
                $l4return_yp[]              = $editl4Data['informed_by_person_return'];
                $l4return_yp[]              = $editl4Data['person_return_date_event'];
                $l4return_yp[]              = $editl4Data['person_return_time_event'];
                $data['l4_report_compiler'] = $editl4Data['report_compiler'];
                $data['l4_report_compiler']              = $editl4Data['l4_report_compiler'];
				$data['l4_yp_injured']                   = $editl4Data['l4_yp_injured'];
				$data['l4_is_complaint']                 = $editl4Data['l4_is_complaint'];
				$data['l4_is_staff_injured']             = $editl4Data['l4_is_staff_injured'];
				$data['l4_is_anyone_injured']            = $editl4Data['l4_is_anyone_injured'];
				$data['if_anyone_injured']               = $editl4Data['if_anyone_injured'];
				$data['l4_is_yp_offered_treatment']      = $editl4Data['l4_is_yp_offered_treatment'];
				$data['l4yp_offered_treatment']          = $editl4Data['l4yp_offered_treatment'];
				$data['l4_total_duration_main']          = $editl4Data['l4_total_duration_main'];


                $l4sequence_data                      = array();
                $l4sequence_data['l4seq_sequence_number'] = $editl4Data['l4seq_sequence_number'];
                $l4sequence_data['l4seq_what_happned']        = $editl4Data['l4seq_what_happned'];
                $l4sequence_data['l4seq_date_event']            = $editl4Data['l4seq_date_event'];
                $l4sequence_data['l4seq_time_event']        = $editl4Data['l4seq_time_event'];
                $l4sequence_data['l4seq_communication']   = $editl4Data['l4seq_communication'];

                $l4seqresult = array();
                $count_se    = count($editl4Data['l4seq_sequence_number']);
                foreach ($l4sequence_data as $kk => $vv) {
                    for ($se = 0; $se < $count_se; $se++) {
                        if($kk == 'l4seq_time_event' || $kk =='l4seq_date_event'){
                        $l4seqresult[$se][$kk] = $l4sequence_data[$kk][$se];

                        $date_date_event = dateformat($l4sequence_data['l4seq_date_event'][$se]);

                        $l4seqresult[$se]['l4seq_time_event_str']   = strtotime($date_date_event .' '. $l4sequence_data['l4seq_time_event'][$se]);
                    }else{
                                $l4seqresult[$se][$kk] = $l4sequence_data[$kk][$se];
                    }
                        
                        $doc_se  = $se + 1;
                        $l4seqresult[$se]['l4seq_who' . $doc_se] = $editl4Data['l4seq_who' . $doc_se];
                    }
                }
                $data['l4sequence_data'] = $l4seqresult;
                $data['l4sequence_data'] = array_sort($l4seqresult,'l4seq_time_event_str',SORT_ASC,false);

                foreach ($l4missing_yp as $k => $v) {
                    $l4missingresult[] = array_column($l4missing_yp, $k);
                }

                foreach ($l4return_yp as $l4returnkey => $l4returnvalue) {
                    $l4returnresult[] = array_column($l4return_yp, $l4returnkey);
                }
            }
        }
        if (!empty($l4missingresult)) {
            $data['l4missing_yp'] = array_filter($l4missingresult);
        }

        if (!empty($l4returnresult)) {
            $data['l4return_data'] = array_filter($l4returnresult);
        }

        /* L4 form end */
		/*L4 PREV DATA*/
            if(!empty($incidentData_l4[1]['l4_form_data'])){
                $prevl4Data = json_decode($incidentData_l4[1]['l4_form_data'], true);
				//pr($prevl4Data);die('here');
                    foreach ($prevl4Data as $key => $prevalue) {
                        if (isset($prevalue['type']) && $prevalue['type'] != 'button' && $prevalue['type'] != 'header') {
                            $preveditl4Data[$prevalue['name']] = $prevalue;
                        }
                    }
					
				$data['preveditl4Data'] = $preveditl4Data;
					//pr($data['preveditl4Data']);exit;
				$data['l4_prev_report_compiler'] = $prevl4Data['report_compiler'];
                $data['l4_prev_total_duration'] = $prevl4Data['l2_total_duration'];
                }
                $data['preveditl2Data'] = $preveditl2Data;

                /*prev l4 sequence data start*/
               $data['l4calculate_notification_worker']=$prevl4Data['calculate_notification_worker'];
				
				$data['l4calculate_notification_missing']=$prevl4Data['calculate_notification_missing']; 
                $prel4missing_yp               = array();
                $prel4return_yp                = array();
                $prel4sequence_data            = array();
                $prel4missing_yp[]             = $prevl4Data['person_informed_missing_team'];
                $prel4missing_yp[]             = $prevl4Data['name_of_person_informed_missing'];
                $prel4missing_yp[]             = $prevl4Data['badge_number_person_missing'];
                $prel4missing_yp[]             = $prevl4Data['contact_number_person_missing'];
                $prel4missing_yp[]             = $prevl4Data['contact_email_person_missing'];
                $prel4missing_yp[]             = $prevl4Data['informed_by_person_missing'];
                $prel4missing_yp[]             = $prevl4Data['date_event'];
                $prel4missing_yp[]             = $prevl4Data['time_event'];
                $prel4sequence_data[]          = $prevl4Data['l4seq_sequence_number'];
                $prel4sequence_data[]          = $prevl4Data['l4seq_who'];
                $prel4sequence_data[]          = $prevl4Data['l4seq_what_happned'];
                $prel4sequence_data[]          = $prevl4Data['l4seq_date_event'];
                $prel4sequence_data[]          = $prevl4Data['l4seq_time_event'];
                $prel4sequence_data[]          = $prevl4Data['l4seq_communication'];
                $prel4return_yp[]              = $prevl4Data['person_informed_return_team'];
                $prel4return_yp[]              = $prevl4Data['name_of_person_informed_return'];
                $prel4return_yp[]              = $prevl4Data['badge_number_person_return'];
                $prel4return_yp[]              = $prevl4Data['contact_number_person_return'];
                $prel4return_yp[]              = $prevl4Data['contact_email_person_return'];
                $prel4return_yp[]              = $prevl4Data['informed_by_person_return'];
                $prel4return_yp[]              = $prevl4Data['person_return_date_event'];
                $prel4return_yp[]              = $prevl4Data['person_return_time_event'];
                $data['l4_report_compiler'] = $prevl4Data['report_compiler'];
                $data['l4_report_compiler']              = $prevl4Data['l4_report_compiler'];
				$data['l4_yp_injured']                   = $prevl4Data['l4_yp_injured'];
				$data['l4_is_complaint']                 = $prevl4Data['l4_is_complaint'];
				$data['l4_is_staff_injured']             = $prevl4Data['l4_is_staff_injured'];
				$data['l4_is_anyone_injured']            = $prevl4Data['l4_is_anyone_injured'];
				$data['if_anyone_injured']               = $prevl4Data['if_anyone_injured'];
				$data['l4_is_yp_offered_treatment']      = $prevl4Data['l4_is_yp_offered_treatment'];
				$data['l4yp_offered_treatment']          = $prevl4Data['l4yp_offered_treatment'];
				$data['l4_total_duration_main']          = $prevl4Data['l4_total_duration_main'];
				
				   foreach ($prel4missing_yp as $k => $v) {
                    $l4premissingresult[] = array_column($prel4missing_yp, $k);
                }
                foreach ($prel4sequence_data as $l4seqkey => $l4seqvalue) {
                    $l4preseqresult[] = array_column($prel4sequence_data, $l4seqkey);
                }
                foreach ($prel4return_yp as $l4returnkey => $l4returnvalue) {
                    $l4prereturnresult[] = array_column($prel4return_yp, $l4returnkey);
                }
				 if (!empty($l4premissingresult)) {
            $data['prel4missing_yp'] = array_filter($l4premissingresult);
        }
        if (!empty($l4preseqresult)) {
            $data['prel4sequence_data'] = array_filter($l4preseqresult);
        }
        if (!empty($l4prereturnresult)) {
            $data['prel4return_data'] = array_filter($l4prereturnresult);
        }
		
                
                /*prev l4 sequence data end*/	
		
		/* L5 form start */
        $l5Form = $allForms[AAI_L5_FORM_ID];
        if (!empty($l5Form)) {
            $data['l5_form_data'] = json_decode($l5Form['form_json_data'], TRUE);

            foreach ($data['l5_form_data'] as $key => $value) {
                if ($value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]] = $OpArray[1];
                                    $data['l5_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l5_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }
            if (isset($incidentData['l5_form_data']) && !empty($incidentData['l5_form_data'])) {
                $editl5Data = json_decode($incidentData['l5_form_data'], true);

                foreach ($editl5Data as $key => $value) {
                    //pr($key);
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editl5Data[$value['name']] = $value;
                    }
                }
                foreach ($data['l5_form_data'] as $key => $value) {
                    if (isset($value['name']) && $value['name'] == $editl5Data[$value['name']]['name']) {
                        $data['l5_form_data'][$key]['value'] = str_replace("\'", "'", $editl5Data[$value['name']]['value']);
                    }
                }

                if (!empty($prev_incidentData['l5_form_data'])) {
                    $prevl5Data = json_decode($prev_incidentData['l5_form_data'], true);
                    foreach ($prevl5Data as $key => $prevalue) {
                        if (isset($prevalue['type']) && $prevalue['type'] != 'button' && $prevalue['type'] != 'header') {
                            $preveditl5Data[$prevalue['name']] = $prevalue;
                        }
                    }
                }
				
                $data['preveditl5Data'] = $preveditl5Data;
                $data['l5_report_compiler'] = $editl5Data['l5_report_compiler'];
                $data['l5_body_map'] = $editl5Data;
            }
        }
        /* L5 end */
        /*code by Ritesh rana*/
        /* L6 start */
        $l6Form = $allForms[AAI_L6_FORM_ID];
        if (!empty($l6Form)) {
            $data['l6_form_data'] = json_decode($l6Form['form_json_data'], TRUE);
            foreach ($data['l6_form_data'] as $key => $value) {
                if ($value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]] = $OpArray[1];
                                    $data['l6_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l6_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }

            $match = array('aam.incident_id'=> $incidentId,'aam.process_id'=> 'l6');
                $fields_report = array("aam.l6_form_data");
                $incidentData_l6 = $this->common_model->get_records(ARCHIVE_AAI_MAIN.' as aam',$fields_report, '', '', $match,'','2','','archive_incident_id','desc');

            if (isset($incidentData_l6[0]['l6_form_data']) && !empty($incidentData_l6[0]['l6_form_data'])) {
                $editl6Data = json_decode($incidentData_l6[0]['l6_form_data'], true);
                foreach ($editl6Data as $key => $value) {
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editl6Data[$value['name']] = $value;
                    }
                }
                foreach ($data['l6_form_data'] as $key => $value) {
                    if (isset($value['name']) && $value['name'] == $editl6Data[$value['name']]['name']) {
                        $data['l6_form_data'][$key]['value'] = str_replace("\'","'",$editl6Data[$value['name']]['value']);
                    }
                }
                $data['l6_report_compiler'] = $editl6Data['report_compiler'];
                /* start code by ritesh Rana*/
            /*start Sequence of events */
                $l6sequence_data['l6sequence_number']      = $editl6Data['l6sequence_number'];
                $l6sequence_data['l6who_raised_complaint'] = $editl6Data['l6who_raised_complaint'];
                $l6sequence_data['l6what_happened']        = $editl6Data['l6what_happened'];
                $l6sequence_data['l6sequence_date']        = $editl6Data['l6sequence_date'];
                $l6sequence_data['l6time_sequence']        = $editl6Data['l6time_sequence'];
                $l6seqresult                               = array();
                $count_sq                                  = count($editl6Data['l6sequence_number']);
                foreach ($l6sequence_data as $kk => $vv) {
                    for ($sq = 0; $sq < $count_sq; $sq++) {
                    if($kk == 'l6time_sequence' || $kk =='l6sequence_date'){
                            $l6seqresult[$sq][$kk] = $l6sequence_data[$kk][$sq];
                            $date_date_event = dateformat($l6sequence_data['l6sequence_date'][$sq]);
                            $l6seqresult[$sq]['l6seq_time_event_str']   = strtotime($date_date_event .' '. $l6sequence_data['l6time_sequence'][$sq]);
                    }else{
                        $l6seqresult[$sq][$kk] = $l6sequence_data[$kk][$sq];
                    }
                    }
                }
                $data['l6sequence_data'] = $l6seqresult;
                $data['l6sequence_data'] = array_sort($l6seqresult,'l6seq_time_event_str',SORT_ASC,false);
            /*start Sequence of events end */

            /*L6 PREV DATA*/
            $prev_incidentData_l6 = $incidentData_l6[1];
            if(!empty($prev_incidentData_l6['l6_form_data'])){
                $prevl6Data = json_decode($prev_incidentData_l6['l6_form_data'], true);
                    foreach ($prevl6Data as $key => $prevalue) {
                        if (isset($prevalue['type']) && $prevalue['type'] != 'button' && $prevalue['type'] != 'header') {
                            $preveditl6Data[$prevalue['name']] = $prevalue;
                        }
                    }
                $data['report_compiler'] = $prevl6Data['report_compiler'];
                }
                $data['preveditl6Data'] = $preveditl6Data;

            /*start prev Sequence of events */
                $l6sequence_data_prev['l6sequence_number']      = $prevl6Data['l6sequence_number'];
                $l6sequence_data_prev['l6who_raised_complaint'] = $prevl6Data['l6who_raised_complaint'];
                $l6sequence_data_prev['l6what_happened']        = $prevl6Data['l6what_happened'];
                $l6sequence_data_prev['l6sequence_date']        = $prevl6Data['l6sequence_date'];
                $l6sequence_data_prev['l6time_sequence']        = $prevl6Data['l6time_sequence'];
                $l6seqresult_prev                               = array();
                $count_sq                                       = count($prevl6Data['l6sequence_number']);
                foreach ($l6sequence_data_prev as $kk => $vv) {
                    for ($sq = 0; $sq < $count_sq; $sq++) {
                         if($kk == 'l6time_sequence' || $kk =='l6sequence_date'){
                            $l6seqresult_prev[$sq][$kk] = $l6sequence_data_prev[$kk][$sq];
                            $date_date_event = dateformat($l6sequence_data_prev['l6sequence_date'][$sq]);
                            $l6seqresult_prev[$sq]['l6seq_time_event_str']   = strtotime($date_date_event .' '. $l6sequence_data_prev['l6time_sequence'][$sq]);
                    }else{
                            $l6seqresult_prev[$sq][$kk] = $l6sequence_data_prev[$kk][$sq];
                    }

                    }
                }
                $data['l6sequence_data_prev'] = $l6seqresult_prev;
                $data['l6sequence_data'] = array_sort($l6seqresult_prev,'l6seq_time_event_str',SORT_ASC,false);
            /*end*/
            }
        }
        /*code end by ritesh rana*/

         /* L7 start */
         /*code by Ritesh Rana*/
        $l7Form = $allForms[AAI_L7_FORM_ID];
        if(!empty($l7Form)) {
            $data['l7_form_data'] = json_decode($l7Form['form_json_data'], TRUE);
            foreach ($data['l7_form_data'] as $key => $value) {
                if ($value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]] = $OpArray[1];
                                    $data['l7_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l7_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }


          $match = array('aam.incident_id'=> $incidentId,'aam.process_id'=> 'l7');
          $fields_report = array("aam.l7_form_data");
          $incidentData_l7 = $this->common_model->get_records(ARCHIVE_AAI_MAIN.' as aam',$fields_report, '', '', $match,'','2','','archive_incident_id','desc');

            if (isset($incidentData_l7[0]['l7_form_data']) && !empty($incidentData_l7[0]['l7_form_data'])) {
                $editl7Data = json_decode($incidentData_l7[0]['l7_form_data'], true);
                foreach ($editl7Data as $key => $value) {
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editl7Data[$value['name']] = $value;
                    }
                }
                
                $l7sequence_data                           = array();
                $l7sequence_data['l7sequence_number']      = $editl7Data['l7sequence_number'];
                $l7sequence_data['l7update_by']            = $editl7Data['l7update_by'];
                $l7sequence_data['l7daily_action_taken']   = $editl7Data['l7daily_action_taken'];
                $l7sequence_data['l7daily_action_outcome'] = $editl7Data['l7daily_action_outcome'];
                $l7sequence_data['l7date_safeguarding']    = $editl7Data['l7date_safeguarding'];
                $l7sequence_data['l7time_safeguard']       = $editl7Data['l7time_safeguard'];

                $l7seqresult = array();
                $count       = count($editl7Data['l7sequence_number']);
                foreach ($l7sequence_data as $kk => $vv) {
                    for ($i = 0; $i < $count; $i++) {

                     
                         if($kk == 'l7time_safeguard' || $kk =='l7date_safeguarding'){
                            $l7seqresult[$i][$kk] = $l7sequence_data[$kk][$i];
                            $date_date_event = dateformat($l7sequence_data['l7date_safeguarding'][$i]);

                            $l7seqresult[$i]['l7seq_time_event_str']   = strtotime($date_date_event .' '. $l7sequence_data['l7time_safeguard'][$i]);
                    }else{
                               $l7seqresult[$i][$kk] = $l7sequence_data[$kk][$i];
                    }

                        $doc                                              = $i + 1;
                        $l7seqresult[$i]['l7supporting_documents' . $doc] = $editl7Data['l7supporting_documents' . $doc];
                    }
                }

                $data['l7sequence_data'] = $l7seqresult;
                $data['l7sequence_data'] = array_sort($l7seqresult,'l7seq_time_event_str',SORT_ASC,false);


                foreach ($data['l7_form_data'] as $key => $value) {
                    if (isset($value['name']) && $value['name'] == $editl7Data[$value['name']]['name']) {
                        $data['l7_form_data'][$key]['value'] = str_replace("\'","'",$editl7Data[$value['name']]['value']);
                    }
                }
                /*prev l7*/
                $prev_incidentData_l7 = $incidentData_l7[1];
            if(!empty($prev_incidentData_l7['l7_form_data'])){
                $prevl7Data = json_decode($prev_incidentData_l7['l7_form_data'], true);
                    foreach ($prevl7Data as $key => $prevalue) {
                        if (isset($prevalue['type']) && $prevalue['type'] != 'button' && $prevalue['type'] != 'header') {
                            $preveditl7Data[$prevalue['name']] = $prevalue;
                        }
                    }
                }
                $data['preveditl7Data'] = $preveditl7Data;

                $l7sequence_prev_data                           = array();
                $l7sequence_prev_data['l7sequence_number']      = $prevl7Data['l7sequence_number'];
                $l7sequence_prev_data['l7update_by']            = $prevl7Data['l7update_by'];
                $l7sequence_prev_data['l7daily_action_taken']   = $prevl7Data['l7daily_action_taken'];
                $l7sequence_prev_data['l7daily_action_outcome'] = $prevl7Data['l7daily_action_outcome'];
                $l7sequence_prev_data['l7date_safeguarding']    = $prevl7Data['l7date_safeguarding'];
                $l7sequence_prev_data['l7time_safeguard']       = $prevl7Data['l7time_safeguard'];

                $l7seqresult_prev = array();
                $count            = count($prevl7Data['l7sequence_number']);
                foreach ($l7sequence_prev_data as $kk => $vv) {
                    for ($i = 0; $i < $count; $i++) {

                    if($kk == 'l7time_safeguard' || $kk =='l7date_safeguarding'){
                            $l7seqresult_prev[$i][$kk] = $l7sequence_prev_data[$kk][$i];
                            $date_date_event = dateformat($l7sequence_prev_data['l7date_safeguarding'][$i]);

                            $l7seqresult_prev[$i]['l7seq_time_event_str']   = strtotime($date_date_event .' '. $l7sequence_prev_data['l7time_safeguard'][$i]);
                    }else{
                            $l7seqresult_prev[$i][$kk] = $l7sequence_prev_data[$kk][$i];
                    }
                     $doc = $i + 1;
                        $l7seqresult_prev[$i]['l7supporting_documents' . $doc] = $prevl7Data['l7supporting_documents' . $doc];
                    }
                }
                $data['l7seqresult_prev']   = $l7seqresult_prev;
                $data['l7seqresult_prev'] = array_sort($l7seqresult_prev,'l7seq_time_event_str',SORT_ASC,false);
                $data['l7_report_compiler'] = $editl7Data['report_compiler'];
            }
        }
        /*end*/
        /* L7 end */
		/* nikunj ghelani L8 form start */
        $l8Form = $allForms[AAI_L8_FORM_ID];
        if (!empty($l8Form)) {
            $data['l8_form_data'] = json_decode($l8Form['form_json_data'], TRUE);

            foreach ($data['l8_form_data'] as $key => $value) {
                if ($value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]] = $OpArray[1];
                                    $data['l8_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l8_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }
			
				
            if (isset($incidentData['l8_form_data']) && !empty($incidentData['l8_form_data'])) {
                $editl8Data = json_decode($incidentData['l8_form_data'], true);
                $editl1Data = json_decode($incidentData['l1_form_data'], true);
				

                foreach ($editl8Data as $key => $value) {
                    //pr($key);
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editl8Data[$value['name']] = $value;
                    }
                }
                foreach ($data['l8_form_data'] as $key => $value) {
                    if (isset($value['name']) && $value['name'] == $editl8Data[$value['name']]['name']) {
                        $data['l8_form_data'][$key]['value'] = str_replace("\'", "'", $editl8Data[$value['name']]['value']);
                    }
                }
                $data['l8_report_compiler'] = $editl8Data['l8_report_compiler'];
                $data['l8_date_of_incident'] = $editl8Data['l8_date_of_incident'];
                $data['l8_time_of_incident'] = $editl8Data['l8_time_of_incident'];
                
            }
        }

        /* L8 form end */
		
		/* nikunj ghelani L9 form start */
        $l9Form = $allForms[AAI_L9_FORM_ID];
        if (!empty($l9Form)) {
            $data['l9_form_data'] = json_decode($l9Form['form_json_data'], TRUE);

            foreach ($data['l9_form_data'] as $key => $value) {
                if ($value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]] = $OpArray[1];
                                    $data['l9_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l9_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }
			
				
            if (isset($incidentData['l9_form_data']) && !empty($incidentData['l9_form_data'])) {
                $editl9Data = json_decode($incidentData['l9_form_data'], true);
                $editl1Data = json_decode($incidentData['l1_form_data'], true);
				

                foreach ($editl9Data as $key => $value) {
                    //pr($key);
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editl9Data[$value['name']] = $value;
                    }
                }
                foreach ($data['l9_form_data'] as $key => $value) {
                    if (isset($value['name']) && $value['name'] == $editl9Data[$value['name']]['name']) {
                        $data['l9_form_data'][$key]['value'] = str_replace("\'", "'", $editl9Data[$value['name']]['value']);
                    }
                }
                $data['l9_report_compiler'] = $editl9Data['l9_report_compiler'];
                $data['l9_date_of_incident'] = $editl9Data['l9_date_of_incident'];
                $data['l9_time_of_incident'] = $editl9Data['l9_time_of_incident'];
                
            }
        }

        /* L9 form end */


        $reviewForm = $allForms[REVIEW];
        if (!empty($reviewForm)) {
            $data['review_form_data'] = json_decode($reviewForm['form_json_data'], TRUE);
            foreach ($data['review_form_data'] as $key => $value) {
                if ($value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]] = $OpArray[1];
                                    $data['review_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['review_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }

            if (isset($incidentData['review_form_data']) && !empty($incidentData['review_form_data'])) {
                $editReviewData = json_decode($incidentData['review_form_data'], true);
                foreach ($editReviewData as $key => $value) {
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editReviewData[$value['name']] = $value;
                    }
                }

                foreach ($data['review_form_data'] as $key => $value) {
                    if (isset($value['name']) && $value['name'] == $editReviewData[$value['name']]['name']) {
                        $data['review_form_data'][$key]['value'] = str_replace("\'", "'", $editReviewData[$value['name']]['value']);
                    }
                }


            }
        }
        /* Review end */


        /* Manager review start */

        $ManagersReviewForm = $allForms[MANAGER_REVIEW];
        if (!empty($ManagersReviewForm)) {
            $data['manager_review_form_data'] = json_decode($ManagersReviewForm['form_json_data'], TRUE);
            foreach ($data['manager_review_form_data'] as $key => $value) {
                if ($value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]] = $OpArray[1];
                                    $data['manager_review_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['manager_review_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }

            if (isset($incidentData['manager_review_form_data']) && !empty($incidentData['manager_review_form_data'])) {
                $editManagerReviewData = json_decode($incidentData['manager_review_form_data'], true);
                foreach ($editManagerReviewData as $key => $value) {
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editManagerReviewData[$value['name']] = $value;
                    }
                }

                foreach ($data['manager_review_form_data'] as $key => $value) {
                    if (isset($value['name']) && $value['name'] == $editManagerReviewData[$value['name']]['name']) {
                        $data['manager_review_form_data'][$key]['value'] = str_replace("\'", "'", $editManagerReviewData[$value['name']]['value']);
                    }
                }
            }
        }
        /* Manager Review end */

        //get signoff data
        

        $array_data = explode(',', $incidentData['signoff_data']);


        $table = AAI_SIGNOFF.' as aai';
        $where = array("l.is_delete"=> "0");
        $where_in = array("aai.aai_signoff_id" => $array_data);
        $fields = array("aai.aai_signoff_id,aai.created_by,aai.created_date,aai.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id=aai.created_by');
        $group_by = array('aai.created_by');
        $data['signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where,'',$where_in);
        
        
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, array("yp_id,care_home,yp_fname,yp_lname,date_of_birth"), '', '', array("yp_id" => $incidentData['yp_id']));
        $data['crnt_view'] = $this->module;
        $data['editMode'] = $formNumber;
        $data['ypId'] = $incidentData['yp_id'];
        $data['isCareIncident'] = $incidentData['is_care_incident'];
        $data['incidentId'] = $incidentId;
        $data['incidentData'] = $incidentData;
        $data['footerJs'][0] = base_url('uploads/custom/js/AAI/AAI.js');
        $data['main_content'] = '/archive_view_ai';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
   

    /*
      @Author : Ritesh Rana
      @Desc   : Read more
      @Input  : incident_id
      @Output :
      @Date   : 26/02/2019
     */

    function readmore($archive_incident_id, $field) {
        $incidentData = $this->common_model->get_records(ARCHIVE_AAI_MAIN, '', '', '', array('archive_incident_id' => $archive_incident_id));

        $data = $incidentData[0];


                                  if(!empty($field) && $field == 'describe_leading'){
                                    $date_incident_started = '';
                                    if(json_decode($data['l1_form_data'], TRUE)){
                                        $l1_form_data = json_decode($data['l1_form_data'], TRUE);
                                    foreach ($l1_form_data as $value1) {
                                        if($value1['type'] == 'textarea' && $value1['name'] == 'l1_describe_leading'){
                                                $data['field_val'] = $value1['value'];        
                                        }
                                    }
                                    }else{
                                        $l2_l3_form_data = json_decode($data['l2_l3_form_data'], TRUE); 
                                        foreach ($l2_l3_form_data as $value) {
                                            if($value['type'] == 'textarea' && $value['name'] == 'l2_describe_leading'){
                                                $data['field_val'] = $value['value'];        
                                            }
                                      }
                                    }
                                  }else if(!empty($field) && $field == 'what_happened'){
                                    if(json_decode($data['l4_form_data'], TRUE)){
                                    $l4_form_data = json_decode($data['l4_form_data'], TRUE);
                                        foreach ($l4_form_data as $value4) {
                                            if($value4['type'] == 'textarea' && $value4['name'] == 'what_happened'){
                                                    $data['field_val'] = $value4['value'];        
                                               }
                                        }
                                    }
                                  }else if(!empty($field) && $field == 'how_did_accident'){ 
                                    if(json_decode($data['l5_form_data'], TRUE)){
                                    $l5_form_data = json_decode($data['l5_form_data'], TRUE);
                                        foreach ($l5_form_data as $value5) {
                                            if($value5['type'] == 'textarea' && $value5['name'] == 'how_did_accident'){
                                                    $data['field_val'] = $value5['value'];        
                                               }
                                        }
                                    }
                                  }else if(!empty($field) && $field == 'l6_complaint_detail'){ 
                                    if(json_decode($data['l6_form_data'], TRUE)){
                                    $l6_form_data = json_decode($data['l6_form_data'], TRUE);
                                        foreach ($l6_form_data as $value6) {
                                            if($value6['type'] == 'textarea' && $value6['name'] == 'l6_complaint_detail'){
                                                    $data['field_val'] = $value6['value'];        
                                               }
                                        }
                                    }
                                  }else if(!empty($field) && $field == 'detail_concern'){ 
                                     if(json_decode($data['l7_form_data'], TRUE)){
                                    $l7_form_data = json_decode($data['l7_form_data'], TRUE);
                                        foreach ($l7_form_data as $value7) {
                                            if($value7['type'] == 'textarea' && $value7['name'] == 'detail_concern'){
                                                    $data['field_val'] = $value7['value'];        
                                               }
                                        }
                                    }
                                  }                      
                                    
        
        $this->load->view($this->viewname . '/readmore', $data);
    }

}
