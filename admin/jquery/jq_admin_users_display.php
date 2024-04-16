<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/functions.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/query.php';
	// include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/form_elements.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/arrays.php';

	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/functions_custom.php';

	$db_table 		= 'admin_users';
	$data_results 	= '';
	$var_ID 		= 'admin_ID';
	$var_active 	= 'admin_active';

	$display_array = array(
		'full_name'		=> 'Name',
		'user_email' 	=> 'Email',
		'council_home' 	=> 'Home Council',
		'council_admin' => 'Admin Councils',
	);

	$col_count = count($display_array);
	$col_count_all = count($display_array) + 1;

	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$admin_user = $mydata['adminUser'];
		$council = $mydata['adminCouncilSelect'];
		$pg_active = $mydata['pgActive'];

		$sort_field = '';
		$sort_dir = '';

	$active_sql = $pg_active !== 'no' ? "AND `" . $var_active . "` = 'yes'" : "AND `" . $var_active . "` = 'no'";

	//! CHECK ADMIN LEVEL ACCESS  ==========================
	$my_admin_level = 100;
	$my_admin_level = getAdminLevel($admin_user);
	$my_admin_council_ID = getAdminCouncilID($admin_user);
	//! CHECK ADMIN LEVEL ACCESS  ==========================
	// echo 'You are admin for ' . $my_admin_council_ID . '<br> ';
	// echo 'Your admin level ' . $my_admin_level . '<br> ';

	$admin_level_array = array();
	$levels_sql = "
	SELECT *
	FROM `admin_levels`
	ORDER BY `level_code`
	";
	$levels_results = mysqli_query($con,$levels_sql);
	$levels_cnt = mysqli_num_rows($levels_results) ?? 0;
	if( $levels_cnt > 0 )
	{
		while( $levels_row = mysqli_fetch_assoc($levels_results) )
		{
			foreach($admin_levels_fields_array as $field_value)
			{
				$$field_value = $levels_row[$field_value];
			}
			$admin_level_array[$level_code] = $level_name . '|' . $level_desc;
		}
	}

foreach( $admin_level_array AS $level_code => $level_data )
{
	$level_data_raw = explode('|',$level_data);
	$level_name = $level_data_raw[0];
	$level_desc = $level_data_raw[1];
	$level_icon = getAdminLevelIcon($level_code);

	$data_results .= '<div class="col-md-12">';
	$council_ID_phrase = $council !== '9999' ? 'for ' . getCouncilFromID($council) : 'for ALL Councils';

	$data_results .= '<span class="h5">' . $level_icon . ' ' . $level_desc . ' ' . $council_ID_phrase . '</span>';

	// //# Build an array to be sorted
	$admin_users_array = array();

	// $council_sql = $admin_council_ID == '9999' && strlen($council) > 0 ? "AND LOWER(`user_council_ID`) LIKE '" . $council . "'" : "AND LOWER(`user_council_ID`) = '" . $council . "'";
	$council_sql = '';
	$council_sql = $council !== 'all' ?  "AND (`admin_council_ID` LIKE '" . $council . "' OR `admin_council_ID` LIKE '9999')" : $council_sql;

	$admin_users_sql = "
	SELECT *
	FROM `admin_users`
	WHERE 1=1
	AND `admin_level` = '" . $level_code . "'
	" . $council_sql . "
	" . $super_council_sql . "
	";
	// echo '<br >' . $admin_users_sql . '<br >';
	$admin_users_results = mysqli_query($con,$admin_users_sql);
	$admin_users_cnt = mysqli_num_rows($admin_users_results) ?? 0;
	if( $admin_users_cnt > 0 )
	{
		while( $row = mysqli_fetch_assoc($admin_users_results) )
		{
			foreach( $admin_users_fields_array as $key => $value )
			{
				$$value = $row[$value];
			}
			$admin_users_array[$admin_user_ID] = userFullnameListFromID($admin_user_ID);
			asort($admin_users_array);
		}
	}

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



	if ( $admin_users_cnt > 0 )
	{
		foreach( $admin_users_array AS $admin_key => $admin_value )
		{
			$sql = "
			SELECT *
			FROM `admin_users`
				JOIN users
				ON admin_users.admin_user_ID = users.user_ID
			WHERE `admin_user_ID` = '" . $admin_key . "'
			";
			// $data_results .= $sql;
			$results = mysqli_query($con,$sql);
			$cnt = mysqli_num_rows($results) ?? 0;

			while( $row = mysqli_fetch_assoc($results) )
			{
					$data_results .= '<tr>';
					foreach( $admin_users_fields_array as $key => $value )
					{
						$$value = $row[$value];
					}
					foreach( $users_fields_array as $key => $value )
					{
						$$value = $row[$value];
					}
				// 	//! =========== CUSTOM VARIABLES ==========

					$full_name = userFullnameListFromID($user_ID);
					$council_home = getCouncilFromID($user_council_ID);

					$council_admin = $admin_council_ID == '9999' ? 'All Councils' : getCouncilFromID($admin_council_ID);
				// 	//! =========== CUSTOM VARIABLES ==========
					$center_fields = array('admin_');
					$nowrap_fields = array('full_name');

					foreach( $display_array AS $display_key => $display_value )
					{
						$td_align = (in_array($display_key, $center_fields) !== false) ? 'text-center' : '';
						$nowrap =  (in_array($display_key, $nowrap_fields) !== false) ? 'nowrap' : '';
						$data_results .= '<td class="list-text ' . $td_align . ' " ' . $nowrap . '> ' . $$display_key . '</td>';
					}

					/* actionButtons === actionButtons === actionButtons */
					$button_set = '';

					$button_set .= '<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Edit"><button id="editItem' . $$var_ID . '" data-info="' . $$var_ID . '" class="btn btn-xs btn-success m-1 list-text text-nowrap edit-item" data-bs-toggle="modal" data-bs-target="#modalAlert"><i class="fas fa-edit list-text text-nowrap" aria-hidden="true"></i> Edit</a></button></span>&nbsp;';

					if( strtolower($$var_active) !== 'no' )
					{
					$button_set .= '<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Deactivate"><button type="button" class="btn btn-danger btn-xs list-text text-nowrap deactivate-item" id="deactivateItem' . $$var_ID . '" data-info="' . $$var_ID . '" data-idfield="' . $var_ID . '" data-table="' . $db_table . '" data-field="' . $var_active . '" data-value="no"  data-bs-toggle="modal" data-bs-target="#modalAlert"><i class="fa-solid fa-trash-can list-text text-nowrap"></i></a></button></span>&nbsp;';
					}
					else
					{
						$button_set .= '<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Reactivate"><button type="button" class="btn btn-primary btn-xs list-text text-nowrap reactivate-item" id="reactivateItem' . $$var_ID . '" data-info="' . $$var_ID . '" data-idfield="' . $var_ID . '" data-table="' . $db_table . '" data-field="' . $var_active . '" data-value="yes"  data-bs-toggle="modal" data-bs-target="#modalAlert"><i class="fa-solid fa-recycle list-text text-nowrap"></i></a></button></span>&nbsp;';
					}

					$data_results .=   '<td class="list-text text-nowrap text-end" nowrap>' . $button_set . '</td>';
					/* actionButtons === actionButtons === actionButtons */

					$data_results .= '</tr>';
			}
		}
	}
	else
	{
		$data_results .= '<tr><td colspan="' . $col_count_all . '">NO DATA</td>';
	}
	$data_results .= '</tbody></table>';

	$data_results .= '</div>';
}

	echo $data_results;
?>
