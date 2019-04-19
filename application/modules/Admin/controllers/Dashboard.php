<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct() {
        parent::__construct();
        //  check_admin_login();
        if (checkPermission('Admin', 'view') == false) {
            redirect('/Dashboard');
        }
        $this->viewname = 'Dashboard';
    }

    /*
      Author : Niral Patel
      Desc  :
      Input  :
      Output :
      Date   :06/06/2017
     */

    public function index() {
        $data['headerCss'][0] = base_url('uploads/assets/js/admin/bower_components/bootstrap/dist/css/bootstrap.min.css');
        $data['headerCss'][1] = base_url('uploads/assets/js/admin/bower_components/font-awesome/css/font-awesome.min.css');
        $data['headerCss'][2] = base_url('uploads/assets/js/admin/bower_components/Ionicons/css/ionicons.min.css');

        $data['footerJs'][0] = base_url('uploads/custom/js/admindashboard/dashboard.js');
        // $data['footerJs'][1] = base_url('uploads/assets/js/admin/bower_components/jquery/dist/jquery.min.js');
        //$data['footerJs'][2] = base_url('uploads/assets/js/admin/bower_components/bootstrap/dist/js/bootstrap.min.js');
        $data['footerJs'][2] = base_url('uploads/assets/js/admin/bower_components/fastclick/lib/fastclick.js');
        $data['footerJs'][3] = base_url('uploads/assets/js/admin/dist/js/adminlte.min.js');
        $data['footerJs'][4] = base_url('uploads/assets/js/admin/dist/js/demo.js');
        $data['footerJs'][5] = base_url('uploads/assets/js/admin/bower_components/Flot/jquery.flot.js');
        $data['footerJs'][6] = base_url('uploads/assets/js/admin/bower_components/Flot/jquery.flot.resize.js');
        $data['footerJs'][7] = base_url('uploads/assets/js/admin/bower_components/Flot/jquery.flot.pie.js');
        $data['footerJs'][8] = base_url('uploads/assets/js/admin/bower_components/Flot/jquery.flot.categories.js');
        $data['footerJs'][9] = base_url('uploads/assets/js/admin/bower_components/raphael/raphael.min.js');
        $data['footerJs'][10] = base_url('uploads/assets/js/admin/bower_components/morris.js/morris.min.js');
        $data['footerJs'][11] = base_url('uploads/assets/js/bootstrap-datetimepicker.min.js');

        $data['ypList'] = $this->getYpList(); // Yp List
        //$data['YpChartData'] = $this->getYpChartData();

        $data['main_content'] = ADMIN_SITE . '/' . $this->viewname . '/' . $this->viewname;
        $this->parser->parse(ADMIN_SITE . '/assets/template', $data);
    }

    private function getYpList() {

        $tableName = YP_DETAILS . ' as yp';
        $fields = array('yp.yp_id, CONCAT(yp.yp_fname," ",yp.yp_lname) as ypName');

        $whereCond = array('yp.status' => 'active', 'yp.is_deleted' => '0');

        $data['information'] = $this->common_model->get_records($tableName, $fields, '', '', $whereCond, '', '', '', '', '', '', '');

        return $data['information'];
    }

    public function getYpChartData() {

        if ($this->input->post("year")) {
            $year = $this->input->post("year");
        } else {
            $year = date('Y');
        }
        $tableName = YP_DETAILS . ' as yp';
        $fields = array('COUNT(yp.yp_id) as totalyp,  MONTHNAME(yp.modified_date) AS monthName '); //MONTHNAME(yp.modified_date)

        if (!empty($year)) {
            $currentYear = $year;
            $whereCond = array('yp.is_deleted' => '0', 'yp.status' => 'active', 'DATE_FORMAT(yp.modified_date, "%Y")=' => $currentYear);
        } else {
            $currentYear = $year;
            $whereCond = array('yp.is_deleted' => '0', 'yp.status' => 'active', 'DATE_FORMAT(yp.modified_date, "%Y")=' => $currentYear);
        }

        $groupBy = 'monthName';
        $ypData = $this->common_model->get_records($tableName, $fields, '', '', $whereCond, '', '', '', '', '', $groupBy, '');
        $months = array(
            'Jan' => '0',
            'Feb' => '0',
            'Mar' => '0',
            'Apr' => '0',
            'May' => '0',
            'Jun' => '0',
            'Jul' => '0',
            'Aug' => '0',
            'Sep' => '0',
            'Oct' => '0',
            'Nov' => '0',
            'Dec' => '0',
        );
        $ypyeardata = array();
        if (!empty($ypData)) {
            foreach ($ypData as $monthData) {
                $ypyeardata[substr($monthData['monthName'], 0, 3)] = $monthData['totalyp'];
            }
        }
        $finalData = array_merge($months, $ypyeardata);
        echo json_encode($finalData);
    }

    public function getKsChartData() {
        if ($this->input->post("year")) {
            $year = $this->input->post("year");
        } else {
            $year = date('Y');
        }
        $tableName = KEY_SESSION . ' as ks';
        $fields = array('COUNT(ks.yp_id) as totalyp,  MONTHNAME(ks.date) AS monthName '); //MONTHNAME(yp.modified_date)

        if (!empty($year)) {
            $currentYear = $year;
            $whereCond = array('DATE_FORMAT(ks.date, "%Y")=' => $currentYear);
        } else {
            $currentYear = $year;
            $whereCond = array('DATE_FORMAT(ks.date, "%Y")=' => $currentYear);
        }

        $groupBy = 'monthName';
        $ypData = $this->common_model->get_records($tableName, $fields, '', '', $whereCond, '', '', '', '', '', $groupBy, '');
        $months = array(
            'Jan' => '0',
            'Feb' => '0',
            'Mar' => '0',
            'Apr' => '0',
            'May' => '0',
            'Jun' => '0',
            'Jul' => '0',
            'Aug' => '0',
            'Sep' => '0',
            'Oct' => '0',
            'Nov' => '0',
            'Dec' => '0',
        );
        $ypyeardata = array();
        if (!empty($ypData)) {
            foreach ($ypData as $monthData) {
                $ypyeardata[substr($monthData['monthName'], 0, 3)] = $monthData['totalyp'];
            }
        }
        $finalData = array_merge($months, $ypyeardata);
        echo json_encode($finalData);
    }

    public function getDoChartData() {
        if ($this->input->post("year")) {
            $year = $this->input->post("year");
        } else {
            $year = date('Y');
        }
        $tableName = DAILY_OBSERVATIONS . ' as do';
        $fields = array('COUNT(do.yp_id) as totalyp,  MONTHNAME(do.daily_observation_date) AS monthName '); //MONTHNAME(yp.modified_date)

        if (!empty($year)) {
            $currentYear = $year;
            $whereCond = array('DATE_FORMAT(do.daily_observation_date, "%Y")=' => $currentYear);
        } else {
            $currentYear = $year;
            $whereCond = array('DATE_FORMAT(do.daily_observation_date, "%Y")=' => $currentYear);
        }

        $groupBy = 'monthName';
        $ypData = $this->common_model->get_records($tableName, $fields, '', '', $whereCond, '', '', '', '', '', $groupBy, '');
        $months = array(
            'Jan' => '0',
            'Feb' => '0',
            'Mar' => '0',
            'Apr' => '0',
            'May' => '0',
            'Jun' => '0',
            'Jul' => '0',
            'Aug' => '0',
            'Sep' => '0',
            'Oct' => '0',
            'Nov' => '0',
            'Dec' => '0',
        );
        $ypyeardata = array();
        if (!empty($ypData)) {
            foreach ($ypData as $monthData) {
                $ypyeardata[substr($monthData['monthName'], 0, 3)] = $monthData['totalyp'];
            }
        }
        $finalData = array_merge($months, $ypyeardata);
        echo json_encode($finalData);
    }

    public function getPPChartData() {
        if ($this->input->post("year")) {
            $year = $this->input->post("year");
        } else {
            $year = date('Y');
        }
        $tableName = PLACEMENT_PLAN . ' as pp';
        $fields = array('COUNT(pp.yp_id) as totalyp,  MONTHNAME(pp.created_date) AS monthName '); //MONTHNAME(yp.modified_date)

        if (!empty($year)) {
            $currentYear = $year;
            $whereCond = array('DATE_FORMAT(pp.created_date, "%Y")=' => $currentYear);
        } else {
            $currentYear = $year;
            $whereCond = array('DATE_FORMAT(pp.created_date, "%Y")=' => $currentYear);
        }

        $groupBy = 'monthName';
        $ypData = $this->common_model->get_records($tableName, $fields, '', '', $whereCond, '', '', '', '', '', $groupBy, '');
        $months = array(
            'Jan' => '0',
            'Feb' => '0',
            'Mar' => '0',
            'Apr' => '0',
            'May' => '0',
            'Jun' => '0',
            'Jul' => '0',
            'Aug' => '0',
            'Sep' => '0',
            'Oct' => '0',
            'Nov' => '0',
            'Dec' => '0',
        );
        $ypyeardata = array();
        if (!empty($ypData)) {
            foreach ($ypData as $monthData) {
                $ypyeardata[substr($monthData['monthName'], 0, 3)] = $monthData['totalyp'];
            }
        }
        $finalData = array_merge($months, $ypyeardata);
        echo json_encode($finalData);
    }

    public function getIbpChartData() {
        if ($this->input->post("year")) {
            $year = $this->input->post("year");
        } else {
            $year = date('Y');
        }
        $tableName = INDIVIDUAL_BEHAVIOUR_PLAN . ' as ibp';
        $fields = array('COUNT(ibp.yp_id) as totalyp,  MONTHNAME(ibp.created_date) AS monthName '); //MONTHNAME(yp.modified_date)

        if (!empty($year)) {
            $currentYear = $year;
            $whereCond = array('DATE_FORMAT(ibp.created_date, "%Y")=' => $currentYear);
        } else {
            $currentYear = $year;
            $whereCond = array('DATE_FORMAT(ibp.created_date, "%Y")=' => $currentYear);
        }

        $groupBy = 'monthName';
        $ypData = $this->common_model->get_records($tableName, $fields, '', '', $whereCond, '', '', '', '', '', $groupBy, '');
        $months = array(
            'Jan' => '0',
            'Feb' => '0',
            'Mar' => '0',
            'Apr' => '0',
            'May' => '0',
            'Jun' => '0',
            'Jul' => '0',
            'Aug' => '0',
            'Sep' => '0',
            'Oct' => '0',
            'Nov' => '0',
            'Dec' => '0',
        );
        $ypyeardata = array();
        if (!empty($ypData)) {
            foreach ($ypData as $monthData) {
                $ypyeardata[substr($monthData['monthName'], 0, 3)] = $monthData['totalyp'];
            }
        }
        $finalData = array_merge($months, $ypyeardata);
        echo json_encode($finalData);
    }

    public function getRaChartData() {
        if ($this->input->post("year")) {
            $year = $this->input->post("year");
        } else {
            $year = date('Y');
        }
        $tableName = RISK_ASSESSMENT . ' as ra';
        $fields = array('COUNT(ra.yp_id) as totalyp,  MONTHNAME(ra.created_date) AS monthName '); //MONTHNAME(yp.modified_date)

        if (!empty($year)) {
            $currentYear = $year;
            $whereCond = array('DATE_FORMAT(ra.created_date, "%Y")=' => $currentYear);
        } else {
            $currentYear = $year;
            $whereCond = array('DATE_FORMAT(ra.created_date, "%Y")=' => $currentYear);
        }

        $groupBy = 'monthName';
        $ypData = $this->common_model->get_records($tableName, $fields, '', '', $whereCond, '', '', '', '', '', $groupBy, '');
        $months = array(
            'Jan' => '0',
            'Feb' => '0',
            'Mar' => '0',
            'Apr' => '0',
            'May' => '0',
            'Jun' => '0',
            'Jul' => '0',
            'Aug' => '0',
            'Sep' => '0',
            'Oct' => '0',
            'Nov' => '0',
            'Dec' => '0',
        );
        $ypyeardata = array();
        if (!empty($ypData)) {
            foreach ($ypData as $monthData) {
                $ypyeardata[substr($monthData['monthName'], 0, 3)] = $monthData['totalyp'];
            }
        }
        $finalData = array_merge($months, $ypyeardata);
        echo json_encode($finalData);
    }

    public function getDocsChartData() {
        if ($this->input->post("year")) {
            $year = $this->input->post("year");
        } else {
            $year = date('Y');
        }
        $tableName = YP_DOCUMENTS . ' as docs';
        $fields = array('COUNT(docs.yp_id) as totalyp,  MONTHNAME(docs.created_date) AS monthName '); //MONTHNAME(yp.modified_date)

        if (!empty($year)) {
            $currentYear = $year;
            $whereCond = array('DATE_FORMAT(docs.created_date, "%Y")=' => $currentYear);
        } else {
            $currentYear = $year;
            $whereCond = array('DATE_FORMAT(docs.created_date, "%Y")=' => $currentYear);
        }

        $groupBy = 'monthName';
        $ypData = $this->common_model->get_records($tableName, $fields, '', '', $whereCond, '', '', '', '', '', $groupBy, '');
        $months = array(
            'Jan' => '0',
            'Feb' => '0',
            'Mar' => '0',
            'Apr' => '0',
            'May' => '0',
            'Jun' => '0',
            'Jul' => '0',
            'Aug' => '0',
            'Sep' => '0',
            'Oct' => '0',
            'Nov' => '0',
            'Dec' => '0',
        );
        $ypyeardata = array();
        if (!empty($ypData)) {
            foreach ($ypData as $monthData) {
                $ypyeardata[substr($monthData['monthName'], 0, 3)] = $monthData['totalyp'];
            }
        }
        $finalData = array_merge($months, $ypyeardata);
        echo json_encode($finalData);
    }

    public function getMedsChartData() {
        if ($this->input->post("year")) {
            $year = $this->input->post("year");
        } else {
            $year = date('Y');
        }
        $tableName = MEDICAL_AUTHORISATIONS_CONSENTS . ' as meds';
        $fields = array('COUNT(meds.yp_id) as totalyp,  MONTHNAME(meds.created_date) AS monthName'); //MONTHNAME(yp.modified_date)

        if (!empty($year)) {
            $currentYear = $year;
            $whereCond = array('DATE_FORMAT(meds.created_date, "%Y")=' => $currentYear);
        } else {
            $currentYear = $year;
            $whereCond = array('DATE_FORMAT(meds.created_date, "%Y")=' => $currentYear);
        }

        $groupBy = 'monthName';
        $ypData = $this->common_model->get_records($tableName, $fields, '', '', $whereCond, '', '', '', '', '', $groupBy, '');
        $months = array(
            'Jan' => '0',
            'Feb' => '0',
            'Mar' => '0',
            'Apr' => '0',
            'May' => '0',
            'Jun' => '0',
            'Jul' => '0',
            'Aug' => '0',
            'Sep' => '0',
            'Oct' => '0',
            'Nov' => '0',
            'Dec' => '0',
        );
        $ypyeardata = array();
        if (!empty($ypData)) {
            foreach ($ypData as $monthData) {
                $ypyeardata[substr($monthData['monthName'], 0, 3)] = $monthData['totalyp'];
            }
        }
        $finalData = array_merge($months, $ypyeardata);
        echo json_encode($finalData);
    }

    public function getComsChartData() {
        if ($this->input->post("year")) {
            $year = $this->input->post("year");
        } else {
            $year = date('Y');
        }
        $tableName = COMMUNICATION_LOG . ' as coms';
        $fields = array('COUNT(coms.yp_id) as totalyp,  MONTHNAME(coms.created_date) AS monthName'); //MONTHNAME(yp.modified_date)

        if (!empty($year)) {
            $currentYear = $year;
            $whereCond = array('DATE_FORMAT(coms.created_date, "%Y")=' => $currentYear);
        } else {
            $currentYear = $year;
            $whereCond = array('DATE_FORMAT(coms.created_date, "%Y")=' => $currentYear);
        }

        $groupBy = 'monthName';
        $ypData = $this->common_model->get_records($tableName, $fields, '', '', $whereCond, '', '', '', '', '', $groupBy, '');
        $months = array(
            'Jan' => '0',
            'Feb' => '0',
            'Mar' => '0',
            'Apr' => '0',
            'May' => '0',
            'Jun' => '0',
            'Jul' => '0',
            'Aug' => '0',
            'Sep' => '0',
            'Oct' => '0',
            'Nov' => '0',
            'Dec' => '0',
        );
        $ypyeardata = array();
        if (!empty($ypData)) {
            foreach ($ypData as $monthData) {
                $ypyeardata[substr($monthData['monthName'], 0, 3)] = $monthData['totalyp'];
            }
        }
        $finalData = array_merge($months, $ypyeardata);
        echo json_encode($finalData);
    }

}
