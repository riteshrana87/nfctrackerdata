<!DOCTYPE html>
<html>
        <head>
            <?php
            /*
              Author : Rupesh Jorkar(RJ)
              Desc   : Call Head area
              Input  : Bunch of Array
              Output : All CSS and JS
              Date   : 04/02/2016
             */
            if (empty($head)) {
                $head = array();
            }
            echo Modules::run('Sidebar/defaultHeader', $head);
            ?>
        </head>
        <body class="cbp-spmenu-push  cbp-spmenu-push-toright">
            <div class="main-content">
           <div class="sticky-header header-section ">
            <div class="header-left">
                <!--logo -->
                <div class="logo">
                    <a href="#">
                        <img class="logo" src="<?= base_url() ?>uploads/assets/front/images/logo.png" />
                    </a>
                </div>
                <!--//logo-->
                <div class="clearfix"> </div>
            </div>
            <div class="clearfix"> </div>
        </div>

           <div id="page-wrapper">
    <div class="main-page">
        <h1 class="page-title">
            <div class="pull-right">
                <div class="btn-group">
              
                </div>
            </div>
        </h1>
        <div class="row">
        	   <?php
        if (($this->session->flashdata('msg'))) {
            echo $this->session->flashdata('msg');
        }
        ?>
               <?php
        if (($this->session->flashdata('signoff_review_msg'))) {
            echo $this->session->flashdata('signoff_review_msg');
        }
        ?>   
        </div>
    </div>
</div>

            <!-- /container -->
            <div class="footer">
            <div class="row">
                <div class="col-sm-6 text-left">
                    <p> </p>
                </div>
                <div class="col-sm-6 text-right">
                    <p><!-- Development by --></p>
                </div>
            </div>
        </div>

            <!-- CORE JQUERY SCRIPTS -->
            </div>
           
    <script src="<?= base_url() ?>uploads/assets/front/js/bootstrap.js"> </script>

    <link href="<?php echo base_url('uploads/custom/css/bootstrap-dialog.css'); ?>" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url('uploads/custom/js/bootstrap-dialog-min.js'); ?>"></script>
    <script src="<?= base_url() ?>uploads/assets/front/js/custom.js"></script>
    <script src="<?= base_url() ?>uploads/assets/js/moment.js"></script>
    
</body>
</html>
