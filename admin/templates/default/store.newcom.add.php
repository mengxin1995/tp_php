<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>计算机</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=store&op=store"><span>监控</span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span>新建节点</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title">
            <h5><?php echo $lang['nc_prompts'];?></h5>
            <span class="arrow"></span></div>
        </th>
      </tr>
      <tr>
        <td><ul>
            <li>平台可以在此处添加计算机</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <form id="store_form" method="post" action="index.php?act=store&op=newcom_add">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="store_id" value="" />
    <table class="table tb-type2">
      <tbody>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="store_name">电脑IP:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="" name="com_ip" class="txt" /></td>
          <td class="vatop tips"></td>
        </tr>
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
      </tbody>
      <tfoot>
      <td>
          <input type="submit" value="提交" style="margin-left:400px;margin-top:10px;height:25px;width:70px"/>
      </td>
      </tfoot>
    </table>
  </form>
</div>

