<?php
function clearProcess(){
  global $my;
  $query = "truncate table user_stops";
  $my->query($query);
  $query = "truncate table track_modes";
  $my->query($query);
  $query = "truncate table mantaghe_tolid_jazb";
  $my->query($query);
  $query = "update track set travel_mode=0,user_travel_mode=0";
  $my->query($query);
}
function proccessStops($user_id){
  global $my;
  global $mantaghe_table;
  $regdate = '';
  $query = "select regdate from `user_stops` where `user_id` = $user_id order by `regdate` desc limit 1";
  if($res = $my->query($query)){
    if($r = $res->fetch_assoc()){
      $regdate = $r['regdate'];
    }
  }
  $mantaghe_id = 0;
  $is_home = 0;
  $stops = array();
  $mantaghe = array();
  $query = "SELECT regtime r,ST_AsText(ushape) p,id FROM `track` `t` WHERE travel_mode = 0 and user_id=$user_id /*and USER_SPEED>0*/ and TIME_TO_SEC(TIMEDIFF((select regtime from track where user_id=t.user_id /*and USER_SPEED>0*/ and id>t.id limit 1),t.regtime))/60>=15".(($regdate!='')?" and regtime > '$regdate'":'');
  //echo $query."<br/>\n";
  if($res = $my->query($query)){
    while($r = $res->fetch_assoc()){
      $query = "insert into `user_stops` (user_id,regdate,ushape,track_id) values ($user_id,'".$r['r']."',ST_GeomFromText('".$r['p']."'),".$r['id'].")";
      //echo $query."<br/>\n";
      if($my->query($query)){
        $us_id = $my->insert_id;
        $query = "SELECT $mantaghe_table".".id mid FROM `user_stops` left join $mantaghe_table on (ST_CONTAINS(shape,ushape)=1) where `user_stops`.`id` = ".$us_id;
        //echo $query."<br/>\n";
        if($res1 = $my->query($query)){
          if($r1 = $res1->fetch_assoc()){
            $query = "update `user_stops` set `mantaghe_id` = ".$r1['mid']." where `id` = ".$us_id;
            //echo $query."<br/>\n";
            $mantaghe_id = $r1['mid'];
            $my->query($query);
          }
        }
        $query = "SELECT (select shape_addr from users where id=user_id ) sp,st_distance(ushape,(select shape_addr from users where id=user_id ))*100000 ds FROM `user_stops` where `id` = ".$us_id;
        //echo $query."<br/>\n";
        if($res1 = $my->query($query)){
          if($r1 = $res1->fetch_assoc()){
            if($r1['ds']<=100 && trim($r1['sp'])!=''){
              $query = "update `user_stops` set `is_home` = 1 where `id` = ".$us_id;
              //echo $query."<br/>\n";
              $is_home = 1;
              $my->query($query);
            }
          }
        }
        if($mantaghe_id>0){
          $stops[] = array(
            "mantaghe_id" => $mantaghe_id,
            "is_home"=>$is_home
          );
        }
      }
    }
    foreach($stops as $i=>$stop){
      if(!isset($mantaghe[$stop['mantaghe_id']])){
        $mantaghe[$stop['mantaghe_id']] = array(
          "tolid" => 0,
          "jazb" => 0
        );
      }
      if((isset($stops[$i-1]) && $stops[$i-1]['is_home']==1)||(isset($stops[$i+1]) && $stops[$i+1]['is_home']==1)){
        $mantaghe[$stop['mantaghe_id']]['tolid']++;
        $mantaghe[$stop['mantaghe_id']]['tolid']++;
      }else{
        $mantaghe[$stop['mantaghe_id']]['tolid']++;
        $mantaghe[$stop['mantaghe_id']]['jazb']++;
      }
    }
    foreach($mantaghe as $mantaghe_id=>$tj){
      $query = "select id from mantaghe_tolid_jazb where user_id=$user_id and mantaghe_id=$mantaghe_id";
      //echo $query."<br/>\n";
      if($res = $my->query($query)){
        if($res->num_rows>0){
          if($r = $res->fetch_assoc()){
            $query = "update mantaghe_tolid_jazb set tolid = tolid + ".$tj['tolid'].",jazb = jazb + ".$tj['jazb']." where id = ".$r['id'];
            //echo $query."<br/>\n";
            $my->query($query);
          }
        }else{
          $query = "insert into mantaghe_tolid_jazb (user_id,mantaghe_id,tolid,jazb) values ($user_id,$mantaghe_id,".$tj['tolid'].",".$tj['jazb'].")";
          //echo $query."<br/>\n";
          $my->query($query);
        }
      }
    }
  }
}
function addStop($user_id,$regdate,$ushape,$trac_id){
  global $mantaghe_table;
  global $my;
  $query = "insert into `user_stops` (user_id,regdate,ushape,track_id) values ($user_id,'$regdate',ST_GeomFromText('$ushape'),$trac_id)";
  //echo $query."<br/>\n";
  if($my->query($query)){
    $us_id = $my->insert_id;
    $query = "SELECT $mantaghe_table".".id mid FROM `user_stops` left join $mantaghe_table on (ST_CONTAINS(shape,ushape)=1) where `user_stops`.`id` = ".$us_id;
    //echo $query."<br/>\n";
    if($res1 = $my->query($query)){
      if($r1 = $res1->fetch_assoc()){
        $query = "update `user_stops` set `mantaghe_id` = ".$r1['mid']." where `id` = ".$us_id;
        //echo $query."<br/>\n";
        $mantaghe_id = $r1['mid'];
        $my->query($query);
      }
    }
    $query = "SELECT (select shape_addr from users where id=user_id ) sp,st_distance(ushape,(select shape_addr from users where id=user_id ))*100000 ds FROM `user_stops` where `id` = ".$us_id;
    //echo $query."<br/>\n";
    if($res1 = $my->query($query)){
      if($r1 = $res1->fetch_assoc()){
        if($r1['ds']<=100 && trim($r1['sp'])!=''){
          $query = "update `user_stops` set `is_home` = 1 where `id` = ".$us_id;
          //echo $query."<br/>\n";
          $is_home = 1;
          $my->query($query);
        }
      }
    }
    if($mantaghe_id>0){
      return array(
        "mantaghe_id" => $mantaghe_id,
        "is_home"=>$is_home
      );
    }else{
      return FALSE;
    }

  }
}
function countTolid($stops){
  global $my;
  $mantaghe = array();
  foreach($stops as $i=>$stop){
    if(!isset($mantaghe[$stop['mantaghe_id']])){
      $mantaghe[$stop['mantaghe_id']] = array(
        "tolid" => 0,
        "jazb" => 0
      );
    }
    if((isset($stops[$i-1]) && $stops[$i-1]['is_home']==1)||(isset($stops[$i+1]) && $stops[$i+1]['is_home']==1)){
      $mantaghe[$stop['mantaghe_id']]['tolid']++;
      $mantaghe[$stop['mantaghe_id']]['tolid']++;
    }else{
      $mantaghe[$stop['mantaghe_id']]['tolid']++;
      $mantaghe[$stop['mantaghe_id']]['jazb']++;
    }
  }
  foreach($mantaghe as $mantaghe_id=>$tj){
    $query = "select id from mantaghe_tolid_jazb where user_id=$user_id and mantaghe_id=$mantaghe_id";
    //echo $query."<br/>\n";
    if($res = $my->query($query)){
      if($res->num_rows>0){
        if($r = $res->fetch_assoc()){
          $query = "update mantaghe_tolid_jazb set tolid = tolid + ".$tj['tolid'].",jazb = jazb + ".$tj['jazb']." where id = ".$r['id'];
          //echo $query."<br/>\n";
          $my->query($query);
        }
      }else{
        $query = "insert into mantaghe_tolid_jazb (user_id,mantaghe_id,tolid,jazb) values ($user_id,$mantaghe_id,".$tj['tolid'].",".$tj['jazb'].")";
        //echo $query."<br/>\n";
        $my->query($query);
      }
    }
  }
}
function proccessStopsNew($user_id){
  global $my;
  global $mantaghe_table;
  global $walk;
  global $stop_max_distance;
  $stops = array();

  $regdate = '';
  $query = "select regdate from `user_stops` where `user_id` = $user_id order by `regdate` desc limit 1";
  if($res = $my->query($query)){
    if($r = $res->fetch_assoc()){
      $regdate = $r['regdate'];
    }
  }
  $mantaghe_id = 0;
  $is_home = 0;
  $stops = array();
  $mantaghe = array();
  $contin = TRUE;
  $sid = 0;
  while($contin){
    $query = "SELECT regtime r,ST_AsText(ushape) p,id FROM `track` `t` WHERE travel_mode = 0 and user_id=$user_id and USER_SPEED=0 and `id` > $sid".(($regdate!='')?" and regtime > '$regdate'":'')." limit 1";
  //   echo $query."<br/>\n";
    if($res = $my->query($query)){
      $contin = ($res->num_rows==0);
      if($r = $res->fetch_assoc()){
        $id = $r['id'];
        $rgt = $r['r'];
        $drgt = explode(' ',$rgt);
        $drgt = $drgt[0];
        $shp = $r['p'];
        $query = "SELECT max(st_distance(`t`.`ushape`,ST_GeomFromText('$shp'))) mds,id FROM `track` `t` WHERE travel_mode = 0 and user_id=$user_id and USER_SPEED<=$walk and `id`>$id and TIME_TO_SEC(TIMEDIFF(`t`.`regtime`,'$rgt'))/60>=20 and date(t.regtime)='$drgt' order by id desc limit 1";
  //       echo $query."<br/>\n";
        if($res = $my->query($query)){
          if($r = $res->fetch_assoc()){
            $mds = $r['mds'];
            if($mds<=$stop_max_distance){
              //Found STOP
  //             echo "STOP<br/>";
              $sid = $r['id'];
              $tmp = addStop($user_id,$rgt,$shp,$id);
              if($tmp!==FALSE){
                $stops[] = $tmp;
              }
            }
          }
        }
      }
    }
  }
  countTolid($stops);
}
function getDistance($user_id,$start,$end){
  global $my;
  $dis = 0;
  $idd = $start;
  $is_end = FALSE;
  while(!$is_end){
    $iddd = $idd;
    $query = "select id from track where `USER_ID` = $user_id and `id` > $idd and `id` <=$end order by `id` limit 1";
    if($res = $my->query($query)){
      if($r = $res->fetch_assoc()){
        $iddd = $r['id'];
      }
    }
    $is_end = ($iddd==$idd);
    if(!$is_end){
      $query = "select st_distance(A.ushape,B.ushape) ds from track A left join track B on (A.id!=B.id) where A.id=$idd and B.id=$iddd ";
      if($res = $my->query($query)){
        if($r = $res->fetch_assoc()){
          $dis += (float)$r['ds'];
        }
      }
    }
    $idd = $iddd;
  }
  return $dis;
}
function processSpeed($user_id,$speed,$shetab,$first_id,$last_id){
  $travel_mode = 6;
  global $walk;
  global $bicyle;
  global $car;
  global $bus;
  global $metro;
  global $min_bus_distance;
  global $min_metro_distance;
  global $my;
  if($speed < $walk){
    $travel_mode = 1;
//   }else if($speed >= $walk && $speed < $car){
//     $travel_mode = 2;
  }else if($speed >= $walk){
    $travel_mode = 3;
  }
  if($travel_mode==3){
    $stop_id = ($first_id>0)?$first_id:$last_id;
    $query = "SELECT st_distance(ushape,shape)*100000 distance,metro_stop.id bid FROM `track` left join `metro_stop`on (st_distance(ushape,shape)*100000<=$min_metro_distance) WHERE track.id=$stop_id";
    if($res=$my->query($query)){
      if($res->num_rows>0){
        $stt = '';
        if($last_id>0){
          $stt = " and `track`.`id` > $last_id ";
        }
        $query = "SELECT ST_Crosses(shape, st_buffer(ushape,0.004)) inter,track.id tid,metro_line.id mid FROM `track` inner join metro_line on (ST_Crosses(shape, st_buffer(ushape,0.004))=1) where track.id < $stop_id $stt and user_id = $user_id";
        if($res=$my->query($query)){
          if($res->num_rows>0){
            $travel_mode = 5;
          }
        }
//         echo "$user_id , $first_id ,$last_id<br/>\n";
      }else{
        $query = "SELECT st_distance(ushape,shape)*100000 distance,bus_stop.id bid FROM `track` left join `bus_stop`on (st_distance(ushape,shape)*100000<=$min_bus_distance) WHERE track.id=$stop_id";
        if($res=$my->query($query)){
          if($res->num_rows>0){
            $travel_mode = 4;
          }
        }
      }
    }
  }
  $dis = getDistance($user_id,$first_id,$last_id);
  $query = "insert into `track_mode` (`user_id`,`track_start_id`, `track_end_id`,`mode`,`dis`) values ";
  $query .= "($user_id,$first_id,$last_id,$travel_mode,$dis)";
  $my->query($query);
  return $travel_mode;
}
function proccessData(){
  echo "START <br/>\n";
  global $my;
  $query = "SELECT ID from users where id = 222 order by ID";
  echo $query."<br/>\n";
  if($ures=$my->query($query)){
    while($ur=$ures->fetch_assoc()){
      $user_id = $ur['ID'];
//       proccessStops($user_id);
      $query = "SELECT * FROM `track` WHERE `USER_ID` = $user_id and `travel_mode`=0 order by id limit 1 ";
      echo $query."<br/>\n";
      if($res = $my->query($query)){
        if($r = $res->fetch_assoc()){ 
          echo $r['USER_SPEED']."<br/>\n";
          print_r($r);
          if($r['USER_SPEED']>0){
            $first_id = $r['id'];
            $query = "SELECT * FROM `track` WHERE `USER_ID` = $user_id and `USER_SPEED`<=0 and `travel_mode`=0 order by id limit 1 ";
            echo $query."<br/>\n";
            if($res = $my->query($query)){
              if($r=$res->fetch_assoc()){
                $last_id = $r['id'];
                if($last_id>$first_id+1){
                  $query = "SELECT max(USER_SPEED)*3.6 um,max(SHETAB) sm FROM `track` WHERE `USER_ID` = $user_id and `id`>=$first_id and `id`<$last_id and `travel_mode`=0 order by id";
                  echo $query."<br/>\n";
                  if($res=$my->query($query)){
                    if($r=$res->fetch_assoc()){
                      $speed = $r['um'];
                      $shetab = $r['sm'];
                      $travel_mode = processSpeed($user_id,$speed,$shetab,-1,$last_id);
                      $query = "update `track` set `travel_mode` = $travel_mode where `id`>=$first_id and `id`<$last_id and `travel_mode`=0";
//                       $my->query($query);
                      echo "fase = 1 , query = $query <hr/>";
                    }
                  }
                }
              }
            }
          }
        }
      }
      /*
      $row_nums = 1;
      while($row_nums>0){
        $row_nums = 0;
        $last_id = -1;
        $first_id = -1;
        $query = "SELECT * FROM `track` WHERE `USER_ID` = $user_id and `user_speed`<=0 and `travel_mode`=0 order by id limit 2 ";
        echo $query."<br/>\n";
        if($res = $my->query($query)){
          $row_nums = $res->num_rows;
          while($r = $res->fetch_assoc()){
            if($first_id == -1){
              $first_id = $r['id'];
            }else{
              $last_id = $r['id'];
            }
          }
          $query = "update `track` set `travel_mode` = 6 where `id` in ($first_id)";
//           $my->query($query);
          echo "fase = 2 , query = $query <br/>";
          if($last_id>$first_id+1){
            $query = "SELECT max(USER_SPEED)*3.6 um,max(SHETAB) sm FROM `track` WHERE `USER_ID` = $user_id and `id`>$first_id and `id`<$last_id and `travel_mode`=0 order by regtime";
            echo $query."<br/>\n";
            if($res = $my->query($query)){
              if($r = $res->fetch_assoc()){
                $speed = $r['um'];
                $shetab = $r['sm'];
                $travel_mode = processSpeed($user_id,$speed,$shetab,$first_id,$last_id);
                $query = "update `track` set `travel_mode` = $travel_mode where `id`>$first_id and `id`<$last_id and `travel_mode`=0";
//                 $my->query($query);
                echo "fase = 3 , query = $query <hr/>";
              }
            }
          }
        }
      }
      $query = "SELECT * FROM `track` WHERE `USER_ID` = $user_id and `travel_mode`=0 order by id desc limit 1 ";
      echo $query."<br/>\n";
      if($res = $my->query($query)){
        if($r = $res->fetch_assoc()){
          $first_id = $r['id'];
          $query = "SELECT * FROM `track` WHERE `USER_ID` = $user_id and `travel_mode`=0 order by id desc limit 1 ";
          echo $query."<br/>\n";
          if($res = $my->query($query)){
            if($r = $res->fetch_assoc()){
              $last_id = $r['id'];
              if($last_id>$first_id+1){
                $query = "SELECT max(USER_SPEED)*3.6 um,max(SHETAB) sm FROM `track` WHERE `USER_ID` = $user_id and `id`>$first_id and `id`<=$last_id and `travel_mode`=0 order by id";
                echo $query."<br/>\n";
                if($res=$my->query($query)){
                  if($r=$res->fetch_assoc()){
                    $speed = $r['um'];
                    $shetab = $r['sm'];
                    $travel_mode = processSpeed($user_id,$speed,$shetab,$first_id,-1);
                    $query = "update `track` set `travel_mode` = $travel_mode where `id`>$first_id and `id`<=$last_id and `travel_mode`=0";
//                     $my->query($query);
                    echo "fase = 4 , query = $query <hr/>";
                  }
                }
              }
            }
          }
        }
      }      
      */
    }
  }
}
function test(){
  global $my;
  $query = "SELECT ID from users order by ID";
  echo $query."<br/>\n";
  if($ures=$my->query($query)){
    while($ur=$ures->fetch_assoc()){
      $user_id = $ur['ID'];
      $query = "SELECT * FROM `track` WHERE `USER_ID` = $user_id and `travel_mode`=0 order by id limit 1 ";
      echo $query."<br/>\n";
      if($res = $my->query($query)){
        if($r = $res->fetch_assoc()){ 
          if($r['USER_SPEED']>0){
            $first_id = $r['id'];
            $query = "SELECT * FROM `track` WHERE `USER_ID` = $user_id and `USER_SPEED`<=0 and `travel_mode`=0 order by id limit 1 ";
            echo $query."<br/>\n";
            if($res = $my->query($query)){
              if($r=$res->fetch_assoc()){
                $last_id = $r['id'];
                if($last_id>$first_id+1){
                  $query = "SELECT max(USER_SPEED)*3.6 um,max(SHETAB) sm FROM `track` WHERE `USER_ID` = $user_id and `id`>=$first_id and `id`<$last_id and `travel_mode`=0 order by id";
                  echo $query."<br/>\n";
                  if($res=$my->query($query)){
                    if($r=$res->fetch_assoc()){
                      $speed = $r['um'];
                      $shetab = $r['sm'];
                      $travel_mode = processSpeed($user_id,$speed,$shetab,-1,$last_id);
                      $query = "update `track` set `travel_mode` = $travel_mode where `USER_ID` = $user_id and `id`>=$first_id and `id`<=$last_id";
                      echo $query."<br/>\n";
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
        echo $query."<br/>\n";
        if($res = $my->query($query)){
          $row_nums = $res->num_rows;
          while($r = $res->fetch_assoc()){
            if($first_id == -1){
              $first_id = $r['id'];
            }else{
              $last_id = $r['id'];
            }
          }
//           $query = "update `track` set `travel_mode` = 6 where `id` in ($first_id)";
//           $my->query($query);
          if($last_id>$first_id+1){
            $query = "SELECT max(USER_SPEED)*3.6 um,max(SHETAB) sm FROM `track` WHERE `USER_ID` = $user_id and `id`>$first_id and `id`<$last_id and `travel_mode`=0 order by regtime";
            echo $query."<br/>\n";
            if($res = $my->query($query)){
              if($r = $res->fetch_assoc()){
                $speed = $r['um'];
                $shetab = $r['sm'];
                $travel_mode = processSpeed($user_id,$speed,$shetab,$first_id,$last_id);
                $query = "update `track` set `travel_mode` = $travel_mode where `USER_ID` = $user_id and `id`>=$first_id and `id`<=$last_id";
                echo $query."<br/>\n";
                $my->query($query);
              }
            }
          }else{
            $row_nums = 0;
          }
        }
      }
      $query = "SELECT * FROM `track` WHERE `USER_ID` = $user_id and `user_speed`<=0 and `travel_mode`>0 order by id desc limit 1 ";
      echo $query."<br/>\n";
      if($res = $my->query($query)){
        if($r = $res->fetch_assoc()){
          $first_id = $r['id'];
          $query = "SELECT * FROM `track` WHERE `USER_ID` = $user_id and `travel_mode`=0 order by id desc limit 1 ";
          echo $query."<br/>\n";
          if($res = $my->query($query)){
            if($r = $res->fetch_assoc()){
              $last_id = $r['id'];
              if($last_id>$first_id+1){
                $query = "SELECT max(USER_SPEED)*3.6 um,max(SHETAB) sm FROM `track` WHERE `USER_ID` = $user_id and `id`>$first_id and `id`<=$last_id and `travel_mode`=0 order by id";
                echo $query."<br/>\n";
                if($res=$my->query($query)){
                  if($r=$res->fetch_assoc()){
                    $speed = $r['um'];
                    $shetab = $r['sm'];
                    $travel_mode = processSpeed($user_id,$speed,$shetab,$first_id,-1);
                    $query = "update `track` set `travel_mode` = $travel_mode where `USER_ID` = $user_id and `id`>=$first_id and `id`<=$last_id";
                    echo $query."<br/>\n";
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