<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['nc_transfer_set']?></h3>
    </div>
  </div>
  <div class="fixed-empty"></div>
  
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12" class="nobg"><div class="title">
            <h5><?php echo $lang['nc_prompts'];?></h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul>
        	<li><?php echo $lang['transfer_tip1'];?></li>
        	<li><?php echo $lang['transfer_tip2'];?></li>
        	<li><?php echo $lang['transfer_tip3'];?></li>
        	<li><?php echo $lang['transfer_tip4'];?></li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <form method="post" name="form_index">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <tr>
          <td colspan="2" class="required"><label><?php echo $lang['nc_transfer_set'];?></label></td>
        </tr>
        <tr class="noborder">
          <td class="w96"><?php echo $lang['admin_transfer_pay_account'];?></td><td><input id="pay_user" name="pay_user" value="" class="w300" type="text"/></td>
        </tr>
        <tr class="noborder">
          <td class="w96"><?php echo $lang['admin_transfer_receiver_account'];?></td><td><input id="receiver_user" name="receiver_user" value="" class="w300" type="text" maxlength="200" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96"><?php echo $lang['admin_transfer_pay_amount'];?></td><td><input id="pay_amount" name="pay_amount" value="" class="w300" type="text" maxlength="200" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96"><?php echo $lang['admin_transfer_reason'];?></td><td><input id="pay_reason" name="pay_reason" value="" class="w300" type="text" maxlength="200" /></td>
        </tr>

      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><a href="JavaScript:void(0);" class="btn" onclick="document.form_index.submit()"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript">
$(function(){

});
</script>