<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord)?'updatedata?id='.$id:'insertdata';
$path = $crnt_view.'/'.$formAction;

?>
<script>
    var url ='<?php echo base_url($path); ?>';
</script>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
          Manage Pocket Money Saving
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url($this->type . '/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Manage Pocket Money Saving</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Manage Pocket Money Saving</h3>
                        <a class="btn btn-primary pull-right" onclick="history.go(-1)" href="javascript:void(0)">Back</a> 
                        
                         <?= isset($validation) ? $validation : ''; ?>
                    </div>
                    <?php  echo $this->session->flashdata('verify_msg'); ?>
                    <!-- form start -->
               <form id="addrole" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate ="">
                        
                        <div class="box-body">
                          <div class="row">
                            <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-md-3 required" for="parents_role">
                                    Young Person List
                                </label>
                                <div class="col-sm-8">
                                     <select class="chosen-select form-control" data-parsley-errors-container="#parent_error" placeholder="Parents Role"  name="yp_id" id="yp_id" required="">
                                            <option value="">Select YP</option>
                                            <?php foreach($YP_details as $rows){
                                                $totalBlance = !empty($rows['saving_balance'])?$rows['saving_balance']:'0';
                                                ?>
                                                    <option value="<?php echo $rows['yp_id'];?>"><?php echo $rows['yp_fname'].' '.$rows['yp_lname'].'(Saving Bal : '.$totalBlance.')';?></option>
                                                <?php }?>
                                        </select>
                                   <span id="parent_error"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-md-3 required" for="parents_role">
                                    Add/Subtract Saving Money
                                </label>
                                <div class="col-sm-8">
                                     <select class="chosen-select form-control" data-parsley-errors-container="#parent_error" placeholder="Select Option"  name="add_subtract" id="add_subtract" required="">
                                            <option value="Add">Add</option>
                                            <option value="Subtract">Subtract</option>
                                    </select>
                                   <span id="parent_error"></span>
                                </div>
                            </div>
                            <div class="form-group">
                           <label for="cat_name" class="control-label col-sm-3 required">Money</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control   addtime_data" name="money_in" id="money_in" min="1" step=".01" placeholder="" value="" required="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3 required" for="status">
                                    Reason
                                </label>
                                <div class="col-sm-8">
                                    <textarea class="form-control " name="reason" placeholder="" id="reason" required="">Saving Pocket Money By Admin</textarea>
                                </div>
                            </div>

                        </div>
                        
                        <div class="clearfix"></div>
                            <div class="col-sm-12 text-center">
                                <div class="bottom-buttons">
                                   
                                    <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
                                    <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="Submit" />
                                    <!-- <input type="reset" class="btn btn-info" name="reset" id="reset" value="Reset" />    -->
                                    <a href="<?php echo base_url().''.$this->type?>/Rolemaster" class="btn btn-default">Cancel</a>
                                </div>
                            </div>
                          </div>
                        </div><!-- /.box-body -->
                    </form>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
