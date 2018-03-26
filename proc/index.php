<?php
require('db.php');
include('proccess.php');
$mantaghe_table = 'nahiye';//'manategh';
$walk = 9;
$bicyle = 16;
$car = 40;
$bus = 40;
$metro = 60;
$min_bus_distance = 5;
$min_metro_distance = 1;
$stop_max_distance =20;
$stop_max_time =10;
if($dbok){
//   clearProcess();
  proccessData();
//   test();
//   proccessStopsNew(222);
}
$my->close();