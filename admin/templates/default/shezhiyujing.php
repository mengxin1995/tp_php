<?php defined('InShopNC') or exit('Access Invalid!');?>
<style type="text/css">
    .d_inline {
        display:inline;
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){

        $("#submit").click(function(){
            alert('提交成功！');
            $("#store_form").submit();
        });
    });
    $(function(){
            var t = document.getElementById("temp");
            var id1 = document.getElementById("i");
            if(t.value == 1){
                alert("您输入的数据不符合规范！！！");
                location.href='index.php?act=store&op=bianjiyujing&id='+id1.value;
            }
        }
    )
</script>
<div>
    <input value=<?php if(isset($output['tt']) && $output['tt'] == 1) echo 1; else echo 0;?> id="temp" style="display: none"/>
    <input value=<?php echo $output["id"];?> id="i" style="display: none"/>
</div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?php echo '设置';?></h3>
            <ul class="tab-base">
                <li><a href="index.php?act=store&op=store_yujing"><span>新建规则</span></a></li>
                <li><a href="JavaScript:void(0);" class="current"><span>编辑</span></a></li>
            </ul>
        </div>
    </div>
    <div class="homepage-focus" nctype="editStoreContent">
        <form id="store_form" method="post" action="index.php?act=store&op=store_edityujing&id=<?php echo $output['id'];?>">
            <table class="table tb-type2">
                <tbody>
                <tr class="noborder">
                    <td colspan="2" class="required"><h4><label style="color: red;">异常报警条件设置：</label></h4></td>
                </tr>
                <tr class="noborder" >
                    <td class="required"><label>属性：</label></td>
                    <td>
                        <select name="choose">
                            <option selected value="0">请选择</option>
                            <option value="1">内存占用量</option>
                            <option value="2">CPU占用量</option>
                            <option value="3">CPU温度大小</option>
                        </select>
                    </td>
                </tr>
                <tr class="noborder">
                    <td class="required"><label>条件：</label></td>
                    <td>
                        <select name="tiaojian">
                            <option selected value="0">请选择</option>
                            <option value="1">大于</option>
                            <option value="2">小于</option>
                        </select>
                    </td>
                </tr>
                <tr class="noborder">
                    <td class="required"><label>预警临界值：</label></td>
                    <td><input type="text" name="yujing" maxlength="3" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"/>单位：(% / 度)</td>
                </tr>
                <tr class="noborder">
                    <td class="required"><label>报警临界值：</label></td>
                    <td><input type="text" name="baojing" maxlength="3" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"/>单位：(% / 度)</td>
                </tr>
                <tr>
                    <td class="required">邮件：</td>
                    <td class="vatop rowform onoff">
                        <label for="cms_isuse_1" class="cb-enable selected" title="<?php echo $lang['nc_open'];?>"><span><?php echo $lang['nc_open'];?></span></label>
                        <label for="cms_isuse_0" class="cb-disable " title="<?php echo $lang['nc_close'];?>"><span><?php echo $lang['nc_close'];?></span></label>
                        <input type="radio" id="cms_isuse_1" name="is_mail" value="1" checked=checked >
                        <input type="radio" id="cms_isuse_0" name="is_mail" value="0" >
                    </td>
                </tr>
                <tr>
                    <td class="required">短信：</td>
                    <td class="vatop rowform onoff">
                        <label for="cms_isuse_11" class="cb-enable selected" title="<?php echo $lang['nc_open'];?>"><span><?php echo $lang['nc_open'];?></span></label>
                        <label for="cms_isuse_00" class="cb-disable" title="<?php echo $lang['nc_close'];?>"><span><?php echo $lang['nc_close'];?></span></label>
                        <input type="radio" id="cms_isuse_11" name="is_duanxin" value="1" checked=checked>
                        <input type="radio" id="cms_isuse_00" name="is_duanxin" value="0" >
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <td colspan="2">
                    <a id="submit" href="javascript:void(0)" class="btn"><span><?php echo $lang['nc_submit'];?></span></a>
                </td>
                </tfoot>
            </table>
        </form>
    </div>
</div>
