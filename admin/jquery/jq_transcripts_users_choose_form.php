<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	include 	 $config_form_elements;
	include 	 $config_arrays;
	include 	 $config_functions_custom;

	$db_table 		= 'users';
	$data_results 	= '';
	$var_ID 		= 'user_ID';
	$var_active 	= 'user_active';

	$fields_array = $db_table . '_fields_array';

	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$admin_user = $mydata['adminUser'];
		$admin_council_ID = $mydata['adminCouncilID'];
		$this_id = $mydata['transcriptsUser'];

		$council = $mydata['councilSelect'];

		$status = $mydata['statusSelect'];
		$deceased = $mydata['deceasedSelect'];
		$active = $mydata['activeSelect'];
		$council = $mydata['councilSelect'];

		$disabled = '';

	foreach( $$fields_array as $key => $value )
	{
		$$value = '';
	}

	$user_sql = $this_id > 0 ? "AND `user_ID` = '" . $this_id . "'" : '';
	$active_sql = $pg_active !== 'no' ? "AND LOWER(`" . $var_active . "`) = 'yes'" : "AND LOWER(`" . $var_active . "`) = 'no'";
	$status_sql = $status !== 'all' && strlen($status) > 0 ? "AND LOWER(`user_status`) LIKE '" . $status . "'" : "";
	$deceased_sql = $deceased !== 'all' && strlen($deceased) > 0 ? "AND LOWER(`user_deceased`) LIKE '" . $deceased . "'" : "";

	$council_sql = '';
	$council_sql = $admin_council_ID == '9999' && strlen($council) > 0 ? "AND LOWER(`user_council_ID`) LIKE '" . $council . "'" : "AND LOWER(`user_council_ID`) = '" . $admin_council_ID . "'";
	$council_sql = $council == 'all' ? '' : $council_sql;

	//! CHECK ADMIN LEVEL ACCESS  ==========================
	$my_admin_level = 100;
	$my_admin_level = getAdminLevel($admin_user);
	$my_admin_council_ID = getAdminCouncilID($admin_user);
	//! CHECK ADMIN LEVEL ACCESS  ==========================

	//# Build queries ==========================
	$addl_sql = $status_sql .
	' ' . $deceased_sql .
	' ' . $council_sql;

	$sql = "
		SELECT  *
		FROM `users`
		WHERE 1=1
		" . $addl_sql . "
		" . $active_sql . "
		ORDER BY `user_last_name`, `user_first_name`
	";
	// echo nl2br($sql) . '<br />';
	$results = mysqli_query($con,$sql);
	$display_results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;

	$msg = '<i class="far fa-plus-square"></i> Add User';
	$bg_clr = "alert-info";
	$form_action = 'insert';

	if ($this_id > 0) {
		while ($row = mysqli_fetch_assoc($results)) {
			foreach( $$fields_array as $key => $value ) {
				$$value = $row[$value];
			}
		}
		$msg = '<i class="fas fa-edit"></i> Edit User';
		$bg_clr = "alert-warning";
		$form_action = 'update';
	}
?>

<form class="col-md-9 col-sm-12 mx-auto alert alert-success border border-dark">

	<div class="mb-3 col-md-12">
		<label for="transcripts_user" class="form-label"> Active Participants (<?php echo $cnt; ?>) : </label>
		<select name="transcripts_user" id="transcripts_user" class="form-select">
			<option value="0">-- Participant List --</option>
			<?php
		while( $row = mysqli_fetch_assoc($display_results) )
		{
			foreach($users_fields_array as $field_key => $field_value)
			{
				$$field_value = $row[$field_value];
			}

			$system_ID = '';
			// $system_ID = ' (' . $user_ID . ') ';
			$user_bsa_ID_display = strlen($user_bsa_ID ?? '') > 4 ? ' - [' . $user_bsa_ID . ']' : '';
			$user_email_display = strlen($user_email ?? '') > 4 ? ' - [' . $user_email . ']' : '';

			$user_name = fullNameList($user_prefix,$user_first_name,$user_nick_name,$user_middle_name,$user_last_name,$user_suffix);
			$bcs_degree = strlen($user_bcs ?? '') > 1 ? '[B]' : '';
			$mcs_degree = strlen($user_mcs ?? '') > 1 ? '[M]' : '';
			$dcs_degree = strlen($user_dcs ?? '') > 1 ? '[D]' : '';
			$user_degrees = $bcs_degree . $mcs_degree . $dcs_degree ;

			$selectme = $user_ID == $this_id  ? 'selected' : '';

			echo '<option value="' . $user_ID . '" ' . $selectme . '>' . $user_name . '' . $system_ID . '' . $user_email_display . '' . $user_bsa_ID_display . '
			' . $user_degrees . ' </option>
			';
		}
			?>
		</select>
	</div>

	<!-- <div class="mb-3 col-md-12 mx-auto text-center">
		<button type="submit" class="btn btn-primary col-md-6" id="display-user"><i class="fas fa-arrow-circle-up"></i> SELECT </button>
		<a href="?clear" type="submit" class="btn btn-warning col-md-3"><i class="fa-solid fa-recycle"></i> reset </a>
	</div> -->

</form>
