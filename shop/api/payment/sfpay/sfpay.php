<?php
/**
 * 顺丰支付接口类
 *
 * 
 * by 33hao 好商城V3  www.33hao.com 开发
 */
defined('InShopNC') or exit('Access Invalid!');

class sfpay{
	/**
	 *顺丰支付网关地址（新）
	 */
	//private $sfpay_gateway_new = 'https://uat-sypay.sf-pay.com/gatprx/payment';//测试地址
	private $sfpay_gateway_new = 'https://www.sf-pay.com/gatprx/payment';//生产地址
	/**
	 * 消息验证地址
	 *
	 * @var string
	 */
	//private $sfpay_verify_url = 'https://uat-sypay.sf-pay.com/gatprx/query/bytrade';//测试地址
	private $sfpay_verify_url = 'https://www.sf-pay.com/gatprx/query/bytrade';//生产地址
	/**
	 * 支付接口标识
	 *
	 * @var string
	 */
    private $code      = 'sfpay';
    /**
	 * 支付接口配置信息
	 *
	 * @var array
	 */
    private $payment;
     /**
	 * 订单信息
	 *
	 * @var array
	 */
    private $order;
    /**
	 * 发送至顺手付的参数
	 *
	 * @var array
	 */
    private $parameter;
    /**
     * 订单类型
     * @var unknown
     */
    private $order_type;

    public function __construct($payment_info = array(),$order_info = array()){
    	if(!empty($payment_info) and !empty($order_info)){
    		$this->payment	= $payment_info;
    		$this->order	= $order_info;
    	}
    }

    /**
     * POST 提交支付表单
     */
    public function submit(){
    	$this->parameter = array(
            'serviceName'		    => 'SFP_B2C_PAY',	//服务名
			'serviceVersion'		=>'V1.0.0',
			'charset'				=>'UTF-8',
			'merchantId'			=>$this->payment['payment_config']['sfpay_account'],//商户代码
			'orderId'				=>$this->order['pay_sn'],//订单号
			'amount'				=>$this->order['api_pay_amount']*100,//订单金额，分为单位
			'ccy'					=>'RMB',
			'orderBeginTime'		=>date('YmdHis',time()),//订单发起时间，格式：yyyyMMddHHmmss
			'orderExpireTime'		=>date('YmdHis',time()+3600*24*3),//订单失效时间，格式：yyyyMMddHHmmss,3天后失效
			'orderName'				=>$this->order['subject'],//商户订单名称
			'orderUrl'				=>SHOP_SITE_URL.'/index.php?act=member_order&op=show_order&order_id='.$this->order['order_list'][0]['order_id'],//订单URL
			'orderDesc'				=>'买家:'.$_SESSION['member_name'],//'orderDesc',//订单描述
			'reserved'				=>$this->order['order_type'],//商户附加信息，原样返回，中文注意转码
			'returnUrl'				=>SHOP_SITE_URL."/api/payment/sfpay/return_url.php",//支付成功后，跳转的前台返回
			'notifyUrl'				=>SHOP_SITE_URL."/api/payment/sfpay/notify_url.php"//后台通知地址
        );
        $this->create_url();
    }

	/**
	 * 通知地址验证
	 *
	 * @return bool
	 */
	public function notify_verify() {
		$param	= $_POST;
		if($param['status']=='SUCCESS'){
			return true;
		}else{
			return false;
		}
		exit;
        $data = array(
            'serviceName'       => 'SFP_B2C_QUERY_BYTRADE',
            'serviceVersion'    => 'V1.0.0',
            'charset'           => 'UTF-8',
            'signType'          => 'MD5',
            'merchantId'        => $param['merchantId'],
            'orderId'           => $param['orderId']
        );
        $arg='';
        while (list ($key, $val) = each ($data)) {
            $arg	.= $key."=".$this->charset_encode($val,"UTF-8","UTF-8")."&";
        }
        $arg .="sign=". $this->dosign($data);
        $veryfy_result  = $this->do_post_curl($this->sfpay_verify_url,$arg);

        if (preg_match("/success/i",$veryfy_result))  {
            return true;
        } else {
            return false;
        }
	}

	/**
	 * 返回地址验证
	 *
	 * @return bool
	 */
	public function return_verify() {
		$param	= $_POST;
		if($param['status']=='SUCCESS'){
			return true;
		}else{
			return false;
		}
		exit;
        $data = array(
            'serviceName'       => 'SFP_B2C_QUERY_BYTRADE',
            'serviceVersion'    => 'V1.0.0',
            'charset'           => 'UTF-8',
            'signType'          => 'MD5',
            'merchantId'        => $param['merchantId'],
            'orderId'           => $param['orderId']
        );
        $arg='';
        while (list ($key, $val) = each ($data)) {
            $arg	.= $key."=".$this->charset_encode($val,"UTF-8","UTF-8")."&";
        }
        $arg .="sign=". $this->dosign($data);
		$veryfy_result  = $this->do_post_curl($this->sfpay_verify_url,$arg);

		if (preg_match("/success/i",$veryfy_result))  {//生产系统返回的值后面带有CRLF，success$不可用了。
            return true;
		} else {
			return false;
		}
	}

    private function do_post_curl($url,$data){
        $postfields = $data;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, 1);
// Edit: prior variable $postFields should be $postfields;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // On dev server only!
        $result = curl_exec($ch);
        return $result;
    }

    private function do_post_request($url, $data, $optional_headers = null)
    {
        $params = array('http' => array(
            'method' => 'POST',
            'content' => $data
        ));
        if ($optional_headers !== null) {
            $params['http']['header'] = $optional_headers;
        }
        $ctx = stream_context_create($params);
        $fp = @fopen($url, 'rb', false, $ctx);
        if (!$fp) {
            throw new Exception("Problem with $url, $php_errormsg");
        }
        $response = @stream_get_contents($fp);
        if ($response === false) {
            throw new Exception("Problem reading data from $url, $php_errormsg");
        }
        return $response;
    }

	/**
	 * 
	 * 取得订单支付状态，成功或失败
	 * @param array $param
	 * @return array
	 */
	public function getPayResult($param){
		return $param['status'] == 'SUCCESS';
	}

	/**
	 * 
	 *
	 * @param string $name
	 * @return 
	 */
	public function __get($name){
	    return $this->$name;
	}

	/**
	 * 远程获取数据
	 * $url 指定URL完整路径地址
	 * @param $time_out 超时时间。默认值：60
	 * return 远程输出的数据
	 */
	private function getHttpResponse($url,$time_out = "60") {
		$urlarr     = parse_url($url);
		$errno      = "";
		$errstr     = "";
		$transports = "";
		$responseText = "";
		if($urlarr["scheme"] == "https") {
			$transports = "ssl://";
			$urlarr["port"] = "443";
		} else {
			$transports = "tcp://";
			$urlarr["port"] = "80";
		}
		$fp=@fsockopen($transports . $urlarr['host'],$urlarr['port'],$errno,$errstr,$time_out);
		if(!$fp) {
			die("ERROR: $errno - $errstr<br />\n");
		} else {
			if (trim(CHARSET) == '') {
				fputs($fp, "POST ".$urlarr["path"]." HTTP/1.1\r\n");
			} else {
				fputs($fp, "POST ".$urlarr["path"].'?_input_charset='.CHARSET." HTTP/1.1\r\n");
			}
			fputs($fp, "Host: ".$urlarr["host"]."\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: ".strlen($urlarr["query"])."\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $urlarr["query"] . "\r\n\r\n");
			while(!feof($fp)) {
				$responseText .= @fgets($fp, 1024);
			}
			fclose($fp);
			$responseText = trim(stristr($responseText,"\r\n\r\n"),"\r\n");
			return $responseText;
		}
	}

    /**
     * 制作支付接口的请求地址
     *
     * @return string
     */
    private function create_url() {
		$url        = $this->sfpay_gateway_new;
		$html = '<html><head></head><body>';
		$html .= '<form method="post" name="E_FORM" action="'.$url.'">';
		foreach ($this->parameter as $key => $val){
			$html .= "<input type='hidden' name='$key' value='$val' />";
		}
		$html .= "<input type='hidden' name='sign' value='".$this->dosign($this->parameter)."' />";
		$html .= "<input type='hidden' name='signType' value='MD5' />";
		$html .= '</form><script type="text/javascript">document.E_FORM.submit();</script>';
		$html .= '</body></html>';
		echo $html;
		exit;
	}

	/**
	 * 取得顺手付签名
	 *
	 * @return string
	 */
	private function dosign($parameter) {
		$filtered_array	= $this->para_filter($parameter);
		$sort_array = $this->arg_sort($filtered_array);
		$arg = "";
        while (list ($key, $val) = each ($sort_array)) {
			$arg	.= $key."=".$this->charset_encode($val,"UTF-8","UTF-8")."&";
		}
		$prestr =$arg. md5($this->payment['payment_config']['sfpay_key']);
		$prestr	.= $parameter['key'];
		$mysign = md5($prestr);
		return $mysign;
	}

	/**
	 * 除去数组中的空值和签名模式
	 *
	 * @param array $parameter
	 * @return array
	 */
	private function para_filter($parameter) {
		$para = array();
		while (list ($key, $val) = each ($parameter)) {
			if($key == "merchantId" || $key == "orderId" || $key == "amount" || $key == "ccy"|| $key == "orderBeginTime"|| $key == "notifyUrl"){
				$para[$key] = $parameter[$key];
			}
		}
		return $para;
	}

	/**
	 * 重新排序参数数组
	 *
	 * @param array $array
	 * @return array
	 */
	private function arg_sort($array) {
//		$new_array = array();
//
//        $new_array['amount']			= $array['amount'];
//        $new_array['ccy']				= $array['ccy'];
//        $new_array['merchantId']		= $array['merchantId'];
//        $new_array['notifyUrl']			= $array['notifyUrl'];
//        $new_array['orderBeginTime']	= $array['orderBeginTime'];
//        $new_array['orderId']			= $array['orderId'];
//
//		return $new_array;

        ksort($array);
        reset($array);

        return $array;

	}

	/**
	 * 实现多种字符编码方式
	 */
	private function charset_encode($input,$_output_charset,$_input_charset="UTF-8") {
		$output = "";
		if(!isset($_output_charset))$_output_charset  = $this->parameter['_input_charset'];
		if($_input_charset == $_output_charset || $input == null) {
			$output = $input;
		} elseif (function_exists("mb_convert_encoding")){
			$output = mb_convert_encoding($input,$_output_charset,$_input_charset);
		} elseif(function_exists("iconv")) {
			$output = iconv($_input_charset,$_output_charset,$input);
		} else die("sorry, you have no libs support for charset change.");
		return $output;
	}
}