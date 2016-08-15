<?php
/**
 * weixin_fans
 *
 * by alphawu 村村兔  www.cuncuntu.com 开发
 */
defined('InShopNC') or exit('Access Invalid!');

class weixin_fansModel extends Model{
    public function __construct(){
        parent::__construct('weixin_fans');
    }


    /**
     * 插入数据
     * @param $insert
     * @return mixed
     */
    public function addWeixinFans($insert){
        $result = $this->table('weixin_fans')->insert($insert);
        return $result;
    }

    /**
     * 获取单条粉丝帐号信息
     *
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getFansInfo($condition, $field = '*') {
        return $this->table('weixin_fans')->field($field)->where($condition)->find();
    }

    /**
     * 获取单条粉丝帐号信息 by id
     *
     * @param int $id
     * @param string $field
     * @return array
     */
    public function getFansInfoByID($id, $field = '*') {
        $condition = array();
        $condition['fanid'] = $id;
        return $this->table('weixin_fans')->field($field)->where($condition)->find();
    }
    /**
     * 获取单条粉丝帐号信息 by openid
     *
     * @param string $openid
     * @param string $field
     * @return array
     */
    public function getFansInfoByOpenID($openid, $field = '*') {
        $condition = array();
        $condition['openid'] = $openid;
        return $this->table('weixin_fans')->field($field)->where($condition)->find();
    }
    /**
     * 获取单条粉丝帐号信息 by unionid
     *
     * @param string $unionid
     * @param string $field
     * @return array
     */
    public function getFansInfoByUnionID($unionid, $field = '*') {
        $condition = array();
        $condition['unionid'] = $unionid;
        return $this->table('weixin_fans')->field($field)->where($condition)->order('fanid desc')->find();
    }

    /**
     * 获取单条粉丝帐号信息 by memberid
     *
     * @param string $memberid
     * @param string $field
     * @return array
     */
    public function getFansInfoByMemberID($memberid, $field = '*') {
        $condition = array();
        $condition['memberid'] = $memberid;
        return $this->table('weixin_fans')->field($field)->where($condition)->find();
    }

    /**
     * 编辑粉丝信息,依据openid
     * @param $update
     * @param $openid
     * @return mixed
     */

    public function editFansInfoByOpenID($update,$openid){

        $condition = array();
        $condition['openid'] = $openid;

        $result = $this->table('weixin_fans')->where($condition)->update($update);

        return $result;

    }
    /**
     * 编辑粉丝信息,依据unionid
     * @param $update
     * @param $openid
     * @return mixed
     */

    public function editFansInfoByUnionID($update,$unionid){

        $condition = array();
        $condition['unionid'] = $unionid;

        $result = $this->table('weixin_fans')->where($condition)->update($update);

        return $result;

    }

    /**
     * 编辑粉丝信息,依据openid
     * @param $update
     * @param $openid
     * @return mixed
     */

    public function weixinunbind($memberid){

        $condition = array();
        $condition['memberid'] = $memberid;


        $update = array();
        $update['memberid'] = '0';
        $update['follow'] = '0';
        $update['unfollowtime'] = time();
        $update['updatetime'] = time();

        $result = $this->table('weixin_fans')->where($condition)->update($update);

        return $result;

    }

}
