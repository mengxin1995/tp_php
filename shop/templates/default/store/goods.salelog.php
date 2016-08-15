<?php defined('InShopNC') or exit('Access Invalid!');?>
<script type="text/javascript">
$(document).ready(function(){
		$('#salelog_demo').find('.demo').ajaxContent({
			event:'click', //mouseover
			loaderType:"img",
			loadingMsg:"<?php echo SHOP_TEMPLATES_URL;?>/images/transparent.gif",
			target:'#salelog_demo'
		});

});
</script>

<?php
function cut_str($string, $sublen, $start = 0, $code = 'UTF-8')
{
  if($code == 'UTF-8')
  {
    $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
    preg_match_all($pa, $string, $t_string);
    if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen));
    return join('', array_slice($t_string[0], $start, $sublen));
  }
  else
  {
    $start = $start*2;
    $sublen = $sublen*2;
    $strlen = strlen($string);
    $tmpstr = '';
    for($i=0; $i< $strlen; $i++)
    {
      if($i>=$start && $i< ($start+$sublen))
      {
        if(ord(substr($string, $i, 1))>129)
        {
          $tmpstr.= substr($string, $i, 2);
        }
        else
        {
          $tmpstr.= substr($string, $i, 1);
        }
      }
      if(ord(substr($string, $i, 1))>129) $i++;
    }
    return $tmpstr;
  }
}
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="mt10">
  <thead>
    <tr>
      <th class="w200"><?php echo $lang['goods_index_buyer'];?></th>
<!--      <th class="w100">--><?php //echo $lang['goods_index_buy_price'];?><!--</th>-->
      <th class=""><?php echo $lang['goods_index_buy_amount'];?></th>
      <th class="w200"><?php echo $lang['goods_index_buy_time'];?></th>
    </tr>
  </thead>
  <?php if(!empty($output['sales']) && is_array($output['sales'])){?>
  <tbody>
    <?php foreach($output['sales'] as $key=>$sale){?>
    <tr>
      <?php if(!empty($_SESSION['member_id'])){ ?>
      <td><a href="index.php?act=member_snshome&mid=<?php echo $sale['buyer_id'];?>" target="_blank" data-param="{'id':<?php echo $sale['buyer_id'];?>}" nctype="mcard"><?php echo $sale['buyer_name'];?></a></td>
      <?php } else {?>
        <td><?php echo cut_str($sale['buyer_name'], 1, 0).'**'.cut_str($sale['buyer_name'], 1, -1);;?></td>
      <?php }?>
<!--      <td><em class="price">--><?php //echo $lang['currency'].$output['marketprice'];?><!--</em> <i style="color:red;">--><?php //echo $output['order_type'][$sale['goods_type']];?><!--</i></td>-->
      <td><?php echo $sale['goods_num'];?></td>
      <td><time><?php echo date('Y-m-d H:i:s', $sale['add_time']);?></time></td>
    </tr>
    <?php }?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="10" class="tr" ><div class="pagination"> <?php echo $output['show_page'];?> </div></td>
    </tr>
  </tfoot>
  <?php }else{?>
  <tbody>
    <tr>
      <td colspan="10" class="ncs-norecord"><?php echo $lang['no_record'];?></td>
    </tr>
  </tbody>
  <?php }?>
</table>
