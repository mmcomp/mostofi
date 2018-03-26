<?php
$trans = array();
$trans['en'] = array(
  'تغییر مبنا' => 'Region Scaling',
  'مناطق سیزده گانه شهرداری' => 'Region',
  'نواحی شهرداری' => 'Zones',
  'نواحی ترافیکی' => 'Trafic Zones',
  'گزارشات آماری' => 'Sample<br/>Characteristics',
  'پراکندگی مشاغل' => 'Employement<br/>Distribution ',
  'کارمند' => 'Employ',
  'شغل آزاد' => 'Self Employ',
  'دانشجو' => 'Uni Student',
  'دانش آموز' => 'Student',
  'خانه دار' => 'Housewife',
  'بازنشسته / بیکار' => 'Retired / unemployed',
  'مغازه دار یا فروشنده' => 'Shopkeeper or Seller',
  'استاد،فرهنگی،روحانی' => 'Master, Cultural, Rouhani',
  'پزشک/پرستار' => 'Doctor / Nurse',
  'راننده-مسافرکش' => 'Driver',
  'کارگر/استادکار' => 'Worker / Master',
  'کارمنددولتی/خصوصی' => 'State / private staff',
  'نظامی' => 'Military',
  'کشاورز' => 'Farmer',
  'خردسال' => 'child',
  'سایر' => 'Others',
  'همه' => 'All',
  'پراکندگی جنسیت' => 'Gender Dispersal',
  'مرد' => 'Male',
  'زن' => 'Female',
  'مالکیت خودرو' => 'Car Ownership',
  'دارندگان' => 'Yes',
  'فاقد خودرو' => 'No',
  'مکان ها' => 'Places',
  'محل تحصیل' => 'Education Place',
  //---
  'محل آموزشگاه' => 'Extra Education Place',
  'محل اشتغال' => 'Employment Place',
  'محل فروشگاه' => 'Store Location',
  'مسیریابی با موبایل' => 'Mobile Routing',
  'سرپرست' => 'Head of the Family',
  'می باشد' => 'Yes',
  'نمی باشد' => 'No',
  'گزارشات تولید و جذب' => 'Reports of Trip Generation and Attraction',
  'گزارش تولید' => 'Report of Trip Generation',
  'گزارش جذب' => 'Report of Trip Attraction',
  'گزارشات مود سفر' => 'Mode Choice Reports',
  'هدف سفر' => 'Trip Purpose ',
  'مد حمل و نقل' => 'Trip Mode',
  'پیاده' => 'Walking ',
  'دوچرخه' => 'Biking',
  'موتور' => 'Motorcycle',
  'خودرو' => 'Private Car',
  'تاکسی' => 'Taxi',
  'مسافرکش' => 'Car Sharing',
  'اتوبوس واحد' => 'Public Bus',
  'اتوبوس غیرواحد' => 'Private Bus',
  'مینی بوس' => 'Mini Bus',
  'مترو' => 'Metro',
//   'باری ها' => '',
  'وانت' => 'Pickup truck',
  'کامیونت' => 'Truck',
  'کامیون دومحور' => 'Two-axle truck',
  'کامیون سه محور' => 'Three-axle truck',
  'تریلی' => 'Trailer',
  'ماتریس OD' => 'OD Matrix'
);
// echo "session:<br/>\n";
// var_dump($_SESSION);
$pers = $_SESSION['pers'];
$permissions = array();
foreach($pers as $p){
  $tmp = explode('|',$p);
  if(!isset($permissions[$tmp[0]])){
    $permissions[$tmp[0]] = array();
  }
  $permissions[$tmp[0]][$tmp[1]] = TRUE;
}
?>
<div class="sidebar">
  <nav class="sidebar-nav">
    <ul class="nav">
      <?php if(isset($permissions['selected_base']) || $is_admin){ ?>
      <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-puzzle"></i>
          <?php
          if(isset($trans[$lang]['تغییر مبنا'])){ 
            echo $trans[$lang]['تغییر مبنا'];
          }else{
            echo 'تغییر مبنا';
          } 
          ?>
        </a>
        <ul class="nav-dropdown-items">
          <?php if(isset($permissions['selected_base']['sh_mantaghe']) || $is_admin){ ?>
          <li class="nav-item">
            <a class="nav-link" href="?selected_base=sh_mantaghe">
          <?php
          if(isset($trans[$lang]['مناطق سیزده گانه شهرداری'])){ 
            echo $trans[$lang]['مناطق سیزده گانه شهرداری'];
          }else{
            echo 'مناطق سیزده گانه شهرداری';
          } 
          ?>
            </a>
          </li>
          <?php }if(isset($permissions['selected_base']['mantaghe']) || $is_admin){ ?>        
          <li class="nav-item">
            <a class="nav-link" href="?selected_base=manategh">
          <?php
          if(isset($trans[$lang]['نواحی شهرداری'])){ 
            echo $trans[$lang]['نواحی شهرداری'];
          }else{
            echo 'نواحی شهرداری';
          } 
          ?>
            </a>
          </li>
          <?php }if(isset($permissions['selected_base']['nahiye']) || $is_admin){ ?>
          <li class="nav-item">
            <a class="nav-link" href="?selected_base=nahiye">
          <?php
          if(isset($trans[$lang]['نواحی ترافیکی'])){ 
            echo $trans[$lang]['نواحی ترافیکی'];
          }else{
            echo 'نواحی ترافیکی';
          } 
          ?>
            </a>
          </li>
          <?php } ?>
        </ul>
      </li>
      <?php } if(isset($permissions['stat']) || $is_admin){ ?>
      <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-puzzle"></i>
          <?php
          if(isset($trans[$lang]['گزارشات آماری'])){ 
            echo $trans[$lang]['گزارشات آماری'];
          }else{
            echo 'گزارشات آماری';
          } 
          ?>
        </a>
        <ul class="nav-dropdown-items">
          <?php if(isset($permissions['stat']['job']) || $is_admin){ ?>
          <li class="nav-item nav-dropdown">
            <!--                     <a class="nav-link nav-dropdown-toggle" href="?report=stat&variable=job&value=0"><i class="icon-puzzle"></i> پراکندگی مشاغل </a> -->
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-puzzle"></i>
              <?php
              if(isset($trans[$lang]['پراکندگی مشاغل'])){ 
                echo $trans[$lang]['پراکندگی مشاغل'];
              }else{
                echo 'پراکندگی مشاغل';
              } 
              ?>
            </a>
            <ul class="nav-dropdown-items">
              <li class="nav-item">
                <a class="nav-link" href="?report=stat&variable=job&value=1"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['کارمند'])){ 
                    echo $trans[$lang]['کارمند'];
                  }else{
                    echo 'کارمند';
                  } 
                  ?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=stat&variable=job&value=2"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['شغل آزاد'])){ 
                    echo $trans[$lang]['شغل آزاد'];
                  }else{
                    echo 'شغل آزاد';
                  } 
                  ?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=stat&variable=job&value=3"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['دانشجو'])){ 
                    echo $trans[$lang]['دانشجو'];
                  }else{
                    echo 'دانشجو';
                  } 
                  ?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=stat&variable=job&value=4"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['دانش آموز'])){ 
                    echo $trans[$lang]['دانش آموز'];
                  }else{
                    echo 'دانش آموز';
                  } 
                  ?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=stat&variable=job&value=5"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['خانه دار'])){ 
                    echo $trans[$lang]['خانه دار'];
                  }else{
                    echo 'خانه دار';
                  } 
                  ?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=stat&variable=job&value=6"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['بازنشسته / بیکار'])){ 
                    echo $trans[$lang]['بازنشسته / بیکار'];
                  }else{
                    echo 'بازنشسته / بیکار';
                  } 
                  ?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=stat&variable=job&value=7"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['مغازه دار یا فروشنده'])){ 
                    echo $trans[$lang]['مغازه دار یا فروشنده'];
                  }else{
                    echo 'مغازه دار یا فروشنده';
                  } 
                  ?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=stat&variable=job&value=8"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['استاد،فرهنگی،روحانی'])){ 
                    echo $trans[$lang]['استاد،فرهنگی،روحانی'];
                  }else{
                    echo 'استاد،فرهنگی،روحانی';
                  } 
                  ?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=stat&variable=job&value=9"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['پزشک/پرستار'])){ 
                    echo $trans[$lang]['پزشک/پرستار'];
                  }else{
                    echo 'پزشک/پرستار';
                  } 
                  ?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=stat&variable=job&value=10"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['راننده-مسافرکش'])){ 
                    echo $trans[$lang]['راننده-مسافرکش'];
                  }else{
                    echo 'راننده-مسافرکش';
                  } 
                  ?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=stat&variable=job&value=11"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['کارگر/استادکار'])){ 
                    echo $trans[$lang]['کارگر/استادکار'];
                  }else{
                    echo 'کارگر/استادکار';
                  } 
                  ?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=stat&variable=job&value=12"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['کارمنددولتی/خصوصی'])){ 
                    echo $trans[$lang]['کارمنددولتی/خصوصی'];
                  }else{
                    echo 'کارمنددولتی/خصوصی';
                  } 
                  ?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=stat&variable=job&value=13"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['نظامی'])){ 
                    echo $trans[$lang]['نظامی'];
                  }else{
                    echo 'نظامی';
                  } 
                  ?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=stat&variable=job&value=14"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['کشاورز'])){ 
                    echo $trans[$lang]['کشاورز'];
                  }else{
                    echo 'کشاورز';
                  } 
                  ?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=stat&variable=job&value=15"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['خردسال'])){ 
                    echo $trans[$lang]['خردسال'];
                  }else{
                    echo 'خردسال';
                  } 
                  ?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=stat&variable=job&value=16"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['سایر'])){ 
                    echo $trans[$lang]['سایر'];
                  }else{
                    echo 'سایر';
                  } 
                  ?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=stat&variable=job&value=0"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['همه'])){ 
                    echo $trans[$lang]['همه'];
                  }else{
                    echo 'همه';
                  } 
                  ?>
                </a>
              </li>
            </ul>
          </li>
          <?php }if(isset($permissions['stat']['gender']) || $is_admin){ ?>
          <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-puzzle"></i>
            <?php
            if(isset($trans[$lang]['پراکندگی جنسیت'])){ 
              echo $trans[$lang]['پراکندگی جنسیت'];
            }else{
              echo 'پراکندگی جنسیت';
            } 
            ?>
            </a>
            <ul class="nav-dropdown-items">
              <li class="nav-item">
                <a class="nav-link" href="?report=stat&variable=GENDER&value=m"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['مرد'])){ 
                    echo $trans[$lang]['مرد'];
                  }else{
                    echo 'مرد';
                  } 
                  ?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=stat&variable=GENDER&value=f"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['زن'])){ 
                    echo $trans[$lang]['زن'];
                  }else{
                    echo 'زن';
                  } 
                  ?>
                </a>
              </li>
            </ul>
          </li>
          <?php }if(isset($permissions['stat']['use_car']) || $is_admin){ ?>
          <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-puzzle"></i>
              <?php
              if(isset($trans[$lang]['مالکیت خودرو'])){ 
                echo $trans[$lang]['مالکیت خودرو'];
              }else{
                echo 'مالکیت خودرو';
              } 
              ?>
            </a>
            <ul class="nav-dropdown-items">
              <li class="nav-item">
                <a class="nav-link" href="?report=stat&variable=use_car&value=1"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['دارندگان'])){ 
                    echo $trans[$lang]['دارندگان'];
                  }else{
                    echo 'دارندگان';
                  } 
                  ?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=stat&variable=use_car&value=0"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['فاقد خودرو'])){ 
                    echo $trans[$lang]['فاقد خودرو'];
                  }else{
                    echo 'فاقد خودرو';
                  } 
                  ?>
                </a>
              </li>
            </ul>
          </li>
          <?php }if(isset($permissions['stat']['location']) || $is_admin){ ?>
          <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-puzzle"></i>
              <?php
              if(isset($trans[$lang]['مکان ها'])){ 
                echo $trans[$lang]['مکان ها'];
              }else{
                echo 'مکان ها';
              } 
              ?>
            </a>
            <ul class="nav-dropdown-items">
              <li class="nav-item">
                <a class="nav-link" href="?report=location&variable=shape_sch1&">
                  <?php
                  if(isset($trans[$lang]['محل تحصیل'])){ 
                    echo $trans[$lang]['محل تحصیل'];
                  }else{
                    echo 'محل تحصیل';
                  } 
                  ?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=location&variable=shape_sch2&">
                  <?php
                  if(isset($trans[$lang]['محل آموزشگاه'])){ 
                    echo $trans[$lang]['محل آموزشگاه'];
                  }else{
                    echo 'محل آموزشگاه';
                  } 
                  ?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=location&variable=shape_wrk1&">
                  <?php
                  if(isset($trans[$lang]['محل اشتغال'])){ 
                    echo $trans[$lang]['محل اشتغال'];
                  }else{
                    echo 'محل اشتغال';
                  } 
                  ?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=location&variable=shape_shp1&">
                  <?php
                  if(isset($trans[$lang]['محل فروشگاه'])){ 
                    echo $trans[$lang]['محل فروشگاه'];
                  }else{
                    echo 'محل فروشگاه';
                  } 
                  ?>
                </a>
              </li>
            </ul>
          </li>
          <?php }if(isset($permissions['stat']['mobile_app']) || $is_admin){ ?>
          <li class="nav-item">
            <a class="nav-link" href="?report=mobile_app&">
              <?php
              if(isset($trans[$lang]['مسیریابی با موبایل'])){ 
                echo $trans[$lang]['مسیریابی با موبایل'];
              }else{
                echo 'مسیریابی با موبایل';
              } 
              ?>
            </a>
          </li>
          <?php }if(isset($permissions['stat']['sarparast']) || $is_admin){ ?>
          <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-puzzle"></i>
              <?php
              if(isset($trans[$lang]['سرپرست'])){ 
                echo $trans[$lang]['سرپرست'];
              }else{
                echo 'سرپرست';
              } 
              ?>
            </a>
            <ul class="nav-dropdown-items">
              <li class="nav-item">
                <a class="nav-link" href="?report=stat&variable=sarparast&value=1"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['می باشد'])){ 
                    echo $trans[$lang]['می باشد'];
                  }else{
                    echo 'می باشد';
                  } 
                  ?>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=stat&variable=sarparast&value=0"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['نمی باشد'])){ 
                    echo $trans[$lang]['نمی باشد'];
                  }else{
                    echo 'نمی باشد';
                  } 
                  ?>
                </a>
              </li>
            </ul>
          </li>
          <?php } ?>
        </ul>
      </li>
      <?php }if(isset($permissions['tolidjazb']) || $is_admin){ ?>
      <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-puzzle"></i>
          <?php
          if(isset($trans[$lang]['گزارشات تولید و جذب'])){ 
            echo $trans[$lang]['گزارشات تولید و جذب'];
          }else{
            echo 'گزارشات تولید و جذب';
          } 
          ?>
        </a>
        <ul class="nav-dropdown-items">
          <?php if(isset($permissions['tolidjazb']['tolid']) || $is_admin){ ?>
          <li class="nav-item">
            <a class="nav-link" href="javascript:loadTolid()">
              <?php
              if(isset($trans[$lang]['گزارش تولید'])){ 
                echo $trans[$lang]['گزارش تولید'];
              }else{
                echo 'گزارش تولید';
              } 
              ?>
              
            </a>
          </li>
          <?php }if(isset($permissions['tolidjazb']['jazb']) || $is_admin){ ?>
          <li class="nav-item">
            <a class="nav-link" href="javascript:loadJazb()">
              <?php
              if(isset($trans[$lang]['گزارش جذب'])){ 
                echo $trans[$lang]['گزارش جذب'];
              }else{
                echo 'گزارش جذب';
              } 
              ?>
              
            </a>
          </li>
          <?php }if(isset($permissions['tolidjazb']['od']) || $is_admin){ ?>
          <li class="nav-item">
            <a class="nav-link" href="od.php" target="_blank">
              <?php
              if(isset($trans[$lang]['ماتریس OD'])){ 
                echo $trans[$lang]['ماتریس OD'];
              }else{
                echo 'ماتریس OD';
              } 
              ?>
              
            </a>
          </li>
          <?php } ?>
        </ul>
      </li>
      <?php }if(isset($permissions['mode']) || $is_admin){ ?>
      <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-puzzle"></i>
          <?php
          if(isset($trans[$lang]['گزارشات مود سفر'])){ 
            echo $trans[$lang]['گزارشات مود سفر'];
          }else{
            echo 'گزارشات مود سفر';
          } 
          ?>
        
        </a>
        <ul class="nav-dropdown-items">
          <?php if(isset($permissions['mode']['goal']) || $is_admin){ ?>
          <li class="nav-item">
            <a class="nav-link" href="mode.php" target="_blank">
              <?php
              if(isset($trans[$lang]['هدف سفر'])){ 
                echo $trans[$lang]['هدف سفر'];
              }else{
                echo 'هدف سفر';
              } 
              ?>
              
            </a>
          </li>
          <?php }if(isset($permissions['mode']['mode']) || $is_admin){ ?>
          <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-puzzle"></i>
              <?php
              if(isset($trans[$lang]['مد حمل و نقل'])){ 
                echo $trans[$lang]['مد حمل و نقل'];
              }else{
                echo 'مد حمل و نقل';
              } 
              ?>
              
            </a>
            <ul class="nav-dropdown-items">
              <li class="nav-item">
                <a class="nav-link" href="?report=mode&rep_mode=1"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['پیاده'])){ 
                    echo $trans[$lang]['پیاده'];
                  }else{
                    echo 'پیاده';
                  } 
                  ?>
                  
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=mode&rep_mode=2"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['دوچرخه'])){ 
                    echo $trans[$lang]['دوچرخه'];
                  }else{
                    echo 'دوچرخه';
                  } 
                  ?>
                  
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=mode&rep_mode=7"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['موتور'])){ 
                    echo $trans[$lang]['موتور'];
                  }else{
                    echo 'موتور';
                  } 
                  ?>
                  
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=mode&rep_mode=3"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['خودرو'])){ 
                    echo $trans[$lang]['خودرو'];
                  }else{
                    echo 'خودرو';
                  } 
                  ?>
                  
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=mode&rep_mode=8"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['تاکسی'])){ 
                    echo $trans[$lang]['تاکسی'];
                  }else{
                    echo 'تاکسی';
                  } 
                  ?>
                  
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=mode&rep_mode=21"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['مسافرکش'])){ 
                    echo $trans[$lang]['مسافرکش'];
                  }else{
                    echo 'مسافرکش';
                  } 
                  ?>
                  
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=mode&rep_mode=4"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['اتوبوس واحد'])){ 
                    echo $trans[$lang]['اتوبوس واحد'];
                  }else{
                    echo 'اتوبوس واحد';
                  } 
                  ?>
                  
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=mode&rep_mode=22"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['اتوبوس غیرواحد'])){ 
                    echo $trans[$lang]['اتوبوس غیرواحد'];
                  }else{
                    echo 'اتوبوس غیرواحد';
                  } 
                  ?>
                  
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=mode&rep_mode=23"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['مینی بوس'])){ 
                    echo $trans[$lang]['مینی بوس'];
                  }else{
                    echo 'مینی بوس';
                  } 
                  ?>
                  
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=mode&rep_mode=5"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['مترو'])){ 
                    echo $trans[$lang]['مترو'];
                  }else{
                    echo 'مترو';
                  } 
                  ?>
                  
                </a>
              </li>
              <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-puzzle"></i>
                  <?php
                  if(isset($trans[$lang]['باری ها'])){ 
                    echo $trans[$lang]['باری ها'];
                  }else{
                    echo 'باری ها';
                  } 
                  ?>
                  
                </a>
                <ul class="nav-dropdown-items">
                  <li class="nav-item">
                    <a class="nav-link" href="?report=mode&rep_mode=9"><i class="icon-puzzle"></i>
                      <?php
                      if(isset($trans[$lang]['وانت'])){ 
                        echo $trans[$lang]['وانت'];
                      }else{
                        echo 'وانت';
                      } 
                      ?>
                      
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="?report=mode&rep_mode=27"><i class="icon-puzzle"></i>
                      <?php
                      if(isset($trans[$lang]['کامیونت'])){ 
                        echo $trans[$lang]['کامیونت'];
                      }else{
                        echo 'کامیونت';
                      } 
                      ?>
                      
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="?report=mode&rep_mode=24"><i class="icon-puzzle"></i>
                      <?php
                      if(isset($trans[$lang]['کامیون دومحور'])){ 
                        echo $trans[$lang]['کامیون دومحور'];
                      }else{
                        echo 'کامیون دومحور';
                      } 
                      ?>
                      
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="?report=mode&rep_mode=25"><i class="icon-puzzle"></i>
                      <?php
                      if(isset($trans[$lang]['کامیون سه محور'])){ 
                        echo $trans[$lang]['کامیون سه محور'];
                      }else{
                        echo 'کامیون سه محور';
                      } 
                      ?>
                      
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="?report=mode&rep_mode=26"><i class="icon-puzzle"></i>
                      <?php
                      if(isset($trans[$lang]['تریلی'])){ 
                        echo $trans[$lang]['تریلی'];
                      }else{
                        echo 'تریلی';
                      } 
                      ?>
                      
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?report=mode&rep_mode=0"><i class="icon-puzzle"></i>
                      <?php
                      if(isset($trans[$lang]['همه'])){ 
                        echo $trans[$lang]['همه'];
                      }else{
                        echo 'همه';
                      } 
                      ?>
                </a>
              </li>
            </ul>
          </li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
    </ul>
  </nav>
</div>