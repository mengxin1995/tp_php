<?php
/**
 * 客户端上报信息
 *
 *
 *
 **by alphawu 村村兔 2015.12.22
 */


defined('InShopNC') or exit('Access Invalid!');
class station_logControl extends mobileHomeControl{
    /**
	 *
	 * 广告展示
	 */
	public function indexOp(){
		output_error('no');
	}
	/**
	 * 增加数据
	 *
	 */
	public function addOp(){
        $data = array();

	    $data['local_ip'] = $_GET['local_ip'];
		$data['pub_ip'] = getIp();

        $data['member_id'] = $_GET['member_id'];

        $data['report_time'] = TIMESTAMP;

        $model_station_log = Model('station_log');

        $result = $model_station_log->station_log_add($data);
        if(!$result){
            output_error('failed');
        }else{
            output_data('success');
        }
	}
}
