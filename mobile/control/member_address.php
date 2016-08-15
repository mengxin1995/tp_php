<?php
/**
 * 我的地址
 *
 *
 *
 *
 * by 33hao.com 好商城V3 运营版
 */

//use Shopnc\Tpl;

defined('InShopNC') or exit('Access Invalid!');

class member_addressControl extends mobileMemberControl {

	public function __construct() {
		parent::__construct();
	}

    /**
     * 地址列表
     */
    public function address_listOp() {
		$model_address = Model('address');
        $address_list = $model_address->getAddressList(array('member_id'=>$this->member_info['member_id']));
        output_data(array('address_list' => $address_list));
    }

    /**
     * 地址详细信息
     */
    public function address_infoOp() {
		$address_id = intval($_POST['address_id']);

		$model_address = Model('address');

        $condition = array();
        $condition['address_id'] = $address_id;
        $address_info = $model_address->getAddressInfo($condition);
        if(!empty($address_id) && $address_info['member_id'] == $this->member_info['member_id']) {
            output_data(array('address_info' => $address_info));
        } else {
            output_error('地址不存在');
        }
    }

    /**
     * 删除地址
     */
    public function address_delOp() {
		$address_id = intval($_POST['address_id']);

		$model_address = Model('address');

        $condition = array();
        $condition['address_id'] = $address_id;
        $condition['member_id'] = $this->member_info['member_id'];
        $model_address->delAddress($condition);
        output_data('1');
    }

    /**
     * 新增地址
     */
    public function address_addOp() {
        $model_address = Model('address');

        $address_info = $this->_address_valid();

        $result = $model_address->addAddress($address_info);
        if($result) {
            output_data(array('address_id' => $result));
        } else {
            output_error('保存失败');
        }
    }
    /**
     * 新增自提点地址
     */
    public function delivery_addOp() {
        $model_address = Model('address');

        $address_info = $this->_delivery_valid();

        $result = $model_address->addAddress($address_info);
        if($result) {
            output_data(array('address_id' => $result));
        } else {
            output_error('保存失败');
        }
    }
    /**
     * 编辑地址
     */
    public function address_editOp() {
        $address_id = intval($_POST['address_id']);

        $model_address = Model('address');

        //验证地址是否为本人
        $address_info = $model_address->getOneAddress($address_id);
        if ($address_info['member_id'] != $this->member_info['member_id']) {
            output_error('参数错误');
        }

        $address_info = $this->_address_valid();

        $result = $model_address->editAddress($address_info, array('address_id' => $address_id));
        if($result) {
            output_data('1');
        } else {
            output_error('保存失败');
        }
    }

    /**
     * 验证地址数据
     */
    private function _address_valid() {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$_POST["true_name"],"require"=>"true","message"=>'姓名不能为空'),
            array("input"=>$_POST["area_info"],"require"=>"true","message"=>'地区不能为空'),
            array("input"=>$_POST["address"],"require"=>"true","message"=>'地址不能为空'),
            array("input"=>$_POST['tel_phone'].$_POST['mob_phone'],'require'=>'true','message'=>'联系方式不能为空')
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            output_error($error);
        }

        $data = array();
        $data['member_id'] = $this->member_info['member_id'];
        $data['true_name'] = $_POST['true_name'];
        if($_POST['area_id']!=""){
            $data['area_id'] = intval($_POST['area_id']);
        }else{
            $data['area_id'] = intval($_POST['city_id']);
        }

        $data['city_id'] = intval($_POST['city_id']);
        $data['area_info'] = $_POST['area_info'];
        $data['address'] = $_POST['address'];
        $data['tel_phone'] = $_POST['tel_phone'];
        $data['mob_phone'] = $_POST['mob_phone'];
        return $data;
    }
    /**
     * 验证自提点数据
     */
    private function _delivery_valid() {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$_POST["true_name"],"require"=>"true","message"=>'姓名不能为空'),
            array("input"=>$_POST["area_info"],"require"=>"true","message"=>'地区不能为空'),
            array("input"=>$_POST['tel_phone'].$_POST['mob_phone'],'require'=>'true','message'=>'联系方式不能为空')
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            output_error($error);
        }

        $data = array();
        $data['member_id'] = $this->member_info['member_id'];
        $data['true_name'] = $_POST['true_name'];
        $data['dlyp_id'] = intval($_POST['dlyp_id']);
        if($_POST['area_id']!=""){
            $data['area_id'] = intval($_POST['area_id']);
        }else {
            $info = Model('delivery_point')->getDeliveryPointOpenInfo(array('dlyp_id' => intval($_POST['dlyp_id'])));
            $data['area_id'] = $info['dlyp_area_3'];
        }
        $data['city_id'] = intval($_POST['city_id']);
        $data['area_info'] = $_POST['area_info'];
        $data['tel_phone'] = $_POST['tel_phone'];
        $data['mob_phone'] = $_POST['mob_phone'];
        return $data;
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
        $condition['dlyp_name']=$this->member_info['member_name'];
        $list = $model_delivery->getDeliveryPointOpenList($condition,5);
        if(!empty($list)) {
            output_data(array('list' => $list));
        } else {
            output_error('地址不存在');
        }
    }
    /**
     * ajax提交订单时获取地区锁定状态wap端
     */
    public function getAddressLockType_ajaxOp(){
        $address_id = $_GET['address_id'];                      //获取自提点id
        $address_model=Model('address');
        $address_info = $address_model->where(array('address_id'=>$address_id))->find();
        if($address_info['dlyp_id'] =='0' &&$address_info['address'] !=''){
            $area_id = $address_info['area_id'];
            $city_id = $address_info['city_id'];
            $area_model=Model('area');
            if($area_id==$city_id){
                $area_info=$area_model->where(array('area_id'=>$city_id))->find(); 	  //获取地区area_address状态
                $area_address=$area_info['area_address'];
                echo $area_address;
            }else if($area_id==0){
                $area_info=$area_model->where(array('area_id'=>$city_id))->find(); 	  //获取地区area_address状态
                $area_address=$area_info['area_address'];
                echo $area_address;
            }else if($area_id!=0){
                $area_info=$area_model->where(array('area_id'=>$area_id))->find(); 	  //获取地区area_address状态
                $area_address=$area_info['area_address'];
                echo $area_address;
            }
        }else{
            echo '3';
        }
    }
    
}
