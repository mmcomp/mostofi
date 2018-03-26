<?php
session_start();
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
    header('location: map.php');
    die();
  }else{
    $msg = 'Wrong Login Information!';
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery-2.1.4.min.js"></script>
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
        باچی
        </span>
<!--         <form class="register-form">
          <input type="text" placeholder="name"/>
          <input type="password" placeholder="password"/>
          <input type="text" placeholder="email address"/>
          <button>create</button>
          <p class="message">Already registered? <a href="#">Sign In</a></p>
        </form> -->
        <form class="login-form" method="post">
          <input type="text" placeholder="نام کاربری" name="username"/>
          <input type="password" placeholder="رمز عبور" name="password"/>
          <button>ورود</button>
          <a href="app/BaChi.1.6.9.apk">دریافت نرم افزار</a>&nbsp;&nbsp;&nbsp;&nbsp;
          <a href="app/bachi.pdf">راهنما</a>
          <p class="message" style="color: #b90a0a;">
            <?php echo $msg; ?>
          </p>
<!--           <p class="message">Not registered? <a href="#">Create an account</a></p> -->
        </form>
      </div>
    </div>
  </body>
</html>