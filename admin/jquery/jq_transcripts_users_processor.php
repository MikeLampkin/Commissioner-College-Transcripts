<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/functions.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/query.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/form_elements.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/arrays.php';

	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/functions_custom.php';

	$db_table 		= 'users';
	$data_results 	= '';
	$var_ID 		= 'user_ID';
	$var_active 	= 'user_active';

	$fields_array = $db_table . '_fields_array';

	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$admin_user = $mydata['adminUser'];
		// $admin_council_ID = $mydata['adminCouncilID'];
		unset($_POST['process']);

		$id_field = $_POST['id_field'];
			unset($_POST['id_field']);
		$db_table = $_POST['db_table'];
			unset($_POST['db_table']);
		$action = $_POST['action'];
			unset($_POST['action']);

			array_filter($_POST);

			$data_results .= 'starting: <br />';

	// $post_cnt=0;
	// foreach ( $_POST as $name => $value )
	// {
	// 	if ( in_array($name, $transcripts_fields_array) && !empty($name) )
	// 	{
	// 		$post_cnt++;
	// 	}
	// }
	// $post_cnt = $post_cnt-1; // Remove the ID in the count.

	// foreach( $_POST as $name => $value )
	// {
	// 	if ( in_array($name, $transcripts_fields_array) && !empty($name) )
	// 	{
	// 		$data_results .= $name;
	// 	}
	// }

	// foreach( $_POST as $name => $value )
	// {
	// 	if ( in_array($name, $users_fields_array) && !empty($name) )
	// 	{
	// 		$data_results .= $name;
	// 	}
	// }

	foreach( $_POST as $name => $value )
	{
		$data_results .= $name . ' =  ' . $value . '<br />';
	}

echo $data_results;

?>
