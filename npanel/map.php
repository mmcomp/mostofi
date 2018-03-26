<?php
include_once('class/jdf.php');
include_once('class/report.php');
include_once('class/kml.php');
include_once('class/csv.php');
$is_random = FALSE;
$kml = new kml;
$csv = new csv;
$reportName = '';
$selected_base = 'nahiye';
if(isset($_REQUEST['selected_base']) && trim($_REQUEST['selected_base'])!=''){
	$_SESSION['selected_base'] = trim($_REQUEST['selected_base']);
}
if(isset($_SESSION['selected_base']) && $_SESSION['selected_base']!=''){
	$selected_base = $_SESSION['selected_base'];
}else{
	$_SESSION['selected_base'] = $selected_base;
}
$aztarikh = '';//jdate("Y/m/d");
$tatarikh = '';//jdate("Y/m/d");
if(isset($_REQUEST['aztarikh'])){
	$aztarikh = $_REQUEST['aztarikh'];
	$tatarikh = $_REQUEST['tatarikh'];
	$_SESSION['aztarikh'] = $aztarikh;
	$_SESSION['tatarikh'] = $tatarikh;
}else if(isset($_SESSION['aztarikh'])){
	$aztarikh = $_SESSION['aztarikh'];
	$tatarikh = $_SESSION['tatarikh'];
}
$points = array();
$manategh = array();
$realvals = array();
$vals = array();
$selected=0;
$legend = '';
$kml_ready = '';
$csv_ready = '';
$jobs = array(
	''=>'همه',
	1=>'کارمند',
	2=>'شغل آزاد',
	3=>'دانشجو',
	4=>'دانش آموز',
	5=>'خانه دار',
	6=>'بیکار'
);
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
// var_dump(getRandOut());
// die();
if($dbok){
  $vals = array();
  $query = "select id,st_astext(shape) wkt from $selected_base";
  if($res = $my->query($query)){
    while($r = $res->fetch_assoc()){
      $r['val'] = 0;
      $r['rval'] = 0;
      $manategh[] = $r;
    }
  }
  if(isset($_REQUEST['report'])){
    $rep = $_REQUEST['report'];
    $rp = new report;
    if($rep == 'mode' && isset($_REQUEST['rep_mode'])){
      $selected = $_REQUEST['rep_mode'];
			$reportName = 'ModeChoice_'.$selected;
      $tmp = $rp->modeOnMantaghe($_REQUEST['rep_mode']);
// 			if($selected==3){
// 				$tmp = array(15 => 20,16 => 100,17 => 30,18 => 40,19 => 60,20 => 63,20 => 20,21 => 100,22 => 30,23 => 40,24 => 60,25 => 63);
// 			}
			if(isset($_SESSION['rep_mode_'.$_REQUEST['rep_mode']])){
				$tmp = $_SESSION['rep_mode_'.$_REQUEST['rep_mode']];
			}else{
				if($is_random)
					$tmp = getRandOut();
				$_SESSION['rep_mode_'.$_REQUEST['rep_mode']] = $tmp;
			}
			$kml_ready = $kml->getReport('mode report',$tmp);
			$csv_ready = $csv->getReport('mode report',$tmp);

    }else if($rep == 'stat' && isset($_REQUEST['variable']) && isset($_REQUEST['value'])){
      $variable = $_REQUEST['variable'];
      $value = $_REQUEST['value'];
			$reportName = 'StaticReportFor_'.$variable;
      $tmp = $rp->statVariableOnMantaghe($variable,$value);
// 			if($value==0 && $variable=='job'){
// 				$tmp = array(10 => 20,13 => 100,17 => 30,23 => 40,34 => 60,35 => 63);
// 			}else if($value==1 && $variable=='job'){
// 				$tmp = array(11 => 20,15 => 100,27 => 30,33 => 40,34 => 60,37 => 63);
// 			}else if($value==2 && $variable=='job'){
// 				$tmp = array(12 => 20,13 => 100,22 => 30,23 => 40,24 => 60,37 => 63);
// 			}else if($value==3 && $variable=='job'){
// 				$tmp = array(13 => 20,14 => 100,15 => 30,16 => 40,17 => 60,18 => 63);
// 			}else if($value==4 && $variable=='job'){
// 				$tmp = array(14 => 20,15 => 100,16 => 30,17 => 40,18 => 60,19 => 63);
// 			}else if($value==5 && $variable=='job'){
// 				$tmp = array(15 => 20,16 => 100,17 => 30,18 => 40,19 => 60,20 => 63);
// 			}else if($value==6 && $variable=='job'){
// 				$tmp = array(21 => 20,22 => 100,23 => 30,24 => 40,25 => 60,26 => 63);
// 			}else if($value=='m' && $variable=='GENDER'){
// 				$tmp = array(21 => 20,22 => 100,23 => 30,24 => 40,25 => 60,26 => 63);
// 			}else if($value=='f' && $variable=='GENDER'){
// 				$tmp = array(11 => 20,15 => 100,27 => 30,33 => 40,34 => 60,37 => 63);
// 			}else if($value==0 && $variable=='use_car'){
// 				$tmp = array(15 => 20,16 => 100,17 => 30,18 => 40,19 => 60,20 => 63);
// 			}else if($value==1 && $variable=='use_car'){
// 				$tmp = array(15 => 20,16 => 100,17 => 30,18 => 40,19 => 60,20 => 63);
// 			}
			if(isset($_SESSION['stat_'.$variable.'_'.$value])){
				$tmp = $_SESSION['stat_'.$variable.'_'.$value];
			}else{
				if($is_random)
					$tmp = getRandOut();
				$_SESSION['stat_'.$variable.'_'.$value] = $tmp;
			}
			$tit = 'statistics for '.$variable.' ';
			if($variable=='job')
				$tit .= $jobs[$value];
			else if($value==1)
				$tit .= 'that have';
			else if($value==0)
				$tit .= 'that haven`t';
			else if($value=='m')
				$tit .= 'for male';
			else if($value=='f')
				$tit .= 'for female';
			$kml_ready = $kml->getReport($tit,$tmp);
			$csv_ready = $csv->getReport($tit,$tmp);
    }else if($rep == 'location'){
      $variable = $_REQUEST['variable'];
			$reportName = 'LocationReportFor_'.$variable;
      $tmp = $rp->locationVariableOnMantaghe($variable);
			if(isset($_SESSION['loc_'.$variable])){
				$tmp = $_SESSION['loc_'.$variable];
			}else{
				if($is_random)
					$tmp = getRandOut();
				$_SESSION['loc_'.$variable] = $tmp;
			}
			$kml_ready = $kml->getReport('user locations in zones',$tmp);
			$csv_ready = $csv->getReport('user locations in zones',$tmp);
    }else if($rep == 'mobile_app'){
			$reportName = 'MobileNavigationUsageReport';
      $tmp = $rp->mobileAppOnMantaghe();
			if(isset($_SESSION['mobile_app'])){
				$tmp = $_SESSION['mobile_app'];
			}else{
				if($is_random)
					$tmp = getRandOut();
				$_SESSION['mobile_app'] = $tmp;
			}
			$kml_ready = $kml->getReport('user mobile navigation usage in zones',$tmp);
			$csv_ready = $csv->getReport('user mobile navigation usage in zones',$tmp);
    }else if($rep == 'tolid'){
			$reportName = 'TolidReport';
      $tmp = $rp->loadTolidJazb($_REQUEST['job'],$_REQUEST['GENDER'],$_REQUEST['use_car']);
			if($_REQUEST['job']!=''){
				$variable='job';
				$value=$_REQUEST['job'];
			}else if($_REQUEST['GENDER']!=''){
				$variable='GENDER';
				$value=$_REQUEST['GENDER'];
			}else if($_REQUEST['use_car']!=''){
				$variable='use_car';
				$value=$_REQUEST['use_car'];
			}
// 			if($value==0 && $variable=='job'){
// 				$tmp = array(10 => 20,13 => 100,17 => 30,23 => 40,34 => 60,35 => 63);
// 			}else if($value==1 && $variable=='job'){
// 				$tmp = array(11 => 20,15 => 100,27 => 30,33 => 40,34 => 60,37 => 63);
// 			}else if($value==2 && $variable=='job'){
// 				$tmp = array(12 => 20,13 => 100,22 => 30,23 => 40,24 => 60,37 => 63);
// 			}else if($value==3 && $variable=='job'){
// 				$tmp = array(13 => 20,14 => 100,15 => 30,16 => 40,17 => 60,18 => 63);
// 			}else if($value==4 && $variable=='job'){
// 				$tmp = array(14 => 20,15 => 100,16 => 30,17 => 40,18 => 60,19 => 63);
// 			}else if($value==5 && $variable=='job'){
// 				$tmp = array(15 => 20,16 => 100,17 => 30,18 => 40,19 => 60,20 => 63);
// 			}else if($value==6 && $variable=='job'){
// 				$tmp = array(21 => 20,22 => 100,23 => 30,24 => 40,25 => 60,26 => 63);
// 			}else if($value=='m' && $variable=='GENDER'){
// 				$tmp = array(21 => 20,22 => 100,23 => 30,24 => 40,25 => 60,26 => 63);
// 			}else if($value=='f' && $variable=='GENDER'){
// 				$tmp = array(11 => 20,15 => 100,27 => 30,33 => 40,34 => 60,37 => 63);
// 			}else if($value==0 && $variable=='use_car'){
// 				$tmp = array(15 => 20,16 => 100,17 => 30,18 => 40,19 => 60,20 => 63);
// 			}else if($value==1 && $variable=='use_car'){
// 				$tmp = array(15 => 20,16 => 100,17 => 30,18 => 40,19 => 60,20 => 63);
// 			}
			if(isset($_SESSION['tolid_'.$variable.'_'.$value])){
				$tmp = $_SESSION['tolid_'.$variable.'_'.$value];
			}else{
				if($is_random)
					$tmp = getRandOut();
				$_SESSION['tolid_'.$variable.'_'.$value] = $tmp;
			}
			$tit = $rep.' report for ';
			if($_REQUEST['job']!=''){
				$tit .= ' job '.$jobs[$_REQUEST['job']];
			}
			if($_REQUEST['GENDER']){
				$tit .= ' GENDER '.($_REQUEST['GENDER']=='m'?'male':'female');
			}
			if($_REQUEST['user_car']){
				$tit .= ' use_car '.($_REQUEST['use_car']==1?'have it':'haven`t it');
			}
			$kml_ready = $kml->getReport($tit,$tmp);
			$csv_ready = $csv->getReport($tit,$tmp);
    }else if($rep == 'jazb'){
			$reportName = 'JazbReport';
      $tmp = $rp->loadTolidJazb($_REQUEST['job'],$_REQUEST['GENDER'],$_REQUEST['use_car'],FALSE);
			if($_REQUEST['job']!=''){
				$variable='job';
				$value=$_REQUEST['job'];
			}else if($_REQUEST['GENDER']!=''){
				$variable='GENDER';
				$value=$_REQUEST['GENDER'];
			}else if($_REQUEST['use_car']!=''){
				$variable='use_car';
				$value=$_REQUEST['use_car'];
			}
// 			if($value==0 && $variable=='job'){
// 				$tmp = array(10 => 20,13 => 100,17 => 30,23 => 40,34 => 60,35 => 63);
// 			}else if($value==1 && $variable=='job'){
// 				$tmp = array(11 => 20,15 => 100,27 => 30,33 => 40,34 => 60,37 => 63);
// 			}else if($value==2 && $variable=='job'){
// 				$tmp = array(12 => 20,13 => 100,22 => 30,23 => 40,24 => 60,37 => 63);
// 			}else if($value==3 && $variable=='job'){
// 				$tmp = array(13 => 20,14 => 100,15 => 30,16 => 40,17 => 60,18 => 63);
// 			}else if($value==4 && $variable=='job'){
// 				$tmp = array(14 => 20,15 => 100,16 => 30,17 => 40,18 => 60,19 => 63);
// 			}else if($value==5 && $variable=='job'){
// 				$tmp = array(15 => 20,16 => 100,17 => 30,18 => 40,19 => 60,20 => 63);
// 			}else if($value==6 && $variable=='job'){
// 				$tmp = array(21 => 20,22 => 100,23 => 30,24 => 40,25 => 60,26 => 63);
// 			}else if($value=='m' && $variable=='GENDER'){
// 				$tmp = array(21 => 20,22 => 100,23 => 30,24 => 40,25 => 60,26 => 63);
// 			}else if($value=='f' && $variable=='GENDER'){
// 				$tmp = array(11 => 20,15 => 100,27 => 30,33 => 40,34 => 60,37 => 63);
// 			}else if($value==0 && $variable=='use_car'){
// 				$tmp = array(15 => 20,16 => 100,17 => 30,18 => 40,19 => 60,20 => 63);
// 			}else if($value==1 && $variable=='use_car'){
// 				$tmp = array(15 => 20,16 => 100,17 => 30,18 => 40,19 => 60,20 => 63);
// 			}
			if(isset($_SESSION['jazb_'.$variable.'_'.$value])){
				$tmp = $_SESSION['jazb_'.$variable.'_'.$value];
			}else{
				if($is_random)
					$tmp = getRandOut();
				$_SESSION['jazb_'.$variable.'_'.$value] = $tmp;
			}
			$tit = $rep.' report for ';
			if($_REQUEST['job']!=''){
				$tit .= ' job '.$jobs[$_REQUEST['job']];
			}
			if($_REQUEST['GENDER']){
				$tit .= ' GENDER '.($_REQUEST['GENDER']=='m'?'male':'female');
			}
			if($_REQUEST['user_car']){
				$tit .= ' use_car '.($_REQUEST['use_car']==1?'have it':'haven`t it');
			}
			$kml_ready = $kml->getReport($tit,$tmp);
			$csv_ready = $csv->getReport($tit,$tmp);
    }

		$ttmp = array();
		foreach($tmp as $mid=>$cid){
			if((int)$mid>0){
				$ttmp[(int)$mid] = $cid;
			}
		}
		$tmp = $ttmp;
		if(count($tmp)==0){
			$tmp = array(10 => 2,13 => 100,17 => 3,23 => 1,34 => 60,40 => 63);
		}
    $bigest_val = 0;

    foreach($manategh as $i=>$mantaghe){
      if(isset($tmp[$mantaghe['id']])){
        $manategh[$i]['val'] = $tmp[$mantaghe['id']];
        $vals[] = $tmp[$mantaghe['id']];
        $realvals[$i] = array(
          'name'=>'Zone '.$mantaghe['id'],//$mantaghe['name'],
          'id'=>$mantaghe['id'],
          'val'=>$tmp[$mantaghe['id']]
        );
      }else{
        $manategh[$i]['val'] = 0;
        $vals[] = 0;
      }
			$manategh[$i]['rval'] = 0;
    }
    $a = $vals;
    $min = min($a);
    $max = max($a);
    $new_min = 0;
    $new_max = 0.8;
		$op_array=array(0.16,0.32,0.48,0.74,0.8);
		
		if($max>0){
			foreach ($a as $i => $v) {
// 				$a[$i] = ((($new_max - $new_min) * ($v - $min)) / ($max - $min)) + $new_min;
				$tmp = (int)$v-$min;
				$tmp = (int)(5*$tmp / ($max-$min));
				$opc = $op_array[$tmp];
				$a[$i] = $opc;
			}
		}
		
		
    $vals = $a;
    for($i=0;$i<count($vals);$i++){
      if(isset($manategh[$i])){
        $manategh[$i]['rval'] = $vals[$i];
      }
    }
		/*
    foreach($realvals as $i=>$val){
      $legend .= '<div id="man_'.$val['id'].'" class="man" onclick="selPol('.$val['id'].');" >';
//       $legend .= '<span style="background:#ac1919;opacity:'.$vals[$i].';">&nbsp;&nbsp;</span>&nbsp;';
			$opc = 0;
			if($max>0){
				$tmp = (int)$val['val']-$min;
				$tmp = (int)(5*$tmp / ($max-$min));
				$opc = $op_array[$tmp];
			}
      $legend .= '<span style="background:#ac1919;opacity:'.$opc.';">&nbsp;&nbsp;</span>&nbsp;';
      $legend .= 'Zone '.$val['id'].' = '.$val['val'].'';
      $legend .= '</div>';
    }
		*/
		$legend .= '<div>';
		$legend .= '<span style="background:#ac1919;opacity:'.$op_array[0].';">&nbsp;&nbsp;</span>&nbsp;';
		$legend .= '0-'.((int)(0.2*$max));
    $legend .= '</div>';
		$legend .= '<div>';
		$legend .= '<span style="background:#ac1919;opacity:'.$op_array[1].';">&nbsp;&nbsp;</span>&nbsp;';
		$legend .= ((int)(0.2*$max)).'-'.((int)(0.4*$max));
    $legend .= '</div>';
		$legend .= '<div>';
		$legend .= '<span style="background:#ac1919;opacity:'.$op_array[2].';">&nbsp;&nbsp;</span>&nbsp;';
		$legend .= ((int)(0.4*$max)).'-'.((int)(0.6*$max));
    $legend .= '</div>';
		$legend .= '<div>';
		$legend .= '<span style="background:#ac1919;opacity:'.$op_array[3].';">&nbsp;&nbsp;</span>&nbsp;';
		$legend .= ((int)(0.6*$max)).'-'.((int)(0.8*$max));
    $legend .= '</div>';
		$legend .= '<div>';
		$legend .= '<span style="background:#ac1919;opacity:'.$op_array[4].';">&nbsp;&nbsp;</span>&nbsp;';
		$legend .= ((int)(0.8*$max)).'-'.($max);
    $legend .= '</div>';
  }

}
$_SESSION['csv_ready'] = $csv_ready;
?>
    <main class="main">

        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">خانه</li>
						<?php if($csv_ready!=''){ ?>
            <li class="breadcrumb-item">
							<a href="excel.php?fname=<?php echo $reportName; ?>&">فایل اکسل</a>
							<img src="img/excel.png" style="height: 20px;"/>
            </li>	
						<?php }?>
						
            <li class="breadcrumb-item active">
							<label for="aztarikh">از تاریخ</label>
							<input id="aztarikh" name="aztarikh" value="<?php echo $aztarikh; ?>"  autocomplete="off" readonly="true" />
							<label for="tatarikh">تا تاریخ</label>
							<input id="tatarikh" name="tatarikh" value="<?php echo $tatarikh; ?>"  autocomplete="off" readonly="true" />
							<a class="btn btn-warning" style="color: #fff !important;" href="javascript:setTarikh()">اعمال فیلتر</a>
							<a class="btn btn-danger" style="color: #fff !important;" href="javascript:clearTarikh()">حذف فیلتر</a>
						</li>
						
            <!-- Breadcrumb Menu-->
            <li class="breadcrumb-menu">
                <!--<div class="btn-group" role="group" aria-label="Button group with nested dropdown">-->
                <!--    <a class="btn btn-secondary" href="#"><i class="icon-speech"></i></a>-->
                <!--    <a class="btn btn-secondary" href="./"><i class="icon-graph"></i> &nbsp;داشبرد</a>-->
                <!--    <a class="btn btn-secondary" href="#"><i class="icon-settings"></i> &nbsp;تنظیمات</a>-->
                <!--</div>-->
            </li>
        </ol>

        <div class="container-fluid">

            <!--<div class="animated fadeIn">-->
                <div class="row">
                    <div class="col-sm-12 col-lg-12 col-md-12">
                      <div id="basicMap"></div>
                    </div>
                </div>
                <!--/row-->
            <!--</div>-->

        </div>
        <!--/.container-fluid-->
    </main>
    <div id="leg">
      <?php echo $legend; ?>
    </div>

    <div id="dialog-form">
      <div id="frm">
        <form id="dfrm" method="POST">
          <input type="hidden" name="report" value="" id="repo" /> 
          شغل
          <select name="job">
            <option value=''>همه</option>
            <option value="1">کارمند</option>
            <option value="2">شغل آزاد</option>
            <option value="3">دانشجو</option>
            <option value="4">دانش آموز</option>
            <option value="5">خانه دار</option>
            <option value="6">بیکار</option>
          </select>
          <br/>
          جنسیت
          <select name="GENDER">
            <option value=''>همه</option>
            <option value="m">مرد</option>
            <option value="f">زن</option>
          </select>
          <br/>
          مالکیت وسیله نقلیه
          <select name="use_car">
            <option value=''>همه</option>
            <option value="1">خودرو شخصی</option>
            <option value="2">موتور سیکلت</option>
            <option value="3">دوچرخه</option>
            <option value="4">هیچ وسیله نقلیه</option>
<!--             <option value="5">خانه دار</option> -->
          </select>
          <br/>
         	هدف سفر
          <select name="use_car">
            <option value=''>همه</option>
            <option value="1">کار شخصی</option>
            <option value="2">شغلی</option>
            <option value="3">خرید</option>
            <option value="4">تحصیلی</option>
            <option value="5">زیارتی و مذهبی</option>
            <option value="6">تفریح</option>
            <option value="7">غیره</option>
          </select>
          <br/>
<!--           <button>
            نمایش
          </button> -->
        </form>
      </div>
    </div>
<form id="tarikh-form" method="post">
</form>