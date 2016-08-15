<?php defined('InShopNC') or exit('Access Invalid!');?>
<style>
    .ncmc-address{background-color: #F5F5F5; height: 70px; padding: 10px 5px 0px 20px;margin:0px 10px 0px 10px; }
</style>
<div class="eject_con">
  <div class="adds">
    <div class="alert alert-success">
      <ul>
     <!--   <li>当您需要对自己的收货地址保密或担心收货时间冲突时可使用该业务。添加后可在购物车中作为收货地址进行选择，货品将直接发送至自提服务站。 到货后短信、站内消息进行通知，届时您可使用“自提码”至该服务站兑码取货。</li>
     -->
      <li>请选择您方便到达的提货点</li>
      </ul>
    </div>
    <div id="warning"></div>
    <form method="post" action="index.php" id="address_form" target="_parent">
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="address_id" value="<?php echo $output['address_info']['address_id'];?>" />
      <dl>
        <dt style="width: 120px;"><i class="required" >*</i>地区选择：</dt>
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
      <div class="ncmc-delivery" id="zt_address"></div>
        <!--增加自定义地址开始-->
        <label style="width: 960px;">
            <div id="address_body" style="display: none;" class="ncmc-address" >
                <div>
                    <input type="radio"  nctype="address_radio"  name="dlyp_id" id="address_dlyp_id" value="" <?php if(!empty($output['address_info']['address'])){?> checked <?php } ?>>
                    <?php echo "详细地址".$lang['nc_colon'];?>
                    <input type="text" class="text w500" name="address" id="address" maxlength="80"  <?php if($output['address_info']['dlyp_id']==0){?>value="<?php echo $output['address_info']['address'];?>" <?php } ?> onclick="javascript:document.getElementById('address_dlyp_id').checked=true;">
                    <p class="hint">请填写真实地址，不需要重复填写所在地区</p>
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
          <input type="text" class="text w100" name="true_name" value="<?php echo $output['address_info']['true_name'];?>"/>
          <p class="hint"></p>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>电话号码<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input type="text" class="text w200" name="tel_phone" value="<?php echo $output['address_info']['tel_phone'];?>"/>
          <p class="hint">区号 - 电话号码 - 分机</p>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>手机<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input type="text" class="text w200" name="mob_phone" value="<?php echo $output['address_info']['mob_phone'];?>"/>
        </dd>
      </dl>
      <div class="bottom">
        <label class="submit-border">
          <input type="submit" class="submit" value="保存" />
        </label>
        <a class="ncm-btn ml5" href="javascript:DialogManager.close('daisou');">取消</a> </div>
    </form>
  </div>
</div>
<script type="text/javascript">
var SITEURL = "<?php echo SHOP_SITE_URL; ?>";
$(document).ready(function(){
	regionInit("region");
    $('#address_form').validate({
    	submitHandler:function(form){
                if ($('input[name="dlyp_id"]:checked').size() == 1) {
                    submitAddDeliveryAddr();
                    location=location;
                }
                else {
                    alert('请选择一个自提点或填写详细地址！');
                }
    	},
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
        	$('#zt_address').load("<?php echo SHOP_SITE_URL;?>/index.php?act=member_address&op=delivery_list&dlyp_id=<?php echo $output['address_info']['dlyp_id'];?>&area_id="+area_id);
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

    $('#zt_address').load("<?php echo SHOP_SITE_URL;?>/index.php?act=member_address&op=delivery_list&dlyp_id=1");

    <?php if (intval($_GET['id'])) { ?>
    $('#zt_address').load("<?php echo SHOP_SITE_URL;?>/index.php?act=member_address&op=delivery_list&dlyp_id=<?php echo $output['address_info']['dlyp_id'];?>&area_id=<?php echo $output['address_info']['area_id']?>");
    setTimeout("$('select').eq(0).val(<?php echo $output['address_info']['province_id']?>).change();",500);
    setTimeout("$('select').eq(1).val(<?php echo $output['address_info']['city_id']?>).change();",1000);
    setTimeout("$('select').eq(2).val(<?php echo $output['address_info']['area_id']?>);",1500);
    <?php } ?>
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

//表单提交前,ajax获取商品锁定状态
/*$('input[type="submit"]').click(function(e){
    var intHot = $("input[name='dlyp_id']:checked").val();
     area_idchk = <?php echo $output['address_info']['area_id']?>;
    if(intHot ==""){
    e.preventDefault();
    $.ajax({
      type:"GET",
      url:SITEURL+"/index.php?act=member_address&op=getAddressLockType_ajax",
      data:{area_idchk:area_idchk},
      dataType:"text",
       success:function(data){
           if(data==1){
               $("#address_form").submit();
           }
           else
             {
                /!* function winBack(){
                     history.back();
                 }*!/
                 showDialog("操作失败，该自定义地址已被关闭,请返回重新修改",'notice','','','','','','','','','');
             }
           }
     });
    }
});*/
</script>