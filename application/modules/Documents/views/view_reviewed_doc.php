<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypid; ?>';
</script>
        <div id="page-wrapper">
            <div class="main-page">
                <?php if(($this->session->flashdata('msg'))){ 
                        echo $this->session->flashdata('msg');
                    
                } ?>
                <div class="sticky-heading" id="sticky-heading">
                <h1 class="page-title">
                     Documents external approval <small>New Forest Care</small>
                    <div class="pull-right">
                        <div class="btn-group">
                           
                            <a href="<?=base_url('Documents/reportReviewedBy/'.$doc_id.'/'.$ypid); ?>" class="btn btn-default">
                                     <i class="fa fa-mail-reply"></i> BACK TO PAGE
                                </a>
                            
                        </div>
                    </div>
                </h1>
               <h1 class="page-title">
                    <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
                </h1>
            </div>
                <div class="row">
                
                    <?php
                     if(!empty($doc_form_data))
                    {
                    foreach ($doc_form_data as $row) {
                    if($row['type']== 'radio-group' || $row['type']== 'date'|| $row['type']== 'select'|| $row['type']== 'number'|| $row['type']== 'text'|| $row['type']== 'checkbox-group' || $row['type']== 'textarea' ) {
                            ?>
                        <div class="col-lg-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <div class="">
                                        <div class="form-group">
                                                <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                                
                                                 <label class="value large">
                                                <?php if($row['type'] == 'textarea' || $row['type']== 'date'|| $row['type']== 'number'|| $row['type']== 'text' ) { ?>
                                        <?php if($row['subtype'] == 'time'){ ?> 
                                            <?= !empty($row['value']) ? timeformat($row['value']) : '' ?>
                                    <?php }elseif($row['type'] == 'date'){ ?>
                                            <?= !empty($row['value']) ? configDateTime($row['value']) : '' ?>
                                    <?php }else{ ?>
                                            <?= !empty($row['value']) ? $row['value'] : '' ?>
                                    <?php } ?>

                                                   <?php  }
                                                    else if($row['type'] == 'checkbox-group') {
                                                            if(count($row['values']) > 0) {
                                                             foreach ($row['values'] as $chked) {
                                                                echo isset($chked['selected'])?'<li>'.$chked['value']."\n":'';
                                                                 }
                                                            }
                                                        ?>
                                                    <?php }  else if($row['type'] == 'radio-group' || $row['type'] == 'select') {
                                                         if(count($row['values']) > 0) {
                                                                
                                                             foreach ($row['values'] as $chked) {
                                                                echo isset($chked['selected'])?$chked['value']:'';
                                                                 }
                                                                
                                                            }
                                                     }
                                                    ?>
                                                </label>
                                            
                                        </div>
                                    </div>
                                    </div>
                            </div>
                        </div>
                                <?php }elseif($row['type'] == 'header'){ ?>
                                 <div class="col-lg-12">
                                        <div class="">
                                            <?php $head =!empty($row['subtype'])?$row['subtype']:'h1' ?>
                                            <?php echo '<'.$head.' class="page-title">'; ?>
                                            <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                                
                                            <?php echo '</'.$head.'>'; ?>
                                        </div>
                                    </div>
                                <?php }else if ($row['type'] == 'file') { ?>
                                <div class="col-lg-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                        <div class="">   
                                            <?php 
                                            /* common file display block replaced by Dhara Bhalala on 25/09/2018 */
                                            $fileViewArray = array(
                                                'fileArray' => (isset($edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']]))? $edit_data[0][$row['name']] : $row['value'],
                                                'filePathMain' => $this->config->item('docs_img_base_url') . $ypid,
                                                'filePathThumb' => $this->config->item('docs_img_base_url') . $ypid,
                                                'class' => 'btn btn-link btn-blue'
                                            );
                                            echo getFileView($fileViewArray); 
                                            ?>                               
                                        </div>
                                    </div>
                                </div>
                            </div>

                          <?php  }} ?>                                
                    <?php } ?>
                        
                  <?php 
                    if (checkPermission('Documents', 'signoff')) {
                        if (!empty($signoff_data)) {
                  ?>
                        <div class="col-lg-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                <h2>sign off</h2>

                      
                        <?php
                       
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
                        
                        ?> 

                                </div>
                            </div>
                        </div>
                    <?php } } ?>
                </div>
                
                <div class="row">
                    <div class="col-xs-12">
                        <div class="pull-right btn-section">
                            <div class="btn-group capsBtn">
                                <a href="<?=base_url('Documents/reportReviewedBy/'.$doc_id.'/'.$ypid); ?>" class="btn btn-default">
                                     <i class="fa fa-mail-reply"></i> BACK TO PAGE
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>