<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class NFCCron extends CI_Controller {

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
        
    }

    /*
      @Author : Niral Patel
      @Desc   : Function for archive 
      @Input  :
      @Output :
      @Date   : 13/10/2017
     */
      public function archiveYp() {
            $today = dateformat();

            $archive_date = date('Y-m-d', strtotime('-28 days'));

            // Get yp detail
            $table = YP_DETAILS . ' as yp';
            $match = array("yp.is_deleted" => '0',"yp.is_archive" => 0, "yp.end_of_placement <=" => $archive_date, "yp.end_of_placement <>" =>'0000-00-00');
            $fields = array("yp.yp_id");
            $yp_data = $this->common_model->get_records($table, $fields, '', '', $match);
           
           //echo $this->db->last_query();exit;

           if(!empty($yp_data))
           {
                foreach ($yp_data as $row) {
                    $where = array('yp_id' => $row['yp_id']);
                    $data = array('is_archive' => 1);
                    $this->common_model->update(YP_DETAILS, $data, $where);
                }
                echo 'Successfully run.';
           }
           else
           {
                echo 'No data found.';
           }
      }

      /*
      @Author : ritesh rana
      @Desc   : Function for Care Plan Meeting Yp 
      @Input  :
      @Output :
      @Date   : 23/01/2018
     */
      public function CarePlanMeetingYp() {

            $today = dateformat();

            $archive_date = date('Y-m-d', strtotime('-6 weeks'));
            //$archive_date = date('Y-m-d', strtotime('-28 days'));
            // Get yp detail

/*            $table = INDIVIDUAL_RECORDS_DATE_STAFF . ' as ds';
            $match = array("ds.created_date" => $archive_date);
            $fields = array("ds.individual_meeting_id");
            $yp_data = $this->common_model->get_records($table, $fields, '', '', '', $match, 1, 0,  'individual_meeting_id', 'DESC', '', '');
*/
        
            $table = YP_DETAILS . ' as yp';
            $match = array("yp.is_deleted" => '0',"yp.is_archive" => 0, "yp.date_of_placement" => $archive_date);
            $fields = array("yp.yp_id");
            $yp_data = $this->common_model->get_records($table, $fields, '', '', $match);
            //pr($yp_data);exit;    

           if(!empty($yp_data))
           {
                foreach ($yp_data as $row) {
                    $insert_array['yp_id'] = $row['yp_id'];
                    $yp_id = $row['yp_id'];
                    $tableName = CARE_PLAN_MEETING;
                    $fields = array('COUNT(yp_id) AS ypData');
                    if (!empty($yp_id)) { // edit 
                        $match = array('yp_id' => $yp_id);
                    }
                    $duplicateEmp = $this->common_model->get_records($tableName, $fields, '', '', $match);   
                if ($duplicateEmp[0]['ypData'] > 0) {
                     echo 'Update data.';
                } else {
                    $insert_array['created_date'] = datetimeformat();
                    $this->common_model->insert(CARE_PLAN_MEETING, $insert_array);
                    //echo $this->db->last_query();exit;
                    echo 'Successfully run.';
                }
                   
                }
           }
           else
           {
                echo 'No data found.';
           }
      }
       /*
          @Author : Niral Patel
          @Desc   : Function for Care Plan Meeting Yp 
          @Input  :
          @Output :
          @Date   : 23/01/2018
        */
          public function PocketMoneySaving() {
            //get yp pocket money
            $yp_pocket_money = $this->common_model->get_records(YP_POCKET_MONEY,'', '', '');
            
            if(!empty($yp_pocket_money))
            {
                foreach ($yp_pocket_money as $row) {
                    if($row['total_balance'] >= 5)
                    {
                        $update_data = array('total_balance'=>($row['total_balance']-5),
                            'saving_balance'=>($row['saving_balance']+5),
                            'updated_at'=>datetimeformat());
                        //update total balance
                        $this->common_model->update(YP_POCKET_MONEY,$update_data,array('yp_id'=>$row['yp_id']));
                        //update pocket money
                        $data['balance']      = ($row['total_balance']-5);
                        $data['yp_id']        = $row['yp_id'];
                        $data['staff']        = $this->session->userdata['LOGGED_IN']['ID'];
                        $data['money_out']    = '5';
                        $data['reason']       = 'Saving Pocket Money';
                        $data['yp_id']        = $row['yp_id'];
                        $data['created_date'] = datetimeformat();
                        $data['created_by']   = $this->session->userdata['LOGGED_IN']['ID'];
                        $this->common_model->insert(POCKET_MONEY, $data);
                       
                        //Insert log activity
                        $activity = array(
                        'user_id'             => $this->session->userdata['LOGGED_IN']['ID'],
                        'yp_id'               => !empty($row['yp_id'])?$row['yp_id']:'',
                        'module_name'         => POCKET_MONEY_MODULE,
                        'module_field_name'   => '',
                        'type'                => 1
                        );
                        log_activity($activity);
                    }
                }
                echo 'Done';
            }
            else
            {
                echo 'No data found.';
            }
          }
           /*
              @Author : Niral Patel
              @Desc   : Function for configure medical stock 
              @Input  :
              @Output :
              @Date   : 22/05/2018
            */
        public function UpdateMedicalStock() {
            //get medication stock
            $table = MEDICATION . ' as m';
            //$where = array("m.care_home_id !="=>'');
            $medication = $this->common_model->get_records($table,'','', '','');
            $medicationData = array();
            
            if(!empty($medication))
            {
                $n = 1;
                foreach ($medication as $row) {
                    echo $n.'<br>';
                    if(empty($row['care_home_id']))
                    {
                        $table = YP_DETAILS . ' as yp';
                        $match = array("yp.yp_id" => $row['yp_id']);
                        $fields = array("yp.care_home");
                        $yp_data = $this->common_model->get_records($table, $fields, '', '', $match);
                    }
                    $table = MEDICAL_CARE_HOME_TRANSECTION;
                    $where = array("medication_id"=>$row['medication_id'],"care_home_id"=>!empty($row['care_home_id'])?$row['care_home_id']:$yp_data[0]['care_home'],'is_archive'=>0);
                    $medicationStockCheck = $this->common_model->get_records($table,'','', '',$where);
                    
                    if(empty($medicationStockCheck))
                    {
                        $medicationData['medication_id'] = $row['medication_id'];
                        $medicationData['care_home_id']  = !empty($row['care_home_id'])?$row['care_home_id']:$yp_data[0]['care_home'];
                        $medicationData['is_archive']    = $row['is_archive'];
                        $where = array("medication_id"=>$row['medication_id'],"care_home_id"=>$medicationData['care_home_id']);
                        $medicationCheck = $this->common_model->get_records(MEDICAL_CARE_HOME_TRANSECTION,'','', '',$where);
                        
                        if(empty($medicationCheck))
                        {
                            //insert transection table
                            $medical_care_home_id = $this->common_model->insert(MEDICAL_CARE_HOME_TRANSECTION, $medicationData);
                            //update daily stock
                            $where = array('medication_id' => $row['medication_id']);
                            $dailyStockData = array('medical_care_home_id' => $medical_care_home_id);
                            
                            $this->common_model->update(NFC_DAILY_STOCK_CHECK, $dailyStockData, $where);
                        }
                    }
                    
                    
                    $n++;

                }
                 echo 'Done';
            }
        }
		
		/*nikunj ghelani
	30-10-2018
	for crone job setting
	*/
	public function CronJob(){
		
		
		$table = MOVE_TO_CAREHOME . ' as mvtc';
        $match = "mvtc.status = 1";
        $fields = array("mvtc.*");
        $data['move_care_home_data'] = $this->common_model->get_records($table, $fields, '', '', $match);
		/* pr($data['move_care_home_data']);
		die; */
		foreach($data['move_care_home_data'] as $move_data){
			$today_date=explode('-',$move_data['move_date']);
			$final_today_date=$today_date['2'].'/'.$today_date['1'].'/'.$today_date['0'];
			$data = array(
					'status' => 1,
					
				);
			$where = array('id' => $move_data['id']);
			$this->common_model->update(MOVE_TO_CAREHOME, $data, $where);
		if (date("d/m/Y") == $final_today_date) {
			
			$match = array("yp_id" =>$move_data['yp_id']);
            $fields = array("*");
            $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
			
			  $postdata = array(
					'yp_id' => ucfirst($data['YP_details'][0]['yp_id']),
					'yp_fname' => ucfirst($data['YP_details'][0]['yp_fname']),
					'yp_lname' => ucfirst($data['YP_details'][0]['yp_lname']),
					'care_home' => $move_data['move_care_home'],
					'care_home_admission_date' => dateformat($move_data['move_date']),  
					'modified_date' => datetimeformat(),
					'updated_by' => $this->session->userdata['LOGGED_IN']['ID'],
				);
				$is_cron=1;
				$this->updatedata($postdata,$is_cron);
			
		}else{
			return false;
		}
		}
		
		
	
		
	}
	
	/*nikunj ghelani
	5-11-2018
	for crone job yp add
	*/
	public function CronJobYPADD(){
		//die('here');
		
		$table = YP_DETAILS_CRON . ' as ypdc';
        $match = "ypdc.is_move = 0";
        $fields = array("ypdc.*");
        $data['move_care_home_data_yp_detail'] = $this->common_model->get_records($table, $fields, '', '', $match);
		// pr($data['move_care_home_data_yp_detail']);
		// die;
		foreach($data['move_care_home_data_yp_detail'] as $move_data){
			
			$today_date=explode('-',$move_data['care_home_admission_date']);
			$final_today_date=$today_date['2'].'/'.$today_date['1'].'/'.$today_date['0'];
			
		if (date("d/m/Y") == $final_today_date) {
			$data = array(
					'is_move' => 1,
					
				);
			$where = array('yp_id' => $move_data['yp_id']);
			$this->common_model->update(YP_DETAILS_CRON, $data, $where);
			
			
		
			 $data = array(
                'yp_fname' => ucfirst($move_data['yp_fname']),
                'yp_lname' => ucfirst($move_data['yp_lname']),
                'yp_initials' => $move_data['yp_initials'],
                'date_of_placement' => $move_data['date_of_placement'],
                'care_home_admission_date' => $move_data['care_home_admission_date'],  
                'care_home' => $move_data['care_home'],
                'created_date' => datetimeformat(),
                'modified_date' => datetimeformat(),
				/*this both are comment becaue this cron job and we dont have session.
				nikunj ghelani
				05/11/2018
				*/
               /*  'created_by' => $main_user_data['ID'],
                'updated_by' => $main_user_data['ID'], */
                
            );
			
			$this->common_model->insert(YP_DETAILS, $data); 
				$is_cron=1;
				
			
		}else{
			//return false;
		}
		}
		
		
	
		
	}
}
