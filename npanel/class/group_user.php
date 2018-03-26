<?php
class group_user extends base
{
  public function __construct($id=-1){
    parent::__construct('group_user',$id);
  }
  public function loadUser($user_id){
    global $my;
    $out = array();
    $user_id = (int)$user_id;
    if(isset($my)){
      $sql = "select `user_group_id` from `group_user` where `user_id` = $user_id";
      if($res = $my->query($sql)){
        while($r=$res->fetch_assoc()){
          $out[] = $r['user_group_id'];
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
      $sql = "select `group_user`.*,`users`.`FNAME` `fname`,`users`.`LNAME` `lname`,`user_group`.`name` `gname` from `group_user` left join `users` on (`users`.`ID` = `user_id`) left join `user_group` on (`user_group_id`=`user_group`.`id`)  ";
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