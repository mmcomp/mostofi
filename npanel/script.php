<?php

?>
<script src="js/libs/jquery-ui.js"></script>
<script src="js/cal/jalali.js"></script>
<script src="js/cal/calendar.js"></script>
<script src="js/cal/calendar-setup.js"></script>
<script src="js/cal/lang/calendar-fa.js"></script>
<script src="OpenLayers.js"></script>
<script>
      var points = <?php echo json_encode($points); ?>;
      var manategh = <?php echo json_encode($manategh); ?>;
      var realvals = <?php echo json_encode($realvals,TRUE); ?>;
      var vals = <?php echo json_encode($vals,TRUE); ?>;
			var selStyle,selId,newStyle;
      var layer;
      var map;
      var dialog;
			var layerListeners = {
				featureclick: function(e) {
					$(".man").css('border','');
					console.log(e.object, e.feature);
					console.log('selId :',selId);
					if(selId){
						newStyle = selStyle;
						newStyle.strokeColor = "#ff0000";
						newStyle.strokeWidth = 2;
						manategh[selId].fit.style = newStyle;
					}
					var selF = e.feature.id;
					console.log('selF : ',selF);
					var id = -1;
					for(var i=0;i < manategh.length;i++){
						if(manategh[i].fit.id==selF){
							id = manategh[i].id;
							selId = i;
							console.log('found selId : ',selId,id);
							selStyle = manategh[i].fit.style;
						}
					}
					if(id>0){
						newStyle = selStyle;
						newStyle.strokeColor = "#00ff00";
						newStyle.strokeWidth = 10;
						manategh[selId].fit.style = newStyle;
					}
					if($("#man_"+id).length>0){
						console.log('top '+id+' : ',$("#man_"+id).position().top);
						$("#man_"+id).css('border','solid 2px #aa829f');
						console.log('top '+id+' : ',$("#man_"+id).position().top);
						$("#leg").animate({
							scrollTop : $("#man_"+id).position().top
						});
					}else{
						console.log('No Leg');
					}
					layer.redraw();
					return false;
				}
			}
			function selPol(id){
				$(".man").css('border','');
				if(selId){
					newStyle = selStyle;
					newStyle.strokeColor = "#ff0000";
					newStyle.strokeWidth = 2;
					manategh[selId].fit.style = newStyle;
				}
				for(var i=0;i < manategh.length;i++){
					if(manategh[i].id==id){
						selId = i;
						selStyle = manategh[i].fit.style;
					}
				}
				console.log(selId);
				if(selId>0){
					newStyle = selStyle;
					newStyle.strokeColor = "#00ff00";
					newStyle.strokeWidth = 10;
					manategh[selId].fit.style = newStyle;
					layer.redraw();
				}
				$("#man_"+id).css('border','solid 2px #aa829f');
			}
      function goodToBad(lon,lat){
        var fromProjection = new OpenLayers.Projection("EPSG:4326");
        var toProjection   = new OpenLayers.Projection("EPSG:900913");
        var position       = new OpenLayers.LonLat(lon, lat).transform( fromProjection, toProjection);
        return position;  
      }
      function init() {
        console.log('init');
        map = new OpenLayers.Map("basicMap");
        console.log(map);
        var baseLayer         = new OpenLayers.Layer.OSM();
        var fromProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
        var toProjection   = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
        
        if(points.length==0){
          var position = new OpenLayers.LonLat(59.6134573,36.288546).transform( fromProjection, toProjection);
        
        }else{
          var position = new OpenLayers.LonLat(points[0].lon,points[0].lat).transform( fromProjection, toProjection);
        }
        
        var zoom = 11; 
        layer = new OpenLayers.Layer.Vector("Path Layer");//, {eventListeners: layerListeners});
        
        var format = new OpenLayers.Format.WKT();
        var mfeature,mfeatures=[];
        for(var ind = 0;ind < manategh.length;ind++){
          mfeature = format.read(manategh[ind].wkt);
          mfeature.geometry.transform('EPSG:4326', 'EPSG:900913');
          mfeature.style = {
						strokeColor: '#000000',
						strokeOpacity: 0.6,
						strokeWidth: 2,
						fillColor : '#ac1919',
						fillOpacity : manategh[ind].rval,
						label : "Z"+manategh[ind].id
          };
					if(manategh[ind].val>0){
						mfeature.style['label'] += "\n"+String(manategh[ind].val)	 ;
					}
          manategh[ind].fit = mfeature;
          mfeatures.push(mfeature);
        }
        layer.addFeatures(mfeatures);
        
//         drawPoints();
        if(points.length){
          drawLine();
        }
        
        map.addLayers([baseLayer,layer]);
        map.setCenter(position, zoom );
        
      }
      function drawPoints(){
        for(var i = 0;i < points.length;i++){
          tmp_lonlat = goodToBad(points[i].lon, points[i].lat);
          var point = new OpenLayers.Geometry.Point(tmp_lonlat.lon ,tmp_lonlat.lat);
          var fich = new OpenLayers.Feature.Vector(point);
          layer.addFeatures([fich]);
        }
      }
      function drawLine(){
        var opoints = [];
        var lines = {};
        var last_mode = 0;
        for(var i = 0;i < points.length;i++){
//           if(i==0){
//             console.log(points[i]);
//           }
          tmp_lonlat = goodToBad(points[i].lon, points[i].lat);
          var point = new OpenLayers.Geometry.Point(tmp_lonlat.lon ,tmp_lonlat.lat);
          if(points[i].mode!=last_mode && last_mode>0){
            var line = new OpenLayers.Geometry.LineString(opoints);
            if(typeof lines[last_mode]== 'undefined'){
              lines[last_mode]=[];
            }
            lines[last_mode].push(line);
//             console.log('mode ',last_mode,' added');
            opoints=[];
          }
          last_mode = points[i].mode;
          opoints.push(point);
          
        }
        var line = new OpenLayers.Geometry.LineString(opoints);
        if(typeof lines[points[i-1].mode]== 'undefined'){
          lines[points[i-1].mode]=[];
        }
        lines[points[i-1].mode].push(line);
//         console.log(lines);
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
      }
      $(document).ready(function(){
          init();
					Calendar.setup({
							inputField: 'aztarikh',
							ifFormat: '%Y/%m/%d',
							dateType: 'jalali'
					});
					Calendar.setup({
							inputField: 'tatarikh',
							ifFormat: '%Y/%m/%d',
							dateType: 'jalali'
					});
      });
			function clearTarikh(){
				if(confirm('آیا مطمئن هستید؟')){
					$("#tarikh-form").append('<input type="hidden" name="aztarikh" value="" />');
					$("#tarikh-form").append('<input type="hidden" name="tatarikh" value="" />');
					$("#tarikh-form").submit();
				}
			}
			function setTarikh(){
				var aztarikh = $("#aztarikh").val();
				var tatarikh = $("#tatarikh").val();
				if(aztarikh=='' || tatarikh==''){
					alert('باید هردو تاریخ انتخاب شود');
				}else{
					if(tatarikh>=aztarikh){
						if($("#tarikh-form").length==1){
							$("#tarikh-form").append('<input type="hidden" name="aztarikh" value="'+aztarikh+'" />');
							$("#tarikh-form").append('<input type="hidden" name="tatarikh" value="'+tatarikh+'" />');
							$("#tarikh-form").submit();
						}
					}else{
						alert('لطفا توالی تاریخ حفظ شود');
					}					
				}
			}
	
      function loadTolid(){
        dialog.dialog('option', 'title', 'تولید');
        $("#repo").val('tolid');
        dialog.dialog( "open" );
      }
      function loadJazb(){
        dialog.dialog('option', 'title', 'جذب');
        $("#repo").val('jazb');
        dialog.dialog( "open" );
      }
      function startSearch(){
        $("#dfrm").submit();
      }
	    var dialog = $( "#dialog-form" ).dialog({
        autoOpen: false,
        height: 400,
        width: 350,
        modal: true,
//         dialogClass: 'my-dialog',
        buttons: {
          "نمایش": startSearch,
          "انصراف": function() {
            dialog.dialog( "close" );
          }
        },
        close: function() {
  //         alert('close');
        }
      });
//       $('.my-dialog .ui-button-text:contains(CANCEL)').text('انصراف');
    </script>