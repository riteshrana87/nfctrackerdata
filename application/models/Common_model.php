<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  @Author : Ritesh Rana
  @Desc   : Common Model For Insert, Update, Delete and Get all records
  @Input 	:
  @Output	:
  @Date   : 20/02/2017
 */

class Common_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->prefix = $this->db->dbprefix;
    }

    public function insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function insert_batch($table, $data) {
        return $this->db->insert_batch($table, $data);
//return $this->db->insert_id();
    }

    public function update($table, $data, $where) {
        return $this->db->where($where)->update($table, $data);

        /* $this->db->where($where);
          $this->db->update($table, $data);
         */
    }

    public function update_batch($table, $data,$whereInField) {
        return $this->db->update_batch($table, $data,$whereInField);
//return $this->db->insert_id();
    }

    public function delete($table, $where) {
        return $this->db->where($where)->delete($table);
        /* $this->db->where($where);
          $this->db->delete($table);
         */
    }

    /* Delete multiple id  by niral */

    public function delete_where_in($table, $where, $field, $wherefield) {
        return $this->db->where($where)->where_in($field, $wherefield)->delete($table);
    }

//GET all type of data
    function get_records($table = '', $fields = '', $join_tables = '', $join_type = '', $match_and = '', $match_like = '', $num = '', $offset = '', $orderby = '', $sort = '', $group_by = '', $wherestring = '', $having = '', $where_in = '', $totalrow = '', $or_where = '', $where_not_in = '', $where_between = '') {
        if (!empty($fields) && is_array($fields)) {
            foreach ($fields as $coll => $value) {
                $this->db->select($value, false);
            }
        }

        $this->db->from($table);

        if (!empty($join_tables)) {
            foreach ($join_tables as $coll => $value) {
                $this->db->join($coll, $value, $join_type);
            }
        }
        if ($match_like != null)
            $this->db->or_like($match_like);
        if ($match_and != null)
            $this->db->where($match_and);

        if ($wherestring != '')
            $this->db->where($wherestring, NULL, FALSE);

        if ($where_between != '' && !empty($where_between)) {
            $this->db->where($where_between, NULL, FALSE);
        }
        if (!empty($where_in) && is_array($where_in)) {
            foreach ($where_in as $key => $value) {
                $this->db->where_in($key, $value);
            }
        }

        if (!empty($where_not_in)) {
            foreach ($where_not_in as $key => $value) {
                $this->db->where_not_in($key, $value);
            }
        }

        if (!empty($or_where)) {
            foreach ($or_where as $key => $value) {
                $this->db->or_where($key, $value);
            }
        }

        if ($group_by != null)
            $this->db->group_by($group_by);
        if ($having != null)
            $this->db->having($having);
        if ($orderby != null && $sort != null)
            $this->db->order_by($orderby, $sort);



        if ($offset != null && $num != null)
            $this->db->limit($num, $offset);
        elseif ($num != null)
            $this->db->limit($num);
        $query_FC = $this->db->get();
//echo $this->db->last_query();exit;
        if (!empty($totalrow))
            return $query_FC->num_rows();
        else
            return $query_FC->result_array();
    }

    function get_records_array($params = array()) {

        if (array_key_exists("fields", $params)) {
            if (!empty($params['fields'])) {
                foreach ($params['fields'] as $coll => $value) {
                    $this->db->select($value, false);
                }
            }
        }

        if (array_key_exists("table", $params)) {
            $this->db->from($params['table']);
        }

        if (array_key_exists("join_tables", $params)) {
            if (!empty($params['join_tables'])) {
                foreach ($params['join_tables'] as $coll => $value) {
                    $this->db->join($coll, $value, $params['join_type']);
                }
            }
        }

        if (array_key_exists("match_like", $params)) {
            if ($params['match_like'] != null)
                $this->db->or_like($params['match_like']);
        }

        if (array_key_exists("match_and", $params)) {
            if ($params['match_and'] != null)
                $this->db->where($params['match_and']);
        }

        if (array_key_exists("wherestring", $params)) {
            if ($params['wherestring'] != '')
                $this->db->where($params['wherestring'], NULL, FALSE);
        }

        if (array_key_exists("where_between", $params)) {
            if ($params['where_between'] != '' && !empty($params['where_between'])) {
                $this->db->where($params['where_between'], NULL, FALSE);
            }
        }

        if (array_key_exists("where_in", $params)) {
            if (!empty($params['where_in'])) {

                foreach ($params['where_in'] as $key => $value) {

                    $this->db->where_in($key, $value);
                }
            }
        }


        if (array_key_exists("where_not_in", $params)) {
            if (!empty($params['where_not_in'])) {
                foreach ($params['where_not_in'] as $key => $value) {
                    $this->db->where_not_in($key, $value);
                }
            }
        }

        if (array_key_exists("or_where", $params)) {
            if (!empty($params['or_where'])) {
                foreach ($params['or_where'] as $key => $value) {
                    $this->db->or_where($key, $value);
                }
            }
        }

        if (array_key_exists("group_by", $params)) {
            if ($params['group_by'] != null)
                $this->db->group_by($params['group_by']);
        }

        if (array_key_exists("having", $params)) {
            if ($params['having'] != null)
                $this->db->having($params['having']);
        }

        if (array_key_exists("orderby", $params)) {
            if ($params['orderby'] != null && $params['sort'] != null)
                $this->db->order_by($params['orderby'], $params['sort']);
        }

        if (array_key_exists("offset", $params)) {
            if ($params['offset'] != null && $params['num'] != null)
                $this->db->limit($params['num'], $params['offset']);
            elseif ($params['num'] != null)
                $this->db->limit($params['num']);
        }

        $query_FC = $this->db->get();
//echo $this->db->last_query();exit;
        if (array_key_exists("totalrow", $params)) {
            if (!empty($params['totalrow'])) {
                return $query_FC->num_rows();
            }
        } else {
            return $query_FC->result_array();
        }
    }

//Function For Get Config 
    public function get_config() {
        return $this->db->get('config');
    }

    public function get_lang() {
        $this->load->helper('cookie');
        $selectedLang = get_cookie('languageSet');
        /* if ($this->session->userdata('lang')) {
          return $this->session->userdata('lang'); */
        if ($selectedLang) {
            return $selectedLang;
        } else {
            $query = $this->db->select('value')->where('config_key', 'language')->get('config');
            if ($query->num_rows() > 0) {
                $row = $query->row();
                return $row->value;
            }
        }
    }

    /* Start added by sanket om 27/01/2016 */

    public function pagination_html($config) {
        $this->load->library('pagination');


// $config["uri_segment"] = $config["uri_segment"];
        $config["uri_segment"] = 3;

        $config["uri_segment"] = 3;

        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);

        return $this->pagination->create_links();
    }

    /* Start added by niral om 10/02/2016 */

    public function common_pagination($config, $page_url) {
        $config["uri_segment"] = $config["uri_segment"];
        $choice = $config["total_rows"] / $config["per_page"];
        $config['full_tag_open'] = '<ul class="pagination pull-right m-0">';
        $config['full_tag_close'] = '</ul>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="' . $page_url . '">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['first_link'] = '&lt;&lt;';
        $config['last_link'] = '&gt;&gt;';

        $this->pagination->cur_page = 4;

        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }

    public function customQuery($sql) {
        $result = $this->db->query($sql, false);
        return $result->result_array();
    }

    /*
      @Description: Function for image upload
      @Author: Ritesh Rana
      @Input:
      @Output: Image will upload on perticular folder
      @Date: 23-02-2017
     */

    function upload_files($uploadFile = '', $filePath = '') {
        $upload_name = $uploadFile;
        $config['upload_path'] = $filePath; /* NB! crea          te this dir! */
//$config['allowed_types'] = 'gif|jpg|png|bmp|jpeg|csv|doc|docx|txt|pdf|xls|mov|mp4|PNG';
        $config['allowed_types'] = '*';

        $random = substr(md5(rand()), 0, 7);
        $config['file_name'] = $random . "-" . (strtolower($_FILES[$uploadFile]['name']));
        $config['overwrite'] = false;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload($upload_name))
            $data = $this->upload->data();
        else {
            echo $this->upload->display_errors();
            exit;
        }
//Upload thumb image
        $sourcePath = $data['full_path'];
        $thumbPath = $smallImgPath;
        $fileName = $data['file_name'];

        return $fileName;
    }

    function upload_image($uploadFile = '', $bigImgPath = '', $smallImgPath = '', $thumb = '', $existImage = '',$extention='') {

        if (!empty($existImage)) {
            $path = $bigImgPath . $existImage;
            $path_thumb = $smallImgPath . $existImage;
            @unlink($path);
            @unlink($path_thumb);
        }
        $upload_name = $uploadFile;
        $config['upload_path'] = $bigImgPath; /* NB! crea          te this dir! */
//$config['allowed_types'] = 'gif|jpg|png|bmp|jpeg|csv|doc|docx|txt|pdf|xls|mov|mp4|PNG';
        if(!empty($extention))
        {$config['allowed_types'] = $extention;}
        else
        {$config['allowed_types'] = '*';}
        

        $random = substr(md5(rand()), 0, 7);
        $config['file_name'] = $random . "-" . (strtolower($_FILES[$uploadFile]['name']));
        $config['overwrite'] = false;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload($upload_name))
            $data = $this->upload->data();
        else {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>".lang('error')."</div>");
        }
//Upload thumb image
        $sourcePath = $data['full_path'];
        $thumbPath = $smallImgPath;
        $fileName = $data['file_name'];

        list($width, $height, $type, $attr) = getimagesize($bigImgPath . $fileName);

        if (!empty($thumb) && $thumb == 'thumb') {
            if (!file_exists($smallImgPath)) {
                mkdir($bigImgPath, 0777);
            }

            $basename = explode('.', $_FILES[$uploadFile]['name']);
            $filename = $basename[0];
//for create small image
            if ($data['file_type'] == 'image/bmp' || $basename[1] == 'bmp') {
                $sourceImgBig = base_url() . $bigImgPath . $fileName;
                copy($sourceImgBig, $smallImgPath . $filename . ".jpeg");
                $imgurl = base_url() . $smallImgPath . $filename . ".jpeg";
                $width = 150;
                $this->make_thumb($imgurl, $smallImgPath . $fileName, $width);
                @unlink($smallImgPath . $filename . ".jpeg");
            } else {
                $filename = $this->upload_small_image($sourcePath, $thumbPath, $fileName);
            }


            return $fileName;
        }
    }

//Upload thumb images
    public function upload_small_image($sourceImgPath, $thumbPath) {
        $configThumb = array();
        $configThumb['image_library'] = 'gd2';
        $configThumb['thumb_marker'] = FALSE;
        $configThumb['source_image'] = '';
        $configThumb['create_thumb'] = TRUE;
        $configThumb['maintain_ratio'] = FALSE;
        $configThumb['width'] = 150;
        $configThumb['height'] = 150;
        $this->load->library('image_lib');

        $configThumb['source_image'] = $sourceImgPath;
        $configThumb['new_image'] = $thumbPath;
        $this->image_lib->initialize($configThumb);
        $this->image_lib->resize();
    }


    /*
      Added by sanket for returnig language name by ID
     * 
     *      */

    function getLanguageNameById($language_id) {
        if ($language_id == '1') {
            return "English";
        } else if ($language_id == '2') {
            return "Spanish";
        }
    }


    //for generating auto generated lead id 
    function lead_auto_gen_Id() {
        return 'L' . mt_rand(100000, 999999);
    }

    //for generating auto generated client id 
    function client_auto_gen_Id() {
        return 'C' . mt_rand(100000, 999999);
    }

    //for generating auto generated opportunity id 
    function opportunity_auto_gen_Id() {
        return 'P' . mt_rand(100000, 999999);
    }

    function customer_auto_gen_Id() {
        return mt_rand(1000000, 9999999);
    }

    function serial_auto_gen_Id() {
        return mt_rand(1000, 9999);
    }

    function initialsId() {
        return mt_rand(100, 999);
    }


    function getCountryIdByName($country_name) {

        $this->db->select('country_id');
        $this->db->from(COUNTRIES);
        $this->db->where('country_name', $country_name);
        $dataarr = $this->db->get()->result();


        //pr($dataarr);
        if (is_array($dataarr) && !empty($dataarr)) {

            return $dataarr[0]->country_id;
        } else {
            return 0;
        }
    }

    function account_auto_gen_Id() {
        return 'C' . mt_rand(100000, 999999);
    }

function sendEmail($to = '', $subject = '', $message = '', $from = '', $cc = '', $bcc = '', $data = '', $file = '') {
        // $config = Array(
        //     "protocol"  => "smtp",
        //     "smtp_host" => "ssl://smtp.gmail.com",
        //     "smtp_port" => 465,
        //     //"smtp_timeout" => "30",
        //     "smtp_user" => "cmetrictest@gmail.com",
        //     "smtp_pass" => "c-metrictest123",
        //     "charset"   => "utf-8",
        //     "mailtype"  => "html",
        //     "newline"   => "\r\n",
        // );

        $config = Array(
            "protocol"  => "smtp",
            "smtp_host" => "smtp.1and1.com",
            "smtp_port" => 587,
            //"smtp_timeout" => "30",
            "smtp_user" => "Developer5@c-metric.com",
            "smtp_pass" => "develop@55#",
            "charset"   => "utf-8",
            "mailtype"  => "html",
            "newline"   => "\r\n",
            "crlf"      => "\r\n",
        );
        /*
        $config = Array(
            "protocol"  => "smtp",
            "smtp_crypto" => "tls",
            "smtp_host" => "smtp.office365.com",
            "smtp_port" => 587,
            "smtp_user" => "noreply@newforestcare.co.uk",
            "smtp_pass" => "4aW2716$3A31i!p*",
            "smtp_auth" => true,
            "charset"   => "utf-8",
            "mailtype"  => "html",
            "newline"   => "\r\n",
            "crlf"      => "\r\n",
        );
*/
        

        $this->load->library ('email', $config);
        
        //$this->email->initialize($config);
        $this->email->subject ($subject);
        $this->email->message ($message);
        $this->email->from ($from, SITENAME);
        $this->email->to ($to);
        $this->email->cc ($cc);
        $this->email->bcc ($bcc);
        if (isset($file) && $file != "") {
            $this->email->attach($file); // attach file /path/to/file1.png
        }
        
        if ($this->email->send ()) {
            return 1;
        } else {
            return 0;
        }
        $this->email->clear (TRUE);
    }
    
    /*
      @Author : Mehul Patel
      @Desc   : Get User Role 
      @Input  :
      @Output :
      @Date   : 01/02/2016
     */
	public function getRoles(){
		
		//$sql = "select * From blzdsk_role_master WHERE role_id NOT IN ( select role_id from blzdsk_aauth_perm_to_group GROUP BY role_id )";
		
		$this->db->select('*')->from(ROLE_MASTER);
		$this->db->where('`role_id` NOT IN (SELECT `role_id` FROM '.$this->db->dbprefix(AAUTH_PERMS_TO_ROLE).')', NULL, FALSE);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
        
        /*
      @Author : Mehul Patel
      @Desc   : permsToRoleList
      @Input  :
      @Output :
      @Date   : 01/02/2016
     */
	public function permsToRoleList(){				
		$this->db->select('*');
		$this->db->from(ROLE_MASTER.' AS rm' );
		$this->db->join(AAUTH_PERMS_TO_ROLE.' AS authgroup','rm.role_id = authgroup.role_id', 'INNER');
		$this->db->group_by('rm.role_id'); 
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
        
        /*
      @Author : Mehul Patel
      @Desc   : checkRoleAssigne
      @Input  : $id
      @Output :
      @Date   : 01/02/2016
     */	
	public function checkRoleAssigne($id = null){
		if($id){
			$this->db->select('lg.role_id,lg.status as userStatus,rm.status as roleStatus');
			$this->db->from(LOGIN.' AS lg' );
			$this->db->join(ROLE_MASTER.' AS rm','lg.role_id = rm.role_id', 'LEFT');
			$this->db->where('rm.role_id', $id);
			$this->db->where('lg.is_delete = 0'); 
			$query = $this->db->get();
			$result = $query->result_array();
			//$result1 = $this->db->last_query();
			//echo $result1; exit;
			return $result;
		}
	}
        
    function medicalnumber() {
        return 'MI_' . mt_rand(1000000000, 9999999999);
    }
    /*get user*/
    public function get_user()
    {
        /*$user_id = $this->session->userdata('LOGGED_IN')['ID'];
        $role_type = $this->session->userdata('LOGGED_IN')['ROLE_TYPE'];
        $table = LOGIN.' as l';
        $wherestring = "l.login_id = ".$user_id." or r.parent_role = ".$role_type."";
        //$where = array("role_id !="=>1);
        //$fields = array("login_id,CONCAT(firstname,' ', lastname) as username");
        $fields = array("DISTINCT l.login_id,CONCAT(l.firstname,' ', l.lastname) as username");
        $join_tables = array(ROLE_MASTER . ' as r' => 'l.role_id= r.role_id');
        return $this->common_model->get_records($table, $fields,$join_tables, 'left', '', '', '','','l.login_id', 'asc','',$wherestring,'','','','','');*/
        $table = LOGIN . ' as l';
        $match = array('role_id !=' => '1','is_delete'=>0);
        $fields = array("l.login_id,concat(l.firstname,' ',l.lastname) as username");
        return $this->common_model->get_records($table, $fields, '', '', $match,'','','','','','','');
    }
     /*get all user*/
    public function get_all_user()
    {
        $table = LOGIN . ' as l';
        $match = array('role_id !=' => '1','is_delete'=>0);
        $fields = array("l.login_id,concat(l.firstname,' ',l.lastname) as username");
        return $this->common_model->get_records($table, $fields, '', '', $match,'','','','','','','');
    }
    
    public function get_medication($yp_id)
    {
        $table = MEDICATION.' as m';
        $wherestring = array('m.yp_id'=>$yp_id,'mch.is_archive'=>0);
        $fields = array("m.medication_name,m.medication_id");
        $join_tables = array(
            MEDICAL_CARE_HOME_TRANSECTION . ' as mch' => 'm.medication_id= mch.medication_id');
         return $this->common_model->get_records($table, $fields,$join_tables, 'left', '', '', '','','m.medication_id', 'asc','',$wherestring,'','','','','');

    }
    
    public function insert_do_previous_version($do_id,$yp_id)
    {
        $query = $this->db->query("INSERT INTO `nfc_do_previous_version` SELECT *  FROM `nfc_daily_observations` WHERE `do_id`=".$do_id." and "."`yp_id`=".$yp_id );
        return $this->db->insert_id();
    }
    
    
    /*get user*/
    public function get_single_user($id)
    {
        $table = LOGIN;
        $where = array("login_id "=>$id);
        $fields = array("login_id,CONCAT(firstname,' ', lastname) as username");
        return $this->common_model->get_records($table, $fields, '', '', '', '', '', '','','', '', $where);
    }
    
    public function get_single_medication($id){
        $table = MEDICATION;
        $where = array("medication_id "=>$id);
        $fields = array("medication_id,medication_name");
        return $this->common_model->get_records($table, $fields, '', '', '', '', '', '','','', '', $where);
    }
    /*
      @Author : Mehul Patel
      @Desc   : Get User Role 
      @Input  :
      @Output :
      @Date   : 01/02/2016
     */
    public function getRes($query=''){
        
        //$sql = "select * From blzdsk_role_master WHERE role_id NOT IN ( select role_id from blzdsk_aauth_perm_to_group GROUP BY role_id )";
        $query = $this->db->query($query);

        /*foreach ($query->result() as $row)
        {
                echo $row->title;
                echo $row->name;
                echo $row->body;
        }
        $this->db->query($query);
        $query = $this->db->get();*/
        $result = $query->result_array();
        return $result;
    }

}
