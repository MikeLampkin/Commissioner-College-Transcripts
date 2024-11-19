<?php  // ** Lampkin 2024 ** // ?>

<?php
	$page_is_protected 	= 'no';
	$navshow 			= 'yes';

	$pg_title 		= "Degree List";
    $pg_keywords 	= "degree list, boy scout, commissioner, bsa, transcripts, commissioner college";
    $pg_description = "Degree List for Commissioner College, BSA";

	$pg 		= 'degrees';
	$db_table 	= 'transcripts';

	require "includes/header.php";
?>
<style>
	#degree-bar .degree-nav {
		color: black!important;
		font-weight: 900;
	}
	#degree-bar .degree-nav:hover {
		color: red;
	}
	#degree-bar .degree-nav.active {
		background-color: #eee;
	}
</style>
<!-- // -- Lampkin 2010 - 2024 -- // -->
<div class="row mb-3">
	<div class="col-4 text-start"><span id="changeCouncil" class="" data-bs-toggle="modal" data-bs-target="#modalAlert"><span data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Click to change your current council."><i class="fas fa-location-circle" aria-hidden="true"></i> Your current council: <strong><span id="showMyCouncil"></span></strong></span></div>
	<div class="col-4 text-center"><span id="alertMsg"></span></div>
	<div class="col-4 text-end"><span class="btn btn-primary btn-xs" id="reloadPage"><i class="fa-solid fa-arrows-rotate" id="reloadIcon"></i> Refresh Page</span></div>
</div>

<ul class="nav nav-tabs" id="degree-bar">
	<?php
	$x=0;
foreach( $degree_award_array AS $award_key => $award_term )
{
	echo '
	<li class="nav-item">
		<span class="nav-link degree-nav" data-info="' . $award_key . '" id="' . $award_key . '" >' . $award_term . '</span>
	</li>
	';
	$x++;
}
	?>
</ul>

<div id="displayResults" class="col-md-12">
	<h4> <i class="fa-solid fa-spinner fa-spin"></i> Thinking...</h4>
</div>

<script>
	let thisPage = 'degrees';
	let thisTable = 'degrees';

	const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
	let today = new Date();
	console.log('Today: ' + today);

	let myCouncil = typeof(localStorage.getItem('myCouncil')) != "undefined" && localStorage.getItem('myCouncil') !== null ? localStorage.getItem('myCouncil') : 'undefined';
	localStorage.setItem("myCouncil", myCouncil);

	let selectDegree = typeof(sessionStorage.getItem('selectDegree')) != "undefined" && sessionStorage.getItem('selectDegree') !== null ? sessionStorage.getItem('selectDegree') : 'bcs';
	sessionStorage.setItem("selectDegree", selectDegree);

	function checkCouncil() {
		let myCouncil = localStorage.getItem('myCouncil');
		if (myCouncil == 'undefined') {
			$('#modalAlert').modal('show');
			$('#searchForm').hide();
			changeCouncil();
		}
	}

	function changeCouncil() {
		let myCouncil = localStorage.getItem('myCouncil');

		let mydata = {
			myCouncil: myCouncil,
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

	function displayCouncil() {
		let myCouncil = localStorage.getItem('myCouncil');
		let mydata = {
			myCouncil: myCouncil,
		};

		$.ajax({
			url: "jquery/jq_council_select_display.php",
			method: "POST",
			dataType: "text",
			data: JSON.stringify(mydata),
			success: function(response) {
				let trimResponse = response.trim();
				let responseLen = trimResponse.length;
				if (responseLen > 4) {
					$('#showMyCouncil').html(response);
					$('#navCouncil').html('[' + response + ']');
					$('#searchForm').show();
				} else {
					$('#showMyCouncil').html('<em><strong>None selected.</strong></em>');
				}

			},
			error: function(response) {
				console.log('ERROR: ' + response);
			}
		});
	}

	function activeDegreeNav() {
		let selectDegree = sessionStorage.getItem('selectDegree');
		$('.degree-nav').removeClass('active');
		$('#'+selectDegree).addClass('active');
	}

	function getList() {
		let marker = Math.floor(randomNumber(0, 255));
		let myCouncil = localStorage.getItem('myCouncil');
		let selectDegree = sessionStorage.getItem('selectDegree');
		let mydata = {
			myCouncil: myCouncil,
			selectDegree: selectDegree,
		};

		$.ajax({
			url: "jquery/jq_" + thisPage + "_display.php?" + marker,
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
		$('.tooltip').remove();
		refreshAjax();
		console.log('refreshing ====>');

		checkCouncil();
		displayCouncil();
		getList();
		activeDegreeNav();

		$(document).ajaxComplete(function(e) {
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

		//! ===========>> LISTENERS
		//! ===========>> LISTENERS
		//! ===========>> LISTENERS
		$(document).on('click', '#clearAll', function(e) {
			clearAll();
		});


		//! ===========>> changeMyCouncil
		$(document).on('click', '#changeCouncil', function(e) {
			changeCouncil();
		});
		//! ===========>> changeMyCouncil

		//! ===========>> changeMyCouncilClick
		$(document).on('click', '.council-change-btn', function(e) {
			localStorage.removeItem("myCouncil");
			let thisData = $(this).data('info');
			localStorage.setItem("myCouncil", thisData);
			refreshPage();
		});
		//! ===========>> changeMyCouncilClick

		//! ===========>> degree-nav
		$(document).on('click', '.degree-nav', function(e) {
			sessionStorage.removeItem("selectDegree");
			let thisData = $(this).data('info');
			sessionStorage.setItem("selectDegree", thisData);
			refreshPage();
		});
		//! ===========>> changeMyCouncilClick


	});
</script>

<?php  // ** Lampkin 2024 ** //
?>
<?php require "includes/footer.php"; ?>
