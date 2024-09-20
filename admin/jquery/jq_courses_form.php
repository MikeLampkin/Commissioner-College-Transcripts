<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	include 	 $config_form_elements;
	include 	 $config_arrays;
	include 	 $config_functions_custom;

	$db_table 		= 'courses';
	$data_results 	= '';
	$var_ID 		= 'course_ID';
	$var_active 	= 'course_active';

	$fields_array = $db_table . '_fields_array';

	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$admin_user = $mydata['adminUser'];
		$council = $mydata['councilSelect'];
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

	$council_ID_phrase = $council !== '9999' ? getCouncilFromID($council) : 'ALL Councils';
	echo '<h5> Editing for ' . $council_ID_phrase . '</h5>';

	$sql = "
		SELECT  *
		FROM `courses`
		WHERE `course_ID` = '" . $this_id . "'
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
<input type="text" id="js_council" value="<?php echo $council; ?>">

<form class="nice-form mx-auto " id="data_entry" method="POST" enctype="multipart/form-data" >

	<input type="hidden" class="form-control" name="process" id="process" value="process" />
	<input type="hidden" class="form-control" name="id_field" id="id_field" value="<?php echo $var_ID; ?>" />
	<input type="hidden" class="form-control" name="db_table" id="db_table" value="<?php echo $db_table; ?>" />
	<input type="hidden" class="form-control" name="action" id="action" value="<?php echo $form_action; ?>" />
	<input type="hidden" class="form-control" name="course_ID" id="course_ID" value="<?php echo $this_id; ?>">

	course code: <input type="text" name="course_code" id="course_code" value="<?php echo $course_code; ?>" /><br >

	<div class="row mx-0">

		<div class="col-md-3 md-3">
			<?php
			$course_type_array = array(
				'ACS'=>'ACS',
				'BCS'=>'BCS',
				'MCS'=>'MCS',
				'DCS'=>'DCS',
				'CED'=>'CED',

			);
				$field_var 		= 'course_type';
				$field_name 	= 'Course Type';
				$field_type 	= 'select';
				$field_size 	= '128';
				$required 		= 'required';
				$placeholder 	= '';
				$tabindex		= '';
				$disabled		= ''; // Optional
				$addl_var		= $course_type_array; // Optional
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

		<div class="col-md-2 md-3">
			<?php
				$field_var 		= 'course_number';
				$field_name 	= 'Course Number';
				$field_type 	= 'text';
				$field_size 	= '3';
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

		<div class="col-md-2 md-3">
			<?php
				$data_array = array();
				for($x=0;$x<10;$x++){ $data_array[$x] =$x; }
				$field_var 		= 'course_number_ext';
				$field_name 	= 'Course Ext';
				$field_type 	= 'select';
				$field_size 	= '3';
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
				$min 		 	= '';
				$max 		 	= '';
				$txt_rows	 	= '';
				formElements($field_var,$$field_var,$field_name,$field_type,$placeholder,$required,$field_size,$tabindex,$disabled,$addl_var,$tooltip,$footie,$typeahead,$form_id,$javascript,$min,$max,$txt_rows);
			?>
		</div>

		<div class="col-md-6 md-3">
			<?php
				$field_var 		= 'course_name';
				$field_name 	= 'Course Name';
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

		<div class="col-md-4">
			<?php

			if( $my_admin_level == '999' && $my_admin_council_ID == '9999' )
			{
				$data_array = array('900'=>'** ALL COUNCILS ***');
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

				$field_var 		= 'course_council_ID';
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

		<div class="col-md-2 md-3">
			<?php
				$course_active = $this_id > 0 ? $course_active : 'yes';

				$field_var 		= 'course_active';
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


<script>

function calcCourseCodeID()
{
	const course_code_array  = {
	'ACS' : '0',
	'BCS' : '1',
	'MCS' : '2',
	'DCS' : '3',
	'CED' : '9',
	};


	let courseType = $('#course_type').val();
	let courseTypeCode = course_code_array[courseType];
	let courseCode = $('#course_code').val();
	let courseCodeExt = $('#course_code_ext').val();

	let courseNum = $('#course_number').val();
	let council = $('#course_council_ID').val();
	let newCode = '1' +courseTypeCode + courseNum + courseCodeExt + council;
}

	//? ===========>> document ready <<=============
	//? ===========>> document ready <<=============
	//? ===========>> document ready <<=============
	$(document).ready(function()
	{
		calcCourseCodeID();

		//! ===========>> calcCourseCodeID
		$(document).on("change", '#course_code', function(e) {
			calcCourseCodeID();
		});

		$(document).on("change", '#course_code_ext', function(e) {
			calcCourseCodeID();
		});

		$(document).on("change", '#course_type', function(e) {
			calcCourseCodeID();
		});

		$(document).on("change", '#course_council_ID', function(e) {
			calcCourseCodeID();
		});

		$(document).on("change", '#course_code', function(e) {
			calcCourseCodeID();
		});
		//! ===========>> calcCourseCodeID

	});
</script>
