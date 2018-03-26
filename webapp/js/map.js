var map_user,map_path,map_path1,baseLayer,pointLayer,layer;
var small_mode = 180000;
var travel_modes = [];
var travel_mode_names = {
  0:'نامشخص',
  1:'پیاده',
  2:'دوچرخه',
  3:'خودرو',
  4:'اتوبوس',
  5:'مترو',
  6:'ایست',
  7:'موتورسیکلت',
  8:'تاکسی',
  9:'وانت بار'

};
var stops_names = {
  0:'نامشخص',
  1:'کارشخصی',
  2:'شغلی',
  3:'خرید',
  4:'تحصیلی',
  5:'زیارت و مذهبی',
  6:'تفریح',
  7:'غیره'
};
var travel_mode_answers = {
  0:'',
  1:'کمتر از پنج دقیقه',
  2:'بین پنج دقیقه تا ده دقیقه ',
  3:'بین ده دقیقه تا یک ربع ',
  4:'بیش از یک ربع '
}
var travel_mode_rahatis = {
  0:'',
  1:'خیلی کم',
  2:'کم',
  3:'بین ده دقیقه تا یک ربع ',
  4:'بیش از یک ربع '
}
var mode_icon = {
  1:'male',
  2:'bicycle',
  3:'car',
  4:'bus',
  5:'subway',
  7:'motorcycle'
}
var mode_color = {
  1: '#0000ff', 
  2: '#ff0000',
  3: '#00ff00',
  4: '#068888',
  5: '#000000'
};
var feild = 'address';
function convToGood(lon,lat){
  var toProjection = new OpenLayers.Projection("EPSG:4326");
  var fromProjection   = new OpenLayers.Projection("EPSG:900913");
  var position       = new OpenLayers.LonLat(lon, lat).transform( fromProjection, toProjection);
  return position;
}
function goodToBad(lon,lat){
  var fromProjection = new OpenLayers.Projection("EPSG:4326");
  var toProjection   = new OpenLayers.Projection("EPSG:900913");
  var position       = new OpenLayers.LonLat(lon, lat).transform( fromProjection, toProjection);
  return position;  
}
function clearPoints(){
  pointLayer.removeAllFeatures();
}
function clearPath(){
  layer.removeAllFeatures();
  // layer.destroyFeatures();
}
function clearPath1(){
  layer.removeAllFeatures();
  // layer.destroyFeatures();
}
function beforeFeatureAdded(){
  pointLayer.removeAllFeatures();
}
function onFeatureAdded(a){
  pointLayer.removeAllFeatures();
  var st_point = new OpenLayers.Geometry.Point(a.geometry.x ,a.geometry.y);
  var ftch = new OpenLayers.Feature.Vector(st_point);
  pointLayer.addFeatures([ftch]);
  var out = convToGood(a.geometry.x,a.geometry.y);
  // alert(out.lon+','+out.lat);
  $("#"+feild).val(out.lon+','+out.lat);
}
function initMap(){
  id = "demoMap";
  map_user = new OpenLayers.Map(id);
  var fromProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
  var toProjection   = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
  navigator.geolocation.getCurrentPosition(function(p){
    var center       = new OpenLayers.LonLat(p.coords.longitude,p.coords.latitude).transform( fromProjection, toProjection);
    var zoom           = 18; 
    // map.addLayer(new OpenLayers.Layer.OSM());
    baseLayer = new OpenLayers.Layer.OSM();
    pointLayer = new OpenLayers.Layer.Vector("Point Layer");
    layer = new OpenLayers.Layer.Vector("Path Layer");
    map_user.addLayers([baseLayer,pointLayer]);
    // map.addControl(new OpenLayers.Control.MousePosition());
    var control = new OpenLayers.Control.DrawFeature(pointLayer,OpenLayers.Handler.Point,{featureAdded: onFeatureAdded});
    // control.activate();
    // map.zoomToMaxExtent();
    map_user.addControl(control);
    control.activate();
    map_user.setCenter(center, zoom );
    
  }, function(e){
    var center       = new OpenLayers.LonLat(59.54019928931992,36.32471101369044).transform( fromProjection, toProjection);
    var zoom           = 18; 
    // map.addLayer(new OpenLayers.Layer.OSM());
    baseLayer = new OpenLayers.Layer.OSM();
    pointLayer = new OpenLayers.Layer.Vector("Point Layer");
    layer = new OpenLayers.Layer.Vector("Path Layer");
    map_user.addLayers([baseLayer,pointLayer]);
    // map.addControl(new OpenLayers.Control.MousePosition());
    var control = new OpenLayers.Control.DrawFeature(pointLayer,OpenLayers.Handler.Point,{featureAdded: onFeatureAdded});
    // control.activate();
    // map.zoomToMaxExtent();
    map_user.addControl(control);
    control.activate();
    map_user.setCenter(center, zoom );
  }, { maximumAge: 3000, timeout: 5000, enableHighAccuracy: true });
}
function initMapPath(){
  // alert('init');
  id = "archiveMap";
  map_path = new OpenLayers.Map(id);
  var fromProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
  var toProjection   = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
  var center       = new OpenLayers.LonLat(59.54019928931992,36.32471101369044).transform( fromProjection, toProjection);
  var zoom           = 18; 
  // map.addLayer(new OpenLayers.Layer.OSM());
  baseLayer = new OpenLayers.Layer.OSM();
  pointLayer = new OpenLayers.Layer.Vector("Point Layer");
  layer = new OpenLayers.Layer.Vector("Path Layer");
  map_path.addLayers([baseLayer,layer]);
  map_path.setCenter(center, zoom );
}
function initMapPath1(){
  id = "archiveMap1";
  map_path1 = new OpenLayers.Map(id);
  var fromProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
  var toProjection   = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
  var center       = new OpenLayers.LonLat(59.54019928931992,36.32471101369044).transform( fromProjection, toProjection);
  var zoom           = 18; 
  // map.addLayer(new OpenLayers.Layer.OSM());
  baseLayer = new OpenLayers.Layer.OSM();
  pointLayer = new OpenLayers.Layer.Vector("Point Layer");
  layer = new OpenLayers.Layer.Vector("Path Layer");
  map_path1.addLayers([baseLayer,layer]);
  map_path1.setCenter(center, zoom );
}
function selectFeild(inFeild){
  feild = inFeild;
  if(typeof map_user === 'undefined'){
    initMap();
  }else{
    clearPoints();
  }
}
function loadTrack(dt,i,pdt){
  $(".t").css('background','none');
  $("#t_"+i).css('background','#eaeaea');
  showModal('m1',pdt);
  getStops(user_id,dt,function(sts){
    viewPath(dt);
  });
}
function loadTrackAll(dt,i,pdt){
  $(".tall").css('background','none');
  $("#tall_"+i).css('background','#eaeaea');
  showModal('m1',pdt);
  // viewPath1(dt);
  getStops(user_id,dt,function(sts){
    viewPath1(dt);
  });
}
function showDates(is_all){
  if(is_all===true){
    $("#dates").html('');
    getDatesAll(user_id,function(gdates){
//       console.log(gdates);
      $("#dates").append('<ul class="list">');
      for(var i = 0;i < gdates.length;i++){
        $("#dates").append('<li class="list-item">');
        // $("#dates").append("<div class=\"list-item__center tall\" id=\"tall_"+i+"\" ><a href=\"javascript:loadTrackAll('"+gdates[i].dt+"',"+i+",'"+gdates[i].pdt+"');\">"+gdates[i].pdt+"</a></div>");
        $("#dates").append("<div class=\"tall\" id=\"tall_"+i+"\" style=\"text-align:center;\" ><table onclick=\"javascript:loadTrackAll('"+gdates[i].dt+"',"+i+",'"+gdates[i].pdt.replace('|',' ')+"');\" style=\"width:100%\"><tr><td style=\"width:50%;text-align:left\">"+gdates[i].pdt.split('|')[0]+"</td><td style=\"width:50%;text-align:right\">"+gdates[i].pdt.split('|')[1]+"</td></tr></table></div>");
        $("#dates").append('</li>');
      }
      $("#dates").append('</ul>');
    });
  }else{
    $("#dates").html('');
    getDates(user_id,function(gdates){
//       console.log(gdates);
      $("#dates").append('<ul class="list">');
      for(var i = 0;i < gdates.length;i++){
        $("#dates").append('<li class="list-item">');
        // $("#dates").append("<div class=\"t\" id=\"t_"+i+"\" style=\"text-align:center;\" ><a href=\"javascript:loadTrack('"+gdates[i].dt+"',"+i+",'"+gdates[i].pdt+"');\" style=\"text-align:center\">"+gdates[i].pdt.split(' ')[1]+"</a></div>");
        $("#dates").append("<div class=\"t\" id=\"t_"+i+"\" style=\"text-align:center;\" ><table onclick=\"javascript:loadTrack('"+gdates[i].dt+"',"+i+",'"+gdates[i].pdt.replace('|',' ')+"');\" style=\"width:100%\"><tr><td style=\"width:50%;text-align:left\">"+gdates[i].pdt.split('|')[0]+"</td><td style=\"width:50%;text-align:right\">"+gdates[i].pdt.split('|')[1]+"</td></tr></table></div>");
        $("#dates").append('</li>');
      }
      $("#dates").append('</ul>');
    });
  }
}
function getTime(inp){
  var out = '';
  if(inp.split(' ').length==2){
    out = inp.split(' ')[1];
  }
  return out;
}
function createLines(inp){
  
}
function drawLine(points){
//   console.log('findGaps');
//   console.log('from');
//   console.log(points);
//   console.log('to');
//   var gaps = findGaps(points);
//   console.log(gaps);
//   alert('draw');
  travel_modes = [];
  var opoints = [];
  var lines = {};
  var last_mode = 0;
  var tmode = {};
  var line,tmp_lmode,tmp_cmode,dis=0;
  var last_tmode = {mode:-1};
  for(var i = 0;i < points.length;i++){
    tmp_lonlat = goodToBad(points[i].lon, points[i].lat);
    var point = new OpenLayers.Geometry.Point(tmp_lonlat.lon ,tmp_lonlat.lat);
    if(typeof tmode.mode=='undefined'){
      tmode = {
        mode : points[i].mode,
        start : getTime(points[i].regtime),
        start_date : points[i].regtime,
        start_id : points[i].id,
        end : '',
        end_date : '',
        end_id : -1,
        umode : 0,
        answer : 0,
        rahati : 0,
        mobile_app :0
        
      };
    }
    /*
    if(points[i+1]){
      dis = getDistanceFromLatLonInKm(points[i+1].lat,points[i+1].lon,points[i].lat,points[i].lon);
      if(dis>50){
//         console.log('p1:',points[i]);
//         console.log('p2:',points[i+1]);
//         console.log('p2-p1:',dis);
//         line = new OpenLayers.Geometry.LineString(opoints);
//         if(typeof lines[points[i].mode]== 'undefined'){
//           lines[points[i].mode]=[];
//         }
//         lines[points[i].mode].push(line);
//         opoints=[];
      }else{
        opoints.push(point);
      }
    }else{
      opoints.push(point);
    }
    */
    opoints.push(point);
    if(points[i].mode!=last_mode && last_mode>0 && last_mode!==6 && points[i-1].id>tmode.start_id && last_mode!=11 && points[i].mode!=11){
      if(travel_modes.length>0)
        last_tmode = travel_modes[travel_modes.length-1];
//       console.log('new mode');
//       console.log('last mode',last_mode);
//       console.log('new mode',points[i].mode);
      var d1 = new Date(String(tmode.start_date).replace(' ','T'));
      // alert(tmode.start_date);
      // alert(d1);
      d1 = d1.getTime();
      var d2 = new Date(String(points[i-1].regtime).replace(' ','T'));
      d2 = d2.getTime();
      // alert('mode = '+last_mode+"\n"+'time = '+(d2-d1));
      if(d2-d1>small_mode){
        tmode.end = getTime(points[i-1].regtime);     
        tmode.end_id = points[i-1].id;
        tmode.end_date = points[i-1].regtime;
//         console.log('last mode',last_tmode);
//         console.log('current mode',tmode);
        
        tmp_lmode = parseInt(last_tmode.mode,10);
        
        tmp_cmode = parseInt(tmode.mode,10);
        
        if(tmp_cmode === tmp_lmode && travel_modes.length>0){
//           console.log('found double');
          tmode.start = last_tmode.start;
          tmode.start_date = last_tmode.start_date;
          tmode.start_id = last_tmode.start_id;
          travel_modes.splice(travel_modes.length-1,1);
        }
        
        travel_modes.push(tmode);
        tmode = {};
        console.log('opoints :',opoints);
        line = new OpenLayers.Geometry.LineString(opoints);
        if(typeof lines[last_mode]== 'undefined'){
          lines[last_mode]=[];
        }
        lines[last_mode].push(line);
        opoints=[];
      }
    }
    last_mode = points[i].mode;
  }
  // alert(travel_modes.length);
  console.log(lines);
  line = new OpenLayers.Geometry.LineString(opoints);
  if(typeof lines[points[i-1].mode]== 'undefined'){
    lines[points[i-1].mode]=[points[i-1].mode];
    tmode.end = getTime(points[i-1].regtime);
    travel_modes.push(tmode);
  }
  lines[points[i-1].mode].push(line);
  var style = { 1 :{ 
                      strokeColor: '#0000ff', 
                      strokeOpacity: 0.9,
                      strokeWidth: 5
                    },
               2 : { 
                      strokeColor: '#ff0000', 
                      strokeOpacity: 0.9,
                      strokeWidth: 5
                    },
               3 : { 
                      strokeColor: '#00ff00', 
                      strokeOpacity: 0.9,
                      strokeWidth: 5
                    },
               4: { 
                      strokeColor: '#068888', 
                      strokeOpacity: 0.9,
                      strokeWidth: 5
                    },
               5: { 
                      strokeColor: '#000000', 
                      strokeOpacity: 0.9,
                      strokeWidth: 5
                    },
               6: { 
                      strokeColor: '#ff00ff', 
                      strokeOpacity: 0.9,
                      strokeWidth: 5
                    }
              };
  for(i in lines){
    var stylee = style[i];
    for(var j=0;j<lines[i].length;j++){
      var line = lines[i][j];
      var feature = new OpenLayers.Feature.Vector(line, null, stylee);
      layer.addFeatures([feature]);
    }
  }
//   alert('0darwing');
  
}
function moveMap1(lon,lat){
  var fromProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
  var toProjection   = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
  var center       = new OpenLayers.LonLat(lon,lat).transform( fromProjection, toProjection);
  var zoom           = 11; 
  map_path1.setCenter(center, zoom );
}
function moveMap(lon,lat){
  var fromProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
  var toProjection   = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
  var center       = new OpenLayers.LonLat(lon,lat).transform( fromProjection, toProjection);
  var zoom           = 11; 
  map_path.setCenter(center, zoom );
}
function setStop(i){
  var ftch = stops[i]['feature'];
  layer.addFeatures([ftch]);
}
function viewPath(dt){
  if(typeof map_path === 'undefined'){
    initMapPath();
  }else{
    clearPath();
  }
  $(".loading").show();
  getPath(user_id,dt,function(data){
    $(".loading").hide();
    $("#travel_mode").html("");
    if(data.length>0){
      // alert(1);
      try{
        drawLine(data);
        // alert('dd');
      }catch(e){
        // alert(e.message);
      }
      $("#m1_loading").hide();
      // alert('11');
      if(data[0]){
        // alert(2);
        try{
          moveMap(data[0].lon,data[0].lat);
        }catch(e){
          // alert(e.message);
        }
        // $("#travel_mode").html("<ons-list><ons-list-header><div class=\"right\">هدف سفر</div></ons-list-header>");
        if(stops.length>0)
          $("#travel_mode").html("<div style='text-align:right;padding:5pt;background:#eaeaea;border:1pt solid #fff;'>هدف سفر</div>");
        var sel = '';
        // alert(travel_modes.length);
        // for(var i in stops[0]){
        //   alert(i+'->'+stops[0][i]);
        // }
        
        for(var i = 0;i<stops.length;i++){
          tmp_lonlat = goodToBad(stops[i].lon, stops[i].lat);
          var st_point = new OpenLayers.Geometry.Point(tmp_lonlat.lon ,tmp_lonlat.lat);
          var ftch = new OpenLayers.Feature.Vector(st_point);
          stops[i]['feature'] = ftch;
          // layer.addFeatures([ftch]);
          // if(even)
          //   $("#travel_mode").append("<li class=\"list-item\">");
          // else
          // $("#travel_mode").append("<ons-list-item>");
          $("#travel_mode").append("<div>");
          $("#travel_mode").append('<a href="javascript:setStop('+i+')" >'+stops[i].regdate.split(' ')[1]+'</a><br/>');
          sel = '<select class="stops" id="stop_'+i+'" >';
          for(var j = 0;j<8;j++){
            sel += '<option value="'+j+'" >';
            sel += stops_names[j];
            sel += '</option>';
          }
          sel += '</select>';
          $("#travel_mode").append(sel);
          // $("#travel_mode").append("</ons-list-item>");
          $("#travel_mode").append("</div>");
        }
        // $("#travel_mode").append("</ons-list><br/>");
        $("#travel_mode").append("<div style='text-align:right;padding:5pt;background:#eaeaea;border:1pt solid #fff;'>وسیله سفر</div>");
        var sel = '';
        // alert(travel_modes.length);
        even = false;
        for(var i = 0;i<travel_modes.length;i++){
          // if(even===true)
          //   $("#travel_mode").append("<ons-list-header>");
          // else
          // if(travel_modes[i].mode!=6){
          if(travel_mode_names[travel_modes[i].mode] && travel_modes[i].mode!=6 && travel_modes[i].mode>0){
//           if(true){
            $("#travel_mode").append("<hr/><div>");
            $("#travel_mode").append(travel_modes[i].start.split(':')[0]+':'+travel_modes[i].start.split(':')[1]+' '+travel_modes[i].end.split(':')[0]+':'+travel_modes[i].end.split(':')[1]+'<br/>');
            sel = '<div style="text-align:center"><table style="width:100%"><tr>';
            sel += ((mode_icon[travel_modes[i].mode])?'<td style="text-align:right;width:50%"><ons-icon id="mode_icon_'+i+'" icon="fa-'+mode_icon[travel_modes[i].mode]+'" ></ons-icon></td><td style="text-align:left">':'<td style="text-align:center">')+'<select class="modes" id="mode_'+i+'" onchange="mode_change(this);">';
            for(var j = 0;j<10;j++){
              if(j!=6){
                sel += '<option value="'+j+'"'+((j==travel_modes[i].mode)?' selected':'')+' >';
                sel += travel_mode_names[j];
                sel += '</option>';
              }
            }
            sel += '</select></td></tr></table></div>';
            sel += '<div id="answer-div-'+i+'" style="text-align:right">';
            sel += '<p id="question'+i+'">'+'زمان  انتظار برای رسیدن وسیله حمل و نقل عمومی  چقدر بود؟'+'</p>';
            sel+= '<select class="modes_answer" id="mode_answer_'+i+'">';
            for(var j = 0;j<=4;j++){
              sel += '<option value="'+j+'" >';
              sel += travel_mode_answers[j];
              sel += '</option>';
            }
            sel += '</select></div>';
            sel += '<div id="rahati-div-'+i+'" style="text-align:right">';
            sel += '<p>'+'میزان راحتی شما در وسیله حمل و نقل عمومی جه میزان است ؟'+'</p>';
            sel+= '<select class="modes_rahati" id="mode_rahati_'+i+'">';
            for(var j = 0;j<=5;j++){
              sel += '<option value="'+j+'" >';
              sel += travel_mode_rahatis[j];
              sel += '</option>';
            }
            sel += '</select></div>'
            $("#travel_mode").append(sel);
            // if(even===true)
            //   $("#travel_mode").append("</ons-list-header>");
            // else
            $("#travel_mode").append("</div>");
            even = (even===true)?false:true;
          }
        }
        // $("#travel_mode").append("</ons-list>");
        $("#travel_mode").append("<div style=\"text-align:right\">");
        $("#travel_mode").append("آیا در طول سفرهای این روز از موبایل اپلیکیشن ها  برای مسیریابی و یا گرفتن اطلاعات وسایل حمل و نقل عمومی استفاده کرده اید ؟");
        $("#travel_mode").append("</div>");
        $("#travel_mode").append("<div style=\"direction:ltr\">");
        $("#travel_mode").append("خیر");
        $("#travel_mode").append("<input type=\"radio\" name=\"mapp\" value=\"0\" id=\"mapp0\" checked>");
        $("#travel_mode").append("<br/>");
        $("#travel_mode").append("بله");
        $("#travel_mode").append("<input type=\"radio\" name=\"mapp\" value=\"1\" id=\"mapp1\">");
        // $("#travel_mode").append("</option>");
        $("#travel_mode").append("</div>");
        $("#travel_mode").append("<p style=\"text-align: center;\"><ons-button onclick=\"saveModes();\">تایید</ons-button></p>");
        $(".modes").each(function(id,field){
          mode_change(field);
        });
      }
    }else{
      alert('اطلاعاتی برای نمایش موجود نمی باشد');
    }
  });
}
function viewPath1(dt){
  if(typeof map_path === 'undefined'){
    initMapPath();
  }else{
    clearPath();
  }
  $(".loading").show();
  getPath(user_id,dt,function(data){
    $(".loading").hide();
    $("#travel_mode").html("");
    if(data.length>0){
      try{
        drawLine(data);
      }catch(e){
        // alert(e.message);
      }
      $("#m1_loading").hide();
      if(data[0]){
        try{
          moveMap(data[0].lon,data[0].lat);
        }catch(e){
          // alert(e.message);
        }
        $("#travel_mode").html("");
        /*
        $("#travel_mode").html("<div style='text-align:right;padding:5pt;background:#eaeaea;border:1pt solid #fff;'>هدف سفر</div>");
        var sel = '';        
        for(var i = 0;i<stops.length;i++){
          tmp_lonlat = goodToBad(stops[i].lon, stops[i].lat);
          var st_point = new OpenLayers.Geometry.Point(tmp_lonlat.lon ,tmp_lonlat.lat);
          var ftch = new OpenLayers.Feature.Vector(st_point);
          stops[i]['feature'] = ftch;
          $("#travel_mode").append("<div>");
          $("#travel_mode").append('<a href="javascript:setStop('+i+')" >'+stops[i].regdate.split(' ')[1]+'</a><br/>');
          sel = '<select disabled class="stops" id="stop_'+i+'" >';
          for(var j = 0;j<8;j++){
            sel += '<option value="'+j+'" >';
            sel += stops_names[j];
            sel += '</option>';
          }
          sel += '</select>';
          $("#travel_mode").append(sel);
          $("#travel_mode").append("</div>");
        }
        */
        $("#travel_mode").append("<div style='text-align:right;padding:5pt;background:#eaeaea;border:1pt solid #fff;'>وسیله سفر</div>");
        var sel = '';
        even = false;
        for(var i = 0;i<travel_modes.length;i++){
          if(travel_mode_names[travel_modes[i].mode] && travel_modes[i].mode!=6 && travel_modes[i].mode>0){
//           if(travel_modes[i].mode!=6){
            $("#travel_mode").append("<hr/><div>");
            $("#travel_mode").append(travel_modes[i].start.split(':')[0]+':'+travel_modes[i].start.split(':')[1]+' '+travel_modes[i].end.split(':')[0]+':'+travel_modes[i].end.split(':')[1]+'<br/>');
            sel = '<div style="text-align:center"><table style="width:100%"><tr>';
            sel += ((mode_icon[travel_modes[i].mode])?'<td style="text-align:right;width:50%"><ons-icon id="mode_icon_'+i+'" icon="fa-'+mode_icon[travel_modes[i].mode]+'" ></ons-icon></td><td style="text-align:left">':'<td style="text-align:center">')+'<select disabled class="modes" id="mode_'+i+'" onchange="mode_change(this);">';
            for(var j = 0;j<10;j++){
              sel += '<option value="'+j+'"'+((j==travel_modes[i].mode)?' selected':'')+' >';
              sel += travel_mode_names[j];
              sel += '</option>';
            }
            sel += '</select></td></tr></table></div>';
            /*
            sel += '<div id="answer-div-'+i+'" style="text-align:right">';
            sel += '<p id="question'+i+'">'+'زمان  انتظار برای رسیدن وسیله حمل و نقل عمومی  چقدر بود؟'+'</p>';
            sel+= '<select disabled class="modes_answer" id="mode_answer_'+i+'">';
            for(var j = 0;j<=4;j++){
              sel += '<option value="'+j+'" >';
              sel += travel_mode_answers[j];
              sel += '</option>';
            }
            sel += '</select></div>';
            sel += '<div id="rahati-div-'+i+'" style="text-align:right">';
            sel += '<p>'+'میزان راحتی شما در وسیله حمل و نقل عمومی جه میزان است ؟'+'</p>';
            sel+= '<select disabled class="modes_rahati" id="mode_rahati_'+i+'">';
            for(var j = 0;j<=5;j++){
              sel += '<option value="'+j+'" >';
              sel += travel_mode_rahatis[j];
              sel += '</option>';
            }
            sel += '</select></div>'
            */
            $("#travel_mode").append(sel);
            $("#travel_mode").append("</div>");
            even = (even===true)?false:true;
          }
        }
        /*
        $("#travel_mode").append("<div style=\"text-align:right\">");
        $("#travel_mode").append("آیا در طول سفرهای این روز از موبایل اپلیکیشن ها  برای مسیریابی و یا گرفتن اطلاعات وسایل حمل و نقل عمومی استفاده کرده اید ؟");
        $("#travel_mode").append("</div>");
        $("#travel_mode").append("<div style=\"direction:ltr\">");
        $("#travel_mode").append("خیر");
        $("#travel_mode").append("<input type=\"radio\" name=\"mapp\" value=\"0\" id=\"mapp0\" checked>");
        $("#travel_mode").append("<br/>");
        $("#travel_mode").append("بله");
        $("#travel_mode").append("<input type=\"radio\" name=\"mapp\" value=\"1\" id=\"mapp1\">");
        $("#travel_mode").append("</div>");
        $("#travel_mode").append("<p style=\"text-align: center;\"><ons-button onclick=\"saveModes();\">تایید</ons-button></p>");
        */
        $(".modes").each(function(id,field){
          mode_change(field);
        });
      }
    }else{
      alert('اطلاعاتی برای نمایش موجود نمی باشد');
    }
  });
}
function mode_change(dobj){
  var obj = $(dobj);
  // alert(obj.val());
  var i = obj.prop('id').split('_')[1];
  var selected_mode = obj.val();
  if(selected_mode == 3 || selected_mode == 4 || selected_mode == 5){
    $("#answer-div-"+i).show();
    if(selected_mode == 3){
      $("#question"+i).html('اگر از خودرو شخصی استفاده کردید زمان پیدا کردن پارکینگ');
      $("#rahati-div-"+i).hide();
    }else{
      $("#question"+i).html('اگر وسیله مترو یا اتوبوس است زمان  انتظار برای رسیدن وسیله حمل و نقل عمومی  چقدر بود؟ ');
      $("#rahati-div-"+i).show();
    }
  }else{
    $("#answer-div-"+i).hide();
    $("#rahati-div-"+i).hide();
  }
  var ico = '<ons-icon style="color:'+mode_color[selected_mode]+';" id="mode_icon_'+i+'" size="32px" icon="fa-'+mode_icon[selected_mode]+'"></ons-icon>';
  $("#mode_icon_"+i).parent().html(ico);//.remove();
  // $("#mode_icon_"+i).remove();
  // $("#mode_icon_"+i+"_tmp").prop('id',"#mode_icon_"+i);
}
function timeDif(start_dt,end_dt){
  var out = '';
  var today = new Date(start_dt);
  var Christmas = new Date(end_dt);
  var diffMs = (Christmas - today); // milliseconds between now & Christmas
  var diffDays = Math.floor(diffMs / 86400000); // days
  var diffHrs = Math.floor((diffMs % 86400000) / 3600000); // hours
  var diffMins = Math.round(((diffMs % 86400000) % 3600000) / 60000); // minutes
  // alert(diffDays + " days, " + diffHrs + " hours, " + diffMins + " minutes until Christmas 2009 =)");
  out = diffHrs + " hours, " + diffMins + " minutes";
  return out;
}
function saveModes(){
  // alert('start save');
  var i = -1;
  $(".stops").each(function(id,field){
    i = parseInt($(field).prop('id').split('_')[1],10);
    // alert(i);
    stops[i]['tgoal'] = $("#stop_"+i+" option:selected").prop('value');
    // alert(stops[i].tgoal);
  });
//stops[i].id,stops[i].tgoal);
  
  $(".modes").each(function(id,field){
    i = parseInt($(field).prop('id').split('_')[1],10);
    // alert($("#mode_"+i+" option:selected").prop('value'));
    travel_modes[i].umode = $("#mode_"+i+" option:selected").prop('value');
    travel_modes[i].answer = $("#mode_answer_"+i+" option:selected").prop('value'); 
    travel_modes[i].rahati = $("#mode_rahati_"+i+" option:selected").prop('value'); 
    travel_modes[i].mobile_app =  ($("#mapp1").prop('checked')?1:0);
    // alert(travel_modes[i].start_id+' - '+travel_modes[i].end_id+' = '+travel_modes[i].mode+': '+travel_modes[i].umode);
  });
  stops_indx = 0;
  sendStop(function(){
    // alert('stops sent');
    stops_indx = 0;
    sendMode(function(){
      addEmtiaz(stops_indx,function(){
        alert('ارسال فرم انجام شد');        
      });
    });
  });
  //user_id,travel_modes[i].start_id,travel_modes[i].end_id,travel_modes[i].umode,travel_modes[i].answer,travel_modes[i].rahati,travel_modes[i].mobile_app);  
}
function viewPath1_(dt){
  if(typeof map_path1 === 'undefined'){
    initMapPath();
  }else{
    clearPath();
  }
  $(".loading").show();
  getPath(user_id,dt,function(data){
    $(".loading").hide();
    $("#m1_loading").hide();
    if(data.length>0){
      drawLine(data);
      if(data[0]){
        moveMap(data[0].lon,data[0].lat);
      }
    }else{
      alert('اطلاعاتی برای نمایش موجود نمی باشد');
    }
  });
}
/*
function viewPath(dt){
  if(typeof map_path === 'undefined'){
    initMapPath();
  }else{
    clearPath();
  }
  $(".loading").show();
  getPath(user_id,dt,function(data){
    $(".loading").hide();
    if(data.length>0){
      if(data.length>1){
        var points = [];
        for(var i = 0;i < data.length;i++){
          tmp_lonlat = goodToBad(data[i].lon, data[i].lat);
          var point = new OpenLayers.Geometry.Point(tmp_lonlat.lon ,tmp_lonlat.lat);
          layer.addFeatures([new OpenLayers.Feature.Vector(point)]);
        }
        map_path.panTo(layer.getDataExtent().getCenterLonLat());
        map_path.zoomTo(layer.getDataExtent().getZoomExtent());
      }else{
        tmp_lonlat = goodToBad(data[0].lon, data[0].lat);
        var point = new OpenLayers.Geometry.Point(tmp_lonlat.lon ,tmp_lonlat.lat);
        layer.addFeatures([new OpenLayers.Feature.Vector(point)]);
        map_path.panTo(layer.getDataExtent().getCenterLonLat());
        map_path.zoomTo(layer.getDataExtent().getZoomExtent());
      }
    }else{
      alert('اطلاعاتی برای نمایش موجود نمی باشد');
    }
  });
}
*/