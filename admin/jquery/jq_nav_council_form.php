<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	// include 	 $config_form_elements;
	include 	 $config_arrays;
	// include 	 $config_functions_custom;

	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$admin_user = $mydata['adminUser'];
		$this_id = $mydata['adminCouncilSelect'];

	$admin_council_array = array();

$sql = "
SELECT *
FROM `admin_users`
WHERE `admin_user_ID` LIKE '" . $admin_user . "'
";
// echo $sql . '<br />';
$results = mysqli_query($con,$sql);
$cnt = mysqli_num_rows($results) ?? 0;
if( $cnt > 0 )
{
	while( $row = mysqli_fetch_assoc($results) )
	{
		$admin_council_ID = $row['admin_council_ID'];
	}
	$admin_council_array = explode(',',$admin_council_ID);

	//! LETS GET ALL COUNCILS IN THE USERS
	$sql = "
	SELECT DISTINCT(`user_council_ID`)
	FROM `users`
	WHERE 1=1
	";
	// echo $sql . '<br />';
	$results = mysqli_query($con,$sql);
	$distinct_array = array();
	$distinct_cnt = mysqli_num_rows($results) ?? 0;
	while( $row = mysqli_fetch_assoc($results) )
	{
		$distinct_array[] = $row['user_council_ID'];
	}
	// $admin_council_sql = $admin_council_ID !== '9999'
	// 					? "AND `council_ID` IN ('" . implode("','",$admin_council_array) . "')"
	// 					: ""
	// 					;
	$admin_council_sql = $admin_council_ID !== '9999'
						? "AND `council_ID` IN ('" . implode("','",$admin_council_array) . "')"
						: "AND `council_ID` IN ('" . implode("','",$distinct_array) . "')"
						;

	// echo $admin_council_sql . '<br />';
	$sql = "
	SELECT *
	FROM `councils`
	WHERE 1=1
	" . $admin_council_sql . "
	ORDER BY `council_name`
	";
	// echo $sql . '<br />';
	$results = mysqli_query($con_master,$sql);

	$options_array = array();
	$council_cnt = mysqli_num_rows($results) ?? 0;
	while( $row = mysqli_fetch_assoc($results) )
	{
		$council_ID = $row['council_ID'];
		$council_bsa_ID = $row['council_bsa_ID'];
		$council_name = $row['council_name'];

		$options_array[$council_ID] = $council_name . '  (' . $council_bsa_ID . ') ';
	}
}
?>
<div class="col-md-12">
<select class="form-select mb-1" aria-label="Select Council" id="change_council_form">
	<option value="<?php echo $this_id; ?>"> ---- Select One ---- </option>
<?php
	foreach( $options_array AS $key => $value )
	{
		$selectme = $this_id == $key ? 'selected' : '';
		echo '<option value="' . $key . '" ' . $selectme . '>' . $value . '</option>';
	}
?>
</select>
<button type="button" class="btn btn-success" id="change_council_submit"> Submit </button>
</div>
