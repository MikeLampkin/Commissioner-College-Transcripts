<?php  // ** Lampkin 2024 ** // ?>
<?php // ** HEADER public 10 - Bootstrap 5.3  ** // ?>
<!doctype html>
<html lang="en" dir="ltr" xml:lang="en">
<?php
	if(!isset($_SESSION) )
	{
		session_start();
		// Server should keep session data for AT LEAST 1 hour
		@ini_set('session.gc_maxlifetime', 360000);
		// Each client should remember their session id for EXACTLY 1 hour
		@session_set_cookie_params(360000);
	}

	// if( basename(__FILE__, '.php') == 'logout' ) { header('Location: includes/logout.php'); }
	if( isset($_GET['logout']) ) { include 'includes/logout.php'; }

	require "config/config.php";
	// header('Set-Cookie: cross-site-cookie=name; SameSite=None; Secure');

	// SHOW ERRORs
	if ( $show_errors == 'yes' ) { ini_set('display_errors',1); error_reporting(E_ALL|E_STRICT); }

	 include 'includes/geoplugin.php';

?>


	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />

		<title><?php if($pg !== 'home' || $pg !== 'index') { echo $pg_title . ' | ' ; } else { echo 'Welcome to '; } ?> Commissioner College Transcript System </title>

		<meta name="description" content="Commissioner College Transcript System">
		<meta name="keywords" content="">

		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!--- META TAGS --->

		<!---meta name="format-detection" content="telephone=no" /--->
		<meta http-equiv="content-language" content="en">
		<meta name="robots" content="noindex,nofollow" />
		<meta name="classification" content="recreation" />
		<meta name="copyright" content="&copy; 2010-2024 Michael H Lampkin. All rights reserved." />
		<meta name="msvalidate.01" content="1B0F965185BC8E4E8EBC1FA2F7F66780" />
		<meta name="google-site-verification" content="nvCelYGc5aUWP4ffKf-TOOU-QVgOVryiv_H8ixX6bRc" />
		<meta name="author" content="Mike Lampkin" />
		<!--- /META TAGS --->

		<?php // JQUERY :: 2024 :: https://releases.jquery.com/  ?>
		<script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>

		<?php //! Bootstrap  -- Feb 2024  ?>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

		<?php // Font Awesome Kit -- LAMPKIN  ?>
		<!-- <script src="https://kit.fontawesome.com/2a2cf637f6.js" crossorigin="anonymous"></script> -->
		<script src="https://kit.fontawesome.com/a54ef717a2.js" crossorigin="anonymous"></script>

		<?php // 2024 AdSense Google  ?>
		<!-- <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6055240313710130"
     crossorigin="anonymous"></script>
		<meta name="google-adsense-account" content="ca-pub-6055240313710130"> -->

		<!-- Google Font -->
		<link rel="preconnect" href="https://fonts.gstatic.com">

		<link rel="stylesheet" type="text/css" href="css/styles.css" />
		<link rel="stylesheet" type="text/css" href="css/media.css" />
		<link rel="stylesheet" type="text/css" href="css/custom.css" />
		<link rel="stylesheet" type="text/css" href="css/colors.css" />
	</head>

	<body>
		<header>
			<?php
				include_once "includes/functions.php";
				include_once "includes/functions_custom.php";
				include_once "includes/query.php";
				include_once "includes/arrays.php";
			?>
			<script src="js/arrays.js"></script>
			<script src="js/ajax_functions.js"></script>
			<?php
				//!! AUTHENTICATION
				$page_is_protected = isset($page_is_protected) ? strtolower($page_is_protected) : 'no' ;
				if( $page_is_protected == 'yes' ) { include "includes/authenticate.php"; }
				//!! AUTHENTICATION
				$loggedin = false;
			?>
		</header>

		<section class="main">
			<?php
				include_once 'includes/navbar.php';
				include_once 'includes/alert.php';
				include_once 'includes/form_elements.php';
			?>
<input type="hidden" id="adminUser" value="<?php echo $admin_user_ID; ?>">
<input type="hidden" id="adminCouncilID" value="<?php echo $admin_council_ID; ?>">

<a id="top" class="top"> </a>
<!-- // ** Lampkin 2024 ** // -->
<div class="container-fluid">
<div class="toast-container position-absolute top-10 end-0 p-3" id="toaster"></div>

	<div class="row">
		<div class="col-md-5 h1 text-main">
			<span id="page_icon" class="page_icon_title"><?php echo $pg_icon; ?></span>
			<span id="page_title" class="page_icon_title"><?php echo $pg_title; ?></span>
		</div>
		<div class="col-md-5 text-center align-middle"><span id="messageBox"> </span></div>
		<div class="col-md-2 text-end align-text-bottom"><small><i class="fad fa-calendar-star"></i> <?php echo date('M d, Y') . "   " ; ?><i class="fad fa-clock"></i> <span id="myClock"></span></small>
		</div>
	</div>
<div id="dataDump"></div>
