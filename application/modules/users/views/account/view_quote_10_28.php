<dl>
    <fieldset>    
        <div class="content-box">
            <legend><h4>Client Information</h4></legend>

            <div class="row">
                <div class="col-sm-12">
                    <div class="control-group form-group">
                        <label class="control-label" for="client_name"><dt>Client Name</dt></label>
                        <div class="controls">
                            <dd><?php echo $quote->client_first_name . ' ' . $quote->client_middle_name . ' ' . $quote->client_last_name; ?></dd>
                        </div>
                    </div>
                </div>
            </div>  
            <div class="row">
                <div class="col-sm-12">
                    <div class="control-group form-group">
                        <label class="control-label" for="client_dob"><dt>Client DOB</dt></label>
                        <div class="controls">
                            <dd><?php echo $quote->client_dob; ?></dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset>    
        <div class="content-box">
            <legend><h4>Property Information</h4></legend>

            <div class="row">
                <div class="col-sm-12">
                    <div class="control-group form-group">
                        <label class="control-label" for="street_address"><dt>Street Address</dt></label>
                        <div class="controls">
                            <dd><?php echo $quote->street_address; ?></dd>
                        </div>
                    </div>
                </div>
            </div>  
            <div class="row">
                <div class="col-sm-12">
                    <div class="control-group form-group">
                        <label class="control-label" for="apertment"><dt>Apartment</dt></label>
                        <div class="controls">
                            <dd><?php echo $quote->apt; ?></dd>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-sm-12">
                    <div class="control-group form-group">
                        <label class="control-label" for="city"> <dt>City</dt></label>
                        <div class="controls">
                           <dd><?php echo $quote->city; ?></dd>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-sm-12">
                    <div class="control-group form-group">
                        <label class="control-label" for="state"> <dt>State</dt></label>
                        <div class="controls">
                           <dd><?php echo $states[$quote->state]; ?></dd>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-sm-12">
                    <div class="control-group form-group">
                        <label class="control-label" for="zip_code"> <dt>Zip Code</dt></label>
                        <div class="controls">
                           <dd><?php echo $quote->zip_code; ?></dd>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-sm-12">
                    <div class="control-group form-group">
                        <label class="control-label" for="occupancy"><dt>occupancy</dt></label>
                        <div class="controls">
                          <dd><?php echo $quote->occupancy; ?></dd>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-sm-12">
                    <div class="control-group form-group">
                        <label class="control-label" for="effective_date"><dt>Effective Date</dt></label>
                        <div class="controls">
                           <dd><?php echo $quote->effective_date; ?></dd>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-sm-12">
                    <div class="control-group form-group">
                        <label class="control-label" for="year_built"><dt>Year Built</dt></label>
                        <div class="controls">
                          <dd><?php echo $quote->year_built; ?></dd>
                        </div>
                    </div>
                </div>
            </div> 
            
            <div class="row">
                <div class="col-sm-12">
                    <div class="control-group form-group">
                        <label class="control-label" for="square_feet"><dt>Square Feet</dt></label>
                        <div class="controls">
                           <dd><?php echo $quote->square_feet; ?></dd>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-sm-12">
                    <div class="control-group form-group">
                        <label class="control-label" for="Status"><dt>Status</dt></label>
                        <div class="controls">
                           <dd><?php echo "Pending"; ?></dd>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="control-group form-group">
                        <label class="control-label" for="client_middle_name"><dt>Construction</dt>
    </label>
                        <div class="controls">
                           <dd><?php echo $quote->construction; ?></dd>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-sm-12">
                    <div class="control-group form-group">
                        <label class="control-label" for="client_middle_name"><dt>Desired Coverage Amount</dt>
    </label>
                        <div class="controls">
                           <dd><?php echo $quote->desired_coverage_amount; ?></dd>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-sm-12">
                    <div class="control-group form-group">
                        <label class="control-label" for="client_middle_name"><dt>Transaction Type</dt>
    </label>
                        <div class="controls">
                          <dd><?php echo $quote->transaction_type; ?></dd>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-sm-12">
                    <div class="control-group form-group">
                        <label class="control-label" for="client_middle_name"><dt>Policy Type</dt>
    </label>
                        <div class="controls">
                           <dd><?php echo $quote->policy_type; ?></dd>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-sm-12">
                    <div class="control-group form-group">
                        <label class="control-label" for="client_middle_name"><dt>Ownership Type</dt>
    </label>
                        <div class="controls">
                           <dd><?php echo $quote->ownership_type; ?></dd>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-sm-12">
                    <div class="control-group form-group">
                        <label class="control-label" for="client_middle_name"><dt>Name</dt>
    </label>
                        <div class="controls">
                           <dd><?php echo $quote->name; ?></dd>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-sm-12">
                    <div class="control-group form-group">
                        <label class="control-label" for="client_middle_name"> <dt>email</dt>
    </label>
                        <div class="controls">
                           <dd><?php echo $quote->email; ?></dd>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-sm-12">
                    <div class="control-group form-group">
                        <label class="control-label" for="client_middle_name"><dt>Phone No</dt>
    </label>
                        <div class="controls">
                           <dd><?php echo $quote->phone_no; ?></dd>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-sm-12">
                    <div class="control-group form-group">
                        <label class="control-label" for="client_middle_name"><?php if ($quote->request_document) { ?>
                                <dt>Attached Document</dt></label>
                        <div class="controls">
                          <dd><a title="My account" href="<?php echo site_url('users/account/download/quote/'.$quote->request_document); ?>" class="btn btn-primary"><i class="fa fa-download"></i> Download </a></dd>
                           <?php } ?>

                        </div>
                    </div>
                </div>
            </div> 
             
             
        </div>
    </fieldset>
</dl>
   
    
    
    
    
    
    
    
    
    
    
    
    
    <!--    <dt>foreclosure</dt>
        <dd><?php // echo $quote->foreclosure; ?></dd>
        <dt>Bankruptcy</dt>
        <dd><?php // echo $quote->bankruptcy; ?></dd>
        <dt>Bank Owned</dt>
        <dd><?php // echo $quote->bank_owned; ?></dd>-->
   
    <!--<dt>Quote Information</dt>-->
    <!--<dd><?php // echo $quote->quote_information; ?></dd>-->
    

<!--    <dt>Phone No</dt>
    <dd><?php //echo $quote->phone_no; ?></dd>-->
<!--    <dt>Square Feet</dt>
    <dd><?php //echo $quote->square_feet; ?></dd>-->
    <?php //if ($quote->requested_document) { ?>
<!--        <dt>Attached Document</dt>
        <dd><a title="My account" href="<?php // echo site_url('users/account/download/' . $quote->requested_document); ?>" class="btn btn-primary"><i class="fa fa-download"></i> Download </a></dd>-->
    <?//php } ?>
<!--</dl>-->