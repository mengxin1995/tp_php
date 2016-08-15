<?php defined('InShopNC') or exit('Access Invalid!');?>

<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=QNsr5LRYEblihnhmIEcrluII"></script>
<script type="text/javascript" src="http://developer.baidu.com/map/jsdemo/demo/convertor.js"></script>
<div class="wrap">
    <div class="tabmenu">
      <?php include template('layout/submenu');?>
  </div>
    <table class="ncm-default-table">
      <thead>
    <tr>
          <th class="w10"></th>
          <th colspan="2"></th>
          <th class="w240"><?php echo $lang['logic_page_title'];?></th>
          <th class="w120"></th>
          <th class="w120"></th>
          <th class="w110"></th>
      </tr>
    </thead>
      <tbody>
    <tr class="bd-line">
          <td colspan="20" class="norecord">
              <div id="allmap" style="height: 400px;width:100%;"></div>
        </td>
      </tr>
    </tbody>

  </table>
</div>

<script type="text/javascript">
    // 百度地图API功能
    var map = new BMap.Map("allmap", {enableMapClick:false});
    var point = new BMap.Point(<?php echo $output['info']['x'];?>, <?php echo $output['info']['y'];?>);
    map.centerAndZoom(point, 15);

    var marker = new BMap.Marker(point);

    //坐标转换完之后的回调函数
    translateCallback = function (point){
        marker = new BMap.Marker(point);
        map.addOverlay(marker);
        marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
        map.setCenter(point);
        map.setZoom(19);

        var infoWindow = new BMap.InfoWindow("<?php echo $output['info']['content'];?>", opts);  // 创建信息窗口对象
        marker.addEventListener("click", function(){
            map.openInfoWindow(infoWindow,point); //开启信息窗口
        });
    }

    //setTimeout(function(){
        BMap.Convertor.translate(point,0,translateCallback);     //真实经纬度转成百度坐标
    //}, 1000);

//    setTimeout(function(){
//        map.setZoom(19);
//    }, 2000);  //2秒后放大到19级

//    var marker = new BMap.Marker(point);  // 创建标注
//    map.addOverlay(marker);               // 将标注添加到地图中
//    marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画

    var top_left_control = new BMap.ScaleControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT});// 左上角，添加比例尺
    //var top_left_navigation = new BMap.NavigationControl();  //左上角，添加默认缩放平移控件
    var top_right_navigation = new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}); //右上角，仅包含平移和缩放按钮

    map.addControl(top_left_control);
    //map.addControl(top_left_navigation);
    map.addControl(top_right_navigation);


    //信息窗口
    var opts = {
        width : 200,     // 信息窗口宽度
        height: 100,     // 信息窗口高度
        title : "<?php echo $output['info']['title'];?>"  // 信息窗口标题
    }


        setTimeout(function(){
            location.reload();
        }, 10000);  //10秒钟刷新页面
</script>