<?php
/**
 * 商品分析
 **by 好商城V3 www.33hao.com 运营版*/

defined('InShopNC') or exit('Access Invalid!');

class stat_goodsControl extends mobileHomeControl{
    private $search_arr;//处理后的参数
    private $gc_arr;//分类数组
    private $choose_gcid;//选择的分类ID

    public function __construct(){
        import('function.statistics');
        import('function.datehelper');
        $model = Model('stat');
        //存储参数
		$this->search_arr = $_REQUEST;
		//处理搜索时间
		if (in_array($this->search_arr['op'],array('pricerange','hotgoods','goods_sale'))){
		    $this->search_arr = $model->dealwithSearchTime($this->search_arr);
		}
        /**
         * 处理商品分类
         */
        $this->choose_gcid = ($t = intval($_REQUEST['choose_gcid']))>0?$t:0;
        $gccache_arr = Model('goods_class')->getGoodsclassCache($this->choose_gcid,3);
        $this->gc_arr = $gccache_arr['showclass'];
    }
	/**

	/**
	 * 热卖商品列表
	 */
	public function hotgoods_listOp(){
	    $model = Model('stat');
        switch ($_GET['type']){
		   case 'goodsnum':
		       break;
		   default:
		       $_GET['type'] = 'orderamount';
		       break;
		}

		$where = array();
		$where['order_isvalid'] = 1;//计入统计的有效订单
	    $searchtime_arr_tmp = explode('|',$this->search_arr['t']);
		foreach ((array)$searchtime_arr_tmp as $k=>$v){
		    $searchtime_arr[] = intval($v);
		}
		$where['order_add_time'] = array('between',$searchtime_arr);
		//商品分类
	    if ($this->choose_gcid > 0){
		    //获得分类深度
		    $depth = $this->gc_arr[$this->choose_gcid]['depth'];
		    $where['gc_parentid_'.$depth] = $this->choose_gcid;
		}
		//查询统计数据
		$field = ' goods_id,goods_name ';
		switch ($_GET['type']){
		   case 'goodsnum':
		       $field .= ' ,SUM(goods_num) as goodsnum ';
		       $orderby = 'goodsnum desc';
		       break;
		   default:
		       $_GET['type'] = 'orderamount';
		       $field .= ' ,SUM(goods_pay_price) as orderamount ';
		       $orderby = 'orderamount desc';
		       break;
		}
		$orderby .= ',goods_id';
		$statlist = $model->statByStatordergoods($where, $field, 0, 50, $orderby, 'goods_id');
		foreach ((array)$statlist as $k=>$v){
    		switch ($_GET['type']){
    		   case 'goodsnum':
    		       $stat_arr['series'][0]['data'][$k] = array('name'=>strval($v['goods_name']),'y'=>intval($v[$_GET['type']]));
    		       break;
    		   case 'orderamount':
    		       $stat_arr['series'][0]['data'][$k] = array('name'=>strval($v['goods_name']),'y'=>floatval($v[$_GET['type']]));
    		       break;
    		}
		    $statlist[$k]['sort'] = $k+1;
		}

		$goods_list_new = array();
		$goods_mode = Model('goods');
		foreach($statlist as $k=>$v){
			$goods_info = $goods_mode->getGoodsInfoByID($v['goods_id'],'goods_image');
			$image = $goods_info['goods_image'];
			if(preg_match('/(^\d+)(_)/',$image,$match)){
				$image_url = UPLOAD_SITE_URL.'/shop/store/goods/'.$match[1].'/'.$image;
				$v['pic'] = $image_url;
			}
			else{
				$v['pic'] = UPLOAD_SITE_URL.'/shop/store/goods/'.'default.jpg';
			}
			$v['goods_price'] = $goods_info['goods_price'];
			$v['goods_promotion_price'] = $goods_info['goods_promotion_price'];
			$v['goods_marketprice'] = $goods_info['goods_marketprice'];
			$goods_list_new[]=$v;
		}

		if($goods_list_new){
			output_data(array('list'=>$goods_list_new));
		}
		else
		{
			output_error('没有商品信息');
		}
	}

	/**
     * 商品销售明细
     */
    public function goods_saleOp(){
    	if(!$this->search_arr['search_type']){
			$this->search_arr['search_type'] = 'day';
		}
		$model = Model('stat');
		//获得搜索的开始时间和结束时间
		$searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);
		//获取相关数据
		$where = array();
		$where['order_isvalid'] = 1;//计入统计的有效订单
		$where['order_add_time'] = array('between',$searchtime_arr);
        //品牌
        $brand_id = intval($_REQUEST['b_id']);
		if ($brand_id > 0){
	        $where['brand_id'] = $brand_id;
	    }
        //商品分类
        if ($this->choose_gcid > 0){
		    //获得分类深度
		    $depth = $this->gc_arr[$this->choose_gcid]['depth'];
		    $where['gc_parentid_'.$depth] = $this->choose_gcid;
		}
        if(trim($_GET['goods_name'])){
			$where['goods_name'] = array('like','%'.trim($_GET['goods_name']).'%');
		}
		if(trim($_GET['store_name'])){
			$where['store_name'] = array('like','%'.trim($_GET['store_name']).'%');
		}
		$field = 'goods_id,goods_name,store_id,store_name,goods_commonid,SUM(goods_num) as goodsnum,COUNT(DISTINCT order_id) as ordernum,SUM(goods_pay_price) as goodsamount';
		//排序
		$orderby_arr = array('goodsnum asc','goodsnum desc','ordernum asc','ordernum desc','goodsamount asc','goodsamount desc');
        if (!in_array(trim($this->search_arr['orderby']),$orderby_arr)){
		    $this->search_arr['orderby'] = 'goodsnum desc';
		}
		$orderby = trim($this->search_arr['orderby']).',goods_id asc';
        //查询记录总条数
		$count_arr = $model->getoneByStatordergoods($where, 'COUNT(DISTINCT goods_id) as countnum');
		$countnum = intval($count_arr['countnum']);

		$goods_list = $model->statByStatordergoods($where, $field, array(10,$countnum), 0, $orderby, 'goods_id');

		$goods_list_new = array();
		$goods_mode = Model('goods');
		foreach($goods_list as $k=>$v){
			$goods_info = $goods_mode->getGoodsInfoByID($v['goods_id'],'goods_image');
			$image = $goods_info['goods_image'];
			if(preg_match('/(^\d+)(_)/',$image,$match)){
				$image_url = UPLOAD_SITE_URL.'/shop/store/goods/'.$match[1].'/'.$image;
				$v['pic'] = $image_url;
			}
			else{
				$v['pic'] = UPLOAD_SITE_URL.'/shop/store/goods/'.'default.jpg';
			}
			$v['goods_price'] = $goods_info['goods_price'];
			$v['goods_promotion_price'] = $goods_info['goods_promotion_price'];
			$v['goods_marketprice'] = $goods_info['goods_marketprice'];
			$goods_list_new[]=$v;
		}

		if($goods_list_new){
			output_data(array('list'=>$goods_list_new));
		}
		else
		{
			output_error('没有商品信息');
		}
    }

}
