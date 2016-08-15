<?php
/**
 * 支付宝返回地址
 *
 * 
 * by 好商城 www.33hao.com 开发
 */
$_GET['act']	= 'payment';
$_GET['op']		= 'return';
$_GET['payment_code'] = 'alipay';
$_GET['extra_common_param'] = 'pd_order';
require_once(dirname(__FILE__).'/../../../index.php');
?>