<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	include 	 $config_form_elements;
	include 	 $config_arrays;
	include 	 $config_functions_custom;

	$db_table 		= 'degree_notifications';
	$data_results 	= '';
	$var_ID 		= 'dn_ID';
	$var_active 	= 'dn_active';

	$fields_array = $db_table . '_fields_array';

	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$admin_user = $mydata['adminUser'];
		$admin_council_select = $mydata['adminCouncilSelect'];

		$disabled = '';

	foreach( $$fields_array as $key => $value )
	{
		$$value = '';
	}

	$dn_frequency = 'off';
	$dn_coucil_ID = $admin_council_select;

	//! CHECK ADMIN LEVEL ACCESS  ==========================
	$my_admin_level = 100;
	$my_admin_level = getAdminLevel($admin_user);
	//! CHECK ADMIN LEVEL ACCESS  ==========================

	$sql = "
		SELECT  *
		FROM `degree_notifications`
		WHERE `dn_council_ID` LIKE '" . $admin_council_select . "'
	";
	// echo nl2br($sql) . '<br />';
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;

	$msg = '<i class="far fa-plus-square"></i> Add Item';
	$bg_clr = "alert-info";
	$form_action = 'insert';

	if ($cnt > 0) {
		while ($row = mysqli_fetch_assoc($results)) {
			foreach( $$fields_array as $key => $value ) {
				$$value = $row[$value];
			}
		}
		$msg = '<i class="fas fa-edit"></i> Edit Item';
		$bg_clr = "alert-warning";
		$form_action = 'update';
	}

	$frequency_array = array(
'mo' => 'Monthly',
'qu' => 'Quarterly',
'off' => '-- OFF --',
	);
?>
<form class="nice-form mx-auto " id="dn_data_entry" method="POST" enctype="multipart/form-data" >

	<input type="hidden" class="form-control" name="process" id="process" value="process" />
	<input type="hidden" class="form-control" name="id_field" id="id_field" value="<?php echo $var_ID; ?>" />
	<input type="hidden" class="form-control" name="db_table" id="db_table" value="<?php echo $db_table; ?>" />
	<input type="hidden" class="form-control" name="action" id="action" value="<?php echo $form_action; ?>" />
	<input type="hidden" class="form-control" name="<?php echo $var_ID; ?>" id="<?php echo $var_ID; ?>" value="<?php echo $$var_ID; ?>" />

	<input type="hidden" id="dn_coucil_ID" value="<?php echo $dn_coucil_ID; ?>">

	<div class="col-6"><i class="fa-solid fa-square-envelope"></i> How often would you like this report sent? </div>
	<div class="col-6">
		<select class="form-select" name="dn_frequency" id="dn_frequency">
			<option value="mo" <?php if( $dn_frequency == 'mo') { echo 'selected'; } ?>>Monthly</option>
			<option value="qu" <?php if( $dn_frequency == 'qu') { echo 'selected'; } ?>>Quarterly</option>
			<option value="off" <?php if( $dn_frequency == 'off') { echo 'selected'; } ?>>- OFF -</option>
		</select>
	</div>
</form>
