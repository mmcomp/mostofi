<?php
class group_access extends base
{
  public function __construct($id=-1){
    parent::__construct('group_access',$id);
  }
  public function loadGroup($user_group_id=-1){
    global $my;
    $out = array();
    $user_group_id = (int)$user_group_id;
    if(isset($my)){
      $sql = "select * from `group_access`";
      if($user_group_id==-1){
        $sql .= " where 1=0";
      }else if($user_group_id!=-2){
        $sql .= " where `user_group_id` = $user_group_id";
      }
      if($res = $my->query($sql)){
        while($r=$res->fetch_assoc()){
          $out[] = $r;
        }
      }
    }
    return $out;
  }
  public function loadUser($user_id){
    global $my;
    $out = array();
    $user_id = (int)$user_id;
    if(isset($my)){
      $sql = "select `access_key` from `group_access` left join `group_user` on (`group_user`.`user_group_id` = `group_access`.`user_group_id`) where `user_id` = $user_id";
      if($res = $my->query($sql)){
        while($r=$res->fetch_assoc()){
          $out[] = $r['access_key'];
        }
      }
    }
    return $out;
  }
  public function loadWithJoin($where=''){
    global $my;
    $where = trim($where);
    $out = array();
    if(isset($my)){
      $sql = "select `group_access`.*,`user_group`.`name` `gname` from `group_access` left join `user_group` on (`user_group_id`=`user_group`.`id`)  ";
      if($where !=''){
        $sql.="where $where";
      }
      if($res = $my->query($sql)){
        while($r=$res->fetch_assoc()){
          $out[] = $r;
        }
      }
    }
    return $out;
  }
}