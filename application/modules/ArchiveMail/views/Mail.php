<div id="page-wrapper" class="mail-module">
    <div class="main-page">
        <?php
        if (($this->session->flashdata('msg'))) {
            echo $this->session->flashdata('msg'); 
        }
        ?>
		<?php /*sticky header added by dhara Bhalala */ ?>
        <div class="sticky-heading padding-for-stic" id="sticky-heading">
        <h1 class="page-title">
            <?= lang('mail_mailbox') ?> <small>New Forest Care</small>
            <div class="pull-right for-tab">
                <div class="btn-group">
                   

<a href="<?=base_url('ArchiveCarehome/index/'.$care_home_id)?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> ARCHIVE CAREHOME YP LIST</a> 
 <a href="<?=base_url('ArchiveCarehome/view/'.$ypid.'/'.$care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> YP INFO
                        </a>
                </div>
            </div>            
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

