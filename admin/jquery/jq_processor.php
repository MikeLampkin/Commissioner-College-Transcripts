<?php
//!  PROCESSOR 2023 :: 3.1 -- NOV 2023
//# ADDED: delete file
//# ADDED: NULL to empty data

	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/functions.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/query.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/arrays.php';

	$post_response = 'error';

	$data = file_get_contents("php://input");
	// $mydata = json_decode($data,true);

	$thisCon = 'con';
	// if ( isset($_POST['db_name']) && $_POST['db_name'] !== $db_name )
	// {
	// 	$thisCon = str_replace('hpl_', 'con_', $_POST['db_name']);
		unset($_POST['db_name']);
	// }

	$process_dump = '';
//! MAIN PROCESSOR STARTS HERE //! MAIN PROCESSOR STARTS HERE //! MAIN PROCESSOR STARTS HERE
if ( isset($_POST['process']) && isset($_POST['id_field']) )
{
	unset($_POST['process']);

	$id_field = $_POST['id_field'];
		unset($_POST['id_field']);
	$db_table = $_POST['db_table'];
		unset($_POST['db_table']);
	$action = $_POST['action'];
		unset($_POST['action']);

		array_filter($_POST);

	//!   GET THE COLUMNS IN THE DATABASE ---------------------|
	$cols_sql = "SHOW COLUMNS FROM " . $db_table;
	$results = mysqli_query($$thisCon,$cols_sql);
	$cnt = mysqli_num_rows($results);
	$inputFieldList =  '';
	$x=0;
	while( $row = mysqli_fetch_assoc($results) ) { $inputFieldList .=  $row['Field']; $x++; if ($x < $cnt) { $inputFieldList .=  ','; } }
	$inputFieldArray = explode(',', $inputFieldList);
	$inputFieldArray_cnt = count($inputFieldArray);
	//!  /GET THE COLUMNS IN THE DATABASE ---------------------|

	foreach( $_POST AS $key => $value )
	{
		if( is_array($value) ) { $new_value = ''; foreach( $value AS $vvalue ) { $new_value .= $vvalue; } } else { $new_value = $value; }
	}

	//*  /pre-PROCESS ---------------------|

	//! FIELDS which start with 'new_' will replace their counterparts.
	$search_value = "new_";
	if( strpos($key,$search_value,0) !== false )
	{
		$old_key = str_replace('new_','',$key);
		$old_key_val = $_POST[$old_key];
		if( $value !== $old_key_val )
		{
			unset($_POST[$old_key]);
			unset($_POST[$key]);
			$_POST[$old_key] = $value;
		}
	}

	$search_value = "check_";
	foreach( $_POST AS $key => $value )
	{
		if( strpos($key,$search_value,0) !== false )
		{
			$var_name = str_replace('check_','',$key);
			$_POST[$var_name] = implode(',', array_filter($_POST[$key]));
			unset($_POST[$key]);
			array_push($_POST,$_POST[$var_name]);
		}
	}

	$search_value = "delete_";
	foreach( $_POST AS $key => $value )
	{
		if( strpos($key,$search_value,0) !== false )
		{
			$var_name = str_replace('delete_','',$key);
			unset($_POST[$key]);
			unset($_POST[$var_name]);
			$_POST[$var_name] = '';
			array_push($_POST,$_POST[$var_name]);
		}
	}

	//*  /pre-PROCESS ---------------------|

	//!  /PROCESS UPLOADS ---------------------|
	if( isset($_POST['attachment_field_name']) )
	{
		$attachment_field_name 	= $_POST['attachment_field_name'];
		include "jq_processor_files.php";
		unset($_POST['attachment_field_name']);
	}
	//!  /PROCESS UPLOADS ---------------------|

	//!  /PROCESS FILE DELETE ---------------------|
	if( isset( $_POST['delete_file'] ) )
	{
		$file_name = trim($_POST['file_name']);
		$file_directory = trim($_POST['file_directory']);
		$file_path = '/var/www/html/'. $file_directory . '/' . $file_name;
		if(file_exists($file_path))
		{
			unlink($file_path);
		}
		unset($_POST['delete_file']);
		unset($_POST['file_name']);
		unset($_POST['file_directory']);
	}
	//!  /PROCESS FILE DELETE ---------------------|

	$post_cnt=0;
	foreach ( $_POST as $name => $value )
	{
		if ( in_array($name, $inputFieldArray) && !empty($name) )
		{
			$post_cnt++;
		}
	}
	$post_cnt = $post_cnt-1; // Remove the ID in the count.

	$process_dump .= 'count of _POST: <strong>' . count($_POST) . '</strong></span><br />';
	$process_dump .= 'count of post_cnt: <strong>' . $post_cnt . '</strong></span><br />';

	//  [================ start DATA PROCESSING ================]
	// This function will run within each post array including multi-dimensional arrays
	if (!function_exists('ExtendedAddslash'))
	{
		function ExtendedAddslash(&$params)
		{ foreach ($params as &$var) { is_array($var) ? ExtendedAddslash($var) : $var=addslashes($var); unset($var); } }
		// Initialize ExtendedAddslash() function for every $_POST variable
	}
	ExtendedAddslash($_POST);

	switch ($action)
	{
		case "insert":
			if ( !isset($_POST['keep_id']) ) // KEEP THE CUSTOM ID
			{
				unset($_POST[$id_field]);
			}
				unset($_POST['keep_id']);

			$processor_sql  = "
				INSERT INTO `" . $db_table . "`
				(";
				$x = 0;
				// LOOP THROUGH FIELDNAMES
				foreach( $_POST as $name => $value )
				{
					if ( in_array($name, $inputFieldArray) && !empty($name) )
					{
						++$x;
						$processor_sql .= "`". trim($name) . "`";
						if ($x > 0 && $x < $post_cnt)
						{
							$processor_sql .= ", ";
						}
					}
				}
			$processor_sql .= "
				)
				VALUES (";
				$x = 0;
				foreach ( $_POST as $name => $value )
				{
					if ( in_array($name, $inputFieldArray) && !empty($name) )
					{
						++$x;
						if( strlen($value) < 1 )
						{
							$processor_sql .= "NULL";
						}
						else
						{
							$processor_sql .= "'". trim($value) . "'";
						}
						if ($x > 0 && $x < $post_cnt)
						{
							$processor_sql .= ", ";
						}
					}
				}
			$processor_sql .= "
					)
			";
		break;

		case "update":
			$myID = $_POST[$id_field];

			unset($_POST['keep_id']);
			unset($_POST[$id_field]);

			$processor_sql  = "
				UPDATE `" . $db_table . "`
				SET ";
				$x = 0;

			foreach ( $_POST as $name => $value )
			{
				if ( in_array($name, $inputFieldArray) && !empty($name) )
				{
					++$x;
					if( strlen($value) < 1 )
					{
						$processor_sql .= "`". $name . "` = NULL";
					}
					else
					{
						$processor_sql .= "`". $name . "` = '" . $value . "'";
					}
					if ($x > 0 && $x < $post_cnt)
					{
						$processor_sql .= ", ";
					}
				}
			}

			$processor_sql .= "
				WHERE `" . $id_field . "`	= '" . $myID . "'
			";
		break;

		default:
			$post_response = 'error: ' . $processor_sql;
			$processor_sql = '';
		}

	$process_dump .= 'processor_sql: ' . nl2br($processor_sql) . '</strong></span><br />';

	if( mysqli_query($$thisCon, $processor_sql)	)
	{
		$post_response = 'success';
	}
	else
	{
		$post_response = 'error: ' . $processor_sql;
	}
}
echo $post_response;
?>
