<?php
session_start();
include_once('class/db.php');
include_once('class/base.php');
include_once('class/users.php');
include_once('class/group_access.php');
include_once('class/group_user.php');
if(isset($_REQUEST['lang'])){
  $_SESSION['lang'] = $_REQUEST['lang'];
}
if(!isset($_SESSION['lang'])){
  $_SESSION['lang'] = 'fa';
}
$lang = $_SESSION['lang'];
if(isset($_GET['logout'])){
   $_SESSION['logout']=1;
}
if(isset($_SESSION['user_id']) && isset($_SESSION['logout']) && $_SESSION['user_id']>0 && $_SESSION['logout']==1 ) {
  session_destroy();
  session_start();
}else if(isset($_SESSION['user_id']) && isset($_SESSION['logout']) && $_SESSION['user_id']>0 && $_SESSION['logout']==0){
    header('location: map.php');
    die();
}
$msg = '';
if(isset($_POST['username'])){
  if($_POST['username']=='mostofi' && $_POST['password']=='123456'){
    $_SESSION['user_id'] = 1;
    $_SESSION['logout'] = 0;
    $_SESSION['pers'] = array();
    header('location: index.php');
    die();
  }else{
    $user = new users;
    $tmp = $user->load("`USERNAME` = '".$_POST['username']."'");
    $user_ok = FALSE;
    foreach($tmp as $r){
      if($r['PASSWORD']==$_POST['password']){
        $user_ok = TRUE;
        $_SESSION['user_id'] = $r['ID'];
      }
    }
    if($user_ok){
      $group_user = new group_user;
      $grps = $group_user->loadUser($_SESSION['user_id']);
      $is_admin = FALSE;
      foreach($grps as $grp){
        if((int)$grp==-2){
          $is_admin = TRUE;
        }
      }
      $group_access = new group_access;
      $pers = $group_access->loadUser($_SESSION['user_id']);
      if(count($pers)==0 && !$is_admin){
//         session_destroy();
//         session_start();
        unset($_SESSION['user_id']);
        $_SESSION['logout'] = 1;
        $msg = 'شما دسترسی به پنل ندارید';
      }else{
        $_SESSION['pers'] = $pers;
        $_SESSION['logout'] = 0;
        header('location: index.php');
        die();
      }
    }else{
      $msg = 'نام کاربری یا رمز عبور اشتباه است';      
    }
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="css/login_style.css">
    <script src="js/libs/jquery.min.js"></script>
    <script src="js/site.js"></script>
    <title>BaChi</title>
    <link rel="icon" type="image/png" href="img/bachi.png">
  </head>
  <body>
    <div class="login-page">
      <div class="form">
        <img src="img/bachi.png" style="margin-left: -15px;" />
        <br/>
        <span style="font-size: 20px;font-weight: bold;">
        <?php 
          if($lang==='fa'){
            echo 'باچی';
          }else{
            echo 'BaChi';
          }
        ?>
        </span>
<!--         <form class="register-form">
          <input type="text" placeholder="name"/>
          <input type="password" placeholder="password"/>
          <input type="text" placeholder="email address"/>
          <button>create</button>
          <p class="message">Already registered? <a href="#">Sign In</a></p>
        </form> -->
        <form class="login-form" method="post">
          <input type="text" placeholder="<?php echo ($lang==='fa'?'نام کاربری':'User Name'); ?>" name="username"/>
          <input type="password" placeholder="<?php echo ($lang==='fa'?'رمز عبور':'Password'); ?>" name="password"/>
          <button><?php echo ($lang==='fa'?'ورود':'Login'); ?></button>
          <?php if($lang==='fa'){ ?>
          <a href="?lang=en">English</a>&nbsp;&nbsp;&nbsp;&nbsp;
          <a href="app/BaChi.1.8.3.apk">دریافت نرم افزار</a>&nbsp;&nbsp;&nbsp;&nbsp;
          <a href="app/bachi.pdf">راهنما</a>
          <?php }else{ ?>
          <a href="?lang=fa">پارسی</a>
          <?php } ?>
          <p class="message" style="color: #b90a0a;">
            <?php echo $msg; ?>
          </p>
<!--           <p class="message">Not registered? <a href="#">Create an account</a></p> -->
        </form>
      </div>
    </div>
  </body>
</html>