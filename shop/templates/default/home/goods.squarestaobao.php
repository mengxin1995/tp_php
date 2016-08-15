<?php defined('InShopNC') or exit('Access Invalid!');?>
<div class="squares" nc_type="current_display_mode">
  <?php if(!empty($output['taobaoke_item_list']) && is_array($output['taobaoke_item_list'])){?>
  <ul class="list_pic">
    <?php foreach($output['taobaoke_item_list'] as $value){?>
    <li class="item">
      <div class="goods-content" nctype_goods=" <?php echo $value['goods_id'];?>" nctype_store="<?php echo $value['store_id'];?>">
        <div class="goods-pic"><a href="<?php echo $value['click_url'];?>" target="_blank" title="<?php echo $value['goods_name'];?>"><img src="<?php echo $value['pic_url'];//echo thumb($value['pic_url'], 240);?>" title="<?php echo $value['goods_name'];?>" alt="<?php echo $value['goods_name'];?>" /></a></div>        
        <div class="goods-info">          
          <div class="goods-name"><a href="<?php echo $value['click_url'];?>" target="_blank" title="<?php echo $value['goods_name'];?>"><?php echo $value['goods_name'];?></a></div>
          <div class="goods-price"> <em class="sale-price" title="<?php echo $lang['goods_class_index_store_goods_price'].$lang['nc_colon'].$lang['currency'].$value['coupon_price'];?>"><?php echo ncPriceFormatForList($value['coupon_price']);?></em> <em class="market-price" title="市场价：<?php echo $lang['currency'].$value['price'];?>"><?php echo ncPriceFormatForList($value['price']);?></em> <span class="raty" data-score="5"></span></div>          
          <div class="sell-stat">
            <ul>
              <li><a href="<?php echo $value['click_url'];?>#ncGoodsRate" target="_blank" class="status"><?php echo $value['goods_salenum'];?></a>
                <p>商品销量</p>
              </li>
              <li><a href="<?php echo $value['click_url'];?>" target="_blank"><?php echo $value['evaluation_count'];?></a>
                <p>用户评论</p>
              </li>
              <li><em member_id="<?php echo $value['member_id'];?>">&nbsp;</em></li>
            </ul>
          </div>
          <div class="store"><a href="<?php echo $value['click_url'];?>" title="<?php echo $value['store_name'];?>" class="name" target="_blank"><?php echo $value['store_name'];?></a></div>
          <div class="add-cart">
            <a href="<?php echo $value['click_url'];?>" nctype="add_cart" target="_blank"><i class="icon-shopping-cart"></i>立即购买</a>
          </div>
		  <div>
		  <?php echo $value['commentNickName'].':'.$value['comment'];?>
		  </div>
        </div>
      </div>
    </li>
    <?php }?>
    <div class="clear"></div>
  </ul>
  <?php }else{?>
  <div id="no_results" class="no-results"><i></i><?php echo $lang['index_no_record'];?></div>
  <?php }?>
</div>