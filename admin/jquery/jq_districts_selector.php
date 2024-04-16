<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/functions.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/query.php';
	// include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/form_elements.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/arrays.php';

	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/functions_custom.php';

	$db_table 		= 'districts';
	$data_results 	= '';
	$var_ID 		= 'district_ID';
	$var_active 	= 'district_active';

	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$admin_user = $mydata['adminUser'];
		$council = $mydata['councilSelect'];
		$district = $mydata['districtSelect'];
		$pg_active = $mydata['pgActive'];
		$this_id = $mydata['thisID'];

	$data_array = array();
	$sql = "
	SELECT *
	FROM `districts`
	WHERE 1=1
	AND `district_council_ID` = '" . $council . "'
	AND `district_active` = 'yes'
	ORDER BY `district_name`
	";
	// $data_results .= $sql;
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;

	// $data_results .= '<select class="form-select form-select-sm" data-field="user_district_ID" name="user_district_ID" id="user_district_ID" aria-label="Select District">';
	$data_results .= '<option data-info="" value="">** Please Select -' . $district . ' **</option>';
	if( $cnt > 0 )
	{
		while( $row = mysqli_fetch_assoc($results) )
		{
			foreach( $districts_fields_array as $key => $value )
			{
				$$value = $row[$value];
			}
			$data_array[$district_ID] = $district_name;
		}

		asort($data_array);

		foreach( $data_array AS $key => $value )
		{
			$selectme = $key == $district ? 'selected' : '';
			$data_results .= '<option ' . $selectme . ' value="' . $key . '">' . $value . ' </option>';
		}

	}
	$data_results .= '<option data-info="" value="">------</option>';
	$data_results .= '<option data-info="2" value="2">Council</option>';
	$data_results .= '<option data-info="1" value="1">Scoutreach</option>';

	// $data_results .= '</select>';

	echo $data_results;
?>
