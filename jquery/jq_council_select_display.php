<?php  // ** Lampkin 2023 ** // ?>
<?php // ** v 5.00 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/query.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/form_elements.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/arrays.php';

	include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/functions_custom.php";

	$var_ID = 'council_ID';
	$var_active = 'council_active';
	$db_table = 'councils';

	$fields_array = $db_table . '_fields_array';
	$return_data = '';
	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$my_council = $mydata['myCouncil'];
		$return_data = getCouncilFromBsa_ID(trim($my_council));
	echo $return_data;
?>
