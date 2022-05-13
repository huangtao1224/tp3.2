<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
        body, html,#allmap {width: 100%;height: 100%;overflow: hidden;margin:0;font-family:"微软雅黑";}
    </style>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=xmzV94OpCNnSPWDBSBsOya7h6iPCzzYC"></script>
    <title>地图</title>
</head>
<body>
    <div id="allmap"></div>
</body>
</html>
<script type="text/javascript">
    // 百度地图API功能
    var map = new BMap.Map("allmap");
    var point = new BMap.Point(116.331398,39.897445);
    console.log(point);
    window.notePoint = {};
    map.centerAndZoom(point,18);
    // 创建地址解析器实例
    var myGeo = new BMap.Geocoder();
    // 将地址解析结果显示在地图上,并调整地图视野
    myGeo.getPoint("大堂区噶地利亚街星丽楼33号", function(point){
        if (point) {
            console.log(point)
            window.notePoint  = new BMap.Point(point.lng, point.lat);
            map.centerAndZoom(point, 18);
            var marker=  new BMap.Marker(point);
            map.addOverlay(marker);



                var opts = {
                  width : 0,     // 信息窗口宽度
                  height: 0,     // 信息窗口高度
                  title : '<font color="#CC5522" style="line-height: 25px;">大堂区噶地利亚街星丽楼33号</font>' , // 信息窗口标题
                  enableMessage:true,//设置允许信息窗发送短息
                  message:""
                }
                var infoWindow = new BMap.InfoWindow('<div style="font-size:12px;line-height:20px;">大堂区噶地利亚街星丽楼33号</div>', opts);  // 创建信息窗口对象 
                map.openInfoWindow(infoWindow,window.notePoint); //开启信息窗口



                marker.addEventListener("click", function(){          
                    map.openInfoWindow(infoWindow,point); //开启信息窗口
                });



        }else{
            alert("您选择地址没有解析到结果!");
        }
    }, "北京市");

    map.enableScrollWheelZoom(true);     //开启鼠标滚轮缩放



    var top_left_control = new BMap.ScaleControl({anchor: BMAP_ANCHOR_TOP_LEFT});// 左上角，添加比例尺
    var top_left_navigation = new BMap.NavigationControl();  //左上角，添加默认缩放平移控件
    var top_right_navigation = new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}); //右上角


    map.addControl(top_left_control);        
    map.addControl(top_left_navigation);     
    map.addControl(top_right_navigation); 


    // var overView = new BMap.OverviewMapControl();
    // var overViewOpen = new BMap.OverviewMapControl({isOpen:true, anchor: BMAP_ANCHOR_BOTTOM_RIGHT}); 
    // map.addControl(overView);   //添加默认缩略地图控件
    // map.addControl(overViewOpen);      //右下角，打开</script>
