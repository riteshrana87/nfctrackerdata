<div class="nav-buttons">
            <ul class="nav nav-pills nav-justified">                
                <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "entry_form") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(AAI_FORM);
                    if (!empty($formdata) && !empty($formdata[0]['form_id'])) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/edit/') . '/' . $formdata[0]['form_id'] ?>"><i class="fa fa-file-text"></i> Main Entry Form</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/add') ?>"><i class="fa fa-file-text"></i> Main Entry Form</a>
                    <?php } ?>
                </li>
<!--                <li <?php /*if (isset($button_header['menu_module']) && $button_header['menu_module'] == "incident_place") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(AAI_FORM);
                    if (!empty($formdata) && !empty($formdata[1]['form_id'])) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/editIncidentPlace/') . '/' . $formdata[1]['form_id'] ?>"><i class="fa fa-file-text"></i> When Incident Took Place</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/addIncidentPlace') ?>"><i class="fa fa-file-text"></i> When Incident Took Place</a>
                    <?php }*/ ?>
                </li>-->
                <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "l1") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(AAI_FORM);
                    if (!empty($formdata) && !empty($formdata[2]['form_id'])) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/editL1/') . '/' . $formdata[2]['form_id'] ?>"><i class="fa fa-file-text"></i> L1</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/addL1') ?>"><i class="fa fa-file-text"></i> L1</a>
                    <?php } ?>
                </li>
                <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "L2nL3") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(AAI_FORM);
                    if (!empty($formdata) && !empty($formdata[3]['form_id'])) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/editL2nL3/') . '/' . $formdata[3]['form_id'] ?>"><i class="fa fa-file-text"></i> L2 & L3</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/addL2nL3') ?>"><i class="fa fa-file-text"></i> L2 & L3</a>
                    <?php } ?>
                </li>
                <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "l4") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(AAI_FORM);
                    if (!empty($formdata) && !empty($formdata[4]['form_id'])) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/editL4/') . '/' . $formdata[4]['form_id'] ?>"><i class="fa fa-file-text"></i> L4</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/addL4') ?>"><i class="fa fa-file-text"></i> L4</a>
                    <?php } ?>
                </li>
				
		<li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "l5") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(AAI_FORM);
                    if (!empty($formdata) && !empty($formdata[5]['form_id'])) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/editL5/') . '/' . $formdata[5]['form_id'] ?>"><i class="fa fa-file-text"></i> L5</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/addL5') ?>"><i class="fa fa-file-text"></i> L5</a>
                    <?php } ?>
                </li>
				
		<li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "l6") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(AAI_FORM);
                    if (!empty($formdata) && !empty($formdata[6]['form_id'])) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/editL6/') . '/' . $formdata[6]['form_id'] ?>"><i class="fa fa-file-text"></i> L6</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/addL6') ?>"><i class="fa fa-file-text"></i> L6</a>
                    <?php } ?>
                </li>
				
		<li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "L7") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(AAI_FORM);
                    if (!empty($formdata) && !empty($formdata[7]['form_id'])) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/editL7/') . '/' . $formdata[7]['form_id'] ?>"><i class="fa fa-file-text"></i> L7</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/addL7') ?>"><i class="fa fa-file-text"></i> L7</a>
                    <?php } ?>
                </li>
					<?/*nikunj ghelani 29-1-19 for l8 form admin side*/?>
				<li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "L8") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(AAI_FORM);
                    if (!empty($formdata) && !empty($formdata[8]['form_id'])) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/editL8/') . '/' . $formdata[8]['form_id'] ?>"><i class="fa fa-file-text"></i> L8</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/addL8') ?>"><i class="fa fa-file-text"></i> L8</a>
                    <?php } ?>
                </li>
				<?/*nikunj ghelani 31-1-19 for l8 form admin side*/?>
				<li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "L9") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(AAI_FORM);
                    if (!empty($formdata) && !empty($formdata[9]['form_id'])) { 
                        ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/editL9/') . '/' . $formdata[9]['form_id'] ?>"><i class="fa fa-file-text"></i> L9</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/addL9') ?>"><i class="fa fa-file-text"></i> L9</a>
                    <?php } ?>
                </li>

                <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "sanctions") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(SANCTION_FORM);
                    if (!empty($formdata) && !empty($formdata[0]['sanction_form_id'])) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/sanctionEdit/') . '/' . $formdata[0]['sanction_form_id'] ?>"><i class="fa fa-file-text"></i> Sanctions</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/sanctionAdd') ?>"><i class="fa fa-file-text"></i> Sanctions</a>
                    <?php } ?>
                </li>
                <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "review") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(AAI_FORM);
                    if (!empty($formdata) && !empty($formdata[10]['form_id'])) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/reviewEdit/') . '/' . $formdata[10]['form_id'] ?>"><i class="fa fa-file-text"></i> Review</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/reviewadd') ?>"><i class="fa fa-file-text"></i> Review</a>
                    <?php } ?>
                </li>

                <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "manager") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(AAI_FORM);
                    if (!empty($formdata) && !empty($formdata[11]['form_id'])) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/managerEdit/') . '/' . $formdata[11]['form_id'] ?>"><i class="fa fa-file-text"></i>Manager Review</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminAAI/manageradd') ?>"><i class="fa fa-file-text"></i>Manager Review</a>
                    <?php } ?>
                </li>
            </ul>
        </div>