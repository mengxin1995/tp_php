<?php defined('InShopNC') or exit('Access Invalid!');?>
<!--v3-v12-->
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['account_syn'];?></h3>
	<?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="post" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label><?php echo $lang['weixin_isuse'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="weixin_isuse_1" class="cb-enable <?php if($output['list_setting']['weixin_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['weixin_isuse_open'];?>"><span><?php echo $lang['weixin_isuse_open'];?></span></label>
            <label for="weixin_isuse_0" class="cb-disable <?php if($output['list_setting']['weixin_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['weixin_isuse_close'];?>"><span><?php echo $lang['weixin_isuse_close'];?></span></label>
            <input type="radio" id="weixin_isuse_1" name="weixin_isuse" value="1" <?php echo $output['list_setting']['weixin_isuse']==1?'checked=checked':''; ?>>
            <input type="radio" id="weixin_isuse_0" name="weixin_isuse" value="0" <?php echo $output['list_setting']['weixin_isuse']==0?'checked=checked':''; ?>></td>
          <td class="vatop tips"><?php echo $lang['weixinSettings_notice'];?></td>
        </tr>

        <tr>
          <td colspan="2" class="required"><label class="validation" for="weixin_appid"><?php echo $lang['weixin_appid'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="weixin_appid" name="weixin_appid" value="<?php echo $output['list_setting']['weixin_appid'];?>" class="txt" type="text">
            </td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="weixin_appkey"><?php echo $lang['weixin_appkey'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="weixin_appkey" name="weixin_appkey" value="<?php echo $output['list_setting']['weixin_appkey'];?>" class="txt" type="text"></td>
          <td class="vatop tips">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="weixin_service_appid"><?php echo $lang['weixin_service_appid'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="weixin_service_appid" name="weixin_service_appid" value="<?php echo $output['list_setting']['weixin_service_appid'];?>" class="txt" type="text">
            </td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="weixin_service_appkey"><?php echo $lang['weixin_service_appkey'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="weixin_service_appkey" name="weixin_service_appkey" value="<?php echo $output['list_setting']['weixin_service_appkey'];?>" class="txt" type="text"></td>
          <td class="vatop tips">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="weixin_subscribe_appid"><?php echo $lang['weixin_subscribe_appid'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="weixin_subscribe_appid" name="weixin_subscribe_appid" value="<?php echo $output['list_setting']['weixin_subscribe_appid'];?>" class="txt" type="text">
            </td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="weixin_subscribe_appkey"><?php echo $lang['weixin_subscribe_appkey'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="weixin_subscribe_appkey" name="weixin_subscribe_appkey" value="<?php echo $output['list_setting']['weixin_subscribe_appkey'];?>" class="txt" type="text"></td>
          <td class="vatop tips">&nbsp;</td>
        </tr>


      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><a href="JavaScript:void(0);" class="btn" onclick="document.settingForm.submit()"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
