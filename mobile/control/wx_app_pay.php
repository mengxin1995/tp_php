<?php
/**
 * 获取客户端微信支付相关信息 2016.01.05 cuncuntu.com
 * 需要修改配置文件
 * $config['wx_appid']  = '';
 * $config['wx_appkey'] = '4';
 * $config['wx_mchid']  = '';
 */

//use shopnc\Tpl;

defined('InShopNC') or exit('Access Invalid!');

class wx_app_payControl extends mobileMemberControl {

    public function __construct(){
        parent::__construct();
    }

    /*
     * 首页显示
     */
    public function indexOp(){
        output_error('禁止访问');
    }

    public function platOp(){

        $pay_sn = $_POST['pay_sn'];
        $payment_code = 'wxpay';
        $url = 'index.php?act=member_order';

        if(!preg_match('/^\d{18}$/',$pay_sn)){
            output_error('pay_sn error');
        }

        $logic_payment = Logic('payment');
        $result = $logic_payment->getPaymentInfo($payment_code);
        if(!$result['state']) {
            output_error($result['msg'].'error');
        }
        $payment_info = $result['data'];

        //计算所需支付金额等支付单信息
        $result = $logic_payment->getRealOrderInfo($pay_sn, $this->member_info['member_id']);
        if(!$result['state']) {
            output_error($result['msg'].'error');
        }

        if ($result['data']['api_pay_state'] || empty($result['data']['api_pay_amount'])) {
            output_error('not need pay');
        }

        define('WXN_APPID', C('wx_appid'));
        define('WXN_MCHID', C('wx_mchid'));
        define('WXN_KEY', C('wx_appkey'));
        //data 已按照ascii 排序key,否则还需要单独排序

        $signTime = TIMESTAMP;

        $prepayid = $this->getPrepayId($pay_sn);

        if(!$prepayid){
            output_error('get prepayid error');
        }



        $data = array(
            'appid'         => WXN_APPID,
            'package'       => 'Sign=WXPay',
            'partnerid'     => WXN_MCHID,
            'prepayid'      => $prepayid,
            'noncestr'     => '9d26f60817f83db3c7670d7287260123',
            'timestamp'     => $signTime,
        );
        ksort($data);
        reset($data);
        $arg = "";
        while (list ($key, $val) = each ($data)) {
            $arg	.= $key."=".$val."&";
        }

        $signTemp = $arg.'key='.WXN_KEY;
        $sign =strtoupper(MD5($signTemp));
        $outdata = array(
            'appid'         => WXN_APPID,
            'noncestr'      => '9d26f60817f83db3c7670d7287260123',
            'package'       => 'Sign=WXPay',
            'partnerid'     => WXN_MCHID,
            'prepayid'      => $prepayid,
            'timestamp'     => $signTime,
            'sign'          => $sign,
        );

        output_data($outdata);

    }


    private function getPrepayId($pay_sn){

        $model_order = Model('order');

        $condition = array(
            'pay_sn'    => $pay_sn
        );
        $order_list = $model_order->getOrderList($condition);

        if(empty($order_list)){
            return false;
        }

        $order_type ='real_order';
        $api_pay_amount = 0;

        $count = 0;

        foreach ( $order_list as $order) {
            $count++;
            $api_pay_amount += $order['order_amount'];
        }

        $subject = '村村兔['.$count.']笔订单';

        require_once '../shop/api/payment/wxpay/lib/WxPay.Api.php';
        require_once '../shop/api/payment/wxpay/log.php';

        $logHandler= new CLogFileHandler(BASE_DATA_PATH.'/log/wxpay/'.date('Y-m-d').'.log');
        $Logwx = Logwx::Init($logHandler, 15);

        //统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody($subject);
        $input->SetAttach($order_type);
        $input->SetOut_trade_no($pay_sn);
        $input->SetTotal_fee($api_pay_amount*100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 3600));
        $input->SetGoods_tag('');
        $input->SetNotify_url(SHOP_SITE_URL.'/api/payment/wxpay/notify_url.php');
        $input->SetTrade_type("APP");
        $input->SetProduct_id($pay_sn);
        $result = WxPayApi::unifiedOrder($input);
        //var_dump($result);
//         header("Content-type:text/html;charset=utf-8");
//         print_R($result);exit;
        $Logwx->DEBUG("unifiedorder-:" . json_encode($result));
        return $result["prepay_id"];
    }
}