<?php defined('InShopNC') or exit('Access Invalid!');?>
<style type="text/css">
.d_inline {
      display:inline;
}
</style>

<div class="page">
  <div class="fixed-empty"></div>

  <div class="homepage-focus" nctype="editStoreContent">
    <ul class="tab-menu">
      <li class="current">电脑信息</li>
    </ul>
    <form id="store_form" method="post" action="index.php?act=store&op=store&ip=<?php echo $output['com']['com_ip'];?>">
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label>电脑IP:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['com']['com_ip'];?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
            <td colspan="2" class="required"><label>用户名:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><?php echo $output['com']['username'];?></td>
            <td class="vatop tips"></td>
        </tr>
      </tbody>
        <tr class="noborder">
            <td colspan="2" class="required"><label>设置异常</label>
      <select name="zu">
          <option value=0>请选择</option>
          <option value=1>一分钟</option>
          <option value=2>五分钟</option>
          <option value=3>十分钟</option>
          <option value=4>十五分钟</option>
          <option value=5>二十分钟</option></td>
      </select>
        </tr>
            <td>
                <input type="submit" value="提交" style="margin-left:400px;margin-top:10px;height:25px;width:70px"/>
            </td>

    </table>
    </form>
