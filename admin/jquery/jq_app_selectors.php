<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/functions.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/query.php';
	// include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/form_elements.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/arrays.php';

	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/functions_custom.php';


	$data = file_get_contents("php://input");
	$mydata = json_decode($data,true);
		$admin_user = $mydata['adminUser'];
		$admin_council_ID = $mydata['adminCouncilID'];
		$select_field = $mydata['selectField'];
		$select_term = $mydata['selectTerm'];
		$select_data = $mydata['sessionVal'];
		$tooltip = $mydata['tooltip'];
		// $db_table = $mydata['dbTable'];
		// 	$ext_array = explode('_',$select_field);
		// 	$ext = $ext_array[0];

	$selectme = $select_data == 'all' ? 'selected' : '';

	$data_results = '<div class="row mb-3">';
	$data_results .= '<span id="' . $select_term . 'Form" for="' . $select_term . 'Select" class="col-sm-4 col-form-label col-form-label-sm text-end"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="' . $tooltip . '">';
		$data_results .= ucfirst($select_term);
	$data_results .= '</span>';
	$data_results .= '<div class="col-sm-8"><select class="form-select form-select-sm selector-action" data-field="' . $select_term . '" id="' . $select_term . 'Selector" aria-label="Select ' . ucfirst($select_term) . '">';
	$data_results .= '<option ' . $selectme . ' data-info="all" value="all">ALL </option>';

	$sql = "
	SELECT DISTINCT(`" . $select_field . "`)
	FROM `users`
	WHERE 1=1
	AND `" . $select_field . "` <> ''
	AND `" . $select_field . "` IS NOT NULL
	AND `user_active` = 'yes'
	ORDER BY `" . $select_field . "`
	";
	// echo nl2br($sql) . '<br />';
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results);
	$output_array = array();
	$x=0;
	if ( $cnt > 0 )
	{
		while( $row = mysqli_fetch_assoc($results) )
		{
			$$select_field = $row[$select_field];
			// $select_field_term = ucfirst($$select_field);
			$select_field_term = $select_field == 'user_council_ID' ? getCouncilFromID($$select_field) : ucfirst($$select_field);
			$output_array[$x] = $select_field_term . '|' . $$select_field;
			$x++;
		}
	}

	if( $select_field == 'user_active' ) { $output_array[1] = 'No|no';}

	asort($output_array);
	foreach($output_array AS $key => $value )
	{
		$value_array = explode('|', $value);
			$val_item = $value_array[1];
			$val_name = $value_array[0];
		$selectme = $select_data == $val_item ? 'selected' : '';
		$data_results .= '<option ' . $selectme . ' data-info="' . $val_item . '" value="' . $val_item . '" data-field="' . $select_field . '">' . $val_name . '</option>';
	}


	$data_results .= '</select>';
	$data_results .= '</div>';
	$data_results .= '</div>';

echo trim($data_results);

?>
