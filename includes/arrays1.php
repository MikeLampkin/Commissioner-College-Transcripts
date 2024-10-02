<?php
	$waiting_list_fields_array = query('getColumns', 'waiting_list', $db_name, '', '', '');


	$users_fields_array = query('getColumns', 'users', $db_name, '', '', '');
	$events_fields_array = query('getColumns', 'events', $db_name, '', '', '');
	$courses_fields_array = query('getColumns', 'courses', $db_name, '', '', '');
	$transcripts_fields_array = query('getColumns', 'transcripts', $db_name, '', '', '');

	$councils_fields_array = query('getColumns', 'councils', 'master', '', '', '');
?>

<?php

$pepper = 'trustworthyloyalhelpfulfriendlycourteouskindobedientthriftybravecleanreverend';
$pepper_array = array('trustworthy','loyal','helpful','friendly','courteous','kind','obedient','cheerful','thrifty','brave','clean','reverent');

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

$admin_users_display_array = array(
	'full_name'		=> 'Name',
	'admin_email' 	=> 'Email',
	'admin_userID' 	=> 'User ID',
);

$admin_levels_fields_array = query('getColumns','admin_levels', $db_name, '', '', '');
$admin_levels_display_array = array('level_ID'=>'ID');
$admin_sql = "
SELECT *
FROM `admin_levels`
ORDER BY `level_code`
";
$admin_results = mysqli_query($con,$admin_sql);
$admin_cnt=0;$admin_cnt = @mysqli_num_rows($admin_results);
while( $row = mysqli_fetch_assoc($admin_results) )
{
	$level_name = $row['level_name'];
	$level_code = $row['level_code'];
	$level_icon = $row['level_icon'];
	$admin_level_array[$level_name] = $level_code . '|' . $level_icon;
	$admin_level_form_array[$level_code] = ucfirst($level_name);
}

$colors_fields_array = query('getColumns','colors', $db_name, '', '', '');


?>
