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
            KEY SESSIONS <small>New Forest Care</small>
            <div class="pull-right">
                <div class="btn-group">
                    <?php 
                    /* condition added by Ritesh Ranan on 28/09/2018 to archive functionality */
                    if($past_care_id == 0){ ?>
                   <a href="<?=base_url('KeySession/external_approval_list/'.$ypid.'/'.$ks_id); ?>" class="btn btn-default width_a">
                        <i class="fa fa-mail-reply"></i> BACK TO PAGE
                    </a>
                    <?php }else{ ?>
<a href="<?=base_url('KeySession/external_approval_list/'.$ypid.'/'.$ks_id.'/'.$care_home_id.'/'.$past_care_id); ?>" class="btn btn-default width_a">
                        <i class="fa fa-mail-reply"></i> BACK TO PAGE
                    </a>                        
                    <?php } ?>
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
                        <div class="<?= ($row['type'] == 'header') ? 'col-sm-12' : 'col-sm-6' ?>">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
                                    <div class ="slimScroll-120">
                                        <?php
                                        if ($row['type'] == 'textarea' || $row['type'] == 'date' || $row['type'] == 'number' || $row['type'] == 'text') {

                                            if (!empty($form_old_data) && $row['name'] == $form_old_data[$n]['name']) {
                                                $diff = new HtmlDiff($form_old_data[$n]['value'], $row['value']);
                                                $diff->build();
                                                echo $diff->getDifference()
                                                ?>
                                            <?php } else { 
                                                if($row['type'] == 'date'){
                                                    if((!empty($row['value']) && $row['value'] !='0000-00-00')){
                                                            echo configDateTime($row['value']);
                                                    }                                                        
                                                }else if(isset($row['subtype'])&& $row['subtype'] == 'time'){
                                                    if((!empty($row['value']))){
                                                        echo timeformat($row['value']);
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
                                        } else if ($row['type'] == 'select') {
                                            if (!empty($edit_data[0][$row['name']])) {
                                                $chk = explode(',', $edit_data[0][$row['name']]);
                                                foreach ($chk as $chk) {
                                                    echo $chk . "\n";
                                                }
                                            } else {
                                                    if(count($row['values']) > 0) {
                                                                 foreach ($row['values'] as $chked) {
                                                                    if($chked['value'] == $row['value']){
                                                                        echo isset($chked['label'])?$chked['label']:'';    
                                                                    }
                                                                    
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
                                        'filePathMain' => $this->config->item('ks_img_base_url') . $ypid,
                                        'filePathThumb' => $this->config->item('ks_img_base_url_small') . $ypid
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

            <?php if (checkPermission('KeySession', 'signoff')) { 
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
                            <?php }?>
                    </div>

                </div>
            </div>

<?php } } ?>
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

        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="pull-right btn-section">
                    <div class="btn-group capsBtn">
                        <?php 
                    /* condition added by Ritesh Ranan on 28/09/2018 to archive functionality */
                    if($past_care_id == 0){ ?>
                   <a href="<?=base_url('KeySession/external_approval_list/'.$ypid.'/'.$ks_id); ?>" class="btn btn-default width_a">
                        <i class="fa fa-mail-reply"></i> BACK TO PAGE
                    </a>
                    <?php }else{ ?>
<a href="<?=base_url('KeySession/external_approval_list/'.$ypid.'/'.$ks_id.'/'.$care_home_id.'/'.$past_care_id); ?>" class="btn btn-default width_a">
                        <i class="fa fa-mail-reply"></i> BACK TO PAGE
                    </a>                        
                    <?php } ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
