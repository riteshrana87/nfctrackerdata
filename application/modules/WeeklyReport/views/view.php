<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypid ?>';
</script>
<div id="page-wrapper">
    <div class="main-page">
        <?php
        if (($this->session->flashdata('successmsg'))) { ?>
            <div class='alert alert-success text-center'><?php echo $this->session->flashdata('successmsg');?></div>            
        <?php }
        if (($this->session->flashdata('errormsg'))) { ?>
            <div class='alert alert-danger text-center'><?php echo $this->session->flashdata('errormsg');?></div>
        <?php }
        ?>
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            WEEKLY REPORT TO SOCIAL WORKER <small>New Forest Care</small>
            <div class="pull-right for-tab">
                <div class="btn-group">
                    <?php 
                    /* condition added by Ritesh Rana on 02/10/2018 to archive functionality */
                    if($past_care_id == 0){ ?>
                    <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                    <a href="<?= base_url('YoungPerson/view/' . $ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>
                    <a href="<?= base_url('WeeklyReport/index/' . $ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> WEEKLY REPORTS
                    </a>
                    <?php  if(checkPermission('ArchiveWr','add')){ ?>
                        <?php if (!empty($edit_data[0]['weekly_report_id'])) { ?>
                          <a href="<?=base_url('ArchiveWr/createArchive/'. $edit_data[0]['weekly_report_id']."/".$ypid); ?>" class="btn btn-default">
                          <i class="fa fa-edit"></i> CREATE ARCHIVE
                          </a>
                    <?php } } ?>
                    <?php if (!empty($edit_data[0]['weekly_report_id'])) { ?>
                        <a href="<?= base_url('WeeklyReport/DownloadPDF/' . $edit_data[0]['weekly_report_id'] . '/' . $ypid. '/download'); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> PDF </a>

                    <?php } ?>
                    <?php }else{ ?>

                        <a href="<?=base_url('ArchiveCarehome/index/'.$care_home_id)?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> ARCHIVE CAREHOME YP LIST</a>

                        <a href="<?= base_url('ArchiveCarehome/view/' . $ypid. '/'. $care_home_id .'/'. $past_care_id); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                        </a>    

                        <a href="<?= base_url('WeeklyReport/index/' . $ypid.'/'. $care_home_id .'/'. $past_care_id); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> WEEKLY REPORTS
                        </a>
                    
                    <?php if (!empty($edit_data[0]['weekly_report_id'])) { ?>
                        <a href="<?= base_url('WeeklyReport/DownloadPDF/' . $edit_data[0]['weekly_report_id'] . '/' . $ypid. '/download'); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> PDF </a>

                    <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </h1>
        <h1 class="page-title">
            <small>Name: </small><?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>

        <h1 class="page-title">
            <small>Period of Report: </small>
                <?php echo (!empty($edit_data[0]['start_date'])) ? configDateTime($edit_data[0]['start_date']) : ""; ?> - 
                <?php echo (!empty($edit_data[0]['end_date'])) ? configDateTime($edit_data[0]['end_date']) : ""; ?>
        </h1>
        </div>
        <div class="row">
            <?php
            if (!empty($form_data)) {
                foreach ($form_data as $row) {

                    if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') {
                        ?>
                        <div class="<?= ($row['type'] == 'header') ? 'col-sm-12' : 'col-sm-12' ?>">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2><?= !empty($row['label']) ? $row['label'] : '' ?> </h2>
                                    <div class ="slimScroll-120">
                                        <?php
                                        if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') {
                                            if (!empty($prev_edit_data)) {

                                                $diff = new HtmlDiff($prev_edit_data[0][$row['name']], $edit_data[0][$row['name']]);
                                                $diff->build();
                                                echo $diff->getDifference()
                                                ?>
                                            <?php } else {
                                                if($row['type'] == 'date'){
                                                    if(!empty($edit_data[0][$row['name']]) && $edit_data[0][$row['name']] !='0000-00-00'){
                                                        echo configDateTime($edit_data[0][$row['name']]);
                                                    }                                                        
                                                }else if(isset($row['subtype'])&& $row['subtype'] == 'time'){
                                                    if((!empty($edit_data[0][$row['name']]))){
                                                        echo timeformat($edit_data[0][$row['name']]);
                                                    }
                                                }else{
                                                    echo !empty($edit_data[0][$row['name']]) ? html_entity_decode($edit_data[0][$row['name']]) : (isset($row['value']) ? $row['value'] : '');
                                                }
                                            } ?>
                                            <?php
                                        } else if ($row['type'] == 'checkbox-group') {
                                            if (!empty($edit_data[0][$row['name']])) {
                                                $chk = explode(',', $edit_data[0][$row['name']]);
                                                foreach ($chk as $chk) {
                                                    echo $chk . "\n";
                                                }
                                            } else {

                                                if (count($row['values']) > 0) {

                                                    foreach ($row['values'] as $chked) {
                                                        echo isset($chked['selected']) ? '<li>' . $chked['value'] . "\n" : '';
                                                    }
                                                }
                                            }
                                            ?>

                                            <?php
                                        } else if ($row['type'] == 'radio-group' || $row['type'] == 'select') {
                                            if (!empty($edit_data[0][$row['name']])) {
                                                echo!empty($edit_data[0][$row['name']]) ? nl2br(htmlentities($edit_data[0][$row['name']])) : '';
                                            } else {
                                                if (count($row['values']) > 0) {

                                                    foreach ($row['values'] as $chked) {
                                                        echo isset($chked['selected']) ? $chked['value'] : '';
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <?php
                    } else if ($row['type'] == 'radio-group') {
                        ?>
                        <div class="col-sm-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
                                        <!--code added for green edit-->
                                        <?php
                                        if (!empty($prev_edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']])) {
                                            if ($prev_edit_data[0][$row['name']] != $edit_data[0][$row['name']]) {
                                                ?>
                                                <span class="text-success">&nbsp;<i class="fa fa-edit" aria-hidden="true"></i></span>
                                                <?php
                                            }
                                        }
                                        ?>
                                    
                                        <?php
                                        if (!empty($edit_data[0][$row['name']])) {
                                            echo ($edit_data[0][$row['name']] == 'Yes') ? '<span class="label label-success">Yes</span>' : (($edit_data[0][$row['name']] == 'No') ? '<span class="label label-danger">No</span>' : '<span class="label label-success">' . $edit_data[0][$row['name']] . '</span>');
                                        } else {
                                            if (count($row['values']) > 0) {

                                                foreach ($row['values'] as $chked) {

                                                    echo isset($chked['selected']) ? ($chked['value'] == 'no') ? '<span class="label label-danger">No</span>' : (($chked['value'] == 'yes') ? '<span class="label label-success">Yes</span>' : '') : '';
                                                }
                                            }
                                        }
                                        ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    } else if ($row['type'] == 'header') {
                        ?>
                        <div class="col-sm-12">
                            <div class="">
                                <?php $head = !empty($row['subtype']) ? $row['subtype'] : 'h1' ?>
                                <?php echo '<' . $head . ' class="page-title">'; ?>
                                <?= !empty($row['label']) ? $row['label'] : '' ?>

                                <?php echo '</' . $head . '>'; ?>
                            </div>
                        </div>
                        <?php if(($row['label'] == 'Health' || $row['label'] == 'health') && !empty($appointments)){ ?>
                        <div class="col-sm-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2>Health Appointments Attended</h2>
                            <div class="form-group">
                            <div class="col-xs-12 mdt_table_hd">
                              <div class="row">
                                <div class="col-sm-4 add_items_field ">
                                  <strong>Health Appointments Attended</strong>
                                </div>
                                <div class="col-sm-4 add_items_field ">
                                  <strong>Date</strong>

                                </div>
                                <div class="col-sm-4 add_items_field ">
                                  <strong>Outcome/ Actions</strong>
                                </div>
                                </div>
                              </div>
                        <div class="clearfix"></div>
                             <?php foreach ($appointments as $row) {
                                    ?>

                                     <div class="delet_bottom col-xs-12 box_sty newrow">
                                       <div class="row">
                                         <div class="col-sm-4 add_items_field mt30 ">
                                           <?= !empty($row['mp_name']) ? $row['mp_name'] : '' ?>
                                         </div>
                                         <div class="col-sm-4 add_items_field mt30 ">
                                           <?=(!empty($row['appointment_date']) && $row['appointment_date'] !='0000-00-00')?configDateTime($row['appointment_date']):''?>

                                         </div>
                                         <div class="col-sm-4 add_items_field mt30 ">
                                           <?= (!empty($row['comments'])) ? ((strlen ($row['comments']) > 50) ? $substr = substr (trim(strip_tags($row['comments'])), 0, 50) . '...<a data-href="'.base_url('Medical'.'/readmore_appointment/'.$row['appointment_id']).'/comments" data-refresh="true" data-toggle="ajaxModal" class="btn">read more</a>' : nl2br(html_entity_decode($row['comments']))):'' ?>
                                             <?php
                                             if(!empty($appointment_view_comments)){ 
                                                foreach ($appointment_view_comments as $comments_data) {
                                                    if($row['appointment_id'] == $comments_data['md_appoint_id']){ ?>                                                        
                                                                    <p class="small"> <?php echo $comments_data['md_comment']?></p>
                                                                    <p class="date"><small><?php echo $comments_data['create_name'] ?>:   <?php echo configDateTime($comments_data['created_date']); ?></small></p>
                                                    <?php } ?>
                                                <?php } } ?>
                                         </div>
                                       </div>
                                       </div>
                                <?php }
                            ?>
                        <div class="clearfix"></div>
                      </div>
                  </div>
              </div>
          </div>
                        <?php } ?>
                        <?php
                    } else if ($row['type'] == 'file') {
                        ?>
                        <div class="col-sm-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
                                    <div class="">   
                                        <?php
                                        if (!empty($edit_data[0][$row['name']])) {
                                            $img = base_url($edit_data[0][$row['name']]);
                                                ?>
                                                <div class="col-sm-1 margin-bottom">
                                                    <?php
                                                    if (@is_array(getimagesize($img))) {
                                                        ?>
                                                        <img width="100" height="100" src="<?= $img ?>">
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <img width="100" height="100" src="<?= base_url('uploads/images/icons 64/file-ico.png') ?>">
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <?php
                                            
                                        }
                                        ?>                               
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } //foreach
            }
            ?>
            <?php if (!empty($edit_data)) { 
                if (checkPermission('WeeklyReport', 'signoff')) {?>
                <div class="col-sm-12">
                    <div class="panel panel-default tile tile-profile">
                        <div class="panel-body">
                            <h2>sign off</h2>
                            <?php
                            if($past_care_id == 0){
                            if (empty($check_wr_signoff_data)) {
                                    ?>

                                    <input type="checkbox" name="wr_signoff" onclick="manager_request_wr(<?php echo $ypid . ',' . $edit_data[0]['weekly_report_id']; ?>);" class="wr_signoff" value="1" >

                                <?php
                                    $login_user_id = $this->session->userdata['LOGGED_IN']['ID'];
                                    echo getUserName($login_user_id);
                                }}                               
                            ?>

                            <?php
                            if (!empty($wr_signoff_data)) {
                                foreach ($wr_signoff_data as $sign_name) {
                                    ?>

                                    <ul class="media-list media-xs">
                                        <li class="media">
                                            <div class="media-body">
                                                <p class="date"><small><?php echo $sign_name['name'] ?>:  <?php echo configDateTimeFormat($sign_name['created_date']); ?></small></p>
                                            </div>
                                        </li>
                                    </ul>
                                <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
		<?php } ?>

	<?php  if (!empty($edit_data[0]['weekly_report_id'])) { 
		if (checkPermission('WeeklyReport', 'document_signoff')) { ?>
			<div class="col-sm-12">
				<div class="panel panel-default tile tile-profile">
					<div class="panel-body">
						<?php 
                        if($past_care_id == 0){ ?>
							<a style="text-decoration:none;" href="javascript:;" data-href="<?php echo base_url() . 'WeeklyReport/send_report/' . $ypid . '/' . $edit_data[0]['weekly_report_id']; ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" ><span class="label label-warning">Send Report</span></a>

                                                <?php if(!empty($check_external_signoff_data)){ ?>
                                                    <a style="text-decoration:none;" href="<?php echo base_url() . 'WeeklyReport/reportReviewedBy/' .$edit_data[0]['weekly_report_id'] . '/' . $ypid; ?>"  aria-hidden="true" data-refresh="true" ><span class="label label-warning">Reviewed By</span></a>
                                                <?php } ?>
                                                <?php }else{ ?>
                                                    <a style="text-decoration:none;" href="<?php echo base_url() . 'WeeklyReport/reportReviewedBy/' .$edit_data[0]['weekly_report_id'] . '/' . $ypid.'/'.$care_home_id.'/'.$past_care_id; ?>"  aria-hidden="true" data-refresh="true" ><span class="label label-warning">Reviewed By</span></a>
                                                <?php } ?>
					</div>
				</div>
			</div>
		<?php } }  ?>
	
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="pull-right btn-section">
                    <div class="btn-group">
                       <?php 
                    /* condition added by Ritesh Rana on 02/10/2018 to archive functionality */
                    if($past_care_id == 0){ ?>
                    <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                    <a href="<?= base_url('YoungPerson/view/' . $ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>

                    <a href="<?= base_url('WeeklyReport/index/' . $ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> WEEKLY REPORTS
                    </a>
                    <?php  if(checkPermission('ArchiveWr','add')){ ?>
                        <?php if (!empty($edit_data[0]['weekly_report_id'])) { ?>
                          <a href="<?=base_url('ArchiveWr/createArchive/'. $edit_data[0]['weekly_report_id']."/".$ypid); ?>" class="btn btn-default">
                          <i class="fa fa-edit"></i> CREATE ARCHIVE
                          </a>
                    <?php } } ?>
                    <?php if (!empty($edit_data[0]['weekly_report_id'])) { ?>
                        <a href="<?= base_url('WeeklyReport/DownloadPDF/' . $edit_data[0]['weekly_report_id'] . '/' . $ypid. '/download'); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> PDF </a>

                    <?php } ?>
                    <?php }else{ ?>

                        <a href="<?=base_url('ArchiveCarehome/index/'.$care_home_id)?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> ARCHIVE CAREHOME YP LIST</a>

                        <a href="<?= base_url('ArchiveCarehome/view/' . $ypid. '/'. $care_home_id .'/'. $past_care_id); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                        </a>    

                        <a href="<?= base_url('WeeklyReport/index/' . $ypid.'/'. $care_home_id .'/'. $past_care_id); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> WEEKLY REPORTS
                        </a>
                    
                    <?php if (!empty($edit_data[0]['weekly_report_id'])) { ?>
                        <a href="<?= base_url('WeeklyReport/DownloadPDF/' . $edit_data[0]['weekly_report_id'] . '/' . $ypid. '/download'); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> PDF </a>

                    <?php } ?>
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var generateFormToken = <?php echo generateFormToken();?>
</script>