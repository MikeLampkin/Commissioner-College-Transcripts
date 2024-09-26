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

	$waiting_list_fields_array = query('getColumns', 'waiting_list', $db_name, '', '', '');

	$validator = $today_d . '_' . $today_t;
	$validator_scramble = scramble($validator);
?>
<?php

//! TEST DATA
// $wl_council_ID = 576;
// $wl_date = date('Y-m-d',strtotime('2024-03-12'));
// $wl_url = 'shac.com';
// $wl_submit_name = 'Mike Lampkinin';
// $wl_submit_email = 'harvey@lampkin.net';


?>
	<form class="nice-form mx-auto mb-3" id="waiting_list" method="POST" enctype="multipart/form-data" >

<!-- <form class="nice-form mx-auto mb-3 needs-validation" id="waiting_list" method="POST" enctype="multipart/form-data" novalidate> -->
	<input type="hidden" name="wl_validate" id="wl_validate value="<?php echo $validator; ?>">
	<div class="row mx-0">

		<div class="col-md-6">
			<?php
				$field_var 		= 'wl_submit_name';
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
				$form_id 		= 'waiting_list';
				$javascript 	= '';
				$min 		 	= '';
				$max 		 	= '';
				$txt_rows	 	= '';
				formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript,$min,$max,$txt_rows);
			?>
		</div>

		<div class="col-md-6">
			<?php
				$field_var 		= 'wl_submit_email';
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
				$form_id 		= 'waiting_list';
				$javascript 	= '';
				$min 		 	= '';
				$max 		 	= '';
				$txt_rows	 	= '';
				formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript,$min,$max,$txt_rows);
			?>
		</div>

		<div class="col-md-4">
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

				$field_var 		= 'wl_council_ID';
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
				$form_id 		= 'waiting_list';
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
			<input type="hidden" name="wl_ipaddress" id="request_ipaddress" value="<?php echo $your_ipaddress; ?>" >
			<input type="hidden" name="wl_geodata" id="request_geodata" value="<?php echo $request_geodata; ?>" >
			<small><span class="text-secondary">User info: <br /><?php echo $your_ipaddress; ?>
			<!-- <br /><?php //echo $request_geodata; ?> -->
			</span></small>
		</div>

	</div>

	<!-- <div class="mb-3 col-md-12 text-center mx-auto">
		<button id="submitWaiting" class="btn btn-success form-submit col-6 hide" type="submit" role="button" data-bs-dismiss="modal"> <i class="fas fa-save"></i> S A V E </button>
		<button id="waiting" class="btn btn-secondary col-6" data-bs-dismiss="modal"> <i class="fa-solid fa-spinner fa-spin"></i> Please complete form. </button>
	</div> -->
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
// $('#submitWaiting').hide();

$('#wl_submit_email').keyup(function() {
	let newval = $(this).val().toLowerCase();
	$(this).val(newval);
});


$('#waiting_list').keyup(function() {
	validTest('waiting_list');
});


//! ===========>> submitWaiting
$(document).on("click", '#form_data_submit', function(e) {
	e.preventDefault();
	if ($('#waiting_list')[0].checkValidity() === false) {
		e.stopPropagation();
	} else {
		console.log('=>>> POST <<<=');
		processWaitingListForm();

		setTimeout(function() {
			$('#modalAlert').modal('hide');
		}, 1000);
	}
	$('#waiting_list').addClass('was-validated');
});
//! ===========>> submitWaiting


</script>
