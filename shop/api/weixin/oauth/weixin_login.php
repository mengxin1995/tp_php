<?php
require_once(BASE_PATH.DS.'api'.DS.'weixin'.DS.'comm'.DS."config.php");


function weixin_login($appid, $scope, $callback)
{
    if(!empty($_GET['mobile'])){
        $callback = $callback.'&m=mobile';
		//v3-b12
		$_SESSION['is_login']=0;
		$_COOKIE['username']='';
        	$_COOKIE['key']='';
    }
    $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection

    //第一步:请求code
    $login_url = "https://open.weixin.qq.com/connect/qrconnect?response_type=code&appid="
        . $appid . "&redirect_uri=" . urlencode($callback)
        . "&state=" . $_SESSION['state']
        . "&scope=snsapi_login"//网页登录只能使用snsapi_login,如果微信内登录,可以修改为snsapi_base或snsapi_userinfo
        . "#wechat_redirect";
    $login_url_mobile = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='
    .$appid.'&redirect_uri='.urlencode($callback).'&response_type=code'
    .'&scope=snsapi_userinfo&state='
    .$_SESSION['state'].'#wechat_redirect';
    if(!empty($_GET['mobile'])){
        header("Location:$login_url_mobile");
    }else{

        header("Location:$login_url");
    }
}

//用户点击weixin登录按钮调用此函数
weixin_login($_SESSION["appid"], $_SESSION["scope"], $_SESSION["callback"]);
