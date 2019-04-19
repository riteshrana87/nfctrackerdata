
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel">Parents / Carers Information</h4>
            <div class="col-md-12 error-list">
                <?= isset($validation) ? $validation : ''; ?>
            </div>
        </div>
        <div class="modal-body">
           
            <div class="form-group">
                <label for="recipient-name" class="control-label">First Name :</label>                
                <span><?= !empty($editRecord[0]['firstname']) ? $editRecord[0]['firstname'] : '' ?></span>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">Surname :</label>                
                <span><?= !empty($editRecord[0]['surname']) ? $editRecord[0]['surname'] : '' ?></span>
            </div>
            
            <div class="form-group">
                <label for="recipient-name" class="control-label">Relationship :</label>                
                <span><?= !empty($editRecord[0]['relationship']) ? $editRecord[0]['relationship'] : '' ?></span>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">Address :</label>                
                <span><?= !empty($editRecord[0]['address']) ? $editRecord[0]['address'] : '' ?></span>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">Mobile Number :</label>                
                <span><?php echo set_value('contact_number', (isset($editRecord[0]['contact_number']) ? $editRecord[0]['contact_number'] : '')) ?></span>
                
            </div>

            <div class="form-group">
                <label for="recipient-name" class="control-label">Landline Number :</label>                
                <span><?php echo set_value('landline_number', (isset($editRecord[0]['landline_number']) ? $editRecord[0]['landline_number'] : '')) ?></span>
                
            </div>

            <div class="form-group">
                <label for="recipient-name" class="control-label">Email Address :</label>
                
                <span><?= !empty($editRecord[0]['email_address']) ? $editRecord[0]['email_address'] : '' ?></span>
                
            </div>
                
            <div class="form-group">
                <label for="recipient-name" class="control-label">YP Authorised Communication :</label>
                <label class="radio-inline">
                    <span>
					<?php if($editRecord[0]['yp_authorised_communication']=='Yes'){
						echo "Yes";
					}elseif($editRecord[0]['yp_authorised_communication']=='No'){
						echo "No";
						
					}?>
					</span>
                </label>
                 
                <span id="error_yp_authorised_communication"></span> 
                    
                
            </div>
            
            <div class="form-group">
                <label for="recipient-name" class="control-label">Carers Authorised Communication :</label>
                 <label class="radio-inline">
                    
					<span>
					<?php if($editRecord[0]['carer_authorised_communication']=='Yes'){
						echo "Yes";
					}elseif($editRecord[0]['carer_authorised_communication']=='No'){
						echo "No";
						
					}?>
					</span>
                </label>
              
                 <span id="error_carer_authorised_communication"></span>   
                
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label">Comments :</label>
                    <span><?php echo set_value('comments', (isset($editRecord[0]['comments'])) ? $editRecord[0]['comments'] : ''); ?></span>
            </div>

           
            
        </div>

    </div>
</div>


