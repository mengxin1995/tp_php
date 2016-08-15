<?php defined('InShopNC') or exit('Access Invalid!');?>
<div class="ncc-bottom"><a href="javascript:void(0)" id='submitOrder' class="ncc-btn ncc-btn-acidblue fr"><?php echo $lang['cart_index_submit_order'];?></a> <a class="ncc-btn ncc-btn-acidblue fr" style="margin-right:2px;font-weight:900;background-color:#FFAA01;border-color:#FFAA01;">★★★重要提示：如果要使用【<span style="color:#FF0000;">F码、代金券</span>】，请在这一步操作，订单提交后将无法再使用。★★★</a> </div>
<script>
function submitNext(){
	if (!SUBMIT_FORM) return;

	if ($('input[name="cart_id[]"]').size() == 0) {
		showDialog('所购商品无效', 'error','','','','','','','','',2);
		return;
	}
    if ($('#address_id').val() == ''){
		showDialog('<?php echo $lang['cart_step1_please_set_address'];?>', 'error','','','','','','','','',2);
		return;
	}
	if ($('#buy_city_id').val() == '') {
		showDialog('有商品不支持该地区的配送,请返回重新选择商品', 'error','','','','','','','','',2);
		return;
	}
	if (($('input[name="cuntao_pay"]').attr('checked')) && $('#password_callback').val() != '1') {
		showDialog('使用站内余额支付，需输入支付密码并使用  ', 'error','','','','','','','','',2);
		return;
	}
	if ($('input[name="fcode"]').size() == 1 && $('#fcode_callback').val() != '1') {
		showDialog('请输入并使用F码', 'error','','','','','','','','',2);
		return;
	}
	//表单提交前,ajax获取自定义地址状态
	var address_id = $('#address_id').val();
	$.ajax({
		type:"GET",
		url:SITEURL+"/index.php?act=member_address&op=getAddressLockType_ajax",
		data:{address_id:address_id},
		dataType:"text",
		success:function(data){
			if(data==0||data==2){
				showDialog('操作失败，该自定义地址已被关闭,请返回重新修改', 'error','','','','','','','','',2);
				exit;
			}else if(data ==3){
				return;
			}
		}
	});
	SUBMIT_FORM = false;
	$('#order_form').submit();
}
$(function(){
    $(document).keydown(function(e) {
        if (e.keyCode == 13) {
        	submitNext();
        	return false;
        }
    });
	$('#submitOrder').on('click',function(){submitNext()});
	calcOrder();
});
</script>