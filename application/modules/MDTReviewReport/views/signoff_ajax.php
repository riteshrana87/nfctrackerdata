<input type="hidden" name="user_type" value="<?=!empty($user_type)?$user_type:''?>">
<?php if(!empty($user_type) && $user_type == 'other'){ ?>
<div class="form-group">
    <label for="recipient-name" class="control-label">First Name <span class="astrick">*</span></label>
    <input class="form-control" name="fname" placeholder="Enter First Name" type="text" value="<?php echo set_value('fname', (!empty($editRecord[0]['fname'])) ? $editRecord[0]['fname'] : '') ?>" required="" data-parsley-pattern="^[a-zA-Z ]+" data-parsley-pattern-message="Please enter only alphabets." minlength="2" maxlength="25" data-parsley-trigger="keyup" />
</div>
<div class="form-group">
    <label for="recipient-name" class="control-label">Last Name <span class="astrick">*</span></label>
    <input class="form-control" min-length="2" name="lname" placeholder="Enter Surname" type="text" value="<?= !empty($editRecord[0]['lname']) ? $editRecord[0]['lname'] : '' ?>" required="" data-parsley-pattern="^[a-zA-Z ]+" data-parsley-pattern-message="Please enter only alphabets." minlength="2" maxlength="25" data-parsley-trigger="keyup" />
</div>

<div class="form-group">

    <label for="recipient-name" class="control-label">Email <span class="astrick">*</span></label>

    <input class="form-control" value="<?= !empty($editRecord[0]['email_id']) ? $editRecord[0]['email_id'] : '' ?>" id="email" name="email" autocomplete="false" placeholder="Enter email id"  required="" data-parsley-required-message="Please enter email id." type="email" value="" />
</div>
<?php } else if(!empty($user_type) && $user_type == 'parent_data'){ ?>

<div class="row">
    <div class="col-xs-4 col-sm-5 p-l-0">
        <label><small>First Name</small></label>
    </div>
    <div class="col-xs-8 col-sm-7">
        <p><?= !empty($parent_data[0]['firstname']) ? $parent_data[0]['firstname'] : lang('NA') ?></p>
    </div>
</div>
<div class="row">
    <div class="col-xs-4 col-sm-5 p-l-0">
        <label><small>Last Name</small></label>
    </div>
    <div class="col-xs-8 col-sm-7">
        <p><?= !empty($parent_data[0]['surname']) ? $parent_data[0]['surname'] : lang('NA') ?></p>
    </div>
</div>
<div class="row">
    <div class="col-xs-4 col-sm-5 p-l-0">
        <label><small>Email Address</small></label>
    </div>
    <div class="col-xs-8 col-sm-7">
        <p><?= !empty($parent_data[0]['email_address']) ? $parent_data[0]['email_address'] : lang('NA') ?></p>
    </div>
</div>
<div class="row">
    <div class="col-xs-4 col-sm-5 p-l-0">
        <label><small>Relationship</small></label>
    </div>
    <div class="col-xs-8 col-sm-7">
        <p><?= !empty($parent_data[0]['relationship']) ? $parent_data[0]['relationship'] : lang('NA') ?></p>
    </div>
</div>
<div class="row">
    <div class="col-xs-4 col-sm-5 p-l-0">
        <label><small>Address</small></label>
    </div>
    <div class="col-xs-8 col-sm-7">
        <p><?= !empty($parent_data[0]['address']) ? $parent_data[0]['address'] : lang('NA') ?></p>
    </div>
</div>
<div class="row">
    <div class="col-xs-4 col-sm-5 p-l-0">
        <label><small>Contact Number</small></label>
    </div>
    <div class="col-xs-8 col-sm-7">
        <p><?= !empty($parent_data[0]['contact_number']) ? $parent_data[0]['contact_number'] : lang('NA') ?></p>
    </div>
</div>
<div class="row">
    <div class="col-xs-4 col-sm-5 p-l-0">
        <label><small>YP Authorised Communication</small></label>
    </div>
    <div class="col-xs-8 col-sm-7">
        <p><?= !empty($parent_data[0]['yp_authorised_communication']) ? $parent_data[0]['yp_authorised_communication'] : lang('NA') ?> </p>
    </div>
</div>
<div class="row">
    <div class="col-xs-4 col-sm-5 p-l-0">
        <label><small>Cares`s Authorised Communication</small></label>
    </div>
    <div class="col-xs-8 col-sm-7">
        <p><?= !empty($parent_data[0]['carer_authorised_communication']) ? $parent_data[0]['carer_authorised_communication'] : lang('NA') ?> </p>
    </div>
</div>
<div class="row">
    <div class="col-xs-4 col-sm-5 p-l-0">
        <label><small>Comments</small></label>
    </div>
    <div class="col-xs-8 col-sm-7">
        <p><?= !empty($parent_data[0]['comments']) ? $parent_data[0]['comments'] : lang('NA') ?></p>
    </div>
</div>
<div class="form-group">
    <input class="form-control" name="fname" placeholder="Enter First Name" type="hidden" value="<?php echo set_value('firstname', (!empty($parent_data[0]['firstname'])) ? $parent_data[0]['firstname'] : '') ?>" required="" data-parsley-pattern="^[a-zA-Z ]+" data-parsley-pattern-message="Please enter only alphabets." minlength="2" maxlength="25" data-parsley-trigger="keyup" />
</div>
<div class="form-group">
    <input class="form-control" min-length="2" name="lname" placeholder="Enter Surname" type="hidden" value="<?= !empty($parent_data[0]['surname']) ? $parent_data[0]['surname'] : '' ?>" required="" data-parsley-pattern="^[a-zA-Z ]+" data-parsley-pattern-message="Please enter only alphabets." minlength="2" maxlength="25" data-parsley-trigger="keyup" />
</div>

<div class="form-group">


    <input class="form-control" value="<?= !empty($parent_data[0]['email_address']) ? $parent_data[0]['email_address'] : '' ?>" id="email" name="email" autocomplete="false" placeholder="Enter email id"  required="" data-parsley-required-message="Please enter email id." type="hidden" value="" />
</div>
<?php }else if(!empty($user_type) && $user_type == 'social_data'){
    ?>
 
<div class="row">
    <div class="col-xs-4 col-sm-5 p-l-0">
        <label><small>Social Worker First Name</small></label>
    </div>
    <div class="col-xs-8 col-sm-7">
        <p><?= !empty($social_worker_data[0]['social_worker_firstname']) ? $social_worker_data[0]['social_worker_firstname'] : lang('NA') ?></p>
    </div>
</div>
<div class="row">
    <div class="col-xs-4 col-sm-5 p-l-0">
        <label><small>Social Worker Last Name</small></label>
    </div>
    <div class="col-xs-8 col-sm-7">
        <p> <?= !empty($social_worker_data[0]['social_worker_surname']) ? $social_worker_data[0]['social_worker_surname'] : lang('NA') ?></p>
    </div>
</div>
<div class="row">
    <div class="col-xs-4 col-sm-5 p-l-0">
        <label><small>Email</small></label>
    </div>
    <div class="col-xs-8 col-sm-7">
        <p><?= !empty($social_worker_data[0]['email']) ? $social_worker_data[0]['email'] : lang('NA') ?></p>
    </div>
</div>
<div class="row">
    <div class="col-xs-4 col-sm-5 p-l-0">
        <label><small>Mobile</small></label>
    </div>
    <div class="col-xs-8 col-sm-7">
        <p><?= !empty($social_worker_data[0]['mobile']) ? $social_worker_data[0]['mobile'] : lang('NA') ?></p>
    </div>
</div>
<div class="row">
    <div class="col-xs-4 col-sm-5 p-l-0">
        <label><small>Landline</small></label>
    </div>
    <div class="col-xs-8 col-sm-7">
        <p><?= !empty($social_worker_data[0]['landline']) ? $social_worker_data[0]['landline'] : lang('NA') ?></p>
    </div>
</div>
<div class="row">
    <div class="col-xs-4 col-sm-5 p-l-0">
        <label><small>Other</small></label>
    </div>
    <div class="col-xs-8 col-sm-7">
        <p><?= !empty($social_worker_data[0]['other']) ? $social_worker_data[0]['other'] : lang('NA') ?></p>
    </div>
</div>

<div class="row">
    <div class="col-xs-4 col-sm-5 p-l-0">
        <label><small>Senior SW</small></label>
    </div>
    <div class="col-xs-8 col-sm-7">
        <p><?= !empty($social_worker_data[0]['senior_social_worker_firstname']) ? $social_worker_data[0]['senior_social_worker_firstname'] : '' ?> <?= !empty($social_worker_data[0]['senior_social_worker_surname']) ? $social_worker_data[0]['senior_social_worker_surname'] : lang('NA') ?> </p>
    </div>
</div>
<div class="form-group">
    <input class="form-control" name="fname" placeholder="Enter First Name" type="hidden" value="<?php echo set_value('social_worker_firstname', (!empty($social_worker_data[0]['social_worker_firstname'])) ? $social_worker_data[0]['social_worker_firstname'] : '') ?>" required="" data-parsley-pattern="^[a-zA-Z ]+" data-parsley-pattern-message="Please enter only alphabets." minlength="2" maxlength="25" data-parsley-trigger="keyup" />
</div>
<div class="form-group">
    <input class="form-control" min-length="2" name="lname" placeholder="Enter Surname" type="hidden" value="<?= !empty($social_worker_data[0]['social_worker_surname']) ? $social_worker_data[0]['social_worker_surname'] : '' ?>" required="" data-parsley-pattern="^[a-zA-Z ]+" data-parsley-pattern-message="Please enter only alphabets." minlength="2" maxlength="25" data-parsley-trigger="keyup" />
</div>

<div class="form-group">

    
    <input class="form-control" value="<?= !empty($social_worker_data[0]['email']) ? $social_worker_data[0]['email'] : '' ?>" id="email" name="email" autocomplete="false" placeholder="Enter email id"  required="" data-parsley-required-message="Please enter email id." type="hidden" value="" />
</div>
<?php }else if(!empty($user_type) && $user_type == 'parent'){
    ?>
    <div class="form-group">
        <label for="recipient-name" class="control-label">First Name <span class="astrick">*</span></label>                
        <input class="form-control" name="fname" placeholder="Enter first name" type="text" data-parsley-pattern="^[a-zA-Z ]+" data-parsley-pattern-message="Please enter only alphabets." minlength="2" maxlength="25" value="" data-parsley-trigger="keyup" data-parsley-required-message="Please enter first name." required="" />
    </div>
    <div class="form-group">
        <label for="recipient-name" class="control-label">Last Name <span class="astrick">*</span></label>                
        <input class="form-control" name="lname" placeholder="Enter sur name" type="text" data-parsley-pattern="^[a-zA-Z ]+" data-parsley-pattern-message="Please enter only alphabets." minlength="2" maxlength="25" value="" data-parsley-trigger="keyup" data-parsley-required-message="Please enter sur name." required="" />
    </div>
    <div class="form-group">
        <label for="recipient-name" class="control-label">Email Address <span class="astrick">*</span></label>
        
        <input class="form-control" id="email_address" name="email" autocomplete="false" placeholder="Enter email address" data-parsley-trigger="change" required="" data-parsley-required-message="Please enter email address." type="email" value="" data-parsley-trigger="keyup" data-parsley-email />
        
    </div>
    <div class="form-group">
        <label for="recipient-name" class="control-label">Relationship <span class="astrick">*</span></label>                
        <input class="form-control" data-parsley-pattern="^[a-zA-Z ]+" data-parsley-pattern-message="Please enter only alphabets." minlength="2" maxlength="25" name="relationship" placeholder="Enter relationship"  type="text" value="" data-parsley-trigger="keyup" data-parsley-required-message="Please enter relationship." required="" />
    </div>
    <div class="form-group">
        <label for="recipient-name" class="control-label">Address <span class="astrick">*</span></label>                
        <input class="form-control" minlength="2" maxlength="100" name="address" placeholder="Enter address"  type="text" value="" data-parsley-trigger="keyup" data-parsley-required-message="Please enter address." required="" />
    </div>
    <div class="form-group">
        <label for="recipient-name" class="control-label">Contact Number <span class="astrick">*</span></label>                
        <input type="text" id='mobile_number' name='contact_number' class="form-control" placeholder="Enter contact number" value="" required='true' data-parsley-required-message="Please enter contact number." minlength="10" maxlength="15" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-pattern-message="Please enter only numbers." data-parsley-trigger="keyup"/>
        
    </div>

    <div class="form-group">
        <label for="recipient-name" class="control-label">YP Authorised Communication <span class="astrick">*</span></label>
        <label class="radio-inline">
            <input class="radio" required="true" data-parsley-required-message="Please enter YP authorised communication." data-parsley-errors-container="#error_yp_authorised_communication" name="yp_authorised_communication"  id="yp_authorised_communication" type="radio" value="Yes" />Yes
        </label>
        <label class="radio-inline">
       
            <input class="radio" required="true" data-parsley-required-message="Please enter YP authorised communication." data-parsley-errors-container="#error_yp_authorised_communication" name="yp_authorised_communication" id="yp_authorised_communication" type="radio" value="No"/> No
        </label>     
        <span id="error_yp_authorised_communication"></span> 
            
        
    </div>
    
    <div class="form-group">
     <label for="recipient-name" class="control-label">Carer’s Authorised Communication <span class="astrick">*</span></label>
         <label class="radio-inline">
            <input class="radio" required="true" data-parsley-required-message="Please enter carer’s authorised communication." data-parsley-errors-container="#error_carer_authorised_communication" name="carer_authorised_communication"  id="carer_authorised_communication" type="radio" value="Yes" />Yes
        </label>
        <label class="radio-inline">
            <input class="radio" required="true" data-parsley-required-message="Please enter YP authorised communication." data-parsley-errors-container="#error_carer_authorised_communication" name="carer_authorised_communication" id="carer_authorised_communication" type="radio" value="No" /> No
        </label>     
         <span id="error_carer_authorised_communication"></span>   
        
    </div>
    <div class="form-group">
        <label for="recipient-name" class="control-label">Comments </label>
            <textarea type="text"  name='comments' class="form-control" placeholder="Enter comments" minlength="2" maxlength="500" data-parsley-trigger="keyup" ></textarea>
        
    </div>
<?php }else if(!empty($user_type) && $user_type == 'social_worker'){
    ?>
    <div class="form-group">
        <label for="recipient-name" class="control-label">Social Worker First Name <span class="astrick">*</span></label>                
        <input class="form-control" name="fname" placeholder="Enter social worker firstname" type="text" data-parsley-pattern="^[a-zA-Z ]+" data-parsley-pattern-message="Please enter only alphabets." minlength="2" maxlength="25" value="" data-parsley-trigger="keyup" required="" />
    </div>
    
    <div class="form-group">
        <label for="recipient-name" class="control-label">Social Worker Last Name <span class="astrick">*</span></label>                
        <input class="form-control" data-parsley-pattern="^[a-zA-Z ]+" data-parsley-pattern-message="Please enter only alphabets." minlength="2" maxlength="25" name="lname" placeholder="Enter social worker lastname"  type="text" value="" data-parsley-trigger="keyup" required="" />
    </div>
    <div class="form-group">
        <label for="recipient-name" class="control-label">Email <span class="astrick">*</span></label>
        
        <input class="form-control" id="email" name="email" autocomplete="false" placeholder="Enter email id" data-parsley-trigger="change" required="" data-parsley-required-message="Please enter email id." type="email" value="" data-parsley-trigger="keyup" data-parsley-email />
        
    </div>
    
<?php
} ?>