<div class="inner-wrap">
    <div class="login-wrap">            
            <div class="form-in">    
                <div class="inner-title"><h1>Login</h1></div>
                <div class="form-wrap">
                    <?php //echo validation_errors() ?>
                    <?php echo $this->session->flashdata('message'); ?>
                    <?php echo form_open(); ?>
                    <div class="form-group">
                        <?php echo form_label('Email:', 'email') ?>
                        <span class="input-icon">
                            <?php echo form_input(array('id' => 'email', 'name' => 'email', 'class' => 'form-control', 'value' => set_value('email'))); ?>
                            <?php echo form_error('email');?>
                        </span>
                    </div>
                    <div  class="form-group">
                        <?php echo form_label('Password:', 'password') ?>
                        <span class="input-icon">
                            <?php echo form_password(array('id' => 'password', 'name' => 'password', 'class' => 'form-control', 'value' => set_value('password'))); ?>                             
                            <?php echo form_error('password');?>
                        </span>
                    </div>

                    <div class="button-set">
                        <?php // echo form_label('&nbsp;', '') ?>
                        <?php echo form_submit('submitForm', 'Login', 'class="btn btn-primary"', 'id="login"'); ?>
                    </div>

                    <div class="links">
                        <?php // echo form_label('', '') ?>
                        <?php echo anchor('/users/forgot-password', 'Forgot Password ?'); ?>
                        <?php if ($this->settings->users_module->enable_registration): ?>
                            &nbsp;|&nbsp; <?php echo anchor('/users/register', 'Register Now'); ?>
                        <?php endif; ?>

                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>        
    </div>
</div>
