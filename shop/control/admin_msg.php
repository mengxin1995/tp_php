<?php
/**
 * 客服中心
 *
 *
 *
 **by 好商城V3 www.33hao.com 运营版*/


defined('InShopNC') or exit('Access Invalid!');

class admin_msgControl extends BaseSellerControl {
	public function __construct() {
		parent::__construct();
		Language::read('member_store_index');
	}
	public function indexOp(){
	    $model_store = Model('store');
		$condition = array();
		$condition['store_id'] = $_SESSION['store_id'];
		$fields = 'store_msg';
		$ans = $model_store->table('store')->field($fields)->where($condition)->find();
		//读完数据标记为已读
		$data = array('is_read' => 1);
		$update = $model_store->table('store')->where($condition)->update($data);
		if ($update) {
			//更新缓存
			QueueClient::push('delOrderCountCache',$condition);
			// redirect('index.php');
		}

		Tpl::output('msg', $ans);
		Tpl::showpage('admin_msg');
	}

	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
	 */
	private function profile_menu($menu_key) {
		$menu_array	= array(
			1=>array('menu_key'=>'store_callcenter','menu_name'=>Language::get('nc_member_path_store_callcenter'),'menu_url'=>'index.php?act=store_callcenter'),
		);
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}
