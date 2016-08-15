<?php
/**
 * 转账管理，管理员可以再后台在两个账户之间互相转账，从付款账户的充值卡余额，转到收款账户的现金余额
 *
 **by 诸暨村淘 zjcuntao*/

defined('InShopNC') or exit('Access Invalid!');

class transferControl extends SystemControl
{
    public function __construct()
    {
        parent::__construct();
        Language::read('transfer');
    }

    public function indexOp()
    {
        if (chksubmit()) {
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => trim($_POST["pay_user"]), "require" => "true", "message" => Language::get('transfer_pay_account_empty')),
                array("input" => trim($_POST["receiver_user"]), "require" => "true", "message" => Language::get('transfer_receiver_account_empty')),
                array("input" => trim($_POST["pay_amount"]), "require" => "true", "message" => Language::get('transfer_pay_amount_empty')),
                array("input" => trim($_POST["pay_reason"]), "require" => "true", "message" => Language::get('transfer_reason_empty'))
            );
            $error = $obj_validate->validate();

            if (!is_numeric(trim($_POST["pay_amount"]))) {
                $error = Language::get('transfer_pay_amount_error');
                showMessage($error);
                exit;
            }

            //if(trim($_POST["pay_user"])!=C('pay_user')){
            //2015.11.24 可以转出的有可能是多个帐号,写在配置文件里面的,先转换为数组

            $users = explode('|',C('pay_user'));

            if(!in_array(trim($_POST["pay_user"]),$users)){
                $error = Language::get('transfer_pay_account_wrong');
                showMessage($error);
                exit;
            }


            //这里判断转入和转出账号是否一样，也可以不判断将同一个账号的充值卡余额转换为现金余额
            if (trim($_POST["pay_user"]) == trim($_POST["receiver_user"])) {
                $error = Language::get('transfer_pay_equal_receiver');
            }

            if ($error != '') {
                showMessage($error);
            } else {

                $pay_user = $_POST['pay_user'];
                $receiver_user = $_POST['receiver_user'];
                $pay_amount = floatval($_POST['pay_amount']);
                $reason = $_POST['pay_reason'];

                $model_member = Model('member');
                $condition_pay = array();
                $condition_pay['member_name'] = $pay_user;

                $pay_user_info = $model_member->getMemberInfo($condition_pay);

                $condition_receiver = array();
                $condition_receiver['member_name'] = $receiver_user;

                $receiver_user_info = $model_member->getMemberInfo($condition_receiver);

                if (empty($pay_user_info) || empty($receiver_user_info)) {
                    $error = Language::get('transfer_member_error');
                    showMessage($error);
                    exit;
                }

                if ($pay_user_info['available_rc_balance'] < $pay_amount) {
                    $error = Language::get('transfer_pay_amount_not_enough');
                    showMessage($error);
                    exit;
                }

                $model_predeposit = Model('predeposit');
                try {

                    $admininfo = $this->getAdminInfo();

                    $model_predeposit->beginTransaction();
                    $data_rcb = array();
                    $data_rcb['member_id'] = $pay_user_info['member_id'];
                    $data_rcb['member_name'] = $pay_user_info['member_name'];
                    $data_rcb['amount'] = $pay_amount;
                    $data_rcb['receiver_user'] = $receiver_user_info['member_name'];
                    $data_rcb['reason'] = $reason;
                    $data_rcb['admin_name'] = $admininfo['name'];

                    $model_predeposit->changeRcb('transfer', $data_rcb);

                    $data_pd = array();
                    $data_pd['member_id'] = $receiver_user_info['member_id'];
                    $data_pd['member_name'] = $receiver_user_info['member_name'];
                    $data_pd['amount'] = $pay_amount;
                    $data_pd['pay_user'] = $pay_user_info['member_name'];
                    $data_pd['reason'] = $reason;
                    $data_pd['admin_name'] = $admininfo['name'];
                    $model_predeposit->changePd('transfer', $data_pd);
                    $model_predeposit->commit();

                    $log_msg=Language::get('transfer_success').'.['.$pay_user.']转账['.$pay_amount.']元给['.$receiver_user.'],理由是:'.$reason.'.';

                    $this->log($log_msg,1);
                } catch (Exception $e) {
                    $this->log(Language::get('transfer_fail'),0);
                    $model_predeposit->rollback();
                    showMessage($e->getMessage());
                    exit;
                }
                showMessage(Language::get('transfer_success'));
            }
        }
        Tpl::showpage('transfer.index');
    }
}
