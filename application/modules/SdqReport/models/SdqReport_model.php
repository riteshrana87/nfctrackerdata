<?php

/*
  Author : Ishani dvae
  Desc   : SDQ Report Model

 */

class SdqReport_model extends CI_Model {

// Get record from  nfc_admin_sdq_subque table 

    public function getSubQue($id) {

        $this->db->select();
        $this->db->from(NFC_ADMIN_SDQ_SUB);

        $this->db->where('parent_que_id', $id);
        $this->db->where('status', 1);
        $this->db->where('is_delete', 0);
        $query = $this->db->get();



        if ($query->num_rows() > 0) {
            $row = $query->result_array();
            return $row;
        }
    }
//get sdq ans value
    function getSdqAnsValue($record_id, $id) {
        $this->db->select('ans');
        $this->db->from(NFC_SDQ_RECORD_ANS);

        $this->db->where('sub_que_id', $id);
        $this->db->where('record_id', $record_id);
        $this->db->where('is_delete', 0);
        $query = $this->db->get();


        if ($query->num_rows() > 0) {
            $row = $query->result_array();
            return $row;
        }
    }

    // check record
    function checkRecord($yp_id , $user_type, $date) {
        $this->db->select();
        $this->db->from(NFC_SDQ_RECORD);

        $this->db->where('yp_id', $yp_id);
        $this->db->where('user_type', $user_type);
        $this->db->where('DATE(created_date)', $date);
        $this->db->where('is_delete', 0);
        $query = $this->db->get();
        $row = $query->result_array();
        return $row;
    }
    //get emotional symptoms data
    function getEmoSympData($id, $que_id, $selected_year) {
        $this->db->distinct();
        $this->db->select('DATE(sr.created_date) AS "date",sr.user_type, sum(sa.ans) AS "emo_score"');
        $this->db->from('nfc_record_ans sa');
        $this->db->where('sa.record_id', $id);
        $this->db->where('sr.is_delete', 0);
        $this->db->where('sa.que_id', $que_id);
        $this->db->where('YEAR(sr.created_date)', $selected_year);
        $this->db->join('nfc_sdq_recordsheet as sr', 'sr.id = sa.record_id', 'left');
        $query = $this->db->get();
        $sdata = $query->result_array();

        return $sdata;
    }
    // GET Total difficulty data
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
    
    // get line year 
    function getlineYear($yp_id) {
        $this->db->select('min(created_date) AS "min_year",max(created_date) AS "max_year", user_type, total_score');
        $this->db->from('nfc_sdq_recordsheet');
        $this->db->where('yp_id', $yp_id);
        $this->db->where('is_delete', '0');
      
        $query = $this->db->get();
        $yeardata = $query->result_array();
        return $yeardata;
    }
    
    // get year data
    function getlineYearData($yp_id) {
        $this->db->select('year(created_date) AS "year", user_type, total_score');
        $this->db->from('nfc_sdq_recordsheet');
        $this->db->where('yp_id', $yp_id);
        $this->db->where('is_delete', '0');
      
        $query = $this->db->get();
        $yeardata = $query->result_array();
        return $yeardata;
    }
    //get line months
    function getlineMonth($yp_id,$year) {
        $this->db->select('DAY(created_date) AS "day",month(created_date) AS "month",year(created_date) AS "year", user_type, total_score');
        $this->db->group_by('created_date'); 
        $this->db->from('nfc_sdq_recordsheet');
        $this->db->where('yp_id', $yp_id);
        $this->db->where('year(created_date)', $year);
        $this->db->where('is_delete', '0');
      
        $query = $this->db->get();
        $yeardata = $query->result_array();
        return $yeardata;
    }

}

?>