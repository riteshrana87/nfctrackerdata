<!-- main content start-->
<?php $this->method = $this->router->fetch_method(); ?>
<div id="page-wrapper">
    <div class="main-page">
        <!--<h1 class="page-title">CRISIS TEAM New <small>Forest Care</small></h1>-->
        <div class="row">
            <div class="col-xs-12">
                <h1 class="page-title">
                    Appointments <small>New Forest Care</small>
                    <div class="pull-right">
                        <div class="btn-group">
                            <a href="<?=base_url('Admin/MedicalView/index/'.$ypid); ?>" class="btn btn-default">
                                <i class="fa fa-mail-reply"></i> MEDS
                            </a>
                          
                        </div>
                    </div>
                </h1>
                 <?php if(($this->session->flashdata('msg'))){ ?>
                <div class="col-sm-12 text-center" id="div_msg">
                    <?php echo $this->session->flashdata('msg');?>
                </div>
                <?php } ?>
                <div class="clearfix"></div>
                <div class="panel panel-default tile tile-profile m-t-10">
                    <div class="panel-body min-h-310">
                        <div class="row m-b-10 dataTables_filter" id="searchForm">
                         <div class="col-lg-3 col-md-3 col-sm-6 text-left">
                            <select class='form-control chosen-select' id='professional_name' name='professional_name'>
                                        <option value=''> Professional Name </option>
                                        <?php if(!empty($mp_yp_data)) {
                                         foreach ($mp_yp_data as $select) { 
                                            ?>
                                             <option value="<?=!empty($select['mp_id'])?$select['mp_id']:''?>">
                                                 <?=!empty($select[$form_data[1]['name']])?$select[$form_data[1]['name']]:''?>
                                                 <?=!empty($select[$form_data[2]['name']])?$select[$form_data[2]['name']]:''?>
                                                 <?=!empty($select[$form_data[3]['name']])?$select[$form_data[3]['name']]:''?>
                                                  <?=!empty($select[$form_data[0]['name']])?'('.$select[$form_data[0]['name']].')':''?>
                                                   
                                             </option>
                                         <?php } } ?>
                            </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 text-left">
                                    
                                <div class="input-group dateadd" id='datepicker'>
                                    <input type="text" class="form-control" placeholder="" name="search_date" id="search_date" value="">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 text-left">
                            <div class="input-group input-append bootstrap-timepicker">
                                <input class="red form-control  addtime" required="true" name="search_time" id="search_time" placeholder="" value="" data-parsley-errors-container="#errors-containertime" type="text">
                                <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>
                            </div>

                            </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 text-right">
                                <div class="form-inline">
                                    <div class="input-group search">
                                                <div class="input-group-btn">
                                                    <button onclick="data_search('changesearch')" class="btn btn-primary"  title="<?=$this->lang->line('search')?>"><?=$this->lang->line('common_search_title')?> <i class="fa fa-search fa-x"></i>
                                                        </button>
                                                        <button class="btn btn-primary" onclick="reset_data()" title="<?=$this->lang->line('reset')?>"><?=$this->lang->line('common_reset_title')?><i class="fa fa-refresh fa-x"></i>
                                                    </button>
                                                </div>
                                    </div>


                            

                                </div>
                            </div>
                            
                        </div>
                        <div class="clearfix"></div>
                        <div class="whitebox" id="common_div">
                            <?php $this->load->view('appointment_ajaxlist'); ?>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
