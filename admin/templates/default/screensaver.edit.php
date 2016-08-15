<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['ss_index_manage'];?></h3>
      <ul class="tab-base">
        <li><a href="index.php?act=screensaver&op=index"><span><?php echo $lang['ss_manage'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['ss_change'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="ss_form" enctype="multipart/form-data" method="post" name="advForm">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <?php foreach($output['ss_list'] as $k => $v){ ?>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="ss_name"><?php echo $lang['ss_name'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" name="ss_name" id="ss_name" class="txt" value="<?php echo $v['title']; ?>"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="ss_description"><?php echo $lang['ss_description'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" name="ss_description" id="ss_description" class="txt" value="<?php echo $v['description']; ?>"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="ss_start_date"><?php echo $lang['ss_start_time'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" name="ss_start_date" id="ss_start_date" class="txt date" value="<?php echo date('Y-m-d',$v['start_time']); ?>"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="ss_end_date"><?php echo $lang['ss_end_time'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" name="ss_end_date" id="ss_end_date" class="txt date" value="<?php echo date('Y-m-d',$v['end_time']); ?>"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr id="ss_pic" >
          <input type="hidden" name="img_1_old" value="<?php echo $v['img_1']; ?>">
          <td colspan="2" class="required"><label for="file_ss_pic"><?php echo $lang['ss_img_upload'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png">
            <div class="type-file-preview"><img src="<?php echo UPLOAD_SITE_URL."/".ATTACH_ADV."/".$v['img_1'];?>" onload="javascript:DrawImage(this,500,500);"></div>
            </span><span class="type-file-box">
            <input type="file" class="type-file-file" id="file_ss_pic" name="ss_pic" size="30" />
            </span>

          <td class="vatop tips"><?php echo $lang['ss_edit_support'];?>gif,jpg,jpeg,png </td>
        </tr>
        <?php }?>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15"><a href="JavaScript:void(0);" class="btn" onclick="document.advForm.submit()"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<link type="text/css" rel="stylesheet" href="<?php echo RESOURCE_SITE_URL."/js/jquery-ui/themes/ui-lightness/jquery.ui.css";?>"/>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<script type="text/javascript">
$(function(){
    $('#ss_start_date').datepicker({dateFormat: 'yy-mm-dd'});
    $('#ss_end_date').datepicker({dateFormat: 'yy-mm-dd'});
});
</script>
<script type="text/javascript">
$(function(){
	var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />"
    $(textButton).insertBefore("#file_ss_pic");
    $("#file_ss_pic").change(function(){
	$("#textfield1").val($("#file_ss_pic").val());
    });
});
</script>