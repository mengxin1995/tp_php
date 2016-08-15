<?php
/**
 * 微信支付通知地址
 *
 * 
 * by alphawu 村村兔
 */
error_reporting(7);
$_GET['act']	= 'payment';
$_GET['op']		= 'wxpay_notify';
require_once(dirname(__FILE__).'/../../../../index.php');