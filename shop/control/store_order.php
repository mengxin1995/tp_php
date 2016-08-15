<?php
/**
 * 卖家实物订单管理
 *
 *
 *
 **by 好商城V3 www.33hao.com 运营版*/


defined('InShopNC') or exit('Access Invalid!');
class store_orderControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
        Language::read('member_store_index');
    }

    /**
     * 订单列表
     *
     */
    public function indexOp() {
        /***批量打印开始***/
        if (chksubmit()){
            //$odids = implode(',', $_POST['check_order_id']);
            $odids = substr($_COOKIE['array_str'],1,strlen($_COOKIE['array_str'])-1);  //分页打印
            if(intval($_POST['form_flag'])==1){
                header('Location:index.php?act=store_deliver&op=plsend&order_id='.$odids);
            }else if(intval($_POST['form_flag'])==2){
                header('Location:index.php?act=store_order_print&op=pl&order='.$odids);
            }else if(intval($_POST['form_flag'])==3){
                header('Location:index.php?act=store_deliver&op=waybill_plprint&order_id='.$odids);
            }else if(intval($_POST['form_flag'])==4){
                header('Location:index.php?act=store_deliver&op=plsend&order_id='.$odids);
            }else if(intval($_POST['form_flag'])==5){
                header('Location:index.php?act=store_order_print&op=pl&order='.$odids);
            }else if(intval($_POST['form_flag'])==6){
                header('Location:index.php?act=store_deliver&op=waybill_plprint&order_id='.$odids);
            }
            exit();
        }
        /***批量打印结束***/
        $model_order = Model('order');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        if ($_GET['order_sn'] != '') {
            $condition['order_sn'] = $_GET['order_sn'];
        }
        if ($_GET['buyer_name'] != '') {
            $key = explode(" ",$_GET['buyer_name']);
            $condition['buyer_name'] = array(array('like', '%'.$key[0].'%'),array('like', '%'.$key[1].'%'),'and');
        }

        $order_id_list = array();
        //bug88 商品名称搜索开始
        if($_GET['goods_name'] != '') {
            //获取用户输入的关键字
            $key = explode(" ",$_GET['goods_name']);
            $cond = array();
            $cond['goods_name'] = array(array('like', '%'.$key[0].'%'),array('like', '%'.$key[1].'%'),'and');
            $orderGoods_list = $model_order->getOrderGoodsList($cond,'order_id');
            //存放符合条件的order_id
            if(!empty($orderGoods_list)){
                foreach($orderGoods_list as $val){
                    array_push($order_id_list,$val['order_id']);
                }
            }
            $condition['order_id']= array("in",$order_id_list);
        }
        //bug88 商品名称搜索结束

        //按商品地址搜索
        if($_GET['goods_address'] != '') {
            //获取用户输入的关键字
            $key = explode(" ",$_GET['goods_address']);
            //存放符合条件的order_id
            $cond_1 = array();
            $cond_1['store_id']=$_SESSION['store_id'];
            $cond_1['reciver_info']=array(array('like','%'.$key[0].'%'),array('like','%'.$key[1].'%'),'and');
            $orderCommon_list = $model_order->getOrderCommonList($cond_1,' order_id ','',1000000);
            $order_id_list_tmp = array();
            if(!empty($orderCommon_list)){
                if(empty($order_id_list)) {
                    foreach($orderCommon_list as $val) {
                        array_push($order_id_list_tmp,$val['order_id']);
                    }
                } else {
                    foreach($orderCommon_list as $val) {
                        if(in_array($val['order_id'],$order_id_list)) {
                            array_push($order_id_list_tmp,$val['order_id']);
                        }
                    }
                }
            }
            unset($order_id_list);
            $order_id_list = $order_id_list_tmp;
            unset($order_id_list_tmp);
            $condition['order_id']= array("in",$order_id_list);
        }

        $allow_state_array = array('state_new','state_pay','state_send','state_success','state_cancel');
        if (in_array($_GET['state_type'],$allow_state_array)) {
            $condition['order_state'] = str_replace($allow_state_array,
                array(ORDER_STATE_NEW,ORDER_STATE_PAY,ORDER_STATE_SEND,ORDER_STATE_SUCCESS,ORDER_STATE_CANCEL), $_GET['state_type']);
        } else {
            $_GET['state_type'] = 'store_order';
        }
        //根据下单时间搜索
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        //根据付款时间搜索
        $if_start_date_pay = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date_pay']);
        $if_end_date_pay = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date_pay']);
        $pay_start_unixtime = $if_start_date_pay ? strtotime($_GET['query_start_date_pay']) : null;
        $pay_end_unixtime = $if_end_date_pay ? strtotime($_GET['query_end_date_pay']): null;
        if ($pay_start_unixtime || $pay_end_unixtime) {
            $condition['payment_time'] = array('time',array($pay_start_unixtime,$pay_end_unixtime));
        }
        //根据发货时间搜索
        $if_start_date_send = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date_send']);
        $if_end_date_send = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date_send']);
        $send_start_unixtime = $if_start_date_send ? strtotime($_GET['query_start_date_send']) : null;
        $send_end_unixtime = $if_end_date_send ? strtotime($_GET['query_end_date_send']): null;
        if ($send_start_unixtime || $send_end_unixtime) {
            $where = array();
            $where['shipping_time'] = array('time',array($send_start_unixtime,$send_end_unixtime));
            $order_common_id_list = $model_order->getOrderCommonList($where,' order_id ','',1000000);
            $order_id_list_tmp = array();
            if(!empty($order_common_id_list)){
                if(empty($order_id_list)) {
                    foreach($order_common_id_list as $val) {
                        array_push($order_id_list_tmp,$val['order_id']);
                    }
                } else {
                    foreach($order_common_id_list as $val) {
                        if(in_array($val['order_id'],$order_id_list)) {
                            array_push($order_id_list_tmp,$val['order_id']);
                        }
                    }
                }
            }
            unset($order_id_list);
            $order_id_list = $order_id_list_tmp;
            unset($order_id_list_tmp);
            $condition['order_id']= array("in",$order_id_list);
        }

        if ($_GET['skip_off'] == 1) {
            $condition['order_state'] = array('neq',ORDER_STATE_CANCEL);
        }

        $order_by = ' order_id ';
        $order_asc_desc = ' desc';

        if($_GET['payment_time'] == 1){
            $order_by = 'payment_time';
            $order_asc_desc = 'desc';
        }

        $order_list = $model_order->getOrderList($condition, 20, '*', $order_by.' '.$order_asc_desc,'', array('goods','order_goods','order_common','member'));

        //页面中显示那些操作
        foreach ($order_list as $key => $order_info) {

            //显示取消订单
            $order_info['if_cancel'] = $model_order->getOrderOperateState('store_cancel',$order_info);

            //显示调整运费
            $order_info['if_modify_price'] = $model_order->getOrderOperateState('modify_price',$order_info);

            //显示修改价格
            $order_info['if_spay_price'] = $model_order->getOrderOperateState('spay_price',$order_info);

            //显示发货
            $order_info['if_send'] = $model_order->getOrderOperateState('send',$order_info);

            //显示锁定中
            $order_info['if_lock'] = $model_order->getOrderOperateState('lock',$order_info);

            //显示物流跟踪
            $order_info['if_deliver'] = $model_order->getOrderOperateState('deliver',$order_info);

            foreach ($order_info['extend_order_goods'] as $value) {
                $value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
                $value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
                $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
                $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
                if ($value['goods_type'] == 5) {
                    $order_info['zengpin_list'][] = $value;
                } else {
                    $order_info['goods_list'][] = $value;
                }
            }

            if (empty($order_info['zengpin_list'])) {
                $order_info['goods_count'] = count($order_info['goods_list']);
            } else {
                $order_info['goods_count'] = count($order_info['goods_list']) + 1;
            }
            $order_list[$key] = $order_info;

        }

        if($_GET['val'] == "0") {
            $odids = substr($_COOKIE['array_str'],1,strlen($_COOKIE['array_str'])-1);
            if($odids != '') {
                $odids = explode(',',$odids);
                $condition['order_id']= array("in",$odids);
            }
            $order_list_tmp = $model_order->getOrderList($condition, '', '*', $order_by.' '.$order_asc_desc,'', array('goods','order_goods','order_common','member'));
            $this->createExcel($order_list_tmp);
        }

        Tpl::output('order_list',$order_list);
        Tpl::output('show_page',$model_order->showpage());
        self::profile_menu('list',$_GET['state_type']);

        Tpl::showpage('store_order.index');
    }

    /**
     * 生成Excel
     */
    private function createExcel($data = array()){
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'订单编号');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'订单金额');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'运费');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'退款');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'下单时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'付款时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'发货时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'买家名称');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'收货人名称');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'收货人电话');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'收货人地址');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'商品名称');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'商家货号');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'商品条码');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'商品库位');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'单价');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'数量');
        //data
        foreach ((array)$data as $k=>$v){
            foreach((array)$v['extend_order_goods'] as $key=>$goods) {
                $tmp = array();
                $tmp[] = array('data'=>$v['order_sn']);
                $tmp[] = array('data'=>$v['order_amount']);
                $tmp[] = array('data'=>$v['shipping_fee']);
                $tmp[] = array('data'=>$v['refund_amount']);
                $tmp[] = array('data'=>date("Y-m-d H:i:s",$v['add_time']));
                $tmp[] = array('data'=>date("Y-m-d H:i:s",$v['payment_time']));
                $tmp[] = array('data'=>date("Y-m-d H:i:s",$v['extend_order_common']['shipping_time']));
                $tmp[] = array('data'=>$v['buyer_name']);
                $tmp[] = array('data'=>$v['extend_order_common']['reciver_name']);
                $tmp[] = array('data'=>$v['extend_order_common']['reciver_info']['phone']);
                $tmp[] = array('data'=>$v['extend_order_common']['reciver_info']['address']);
                $tmp[] = array('data'=>$goods['goods_name']);
                $tmp[] = array('data'=>$goods['goods_serial']);
                $tmp[] = array('data'=>$goods['goods_barcode']);
                $goods_position_tmp = Model('goods')->getGoodsList(array('goods_id'=>$goods['goods_id']),'goods_position');
                $tmp[] = array('data'=>$goods_position_tmp[0]['goods_position']);
                $tmp[] = array('data'=>$goods['goods_price']);
                $tmp[] = array('data'=>$goods['goods_num']);
                $excel_data[] = $tmp;
            }
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset('实物交易订单',CHARSET));
        $excel_obj->generateXML($excel_obj->charset('实物交易订单',CHARSET).'-'.date('Y-m-d',time()));
    }

/////////////////////////////////////分页打印插件
    public function checkitemOp() {
        setcookie('array_str','');
        $val = $_POST['val'];
        $chk = $_POST['check'];

        $ary = explode(',',$_COOKIE['array_str']);
        if($chk == 'checked'){
            if(!in_array($val,$ary))	array_push($ary, $val);  sort($ary);//升序
        }else{
            unset($ary[array_search($val,$ary)]);
        }
        setcookie('array_str',implode(',',$ary));
    }

    /**
     * 卖家订单详情
     *
     */
    public function show_orderOp() {
        Language::read('member_member_index');
        $order_id = intval($_GET['order_id']);
        if ($order_id <= 0) {
            showMessage(Language::get('wrong_argument'),'','html','error');
        }
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        //开始接收买家留言和图片信息
        if (!empty($_POST['pay_message']) && !empty($_POST['img']) ){
            $message1 = $_POST['pay_message'];
            $message2 = $_POST['img'];
            //var_dump($_POST);
            //这里判断是否收到卖家留言
            $data = array('seller_message' => $message1[$order_id]);
            $update = $model_order->table('order')->where($condition)->update($data);
            if ($update) {
                //更新缓存
                QueueClient::push('delOrderCountCache',$condition);
            }
            $data = array('message_img' => $message2[$order_id]);
            $update = $model_order->table('order')->where($condition)->update($data);
            if ($update) {
                //更新缓存
                QueueClient::push('delOrderCountCache',$condition);
            }
        }
        $condition['store_id'] = $_SESSION['store_id'];
        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods','member'));
        if (empty($order_info)) {
            showMessage(Language::get('store_order_none_exist'),'','html','error');
        }

        $model_refund_return = Model('refund_return');
        $order_list = array();
        $order_list[$order_id] = $order_info;
        $order_list = $model_refund_return->getGoodsRefundList($order_list,1);//订单商品的退款退货显示
        $order_info = $order_list[$order_id];
        $refund_all = $order_info['refund_list'][0];
        if (!empty($refund_all) && $refund_all['seller_state'] < 3) {//订单全部退款商家审核状态:1为待审核,2为同意,3为不同意
            Tpl::output('refund_all',$refund_all);
        }

        //显示锁定中
        $order_info['if_lock'] = $model_order->getOrderOperateState('lock',$order_info);

        //显示调整运费
        $order_info['if_modify_price'] = $model_order->getOrderOperateState('modify_price',$order_info);

        //显示调整价格
        $order_info['if_spay_price'] = $model_order->getOrderOperateState('spay_price',$order_info);

        //显示取消订单
        $order_info['if_cancel'] = $model_order->getOrderOperateState('buyer_cancel',$order_info);

        //显示发货
        $order_info['if_send'] = $model_order->getOrderOperateState('send',$order_info);

        //显示物流跟踪
        $order_info['if_deliver'] = $model_order->getOrderOperateState('deliver',$order_info);

        //显示系统自动取消订单日期
        if ($order_info['order_state'] == ORDER_STATE_NEW) {
            $order_info['order_cancel_day'] = $order_info['add_time'] + ORDER_AUTO_CANCEL_DAY * 24 * 3600;
            // by 33hao.com
            //$order_info['order_cancel_day'] = $order_info['add_time'] + ORDER_AUTO_CANCEL_DAY + 3 * 24 * 3600;
        }

        //显示快递信息
        if ($order_info['shipping_code'] != '') {
            $express = rkcache('express',true);
            $order_info['express_info']['e_code'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_code'];
            $order_info['express_info']['e_name'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_name'];
            $order_info['express_info']['e_url'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_url'];
        }

        //显示系统自动收获时间
        if ($order_info['order_state'] == ORDER_STATE_SEND) {
            $order_info['order_confirm_day'] = $order_info['delay_time'] + ORDER_AUTO_RECEIVE_DAY * 24 * 3600;
            //by 33hao.com
            //$order_info['order_confirm_day'] = $order_info['delay_time'] + ORDER_AUTO_RECEIVE_DAY + 15 * 24 * 3600;
        }

        //如果订单已取消，取得取消原因、时间，操作人
        if ($order_info['order_state'] == ORDER_STATE_CANCEL) {
            $order_info['close_info'] = $model_order->getOrderLogInfo(array('order_id'=>$order_info['order_id']),'log_id desc');
        }

        foreach ($order_info['extend_order_goods'] as $value) {
            $value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
            $value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
            $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
            $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
            if ($value['goods_type'] == 5) {
                $order_info['zengpin_list'][] = $value;
            } else {
                $order_info['goods_list'][] = $value;
            }
        }

        if (empty($order_info['zengpin_list'])) {
            $order_info['goods_count'] = count($order_info['goods_list']);
        } else {
            $order_info['goods_count'] = count($order_info['goods_list']) + 1;
        }

        Tpl::output('order_info',$order_info);

        //发货信息
        if (!empty($order_info['extend_order_common']['daddress_id'])) {
            $daddress_info = Model('daddress')->getAddressInfo(array('address_id'=>$order_info['extend_order_common']['daddress_id']));
            Tpl::output('daddress_info',$daddress_info);
        }

        Tpl::showpage('store_order.show');
    }

    /**
     * 卖家订单留言页面
     *
     */
    public function seller_messageOp(){
        Language::read('member_member_index');

        $model_order = Model('order');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        if ($_GET['order_id'] != '') {
            $condition['order_id'] = $_GET['order_id'];
        }

        $order_by = ' order_id ';
        $order_asc_desc = ' desc';
        $order_list = $model_order->getOrderList($condition, 20, '*', $order_by.' '.$order_asc_desc,'', array('goods','order_goods','order_common','member'));

        //页面中显示那些操作
        foreach ($order_list as $key => $order_info) {

            //显示取消订单
            $order_info['if_cancel'] = $model_order->getOrderOperateState('store_cancel',$order_info);

            //显示调整运费
            $order_info['if_modify_price'] = $model_order->getOrderOperateState('modify_price',$order_info);

            //显示修改价格
            $order_info['if_spay_price'] = $model_order->getOrderOperateState('spay_price',$order_info);

            //显示发货
            $order_info['if_send'] = $model_order->getOrderOperateState('send',$order_info);

            //显示锁定中
            $order_info['if_lock'] = $model_order->getOrderOperateState('lock',$order_info);

            //显示物流跟踪
            $order_info['if_deliver'] = $model_order->getOrderOperateState('deliver',$order_info);

            foreach ($order_info['extend_order_goods'] as $value) {
                $value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
                $value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
                $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
                $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
                if ($value['goods_type'] == 5) {
                    $order_info['zengpin_list'][] = $value;
                } else {
                    $order_info['goods_list'][] = $value;
                }
            }

            if (empty($order_info['zengpin_list'])) {
                $order_info['goods_count'] = count($order_info['goods_list']);
            } else {
                $order_info['goods_count'] = count($order_info['goods_list']) + 1;
            }
            $order_list[$key] = $order_info;

        }


        Tpl::output('order_list',$order_list);
        Tpl::output('order',$condition);
        self::profile_menu('list',$_GET['state_type']);

        Tpl::showpage('seller_message.show');
    }

    /**
     * 卖家订单状态操作
     *
     */
    public function change_stateOp() {
        $state_type	= $_GET['state_type'];
        $order_id	= intval($_GET['order_id']);

        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $_SESSION['store_id'];
        $order_info	= $model_order->getOrderInfo($condition);

        if ($_GET['state_type'] == 'order_cancel') {
            $result = $this->_order_cancel($order_info,$_POST);
        } elseif ($_GET['state_type'] == 'modify_price') {
            $result = $this->_order_ship_price($order_info,$_POST);
        } elseif ($_GET['state_type'] == 'spay_price') {
            $result = $this->_order_spay_price($order_info,$_POST);
        }
        if (!$result['state']) {
            showDialog($result['msg'],'','error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
        } else {
            showDialog($result['msg'],'reload','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
        }
    }

    /**
     * 取消订单
     * @param unknown $order_info
     */
    private function _order_cancel($order_info, $post) {
        $model_order = Model('order');
        $logic_order = Logic('order');

        if(!chksubmit()) {
            Tpl::output('order_info',$order_info);
            Tpl::output('order_id',$order_info['order_id']);
            Tpl::showpage('store_order.cancel','null_layout');
            exit();
        } else {
            $if_allow = $model_order->getOrderOperateState('store_cancel',$order_info);
            if (!$if_allow) {
                return callback(false,'无权操作');
            }
            $msg = $post['state_info1'] != '' ? $post['state_info1'] : $post['state_info'];
            return $logic_order->changeOrderStateCancel($order_info,'seller',$_SESSION['member_name'], $msg);
        }
    }

    /**
     * 修改运费
     * @param unknown $order_info
     */
    private function _order_ship_price($order_info, $post) {
        $model_order = Model('order');
        $logic_order = Logic('order');
        if(!chksubmit()) {
            Tpl::output('order_info',$order_info);
            Tpl::output('order_id',$order_info['order_id']);
            Tpl::showpage('store_order.edit_price','null_layout');
            exit();
        } else {
            $if_allow = $model_order->getOrderOperateState('modify_price',$order_info);
            if (!$if_allow) {
                return callback(false,'无权操作');
            }
            return $logic_order->changeOrderShipPrice($order_info,'seller',$_SESSION['member_name'],$post['shipping_fee']);
        }

    }
    /**
     * 修改商品价格
     * @param unknown $order_info
     */
    private function _order_spay_price($order_info, $post) {
        $model_order = Model('order');
        $logic_order = Logic('order');
        if(!chksubmit()) {
            Tpl::output('order_info',$order_info);
            Tpl::output('order_id',$order_info['order_id']);
            Tpl::showpage('store_order.edit_spay_price','null_layout');
            exit();
        } else {
            $if_allow = $model_order->getOrderOperateState('spay_price',$order_info);
            if (!$if_allow) {
                return callback(false,'无权操作');
            }
            return $logic_order->changeOrderSpayPrice($order_info,'seller',$_SESSION['member_name'],$post['goods_amount']);
        }
    }


    /**
     * 用户中心右边，小导航
     *
     * @param string	$menu_type	导航类型
     * @param string 	$menu_key	当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type='',$menu_key='') {
        Language::read('member_layout');
        switch ($menu_type) {
            case 'list':
                $menu_array = array(
                    array('menu_key'=>'store_order',		'menu_name'=>Language::get('nc_member_path_all_order'),	'menu_url'=>'index.php?act=store_order'),
                    array('menu_key'=>'state_new',			'menu_name'=>Language::get('nc_member_path_wait_pay'),	'menu_url'=>'index.php?act=store_order&op=index&state_type=state_new'),
                    array('menu_key'=>'state_pay',	        'menu_name'=>Language::get('nc_member_path_wait_send'),	'menu_url'=>'index.php?act=store_order&op=store_order&state_type=state_pay'),
                    array('menu_key'=>'state_send',		    'menu_name'=>Language::get('nc_member_path_sent'),	    'menu_url'=>'index.php?act=store_order&op=index&state_type=state_send'),
                    array('menu_key'=>'state_success',		'menu_name'=>Language::get('nc_member_path_finished'),	'menu_url'=>'index.php?act=store_order&op=index&state_type=state_success'),
                    array('menu_key'=>'state_cancel',		'menu_name'=>Language::get('nc_member_path_canceled'),	'menu_url'=>'index.php?act=store_order&op=index&state_type=state_cancel'),
                );
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
