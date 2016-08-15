<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['ss_index_manage'];?></h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['ss_manage'];?></span></a></li>
        <li><a href="index.php?act=screensaver&op=ss_add"><span><?php echo $lang['ss_add'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="post" id="store_form">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th></th>
          <th><?php echo $lang['ss_name'];?></th>
          <th class="align-center"><?php echo $lang['ss_start_time'];?></th>
          <th class="align-center"><?php echo $lang['ss_end_time'];?></th>
          <th class="align-center"><?php echo $lang['nc_handle'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['ss_info']) && is_array($output['ss_info'])){ ?>
        <?php foreach($output['ss_info'] as $k => $v){ ?>
        <tr class="hover">
          <td class="w24"><input type="checkbox" class="checkitem" name="del_id[]" value="<?php echo $v['id']; ?>" /></td>
          <td><span title="<?php echo $v['title']; ?>"><?php echo str_cut($v['title'], '36'); ?></span></td>

          <td class="align-center nowrap"><?php echo date('Y-m-d',$v['start_time']); ?></td>
          <td class="align-center nowrap"><?php echo date('Y-m-d',$v['end_time']); ?></td>
          <td class="w72 align-center"><a href="index.php?act=screensaver&op=ss_edit&id=<?php echo $v['id'];?>"><?php echo $lang['ss_edit'];?></a>&nbsp;|&nbsp;<a href="javascript:if(confirm('<?php echo $lang['nc_ensure_del'];?>'))window.location = 'index.php?act=screensaver&op=ss_del&id=<?php echo $v['id'];?>';"><?php echo $lang['nc_del'];?></a></td>
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
            </span>&nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="if(confirm('<?php echo $lang['ss_del_sure'];?>')){$('#store_form').submit();}"><span><?php echo $lang['nc_del'];?></span></a>
            <div class="pagination"> <?php echo $output['page'];?> </div></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>