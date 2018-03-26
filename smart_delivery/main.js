var map_user,map_path,baseLayer,pointLayer,layer;
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
}
function onFeatureAdded(a){
  var out = convToGood(a.geometry.x,a.geometry.y);
  console.log(out.lon+','+out.lat);
  $("#"+feild).val(out.lon+','+out.lat);
}
function initMap(){
  id = "demoMap";
  map_user = new OpenLayers.Map(id);
  var fromProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
  var toProjection   = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
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
}
function initMapPath(){
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
function selectFeild(inFeild){
  feild = inFeild;
  if(typeof map_user === 'undefined'){
    initMap();
  }else{
    clearPoints();
  }
}
function viewPath(){
  if(typeof map_path === 'undefined'){
    initMapPath();
  }else{
    clearPath();
  }
  $(".loading").show();
  getPath(user_id,function(data){
    $(".loading").hide();
    if(data.length>0){
      // alert(data.length);
      if(data.length>1){
        var points = [];
        for(var i = 0;i < data.length;i++){
          tmp_lonlat = goodToBad(data[i].lon, data[i].lat);
          var point = new OpenLayers.Geometry.Point(tmp_lonlat.lon ,tmp_lonlat.lat);
          layer.addFeatures([new OpenLayers.Feature.Vector(point)]);
          // points.push(new OpenLayers.Geometry.Point(tmp_lonlat.lon, tmp_lonlat.lat));
        }
        // var line = new OpenLayers.Geometry.LineString(points);
        /*
        var style = { 
          strokeColor: '#0000ff', 
          strokeOpacity: 0.5,
          strokeWidth: 5
        };
        var feature = new OpenLayers.Feature.Vector(line, null, style);
        layer.addFeatures([feature]);
        */
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