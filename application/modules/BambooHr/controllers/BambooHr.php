<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class BambooHr extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->viewname = $this->router->fetch_class();
        date_default_timezone_set('Asia/Kolkata');
        set_time_limit(0);
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Common Model Index Page
      @Input 	:
      @Output	:
      @Date   : 01/09/2017
     */

    public function index() {
       $apiKey = '4cf2fa4db6a143e49617b6c8c35d2a2f47354405';
        $url = 'https://api.bamboohr.com/api/gateway.php/nfccmetricsandbox/v1/employees/directory';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERPWD, "$apiKey" . ":" . "x");
        $result = curl_exec($ch);
        curl_close($ch);
        $obj = new SimpleXMLElement($result);
        $arr = json_decode(json_encode($obj), TRUE);
        
        $empDataArray = array();
        foreach ($arr['employees']['employee'] as $key => $employees_data) {

            $empDataArray[$key] = array_combine($arr['fieldset']['field'], $employees_data['field']);
            $empDataArray[$key]['emp_id'] = $employees_data['@attributes']['id'];
        }
        pr($empDataArray);exit; 
    }    
}
