<?php date_default_timezone_set('America/Chicago'); ?>
<?php
//! Version 1.0 -- 09/2022
//! This scripts deletes files older than 60 days
$full_path_dir = '/var/www/html/branchdocs/admin/uploads/';
$clean_dir = array_diff(scandir($full_path_dir,1), array('HOLD','..', '.','.DS_Store'));
$clean_dir_cnt = count($clean_dir);
$file_array = array();
//! 3: Loop through all the files in files_sftp
foreach ($clean_dir as $key => $file_name)
{
	$key = $key+1;
	$full_path_file = $full_path_dir . '/' . $file_name;

	$file_name_array = explode("_",$file_name);
		$file_date 		= $file_name_array[0];
		$file_date_nice = date ("Y-m-d", strtotime($file_date));

	$sixty_marker = date('Y-m-d',strtotime('-60 days'));

	if( strtotime($file_date_nice) < strtotime($sixty_marker) )
	{
		unlink($full_path_file);
	}
}
?>
