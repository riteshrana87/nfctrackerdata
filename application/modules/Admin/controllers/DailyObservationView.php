<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DailyObservationView extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->viewname = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
    }

    /*
      @Author : Niral Patel
      @Desc   : View Page
      @Input 	:
      @Output	:
      @Date   : 28/08/2017
     */

    public function view($id) {
        if (is_numeric($id)) {
            //get daily observation data
            $table = DAILY_OBSERVATIONS . ' as do';
            $where = array("do.do_id" => $id);
            $fields = array("do.*,CONCAT(l.firstname,' ', l.lastname) as create_name,CONCAT(yp.yp_fname,' ', yp.yp_lname) as yp_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.created_by', YP_DETAILS . ' as yp' => 'yp.yp_id= do.yp_id');

            $data['dodata'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
            $ypid = $data['dodata'][0]['yp_id'];
            if (empty($data['dodata'])) {
                $msg = $this->lang->line('common_no_record_found');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('Admin/Reports/DOS');
            }
            //get staff details
            $table = DO_STAFF_TRANSECTION . ' as do';
            $where = array("do.do_id" => $id);
            $fields = array("do.*,CONCAT(l.firstname,' ', l.lastname) as staff_name");
            $join_tables = array(LOGIN . ' as l' => 'l.login_id= do.user_id');

            $data['do_staff_data'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', '', '', '', $where);
            //get food form
            $match = array('food_form_id' => 1);
            $food_forms = $this->common_model->get_records(FOOD_FORM, '', '', '', $match);
            if (!empty($food_forms)) {
                $data['food_form_data'] = json_decode($food_forms[0]['form_json_data'], TRUE);
            }

            //get food data
            $match = array('do_id' => $id);
            $data['food_data'] = $this->common_model->get_records(DO_FOODCONSUMED, '', '', '', $match);

            //get SUMMARIES form
            $match = array('do_form_id' => 1);
            $food_forms = $this->common_model->get_records(DO_FORM, '', '', '', $match);
            if (!empty($food_forms)) {
                $data['summary_form_data'] = json_decode($food_forms[0]['form_json_data'], TRUE);
            }
            //get last day do
            $table = DAILY_OBSERVATIONS . ' as do';
            $where = array("do.daily_observation_date" => "'" . date('Y-m-d', (strtotime('-1 day', strtotime($data['dodata'][0]['daily_observation_date'])))) . "'", "do.yp_id" => $ypid);
            $fields = array("do.do_id,do.handover_next_day");
            $data['lastDayData'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);

            $table = MEDICAL_PROFESSIONALS_APPOINTMENT . ' as dpa';
            $where = array(
                "dpa.yp_id" => $ypid,
                'appointment_date' => "'" . $data['dodata'][0]['daily_observation_date'] . "'",
                'is_delete' => 0
            );
            $fields = array("dpa.*");
            $data['do_professionals_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            //get planner appointment
            $table = PLANNER . ' as dpa';
            $where = array("dpa.yp_id" => $ypid,
                'appointment_date' => "'" . $data['dodata'][0]['daily_observation_date'] . "'",
                'is_delete' => 0
            );
            $fields = array("dpa.*");
            $data['do_planner_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            //get adminstry medication
            $table = ADMINISTER_MEDICATION . ' as mc';
            $fields = array("mc.*,md.stock");
            $where = array("mc.yp_id" => $ypid, 'mc.date_given' => $data['dodata'][0]['daily_observation_date']);
            $join_tables = array(MEDICATION . ' as md' => 'md.medication_id=mc.select_medication');
            $data['administer_medication'] = $this->common_model->get_records($table, $fields, $join_tables, 'left', '', '', '', '', 'administer_medication_id', 'desc', '', $where);
            //get am form
            $match = array('am_form_id' => 1);
            $formsdata = $this->common_model->get_records(AM_FORM, '', '', '', $match);
            if (!empty($formsdata)) {
                $data['form_data'] = json_decode($formsdata[0]['form_json_data'], TRUE);
            }
            $table = DO_PREVIOUS_VERSION . ' as do';
            $where = array("do.do_id" => $id, "do.yp_id" => $ypid);
            $fields = array("do.*");
            $data['do_prev_data'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
            //get food data
            $match = array('do_id' => $id, 'is_previous_version' => 1);
            $data['food_previous_data'] = $this->common_model->get_records(DO_FOODCONSUMED, '', '', '', $match);
            $data['do_id'] = $id;
            $data['ypid'] = !empty($data['dodata'][0]['yp_id']) ? $data['dodata'][0]['yp_id'] : '';

            $data['footerJs'][0] = base_url('uploads/custom/js/dailyobservation/dailyobservation.js');
            $data['header'] = array('menu_module' => 'YoungPerson');
            $data['main_content'] = '/DailyObservationView/view';
            $this->parser->parse('/assets/reporttemplate', $data);
        } else {

            show_404();
        }
    }

}
