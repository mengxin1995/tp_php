<?php
/**
 * weixin_fans_geo
 *
 * by alphawu 村村兔  www.cuncuntu.com 开发
 */
defined('InShopNC') or exit('Access Invalid!');

class weixin_fans_geoModel extends Model{
    public function __construct(){
        parent::__construct('weixin_fans_geo');
    }


    /**
     * 插入数据
     * @param $insert
     * @return mixed
     */
    public function addWeixinFansGeo($insert){
        $result = $this->table('weixin_fans_geo')->insert($insert);
        return $result;
    }

    /**
     * 获取单条粉丝地理位置信息 by openid
     *
     * @param string $openid
     * @param string $field
     * @return array
     */
    public function getFansGeoByOpenID($openid, $field = '*') {
        $condition = array();
        $condition['openid'] = $openid;
        return $this->table('weixin_fans_geo')->field($field)->where($condition)->order(' id desc ')->find();
    }
}
