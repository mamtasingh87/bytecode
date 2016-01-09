       
<h2 class="sub-inner-head">Binder Request</h2>
<fieldset>
    <?php
    $attributes = array('class' => 'binder-request-form', 'id' => '');
    echo form_open_multipart('', $attributes);
    ?>
    <div class="row">
            <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="borrower_name" class="control-label">Borrower Name<span class="required">*</span></label>
                    <div class='controls'>
                        <input id="borrower_name" class="form-control" type="text" name="borrower_name" maxlength="100" value="<?php echo set_value('borrower_name'); ?>"  />
                        <?php echo form_error('borrower_name'); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="borrower_phone" class="control-label">Borrower Phone<span class="required">*</span></label>
                    <div class='controls'>
                        <input id="phone_no" class="form-control" type="text" name="borrower_phone" maxlength="25" value="<?php echo set_value('borrower_phone'); ?>"  />
                        <?php echo form_error('borrower_phone'); ?>
                    </div>
                </div>
            </div>
        </div>
    <div class="row">
            <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="borrower_email" class="control-label">Borrower Email<span class="required">*</span></label>
                    <div class='controls'>
                        <input id="name" class="form-control" type="text" name="borrower_email" maxlength="100" value="<?php echo set_value('borrower_email'); ?>"  />
                        <?php echo form_error('borrower_email'); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="premium_quote" class="control-label">Quote Amount to be Bound<span class="required">*</span></label>
                    <div class='controls'>
                        <input id="borrower_name" class="form-control" type="text" name="premium_quote" maxlength="100" value="<?php echo set_value('premium_quote'); ?>"  />
                        <?php echo form_error('premium_quote'); ?>
                    </div>
                </div>
            </div>
        </div>
    <div class="row">
            <div class="col-sm-12">
                <div class="control-group form-group">
                    <label for="closing_date" class="control-label">Closing Date<span class="required">*</span></label>
                    <div class='controls'>
                        <input class="datepicker form-control" type="text" name="closing_date"  value="<?php echo set_value('closing_date'); ?>"  readonly="readonly"/>
                        <?php echo form_error('closing_date'); ?>
                    </div>
                </div> 
            </div>
            <div class="col-sm-12">
                <div class="control-group form-group">
                    <label for="mortgage_clause" class="control-label">Mortgagee Clause</label>
                    <div class='controls'>
                        <?php echo form_textarea(array('name' => 'mortgage_clause', 'class' => 'form-control', 'rows' => '5', 'cols' => '80', 'value' => set_value('mortgage_clause'))) ?>
                        <?php echo form_error('mortgage_clause'); ?>
                    </div>
                </div>
            </div>
        </div>        
    <div class="row">
        <div class="col-sm-12">
            <div class="control-group form-group">
        <label for="loan_number" class="control-label">Loan Number<span class="required">*</span></label>
        <div class='controls'>
            <input id="phone_no"  class="form-control" type="text" name="loan_number" maxlength="50" value="<?php echo set_value('loan_number'); ?>"  />
            <?php echo form_error('loan_number'); ?>
        </div>
    </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
    <div class="control-group form-group">
        <label for="requested_document" class="control-label">Request Document</label>
        <div class='controls'>
            <div class="input_fields_wrap pull-left">
            <input id="requested_document"  type="file"  name="requested_document[]" />
            <?php echo form_error('requested_document'); ?>
            </div>
                        <button class="add_field_button btn btn-primary pull-right">Add More Files</button>
        </div>
    </div>
        </div>
        </div>
    
    <?php if(!$sessionAvailability) { ?>
            <!--<div class="content-box">-->        
            
            
    <div class="row">               
            <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="first_name" class="control-label">Requestor's First Name <span class="required">*</span></label>
                    <div class='controls'>
                        <input id="name" class="form-control" type="text" name="first_name" maxlength="50" value="<?php echo set_value('first_name'); ?>"  />
                        <?php echo form_error('first_name'); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="last_name" class="control-label">Requestor's Last Name <span class="required">*</span></label>
                    <div class='controls'>
                        <input id="name" class="form-control" type="text" name="last_name" maxlength="50" value="<?php echo set_value('last_name'); ?>"  />
                        <?php echo form_error('last_name'); ?>
                    </div>
                </div>
            </div>
        </div>
    
    <div class="row">
        <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="email" class="control-label">Requestor's Email <span class="required">*</span></label>
                    <div class='controls'>
                        <input id="name" class="form-control" type="text" name="email" maxlength="100" value="<?php echo set_value('email'); ?>"  />
                        <?php echo form_error('email'); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="phone_no" class="control-label">Requestor's Phone No <span class="required">*</span></label>
                    <div class='controls'>
                        <input id="phone_no" class="form-control" type="text" name="phone_no" maxlength="25" value="<?php echo set_value('phone_no'); ?>"  />
                        <?php echo form_error('phone_no'); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
     <div class="row">
        <div class="col-sm-12">
    <div class="control-group">
        <label></label>
        <div class='controls' style="text-align:right;">
            <?php echo form_submit('submit', 'Submit', 'class="btn btn-primary"'); ?>
        </div>
    </div>
    </div>
    </div>
    <?php echo form_close(); ?></fieldset> 
<script>
$(document).ready(function() {
    var max_fields      = 30; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
   
    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append('<input id="requested_document"  type="file"  name="requested_document[]" />'); //add input box
        }
    });
});
</script>
<script type="text/javascript">
$(document).ready(function(){
      
   $(".datepicker").datepicker({
       changeMonth: true,
       changeYear: true,
       dateFormat: 'mm-dd-yy'
   });
});    
</script>