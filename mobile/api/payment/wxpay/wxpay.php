<?php
/**
 * 微信支付接口类
 *
 *
 * by alphawu 村村兔
 */
defined('InShopNC') or exit('Access Invalid!');

require_once(BASE_ROOT_PATH.DS.'shop/api/payment/wxpay/log.php');
class wxpay{

    /**
     * 存放支付订单信息
     * @var array
     */
    private $_payment_info = array();
    private $_order_info = array();


    /**
     * 支付信息初始化
     * @param array $payment_info
     * @param array $order_info
     */
    public function __construct($payment_info = array(), $order_info = array()) {
        $this->_payment_info = $payment_info['payment_config'];
        $this->_order_info = $order_info;
    }

    public function submit(){
        $_SESSION['pay_sn'] = $this->_order_info['pay_sn'];
        $_SESSION['order_type'] = $this->_order_info['order_type'];
        $_SESSION['api_pay_amount'] = $this->_order_info['api_pay_amount'];

        $_SESSION['wxn_appid'] = $this->_payment_info['wxpay_appid'];
        $_SESSION['wxn_mchid'] = $this->_payment_info['wxpay_mchid'];
        $_SESSION['wxn_key'] = $this->_payment_info['wxpay_key'];
        $_SESSION['wxn_secret'] = $this->_payment_info['wxpay_secret'];

        $url = MOBILE_SITE_URL.DS.'api/payment/wxpay/wxpay_callback.php';

        header("Location:$url");

        exit;
    }
}
