<div id="page-wrapper" class="mail-module">
    <div class="main-page">
        <?php
        if (($this->session->flashdata('msg'))) {
            echo $this->session->flashdata('msg'); 
        }
        ?>
		<?php /*sticky header added by dhara Bhalala */ ?>
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title email_title">
            <?=!empty($care_home_name_data)?$care_home_name_data:''?>
            <div class="pull-right for-tab">
                <div class="btn-group">
                    <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                    <a href="<?= base_url('YoungPerson/view/' . $ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>
                </div>
            </div>            
        </h1>
        <h1 class="page-title email_title">
                    <small>Name: </small> <span class="small-font"><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?></span><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small> Email:</small><span class="small-font"> <?=!empty($yp_mail_id)?$yp_mail_id:''?> </span>
        </h1>
        </div>
        <div class="row">
            <div id="leftbar">
            <?php echo $this->load->view('leftbar'); ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
            <div id="main_div">
                <?php echo $this->load->view('mailMainThread'); ?>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
            
        </div>
        
    </div>
</div>

