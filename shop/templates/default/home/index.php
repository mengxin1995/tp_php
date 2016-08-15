<?php
/*
 * alphawu 2015.10.15,修改83行，去掉热门晒单功能。
 */
?>
<?php defined('InShopNC') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/index.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/home_index.js" charset="utf-8"></script>
<!--[if IE 6]>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ie6.js" charset="utf-8"></script>
<![endif]-->
<script type="text/javascript">
var uid = window.location.href.split("#V3");
var  fragment = uid[1];
if(fragment){
	if (fragment.indexOf("V3") == 0) {document.cookie='uid=0';}
else {document.cookie='uid='+uid[1];}
	}

</script>
<style type="text/css">
.category { display: block !important; }
</style>
<div class="clear"></div>

<!-- HomeFocusLayout Begin-->
<div class="home-focus-layout"> <?php echo $output['web_html']['index_pic'];?>
  <div class="right-sidebar">
    <div class="proclamation">
        <a data-type="5" data-tmpl="210x220" data-tmplid="135" data-style="2" data-border="1" href="#">淘宝充值</a>
    </div>
    <?php if(!empty($output['group_list']) && is_array($output['group_list'])) { ?>
    <div class="groupbuy">
      <div class="title"><i>抢</i>每日一品</div>
      <ul>
        <?php foreach($output['group_list'] as $val) { ?>
        <li>
          <dl><a href="<?php echo urlShop('show_groupbuy','groupbuy_detail',array('group_id'=> $val['groupbuy_id']));?>"><img src="<?php echo gthumb($val['groupbuy_image1'], 'small');?>" style="width:100%;"/></a>
            <dt><a href="<?php echo urlShop('show_groupbuy','groupbuy_detail',array('group_id'=> $val['groupbuy_id']));?>"><?php echo $val['groupbuy_name']; ?></a></dt>
            <dd class="price"><span class="groupbuy-price"><a href="<?php echo urlShop('show_groupbuy','groupbuy_detail',array('group_id'=> $val['groupbuy_id']));?>"><?php echo ncPriceFormatForList($val['groupbuy_price']); ?></a></span><span class="buy-button"><a href="<?php echo urlShop('show_groupbuy','groupbuy_detail',array('group_id'=> $val['groupbuy_id']));?>"><?php if($val['state']==32){echo '已结束';}else{if($val['start_time']>time()){echo '未开始';}else{echo '立即抢';}}?></a></span></dd>
            <dd class="time"><span class="sell">已售<em><?php echo $val['buy_quantity'];?></em></span> <span class="time-remain" count_down="<?php echo $val['end_time']-TIMESTAMP; ?>"> <em time_id="d">0</em><?php echo $lang['text_tian'];?><em time_id="h">0</em><?php echo $lang['text_hour'];?> <em time_id="m">0</em><?php echo $lang['text_minute'];?><em time_id="s">0</em><?php echo $lang['text_second'];?> </span></dd>
          </dl>
        </li>
        <?php } ?>
      </ul>
    </div>
    <?php }else{ ?>
      <!--每日一品替代广告位开始-->
    <div class="groupbuy">
      <div class="title"><i>热</i>推荐商品</div>
      <?php if($output['current_site']['site_id'] == '0'){?>
        <?php echo loadadv(1050);?>
      <?php }else if($output['current_site']['site_id'] == '1'){?>
        <?php echo loadadv(1070);?>
        <?php echo loadadv(1050);?>
      <?php } else if($output['current_site']['site_id'] == '2'){?>
        <?php echo loadadv(1090);?>
        <?php echo loadadv(1050);?>
      <?php } ?>
      <!--每日一品替代广告位结束-->
    </div>
    <?php }?>
  </div>
</div>
<!--HomeFocusLayout End-->

<div class="home-sale-layout wrapper">
  <div class="left-layout"> <?php echo $output['web_html']['index_sale'];?> </div>
  <?php if(!empty($output['xianshi_item']) && is_array($output['xianshi_item'])) { ?>
  <div class="right-sidebar">
    <div class="title">
      <h3><?php echo $lang['nc_xianshi'];?></h3>
    </div>
    <div id="saleDiscount" class="sale-discount">
      <ul>
        <?php foreach($output['xianshi_item'] as $val) { ?>
        <li>
          <dl>
            <dt class="goods-name"><?php echo $val['goods_name']; ?></dt>
            <dd class="goods-thumb"><a href="<?php echo urlShop('goods','index',array('goods_id'=> $val['goods_id']));?>"> <img src="<?php echo thumb($val, 240);?>"/></a></dd>
            <dd class="goods-price"><?php echo ncPriceFormatForList($val['xianshi_price']); ?> <span class="original"><?php echo ncPriceFormatForList($val['goods_price']);?></span></dd>
            <dd class="goods-price-discount"><em><?php echo $val['xianshi_discount']; ?></em></dd>
            <dd class="time-remain" count_down="<?php echo $val['end_time']-TIMESTAMP;?>"><i></i><em time_id="d">0</em><?php echo $lang['text_tian'];?><em time_id="h">0</em><?php echo $lang['text_hour'];?> <em time_id="m">0</em><?php echo $lang['text_minute'];?><em time_id="s">0</em><?php echo $lang['text_second'];?> </dd>
            <dd class="goods-buy-btn"></dd>
          </dl>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <?php } ?>
</div>
<!--首页中上部banner开始-->
<div class="wrapper">
  <div class="mt10">
    <div class="mt10">
      <?php if($output['current_site']['site_id'] == '0'){?>
        <?php echo loadadv(11,'html');?>
      <?php }else if($output['current_site']['site_id'] == '1'){?>
        <?php echo loadadv(11,'html');?>
        <?php echo loadadv(21,'html');?>
      <?php } else if($output['current_site']['site_id'] == '2'){?>
        <?php echo loadadv(11,'html');?>
        <?php echo loadadv(31,'html');?>
      <?php } ?>
    </div>
  </div>
</div>
<!--首页中上部banner结束-->
<!--StandardLayout Begin--> 
<?php echo $output['web_html']['index'];?> 
<!--StandardLayout End--> 
<!--热门晒单str v3-b12-->
<?php if(!empty($output['goods_evaluate_info'])&&1>2){?>
<div class="wrapper">
  <div class="home-share">
    <div class="caption">
      <p>热门晒单</p>
    </div>
    <div class="scrollbox">
      <ul>
        <?php if(!empty($output['goods_evaluate_info']) && is_array($output['goods_evaluate_info'])){?>
        <?php foreach($output['goods_evaluate_info'] as $k=>$v){?>
        <li>
          <div class="share_c">
            <div class="l"> <a href="<?php echo urlShop('goods','index',array('goods_id'=> $v['geval_goodsid']));?>" target="_blank"> <img src="<?php echo strpos($v['goods_pic'],'http')===0 ? $v['goods_pic']:UPLOAD_SITE_URL."/".ATTACH_GOODS."/".$v['geval_storeid']."/".$v['geval_goodsimage'];?>"> </a></div>
            <div class="r">
              <p><a href="<?php echo urlShop('goods','index',array('goods_id'=> $v['geval_goodsid']));?>" target="_blank"><?php echo $v['geval_goodsname'];?></a> </p>
              <p class="s_title"><a class="s_content" href="<?php echo urlShop('goods','index',array('goods_id'=> $v['geval_goodsid']));?>" target="_blank"><?php echo $v['geval_content'];?></a> </p>
            </div>
          </div>
        </li>
        <?php }}?>
      </ul>
    </div>
  </div>
</div>
<!--热门晒单end-->
<?php }?>
<!--首页底部通栏图片广告start-->
<div class="wrapper">
  <div class="mt10">
    <?php if($output['current_site']['site_id'] == '0'){?>
      <?php echo loadadv(9,'html');?>
      <?php echo loadadv(19,'html');?>
      <?php echo loadadv(29,'html');?>
    <?php }else if($output['current_site']['site_id'] == '1'){?>
      <?php echo loadadv(9,'html');?>
      <?php echo loadadv(19,'html');?>
    <?php } else if($output['current_site']['site_id'] == '2'){?>
      <?php echo loadadv(9,'html');?>
      <?php echo loadadv(29,'html');?>
    <?php } ?>

  </div>
</div>
<!--首页底部通栏图片广告end-->
<!--link Begin-->
<!--<div class="full_module wrapper">
  <h2><b><?php echo $lang['index_index_link'];?></b></h2>
  <div class="piclink">
    <?php if(is_array($output['$link_list']) && !empty($output['$link_list'])) {
		  	foreach($output['$link_list'] as $val) {
		  		if($val['link_pic'] != ''){
		  ?>
    <span><a href="<?php echo $val['link_url']; ?>" target="_blank"><img src="<?php echo $val['link_pic']; ?>" title="<?php echo $val['link_title']; ?>" alt="<?php echo $val['link_title']; ?>" width="88" height="31" ></a></span>
    <?php
		  		}
		 	}
		 }
		 ?>
    <div class="clear"></div>
  </div>
  <div class="textlink">
    <?php 
		  if(is_array($output['$link_list']) && !empty($output['$link_list'])) {
		  	foreach($output['$link_list'] as $val) {
		  		if($val['link_pic'] == ''){
		  ?>
    <span><a href="<?php echo $val['link_url']; ?>" target="_blank" title="<?php echo $val['link_title']; ?>"><?php echo str_cut($val['link_title'],16);?></a></span>
    <?php
		  		}
		 	}
		 }
		 ?>
    <div class="clear"></div>
  </div>
</div>-->
<!--link end-->
<div class="footer-line"></div>
<!--首页底部保障开始-->
<?php require_once template('layout/index_ensure');?>
<!--首页底部保障结束-->
<!--StandardLayout Begin-->
<div class="nav_Sidebar">
<a class="nav_Sidebar_1" href="javascript:;" ></a>
<a class="nav_Sidebar_2" href="javascript:;" ></a>
<a class="nav_Sidebar_3" href="javascript:;" ></a>
<a class="nav_Sidebar_4" href="javascript:;" ></a>
<a class="nav_Sidebar_5" href="javascript:;" ></a>
<a class="nav_Sidebar_6" href="javascript:;" ></a> 
<!--
<a class="nav_Sidebar_7" href="javascript:;" ></a>
<a class="nav_Sidebar_8" href="javascript:;" ></a>
-->
</div>
<!--StandardLayout End-->