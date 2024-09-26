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

	$users_report_fields_array = array(
		'user_ID' => 'DB ID',
		'user_bsa_ID' => 'BSA ID',
		'user_council_ID' => 'Council ID',
		'user_prefix' => 'Prefix',
		'user_first_name' => 'First Name',
		'user_middle_name' => 'Middle Name',
		'user_last_name' => 'Last Name',
		'user_nick_name' => 'Nick Name',
		'user_maiden_name' => 'Maiden Name',
		'user_suffix' => 'Suffix',
		'user_dob' => 'DOB',
		'user_address' => 'Address',
		'user_address2' => 'Address2',
		'user_city' => 'City',
		'user_state' => 'State',
		'user_zip' => 'Zip',
		'user_phone' => 'Phone',
		// 'user_phone2' => 'Phone 2',
		'user_email' => 'Email',
		'user_district' => 'District',
		'user_deceased' => 'Deceased',
		// 'user_faststart' => 'Fast Start',
		// 'user_basic' => 'Basic',
		// 'user_arrowhead' => 'Arrowhead',
		// 'user_comm_key' => 'Comm Key',
		// 'user_distinguished' => 'Distinguished',
		// 'user_excellence' => 'Excellence',
		// 'user_bcs' => 'BCS',
		// 'user_mcs' => 'MCS',
		// 'user_dcs' => 'DCS',
		'user_pro' => 'Pro',
		'user_positions' => 'Positions',
		'user_status' => 'Status',
		'user_staff_years' => 'Staff',
		'user_first_update' => 'First Update',
		'user_last_update' => 'Last Update',
		'user_active' => 'DB Active'
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
	$userdata_array = array();


	foreach( $users_report_fields_array AS $key => $value )
	{
		$header_array[] = $value;
	}

	$sql = "
	SELECT *
	FROM `users`
	WHERE `user_council_ID` = '" . $admin_council_select . "'
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

			$user_district = 'test';

			foreach( $users_report_fields_array AS $key => $value )
			{
				$row_array[] = $$key;
			}

			$userdata_array[] = $row_array;

		}
	}

	$full_path = '/var/www/html/admin';
	$full_path_output_dir = $full_path . '/' . 'reports/';
	$new_title = $admin_council_select . '_' . date('Y-m-d-gis') . '_report_users';

	$spreadsheet = new Spreadsheet();
	$activeWorksheet = $spreadsheet->getActiveSheet('CustomName');

	// $spreadsheet->getProperties()
    // ->setCreator("CommissionerCollege.com")
    // ->setLastModifiedBy("Admin")
    // ->setTitle("Office 2007 XLSX Test Document")
    // ->setSubject("Office 2007 XLSX Test Document")
    // ->setDescription(
    //     "Test document for Office 2007 XLSX, generated using PHP classes."
    // )
    // ->setKeywords("office 2007 openxml php")
    // ->setCategory("Test result file");

	$activeWorksheet->mergeCells('A1:E1');
	$activeWorksheet->setCellValue('A1', 'Users Report: ' . date('M d, Y - g:i a'));
	$activeWorksheet->fromArray($header_array, null, 'A2');

	$count = 3;
	foreach( $userdata_array as $rowNum => $rowData ) {
		$activeWorksheet->fromArray($rowData, null, 'A' . $count);
		$count++;
	}

	$writer = new Xlsx($spreadsheet);
	$writer->save($full_path_output_dir . $new_title . '.xlsx');

echo 'success';
