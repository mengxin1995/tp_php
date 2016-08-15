<?php defined('InShopNC') or exit('Access Invalid!');?>
<div class="nc-login-layout">
  <div class="left-pic"><img src="<?php echo $output['lpic'];?>"  border="0"></div>
  <div class="nc-login">
  <div class="arrow"></div>
    <div class="nc-login-mode">
      <ul class="tabs-nav">
        <li><a href="#default">绑定到已有帐号<i></i></a></li>
      </ul>
      <div id="tabs_container" class="tabs-container">
        <div id="default" class="tabs-content">
          <form id="login_form" class="nc-login-form" method="post" action="<?php echo SHOP_SITE_URL;?>/index.php?act=connect&op=login">
            <?php Security::getToken();?>
            <input type="hidden" name="form_submit" value="ok" />
            <input name="nchash" type="hidden" value="<?php echo getNchash();?>" />
            <dl>
              <dt>账&nbsp;&nbsp;&nbsp;号：</dt>
              <dd>
                <input type="text" class="text" autocomplete="off"  name="user_name" tipMsg="可使用已注册的用户名或手机号登录" id="user_name"  >
              </dd>
            </dl>
            <dl>
              <dt><?php echo $lang['login_index_password'];?>：</dt>
              <dd>
                <input type="password" class="text" name="password" autocomplete="off" tipMsg="<?php echo $lang['login_register_password_to_login'];?>" id="password">
              </dd>
            </dl>
            <?php if(C('captcha_status_login') == '1') { ?>
            <div class="code-div mt15">
              <dl>
                <dt><?php echo $lang['login_index_checkcode'];?>：</dt>
                <dd>
                  <input type="text" name="captcha" autocomplete="off" class="text w100" tipMsg="<?php echo $lang['login_register_input_code'];?>" id="captcha" size="10" />                  
                </dd>
              </dl>
              <span><img src="index.php?act=seccode&op=makecode&type=50,120&nchash=<?php echo getNchash();?>" name="codeimage" id="codeimage"> <a class="makecode" href="javascript:void(0)" onclick="javascript:document.getElementById('codeimage').src='index.php?act=seccode&op=makecode&type=50,120&nchash=<?php echo getNchash();?>&t=' + Math.random();"><?php echo $lang['login_index_change_checkcode'];?></a></span></div>
            <?php } ?>
            <div class="submit-div">
              <input type="submit" class="submit" value="绑&nbsp;&nbsp;&nbsp;定">
            </div>
          </form>
          <form class="nc-login-form" method="post" action="<?php echo SHOP_SITE_URL;?>/index.php?act=connect&op=register">
            <div class="submit-div">
                <input type="submit" class="submit" value="不绑定,使用QQ帐号快速注册">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="clear"></div>
</div>
<script>
$(function(){
	//初始化Input的灰色提示信息  
	$('input[tipMsg]').inputTipText({pwd:'password'});
	//登录方式切换
	$('.nc-login-mode').tabulous({
		 effect: 'flip'//动画反转效果
	});	
	var div_form = '#default';
	$(".nc-login-mode .tabs-nav li a").click(function(){
        if($(this).attr("href") !== div_form){
            div_form = $(this).attr('href');
            $(""+div_form).find(".makecode").trigger("click");
    	}
	});
	
	$("#login_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.append(error);
            element.parents('dl:first').addClass('error');
        },
        success: function(label) {
            label.parents('dl:first').removeClass('error').find('label').remove();
        },
    	submitHandler:function(form){
    	    ajaxpost('login_form', '', '', 'onerror');
    	},
        onkeyup: false,
		rules: {
			user_name: "required",
			password: "required"
			<?php if(C('captcha_status_login') == '1') { ?>
            ,captcha : {
                required : true,
                remote   : {
                    url : 'index.php?act=seccode&op=check&nchash=<?php echo getNchash();?>',
                    type: 'get',
                    data:{
                        captcha : function(){
                            return $('#captcha').val();
                        }
                    },
                    complete: function(data) {
                        if(data.responseText == 'false') {
                        	document.getElementById('codeimage').src='index.php?act=seccode&op=makecode&type=50,120&nchash=<?php echo getNchash();?>&t=' + Math.random();
                        }
                    }
                }
            }
			<?php } ?>
		},
		messages: {
			user_name: "<i class='icon-exclamation-sign'></i>请输入已注册的用户名或手机号",
			password: "<i class='icon-exclamation-sign'></i><?php echo $lang['login_index_input_password'];?>"
			<?php if(C('captcha_status_login') == '1') { ?>
            ,captcha : {
                required : '<i class="icon-remove-circle" title="<?php echo $lang['login_index_input_checkcode'];?>"></i>',
				remote	 : '<i class="icon-remove-circle" title="<?php echo $lang['login_index_input_checkcode'];?>"></i>'
            }
			<?php } ?>
		}
	});
});
</script>