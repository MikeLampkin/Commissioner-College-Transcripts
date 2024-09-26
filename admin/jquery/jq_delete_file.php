<?php
	include_once "config/config.php";
	include_once "includes/functions.php";
	include_once "includes/query.php";

	$data = file_get_contents("php://input");
	$mydata = json_decode($data,true);
		$file = $mydata['file'];
		$directory = $mydata['directory'];
	$file_loc = '/var/www/html/admin/' . $directory . '/' . $file;
	unlink($file_loc);
	echo 'success';
?>
