{{ theme:partial name="header" }}
<?php if($this->secure->is_auth()){ ?>
    <!-- Main Content -->
    <div id="content_wrapper">
        
        {{ theme:partial name="reward_strip" }}
        <?php $email = ($this->session->userdata("invite_email")!="")?$this->session->userdata("invite_email"):'';?>
       <div class="inner-wrap">

    <div class="row">
        <div class="col-sm-4">
            <div id="left_column" class="col-left">
                <div class="block-title"><h3>My Account</h3></div>
                <div class="block-cont">
                    <ul class="sidenav">
                         <li><a <?php echo ($this->uri->uri_string() == '/users/account/') ? 'class="current"' : ''; ?> href="<?php echo site_url('users/account/'); ?>">Dashboard</a></li>
                        <li><a <?php echo ($this->uri->uri_string() == '/users/account/profile') ? 'class="current"' : ''; ?> href="<?php echo site_url('users/account/profile'); ?>">Profile</a></li>
                        <li><a <?php echo ($this->uri->uri_string() == '/users/account/change-password') ? 'class="current"' : ''; ?> href="<?php echo site_url('users/account/change-password'); ?>">Change Password</a></li>
                        <li><a <?php echo ($this->uri->uri_string() == '/users/account/rewards') ? 'class="current"' : ''; ?> href="<?php echo site_url('users/account/rewards'); ?>">Rewards</a></li>
                         <li><a <?php echo ($this->uri->uri_string() == '/quote_request') ? 'class="current"' : ''; ?> href="<?php echo site_url('quote_request'); ?>">New Quote Request</a></li>
                          <li><a <?php echo ($this->uri->uri_string() == '/users/account/previous-quotes') ? 'class="current"' : ''; ?> href="<?php echo site_url('users/account/previous-quotes'); ?>">Previous Quote Requests</a></li>
                          <li><a <?php echo ($this->uri->uri_string() == '/binder_request') ? 'class="current"' : ''; ?> href="<?php echo site_url('binder_request'); ?>">New Binder Request</a></li>
                          <li><a <?php echo ($this->uri->uri_string() == '/users/account/previous-binders') ? 'class="current"' : ''; ?> href="<?php echo site_url('users/account/previous-binders'); ?>">Previous Binder Requests</a></li>
<!--                          <li><a <?php echo ($this->uri->uri_string() == '/users/account/change-password') ? 'class="current"' : ''; ?> href="#">Trivia Question of the day</a></li>-->
                          <!--<li><a <?php echo ($this->uri->uri_string() == '/users/account/change-password') ? 'class="current"' : ''; ?> href="#">Invite Friends</a></li>-->
                          <li><a <?php echo ($this->uri->uri_string() == '/users/account/invitation-log') ? 'class="current"' : ''; ?> href="<?php echo site_url('/users/account/invitation-log'); ?>">Invitation Log</a></li>
                    </ul>
                </div>
</div>
            
            <div class="clearfix"></div>
             <?php echo theme_partial('invite_widget'); ?>
             <?php echo theme_partial('question'); ?>
            <?php //echo theme_partial('survey_mokey'); ?>
        </div>
        <div class="col-sm-8">
            <div id="two_col_right" class="col-main">
    <?php echo $this->session->flashdata('message'); ?>
                 <?php echo $this->session->flashdata('error'); ?>
    <?php //  echo validation_errors(); ?>

    <?php echo $content; ?>
</div>
        </div>
    </div>

</div>
        <div class="clear"></div>
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
  location.hash = "two_col_right"; 
});
</script> 
<?php } ?>
<?php } else { ?>
    <!-- Main Content -->
    <div id="content_wrapper">
        <div class="inner-wrap reward-box">
   
               <?php echo $this->session->flashdata('message'); ?>
                 <?php echo $this->session->flashdata('error'); ?>
    </div>
      
        {{ content }}
        <div class="clear"></div>
    </div>
<?php } ?>
{{ theme:partial name="footer" }}
