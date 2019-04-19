<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <?php if(($this->session->flashdata('msg'))){ 
                echo $this->session->flashdata('msg');
        } ?>
        <div class="sticky-heading" id="sticky-heading">
        <h1 class="page-title">
            Documents <small>New Forest Care</small>
        </h1>
        <h1 class="page-title">
            <small>Name: </small><?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small> <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
        </h1>
      </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                    <h4>Documents Saved</h4>
                    <p>
                        Your file has been successfully uploaded 
                    </p>
                 <div class="pull-right btn-section">
                    <div class="btn-group">
                        <a href="<?=base_url('YoungPerson/index/'.$YP_details[0]['care_home'])?>" class="btn btn-default" name="submit_ppform" id="submit_ppform" value="submit" style="default"><i class="fa fa-mail-reply"></i> CARE HOME YP LIST</a>
                        <a class="btn btn-default" href="<?=base_url('YoungPerson/view/'.$yp_id); ?>">
                        <i class="fa fa-mail-reply"></i> YP Info</a>
                        
                        <a class="btn btn-default" href="<?=base_url('Documents/create/'.$yp_id); ?>">
                        Add File</a>

                        <a class="btn btn-default" href="<?=base_url('Documents/index/'.$yp_id); ?>">
                            <i class="fa fa-mail-reply"></i> Docs
                        </a>
                   </div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>