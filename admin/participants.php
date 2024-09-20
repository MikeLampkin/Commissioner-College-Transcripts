<?php  // ** Lampkin 2024 ** // ?>

<?php
	$page_is_protected = 'yes';
	$navshow 		= 'yes';

	$pg_title 		= 'Core: Participants';
	$pg_keywords 	= '';
	$pg_description = '';

	$pg 			= 'participants';
	$db_table 		= 'users';

	require 'includes/header.php';

?>

<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-6" id="alertMsg"></div>
	<div class="col-md-3"></div>
</div>

<div class="row mb-3">
	<div id="" class="col-md-2"><span id="addItem" data-info="" class="btn btn-primary btn-sm edit-item" data-bs-toggle="modal" data-bs-target="#modalAlert"><i class="fa-solid fa-circle-plus"></i> Add New Participant</span></div>
</div>

<div class="alert alert-secondary row">
	<div class="col-md-3 text-start">
		<span class="btn btn-primary btn-sm" id="reloadPage"><i class="fa-solid fa-arrows-rotate" id="reloadIcon"></i> Refresh Page</span>
	</div>
	<div class="col-md-6">
	<form>
			<div class="input-group mb-1">
				<input type="text" class="form-control my-0 border" id="search_terms" name="search_terms" placeholder="Search anything.">
				<button id="search_submit" class="btn btn-success" ><i class="fa-solid fa-search" aria-hidden="true"></i> Search </button>
				<span id="search_clear" class="btn btn-danger clear-search"><i class="fa-solid fa-search-minus" aria-hidden="true"></i></span>
			</div>
			<small><small>Put a comma between separate search terms.</small></small>
			</form>
	</div>
	<div id="limitAmount" class="col-md-3 text-end"></div>
</div>

<div class="row">
	<div id="" class="col-md-2 text-start"><button class="btn btn-outline-secondary btn-sm position-relative" id="filters" type="button" data-bs-toggle="collapse" data-bs-target="#filterList" aria-expanded="false" aria-controls="filterList"><i class="fa-solid fa-bars-filter"></i> Filters <span id="filters_alert"></span></button></div>
	<div id="paginationDisplay" class="col-md-8 pagination_display"></div>
	<div id="" class="col-md-2 text-end">
		<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Copy and Paste into Excel">
			<button type="button" class="btn btn-outline-secondary btn-xs copy-btn" data-info="copyToClipboard"><i class="fa-solid fa-copy"></i> Copy Page</button>
			<!-- <button type="button" class="btn btn-outline-secondary btn-xs"><i class="fa-solid fa-book-copy"></i> Copy Entire Report</button> -->
		</span>
	</div>
</div>

<div class="collapse my-3 col-12" id="filterList">
	<div class="card card-body border shadow">
		<h4>Filters </h4>
		<!-- <span class="btn btn-xs btn-outline-danger" id="clear_all">Clear All </span> -->
		<div class="row">

			<div class="col-md-3 selector_box">
				<div class="selectors" id="statusSelectForm" class="selector" data-field="user_status" data-term="status" data-tooltip="Registration Status"></div>
			</div>
			<div class="col-md-3 selector_box">
				<div class="selectors" id="deceasedSelectForm" class="selector" data-field="user_deceased" data-term="deceased" data-tooltip="Deceased or Living"></div>
			</div>
			<div class="col-md-3 selector_box">
				<div class="selectors" id="dataSelectForm" class="selector" data-field="user_active" data-term="active" data-tooltip="Live Data"></div>
			</div>

		</div>
	</div>
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
	let thisPage = 'participants';
	let thisTable = 'users';

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

	function getSelector(selectField='user_active',selectTerm='data',dbTable='users',vars='')
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
					'db_table' 	: 'users',
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
					'db_table' 	: 'users',
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
