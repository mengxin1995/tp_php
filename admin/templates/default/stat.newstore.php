<?php defined('InShopNC') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>预警报警统计</h3>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="fixed-empty"></div>
  
  <form method="get" action="index.php" name="formSearch" id="formSearch">
    <input type="hidden" name="act" value="stat_store" />
    <input type="hidden" name="op" value="newstore" />
    <div class="w100pre" style="width: 100%;">
        <span class="right" style="margin:12px 0px 6px 4px;"></span>
    </div>
  </form>

  <table class="table tb-type2">
    <thead>
    <tr class="sortbar-array">
      <th>IP</th>
      <th>用户名</th>
      <th>计算机名</th>
      <th>CPU预警数</th>
      <th>CPU报警数</th>
      <th>内存预警数</th>
      <th>内存报警数</th>
      <th>温度预警数</th>
      <th>温度报警数</th>
      <th>总预警数</th>
      <th>总报警数</th>
      <th class="w120"><?php echo $lang['nc_handle'];?></th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($output['list']) && is_array($output['list'])) { ?>
      <?php foreach($output['list'] as $v) { ?>
        <tr class="bd-line">
          <td><?php echo $v['com_ip'];?></td>
          <td><?php echo $v['warning_user']?></td>
          <td><?php echo $v['warning_computer_user'];?></td>
          <td><?php echo $v['warning_cpu_alerted'];?></td>
          <td><?php echo $v['warning_cpu_alarm'];?></td>
          <td><?php echo $v['warning_mem_alerted'];?></td>
          <td><?php echo $v['warning_mem_alarm'];?></td>
          <td><?php echo $v['warning_tem_alerted'];?></td>
          <td><?php echo $v['warning_tem_alarm'];?></td>
          <td><?php echo $v['warning_cpu_alerted'] + $v['warning_mem_alerted'] + $v['warning_tem_alerted'];?></td>
          <td><?php echo $v['warning_cpu_alarm'] + $v['warning_mem_alarm'] + $v['warning_tem_alarm'];?></td>
          <td><a href="index.php?act=stat_store&op=newstore1&com_ip=<?php echo $v['com_ip'];?>">走势图</a></td>
        </tr>
      <?php }?>
    <?php } else { ?>
        <!--未查询到计算机提示-->
      <tr>
        <td colspan="20" class="norecord" style="text-align: center;"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
    <?php } ?>
    </tbody>
    <?php if (!empty($output['goodslist']) && is_array($output['goodslist'])) { ?>
        <!--如果有多页翻页-->
      <tfoot>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
      </tr>
      </tfoot>
    <?php } ?>
  </table>
  <!--操作生成的图表-->

  <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
  <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
  <link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
  <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/highcharts/highcharts.js"></script>
  <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/statistics.js"></script>
  <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script>
  <script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" ></script>
  <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js"></script>
</div>
<script>
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

	$('#ncsubmit').click(function(){
    	$('#formSearch').submit();
    });
});
</script>