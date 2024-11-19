#!/usr/bin/php
<?php date_default_timezone_set('America/Chicago'); ?>
<?php
//! Version 1.0 -- 10/2024
//! This scripts tests the cron

	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	// include 	 $config_form_elements;
	include 	 $config_arrays;
	include 	 $config_functions_custom;

	$subject = 'Cron Tester has run: ' . date('M d, Y - g:i a');
	$message = 'CommissionerCollege.com Cron has run:  ' . date('M d, Y - g:i a');

	$replyto_name = 'Mike Lampkin';
	$replyto_address = 'mike@lampkin.net';

	$recipient_name = 'Mike Lampkin';
	$recipient_address = 'mike.lampkin@houstontx.gov';
	include $config_mailme;

?>

<?php echo 'Cron Tester run: ' . date('M d, Y - g:i a'); ?>
