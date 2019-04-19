<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | Base Site URL
  |--------------------------------------------------------------------------
  |
  | URL to your CodeIgniter root. Typically this will be your base URL,
  | WITH a trailing slash:
  |
  | http://example.com/
  |
  | WARNING: You MUST set this value!
  |
  | If it is not set, then CodeIgniter will try guess the protocol and path
  | your installation, but due to security concerns the hostname will be set
  | to $_SERVER['SERVER_ADDR'] if available, or localhost otherwise.
  | The auto-detection mechanism exists only for convenience during
  | development and MUST NOT be used in production!
  |
  | If you need to allow multiple domains, remember that this file is still
  | a PHP script and you can easily do that on your own.
  |
 */
/**
 * User Module
 * [controller name][action] = array('respective function name for the Controller')
 */

//Admin
$config['Admin']['add'] = array('do_login', 'forgot_password', 'reset_password', 'add_new_password', 'check_user');
$config['Admin']['delete'] = array('deletedata', 'isDuplicateEmail');
$config['Admin']['edit'] = array('edit', 'updatedata', 'isDuplicateEmail');
$config['Admin']['view'] = array('index', 'userlist', 'view', 'isDuplicateEmail', 'testmail');


//Admin/Dashboard
$config['Dashboard']['add'] = array('staffNotices','insertStaffNotices','download','schollHandover','insertSchollHandover','schollHandoverUploadFile','editstaff','CrisisHandover','CrisisHandoverUploadFile','insertCrisisHandover','CrisisHandoverFiledownload','getCrisisHandUploadData');
$config['Dashboard']['delete'] = array('deleteStaff');
$config['Dashboard']['edit'] = array('');
$config['Dashboard']['view'] = array('index','staffNotices','insertStaffNotices','download','schollHandover','insertSchollHandover','schollHandoverUploadFile','HandoverFiledownload','editstaff','CrisisHandover','CrisisHandoverUploadFile','insertCrisisHandover','CrisisHandoverFiledownload','getCrisisHandUploadData');


//Admin/Dashboard
$config['PhotoGallery']['add'] = array('addphoto','insertPhotoGallery','download','editphoto','upload_file');
$config['PhotoGallery']['delete'] = array('deletePhoto');
$config['PhotoGallery']['edit'] = array('updatePhotoGallery');
$config['PhotoGallery']['view'] = array('index','addphoto','insertPhotoGallery','download','editphoto','upload_file');



//User
$config['User']['add'] = array('insertdata', 'registration', 'isDuplicateEmail', 'assignModuleCount','getUserData','add','test_mail');
$config['User']['delete'] = array('deletedata', 'isDuplicateEmail','getUserData');
$config['User']['edit'] = array('edit', 'updatedata', 'isDuplicateEmail','getUserData');
$config['User']['view'] = array('index', 'userlist', 'view', 'isDuplicateEmail', 'testmail','getUserData');



$config['Home']['view'] = array('index', 'changeview', 'grantview', 'get_home_header', 'get_home_activity');
$config['Errors']['view'] = array('index');

// Rolemaster Module
$config['Rolemaster']['add'] = array('insertdata', 'add', 'addPermission', 'insertPerms', 'assignPermission', 'addModule', 'insertAssginPerms', 'insertModule', 'checkRoleStatus', 'checkRoleAssignedToUser', 'permissionTab','updateRolebasedUserCreationCount','assignModuleCount','editTimeCheckPurchasedUserLimit','updateTimeCheckUserAvailbility', 'editPermission');
$config['Rolemaster']['delete'] = array('deletedata', 'deletePerms', 'deleteAssignperms', 'deleteModuleData', 'permissionTab','editTimeCheckPurchasedUserLimit','updateTimeCheckUserAvailbility');
$config['Rolemaster']['edit'] = array('edit', 'updatedata', 'editPerms', 'updatePerms', 'editPermission', 'editModule', 'updateModule', 'insertAssginPerms', 'permissionTab','updateRolebasedUserCreationCount','assignModuleCount','editTimeCheckPurchasedUserLimit','updateTimeCheckUserAvailbility');
$config['Rolemaster']['view'] = array('index', 'role_list', 'view_perms_to_role_list', 'checkRoleStatus', 'checkRoleAssignedToUser', 'permissionTab');

//Module Master
$config['ModuleMaster']['add'] = array('add','insertModule', 'formValidation');
$config['ModuleMaster']['edit'] = array('edit', 'updateModule' ,'formValidation');
$config['ModuleMaster']['delete'] = array('deleteModuleData');
$config['ModuleMaster']['view'] = array('index');


//Form builder
$config['FormBuilder']['add'] = array('add','insertModule', 'formValidation');
$config['FormBuilder']['edit'] = array('edit', 'updateModule' ,'formValidation');
$config['FormBuilder']['delete'] = array('deleteModuleData');
$config['FormBuilder']['view'] = array('index');


//Young Person
$config['YoungPerson']['add'] = array('CronJobYPADD','canclecron','CronJob','insertdata', 'registration', 'isDuplicateEmail', 'assignModuleCount', 'personal_info','placingAuthority','updatePlacingAuthority','socialWorkerDetails','updateSocialWorkerDetails','parentCarerInformation','updateParentCarerDetails','overviewOfYoungPerson','updateOverviewOfYoungPerson','ProfileInfo','updateProfileInfo','upload_file','isDuplicateInitials','editPlacingAuthority','addParentCarerInformation','updateParentCarerDetails');
$config['YoungPerson']['delete'] = array('CronJobYPADD','canclecron','CronJob','deletedata', 'isDuplicateEmail','delete_past_carehome','removeParentCarerInformation');
$config['YoungPerson']['edit'] = array('CronJobYPADD','canclecron','CronJob','edit', 'updatedata', 'isDuplicateEmail','personal_info','updatePersonalInfo','placingAuthority','updatePlacingAuthority','socialWorkerDetails','updateSocialWorkerDetails','editParentCarerInformation','updateParentCarerDetails','overviewOfYoungPerson','updateOverviewOfYoungPerson','ProfileInfo','updateProfileInfo','upload_file','isDuplicateInitials','editPlacingAuthority');
$config['YoungPerson']['view'] = array('CronJobYPADD','canclecron','CronJob','index', 'userlist', 'view', 'isDuplicateEmail', 'testmail', 'personal_info','placingAuthority','updatePlacingAuthority','socialWorkerDetails','updateSocialWorkerDetails','parentCarerInformation','updateParentCarerDetails','overviewOfYoungPerson','updateOverviewOfYoungPerson','ProfileInfo','updateProfileInfo','upload_file','medicationStockCheck','checkedStock','stockComment','stockAdjustment','readmore_comment','checkMedicationStock','stockCheckLog','createArchive');
//Young Person
$config['ArchiveYoungPerson']['add'] = array();
$config['ArchiveYoungPerson']['delete'] = array();
$config['ArchiveYoungPerson']['edit'] = array();
$config['ArchiveYoungPerson']['view'] = array('index', 'userlist', 'view');
$config['ArchiveYoungPerson']['hidden_archive'] = array('undoArchive');
$config['ArchiveYoungPerson']['comment'] = array();
$config['ArchiveYoungPerson']['signoff'] = array();
$config['ArchiveYoungPerson']['document_signoff'] = array('');

//Young Person
$config['MedicationStock']['add'] = array('generateExcelFile');
$config['MedicationStock']['delete'] = array('generateExcelFile');
$config['MedicationStock']['edit'] = array('generateExcelFile');
$config['MedicationStock']['view'] = array('generateExcelFile','index','medicationStockCheck','checkedStock','stockComment','stockAdjustment','readmore_comment','checkMedicationStock','stockCheckLog','readmore_medication_comment');
$config['MedicationStock']['hidden_archive'] = array('createArchive','medicationArchiveStock','undoArchive');
$config['MedicationStock']['comment'] = array();
$config['MedicationStock']['signoff'] = array();
$config['MedicationStock']['document_signoff'] = array('');
// Reports
$config['Reports']['add'] = array('showReportList','generateExcelFile','downloadExcelFile','generateExcelFileUrl','filterExcelData');
$config['Reports']['edit'] = array('showReportList','generateExcelFile','downloadExcelFile','generateExcelFileUrl','filterExcelData');
$config['Reports']['view'] = array('index','PP','IBP','RA','DOC','KS','DOCS','MODC','COMS');
$config['Reports']['delete'] = array();

//PP
$config['PlacementPlan']['add'] = array('DownloadPdf','DownloadPrint','manager_review','edit_slug','update_slug','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','test_mail');
$config['PlacementPlan']['edit'] = array('DownloadPdf','edit','insert','save_pp','DownloadPrint','manager_review','update_slug','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','test_mail');
$config['PlacementPlan']['view'] = array('DownloadPdf','index','signoff','manager_review','sendMailToRelation','signoffData','insertdata','signoff_review_data','test_mail','getUserTypeDetail','external_approval_list','view');
$config['PlacementPlan']['delete'] = array();
$config['PlacementPlan']['hidden_archive'] = array();
$config['PlacementPlan']['comment'] = array();
$config['PlacementPlan']['signoff'] = array();
$config['PlacementPlan']['document_signoff'] = array('signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','resend_external_approval');


//IBP
$config['Ibp']['add'] = array('DownloadPdf','DownloadPrint','manager_review','edit_slug','update_slug','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data');
$config['Ibp']['edit'] = array('DownloadPdf','edit','insert','save_ibp','DownloadPrint','manager_review','update_slug','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data');
$config['Ibp']['view'] = array('DownloadPdf','index','DownloadPrint','manager_review','sendMailToRelation','signoffData','insertdata','signoff_review_data','getUserTypeDetail','external_approval_list','viewApprovalIbp');
$config['Ibp']['delete'] = array();
$config['Ibp']['hidden_archive'] = array();
$config['Ibp']['signoff'] = array();
$config['Ibp']['comment'] = array();
$config['Ibp']['document_signoff'] = array('signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','resend_external_approval');


//RiskAssesment
$config['RiskAssesment']['add'] = array('DownloadPdf','DownloadPrint','manager_review','edit_slug','update_slug','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data');
$config['RiskAssesment']['edit'] = array('DownloadPdf','edit','insert','save_ra','DownloadPrint','manager_review','update_slug','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data');
$config['RiskAssesment']['view'] = array('DownloadPdf','index','signoff','manager_review','sendMailToRelation','signoffData','insertdata','signoff_review_data','getUserTypeDetail');
$config['RiskAssesment']['delete'] = array();
$config['RiskAssesment']['hidden_archive'] = array();
$config['RiskAssesment']['signoff'] = array();
$config['RiskAssesment']['comment'] = array();
$config['RiskAssesment']['document_signoff'] = array('signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','external_approve','external_view','resend_external_approval');

//DailyObservation
$config['DailyObservation']['add'] = array('DownloadPdf','createDo','checkDo','DownloadPrint','isDuplicateDo','add_commnts','manager_review','update_slug','update','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','add_overview','insert_overview','save_overview','add_staff','insert_staff','save_staff','MedicalProfessionalsAppointment','DailyObservations','AppointmentViewComments');
$config['DailyObservation']['edit'] = array('DownloadPdf','add_overview','insert_overview','save_overview','add_staff','insert_staff','save_staff','DownloadPrint','isDuplicateDo','add_commnts','manager_review','update_slug','edit_do','update','save_do_data','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','MedicalProfessionalsAppointment','DailyObservations','AppointmentViewComments');
$config['DailyObservation']['view'] = array('DownloadPdf','index','view','manager_review','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','getUserTypeDetail','MedicalProfessionalsAppointment','DailyObservations','AppointmentViewComments');
$config['DailyObservation']['delete'] = array('deleteDo','delete_staff');
$config['DailyObservation']['hidden_archive'] = array();
$config['DailyObservation']['signoff'] = array('DownloadPdf');
$config['DailyObservation']['comment'] = array();
$config['DailyObservation']['document_signoff'] = array('signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','resend_external_approval','viewApprovalDo','external_approval_list','MedicalProfessionalsAppointment','DailyObservations','AppointmentViewComments');
$config['DailyObservation']['external_edit'] = array();


//KeySession
$config['KeySession']['add'] = array('DownloadPdf','create','insert','save_ks','DownloadPrint','manager_review','add_commnts','edit_draft','insert_draft_ks','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data');
$config['KeySession']['edit'] = array('DownloadPdf','manager_review','edit_draft','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data');
$config['KeySession']['view'] = array('DownloadPdf','index','readmore','view','DownloadPrint','manager_review','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','getUserTypeDetail','external_approval_list','viewApprovalKs');
$config['KeySession']['delete'] = array('deletedata');
$config['KeySession']['hidden_archive'] = array();
$config['KeySession']['Move_KS_YPC'] = array('moveToYpc');
$config['KeySession']['Conclude_KS_YPC'] = array('concludeKS');
$config['KeySession']['signoff'] = array();
$config['KeySession']['comment'] = array();
$config['KeySession']['document_signoff'] = array('signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','resend_external_approval');

//Concerns
$config['Concerns']['add'] = array('DownloadPdf','create','insert','saveConcerns','DownloadPrint','manager_review','add_commnts','edit_draft','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','insert_draft_ypc');
$config['Concerns']['edit'] = array('DownloadPdf','manager_review','edit_draft','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data');
$config['Concerns']['view'] = array('DownloadPdf','index','readmore','view','DownloadPrint','manager_review','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','getUserTypeDetail');
$config['Concerns']['delete'] = array('deletedata');
$config['Concerns']['hidden_archive'] = array();
$config['Concerns']['Move_KS_YPC'] = array('moveToKS');
$config['Concerns']['Conclude_KS_YPC'] = array('concludeYPC');
$config['Concerns']['signoff'] = array();
$config['Concerns']['comment'] = array();
$config['Concerns']['document_signoff'] = array('signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','resend_external_approval', 'reportReviewedBy', 'reviewedConcern','resend_external_approval');

//Documents
$config['Documents']['add'] = array('DownloadPdf','create','insert','save_docs','DownloadPrint','manager_review','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data');
$config['Documents']['edit'] = array('DownloadPdf','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data');
$config['Documents']['view'] = array('DownloadPdf','index','download','view','readmore','DownloadPrint','manager_review','getUserTypeDetail','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data');
$config['Documents']['delete'] = array('deleteDocs');
$config['Documents']['hidden_archive'] = array();
$config['Documents']['signoff'] = array();
$config['Documents']['comment'] = array();
$config['Documents']['document_signoff'] = array('signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data', 'resend_external_approval', 'reportReviewedBy', 'reviewedConcern','resend_external_approval');

//Medical
$config['Medical']['add'] = array('add_commnts','generateExcelFile','add_mc','insert_mc','save_mc','insert_medication','save_medication','save_administer_medication','insert_administer_medication','add_appointment','insert_appointment','save_appointment','add_mp','insert_mp','save_mp','healthAssessment','insert_health_assessment','save_health_assessment','log_health_assessment','insertAllergies','add_edit_allergies','DownloadPrint','DownloadViewMc','DownloadMedicaltion','DownloadAdministerMedication','DownloadHealthAssessment','DownloadAppointment','manager_review','edit_medication','edit_slug','update_slug','mac_edit_slug','mac_update_slug','appointment_edit_slug','appointment_update_slug','omi_edit_slug','omi_update_slug','mi_edit_slug','mi_update_slug','me_edit_slug','me_update_slug','check_qut','add_appointment_data','view_healthAssessment','add_health_assessment_commnts','add_recommendations','getMedicalDetail','medication_list','readmore_medical_professionals');
$config['Medical']['edit'] = array('add_commnts','generateExcelFile','create','insert','add_mi','insert_mi','save_mi','add_omi','insert_omi','save_omi','add_mac','insert_mac','save_mac','editMi','healthAssessment','insert_health_assessment','save_health_assessment','log_health_assessment','appointment_edit','update_appointment','insertAllergies','add_edit_allergies','DownloadPrint','DownloadViewMc','DownloadMedicaltion','DownloadAdministerMedication','DownloadHealthAssessment','DownloadAppointment','manager_review','edit_medication','edit_administer_medication','edit_slug','update_slug','mac_edit_slug','mac_update_slug','appointment_edit_slug','appointment_update_slug','omi_edit_slug','omi_update_slug','mi_edit_slug','mi_update_slug','me_edit_slug','me_update_slug','check_qut','add_appointment_data','view_healthAssessment','add_health_assessment_commnts','add_recommendations','getMedicalDetail','medication_list');
$config['Medical']['view'] = array('add_commnts','generateExcelFile','index','view_mc','medication','save_medication'
  ,'log_administer_medication','administer_medication','mp_ajax','healthAssessment','insert_health_assessment','save_health_assessment','log_health_assessment','appointment','view_appointment','readmore_appointment','appointment_view','insertAllergies','add_edit_allergies','DownloadPrint','DownloadViewMc','DownloadMedicaltion','DownloadAdministerMedication','DownloadHealthAssessment','DownloadAppointment','manager_review','edit_medication','add_appointment_data','view_healthAssessment','add_health_assessment_commnts','medication_list','readmore_medication','readmore_health_assessment','readmore_administer_medication','readmore_medical_communication','readmore_medical_professionals');
$config['Medical']['delete'] = array('ajax_delete_all','delete_administer_medication');
$config['Medical']['hidden_archive'] = array();
$config['Medical']['signoff'] = array();
$config['Medical']['comment'] = array();
$config['Medical']['document_signoff'] = array('signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data');

//Communication
$config['Communication']['add'] = array('DownloadPdf','add_communication','insert','save_comm','DownloadPrint','manager_review','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data');
$config['Communication']['edit'] = array('DownloadPdf','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data');
$config['Communication']['view'] = array('DownloadPdf','index','view','readmore','DownloadPrint','getUserTypeDetail','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','viewApprovalCom','external_approval_list');
$config['Communication']['delete'] = array();
$config['Communication']['hidden_archive'] = array();
$config['Communication']['signoff'] = array();
$config['Communication']['comment'] = array();
$config['Communication']['document_signoff'] = array('signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','resend_external_approval');

//Archive placement plan
$config['ArchivePlacementPlan']['add'] = array('createArchive');
$config['ArchivePlacementPlan']['edit'] = array();
$config['ArchivePlacementPlan']['view'] = array('index','view');
$config['ArchivePlacementPlan']['delete'] = array();
$config['ArchivePlacementPlan']['hidden_archive'] = array();
$config['ArchivePlacementPlan']['signoff'] = array();
$config['ArchivePlacementPlan']['comment'] = array();
$config['ArchivePlacementPlan']['document_signoff'] = array('');

//Archive Ibp
$config['ArchiveIbp']['add'] = array('createArchive');
$config['ArchiveIbp']['edit'] = array();
$config['ArchiveIbp']['view'] = array('index','view');
$config['ArchiveIbp']['delete'] = array();
$config['ArchiveIbp']['hidden_archive'] = array();
$config['ArchiveIbp']['signoff'] = array();
$config['ArchiveIbp']['comment'] = array();
$config['ArchiveIbp']['document_signoff'] = array('');

//Archive Risk Assesment
$config['ArchiveRiskAssesment']['add'] = array('createArchive');
$config['ArchiveRiskAssesment']['edit'] = array();
$config['ArchiveRiskAssesment']['view'] = array('index','view');
$config['ArchiveRiskAssesment']['delete'] = array();
$config['ArchiveRiskAssesment']['hidden_archive'] = array();
$config['ArchiveRiskAssesment']['signoff'] = array();
$config['ArchiveRiskAssesment']['comment'] = array();
$config['ArchiveRiskAssesment']['document_signoff'] = array('');

//Archive Daily Observation
$config['ArchiveDailyObservation']['add'] = array('createArchive');
$config['ArchiveDailyObservation']['edit'] = array();
$config['ArchiveDailyObservation']['view'] = array('index','view');
$config['ArchiveDailyObservation']['delete'] = array('deleteArchiveDo');
$config['ArchiveDailyObservation']['hidden_archive'] = array();
$config['ArchiveDailyObservation']['signoff'] = array();
$config['ArchiveDailyObservation']['comment'] = array();
$config['ArchiveDailyObservation']['document_signoff'] = array('');

//Archive Ibp
$config['ArchiveKs']['add'] = array('createArchive');
$config['ArchiveKs']['edit'] = array();
$config['ArchiveKs']['view'] = array('index','view','undoArchive');
$config['ArchiveKs']['delete'] = array();
$config['ArchiveKs']['hidden_archive'] = array('undoArchive');
$config['ArchiveKs']['signoff'] = array();
$config['ArchiveKs']['comment'] = array();
$config['ArchiveKs']['document_signoff'] = array('');


//ArchiveConcerns

$config['ArchiveConcerns']['add'] = array('createArchive');
$config['ArchiveConcerns']['edit'] = array();
$config['ArchiveConcerns']['view'] = array('index','view','undoArchive');
$config['ArchiveConcerns']['delete'] = array();
$config['ArchiveConcerns']['hidden_archive'] = array('undoArchive');
$config['ArchiveConcerns']['signoff'] = array();
$config['ArchiveConcerns']['comment'] = array();
$config['ArchiveConcerns']['document_signoff'] = array('');


$config['ActivityLog']['add'] = array();
$config['ActivityLog']['edit'] = array();
$config['ActivityLog']['view'] = array('index');
$config['ActivityLog']['delete'] = array();

$config['NFCCron']['add'] = array('getUserData','checkDuplicateRole','getEmployeesData','getEmployeesTablesData','getLoginData','archiveYp','CronJob','CronJobYPADD');
$config['NFCCron']['edit'] = array();
$config['NFCCron']['view'] = array('index','archiveYp');
$config['NFCCron']['delete'] = array();

$config['CommentsKs']['add'] = array();
$config['CommentsKs']['edit'] = array();
$config['CommentsKs']['view'] = array();
$config['CommentsKs']['delete'] = array();

$config['Filemanager']['add'] = array('upload', 'makeDir','download');
$config['Filemanager']['edit'] = array();
$config['Filemanager']['view'] = array('index', 'loadAjaxView', 'upload', 'delete', 'deleteImage', 'makeDir','download');
$config['Filemanager']['delete'] = array('delete', 'deleteImage');
$config['Filemanager']['hidden_archive'] = array('');
$config['Filemanager']['signoff'] = array();
$config['Filemanager']['comment'] = array();
$config['Filemanager']['document_signoff'] = array('');


//IndividualStrategies
$config['IndividualStrategies']['add'] = array('DownloadPDF_after_mail','DownloadPdf','manager_review','edit_slug','update_slug','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','resend_external_approval');
$config['IndividualStrategies']['edit'] = array('DownloadPDF_after_mail','edit','insert','save_ra','DownloadPdf','manager_review','update_slug','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data');
$config['IndividualStrategies']['view'] = array('DownloadPDF_after_mail','index','DownloadPdf','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','getUserTypeDetail','external_approval_list','viewApprovalIs');
$config['IndividualStrategies']['delete'] = array();
$config['IndividualStrategies']['hidden_archive'] = array('');
$config['IndividualStrategies']['signoff'] = array();
$config['IndividualStrategies']['comment'] = array();
$config['IndividualStrategies']['document_signoff'] = array('resend_external_approval','external_approval_list','viewApprovalIs');

//Archive Individual Strategies New
$config['ArchiveIndividualStrategies']['add'] = array('createArchive');
$config['ArchiveIndividualStrategies']['edit'] = array();
$config['ArchiveIndividualStrategies']['view'] = array('index','view');
$config['ArchiveIndividualStrategies']['delete'] = array();
$config['ArchiveIndividualStrategies']['hidden_archive'] = array('');
$config['ArchiveIndividualStrategies']['signoff'] = array();
$config['ArchiveIndividualStrategies']['comment'] = array();
$config['ArchiveIndividualStrategies']['document_signoff'] = array('');


//CseReport
$config['CseReport']['add'] = array('cseQuestion','savedata','CseRecordReport','getValsum','getTotalData','getHealthData','getBehaviourData','getGroomData','getLookChildData','getFamilySocialdData','getEsafetyData','getLineGraphData','getCommentData');
$config['CseReport']['edit'] = array('cseQuestion','savedata','CseRecordReport','edit','updatedata','getValsum','getTotalData','getHealthData','getBehaviourData','getGroomData','getLookChildData','getFamilySocialdData','getEsafetyData','getLineGraphData','getCommentData');
$config['CseReport']['view'] = array('index','view','CseRecordReport','getTotalData','getHealthData','getBehaviourData','getGroomData','getLookChildData','getFamilySocialdData','getEsafetyData','generatePDF','getLineGraphData','removeHighChartPoints','getCommentData');
$config['CseReport']['delete'] = array();
$config['CseReport']['hidden_archive'] = array('');
$config['CseReport']['signoff'] = array();
$config['CseReport']['comment'] = array();
$config['CseReport']['document_signoff'] = array('');



//SdqReport
$config['SdqReport']['add'] = array('index','sdqQuestion','savedata','sdqsample','SdqRecordReport','edit','updatedata','getValsum','getLineGraphData','getTotalDiffData','getEmoSymData','getConScaleData','getHypScaleData','getPeerScaleData','getProScaleData','SdqTrendReport','generatePDF','getTotalDifTrendData','getEmoSymTrendData','getConScaleTrend','getHypScaleTrend','getPeerScaleTrend','getProScaleTrend');
$config['SdqReport']['edit'] = array('index','sdqQuestion','savedata','sdqsample','SdqRecordReport','edit','updatedata','getValsum','getLineGraphData','getTotalDiffData','getEmoSymData','getConScaleData','getHypScaleData','getPeerScaleData','getProScaleData','SdqTrendReport','generatePDF','getTotalDifTrendData','getEmoSymTrendData','getConScaleTrend','getHypScaleTrend','getPeerScaleTrend','getProScaleTrend');
$config['SdqReport']['view'] = array('index','sdqQuestion','savedata','sdqsample','SdqRecordReport','edit','updatedata','getValsum','getLineGraphData','getTotalDiffData','getEmoSymData','getConScaleData','getHypScaleData','getPeerScaleData','getProScaleData','SdqTrendReport','generatePDF','getTotalDifTrendData','getEmoSymTrendData','getConScaleTrend','getHypScaleTrend','getPeerScaleTrend','getProScaleTrend');
$config['SdqReport']['delete'] = array('index','sdqQuestion','savedata','sdqsample','SdqRecordReport','edit','updatedata','getValsum','getLineGraphData','getTotalDiffData','getEmoSymData','getConScaleData','getHypScaleData','getPeerScaleData','getProScaleData','SdqTrendReport','generatePDF','getTotalDifTrendData','getEmoSymTrendData','getConScaleTrend','getHypScaleTrend','getPeerScaleTrend','getProScaleTrend');
$config['SdqReport']['hidden_archive'] = array('index','sdqQuestion','savedata','sdqsample','SdqRecordReport','edit','updatedata','getValsum','getLineGraphData','getTotalDiffData','getEmoSymData','getConScaleData','getHypScaleData','getPeerScaleData','getProScaleData','SdqTrendReport','generatePDF','getTotalDifTrendData','getEmoSymTrendData','getConScaleTrend','getHypScaleTrend','getPeerScaleTrend','getProScaleTrend');
$config['SdqReport']['comment'] = array('index','sdqQuestion','savedata','sdqsample','SdqRecordReport','edit','updatedata','getValsum','getLineGraphData','getTotalDiffData','getEmoSymData','getConScaleData','getHypScaleData','getPeerScaleData','getProScaleData','SdqTrendReport','generatePDF','getTotalDifTrendData','getEmoSymTrendData','getConScaleTrend','getHypScaleTrend','getPeerScaleTrend','getProScaleTrend');
$config['SdqReport']['signoff'] = array('index','sdqQuestion','savedata','sdqsample','SdqRecordReport','edit','updatedata','getValsum','getLineGraphData','getTotalDiffData','getEmoSymData','getConScaleData','getHypScaleData','getPeerScaleData','getProScaleData','SdqTrendReport','generatePDF','getTotalDifTrendData','getEmoSymTrendData','getConScaleTrend','getHypScaleTrend','getPeerScaleTrend','getProScaleTrend');
$config['SdqReport']['hidden_archive'] = array('');
$config['SdqReport']['comment'] = array();
$config['SdqReport']['document_signoff'] = array('');


//Weekly Report
$config['WeeklyReport']['add'] = array('create','DownloadPDF','manager_review','edit_slug','update_slug','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','insert_draft_wr');
$config['WeeklyReport']['edit'] = array('edit','insert','save_weekly_report','DownloadPrint','manager_review','update_slug','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data');
$config['WeeklyReport']['view'] = array('view','index','DownloadPrint','manager_review','sendMailToRelation','signoffData','insertdata','signoff_review_data','send_report', 'getStaffData');
$config['WeeklyReport']['delete'] = array('deletedata');
$config['WeeklyReport']['signoff'] = array();
$config['WeeklyReport']['hidden_archive'] = array('');
$config['WeeklyReport']['comment'] = array('');
$config['WeeklyReport']['document_signoff'] = array('reportReviewedBy');

//Archive Weekly report
$config['ArchiveWr']['add'] = array('createArchive');
$config['ArchiveWr']['edit'] = array();
$config['ArchiveWr']['view'] = array('index','view');
$config['ArchiveWr']['delete'] = array();
$config['ArchiveWr']['signoff'] = array();
$config['ArchiveWr']['hidden_archive'] = array('undoArchive');
$config['ArchiveWr']['comment'] = array();
$config['ArchiveWr']['document_signoff'] = array('');


//MDTReviewReport
$config['MDTReviewReport']['add'] = array('DownloadPDF_after_mail','DownloadPdf','add','insert','insert_draft','createReport','checkReport','DownloadPdf','manager_review','edit_slug','update_slug','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data');
$config['MDTReviewReport']['edit'] = array('DownloadPDF_after_mail','DownloadPdf','edit','edit_draft','insert','save_ra','DownloadPdf','manager_review','update_slug','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data');
$config['MDTReviewReport']['view'] = array('DownloadPDF_after_mail','DownloadPdf','index','view','DownloadPdf','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','getUserTypeDetail');
$config['MDTReviewReport']['delete'] = array('deleteMDT');
$config['MDTReviewReport']['hidden_archive'] = array('');
$config['MDTReviewReport']['signoff'] = array();
$config['MDTReviewReport']['comment'] = array();
$config['MDTReviewReport']['document_signoff'] = array('external_approve','external_view','resend_external_approval');

//Archive Individual Strategies New
$config['ArchiveMDTReport']['add'] = array('createArchive');
$config['ArchiveMDTReport']['edit'] = array();
$config['ArchiveMDTReport']['view'] = array('index','view');
$config['ArchiveMDTReport']['delete'] = array();
$config['ArchiveMDTReport']['hidden_archive'] = array('');
$config['ArchiveMDTReport']['signoff'] = array();
$config['ArchiveMDTReport']['comment'] = array();
$config['ArchiveMDTReport']['document_signoff'] = array('');

/*ghelani nikunj
17/9/2018
for  care to care archive functionlaity so new module created
*/

//CareArchivedMDTReviewReport New
$config['CareArchivedMDTReviewReport']['add'] = array('createArchive');
$config['CareArchivedMDTReviewReport']['edit'] = array();
$config['CareArchivedMDTReviewReport']['view'] = array('index','view');
$config['CareArchivedMDTReviewReport']['delete'] = array();
$config['CareArchivedMDTReviewReport']['hidden_archive'] = array('');
$config['CareArchivedMDTReviewReport']['signoff'] = array();
$config['CareArchivedMDTReviewReport']['comment'] = array();
$config['CareArchivedMDTReviewReport']['document_signoff'] = array('');

//Archive Individual Strategies New
$config['CheckStock']['add'] = array('');
$config['CheckStock']['edit'] = array();
$config['CheckStock']['view'] = array('','');
$config['CheckStock']['delete'] = array();
$config['CheckStock']['hidden_archive'] = array('');
$config['CheckStock']['signoff'] = array();
$config['CheckStock']['comment'] = array();
$config['CheckStock']['document_signoff'] = array('');


//LocationRegister
$config['LocationRegister']['add'] = array('DownloadPdf','create','insert','save_lr','DownloadPrint','manager_review','add_commnts','edit_draft','insert_draft_ks','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data');
$config['LocationRegister']['edit'] = array('DownloadPdf','manager_review','edit_draft','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data');
$config['LocationRegister']['view'] = array('DownloadPdf','index','readmore','view','DownloadPrint','manager_review','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','getUserTypeDetail','external_approval_list','viewApprovalLr');
$config['LocationRegister']['delete'] = array('deletedata');
$config['LocationRegister']['hidden_archive'] = array();
$config['LocationRegister']['signoff'] = array();
$config['LocationRegister']['comment'] = array();
$config['LocationRegister']['document_signoff'] = array('signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','resend_external_approval');

//YPFinance
$config['YPFinance']['add'] = array('');
$config['YPFinance']['edit'] = array();
$config['YPFinance']['view'] = array('index','view');
$config['YPFinance']['delete'] = array();
$config['YPFinance']['hidden_archive'] = array('');
$config['YPFinance']['signoff'] = array();
$config['YPFinance']['comment'] = array();
$config['YPFinance']['document_signoff'] = array('');


//PocketMoney
$config['PocketMoney']['add'] = array('add','insert');
$config['PocketMoney']['edit'] = array();
$config['PocketMoney']['view'] = array('index','readmore','view','DownloadPrint','generateExcelFile','downloadExcelFile');
$config['PocketMoney']['delete'] = array('deletedata');
$config['PocketMoney']['hidden_archive'] = array();
$config['PocketMoney']['signoff'] = array();
$config['PocketMoney']['comment'] = array();
$config['PocketMoney']['document_signoff'] = array();

//ClothingMoney
$config['ClothingMoney']['add'] = array('add','insert');
$config['ClothingMoney']['edit'] = array();
$config['ClothingMoney']['view'] = array('index','readmore','view','DownloadPrint','generateExcelFile','downloadExcelFile');
$config['ClothingMoney']['delete'] = array('deletedata');
$config['ClothingMoney']['hidden_archive'] = array();
$config['ClothingMoney']['signoff'] = array();
$config['ClothingMoney']['comment'] = array();
$config['ClothingMoney']['document_signoff'] = array();


//Planner
$config['Planner']['add'] = array('add','insert');
$config['Planner']['edit'] = array('editRecord');
$config['Planner']['view'] = array('index','carehome','readmore','view','DownloadPrint','getEvents','viewPlanner','viewAppointment');
$config['Planner']['delete'] = array('deletedata','deleteEvent');
$config['Planner']['hidden_archive'] = array();
$config['Planner']['signoff'] = array();
$config['Planner']['comment'] = array();
$config['Planner']['document_signoff'] = array();

//Appointments
$config['Appointments']['add'] = array('add','insert','add_appointment','insert_appointment','save_appointment');
$config['Appointments']['edit'] = array('appointment_edit','update_appointment','save_appointment');
$config['Appointments']['view'] = array('index','readmore','appointment','DownloadAppointment','appointment_view','readmore_appointment');
$config['Appointments']['delete'] = array('ajax_delete_all');
$config['Appointments']['hidden_archive'] = array();
$config['Appointments']['signoff'] = array();
$config['Appointments']['comment'] = array();
$config['Appointments']['document_signoff'] = array();
//RiskManagementPlan
$config['RiskManagementPlan']['add'] = array('getRmpFormData','DownloadPDF_after_mail','create','insert','save_rmp','DownloadPrint','manager_review','add_commnts','edit_draft','insert_draft_ks','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data');
$config['RiskManagementPlan']['edit'] = array('getRmpFormData','DownloadPDF_after_mail','manager_review','edit_draft','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','copy');
$config['RiskManagementPlan']['view'] = array('getRmpFormData','DownloadPDF_after_mail','index','readmore','view','DownloadPrint','DownloadPDF','manager_review','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','getUserTypeDetail','external_approval_list','viewApprovalRMP');
$config['RiskManagementPlan']['delete'] = array('deletedata');
$config['RiskManagementPlan']['hidden_archive'] = array();
$config['RiskManagementPlan']['signoff'] = array();
$config['RiskManagementPlan']['comment'] = array();
$config['RiskManagementPlan']['document_signoff'] = array('signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','resend_external_approval');

//ArchiveRMP
$config['ArchiveRMP']['add'] = array('createArchive');
$config['ArchiveRMP']['edit'] = array();
$config['ArchiveRMP']['view'] = array('index','view','undoArchive');
$config['ArchiveRMP']['delete'] = array();
$config['ArchiveRMP']['hidden_archive'] = array('undoArchive');
$config['ArchiveRMP']['signoff'] = array();
$config['ArchiveRMP']['comment'] = array();
$config['ArchiveRMP']['document_signoff'] = array('');

//SetLogoutTime
$config['SetLogoutTime']['add'] = array('insert_theme_data');
$config['SetLogoutTime']['edit'] = array('insert_theme_data');
$config['SetLogoutTime']['view'] = array('index');
$config['SetLogoutTime']['delete'] = array();
$config['SetLogoutTime']['hidden_archive'] = array();
$config['SetLogoutTime']['signoff'] = array();
$config['SetLogoutTime']['comment'] = array();
$config['SetLogoutTime']['document_signoff'] = array();


//mail
$config['Mail']['add'] = array('ComposeMail', 'saveConcept', 'forwardEmail', 'uploadFromEditor','ComposeMailYp','ComposeMailYPC','ComposeMailRMP','DownloadRMPPdf','ComposeMailDO','DownloadDOPdf','validateMailConfig','ComposeMailCpt','DownloadCptPdf','ComposeMailpp','DownloadPPPdf');
$config['Mail']['delete'] = array('deletedata');
$config['Mail']['edit'] = array('getUnreadMailCount', 'mailconfig','ComposeMailYp','ComposeMailYPC','ComposeMailRMP','DownloadRMPPdf','ComposeMailDO','DownloadDOPdf','validateMailConfig','ComposeMailCpt','DownloadCptPdf','ComposeMailCpt','DownloadCptPdf','ComposeMailpp','DownloadPPPdf');
$config['Mail']['view'] = array('index', 'mailDataExport', 'getHeader', 'getEmails', 'moveMessage', 'markasFlagged','ComposeMailYp','ComposeMailYPC','DownloadYPCPdf','ComposeMailRMP','DownloadRMPPdf','ComposeMailDO','DownloadDOPdf','validateMailConfig','ComposeMailCpt','DownloadCptPdf','ComposeMailpp','DownloadPPPdf');


//ParentsCarerDetails
$config['ParentsCarerDetails']['add'] = array('addParentCarerInformation');
$config['ParentsCarerDetails']['edit'] = array('editParentCarerInformation','updateParentCarerDetails','viewParentCarerInformation');
$config['ParentsCarerDetails']['view'] = array('index');
$config['ParentsCarerDetails']['delete'] = array('removeParentCarerInformation');
$config['ParentsCarerDetails']['hidden_archive'] = array();
$config['ParentsCarerDetails']['signoff'] = array();
$config['ParentsCarerDetails']['comment'] = array();
$config['ParentsCarerDetails']['document_signoff'] = array();

//Help module
  $config['Help']['add'] = array('saveHelpData','add','getNotifiedMessage');
  $config['Help']['delete'] = array('deletedata','saveHelpData','add');
  $config['Help']['edit'] = array('saveHelpData','add');
  $config['Help']['view'] = array('index','saveHelpData','add');
  

/*$config['AAI']['add'] = array('create','updateMainForm','checkPlaceType','updateTypeForm','updateL1Form','updateL2nL3Form','updateL6Form','updateL4Form','updateL7Form');
$config['AAI']['edit'] = array('edit','updateMainForm','updateTypeForm','updateL1Form','updateL2nL3Form','updateL4Form','updateL6Form','updateL7Form');
$config['AAI']['view'] = array('index','checkPlaceType','view');
||||||| .r2636
$config['AAI']['add'] = array('create','updateMainForm','checkPlaceType','updateTypeForm','updateL1Form','updateL2nL3Form','updateL6Form','updateL4Form','updateL7Form');
$config['AAI']['edit'] = array('edit','updateMainForm','updateTypeForm','updateL1Form','updateL2nL3Form','updateL4Form','updateL6Form','updateL7Form');
$config['AAI']['view'] = array('index','checkPlaceType','view');
=======
$config['AAI']['add'] = array('create','updateMainForm','checkPlaceType','updateTypeForm','updateL1Form','updateL2nL3Form','updateL6Form','updateL4Form','updateL7Form','who_was_involved_in_incident');
$config['AAI']['edit'] = array('edit','updateMainForm','updateTypeForm','updateL1Form','updateL2nL3Form','updateL4Form','updateL6Form','updateL7Form','who_was_involved_in_incident');
$config['AAI']['view'] = array('index','checkPlaceType','view','who_was_involved_in_incident');
>>>>>>> .r2665
$config['AAI']['delete'] = array();
$config['AAI']['hidden_archive'] = array();
$config['AAI']['signoff'] = array();
$config['AAI']['comment'] = array();
$config['AAI']['document_signoff'] = array();
$config['AAI']['external_edit'] = array();*/
$config['AAI']['add'] = array('create','updateMainForm','checkPlaceType','updateTypeForm','updateL1Form','updateL2nL3Form','updateL6Form','updateL4Form','send_notification_social_worker','send_notification_missing_team','updateL5Form','updateL7Form','updateReviewForm','updateManagerReviewForm','manager_review','updateL8Form','updateL9Form','who_was_involved_in_incident');
$config['AAI']['edit'] = array('updateL1Form','updateL2nL3Form','updateL6Form','edit','updateMainForm','updateTypeForm','updateL4Form','send_notification_social_worker','send_notification_missing_team','updateL5Form','updateL7Form','updateReviewForm','updateManagerReviewForm','manager_review','updateL8Form','updateL9Form','who_was_involved_in_incident');
$config['AAI']['view'] = array('view','index','checkPlaceType','updateL4Form','send_notification_social_worker','send_notification_missing_team','updateL5Form','updateReviewForm','updateManagerReviewForm','manager_review','getUserTypeDetail','external_approval_list','approvalView','updateL8Form','updateL9Form','readmore','who_was_involved_in_incident');
$config['AAI']['delete'] = array();
$config['AAI']['hidden_archive'] = array();
$config['AAI']['signoff'] = array('manager_review');
$config['AAI']['comment'] = array();
$config['AAI']['document_signoff'] = array('signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','resend_external_approval');
$config['AAI']['external_edit'] = array();



/*Ritesh Rana
21/01/2019
for archive AAI functionlaity so new module created
*/
//ArchiveAAI
$config['ArchiveAAI']['add'] = array('create','updateMainForm','checkPlaceType','updateTypeForm','updateL1Form','updateL2nL3Form','updateL6Form','updateL4Form','updateL7Form','updateL8Form','updateL9Form');
$config['ArchiveAAI']['edit'] = array('edit','updateMainForm','updateTypeForm','updateL1Form','updateL2nL3Form','updateL4Form','updateL6Form','updateL7Form','updateL8Form','updateL9Form');
$config['ArchiveAAI']['view'] = array('index','checkPlaceType','view','updateL8Form','updateL9Form','readmore');
$config['ArchiveAAI']['delete'] = array();
$config['ArchiveAAI']['hidden_archive'] = array();
$config['ArchiveAAI']['signoff'] = array();
$config['ArchiveAAI']['comment'] = array();
$config['ArchiveAAI']['document_signoff'] = array();
$config['ArchiveAAI']['external_edit'] = array();

//Sanctions
$config['Sanctions']['add'] = array('index','view','edit','insert','add');
$config['Sanctions']['edit'] = array('edit','insert');
$config['Sanctions']['view'] = array('index','view','readmore','edit','sanctionsView');
$config['Sanctions']['delete'] = array();
$config['Sanctions']['hidden_archive'] = array();
$config['Sanctions']['signoff'] = array();
$config['Sanctions']['comment'] = array();
$config['Sanctions']['document_signoff'] = array();
$config['Sanctions']['external_edit'] = array();

//Ghelani nikunj 13-2-19 for AAI Report
$config['AAIReport']['add'] = array('index','view','edit','insert','DashboardReport','Dashboard','getNumberOfIncident','getRelatedToL2','getRelatedToL2AndL3','getRelatedTol7lado','getRelatedToL3','getRelatedToL1','getRelatedToREG40','getRelatedToPoliceInvolvement','comparison_of_number_of_incident_between_care_homes','number_of_type_of_incident_by_yp_over','Management','numner_and_level_of_incidents_by_staff_member_over_time','number_of_sactions','number_of_complaints_by_yp','number_of_complaints_by_carehome','numer_of_safeguarding_occurences_by_yp_and_carehome');
$config['AAIReport']['edit'] = array('edit','insert','DashboardReport','Dashboard','getNumberOfIncident','getRelatedToL2','getRelatedToL2AndL3','getRelatedTol7lado','getRelatedToL3','getRelatedToL1','getRelatedToREG40','getRelatedToPoliceInvolvement','comparison_of_number_of_incident_between_care_homes','number_of_type_of_incident_by_yp_over','Management','numner_and_level_of_incidents_by_staff_member_over_time','number_of_sactions','number_of_complaints_by_yp','number_of_complaints_by_carehome','numer_of_safeguarding_occurences_by_yp_and_carehome');
$config['AAIReport']['view'] = array('index','view','readmore','edit','sanctionsView','DashboardReport','Dashboard','getNumberOfIncident','getRelatedToL2','getRelatedToL2AndL3','getRelatedTol7lado','getRelatedToL3','getRelatedToL1','getRelatedToREG40','getRelatedToPoliceInvolvement','comparison_of_number_of_incident_between_care_homes','number_of_type_of_incident_by_yp_over','Management','numner_and_level_of_incidents_by_staff_member_over_time','number_of_sactions','number_of_complaints_by_yp','number_of_complaints_by_carehome','numer_of_safeguarding_occurences_by_yp_and_carehome');
$config['AAIReport']['delete'] = array();
$config['AAIReport']['hidden_archive'] = array();
$config['AAIReport']['signoff'] = array();
$config['AAIReport']['comment'] = array();
$config['AAIReport']['document_signoff'] = array();
$config['AAIReport']['external_edit'] = array();


//CarePlanTarget
$config['CarePlanTarget']['add'] = array('DownloadPdf','create','insert','save_cpt','DownloadPrint','manager_review','add_commnts','edit_draft','insert_draft_ks','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data');
$config['CarePlanTarget']['edit'] = array('DownloadPdf','manager_review','edit_draft','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data');
$config['CarePlanTarget']['view'] = array('DownloadPdf','index','readmore','view','DownloadPrint','manager_review','signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','getUserTypeDetail','external_approval_list','viewApprovalCpt');
$config['CarePlanTarget']['delete'] = array('deletedata');
$config['CarePlanTarget']['hidden_archive'] = array();
$config['CarePlanTarget']['Move_KS_YPC'] = array('moveToYpc');
$config['CarePlanTarget']['Conclude_KS_YPC'] = array('concludeCPT');
$config['CarePlanTarget']['signoff'] = array();
$config['CarePlanTarget']['comment'] = array();
$config['CarePlanTarget']['document_signoff'] = array('signoff','sendMailToRelation','signoffData','insertdata','signoff_review_data','resend_external_approval');


//Archive Cpt
$config['ArchiveCpt']['add'] = array('createArchive');
$config['ArchiveCpt']['edit'] = array();
$config['ArchiveCpt']['view'] = array('index','view','undoArchive');
$config['ArchiveCpt']['delete'] = array();
$config['ArchiveCpt']['hidden_archive'] = array('undoArchive');
$config['ArchiveCpt']['signoff'] = array();
$config['ArchiveCpt']['comment'] = array();
$config['ArchiveCpt']['document_signoff'] = array('');