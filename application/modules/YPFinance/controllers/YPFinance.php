<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class YPFinance extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->viewname = $this->router->fetch_class ();
        $this->method   = $this->router->fetch_method();
    }

    /*
      @Author : Niral Patel
      @Desc   : YP finance Index Page
      @Input  : yp id
      @Output :
      @Date   : 2/05/2018
     */

    
    public function index($ypid,$careHomeId=0,$isArchive=0) {
		
		  /*for care to care data by ghelani nikunj on 18/9/2018 for care to  care data get with the all previous care home*/
        if ($isArchive !== 0) {
            $temp = $this->common_model->get_records(PAST_CARE_HOME_INFO, array('move_date'), '', '', array("yp_id" => $ypid, "past_carehome" => $careHomeId));
            $data_care_home_detail = $this->common_model->get_records(PAST_CARE_HOME_INFO, array("*"), '', '', array("yp_id" => $ypid, "move_date <= " => $temp[0]['move_date']));
            $created_date = $movedate = '';
            $count_care = count($data_care_home_detail);
            if ($count_care >= 1) {
                $created_date = $data_care_home_detail[0]['enter_date'];
                $movedate = $data_care_home_detail[$count_care - 1]['move_date'];
            } elseif ($count_care == 0) {
                $created_date = $data_care_home_detail[0]['enter_date'];
                $movedate = $data_care_home_detail[0]['move_date'];
            }
        }

        /***********************/

      if(is_numeric($ypid))
      {
        //get YP information
        $match = array("yp_id"=>$ypid);
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
        if(empty($data['YP_details']))
        {
            $msg = $this->lang->line('common_no_record_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('YoungPerson/view/'.$ypid);
        }
        
         if($isArchive!=0){
			 
		 $match = array('yp_id'=> $ypid);
		 $where_date="created_date BETWEEN  '".$created_date."' AND '".$movedate."'";
         
		 $data['yp_pocket_money_archive'] = $this->common_model->get_records(POCKET_MONEY, '', '', '', '', '', '', '', '', '', '', $match,'','','','','',$where_date);
		
		 $data['yp_pocket_money']=end($data['yp_pocket_money_archive']);
		 }else{
			 //get total balance pocket money
         $match = array('yp_id'=> $ypid);
         $data['yp_pocket_money'] = $this->common_model->get_records(YP_POCKET_MONEY,'', '', '', $match);
		 }
		
		if($isArchive==0){
         //get total balance clothing money
         $match = array('yp_id'=> $ypid);
         $data['yp_clothing_money'] = $this->common_model->get_records(YP_CLOTHING_MONEY,'', '', '', $match);
		}else{
			
		$match = array('yp_id'=> $ypid);
		 $where_date="created_date BETWEEN  '".$created_date."' AND '".$movedate."'";
		 $data['yp_clothing_money_archive'] = $this->common_model->get_records(CLOTHING_MONEY, '', '', '', '', '', '', '', '', '', '', $match,'','','','','',$where_date);
		 $data['yp_clothing_money']=end($data['yp_clothing_money_archive']);
			
		}
        $data['ypid'] = $ypid;
		$data['is_archive_page'] = $isArchive;
        $data['careHomeId'] = $careHomeId;
        $data['header'] = array('menu_module' => 'YoungPerson');
        $data['crnt_view'] = $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/keysession/keysession.js');
       
        $data['main_content'] = '/index';
        $this->parser->parse('layouts/DefaultTemplate', $data);
      }
      else
      {
          show_404 ();
      }
    }
  
}
