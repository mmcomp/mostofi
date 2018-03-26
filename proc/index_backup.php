<?php
$dbok = TRUE;
$my = new mysqli('localhost','mirsamie_track','Track@159951','mirsamie_track');
if($my->connect_errno){
  $dbok = FALSE;
}
//----ADD-SHAPE--------------START
/*
include('shpParser.php');
$shp = new shpParser;
$shp->load('../shp/points_layer_467_user_26_9755.shp');
$data = $shp->getShapeData();
echo "data count = ".count($data)."<hr/>";
foreach($data as $point){
  $query = "insert into `metro_stop` (`lon`,`lat`,`shape`) values ('".($point['geom']['bbox']['xmin'])."','".($point['geom']['bbox']['ymin'])."',ST_GeomFromText('".($point['geom']['wkt'])."'))";
  $my->query($query);
}
*/
//----ADD-SHAPE----------------END
/*
$date = DateTime::createFromFormat('j-M-y H.i.s', '10-MAY-17 04.25.40');
echo $date->format('Y-m-d H:i:s');
*/
//----UPDATE-REGTIME---------START
/*
if($dbok){
  $query = "select id,reg_time from track order by id";
  if($res = $my->query($query)){
    while($r = $res->fetch_assoc()){
      $rd = substr($r['reg_time'],0,18);
      if($rd!=''){
        $date = DateTime::createFromFormat('j-M-y H.i.s', $rd);
        $dt = $date->format('Y-m-d H:i:s');
        $query = "update track set regtime='$dt' where id = ".$r['id'];
        $my->query($query);
      }
    }
  }else{
    echo "query error:".$my->error;
  }
}else{
  echo "DB ERROR:".$my->error;
}
*/
//----UPDATE-REGTIME-----------END
//----UPDATE-SHPEPIONT-------START
/*
if($dbok){
  $query = "select id,lon,lat from track order by id";
  if($res = $my->query($query)){
    while($r = $res->fetch_assoc()){
      $query = "update track set ushape=ST_GeomFromText('POINT(".$r['lon']." ".$r['lat'].")') where id = ".$r['id'];
//       echo $query."<br/>";
      $my->query($query);
    }
  }else{
    echo "query error:".$my->error;
  }
}else{
  echo "DB ERROR:".$my->error;
}
*/
//----UPDATE-SHAPEPOINT--------END
//----UPDATE-PROCCESS--------START

function processSpeed($speed,$shetab,$first_id,$last_id){
  $travel_mode = 6;
  $walk = 7;
  $bicyle = 20;
  $car = 40;
  $bus = 40;
  $metro = 60;
  $min_bus_distance = 5;
  $min_metro_distance = 1;
  global $my;
  if($speed < $walk){
    $travel_mode = 1;
  }else if($speed >= $walk && $speed < $bicyle){
    $travel_mode = 2;
  }else if($speed >= $bicyle){
    $travel_mode = 3;
  }
  if($travel_mode==3){
    $stop_id = ($first_id>0)?$first_id:$last_id;
    $query = "SELECT st_distance(ushape,shape)*1609.34 distance,metro_stop.id bid FROM `track` left join `metro_stop`on (st_distance(ushape,shape)*1609.34<=$min_metro_distance) WHERE track.id=$stop_id";
//     echo $query."<br/>";
    if($res=$my->query($query)){
      if($res->num_rows>0){
        $travel_mode = 5;
      }else{
        $query = "SELECT st_distance(ushape,shape)*1609.34 distance,bus_stop.id bid FROM `track` left join `bus_stop`on (st_distance(ushape,shape)*1609.34<=$min_bus_distance) WHERE track.id=$stop_id";
    //     echo $query."<br/>";
        if($res=$my->query($query)){
          if($res->num_rows>0){
            $travel_mode = 4;
          }
        }
      }
    }
  }
  return $travel_mode;
}
function proccessData(){
  global $my;
//   $user_id = "221";
  $query = "SELECT ID from users order by ID";
  if($ures=$my->query($query)){
    while($ur=$ures->fetch_assoc()){
//       var_dump($ur);
      $user_id = $ur['ID'];
      $query = "SELECT * FROM `track` WHERE `USER_ID` = $user_id and `travel_mode`=0 order by id limit 1 ";
//       echo $query."<br/>";
      if($res = $my->query($query)){
        if($r = $res->fetch_assoc()){
//           echo "first speed :[".$r['id']."] '".$r['USER_SPEED']."'<br/>";     
          if($r['USER_SPEED']>0){
            $first_id = $r['id'];
            $query = "SELECT * FROM `track` WHERE `USER_ID` = $user_id and `USER_SPEED`<=0 and `travel_mode`=0 order by id limit 1 ";
//             echo $query."<br/>";
            if($res = $my->query($query)){
              if($r=$res->fetch_assoc()){
                $last_id = $r['id'];
                if($last_id>$first_id+1){
                  $query = "SELECT max(USER_SPEED)*3.6 um,max(SHETAB) sm FROM `track` WHERE `USER_ID` = $user_id and `id`>=$first_id and `id`<$last_id and `travel_mode`=0 order by id";
//                   echo $query."<br/>";
                  if($res=$my->query($query)){
                    if($r=$res->fetch_assoc()){
                      $speed = $r['um'];
                      $shetab = $r['sm'];
                      $travel_mode = processSpeed($speed,$shetab,-1,$last_id);
                      $query = "update `track` set `travel_mode` = $travel_mode where `id`>=$first_id and `id`<$last_id and `travel_mode`=0";
//                       echo $query."<br/>";
                      $my->query($query);
                    }
                  }
                }
              }
            }
          }
        }
      }
      $row_nums = 1;

      while($row_nums>0){
        $row_nums = 0;
        $last_id = -1;
        $first_id = -1;
        $query = "SELECT * FROM `track` WHERE `USER_ID` = $user_id and `user_speed`<=0 and `travel_mode`=0 order by id limit 2 ";
//         echo $query." : ";
        if($res = $my->query($query)){
          $row_nums = $res->num_rows;
//           echo 'row_num = '.$row_nums.'<br/>';
          while($r = $res->fetch_assoc()){
            if($first_id == -1){
              $first_id = $r['id'];
            }else{
              $last_id = $r['id'];
            }
          }
          $query = "update `track` set `travel_mode` = 6 where `id` in ($first_id)";
//           echo $query."<br/>";
          $my->query($query);
          if($last_id>$first_id+1){
            $query = "SELECT max(USER_SPEED)*3.6 um,max(SHETAB) sm FROM `track` WHERE `USER_ID` = $user_id and `id`>$first_id and `id`<$last_id and `travel_mode`=0 order by regtime";
//             echo $query."<br/>";
            if($res = $my->query($query)){
              if($r = $res->fetch_assoc()){
                $speed = $r['um'];
                $shetab = $r['sm'];
                $travel_mode = processSpeed($speed,$shetab,$first_id,$last_id);
                $query = "update `track` set `travel_mode` = $travel_mode where `id`>$first_id and `id`<$last_id and `travel_mode`=0";
//                 echo $query."<br/>";
                $my->query($query);
              }
            }
          }
        }
      }
      $query = "SELECT * FROM `track` WHERE `USER_ID` = $user_id and `travel_mode`=6 order by id desc limit 1 ";
//       echo $query."<br/>";
      if($res = $my->query($query)){
        if($r = $res->fetch_assoc()){
          $first_id = $r['id'];
//           echo "last stop :[".$first_id."] ".$r['USER_SPEED']." <br/>";
          $query = "SELECT * FROM `track` WHERE `USER_ID` = $user_id and `travel_mode`=0 order by id desc limit 1 ";
//           echo $query."<br/>";
          if($res = $my->query($query)){
            if($r = $res->fetch_assoc()){
              $last_id = $r['id'];
//               echo "last point : [$last_id]".$r['USER_SPEED']."<br/>";
              if($last_id>$first_id+1){
                $query = "SELECT max(USER_SPEED)*3.6 um,max(SHETAB) sm FROM `track` WHERE `USER_ID` = $user_id and `id`>$first_id and `id`<=$last_id and `travel_mode`=0 order by id";
//                 echo $query."<br/>";
                if($res=$my->query($query)){
                  if($r=$res->fetch_assoc()){
                    $speed = $r['um'];
                    $shetab = $r['sm'];
                    $travel_mode = processSpeed($speed,$shetab,$first_id,-1);
                    $query = "update `track` set `travel_mode` = $travel_mode where `id`>$first_id and `id`<=$last_id and `travel_mode`=0";
//                     echo $query."<br/>";
                    $my->query($query);
                  }
                }
              }
            }
          }
        }
      }      
    }
  }
}
if($dbok){
  proccessData();
}
//----UPDATE-PROCCESS----------END