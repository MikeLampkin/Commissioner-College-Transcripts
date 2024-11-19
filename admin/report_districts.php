<?php  // ** Lampkin 2024 ** // ?>

<?php
	$page_is_protected = 'yes';
	$navshow 		= 'yes';

	$pg_title 		= 'Reports: Districts';
	$pg_keywords 	= '';
	$pg_description = '';

	$pg 			= 'report_districts';
	$db_table 		= 'users';

	require 'includes/header.php';

?>

<style>
	.tr_active
	{
		background-color: yellow;
	}

	.marker_never.marker_no td
	{
		color: darkred!important;
		font-weight:bold;
		background-color: pink!important;
	}

	.marker_yes
	{
		color: darkgreen!important;
		font-weight: bold;
		background-color: lime!important;
	}
</style>
<div class="alert alert-secondary row">
	<div id="" class="col-md-3 text-start">
		<span class="btn btn-primary btn-sm" id="reloadPage"><i class="fa-solid fa-arrows-rotate" id="reloadIcon"></i> Refresh Page</span>
	</div>
	<div class="">
		<!-- Use this page to export MS Excel files of all of your data. -->
		<strong> Generate Districts Report </strong>: to download a spreadsheet of all user information
		<br />
		Please note that reports are only kept for 30 days. Please download your report once you've generated it.
	</div>
</div>

<div class="grid text-center">
	<button type="button" class="g-col-6 btn btn-success" id="districtsReport"> Generate Districts Report </button>
	<span class="g-col-6  btn btn-secondary" id="districtsReportWait"><i class="fa-solid fa-spinner-scale fa-spin-pulse"></i> Generating Districts Report </span>
</div>

<div id="displayResults" class="col-md-12">
	<h4> <i class="fa-solid fa-spinner fa-spin"></i> Thinking...</h4>
</div>

<script>
	let thisPage = 'report_districts';
	let thisTable = 'users';

	const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
	let today = new Date();

	function getList() {
		let marker = Math.floor(randomNumber(0, 255));
		let adminCouncilSelect = localStorage.getItem('adminCouncilSelect');

		let mydata = {
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

	function generateDistrictsReport() {
		let marker = Math.floor(randomNumber(0, 255));
		let adminCouncilSelect = localStorage.getItem('adminCouncilSelect');

		let mydata = {
			adminUser:adminUser,
			adminCouncilSelect:adminCouncilSelect,
		};

		$.ajax({
			url: 		"jquery/jq_" + thisPage + "_users.php?"+ marker,
			method: 	"POST",
			dataType:	"text",
			data: 		JSON.stringify(mydata),
			success:	function(response)
			{
				let trimResponse = response.trim();
				toastMessage(trimResponse,'');
				$('#districtsReport').show();
				$('#districtsReportWait').hide();
				refreshPage();
			},
			error: function(response)
			{
				console.log('ERROR: ' + response);
			}
		});
	}

	function deleteFile(file,directory)
	{
		let mydata = {
			file:file,
			directory:directory,
		};
		$.ajax({
			url: 		"jquery/jq_delete_file.php",
			method: 	"POST",
			dataType:	"text",
			data: 		JSON.stringify(mydata),
			success:	function(response)
			{
				let trimResponse = response.trim();
				toastMessage(trimResponse,'')
				refreshPage();
			},
			error: function(response)
			{
				console.log('ERROR: ' + response);
				// if($("#datadump")) { $("#datadump").html(response); }
			}
		});
	}

	function refreshPage() {
		refreshAjax();
		console.log('refreshing ====>');
		getList();
		$('#transcriptsReportWait').hide();
		$('#districtsReportWait').hide();
	}

	//? ===========>> document ready <<=============
	//? ===========>> document ready <<=============
	//? ===========>> document ready <<=============
	$(document).ready(function()
	{
		refreshPage();

		//! ===========>> generateDistrictsReport
		$(document).on("click", '#districtsReport', function(e) {
			e.preventDefault();
			$('#districtsReport').hide();
			$('#districtsReportWait').show();
			generatedistrictsReport();
		});
		//! ===========>> generateDistrictsReport

		$(document).on("click", '.delete-file-button', function(e) {
			e.preventDefault();
			let thisFile = $(this).data('file');
			if($("#modalData")) { $("#modalData").html('Are you sure you want to delete <strong>' + thisFile + '</strong>?'); }
			$('#modalLabel').html('Delete File');
			$('#modalFooter').html('<button type="button" class="btn btn-danger action-btn delete-file-commit" id="deletefilecommit" data-file="'+ thisFile +'"><i class="fa-solid fa-skull-crossbones"></i> Delete</button>');
			$('#modalAlert').modal('show');
		});

		$(document).on("click", ".delete-file-commit", function(e)
		{
			$('#modalAlert').modal('hide');
			let thisFile = $(this).data('file');
			deleteFile(thisFile,'reports');
		});

	});
</script>


<?php  // ** Lampkin 2024 ** // ?>
<?php require 'includes/footer.php'; ?>
