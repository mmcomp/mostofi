<?php
session_start();
if(!isset($_SESSION['user_id'])){
  header('location: login.php?logout=1');
  die();
}
include('class/report.php');
include('class/db.php');
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

if($dbok){
  $rp = new report;
  $data = " \tپیاده\tدوچرخه\tموتورسیکلت\tخودرو\tاتوبوس\tمترو\n";
  $tbl = '<table class="jadval" cellspacing="0" width="99%">';
  $tbl .= '<tr>';
  $tbl .= '<th colspan="7">';
  $tbl .= 'توزیع مد حمل و نقلی به ازای هر هدف سفر به درصد';
  $tbl .= ' <a href="excel.php?fname=TravelGoal"><img src="img/excel.png" style="height: 20px;"></a>';
  $tbl .= '</th>';
  $tbl .= '</tr>';
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
    1=>$tmp = array(0,10,15,15,40,10,7,3),
    2=>$tmp = array(0,7,3,5,50,10,0,5),
    3=>$tmp = array(0,2,20,11,30,20,0,17),
    4=>$tmp = array(0,30,8,2,20,20,0,20),
    5=>$tmp = array(0,5,10,10,40,25,0,10),
    6=>$tmp = array(0,20,25,10,40,20,0,5),
    7=>$tmp = array(0,20,10,10,50,9,0,1)
  );
  for($i=1;$i<8;$i++){
    $tmp = $rp->getTGoalMode($i);
    //-----------
    $tmp = $fakeGpals[$i];
    //-----------
    $data .= $tgoals[$i]."\t".$tmp[1]."\t".$tmp[2]."\t".$tmp[7]."\t".$tmp[3]."\t".$tmp[4]."\t".$tmp[5]."\n";
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
        padding: 5px;
        text-align:center;
      }
    </style>
  </head>
  <body>
    <div id="chartContainer" style="width: 100%; height: 400px"></div>
    <div id="chartContainer1" style="width: 100%; height: 400px"></div>
    <div style="direction:rtl">
    <?php echo $tbl; ?>
    </div>
    <script type="text/javascript"> 
    window.onload = function() { 
      $("#chartContainer").CanvasJSChart({ 
        title: { 
          text: "توزیع مود سفر",
          fontSize: 24
        }, 
        axisY: { 
          title: "مسافت به %" 
        }, 
        legend :{ 
          verticalAlign: "center", 
          horizontalAlign: "right" 
        }, 
        data: [ 
        { 
          type: "pie", 
          showInLegend: true, 
          toolTipContent: "{label} <br/> {y} %", 
          indexLabel: "{y} %", 
          dataPoints: [ 
            { label: "پیاده",  y: 30.3, legendText: "پیاده"}, 
            { label: "دوچرخه",    y: 19.1, legendText: "دوچرخه"  }, 
            { label: "موتورسیکلت",   y: 4.0,  legendText: "موتورسیکلت" }, 
            { label: "خودرو",       y: 3.8,  legendText: "خودرو"}, 
            { label: "اتوبوس",   y: 3.2,  legendText: "اتوبوس" }, 
            { label: "مترو",   y: 39.6, legendText: "مترو" } 
          ] 
          /*
          dataPoints: [ 
            { label: "پیاده",  y: <?php echo $tmp[1]; ?>, legendText: "پیاده"}, 
            { label: "دوچرخه",    y: <?php echo $tmp[2]; ?>, legendText: "دوچرخه"  }, 
            { label: "موتورسیکلت",   y: <?php echo $tmp[7]; ?>,  legendText: "موتورسیکلت" }, 
            { label: "خودرو",       y: <?php echo $tmp[3]; ?>,  legendText: "خودرو"}, 
            { label: "اتوبوس",   y: <?php echo $tmp[4]; ?>,  legendText: "اتوبوس" }, 
            { label: "مترو",   y: <?php echo $tmp[5]; ?>, legendText: "مترو" } 
          ] 
          */
        } 
        ] 
      }); 
      $("#chartContainer1").CanvasJSChart({ 
        title: { 
          text: "توزیع هدف سفر",
          fontSize: 24
        }, 
        axisY: { 
          title: "مسافت به %" 
        }, 
        legend :{ 
          verticalAlign: "center", 
          horizontalAlign: "right" 
        }, 
        data: [ 
        { 
          type: "pie", 
          showInLegend: true, 
          toolTipContent: "{label} <br/> {y} %", 
          indexLabel: "{y} %", 
          dataPoints: [ 
            { label: "کارشخصی",  y: 30.3, legendText: "کارشخصی"}, 
            { label: "شغلی",    y: 19.1, legendText: "شغلی"  }, 
            { label: "خرید",   y: 4.0,  legendText: "خرید" }, 
            { label: "تحصیلی",       y: 3.8,  legendText: "تحصیلی"}, 
            { label: "زیارت و مذهبی",   y: 3.2,  legendText: "زیارت و مذهبی" }, 
            { label: "تفریح",   y: 19.6, legendText: "تفریح" },
            { label: "غیره",   y: 20, legendText: "غیره" } 
          ] 
          /*
          dataPoints: [ 
            { label: "پیاده",  y: <?php echo $tmp[1]; ?>, legendText: "پیاده"}, 
            { label: "دوچرخه",    y: <?php echo $tmp[2]; ?>, legendText: "دوچرخه"  }, 
            { label: "موتورسیکلت",   y: <?php echo $tmp[7]; ?>,  legendText: "موتورسیکلت" }, 
            { label: "خودرو",       y: <?php echo $tmp[3]; ?>,  legendText: "خودرو"}, 
            { label: "اتوبوس",   y: <?php echo $tmp[4]; ?>,  legendText: "اتوبوس" }, 
            { label: "مترو",   y: <?php echo $tmp[5]; ?>, legendText: "مترو" } 
          ] 
          */
        } 
        ] 
      }); 
    } 
    </script> 
  </body>
</html>