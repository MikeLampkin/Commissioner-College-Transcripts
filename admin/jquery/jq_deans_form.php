<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	include 	 $config_form_elements;
	include 	 $config_arrays;
	include 	 $config_functions_custom;

	$db_table 		= 'deans';
	$data_results 	= '';
	$var_ID 		= 'dean_ID';
	$var_active 	= 'dean_active';

	$fields_array = $db_table . '_fields_array';

	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$admin_user = $mydata['adminUser'];
		$admin_council_ID = $mydata['adminCouncilID'];
		$this_id = $mydata['thisID'];
		// $council = $mydata['councilSelect'];

		$disabled = '';

	foreach( $$fields_array as $key => $value )
	{
		$$value = '';
	}

	//! CHECK ADMIN LEVEL ACCESS  ==========================
	$my_admin_level = 100;
	$my_admin_level = getAdminLevel($admin_user);
	// $my_admin_council_ID = getAdminCouncilID($admin_user);
	//! CHECK ADMIN LEVEL ACCESS  ==========================

	$sql = "
		SELECT  *
		FROM `deans`
		WHERE 1=1
		AND `dean_ID` = '" . $this_id . "'
	";
	// echo nl2br($sql) . '<br />';
	$results = mysqli_query($con,$sql);
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
		$msg = '<i class="fas fa-edit"></i> Edit Deans';
		$bg_clr = "alert-warning";
		$form_action = 'update';
	}
?>

<form id="data_entry" class="col-md-6 mx-auto row" method="POST" enctype="multipart/form-data" >
	<input type="hidden" class="form-control" name="process" id="process" value="process" />
	<input type="hidden" class="form-control" name="id_field" id="id_field" value="<?php echo $var_ID; ?>" />
	<input type="hidden" class="form-control" name="db_table" id="db_table" value="<?php echo $db_table; ?>" />
	<input type="hidden" class="form-control" name="action" id="action" value="<?php echo $form_action; ?>" />
	<input type="hidden" class="form-control" name="dean_ID" id="dean_ID" value="<?php echo $this_id; ?>" />

	<div class="mb-1 col-md-12">
		<?php
// HERE WE NEED TO EXCLUDE ALL EXISTING admin_users IDs EXCEPT this_id
				$admin_users_councils_sql = $council !== '9999' ? "AND `dean_council_ID` = '" . $council . "'" : '';
				$data_array = array();

				$exclude_array = array();
				$sql = "
				SELECT `dean_bcs`
				FROM `deans`
				WHERE 1=1
				AND `dean_bcs` <> '" . $this_id . "'
				" . $admin_users_councils_sql . "
				";
				echo $sql . '<br>';
				// $results = mysqli_query($con, $sql);
				// $cnt = 0;$cnt = mysqli_num_rows($results);
				// if ( $cnt > 0 )
				// {
				// 	while( $row = mysqli_fetch_assoc($results) )
				// 	{
				// 		$exclude_array[] = $row['dean_bcs'];
				// 	}
				// }
				// $exclude_list = "'" . implode("','",$exclude_array) . "'";

				// $users_councils_sql =  $my_admin_level !== '999' && $my_admin_council_ID !== '9999' ? "AND `user_council_ID` = '" . $council . "'" : '';
				// $sql = "
				// SELECT *
				// FROM `users`
				// WHERE 1=1
				// AND `user_ID` NOT IN (" . $exclude_list . ")
				// AND LOWER(`user_active`) = 'yes'
				// AND LOWER(`user_deceased`) <> 'yes'
				// AND `user_council_ID` <> ''
				// " . $users_councils_sql . "
				// ORDER BY `user_last_name`, `user_first_name`, `user_email`
				// ";
				// // echo $sql . '<br>';
				// $results = mysqli_query($con, $sql);
				// $cnt = 0;$cnt = mysqli_num_rows($results);
				// if ( $cnt > 0 )
				// {
				// 	while( $row = mysqli_fetch_assoc($results) )
				// 	{
				// 		foreach ($users_fields_array as $key => $value)
				// 		{
				// 			$$value = $row[$value];
				// 		}
				// 		$council_name = strlen($user_council_ID) > 0 ? str_replace(' Council','',  getCouncilFromID($user_council_ID)) : 'unk';
				// 		$data_array[$user_ID] = userFullnameListFromID($user_ID) . ' [' . $council_name . ']';
				// 	}
				// }

				// $field_var 		= 'dean_bcs';
				// $field_name 	= 'Users';
				// $field_type 	= 'select';
				// $field_size 	= '64';
				// $required 		= 'required';
				// $placeholder 	= '';
				// $tabindex		= '';
				// $disabled		= ''; // Optional
				// $addl_var		= $data_array; // Optional
				// $tooltip		= ''; // Optional
				// $footie			= ''; // Optional
				// $typeahead		= ''; // Optional
				// $form_id 		= 'data_entry';
				// $javascript 	= '';
				// $min 		 	= '';
				// $max 		 	= '';
				// $txt_rows	 	= '';
				// formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript,$min,$max,$txt_rows);
		?>
	</div>



	<div class="mt-3 col-md-12 text-center mx-auto">
		<button id="process-form" class="btn btn-success col-6" type="submit" role="button" data-bs-dismiss="modal"> <i class="fas fa-save"></i> S A V E </button>
		<button type="button" class="btn btn-secondary col-3" type="cancel" data-bs-dismiss="modal"><i class="fa-regular fa-rectangle-xmark"></i> Close</button>
	</div>

</form>
