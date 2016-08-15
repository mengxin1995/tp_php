<?php
/**
 * 客户端屏保管理
 *
 *
 *
 **by alphawu 村村兔 2015.12.22
 */

defined('InShopNC') or exit('Access Invalid!');

class screensaverControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('screensaver');
	}

	/**
	 *
	 * 添加屏保
	 */
	public function ss_addOp(){
		if(!chksubmit()){
		    Tpl::showpage('screensaver_add');
		}else{
			$lang = Language::getLangContent();
			$mode_ss  = Model('screensaver');
		    $upload		= new UploadFile();
			/**
			 * 验证
			 */
			$obj_validate = new Validate();
			$validate_arr = array();
			$validate_arr[] = array("input"=>$_POST["ss_name"], "require"=>"true", "message"=>$lang['ss_can_not_null']);
			$validate_arr[] = array("input"=>$_POST["ss_start_time"], "require"=>"true", "message"=>$lang['must_select_start_time']);
			$validate_arr[] = array("input"=>$_POST["ss_end_time"], "require"=>"true", "message"=>$lang['must_select_end_time']);

			$validate_arr[] = array("input"=>$_FILES['ss_pic']['name'], "require"=>"true", "message"=>$lang['picss_null_error']);

			$obj_validate->validateparam = $validate_arr;
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$insert_array['title']       = trim($_POST['ss_name']);
				$insert_array['description']       = trim($_POST['ss_description']);
				$insert_array['add_time']  = TIMESTAMP;
				$insert_array['start_time']  = $this->getunixtime($_POST['ss_start_time']);
				$insert_array['end_time']    = $this->getunixtime($_POST['ss_end_time']);

				$upload->set('default_dir',ATTACH_ADV);
				$result	= $upload->upfile('ss_pic');
				if (!$result){
					showMessage($upload->error,'','','error');
				}
				$insert_array['img_1'] = $upload->file_name;


				//广告信息入库
				$result = $mode_ss->ss_add($insert_array);

			    if ($result){
					$this->log(L('ss_add_succ').'['.$_POST["ss_name"].']',null);
					showMessage($lang['ss_add_succ'],'index.php?act=screensaver&op=index');
				}else {
					showMessage($lang['ss_add_fail'],'index.php?act=screensaver&op=index');
				}
		}
	  }
	}

	/**
	 *
	 * 屏保列表
	 */
	public function indexOp(){
		$lang = Language::getLangContent();
		$mode_ss  = Model('screensaver');
		if (chksubmit()){
			if (is_array($_POST['del_id']) && !empty($_POST['del_id'])){
				$in_array_id = implode(",", $_POST['del_id']);
				$condition_del = array();
				$condition_del['id'] = array('in',$in_array_id);

				$mode_ss->ss_del($condition_del);

			}
			$this->log(L('ss_del_succ').'[ID:'.$in_array_id.']',null);
			showMessage($lang['ss_del_succ'],'index.php?act=screensaver&op=index');
		}
		/**
		 * 分页
		 */
		$page	 = new Page();
		$page->setEachNum(20);
		$page->setStyle('admin');
		$condition = array();

		$limit     = '';
		$orderby   = '';
		$ss_info = $mode_ss->getList($condition,$page,$limit,$orderby);
		Tpl::output('ss_info',$ss_info);
		Tpl::output('page',$page->show());
		Tpl::showpage('screensaver.index');
	}

	/**
	 *
	 * 修改屏保
	 */
    public function ss_editOp(){
    	if($_POST['form_submit'] != 'ok'){
			$mode_ss  = Model('screensaver');
			$condition['id'] = intval($_GET['id']);
			$ss_list = $mode_ss->getList($condition);
			Tpl::output('ss_list',$ss_list);
			Tpl::showpage('screensaver.edit');
    	}else{
    		$lang = Language::getLangContent();
			$mode_ss  = Model('screensaver');
		    $upload		= new UploadFile();
			/**
			 * 验证
			 */
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			    array("input"=>$_POST["ss_name"], "require"=>"true", "message"=>$lang['ss_can_not_null']),
				array("input"=>$_POST["ss_start_date"], "require"=>"true","message"=>$lang['must_select_start_time']),
				array("input"=>$_POST["ss_end_date"], "require"=>"true", "message"=>$lang['must_select_end_time'])
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$param['title']      = trim($_POST['ss_name']);
				$param['description']      = trim($_POST['ss_description']);
				$param['start_time'] = $this->getunixtime(trim($_POST['ss_start_date']));
				$param['end_time']   = $this->getunixtime(trim($_POST['ss_end_date']));


				if($_FILES['ss_pic']['name'] != ''){
				  	$upload->set('default_dir',ATTACH_ADV);
					$result	= $upload->upfile('ss_pic');
					if (!$result){
						showMessage($upload->error,'','','error');
					}
					$param['img_1'] = $upload->file_name;
				}else{
					$param['img_1'] = trim($_POST['img_1_old']);
				}


				$result = $mode_ss->ss_edit($param,intval($_GET['id']));

				$url ='index.php?act=screensaver&op=index';
				if ($result){
					$this->log(L('ss_change_succ').'['.$_POST["ss_name"].']',null);
					showMessage($lang['ss_change_succ'],$url);
				}else {
					showMessage($lang['ss_change_fail'],$url);
				}
			}
    	}
    }

    /**
     *
     * 删除屏保
     */
    public function ss_delOp(){
    	$lang = Language::getLangContent();
		$mode_ss  = Model('screensaver');
       /**
		 * 删除一个屏保
		 */

		$result  = $mode_ss->ss_delByID(intval($_GET['id']));

		if (!$result){
			showMessage($lang['ss_del_fail'],'index.php?act=screensaver&op=index');die;
		}else{
			$this->log(L('ss_del_succ').'['.intval($_GET['id']).']',null);
			showMessage($lang['ss_del_succ'],'index.php?act=screensaver&op=index');die;
		}
		 /**
		  * 多选删除多个屏保
		  */
//	if (chksubmit()){
//
//        if($_POST['del_id'] != ''){
//			foreach ($_POST['del_id'] as $k=>$v){
//				$v = intval($v);
//
//				$adv->adv_del($v);
//
//			}
//			$url = array(
//						array(
//							'url'=>'index.php?act=adv&op=adv',
//							'msg'=>$lang['goback_adv_manage'],
//						)
//					);
//			showMessage($lang['adv_del_succ'],$url);
//		}
//	}
    }

    /**
     *
     * 获取UNIX时间戳
     */
	public function getunixtime($time){
		$array     = explode("-", $time);
		$unix_time = mktime(0,0,0,$array[1],$array[2],$array[0]);
		return $unix_time;
	}
}
