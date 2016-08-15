<?php
/**
 * 支付宝通知地址
 *
 * 
 * by 33hao 好商城V3  www.33hao.com 开发
 */
$_GET['act']	= 'payment';
$_GET['op']		= 'notify';
$_GET['payment_code'] = 'sfpay';

//赋值，方便后面合并使用支付宝验证方法
$_POST['out_trade_no'] = $_POST['orderId'];
$_POST['extra_common_param'] = $_POST['reserved'];
$_POST['trade_no'] = $_POST['bankOrderId'];

require_once(dirname(__FILE__).'/../../../index.php');
?>