<?php  // ** Lampkin 2023 ** // ?>
<?php // ** v 5.00 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/query.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/form_elements.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/arrays.php';

	include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/functions_custom.php";
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/geoplugin.php';

	$db_table 		= 'events';
	$pg_name 		= 'events';
	$var_ID 		= 'e_ID';
	$var_active 	= 'e_active';

	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$thisID = $mydata['thisID'];

	$disabled = '';

	$fields_array = query('getColumns', 'events', $db_name, '', '', '');

	$validator = $today_d . '_' . $today_t;
	$validator_scramble = scramble($validator);

	foreach( $fields_array as $key => $value )
	{
		$$value = '';
	}

	$sql = "
		SELECT  *
		FROM `" . $db_table . "`
		WHERE `" . $var_ID . "` = '" . $thisID . "'
	";
	// echo nl2br($sql) . '<br />';
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results);

	$msg = '<i class="far fa-plus-square"></i> Add Item';
	$bg_clr = "alert-info";
	$form_action = 'insert';

	if ($thisID > 0) {
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

<?php

//! TEST DATA
// $e_council_ID = 576;
// $e_date = date('Y-m-d',strtotime('2024-03-12'));
// $e_url = 'shac.com';
// $e_submit_name = 'Mike Lampkinin';
// $e_submit_email = 'harvey@lampkin.net';


?>
	<form class="nice-form mx-auto mb-3" id="event-entry" method="POST" enctype="multipart/form-data" >

	<input type="hidden" class="form-control" name="process" id="process" value="process" />
	<input type="hidden" class="form-control" name="id_field" value="<?php echo $var_ID; ?>" />
	<input type="hidden" class="form-control" name="db_table" value="<?php echo $db_table; ?>" />
	<input type="hidden" class="form-control" name="action" value="<?php echo $form_action; ?>" />
	<input type="hidden" class="form-control" name="<?php echo $var_ID;?>" value="<?php echo $thisID; ?>">

	<input type="hidden" name="validate" value="<?php echo $validator; ?>">
	<input type="hidden" name="e_active" value="uap">
	<input type="hidden" name="e_submit_date" value="<?php echo $today_dt; ?>">
	<div class="row mx-0">

		<div class="col-md-6">
			<?php
				$data_array = array();
				$sql = "
				SELECT *
				FROM `councils`
				WHERE `council_active` = 'yes'
				ORDER BY `council_name`
				";
				$results = mysqli_query($con_master,$sql);
				while ($row = mysqli_fetch_assoc($results))
				{
					$council_bsa_ID = $row['council_bsa_ID'];
					$council_name = $row['council_name'];
					$data_array[$council_bsa_ID] = $council_name;
				}

				$field_var 		= 'e_council_ID';
				$field_name 	= 'Council';
				$field_type 	= 'select';
				$field_size 	= '128';
				$required 		= 'required';
				$placeholder 	= '';
				$tabindex		= '';
				$disabled		= ''; // Optional
				$addl_var		= $data_array; // Optional **ADD ARRAY HERE
				$tooltip		= ''; // Optional
				$footie			= ''; // Optional
				$typeahead		= ''; // Optional
				$form_id 		= 'event-entry';
				$javascript 	= '';
				$min 		 	= '';
				$max 		 	= '';
				$txt_rows	 	= '';
				formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript,$min,$max,$txt_rows);
			?>
		</div>

		<?php
				// $sixty_marker = date('Y-m-d',strtotime('-60 days'));
		?>
		<div class="col-md-6">
			<?php
				$field_var 		= 'e_date';
				$field_name 	= 'Date of Event';
				$field_type 	= 'date';
				$field_size 	= '128';
				$required 		= 'required';
				$placeholder 	= '';
				$tabindex		= '';
				$disabled		= ''; // Optional
				$addl_var		= ''; // Optional **ADD ARRAY HERE
				$tooltip		= ''; // Optional
				$footie			= ''; // Optional
				$typeahead		= ''; // Optional
				$form_id 		= 'event-entry';
				$javascript 	= '';
				$min 		 	= date('Y-m-d');
				$max 		 	= '';
				$txt_rows	 	= '';
				formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript,$min,$max,$txt_rows);
			?>
		</div>

		<div class="col-md-12">
			<?php
				$field_var 		= 'e_url';
				$field_name 	= 'Website or Registration URL';
				$field_type 	= 'text';
				$field_size 	= '128';
				$required 		= 'required';
				$placeholder 	= '';
				$tabindex		= '';
				$disabled		= ''; // Optional
				$addl_var		= ''; // Optional **ADD ARRAY HERE
				$tooltip		= ''; // Optional
				$footie			= ''; // Optional
				$typeahead		= ''; // Optional
				$form_id 		= 'event-entry';
				$javascript 	= '';
				$min 		 	= '';
				$max 		 	= '';
				$txt_rows	 	= '';
				formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript,$min,$max,$txt_rows);
			?>
		</div>
		<div class="col-md-2">
			<?php
				$e_virtual = strlen($e_ID) < 1 ? 'no' : $e_virtual;

				$field_var 		= 'e_virtual';
				$field_name 	= 'Is the event virtual?';
				$field_type 	= 'select';
				$field_size 	= '128';
				$required 		= 'required';
				$placeholder 	= '';
				$tabindex		= '';
				$disabled		= ''; // Optional
				$addl_var		= $yesno_array; // Optional **ADD ARRAY HERE
				$tooltip		= ''; // Optional
				$footie			= ''; // Optional
				$typeahead		= ''; // Optional
				$form_id 		= 'event-entry';
				$javascript 	= '';
				$min 		 	= '';
				$max 		 	= '';
				$txt_rows	 	= '';
				formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript,$min,$max,$txt_rows);
			?>
		</div>

		<div class="col-md-6">
			<?php
				$field_var 		= 'e_location';
				$field_name 	= 'Location';
				$field_type 	= 'text';
				$field_size 	= '128';
				$required 		= '';
				$placeholder 	= '';
				$tabindex		= '';
				$disabled		= ''; // Optional
				$addl_var		= ''; // Optional **ADD ARRAY HERE
				$tooltip		= ''; // Optional
				$footie			= ''; // Optional
				$typeahead		= ''; // Optional
				$form_id 		= 'event-entry';
				$javascript 	= '';
				$min 		 	= '';
				$max 		 	= '';
				$txt_rows	 	= '';
				formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript,$min,$max,$txt_rows);
			?>
		</div>

		<div class="col-md-2">
			<?php
				$field_var 		= 'e_time_start';
				$field_name 	= 'Start Time ';
				$field_type 	= 'time';
				$field_size 	= '60';
				$required 		= '';
				$placeholder 	= '';
				$tabindex		= '';
				$disabled		= ''; // Optional
				$addl_var		= ''; // Optional **ADD ARRAY HERE
				$tooltip		= ''; // Optional
				$footie			= ''; // Optional
				$typeahead		= ''; // Optional
				$form_id 		= 'event-entry';
				$javascript 	= '';
				$min 		 	= '06:00';
				$max 		 	= '21:00';
				$txt_rows	 	= '';
				formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript,$min,$max,$txt_rows);
			?>
		</div>

		<div class="col-md-2">
			<?php
				$field_var 		= 'e_time_end';
				$field_name 	= 'End Time';
				$field_type 	= 'time';
				$field_size 	= '60';
				$required 		= '';
				$placeholder 	= '';
				$tabindex		= '';
				$disabled		= ''; // Optional
				$addl_var		= ''; // Optional **ADD ARRAY HERE
				$tooltip		= ''; // Optional
				$footie			= ''; // Optional
				$typeahead		= ''; // Optional
				$form_id 		= 'event-entry';
				$javascript 	= '';
				$min 		 	= '';
				$max 		 	= '';
				$txt_rows	 	= '';
				formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript,$min,$max,$txt_rows);
			?>
		</div>

		<div class="col-md-3">
			<?php
				$field_var 		= 'e_cost';
				$field_name 	= 'Cost';
				$field_type 	= 'text';
				$field_size 	= '12';
				$required 		= '';
				$placeholder 	= '';
				$tabindex		= '';
				$disabled		= ''; // Optional
				$addl_var		= ''; // Optional **ADD ARRAY HERE
				$tooltip		= ''; // Optional
				$footie			= ''; // Optional
				$typeahead		= ''; // Optional
				$form_id 		= 'event-entry';
				$javascript 	= '';
				$min 		 	= '';
				$max 		 	= '';
				$txt_rows	 	= '';
				formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript,$min,$max,$txt_rows);
			?>
		</div>

		<div class="col-md-6">
			<?php
				$field_var 		= 'e_offerings';
				$field_name 	= 'Offering';
				$field_type 	= 'textarea';
				$field_size 	= '1000';
				$required 		= '';
				$placeholder 	= '';
				$tabindex		= '';
				$disabled		= ''; // Optional
				$addl_var		= ''; // Optional **ADD ARRAY HERE
				$tooltip		= ''; // Optional
				$footie			= ''; // Optional
				$typeahead		= ''; // Optional
				$form_id 		= 'event-entry';
				$javascript 	= '';
				$min 		 	= '';
				$max 		 	= '';
				$txt_rows	 	= '';
				formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript,$min,$max,$txt_rows);
			?>
		</div>

		<div class="col-md-6">
			<?php
				$field_var 		= 'e_info';
				$field_name 	= 'More Info';
				$field_type 	= 'textarea';
				$field_size 	= '1000';
				$required 		= '';
				$placeholder 	= '';
				$tabindex		= '';
				$disabled		= ''; // Optional
				$addl_var		= ''; // Optional **ADD ARRAY HERE
				$tooltip		= ''; // Optional
				$footie			= ''; // Optional
				$typeahead		= ''; // Optional
				$form_id 		= 'event-entry';
				$javascript 	= '';
				$min 		 	= '';
				$max 		 	= '';
				$txt_rows	 	= '';
				formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript,$min,$max,$txt_rows);
			?>
		</div>

<hr />
		<div class="col-md-6">
			<?php
				$field_var 		= 'e_submit_name';
				$field_name 	= 'Your Name';
				$field_type 	= 'text';
				$field_size 	= '128';
				$required 		= 'required';
				$placeholder 	= '';
				$tabindex		= '';
				$disabled		= ''; // Optional
				$addl_var		= ''; // Optional **ADD ARRAY HERE
				$tooltip		= ''; // Optional
				$footie			= ''; // Optional
				$typeahead		= ''; // Optional
				$form_id 		= 'event-entry';
				$javascript 	= '';
				$min 		 	= '';
				$max 		 	= '';
				$txt_rows	 	= '';
				formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript,$min,$max,$txt_rows);
			?>
		</div>

		<div class="col-md-6">
			<?php
				$field_var 		= 'e_submit_email';
				$field_name 	= 'Your Email';
				$field_type 	= 'text';
				$field_size 	= '128';
				$required 		= 'required';
				$placeholder 	= '';
				$tabindex		= '';
				$disabled		= ''; // Optional
				$addl_var		= ''; // Optional **ADD ARRAY HERE
				$tooltip		= ''; // Optional
				$footie			= ''; // Optional
				$typeahead		= ''; // Optional
				$form_id 		= 'event-entry';
				$javascript 	= '';
				$min 		 	= '';
				$max 		 	= '';
				$txt_rows	 	= '';
				formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript,$min,$max,$txt_rows);
			?>
		</div>

		<div class="col-md-4">
			<?php include "ssi_form_verify.php"; ?>
		</div>
		<div class="col-4">
			<input type="hidden" name="e_ipaddress" id="request_ipaddress" value="<?php echo $your_ipaddress; ?>" >
			<input type="hidden" name="e_geodata" id="request_geodata" value="<?php echo $request_geodata; ?>" >
			<small><span class="text-secondary">User info: <br /><?php echo $your_ipaddress; ?>
			<!-- <br /><?php //echo $request_geodata; ?> -->
			</span></small>
		</div>

	</div>

	<div class="" id="submit-buttons-box">
		<div class="form-group col-md-12 center mx-auto my-2 text-center">

			<div class="" id="submitOn">
				<div class="text-danger" id="form_data_nosubmit_warn"> Please make sure that you've filled in all the required fields.</div>
				<span class="btn btn-secondary" id="form_data_nosubmit" > <i class="fa-solid fa-sync fa-spin"></i> Please complete the form. </span>
				<button type="submit" class="btn btn-success hideme" id="form_data_submit">Submit My Application</button>
				<span class="btn btn-danger rounded text-white" data-bs-dismiss="modal"> Cancel </span>
			</div>

			<div class="hideme" id="submitOff">
				<div class="xxfa-2x btn btn-secondary col-md-6">
					<i class="fas fa-sync fa-spin"></i> Saving your application form...
				</div>
			</div>

		</div>
	</div>

</form>
<script>
$('#event_submit_email').keyup(function() {
	let newval = $(this).val().toLowerCase();
	$(this).val(newval);
});

$('#event-entry').keyup(function() {
	validTest('event-entry');
});

//! ===========>> submitEvent
$(document).on("click", '#form_data_submit', function(e) {
	e.preventDefault();
	if ($('#event-entry')[0].checkValidity() === false) {
		e.stopPropagation();
	} else {
		console.log('=>>> POST <<<=');
		processEventForm();

		setTimeout(function() {
			$('#modalAlert').modal('hide');
		}, 1000);
	}
	$('#event-entry').addClass('was-validated');
});
//! ===========>> submitEvent
</script>
