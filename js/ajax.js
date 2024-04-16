//! ========== AJAX
//! ========== SEP 2023
//! ========== v 1.82


//> ***************************************************************************************
//> LISTENERS
//> ***************************************************************************************
$(document).ready(function()
{

	// refreshPage();

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
		let thisFormID = $(this).closest("form[id]").attr('id');
		processForm(thisFormID);
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
		localStorage.removeItem("pageNum");
		let newData = $(this).data('gotopage');
		localStorage.setItem("pageNum",newData);
		$('html,body').scrollTop(0);

		// console.log('NewPage: ' +newData);
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
		localStorage.removeItem('pageNum');
		localStorage.setItem('pageNum',1);
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




	$(document).ajaxComplete(function(e)
	{
		const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
		const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
	});


});
