<?php
$dbok = TRUE;
$my = new mysqli('localhost','mirsamie_track','Track@159951','mirsamie_track');
if($my->connect_errno){
  $dbok = FALSE;
}
$query = "select * from `track` where `USER_ID` = 221 order by `id` ";
if($res=$my->query($query)){
  echo $res->num_rows."<br/>\n";
  while($r=$res->fetch_assoc()){
    var_dump($r);
  }
}