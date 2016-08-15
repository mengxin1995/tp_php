<?php
/**
 * 诸暨村淘内部支付接口类
 *
 * 
 * by zjcuntao alphawu 开发
 */
defined('InShopNC') or exit('Access Invalid!');

class cuntaopay{
	/**
	 * 诸暨村淘内部支付网关
	 *
	 * @var string
	 */
	private $gateway   = '/index.php?act=buy&op=cuntaopay';
	/**
	 * 支付接口标识
	 *
	 * @var string
	 */
    private $code      = 'predeposit';
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

    
    public function __construct($payment_info,$order_info){
    	$this->cuntaopay($payment_info,$order_info);
    }
    public function cuntaopay($payment_info = array(),$order_info = array()){
    	if(!empty($payment_info) and !empty($order_info)){
    		$this->payment	= $payment_info;
    		$this->order	= $order_info;
    	}
    }
	/**
	 * 支付表单
	 *
	 */
	public function submit(){
		
		$v_oid = $this->order['pay_sn'];															//订单号
		/* 交易参数 */
		$parameter = array(
		'pay_sn'        => $v_oid,                    			     				// 订单号
		'code'			=>$this->code
		);

		$html = '<html><head></head><body>';
		$html .= '<form method="post" name="E_FORM" action="'.SHOP_SITE_URL.$this->gateway.'">';
		foreach ($parameter as $key => $val){
			$html .= "<input type='hidden' name='$key' value='$val' />";
		}
		$html .= '</form><script type="text/javascript">document.E_FORM.submit();</script>';
		$html .= '</body></html>';
		echo $html;
		exit;
	}
}
