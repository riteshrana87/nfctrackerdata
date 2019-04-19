<?php
/*
  @Description : Module Add/edit
  @Author      : Ritesh Rana
  @Date        : 08-06-2017

 */
$this->type = ADMIN_SITE;
$path = $form_action_path;
?>
<script>
    var formAction = <?php echo $getFormData[0]['form_json_data'];?>;
</script>

<div class="content-wrapper">
  <section class="content-header">
    <?php $this->load->view(ADMIN_SITE . '/assets/modcformtype'); ?>
    <h1>
      Medical Inoculations 
    </h1>
  </section>
    <input type="hidden" id="editformjson" value='<?php echo json_encode($getFormData[0]["form_json_data"]);?>'>
<div class="content">    
    <div id="stage1" class="build-wrap"></div>
    <form class="render-wrap">
    </form>
    <button id="edit-form" class="de_submit">Edit Form</button>
    <form id="formbuild_data" method="post" action="<?= base_url($path) ?>">
        <input type="hidden" id="mi_form_id" name="mi_form_id" value="<?php echo $id;?>">
        <input type="hidden" id="formbuild_data_val" name="form_data">
        <input type="hidden" id="form_json_data" name="form_json_data">
        <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
        <div id="deletefields">
            
        </div>
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
      <button style="display: none" id="testSubmit" type="submit" class="de_submit">Submit</button>
      <button style="display: none" id="resetDemo" type="button">Reset Demo</button>
    </div>
  </div>

</div><!-- /.content-wrapper -->
