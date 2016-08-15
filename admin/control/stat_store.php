<?php
/**
 * 统计管理（店铺）
 **by 好商城V3 www.33hao.com 运营版*/

defined('InShopNC') or exit('Access Invalid!');
class stat_storeControl extends SystemControl{
	private $links = array(
        array('url'=>'act=stat_store&op=newstore','lang'=>'stat_newstore'),
        array('url'=>'act=stat_store&op=hotrank','lang'=>'stat_storehotrank'),
        array('url'=>'act=stat_store&op=storesales','lang'=>'stat_storesales'),
        array('url'=>'act=stat_store&op=degree','lang'=>'stat_storedegree'),
        array('url'=>'act=stat_store&op=storearea','lang'=>'stat_storearea'),
    );

    private $search_arr;//处理后的参数
    private $store_class;//店铺分类

	public function __construct(){
        parent::__construct();
        Language::read('stat');
        import('function.statistics');
        import('function.datehelper');
        $model = Model('stat');
        //存储参数
		$this->search_arr = $_REQUEST;
		//处理搜索时间
		if (in_array($_REQUEST['op'],array('hotrank','storesales'))){
		    $this->search_arr = $model->dealwithSearchTime($this->search_arr);
    		//获得系统年份
    		$year_arr = getSystemYearArr();
    		//获得系统月份
    		$month_arr = getSystemMonthArr();
    		//获得本月的周时间段
    		$week_arr = getMonthWeekArr($this->search_arr['week']['current_year'], $this->search_arr['week']['current_month']);
    		Tpl::output('year_arr', $year_arr);
    		Tpl::output('month_arr', $month_arr);
    		Tpl::output('week_arr', $week_arr);
		}
		Tpl::output('search_arr', $this->search_arr);
		//店铺分类
		$this->store_class = rkcache('store_class', true);
		Tpl::output('store_class', $this->store_class);
    }
    /**
	 * 新增店铺
	 */
    public function newstoreOp(){
      	$model = Model();
		$com = $model->table('monitor')->select();
		$com_ip_list = '';
		if (!empty($com)) {
			foreach ($com as $val) {
				$com_ip_list = $com_ip_list . ($val['com_ip'] . ",");
			}
		}
		$condition['com_ip'] = array("in", $com_ip_list);
		$list = $model->table('monitor_warnsum')->where($condition)->select();
		Tpl::output("list", $list);
		Tpl::showpage('stat.newstore');
    }

	public function newstore1Op(){
		$model = Model();
		$com = $model->table('monitor')->select();
		$com_ip_list = '';
		if (!empty($com)) {
			foreach ($com as $val) {
				$com_ip_list = $com_ip_list . ($val['com_ip'] . ",");
			}
		}
		$condition1['com_ip'] = $_GET['com_ip'];
		$list1 = $model->table('monitor_warnsum')->where($condition1)->select();
		Tpl::output("list", $list1);

		//$model = Model();
		$now = time();
		$list['mem'] = array();
		$jiange = 60 * 60 * 12;
		// $condition['com_ip'] = $_GET['com_ip'];
		for ($i=1; $i<=30; $i++) {
			$condition['CreateTime'] = array('elt', $now);
			$x = $model->table('monitor_warning')->where($condition)->order('CreateTime desc')->find();
			$list['warning_cpu_alerted'][$i] = $x['warning_cpu_alerted'];
			$list['warning_cpu_alarm'][$i] = $x['warning_cpu_alarm'];
			$list['warning_mem_alerted'][$i] = $x['warning_mem_alerted'];
			$list['warning_mem_alarm'][$i] = $x['warning_mem_alarm'];
			$list['warning_tem_alerted'][$i] = $x['warning_tem_alerted'];
			$list['warning_tem_alarm'][$i] = $x['warning_tem_alarm'];
			$now -= $jiange;
		}
		Tpl::output('l', $list);

		Tpl::showpage('stat.newstore1');
	}
	/**
     * 热卖排行
     */
    public function hotrankOp(){
        if(!$this->search_arr['search_type']){
			$this->search_arr['search_type'] = 'day';
		}
		$model = Model('stat');
		//获得搜索的开始时间和结束时间
		$searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);
		Tpl::output('searchtime',implode('|',$searchtime_arr));
        Tpl::output('top_link',$this->sublink($this->links, 'hotrank'));
        Tpl::showpage('stat.store.hotrank');
    }
    /**
     * 热卖排行列表
     */
    public function hotrank_listOp(){
        $datanum = 30;
        $model = Model('stat');
        switch ($_GET['type']){
		   case 'ordernum':
		       $sort_text = '下单量';
		       break;
		   default:
		       $_GET['type'] = 'orderamount';
		       $sort_text = '下单金额';
		       break;
		}
		$where = array();
        $searchtime_arr_tmp = explode('|',$this->search_arr['t']);
		foreach ((array)$searchtime_arr_tmp as $k=>$v){
		    $searchtime_arr[] = intval($v);
		}
        //店铺分类
    	$search_sclass = intval($_REQUEST['search_sclass']);
		if ($search_sclass){
		    $where['sc_id'] = $search_sclass;
		}
		$where['order_isvalid'] = 1;//计入统计的有效订单
		$where['order_add_time'] = array('between',$searchtime_arr);
		//查询统计数据
		$field = ' store_id,store_name ';
		switch ($_GET['type']){
		   case 'ordernum':
		       $field .= ' ,COUNT(*) as ordernum ';
		       $orderby = 'ordernum desc';
		       break;
		   default:
		       $_GET['type'] = 'orderamount';
		       $field .= ' ,SUM(order_amount) as orderamount ';
		       $orderby = 'orderamount desc';
		       break;
		}
		$orderby .= ',store_id';
		$statlist = $model->statByStatorder($where, $field, 0, $datanum, $orderby, 'store_id');
		foreach ((array)$statlist as $k=>$v){
		    $statlist[$k]['sort'] = $k+1;
		}
		/**
		 * 飙升榜
		 */
		$soaring_statlist = array();//飙升榜数组
		//查询期间产生订单的店铺数
		$where = array();
		//店铺分类
    	$search_sclass = intval($_REQUEST['search_sclass']);
		if ($search_sclass){
		    $where['sc_id'] = $search_sclass;
		}
		$where['order_isvalid'] = 1;//计入统计的有效订单
		$where['order_add_time'] = array('between',$searchtime_arr);
		$field = 'COUNT(*) as countnum';
		$countnum = $model->getoneByStatorder($where, $field);
		$countnum = $countnum['countnum'];
		if ($countnum > 0){
    		$store_arr = array();
    		$field = 'store_id,store_name,order_amount';
    		for ($i=0; $i<$countnum; $i+=1000){//由于数据库底层的限制，所以每次查询1000条
    		    $order_list = array();
    		    $order_list = $model->statByStatorder($where, $field, 0, $i.',1000', 'order_id');
    		    foreach ((array)$order_list as $k=>$v){
    		        $store_arr[$v['store_id']]['orderamount'] = $store_arr[$v['store_id']]['orderamount'] + $v['order_amount'];
    		        $store_arr[$v['store_id']]['ordernum'] = intval($store_arr[$v['store_id']]['ordernum']) + 1;
    		        $store_arr[$v['store_id']]['store_name'] = $v['store_name'];
    		        $store_arr[$v['store_id']]['store_id'] = $v['store_id'];
    		    }
    		}
    		//查询同一时间周期相比的环比数值
    		$where = array();
    		$stime = $searchtime_arr[0] - ($searchtime_arr[1] - $searchtime_arr[0]) - 1;
    		$etime = $searchtime_arr[0] - 1;
    		//店铺分类
        	$search_sclass = intval($_REQUEST['search_sclass']);
    		if ($search_sclass){
    		    $where['sc_id'] = $search_sclass;
    		}
    		$where['order_isvalid'] = 1;//计入统计的有效订单
    		$where['order_add_time'] = array('between',array($stime,$etime));
    		$field = 'COUNT(*) as up_countnum';
    		$up_countnum = $model->getoneByStatorder($where, $field);
    		$up_countnum = $up_countnum['up_countnum'];
    		$up_store_arr = array();
    		if ($up_countnum > 0){
        		$field = 'store_id,store_name,order_amount';
        		for ($i=0; $i<$up_countnum; $i+=1000){//由于数据库底层的限制，所以每次查询1000条
        		    $order_list = array();
        		    $order_list = $model->statByStatorder($where, $field, 0, $i.',1000', 'store_id');
        		    foreach ((array)$order_list as $k=>$v){
        		        $up_store_arr[$v['store_id']]['orderamount'] = $up_store_arr[$v['store_id']]['orderamount'] + $v['order_amount'];
        		        $up_store_arr[$v['store_id']]['ordernum'] = intval($up_store_arr[$v['store_id']]['ordernum']) + 1;
        		    }
        		}
    		}
    		//计算环比飙升数值
    		$soaring_arr = array();
    		foreach ((array)$store_arr as $k=>$v){
    		    if ($up_store_arr[$k][$_GET['type']] > 0){//上期数值大于0，则计算飙升值，否则不计入统计
    		        $soaring_arr[$k] = round((($v[$_GET['type']]-$up_store_arr[$k][$_GET['type']])/$up_store_arr[$k][$_GET['type']]*100),2);
    		    }
    		}
    		arsort($soaring_arr);//降序排列数组
    		$i = 1;
    		//取出前10名飙升店铺
    		foreach ((array)$soaring_arr as $k=>$v){
    		    if ($i <= $datanum){
            		$tmp = array();
            		$tmp['sort'] = $i;
            		$tmp['store_name'] = $store_arr[$k]['store_name'];
            		$tmp['store_id'] = $store_arr[$k]['store_id'];
            		$tmp['hb'] = $v;
            		switch ($_GET['type']){
            		   case 'ordernum':
            		       $tmp['ordernum'] = $store_arr[$k]['ordernum'];
            		       break;
            		   case 'orderamount':
            		       $tmp['orderamount'] = ncPriceFormat($store_arr[$k]['orderamount']);
            		       break;
            		}
    		        $soaring_statlist[] = $tmp;
    		        $i++;
    		    } else {
    		        break;
    		    }
    		}
		}
		Tpl::output('soaring_statlist',$soaring_statlist);
		Tpl::output('statlist',$statlist);
		Tpl::output('sort_text',$sort_text);
		Tpl::output('stat_field',$_GET['type']);
		Tpl::showpage('stat.store.hotrank.list','null_layout');
    }
    /**
     * 店铺等级
     */
    public function degreeOp(){
    	$where = array();
    	$field = ' count(*) as allnum,grade_id ';
    	$model = Model('stat');
    	//查询店铺分类下的店铺
    	$search_sclass = intval($_REQUEST['search_sclass']);
    	if ($search_sclass > 0){
    	    $where['sc_id'] = $search_sclass;
    	}
    	$storelist = $model->getNewStoreStatList($where, $field, 0, '', 0, 'grade_id');
    	$sd_list = $model->getStoreDegree();
    	$statlist['headertitle'] = array();
    	$statlist['data'] = array();
    	//处理数组数据
    	if(!empty($storelist)){
    		foreach ($storelist as $k=>$v){
    			$storelist[$k]['p_name'] = $v['grade_id'] > 0?$sd_list[$v['grade_id']]:'平台店铺';
    			$storelist[$k]['allnum'] = intval($v['allnum']);
    			$statlist['headertitle'][] = $v['grade_id'] > 0?$sd_list[$v['grade_id']]:'平台店铺';
    			$statlist['data'][] = $v['allnum'];
    		}
    		$data = array(
    			'title'=>'店铺等级统计',
    			'name'=>'店铺个数',
    			'label_show'=>true,
    			'series'=>$storelist
    		);
    		Tpl::output('stat_json',getStatData_Pie($data));
    	}
		Tpl::output('top_link',$this->sublink($this->links, 'degree'));
		Tpl::showpage('stat.storedegree');
    }
	/**
	 * 查看店铺列表
	 */
	public function showstoreOp(){
		$model = Model('stat');
		$where = array();
		if (in_array($_GET['type'],array('newbyday','newbyweek','newbymonth','storearea'))){
		    $actionurl = 'index.php?act=stat_store&op=showstore&type='.$_GET['type'].'&t='.$this->search_arr['t'];
    		$searchtime_arr_tmp = explode('|',$this->search_arr['t']);
    		foreach ((array)$searchtime_arr_tmp as $k=>$v){
    		    $searchtime_arr[] = intval($v);
    		}
		    $where['store_time'] = array('between',$searchtime_arr);
		}
		//商品分类
		$sc_id = intval($_GET['scid']);
		if ($sc_id > 0){
		    $where['sc_id'] = $sc_id;
		    $actionurl .="&scid=$sc_id";
		}
		//省份
		if (isset($_GET['provid'])){
		    $province_id = intval($_GET['provid']);
		    $where['province_id'] = $province_id;
		    $actionurl .="&provid=$province_id";
		}

		if ($_GET['exporttype'] == 'excel'){
		    $store_list = $model->getNewStoreStatList($where);
		} else {
		    $store_list = $model->getNewStoreStatList($where, '', 10);
		}

		//店铺等级
		$model_grade = Model('store_grade');
		$grade_list = $model_grade->getGradeList();
		if (!empty($grade_list)){
			$search_grade_list = array();
			foreach ($grade_list as $k => $v){
				$search_grade_list[$v['sg_id']] = $v['sg_name'];
			}
		}
		//导出Excel
        if ($_GET['exporttype'] == 'excel'){
            //导出Excel
			import('libraries.excel');
		    $excel_obj = new Excel();
		    $excel_data = array();
		    //设置样式
		    $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
			//header
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>'店铺名称');
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>'店主账号');
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>'店主卖家账号');
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>'所属等级');
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>'有效期至');
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>'开店时间');
			//data
			foreach ($store_list as $k=>$v){
				$excel_data[$k+1][] = array('data'=>$v['store_name']);
				$excel_data[$k+1][] = array('data'=>$v['member_name']);
				$excel_data[$k+1][] = array('data'=>$v['seller_name']);
				$excel_data[$k+1][] = array('data'=>$search_grade_list[$v['grade_id']]);
				$excel_data[$k+1][] = array('data'=>$v['store_end_time']?date('Y-m-d', $v['store_end_time']):'无限制');
				$excel_data[$k+1][] = array('data'=>date('Y-m-d', $v['store_time']));
			}
			$excel_data = $excel_obj->charset($excel_data,CHARSET);
			$excel_obj->addArray($excel_data);
		    $excel_obj->addWorksheet($excel_obj->charset('新增店铺',CHARSET));
		    $excel_obj->generateXML($excel_obj->charset('新增店铺',CHARSET).date('Y-m-d-H',time()));
			exit();
        }
        Tpl::output('search_grade_list', $search_grade_list);
        Tpl::output('actionurl',$actionurl);
		Tpl::output('store_list',$store_list);
		Tpl::output('show_page',$model->showpage(2));
		$this->links[] = array('url'=>'act=stat_store&op=showstore','lang'=>'stat_storelist');
		Tpl::output('top_link',$this->sublink($this->links, 'showstore'));
	    Tpl::showpage('stat.info.storelist');
	}

	/**
	 * 销售统计
	 */
	public function storesalesOp(){
	    if(!$this->search_arr['search_type']){
			$this->search_arr['search_type'] = 'day';
		}
		$model = Model('stat');
		//获得搜索的开始时间和结束时间
		$searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);
		Tpl::output('searchtime',implode('|',$searchtime_arr));
        Tpl::output('top_link',$this->sublink($this->links, 'storesales'));
        Tpl::showpage('stat.store.sales');
	}
	/**
	 * 店铺销售统计列表
	 */
	public function storesales_listOp(){
		$model = Model('stat');
	    $searchtime_arr_tmp = explode('|',$this->search_arr['t']);
		foreach ((array)$searchtime_arr_tmp as $k=>$v){
		    $searchtime_arr[] = intval($v);
		}
		$where = array();
		$where['order_isvalid'] = 1;//计入统计的有效订单
		$where['order_add_time'] = array('between',$searchtime_arr);
		//店铺分类
    	$search_sclass = intval($_REQUEST['search_sclass']);
		if ($search_sclass){
		    $where['sc_id'] = $search_sclass;
		}
		//店铺名称
		$where['store_name'] = array('like',"%{$_GET['search_sname']}%");
		//查询总条数
	    $count_arr = $model->getoneByStatorder($where, 'COUNT(DISTINCT store_id) as countnum');
		$countnum = intval($count_arr['countnum']);
		//列表字段
		$field = " store_id,store_name,SUM(order_amount) as orderamount, COUNT(*) as ordernum, COUNT(DISTINCT buyer_id) as membernum";
		//排序
		$orderby_arr = array('membernum asc','membernum desc','ordernum asc','ordernum desc','orderamount asc','orderamount desc');
	    if (!in_array(trim($this->search_arr['orderby']),$orderby_arr)){
		    $this->search_arr['orderby'] = 'membernum desc';
		}
		$orderby = trim($this->search_arr['orderby']).',store_id asc';

	    if ($this->search_arr['exporttype'] == 'excel'){
		    $statlist = $model->statByStatorder($where, $field, 0, 0, $orderby, 'store_id');
		} else {
		    $statlist = $model->statByStatorder($where, $field, array(20,$countnum), 0, $orderby, 'store_id');
		    foreach ((array)$statlist as $k=>$v){
		        $v['view'] = "<a href='javascript:void(0);' nc_type='showtrends' data-param='{\"storeid\":\"{$v['store_id']}\"}'>走势图</a>";
		        $statlist[$k] = $v;
		    }
		}

		//列表header
		$statheader = array();
        $statheader[] = array('text'=>'店铺名称','key'=>'store_name');
        $statheader[] = array('text'=>'下单会员数','key'=>'membernum','isorder'=>1);
        $statheader[] = array('text'=>'下单量','key'=>'ordernum','isorder'=>1);
        $statheader[] = array('text'=>'下单金额','key'=>'orderamount','isorder'=>1);

	    //导出Excel
        if ($this->search_arr['exporttype'] == 'excel'){
            //导出Excel
			import('libraries.excel');
		    $excel_obj = new Excel();
		    $excel_data = array();
		    //设置样式
		    $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
			//header
			foreach ($statheader as $k=>$v){
			    $excel_data[0][] = array('styleid'=>'s_title','data'=>$v['text']);
			}
			//data
			foreach ($statlist as $k=>$v){
    			foreach ($statheader as $h_k=>$h_v){
    			    $excel_data[$k+1][] = array('data'=>$v[$h_v['key']]);
    			}
			}
			$excel_data = $excel_obj->charset($excel_data,CHARSET);
			$excel_obj->addArray($excel_data);
		    $excel_obj->addWorksheet($excel_obj->charset('店铺销售统计',CHARSET));
		    $excel_obj->generateXML($excel_obj->charset('店铺销售统计',CHARSET).date('Y-m-d-H',time()));
			exit();
        }
		Tpl::output('statlist',$statlist);
		Tpl::output('statheader',$statheader);
		Tpl::output('orderby',$this->search_arr['orderby']);
		Tpl::output('actionurl',"index.php?act={$this->search_arr['act']}&op={$this->search_arr['op']}&t={$this->search_arr['t']}&search_sclass={$search_sclass}&search_sname={$_GET['search_sname']}");
		Tpl::output('show_page',$model->showpage(2));
		Tpl::showpage('stat.listandorder','null_layout');
	}
	/**
	 * 销售走势
	 */
	public function storesales_trendsOp(){
	    $storeid = intval($_GET['storeid']);
	    if ($storeid <= 0){
	        Tpl::output('stat_error','走势图加载错误');
	        Tpl::showpage('stat.store.salestrends');
	        exit();
	    }
	    if (!$_GET['search_type']){
	        $_GET['search_type'] = 'day';
	    }
		$model = Model('stat');
		$where = array();
		$where['store_id'] = $storeid;
	    $searchtime_arr_tmp = explode('|',$this->search_arr['t']);
		foreach ((array)$searchtime_arr_tmp as $k=>$v){
		    $searchtime_arr[] = intval($v);
		}
		$where['order_isvalid'] = 1;//计入统计的有效订单
		$where['order_add_time'] = array('between',$searchtime_arr);

		$field = " store_id,store_name,SUM(order_amount) as orderamount, COUNT(*) as ordernum, COUNT(DISTINCT buyer_id) as membernum";
		$stat_arr = array('orderamount'=>array(),'ordernum'=>array(),'membernum'=>array());
		$statlist = array();
		if($_GET['search_type'] == 'day'){
			//构造横轴数据
			for($i=0; $i<24; $i++){
				//横轴
				foreach ($stat_arr as $k=>$v){
				    $stat_arr[$k]['xAxis']['categories'][] = "$i";
				    $statlist[$k][$i] = 0;
				}
			}
			$field .= ' ,HOUR(FROM_UNIXTIME(order_add_time)) as timeval ';
		}
	    if($_GET['search_type'] == 'week'){
	        //构造横轴数据
	        for($i=1; $i<=7; $i++){
	            $tmp_weekarr = getSystemWeekArr();
				//横轴
	            foreach ($stat_arr as $k=>$v){
				    $stat_arr[$k]['xAxis']['categories'][] = $tmp_weekarr[$i];
				    $statlist[$k][$i] = 0;
				}
				unset($tmp_weekarr);
			}
			$field .= ' ,WEEKDAY(FROM_UNIXTIME(order_add_time))+1 as timeval ';
		}
		if($_GET['search_type'] == 'month'){
		    //计算横轴的最大量（由于每个月的天数不同）
			$dayofmonth = date('t',$searchtime_arr[0]);
		    //构造横轴数据
			for($i=1; $i<=$dayofmonth; $i++){
				//横轴
			    foreach ($stat_arr as $k=>$v){
				    $stat_arr[$k]['xAxis']['categories'][] = $i;
				    $statlist[$k][$i] = 0;
				}
			}
			$field .= ' ,day(FROM_UNIXTIME(order_add_time)) as timeval ';
		}
		//查询数据
		$statlist_tmp = $model->statByStatorder($where, $field, 0, '', 'timeval','timeval');
		//整理统计数组
		$storename = '';
	    if($statlist_tmp){
			foreach($statlist_tmp as $k => $v){
			    $storename = $v['store_name'];
			    foreach ($stat_arr as $t_k=>$t_v){
			        if ($k == 'orderamount'){
			            $statlist[$t_k][$v['timeval']] = round($v[$t_k],2);
			        } else {
			            $statlist[$t_k][$v['timeval']] = intval($v[$t_k]);
			        }
			    }
			}
		}
		foreach ($stat_arr as $k=>$v){
		    $stat_arr[$k]['legend']['enabled'] = false;
    		switch ($k){
    		    case 'orderamount':
    		        $caption = '下单金额';
    		        break;
    		    case 'ordernum':
    		        $caption = '下单量';
    		        break;
    		    default:
    		        $caption = '下单会员数';
    		        break;
    		}
    		$stat_arr[$k]['series'][0]['name'] = $caption;
		    $stat_arr[$k]['series'][0]['data'] = array_values($statlist[$k]);
    		$stat_arr[$k]['title'] = $caption.'走势';
    		$stat_arr[$k]['yAxis'] = $caption;
    		//得到统计图数据
    		$stat_json[$k] = getStatData_LineLabels($stat_arr[$k]);
		}
		Tpl::output('storename',$storename);
		Tpl::output('stat_json',$stat_json);
		Tpl::showpage('stat.store.salestrends','null_layout');
	}
	/**
	 * 地区分布
	 */
	public function storeareaOp(){
		$model = Model('stat');
		//获得搜索的开始时间和结束时间
		$searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);
		$where = array();
		if (trim($_GET['search_time'])){
		    $where['store_time'] = array('elt',strtotime($_GET['search_time']));
		}
		$search_sclass = intval($_REQUEST['search_sclass']);
		if ($search_sclass > 0){
		    $where['sc_id'] = $search_sclass;
		}
		$field = ' province_id, COUNT(*) as storenum ';
		$statlist_tmp = $model->statByStore($where, $field, 0, 0, 'storenum desc,province_id', 'province_id');
		// 地区
        $province_array = Model('area')->getTopLevelAreas();
        //地图显示等级数组
        $level_arr = array(array(1,2,3),array(4,5,6),array(7,8,9),array(10,11,12));
        $statlist = array();
		foreach ((array)$statlist_tmp as $k=>$v){
		    $v['level'] = 4;//排名
		    foreach ($level_arr as $lk=>$lv){
		        if (in_array($k+1,$lv)){
		            $v['level'] = $lk;//排名
		        }
		    }
		    $province_id = intval($v['province_id']);
		    $v['sort'] = $k+1;
		    $v['provincename'] = ($t = $province_array[$province_id]) ? $t : '其他';
		    $statlist[$province_id] = $v;
		}
        $stat_arr = array();
		foreach ((array)$province_array as $k=>$v){
		    if ($statlist[$k]){
		        $stat_arr[] = array('cha'=>$k,'name'=>$v,'des'=>"，店铺量：{$statlist[$k]['storenum']}",'level'=>$statlist[$k]['level']);
		    } else {
		        $stat_arr[] = array('cha'=>$k,'name'=>$v,'des'=>'，无订单数据','level'=>4);
		    }
		}
		$stat_json = getStatData_Map($stat_arr);
		Tpl::output('stat_json',$stat_json);
		Tpl::output('statlist',$statlist);
		$actionurl = 'index.php?act=stat_store&op=showstore&type=storearea';
		if (trim($_GET['search_time'])){
		    $actionurl = $actionurl.'&t=0|'.strtotime($_GET['search_time']);
		}
		if ($search_sclass > 0){
		    $actionurl .= "&scid=$search_sclass";
		}
		Tpl::output('actionurl',$actionurl);
		Tpl::output('top_link',$this->sublink($this->links, 'storearea'));
		Tpl::showpage('stat.storearea');
	}
}
