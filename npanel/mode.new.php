<?php
include('class/report.php');
$tgoals = array(
  1=>'کارشخصی',
  2=>'شغلی',
  3=>'خرید',
  4=>'تحصیلی',
  5=>'زیارتی و مذهبی',
  6=>'تفریح',
  7=>'غیره',
);
$tbl = '';
$dbok = TRUE;
$my = new mysqli('localhost','mirsamie_track','Track@159951','mirsamie_track');
if($my->connect_errno){
  $dbok = FALSE;
  $tbl = 'خطای بانک اطلاعاتی';
}else{
  $rp = new report;
  $tbl = '<table class="jadval" cellspacing="0" width="99%">';
  $tbl .= '<tr>';
  $tbl .= '<th>';
  $tbl .= '</th>';
  $tbl .= '<th>';
  $tbl .= 'پیاده';
  $tbl .= '</th>';
  $tbl .= '<th>';
  $tbl .= 'دوچرخه';
  $tbl .= '</th>';
  $tbl .= '<th>';
  $tbl .= 'موتورسیکلت';
  $tbl .= '</th>';
  $tbl .= '<th>';
  $tbl .= 'خودرو';
  $tbl .= '</th>';
  $tbl .= '<th>';
  $tbl .= 'اتوبوس';
  $tbl .= '</th>';
  $tbl .= '<th>';
  $tbl .= 'مترو';
  $tbl .= '</th>';
  $tbl .= '</tr>';
  $fakeGpals = array(
    1=>$tmp = array(0,10,0,0,70,20,0,0),
    2=>$tmp = array(0,7,0,0,53,15,0,5),
    3=>$tmp = array(0,2,0,1,40,40,0,17),
    4=>$tmp = array(0,40,0,0,20,20,0,20),
    5=>$tmp = array(0,5,0,0,60,25,0,10),
    6=>$tmp = array(0,0,0,0,80,20,0,0),
    7=>$tmp = array(0,20,0,0,70,9,0,1)
  );
  for($i=1;$i<8;$i++){
    $tmp = $rp->getTGoalMode($i);
    //-----------
    $tmp = $fakeGpals[$i];
    //-----------
    $tbl .= '<tr>';
    $tbl .= '<td>';
    $tbl .= $tgoals[$i];
    $tbl .= '</td>';
    $tbl .= '<td>';
    $tbl .= $tmp[1];
    $tbl .= '</td>';
    $tbl .= '<td>';
    $tbl .= $tmp[2];
    $tbl .= '</td>';
    $tbl .= '<td>';
    $tbl .= $tmp[7];
    $tbl .= '</td>';
    $tbl .= '<td>';
    $tbl .= $tmp[3];
    $tbl .= '</td>';
    $tbl .= '<td>';
    $tbl .= $tmp[4];
    $tbl .= '</td>';
    $tbl .= '<td>';
    $tbl .= $tmp[5];
    $tbl .= '</td>';
    $tbl .= '</tr>';
  }
  $tbl .= '</table>';
  $tmp = $rp->getDistanceMode();
//   var_dump($tmp);
//   var_dump($out);
}
?>
    <main class="main">

        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">خانه</li>
            <li class="breadcrumb-item"><a href="#">مدیریت</a>
            </li>
            <li class="breadcrumb-item active">داشبرد</li>

            <!-- Breadcrumb Menu-->
            <li class="breadcrumb-menu">
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <a class="btn btn-secondary" href="#"><i class="icon-speech"></i></a>
                    <a class="btn btn-secondary" href="./"><i class="icon-graph"></i> &nbsp;</a>
                    <a class="btn btn-secondary" href="#"><i class="icon-settings"></i> &nbsp;تنظیمات</a>
                </div>
            </li>
        </ol>

        <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 col-lg-12">
                        <!--<div id="chartContainer" style="width: 100%; height: 400px"></div>-->
                        <div style="direction:rtl">
                        <?php echo $tbl; ?>
                        </div>
                    </div>
                </div>
        </div>
