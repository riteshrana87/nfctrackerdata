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
            $table = ARCHIVE_AAI_MAIN . ' as aai';
            $where = array("aai.yp_id" => $ypId,"aai.incident_id" => $incident_id);
            $fields = array("aai.archive_incident_id,aai.incident_id,aai.reference_number,aai.yp_id,aai.is_care_incident,aai.care_home_id,aai.entry_form_data,aai.is_pi,CONCAT(`firstname`,' ', `lastname`) as create_name,ch.care_home_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= aai.created_by', CARE_HOME . ' as ch' => 'ch.care_home_id = aai.care_home_id');
            if (!empty($searchtext)) {
                $searchtext = html_entity_decode(trim(addslashes($searchtext)));
                $match = array("ai.yp_id" => $ypId);
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

        function appendNFCType1($n) {
            $n ['user_type'] = 'N';
            $n ['job_title'] = '';
            $n ['work_location'] = '';
            return $n;
        }
        $nfcUsers = array_map("appendNFCType1", $nfcUsers);
        $bambooUsers = $this->common_model->get_records(BAMBOOHR_USERS, array('user_id', 'first_name', 'last_name', 'email', 'job_title', 'work_location'), '', '', '', '', '', '', '', '', '', $emailMatch);

        function appendBambooType1($n) {
            $n ['user_type'] = 'B';
            return $n;
        }
        $bambooUsers = array_map("appendBambooType1", $bambooUsers);
        $data['bambooNfcUsers'] = array_merge($bambooUsers, $nfcUsers);
        $data['loggedInUser'] = $this->session->userdata['LOGGED_IN'];
        $table = AAI_MAIN . ' as a';
        $join_tables = array(YP_DETAILS . ' as yp' => 'a.yp_id=yp.yp_id', CARE_HOME . ' as c' => 'c.care_home_id=yp.care_home');
        $dateQuery = 'date(a.created_date) >= CURDATE() - INTERVAL 14 DAY ';
        $data['YPIncidentData'] = $this->common_model->get_records($table, '', $join_tables, '', array('a.yp_id' => $incidentData['yp_id'],'a.incident_id !=' => $incidentId), '', '', '', '', '', '', $dateQuery);
        /* entry form end */
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
            if (isset($incidentData['l1_form_data']) && !empty($incidentData['l1_form_data'])) {
                $editl1Data = json_decode($incidentData['l1_form_data'], true);
                foreach ($editl1Data as $key => $value) {
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editl1Data[$value['name']] = $value;
                    }
                }
                foreach ($data['l1_form_data'] as $key => $value) {
                    if (isset($value['name']) && $value['name'] == $editl1Data[$value['name']]['name']) {
                        $data['l1_form_data'][$key]['value'] = str_replace("\'","'",$editl1Data[$value['name']]['value']);
                    }
                }
                $data['l1_report_compiler'] = $editl1Data['report_compiler'];
                $data['l1_total_duration'] = $editl1Data['l1_total_duration'];
            }
        }
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
                $fields_report = array("aam.*");
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
                }
                $data['l2_report_compiler'] = $editl2Data['report_compiler'];
                $data['l2_total_duration'] = $editl2Data['l2_total_duration'];

                /* start code by ritesh Rana*/
                /*start Sequence of events */
                $l2sequence_data = array();
                $l2sequence_data['l2sequence_number']=$editl2Data['l2sequence_number'];
                $l2sequence_data['l2position']=$editl2Data['l2position'];
                $l2sequence_data['l2type']=$editl2Data['l2type'];
                $l2sequence_data['l2comments']=$editl2Data['l2comments'];
                $l2sequence_data['l2time_sequence']=$editl2Data['l2time_sequence'];

                $l2seqresult = array();
                $count_se = count($editl2Data['l2sequence_number']);
                foreach($l2sequence_data as $kk => $vv){
                    for($se=0; $se<$count_se;$se++){
                        $l2seqresult[$se][$kk] = $l2sequence_data[$kk][$se];
                        $doc_se = $se+1;
                        $l2seqresult[$se]['l2Who_was_involved_in_incident'.$doc_se] = $editl2Data['l2Who_was_involved_in_incident'.$doc_se];
                    }
                }
                $data['l2sequence_events'] = $l2seqresult;

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
                $count = count($prevl2Data['l2sequence_number']);
                foreach($l2sequence_prev_data as $kk => $vv){
                    for($i=0; $i<$count;$i++){
                        $l2seqresult_prev[$i][$kk] = $l2sequence_prev_data[$kk][$i];
                        $doc = $i+1;
                        $l2seqresult_prev[$i]['l2Who_was_involved_in_incident'.$doc] = $prevl2Data['l2Who_was_involved_in_incident'.$doc];
                    }
                }
                $data['l2seqresult_prev'] = $l2seqresult_prev;
                
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
            $data['l4_form_data'] = json_decode($l4Form['form_json_data'], TRUE);
            foreach ($data['l4_form_data'] as $key => $value) {
                if ($value['type'] == 'select') {
                    foreach ($data['dropdown_data'] as $key1 => $value1) {
                        if ($value['description'] == $value1['prefix']) {
                            if ($value1['total_options'] > 0) {
                                $optionsArray = explode(';', $value1['dropdown_options']);
                                foreach ($optionsArray as $op => $v) {
                                    $OpArray = explode('|', $v);
                                    $finalOptionsArray[$OpArray[0]] = $OpArray[1];
                                    $data['l4_form_data'][$key]['values'][$op]['label'] = $OpArray[1];
                                    $data['l4_form_data'][$key]['values'][$op]['value'] = $OpArray[0];
                                }
                            }
                        }
                    }
                }
            }
            if (isset($incidentData['l4_form_data']) && !empty($incidentData['l4_form_data'])) {
                $editl4Data = json_decode($incidentData['l4_form_data'], true);
                foreach ($editl4Data as $key => $value) {
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editl4Data[$value['name']] = $value;
                    }
                }
                foreach ($data['l4_form_data'] as $key => $value) {
                    if (isset($value['name']) && $value['name'] == $editl4Data[$value['name']]['name']) {
                        $data['l4_form_data'][$key]['value'] = str_replace("\'","'",$editl4Data[$value['name']]['value']);
                    }
                }
            }
        }

        /* L4 form end */

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
                        $data['l5_form_data'][$key]['value'] = str_replace("\'","'",$editl5Data[$value['name']]['value']);
                    }
                }
				$data['l5_report_compiler'] = $editl5Data['l5_report_compiler'];
				$data['l5_body_map'] = $editl5Data;
				




            }
        }
		
		    /*  pr($editl5Data);
				die;     */
 
        /* L5 form end */
        

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
                $fields_report = array("aam.*");
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
                $l6sequence_data['l6sequence_number']=$editl6Data['l6sequence_number'];
                $l6sequence_data['l6who_raised_complaint']=$editl6Data['l6who_raised_complaint'];
                $l6sequence_data['l6what_happened']=$editl6Data['l6what_happened'];
                $l6sequence_data['l6sequence_date']=$editl6Data['l6sequence_date'];
                $l6sequence_data['l6time_sequence']=$editl6Data['l6time_sequence'];
                $l6seqresult = array();
                $count_sq = count($editl6Data['l6sequence_number']);
                foreach($l6sequence_data as $kk => $vv){
                    for($sq=0; $sq<$count_sq;$sq++){
                        $l6seqresult[$sq][$kk] = $l6sequence_data[$kk][$sq];
                    }
                }
            $data['l6sequence_data']=$l6seqresult;
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
                $l6sequence_data_prev['l6sequence_number']=$prevl6Data['l6sequence_number'];
                $l6sequence_data_prev['l6who_raised_complaint']=$prevl6Data['l6who_raised_complaint'];
                $l6sequence_data_prev['l6what_happened']=$prevl6Data['l6what_happened'];
                $l6sequence_data_prev['l6sequence_date']=$prevl6Data['l6sequence_date'];
                $l6sequence_data_prev['l6time_sequence']=$prevl6Data['l6time_sequence'];
                $l6seqresult_prev = array();
                $count_sq = count($prevl6Data['l6sequence_number']);
                foreach($l6sequence_data_prev as $kk => $vv){
                    for($sq=0; $sq<$count_sq;$sq++){
                        $l6seqresult_prev[$sq][$kk] = $l6sequence_data_prev[$kk][$sq];
                    }
                }
            $data['l6sequence_data_prev']=$l6seqresult_prev;
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
          $fields_report = array("aam.*");
          $incidentData_l7 = $this->common_model->get_records(ARCHIVE_AAI_MAIN.' as aam',$fields_report, '', '', $match,'','2','','archive_incident_id','desc');

            if (isset($incidentData_l7[0]['l7_form_data']) && !empty($incidentData_l7[0]['l7_form_data'])) {
                $editl7Data = json_decode($incidentData_l7[0]['l7_form_data'], true);
                foreach ($editl7Data as $key => $value) {
                    if (isset($value['type']) && $value['type'] != 'button' && $value['type'] != 'header') {
                        $editl7Data[$value['name']] = $value;
                    }
                }
                $l7sequence_data = array();
                $l7sequence_data['l7sequence_number']=$editl7Data['l7sequence_number'];
                $l7sequence_data['l7update_by']=$editl7Data['l7update_by'];
                $l7sequence_data['l7daily_action_taken']=$editl7Data['l7daily_action_taken'];
                $l7sequence_data['l7daily_action_outcome']=$editl7Data['l7daily_action_outcome'];
                $l7sequence_data['l7date_safeguarding']=$editl7Data['l7date_safeguarding'];
                $l7sequence_data['l7time_safeguard']=$editl7Data['l7time_safeguard'];
                 
                $l7seqresult = array();
                $count = count($editl7Data['l7sequence_number']);
                foreach($l7sequence_data as $kk => $vv){
                    for($i=0; $i<$count;$i++){
                        $l7seqresult[$i][$kk] = $l7sequence_data[$kk][$i];
                        $doc = $i+1;
                        $l7seqresult[$i]['l7supporting_documents'.$doc] = $editl7Data['l7supporting_documents'.$doc];
                    }
                }

                $data['l7sequence_data']=$l7seqresult;

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
                
                $l7sequence_prev_data = array();
                $l7sequence_prev_data['l7sequence_number']=$prevl7Data['l7sequence_number'];
                $l7sequence_prev_data['l7update_by']=$prevl7Data['l7update_by'];
                $l7sequence_prev_data['l7daily_action_taken']=$prevl7Data['l7daily_action_taken'];
                $l7sequence_prev_data['l7daily_action_outcome']=$prevl7Data['l7daily_action_outcome'];
                $l7sequence_prev_data['l7date_safeguarding']=$prevl7Data['l7date_safeguarding'];
                $l7sequence_prev_data['l7time_safeguard']=$prevl7Data['l7time_safeguard'];

                $l7seqresult_prev = array();
                $count = count($prevl7Data['l7sequence_number']);
                foreach($l7sequence_prev_data as $kk => $vv){
                    for($i=0; $i<$count;$i++){
                        $l7seqresult_prev[$i][$kk] = $l7sequence_prev_data[$kk][$i];
                        $doc = $i+1;
                        $l7seqresult_prev[$i]['l7supporting_documents'.$doc] = $prevl7Data['l7supporting_documents'.$doc];
                    }
                }
                $data['l7seqresult_prev'] = $l7seqresult_prev;
                $data['l7_report_compiler'] = $editl7Data['report_compiler'];
            }
        }
        /*end*/
        /* L7 end */
		
		if(!empty($l4missingresult)){
		$data['l4missing_yp']=array_filter($l4missingresult);
		}
		if(!empty($l4seqresult)){
		$data['l4sequence_data']=array_filter($l4seqresult);
		}
		if(!empty($l4returnresult)){
		$data['l4return_data']=array_filter($l4returnresult);
		}
        
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
   

}
