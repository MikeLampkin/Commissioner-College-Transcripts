<?php  // ** Lampkin 2024 ** // ?>

<?php
	$page_is_protected = 'no';
	$navshow = 'yes';

	$pg_title 		= "Commissioner College Transcripts";
    $pg_keywords 	= "transcripts, boy scout, commissioner, bsa, transcripts, commissioner college";
    $pg_description = "Transcripts for Commissioner College, BSA";

	$pg 		= 'transcripts';
	$db_table 	= 'transcripts';

	require "includes/header.php";

?>
<!-- <button id="clearAll" class="btn btn-danger btn-xs m-3">Clear TEST Search</button><br /> -->

<!-- // -- Lampkin 2010 - 2024 -- // -->
	<div class="row mb-3">
		<div class="col-4 text-start"><span id="changeCouncil" class="" data-bs-toggle="modal" data-bs-target="#modalAlert"><span data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Click to change your current council."><i class="fas fa-location-circle" aria-hidden="true"></i> Your current council: <strong><span id="showMyCouncil"></span></strong></span></div>
		<div class="col-4 text-center"><span id="alertMsg"></span></div>
		<div class="col-4 text-end"><span class="btn btn-primary btn-xs" id="reloadPage"><i class="fa-solid fa-arrows-rotate" id="reloadIcon"></i> Refresh Page</span></div>
	</div>

	<div id="searchForm" class="col-md-12 mb-3">
		<div class="col-md-6 my-3">
			<div class="m-2 p-0">
				<span class="h4 text-success"> Find Commissioner: </span>
			</div>
			<div class="mb-2">
				<div class="input-group">
					<div class="input-group-prepend">
						<button class="btn btn-success" id="search_submit" role="button" aria-label="submit" aria-labelledby="submit" > <i class="fas fa-search"></i> </button>
					</div>
					<input type="text" class="form-control" id="search_terms" name="search_terms" placeholder="Search">
					<div class="input-group-append">
						<button class="btn btn-danger" id="search_clear" role="button" aria-label="clear" aria-labelledby="clear"> <i class="fas fa-search-minus"></i></button>
					</div>
				</div>
			</div>
		</div>

	</div>
	<div id="userData" class="col-md-12"><h4> <i class="fa-solid fa-spinner fa-spin"></i> Searching...</h4></div>
	<div id="transcriptsData" class="col-md-12"><h4> <i class="fa-solid fa-spinner fa-spin"></i> Waiting...</h4></div>

<script>
let thisPage = 'transcripts';

const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
let today = new Date();
console.log('Today: ' +today);

let myCouncil = typeof(localStorage.getItem('myCouncil')) != "undefined" && localStorage.getItem('myCouncil') !== null ? localStorage.getItem('myCouncil') : 'undefined';
localStorage.setItem("myCouncil", myCouncil);

let searchTerms = typeof(localStorage.getItem('searchTerms')) != "undefined" && localStorage.getItem('searchTerms') !== null ? localStorage.getItem('searchTerms') : '';
localStorage.setItem("searchTerms", searchTerms);

let viewEmpty = typeof(localStorage.getItem('viewEmpty')) != "undefined" && localStorage.getItem('viewEmpty') !== null ? localStorage.getItem('viewEmpty') : 'yes';
localStorage.setItem("viewEmpty", viewEmpty);

let viewReq = typeof(localStorage.getItem('viewReq')) != "undefined" && localStorage.getItem('viewReq') !== null ? localStorage.getItem('viewReq') : 'bcs';
localStorage.setItem("viewReq", viewReq);

let selectUser = typeof(localStorage.getItem('selectUser')) != "undefined" && localStorage.getItem('selectUser') !== null ? localStorage.getItem('selectUser') : 0;
	localStorage.setItem("selectUser",selectUser);

function checkCouncil()
{
	let myCouncil = localStorage.getItem('myCouncil');
	if( myCouncil == 'undefined' )
	{
		$('#modalAlert').modal('show');
		$('#searchForm').hide();
		changeCouncil();
	}
}

function changeCouncil()
{
	let myCouncil = localStorage.getItem('myCouncil');

	let mydata = {
		myCouncil:myCouncil,
	};

	$.ajax({
		url: "jquery/jq_council_select_form.php",
		method: "POST",
		dataType: "text",
		data: JSON.stringify(mydata),
		success: function(response) {
			$('#modalLabel').html('Change Your Council');
			$('#modalData').html(response);
			$('#modalFooter').hide();
		},
		error: function(response) {
			console.log('ERROR: ' + response);
		}
	});
}

function displayCouncil()
{
	let myCouncil = localStorage.getItem('myCouncil');
	let mydata = {
		myCouncil:myCouncil,
	};

	$.ajax({
		url: "jquery/jq_council_select_display.php",
		method: "POST",
		dataType: "text",
		data: JSON.stringify(mydata),
		success: function(response) {
			let trimResponse = response.trim();
			let responseLen = trimResponse.length;
			if( responseLen > 4 )
			{
				$('#showMyCouncil').html(response);
				$('#navCouncil').html('['+response+']');
				$('#searchForm').show();
			}
			else
			{
				$('#showMyCouncil').html('<em><strong>None selected.</strong></em>');
			}

		},
		error: function(response) {
			console.log('ERROR: ' + response);
		}
	});
}

function displaySearchTerms()
{
	let myCouncil = localStorage.getItem('myCouncil');
	let searchTerms = localStorage.getItem('searchTerms');
	let selectUser = localStorage.getItem('selectUser');
	$('#search_terms').val(searchTerms);
}

function displayUsers()
{
	let myCouncil = localStorage.getItem('myCouncil');
	let searchTerms = localStorage.getItem('searchTerms');
	let selectUser = localStorage.getItem('selectUser');

	// console.log('displayUsers: ' +searchTerms);

	let mydata = {
		searchTerms:searchTerms,
		myCouncil:myCouncil,
		selectUser:selectUser,
		pgPage:thisPage,
		pgActive:pgActive,
	};

	$.ajax({
		url: "jquery/jq_transcripts_users_display.php",
		method: "POST",
		dataType: "text",
		data: JSON.stringify(mydata),
		success: function(response) {
			$('#userData').html(response);
		},
		error: function(response) {
			console.log('ERROR: ' + response);
		}
	});
}

function displayTranscripts()
{
	let myCouncil = localStorage.getItem('myCouncil');
	let selectUser = localStorage.getItem('selectUser');
	let viewEmpty = localStorage.getItem('viewEmpty');
	let viewReq = localStorage.getItem('viewReq');

	// if( selectUser > 0 ) { 	$('#displayTranscripts').show(); } else { $('#displayTranscripts').hide(); }

	let mydata = {
		myCouncil:myCouncil,
		selectUser:selectUser,
		viewEmpty:viewEmpty,
		viewReq:viewReq,
	};

	$.ajax({
		url: "jquery/jq_transcripts_display.php",
		method: "POST",
		dataType: "text",
		data: JSON.stringify(mydata),
		success: function(response) {
			$('#transcriptsData').show();
			$('#transcriptsData').html(response);
		},
		error: function(response) {
			console.log('ERROR: ' + response);
		}
	});
}


// function submitSearch(newSearchTerms)
// {
// 	console.log('ss newSearchTerms: ' +newSearchTerms);
// 	localStorage.removeItem('searchTerms');
// 	localStorage.setItem('searchTerms',newSearchTerms);
// 	$('#search_terms').val(newSearchTerms);
// 	displayUsers();
// }

function refreshPage()
{
	$('.tooltip').remove();
	refreshAjax();
	console.log('refreshing ====>');

	let myCouncil = localStorage.getItem('myCouncil');
	let searchTerms = localStorage.getItem('searchTerms');
	let selectUser = localStorage.getItem('selectUser');
	// console.log('refresh myCouncil: ' + myCouncil);
	// console.log('refresh searchTerms: ' + searchTerms);
	// console.log('refresh selectUser: ' + selectUser);


	// searchForm();
	checkCouncil();
	displayCouncil();

	displaySearchTerms();
	displayUsers();
	displayTranscripts();

	$(document).ajaxComplete(function(e)
	{
		// let totalCnt = $('#totalCnt').val();
		// if( totalCnt >= 1 )
		// {
		// 	$("#paginationDisplay").html(getPagination(totalCnt));
		// }
	});
}

//? ===========>> document ready <<=============
//? ===========>> document ready <<=============
//? ===========>> document ready <<=============
$(document).ready(function() {

	// displayForm();
	refreshPage();
	$('#transcriptsData').hide();

	//! ===========>> LISTENERS
	//! ===========>> LISTENERS
	//! ===========>> LISTENERS
	$(document).on('click', '#clearAll', function(e)
	{
		clearAll();
	});


	//! ===========>> changeMyCouncil
	$(document).on('click', '#changeCouncil', function(e)
	{
		changeCouncil();
	});
	//! ===========>> changeMyCouncil

	//! ===========>> changeMyCouncilClick
	$(document).on('click', '.council-change-btn', function(e)
	{
		localStorage.removeItem("myCouncil");
		let thisData = $(this).data('info');
		localStorage.setItem("myCouncil",thisData);
		refreshPage();
	});
	//! ===========>> changeMyCouncilClick


	//! =====>> ClearSearch
	$(document).on('click', '#search_clear', function(e)
	{
		localStorage.removeItem('searchTerms');
		// localStorage.setItem('searchTerms','');
		localStorage.removeItem('selectUser');
		// localStorage.setItem('selectUser','');
		refreshPage();
	});
	//! =====>> ClearSearch

	//! ===========>> SEARCH
	$(document).on('click', '#search_submit', function(e)
	{
		let newSearchTerms = $('#search_terms').val();
		localStorage.removeItem('searchTerms');
		localStorage.setItem('searchTerms',newSearchTerms);
		displayUsers();
		// submitSearch(newSearchTerms);
	});
	//! ===========>> SEARCH

	//! ===========>> selectUser
	$(document).on('click', '.select-user', function(e)
	{
		localStorage.removeItem("selectUser");
		let thisData = $(this).data('info');
		localStorage.setItem("selectUser",thisData);
		refreshPage();
	});
	//! ===========>> selectUser


	$(document).on('click', '#viewEmpty', function(e)
	{
		e.preventDefault();
		let viewEmptyStatus = $(this).is(":checked");
		viewEmpty = ( viewEmptyStatus == true ) ? "yes" : "no";
		localStorage.setItem('viewEmpty', viewEmpty);
		$(this).addClass('hide');
		refreshPage();
	});

	$(document).on("click", '.view_req', function(e)
	{
		e.preventDefault();
		viewReq = $(this).data('view');
		$(this).addClass('active');
		localStorage.setItem('viewReq', viewReq);
		refreshPage();
	});

});
</script>

<?php  // ** Lampkin 2024 ** // ?>
<?php require "includes/footer.php"; ?>
