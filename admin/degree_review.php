<?php  // ** Lampkin 2024 ** // ?>

<?php
	$page_is_protected = 'yes';
	$navshow 		= 'yes';

	$pg_title 		= 'Degree Review';
	$pg_keywords 	= '';
	$pg_description = '';

	$pg 			= 'degree_review';
	$db_table 		= 'degree_review';

	require 'includes/header.php';

?>

<div class="alert alert-secondary row">
	<div class="col-md-3 text-start">
		<!-- <span id="addItem" data-info="" class="btn btn-purple text-white btn-sm edit-item" data-bs-toggle="modal" data-bs-target="#modalAlert"><i class="fa-solid fa-circle-plus"></i> Add New Reviewer</span> -->
		<!-- <span class="btn btn-primary btn-sm" id="reloadPage"><i class="fa-solid fa-arrows-rotate" id="reloadIcon"></i> Refresh Page</span> -->
	</div>
	<div class="col-md-6" id="workSpace"></div>
	<div class="col-md-3 text-end">
			<!-- <span id="viewActiveBtns"></span> -->
			<!-- <span id="limitAmount"></span> -->
	</div>
</div>

<div id="displayResults" class="col-md-12">
	<h4> <i class="fa-solid fa-spinner fa-spin"></i> Thinking...</h4>
</div>

<script>
	let thisPage = 'degree_review';
	let thisTable = 'transcripts';

	const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
	let today = new Date();
	console.log('Today: ' + today);

	let activeSelect = typeof(localStorage.getItem('activeSelect_dr')) != "undefined" && localStorage.getItem('activeSelect_dr') != null ? localStorage.getItem('activeSelect_dr') : 'yes';
	localStorage.setItem("activeSelect_dr",activeSelect);

	function displaysearchTerms() {
		let itemSelect = localStorage.getItem('itemSelect') ?? '';
		$('#search_terms').val(itemSelect);
	}

	function getList() {
		let activeSelect = localStorage.getItem('activeSelect_dr');
		let adminCouncilSelect = localStorage.getItem('adminCouncilSelect');

		let mydata = {

			adminCouncilSelect:adminCouncilSelect,
			activeSelect:activeSelect,
		};

		$.ajax({
			url: 		"jquery/jq_"+ thisPage +"_display.php",
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



	function refreshPage() {
		refreshAjax();
		console.log('refreshing ====>');

		getList();

		$(document).ajaxComplete(function(e) {
			displaysearchTerms();

			let totalCnt = $('#totalCnt').val();
			if (totalCnt >= 1) {
				$(".pagination_display").html(getPagination(totalCnt));
			}
		});
	}

	//? ===========>> document ready <<=============
	//? ===========>> document ready <<=============
	//? ===========>> document ready <<=============
	$(document).ready(function() {

		refreshPage();
		$('.modal-dialog').removeClass('modal-xl');

		//! ===========>> LISTENERS
		//! ===========>> LISTENERS
		//! ===========>> LISTENERS


	});
</script>


<?php  // ** Lampkin 2023 ** // ?>
<?php require "includes/footer.php"; ?>
