<?php
?>
    <header class="navbar">
        <div class="container-fluid">
            <button class="navbar-toggler mobile-toggler hidden-lg-up" type="button">&#9776;</button>
            <a class="navbar-brand" href="#"></a>
            <ul class="nav navbar-nav hidden-md-down">
                <li class="nav-item">
                    <a class="nav-link navbar-toggler layout-toggler" href="#">&#9776;</a>
                </li>

                <!--<li class="nav-item p-x-1">
                    <a class="nav-link" href="#">داشبورد</a>
                </li>
                <li class="nav-item p-x-1">
                    <a class="nav-link" href="#">Users</a>
                </li>
                <li class="nav-item p-x-1">
                    <a class="nav-link" href="#">Settings</a>
                </li>-->
            </ul>
            <ul class="nav navbar-nav pull-left hidden-md-down">
                <!--<li class="nav-item">-->
                <!--    <a class="nav-link aside-toggle" href="#"><i class="icon-bell"></i><span class="tag tag-pill tag-danger">5</span></a>-->
                <!--</li>-->
                <!--<li class="nav-item">-->
                <!--    <a class="nav-link" href="#"><i class="icon-list"></i></a>-->
                <!--</li>-->
                <!--<li class="nav-item">-->
                <!--    <a class="nav-link" href="#"><i class="icon-location-pin"></i></a>-->
                <!--</li>-->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                      <?php
                      if($_SESSION['user_id']==1){
                      ?>
                        <img src="img/avatars/hamid_mostofi.png" class="img-avatar" alt="مدیر">
                        <span class="hidden-md-down">مدیر</span>
                      <?php
                      }else{
                        $user = new users($_SESSION['user_id']);
                        if(file_exists("img/avatars/".$_SESSION['user_id'].".jpg")){
                          echo '<img src="img/avatars/'.$_SESSION['user_id'].'.jpg" class="img-avatar" alt="'.$user->FNAME.' '.$user->LNAME.'">';
                        }else{
                          echo '<img src="img/avatars/avatar.jfif" class="img-avatar" alt="'.$user->FNAME.' '.$user->LNAME.'">';
                        }
                        echo '<span class="hidden-md-down">'.$user->FNAME.' '.$user->LNAME.'</span>';
                      }
                      ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                      <div class="dropdown-header text-xs-center">
                          <strong>کاربر</strong>
                      </div>
                      <?php if($is_admin){ ?>
                      <a class="dropdown-item" href="?page=group"><i class="fa fa-user"></i>مدیریت گروه</a>
                      <a class="dropdown-item" href="?page=group_user"><i class="fa fa-user"></i>مدیریت کاربر</a>
                      <a class="dropdown-item" href="?page=access"><i class="fa fa-user"></i>مدیریت دسترسی</a>
                      <?php } ?>
                        <!--<a class="dropdown-item" href="#"><i class="fa fa-user"></i> پروفایل</a>-->
                        <!--<a class="dropdown-item" href="#"><i class="fa fa-wrench"></i> تنظیمات</a>-->
                        <!--<a class="dropdown-item" href="#"><i class="fa fa-usd"></i> Payments<span class="tag tag-default">42</span></a>-->
                        <div class="divider"></div>
                        <a class="dropdown-item" href="login.php?logout=1"><i class="fa fa-lock"></i> خروج</a>
                    </div>
                </li>
                <!--<li class="nav-item">-->
                <!--    <a class="nav-link navbar-toggler aside-toggle" href="#">&#9776;</a>-->
                <!--</li>-->

            </ul>
        </div>
    </header>
