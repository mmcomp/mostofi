<?php
class access_key{
  private $accesses = array(
    "selected_base" => array(
      "sh_mantaghe" => "مناطق سیزده گانه شهرداری",
      "mantaghe" => "نواحی شهرداری",
      "nahiye" => "نواحی  ترافیکی"
    ),
    "stat" => array(
      "job" => "پراکندگی مشاغل",
      "gender" => "پراکندگی جنسیت",
      "use_car" => "مالکیت خودرو",
      "location" => "مکان ها",
      "mobile_app" => "مسیریابی با موبایل",
      "sarparast" => "سرپرست"
    ),
    "tolidjazb" => array(
      "tolid" => "گزارش تولید",
      "jazb" => "گزارش جذب",
      "od" => "ماتریس OD"
    ),
    "mode" => array(
      "goal" => "هدف سفر",
      "mode" => "مود"
    )
  );
  public function decode($inp){
    $out = 'ناشناخته';
    $tmp = explode('|',$inp);
    var_dump($tmp);
    if(count($tmp)==2){
      if(isset($this->accesses[$tmp[0]][$tmp[1]])){
        $out = $this->accesses[$tmp[0]][$tmp[1]];
      }
    }
    return $out;
  }
  public function encode(){
    $out = '';
    foreach($this->accesses as $master=>$slave){
      foreach($slave as $key=>$value){
        $out .= "<option value=\"$master|$key\">$value</option>";
      }
    }
    return $out;
  }
}