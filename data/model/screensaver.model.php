<?php
/**
 * 客户端屏保
 *
 * by alphawu 村村兔 2015.12.22
 */
defined('InShopNC') or exit('Access Invalid!');

class screensaverModel extends Model
{
    public function __construct(){
        parent::__construct('screensaver');
    }

   /**
     * 新增屏保位
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function ss_add($param){
        if (empty($param)){
            return false;
        }
        return $this->insert($param);
    }

    /**
     * 删除一条屏保位
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function ss_delByID($id)
    {
        $condition = array();
        $condition['id'] = $id;

        return $this->where($condition)->delete();
    }
    /**
     * 删除屏保位
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function ss_del($condition)
    {
        return $this->where($condition)->delete();
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

    /**
     * 更新屏保
     *
     * @param array $update 更新数据
     * @param int $id 需要更新的编号
     * @return boolean
     */
    public function ss_edit($update, $id) {
        $condition = array();
        $condition['id'] = $id;
        return $this->table('screensaver')->where($condition)->update($update);
    }
    /**
     * 更新下载次数
     *
     * @param array $update 更新数据
     * @param int $id 需要更新的编号
     * @return boolean
     */
    public function num_update($ids) {
        $condition = array();
        $condition['id'] = array('in',$ids);
        return $this->table('screensaver')->where($condition)->setInc('down_num',1);
    }
}
