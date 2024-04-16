<?php // ** Copyright © 2010-2022 Michael H Lampkin - mike@lampkin.net ** // ?>
<?php // ** ADMIN LOGIN 10.1 - 2023 ** // ?>

<?php
	// $authenticate_methods = array('Simple','COH','Google','Facebook','Instagram','Reddit','Twitter','Yahoo');
	// $authenticate_methods = array('Simple', 'COH', 'COH2', 'COH3', 'Google', 'Facebook', 'Instagram', 'Reddit', 'Twitter', 'Yahoo');

	$referring_url = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	$referring_url = str_replace('clear','',$referring_url); // url set in header; remove the clear var
	$_SESSION['referring_url'] = $referring_url;

	$path_doc = $_SERVER['PHP_SELF'];
	$dc_name = basename($_SERVER['PHP_SELF']);
	$path = str_replace($dc_name,'',$path_doc);
?>


<style>

	.login-logobox
	{
		margin-top: -3.125em;
		margin-bottom: 0.3125em;
		text-align: center;
	}

	.login-logobox img
	{
		height:6.25em;
		width: auto;
		border-radius:50%;
		box-shadow: 0em 0em 10px #000;
	}

</style>
<?php
	$feedback = '';

	$feedback .= '
		<div class="valid-feedback">
			Looks good!
		</div>';

	$feedback .= '
		<div class="invalid-feedback">
			Please complete this field.
		</div>';
?>

		<div class="container-fluid mb-3">
			<form id="form_login" class="col-12 pt-3" method="post" action="<?php echo $path . 'includes/login_users.php'; ?>">

				<div class="card col-md-6 mx-auto login-box shadow">
					<div class="card-header">
						<div class="login-logobox">
							<img src="https://commissionercollege.com/img/commissioner_college_icon_red.png" class="">
						</div>
					</div>

					<div class="card-body">

						<ul class="list-group list-group-flush">
							<li class="list-group-item">
								<input type="hidden" class="form-control" name="referring_url" id="referring_url" value="<?php echo $referring_url ;?>" >

								<div class="input-group mb-3">
									<span class="input-group-text border" id="formlogin-icon"><i class="fas fa-user" ></i></span>
									<input type="text" class="form-control form-control-lg" name="formlogin" id="formlogin" placeholder="Login" autofocus>
								</div>

								<div class="input-group mb-3">
									<span class="input-group-text border" id="formpass-icon"><i class="fas fa-lock" ></i></span>
									<input type="password" class="form-control form-control-lg" name="formpass" id="formpass" placeholder="Password">
								</div>

								<div class="form-check form-switch mx-auto mb-3">
									<input class="form-check-input" type="checkbox" id="showPass" onchange="showPassword('formpass')">
									<label class="form-check-label" for="showPass"> Show password </label>
								</div>

								<div class="d-grid text-center">
									<button type="submit" class="btn btn-lg btn-success" id="submitLogin">
										<i class="fas fa-sign-in-alt"></i> <strong> Login </strong>
									</button>

								</div>
							</li>
						</ul>
					</div>
					<div class="card-footer">
						<div class="row mb-3 text-center">
							<div class="col-md-12">
								Trouble signing in?<br />
								<a href="?logout" class="btn btn-secondary btn-xs"><i class="far fa-hand-sparkles"></i> Clear Settings</a>
								<button type="button" class="btn btn-secondary btn-xs" data-bs-toggle="modal" data-bs-target="#modalFind"><i class="far fa-id-badge"></i> Find login </button>
								<button type="button" class="btn btn-secondary btn-xs" data-bs-toggle="modal" data-bs-target="#modalReset"><i class="far fa-key"></i> Reset password </button>
							</div>
						</div>
					</div>
				</div>


			</form>
		</div>

		<div class="row">
			<div class="col-md-12 text-center mx-auto">
				<i class="fad fa-calendar-star"></i> <?php echo date('M d, Y') . "   " ; ?><i class="fad fa-clock"></i> <span id="myClock"></span>
			</div>
		</div>

		<div class="modal fade" id="modalReset" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="View" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"><i class="far fa-key"></i> Reset My Password</h5>
						 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
				<form class="nice-form" id="reset_password" method="POST" enctype="multipart/form-data" >
					<input type="hidden" class="form-control" name="reset_ip" id="reset_ip" value="<?php echo $your_ipaddress; ?>" />

					<div class="modal-body required">
						<p>Enter your email address and an email will be sent with information on resetting your password.</p>
						<input type="text" class="form-control" name="reset_email" id="reset_email" value="" placeholder="Email address" autofocus required>
					</div>
					<div class="modal-footer">
						<button id="reset_me" type="submit" class="btn btn-info" role="button" Xdata-bs-dismiss="modal"> <i class="far fa-key"></i> S E N D </button>
					</div>
				</form>
				</div>
			</div>
		</div>

		<div id="liveToast" class="toast-container position-fixed top-0 end-0 p-3">
			<div class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
				<div class="d-flex">
					<div class="toast-body">
						<i class="fa-solid fa-bell-ring fa-shake"></i> If you're in our database, your email should arrive shortly!
					</div>
					<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
				</div>
			</div>
		</div>

<script>
let thisPage = 'login';

const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
let today = new Date();
console.log('Today: ' +today);

let myAdmin = typeof(localStorage.getItem('myAdmin')) != "undefined" && localStorage.getItem('myAdmin') !== null ? localStorage.getItem('myAdmin') : 'undefined';
localStorage.setItem("myAdmin", myAdmin);

let myCouncil = typeof(localStorage.getItem('myCouncil')) != "undefined" && localStorage.getItem('myCouncil') !== null ? localStorage.getItem('myCouncil') : 'undefined';
localStorage.setItem("myCouncil", myCouncil);

$('#liveToast').hide();

//! ===========>> FUNCTIONS
function loginAdmin()
{
	let formLogin = $('#formLogin').val();
	let formPass = $('$formPass').val();

	let mydata = {
		formLogin:formLogin,
		formPass:formPass,
	};

	$.ajax({
		url: "jquery/jq_login_admin.php",
		method: "POST",
		dataType: "text",
		data: JSON.stringify(mydata),
		success: function(response) {
			$('#dataDump').html(response);
		},
		error: function(response) {
			console.log('ERROR: ' + response);
		}
	});
}

function resetPassword()
{
	let emailVal = $('#reset_email').val();
	let ipVal = $('#reset_ip').val();

	let mydata = {
		emailVal:emailVal,
		ipVal:ipVal,
	};

	$.ajax({
		url: "jquery/jq_login_reset.php",
		method: "POST",
		dataType: "text",
		data: JSON.stringify(mydata),
		success: function(response) {
			$('#liveToast').show();
		},
		error: function(response) {
			console.log('ERROR: ' + response);
		}
	});
}

//! ===========>> LISTENERS

//? ===========>> document ready <<=============
$(document).ready(function()
{
//! Reset
	$(document).on('click', '#reset_me', function(e)
	{
		e.preventDefault();
		if ($('#reset_password')[0].checkValidity() === false) {
			e.stopPropagation();
		} else {
			console.log('=>>> POST <<<=');
			resetPassword();
			setTimeout(function() {
				$('#modalReset').modal('hide');
			}, 1000);
		}
		$('#reset_password').addClass('was-validated');
	});
//! Reset

//! Login
	$(document).on('click', '#formSubmit', function(e)
	{
		e.preventDefault();
		loginAdmin();
	});
//! Login



});

</script>


<?php
	include "footer.php";
	exit();
?>
