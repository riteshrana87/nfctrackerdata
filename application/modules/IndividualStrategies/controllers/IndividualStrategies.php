<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class IndividualStrategies extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session', 'm_pdf'));
    }

    /*
      @Author : Niral Patel
      @Desc   : IndividualStrategies Index Page
      @Input 	: yp id
      @Output	:
      @Date   : 12/07/2017
     */

    public function index($id, $careHomeId = 0, $isArchive = 0) {

        $data['is_archive_page'] = $isArchive;
        $data['careHomeId'] = $careHomeId;
        /* for care to care data
          ghelani nikunj
          24/9/2018
          care to  care data get with the all previous care home */
        if ($isArchive !== 0) {
            $temp = $this->common_model->get_records(PAST_CARE_HOME_INFO, array('move_date'), '', '', array("yp_id" => $id, "past_carehome" => $careHomeId));
            $careHomeDetails = $this->common_model->get_records(PAST_CARE_HOME_INFO, array("*"), '', '', array("yp_id" => $id, "move_date <= " => $temp[0]['move_date']));
            $enterDate = $moveDate = '';
            $totalCareHome = count($careHomeDetails);
            if ($totalCareHome >= 1) {
                $enterDate = $careHomeDetails[0]['enter_date'];
                $moveDate = $careHomeDetails[$totalCareHome - 1]['move_date'];
            } elseif ($totalCareHome == 0) {
                $enterDate = $careHomeDetails[0]['enter_date'];
                $moveDate = $careHomeDetails[0]['move_date'];
            }
        }
        if (is_numeric($id)) {
            //get pp form
            $match = array('is_form_id' => 1);
            $formsdata = $this->common_model->get_records(IS_FORM, '', '', '', $match);
            if (!empty($formsdata)) {
                $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($id,$fields);

            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/' . $id);
            }
            //get ra yp data
            $match = array('yp_id' => $id, 'is_previous_version' => 0);
            $data['edit_data'] = $this->common_model->get_records(INDIVIDUAL_STRATEGIES, '', '', '', $match);

            $table = NFC_AMENDMENTS . ' as do';
            $match = array('do.yp_id' => $id);
            $fields = array("do.*,CONCAT(l.firstname,' ', l.lastname) as create_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.staff_initials');
            $data['item_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);

            $table = NFC_CURRENT_PROTOCOLS_IN_PLACE . ' as cpip';
            $match = array('cpip.yp_id' => $id);
            $fields = array("cpip.*,CONCAT(l.firstname,' ', l.lastname) as create_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= cpip.staff_initials');
            $data['protocols_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);

            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $table = INDIVIDUAL_MEETING_SIGNOFF . ' as ims';
            $where = array("l.is_delete" => "0", "ims.yp_id" => $id, "ims.is_delete" => "0");
            $fields = array("ims.created_by,ims.created_date,ims.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id=ims.created_by');
            $group_by = array('created_by');
            $data['ra_signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);

            $table = INDIVIDUAL_MEETING_SIGNOFF . ' as ims';
            $where = array("ims.yp_id" => $id, "ims.created_by" => $login_user_id, "ims.is_delete" => "0");
            $fields = array("ims.individual_meeting_signoff_id,ims.individual_meeting_id,ims.created_date");
            $data['check_signoff_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            //get is old yp data
            $match = array('yp_id' => $id, 'is_previous_version' => 1);
            $data['prev_edit_data'] = $this->common_model->get_records(INDIVIDUAL_STRATEGIES, '', '', '', $match);

            $match = array('yp_id' => $id, 'status' => 1);
            $prev_archive = $this->common_model->get_records(INDIVIDUAL_STRATEGIES_ARCHIVE, '', '', '', $match, '', '1', '', 'is_archive_id', 'desc');

            if (!empty($prev_archive)) {
                $is_archive_id = $prev_archive[0]['is_archive_id'];
                $table = NFC_ARCHIVE_AMENDMENTS . ' as do';
                $match = array('do.yp_id' => $id, 'do.archive_individual_meeting_id' => $is_archive_id);
                $fields = array("do.*,CONCAT(l.firstname,' ', l.lastname) as create_name");
                $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.staff_initials');
                $data['item_details_archive'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);

                $table = NFC_ARCHIVE_CURRENT_PROTOCOLS_IN_PLACE . ' as cpip';
                $match = array('cpip.yp_id' => $id, 'cpip.archive_individual_meeting_id' => $is_archive_id);
                $fields = array("cpip.*,CONCAT(l.firstname,' ', l.lastname) as create_name");
                $join_tables = array(LOGIN . ' as l' => 'l.login_id= cpip.staff_initials');
                $data['protocols_details_archive'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
            }

            //get external approve
            $table = NFC_IS_SIGNOFF_DETAILS;
            $fields = array('form_json_data,user_type,key_data,email');
            $where = array('yp_id' => $id);
            $data['check_external_signoff_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            $data['ypid'] = $id;
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['footerJs'][0] = base_url('uploads/custom/js/IndividualStrategies/IndividualStrategies.js');
            $data['crnt_view'] = $this->viewname;
            $data['main_content'] = '/view';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : create RiskAssesment edit page
      @Input 	:
      @Output	:
      @Date   : 12/07/2017
     */
      
    public function edit($id) {
        if (is_numeric($id)) {
            //get pp form
            $match = array('is_form_id' => 1);
            $pp_forms = $this->common_model->get_records(IS_FORM, '', '', '', $match);
            if (!empty($pp_forms)) {
                $data['form_data'] = json_decode($pp_forms[0]['form_json_data'], TRUE);
            }
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($id,$fields);

            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/' . $id);
            }
            //get pp yp data
            $match = array('yp_id' => $id, 'is_previous_version' => 0);
            $data['edit_data'] = $this->common_model->get_records(INDIVIDUAL_STRATEGIES, '', '', '', $match);
            //get amendments data
            $table = NFC_AMENDMENTS;
            $match = array('yp_id' => $id);
            $field = array('*');
            $data['item_details'] = $this->common_model->get_records($table, $field, '', '', $match);
            //get Current Protocols In Place data
            $table = NFC_CURRENT_PROTOCOLS_IN_PLACE;
            $match = array('yp_id' => $id);
            $field = array('*');
            $data['protocols_details'] = $this->common_model->get_records($table, $field, '', '', $match);

            $url_data = base_url('IndividualStrategies/edit/' . $id);
            $match = array('url_data' => $url_data);
            $data['check_edit_permission'] = $this->common_model->get_records(CHECK_EDIT_URL, '*', '', '', $match);
            if (count($data['check_edit_permission']) > 0) {
                $in_time = date('Y-m-d h:i:s', strtotime($data['check_edit_permission'][0]['datetime']));
                $currnt_time = date('Y-m-d h:i:s');
                if (strtotime($in_time) > strtotime($currnt_time)) {
                    $now = strtotime($in_time) - strtotime($currnt_time);
                } else {
                    $now = strtotime($currnt_time) - strtotime($in_time);
                }

                $secs = floor($now % 60);

                if ($secs >= 10) {
                    $data['ypid'] = $id;
                    $data['footerJs'][0] = base_url('uploads/custom/js/IndividualStrategies/IndividualStrategies.js');
                    $data['footerJs'][1] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
                    $data['header'] = array('menu_module' => 'YoungPerson');
                    $data['crnt_view'] = $this->viewname;
                    $data['main_content'] = '/edit';
                    $this->parser->parse('layouts/DefaultTemplate', $data);
                } else {
                    $msg = $this->lang->line('check_is_user_update_data');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                    redirect('/' . $this->viewname . '/index/' . $id);
                }
            } else {
                $data['ypid'] = $id;
                $data['footerJs'][0] = base_url('uploads/custom/js/IndividualStrategies/IndividualStrategies.js');
                $data['footerJs'][1] = base_url('uploads/custom/js/formbuilder/formbuilder.js');
                $data['header'] = array('menu_module' => 'YoungPerson');
                $data['crnt_view'] = $this->viewname;
                $data['main_content'] = '/edit';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }

    /*
      @Author : Ritesh rana
      @Desc   : Insert is form
      @Input    :
      @Output   :
      @Date   : 26/02/2018
     */

    public function insert() {
        if (!validateFormSecret()) {
            redirect($_SERVER['HTTP_REFERER']);  //Redirect On Listing page
        }
        $postData = $this->input->post();
        $match = array("yp_id" => $postData['yp_id']);
        $fields = array("care_home");
        $data_care_home_id['YP_details_care_home_id'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
        unset($postData['submit_isform']);
        //get pp form
        $match = array('is_form_id' => 1);
        $pp_forms = $this->common_model->get_records(IS_FORM, '', '', '', $match);

        if (!empty($pp_forms)) {
            $pp_form_data = json_decode($pp_forms[0]['form_json_data'], TRUE);
            $data = array();
            foreach ($pp_form_data as $row) {
                if (isset($row['name'])) {
                    if ($row['type'] == 'file') {
                        $filename = $row['name'];
                        //get image previous image
                        $match = array('individual_strategies_id' => $postData['individual_strategies_id'], 'yp_id' => $postData['yp_id'], 'is_previous_version' => 0);
                        $pp_yp_data = $this->common_model->get_records(INDIVIDUAL_STRATEGIES, array('`' . $row['name'] . '`'), '', '', $match);
                        //delete img
                        if (!empty($postData['hidden_' . $row['name']])) {
                            $delete_img = explode(',', $postData['hidden_' . $row['name']]);
                            $db_images = explode(',', $pp_yp_data[0][$filename]);
                            $differentedImage = array_diff($db_images, $delete_img);
                            $pp_yp_data[0][$filename] = !empty($differentedImage) ? implode(',', $differentedImage) : '';
                        }
                        if (!empty($_FILES[$filename]['name'][0])) {
                            /* common function replaced by Dhara Bhalala on 29/09/2018 */
                            createDirectory(array($this->config->item('is_base_url'), $this->config->item('is_base_big_url'), $this->config->item('is_base_big_url') . '/' . $postData['yp_id']));

                            $file_view = $this->config->item('is_img_url') . $postData['yp_id'];
                            //upload big image
                            $upload_data = uploadImage($filename, $file_view, '/' . $this->viewname . '/index/' . $postData['yp_id']);


                            //upload small image
                            $insertImagesData = array();
                            if (!empty($upload_data)) {
                                foreach ($upload_data as $imageFiles) {
                                    /* common function replaced by Dhara Bhalala on 29/09/2018 */
                                    createDirectory(array($this->config->item('is_base_small_url'), $this->config->item('is_base_small_url') . '/' . $postData['yp_id']));

                                    /* condition added by Dhara Bhalala on 21/09/2018 to solve GD lib error */
                                    if ($imageFiles['is_image'])
                                        $a = do_resize($this->config->item('is_img_url') . $postData['yp_id'], $this->config->item('is_img_url_small') . $postData['yp_id'], $imageFiles['file_name']);
                                    array_push($insertImagesData, $imageFiles['file_name']);
                                    if (!empty($insertImagesData)) {
                                        $images = implode(',', $insertImagesData);
                                    }
                                }
                                if (!empty($pp_yp_data[0][$filename])) {
                                    $images .=',' . $pp_yp_data[0][$filename];
                                }
                                $data[$row['name']] = !empty($images) ? $images : '';
                            }
                        } else {
                            $data[$row['name']] = !empty($pp_yp_data[0][$filename]) ? $pp_yp_data[0][$filename] : '';
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
                                if ($row['subtype'] == 'time') {
                                    $data[$row['name']] = dbtimeformat($postData[$row['name']]);
                                } else {
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
        //get current data
        $match = array('yp_id' => $postData['yp_id'], 'is_previous_version' => 0);
        $pp_new_data = $this->common_model->get_records(INDIVIDUAL_STRATEGIES, array('*'), '', '', $match);

        //get pp yp data
        $match = array('yp_id' => $postData['yp_id'], 'is_previous_version' => 1);
        $previous_data = $this->common_model->get_records(INDIVIDUAL_STRATEGIES, array('*'), '', '', $match);

        if (!empty($pp_new_data)) {
            $update_pre_data = array();
            $updated_field = array();
            $n = 0;
            if (!empty($pp_form_data)) {
                foreach ($pp_form_data as $row) {
                    if (isset($row['name'])) {
                        if ($row['type'] != 'button') {
                            if (!empty($pp_new_data)) {
                                if ($postData[$row['name']] != $pp_new_data[0][$row['name']]) {
                                    $updated_field[] = $row['label'];
                                    $n++;
                                }
                            }
                            $update_pre_data[$row['name']] = strip_slashes($pp_new_data[0][$row['name']]);
                        }
                    }
                }
                $update_pre_data['yp_id'] = $postData['yp_id'];
                $update_pre_data['created_date'] = $pp_new_data[0]['created_date'];
                $update_pre_data['created_by'] = $pp_new_data[0]['created_by'];
                $update_pre_data['modified_by'] = $pp_new_data[0]['modified_by'];
                $update_pre_data['modified_date'] = $pp_new_data[0]['modified_date'];
                $update_pre_data['care_home_id'] = $data_care_home_id['YP_details_care_home_id'][0]['care_home'];
                $update_pre_data['is_previous_version'] = 1;
            }
        }
        if (!empty($postData['individual_strategies_id'])) {
            $individual_strategies_id = $postData['individual_strategies_id'];
            $data['individual_strategies_id'] = $postData['individual_strategies_id'];
            $data['yp_id'] = $postData['yp_id'];
            $data['modified_date'] = datetimeformat();
            $data['modified_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            $data['care_home_id'] = $data_care_home_id['YP_details_care_home_id'][0]['care_home'];
            $this->common_model->update(INDIVIDUAL_STRATEGIES, $data, array('individual_strategies_id' => $postData['individual_strategies_id']));
            if (!empty($updated_field)) {
                foreach ($updated_field as $fields) {
                    //Insert log activity
                    $activity = array(
                        'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                        'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                        'module_name' => IS_MODULE,
                        'module_field_name' => $fields,
                        'type' => 2
                    );
                    log_activity($activity);
                }
            }
        } else {
            
            $data['yp_id'] = $postData['yp_id'];
            $data['created_date'] = datetimeformat();
            $data['modified_date'] = datetimeformat();
            $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            $data['care_home_id'] = $data_care_home_id['YP_details_care_home_id'][0]['care_home'];
            $this->common_model->insert(INDIVIDUAL_STRATEGIES, $data);
            $individual_strategies_id = $this->db->insert_id();
            //Insert log activity
            $activity = array(
                'user_id' => $this->session->userdata['LOGGED_IN']['ID'],
                'yp_id' => !empty($postData['yp_id']) ? $postData['yp_id'] : '',
                'module_name' => IS_MODULE,
                'module_field_name' => '',
                'type' => 1
            );
            log_activity($activity);
        }
        $new_change = 0;
        $new_update = 0;
        if (!empty($individual_strategies_id)) {
            // amendments insater & update 
            //Delete item
            $delete_item_id = $this->input->post('delete_item_id');
            if (!empty($delete_item_id)) {
                $new_update++;
                $delete_item = substr($delete_item_id, 0, -1);
                $delete_item_id = explode(',', $delete_item);
                $where1 = array('individual_meeting_id' => $individual_strategies_id);
                $this->common_model->delete_where_in(NFC_AMENDMENTS, $where1, 'amendments_id', $delete_item_id);
            }
            //update amendments item
            $where1 = array('individual_meeting_id' => $individual_strategies_id);
            $individual_meeting_item = $this->common_model->get_records(NFC_AMENDMENTS, array('amendments_id'), '', '', $where1, '');

            if (!empty($individual_meeting_item)) {
                
                for ($i = 0; $i < count($individual_meeting_item); $i++) {
                    $update_item = '';
                    if (!empty($postData['amendments_' . $individual_meeting_item[$i]['amendments_id']])) {

                        $adst = addslashes(($postData['amendments_' . $individual_meeting_item[$i]['amendments_id']]));

                        $where1 = "yp_id =" . $postData['yp_id'] . " and amendments_id =" . $individual_meeting_item[$i]['amendments_id'];
                        $amendments_archive = $this->common_model->get_records(NFC_ARCHIVE_AMENDMENTS, array('archive_amendments_id,amendments'), '', '', $where1, '', '1', '', 'archive_amendments_id', 'desc', '', '');
                        if (!empty($amendments_archive)) {
                            $current = strip_tags($postData['amendments_' . $individual_meeting_item[$i]['amendments_id']]);
                            $ame = strip_tags($amendments_archive[0]['amendments']);
                            if ($current != $ame) {
                                $new_update++;
                            }
                        }
                        
                        $update_item['amendments'] = stripslashes(str_replace("'", '"', $this->input->post('amendments_' . $individual_meeting_item[$i]['amendments_id'])));

                        /* Start - Ritesh Rana : Mantis issue # 8931, Dt: 17th April 2018 */
                        $prev_amendments_data = $this->common_model->get_records(NFC_AMENDMENTS, array('*'), '', '', $where1);

                        if ($prev_amendments_data) {
                            $amendments_prev_info = $prev_amendments_data[0]['amendments'];
                            if ($amendments_prev_info != $update_item['amendments']) {
                                $update_item['modified_date'] = datetimeformat();
                            }
                        }

                        /* End - Ritesh Rana : Mantis issue # 8931, Dt: 17th April 2018 */

                        $where = array('amendments_id' => $individual_meeting_item[$i]['amendments_id']);
                        $success_update = $this->common_model->update(NFC_AMENDMENTS, $update_item, $where);
                    }
                }
            }


            //Insert new amendments
            $item_name = $this->input->post('amendments');
            for ($i = 0; $i < count($item_name); $i++) {

                if (!empty($item_name[$i])) {
                    $new_change ++;
                    $item_data['individual_meeting_id'] = $individual_strategies_id;
                    $item_data['yp_id'] = $postData['yp_id'];
                    $item_data['amendments'] = strip_slashes($item_name[$i]);
                    $item_data['staff_initials'] = $this->session->userdata['LOGGED_IN']['ID'];
                    $item_data['created_date'] = datetimeformat();
                    $item_data['modified_date'] = datetimeformat();
                    $this->common_model->insert(NFC_AMENDMENTS, $item_data);
                }
            }

            /* end amendments functionality */
            /* start Current Protocols in place */
            $delete_protocols_id = $this->input->post('delete_protocols_id');
            if (!empty($delete_protocols_id)) {
                $new_update++;
                $delete_protocols = substr($delete_protocols_id, 0, -1);
                $delete_id = explode(',', $delete_protocols);
                $where1 = array('individual_meeting_id' => $individual_strategies_id);
                $this->common_model->delete_where_in(NFC_CURRENT_PROTOCOLS_IN_PLACE, $where1, 'protocols_in_place_id', $delete_id);
            }
            //update Current Protocols in place
            $where1 = array('individual_meeting_id' => $individual_strategies_id);
            $individual_protocols_item = $this->common_model->get_records(NFC_CURRENT_PROTOCOLS_IN_PLACE, array('protocols_in_place_id'), '', '', $where1, '');
            if (!empty($individual_protocols_item)) {
                for ($i = 0; $i < count($individual_protocols_item); $i++) {
                    $update_protocols = '';
                    if (!empty(addslashes($postData['current_protocols_in_place_' . $individual_protocols_item[$i]['protocols_in_place_id']]))) {

                        $where1 = "yp_id =" . $postData['yp_id'] . " and protocols_in_place_id =" . $individual_protocols_item[$i]['protocols_in_place_id'];
                        $current_protocol_archive = $this->common_model->get_records(NFC_ARCHIVE_CURRENT_PROTOCOLS_IN_PLACE, array('archive_protocols_in_place_id,current_protocols_in_place'), '', '', $where1, '', '1', '', 'archive_protocols_in_place_id', 'desc', '', '');

                        if (!empty($current_protocol_archive)) {
                            $current_pro = strip_tags($postData['current_protocols_in_place_' . $individual_protocols_item[$i]['protocols_in_place_id']]);
                            $protocol = strip_tags($current_protocol_archive[0]['current_protocols_in_place']);
                            if ($current_pro != $protocol) {
                                $new_update++;
                            }
                        }

                        /* Start - Ritesh Rana : Mantis issue # 8931, Dt: 17th April 2018 */
                        $update_protocols['current_protocols_in_place'] = strip_slashes($this->input->post('current_protocols_in_place_' . $individual_protocols_item[$i]['protocols_in_place_id']));
                        $prev_protocol_data = $this->common_model->get_records(NFC_CURRENT_PROTOCOLS_IN_PLACE, array('*'), '', '', $where1);

                        if ($prev_protocol_data) {
                            $protocol_prev_info = $prev_protocol_data[0]['current_protocols_in_place'];
                            if ($protocol_prev_info != $update_protocols['current_protocols_in_place']) {
                                $update_protocols['modified_date'] = datetimeformat();
                            }
                        }
                        /* End - Ritesh Rana : Mantis issue # 8931, Dt: 17th April 2018 */
                        $where = array('protocols_in_place_id' => $individual_protocols_item[$i]['protocols_in_place_id']);
                        $success_update = $this->common_model->update(NFC_CURRENT_PROTOCOLS_IN_PLACE, $update_protocols, $where);
                    }
                }
            }

            //Insert new Current Protocols in place
            $protocols_name = $this->input->post('current_protocols_in_place');
            for ($i = 0; $i < count($protocols_name); $i++) {
                if (!empty($protocols_name[$i])) {
                    $new_change ++;
                    $protocols_data['individual_meeting_id'] = $individual_strategies_id;
                    $protocols_data['yp_id'] = $postData['yp_id'];
                    $protocols_data['current_protocols_in_place'] = strip_slashes($protocols_name[$i]);
                    $protocols_data['staff_initials'] = $this->session->userdata['LOGGED_IN']['ID'];
                    $protocols_data['created_date'] = datetimeformat();
                    $protocols_data['modified_date'] = datetimeformat();
                    $this->common_model->insert(NFC_CURRENT_PROTOCOLS_IN_PLACE, $protocols_data);
                }
            }
            /* end Current Protocols in place */
        }


        if (!empty($previous_data)) {
            if ($n != 0 || $new_update > 0 || $new_change > 0) {
                $this->common_model->update(INDIVIDUAL_STRATEGIES, $update_pre_data, array('yp_id' => $postData['yp_id'], 'is_previous_version' => 1, 'care_home_id' => $data_care_home_id['YP_details_care_home_id'][0]['care_home']));
            }
        } else {
            $this->common_model->insert(INDIVIDUAL_STRATEGIES, $update_pre_data);
        }

        $this->createArchive($postData['yp_id'], $individual_strategies_id, $new_change, $new_update);
        redirect('/' . $this->viewname . '/index/' . $data['yp_id']);
    }

    /*
      @Author : Ritesh Rana
      @Desc   : usr sign off functionality
      @Input    : yp_id,is_id
      @Output   :
      @Date   : 12/07/2017
     */

    public function manager_review($yp_id, $individual_strategies_id) {
        if (!empty($yp_id) && !empty($individual_strategies_id)) {
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $match = array('yp_id' => $yp_id, 'created_by' => $login_user_id, 'is_delete' => '0');
            $check_signoff_data = $this->common_model->get_records(INDIVIDUAL_MEETING_SIGNOFF, '', '', '', $match);
            if (empty($check_signoff_data) > 0) {
                $update_pre_data['yp_id'] = $yp_id;
                $update_pre_data['individual_meeting_id'] = $individual_strategies_id;
                $update_pre_data['created_date'] = datetimeformat();
                $update_pre_data['created_by'] = $this->session->userdata('LOGGED_IN')['ID'];
                if ($this->common_model->insert(INDIVIDUAL_MEETING_SIGNOFF, $update_pre_data)) {
                    $msg = $this->lang->line('successfully_strategies_review');
                    $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                } else {
                    // error
                    $msg = $this->lang->line('error_msg');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                }
            } else {
                $msg = $this->lang->line('already_strategies_review');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
        } else {
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }

        redirect('/' . $this->viewname . '/index/' . $yp_id);
    }
     /*
      @Author : Niral Patel
      @Desc   : check edit page 
      @Input    :
      @Output   :
      @Date   : 18/08/2017
     */
    public function update_slug() {
        $postData = $this->input->post();
        $where = array('url_data' => $postData['url_data']);
        $this->common_model->delete(CHECK_EDIT_URL, $where);

        $update_pre_data['datetime'] = date('Y-m-d H:i:s');
        $update_pre_data['url_data'] = $postData['url_data'];
        $this->common_model->insert(CHECK_EDIT_URL, $update_pre_data);
        return TRUE;
    }

    /*
      @Author : Niral Patel
      @Desc   : Create archive
      @Input    :
      @Output   :
      @Date   : 18/08/2017
     */

    public function createArchive($id, $individual_strategies_id, $new_change, $new_update) {
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data_yp_detail['YP_details'] = YpDetails($id, $fields);
        if (is_numeric($id)) {
            //get ra form
            $match = array('is_form_id' => 1);
            $formsdata = $this->common_model->get_records(IS_FORM, '', '', '', $match);

            //get IS yp data
            $match = array('yp_id' => $id);
            $pp_yp_data = $this->common_model->get_records(INDIVIDUAL_STRATEGIES, '', '', '', $match);

            /* edit by nikunj ghelani
              query for get medified date from individual strategie */

            if (!empty($formsdata) && !empty($pp_yp_data)) {
                $match = array('yp_id' => $id, 'is_previous_version' => 1);
                $data['prev_edit_data'] = $this->common_model->get_records(INDIVIDUAL_STRATEGIES, '', '', '', $match);
                $last_modified_date = $data['prev_edit_data'][0]['modified_date'];
                $form_data = json_decode($formsdata[0]['form_json_data'], TRUE);
                $data = array();
                $i = 0;
                foreach ($form_data as $row) {
                    if (isset($row['name'])) {
                        if ($row['type'] != 'button') {
                            if ($row['type'] == 'checkbox-group') {
                                $form_data[$i]['value'] = implode(',', $pp_yp_data[0][$row['name']]);
                            } else {
                                /*changes done by Dhara Bhalala to manage single quote in json*/
                                $form_data[$i]['value'] = str_replace("'", "\'", $pp_yp_data[0][$row['name']]);
//                                $form_data[$i]['value'] = str_replace("'", '"', $pp_yp_data[0][$row['name']]);
                            }
                        }
                    }
                    $i++;
                }

                $archive = array(
                    'yp_id' => $id,
                    'form_json_data' => json_encode($form_data, TRUE),
                    'created_by' => $this->session->userdata('LOGGED_IN')['ID'],
                    'created_date' => datetimeformat(),
                    'modified_date' => $last_modified_date,
                    'modified_time' => date("H:i:s"),
                    'status' => 0,
                    'care_home_id' => $data_yp_detail['YP_details'][0]['care_home']
                );

                //get ra yp data
//                $wherestring = " form_json_data = '" . str_replace("\\", "\\\\", json_encode($form_data, TRUE)) . "'";
//                $pp_archive_data = $this->common_model->get_records(INDIVIDUAL_STRATEGIES_ARCHIVE, array('yp_id'), '', '', '', '', '', '', '', '', '', $wherestring);
                
                /*changes done by Dhara Bhalala to prevent auto archive when no updation changes*/
                $match = array('form_json_data' => json_encode($form_data, TRUE));
                $pp_archive_data = $this->common_model->get_records(INDIVIDUAL_STRATEGIES_ARCHIVE, array('yp_id'), '', '', $match);
                
                if (empty($pp_archive_data) || $new_change > 0 || $new_update > 0) {

                    //get ra yp data
                    $match = array('yp_id' => $id);
                    $archive_data = $this->common_model->get_records(INDIVIDUAL_STRATEGIES_ARCHIVE, '', '', '', $match);

                    if (!empty($archive_data)) {

                        //update status to archive
                        $update_archive = array(
                            'created_date' => datetimeformat(),
                            'modified_date' => $last_modified_date,
                            'modified_time' => date("H:i:s"),
                            'status' => 1,
                            'care_home_id' => $data_yp_detail['YP_details'][0]['care_home']
                        );
                        $where = array('status' => 0, 'yp_id' => $id);
                        $this->common_model->update(INDIVIDUAL_STRATEGIES_ARCHIVE, $update_archive, $where);
                    }
                    //insert archive data for next time

                    $this->common_model->insert(INDIVIDUAL_STRATEGIES_ARCHIVE, $archive);

                    $archive_individual_meeting_id = $this->db->insert_id();


                    $table = NFC_AMENDMENTS;
                    $match = array('yp_id' => $id);
                    $field = array('*');
                    $amendment_data = $this->common_model->get_records($table, $field, '', '', $match);

                    if (!empty($amendment_data)) {
                        foreach ($amendment_data as $data) {
                            $amendment['amendments_id'] = $data['amendments_id'];
                            $amendment['archive_individual_meeting_id'] = $archive_individual_meeting_id;
                            $amendment['yp_id'] = $data['yp_id'];
                            $amendment['amendments'] = ucfirst($data['amendments']);
                            $amendment['staff_initials'] = $data['staff_initials'];
                            $amendment['amendments_modified_date'] = $data['modified_date'];
                            $amendment['created_date'] = datetimeformat();
                            $this->common_model->insert(NFC_ARCHIVE_AMENDMENTS, $amendment);
                        }
                    }

                    $table = NFC_CURRENT_PROTOCOLS_IN_PLACE;
                    $match = array('yp_id' => $id);
                    $field = array('*');
                    $protocols_details = $this->common_model->get_records($table, $field, '', '', $match);


                    if (!empty($protocols_details)) {
                        foreach ($protocols_details as $protocols_data) {
                            $protocols['protocols_in_place_id'] = $protocols_data['protocols_in_place_id'];
                            $protocols['yp_id'] = $protocols_data['yp_id'];
                            $protocols['archive_individual_meeting_id'] = $archive_individual_meeting_id;
                            $protocols['current_protocols_in_place'] = ucfirst($protocols_data['current_protocols_in_place']);
                            $protocols['staff_initials'] = $protocols_data['staff_initials'];
                            $protocols['created_date'] = datetimeformat();
                            $protocols['protocol_modified_date'] = $protocols_data['modified_date'];
                            $this->common_model->insert(NFC_ARCHIVE_CURRENT_PROTOCOLS_IN_PLACE, $protocols);
                        }
                    }
                    if ($archive_individual_meeting_id > 1) {
                        $archive_individual_meeting_id = $archive_individual_meeting_id - 1;
                    }
                    $table = INDIVIDUAL_MEETING_SIGNOFF;
                    $match = array('yp_id' => $id, 'individual_meeting_id' => $pp_yp_data[0]['individual_strategies_id'], 'is_delete' => '0');
                    $field = array('yp_id,individual_meeting_id,created_by');
                    $signoff_details = $this->common_model->get_records($table, $field, '', '', $match);

                    if (!empty($signoff_details)) {
                        foreach ($signoff_details as $signoff_data) {
                            $signoff['yp_id'] = $signoff_data['yp_id'];
                            $signoff['archive_individual_meeting_id'] = $archive_individual_meeting_id;
                            $signoff['individual_meeting_id'] = ucfirst($signoff_data['individual_meeting_id']);
                            $signoff['created_by'] = $signoff_data['created_by'];
                            $signoff['created_date'] = datetimeformat();
                            $this->common_model->insert(NFC_ARCHIVE_INDIVIDUAL_MEETING_SIGNOFF, $signoff);
                        }

                        $archive = array('is_delete' => 1);
                        $where = array('yp_id' => $id);
                        $this->common_model->update(INDIVIDUAL_MEETING_SIGNOFF, $archive, $where);
                    }

                    return TRUE;
                } else {
                    return TRUE;
                }
            } else {
                return TRUE;
            }
        } else {
            show_404();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : open popup for Signoff / Approval functionality
      @Input    : yp_id,is_id
      @Output   :
      @Date   : 12/07/2017
     */

    public function signoff($yp_id = '', $is_id = '') {
        $this->formValidation();
         $fields = array("care_home");
        $data['YP_details'] = YpDetails($yp_id,$fields);

        $data['care_home_id'] = $data['YP_details'][0]['care_home'];
        $main_user_data = $this->session->userdata('LOGGED_IN');
        if ($this->form_validation->run() == FALSE) {

            $data['footerJs'][0] = base_url('uploads/custom/js/IndividualStrategies/IndividualStrategies.js');
            $data['crnt_view'] = $this->viewname;

            $data['ypid'] = $yp_id;
            $data['is_id'] = $is_id;
            //get social info
            $table = SOCIAL_WORKER_DETAILS . ' as sw';
            $match = array("sw.yp_id" => $yp_id);
            $fields = array("sw.social_worker_id,sw.social_worker_firstname,sw.social_worker_surname");
            $data['social_worker_data'] = $this->common_model->get_records($table, $fields, '', '', $match);
            //get parent info
            $table = PARENT_CARER_INFORMATION . ' as pc';
            $match = array("pc.yp_id" => $yp_id, 'pc.is_deleted' => 0);
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
      @Desc   : insert Signoff / Approval functionality
      @Input    : yp_id,is_id
      @Output   :
      @Date   : 12/07/2017
     */

    public function insertdata() {
        $postdata = $this->input->post();
        $ypid = $postdata['ypid'];
        $is_id = $postdata['is_id'];
        $user_type = $postdata['user_type'];
        // insert parent details
        if ($user_type == 'parent') {
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
                'module_name' => IS_PARENT_CARER_DETAILS_YP,
                'module_field_name' => '',
                'type' => 1
            );
            log_activity($activity);
        }
        //Current Login detail
        $main_user_data = $this->session->userdata('LOGGED_IN');

        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect('YoungPerson/view/' . $ypid);
        }
        $data['crnt_view'] = $this->viewname;

        $match = array('is_form_id' => 1);
        $formsdata = $this->common_model->get_records(IS_FORM, '', '', '', $match);
        //get ra yp data
        $match = array('yp_id' => $ypid);
        $pp_yp_data = $this->common_model->get_records(INDIVIDUAL_STRATEGIES, '', '', '', $match);

        if (!empty($formsdata) && !empty($pp_yp_data)) {
            $form_data = json_decode($formsdata[0]['form_json_data'], TRUE);
            $data = array();
            $i = 0;
            foreach ($form_data as $row) {
                if (isset($row['name'])) {
                    if ($row['type'] != 'button') {
                        if ($row['type'] == 'checkbox-group') {
                            $form_data[$i]['value'] = implode(',', $pp_yp_data[0][$row['name']]);
                        } else {
                            $form_data[$i]['value'] = str_replace("'", '"', $pp_yp_data[0][$row['name']]);
                        }
                    }
                }
                $i++;
            }
        }

        $data = array(
            'user_type' => ucfirst($postdata['user_type']),
            'yp_id' => ucfirst($postdata['ypid']),
            'is_id' => ucfirst($postdata['is_id']),
            'form_json_data' => json_encode($form_data, TRUE),
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
        if ($this->common_model->insert(NFC_IS_SIGNOFF_DETAILS, $data)) {

            $signoff_id = $this->db->insert_id();

            $table = NFC_AMENDMENTS;
            $match = array('yp_id' => $ypid);
            $field = array('amendments_id,yp_id,amendments,staff_initials');
            $amendment_data = $this->common_model->get_records($table, $field, '', '', $match);
            if (!empty($amendment_data)) {
                foreach ($amendment_data as $data_amendment) {
                    $amendment['amendments_id'] = $data_amendment['amendments_id'];
                    $amendment['approval_individual_meeting_id'] = $signoff_id;
                    $amendment['yp_id'] = $data_amendment['yp_id'];
                    $amendment['amendments'] = ucfirst($data_amendment['amendments']);
                    $amendment['staff_initials'] = $data_amendment['staff_initials'];
                    $amendment['created_date'] = datetimeformat();
                    $this->common_model->insert(NFC_APPROVAL_AMENDMENTS, $amendment);
                }
            }

            $table = NFC_CURRENT_PROTOCOLS_IN_PLACE;
            $match = array('yp_id' => $ypid);
            $field = array('protocols_in_place_id,yp_id,current_protocols_in_place,staff_initials');
            $protocols_details = $this->common_model->get_records($table, $field, '', '', $match);

            if (!empty($protocols_details)) {
                foreach ($protocols_details as $protocols_data) {
                    $protocols['protocols_in_place_id'] = $protocols_data['protocols_in_place_id'];
                    $protocols['yp_id'] = $protocols_data['yp_id'];
                    $protocols['approval_individual_meeting_id'] = $signoff_id;
                    $protocols['current_protocols_in_place'] = ucfirst($protocols_data['current_protocols_in_place']);
                    $protocols['staff_initials'] = $protocols_data['staff_initials'];
                    $protocols['created_date'] = datetimeformat();
                    $this->common_model->insert(NFC_APPROVAL_CURRENT_PROTOCOLS_IN_PLACE, $protocols);
                }
            }

            $table = INDIVIDUAL_MEETING_SIGNOFF;
            $match = array('yp_id' => $ypid, 'is_delete' => '0');
            $field = array('yp_id,created_by');
            $signoff_data = $this->common_model->get_records($table, $field, '', '', $match);
            if (!empty($signoff_data)) {
                foreach ($signoff_data as $approval_value) {
                    $update_arc_data['approval_is_id'] = $signoff_id;
                    $update_arc_data['yp_id'] = $approval_value['yp_id'];
                    $update_arc_data['created_date'] = datetimeformat();
                    $update_arc_data['created_by'] = $approval_value['created_by'];
                    $this->common_model->insert(NFC_APPROVAL_INDIVIDUAL_MEETING_SIGNOFF, $update_arc_data);
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

        redirect('IndividualStrategies/index/' . $ypid);
    }

    /*
      @Author : Ritesh Rana
      @Desc   : Send email for Signoff / Approval functionality
      @Input    : yp_id,is_id
      @Output   :
      @Date   : 12/07/2017
     */

    private function sendMailToRelation($data = array(), $signoff_id) {

        if (!empty($data) && is_numeric($signoff_id)) {
            if (!empty($data['yp_id'])) {
                $match = array("yp_id" => $data['yp_id']);
                $fields = array("concat(yp_fname,' ',yp_lname) as yp_name");
                $YP_details = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);
            }
            $yp_name = !empty($YP_details[0]['yp_name']) ? $YP_details[0]['yp_name'] : "";
            /* Send Created Customer password with Link */
            $toEmailId = $data['email'];
            $customerName = $data['fname'] . ' ' . $data['lname'];

            $email = md5($toEmailId);
            $loginLink = base_url('IndividualStrategies/signoffData/' . $data['yp_id'] . '/' . $signoff_id . '/' . $email);
            $find = array('{NAME}', '{LINK}');

            $replace = array(
                'NAME' => $customerName,
                'LINK' => $loginLink,
            );

            $emailSubject = 'Welcome to NFCTracker';
            $emailBody = '<div>'
                    . '<p>Hello {NAME} ,</p> '
                    . '<p>Please find Individual Strategies for ' . $yp_name . ' for your approval.</p> '
                    . "<p>For security purposes, Please do not forward this email on to any other person. It is for the recipient only and if this is sent in error please advise itsupport@newforestcare.co.uk and delete this email. This link is only valid for " . REPORT_EXPAIRED_HOUR . ", should this not be signed off within " . REPORT_EXPAIRED_HOUR . " of recieving then please request again</p>"
                    . '<p> <a href="{LINK}">click here</a> </p> '
                    . '<div>';

            $finalEmailBody = str_replace($find, $replace, $emailBody);

            return $this->common_model->sendEmail($toEmailId, $emailSubject, $finalEmailBody, FROM_EMAIL_ID);
        }
        return true;
    }

    public function formValidation($id = null) {
        $this->form_validation->set_rules('fname', 'Firstname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('lname', 'Lastname', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
    }

    /*
      @Author : Ritesh Rana
      @Desc   : View Signoff / Approval functionality
      @Input    : yp_id,email_id and sign off id
      @Output   :
      @Date   : 12/07/2017
     */

    public function signoffData($id, $signoff_id, $email) {

        if (is_numeric($id) && is_numeric($signoff_id) && !empty($email)) {

            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $match = array('yp_id' => $id, 'is_signoff_details_id' => $signoff_id, 'key_data' => $email, 'status' => 'inactive');
            $check_signoff_data = $this->common_model->get_records(NFC_IS_SIGNOFF_DETAILS, array("*"), '', '', $match);

            if (!empty($check_signoff_data)) {
                $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'] . REPORT_EXPAIRED_DAYS));
                if (strtotime(datetimeformat()) <= strtotime($expairedDate)) {
                    $data['form_data'] = json_decode($check_signoff_data[0]['form_json_data'], TRUE);

                    //get YP information
                    $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
                    $data['YP_details'] = YpDetails($id,$fields);

                    if (empty($data['YP_details'])) {
                        $msg = $this->lang->line('common_no_record_found');
                        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                        redirect('YoungPerson/view/' . $id);
                    }

                    //get Amendments data
                    $table = NFC_APPROVAL_AMENDMENTS . ' as do';
                    $match = array('do.yp_id' => $id, 'approval_individual_meeting_id' => $signoff_id);
                    $fields = array("do.amendments,do.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name");
                    $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.staff_initials');
                    $data['item_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
                    //get current protocols in place
                    $table = NFC_APPROVAL_CURRENT_PROTOCOLS_IN_PLACE . ' as cpip';
                    $match = array('cpip.yp_id' => $id, 'approval_individual_meeting_id' => $signoff_id);
                    $fields = array("cpip.current_protocols_in_place,cpip.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name");
                    $join_tables = array(LOGIN . ' as l' => 'l.login_id= cpip.staff_initials');
                    $data['protocols_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
                    //get IS Signoff datat 
                    $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
                    $table = INDIVIDUAL_MEETING_SIGNOFF . ' as ims';
                    $where = array("l.is_delete" => "0", "ims.yp_id" => $id, "ims.is_delete" => "0");
                    $fields = array("ims.created_by,ims.created_date,ims.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
                    $join_tables = array(LOGIN . ' as l' => 'l.login_id=ims.created_by');
                    $group_by = array('created_by');
                    $data['ra_signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);

                    //get IS old yp data
                    $data['key_data'] = $email;
                    $data['ypid'] = $id;
                    $data['signoff_id'] = $signoff_id;
                    $match = array('yp_id' => $id, 'is_previous_version' => 1);
                    $data['prev_edit_data'] = $this->common_model->get_records(INDIVIDUAL_STRATEGIES, '', '', '', $match);

                    $data['header'] = array('menu_module' => 'YoungPerson');
                    $data['footerJs'][0] = base_url('uploads/custom/js/IndividualStrategies/IndividualStrategies.js');
                    $data['crnt_view'] = $this->viewname;
                    $data['main_content'] = '/signoff_view';
                    $this->parser->parse('layouts/DefaultTemplate', $data);
                } else {
                    $msg = lang('link_expired');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>")
                    ;
                    $this->load->view('successfully_message');
                }
            } else {
                $msg = $this->lang->line('already_is_review');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                $this->load->view('successfully_message');
            }
            //get pp form
        } else {
            show_404();
        }
    }

    /*
      @Author : Ritesh Rana
      @Desc   : review data for Signoff / Approval view page
      @Input    : yp_id,signoff_id,email_id
      @Output   :
      @Date   : 12/07/2017
     */

    public function signoff_review_data($yp_id, $signoff_id, $email) {
        if (!empty($yp_id) && !empty($signoff_id) && !empty($email)) {
            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $match = array('yp_id' => $yp_id, 'is_signoff_details_id' => $signoff_id, 'key_data' => $email, 'status' => 'inactive');
            $check_signoff_data = $this->common_model->get_records(NFC_IS_SIGNOFF_DETAILS, array("form_json_data,created_date,key_data"), '', '', $match);
            if (!empty($check_signoff_data)) {
                $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'] . REPORT_EXPAIRED_DAYS));
                if (strtotime(datetimeformat()) <= strtotime($expairedDate)) {
                    $u_data['status'] = 'active';
                    $u_data['modified_date'] = datetimeformat();
                    $success = $this->common_model->update(NFC_IS_SIGNOFF_DETAILS, $u_data, array('is_signoff_details_id' => $signoff_id, 'yp_id' => $yp_id, 'key_data' => $email));
                    if ($success) {
                        $msg = $this->lang->line('successfully_is_review');
                        $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-success text-center'>$msg</div>");
                    } else {
                        // error
                        $msg = $this->lang->line('error_msg');
                        $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>");
                    }
                } else {
                    $msg = lang('link_expired');
                    $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>")
                    ;
                }
            } else {
                $msg = $this->lang->line('already_is_review');
                $this->session->set_flashdata('signoff_review_msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
        } else {
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
        $postdata = $this->input->post();
        $user_type = !empty($postdata['user_type']) ? $postdata['user_type'] : '';
        $id = $postdata['id'];
        $ypid = $postdata['ypid'];
        $table = YP_DETAILS . ' as yp';
        $match = "yp.yp_id = " . $ypid;
        $fields = array("yp.*,swd.social_worker_firstname,swd.social_worker_surname,swd.landline,swd.mobile,swd.email,swd.senior_social_worker_firstname,swd.senior_social_worker_surname,oyp.pen_portrait,swd.other,pc.firstname,pc.surname,pc.relationship,pc.address,pc.contact_number,pc.email_address,pc.yp_authorised_communication,pc.carer_authorised_communication,pc.comments");
        $join_tables = array(SOCIAL_WORKER_DETAILS . ' as swd' => 'swd.yp_id= yp.yp_id', PARENT_CARER_INFORMATION . ' as pc' => 'pc.yp_id= yp.yp_id', OVERVIEW_OF_YOUNG_PERSON . ' as oyp' => 'oyp.yp_id= yp.yp_id');

        $data['editRecord'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
        if ($user_type == 'parent_data') {

            //get parent info
            $table = PARENT_CARER_INFORMATION . ' as pc';
            $match = array("pc.parent_carer_id" => $id, 'pc.is_deleted' => 0);
            $fields = array("pc.*");
            $data['parent_data'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['editRecord'][0]['fname'] = $data['editRecord'][0]['firstname'];
            $data['editRecord'][0]['lname'] = $data['editRecord'][0]['surname'];
            $data['editRecord'][0]['email_id'] = $data['editRecord'][0]['email_address'];
        } else if ($user_type == 'social_data') {
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
      @Author : Ritesh rana
      @Desc   : view id data
      @Input  :
      @Output :
      @Date   : 13/04/2018
     */

    public function external_approval_list($ypid, $is_id) {
        if (is_numeric($ypid) && is_numeric($is_id)) {
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($ypid,$fields);

            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/' . $ypid);
            }

            $searchtext = $perpage = '';
            $searchtext = $this->input->post('searchtext');
            $sortfield = $this->input->post('sortfield');
            $sortby = $this->input->post('sortby');
            $perpage = 10;
            $allflag = $this->input->post('allflag');
            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $this->session->unset_userdata('is_approval_session_data');
            }

            $searchsort_session = $this->session->userdata('is_approval_session_data');
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
                    $sortfield = 'is_signoff_details_id';
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
                    $config['per_page'] = '10';
                    $data['perpage'] = '10';
                }
            }
            //pagination configuration
            $config['first_link'] = 'First';
            $config['base_url'] = base_url() . $this->viewname . '/external_approval_list/' . $ypid . '/' . $is_id;

            if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
                $config['uri_segment'] = 0;
                $uri_segment = 0;
            } else {
                $config['uri_segment'] = 5;
                $uri_segment = $this->uri->segment(5);
            }
            //Query

            $table = NFC_IS_SIGNOFF_DETAILS . ' as com';
            $where = array("com.yp_id" => $ypid, "com.is_id" => $is_id);
            $fields = array("c.care_home_name,com.*,CONCAT(`firstname`,' ', `lastname`) as create_name ,CONCAT(`fname`,' ', `lname`) as user_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= com.created_by', CARE_HOME . ' as c' => 'c.care_home_id= com.care_home_id');
            if (!empty($searchtext)) {
                
            } else {
                $data['information'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);

                $config['total_rows'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
            }
            $data['ypid'] = $ypid;
            $data['is_id'] = $is_id;

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

            $this->session->set_userdata('is_approval_session_data', $sortsearchpage_data);
            setActiveSession('is_approval_session_data'); // set current Session active
            $data['header'] = array('menu_module' => 'Communication');

            //get communication form
            $data['crnt_view'] = $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/IndividualStrategies/IndividualStrategies.js');
            $data['header'] = array('menu_module' => 'YoungPerson');

            if ($this->input->post('result_type') == 'ajax') {
                $this->load->view($this->viewname . '/approval_ajaxlist', $data);
            } else {
                $data['main_content'] = '/is_list';
                $this->parser->parse('layouts/DefaultTemplate', $data);
            }
        } else {
            show_404();
        }
    }

     /*
      @Author : Ritesh rana
      @Desc   : view approval IS
      @Input  :
      @Output :
      @Date   : 13/04/2018
     */
    public function viewApprovalIs($id, $ypid) {

        if (is_numeric($id) && is_numeric($ypid)) {
            //get archive pp data
            $match = array('is_signoff_details_id' => $id);
            $formsdata = $this->common_model->get_records(NFC_IS_SIGNOFF_DETAILS, '', '', '', $match);
            $data['formsdata'] = $formsdata;

            if (!empty($formsdata)) {
                $data['pp_form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }
            //get YP information
            $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
            $data['YP_details'] = YpDetails($ypid,$fields);
            if (empty($data['YP_details'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('YoungPerson/view/' . $ypid);
            }

            //get approval individual behaviour plan signoff
            $table = NFC_APPROVAL_INDIVIDUAL_BEHAVIOUR_PLAN_SIGNOFF . ' as ibp';
            $where = array("l.is_delete" => "0", "ibp.yp_id" => $ypid, "ibp.is_delete" => "0", "approval_ibp_id" => $id);
            $fields = array("ibp.created_by,ibp.created_date,ibp.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id=ibp.created_by');
            $group_by = array('created_by');
            $data['signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);

            //get approval amendments
            $table = NFC_APPROVAL_AMENDMENTS . ' as do';
            $match = array('do.yp_id' => $ypid, 'approval_individual_meeting_id' => $id);
            $fields = array("do.amendments,do.amendments_id,do.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.staff_initials');
            $data['item_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
            
            //get approval current protocols in place
            $table = NFC_APPROVAL_CURRENT_PROTOCOLS_IN_PLACE . ' as cpip';
            $match = array('cpip.yp_id' => $ypid, 'approval_individual_meeting_id' => $id);
            $fields = array("cpip.protocols_in_place_id,cpip.current_protocols_in_place,cpip.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= cpip.staff_initials');
            $data['protocols_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
            
            $table = NFC_APPROVAL_INDIVIDUAL_MEETING_SIGNOFF . ' as is';
            $where = array("l.is_delete" => "0", "is.yp_id" => $ypid, "is.is_delete" => "0", "approval_is_id" => $id);
            $fields = array("is.created_by,is.created_date,is.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id=is.created_by');
            $group_by = array('created_by');
            $data['signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);

            $data['ypid'] = $ypid;
            $data['is_id'] = $formsdata[0]['is_id'];

            $data['footerJs'][0] = base_url('uploads/custom/js/IndividualStrategies/IndividualStrategies.js');
            $data['crnt_view'] = $this->viewname;
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/approval_view';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }

    /*
      @Author : Ritesh rana
      @Desc   : resend external approval
      @Input  :
      @Output :
      @Date   : 13/04/2018
     */
    public function resend_external_approval($signoff_id, $ypid, $isid) {
        $match = array('is_signoff_details_id' => $signoff_id);
        $signoff_data = $this->common_model->get_records(NFC_IS_SIGNOFF_DETAILS, array("yp_id,fname,lname,email"), '', '', $match);

        if (!empty($signoff_data)) {
            $data = array(
                'yp_id' => ucfirst($signoff_data[0]['yp_id']),
                'fname' => ucfirst($signoff_data[0]['fname']),
                'lname' => ucfirst($signoff_data[0]['lname']),
                'email' => $signoff_data[0]['email']
            );
            $sent = $this->sendMailToRelation($data, $signoff_id); // send mail
            if ($sent) {
                $u_data['created_date'] = datetimeformat();
                $u_data['modified_date'] = NULL;
                $success = $this->common_model->update(NFC_IS_SIGNOFF_DETAILS, $u_data, array('is_signoff_details_id' => $signoff_id));
                $msg = $this->lang->line('mail_sent_successfully');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            } else {
                $msg = $this->lang->line('error');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            }
        }

        redirect($this->viewname . '/external_approval_list/' . $ypid . '/' . $isid);
    }

/*
      @Author : Ritesh rana
      @Desc   : Download Pdf
      @Input  :
      @Output :
      @Date   : 13/04/2018
     */
    public function DownloadPdf($individual_strategies_id, $yp_id) {
        $data = [];
        $match = array('is_form_id' => 1);
        $ra_forms = $this->common_model->get_records(IS_FORM, '', '', '', $match);
        if (!empty($ra_forms)) {
            $data['ra_form_data'] = json_decode($ra_forms[0]['form_json_data'], TRUE);
        }
        //get YP information
        $data['id'] = $yp_id;
        $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
        $data['YP_details'] = YpDetails($yp_id,$fields);
        //get amendments
        $table = NFC_AMENDMENTS . ' as do';
        $match = array('do.yp_id' => $yp_id, 'do.individual_meeting_id' => $individual_strategies_id);
        $fields = array("do.amendments,do.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.staff_initials');
        $data['item_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
        //get current protocols in place
        $table = NFC_CURRENT_PROTOCOLS_IN_PLACE . ' as cpip';
        $match = array('cpip.yp_id' => $yp_id, 'cpip.individual_meeting_id' => $individual_strategies_id);
        $fields = array("cpip.current_protocols_in_place,cpip.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id= cpip.staff_initials');
        $data['protocols_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
        
        //get individual meeting signoff     
        $table = INDIVIDUAL_MEETING_SIGNOFF . ' as ims';
        $where = array("l.is_delete" => "0", "ims.yp_id" => $yp_id, "ims.individual_meeting_id" => $individual_strategies_id);
        $fields = array("ims.created_by,ims.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id=ims.created_by');
        $group_by = array('created_by');
        $data['signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);

        //get individual signoff details
        $table = NFC_IS_SIGNOFF_DETAILS . ' as sd';
        $match_social = array('sd.yp_id' => $yp_id, 'sd.is_id' => $individual_strategies_id, 'sd.status' => 'active');
        $social_where_in = array("sd.user_type" => ['Social_worker', 'Social_data']);
        $match_parent = array('sd.yp_id' => $yp_id, 'sd.is_id' => $individual_strategies_id, 'sd.status' => 'active');
        $parent_where_in = array("sd.user_type" => ['Parent', 'Parent_data']);
        $match_other = array('sd.yp_id' => $yp_id, 'sd.is_id' => $individual_strategies_id, "sd.user_type" => "Other", 'sd.status' => 'active');
        $fields = array("sd.*,CONCAT(sd.fname,' ', sd.lname) as staff_name");
        $data['social_worker_signoff'] = $this->common_model->get_records($table, $fields, '', '', $match_social, '', '', '', '', '', '', '', '', $social_where_in);
        $data['parent_signoff'] = $this->common_model->get_records($table, $fields, '', '', $match_parent, '', '', '', '', '', '', '', '', $parent_where_in);
        $data['other_signoff'] = $this->common_model->get_records($table, $fields, '', '', $match_other);

        //get IS yp data
        $table = INDIVIDUAL_STRATEGIES . ' as cpm';
        $match = array('cpm.yp_id' => $yp_id, 'cpm.individual_strategies_id' => $individual_strategies_id);
        $fields = array("cpm.*,CONCAT(l.firstname,' ', l.lastname) as create_name");
        $join_tables = array(LOGIN . ' as l' => 'l.login_id = cpm.created_by');
        $data['edit_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);

        $data['crnt_view'] = $this->viewname;
        $data['ypid'] = $yp_id;

        //new
        $pdfFileName = "individual_strategies.pdf";
        $PDFInformation['yp_details'] = $data['YP_details'][0];
        $PDFInformation['edit_data'] = $data['edit_data'][0]['modified_date'];

        $PDFHeaderHTML = $this->load->view('isrpdfHeader', $PDFInformation, true);
        $PDFFooterHTML = $this->load->view('isrpdfFooter', $PDFInformation, true);

        //Set Header Footer and Content For PDF
        $this->m_pdf->pdf->mPDF('utf-8', 'A4', '', '', '15', '15', '55', '25');

        $this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
        $this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);
        $data['main_content'] = '/isrpdf';
        $html = $this->parser->parse('layouts/PdfDataTemplate', $data);

        /* remove */
        $this->m_pdf->pdf->WriteHTML($html);
        //Store PDF in individual_strategies Folder
        $this->m_pdf->pdf->Output($pdfFileName, "D");
    }

    /*
      @Author : Nikunj Ghelani
      @Desc   : PDF data
      @Input  :
      @Output :
      @Date   : 28/06/2018
     */

    public function DownloadPDF_after_mail($id, $signoff_id) {

        if (is_numeric($id) && is_numeric($signoff_id)) {

            $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
            $match = array('yp_id' => $id, 'is_signoff_details_id' => $signoff_id, 'status' => 'inactive');
            $check_signoff_data = $this->common_model->get_records(NFC_IS_SIGNOFF_DETAILS, array("form_json_data,user_type,email,key_data,created_date"), '', '', $match);
            if (!empty($check_signoff_data)) {
                $expairedDate = date('Y-m-d H:i:s', strtotime($check_signoff_data[0]['created_date'] . REPORT_EXPAIRED_DAYS));
                if (strtotime(datetimeformat()) <= strtotime($expairedDate)) {
                    $data['form_data'] = json_decode($check_signoff_data[0]['form_json_data'], TRUE);

                    //get YP information
                    $fields = array("care_home,yp_fname,yp_lname,date_of_birth,yp_id");
                    $data['YP_details'] = YpDetails($id,$fields);
                    
                    if (empty($data['YP_details'])) {
                        $msg = $this->lang->line('common_no_record_found');
                        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                        redirect('YoungPerson/view/' . $id);
                    }
                    //get IS yp data
                    $match = array('yp_id' => $id, 'is_previous_version' => 1);
                    $data['is_edit_data'] = $this->common_model->get_records(NFC_INDIVIDUAL_STRATEGIES, array("*"), '', '', $match);
                    //get approval amendments
                    $table = NFC_APPROVAL_AMENDMENTS . ' as do';
                    $match = array('do.yp_id' => $id, 'approval_individual_meeting_id' => $signoff_id);
                    $fields = array("do.amendments,do.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name");
                    $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.staff_initials');
                    $data['item_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
                    //get approval current protocols in place
                    $table = NFC_APPROVAL_CURRENT_PROTOCOLS_IN_PLACE . ' as cpip';
                    $match = array('cpip.yp_id' => $id, 'approval_individual_meeting_id' => $signoff_id);
                    $fields = array("cpip.current_protocols_in_place,cpip.created_date,CONCAT(l.firstname,' ', l.lastname) as create_name");
                    $join_tables = array(LOGIN . ' as l' => 'l.login_id= cpip.staff_initials');
                    $data['protocols_details'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
                    
                    //get signoff data 
                    $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
                    $table = INDIVIDUAL_MEETING_SIGNOFF . ' as ims';
                    $where = array("l.is_delete" => "0", "ims.yp_id" => $id, "ims.is_delete" => "0");
                    $fields = array("ims.created_by,ims.created_date,ims.yp_id, CONCAT(`firstname`,' ', `lastname`) as name");
                    $join_tables = array(LOGIN . ' as l' => 'l.login_id=ims.created_by');
                    $group_by = array('created_by');
                    $data['ra_signoff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', $group_by, $where);

                    $table = INDIVIDUAL_MEETING_SIGNOFF . ' as ims';
                    $where = array("ims.yp_id" => $id, "ims.created_by" => $login_user_id, "ims.is_delete" => "0");
                    $fields = array("ims.individual_meeting_signoff_id,ims.individual_meeting_id");
                    $data['check_signoff_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

                    //get ra old yp data
                    $data['key_data'] = $email;
                    $data['ypid'] = $id;
                    $data['signoff_id'] = $signoff_id;
                    $match = array('yp_id' => $id, 'is_previous_version' => 1);
                    $data['prev_edit_data'] = $this->common_model->get_records(INDIVIDUAL_STRATEGIES, '', '', '', $match);

                    $data['header'] = array('menu_module' => 'YoungPerson');
                    $data['footerJs'][0] = base_url('uploads/custom/js/IndividualStrategies/IndividualStrategies.js');
                    $data['crnt_view'] = $this->viewname;
                    $pdfFileName = "individualstrategies.pdf";
                    $PDFInformation['yp_details'] = $data['YP_details'][0];
                    $PDFInformation['edit_data'] = $data['edit_data'][0]['modified_date'];
                    $PDFInformation['edit_date'] = $data['is_edit_data'][0]['modified_date'];


                    $PDFHeaderHTML = $this->load->view('individualstrategiespdfHeader', $PDFInformation, true);

                    $PDFFooterHTML = $this->load->view('individualstrategiespdfFooter', $PDFInformation, true);

                    //Set Header Footer and Content For PDF
                    $this->m_pdf->pdf->mPDF('utf-8', 'A4', '', '', '10', '10', '45', '25');

                    $this->m_pdf->pdf->SetHTMLHeader($PDFHeaderHTML, 'O');
                    $this->m_pdf->pdf->SetHTMLFooter($PDFFooterHTML);
                    $data['main_content'] = '/individualstrategies';
                    $html = $this->parser->parse('layouts/PdfDataTemplate', $data);

                    /* remove */
                    $this->m_pdf->pdf->WriteHTML($html);

                    //Store PDF in individual_strategies Folder
                    $this->m_pdf->pdf->Output($pdfFileName, "D");
                } else {
                    $msg = lang('link_expired');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>")
                    ;
                    $this->load->view('successfully_message');
                }
            } else {
                $msg = $this->lang->line('already_is_review');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                $this->load->view('successfully_message');
            }
            //get pp form
        } else {
            show_404();
        }
    }

}
