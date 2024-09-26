<?php  // ** Lampkin 2024 ** // ?>

<?php
	$page_is_protected = 'no';
	$navshow = 'yes';

	$pg_title 		= "Course List";
    $pg_keywords 	= "course list, boy scout, commissioner, bsa, transcripts, commissioner college";
    $pg_description = "Course List for Commissioner College, BSA";

	$pg 		= 'courses';
	$db_table 	= 'transcripts';

	require "includes/header.php";

?>

<div id="displayResults" class="col-md-12">
	<h4> <i class="fa-solid fa-spinner fa-spin"></i> Thinking...</h4>
</div>


<script>
	let thisPage = 'courses';
	let thisTable = 'courses';

	const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
	let today = new Date();
	console.log('Today: ' +today);

	let myCouncil = typeof(localStorage.getItem('myCouncil')) != "undefined" && localStorage.getItem('myCouncil') !== null ? localStorage.getItem('myCouncil') : 'undefined';
	localStorage.setItem("myCouncil", myCouncil);
	console.log(myCouncil);

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


	function getList() {
		let marker = Math.floor(randomNumber(0, 255));
		let myCouncil = localStorage.getItem('myCouncil');

		let mydata = {
			myCouncil:myCouncil,
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

	function refreshPage()
	{
		$('.tooltip').remove();
		refreshAjax();
		console.log('refreshing ====>');
		console.log(myCouncil);

		getList();
		checkCouncil();
		displayCouncil();

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


	});
</script>

<?php  // ** Lampkin 2024 ** // ?>
<?php require "includes/footer.php"; ?>
