<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Documents extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session','m_pdf'));
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Documents Index Page
      @Input 	: yp id
      @Output	:
      @Date   : 14/07/2017
     */

    public function index($id,$careHomeId=0,$isArchive=0) {
		
		/*for care to care data by ghelani nikunj on 18/9/2018 for care to  care data get with the all previous care home*/
        if ($isArchive !== 0) {
            $temp = $this->common_model->get_records(PAST_CARE_HOME_INFO, array('move_date'), '', '', array("yp_id" => $id, "past_carehome" => $careHomeId));
            $data_care_home_detail = $this->common_model->get_records(PAST_CARE_HOME_INFO, array("*"), '', '', array("yp_id" => $id, "move_date <= " => $temp[0]['move_date']));
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
		
        if(is_numeric($id))
        {
			$fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($id,$fields);
            
            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = RECORD_PER_PAGE;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('doca_data');
            }

            $searchsort_session = $this->session->userdata('doca_data');
            //Sorting
            if (!empty($sortfield) && !empty($sortby)) {
                $data['sortfield'] = $sortfield;
                $data['sortby'] = $sortby;
            } else {
                if (!empty($searchsort_session['sortfield'])) {
                    $data['sortfield'] = $searchsort_session['sortfield'];
                    $data['sortby'] = $searchsort_session['sortby'];
                    $sortfield = $searchsort_session['sortfield'];
                    $sortby = $searchsort_session['sortby'];
                } else {
                    $sortfield = 'docs_id';
                    $sortby = 'desc';
                    $data['sortfield'] = $sortfield;
                    $data['sortby'] = $sortby;
                }
            }
            //Search text
            if (!empty($searchtext)) {
                $data['searchtext'] = $searchtext;
            } else {
                if (empty($allflag) && !empty($searchsort_session['searchtext'])) {
                    $data['searchtext'] = $searchsort_session['searchtext'];
                    $searchtext = $data['searchtext'];
                } else {
                    $data['searchtext'] = '';
                }
            }

            if (!empty($perpage) && $perpage != 'null') {
                $data['perpage'] = $perpage;
                $config['per_page'] = $perpage;
            } else {
                if (!empty($searchsort_session['perpage'])) {
                    $data['perpage'] = trim($searchsort_session['perpage']);
                    $config['per_page'] = trim($searchsort_session['perpage']);
                } else {
                    $config['per_page'] = $perpage;
                    $data['perpage'] = $perpage;
                }
            }
            //pagination configuration
            $config['first_link'] = 'First';
           if($isArchive==0){
                //need to change when client will approved functionality
            $config['base_url'] = base_url() . $this->viewname . '/index/' . $id;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 4;
                $uri_segment = $this->uri->segment(4);
            }
            }else{
                
                $config['base_url'] = base_url() . $this->viewname . '/index/' . $id.'/'.$careHomeId.'/'.$isArchive;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
				
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
				
                $config['uri_segment'] = 6;
				
                $uri_segment = $this->uri->segment(6);
            }
                
               
            }

            //Query
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $table = YP_DOCUMENTS . ' as docs';
            if($isArchive==0){
				$where = array("docs.yp_id" => $id);
				$fields = array("c.care_home_name,l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname,docs.*");
				$join_tables = array(LOGIN . ' as l' => 'l.login_id = docs.created_by',CARE_HOME . ' as c' => 'c.care_home_id= docs.care_home_id');
				if (!empty($searchtext)) {
                $searchtext = html_entity_decode(trim(addslashes($searchtext)));
                $match = '';
               
                 $where_search = '((docs.description LIKE "%' . $searchtext . '%") AND l.is_delete = "0" AND docs.yp_id = '.$id.')';
                $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);

                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1');
				} else {
					$data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
					
					$config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
				}
			}else{
				$where = array("docs.yp_id" => $id,"docs.care_home_id !="=>$data['YP_details'][0]['care_home']);
				$where_date="docs.created_date BETWEEN  '".$created_date."' AND '".$movedate."'";
				$fields = array("c.care_home_name,l.login_id, CONCAT(`firstname`,' ', `lastname`) as name, l.firstname, l.lastname,docs.*");
				$join_tables = array(LOGIN . ' as l' => 'l.login_id = docs.created_by',CARE_HOME . ' as c' => 'c.care_home_id= docs.care_home_id');
				
				if (!empty($searchtext)) {
                $searchtext = html_entity_decode(trim(addslashes($searchtext)));
                $match = '';
				$where_date="docs.created_date BETWEEN  '".$created_date."' AND '".$movedate."'";
             
                 $where_search = '((docs.description LIKE "%' . $searchtext . '%") AND l.is_delete = "0" AND docs.yp_id = '.$id.')';
                $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search,'','','','','',$where_date);

                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1','','',$where_date);
				} else {
					$data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where,'','','','','',$where_date);
					

					$config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1','','',$where_date);
				}
				
				
			}

            $this->ajax_pagination->initialize($config);
            $data['pagination'] = $this->ajax_pagination->create_links();
            $data['uri_segment'] = $uri_segment;
            $sortsearchpage_data = array(
                'sortfield' => $data['sortfield'],
                'sortby' => $data['sortby'],
                'searchtext' => $data['searchtext'],
                'perpage' => trim($data['perpage']),
                'uri_segment' => $uri_segment,
                'total_rows' => $config['total_rows']);

            if(empty($data['YP_details']))
            {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$id);
            }
            $data['ypid'] = $id;
			$data['is_archive_page'] = $isArchive;
            $data['careHomeId'] = $careHomeId;
            //get docs form
            $match = array('docs_form_id' => 1);
            $docs_forms = $this->common_model->get_records(DOCS_FORM, array("form_json_data"), '', '', $match);
            if (!empty($docs_forms)) {
                $data['form_data'] = json_decode($docs_forms[0]['form_json_data'], TRUE);
            }

            $this->session->set_userdata('doca_data', $sortsearchpage_data);
            setActiveSession('doca_data'); // set current Session active
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['crnt_view'] = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/documents/documents.js');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/ajaxlist', $data);
            } else {
                $data['main_content'] = '/documents';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        }
        else
        {
            show_404 ();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : create yp edit page
      @Input 	:
      @Output	:
      @Date   : 13/07/2017
     */

    public function create($id) {
        if(is_numeric($id))
        {
            //get docs form
            $match = array('docs_form_id' => 1);
            $docs_forms = $this->common_model->get_records(DOCS_FORM, '', '', '', $match);
            if (!empty($docs_forms)) {
                $data['docs_form_data'] = json_decode($docs_forms[0]['form_json_data'], TRUE);
            }
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($id,$fields);
            if(empty($data['YP_details']))
            {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$id);
            }
            $data['ypid'] = $id;
            $data['footerJs'][0] = base_url('uploads/custom/js/documents/documents.js');
            $data['crnt_view'] = $this->viewname;
            $data['main_content'] = '/edit';
            $data['header'] = array('menu_module' => 'YoungPerson');
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
        else
        {
            show_404 ();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Insert Documents
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */

    public function insert() {
		if (!validateFormSecret()) {
            redirect($_SERVER['HTTP_REFERER']);  //Redirect On Listing page
        }
        $postData = $this->input->post();
		//get YP information
            $fields = array("care_home");
            $data_yp_detail['YP_details'] = YpDetails($postData['yp_id'],$fields);
        unset($postData['submit_docsform']);
        //get docs form
        $match = array('docs_form_id' => 1);
        $docs_forms = $this->common_model->get_records(DOCS_FORM, array("form_json_data"), '', '', $match);
        if (!empty($docs_forms)) {
            $docs_form_data = json_decode($docs_forms[0]['form_json_data'], TRUE);
            $data = array();
            foreach ($docs_form_data as $row) {
                if (isset($row['name'])) {
                    if ($row['type'] == 'file') {
                        $filename = $row['name'];
                        if (!empty($_FILES[$filename]['name'][0])) {
                            /* common function replaced by Dhara Bhalala on 29/09/2018 */
                            createDirectory(array($this->config->item('docs_base_url'), $this->config->item('docs_base_big_url'), $this->config->item('docs_base_big_url') . '/' . $postData['yp_id']));

                            $file_view = $this->config->item('docs_img_url') . '/' . $postData['yp_id'];
                            $tmpFile = $_FILES[$filename]['name'][0];
                            /* File rename and removed message by Dhara Bhalala on 25/09/2018 as per client requirement */

                            //upload big image
                            $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/index/' . $postData['yp_id']);
                            //upload small image
                            $insertImagesData = array();
                            if (!empty($upload_data)) {
                                foreach ($upload_data as $imageFiles) {
                                    array_push($insertImagesData, $imageFiles['file_name']);
                                    if (!empty($insertImagesData)) {
                                        $images = implode(',', $insertImagesData);
                                    }
                                }
                                $data[$row['name']] = !empty($images) ? $images : '';
                            }
                        }
                    } else {
                         if ($row['type'] != 'button') {
                            if ($row['type'] == 'checkbox-group') {
                                $data[$row['name']] = !empty($postData[$row['name']]) ? implode(',', $postData[$row['name']]) : '';
                            } elseif ($row['type'] == 'textarea' && $row['subtype'] == 'tinymce') {
                                $data[$row['name']] = strip_slashes($postData[$row['name']]);
                            } elseif ($row['type'] == 'date') {
                                $data[$row['name']] = dateformat($postData[$row['name']]);
                            } elseif ($row['type'] == 'text') {
                              if($row['subtype'] == 'time'){
                                $data[$row['name']] = dbtimeformat($postData[$row['name']]);
                              }else{
                                $data[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                              }
                            } else {
                                $data[$row['name']] = strip_tags(strip_slashes($postData[$row['name']]));
                            }
                        }
                    }
                }
            }
        }
        if (!empty($postData['docs_id'])) {
            $data['docs_id'] = $postData['docs_id'];
            $data['yp_id'] = $postData['yp_id'];
            $main_user_data = $this->session->userdata('LOGGED_IN');
            $data['created_by'] = $main_user_data['ID'];
            $data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];

            $this->common_model->update(YP_DOCUMENTS, $data, array('docs_id' => $postData['docs_id'],'care_home_id'=>$data_yp_detail['YP_details'][0]['care_home']));
        } else {
            if (!empty($data)) {
                $data['yp_id'] = $postData['yp_id'];
                $main_user_data = $this->session->userdata('LOGGED_IN');
                $data['created_by'] = $main_user_data['ID'];
                $data['created_date'] = datetimeformat();
				$data['care_home_id'] = $data_yp_detail['YP_details'][0]['care_home'];
				
                $this->common_model->insert(YP_DOCUMENTS, $data);
                $data['docs_id'] = $this->db->insert_id();
                //Insert log activity
                $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                    'module_name' => MODULE_DOCS_MODULE, /* updated by Dhara Bhalala as naming conflicts bug on mantis tick. 9922 */
                    'module_field_name' => $images,
                    'type' => 1
                );
                log_activity($activity);
            }
        }

        if (!empty($data)) {
            redirect('/' . $this->viewname . '/save_docs/' . $data['docs_id'] . '/' . $data['yp_id']);
        } else {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>Please  insert documents.</div>");
            redirect('/' . $this->viewname . '/create/' . $postData['yp_id']);
        }
    }

     /*
      @Author : Ritesh Rana
      @Desc   : View Documents
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */

    public function view($id,$yp_id,$careHomeId=0,$isArchive=0) {
		$data['is_archive_page'] = $isArchive;
        $data['careHomeId'] = $careHomeId;
		
      if(is_numeric($id) && is_numeric($yp_id))
      {
            //get docs form
            $match = array('docs_form_id' => 1);
            $docs_forms = $this->common_model->get_records(DOCS_FORM, array("form_json_data"), '', '', $match);
            if (!empty($docs_forms)) {
                $data['form_data'] = json_decode($docs_forms[0]['form_json_data'], TRUE);
            }
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($yp_id,$fields);
            

            if(empty($data['YP_details']))
            {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$yp_id);
            }
            //get YP docs
            $match = array("docs_id"=>$id,"yp_id"=>$yp_id);
            $fields = array("*");
            $data['edit_data'] = $this->common_model->get_records(YP_DOCUMENTS, $fields, '', '', $match);
            
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $table = DOCUMENT_SIGNOFF.' as docs';
            $where = array("l.is_delete"=> "0","docs.yp_id" => $yp_id,"docs.docs_id"=>$id);
            $fields = array("docs.created_by,docs.created_date,docs.yp_id,docs.docs_id, CONCAT(`firstname`,' ', `lastname`) as name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id=docs.created_by');
            $group_by = array('created_by');
            $data['docs_signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);
            

            $table = DOCUMENT_SIGNOFF.' as docs';
            $where = array("docs.yp_id" => $yp_id,"docs.created_by" => $login_user_id,"docs.is_delete"=> "0","docs.docs_id"=>$id);
            $fields = array("docs.*");
            $data['check_signoff_data'] = $this->common_model->get_records($table,$fields,'','','','','','','','','',$where);

            $table = NFC_DOCS_SIGNOFF_DETAILS;
            $fields = array('*');
            $where = array('docs_id' => $id, 'yp_id' => $yp_id);
            $data['check_external_signoff_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where); 

            if(empty($data['edit_data']))
            {
              $msg = $this->lang->line('common_no_record_found');
              $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
              redirect('YoungPerson/view/'.$yp_id);
            }
            $data['doc_id'] = $id;
            $data['ypid'] = $data['edit_data'][0]['yp_id'];
            $data['footerJs'][0] = base_url('uploads/custom/js/documents/documents.js');
            $data['crnt_view'] = $this->viewname;
            $data['main_content'] = '/view';
            $data['header'] = array('menu_module' => 'YoungPerson');
            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
        else
        {
            show_404 ();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Save Documents form
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */

    public function save_docs($docs_id, $yp_id) {
      if(is_numeric($docs_id) && is_numeric($yp_id))
      {
        //get YP information
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($yp_id,$fields);
        if(empty($data['YP_details']))
        {
          $msg = $this->lang->line('common_no_record_found');
          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");   
          redirect('YoungPerson/view/'.$yp_id);
        }
        $match = array('docs_id' => $docs_id,'yp_id' => $yp_id);
        $docs_yp_data = $this->common_model->get_records(YP_DOCUMENTS, '', '', '', $match);
        if(empty($docs_yp_data))
        {
          $msg = $this->lang->line('common_no_record_found');
          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
          redirect('YoungPerson/view/'.$yp_id);
        }
        $data['yp_id'] = $yp_id;
        $data['docs_id'] = $docs_id;
        $data['main_content'] = '/save_docs';
        $data['header'] = array('menu_module' => 'YoungPerson');
        $this->parser->parse('layouts/DefaultTemplate', $data);
      }
      else
      {
        show_404 ();
      }
    }

    /*
      @Author : Niral Patel
      @Desc   : Read more
      @Input    : yp id
      @Output   :
      @Date   : 26/07/2017
     */
      public function readmore($id,$field)
      {
            $params['fields'] = [$field];
            $params['table'] = YP_DOCUMENTS;
            $params['match_and'] = 'docs_id=' . $id . '';
            $data['documents'] = $this->common_model->get_records_array($params);
            $data['field'] = $field;
            $this->load->view($this->viewname . '/readmore', $data);
      }
      
       /*
      @Author : Ritesh Rana
      @Desc   : Print Documents
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */
      
    public function DownloadPrint($docs_id,$yp_id,$section = NULL) {
        $data = [];
        $match = array('docs_form_id'=> 1);
        $docs_forms = $this->common_model->get_records(DOCS_FORM,array("form_json_data"), '', '', $match);
        if(!empty($docs_forms))
        {
            $data['docs_form_data'] = json_decode($docs_forms[0]['form_json_data'], TRUE);
        }
        //get YP information
        $table = YP_DETAILS.' as yp';
        $match = array("yp.yp_id"=>$yp_id);
        $fields = array("yp.yp_fname,yp.yp_lname,pa.placing_authority_id,pa.authority,pa.address_1,pa.town,pa.county,pa.postcode,sd.mobile,sd.email");
        $join_tables = array(PLACING_AUTHORITY . ' as pa' => 'pa.yp_id=yp.yp_id',SOCIAL_WORKER_DETAILS . ' as sd' => 'sd.yp_id=yp.yp_id');
        $data['YP_details']  = $this->common_model->get_records($table,$fields,$join_tables,'left','',$match,'','','','','','');
            
        //get pp yp data
        $match = array('yp_id'=> $yp_id,'docs_id'=> $docs_id);
        $data['edit_data'] = $this->common_model->get_records(YP_DOCUMENTS,'', '', '', $match);
        
         $data['crnt_view'] = $this->viewname;
        $data['ypid'] = $yp_id;
        
        $data['main_content'] = '/docspdf';
        $data['section'] = $section;
        $html = $this->parser->parse('layouts/PDFTemplate', $data);
        $pdfFileName = "docs" . $docs_id . ".pdf";
        $pdfFilePath = FCPATH . 'uploads/docs/';
        if (!is_dir(FCPATH . 'uploads/docs/')) {
            @mkdir(FCPATH . 'uploads/docs/', 0777, TRUE);
        }
        if (file_exists($pdfFilePath . $pdfFileName)) {
            unlink($pdfFilePath . $pdfFileName);
        }

        $this->load->library('m_pdf');

        if ($section == 'StorePDF') {
            ob_clean();
            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($pdfFilePath . $pdfFileName, 'F');
            return 1;
            die;
        } elseif ($section == 'print') {
            echo $html;exit;
        } else {
            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($pdfFileName, "D");
        }
    }

     /*
      @Author : Ritesh Rana
      @Desc   : User sign off Documents
      @Input  : ypid and docs id
      @Output   :
      @Date   : 14/07/2017
     */

    public function manager_review($yp_id,$docs_id) {

if (!empty($yp_id)) {
          $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
          $match = array('yp_id'=> $yp_id,'docs_id'=> $docs_id,'created_by'=>$login_user_id);
          $check_signoff_data = $this->common_model->get_records(DOCUMENT_SIGNOFF,'', '', '', $match);

        if(empty($check_signoff_data) > 0){
          $update_pre_data['docs_id'] = $docs_id;
          $update_pre_data['yp_id'] = $yp_id;
          $update_pre_data['created_date'] = datetimeformat();
          $update_pre_data['created_by'] = $login_user_id;
        if ($this->common_model->insert(DOCUMENT_SIGNOFF,$update_pre_data)) {
          $msg = $this->lang->line('successfully_docs_review');
          $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
          //Redirect On Listing page
        } else {
          // error
          $msg = $this->lang->line('error_msg');
          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
           //Redirect On Listing page
        }
      }else{
        $msg = $this->lang->line('already_docs_review');
      $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
      }
    }else{      
      $msg = $this->lang->line('error_msg');
      $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
    }

    redirect('/' . $this->viewname .'/view/'.$docs_id.'/'.$yp_id);
  }


 /*
      @Author : Ritesh Rana
      @Desc   : delete Documents
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */
    public function deleteDocs($yp_id, $docs_id) {
        //Delete Record From Database
        if (!empty($yp_id) && !empty($docs_id)) {
            $params['fields'] = ['input_single_file'];
            $params['table'] = YP_DOCUMENTS . ' as docs';
            $params['match_and'] = 'docs.docs_id=' . $docs_id . '';
            $cost_files = $this->common_model->get_records_array($params);
            $deleteImg = explode(',', $cost_files[0]['input_single_file']);
            if (count($deleteImg) > 0) {
                foreach ($deleteImg as $value) {
                    $path = $this->config->item('docs_img_url') . $yp_id . '/' . $value;
                    unlink(FCPATH . $path);
                }
            }
            $where = array('docs_id' => $docs_id);
            if ($this->common_model->delete(YP_DOCUMENTS, $where)) {
                //Insert log activity
                $activity = array(
                    'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                    'module_name' => MODULE_DOCS_MODULE, /* updated by Dhara Bhalala as naming conflicts bug on mantis tick. 9922 */
                    'module_field_name' => '',
                    'yp_id' => $yp_id,
                    'type' => 3
                );

                log_activity($activity);
                $msg = $this->lang->line('Deleted_Docs_Successfully');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                unset($docs_id);
            } else {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
        }
        redirect('Documents/index/' . $yp_id);
    }


 /*
      @Author : Ritesh Rana
      @Desc   : open popup for Signoff / Approval Documents
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */

    public function signoff($yp_id = '',$doc_id = '') {
        $this->formValidation();
		$match = array("yp_id"=>$yp_id);
        $fields = array("*");
        $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
        $data['care_home_id'] = $data['YP_details'][0]['care_home'];
        $main_user_data = $this->session->userdata('LOGGED_IN');
        if ($this->form_validation->run() == FALSE) {

            $data['footerJs'][0] = base_url('uploads/custom/js/documents/documents.js');
            $data['crnt_view'] = $this->viewname;

            $data['ypid']= $yp_id;
            $data['doc_id']= $doc_id;
            //get social info
            $table = SOCIAL_WORKER_DETAILS . ' as sw';
            $match = array("sw.yp_id" => $yp_id);
            $fields = array("sw.social_worker_id,sw.social_worker_firstname,sw.social_worker_surname");
            $data['social_worker_data'] = $this->common_model->get_records($table, $fields, '', '', $match);

            //get parent info
            $table = PARENT_CARER_INFORMATION . ' as pc';
            $match = array("pc.yp_id" => $yp_id,'pc.is_deleted' => 0);
            $fields = array("pc.parent_carer_id,pc.firstname,pc.surname");
            $data['parent_data'] = $this->common_model->get_records($table, $fields, '', '', $match);

            //Get Records From Login Table
            $data['form_action_path'] = $this->viewname . '/signoff';
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['validation'] = validation_errors();
            if (!empty($data['validation'])) {
                $data['main_content'] = '/signoff';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            } else {
                $this->load->view('/signoff', $data);
            }
        } else {
            $this->insertdata();
        }
    }

/*
      @Author : Ritesh Rana
      @Desc   : Insert Signoff / Approval Documents
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */
    public function insertdata() {
        $postdata = $this->input->post();

        $ypid = $postdata['ypid'];
        $doc_id = $postdata['doc_id'];
        $user_type = $postdata['user_type'];
        if($user_type == 'parent')
        {
          $parent_data['firstname'] = strip_tags($postdata['fname']);
          $parent_data['surname'] = strip_tags($postdata['lname']);
          $parent_data['relationship'] = strip_tags($postdata['relationship']);
          $parent_data['address'] = strip_tags($postdata['address']);
          $parent_data['contact_number'] = strip_tags($postdata['contact_number']);
          $parent_data['email_address'] = strip_tags($postdata['email']);
          $parent_data['yp_authorised_communication'] = strip_tags($postdata['yp_authorised_communication']);
          $parent_data['carer_authorised_communication'] = strip_tags($postdata['carer_authorised_communication']);
          $parent_data['yp_authorised_communication'] = strip_tags($postdata['yp_authorised_communication']);
          $parent_data['comments'] = strip_tags($postdata['comments']);
          $parent_data['created_date'] = datetimeformat();
          $parent_data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
          $parent_data['yp_id'] = $this->input->post('ypid');
          $parent_data['care_home_id'] = $this->input->post('care_home_id');
          $success = $this->common_model->insert(PARENT_CARER_INFORMATION, $parent_data);
          //Insert log activity
          $activity = array(
              'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
              'yp_id' => !empty($postdata['ypid']) ? $postdata['ypid'] : '',
              'module_name' => PARENT_CARER_DETAILS_YP,
              'module_field_name' => '',
              'type' => 1
          );
          log_activity($activity);
        }
            //Current Login detail
        $main_user_data = $this->session->userdata('LOGGED_IN');
        
            if (!validateFormSecret()) {
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
                redirect('YoungPerson/view/'.$ypid);
            }
            $data['crnt_view'] = $this->viewname;
			
			$match = array('docs_form_id'=> 1);
			 $formsdata = $this->common_model->get_records(DOCS_FORM,array("*"), '', '', $match);
			
			 //get pp yp data
			 $match = array('yp_id'=> $ypid,'docs_id'=>$doc_id);
			 $doc_yp_data = $this->common_model->get_records(YP_DOCUMENTS,array("*"), '', '', $match);
			 if(!empty($formsdata) && !empty($doc_yp_data))
			 {
				  $doc_form_data = json_decode($formsdata[0]['form_json_data'], TRUE);
				  $data = array();
				  $i=0;
				  foreach ($doc_form_data as $row) {
					  if(isset($row['name']))
					  {
						  if($row['type'] != 'button')
						  {
							  if($row['type'] == 'checkbox-group')
							  {
								$doc_form_data[$i]['value'] = implode(',',$doc_yp_data[0][$row['name']]);
								echo $doc_yp_data[0][$row['name']];
							  }else{
								$doc_form_data[$i]['value'] = str_replace("'", '"', $doc_yp_data[0][$row['name']]);
							  }
						  }
					  }
					  $i++;
				  }
			
				 }
				 
            $data = array(
                'user_type' => ucfirst($postdata['user_type']),
                'yp_id' => ucfirst($postdata['ypid']),
                'docs_id' => ucfirst($postdata['doc_id']),
                'form_json_data' =>json_encode($doc_form_data, TRUE),
                'fname' => ucfirst($postdata['fname']),
                'lname' => ucfirst($postdata['lname']),
                'email' => $postdata['email'],
                'key_data' => md5($postdata['email']),
                'created_date' => datetimeformat(),
                'created_by' => $main_user_data['ID'],
                'updated_by' => $main_user_data['ID'],
				'care_home_id' => $postdata['care_home_id'],
				
            );
            //Insert Record in Database
            if ($this->common_model->insert(NFC_DOCS_SIGNOFF_DETAILS, $data)) {

                $signoff_id = $this->db->insert_id();

				$table = DOCUMENT_SIGNOFF;
				$where = array("docs_id" => $postdata['doc_id'],"yp_id" => $ypid,"is_delete"=> "0");
				$fields = array("created_by,yp_id,docs_id,created_date");
				$group_by = array('created_by');
				$signoff_data = $this->common_model->get_records($table,$fields,'','','','','','','','',$group_by,$where);
			  
				if(!empty($signoff_data)){
					foreach ($signoff_data as $archive_value) {
						$update_arc_data['approval_docs_id'] = $signoff_id;
						$update_arc_data['yp_id'] = $archive_value['yp_id'];
						$update_arc_data['created_date'] = $archive_value['created_date'];
						$update_arc_data['created_by'] = $archive_value['created_by'];
						$this->common_model->insert(NFC_APPROVAL_DOCS_SIGNOFF,$update_arc_data);
					}
				}
			  
                $this->sendMailToRelation($data, $signoff_id); // send mail
                
                $msg = $this->lang->line('successfully_sign_off');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            } else {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
        
        redirect($this->viewname . '/view/'.$doc_id.'/'.$ypid);
    }


/*
      @Author : Ritesh Rana
      @Desc   : Send email for Signoff / Approval Documents
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */
    private function sendMailToRelation($data = array(), $signoff_id) {

        if (!empty($data)) {
            if(!empty($data['yp_id']))
            {
                $match = array("yp_id" => $data['yp_id']);
                $fields = array("concat(yp_fname,' ',yp_lname) as yp_name");
                $YP_details = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            }
            $yp_name = !empty($YP_details[0]['yp_name'])?$YP_details[0]['yp_name']:"";
            /* Send Created Customer password with Link */
            $toEmailId = $data['email'];
            $customerName = $data['fname'] . ' ' . $data['lname'];

            $email = md5($toEmailId);
            $loginLink = base_url('Documents/signoffData/' . $data['yp_id'] . '/' . $signoff_id . '/' . $email);

            $find = array('{NAME}','{LINK}');

            $replace = array(
                'NAME' => $customerName,
                'LINK' => $loginLink,
            );
            
            $emailSubject = 'Welcome to NFCTracker';
                    $emailBody = '<div>'
                    . '<p>Hello {NAME} ,</p> '
                    . '<p>Please find Document for '.$yp_name.' for your approval.</p> '
                    . "<p>For security purposes, Please do not forward this email on to any other person. It is for the recipient only and if this is sent in error please advise itsupport@newforestcare.co.uk and delete this email. This link is only valid for ".REPORT_EXPAIRED_HOUR.", should this not be signed off within ".REPORT_EXPAIRED_HOUR." of recieving then please request again</p>"
                    . '<p> <a href="{LINK}">click here</a> </p> '
                    . '<div>';        

            $finalEmailBody = str_replace($find,$replace,$emailBody);

            return $this->common_model->sendEmail($toEmailId, $emailSubject, $finalEmailBody, FROM_EMAIL_ID);
        }
        return true;
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Documents form Validation
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */
    public function formValidation($id = null) {
        $this->form_validation->set_rules('fname', 'Firstname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('lname', 'Lastname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
        
    }

/*
      @Author : Ritesh Rana
      @Desc   : View Signoff / Approval Documents
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */
    public function signoffData($yp_id,$id,$email) {

     if(is_numeric($id) && is_numeric($yp_id) && !empty($email))
       {
          $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
          $match = array('yp_id'=> $yp_id,'docs_signoff_details_id'=>$id,'key_data'=> $email,'status'=>'inactive');
          $check_signoff_data = $this->common_model->get_records(NFC_DOCS_SIGNOFF_DETAILS,array("*"), '', '', $match);
        if(!empty($check_signoff_data)){
              $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'].REPORT_EXPAIRED_DAYS));
              if(strtotime(datetimeformat()) <= strtotime($expairedDate))
              {
                    $data['form_data'] = json_decode($check_signoff_data[0]['form_json_data'], TRUE);

                      //get YP information
                      $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
                      $data['YP_details'] = YpDetails($yp_id,$fields);
                      if(empty($data['YP_details']))
                      {
                          $msg = $this->lang->line('common_no_record_found');
                          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                          redirect('YoungPerson/view/'.$yp_id);
                      }

                      $table = NFC_APPROVAL_DOCS_SIGNOFF.' as ras';
                      $where = array("l.is_delete"=> "0","ras.yp_id" => $yp_id,"ras.is_delete"=> "0","approval_docs_id"=>$id);
                      $fields = array("ras.created_by,ras.created_date,ras.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
                      $join_tables = array(LOGIN . ' as l' => 'l.login_id=ras.created_by');
                      $group_by = array('created_by');
                      $data['signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);
                        
                    $data['key_data']= $email;
                    $data['ypid'] = $yp_id;
                    $data['doc_id'] = $check_signoff_data[0]['docs_id'];
                    $data['signoff_id'] = $id;
                    
                    $data['footerJs'][0] = base_url('uploads/custom/js/documents/documents.js');
                    $data['crnt_view'] = $this->viewname;
                    $data['header'] = array('menu_module' => 'YoungPerson');
                    $data['main_content'] = '/signoff_view';
                    $this->parser->parse('layouts/DefaultTemplate', $data);
            }
            else
            {
                $msg = lang('link_expired');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>")
                ;
                $this->load->view('successfully_message');
            }
      }else{
            $msg = $this->lang->line('already_docs_review');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            $this->load->view('successfully_message');
      }
      }
      else
      {
          show_404 ();
      }
    } 

/*
      @Author : Ritesh Rana
      @Desc   : Signoff Review Signoff / Approval Documents
      @Input    :
      @Output   :
      @Date   : 14/07/2017
     */
    public function signoff_review_data($yp_id,$docs_id,$email) {
    if (!empty($yp_id) && !empty($docs_id) && !empty($email)) {
          $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
          $match = array('yp_id'=> $yp_id,'docs_signoff_details_id'=>$docs_id,'key_data'=> $email,'status'=>'inactive');
          $check_signoff_data = $this->common_model->get_records(NFC_DOCS_SIGNOFF_DETAILS,array("*"), '', '', $match);
		  
        if(!empty($check_signoff_data)){ 
          $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'].REPORT_EXPAIRED_DAYS));
          if(strtotime(datetimeformat()) <= strtotime($expairedDate))
          {
                $u_data['status'] = 'active';
                $u_data['modified_date'] = datetimeformat();
                $u_data['modified_date'] = datetimeformat();
                $success =$this->common_model->update(NFC_DOCS_SIGNOFF_DETAILS,$u_data,array('docs_signoff_details_id'=> $docs_id,'yp_id'=> $yp_id,'key_data'=> $email));
                if ($success) {

                $msg = $this->lang->line('successfully_docs_review');
                $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-success text-center'>$msg</div>");
                } else {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>");
                }
            }
            else
            {
              $msg = lang('link_expired');
              $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>")
              ;
            }
      }else{
        $msg = $this->lang->line('already_docs_review');
      $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>");

      }
    }else{      
      $msg = $this->lang->line('error_msg');
      $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>");
      
    }
	$this->load->view('successfully_message');
  }
    /*
      @Author : Niral Patel
      @Desc   : get user type detail
      @Input  :
      @Output :
      @Date   : 28/03/2018
     */
    public function getUserTypeDetail() {
      $postdata  = $this->input->post();
      $user_type = !empty($postdata['user_type'])?$postdata['user_type']:''; 
      $id = $postdata['id']; 
      $ypid      = $postdata['ypid']; 
      $table = YP_DETAILS . ' as yp';
      $match = "yp.yp_id = " . $ypid;
      $fields = array("yp.*,swd.social_worker_firstname,swd.social_worker_surname,swd.landline,swd.mobile,swd.email,swd.senior_social_worker_firstname,swd.senior_social_worker_surname,oyp.pen_portrait,swd.other,pc.firstname,pc.surname,pc.relationship,pc.address,pc.contact_number,pc.email_address,pc.yp_authorised_communication,pc.carer_authorised_communication,pc.comments");
      $join_tables = array(SOCIAL_WORKER_DETAILS . ' as swd' => 'swd.yp_id= yp.yp_id', PARENT_CARER_INFORMATION . ' as pc' => 'pc.yp_id= yp.yp_id', OVERVIEW_OF_YOUNG_PERSON . ' as oyp' => 'oyp.yp_id= yp.yp_id');

      $data['editRecord'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
      if($user_type == 'parent_data')
      {
          
          //get parent info
          $table = PARENT_CARER_INFORMATION . ' as pc';
          $match = array("pc.parent_carer_id" => $id,'pc.is_deleted' => 0);
          $fields = array("pc.*");
          $data['parent_data'] = $this->common_model->get_records($table, $fields, '', '', $match);
          $data['editRecord'][0]['fname'] = $data['editRecord'][0]['firstname'];
          $data['editRecord'][0]['lname'] = $data['editRecord'][0]['surname'];
          $data['editRecord'][0]['email_id'] = $data['editRecord'][0]['email_address'];
      }
      else if($user_type == 'social_data')
      {
          //get social info
          $table = SOCIAL_WORKER_DETAILS . ' as sw';
          $match = array("sw.social_worker_id" => $id);
          $fields = array("sw.*");
          $data['social_worker_data'] = $this->common_model->get_records($table, $fields, '', '', $match);
          $data['editRecord'][0]['fname'] = $data['editRecord'][0]['social_worker_firstname'];
          $data['editRecord'][0]['lname'] = $data['editRecord'][0]['social_worker_surname'];
          $data['editRecord'][0]['email_id'] = $data['editRecord'][0]['email'];
      }
     
      $data['user_type'] = $user_type;
      $this->load->view($this->viewname . '/signoff_ajax', $data);

    }
    
    /*
      @Author : Tithi Patel
      @Desc   : get list of external approver
      @Input 	: yp_id, wr_id
      @Output	:
      @Date   : 12/04/2018
     */
    public function reportReviewedBy($doc_id, $yp_id) {
        if (is_numeric($doc_id) && is_numeric($yp_id)) { 
            
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($yp_id,$fields);

            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = RECORD_PER_PAGE;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('wr_data');
            }

            $searchsort_session = $this->session->userdata('wr_data');
            //Sorting
            if (!empty($sortfield) && !empty($sortby)) {
                $data['sortfield'] = $sortfield;
                $data['sortby'] = $sortby;
            } else {
                if (!empty($searchsort_session['sortfield'])) {
                    $data['sortfield'] = $searchsort_session['sortfield'];
                    $data['sortby'] = $searchsort_session['sortby'];
                    $sortfield = $searchsort_session['sortfield'];
                    $sortby = $searchsort_session['sortby'];
                } else {
                    $sortfield = 'dc.created_date';
                    $sortby = 'desc';
                    $data['sortfield'] = $sortfield;
                    $data['sortby'] = $sortby;
                }
            }
            //Search text
            if (!empty($searchtext)) {
                $data['searchtext'] = $searchtext;
            } else {
                if (empty($allflag) && !empty($searchsort_session['searchtext'])) {
                    $data['searchtext'] = $searchsort_session['searchtext'];
                    $searchtext = $data['searchtext'];
                } else {
                    $data['searchtext'] = '';
                }
            }

            if (!empty($perpage) && $perpage != 'null') {
                $data['perpage'] = $perpage;
                $config['per_page'] = $perpage;
            } else {
                if (!empty($searchsort_session['perpage'])) {
                    $data['perpage'] = trim($searchsort_session['perpage']);
                    $config['per_page'] = trim($searchsort_session['perpage']);
                } else {
                    $config['per_page'] = RECORD_PER_PAGE;
                    $data['perpage'] = RECORD_PER_PAGE;
                }
            }
            //pagination configuration
            $config['first_link'] = 'First';
            $config['base_url'] = base_url() . $this->viewname . '/reportReviewedBy/' . $doc_id . '/' . $yp_id;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 5;
                $uri_segment = $this->uri->segment(5);
            }
            
            $table = NFC_DOCS_SIGNOFF_DETAILS . ' AS dc';
            $fields = array('yd.care_home_id,c.care_home_name,dc.*, CONCAT(l.firstname," ", l.lastname) as sent_by, CONCAT(dc.fname," ", dc.lname) as review_by');
            $where = array('dc.docs_id' => $doc_id, 'dc.yp_id' => $yp_id);
            $join_tables = array(LOGIN . ' as l' => 'l.login_id=dc.created_by',YP_DOCUMENTS . ' as yd' => 'yd.docs_id= dc.docs_id',CARE_HOME . ' as c' => 'c.care_home_id= yd.care_home_id');
            
            if (!empty($searchtext)) {
                $searchtext = html_entity_decode(trim(addslashes($searchtext)));
                $match = '';
                $where_search = '((l.firstname LIKE "%' . $searchtext . '%" OR l.lastname LIKE "%' . $searchtext . '%" OR dc.fname LIKE "%' . $searchtext . '%" OR dc.lname LIKE "%' . $searchtext . '%" OR dc.created_date LIKE "%' . $searchtext . '%") AND l.is_delete = "0" AND dc.docs_id = "'. $ypc_id . '" AND dc.yp_id = "' .$yp_id .'")';

                $data['doc_signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);

                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1');
            } else {
                $data['doc_signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);

                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
            }
            
            $this->ajax_pagination->initialize($config);
            $data['pagination'] = $this->ajax_pagination->create_links();
            
            $data['uri_segment'] = $uri_segment;
            $sortsearchpage_data = array(
                'sortfield' => $data['sortfield'],
                'sortby' => $data['sortby'],
                'searchtext' => $data['searchtext'],
                'perpage' => trim($data['perpage']),
                'uri_segment' => $uri_segment,
                'total_rows' => $config['total_rows']);
            
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/'.$yp_id);
            }
            $data['ypid'] = $yp_id;
            $data['doc_id'] = $doc_id;
            $data['footerJs'][0] = base_url('uploads/custom/js/documents/documents.js');
            $data['crnt_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'YoungPerson');
            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/reviewed_ajaxlist', $data);
            } else {
                $data['main_content'] = '/reviewed_by';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : reviewed Concern form
      @Input    :
      @Output   :
      @Date   : 12/04/2018
     */
    
    public function reviewedConcern($doc_id, $yp_id, $signoffid){
        if(is_numeric($doc_id) && is_numeric($yp_id) && is_numeric($signoffid))
        {
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($yp_id,$fields);

            $table = NFC_DOCS_SIGNOFF_DETAILS;
            $fields = array('form_json_data');
            $where = array('docs_id' => $doc_id, 'yp_id' => $yp_id);
            $data['external_signoff_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            if(!empty($data['external_signoff_data'])){
                $data['doc_form_data'] = json_decode($data['external_signoff_data'][0]['form_json_data'], TRUE);
            }
            $table = NFC_APPROVAL_DOCS_SIGNOFF.' as ras';
              $where = array("l.is_delete"=> "0","ras.yp_id" => $yp_id,"ras.is_delete"=> "0","approval_docs_id"=>$signoffid);
              $fields = array("ras.created_by,ras.created_date,ras.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
              $join_tables = array(LOGIN . ' as l' => 'l.login_id=ras.created_by');
              $group_by = array('created_by');
              $data['signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);
            //check data exist or not
            if(empty($data['YP_details']))
            {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/'.$yp_id);
            }
            $data['ypid'] = $yp_id;
            $data['doc_id'] = $doc_id;
            $data['footerJs'][0] = base_url('uploads/custom/js/documents/documents.js');
            $data['crnt_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/view_reviewed_doc';
            $this->parser->parse('layouts/DefaultTemplate', $data);
          }
          else
          {
              show_404 ();
          }
    }
    /*
      @Author : Niral Patel
      @Desc   : external approve view data
      @Input  :
      @Output :
      @Date   : 12/04/2018
     */
    
    public function resend_external_approval($signoff_id,$ypid,$docid) {
      $match = array('docs_signoff_details_id'=>$signoff_id);
      $signoff_data = $this->common_model->get_records(NFC_DOCS_SIGNOFF_DETAILS,array("yp_id,fname,lname,email"), '', '', $match);
      if(!empty($signoff_data))
      {
        $data = array(
                'yp_id' => ucfirst($signoff_data[0]['yp_id']),
                'fname' => ucfirst($signoff_data[0]['fname']),
                'lname' => ucfirst($signoff_data[0]['lname']),
                'email' => $signoff_data[0]['email']
            );
        $sent = $this->sendMailToRelation($data,$signoff_id); // send mail
        if($sent)
        {
          $u_data['created_date'] = datetimeformat();
          $u_data['modified_date'] = NULL;
          $success =$this->common_model->update(NFC_DOCS_SIGNOFF_DETAILS,$u_data,array('docs_signoff_details_id'=> $signoff_id));
          $msg = $this->lang->line('mail_sent_successfully');
          $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
        else
        {
          $msg = $this->lang->line('error');
          $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        }
      }
      redirect($this->viewname.'/reportReviewedBy/' . $docid.'/'.$ypid);
     }
	  /*
      @Author : Nikunj Ghelani
      @Desc   : PDF data
      @Input  :
      @Output :
      @Date   : 16/07/2018
     */
	 
    public function DownloadPdf($yp_id,$id) {
		 
		  if(is_numeric($id) && is_numeric($yp_id))
       {
          $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
          $match = array('yp_id'=> $yp_id,'docs_signoff_details_id'=>$id,'status'=>'inactive');
          $check_signoff_data = $this->common_model->get_records(NFC_DOCS_SIGNOFF_DETAILS,array("created_date,form_json_data,docs_id"), '', '', $match);
        if(!empty($check_signoff_data)){
              $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'].REPORT_EXPAIRED_DAYS));
              if(strtotime(datetimeformat()) <= strtotime($expairedDate))
              {
                    $data['form_data'] = json_decode($check_signoff_data[0]['form_json_data'], TRUE);

                      //get YP information
                      $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
                      $data['YP_details'] = YpDetails($yp_id,$fields);

                      if(empty($data['YP_details']))
                      {
                          $msg = $this->lang->line('common_no_record_found');
                          $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                          redirect('YoungPerson/view/'.$yp_id);
                      }


                      $table = NFC_APPROVAL_DOCS_SIGNOFF.' as ras';
                      $where = array("l.is_delete"=> "0","ras.yp_id" => $yp_id,"ras.is_delete"=> "0","approval_docs_id"=>$id);
                      $fields = array("ras.created_by,ras.created_date,ras.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
                      $join_tables = array(LOGIN . ' as l' => 'l.login_id=ras.created_by');
                      $group_by = array('created_by');
                      $data['signoff_data'] = $this->common_model->get_records($table,$fields,$join_tables,'left','','','','','','',$group_by,$where);
                    
                      
                    $data['key_data']= $email;
                    $data['ypid'] = $yp_id;
                    $data['doc_id'] = $check_signoff_data[0]['docs_id'];
                    $data['signoff_id'] = $id;
                    
                    $data['footerJs'][0] = base_url('uploads/custom/js/documents/documents.js');
                    $data['crnt_view'] = $this->viewname;
                    $pdfFileName = "documents_pdf.pdf";
					$PDFInformation['yp_details'] = $data['YP_details'][0];
					$PDFInformation['edit_data'] = $data['edit_data'][0]['modified_date'];
					
					
					$PDFHeaderHTML  = $this->load->view('documents_pdfHeader', $PDFInformation,true);
					
					$PDFFooterHTML  = $this->load->view('documents_pdfFooter', $PDFInformation,true);
					
					//Set Header Footer and Content For PDF
					$this->m_pdf->pdf->mPDF('utf-8','A4','','','10','10','45','25');
			
					$this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
					$this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);                    
					$data['main_content'] = '/documents_pdf';
					$html = $this->parser->parse('layouts/PdfDataTemplate', $data);
					
					/*remove*/
					$this->m_pdf->pdf->WriteHTML($html);
					//Store PDF in individual_strategies Folder
					$this->m_pdf->pdf->Output($pdfFileName, "D");
            }
            else
            {
                $msg = lang('link_expired');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>")
                ;
                $this->load->view('successfully_message');
            }
      }else{
            $msg = $this->lang->line('already_docs_review');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            $this->load->view('successfully_message');
      }
      }
      else
      {
          show_404 ();
      }
		 
		 
	 }
}
