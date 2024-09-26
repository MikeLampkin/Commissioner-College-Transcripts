<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	// include 	 $config_form_elements;
	include 	 $config_arrays;
	include 	 $config_functions_custom;

	$db_table 		= 'users';
	$data_results = '';
	$var_ID 		= 'user_ID';
	$var_active 	= 'user_active';
	$default_sort 	= 'user_last_name';

	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$admin_user = $mydata['adminUser'];
		$admin_council_select = $mydata['adminCouncilSelect'];


	function getTranscripts($uID)
	{
		$data = array();
		global $con, $transcripts_fields_array;
		$sql = "
		SELECT *
		FROM `transcripts`
		WHERE 1=1
		AND `transcript_user_ID` LIKE '" . $uID . "'
		AND `transcript_year` <> '9999'
		";
		$results = mysqli_query($con, $sql);
		// $cnt = mysqli_num_rows($results) ?? 0;
		while ($row = mysqli_fetch_assoc($results)) {
			foreach ($transcripts_fields_array as $key => $value) {
				$$value = $row[$value];
			}
			$data[$transcript_course_ID] = $transcript_council_ID . '|' . $transcript_year;
		}
		return $data;
	}
	function getCourseInfo($id)
	{
		$data = '';
		global $con;
		$sql = "
		SELECT *
		FROM `courses`
		WHERE `course_ID` = '" . $id . "'
		";
		$results = mysqli_query($con,$sql);
		$cnt = mysqli_num_rows($results) ?? 0;
		if( $cnt > 0 )
		{
			while( $row = mysqli_fetch_assoc($results) )
			{
				$course_type = $row['course_type'];
				$course_number = $row['course_number'];

				$data = $course_type . ' ' . $course_number;
			}
		}
		return $data;
	}

	$users_report_fields_array = array(
		'user_ID' => 'DB ID',
		'user_bsa_ID' => 'BSA ID',
		// 'user_council_ID' => 'Council ID',
		'full_name' => 'Name',
		'user_basic' => 'Basic',
		'user_arrowhead' => 'Arrowhead',
		'user_comm_key' => 'Comm Key',
		'user_distinguished' => 'Distinguished',
		'user_excellence' => 'Excellence',
		'user_bcs' => 'BCS',
		'user_mcs' => 'MCS',
		'user_dcs' => 'DCS',
	);

	//! composer
	include 	 $composer_autoload;

	//! phpOffice
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	// use PhpOffice\PhpSpreadsheet\Reader\IReader;
	// use PhpOffice\PhpSpreadsheet\Writer\IWriter;
	// use PhpOffice\PhpSpreadsheet\IOFactory;

	$council_course_array = array();
	$sql = "
		SELECT *
		FROM `courses`
		WHERE 1=1
		AND
		( `course_council_ID` = '999'
		OR `course_council_ID` = '" . $admin_council_select . "'
		)
		AND `course_type` <> 'ACS'
		ORDER BY `course_code`
		";
	// echo nl2br($sql) . '<br />';
	$results = mysqli_query($con, $sql);
	$cnt = mysqli_num_rows($results) ?? 0;
	while ($row = mysqli_fetch_assoc($results)) {
		foreach ($courses_fields_array as $key => $value) {
			$$value = $row[$value];
		}
		$council_course_array[$course_ID] = $course_type . '\n' . $course_code;
	}

	$header_array = array();
	$transcript_array = array();

	foreach ($users_report_fields_array as $key => $value) {
		$header_array[] = $value;
	}
	foreach ($council_course_array as $key => $value) {
		$header_array[] = $value;
	}
	$header_array[] = 'OTHER CLASSES';

	// $transcript_array[] = $header_array;

	$sql = "
		SELECT *
		FROM `users`
		WHERE 1=1
		AND `user_council_ID` = '" . $admin_council_select . "'
		AND LOWER(`user_active`) LIKE 'yes'
		ORDER BY `user_last_name`, `user_first_name`
		";
	// echo nl2br($sql) . '<br />';
	$results = mysqli_query($con, $sql);
	$cnt = mysqli_num_rows($results) ?? 0;

	if ($cnt > 0) {
		while ($row = mysqli_fetch_assoc($results)) {
			$row_array = array();

			foreach ($users_fields_array as $key => $value) {
				$$value = $row[$value];
			}
			$full_name = userFullnameListFromID($user_ID);

			foreach ($users_report_fields_array as $key => $value) {
				$row_array[] = $$key;
			}

			$my_transcript_array = getTranscripts($user_ID);

			foreach ($council_course_array as $key => $value) {
				if (key_exists($key, $my_transcript_array)) {
					$my_transcript_vals_array = explode('|', $my_transcript_array[$key]);
						$my_transcript_council = $my_transcript_vals_array[0];
						$my_transcript_date = $my_transcript_vals_array[1];

					//! NOW PUT THIS IN THE NEW ROW ARRAY
					$row_array[] = $my_transcript_date;
					//! REMOVE THIS FROM THE TRANSCRIPT ARRAY
					unset($my_transcript_array[$key]);
				} else {
					$row_array[] = '';
				}
			}

			//! ANYTHING LEFTOVER SHOULD BE EXPLODED AND PUT INTO A SINGLE CELL
			$my_transcript_remainder = '';
			if( count($my_transcript_array) > 0 ) {
				foreach( $my_transcript_array AS $key => $value )
				{
					$my_transcript_vals_array = explode('|', $value);
						$my_transcript_council = $my_transcript_vals_array[0];
						$my_transcript_date = $my_transcript_vals_array[1];

					$my_transcript_remainder .= getCourseInfo($key) . ' - ' . $my_transcript_date . ' [' . getCouncilFromID($my_transcript_council) . ']; ';
				}
			}
			$row_array[] = $my_transcript_remainder;

			$transcript_array[] = $row_array;
		}
	}

	$full_path = '/var/www/html/admin';
	$full_path_output_dir = $full_path . '/' . 'reports/';
	$new_title = $admin_council_select . '_' . date('Y-m-d-gis') . '_report_transcripts';


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
	$activeWorksheet->setCellValue('A1', 'Transcripts Report: ' . date('M d, Y - g:i a'));
	$activeWorksheet->fromArray($header_array, null, 'A2');

	$count = 3;
	foreach( $transcript_array as $rowNum => $rowData ) {
		$activeWorksheet->fromArray($rowData, null, 'A' . $count);
		$count++;
	}


	$writer = new Xlsx($spreadsheet);
	$writer->save($full_path_output_dir . $new_title . '.xlsx');


	// echo $writer;

echo 'success';
