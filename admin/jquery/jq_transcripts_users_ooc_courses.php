<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/functions.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/query.php';
	// include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/form_elements.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/arrays.php';

	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/functions_custom.php';

	$data_results = '';
	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$admin_user = $mydata['adminUser'];
		$admin_council_ID = $mydata['adminCouncilID'];
		$transcript_council = $mydata['transcriptCouncil'];

		$council = $mydata['councilSelect'];

	$output_array = array();
	$sql = "
	SELECT *
	FROM `courses`
	WHERE 1=1
	AND `course_council_ID` = '" . $transcript_council . "'
	AND `course_active` = 'yes'
	";
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;

	if( $cnt > 0 )
	{
		$data_results .= '<option >Please select a course</option>';

		while ($row = mysqli_fetch_assoc($results))
		{
			foreach( $courses_fields_array as $key => $value )
			{
				$$value = $row[$value];
			}
			$data_results .= '<option value="' . $course_ID . '">' . $course_name . '</option>';
		}
	}
	else
	{
		$data_results .= 'No courses available.';
	}

	echo $data_results
	?>
