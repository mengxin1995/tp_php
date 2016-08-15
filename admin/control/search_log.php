<?php
/**
 * 搜索日志
 *
 *
 *
 **by alphawu*/

defined('InShopNC') or exit('Access Invalid!');
class search_logControl extends SystemControl{
	const EXPORT_SIZE = 5000;

	public function __construct(){
		parent::__construct();
		Language::read('search_log');
	}

	/**
	 * 日志列表
	 *
	 */
	public function listOp(){
		$model = Model('search_log');
		$condition = array();
		if (!empty($_GET['keyword'])){
			$key = explode(" ",$_GET['keyword']);
			$condition['keyword']=array(array('like','%'.$key[0].'%'),array('like','%'.$key[1].'%'),'and');
		}
		if(!empty($_GET['time_from'])){
			$time1	= $_GET['time_from'];
		}
		if(!empty($_GET['time_to'])){
			$time2	= $_GET['time_to'];
			if($time2 !== false) $time2 = date('Y-m-d',strtotime("$time2 +1 day"));
		}
		if ($time1 && $time2){
			$condition['search_time'] = array('between',array($time1,$time2));
		}elseif($time1){
			$condition['search_time'] = array('egt',$time1);
		}elseif($time2){
			$condition['search_time'] = array('elt',$time2);
		}
		$list = $model->where($condition)->order('id desc')->page(20)->select();//var_dump($list);exit;
//		$search = Model()->table('search,gsearch')->field('search_id,search_name,gid,gname')->join('left')->on('search.search_gid=gsearch.gid')->select();
		$count = $model->where($condition)->count("id");
		Tpl::output('scount',$count);
		Tpl::output('list',$list);
		Tpl::output('page',$model->showpage());
		Tpl::showpage('search_log.index');
	}
	/**
	 * 导出第一步
	 */
	public function export_step1Op(){
		$model = Model('search_log');
		$condition = array();
		if(!empty($_GET['time_from'])){
			$time1	= $_GET['time_from'];
		}
		if(!empty($_GET['time_to'])){
			$time2	= $_GET['time_to'];
			if($time2 !== false) $time2 = date('Y-m-d',strtotime("$time2 +1 day"));
		}
		if ($time1 && $time2){
			$condition['search_time'] = array('between',array($time1,$time2));
		}elseif($time1){
			$condition['search_time'] = array('egt',$time1);
		}elseif($time2){
			$condition['search_time'] = array('elt',$time2);
		}
		if (!is_numeric($_GET['curpage'])){
			$count = $model->where($condition)->count();
			$array = array();
			if ($count > self::EXPORT_SIZE ){	//显示下载链接
				$page = ceil($count/self::EXPORT_SIZE);
				for ($i=1;$i<=$page;$i++){
					$limit1 = ($i-1)*self::EXPORT_SIZE + 1;
					$limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
					$array[$i] = $limit1.' ~ '.$limit2 ;
				}
				Tpl::output('list',$array);
				Tpl::output('murl','index.php?act=search_log&op=list');
				Tpl::showpage('export.excel');
			}else{	//如果数量小，直接下载
				$data = $model->where($condition)->order('id desc')->limit(self::EXPORT_SIZE)->select();
				$this->createExcel($data);
			}
		}else{	//下载
			$limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
			$limit2 = self::EXPORT_SIZE;
			$data = $model->where($condition)->order('id desc')->limit("{$limit1},{$limit2}")->select();
			$this->createExcel($data);
		}
	}

	/**
	 * 生成excel
	 *
	 * @param array $data
	 */
	private function createExcel($data = array()){
		Language::read('export');
		import('libraries.excel');
		$excel_obj = new Excel();
		$excel_data = array();
		//设置样式
		$excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
		//header
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('search_log_do'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('search_log_dotime'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'IP');
		foreach ((array)$data as $k=>$v){
			$tmp = array();
			$tmp[] = array('data'=>$v['keyword']);
			$tmp[] = array('data'=>date('Y-m-d H:i:s',$v['search_time']));
			$tmp[] = array('data'=>$v['ip']);
			$excel_data[] = $tmp;
		}
		$excel_data = $excel_obj->charset($excel_data,CHARSET);
		$excel_obj->addArray($excel_data);
		$excel_obj->addWorksheet($excel_obj->charset(L('nc_search_log'),CHARSET));
		$excel_obj->generateXML($excel_obj->charset(L('nc_search_log'),CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
	}
}
