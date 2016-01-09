
    <h2 class="sub-inner-head">Quote Request</h2>
    <div class="quote-request-top">
        <h2 class="">For Assistance Call: 866.568.5522</h2>
        <h3>Fast, Accurate &AMP; Hassle Free Quotes</h3>
        <span>We just need some basic information from you first. . .</span>
    </div>
    <div class="quote-request-bottom">
        <fieldset>    
    <?php
    $attributes = array('class' => 'quote-request-form', 'id' => '');
    echo form_open_multipart('', $attributes);
    ?>
            <div class="content-box">
            <legend><h4>Client Information</h4></legend>
                <div class="row">
        <div class="col-sm-12">
            <div class="control-group form-group">
        <label for="client_first_name" class="control-label">First Name <span class="required">*</span></label>
        <div class='controls'>
            <input id="client_first_name" class="form-control" type="text" name="client_first_name" maxlength="100" value="<?php echo set_value('client_first_name'); ?>"  />
            <?php echo form_error('client_first_name'); ?>
        </div>
    </div>
        </div>
    </div>
    <div class="row">
            <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="client_middle_name" class="control-label">Middle Name</label>
                    <div class='controls'>
                        <input id="client_middle_name"  class="form-control" type="text" name="client_middle_name" maxlength="2" value="<?php echo set_value('client_middle_name'); ?>"  />
                        <?php echo form_error('client_middle_name'); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="client_last_name" class="control-label">Last Name <span class="required">*</span></label>
                    <div class='controls'>
                        <input id="client_last_name" class="form-control" type="text" name="client_last_name" maxlength="100" value="<?php echo set_value('client_last_name'); ?>"  />
                        <?php echo form_error('client_last_name'); ?>
                    </div>
                </div>
            </div>        
        </div>
                <div class="row">
                    <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="client_dob" class="control-label">Date Of Birth</label>
                    <div class='controls'>
                        <input id="client_dob" class="form-control datepicker_dob"  type="text" class="datepicker" name="client_dob" maxlength="100" value="<?php echo set_value('client_dob'); ?>"  />
                        <?php echo form_error('client_dob'); ?>
                    </div>
                </div>
            </div>
                </div>
            </div>
    <div class="content-box">        
            <legend><h4>Property Information</h4></legend>       
    <div class="row">        
        <div class="col-sm-12">
                <div class="control-group form-group">
                    <label for="street_address" class="control-label">Street Address <span class="required">*</span></label>
                    <div class='controls'>
                        <input id="street_address" class="form-control"  type="text" name="street_address" maxlength="100" value="<?php echo set_value('street_address'); ?>"  />
                        <?php echo form_error('street_address'); ?>
                    </div>
                </div>
            </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="apt" class="control-label">Apt</label>
                    <div class='controls'>
                        <input id="apt" class="form-control" type="text" name="apt" maxlength="10" value="<?php echo set_value('apt'); ?>"  />
                        <?php echo form_error('apt'); ?>
                    </div>
                </div>
            </div>
        <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="city" class="control-label">City <span class="required">*</span></label>
                    <div class='controls'>
                        <input id="city" class="form-control" type="text" name="city" maxlength="50" value="<?php echo set_value('city'); ?>"  />
                        <?php echo form_error('city'); ?>
                    </div>
                </div>
            </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="state" class="control-label">State <span class="required">*</span></label>
                    <div class='controls'>
                        <?php echo form_dropdown('state', $states, $this->input->post('state'),'class="form-control"') ?>
                        <?php echo form_error('state'); ?>
                    </div>
                </div>
            </div>
        <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="zip_code" class="control-label">Zip Code <span class="required">*</span></label>
                    <div class='controls'>
                        <input id="zip_code" class="form-control" type="text" name="zip_code" maxlength="5" value="<?php echo set_value('zip_code'); ?>"  />
                        <?php echo form_error('zip_code'); ?>
                    </div>
                </div>
            </div>
    </div>
    <div class="row">
            <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="occupancy" class="control-label">Occupancy</label>
                    <div class="controls"><?php $options = array('' => 'Please Select', 'Owner' => 'Owner', 'Tenant' => 'Tenant', 'Owner (Seasonal)' => 'Owner (Seasonal)'); ?>

                        <?php echo form_dropdown('occupancy', $options, $this->input->post('occupancy'), 'class="form-control"') ?>
                        <?php echo form_error('occupancy'); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="effective_date" class="control-label">Effective Date </label>
                    <div class='controls'>
                        <input class="datepicker form-control" type="text" name="effective_date"  value="<?php echo set_value('effective_date'); ?>"  />
                        <?php echo form_error('effective_date'); ?>
                    </div>
                </div>
            </div>
        </div>
    <div class="row">
            <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="year_built" class="control-label">Year Built</label>
                    <div class='controls'>
                        <input id="year_built" class="form-control" type="text" name="year_built" maxlength="4" value="<?php echo set_value('year_built'); ?>"  />
                        <?php echo form_error('year_built'); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="square_feet" class="control-label">Square Feet</label>
                    <div class='controls'>
                        <input id="square_feet" class="form-control" type="text" name="square_feet" maxlength="20" value="<?php echo set_value('square_feet'); ?>"  />
                        <?php echo form_error('square_feet'); ?>
                    </div>
                </div>
            </div>
        </div>
    <div class="row">
            <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="construction" class="control-label">Construction</label>
                    <div class="controls"><?php $options = array('' => 'Please Select', 'Masonry' => 'Masonry', 'Frame' => 'Frame', 'Masonry & Frame' => 'Masonry & Frame'); ?>
                        <?php echo form_dropdown('construction', $options, $this->input->post('construction'), 'class="form-control"') ?>
                        <?php echo form_error('construction'); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="transaction_type" class="control-label">Transaction Type</label>
                    <div class="controls"><?php $options = array('' => 'Please Select', 'No Purchase' => 'No Purchase', 'Refi' => 'Refi', 'No Prior' => 'No Prior'); ?>
                        <?php echo form_dropdown('transaction_type', $options, $this->input->post('transaction_type'), 'class="form-control"') ?>
                        <?php echo form_error('transaction_type'); ?>
                    </div>
                </div>
            </div>
        </div>        
    <div class="row">
            <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="policy_type" class="control-label">Policy Type</label>
                    <div class="controls"><?php $options = array('' => 'Please Select', 'H03' => 'H03', 'H06' => 'H06', 'Investment Property (Home),' => 'Investment Property (Home)', 'Investment Property (Condo),' => 'Investment Property (Condo)'); ?>
                        <?php echo form_dropdown('policy_type', $options, $this->input->post('policy_type'), 'class="form-control"') ?>
                        <?php echo form_error('policy_type'); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="ownership_type" class="control-label">Ownership Type</label>
                    <div class="controls"><?php $options = array('' => 'Please Select', 'Individual' => 'Individual', 'Business Entity' => 'Business Entity(LLC,INC etc)', 'Trust' => 'Trust'); ?>
                        <?php echo form_dropdown('ownership_type', $options, $this->input->post('ownership_type'), 'class="form-control"') ?>
                        <?php echo form_error('ownership_type'); ?>
                    </div>
                </div>
            </div>
        </div>
    
    <div class="control-group form-group">
            <input type="checkbox" value="1" id="is_foreclosure" name="is_foreclosure" class="addi_fields" <?php echo (isset($is_foreclosure) && $is_foreclosure==1)?'checked="checked"':'';?>>
        <label for="is_foreclosure" class="control-label">
            Foreclosure
        </label>
        <div class="controls <?php echo (isset($is_foreclosure) && $is_foreclosure==1)?'':'hidden';?>">
            <input id="foreclosure" class="form-control" type="text" name="foreclosure" maxlength="100" value="<?php echo set_value('foreclosure'); ?>"  placeholder="Explain" />
            <?php echo form_error('foreclosure'); ?>
        </div>
    </div>

    <div class="control-group form-group">
            <input type="checkbox" value="1" id="is_bankruptcy" name="is_bankruptcy" class="addi_fields" <?php echo (isset($is_bankruptcy) && $is_bankruptcy==1)?'checked="checked"':'';?>>
        <label for="is_bankruptcy" class="control-label">
            Bankruptcy
        </label>
        <div class="controls <?php echo (isset($is_bankruptcy) && $is_bankruptcy==1)?'':'hidden';?>">
            <input id="bankruptcy" class="form-control" type="text" name="bankruptcy" maxlength="255" value="<?php echo set_value('bankruptcy'); ?>" placeholder="Explain" />
            <?php echo form_error('bankruptcy'); ?>
        </div>
    </div>

    <div class="control-group form-group">
            <input type="checkbox" value="1" id="is_bank_owned" name="is_bank_owned" class="addi_fields" <?php echo (isset($is_bank_owned) && $is_bank_owned==1)?'checked="checked"':'';?>>
        <label for="is_bank_owned" class="control-label">
            Bank Owned
        </label>
        <div class="controls <?php echo (isset($is_bank_owned) && $is_bank_owned==1)?'':'hidden';?>">
            <input id="bank_owned" class="form-control" type="text" name="bank_owned" maxlength="255" value="<?php echo set_value('bank_owned'); ?>" placeholder="Explain" />
            <?php echo form_error('bank_owned'); ?>
        </div>
    </div>        
    <div class="control-group form-group">
        <label for="desired_coverage_amount" class="control-label">Desired Coverage Amount</label>
        <div class='controls'>
            <input id="desired_coverage_amount" class="form-control" type="text" name="desired_coverage_amount" maxlength="10" value="<?php echo set_value('desired_coverage_amount'); ?>"  />
            <?php echo form_error('desired_coverage_amount'); ?>
        </div>
    </div>
    <div class="control-group form-group">
        <div class='controls'>
                <input type="checkbox" id="is_flood_zone"  name="is_flood_zone" value="1" <?php echo (isset($is_flood_zone) && $is_flood_zone==1)?'checked="checked"':'';?>>
            <label for="is_flood_zone">
                I would like a Flood Zone Determination</label>		
        </div>

        <?php echo form_error('is_flood_zone'); ?>
    </div>
    
    <div class="row">
        <div class="col-sm-12">
                <div class="control-group form-group">
                    <label for="quote_information" class="control-label">Quote Information</label>
                    <div class='controls'>
                        <?php echo form_textarea(array('name' => 'quote_information', 'class' => 'form-control', 'rows' => '5', 'cols' => '80', 'value' => set_value('quote_information'))) ?>
                        <?php echo form_error('quote_information'); ?>
                    </div>
                </div>
            </div>
    </div>
    
    <div class="control-group form-group">
        If you have a wind minitagtion, dec page, or inspection document, please upload here.<br>
        <label for="request_document" class="control-label">Request Document</label>
        <div class='controls'>
            <input id="request_document"  type="file" name="request_document" />
            <?php echo form_error('request_document'); ?>
        </div>
    </div>
    </div>
            <?php if(!$sessionAvailability) { ?>
            <div class="content-box">        
            <legend><h4>Requestor's Information</h4></legend>
            
    <div class="row">               
            <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="first_name" class="control-label">First Name <span class="required">*</span></label>
                    <div class='controls'>
                        <input id="name" class="form-control" type="text" name="first_name" maxlength="50" value="<?php echo set_value('first_name'); ?>"  />
                        <?php echo form_error('first_name'); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="last_name" class="control-label">Last Name <span class="required">*</span></label>
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
                    <label for="email" class="control-label">Email <span class="required">*</span></label>
                    <div class='controls'>
                        <input id="name" class="form-control" type="text" name="email" maxlength="100" value="<?php echo set_value('email'); ?>"  />
                        <?php echo form_error('email'); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="control-group form-group">
                    <label for="phone_no" class="control-label">Phone No <span class="required">*</span></label>
                    <div class='controls'>
                        <input id="phone_no" class="form-control" type="text" name="phone_no" maxlength="25" value="<?php echo set_value('phone_no'); ?>"  />
                        <?php echo form_error('phone_no'); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
            </div>
    <div class="control-group ">
<!--        <label></label>-->
        <div class='controls'>
            <?php echo form_submit('submit', 'Submit', 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
        </fieldset>
    </div>

<script type="text/javascript">
$(document).ready(function(){
   $(".addi_fields").click(function(){
      var checked = $(this).is(':checked'); 
      if(checked){
          $(this).parent().find(".controls").removeClass('hidden');
      } else {
          $(this).parent().find(".controls").addClass('hidden');
      }
   });
   
   $(".datepicker").datepicker({
       changeMonth: true,
       changeYear: true,
       dateFormat: 'yy-mm-dd'
   });
   $(".datepicker_dob").datepicker({
       changeMonth: true,
       changeYear: true,
       yearRange: '1915:2010',
       dateFormat: 'yy-mm-dd'
   });
});    
</script>