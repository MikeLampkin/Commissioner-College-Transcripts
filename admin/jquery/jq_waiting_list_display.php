<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	include 	 $config_form_elements;
	include 	 $config_arrays;
	include 	 $config_functions_custom;

	$db_table 		= 'waiting_list';
	$data_results 	= '';
	$var_ID 		= 'wl_ID';
	$var_active 	= 'wl_active';

	$fields_array = $db_table . '_fields_array';
	$display_array = array(
		// 'wl_ID' => 'ID',
		'wl_submit_name' => 'Name',
		'wl_submit_email' => 'Email',
		'council' => 'Council',
		'date' => 'Date',
		);

	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$admin_user = $mydata['adminUser'];

		$active = 'yes';
		$search_sql= '';

		$sort_sql = '';

		$limit = '';
		$limit_sql =  '';

		$offset_sql = '';
	foreach( $$fields_array as $key => $value )
	{
		$$value = '';
	}

	//! CHECK ADMIN LEVEL ACCESS  ==========================
	$my_admin_level = 100;
	$my_admin_level = getAdminLevel($admin_user);
	//! CHECK ADMIN LEVEL ACCESS  ==========================

	$message_type_array = array(
		'uap' => 'Unapproved',
		'yes' => 'Approved',
		'no' => 'Hidden',
	);

	foreach( $message_type_array AS $msg_key => $msg_value )
	{

		$active_sql = "AND `" . $var_active . "` LIKE '" . $msg_key . "' ";

		$data_results .= '<h4> ' . $msg_value . ' </h4>';

		//# Build queries ==========================
		$addl_sql = '';

		$total_sql = "
		SELECT *
		FROM `" . $db_table . "`
		WHERE 1=1
		" . $addl_sql . "
		" . $search_sql . "
		" . $active_sql . "
		";
		// $data_results =  $total_sql . '<hr />';
		$total_results = mysqli_query($con,$total_sql);
		$total_cnt = mysqli_num_rows($total_results) ?? 0;

		$sql = "
		SELECT *
		FROM `" . $db_table . "`
		WHERE 1=1
		" . $addl_sql . "
		" . $search_sql . "
		" . $active_sql . "

		" . $sort_sql . "
		" . $limit_sql . "
		" . $offset_sql . "
		";
		// $data_results .=  nl2br($sql) . '<br />';
		$results = mysqli_query($con,$sql);
		$cnt = mysqli_num_rows($results);

		// //# ======= start output ======================================================================
		$col_cnt = count($display_array)+1;

		$center_fields = array('bsa_marker');
		$nowrap_fields = array('user_list_name','date_nice','link_file','file_size');

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

								$display_term = $display_value;
								$display_sort = $display_key;

								if( strpos($display_value,'|') !== false ) {
									$display_data = explode('|',$display_value);
									$display_term = $display_data[0];
									$display_sort = $display_data[1];
								}

								$sort_opp = $sort_dir == 'ASC' ? 'DESC' : 'ASC';
								$col_percent ='';
								// $col_percent = $x == 1 ? '5' : 90/$col_cnt;
								$td_align = (in_array($display_key, $center_fields) !== false) ? 'text-center' : '';
								$nowrap =  (in_array($display_key, $nowrap_fields) !== false) ? 'nowrap' : '';
								// $data_results .= '<td class="list-text ' . $td_align . ' " ' . $nowrap . '> ' . $$display_key . '</td>';

								$data_results .= '
								<th scope="col" class="sort-table main-clr font-weight-bold text-uppercase hdr ' . $display_key . ' ' . $td_align . ' " data-sort="' . $display_sort . '|' . $sort_opp . '" ' . $nowrap . '>
										' . $display_term . ' ' . $arrow . '
								</th>
								';
							}
						$data_results .= '
						<th scope="col" class="main-clr font-weight-bold text-uppercase hdr col-md-1">
							Actions
						</th>
					</tr>
				</thead>
				<tbody>
				';


		if ( $cnt > 0 )
		{
			while( $row = mysqli_fetch_assoc($results) )
			{
				$data_results .= '<tr>';
				foreach( $$fields_array as $key => $value )
				{
					$$value = $row[$value];
				}


				//! =========== CUSTOM VARIABLES ==========


					$council = getCouncilFromID($wl_council_ID);
					$date = date('M d, Y - G:i a', strtotime($wl_first_update));


				//! =========== CUSTOM VARIABLES ==========


				foreach( $display_array AS $display_key => $display_value )
				{
					$td_align = (in_array($display_key, $center_fields) !== false) ? 'text-center' : '';
					$nowrap =  (in_array($display_key, $nowrap_fields) !== false) ? 'nowrap' : '';
					$data_results .= '<td class="list-text ' . $td_align . ' " ' . $nowrap . '> ' . $$display_key . '</td>';
				}

				/* actionButtons === actionButtons === actionButtons */
				$button_set = '';

				$button_set .= '<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Edit"><button title="Edit" id="editItem' . $$var_ID . '" data-info="' . $$var_ID . '" class="btn btn-xs btn-success m-1 list-text text-nowrap edit-item" data-bs-toggle="modal" data-bs-target="#modalAlert"><i class="fa-solid fa-edit list-text text-nowrap" aria-hidden="true"></i> Edit</button></span>&nbsp;';

					$button_uap = $button_yes = $button_no = '';
					if( $wl_active !== 'uap' )
					{
						$button_uap = '<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Unapprove"><button title="Unapprove" data-active="uap" data-info="' . $$var_ID . '" class="btn btn-xs btn-info m-1 list-text text-nowrap act-item"> <i class="fa-solid fa-circle"></i> </button></span>&nbsp;';
					}
					if( $wl_active !== 'yes' )
					{
						$button_yes = '<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Approve"><button title="Approve" data-active="yes" data-info="' . $$var_ID . '" class="btn btn-xs btn-success m-1 list-text text-nowrap act-item"> <i class="fa-solid fa-circle-y"></i> </button></span>&nbsp;';
					}
					if( $wl_active !== 'no' )
					{
						$button_no = '<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Delete"><button title="Delete" data-active="no" data-info="' . $$var_ID . '" class="btn btn-xs btn-danger m-1 list-text text-nowrap act-item"> <i class="fa-solid fa-circle-xmark"></i> </button></span>&nbsp;';
					}

					$button_set .= $button_uap . $button_yes . $button_no;

				$data_results .=   '<td class="list-text text-nowrap text-end" nowrap>' . $button_set . '</td>';
				/* actionButtons === actionButtons === actionButtons */

				$data_results .= '</tr>';
			}
		}
		else
		{
			$data_results .= '<tr><td colspan="' . $col_count . '">NO DATA</td></tr>';
		}
		$data_results .= '</tbody></table>';
	}
// $data_results .=  '<textarea id="copyToClipboard" style="display:none;">' . htmlspecialchars($data_results_excel) . '</textarea>';

echo $data_results;
?>
