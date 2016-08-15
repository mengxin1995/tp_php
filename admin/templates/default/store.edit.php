<?php defined('InShopNC') or exit('Access Invalid!');?>
<style type="text/css">
.d_inline {
      display:inline;
}
</style>

<div class="page">
  <div class="homepage-focus" nctype="editStoreContent">
    <ul class="tab-menu">
      <li class="current">计算机信息</li>
    </ul>
    <form id="store_form" method="post" action="index.php?act=store&op=store&ip=<?php echo $output['com']['com_ip'];?>">
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label>计算机IP:</label></td>
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
            <td colspan="2" class="required"><label>添加到组别:</label>
      <select name="zu">
          <option value=0>请选择</option>
          <option value=1>组别A</option>
          <option value=2>组别B</option>
          <option value=3>组别C</option>
          <option value=4>组别D</option>
          <option value=5>组别E</option></td>
      </select>
     
        </tr>
            <td>
                <input type="submit" value="提交" style="margin-left:400px;margin-top:10px;height:25px;width:70px"/>
            </td>

    </table>
    </form>
