<?php
//$password = array(
//                'id'   => 'password',
//                'name' => 'password',
//                'value' => set_value('password'),
//            );
//
//$confirm_password = array(
//                'id'   => 'confirm_password',
//                'name' => 'confirm_password',
//            );
?>
<h2 class="sub-inner-head">Change Password</h2>

<?php echo form_open(); ?>

<div class="form">
    
    <div class="form-group">
        <?php echo form_label('Old Password:', 'old_password'); ?>
        <?php echo form_password(array('id' => 'old_password', 'name' => 'old_password', 'class' => 'form-control', 'value' => set_value(''))); ?>
        <?php // echo form_password($password); ?>
        <?php echo form_error('old_password'); ?>
    </div>
    <div class="form-group">
        <?php echo form_label('New Password:', 'password'); ?>
        <?php echo form_password(array('id' => 'password', 'name' => 'password', 'class' => 'form-control', 'value' => set_value(''))); ?>
        <?php // echo form_password($password); ?>
        <?php echo form_error('password'); ?>
    </div>

    <div class="form-group">
        <?php echo form_label('Confirm Password:', 'confirm_password'); ?>
        <?php echo form_password(array('id' => 'confirm_password', 'name' => 'confirm_password', 'class' => 'form-control', 'value' => set_value(''))); ?>
        <?php // echo form_password($confirm_password); ?>
        <?php echo form_error('confirm_password'); ?>
    </div>

    <div class="button-set">
        <?php // echo form_label('&nbsp;', ''); ?>
        <input class="submit btn btn-primary" type="submit" value="Change" />
    </div>
</div>

<?php echo form_close(); ?>
