<div class="nav-buttons">
            <ul class="nav nav-pills nav-justified">
                 <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "mac") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(MAC_FORM);
                    if (!empty($formdata)) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminMedicalInformation/editAuthorisations/') . '/' . $formdata[0]['mac_form_id'] ?>"><i class="fa fa-medkit"></i>MAC</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminMedicalInformation/addAuthorisations') ?>"><i class="fa fa-medkit"></i>MAC</a>
                    <?php } ?>
                </li>
                <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "mp") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(MP_FORM);
                    if (!empty($formdata)) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminMedicalInformation/editProfessionals/') . '/' . $formdata[0]['mp_form_id'] ?>"><i class="fa fa-medkit"></i>MP</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminMedicalInformation/addProfessionals') ?>"><i class="fa fa-medkit"></i>MP</a>
                    <?php } ?>
                </li>
                <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "omi") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(OMI_FORM);
                    if (!empty($formdata)) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminMedicalInformation/editOtherInformation/') . '/' . $formdata[0]['omi_form_id'] ?>"><i class="fa fa-medkit"></i>OMI</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminMedicalInformation/addOtherInformation') ?>"><i class="fa fa-medkit"></i>OMI</a>
                    <?php } ?>
                </li>
                <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "ino") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(MI_FORM);
                    if (!empty($formdata)) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminMedicalInformation/editInoculations/') . '/' . $formdata[0]['mi_form_id'] ?>"><i class="fa fa-medkit"></i>MI</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminMedicalInformation/addInoculations') ?>"><i class="fa fa-medkit"></i>MI</a>
                    <?php } ?>
                </li>
                
                <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "mc") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(MC_FORM);
                    if (!empty($formdata)) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminMedicalInformation/editCommunication/') . '/' . $formdata[0]['mc_form_id'] ?>"><i class="fa fa-medkit"></i>MC</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminMedicalInformation/addCommunication') ?>"><i class="fa fa-medkit"></i>MC</a>
                    <?php } ?>
                </li>
                 <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "mm") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(M_FORM);
                    if (!empty($formdata)) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminMedicalInformation/editMedication/') . '/' . $formdata[0]['m_form_id'] ?>"><i class="fa fa-medkit"></i>M</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminMedicalInformation/addMedication') ?>"><i class="fa fa-medkit"></i>M</a>
                    <?php } ?>
                </li>
                <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "am") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(AM_FORM);
                    if (!empty($formdata)) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminMedicalInformation/editAdministerMedication/') . '/' . $formdata[0]['am_form_id'] ?>"><i class="fa fa-medkit"></i>AM</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminMedicalInformation/addAdministerMedication') ?>"><i class="fa fa-medkit"></i>AM</a>
                    <?php } ?>
                </li>
                <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "ha") { ?>class="active"<?php } ?>>
                    <?php
                    $formdata = checkFormBuilderData(HA_FORM);
                    if (!empty($formdata)) {
                        ?>
                        <a href="<?= base_url($this->type . '/AdminMedicalInformation/editHealthAssessment/') . '/' . $formdata[0]['ha_form_id'] ?>"><i class="fa fa-medkit"></i>HA</a>
                    <?php } else { ?>
                        <a href="<?= base_url($this->type . '/AdminMedicalInformation/addHealthAssessment') ?>"><i class="fa fa-medkit"></i>HA</a>
                    <?php } ?>
                </li>
            </ul>
        </div>