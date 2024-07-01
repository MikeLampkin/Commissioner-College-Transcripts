<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	include 	 $config_form_elements;
	include 	 $config_arrays;
	include 	 $config_functions_custom;

	$db_table 		= 'deans';
	$data_results 	= '';
	$var_ID 		= 'dean_ID';
	$var_active 	= 'dean_active';

	$display_array = array(
		'council_name' 	=> 'Council',
		'dean_bcs'	=> 'BCS',
		'dean_bcs'	=> 'MCS',
		'dean_bcs'	=> 'DCS',
		'dean_bcs'	=> 'Registrar 1',
		'dean_bcs'	=> 'Registrar 2',
		'dean_bcs'	=> 'Registrar 3',
	);

	$col_count = count($display_array);
	$col_count_all = count($display_array) + 1;

	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$admin_user = $mydata['adminUser'];
		$council = $mydata['councilSelect'];

		$sort_field = '';
		$sort_dir = '';

	//! CHECK ADMIN LEVEL ACCESS  ==========================
	$my_admin_level = 100;
	$my_admin_level = getAdminLevel($admin_user);
	$my_admin_council_ID = getAdminCouncilID($admin_user);
	//! CHECK ADMIN LEVEL ACCESS  ==========================
	// echo 'You are admin for ' . $my_admin_council_ID . '<br> ';
	// echo 'Your admin level ' . $my_admin_level . '<br> ';

	$data_results .= '
		<table class="table table-striped table-bordered table-hover table-sm">
			<thead>
				<tr>
					<th scope="col" class="main-clr text-light font-weight-bold text-uppercase hdr col-md-1">
						Title
					</th>
					<th scope="col" class="main-clr text-light font-weight-bold text-uppercase hdr col-md-1">
						Name
					</th>
					<th scope="col" class="main-clr text-light font-weight-bold text-uppercase hdr col-md-1">
						Email
					</th>
				</tr>
			</thead>
			<tbody>
			';


	$sql = "
	SELECT *
	FROM `deans`
	WHERE 1=1
	AND `dean_council_ID` = '" . $council . "'
	";
	$data_results .= $sql;
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;

	if( $cnt > 0 )
	{
		while( $row = mysqli_fetch_assoc($results) )
		{
			$data_results .= '<tr>';
			foreach( $deans_fields_array as $key => $value )
			{
				$$value = $row[$value];
			}
		// 	//! =========== CUSTOM VARIABLES ==========

			$council_name = getCouncilFromID($dean_council_ID);
			// $dean_name = fullNameList($user_prefix,$user_first_name,$user_nick_name,$user_middle_name,$user_last_name,$user_suffix);


			// 	//! =========== CUSTOM VARIABLES ==========

			foreach( $display_array AS $display_key => $display_value )
			{
				$td_align = (in_array($display_key, $center_fields) !== false) ? 'text-center' : '';
				$nowrap =  (in_array($display_key, $nowrap_fields) !== false) ? 'nowrap' : '';
				$data_results .= '<td class="list-text ' . $td_align . ' " ' . $nowrap . '> ' . $$display_key . '</td>';
			}

			/* actionButtons === actionButtons === actionButtons */
			$button_set = '';

			$button_set .= '<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Edit"><button id="editItem' . $$var_ID . '" data-info="' . $$var_ID . '" class="btn btn-xs btn-success m-1 list-text text-nowrap edit-item" data-bs-toggle="modal" data-bs-target="#modalAlert"><i class="fas fa-edit list-text text-nowrap" aria-hidden="true"></i> Edit</a></button></span>&nbsp;';


			$data_results .=   '<td class="list-text text-nowrap text-end" nowrap>' . $button_set . '</td>';
			/* actionButtons === actionButtons === actionButtons */

			$data_results .= '</tr>';
		}
	}
	else
	{
		$data_results .= '<tr><td colspan="' . $col_count_all . '">NO DATA</td>';
	}
	$data_results .= '</tbody></table>';


	echo $data_results;
?>
