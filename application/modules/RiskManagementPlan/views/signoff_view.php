<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypid; ?>';
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
		<div class="pull-right for-tab">
                <div class="btn-group">
                   <a href="<?= base_url('RiskManagementPlan/DownloadPDF_after_mail/' . $ypid . '/' . $signoff_id); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Generate PDF </a>
                </div>
            </div>
            Risk Management Plan <small>New Forest Care</small>
        </h1>
        <h1 class="page-title">
		
            <small>Name: </small><?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
    </div>
        <div class="row">
               <div class="col-lg-12">
                    <div class="panel panel-default tile tile-profile">
                        <div class="panel-body">
                        <?php
                         if(!empty($form_data))
                        {
                        foreach ($form_data as $row) {
                        if($row['type']== 'radio-group' || $row['type']== 'date'|| $row['type']== 'select'|| $row['type']== 'number'|| $row['type']== 'text'|| $row['type']== 'checkbox-group' ) {
                                ?>
                                
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                                <label><?=!empty($row['label'])?$row['label']:''?></label>
                                                 <label class="value large">
                                                <?php if($row['type'] == 'textarea' || $row['type']== 'date'|| $row['type']== 'number'|| $row['type']== 'text' ) {
                                                    if($row['type'] == 'date'){
                                                        if(!empty($row['value']) && $row['value'] !='0000-00-00'){
                                                                echo configDateTime($row['value']);
                                                        }                                                        
                                                    }else if(isset($row['subtype'])&& $row['subtype'] == 'time'){
                                                        if((!empty($row['value']))){
                                                            echo timeformat($row['value']);
                                                        }
                                                    }else{
                                                        echo !empty($edit_data[0][$row['name']])?nl2br(html_entity_decode($edit_data[0][$row['name']])):(isset($row['value'])?$row['value']:'');
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
                                                                echo isset($chked['selected'])?'<li>'.$chked['value']."\n":'';
                                                                 }
                                                               
                                                            }
                                                        }?>

                                                   <?php }  else if($row['type'] == 'radio-group' || $row['type'] == 'select') { 
                                                     if(!empty($row['values'])) {
                                                        if(!empty($row['description']) && ($row['description'] == 'get_user' ||$row['description'] == 'select_multiple_user'))
                                                        {
                                                              $userAr = explode(',',$row['values']);
                                                              if(!empty($userAr))
                                                              {
                                                                foreach ($userAr as $uid) {
                                                                    echo '<p>'.getUserName($uid).'</p>';
                                                                }
                                                              }
                                                        }
                                                        else
                                                        {
                                                            echo !empty($row['values'])?nl2br(htmlentities($row['values'])):'';
                                                        }
                                                        
                                                     }
                                                     else
                                                     {
                                                        echo lang('NA');
                                                     }
                                                    } ?>
                                                </label>
                                            
                                        </div>
                                    </div>
                                    
                                <?php
                                } } } ?>
                        </div>
                    </div>
                    </div>
                <?php
                if(!empty($form_data))
                {
                    foreach ($form_data as $row) {

                        if ($row['type'] == 'textarea') { ?>
                            <div class="col-lg-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                        <?=!empty($edit_data[0][$row['name']])?nl2br(html_entity_decode($edit_data[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
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
                                                'filePathMain' => $this->config->item('rmp_img_base_url') . $ypid,
                                                'filePathThumb' => $this->config->item('rmp_img_base_url_small') . $ypid
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
                 
                        <div class="col-lg-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                <h2>sign off</h2>
                                  
                                    <?php
                                   if (!empty($signoff_data)) {
                                            foreach ($signoff_data as $sign_name) {
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

                    <div class="col-lg-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>Comments Box</h2>
                                <?php if(!empty($comments)){ 
                                    foreach ($comments as $comments_data) {
                                ?>

                                <ul class="media-list media-xs">
                                    <li class="media">
                                        <div class="media-body">
                                            <p class="small">
                                        <?php echo $comments_data['rmp_comments']?>
                                            </p>
                                            <p class="date"><small><?php echo $comments_data['create_name'] ?>:   <?php echo configDateTime($comments_data['created_date']) ?></small></p>
                                        </div>
                                    </li>
                                </ul>
                                <?php } }?>   

                            </div>
                        </div>
                    </div>
                        <div class="col-sm-12">
                    <div class="panel panel-default tile tile-profile">

                        <div class="panel-body">
                            <h2>User sign off</h2>
                                    <input type="hidden" id="email_data" value="<?php echo $key_data; ?>" name="email_data">
                                    <input type="checkbox" onclick="signoff_request(<?php echo $ypid . ',' . $signoff_id; ?>);" name="signoff_data" class="" value="active">
                            
                        </div>

                    </div>
                </div>
            
                </div>
    
    </div>
</div>