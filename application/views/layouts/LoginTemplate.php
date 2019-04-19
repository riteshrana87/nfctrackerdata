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
            <?php
            /*
              Author : Ritesh rana
              Desc   : Call Head area
              Input  : Bunch of Array
              Output : All CSS and JS
              Date   : 07/03/2017
             */
            if (empty($header)) {
                $header = array();
            }
            echo Modules::run('Sidebar/defaultLogoHeader', $header);
            ?>

            <!-- Example row of columns -->
            <?php
            /*
              Author : Rupesh Jorkar(RJ)
              Desc   : Call Page Content Area
              Input  : View Page Name and Bunch of array
              Output : View Page
              Date   : 11/01/2016
             */
            $this->load->view($main_content);
            ?>
            <!-- /container -->
            <?php
            /*
              Author : Rupesh Jorkar(RJ)
              Desc   : Call Footer Area
              Input  :
              Output : Footer Area( Menu, Content)
              Date   : 18/01/2016
             */
            echo Modules::run('Sidebar/defaultFooter');
            ?>
            <!-- CORE JQUERY SCRIPTS -->
            </div>
           
            <div class="modal fade" id="new-notice" tabindex="-1" role="dialog" aria-labelledby="new-noticeModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Add New Notice</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Title</label>
                            <input type="text" class="form-control" id="recipient-name">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="control-label">Notice</label>
                            <textarea class="form-control" id="message-text" rows="5"></textarea>
                        </div>
                        <div class="form-group dropzone">
                            <div class="zone">

                                <div id="dropZ">
                                    <i class="fa fa-cloud-upload"></i>
                                    <div>Drag and drop your file here</div>
                                    <span>OR</span>
                                    <div class="selectFile">
                                        <label for="file">Select file</label>
                                        <input type="file" name="files[]" id="file">
                                    </div>
                                    <p>File size limit : 10 MB</p>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Submit</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
            
            <script src="<?= base_url() ?>uploads/assets/js/parsley.min.js"></script>
                <script src="<?= base_url() ?>uploads/custom/js/bootstrap-toggle.js"></script>
                <link href="<?= base_url() ?>uploads/custom/css/bootstrap-toggle.css" rel="stylesheet">
                    <link href="<?= base_url() ?>uploads/assets/css/parsley.css" rel="stylesheet">
    <script src="<?= base_url() ?>uploads/assets/front/js/jquery-1.11.1.min.js"></script>
    <script src="<?= base_url() ?>uploads/assets/front/js/bootstrap.js"> </script>
    <script src="<?= base_url() ?>uploads/assets/front/js/jquery.slimscroll.min.js"></script>
    <script src="<?= base_url() ?>uploads/assets/front/js/dropzone.js"></script>
    <script src="<?= base_url() ?>uploads/assets/front/js/custom.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.tile .slimScroll').slimscroll({
                height: 60,
                size: 3,
                alwaysVisible: true,
                color: '#007c34'
            });
        });

        $(document).bind('dragover', function (e) {
            var dropZone = $('.zone'),
                timeout = window.dropZoneTimeout;
            if (!timeout) {
                dropZone.addClass('in');
            } else {
                clearTimeout(timeout);
            }
            var found = false,
                node = e.target;
            do {
                if (node === dropZone[0]) {
                    found = true;
                    break;
                }
                node = node.parentNode;
            } while (node != null);
            if (found) {
                dropZone.addClass('hover');
            } else {
                dropZone.removeClass('hover');
            }
            window.dropZoneTimeout = setTimeout(function () {
                window.dropZoneTimeout = null;
                dropZone.removeClass('in hover');
            }, 100);
        });
    </script>

                        
<?php
/*
  @Author : Ritesh Rana
  @Desc   : Used for the custom CSS initilization just pass array of the scripts with links
  @Input  :
  @Output :
  @Date   : 07/03/2017
 */
                if (isset($footerJs) && count($footerJs) > 0) {
                         foreach ($footerJs as $js) {?>
                                <script src="<?php echo $js; ?>" ></script>
                <?php }} ?>



                        
                                                      
<?= $this->load->view('/Common/common', '', true); ?>
                                
                        </body>

                        </html>
