<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="ncc-main">
  <div class="ncc-title">
    <h3><?php echo $lang['cart_index_payment'];?></h3>
    <h5>查看充值记录可以通过<a href="index.php?act=predeposit&op=index" target="_blank">我的充值列表 </a>进行查看。</h5>
  </div>
  <form action="index.php?act=payment&op=pd_order" method="POST" id="buy_form">
    <input type="hidden" name="pdr_sn" value="<?php echo $output['pdr_info']['pdr_sn'];?>">
    <input type="hidden" id="payment_code" name="payment_code" value="">
    <div class="ncc-receipt-info">
    <div>充值单号 : <?php echo $output['pdr_info']['pdr_sn'];?></div>
      <div class="ncc-receipt-info-title">
        <h3>您已申请账户余额充值，请立即在线支付！
          充值金额：<strong>￥<?php echo $output['pdr_info']['pdr_amount'];?></strong> ，优惠率是：<strong><?php echo $output['pdr_info']['pdr_rate'];?></strong>，实际充值金额是：<strong>￥<?php echo $output['pdr_info']['pdr_real_amount'];?></strong>。</h3>
      </div>
    </div>
    <div class="ncc-receipt-info">
      <?php if (!isset($output['payment_list'])) {?>
      <?php }else if (!empty($output['payment_list'])){ ?>
      <div class="ncc-receipt-info-title">
        <h3>支付选择</h3>
      </div>
      <ul class="ncc-payment-list">
        <?php foreach($output['payment_list'] as $val) { ?>
        <li payment_code="<?php echo $val['payment_code']; ?>">
          <label for="pay_<?php echo $val['payment_code']; ?>">
          <i></i>
          <div class="logo" for="pay_<?php echo $val['payment_id']; ?>"> <img src="<?php echo SHOP_TEMPLATES_URL?>/images/payment/<?php echo $val['payment_code']; ?>_logo.gif" /> </div>
          </label>
        </li>
        <?php } ?>
      </ul>
      <?php } ?>
    </div>
    <div class="ncc-bottom tc mb50"><a href="javascript:void(0);" id="next_button" class="ncc-btn ncc-btn-green"><i class="icon-shield"></i>确认提交支付</a></div>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $('.ncc-payment-list > li').on('click',function(){
    	$('.ncc-payment-list > li').removeClass('using');
        $(this).addClass('using');
        $('#payment_code').val($(this).attr('payment_code'));
    });
    $('#next_button').on('click',function(){
        if ($('#payment_code').val() != '') {
            $('#buy_form').submit();
        }
    });
});
</script>