
<script type="text/javascript">

// (A) PREVENT CONTEXT MENU FROM OPENING
	document.addEventListener(".noselect", (evt) => {
		evt.preventDefault();
	}, false);

	function compareVerify(fieldOne,fieldTwo)
	{
		let field_new = document.getElementById(fieldOne);
		let field_origin = document.getElementById(fieldTwo);

		let compare_response = document.getElementById("compareV_response");

		let text_origin = field_origin.value.trim();
		let text_new = field_new.value.trim();

		if ( text_new !== text_origin )
		{
			matches = false;
		} else {
			matches = true;
		}

		if ( matches )
		{
			compare_response.innerHTML = "<small> (<span style=\"color:green;\"> Verified <i class=\"fas fa-shield-check\"></i></span>)</small>";
				field_new.style.borderColor = "green";
				field_origin.style.borderColor = "green";
				$("#form_data_nosubmit").addClass("hideme");
				$("#form_data_nosubmit_warn").addClass("hideme");
				$("#form_data_submit").removeClass("hideme");
				$('#submit-buttons-box').addClass("bg-forestgreen-soft");
				$('#submit-buttons-box').removeClass("bg-gray-soft");
		} else {
			compare_response.innerHTML = "<small> (<span style=\"color:red;\"> Fields don't match! <i class=\"fas fa-shield\"></i></span>)</small>";
				field_new.style.borderColor = "red";
				field_origin.style.borderColor = "red";
				$("#form_data_nosubmit").removeClass("hideme");
				$("#form_data_nosubmit_warn").removeClass("hideme");
				$("#form_data_submit").addClass("hideme");
				$('#submit-buttons-box').removeClass("bg-forestgreen-soft");
				$('#submit-buttons-box').addClass("bg-gray-soft");
		}
	}
</script>

	<?php
		$rando = randomWord(5);
	?>

		<input type="hidden" name="verify" id="verify" value="<?php echo $rando; ?>">
		<div class="form-group m-0 p-0">
			<span data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="">
				<label for="verify_check" class="col-md-12 px-0 form-label required" id="label_verify_check">Please type the security verification code: <span id="compareV_response"></span></label>
			</span>

			<div class="input-group">
				<span class="input-group-text noselect" id="rando-addon">Type: <strong><span class="noselect" style="user-select:none;"><?php echo $rando; ?></span></strong></span>
				<input type="text" class="form-control verify_check" name="verify_check" id="verify_check" value="" placeholder="" maxlength="5" onkeyup="compareVerify('verify','verify_check')" required>
			</div>
		</div>
