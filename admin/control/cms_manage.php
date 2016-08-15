<?php
/**
 * cms管理
 *
 *
 *
 **by 好商城V3 www.33hao.com 运营版*/

defined('InShopNC') or exit('Access Invalid!');
class cms_manageControl extends SystemControl{

	public function __construct(){
		parent::__construct();
		Language::read('cms');
	}

	public function indexOp() {
        $this->cms_manageOp();
	}

	/**
	 * cms设置
	 */
	public function cms_manageOp() {
        $com_cpu = Model();
        $com_info = $com_cpu->table('monitor_bottom')->find();
        Tpl::output("state", $com_info);
        $this->show_menu('cms_manage');
        Tpl::showpage('cms_manage');
	}

	/**
	 * cms设置保存
	 */
	public function cms_manage_saveOp() {
        $model_setting = Model('');
        $update_array = array(
            'cpu_isuse' => $_POST['cpu_isuse'],
            'mem_isuse' => $_POST['mem_isuse'],
            'wangluo' => $_POST['wangluo'],
            'fuwu' => $_POST['fuwu'],
            'time' => $_POST['zhouqi'] == 0 ? 1 : $_POST['zhouqi'],
        );
        $result = $model_setting->table('monitor_bottom')->where('id=1')->update($update_array);
        if ($result === true){
            $this->log(Language::get('cms_log_manage_save'), 0);
            showMessage(Language::get('nc_common_save_succ'));
        }else {
            $this->log(Language::get('cms_log_manage_save'), 0);
            showMessage(Language::get('nc_common_save_fail'));
        }
	}

    private function show_menu($menu_key) {
        $menu_array = array(
            'cms_manage'=>array('menu_type'=>'link','menu_name'=>Language::get('nc_manage'),'menu_url'=>'index.php?act=cms_manage&op=cms_manage'),
        );
        $menu_array[$menu_key]['menu_type'] = 'text';
        Tpl::output('menu',$menu_array);
    }


}
