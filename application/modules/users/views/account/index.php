 {{ theme:partial name="reward_strip" }}
<div class="inner-wrap">
 <?php $email = ($this->session->userdata("invite_email")!="")?$this->session->userdata("invite_email"):'';?>
    <div class="row">
        <div class="col-sm-4 col-md-3">
            <div id="left_column" class="col-left">
                <div class="block-title"><h3>My Account</h3></div>
                <div class="block-cont">
                    <ul class="sidenav">                        
                       <li><a <?php echo ($this->uri->uri_string() == 'users/account') ? 'class="current"' : ''; ?> href="<?php echo site_url('users/account/'); ?>">Dashboard</a></li>
                        <li><a <?php echo ($this->uri->uri_string() == 'users/account/profile') ? 'class="current"' : ''; ?> href="<?php echo site_url('users/account/profile'); ?>">Profile</a></li>
                        <li><a <?php echo ($this->uri->uri_string() == 'users/account/change-password') ? 'class="current"' : ''; ?> href="<?php echo site_url('users/account/change-password'); ?>">Change Password</a></li>
                    </ul>
                </div>
</div>
            <div class="clearfix"></div>
            <?php echo theme_partial('invite_widget'); ?>
             <?php echo theme_partial('question'); ?>
            <?php //echo theme_partial('survey_mokey'); ?>
        </div>
        <div class="col-sm-8 col-md-9">
            <div id="two_col_right" class="col-main">
    <?php echo $this->session->flashdata('message'); ?>
                 <?php echo $this->session->flashdata('error'); ?>
    <?php //  echo validation_errors(); ?>

    <?php echo $content; ?>
</div>
        </div>
        
        
      
    </div>

</div>
   <?php if($email || $this->session->flashdata('refer_success') || $this->session->flashdata('refer_error')) { ?>
<script type="text/javascript">
$(document).ready(function(){
   $(".questions-box .block-title").addClass('open');
   $(".questions-box .block-cont").show();
   $("#invite_email").focus(); 
});    
</script>
<?php } else { ?>
<script type="text/javascript">
$(document).ready(function(){
//  location.hash = "two_col_right"; 
});
</script> 
<?php } ?>