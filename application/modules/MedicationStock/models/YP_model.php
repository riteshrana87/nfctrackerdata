<?php

/*
  Author : Ishani dvae
  Desc   : yp Model

 */

class YP_model extends CI_Model {

// Get record from  nfc_admin_sdq_subque table 

    public function check_carehome($id) {

        $this->db->from(YP_DETAILS);
        $this->db->select('care_home,date_of_placement,care_home_admission_date');
        $this->db->where('yp_id', $id);
        $this->db->where('is_deleted', '0');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->result_array();
            return $row;
        }
    }

    function check_pastcarehome($id) {
        $this->db->select('*');
        $this->db->from(PAST_CARE_HOME_INFO);

        $this->db->where('yp_id', $id);
        $this->db->where('is_delete', '0');
        $this->db->where('is_move', '0');
        $query = $this->db->get();
        $row = $query->result_array();
        return $row;

    }

    function get_carehome($id) {
        $this->db->select('care_home_name');
        $this->db->from(CARE_HOME);
        $this->db->where('care_home_id', $id);
        $this->db->where('is_delete', '0');
        $query = $this->db->get();
        $row = $query->result_array();
        return $row;
    }
   


}

?>