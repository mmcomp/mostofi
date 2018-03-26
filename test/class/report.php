<?php
class report{
  public function modeOnMantaghe($mode,$user_mode=FALSE){
    global $my;
    $out = array();
    $field = 'travel_mode';
    if($user_mode===TRUE){
      $field = 'user_travel_mode';
    }
    $query = "SELECT manategh.id mid,count(track.id) cid FROM `track` left join manategh on (ST_CONTAINS(shape,ushape)=1) where $field=$mode group by manategh.id ";
//     echo $query."<br/>\n";
    if($res=$my->query($query)){
      while($r=$res->fetch_assoc()){
        $out[$r['mid']] = (int)$r['cid'];
      }
    }
    return $out;
  }
  public function modeOnMantagheDate($mode,$aztarikh,$tatarikh,$user_mode=FALSE){
    global $my;
    $out = array();
    $field = 'travel_mode';
    if($user_mode===TRUE){
      $field = 'user_travel_mode';
    }
    $query = "SELECT manategh.id mid,count(track.id) cid FROM `track` left join manategh on (ST_CONTAINS(shape,ushape)=1) where `regtime`>='$aztarikh' and `regtime` <='$tatarikh' and $field=$mode group by manategh.id ";
    if($res=$my->query($query)){
      while($r=$res->fetch_assoc()){
        $out[$r['mid']] = (int)$r['cid'];
      }
    }
    return $out;
  }
  public function statVariableOnMantaghe($variable,$value=0){
    global $my;
    $out = array();
    $query = "SELECT count(users.id) cid,manategh.id mid FROM `users` left join manategh on (st_contains(shape,shape_addr)=1) where `$variable`='$value' group by mid";
//     echo $query."<br/>\n";
    if($res=$my->query($query)){
      while($r=$res->fetch_assoc()){
        $out[$r['mid']] = (int)$r['cid'];
      }
    }
    return $out;
  }
  public function locationVariableOnMantaghe($variable){
    global $my;
    $out = array();
    $query = "SELECT count(users.id) cid,manategh.id mid FROM `users` left join manategh on (st_contains(shape,$variable)=1) group by mid";
//     echo $query."<br/>\n";
    if($res=$my->query($query)){
      while($r=$res->fetch_assoc()){
        $out[$r['mid']] = (int)$r['cid'];
      }
    }
    return $out;
  }
  public function mobileAppOnMantaghe(){
    global $my;
    $out = array();
    $query = "SELECT count(users.id) cid,manategh.id mid FROM `track_mode` left join `users` on (`users`.`id`=`user_id`) left join manategh on (st_contains(shape,shape_addr)=1) where mobile_app=1 group by mid";
//     echo $query."<br/>\n";
    if($res=$my->query($query)){
      while($r=$res->fetch_assoc()){
        $out[$r['mid']] = (int)$r['cid'];
      }
    }
    return $out;
  }
  public function loadTolidJazb($job,$GENDER,$use_car,$is_tolid=TRUE){
    global $my;
    $out = array();
    $wer = '';
    if((int)$job>0){
      $wer = ' job = '.$job;
    }
    if($GENDER!=''){
      $wer .= (($wer=='')?'':' and').' GENDER = \''.$GENDER."'";
    }
    if((int)$use_car>0){
      $wer .= (($wer=='')?'':' and').' use_car = '.$use_car;
    }
    $jid = '';
    $tid = '';
    if($is_tolid){
      $tid = 'cid';
    }else{
      $jid = 'cid';
    }
    $query = "select mantaghe_tolid_jazb.mantaghe_id mid,tolid $tid,jazb $jid from mantaghe_tolid_jazb ";
    if($wer!=''){
      $query .= "left join users on (user_id=users.id) where $wer";
    }
//     echo $query."<br/>\n";
    if($res=$my->query($query)){
      while($r=$res->fetch_assoc()){
        $out[$r['mid']] = (int)$r['cid'];
      }
    }
    return $out;
  }
  public function getTGoalMode($tgoal){
    global $my;
    $out = array();
    $query = "select mode,count(*) co from user_stops left join track_mode on (track_id<=track_start_id and track_id>=track_end_id) where tgoal = $tgoal and mode != NULL group by mode";
//     echo $query."<br/>\n";
    if($res=$my->query($query)){
      while($r=$res->fetch_assoc()){
        $out[(int)$r['mode']] = (int)$r['co'];
      }
    }
    for($i=1;$i<8;$i++){
      if(!isset($out[$i])){
        $out[$i] = 0;
      }
    }
    $max = max($out);
    $min = min($out);
    $new_max = 100;
    $new_min = 0;
    if($max>0){
      foreach ($out as $i => $v) {
        $out[$i] = ((($new_max - $new_min) * ($v - $min)) / ($max - $min)) + $new_min;
      }
    }
    return $out;
  }
  public function getDistanceMode(){
    global $my;
    $out = array();
    $query = "select mode,sum(dis) ds from track_mode where group by mode";
//     echo $query."<br/>\n";
    if($res=$my->query($query)){
      while($r=$res->fetch_assoc()){
        $out[(int)$r['mode']] = (float)$r['ds'];
      }
    }
    for($i=1;$i<8;$i++){
      if(!isset($out[$i])){
        $out[$i] = 0;
      }
    }
    $max = max($out);
    $min = min($out);
    $new_max = 100;
    $new_min = 0;
    if($max>0){
      foreach ($out as $i => $v) {
        $out[$i] = ((($new_max - $new_min) * ($v - $min)) / ($max - $min)) + $new_min;
      }
    }
    return $out;
  }
}