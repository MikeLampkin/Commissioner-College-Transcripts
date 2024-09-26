<?php  // ** Lampkin 2024 ** // ?>

<?php
	$page_is_protected = 'yes';
	$navshow 		= 'yes';

	$pg_title 		= 'Degree Review Team';
	$pg_keywords 	= '';
	$pg_description = '';

	$pg 			= 'degree_team';
	$db_table 		= 'degree_team';

	require 'includes/header.php';

?>

<div class="alert alert-secondary row">
	<div class="col-md-3 text-start">
		<span id="addItem" data-info="" class="btn btn-purple text-white btn-sm edit-item" data-bs-toggle="modal" data-bs-target="#modalAlert"><i class="fa-solid fa-circle-plus"></i> Add New Reviewer</span>
		<span class="btn btn-primary btn-sm" id="reloadPage"><i class="fa-solid fa-arrows-rotate" id="reloadIcon"></i> Refresh Page</span>
	</div>
	<div class="col-md-6" id="workSpace"></div>
	<div class="col-md-3 text-end">
			<span id="viewActiveBtns"></span>
			<!-- <span id="limitAmount"></span> -->
	</div>
</div>

<div id="" class="alert alert-info row">
	<div class="col-6"><i class="fa-solid fa-envelope-open-text"></i> As training happens monthly and records get updated regularly, it is sometimes necessary to have regular updates as to which commissioner has earned a degree. This is the team that receives the regular emails from the Degree Review bot.</div>

	<div class="col-6" id="notificationResults"></div>
</div>

<div id="displayResults" class="col-md-12">
	<h4> <i class="fa-solid fa-spinner fa-spin"></i> Thinking...</h4>
</div>

<script>
	let thisPage = 'degree_team';
	let thisTable = 'degree_team';

	const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
	let today = new Date();
	console.log('Today: ' + today);

	let activeSelect = typeof(localStorage.getItem('activeSelect_dt')) != "undefined" && localStorage.getItem('activeSelect_dt') != null ? localStorage.getItem('activeSelect_dt') : 'yes';
	localStorage.setItem("activeSelect_dt",activeSelect);

	function displaysearchTerms() {
		let itemSelect = localStorage.getItem('itemSelect') ?? '';
		$('#search_terms').val(itemSelect);
	}

	function getList() {
		let activeSelect = localStorage.getItem('activeSelect_dt');
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

	function displayEntryForm(thisID)
	{
		let adminCouncilSelect = localStorage.getItem('adminCouncilSelect');

		let marker = Math.floor(randomNumber(0, 255));

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
					$('#modalLabel').html('Add New Reviewer ');
				} else {
					$('#modalLabel').html('Modify This Reviewer');
				}
				$('#modalData').html(response);
				$('#modalFooter').html(modalButtonSave+ ' ' +modalButtonClose);
			},
			error: function(response) {
				console.log('ERROR: ' + response);
			}
		});
	}
	function displayNotificationForm(thisID)
	{
		let adminCouncilSelect = localStorage.getItem('adminCouncilSelect');

		let marker = Math.floor(randomNumber(0, 255));

		let mydata = {
			adminUser:adminUser,
			adminCouncilSelect:adminCouncilSelect,
		};

		$.ajax({
			url: "jquery/jq_degree_notification_form.php?"+ marker,
			method: "POST",
			dataType: "text",
			data: JSON.stringify(mydata),
			success: function(response) {
				$('#notificationResults').html(response);
			},
			error: function(response) {
				console.log('ERROR: ' + response);
			}
		});
	}

	function updateFrequency() {
		processForm('dn_data_entry');
	}

	function refreshPage() {
		refreshAjax();
		console.log('refreshing ====>');

		getList();
		displayNotificationForm();

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
		//! ===========>> activeButton <<=============
		$(document).on("click", '.active-btn', function(e)
		{
			localStorage.removeItem("activeSelect_dt");
			let newData = $(this).data('info');
			localStorage.setItem("activeSelect_dt",newData);
			refreshPage();
		});
		//! ===========>> activeButton <<=============

		//! ===========>> editItem
		$(document).on("click", '.edit-item', function(e) {
			$('.tooltip').remove();
			let thisID = $(this).data('info');
			displayEntryForm(thisID);
		});
		//! ===========>> editItem

		//! ===========>> SEARCH
		$(document).on("click", '#itemSelect', function(e) {
			e.preventDefault();
			let itemSelect = $(this).data('info');
			refreshPage();
		});
		//! ===========>> SEARCH

		//! ===========>> SEARCH
		$(document).on("change", '#dn_frequency', function(e) {
			e.preventDefault();
			// let itemSelect = $(this).data('info');
			updateFrequency();
		});
		//! ===========>> SEARCH

		$(document).ajaxComplete(function(e) {
			//! ===========>> LOWERCASE USER EMAIL  <<================
			$('#dt_email').keyup(function(){
				$(this).val($(this).val().toLowerCase());
			});
			//! ===========>> LOWERCASE USER EMAIL  <<================
		});


	});
</script>


<?php  // ** Lampkin 2023 ** // ?>
<?php require "includes/footer.php"; ?>
