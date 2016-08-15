<?php
/**
 *  合伙人服务站
 *
 *
 *
 *
 *  by cuncuntu.com
 */
defined('InShopNC') or exit('Access Invalid!');

class store_joinin_partner_wapControl extends BaseHomeControl {
    public function __construct() {
        parent::__construct();
        Tpl::setLayout('null_layout');
        Tpl::output('html_title',C('site_name').' - '.'合伙人服务站申请');
        Tpl::output('article_list','');//底部不显示文章分类
    }


    public function indexOp() {
        $this->partner_wapOp();
    }
    public function partner_wapOp() {
        if(!empty($_POST)) {
            $param = array();
            $param['partner_name'] = $_POST['partner_name'];
            $param['partner_sex'] = $_POST['partner_sex'];
            $param['partner_age'] = $_POST['partner_age'];
            $param['partner_job'] = $_POST['partner_job'];
            $param['partner_phone'] = $_POST['partner_phone'];
            $param['partner_address'] = $_POST['partner_address'];
            $param['partner_field'] = $_POST['partner_field'];
            $param['partner_position'] = $_POST['partner_position'];
            $param['partner_relation'] = $_POST['partner_relation'];
            $param['partner_village'] = $_POST['partner_village'];
            $param['partner_experience'] = $_POST['partner_experience'];
            $param['partner_boss'] = $_POST['partner_boss'];
            $param['partner_time'] = $_POST['partner_time'];
            $param['partner_opinion'] = $_POST['partner_opinion'];
            $param['pdr_add_time'] = TIMESTAMP;
            $param['paystate_search'] = 0;
            $this->step_save_valid($param);
            $model_store_joinin_partner = Model('store_joinin_partner');
            $model_store_joinin_partner->save($param);
            showMessage('提交成功','','html','succ');
        }
        Tpl::showpage('store_joinin_partner_apply_wap');
        exit;
    }
    private function step_save_valid($param) {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$param['partner_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"姓名不能为空"),
            array("input"=>$param['partner_job'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"职业不能为空且必须小于50个字"),
            array("input"=>$param['partner_phone'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"联系电话不能为空"),
            array("input"=>$param['partner_address'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"详细地址不能为空且必须小于50个字")
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            showMessage($error);
        }
    }

}
