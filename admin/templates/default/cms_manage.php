<?php defined('InShopNC') or exit('Access Invalid!');?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#submit").click(function(){
            $("#add_form").submit();
        });
    });
</script>
<div class="page">
    <!--页面开头-->
    <div class="fixed-bar">
        <div class="item-title">
            <h3>设置</h3>
            <ul class="tab-base">
                <?php   foreach($output['menu'] as $menu) {  if($menu['menu_type'] == 'text') { ?>
                    <li><a href="<?php echo $menu['menu_url'];?>" class="current"><span><?php echo $menu['menu_name'];?></span></a></li>
                <?php }  else { ?>
                    <li><a href="<?php echo $menu['menu_url'];?>" ><span><?php echo $menu['menu_name'];?></span></a></li>
                <?php  } }  ?>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form style="margin-top: 10px;" action="index.php?act=cms_manage&op=cms_manage_save" id="add_form" method="post" enctype="multipart/form-data">
        <table class="table tb-type2">
            <tr>
                <td class="required">当前采集周期：</td>
                <td colspan="2"><?php if($output['state']['time']) {echo $output['state']['time'];} else {echo '0';}?><span style="color:red; font-size: 15px; margin-left: 2px;">分</span></td>
            </tr>
            <tr class="noborder">
                <td class="required">采集周期：</td>
                <td><input style="border: 2px solid;" type="text" name="zhouqi" maxlength="10" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"/><span style="color:red; font-size: 15px; margin-left: 2px;">分</span></td>
                <td class="vatop tips">(最大输入10个数字)</td>
            </tr>
            <tr class="noborder">
                <td colspan="2" class="required" style="font-size: large;color: rgb(0, 0, 255)">采集项目设置：</td>
            </tr>
            <tr>
                <td  colspan="2" class="required">CPU信息采集：</td>
                <td class="vatop rowform onoff">
                    <label for="cms_isuse_1" class="cb-enable <?php if($output['state']['cpu_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_open'];?>"><span><?php echo $lang['nc_open'];?></span></label>
                    <label for="cms_isuse_0" class="cb-disable <?php if($output['state']['cpu_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_close'];?>"><span><?php echo $lang['nc_close'];?></span></label>
                    <input type="radio" id="cms_isuse_1" name="cpu_isuse" value="1" <?php echo $output['state']['cpu_isuse']==1?'checked=checked':''; ?>>
                    <input type="radio" id="cms_isuse_0" name="cpu_isuse" value="0" <?php echo $output['state']['cpu_isuse']==0?'checked=checked':''; ?>>
                </td>
                <!--开关选择的注释 以下同此-->
                <td class="vatop tips"><?php echo $lang['cms_isuse_explain'];?></td>
            </tr>
            <tr>
                <td  colspan="2" class="required">内存信息采集：</td>
                <td class="vatop rowform onoff">
                    <label for="cms_isuse_11" class="cb-enable <?php if($output['state']['mem_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_open'];?>"><span><?php echo $lang['nc_open'];?></span></label>
                    <label for="cms_isuse_00" class="cb-disable <?php if($output['state']['mem_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_close'];?>"><span><?php echo $lang['nc_close'];?></span></label>
                    <input type="radio" id="cms_isuse_11" name="mem_isuse" value="1" <?php echo $output['state']['mem_isuse']==1?'checked=checked':''; ?>>
                    <input type="radio" id="cms_isuse_00" name="mem_isuse" value="0" <?php echo $output['state']['mem_isuse']==0?'checked=checked':''; ?>>
                </td>
                <td class="vatop tips"><?php echo $lang['cms_isuse_explain'];?></td>
            </tr>
            <tr>
                <td  colspan="2" class="required">网络I/O吞吐率：</td>
                <td class="vatop rowform onoff">
                    <label for="cms_isuse_111" class="cb-enable <?php if($output['state']['wangluo'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_open'];?>"><span><?php echo $lang['nc_open'];?></span></label>
                    <label for="cms_isuse_000" class="cb-disable <?php if($output['state']['wangluo'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_close'];?>"><span><?php echo $lang['nc_close'];?></span></label>
                    <input type="radio" id="cms_isuse_111" name="wangluo" value="1" <?php echo $output['state']['wangluo']==1?'checked=checked':''; ?>>
                    <input type="radio" id="cms_isuse_000" name="wangluo" value="0" <?php echo $output['state']['wangluo']==0?'checked=checked':''; ?>>
                </td>
                <td class="vatop tips"><?php echo $lang['cms_isuse_explain'];?></td>
            </tr>
            <tr>
                <td  colspan="2" class="required">服务信息采集：</td>
                <td class="vatop rowform onoff">
                    <label for="cms_isuse_1111" class="cb-enable <?php if($output['state']['fuwu'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_open'];?>"><span><?php echo $lang['nc_open'];?></span></label>
                    <label for="cms_isuse_0000" class="cb-disable <?php if($output['state']['fuwu'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_close'];?>"><span><?php echo $lang['nc_close'];?></span></label>
                    <input type="radio" id="cms_isuse_1111" name="fuwu" value="1" <?php echo $output['state']['fuwu']==1?'checked=checked':''; ?>>
                    <input type="radio" id="cms_isuse_0000" name="fuwu" value="0" <?php echo $output['state']['fuwu']==0?'checked=checked':''; ?>>
                </td>
                <td class="vatop tips"><?php echo $lang['cms_isuse_explain'];?></td>
            </tr>
            <tfoot>
            <tr>
                <td colspan="2"><a id="submit" href="javascript:void(0)" class="btn"><span><?php echo $lang['nc_submit'];?></span></a></td>
            </tr>
            </tfoot>
        </table>
    </form>

</div>
