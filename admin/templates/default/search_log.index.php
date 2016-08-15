<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['nc_search_log'];?></h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_search_log'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" name="act" value="search_log">
    <input type="hidden" name="op" value="list">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><?php echo $lang['search_log_do'];?></th>
          <td><input class="txt" name="keyword" value="<?php echo $_GET['keyword'];?>" type="text"></td>
          <th><?php echo $lang['search_log_dotime'];?></th>
          <td><input class="txt date" type="text" value="<?php echo $_GET['time_from'];?>" id="time_from" name="time_from">
            <label for="time_to">~</label>
            <input class="txt date" type="text" value="<?php echo $_GET['time_to'];?>" id="time_to" name="time_to"/></td>
          <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
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
            <li><?php echo $lang['search_log_tips1'];?></li>
          </ul></td>
      </tr>
    </tbody>
  </table>   
  <form method="post" id='form_list' action="index.php?act=search_log&op=list_del">
    <input type="hidden" name="form_submit" value="ok" />
    <div style="text-align:right;"><a class="btns" href="javascript:void(0);" id="ncexport"><span><?php echo $lang['nc_export'];?>Excel</span></a></div>
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th></th>
          <th><?php echo $lang['search_log_do'];?></th>          
          <th class="align-center"><?php echo $lang['search_log_dotime'];?></th>
          <th class="align-center">IP</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <?php foreach($output['list'] as $k => $v){ ?>
        <tr class="hover">
          <td class="w24">
            <input name="del_id[]" type="checkbox" class="checkitem" value="<?php echo $v['id']; ?>">
          </td>
          <td><a href="<?php echo urlShop('search', 'index', array('keyword' => $v['keyword']));?>" target="_blank"><?php echo $v['keyword'];?></a></td>
          <td class="align-center"><?php echo $v['search_time']; ?></td>
          <td class="align-center"><?php echo $v['ip']; ?></td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="10"><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <tr class="tfoot">
          <td></td>
          <td colspan="16">
            <?php echo $lang['search_log_total'];?><?php echo $output['scount'];?><?php echo $lang['search_log_record'];?>
            <div class="pagination"> <?php echo $output['page'];?> </div></td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
$(function(){
    $('#time_from').datepicker({dateFormat: 'yy-mm-dd'});
    $('#time_to').datepicker({dateFormat: 'yy-mm-dd'});
    $('#ncexport').click(function(){
    	$('input[name="op"]').val('export_step1');
    	$('#formSearch').submit();
    });
    $('#ncsubmit').click(function(){
    	$('input[name="op"]').val('list');
    	$('#formSearch').submit();
    });
});
</script>
