<?php
    $this->load->view(ADMIN_SITE.'/assets/header');
    $this->load->view(ADMIN_SITE.'/assets/left');	
    ?>
    <div class="content-wrapper">
    <?php
    $this->load->view($main_content);
    ?>
    </div>
    <?php
    $this->load->view(ADMIN_SITE.'/assets/footer');     
?>