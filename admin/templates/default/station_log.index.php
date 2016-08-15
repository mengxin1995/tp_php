<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['station_log_index_manage'];?></h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['sl_manage'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="post" id="store_form">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th><?php echo $lang['sl_member_id'];?></th>
          <th class="align-center"><?php echo $lang['sl_local_ip'];?></th>
          <th class="align-center"><?php echo $lang['sl_pub_ip'];?></th>
          <th class="align-center"><?php echo $lang['sl_report_time'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['sl_info']) && is_array($output['sl_info'])){ ?>
        <?php foreach($output['sl_info'] as $k => $v){ ?>
        <tr class="hover">
          <td><span title="<?php echo $v['member_id']; ?>"><?php echo $v['member_id']; ?></span></td>
          <td class="align-center nowrap"><?php echo $v['local_ip']; ?></td>
          <td class="align-center nowrap"><?php echo $v['pub_ip']; ?></td>
          <td class="align-center nowrap"><?php echo date('Y-m-d H:i:s',$v['report_time']); ?></td>
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
          <td id="batchAction" colspan="15">
            <div class="pagination"> <?php echo $output['page'];?> </div></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>