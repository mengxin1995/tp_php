<?php 
require_once(BASE_PATH.DS.'api'.DS.'weixin'.DS.'comm'.DS."config.php");
require_once(BASE_PATH.DS.'api'.DS.'weixin'.DS.'comm'.DS."utils.php");

function weixin_callback()
{

    if($_REQUEST['state'] == $_SESSION['state']) //csrf
    {
        if(!isset($_GET['code'])){
            //用户没有同意授权
            //
            echo "<h3>授权失败</h3>";
            exit;
        }

        $token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?grant_type=authorization_code&"
            . "appid=" . $_SESSION["appid"]
            . "&secret=" . $_SESSION["appkey"]
            . "&code=" . $_REQUEST["code"];

        $response = get_url_contents($token_url);
        if (strpos($response, "errcode") != false)
        {
            $msg = json_decode($response);
            if (isset($msg->errcode))
            {
                echo "<h3>error:</h3>" . $msg->errcode;
                echo "<h3>msg  :</h3>" . $msg->errmsg;
                exit;
            }
        }

        $params = json_decode($response);

        //set access token to session
        $_SESSION["access_token"] = $params->access_token;
        $_SESSION["refresh_token"] = $params->refresh_token;
        $_SESSION["wxopenid"] = $params->openid;
        $_SESSION["unionid"] = $params->unionid;
        $_SESSION['token_expire'] = $params->expires_in;

    }
    else 
    {
        echo("The state does not match. You may be a victim of CSRF.");
    }
}



//weixin登录成功后的回调地址,主要保存access token
weixin_callback();

@header('location: index.php?act=connect_weixin');
exit;
