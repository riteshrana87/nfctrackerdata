<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <?php
        if (($this->session->flashdata('msg'))) {
            echo $this->session->flashdata('msg');
        }
        ?>
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            Individual Strategies <small>New Forest Care</small>
            <div class="pull-right for-tab">
                <div class="btn-group">
                    <a href="<?=base_url('IndividualStrategies/external_approval_list/'.$ypid.'/'.$is_id); ?>" class="btn btn-default width_a">
                        <i class="fa fa-mail-reply"></i> BACK TO PAGE
                    </a>
                   
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
            if (!empty($pp_form_data)) {
                foreach ($pp_form_data as $row) {

                    if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'select' || $row['type'] == 'number' || $row['type'] == 'text' || $row['type'] == 'checkbox-group') {
                        ?>
                        <div class="col-sm-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
                                    <div class ="small">
                                        <?php
                                        if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') { ?>
                                            <?php if($row['subtype'] == 'time'){ ?>
                                                <?=!empty($row['value'])?timeformat($row['value']):''?>
                                            <?php }elseif($row['type'] == 'date'){ ?>
                                                <?=!empty($row['value'])?configDateTime($row['value']):''?>
                                            <?php }else{ ?>
                                                <?=!empty($row['value'])?$row['value']:''?>
                                            <?php } ?>

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

                                        <?php } ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <?php
                    } else if ($row['type'] == 'radio-group') {
                        ?>
                        <div class="col-sm-6">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <label><?= !empty($row['label']) ? $row['label'] : '' ?></label>
                                    <div class="pull-right">
                                        <?php
                                        if (!empty($edit_data[0][$row['name']])) {
                                            echo ($edit_data[0][$row['name']] == 'yes') ? '<span class="label label-success">Yes</span>' : (($edit_data[0][$row['name']] == 'no') ? '<span class="label label-danger">No</span>' : '<span class="label label-success">' . $edit_data[0][$row['name']] . '</span>');
                                        } else {
                                            if (!empty($row['value'])) {
                                                echo ($row['value'] == 'yes') ? '<span class="label label-success">Yes</span>' : (($row['value'] == 'no') ? '<span class="label label-danger">No</span>' : '<span class="label label-success">' . $row['value'] . '</span>');
                                            }
                                        }
                                        ?></div>
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
                                <?php
                            } else if ($row['type'] == 'file') {
                                ?>
                        <div class="col-sm-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
                                    <div class="">   
                                    <?php
                                    /* common file display block replaced by Dhara Bhalala on 25/09/2018 */
                                    $fileViewArray = array(
                                        'fileArray' => (isset($edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']]))? $edit_data[0][$row['name']] : $row['value'],
                                        'filePathMain' => $this->config->item('is_img_base_url') . $ypid,
                                        'filePathThumb' => $this->config->item('is_img_base_url_small') . $ypid
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


<!--start Amendments -->
<div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                        <h2>Amendments</h2>

                        <?php if (!empty($item_details)) {$n= 0;
                                                    foreach ($item_details as $row) { 
                                                ?>

                                <ul class="media-list media-xs">
                                    <li class="media">
                                        <div class="media-body">
                                            <p class="small">
                                            <?php if (!empty($item_details_archive[$n]['amendments']) && $item_details_archive[$n]['amendments_id'] == $row['amendments_id']) {

                                                $diff = new HtmlDiff($item_details_archive[$n]['amendments'], $row['amendments']);
                                                $diff->build();
                                                echo $diff->getDifference();
                                                ?>
                                             <?php } else { ?>
                                             <?php if(!empty($item_details_archive))
                                                {
                                                     $diff = new HtmlDiff('', $row['amendments']);
                                                        $diff->build();
                                                        echo $diff->getDifference();
                                                }else{
                                                    ?>
                                             <?= !empty($row['amendments']) ? $row['amendments'] : '' ?>
                                             <?php
                                                }
                                              } ?>
                                            
                                            </p>
                                            <p class="date"><small>
                                                <?php echo $row['create_name'] ?>: <?php echo configDateTimeFormat($row['created_date']); ?></small></p>
                                        </div>
                                    </li>
                                </ul>
                            <?php $n++; }
                        }
                        ?>   
                        
                    </div>
                </div>
            </div>                
           <!--end Amendments -->  
           <!--start Current Protocols in place -->                         
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                        <h2>Current Protocols in place</h2>
                        <?php if (!empty($protocols_details)) { $n =0;
                                foreach ($protocols_details as $row) { 
                        ?> 

                                <ul class="media-list media-xs">
                                    <li class="media">
                                        <div class="media-body">
                                            <p class="small">
                                            <?php if (!empty($protocols_details_archive[$n]['current_protocols_in_place']) && $protocols_details_archive[$n]['protocols_in_place_id'] == $row['protocols_in_place_id']) { 

                                                $diff = new HtmlDiff($protocols_details_archive[$n]['current_protocols_in_place'], $row['current_protocols_in_place']);
                                                $diff->build();
                                                echo $diff->getDifference();
                                                ?>
                                             <?php } else { ?>
                                             <?php if(!empty($protocols_details_archive))
                                                {
                                                     $diff = new HtmlDiff('', $row['current_protocols_in_place']);
                                                        $diff->build();
                                                        echo $diff->getDifference();
                                                }else{
                                                    ?>
                                             <?= !empty($row['current_protocols_in_place']) ? $row['current_protocols_in_place'] : '' ?>
                                             <?php
                                                }
                                              } ?>
                                            
                                            </p>
                                            <p class="date"><small>
                                                <?php echo $row['create_name'] ?>: <?php echo configDateTimeFormat($row['created_date']); ?></small></p>
                                        </div>
                                    </li>
                                </ul>
                            <?php $n++; }
                        }
                        ?>   
                        
                    </div>
                </div>
            </div> 
            <!--end Current Protocols in place --> 
            <!-- start signof  --> 
            <?php if (checkPermission('ArchivePlacementPlan', 'signoff')) { 
                if (!empty($signoff_data)) {?>
            <div class="col-sm-12">
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
<!-- end signof  --> 
<!-- start comments  --> 
<?php if(!empty($comments)){ ?>
                <div class="col-lg-12">
                        <div class="panel panel-default tile tile-profile">
                            <div class="panel-body">
                                <h2>Comments Box</h2>
                                <?php
                                    foreach ($comments as $comments_data) {
                                ?>

                                <ul class="media-list media-xs">
                                    <li class="media">
                                        <div class="media-body">
                                            <p class="small">
                                        <?php echo $comments_data['ks_comments']?>
                                            </p>
                                            <p class="date"><small><?php echo $comments_data['create_name'] ?>:   <?php echo configDateTime($comments_data['created_date']); ?></small></p>
                                        </div>
                                    </li>
                                </ul>
                                <?php }?>   

                            </div>
                        </div>
                    </div>
                    <?php } ?>
<!-- end comments  -->                     
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="pull-right btn-section">
                    <div class="btn-group capsBtn">
                        <a href="<?=base_url('IndividualStrategies/external_approval_list/'.$ypid.'/'.$is_id); ?>" class="btn btn-default width_a">
                            <i class="fa fa-mail-reply"></i> BACK TO PAGE
                        </a> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>