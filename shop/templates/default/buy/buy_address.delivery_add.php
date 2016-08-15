<?php defined('InShopNC') or exit('Access Invalid!');?>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js" type="text/javascript"></script>
<style>

    .alert { color: #C09853; background-color: #FCF8E3; padding: 9px 14px; margin: 10px auto; border: 1px solid #FBEED5; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);}
    .alert a { color: #927036; text-decoration: underline;}
    .alert h4 { font-size: 16px; font-weight: bold; line-height: 1.5em; margin-bottom: 2px;}
    .alert-success { color: #468847; background-color: #DFF0D8; border-color: #D6E9C6;}
    .alert-info { color: #3A87AD; background-color: #D9EDF7; border-color: #BCE8F1;}
    .alert-error { color: #B94A48; background-color: #F2DEDE; border-color: #EED3D7;}
    .alert-block { padding-top: 14px; padding-bottom: 14px;}
    .alert ul { font-size: 12px;}
    .alert li { margin-bottom: 6px;}
    .alert li em { font-weight: 600; color: #F30; margin: 0 2px;}
    .alert i { font-size: 14px; margin-right: 4px; vertical-align: middle;}

    /* 表单项属性------------------------------------------- */
    /*input[type="text"], input[type="password"], input.text, input.password { font: 12px/20px Arial; color: #777; background-color: #FFF; vertical-align: top; display: inline-block; height: 20px; padding: 4px; border: solid 1px #CCC; outline: 0 none;}*/
    /*input[type="text"]:focus, input[type="password"]:focus, input.text:focus, input.password:focus, textarea:focus { color: #333; border-color: #75B9F0; box-shadow: 0 0 0 2px rgba(82, 168, 236, 0.15); outline: 0 none;}*/
    /*input[type="text"].error, input[type="password"].error, textarea.error { border-color: #ED6C4F; box-shadow: 0 0 0 2px rgba(232, 71, 35, 0.15); outline: 0 none;}*/
    /*textarea, .textarea { font: 12px/18px Arial; color: #777; background-color: #FFF; vertical-align: top; display: inline-block; height: 54px; padding: 4px; border: solid 1px #CCC; outline: 0 none;}*/
    select { color: #777; background-color: #FFF; height: 30px; vertical-align: middle; *display: inline; padding: 4px; border: solid 1px #CCC; *zoom:1;}
    /*select option, .select option { line-height: 20px; display: block; height: 20px; padding: 4px;}*/
    /*.submit-border { display: inline-block; *display: inline*//*IE6,7*//*; border: solid 1px; border-color: #52A452 #52A452 #448944 #52A452; zoom:1;}*/
    /*input[type="submit"], input.submit, a.submit { font-size: 12px; font-weight: bold; color: #FFF; text-shadow: 0 -1px 0 rgba(0,0,0,0.1); background-color: #5BB75B; display: block; height: 30px; padding: 0 20px 2px 20px; border: 0; cursor: pointer; }*/
    /*.submit-border:hover { borderd-color: #499249 #499249 #3D7A3D #499249;}*/
    /*input[type="submit"]:hover, input.submit:hover, a.submit:hover { text-decoration: none; color: #FFF; background-color: #51A351;}*/
    /*input[type="file"] { line-height:20px; background-color:#FBFBFB; height: 20px; border: solid 1px #D8D8D8; cursor: default;}*/
    /*.add-on { line-height: 28px; background-color: #E6E6E6; vertical-align: top; display: inline-block; text-align: center; width: 28px; height: 28px; border: solid #CCC; border-width: 1px 1px 1px 0}*/
    /*.add-on { *display: inline*//*IE6,7*//*; zoom:1;}*/
    /*.add-on i { font-size: 14px; color: #666; text-shadow: 1px 1px 0 #FFFFFF; *margin-top: 8px*//*IE7*//*;}*/

    /*表单验证错误提示文字*/
    label.error { font-size: 12px; color: #E84723; margin-left: 8px;}
    label.error i { margin-right: 4px;}

    /* 翻页样式 */
    .pagination { display: inline-block; margin: 0 auto;}
    .pagination ul { font-size: 0; *word-spacing:-1px/*IE6、7*/; }
    .pagination ul li { vertical-align: top; letter-spacing: normal; word-spacing: normal; display: inline-block; margin: 0 0 0 -1px;}
    .pagination ul li { *display: inline/*IE6、7*/; *zoom:1;}
    .pagination li span { font: normal 14px/20px "microsoft yahei"; color: #AAA; background-color: #FAFAFA; text-align: center; display: block; min-width: 20px; padding: 8px; border: 1px solid #E6E6E6; position: relative; z-index: 1;}
    .pagination li a span ,
    .pagination li a:visited span { color: #005AA0; text-decoration: none; background-color: #FFF; position: relative; z-index: 1;}
    .pagination li a:hover span, .pagination li a:active span{ color: #FFF; text-decoration: none !important; background-color: #D93600; border-color: #CA3300; position: relative; z-index: 9; cursor:pointer;}
    .pagination li a:hover { text-decoration: none;}
    .pagination li span.currentpage { color: #AAA; font-weight: bold; background-color: #FAFAFA; border-color: #E6E6E6; position: relative; z-index: 2;}

    .eject_con { background-color: #FFF; overflow: hidden;}
    .eject_con h3 { font: 14px/36px "microsoft yahei"; text-align: center; height: 36px; margin-top: 10px;}
    .eject_con dl { font-size: 0; *word-spacing:-1px/*IE6、7*/; line-height: 20px; clear: both; padding: 0; margin: 0; overflow: hidden;}
    .eject_con dl dt,
    .eject_con dl dd { font-size: 12px; line-height: 32px; vertical-align: top; letter-spacing: normal; word-spacing: normal; text-align: right; display: inline-block; width: 10%; padding: 10px 1% 0 0; margin: 0; *display: inline/*IE6,7*/; *zoom: 1;}
    .eject_con dl dt i.required { font: 12px/16px Tahoma; color: #F30; vertical-align: middle; margin-right: 4px;}
    .eject_con dl dd { text-align: left; width: 89%; padding: 10px 0 0 0; }
    .eject_con-list { margin-top: 4px;}
    .eject_con-list li { line-height: 24px;}
    .eject_con-list li .radio { vertical-align: middle; margin-right: 4px;}
    .eject_con .bottom { background-color:#F9F9F9; text-align: center; border-top: 1px solid #EAEAEA; margin-top:12px; }
    .eject_con .alert { margin: 5px;}
    .ncmc-delivery { background-color: #F5F5F5; min-height: 250px; padding: 10px 5px 0px 20px; margin: 10px 10px 0px 10px; position: relative; z-index: 1;}
    .ncmc-delivery ul { width: 570px; height: 200px;}
    .ncmc-delivery ul li { height: 20px; padding: 9px; margin: 1px;}
    .ncmc-delivery ul li.select { background:#FFF; border: solid 1px #ff966e; margin: 0;}
    .ncmc-delivery .delivery-map { background-color: #FFF; width: 250px; height: 250px; position: absolute; z-index: 1; top: 10px; right: 10px;}
    .ncmc-delivery .pagination { clear: both;}
    .ncmc-delivery .pagination ul { width: auto; height: auto;}
    .ncmc-delivery .pagination ul li { height: auto; padding: 0;}
    .ncmc-delivery .pagination ul li span { font-size: 12px; padding: 2px;}
    #region{height:30px;}
    .ncmc-address{background-color: #F5F5F5; height: 70px; padding: 10px 5px 0px 30px;margin:0px 10px 0px 10px; }
</style>
<div class="eject_con">
  <div class="adds">
    <div class="alert alert-success">
      <ul>
        <li>请选择一个你可以方便到达的提货点。</li>
      </ul>
    </div>
    <div id="warning"></div>
    <form method="post" action="index.php" id="address_form" target="_parent">
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="address_id" value="<?php echo $output['address_info']['address_id'];?>" />
      <dl>
        <dt><i class="required">*</i>地区选择：</dt>
        <dd>查找 <span id="region">
          <input type="hidden" value="<?php echo $output['address_info']['city_id'];?>" name="city_id" id="city_id">
          <select>
          </select>
                <!--增加自定义地址开始-->
                 <input type="hidden" name="area_id" id="area_id" class="area_ids"/>
                 <input type="hidden" name="area_info" id="area_info" class="area_names"/>
                <!--增加自定义地址结束-->
          </span>范围内的自提服务站。</dd>
      </dl>
      <div class="ncmc-delivery" id="zt_address" style="display: block;"></div>
        <!--增加自定义地址开始-->
        <label style="width: 960px;">
        <div id="address_body" style="display: none;" class="ncmc-address" >
            <div>
            <input type="radio"  nctype="address_radio"  name="dlyp_id" id="address_dlyp_id"  value="" <?php if(!empty($output['address_info']['address'])){?> checked<?php } ?>>
            <?php echo $lang['cart_step1_whole_address'].$lang['nc_colon'];?>
            <input type="text" class="text w500" name="address" id="address" maxlength="80" value="" onclick="javascript:document.getElementById('address_dlyp_id').checked=true;">
            <p class="hint"><?php echo $lang['cart_step1_true_address'];?></p>
            </div>
        </div>
        <div id="address_vaild" style="display: none; height: 30px;"class="ncmc-address" >
            <div>
                <p class="hint" style="color: red;padding-left: 30px;">您所选择的区域下暂不支持自定义地址！</p>
            </div>
        </div>
            </label>
        <!--增加自定义地址结束-->
      <dl>
        <dt><i class="required">*</i>收货人姓名<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input type="text" class="text w100" name="true_name" id="true_name" value="<?php echo $output['address_info']['true_name'];?>"/>
          <p class="hint"></p>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>电话号码<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input type="text" class="text w200" name="tel_phone" id="tel_phone" value="<?php echo $output['address_info']['tel_phone'];?>"/>
          <p class="hint">区号 - 电话号码 - 分机</p>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>手机<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input type="text" class="text w200" name="mob_phone" id="mob_phone" value="<?php echo $output['address_info']['mob_phone'];?>"/>
        </dd>
      </dl>
    </form>
  </div>
</div>
<script type="text/javascript">
var SITEURL = "<?php echo SHOP_SITE_URL; ?>";
$(document).ready(function(){
	regionInit("region");
    $('#address_form').on('change','select',function(){
        //只显示选择框的前两个部分，村就不显示了 zjcuntao
        var c = $('#region').find('select').size();
        if(c==4){
            $('#region').find('select').last().remove();
        }
        //end 只显示选择框的前两个部分，村就不显示了 zjcuntao

        //area_id = $('#address_form').find('select').last().val();
        area_id = $(this).val();//使用当前选择框的id
        if (area_id != '-请选择-') {
        	$('#zt_address').load("<?php echo SHOP_SITE_URL;?>/index.php?act=buy&op=delivery_list&dlyp_id=<?php echo $output['address_info']['dlyp_id'];?>&area_id="+area_id);
            /*增加自定义地址开始*/
            if (area_id != '-请选择-') {
                $.ajax({
                    type:'post',
                    url:"<?php echo SHOP_SITE_URL;?>/index.php?act=buy&op=add_ajax",
                    data:{area_id:area_id},
                    dataType:'json',
                    success:function(result){
                        $.each(result,function(i,value){
                            if(value.area_address==1)
                            {
                                $('#address_body').show();
                                $('#address_vaild').hide();
                            }else if(value.area_address==2){
                                $('#address_vaild').hide();
                                $('#address_body').hide();
                            }
                            else if(value.area_address==0){
                                $('#address_body').hide();
                                $('#address_vaild').show();
                            }
                        });

                    }
                });
            }
            /*增加自定义地址结束*/
        }
    });

    $('#zt_address').load("<?php echo SHOP_SITE_URL;?>/index.php?act=buy&op=delivery_list&dlyp_id=1");

    <?php if (intval($_GET['id'])) { ?>
    $('#zt_address').load("<?php echo SHOP_SITE_URL;?>/index.php?act=buy&op=delivery_list&dlyp_id=<?php echo $output['address_info']['dlyp_id'];?>&area_id=<?php echo $output['address_info']['area_id']?>");
    setTimeout("$('select').eq(0).val(<?php echo $output['address_info']['province_id']?>).change();",500);
    setTimeout("$('select').eq(1).val(<?php echo $output['address_info']['city_id']?>).change();",1000);
    setTimeout("$('select').eq(2).val(<?php echo $output['address_info']['area_id']?>);",1500);
    <?php } ?>
    $('#address_form').validate({
        rules : {
            dlyp_id : {
                required:true
            },
            true_name : {
                required : true
            },
            tel_phone : {
                required : check_phone,
                minlength : 6,
                maxlength : 20
            },
            mob_phone : {
                required : check_phone,
                minlength : 11,
                maxlength : 11,
                digits : true
            }
        },
        messages : {
            dlyp_id : {
                required : '【请选择一个自提点！】 '
            },
            true_name : {
                required : '请填写收货人姓名'
            },
            tel_phone : {
                required : '手机和电话至少填写一个',
                minlength: '请正确填写电话号码',
                maxlength: '请正确填写电话号码'
            },
            mob_phone : {
                required : '手机和电话至少填写一个',
                minlength: '请正确填写手机号',
                maxlength: '请正确填写手机号',
                digits : '请正确填写手机号'
            }
        },
        groups : {
            phone:'tel_phone mob_phone'
        }
    });
});
function check_phone(){
    return ($('input[name="tel_phone"]').val() == '' && $('input[name="mob_phone"]').val() == '');
}
function submitAddDeliveryAddr(){
    if ($('#address_form').valid()) {
        var intHot = $("input[name='dlyp_id']:checked").val();
        if (intHot !="") {
            var city_id = $('input[name=dlyp_id]:checked').attr('data_city_id');
            var area_id = $('input[name=dlyp_id]:checked').attr('data_area_id');
            var area_info = $('input[name=dlyp_id]:checked').attr('data_area');

            $('#buy_city_id').val($('input[name=dlyp_id]:checked').attr('data_city_id'));
            $('#city_id').val($('input[name=dlyp_id]:checked').attr('data_city_id'));
            $('#area_id').val($('input[name=dlyp_id]:checked').attr('data_area_id'));
            $('#area_info').val($('input[name=dlyp_id]:checked').attr('data_area_id'));
            //$('#address').val('');
            var datas = $('#address_form').serialize();
            $.post('index.php?act=buy&op=add_delivery', datas, function (data) {
                if (data.state) {
                    var true_name = $.trim($("#true_name").val());
                    var tel_phone = $.trim($("#tel_phone").val());
                    var mob_phone = $.trim($("#mob_phone").val());
                    showShippingPrice(city_id, area_id);
                    hideAddrList(data.addr_id, true_name, area_info, (mob_phone != '' ? mob_phone : tel_phone));
                } else {
                    alert(data.msg);
                    exit ;
                }
            }, 'json');
        }else if( intHot ==""){
            $('#city_id').val($('#region').find('select').eq(1).val());
            var datas = $('#address_form').serialize();
            $.post('index.php?act=buy&op=add_addr',datas,function(data){
                if (data.state){
                    var true_name = $.trim($("#true_name").val());
                    var tel_phone = $.trim($("#tel_phone").val());
                    var mob_phone = $.trim($("#mob_phone").val());
                    var area_info = $.trim($("#area_info").val());
                    var address = $.trim($("#address").val());
                    showShippingPrice($('#city_id').val(),$('#area_id').val());
                    hideAddrList(data.addr_id,true_name,area_info+'&nbsp;&nbsp;'+address,(mob_phone != '' ? mob_phone : tel_phone));
                }else{
                    alert(data.msg);
                    exit ;
                }
            },'json');
        }
        else{
            alert("保存失败！请输入详细地址");
            exit ;
        }
    }else {
        alert('保存失败,请仔细查看带*号的输入框是否填写！');
        exit ;
    }
}
</script>