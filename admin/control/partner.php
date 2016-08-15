<?php
/**
 * 服务站管理
 **by njq  shop.cuncuntu.com  运营版*/

defined('InShopNC') or exit('Access Invalid!');
class partnerControl extends SystemControl{
	const EXPORT_SIZE = 1000;
	public function __construct(){
		parent::__construct();
		Language::read('partner');
	}
	/**
	 * 服务站管理
	 */
	public function partnerOp()
	{
		$model_pt = Model('store_joinin_partner');
		/**
		 * 删除
		 */
		if (chksubmit()){
			if (!empty($_POST['partner_id']) && is_array($_POST['partner_id']) ){
				$result = $model_pt->drop(array('partner_id'=>array('in',$_POST['partner_id'])));
					showMessage('删除成功');

			}
			showMessage('删除失败');
		}

		/**
		 * 查询条件
		 */
		$condition = array();
		$if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
		$if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
		$start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
		$end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
		if ($start_unixtime || $end_unixtime) {
			$condition['pdr_add_time'] = array('time',array($start_unixtime,$end_unixtime));
		}
		if (!empty($_GET['partner_name'])){
			$condition['partner_name'] = array('like', '%' . trim($_GET['partner_name']) . '%');
		}
		if (!empty($_GET['partner_address'])){
			$condition['partner_address'] = array('like', '%' . trim($_GET['partner_address']) . '%');
		}
		if ($_GET['paystate_search'] != ''){
			$condition['paystate_search'] = $_GET['paystate_search'];
		}

		switch ($_GET['type']) {
			// 已审核服务站
			case 'lockup':
				$recharge_list = $model_pt->getLockUpList($condition);
				break;
			// 等待审核服务站
			case 'waitverify':
				$recharge_list = $model_pt->getWaitVerifyList($condition, '*', 10, 'partner_id desc');
				break;
			// 全部服务站
			default:
				$recharge_list = $model_pt->getList($condition);
				break;
		}

		Tpl::output('list', $recharge_list);
		Tpl::output('page', $model_pt->showpage(2));
		Tpl::output('paystate_search_array', $this->get_paystate_search());
		switch ($_GET['type']) {
			// 已审核服务站
			case 'lockup':
				Tpl::showpage('partner.close');
				break;
			// 等待审核服务站
			case 'waitverify':
				Tpl::showpage('partner.verify');
				break;
			// 全部服务站
			default:
				Tpl::showpage('partner.index');
				break;
		}
	}

	private function get_paystate_search() {
		$paystate_search_array = array(
				STORE_PAYSTATE_SEARCH_NEW => '新申请',
				STORE_PAYSTATE_SEARCH_VERIFY_FAIL => '审核拒绝',
				STORE_PAYSTATE_SEARCH_FINAL => '审核成功',
		);
		return $paystate_search_array;
	}
	/**
	 * 审核详细页
	 */
	public function partner_detailOp(){
		$model_pt = Model('store_joinin_partner');
		$info = $model_pt->getOne(array('partner_id'=>$_GET['partner_id']));
		$info_title = '编辑';
		if(in_array(intval($info['paystate_search']), array(STORE_PAYSTATE_SEARCH_NEW))) {
			$info_title = '审核';
		}
		Tpl::output('info_title', $info_title);
		Tpl::output('info', $info);
		Tpl::showpage('pt.info');
	}

	/**
	 * 审核
	 */
	public function partner_verifyOp() {
		$model_pt = Model('store_joinin_partner');
		$partner_detail = $model_pt->getOne(array('partner_id'=>$_POST['partner_id']));
		$this->partner_verify_open($partner_detail);
	}
	private function partner_verify_open($partner_detail) {
		$model_pt = Model('store_joinin_partner');

		$param = array();
		$param['paystate_search'] = $_POST['verify_type'] === 'pass' ? STORE_PAYSTATE_SEARCH_FINAL : STORE_PAYSTATE_SEARCH_FINAL;
		$param['partner_message'] = $_POST['partner_message'];
		$model_pt->modify($param, array('partner_id'=>$_POST['partner_id']));
		if($_POST['verify_type'] === 'pass') {
			$param['paystate_search'] = 1;
			$model_pt->modify($param, array('partner_id'=>$_POST['partner_id']));
			$param['partner_message'] = $_POST['partner_message'];
				showMessage('审核成功','index.php?act=partner&op=partner');
			} else {
			$param['paystate_search'] = 2;
			$param['partner_message'] = $_POST['partner_message'];
			$model_pt->modify($param, array('partner_id'=>$_POST['partner_id']));
			showMessage('审核拒绝','index.php?act=partner&op=partner');
			}

		}
	/**
	 * 服务站删除
	 */
	public function recharge_delOp(){
		$pt_id = intval($_GET["partner_id"]);
		if ($pt_id <= 0){
			showMessage(Language::get('admin_partner_parameter_error'),'index.php?act=partner&op=partner','','error');
		}
		$model_pt = Model('store_joinin_partner');
		$condition = array();
		$condition['partner_id'] = $pt_id;
		$result = $model_pt->drop($condition);
		if ($result){
			showMessage(Language::get('admin_partner_recharge_del_success'),'index.php?act=partner&op=partner');
		}else {
			showMessage(Language::get('admin_partner_recharge_del_fail'),'index.php?act=partner&op=partner','','error');
		}
	}

}
