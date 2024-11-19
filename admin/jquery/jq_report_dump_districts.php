<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	// include 	 $config_form_elements;
	include 	 $config_arrays;
	include 	 $config_functions_custom;

	$db_table 		= 'users';
	$data_results = $data_results_intro = $data_results_table = '';
	$var_ID 		= 'user_ID';
	$var_active 	= 'user_active';
	$default_sort 	= 'user_last_name';

	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$admin_user = $mydata['adminUser'];
		$admin_council_select = $mydata['adminCouncilSelect'];
		// $this_id = $mydata['thisID'];
		// $council = $mydata['councilSelect'];

	$ccs_min_year = date('Y',strtotime('-5 year'));

	$report_fields_array = array(
		'x' => '#',
		'user_bsa_ID' => 'BSA ID',
		'district_name' => 'District',
		'name' => 'Name',
		'user_email' => 'Email',
		'user_basic' => 'Basic',
		'last_ccs' => 'Last CCS',
		'last_degree' => 'Last Degree',
		'next_degree' => 'Next Degree',
		'next_prereq' => 'Next PreReq',
		'ccs_staff' => 'CCS Staff',
	);

	//! composer
	include 	 $composer_autoload;

	//! phpOffice
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	// use PhpOffice\PhpSpreadsheet\Reader\IReader;
	// use PhpOffice\PhpSpreadsheet\Writer\IWriter;
	// use PhpOffice\PhpSpreadsheet\IOFactory;

	$header_array = array();
	$output_data_array = array();

	foreach( $report_fields_array AS $key => $value )
	{
		$header_array[] = $value;
	}

	// $districts_array = array();
	// $sql = "
	// SELECT *
	// FROM `districts`
	// WHERE 1=1
	// AND `district_council_ID` = '" . $admin_council_select . "'
	// ORDER BY `district_name`
	// ";
	// // echo nl2br($sql) . '<br />';
	// $results = mysqli_query($con,$sql);
	// $cnt = mysqli_num_rows($results) ?? 0;
	// if ($cnt > 0) {
	// 	while ($row = mysqli_fetch_assoc($results)) {
	// 		$district_ID = $row['district_ID'];
	// 		$district_name = $row['district_name'];
	// 		$districts_array[$district_ID] = $district_name;
	// 	}
	// }

	$full_path = '/var/www/html/admin';
	$full_path_output_dir = $full_path . '/' . 'reports/';
	$new_title = $admin_council_select . '_' . date('Y-m-d-gis') . '_report_districts';

	$spreadsheet = new Spreadsheet();
	$activeWorksheet = $spreadsheet->getActiveSheet();

	$activeWorksheet->mergeCells('A1:E1');
	$activeWorksheet->setCellValue('A1', 'Districts Report: ' . date('M d, Y - g:i a'));
	$activeWorksheet->fromArray($header_array, null, 'A2');

	$sql = "
	SELECT *
	FROM `users`
	WHERE 1=1
	AND `user_council_ID` = '" . $admin_council_select . "'
	AND LOWER(`user_status`) = 'active'
	AND LOWER(`user_deceased`) <> 'yes'
	AND LOWER(`user_active`) = 'yes'
	ORDER BY `user_last_name`
	LIMIT 10
	";
	// echo nl2br($sql) . '<br />';
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;

	if ($cnt > 0) {
		while ($row = mysqli_fetch_assoc($results)) {
			$row_array = array();

			foreach( $users_fields_array as $key => $value ) {
				$$value = $row[$value];
			}

			//! -------------CUSTOM VALUES -----------
			$district_name = getDistrictName($user_district_ID);
			$name = fullNameList($user_prefix,$user_first_name,$user_nick_name,$user_middle_name,$user_last_name,$user_suffix);

			// $last_ccs =
			// $last_degree =
			// $next_degree =
			// $next_prereq =
			// $ccs_staff = '';

			$last_ccs = lastCCS($user_ID);
			$last_degree = lastDegree($user_ID);
			$next_degree = strpos($last_degree,'MCS:') !== false ? 'DCS' : ( strpos($last_degree,'BCS:') !== false ? 'MCS' : ( strpos($last_degree,'DCS:') !== false ? '<em>none</em>' : 'BCS' ) );
			$next_prereq = nextPrereq($user_ID);
			$ccs_staff = $user_staff_years > 0 && strlen($user_staff_years) > 1 ? 'Yes' : 'No';
			//! -------------CUSTOM VALUES -----------

			foreach( $report_fields_array AS $key => $value )
			{
				$row_array[] = $$key;
			}
			$output_data_array[] = $row_array;
		}
	}

	$count = 3;
	foreach( $output_data_array as $rowNum => $rowData ) {
		$activeWorksheet->fromArray($rowData, null, 'A' . $count);
		$count++;
	}

	$writer = new Xlsx($spreadsheet);
	$writer->save($full_path_output_dir . $new_title . '.xlsx');

echo 'success';
