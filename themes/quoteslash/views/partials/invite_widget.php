<?php $email = ($this->session->userdata("invite_email")!="")?$this->session->userdata("invite_email"):'';?>
<div id="left_column" class="col-left questions-box">
    <div class="block-title"><h3>Invite Friends</h3></div>
    <div class="block-cont">
    <?php echo $this->session->flashdata('refer_error'); ?>
    <?php echo $this->session->flashdata('refer_success'); ?>
    <?php echo form_open(site_url('trivia/questionfront/referfriend')); ?>
    <div>
        <div class="form-group">            
        <?php echo form_label('Enter E-mail to invite') ?>
            <?php echo form_input(array('id' => 'invite_email', 'name' => 'invite_email', 'class' => 'form-control', 'value' => $email)) ?>
            <?php echo form_error('inite_email'); ?>
        <div class="button-set">
            <input class="submit btn btn-primary" type="submit" value="Invite" />
        </div>
        </div>
    </div>
    <?php echo form_close(); ?>
    </div>
</div>

