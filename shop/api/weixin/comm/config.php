<?php
/**
 * PHP SDK for weixin登录 OpenAPI
 *
 * @version 1.0
 * @author alphawu
 * @copyright © 2015, cuncuntu. All rights reserved.
 */

//
session_start();
//

//包含配置信息
//$data = rkcache("setting", true);
$data = require(BASE_DATA_PATH.DS.'cache'.DS.'setting.php');// by 33hao.com
//qq互联是否开启
if($data['weixin_isuse'] != 1){
	@header('location: index.php');
	exit;
}
$weixin_appid=trim($data['weixin_appid']);
$weixin_appkey=trim($data['weixin_appkey']);
if(!empty($_SESSION['m'])){
	$weixin_appid=trim($data['weixin_service_appid']);
	$weixin_appkey=trim($data['weixin_service_appkey']);
}

//申请到的appid
$_SESSION["appid"]    = $weixin_appid;

//申请到的appkey
$_SESSION["appkey"]   = $weixin_appkey;

//weixin登录成功后跳转的地址,请确保地址真实可用，否则会导致登录失败。
$_SESSION["callback"] = SHOP_SITE_URL."/api.php?act=toweixin&op=g";

//weixin授权api接口.按需调用
$_SESSION["scope"] = "snsapi_userinfo";

