<?php
/**
 * QQ互联登录
 *
 *
 **by 好商城V3 www.33hao.com 运营版*/


defined('InShopNC') or exit('Access Invalid!');

class connectControl extends BaseHomeControl{
	public function __construct(){
		parent::__construct();
		Language::read("home_login_register,home_login_index,home_qqconnect");
		/**
		 * 判断qq互联功能是否开启
		 */
		if (C('qq_isuse') != 1){
			showMessage(Language::get('home_qqconnect_unavailable'),'index.php','html','error');//'系统未开启QQ互联功能'
		}
		/**
		 * 初始化测试数据
		 */
		//测试先注释掉下面代码,2015.12.11
		if (!$_SESSION['openid']){
			showMessage(Language::get('home_qqconnect_error'),'index.php','html','error');//'系统错误'
		}
		Tpl::output('hidden_nctoolbar', 1);
		Tpl::setLayout('login_layout');
	}
	/**
	 * 首页
	 */
	public function indexOp(){
		/**
		 * 检查登录状态
		 */
		if($_SESSION['is_login'] == '1') {
			//qq绑定
			$this->bindqqOp();
		}else {

			$this->autologin();
			$this->bindmember();
			//$this->registerOp();
		}
	}

    private function checkWapQQlogin(){
        if(!empty($_SESSION['m'])){
            return true;
        }
        return false;
    }

	/**
	 * qq绑定新用户
	 * 外部通过表单直接调用,2015.12.11
	 */
	public function registerOp(){
		//'必须先绑定帐号,暂时性处理,后面要可以选择是绑定还是新注册'
		//showMessage(Language::get('home_qqconnect_not_bind'),'index.php','html','error');
		//exit;
		//实例化模型
		$model_member	= Model('member');
		if (chksubmit()){
			$update_info	= array();
			$update_info['member_passwd']= md5(trim($_POST["password"]));
			if(!empty($_POST["email"])) {
				$update_info['member_email']= $_POST["email"];
				$_SESSION['member_email']= $_POST["email"];
			}
			$model_member->editMember(array('member_id'=>$_SESSION['member_id']),$update_info);
			showMessage(Language::get('nc_common_save_succ'),SHOP_SITE_URL);
		}else {
			//检查登录状态
			$model_member->checkloginMember();
			//获取qq账号信息
			require_once (BASE_PATH.'/api/qq/user/get_user_info.php');
			$qquser_info = get_user_info($_SESSION["appid"], $_SESSION["appkey"], $_SESSION["token"], $_SESSION["secret"], $_SESSION["openid"]);
			Tpl::output('qquser_info',$qquser_info);

			//处理qq账号信息
			$qquser_info['nickname'] = trim($qquser_info['nickname']);
			$user_passwd = rand(100000, 999999);
			/**
			 * 会员添加
			 */
			$user_array	= array();
			$user_array['member_name']		= $qquser_info['nickname'];
			$user_array['member_passwd']	= $user_passwd;
			$user_array['member_email']		= '';
			$user_array['member_qqopenid']	= $_SESSION['openid'];//qq openid
			$user_array['member_qqinfo']	= serialize($qquser_info);//qq 信息
			$rand = rand(100, 899);
			if(strlen($user_array['member_name']) < 3) $user_array['member_name']		= $qquser_info['nickname'].$rand;
			$check_member_name	= $model_member->getMemberInfo(array('member_name'=>trim($user_array['member_name'])));
			$result	= 0;
			if(empty($check_member_name)) {
				$result	= $model_member->addMember($user_array);
			}else {
				$user_array['member_name'] = trim($qquser_info['nickname']).$rand;
				$check_member_name	= $model_member->getMemberInfo(array('member_name'=>trim($user_array['member_name'])));
				if(empty($check_member_name)) {
					$result	= $model_member->addMember($user_array);
				}else {
					for ($i	= 1;$i < 999999;$i++) {
						$rand = $rand+$i;
						$user_array['member_name'] = trim($qquser_info['nickname']).$rand;
						$check_member_name	= $model_member->getMemberInfo(array('member_name'=>trim($user_array['member_name'])));
						if(empty($check_member_name)) {
							$result	= $model_member->addMember($user_array);
							break;
						}
					}
				}
			}
			if($result) {
				Tpl::output('user_passwd',$user_passwd);
				//v3-b12 修复 QQ登录头像
				$avatar = @copy($qquser_info['figureurl_qq_2'],BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR."/avatar_$result.jpg");

				$update_info	= array();
				if($avatar) {
				    $update_info['member_avatar'] 	= "avatar_$result.jpg";
    				$model_member->editMember(array('member_id'=>$result),$update_info);
    				$user_array['member_avatar'] 	= "avatar_$result.jpg";
				}
				$user_array['member_id']		= $result;
				$model_member->createSession($user_array);
                if($this->checkWapQQlogin()){
                    @header('location: '.MOBILE_SITE_URL.'/index.php?act=login&type=qq');
                    exit;
                }else{
                    Tpl::showpage('connect_register');
                }
			} else {
				showMessage(Language::get('login_usersave_regist_fail'),SHOP_SITE_URL.'/index.php?act=login&op=register','html','error');//"会员注册失败"
			}
		}
	}

	/**
	 * 给用户选择,是绑定到已有帐号还是使用第三方帐号注册
	 * 该代码同时存在于connect.php和connect_weixin.php
	 * 除最后一行的页面文件不同之外,其他相同.
	 * 该代码原始出处为login.php的indexOp,取其登录部分
	 * 2015.12.11
	 */
	private function bindmember(){
		Language::read("home_login_index,home_login_register");
		$lang	= Language::getLangContent();
		$model_member	= Model('member');
		//检查登录状态
		$model_member->checkloginMember();

		//登录表单页面
		$_pic = @unserialize(C('login_pic'));
		if ($_pic[0] != ''){
			Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.$_pic[array_rand($_pic)]);
		}else{
			Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.rand(1,4).'.jpg');
		}

		Tpl::output('html_title',C('site_name').' - 绑定到已有帐号');
		Tpl::showpage('login_bind_qq');
	}

	/**
	 * 已有用户绑定QQ
	 */
	public function bindqqOp(){
		$model_member	= Model('member');
		//验证QQ账号用户是否已经存在
		$array	= array();
		$array['member_qqopenid']	= $_SESSION['openid'];
		$member_info = $model_member->getMemberInfo($array);
		if (is_array($member_info) && count($member_info)>0){
			unset($_SESSION['openid']);
			showMessage(Language::get('home_qqconnect_binding_exist'),'index.php?act=member_connect&op=qqbind','html','error');//'该QQ账号已经绑定其他商城账号,请使用其他QQ账号与本账号绑定'
		}
		//获取qq账号信息
		require_once (BASE_PATH.'/api/qq/user/get_user_info.php');
		$qquser_info = get_user_info($_SESSION["appid"], $_SESSION["appkey"], $_SESSION["token"], $_SESSION["secret"], $_SESSION["openid"]);
		$edit_state		= $model_member->editMember(array('member_id'=>$_SESSION['member_id']), array('member_qqopenid'=>$_SESSION['openid'], 'member_qqinfo'=>serialize($qquser_info)));
		if ($edit_state){
			showMessage(Language::get('home_qqconnect_binding_success'),'index.php?act=member_connect&op=qqbind');
		}else {
			showMessage(Language::get('home_qqconnect_binding_fail'),'index.php?act=member_connect&op=qqbind','html','error');//'绑定QQ失败'
		}
	}

	/**
	 * 绑定qq后自动登录
	 */
	public function autologin(){
		//查询是否已经绑定该qq,已经绑定则直接跳转
		$model_member	= Model('member');
		$array	= array();
		$array['member_qqopenid']	= $_SESSION['openid'];
		$member_info = $model_member->getMemberInfo($array);
		if (is_array($member_info) && count($member_info)>0){
			if(!$member_info['member_state']){//1为启用 0 为禁用
				showMessage(Language::get('nc_notallowed_login'),'','html','error');
			}
			$model_member->createSession($member_info);
            if($this->checkWapQQlogin()){
                @header('location: '.MOBILE_SITE_URL.'/index.php?act=login&type=qq');
                exit;
            }else{
                $success_message = Language::get('login_index_login_success');
                showMessage($success_message,SHOP_SITE_URL);
            }
		}
	}
	/**
	 * 更换绑定QQ号码
	 */
	public function changeqqOp(){
		//如果用户已经登录，进入此链接则显示错误
		if($_SESSION['is_login'] == '1') {
			showMessage(Language::get('home_qqconnect_error'),'index.php','html','error');//'系统错误'
		}
		unset($_SESSION['openid']);
		@header('Location:'.SHOP_SITE_URL.'/api.php?act=toqq');
		exit;
	}

	/**
	 * 登录操作,代码复制自login.indexOp,2015.12.11
	 * 根据绑定的需要,进行了精简,方便绑定帐号,该代码同时存在于connect.php和connect_weixin.php
	 * 代码除最后一行绑定操作之外,其他应相同.
	 *
	 */
	public function loginOp(){
		Language::read("home_login_index,home_login_register");
		$lang	= Language::getLangContent();
		$model_member	= Model('member');
		$result = chksubmit(true,C('captcha_status_login'),'num');
		if ($result !== false){
			if ($result === -11){
				showDialog($lang['login_index_login_illegal'],'','error');
			}elseif ($result === -12){
				showDialog($lang['login_index_wrong_checkcode'],'','error');
			}
			if (process::islock('login')) {
				showDialog($lang['nc_common_op_repeat'],SHOP_SITE_URL,'','error');
			}
			$obj_validate = new Validate();
			$user_name = $_POST['user_name'];
			$password = $_POST['password'];
			$obj_validate->validateparam = array(
				array("input"=>$user_name,     "require"=>"true", "message"=>$lang['login_index_username_isnull']),
				array("input"=>$password,      "require"=>"true", "message"=>$lang['login_index_password_isnull']),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showDialog($error,SHOP_SITE_URL,'error');
			}
			$condition = array();
			$condition['member_name'] = $user_name;
			$condition['member_passwd'] = md5($password);
			$member_info = $model_member->getMemberInfo($condition);
			if(empty($member_info) && preg_match('/^0?(13|15|17|18|14)[0-9]{9}$/i', $user_name)) {//根据会员名没找到时查手机号
				$condition = array();
				$condition['member_mobile'] = $user_name;
				$condition['member_passwd'] = md5($password);
				$member_info = $model_member->getMemberInfo($condition);
			}
			if(empty($member_info) && (strpos($user_name, '@') > 0)) {//按邮箱和密码查询会员
				$condition = array();
				$condition['member_email'] = $user_name;
				$condition['member_passwd'] = md5($password);
				$member_info = $model_member->getMemberInfo($condition);
			}
			if(is_array($member_info) && !empty($member_info)) {
				if(!$member_info['member_state']){
					showDialog($lang['login_index_account_stop'],'','error');
				}
			}else{
				process::addprocess('login');
				showDialog($lang['login_index_login_fail'],'','error');
			}

			$model_member->createSession($member_info);
			process::clear('login');
			// cookie中的cart存入数据库
			Model('cart')->mergecart($member_info,$_SESSION['store_id']);

			// cookie中的浏览记录存入数据库
			Model('goods_browse')->mergebrowse($_SESSION['member_id'],$_SESSION['store_id']);

			//下面执行绑定操作
			showDialog('',SHOP_SITE_URL.'/index.php?act=connect&op=bindqq','js');

		}
	}
}
