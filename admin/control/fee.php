<?php
/**
 * 跨区域运费管理
 *
 *
 *
 *
 * by alphawu 村村兔 2016.01.11
 */

defined('InShopNC') or exit('Access Invalid!');

class feeControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('fee');
	}

	/**
	 *
	 * 管理员添加运费记录
	 */
	public function fee_addOp(){
		if(!chksubmit()){
		    $model_area  = Model('area');
		    /**
		     * 取顶级区域信息
		     */
		    $province_list = $model_area->getTopLevelAreas();
		    Tpl::output('province_list',$province_list);
		    Tpl::showpage('fee_add');
		}else{
			$lang = Language::getLangContent();
			$model_fee  = Model('fee');
			/**
			 * 验证
			 */
			$obj_validate = new Validate();
			$validate_arr = array();
			$validate_arr[] = array("input"=>$_POST["fee_name"], "require"=>"true", "message"=>$lang['fee_name_can_not_null']);
			$validate_arr[] = array("input"=>$_POST["send_area"], "require"=>"true", "message"=>$lang['fee_send_area_can_not_null']);
			$validate_arr[] = array("input"=>$_POST["receive_area"], "require"=>"true", "message"=>$lang['fee_receive_area_can_not_null']);
			$validate_arr[] = array("input"=>$_POST["fee_goods_id"], "require"=>"true", "message"=>$lang['fee_goods_id_can_not_null']);

			$obj_validate->validateparam = $validate_arr;
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}elseif($_POST["send_area"]==$_POST["receive_area"]){
				showMessage($lang['fee_area_can_not_be_same']);
			}else {
				$insert_array['title']       	= trim($_POST['fee_name']);
				$insert_array['addtime']  		= TIMESTAMP;
				$insert_array['stat']    		= $_POST['stat'];

				$send_area 						= intval($_POST['send_area']);
				$receive_area 					= intval($_POST['receive_area']);
				$goods_id						= intval($_POST['fee_goods_id']);

				$model_goods	= Model('goods');
				$condition_goods = array(
					'goods_id'		=> $goods_id
				);
				$goods_info		= $model_goods->getGoodsInfo($condition_goods);

				if(empty($goods_info)){
					showMessage($lang['fee_goods_id_not_exist']);
				}

				$insert_array['goods_id']  		= $goods_id;
				$insert_array['id2id'] 			= $send_area.'|'.$receive_area;
				$insert_array['send_area_id'] 	= $send_area;
				$insert_array['receive_area_id']= $receive_area;

				$condition = array(
					'id2id'		=> $send_area.'|'.$receive_area
				);

				$count = $model_fee->getFeeCount($condition);

				if($count>0){
					showMessage($lang['fee_exist']);
				}


				$result = $model_fee->addFee($insert_array);

			    if ($result){
					showMessage($lang['fee_add_success'],'index.php?act=fee&op=index');
				}else {
					showMessage($lang['fee_add_fail']);
				}
			}
	  	}
	}

	/**
	 *
	 * 列表
	 */
	public function indexOp(){
		$lang = Language::getLangContent();
		$model_fee  = Model('fee');
		/**
		 * 多选删除运费模板
		 */
	    if (chksubmit()){
			if (!empty($_POST['del_id'])){
			    foreach ($_POST['del_id'] as $v) {
					$model_fee->delFeeById($v);
			    }
			}
			showMessage($lang['fee_del_success']);
		}
		/**
		 * 显示广告位管理界面
		 */

		$condition = array();
		if(!empty($_GET['search_name']))
		{
			$condition['title'] = array('like','%'.$_GET['search_name'].'%');
		}


		$fee_list  = $model_fee->getFeeList($condition);

 		Tpl::output('fee_list',$fee_list);

		$stat = C('fee_isuse');
		$fee_min = C('fee_min');
 		Tpl::output('stat',$stat);
 		Tpl::output('fee_min',$fee_min);
		Tpl::showpage('fee_index');
	}

	public function statOp(){

		$model_setting = Model('setting');

		$update_array = array();
		$update_array['fee_isuse'] 	= $_POST['fee_isuse'];
		$update_array['fee_min'] 	=floatval($_POST['fee_min']);
		$result = $model_setting->updateSetting($update_array);
		if ($result === true){
			showMessage(Language::get('nc_common_save_succ'));
		}else {
			showMessage(Language::get('nc_common_save_fail'));
		}
	}

	/**
	 *
	 * 修改运费模板
	 */
	public function fee_editOp(){
		$lang = Language::getLangContent();
		if(!chksubmit()){
			$id = intval($_GET['fee_id']);

			$model_fee = Model('fee');

			$fee_info = $model_fee->getFeeById($id);
			if(empty($fee_info)){
				showMessage($lang['fee_not_exist']);
			}

			$model_area  = Model('area');
			/**
			 * 取顶级区域信息
			 */
			$province_list = $model_area->getTopLevelAreas();
			Tpl::output('province_list',$province_list);
			Tpl::output('fee_info',$fee_info);
			Tpl::showpage('fee_edit');
		}else{
			$model_fee  = Model('fee');
			/**
			 * 验证
			 */
			$obj_validate = new Validate();
			$validate_arr = array();
			$validate_arr[] = array("input"=>$_POST["fee_name"], "require"=>"true", "message"=>$lang['fee_name_can_not_null']);
			$validate_arr[] = array("input"=>$_POST["send_area"], "require"=>"true", "message"=>$lang['fee_send_area_can_not_null']);
			$validate_arr[] = array("input"=>$_POST["receive_area"], "require"=>"true", "message"=>$lang['fee_receive_area_can_not_null']);
			$validate_arr[] = array("input"=>$_POST["fee_goods_id"], "require"=>"true", "message"=>$lang['fee_goods_id_can_not_null']);

			$obj_validate->validateparam = $validate_arr;
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}elseif($_POST["send_area"]==$_POST["receive_area"]){
				showMessage($lang['fee_area_can_not_be_same']);
			}else {
				$update_array['title']       	= trim($_POST['fee_name']);
				$update_array['updatetime']  	= TIMESTAMP;
				$update_array['stat']    		= $_POST['stat'];

				$send_area 						= intval($_POST['send_area']);
				$receive_area 					= intval($_POST['receive_area']);
				$goods_id						= intval($_POST['fee_goods_id']);

				$model_goods	= Model('goods');
				$condition_goods = array(
					'goods_id'		=> $goods_id
				);
				$goods_info		= $model_goods->getGoodsInfo($condition_goods);

				if(empty($goods_info)){
					showMessage($lang['fee_goods_id_not_exist']);
				}

				$update_array['goods_id']  		= $goods_id;
				$update_array['id2id'] 			= $send_area.'|'.$receive_area;
				$update_array['send_area_id'] 	= $send_area;
				$update_array['receive_area_id']= $receive_area;


				$result = $model_fee->updateFee($update_array,$_POST['fee_id']);

				if ($result){
					showMessage($lang['fee_update_success'],'index.php?act=fee&op=index');
				}else {
					showMessage($lang['fee_update_fail']);
				}
			}
		}
	}

    /**
     *
     * 删除运费模板
     */
    public function fee_delOp(){
    	$lang = Language::getLangContent();
    	$model_fee  = Model('fee');
       /**
		 * 删除一个广告
		 */

		$result  = $model_fee->delFeeById(intval($_GET['fee_id']));

		if (!$result){
				showMessage($lang['fee_del_fail']);die;
			}else{
				showMessage($lang['fee_del_success']);die;
			}
    }

	/**
	 *
	 * ajaxOp
	 */
	public function ajaxOp(){
		switch ($_GET['branch']){
			case 'is_use':
				$model_fee = Model('fee');
				$param['stat']=intval($_GET['value']);
				$model_fee->updateFee($param,intval($_GET['id']));

				echo 'true';exit;
				break;
		}
	}
}
