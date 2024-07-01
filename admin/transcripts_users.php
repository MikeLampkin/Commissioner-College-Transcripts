<?php  // ** Lampkin 2024 ** // ?>

<?php
	$page_is_protected = 'yes';
	$navshow 		= 'yes';

	$pg_title 		= 'Transcripts: By Participant';
	$pg_keywords 	= '';
	$pg_description = '';

	$pg 			= 'transcripts_users';
	$db_table 		= 'transcripts';

	require 'includes/header.php';

?>

<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-6" id="alertMsg"></div>
	<div class="col-md-3"></div>
</div>

<div class="row mb-3">
	<div id="" class="col-md-2 text-start"><button class="btn btn-outline-secondary btn-sm position-relative" id="filters" type="button" data-bs-toggle="collapse" data-bs-target="#filterList" aria-expanded="false" aria-controls="filterList"><i class="fa-solid fa-bars-filter"></i> Filters <span id="filters_alert"></span></button></div>
	<div id="paginationDisplay" class="col-md-8 pagination_display"></div>
	<div id="" class="col-md-2 text-end">
		<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Copy and Paste into Excel">
			<!-- <button type="button" class="btn btn-outline-secondary btn-xs copy-btn" data-info="copyToClipboard"><i class="fa-solid fa-copy"></i> Copy Page</button> -->
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
				<div class="selectors" id="statusSelectForm" class="selector" data-field="user_status" data-term="status" data-tooltip="Registration status"></div>
			</div>
			<div class="col-md-3 selector_box">
				<div class="selectors" id="deceasedSelectForm" class="selector" data-field="user_deceased" data-term="deceased" data-tooltip="Deceased or living"></div>
			</div>
			<div class="col-md-3 selector_box">
				<div class="selectors" id="councilSelectForm" class="selector" data-field="user_council_ID" data-term="council" data-tooltip="Current Council"></div>
			</div>
			<div class="col-md-3 selector_box">
				<div class="selectors" id="activeSelectForm" class="selector" data-field="user_active" data-term="active" data-tooltip="Database listing"></div>
			</div>

		</div>
	</div>
</div>

<div id="displayUserSelector" class="col-md-12">
	<h4> <i class="fa-solid fa-spinner fa-spin"></i> Thinking...</h4>
</div>

<div id="displayResults" class="col-md-12">
	<h4> <i class="fa-solid fa-spinner fa-spin"></i> Thinking...</h4>
</div>
<div id="processResults" class="col-md-12">
</div>

<script>
	let thisPage = 'transcripts_users';
	let thisTable = 'transcripts';

	const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
	let today = new Date();

	const selectors_array = {
		'deceased' : 'user_deceased',
		'status' : 'user_status',
		'council' : 'user_council_ID',
		'active' : 'user_active'
	};

	const adminUser = $('#adminUser').val();
	const adminCouncilID = $('#adminCouncilID').val();

	localStorage.setItem("adminUser", adminUser);
	localStorage.setItem("adminCouncilID", adminCouncilID);

	let transcriptsUser = typeof(localStorage.getItem('transcriptsUser')) != "undefined" && localStorage.getItem('transcriptsUser') != null ? localStorage.getItem('transcriptsUser') : '';
	localStorage.setItem("transcriptsUser",transcriptsUser);
	// $('#transcripts_user').val(transcriptsUser);

	let deceasedSelect = typeof(localStorage.getItem('deceasedSelect')) != "undefined" && localStorage.getItem('deceasedSelect') != null ? localStorage.getItem('deceasedSelect') : 'no';
	localStorage.setItem("deceasedSelect",deceasedSelect);

	let statusSelect = typeof(localStorage.getItem('statusSelect')) != "undefined" && localStorage.getItem('statusSelect') != null ? localStorage.getItem('statusSelect') : 'active';
	localStorage.setItem("statusSelect",statusSelect);

	let councilSelect = typeof(localStorage.getItem('councilSelect')) != "undefined" && localStorage.getItem('councilSelect') != null ? localStorage.getItem('councilSelect') : '';
	localStorage.setItem("councilSelect",councilSelect);

	let activeSelect = typeof(localStorage.getItem('activeSelect')) != "undefined" && localStorage.getItem('activeSelect') != null ? localStorage.getItem('activeSelect') : 'yes';
	localStorage.setItem("activeSelect",activeSelect);


	function getSelector(selectField='user_active',selectTerm='active',dbTable='users',vars='')
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
			$('#filters_alert').addClass('position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger');
			$('#filters_alert').html(x);
		}
	}

	function getUserSelector() {
		let marker = Math.floor(randomNumber(0, 255));

		let transcriptsUser = localStorage.getItem('transcriptsUser');

		let activeSelect = localStorage.getItem('activeSelect');
		let statusSelect = localStorage.getItem('statusSelect');
		let deceasedSelect = localStorage.getItem('deceasedSelect');
		let councilSelect = localStorage.getItem('councilSelect');

		let mydata = {
				adminUser:adminUser,
				adminCouncilID:adminCouncilID,

				transcriptsUser:transcriptsUser,
				activeSelect:activeSelect,
				statusSelect:statusSelect,
				councilSelect:councilSelect,
				deceasedSelect:deceasedSelect,
				pgActive:pgActive,
				limitNum:limitNum,
				pgNum:pgNum,
				pgSort:pgSort,
				thisPage:thisPage,

			};
			$.ajax({
				url: 		"jquery/jq_transcripts_users_choose_form.php?"+ marker,
				method: 	"POST",
				dataType:	"text",
				data: 		JSON.stringify(mydata),
				success:	function(response)
				{
					$('#displayUserSelector').html(response);
				},
				error: function(response)
				{
					console.log('ERROR: ' + response);
				}
			});
	}

	function getTranscript() {
		let marker = Math.floor(randomNumber(0, 255));

		let transcriptsUser = localStorage.getItem('transcriptsUser');

		let activeSelect = localStorage.getItem('activeSelect');
		let statusSelect = localStorage.getItem('statusSelect');
		let deceasedSelect = localStorage.getItem('deceasedSelect');
		let councilSelect = localStorage.getItem('councilSelect');

		let mydata = {
			adminUser:adminUser,
			adminCouncilID:adminCouncilID,

			transcriptsUser:transcriptsUser,
			activeSelect:activeSelect,
			statusSelect:statusSelect,
			councilSelect:councilSelect,
			deceasedSelect:deceasedSelect,
			pgActive:pgActive,
			limitNum:limitNum,
			pgNum:pgNum,
			pgSort:pgSort,
			thisPage:thisPage,

		};

		$.ajax({
			url: 		"jquery/jq_transcripts_users_data_form.php?"+ marker,
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

	function addFormChunk() {

	}

	function refreshPage() {
		refreshAjax();
		console.log('refreshing ====>');
		$('.tooltip').remove();
		$('.modal-dialog').removeClass('modal-xl');

		highlightFilterBtn();
		refreshSelectors();
		getUserSelector();
		getTranscript();

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

		$(document).on("click", '#test-msg', function(e) {
			toastMessage('error','There was an error saving your data.');
		});

		//! ===========>> transcriptsUser
		$(document).on("change", '#transcripts_user', function(e) {
			let transcriptsUser = $(this).val();
			localStorage.removeItem('transcriptsUser');
			localStorage.setItem('transcriptsUser',transcriptsUser);
			refreshPage();
		});
		//! ===========>> transcriptsUser

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

		//! ===========>> addNewFormItem

		$(document).on("click", '#addNewFormItem', function(e) {
			let addMarker = Math.floor(randomNumber(0, 255));
			let councilSelect = localStorage.getItem('councilSelect');

			let formChunk = '<tr class="form-group my-0">';
				formChunk += '<td class="border border-success">';
				formChunk += '<select class="form-select form-select-sm select-out-council" name="course_council_ID[]" data-info="'+addMarker+'" id="selectOutCouncil'+addMarker+'"></select>';
				formChunk += '</td>';
				formChunk += '<td class="border border-success" id="info' + addMarker + '">';
					formChunk += '<i class="fa-solid fa-hand-point-left"></i> Select a Council' + councilSelect + '| addMarker: ' +addMarker;
				formChunk += '</td>';
				formChunk += '<td class="border border-success">';
						formChunk += '<input type="text" class="form-control form-control-sm" name="course_year[]" id="course_year'+addMarker+'" value="">';
				formChunk += '</td>';
			formChunk += '</tr>';
			// $('#addFormItem').append(formChunk);
			$('#transcriptsTable tr:last').after(formChunk)
			let mydata = {
				adminUser:adminUser,
				adminCouncilID:adminCouncilID,
				councilSelect:councilSelect,
			};
			let marker = Math.floor(randomNumber(0, 255));

			$.ajax({
				url: 		"jquery/jq_transcripts_users_ooc_councils.php?"+ marker,
				method: 	"POST",
				dataType:	"text",
				data: 		JSON.stringify(mydata),
				success:	function(response)
				{
					$('.select-out-council').html(response);
				},
				error: function(response)
				{
					console.log('ERROR: ' + response);
				}
			});
		});

		$(document).on("change", '.select-out-council', function(e) {
			let thisMarker = $(this).data('info');
			let thisTranscriptCouncil = $('#selectOutCouncil'+thisMarker).val();
			console.log('changed');
			let mydata = {
				adminUser:adminUser,
				adminCouncilID:adminCouncilID,
				councilSelect:councilSelect,
				transcriptCouncil:thisTranscriptCouncil
			};
			let marker = Math.floor(randomNumber(0, 255));

			$.ajax({
				url: 		"jquery/jq_transcripts_users_ooc_courses.php?"+ marker,
				method: 	"POST",
				dataType:	"text",
				data: 		JSON.stringify(mydata),
				success:	function(response)
				{
					$('#info'+thisMarker).html('<select class="form-select form-select-sm select-out-course" data-info="'+thisMarker+'" name="course_ID[]" id="selectOutCourse'+thisMarker+'">'+ response + '</select>');
				},
				error: function(response)
				{
					console.log('ERROR: ' + response);
				}
			});
		});
		// $(document).on("change", '.select-out-course', function(e) {
		// 	let thisVal = $(this).val();
		// 	let thisMarker = $(this).data('info');
		// 	$('#course_ID'+thisMarker).val(thisVal);
		// 	let thisTranscriptCouncil = $('#selectOutCouncil'+thisMarker).val();
		// 	console.log('changed');
		// });
		//! ===========>> addNewFormItem

		//! ===========>> Process Transcripts
		$(document).on("click", '.submit-transcript', function(e) {
			e.preventDefault();

			console.log('submit-transcript');
			let marker = Math.floor(randomNumber(0, 255));

			var formData = $('#transaction_data').serialize();

			$.ajax({
				url: 		"jquery/jq_transcripts_users_processor.php?"+ marker,
				method: 	"POST",
				data:		formData,
				type: 		'POST',
				success:	function(response)
				{
					window.scrollTo(0,0);
					$('#processResults').html(response);
				},
				error: function(response)
				{
					console.log('ERROR: ' + response);
				}
			});
			toastMessage('success','The transcript data was saved successfully.');

		});

		//! ===========>> Process Transcripts


	});
</script>

<?php  // ** Lampkin 2024 ** // ?>
<?php require 'includes/footer.php'; ?>
