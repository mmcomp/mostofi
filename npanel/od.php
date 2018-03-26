<?php
session_start();
if(!isset($_SESSION['user_id'])){
  header('location: login.php?logout=1');
  die();
}
include_once('class/db.php');
include_once('class/report.php');
$selected_base = $_SESSION['selected_base'];
function getRandOut(){
	$max_pol = 253;
	if($_SESSION['selected_base']=='manategh'){
		$max_pol = 50;
	}else if($_SESSION['selected_base']=='sh_mantaghe'){
		$max_pol = 13;
	}
	$mans_length = (int)(0.8*$max_pol);
	$mans = array();
	while(count($mans)<=$mans_length){
		$n = rand(1,$max_pol);
		if(!in_array($n,$mans)){
			$mans[] = $n;
		}
	}	
	$tmp = array();
	foreach($mans as $man){
		$tmp[$man] = rand(0,100);
	}
	/*
		15 => 20,
		16 => 100,
		17 => 30,
		18 => 40,
		19 => 60,
		20 => 63,
		20 => 20,21 => 100,22 => 30,23 => 40,24 => 60,25 => 63
	);
	*/
	return $tmp;
}
$tbl = '';
if($dbok){
	$data = ' ';
  $tbl = '<table class="jadval" cellspacing="0" width="99%">';
  $sql = "select * from `$selected_base` order by id";
  $i = 1;
  if($res=$my->query($sql)){
    $tbl .= '<tr>';
    $tbl .= '<th>';
    $tbl .= '&nbsp;';
    $tbl .= '</th>';
    while($r=$res->fetch_assoc()){
      $tbl .= '<th>';
			if($selected_base!='sh_mantaghe'){
      	$tbl .= 'Z'.$i;
				$data .= "\t".'Z'.$i;
			}else{
				$tbl .= 'منطقه '.(($i!=13)?$i:'ثامن');
				$data .= "\t".'منطقه '.(($i!=13)?$i:'ثامن');
			}
      $tbl .= '</th>';
      $i++;
    }
		$data .= "\n";
    $i--;
    $tbl .= '</tr>';
    for($j=0;$j<$i;$j++){
      $tbl .= '<tr>';
      $tbl .= '<td>';
			if($selected_base!='sh_mantaghe'){
      	$tbl .= 'Z'.($j+1);
				$data .= 'Z'.($j+1);
			}else{
				$tbl .= 'منطقه '.(($j!=12)?($j+1):'ثامن');
				$data .= 'منطقه '.(($j!=12)?($j+1):'ثامن');
			}
      $tbl .= '</td>';
      $tmp = getRandOut();
      for($k=0;$k<$i;$k++){
        $tbl .= '<td>';
        $tbl .= (isset($tmp[$k])?$tmp[$k]:'0');
				$data .= "\t".(isset($tmp[$k])?$tmp[$k]:'0');
        $tbl .= '</td>';
      }
			$data .= "\n";
      $tbl .= '</tr>';
    }
  }
  $tbl .= '</table>';
}
$_SESSION['csv_ready'] = $data;
?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>BaChi</title>
    <link rel="icon" type="image/png" href="img/bachi.png">
    <script src="js/libs/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.canvasjs.min.js"></script> 
    <style>
      table.jadval{
        margin:10px;
      } 
      table.jadval td,table.jadval th{
        border: solid 1px #000;
        padding: 0px;
        text-align:center;
      }
    </style>
  </head>
  <body>
    <div style="direction:rtl">
    <h1 style="text-align:center">
    ماتریس مبدا - مقصد سفر  
			<a href="excel.php?fname=od"><img src="img/excel.png" style="height: 20px;"></a>
    </h1>
    <?php echo $tbl; ?>
    </div>
  </body>
</html>