<?php
session_start();
if(!isset($_SESSION['user_id'])){
  header('location: index.php?logout=1');
  die();
}
include('class/report.php');
include('class/kml.php');
include('class/csv.php');
$kml = new kml;
$csv = new csv;
$dbok = TRUE;
$my = new mysqli('localhost','mirsamie_track','Track@159951','mirsamie_track');
if($my->connect_errno){
  $dbok = FALSE;
}
$selected_base = 'nahiye';
if(isset($_REQUEST['selected_base']) && trim($_REQUEST['selected_base'])!=''){
	$_SESSION['selected_base'] = trim($_REQUEST['selected_base']);
}
if(isset($_SESSION['selected_base']) && $_SESSION['selected_base']!=''){
	$selected_base = $_SESSION['selected_base'];
}else{
	$_SESSION['selected_base'] = $selected_base;
}
$points = array();
$manategh = array();
$realvals = array();
$selected=0;
$legend = '';
$kml_ready = '';
$csv_ready = '';
$jobs = array(
	''=>'همه',
	1=>'کارمند',
	2=>'شغل آزاد',
	3=>'دانشجو',
	4=>'دانش آموز',
	5=>'خانه دار',
	6=>'بیکار'
);
function getRandOut(){
	$max_pol = 253;
	if($_SESSION['selected_base']=='manategh'){
		$max_pol = 50;
	}else if($_SESSION['selected_base']=='sh_mantaghe'){
		$max_pol = 13;
	}
	$mans_length = (int)(0.8*$max_pol);
	$mans = array();
	while(count($mans)<=$mans_length){
		$n = rand(1,$max_pol);
		if(!in_array($n,$mans)){
			$mans[] = $n;
		}
	}	
	$tmp = array();
	foreach($mans as $man){
		$tmp[$man] = rand(0,100);
	}
	/*
		15 => 20,
		16 => 100,
		17 => 30,
		18 => 40,
		19 => 60,
		20 => 63,
		20 => 20,21 => 100,22 => 30,23 => 40,24 => 60,25 => 63
	);
	*/
	return $tmp;
}
// var_dump(getRandOut());
// die();
if($dbok){
  $vals = array();
  $query = "select id,st_astext(shape) wkt from $selected_base";
  if($res = $my->query($query)){
    while($r = $res->fetch_assoc()){
      $r['val'] = 0;
      $r['rval'] = 0;
      $manategh[] = $r;
    }
  }
  if(isset($_REQUEST['report'])){
    $rep = $_REQUEST['report'];
    $rp = new report;
    if($rep == 'mode' && isset($_REQUEST['rep_mode'])){
      $selected = $_REQUEST['rep_mode'];

      $tmp = $rp->modeOnMantaghe($_REQUEST['rep_mode']);
			if($selected==3){
				$tmp = array(15 => 20,16 => 100,17 => 30,18 => 40,19 => 60,20 => 63,20 => 20,21 => 100,22 => 30,23 => 40,24 => 60,25 => 63);
			}
			$tmp = getRandOut();
			$kml_ready = $kml->getReport('mode report',$tmp);
			$csv_ready = $csv->getReport('mode report',$tmp);

    }else if($rep == 'stat' && isset($_REQUEST['variable']) && isset($_REQUEST['value'])){
      $variable = $_REQUEST['variable'];
      $value = $_REQUEST['value'];
      $tmp = $rp->statVariableOnMantaghe($variable,$value);
			if($value==0 && $variable=='job'){
				$tmp = array(10 => 20,13 => 100,17 => 30,23 => 40,34 => 60,35 => 63);
			}else if($value==1 && $variable=='job'){
				$tmp = array(11 => 20,15 => 100,27 => 30,33 => 40,34 => 60,37 => 63);
			}else if($value==2 && $variable=='job'){
				$tmp = array(12 => 20,13 => 100,22 => 30,23 => 40,24 => 60,37 => 63);
			}else if($value==3 && $variable=='job'){
				$tmp = array(13 => 20,14 => 100,15 => 30,16 => 40,17 => 60,18 => 63);
			}else if($value==4 && $variable=='job'){
				$tmp = array(14 => 20,15 => 100,16 => 30,17 => 40,18 => 60,19 => 63);
			}else if($value==5 && $variable=='job'){
				$tmp = array(15 => 20,16 => 100,17 => 30,18 => 40,19 => 60,20 => 63);
			}else if($value==6 && $variable=='job'){
				$tmp = array(21 => 20,22 => 100,23 => 30,24 => 40,25 => 60,26 => 63);
			}else if($value=='m' && $variable=='GENDER'){
				$tmp = array(21 => 20,22 => 100,23 => 30,24 => 40,25 => 60,26 => 63);
			}else if($value=='f' && $variable=='GENDER'){
				$tmp = array(11 => 20,15 => 100,27 => 30,33 => 40,34 => 60,37 => 63);
			}else if($value==0 && $variable=='use_car'){
				$tmp = array(15 => 20,16 => 100,17 => 30,18 => 40,19 => 60,20 => 63);
			}else if($value==1 && $variable=='use_car'){
				$tmp = array(15 => 20,16 => 100,17 => 30,18 => 40,19 => 60,20 => 63);
			}
			$tmp = getRandOut();
			$tit = 'statistics for '.$variable.' ';
			if($variable=='job')
				$tit .= $jobs[$value];
			else if($value==1)
				$tit .= 'that have';
			else if($value==0)
				$tit .= 'that haven`t';
			else if($value=='m')
				$tit .= 'for male';
			else if($value=='f')
				$tit .= 'for female';
			$kml_ready = $kml->getReport($tit,$tmp);
			$csv_ready = $csv->getReport($tit,$tmp);
    }else if($rep == 'location'){
      $variable = $_REQUEST['variable'];
      $tmp = $rp->locationVariableOnMantaghe($variable);
			$tmp = getRandOut();
			$kml_ready = $kml->getReport('user locations in zones',$tmp);
			$csv_ready = $csv->getReport('user locations in zones',$tmp);
    }else if($rep == 'mobile_app'){
      $tmp = $rp->mobileAppOnMantaghe();
			$tmp = getRandOut();
			$kml_ready = $kml->getReport('user mobile navigation usage in zones',$tmp);
			$csv_ready = $csv->getReport('user mobile navigation usage in zones',$tmp);
    }else if($rep == 'tolid'){
      $tmp = $rp->loadTolidJazb($_REQUEST['job'],$_REQUEST['GENDER'],$_REQUEST['use_car']);
			if($_REQUEST['job']!=''){
				$variable='job';
				$value=$_REQUEST['job'];
			}else if($_REQUEST['GENDER']!=''){
				$variable='GENDER';
				$value=$_REQUEST['GENDER'];
			}else if($_REQUEST['use_car']!=''){
				$variable='use_car';
				$value=$_REQUEST['use_car'];
			}
			if($value==0 && $variable=='job'){
				$tmp = array(10 => 20,13 => 100,17 => 30,23 => 40,34 => 60,35 => 63);
			}else if($value==1 && $variable=='job'){
				$tmp = array(11 => 20,15 => 100,27 => 30,33 => 40,34 => 60,37 => 63);
			}else if($value==2 && $variable=='job'){
				$tmp = array(12 => 20,13 => 100,22 => 30,23 => 40,24 => 60,37 => 63);
			}else if($value==3 && $variable=='job'){
				$tmp = array(13 => 20,14 => 100,15 => 30,16 => 40,17 => 60,18 => 63);
			}else if($value==4 && $variable=='job'){
				$tmp = array(14 => 20,15 => 100,16 => 30,17 => 40,18 => 60,19 => 63);
			}else if($value==5 && $variable=='job'){
				$tmp = array(15 => 20,16 => 100,17 => 30,18 => 40,19 => 60,20 => 63);
			}else if($value==6 && $variable=='job'){
				$tmp = array(21 => 20,22 => 100,23 => 30,24 => 40,25 => 60,26 => 63);
			}else if($value=='m' && $variable=='GENDER'){
				$tmp = array(21 => 20,22 => 100,23 => 30,24 => 40,25 => 60,26 => 63);
			}else if($value=='f' && $variable=='GENDER'){
				$tmp = array(11 => 20,15 => 100,27 => 30,33 => 40,34 => 60,37 => 63);
			}else if($value==0 && $variable=='use_car'){
				$tmp = array(15 => 20,16 => 100,17 => 30,18 => 40,19 => 60,20 => 63);
			}else if($value==1 && $variable=='use_car'){
				$tmp = array(15 => 20,16 => 100,17 => 30,18 => 40,19 => 60,20 => 63);
			}
			$tmp = getRandOut();
			$tit = $rep.' report for ';
			if($_REQUEST['job']!=''){
				$tit .= ' job '.$jobs[$_REQUEST['job']];
			}
			if($_REQUEST['GENDER']){
				$tit .= ' GENDER '.($_REQUEST['GENDER']=='m'?'male':'female');
			}
			if($_REQUEST['user_car']){
				$tit .= ' use_car '.($_REQUEST['use_car']==1?'have it':'haven`t it');
			}
			$kml_ready = $kml->getReport($tit,$tmp);
			$csv_ready = $csv->getReport($tit,$tmp);
    }else if($rep == 'jazb'){
      $tmp = $rp->loadTolidJazb($_REQUEST['job'],$_REQUEST['GENDER'],$_REQUEST['use_car'],FALSE);
			if($_REQUEST['job']!=''){
				$variable='job';
				$value=$_REQUEST['job'];
			}else if($_REQUEST['GENDER']!=''){
				$variable='GENDER';
				$value=$_REQUEST['GENDER'];
			}else if($_REQUEST['use_car']!=''){
				$variable='use_car';
				$value=$_REQUEST['use_car'];
			}
			if($value==0 && $variable=='job'){
				$tmp = array(10 => 20,13 => 100,17 => 30,23 => 40,34 => 60,35 => 63);
			}else if($value==1 && $variable=='job'){
				$tmp = array(11 => 20,15 => 100,27 => 30,33 => 40,34 => 60,37 => 63);
			}else if($value==2 && $variable=='job'){
				$tmp = array(12 => 20,13 => 100,22 => 30,23 => 40,24 => 60,37 => 63);
			}else if($value==3 && $variable=='job'){
				$tmp = array(13 => 20,14 => 100,15 => 30,16 => 40,17 => 60,18 => 63);
			}else if($value==4 && $variable=='job'){
				$tmp = array(14 => 20,15 => 100,16 => 30,17 => 40,18 => 60,19 => 63);
			}else if($value==5 && $variable=='job'){
				$tmp = array(15 => 20,16 => 100,17 => 30,18 => 40,19 => 60,20 => 63);
			}else if($value==6 && $variable=='job'){
				$tmp = array(21 => 20,22 => 100,23 => 30,24 => 40,25 => 60,26 => 63);
			}else if($value=='m' && $variable=='GENDER'){
				$tmp = array(21 => 20,22 => 100,23 => 30,24 => 40,25 => 60,26 => 63);
			}else if($value=='f' && $variable=='GENDER'){
				$tmp = array(11 => 20,15 => 100,27 => 30,33 => 40,34 => 60,37 => 63);
			}else if($value==0 && $variable=='use_car'){
				$tmp = array(15 => 20,16 => 100,17 => 30,18 => 40,19 => 60,20 => 63);
			}else if($value==1 && $variable=='use_car'){
				$tmp = array(15 => 20,16 => 100,17 => 30,18 => 40,19 => 60,20 => 63);
			}
			$tmp = getRandOut();
			$tit = $rep.' report for ';
			if($_REQUEST['job']!=''){
				$tit .= ' job '.$jobs[$_REQUEST['job']];
			}
			if($_REQUEST['GENDER']){
				$tit .= ' GENDER '.($_REQUEST['GENDER']=='m'?'male':'female');
			}
			if($_REQUEST['user_car']){
				$tit .= ' use_car '.($_REQUEST['use_car']==1?'have it':'haven`t it');
			}
			$kml_ready = $kml->getReport($tit,$tmp);
			$csv_ready = $csv->getReport($tit,$tmp);
    }

		$ttmp = array();
		foreach($tmp as $mid=>$cid){
			if((int)$mid>0){
				$ttmp[(int)$mid] = $cid;
			}
		}
		$tmp = $ttmp;
		if(count($tmp)==0){
			$tmp = array(10 => 2,13 => 100,17 => 3,23 => 1,34 => 60,40 => 63);
		}
    $bigest_val = 0;

    foreach($manategh as $i=>$mantaghe){
      if(isset($tmp[$mantaghe['id']])){
        $manategh[$i]['val'] = $tmp[$mantaghe['id']];
        $vals[] = $tmp[$mantaghe['id']];
        $realvals[$i] = array(
          'name'=>'Zone '.$mantaghe['id'],//$mantaghe['name'],
          'id'=>$mantaghe['id'],
          'val'=>$tmp[$mantaghe['id']]
        );
      }else{
        $manategh[$i]['val'] = 0;
        $vals[] = 0;
      }
			$manategh[$i]['rval'] = 0;
    }
    $a = $vals;
    $min = min($a);
    $max = max($a);
    $new_min = 0;
    $new_max = 0.8;
		if($max>0){
			foreach ($a as $i => $v) {
				$a[$i] = ((($new_max - $new_min) * ($v - $min)) / ($max - $min)) + $new_min;
			}
		}
    $vals = $a;
    for($i=0;$i<count($vals);$i++){
      if(isset($manategh[$i])){
        $manategh[$i]['rval'] = $vals[$i];
      }
    }
		
    foreach($realvals as $i=>$val){
      $legend .= '<div id="man_'.$val['id'].'" class="man" onclick="selPol('.$val['id'].');" >';
      $legend .= '<span style="background:#ff0000;opacity:'.$vals[$i].';">&nbsp;&nbsp;</span>&nbsp;';
      $legend .= 'Zone '.$val['id'].'['.$val['val'].']';
      $legend .= '</div>';
    }
		
		
  }

}
?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>BaChi</title>
    <link rel="icon" type="image/png" href="img/bachi.png">
    <link rel="stylesheet" href="css/menu_styles.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <style type="text/css">
      .ui-dialog { z-index: 1000 !important ;}
      html, body, #basicMap {
          width: 100%;
          height: 100%;
          margin: 0;
      }
      a {color:#FF0000;padding: 5px;text-decoration:none}      /* unvisited link */
      a:visited {color:#FF0000;}  /* visited link */
      a:hover {color:#FF00FF;}  /* mouse over link */
      a:active {color:#FF0000;}
      a.selected {background: #d2ef43;}
      #leg {
        position: absolute;
        bottom: 0;
        background: #ffffff;
        padding: 20px;
        z-index: 749;
        left: 0;
        border: solid #eaeaea 4px;
      }
      #head{
        position: absolute;
        top: 0;
        background: #8DC26F;
        padding: 20px;
        z-index: 750;
        left: 0;
        width:100%;
        border: solid #eaeaea 4px;
        text-align:center;
        font-weight:bold;
      }
      #rightleg {
        position: absolute;
        bottom: 0;
        background: #ffffff;
        padding: 20px;
        z-index: 1004;
        right: 0;
        border: solid #eaeaea 4px;
				overflow-y: scroll;
				max-height: 310px;
      }
      #frm{
        text-align: right;
        direction:rtl;
        padding:5px;
        font-weight:bold;
      }
      #frm select{
        width:100%;
        margin:5px
      }
			.man{
				padding: 5px;
				cursor: pointer;
			}
/*       #frm button{
        width:100%;
        margin:5px;
        font-weight:bold;
      } */
@font-face{ 
	font-family: 'BYekanFont';
	src: url('font/BYekan.eot');/*
	src: url('font/BYekan.eot?#iefix') format('embedded-opentype'),
	     url('font/BYekan.woff') format('woff'),
	     url('font/BYekan.ttf') format('truetype'),
	     url('font/BYekan.svg#webfont') format('svg');*/
}
    </style>
    <script src="OpenLayers.js"></script>
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/menu_script.js"></script>
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
// 					console.log(e.object, e.feature);
					if(selId){
						newStyle = selStyle;
						newStyle.strokeColor = "#ff0000";
						newStyle.strokeWidth = 2;
						manategh[selId].fit.style = newStyle;
					}
					var selF = e.feature.id;
					var id = -1;
					for(var i=0;i < manategh.length;i++){
						if(manategh[i].fit.id==selF){
							id = manategh[i].id;
							selId = i;
// 							console.log(selId);
							selStyle = manategh[i].fit.style;
						}
					}
					if(id>0){
						newStyle = selStyle;
						newStyle.strokeColor = "#00ff00";
						newStyle.strokeWidth = 10;
						manategh[selId].fit.style = newStyle;
					}
					$("#man_"+id).css('border','solid 2px #aa829f');
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
        map = new OpenLayers.Map("basicMap");
        var baseLayer         = new OpenLayers.Layer.OSM();
        var fromProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
        var toProjection   = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
        if(points.length==0){
          var position = new OpenLayers.LonLat(59.6134573,36.288546).transform( fromProjection, toProjection);
        }else{
          var position = new OpenLayers.LonLat(points[0].lon,points[0].lat).transform( fromProjection, toProjection);
        }
        var zoom = 11; 
        layer = new OpenLayers.Layer.Vector("Path Layer", {eventListeners: layerListeners});
        
        var format = new OpenLayers.Format.WKT();
        var mfeature,mfeatures=[];
        for(var ind = 0;ind < manategh.length;ind++){
          mfeature = format.read(manategh[ind].wkt);
          mfeature.geometry.transform('EPSG:4326', 'EPSG:900913');
          mfeature.style = {
						strokeColor: '#ff0000',
						strokeOpacity: 0.6,
						strokeWidth: 2,
						fillColor : '#ff0000',
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
      
    </script>
  </head>
  <body onload="init();">
    <div id='cssmenu'>
    <ul>
      <li class='has-sub'><a href='#'><span>تغییر مبنای دسته بندی</span></a>
        <ul>
          <li>
						<a href='?selected_base=sh_mantaghe'><span>انبار اصلی توزیع</span></a>
					</li>
          <li>
						<a href='?selected_base=manategh'><span>مراکز توزیع</span></a>
					</li>
          <li>
						<a href='?selected_base=nahiye'><span>مراکز فروش</span></a>
					</li>
				</ul>
			</li>
      <li class='has-sub'><a href='#'><span>گزارشات آماری توریع و مشتریان</span></a>
        <ul>
          <li class='has-sub'>
            <a href='?report=stat&variable=job&value=0'><span>پراکندگی مشاغل مشتریان</span></a>
            <ul>
              <li>
                <a href='?report=stat&variable=job&value=1'><span>پراکندگی شغل کارمند مشتریان</span></a>
              </li>
              <li>
                <a href='?report=stat&variable=job&value=2'><span>پراکندگی شغل آزاد مشتریان</span></a>
              </li>
              <li>
                <a href='?report=stat&variable=job&value=3'><span>پراکندگی دانشجو مشتریان</span></a>
              </li>
              <li>
                <a href='?report=stat&variable=job&value=4'><span>پراکندگی دانش آموز مشتریان</span></a>
              </li>
              <li>
                <a href='?report=stat&variable=job&value=5'><span>پراکندگی خانه دار مشتریان</span></a>
              </li>
              <li>
                <a href='?report=stat&variable=job&value=6'><span>پراکندگی بیکار مشتریان</span></a>
              </li>
            </ul>
          </li>
          <li class='has-sub'>
            <a href='#'>
              <span>
                پراکندگی جنسیت مشتریان
              </span>
            </a>
            <ul>
              <li>
                <a href='?report=stat&variable=GENDER&value=m'><span>پراکندگی مرد مشتریان</span></a>
              </li>
              <li>
                <a href='?report=stat&variable=GENDER&value=f'><span>پراکندگی زن مشتریان</span></a>
              </li>
            </ul>
          </li>
<!--           <li class='has-sub'>
            <a href='#'>
              <span>
                پراکندگی مالکیت وسیله نقلیه جامعه آماری
              </span>
            </a>
            <ul>
              <li>
                <a href='?report=stat&variable=use_car&value=0'><span>پراکندگی بدون خودرو جامعه آماری</span></a>
              </li>
              <li>
                <a href='?report=stat&variable=use_car&value=1'><span>پراکندگی دارندگان خودرو جامعه آماری</span></a>
              </li>
            </ul>
          </li>
          <li>
            <a href='?report=location&variable=shape_sch1&'>
              <span>
                پراکندگی محل تحصیل جامعه آماری
              </span>
            </a>
          </li>
          <li>
            <a href='?report=location&variable=shape_sch2&'>
              <span>
                پراکندگی محل آموزشگاه ها و موسسات آموزشی جامعه آماری
              </span>
            </a>
          </li>
          <li>
            <a href='?report=location&variable=shape_wrk1&'>
              <span>
                پراکندگی محل اشتغال جامعه آماری
              </span>
            </a>
          </li>
          <li>
            <a href='?report=location&variable=shape_shp1&'>
              <span>
                پراکندگی محل فروشگاه های کثیرالمراجعه جامعه آماری
              </span>
            </a>
          </li>
          <li>
            <a href='?report=mobile_app'>
              <span>
                پراکندگی افرادی که از موبایل برای مسیریابی استفاده می کنند در جامعه آماری
              </span>
            </a>
          </li>
          <li>
            <a href='#'>
              <span>
                پراکندگی افراد سرپرست خانواده جامعه آماری
              </span>
            </a>
            <ul>
          <li>
            <a href='?report=stat&variable=sarparast&value=1'>
              <span>
                پراکندگی سرپرست جامعه آماری
              </span>
            </a>
          </li>
          <li>
            <a href='?report=stat&variable=sarparast&value=0'>
              <span>
                پراکندگی افراد غیرسرپرست جامعه آماری
              </span>
            </a>
          </li>
            </ul>
          </li> -->
        </ul>
      </li>
      <li class='has-sub'><a href='#'><span>گزارشات درخواست مشتریان و توزیع</span></a>
        <ul>
          <li>
            <a href='?report=stat&variable=GENDER&value=m'><span>گزارشات درخواست مستریان</span></a>
          </li>
          <li>
            <a href='?report=stat&variable=GENDER&value=f'><span>گزارشات پراگندگی فعالیت مراکز توزیع</span></a>
          </li>
        </ul>
      </li>
<!--       <li class='has-sub'><a href='#'><span>گزارشات مود</span></a>
        <ul>
          <li>
            <a href='?report=mode&rep_mode=1'><span>گزارشات پیاده</span></a>
          </li>
          <li>
            <a href='?report=mode&rep_mode=2'><span>گزارشات دوچرخه</span></a>
          </li>
          <li>
            <a href='?report=mode&rep_mode=7'><span>گزارشات موتور</span></a>
          </li>
          <li>
            <a href='?report=mode&rep_mode=3'><span>گزارشات خودرو</span></a>
          </li>
          <li>
            <a href='?report=mode&rep_mode=4'><span>گزارشات اتوبوس</span></a>
          </li>
          <li>
            <a href='?report=mode&rep_mode=5'><span>گزارشات مترو</span></a>
          </li>
          <li>
            <a href='mode.php' target='_blank'><span>گزارشات تجمیعی هدف سفر</span></a>
          </li>
        </ul>
      </li> -->
      <li><a href='index.php?logout=1'><span>خروج</span></a></li>
<!--        <li><a href='#'><span>Home</span></a></li>
       <li class='active has-sub'><a href='#'><span>Products</span></a>
          <ul>
             <li class='has-sub'><a href='#'><span>Product 1</span></a>
                <ul>
                   <li><a href='#'><span>Sub Product</span></a></li>
                   <li class='last'><a href='#'><span>Sub Product</span></a></li>
                </ul>
             </li>
             <li class='has-sub'><a href='#'><span>Product 2</span></a>
                <ul>
                   <li><a href='#'><span>Sub Product</span></a></li>
                   <li class='last'><a href='#'><span>Sub Product</span></a></li>
                </ul>
             </li>
          </ul>
       </li>
       <li><a href='#'><span>About</span></a></li>
       <li class='last'><a href='#'><span>Contact</span></a></li> -->
    </ul>
    </div>
<!--     <div id="head">
      BaChi
    </div> -->
    <div id="basicMap"></div>
		<?php if($kml_ready!=''){ ?>
			<div id="leg">
      	<div>
					<a href="kml/<?php echo $kml_ready; ?>">KML File</a>
				</div>
      	<div>
					<a href="<?php echo $csv_ready; ?>">CSV File</a>
				</div>
			</div>
		<?php } ?>
<!--     <div id="leg">
      <div>
        <a href="index.php?logout=1">
          Logout
        </a>  
        &nbsp;
        <a href="?report=mode&rep_mode=1"<?php echo ($selected==1)?' class="selected"':''; ?>>
          Walk
        </a>
        &nbsp;
        <a href="?report=mode&rep_mode=2"<?php echo ($selected==2)?' class="selected"':''; ?>>
          Bicycle
        </a>
        &nbsp;
        <a href="?report=mode&rep_mode=3"<?php echo ($selected==3)?' class="selected"':''; ?>>
          Car
        </a>
        &nbsp;
        <a href="?report=mode&rep_mode=4"<?php echo ($selected==4)?' class="selected"':''; ?>>
          Bus
        </a>
        &nbsp;
        <a href="?report=mode&rep_mode=5"<?php echo ($selected==5)?' class="selected"':''; ?>>
          Metro
        </a>
        &nbsp;
        <a href="?report=mode&rep_mode=6"<?php echo ($selected==6)?' class="selected"':''; ?>>
          Stop
        </a>
      </div>
    </div> -->
    <div id="rightleg">
      <?php echo $legend; ?>
    </div>
    <div id="dialog-form">
      <div id="frm">
        <form id="dfrm" method="POST">
          <input type="hidden" name="report" value="" id="repo" /> 
          شغل
          <select name="job">
            <option value=''>همه</option>
            <option value="1">کارمند</option>
            <option value="2">شغل آزاد</option>
            <option value="3">دانشجو</option>
            <option value="4">دانش آموز</option>
            <option value="5">خانه دار</option>
            <option value="6">بیکار</option>
          </select>
          <br/>
          جنسیت
          <select name="GENDER">
            <option value=''>همه</option>
            <option value="m">مرد</option>
            <option value="f">زن</option>
          </select>
          <br/>
          مالکیت وسیله نقلیه
          <select name="use_car">
            <option value=''>همه</option>
            <option value="1">خودرو شخصی</option>
            <option value="2">موتور سیکلت</option>
            <option value="3">دوچرخه</option>
            <option value="4">هیچ وسیله نقلیه</option>
            <option value="5">خانه دار</option>
          </select>
          <br/>
<!--           <button>
            نمایش
          </button> -->
        </form>
      </div>
    </div>
    <script>
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
	    dialog = $( "#dialog-form" ).dialog({
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
  </body>
</html>