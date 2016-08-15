<?php
/**
 * 支付宝返回地址
 *
 * 
 * by 33hao 好商城V3  www.33hao.com 开发
 */
$_GET['act']	= 'payment';
$_GET['op']		= 'return';
$_GET['payment_code'] = 'sfpay';

//var_dump($_POST);

//赋值，方便后面合并使用支付宝验证方法
$_GET['out_trade_no'] = $_POST['orderId'];
$_GET['extra_common_param'] = $_POST['reserved'];
$_GET['trade_no'] = $_POST['bankOrderId'];
$_GET['status'] = $_POST['status'];
require_once(dirname(__FILE__).'/../../../index.php');
?>