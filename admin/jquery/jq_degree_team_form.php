<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	include 	 $config_form_elements;
	include 	 $config_arrays;
	include 	 $config_functions_custom;

	$db_table 		= 'degree_team';
	$data_results 	= '';
	$var_ID 		= 'dt_ID';
	$var_active 	= 'dt_active';

	$fields_array = $db_table . '_fields_array';

	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$admin_user = $mydata['adminUser'];
		$admin_council_select = $mydata['adminCouncilSelect'];
		$this_id = $mydata['thisID'];

		$disabled = '';

	foreach( $$fields_array as $key => $value )
	{
		$$value = '';
	}

	//! CHECK ADMIN LEVEL ACCESS  ==========================
	$my_admin_level = 100;
	$my_admin_level = getAdminLevel($admin_user);
	//! CHECK ADMIN LEVEL ACCESS  ==========================

	// $council_ID_phrase = $council !== '9999' ? getCouncilFromID($council) : 'ALL Councils';
	// echo '<strong> Editing for ' . $council_ID_phrase . '</strong>';

	$sql = "
		SELECT  *
		FROM `degree_team`
		WHERE `dt_ID` = '" . $this_id . "'
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
	<input type="hidden" class="form-control" name="<?php echo $var_ID; ?>" id="<?php echo $var_ID; ?>" value="<?php echo $$var_ID; ?>">
	<input type="hidden" class="form-control" name="dt_council_ID" id="dt_council_ID" value="<?php echo $admin_council_select; ?>">

	<div class="row mx-0">
		<div class="col-md-6 md-3">
			<?php
				$field_var 		= 'dt_name';
				$field_name 	= 'Reviewer Name';
				$field_type 	= 'text';
				$field_size 	= '128';
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
				$min 		 	= '';
				$max 		 	= '';
				$txt_rows	 	= '';
				formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript,$min,$max,$txt_rows);
			?>
		</div>
		<div class="col-md-6 md-3">
			<?php
				$field_var 		= 'dt_email';
				$field_name 	= 'Reviewer Email';
				$field_type 	= 'text';
				$field_size 	= '256';
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
				$min 		 	= '';
				$max 		 	= '';
				$txt_rows	 	= '';
				formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript,$min,$max,$txt_rows);
			?>
		</div>

		<div class="col-md-3 md-3">
			<?php
				$dt_active = $this_id > 0 ? $dt_active : 'yes';

				$field_var 		= 'dt_active';
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
