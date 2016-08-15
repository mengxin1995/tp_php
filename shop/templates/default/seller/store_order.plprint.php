<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <?php defined('InShopNC') or exit('Access Invalid!');?>

  <link href="<?php echo SHOP_TEMPLATES_URL;?>/css/seller_center.css" rel="stylesheet" type="text/css"/>
  <style type="text/css">
    body { background: #FFF none;
    }
    .PageNext{page-break-after: always;}
  </style>
  <title><?php echo $lang['member_printorder_print'];?>--<?php echo $output['store_info']['store_name'];?><?php echo $lang['member_printorder_title'];?></title>
</head>

<body OnLoad="window.print();">
<?php if (!empty($output['order_info0'])){?>
<div class="print-page ">
  <div id="printarea">
    <?php foreach ($output['aid'] as $ak =>$av){?>
      <?php foreach ($output['goods_list'.$ak] as $item_k =>$item_v){?>
        <!--分页-->
        <div class="PageNext"></div>
        <div class="orderprint">
          <div style="position:absolute;padding-left:8mm;padding-top:2mm;display:none;"><img src="../barcodegen/html/image.php?filetype=PNG&dpi=72&scale=1&rotation=0&font_family=Verdana.ttf&font_size=12&thickness=50&code=BCGcode93&text=<?php echo urlencode($output['order_info'.$ak]['order_sn']);?>"/></div>
          <div style="position:absolute;padding-left:150mm;display:none;"><img style="width:135px;height:135px;" src="../phpqrcode/img.php?data=<?php echo urlencode('http://wuliu.cuncuntu.com/index.php?g=&m=Order&a=s&order_id='.$output['order_info'.$ak]['order_sn']);?>"/></div>
          <div class="top">
            <?php if (empty($output['store_info'.$ak]['store_label'])){?>
              <div class="full-title"><?php echo $output['store_info'.$ak]['store_name'];?> <?php echo $lang['member_printorder_title'];?></div>
            <?php }else {?>
              <div class="logo"></div>
              <div class="logo-title"><?php echo $output['store_info'.$ak]['store_name'];?><?php echo $lang['member_printorder_title'];?></div>
            <?php }?>
          </div>
          <table class="buyer-info" style="margin-top: 0px;">
            <tr>
              <td class="w200"><?php echo $lang['member_printorder_truename'].$lang['nc_colon']; ?><?php echo $output['order_info'.$ak]['extend_order_common']['reciver_name'];?></td>
              <td><?php echo '电话'.$lang['nc_colon']; ?><?php echo @$output['order_info'.$ak]['extend_order_common']['reciver_info']['phone'];?><span style="color:#FFF;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>买家ID：<?php echo $output['order_info'.$ak]['buyer_name'];?></td>
              <td></td>
            </tr>
            <tr>
              <td colspan="3">发&nbsp;&nbsp;&nbsp;&nbsp;票： <?php foreach ((array)$output['order_info'.$ak]['extend_order_common']['invoice_info'] as $key => $value){?>
                  <span><?php echo $key;?> (<strong><?php echo $value;?></strong>)</span>
                <?php } ?>
              </td>
            </tr>
            <tr>
              <td colspan="3">
                买家留言：<?php echo @$output['order_info'.$ak]['extend_order_common']['order_message']; ?>
              </td>
            </tr>
            <tr>
              <td colspan="3"><?php echo $lang['member_printorder_address'].$lang['nc_colon']; ?><?php echo @$output['order_info'.$ak]['extend_order_common']['reciver_info']['address'];?></td>
            </tr>
            <tr>
              <td><?php echo $lang['member_printorder_orderno'].$lang['nc_colon'];?><?php echo $output['order_info'.$ak]['order_sn'];?></td>
              <td><?php echo $lang['member_printorder_orderadddate'].$lang['nc_colon'];?><?php echo @date('Y-m-d',$output['order_info'.$ak]['add_time']);?></td>
              <td><?php if ($output['order_info'.$ak]['shippin_code']){?>
                  <span><?php echo $lang['member_printorder_shippingcode'].$lang['nc_colon']; ?><?php echo $output['order_info'.$ak]['shipping_code'];?></span>
                <?php }?></td>
            </tr>
          </table>
          <table class="order-info">
            <thead>
            <tr>
              <th class="w40"><?php echo $lang['member_printorder_serialnumber'];?></th>
              <th class="tl"><?php echo $lang['member_printorder_goodsname'];?></th>
              <th class="w70 tl"><?php echo $lang['member_printorder_goodsprice'];?>(<?php echo $lang['currency_zh'];?>)</th>
              <th class="w50"><?php echo $lang['member_printorder_goodsnum'];?></th>
              <th class="w70 tl"><?php echo $lang['member_printorder_subtotal'];?>(<?php echo $lang['currency_zh'];?>)</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($item_v as $k=>$v){?>
              <tr>
                <td><?php echo $k;?></td>
                <td class="tl"><?php echo $v['goods_name'];?>[<?php echo $v['goods_barcode'];?>]</td>
                <td class="tl"><?php echo $lang['currency'].$v['goods_price'];?></td>
                <td><?php echo $v['goods_num'];?></td>
                <td class="tl"><?php echo $lang['currency'].$v['goods_all_price'];?></td>
              </tr>
            <?php }?>
            <tr>
              <th></th>
              <th colspan="2" class="tl"><?php echo $lang['member_printorder_amountto'];?></th>
              <th><?php echo $output['goods_all_num'.$ak];?></th>
              <th class="tl"><?php echo $lang['currency'].$output['goods_total_price'.$ak];?></th>
            </tr>
            </tbody>
            <tfoot>
            <tr>
              <th colspan="10"><span><?php echo $lang['member_printorder_totle'].$lang['nc_colon'];?><?php echo $lang['currency'].$output['goods_total_price'.$ak];?></span><span><?php echo $lang['member_printorder_freight'].$lang['nc_colon'];?><?php echo $lang['currency'].$output['order_info'.$ak]['shipping_fee'];?></span><span><?php echo $lang['member_printorder_privilege'].$lang['nc_colon'];?><?php echo $lang['currency'].$output['promotion_amount'.$ak];?></span><span><?php echo $lang['member_printorder_orderamount'].$lang['nc_colon'];?><?php echo $lang['currency'].$output['order_info'.$ak]['order_amount'];?></span><span><?php echo $lang['member_printorder_shop'].$lang['nc_colon'];?><?php echo $output['store_info'.$ak]['store_name'];?></span>
                <?php if (!empty($output['store_info'.$ak]['store_qq'])){?>
                  <span>QQ：<?php echo $output['store_info'.$ak]['store_qq'];?></span>
                <?php }elseif (!empty($output['store_info'.$ak]['store_ww'])){?>
                  <span><?php echo $lang['member_printorder_shopww'].$lang['nc_colon'];?><?php echo $output['store_info'.$ak]['store_ww'];?></span>
                <?php }?></th>
            </tr>
            </tfoot>
          </table>
          <?php if (empty($output['store_info'.$ak]['store_stamp'])){?>
            <div class="explain">
              <?php echo $output['store_info'.$ak]['store_printdesc'];?>
            </div>
          <?php }else {?>
            <div class="explain">
              <?php echo $output['store_info'.$ak]['store_printdesc'];?>
            </div>
            <div class="seal"><img src="<?php echo $output['store_info'.$ak]['store_stamp'];?>" onload="javascript:DrawImage(this,120,120);"/></div>
          <?php }?>
          <div class="tc page"><?php echo $lang['member_printorder_pagetext_1']; ?><?php echo $item_k;?><?php echo $lang['member_printorder_pagetext_2']; ?>/<?php echo $lang['member_printorder_pagetext_3']; ?><?php echo count($output['goods_list']);?><?php echo $lang['member_printorder_pagetext_2']; ?></div>
        </div>
      <?php }}?>
  </div>
  <?php }?>
</div>
</div>
</body>
</html>