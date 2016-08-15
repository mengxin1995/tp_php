<?php
/**
 * 订单搜索，跳转到诸暨村淘物流
 *
 * by zjcuntao.com
*/


defined('InShopNC') or exit('Access Invalid!');

class order_listControl extends BaseHomeControl {
	/**
	 * 订单搜索
	 */
	public function indexOp(){		
		
		$keyword = trim($_GET['keyword']);
		$url="http://wuliu.cuncuntu.com/index.php?g=&m=order&a=s&order_id=".$keyword;
		header("Location: $url");
		//确保重定向后，后续代码不会被执行
		exit;
	}
}