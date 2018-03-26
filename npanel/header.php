<?php
?>
<!--
 * CoreUI - Open Source Bootstrap Admin Template
 * @version v1.0.0-alpha.2
 * @link http://coreui.io
 * Copyright (c) 2016 creativeLabs Łukasz Holeczek
 * @license MIT
 -->
<!DOCTYPE html>
<html lang="IR-fa" dir="rtl">

<head><meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="CoreUI Bootstrap 4 Admin Template">
    <meta name="author" content="Lukasz Holeczek">
    <meta name="keyword" content="CoreUI Bootstrap 4 Admin Template">
    <!-- <link rel="shortcut icon" href="assets/ico/favicon.png"> -->
    <title>Bachi</title>
    <!-- Icons -->
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/simple-line-icons.css" rel="stylesheet">
    <!-- Main styles for this application -->
    <link href="dest/style.css" rel="stylesheet">
    <link href="css/jquery-ui.css" rel="stylesheet">
    <link href="js/cal/skins/aqua/theme.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="img/bachi.png">
	<style type="text/css">
					.ui-dialog { z-index: 1000 !important ;}
					html, body {
							width: 100%;
							height: 100%;
							margin: 0;
					}
					#basicMap{
							width: 100%;
							height: 635px;
					}
					a {color:#FF0000;padding: 5px;text-decoration:none}      /* unvisited link */
					a:visited {color:#FF0000;}  /* visited link */
					a:hover {color:#FF00FF;}  /* mouse over link */
					a:active {color:#FF0000;}
					a.selected {background: #d2ef43;}
					#leg {
						position: absolute;
						bottom: 0;
						background: #ffffff;
						padding: 20px;
						z-index: 749;
						left: 0;
						border: solid #eaeaea 4px;
						overflow-y: scroll;
						max-height: 310px;
					}
					#head{
						position: absolute;
						top: 0;
						background: #8DC26F;
						padding: 20px;
						z-index: 750;
						left: 0;
						width:100%;
						border: solid #eaeaea 4px;
						text-align:center;
						font-weight:bold;
					}
					#rightleg {
						position: absolute;
						bottom: 0;
						background: #ffffff;
						padding: 20px;
						z-index: 1004;
						right: 0;
						border: solid #eaeaea 4px;
						overflow-y: scroll;
						max-height: 310px;
					}
					#frm{
						text-align: right;
						direction:rtl;
						padding:5px;
						font-weight:bold;
					}
					#frm select{
						width:100%;
						margin:5px
					}
					.man{
						padding: 5px;
						cursor: pointer;
					}
		/*       #frm button{
						width:100%;
						margin:5px;
						font-weight:bold;
					} */
		@font-face{ 
			font-family: 'BYekanFont';
			src: url('font/BYekan.eot');/*
			src: url('font/BYekan.eot?#iefix') format('embedded-opentype'),
					 url('font/BYekan.woff') format('woff'),
					 url('font/BYekan.ttf') format('truetype'),
					 url('font/BYekan.svg#webfont') format('svg');*/
		}
				</style>
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
<!-- BODY options, add following classes to body to change options
		1. 'compact-nav'     	  - Switch sidebar to minified version (width 50px)
		2. 'sidebar-nav'		  - Navigation on the left
			2.1. 'sidebar-off-canvas'	- Off-Canvas
				2.1.1 'sidebar-off-canvas-push'	- Off-Canvas which move content
				2.1.2 'sidebar-off-canvas-with-shadow'	- Add shadow to body elements
		3. 'fixed-nav'			  - Fixed navigation
		4. 'navbar-fixed'		  - Fixed navbar
	-->