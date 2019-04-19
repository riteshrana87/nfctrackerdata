<?php

/*
  @Author	: Niral Patel
  @Desc	: Function for get diffrent between two string
  @Input 	: Array
  @Output	: Array
  @Date	: 27/09/2017
 */
function get_decorated_diff($old, $new){
    $originalOld = $old;
    $originalNew = $new;
    $old = strtolower($old); //Add this line
    $new = strtolower($new); //Add this line

    $from_start = strspn($old ^ $new, "\0");        
    $from_end = strspn(strrev($old) ^ strrev($new), "\0");

    $old_end = strlen($old) - $from_end;
    $new_end = strlen($new) - $from_end;

    $start = substr($new, 0, $from_start);
    $end = substr($new, $new_end);
    $new_diff = substr($originalNew, $from_start, $new_end - $from_start);  
    $old_diff = substr($originalOld, $from_start, $old_end - $from_start);

    echo $new = "$start<ins style='background-color:#ccffcc'>$new_diff</ins>$end";exit;
    $old = "$start<del style='background-color:#ffcccc'>$old_diff</del>$end";
    return $new;
}

function htmlDiff($old, $new){ 
        $diff = diff(explode(' ', $old), explode(' ', $new)); 
        foreach($diff as $k){ 
                if(is_array($k)) 
                        $ret .= (!empty($k['d'])?"<del>".implode(' ',$k['d'])."</del> ":''). 
                                (!empty($k['i'])?"<ins>".implode(' ',$k['i'])."</ins> ":''); 
                else $ret .= $k . ' '; 
        } 
        return $ret; 
} 

function diff($old, $new) {
    $old = trim($old);
    $new = trim($new);

    if($old == $new) {
        return $new;
    }

    $old    = explode(" ", $old);
    $new    = explode(" ", $new);    
  
    $result = "";       
    $length = max(count($old), count($new));

    for($i = 0; $i < $length; $i++) {
      /*echo $i.'<br>';*/
      $original_old='';$original_new='';$oldst='';$newst='';
       $original_old = $old[$i];
       $original_new = $new[$i];
      $oldst = !empty($old[$i])?strip_tags($old[$i]):'';
      $newst = !empty($new[$i])?strip_tags($new[$i]):'';
     
        if(!isset($new[$i])) {
            //$result .= "<del>{$old[$i]}</del>";
            continue;
        }

        if(!isset($oldst)) { 
            //$result .= "<ins style='background-color:#ccffcc'>{$original_new}</ins> ";
             $result .= str_replace($newst,"<ins style='background-color:#ccffcc'>{$newst}</ins>", $original_new);
            continue;
        }           

        if(strip_tags($oldst) != strip_tags($newst)) { 
         // $result .= "<ins style='background-color:#ccffcc'>{$original_new}</ins> ";
          $result .= str_replace($newst,"<span style='background-color:#ccffcc'>{$newst}</span>", $original_new);
            continue;
        }

        $result .= "{$original_new} ";
    }
    $result = str_replace(array("</ins> <ins>", "</del> <del>"), " ", $result);     

    return trim($result);   
}
/*
  @Author : Niral Patel
  @Desc : Function for use Pre in Short-Cut
  @Input  : Array
  @Output : Array
  @Date : 27/09/2017
 */
function pr($var) {
    echo '<pre>';
    if (is_array($var)) {
        print_r($var);
    } else {
        var_dump($var);
    }
    echo '</pre>';
}
/*function created by dhara bhalala to print and exit */
function pre($var) {
    echo '<pre>';
    if (is_array($var)) {
        print_r($var);
    } else {
        var_dump($var);
    }
    echo '</pre>';
    exit;
}

/*
  @Author	: Ritesh Rana
  @Desc	        : Function for generate random number
  @Input 	: no of character length
  @Output	: generated random number
  @Date	        : 15th March 2017
 */

function randomNumber($length, $character = false) {
    $random = "";
    rand((double) microtime() * 1000000);

    $data = '1234567890';
    if ($character) {

        $data .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        //$data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
        //$data .= "0FGH45OP89";
    }

    for ($i = 0; $i < $length; $i++) {
        $random .= substr($data, (rand() % (strlen($data))), 1);
    }
    return $random;
}



/*
  @Author	: Ritesh Rana
  @Desc	: Function for Active current session for pagination
  @Input 	: no of character length
  @Output	: generated random number
  @Date	: 15th March 2017
 */

function setActiveSession($activeSession = '') {
    $CI = & get_instance();

    $CI->load->library('session');
    $sess_array = $CI->session->all_userdata();

    //pr($sess_array['LOGGED_IN']);
    foreach ($sess_array as $key => $val) {

        if ($key != 'session_id' && $key != $activeSession && $key != 'LOGGED_IN' && $key != 'nfc_admin_session') { // Except Login Session
            $CI->session->unset_userdata($key);
        }
    }
}

/*
  @Author	: Ritesh Rana
  @Desc		: Function for Show Round Amount
  @Input 	: Array
  @Output	: Array
  @Date		: 10/03/2018
 */

function amtRound($val) {
    return round($val, 2);
}

/*
  @Author	: Ritesh Rana
  @Desc		: Set Date Formate as per Config Table
  @Input 	: Date
  @Output	: Date with formate
  @Date		: 13/04/2018
 */

function configDateTime($date) {
    $ci = & get_instance();
    if($ci->session->userdata('config_date_format') == ''){
    $table = CONFIG . ' as con';
    $fields = array("con.value, con.config_key");
    $where = "con.config_key = 'date_format'";
    $dateInfo = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
        $ci->session->set_userdata('config_date_format', $dateInfo[0]['value']);
    }    
    $dateInfo = $ci->session->userdata('config_date_format');
    //return date('m/d/Y', strtotime($date));
    if (!empty($date) && $date !== '' && $date !== '0000-00-00') {
        return date($dateInfo, strtotime($date));
    } else {
//        return date($dateInfo);
        return '';
    }
}
/*
  @Author : Ritesh Rana
  @Desc   : Set Date Formate as per Config Table
  @Input  : Date
  @Output : Date with formate
  @Date   : 13/04/2018
 */

function configDateFormat($date) {
    $ci = & get_instance();
    $table = CONFIG . ' as con';
    $fields = array("con.value, con.config_key");
    $where = "con.config_key = 'date_format'";
    $dateInfo = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $dateInfo[0]['value'];    
}
/*
  @Author : Ritesh Rana
  @Desc   : Set Datetime Formate as per Config Table
  @Input  : Date
  @Output : Datetime with formate
  @Date   : 11/05/2017
 */

function configDateTimeFormat($date) {
    $ci = & get_instance();
    if($ci->session->userdata('config_date_time_format') == ''){
        $table = CONFIG . ' as con';
        $fields = array("con.value, con.config_key");
        $where = "con.config_key = 'datetime_format'";
        $dateInfo = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
        $ci->session->set_userdata('config_date_time_format', $dateInfo[0]['value']); 
    }
    $dateInfo = $ci->session->userdata('config_date_time_format');
    //return date('m/d/Y', strtotime($date));
    if (!empty($date)) {
        return date($dateInfo, strtotime($date));
    } else {
        return date($dateInfo);
    }
}

function getStaffUploadData($staff_id) {
    $ci = & get_instance();
    $table = STAFF_NOTICES_UPLOADS . ' as snu';
    $where = array("staff_notices_id" => $staff_id);
    $fields = array("snu.*");
    $info_data = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $info_data;
}

function getPhotoUploadData($photo_gallery_id) {
    $ci = & get_instance();
    $table = GALLERY_PHOTO_UPLOADS . ' as snu';
    $where = array("photo_gallery_id" => $photo_gallery_id,"is_delete" => 0);
    $fields = array("snu.*");
    $info_data = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $info_data;
}

function getSchoolHandUploadData($staff_id) {
    $ci = & get_instance();
    $table = SCHOOL_HANDOVER_FILE . ' as shf';
    $where = array("school_handover_id" => $staff_id);
    $fields = array("shf.*");
    $info_data = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $info_data;
}

function getCrisisHandUploadData($staff_id) {
    $ci = & get_instance();
    $table = CRISIS_HANDOVER_FILE . ' as shf';
    $where = array("crisis_handover_id" => $staff_id);
    $fields = array("shf.*");
    $info_data = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $info_data;
}

/*
  @Author : Ritesh Rana
  @Desc   : Function for Formate Date
  @Input 	: Date Formate
  @Output	: Date
  @Date   : 12/01/2016
 */

function datetimeformat($date = '') {
    if (!empty($date)) {
        return date("Y-m-d H:i:s", strtotime($date));
    } else {
        return date("Y-m-d H:i:s");
    }
}
/* function used to display time */
function timeformat($time = '') {
    if (!empty($time)) {
        return date("g:i A", strtotime($time));
    } else {
        return date("g:i A");
    }
}
/* function used to display time In AI module because time picker is changed 24 hours in AI only */
function timeFormatAi($time = '') {
    if (!empty($time)) {
        return date("H:i", strtotime($time));
    } else {
        return date("H:i");
    }
}
/* function used to display time In AI module because time picker is changed 24 hours in AI only */
function configDateTimeFormatAi($date) {    
    $dateInfo = 'd/m/Y H:i';
    //return date('m/d/Y', strtotime($date));
    if (!empty($date)) {
        return date($dateInfo, strtotime($date));
    } else {
        return date($dateInfo);
    }
}
/* this function used to store time in DB */
function dbtimeformat($time = '') {
    if (!empty($time)) {
        return date("H:i:s", strtotime($time));
    } else {
        return '';
//        return date("H:i:s");
    }
}
/* this function used to store date in DB */
function dateformat($date = '') {
    if (!empty($date)) {
        if (strpos($date, '/') !== false) {
           $date= str_replace("/","-",$date);
        }
        return date("Y-m-d", strtotime($date));
    } else {
        return '';
//        return date("Y-m-d");
    }
}
/*
  @Author : Ritesh Rana
  @Desc   : Get User Detail As per User ID
  @Input  : User ID
  @Output : User Information
  @Date   : 21/04/2016
 */

function getUserDetail($login_id) {
    $CI = & get_instance();
    $table = LOGIN . ' as lgn';
    $fields = array("lgn.firstname, lgn.lastname, lgn.email, lgn.address");
    $where = array('lgn.status' => '1', 'lgn.is_delete' => '0', 'lgn.login_id' => $login_id);
    $userArray = $CI->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $userArray;
}

/*
  @Author : Ritesh Rana
  @Desc   : Get User Detail As per User ID
  @Input  : User ID
  @Output : User Information
  @Date   : 11/03/2019
 */
function getUserDetailUseINAAI($incidentId,$yp_id,$process_id) {
    $CI = & get_instance();
    $table = AAI_REPORT_COMPILER . ' as arc';
    $where_report_com = array('incident_id' => $incidentId,'process_id'=>$process_id);
    $join_tables_rep          = array(LOGIN . ' as l' => 'l.login_id = arc.report_compiler_id');
    $userArray = $CI->common_model->get_records($table, array("arc.*,CONCAT(`firstname`,' ', `lastname`) as name"), $join_tables_rep, 'left', $where_report_com); 
      //echo $this->db->last_query();exit;
    return $userArray;
}

function getUserDetailUseINAAI_old($incidentId,$reference_number,$yp_id,$process_id) {
    $CI = & get_instance();
    $table = AAI_REPORT_COMPILER . ' as arc';
    $where_report_com = array('incident_id' => $incidentId,'reference_number' => $reference_number,'process_id'=>$process_id);
    $join_tables_rep          = array(LOGIN . ' as l' => 'l.login_id = arc.report_compiler_id');
    $userArray = $CI->common_model->get_records($table, array("arc.*,CONCAT(`firstname`,' ', `lastname`) as name"), $join_tables_rep, 'left', $where_report_com); 
      //echo $this->db->last_query();exit;
    return $userArray;
}

function getUserDetailAAiList($list_main_incident_id) {
    $CI = & get_instance();
    $table = AAI_REPORT_COMPILER . ' as arc';
    $where_report_com = array('list_main_incident_id' => $list_main_incident_id);
    $join_tables_rep          = array(LOGIN . ' as l' => 'l.login_id = arc.report_compiler_id');
    $userArray = $CI->common_model->get_records($table, array("arc.aai_report_compiler_id,CONCAT(`firstname`,' ', `lastname`) as name"), $join_tables_rep, 'left', $where_report_com); 

    $report_compiler = array();
    foreach ($userArray as $value) {
      $report_compiler[] = $value['name'];    
    }
   $report_compiler_data = implode(',', $report_compiler);
      //echo $this->db->last_query();exit;
    return $report_compiler_data;
}


function checkUserDetail($incidentId,$reference_number,$yp_id,$process_id,$user_id) {
    $CI = & get_instance();
    $table = AAI_REPORT_COMPILER . ' as arc';
    $where_report_com = array('incident_id' => $incidentId,'reference_number' => $reference_number,'process_id'=>$process_id,'yp_id' => $yp_id,'report_compiler_id'=>$user_id);
    $join_tables_rep          = array(LOGIN . ' as l' => 'l.login_id = arc.report_compiler_id');
    $check_report_com_data = $CI->common_model->get_records($table, array("arc.*,CONCAT(`firstname`,' ', `lastname`) as name"), $join_tables_rep, 'left', $where_report_com); 
    return $check_report_com_data;
}

 

/*
  @Author : Ritesh Rana
  @Desc   : Get User Detail As per User ID
  @Input  : User ID
  @Output : User Information
  @Date   : 20/09/2018
 */

function getUserData($login_id) {
    $CI = & get_instance();
    $match = array("login_id" => $login_id);
    $fields = array("CONCAT(`firstname`,' ', `lastname`) as create_name");
    $logindetail = $CI->common_model->get_records(LOGIN, $fields, '', '', $match);
    return $logindetail;
}

/*
  @Author : Ritesh Rana
  @Desc   : Get Random String as you want
  @Input  :
  @param  : type of random string.basic,alpha,alunum,numeric,nozero,unique,md5,encrypt and sha1
  @Output : string
  @Date   : 21/01/2018
 */

function random_string($type = 'alnum', $len = 8) {
    switch ($type) {
        case 'basic' : return mt_rand();
            break;
        case 'alnum' :
        case 'numeric' :
        case 'nozero' :
        case 'alpha' :

            switch ($type) {
                case 'alpha' : $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    break;
                case 'alnum' : $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    break;
                case 'numeric' : $pool = '0123456789';
                    break;
                case 'nozero' : $pool = '123456789';
                    break;
            }

            $str = '';
            for ($i = 0; $i < $len; $i++) {
                $str .= substr($pool, mt_rand(0, strlen($pool) - 1), 1);
            }
            return $str;
            break;
        case 'unique' :
        case 'md5' :

            return md5(uniqid(mt_rand()));
            break;
        case 'encrypt' :
        case 'sha1' :

            $CI = & get_instance();
            $CI->load->helper('security');

            return do_hash(uniqid(mt_rand(), TRUE), 'sha1');
            break;
    }
}

/*
  @Author : Ritesh Rana
  @Desc   : Get User Type from Role Master
  @Input  :
  @Output :
  @Date   : 04/04/2017
 */

function getUserType($role_id = null) {
    $ci = & get_instance();
    $table = ROLE_MASTER . ' as rm';
    $fields = array("rm.role_id, rm.role_name");
    if($role_id == '1'){
        $where = array('rm.status' => 1, 'rm.is_delete' => 0);
    }else{
        $where = array('rm.status' => 1, 'rm.is_delete' => 0,'rm.parent_role' => $role_id );
    }
    
    $where_not_in = array('rm.role_id' => $role_id );
    // $where = array('rm.is_delete' => '0');
    $data['role_option'] = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where,'','','','',$where_not_in);

    return $data['role_option'];
}

/*
  @Author : Ritesh Rana
  @Desc   : Get User Type from Role Master
  @Input  :
  @Output :
  @Date   : 04/04/2017
 */

function getUserTypeList() {

    $ci = & get_instance();
    $ci->db->select('*')->from(ROLE_MASTER);
    $ci->db->where('`role_id` NOT IN (SELECT `role_id` FROM `nfc_aauth_perm_to_group`)', NULL, FALSE);
    $ci->db->where('is_delete = 0');
    $query = $ci->db->get();
    $data['role_option'] = $query->result_array();
    return $data['role_option'];
}

/*
  @Author : Ritesh Rana
  @Desc   : Get User Type from Role Master
  @Input  :
  @Output :
  @Date   : 04/04/2017
 */

function getUserTypeAssign() {

    $ci = & get_instance();
    $ci->db->select('*')->from(ROLE_MASTER);
    $ci->db->where('`role_id` NOT IN (SELECT `role_id` FROM `nfc_aauth_perm_to_group`)', NULL, FALSE);
    $ci->db->where('is_delete = 0');
    $query = $ci->db->get();
    $data['role_option'] = $query->result_array();
    return $data['role_option'];
}

/*
  @Author : Ritesh Rana
  @Desc   :  Create lang function for get lang line
  @Input 	:  $line
  @Output	:  Display line
  @Date   : 04/04/2017
 */
if (!function_exists('lang')) {

    function lang($line, $id = '') {
        $CI = & get_instance();
        $line = $CI->lang->line($line);

        if ($id != '') {
            $line = '<label for="' . $id . '">' . $line . "</label>";
        }

        return $line;
    }

}

/*
  @Author : Ritesh Rana
  @Desc   : Get permission list from aauth_perms
  @Input  :
  @Output :
  @Date   : 04/04/2017
 */

function getPermsList() {

    $ci = & get_instance();
    $table = AAUTH_PERMS . ' as ap';
    $match = "";
    $fields = array("ap.id, ap.name");
    $data['permsList'] = $ci->common_model->get_records($table, $fields);
    return $data['permsList'];
}

/*
  @Author : Ritesh Rana
  @Desc   : Get Module list from Module Master
  @Input  :
  @Output :
  @Date   : 04/04/2017
 */

function getModuleList() {

    $ci = & get_instance();
    $table = MODULE_MASTER . ' as mm';
    $match = "";
    $fields = array("mm.module_id, mm.component_name, mm.module_name, mm.module_unique_name, mm.status");
    $where = array('mm.status' => '1');
    $data['moduleList'] = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $data['moduleList'];
}

/*
  @Author : Ritesh Rana
  @Desc   : Get Module list from Module Master
  @Input  :
  @Output :
  @Date   : 04/04/2017
 */

function getCRMModuleList() {
    $ci = & get_instance();
    $table = MODULE_MASTER . ' as mm';
    $match = "";
    $fields = array("mm.module_id, mm.module_name, mm.module_unique_name, mm.status");
    $where = array('mm.status' => '1');
    $data['moduleList'] = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $data['moduleList'];
}

/*
  @Author : Ritesh Rana
  @Desc   : Get Module Status from Module Master
  @Input  :
  @Output :
  @Date   : 04/04/2017
 */

function getModuleStatus() {

    $ci = & get_instance();
    $table = MODULE_MASTER . ' as mm';
    $fields = array("mm.status");
    $data['module_option'] = $ci->common_model->get_records($table, $fields);

    return $data['module_option'];
}

/*
  @Author : Ritesh Rana
  @Desc   : Helperfunction for checkpermission
  @Input  : action name
  @Output : if has permission then return true else false
  @Date   : 04/04/2017
 */

function checkPermission($controller, $method) {
    $CI = & get_instance();

    $system_lang = $CI->common_model->get_lang();
    $CI->config->set_item('language', $system_lang);
    $CI->lang->load('label', $system_lang ? $system_lang : 'english');

    //$CI->loginpage_redirect();  //Function added by RJ for redirection

    if (!isset($CI->router)) { # Router is not loaded
        $CI->load->library('router');
    }
    if (!isset($CI->session)) { # Sessions are not loaded
        $CI->load->library('session');
        $CI->load->library('database');
    }
    $dbPermArray = $resultData = $permArrMaster = $validateArr = array();
    $flag = 0;
    //$class = $CI->router->fetch_class();
    $class = $controller;
    // $method = $CI->router->fetch_method();
    if ($CI->session->has_userdata('LOGGED_IN')) {
        $session = $CI->session->userdata('LOGGED_IN');
        $CI->db->select('module_unique_name,controller_name,name,MM.component_name');
        $CI->db->from('aauth_perm_to_group as APG');
        $CI->db->join('module_master as MM', 'MM.module_id=APG.module_id');
        $CI->db->join('aauth_perms as AP', 'AP.id=APG.perm_id');
        $CI->db->where('role_id', $session['ROLE_TYPE']);
        $CI->db->where('controller_name', $class);
        $resultData = $CI->db->get()->result_array();
        $configPerms = $CI->load->config('acl');
        $newArr = array();
        $permsArray = $CI->config->item($class);

        if (count($resultData) > 0) {
            $dbPermArray = array_map(function ($obj) {
                return $obj['name'];
            }, $resultData);

            foreach ($dbPermArray as $prmObj) {
                if (array_key_exists($prmObj, $permsArray)) {
                    $permArrMaster[$prmObj] = $permsArray[$prmObj];
                }
            }
            if (array_key_exists($method, $permArrMaster)) {
                return true;
            } else {
                return false;
            }
        }
    }    
}
/*
      @Author : Dhara Bhalala
      @Desc   : Common create dir Function
      @Date   : 28/09/2018
*/
function createDirectory($filePath = array()) {
    if (isset($filePath) && !empty($filePath)) {
        foreach ($filePath as $path) {
            if (!is_dir($path))
                mkdir($path, 0777, TRUE);
        }
    }
}

/*
      @Author : Dhara Bhalala
      @Desc   : Common file rename Function
      @Date   : 28/09/2018
*/
function filterFileName($fileName) {
    $newstr = preg_replace('/[^a-zA-Z0-9\-\.\']/', '_', $fileName[0]);
    $fileName = str_replace("'", '', $newstr);
    return $fileName;       
}

/*
      @Author : Dhara Bhalala
      @Desc   : Common File display Function
      @Date   : 28/09/2018
*/
function getFileView($fileArray = array()){
    $CI = & get_instance();
    $html = $class = $deleteFileHidden = '';
    if(isset($fileArray['fileArray']) && !empty($fileArray['fileArray'])){
        $filePathMain = $fileArray['filePathMain'];
        $filePathThumb = $fileArray['filePathThumb'];
        if(isset($fileArray['class']) && $fileArray['class'] !== '')
            $class = $fileArray['class'];
        if(isset($fileArray['deleteFileHidden']) && $fileArray['deleteFileHidden'] !== '')
            $deleteFileHidden = $fileArray['deleteFileHidden'];
        $files = explode(',', $fileArray['fileArray']);

        foreach ($files as $img) {
            if($img !== ''){
                $fileBasePath = str_replace($CI->config->item('base_url').'/', '', $filePathMain . '/' . $img);
                if (file_exists($fileBasePath)) {
                    $html .= '<div class="col-sm-1 image-property">';
                    $html .= '<a class="' . $class . '" href="' . base_url('CommonController/downloadFile/?file_name=' . urlencode(base64_encode($filePathMain . '/' . $img))) . '">';

                    if (@is_array(getimagesize($filePathMain . '/' . $img))) {
                        $html .= '<img alt="'.$img.'" title="'.$img.'" width="100" height="100" src="' . $filePathThumb . '/' . $img . '">';
                    } else {
                        $html .= '<img alt="'.$img.'" title="'.$img.'" width="100" height="100" src="' . base_url(FILE_ICON_PATH) . '">';
                    }
                    $html .= '</a>';
                    if ($deleteFileHidden !== '')
                        $html .= '<span class="astrick delete_img" onclick="delete_img(this,\'' . $img . '\',\'' . $deleteFileHidden . '\')">X</span>';
                    $html .= '</div>';
                }
            }            
        }
    }    
    return $html;    
}


/*
      @Author : Dhara Bhalala
      @Desc   : Common File display Function
      @Date   : 28/09/2018
*/
function getpdfFileView($fileArray = array()){
    $CI = & get_instance();
    $html = $class = $deleteFileHidden = '';
    if(isset($fileArray['fileArray']) && !empty($fileArray['fileArray'])){
        $filePathMain = $fileArray['filePathMain'];
        $filePathThumb = $fileArray['filePathThumb'];
        if(isset($fileArray['class']) && $fileArray['class'] !== '')
            $class = $fileArray['class'];
        if(isset($fileArray['deleteFileHidden']) && $fileArray['deleteFileHidden'] !== '')
            $deleteFileHidden = $fileArray['deleteFileHidden'];
        $files = explode(',', $fileArray['fileArray']);

        foreach ($files as $img) {
            if($img !== ''){
                $fileBasePath = str_replace($CI->config->item('base_url').'/', '', $filePathMain . '/' . $img);
                if (file_exists($fileBasePath)) {
                    $html .= '<a class="' . $class . '" href="' . base_url('CommonController/downloadFile/?file_name=' . urlencode(base64_encode($filePathMain . '/' . $img))) . '">';

                    if (@is_array(getimagesize($filePathMain . '/' . $img))) {
                        $html .= '<img alt="'.$img.'" title="'.$img.'" width="100" height="100" src="' . $filePathThumb . '/' . $img . '">';
                    } else {
                        $html .= '<img alt="'.$img.'" title="'.$img.'" width="100" height="100" src="' . base_url(FILE_ICON_PATH) . '">';
                    }
                    $html .= '</a>';
                  
                    
                }
            }            
        }
    }    
    return $html;    
}


/*
      @Author : Rana Ritesh
      @Desc   : Common File display Function
      @Date   : 08/02/2019
*/
function getFileViewNotDelete ($fileArray = array()){
    $CI = & get_instance();
    $html = $class = $deleteFileHidden = '';
    if(isset($fileArray['fileArray']) && !empty($fileArray['fileArray'])){
        $filePathMain = $fileArray['filePathMain'];
        $filePathThumb = $fileArray['filePathThumb'];
        if(isset($fileArray['class']) && $fileArray['class'] !== '')
            $class = $fileArray['class'];
        if(isset($fileArray['deleteFileHidden']) && $fileArray['deleteFileHidden'] !== '')
            $deleteFileHidden = $fileArray['deleteFileHidden'];
        $files = explode(',', $fileArray['fileArray']);

        foreach ($files as $img) {
            if($img !== ''){
                $fileBasePath = str_replace($CI->config->item('base_url').'/', '', $filePathMain . '/' . $img);
                if (file_exists($fileBasePath)) {
                    $html .= '<div class="col-sm-1 image-property">';
                    $html .= '<a class="' . $class . '" href="' . base_url('CommonController/downloadFile/?file_name=' . urlencode(base64_encode($filePathMain . '/' . $img))) . '">';

                    if (@is_array(getimagesize($filePathMain . '/' . $img))) {
                        $html .= '<img alt="'.$img.'" title="'.$img.'" width="100" height="100" src="' . $filePathThumb . '/' . $img . '">';
                    } else {
                        $html .= '<img alt="'.$img.'" title="'.$img.'" width="100" height="100" src="' . base_url(FILE_ICON_PATH) . '">';
                    }
                    $html .= '</a>';
                    if ($deleteFileHidden !== '')
                       // $html .= '<span class="astrick delete_img" onclick="delete_img(this,\'' . $img . '\',\'' . $deleteFileHidden . '\')">X</span>';
                    $html .= '</div>';
                }
            }            
        }
    }    
    return $html;    
}



/*
      @Author : Ritesh Rana
      @Desc   : Common File display Function
      @Date   : 16/01/2019
*/
function getFileViewPrint($fileArray = array()){
    $CI = & get_instance();
    $html = $class = $deleteFileHidden = '';
    if(isset($fileArray['fileArray']) && !empty($fileArray['fileArray'])){
        $filePathMain = $fileArray['filePathMain'];
        $filePathThumb = $fileArray['filePathThumb'];
        if(isset($fileArray['class']) && $fileArray['class'] !== '')
            $class = $fileArray['class'];
        if(isset($fileArray['deleteFileHidden']) && $fileArray['deleteFileHidden'] !== '')
            $deleteFileHidden = $fileArray['deleteFileHidden'];
        $files = explode(',', $fileArray['fileArray']);

        foreach ($files as $img) {
            if($img !== ''){
                $fileBasePath = str_replace($CI->config->item('base_url').'/', '', $filePathMain . '/' . $img);
                if (file_exists($fileBasePath)) {
                    $html .= '<div class="col-sm-1 image-property">';
                    if (@is_array(getimagesize($filePathMain . '/' . $img))) {
                        $html .= '<img alt="'.$img.'" title="'.$img.'" width="100" height="100" src="' . $filePathThumb . '/' . $img . '">';
                    } else {
                        $html .= '<img alt="'.$img.'" title="'.$img.'" width="100" height="100"  src="' . base_url(FILE_ICON_PATH) . '">';
                    }
                    $html .= '</div>';
                }
            }            
        }
    }    
    return $html;    
}


/*
      @Author : Ritesh Rana
      @Desc   : Common File display Function
      @Date   : 07/03/2019
*/
function getCptFileViewPdf($fileArray = array()){
    $CI = & get_instance();
    $html = $class = $deleteFileHidden = '';
    if(isset($fileArray['fileArray']) && !empty($fileArray['fileArray'])){
        $filePathMain = $fileArray['filePathMain'];
        $filePathThumb = $fileArray['filePathThumb'];
        if(isset($fileArray['class']) && $fileArray['class'] !== '')
            $class = $fileArray['class'];
        if(isset($fileArray['deleteFileHidden']) && $fileArray['deleteFileHidden'] !== '')
            $deleteFileHidden = $fileArray['deleteFileHidden'];
        $files = explode(',', $fileArray['fileArray']);

        foreach ($files as $img) {
            if($img !== ''){
                $fileBasePath = str_replace($CI->config->item('base_url').'/', '', $filePathMain . '/' . $img);
                if (file_exists($fileBasePath)) {
                    $html .= '<td style="font-size: 14px; margin:0;margin-bottom:10px;color:#000;text-align:left;height: auto;  padding: 4px 10px;" class="img-sp">';
                    if (@is_array(getimagesize($filePathMain . '/' . $img))) {
                        $html .= '<img alt="'.$img.'" title="'.$img.'" width="100" height="100" src="' . $filePathThumb . '/' . $img . '">';
                    } else {
                        $html .= '<img alt="'.$img.'" title="'.$img.'" width="100" height="100"  src="' . base_url(FILE_ICON_PATH) . '">';
                    }
                    $html .= '</td>';
                }
            }            
        }
    }    
    return $html;    
}


/*
      @Author : Ritesh Rana
      @Desc   : Common Upload Function
      @Input 	:
      @Output	:
      @Date   : 04/04/2017
     */
function uploadImage($input, $path, $redirect, $file_name = null, $file_ext_tolower = false, $encrypt_name = false, $remove_spaces = false, $detect_mime = true) {
    $CI = & get_instance();
    $files = $_FILES;
    //pr($files);exit;
    $FileDataArr = array();
    $config['upload_path'] = $path;
    $config['allowed_types'] = '*';
    $config['max_size'] = 204800;
    $config['min_size'] = 1;
//        $config['max_width'] = 1024;
//        $config['max_height'] = 768;
    $config['file_ext_tolower'] = $file_ext_tolower;
    $config['encrypt_name'] = $encrypt_name;
    $config['remove_spaces'] = $remove_spaces;
    $config['detect_mime'] = $detect_mime;
    if ($file_name != null) {
        $config['file_name'] = $file_name;
    }

    $tmpFile = count($_FILES[$input]['name']);
    if ($tmpFile > 0 && $_FILES[$input]['name'][0] != NULL) {
        for ($i = 0; $i < $tmpFile; $i++) {
            $imgname = strtolower(end((explode(".",$files[$input]['name'][$i]))));
            $fileName[0] = trim($files[$input]['name'][$i]);
            $_FILES[$input]['name'] = filterFileName($fileName);
            $_FILES[$input]['type'] = $files[$input]['type'][$i];
            $_FILES[$input]['tmp_name'] = $files[$input]['tmp_name'][$i];
            $_FILES[$input]['error'] = $files[$input]['error'][$i];
            $_FILES[$input]['size'] = $files[$input]['size'][$i];

            $content = file_get_contents($_FILES[$input]['tmp_name']);
            if (preg_match('/\<\?php/i', $content)) {
                $CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
                redirect($redirect);
                die;
            }
            $CI->load->library('upload', $config);
            if($_FILES[$input]['size'] > $config['min_size']){
              if ($CI->upload->do_upload($input)) {
                $FileDataArr[] = $CI->upload->data();
            } else {
                $CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . $CI->upload->display_errors() . "</div>");
                redirect($redirect);
                die;
            }
            }else{
              $CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>File size should be greater than 0kb</div>");
                redirect($redirect);
                die;
            }
            
        }
    }
    return $FileDataArr;
}

function uploadPhotoGallery($input, $path, $redirect, $file_name = null, $file_ext_tolower = false, $encrypt_name = false, $remove_spaces = false, $detect_mime = true) {
    $CI = & get_instance();
    $files = $_FILES;
    $FileDataArr = array();
    $config['upload_path'] = $path;
    $config['allowed_types'] = 'jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF';
    $config['max_size'] = 204800;
//        $config['max_width'] = 1024;
//        $config['max_height'] = 768;
    $config['file_ext_tolower'] = $file_ext_tolower;
    $config['encrypt_name'] = $encrypt_name;
    $config['remove_spaces'] = $remove_spaces;
    $config['detect_mime'] = $detect_mime;
    if ($file_name != null) {
        $config['file_name'] = $file_name;
    }

    $tmpFile = count($_FILES[$input]['name']);
    if ($tmpFile > 0 && $_FILES[$input]['name'][0] != NULL) {
        for ($i = 0; $i < $tmpFile; $i++) {
            $imgname = strtolower(end((explode(".",$files[$input]['name'][$i]))));
            $_FILES[$input]['name'] = trim($files[$input]['name'][$i]);
            $_FILES[$input]['type'] = $files[$input]['type'][$i];
            $_FILES[$input]['tmp_name'] = $files[$input]['tmp_name'][$i];
            $_FILES[$input]['error'] = $files[$input]['error'][$i];
            $_FILES[$input]['size'] = $files[$input]['size'][$i];
            $content = file_get_contents($_FILES[$input]['tmp_name']);
            if (preg_match('/\<\?php/i', $content)) {
                $CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
                redirect($redirect);
                die;
            }
            $CI->load->library('upload', $config);
            if ($CI->upload->do_upload($input)) {
                $FileDataArr[] = $CI->upload->data();
            } else {
                $CI->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . $CI->upload->display_errors() . "</div>");
                redirect($redirect);
                die;
            }
        }
    }
    return $FileDataArr;
}

 /*
      @Author : Ritesh Rana
      @Desc   : Common Upload Function
      @Input  :
      @Output :
      @Date   : 13/07/2017
     */

function do_resize($source, $destination, $filename) {
    $CI =& get_instance ();
    $CI->load->library ('image_lib');
    $CI->image_lib->clear ();
    if (!file_exists ($destination)) {
        mkdir ($destination, 0777, TRUE);
        chmod ($destination, 0777);
    }
    $source_path  = $source . '/' . $filename;
    $target_path  = $destination;
    $config_manip = array(
        'image_library'  => 'gd2',
        'source_image'   => $source_path,
        'new_image'      => $target_path,
        'maintain_ratio' => TRUE,
        'create_thumb'   => TRUE,
        'thumb_marker'   => '',
        'width'          => 150,
        'height'         => 150
    );
    $CI->image_lib->initialize ($config_manip);
    if (!$CI->image_lib->resize ()) {
        echo $CI->image_lib->display_errors ();
    }
    // clear //
    $CI->image_lib->clear ();
}
/*
  @Author : Ritesh Rana
  @Desc   : Get Email settings
  @Input  :
  @Output :
  @Date   : 04/04/2017
 */

function getMailConfig() {

    $CI = & get_instance();
    $dashWhere = "config_key='email_settings'";
    $defaultDashboard = $CI->common_model->get_records(CONFIG, array('value'), '', '', $dashWhere);
    $configData = (array) json_decode($defaultDashboard[0]['value']);
    return $configData;
}

/*
  @Author : Ritesh Rana
  @Desc   : Send mail with CI Helper
  @Input  :
  @Output :
  @Date   : 04/04/2017
 */

function send_mail($to, $subject, $message, $attach = NULL) {

    $CI = & get_instance();

    $configs = getMailConfig(); // Get Email configs from Email settigs page
    //$CI->load->library('parser');
    if (!empty($configs)) {
        $config['protocol'] = $configs['email_protocol'];
        $config['smtp_host'] = $configs['smtp_host']; //change this
        $config['smtp_port'] = $configs['smtp_port'];
        $config['smtp_user'] = $configs['smtp_user']; //change this
        $config['smtp_pass'] = $configs['smtp_pass']; //change this
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n"; //use double quotes to comply with RFC 8
        $CI->load->library('email', $config); // load email library
        $CI->email->from($configs['smtp_user'], "NFC Tracker");
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);
        if (isset($attach) && $attach != "") {
            $CI->email->attach($attach); // attach file /path/to/file1.png
        }

        return $CI->email->send();
    } else {

        $where = "config_key='email'";
        $fromEmail = $CI->common_model->get_records(CONFIG, array('value'), '', '', $where);
        if (isset($fromEmail[0]['value']) && !empty($fromEmail[0]['value'])) {
            $from_Email = $fromEmail[0]['value'];
        }
        $where1 = "config_key='project_name'";
        $projectName = $CI->common_model->get_records(CONFIG, array('value'), '', '', $where1);
        if (isset($projectName[0]['value']) && !empty($projectName[0]['value'])) {
            $project_Name = $projectName[0]['value'];
        }
        $CI->load->library('email');
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $CI->email->initialize($config);
        $config['mailtype'] = "html";
        $CI->email->initialize($config);
        $CI->email->set_newline("\r\n");
        $CI->email->from($from_Email, $project_Name);
        //$list = array('mehul.patel@c-metric.com');
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);
        if (isset($attach) && $attach != "") {
            $CI->email->attach($attach); // attach file /path/to/file1.png
        }
        return $CI->email->send();
    }


    // pr($data); exit;
}

/*
  @Author : Ritesh Rana
  @Desc   : Send mail with CI Helper
  @Input  : $attach = array(),
  $headers = array(),
  $configs_arr = array(),
  other inputs are strings
  @Output :
  @Date   : 4/03/2017
 */

function send_mail1($to, $subject, $message, $attach = '', $from_email = '', $from_name = '', $cc = '', $bcc = '', $headers = '', $configs_arr = '') {

    $CI = & get_instance();

    $configs = getMailConfig(); // Get Email configs from Email settigs page
    //$CI->load->library('parser');
    if (!empty($configs)) {
        $config['protocol'] = $configs['email_protocol'];
        $config['smtp_host'] = $configs['smtp_host']; //change this
        $config['smtp_port'] = $configs['smtp_port'];
        $config['smtp_user'] = $configs['smtp_user']; //change this
        $config['smtp_pass'] = $configs['smtp_pass']; //change this
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n"; //use double quotes to comply with RFC 8
        //Add other config
        if (!empty($configs_arr)) {
            foreach ($configs_arr as $key => $value) {
                $config[$key] = $value;
            }
        }
        $CI->load->library('email', $config); // load email library
        //Add header
        if (!empty($headers)) {
            foreach ($headers as $key => $value) {
                $CI->email->set_header($key, $value);
            }
            //$this->email->attach("uploads/attachment_temp/".$data['attachment']);
        }
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);
        if (!empty($from_email)) {
            $from_name = !empty($from_name) ? $from_name : 'CMS TEST';
            $CI->email->from($from_email, $from_name);
        } else {
            $CI->email->from($configs['smtp_user'], "CMS TEST");
        }
        $CI->email->to($to);
        if (!empty($cc)) {
            $CI->email->cc($cc);
        }
        if (!empty($bcc)) {
            $CI->email->bcc($bcc);
        }


        if (!empty($attach)) {
            foreach ($attach as $row_attachment) {
                $CI->email->attach($row_attachment);
            }
            //$this->email->attach("uploads/attachment_temp/".$data['attachment']);
        }

        return $CI->email->send();
    } else {

        $where = "config_key='email'";
        $fromEmail = $CI->common_model->get_records(CONFIG, array('value'), '', '', $where);
        if (isset($fromEmail[0]['value']) && !empty($fromEmail[0]['value'])) {
            $from_email_conf = $fromEmail[0]['value'];
        }
        $where1 = "config_key='project_name'";
        $projectName = $CI->common_model->get_records(CONFIG, array('value'), '', '', $where1);
        if (isset($projectName[0]['value']) && !empty($projectName[0]['value'])) {
            $project_Name = $projectName[0]['value'];
        }
        $CI->load->library('email');
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = "html";
        //Add other config
        if (!empty($configs_arr)) {
            foreach ($configs_arr as $key => $value) {
                $config[$key] = $value;
            }
        }
        //$CI->load->library('email', $config); // load email library
        $CI->email->initialize($config);
        //Add header
        if (!empty($headers)) {
            foreach ($headers as $key => $value) {
                $CI->email->set_header($key, $value);
            }
            //$this->email->attach("uploads/attachment_temp/".$data['attachment']);
        }
        $CI->email->set_newline("\r\n");

        //$list = array('mehul.patel@c-metric.com');
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);
        if (!empty($from_email)) {
            $from_name = !empty($from_name) ? $from_name : 'CMS TEST';
            $CI->email->from($from_email, $from_name);
        } else {
            $CI->email->from($from_email_conf, $project_Name);
        }
        $CI->email->to($to);
        if (!empty($cc)) {
            $CI->email->cc($cc);
        }
        if (!empty($bcc)) {
            $CI->email->bcc($bcc);
        }


        if (!empty($attach)) {
            foreach ($attach as $row_attachment) {
                $CI->email->attach($row_attachment);
            }
            //$this->email->attach("uploads/attachment_temp/".$data['attachment']);
        }
        return $CI->email->send();
//    if($CI->email->send())
//    { 
//      return true;
//    }
//    else
//    {
//      return false;
//    }
//    
    }


    // pr($data); exit;
}

/*
  @Author : Ritesh Rana
  @Desc   :Generates Token on Form
  @Input  :
  @Output :
  @Date   : 14/03/2017
 */

function createFormToken() {
    $CI = & get_instance();
    $CI->load->library('session'); // load Session library
    $secret = md5(uniqid(rand(), true));
    $CI->session->set_userdata('FORM_SECRET', $secret);
    return $secret;
}

function generateFormToken() {
    $CI = & get_instance();
    $CI->load->library('session'); // load Session library
    $secret = md5(uniqid(rand(), true));
    $CI->session->set_userdata('FORM_SECRET_DATA', $secret);
    return $secret;
}

/*
  @Author : Ritesh Rana
  @Desc   :validates Token on Form
  @Input  :
  @Output :
  @Date   : 14/03/2017
 */

function validateFormSecret() {
    $CI = & get_instance();
    $CI->load->library('session'); // load Session library
    $frmSession = $CI->session->userdata('FORM_SECRET');
    $form_secret = isset($_POST["form_secret"]) ? $_POST["form_secret"] : '';

    if (isset($frmSession)) {
        if (strcasecmp($form_secret, $frmSession) === 0) {
            /* Put your form submission code here after processing the form data, unset the secret key from the session */
            $CI->session->unset_userdata('FORM_SECRET', '');
            return true;
        } else {
            //Invalid secret key
            return false;
        }
    } else {
        //Secret key missing
        return false;
    }
}

function validateFormSecretsData() {
    $CI = & get_instance();
    $CI->load->library('session'); // load Session library
    $frmSession = $CI->session->userdata('FORM_SECRET');
    $form_secret = isset($_POST["form_secret"]) ? $_POST["form_secret"] : '';

    if (isset($frmSession)) {
        if (strcasecmp($form_secret, $frmSession) === 0) {
            /* Put your form submission code here after processing the form data, unset the secret key from the session */
            $CI->session->unset_userdata('FORM_SECRET', '');
            return true;
        } else {
            //Invalid secret key
            return false;
        }
    } else {
        //Secret key missing
        return false;
    }
}

/*
  @Author : Sanket jayani
  @Desc   :for getting document image name
  @Input  :
  @Output :
  @Date   : 22/02/2016
 */

function getImgFromFileExtension($file_extension) {
    if ($file_extension == '') {
        $file_extension = 'txt';
    }

    $document_array = array('jpg' => 'jpg-64.png', 'csv' => 'xls-64.png',
        'aac' => 'aac-64.png', 'aib' => 'aib-64.png',
        'avi' => 'avi-64.png', 'docx' => 'docs-64.png',
        'flac' => 'flac-64.png', 'gif' => 'gif-64.png',
        'html' => 'html-64.png', 'js' => 'js-64.png',
        'movs' => 'movs-64.png', 'mp4' => 'mp3-64.png',
        'mp4' => 'mp4-64.png', 'pdf' => 'pdf-64.png', 'default' => 'file-64.png',
        'png' => 'png-64.png', 'psd' => 'psd-64.png',
        'txt' => 'txt-64.png', 'xlsx' => 'xlsx-64.png', 'xls' => 'xls-64.png', 'ppt' => 'ppt-64.png', 'pptx' => 'pptx-64.png');

    if (array_key_exists(strtolower($file_extension), $document_array)) {
        return $document_array[strtolower($file_extension)];
    } else {

        return $document_array['default'];
    }
}

function getCountryName($country_id) {

    $CI = & get_instance();
    $table = COUNTRIES . ' as c';
    $match = "country_id = " . $country_id;
    $fields = array("c.country_name");
    $countryName = $CI->common_model->get_records($table, $fields, '', '', $match);
    return $countryName;
}

function getCategoryName($cat_id) {

    $CI = & get_instance();
    $table = CATEGORY . ' as c';
    $match = "cat_id = " . $cat_id;
    $fields = array("c.cat_name");
    $categoryName = $CI->common_model->get_records($table, $fields, '', '', $match);
    return $categoryName;
}

function getRoleName($role_id) {
    
    //echo "ROLE ID :".$role_id;
    $CI = & get_instance();
    $table = ROLE_MASTER . ' as rm';
    $match = "rm.role_id = " . $role_id;
    $fields = array("rm.role_name");
    $roleName = $CI->common_model->get_records($table, $fields, '', '', $match);
    //print_r($roleName);
    return $roleName;
}

function getSelectedModule($id) {
    $CI = & get_instance();
    $table3 = LOGIN . ' as l';
    $where3 = array("l.login_id " => $id);
    $fields3 = array("l.role_id");
    $getCountofSupportuser1 = $CI->common_model->get_records($table3, $fields3, '', '', '', '', '', '', '', '', '', $where3);
    return $getCountofSupportuser1;
}

function convertTimeTo12HourFormat($time) {
    return date("g:i A", strtotime($time));
}

// get Languages from blzdsk_language_master table
function getLanguages() {
    $CI = & get_instance();
    $table = LANGUAGE_MASTER . ' as lm';
    $fields = array("lm.language_id,lm.language_name,lm.name");
    $order_by = 'lm.language_name';
    $order = 'ASC';
    $language_data = $CI->common_model->get_records($table, $fields, '', '', '', '', '', '', $order_by, $order);
    return $language_data;
}

// Get User List from Role 
function getUserList($roleID) {
    $CI = & get_instance();
    $table3 = LOGIN . ' as l';
    $where3 = array("l.role_id " => $roleID, "l.is_delete" => "0");
    $fields3 = array("l.login_id");
    $getCountofSupportuser1 = $CI->common_model->get_records($table3, $fields3, '', '', '', '', '', '', '', '', '', $where3);

    return $getCountofSupportuser1;
}

function getSubcategory($category_id) {
    $CI = & get_instance();
    //print_r($roleName);
    $table = SUBCATEGORY . ' as sct';
    $match = "sct.status = 'active' AND sct.is_deleted = 0 AND sct.cat_id = $category_id";
    $fields = array("sct.subcat_id, sct.subcat_name");
    $subcategory = $CI->common_model->get_records($table, $fields, '', '', $match);
    return $subcategory;
}

function showTotaldata($delivery_order_id) {
    $ci = & get_instance();
    $table = DELIVERY_ITEM_LIST . ' as dit';
    $where = array("dit.delivery_order_id" => $delivery_order_id);
    $fields = array("dit.quantity ,dit.price,dit.quantity * dit.price as 'total'");
    $total_info_id = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    $total_val = "";
    foreach ($total_info_id as $total_data) {
        $total_val += $total_data['total'];
    }
    return $total_val;
}

/*
  @Author : Ritesh Rana
  @Desc   :  Create Dropdown
  @Input 	:  $name ,array $options,$selected
  @Output	:  Dropdown create
  @Date   : 10/04/2017
 */

function dropdown($name, array $options, $selected = null, $readonly = null, $first_option = null, $second_option = null) {
    //pr($first_option);die();
    /*     * * begin the select ** */
    $dropdown = '<select class="form-control" name="' . $name . '" id="' . $name . '" ' . $readonly . '>' . "\n";

    $selected = $selected;
    /*     * * loop over the options ** */
    if ($first_option != '') {
        $dropdown .= '<option value="">' . $first_option . '</option>' . "\n";
    }
    if ($second_option != '') {
        $select = $selected == '0' ? ' selected' : null;
        $dropdown .= '<option value="0" ' . $select . '>' . $second_option . '</option>' . "\n";
    }
    foreach ($options as $key => $option) {
        /*         * * assign a selected value ** */
        $select = $selected == $key ? ' selected' : null;

        /*         * * add each option to the dropdown ** */

        $dropdown .= '<option value="' . $key . '"' . $select . '>' . $option . '</option>' . "\n";
    }

    /*     * * close the select ** */
    $dropdown .= '</select>' . "\n";

    /*     * * and return the completed dropdown ** */
    return $dropdown;
}



function check_admin_login()
{
    $CI = & get_instance();  //get instance, access the CI superobject
    $adminLogin = $CI->session->userdata('nfc_admin_session');
    (!empty($adminLogin['admin_id']))?'':redirect('Admin');
}

/*
  @Author : Ritesh Rana
  @Desc   : random string generate for password
  @Input  : Length
  @Output : random string
  @Date   : 11th May 2017
 */
function rand_string( $length ) {

  $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
  return substr(str_shuffle($chars),0,$length);
} 

function checkRolePermission()
{
  $CI = & get_instance();
  $admin_session = $CI->session->userdata('nfc_admin_session');
  
  $table = ROLE_MASTER.' as rm';
  $match = "rm.role_id = '" . $admin_session['admin_type'] . "'";
  $role_result = $CI->common_model->get_records($table, array("rm.role_id,rm.role_name"), '', '', $match);
  if(!empty($role_result[0]['role_name']) && ($role_result[0]['role_name'] == 'SuperAdmin' || $role_result[0]['role_name'] == 'Super-Admin')){
    return true;
  }
  else
  {
    return false;
  }
}
function checkAdminPermission($controller, $method) {
    $CI = & get_instance();

    $system_lang = $CI->common_model->get_lang();
    $CI->config->set_item('language', $system_lang);
    $CI->lang->load('label', $system_lang ? $system_lang : 'english');

    //$CI->loginpage_redirect();  //Function added by RJ for redirection

    if (!isset($CI->router)) { # Router is not loaded
        $CI->load->library('router');
    }
    if (!isset($CI->session)) { # Sessions are not loaded
        $CI->load->library('session');
        $CI->load->library('database');
    }
    $dbPermArray = $resultData = $permArrMaster = $validateArr = array();
    $flag = 0;
    //$class = $CI->router->fetch_class();
    $class = $controller;
    // $method = $CI->router->fetch_method();
    if ($CI->session->has_userdata('nfc_admin_session')) {
        $session = $CI->session->userdata('nfc_admin_session');
        $CI->db->select('module_unique_name,controller_name,name,MM.component_name');
        $CI->db->from('aauth_perm_to_group as APG');
        $CI->db->join('module_master as MM', 'MM.module_id=APG.module_id');
        $CI->db->join('aauth_perms as AP', 'AP.id=APG.perm_id');
        $CI->db->where('role_id', $session['admin_type']);
        $CI->db->where('controller_name', $class);
        $resultData = $CI->db->get()->result_array();

        $configPerms = $CI->load->config('acl');
        $newArr = array();
        $permsArray = $CI->config->item($class);

        if (count($resultData) > 0) {
            $dbPermArray = array_map(function ($obj) {
                return $obj['name'];
            }, $resultData);

            foreach ($dbPermArray as $prmObj) {
                if (array_key_exists($prmObj, $permsArray)) {
                    $permArrMaster[$prmObj] = $permsArray[$prmObj];
                }
            }
            if (array_key_exists($method, $permArrMaster)) {
                /*
                 * custom code for validating project status condition whether project is completed or not
                 */
                if ($resultData[0]['component_name'] == 'PM' && $method != 'view' && $class != 'Projectmanagement') {

                    if ($CI->session->has_userdata('PROJECT_STATUS') && $CI->session->userdata('PROJECT_STATUS') == 3) {
                        return false;
                    }
                }
                return true;
            } else {
                return false;
            }
        }
    }
    /*
      @Author : Ritesh Rana
      @Desc   : Common Upload Function
      @Input 	:
      @Output	:
      @Date   : 04/04/2017
     */
}

function checkFormBuilderData($table) {
    $ci = & get_instance();
    //$table = $table;
    //$match = "";
    $fields = array("*");
    $dateInfo = $ci->common_model->get_records($table, $fields, '', '', '');
    if (!empty($dateInfo)) {
        return $dateInfo;
    } else {
        return FALSE;
    }
}
/*
      @Author : Niral Patel
      @Desc   : Check string is json
      @Input  :
      @Output :
      @Date   : 22/08/2017
     */
    function is_json($string) {
      return !empty($string) && is_string($string) && is_array(json_decode($string, true)) && json_last_error() == 0;
    }
     /*
      @Author : Niral Patel
      @Desc   : Insert module activities
      @Input    :
      @Output   :
      @Date   : 11/10/2017
     */

    function log_activity($activity) {
        $ci = & get_instance();
        $activity['activity_date'] = datetimeformat();
        $ci->common_model->insert(ACTIVITY_LOG, $activity);
      }

     function individual_meeting_log($activity) {
        $ci = & get_instance();
        $activity['modified_date'] = datetimeformat();
        $ci->common_model->insert(NFC_INDIVIDUAL_MEETING_LOG, $activity);
      }

function do_activity_log($activity) {
    $ci = & get_instance();
    $table = DO_LOG . ' as do';
    $where = 'do.do_id ="'.$activity['do_id'].'" AND do.yp_id = "'.$activity['yp_id'].'" AND do.module_field_name = "'.$activity['module_field_name'].'"';
    $fields = array("do.*");
    $do_data = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
  if(!empty($do_data)){
      $where = array('do_log_id' => $do_data[0]['do_log_id'],'do_id' => $do_data[0]['do_id'],'yp_id' => $do_data[0]['yp_id']);
      $data['update_data'] = $ci->common_model->update(DO_LOG, $activity, $where);
  }else{
      $activity['modified_date'] = datetimeformat();
      $ci->common_model->insert(DO_LOG, $activity);
  }
}

function updatedUser($do_id,$yp_id,$module_field_name) {
    $ci = & get_instance();
    $table = DO_LOG . ' as do';
    $where = 'do.do_id ="'.$do_id.'" AND do.yp_id = "'.$yp_id.'" AND do.module_field_name = "'.$module_field_name.'"';
    $fields = array("do.*,CONCAT(l.firstname,' ', l.lastname) as create_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.user_id');
    $do_data = $ci->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
    if(!empty($do_data)){
      $old_date_timestamp = strtotime($do_data[0]['modified_date']);
      $new_date = date('d/m/Y H:i:s', $old_date_timestamp);
    return $do_data[0]['create_name'] . ':' . $new_date;
    }else {
      return false;
    }
}
      
      
/*
  @Author : Mehul Patel 
  @Desc   : Get User Name As per User ID
  @Input  : User ID
  @Output : User Information
  @Date   : 06/03/2018
 */
function getUserName($login_id) {
    $CI = & get_instance();
    $table = LOGIN . ' as lgn';
    $fields = array("lgn.firstname, lgn.lastname");
    $where = array('lgn.status' => '1', 'lgn.is_delete' => '0', 'lgn.login_id' => $login_id);
    $userArray = $CI->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    $name = "";
    if(isset($userArray) && !empty($userArray)){
        $name = $userArray[0]['firstname']." ".$userArray[0]['lastname'];
    }else{
        $name = "";
    }
    return $name;
}
/*
  @Author : Niral Patel 
  @Desc   : Get User Name As per User ID
  @Input  : User ID
  @Output : User Information
  @Date   : 15/05/2018
 */
function getUserNameWithNumber($login_id) {
    $CI = & get_instance();
    $table = LOGIN . ' as lgn';
    $fields = array("lgn.firstname, lgn.lastname,lgn.mobile_number");
    $where = array('lgn.status' => '1', 'lgn.is_delete' => '0', 'lgn.login_id' => $login_id);
    $userArray = $CI->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    $name = "";
    if(isset($userArray) && !empty($userArray)){
        $name = $userArray[0]['firstname']." ".$userArray[0]['lastname'] .' - '.$userArray[0]['mobile_number'];
    }else{
        $name = "";
    }
    return $name;
}



function getUserTimeOut($login_id) {
    $CI = & get_instance();
    $table = USER_SET_LOGOUT_TIME . ' as uslt';
    $match = "uslt.login_user_id = '" . $login_id . "'";
    $fields = array("uslt.milliseconds");
    $user_set_logout_time = $CI->common_model->get_records($table, $fields, '', '', $match);

    $set_logout_time = "";
    if(isset($user_set_logout_time) && !empty($user_set_logout_time)){
        $set_logout_time = $user_set_logout_time[0]['milliseconds'];
    }else{
        $set_logout_time = "";
    }
    return $set_logout_time;
}

function find_group_name($needle, array $haystack)
{
    foreach ($haystack as $key => $value) {
        if (false !== stripos($value, $needle)) {
            return $key;
        }
    }
    return false;
}


function insertGroupName($group_array) {
  $CI = & get_instance();
            if (!empty($group_array)) {
                $where_in = array("gn.group_name" => $group_array);
            } else {
                $where_in = array("gn.group_name" => '');
            }
            $table = GROUP_NAME . ' as gn';
            $where = array("gn.status" => "1");
            $fields = array("gn.group_name");
            $GroupNameData = $CI->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where, '', $where_in);

if(empty($GroupNameData)){
$value_data = base_url();
$app_name = '';
if (strpos($value_data, LOCALHOST) !== false) {
     $pos = find_group_name(DEVELOPMENT,$group_array);
     if(!empty($group_array[$pos])){
        $app_name = DEVELOPMENT;
     }else{
        $app_name = '';
     }
}else if (strpos($value_data, PREPRODTRACKER) !== false) {
    $pos = find_group_name(PREPRODUCTION,$group_array);
     if(!empty($group_array[$pos])){
        $app_name = PREPRODUCTION;
     }else{
        $app_name = '';
     }
}else if (strpos($value_data, TRACKER) !== false) {
    $pos = find_group_name(PRODUCTION,$group_array);
     if(!empty($group_array[$pos])){
        $app_name = PRODUCTION;
     }else{
        $app_name = '';
     }
}

switch ($app_name) {
    case DEVELOPMENT:
            if(!empty($group_array[$pos])){
                 $data = array(
                    'group_name' => $group_array[$pos],
                    'group_unique_name' => DEVELOPMENT,
                    'created_date' => datetimeformat(),
                    'updated_date' => datetimeformat(),
                    'status' => 1,
                );
                //Insert Record in Database
                $insert_id = $CI->common_model->insert(GROUP_NAME, $data);
            }
        break;
    case PREPRODUCTION:
         $pos = find_group_name(PREPRODUCTION,$group_array);
            if(!empty($group_array[$pos])){
                 $data = array(
                    'group_name' => $group_array[$pos],
                    'group_unique_name' => PREPRODUCTION,
                    'created_date' => datetimeformat(),
                    'updated_date' => datetimeformat(),
                    'status' => 1,
                );
                //Insert Record in Database
                $insert_id = $CI->common_model->insert(GROUP_NAME, $data);
            }
        break;
    case PRODUCTION:
         $pos = find_group_name(PRODUCTION,$group_array);
            if(!empty($group_array[$pos])){
                 $data = array(
                    'group_name' => $group_array[$pos],
                    'group_unique_name' => PRODUCTION,
                    'created_date' => datetimeformat(),
                    'updated_date' => datetimeformat(),
                    'status' => 1,
                );
                //Insert Record in Database
                $insert_id = $CI->common_model->insert(GROUP_NAME, $data);
            }
        break;
    default:
        $CI->session->unset_userdata('access_token');
        $CI->session->unset_userdata('token_type');
        $return_to = base_url('Masteradmin/error');
        $feedURL = "https://login.microsoftonline.com/".$CI->config->item('directory_identifier')."/oauth2/logout?post_logout_redirect_uri=". urlencode($return_to);
        redirect($feedURL);
}
}
    return true;
}

/*
  @Author : Ritesh rana
  @Desc   : Get care home name As per care home ID
  @Input  : care_home_id
  @Output : care home name
  @Date   : 14/09/2018
 */
function getCareHomeName($care_home_id) {
    $CI = & get_instance();
    $table = CARE_HOME . ' as ch';
    $fields = array("ch.care_home_name");
    //$where = array('ch.status' => 'active', 'ch.is_delete' => '0', 'ch.care_home_id' => $care_home_id);
    $where = array("ch.status" => "'active'", "ch.is_delete" => "'0'", "ch.care_home_id" => $care_home_id);

    $careArray = $CI->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    $name = "";
    if(isset($careArray) && !empty($careArray)){
        $name = $careArray[0]['care_home_name'];
    }else{
        $name = "";
    }
    return $name;
}

/*
  @Author : Ritesh rana
  @Desc   : Get care home name As per care home ID
  @Input  : care_home_id
  @Output : care home name
  @Date   : 04/03/2019
*/

function getCareHomeData($care_home_id) {
    $CI = & get_instance();
    $table = CARE_HOME . ' as ch';
    $fields = array("ch.*");
    //$where = array('ch.status' => 'active', 'ch.is_delete' => '0', 'ch.care_home_id' => $care_home_id);
    $where = array("ch.status" => "'active'", "ch.is_delete" => "'0'", "ch.care_home_id" => $care_home_id);

    $careArray = $CI->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $careArray[0];
}

/*
  @Author : Ritesh rana
  @Desc   : Get Cse Ans value 
  @Input  : record_id and cse_sub_que_id
  @Output : care home name
  @Date   : 25/09/2018
 */
function getCseAnsValue($record_id, $id) {

    $ci = & get_instance();
        $ci->db->select('ans');
        $ci->db->from(NFC_CSE_RECORD_ANS);

        $ci->db->where('sub_que_id', $id);
        $ci->db->where('record_id', $record_id);
        $ci->db->where('is_delete', 0);
        $query = $ci->db->get();
        
        if ($query->num_rows() > 0) {
            $row = $query->result_array();
            return $row;
        }
    }

    /*
  @Author : Ritesh rana
  @Desc   : Get emial id As per yp initials
  @Input  : email id
  @Output : email
  @Date   : 10/10/2018
 */

function YpInitialsWithEmail($yp_init,$email) {
     $email_id_data = explode("@", $email);
            if (strpos($email_id_data, 'newforestcare') !== false) {
                $data['fromMail_data']= $yp_init.' '.NFCTracker.'<'.$email.'>';
            }elseif(strpos($email_id_data, 'newforestschool') !== false) {
                $data['fromMail_data']= $yp_init.' '.NFSTracker.'<'.$email.'>';
            }else{
                $data['fromMail_data'] = $email;    
            }
            
    return $data['fromMail_data'];
}


function YpEmail($yp_id) {
//get YP information
    $ci = & get_instance();
        $match = array("yp_id"=>$yp_id);
        $fields = array("*");
        $YP_details = $ci->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

        $yp_initials = $YP_details[0]['yp_initials'];
        $yp_init = substr($yp_initials,0,3);
        $yp_fromMail = $yp_init.'-'.YP_EMAIL;
    return $yp_fromMail;
}


function getYpDetails($yp_id){
      $ci = & get_instance();
        $match = array("yp_id"=>$yp_id);
        $fields = array("*");
        $YP_details = $ci->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

  return $YP_details;         
}

 /*
  @Author : Ritesh rana
  @Desc   : yp details
  @Input  : ypid id
  @Output : email
  @Date   : 12/11/2018
 */
function YpDetails($yp_id,$fields){
      $ci = & get_instance();
        $match = array("yp_id"=>$yp_id);
        $YP_details = $ci->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
        
  return $YP_details;         
}

 /*
  @Author : Ritesh rana
  @Desc   : get social worker data
  @Input  : ypid id
  @Output : email
  @Date   : 12/11/2018
 */
function SocialWorkerData($yp_id){
      $ci = & get_instance();
      $table = SOCIAL_WORKER_DETAILS . ' as sw';
      $match = array("sw.yp_id" => $yp_id);
      $fields = array("sw.social_worker_id,sw.social_worker_firstname,sw.social_worker_surname");
      $social_worker_data = $ci->common_model->get_records($table, $fields, '', '', $match);
  return $social_worker_data;         
}

/*
  @Author : Ritesh rana
  @Desc   : ParentData
  @Input  : ypid id
  @Output : email
  @Date   : 12/11/2018
 */
  function ParentData($yp_id){
      $ci = & get_instance();
      $table = PARENT_CARER_INFORMATION . ' as pc';
      $match = array("pc.yp_id" => $yp_id,'pc.is_deleted' => 0);
      $fields = array("pc.parent_carer_id,pc.firstname,pc.surname");
      $parent_data = $ci->common_model->get_records($table, $fields, '', '', $match);
  return $parent_data;         
}

  /*
  @Author : Ritesh rana
  @Desc   : parent carer and social worker email list
  @Input  : ypid id
  @Output : email
  @Date   : 12/11/2018
 */
function ParentCarerAndSocialWorkerEmail($yp_id) {
//get YP information
    $ci = & get_instance();
         $group_by = 'email';
         $match = array("yp_id"=>$yp_id,"is_deleted"=>0);
          $fields = array("email_address as email,relationship");
          $contacts = $ci->common_model->get_records(PARENT_CARER_INFORMATION, $fields, '', '', $match,'','','','','',$group_by);
          
        $table = SOCIAL_WORKER_DETAILS . ' as pa';
        $match = "pa.yp_id = " . $yp_id;
        $fields = array("pa.email");
        $socialRecord = $ci->common_model->get_records($table, $fields, '', '', $match);

        $social_data = array();
        if(!empty($socialRecord)){
          foreach ($socialRecord as $value) {
            $a= array();
                $a['email'] = $value['email'];   
                $a['relationship'] = 'Social Worker'; 
                array_push($social_data,$a);  
          } 
        }
        $contacts_data = array_merge($contacts, $social_data);


    return $contacts_data;
}

/*
  @Author : Ritesh rana
  @Desc   : array_sort
  @Input  : array 
  @Output : email
  @Date   : 04/03/2019
 */
function array_sort($array, $on, $order=SORT_ASC,$isdate=false){

    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                      if($isdate){
                        $sortable_array[$k] = strtotime($v2);
                      }else
                      {
                        $sortable_array[$k] = $v2;
                      }
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
                break;
            case SORT_DESC:
                arsort($sortable_array);
                break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}


   /*
  @Author : Ritesh rana
  @Desc   : Get emial id As per yp initials
  @Input  : email id
  @Output : email
  @Date   : 10/10/2018
 */

function EmailFromName($yp_init,$email) {
     $email_id_data = explode("@", $email);
            if (strpos($email_id_data, 'newforestcare') !== false) {
                $data['fromMail_data']= $yp_init.' '.NFCTracker;
            }elseif(strpos($email_id_data, 'newforestschool') !== false) {
                $data['fromMail_data']= $yp_init.' '.NFSTracker;
            }else{
                $data['fromMail_data'] = $email;    
            }
            
    return $data['fromMail_data'];
}

/*
  @Author : Ritesh Rana
  @Desc   :Generates Token on Form
  @Input  :
  @Output :
  @Date   : 22/02/2018
 */

function send_mail_imap($to, $subject, $message, $attach = '', $from_email = '', $from_name = '', $cc = '', $bcc = '', $headers = '', $configs_arr = '') {
    $CI = & get_instance();
	  $configs = getMailConfig(); // Get Email configs from Email settigs page
    //$CI->load->library('parser');
    //sends email from client config 
    if (!empty($configs_arr)) {
        $config['mailtype'] = 'html';
//        $config['charset'] = 'iso-8859-1';
        $config['charset'] = 'UTF-8';
        $config['smtp_auth'] = 'TRUE';
        $config['smtp_crypto'] = 'tls';
        $config['smtp_auth'] = TRUE;
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n"; //use double quotes to comply with RFC 8
        $config['crlf'] = "\r\n";

  /*
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n"; //use double quotes to comply with RFC 8
        */
        //Add other config
        if (!empty($configs_arr)) {
            foreach ($configs_arr as $key => $value) {
                $config[$key] = $value;
            }
        }
        //unset($config['from']);
        $CI->load->library('email', $config); // load email library
        //Add header
        if (!empty($headers)) {
            foreach ($headers as $key => $value) {
                $CI->email->set_header($key, $value);
            }
            //$this->email->attach("uploads/attachment_temp/".$data['attachment']);
        }
		
		$to_mail = explode(";", $to);
		//pr($to_mail);exit;
        foreach ($to_mail as $to_mail_id){
              $CI->email->to($to_mail_id);
        }
        $CI->email->subject($subject);
        $CI->email->message($message);

        $from_name = !empty($from_name) ? $from_name : $from_email;

        $CI->email->from($from_email, $from_name);
		  
		    $to_mail = str_replace(';', ',', $to);

        $CI->email->to($to_mail);
		
       $cc_mail = str_replace(';', ',', $cc);
        if (!empty($cc)) {
            $CI->email->cc($cc_mail);
        }
        $bcc_mail = str_replace(';', ',', $bcc);
        if (!empty($bcc)) {
            $CI->email->bcc($bcc_mail);
        }

        if (!empty($attach)) {
            foreach ($attach as $row_attachment) {
                $CI->email->attach($row_attachment);
            }
            //$this->email->attach("uploads/attachment_temp/".$data['attachment']);
        }
        //Send email
        if ($CI->email->send()) {
            return true;
        } else {
            //sends email from system config 
            if (!empty($configs)) {
                $config['protocol'] = $configs['email_protocol'];
                $config['smtp_host'] = $configs['smtp_host']; //change this
                $config['smtp_port'] = $configs['smtp_port'];
                $config['smtp_user'] = $configs['smtp_user']; //change this
                $config['smtp_pass'] = $configs['smtp_pass']; //change this
                $config['mailtype'] = 'html';
//                $config['charset'] = 'iso-8859-1';
                $config['charset'] = 'UTF-8';
                $config['wordwrap'] = TRUE;
                $config['newline'] = "\r\n"; //use double quotes to comply with RFC 8
                //Add other config
                if (!empty($configs_arr)) {
                    foreach ($configs_arr as $key => $value) {
                        $config[$key] = $value;
                    }
                }
                $CI->load->library('email', $config); // load email library
                //Add header
                if (!empty($headers)) {
                    foreach ($headers as $key => $value) {
                        $CI->email->set_header($key, $value);
                    }
                    //$this->email->attach("uploads/attachment_temp/".$data['attachment']);
                }
                $CI->email->to($to);
                $CI->email->subject($subject);
                $CI->email->message($message);
                if (!empty($from_email)) {
                    $from_name = !empty($from_name) ? $from_name : 'CMS TEST';
                    $CI->email->from($from_email, $from_name);
                } else {
                    $CI->email->from($configs['smtp_user'], "CMS TEST");
                }
                $CI->email->to($to);
                if (!empty($cc)) {
                    $CI->email->cc($cc);
                }
                if (!empty($bcc)) {
                    $CI->email->bcc($bcc);
                }


                if (!empty($attach)) {
                    foreach ($attach as $row_attachment) {
                        $CI->email->attach($row_attachment);
                    }
                    //$this->email->attach("uploads/attachment_temp/".$data['attachment']);
                }

                return $CI->email->send();
            }
        }
    } else {
        //sends email from system default 
        $CI->load->library('email');
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
//        $config['charset'] = 'iso-8859-1';
        $config['charset'] = 'UTF-8';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = "html";
        //Add other config
        if (!empty($configs_arr)) {
            foreach ($configs_arr as $key => $value) {
                $config[$key] = $value;
            }
        }
        //$CI->load->library('email', $config); // load email library
        $CI->email->initialize($config);
        //Add header
        if (!empty($headers)) {
            foreach ($headers as $key => $value) {
                $CI->email->set_header($key, $value);
            }
            //$this->email->attach("uploads/attachment_temp/".$data['attachment']);
        }
        $CI->email->set_newline("\r\n");
        //$list = array('mehul.patel@c-metric.com');
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);
        $from_name = !empty($from_name) ? $from_name : $from_email;
        $CI->email->from($from_email, $from_name);
        $CI->email->to($to);
        if (!empty($cc)) {
            $CI->email->cc($cc);
        }
        if (!empty($bcc)) {
            $CI->email->bcc($bcc);
        }

        if (!empty($attach)) {
            foreach ($attach as $row_attachment) {
                $CI->email->attach($row_attachment);
            }
            //$this->email->attach("uploads/attachment_temp/".$data['attachment']);
        }
        return $CI->email->send();
    }
}

?>
