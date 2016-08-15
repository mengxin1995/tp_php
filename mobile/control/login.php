<?php
/**
 * 前台登录 退出操作  v3-b12
 *
 *
 *
 *
 * by 33hao.com 好商城V3 运营版
 */



defined('InShopNC') or exit('Access Invalid!');
class loginControl extends mobileHomeControl {

	public function __construct(){
		parent::__construct();
	}

    private function isQQLogin(){
        if(isset($_GET['type']) && $_GET['type']=='qq'){
            return true;
        }
        return false;
    }

    private function isWeiXinLogin(){
        if(isset($_GET['type']) && $_GET['type']=='weixin'){
            return true;
        }
        return false;
    }

	/**
	 * 登录
	 */
	public function indexOp(){
        if(!$this->isQQLogin() && !$this->isWeiXinLogin()){
            if(empty($_POST['username']) || empty($_POST['password']) || !in_array($_POST['client'], $this->client_type_array)) {
                output_error('登录失败');
            }
        }
		$model_member = Model('member');
        $array = array();
        if($this->isQQLogin()){
            $array['member_qqopenid']	= $_SESSION['openid'];
        }elseif($this->isWeiXinLogin()){
            $model_weixin_fans = Model('weixin_fans');
            $weixin_fans_info = $model_weixin_fans->getFansInfoByUnionID($_SESSION['unionid']);

            $array['member_id']	= $weixin_fans_info['memberid'];
        }else{
            $array['member_name']	= $_POST['username'];
            $array['member_passwd']	= md5($_POST['password']);
        }
        $member_info = $model_member->getMemberInfo($array);
        if(empty($member_info) && preg_match('/^0?(13|15|17|18|14)[0-9]{9}$/i', $_POST['username'])) {//根据会员名没找到时查手机号
            $condition = array();
            $condition['member_mobile'] = $_POST['username'];
            $condition['member_passwd'] = md5($_POST['password']);
            $member_info = $model_member->getMemberInfo($condition);
        }
        if(empty($member_info) && (strpos($_POST['username'], '@') > 0)) {//按邮箱和密码查询会员
            $condition = array();
            $condition['member_email'] = $_POST['username'];
            $condition['member_passwd'] = md5($_POST['password']);
            $member_info = $model_member->getMemberInfo($condition);
        }
        if(!empty($member_info)) {
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token) {
		if($this->isQQLogin()||$this->isWeiXinLogin()){
                        setNc2Cookie('username',$member_info['member_name']);
                        setNc2Cookie('key',$token);
                        header("location:".WAP_SITE_URL.'/tmpl/member/member.html?act=member');
                    }else{
            output_data(array('username' => $member_info['member_name'],'member_id' => $member_info['member_id'],'station_id' => $member_info['inviter_id'], 'key' => $token));
                    }
            } else {
                output_error('登录失败');
            }
        } else {

            //细分用户登录失败信息
            $condition = array();
            $condition['member_name'] = $_POST['username'];

            $member_info = $model_member->getMemberInfo($condition);
            if(empty($member_info)){
                output_error('用户名不存在');
            }
            else{

                output_error('密码错误');
            }
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
        $mb_user_token_info['client_type'] = $_POST['client'] == null ? 'Android' : $_POST['client'] ;

        $result = $model_mb_user_token->addMbUserToken($mb_user_token_info);

        if($result) {
            return $token;
        } else {
            return null;
        }
    }

    public function send_codeOp() {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$_GET["mobile"], "require"=>"true", 'validator'=>'mobile',"message"=>'请正确填写手机号码'),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            exit(json_encode(array('state'=>'false','msg'=>$error)));
        }
        $model_member = Model('member');
        $conditon = array();
        $conditon['member_mobile'] = $_GET["mobile"];
        $result = $model_member->getMemberInfo($conditon);
        if($result) {
            exit(json_encode(array('state'=>'false','msg'=>'该手机号已被注册！')));
        }
        $verify_code = rand(100,999).rand(100,999);
        $model_tpl = Model('mail_templates');
        $tpl_info = $model_tpl->getTplInfo(array('code'=>'modify_mobile'));
        $param = array();
        $param['site_name']	= C('site_name');
        $param['send_time'] = date('Y-m-d H:i',TIMESTAMP);
        $param['verify_code'] = $verify_code;
        $message = ncReplaceText($tpl_info['content'],$param);
        $sms = new Sms();
        $result = $sms->send($_GET["mobile"],$message);
        if ($result) {
            exit(json_encode(array('state'=>'true','verify_code'=>$verify_code,'msg'=>'验证码发送成功！')));
        } else {
            exit(json_encode(array('state'=>'false','msg'=>'验证码发送失败！')));
        }
    }
	/**
	 * 注册 重复注册验证 好商城v3-b10
	 */
	public function registerOp(){
        if (process::islock('reg')){
			output_error('您的操作过于频繁，请稍后再试');
		}
		$model_member = Model('member');
        $register_info = array();
        $register_info['username'] = $_POST['username'];
        $register_info['mobile'] = $_POST['mobile'];
        $register_info['email'] = '';
        $register_info['password'] = $_POST['password'];
        $register_info['password_confirm'] = $_POST['password_confirm'];
        $register_info['client'] = $_POST['client'];
		//添加奖励积分 v3-b12
		$register_info['inviter_id'] = intval(base64_decode($_COOKIE['uid']))/1;
        $member_info = $model_member->register($register_info);
        if(!isset($member_info['error'])) {
	        process::addprocess('reg');
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token) {
                output_data(array('username' => $member_info['member_name'], 'key' => $token));
            } else {
                output_error('注册失败');
            }
        } else {
			output_error($member_info['error']);
        }
    }
}
