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
		// $transcript_council = $mydata['transcriptCouncil'];

		$council = $mydata['councilSelect'];

	$output_array = array();
	$sql = "
	SELECT DISTINCT(`transcript_council_ID`)
	FROM `transcripts`
	WHERE 1=1
	AND `transcript_council_ID` <> '999'
	AND `transcript_council_ID` <> '" . $council . "'
	AND `transcript_active` = 'yes'
	";
	$results = mysqli_query($con,$sql);
	while ($row = mysqli_fetch_assoc($results))
	{
		$council_ID = $row['transcript_council_ID'];
		$council_name = getCouncilFromID($council_ID) ?? 'unk';
		$output_array[$council_ID] = $council_name;
	}
	$data_results .= '<select >';
	$data_results .= '<option >Please select a council</option>';
	asort($output_array);
	foreach( $output_array AS $key => $value )
	{
		$data_results .= '<option value="' . $key . '">' . $value . ' [' . $key . ']</option>';
	}
	$data_results .= '</select >';

	echo $data_results
	?>
