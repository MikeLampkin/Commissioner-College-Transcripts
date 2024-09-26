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

			$user_full_name = userFullnameFromID($thesis_user_ID);

		}
		$msg = '<i class="fas fa-edit"></i> Upload Thesis for ' . $user_full_name;
		$bg_clr = "alert-warning";
		$form_action = 'update';
	}

?>

<form id="uploader" class="row ">
	<input type="hidden" class="form-control" name="thesis_ID" id="thesis_ID" value="<?php echo $this_id; ?>">
	<input type="hidden" class="form-control" name="thesis_council_ID" id="thesis_council_ID" value="<?php echo $admin_council_select; ?>">

	<div class="col-auto p-2">
		<i class="fa-solid fa-image fa-lg"></i>
		<?php echo $msg; ?>
	</div>
	<!-- <div class="col">
		<input type="text" class="form-control" name="upload_desc" id="upload_desc" placeholder="Description of File" aria-label="Description of File" value="<?php echo $upload_desc; ?>" required />
	</div> -->
	<div class="col">
		<input class="form-control" type="file" name="upload_file" id="upload_file"  placeholder="Upload File" aria-label="Upload File" required />
	</div>
	<div class="col-auto">
		<button type="submit" id="upload_submit" class="btn btn-primary">Submit</button>
		<a href="uploads" type="cancel" id="clear" class="btn btn-orange">Clear</a>
	</div>
</form>
