<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypid; ?>';
    var LRId = '<?php echo $location_register_id; ?>';

</script>
        <div id="page-wrapper">
            <div class="main-page">
                <?php if(($this->session->flashdata('msg'))){ 
                        echo $this->session->flashdata('msg');
                    
                } ?>
            <div class="sticky-heading" id="sticky-heading">
                <h1 class="page-title">
                   Location Register <small>New Forest Care</small>
                    <div class="pull-right for-tab">
                        <div class="btn-group">
                            <?php 
                             /* condition added by Ritesh Ranan on 27/09/2018 to archive functionality */
                            if($past_care_id == 0){ ?>
                            <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                            <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                            <a href="<?=base_url('LocationRegister/index/'.$ypid); ?>" class="btn btn-default">
                                     <i class="fa fa-mail-reply"></i> Location Register
                            </a>
                            <?php }else{ ?>
                            <a href="<?=base_url('ArchiveCarehome/index/'.$care_home_id)?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> ARCHIVE CAREHOME YP LIST</a>
                            <a href="<?=base_url('ArchiveCarehome/view/'.$ypid.'/'.$care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                            <a href="<?=base_url('LocationRegister/index/'.$ypid.'/'.$care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                                     <i class="fa fa-mail-reply"></i> Location Register
                            </a>
                            <?php } ?>
                            
                        </div>
                    </div>
                </h1>
               <h1 class="page-title">
                    <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
                </h1>
            </div>
                <div class="row">
                        <?php
                         if(!empty($ks_form_data))
                        {
                        foreach ($ks_form_data as $row) {
                        if($row['type']== 'radio-group' || $row['type']== 'date'|| $row['type']== 'select'|| $row['type']== 'number'|| $row['type']== 'text'|| $row['type']== 'checkbox-group' ) {
                                ?>
                                
                                   <div class="col-lg-6">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                                <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                                <?php if($row['type'] == 'textarea' || $row['type']== 'date'|| $row['type']== 'number'|| $row['type']== 'text' ) {
                                                    if($row['type'] == 'date'){
                                                        if(!empty($edit_data[0][$row['name']]) && $edit_data[0][$row['name']] !='0000-00-00'){
                                                                echo configDateTime($edit_data[0][$row['name']]);
                                                        }else{
                                                            echo lang('NA');
                                                        }                                                        
                                                    }else if(isset($row['subtype'])&& $row['subtype'] == 'time'){
                                                        if((!empty($edit_data[0][$row['name']]))){
                                                            echo timeformat($edit_data[0][$row['name']]);
                                                        }else{
                                                            echo lang('NA');
                                                        }
                                                    }else{
                                                        echo !empty($edit_data[0][$row['name']])?nl2br(html_entity_decode($edit_data[0][$row['name']])):(isset($row['value'])?$row['value']:lang('NA'));
                                                    }
                                                }
                                                    else if($row['type'] == 'checkbox-group') {
                                                    if(!empty($edit_data[0][$row['name']])) {
                                                        $chk = explode(',',$edit_data[0][$row['name']]);
                                                                foreach ($chk as $chk) {
                                                                    echo $chk."\n";
                                                                }
                                                             
                                                        
                                                    }else{

                                                            if(count($row['values']) > 0) {
                                                               
                                                             foreach ($row['values'] as $chked) {
                                                                echo isset($chked['selected'])?'<li>'.$chked['value']."\n":lang('NA');
                                                                 }
                                                               
                                                            }
                                                        }?>

                                                   <?php }else if($row['type'] == 'radio-group' || $row['type'] == 'select') {
                                                     if(!empty($edit_data[0][$row['name']])) {
                                                        echo !empty($edit_data[0][$row['name']])?nl2br(htmlentities($edit_data[0][$row['name']])):lang('NA');
                                                     }
                                                     else
                                                     {
                                                         if(count($row['values']) > 0) {
                                                                
                                                             foreach ($row['values'] as $chked) {
                                                                echo isset($chked['selected'])?$chked['value']:lang('NA');
                                                                 }
                                                                
                                                            }
                                                     }
                                                    } ?>
                                                
                                          </div>  
                                        </div>
                                    </div>
                                    
                                <?php
                                } } } ?>
                      
                <?php
                if(!empty($ks_form_data))
                {
                    foreach ($ks_form_data as $row) {

                        if ($row['type'] == 'textarea') { ?>
                            <div class="col-lg-6">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                        <?= (!empty($edit_data[0][$row['name']]) && trim($edit_data[0][$row['name']]) !='') ? nl2br(html_entity_decode($edit_data[0][$row['name']])): lang('NA') ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        else if ($row['type'] == 'header') {
                           ?>
                           <div class="col-lg-12">
                                        <div class="">
                                            <?php $head =!empty($row['subtype'])?$row['subtype']:'h1' ?>
                                            <?php echo '<'.$head.' class="page-title">'; ?>
                                            <?=!empty($row['label'])?$row['label']:''?>
                                                
                                            <?php echo '</'.$head.'>'; ?>
                                        </div>
                                    </div>
                           <?php
                        }
                        else if ($row['type'] == 'file') { ?>
                            <div class="col-lg-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                        <div class="">   
                                            <?php 
                                            /* common file display block replaced by Dhara Bhalala on 25/09/2018 */
                                            $fileViewArray = array(
                                                'fileArray' => (isset($edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']]))? $edit_data[0][$row['name']] : $row['value'],
                                                'filePathMain' => $this->config->item('lr_img_base_url') . $ypid,
                                                'filePathThumb' => $this->config->item('lr_img_base_url_small') . $ypid
                                            );
                                            echo getFileView($fileViewArray); 
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
                    if (checkPermission('LocationRegister', 'signoff')) { 
                    ?>
                        <div class="col-lg-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                <h2>sign off</h2>

                        <?php 
                         if($past_care_id == 0){
                        if(empty($check_ks_signoff_data)){ ?>
                                <input type="checkbox" name="lr_signoff" onclick="manager_request(<?php echo $ypid ; ?>,<?php echo $location_register_id;?>);" class="" value="1" >
                        <?php 
                        $login_user_id= $this->session->userdata['LOGGED_IN']['ID'];
                            echo getUserName($login_user_id);
                        }}
                         ?> 


                        <?php
                       if (!empty($ks_signoff_data)) {
                                foreach ($ks_signoff_data as $sign_name) {
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
                    <?php } } ?>
                    <?php if (!empty($edit_data)) { 
                if (checkPermission('LocationRegister', 'document_signoff')) {
                        ?>
                <div class="col-sm-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <?php 
                                     /* condition added by Ritesh Ranan on 27/09/2018 to archive functionality */
                                    if($past_care_id == 0){ ?>
                                     <a href="javascript:;" data-href="<?php echo base_url() . 'LocationRegister/signoff/'.$ypid.'/'.$location_register_id; ?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" ><span class="label label-warning"><?php echo lang('external_approval'); ?></span></a>
                                     <?php if(!empty($check_external_signoff_data)){ ?>
                                         <a href="<?php echo base_url() . 'LocationRegister/external_approval_list/'.$ypid.'/'.$location_register_id; ?>" > <span class="label label-warning"><?php echo lang('external_approval_list'); ?></span>
                                         </a>
                                    <?php } ?>
                                    <?php }else{ ?>

                                     <?php if(!empty($check_external_signoff_data)){ ?>
                                         <a href="<?php echo base_url() . 'LocationRegister/external_approval_list/'.$ypid.'/'.$location_register_id.'/'.$care_home_id.'/'.$past_care_id; ?>" > <span class="label label-warning"><?php echo lang('external_approval_list'); ?></span>
                                         </a>
                                    <?php } ?>

                                    <?php } ?>
                                </div>
                            </div>
                        </div> 
                
               <?php }} ?>
                </div>



                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="pull-right btn-section">
                            <div class="btn-group capsBtn">
                                 <?php if($past_care_id == 0){ ?>
                            <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                            <a href="<?=base_url('YoungPerson/view/'.$ypid); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                            <a href="<?=base_url('LocationRegister/index/'.$ypid); ?>" class="btn btn-default">
                                     <i class="fa fa-mail-reply"></i> Location Register
                            </a>
                            <?php }else{ ?>
                            <a href="<?=base_url('ArchiveCarehome/index/'.$care_home_id)?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> ARCHIVE CAREHOME YP LIST</a>
                            <a href="<?=base_url('ArchiveCarehome/view/'.$ypid.'/'.$care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                                    <i class="fa fa-mail-reply"></i> YP INFO
                            </a>
                            <a href="<?=base_url('LocationRegister/index/'.$ypid.'/'.$care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                                     <i class="fa fa-mail-reply"></i> Location Register
                            </a>
                            <?php } ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>