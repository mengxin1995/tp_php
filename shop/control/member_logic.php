<?php
/**
 * 代金券
 *
 *
 *
 **by 好商城V3 www.33hao.com 运营版*/


defined('InShopNC') or exit('Access Invalid!');

class member_logicControl extends BaseMemberControl
{

    public function __construct()
    {

        parent::__construct();

        /**
         * 读取语言包
         */
        Language::read('member_layout,member_logic');

        //定义状态常量
    }

    /*
     * 默认显示百度地图信息
     */
    public function indexOp()
    {
        $this->profile_menu('logic_index');


        $parameters = array('quyuId' => '330151', 'loginName' => 'zjwl', 'pwd' => '1', 'mobile' => '15257578320', 'top' => 1);
        $client = new soapclient('http://115.29.168.164:8006/ServiceDeal.asmx?wsdl');
        $string = $client->HulinyTrackSelectTop($parameters);
        $HulinyTrackInfo = $string->HulinyTrackSelectTopResult->HulinyTrackInfo;


        $info = array();
        $info['x'] = $HulinyTrackInfo->X;
        $info['y'] = $HulinyTrackInfo->Y;
        $info['title'] = '车辆信息';
        $info['content'] = '当前时间：'.$HulinyTrackInfo->createTime.'<br/>当前时速：'.number_format (floatval($HulinyTrackInfo->Speed)/3.6,2).'KM/H';

        Tpl::output('info', $info);
        Tpl::showpage('member_logic.index');
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string $menu_type 导航类型
     * @param string $menu_key 当前导航的menu_key
     * @param array $array 附加菜单
     * @return
     */
    private function profile_menu($menu_key = '')
    {
        $menu_array = array(
            1 => array('menu_key' => 'logic_index', 'menu_name' => '物流车辆', 'menu_url' => 'index.php?act=member_logic&op=index'),
        );
        Tpl::output('member_menu', $menu_array);
        Tpl::output('menu_key', $menu_key);
    }
}
