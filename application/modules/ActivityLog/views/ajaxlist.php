<?php $this->method = $this->router->fetch_method();  ?>
<div class="row">
   <div class="col-lg-12">
        <div class="panel panel-default tile tile-profile bordet-0">		
            <div class="panel-body">
                <h2>AUDIT LOG</h2>
              <div class="table-responsive">			  
                <table class="table audit_table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>User Name</th>
                      <th>Date & Time</th>
                      <th>Action</th>
                      <th>Module Name</th>
                      <th>YP Name</th>
                      <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                      <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /> 				
                      <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?= !empty($uri_segment) ? $uri_segment :   '0' ?>">
                    </tr>
                  </thead>
                  <tbody>
                <?php if(!empty($information)){ 
                    foreach ($information as $row) {
                ?>
                    <tr>
                      <td>
                        <?=!empty($row['user_name'])?$row['user_name']:''?>
                      </td>
                      <td>
                        <?=!empty($row['activity_date'])?configDateTimeFormat($row['activity_date']):''?>
                      </td>
                      <td>
                        <?=($row['type'] == 1)?'Added':(($row['type'] == 2)?'Updated':'Deleted')?>
                      </td>
                      <td>
                        <?=!empty($row['module_name'])?$row['module_name']:''?>  <?=!empty($row['module_field_name'])?' - '.$row['module_field_name']:''?>
                      </td>
                      <td>
                        <?=!empty($row['yp_name'])?' YP('.$row['yp_name'].')':''?>
                      </td>
                    </tr>
                <!--<ul class="media-list media-xs">
                    <li class="media">
                        <div class="media-body">
                            <p>
                                <strong><?=!empty($row['user_name'])?$row['user_name']:''?></strong>
                                <small class="date"><?=!empty($row['activity_date'])?configDateTimeFormat($row['activity_date']):''?></small>
                            </p>
                            <p>
                            <strong><?=($row['type'] == 1)?'Added':(($row['type'] == 2)?'Updated':'Deleted')?> </strong> <?=!empty($row['yp_name'])?' YP('.$row['yp_name'].')':''?>: <?=!empty($row['module_name'])?$row['module_name']:''?>  <?=!empty($row['module_field_name'])?' - '.$row['module_field_name']:''?>
                          </p>
                        </div>
                    </li>
                </ul>-->
                <?php } } else { ?>
                        <tr>
                            <td colspan="6" class="text-center"><?= lang('common_no_record_found') ?></td>
                        </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="" id="common_tb_audit">
        <?php if (isset($pagination) && !empty($pagination)) { ?>
            <div class="col-sm-12">
                <?php echo $pagination; ?>
            </div>
        <?php } ?>
    </div>
</div>