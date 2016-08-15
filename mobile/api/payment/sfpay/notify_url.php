<?php
/**
 * 顺手付通知地址
 *
 * 
 * by by alphawu,cuncuntu.com,2015.12.31
 */
$_GET['act']	= 'payment';
$_GET['op']		= 'notify';
$_GET['payment_code'] = 'sfpay';

//赋值，方便后面合并使用支付宝验证方法
$_POST['out_trade_no'] = $_POST['orderId'];
$_POST['trade_no'] = $_POST['sfBusinessNo'];

require_once(dirname(__FILE__).'/../../../index.php');