<?php
/**
 * weixin_uni_group
 *
 * by alphawu 村村兔  www.cuncuntu.com 开发
 */
defined('InShopNC') or exit('Access Invalid!');

class weixin_uni_groupModel extends Model{
    public function __construct(){
        parent::__construct('weixin_uni_group');
    }


    /**
     * 插入数据
     * @param $insert
     * @return mixed
     */
    public function addUniGroup($insert){
        $result = $this->table('weixin_uni_group')->insert($insert);
        return $result;
    }

	
}
