<?php defined('InShopNC') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['ss_index_manage'];?></h3>
      <ul class="tab-base">
        <li><a href="index.php?act=screensaver&op=index"><span><?php echo $lang['ss_manage'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['ss_add'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="ss_form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2" id="main_table">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="ss_name"><?php echo $lang['ss_name'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" name="ss_name" id="ss_name" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="ss_description"><?php echo $lang['ss_description'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" name="ss_description" id="ss_description" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="ss_start_time"><?php echo $lang['ss_start_time'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="" name="ss_start_time" id="ss_start_time" class="txt date"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="ss_end_time"><?php echo $lang['ss_end_time'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="" name="ss_end_time" id="ss_end_time" class="txt date"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="file_ss_pic"><?php echo $lang['ss_img_upload'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><span class="type-file-box">
            <input type="file" class="type-file-file" id="file_ss_pic" name="ss_pic" size="90"/>
            </span></td>
          <td class="vatop tips"><?php echo $lang['ss_edit_support'];?>gif,jpg,jpeg,png</td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15" ><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
$(function(){
    $('#ss_start_time').datepicker({dateFormat: 'yy-mm-dd'});
    $('#ss_end_time').datepicker({dateFormat: 'yy-mm-dd'});
});
</script>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#ss_form").valid()){
     $("#ss_form").submit();
	}
	});
});
//
$(document).ready(function(){
	$('#ss_form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        rules : {
        	ss_name : {
                required : true
            },
            ss_start_time  : {
                required : true,
                date	 : false
            },
            ss_end_time  : {
            	required : true,
                date	 : false
            }
        },
        messages : {
        	ss_name : {
                required : '<?php echo $lang['ss_can_not_null'];?>'
            },
            ss_start_time  : {
                required : '<?php echo $lang['ss_start_time_can_not_null']; ?>'
            },
            ss_end_time  : {
            	required   : '<?php echo $lang['ss_end_time_can_not_null']; ?>'
            }
        }
    });
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