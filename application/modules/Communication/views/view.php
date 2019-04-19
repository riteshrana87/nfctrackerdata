<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
</script>
<div id="page-wrapper">
    <div class="main-page">
         <?php
        if (($this->session->flashdata('msg'))) {
            echo $this->session->flashdata('msg');
        }
        ?>
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            Communication <small>New Forest Care</small>
            <div class="pull-right for-tab">
                <div class="btn-group">
                    <?php 
                    /* condition added by Ritesh Ranan on 28/09/2018 to archive functionality */
                    if($past_care_id == 0){?>
                    <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                     <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>
                    <a href="<?=base_url('Communication/index/'.$ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply" aria-hidden="true"></i> BACK
                    </a>
                    <?php }else{ ?>
                    <a href="<?=base_url('ArchiveCarehome/index/'.$care_home_id)?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> ARCHIVE CAREHOME YP LIST</a>
                    <a href="<?=base_url('ArchiveCarehome/view/'.$ypid.'/'.$care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>

                    <a href="<?=base_url('Communication/index/'.$ypid.'/'.$care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply" aria-hidden="true"></i> BACK
                    </a>
                    <?php } ?>
                    <a href="<?= base_url('Communication/DownloadPrint/' . $co_id .'/'. $ypid .'/print'); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print </a>

                </div>
            </div>
        </h1>
    </div>
        <div class="clearfix"></div>
        <div class="row m-t-10">
            <?php
        if(!empty($form_data))
        {
            foreach ($form_data as $row) {

             if($row['type'] == 'textarea' || $row['type']== 'radio-group' || $row['type']== 'date'|| $row['type']== 'select'|| $row['type']== 'number'|| $row['type']== 'text'|| $row['type']== 'checkbox-group' ) {
                ?>
                <div class="col-lg-12">
                    <div class="panel panel-default tile tile-profile">
                        <div class="panel-body">
                        <h2><?=!empty($row['label'])?$row['label']:''?>
                        </h2>
                        <ul class="media-list media-xs">
                            <li class="media">
                                <div class="media-body">
                                    <p class ="small">
                                        <?php if($row['type'] == 'textarea' || $row['type']== 'date'|| $row['type']== 'number'|| $row['type']== 'text' ) {
                                            if($row['type'] == 'date'){
                                                if(!empty($medical_data[0][$row['name']]) && $medical_data[0][$row['name']] !='0000-00-00'){
                                                    echo configDateTime($medical_data[0][$row['name']]);
                                                }                                                        
                                            }else if(isset($row['subtype'])&& $row['subtype'] == 'time'){
                                                if((!empty($medical_data[0][$row['name']]))){
                                                    echo timeformat($medical_data[0][$row['name']]);
                                                }
                                            }else{
                                                echo !empty($medical_data[0][$row['name']])?nl2br(html_entity_decode($medical_data[0][$row['name']])):(isset($row['value'])?$row['value']:'');  
                                            }
                                            }
                                            else if($row['type'] == 'checkbox-group') {
                                            if(!empty($medical_data[0][$row['name']])) {
                                                $chk = explode(',',$medical_data[0][$row['name']]);
                                                        foreach ($chk as $chk) {
                                                            echo $chk."\n";
                                                        }
                                                     
                                                
                                            }else{

                                                    if(count($row['values']) > 0) {
                                                       
                                                     foreach ($row['values'] as $chked) {
                                                        echo isset($chked['selected'])?'<li>'.$chked['value']."\n":'';
                                                         }
                                                       
                                                    }
                                                }?>

                                           <?php }  else if($row['type'] == 'radio-group' || $row['type'] == 'select') {
                                             if(!empty($medical_data[0][$row['name']])) {
                                                echo !empty($medical_data[0][$row['name']])?nl2br(htmlentities($medical_data[0][$row['name']])):'';
                                             }
                                             else
                                             {
                                                 if(count($row['values']) > 0) {
                                                        
                                                     foreach ($row['values'] as $chked) {
                                                        echo isset($chked['selected'])?$chked['value']:'';
                                                         }
                                                        
                                                    }
                                             }
                                            } ?>
                                        </p>
                                    <p class="date"><small><?=!empty($medical_data[0]['create_name'])?$medical_data[0]['create_name'].' : ':''?> <?=!empty($medical_data[0]['daily_observation_date'])?$medical_data[0]['daily_observation_date']:''?> </small></p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
                }
                else if ($row['type'] == 'header') {
                   ?>
                   <div class="col-lg-12">
                        <h1 class="page-title"><?=!empty($row['label'])?$row['label']:''?></h1>
                       
                    </div>
             <?php
                }
                else if ($row['type'] == 'file') { ?>
                    <div class="col-lg-12">
                    <div class="panel panel-default tile tile-profile">
                        <div class="panel-body">
                        <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                        <ul class="media-list media-xs">
                            <li class="media">
                                <div class="media-body">
                                    <p class ="small">
                                        <?php 
                                        /* common file display block replaced by Dhara Bhalala on 25/09/2018 */
                                        $fileViewArray = array(
                                            'fileArray' => (isset($medical_data[0][$row['name']]) && !empty($medical_data[0][$row['name']]))? $medical_data[0][$row['name']] : $row['value'],
                                            'filePathMain' => $this->config->item('communication_img_base_url') . $ypid,
                                            'filePathThumb' => $this->config->item('communication_img_base_url_small') . $ypid
                                        );
                                        echo getFileView($fileViewArray);
                                    ?>  
                                        </p>
                                    
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                    </div>
               <?php
                }
            } //foreach
        }
         ?>
         <?php if (!empty($medical_data)) {
                       if (checkPermission('Communication', 'signoff')) {
          ?> 
                        <div class="col-sm-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2>sign off</h2>
                                    <?php
                                    if($past_care_id == 0){
                                    if(empty($check_signoff_data)){ ?>
                                    
                                <input type="checkbox" name="coms_signoff" onclick="manager_request_coms(<?php echo $ypid ; ?>,<?php echo $co_id;?>);" class="" value="1">

                            <?php 
                            $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
                            echo getUserName($login_user_id);
                        } }
                        ?>



                            <?php
                       if (!empty($coms_signoff_data)) {
                                foreach ($coms_signoff_data as $sign_name) {
                                ?>

                                <ul class="media-list media-xs">
                                    <li class="media">
                                        <div class="media-body">
                                            <p class="date"><small><?php echo $sign_name['name'] ?>:  <?php echo configDateTimeFormat($sign_name['created_date']); ?></small></p>
                                        </div>
                                    </li>
                                </ul>
                            <?php }
                        }
                        ?> 
                            
                                </div>
                            </div>
                        </div>
                        <?php }} ?>
              <?php if (!empty($medical_data)) { 
            if (checkPermission('Communication', 'document_signoff')) {
                    ?>
            <div class="col-sm-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                 
                                 <?php 
                                 if($past_care_id == 0){ ?> 
                                    <a href="javascript:;" data-href="<?php echo base_url() . 'Communication/signoff/'.$ypid.'/'.$co_id; ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" ><span class="label label-warning"><?php echo lang('external_approval'); ?></span></a>
                                 <?php if(!empty($check_external_signoff_data)){ ?>
                                 <a href="<?php echo base_url() . 'Communication/external_approval_list/'.$ypid.'/'.$co_id; ?>" > <span class="label label-warning"><?php echo lang('external_approval_list'); ?></span>
                                    </a>
                                <?php }}else{ ?>
                                    <a href="<?php echo base_url() . 'Communication/external_approval_list/'.$ypid.'/'.$co_id.'/'.$care_home_id.'/'.$past_care_id; ?>" > <span class="label label-warning"><?php echo lang('external_approval_list'); ?></span>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div> 

           <?php }} ?>  
    </div>
    <div class="row">
            <div class="col-xs-12">
                <div class="pull-right btn-section">
                    <div class="btn-group">
                    <?php 
                    /* condition added by Ritesh Ranan on 28/09/2018 to archive functionality */
                    if($past_care_id == 0){?>
                    <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                     <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>
                    <a href="<?=base_url('Communication/index/'.$ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply" aria-hidden="true"></i> BACK
                    </a>
                    <?php }else{ ?>
                    <a href="<?=base_url('ArchiveCarehome/index/'.$care_home_id)?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> ARCHIVE CAREHOME YP LIST</a>
                    <a href="<?=base_url('ArchiveCarehome/view/'.$ypid.'/'.$care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>

                    <a href="<?=base_url('Communication/index/'.$ypid.'/'.$care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply" aria-hidden="true"></i> BACK
                    </a>
                    <?php } ?>
                    <a href="<?= base_url('Communication/DownloadPrint/' . $co_id .'/'. $ypid .'/print'); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> PRINT </a>
                    </div>
                </div>
            </div>
        </div>
</div>
</div>
