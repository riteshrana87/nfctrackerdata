<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <h1 class="page-title">
            Communication <small>New Forest Care</small>
            <div class="pull-right">
                <div class="btn-group">
                     <a href="<?=base_url('Admin/Reports/COMS'); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> COMS Reports
                    </a>
                </div>
            </div>
        </h1>
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
                                        <?php if($row['type'] == 'textarea' || $row['type']== 'date'|| $row['type']== 'number'|| $row['type']== 'text' ) {?>
                                         
                                           
                                           <?=!empty($medical_data[0][$row['name']])?nl2br(html_entity_decode($medical_data[0][$row['name']])):(isset($row['value'])?$row['value']:'')?>
                                           <?php  }
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
               <?php
                }
            } //foreach
        }
         ?>
        </div>
       
    </div>
</div>