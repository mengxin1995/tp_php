<?php
/**
 * 退货管理
 *
 *
 *
 **by 好商城V3 www.33hao.com 运营版*/

defined('InShopNC') or exit('Access Invalid!');
class returnControl extends SystemControl{
    /**
     * 每次导出退货订单数量
     * @var int
     */
    const EXPORT_SIZE = 1000;

	public function __construct(){
		parent::__construct();
		$model_refund = Model('refund_return');
		$model_refund->getRefundStateArray();
	}

	/**
	 * 待处理列表
	 */
	public function return_manageOp() {
		$model_refund = Model('refund_return');
		$condition = array();
		$condition['refund_state'] = '2';//状态:1为处理中,2为待管理员处理,3为已完成

		$keyword_type = array('order_sn','refund_sn','store_name','buyer_name','goods_name');
		if (trim($_GET['key']) != '' && in_array($_GET['type'],$keyword_type)) {
			$type = $_GET['type'];
			$condition[$type] = array('like','%'.$_GET['key'].'%');
		}
		if (trim($_GET['add_time_from']) != '' || trim($_GET['add_time_to']) != '') {
			$add_time_from = strtotime(trim($_GET['add_time_from']));
			$add_time_to = strtotime(trim($_GET['add_time_to']));
			if ($add_time_from !== false || $add_time_to !== false) {
				$condition['add_time'] = array('time',array($add_time_from,$add_time_to));
			}
		}
		$return_list = $model_refund->getReturnList($condition,10);

		Tpl::output('return_list',$return_list);
		Tpl::output('show_page',$model_refund->showpage());
		Tpl::showpage('return_manage.list');
	}

	/**
	 * 所有记录
	 */
	public function return_allOp() {
		$model_refund = Model('refund_return');
		$condition = array();

		$keyword_type = array('order_sn','refund_sn','store_name','buyer_name','goods_name');
		if (trim($_GET['key']) != '' && in_array($_GET['type'],$keyword_type)) {
			$type = $_GET['type'];
			$condition[$type] = array('like','%'.$_GET['key'].'%');
		}
		if (trim($_GET['add_time_from']) != '' || trim($_GET['add_time_to']) != '') {
			$add_time_from = strtotime(trim($_GET['add_time_from']));
			$add_time_to = strtotime(trim($_GET['add_time_to']));
			if ($add_time_from !== false || $add_time_to !== false) {
				$condition['add_time'] = array('time',array($add_time_from,$add_time_to));
			}
		}
		$return_list = $model_refund->getReturnList($condition,10);
		Tpl::output('return_list',$return_list);
		Tpl::output('show_page',$model_refund->showpage());
		Tpl::showpage('return_all.list');
	}

	/**
	 * 退货处理页
	 *
	 */
	public function editOp() {
		$model_refund = Model('refund_return');
		$condition = array();
		$condition['refund_id'] = intval($_GET['return_id']);
		$return_list = $model_refund->getReturnList($condition);
		$return = $return_list[0];
		if (chksubmit()) {
			if ($return['refund_state'] != '2') {//检查状态,防止页面刷新不及时造成数据错误
				showMessage(Language::get('nc_common_save_fail'));
			}
			$order_id = $return['order_id'];
			$refund_array = array();
			$refund_array['admin_time'] = time();
			$refund_array['refund_state'] = '3';//状态:1为处理中,2为待管理员处理,3为已完成
			$refund_array['admin_message'] = $_POST['admin_message'];
			$state = $model_refund->editOrderRefund($return);
			if ($state) {
			    $model_refund->editRefundReturn($condition, $refund_array);
			    $this->log('退货确认，退货编号'.$return['refund_sn']);

			    // 发送买家消息
                $param = array();
                $param['code'] = 'refund_return_notice';
                $param['member_id'] = $return['buyer_id'];
                $param['param'] = array(
                    'refund_url' => urlShop('return_id', 'view', array('return_id' => $return['refund_id'])),
                    'refund_sn' => $return['refund_sn']
                );
                QueueClient::push('sendMemberMsg', $param);

				showMessage(Language::get('nc_common_save_succ'),'index.php?act=return&op=return_manage');
			} else {
				showMessage(Language::get('nc_common_save_fail'));
			}
		}
		Tpl::output('return',$return);
		$info['buyer'] = array();
	    if(!empty($return['pic_info'])) {
	        $info = unserialize($return['pic_info']);
	    }
		Tpl::output('pic_list',$info['buyer']);
		Tpl::showpage('return.edit');
	}

	/**
	 * 退货记录查看页
	 *
	 */
	public function viewOp() {
		$model_refund = Model('refund_return');
		$condition = array();
		$condition['refund_id'] = intval($_GET['return_id']);
		$return_list = $model_refund->getReturnList($condition);
		$return = $return_list[0];
		Tpl::output('return',$return);
		$info['buyer'] = array();
	    if(!empty($return['pic_info'])) {
	        $info = unserialize($return['pic_info']);
	    }
		Tpl::output('pic_list',$info['buyer']);
		Tpl::showpage('return.view');
	}

	/**
	 * 导出
	 *
	 */
	public function export_step1Op(){
		$model_refund = Model('refund_return');
		$condition = array();

		$keyword_type = array('order_sn','refund_sn','store_name','buyer_name','goods_name');
		if (trim($_GET['key']) != '' && in_array($_GET['type'],$keyword_type)) {
			$type = $_GET['type'];
			$condition[$type] = array('like','%'.$_GET['key'].'%');
		}
		if (trim($_GET['add_time_from']) != '' || trim($_GET['add_time_to']) != '') {
			$add_time_from = strtotime(trim($_GET['add_time_from']));
			$add_time_to = strtotime(trim($_GET['add_time_to']));
			if ($add_time_from !== false || $add_time_to !== false) {
				$condition['add_time'] = array('time',array($add_time_from,$add_time_to));
			}
		}

		if (!is_numeric($_GET['curpage'])){
			$count = $model_refund->getReturnCount($condition);
			$array = array();
			if ($count > self::EXPORT_SIZE ){	//显示下载链接
				$page = ceil($count/self::EXPORT_SIZE);
				for ($i=1;$i<=$page;$i++){
					$limit1 = ($i-1)*self::EXPORT_SIZE + 1;
					$limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
					$array[$i] = $limit1.' ~ '.$limit2 ;
				}
				Tpl::output('list',$array);
				Tpl::output('murl','index.php?act=return&op=return_all');
				Tpl::showpage('export.excel');
			}else{	//如果数量小，直接下载
				$data = $model_refund->getReturnListForExport($condition,self::EXPORT_SIZE);
				$this->createExcel($data);
			}
		}else{	//下载
			$limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
			$limit2 = self::EXPORT_SIZE;
			$data = $model_refund->getReturnListForExport($condition,"{$limit1},{$limit2}");
			$this->createExcel($data);
		}
	}

	/**
	 * 生成excel
	 *
	 * @param array $data
	 */
	private function createExcel($data = array()){
		Language::read('export');
		import('libraries.excel');
		$excel_obj = new Excel();
		$excel_data = array();
		//设置样式
		$excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
		//header
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_return_order_no'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_return_no'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_return_store'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_return_goods'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_return_buyer'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_return_time'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_return_seller_time'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_return_admin_time'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_return_amount'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_return_account'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_return_seller_approve'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_return_admin_approve'));
		//data

        $seller_state = array(1=>Language::get('exp_seller_state_1') , 2=>Language::get('exp_seller_state_2'),3=>Language::get('exp_seller_state_3'));
        $admin_array = array(1=>Language::get('exp_refund_state_1') , 2=>Language::get('exp_refund_state_2'),3=>Language::get('exp_refund_state_3'));

		foreach ((array)$data as $k=>$v){
			$tmp = array();
			$tmp[] = array('data'=>'NC'.$v['order_sn']);
			$tmp[] = array('data'=>'NC'.$v['refund_sn']);
			$tmp[] = array('data'=>$v['store_name']);
			$tmp[] = array('data'=>$v['goods_name']);
			$tmp[] = array('data'=>$v['buyer_name']);
			$tmp[] = array('data'=>date('Y-m-d H:i:s',$v['add_time']));
			$tmp[] = array('data'=>date('Y-m-d H:i:s',$v['seller_time']));
			$tmp[] = array('data'=>date('Y-m-d H:i:s',$v['admin_time']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['refund_amount']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['goods_num']));
			$tmp[] = array('data'=>$seller_state[$v['seller_state']]);
			$tmp[] = array('data'=>($v['seller_state'] == 2 && $v['refund_state'] >= 2) ? $admin_array[$v['refund_state']]:'无');
			$excel_data[] = $tmp;
		}
		$excel_data = $excel_obj->charset($excel_data,CHARSET);
		$excel_obj->addArray($excel_data);
		$excel_obj->addWorksheet($excel_obj->charset(L('exp_return_order'),CHARSET));
		$excel_obj->generateXML($excel_obj->charset(L('exp_return_order'),CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
	}
}
