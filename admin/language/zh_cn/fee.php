<?php
defined('InShopNC') or exit('Access Invalid!');
/**
 * 导航及全局
 */
$lang['fee_index_manage']	= '运费模板管理';
$lang['fee_manage']	= '运费模板管理';
$lang['fee_add']	= '新增运费模板';
$lang['fee_edit']	= '编辑运费模板';
$lang['fee_change']	= '修改';
$lang['fee_delete']	= '删除';
$lang['fee_all']	= '全部';
/**
 * 运费模板
 */
$lang['fee_name']	         = '模板名称';
$lang['fee_send_area']	     = '发货区域';
$lang['fee_receive_area']    = '收货区域';
$lang['fee_is_use']			 = '是否启用';
$lang['fee_goods_id']		 = '运费商品编号';
$lang['fee_in_use']			 = '启用';
$lang['fee_not_in_use']      = '不启用';
$lang['fee_min']             = '满多少免运费';


/**
 * 提示信息
 */
$lang['fee_name_can_not_null']	            = '模板名称不能为空';
$lang['fee_send_area_can_not_null']	        = '发货区域不能为空';
$lang['fee_receive_area_can_not_null']	    = '收货区域不能为空';
$lang['fee_goods_id_can_not_null']	        = '运费商品编号不能为空';
$lang['fee_goods_id_must_be_digit']	        = '运费商品编号必须是数字';
$lang['fee_goods_id_not_exist']	            = '运费商品不存在';
$lang['fee_area_can_not_be_same']	        = '发货区域与收货区域不能相同';
$lang['fee_add_success']	                = '运费模板添加成功';
$lang['fee_add_fail']	                    = '运费模板添加失败';
$lang['fee_del_success']	                = '运费模板删除成功';
$lang['fee_del_fail']	                    = '运费模板删除失败';
$lang['fee_edit_success']	                = '运费模板更新成功';
$lang['fee_edit_fail']	                    = '运费模板更新失败';
$lang['fee_exist']	                        = '区域之间的运费模板已经存在';
$lang['fee_not_exist']	                    = '运费模板不存在';

$lang['fee_help']	                        = '显示不同区域直接的运费信息';
$lang['fee_goods_help']	                    = '运费商品必须是不能购买的商品,如果运费商品跟用户购买的商品同事出现在订单里面,将会出现无法预料的错误.建议新建一个运费的店铺,运费商品都加入该店铺.';

$lang['fee_del_sure']	                    = '确定要删除所选模板么?';
$lang['fee_isuse_open']	                    = '运费模板开启';
$lang['fee_isuse_close']	                = '运费模板关闭';
$lang['feeSettings_notice']	                = '只有开启,下面的设置才会有效';
$lang['fee_min_notice']	                    = '输入最低商品价值,超出才会收取运费';