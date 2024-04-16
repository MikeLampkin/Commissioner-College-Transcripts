// ** Lampkin 2023 ** //
// ** CUSTOM JAVASCRIPTS = VERSION 6.3 ** //
// ** UPDATES:    ** //
// ** toastr : danger=>error : showAllPasswords   ** //

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

function copyToClipboard(element)
{
	var $temp = $("<textarea>");
	$("body").append($temp);
	$temp.val($(element).text()).select();
	document.execCommand("copy");
	$temp.remove();
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

function toastrResponse(clr,msg,title)
{
	var clr = clr;
	var msg = msg;
	var title = title;

	if( clr == 'danger' ) { clr = 'error'; }
	else if( clr == 'primary' ) { clr = 'success'; }

	toastr.options = {
	"positionClass": "toast-top-center",
	"closeButton": true,
	"debug": false,
	"newestOnTop": true,
	"progressBar": true,
	"preventDuplicates": false,
	"onclick": null,
	"showDuration": "300",
	"hideDuration": "1000",
	"timeOut": "5000",
	"extendedTimeOut": "1000",
	"showEasing": "swing",
	"hideEasing": "linear",
	"showMethod": "fadeIn",
	"hideMethod": "fadeOut"
	}

	return toastr[clr](msg,title);
}


$(function () {
	$('[data-bs-toggle="tooltip"]').tooltip()
});

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


$(document).ready(function()
{
	startTime();

	$('.toast').toast('show');

	const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
	if( tooltipTriggerList.length > 0 )
	{
		const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
	}




});

// a8e44cfebd4a9f19d24aad2c34421488b203cfe13e7c1e468b79904e21b0f538
