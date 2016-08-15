<?php
defined('InShopNC') or exit('Access Invalid!');
/**
 * 转账功能
 */
$lang['admin_transfer_pay_account']	 		= '转出人账号';
$lang['admin_transfer_receiver_account']	= '转入人账号';
$lang['admin_transfer_pay_amount']	        = '转账金额';
$lang['admin_transfer_reason']	 	        = '操作原因';


$lang['transfer_tip1']	 	                = '该操作将转出账号的<b>充值卡</b>余额转入到转入账号的<b>现金</b>余额';
$lang['transfer_tip2']	 	                = '该操作只能由管理员执行';
$lang['transfer_tip3']	 	                = '转出账号和转入账号都必须存在';
$lang['transfer_tip4']	 	                = '转出账号的充值卡余额要大于等于转出金额';


$lang['transfer_pay_account_empty']         = '转出账号不能为空';
$lang['transfer_receiver_account_empty']    = '转入账号不能为空';
$lang['transfer_pay_amount_empty']          = '转出金额不能为空';
$lang['transfer_pay_amount_error']          = '转出金额必须是数字';
$lang['transfer_pay_amount_not_enough']     = '账号余额小于转出金额';
$lang['transfer_reason_empty']              = '操作原因不能为空';
$lang['transfer_pay_equal_receiver']        = '转出账号和转入账号不能相同';
$lang['transfer_pay_account_wrong']         = '当前账号不允许转出';

$lang['transfer_member_error']              = '转出账号或者转入账号不存在';
$lang['transfer_success']                   = '<b>转账成功</b>';
$lang['transfer_fail']                      = '<b>转账失败</b>';
