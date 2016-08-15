<?php
/**
 * 客户端屏保
 *
 *
 *
 * by alphawu 村村兔 2015.12.22
 */


defined('InShopNC') or exit('Access Invalid!');
class screensaverControl extends mobileHomeControl{

	public function __construct() {
        parent::__construct();
    }

    /**
     *
     */
    public function indexOp() {
        $order = ' start_time asc';
        $condition['end_time'] = array('gt',TIMESTAMP);

        $model_ss = Model('screensaver');

        $list = $model_ss->getList($condition,'',$order);
        if($list){
            $ids = array();
            foreach ($list as $value) {
                $ids[] = $value['id'];
            }
            $model_ss->num_update($ids);

            output_data(array('list'=>$list));
        }
        else{
            output_error('no');
        }
    }
}
