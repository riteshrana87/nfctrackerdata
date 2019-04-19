<?php
$this->type = ADMIN_SITE;
$path = $form_action_path;
?>
<div class="content-wrapper">
    <section class="content-header">
         <?php $this->load->view(ADMIN_SITE . '/assets/aaiformtype'); ?>
        <h1>
            L8
        </h1>
    </section>
    <div class="content">

        <div id="stage1" class="build-wrap"></div>
        <form class="render-wrap">
        </form>
        <button id="edit-form">Edit Form</button>
        <form id="formbuild_data" method="post" action="<?= base_url($path) ?>">
            <input type="hidden" id="formbuild_data_val" name="form_data">
            <input type="hidden" id="form_json_data" name="form_json_data">
            <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">

        </form>
        <div class="action-buttons">
            <button style="display: none" id="showData" type="button">Show Data</button>
            <button style="display: none" id="clearFields" type="button">Clear All Fields</button>
            <button style="display: none" id="getData" type="button">Get Data</button>
            <button style="display: none" id="getXML" type="button">Get XML Data</button>
            <button style="display: none" id="getJSON" type="button">Get JSON Data</button>
            <button style="display: none" id="getJS" type="button">Get JS Data</button>
            <button style="display: none" id="setData" type="button">Set Data</button>
            <button style="display: none" id="addField" type="button">Add Field</button>
            <button style="display: none" id="removeField" type="button">Remove Field</button>
            <button style="display: none" id="testSubmit" type="submit">Submit</button>
            <button style="display: none" id="resetDemo" type="button">Reset Demo</button>

        </div>
    </div>

</div><!-- /.content-wrapper -->
