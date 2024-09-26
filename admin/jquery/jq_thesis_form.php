<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	include 	 $config_form_elements;
	include 	 $config_arrays;
	include 	 $config_functions_custom;

	$db_table 		= 'thesis';
	$data_results 	= '';
	$var_ID 		= 'thesis_ID';
	$var_active 	= 'thesis_active';

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
		FROM `thesis`
		WHERE `thesis_ID` = '" . $this_id . "'
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




$data_array = array();

$sql = "
SELECT *
FROM `users`
WHERE 1=1
AND LOWER(`user_active`) = 'yes'
AND LOWER(`user_deceased`) <> 'yes'
AND `user_council_ID` <> ''
AND `user_council_ID` = '" . $admin_council_select . "'
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
?>

<form class="nice-form mx-auto " id="data_entry" method="POST" enctype="multipart/form-data" >

	<input type="hidden" class="form-control" name="process" id="process" value="process" />
	<input type="hidden" class="form-control" name="id_field" id="id_field" value="<?php echo $var_ID; ?>" />
	<input type="hidden" class="form-control" name="db_table" id="db_table" value="<?php echo $db_table; ?>" />
	<input type="hidden" class="form-control" name="action" id="action" value="<?php echo $form_action; ?>" />
	<input type="hidden" class="form-control" name="thesis_ID" id="thesis_ID" value="<?php echo $this_id; ?>">
	<input type="hidden" class="form-control" name="thesis_council_ID" id="thesis_council_ID" value="<?php echo $admin_council_select; ?>">

	<div class="row mx-0">

	<div class="col-md-6 md-3">
			<?php
				$field_var 		= 'thesis_user_ID';
				$field_name 	= 'Person';
				$field_type 	= 'select';
				$field_size 	= '128';
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
		<div class="col-md-6 md-3">
			<?php
				$field_var 		= 'thesis_title';
				$field_name 	= 'Title';
				$field_type 	= 'text';
				$field_size 	= '128';
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
		<div class="col-md-3 md-3">
			<?php
				$field_var 		= 'thesis_date';
				$field_name 	= 'Date';
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

		<div class="col-md-3 md-3">
			<?php
				$thesis_active = $this_id > 0 ? $thesis_active : 'yes';

				$field_var 		= 'thesis_active';
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
		<div class="col-md-3 md-3">
			<?php if( $thesis_user_ID < 0 ) { ?>
			<strong>Please note: You will attach the PDF once this listing is created.</strong>
			<?php } ?>
		</div>

	</div>

</form>
