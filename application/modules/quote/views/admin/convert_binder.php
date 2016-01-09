<div class="box">
    <div class="heading">
        <h1><img alt="" src="<?php echo theme_url('assets/images/tax.png'); ?>"> Convert Into Binder</h1>

        <div class="buttons">
            <a class="button" href="#" id="save" onClick="$('#quote_convert_form').submit()"><span>Save</span></a>
            <a class="button" href="#" id="save" onClick='window.location.href="<?php echo site_url(ADMIN_PATH.'/quote/quote');?>"'><span>Cancel</span></a>
        </div>
    </div>
    <div class="content">
        <?php echo form_open(null, 'id="quote_convert_form"'); ?>
        <div class="form">
            <div class="field_spacing">
                <?php echo form_label('<span class="required">*</span> Borrower Name:', 'borrower_name'); ?>
                <?php echo form_input(array('id' => 'borrower_name','size'=>'63', 'name' => 'borrower_name', 'value' => set_value('borrower_name'))); ?>
            </div>
            <div class="field_spacing">
                <?php echo form_label('<span class="required">*</span> Borrower Email:', 'borrower_email'); ?>
                <?php echo form_input(array('id' => 'borrower_email','size'=>'63', 'name' => 'borrower_email', 'value' => set_value('borrower_email'))); ?>
            </div>
            <div class="field_spacing">
                <?php echo form_label('<span class="required">*</span> Borrower Phone:', 'borrower_phone'); ?>
                <?php echo form_input(array('id' => 'borrower_phone','size'=>'63', 'name' => 'borrower_phone', 'value' => set_value('borrower_phone'))); ?>
            </div>
            <div class="field_spacing">
                <?php echo form_label('<span class="required">*</span> Quote Amount to be Bound:', 'premium_quote'); ?>
                <?php echo form_input(array('id' => 'premium_quote','size'=>'63', 'name' => 'premium_quote', 'value' => set_value('premium_quote'))); ?>
            </div>
            <div class="field_spacing">
                <?php echo form_label('<span class="required">*</span> Closing Date:', 'closing_date'); ?>
                <?php echo form_input(array('id' => 'closing_date','size'=>'63', 'name' => 'closing_date', 'value' => set_value('closing_date'))); ?>
            </div>
            <div class="field_spacing">
                <?php echo form_label('Mortgage Clause:', 'mortgage_clause'); ?>
                <?php echo form_textarea(array('name' => 'mortgage_clause', 'class' => 'form-control', 'rows' => '5', 'cols' => '80', 'value' => set_value('mortgage_clause'))) ?>
                <?php echo form_error('mortgage_clause'); ?>
            </div>
            <div class="field_spacing">
                <?php echo form_label('<span class="required">*</span> Loan No:', 'loan_number'); ?>
                <?php echo form_input(array('id' => 'loan_number','size'=>'63', 'name' => 'loan_number', 'value' => set_value('loan_number'))); ?>
            </div>
            <div class="field_spacing">
                <?php echo form_label('Request Document:', 'requested_document'); ?>
                <input id="requested_document"  type="file" name="requested_document" />
            </div>
            
        <input type="hidden" name="convert_id" value="<?php echo $convertId;?>">
</div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $("#closing_date").datepicker({
        changeMonth: true,
       changeYear: true,
       dateFormat: 'yy-mm-dd'
    });
    $('body').find('#ui-datepicker-div').wrap('<div class="smoothness"></div>');
})    
</script>