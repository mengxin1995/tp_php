<?php
/**
 * 会员管理
 *
 *
 *
 **by 好商城V3 www.33hao.com 运营版*/

defined('InShopNC') or exit('Access Invalid!');

class memberControl extends SystemControl{
	const EXPORT_SIZE = 1000;
	public function __construct(){
		parent::__construct();
		Language::read('member');
	}

	/**
	 * 会员管理
	 */
	public function memberOp(){
		$lang	= Language::getLangContent();
		$model_member = Model('member');
				/**
		 * 删除
		 */
		if (chksubmit()){
			/**
			 * 判断是否是管理员，如果是，则不能删除
			 */
			/**
			 * 删除
			 */
			if (!empty($_POST['del_id'])){
				if (is_array($_POST['del_id'])){
					foreach ($_POST['del_id'] as $k => $v){
						$v = intval($v);
						$rs = true;//$model_member->del($v);
						if ($rs){
							//删除该会员商品,店铺
							//获得该会员店铺信息
							$member = $model_member->getMemberInfo(array(
								'member_id'=>$v
							));
							//删除用户
							$model_member->del($v);
						}
					}
				}
				showMessage($lang['nc_common_del_succ']);
			}else {
				showMessage($lang['nc_common_del_fail']);
			}
		}
		//会员级别
		$member_grade = $model_member->getMemberGradeArr();
		if ($_GET['search_field_value'] != '') {
    		switch ($_GET['search_field_name']){
    			case 'member_name':
    				$condition['member_name'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    			case 'member_email':
    				$condition['member_email'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
				//好商 城v3- b11
				case 'member_mobile':
    				$condition['member_mobile'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    			case 'member_truename':
    				$condition['member_truename'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    		}
		}
		switch ($_GET['search_state']){
			case 'no_informallow':
				$condition['inform_allow'] = '2';
				break;
			case 'no_isbuy':
				$condition['is_buy'] = '0';
				break;
			case 'no_isallowtalk':
				$condition['is_allowtalk'] = '0';
				break;
			case 'no_memberstate':
				$condition['member_state'] = '0';
				break;
			case 'is_station':
				$condition['member_is_station'] = '1';
				break;
			case 'has_station':
				$condition['member_has_station'] = '1';
				break;
		}
		//会员等级
		$search_grade = intval($_GET['search_grade']);
		if ($_GET['search_grade']!='' && $search_grade >= 0 && $member_grade){
		    $condition['member_exppoints'] = array(array('egt',$member_grade[$search_grade]['exppoints']),array('lt',$member_grade[$search_grade+1]['exppoints']),'and');
		}
		//排序
		$order = trim($_GET['search_sort']);
		if (empty($order)) {
		    $order = 'member_id desc';
		}
		$member_list = $model_member->getMemberList($condition, '*', 10, $order);		
		//整理会员信息
		if (is_array($member_list)){
			foreach ($member_list as $k=> $v){
				$member_list[$k]['member_time'] = $v['member_time']?date('Y-m-d H:i:s',$v['member_time']):'';
				$member_list[$k]['member_login_time'] = $v['member_login_time']?date('Y-m-d H:i:s',$v['member_login_time']):'';
				$member_list[$k]['member_grade'] = ($t = $model_member->getOneMemberGrade($v['member_exppoints'], false, $member_grade))?$t['level_name']:'';
			}
		}
		Tpl::output('member_grade',$member_grade);
		Tpl::output('search_sort',trim($_GET['search_sort']));
		Tpl::output('search_field_name',trim($_GET['search_field_name']));
		Tpl::output('search_field_value',trim($_GET['search_field_value']));
		Tpl::output('member_list',$member_list);
		Tpl::output('page',$model_member->showpage());
		Tpl::showpage('member.index');
	}

	/**
	 * 会员修改
	 */
	public function member_editOp(){
		$lang	= Language::getLangContent();
		$model_member = Model('member');
		/**
		 * 保存
		 */
		if (chksubmit()){
			/**
			 * 验证
			 */
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["member_email"], "require"=>"true", 'validator'=>'Email', "message"=>$lang['member_edit_valid_email']),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$update_array = array();
				$update_array['member_id']			= intval($_POST['member_id']);
				if (!empty($_POST['member_passwd'])){
					$update_array['member_passwd'] = md5($_POST['member_passwd']);
				}
				$update_array['member_email']		= $_POST['member_email'];
				$update_array['member_truename']	= $_POST['member_truename'];
				$update_array['member_sex'] 		= $_POST['member_sex'];
				$update_array['member_qq'] 			= $_POST['member_qq'];
				$update_array['member_ww']			= $_POST['member_ww'];
				$update_array['member_rate']		= round(floatval($_POST['member_rate']),2);
				$update_array['wholesale'] 		= $_POST['wholesale'];
				$update_array['inform_allow'] 		= $_POST['inform_allow'];
				$update_array['is_buy'] 			= $_POST['isbuy'];
				$update_array['is_allowtalk'] 		= $_POST['allowtalk'];
				$update_array['member_state'] 		= $_POST['memberstate'];
				//v3-b11 新增
				$update_array['member_areaid']		= $_POST['area_id'];
				$update_array['member_cityid']		= $_POST['city_id'];
                $update_array['member_provinceid']	= $_POST['province_id'];
                $update_array['member_areainfo']	= $_POST['area_info'];
				$update_array['member_mobile'] 		= $_POST['member_mobile'];
				$update_array['member_email_bind'] 	= intval($_POST['memberemailbind']);
				$update_array['member_mobile_bind'] = intval($_POST['membermobilebind']);

			
				if (!empty($_POST['member_avatar'])){
					$update_array['member_avatar'] = $_POST['member_avatar'];
				}
				$result = $model_member->editMember(array('member_id'=>intval($_POST['member_id'])),$update_array);
				if ($result){
					$url = array(
					array(
					'url'=>'index.php?act=member&op=member',
					'msg'=>$lang['member_edit_back_to_list'],
					),
					array(
					'url'=>'index.php?act=member&op=member_edit&member_id='.intval($_POST['member_id']),
					'msg'=>$lang['member_edit_again'],
					),
					);
					$this->log(L('nc_edit,member_index_name').'[ID:'.$_POST['member_id'].']',1);
					showMessage($lang['member_edit_succ'],$url);
				}else {
					showMessage($lang['member_edit_fail']);
				}
			}
		}
		$condition['member_id'] = intval($_GET['member_id']);
		$member_array = $model_member->getMemberInfo($condition);

		Tpl::output('member_array',$member_array);
		Tpl::showpage('member.edit');
	}

	/**
	 * 新增会员
	 */
	public function member_addOp(){
		$lang	= Language::getLangContent();
		$model_member = Model('member');
		/**
		 * 保存
		 */
		if (chksubmit()){
			/**
			 * 验证
			 */
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			    array("input"=>$_POST["member_name"], "require"=>"true", "message"=>$lang['member_add_name_null']),
			    array("input"=>$_POST["member_passwd"], "require"=>"true", "message"=>'密码不能为空'),
			    array("input"=>$_POST["member_email"], "require"=>"true", 'validator'=>'Email', "message"=>$lang['member_edit_valid_email'])
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$insert_array = array();
				$insert_array['member_name']	= trim($_POST['member_name']);
				$insert_array['member_passwd']	= trim($_POST['member_passwd']);
				$insert_array['member_email']	= trim($_POST['member_email']);
				$insert_array['member_truename']= trim($_POST['member_truename']);
				$insert_array['member_mobile']= trim($_POST['member_mobile']);
				$insert_array['member_sex'] 	= trim($_POST['member_sex']);
				$insert_array['member_qq'] 		= trim($_POST['member_qq']);
				$insert_array['member_ww']		= trim($_POST['member_ww']);
                //默认允许举报商品
                $insert_array['inform_allow'] 	= '1';
				if (!empty($_POST['member_avatar'])){
					$insert_array['member_avatar'] = trim($_POST['member_avatar']);
				}

				$result = $model_member->addMember($insert_array);
				if ($result){
					$url = array(
					array(
					'url'=>'index.php?act=member&op=member',
					'msg'=>$lang['member_add_back_to_list'],
					),
					array(
					'url'=>'index.php?act=member&op=member_add',
					'msg'=>$lang['member_add_again'],
					),
					);
					$this->log(L('nc_add,member_index_name').'[	'.$_POST['member_name'].']',1);
					showMessage($lang['member_add_succ'],$url);
				}else {
					showMessage($lang['member_add_fail']);
				}
			}
		}
		Tpl::showpage('member.add');
	}

	/**
	 * ajax操作
	 */
	public function ajaxOp(){
		switch ($_GET['branch']){
			/**
			 * 验证会员是否重复
			 */
			case 'check_user_name':
				$model_member = Model('member');
				$condition['member_name']	= $_GET['member_name'];
				$condition['member_id']	= array('neq',intval($_GET['member_id']));
				$list = $model_member->getMemberInfo($condition);
				if (empty($list)){
					echo 'true';exit;
				}else {
					echo 'false';exit;
				}
				break;
				/**
			 * 验证邮件是否重复
			 */
			case 'check_email':
				$model_member = Model('member');
				$condition['member_email'] = $_GET['member_email'];
				$condition['member_id'] = array('neq',intval($_GET['member_id']));
				$list = $model_member->getMemberInfo($condition);
				if (empty($list)){
					echo 'true';exit;
				}else {
					echo 'false';exit;
				}
				break;

            /*
             * 判断是否是站长
             */

            case 'station':
                $model_member = Model('member');

                $member_info = $model_member->getMemberInfoByID($_GET['id']);
                if(empty($member_info['member_truename']) && $_GET['value']==1){
                    echo '用户真实姓名为空，不能操作。';exit;
                }
                if(empty($member_info['member_mobile']) && $_GET['value']==1){
                    echo '用户手机为空，不能操作。';exit;
                }

                //1.删除该用户的所有地址信息
                //2.新增一条地址信息

                $model_address = Model('address');
                $model_delivery = Model('delivery_point');

                $condition_delivery = array();
                $condition_delivery['dlyp_name'] = $member_info['member_name'];
                $condition_delivery['dlyp_state'] = 1;//必须是启用的自提点,方可成为站长?2015.11.06 alpha

                $delivery_info = $model_delivery->getDeliveryPointInfo($condition_delivery);
                if(empty($delivery_info) && $_GET['value']==1){
                    echo '没有对应的自提点信息，无法设置为站长。';exit;
                }
                $insert_data = array();

                $insert_data['member_id'] = $_GET['id'];
                $insert_data['true_name'] = $delivery_info['dlyp_truename'];
                $insert_data['area_id'] = $delivery_info['dlyp_area_3'];
                $insert_data['city_id'] = $delivery_info['dlyp_area_2'];
                $insert_data['area_info'] = $delivery_info['dlyp_area_info'];
                $insert_data['address'] = $delivery_info['dlyp_address'];
                $insert_data['tel_phone'] = $delivery_info['dlyp_telephony'];
                $insert_data['mob_phone'] = $delivery_info['dlyp_mobile'];
                $insert_data['is_default'] = 1;
                $insert_data['dlyp_id'] = $delivery_info['dlyp_id'];

                $update_array['member_is_station'] = $_GET['value'];
                $where_array['member_id'] = $_GET['id'];
                if($_GET['value']==1) {
                    $model_address->delAddress($where_array);//删除将要成为站长用户的所有地址

                    $model_address->addAddress($insert_data);
                }


                $result = $model_member->editMember($where_array,$update_array);
                if($result) {
                    echo 'true';exit;
                }
                else {
                    echo 'false';exit;
                }
                break;
            /*
             * 判断是否有自提点
             */
            case 'delivery':
                $model_member = Model('member');
                $update_array['member_has_station'] = $_GET['value'];
                $where_array['member_id'] = $_GET['id'];

                //处理自提点
                //1.根据用户id获取用户信息
                //2.根据用户信息的用户名，匹配出来点的编号（x222)
                //3.根据点的编号，获取地址信息
                //4.更新到自提点表

                $member_info = $model_member->getMemberInfoByID($_GET['id']);
                if(empty($member_info['member_truename']) && $_GET['value']==1){
                    echo '用户真实姓名为空，不能操作。';exit;
                }
                if(empty($member_info['member_mobile']) && $_GET['value']==1){
                    echo '用户手机为空，不能操作。';exit;
                }

                //判断用户名是否符合命名规范
				$express = '/^(?P<code>[@a-z]\d+)(?P<city>('.C('city_name').'))(?P<village>.+)/i';
                $m = preg_match($express,$member_info["member_name"],$matches);
                if($m<1){
                    echo '该用户不允许添加为自提点。';exit;
                }
                $code = $matches['code'];//X101
                $address = $matches['village'];//大塘村(X101诸暨大塘村)
				$city_name = $matches['city'];//诸暨 或者 临安

				$model_area = Model('area');

				$area_info = $model_area->getAddress($code,$city_name);
				if(!$area_info['stat']){
					echo $area_info['msg'];exit;
				}


                $model_delivery = Model('delivery_point');
                $delivery_condition = array();
                $delivery_condition['dlyp_name'] = $member_info["member_name"];
                $delivery_info = $model_delivery->getDeliveryPointInfo($delivery_condition);


                $data = array();
                //如果自提点存在
                if($delivery_info){
                    //更新自提点信息
                    $data['dlyp_passwd'] = $member_info["member_passwd"];
                    $data['dlyp_truename'] = $member_info["member_truename"];
                    $data['dlyp_mobile'] = $member_info["member_mobile"];
                    $data['dlyp_area_info'] = $area_info['msg'];
                    if($_GET['value']==0){
                        $data['dlyp_state'] = 0;
                    }
                    else{
                        $data['dlyp_state'] = 1;
                    }
                    $update = $model_delivery->editDeliveryPoint($data,$delivery_condition);
                }
                else{
                    //插入自提点信息

                    $data['dlyp_name'] = $member_info["member_name"];
                    $data['dlyp_passwd'] = $member_info["member_passwd"];
                    $data['dlyp_truename'] = $member_info["member_truename"];
                    $data['dlyp_mobile'] = $member_info["member_mobile"];
                    $data['dlyp_telephony'] = '';
                    $data['dlyp_address_name'] = $code.$address.'自提点';
                    $data['dlyp_area_2'] = $area_info['area_parent_id'];
                    $data['dlyp_area_3'] = $area_info['area_id'];
                    $data['dlyp_area_info'] = $area_info['msg'];
                    $data['dlyp_address'] = '';
                    $data['dlyp_idcard'] = '批量用户不需要身份证';
                    $data['dlyp_idcard_image'] = 'default.png';
                    $data['dlyp_addtime'] = time();
                    $data['dlyp_state'] = 1;
                    $data['dlyp_fail_reason'] = '';
                    $insert = $model_delivery->addDeliveryPoint($data);
                    //end插入自提点信息
                }
                $result = $model_member->editMember($where_array,$update_array);
                if($result) {
                    echo 'true';exit;
                }
                else {
                    echo '添加自提点失败！';exit;
                }
                break;
		}
	}

	/**
	 * 导出
	 *
	 */
	public function export_step1Op(){
		$model_member = Model('member');
		//会员级别
		$member_grade = $model_member->getMemberGradeArr();
		$condition = array();
		if ($_GET['search_field_value'] != '') {
			switch ($_GET['search_field_name']){
				case 'member_name':
					$condition['member_name'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
					break;
				case 'member_email':
					$condition['member_email'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
					break;
				//好商 城v3- b11
				case 'member_mobile':
					$condition['member_mobile'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
					break;
				case 'member_truename':
					$condition['member_truename'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
					break;
			}
		}
		switch ($_GET['search_state']){
			case 'no_informallow':
				$condition['inform_allow'] = '2';
				break;
			case 'no_isbuy':
				$condition['is_buy'] = '0';
				break;
			case 'no_isallowtalk':
				$condition['is_allowtalk'] = '0';
				break;
			case 'no_memberstate':
				$condition['member_state'] = '0';
				break;
			case 'is_station':
				$condition['member_is_station'] = '1';
				break;
			case 'has_station':
				$condition['member_has_station'] = '1';
				break;
		}
		//会员等级
		$search_grade = intval($_GET['search_grade']);
		if ($_GET['search_grade']!='' && $search_grade >= 0 && $member_grade){
			$condition['member_exppoints'] = array(array('egt',$member_grade[$search_grade]['exppoints']),array('lt',$member_grade[$search_grade+1]['exppoints']),'and');
		}
		//排序
		$order = trim($_GET['search_sort']);
		if (empty($order)) {
			$order = 'member_id asc';
		}


		if (!is_numeric($_GET['curpage'])){
			$count = $model_member->getMemberCount($condition);
			$array = array();
			if ($count > self::EXPORT_SIZE ){	//显示下载链接
				$page = ceil($count/self::EXPORT_SIZE);
				for ($i=1;$i<=$page;$i++){
					$limit1 = ($i-1)*self::EXPORT_SIZE + 1;
					$limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
					$array[$i] = $limit1.' ~ '.$limit2 ;
				}
				Tpl::output('list',$array);
				Tpl::output('murl','index.php?act=order&op=index');
				Tpl::showpage('export.excel');
			}else{	//如果数量小，直接下载
				$member_list = $model_member->getMemberList($condition,'*','',$order,self::EXPORT_SIZE);
				//整理会员信息
				if (is_array($member_list)){
					foreach ($member_list as $k=> $v){
						$member_list[$k]['ext'] = $model_member->getOneMemberStat($v['member_id']);
					}
				}
				$this->createExcel($member_list);
			}
		}else{	//下载
			$limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
			$limit2 = self::EXPORT_SIZE;
			$member_list = $model_member->getMemberList($condition,'*','',$order,"{$limit1},{$limit2}");
			//整理会员信息
			if (is_array($member_list)){
				foreach ($member_list as $k=> $v){
					$member_list[$k]['ext'] = $model_member->getOneMemberStat($v['member_id']);

				}
			}

			$this->createExcel($member_list);
		}
	}

	/**
	 * 生成excel
	 *
	 * @param array $data
	 */
	private function createExcel($data = array()){
		Language::read('export');
		import('libraries.excel');
		$excel_obj = new Excel();
		$excel_data = array();
		//设置样式
		$excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
		//header
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'会员编号');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'会员名称');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'真实姓名');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'邮箱');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'手机号码');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'QQ');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'登陆次数');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'注册时间');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'最近登录时间');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'会员积分');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'可用预存款余额');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'冻结预存款金额');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'可用充值卡余额');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'冻结充值卡金额');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'是否是站长');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'是否有自提点');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'充值优惠率');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'station_id');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'下单量');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'下单金额');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'下单商品数');
		//data
		foreach ((array)$data as $k=>$v){
			$tmp = array();
			$tmp[] = array('data'=>$v['member_id']);
			$tmp[] = array('data'=>$v['member_name']);
			$tmp[] = array('data'=>$v['member_truename']);
			$tmp[] = array('data'=>$v['member_email']);
			$tmp[] = array('data'=>$v['member_mobile']);
			$tmp[] = array('data'=>$v['member_qq']);
			$tmp[] = array('data'=>$v['member_login_num']);
			$tmp[] = array('data'=>date('Y-m-d H:i:s',$v['member_time']));
			$tmp[] = array('data'=>date('Y-m-d H:i:s',$v['member_login_time']));
			$tmp[] = array('format'=>'Number','data'=>$v['member_point']);
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['available_predeposit']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['freeze_predeposit']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['available_rc_balance']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['freeze_rc_balance']));
			$tmp[] = array('data'=>$v['member_is_station']==0?'否':'是');
			$tmp[] = array('data'=>$v['member_has_station']==0?'无':'有');
			$tmp[] = array('data'=>$v['member_rate']);
			$tmp[] = array('data'=>$v['inviter_id']);
			$tmp[] = array('data'=>$v['ext'][0]['order_num']);
			$tmp[] = array('data'=>$v['ext'][0]['order_amount']);
			$tmp[] = array('data'=>$v['ext'][0]['order_goodsnum']);
			$excel_data[] = $tmp;
		}
		$excel_data = $excel_obj->charset($excel_data,CHARSET);
		$excel_obj->addArray($excel_data);
		$excel_obj->addWorksheet($excel_obj->charset('会员信息表',CHARSET));
		$excel_obj->generateXML($excel_obj->charset('会员信息表',CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
	}
}
