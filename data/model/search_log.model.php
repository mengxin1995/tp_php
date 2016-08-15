<?php
/**
 * 搜索日志模型
 *
 * 
 *
 *
 * by AlphaWu 开发
 */
defined('InShopNC') or exit('Access Invalid!');
class search_logModel extends Model{

    public function __construct(){
        parent::__construct('search_log');
    }

	/**
	 * 读取列表 
	 * @param array $condition
	 *
	 */
	public function getsearchLogList($condition, $page='', $order='', $field='*') {
        $result = $this->field($field)->where($condition)->page($page)->order($order)->select();
        return $result;
	}

    /**
	 * 读取单条记录
	 * @param array $condition
	 *
	 */
    public function getsearchLogInfo($condition) {
        $result = $this->where($condition)->find();
        return $result;
    }

	/*
	 * 增加 
	 * @param array $param
	 * @return bool
	 */
    public function addsearchLog($param){
        return $this->insert($param);	
    }
	
	/*
	 * 删除
	 * @param array $condition
	 * @return bool
	 */
    public function delsearchLog($condition){
        return $this->where($condition)->delete();
    }
	
}
