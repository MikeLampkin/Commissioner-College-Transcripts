<?php  // ** Lampkin 2024 ** //
?>

<?php
include 	 $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
include 	 $config_functions;
include 	 $config_query;
// include 	 $config_form_elements;
include 	 $config_arrays;
include 	 $config_functions_custom;

$db_table 		= 'degrees';
$return_data= '';
$var_ID 		= 'course_ID';
$var_active 	= 'course_active';

$display_array = array(
	'course_type'	=> 'Type',
	'course_no'		=> 'No.',
	'course_name'	=> 'Name',
	'council_name' 	=> 'Council',
);

$data = file_get_contents("php://input");
$mydata = json_decode($data, true);
	$my_council = $mydata['myCouncil'];
	$select_degree = $mydata['selectDegree'];

	$award_name = $degree_award_array[$select_degree];

	$sql = "
	SELECT *
	FROM `users`
	WHERE 1=1
	AND LOWER(`user_active`) LIKE 'yes'
	AND `" . $select_degree . "` <> ''
	AND `user_council_ID` = '" . $my_council . "'
	ORDER BY `user_last_name`, `user_first_name`
	";
// $return_data .= nl2br($sql);
	$results = mysqli_query($con, $sql);
	$cnt = mysqli_num_rows($results);


	$return_data .= '
	<div class="mt-3">
		<h5>' . $award_name . '</h5>
	</div>

	<div class="row m-0 p-0">';

if( $cnt > 0 ) {

		$return_data .= '
		<div class="col-md-3 m-0 p-3">';
			$thisUser_cnt = 0;
			$break = ceil($cnt / 4);
			$col_break = $break;

			while ($row = mysqli_fetch_assoc($results)) {
				$thisUser_cnt++;

				foreach ($users_fields_array as $field_key => $field_value) {
					$$field_value = $row[$field_value];
				}
				// $user_name = fullNameList($user_prefix,$user_first_name,$user_nick_name,$user_middle_name,$user_last_name,$user_suffix);
				$user_name = fullNameList($user_prefix, $user_first_name, $user_nick_name, $user_middle_name, $user_last_name, $user_suffix);
				$$select_degree = (strlen($$select_degree) > 4) ? date('Y', strtotime($$select_degree)) : $$select_degree;

				$return_data .= $user_name . ' <span class="float-end"><small> [' . $$select_degree . '] </small></span>
				<br />
				';

				if ($thisUser_cnt !== $cnt) {
					if ($thisUser_cnt - $col_break == 0) {
						$return_data .= '</div>';
						$return_data .= '<div class="col-md-3 m-0 p-3">';
						$col_break = $col_break + $break;
					}
				}
			}

			$return_data .= '</div>';
} else {
	$return_data = '<div class="m-3"><strong><em>No data</em></strong></div>';
}
$return_data .= '</div>';

echo $return_data;
?>
