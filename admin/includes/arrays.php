<?php
	$table_array = array(
		'admin_levels',
		'admin_msg',
		'admin_users',
		'attempts',
		'colors',
		'courses',
		'deans',
		'districts',
		'download_links',
		'events',
		'transcripts',
		'transcripts_safety',
		'users',
		'waiting_list',
	);
	foreach( $table_array AS $key => $value )
	{
		$array_name = $value . '_fields_array';
		$$array_name = query('getColumns', $value, $db_name, '', '', '');
	}

	$awards_fields_array = query('getColumns', 'awards', 'master', '', '', '');
	$councils_fields_array = query('getColumns', 'councils', 'master', '', '', '');
	$positions_fields_array = query('getColumns', 'positions', 'master', '', '', '');

	$users_awards_fields_array = ['user_basic','user_arrowhead','user_comm_key','user_distinguished','user_excellence','user_bcs','user_mcs','user_dcs'];

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
