<footer>
    <div class="footer-wrap">
        <div class="container">
            <div class="row ">
                <div class="display-table ">
                    <div class="col-sm-6 display-table-cell">
                        <div class="copyright">&copy; All rights reserved.</div>   
                    </div>
                    <div class="col-sm-6 display-table-cell">
                        <div class="social-icon">
                            <ul>
                                <li><a class="facebook" href="https://www.facebook.com/Insuranceexpresscom/?fref=ts" target="blank">&nbsp;</a></li>
                                <li><a class="in" href="https://www.linkedin.com/pub/todd-beall/78/214/287" target="blank">&nbsp;</a></li>
                                <li><a class="mail" href="mailto:quoteslash@insuranceexpress.com" target="blank">&nbsp;</a></li>
                            </ul>
                        </div>
                    </div>
                </div>                
            </div>                
        </div>
    </div>
</footer>

</div><!-- container -->

<?php /* ?>
<div class="box box-primary livechat hidden">
    <div class="box-header with-border">
        <h3 class="box-title">Live Chat</h3>
        <div class="box-tools pull-right">
            <button class="collapse_chat"><i class="fa fa-minus"></i></button>
        </div>
    </div><!-- /.box-header -->
    <div class="box-body">
        <div id="status"></div>
    </div><!-- /.box-body -->
</div>
<?php */ ?>


</div>
{{ template:footer }}

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">View Binder Request</h4>
            </div>
            <div class="modal-body">


            </div>

        </div>

    </div>
</div>

<script type="text/javascript">
    var liveOnlineChatImg = '<?php echo theme_url("assets/images/LiveChat_link.jpg"); ?>';
    var liveOfflineChatImg = '<?php echo theme_url("assets/images/live-chat_offline.jpeg"); ?>';
    $(document).ready(function() {
<?php if ($this->session->flashdata('reg_success')) { ?>
            $('#myModal').modal('show');
            $(".modal-title").html('Registration Successful');
            var html = '<h2 class="reg-heading">Congratulations !</h2>';
            html += '<p>You have successfully registered with quote slash.</p>';
            html += '<p>To activate your account please login into your email account and click on the activation link.</p>';
            html += '<label class="thanks">Thanks,</label>';
            html += '<p>Quoteslash Team</p>';
            html += '<p><strong style="font-weight:bold;">If you do not receive the confirmation message within a few minutes of signing up, please check your Spam/Junk/Bulk E-mail folder.</p></strong>';
            $(".modal-body").html(html);
<?php } ?>
<?php if ($this->session->flashdata('quote_success')) { ?>
            $('#myModal').modal('show');
            $(".modal-title").html('Quote Request');
            var html = '<h2 class="reg-heading">Congratulations !</h2><p class="">Your quote request was submitted successfully.<br>We will get back to you with further updates shortly.</p>';
            html += '<p><br>Thanks,<br>Quoteslash Team</p>';
            html += '<p><strong style="font-weight:bold;">If you do not receive the message within a few minutes of signing up, please check your Spam/Junk/Bulk E-mail folder.</strong></p>';
            $(".modal-body").html(html);
<?php } ?>
<?php if ($this->session->flashdata('binder_success')) { ?>
            $('#myModal').modal('show');
            $(".modal-title").html('Binder Request');
            var html = '<h2 class="reg-heading">Congratulations !</h2><p class="">Your binder request was submitted successfully.<br>We will get back to you with further updates shortly.</p>';
            html += '<p><br>Thanks,<br>Quoteslash Team</p>';
            html += '<p><strong style="font-weight:bold;">If you do not receive the message within a few minutes of signing up, please check your Spam/Junk/Bulk E-mail folder.</strong></p>';
            $(".modal-body").html(html);
<?php } ?>
        //        sticky footer 
        var stickyfooter = function() {
            var wh = jQuery(window).height();
            var hh = jQuery('header').outerHeight();
            var fh = jQuery('footer').outerHeight();
            jQuery('#content_wrapper ').css('min-height', wh - (hh + fh));
        };
        stickyfooter();
        jQuery(window).resize(function() {
            stickyfooter();
        });




    });
</script>
<!--Start of Tawk.to Status Code-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/56758531e8d7f443203d78a4/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Status Code -->

<!--Start of tawk.to clickable text Script-->

<script type="text/javascript">

//    function toggleChat() {
//
//        Tawk_API.toggle();
//        return false;
//
//    }

</script>

<script type="text/javascript">
    $(document).ready(function() {
        var $_chatCnt = $(".livechat .box-header");
        var $_status = $("#status");
        $_status.parent().hide();
        $_chatCnt.find("i").removeClass("fa-minus");
        $_chatCnt.on("click", function() {
            var elem = $("#status").parent();
            if ($(elem).is(":visible")) {
                $_status.parent().hide();
                $(this).find("i").removeClass("fa-minus");
            } else {
                $_status.parent().show();
                $(this).find("i").addClass("fa-minus");
            }
        });

    });

    function resend_activation_popup() {
        $('#myModal').modal('show');
        $(".modal-title").html('Resend Activation Link');
        var html = '<div class="form-group"><label for="email">Email:</label><input type="text" class="form-control" id="resend_email" value="" name="email" required></span></div>';
        html += '<p style="display:none;color:#F00" id="email-error-msg"></p>';
        html += '<input type="submit" class="btn btn-primary" value="Submit" name="submitForm" onclick="resend_activation_submit()">';
        $(".modal-body").html(html);
    }

    function resend_activation_submit() {
        $('#email-error-msg').hide();
        var email = $('#resend_email').val();

        if (email != '' && !validateEmail(email)) {
            $('#email-error-msg').html('Email is invalid').show();
        } else if (email == '') {
            $('#email-error-msg').html('Email is required').show();
        }else {
            $.ajax({
                url: '<?php echo site_url('users/users/resend-activation-link') ?>',
                data: {email: email},
                type: "POST",
                dataType: "json",
                success: function(data) {
                    if (data.success) {
                        $('#myModal').modal('show');
                        $(".modal-title").html('Activation Link Successfully Sent.');
                        var html = '<h2 class="reg-heading">Congratulations !</h2>';
                        html += '<p>New activation link has been successfully sent.</p>';
                        html += '<p>To activate your account please login into your email account and click on the activation link.</p>';
                        html += '<p>Thanks,</p>';
                        html += '<p>Quoteslash Team</p>';
                        $(".modal-body").html(html);
                    } else {
                        $('#email-error-msg').html('Email not found.').show();
                    }
                }
            });
        }
    }
    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
</script>
</body>

</html>



