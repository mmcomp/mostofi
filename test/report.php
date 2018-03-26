<?php
include('class/report.php');
$dbok = TRUE;
$my = new mysqli('localhost','mirsamie_track2','Track2@159951','mirsamie_track2');
if($my->connect_errno){
  $dbok = FALSE;
}
if($dbok){
  $rp = new report;
  $m = $rp->modeOnMantagheDate(1,'2017-04-01','2017-06-31');
  var_dump($m);
}