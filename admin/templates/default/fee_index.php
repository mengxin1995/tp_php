<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['fee_index_manage'];?></h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['fee_manage'];?></span></a></li>
        <li><a href="index.php?act=fee&op=fee_add" ><span><?php echo $lang['fee_add'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <hr/>
  <form method="post" action="index.php?act=fee&op=stat" name="formStat">
    <input type="hidden" name="act" value="fee" />
    <input type="hidden" name="op" value="stat" />
    <table class="tb-type1 noborder search">
      <tbody>
      <tr class="noborder">
        <td colspan="2" class="required"><label><?php echo $lang['fee_is_use'];?>:</label></td>
      </tr>
      <tr class="noborder">
        <td class="vatop rowform onoff"><label for="fee_isuse_1" class="cb-enable <?php if($output['stat'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['fee_isuse_open'];?>"><span><?php echo $lang['fee_isuse_open'];?></span></label>
          <label for="fee_isuse_0" class="cb-disable <?php if($output['stat']  == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['fee_isuse_close'];?>"><span><?php echo $lang['fee_isuse_close'];?></span></label>
          <input type="radio" id="fee_isuse_1" name="fee_isuse" value="1" <?php echo $output['stat']==1?'checked=checked':''; ?>>
          <input type="radio" id="fee_isuse_0" name="fee_isuse" value="0" <?php echo $output['stat']==0?'checked=checked':''; ?>></td>
        <td class="vatop tips"><?php echo $lang['feeSettings_notice'];?></td>
      </tr>
      <tr class="noborder">
        <td colspan="2" class="required"><label><?php echo $lang['fee_min'];?>:</label></td>
      </tr>
      <tr class="noborder">
        <td class="vatop rowform onoff">
          <input type="text" id="fee_min" name="fee_min" value="<?php echo $output['fee_min']; ?>"></td>
        <td class="vatop tips"><?php echo $lang['fee_min_notice'];?></td>
      </tr>
      <tr class="tfoot">
        <td colspan="2" ><a href="JavaScript:void(0);" class="btn" onclick="document.formStat.submit()"><span><?php echo $lang['nc_submit'];?></span></a></td>
      </tr>
      </tbody>
    </table>
  </form>
  <hr/>
  <form method="get" action="index.php?act=fee&op=index" name="formSearch">
    <input type="hidden" name="act" value="fee" />
    <input type="hidden" name="op" value="index" />
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label for="search_name"><?php echo $lang['fee_name']; ?></label></th>
          <td><input class="txt" type="text" name="search_name" id="search_name" value="<?php echo $_GET['search_name'];?>" /></td>
          <td><a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
              <?php if($_GET['search_name']!= ''){?>
                  <a href="JavaScript:void(0);" onclick=window.location.href='index.php?act=fee&op=index' class="btns " title="<?php echo $lang['fee_all']; ?>"><span><?php echo $lang['fee_all']; ?></span></a>
              <?php }?>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title">
            <h5><?php echo $lang['nc_prompts'];?></h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul>
            <li><?php echo $lang['fee_help'];?></li>
          </ul></td>
      </tr>
    </tbody>
  </table>


  <form method="post" id="store_form">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th><input type="checkbox" class="checkall"/></th>
          <th class="align-center"><?php echo $lang['fee_name'];?></th>
          <th class="align-center"><?php echo $lang['fee_goods_id'];?></th>
          <th class="align-center"><?php echo $lang['fee_is_use'];?></th>
          <th class="align-center"><?php echo $lang['nc_handle'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['fee_list']) && is_array($output['fee_list'])){ ?>
        <?php foreach($output['fee_list'] as $k => $v){ ?>
        <tr class="hover">
          <td class="w24"><input type="checkbox" class="checkitem" name="del_id[]" value="<?php echo $v['id']; ?>" /></td>
          <td class="align-center"><span title="<?php echo $v['title'];?>"><?php echo str_cut($v['title'], '40');?></span></td>
          <td class="align-center"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $v['goods_id']));?>" target="_blank"><?php echo $v['goods_id'];?></a></td>

          <td class="align-center yes-onoff"><?php if($v['stat'] == '0'){ ?>
            <a href="JavaScript:void(0);" class=" disabled" ajax_branch="is_use" nc_type="inline_edit" fieldname="stat" fieldid="<?php echo $v['id']?>" fieldvalue="0" title="<?php echo $lang['nc_editable'];?>"><img src="<?php echo ADMIN_TEMPLATES_URL;?>/images/transparent.gif"></a>
            <?php }else { ?>
            <a href="JavaScript:void(0);" class=" enabled" ajax_branch="is_use" nc_type="inline_edit" fieldname="stat" fieldid="<?php echo $v['id']?>" fieldvalue="1" title="<?php echo $lang['nc_editable'];?>"><img src="<?php echo ADMIN_TEMPLATES_URL;?>/images/transparent.gif"></a>
            <?php } ?></td>
          <td class="align-center">
          <a href='index.php?act=fee&op=fee_edit&fee_id=<?php echo $v['id'];?>'><?php echo $lang['nc_edit'];?></a> |<a href="javascript:if(confirm('<?php echo $lang['nc_ensure_del'];?>'))window.location = 'index.php?act=fee&op=fee_del&fee_id=<?php echo $v['id'];?>';"><?php echo $lang['nc_del'];?></a></td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="15"><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td><input type="checkbox" class="checkall" id="checkall"/></td>
          <td id="batchAction" colspan="15"><span class="all_checkbox">
            <label for="checkall"><?php echo $lang['nc_select_all'];?></label>
            </span>&nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="if(confirm('<?php echo $lang['fee_del_sure'];?>')){$('#store_form').submit();}"><span><?php echo $lang['nc_del'];?></span></a>
            <div class="pagination"> <?php echo $output['page'];?> </div></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
