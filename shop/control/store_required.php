<?php
/**
 * Created by PhpStorm.
 * User: CCT
 * Date: 2016/1/7
 * Time: 10:50
 */
defined('InShopNC') or exit('Access Invalid!');
class store_requiredControl extends BaseSellerControl {
    public function show_formOp()
    {
        Tpl::setLayout('null_layout');
        Tpl::showpage('store_required_form');
    }

    public function submitOp()
    {
        $model_store = Model('store');
        $param = array();
        if(!empty($_POST['store_phone'])) {
            $param['store_phone'] = $_POST['store_phone'];
        }
        if(!empty($_POST['store_mobile'])) {
            $model_member = Model('member');
            $condition = array();
            $condition['member_id'] = $_SESSION['member_id'];
            $condition['auth_code'] = intval($_POST['vcode']);
            $member_common_info = $model_member->getMemberCommonInfo($condition,'send_acode_time');
            if (!$member_common_info) {
                showDialog('短信验证码错误，请重新输入');
            } else if (TIMESTAMP - $member_common_info['send_acode_time'] > 1800) {
                showDialog('短信验证码已过期，请重新获取验证码');
            } else {
                $param['store_phone'] = $_POST['store_mobile'];
            }
        }
        if(!empty($_POST['store_qq'])) {
            $param['store_qq'] = $_POST['store_qq'];
        }
        if(!empty($_POST['store_ww'])) {
            $param['store_ww'] = $_POST['store_ww'];
        }
        $model_store->editStore($param, array('member_id' => $_SESSION['member_id']));
        redirect('index.php?act=seller_center');
    }
}