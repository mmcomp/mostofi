<?php
/*
require('db.php');
$table = "sh_nahie";


$kml_file = '../shp/navahi.kml';
$data = simplexml_load_file($kml_file);


for($i = 0;$i < count($data->Document->Folder->Placemark);$i++){

  if(isset($data->Document->Folder->Placemark[$i]->MultiGeometry)){
    $cords = ''.$data->Document->Folder->Placemark[$i]->MultiGeometry->Polygon->outerBoundaryIs->LinearRing->coordinates.'';
  }else if(isset($data->Document->Folder->Placemark[$i]->Polygon)){
    $cords = ''.$data->Document->Folder->Placemark[$i]->Polygon->outerBoundaryIs->LinearRing->coordinates.'';
  }
  $tmp_cords = str_replace(' ','|',$cords);
  $tmp_cords = str_replace(',',' ',$tmp_cords);
  $tmp_cords = str_replace('|',',',$tmp_cords);

  $wkt = 'POLYGON((';
  $wkt .= $tmp_cords;
  $wkt .= '))';
  $query = "insert into `$table` (`lon`,`lat`,`shape`) values ('0','0',ST_GeomFromText('$wkt'))";
  if($my->query($query)){   
  }else{
    echo $query."<hr/>\n";
  }           
}
*/

/*
$shape_file = "zone.shp";

include('shpParser.php');
$shp = new shpParser;
$shp->load('../shp/'.$shape_file);
$data = $shp->getShapeData();
echo "data count = ".count($data)."<hr/>";
foreach($data as $i=>$point){
  if($point['shapeType']['name']=='Polygon')
  {
    if(strpos($point['geom']['wkt'],'POLYGON(')===0)
      $point['geom']['wkt'] = str_replace('POLYGON(','POLYGON((',$point['geom']['wkt']).')';
  }
  $query = "insert into `$table` (`lon`,`lat`,`shape`) values ('".($point['geom']['bbox']['xmin'])."','".($point['geom']['bbox']['ymin'])."',ST_GeomFromText('".($point['geom']['wkt'])."'))";
  if($my->query($query)){   
  }else{
    echo $query."<hr/>\n";
  }
}
*/