<?php defined('InShopNC') or exit('Access Invalid!');?>
<!--v3-v12-->
<div class="page">
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch" action="index.php?act=store&op=store_msg">
  <input type="hidden" value="store" name="act">
  <input type="hidden" value="store_msg" name="op">
  <table class="tb-type1 noborder search">
  <tbody>
    <tr>
      <th><label for="owner_and_name">发送人</label></th>
      <td><input type="text" value="<?php echo $output['sender'];?>" name="sender" id="sender" class="txt"></td><td></td>
        <th><label for="owner_and_name">店铺</label></th>
        <td><input type="text" value="<?php echo $output['store_name'];?>" name="store_name" id="store_name" class="txt"></td><td></td>
        <th><label for="search_stime">发送时间</label></th>
        <td><input type="text" class="txt date" value="<?php echo $_GET['search_stime'];?>" name="search_stime" id="search_stime" class="txt">
            <label for="search_etime">~</label>
            <input type="text" class="txt date" value="<?php echo $_GET['search_etime'];?>" name="search_etime" id="search_etime" class="txt">
        <th><label for="store_name"><?php echo "已读";?></label></th>
        <td>
            <input type="radio" name="is_read" value="1" <?php if($output['is_read'] == 1){echo 'checked="checked"';}?> />是
            <input type="radio" name="is_read" value="2" <?php if($output['is_read'] == 2){echo 'checked="checked"';}?> />否
        </td>
        <td>
            <input type="submit" value="提交" style="margin-left:400px;margin-top:10px;height:25px;width:70px"/>
        </td>
    </tr>
  </tbody>
  </table>
  </form>
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th>发送人</th>
          <th>店铺</th>
          <th>消息</th>
          <th class="align-center">时间</th>
          <th class="align-center">已读</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['store_list']) && is_array($output['store_list'])){ ?>
        <?php foreach($output['store_list'] as $k => $v){ ?>
        <tr>
            <td><?php echo $v['sender'];?></td>
            <td><?php echo $v['store_name'];?></td>
            <td><?php echo $v['store_msg'];?></td>
            <?php $date=date('Y-m-d H:i:s',$v['send_time']);?>
            <td><?php echo $date;?></td>
            <td><?php if($v['is_read'] == 1){echo "是";}
                else echo "否";?></td>
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
          <td></td>
          <td colspan="16">
            <div class="pagination"><?php echo $output['page'];?></div></td>
        </tr>
      </tfoot>
    </table>

</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
    $(function(){
        $('#search_stime').datepicker({dateFormat: 'yy-mm-dd'});
        $('#search_etime').datepicker({dateFormat: 'yy-mm-dd'});
    });
    function submit_form(type){
        if(type=='del'){
            if(!confirm('<?php echo $lang['nc_ensure_del'];?>')){
                return false;
            }
            $('#form_trace').attr('action','index.php?act=sns_strace&op=strace_del');
        }else if(type=='hide'){
            $('#form_trace').attr('action','index.php?act=sns_strace&op=strace_edit&type=hide');
        }else{
            $('#form_trace').attr('action','index.php?act=sns_strace&op=strace_edit&type=show');
        }
        $('#form_trace').submit();
    }
</script>