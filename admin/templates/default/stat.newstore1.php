<?php defined('InShopNC') or exit('Access Invalid!');?>


<input style="display: none" id='warning_cpu_alerted' value="<?php print_r($output['l']['warning_cpu_alerted']); ?>" />
<input style="display: none" id='warning_cpu_alarm' value="<?php print_r($output['l']['warning_cpu_alarm']); ?>" />
<input style="display: none" id='warning_mem_alerted' value="<?php print_r($output['l']['warning_mem_alerted']); ?>" />
<input style="display: none" id='warning_mem_alarm' value="<?php print_r($output['l']['warning_mem_alarm']); ?>" />
<input style="display: none" id='warning_tem_alerted' value="<?php print_r($output['l']['warning_tem_alerted']); ?>" />
<input style="display: none" id='warning_tem_alarm' value="<?php print_r($output['l']['warning_tem_alarm']); ?>" />
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
  <div id="containerchart" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
  <hr style="filter: alpha(opacity=100, finishopacity=0, style=3)" width="80%" color=#987cb9 SIZE=3 />
  <div id="containerchart2" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
  <hr style="filter: alpha(opacity=100, finishopacity=0, style=3)" width="80%" color=#987cb9 SIZE=3 />
  <div id="containerchart3" style="min-width: 400px; height: 400px; margin: 0 auto"></div>

  <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
  <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
  <link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
  <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/highcharts/highcharts.js"></script>
  <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/statistics.js"></script>
  <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script>
  <script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" ></script>
  <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js"></script>
  <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script>
</div>
<script>
  // 折线图
  var chart = new Highcharts.Chart({
    chart: {
      renderTo: 'containerchart',
      type: 'line',
      marginRight: 130,
      marginBottom: 40
    },
    title: {
      text: 'CPU报警预警折线走势图',
      x: -20 //center
    },
    subtitle: {
      text: 'Source: 本地数据库',
      x: -20
    },
    xAxis: {
      title:{
        text:'时刻 (天/DAY)'
      },
      min: 0,
      tickInterval: 1,
      max: 7
    },
    yAxis: {
      title: {
        text: '次数/次'
      },
      plotLines: [{
        value: 0,
        width: 1,
        color: '#808080'
      }],
      tickInterval: 10,
      min: 0,
    },
    tooltip: {
      formatter: function() {
        return '<b>'+ this.series.name +'</b><br/>'+
            this.x +'天: '+ this.y +'次';
      }
    },
    legend: {
      layout: 'vertical',
      align: 'right',
      verticalAlign: 'top',
      x: -10,
      y: 100,
      borderWidth: 0
    },
    credits: {
      enabled:false
    },
    exporting: {
      enabled:false
    },

    series: [
      {
      name: 'CPU预警',
      data: (
          function(){
            var data = [], i;

            var t = document.getElementById("warning_cpu_alerted");
            //alert(t.value);
            var arr= t.value.toString().split('=>');
            // console.log(arr);
            for (i = 1; i <= 7; i++)
            {
              var temp_arr=arr[i].split(' ');
              data.push({
                x: i-1,
                y: parseInt(temp_arr[1])
              });
              //alert('tt = ' + tt);
            }
            return data;
          }())
    },{
      name: 'CPU报警',
      data:(
          function(){
            var data = [], i;
            var t = document.getElementById("warning_cpu_alarm");
            //alert(t.value);
            var arr= t.value.toString().split('=>');
            for (i = 1; i <= 7; i++)
            {
              var temp_arr=arr[i].split(' ');
              data.push({
                x: i-1,
                y: parseInt(temp_arr[1])
              });
              //alert('tt = ' + tt);
            }
            return data;
          }())
    }]
  });
  var chart = new Highcharts.Chart({
    chart: {
      renderTo: 'containerchart2',
      type: 'line',
      marginRight: 130,
      marginBottom: 40
    },
    title: {
      text: '内存报警预警折线走势图',
      x: -20 //center
    },
    subtitle: {
      text: 'Source: 本地数据库',
      x: -20
    },
    xAxis: {
      title:{
        text:'时刻 (天/DAY)'
      },
      min: 0,
      tickInterval: 1,
      max: 7
    },
    yAxis: {
      title: {
        text: '次数/次'
      },
      plotLines: [{
        value: 0,
        width: 1,
        color: '#808080'
      }],
      tickInterval: 20,
      min: 0,
    },
    tooltip: {
      formatter: function() {
        return '<b>'+ this.series.name +'</b><br/>'+
            this.x +'天: '+ this.y +'次';
      }
    },
    legend: {
      layout: 'vertical',
      align: 'right',
      verticalAlign: 'top',
      x: -10,
      y: 100,
      borderWidth: 0
    },
    credits: {
      enabled:false
    },
    exporting: {
      enabled:false
    },

    series: [
      {
        name: '内存预警',
        data: (
       function(){
       var data = [], i;

       var t = document.getElementById("warning_mem_alerted");
       //alert(t.value);
       var arr= t.value.toString().split('=>');
       // console.log(arr);
       for (i = 1; i <= 7; i++)
       {
       var temp_arr=arr[i].split(' ');
       data.push({
       x: i-1,
       y: parseInt(temp_arr[1])
       });
       //alert('tt = ' + tt);
       }
       return data;
       }())
       },{
       name: '内存报警',
       data:(
       function(){
       var data = [], i;
       var t = document.getElementById("warning_mem_alarm");
       //alert(t.value);
       var arr= t.value.toString().split('=>');
       for (i = 1; i <= 7; i++)
       {
       var temp_arr=arr[i].split(' ');
       data.push({
       x: i-1,
       y: parseInt(temp_arr[1])
       });
       //alert('tt = ' + tt);
       }
       return data;
       }())
      }]
  });
  var chart = new Highcharts.Chart({
    chart: {
      renderTo: 'containerchart3',
      type: 'line',
      marginRight: 130,
      marginBottom: 40
    },
    title: {
      text: '温度报警预警折线走势图',
      x: -20 //center
    },
    subtitle: {
      text: 'Source: 本地数据库',
      x: -20
    },
    xAxis: {
      title:{
        text:'时刻 (天/DAY)'
      },
      min: 0,
      tickInterval: 1,
      max: 7
    },
    yAxis: {
      title: {
        text: '次数/次'
      },
      plotLines: [{
        value: 0,
        width: 1,
        color: '#808080'
      }],
      tickInterval: 20,
      min: 0,
    },
    tooltip: {
      formatter: function() {
        return '<b>'+ this.series.name +'</b><br/>'+
            this.x +'天: '+ this.y +'次';
      }
    },
    legend: {
      layout: 'vertical',
      align: 'right',
      verticalAlign: 'top',
      x: -10,
      y: 100,
      borderWidth: 0
    },
    credits: {
      enabled:false
    },
    exporting: {
      enabled:false
    },

    series: [
      {
        name: '温度报警',
        data: (
       function(){
       var data = [], i;

       var t = document.getElementById("warning_tem_alerted");
       //alert(t.value);
       var arr= t.value.toString().split('=>');
       // console.log(arr);
       for (i = 1; i <= 7; i++)
       {
       var temp_arr=arr[i].split(' ');
       data.push({
       x: i-1,
       y: parseInt(temp_arr[1])
       });
       //alert('tt = ' + tt);
       }
       return data;
       }())
       },{
       name: '温度预警',
       data:(
       function(){
       var data = [], i;
       var t = document.getElementById("warning_tem_alarm");
       //alert(t.value);
       var arr= t.value.toString().split('=>');
       for (i = 1; i <= 7; i++)
       {
       var temp_arr=arr[i].split(' ');
       data.push({
       x: i-1,
       y: parseInt(temp_arr[1])
       });
       //alert('tt = ' + tt);
       }
       return data;
       }())
      }]
  });
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