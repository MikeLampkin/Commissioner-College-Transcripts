<?php  // ** Lampkin 2024 ** // ?>

<?php
	$page_is_protected = 'yes';
	$navshow 		= 'yes';

	$pg_title 		= 'Core: Deans';
	$pg_keywords 	= '';
	$pg_description = '';

	$pg 			= 'deans';
	$db_table 		= 'deans';

	require 'includes/header.php';

?>

<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-6" id="alertMsg"></div>
	<div class="col-md-3"></div>
</div>

<div class="alert alert-secondary row">
	<div id="refreshDisplay" class="col-md-3 text-start">
		<span class="btn btn-primary btn-sm" id="reloadPage"><i class="fa-solid fa-arrows-rotate" id="reloadIcon"></i> Refresh Page</span>
	</div>
</div>

<div class="row mb-3">
	<div id="" class="col-md-2 text-start"><button class="btn btn-outline-secondary btn-sm position-relative" id="filters" type="button" data-bs-toggle="collapse" data-bs-target="#filterList" aria-expanded="false" aria-controls="filterList"><i class="fa-solid fa-bars-filter"></i> Filters <span id="filters_alert"></span></button></div>
	<div id="paginationDisplay" class="col-md-8 pagination_display"></div>
	<div id="" class="col-md-2 text-end">
	</div>
</div>

<div class="collapse my-3 col-12" id="filterList">
	<div class="card card-body border shadow">
		<h4>Filters </h4>
		<!-- <span class="btn btn-xs btn-outline-danger" id="clear_all">Clear All </span> -->
		<div class="row">

			<!-- <div class="col-md-3 selector_box">
				<div class="selectors" id="statusSelectForm" class="selector" data-field="user_status" data-term="status" data-tooltip="Registration status"></div>
			</div> -->
			<!-- <div class="col-md-3 selector_box">
				<div class="selectors" id="deceasedSelectForm" class="selector" data-field="user_deceased" data-term="deceased" data-tooltip="Deceased or living"></div>
			</div> -->
			<div class="col-md-3 selector_box">
				<div class="selectors" id="councilSelectForm" class="selector" data-field="user_council_ID" data-term="council" data-tooltip="Current Council"></div>
			</div>
			<!-- <div class="col-md-3 selector_box">
				<div class="selectors" id="activeSelectForm" class="selector" data-field="user_active" data-term="active" data-tooltip="Database listing"></div>
			</div> -->

		</div>
	</div>
</div>

<div id="displayResults" class="col-md-12">
	<h4> <i class="fa-solid fa-spinner fa-spin"></i> Thinking...</h4>
</div>

<script>
	let thisPage = 'deans';
	let thisTable = 'deans';

	const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
	let today = new Date();

	const adminUser = $('#adminUser').val();
	const adminCouncilID = $('#adminCouncilID').val();

	const selectors_array = {
		'council' : 'user_council_ID',
	};

	localStorage.setItem("adminUser", adminUser);
	localStorage.setItem("adminCouncilID", adminCouncilID);

	// let searchTerms = typeof(localStorage.getItem('searchTerms')) != "undefined" && localStorage.getItem('searchTerms') != null ? localStorage.getItem('searchTerms') : '';
	// localStorage.setItem("searchTerms",searchTerms);
	// $('#search_terms').val(searchTerms);

	let councilSelect = typeof(localStorage.getItem('councilSelect')) != "undefined" && localStorage.getItem('councilSelect') != null ? localStorage.getItem('councilSelect') : '';
	localStorage.setItem("councilSelect",councilSelect);


	function displaySearchTerms() {
		let searchTerms = localStorage.getItem('searchTerms') ?? '';
		$('#search_terms').val(searchTerms);
	}

	function getSelector(selectField='course_active',selectTerm='active',dbTable='deans',vars='')
	{
		let sessionVal = vars;

		let tooltip = $('#'+selectTerm+'SelectForm').data('tooltip');

		let mydata = {
			adminUser:adminUser,
			adminCouncilID:adminCouncilID,
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
			// $('#filters_alert').addClass('position-absolute top-0 start-100 text-white xs translate-middle p-2 bg-danger border border-light rounded-circle text-');
			$('#filters_alert').addClass('position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger');
			$('#filters_alert').html(x);
		}
	}

	function displayEntryForm(thisID) {
		let marker = Math.floor(randomNumber(0, 255));

		let councilSelect = localStorage.getItem('councilSelect');

		let mydata = {
			thisID:thisID,
			adminUser:adminUser,
			adminCouncilID:adminCouncilID,
			councilSelect:councilSelect,
		};

		$.ajax({
			url: "jquery/jq_" + thisPage + "_form.php?"+ marker,
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
		refreshAjax();
		console.log('refreshing ====>');
		$('.tooltip').remove();
		$('.modal-dialog').removeClass('modal-xl');

		highlightFilterBtn();
		refreshSelectors();
		displayEntryForm();

		limitAmt(['10','25','50','100','ALL']);
		$(document).ajaxComplete(function(e) {
			// displaySearchTerms();
			// let totalCnt = $('#totalCnt').val();
			// if( totalCnt )
			// {
			// 	$(".pagination_display").html(getPagination(totalCnt));
			// }
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

	});
</script>


<?php  // ** Lampkin 2024 ** // ?>
<?php require 'includes/footer.php'; ?>
