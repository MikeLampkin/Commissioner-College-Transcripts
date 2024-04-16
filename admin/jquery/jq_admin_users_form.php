<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/functions.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/query.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/form_elements.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/arrays.php';

	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/functions_custom.php';

	$db_table 		= 'admin_users';
	$data_results 	= '';
	$var_ID 		= 'admin_ID';
	$var_active 	= 'admin_active';

	$fields_array = $db_table . '_fields_array';

	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$admin_user = $mydata['adminUser'];
		$council = $mydata['adminCouncilSelect'];
		$this_id = $mydata['thisID'];

		$disabled = '';

	foreach( $$fields_array as $key => $value )
	{
		$$value = '';
	}

	//! CHECK ADMIN LEVEL ACCESS  ==========================
	$my_admin_level = 100;
	$my_admin_level = getAdminLevel($admin_user);
	$my_admin_council_ID = getAdminCouncilID($admin_user);
	//! CHECK ADMIN LEVEL ACCESS  ==========================
	// echo 'You are admin for ' . $my_admin_council_ID . '<br> ';
	// echo 'Your admin level ' . $my_admin_level . '<br> ';
	// echo 'You are working with ' . $council . '<br> ';

	$council_ID_phrase = $council !== '9999' ? getCouncilFromID($council) : 'ALL Councils';
	echo '<h5> Editing for ' . $council_ID_phrase . '</h5>';

	$sql = "
		SELECT  *
		FROM `admin_users`
		WHERE `admin_ID` = '" . $this_id . "'
	";
	// echo nl2br($sql) . '<br />';
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;

	$msg = '<i class="far fa-plus-square"></i> Add Item';
	$bg_clr = "alert-info";
	$form_action = 'insert';

	if ($this_id > 0) {
		while ($row = mysqli_fetch_assoc($results)) {
			foreach( $$fields_array as $key => $value ) {
				$$value = $row[$value];
			}
		}
		$msg = '<i class="fas fa-edit"></i> Edit Item';
		$bg_clr = "alert-warning";
		$form_action = 'update';
	}

?>

<form class="nice-form mx-auto " id="data_entry" method="POST" enctype="multipart/form-data" >

	<input type="hidden" class="form-control" name="process" id="process" value="process" />
	<input type="hidden" class="form-control" name="id_field" id="id_field" value="<?php echo $var_ID; ?>" />
	<input type="hidden" class="form-control" name="db_table" id="db_table" value="<?php echo $db_table; ?>" />
	<input type="hidden" class="form-control" name="action" id="action" value="<?php echo $form_action; ?>" />
	<input type="hidden" class="form-control" name="admin_ID" id="admin_ID" value="<?php echo $this_id; ?>">

	<input type="hidden" class="form-control" name="admin_first_name" id="admin_first_name" />
	<input type="hidden" class="form-control" name="admin_last_name" id="admin_last_name" />
	<input type="hidden" class="form-control" name="admin_email" id="admin_email" />

	<div class="row mx-0">

		<div class="col-md-6">
			<?php
				// HERE WE NEED TO EXCLUDE ALL EXISTING admin_users IDs EXCEPT this_id
				$admin_users_councils_sql = $council !== '9999' ? "AND `admin_council_ID` = '" . $council . "'" : '';
				$data_array = array();

				$exclude_array = array();
				$sql = "
				SELECT `admin_user_ID`
				FROM `admin_users`
				WHERE 1=1
				AND `admin_ID` <> '" . $this_id . "'
				" . $admin_users_councils_sql . "
				";
				// echo $sql . '<br>';
				$results = mysqli_query($con, $sql);
				$cnt = 0;$cnt = mysqli_num_rows($results);
				if ( $cnt > 0 )
				{
					while( $row = mysqli_fetch_assoc($results) )
					{
						$exclude_array[] = $row['admin_user_ID'];
					}
				}
				$exclude_list = "'" . implode("','",$exclude_array) . "'";

				$users_councils_sql =  $my_admin_level !== '999' && $my_admin_council_ID !== '9999' ? "AND `user_council_ID` = '" . $council . "'" : '';
				$sql = "
				SELECT *
				FROM `users`
				WHERE 1=1
				AND `user_ID` NOT IN (" . $exclude_list . ")
				AND LOWER(`user_active`) = 'yes'
				AND LOWER(`user_deceased`) <> 'yes'
				AND `user_council_ID` <> ''
				" . $users_councils_sql . "
				ORDER BY `user_last_name`, `user_first_name`, `user_email`
				";
				// echo $sql . '<br>';
				$results = mysqli_query($con, $sql);
				$cnt = 0;$cnt = mysqli_num_rows($results);
				if ( $cnt > 0 )
				{
					while( $row = mysqli_fetch_assoc($results) )
					{
						foreach ($users_fields_array as $key => $value)
						{
							$$value = $row[$value];
						}
						$council_name = strlen($user_council_ID) > 0 ? str_replace(' Council','',  getCouncilFromID($user_council_ID)) : 'unk';
						$data_array[$user_ID] = userFullnameListFromID($user_ID) . ' [' . $council_name . ']';
					}
				}

				$field_var 		= 'admin_user_ID';
				$field_name 	= 'Users';
				$field_type 	= 'select';
				$field_size 	= '64';
				$required 		= 'required';
				$placeholder 	= '';
				$tabindex		= '';
				$disabled		= ''; // Optional
				$addl_var		= $data_array; // Optional
				$tooltip		= ''; // Optional
				$footie			= ''; // Optional
				$typeahead		= ''; // Optional
				$form_id 		= 'data_entry';
				$javascript 	= '';
				$min 		 	= '';
				$max 		 	= '';
				$txt_rows	 	= '';
				formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript,$min,$max,$txt_rows);
			?>
		</div>

		<div class="col-md-6">
			<?php

			if( $my_admin_level == '999' && $my_admin_council_ID == '9999' )
			{
				$data_array = array('9999'=>'** ALL COUNCILS ACCESS ***');
				$sql = "
				SELECT *
				FROM `councils`
				WHERE `council_active` = 'yes'
				ORDER BY `council_name`
				";
				$results = mysqli_query($con_master,$sql);
				while ($row = mysqli_fetch_assoc($results))
				{
					$council_ID = $row['council_ID'];
					$council_name = $row['council_name'];
					$data_array[$council_ID] = $council_name;
				}
			}
			else
			{
				$data_array = array();
				$sql = "
				SELECT *
				FROM `councils`
				WHERE `council_active` = 'yes'
				AND `council_ID` = '" . $council . "'
				ORDER BY `council_name`
				";
				$results = mysqli_query($con_master,$sql);
				while ($row = mysqli_fetch_assoc($results))
				{
					$council_ID = $row['council_ID'];
					$council_name = $row['council_name'];
					$data_array[$council_ID] = $council_name;
				}
			}

				$field_var 		= 'admin_council_ID';
				$field_name 	= 'Council';
				$field_type 	= 'select';
				$field_size 	= '12';
				$required 		= 'required';
				$placeholder 	= '';
				$tabindex		= '';
				$disabled		= ''; // Optional
				$addl_var		= $data_array; // Optional
				$tooltip		= ''; // Optional
				$footie			= ''; // Optional
				$typeahead		= ''; // Optional
				$form_id 		= 'data_entry';
				$javascript 	= '';
				$min 		 	= '';
				$max 		 	= '';
				$txt_rows	 	= '';
				formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript,$min,$max,$txt_rows);
			?>
		</div>

		<div class="col-md-3 md-3">
			<?php
				$admin_level_array = array();
				$levels_sql = "
				SELECT *
				FROM `admin_levels`
				ORDER BY `level_code`
				";
				$levels_results = mysqli_query($con,$levels_sql);
				$levels_cnt = mysqli_num_rows($levels_results) ?? 0;
				if( $levels_cnt > 0 )
				{
					while( $levels_row = mysqli_fetch_assoc($levels_results) )
					{
						foreach($admin_levels_fields_array as $field_value)
						{
							$$field_value = $levels_row[$field_value];
						}
						$admin_level_array[$level_code] = $level_desc;
					}
				}
				$field_var 		= 'admin_level';
				$field_name 	= 'Level';
				$field_type 	= 'select';
				$field_size 	= '12';
				$required 		= 'required';
				$placeholder 	= '';
				$tabindex		= '';
				$disabled		= ''; // Optional
				$addl_var		= $admin_level_array; // Optional
				$tooltip		= ''; // Optional
				$footie			= ''; // Optional
				$typeahead		= ''; // Optional
				$form_id 		= 'data_entry';
				$javascript 	= '';
				$min 		 	= '';
				$max 		 	= '';
				$txt_rows	 	= '';
				formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript,$min,$max,$txt_rows);
			?>
		</div>

		<div class="col-md-3 md-3">
			<?php
				$admin_active = $this_id > 0 ? $admin_active : 'yes';

				$field_var 		= 'admin_active';
				$field_name 	= 'Active';
				$field_type 	= 'select';
				$field_size 	= '12';
				$required 		= 'required';
				$placeholder 	= '';
				$tabindex		= '';
				$disabled		= ''; // Optional
				$addl_var		= $active_array; // Optional
				$tooltip		= ''; // Optional
				$footie			= ''; // Optional
				$typeahead		= ''; // Optional
				$form_id 		= 'data_entry';
				$javascript 	= '';
				$min 		 	= '';
				$max 		 	= '';
				$txt_rows	 	= '';
				formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript,$min,$max,$txt_rows);
			?>
		</div>


	</div>

</form>
