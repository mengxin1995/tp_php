<?php
/**
 * 合伙人申请表模型
 *
 * 
 *
 *
 * by nijianqi  shop.cuncuntu.com 开发
 */
defined('InShopNC') or exit('Access Invalid!');
class store_joinin_partnerModel extends Model{

    public function __construct(){
        parent::__construct('store_joinin_partner');
    }


    const STATE1 = 1;       // 审核通过
    const STATE2 = 2;       // 审核失败
    const STATE0 = 0;    // 等待审核
	/**
	 * 所有的服务站列表
	 * @param array $condition
	 *
	 */
    public function getList($condition, $field = '*', $page = 10, $order = 'partner_id desc') {
        return $this->table('store_joinin_partner')->field($field)->where($condition)->order($order)->page($page)->select();
    }
    /**
     * 已审核的服务站列表
     *
     * @param array $condition 条件
     * @param array $field 字段
     * @param string $page 分页
     * @param string $order 排序
     * @return array
     */
    public function getLockUpList($condition, $field = '*', $page = 10, $order = "partner_id desc") {
        if (!isset($condition['paystate_search'])) {
            $condition['paystate_search']  = array('neq', self::STATE0);
        }
        return $this->getList($condition, $field, $page, $order);
    }

    /**
     * 等待审核服务站列表
     *
     * @param array $condition 条件
     * @param array $field 字段
     * @param string $page 分页
     * @param string $order 排序
     * @return array
     */
    public function getWaitVerifyList($condition, $field = '*', $page = 10, $order = "partner_id desc") {
            $condition['paystate_search']  = self::STATE0;
        return $this->getList($condition, $field, $page, $order);
    }
	/**
	 * 待审核服务站数量
	 * @param unknown $condition
	 */
	public function getStoreJoininPartnerCount($condition) {
	    return  $this->where($condition)->count();
	}

    /**
	 * 读取单条记录
	 * @param array $condition
	 *
	 */
    public function getOne($condition){
        $result = $this->where($condition)->find();
        return $result;
    }

	/*
	 *  判断是否存在 
	 *  @param array $condition
     *
	 */
	public function isExist($condition) {
        $result = $this->getOne($condition);
        if(empty($result)) {
            return FALSE;
        }
        else {
            return TRUE;
        }
	}

	/*
	 * 增加 
	 * @param array $param
	 * @return bool
	 */
    public function save($param){
        return $this->insert($param);	
    }

	/*
	 * 更新
	 * @param array $update
	 * @param array $condition
	 * @return bool
	 */
    public function modify($update, $condition){
        return $this->where($condition)->update($update);
    }

	/*
	 * 删除
	 * @param array $condition
	 * @return bool
	 */
    public function drop($condition){
        return $this->where($condition)->delete();
    }

}
