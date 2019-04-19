<!-- main content start-->
<script>
    var baseurl = '<?php echo base_url(); ?>';
    var YPId = '<?= !empty($edit_data[0]['yp_id']) ? $edit_data[0]['yp_id'] : $ypid ?>';
    var form_field_name = '<?php echo json_encode($form_field_data_name);?>';
    var wr_id = '<?=!empty($edit_data[0]['weekly_report_id'])?$edit_data[0]['weekly_report_id']:''?>';
</script>
<div id="page-wrapper">
    <div class="main-page">
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            WEEKLY REPORT TO SOCIAL WORKER <small>New Forest Care</small>
            <div class="pull-right for-tab">
                <div class="btn-group">
                    <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                    <a href="<?= base_url('YoungPerson/view/' . $ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>
                    <a href="<?= base_url('WeeklyReport/index/' . $ypid); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> WEEKLY REPORTS
                    </a>
                </div>
            </div>
        </h1>
        <h1 class="page-title">
            <small>Name: </small><?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small>  <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
    </div>
        <div class="row">
            <form action="<?= base_url('WeeklyReport/insert') ?>" method="post" id="wrform" name="wrform" data-parsley-validate enctype="multipart/form-data">
                <div class="col-sm-12">
                    <div class="panel panel-default tile tile-profile">
                        <div class="panel-body">
                          <div class="row">
                            <div class="col-sm-4">
                                <h2>Start Date <span class="astrick">*</span></h2>
                                <div class="input-group input-append date m-b-20-xs" id="date_picker_start_date">
                                    <input type="text" class="form-control" 
                                        name="start_date" id="start_date"
                                        value="<?= !empty($edit_data[0]['start_date']) ? date("d/m/Y", strtotime($edit_data[0]['start_date'])) : ''; ?>" readonly 
                                        data-parsley-required="true" data-parsley-errors-container="#errors-containerstartdate" />
                                    <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                                </div>
                                <span id="errors-containerstartdate" class="text-danger parsley-type"></span>
                            </div>
                            <div class="col-sm-4">
                                <h2>End Date <span class="astrick">*</span></h2>
                                <div class="input-group input-append date" id="date_picker_end_date">
                                    <input type="text" class="form-control" 
                                        name="end_date" id="end_date"
                                        value="<?= !empty($edit_data[0]['end_date']) ? date("d/m/Y", strtotime($edit_data[0]['end_date'])) : ''; ?>" readonly 
                                        data-parsley-required="true" data-parsley-errors-container="#errors-containerenddate" />
                                    <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                                </div>
                                <span id="errors-containerenddate" class="text-danger parsley-type"></span>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
                <?php
                if (!empty($form_data)) {
                    foreach ($form_data as $row) {
                        if ($row['type'] == 'textarea') {
                            ?>
                            <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>

                                        <textarea 
                                            class="<?= !empty($row['className']) ? $row['className'] : '' ?> <?= ($row['subtype'] == 'tinymce') ? 'tinyeditor' : '' ?>" 
                                            <?= !empty($row['required']) ? 'required=true' : '' ?>
                                            name="<?= !empty($row['name']) ? $row['name'] : '' ?>" 
                                            placeholder="<?= !empty($row['placeholder']) ? $row['placeholder'] : '' ?>"
                                            <?php if ($row['subtype'] != 'tinymce') { ?>
                                                <?= !empty($row['maxlength']) ? 'maxlength="' . $row['maxlength'] . '"' : '' ?>
                                                <?= !empty($row['rows']) ? 'rows="' . $row['rows'] . '"' : '' ?>
                                            <?php } ?>
                                            id="<?= !empty($row['name']) ? $row['name'] : '' ?>" ><?= !empty($edit_data[0][$row['name']]) ? strip_tags(html_entity_decode($edit_data[0][$row['name']])) : (isset($row['value']) ? $row['value'] : '') ?></textarea>
                                        <span id="errors-container<?= $row['name'] ?>" class="text-danger parsley-type"></span>
                                    </div>
                                </div>
                            </div>
                            <?php
                        } else if ($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date') {
                            if((!empty($row['subtype']) && $row['subtype'] == 'time') && isset($edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']])){
                                $edit_data[0][$row['name']] = timeformat($edit_data[0][$row['name']]);
                            }
                            if($row['type'] == 'date' && isset($edit_data[0][$row['name']]) && !empty($edit_data[0][$row['name']])){
                                $edit_data[0][$row['name']] = configDateTime($edit_data[0][$row['name']]);
                            }
                            ?>
                            <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>
                                        <?php if ((!empty($row['subtype']) && $row['subtype'] == 'time') || $row['type'] == 'date') { ?>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                <?php } ?>
                                                <div class="<?= (!empty($row['subtype']) && $row['subtype'] == 'time') ? 'input-group input-append bootstrap-timepicker' : '' ?><?= (!empty($row['type']) && $row['type'] == 'date') ? (!empty($row['name']) && $row['name'] == 'health_lac_date') ? 'input-group input-append date health_lac_date' : 'input-group input-append date medi_adddate' : '' ?>">

                                                    <input type="<?= (!empty($row['type']) && $row['type'] == 'number') ? 'text' : ((!empty($row['subtype']) && $row['subtype'] != 'time') ? $row['subtype'] : 'text') ?>" 
                                                           <?= (!empty($row['type']) && $row['type'] == 'number') ? 'data-parsley-type="number"' : ''; ?> 
                                                           class="<?= !empty($row['className']) ? $row['className'] : '' ?> <?= ($row['type'] == 'date') ? 'medi_adddate' : '' ?> <?= (!empty($row['subtype']) && $row['subtype'] == 'time') ? 'addtime' : '' ?>" 
                                                           <?= !empty($row['required']) ? 'required=true' : '' ?>
                                                           name="<?= !empty($row['name']) ? $row['name'] : '' ?>" id="<?= !empty($row['name']) ? $row['name'] : '' ?>" 
                                                           <?= !empty($row['maxlength']) ? 'maxlength="' . $row['maxlength'] . '"' : '' ?>
                                                           <?= (!empty($row['min']) || (!empty($row['min']) && $row['min'] == '0')) ? 'min="' . $row['min'] . '"' : '' ?>
                                                           <?= !empty($row['max']) ? 'max="' . $row['max'] . '"' : '' ?>
                                                           <?= !empty($row['step']) ? 'step="' . $row['step'] . '"' : '' ?>
                                                           placeholder="<?= !empty($row['placeholder']) ? $row['placeholder'] : '' ?>"
                                                           value="<?= !empty($edit_data[0][$row['name']]) ? $edit_data[0][$row['name']] : (isset($row['value']) ? $row['value'] : '') ?>" <?= ($row['type'] == 'date') ? 'readonly' : '' ?> data-parsley-errors-container="#errors-container<?= $row['name'] ?>" />
                                                           <?php if (!empty($row['subtype']) && $row['subtype'] == 'time') { ?>
                                                        <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>
                                                    <?php } ?>

                                                    <?php if (!empty($row['type']) && $row['type'] == 'date') { ?>
                                                        <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                                                    <?php } ?>

                                                </div>
                                                <span id="errors-container<?= $row['name'] ?>" class="text-danger parsley-type"></span>
                                                <?php if ((!empty($row['subtype']) && $row['subtype'] == 'time') || (!empty($row['type']) && $row['type'] == 'date')) { ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <?php
                        } else if ($row['type'] == 'radio-group') {
                            ?>
                            <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>

                                        <div class="radio-group">
                                            <?php
                                            if (count($row['values']) > 0) {
                                                foreach ($row['values'] as $radio) {
                                                    if (!empty($radio['label'])) {
                                                        ?>
                                                        <div class="<?= !empty($row['inline']) ? 'radio-inline' : 'radio' ?>">
                                                            <label ><input  name="<?= !empty($row['name']) ? $row['name'] : '' ?>" <?= !empty($row['required']) ? 'required=true' : '' ?> 
                                                                           class="<?= !empty($row['className']) ? $row['className'] : '' ?>" 
                                                                           id="<?= !empty($row['name']) ? $row['name'] : '' ?>"
                                                                           value="<?= !empty($radio['value']) ? $radio['value'] : '' ?>" 
																		   <?= (!empty($edit_data[0][$row['name']]) && $edit_data[0][$row['name']] == $radio['value']) ? 'checked="checked"' : isset($radio['selected']) ? 'checked="checked"' : '' ?>  
																		   type="radio" data-parsley-errors-container="#customRadioError<?php echo $row['name'];?>">
                                                                <?= !empty($radio['label']) ? $radio['label'] : '' ?></label>
                                                        </div>														
                                                    <?php
                                                    }
                                                }
                                            } //radio loop  
                                            ?>
                                        </div>
										<span class="parsley-type text-danger" id="customRadioError<?php echo $row['name'];?>"></span>
                                    </div>
                                </div>
                            </div>
                            <?php
                        } else if ($row['type'] == 'checkbox-group') {
                            ?>
                            <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>

                                        <div class="checkbox-group">
                                            <?php
                                            if (count($row['values']) > 0) {
                                                $checkedValues = array();
                                                if (!empty($edit_data[0][$row['name']])) {
                                                    $checkedValues = explode(',', $edit_data[0][$row['name']]);
                                                }
                                                foreach ($row['values'] as $checkbox) {
                                                    if (!empty($checkbox['label'])) {
                                                        ?>
                                                        <div class="<?= !empty($row['inline']) ? 'checkbox-inline' : 'checkbox' ?>">
                                                            <label class="<?= !empty($row['toggle']) ? 'kc-toggle' : '' ?>"><input 
                                                                    class="<?= !empty($row['className']) ? $row['className'] : '' ?> <?= !empty($row['toggle']) ? 'kc-toggle' : '' ?>"
                                                                    name="<?= !empty($row['name']) ? $row['name'] . '[]' : '' ?>" value="<?= !empty($checkbox['value']) ? $checkbox['value'] : '' ?>" <?= (!empty($checkedValues) && in_array($checkbox['value'], $checkedValues)) ? 'checked="checked"' : !empty($checkbox['selected']) ? 'checked="checked"' : '' ?>  
                                                                <?= !empty($row['required']) ? 'required=true' : '' ?>
                                                                    type="checkbox">
                                                        <?= !empty($checkbox['label']) ? $checkbox['label'] : '' ?></label>
                                                        </div>
                                                    <?php
                                                    }
                                                }
                                            } //radio loop  
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
            <?php
        } else if ($row['type'] == 'select') {
            ?>
                            <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>

                                        <select  class="chosen-select <?= !empty($row['className']) ? $row['className'] : '' ?>" name="<?= !empty($row['name']) ? $row['name'] : '' ?>" id="<?= !empty($row['name']) ? $row['name'] : '' ?>" <?= !empty($row['required']) ? 'required=true' : '' ?>>
                                            <option value="">Select</option>
                                            <?php
                                            if (count($row['values']) > 0) {
                                                foreach ($row['values'] as $select) {
                                                    if (!empty($select['label'])) {
                                                        ?>
                                                        <option value="<?= !empty($select['value']) ? $select['value'] : '' ?>" <?= (!empty($edit_data[0][$row['name']]) && $edit_data[0][$row['name']] == $select['value']) ? 'selected="true"' : !empty($select['selected']) ? 'selected="true"' : '' ?> ><?= !empty($select['label']) ? $select['label'] : '' ?></option>
                    <?php
                    }
                }
            } //select loop 
            ?>

                                        </select>

                                    </div>
                                </div>
                            </div>
            <?php
        } else if ($row['type'] == 'hidden' || $row['type'] == 'button') {
            ?>
            <?php if ($row['type'] == 'button') { ?>
                                <div class="col-sm-12">
                                    <div class="panel panel-default tile tile-profile">
                                        <div class="panel-body">
                                            <div class="fb-button form-group">

                                                <button name="<?= !empty($row['name']) ? $row['name'] : '' ?>" value="" type="<?= !empty($row['type']) ? $row['type'] : '' ?>" class="<?= !empty($row['className']) ? $row['className'] : '' ?>" name="<?= !empty($row['name']) ? $row['name'] : '' ?>" id="<?= !empty($row['name']) ? $row['name'] : '' ?>" style="<?= !empty($row['style']) ? $row['style'] : '' ?>"><?= !empty($row['label']) ? $row['label'] : '' ?></button>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ($row['type'] == 'hidden') { ?>
                                <div class="col-sm-12">
                                    <input type="hidden" name="<?= !empty($row['name']) ? $row['name'] : '' ?>" id="<?= !empty($row['name']) ? $row['name'] : '' ?>" value="" />
                                </div>
            <?php } ?>
            <?php
        } else if ($row['type'] == 'header') {
            ?>
                            <div class="col-sm-12">
                                <div class="">
                                    <h1 class="page-title"><?= !empty($row['label']) ? $row['label'] : '' ?></h1>
                                </div>
                            </div>
                        <?php } else if ($row['type'] == 'file') {
                                               ?>
                            <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2 class="page-title">PHOTO OF THE WEEK</h2>
                                      <button type="button" name="gallery" id="gallery-btn" data-href="<?php echo $url; ?>"  class="btn btn-primary">Select Attachment from tracker <br>Library</button>
                                      <div class="mediaGalleryImg"> </div>
                                        <h2></h2>
                                               <?php
                                        if (!empty($edit_data[0][$row['name']])) {
                                            $img = base_url($edit_data[0][$row['name']]);
                                                ?>
                                                <div class="col-sm-1 margin-bottom">
                                                    <?php
                                                    if (@is_array(getimagesize($img))) {
                                                        ?>
                                                        <img width="100" height="100" src="<?= $img ?>">
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <img width="100" height="100" src="<?= base_url('uploads/images/icons 64/file-ico.png') ?>">
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <?php
                                            
                                        }
                                        ?> 
            

                                    </div>

                                </div>
                            </div>

            <?php
        }
    } //foreach
    ?>
        
                    <div class="col-sm-12">
                        <div class="footer-btns">
                            <input type="hidden" name="saveAsDraft" id="saveAsDraft" value="0" />
                            <input type="hidden" name="weekly_report_id" id="weekly_report_id" value="<?= !empty($edit_data[0]['weekly_report_id']) ? $edit_data[0]['weekly_report_id'] : '' ?>">
                            <input type="hidden" name="yp_id" id="yp_id" value="<?= !empty($edit_data[0]['yp_id']) ? $edit_data[0]['yp_id'] : $ypid ?>">
                            <input type="hidden" name="care_home_id" id="care_home_id" value="<?= !empty($care_home_id) ? $care_home_id : '' ?>">
                            <input type="hidden" name="draft_data" id="draft_data" value="0">
                              <div class="btn-group">
                            <button type="submit" class="btn btn-default" name="draft_wrform" id="draft_wrform" style="default" onclick="$('#saveAsDraft').val(1);">SAVE AS DRAFT</button>
                            <button type="submit" class="btn btn-default" name="submit_wrform" id="submit_wrform" style="default" onclick="$('#saveAsDraft').val(0);">SAVE</button>
                              </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                                    <a href="<?= base_url('YoungPerson/view/' . $ypid); ?>" class="btn btn-default">
                                        <i class="fa fa-mail-reply"></i> YP INFO
                                    </a>
                                    <a href="<?= base_url('WeeklyReport/index/' . $ypid); ?>" class="btn btn-default">
                                        <i class="fa fa-mail-reply"></i> WEEKLY REPORTS
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
    <?php
}
?>

            </form>        
        </div>

    </div>
</div>
<div class="modal fade modal-image" id="modalGallery" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" onClick="$('#modalGallery').modal('hide');" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Uploads</h4>
      </div>
      <div class="modal-body" id="modbdy"> </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onClick="$('#modalGallery').modal('hide');">Close</button>
      </div>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>