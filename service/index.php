<?php
include('./jdf.php');
function prepareOutput($r){
  $out = new stdClass;
  foreach($r as $key=>$value){
    $out->$key = $value;
  }
  return $out;
}
function getMantaghe($lon,$lat){
  //59.556206841560325,36.330129907176186
  $out = 0;
  global $my;
  $query = "SELECT `id` FROM `manategh` WHERE ST_CONTAINS(shape,point($lon,$lat))=1 ";
  if($res = $my->query($query)){
    if($r = $res->fetch_assoc()){
      $out = $r['id'];
    }
  }
  return $out;
}
if(!isset($_GET['function']) || !isset($_GET['params'])){
  die('Bad Request');
}
date_default_timezone_set("Asia/Tehran");
// echo strlen($_GET['params']);
// var_dump($_GET);
// var_dump($_SERVER['REQUEST_URI']);
$tmp= explode($_REQUEST['function'],$_SERVER['REQUEST_URI']);
// var_dump($tmp);
$function = $_REQUEST['function'];
$params = array();
$tmp_params = explode('/',$tmp[count($tmp)-1]);
foreach($tmp_params as $i=>$param){
  if($i>0)
    $params[] = urldecode($param);
}
$method = $_SERVER['REQUEST_METHOD'];
$dbok = TRUE;
$my = new mysqli('localhost','mirsamie_track','Track@159951','mirsamie_track');
if($my->connect_errno){
  $dbok = FALSE;
}else{
  $my->set_charset("utf8");
}
$output = 'json';
$sampleXml = file_get_contents('sample.xml');
$username = 'guest';
$password = 'guest';
if(isset($_SERVER['PHP_AUTH_USER'])){
  $username = $_SERVER['PHP_AUTH_USER'];
  $password = $_SERVER['PHP_AUTH_PW'];
}
// echo "$method\n $function\n ";
// var_dump($params);
//---------------------------------------------------------------------------------------
$out = array('items'=>array());
if($method=='GET'){
  if($function=='login'){
    if(count($params)==2){
      if($dbok){
        $query = "select `id`,`fname`,`lname`,`PASSWORD` from `users` where `USERNAME`='".$params[0]."'";
        if($res=$my->query($query)){
          if($r=$res->fetch_assoc()){
            if($r['PASSWORD']==$params[1]){
              $userData = prepareOutput($r);
              $tmp = array();
              foreach($userData as $key=>$value){
                $tmp[strtolower($key)]=$value;
              }
              $out['items'][] = $tmp;
            }
          }
        }
      }
    }
  }else if($function=='emtiaz'){
    if(count($params)==1){
      if($dbok){
        $query = "select `emtiaz` from `users` where `id`='".$params[0]."'";
        if($res=$my->query($query)){
          if($r=$res->fetch_assoc()){
            $userData = prepareOutput($r);
            $tmp = array();
            foreach($userData as $key=>$value){
              $tmp[strtolower($key)]=$value;
            }
            $out['items'][] = $tmp;
          }
        }
      }
    }
  }else if($function=='trackdates'){
//trackdates/{userid}/{all}
    if(count($params)>=1){
      if($dbok){
        $wer = ' and `travel_mode`!=0 and `user_travel_mode`=0 and date(`regtime`) not in (SELECT date(regtime) rg FROM `track` WHERE user_travel_mode!=0 and user_id='.$params[0].' group by date(regtime)) ';
        if(isset($params[1]) && $params[1]=='all'){
          $wer = ' and `travel_mode`!=0 and `user_travel_mode`!=0 and date(`regtime`)  in (SELECT date(regtime) rg FROM `track` WHERE user_travel_mode!=0 and user_id='.$params[0].' group by date(regtime)) ';
        }
        $query = "select date(`regtime`) `dt` from `track` where `USER_ID` = ".$params[0].$wer.' group by date(`regtime`) ';
//         echo $query;
        if($res=$my->query($query)){
          while($r=$res->fetch_assoc()){
//             var_dump($r);
            $r['pdt'] =jdate("l|Y/m/d",strtotime($r['dt']));
            $out['items'][] = prepareOutput($r);
          }
        }
      }
    }
  }else if($function=='stops'){
//track/{userid}/{today||date}  
    if(count($params)>=1){
      if($dbok){
        $date_where = '';
        if(!isset($params[1])){
          $params[1]='today';
        }
        if($params[1]=='today'){
          $date_where = " and date(`regdate`)='".date("Y-m-d")."' ";
        }else if(trim($params[1])!=''){
          $date_where = " and date(`regdate`)='".$params[1]."' ";
        }
        $query = "select `id`,st_astext(ushape) shp,regdate from `user_stops` where `USER_ID` = ".$params[0].$date_where.' and `tgoal` = 0 order by `id` ';
//         echo $query;
        if($res=$my->query($query)){
          while($r=$res->fetch_assoc()){
//             var_dump($r);
            $r['pregdate'] = jdate("Y/m/d",strtotime($r['regdate']));
            $tmp = explode(' ',$r['shp']);
            $lon = $tmp[0];
            $lat = $tmp[1];
            $lon = explode('(',$lon);
            $lon = $lon[1];
            $lat = explode(')',$lat);
            $lat = $lat[0];
            $r['lon'] = $lon;
            $r['lat'] = $lat;
            $out['items'][] = prepareOutput($r);
          }
        }
      }
    }
  }else if($function=='newtrack'){
//track/{userid}/{today||date}  
    if(count($params)>=1){
      if($dbok){
        $date_where = '';
        if(!isset($params[1])){
          $params[1]='today';
        }
        if($params[1]=='today'){
          $date_where = " and date(`regtime`)='".date("Y-m-d")."' ";
        }else if(trim($params[1])!=''){
          $date_where = " and date(`regtime`)='".$params[1]."' ";
        }
        $query = "select `id`,`USER_SPEED` `user_speed`, `LON` `lon`, `LAT` `lat`, `SHETAB` `shetab`, `ALTITUDE`, `regtime`, `travel_mode` `mode` from `track` where `USER_ID` = ".$params[0].$date_where.' order by `regtime`,`id` ';
//         echo $query;
        if($res=$my->query($query)){
          while($r=$res->fetch_assoc()){
//             var_dump($r);
            $r['pregtime'] = jdate("Y/m/d",strtotime($r['regtime']));
            $out['items'][] = prepareOutput($r);
          }
        }
      }
    }
  }else if($function=='track'){
//track/{userid}/{lon}/{lat}/{speed}/{shetab}/{altitude}  
    if(count($params)>=1){
      if($dbok){
        $query = "select `USER_SPEED`, `LON` `lon`, `LAT` `lat`, `SHETAB`, `ALTITUDE`, `regtime`, `travel_mode` `mode` from `track` where `USER_ID` = ".$params[0].' order by `id` ';
//         echo $query;
        if($res=$my->query($query)){
          while($r=$res->fetch_assoc()){
//             var_dump($r);
            $out['items'][] = prepareOutput($r);
          }
        }
      }
    }
  }else if($function=='reqdump'){
    if($dbok){
      $query = "insert into `reqdump` (`req`) values ('".mysql_escape_string(json_encode($_REQUEST))."')";
      echo $query;
      $my->query($query);

    }
  }else if($function=='ftrack'){
//ftrack/lon/lat  
    if(count($params)==2){
      if($dbok){
        $query = "insert into `test` (`lon`,`lat`,`regtime`) values ('".$params[0]."','".$params[1]."','".date("Y-m-d H:i:s")."')";
//         echo $query;
        $my->query($query);
        
      }
    }
  }else if($function=='wfs'){
    include('../panel/class/report.php');
      
    $output = 'xml';
    $dxml = '';
    if($username == 'rajhman' && $password == 'RaJhMan159951!'){
      if(count($params)>=1){
        $rep = $params[0];
        $rp = new report;
        if($rep=='mode' && isset($params[1])){
          $rep_mode = $params[1];
          $tmp = $rp->modeOnMantaghe($rep_mode);

          foreach($tmp as $id=>$result){
            $dxml .= '
              <Feature>
                <Val>Zone '.$id.'</Val>
                <Val>'.$id.'</Val>
                <Val>'.$rp->pols[$id].'</Val>
                <Val>'.$result.'</Val>
              </Feature>
            ';
          }
        }/*else if($rep == 'stat' &&  isset($params[1])){
        }*/else{
          $dxml = '
            <Feature>
              <Val>Error</Val>
              <Val>Wrong Parameters</Val>
              <Val></Val>
              <Val></Val>
            </Feature>
          ';
        }
      }
    }else{
      $dxml = '
        <Feature>
          <Val>Error</Val>
          <Val>Wrong Username or Password</Val>
          <Val></Val>
          <Val></Val>
        </Feature>
      ';
    }
    $sampleXml = str_replace('#ftr#',$dxml,$sampleXml);
  }
}else if($method == 'PUT'){
  if($function=='track'){
//track/{userid}/{lon}/{lat}/{speed}/{shetab}/{altitude}  
    if(count($params)==6){
      if($dbok){
        $query = "insert into `track` (`USER_ID`, `ushape`, `USER_SPEED`, `LON`, `LAT`, `SHETAB`, `ALTITUDE`, `regtime`, `travel_mode`) values ";
        $query .= "('".$params[0]."',POINT(".$params[1].",".$params[2]."),'".$params[3]."','".$params[1]."','".$params[2]."','".$params[4]."','".$params[5]."','".date("Y-m-d H:i:s")."',0)";
        if($my->query($query)){
          $out['items'][] = TRUE;
        }
      }
    }
  }else if($function=='mode'){
    //mode/{user_id}/{start_id}/{end_id}/{mode}/{answer}/{rahati}/{mobile_app}
    if(count($params)==7){
      if($dbok){
        $query = "update `track` set `user_travel_mode` = ".$params[3]." where `USER_ID` = ".$params[0]." and `id`>=".$params[1]." and id <=".$params[2];
//         echo $query;
        if($my->query($query)){
          $out['items'][] = TRUE;
        }

        $start = $params[1];
        $end = $params[2];
        $user_id = $params[0];
        
        $query = "update `track_mode` set `mode` =  ".$params[3].",answer = ".$params[4].",rahati = ".$params[5].",mobile_app = ".$params[6]." where user_id = $user_id and track_start_id = $start and track_end_id = $end";
        //(`user_id`,`track_start_id`, `track_end_id`,`mode`, `answer`, `rahati`, `mobile_app`,`dis`) values ";
        //$query .= "(".$params[0].",".$params[1].",".$params[2].",".$params[3].",".$params[4].",".$params[5].",".$params[6].",'".$dis."')";
        $my->query($query);
      }
    }
  }else if($function=='emtiaz'){
    if(count($params)==2){
      if($dbok){
        $query = "update `users` set `emtiaz` = `emtiaz` + " . $params[1] . " where `id`='".$params[0]."'";
//         echo $query."\n";
        if($res=$my->query($query)){
            $out['items'][] = TRUE;
        }
      }
    }
  }else if($function=='stops'){
    //mode/{id}/{tgoal}
    if(count($params)==2){
      if($dbok){
        $query = "update `user_stops` set `tgoal` = ".$params[1]." where `id` = ".$params[0];
//         echo $query;
        if($my->query($query)){
          $out['items'][] = TRUE;
        }
      }
    }
  }else if($function=='user'){
    //  0     1      2     3    4      5      6      7        8      9   10 11    12    13     14      15     16    17    18        19       20          21
    //fname,lname,gender,email,cell,address,work1,username,password,age,job,car,benzin,work2,school1,school2,shop1,shop2,use_car,sarparast,vasile,tedad_khanevade
//user/{fname}/{lname}/{gender}/{email}/{cell}/{address}/{work1}/{username}/{password}/{work2}
//     var_dump($params);
    if(count($params)==22){
      if($dbok){
        $fname = $params[0];
        $lname = $params[1];
        $gender = $params[2];
        $email = $params[3];
        $cell = $params[4];
        $username = $params[7];
        $password = $params[8];
        $age = $params[9];
        $job = $params[10];
        $car = $params[11];
        $benzin = $params[12];
        $use_car = $params[18];
        $sarparast = $params[19];
        $vasile = $params[20];
        $address = $params[5];
        $tedad_khanevade = $params[21];
        $add_arr = explode(',',$address);
        $addr_mantaghe_id = 0;
        $shape_addr = 'null';
        if(count($add_arr)==2){
          $addr_mantaghe_id = getMantaghe($add_arr[0],$add_arr[1]);
          $shape_addr = 'POINT('.$address.')';
        }
        $shop1 = $params[16];
        $add_arr = explode(',',$shop1);
        $shp1_mantaghe_id = 0;
        $shape_shp1 = 'null';
        if(count($add_arr)==2){
          $shp1_mantaghe_id = getMantaghe($add_arr[0],$add_arr[1]);
          $shape_shp1 = 'POINT('.$shop1.')';
        }
        $shop2 = $params[17];
        $add_arr = explode(',',$shop2);
        $shp2_mantaghe_id = 0;
        $shape_shp2 = 'null';
        if(count($add_arr)==2){
          $shp2_mantaghe_id = getMantaghe($add_arr[0],$add_arr[1]);
          $shape_shp2 = 'POINT('.$shop2.')';
        }
        $school1 = $params[14];
        $add_arr = explode(',',$school1);
        $sch1_mantaghe_id = 0;
        $shape_sch1 = 'null';
        if(count($add_arr)==2){
          $sch1_mantaghe_id = getMantaghe($add_arr[0],$add_arr[1]);
          $shape_sch1 = 'POINT('.$school1.')';
        }
        $school2 = $params[15];
        $add_arr = explode(',',$school2);
        $sch2_mantaghe_id = 0;
        $shape_sch2 = 'null';
        if(count($add_arr)==2){
          $sch2_mantaghe_id = getMantaghe($add_arr[0],$add_arr[1]);
          $shape_sch2 = 'POINT('.$school2.')';
        }
        $work1 = $params[6];
        $add_arr = explode(',',$work1);
        $wrk1_mantaghe_id = 0;
        $shape_wrk1 = 'null';
        if(count($add_arr)==2){
          $wrk1_mantaghe_id = getMantaghe($add_arr[0],$add_arr[1]);
          $shape_wrk1 = 'POINT('.$work1.')';
        }
        $work2 = $params[13];
        $add_arr = explode(',',$work2);
        $wrk2_mantaghe_id = 0;
        $shape_wrk2 = 'null';
        if(count($add_arr)==2){
          $wrk2_mantaghe_id = getMantaghe($add_arr[0],$add_arr[1]);
          $shape_wrk2 = 'POINT('.$work2.')';
        }
        $tedad_khanevade = (int)$tedad_khanevade;
        $query = "insert into `users` (`FNAME`, `LNAME`, `age`, `CELL`, `GENDER`, `job`, `car`, `benzin`, `use_car`, `sarparast`, `vasile`, `ADDRESS`, `shape_addr`, `addr_mantaghe_id`, `EMAIL`, `USERNAME`, `PASSWORD`, `WORKADDR1`, `shape_wrk1`, `wrk1_mantaghe_id`, `WORKADDR2`, `shape_wrk2`, `wrk2_mantaghe_id`, `shop1`, `shape_shp1`, `shp1_mantaghe_id`, `shop2`, `shape_shp2`, `shp2_mantaghe_id`, `school1`, `shape_sch1`, `sch1_mantaghe_id`, `school2`, `shape_sch2`, `sch2_mantaghe_id`,`tedad_khanevade`) values ";
        $query .= "('$fname','$lname','$age','$cell','$gender','$job','$car','$benzin','$use_car','$sarparast','$vasile','$address',$shape_addr,'$addr_mantaghe_id','$email','$username','$password','$work1',$shape_wrk1,'$wrk1_mantaghe_id','$work2',$shape_wrk2,'$wrk2_mantaghe_id','$shop1',$shape_shp1,'$shp1_mantaghe_id','$shop2',$shape_shp2,'$shp2_mantaghe_id','$school1',$shape_sch1,'$sch1_mantaghe_id','$school2',$shape_sch2,'$sch2_mantaghe_id',$tedad_khanevade)";
//         echo $query;
        if($my->query($query)){
          $out['items'][] = $my->insert_id;
        }
        
      }
    }
  }
}
$my->close();
if($output=='xml'){
  header('Content-Type: application/xml');
  echo $sampleXml;
}else{
  echo json_encode($out,TRUE);
}
die();