var loc;
var amap;
var loaded = false;
var site = '卓刀泉古玩珠宝城';
var layer;
var infoWin;

function geocoder() {

    var _onGeoGetLocation = function(e){
        loc = e.geocodes[0].location;
        amap = new AMap.Map('container', {
            resizeEnable: true,
            zoom: 15,
            center: e.geocodes[0].location,
        });

        amap.plugin(["AMap.ToolBar"],function(){
            //加载工具条
            var tool = new AMap.ToolBar();
            amap.addControl(tool);
        });

        var marker = new AMap.Marker({
            position: e.geocodes[0].location,
            title: site,
            zIndex:9999,
            clickable:true,
            bubble:true,
        });
        marker.setMap(amap);
    };


    var geocoder = new AMap.Geocoder({
        city: "武汉", //城市，默认：“全国”
        radius: 500 //范围，默认：500
    });

    var clickListener = AMap.event.addListener(geocoder, "complete", _onGeoGetLocation); //绑定事件，返回监听对象


    geocoder.getLocation(site, function(status, result) {
        if (status === 'complete' && result.info === 'OK') {
            //console.log(result.geocodes[0].location.getLng()+','+result.geocodes[0].location.getLat());
            //loc = result.geocodes[0].location;

            //return result.geocodes[0].location;//[result.geocodes[0].location.getLng(),result.geocodes[0].location.getLat()];
        }
    });
}

function createVisualMap(data) {
    if (loaded) {
        layer.destroy();
    }
    loaded = true;

    var map = Loca.create(amap);
    layer = Loca.visualLayer({
        eventSupport:true,
        container: map,
        type: 'point',
        shape: 'circle'
    });
    layer.setData(data, {
        lnglat: 'center'
    });
    layer.setOptions({
        style: {
            radius: 10,
            fill: 'rgba(198, 26, 0, 0.8)',
            lineWidth: 1,
            stroke: '#eee'
        }
    });

    layer.on('click', function(event) {
        if (!infoWin) {
            infoWin = new AMap.InfoWindow();
        }

        var type = event.type;
        // 当前元素的原始数据
        var rawData = event.rawData;
        // 原始鼠标事件
        var originalEvent = event.originalEvent;
        var lnglat = event.lnglat;
        console.log('事件类型 ' + type);
        console.log('原始数据 ' + JSON.stringify(rawData));
        console.log('鼠标事件 ' + originalEvent);

        infoWin.setContent(rawData.name + '<br/>' + rawData.center);
        infoWin.open(map.getMap(), new AMap.LngLat(lnglat[0], lnglat[1]));
    });

    //layer.setZIndex(10);
    layer.render();

}




$(function () {
    $(".pushdata").on("click",function(e){
        if ($("#two").val()!=""){
            $.ajax({
                type: "POST",
                url: window.location.protocol+'//'+window.location.host + '/index.php/index/index/get',
                data: {
                    "two" : $("#two").val(),
                },
                dataType: "json",
                success: function(data){
                    if (data.response == "success") {
                        console.log(data.data);
                        createVisualMap(data.data);
                    }else{

                    }
                },
                error: function(jqXHR, textStatus){
                    alert(jqXHR.status+':'+jqXHR.statusText);
                },
                complete:function(){

                }
            });

        }

    });

    geocoder();





});





/* //地理编码返回结果展示
 function geocoder_CallBack(data) {
     var resultStr = "";
     //地理编码结果数组
     var geocode = data.geocodes;
     for (var i = 0; i < geocode.length; i++) {
         //拼接输出html
         resultStr +=  geocode[i].location.getLng() + ", " + geocode[i].location.getLat() + "" + "<b>&nbsp;&nbsp;&nbsp;&nbsp;匹配级别</b>：" + geocode[i].level + "</span>";
         addMarker(i, geocode[i]);
     }
     map.setFitView();
     document.getElementById("result").innerHTML = resultStr;
 }*/



//console.log(loc);




