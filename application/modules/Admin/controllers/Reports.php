<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

    function __construct() {
        parent::__construct();
        check_admin_login();
        $this->type = "Admin";
        $this->viewname = $this->uri->segment(2);
        $this->load->library('Ajax_pagination');
        $this->perPage = 10;
        $this->load->library('excel');
    }

    public function index() {
        redirect('Admin/Reports/PP');
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Get  Young Person Name List
      @Input  :
      @Output :
      @Date   : 22th Aug 2017
     */

    private function getYpList() {
        $tableName = YP_DETAILS . ' as yp';
        $fields = array('yp.yp_id, CONCAT(yp.yp_fname," ",yp.yp_lname) as ypName');

        $whereCond = array('yp.status' => 'active', 'yp.is_deleted' => '0');

        $data['information'] = $this->common_model->get_records($tableName, $fields, '', '', $whereCond, '', '', '', '', '', '', '');

        return $data['information'];
    }

    /**
     * @Author : Ritesh Rana    
     * @Desc: get Header and field Listing
     * @return string
     * @Date   : 22th Aug 2017
     */
    private function getHeaderFieldName($tableName = '', $match_Id = '', $type = '', $is_excel = false) {
        $returnArray = array();
        if (!empty($tableName) && !empty($match_Id) && !empty($type)) {

            $match = array($match_Id => 1);
            $pp_forms = $this->common_model->get_records($tableName, '', '', '', $match);

            if (!empty($pp_forms)) {

                $data['form_data'] = json_decode($pp_forms[0]['form_json_data'], TRUE);

                $passRow = '';
                if (!empty($is_excel)) {
                    if (!empty($data['form_data'])) {

                        foreach ($data['form_data'] as $ppHeader) {
                            if ($ppHeader['type'] == 'header') {
                                continue;
                            }

                            $passHeaderArray[] = $ppHeader['label'];
                            $passRow .= (isset($ppHeader['name'])) ? $type . "." . "`" . $ppHeader['name'] . "`, " : '';
                        }
                    }
                } else {
                    if (!empty($data['form_data'])) {

                        foreach ($data['form_data'] as $ppHeader) {
                            if (!empty($ppHeader['displayonadminlist'])) {
                                if ($ppHeader['type'] == 'header') {
                                    continue;
                                }

                                $passHeaderArray[] = $ppHeader['label'];
                                $passRow .= (isset($ppHeader['name'])) ? $type . "." . "`" . $ppHeader['name'] . "`, " : '';
                            }
                        }
                    }
                }

                $passHeaderArray[] = 'YP Name';
                //$passHeaderArray[] = 'last Name';
                $passHeaderArray[] = 'Date of Birth';
                $passHeaderArray[] = 'Create Date';
            }

            $returnArray = array(
                'headerName' => $passHeaderArray,
                'passField' => $passRow
            );
        }
        return $returnArray;
    }

    /**
     * @Author : Ritesh Rana    
     * @Desc: get Header and field Listing
     * @return string
     * @Date   : 22th Aug 2017
     */
    private function getHeaderFieldNameDo($tableName = '', $match_Id = '', $type = '', $is_excel = false) {

        $returnArray = array();
        if (!empty($tableName) && !empty($match_Id) && !empty($type)) {

            $match = array($match_Id => 1);
            $pp_forms = $this->common_model->get_records($tableName, '', '', '', $match);

            if (!empty($pp_forms)) {

                $data['form_data'] = json_decode($pp_forms[0]['form_json_data'], TRUE);

                $passRow = '';
                if (!empty($is_excel)) {
                    if (!empty($data['form_data'])) {

                        foreach ($data['form_data'] as $ppHeader) {
                            if ($ppHeader['type'] == 'header') {
                                continue;
                            }

                            $passHeaderArray[] = $ppHeader['label'];
                            $passRow .= (isset($ppHeader['name'])) ? $type . "." . "`" . $ppHeader['name'] . "`, " : '';
                        }
                    }
                } else {
                    if (!empty($data['form_data'])) {

                        foreach ($data['form_data'] as $ppHeader) {
                            if (!empty($ppHeader['displayonadminlist'])) {
                                if ($ppHeader['type'] == 'header') {
                                    continue;
                                }

                                $passHeaderArray[] = $ppHeader['label'];
                                $passRow .= (isset($ppHeader['name'])) ? $type . "." . "`" . $ppHeader['name'] . "`, " : '';
                            }
                        }
                    }
                }
                //do form 
                $match = array('food_form_id' => 1);
                $do_forms = $this->common_model->get_records(FOOD_FORM, '', '', '', $match);

                if (!empty($do_forms)) {
                    $data['do_form_data'] = json_decode($do_forms[0]['form_json_data'], TRUE);


                    if (!empty($is_excel)) {
                        if (!empty($data['do_form_data'])) {

                            foreach ($data['do_form_data'] as $ppHeader) {
                                if ($ppHeader['type'] == 'header') {
                                    continue;
                                }

                                $passHeaderArray[] = $ppHeader['label'];
                                $passRow .= (isset($ppHeader['name'])) ? 'df' . "." . "`" . $ppHeader['name'] . "`, " : '';
                            }
                        }
                    } else {
                        if (!empty($data['do_form_data'])) {

                            foreach ($data['do_form_data'] as $ppHeader) {
                                if (!empty($ppHeader['displayonadminlist'])) {
                                    if ($ppHeader['type'] == 'header') {
                                        continue;
                                    }

                                    $passHeaderArray[] = $ppHeader['label'];
                                    $passRow .= (isset($ppHeader['name'])) ? 'df' . "." . "`" . $ppHeader['name'] . "`, " : '';
                                }
                            }
                        }
                    }
                }
                $passHeaderArray[] = 'YP Name';
                //$passHeaderArray[] = 'last Name';
                $passHeaderArray[] = 'Date of Birth';
                $passHeaderArray[] = 'Create Date';
            }

            $returnArray = array(
                'headerName' => $passHeaderArray,
                'passField' => $passRow
            );
        }
        return $returnArray;
    }

    /**
     * @Author : Niral Patel    
     * @Desc: get Header and field Listing
     * @return string
     * @Date   : 28th Aug 2017
     */
    private function getHeaderMedsDefaultFieldName($tableName = '', $match_Id = '', $type = '', $is_excel = false) {

        $returnArray = array();


        $passHeaderArray[] = 'YP Name';
        //$passHeaderArray[] = 'last Name';
        $passHeaderArray[] = 'Create Date';
        $passHeaderArray[] = 'Medical Number';
        $passHeaderArray[] = 'Date Received';
        $returnArray = array(
            'headerName' => $passHeaderArray,
            'passField' => !empty($passRow) ? $passRow : ''
        );

        return $returnArray;
    }

    /**
     * @Author : Niral Patel    
     * @Desc: get Header and field Listing
     * @return string
     * @Date   : 28th Aug 2017
     */
    private function getHeaderFieldNameComs($tableName = '', $match_Id = '', $type = '', $is_excel = false) {

        $returnArray = array();
        if (!empty($tableName) && !empty($match_Id) && !empty($type)) {

            $match = array($match_Id => 1);
            $pp_forms = $this->common_model->get_records($tableName, '', '', '', $match);

            if (!empty($pp_forms)) {

                $data['form_data'] = json_decode($pp_forms[0]['form_json_data'], TRUE);

                $passRow = '';
                if (!empty($is_excel)) {
                    if (!empty($data['form_data'])) {

                        foreach ($data['form_data'] as $ppHeader) {
                            if ($ppHeader['type'] == 'header') {
                                continue;
                            }

                            $passHeaderArray[] = $ppHeader['label'];
                            $passRow .= (isset($ppHeader['name'])) ? $type . "." . "`" . $ppHeader['name'] . "`, " : '';
                        }
                    }
                } else {
                    if (!empty($data['form_data'])) {

                        foreach ($data['form_data'] as $ppHeader) {
                            if (!empty($ppHeader['displayonadminlist'])) {
                                if ($ppHeader['type'] == 'header') {
                                    continue;
                                }

                                $passHeaderArray[] = $ppHeader['label'];
                                $passRow .= (isset($ppHeader['name'])) ? $type . "." . "`" . $ppHeader['name'] . "`, " : '';
                            }
                        }
                    }
                }

                $passHeaderArray[] = 'YP Name';
                //$passHeaderArray[] = 'last Name';
                //$passHeaderArray[] = 'Date of Birth';
                $passHeaderArray[] = 'Date';
            }

            $returnArray = array(
                'headerName' => $passHeaderArray,
                'passField' => $passRow
            );
        }
        return $returnArray;
    }

    /*
      @Author : Niral Patel
      @Desc   : Show Report Listing based on the filter
      @Input  :
      @Output :
      @Date   : 28th Aug 2017
     */

    public function showReportList() {
        $postData = $this->input->post();
        $reportType = $this->input->post('reportType');
        $ypId = $this->input->post('yp_name');
        $startDate = (!empty($postData['admin_report_start_date'])) ? dateformat($postData['admin_report_start_date']) : '';
        $endDate = (!empty($postData['admin_report_end_date'])) ? dateformat($postData['admin_report_end_date']) : '';

        if (!empty($reportType)) {

            $config['per_page'] = NO_OF_RECORDS_PER_PAGE;
            $data['perpage'] = NO_OF_RECORDS_PER_PAGE;

            $config['first_link'] = 'First';
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);

            $config['base_url'] = base_url() . $this->type . '/' . $this->viewname . '/showReportList/';

            if ($reportType == 'PP') {

                $filterData = array(
                    'yp_id' => $ypId,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'reportType' => $reportType,
                    'uri_segment' => $uri_segment,
                    'perPage' => $config['per_page']
                );



                $data['information'] = $this->getPPData($filterData, $totalRows = false, $postData, $Fields = true, $is_excel = false); // get PP List Data

                $config['total_rows'] = $this->getPPData($filterData, $totalRows = true, $postData, $Fields = true, $is_excel = false); // get PP List Data

                $this->ajax_pagination->initialize($config);
                $data['pagination'] = $this->ajax_pagination->create_links();
                $data['uri_segment'] = $uri_segment;
                $data['sortfield'] = $postData['sortfield'];
                $data['sortby'] = $postData['sortby'];
                $data['view_url'] = 'Admin/PlacementPlanView/index/';
                $this->load->view($this->viewname . '/files/pp_ajax_list', $data);
            }

            if ($reportType == 'IBP') {

                $filterData = array(
                    'yp_id' => $ypId,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'reportType' => $reportType,
                    'uri_segment' => $uri_segment,
                    'perPage' => $config['per_page']
                );

                $data['information'] = $this->getIBPData($filterData, $totalRows = false, $postData, $Fields = true, $is_excel = false); // get IBP List Data
                $config['total_rows'] = $this->getIBPData($filterData, $totalRows = true, $postData, $Fields = true, $is_excel = false); // get IBP List Data

                $this->ajax_pagination->initialize($config);
                $data['pagination'] = $this->ajax_pagination->create_links();
                $data['uri_segment'] = $uri_segment;
                $data['sortfield'] = $postData['sortfield'];
                $data['sortby'] = $postData['sortby'];
                $data['view_url'] = 'Admin/IbpView/index/';
                $this->load->view($this->viewname . '/files/pp_ajax_list', $data);
            }

            if ($reportType == 'RA') {

                $filterData = array(
                    'yp_id' => $ypId,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'reportType' => $reportType,
                    'uri_segment' => $uri_segment,
                    'perPage' => $config['per_page']
                );

                $data['information'] = $this->getRAData($filterData, $totalRows = false, $postData, $Fields = true, $is_excel = false); // get RA List Data

                $config['total_rows'] = $this->getRAData($filterData, $totalRows = true, $postData, $Fields = true, $is_excel = false); // get RA List Data

                $this->ajax_pagination->initialize($config);
                $data['pagination'] = $this->ajax_pagination->create_links();
                $data['uri_segment'] = $uri_segment;
                $data['sortfield'] = $postData['sortfield'];
                $data['sortby'] = $postData['sortby'];
                $data['view_url'] = 'Admin/RiskAssesmentView/index/';
                $this->load->view($this->viewname . '/files/pp_ajax_list', $data);
            }

            if ($reportType == 'KS') {

                $filterData = array(
                    'yp_id' => $ypId,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'reportType' => $reportType,
                    'created_by' => $postData['created_by'],
                    'uri_segment' => $uri_segment,
                    'perPage' => $config['per_page']
                );


                $data['information'] = $this->getKSData($filterData, $totalRows = false, $postData, $Fields = true, $is_excel = false); // get KS List Data

                $config['total_rows'] = $this->getKSData($filterData, $totalRows = true, $postData, $Fields = true, $is_excel = false); // get KS List Data

                $this->ajax_pagination->initialize($config);
                $data['pagination'] = $this->ajax_pagination->create_links();
                $data['uri_segment'] = $uri_segment;
                $data['sortfield'] = $postData['sortfield'];
                $data['sortby'] = $postData['sortby'];
                $data['view_url'] = 'Admin/KeySessionView/view/';
                $this->load->view($this->viewname . '/files/pp_ajax_list', $data);
            }
            if ($reportType == 'DOCS') {

                $filterData = array(
                    'yp_id' => $ypId,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'reportType' => $reportType,
                    'uri_segment' => $uri_segment,
                    'perPage' => $config['per_page']
                );



                $data['information'] = $this->getDocsData($filterData, $totalRows = false, $postData, $Fields = true, $is_excel = false); // get Docs List Data

                $config['total_rows'] = $this->getDocsData($filterData, $totalRows = true, $postData, $Fields = true, $is_excel = false); // get Docs List Data

                $this->ajax_pagination->initialize($config);
                $data['pagination'] = $this->ajax_pagination->create_links();
                $data['uri_segment'] = $uri_segment;
                $data['sortfield'] = $postData['sortfield'];
                $data['sortby'] = $postData['sortby'];
                $data['view_url'] = 'Admin/DocumentsView/view/';
                $this->load->view($this->viewname . '/files/docs_ajax_list', $data);
            }
            if ($reportType == 'COMS') {

                $filterData = array(
                    'yp_id' => $ypId,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'reportType' => $reportType,
                    'created_by' => $postData['created_by'],
                    'communication_type' => $postData['communication_type'],
                    'uri_segment' => $uri_segment,
                    'perPage' => $config['per_page']
                );


                $data['information'] = $this->getCOMSData($filterData, $totalRows = false, $postData, $Fields = true, $is_excel = false); // get COMS List Data

                $config['total_rows'] = $this->getCOMSData($filterData, $totalRows = true, $postData, $Fields = true, $is_excel = false); // get COMS List Data

                $this->ajax_pagination->initialize($config);
                $data['pagination'] = $this->ajax_pagination->create_links();
                $data['uri_segment'] = $uri_segment;
                $data['sortfield'] = $postData['sortfield'];
                $data['sortby'] = $postData['sortby'];
                $data['view_url'] = 'Admin/CommunicationView/view/';
                $this->load->view($this->viewname . '/files/pp_ajax_list', $data);
            }
            if ($reportType == 'DO') {

                $filterData = array(
                    'yp_id' => $ypId,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'reportType' => $reportType,
                    'created_by' => $postData['created_by'],
                    'staff' => $postData['staff'],
                    'uri_segment' => $uri_segment,
                    'perPage' => $config['per_page']
                );


                $data['information'] = $this->getDOData($filterData, $totalRows = false, $postData, $Fields = true, $is_excel = false); // get do List Data

                $config['total_rows'] = $this->getDOData($filterData, $totalRows = true, $postData, $Fields = true, $is_excel = false); // get do List Data

                $this->ajax_pagination->initialize($config);
                $data['pagination'] = $this->ajax_pagination->create_links();
                $data['uri_segment'] = $uri_segment;
                $data['sortfield'] = $postData['sortfield'];
                $data['sortby'] = $postData['sortby'];
                $data['view_url'] = 'Admin/DailyObservationView/view/';
                $this->load->view($this->viewname . '/files/pp_ajax_list', $data);
            }
            if ($reportType == 'MEDS') {

                $filterData = array(
                    'yp_id' => $ypId,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'reportType' => $reportType,
                    'medical_number' => $postData['medical_number'],
                    'date_received' => (!empty($postData['date_received'])) ? dateformat($postData['date_received']) : '',
                    'uri_segment' => $uri_segment,
                    'perPage' => $config['per_page']
                );


                $data['information'] = $this->getMEDSData($filterData, $totalRows = false, $postData, $Fields = true, $is_excel = false); // get do List Data

                $config['total_rows'] = $this->getMEDSData($filterData, $totalRows = true, $postData, $Fields = true, $is_excel = false); // get do List Data

                $this->ajax_pagination->initialize($config);
                $data['pagination'] = $this->ajax_pagination->create_links();
                $data['uri_segment'] = $uri_segment;
                $data['sortfield'] = $postData['sortfield'];
                $data['sortby'] = $postData['sortby'];
                $data['view_url'] = 'Admin/MedicalView/index/';
                $this->load->view($this->viewname . '/files/pp_ajax_list', $data);
            }
        } else {
            echo "Please Select Reoprt Type";
        }
    }

    /*     * ********************************************** Start - PP ********************************************************* */
    /*
      @Author : Niral Patel
      @Desc   : Reports for PP
      @Input  :
      @Output :
      @Date   : 28th Aug 2017
     */

    public function PP() {

        $data['ypList'] = $this->getYpList(); // Yp List
        $data['form_action_path'] = $this->type . '/' . $this->viewname . '/PP';

        $data['reportType'] = 'PP';

        $data['footerJs'] = array(
            '0' => base_url() . 'uploads/assets/js/bootstrap-datetimepicker.min.js',
            '1' => base_url() . 'uploads/custom/js/reports/reports.js',
        );

        $data['button_header'] = array('menu_module' => 'pp');
        $data['main_content'] = $this->type . '/' . $this->viewname . '/ppReport';

        $this->parser->parse($this->type . '/assets/template', $data);
    }

    /**
     * @Author : Niral Patel
     * @param type $filterData
     * @param type $totalRows
     * @return type
     * @Date   : 28th Aug 2017
     */
    private function getPPData($filterData, $totalRows = false, $postData, $Fields = true, $is_excel = false) {

        $headerAndFieldData = $this->getHeaderFieldName(PP_FORM, 'pp_form_id', 'pp', $is_excel); // Header and PassField Data


        if (!empty($headerAndFieldData)) {
            //start - Query
            $tableName = PLACEMENT_PLAN . ' as pp';

            $fieldString = $headerAndFieldData['passField'];
            if ($Fields) {

                if ($is_excel) {
                    $field_id = '';
                } else {
                    $field_id = ',pp.yp_id as id';
                }
                $fields = array($fieldString . '       
                        concat(ypd.yp_fname," ",ypd.yp_lname) as yp_fname,if(ypd.date_of_birth != "" and ypd.date_of_birth !="0000-00-00",DATE_FORMAT(ypd.date_of_birth, "%d/%m/%Y"),"") as date_of_birth, if(pp.created_date != "" and pp.created_date !="0000-00-00",DATE_FORMAT(pp.created_date, "%d/%m/%Y"),"") as created_date' . $field_id);
                $flds = array($fieldString . '       
                        ypd.yp_fname, ypd.date_of_birth, ypd.created_date, created_by,id');
            }
            $join_tables = array(
                YP_DETAILS . ' as ypd' => 'pp.yp_id = ypd.yp_id'
            );

            $whereCond = 'ypd.status = "active" AND ypd.is_deleted = "0" AND is_previous_version = 0';

            if (!empty($filterData['yp_id'])) {
                $whereCond .= ' AND pp.yp_id = ' . $filterData['yp_id'];
            }

            if (!empty($filterData['startDate'])) {
                $whereCond .= ' AND ypd.created_date >= "' . $filterData['startDate'] . '"';
            }

            if (!empty($filterData['endDate'])) {
                $whereCond .= ' AND ypd.created_date <= "' . $filterData['endDate'] . '"';
            }

            if (!$totalRows) {

                $informationData = $this->common_model->get_records($tableName, $fields, $join_tables, 'left', $whereCond, '', $filterData['perPage'], $filterData['uri_segment'], !empty($postData['sortfield']) ? $postData['sortfield'] : 'placement_plan_id', !empty($postData['sortby']) ? $postData['sortby'] : 'desc', '', '');

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

    /*     * ********************************************** Start - DOCS ********************************************************* */
    /*
      @Author : Niral Patel
      @Desc   : Reports for Docs
      @Input  :
      @Output :
      @Date   : 23th July 2017
     */

    public function DOCS() {

        $data['ypList'] = $this->getYpList(); // Yp List
        $data['form_action_path'] = $this->type . '/' . $this->viewname . '/DOCS';

        $data['reportType'] = 'DOCS';

        $data['footerJs'] = array(
            '0' => base_url() . 'uploads/assets/js/bootstrap-datetimepicker.min.js',
            '1' => base_url() . 'uploads/custom/js/reports/reports.js',
        );

        $data['button_header'] = array('menu_module' => 'docs');
        $data['main_content'] = $this->type . '/' . $this->viewname . '/docsReport';

        $this->parser->parse($this->type . '/assets/template', $data);
    }

    /*     * ********************************************** End - PP ********************************************************* */

    /**
     * @Author : Niral Patel
     * @param type $filterData
     * @param type $totalRows
     * @return type
     * @Date   : 28th Aug 2017
     */
    private function getDocsData($filterData, $totalRows = false, $postData, $Fields = true, $is_excel = false) {

        $headerAndFieldData = $this->getHeaderFieldName(DOCS_FORM, 'docs_form_id', 'docs', $is_excel); // Header and PassField Data

        if (!empty($headerAndFieldData)) {
            //start - Query
            $tableName = YP_DOCUMENTS . ' as docs';

            $fieldString = $headerAndFieldData['passField'];
            if ($is_excel) {
                $field_id = '';
            } else {
                $field_id = ',docs.yp_id as id,docs.docs_id';
            }
            if ($Fields) {
                $fields = array($fieldString . '     
                        concat(ypd.yp_fname," ",ypd.yp_lname) as yp_fname,if(ypd.date_of_birth != "" and ypd.date_of_birth !="0000-00-00",DATE_FORMAT(ypd.date_of_birth, "%d/%m/%Y"),"") as date_of_birth, if(docs.created_date != "" and docs.created_date !="0000-00-00",DATE_FORMAT(docs.created_date, "%d/%m/%Y"),"") as created_date' . $field_id);
            } else {
                $fields = array($fieldString . '     
                        concat(ypd.yp_fname," ",ypd.yp_lname) as yp_fname,if(ypd.date_of_birth != "" and ypd.date_of_birth !="0000-00-00",DATE_FORMAT(ypd.date_of_birth, "%d/%m/%Y"),"") as date_of_birth, if(docs.created_date != "" and docs.created_date !="0000-00-00",DATE_FORMAT(docs.created_date, "%d/%m/%Y"),"") as created_date' . $field_id);
            }

            $flds = array($fieldString . '       
                        ypd.yp_fname, ypd.date_of_birth, docs.created_date,id,docs_id');
            $join_tables = array(
                YP_DETAILS . ' as ypd' => 'docs.yp_id = ypd.yp_id'
            );

            $whereCond = 'ypd.status = "active" AND ypd.is_deleted = "0" ';

            if (!empty($filterData['yp_id'])) {
                $whereCond .= ' AND docs.yp_id = ' . $filterData['yp_id'];
            }

            if (!empty($filterData['startDate'])) {
                $whereCond .= ' AND DATE_FORMAT(docs.created_date, "%Y-%m-%d") >= "' . $filterData['startDate'] . '"';
            }

            if (!empty($filterData['endDate'])) {
                $whereCond .= ' AND DATE_FORMAT(docs.created_date, "%Y-%m-%d") <= "' . $filterData['endDate'] . '"';
            }

            if (!$totalRows) {

                $informationData = $this->common_model->get_records($tableName, $fields, $join_tables, 'left', $whereCond, '', $filterData['perPage'], $filterData['uri_segment'], !empty($postData['sortfield']) ? $postData['sortfield'] : 'docs_id', !empty($postData['sortby']) ? $postData['sortby'] : 'desc', '', '');
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

    /*     * ********************************************** Start - IBP ********************************************************* */
    /*
      @Author : Niral Patel
      @Desc   : Reports for IBP
      @Input  :
      @Output :
      @Date   : 28th Aug 2017
     */

    public function IBP() {

        $data['ypList'] = $this->getYpList(); // Yp List
        $data['form_action_path'] = $this->type . '/' . $this->viewname . '/IBP';

        $data['reportType'] = 'IBP';

        $data['footerJs'] = array(
            '0' => base_url() . 'uploads/assets/js/bootstrap-datetimepicker.min.js',
            '1' => base_url() . 'uploads/custom/js/reports/reports.js',
        );

        $data['button_header'] = array('menu_module' => 'ibp');
        $data['main_content'] = $this->type . '/' . $this->viewname . '/ibpReport';

        $this->parser->parse($this->type . '/assets/template', $data);
    }

    /**
     * @Author : Niral Patel
     * @param type $filterData
     * @param type $totalRows
     * @return type
     * @Date   : 28th Aug 2017
     */
    private function getIBPData($filterData, $totalRows = false, $postData, $Fields = true, $is_excel = false) {

        $headerAndFieldData = $this->getHeaderFieldName(IBP_FORM, 'ibp_form_id', 'ibp', $is_excel); // Header and PassField Data
        if (!empty($headerAndFieldData)) {
            //start - Query
            $tableName = INDIVIDUAL_BEHAVIOUR_PLAN . ' as ibp';

            $fieldString = $headerAndFieldData['passField'];

            if ($Fields) {
                if ($is_excel) {
                    $field_id = '';
                } else {
                    $field_id = ',ibp.yp_id as id';
                }

                $fields = array($fieldString . '       
                        concat(ypd.yp_fname," ",ypd.yp_lname) as yp_fname,if(ypd.date_of_birth != "" and ypd.date_of_birth !="0000-00-00",DATE_FORMAT(ypd.date_of_birth, "%d/%m/%Y"),"") as date_of_birth, if(ibp.created_date != "" and ibp.created_date !="0000-00-00",DATE_FORMAT(ibp.created_date, "%d/%m/%Y"),"") as created_date' . $field_id);
                $flds = array($fieldString . '       
                        ypd.yp_fname, ypd.date_of_birth, ypd.created_date, created_by,id');
            }

            $join_tables = array(
                YP_DETAILS . ' as ypd' => 'ibp.yp_id = ypd.yp_id'
            );

            $whereCond = 'ypd.status = "active" AND ypd.is_deleted = "0" AND is_previous_version = 0';

            if (!empty($filterData['yp_id'])) {
                $whereCond .= ' AND ibp.yp_id = ' . $filterData['yp_id'];
            }

            if (!empty($filterData['startDate'])) {
                $whereCond .= ' AND ypd.created_date >= "' . $filterData['startDate'] . '"';
            }

            if (!empty($filterData['endDate'])) {
                $whereCond .= ' AND ypd.created_date <= "' . $filterData['endDate'] . '"';
            }

            if (!$totalRows) {

                $informationData = $this->common_model->get_records($tableName, $fields, $join_tables, 'left', $whereCond, '', $filterData['perPage'], $filterData['uri_segment'], !empty($postData['sortfield']) ? $postData['sortfield'] : 'ibp_id', !empty($postData['sortby']) ? $postData['sortby'] : 'desc', '', '');

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

    /*     * ********************************************** End - IBP ********************************************************* */

    /*     * ********************************************** Start - RA ********************************************************* */
    /*
      @Author : Niral Patel
      @Desc   : Reports for RA
      @Input  :
      @Output :
      @Date   : 28th Aug 2017
     */

    public function RA() {

        $data['ypList'] = $this->getYpList(); // Yp List
        $data['form_action_path'] = $this->type . '/' . $this->viewname . '/RA';

        $data['reportType'] = 'RA';

        $data['footerJs'] = array(
            '0' => base_url() . 'uploads/assets/js/bootstrap-datetimepicker.min.js',
            '1' => base_url() . 'uploads/custom/js/reports/reports.js',
        );

        $data['button_header'] = array('menu_module' => 'ra');
        $data['main_content'] = $this->type . '/' . $this->viewname . '/raReport';

        $this->parser->parse($this->type . '/assets/template', $data);
    }

    /**
     * @Author : Niral Patel
     * @param type $filterData
     * @param type $totalRows
     * @return type
     * @Date   : 28th Aug 2017
     */
    private function getRAData($filterData, $totalRows = false, $postData, $Fields = true, $is_excel = false) {

        $headerAndFieldData = $this->getHeaderFieldName(RA_FORM, 'ra_form_id', 'ra', $is_excel); // Header and PassField Data
        
        if (!empty($headerAndFieldData)) {
            //start - Query
            $tableName = RISK_ASSESSMENT . ' as ra';

            $fieldString = $headerAndFieldData['passField'];

            if ($Fields) {
                if ($is_excel) {
                    $field_id = '';
                } else {
                    $field_id = ',ra.yp_id as id';
                }

                $fields = array($fieldString . '       
                        concat(ypd.yp_fname," ",ypd.yp_lname) as yp_fname,if(ypd.date_of_birth != "" and ypd.date_of_birth !="0000-00-00",DATE_FORMAT(ypd.date_of_birth, "%d/%m/%Y"),"") as date_of_birth, if(ra.created_date != "" and ra.created_date !="0000-00-00",DATE_FORMAT(ra.created_date, "%d/%m/%Y"),"") as created_date' . $field_id);
                $flds = array($fieldString . '       
                        ypd.yp_fname, ypd.date_of_birth, ypd.created_date, created_by,id');
            }

            $join_tables = array(
                YP_DETAILS . ' as ypd' => 'ra.yp_id = ypd.yp_id'
            );

            $whereCond = 'ypd.status = "active" AND ypd.is_deleted = "0" AND is_previous_version = 0';

            if (!empty($filterData['yp_id'])) {
                $whereCond .= ' AND ra.yp_id = ' . $filterData['yp_id'];
            }

            if (!empty($filterData['startDate'])) {
                $whereCond .= ' AND ypd.created_date >= "' . $filterData['startDate'] . '"';
            }

            if (!empty($filterData['endDate'])) {
                $whereCond .= ' AND ypd.created_date <= "' . $filterData['endDate'] . '"';
            }


            if (!$totalRows) {

                $informationData = $this->common_model->get_records($tableName, $fields, $join_tables, 'left', $whereCond, '', $filterData['perPage'], $filterData['uri_segment'], !empty($postData['sortfield']) ? $postData['sortfield'] : 'risk_assesment_id', !empty($postData['sortby']) ? $postData['sortby'] : 'desc', '', '');

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

    /*     * ********************************************** End - RA ********************************************************* */

    /*     * ********************************************** Start - KS ********************************************************* */
    /*
      @Author : Niral Patel
      @Desc   : Reports for KS
      @Input  :
      @Output :
      @Date   : 24th August 2017
     */

    public function KS() {

        $data['ypList'] = $this->getYpList(); // Yp List
        $match = array('is_delete' => 0, 'status' => 'active',);
        $data['loginList'] = $this->common_model->get_records(LOGIN, array('login_id', 'concat(firstname," ",lastname) as created_by'), '', '', $match, '', '', '', 'login_id', 'desc'); // Yp List

        $data['form_action_path'] = $this->type . '/' . $this->viewname . '/KS';

        $data['reportType'] = 'KS';

        $data['footerJs'] = array(
            '0' => base_url() . 'uploads/assets/js/bootstrap-datetimepicker.min.js',
            '1' => base_url() . 'uploads/custom/js/reports/reports.js',
        );

        $data['button_header'] = array('menu_module' => 'ks');
        $data['main_content'] = $this->type . '/' . $this->viewname . '/ksReport';

        $this->parser->parse($this->type . '/assets/template', $data);
    }

    /**
     * @Author : Niral Patel
     * @param type $filterData
     * @param type $totalRows
     * @return type
     * @Date   : 24th August 2017
     */
    private function getKSData($filterData, $totalRows = false, $postData, $Fields = true, $is_excel = false) {

        $headerAndFieldData = $this->getHeaderFieldName(KS_FORM, 'ks_form_id', 'ks', $is_excel); // Header and PassField Data

        $headerAndFieldData['headerName'][count($headerAndFieldData['headerName'])] = 'Created By';
        if (!empty($headerAndFieldData)) {
            //start - Query
            $tableName = KEY_SESSION . ' as ks';

            $fieldString = $headerAndFieldData['passField'];
            if ($Fields) {
                if ($is_excel) {
                    $field_id = '';
                } else {
                    $field_id = ',ks.yp_id,ks.ks_id as id';
                }
                $fields = array($fieldString . '       
                        concat(ypd.yp_fname," ",ypd.yp_lname) as yp_fname,if(ypd.date_of_birth != "" and ypd.date_of_birth !="0000-00-00",DATE_FORMAT(ypd.date_of_birth, "%d/%m/%Y"),"") as date_of_birth, if(ypd.created_date != "" and ks.created_date !="0000-00-00",DATE_FORMAT(ks.created_date, "%d/%m/%Y"),"") as created_date, concat(l.firstname," ",l.lastname) as created_by' . $field_id);
                $flds = array($fieldString . '       
                        ypd.yp_fname, ypd.date_of_birth, ypd.created_date, created_by,id');
            }
            $join_tables = array(
                YP_DETAILS . ' as ypd' => 'ks.yp_id = ypd.yp_id',
                LOGIN . ' as l' => 'l.login_id = ypd.created_by',
            );

            $whereCond = 'ypd.status = "active" AND ypd.is_deleted = "0" ';

            if (!empty($filterData['yp_id'])) {
                $whereCond .= ' AND ks.yp_id = ' . $filterData['yp_id'];
            }

            if (!empty($filterData['startDate'])) {
                $whereCond .= ' AND DATE_FORMAT(ks.created_date, "%Y-%m-%d") >= "' . $filterData['startDate'] . '"';
            }

            if (!empty($filterData['endDate'])) {
                $whereCond .= ' AND DATE_FORMAT(ks.created_date, "%Y-%m-%d") <= "' . $filterData['endDate'] . '"';
            }

            if (!empty($filterData['created_by'])) {
                $whereCond .= ' AND l.login_id = "' . $filterData['created_by'] . '"';
            }

            if (!$totalRows) {

                $informationData = $this->common_model->get_records($tableName, $fields, $join_tables, 'left', $whereCond, '', $filterData['perPage'], $filterData['uri_segment'], !empty($postData['sortfield']) ? $postData['sortfield'] : 'ks_id', !empty($postData['sortby']) ? $postData['sortby'] : 'desc', '', '');
                
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

    /*
      @Author : Niral Patel
      @Desc   : Reports for COMS
      @Input  :
      @Output :
      @Date   : 25th August 2017
     */

    public function COMS() {

        $data['ypList'] = $this->getYpList(); // Yp List
        //get communication form
        $match = array('coms_form_id' => 1);
        $formsdata = $this->common_model->get_records(COMS_FORM, '', '', '', $match);
        if (!empty($formsdata)) {
            $form_data = json_decode($formsdata[0]['form_json_data'], TRUE);
            foreach ($form_data as $row) {
                if ($row['name'] == 'communication_type') {
                    foreach ($row['values'] as $rowvalue) {
                        $data['type_list'][] = $rowvalue['value'];
                    }
                }
            }
        }

        $match = array('is_delete' => 0, 'status' => 'active',);
        $data['loginList'] = $this->common_model->get_records(LOGIN, array('login_id', 'concat(firstname," ",lastname) as created_by'), '', '', $match, '', '', '', 'login_id', 'desc'); // Yp List

        $data['form_action_path'] = $this->type . '/' . $this->viewname . '/COMS';

        $data['reportType'] = 'COMS';

        $data['footerJs'] = array(
            '0' => base_url() . 'uploads/assets/js/bootstrap-datetimepicker.min.js',
            '1' => base_url() . 'uploads/custom/js/reports/reports.js',
        );

        $data['button_header'] = array('menu_module' => 'coms');
        $data['main_content'] = $this->type . '/' . $this->viewname . '/comsReport';

        $this->parser->parse($this->type . '/assets/template', $data);
    }

    /**
     * @Author : Niral Patel
     * @param type $filterData
     * @param type $totalRows
     * @return type
     * @Date   : 24th August 2017
     */
    private function getCOMSData($filterData, $totalRows = false, $postData, $Fields = true, $is_excel = false) {

        $headerAndFieldData = $this->getHeaderFieldNameComs(COMS_FORM, 'coms_form_id', 'coms', $is_excel); // Header and PassField Data

        $headerAndFieldData['headerName'][count($headerAndFieldData['headerName'])] = 'Staff';
        if (!empty($headerAndFieldData)) {
            //start - Query
            $tableName = COMMUNICATION_LOG . ' as coms';

            $fieldString = $headerAndFieldData['passField'];
            if ($Fields) {
                if ($is_excel) {
                    $field_id = '';
                } else {
                    $field_id = ',coms.communication_log_id as id';
                }
                $fields = array($fieldString . '       
                    concat(ypd.yp_fname," ",ypd.yp_lname) as yp_fname,if(coms.created_date != "" and coms.created_date !="0000-00-00",DATE_FORMAT(coms.created_date, "%d/%m/%Y"),"") as created_date, concat(l.firstname," ",l.lastname) as Staff' . $field_id);

                $flds = array($fieldString . '       
                    ypd.yp_fname,ypd.created_date, ypd.created_by,id');
            }
            $join_tables = array(
                YP_DETAILS . ' as ypd' => 'coms.yp_id = ypd.yp_id',
                LOGIN . ' as l' => 'l.login_id = ypd.created_by',
            );

            $whereCond = 'ypd.status = "active" AND ypd.is_deleted = "0" ';

            if (!empty($filterData['yp_id'])) {
                $whereCond .= ' AND coms.yp_id = ' . $filterData['yp_id'];
            }

            if (!empty($filterData['startDate'])) {
                $whereCond .= ' AND ypd.created_date >= "' . $filterData['startDate'] . '"';
            }

            if (!empty($filterData['endDate'])) {
                $whereCond .= ' AND ypd.created_date <= "' . $filterData['endDate'] . '"';
            }
            if (!empty($filterData['created_by'])) {
                $whereCond .= ' AND l.login_id = "' . $filterData['created_by'] . '"';
            }
            if (!empty($filterData['communication_type'])) {
                $whereCond .= ' AND coms.communication_type = "' . $filterData['communication_type'] . '"';
            }

            if (!$totalRows) {

                $informationData = $this->common_model->get_records($tableName, $fields, $join_tables, 'left', $whereCond, '', $filterData['perPage'], $filterData['uri_segment'], !empty($postData['sortfield']) ? $postData['sortfield'] : 'coms.communication_log_id', !empty($postData['sortby']) ? $postData['sortby'] : 'desc', '', '');

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

    /*
      @Author : Niral Patel
      @Desc   : Reports for DOS
      @Input  :
      @Output :
      @Date   : 28th August 2017
     */

    public function DOS() {

        $data['ypList'] = $this->getYpList(); // Yp List

        $match = array('is_delete' => 0, 'status' => 'active',);
        $data['loginList'] = $this->common_model->get_records(LOGIN, array('login_id', 'concat(firstname," ",lastname) as created_by'), '', '', $match, '', '', '', 'login_id', 'desc'); // Yp List

        $data['form_action_path'] = $this->type . '/' . $this->viewname . '/DOS';

        $data['reportType'] = 'DO';

        $data['footerJs'] = array(
            '0' => base_url() . 'uploads/assets/js/bootstrap-datetimepicker.min.js',
            '1' => base_url() . 'uploads/custom/js/reports/reports.js',
        );

        $data['button_header'] = array('menu_module' => 'do');
        $data['main_content'] = $this->type . '/' . $this->viewname . '/doReport';

        $this->parser->parse($this->type . '/assets/template', $data);
    }

    /**
     * @Author : Niral Patel
     * @param type $filterData
     * @param type $totalRows
     * @return type
     * @Date   : 24th August 2017
     */
    private function getDOData($filterData, $totalRows = false, $postData, $Fields = true, $is_excel = false) {

        $headerAndFieldData = $this->getHeaderFieldNameDo(DO_FORM, 'do_form_id', 'do', $is_excel);
        
        /* $headerAndFieldDataFood = $this->getHeaderFieldName(FOOD_FORM, 'food_form_id', 'df',$is_excel); // Header and PassField Data */

        $headerAndFieldData['headerName'][count($headerAndFieldData['headerName'])] = 'Created By';
        $headerAndFieldData['headerName'][count($headerAndFieldData['headerName'])] = 'Staff';

        if (!empty($headerAndFieldData)) {
            //start - Query
            $tableName = DAILY_OBSERVATIONS . ' as do';

            $fieldString = $headerAndFieldData['passField'];
            //$fieldStringFood = !empty($headerAndFieldDataFood)?$headerAndFieldDataFood['passField']:'';
            if (!empty($filterData['staff'])) {
                $whereCond1 = 'and ds.user_id = "' . $filterData['staff'] . '"';
            }
            if ($Fields) {
                if ($is_excel) {
                    $field_id = '';
                } else {
                    $field_id = ',do.do_id as id';
                }

                $fields = array($fieldString . '       
                       concat(ypd.yp_fname," ",ypd.yp_lname) as yp_fname,if(ypd.date_of_birth != "" and ypd.date_of_birth !="0000-00-00",DATE_FORMAT(ypd.date_of_birth, "%d/%m/%Y"),"") as date_of_birth, if(do.created_date != "" and do.created_date !="0000-00-00",DATE_FORMAT(do.created_date, "%d/%m/%Y"),"") as created_date, concat(l.firstname," ",l.lastname) as created_by, group_concat(distinct concat(l1.firstname," ",l1.lastname)) as staff' . $field_id);

                $flds = array($fieldString . '       
                        ypd.yp_fname, ypd.date_of_birth, do.created_date, created_by,staff,id');
            }
            $join_tables = array(
                YP_DETAILS . ' as ypd' => 'do.yp_id = ypd.yp_id',
                LOGIN . ' as l' => 'l.login_id = ypd.created_by',
                DO_STAFF_TRANSECTION . ' as ds' => 'ds.do_id = do.do_id',
                LOGIN . ' as l1' => 'l1.login_id = ds.user_id',
                DO_FOODCONSUMED . ' as df' => 'do.do_id = df.do_id',
            );

            $whereCond = 'ypd.status = "active" AND ypd.is_deleted = "0" AND is_previous_version = 0';

            if (!empty($filterData['yp_id'])) {
                $whereCond .= ' AND do.yp_id = ' . $filterData['yp_id'];
            }

            // if (!empty($filterData['startDate'])) {
            //     $whereCond .= ' AND do.created_date >= "' . $filterData['startDate'] . '"';
            // }
            // if (!empty($filterData['endDate'])) {
            //     $whereCond .= ' AND do.created_date <= "' . $filterData['endDate'] . '"';
            // }

            if (!empty($filterData['startDate'])) {
                $whereCond .= ' AND DATE_FORMAT(do.created_date, "%Y-%m-%d") >= "' . $filterData['startDate'] . '"';
            }

            if (!empty($filterData['endDate'])) {
                $whereCond .= ' AND DATE_FORMAT(do.created_date, "%Y-%m-%d") <= "' . $filterData['endDate'] . '"';
            }


            if (!empty($filterData['created_by'])) {
                $whereCond .= ' AND l.login_id = "' . $filterData['created_by'] . '"';
            }

            if (!empty($filterData['staff'])) {
                $whereCond .= ' AND ds.user_id = "' . $filterData['staff'] . '"';
            }

            if (!$totalRows) {

                $informationData = $this->common_model->get_records($tableName, $fields, $join_tables, 'left', $whereCond, '', $filterData['perPage'], $filterData['uri_segment'], !empty($postData['sortfield']) ? $postData['sortfield'] : 'do.do_id', !empty($postData['sortby']) ? $postData['sortby'] : 'desc', array('do.do_id'), '');

                return $returnData = array(
                    'headerData' => $headerAndFieldData['headerName'],
                    'fieldData' => explode(',', $flds[0]),
                    'informationData' => $informationData
                );
            }

            if ($totalRows) {
                return $this->common_model->get_records($tableName, $fields, $join_tables, 'left', $whereCond, '', '', '', '', '', array('do.do_id'), '', '', '', '1');
            }
        } else {
            return '';
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Reports for DOS
      @Input  :
      @Output :
      @Date   : 28th August 2017
     */

    public function MEDS() {

        $data['ypList'] = $this->getYpList(); // Yp List


        $data['medical_number'] = $this->common_model->get_records(MEDICAL_INFORMATION, array('medical_number'), '', '', '', '', '', '', 'mi_id', 'desc'); // Yp List

        $data['form_action_path'] = $this->type . '/' . $this->viewname . '/MEDS';

        $data['reportType'] = 'MEDS';

        $data['footerJs'] = array(
            '0' => base_url() . 'uploads/assets/js/bootstrap-datetimepicker.min.js',
            '1' => base_url() . 'uploads/custom/js/reports/reports.js',
        );

        $data['button_header'] = array('menu_module' => 'meds');
        $data['main_content'] = $this->type . '/' . $this->viewname . '/medsReport';

        $this->parser->parse($this->type . '/assets/template', $data);
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
                    $field_id = ',me.yp_id as id,me.allergies_and_meds_not_to_be_used';
                    $headerAndFieldData['headerName'][count($headerAndFieldData['headerName'])] = ' Allergies & Meds not to be Used';
                } else {
                    $field_id = ',me.yp_id as id';
                }
                $fields = array($fieldString . '       
                        concat(ypd.yp_fname," ",ypd.yp_lname) as yp_fname,if(me.created_date != "" and me.created_date !="0000-00-00",DATE_FORMAT(me.created_date, "%d/%m/%Y"),"") as created_date,me.medical_number,if(me.date_received != "" and me.date_received !="0000-00-00",DATE_FORMAT(me.date_received, "%d/%m/%Y"),"") as date_received' . $field_id);
                $flds = array($fieldString . '       
                        ypd.yp_fname, ypd.created_date,me.medical_number,me.date_received,id');
            }
            $join_tables = array(
                YP_DETAILS . ' as ypd' => 'me.yp_id = ypd.yp_id',
                LOGIN . ' as l' => 'l.login_id = ypd.created_by',
            );

            $whereCond = 'ypd.status = "active" AND ypd.is_deleted = "0" AND is_previous_version = 0';

            if (!empty($filterData['yp_id'])) {
                $whereCond .= ' AND me.yp_id = ' . $filterData['yp_id'];
            }

            if (!empty($filterData['startDate'])) {
                $whereCond .= ' AND ypd.created_date >= "' . $filterData['startDate'] . '"';
            }

            if (!empty($filterData['endDate'])) {
                $whereCond .= ' AND ypd.created_date <= "' . $filterData['endDate'] . '"';
            }
            if (!empty($filterData['medical_number'])) {
                $whereCond .= ' AND me.medical_number = "' . $filterData['medical_number'] . '"';
            }
            if (!empty($filterData['medical_number'])) {
                $whereCond .= ' AND me.medical_number = "' . $filterData['medical_number'] . '"';
            }
            if (!empty($filterData['date_received'])) {
                $whereCond .= ' AND me.date_received = "' . $filterData['date_received'] . '"';
            }

            if (!$totalRows) {

                $informationData = $this->common_model->get_records($tableName, $fields, $join_tables, 'left', $whereCond, '', $filterData['perPage'], $filterData['uri_segment'], !empty($postData['sortfield']) ? $postData['sortfield'] : 'mi_id', !empty($postData['sortby']) ? $postData['sortby'] : 'desc', '', '');

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

    /*     * ********************************************** End - MEDs ********************************************************* */

    /*
     * @Author : Niral Patel
     * @Desc   : generate query string for download file
     * @Input   :
     * @Output  :
     * @Date   : 30st Aug 2017
     */

    public function generateExcelFileUrl() {
        $postData = $this->input->post();

        $ypId = !empty($postData['yp_name']) ? $postData['yp_name'] : '';
        $reportType = !empty($postData['reportType']) ? $postData['reportType'] : '';
        $startDate = (!empty($postData['admin_report_start_date'])) ? dateformat($postData['admin_report_start_date']) : '';
        $endDate = (!empty($postData['admin_report_end_date'])) ? dateformat($postData['admin_report_end_date']) : '';
        $created_by = !empty($postData['created_by']) ? $postData['created_by'] : '';

        $filterData = array(
            'yp_id' => !empty($ypId) ? $ypId : '',
            'startDate' => !empty($startDate) ? $startDate : '',
            'endDate' => !empty($endDate) ? $endDate : '',
            'created_by' => !empty($created_by) ? $created_by : '',
            'communication_type' => !empty($postData['communication_type']) ? $postData['communication_type'] : '',
            'staff' => !empty($postData['staff']) ? $postData['staff'] : '',
            'medical_number' => !empty($postData['medical_number']) ? $postData['medical_number'] : '',
            'date_received' => (!empty($postData['date_received'])) ? dateformat($postData['date_received']) : '',
            'reportType' => $reportType,
        );
        $queryString = http_build_query($filterData);

        echo base_url('Admin/Reports/filterExcelData') . '?' . $queryString;
        exit;
    }

    /*
     * @Author : Niral Patel
     * @Desc   : Based on query string filter data
     * @Input   :
     * @Output  :
     * @Date   : 30st Aug 2017
     */

    public function filterExcelData() {
        $getData = $this->input->get();
        $reportType = $this->input->get('reportType');
        $ypId = $this->input->get('yp_id');
        $startDate = (!empty($getData['startDate'])) ? $getData['startDate'] : '';
        $endDate = (!empty($getData['endDate'])) ? $getData['endDate'] : '';
        $created_by = $this->input->get('created_by');
        if (!empty($reportType)) {

            $filterFinalData = array(
                'yp_id' => $ypId,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'reportType' => $reportType,
                'created_by' => $created_by,
                'communication_type' => (!empty($getData['communication_type'])) ? $getData['communication_type'] : '',
                'staff' => (!empty($getData['staff'])) ? $getData['staff'] : '',
                'medical_number' => (!empty($getData['medical_number'])) ? $getData['medical_number'] : '',
                'date_received' => (!empty($getData['date_received'])) ? $getData['date_received'] : '',
                'uri_segment' => '',
                'perPage' => ''
            );
            $this->generateExcelFile($filterFinalData); //Call Function for generate excel file
        } else {
            echo "Invalid URL";
            exit;
        }
    }

    /*
     * @Author : Niral Patel
     * @Desc   : Generate Report based on the data
     * @Input   :
     * @Output  :
     * @Date   : 30st Aug 2017
     */

    protected function generateExcelFile($filterFinalData) {
        $reportType = $filterFinalData['reportType'];

        $this->activeSheetIndex = $this->excel->setActiveSheetIndex(0);

        //name the worksheet
        $this->excel->getActiveSheet()->setTitle($reportType);
        $exceldataHeader = "";
        $exceldataValue = "";
        $headerCount = 1;
        if ($reportType == 'MEDS') {
            $recordData = $this->getMEDSData($filterFinalData, $totalRows = false, '', $Fields = true, $is_excel = true); // get coms List Data

            if (!empty($recordData['informationData'])) {
                foreach ($recordData['informationData'] as $index => $rowValue) {
                    $rowArray = $exceldataValue = $macHeader = $omiHeader = $miHeader = $mpHeader = $mcHeader = $mHeader = $amHeader = array();
                    $haHeader = $macvalue = $omivalue = $mivalue = $mpvalue = $mcvalue = $mvalue = $amvalue = $havalue = array();
                    foreach ($rowValue as $rowindex => $rowData) {
                        if ($rowindex != 'id') {
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
                            $rowArray[$rowindex] = html_entity_decode(strip_tags(!empty($st) ? $st : $rowData));
                        }
                    }
                    if ($headerCount === 1) {
                        $exceldataHeader[] = $recordData['headerData'];  // Set Header of the generated Excel File
                    }
                    $exceldataValue[] = $rowArray; // Set values
                    $headerCount++;
                    if ($rowValue) {
                        //start code for multiple sheet
                        $BlankArray = array('');
                        $ypid = $rowValue['id'];
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
                                $macHeader[] = html_entity_decode($row['label']);
                                $macvalue[] = isset($row['name']) ? !empty($mac_details[0][$row['name']]) ? nl2br(html_entity_decode(strip_tags($mac_details[0][$row['name']]))) : '' : '';
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
                                $omiHeader[] = html_entity_decode($row['label']);
                                $omivalue[] = isset($row['name']) ? !empty($omi_details[0][$row['name']]) ? nl2br(html_entity_decode(strip_tags($omi_details[0][$row['name']]))) : '' : '';
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
                                $miHeader[] = html_entity_decode($row['label']);
                                $mivalue[] = isset($row['name']) ? !empty($miform_details[0][$row['name']]) ? nl2br(html_entity_decode(strip_tags($miform_details[0][$row['name']]))) : '' : '';
                            }
                        }
                        //get mp form
                        $match = array('mp_form_id' => 1);
                        $formsdata = $this->common_model->get_records(MP_FORM, '', '', '', $match);
                        if (!empty($formsdata)) {
                            $mp_form_data = json_decode($formsdata[0]['form_json_data'], TRUE);
                        }

                        //get mp details        
                        $mp_details = $this->common_model->get_records(MEDICAL_PROFESSIONALS . ' as mp', 'mp.*', '', '', '', '', '', '', 'mp.mp_id', 'desc', '', array("mp.yp_id" => $ypid));
                        $mpHeader = $mpvalue = array();
                        if (!empty($mp_form_data)) {
                            foreach ($mp_form_data as $row) {
                                $mpHeader[] = html_entity_decode($row['label']);
                            }
                        }
                        if (!empty($mp_details)) {
                            foreach ($mp_details as $mpindex => $data) {
                                if (!empty($mp_form_data)) {
                                    foreach ($mp_form_data as $row) {
                                        $mpvalue[$mpindex][] = isset($row['name']) ? !empty($data[$row['name']]) ? nl2br(html_entity_decode(strip_tags($data[$row['name']]))) : '' : '';
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
                        $mc_details = $this->common_model->get_records(MEDICAL_COMMUNICATION . ' as mc', 'mc.*', '', '', '', '', '', '', 'mc.communication_id', 'desc', '', array("mc.yp_id" => $ypid));
                        $mcHeader = $mcvalue = array();
                        if (!empty($mc_form_data)) {
                            foreach ($mc_form_data as $row) {
                                $mcHeader[] = html_entity_decode($row['label']);
                            }
                        }
                        if (!empty($mc_details)) {
                            foreach ($mc_details as $mcindex => $data) {
                                if (!empty($mc_form_data)) {
                                    foreach ($mc_form_data as $row) {
                                        $mcvalue[$mcindex][] = isset($row['name']) ? !empty($data[$row['name']]) ? nl2br(html_entity_decode(strip_tags($data[$row['name']]))) : '' : '';
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
                        $m_details = $this->common_model->get_records(MEDICATION . ' as mc', 'mc.*', '', '', '', '', '', '', 'mc.medication_id', 'desc', '', array("mc.yp_id" => $ypid));
                        $mHeader = $mvalue = array();
                        if (!empty($m_form_data)) {
                            foreach ($m_form_data as $row) {
                                $mHeader[] = html_entity_decode($row['label']);
                            }
                        }
                        if (!empty($m_details)) {
                            foreach ($m_details as $mindex => $data) {
                                if (!empty($m_form_data)) {
                                    foreach ($m_form_data as $row) {
                                        $mvalue[$mindex][] = isset($row['name']) ? !empty($data[$row['name']]) ? nl2br(html_entity_decode(strip_tags($data[$row['name']]))) : '' : '';
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

                        $am_details = $this->common_model->get_records(ADMINISTER_MEDICATION . ' as mc', array('mc.*,concat(l.firstname," ",l.lastname) as staff1,concat(l.firstname," ",l.lastname) as staff2'), $join_tables, 'left', '', '', '', '', 'mc.administer_medication_id', 'desc', '', array("mc.yp_id" => $ypid));

                        $amHeader = $amvalue = array();
                        if (!empty($am_form_data)) {
                            foreach ($am_form_data as $row) {
                                $amHeader[] = html_entity_decode($row['label']);
                            }
                        }
                        if (!empty($am_details)) {
                            foreach ($am_details as $amindex => $data) {
                                if (!empty($am_form_data)) {
                                    foreach ($am_form_data as $row) {
                                        $amvalue[$amindex][] = isset($row['name']) ? !empty($data[$row['name']]) ? nl2br(html_entity_decode(strip_tags($data[$row['name']]))) : '' : '';
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
                        $ha_details = $this->common_model->get_records(HEALTH_ASSESSMENT . ' as mc', 'mc.*', '', '', '', '', '', '', 'mc.health_assessment_id', 'desc', '', array("mc.yp_id" => $ypid));
                        $haHeader = $havalue = array();
                        if (!empty($ha_form_data)) {
                            foreach ($ha_form_data as $row) {
                                $haHeader[] = html_entity_decode($row['label']);
                            }
                        }

                        if (!empty($ha_details)) {
                            foreach ($ha_details as $haindex => $data) {
                                if (!empty($ha_form_data)) {
                                    foreach ($ha_form_data as $row) {
                                        $havalue[$haindex][] = isset($row['name']) ? !empty($data[$row['name']]) ? nl2br(html_entity_decode(strip_tags($data[$row['name']]))) : '' : '';
                                    }
                                }
                            }
                        }
                        //end get data code

                        if ($index != 0) {
                            $this->excel->createSheet();
                            $sheet = $this->excel->setActiveSheetIndex($index);
                            $sheet->setTitle(substr($rowValue['yp_fname'], 0, 30));
                        } else {
                            $sheet = $this->excel->getActiveSheet();
                            $this->excel->setActiveSheetIndex(0)->setTitle(substr($rowValue['yp_fname'], 0, 30));
                        }

                        $sheet->getStyle('A1:Z1')->getFont()->setBold(true);
                        $sheet->fromArray($exceldataHeader, Null, 'A1')->getStyle('A1')->getFont()->setBold(true); // Set Header Data
                        $sheet->fromArray($exceldataValue, Null, 'A2'); // Set Fetch Data
                        $sheet->fromArray($BlankArray, Null, 'A3'); // Set blank Data
                        $sheet->fromArray(array('Medical Authorisations & Consents'), Null, 'A4')->getStyle('A4')->getFont()->setBold(true);
                        $sheet->mergeCells('A4:F4');
                        $sheet->getStyle('A4')->getAlignment()->applyFromArray(
                                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT));
                        if (!empty($mac_form_data)) {
                            $col = 'A';
                            foreach ($mac_form_data as $row) {

                                if ($row['type'] == 'header') {
                                    $sheet->setCellValue($col . '5', html_entity_decode($row['label']));
                                    $sheet->mergeCells($col . '5:' . $col . '6');
                                    $sheet->getStyle($col . '5')->getAlignment()->applyFromArray(
                                            array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER));
                                } else {
                                    $sheet->setCellValue($col . '5', html_entity_decode($row['label']));
                                }
                                // Set mac Header Data
                                $col++;
                            }
                        }
                        //$sheet->fromArray($macHeader, Null, 'A5'); // Set mac Header Data
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
                        if (!empty($mi_form_data)) {
                            $col = 'A';
                            foreach ($mi_form_data as $row) {

                                if ($row['type'] == 'header') {
                                    $sheet->setCellValue($col . '13', html_entity_decode($row['label']));
                                    $sheet->mergeCells($col . '13:' . $col . '14');
                                    $sheet->getStyle($col . '13')->getAlignment()->applyFromArray(
                                            array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER));
                                } else {
                                    $sheet->setCellValue($col . '13', html_entity_decode($row['label']));
                                }
                                // Set omi Header Data
                                $col++;
                            }
                        }
                        //$sheet->fromArray($miHeader, Null, 'A13'); // Set omi Header Data
                        $sheet->getStyle('A13:AZ13')->getFont()->setBold(true);
                        $sheet->fromArray($mivalue, Null, 'A14'); // Set omi Fetch Data

                        $sheet->fromArray($BlankArray, Null, 'A15'); // Set blank Data
                        $sheet->fromArray(array('Medical Professionals'), Null, 'A16')->getStyle('A16')->getFont()->setBold(true);
                        $sheet->mergeCells('A16:F16');
                        $sheet->getStyle('A16')->getAlignment()->applyFromArray(
                                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT));

                        $sheet->fromArray($mpHeader, Null, 'A17'); // Set omi Header Data
                        $sheet->getStyle('A17:Z17')->getFont()->setBold(true);
                        $cellNo = '18';
                        if (!empty($mpvalue)) {

                            foreach ($mpvalue as $mpRow) {
                                $sheet->fromArray($mpRow, Null, 'A' . $cellNo);
                                $cellNo++;
                            }
                        }
                        //mc data
                        $sheet->fromArray($BlankArray, Null, 'A' . $cellNo++); // Set blank Data
                        $sheet->fromArray(array('Medical Communication Log'), Null, 'A' . $cellNo)->getStyle('A' . $cellNo)->getFont()->setBold(true);
                        $sheet->mergeCells('A' . $cellNo . ':F' . $cellNo);
                        $sheet->getStyle('A' . $cellNo++)->getAlignment()->applyFromArray(
                                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT));

                        $sheet->fromArray($mcHeader, Null, 'A' . $cellNo); // Set omi Header Data
                        $sheet->getStyle('A' . $cellNo . ':Z' . $cellNo++)->getFont()->setBold(true);
                        if (!empty($mcvalue)) {

                            foreach ($mcvalue as $mpRow) {
                                $sheet->fromArray($mpRow, Null, 'A' . $cellNo);
                                $cellNo++;
                            }
                        }

                        //m data
                        $sheet->fromArray($BlankArray, Null, 'A' . $cellNo++); // Set blank Data
                        $sheet->fromArray(array('Medication'), Null, 'A' . $cellNo)->getStyle('A' . $cellNo)->getFont()->setBold(true);
                        $sheet->mergeCells('A' . $cellNo . ':F' . $cellNo);
                        $sheet->getStyle('A' . $cellNo++)->getAlignment()->applyFromArray(
                                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT));

                        $sheet->fromArray($mHeader, Null, 'A' . $cellNo); // Set omi Header Data
                        $sheet->getStyle('A' . $cellNo . ':Z' . $cellNo++)->getFont()->setBold(true);
                        if (!empty($mvalue)) {

                            foreach ($mvalue as $mpRow) {
                                $sheet->fromArray($mpRow, Null, 'A' . $cellNo);
                                $cellNo++;
                            }
                        }

                        //m data
                        $sheet->fromArray($BlankArray, Null, 'A' . $cellNo++); // Set blank Data
                        $sheet->fromArray(array('Administration History'), Null, 'A' . $cellNo)->getStyle('A' . $cellNo)->getFont()->setBold(true);
                        $sheet->mergeCells('A' . $cellNo . ':F' . $cellNo);
                        $sheet->getStyle('A' . $cellNo++)->getAlignment()->applyFromArray(
                                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT));

                        $sheet->fromArray($amHeader, Null, 'A' . $cellNo); // Set omi Header Data
                        $sheet->getStyle('A' . $cellNo . ':Z' . $cellNo++)->getFont()->setBold(true);
                        if (!empty($amvalue)) {

                            foreach ($amvalue as $mpRow) {
                                $sheet->fromArray($mpRow, Null, 'A' . $cellNo);
                                $cellNo++;
                            }
                        }

                        //ha data
                        $sheet->fromArray($BlankArray, Null, 'A' . $cellNo++); // Set blank Data
                        $sheet->fromArray(array('Health Assessment'), Null, 'A' . $cellNo)->getStyle('A' . $cellNo)->getFont()->setBold(true);
                        $sheet->mergeCells('A' . $cellNo . ':F' . $cellNo);
                        $sheet->getStyle('A' . $cellNo++)->getAlignment()->applyFromArray(
                                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT));

                        $sheet->fromArray($haHeader, Null, 'A' . $cellNo); // Set omi Header Data
                        $sheet->getStyle('A' . $cellNo . ':Z' . $cellNo++)->getFont()->setBold(true);
                        if (!empty($havalue)) {

                            foreach ($havalue as $mpRow) {
                                $sheet->fromArray($mpRow, Null, 'A' . $cellNo);
                                $cellNo++;
                            }
                        }
                    }
                }
            }
        } else {
            if ($reportType == 'PP') {
                $recordData = $this->getPPData($filterFinalData, $totalRows = false, '', $Fields = true, $is_excel = true); // get PP List Data
            }

            if ($reportType == 'IBP') {

                $recordData = $this->getIBPData($filterFinalData, $totalRows = false, '', $Fields = true, $is_excel = true); // get ibp List Data
            }

            if ($reportType == 'RA') {

                $recordData = $this->getRAData($filterFinalData, $totalRows = false, '', $Fields = true, $is_excel = true); // get ra List Data
            }

            if ($reportType == 'KS') {

                $recordData = $this->getKSData($filterFinalData, $totalRows = false, '', $Fields = true, $is_excel = true); // get ks List Data
            }
            if ($reportType == 'DOCS') {

                $recordData = $this->getDocsData($filterFinalData, $totalRows = false, '', $Fields = true, $is_excel = true); // get docs List Data
            }
            if ($reportType == 'COMS') {
                $recordData = $this->getCOMSData($filterFinalData, $totalRows = false, '', $Fields = true, $is_excel = true); // get coms List Data
            }
            if ($reportType == 'DO') {
                $recordData = $this->getDOData($filterFinalData, $totalRows = false, '', $Fields = true, $is_excel = true); // get coms List Data
            }



            foreach ($recordData['informationData'] as $index => $rowValue) {
                $rowArray = array();
                foreach ($rowValue as $rowindex => $rowData) {
                    $st = '';
                    if (!empty($rowData) && is_json($rowData)) {
                        $jdata = json_decode($rowData);

                        if (!empty($jdata)) {

                            $t = 1;
                            foreach ($jdata as $row) {
                                $st .= ' ' . $t . ') ' . nl2br(strip_tags($row->content)) . ' Date :' . configDateTimeFormat($row->date);
                                $t++;
                            }
                        }
                    }
                    $rowArray[$rowindex] = html_entity_decode(strip_tags(!empty($st) ? $st : $rowData));
                }
                if ($headerCount === 1) {
                    $exceldataHeader[] = $recordData['headerData']; //array_keys($rowValue); // Set Header of the generated Excel File
                    //continue;
                }
                $exceldataValue[] = $rowArray; // Set values
                $headerCount++;
            }

            //Fill data 
            $this->excel->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->fromArray($exceldataHeader, Null, 'A1'); // Set Header Data
            $this->excel->getActiveSheet()->fromArray($exceldataValue, Null, 'A2'); // Set Fetch Data

            $this->activeSheetIndex = $this->excel->setActiveSheetIndex(0);
        }
        $fileName = $reportType . date('Y-m-d H:i:s') . '.xls'; // Generate file name

        $this->downloadExcelFile($this->excel, $fileName); // download function Xls file function call
    }

    /**
     * @Autor Niral Patel
     * @Desc downloadExcelfile
     * @param type $excel     
     * @return type
     * @Date 6th June 2017
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
     * @Autor Niral Patel
     * @Desc download documents
     * @param type $excel     
     * @return type
     * @Date 29th Aug 2017
     */
    function download_documents($docs_id, $imagename) {
        //get document data
        $params['fields'] = ['*'];
        $params['table'] = YP_DOCUMENTS . ' as docs';
        $params['match_and'] = 'docs.docs_id=' . $docs_id . '';
        $cost_files = $this->common_model->get_records_array($params);
        if (count($cost_files) > 0) {

            $pth = file_get_contents($this->config->item('docs_img_base_url') . $cost_files[0]['yp_id'] . '/' . $imagename);
            $this->load->helper('download');
            force_download($image_name, $pth);
        }
        redirect('Admin/Reports/DOCS');
    }

}
