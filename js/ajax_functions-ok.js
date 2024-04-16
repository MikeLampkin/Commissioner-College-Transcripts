//! ========== AJAX
//! ========== Jan 2024
//! ========== v 2.4

let pgPath = window.location.pathname;
let pgName = pgPath.split("/").pop();
const pepper = 'trustworthyloyalhelpfulfriendlycourteouskindobedientthriftybravecleanreverend';


let siteUser = typeof(localStorage.getItem('siteUser')) != "undefined" && localStorage.getItem('siteUser') !== null ? localStorage.getItem('siteUser') : $('#siteUser').val();
let fontSize = typeof(localStorage.getItem('fontSize')) != "undefined" && localStorage.getItem('fontSize') !== null ? localStorage.getItem('fontSize') : '15';
let limitNum = typeof(localStorage.getItem('limitNum')) != "undefined" && localStorage.getItem('limitNum') !== null ? localStorage.getItem('limitNum') : '50';
let pageNum = typeof(localStorage.getItem('pageNum')) != "undefined" && localStorage.getItem('pageNum') !== null ? localStorage.getItem('pageNum') : '1';
let pgSort = typeof(localStorage.getItem('pgSort')) != "undefined" && localStorage.getItem('pgSort') !== null ? localStorage.getItem('pgSort') : '';
let pgActive = typeof(localStorage.getItem('pgActive')) != "undefined" && localStorage.getItem('pgActive') !== null ? localStorage.getItem('pgActive') : 'yes';

localStorage.setItem("siteUser", siteUser);
localStorage.setItem("fontSize",fontSize);
localStorage.setItem("limitNum",limitNum);
localStorage.setItem("pageNum",pageNum);
localStorage.setItem("pgSort",'');
localStorage.setItem("pgActive",pgActive);

function randomNumber(min, max)
{
	return Math.random() * (max - min) + min;
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
	let pageNum = localStorage.getItem('pageNum') ?? 1;

	let prev = parseInt(pageNum)-1;
	let next = parseInt(pageNum)+1;
	let response_pag = '';


	if( limitNum !== 'ALL' && limitNum !== total)
	{
		limitNum = ( limitNum == 'ALL' ) ? parseInt(total) : parseInt(limitNum);
		let maxLinks = 10;
		let pageCountTotal = Math.ceil(total/limitNum); // Volume of pages
		let pageButtonCount = ( pageCountTotal <= 10 ) ? pageCountTotal : 10; // If it's more than 10 pages, show 10, then chunks of 10;
		// let curButtonGroup = (pageNum < 1 ) ? 1 : Math.ceil(pageNum / pageButtonCount);
		// let totalGroups = ( Math.ceil(pageCountTotal / pageButtonCount) > 1 ) ? Math.ceil(pageCountTotal / pageButtonCount) * 10 : 0;

		let displayMin = parseInt(pageNum)-5;
		let displayMax = parseInt(pageNum)+5;

		response_pag += '<nav id="pagination-nav" class="bg-white" aria-label="Page navigation" style="cursor:pointer;">';
		response_pag += '	<div class="text-center"><small>You are on page ' +pageNum+' of ' +pageCountTotal +' pages.</small></div>';
		response_pag += '	<ul class="pagination justify-content-center m-0 p-0">';

		let firstActive = ( pageNum == 1 ) ? ' disabled ' : '';
		response_pag += '		<li class="page-item">';
		response_pag += '			<a class="page-link ' +firstActive+ '" data-gotopage="1" aria-label="First">';
		response_pag += '				<span aria-hidden="true"><i class="fa-solid fa-angles-left"></i></span>';
		response_pag += '			</a>';
		response_pag += '		</li>';

		let prevActive = ( pageNum == 1 ) ? ' disabled ' : '';
		response_pag += '		<li class="page-item">';
		response_pag += '			<a class="page-link ' +prevActive+ '" data-gotopage="' +prev+ '" aria-label="Previous">';
		response_pag += '				<span aria-hidden="true"><i class="fa-solid fa-angle-left"></i></span>';
		response_pag += '			</a>';
		response_pag += '		</li>';

		let thisGroup = 0;
		let activeGroup = 0;
		let pageNumGroup = Math.ceil(pageNum);
		for( let x=0; x<=pageCountTotal-1; x++ )
		{
			let pageNumLink = x+1;
			if( pageNumLink % maxLinks == 0 && pageNumLink !== 0 )
			{
				thisGroup = thisGroup + maxLinks;
			}
			let nextGroup = thisGroup + maxLinks;
			if( pageNum >= thisGroup-1 && pageNum <= thisGroup+1 )
			{
				activeGroup = thisGroup
			}

			let selectme = ( pageNumLink == pageNum ) ? 'active' : '';
			if( pageNumLink >= displayMin && pageNumLink <= displayMax  )
			{
				response_pag += '	<li class="page-item ' + selectme + ' pagination-item">';
				response_pag += '		<a class="page-link ' + selectme + ' pagination-link" data-gotopage="' +pageNumLink+ '">';
				response_pag += '				<span aria-hidden="true">' +pageNumLink+ '</span>';
				response_pag += '		</a>';
				response_pag += '	</li>';
			}

			if( pageNumLink == parseInt(pageNumGroup)+maxLinks-1 || pageNumLink == parseInt(pageNumGroup)-maxLinks-1 )
			{
				response_pag += '	<li class="page-item ' + selectme + ' pagination-item">';
				response_pag += '		<a class="page-link ' + selectme + ' pagination-link" data-gotopage="' +pageNumLink+ '">';
				response_pag += '				<span aria-hidden="true">[ ' +pageNumLink+ ' ]</span>';
				response_pag += '		</a>';
				response_pag += '	</li>';
			}
		}

		let nextActive = ( pageNum == pageCountTotal ) ? ' disabled ' : '';
		response_pag += '		<li class="page-item">';
		response_pag += '			<a class="page-link ' +nextActive+ '" data-gotopage="' +next+ '" aria-label="Next">';
		response_pag += '				<span aria-hidden="true"><i class="fa-solid fa-angle-right"></i></span>';
		response_pag += '			</a>';
		response_pag += '		</li>';

		let lastActive = ( pageNum == pageCountTotal ) ? ' disabled ' : '';
		response_pag += '		<li class="page-item">';
		response_pag += '			<a class="page-link ' +lastActive+ '" data-gotopage="' +pageCountTotal+ '" aria-label="Last">';
		response_pag += '				<span aria-hidden="true"><i class="fa-solid fa-angles-right"></i></span>';
		response_pag += '			</a>';
		response_pag += '		</li>';


		response_pag += '	</ul>';
		// response_pag += '	<div class="text-center"><small>You are on page ' +pageNum+' of ' +pageCountTotal +' pages.</span></div>';
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
	limitAmt();
	randomIcon();
};
