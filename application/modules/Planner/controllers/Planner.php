<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Planner extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Niral Patel
      @Desc   : Planner Index Page
      @Input  : yp id
      @Output :
      @Date   : 08/05/2018
     */

    public function index($care_home_id = '', $id = '', $isArchive = 0) {
        if (is_numeric($id) && is_numeric($care_home_id)) {
            if (!empty($id)) {
                //get YP information
                $match = array("yp_id" => $id);
                $fields = array("yp_id,care_home,yp_fname,yp_lname,date_of_birth");
                $data['YP_details'] = $this->common_model->get_records(YP_DETAILS, $fields, '', '', $match);

                if (empty($data['YP_details'])) {
                    $msg = $this->lang->line('common_no_record_found');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                    redirect('YoungPerson/view/' . $id);
                }
            }
            $data['ypid'] = $id;
            $data['care_home_id'] = $care_home_id;
            $data['isArchive'] = $isArchive;

            $data['footerCss'][0] = base_url('uploads/assets/css/fullcalendar/fullcalendar.min.css');

            $data['footerJs'][0] = base_url('uploads/assets/js/fullcalendar/fullcalendar.min.js');
            $data['footerJs'][1] = base_url('uploads/assets/js/fullcalendar/moment.min.js');
            $data['footerJs'][3] = base_url('uploads/custom/js/Planner/Planner.js');
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['crnt_view'] = $this->viewname;
            $data['main_content'] = '/add';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }
    
    public function carehome($care_home_id = '') {

        if (is_numeric($care_home_id)) {
            if (!empty($id)) {
                //get YP information
                $match = array("care_home_id" => $care_home_id);
                $fields = array("*");
                $data['YP_details'] = $this->common_model->get_records(CARE_HOME, $fields, '', '', $match);

                if (empty($data['YP_details'])) {
                    $msg = $this->lang->line('common_no_record_found');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                    redirect('YpCareHome');
                }
            }
            $data['care_home_id'] = $care_home_id;

            $data['footerCss'][0] = base_url('uploads/assets/css/fullcalendar/fullcalendar.min.css');

            $data['footerJs'][0] = base_url('uploads/assets/js/fullcalendar/fullcalendar.min.js');
            $data['footerJs'][1] = base_url('uploads/assets/js/fullcalendar/moment.min.js');
            $data['footerJs'][3] = base_url('uploads/custom/js/Planner/Planner.js');
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['crnt_view'] = $this->viewname;
            $data['main_content'] = '/add';
            $this->parser->parse('layouts/DefaultTemplate', $data);
        } else {
            show_404();
        }
    }

    /*
      @Author : Niral patel
      @Desc   : Insert PM form
      @Input    :
      @Output   :
      @Date   : 05/03/2018
     */

    public function insert() {
        $postData = $this->input->post();


        //get pp form
        $match = array('appointment_date' => dateformat($postData['appointment_date']), 'appointment_time' => date("H:i:s", strtotime($postData['appointment_time'])), 'is_delete' => 0);
        $event_data = $this->common_model->get_records(PLANNER, array("planner_id"), '', '', $match);
        if (empty($event_data)) {
            $data['yp_id'] = $postData['yp_id'];
            $data['appointment_date'] = dateformat($postData['appointment_date']);
            $data['appointment_time'] = date("H:i:s", strtotime($postData['appointment_time']));
            $data['title'] = $postData['title'];
            $data['comment'] = $postData['comment'];
            $data['created_at'] = datetimeformat();
            $data['created_by'] = $this->session->userdata['LOGGED_IN']['ID'];
            $this->common_model->insert(PLANNER, $data);
        }
        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>Events added successfully.</div>");
        redirect($this->viewname . '/Planner/index/' . $postData['yp_id']);
    }

    /*
      @Author : Niral Patel
      @Desc   : Get events
      @Input  :
      @Output :
      @Date   : 10-5-2018
     */

    public function getEvents() {
        $postData = $this->input->post();
        if (isset($postData['isArchive']) && $postData['isArchive'] != '' && !empty($postData['isArchive'])) {
            $care_home_id = $postData['yp_id'];
            $yp_id = $postData['care_home_id'];
            /* here  ypid and care home variable are visa varsa because of in post data the result are not proper when getevent are fire.
              ghelani nikunj on 3/10/2018
             */
            if ($postData['isArchive'] !== 0) {
                $care_home_id = $postData['yp_id'];
                $yp_id = $postData['care_home_id'];

                $temp_match = array("yp_id" => $postData['care_home_id'], "past_carehome" => $postData['yp_id']);
                $temp = $this->common_model->get_records(PAST_CARE_HOME_INFO, array('move_date'), '', '', $temp_match);

                $last_date = $temp[0]['move_date'];
                $match = array("yp_id" => $postData['care_home_id'], "move_date <= " => $last_date);
                $data_care_home_detail['care_home_detail'] = $this->common_model->get_records(PAST_CARE_HOME_INFO, array("enter_date,move_date"), '', '', $match);

                $created_date = $movedate = '';
                $count_care = count($data_care_home_detail['care_home_detail']);
                if ($count_care >= 1) {
                    $created_date = $data_care_home_detail['care_home_detail'][0]['created_date'];
                    $movedate = $data_care_home_detail['care_home_detail'][$count_care - 1]['move_date'];
                } elseif ($count_care == 0) {
                    $created_date = $data_care_home_detail['care_home_detail'][0]['created_date'];
                    $movedate = $data_care_home_detail['care_home_detail'][0]['move_date'];
                }
            }
        } else {
            $care_home_id = $postData['care_home_id'];
            $yp_id = $postData['yp_id'];
        }

        if (!empty($yp_id)) {
            //get planner apt
            if (isset($postData['isArchive']) && $postData['isArchive'] != '' && !empty($postData['isArchive'])) {

                $match = array('yp_id' => $yp_id, 'is_delete' => 0);
                $where_date = "created_at BETWEEN  '" . $created_date . "' AND '" . $movedate . "'";
                $eventData = $this->common_model->get_records(PLANNER, array("*"), '', '', '', '', '', '', '', '', '', $match, '', '', '', '', '', $where_date);

                //$this->common_model->get_records(PLANNER,array("*"), '', '', $match);
                //get medical apt
                $match = array('yp_id' => $yp_id, 'is_delete' => 0);
                $medicalData = $this->common_model->get_records(MEDICAL_PROFESSIONALS_APPOINTMENT, array("*"), '', '', $match);
            } else {
                $match = array('yp_id' => $yp_id, 'is_delete' => 0);
                $eventData = $this->common_model->get_records(PLANNER, array("*"), '', '', $match);

                //get medical apt
                $match = array('yp_id' => $yp_id, 'is_delete' => 0);
                $medicalData = $this->common_model->get_records(MEDICAL_PROFESSIONALS_APPOINTMENT, array("*"), '', '', $match);
            }
        } else {
            //get planner apt
            $match = array('y.care_home' => $care_home_id, 'is_delete' => 0);
            $join_tables = array(YP_DETAILS . ' as y' => 'y.yp_id= p.yp_id');
            $eventData = $this->common_model->get_records(PLANNER . ' as p', array("*"), $join_tables, 'left', $match);

            //get medical apt
            $match = array('y.care_home' => $care_home_id, 'is_delete' => 0);
            $join_tables = array(YP_DETAILS . ' as y' => 'y.yp_id= p.yp_id');
            $medicalData = $this->common_model->get_records(MEDICAL_PROFESSIONALS_APPOINTMENT . ' as p', array("*"), $join_tables, 'left', $match);
        }

        $data = array();
        if (!empty($eventData)) {
            foreach ($eventData as $row) {
                $endDate = date("H:i:s", strtotime('+59 minutes', strtotime($row['appointment_time'])));
                $repeat = '';
                $repeat = !empty($row['is_repeat']) ? '<i class="fa fa-repeat" aria-hidden="true"></i>' : '';
                $data[] = array(
                    'planner_id' => $row['planner_id'],
                    'date' => $row['appointment_date'],
                    'title' => empty($yp_id) ? timeformat($row['appointment_time']) . ' - ' . $row['yp_fname'] . ' ' . $row['yp_lname'] . ' ' . $repeat : timeformat($row['appointment_time']) . ' ' . $repeat,
                    'start' => $row['appointment_date'] . 'T' . $row['appointment_time'],
                    'end' => $row['appointment_date'] . 'T' . $endDate,
                    'textEscape' => false,
                    //'color'         => ($row['is_reservable'] == 1)?'#008000':'#ff0000',
                    //'description'   => 'From '.date('h:i a',strtotime($row['start_time'])).' to '.date('h:i a',strtotime($row['end_time'])).' '.$isRes,
                    //'totaluser'     => $row['totaluser'],
                    'url' => base_url('/Planner/viewPlanner/' . $row['planner_id']));
            }
        }

        if (!empty($medicalData)) {
            foreach ($medicalData as $row) {
                $endDate = date("H:i:s", strtotime('+59 minutes', strtotime($row['appointment_time'])));
                $repeat = '';
                $repeat = !empty($row['is_repeat']) ? '<i class="fa fa-repeat" aria-hidden="true"></i>' : '';
                $data[] = array(
                    'appointment_id' => $row['appointment_id'],
                    'date' => $row['appointment_date'],
                    'title' => empty($yp_id) ? timeformat($row['appointment_time']) . ' - ' . $row['yp_fname'] . ' ' . $row['yp_lname'] . ' ' . $repeat : timeformat($row['appointment_time']) . ' ' . $repeat,
                    'start' => $row['appointment_date'] . 'T' . $row['appointment_time'],
                    'end' => $row['appointment_date'] . 'T' . $endDate,
                    'color' => '#008000',
                    //'description'   => 'From '.date('h:i a',strtotime($row['start_time'])).' to '.date('h:i a',strtotime($row['end_time'])).' '.$isRes,
                    //'totaluser'     => $row['totaluser'],
                    'url' => base_url('/Planner/viewAppointment/' . $row['appointment_id']));
            }
        }
        echo json_encode($data);
    }

    /*
      @Author : Niral Patel
      @Desc   : edit event
      @Input  :
      @Output :
      @Date   : 10-5-2018
     */

    public function viewPlanner($id) {
        $data = array();
        //check weekly hours
        $match = array('planner_id' => $id);
        $data['eventData'] = $this->common_model->get_records(PLANNER, '', '', '', $match, '', '', '', '', '', '', '', '', '', '');
        $data['ypid'] = $data['eventData'][0]['yp_id'];
        $this->load->view($this->viewname . '/view_planner', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : view Appointment
      @Input  :
      @Output :
      @Date   : 10-5-2018
     */

    public function viewAppointment($id) {
        $data = array();
        $table = MEDICAL_PROFESSIONALS_APPOINTMENT . ' as mc';
        $match = array('mc.appointment_id' => $id);
        $fields = array("mc.*,concat(mp.title,' ',mp.first_name,' ',mp.surname,' - ',mp.professional) as mp_name,mp.yp_id");
        $join_tables = array(MEDICAL_PROFESSIONALS . ' as mp' => 'mc.mp_id= mp.mp_id', YP_DETAILS . ' as yp' => 'mc.yp_id= yp.yp_id');
        $data['mp_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', $match);
        $data['ypid'] = $data['mp_data'][0]['yp_id'];
        $this->load->view($this->viewname . '/view_appointment', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Delete event
      @Input  :
      @Output :
      @Date   : 10-5-2017
     */

    function deleteEvent() {
        $postData = $this->input->post();
        if ($this->common_model->update(PLANNER, array('is_delete' => 1), array('planner_id' => $postData['id']))) {
            echo $msg = '1';
        } else {
            // error
            echo $msg = '0';
        }
    }

}
