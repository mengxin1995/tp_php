$(function(){
	var verify_code = '';
	$.sValid.init({//注册验证
        rules:{
        	username:"required",
			tel_number:"required",
			message_code:"required",
			password_set:"required",
            password_confirm:"required",
        },
        messages:{
            username:"用户名必须填写！",
			tel_number:"手机号必须填写!",
			message_code:"验证码必须填写",
			password_set:"密码必须填写!",
            password_confirm:"确认密码必须填写!",
        },
        callback:function (eId,eMsg,eRules){
            if(eId.length >0){
                var errorHtml = "";
                $.map(eMsg,function (idx,item){
                    errorHtml += "<p>"+idx+"</p>";
                });
                $(".error-tips").html(errorHtml).show();
            }else{
                $(".error-tips").html("").hide();
            }
        }
    });

	//发送验证码
	var ALLOW_SEND = true;
	function StepTimes() {
		$num = parseInt($('#show_times').html());
		$num = $num - 1;
		$('#show_times').html($num);
		if ($num <= 0) {
			ALLOW_SEND = !ALLOW_SEND;
			$('#waiting').hide();
			$("#send_messagebtn").css("background-color","#FFFFFF");
			$('#click').show();
		} else {
			setTimeout(StepTimes, 1000);
		}
	}
	$('#send_messagebtn').on('click', function() {
		if ($('#tel_number').val() == '') return false;
		if (!ALLOW_SEND) return;
		ALLOW_SEND = !ALLOW_SEND;
		$('#click').hide();
		$('#sending').show();
		$.getJSON(ApiUrl+'/index.php?act=login&op=send_code', {mobile: $('#tel_number').val()}, function (data) {
			if (data.state == 'true') {
				$('#sending').hide();
				$("#send_messagebtn").css("background-color","#A9A9A9");
				$('#waiting').show();
				$('#show_times').html(60);
				setTimeout(StepTimes, 1000);
                $(".error-tips").html(data.msg).show();
                $("input[name=verify_code]").val(data.verify_code);
			} else {
				ALLOW_SEND = !ALLOW_SEND;
				$('#sending').hide();
				$('#click').show();
				$(".error-tips").html(data.msg).show();
			}
		});
	});
	$('#loginbtn').click(function(){	
		var username = $("input[name=username]").val();
		var mobile = $("input[name=mobile]").val();
		var code = parseInt($("input[name=code]").val());
        var verify_code = parseInt($("input[name=verify_code]").val());
		var pwd = $("input[name=pwd]").val();
		var password_confirm = $("input[name=password_confirm]").val();
		var client = $("input[name=client]").val();
		if($.sValid()){
            if(verify_code == code) {
                $.ajax({
                    type:'post',
                    url:ApiUrl+"/index.php?act=login&op=register",
                    data:{username:username,mobile:mobile,code:code,password:pwd,password_confirm:password_confirm,client:client},
                    dataType:'json',
                    success:function(result){
                        if(!result.datas.error){
                            if(typeof(result.datas.key)=='undefined'){
                                return false;
                            }else{
                                addcookie('username',result.datas.username);
                                addcookie('key',result.datas.key);
                                location.href = WapSiteUrl+'/tmpl/member/member.html';
                            }
                            $(".error-tips").hide();
                        }else{
                            $(".error-tips").html(result.datas.error).show();
                        }
                    }
                });
            } else {
                $(".error-tips").html('验证码错误').show();
            }
		}
	});
});