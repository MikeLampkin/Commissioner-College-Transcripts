<?php  // ** Lampkin 2024 ** // ?>

<?php
	$page_is_protected = 'yes';
	$navshow 		= 'yes';

	$pg_title 		= 'Core: Deans';
	$pg_keywords 	= '';
	$pg_description = '';

	$pg 			= 'deans';
	$db_table 		= 'deans';

	require 'includes/header.php';

?>

<div class="alert alert-secondary row">
	<div id="" class="col-md-3 text-start">
		<span class="btn btn-primary btn-sm" id="reloadPage"><i class="fa-solid fa-arrows-rotate" id="reloadIcon"></i> Refresh Page</span>
	</div>
</div>

<div id="displayResults" class="col-md-12">
	<h4> <i class="fa-solid fa-spinner fa-spin"></i> Thinking...</h4>
</div>

<script>
	let thisPage = 'deans';
	let thisTable = 'deans';

	const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
	let today = new Date();


	function displayEntryForm(thisID) {
		let marker = Math.floor(randomNumber(0, 255));

		let adminCouncilSelect = localStorage.getItem('adminCouncilSelect');

		let mydata = {
			adminUser:adminUser,
			adminCouncilSelect:adminCouncilSelect,
		};

		$.ajax({
			url: "jquery/jq_" + thisPage + "_form.php?"+ marker,
			method: "POST",
			dataType: "text",
			data: JSON.stringify(mydata),
			success: function(response) {
				$('#displayResults').html(response);
			},
			error: function(response) {
				console.log('ERROR: ' + response);
			}
		});
	}

	function refreshPage() {
		refreshAjax();
		console.log('refreshing ====>');

		displayEntryForm();
	}

	//? ===========>> document ready <<=============
	//? ===========>> document ready <<=============
	//? ===========>> document ready <<=============
	$(document).ready(function()
	{
		refreshPage();




	});
</script>


<?php  // ** Lampkin 2024 ** // ?>
<?php require 'includes/footer.php'; ?>
