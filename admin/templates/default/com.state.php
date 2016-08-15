<?php defined('InShopNC') or exit('Access Invalid!');?>

<!--v3-v12-->
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?php echo '计算机';?></h3>
            <ul class="tab-base">
                <li><a href="JavaScript:void(0);" class="current"><span> 异常状态记录</span></a></li>
            </ul>
        </div>
    </div>

    <div class="fixed-empty"></div>

    <form method="get" name="formSearch" id="formSearch" action="index.php?act=store&op=com_state">
        <input type="hidden" value="store" name="act">
        <input type="hidden" value="com_state" name="op">
        <table class="tb-type1 noborder search">
            <tbody>
            <tr>
                <th><label for="owner_and_name">IP</label></th>
                <td><input type="text" value="<?php echo $output['com_ip'];?>" name="com_ip" id="com_ip" class="txt"></td><td></td>
                <th><label for="owner_and_name">计算机名</label></th>
                <td><input type="text" value="<?php echo $output['com_name'];?>" name="com_name" id="com_name" class="txt"></td><td></td>
                <td>
                    <input type="submit" value="查找" style="margin-left:400px;margin-top:10px;height:25px;width:70px"/>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
    <div style="text-align:right;"><a class="btns" href="javascript:void(0);" id="ncexport"><span><?php echo $lang['nc_export'];?>Excel</span></a></div>
    <table class="table tb-type2">
        <thead>
        <tr class="thead">
            <th>IP</th>
            <th>计算机名</th>
            <th>状态变化</th>
            <th>时间</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                    <tr>
                        <td><?php echo $v['com_ip'];?></td>
                        <td><?php echo $v['com_name'];?></td>
                        <td><?php echo $v['jinggao'];?></td>
                        <td><?php echo date("Y-m-d h:i:s",$v['CreateTime']);?></td>
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

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
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
        $('#ncdelete').click(function(){
            $('#delago').val($('#delago1').val());
            $('input[name="op"]').val('list_del');$('#formSearch').submit();
        });
    });
</script>