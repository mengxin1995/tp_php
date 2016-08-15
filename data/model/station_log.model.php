<?php
/**
 * 客户端上报日志
 *
 * by alphawu 村村兔 2015.12.22
 */
defined('InShopNC') or exit('Access Invalid!');

class station_logModel extends Model
{
    public function __construct(){
        parent::__construct('station_log');
    }

   /**
     * 新增一条记录
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function station_log_add($param){
        if (empty($param)){
            return false;
        }
        return $this->insert($param);
    }

    /**
     * 根据条件查询多条记录
     *
     * @param array $condition 查询条件
     * @param obj $page 分页对象
     * @return array 二维数组
     */
    public function getList($condition, $page='', $order='', $field='*') {
        $result = $this->field($field)->where($condition)->page($page)->order($order)->select();
        return $result;
    }
}
