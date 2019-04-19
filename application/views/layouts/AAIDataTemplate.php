<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>NFC Tracker</title>
    <link href="<?php echo base_url(); ?>uploads/reports/assets/css/custom-style.css" rel="stylesheet" />
    <!-- GOOGLE FONTS -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,300' rel='stylesheet' type='text/css' />
    <script src="<?= base_url() ?>uploads/assets/js/jquery3.2.1.min.js"></script>
</head>
<body>
    
    <?php
            /*
              Author : Ritesh Rana
              Desc   : Call Page Content Area
              Input  : View Page Name and Bunch of array
              Output : View Page
              Date   : 13/02/2019
             */
            $this->load->view($main_content);
            ?>

            <?php
/*
  @Author : Ritesh Rana
  @Desc   : Used for the custom CSS initilization just pass array of the scripts with links
  @Input  :
  @Output :
  @Date   : 13/02/2019
 */
                if (isset($footerJs) && count($footerJs) > 0) {
                         foreach ($footerJs as $js) {?>
                                <script src="<?php echo $js; ?>" ></script>
                <?php }
        } ?>
    
</body>
</html>
