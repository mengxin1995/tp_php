<?php
/**
 * 微信平台初始化文件
 *
 * 微信板块初始化文件，引用框架初始化文件
 *
 * by alphawu 村村兔  www.cuncuntu.com 开发
 */
define('APP_ID','weixin');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));
if (!@include(dirname(dirname(__FILE__)).'/global.php')) exit('global.php isn\'t exists!');
if (!@include(BASE_CORE_PATH.'/33hao.php')) exit('33hao.php isn\'t exists!');

if (!@include(BASE_PATH.'/config/config.ini.php')){
	@header("Location: install/index.php");die;
}

//require(BASE_PATH.'/framework/function/function.php');
if (!@include(BASE_PATH.'/control/wechat.class.php')) exit('wechat.class.php isn\'t exists!');

Base::run();
