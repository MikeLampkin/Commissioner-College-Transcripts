<?php  // ** Lampkin 2024 ** // ?>

<?php
	$page_is_protected = 'no';
	$navshow = 'yes';

	$pg_title = "Welcome";
	$pg_keywords = "";
	$pg_description = "";

	$pg = "home";
	$db_table = 'transcripts';

	require "includes/header.php";

?>



<!-- // -- Lampkin 2010 - 2024 -- // -->
<h2 class="">New Commissioner College Transcripts -- COMING SOON!</h2>

<div class="alert alert-light">
	<h4 class="text-darkred">Update: January 2024</h4>
	<p> After many requests, I've decided to push out the Commissioner Transcript System to all councils. You'll be able to sign up your council and track all of your commissioners' progress through their College of Commissioner journey.

	<!-- <p>To help offset costs, I'll have an advertisement or two on the site, but I'll do my best to keep the ads "civil" and unobtrusive.</p> -->

	I have a few folks on the waiting list to be notified when the site is ready. If you'd like to be notified, please drop me a note using the form below.</p>
	<p> Thanks! Mike </p>
</div>

<div id="messageBox"></div>

<div class="row p-1">
	<div class="col-md-6 pr-1">
		<div class="alert alert-warning">
			<div class="p-1 m-0"><h4><i class="fa-solid fa-list-ul"></i> Waiting List</h4></div>
			<div class="" id="waitingListFormAlert"></div>
			<div id="waitingListDisplay" class=""></div>
			<span class="btn btn-success col-12" id="displayWaitingListForm" data-bs-toggle="modal" data-bs-target="#modalAlert"> Join the Waiting List </span>

		</div>
	</div>
	<div class="col-md-6 pl-1">
		<div class="alert alert-primary">
			<div class="p-1 m-0"><h4><i class="fa-solid fa-party-horn"></i> Post Your Commissioner College</h4></div>
			<div class="" id="eventFormAlert"></div>
			<div id="eventListDisplay" class=""></div>
			<span class="btn btn-primary col-12" id="displayEventForm" data-bs-toggle="modal" data-bs-target="#modalAlert"> Submit Your Upcoming Event </span>
		</div>
	</div>
</div>


<!-- <div class="alert alert-warning"><h4>Testing</h4>
<?php

echo $request_geodata;
?>
</div> -->

<script>
	let thisPage = 'index';
	let thisTable = 'waiting_list';

	const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
	let today = new Date();
	console.log('Today: ' + today);
	let today_dt = formatDateYYYYMMDDHHMMSS(today);
	console.log('Today: ' + today_dt);

	function displayWaitingList() {

		let mydata = {

		};

		$.ajax({
			url: 		"jquery/jq_waiting_list_display.php",
			method: 	"POST",
			dataType:	"text",
			data: 		JSON.stringify(mydata),
			success:	function(response)
			{
				$('#waitingListDisplay').html(response);
			},
			error: function(response)
			{
				console.log('ERROR: ' + response);
			}
		});
	}


	function displayWaitingListForm() {
		let mydata = {

		};

		$.ajax({
			url: "jquery/jq_waiting_list_form.php",
			method: "POST",
			dataType: "text",
			data: JSON.stringify(mydata),
			success: function(response) {
				$('#modalLabel').html('Join the Waiting List!');
				$('#modalData').html(response);
			},
			error: function(response) {
				console.log('ERROR: ' + response);
			}
		});
	}

	function processWaitingListForm(formData) {

		let wlValidate = $('#wl_validate').val();
		let wlName = $('#wl_submit_name').val();
		let wlEmail = $('#wl_submit_email').val();
		let wlCouncil = $('#wl_council_ID').val();

		console.log('wlName: ' +wlName);
		let mydata = {
			wlValidate:wlValidate,
			wlName:wlName,
			wlEmail:wlEmail,
			wlCouncil:wlCouncil,
		}

		$.ajax({
			url: 		"jquery/jq_waiting_list_process.php",
			method: 	"POST",
			dataType:	"text",
			data: 		JSON.stringify(mydata),
			success:	function(response)
			{
				let trimResponse = response.trim();
				displayMessage(trimResponse,'waitingListFormAlert');
				refreshPage();
			},
			error: function(response)
			{
				console.log('ERROR: ' + response);
			}
		});
	}


	function displayEventList() {

		let mydata = {

		};

		$.ajax({
			url: 		"jquery/jq_event_display.php",
			method: 	"POST",
			dataType:	"text",
			data: 		JSON.stringify(mydata),
			success:	function(response)
			{
				$('#eventListDisplay').html(response);
			},
			error: function(response)
			{
				console.log('ERROR: ' + response);
			}
		});
	}

	function displayEventForm() {
		let mydata = {

		};

		$.ajax({
			url: "jquery/jq_event_form.php",
			method: "POST",
			dataType: "text",
			data: JSON.stringify(mydata),
			success: function(response) {
				$('#modalLabel').html('Submit Your Commissioner College!');
				$('#modalData').html(response);
			},
			error: function(response) {
				console.log('ERROR: ' + response);
			}
		});
	}

	function validateEntryForm() {
		$('.input_class').each(function(i, obj) {
			let thisID = $(this).attr('id');
			let thisReq = $(this).attr('required');
			if( thisReq == true )
			{
				console.log('thisREq: ' + thisReq + thisID);
			}
		});
	};

	function processEventForm(formData) {

		let eValidate = $('#e_validate').val();
		let eDate = $('#e_date').val();
		let eCouncil = $('#e_council_ID').val();
		let eVirtual = $('#e_virtual').val();
		let eLocation = $('#eLocation').val();
		let eTime = $('#e_time').val();
		let eCost = $('#e_cost').val();
		let eOfferings = $('#e_offerings').val();
		let eInfo = $('#e_info').val();
		let eUrl = $('#e_url').val();
		let eTimeStart = $('#e_time_start').val();
		let eTimeEnd = $('#e_time_end').val();

		let eSubmitName = $('#e_submit_name').val();
		let eSubmitEmail = $('#e_submit_email').val();

		let mydata = {
			eValidate:eValidate,
			eDate:eDate,
			eCouncil:eCouncil,
			eVirtual:eVirtual,
			eLocation:eLocation,
			eTime:eTime,
			eCost:eCost,
			eOfferings:eOfferings,
			eInfo:eInfo,
			eUrl:eUrl,
			eTimeStart:eTimeStart,
			eTimeEnd:eTimeEnd,
			eSubmitName:eSubmitName,
			eSubmitEmail:eSubmitEmail,
		}

		$.ajax({
			url: 		"jquery/jq_event_process.php",
			method: 	"POST",
			dataType:	"text",
			data: 		JSON.stringify(mydata),
			success:	function(response)
			{
				let trimResponse = response.trim();
				displayMessage(trimResponse,'eventFormAlert');
				refreshPage();
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

		displayWaitingList();
		displayEventList();

		$(document).ajaxComplete(function(e) {

		});
		validateEntryForm();

	}

	//? ===========>> document ready <<=============
	//? ===========>> document ready <<=============
	//? ===========>> document ready <<=============
	$(document).ready(function()
	{
		// clearSettings();

		refreshPage();
		$('.modal-dialog').removeClass('modal-xl');

		//! ===========>> LISTENERS
		//! ===========>> LISTENERS
		//! ===========>> LISTENERS

		//! ===========>> clear_all click ---  This clears all JS settings
		// $(document).on("mouseout", '.nav-link', function(e) {
		// 	clearAll();
		// });
		//! ===========>> clear_all click


		//! ===========>> displayWaitingListForm
		$(document).on("click", '#displayWaitingListForm', function(e) {
			$('.modal-dialog').addClass('modal-xl');
			displayWaitingListForm();
		});
		//! ===========>> displayWaitingListForm

		//! ===========>> displayEventForm
		$(document).on("click", '#displayEventForm', function(e) {
			$('.modal-dialog').addClass('modal-xl');
			displayEventForm();
		});
		//! ===========>> displayEventForm

	});
</script>

<?php  // ** Lampkin 2024 ** // ?>
<?php require "includes/footer.php"; ?>
