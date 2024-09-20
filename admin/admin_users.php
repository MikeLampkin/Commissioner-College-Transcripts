<?php  // ** Lampkin 2024 ** // ?>

<?php
	$page_is_protected = 'yes';
	$navshow 		= 'yes';

	$pg_title 		= 'Admin Access';
	$pg_keywords 	= '';
	$pg_description = '';

	$pg 			= 'admin_users';
	$db_table 		= 'transcripts';

	require 'includes/header.php';

?>

<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-6" id="alertMsg"></div>
	<div class="col-md-3"></div>
</div>

<div class="row mb-3">
	<div id="" class="col-md-2"><span id="addItem" data-info="" class="btn btn-primary btn-sm edit-item" data-bs-toggle="modal" data-bs-target="#modalAlert"><i class="fa-solid fa-circle-plus"></i> Add New Admin</span></div>
	<div id="" class="col-md-4"></div>
	<div id="" class="col-md-4 text-end">
<?php
// adminCouncilSelect
if( $admin_council_ID == '9999' )
{
	$sql = "
	SELECT DISTINCT(`user_council_ID`)
	FROM `users`
	WHERE 1=1
	AND `user_council_ID` <> ''
	AND `user_council_ID` IS NOT NULL
	AND `user_active` = 'yes'
	ORDER BY `user_council_ID`
	";
	// echo nl2br($sql) . '<br />';
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results);
	$output_array = array();
	$x=0;
	if ( $cnt > 0 )
	{
		while( $row = mysqli_fetch_assoc($results) )
		{
			$user_council_ID = $row['user_council_ID'];
			$user_council_ID_term = getCouncilFromID($user_council_ID);
			$output_array[$user_council_ID] = $user_council_ID_term;
			$x++;
		}

		asort($output_array);

		echo '
				<div class="mb-3 row">
					<label for="adminCouncilSelect" class="col-sm-2 col-form-label">Council </label>
					<div class="col-sm-10">
						<select class="form-select" id="adminCouncilSelect">
							<option value="9999" id="ALL" data-info="9999">** ALL COUNCILS ***</option>

		';
				foreach($output_array AS $key => $value )
				{
					echo '<option value="' . $key . '" id="' . $key . '" data-info="' . $key . '">' . $value . '</option>';
				}
		echo '
					</select>
				</div>
			</div>
		';
	}
}
?>
	</div>
</div>

<div id="displayResults" class="col-md-12">
	<h4> <i class="fa-solid fa-spinner fa-spin"></i> Thinking...</h4>
</div>

<script>
	let thisPage = 'admin_users';
	let thisTable = 'admin_users';

	const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
	let today = new Date();


	function getList() {
		let marker = Math.floor(randomNumber(0, 255));

		let pgActive = localStorage.getItem('pgActive');
		let adminCouncilSelect = localStorage.getItem('adminCouncilSelect');

		let mydata = {
			pgActive:pgActive,
			adminUser:adminUser,
			adminCouncilSelect:adminCouncilSelect,
		};

		$.ajax({
			url: 		"jquery/jq_" + thisPage + "_display.php?"+ marker,
			method: 	"POST",
			dataType:	"text",
			data: 		JSON.stringify(mydata),
			success:	function(response)
			{
				$('#displayResults').html(response);
			},
			error: function(response)
			{
				console.log('ERROR: ' + response);
			}
		});
	}

	function displayEntryForm(thisID) {
		let marker = Math.floor(randomNumber(0, 255));

		let adminCouncilSelect = localStorage.getItem('adminCouncilSelect');

		let mydata = {
			thisID:thisID,
			adminUser:adminUser,
			adminCouncilSelect:adminCouncilSelect,
		};

		$.ajax({
			url: "jquery/jq_" + thisPage + "_form.php?"+ marker,
			method: "POST",
			dataType: "text",
			data: JSON.stringify(mydata),
			success: function(response) {
				if( thisID < 1 )
				{
					$('#modalLabel').html('Add New Admin ');
				} else {
					$('#modalLabel').html('Modify This Admin');
				}
				$('#modalData').html(response);
				$('#modalFooter').html(modalButtonSave+ ' ' +modalButtonClose);
			},
			error: function(response) {
				console.log('ERROR: ' + response);
			}
		});
	}

	function refreshPage() {
		refreshAjax();
		console.log('refreshing ====>');

		getList();
		let adminCouncilSelect = localStorage.getItem('adminCouncilSelect');
		$('#adminCouncilSelect').val(adminCouncilSelect);

		$(document).ajaxComplete(function(e) {
			// displaySearchTerms();
			// let totalCnt = $('#totalCnt').val();
			// if (totalCnt >= 1) {
			// 	$(".pagination_display").html(getPagination(totalCnt));
			// }
		});
	}

	//? ===========>> document ready <<=============
	//? ===========>> document ready <<=============
	//? ===========>> document ready <<=============
	$(document).ready(function()
	{
		refreshPage();
		$('.modal-dialog').removeClass('modal-xl');

		//! ===========>> editItem
		$(document).on("click", '.edit-item', function(e) {
			$('.tooltip').remove();
			$('.modal-dialog').addClass('modal-xl');
			let thisID = $(this).data('info');
			displayEntryForm(thisID);
		});
		//! ===========>> editItem


		//! ===========>> adminCouncilSelect
		$(document).on("change", '#adminCouncilSelect', function(e) {
			let thisVal = $(this).val();
			localStorage.removeItem("adminCouncilSelect");
			localStorage.setItem("adminCouncilSelect",thisVal);
			refreshPage();
		});
		//! ===========>> adminCouncilSelect

	});
</script>

<?php  // ** Lampkin 2024 ** // ?>
<?php require 'includes/footer.php'; ?>
