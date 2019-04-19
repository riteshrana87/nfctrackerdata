<div class="nav-buttons formtype">
    <ul class="nav nav-pills nav-justified">
        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "pp") { ?>class="active"<?php } ?>>
            <?php
            $formdata = checkFormBuilderData(PP_FORM);
            if (!empty($formdata) && !empty($formdata[0]['pp_form_id'])) {
                ?>
                <a href="<?= base_url($this->type . '/AdminPlacementPlan/edit/') . '/' . $formdata[0]['pp_form_id'] ?>"><i class="fa fa-file-text"></i>PP</a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/AdminPlacementPlan/add') ?>"><i class="fa fa-file-text"></i>PP</a>
            <?php } ?>
        </li>
        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "ibp") { ?>class="active"<?php } ?>>
            <?php
            $formdata = checkFormBuilderData(IBP_FORM);
            if (!empty($formdata) && !empty($formdata[0]['ibp_form_id'])) {
                ?>
                <a href="<?= base_url($this->type . '/AdminIndividualBehaviourPlan/edit/') . '/' . $formdata[0]['ibp_form_id'] ?>"><i class="fa fa-file-text"></i>IBP</a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/AdminIndividualBehaviourPlan/add') ?>"><i class="fa fa-file-text"></i>IBP</a>
            <?php } ?>
        </li>

        <?php /* ?>
        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "ra") { ?>class="active"<?php } ?>>
            <?php 
            $formdata = checkFormBuilderData(RA_FORM);
            if (!empty($formdata) && !empty($formdata[0]['ra_form_id'])) {
                ?>
                <a href="<?= base_url($this->type . '/AdminRiskAssessment/edit/') . '/' . $formdata[0]['ra_form_id'] ?>"><i class="fa fa-file-text"></i>RA</a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/AdminRiskAssessment/add') ?>"><i class="fa fa-file-text"></i>RA</a>
            <?php } ?>
        </li>
        <?php */ ?>
        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "do") { ?>class="active"<?php } ?>>
            <?php
            $formdata = checkFormBuilderData(DO_FORM);
            if (!empty($formdata) && !empty($formdata[0]['do_form_id'])) {
                ?>
                <a href="<?= base_url($this->type . '/AdminDailyObservations/edit/') . '/' . $formdata[0]['do_form_id'] ?>"><i class="fa fa-file-text"></i>DO</a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/AdminDailyObservations/add') ?>"><i class="fa fa-file-text"></i>DO</a>
            <?php } ?>
        </li>
        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "ks") { ?>class="active"<?php } ?>>
            <?php
            $formdata = checkFormBuilderData(KS_FORM);
            if (!empty($formdata) && !empty($formdata[0]['ks_form_id'])) {
                ?>
                <a href="<?= base_url($this->type . '/AdminKeySession/edit/') . '/' . $formdata[0]['ks_form_id'] ?>"><i class="fa fa-file-text"></i>KS</a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/AdminKeySession/add') ?>"><i class="fa fa-file-text"></i>KS</a>
            <?php } ?>
        </li>
        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "docs") { ?>class="active"<?php } ?>>
            <?php
            $formdata = checkFormBuilderData(DOCS_FORM);
            if (!empty($formdata) && !empty($formdata[0]['docs_form_id'])) {
                ?>
                <a href="<?= base_url($this->type . '/AdminDocuments/edit/') . '/' . $formdata[0]['docs_form_id'] ?>"><i class="fa fa-file-text"></i>DOCS</a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/AdminDocuments/add') ?>"><i class="fa fa-file-text"></i>DOCS</a>
            <?php } ?>
        </li>
        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "mac") { ?>class="active"<?php } ?>>
            <?php
            $formdata = checkFormBuilderData(MAC_FORM);
            if (!empty($formdata)) {
                ?>
                <a href="<?= base_url($this->type . '/AdminMedicalInformation/editAuthorisations/') . '/' . $formdata[0]['mac_form_id'] ?>"><i class="fa fa-medkit"></i>MEDC</a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/AdminMedicalInformation/addAuthorisations') ?>"><i class="fa fa-medkit"></i>MODC</a>
            <?php } ?>
        </li>
        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "coms") { ?>class="active"<?php } ?>>
            <?php
            $formdata = checkFormBuilderData(COMS_FORM);
            if (!empty($formdata)) {
                ?>
                <a href="<?= base_url($this->type . '/AdminCommunication/edit/') . '/' . $formdata[0]['coms_form_id'] ?>"><i class="fa fa-medkit"></i>COMS</a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/AdminCommunication/add') ?>"><i class="fa fa-medkit"></i>COMS</a>
            <?php } ?>
        </li>
        <?php /* ?>
        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "is") { ?>class="active"<?php } ?>>
            <?php
            $formdata = checkFormBuilderData(IS_FORM);
            if (!empty($formdata) && !empty($formdata[0]['is_form_id'])) {
                ?>
                <a href="<?= base_url($this->type . '/AdminIndividualStrategies/edit/') . '/' . $formdata[0]['is_form_id'] ?>"><i class="fa fa-file-text"></i>IS
              </a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/AdminIndividualStrategies/add') ?>"><i class="fa fa-file-text"></i>IS</a>
            <?php } ?>
        </li>
        <?php */   ?>
        
        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "wr") { ?>class="active"<?php } ?>>
            <?php
            $formdata = checkFormBuilderData(WR_FORM);
            if (!empty($formdata) && !empty($formdata[0]['wr_form_id'])) {
                ?>
                <a href="<?= base_url($this->type . '/WeeklyReportView/edit/') . '/' . $formdata[0]['wr_form_id'] ?>"><i class="fa fa-file-text"></i>WR</a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/WeeklyReportView/add') ?>"><i class="fa fa-file-text"></i>WR</a>
            <?php } ?>
        </li>
        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "ypc") { ?>class="active"<?php } ?>>
            <?php
            $formdata = checkFormBuilderData(YPC_FORM);
            if (!empty($formdata) && !empty($formdata[0]['ypc_form_id'])) {
                ?>
                <a href="<?= base_url($this->type . '/AdminConcerns/edit/') . '/' . $formdata[0]['ypc_form_id'] ?>"><i class="fa fa-file-text"></i>YPC</a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/AdminConcerns/add') ?>"><i class="fa fa-file-text"></i>YPC</a>
            <?php } ?>
        </li>
         <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "mdt") { ?>class="active"<?php } ?>>
            <?php
            $formdata = checkFormBuilderData(MDT_FORM);
            if (!empty($formdata) && !empty($formdata[0]['mdt_form_id'])) {
                ?>
                <a href="<?= base_url($this->type . '/AdminMDTReport/edit/') . '/' . $formdata[0]['mdt_form_id'] ?>"><i class="fa fa-file-text"></i>MDT</a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/AdminMDTReport/add') ?>"><i class="fa fa-file-text"></i>MDT</a>
            <?php } ?>
        </li>
        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "ypf") { ?>class="active"<?php } ?>>
            <?php
            $formdata = checkFormBuilderData(YPM_FORM);
            if (!empty($formdata) && !empty($formdata[0]['ypm_form_id'])) {
                ?>
                <a href="<?= base_url($this->type . '/AdminPocketMoney/edit/') . '/' . $formdata[0]['ypm_form_id'] ?>"><i class="fa fa-file-text"></i>YPF</a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/AdminPocketMoney/add') ?>"><i class="fa fa-file-text"></i>YPF</a>
            <?php } ?>
        </li>
        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "lr") { ?>class="active"<?php } ?>>
            <?php
            $formdata = checkFormBuilderData(LR_FORM);
            if (!empty($formdata) && !empty($formdata[0]['lr_form_id'])) {
                ?>
                <a href="<?= base_url($this->type . '/AdminLocationRegister/edit/') . '/' . $formdata[0]['lr_form_id'] ?>"><i class="fa fa-file-text"></i>LR</a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/AdminLocationRegister/add') ?>"><i class="fa fa-file-text"></i>LR</a>
            <?php } ?>
        </li>
        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "rmp") { ?>class="active"<?php } ?>>
            <?php
            $formdata = checkFormBuilderData(RMP_FORM);
            if (!empty($formdata) && !empty($formdata[0]['rmp_form_id'])) {
                ?>
                <a href="<?= base_url($this->type . '/AdminRMP/edit/') . '/' . $formdata[0]['rmp_form_id'] ?>"><i class="fa fa-file-text"></i>RMP</a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/AdminRMP/add') ?>"><i class="fa fa-file-text"></i>RMP</a>
            <?php } ?>
        </li>
        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "cpt") { ?>class="active"<?php } ?>>
            <?php
            $formdata = checkFormBuilderData(CPT_FORM);
            if (!empty($formdata) && !empty($formdata[0]['cpt_form_id'])) {
                ?>
                <a href="<?= base_url($this->type . '/AdminCarePlanTarget/edit/') . '/' . $formdata[0]['cpt_form_id'] ?>"><i class="fa fa-file-text"></i>CPT</a>
            <?php } else { ?>
            <a href="<?= base_url($this->type . '/AdminCarePlanTarget/add') ?>"><i class="fa fa-file-text"></i>CPT</a>
            <?php } ?>
        </li>
        
        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "aai") { ?>class="active"<?php } ?>>
            <?php 
            $formdata = checkFormBuilderData(AAI_FORM);
            if (!empty($formdata) && !empty($formdata[0]['form_id'])) {
                ?>
                <a href="<?= base_url($this->type . '/AdminAAI/edit/') . '/' . $formdata[0]['form_id'] ?>"><i class="fa fa-file-text"></i>AAI</a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/AdminAAI/add') ?>"><i class="fa fa-file-text"></i>AAI</a>
            <?php } ?>
        </li>
        
    </ul>
</div>