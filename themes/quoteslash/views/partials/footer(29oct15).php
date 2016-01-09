<footer>
    <div class="footer-wrap">
        <div class="container">
            <div class="row ">
                <div class="display-table ">
                    <div class="col-sm-6 display-table-cell">
                        <div class="copyright">&copy;  Insurance Express, LLC | All rights reserved.</div>   
                    </div>
                    <div class="col-sm-6 display-table-cell">
                        <div class="social-icon">
                            <ul>
                                <li><a class="facebook" href="#">&nbsp;</a></li>
                                <li><a class="in" href="#">&nbsp;</a></li>
                                <li><a class="mail" href="#">&nbsp;</a></li>
                            </ul>
                        </div>
                    </div>
                </div>                
            </div>                
        </div>
    </div>
</footer>

</div><!-- container -->


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
            var html = '<h2>Congratulations !</h2>';
            html += '<p>You have successfully registered with quote slash.</p>';
            html += '<p>To activate your account please login into your email account and click on the activation link.</p>';
            html += '<p>Thanks,</p>';
            html += '<p>Quoteslash Team</p>';
            $(".modal-body").html(html);
<?php } ?>
<?php if ($this->session->flashdata('quote_success')) { ?>
            $('#myModal').modal('show');
            $(".modal-title").html('QUOTE REQUEST');
            var html = '<p class="">Quote Request Received.</p>';
            $(".modal-body").html(html);
<?php } ?>
<?php if ($this->session->flashdata('binder_success')) { ?>
            $('#myModal').modal('show');
            $(".modal-title").html('Binder Request');
            var html = '<p class="">Binder Request Received.</p>';
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
<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
    (function() {
        var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/560230da45ce6da15a8088aa/default';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>
<!--End of Tawk.to Script-->

<!--Start of tawk.to clickable text Script-->

<script type="text/javascript">

    function toggleChat() {

        Tawk_API.toggle();
        return false;

    }

</script>

<!--End of tawk.to clickable text Script-->

<!--Start of Tawk.to Status Code-->
<script type="text/javascript">Tawk_API.onStatusChange = function(status) {
        $(".livechat").removeClass("hidden");
        if (status === 'online') {
            document.getElementById('status').innerHTML = '<a href="#chat" onclick="toggleChat()"> <img src="' + liveOnlineChatImg + '"> </a>';
        } else if (status === 'away') {
            document.getElementById('status').innerHTML = '<a href="#chat" onclick="toggleChat()"> <img src="/path/to/img"> </a>';
        } else if (status === 'offline') {
            document.getElementById('status').innerHTML = '<a href="#chat" onclick="toggleChat()"> <img src="' + liveOfflineChatImg + '"> </a> </a>';
        }
    };
</script>
<!--End of Tawk.to Status Code -->

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
</script>

</body>

</html>



