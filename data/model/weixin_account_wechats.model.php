<?php
/**
 * weixin_uni_group
 *
 * by alphawu 村村兔  www.cuncuntu.com 开发
 */
defined('InShopNC') or exit('Access Invalid!');

class weixin_account_wechatsModel extends Model{
    public function __construct(){
        parent::__construct('weixin_account_wechats');
    }


    /**
     * 插入数据
     * @param $insert
     * @return mixed
     */
    public function addAccountWechats($insert){
        $result = $this->table('weixin_account_wechats')->insert($insert);
        return $result;
    }

    /**
     * 获取单条微信帐号信息
     *
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getWechatInfo($condition, $field = '*') {
        return $this->table('weixin_account_wechats')->field($field)->where($condition)->find();
    }

    /**
     * 获取单条微信帐号信息 by id
     *
     * @param int $id
     * @param string $field
     * @return array
     */
    public function getWechatInfoByID($id, $field = '*') {
        $condition = array();
        $condition['acid'] = $id;
        return $this->table('weixin_account_wechats')->field($field)->where($condition)->find();
    }
    /**
     * 获取单条微信帐号信息 by originid
     *
     * @param string $originid
     * @param string $field
     * @return array
     */
    public function getWechatInfoByOriginID($originid, $field = '*') {
        $condition = array();
        $condition['originid'] = $originid;
        return $this->table('weixin_account_wechats')->field($field)->where($condition)->find();
    }
    /**
     * 获取单条微信帐号信息 by appid
     *
     * @param string $appid
     * @param string $field
     * @return array
     */
    public function getWechatInfoByAppID($appid, $field = '*') {
        $condition = array();
        $condition['appid'] = $appid;
        return $this->table('weixin_account_wechats')->field($field)->where($condition)->find();
    }


    /**
     * 编辑微信平台信息,依据openid
     * @param $update
     * @param $id
     * @return mixed
     */

    public function editWechatInfoByID($update,$id){

        $condition = array();
        $condition['acid'] = $id;

        $result = $this->table('weixin_account_wechats')->where($condition)->update($update);

        return $result;

    }
}
