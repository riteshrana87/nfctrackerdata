<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord)?'updatedata':'savequedata';

?>

<div class="content-wrapper">
     <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           <?PHP if($formAction == "savequedata"){ ?><?=$this->lang->line('add_sdq_q')?><?php }else{ ?><?=$this->lang->line('edit_sdq_q')?><?php }?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url($this->type . '/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?PHP if($formAction == "savequedata"){ ?><?=$this->lang->line('add_sdq')?><?php }else{ ?><?=$this->lang->line('edit_sdq')?><?php }?></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?PHP if($formAction == "savequedata"){ ?><?=$this->lang->line('add_sdq')?><?php }else{ ?><?=$this->lang->line('edit_sdq')?><?php }?></h3>
                         <?= isset($validation) ? $validation : ''; ?>
                    </div>
                    <!-- form start -->
                    <form id="addsdq" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url($crnt_view . '/savequedata'); ?>" data-parsley-validate ="">
                        <div class="box-body">
                             <?php if(($this->session->flashdata('msg'))){ ?>
                            <div class="col-sm-12 text-center" id="div_msg">
                                <?php echo $this->session->flashdata('msg');?>
                            </div>
                            <?php } ?>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3 col-md-3 required" for="parents_role">
                                            Question
                                        </label>
                                        <div class="col-sm-8">
                                            <input class="form-control" name="que" placeholder="" type="text" value="<?=!empty($editRecord[0]['que'])?htmlentities($editRecord[0]['que']):''?>" data-parsley-required  minlength="3"  maxlength="70"/>
                                            <span id="parent_error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="que1" class="control-label col-sm-3 required">Sub Question 1</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" name="sub_que[]" placeholder="" type="text" value="<?=!empty($sub_que_data[0]['sub_que'])?htmlentities($sub_que_data[0]['sub_que']):''?>" data-parsley-required  minlength="3"  maxlength="70"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="que2" class="control-label col-sm-3 required">Sub Question 2</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" name="sub_que[]" placeholder="" type="text" value="<?=!empty($sub_que_data[1]['sub_que'])?htmlentities($sub_que_data[1]['sub_que']):''?>" data-parsley-required  minlength="3"  maxlength="70"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="que3" class="control-label col-sm-3 required">Sub Question 3</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" name="sub_que[]" placeholder="" type="text" value="<?=!empty($sub_que_data[2]['sub_que'])?htmlentities($sub_que_data[2]['sub_que']):''?>" data-parsley-required minlength="3"  maxlength="70"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="que4" class="control-label col-sm-3 required">Sub Question 4</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" name="sub_que[]" placeholder="" type="text" value="<?=!empty($sub_que_data[3]['sub_que'])?htmlentities($sub_que_data[3]['sub_que']):''?>" data-parsley-required minlength="3"  maxlength="70"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="que5" class="control-label col-sm-3 required">Sub Question 5</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" name="sub_que[]" placeholder="" type="text" value="<?=!empty($sub_que_data[4]['sub_que'])?htmlentities($sub_que_data[4]['sub_que']):''?>" data-parsley-required minlength="3"  maxlength="70"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="status">
                                            <?= $this->lang->line('role_status') ?>
                                        </label>
                                        <div class="col-sm-8">
                                            <?php
                                            $options = array('1' => lang('active'), '0' => lang('inactive'));
                                            $name = "status";
                                            $selected = 1;
                                            if ($formAction == "savequedata") {
                                                $selected = 1;
                                            } else {
                                                $selected = $editRecord[0]['status'];
                                            }
                                            echo dropdown($name, $options, $selected);
                                            ?>
                                            <span class="text-danger"><?php echo form_error('status'); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                                <div class="col-sm-12 text-center">
                                    <div class="bottom-buttons">
                                    <input name="sdq_id" type="hidden" value="<?=!empty($id)?$id:''?>" />
                                    <?php  if($formAction == "savequedata"){?>
                                        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('add')?>" />
                                    <?php }else{ ?>
                                        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('edit')?>" />
                                    <?php }?>

                                    <input type="reset" class="btn btn-info" name="reset" id="reset" value="Reset" />   
                                    <a href="<?php echo base_url().''.$this->type?>/Sdq" class="btn btn-default">Cancel</a>
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