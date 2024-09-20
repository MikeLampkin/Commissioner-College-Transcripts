//! ========== AJAX
//! ========== May 2024
//! ========== v 3.5
//# deactivate/reactivate | checkPage |

//> ***************************************************************************************
//> LISTENERS
//> ***************************************************************************************
$(document).ready(function()
{
	startTime();

	$(".toast").toast({
		autohide: false
	});

	showAlert();

	$(document).ajaxComplete(function(e)
	{
		const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
		const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
	});

	$(document).on('click', '.scroll', function(e)
	{
		e.preventDefault();
	});

	//! ===========>> Spin Ticket Icon <<=============
	$('#ticketBtn').removeClass('fa-spin');

	$(document).on("mouseover", '#ticketIcn', function(e)
	{
		$('#ticketBtn').addClass('fa-spin');
	});
	$(document).on("mouseout", '#ticketIcn', function(e)
	{
		$('#ticketBtn').removeClass('fa-spin');
		$('.tooltip').remove();
	});
	//! ===========>> Spin Ticket Icon <<=============

	//! ===========>> fontSize <<=============
	$(document).on("click", '.font-size-adjust', function(e)
	{
		localStorage.removeItem("fontSize");
		let newData = $(this).data('size');
		localStorage.setItem("fontSize",newData);
		refreshPage();
	});
	//! ===========>> fontSize <<=============

	//! ===========>> ACCORDION COLLAPSE SAVER  <<================
	$('.accordion-header').each(function(i, obj)
	{
		let thisTarget = $(this).data("bs-target");
		let thisAria = $(this).attr('aria-expanded');
		let savedAria = localStorage.getItem(thisTarget) ?? thisAria;
		if( thisAria !== savedAria )
		{
			thisAria = savedAria;
			$(this).attr('aria-expanded',savedAria);
		}
		if( thisAria !== 'true' ) { $(this).addClass('collapsed');$(thisTarget).removeClass('show'); } else { $(this).removeClass('collapsed');$(thisTarget).addClass('show'); }
	});

	$('.accordion-header').on("click", function()
	{
		let thisTarget = $(this).data("bs-target");
		let thisAria = $(this).attr('aria-expanded');
		localStorage.setItem(thisTarget, thisAria);
	});
	//! ===========>> ACCORDION COLLAPSE SAVER  <<================

	//! ===========>> PROCESS  <<================
	$(document).on("click", '#process-form', function(e)
	{
		e.preventDefault();
		console.log('=>>> POST OLD <<<=');
		const thisFormID = $(this).data('form') ?? 'data_entry';
		if( thisFormID.length > 0 )
		{
			processForm(thisFormID);
		}
		else
		{
			console.log('UNKNOWN FORM ID');
		}
	});
	//! ===========>> PROCESS  <<================

	//! ===========>> NEW PROCESS  <<================
	$(document).on("click", '#form-process', function(e) {
		e.preventDefault();
		let thisFormID = $(this).closest("form[id]").attr('id') ?? 'data_entry';
		let thisTest = $(this).data('test') !== null && $(this).data('test') !== 'undefined' ? $('#test').val() : 'no';
		let formData = $('#'+thisFormID).serializeArray();
		formProcess(formData,thisTest);
	});
	//! ===========>> NEW PROCESS  <<================

	//! ===========>> NEW PROCESS active-process  <<================
	$(document).on("click", '#active-process', function(e) {
		e.preventDefault();
		let thisTest = $(this).data('test') !== null && $(this).data('test') !== 'undefined' ? $('#test').val() : 'no';
		let formData = $('#active_edit').serializeArray();
		formProcess(formData,thisTest);
	});
	//! ===========>> NEW PROCESS active-process <<================

	//! ===========>> reloadPage click  <<================
	$(document).on("click", '#reloadPage', function(e)
	{
		refreshPage();
	});
	//! ===========>> reloadPage CLICK  <<================

	//! ===========>> PAGINATION CLICK  <<================
	$(document).on("click", '.page-link', function(e)
	{
		localStorage.removeItem("pgNum");
		let newData = $(this).data('gotopage');
		localStorage.setItem("pgNum",newData);
		$('html,body').scrollTop(0);
		refreshPage();
	});
	//! ===========>> PAGINATION CLICK  <<================

	//! ===========>> activeButton <<=============
	$(document).on("click", '.active-btn', function(e)
	{
		localStorage.removeItem("pgActive");
		let newData = $(this).data('info');
		localStorage.setItem("pgActive",newData);
		refreshPage();
	});
	//! ===========>> activeButton <<=============

	//! ===========>> LOWERCASE USER EMAIL  <<================
	$('#u_email').keyup(function(){
		$(this).val($(this).val().toLowerCase());
	});
	//! ===========>> LOWERCASE USER EMAIL  <<================


	//! ===========>> CUSTOM SEARCH  <<================
	$(document).on("click", '.custom-search', function(e)
	{
		let thisData = $(this).data('info');
		if( thisData !== 'all' )
		{
			let customSearch = localStorage.getItem('customSearch');
			let customSearchArray = customSearch.split(',');
				customSearchArray = $.grep(customSearchArray, function(value) {
				return value != 'all';
			  });

			customSearchArray.sort();
			let index = customSearchArray.includes(thisData);
			if( index == true ) //! If ID is in the array, REMOVE it
			{
				customSearchArray.sort();
				let newArray = [];
				let x=0;
				for( let i=0; i<customSearchArray.length; i++ )
				{
					let thisItem = customSearchArray[i];
					if( thisItem !== thisData )
					{
						newArray[x] = customSearchArray[i];
						x++;
					}
				}
				customSearchArray = newArray;
				let newCustomSearch = customSearchArray.toString();
				localStorage.removeItem('customSearch');
				localStorage.setItem('customSearch',newCustomSearch);
			}
			else  //! ..otherwise ADD it
			{
				customSearchArray.push(thisData);
				customSearchArray.sort();
				let newCustomSearch = customSearchArray.toString();
				localStorage.removeItem('customSearch');
				localStorage.setItem('customSearch',newCustomSearch);
			}
		}
		else
		{
			localStorage.removeItem('customSearch');
			localStorage.setItem('customSearch','all');
		}
		refreshPage();
	});
	//! ===========>> CUSTOM SEARCH  <<================

	//! ===========>> DEACTIVATE  <<================
	$(document).on("click", '.deactivate-item', function(e)
	{
		e.preventDefault();
		let thisID = $(this).data('info');
		let thisIDField = $(this).data('idfield');
		let thisTable = $(this).data('table');
		let thisField = $(this).data('field');
		let thisValue = $(this).data('value');
		let thisMsg = $(this).data('msg');
		let thisFormID = $(this).data('form');
		preDeactivateItem(thisID,thisIDField,thisTable,thisField,thisValue,thisFormID,thisMsg);
	});
	//! ===========>> DEACTIVATE  <<================

	//! ===========>> REACTIVATE  <<================
	$(document).on("click", '.reactivate-item', function(e)
	{
		e.preventDefault();
		let thisID = $(this).data('info');
		let thisIDField = $(this).data('idfield');
		let thisTable = $(this).data('table');
		let thisField = $(this).data('field');
		let thisValue = $(this).data('value');
		let thisMsg = $(this).data('msg');
		let thisFormID = $(this).data('form');
		preReactivateItem(thisID,thisIDField,thisTable,thisField,thisValue,thisFormID,thisMsg);
	});
	//! ===========>> ARCHIVE  <<================

	//! ===========>> copyToClipboard  <<================
	$(document).on("click", '.copy-btn', function(e)
	{
		let itemID = $(this).data('info');
		copyToClipboard(itemID);
	});
	//! ===========>> copyToClipboard  <<================

	//! ===========>> REFERRING URL <<=============
	let currentURL = $('#referring').val();
	let recentURL = localStorage.getItem('referringURL');
	if( currentURL !== recentURL )
	{
		localStorage.removeItem('referringURL');
		localStorage.setItem('referringURL',currentURL);
		localStorage.removeItem('newPage');
		localStorage.setItem('newPage','yes');
	}
	else
	{
		localStorage.setItem('newPage','no');
	}
	//! ===========>> REFERRING URL <<=============


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
			localStorage.removeItem("pgNum");
			let thisData = $(this).data('limit');
			localStorage.setItem("limitNum", thisData);
			localStorage.setItem("pgNum",1);
			refreshPage();
		});
		//! ===========>> LIMIT

		//! ===========>> editItem
		$(document).on("click", '.edit-item', function(e) {
			$('.tooltip').remove();
			$('.modal-dialog').addClass('modal-xl');
			let thisID = $(this).data('info');
			// console.log('thisID: ' +thisID);
			displayEntryForm(thisID);
		});
		//! ===========>> editItem


		//! ===========>> alertDismiss
		$(document).on("click", '.admin-alert', function(e) {
			let thisID = $(this).attr('id');
			localStorage.removeItem("alertDismiss");
			localStorage.setItem("alertDismiss", thisID);
		});
		//! ===========>> alertDismiss
});
