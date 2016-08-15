<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>完善店铺信息</title>
    <link href="<?php echo SHOP_TEMPLATES_URL;?>/css/member.css" rel="stylesheet" type="text/css">

    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
    <link href="<?php echo SHOP_TEMPLATES_URL?>/css/base.css" rel="stylesheet" type="text/css">
    <link href="<?php echo SHOP_TEMPLATES_URL?>/css/seller_center.css" rel="stylesheet" type="text/css">
    <link href="<?php echo SHOP_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <!--[if IE 7]>
    <link rel="stylesheet" href="<?php echo SHOP_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome-ie7.min.css">
    <![endif]-->
    <script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
    <script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="<?php echo RESOURCE_SITE_URL;?>/js/html5shiv.js"></script>
    <script src="<?php echo RESOURCE_SITE_URL;?>/js/respond.min.js"></script>
    <![endif]-->
    <!--[if IE 6]>
    <script src="<?php echo RESOURCE_SITE_URL;?>/js/IE6_MAXMIX.js"></script>
    <script src="<?php echo RESOURCE_SITE_URL;?>/js/IE6_PNG.js"></script>
    <script>
        DD_belatedPNG.fix('.pngFix');
    </script>
    <![endif]-->
    <script language="JavaScript" type="text/javascript">
        window.onload = function() {
            tips = new Array(2);
            tips[0] = document.getElementById("loginBG01");
            tips[1] = document.getElementById("loginBG02");
            index = Math.floor(Math.random() * tips.length);
            tips[index].style.display = "block";
        };
        $(document).ready(function() {
            $('.send_success_tips').hide();
            var ALLOW_SEND = true;

            function StepTimes() {
                $num = parseInt($('#show_times').html());
                $num = $num - 1;
                $('#show_times').html($num);
                if ($num <= 0) {
                    ALLOW_SEND = !ALLOW_SEND;
                    $('.send_success_tips').hide();
                } else {
                    setTimeout(StepTimes, 1000);
                }
            }

            $('#send_auth_code').on('click', function () {
                if ($('#store_mobile').val() == '') return false;
                if (!ALLOW_SEND) return;
                ALLOW_SEND = !ALLOW_SEND;
                $('#sending').show();
                $.getJSON('index.php?act=member_security&op=send_validate_mobile', {mobile: $('#store_mobile').val()}, function (data) {
                    if (data.state == 'true') {
                        $('#sending').hide();
                        $('.send_success_tips').show();
                        $('#show_times').html(60);
                        setTimeout(StepTimes, 1000);
                    } else {
                        ALLOW_SEND = !ALLOW_SEND;
                        $('#sending').hide();
                        $('.send_success_tips').hide();
                        alert(data.msg);
                    }
                });
            });

            $('#store_submit').on('click', function () {
                if(!($('#store_phone').val() == ''&&$('#store_mobile').val() == '')) {
                    if(!($('#store_qq').val() == ''&&$('#store_ww').val() == '')) {
                        $('#form_required').submit();
                    }
                }

            });
        });
    </script>
    <style>
        .title{
            display: block;
            float: left;
            width: 40px;
        }
    </style>
</head>
<body>
<div id="loginBG01" class="ncsc-login-bg">
    <p class="pngFix"></p>
</div>
<div id="loginBG02" class="ncsc-login-bg">
    <p class="pngFix"></p>
</div>
<div class="ncsc-login-container">
    <div class="ncsc-login-title">
        <h2>请先完善店铺信息</h2>
        <strong>店铺电话和店铺手机二选一必填，QQ和阿里旺旺二选一必填</strong>
    </div>
    <form id="form_required" action='index.php?act=store_required&op=submit' method="post" style="margin-top: 10px">
        <div style="margin-top: 15px">
            <span class="title">电话</span>
            <input name="store_phone" id="store_phone" type="text" style="width: 260px">
        </div>
        <div style="margin-top: 15px">
            <span class="title">手机</span>
            <input name="store_mobile" id="store_mobile" type="text" style="width: 95px;">
            <a href="javascript:void(0);" id="send_auth_code" class="ncm-btn ml5" style="width: 130px"><span id="sending" style="display: none;">正在</span><span class="send_success_tips" style="display: none;"><strong id="show_times" class="red mr5"></strong>秒后再次</span>获取验证码</a>
            <p class="send_success_tips hint mt10">验证码已发出，请注意查收，请在<strong>“30分种”</strong>内完成验证。</p>
        </div>
        <div style="margin-top: 15px">
            <span class="title">验证码</span>
            <input name="vcode" id="vcode" type="text" style="width: 260px">
        </div>
        <div style="margin-top: 15px">
            <span class="title">QQ</span>
            <input name="store_qq" id="store_qq" type="text" style="width: 260px">
        </div>
        <div style="margin-top: 15px">
            <span class="title">旺旺</span>
            <input name="store_ww" id="store_ww" type="text" style="width: 260px">
        </div>
        <div>
            <input id="store_submit" type="button" class="login-submit" style="margin-right: 145px;width: 100px;height: 30px" value="提交">
        </div>
    </form>
</div>
</body>
</html>
