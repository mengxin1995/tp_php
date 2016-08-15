<?php
/**
 * 跨区域运费管理
 *
 * 
 *
 *
 * by alphawu 村村兔 2016.01.11
 */
defined('InShopNC') or exit('Access Invalid!');
class feeModel extends Model{
    public function __construct(){
        parent::__construct();
    }
    /**
     * 计算数量
     * 
     * @param array $condition 条件
     * $param string $table 表名
     * @return int
     */
    public function getFeeCount($condition) {
        $result = $this->table('fee')->where($condition)->count();
        return $result;
    }

    /**
     * 获取单条数据
     * 
     * @param array $condition 条件
     * @param string $table 表名
     * @return array 一维数组
     */
    public function getFee($condition) {
        $resule = $this->table('fee')->where($condition)->find();
        return $resule;
    }
    /**
     * 获取单条数据
     *
     * @param array $id2id
     * @param string $table 表名
     * @return array 一维数组
     */
    public function getFeeById2Id($id2id) {
		$condition = array(
			'id2id'	=> $id2id
		);

        $resule = $this->table('fee')->where($condition)->find();
        return $resule;
    }
    /**
     * 获取单条数据
     *
     * @param $id
     * @param string $table 表名
     * @return array 一维数组
     */
    public function getFeeById($id) {
		$condition = array(
			'id'	=> $id
		);

        $resule = $this->table('fee')->where($condition)->find();
        return $resule;
    }
	/**
	 * 分类列表
	 *
	 * @param array $condition 查询条件
	 * @param obj $page 分页对象
	 * @return array 二维数组
	 */
	public function getFeeList($condition,$order ='id desc'){
		$resule = $this->table('fee')->where($condition)->order($order)->select();
		return $resule;
	}
    /**
     * 添加一条运费记录
     *
     * @param array $data
     * @return bool
     */
    public function addFee($data) {
        $result = $this->table('fee')->insert($data);
        return $result;
    }
	/**
	 * 更新运费记录
	 *
	 * @param array $data
	 * @param int $id
	 * @return bool
	 */
	public function updateFee($data,$id){
		$condition = array();
		$condition['id'] = $id;

		if(is_array($data) && !empty($data)){
			return $this->table('fee')->where($condition)->update($data);
		}else{
			return false;
		}
	}

	/**
	 * 删除运费记录
	 *
	 * @param string $id
	 * @return bool
	 */
	public function delFeeById($id){
		$condition = array();
		$condition['id'] = $id;
		if(!empty($id)) {
			return $this->table('fee')->where($condition)->delete();
		}else{
			return false;
		}
	}

	/**
	 * 删除运费记录
	 *
	 * @param string $id
	 * @return bool
	 */
	public function delFee($condition){
		if(!empty($condition)) {
			return $this->table('fee')->where($condition)->delete();
		}else{
			return false;
		}
	}
}