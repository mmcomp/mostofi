<!DOCTYPE HTML>
<title>OpenLayers Simplest Example</title>
<div id="demoMap" style="height:250px"></div>
<script src="OpenLayers.js"></script>
<script>
      function convToGood(lon,lat){
        var toProjection = new OpenLayers.Projection("EPSG:4326");
        var fromProjection   = new OpenLayers.Projection("EPSG:900913");
        var position       = new OpenLayers.LonLat(lon, lat).transform( fromProjection, toProjection);
        return position;
      }
      function onFeatureAdded(a){
        console.log(a);
        var out = convToGood(a.geometry.x,a.geometry.y);
        
        console.log(out);
      }
      map = new OpenLayers.Map("demoMap");
      var fromProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
      var toProjection   = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
      var position       = new OpenLayers.LonLat(59.54019928931992,36.32471101369044).transform( fromProjection, toProjection);
      var zoom           = 18; 
      // map.addLayer(new OpenLayers.Layer.OSM());
      var baseLayer = new OpenLayers.Layer.OSM();
      var pointLayer = new OpenLayers.Layer.Vector("Point Layer");
      map.addLayers([baseLayer,pointLayer]);
      // map.addControl(new OpenLayers.Control.MousePosition());
      var control = new OpenLayers.Control.DrawFeature(pointLayer,OpenLayers.Handler.Point,{featureAdded: onFeatureAdded});
      // control.activate();
      // map.zoomToMaxExtent();
      map.addControl(control);
      control.activate();
      map.setCenter(position, zoom );
</script>