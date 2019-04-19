<?php 

if (!empty($comment_details)) {
            foreach ($comment_details as $result) {
                if(!empty($result['comment'])){
                ?>
                <div class="col-md-12">
                    <div class="panel panel-default tile tile-profile">
                        <div class="panel-body">
                            <h2><?= !empty($result['user_type']) ? $result['user_type'] : lang('NA') ; ?>  <?= (!empty($result['created_date']) && $result['created_date'] != '0000-00-00') ? configDateTime($result['created_date']) : '' ;?> </h2>
                            <div class =""> <textarea class="form-control" readonly=""><?= !empty($result['comment']) ? $result['comment'] : lang('NA') ; ?></textarea> </div>
                        </div>
                    </div>
                </div>
            <?php } }
        }
        ?>