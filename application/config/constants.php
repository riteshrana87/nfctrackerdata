<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | Display Debug backtrace
  |--------------------------------------------------------------------------
  |
  | If set to TRUE, a backtrace will be displayed along with php errors. If
  | error_reporting is disabled, the backtrace will not display, regardless
  | of this setting
  |
 */
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */

/*
  |--------------------------------------------------------------------------
  | Exit Status Codes
  |--------------------------------------------------------------------------
  |
  | Used to indicate the conditions under which the script is exit()ing.
  | While there is no universal standard for error codes, there are some
  | broad conventions.  Three such conventions are mentioned below, for
  | those who wish to make use of them.  The CodeIgniter defaults were
  | chosen for the least overlap with these conventions, while still
  | leaving room for others to be defined in future versions and user
  | applications.
  |
  | The three main conventions used for determining exit status codes
  | are as follows:
  |
  |    Standard C/C++ Library (stdlibc):
  |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
  |       (This link also contains other GNU-specific conventions)
  |    BSD sysexits.h:
  |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
  |    Bash scripting:
  |       http://tldp.org/LDP/abs/html/exitcodes.html
  |
 */
defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
  Author : Ritesh Rana
  Desc   : Define Constants Value
  Date   : 23/02/2017
 */
define('SAMPLE_TABLE', 'sampletable');
define('ERROR_DANGER_DIV', '<div class="alert alert-danger text-center">');
define('ERROR_SUCCESS_DIV', '<div class="alert alert-success text-center">');
define('ERROR_START_DIV', '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span>');
/* Added  By Sanket */
define('ERROR_START_DIV_NEW', '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span>');
define('ERROR_END_DIV', '</div>');
define('SITE_NAME', 'NFC Tracker');
/*
  Author : Ritesh Rana
  Desc   : Define Constants Value
  Date   : 23/02/2017
 */
define('LOG_MASTER', 'log_master');
define('ROLE_MASTER', 'role_master');

define('AAUTH_PERMS', 'aauth_perms');
define('AAUTH_PERMS_TO_ROLE', 'aauth_perm_to_group');
define('MODULE_MASTER', 'module_master');
define('PRODUCT_TAX_MASTER', 'product_tax_master');
define('CONFIG', 'config');
define('LOGIN', 'login');
define('LANGUAGE_MASTER', 'language_master');
define('CI_SESSION', 'ci_sessions');
define('COUNTRIES', 'countries');
define('RECORD_PER_PAGE', '10');
define('SALUTIONS_LIST', 'salutions_list');
define('PROFILE_PIC_UPLOAD_PATH', 'uploads/profile_photo');

define('PROFILE_PIC_HEIGHT', '36');
define('PROFILE_PIC_WIDTH', '36');
define('EMAIL_TEMPLATE_MASTER', 'email_template_master');


define('NO_OF_RECORDS_PER_PAGE', '10');

define('ALLOWED_ATTACHMENT_TYPE', "['jpg','jpeg','png','doc','docx','pdf','xls','xlsx']");
define('ALLOWED_IMAGE_ATTACHMENT_TYPE', "['jpg','jpeg','png']");
define('ALLOWED_MAX_FILE_SIZE', '8');

define('ADMIN_SITE', 'Admin');
define('CUSTOMER_TABLE', 'customers');
define('RA_FORM', 'ra_form');
define('YP_DETAILS', 'yp_details');
define('YP_DETAILS_CRON', 'yp_details_cron');
define('PLACING_AUTHORITY', 'placing_authority');
define('SOCIAL_WORKER_DETAILS', 'social_worker_details');
define('OVERVIEW_OF_YOUNG_PERSON', 'overview_of_young_person');
 
define('STAFF_NOTICES', 'staff_notices');
define('STAFF_NOTICES_UPLOADS', 'staff_notices_uploads');
define('SCHOOL_HANDOVER', 'school_handover');
define('SCHOOL_HANDOVER_FILE', 'school_handover_file');
define('CRISIS_HANDOVER', 'crisis_handover');
define('CRISIS_HANDOVER_FILE', 'crisis_handover_file');
define('PP_FORM', 'pp_form');
define('IBP_FORM', 'ibp_form');

define('PLACEMENT_PLAN', 'placement_plan');
define('PLACEMENT_PLAN_ARCHIVE', 'placement_plan_archive');
define('INDIVIDUAL_BEHAVIOUR_PLAN', 'individual_behaviour_plan');
define('IBP_ARCHIVE', 'ibp_archive');
define('RISK_ASSESSMENT', 'risk_assessment');
define('RISK_ASSESSMENT_ARCHIVE', 'risk_assessment_archive');
define('DAILY_OBSERVATIONS', 'daily_observations');
define('DO_ARCHIVE', 'do_archive');
define('DO_STAFF_TRANSECTION', 'do_staff_transection');
define('DO_FOODCONSUMED', 'do_foodconsumed');
define('DO_PREVIOUS_VERSION', 'do_previous_version');

define('COMMUNICATION_LOG', 'communication_log');
define('MEDICAL_COMMUNICATION', 'medical_communication');
define('MEDICAL_AUTHORISATIONS_CONSENTS', 'medical_authorisations_consents');
define('MEDICAL_PROFESSIONALS', 'medical_professionals');
define('OTHER_MEDICAL_INFORMATION', 'other_medical_information');
define('MEDICAL_INOCULATIONS', 'medical_inoculations');
define('MEDICAL_PROFESSIONALS_APPOINTMENT', 'medical_professionals_appointment');
define('MEDICATION', 'medication');
define('ADMINISTER_MEDICATION', 'administer_medication');

define('DO_FORM', 'do_form');
define('KS_FORM', 'ks_form');
define('CPT_FORM', 'cpt_form');
define('YPC_FORM', 'ypc_form');
define('DOCS_FORM', 'docs_form');
define('MAC_FORM', 'mac_form');
define('MP_FORM', 'mp_form');
define('OMI_FORM', 'omi_form');
define('MI_FORM', 'mi_form');
define('MC_FORM', 'mc_form');
define('M_FORM', 'm_form');
define('AM_FORM', 'am_form');
define('COMS_FORM', 'coms_form');
define('OVERVIEW_FORM', 'overview_form');
define('FOOD_FORM', 'food_form');
define('KEY_SESSION', 'key_session');
define('KS_ARCHIVE', 'ks_archive');
define('RMP_FORM', 'rmp_form');
define('CAREPLANTARGET', 'careplantarget');
define('CPT_COMMENTS', 'cpt_comments');

define('ACTIVITY_LOG', 'activity_log');
define('YP_DOCUMENTS', 'yp_documents');
define('MEDICAL_INFORMATION', 'medical_information');
define('HA_FORM', 'ha_form');
define('HEALTH_ASSESSMENT', 'health_assessment');
define('USER_JOBINFO', 'nfc_user_jobinfo');
define('EMPLOYMENT_STATUS', 'nfc_employment_status');
define('CONTACTS', 'nfc_contacts');
define('OB_COMMENTS', 'ob_comments');
define('KS_COMMENTS', 'ks_comments');
define('MD_COMMENTS', 'nfc_md_appointment_comment');
define('YPC_COMMENTS', 'ypc_comments');
define('PHOTO_GALLERY', 'photo_gallery');
define('GALLERY_PHOTO_UPLOADS', 'gallery_photo_uploads');
define('YP_CONCERNS', 'yp_concerns');

/*module name*/
define('PP_MODULE', 'PP');
define('IBP_MODULE', 'IBP');
define('RA_MODULE', 'RA');
define('DO_MODULE', 'DO');
define('DO_FOOD_MODULE', 'DO Food');
define('DO_OVERVIEW_MODULE', 'DO Overview');
define('DO_SUMMARY_MODULE', 'DO Summary');
define('MDT_REPORT_MODULE', 'MDT Report Module');

define('DOCS_MODULE', 'Document');
/*NIKUNJ GHELANI 29/8/2018*/
define('MODULE_DOCS_MODULE', 'DOCS');
/**/
define('KS_MODULE', 'KS');
define('CPT_MODULE', 'CPT');
define('RMP_MODULE', 'RMP');
define('CONCERNS_MODULE', 'Concerns');
/*nikunj ghelani 29/8/2018*/
define('YPC_CONCERNS_MODULE', 'YPC');
/*************************************/

define('MEDS_MODULE', 'MEDS');
define('MEDS_ALLERGIES_MODULE', 'Allergies & Meds not to be Used');
define('MEDS_MAC_MODULE', 'Medical Authorisations & Consents');
define('MEDS_MP_MODULE', 'Medical Professionals');
define('MEDS_OMI_MODULE', 'Other Medical Information');
define('MEDS_MI_MODULE', 'Inoculations');
define('MEDS_MC_MODULE', 'Medical Communication');
define('MEDS_M_MODULE', 'Medical Medication');
define('MEDS_AM_MODULE', 'Medical Administer Medication');
define('MEDS_HA_MODULE', 'Medical Health Assessment');
define('MEDS_APP_MODULE', 'Medical Appointment');
define('MEDS_YP_DETAILS', 'Medical young person details');
define('PLANNER_APP_MODULE', 'Planner Appointment');
define('COMS_MODULE', 'COMS');
define('YP_MODULE', 'YP');
define('PERSONAL_INFO_YP', 'Personal Info');
define('PLACING_AUTHORITY_YP', 'Placing Authority');
define('SOCIAL_WORKER_DETAILS_YP', 'Social Worker Details');
define('PARENT_CARER_DETAILS_YP', 'Parent/Carer`s Details');
/*nikunj ghelani for add short name at all activity*
29/8/2018
*/
define('LR_PARENT_CARER_DETAILS_YP', 'LR Health Assessment');
define('MDT_PARENT_CARER_DETAILS_YP', 'MDT Health Assessment');
define('DOCS_PARENT_CARER_DETAILS_YP', 'DOCS Health Assessment');
define('WR_PARENT_CARER_DETAILS_YP', 'WR Health Assessment');
define('MEDS_MEDS_HA_MODULE', 'MEDS Health Assessment');
define('MEDS_MEDS_AM_MODULE', 'MEDS Administer Medication');
define('MEDS_MEDS_M_MODULE', 'MEDS Medication');
define('MEDS_MEDS_MC_MODULE', 'MEDS Communication');
define('MEDS_MEDS_ALLERGIES_MODULE', 'MEDS Allergies & Meds not to be Used');
define('MEDS_MEDS_YP_DETAILS', 'MEDS young person details');
define('MEDS_MEDS_MAC_MODULE', 'MEDS Authorisations & Consents');
define('MEDS_MEDS_APP_MODULE', 'MEDS Appointment');
define('MEDS_MEDS_MP_MODULE', 'MEDS Professionals');
define('MEDS_MEDS_MI_MODULE', 'MEDS Inoculations');
define('MEDS_MEDS_OMI_MODULE', 'MEDS Other Medical Information');
define('DO_PARENT_CARER_DETAILS_YP', 'DO Parent/Carer`s Details');
define('COMS_PARENT_CARER_DETAILS_YP', 'COMS Parent/Carer`s Details');
define('PP_PARENT_CARER_DETAILS_YP', 'PP Parent/Carer`s Details');
define('KS_PARENT_CARER_DETAILS_YP', 'KS Parent/Carer`s Details');
define('YPC_PARENT_CARER_DETAILS_YP', 'YPC Parent/Carer`s Details');
define('IS_PARENT_CARER_DETAILS_YP', 'IS Parent/Carer`s Details');
define('RA_PARENT_CARER_DETAILS_YP', 'RA Parent/Carer`s Details');
define('IBP_PARENT_CARER_DETAILS_YP', 'IBP Parent/Carer`s Details');
define('CPT_PARENT_CARER_DETAILS_YP', 'CPT Parent/Carer`s Details');
/****************************************************/
define('PEN_PORTRAIT_YP', 'Pen Portrait & Risk Oversight');
define('STAFF_NOTICE_MODULE', 'STAFF NOTICE');
define('YP_PHOTO_UPDATED', 'YP Photo Updated');
define('YP_NEW_ADD', 'New Yp');
/*NIKUNJ GHELANI 29/8/2018*/
define('COMS_YP_NEW_ADD', 'COMS New Yp');
define('LP_NEW_ADD', 'LR New Yp');
/****************************/
define('SCHOOL_HANDOVER_MODULE', 'SCHOOL HANDOVER FOR CRISIS');
define('CRISIS_HANDOVER_MODULE', 'Care Handover For School');
define('YP_PHOTO_GALLERY', 'Photo Gallery');
define('SITENAME', 'NFCTracker');
define('POCKET_MONEY_MODULE', 'Pocket Money');
define('CLOTHING_MONEY_MODULE', 'Clothing Money');
define('SDQ_MODULE', 'SDQ');
define('CSE_MODULE', 'CSE Report');


//ishani dave
define('NFC_ADMIN_SDQ', 'nfc_admin_sdq');
define('NFC_ADMIN_SDQ_SUB', 'nfc_admin_sdq_subque');
define('NFC_SDQ_RECORD', 'nfc_sdq_recordsheet');
define('NFC_SDQ_RECORD_ANS', 'nfc_record_ans');
define('USER_THEME_COLOR', 'user_theme_color');
define('CHECK_EDIT_URL', 'check_edit_url');
define('PLACEMENT_PLAN_SIGNOFF', 'placement_plan_signoff');
define('INDIVIDUAL_BEHAVIOUR_PLAN_SIGNOFF', 'individual_behaviour_plan_signoff');
define('RISK_ASSESSMENT_SIGNOFF', 'risk_assessment_signoff');
define('DOCUMENT_SIGNOFF', 'document_signoff');
define('KEYSESSION_SIGNOFF', 'keysession_signoff');
define('CONCERNS_SIGNOFF', 'concerns_signoff');
define('DO_SIGNOFF', 'do_signoff');
define('MEDS_SIGNOFF', 'meds_signoff');
define('COMS_SIGNOFF', 'coms_signoff');
define('HA_COMMENTS', 'ha_comments');
define('HA_RECOMMENDATIONS', 'ha_recommendations');
define('CARE_HOME', 'care_home');
define('AAI_EMAIL_TEMPLATE', 'aai_email_template');
define('AAI_RECEIPT', 'nfc_aai_receipt');
define('AAI_DROPDOWN', 'aai_dropdown');
define('AAI_DROPDOWN_OPTION', 'aai_dropdown_option');
define('AAI_FORM', 'aai_form');
//define('AAI_MAIN', 'aai_main');
define('AAI_MAIN', 'aai_main_table');
//define('AAI_MAIN', 'aai_main_table');

define('AAI_LIST_MAIN', 'aai_list_main');
define('ARCHIVE_AAI_MAIN', 'archive_aai_main');
/*this form ids used for form number */
define('AAI_MAIN_ENTRY_FORM', '1');
define('AAI_INCIDENT_TYPE_FORM', '2');
define('AAI_L1_FORM', '3');
define('AAI_L2NL3_FORM', '4');
define('AAI_L4_FORM', '5');
define('AAI_L5_FORM', '6');
define('AAI_L6_FORM', '7');
define('AAI_L7_FORM', '8');
define('AAI_L8_FORM', '9');
define('AAI_L9_FORM', '10');
/*this form ids referenced to the aai_form table */
define('AAI_MAIN_ENTRY_FORM_ID', '1');
define('AAI_INCIDENT_TYPE_FORM_ID', '2');
define('AAI_L1_FORM_ID', '3');
define('AAI_L2NL3_FORM_ID', '4');
define('AAI_L4_FORM_ID', '5');
define('AAI_L5_FORM_ID', '6');
define('AAI_L6_FORM_ID', '7');
define('AAI_L7_FORM_ID', '8');
define('AAI_L8_FORM_ID', '9');
define('AAI_L9_FORM_ID', '10');
define('AAI_FORM_SAVE_AS_DRAFT', '11');
define('AAI_FORM_COMPLETED', '12');
/*code by ritesh rana 04-02-2019 */

define('AAI_ENTRY_FORM_DATA', 'aai_entry_form');
define('AAI_INCIDENT_TYPE_DATA', 'aai_incident_type');
define('AAI_L1_FORM_DATA', 'aai_l1_form');
define('AAI_L2_L3_FORM_DATA', 'aai_l2_l3_form');
define('AAI_L4_FORM_DATA', 'aai_l4_form');
define('AAI_L5_FORM_DATA', 'aai_l5_form');
define('AAI_L6_FORM_DATA', 'aai_l6_form');
define('AAI_L7_FORM_DATA', 'aai_l7_form');
define('AAI_L8_FORM_DATA', 'aai_l8_form');
define('AAI_L9_FORM_DATA', 'aai_l9_form');
define('AAI_REVIEW_FORM_DATA', 'aai_review_form');
define('AAI_MANAGER_REVIEW_FORM_DATA', 'aai_manager_review_form');
define('AAI_ARCHIVE', 'aai_archive');


define('AAI_L2_L3_SEQUENCE_EVENT', 'aai_l2_l3_sequence_event');
define('AAI_L2_WHO_WAS_INVOLVED', 'aai_l2_who_was_involved');
define('AAI_L2_L3_MEDICAL_OBSERVATION', 'aai_l2_l3_medical_observation');
define('AAI_L2_MEDICAL_OBSERVATION_TAKEN', 'aai_l2_medical_observation_taken');

define('AAI_L2_L3_SEQUENCE_ARCHIVE', 'aai_l2_l3_sequence_archive');
define('AAI_L2_WHO_WAS_INVOLVED_ARCHIVE', 'aai_l2_who_was_involved_archive');

define('AAI_L2_L3_MEDOBS_ARCHIVE', 'aai_l2_l3_medobs_archive');

define('AAI_L2_ARCHIVE_MEDICAL_OBSERVATION_TAKEN', 'aai_l2_archive_medical_observation_taken');
define('AAI_L6_SEQUENCE_EVENT', 'aai_l6_sequence_event');
define('AAI_L6_SEQUENCE_ARCHIVE', 'aai_l6_sequence_archive');

define('AAI_L4_PERSON_INFORMED_MISSING', 'aai_l4_person_inform_missing');
define('AAI_L4_PERSON_INFORMED_RETURN', 'aai_l4_person_inform_return');
define('AAI_L4_SEQUENCE_EVENT', 'aai_l4_sequence_event');

define('AAI_L4_PERSON_INFORMED_MISSING_ARCHIVE', 'aai_l4_person_missing_archive');
define('AAI_L4_PERSON_INFORMED_RETURN_ARCHIVE', 'aai_l4_person_return_archive');
define('AAI_L4_SEQUENCE_EVENT_ARCHIVE', 'aai_l4_sequence_archive');

define('AAI_L7_SAFEGUARDING_UPDATES', 'aai_l7_safeguarding_updates');
define('AAI_L7_SAFEGUARDING_ARCHIVE', 'aai_l7_safeguarding_archive');


/*drop down constants*/
define('MALE', 26);
define('FEMALE', 27);










define('REVIEW', '11');
define('MANAGER_REVIEW', '12');

define('VIEW_AAI_MAIN_ENTRY_FORM', 'main');
define('VIEW_AAI_INCIDENT_TYPE_FORM', 'type');
define('VIEW_AAI_L1_FORM', '1');
define('VIEW_AAI_L2NL3_FORM', '1');
define('VIEW_AAI_L4_FORM', '4');
define('VIEW_AAI_L5_FORM', '5');
define('VIEW_AAI_L6_FORM', '6');
define('VIEW_AAI_L7_FORM', '7');
define('VIEW_AAI_L8_FORM', '8');
define('VIEW_AAI_L9_FORM', '9');

define('OTHER_OPTION', '48');
define('EXTERNAL_AGENCY_OTHER_OPTION', '58');

define('BAMBOOHR_USERS', 'bamboohr_users');
define('BAMBOOHR_API_KEY', '4cf2fa4db6a143e49617b6c8c35d2a2f47354405');
define('BAMBOOHR_COMPANY_ID', 'nfccmetricsandbox');

define('PAST_CARE_HOME_INFO', 'nfc_past_carehome_info');

// admin cse
define('NFC_ADMIN_CSE', 'nfc_admin_cse');
define('NFC_ADMIN_CSE_SUB', 'nfc_admin_cse_subque');
define('NFC_CSE_RECORD', 'nfc_cse_recordsheet');
define('NFC_CSE_RECORD_ANS', 'nfc_cse_record_ans');

//Individual Strategies meeting
define('IS_FORM', 'is_form');
define('INDIVIDUAL_STRATEGIES', 'individual_strategies');
define('INDIVIDUAL_STRATEGIES_ARCHIVE', 'individual_strategies_archive');
define('IS_MODULE', 'IS');
define('INDIVIDUAL_RECORDS_DATE_STAFF', 'nfc_individual_records_date_staff');
define('NFC_AMENDMENTS', 'nfc_amendments');
define('NFC_ARCHIVE_AMENDMENTS', 'nfc_archive_amendments');
define('NFC_CURRENT_PROTOCOLS_IN_PLACE', 'nfc_current_protocols_in_place');
define('NFC_ARCHIVE_CURRENT_PROTOCOLS_IN_PLACE', 'nfc_archive_current_protocols_in_place');
define('INDIVIDUAL_MEETING_SIGNOFF', 'nfc_individual_meeting_signoff');
define('NFC_ARCHIVE_INDIVIDUAL_MEETING_SIGNOFF', 'nfc_archive_individual_meeting_signoff');
define('FROM_EMAIL_ID', 'noreply@newforestcare.co.uk');
define('SUPER_ADMIN_EMAIL_ID', 'CM.Superadmin@newforestcare.co.uk');
define('SETTINGS_PROFILE_PIC_UPLOAD_PATH', 'uploads/assets/front/images');
define('NFC_INDIVIDUAL_MEETING_LOG', 'individual_meeting_log');

/*mtd -Niral patel*/
define('MDT_FORM', 'mdt_form');
define('MDT_REPORT', 'mdt_report');
define('MDT_REPORT_ARCHIVE', 'mdt_report_archive');
define('MDT_MODULE', 'MDT');
define('MDT_CARE_PLAN_TARGET', 'mdt_care_plan_target');
define('MDT_REGULAR_HOBBIES', 'mdt_regular_hobbies');
define('MDT_PHYSICAL_EXERCISE', 'mdt_physical_exercise');
define('MDT_INCIDENT', 'mdt_incident');
define('MDT_SANCTION', 'mdt_sanction');
define('MDT_LIFE_SKILLS', 'mdt_life_skills');
define('MDT_REPORT_SIGNOFF', 'mdt_report_signoff');
define('MDT_SIGNOFF_DETAILS', 'mdt_signoff_details');
define('MDT_ARCHIVE_MDT_REPORT_SIGNOFF', 'mdt_archive_mdt_report_signoff');
define('MDT_CARE_PLAN_TARGET_ARCHIVE', 'mdt_care_plan_target_archive');
define('MDT_REGULAR_HOBBIES_ARCHIVE', 'mdt_regular_hobbies_archive');
define('MDT_PHYSICAL_EXERCISE_ARCHIVE', 'mdt_physical_exercise_archive');
define('MDT_INCIDENT_ARCHIVE', 'mdt_incident_archive');
define('MDT_SANCTION_ARCHIVE', 'mdt_sanction_archive');
define('MDT_LIFE_SKILLS_ARCHIVE', 'mdt_life_skills_archive');
define('MDT_APPROVAL_SIGNOFF', 'mdt_approval_signoff');
define('MDT_CARE_PLAN_TARGET_WEEK', 'mdt_care_plan_target_week');
define('MDT_CARE_PLAN_TARGET_WEEK_ARCHIVE', 'mdt_care_plan_target_week_archive');
define('MDT_CARE_PLAN_TARGET_PREVIOUS', 'mdt_care_plan_target_previous');
define('MDT_CARE_PLAN_TARGET_PREVIOUS_ARCHIVE', 'mdt_care_plan_target_previous_archive');


define('PARENT_CARER_INFORMATION', 'parent_carer_information');


//old database name for Individual Strategies meeting
define('CARE_PLAN_MEETING', 'nfc_individual_records');
define('INDIVIDUAL_MEETING_RECORDS', 'nfc_individual_meeting_records');

define('NFC_ARCHIVE_INDIVIDUAL_RECORDS', 'nfc_archive_individual_records');
define('NFC_ARCHIVE_PLACEMENT_PLAN_SIGNOFF', 'nfc_archive_placement_plan_signoff');
define('NFC_SIGNOFF_DETAILS', 'nfc_signoff_details');
define('NFC_IBP_SIGNOFF_DETAILS', 'nfc_ibp_signoff_details');
define('NFC_INDIVIDUAL_BEHAVIOUR_PLAN', 'nfc_individual_behaviour_plan');
define('NFC_INDIVIDUAL_STRATEGIES', 'nfc_individual_strategies');

define('NFC_ARCHIVE_INDIVIDUAL_BEHAVIOUR_PLAN_SIGNOFF', 'nfc_archive_individual_behaviour_plan_signoff');
define('NFC_ARCHIVE_DO_SIGNOFF', 'nfc_archive_do_signoff');
define('NFC_ARCHIVE_RA_SIGNOFF', 'nfc_archive_ra_signoff');
define('NFC_DO_SIGNOFF_DETAILS', 'nfc_do_signoff_details');
define('NFC_DO_SIGNOFF_LOG', 'Do sign off');
define('NFC_KS_SIGNOFF_DETAILS', 'nfc_ks_signoff_details');
define('NFC_IS_SIGNOFF_DETAILS', 'nfc_is_signoff_details');
define('NFC_RA_SIGNOFF_DETAILS', 'nfc_ra_signoff_details');

// Added By Mehul Patel
define('MDT_CPR_FORM', 'nfc_mdt_cpr_form');
define('MDT_CARE_PLAN_TARGET_IDENITIFIED_MODULE', 'MDT_cpti');
define('MDT_CARE_PLAN_REPORT', 'mdt_care_plan_report');

// Tithi Pate;
define('WR_FORM', 'nfc_wr_form');
define('WEEKLY_REPORT', 'nfc_weekly_report');
define('WR_ARCHIVE', 'wr_archive');
define('WEEKLY_REPORT_SIGNOFF', 'weekly_report_signoff');
define('NFC_WR_SIGNOFF_DETAILS', 'nfc_wr_signoff_details');
define('WR_MODULE', 'WR');
define('DO_LOG', 'do_log');
define('DOCUMENTS', 'Documents');
define('DAILYOBSERVATION', 'DailyObservation');
/*if this constant used somewhere so i create new one
nikunj ghelani
*/
define('DAILYOBSERVATION_MODULE', 'DO');
/***************************************************/
define('ARCHIVE_DAILYOBSERVATION', 'ArchiveDailyObservation');
define('NFC_ARCHIVE_WEEKLY_REPORT_SIGNOFF', 'nfc_archive_weekly_report_signoff');
define('PAST_CARE_HOME', 'Past Care Home');
define('WR_REPORT_SENT', 'nfc_wr_report_sent');
define('NFC_YPC_SIGNOFF_DETAILS', 'nfc_ypc_signoff_details');
define('NFC_APPROVAL_PLACEMENT_PLAN_SIGNOFF', 'nfc_approval_placement_plan_signoff');
define('NFC_APPROVAL_KEYSESSION_SIGNOFF', 'nfc_approval_keysession_signoff');
define('NFC_APPROVAL_YPC_SIGNOFF', 'nfc_approval_ypc_signoff');
define('NFC_COMS_SIGNOFF_DETAILS', 'nfc_coms_signoff_details');
define('NFC_DOCS_SIGNOFF_DETAILS', 'nfc_docs_signoff_details');
define('NFC_APPROVAL_COMS_SIGNOFF', 'nfc_approval_coms_signoff');
define('NFC_APPROVAL_DOCS_SIGNOFF', 'nfc_approval_docs_signoff');
define('NFC_APPROVAL_DO_SIGNOFF', 'nfc_approval_do_signoff');
define('NFC_ARCHIVE_OB_COMMENTS', 'nfc_archive_ob_comments');
define('NFC_APPROVAL_OB_COMMENTS', 'nfc_approval_ob_comments');
define('NFC_APPROVAL_AMENDMENTS', 'nfc_approval_amendments');
define('NFC_APPROVAL_CURRENT_PROTOCOLS_IN_PLACE', 'nfc_approval_current_protocols_in_place');
define('NFC_APPROVAL_RA_SIGNOFF', 'nfc_approval_ra_signoff');
define('NFC_APPROVAL_INDIVIDUAL_BEHAVIOUR_PLAN_SIGNOFF', 'nfc_approval_individual_behaviour_plan_signoff');
define('DO_PROFESSIONALS_APPOINTMENT', 'nfc_do_professionals_appointment');
define('NFC_APPROVAL_INDIVIDUAL_MEETING_SIGNOFF', 'nfc_approval_individual_meeting_signoff');
define('NFC_DAILY_STOCK_CHECK', 'nfc_daily_stock_check');
define('POCKET_MONEY', 'pocket_money');
define('CLOTHING_MONEY', 'clothing_money');
define('YP_POCKET_MONEY', 'yp_pocket_money');
define('YP_CLOTHING_MONEY', 'yp_clothing_money');
define('PLANNER', 'planner_appointment');
define('DO_PLANNER_APPOINTMENT', 'do_planner_appointment');
define('MOVE_TO_CAREHOME', 'move_to_carehome');
define('pp_health', 'nfc_pp_health');
define('pp_health_archive', 'nfc_pp_health_archive');
define('pp_edu', 'nfc_pp_edu');
define('pp_edu_archive', 'nfc_pp_edu_archive');
define('pp_tra_archive', 'nfc_pp_tra_archive');
define('pp_con_archive', 'nfc_pp_con_archive');
define('pp_ft_archive', 'nfc_pp_ft_archive');
define('pp_mgi_archive', 'nfc_pp_mgi_archive');
define('pp_pr_archive', 'nfc_pp_pr_archive');
define('pp_bc_archive', 'nfc_pp_bc_archive');
define('pp_tra', 'nfc_pp_tra');
define('pp_con', 'nfc_pp_con');
define('pp_ft', 'nfc_pp_ft');
define('pp_mgi', 'nfc_pp_mgi');
define('pp_pr', 'nfc_pp_pr');
define('pp_bc', 'nfc_pp_bc');
define('pp_aims_of_placement', 'pp_aims_of_placement');
define('archive_pp_aims_of_placement', 'archive_pp_aims_of_placement');
define('pp_actions_from_lac_review', 'pp_actions_from_lac_review');
define('pp_archive_actions_from_lac_review', 'pp_archive_actions_from_lac_review');


define('YPM_FORM', 'ypm_form');
define('YCM_FORM', 'ycm_form');
define('LR_FORM', 'lr_form');
define('LOCATION_REGISTER', 'location_register');
define('LOCATION_REGISTER_SIGNOFF', 'location_register_signoff');
define('APPROVAL_LOCATION_REGISTER_SIGNOFF', 'approval_location_register_signoff');
define('LR_SIGNOFF_DETAILS', 'lr_signoff_details');

define('LR_MODULE', 'LR');

define('USER_SET_LOGOUT_TIME', 'user_set_logout_time');
define('GROUP_MASTER', 'group_master');
//rmp
define('RMP', 'rmp');
define('RMP_SIGNOFF', 'RMP_SIGNOFF');
define('RMP_SIGNOFF_DETAILS', 'rmp_signoff_details');
define('RMP_COMMENTS', 'rmp_comments');
define('NFC_ARCHIVE_RMP_SIGNOFF', 'archive_rmp_signoff');
define('RMP_ARCHIVE', 'rmp_archive');
define('NFC_APPROVAL_RMP_SIGNOFF', 'approval_rmp_signoff');

//medication stock

define('MEDICAL_CARE_HOME_TRANSECTION', 'medical_care_home_transection');
define('ROLE_GROUP_TRANS', 'role_group_trans');

define('REPORT_EXPAIRED_DAYS', ' + 3 days');
define('REPORT_EXPAIRED_HOUR', '72 hours');

//Mail 
define('EMAIL_CLIENT_MASTER', 'email_client_master');
define('EMAIL_CLIENT_ATTACHMENTS', 'email_client_attachments');
define('CONTACT_MASTER', 'contact_master');
define('EMAIL_CONFIG', 'email_config');

define('EMAIL_SETTINGS', 'email_settings');
define('TBL_MESSAGE_MASTER', 'message_master');
define('HELP', 'help');
define('FROM_EMAIL_HELP', 'tracker@newforestcare.co.uk');
define('GROUP_NAME', 'nfc_group_name');
define('DEVELOPMENT', 'Development');
define('LOCALHOST', 'localhost');
define('DEVTRACKER', 'devtracker');
define('PREPRODTRACKER', 'preprodtracker');

define('PREPRODUCTION', 'PreProduction');
define('TRACKER', 'tracker');
define('PRODUCTION', 'Production');
define('YP_EMAIL', 'tracker@newforestcare.co.uk');
define('FILE_ICON_PATH', 'uploads/images/icons 64/file-ico.png');
define('NFCTracker', 'NFC Tracker');
define('NFSTracker', 'NFS Tracker');
define('SANCTION_FORM', 'sanction_form');
define('SANCTION', 'Sanction');
define('AAI_SANCTIONS', 'aai_sanctions');
define('AAI_SIGNOFF', 'aai_signoff');
define('AAI_SIGNOFF_APPROVAL', 'aai_signoff_approval');

//If Laying id 67 / Ground holds selected then Form is L3
define('LAYING_ID', '67');
define('CPT_SIGNOFF', 'cpt_signoff');
define('APPROVAL_CPT_SIGNOFF', 'approval_cpt_signoff');
define('CPT_SIGNOFF_DETAILS', 'cpt_signoff_details');
define('AAI_REPORT_COMPILER', 'aai_report_compiler');
define('SANCTIONS_PROCESS', 'sanctions_process');

define('AAI_MODULE', 'AAI');
define('AAI_MODULE_ENTRY_FORM', 'AAI -> Main Entry Form');
define('AAI_MODULE_TYPE_FORM', 'AAI -> Type of Incident Form');
define('AAI_MODULE_L4_FORM', 'AAI -> L4 Form');
define('AAI_MODULE_L9_FORM', 'AAI -> L9 Form');






























