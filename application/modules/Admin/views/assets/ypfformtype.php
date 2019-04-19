<div class="nav-buttons">
            <ul class="nav nav-pills nav-justified">
                 <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "ypf") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(YPM_FORM);
                    if (!empty($formdata)) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminPocketMoney/edit/') . '/' . $formdata[0]['ypm_form_id'] ?>"><i class="fa fa-medkit"></i>PM</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminPocketMoney/add') ?>"><i class="fa fa-medkit"></i>PM</a>
                    <?php } ?>
                </li>
                <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "ycm") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(YCM_FORM);
                    if (!empty($formdata)) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminPocketMoney/editClothingMoney/') . '/' . $formdata[0]['ycm_form_id'] ?>"><i class="fa fa-medkit"></i>CM</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminPocketMoney/addClothingMoney') ?>"><i class="fa fa-medkit"></i>CM</a>
                    <?php } ?>
                </li>
            </ul>
        </div>