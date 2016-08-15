<?php
/**
 * 闪购客户端api by zjcuntao.com
 */

//use shopnc\Tpl;

defined('InShopNC') or exit('Access Invalid!');


class zjcuntaoControl extends mobileHomeControl {

    public function __construct(){
        parent::__construct();
    }

    /*
     * 首页显示
     */
    public function indexOp(){
        output_error('禁止访问');
    }


	public function qrcodeOp(){
		$app = $_GET['app'];
		if($app=='yes'){

            Tpl::showpage('app_down');
            //header('Location:http://shop.cuncuntu.com/app/flashgo.apk');
			exit;
		}
		else{
			$member_id = intval($_GET['station_id']);
			$model_address = Model('address');

			$condition = array();
			$condition['member_id'] = $member_id;
			$address_info = $model_address->getDefaultAddressInfo($condition);
			if(!empty($member_id)) {
				output_data(array('address_info' => $address_info));
			} else {
				output_error('地址不存在');
			}
		}
	}

	 /**
     * 获取某个用户的一个地址信息
     */
    public function getAddressByMemberIDOp() {
		$member_id = intval($_POST['member_id']);

		$model_address = Model('address');

        $condition = array();
        $condition['member_id'] = $member_id;
        $address_info = $model_address->getDefaultAddressInfo($condition);
        if(!empty($address_info)) {
            output_data(array('address_info' => $address_info));
        } else {
            output_error('地址不存在');
        }
    }
    /*
     * 客户端重置支付密码
     */
    public function resetPaypwdOp(){
        $model_member = Model('member');
        $param = array();
        $param['member_paypwd'] = md5($_POST['paypwd']);//md5('111111');
        $condition = array();
        //$condition['member_id'] = $_POST['member_id'];
        $condition['member_name'] = $_POST['member_name'];
        $condition['member_passwd'] = md5($_POST['member_passwd']);

        $count = $model_member->getMemberCount($condition);
        if($count>0){
            $update = $model_member->editMember($condition,$param);
            if($update){
                output_data(array('data' => 'success'));
            }
            else{
                output_error('failed');
            }
        }
        else{
            output_error('wrong');
        }
    }
    /*
     * 客户端重置支付密码，用token验证
     */
    public function resetPaypwd2Op(){
        $model_member = Model('member');
        $param = array();
        $param['member_paypwd'] = md5($_POST['paypwd']);//md5('111111');
        $condition = array();
        //$condition['member_id'] = $_POST['member_id'];
        $condition['member_name'] = $_POST['member_name'];
        $condition['token'] = md5($_POST['token']);

        $model_mb_token = Model('mb_user_token');

        $user_token = $model_mb_token->getMbUserTokenInfo($condition);

        $count = $model_member->getMemberCount($condition);
        if($user_token){
            $condition2 = array();
            $condition2['member_name'] = $_POST['member_name'];

            $update = $model_member->editMember($condition2,$param);
            if($update){
                output_data(array('data' => 'success'));
            }
            else{
                output_error('failed');
            }
        }
        else{
            output_error('wrong');
        }
    }
	/**
	 * 注册
	 */
	public function registerOp(){
		$model_member	= Model('member');
        $register_info = array();
        $register_info['username'] = $_POST['mobile'];
        $register_info['username'] = $_POST['mobile'];
        $register_info['member_mobile'] = $_POST['mobile'];
        $register_info['password'] = $_POST['password'];
        $register_info['password_confirm'] = $_POST['password'];
        $register_info['member_paypwd'] = $_POST['pay_password'];
		$register_info['inviter_id'] = $_POST['station_id'];
		$register_info['member_truename'] = $_POST['name'];
        $register_info['email'] = $_POST['mobile'].'@zjcuntao.com';
        $member_info = $model_member->registerforzjcuntao($register_info);
        if(!isset($member_info['error'])) {
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], 'flash');//flash=闪购客户端
            if($token) {
				//添加一个默认地址
				try{
					$member_id = intval($_POST['station_id']);

					$model_address = Model('address');

					$condition = array();
					$condition['member_id'] = $member_id;
					$address_info = $model_address->getDefaultAddressInfo($condition);

					$data = array();
					$data['member_id'] = $member_info['member_id'];
					$data['true_name'] = $_POST['name'];
					$data['area_id'] = intval($address_info['area_id']);
					$data['city_id'] = intval($address_info['city_id']);
					$data['area_info'] = $address_info['area_info'];
					$data['address'] = $address_info['address'];
					$data['tel_phone'] = $_POST['mobile'];
					$data['mob_phone'] = $_POST['mobile'];
					$data['is_default'] = 1;

					$result = $model_address->addAddress($data);
				}
				catch (Exception $e) {

				}
				//var_dump($result);
				//end添加一个默认地址
                output_data(array('member_id'=>$member_info['member_id'],'username' => $member_info['member_name'], 'key' => $token));
            } else {
                output_error('注册失败');
            }
        } else {
			output_error($member_info['error']);
        }

    }

    /**
     * 平台充值卡
     */
    public function rechargecard_addOp()
    {
        $memberId = intval($_POST['member_id']);
        $memberName = $_POST['member_name'];
        $sn = (string) $_POST['rc_sn'];
        if (!$sn || strlen($sn) > 50) {
			output_error('平台充值卡卡号不能为空且长度不能大于50');
            exit;
        }

        try {
            model('predeposit')->addRechargeCardforzjcuntao($sn,$memberId,$memberName);
			output_data(array('data' => '平台充值卡使用成功'));
			exit;
        } catch (Exception $e) {
			output_error($e->getMessage());
            exit;
        }
    }

	/**
     * 登录生成token
     */
    private function _get_token($member_id, $member_name, $client) {
        $model_mb_user_token = Model('mb_user_token');

        //重新登录后以前的令牌失效
        //暂时停用
        //$condition = array();
        //$condition['member_id'] = $member_id;
        //$condition['client_type'] = $_POST['client'];
        //$model_mb_user_token->delMbUserToken($condition);

        //生成新的token
        $mb_user_token_info = array();
        $token = md5($member_name . strval(TIMESTAMP) . strval(rand(0,999999)));
        $mb_user_token_info['member_id'] = $member_id;
        $mb_user_token_info['member_name'] = $member_name;
        $mb_user_token_info['token'] = $token;
        $mb_user_token_info['login_time'] = TIMESTAMP;
        $mb_user_token_info['client_type'] = $client;

        $result = $model_mb_user_token->addMbUserToken($mb_user_token_info);

        if($result) {
            return $token;
        } else {
            return null;
        }

    }


	public function addAddressOp(){
		$model_address = Model('address');

		$data = array();
        $data['member_id'] = $_POST['member_id'];
        $data['true_name'] = $_POST['true_name'];
        $data['area_id'] = intval($_POST['area_id']);
        $data['city_id'] = intval($_POST['city_id']);
        $data['area_info'] = $_POST['area_info'];
        $data['address'] = $_POST['address'];
        $data['tel_phone'] = $_POST['tel_phone'];
        $data['mob_phone'] = $_POST['mob_phone'];
        $data['is_default'] = 1;

		$result = $model_address->addAddress($data);
        if($result) {
            output_data(array('address_id' => $result));
        } else {
            output_error('保存失败');
        }

	}


    public function cuntaoPayOp(){

        $key        = $_POST['key'];
        $pay_sn     = $_POST['pay_sn'];
        $code       = $_POST['code'];
        $type       = $_POST['type'];
        $model_token = Model('mb_user_token');

        $member_info = $model_token->getMbUserTokenInfoByToken($key);

        if(empty($member_info)){
            //key错误，用户不存在
            output_error('key错误,支付失败');
            return;
        }

        $logic_payment = Logic('payment');
        if($type=='vr'){
            $result = $logic_payment->getVrOrderInfo($pay_sn, $member_info['member_id']);
        }
        else {
            //重新计算所需支付金额
            $result = $logic_payment->getRealOrderInfo($pay_sn, $member_info['member_id']);
        }
        if(!$result['state']) {
            output_error($result['msg']);
        }


        $buyer_info	= Model('member')->getMemberInfoByID($member_info['member_id']);
        $condition = array();
        $condition['pay_sn'] = $pay_sn;
        $condition['order_state'] = ORDER_STATE_NEW;
        $order_list_needpay = Model('order')->getOrderList($condition);

        if(empty($order_list_needpay)){
            output_error('订单已经支付');
        }

        $logic_buy_1 = Logic('buy_1');


        $pay_amount=0.0;
        $avaliable_amount =0.0;
        foreach ($order_list_needpay as $key => $order_info) {
            $pay_amount += floatval($order_info['order_amount']);
        }

        $avaliable_amount = floatval($buyer_info['available_predeposit'])+floatval($buyer_info['available_rc_balance']);

        if($pay_amount>$avaliable_amount){
            output_error('余额不足，支付失败');
            return;
        }

        //使用村淘站内支付
        if (!empty($code)) {
            $order_list = $logic_buy_1->rcbPay($order_list_needpay, $_POST, $buyer_info);
            $logic_buy_1->pdPay($order_list ? $order_list :$order_list_needpay, $_POST, $buyer_info);
        }

        output_data('1');
    }

    /*
     * 提供手机端每日一品数据
     */
    public function getMeiRiYiPinOp(){
        $model_groupbuy = Model('groupbuy');

        $groupbuy_list = $model_groupbuy->getMeiRiYiPinMobile();

        $groupbuy_list_new = array();
        foreach($groupbuy_list as $k=>$v){
            $v['groupbuy_intro'] = '';
            $groupbuy_list_new[]=$v;
        }


        if($groupbuy_list){
            output_data(array('list'=>$groupbuy_list_new));
        }
        else
        {
            output_error('没有抢购信息');
        }
    }

    /**
     * 申请提现,代码来自shop/control/predeposit.php,2015.12.31
     */
    public function pd_cash_addOp(){
        $key        = $_POST['key'];
        $model_token = Model('mb_user_token');
        $member_info = $model_token->getMbUserTokenInfoByToken($key);
        $pdc_amount = abs(floatval($_POST['pdc_amount']));
        $model_pd = Model('predeposit');
        //验证支付密码
        if (md5($_POST['password']) != $member_info['member_paypwd']) {
            output_error('支付密码错误');
        }
        //验证金额是否足够
        if (floatval($member_info['available_predeposit']) < $pdc_amount){
            output_error('余额不足，提现失败');
        }
        try {
            $model_pd->beginTransaction();
            $pdc_sn = $model_pd->makeSn();
            $data = array();
            $data['pdc_sn'] = $pdc_sn;
            $data['pdc_member_id'] = $member_info['member_id'];
            $data['pdc_member_name'] = $member_info['member_name'];
            $data['pdc_amount'] = $pdc_amount;
            $data['pdc_bank_name'] = $_POST['pdc_bank_name'];
            $data['pdc_bank_no'] = $_POST['pdc_bank_no'];
            $data['pdc_bank_user'] = $_POST['pdc_bank_user'];
            $data['pdc_add_time'] = TIMESTAMP;
            $data['pdc_payment_state'] = 0;
            $insert = $model_pd->addPdCash($data);
            if (!$insert) {
                output_error('提现失败');
            }
            //冻结可用预存款
            $data = array();
            $data['member_id'] = $member_info['member_id'];
            $data['member_name'] = $member_info['member_name'];
            $data['amount'] = $pdc_amount;
            $data['order_sn'] = $pdc_sn;
            $model_pd->changePd('cash_apply',$data);
            $model_pd->commit();
            output_data('申请成功,等待审核');
        } catch (Exception $e) {
            $model_pd->rollback();
            output_error('提现失败2');
        }
    }
    /**
     * 地区列表
     */
    public function area_listOp() {
        $area_id = intval($_POST['area_id']);

        $model_area = Model('area');

        $condition = array();
        if($area_id > 0) {
            $condition['area_parent_id'] = $area_id;
        } else {
            $condition['area_deep'] = 1;
        }
        $area_list = $model_area->getAreaList($condition, 'area_id,area_name');
        output_data(array('area_list' => $area_list));
    }
    /**
     * 自提点列表
     */
    public function delivery_listOp() {
        $model_delivery = Model('delivery_point');
        $condition = array();
        $area_id = isset($_POST['area_id']) ? intval(trim($_POST['area_id'])) : 0 ;

        $model_area = Model('area');

        $area_condition = array();
        $area_condition['area_id'] = $area_id;

        $area_info = $model_area->getAreaInfo($area_condition);

        $area_level =empty($area_info) ? 0 : $area_info['area_deep'];

        $child_ids = $model_area->getChildsByPid($area_id);
        if($area_level == 0){
            //不设定条件
        }
        else if($area_level == 1){
            //第一级,县市级

            $condition['dlyp_area_2'] = array('in',$child_ids);
        }
        else if($area_level == 2){
            //第二级,乡镇级
            $condition['dlyp_area_3'] = array('in',$child_ids);
        }
        else {
            //条件为村
            $condition['dlyp_area_3'] = $area_id;
        }

        $list = $model_delivery->getDeliveryPointOpenList($condition,5);
        if(!empty($list)) {
            output_data(array('list' => $list));
        } else {
            output_error('地址不存在');
        }
    }
}