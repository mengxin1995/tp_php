<?php
/**
 * 微信支付接口类
 *
 * 
 * by alphawu 村村兔
 */

define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));
require_once(BASE_PATH."/../../../../data/config/config.ini.php");

function submit(){
    session_save_path(BASE_PATH.'/../../../../data/session');
    session_start();
    global $config;

    define('WXN_APPID', $_SESSION['wxn_appid']);
    define('WXN_MCHID', $_SESSION['wxn_mchid']);
    define('WXN_KEY', $_SESSION['wxn_key']);
    define('WXN_SECRET', $_SESSION['wxn_secret']);

    require_once("WxPay.JsApiPay.php");

    //①、获取用户openid
    $tools = new JsApiPay();
    $openId = $tools->GetOpenid();

    //②、统一下单
    $input = new WxPayUnifiedOrder();
    $input->SetBody($_SESSION['pay_sn'].'订单');
    $input->SetAttach($_SESSION['order_type']);
    $input->SetOut_trade_no($_SESSION['pay_sn']);
    $input->SetTotal_fee($_SESSION['api_pay_amount']*100);
    $input->SetTime_start(date("YmdHis"));
    $input->SetTime_expire(date("YmdHis", time() + 600));
    $input->SetGoods_tag('');
    $input->SetNotify_url($config['mobile_site_url'].'/api/payment/wxpay/notify_url.php');
    $input->SetTrade_type("JSAPI");
    $input->SetProduct_id($_SESSION['pay_sn']);
    $input->SetOpenid($openId);
    $order = WxPayApi::unifiedOrder($input);
    $jsApiParameters = $tools->GetJsApiParameters($order);

    $html = <<<html

<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>村村兔微信支付</title>
<script type="text/javascript">
//调用微信JS api 支付
function jsApiCall()
{
WeixinJSBridge.invoke(
'getBrandWCPayRequest',
$jsApiParameters,
function(res){
if(res.err_msg == "get_brand_wcpay_request:ok" ) {
alert('支付完成');
history.back(-1);
}
}
);
}
</script>
<script type="text/javascript">
window.onload = function(){
if (typeof WeixinJSBridge == "undefined"){
if( document.addEventListener ){
document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
}else if (document.attachEvent){
document.attachEvent('WeixinJSBridgeReady', jsApiCall);
document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
}
}else{
jsApiCall();
}
};
</script>
</head>
<body>
</body>
</html>
html;


    echo $html;
    exit;
}
submit();
