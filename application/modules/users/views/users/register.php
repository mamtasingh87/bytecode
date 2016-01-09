<div class="inner-wrap">    
    <div class="register-wrap">
        <div class="form-in">
            <div class="inner-title"><h1>Register</h1></div>

            <div class="form-wrap">
                <?php echo $this->session->flashdata('message'); ?>
                <?php echo $this->session->flashdata('error'); ?>
                <?php // echo validation_errors()?>
                <?php echo form_open(); ?>       
                <?php if (isset($refer_code) && $refer_code): ?>
                    <?php echo form_hidden('referral', set_value('referral', $refer_code)) ?>
                <?php endif; ?>
                <div class="form-group">
                    <?php echo form_label('<span class="required">*</span> Email:', 'email') ?>
                    <span class="input-icon">
                        <?php echo form_input(array('id' => 'email', 'name' => 'email', 'class' => 'form-control', 'value' => set_value('email'))); ?>
                        <?php echo form_error('email'); ?>
                    </span>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo form_label('<span class="required">*</span> Create Password:', 'password') ?>
                            <span class="input-icon">
                                <?php echo form_password(array('id' => 'password', 'name' => 'password', 'class' => 'form-control', 'value' => set_value('password'))); ?>
                                <?php echo form_error('password'); ?>
                            </span>
                        </div>
                    </div>    
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo form_label('<span class="required">*</span> Confirm Password:', 'confirm_password') ?>
                            <span class="input-icon">
                                <?php echo form_password(array('id' => 'confirm_password', 'name' => 'confirm_password', 'class' => 'form-control', 'value' => set_value('confirm_password'))); ?>
                                <?php echo form_error('confirm_password'); ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo form_label('<span class="required">*</span> First Name:', 'first_name') ?>
                            <span class="input-icon">
                                <?php echo form_input(array('id' => 'first_name', 'name' => 'first_name', 'class' => 'form-control', 'value' => set_value('first_name'))); ?>
                                <?php echo form_error('first_name'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo form_label('<span class="required">*</span> Last Name:', 'last_name') ?>
                            <span class="input-icon">
                                <?php echo form_input(array('id' => 'last_name', 'name' => 'last_name', 'class' => 'form-control', 'value' => set_value('last_name'))); ?>
                                <?php echo form_error('last_name'); ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo form_label('<span class="required">*</span> Phone:', 'phone') ?>
                            <span class="input-icon">
                                <?php echo form_input(array('id' => 'phone', 'name' => 'phone', 'class' => 'form-control', 'value' => set_value('phone'))); ?>
                                <?php echo form_error('phone'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo form_label('Address:', 'address') ?>
                            <span class="input-icon">
                                <?php echo form_input(array('id' => 'address', 'name' => 'address', 'class' => 'form-control', 'value' => set_value('address'))); ?>
                                <?php echo form_error('address'); ?>
                            </span>
                        </div>
                    </div>    
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo form_label('Address 2:', 'address2') ?>
                            <span class="input-icon">
                                <?php echo form_input(array('id' => 'address2', 'name' => 'address2', 'class' => 'form-control', 'value' => set_value('address2'))); ?>
                                <?php echo form_error('address2'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo form_label('City:', 'city') ?>
                            <span class="input-icon">
                                <?php echo form_input(array('id' => 'city', 'name' => 'city', 'class' => 'form-control', 'value' => set_value('city'))); ?>
                                <?php echo form_error('city'); ?>
                            </span>
                        </div>
                    </div>    
                </div>   
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo form_label('State:', 'state') ?>
                            <span class="input-icon">
                                <span class="select-span">
                                    <?php echo form_dropdown('state', $states, set_value('state'), 'class="form-control"'); ?>
                                </span>
                                <?php echo form_error('state'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo form_label('Zip:', 'zip') ?>
                            <span class="input-icon">
                                <?php echo form_input(array('id' => 'zip', 'name' => 'zip', 'class' => 'form-control', 'value' => set_value('zip'))); ?>
                                <?php echo form_error('zip'); ?>
                            </span>
                        </div>
                    </div>

                </div>
                <div class="hide form-group">
                    <?php echo form_label('<span class="required">*</span> Spam Check:', 'spam_check') ?>
                    <span class="input-icon">
                        <?php echo form_input(array('id' => 'spam_check', 'name' => 'spam_check', 'class' => 'form-control', 'value' => set_value('spam_check'))); ?>
                        <?php echo form_error('spam_check'); ?>
                    </span>
                </div>

                <div class="button-set">
                    <?php // echo form_label('&nbsp;', '')?>
                    <?php echo form_submit('submitForm', 'Register', 'class="submit btn btn-primary"') ?>
                </div>

                <div class="links">
                    Already a Member? <a class="button" href="<?php echo site_url('users/login'); ?>">Login</a>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>    
</div>