<?php defined('InShopNC') or exit('Access Invalid!');?>
<!--<script src="--><?php //echo RESOURCE_SITE_URL;?><!--/js/common_select.js"></script>-->

<div class="ncc-main">
    <div class="ncc-title">
        <h3>诸暨村淘站内支付</h3>
        <h5>订单详情内容可通过查看<a href="index.php?act=member_order" target="_blank">我的订单</a>进行核对处理。</h5>
    </div>
    <form action="index.php?act=buy&op=cuntaopayOK" method="POST" id="pay_form">
        <input type="hidden" name="pay_sn" value="<?php echo $output['pay_info']['pay_sn'];?>">
        <input type="hidden" id="payment_code" name="payment_code" value="predeposit">
        <div class="ncc-receipt-info">
            <div class="ncc-receipt-info-title">
                <h3><?php echo $output['order_remind'];?>
                    <?php if ($output['pay_amount_online'] > 0) {?>
                        在线支付金额：<strong>￥<?php echo $output['pay_amount_online'];?></strong>
                    <?php } ?>
                </h3>
            </div>
            <table class="ncc-table-style">
                <thead>
                <tr>
                    <th class="w50"></th>
                    <th class="w200 tl">订单号</th>
                    <th class="tl w150">支付方式</th>
                    <th class="tl">金额</th>
                </tr>
                </thead>
                <tbody>
                <?php if (count($output['order_list'])>1) {?>
                    <tr>
                        <th colspan="20">由于您的商品由不同商家发出，此单将分为<?php echo count($output['order_list']);?>个不同子订单配送！</th>
                    </tr>
                <?php }?>
                <?php foreach ($output['order_list'] as $key => $order) {?>
                    <tr>
                        <td></td>
                        <td class="tl"><?php echo $order['order_sn']; ?></td>
                        <td class="tl"><?php echo $order['payment_state'];?></td>
                        <td class="tl">￥<?php echo $order['order_amount'];?></td>
                    </tr>
                <?php  }?>
                </tbody>
            </table>
        </div>
        <div class="ncc-receipt-info">
            <table class="ncc-table-style">
                <tbody>
                <!-- S 预存款 & 充值卡 -->
                <?php if($output['available_pd_amount']+ $output['available_rcb_amount']>=$output['pay_amount_online']){?>
                    <tr id="pd_panel">
                        <td class="pd-account" colspan="20"><div class="ncc-pd-account">
                                <div class="mt5 mb5">
                                    <label>
                                        <input type="checkbox" class="vm mr5" value="1" name="cuntao_pay">
                                        站内余额支付（可用金额：<em><?php echo $output['available_rcb_amount']+$output['available_pd_amount'];?></em><?php echo $lang['currency_zh'];?>）</label>
                                </div>
                                <div id="pd_password" style="display:none;">支付密码：
                                    <input type="password" class="text w120" value="" name="password" id="pay-password" maxlength="35" autocomplete="off">
                                    <input type="hidden" value="" name="password_callback" id="password_callback">
                                    <a class="ncc-btn-mini ncc-btn-orange" id="pd_pay_submit" href="javascript:void(0)">使用</a>
                                    <?php if (!$output['member_paypwd']) {?>
                                        还未设置支付密码，<a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_security&op=auth&type=modify_paypwd" target="_blank">马上设置</a>
                                    <?php } ?>
                                </div>
                            </div></td>
                    </tr>
                <?php } else {?>
                <tr id="pd_panel_2">
                    <td class="pd-account" colspan="20"><div class="ncc-pd-account">
                            <div class="mt5 mb5">
                                当前可用余额：<em><?php echo $output['available_rcb_amount']+$output['available_pd_amount'];?></em><?php echo $lang['currency_zh'];?>
                                ，不够支付当前订单，请使用在线支付或者充值后再付款。
                            </div>
                        </div></td>
                </tr>
                <?php }?>
                <!-- E 预存款 -->

                </tbody>
            </table></div>
        <?php if($output['available_pd_amount']+ $output['available_rcb_amount']>=$output['pay_amount_online']){?>
            <div class="ncc-bottom tc mb50"><a href="javascript:void(0);" id="cuntaopay_button" class="ncc-btn ncc-btn-green"><i class="icon-shield"></i>确认支付</a></div>
        <?php } else {?>
            <div class="ncc-bottom tc mb50"><a href="index.php?act=predeposit&op=recharge_add" class="ncc-btn ncc-btn-orange" target="_blank"><i class="icon-shield"></i>在线充值</a></div>
        <?php }?>
    </form>
</div>
<script type="text/javascript">
    var SUBMIT_FORM = true;

    function submitPayForm(){
        if (!SUBMIT_FORM) return;
        if ((!$('input[name="cuntao_pay"]').attr('checked'))) {
            showDialog('请选择支付方式', 'error','','','','','','','','',2);
            return;
        }

        if (($('input[name="cuntao_pay"]').attr('checked')) && $('#password_callback').val() != '1') {
            showDialog('使用站内余额支付，需输入支付密码并使用  ', 'error','','','','','','','','',2);
            return;
        }
        SUBMIT_FORM = false;
        $('#pay_form').submit();
    }

    $(function(){
        $.ajaxSetup({
            async : false
        });
        function showPaySubmit() {
            if ($('input[name="cuntao_pay"]').attr('checked')) {
                $('#pay-password').val('');
                $('#password_callback').val('');
                $('#pd_password').show();
            } else {
                $('#pd_password').hide();
            }
        }

        $('#pd_pay_submit').on('click',function(){
            if ($('#pay-password').val() == '') {
                showDialog('请输入支付密码', 'error','','','','','','','','',2);return false;
            }
            $('#password_callback').val('');
            $.get("index.php?act=buy&op=check_pd_pwd", {'password':$('#pay-password').val()}, function(data){
                if (data == '1') {
                    $('#password_callback').val('1');
                    $('#pd_password').hide();
                } else {
                    $('#pay-password').val('');
                    showDialog('支付密码码错误', 'error','','','','','','','','',2);
                }
            });
        });

        $('input[name="cuntao_pay"]').on('change',function(){
            showPaySubmit();
        });


        $(document).keydown(function(e) {
            if (e.keyCode == 13) {
                submitPayForm();
                return false;
            }
        });
        $('#cuntaopay_button').on('click',function(){submitPayForm()});

    });
</script>