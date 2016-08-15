<?php defined('InShopNC') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/member.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/member.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ToolTip.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js"></script>
<script type="text/javascript">
$(document).ready(function(){
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
            setTimeout(StepTimes,1000);
        }
    }
    $('#send_auth_code').on('click',function(){
        if ($('#contacts_phone').val() == '') return false;
        if (!ALLOW_SEND) return;
        ALLOW_SEND = !ALLOW_SEND;
        $('#sending').show();
        $.getJSON('index.php?act=member_security&op=send_validate_mobile',{mobile:$('#contacts_phone').val()},function(data){
            if (data.state == 'true') {
                $('#sending').hide();
                $('.send_success_tips').show();
                $('#show_times').html(60);
                setTimeout(StepTimes,1000);
            } else {
                ALLOW_SEND = !ALLOW_SEND;
                $('#sending').hide();
                $('.send_success_tips').hide();
                showDialog(data.msg,'error','','','','','','','','',2);
            }
        });
    });

    $('#company_address').nc_region();
    $('#business_licence_address').nc_region();
    
    $('#business_licence_start').datepicker();
    $('#business_licence_end').datepicker();

    $('#btn_apply_agreement_next').on('click', function() {
        if($('#input_apply_agreement').prop('checked')) {
            $('#apply_agreement').hide();
            $('#apply_company_info').show();
        } else {
            alert('请阅读并同意协议');
        }
    });
    $('#form_company_info').validate({
        errorPlacement: function(error, element){
            element.nextAll('span').first().after(error);
        },
        rules : {
            company_name: {
                required: true,
                maxlength: 50 
            },
            company_address: {
                required: true,
                maxlength: 50 
            },
            company_address_detail: {
                required: true,
                maxlength: 50 
            },
            contacts_name: {
                required: true,
                maxlength: 20 
            },
            contacts_phone: {
                required: true,
                maxlength: 20 
            },
            vcode: {
                required : true,
                maxlength : 6,
                minlength : 6,
                digits : true
            },
            contacts_email: {
                required: true,
                email: true 
            },
            business_licence_number: {
                required: true,
                maxlength: 20
            },
            business_sphere: {
                required: true,
                maxlength: 500
            },
            <?php if($output['joinin_detail']['business_licence_number_electronic'] ==''){ ?>
            business_licence_number_electronic: {
                required: true
            },
            <?php }?>
        },
        messages : {
            company_name: {
                required: '请输入店铺名字',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            company_address: {
                required: '请选择区域地址',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            company_address_detail: {
                required: '请输入目前详细住址或办公地址',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            contacts_name: {
                required: '请输入联系人姓名',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            contacts_phone: {
                required: '请输入联系人手机',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            vcode: {
                required : '请输入短信验证码',
                maxlength : '请正确输入短信验证码',
                minlength : '请正确输入短信验证码',
                digits : '请正确输入短信验证码'
            },
            contacts_email: {
                required: '请输入常用邮箱地址',
                email: '请填写正确的邮箱地址'
            },
            business_licence_number: {
                required: '请输入身份证号',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            business_sphere: {
                required: '请填写身份证上姓名',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            <?php if($output['joinin_detail']['business_licence_number_electronic'] ==''){ ?>
            business_licence_number_electronic: {
                required: '请选择上传手执身份证照'
            },
            <?php }?>
        }
    });
    $('#btn_apply_company_next').on('click', function() {
        if($('#form_company_info').valid()) {
            $('#form_company_info').submit();
        }
    });
});
</script>

<div id="append_parent"></div>
<!-- 公司信息 -->
<div id="apply_company_info" class="apply-company-info">
  <div class="alert">
    <h4>注意事项：</h4>
    以下所需要上传的电子版资质文件仅支持JPG\GIF\PNG格式图片，大小请控制在1M之内。</div>
  <form id="form_company_info" action="index.php?act=store_joinin_c2c&op=step2" method="post" enctype="multipart/form-data" >
    <table border="0" cellpadding="0" cellspacing="0" class="all">
      <thead>
        <tr>
          <th colspan="2">店铺及联系人信息</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th><i>*</i>店铺名称：</th>
          <td><input name="company_name" type="text" class="w200" value="<?php echo $output['joinin_detail']['company_name'];?>"/>
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>所在地：</th>
          <td><input id="company_address" name="company_address" type="hidden" value="<?php echo $output['joinin_detail']['company_address'];?>"/>
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>详细地址：</th>
          <td><input name="company_address_detail" type="text" class="w200"  value="<?php echo $output['joinin_detail']['company_address_detail'];?>">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>联系人姓名：</th>
          <td><input name="contacts_name" type="text" class="w100"  value="<?php echo $output['joinin_detail']['contacts_name'];?>"/>
            <span></span>
          </td>
        </tr>
        <tr>
          <th><i>*</i>联系人手机：</th>
          <td><input id="contacts_phone" name="contacts_phone" type="text" class="w100" value="<?php echo $output['joinin_detail']['contacts_phone'];?>"/>
            <span></span>
              <a href="javascript:void(0);" id="send_auth_code" class="ncm-btn ml5"><span id="sending" style="display: none;">正在</span><span class="send_success_tips" style="display: none;"><strong id="show_times" class="red mr5"></strong>秒后再次</span>获取短信验证码</a>
              <p class="send_success_tips hint mt10">“安全验证码”已发出，请注意查收，请在<strong>“30分种”</strong>内完成验证。</p>
          </td>
        </tr>
        <tr>
            <th><i>*</i>短信验证码：</th>
            <td><input type="text" class="w100" maxlength="6" value="" name="vcode" id="vcode" />
            <span></span></td>
        </tr>
        <tr>
            <th><i>*</i>电子邮箱：</th>
            <td><input name="contacts_email" type="text" class="w200"  value="<?php echo $output['joinin_detail']['contacts_email'];?>"/>
            <span></span></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" class="all">
      <thead>
        <tr>
          <th colspan="20">身份证信息</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th><i>*</i>姓名：</th>
          <td><input name="business_sphere" type="text" class="w100"  value="<?php echo $output['joinin_detail']['business_sphere'];?>"/>
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>身份证号：</th>
          <td><input name="business_licence_number" type="text" class="w200"  value="<?php echo $output['joinin_detail']['business_licence_number'];?>"/>
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>手执身份证照片：</th>
          <td><input name="business_licence_number_electronic" type="file" class="w200" /><input name="business_licence_number_electronic2" type="hidden"  value="<?php echo $output['joinin_detail']['business_licence_number_electronic'];?>" />
          	<img border="0" alt="手执身份证照范例" src="<?php if($output['joinin_detail']['business_licence_number_electronic'] !=''){echo UPLOAD_SITE_URL.'/shop/store_joinin/'.$output['joinin_detail']['business_licence_number_electronic'];}else{ echo SHOP_TEMPLATES_URL.'/images/example.jpg';}?>" style="width:300px;height:210px">
            <span class="block">请确保图片清晰，身份证上文字可辨（清晰照片也可使用）。</span></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>
  </form>
  <div class="bottom"><a id="btn_apply_company_next" href="javascript:;" class="btn">下一步，提交财务资质信息</a></div>
</div>
