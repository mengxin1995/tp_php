<?php
/**
 * 载入权限 v3-b12
 *
 * by 33hao 好商城V3  www.33hao.com 开发
 */
defined('InShopNC') or exit('Access Invalid!');
$_limit =  array(
	array('name'=>'监控', 'child'=>array(
		array('name'=>'节点监控', 'op'=>'store', 'act'=>'store'),
		array('name'=>'新建节点', 'op'=>'newcom_add', 'act'=>'store'),
		array('name'=>'分组管理', 'op'=>'store_fenzu', 'act'=>'store'),
		array('name'=>'状态变化记录', 'op'=>'com_state', 'act'=>'store'),
		)),
	array('name'=>'设置', 'child'=>array(
	    array('name'=>'节点采集设置', 'op'=>'cms_manage', 'act'=>'cms_manage'),
	    array('name'=>'异常报警规则', 'op'=>'xianshiguize', 'act'=>'store'),
		array('name'=>'新建规则', 'op'=>'store_yujing', 'act'=>'store'),
		)),
	array('name'=>$lang['nc_stat'], 'child'=>array(
			array('name'=>$lang['nc_statstore'], 'op'=>'newstore', 'act'=>'stat_store'),
	)),
);


if (C('cms_isuse') !== NULL){
	$_limit[] = array('name'=>日志, 'child'=>array(
		array('name'=>$lang['nc_admin_log'], 'op'=>'cms_manage', 'act'=>'cms_manage'),
		array('name'=>'数据备份', 'op'=>'list', 'act'=>'admin_log'),
		array('name'=>'新建规则', 'op'=>'db', 'act'=>'db'),
		));
}

return $_limit;
