<?php

/*
  Author : Ishani dvae
  Desc   : SDQ Report Model

 */

class CseReport_model extends CI_Model {

// Get record from  nfc_admin_sdq_subque table 

    public function getSubQue($id) {

        $this->db->select();
        $this->db->from(NFC_ADMIN_CSE_SUB);

        $this->db->where('parent_que_id', $id);
        $this->db->where('status', '1');
        $this->db->where('is_delete', '0');
        $query = $this->db->get();



        if ($query->num_rows() > 0) {
            $row = $query->result_array();
            return $row;
        }
    }

    function getCseAnsValue($record_id, $id) {
        $this->db->select('ans');
        $this->db->from(NFC_CSE_RECORD_ANS);

        $this->db->where('sub_que_id', $id);
        $this->db->where('record_id', $record_id);
        $this->db->where('is_delete', 0);
        $query = $this->db->get();


        if ($query->num_rows() > 0) {
            $row = $query->result_array();
            return $row;
        }
    }

    function checkRecord($yp_id , $user_type, $date) {
        $this->db->select();
        $this->db->from(NFC_CSE_RECORD);

        $this->db->where('yp_id', $yp_id);
        $this->db->where('user_type', $user_type);
        $this->db->where('DATE(created_date)', $date);
        $this->db->where('is_delete', 0);
        $query = $this->db->get();
        $row = $query->result_array();
        return $row;
    }

    function getHealthData($id, $que_id, $selected_year) {
        $this->db->select('DATE(sr.created_date) AS "date",sr.user_type, sa.ans');
        $this->db->from('nfc_cse_record_ans sa');
        $this->db->where('sa.record_id', $id);
        $this->db->where('sr.is_delete', '0');
        $this->db->where('sa.que_id', $que_id);
        $this->db->where('YEAR(sr.created_date)', $selected_year);
        $this->db->join('nfc_cse_recordsheet as sr', 'sr.id = sa.record_id', 'left');
        $query = $this->db->get();
        $sdata = $query->result_array();
        return $sdata;
      
    }

    function getTotDifData($id, $selected_year, $que_id) {
        $this->db->distinct();
        $this->db->select('DATE(sr.created_date) AS "date",sr.user_type, sum(sa.ans) AS "total_score"');
        $this->db->from('nfc_record_ans sa');
        $this->db->where('sa.record_id', $id);
        $this->db->where('sr.is_delete', 0);
        $this->db->where_not_in('sa.que_id', $que_id);
        $this->db->where('YEAR(sr.created_date)', $selected_year);
        $this->db->join('nfc_sdq_recordsheet as sr', 'sr.id = sa.record_id', 'left');
        $query = $this->db->get();
        $sdata = $query->result_array();
        return $sdata;
    }

    function getlineYear($yp_id) {

        
       $this->db->select('year(created_date) AS "year", user_type, total_score_h,total_score_m,total_score_l, total_score_n');
       $this->db->group_by('user_type'); 
       $this->db->group_by('year(created_date)');
        $this->db->from('nfc_cse_recordsheet');
        $this->db->where('yp_id', $yp_id);
        $this->db->where('is_delete', '0');

/*
  @Author : Ritesh rana
  @Desc   : Get past care home data As per create date and move date
  @Input  : create date and pmove data
  @Output : CSE report data
  @Date   : 25/09/2018
 */
     if($this->input->post('PastCareId') > 0){
        $movedate = $this->input->post('movedate');
        $CreatedDate = $this->input->post('CreatedDate');
        $where_date = "created_date BETWEEN  '".$CreatedDate."' AND '".$movedate."'";
        $this->db->where($where_date, NULL, FALSE);
    }
    /*end*/
        $query = $this->db->get();
        $yeardata = $query->result_array();
        return $yeardata;
    }
    function getlineYearData($yp_id, $year, $userType) {
        $this->db->select('sum(total_score_h) as h_score,sum(total_score_m) as m_score,sum(total_score_l) as l_score, sum(total_score_n) as n_score,count(id) as total_count');
       // $this->db->group_by('created_date');
        $this->db->from('nfc_cse_recordsheet');
        $this->db->where('yp_id', $yp_id);
        $this->db->where('year(created_date)', $year);
        $this->db->where('user_type', $userType);
        $this->db->where('is_delete', '0');
      
        $query = $this->db->get();
        $yeardata = $query->result_array();
        return $yeardata;
    }
    function getlineMonth($yp_id,$year) {
        $this->db->select('DAY(created_date) AS "day",month(created_date) AS "month",year(created_date) AS "year", user_type, total_score_h,total_score_m,total_score_l, total_score_n');
        $this->db->group_by('created_date'); 
        $this->db->from('nfc_cse_recordsheet');
        $this->db->where('yp_id', $yp_id);
        $this->db->where('year(created_date)', $year);
        $this->db->where('is_delete', '0');

        /*
  @Author : Ritesh rana
  @Desc   : Get past care home data As per create date and move date
  @Input  : create date and pmove data
  @Output : CSE report data
  @Date   : 25/09/2018
 */
     if($this->input->post('PastCareId') > 0){
        $movedate = $this->input->post('movedate');
        $CreatedDate = $this->input->post('CreatedDate');
        $where_date = "created_date BETWEEN  '".$CreatedDate."' AND '".$movedate."'";
        $this->db->where($where_date, NULL, FALSE);
    }
    /*end*/
        $query = $this->db->get();
        $yeardata = $query->result_array();
        return $yeardata;
    }

}

?>