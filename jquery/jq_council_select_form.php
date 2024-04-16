<?php  // ** Lampkin 2023 ** // ?>
<?php // ** v 5.00 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/query.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/form_elements.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/arrays.php';

	include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/functions_custom.php";

	// $councils_fields_array = query('getColumns', 'councils', 'master', '', '', '');

	$var_ID = 'council_ID';
	$var_active = 'council_active';
	$db_table = 'councils';

	// $display_array = $db_table . '_display_array';
	$fields_array = $db_table . '_fields_array';
	$return_data = '';
	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$my_council = $mydata['myCouncil'];

$council_array = array();
$sql = "
SELECT DISTINCT `user_council_ID` AS `user_council_ID`
FROM `users`
WHERE `user_council_ID` <> ''
";
$return_data = '<div class="text-center">';
$results = mysqli_query($con,$sql);
$cnt = mysqli_num_rows($results);
if( $cnt > 0 )
{
	while( $row = mysqli_fetch_assoc($results) )
	{
		$user_council_ID = $row['user_council_ID'];
		$council_name = getCouncilFromID(trim($user_council_ID));
		$council_array[$user_council_ID] = $council_name;
	}
}
asort($council_array);
foreach( $council_array AS $key => $value )
{

	$selectme = $my_council == $key ? 'active' : '';
	$return_data .= '<button type="button" id="council_' . $key . '" class="btn btn-primary m-1 ' . $selectme . ' council-change-btn mr-2" data-info="' . $key . '" data-bs-dismiss="modal">' . $value . '</button>';

}
	$return_data .= '</div>';

	echo $return_data;
?>
