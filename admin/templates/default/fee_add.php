<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['fee_index_manage'];?></h3>
      <ul class="tab-base">
        <li><a href="index.php?act=fee&op=index"><span><?php echo $lang['fee_manage'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['fee_add'];?></span></a><em></em></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="link_form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="fee_name"><?php echo $lang['fee_name'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" name="fee_name" id="fee_name" class="txt">
            </td>
          <td class="vatop tips"></td>
        </tr>

        <tr>
          <td colspan="2" class="required"><label class="validation" for="send_area"><?php echo $lang['fee_send_area'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><select name="send_area" id="send_area">
              <?php
              foreach ($output['province_list'] as $k=>$v){
                  echo "<option value='".$k."' >".$v;
                  echo "</option>";
              }
              ?>
            </select>
		  </td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="receive_area"><?php echo $lang['fee_receive_area'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><select name="receive_area" id="receive_area" notequalTo="#send_area">
              <?php
              foreach ($output['province_list'] as $k=>$v){
                  echo "<option value='".$k."' >".$v;
                  echo "</option>";
              }
              ?>
            </select>
		  </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
            <td colspan="2" class="required"><label class="validation" for="fee_goods_id"><?php echo $lang['fee_goods_id'];?>:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><input type="text" name="fee_goods_id" id="fee_goods_id" class="txt">
            </td>
            <td class="vatop tips"><?php echo $lang['fee_goods_help'];?></td>
        </tr>

        <tr>
          <td colspan="2" class="required"><?php echo $lang['fee_is_use'];?>:</td>
        </tr>
        <tr class="noborder">
			<td class="vatop rowform"><ul>
              <li>
                <input name="stat" type="radio" value="1" checked="checked">
                <label><?php echo $lang['fee_in_use'];?></label>
              </li>
              <li>
                <input type="radio" name="stat" value="0">
                <label><?php echo $lang['fee_not_in_use'];?></label>
              </li>
            </ul></td>
          <td class="vatop tips"></td>
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
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#link_form").valid()){
     $("#link_form").submit();
	}
	});
});
//
$(document).ready(function(){
    $.validator.addMethod("notequalTo", function (value, element, param)
        {
            return value != $(param).val();
        },
        "<?php echo $lang['fee_area_can_not_be_same'];?>"
    );
    $('#link_form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        rules : {
        	fee_name : {
                required : true
            },
            send_area:{
				required :true
			},
            fee_goods_id:{
				required :true
			},
            receive_area:{
				required :true
			}
        },
        messages : {
            fee_name : {
                required : '<?php echo $lang['fee_name_can_not_null'];?>'
            },
            send_area:{
                required :'<?php echo $lang['fee_send_area_can_not_null'];?>'
            },
            fee_goods_id:{
                required :'<?php echo $lang['fee_goods_id_can_not_null'];?>'
            },
            receive_area:{
                required :'<?php echo $lang['fee_receive_area_can_not_null'];?>',
                notequalTo:'<?php echo $lang['fee_area_can_not_be_same'];?>'
            }
        }
    });
});
</script>