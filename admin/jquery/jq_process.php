<?php  // ** NEW PROCESSOR :: Lampkin :: 2024 ** // ?>
<?php // ** 4.0 | SEPT 2024 ** // ?>
<?php //@ htmlentities | UPLOADS  | htmlentities?? // ?>

<?php
include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
include 	 $config_functions;
include 	 $config_query;
// include 	 $config_form_elements;
include 	 $config_arrays;

//! USAGE ================
// $(document).on("click", '#form-process', function(e) {
// 	e.preventDefault();
// 	console.log('=>>> POST <<<=');
// 	let formData = $('#data_entry').serializeArray();
// 	processData(formData);
// });
// function processData(formData) {
// 	$.ajax({
// 		type: 		"POST",
// 		url: 		"jquery/jq_process.php",
// 		data:		formData,
// 		contentType: false,
// 		cache: 		false,
// 		processData: false,
// 		success:	function(response)
// 		{
// 			let trimResponse = response.trim();
// 			if( trimResponse == 'success' )
// 			{
// 				toastMessage(trimResponse,'')
// 				refreshPage();
// 			}
// 			else
// 			{
// 				$('#dataDump').html(response);
// 			}
// 		},
// 		error: function(response)
// 		{
// 			console.log('ERROR: ' + JSON.stringify(response));
// 		}
// 	});
// }

$today_d = date("Y-m-d");
$today_t = date("H:i:s");
$today_dt = date("Y-m-d") . ' ' . date("H:i:s");
$rightnow = date("Y-m-d") . ' ' . date("H:i:s");

$post_response = 'errox';
$tracking_results = '';

$data = file_get_contents("php://input");
$mydata = json_decode($data, true);
	$admin_user = $mydata['adminUser'];
	$form_data = $mydata['formData'];

//! ---::: set values and create a post_array :::---
$post_array = array();
foreach( $form_data AS $position => $form_data_array )
{
	$field_name = $form_data_array['name'];
	$field_value = $form_data_array['value'];
	$post_array[$field_name] = $field_value;
}
$this_con = isset($post_array['db_name']) && strpos($post_array['db_name'],'master') !== false  ? 'con_master' : 'con';
// $this_test = isset($post_array['test']) && $post_array['test'] == 'yes' ? 'yes' : 'no';
$this_test = $post_array['test'];
unset($post_array['test']);
//! ---::: set values and create a post_array :::---

//! MAIN PROCESSOR STARTS HERE //! MAIN PROCESSOR STARTS HERE //! MAIN PROCESSOR STARTS HERE
if ( isset($post_array['process']) && isset($post_array['id_field']) )
{
	unset($post_array['process']);

	$id_field = $post_array['id_field'];
	$$id_field = $post_array[$id_field];
		unset($post_array['id_field']);
		unset($post_array[$id_field]);
	$db_table = $post_array['db_table'];
		unset($post_array['db_table']);
	$action = $post_array['action'];
		unset($post_array['action']);

		array_filter($post_array);

	//!   GET THE COLUMNS IN THE DATABASE ---------------------|
	$cols_sql = "SHOW COLUMNS FROM " . $db_table;
	$results = mysqli_query($$this_con,$cols_sql);
	$cnt = mysqli_num_rows($results);
	$input_fields_array =  array();
	while( $row = mysqli_fetch_assoc($results) ) { $input_fields_array[] = $row['Field'];}
	$input_fields_array_cnt = count($input_fields_array);
	//!  /GET THE COLUMNS IN THE DATABASE ---------------------|

	//*  pre-PROCESS ---------------------|
	foreach( $post_array AS $key => $value )
	{
		//! FIELDS which start with 'new_' will replace their counterparts.
		$search_value = "new_";
		if( strpos($key,$search_value,0) !== false )
		{
			$old_key = str_replace('new_','',$key);
			$old_key_val = $post_array[$old_key];
			if( $value !== $old_key_val )
			{
				unset($post_array[$old_key]);
				unset($post_array[$key]);
				$post_array[$old_key] = $value;
			}
		}

		$search_value = "check_";
		foreach( $post_array AS $key => $value )
		{
			if( strpos($key,$search_value,0) !== false )
			{
				$var_name = str_replace('check_','',$key);
				$post_array[$var_name] = implode(',', array_filter($post_array[$key]));
				unset($post_array[$key]);
				array_push($post_array,$post_array[$var_name]);
			}
		}

		$search_value = "delete_";
		foreach( $post_array AS $key => $value )
		{
			if( strpos($key,$search_value,0) !== false )
			{
				$var_name = str_replace('delete_','',$key);
				unset($post_array[$key]);
				unset($post_array[$var_name]);
				$post_array[$var_name] = '';
				array_push($post_array,$post_array[$var_name]);
			}
		}
	}
	//*  /pre-PROCESS ---------------------|

	$post_sql_field_array = array();
	$post_sql_value_array = array();
	$post_sql_update_array = array();
	foreach( $post_array as $name => $value )
	{
		if( in_array($name, $input_fields_array) && !empty($name) )
		{
			$post_sql_field_array[] = "`" . $name . "`";
			$post_sql_value_array[] = "'" . $value . "'";
			$post_sql_update_array[$name] = $value;
		}
	}

	//!  PROCESS UPLOADS ---------------------|



	//!  /PROCESS UPLOADS ---------------------|

	//# This function will run within each post array including multi-dimensional arrays
	if (!function_exists('ExtendedAddslash'))
	{
		function ExtendedAddslash(&$params)
		{ foreach ($params as &$var) { is_array($var) ? ExtendedAddslash($var) : $var=addslashes($var); unset($var); } }
		// Initialize ExtendedAddslash() function for every $post_array variable
	}
	ExtendedAddslash($post_array);

	//@  [================ start DATA PROCESSING ================]
	switch ($action)
	{
		case "insert":
			$processor_sql  = " INSERT INTO `" . $db_table . "` (";
			$processor_sql .=  implode(", ",$post_sql_field_array);
			$processor_sql .= ") VALUES (";
			$processor_sql .=  implode(", ",$post_sql_value_array);
			$processor_sql .= ")";
		break;

		case "update":
			$processor_sql  = " UPDATE `" . $db_table . "` SET ";
			foreach( $post_sql_update_array AS $key => $value )
			{
				$processor_sql  .= "`" . $key . "` =  '" . htmlentities($value) ."',";
			}
			$processor_sql  .= " `" . $id_field . "` = '" . $$id_field . "'";
			$processor_sql .= " WHERE `" . $id_field . "` = '" . $$id_field . "'";
		break;

		default:
			$post_response = 'error: ' . $processor_sql;
			$processor_sql = '';
		}

		$tracking_results .= 'processor_sql: ' . nl2br($processor_sql) . '</strong></span><br />';

	if( $this_test !== 'yes' )
	{
		if( mysqli_query($$this_con, $processor_sql)	)
		{
			$post_response = 'success';
		}
		else
		{
			$post_response = 'error: ' . $processor_sql;
		}

		echo $post_response;
	}
	else
	{
		$post_response = 'test|' . $processor_sql;
		echo $post_response;

		// echo 'TESTING - NO ENTRY: ' . $processor_sql . ' | ' . $tracking_results;
	}
}
?>
