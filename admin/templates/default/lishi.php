<?php defined('InShopNC') or exit('Access Invalid!');?>

<script type="text/javascript">
  window.onload = function(){
    window.setInterval("fun()", 5 * 60 * 1000);
  }
  function fun(){
    history.go(0);
  }
</script>

<input style="display: none" id='mem' value="<?php print_r($output['list']['mem']); ?>" />
<input style="display: none" id='cpu_use' value="<?php print_r($output['list']['cpu_use']); ?>" />
<input style="display: none" id='cpu_temp' value="<?php print_r($output['list']['cpu_temp']); ?>" />
<input style="display: none" id='io_shang' value="<?php print_r($output['list']['io_shang']); ?>" />
<input style="display: none" id='io_xia' value="<?php print_r($output['list']['io_xia']); ?>" />
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>历史统计</h3>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" action="index.php" name="formSearch" id="formSearch">
    <input type="hidden" name="act" value="stat_member" />
    <input type="hidden" name="op" value="newmember" />
    <input type="hidden" name="" value="" />
    <div class="w100pre" style="width: 100%;">
        <span class="right" style="margin:12px 0px 6px 4px;"></span>
    </div>
  </form>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th class="nobg" colspan="12"><div class="title"><h5><?php echo $lang['nc_prompts'];?></h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        <ul>
            <li>统计图展示了时间段内的走势</li>
        </ul></td>
      </tr>
    </tbody>
  </table>

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
  <script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" ></script>
  <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js"></script>
  <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script>
</div>
<script>
  //  柱状图
  var chart = new Highcharts.Chart({
    chart: {
      renderTo: 'containerchart2',
      type: 'column',
      marginRight: 130,
      marginBottom: 40
    },
    title: {
      text: '温度分布信息柱状图'
    },
    subtitle: {
      text: 'Source: 本地数据库'
    },
    xAxis: {
      min: 0,
      tickInterval: 5,
      title:{
        text: '天/小时'
      }
    },
    yAxis: {
      min: 0,
      title: {
        text: '摄氏度(℃)'
      }
    },
    tooltip: {
      headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
      pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
      '<td style="padding:0"><b>{point.y:.1f} ℃</b></td></tr>',
      footerFormat: '</table>',
      shared: true,
      useHTML: true
    },
    plotOptions: {
      column: {
        pointPadding: 0.2,
        borderWidth: 0
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
    series: [{
      name: '温度',
      data: (function(){
        var data = [], i;
        var t = document.getElementById("cpu_temp");
        var str = t.value.toString().split("=>");
        for (i = 0; i < 60; i++)
        {
          var s = str[i+1].split(" ");
          data.push({
            x: i,
            y: parseInt(s[1])
          });
        }
        return data;
      }())

    }]
  });
  //  区域图
  var chart = new Highcharts.Chart({
    chart: {
      renderTo:'containerchart3',
      type: 'area'
    },
    title: {
      text: 'I / O 吞吐率检测区域图'
    },
    subtitle: {
      text: 'Source: 本地数据库'
    },
    xAxis: {
      labels: {
        formatter: function() {
          return this.value; // clean, unformatted number for year
        }
      },
      max: 60
    },
    yAxis: {
      title: {
        text: '速度(K/秒)'
      },
      min: 0
    },
    tooltip: {
      pointFormat: '{series.name} ： <b>{point.y:,.0f}</b><br/>warheads in {point.x}'
    },
    plotOptions: {
      area: {
        marker: {
          enabled: false,
          symbol: 'circle',
          radius: 2,
          states: {
            hover: {
              enabled: true
            }
          }
        }
      }
    },
    credits: {
      enabled:false
    },
    exporting: {
      enabled:false
    },
    series: [{
      name: '上行',
      data: (function(){
        var data = [], i;
        var t = document.getElementById("io_shang");
        var arr = t.value.toString().split("=>");
        for (i = 0; i < 60; i++)
        {
          var s = arr[i+1].split(" ");
          data.push({
            x: i,
            y: parseInt(s[1])
          });
          //alert('tt = ' + tt);
        }
        return data;
      }())
    }, {
      name: '下行',
      data: (function(){
        var data = [], i;
        var t = document.getElementById("io_xia");
        var arr = t.value.toString().split("=>");
        for (i = 0; i < 60; i++)
        {
          var str = arr[i+1].split(' ');
          data.push({
            x: i,
            y: parseInt(str[1])
          });
          //alert('tt = ' + tt);
        }
        return data;
      }())
    }]
  });
// 折线图
  var chart = new Highcharts.Chart({
    chart: {
      renderTo: 'containerchart',
      type: 'line',
      marginRight: 130,
      marginBottom: 40
    },
    title: {
      text: '采集信息折线走势图',
      x: -20 //center
    },
    subtitle: {
      text: 'Source: 本地数据库',
      x: -20
    },
    xAxis: {
      title:{
        text:'时刻 (时/HOUR)'
      },
      min: 0,
      tickInterval: 5
    },
    yAxis: {
      title: {
        text: '百分比 (%)'
      },
      plotLines: [{
        value: 0,
        width: 1,
        color: '#808080'
      }],
      tickInterval: 5,
      min: 0,
      max: 100
    },
    tooltip: {
      formatter: function() {
        return '<b>'+ this.series.name +'</b><br/>'+
            this.x +'时: '+ this.y +'%';
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

    series: [{
      name: '内存占用量',
      data:(
          function(){
            var data = [], i;

            var t = document.getElementById("mem");
            //alert(t.value);
            var arr= t.value.toString().split('=>');
           // console.log(arr);
            for (i = 1; i <= 60; i++)
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
    name: 'CPU占用量',
      data:(
          function(){
            var data = [], i;
            var t = document.getElementById("cpu_use");
            //alert(t.value);
            var arr= t.value.toString().split('=>');
            for (i = 1; i <= 60; i++)
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