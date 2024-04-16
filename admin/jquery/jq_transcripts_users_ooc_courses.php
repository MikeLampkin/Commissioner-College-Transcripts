<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	include 	 $config_form_elements;
	include 	 $config_arrays;
	include 	 $config_functions_custom;

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
