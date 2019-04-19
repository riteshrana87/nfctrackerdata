<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord) ? 'updatedata' : 'savedata';
?>
<!-- main content start-->
<script>
    var valurl = "<?php echo base_url('SdqReport/getValsum'); ?>";
</script>
<div id="page-wrapper">
    <div class="main-page">
        <form id="savesdqreport" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url($crnt_view . '/' . $formAction); ?>" data-parsley-validate = "">
            <?php
            if (($this->session->flashdata('msg'))) {
                echo $this->session->flashdata('msg');
            }
            ?>
            <div class="sticky-heading" id="sticky-heading">
            <h1 class="page-title">
                <?PHP if ($formAction == "savedata") { ?> Complete an SDQ Assessment  <?php } else { ?> EDIT Complete an SDQ Assessment <?php } ?>
                <div class="pull-right pull-left-sm-xs for-tab">
                    <div class="btn-group">
                        <?php //if (checkPermission('ArchiveYoungPerson', 'view')) { ?>
                        <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                        <a href="<?= base_url('YoungPerson/view/' . $ypid); ?>" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i> YP INFO
                        </a>
                        <?php //} ?>
                    </div>
                    <div class="btn-group">
                        <a href="<?= base_url('SdqReport/index/' . $ypid); ?>" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i> SDQ Record Sheet List</a>
                    </div>
                </div>
              <div class="clearfix"></div>
            </h1>
            <h1 class="page-title">
                <small>Name: </small><?php echo isset($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?php echo isset($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>Gender:</small> <?php if(!empty($YP_details[0]['gender'])){ if($YP_details[0]['gender'] == 'M'){ ?> Male<?php } else{ ?>Female <?php }} else{ ?> N/A <?php } ?>
            </h1>
        </div>
            <div class="<?php if ($formAction == 'updatedata') { ?> col-md-3<?php } else{ ?> col-md-2 <?php } ?>col-sm-4 col-xs-12">
                <div class="form-group">
                    <?php if ($formAction == 'updatedata') { ?>
                    <h1 class="page-title"><small>SDQ Completed By: </small> <?php echo isset($editRecord[0]['user_type']) ? $editRecord[0]['user_type'] : ''; ?> </h1>
                    <input type="hidden" class="form-control" name="user_type" readonly="" value="<?php echo $editRecord[0]['user_type']; ?>">
                    <?php } else {?>
                     <select name="user_type" required="" class="form-control">
                        <option value="">Select User</option>
                        <option value="parent">Parent</option>
                        <option value="teacher">Teacher</option>
                        <option value="self">Self</option>
                    </select>
                    <?php } ?>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <!-- form start -->
<?php
if (!empty($sdq_que_details)) {
    foreach ($sdq_que_details as $row) {
        $sub_que_details = $this->SdqReport_model->getSubQue($row['sdq_que_id']);
        ?>
                        <div class="col-sm-12">
                            <div class="panel panel-default tile tile-profile">
                                <div class="panel-body">
                                    <h2><?php echo!empty($row['que']) ? $row['que'] : '' ?></h2>
                                    <input type="hidden" name="que_id[]" value="<?php echo!empty($row['sdq_que_id']) ? $row['sdq_que_id'] : '' ?>">
        <?php
        if (count($sub_que_details) > 0) {
            foreach ($sub_que_details as $result) {
                ?>
                                            <div class="row m-b-10">
                                                <div class="col-md-7">
                                                    <label class="m-t-7"><?php echo!empty($result['sub_que']) ? $result['sub_que'] : '' ?></label>
                                                    <input type="hidden" name="sub_que_id[]" value="<?php echo!empty($result['sdq_sub_que_id']) ? $result['sdq_sub_que_id'] : '' ?>">
                                                </div>
                <?php $subQueansValue = $this->SdqReport_model->getSdqAnsValue(!empty($editRecord[0]['id']) ? $editRecord[0]['id'] : '', $result['sdq_sub_que_id']); ?>
                                                <div class="col-md-5">
                                                    <div class="radio-group sdq-report">
                                                        <div class="radio-inline">
                                                            <label><input name="ans_<?php echo $result['sdq_sub_que_id']; ?>"  class="" value="0" type="radio" <?= ($subQueansValue[0]['ans'] == '0') ? 'checked=""' : '' ?> onchange="getVal('#savesdqreport');" required="" data-parsley-errors-container="#erro-div_<?php echo $result['sdq_sub_que_id']; ?>"> Not True</label>
                                                        </div>
                                                        <div class="radio-inline">
                                                            <label><input name="ans_<?php echo $result['sdq_sub_que_id']; ?>"  class="" value="1" type="radio" <?= (isset($subQueansValue[0]['ans']) && $subQueansValue[0]['ans'] == '1') ? 'checked=""' : '' ?> onchange="getVal('#savesdqreport');" required=""> Somewhat True</label>
                                                        </div>
                                                        <div class="radio-inline">
                                                            <label><input name="ans_<?php echo $result['sdq_sub_que_id']; ?>"  class="" value="2" type="radio" <?= (isset($subQueansValue[0]['ans']) && $subQueansValue[0]['ans'] == '2') ? 'checked=""' : '' ?> onchange="getVal('#savesdqreport');" required=""> Certainly True</label>
                                                        </div>
                                                    </div>
                                                    <div id="erro-div_<?php echo $result['sdq_sub_que_id']; ?>"></div>
                                                </div>
                                            </div>
                                <?php
                            }
                        }
                        ?>
                                </div>
                            </div>
                        </div>
        <?php
    }
} //foreach
?>
                <div class="col-sm-6">
                    <div class="form-inline">                                   
                        <div class="form-group m-lr-0">
                            <label>TOTAL OVERALL SCORE : </label>
                            <label><input name="total_score"  id="total_score" class="form-control" value="<?php echo!empty($editRecord[0]['total_score']) ? $editRecord[0]['total_score'] : '' ?>" type="text"> </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="pull-right">
                        <input type="hidden" name="yp_id" id="yp_id" value="<?php echo isset($ypid) ? $ypid : '' ?>">
                        <input type="hidden" name="record_id" id="record_id" value="<?php echo isset($editRecord[0]['id']) ? $editRecord[0]['id'] : '' ?>">
                        <button type="submit"  class="btn btn-default" name="submit" id="submit" value="submit" style="default">Save</button>
                        <!--<a href="" class="btn btn-default" name="submit_sdqform" id="submit_sdqform" value="submit" style="default">Archive</a>-->
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>