<?php
class base{
  private $tbl;
  public function __construct($tbl,$id=-1){
    global $my;
    $id = (int)$id;
    $tbl = trim($tbl);
    $this->tbl = $tbl;
    if($id>0 && isset($my) && $tbl!=''){
      $sql = "select * from `$tbl` where `id` = $id";
      if($res =$my->query($sql)){
        if($r = $res->fetch_assoc()){
          foreach($r as $key=>$value){
            $this->$key =$value;
          }
        }
      }
    }
  }
  public function add($feild){
    global $my;
    $tbl = $this->tbl;
    $id = -1;
    if(count($feild)>0 && isset($my) && $tbl!=''){
      $fl = '';
      $val = '';
      foreach($feild as $feil=>$value){
        $fl .= (($fl!='')?',':'')."`$feil`";
        $val .= (($val!='')?',':'')."'".$value."'";
      }
      $sql = "insert into `$tbl` ($fl) values ($val)";
      if($my->query($sql)){
        $id = $my->insert_id;
        $sql = "select * from `$tbl` where `id` = $id";
        if($res =$my->query($sql)){
          if($r = $res->fetch_assoc()){
            foreach($r as $key=>$value){
              $this->$key = $value;
            }
          }
        }
      }
    }
    return $id;
  }
  public function update($feild){
    global $my;
    $tbl = $this->tbl;
    $id = -1;
    if(count($feild)>0 && isset($my) && $tbl!=''){
      $fl = '';
      foreach($feild as $feil=>$value){
        $fl .= (($fl!='')?',':'')." `$feil`='".$value."' ";
      }
      $id = $this->id;
      $sql = "update `$tbl` set $fl where `id` = $id";
      if($my->query($sql)){
        $sql = "select * from `$tbl` where `id` = $id";
        if($res =$my->query($sql)){
          if($r = $res->fetch_assoc()){
            foreach($r as $key=>$value){
              $this->$key = $value;
            }
          }
        }
        if($my->affected_rows==1){
          $id = $this->id;
        }else{
          $id = -2;
        }
      }
    }
    return $id;
  }
  public function delete(){
    global $my;
    $tbl = $this->tbl;
    $out = FALSE;
    if(isset($my) && $tbl!=''){
      $sql = "delete from `$tbl` where `id` = ".$this->id;
      if($my->query($sql)){
        $out = TRUE;
      }
    }
    return $out;
  }
  public function load($where=''){
    global $my;
    $tbl = $this->tbl;
    $where = trim($where);
    $out = array();
    if(isset($my) && $tbl!=''){
      $sql = "select * from `$tbl` ";
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