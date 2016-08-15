<?php
/**
 * 顺丰支付接口类
 *
 * 
 * by alphawu,cuncuntu.com,2015.12.31
 */
defined('InShopNC') or exit('Access Invalid!');

class sfpay{
	/**
	 *顺丰支付网关地址（新）
	 */
	//private $sfpay_gateway_new = 'https://uat-mobile.sf-pay.com/h5-cashier/home';//测试地址
	//private $sfpay_gateway_new = 'http://mpay-sit.sf-pay.com:8090/h5-cashier/home/';//测试地址
	private $sfpay_gateway_new = 'https://cashier.sf-pay.com/h5-cashier/home';//生产地址
	/**
	 * 消息验证地址
	 *
	 * @var string
	 */
	//private $sfpay_verify_url = 'https://uat-sypay.sf-pay.com/gatprx/query/bytrade';//测试地址
	//private $sfpay_verify_url = 'https://www.sf-pay.com/gatprx/query/bytrade';//生产地址
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
            'serviceName'		    => 'CREATE_ORDER',	//服务名
			//'serviceVersion'		=>'V1.0.0',
			'charset'				=>'UTF-8',
			'requestTime'			=>date('YmdHis',time()),
			'merchantId'			=>$this->payment['payment_config']['sfpay_mchid'],//商户代码
			'merchantName'			=>$this->payment['payment_config']['sfpay_mchname'],/////////
			'orderId'				=>$this->order['pay_sn'],//订单号
			'amt'					=>$this->order['api_pay_amount']*100,//订单金额，分为单位
			'ccy'					=>'RMB',
			'orderBeginTime'		=>date('YmdHis',time()),//订单发起时间，格式：yyyyMMddHHmmss
			'orderExpDate'			=>date('YmdHis',time()+3600*24*3),//订单失效时间，格式：yyyyMMddHHmmss,3天后失效
			//'orderName'				=>$this->order['subject'],//商户订单名称
			//'orderUrl'				=>SHOP_SITE_URL.'/index.php?act=member_order&op=show_order&order_id='.$this->order['order_list'][0]['order_id'],//订单URL
			//'orderDesc'				=>'买家:'.$_SESSION['member_name'],//'orderDesc',//订单描述
			'reserved'				=>$this->order['order_type'],//商户附加信息，原样返回，中文注意转码
			'notifyUrl'				=>MOBILE_SITE_URL."/api/payment/sfpay/notify_url.php",//后台通知地址
			//'returnUrl'				=>MOBILE_SITE_URL."/api/payment/sfpay/return_url.php",//支付成功后，跳转的前台返回
			'tradeScene'			=>'030200005',
			'merchantUrl'			=>WAP_SITE_URL,
			'callBackUrl'			=>WAP_SITE_URL.'/tmpl/member/order_list.html',
			'accessWay'				=>'Wap'
        );

        $this->create_url();
    }

    /**
     * 制作支付接口的请求地址
     *
     * @return string
     */
    private function create_url() {
		$url        = $this->sfpay_gateway_new;
		$html = '<html><head></head><body>';
		$html .= '<form method="post" id="sfpaysubmit" name="sfpaysubmit" action="'.$url.'">';
		foreach ($this->parameter as $key => $val){
			$html .= "<input type='hidden' name='$key' value='$val' />";
		}
		$html .= "<input type='hidden' name='sign' value='".$this->dosign($this->parameter)."' />";
		$html .= "<input type='hidden' name='signType' value='MD5' />";
		$html .= '<script type="text/javascript">document.forms[\'sfpaysubmit\'].submit();</script>';
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
			//$arg	.= $key."=".$val."&";
		}
		$arg = rtrim($arg,'&');
		$this->args=$arg;
		$this->pre_key=md5($this->parameter['requestTime']).$this->payment['payment_config']['sfpay_key'];
		$prestr =$arg.$this->pre_key;
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
			if($key == "merchantId" || $key == "orderId" || $key == "amt" || $key == "ccy" || $key == "notifyUrl"){
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
		$new_array = array();

		$new_array['merchantId']		= $array['merchantId'];
		$new_array['orderId']			= $array['orderId'];
		$new_array['amt']				= $array['amt'];
		$new_array['ccy']				= $array['ccy'];
		$new_array['notifyUrl']			= $array['notifyUrl'];

		return $new_array;

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


	/**
	 * 获取notify信息
	 */
	public function getNotifyInfo($payment_config) {
		if($this->_verify($payment_config)) {
			return array(
				//商户订单号
				'out_trade_no' => $_POST['out_trade_no'],
				//顺手付交易号
				'trade_no' => $_POST['trade_no'],
			);
		}
		return false;
	}

	/**
	 * 验证返回信息
	 */
	private function _verify($payment_config) {

		if(empty($payment_config)) {
			return false;
		}

		$requestTime		= $_POST['requestTime'];
		$sign				= $_POST['sign'];
		$sfpay_data = array(
			'merchantId'		=> $_POST['merchantId'],
			'orderId'			=> $_POST['orderId'],
			'amt'				=> $_POST['amt'],
			'orderBeginTime'	=> $_POST['orderBeginTime'],
			'status'			=> $_POST['status'],
			'sfBusinessNo'		=> $_POST['sfBusinessNo'],
		);
		if('SUCCESS'!=$sfpay_data['status']){
			return false;
		}

		$arg = "";
		while (list ($key, $val) = each ($sfpay_data)) {
			$arg	.= $key."=".$this->charset_encode($val,"UTF-8","UTF-8")."&";
		}
		$arg = rtrim($arg,'&');
		$prestr =$arg. md5($requestTime).$payment_config['sfpay_key'];

		$mysign = md5($prestr);

		return $mysign==$sign?true:false;


	}
}