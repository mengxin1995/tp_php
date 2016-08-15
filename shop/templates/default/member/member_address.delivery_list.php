<?php defined('InShopNC') or exit('Access Invalid!');?>
<?php if (!empty($output['list'])) { ?>

<ul>
  <?php foreach($output['list'] as $k => $v) { ?>
  <li>
    <label>
      <input type="radio" <?php echo $_GET['dlyp_id'] == $v['dlyp_id'] ? 'checked' : null; ?> nctype="dlyp_radio" data_image="<?php echo $v['dlyp_idcard_image'];?>" data_area="<?php echo $v['dlyp_area_info'].$v['dlyp_address'];?>" value="<?php echo $v['dlyp_id'];?>" name="dlyp_id" >
      <?php echo $v['dlyp_address_name'] ? $v['dlyp_address_name'].'，' : '';?>
      站长：<?php echo $v['dlyp_truename'];?>，
      电话：<?php echo $v['dlyp_telephony'] ? $v['dlyp_telephony'] : $v['dlyp_mobile'];?></label>
    <div class="delivery-map"></div>
  </li>
  <?php } ?>
</ul>
<div class="pagination"> <?php echo $output['show_page'];?> </div>
<?php } else { ?>
<div class="no-delivery">该地区下还没有自提点！</div>
<?php } ?>
<script type="text/javascript">
$(document).ready(function(){
	$('input[nctype="dlyp_radio"]').on('click',function(){
		$('#zt_address > ul').children().removeClass('select');
		$(this).parent().parent().addClass('select');
		$('.delivery-map').html('<img height="250" width="250" src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_DELIVERY.DS?>'+$(this).attr('data_image')+'">');
		/*$('input[name="address"]').val("");*/
		$('#address_vaild').hide();
	});
	if ($('input[type="radio"]:checked').val()) {
		$('input[type="radio"]:checked').parent().parent().addClass('select');
		$('.delivery-map').html('<img height="250" width="250" src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_DELIVERY.DS?>'+$(this).attr('data_image')+'">');
	}
	$('#zt_address').find('.demo').ajaxContent({
		event:'click', //mouseover
		loaderType:"img",
		loadingMsg:"<?php echo SHOP_TEMPLATES_URL;?>/images/transparent.gif",
		target:'#zt_address'
	});
});

</script>