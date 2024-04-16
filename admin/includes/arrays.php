<?php
	$waiting_list_fields_array = query('getColumns', 'waiting_list', $db_name, '', '', '');

	$users_fields_array = query('getColumns', 'users', $db_name, '', '', '');
	$events_fields_array = query('getColumns', 'events', $db_name, '', '', '');
	$courses_fields_array = query('getColumns', 'courses', $db_name, '', '', '');
	$transcripts_fields_array = query('getColumns', 'transcripts', $db_name, '', '', '');

	$councils_fields_array = query('getColumns', 'councils', 'master', '', '', '');
	$districts_fields_array = query('getColumns', 'districts', $db_name, '', '', '');

?>

<?php
//# -------- PERMANENT -----------------------------================
$yesno_array = array(
'yes' => 'Yes',
'no' => 'No'
);

$active_array = array(
'yes' => 'Active / Visible',
'no' => 'Inactive / Hidden'
);

$month_array = array('1'=>'January','2'=>'February','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December',);

$admin_msg_fields_array = query('getColumns','admin_msg', $db_name, '', '', '');
$admin_users_fields_array = query('getColumns','admin_users', $db_name, '', '', '');

$admin_levels_fields_array = query('getColumns','admin_levels', $db_name, '', '', '');
$colors_fields_array = query('getColumns','colors', $db_name, '', '', '');
?>
