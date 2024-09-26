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
		$admin_council_select = $mydata['adminCouncilSelect'];
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
	//! CHECK ADMIN LEVEL ACCESS  ==========================

	$sql = "
		SELECT  *
		FROM `users`
		WHERE 1=1
		AND `user_ID` = '" . $this_id . "'
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
		$msg = '<i class="fas fa-edit"></i> Edit User';
		$bg_clr = "alert-warning";
		$form_action = 'update';
	}
?>

<form id="data_entry" class="" method="POST" enctype="multipart/form-data" >
	<input type="hidden" class="form-control" name="process" id="process" value="process" />
	<input type="hidden" class="form-control" name="id_field" id="id_field" value="<?php echo $var_ID; ?>" />
	<input type="hidden" class="form-control" name="db_table" id="db_table" value="<?php echo $db_table; ?>" />
	<input type="hidden" class="form-control" name="action" id="action" value="<?php echo $form_action; ?>" />
	<input type="hidden" class="form-control" name="user_ID" id="user_ID" value="<?php echo $this_id; ?>" />

	<?php //? start card - basic ?>
	<div class="card">
		<div id="header_basic" class="card-header btn btn-block text-start h5 px-3 py-2" type="button" aria-expanded="true" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
			<span class="required">Basic Info</span>
			<span class="xs required">(Required)</span> <i class="fas fa-caret-up float-end" id="caret_collapse1"></i>
		</div>

		<div id="collapse1" class="collapse show" aria-labelledby="header_basic" data-bs-parent="#XXXdata_entry">
			<div class="card-body row">

				<div class="mb-1 col-md-5">
					<?php
						$field_var 		= 'user_first_name';
						$field_name 	= 'First Name';
						$field_type 	= 'text';
						$field_size 	= '64';
						$required 		= 'required';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= ''; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

				<div class="mb-1 col-md-2">
					<?php
						$field_var 		= 'user_middle_name';
						$field_name 	= 'Middle Name';
						$field_type 	= 'text';
						$field_size 	= '64';
						$required 		= '';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= ''; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

				<div class="mb-1 col-md-5">
					<?php
						$field_var 		= 'user_last_name';
						$field_name 	= 'Last Name';
						$field_type 	= 'text';
						$field_size 	= '64';
						$required 		= 'required';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= ''; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

				<div class="mb-1 col-md-3">
					<?php
						$field_var 		= 'user_nick_name';
						$field_name 	= 'Nickname';
						$field_type 	= 'text';
						$field_size 	= '64';
						$required 		= '';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= ''; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>


				<div class="mb-1 col-md-2">
					<?php
						$field_var 		= 'user_prefix';
						$field_name 	= 'Prefix';
						$field_type 	= 'text';
						$field_size 	= '12';
						$required 		= '';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= ''; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

				<div class="mb-1 col-md-2">
					<?php
						$field_var 		= 'user_suffix';
						$field_name 	= 'Suffix';
						$field_type 	= 'text';
						$field_size 	= '12';
						$required 		= '';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= ''; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

				<div class="mb-1 col-md-2">
					<?php
						$field_var 		= 'user_maiden_name';
						$field_name 	= 'Maiden';
						$field_type 	= 'text';
						$field_size 	= '12';
						$required 		= '';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= ''; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

				<div class="mb-1 col-md-3">
					<?php
						$field_var 		= 'user_bsa_ID';
						$field_name 	= 'BSA ID';
						$field_type 	= 'text';
						$field_size 	= '12';
						$required 		= '';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= ''; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

				<div class="mb-1 col-md-12">
					<?php
						$field_var 		= 'user_notes_public';
						$field_name 	= 'Notes for Other Admin';
						$field_type 	= 'textarea';
						$field_size 	= '1000';
						$required 		= '';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= ''; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

<div class="mb-1 col-md-3">
	<?php
		$field_var 		= 'user_bcs';
		$field_name 	= 'BCS';
		$field_type 	= 'text';
		$field_size 	= '64';
		$required 		= '';
		$placeholder 	= '';
		$tabindex		= '';
		$disabled		= ''; // Optional
		$addl_var		= ''; // Optional
		$tooltip		= ''; // Optional
		$footie			= ''; // Optional
		$typeahead		= ''; // Optional
		$form_id 		= 'data_entry';
		$javascript 	= '';
		formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
	?>
</div>

<div class="mb-1 col-md-3">
	<?php
		$field_var 		= 'user_mcs';
		$field_name 	= 'MCS';
		$field_type 	= 'text';
		$field_size 	= '64';
		$required 		= '';
		$placeholder 	= '';
		$tabindex		= '';
		$disabled		= ''; // Optional
		$addl_var		= ''; // Optional
		$tooltip		= ''; // Optional
		$footie			= ''; // Optional
		$typeahead		= ''; // Optional
		$form_id 		= 'data_entry';
		$javascript 	= '';
		formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
	?>
</div>
<div class="mb-1 col-md-3">
	<?php
		$field_var 		= 'user_dcs';
		$field_name 	= 'DCS';
		$field_type 	= 'text';
		$field_size 	= '64';
		$required 		= '';
		$placeholder 	= '';
		$tabindex		= '';
		$disabled		= ''; // Optional
		$addl_var		= ''; // Optional
		$tooltip		= ''; // Optional
		$footie			= ''; // Optional
		$typeahead		= ''; // Optional
		$form_id 		= 'data_entry';
		$javascript 	= '';
		formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
	?>
</div>
			</div>
		</div>
	</div>
	<?php //? end card basic ?>


	<?php //? start card 2 ?>
	<div class="card">
		<div id="header2" class="card-header btn btn-block text-start h5 px-3 py-2" type="button" aria-expanded="true" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
			<span class="required">Commissioner Info</span>
			<span class="xs required">(Required: Helps sort data.)</span> <i class="fas fa-caret-up float-end" id="caret_collapse2"></i>
		</div>

		<div id="collapse2" class="collapse show" aria-labelledby="header2" data-bs-parent="#XXXdata_entry">
			<div class="card-body row">

				<div class="mb-1 col-md-6">
				<?php
						$sql = "
						SELECT *
						FROM `councils`
						WHERE 1=1
						AND `council_active` = 'yes'
						ORDER BY `council_name`
						";
						$results = mysqli_query($con_master,$sql);
						$data_array = array();
						while( $data = mysqli_fetch_assoc($results) )
						{
							$data_array[$data['council_ID']] = $data['council_name'] . ' [' . $data['council_bsa_ID'] . ']';
						}

						$council_select_disabled = 'disabled';
						$field_var 		= 'user_council_ID';
						$field_name 	= 'Council';
						$field_type 	= 'select';
						$field_size 	= '12';
						$required 		= 'required';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= $council_select_disabled; // Optional
						$addl_var		= $data_array; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

				<div class="mb-1 col-md-3">
					<?php
						$sql = "
						SELECT *
						FROM `districts`
						WHERE 1=1
						AND `district_active` = 'yes'
						AND
						(
						 `district_council_ID` = '" . $admin_council_select . "'
						OR
						 `district_council_ID` = '999'
						 )
						ORDER BY `district_council_ID`, `district_name`
						";
						$results = mysqli_query($con,$sql);
						$data_array = array();
						while( $row = mysqli_fetch_assoc($results) )
						{
							$data_array[$row['district_ID']] = $row['district_name'];
						}

						$field_var 		= 'user_district_ID';
						$field_name 	= 'District ';
						$field_type 	= 'select';
						$field_size 	= '64';
						$required 		= '';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= $data_array; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

				<div class="mb-1 col-md-2">
				<?php
						$user_pro = $this_id < 1 || strlen($user_pro ?? '') < 1 ? 'no' : $user_pro;

						$field_var 		= 'user_pro';
						$field_name 	= 'BSA Pro';
						$field_type 	= 'radio';
						$field_size 	= '12';
						$required 		= '';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= $yesno_array; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

				<div class="mb-1 col-md-8">
					<?php
						$field_var 		= 'user_staff_years';
						$field_name 	= 'CCS Staff Years';
						$field_type 	= 'textarea';
						$field_size 	= '1000';
						$required 		= '';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= ''; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

				<div class="mb-1 col-md-2">
					<?php
						$user_positions = strlen($user_positions ?? '') < 1 ? 'UC' : $user_positions;
						$field_var 		= 'user_positions';
						$field_name 	= 'District Positions';
						$field_type 	= 'text';
						$field_size 	= '64';
						$required 		= '';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= ''; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

			</div>
		</div>
	</div>
	<?php //? end card 2 ?>

	<?php //? start card 3 ?>
	<div class="card">
		<div id="header3" class="card-header btn btn-block text-start h5 px-3 py-2" type="button" aria-expanded="true" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="true" aria-controls="collapse3"> <span class="required">Important Info</span> <span class="xs required">(Required: Helps sort data.)</span><i class="fas fa-caret-up float-end" id="caret_collapse3"></i>
		</div>

		<div id="collapse3" class="collapse show" aria-labelledby="header3" data-bs-parent="#XXXdata_entry">
			<div class="card-body row">

				<div class="mb-1 col-md-2">
				<?php
						$user_deceased = $this_id < 1 || strlen($user_deceased ?? '') < 1 ? 'no' : $user_deceased;

						$field_var 		= 'user_deceased';
						$field_name 	= 'Deceased';
						$field_type 	= 'radio';
						$field_size 	= '12';
						$required 		= 'required';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= $yesno_array; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

				<div class="mb-1 col-md-4">
					<?php
						$user_status = $this_id < 1 || strlen($user_status ?? '') < 1 ? 'active' : $user_status;

						$field_var 		= 'user_status';
						$field_name 	= 'Status';
						$field_type 	= 'select';
						$field_size 	= '12';
						$required 		= 'required';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= array('active'=>'Active - Registered ','inactive'=>'Inactive - Unregistered ',); // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

				<div class="mb-1 col-md-2">
					<?php
						$user_dob = $this_id < 1 || strlen($user_dob ?? '') < 1 ? '1980-01-01' : $user_dob;
					?>
					<?php
						$field_var 		= 'user_dob';
						$field_name 	= 'DoB';
						$field_type 	= 'date';
						$field_size 	= '12';
						$required 		= '';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= ''; // Optional
						$tooltip		= ''; // Optional
						$footie			= '
						<a class="btn btn-info btn-xs text-white mx-1" id="" onclick="insertAdultDate(\'user_dob\');"> Generic Adult Date </a>
						'; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

				<div class="mb-1 col-md-2">
					<?php
						$field_var 		= 'user_gender';
						$field_name 	= 'Gender';
						$field_type 	= 'select';
						$field_size 	= '12';
						$required 		= '';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= array('M'=>'Male','F'=>'Female','X'=>'Unk'); // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						$min 		 	= '';
						$max 		 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript,$min,$max);
					?>
				</div>

			</div>
		</div>

	</div>
	<?php //? end card 3 ?>

	<?php //? start card 4 ?>
	<div class="card">
		<div id="header4" class="card-header btn btn-block text-start h5 px-3 py-2" type="button" aria-expanded="true" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="true" aria-controls="collapse2">
			Contact Info <span class="xs">(Optional)</span><i class="fas fa-caret-up float-end" id="caret_collapse4"></i>
		</div>

		<div id="collapse4" class="collapse" aria-labelledby="header4" data-bs-parent="#XXXdata_entry">
			<div class="card-body row">

				<div class="mb-1 col-md-6">
					<?php
					$user_email = strtolower($user_email);

						$field_var 		= 'user_email';
						$field_name 	= 'Email';
						$field_type 	= 'text';
						$field_size 	= '128';
						$required 		= '';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= ''; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

				<div class="mb-1 col-md-6">
					<?php
					$user_email2 = strtolower($user_email2);

						$field_var 		= 'user_email2';
						$field_name 	= 'Email (alt)';
						$field_type 	= 'text';
						$field_size 	= '128';
						$required 		= '';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= ''; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

				<div class="mb-1 col-md-2">
					<?php
						$field_var 		= 'user_phone';
						$field_name 	= 'Phone';
						$field_type 	= 'text';
						$field_size 	= '64';
						$required 		= '';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= ''; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>

				</div>

				<div class="mb-1 col-md-2">
					<?php
						$field_var 		= 'user_phone2';
						$field_name 	= 'Phone (alt)';
						$field_type 	= 'text';
						$field_size 	= '64';
						$required 		= '';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= ''; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

			</div>
		</div>

	</div>
	<?php //? end card 4 ?>

	<?php //? start card 5 ?>
	<!-- <div class="card">
		<div id="header5" class="card-header btn btn-block text-start h5 px-3 py-2" type="button" aria-expanded="true" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="true" aria-controls="collapse5">
			Login Info <span class="xs">(Optional. Only needed if person becomes a site admin.)</span><i class="fas fa-caret-up float-end" id="caret_collapse5"></i>
		</div>

		<div id="collapse5" class="collapse" aria-labelledby="header5" data-bs-parent="#XXXdata_entry">
			<div class="card-body row">

				<div class="mb-1 col-md-6">
					<?php

					if( strlen($user_login ?? '') < 2) { $user_login = strtolower(cleanName($user_first_name)) . strtolower(cleanName($user_last_name)); }
						$field_var 		= 'user_login';
						$field_name 	= 'Login';
						$field_type 	= 'text';
						$field_size 	= '128';
						$required 		= '';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= ''; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

				<div class="mb-1 col-md-6">
					<?php
						$field_var 		= 'user_password';
						$field_name 	= 'Password <small>(*new or changing only)</small>';
						$field_type 	= 'password';
						$field_size 	= '12';
						$required 		= '';
						$placeholder 	= 'New password';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= ''; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>


			</div>
		</div>
	</div> -->
	<?php //? end card 5 ?>

	<?php //? start card 6 ?>
	<div class="card">
		<div id="header6" class="card-header btn btn-block text-start h5 px-3 py-2" type="button" aria-expanded="true" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="true" aria-controls="collapse6">
			Address Info <span class="xs">(Optional. For your records only.)</span> <i class="fas fa-caret-up float-end" id="caret_collapse6"></i>
		</div>

		<div id="collapse6" class="collapse" aria-labelledby="header6" data-bs-parent="#XXXdata_entry">
			<div class="card-body row">

				<div class="mb-1 col-md-6">
					<?php
						$field_var 		= 'user_address';
						$field_name 	= 'Address';
						$field_type 	= 'text';
						$field_size 	= '128';
						$required 		= '';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= ''; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

				<div class="mb-1 col-md-6">
					<?php
						$field_var 		= 'user_address2';
						$field_name 	= 'Address 2';
						$field_type 	= 'text';
						$field_size 	= '128';
						$required 		= '';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= ''; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

				<div class="mb-1 col-md-4">
					<?php
						$field_var 		= 'user_city';
						$field_name 	= 'City';
						$field_type 	= 'text';
						$field_size 	= '64';
						$required 		= '';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= ''; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

				<div class="mb-1 col-md-2">
					<?php
					$user_state = strlen($user_state ?? '') < 1 ? 'TX' : $user_state;
					$state_array = array(
						'AL' => 'Alabama',
						'AK' => 'Alaska',
						'AZ' => 'Arizona',
						'AR' => 'Arkansas',
						'CA' => 'California',
						'CO' => 'Colorado',
						'CT' => 'Connecticut',
						'DE' => 'Delaware',
						'DC' => 'District of Columbia',
						'FL' => 'Florida',
						'GA' => 'Georgia',
						'HI' => 'Hawaii',
						'ID' => 'Idaho',
						'IL' => 'Illinois',
						'IN' => 'Indiana',
						'IA' => 'Iowa',
						'KS' => 'Kansas',
						'KY' => 'Kentucky',
						'LA' => 'Louisiana',
						'ME' => 'Maine',
						'MD' => 'Maryland',
						'MA' => 'Massachusetts',
						'MI' => 'Michigan',
						'MN' => 'Minnesota',
						'MS' => 'Mississippi',
						'MO' => 'Missouri',
						'MT' => 'Montana',
						'NE' => 'Nebraska',
						'NV' => 'Nevada',
						'NH' => 'New Hampshire',
						'NJ' => 'New Jersey',
						'NM' => 'New Mexico',
						'NY' => 'New York',
						'NC' => 'North Carolina',
						'ND' => 'North Dakota',
						'OH' => 'Ohio',
						'OK' => 'Oklahoma',
						'OR' => 'Oregon',
						'PA' => 'Pennsylvania',
						'RI' => 'Rhode Island',
						'SC' => 'South Carolina',
						'SD' => 'South Dakota',
						'TN' => 'Tennessee',
						'TX' => 'Texas',
						'UT' => 'Utah',
						'VT' => 'Vermont',
						'VA' => 'Virginia',
						'WA' => 'Washington',
						'WV' => 'West Virginia',
						'WI' => 'Wisconsin',
						'WY' => 'Wyoming'
					);
						$field_var 		= 'user_state';
						$field_name 	= 'State';
						$field_type 	= 'select';
						$field_size 	= '2';
						$required 		= '';
						$placeholder 	= 'TX';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= $state_array; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

				<div class="mb-1 col-md-2">
					<?php
						$field_var 		= 'user_zip';
						$field_name 	= 'Zip Code';
						$field_type 	= 'text';
						$field_size 	= '12';
						$required 		= '';
						$placeholder 	= '';
						$tabindex		= '';
						$disabled		= ''; // Optional
						$addl_var		= ''; // Optional
						$tooltip		= ''; // Optional
						$footie			= ''; // Optional
						$typeahead		= ''; // Optional
						$form_id 		= 'data_entry';
						$javascript 	= '';
						formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript);
					?>
				</div>

			</div>
		</div>

	</div>
	<?php //? end card 6 ?>

	<!-- <div class="mt-3 col-md-12 text-center mx-auto">
		<button id="process-form" class="btn btn-success col-6" type="submit" role="button" data-bs-dismiss="modal"> <i class="fas fa-save"></i> S A V E </button>
		<button type="button" class="btn btn-secondary col-3" type="cancel" data-bs-dismiss="modal"><i class="fa-regular fa-rectangle-xmark"></i> Close</button>
	</div> -->

</form>

<script>
$('#user_email').keyup(function(){
	$(this).val($(this).val().toLowerCase());
});
</script>
