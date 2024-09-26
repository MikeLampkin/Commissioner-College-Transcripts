<?php
include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
include 	 $config_functions;
include 	 $config_query;
// include 	 $config_form_elements;
include 	 $config_arrays;

$today_d = date("Y-m-d");
$today_t = date("H:i:s");
$today_dt = date("Y-m-d") . ' ' . date("H:i:s");
$rightnow = date("Y-m-d") . ' ' . date("H:i:s");

$process_sql = 'NODATA';

// include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions_custom.php';
$data_results = 'success';

$pg 		= 'thesis';
$db_table 	= 'thesis';

$var_ID 	= 'thesis_ID';
$var_active = 'thesis_active';
// $action 	= 'insert';
// $this_id 	= '';

$data = file_get_contents("php://input");
$mydata = json_decode($data, true);
	$admin_user = $mydata['adminUser'];
	$form_data = $mydata['formData'];

//! ---::: set values and create a post_array :::---

$thesis_ID = $_POST['thesis_ID'];
$thesis_council_ID = $_POST['thesis_council_ID'];
// $thesis_desc = $_POST['thesis_desc'];
$upload_file = $_POST['upload_file'];

// $file_name = $_POST['file_name'];

// echo '<h3>thesis_ID: ' . $thesis_ID . '</h3>';
// echo '<h3>thesis_council_ID: ' . $thesis_council_ID . '</h3>';
// echo '<h3>thesis_desc: ' . $thesis_desc . '</h3>';
// echo '<h3>file_name: ' . $_FILES["file"]["name"] . '</h3>';

$home_path = getcwd();
$upload_dir = 'thesis/';

$full_path = '/var/www/html/' . $upload_dir;
$clean_dir = array_diff(scandir($full_path,1), array('HOLD','..', '.','Archive'));
$file_cnt = count($clean_dir);

$allow_types = array('pptx','pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg', 'xlsx');

	// Upload file
	$uploaded_file = '';
	if(!empty($_FILES["file"]["name"]))
	{
		//! File path configs
		$file_name = basename($_FILES["file"]["name"]);

		//! Let's clean the name & remove spaces
		$append_name = $thesis_council_ID;
		$new_file_name = $append_name . '_ ' . str_replace(' ','_',cleanName($file_name));
		//!==============================
		$target_file_path = $full_path . $new_file_name;
		$file_type = pathinfo($target_file_path, PATHINFO_EXTENSION);

		// Allow certain file formats to upload
		// if(in_array($file_type, $allow_types))
		// { }
			//! Upload file to the server
			if(move_uploaded_file($_FILES["file"]["tmp_name"], $target_file_path))
			{
				$uploaded_file = $new_file_name;

				if( $thesis_ID > 0 )
				{
					$process_sql = "
					UPDATE `thesis`
					SET
					`thesis_file` = '" . $uploaded_file . "'
					WHERE `thesis_ID` = '" . $thesis_ID . "'
					";
					if( mysqli_query($con,$process_sql) )
					{
						$data_results = 'success';
					}
					else
					{
						$data_results = 'error';
					}
				}
			}

	}

echo trim($data_results);
