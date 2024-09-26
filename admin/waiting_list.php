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

	const selectors_array = {
		'deceased' : 'user_deceased',
		'status' : 'user_status',
		'data' : 'user_active'
	};

	let searchTerms = typeof(localStorage.getItem('searchTerms')) != "undefined" && localStorage.getItem('searchTerms') != null ? localStorage.getItem('searchTerms') : '';
	localStorage.setItem("searchTerms",searchTerms);
	$('#search_terms').val(searchTerms);

	let deceasedSelect = typeof(localStorage.getItem('deceasedSelect')) != "undefined" && localStorage.getItem('deceasedSelect') != null ? localStorage.getItem('deceasedSelect') : 'no';
	localStorage.setItem("deceasedSelect",deceasedSelect);

	let statusSelect = typeof(localStorage.getItem('statusSelect')) != "undefined" && localStorage.getItem('statusSelect') != null ? localStorage.getItem('statusSelect') : 'active';
	localStorage.setItem("statusSelect",statusSelect);

	let dataSelect = typeof(localStorage.getItem('dataSelect')) != "undefined" && localStorage.getItem('dataSelect') != null ? localStorage.getItem('dataSelect') : 'yes';
	localStorage.setItem("dataSelect",dataSelect);

	function displaySearchTerms() {
		let searchTerms = localStorage.getItem('searchTerms') ?? '';
		$('#search_terms').val(searchTerms);
	}

	function getSelector(selectField='user_active',selectTerm='data',dbTable='waiting_list',vars='')
	{
		let sessionVal = vars;
		let tooltip = $('#'+selectTerm+'SelectForm').data('tooltip');

		let mydata = {
			adminUser:adminUser,
			adminCouncilSelect:adminCouncilSelect,
			dbTable:dbTable,
			selectField:selectField,
			selectTerm:selectTerm,
			sessionVal:sessionVal,
			tooltip:tooltip,
		};

		$.ajax({
			url: 		"jquery/jq_app_selectors.php",
			method: 	"POST",
			dataType:	"text",
			data: 		JSON.stringify(mydata),
			success:	function(response)
			{
				$('#'+ selectTerm + 'SelectForm').html(response);
				$('#'+ selectTerm + 'SelectForm').removeClass('filter-hilite');
				if( sessionVal.length > 1 && sessionVal !== 'all' )
				{
					$('#' + selectTerm + 'SelectForm').addClass('filter-hilite');
				}
			},
			error: function(response)
			{
				console.log('ERROR: ' + response);
			}
		});
	}

	function refreshSelectors()
	{
		for (const [key, value] of Object.entries(selectors_array))
		{
			let thisSelect = key + 'Select';
			getSelector(value,key,thisTable,localStorage.getItem(thisSelect));

		}
	}

	function highlightFilterBtn()
	{
		$('#filters').removeClass('active');
		$('#filters_alert').removeClass('position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger');
		let hilite = 'no';
		let x=0;

		for (const [key, value] of Object.entries(selectors_array))
		{
			let thisSelect = key + 'Select';
			if( localStorage.getItem(thisSelect) != null && localStorage.getItem(thisSelect) != 'all' )
			{
				hilite = 'yes';
				x++;
			}
		}

		if( hilite == 'yes' )
		{
			$('#filters').addClass('active');
			$('#filters_alert').addClass('position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger');
			$('#filters_alert').html(x);
		}
	}

	function getList() {
		let marker = Math.floor(randomNumber(0, 255));
		let searchTerms = localStorage.getItem('searchTerms');
		// let pgActive = localStorage.getItem('pgActive');
		let limitNum = localStorage.getItem('limitNum');
		let pgNum = localStorage.getItem('pgNum');
		let pgSort = localStorage.getItem('pgSort');

		let dataSelect = localStorage.getItem('dataSelect');
		let statusSelect = localStorage.getItem('statusSelect');
		let deceasedSelect = localStorage.getItem('deceasedSelect');
		let adminCouncilSelect = localStorage.getItem('adminCouncilSelect');

		let mydata = {
			adminUser:adminUser,
			adminCouncilSelect:adminCouncilSelect,
			searchTerms:searchTerms,
			dataSelect:dataSelect,
			statusSelect:statusSelect,
			deceasedSelect:deceasedSelect,
			pgActive:pgActive,
			limitNum:limitNum,
			pgNum:pgNum,
			pgSort:pgSort,
			thisPage:thisPage,

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

	function displayEntryForm(thisID) {
		let marker = Math.floor(randomNumber(0, 255));

		let adminCouncilSelect = localStorage.getItem('adminCouncilSelect');

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
					$('#modalLabel').html('Add New Participant ');
				} else {
					$('#modalLabel').html('Modify This Participant');
				}
				$('#modalData').html(response);
				$('#modalFooter').html(modalButtonSave+ ' ' +modalButtonClose);
			},
			error: function(response) {
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

		highlightFilterBtn();
		refreshSelectors();
		getList();

		limitAmt(['10','25','50','100','ALL']);
		$(document).ajaxComplete(function(e) {
			displaySearchTerms();
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

		//! ===========>> SEARCH
		$(document).on("click", '#search_submit', function(e) {
			e.preventDefault();
			localStorage.removeItem('searchTerms');
			let searchTerms = $('#search_terms').val();
			localStorage.setItem('searchTerms', searchTerms);
			localStorage.removeItem('pgNum');
			localStorage.setItem('pgNum', 1);
			refreshPage();
		});
		//! ===========>> SEARCH

		//! ===========>> clearBtn --- This clears ONLY the search settings
		$(document).on("click", '#search_clear', function(e) {
			localStorage.removeItem('searchTerms');
			localStorage.removeItem('pgNum');
			localStorage.setItem('pgNum', 1);
			refreshPage();
		});
		//! ===========>> clearBtn

		//! ===========>> SORT
		$(document).on("click", '.sort-table', function(e) {
			localStorage.removeItem("pgSort");
			let thisData = $(this).data('sort');
			localStorage.setItem("pgSort", thisData);
			refreshPage();
		});
		//! ===========>> SORT

		//! ===========>> LIMIT
		$(document).on("click", '.limit-btn', function(e) {
			localStorage.removeItem("limitNum");
			let thisData = $(this).data('limit');
			localStorage.setItem("limitNum", thisData);
			refreshPage();
		});
		//! ===========>> LIMIT

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

		//! ===========>> selector
		$(document).on("change", '.selector-action', function(e) {
			let fieldName = $(this).data('field');
			let fieldValue = $(this).val();
			let fieldNameVar = fieldName + 'Select';
			// updateBtnValues(fieldName,fieldValue,fieldId);
			localStorage.removeItem(fieldNameVar);
			localStorage.setItem(fieldNameVar,fieldValue);
			localStorage.setItem('pgNum',1);
			refreshPage();
		});
		//! ===========>> selector


		//! ===========>> caret
		$(document).on("click", '.card-header', function(e) {
			let thisID = $(this).data('bs-target').replace('#','');
			// console.log('thisID: ' +thisID);
			let newTarget = 'caret_' + thisID;
			// console.log('newTarget: ' +newTarget);

			let thisInfo = $('#'+thisID).attr('class');
			// console.log('thisInfo: ' +thisInfo);

			if( $('#'+thisID ).hasClass('show') )
			{
				console.log('SHOWING');
				$('#'+newTarget).addClass('text-danger');

			}
		});
		//! ===========>> caret

	});
</script>

<?php  // ** Lampkin 2024 ** // ?>
<?php require 'includes/footer.php'; ?>
