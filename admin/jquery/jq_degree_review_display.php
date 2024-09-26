<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	include 	 $config_form_elements;
	include 	 $config_arrays;
	include 	 $config_functions_custom;

	$db_table 		= 'transcripts';
	$data_results 	= '';
	$var_ID 		= 'dt_ID';
	$var_active 	= 'dt_active';


	echo $data_results;
?>
<div id="bcs" class="calculateMe"><div class="fa-3x"><i class="fa-solid fa-sync fa-spin text-primary"></i> Calculating Bachelor's Progress</div></div>
<div id="mcs" class="calculateMe"><div class="fa-3x"><i class="fa-solid fa-sync fa-spin text-info"></i> Calculating Master's Progress</div></div>
<div id="dcs" class="calculateMe"><div class="fa-3x"><i class="fa-solid fa-sync fa-spin text-success"></i> Calculating Doctorate Progress</div></div>
<div id="xcs" class="calculateMe"><div class="fa-3x"><i class="fa-solid fa-sync fa-spin text-danger"></i> Calculating "Almost" Progress</div></div>


<script>
	function calcDegree(degreeType)
	{
		let postData = {degreeType:degreeType};
		$.ajax({
			url: 		"degree_calculator.php",
			method: 	"POST",
			dataType:	"text",
			data: 		JSON.stringify(postData),
			success:	function(response)
			{
				var returndata = '';
				try
				{
					let degreeTypeInput = degreeType + '_input';
					$('#'+degreeType).html(response);
					$('#'+degreeTypeInput).val(response);
				}
				catch(e)
				{
					alert('Problem');
				}
			},
			error: function(response)
			{
				console.log('PROCESSOR ERROR: ' + response);
			}
		});
	}


	function sendEmail(group)
	{
	// console.log('group: ' +group);
		let bcsInput = $('#bcs_input').val();
	// console.log('bcsInput: ' +bcsInput);
		let mcsInput = $('#mcs_input').val();
	// console.log('mcsInput: ' +mcsInput);
		let myInfo = $('#my_info').val();
	// console.log('myInfo: ' +myInfo);
		let postData = {group:group,myInfo:myInfo,bcsInput:bcsInput,mcsInput:mcsInput};
		$.ajax({
			url: 		"jquery_degree_emails.php",
			method: 	"POST",
			dataType:	"text",
			data: 		JSON.stringify(postData),
			success:	function(response)
			{
				$("#message_box").html(response).fadeIn(2000).fadeOut(3000);
			},
			error: function(response)
			{
				console.log('ERROR: ' + response);
			}
		});


	}
	$(document).ready(function()
	{
			// alert('ready');

		$('.calculateMe').each(function(i, obj)
		{
			calcDegree(obj.id)
		});


		$(document).on("click", '.mail_sender', function(e)
		{
			let group = $(this).attr('id');
			sendEmail(group);
		});
	});
</script>
