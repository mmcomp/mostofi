<?php
include('class/kml.php');
$kml = new kml;
$tmp = array(15 => 20,16 => 100,17 => 30,18 => 40,19 => 60,20 => 63,20 => 20,21 => 100,22 => 30,23 => 40,24 => 60,25 => 63);
$f = $kml->getReport('test',$tmp);
echo "<a href=\"/kml/$f\">Download</a>";