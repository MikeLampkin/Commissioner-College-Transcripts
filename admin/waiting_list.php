<?php  // ** Lampkin 2024 ** // ?>

<?php
	$page_is_protected = 'yes';
	$navshow 		= 'yes';

	$pg_title 		= 'Jedi: Waiting List';
	$pg_keywords 	= '';
	$pg_description = '';

	$pg 			= 'waiting_list';
	$db_table 		= 'waiting_list';

	require 'includes/header.php';

?>

<div class="alert alert-secondary row">
	<div class="col-md-3 text-start">
		<!-- <span id="addItem" data-info="" class="btn btn-purple text-white btn-sm edit-item" data-bs-toggle="modal" data-bs-target="#modalAlert"><i class="fa-solid fa-circle-plus"></i> Add New Participant</span> -->
		<span class="btn btn-primary btn-sm" id="reloadPage"><i class="fa-solid fa-arrows-rotate" id="reloadIcon"></i> Refresh Page</span>
	</div>
	<div class="col-md-6">
		<!-- <form>
			<div class="input-group mb-1">
				<input type="text" class="form-control my-0 border" id="search_terms" name="search_terms" placeholder="Search anything.">
				<button id="search_submit" class="btn btn-success" ><i class="fa-solid fa-search" aria-hidden="true"></i> Search </button>
				<span id="search_clear" class="btn btn-danger clear-search"><i class="fa-solid fa-search-minus" aria-hidden="true"></i></span>
			</div>
			<small><small>Put a comma between separate search terms.</small></small>
		</form> -->
	</div>
	<div id="limitAmount" class="col-md-3 text-end"></div>
</div>

<div id="displayResults" class="col-md-12">
	<h4> <i class="fa-solid fa-spinner fa-spin"></i> Thinking...</h4>
</div>

<div class="row">
	<div id="" class="col-md-2"></div>
	<div id="paginationDisplay2" class="col-md-8 pagination_display"></div>
	<div id="" class="col-md-2"></div>
</div>

<script>
	let thisPage = 'waiting_list';
	let thisTable = 'waiting_list';

	const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
	let today = new Date();

	function getList() {
		let marker = Math.floor(randomNumber(0, 255));
		// let pgActive = localStorage.getItem('pgActive');
		// let adminUser = $('#adminUser').val();

		let mydata = {
			adminUser:adminUser,
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


	function quickSubmit(parameters)
	{
		let marker = Math.floor(randomNumber(0, 255));
		var form = $('<form id="quickSubmit' + marker + '"></form>');
			form.attr("method", "post");
			$.each(parameters, function(key, value) {
				var field = $('<input></input>');
				field.attr("type", "hidden");
				field.attr("name", key);
				field.attr("value", value);
				form.append(field);
			});
			$(document.body).append(form);
			processForm('quickSubmit' + marker);
	}

	function refreshPage() {
		refreshAjax();
		console.log('refreshing ====>');
		$('.tooltip').remove();
		$('.modal-dialog').removeClass('modal-xl');

		getList();

		$(document).ajaxComplete(function(e) {
			let totalCnt = $('#totalCnt').val();
			if( totalCnt )
			{
				$(".pagination_display").html(getPagination(totalCnt));
			}
		});
	}

	//? ===========>> document ready <<=============
	//? ===========>> document ready <<=============
	//? ===========>> document ready <<=============
	$(document).ready(function()
	{
		refreshPage();

		$('#user_email').keyup(function(){
			$(this).val($(this).val().toLowerCase());
		});

		//! ===========>> editItem
		$(document).on("click", '.edit-item', function(e) {
			$('.tooltip').remove();
			$('.modal-dialog').addClass('modal-xl');
			let thisID = $(this).data('info');
			displayEntryForm(thisID);
		});
		//! ===========>> editItem

		//! ===========>> action
		$(document).on("click", '.action-item', function(e) {
			let thisID = $(this).data('info');
			let thisIDField = $(this).data('idfield');
			let thisField = $(this).data('field');
			let thisValue = $(this).data('value');

			console.log('action item');
				let parameters = {
					'process' 	: 'yes',
					'id_field' 	: 'user_ID',
					'db_table' 	: 'waiting_list',
					'action' 	: 'update',
					'user_ID' 	: thisID,
					[thisField] : thisValue
				};
				quickSubmit(parameters);

			if( thisIDField == 'deceased' && thisValue == 'yes' )
			{
				let oppValue = thisValue == 'yes' ? 'no' : 'yes';
				let newparameters = {
					'process' 	: 'yes',
					'id_field' 	: 'user_ID',
					'db_table' 	: 'waiting_list',
					'action' 	: 'update',
					'user_ID' 	: thisID,
					'user_status' : 'inactive'
				};
				quickSubmit(newparameters);
			}
		});
		//! ===========>> action

	});
</script>

<?php  // ** Lampkin 2024 ** // ?>
<?php require 'includes/footer.php'; ?>
