<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <?php if(($this->session->flashdata('msg'))){ 
                echo $this->session->flashdata('msg');
            
        } ?>
        <h1 class="page-title">
            Documents   <small>New Forest Care</small>
            <div class="pull-right">
                <div class="btn-group">
                    <a href="<?=base_url('Admin/Reports/DOCS'); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> DOCS Reports
                    </a>
                   
                </div>
            </div>
        </h1>
       <h1 class="page-title">
            <small>Name: </small><?=!empty($YP_details[0]['yp_fname'])?$YP_details[0]['yp_fname']:''?> <?=!empty($YP_details[0]['yp_lname'])?$YP_details[0]['yp_lname']:''?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
        <div class="row">
        <?php
        if(!empty($form_data))
        {
            foreach ($form_data as $row) {

             if($row['type'] == 'textarea' || $row['type']== 'date'|| $row['type']== 'select'|| $row['type']== 'number'|| $row['type']== 'text'|| $row['type']== 'checkbox-group' ) {
                ?>
                    <div class="<?=($row['type'] == 'header')?'col-sm-12':'col-sm-6'?>">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                <div class ="slimScroll-120">
                                <?php if($row['type'] == 'textarea' || $row['type']== 'date'|| $row['type']== 'number'|| $row['type']== 'text' ) {?>
                                 
                                
                                   
                                   <?=!empty($edit_data[0][$row['name']])?nl2br(html_entity_decode($edit_data[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
                                   <?php  }
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
                                     if(!empty($edit_data[0][$row['name']])) {
                                        echo !empty($edit_data[0][$row['name']])?nl2br(htmlentities($edit_data[0][$row['name']])):'';
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
                                </div>
                               
                            </div>
                        </div>
                    </div>
                    <?php
                }
                else if($row['type'] == 'radio-group') {
                    ?>
                    <div class="col-sm-6">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <label><?=!empty($row['label'])?$row['label']:''?></label>
                                <div class="pull-right">
                    <?php
                                     if(!empty($edit_data[0][$row['name']])) {
                                        echo ($edit_data[0][$row['name']] == 'yes')?'<span class="label label-success">Yes</span>':(($edit_data[0][$row['name']] == 'no')?'<span class="label label-danger">No</span>':'<span class="label label-success">'.$edit_data[0][$row['name']].'</span>');
                                     }
                                     else
                                     { 
                                         if(count($row['values']) > 0) {
                                                
                                             foreach ($row['values'] as $chked) {
                                              
                                                 echo isset($chked['selected'])?($chked['value'] == 'no')?'<span class="label label-danger">No</span>':(($chked['value'] == 'yes')?'<span class="label label-warning">Yes</span>':''):'';
                                                 }
                                                
                                            }
                                     }
                                     ?></div>
                        </div>
                            </div>
                                </div>
                <?php
                }
                else if ($row['type'] == 'header') {
                   ?>
                   <div class="col-sm-12">
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
                    <div class="col-sm-12">
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
                <?php
                }
            } //foreach
        }
         ?>
            
            
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="pull-right btn-section">
                    <div class="btn-group">
                         <a href="<?=base_url('Admin/Reports/DOCS'); ?>" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i> DOCS Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>