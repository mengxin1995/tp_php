<?php
/**
 * 账户余额变动记录 by zjcuntao.com
 */


defined('InShopNC') or exit('Access Invalid!');


class balancechange_logControl extends mobileHomeControl {

    public function __construct(){
        parent::__construct();
    }


    /**
     * 充值明细列表
     */
    public function recharge_listOp(){
        $condition = array();
        $key        = $_POST['key'];
        $model_token = Model('mb_user_token');
        $member_info = $model_token->getMbUserTokenInfoByToken($key);
        if(empty($member_info)){
            //key错误，用户不存在
            output_error('key错误,查询失败');
            return;
        }
        $member_id = intval($_POST['member_id']);
        $condition['pdr_member_id']	= $member_id;
        if (!empty($_POST['pdr_sn'])) {
            $condition['pdr_sn'] = $_POST['pdr_sn'];
        }

        if(!empty($_POST['page']))
        {
            $page = $_POST['page'];
        }else{
            $page = 1;
        }

        if(!empty($_POST['size']))
        {
            $size = $_POST['size'];
        }else{
            $size = 10;
        }

        $limit = ($page-1)*$size.','.$size;

        $model_pd = Model('predeposit');
        $list = $model_pd->getPdRechargeList($condition,'','*','pdr_id desc',$limit);
        if(!empty($list)) {
            output_data(array('list' => $list));
        } else {
            output_error('记录不存在');
        }
    }
    /**
     * 余额提现列表
     */
    public function pd_cash_listOp(){
        $condition = array();
        $key        = $_POST['key'];
        $model_token = Model('mb_user_token');
        $member_info = $model_token->getMbUserTokenInfoByToken($key);
        if(empty($member_info)){
            //key错误，用户不存在
            output_error('key错误,查询失败');
            return;
        }
        $member_id = intval($_POST['member_id']);
        $condition['pdc_member_id'] =  $member_id;
        if (!empty($_POST['sn_search'])) {
            $condition['pdc_sn'] = $_POST['sn_search'];
        }
        if (empty($_POST['pdc_payment_state'])){
            $condition['pdc_payment_state'] = intval($_POST['pdc_payment_state']);
        }

        if(!empty($_POST['page']))
        {
            $page = $_POST['page'];
        }else{
            $page = 1;
        }

        if(!empty($_POST['size']))
        {
            $size = $_POST['size'];
        }else{
            $size = 10;
        }

        $limit = ($page-1)*$size.','.$size;

        $model_pd = Model('predeposit');
        $list = $model_pd->getPdCashList($condition,'','*','pdc_id desc',$limit);
        if(!empty($list)) {
            output_data(array('list' => $list));
        } else {
            output_error('记录不存在');
        }
    }
    /**
     * 充值卡记录
     */
    public function rcb_log_listOp()
    {
        $key        = $_POST['key'];
        $model_token = Model('mb_user_token');
        $member_info = $model_token->getMbUserTokenInfoByToken($key);
        if(empty($member_info)){
            //key错误，用户不存在
            output_error('key错误,查询失败');
            return;
        }
        $model_rcb = Model('predeposit');
        $condition = array();
        $member_id = intval($_POST['member_id']);
        $condition['member_id'] = $member_id;
        //关键字搜索
        if (!empty($_POST['description'])) {
            $condition['description'] = array('like', '%'.$_POST['description'].'%');
        }


        if(!empty($_POST['page']))
        {
            $page = $_POST['page'];
        }else{
            $page = 1;
        }

        if(!empty($_POST['size']))
        {
            $size = $_POST['size'];
        }else{
            $size = 10;
        }

        $limit = ($page-1)*$size.','.$size;

        $list = $model_rcb->getRcbLogList($condition,'','*','id desc',$limit);
        if(!empty($list)) {
            output_data(array('list' => $list));
        } else {
            output_error('记录不存在');
        }
    }
    /**
     * 账户余额日志
     */
    public function pd_log_listOp(){
        $key        = $_POST['key'];
        $model_token = Model('mb_user_token');
        $member_info = $model_token->getMbUserTokenInfoByToken($key);
        if(empty($member_info)){
            //key错误，用户不存在
            output_error('key错误,查询失败');
            return;
        }
        $model_pd = Model('predeposit');
        $condition = array();
        $member_id = intval($_POST['member_id']);
        $condition['lg_member_id'] = $member_id;
        //订单号搜索
        if (!empty($_POST['lg_desc'])) {
            $condition['lg_desc'] = array('like', '%'.$_POST['lg_desc'].'%');
        }


        if(!empty($_POST['page']))
        {
            $page = $_POST['page'];
        }else{
            $page = 1;
        }

        if(!empty($_POST['size']))
        {
            $size = $_POST['size'];
        }else{
            $size = 10;
        }

        $limit = ($page-1)*$size.','.$size;

        $list = $model_pd->getPdLogList($condition,'','*','lg_id desc',$limit);
        if(!empty($list)) {
            output_data(array('list' => $list));
        } else {
            output_error('记录不存在');
        }
    }

}