<?php
session_start();
include_once('class/db.php');
include_once('class/base.php');
include_once('class/users.php');
include_once('class/group.php');
include_once('class/group_user.php');
include_once('class/group_access.php');
if(!isset($_SESSION['user_id'])){
  header('location: login.php?logout=1');
  die();
}
$lang = $_SESSION['lang'];
$page = 'map.php';
if(isset($_REQUEST['page']) && trim($_REQUEST['page'])!=''){
    $page = trim($_REQUEST['page']).'.php';
}
$group_user = new group_user;
$grps = $group_user->loadUser($_SESSION['user_id']);
$is_admin = FALSE;
foreach($grps as $grp){
  if((int)$grp==-2){
    $is_admin = TRUE;
  }
}
if($_SESSION['user_id']==1){
  $is_admin = TRUE;
}
require_once('header.php');
?>
<body class="navbar-fixed sidebar-nav fixed-nav">
    <?php require_once('navbar.php'); ?>
    <?php require_once('menu.php'); ?>
    <!-- Main content -->
    <?php require_once($page); ?>
    <?php require_once('aside-menu.php'); ?>

    <?php require_once('footer.php'); ?>
    <!-- Bootstrap and necessary plugins -->
    <script src="js/libs/jquery.min.js"></script>
    <script src="js/libs/tether.min.js"></script>
    <script src="js/libs/bootstrap.min.js"></script>
    <script src="js/libs/pace.min.js"></script>

    <!-- Plugins and scripts required by all views -->
    <script src="js/libs/Chart.min.js"></script>

    <!-- CoreUI main scripts -->

    <script src="js/app.js"></script>

    <!-- Plugins and scripts required by this views -->
    <!-- Custom scripts required by this view -->
    <!--<script src="js/views/main.js"></script>-->

    <!-- Grunt watch plugin -->
    <!--<script src="//localhost:35729/livereload.js"></script>-->
    <?php if($page=='map.php') require_once('script.php'); ?>
</body>

</html>
