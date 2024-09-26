<?php date_default_timezone_set('America/Chicago'); ?>
<?php
//! Version 1.0 -- 09/2022
//! This scripts deletes files older than 30 days
$full_path_dir = '/var/www/html/admin/reports/';
$clean_dir = array_diff(scandir($full_path_dir,1), array('HOLD','..', '.','.DS_Store','index.php'));
$clean_dir_cnt = count($clean_dir);
$file_array = array();
//! Loop through all the files in files_sftp
foreach ($clean_dir as $key => $file_name)
{
	$key = $key+1;
	$full_path_file = $full_path_dir . '/' . $file_name;
	$file_date = date('Y-m-d',filemtime('/var/www/html/admin/reports/' . $file_name));
	$expire_date = date('Y-m-d',strtotime('-1 hour'));

	if( strtotime($file_date) < strtotime($expire_date) )
	{
		unlink($full_path_file);
	}
}
?>
