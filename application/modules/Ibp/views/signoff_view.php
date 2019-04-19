<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?php echo $ypid ?>';
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
                   <a href="<?= base_url('Ibp/DownloadPdf/' . $ypid . '/' . $signoff_id); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Generate PDF </a>
                </div>
            </div>
            INDIVIDUAL BEHAVIOUR PLAN  <small>New Forest Care</small>
            <div class="pull-right">
                <div class="btn-group">
                    
                </div>
            </div>
        </h1>
        <h1 class="page-title">
            <small>Name: </small><?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
    </div>
        <div class="row">
                <?php
                $n = 0;
                if(!empty($form_data))
                {
                    foreach ($form_data as $row) {

                     if($row['type'] == 'textarea' || $row['type']== 'date' || $row['type']== 'select'|| $row['type']== 'number'|| $row['type']== 'text' || $row['type']== 'checkbox-group' ) { 
                        ?>
                            <div class="<?=($row['type'] == 'header')?'col-sm-12':'col-sm-6'?>">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?=!empty($row['label'])?$row['label']:''?></h2>
                                        <div class ="slimScroll-120">
                                        <?php if($row['type'] == 'textarea' || $row['type']== 'number') { ?>
                                                <?=!empty($row['value'])?$row['value']:''?>

                                           <?php  } else if($row['type'] == 'text') {
                                                        if($row['subtype'] == 'time') { ?>
                                                            <?=!empty($row['value'])?timeformat($row['value']):''?>
                                                            <?php }else{ ?>
                                                            <?=!empty($row['value'])?$row['value']:''?>
                                                            <?php }
                                                }else if($row['type'] == 'date') {?>
                                                            <?=!empty($row['value'])?configDateTime($row['value']):''?>
                                                        
                                                <?php } else if($row['type'] == 'checkbox-group') {
                                                    if(count($row['values']) > 0) {
                                                     foreach ($row['values'] as $chked) {
                                                        echo isset($chked['selected'])?'<li>'.$chked['value']."\n":'';
                                                         }
                                                       
                                                    }
                                                } else if($row['type'] == 'radio-group' || $row['type'] == 'select') {
                                                 if(count($row['values']) > 0) {
                                                        
                                                     foreach ($row['values'] as $chked) {
                                                        echo isset($chked['selected'])?$chked['value']:'';
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
                                                if(!empty($row['value'])) {
                                                    echo ($row['value'] == 'yes')?'<span class="label label-success">Yes</span>':(($row['value'] == 'no')?'<span class="label label-danger">No</span>':'<span class="label label-success">'.$row['value'].'</span>');
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
                                                'filePathMain' => $this->config->item('ibp_img_base_url') . $ypid,
                                                'filePathThumb' => $this->config->item('ibp_img_base_url_small') . $ypid
                                            );
                                            echo getFileView($fileViewArray);
                                            ?>                               
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    $n++;
                    } //foreach
                }
                 ?>
                    <?php
            if (checkPermission('ArchiveIbp', 'signoff')) { 
                if (!empty($ibp_signoff_data)) {
            ?>

          <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                        <h2>sign off</h2>
                        <?php
                       
                                foreach ($ibp_signoff_data as $sign_name) {
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
<?php } }?>

     
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