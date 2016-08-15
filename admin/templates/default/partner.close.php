<?php defined('InShopNC') or exit('Access Invalid!');?>
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="<?php echo ADMIN_TEMPLATES_URL;?>/css/font/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['admin_partner_parameter_apply'];?></h3>
      <ul class="tab-base">
        <li><a href="<?php echo urlAdmin('partner', 'partner');?>" ><span><?php echo $lang['admin_partner_parameter_allapply'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['admin_partner_parameter_editapply'];?></span></a></li>
        <li><a href="<?php echo urlAdmin('partner', 'partner', array('type' => 'waitverify'));?>"><span><?php echo $lang['admin_partner_parameter_waitapply'];?></span></a></li>
      </ul>
    </div>
  </div>
    <div class="fixed-empty"></div>
    <form method="get" action="index.php" name="formSearch" id="formSearch">
        <input type="hidden" name="act" value="partner">
        <input type="hidden" name="op" value="partner">
        <input type="hidden" name="type" value="lockup" />
        <table class="tb-type1 noborder search">
            <tbody>
            <tr>
                <th><?php echo $lang['admin_partner_name'];?> </th>
                <td><input type="text" name="partner_name" class="txt" value='<?php echo $_GET['partner_name'];?>'></td>
                <th><?php echo $lang['admin_partner_address'];?> </th>
                <td><input type="text" name="partner_address" class="txt" value='<?php echo $_GET['partner_address'];?>'></td>
                <th><?php echo $lang['admin_predeposit_addtime'];?> </th>
                <td><input type="text" id="query_start_date" name="query_start_date" class="txt date" value="<?php echo $_GET['query_start_date'];?>" >
                    <label>~</label>
                    <input type="text" id="query_end_date" name="query_end_date" class="txt date" value="<?php echo $_GET['query_end_date'];?>" ></td>
                <td>
                    <select id="paystate_search" name="paystate_search">
                        <option value=""><?php echo $lang['admin_paystate_search'] ?></option>
                        <option value="0" <?php if($_GET['paystate_search'] == '0' ) { ?>selected="selected"<?php } ?>>待处理</option>
                        <option value="1" <?php if($_GET['paystate_search'] == '1' ) { ?>selected="selected"<?php } ?>>已批准</option>
                        <option value="2" <?php if($_GET['paystate_search'] == '2' ) { ?>selected="selected"<?php } ?>>已拒绝</option>
                    </select>
                    <a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a></td>
            </tr>
            </tbody>
        </table>
    </form>
    <table class="table tb-type2" id="prompt">
        <tbody>
        <tr class="space odd">
            <th colspan="12"><h5>列表</h5></th>
        </tr>
        </tbody>
    </table>
    <form id="form_partner" method='post' name="">
        <input type="hidden" name="form_submit" value="ok" />
        <table class="table tb-type2">
            <thead>
            <tr class="thead">
                <th class="w24">&nbsp;</th>
                <th><?php echo $lang['admin_partner_name'];?></th>
                <th class="align-center"><?php echo $lang['admin_partner_job'] ?></th>
                <th class="align-center"><?php echo $lang['admin_partner_address'] ?></th>
                <th class="align-center"><?php echo $lang['admin_partner_phone'] ?></th>
                <th class="align-center"><?php echo $lang['admin_partner_addtime']; ?></th>
                <th class="align-center"><?php echo $lang['admin_paystate_search'] ?></th>
                <th class="align-center"><?php echo "操作" ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                <?php foreach($output['list'] as $k => $v){?>
                    <tr class="hover">
                        <td ><input type="checkbox" name="partner_id[]" value="<?php echo $v['partner_id'];?>" class="checkitem">
                        <td><?php echo $v['partner_name']; ?></td>
                        <td><?php echo $v['partner_job']; ?></td>
                        <td><?php echo $v['partner_address']; ?></td>
                        <td><?php echo $v['partner_phone']; ?></td>
                        <td class="nowarp align-center"><?php echo @date('Y-m-d H:i:s',$v['pdr_add_time']);?></td>
                        <td class="align-center"><?php echo str_replace(array('0','1','2'),array('待处理','已批准','已拒绝'),$v['paystate_search']);?></td>
                        <td class="w72 align-center">
                                <a href="JavaScript:void(0);" onclick="if(confirm('<?php echo $lang['nc_ensure_del'];?>')){window.location ='index.php?act=partner&op=recharge_del&partner_id=<?php echo $v['partner_id']; ?>'}"><span><?php echo $lang['nc_del'];?></span></a>&nbsp;|
                            <?php if(in_array(intval($v['paystate_search']), array(STORE_PAYSTATE_SEARCH_NEW))) { ?>
                                <a href="index.php?act=partner&op=partner_detail&partner_id=<?php echo $v['partner_id'];?>">审核</a>
                            <?php } else { ?>
                                <a href="index.php?act=partner&op=partner_detail&partner_id=<?php echo $v['partner_id'];?>">查看</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php }else { ?>
                <tr class="no_data">
                    <td colspan="10"><?php echo $lang['nc_no_record'];?></td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot class="tfoot">
            <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                <tr>
                    <td class="w24"><input type="checkbox" class="checkall" id="checkallBottom"></td>
                    <td colspan="16">
                        <label for="checkallBottom"><?php echo $lang['nc_select_all']; ?></label>
                        &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="if(confirm('<?php echo $lang['nc_ensure_del']?>')){$('#form_partner').submit();}"><span><?php echo $lang['nc_del'];?></span></a>
                        <div class="pagination"> <?php echo $output['page'];?> </div></td>
                </tr>
            <?php } ?>
            </tfoot>
        </table>
    </form>
</div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script language="javascript">
    $(function(){
        $('#query_start_date').datepicker({dateFormat: 'yy-mm-dd'});
        $('#query_end_date').datepicker({dateFormat: 'yy-mm-dd'});
        $('#ncsubmit').click(function(){
            $('input[name="op"]').val('partner');$('#formSearch').submit();
        });
    });
</script>