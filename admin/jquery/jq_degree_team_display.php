<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	include 	 $config_form_elements;
	include 	 $config_arrays;
	include 	 $config_functions_custom;

	$db_table 		= 'degree_team';
	$data_results 	= '';
	$var_ID 		= 'dt_ID';
	$var_active 	= 'dt_active';

	$display_array = array(
		'noid'	=> '#',
		'dt_name'	=> 'Name',
		'dt_email'	=> 'Email',
		'council_name' 	=> 'Council',
	);

	$col_count = count($display_array);
	$col_count_all = count($display_array) + 1;

	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$admin_user = $mydata['adminUser'];
		$admin_council_select = $mydata['adminCouncilSelect'];
		$active = $mydata['activeSelect'];
		$this_id = $mydata['thisID'];

		$sort_field = '';
		$sort_dir = '';

	$active_sql = $active !== 'all' && strlen($active) > 0 ? "AND LOWER(`" . $var_active . "`) LIKE '" . $active . "'" : "";
	$active_clr = $active == 'yes' ? '' : $data_results .= '<style>.td-text{color:red!important;</style>';

	//! CHECK ADMIN LEVEL ACCESS  ==========================
	$my_admin_level = 100;
	$my_admin_level = getAdminLevel($admin_user);
	//! CHECK ADMIN LEVEL ACCESS  ==========================

	$data_results .= '
		<table class="table table-striped table-bordered table-hover table-sm">
			<thead>
				<tr>
					';
						$x=0;
						foreach( $display_array AS $display_key => $display_value )
						{
							$x++;
							$arrow = $sort_field !== $display_key ? '' : ( $sort_dir == 'ASC' ? '<i class="fa-solid fa-caret-up text-light"></i>' : '<i class="fa-solid fa-caret-down text-primary"></i>' );

							if( strpos('|', $display_value) !== false ) {
								$display_data = explode('|',$display_value);
								$display_term = $display_data[0];
								$display_sort = $display_data[1];
							} else {
								$display_term = $display_value;
								$display_sort = $display_key;
							}

							$sort_opp = $sort_dir == 'ASC' ? 'DESC' : 'ASC';
							$data_results .= '
							<th scope="col" class="sort-table main-clr text-light font-weight-bold text-uppercase hdr ' . $display_key . '" data-sort="' . $display_sort . '|' . $sort_opp . '" nowrap>
									' . $display_term . ' ' . $arrow . '
							</th>
							';
						}
					$data_results .= '
					<th scope="col" class="main-clr text-light font-weight-bold text-uppercase hdr col-md-1">
						Actions
					</th>
				</tr>
			</thead>
			<tbody>
			';


	$sql = "
	SELECT *
	FROM `degree_team`
	WHERE 1=1
	AND `dt_council_ID` = '" . $admin_council_select . "'
	" . $active_sql . "
	ORDER BY `dt_name`
	";
	// $data_results .= $sql;
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;

	if( $cnt > 0 )
	{
		$x=0;
		while( $row = mysqli_fetch_assoc($results) )
		{
			$x++;
			$data_results .= '<tr>';
			foreach( $degree_team_fields_array as $key => $value )
			{
				$$value = $row[$value];
			}
			// 	//! =========== CUSTOM VARIABLES ==========

			$noid = $x;
			$council_name = getCouncilFromID($dt_council_ID);
			// $council_name = $dt_council_ID !== '999' ? getCouncilFromID($dt_council_ID) : getCouncilFromID($council);


			// 	//! =========== CUSTOM VARIABLES ==========
			$center_fields = array('admin_');
			$nowrap_fields = array('full_name');

			foreach( $display_array AS $display_key => $display_value )
			{
				$td_align = (in_array($display_key, $center_fields) !== false) ? 'text-center' : '';
				$nowrap =  (in_array($display_key, $nowrap_fields) !== false) ? 'nowrap' : '';
				$data_results .= '<td class="td-text list-text ' . $td_align . ' " ' . $nowrap . '> ' . $$display_key . '</td>';
			}

			/* actionButtons === actionButtons === actionButtons */
			//  && $admin_user !== $admin_user_ID
			$button_set = '';

			if( $my_admin_level >= $level_code )
			{
				$button_set .= '<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Edit"><button id="editItem' . $$var_ID . '" data-info="' . $$var_ID . '" class="btn btn-success btn-sm m-1 list-text text-nowrap edit-item" data-bs-toggle="modal" data-bs-target="#modalAlert"><i class="fas fa-edit list-text text-nowrap" aria-hidden="true"></i> Edit</button></span>&nbsp;';

					$var_active_opp = strtolower($$var_active) == 'yes' ? 'no' : 'yes';
					$active_btn_term = strtolower($$var_active) == 'yes' ? 'deactivate' : 'reactivate';
					$active_btn_clr = strtolower($$var_active) == 'yes' ? 'danger' : 'primary';
					$active_btn_icn = strtolower($$var_active) == 'yes' ? 'fa-trash-can' : 'fa-recycle';
					$button_set .= '<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="' . ucfirst($active_btn_term) . '"><button type="button" class="btn btn-' . $active_btn_clr . ' btn-sm list-text text-nowrap ' . $active_btn_term . '-item" id="' . $active_btn_term . 'Item' . $$var_ID . '" data-info="' . $$var_ID . '" data-idfield="' . $var_ID . '" data-table="' . $db_table . '" data-field="' . $var_active . '" data-value="' . $var_active_opp . '"  data-bs-toggle="modal" data-bs-target="#modalAlert"><i class="fa-solid ' . $active_btn_icn . ' list-text text-nowrap"></i></button></span>&nbsp;';

			}
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
