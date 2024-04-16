//! ========== AJAX
//! ========== FEB 2024
//! ========== v 2.7


//> ***************************************************************************************
//> LISTENERS
//> ***************************************************************************************
$(document).ready(function()
{
	startTime();

	$(".toast").toast({
		autohide: false
	});


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
		const thisFormID = $(this).closest("form[id]").attr('id') ?? 'data_entry';
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

	//! ===========>> LIMIT  <<================
	$(document).on("click", '.limit-btn', function(e)
	{
		localStorage.removeItem("limitNum");
		let thisData = $(this).data('limit');
		localStorage.setItem("limitNum",thisData);
		localStorage.removeItem('pgNum');
		localStorage.setItem('pgNum',1);
		refreshPage();
	});
	//! ===========>> LIMIT  <<================

	//! ===========>> DEACTIVATE  <<================
	$(document).on("click", '.deactivate-item', function(e)
	{
		e.preventDefault();
		let thisID = $(this).data('info');
		let thisIDField = $(this).data('idfield');
		let thisTable = $(this).data('table');
		let thisField = $(this).data('field');
		let thisValue = $(this).data('value');
		let thisFormID = $(this).data('form');
		preArchiveItem(thisID,thisIDField,thisTable,thisField,thisValue,thisFormID);
	});
	//! ===========>> DEACTIVATE  <<================

	//! ===========>> REACTIVATE  <<================
	$(document).on("click", '.unarchive-item', function(e)
	{
		e.preventDefault();
		let thisID = $(this).data('info');
		let thisIDField = $(this).data('idfield');
		let thisTable = $(this).data('table');
		let thisField = $(this).data('field');
		let thisValue = $(this).data('value');
		let thisFormID = $(this).data('form');
		unArchiveItem(thisID,thisIDField,thisTable,thisField,thisValue,thisFormID);
	});
	//! ===========>> ARCHIVE  <<================

	//! ===========>> copyToClipboard  <<================
	$(document).on("click", '.copy-btn', function(e)
	{
		let itemID = $(this).data('info');
		copyToClipboard(itemID);
	});
	//! ===========>> copyToClipboard  <<================


});
