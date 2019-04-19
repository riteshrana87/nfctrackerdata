<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CommonController extends CI_Controller {

    function __construct() {

        parent::__construct();
    }

    /*
      @Author : Dhara Bhalala
      @Desc   : Common Function To Download Files from all over system
      @Input 	:
      @Output	:
      @Date   : 24/09/2018
     */

    public function downloadFile() {
        $downloadArray = $this->input->get();
        if (isset($downloadArray) && !empty($downloadArray)) {
            $filePath = urldecode(base64_decode($downloadArray['file_name']));
            $fileName = basename($filePath);
            $CI = & get_instance();
            if ($fileName != '') {
                $path = file_get_contents($filePath);
                $CI->load->helper('download');
                force_download($fileName, $path);
                exit;
            }
        }
    }

    /*
      @Author : Nikunj Ghelani
      @Desc   : Add CareHomeId YPId wise at each table
      @Input 	:
      @Output	:
      @Date   : 23/10/2018
     */

    public function UpdateCareHomeID() {


        //GET ALL WORKING YPLIST
        $database = $this->db->database;

        $match = array("is_deleted" => '0', "is_archive" => '0');
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
        //SELECT THOSE TABLE WHICH HAS CARE_HOME_ID COLOUMN
        $sql = $this->db->query("Select * From INFORMATION_SCHEMA.COLUMNS Where column_name = 'care_home_id'AND TABLE_SCHEMA = '" . $database . "'");

        $result = $sql->result_array();

        foreach ($data['YP_details'] as $yp_detail) {

            foreach ($result as $table_name) {
                echo $table_name['TABLE_NAME'] . "</br>";

                if (($table_name['TABLE_NAME'] != 'nfc_care_home') && ($table_name['TABLE_NAME'] != 'nfc_medical_care_home_transection') && ($table_name['TABLE_NAME'] != 'nfc_record_ans')) {
                    $update = $this->db->query("UPDATE " . $table_name['TABLE_NAME'] . " SET care_home_id = '" . $yp_detail['care_home'] . "' WHERE yp_id='" . $yp_detail['yp_id'] . "'");
                }
            }
        }

        echo "done";
        die;
    }

    /*
      @Author : Dhara Bhalala
      @Desc   : Database date and time fields format recovery for all module
      @Input 	:
      @Output	:
      @Date   : 11/10/2018
     */

    function changeDateTime() {
        /* steps that need to follow to run script
         * 1- uncomment module,
         * 2- comment update query and first check result by uncomment pr() function
         * 3- if result is ok, then run the script.
         * 4- change data type using commented Query.
         */
        $modules = array(
   //         'KS',
            'MEDS',
//            'COMS',
//            'WR',
//            'YPC',
//            'LR',
//            'RMP',
        );

        foreach ($modules as $module) {
            switch ($module) {

                case 'KS':
                    $query = 'select ks_id,IF(date, STR_TO_DATE(date, "%d/%m/%Y"), "")as date,time as time from nfc_key_session';
                    $data = $this->common_model->customQuery($query);
                    if (isset($data) && !empty($data)) {
                        foreach ($data as $key => $value) {
                            $newDate = $this->getNewDate($value['date']);
                            $newTime = $this->getNewTime($value['time']);
//                        pr($value['date'] . '=>' . $newDate); pr($value['time'] . '=>' . $newTime);
//                        ALTER TABLE `nfc_key_session` CHANGE `date` `date` DATE NULL DEFAULT NULL, CHANGE `time` `time` TIME NULL DEFAULT NULL;
                            $this->common_model->update(KEY_SESSION, array("date" => $newDate, "time" => $newTime), array('ks_id' => $value['ks_id']));
                        }
                    }
                    break;

                case 'MEDS':
                    $query1 = 'select administer_medication_id,IF(date_given, STR_TO_DATE(date_given, "%d/%m/%Y"), "")as date,time_given as time from nfc_administer_medication';
                    $data = $this->common_model->customQuery($query1);
                    if (isset($data) && !empty($data)) {
                        foreach ($data as $key => $value) {
                            $newDate = $this->getNewDate($value['date']);
                            $newTime = $this->getNewTime($value['time']);
                        pr($value['date'] . '=>' . $newDate); pr($value['time'] . '=>' . $newTime);
//                        ALTER TABLE `nfc_administer_medication` CHANGE `date_given` `date_given` DATE NULL DEFAULT NULL, CHANGE `time_given` `time_given` TIME NULL DEFAULT NULL;
                            $this->common_model->update(ADMINISTER_MEDICATION, array("date_given" => $newDate, "time_given" => $newTime), array('administer_medication_id' => $value['administer_medication_id']));
                        }
                    }

                    break;

                case 'COMS':
                    $query = 'select communication_log_id,IF(date_of_communication, STR_TO_DATE(date_of_communication, "%d/%m/%Y"), "")as date,time as time from nfc_communication_log';
                    $data = $this->common_model->customQuery($query);
                    if (isset($data) && !empty($data)) {
                        foreach ($data as $key => $value) {
                            $newDate = $this->getNewDate($value['date']);
                            $newTime = $this->getNewTime($value['time']);
//                        pr($value['date'] . '=>' . $newDate); pr($value['time'] . '=>' . $newTime);
//                        ALTER TABLE `nfc_communication_log` CHANGE `date_of_communication` `date_of_communication` DATE NULL DEFAULT NULL, CHANGE `time` `time` TIME NULL DEFAULT NULL;
                            $this->common_model->update(COMMUNICATION_LOG, array("date_of_communication" => $newDate, "time" => $newTime), array('communication_log_id' => $value['communication_log_id']));
                        }
                    }
                    break;

                case 'WR':
                    $query = 'select weekly_report_id,IF(health_lac_date, STR_TO_DATE(health_lac_date, "%d/%m/%Y"), "")as date from nfc_weekly_report';
                    $data = $this->common_model->customQuery($query);
                    if (isset($data) && !empty($data)) {
                        foreach ($data as $key => $value) {
                            $newDate = $this->getNewDate($value['date']);
//                        pr($value['date'] . '=>' . $newDate);
//                        ALTER TABLE `nfc_weekly_report` CHANGE `health_lac_date` `health_lac_date` DATE NULL DEFAULT NULL;
                            $this->common_model->update(WEEKLY_REPORT, array("health_lac_date" => $newDate), array('weekly_report_id' => $value['weekly_report_id']));
                        }
                    }

                    break;

                case 'YPC':
                    $query = 'select ypc_id,IF(date, STR_TO_DATE(date, "%d/%m/%Y"), "")as date,time as time from nfc_yp_concerns';
                    $data = $this->common_model->customQuery($query);
                    if (isset($data) && !empty($data)) {
                        foreach ($data as $key => $value) {
                            $newDate = $this->getNewDate($value['date']);
                            $newTime = $this->getNewTime($value['time']);
//                        pr($value['date'] . '=>' . $newDate);  pr($value['time'] . '=>' . $newTime);
//                        ALTER TABLE `nfc_yp_concerns` CHANGE `date` `date` DATE NULL DEFAULT NULL, CHANGE `time` `time` TIME NULL DEFAULT NULL;
                            $this->common_model->update(YP_CONCERNS, array("date" => $newDate, "time" => $newTime), array('ypc_id' => $value['ypc_id']));
                        }
                    }

                    break;

                case 'LR':
                    $query = 'select location_register_id,IF(left_date, STR_TO_DATE(left_date, "%d/%m/%Y"), "")as date1,left_time as time1,'
                            . 'IF(returned_date, STR_TO_DATE(returned_date, "%d/%m/%Y"), "")as date2,returned_time as time2 from nfc_location_register';
                    $data = $this->common_model->customQuery($query);
                    if (isset($data) && !empty($data)) {
                        foreach ($data as $key => $value) {
                            $newDate1 = $this->getNewDate($value['date1']);
                            $newTime1 = $this->getNewTime($value['time1']);
                            $newDate2 = $this->getNewDate($value['date2']);
                            $newTime2 = $this->getNewTime($value['time2']);
//                        pr($value['date1'] . '=>' . $newDate1); pr($value['time1'] . '=>' . $newTime1);
//                        pr($value['date2'] . '=>' . $newDate2); pr($value['time2'] . '=>' . $newTime2);
//                        ALTER TABLE `nfc_location_register` CHANGE `left_date` `left_date` DATE NULL DEFAULT NULL, CHANGE `left_time` `left_time` TIME NULL DEFAULT NULL, CHANGE `returned_date` `returned_date` DATE NULL DEFAULT NULL, CHANGE `returned_time` `returned_time` TIME NULL DEFAULT NULL;
                            $this->common_model->update(LOCATION_REGISTER, array("left_date" => $newDate1, "left_time" => $newTime1, "returned_date" => $newDate2, "returned_time" => $newTime2), array('location_register_id' => $value['location_register_id']));
                        }
                    }
                    
                    break;

                case 'RMP':
                    $query = 'select rmp_id,IF(activity_date_from, STR_TO_DATE(activity_date_from, "%d/%m/%Y"), "")as date1,'
                            . 'IF(activity_date_to, STR_TO_DATE(activity_date_to, "%d/%m/%Y"), "")as date2 from nfc_rmp';
                    $data = $this->common_model->customQuery($query);
                    if (isset($data) && !empty($data)) {
                        foreach ($data as $key => $value) {
                            $newDate1 = $this->getNewDate($value['date1']);
                            $newDate2 = $this->getNewDate($value['date2']);
//                        pr($value['date1'] . '=>' . $newDate1); pr($value['date2'] . '=>' . $newDate2);
//                        ALTER TABLE `nfc_rmp` CHANGE `activity_date_from` `activity_date_from` DATE NULL DEFAULT NULL, CHANGE `activity_date_to` `activity_date_to` DATE NULL DEFAULT NULL;
                            $this->common_model->update(RMP, array("activity_date_from" => $newDate1, "activity_date_to" => $newDate2), array('rmp_id' => $value['rmp_id']));
                        }
                    }

                    break;
                default:
                    break;
            }
            pre($module . ' Done');
        }
    }

    /*
      @Author : Dhara Bhalala
      @Desc   : comon date format
      @Input 	:
      @Output	:
      @Date   : 11/10/2018
     */

    function getNewDate($oldDate) {
        if ($oldDate != '') {
            $date = strtotime($oldDate);
            $newDate = date('Y-m-d', $date);
            return $newDate;
        } else {
            return $oldDate;
        }
    }

    /*
      @Author : Dhara Bhalala
      @Desc   : comon time format
      @Input 	:
      @Output	:
      @Date   : 11/10/2018
     */

    function getNewTime($time) {
        if ($time != '') {
            $newTime = date("H:i:s", strtotime($time));
            return $newTime;
        } else {
            return $time;
        }
    }

    /* add ks/ypc permission to all roles */
    /* function roleManagement() {        
      $permissionId = 10;
      $module1Id = 10;
      $module2Id = 28;
      $query = 'select distinct role_id from nfc_aauth_perm_to_group';
      $data = $this->common_model->customQuery($query);
      $dbRoleArray = array_map(function ($obj) {
      return $obj['role_id'];
      }, $data);
      //        pr($dbRoleArray);
      $exploded = array();
      foreach ($dbRoleArray as $role) {
      $exploded[] = array('perm_id' => $permissionId, 'role_id' => $role, 'module_id' => $module1Id, 'component_name' => 'NFC');
      $exploded[] = array('perm_id' => $permissionId, 'role_id' => $role, 'module_id' => $module2Id, 'component_name' => 'NFC');

      }
      //        pre($exploded);

      //        $this->common_model->insert_batch(AAUTH_PERMS_TO_ROLE, $exploded);
      } */

    function docsRename() {
        ini_set('max_execution_time', 0);
        $query = "SELECT docs_id,yp_id,input_single_file FROM `nfc_yp_documents`";
        $data = $this->common_model->customQuery($query);
        foreach ($data as $key => $value) {
            $renameImg[0] = $value['input_single_file'];
            $yp_id = $value['yp_id'];
            $newname = filterFileName($renameImg);
            $path = $this->config->item('docs_img_url') . $yp_id . '/';
//            rename($path . $renameImg[0], $path . $newname);
//            $this->common_model->update('nfc_yp_documents', array("input_single_file" => $newname), array('docs_id' => $value['docs_id']));                    
            pr('renamed file to' . $newname);
        }
        pre('end');
    }

}
