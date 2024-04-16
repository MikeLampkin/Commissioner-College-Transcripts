//! ========== AJAX
//! ========== Jan 2024
//! ========== v 2.5

let pgPath = window.location.pathname;
let pgName = pgPath.split("/").pop();
// console.log( pgName );
// let pgFieldsArray = pgName + '_fields_array';
	// let pgNameSort = pgName +'sort';

const modalButtonArchive = '<button id="process-form" class="btn btn-warning" type="submit" role="button" data-bs-dismiss="modal"> <i class="fas fa-save"></i> A R C H I V E </button>';
const modalButtonUnarchive = '<button id="process-form" class="btn btn-success" type="submit" role="button" data-bs-dismiss="modal"> <i class="fas fa-save"></i> U N A R C H I V E </button>';
const modalButtonSave = '<button id="process-form" class="btn btn-success" type="submit" role="button" data-bs-dismiss="modal"> <i class="fas fa-save"></i> S A V E </button>';
const modalButtonDelete = '<button id="process-form" class="btn btn-danger" type="submit" role="button" data-bs-dismiss="modal"> <i class="fa-solid fa-trash-can"></i> D E L E T E </button>';
const modalButtonUnDelete = '<button id="process-form" class="btn btn-info" type="submit" role="button" data-bs-dismiss="modal"> <i class="fa-solid fa-trash-undo"></i> U N D E L E T E </button>';
const modalButtonYes = '<button id="process-form" class="btn btn-primary" type="submit" role="button" data-bs-dismiss="modal"> <i class="fas fa-save"></i> Y E S </button>';
const modalButtonClose = '<button type="button" class="btn btn-secondary" type="cancel" data-bs-dismiss="modal"><i class="fa-regular fa-rectangle-xmark"></i> Close</button>';

let adminUser = typeof(localStorage.getItem('adminUser')) != "undefined" && localStorage.getItem('adminUser') !== null ? localStorage.getItem('adminUser') : $('#adminUser').val();
let fontSize = typeof(localStorage.getItem('fontSize')) != "undefined" && localStorage.getItem('fontSize') !== null ? localStorage.getItem('fontSize') : '15';
let limitNum = typeof(localStorage.getItem('limitNum')) != "undefined" && localStorage.getItem('limitNum') !== null ? localStorage.getItem('limitNum') : '50';
let pgNum = typeof(localStorage.getItem('pgNum')) != "undefined" && localStorage.getItem('pgNum') !== null ? localStorage.getItem('pgNum') : '1';
let pgSort = typeof(localStorage.getItem('pgSort')) != "undefined" && localStorage.getItem('pgSort') !== null ? localStorage.getItem('pgSort') : '';
let pgActive = typeof(localStorage.getItem('pgActive')) != "undefined" && localStorage.getItem('pgActive') !== null ? localStorage.getItem('pgActive') : 'yes';

localStorage.setItem("adminUser", adminUser);
localStorage.setItem("fontSize",fontSize);
localStorage.setItem("limitNum",limitNum);
localStorage.setItem("pgNum",pgNum);
localStorage.setItem("pgSort",'');
localStorage.setItem("pgActive",pgActive);

let success_msg = '<div class="alert alert-success"><h4>Success</h4></div> ';

const pepper = 'trustworthyloyalhelpfulfriendlycourteouskindobedientthriftybravecleanreverend';

// Function to generate random number
function randomNumber(min, max)
{
	return Math.random() * (max - min) + min;
}

function brag(message)
{
	if($("#main_message")) { $("#main_message").append(message); }
}

function clearForm(form)
{
	$("#"+form).trigger("reset");
	localStorage.clear();
}


function copyToClipboard(itemID)
{
	let copiedtext = $('#'+itemID).val();
	if (navigator.clipboard) {
		navigator.clipboard.writeText(copiedtext)
			.then(() => {
				$("#copy-icon").toggleClass("fa-file-export",0).toggleClass("fa-file-check",0);
				$(".copy-btn").toggleClass("btn-success",0).toggleClass("btn-secondary",0);
				setTimeout( function() {
					$("#copy-icon").toggleClass("fa-file-export",0).toggleClass("fa-file-check",0);
					$(".copy-btn").toggleClass("btn-success",0).toggleClass("btn-secondary",0);
				}, 3000);
			})
			.catch((error) => {
				$(".copy-btn").toggleClass("btn-danger",0).toggleClass("btn-secondary",0);
			});
	} else {
		$(".copy-icon").text("Not copied!").show().fadeOut(1200);
	}
}


//! ===========>> TIME/DATE <<=============
//! ===========>> TIME/DATE <<=============
//! ===========>> TIME/DATE <<=============

function formatDateYYYYMMDD(date)
{
	var d = new Date(date),
		month = '' + (d.getMonth() + 1),
		day = '' + d.getDate(),
		year = d.getFullYear();

	if (month.length < 2)
		{ month = '0' + month; }
	if (day.length < 2)
		{ day = '0' + day; }

	result = [year, month, day].join('-');
	return result;
}

function formatDateYYYYMMDDHHMMSS(date)
{
	var d = new Date(date),
		month = '' + (d.getMonth() + 1),
		day = '' + d.getDate(),
		year = d.getFullYear();
	var time = d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();

	if (month.length < 2)
		{ month = '0' + month; }
	if (day.length < 2)
		{ day = '0' + day; }

	// var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
	// var startTime = '08:00:00';
	// var endTime = '17:00:00';

	result = [year, month, day].join('-');
	result += ' ' + time;

	return result;
}

function formatDateYYYYMMDDtHHMMSS(date)
{
	var d = new Date(date),
		month = '' + (d.getMonth() + 1),
		day = '' + d.getDate(),
		year = d.getFullYear();
	var time = d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();

	if (month.length < 2)
		{ month = '0' + month; }
	if (day.length < 2)
		{ day = '0' + day; }

	// var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
	// var startTime = '08:00:00';
	// var endTime = '17:00:00';

	result = [year, month, day].join('-');
	result += 'T' + time;

	return result;
}


function formatDateMMDDYYYY(date)
{
	var d = new Date(date),
		month = '' + (d.getMonth() + 1),
		day = '' + d.getDate(),
		year = d.getFullYear();

	if (month.length < 2)
		{ month = '0' + month; }
	if (day.length < 2)
		{ day = '0' + day; }

	result = [month, day, year].join('-');
	return result;
}

function insertTodayTime(obj)
{
	var today = new Date();
	var dateMMDDYYYY = formatDateYYYYMMDDtHHMMSS(today);

	document.getElementById(obj).value = dateMMDDYYYY;
}


function prettyDate(data) {
	let thisDate = new Date(data).toLocaleDateString('en-us', { weekday:"long", year:"numeric", month:"short", day:"numeric"})
	return thisDate;
}

// ===========>> TIME/DATE <<=============
// ===========>> TIME/DATE <<=============
// ===========>> TIME/DATE <<=============

// function successMessage()
// {
// 	let successMsg = '<div class="toast-body"><h5><i class="fa-solid fa-badge-check"></i> Success!</h5></div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>';
// 	$('#alertMsg').html('<div class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true"><div class="d-flex">'+successMsg+'</div></div>').fadeIn(1000).fadeOut(3000);
// }

function displayMessage(msgType,msgField="messageBox",outFade=7000)
{
	let displayMsg = msgType != 'success' ? '<span class="text-warning fw-bold mx-auto"><span class="h5"> <i class="fa-solid fa-sun text-info"></i> Ooops!</span> It looks like there was a problem. [' + msgType + ']</span>' : '<span class="text-success fw-bold mx-auto"><span class="h5"> <i class="fa-regular fa-face-smile-hearts text-danger"></i> SUCCESS!</span> Your information has been recorded.</span>';
	$('#'+msgField).html(displayMsg).fadeIn(2000).fadeOut(outFade);
}

function clearAll()
{
	localStorage.clear();
	refreshPage();
	window.location.reload();

}

//! ===========>> PAGINATION 2023 <<=============
//! ===========>> PAGINATION 2023 <<=============
//! ===========>> PAGINATION 2023 <<=============

	function getPagination(total)
	{
		let limitNum = localStorage.getItem('limitNum') ?? 50;
		let pgNum = localStorage.getItem('pgNum') ?? 1;

		let prev = parseInt(pgNum)-1;
		let next = parseInt(pgNum)+1;
		let response_pag = '';


		if( limitNum !== 'ALL' && limitNum !== total)
		{
			limitNum = ( limitNum == 'ALL' ) ? parseInt(total) : parseInt(limitNum);
			let maxLinks = 10;
			let pageCountTotal = Math.ceil(total/limitNum); // Volume of pages
			let pageButtonCount = ( pageCountTotal <= 10 ) ? pageCountTotal : 10; // If it's more than 10 pages, show 10, then chunks of 10;
			// let curButtonGroup = (pgNum < 1 ) ? 1 : Math.ceil(pgNum / pageButtonCount);
			// let totalGroups = ( Math.ceil(pageCountTotal / pageButtonCount) > 1 ) ? Math.ceil(pageCountTotal / pageButtonCount) * 10 : 0;

			let displayMin = parseInt(pgNum)-5;
			let displayMax = parseInt(pgNum)+5;

			response_pag += '<nav id="pagination-nav" class="bg-white" aria-label="Page navigation" style="cursor:pointer;">';
			response_pag += '	<div class="text-center"><small>You are on page ' +pgNum+' of ' +pageCountTotal +' pages.</small></div>';
			response_pag += '	<ul class="pagination justify-content-center m-0 p-0">';

			let firstActive = ( pgNum == 1 ) ? ' disabled ' : '';
			response_pag += '		<li class="page-item">';
			response_pag += '			<a class="page-link ' +firstActive+ '" data-gotopage="1" aria-label="First">';
			response_pag += '				<span aria-hidden="true"><i class="fa-solid fa-angles-left"></i></span>';
			response_pag += '			</a>';
			response_pag += '		</li>';

			let prevActive = ( pgNum == 1 ) ? ' disabled ' : '';
			response_pag += '		<li class="page-item">';
			response_pag += '			<a class="page-link ' +prevActive+ '" data-gotopage="' +prev+ '" aria-label="Previous">';
			response_pag += '				<span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>';
			response_pag += '			</a>';
			response_pag += '		</li>';

			let thisGroup = 0;
			let activeGroup = 0;
			let pgNumGroup = Math.ceil(pgNum);
			for( let x=0; x<=pageCountTotal-1; x++ )
			{
				let pgNumLink = x+1;
				if( pgNumLink % maxLinks == 0 && pgNumLink !== 0 )
				{
					thisGroup = thisGroup + maxLinks;
				}
				let nextGroup = thisGroup + maxLinks;
				if( pgNum >= thisGroup-1 && pgNum <= thisGroup+1 )
				{
					activeGroup = thisGroup
				}

				let selectme = ( pgNumLink == pgNum ) ? 'active' : '';
				if( pgNumLink >= displayMin && pgNumLink <= displayMax  )
				{
					response_pag += '	<li class="page-item ' + selectme + ' pagination-item">';
					response_pag += '		<a class="page-link ' + selectme + ' pagination-link" data-gotopage="' +pgNumLink+ '">';
					response_pag += '				<span aria-hidden="true">' +pgNumLink+ '</span>';
					response_pag += '		</a>';
					response_pag += '	</li>';
				}

				if( pgNumLink == parseInt(pgNumGroup)+maxLinks-1 || pgNumLink == parseInt(pgNumGroup)-maxLinks-1 )
				{
					response_pag += '	<li class="page-item ' + selectme + ' pagination-item">';
					response_pag += '		<a class="page-link ' + selectme + ' pagination-link" data-gotopage="' +pgNumLink+ '">';
					response_pag += '				<span aria-hidden="true">[ ' +pgNumLink+ ' ]</span>';
					response_pag += '		</a>';
					response_pag += '	</li>';
				}
			}

			let nextActive = ( pgNum == pageCountTotal ) ? ' disabled ' : '';
			response_pag += '		<li class="page-item">';
			response_pag += '			<a class="page-link ' +nextActive+ '" data-gotopage="' +next+ '" aria-label="Next">';
			response_pag += '				<span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>';
			response_pag += '			</a>';
			response_pag += '		</li>';

			let lastActive = ( pgNum == pageCountTotal ) ? ' disabled ' : '';
			response_pag += '		<li class="page-item">';
			response_pag += '			<a class="page-link ' +lastActive+ '" data-gotopage="' +pageCountTotal+ '" aria-label="Last">';
			response_pag += '				<span aria-hidden="true"><i class="fa-solid fa-angles-right"></i></span>';
			response_pag += '			</a>';
			response_pag += '		</li>';


			response_pag += '	</ul>';
			// response_pag += '	<div class="text-center"><small>You are on page ' +pgNum+' of ' +pageCountTotal +' pages.</span></div>';
			response_pag += '</nav>';
		} else {
			response_pag = 'Viewing all records.';
		}

		return response_pag;
	}


	function limitAmt()
	{
		let limitArray = ['10','25','50','100'];
		let returndata = '<small>Record count: </small>';
		let limitNum = localStorage.getItem('limitNum') ?? 50;
		for( i=0; i<limitArray.length; i++)
		{
			let value = limitArray[i];
			let selectMe = ( limitNum == value ) ? 'btn-primary active disabled' : 'btn-outline-primary';
			returndata += '<span data-limit="' +value+ '" data-page="1" class="limit-btn btn btn-xs ' +selectMe+ '" tabindex="-1" role="button" aria-disabled="true">' +value+ '</span>';
		}
		$("#limitAmount").html(returndata);
	}
//! ===========>> PAGINATION 2023 <<=============
//! ===========>> PAGINATION 2023 <<=============
//! ===========>> PAGINATION 2023 <<=============


function viewActiveBtns()
{
	let pgActive = localStorage.getItem("pgActive");
	let responseButton = pgActive == 'yes' ? '<button class="btn btn-danger btn-sm active-btn" data-info="no"><i class="fa-solid fa-eye-slash"></i> View Inactive</button>' : '<button class="btn btn-success btn-sm active-btn" data-info="yes"><i class="fa-solid fa-eye"></i> View Active</button>';
	// return responseButton;
	$('#viewActiveBtns').html(responseButton);
}

function randomIcon()
{
	let iconArrayLen = Object.keys(icon_header_array).length;
	let iconRandoNum = Math.ceil(randomNumber(0, iconArrayLen-1));
	let iconNew = icon_header_array[Object.keys(icon_header_array)[iconRandoNum]];
	let iconNewMod = iconNew.replace('">', ' fa-stack-1x">');
	let newStack = '<span class="fa-stack" style="vertical-align:top;font-size: 0.65em;"><i class="fa-regular fa-circle fa-stack-2x"></i>'+iconNewMod+'</span>';
	// console.log('iconNew:' +iconNew);
	$('#page_icon').html(newStack);


	let colorA = Math.floor(randomNumber(0, 255));
	let colorB = Math.floor(randomNumber(0, 155));
	let colorC = Math.floor(randomNumber(0, 255));
	// console.log('colorA:' +colorA);
	// console.log('colorB:' +colorB);
	// console.log('colorC:' +colorC);
	$('.page_icon_title').fadeOut(500);
	$('#page_title').attr('style',"color:rgb(" +colorA+ ","+colorB+","+colorC+")");
	$('#page_icon').attr('style',"color:rgb(" +colorC+ ","+colorB+","+colorA+")");
	$('.page_icon_title').fadeIn(500);
}

function getNameData() {

	let findID = $('#admin_userID').val();
	let mydata = {
		findID:findID,
	};

	$.ajax({
		url: 		"jquery/jq_admin_access_names.php",
		method: 	"POST",
		dataType:	"text",
		data: 		JSON.stringify(mydata),
		success:	function(response)
		{
			// $('#dataDump').html(response);
			let responseTrim = response.trim();
			// $('#dataDump').html(responseTrim);
			let responseArray = responseTrim.split('|');
			$('#admin_first_name').val(responseArray[0]);
			$('#admin_last_name').val(responseArray[1]);
			$('#admin_email').val(responseArray[2]);
		},
		error: function(response)
		{
			console.log('ERROR: ' + response);
		}
	});
}



//# ===========>> General Use <<=============
//! ===========>> General Use <<=============
//? ===========>> General Use <<=============
function clearData(obj)
{
	document.getElementById(obj).value = '';
}

function preDeleteItem(thisID,thisIDField,thisTable,thisField,thisValue)
{
	// console.log('thisID: ' +thisID);
	// console.log('thisIDField: ' +thisIDField);
	// console.log('thisTable: ' +thisTable);
	// console.log('thisField: ' +thisField);
	// console.log('thisValue: ' +thisValue);
	let modalButtons = modalButtonDelete + ' ' + modalButtonClose;
	let modalMessage = '<form class="alert alert-danger" id="active_edit" class="" method="POST" enctype="multipart/form-data">';
		modalMessage += '<input type="hidden" name="process" id="process" value="yes">';
		modalMessage += '<input type="hidden" name="db_table" id="db_table" value="'+thisTable+'" />';
		modalMessage += '<input type="hidden" name="id_field" id="id_field" value="'+thisIDField+'" />';
		modalMessage += '<input type="hidden" name="action" id="action" value="update" />';
		modalMessage += '<input type="hidden" name="'+thisIDField+'" id="'+thisIDField+'" value="'+thisID+'">';
		modalMessage += '<input type="hidden" name="'+thisField+'" id="'+thisField+'" value="no">';
		modalMessage += '<h5 class="mb-3">Are your sure you want to delete this item?</h5>';
		modalMessage += modalButtons;
		modalMessage += '</form>';

	$('#modalLabel').html('<i class="fa-solid fa-trash-can text-danger"></i> Delete item?');
	$('#modalButtons').hide();
	$('#modalData').html(modalMessage);
}

function unDeleteItem(thisID,thisIDField,thisTable,thisField,thisValue)
{
	let modalButtons = modalButtonUnDelete + ' ' + modalButtonClose;
	let modalMessage = '<form class="alert alert-primary" id="active_edit" class="" method="POST" enctype="multipart/form-data">';
		modalMessage += '<input type="hidden" name="process" id="process" value="yes">';
		modalMessage += '<input type="hidden" name="db_table" id="db_table" value="'+thisTable+'" />';
		modalMessage += '<input type="hidden" name="id_field" id="id_field" value="'+thisIDField+'" />';
		modalMessage += '<input type="hidden" name="action" id="action" value="update" />';
		modalMessage += '<input type="hidden" name="'+thisIDField+'" id="'+thisIDField+'" value="'+thisID+'">';
		modalMessage += '<input type="hidden" name="'+thisField+'" id="'+thisField+'" value="yes">';
		modalMessage += '<h5 class="mb-3">Are your sure you want to undelete this item?</h5>';
		modalMessage += modalButtons;
		modalMessage += '</form>';

	$('#modalLabel').html('<i class="fa-solid fa-recycle text-info"></i> Activate item?');
	$('#modalButtons').hide();
	$('#modalData').html(modalMessage);
}

function preDeleteFile(thisID,thisIDField,thisTable,thisField,fileName,fileDirectory)
{

	let modalButtons = modalButtonDelete + ' ' + modalButtonClose;
	let modalMessage = '<form class="alert alert-danger" id="file_delete" class="" method="POST" enctype="multipart/form-data">';
		modalMessage += '<input type="hidden" name="process" id="process" value="yes">';
		modalMessage += '<input type="hidden" name="delete_file" id="delete_file" value="yes">';

		modalMessage += '<input type="hidden" name="id_field" id="id_field" value="'+thisIDField+'" />';
		modalMessage += '<input type="hidden" name="db_table" id="db_table" value="'+thisTable+'" />';
		modalMessage += '<input type="hidden" name="action" id="action" value="update" />';
		modalMessage += '<input type="hidden" name="'+thisIDField+'" id="'+thisIDField+'" value="'+thisID+'">';

		modalMessage += '<input type="hidden" name="'+thisField+'" id="'+thisField+'" value="">';
		modalMessage += '<input type="hidden" name="file_name" id="file_name" value="'+fileName+'">';
		modalMessage += '<input type="hidden" name="file_directory" id="file_directory" value="'+fileDirectory+'">';

		modalMessage += '<h5 class="mb-3">Are your sure you want to <strong>permanently</strong> delete this file? (' +fileName+')</h5>';
		modalMessage += modalButtons;
		modalMessage += '</form>';

	$('#modalLabel').html('<i class="fa-solid fa-trash-can text-danger"></i> Delete file?');
	$('#modalData').html(modalMessage);
}


function preArchiveItem(thisID,thisIDField,thisTable,thisField,thisValue)
{
	// console.log('thisID: ' +thisID);
	// console.log('thisIDField: ' +thisIDField);
	// console.log('thisTable: ' +thisTable);
	// console.log('thisField: ' +thisField);
	// console.log('thisValue: ' +thisValue);
	let modalButtons = modalButtonArchive + ' ' + modalButtonClose;
	let modalMessage = '<form class="alert alert-warning" id="active_edit" class="" method="POST" enctype="multipart/form-data">';
		modalMessage += '<input type="hidden" name="process" id="process" value="yes">';
		modalMessage += '<input type="hidden" name="db_table" id="db_table" value="'+thisTable+'" />';
		modalMessage += '<input type="hidden" name="id_field" id="id_field" value="'+thisIDField+'" />';
		modalMessage += '<input type="hidden" name="action" id="action" value="update" />';
		modalMessage += '<input type="hidden" name="'+thisIDField+'" id="'+thisIDField+'" value="'+thisID+'">';
		modalMessage += '<input type="hidden" name="'+thisField+'" id="'+thisField+'" value="no">';
		modalMessage += '<h5 class="mb-3">Are your sure you want to archive this item?</h5>';
		modalMessage += modalButtons;
		modalMessage += '</form>';

	$('#modalLabel').html('<i class="fa-solid fa-trash-can text-danger"></i> Archive item?');
	$('#modalData').html(modalMessage);
}

function preActivateItem(thisID,thisIDField,thisTable,thisField,thisValue)
{
	// console.log('thisID: ' +thisID);
	// console.log('thisIDField: ' +thisIDField);
	// console.log('thisTable: ' +thisTable);
	// console.log('thisField: ' +thisField);
	// console.log('thisValue: ' +thisValue);
	let modalButtons = modalButtonUnarchive + ' ' + modalButtonClose;
	let modalMessage = '<form class="alert alert-success" id="active_edit" class="" method="POST" enctype="multipart/form-data">';
		modalMessage += '<input type="hidden" name="process" id="process" value="yes">';
		modalMessage += '<input type="hidden" name="db_table" id="db_table" value="'+thisTable+'" />';
		modalMessage += '<input type="hidden" name="id_field" id="id_field" value="'+thisIDField+'" />';
		modalMessage += '<input type="hidden" name="action" id="action" value="update" />';
		modalMessage += '<input type="hidden" name="'+thisIDField+'" id="'+thisIDField+'" value="'+thisID+'">';
		modalMessage += '<input type="hidden" name="'+thisField+'" id="'+thisField+'" value="'+thisValue+'">';
		modalMessage += '<h5 class="mb-3">Are your sure you want to activate this item?</h5>';
		modalMessage += modalButtons;
		modalMessage += '</form>';

	$('#modalLabel').html('<i class="fa-solid fa-recycle text-success"></i> Activate item?');
	$('#modalData').html(modalMessage);
}

function unArchiveItem(thisID,thisIDField,thisTable,thisField,thisValue)
{
	let modalButtons = modalButtonUnArchive + ' ' + modalButtonClose;
	let modalMessage = '<form class="alert alert-primary" id="active_edit" class="" method="POST" enctype="multipart/form-data">';
		modalMessage += '<input type="hidden" name="process" id="process" value="yes">';
		modalMessage += '<input type="hidden" name="db_table" id="db_table" value="'+thisTable+'" />';
		modalMessage += '<input type="hidden" name="id_field" id="id_field" value="'+thisIDField+'" />';
		modalMessage += '<input type="hidden" name="action" id="action" value="update" />';
		modalMessage += '<input type="hidden" name="'+thisIDField+'" id="'+thisIDField+'" value="'+thisID+'">';
		modalMessage += '<input type="hidden" name="'+thisField+'" id="'+thisField+'" value="yes">';
		modalMessage += '<h5 class="mb-3">Are your sure you want to unarchive this item?</h5>';
		modalMessage += modalButtons;
		modalMessage += '</form>';

	$('#modalLabel').html('<i class="fa-solid fa-recycle text-info"></i> Activate item?');
	$('#modalData').html(modalMessage);
}



//! ===========>> PROCESS <<=============
//! ===========>> PROCESS <<=============
//! ===========>> PROCESS <<=============
function processForm(thisID)
{
	console.log('processing: ' + thisID);
	let formData = document.getElementById(thisID);
	let dataSet = new FormData(formData);

	$.ajax({
		type: 		"POST",
		url: 		"jquery/jq_processor.php",
		data:		dataSet,
		contentType: false,
		cache: 		false,
		processData: false,
		success:	function(response)
		{
			let trimResponse = response.trim();
			if( trimResponse == 'success' )
			{
				displayMessage(trimResponse);
				refreshPage();
			}
			else
			{
				$('#dataDump').html(response);
			}
		},
		error: function(response)
		{
			console.log('ERROR: ' + JSON.stringify(response));
		}
	});
}
// ===========>> PROCESS <<=============
// ===========>> PROCESS <<=============
// ===========>> PROCESS <<=============


function refreshAjax() {
	// console.log('ajaxing ====>');

	viewActiveBtns();
	limitAmt();
	randomIcon();
};


function validTest(formName)
{
	let allRequired = $('#' + formName + ' input,textarea,select').filter('[required]:visible');
	let countRequired = allRequired.length;
	// console.log('countRequired: ' + countRequired);

	let countComplete=0;
	$(allRequired).each(function()
	{
		// let thisItem = $(this).attr('id');
		let thisVal = $(this).val();
		let thisValLen = thisVal.length;
		if( thisValLen > 0 )
		{
			countComplete++;
		}
		// console.log('id: ' + thisItem);
		// console.log('val: ' + thisVal);
		if( countComplete == countRequired )
		{
			// console.log('We are complete!');
		}
	});
}
