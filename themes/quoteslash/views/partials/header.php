<!DOCTYPE html>
<html lang="{{ lang }}">
    <head>
        <meta charset="UTF-8" /> 

        {{ template:head }}

        <!-- Stylesheet Includes -->
        <link href='https://fonts.googleapis.com/css?family=Oxygen:400,300,700' rel='stylesheet' type='text/css'>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="{{ theme_url }}/assets/css/reset.css" />
        <link rel="stylesheet" type="text/css" href="{{ theme_url }}/assets/css/content.css" />
        <!--<link rel="stylesheet" type="text/css" href="{{ theme_url }}/assets/css/bootstrap.css" />-->
        <link rel="stylesheet" type="text/css" href="{{ theme_url }}/assets/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="{{ theme_url }}/assets/css/font-awesome.min.css" />        
        <link rel="stylesheet" type="text/css" href="{{ theme_url }}/assets/css/style.css" />
        <link rel="stylesheet" type="text/css" href="{{ theme_url }}/assets/css/responsive.css" />
        <link rel="stylesheet" type="text/css" href="{{ theme_url }}/assets/css/owl.carousel.css" />
        <link rel="stylesheet" type="text/css" href="{{ theme_url }}/assets/js/jqueryui/smoothness/jquery-ui-1.9.2.custom.min.css" />
        <link rel="stylesheet" type="text/css" href="{{ theme_url }}/assets/css/ui/jquery.ui.theme.css" />
        <link rel="stylesheet" href="{{ theme_url }}/assets/css/plugins/select2.min.css">
        <!-- Javascript Includes -->
        <script type="text/javascript" src="{{ theme_url }}/assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="{{ theme_url }}/assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="{{ theme_url }}/assets/js/jqueryui/jquery-ui-1.9.2.custom.min.js"></script>
        <script type="text/javascript" src="{{ theme_url }}/assets/js/owl.carousel.js"></script>
        <script type="text/javascript" src="{{ theme_url }}/assets/js/superfish.js"></script>
        <script type="text/javascript" src="{{ theme_url }}/assets/js/plugins/flot/jquery.flot.min.js"></script>
        <script type="text/javascript" src="{{ theme_url }}/assets/js/plugins/flot/jquery.flot.resize.min.js"></script>
        <script type="text/javascript" src="{{ theme_url }}/assets/js/plugins/flot/jquery.flot.categories.min.js"></script>
        <script type="text/javascript" src="{{ theme_url }}/assets/js/plugins/select2.full.min.js"></script>
        <script type="text/javascript" src="{{ theme_url }}/assets/js/hack.js"></script>
        <!--[if lt IE 9]>
            <script src="{{ theme_url }}/assets/js/html5.js"></script>
        <![endif]-->

        <script type="text/javascript">
$(document).ready(function() {
    $('nav > ul').superfish({
        hoverClass: 'sfHover',
        pathClass: 'overideThisToUse',
        delay: 0,
        animation: {height: 'show'},
        speed: 'normal',
        autoArrows: false,
        dropShadows: false,
        disableHI: false, /* set to true to disable hoverIntent detection */
        onInit: function() {
        },
        onBeforeShow: function() {
        },
        onShow: function() {
        },
        onHide: function() {
        }
    });
    get_cart_quantity()

    $('nav > ul').css('display', 'block');

    $(".banner").owlCarousel({
        navigation: true, // Show next and prev buttons
        slideSpeed: 300,
        paginationSpeed: 400,
        autoPlay: 3000,
        singleItem: true
    });

    jQuery('.dropdown-menu').click(function() {
        jQuery('.mobile-nav').slideToggle();

    });
    $(".select2").select2();

    menuaccor();
    function menuaccor() {
        jQuery('.block-title').click(function() {
            jQuery(this).toggleClass('open');
            jQuery(this).parent().find('.block-cont').stop().slideToggle().parent().siblings().find('.block-cont').slideUp();
        });
    }
});

function get_cart_quantity(){
    var html_structure='<i class="fa fa-shopping-cart"></i>';
    $.ajax({
        url:'<?php echo site_url('redemption/redeem/checkcart') ?>',
        success:function(evt){
            var json_data = JSON.parse(evt);
            if (json_data.success) {
                html_structure=html_structure+'<span class="cart-qty">'+json_data.quantity+'</span>';
                $('#my-cart-show').parent('li').show();
                $('#my-cart-show').html(html_structure);
            }else{
                $('#my-cart-show').parent('li').hide();
            }
        }
    })
}
        </script>
    </head>


    <body>
        <div  class="container">
            <!-- Header -->
            <header>
                <div class="header-wrap">
                    <div id="container" class="container" >
                        <div class="row ">
                            <div class="display-table ">
                                <!--                <a id="logo" href="{{ site_url }}">{{ settings:site_name }}</a> -->
                                <div class="col-sm-2 col-md-3  display-table-cell"><a id="logo" href="{{ site_url }}"><img  src="{{ theme_url }}/assets/images/logo.png" alt="logo"/></a> </div>
                                <div class="col-sm-5 col-md-6 display-table-cell">
                                    <nav>
                                        {{ navigations:nav nav_id="1" class="left" }}                                   
                                        <div class="clear"></div>
                                    </nav>
                                    <div class="mobile-menu">
                                        <div class="dropdown-menu">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </div>
                                        <ul class="mobile-nav">
                                            <li><a href="{{ site_url }}/home" title="Home">Home</a></li>
                                            <li><a href="{{ site_url }}/about" title="About Us">About Us</a></li>
                                            <li><a href="{{ site_url }}/service" title="Services">Services</a></li>
                                            <li><a href="{{ site_url }}/started" title="Get Started">Get Started</a></li>
                                            <li><a href="{{ site_url }}/contact" title="Contact Us">Contact Us</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-3  display-table-cell">
                                    <div class="header-login">
                                        <ul>
                                            {{ if users:is_logged_in }}
                                            <li style="display: none;"><a id="my-cart-show" class="btn btn-primary" href="{{ site_url }}/redemption/redeem/showcart" title="My Cart"></a></li>
                                            <li><a class="btn btn-primary" href="{{ site_url }}/users/account" title="My account"><i class="fa fa-user"></i>My account</a></li>
                                            <li><a class="btn btn-primary" href="{{ site_url }}/users/logout" title="Logout"><i class="fa fa-power-off"></i>Logout</a></li>
                                            {{else}}
                                            <li><a class="btn btn-primary" href="{{ site_url }}/users/login" title="Login"><i class="fa fa-sign-in"></i>Login</a></li>
                                            <li><a class="btn btn-primary" href="{{ site_url }}/users/register" title="Register"><i class="fa fa-user-plus"></i>Register</a></li>
                                            {{ endif }}
                                            {{ if users:is_logged_in }}
                                            <!--										<li><a href="{{ site_url }}/users/logout"></a></li>-->
                                            {{else}}
    <!--										<li class="logout"><a href="javascript:void(0);"><i class="fa fa-lock"></i></a></li>-->
                                            {{endif}}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

