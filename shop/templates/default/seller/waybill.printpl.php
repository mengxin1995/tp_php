<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>打印物流单</title>
    <style>
        body { margin: 0; }
        .PageNext{page-break-after: always;}
    </style>
</head>
<body OnLoad="window.print();">
<?php foreach ($output['aid'] as $ak =>$av){?>
<!--分页-->
<div class="PageNext"></div>
<div style="width:100mm;height:59mm;padding-left:2mm;padding-top:1mm;font-family:'microsoft yahei';font-size:1.0em;">
    <div><span>运单号:&nbsp;&nbsp;<?php echo $output['order'.$ak]['shipping_code'];?></span></div>
    <div><span>订单编号:&nbsp;&nbsp;<?php echo $output['order'.$ak]['order_sn'];?></span></div>
    <div><span>店铺名称:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $output['order'.$ak]['store_name'];?></span></div>
    <div><span>联系方式:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $output['order'.$ak]['store_phone'];?></span></div>
    <div><span>发货地址:&nbsp;&nbsp;<?php echo $output['order'.$ak]['store_address'];?></span></div>
    <div style="background-color:#000;height:2px;;"></div>
    <div><span>收货人:(<?php echo $output['order'.$ak]['reciver_name'].' '.$output['order'.$ak]['buyer_phone'];?>)</span> </div>
    <div><span>买家ID:&nbsp;&nbsp;<?php echo $output['order'.$ak]['buyer_name'];?></span> <span style="margin-left:20mm;font-size:1.5em;">签收：</span></div>
    <div><span>收货地址:&nbsp;&nbsp;<?php echo $output['order'.$ak]['address'];?></span></div>
    <div><span>站长:&nbsp;&nbsp;<?php echo $output['order'.$ak]['station_name']?>&nbsp;&nbsp;<?php echo $output['order'.$ak]['phone'];?></span></div>
</div>
<div style="width:100mm;height:89mm;padding-left:2mm;padding-top:2mm;font-family:'microsoft yahei';font-size:1.0em;">
    <div style="margin-left:2mm;font-size:1.1em;">村村兔</div>
    <div><span>运单号:&nbsp;&nbsp;<?php echo $output['order'.$ak]['shipping_code'];?></span></div>
    <div><span>订单编号:&nbsp;&nbsp;<?php echo $output['order'.$ak]['order_sn'];?></span></div>
    <div><span>店铺名称:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $output['order'.$ak]['store_name'];?></span></div>
    <div><span>联系方式:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $output['order'.$ak]['store_phone'];?></span></div>
    <div><span>发货地址:&nbsp;&nbsp;<?php echo $output['order'.$ak]['store_address'];?></span></div>
    <div style="background-color:#000;height:2px;;"></div>
    <div><span>收件人:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $output['order'.$ak]['reciver_name'];?></span> <span>买家ID:&nbsp;&nbsp;<?php echo $output['order'.$ak]['buyer_name'];?></span></div>
    <div><span>联系方式:&nbsp;&nbsp;<?php echo $output['order'.$ak]['phone'];?></span></div>
    <div><span>收货地址:&nbsp;&nbsp;<?php echo $output['order'.$ak]['address'];?></span></div>
    <div style="margin-left:40mm;margin-top:5mm;"><img src="../barcodegen/html/image.php?filetype=PNG&dpi=72&scale=1&rotation=0&font_family=Verdana.ttf&font_size=12&thickness=50&code=BCGcode93&text=<?php echo urlencode($output['order'.$ak]['order_sn']);?>"/></div>
</div>
<?php }?>
</body>
</html>

