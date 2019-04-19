<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord) ? 'updatedata' : 'savedata';
?>
<!-- main content start-->
<script>
    var valurl = "<?php echo base_url('CseReport/getValsum'); ?>";
</script>
<div id="page-wrapper">
    <div class="main-page">
        <form id="savecsereport" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url($crnt_view . '/' . $formAction); ?>" data-parsley-validate = "">
            <?php
            if (($this->session->flashdata('msg'))) {
                echo $this->session->flashdata('msg');
            }
            ?>
            <div class="sticky-heading" id="sticky-heading">
                <h1 class="page-title">
                        VIEW Complete an CSE Assessment 
                    <div class="pull-right pull-left-sm-xs for-tab">
                        <div class="btn-group">
                            <a href="<?=base_url('ArchiveCarehome/index/'.$care_home_id)?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> ARCHIVE CAREHOME YP LIST</a>

                     <a href="<?= base_url('ArchiveCarehome/view/' . $ypid. '/'. $care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                        <i class="fa fa-mail-reply"></i> YP INFO
                    </a>
                    <a href="<?= base_url('CseReport/index/' . $ypid.'/'.$care_home_id.'/'.$past_care_id); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> CSE Record Sheet List</a>
                        </div>
                        
                    </div>
                    <div class="clearfix"></div>
                </h1>
                <h1 class="page-title">
                    <small>Name: </small><?php echo isset($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?php echo isset($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?>
                    <small>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="visible-xs-block"></div>
                    </small>
                    <small>Gender:</small> <?php
                    if (!empty($YP_details[0]['gender'])) {
                        if ($YP_details[0]['gender'] == 'M') {
                            ?> Male<?php } else { ?>Female <?php
                        }
                    } else {
                        ?> N/A <?php } ?>
                </h1>
            </div>
            <div class="<?php if ($formAction == 'updatedata') { ?> col-md-3<?php } else { ?> col-md-2 <?php } ?>col-sm-4 col-xs-12">

                    <div class="form-group">
                        <h1 class="page-title">
                            <small>CSE Completed By: </small> <?php echo isset($editRecord[0]['user_type']) ? $editRecord[0]['user_type'] : ''; ?> 
                            <small>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <div class="visible-xs-block"></div>
                            </small>
                            <small>Date Of Complete:</small><?= (!empty($editRecord[0]['created_date']) && $editRecord[0]['created_date'] != '0000-00-00') ? configDateTime($editRecord[0]['created_date']) : '' ?>
                        </h1>
                        <input type="hidden" class="form-control" name="user_type" readonly="" value="<?php echo isset($editRecord[0]['user_type']) ? $editRecord[0]['user_type'] : ''; ?>">
                    </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="panel-group tool-tips" role="tablist" aria-multiselectable="true">
                    <!-- form start -->
                    <?php
                    if (!empty($cse_que_details)) {
                        foreach ($cse_que_details as $row) {
                            $sub_que_details = $this->CseReport_model->getSubQue($row['cse_que_id']);
                            ?>
                            <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-heading" role="tab" id="<?php echo!empty($row['que']) ? $row['que'] : '' ?>">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle text-uppercase collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo!empty($row['cse_que_id']) ? $row['cse_que_id'] : '' ?>" aria-expanded="true" aria-controls="collapse<?php echo!empty($row['que']) ? $row['que'] : '' ?>">
        <?php echo!empty($row['que']) ? $row['que'] : '' ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse<?php echo!empty($row['cse_que_id']) ? $row['cse_que_id'] : '' ?>" class="panel-collapse <?php if ($formAction == 'updatedata') { ?>collapse in<?php } else { ?>collapse<?php } ?>" role="tabpanel" aria-expanded="false" aria-labelledby="<?php echo!empty($row['cse_que_id']) ? $row['cse_que_id'] : '' ?>">
                                        <div class="panel-body">
                                            <input type="hidden" name="que_id[]" value="<?php echo!empty($row['cse_que_id']) ? $row['cse_que_id'] : '' ?>">
                                            <?php
                                            if (count($sub_que_details) > 0) {
                                                foreach ($sub_que_details as $result) {
                                                    ?>
                                                    <div class="row m-b-10">
                                                        <div class="col-md-9 col-sm-8">
                                                            <label class="m-t-7 f-w-normal"><?php echo!empty($result['sub_que']) ? $result['sub_que'] : '' ?></label>
                                                            <input type="hidden" name="sub_que_id[]" value="<?php echo!empty($result['cse_sub_que_id']) ? $result['cse_sub_que_id'] : '' ?>">
                                                        </div>
                <?php 
                $subQueansValue = getCseAnsValue(!empty($editRecord[0]['id']) ? $editRecord[0]['id'] : '', $result['cse_sub_que_id']); 
                ?>
                                                        <div class="col-md-3 col-sm-4">
                                                            <div class="radio-group sdq-report">
                                                                    <?php if ($subQueansValue[0]['ans'] == 'h') {  
                                                                            echo "High Risk";
                                                                     } ?>
                                                                    <?php if ($subQueansValue[0]['ans'] == 'm') { 
                                                                            echo "Medium Risk";
                                                                     } ?>
                                                                    <?php if ($subQueansValue[0]['ans'] == 'l') { 
                                                                            echo "Low Risk";
                                                                    } ?>
                                                                    <?php if ($subQueansValue[0]['ans'] == 'n') { 
                                                                        echo "No Known Risk";
                                                                     } ?>
 
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } //foreach
                    ?>
                </div>
                <div class="col-sm-12">
                    <div class="panel panel-default tile tile-profile">
                        <div class="panel-heading" role="tab" id="E Safety">
                            <h4 class="panel-title total-sdq">
                                <a class="" role="button" href="javascrip:voi(0)">Total High(H), Medium(M), Low(L) Concerns Identified :</a>
                                <div class="pull-right pull-left-sm col-md-8 pd-top-10">
                                    <div class="form-inline">
                                        

                                        <label class="col-md-2">H :&nbsp;
                                                <?php echo!empty($editRecord[0]['total_score_h']) ? $editRecord[0]['total_score_h'] : '0' ?></label>
                                            &nbsp;&nbsp;
                                        <label class="col-md-2">M :&nbsp;<?php echo!empty($editRecord[0]['total_score_m']) ? $editRecord[0]['total_score_m'] : '0' ?></label>&nbsp;&nbsp;
                                        <label class="col-md-2">L :&nbsp;<?php echo!empty($editRecord[0]['total_score_l']) ? $editRecord[0]['total_score_l'] : '0' ?> </label>&nbsp;&nbsp;
                                        <label class="col-md-2">N :&nbsp;<?php echo!empty($editRecord[0]['total_score_n']) ? $editRecord[0]['total_score_n'] : '0' ?></label>



                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>RISK MATRIX (Potential total score 205)</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <?php
                            if (!empty($editRecord)) {
                                $total_h = $editRecord[0]['total_score_h'] * 3;
                                $total_m = $editRecord[0]['total_score_m'] * 2;
                                $total_l = $editRecord[0]['total_score_l'] * 1;
                                $total_n = $editRecord[0]['total_score_n'] * 0;
                                $total_all = $total_h + $total_m + $total_l + $total_n;
                            }
                            ?>
                            <tbody>
                                <tr>
                                    <td>Total H</td>
                                    <td>X3 = <span id="total_h"><?php echo!empty($total_h) ? $total_h : ''; ?></span></td>
                                </tr>
                                <tr>
                                    <td>Total M</td>
                                    <td>X2 = <span id="total_m"><?php echo!empty($total_m) ? $total_m : ''; ?></span></td>
                                </tr>
                                <tr>
                                    <td>Total L</td>
                                    <td>X1 = <span id="total_l"><?php echo!empty($total_l) ? $total_l : ''; ?></span></td>
                                </tr>
                                <tr>
                                    <td>Total N</td>
                                    <td>X0 = <span id="total_n"><?php echo!empty($total_n) ? $total_n : ''; ?></span></td>
                                </tr>
                                <tr>
                                    <td>Total all</td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp; = <span id="total"><?php echo!empty($total_all) ? $total_all : '' ?></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-xs-12">
                    <b>Action to be taken to reduce Risk:</b> <?php echo!empty($editRecord[0]['comment']) ? $editRecord[0]['comment'] : '' ?>
                </div>
                    <div class="col-sm-12">
                        <?php if ($formAction == 'updatedata') { ?>
                        <!-- box start -->
                        <div class="col-md-12 box-zero">
                            <div class="row div-1-i">
                                <div class="colo4 col-md-12">
                                    <div class="col-md-6 green-1">
                                        <div class="text-green">
                                            0. No Known risk
                                        </div>
                                    </div>
                                    <div class="col-md-6 green-1">
                                        No history or evidence at present to indicate likelihood of risk from behaviour.
                                        No current indication of risk.
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="colo3 col-md-12">
                                    <div class="col-md-6 green-2">
                                        <div class="text-green">
                                            1. Low apparent risk
                                        </div>
                                    </div>
                                    <div class="col-md-6 green-2">
                                        No current indication of risk but young person’s history
                                        indicates possible risk from identified behaviour.
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="colo2 col-md-12">
                                    <div class="col-md-6 green-3">
                                        <div class="text-green">
                                            2. Medium apparent risk
                                        </div>
                                    </div>
                                    <div class="col-md-6 green-3">
                                        Young person’s history and current behaviour indicates
                                        the presence of risk but action has already been identified to moderate risk.
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="colo col-md-12">
                                    <div class="col-md-6 green-4">
                                        <div class="text-green">
                                            3. High apparent risk
                                        </div>
                                    </div>
                                    <div class="col-md-6 green-4">
                                            The young person’s circumstances indicate that the behaviour may result in a risk of serious harm without
                                            intervention from one or more agency.
                                            The young person will commit the behaviour as soon as they are able and the risk of significant harm is considered imminent.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="graph-text col-md-3">
                                        <p>No known risk = 0</p>
                                        <p>Low risk = 1</p>
                                        <p>Med Risk = 2</p>
                                        <p>High Risk = 3</p>
                                    </div>
                                    <div class="graph-text col-md-3">
                                        <p>0-85 = Low</p>
                                        <p>86-170 = Medium</p>
                                        <p>171-255 = High</p>
                                    </div>
                                </div>
                            </div>
                            <!-- box over -->
                        <?php } ?>
                    </div>

            </div>
        </form>
    </div>
</div>