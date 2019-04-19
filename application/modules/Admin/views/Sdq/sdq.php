<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?> 

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Strengths And Difficulties Questions List
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url($this->type . '/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">SDQ</li>
        </ol>

    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">SDQ List</h3>
                        <a class="btn  pull-right btn-primary howler" title="Add New" href="<?= base_url($this->type . '/' . $this->viewname . '/add'); ?>">Add New </a>
                    </div>
                    <div class="box-body">
                        <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                            <?php if (($this->session->flashdata('msg'))) { ?>
                                <div class="col-sm-12 text-center" id="div_msg">
                                    <?php echo $this->session->flashdata('msg'); ?>
                                </div>
                            <?php } ?>
                            <div class="row">
                                <div class="col-sm-11">                                   
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div id="common_div">
                                        <?php $this->load->view('Admin/Sdq/ajaxlist'); ?> 
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
