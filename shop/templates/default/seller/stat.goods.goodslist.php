<?php defined('InShopNC') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="alert mt10" style="clear:both;">
	<ul class="mt5">
		<li>1、<?php echo $lang['stat_validorder_explain'];?></li>
        <li>2、点击每列旁边的箭头对列表进行排序，默认按照“最近30天下单数量”降序排列</li>
        <li>3、点击每条记录后的“走势图”，查看该时间段内下单金额、下单商品数、下单量走势</li>
      </ul>
</div>
<form method="get" action="index.php" target="_self" id="formSearch">
  <table class="search-form">
    <input type="hidden" name="act" value="statistics_goods" />
    <input type="hidden" name="op" value="goodslist" />
    <input type="hidden" id="orderby" name="orderby" value="<?php echo $output['orderby'];?>"/>
      <tr>
          <td class="tr">
              <div class="fr">
                  <div class="fl">&nbsp;&nbsp;时间区间&nbsp;
                      <select name="search_type" id="search_type" class="querySelect">
                          <option value="last_thirty_days" <?php echo $output['search_arr']['search_type']=='last_thirty_days'?'selected':''; ?>>最近三十天</option>
                          <option value="day" <?php echo $output['search_arr']['search_type']=='day'?'selected':''; ?>>按天统计</option>
                          <option value="week" <?php echo $output['search_arr']['search_type']=='week'?'selected':''; ?>>按周统计</option>
                          <option value="month" <?php echo $output['search_arr']['search_type']=='month'?'selected':''; ?>>按月统计</option>
                          <option value="custom" <?php echo $output['search_arr']['search_type']=='custom'?'selected':''; ?>>自定义</option>
                      </select>
                  </div>
                  <div id="searchtype_day" style="display:none;" class="fl">
                      <input type="text" class="text w70" name="search_time" id="search_time" value="<?php echo @date('Y-m-d',$output['search_arr']['day']['search_time']);?>" /><label class="add-on"><i class="icon-calendar"></i></label>
                  </div>
                  <div id="searchtype_week" style="display:none;" class="fl">
                      <select name="searchweek_year" class="querySelect">
                          <?php foreach ($output['year_arr'] as $k=>$v){?>
                              <option value="<?php echo $k;?>" <?php echo $output['search_arr']['week']['current_year'] == $k?'selected':'';?>><?php echo $v; ?></option>
                          <?php } ?>
                      </select>
                      <select name="searchweek_month" class="querySelect">
                          <?php foreach ($output['month_arr'] as $k=>$v){?>
                              <option value="<?php echo $k;?>" <?php echo $output['search_arr']['week']['current_month'] == $k?'selected':'';?>><?php echo $v; ?></option>
                          <?php } ?>
                      </select>
                      <select name="searchweek_week" class="querySelect">
                          <?php foreach ($output['week_arr'] as $k=>$v){?>
                              <option value="<?php echo $v['key'];?>" <?php echo $output['search_arr']['week']['current_week'] == $v['key']?'selected':'';?>><?php echo $v['val']; ?></option>
                          <?php } ?>
                      </select>
                  </div>
                  <div id="searchtype_month" style="display:none;" class="fl">
                      <select name="searchmonth_year" class="querySelect">
                          <?php foreach ($output['year_arr'] as $k=>$v){?>
                              <option value="<?php echo $k;?>" <?php echo $output['search_arr']['month']['current_year'] == $k?'selected':'';?>><?php echo $v; ?></option>
                          <?php } ?>
                      </select>
                      <select name="searchmonth_month" class="querySelect">
                          <?php foreach ($output['month_arr'] as $k=>$v){?>
                              <option value="<?php echo $k;?>" <?php echo $output['search_arr']['month']['current_month'] == $k?'selected':'';?>><?php echo $v; ?></option>
                          <?php } ?>
                      </select>
                  </div>
                  <div id="searchtype_custom" style="display:none;" class="fl">
                      <input type="text" class="text w70" name="search_custom_start" id="search_custom_start" value="<?php echo @date('Y-m-d',$output['search_arr']['custom']['search_custom_start']);?>" /><label class="add-on"><i class="icon-calendar"></i></label>
                      ~
                      <input type="text" class="text w70" name="search_custom_end" id="search_custom_end" value="<?php echo @date('Y-m-d',$output['search_arr']['custom']['search_custom_end']);?>" /><label class="add-on"><i class="icon-calendar"></i></label>
                  </div>
              </div>
              <div class="fr">商品分类&nbsp;
                  <span id="searchgc_td"></span><input type="hidden" id="choose_gcid" name="choose_gcid" value="0"/>
              </div>
          </td>
      </tr>
      <tr>
          <td class="tr">
              <div class="fr">
                  <label class="submit-border"><a class="submit" href="index.php?act=statistics_goods&op=export&search_time=<?php $output['search_arr']['search_type'] ?>&search_type=<?php echo $output['search_arr']['search_type'] ?>&search_time=<?php echo $output['search_arr']['day']['search_time'] ?>&current_week=<?php echo $output['search_arr']['week']['current_week'] ?>&current_year=<?php echo $output['search_arr']['month']['current_year'] ?>&current_month=<?php echo $output['search_arr']['month']['current_month'] ?>&search_custom_start=<?php echo $output['search_arr']['custom']['search_custom_start'] ?>&search_custom_end=<?php echo $output['search_arr']['custom']['search_custom_end'] ?>"><span>导出Excel&nbsp;</span></a></label>
              </div>
              <div class="fr">&nbsp;
                  <label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['nc_common_search'];?>" /></label>
              </div>
              <div class="fr">商品名称&nbsp;
                  <input type="text" class="text w150" name="search_gname" value="<?php echo $_GET['search_gname']; ?>" />
              </div>
          </td>
      </tr>
  </table>
</form>
<table class="ncsc-default-table">
  <thead>
    <tr class="sortbar-array">
      <th>商品编号</th>
      <th></th>
      <th>商品名称</th>
      <th>现价</th>
      <th class="align-center"><a title="点击进行排序" nc_type="orderitem" data-param='{"orderby":"ordergoodsnum"}' class="<?php echo (!$output['orderby'] || $output['orderby']=='ordergoodsnum desc')?'selected desc':''; echo $output['orderby']=='ordergoodsnum asc'?'selected asc':''; ?>">该时间段下单数量<i></i></a></th>
      <th class="align-center"><a title="点击进行排序" nc_type="orderitem" data-param='{"orderby":"ordergamount"}' class="<?php echo $output['orderby']=='ordergamount desc'?'selected desc':''; echo $output['orderby']=='ordergamount asc'?'selected asc':''; ?>">该时间段下单金额<i></i></a></th>
      <th>总下单数量</th>
      <th>总下单金额</th>
      <th class="w120"><?php echo $lang['nc_handle'];?></th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['goodslist']) && is_array($output['goodslist'])) { ?>
    <?php foreach($output['goodslist'] as $v) { ?>
    <tr class="bd-line">
      <td><?php echo $v['goods_id'];?></td>
      <td width="33px"><div class="pic-thumb"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $v['goods_id']));?>" target="_blank"><img src="<?php echo thumb($v, 60);?>"/></a></div></td>
      <td class="tl" style="width: 190px">
          <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $v['goods_id']));?>" target="_blank"><?php echo $v['goods_name'];?></a>
      </td>
      <td><?php echo $v['goods_price'];?></td>
      <td><?php echo $v['ordergoodsnum'];?></td>
      <td><?php echo $v['ordergamount'];?></td>
      <td><?php echo $v['ordergnumsum'];?></td>
      <td><?php echo $v['ordergamountsum'];?></td>
      <td><a href="javascript:void(0);" nc_type='showdata' data-param='{"gid":"<?php echo $v['goods_id'];?>"}'>走势图</a></td>
    </tr>
    <?php }?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <?php if (!empty($output['goodslist']) && is_array($output['goodslist'])) { ?>
  <tfoot>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
  </tfoot>
  <?php } ?>
</table>
<table class="ncsc-default-table">
	<tbody>
    	<tr>
    		<div id="goodsinfo_div" class="close_float" style="text-align:center;"></div>
    	</tr>
	</tbody>
</table>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" ></script>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" ></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/highcharts/highcharts.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script>

<script type="text/javascript">
//展示搜索时间框
function show_searchtime(){
    s_type = $("#search_type").val();
    $("[id^='searchtype_']").hide();
    $("#searchtype_"+s_type).show();
}
$(function(){
    //统计数据类型
    var s_type = $("#search_type").val();
    $('#search_time').datepicker({dateFormat: 'yy-mm-dd'});
    $('#search_custom_start').datepicker({dateFormat: 'yy-mm-dd'});
    $('#search_custom_end').datepicker({dateFormat: 'yy-mm-dd'});

    show_searchtime();
    $("#search_type").change(function(){
        show_searchtime();
    });

    //更新周数组
    $("[name='searchweek_month']").change(function(){
        var year = $("[name='searchweek_year']").val();
        var month = $("[name='searchweek_month']").val();
        $("[name='searchweek_week']").html('');
        $.getJSON('index.php?act=index&op=getweekofmonth',{y:year,m:month},function(data){
            if(data != null){
                for(var i = 0; i < data.length; i++) {
                    $("[name='searchweek_week']").append('<option value="'+data[i].key+'">'+data[i].val+'</option>');
                }
            }
        });
    });

	//商品分类
	init_gcselect(<?php echo $output['gc_choose_json'];?>,<?php echo $output['gc_json']?>);
	
    $('#query_start_date').datepicker({dateFormat: 'yy-mm-dd'});
    $('#query_end_date').datepicker({dateFormat: 'yy-mm-dd'});

    $("[nc_type='showdata']").click(function(){
    	var data_str = $(this).attr('data-param');
		eval('data_str = '+data_str);
		getStatdata(data_str.gid);
    });
    //排序
    $("[nc_type='orderitem']").click(function(){
    	var data_str = $(this).attr('data-param');
	    eval( "data_str = "+data_str);
        if($(this).hasClass('desc')){
        	$("#orderby").val(data_str.orderby + ' asc');
        } else {
        	$("#orderby").val(data_str.orderby + ' desc');
        }
        $('#formSearch').submit();
    });
});
function getStatdata(gid){
	$('#goodsinfo_div').load('index.php?act=statistics_goods&op=goodsinfo&gid='+gid+'&search_type=<?php echo $output['search_arr']['search_type'] ?>&search_time=<?php echo $output['search_arr']['day']['search_time'] ?>&current_week=<?php echo $output['search_arr']['week']['current_week'] ?>&current_year=<?php echo $output['search_arr']['month']['current_year'] ?>&current_month=<?php echo $output['search_arr']['month']['current_month'] ?>&search_custom_start=<?php echo $output['search_arr']['custom']['search_custom_start'] ?>&search_custom_end=<?php echo $output['search_arr']['custom']['search_custom_end'] ?>');
}
</script>