<div class="nav-buttons">
            <ul class="nav nav-pills nav-justified">
                <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "food") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(FOOD_FORM);
                    if (!empty($formdata) && !empty($formdata[0]['food_form_id'])) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminDailyObservations/editFoodConsumed/') . '/' . $formdata[0]['food_form_id'] ?>"><i class="fa fa-file-text"></i> Food Consumed</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminDailyObservations/addFoodConsumed') ?>"><i class="fa fa-file-text"></i> Food Consumed</a>
                    <?php } ?>
                </li>
                <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "do") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(DO_FORM);
                    if (!empty($formdata) && !empty($formdata[0]['do_form_id'])) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminDailyObservations/edit/') . '/' . $formdata[0]['do_form_id'] ?>"><i class="fa fa-file-text"></i> Summaries</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminDailyObservations/add') ?>"><i class="fa fa-file-text"></i> Summaries</a>
                    <?php } ?>
                </li>
                 
            </ul>
        </div>