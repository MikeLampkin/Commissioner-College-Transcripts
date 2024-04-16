<?php  // ** Lampkin 2024 ** // ?>

<?php
	$page_is_protected = 'no';
	$navshow = 'yes';

	$pg_title = "Welcome";
	$pg_keywords = "";
	$pg_description = "";

	$pg = "home";
	$db_table = 'transcripts';

	require "includes/header.php";

?>
<?php

$visitor_IP = $_SERVER['REMOTE_ADDR'];
$reset_marker = isset($_POST['reset']) ? 'SET' : 'NOTSET';

$my_email 	= 'unset';
$master_code 	= $_GET['reset'] ?? 'unset';
$timestamp_raw  = time();

if( $master_code !== 'unset' )
{
	$master_code 	= urldecode($master_code);
	$master_code_parts = explode('||', $master_code);

	$timestamp_raw = $master_code_parts[0];
	$my_email_raw 	= $master_code_parts[1];
	$my_email 	= unscramble($my_email_raw);
}

$timestamp = date('M d, Y g:i a', $timestamp_raw);

$expiration_raw = strtotime($timestamp . ' + 48 hours');
$expiration = date('M d, Y g:i a', $expiration_raw);

// echo 'my_email: ' . $my_email . '<br />';
// echo 'timestamp: ' . $timestamp . '<br />';
// echo 'expiration: ' . $expiration . '<br />';

$sql = "
SELECT *
FROM `users`
WHERE LOWER(`user_email`) = '" . $my_email . "'
";
$results = mysqli_query($con, $sql);
$results_cnt = '0';
@$results_cnt = mysqli_num_rows($results);

if ($results_cnt <= 0)
{
	echo '<h3>The reset code that you entered is incorrect. Please enter the code from your reset email or request a new password again.';

} else {
	while ($row = mysqli_fetch_assoc($results))
	{
		foreach ($users_fields_array as $field_key => $field_value)
		{
			$$field_value = $row[$field_value];
		}
	}


?>

<?php
	if( $master_code == 'unset' )
	{
?>
		<form class="nice-form col-md-6 mx-auto" id="find_user" method="POST" enctype="multipart/form-data">
			<div class="card">
				<div class="card-header">
					<strong> Hello  </strong>
					<br /> Please enter the code provided in your email.
					<br />
					<div class="text-right xs">If your code is wrong, no further action will be taken.</div>
				</div>
				<div class="card-body">
					<div class="form-group">
						<label for="user_email"> Code </label>
						<input type="text" class="form-control" name="user_code" id="user_code" placeholder="" maxlength=""  required />
					</div>
				</div>

				<div class="card-footer text-center">
					<button type="submit" class="btn btn-success" id="form_user_submit" role="button" data-dismiss="modal">Submit Code</button>
				</div>

			</div>
		</form>
<?php
	} else {
		if( $timestamp_raw < $expiration_raw )
		{
?>
		<div id="display">
			<form class="nice-form col-md-6 mx-auto" id="pass_reset" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="user_email" id="user_email" value="<?php echo $user_email; ?>">
				<div class="card">
					<div class="card-header">
						<strong> Hello <?php echo $user_first_name; ?>. </strong>
						<br /> Please enter the new password for your account (<?php echo $user_email; ?>).
						<br />
						<div class="text-right xs">This link will expire on <?php echo $expiration; ?>.</div>
					</div>
					<div class="card-body">

						<div class="form-group">
							<label for="new_passwordA" class="required"> New Password </label><span class="col-md-8 text-right" id="pwrd_response" style="font-size:11px;"></span>
							<input type="password" class="form-control" name="new_passwordA" id="new_passwordA" placeholder="" tabindex="1" maxlength="" onKeyUp="validatePassword('new_passwordA')" required />
						</div>
						<div class="form-group">
							<label for="new_passwordB"> Password again </label><span class="col-md-8 text-right" id="compare_response" style="font-size:11px;"></span>
							<input type="password" class="form-control" name="new_passwordB" id="new_passwordB" placeholder="" tabindex="2" maxlength="" onKeyUp="compareFields('new_passwordB','new_passwordA')" required />
						</div>

						<div class="form-check form-group">
							<input class="form-check-input" type="checkbox" id="showPass" onchange="showAllPasswords('new_passwordB','new_passwordA')" />
							<label class="form-check-label" for="showPass"> Show password </label>
						</div>
					</div>

					<div class="card-footer text-center">
						<button type="submit" class="btn btn-success" id="form_data_submit" role="button" data-dismiss="modal">Save New Password</button>
					</div>

				</div>
			</form>
		</div>
<?php
		} // timestamp
?>
<?php
	} // master_code

} // results_cnt
?>

<script>

let timeMark = Date.now();

	function resetPassword() {
		let email = $('#user_email').val();
		let data = $('#new_passwordA').val();

		let mydata = {
			email:email,
			data:data
		};

		console.log('email: ' +email);
		console.log('data: ' +data);

		$.ajax({
			url: "jquery/jq_password_reset.php?"+timeMark,
			method: "POST",
			dataType: "text",
			data: JSON.stringify(mydata),
			success: function(response) {
				let trimResponse = response.trim();
					// $('#dataDump').html(trimResponse);

				if( trimResponse = 'success' )
				{
					let thisMsg = '<div class="alert alert-success mx-auto"><span class="text-success fw-bold mx-auto"><span class="h5"> <i class="fa-regular fa-face-smile-hearts text-danger"></i> SUCCESS!</span> Your information has been recorded. Please visit our <a href="/admin">admin page</a> to login again.</span></div>';
					$('#display').html(thisMsg);
				}
				else
				{
					$('#display').html(trimResponse);
				}
			},
			error: function(response) {
				console.log('ERROR: ' + response);
			}
		});
	}

	//? ===========>> document ready <<=============
	//? ===========>> document ready <<=============
	//? ===========>> document ready <<=============
	$(document).ready(function()
	{

		//! ===========>> LISTENERS
		//! ===========>> LISTENERS
		//! ===========>> LISTENERS

		//! ===========>> submitCode
		$(document).on("click", '#form_user_submit', function(e) {
			e.preventDefault();
			let code = $('#user_code').val();
			console.log(code);
			window.location.href ='password-reset?reset='+code;
		});
		//! ===========>> submitCode


		//! ===========>> submitPassword
		$(document).on("click", '#form_data_submit', function(e) {
			e.preventDefault();
			resetPassword();
		});
		//! ===========>> submitPassword


	});
</script>

<?php  // ** Lampkin 2024 ** // ?>
<?php include "includes/footer.php"; ?>
