<div class="inner-wrap">    
        <div class="forgot-pass-wrap">
            <div class="form-in"> 
                <div class="inner-title"><h1>Forgot Password</h1></div>
                
<div class="form-wrap">
<?php //  echo validation_errors()?>
<?php echo  form_open(); ?>

    <div class="form-group">
        <?php echo  form_label('Enter your email:', 'email'); ?>
        <span class="input-icon icon-right">
            <?php echo  form_input(array('id'=>'email', 'name'=>'email', 'class' => 'form-control', 'value'=>set_value('email'))); ?>
            <?php echo form_error('email');?>
        </span>
    </div>

    <div class="button-set">
        <?php // echo form_label('&nbsp;', '')?>
        <?php echo form_submit('submit', 'Submit', 'class="submit btn btn-primary"'); ?>
    </div>

<?php echo form_close(); ?>
            </div>       
        </div>  
        </div>      
</div>
