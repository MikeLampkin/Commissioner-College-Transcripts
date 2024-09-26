<?php  // ** Lampkin 2024 ** // ?>

<?php
	$page_is_protected = 'yes';
	$navshow 		= 'yes';

	$pg_title 		= 'Thesis Library';
	$pg_keywords 	= '';
	$pg_description = '';

	$pg 			= 'thesis';
	$db_table 		= 'thesis';

	require 'includes/header.php';

?>

<div class="alert alert-secondary row">
	<div class="col-md-3 text-start">
		<span id="addItem" data-info="" class="btn btn-purple text-white btn-sm edit-item" data-bs-toggle="modal" data-bs-target="#modalAlert"><i class="fa-solid fa-circle-plus"></i> Add New Thesis</span>
		<span class="btn btn-primary btn-sm" id="reloadPage"><i class="fa-solid fa-arrows-rotate" id="reloadIcon"></i> Refresh Page</span>
		<!-- <span id="viewActiveBtns"></span> -->
	</div>
	<div class="col-md-9" id="workSpace"></div>
	<!-- <div class="col-md-3 text-end"><span id="limitAmount"></span></div> -->
</div>

<div class="row">
	<div id="" class="col-md-2"></div>
	<!-- <div id="paginationDisplay" class="col-md-8 pagination_display"></div> -->
	<!-- <div id="" class="col-md-2 text-end"><button class="btn btn-outline-secondary position-relative" id="filters" type="button" data-bs-toggle="collapse" data-bs-target="#filterList" aria-expanded="false" aria-controls="filterList"><i class="fa-solid fa-bars-filter"></i> Filters <span id="filters_alert"></span></button></div> -->
</div>

<div id="displayResults" class="col-md-12">
	<h4> <i class="fa-solid fa-spinner fa-spin"></i> Thinking...</h4>
</div>

<script>
	let thisPage = 'thesis';
	let thisTable = 'thesis';

	const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
	let today = new Date();
	console.log('Today: ' + today);

	function displaysearchTerms() {
		let itemSelect = localStorage.getItem('itemSelect') ?? '';
		$('#search_terms').val(itemSelect);
	}

	let activeSelect = 'yes';

	function getList() {
		let pgActive = localStorage.getItem('pgActive');
		let adminCouncilSelect = localStorage.getItem('adminCouncilSelect');

		let mydata = {

			adminCouncilSelect:adminCouncilSelect,
			pgActive:pgActive,
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
					$('#modalLabel').html('Add New Thesis ');
				} else {
					$('#modalLabel').html('Modify This Thesis');
				}
				$('#modalData').html(response);
				$('#modalFooter').html(modalButtonSave+ ' ' +modalButtonClose);
			},
			error: function(response) {
				console.log('ERROR: ' + response);
			}
		});
	}

	function displayUploadForm(thisID,thisMsg)
	{
		let cleanMsg = decodeURIComponent(thisMsg.replace(/\+/g, ' '));
		let marker = Math.floor(randomNumber(0, 255));

		let mydata = {
			thisID:thisID,
			adminUser:adminUser,
			adminCouncilSelect:adminCouncilSelect,
		};

		$.ajax({
			url: "jquery/jq_" + thisPage + "_upload.php?"+ marker,
			method: "POST",
			dataType: "text",
			data: JSON.stringify(mydata),
			success: function(response) {
				// $('#modalLabel').html('Upload Thesis File for <em>' + cleanMsg + '</em>');
				// $('#modalData').html(response);
				// $('#modalFooter').hide();
				$('#workSpace').addClass('bg-yellow');
				$('#workSpace').html(response);
			},
			error: function(response) {
				console.log('ERROR: ' + response);
			}
		});
	}

	function processUpload() {
		let adminUser = $('#adminUser').val();

		let thisID 		= $('#thesis_ID').val();
		let thisCouncilID 	= $('#thesis_council_ID').val();
		let thisFile 	= $('#thesis_file').val();

		let testLen = thisCouncilID.length;
		if( testLen > 0 )
		{
			var formData = new FormData();
				formData.append('thesis_ID', thisID);
				formData.append('thesis_council_ID', thisCouncilID);
				formData.append('upload_file', thisFile);
				formData.append('file', $('#upload_file')[0].files[0]);
				formData.append('file_name', thisFile);

			$.ajax({
				url: 		"jquery/jq_thesis_upload_process.php",
				method: 	"POST",
				dataType:	"text",
				contentType: false,
				processData: false,
				data: 		formData,
				success:	function(response)
				{
					// $('#dataDump').html(response);
					let trimResponse = response.trim();
					if( trimResponse == 'success')
					{
						$('#workSpace').removeClass('bg-yellow');
						$('#workSpace').html(' ');
						toastMessage(trimResponse,'');
					}
					else
					{
						$('#workSpace').removeClass('bg-yellow');
						$('#workSpace').html(' ');
						toastMessage(trimResponse,response);
					}
					refreshPage();
				},
				error: function(response)
				{
					console.log('ERROR: ' + response);
				}
			});
		}
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


		//! ===========>> upload_file
		$(document).on("click", '.upload-item', function(e) {
			$('.tooltip').remove();
			let thisID = $(this).data('info');
			let thisMsg = $(this).data('msg');
			console.log(thisID);
			window.scrollTo(0, 0);
			displayUploadForm(thisID,thisMsg);
		});
		//! ===========>> upload_ID

		//! ===========>> upload_ID
		$(document).on("click", '#upload_submit', function(e) {
			e.preventDefault();
			console.log('=>>> POST <<<=');
			processUpload();
		});
		//! ===========>> upload_ID

		$(document).ajaxComplete(function(e) {
			//! ===========>> LOWERCASE USER EMAIL  <<================
			$('#thesis_email').keyup(function(){
				$(this).val($(this).val().toLowerCase());
			});
			//! ===========>> LOWERCASE USER EMAIL  <<================
		});


	});
</script>


<?php  // ** Lampkin 2023 ** // ?>
<?php require "includes/footer.php"; ?>
