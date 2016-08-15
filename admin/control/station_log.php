<?php
/**
 * 站长上报日志
 *
 *
 *
 **by alphawu 村村兔 2015.12.24
 */

defined('InShopNC') or exit('Access Invalid!');

class station_logControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('station_log');
	}

	/**
	 *
	 * 屏保列表
	 */
	public function indexOp(){
		$mode_sl  = Model('station_log');

		/**
		 * 分页
		 */
		$page	 = new Page();
		$page->setEachNum(20);
		$page->setStyle('admin');
		$condition = array();

		$limit     = '';
		$orderby   = 'id desc';
		$sl_info = $mode_sl->getList($condition,$page,$orderby,$limit);
		Tpl::output('sl_info',$sl_info);
		Tpl::output('page',$page->show());
		Tpl::showpage('station_log.index');
	}
}
