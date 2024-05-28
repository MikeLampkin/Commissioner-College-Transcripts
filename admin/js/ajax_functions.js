//! ========== AJAX
//! ========== APR 2024
//! ========== v 3.32

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

let customSearch = typeof(localStorage.getItem('customSearch')) != "undefined" && localStorage.getItem('customSearch') !== null ? localStorage.getItem('customSearch') : 'all';
localStorage.setItem('customSearch',customSearch);

let fontSize = typeof(localStorage.getItem('fontSize')) != "undefined" && localStorage.getItem('fontSize') !== null ? localStorage.getItem('fontSize') : '15';
let limitNum = typeof(localStorage.getItem('limitNum')) != "undefined" && localStorage.getItem('limitNum') !== null ? localStorage.getItem('limitNum') : '50';
let pgNum = typeof(localStorage.getItem('pgNum')) != "undefined" && localStorage.getItem('pgNum') !== null ? localStorage.getItem('pgNum') : '1';
let pgSort = typeof(localStorage.getItem('pgSort')) != "undefined" && localStorage.getItem('pgSort') !== null ? localStorage.getItem('pgSort') : '';
let pgActive = typeof(localStorage.getItem('pgActive')) != "undefined" && localStorage.getItem('pgActive') !== null ? localStorage.getItem('pgActive') : 'yes';

localStorage.setItem('fontSize',fontSize);
localStorage.setItem('limitNum',limitNum);
localStorage.setItem('pgNum',pgNum);
localStorage.setItem('pgSort',pgSort);
localStorage.setItem('pgActive',pgActive);

//# ------ THESE WORK TOGETHER
let referringURL = typeof(localStorage.getItem('referringURL')) != 'undefined' && localStorage.getItem('referringURL') !== null ? localStorage.getItem('referringURL') : '';
localStorage.setItem('referringURL',referringURL);
let newPage = typeof(localStorage.getItem('newPage')) != 'undefined' && localStorage.getItem('newPage') !== null ? localStorage.getItem('newPage') : 'no';
localStorage.setItem('newPage',newPage);
//# ------ THESE WORK TOGETHER



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

function scrollToAnchor(aid){
    let aTag = $("#"+ aid);
	if( aTag.length < 1 )
	{
		let aTag = $("a[name='"+ aid +"']");
	}
    $('html,body').animate({scrollTop: aTag.offset().top},'slow');
}

function copyToClipboard(itemID)
{
	let copiedtext = $(itemID).val();
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

function copyToClipboardB(itemID)
{
	let copiedtext = $('#'+itemID).val();
	if (navigator.clipboard) {
		navigator.clipboard.writeText(copiedtext);
	} else {
		alert('copy failed');
	}
}

function copyToClipboardA(element)
{
	var $temp = $("<textarea>");
	$("body").append($temp);
	$temp.val($(element).text()).select();
	document.execCommand("copy");
	$temp.remove();
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


//! ===========>> Timer  <<================
function timer(id='timer',count='30',type='sec')
{
	$('#'+id).html(count + " secs");
	let span = type == 'sec' ? 1000 : 6000;//1000 will  run it every 1 second
	let counter = setInterval(timex,span);
	function timex()
	{
		count = count-1;
		$('#'+id).html(count + " secs");
		if(count <= 0)
		{
			clearInterval(counter);
			return;
		}
	}

}
//! ===========>> Timer  <<================

//! ===========>> Toast  <<================
function toastMessage(msgType='success',msgBody='')
{
	let marker = Math.floor(randomNumber(0, 255));

	// Create a new toast element
	var toastEl = document.createElement("div");
	toastEl.classList.add("toast");
	toastEl.setAttribute("role", "alert");
	toastEl.setAttribute("aria-live", "assertive");
	toastEl.setAttribute("aria-atomic", "true");

	let toastColor = msgType == 'success' ? 'success' : 'danger';
	let toastBody = msgBody.length < 1 ? 'Your save was successful.' : msgBody;
	let toastIcon = msgType == 'success' ? '<span class="fa-stack fa-1x"><i class="fa-solid fa-circle fa-stack-2x"></i><i class="fa-solid fa-thumbs-up fa-bounce fa-stack-1x fa-inverse"></i></span>' : '<span class="fa-stack fa-1x"><i class="fa-solid fa-circle fa-stack-2x"></i><i class="fa-solid fa-dumpster-fire fa-beat-fade fa-stack-1x fa-inverse"></i></span>';
	let toastItem = `
		<div class="toast-header text-`+ toastColor +`">
			` + toastIcon + `
			<strong class="me-auto">` + msgType.toUpperCase() + `</strong>
			<small class="text-body-secondary" id="toastTimer` + marker + `"></small>
			<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
		</div>
		<div class="toast-body">` + toastBody + `</div>
	`;

		toastEl.innerHTML =toastItem;
		// Append the toast to the container
		document.getElementById("toaster").appendChild(toastEl);
		timer('toastTimer'+marker,5,'sec');

		// Initialize the toast
		var toast = new bootstrap.Toast(toastEl);
		toast.show();
}
//! ===========>> Toast  <<================


// function displayMessage(msgType,msgField="messageBox")
// {
// 	let displayMsg = msgType != 'success' ? '<span class="text-warning fw-bold mx-auto"><span class="h5"> <i class="fa-solid fa-sun text-info"></i> Ooops!</span> It looks like there was a problem. [' + msgType + ']</span>' : '<span class="text-success fw-bold mx-auto"><span class="h5"> <i class="fa-regular fa-face-smile-hearts text-danger"></i> SUCCESS!</span> Your information has been recorded.</span>';
// 	$('#'+msgField).html(displayMsg).fadeIn(2000).fadeOut(5000);
// }

function clearAll()
{
	localStorage.clear();
	refreshPage();
	window.location.reload();
}

//! ===========>> PAGINATION 2023 <<=============
//! ===========>> PAGINATION 2023 <<=============
//! ===========>> PAGINATION 2023 <<=============

	function getPagination(totalCnt)
	{
		let total = parseInt(totalCnt);
		var response_pagination = '';
		if( total > 0 )
		{
				response_pagination = '';
			let limitNum = localStorage.getItem('limitNum') ?? 50;
			let pgNum = localStorage.getItem('pgNum') ?? 1;

			let prev = parseInt(pgNum)-1;
			let next = parseInt(pgNum)+1;

			if( limitNum !== 'ALL' && limitNum !== total)
			{
				limitNum = ( limitNum == 'ALL' ) ? total : parseInt(limitNum);
				let maxLinks = 10;
				let pageCountTotal = Math.ceil(total/limitNum); // Volume of pages
				let pageButtonCount = ( pageCountTotal <= 10 ) ? pageCountTotal : 10; // If it's more than 10 pages, show 10, then chunks of 10;
				// let curButtonGroup = (pgNum < 1 ) ? 1 : Math.ceil(pgNum / pageButtonCount);
				// let totalGroups = ( Math.ceil(pageCountTotal / pageButtonCount) > 1 ) ? Math.ceil(pageCountTotal / pageButtonCount) * 10 : 0;

				let displayMin = parseInt(pgNum)-5;
				let displayMax = parseInt(pgNum)+5;

				response_pagination += '<nav id="pagination-nav" class="bg-white" aria-label="Page navigation" style="cursor:pointer;">';
				response_pagination += '	<div class="text-center"><small>You are on page ' +pgNum+' of ' +pageCountTotal +' pages.</small></div>';
				response_pagination += '	<ul class="pagination justify-content-center m-0 p-0">';

				let firstActive = ( pgNum == 1 ) ? ' disabled ' : '';
				response_pagination += '		<li class="page-item">';
				response_pagination += '			<a class="page-link ' +firstActive+ '" data-gotopage="1" aria-label="First">';
				response_pagination += '				<span aria-hidden="true"><i class="fa-solid fa-angles-left"></i></span>';
				response_pagination += '			</a>';
				response_pagination += '		</li>';

				let prevActive = ( pgNum == 1 ) ? ' disabled ' : '';
				response_pagination += '		<li class="page-item">';
				response_pagination += '			<a class="page-link ' +prevActive+ '" data-gotopage="' +prev+ '" aria-label="Previous">';
				response_pagination += '				<span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>';
				response_pagination += '			</a>';
				response_pagination += '		</li>';

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
						response_pagination += '	<li class="page-item ' + selectme + ' pagination-item">';
						response_pagination += '		<a class="page-link ' + selectme + ' pagination-link" data-gotopage="' +pgNumLink+ '">';
						response_pagination += '				<span aria-hidden="true">' +pgNumLink+ '</span>';
						response_pagination += '		</a>';
						response_pagination += '	</li>';
					}

					if( pgNumLink == parseInt(pgNumGroup)+maxLinks-1 || pgNumLink == parseInt(pgNumGroup)-maxLinks-1 )
					{
						response_pagination += '	<li class="page-item ' + selectme + ' pagination-item">';
						response_pagination += '		<a class="page-link ' + selectme + ' pagination-link" data-gotopage="' +pgNumLink+ '">';
						response_pagination += '				<span aria-hidden="true">[ ' +pgNumLink+ ' ]</span>';
						response_pagination += '		</a>';
						response_pagination += '	</li>';
					}
				}

				let nextActive = ( pgNum == pageCountTotal ) ? ' disabled ' : '';
				response_pagination += '		<li class="page-item">';
				response_pagination += '			<a class="page-link ' +nextActive+ '" data-gotopage="' +next+ '" aria-label="Next">';
				response_pagination += '				<span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>';
				response_pagination += '			</a>';
				response_pagination += '		</li>';

				let lastActive = ( pgNum == pageCountTotal ) ? ' disabled ' : '';
				response_pagination += '		<li class="page-item">';
				response_pagination += '			<a class="page-link ' +lastActive+ '" data-gotopage="' +pageCountTotal+ '" aria-label="Last">';
				response_pagination += '				<span aria-hidden="true"><i class="fa-solid fa-angles-right"></i></span>';
				response_pagination += '			</a>';
				response_pagination += '		</li>';


				response_pagination += '	</ul>';
				// response_pagination += '	<div class="text-center"><small>You are on page ' +pgNum+' of ' +pageCountTotal +' pages.</span></div>';
				response_pagination += '</nav>';
			} else {
				response_pagination = 'Viewing all records.';
			}
		}
		return response_pagination;
	}


	function limitAmt(vars='')
	{
		let limitArray = length.vars > 1 ? vars : ['10','25','50','100'];
		// let limitArray = ['10','25','50','100','ALL'];
		let returndata = '<small>Record count: </small>';
		let limitNum = localStorage.getItem('limitNum') ?? 50;
		for( i=0; i<limitArray.length; i++)
		{
			let value = limitArray[i];
			let selectMe = ( limitNum == value ) ? 'btn-primary active disabled' : 'btn-outline-primary';
			returndata += '<span data-limit="' +value+ '" data-page="1" class="limit-btn btn btn-xs mr-1 ' +selectMe+ '" tabindex="-1" role="button" aria-disabled="true">' +value+ '</span>';
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
			// console.log(response);
			let trimResponse = response.trim();
			if( trimResponse == 'success' )
			{
				toastMessage(trimResponse,'')
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
//! ===========>> PROCESS <<=============
//! ===========>> PROCESS <<=============
//! ===========>> PROCESS <<=============



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

function countChars(limit,field,output)
{
	var value = document.getElementById(field).value;
	var length = value.length;
	var charactersLeft = limit - length;
	document.getElementById(output).innerHTML =  charactersLeft;
}

function startTime()
{
	var today = new Date();
	var hours = today.getHours();
	var minutes = today.getMinutes();
	var seconds = today.getSeconds();
	minutes = checkTime(minutes);
	seconds = checkTime(seconds);

	var ampm = hours >= 12 ? 'pm' : 'am';

	hours = (hours + 24) % 12 || 12;
	hours = hours ? hours : 12; // the hour '0' should be '12'

	document.getElementById('myClock').innerHTML =
	hours + ":" + minutes + ":" + seconds + " " + ampm;
	var t = setTimeout(startTime, 1000);
}

function checkTime(i)
{
	if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
	return i;
}

function toggleAllChecklist(source)
{
	checkboxes = document.getElementsByName(source);
	for( var i=0; i<checkboxes.length; i++ )
	{
		checkboxes[i].checked = source.checked;
	}
}

function showPassword()
{
	for (let i=0; i< arguments.length; i++)
	{
		let x = document.getElementById(arguments[i]);
		if (x.type === "password") {
			x.type = "text";
		} else {
			x.type = "password";
		}
	}
}

function showAllPasswords()
{
	for (let i=0; i< arguments.length; i++)
	{
		let x = document.getElementById(arguments[i]);
		if (x.type === "password") {
			x.type = "text";
		} else {
			x.type = "password";
		}
	}
}

function toggleCSS(item,css1)
{
	var x = document.getElementById(item);
	x.classList.toggle(css1);
}

function hideShow(id)
{
	let x = document.getElementById("myDIV");
	if(x.style.display === "none") {
		x.style.display = "block";
	} else {
		x.style.display = "none";
	}
}

function swapCSS(item,css1,css2)
{
	var x = document.getElementById(item);
	if( x.classList.contains(css1) ) {
		x.classList.remove(css1);
		x.classList.add(css2);
	} else {
		x.classList.add(css1);
		x.classList.remove(css2);
	}
}

function validatePassword(field)
{
	passtxt = document.getElementById(field);
	pwrd_response = document.getElementById("pwrd_response");

	texto = passtxt.value.trim();
	uppercase = false;
	lowercase = false;
	number = false;
	length = false;

	//uppercase letter
	for (i=0; i< texto.length; i++){
		if ( isNaN(texto[i]) && (texto[i] == texto[i].toUpperCase()) ){
			uppercase = true;
			break;
		} else {
			uppercase = false;
		}
	}

	//lowercase
	for (i=0; i< texto.length; i++){
		if ( isNaN(texto[i]) && (texto[i] == texto[i].toLowerCase()) ){
			lowercase = true;
			break;
		} else {
			lowercase = false;
		}
	}

	//number
	for (i=0; i< texto.length; i++){
		if (!isNaN(texto[i])){
			number = true;
			break;
		} else {
			number = false;
		}
	}

	if (texto.length < 8){
		length = false;
	} else {
		length = true;
	}

	if (lowercase && uppercase && number && length){
		pwrd_response.innerHTML = "<span style=\"color:green;\"> <i class=\"far fa-laugh-beam\"></i> Password is good! <i class=\"fas fa-shield-check\"></i></span>";
	} else {
		pwrd_response.innerHTML = " [ " +
		"lowercase: " + amIright(lowercase) + " | "+
		"uppercase: " + amIright(uppercase) + " | "+
		"number: " + amIright(number) + " | "+
		"length: " + amIright(length) + " ]";
	}

}

function amIright(val){
if (val == true)
	return "<span style=\"color:green;\"><i class=\"far fa-laugh-beam\"></i></span>";
	else
	return "<span style=\"color:red;\"><i class=\"far fa-sad-tear\"></i></span>";
}

function compareFields(fieldOne,fieldTwo)
{
	field_new = document.getElementById(fieldOne);
	field_origin = document.getElementById(fieldTwo);

	compare_response = document.getElementById("compare_response");

	text_origin = field_origin.value.trim();
	text_new = field_new.value.trim();

	if ( text_new !== text_origin )
	{
		matches = false;
	} else {
		matches = true;
	}

	if ( matches )
	{
		compare_response.innerHTML = "<span style=\"color:green;\"> <i class=\"far fa-laugh-beam\"></i> Fields match! <i class=\"fas fa-shield-check\"></i></span>";
			field_new.style.borderColor = "green";
			field_origin.style.borderColor = "green";
	} else {
		compare_response.innerHTML = "<span style=\"color:red;\"> <i class=\"fas fa-sad-tear\"></i> Fields don't match! <i class=\"fas fa-shield\"></i></span>";
			field_new.style.borderColor = "red";
			field_origin.style.borderColor = "red";
	}
}



$(window).scroll(function()
{
	var scroll = $(window).scrollTop();
	if (scroll > 0) {
		$(".container-freeze").addClass("active");
		$(".container-master").addClass("active");
	}
	else {
		$(".container-freeze").removeClass("active");
		$(".container-master").removeClass("active");
	}
});

function formatDate(date)
{
	var d = new Date(date),
		month = '' + (d.getMonth() + 1),
		day = '' + d.getDate(),
		year = d.getFullYear();

	if (month.length < 2)
		month = '0' + month;
	if (day.length < 2)
		day = '0' + day;

	return [year, month, day].join('-');
}

function insertToday(obj)
{
	var today = new Date();
	var dateMMDDYYYY = formatDate(today);

	document.getElementById(obj).value = dateMMDDYYYY;
}
function insertClear(obj)
{
	document.getElementById(obj).value = '';
}


function insertDeactivate(obj)
{
	document.getElementById(obj).value = '1980-01-01';
	document.getElementById(obj).value = '08:00:00';
	document.getElementById(obj).value = '1980-01-01';
	document.getElementById(obj).value = '17:00:00';
	// document.getElementById("data_entry").submit();
}

function clearForm(form,pg='')
{
	$("#"+form).trigger("reset");
	localStorage.clear();
	search();
	if( pg !== '' )
	{
		window.location.href = pg;
	}
}

function toggleAll(checkItem,source)
{
	var checkbox = checkItem;
	checkboxes = document.getElementsByName(checkbox);
	for(var i=0, n=checkboxes.length;i<n;i++) {
	checkboxes[i].checked = source.checked;
	}
}

function displayCustomSearchBtn()
{
	$('.custom-search').removeClass('active');
	let customSearch = localStorage.getItem("customSearch");
	if( customSearch && customSearch.indexOf(',') )
	{
		let customSearchArray = customSearch.split(',');
		for( let i=0; i<customSearchArray.length; i++ )
		{
			$('#search-btn-'+customSearchArray[i]).addClass('active');
		}
	}
	else
	{
		$('#search-btn-'+customSearch).addClass('active');
	}
}

function checkPage(page,defaultVar)
{
	let currentPage = localStorage.getItem("currentPage") ?? '';
	if( currentPage !== page )
	{
		localStorage.setItem('currentPage',page);
		localStorage.removeItem("limitNum");
		localStorage.setItem("limitNum",50);
		localStorage.removeItem("pgSort");
		localStorage.setItem("pgSort",defaultVar);
		localStorage.removeItem("pgNum");
		localStorage.setItem("pgNum",1);
	}
}

function refreshAjax() {
	// console.log('ajaxing ====>');

	$('#displayResults').html('<span class="h4 m-2"> <i class="fa-solid fa-spinner fa-spin"></i> Thinking...</span>');


	viewActiveBtns();
	limitAmt();
	randomIcon();
	displayCustomSearchBtn();

	$(".pagination_display").html('');

};
