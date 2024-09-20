<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	include 	 $config_form_elements;
	include 	 $config_arrays;
	include 	 $config_functions_custom;

	$db_table 		= 'users';
	$data_results = $data_results_intro = $data_results_table = '';
	$var_ID 		= 'user_ID';
	$var_active 	= 'user_active';
	$default_sort 	= 'user_last_name';

	$fields_array 	= $users_fields_array;
	$display_array = array(
		'user_bsa_ID' => 'BSA ID',
		// 'bsa_marker' => '<i class="fa-solid fa-user-check"></i>',
		'user_name_details' => 'Name',

		'last_ccs' => '<small>Last<br></small> CCS',
		// 'user_last_bsa_reg' => '<small>Last<br></small> BSA',

		'all_count_cnt' => '#courses',

			// 'acs_count' => '# ACS',
			// 'bcs_count' => '# BCS',
			// 'mcs_count' => '# MCS',
			// 'dcs_count' => '# DCS',
			// 'ced_count' => '# CED',

		'user_district_ID' => 'District',
		// 'user_positions' => 'Positions',
		'email_phone' => 'Phone / Email',
		'user_notes_public' => 'Notes',
	);

	$search_fields_array = array(
		0 => 'user_first_name',
		1 => 'user_last_name',
		2 => 'user_middle_name',
		3 => 'user_nick_name',
	);

	$col_count = count($display_array);
	$col_count_all = count($display_array) + 1;

	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$admin_user = $mydata['adminUser'];
		$admin_council_select = $mydata['adminCouncilSelect'];
		$search_terms = $mydata['searchTerms'];

		$status = $mydata['statusSelect'];
		$deceased = $mydata['deceasedSelect'];
		$active = $mydata['dataSelect'];

		$limit = $mydata['limitNum'];
		$pgnum = $mydata['pgNum'];
		$sort_data = $mydata['pgSort'];
		$pg_name = $mydata['thisPage'];

	//# Build the query elements ===========================

	$status_sql = $status !== 'all' && strlen($status) > 0 ? "AND LOWER(`user_status`) LIKE '" . $status . "'" : "";
	$deceased_sql = $deceased !== 'all' && strlen($deceased) > 0 ? "AND LOWER(`user_deceased`) LIKE '" . $deceased . "'" : "";
	$active_sql = $active !== 'all' && strlen($active) > 0 ? "AND LOWER(`" . $var_active . "`) LIKE '" . $active . "'" : "";

	$limit_sql = $limit < 0 ? "LIMIT 10" : "LIMIT " . $limit;
		$offset = ( $pgnum > 1 ) ? ($pgnum - 1) * $limit : 0;
		$offset_sql = "OFFSET " . $offset;

	$sort_field = $default_sort;
	$sort_dir = 'ASC';
		if( strpos(strtolower($sort_data),'|') !== false )
		{
			$sort_array = explode('|',$sort_data);
			$sort_field = $sort_array[0];
			$sort_dir = $sort_array[1];
		}
	$sort_sql = "ORDER BY `" . $sort_field . "` " . $sort_dir . "";
	//# Build the query elements ===========================


	//# search --- v. Feb 12 2024 ==========================
	$search_array = explode(',',$search_terms);
	$search_sql = '';
	foreach( $search_array AS $key => $value )
	{
		$conjunction = strpos($value, '-') !== false ? 'NOT LIKE' : 'LIKE';
		$clean_value = cleanData($value);
		$clean_value = cleanName($clean_value);
		foreach( $search_fields_array AS $field_key => $field_array )
		{
			if( !empty($clean_value) )
			{
				$search_sql .= "AND `" . $field_array . "` " . $conjunction . " '" . $clean_value . "'";
			}
		}
	}
	if ( strlen(trim($search_terms)) >= 2 )
	{
		$search_terms_array = explode(',',$search_terms);
		$search_sql = "AND (";
		$xx=0;
		foreach($search_terms_array AS $search_item)
		{
			$xx++;
			$conjunction = strpos($search_item, '-') !== false ? 'NOT LIKE' : 'LIKE';
			// $search_item = cleanData($search_item);
			// $search_item = cleanName($search_item);
			if ( strlen(trim($search_item)) >= 1 )
			{
				$xy=0;
				foreach ( $search_fields_array AS $key => $value )
				{
					$xy++;
					$search_sql .= "
					LOWER(`" . $value . "`) " . $conjunction . " '%" . strtolower(trim($search_item)) . "%'";
					if( $xy < count($search_fields_array) ) { $search_sql .= " OR "; }
				}
			}
			if( $xx < count($search_terms_array) ) { $search_sql .= " OR "; }
		}
		$search_sql .= ' )';
	}
	//# search =============================================

	//! CHECK ADMIN LEVEL ACCESS  ==========================
	$my_admin_level = 100;
	$my_admin_level = getAdminLevel($admin_user);
	//! CHECK ADMIN LEVEL ACCESS  ==========================


	//# Build queries ==========================
	$addl_sql = $status_sql . '
	'
	. $deceased_sql;

	$total_sql = "
	SELECT *
	FROM `users`
	WHERE 1=1
	AND LOWER(`user_council_ID`) LIKE '" . $admin_council_select . "'
	" . $addl_sql . "
	" . $search_sql . "
	" . $active_sql . "
	";
	// $data_results_intro .=  $total_sql . '<hr />';
	$total_results = mysqli_query($con,$total_sql);
	$total_cnt = mysqli_num_rows($total_results) ?? 0;

	$data_results_intro .= '<input type="hidden" id="totalCnt" value="' . $total_cnt . '">';
	$data_results_intro .= '<div class="row">';
		$data_results_intro .= '<div class="col-md-6">';
			$plural = $total_cnt > 1 ? 's' : '';
			$data_results_intro .= 'There are <strong>' . $total_cnt . '</strong> item' . $plural . ' on this report. <br />';
			$data_results_intro .= 'Displaying: ';

			$patch = getCouncilPatch($admin_council_select);
			$council_name = getCouncilFromID($admin_council_select);
			$council_image = file_exists('/var/www/html/img/img_councils/' . $patch . '') !== false ? '<img src="../img/img_councils/' . $patch . '" id="council_strip" title="' . $council_name . ' Council Strip" class="council-strip">' : '<img src="../img/img_councils/generic.png" id="council_strip" title="Council Strip" class="council-strip">';

			$council_msg = $council_image . ' ' . $council_name . ' ' ;
			$data_results_intro .= ' <strong> Council:  <span class="text-success">' . $council_msg . '</span></strong> ';
			$deceased_msg = $deceased == 'yes' ? 'Deceased' : 'Living';
			$data_results_intro .= '| <strong> Deceased:  <span class="text-success">' . $deceased_msg . '</span></strong> ';
			$status_msg = ucfirst($status);
			$data_results_intro .= '| <strong> Status:  <span class="text-primary">' . $status_msg . '</span></strong> ';
			// $active_msg = $active == 'yes' ? 'Live' : 'Archived';
			$active_msg = ucfirst($active);
			$data_results_intro .= '| <strong> Live Data:  <span class="text-info">' . $active_msg . '</span></strong> ';
	// $data_results_intro .= 'items. ';
		$data_results_intro .= '</div>';
		$data_results_intro .= '<div class="col-md-6 text-end">';
		$data_results_intro .= 'Page: <strong>' . $pgnum . '</strong> of ' . ceil($total_cnt/$limit);
		$data_results_intro .= '</div>';
	$data_results_intro .= '</div>';



	$sql = "
	SELECT *
	FROM `users`
	WHERE 1=1
	" . $addl_sql . "
	" . $search_sql . "
	" . $active_sql . "

	" . $sort_sql . "
	" . $limit_sql . "
	" . $offset_sql . "
	";
	// $data_results_intro .=  nl2br($sql) . '<br />';
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results);



	//# ======= start output ======================================================================
	$col_cnt = count($display_array)+1;

	$center_fields = array('bsa_marker');
	$nowrap_fields = array('po_number');

	$data_results_table .= '
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
							// $data_results_table .= '<td class="list-text ' . $td_align . ' " ' . $nowrap . '> ' . $$display_key . '</td>';

							$data_results_table .= '
							<th scope="col" class="sort-table main-clr font-weight-bold text-uppercase hdr ' . $display_key . ' ' . $td_align . ' " data-sort="' . $display_sort . '|' . $sort_opp . '" ' . $nowrap . '>
									' . $display_term . ' ' . $arrow . '
							</th>
							';
						}
					$data_results_table .= '
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
			$data_results_table .= '<tr>';
			foreach( $fields_array as $key => $value )
			{
				$$value = $row[$value];
			}


			//! =========== CUSTOM VARIABLES ==========
			// 'user_bsa_ID' => 'BSA ID',
			// 'bsa_marker' => '<i class="fa-solid fa-user-check"></i>',
			// 'user_name_details' => 'Name',

			// 'last_ccs' => '<small>Last<br></small> CCS',
			// 'user_last_bsa_reg' => '<small>Last<br></small> BSA',

			// 'all_count_cnt' => '#courses',

			// 	// 'acs_count' => '# ACS',
			// 	// 'bcs_count' => '# BCS',
			// 	// 'mcs_count' => '# MCS',
			// 	// 'dcs_count' => '# DCS',
			// 	// 'ced_count' => '# CED',

			// 'user_district' => 'District',
			// 'user_positions' => 'Positions',
			// 'email_phone' => 'Phone / Email',
			// 'user_notes_public' => 'Notes',

			$bsa_marker = '';
			$last_ccs = '';
			$all_count_cnt = '';
			$email_phone = '';

			$user_name = fullNameList($user_prefix,$user_first_name,$user_nick_name,$user_middle_name,$user_last_name,$user_suffix);
			$user_name = trim($user_name);
			$user_name_tooltip = str_replace('"', '^', $user_name);
			$user_image = 'no-photo.jpg';
			if ( strlen($user_image) < 4 || !file_exists('../img_users/' . $user_image) ) { $user_image = 'no-photo.jpg'; }

			//! ======== name marker ============
				$name_marker = '';

				//! INACTIVE
				if( $user_status == 'inactive')
				{
					$name_marker = '
					<span data-bs-toggle="tooltip" data-bs-placement="top" class="text-danger" title="Inactive">
						<i class="fa-solid fa-circle-x text-danger list-text-sm align-top"></i>
					</span>
					';
				}
				//! DECEASED
				$deceased_marker = '';
				if( $user_deceased == 'yes')
				{
					$name_marker = '
					<span data-bs-toggle="tooltip" data-bs-placement="top" class="text-danger" title="Deceased">
						<i class="fa-solid fa-dot-circle text-danger list-text-sm align-top"></i>
					</span>
					';
				}

				$is_user_pro = ( $user_pro == 'yes') ? 'yes' : 'no';

				$user_fullname_list = fullNameList($user_prefix,$user_first_name,$user_nick_name,$user_middle_name,$user_last_name,$user_suffix);
				$user_fulladdress = fullAddress($user_address,$user_address2,$user_city,$user_state,$user_zip,'','','');

				$user_phone = ( strlen($user_phone ?? '') > 2 ) ? prettyPhone($user_phone) : $user_phone;
				$user_phone2 = ( strlen($user_phone2 ?? '') > 2 ) ? prettyPhone($user_phone2) : $user_phone2;

				$user_em_contact1_phone = ( strlen($user_em_contact1_phone ?? '') > 2 ) ? prettyPhone($user_em_contact1_phone) : $user_em_contact1_phone;
				$user_em_contact2_phone = ( strlen($user_em_contact2_phone ?? '') > 2 ) ? prettyPhone($user_em_contact2_phone) : $user_em_contact2_phone;

				$user_image = 'no-photo.jpg';
				// if ( strlen($user_image) < 4 || !file_exists('../img_users/' . $user_image) ) { $user_image = 'no-photo.jpg'; }
				$user_name_details = '
				<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View details.">
				<span id="viewItem' . $$var_ID . '" data-info="' . $$var_ID . '" class="list-text text-nowrap view-item" data-bs-toggle="modal" data-bs-target="#details' . $user_ID . '">

				<!-- <a id="viewItem' . $$var_ID . '" data-info="' . $$var_ID . '" class="list-text text-nowrap view-item" data-bs-toggle="modal" data-bs-target="#modalAlert"> -->
					' . $user_fullname_list .'
				</span>
				</span>
				';

				$user_name_details = $user_name_details . ' <span class="xs">' . $name_marker . '</span>';

			//! ======== name marker ============

			// $bsa_marker = ( strlen($user_bsa_ID ?? '') > 2) ? '<i class="fa-solid fa-user-circle mx-auto"></i>' : '<i class="far fa-question-circle"></i>';
			// $bsa_marker = '<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="BSA ID">' . $bsa_marker .'</span>';

			$last_ccs = lastCCS($user_ID);
			$min_ccs = date('Y',strtotime('-5 years'));
			$this_clr = $last_ccs < $min_ccs ? 'text-danger' : ( $last_ccs == 'none'  ? 'text-secondary' : 'text-black');
				$last_ccs = $last_ccs == 'none' ? '<em>' . $last_ccs . '</em>' : $last_ccs;
			$last_ccs =  '<span class="' . $this_clr . ' text-centered" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Last CCS attended.">
			' . $last_ccs . '</span>';

			// $user_email = strlen($user_email) > 4 ? '<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Click to copy."><input type="hidden" id="copyEmail' . $user_ID . '" value="ThIss DaaTaa"><a class="no-deco" onclick="copyToClipboardB(\'copyEmail' . $user_ID . '\')">' . $user_email . '</a></span>' : '';
			// $user_phone = strlen($user_phone) > 4 ? '<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Click to copy."><input type="hidden" id="copyPhone' . $user_ID . '" value="ThIss DaaTaa"><a class="no-deco" onclick="copyToClipboardB(\'copyPhone' . $user_ID . '\')">' . $user_phone . '</a></span>' : '';
			$user_email = strlen($user_email) ? '<small>' . $user_email . '</small>' : '';
			$user_phone = strlen($user_phone) ? '<small>' . $user_phone . '</small>' : '';

			$email_phone = strlen($user_phone) > 4 ?  $user_phone : $email_phone;
			$email_phone = strlen($user_email) > 4 ?  $user_email : $email_phone;
			$email_phone = strlen($user_phone) > 4 && strlen($user_email) > 4 ?  $user_email . '<br />' . $user_phone : $email_phone;

			//! =========== CUSTOM VARIABLES ==========


			foreach( $display_array AS $display_key => $display_value )
			{
				$td_align = (in_array($display_key, $center_fields) !== false) ? 'text-center' : '';
				$nowrap =  (in_array($display_key, $nowrap_fields) !== false) ? 'nowrap' : '';
				$data_results_table .= '<td class="list-text ' . $td_align . ' " ' . $nowrap . '> ' . $$display_key . '</td>';
			}

			/* actionButtons === actionButtons === actionButtons */
			$button_set = '';

			$button_set .= '<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Edit"><button title="Edit" id="editItem' . $$var_ID . '" data-info="' . $$var_ID . '" class="btn btn-xs btn-success m-1 list-text text-nowrap edit-item" data-bs-toggle="modal" data-bs-target="#modalAlert"><i class="fa-solid fa-edit list-text text-nowrap" aria-hidden="true"></i> Edit</a></button></span>&nbsp;';

			$action_buttons_value_array = array(
				'deceased' 	=> 'yes|no',
				'status' 	=> 'active|inactive',
				'pro' 		=> 'yes|no',
				'active' 	=> 'yes|no',
			);
			$action_buttons_tips_array = array(
				'deceased' 	=> 'Deceased|Living',
				'status' 	=> 'Active|Inactive',
				'pro' 		=> 'Pro|Volunteer',
				'active' 	=> 'Click to Delete|Click to Reinstate',
			);
			$action_buttons_icon_array = array(
				'deceased' 	=> 'fa-dot-circle|fa-user-circle',
				'status' 	=> 'fa-circle-check|fa-circle-x',
				'pro' 		=> 'fa-circle-p|fa-circle-v',
				'active' 	=> 'fa-trash-can|fa-recycle',
			);
			$action_buttons_color_array = array(
				'deceased' 	=> 'danger|info',
				'status' 	=> 'warning|danger',
				'pro' 		=> 'lightgray|primary',
				'active' 	=> 'danger|success',
			);
			foreach( $action_buttons_value_array AS $key => $terms )
			{
				$field_key = 'user_' . $key;
				$field_value = $$field_key;
				$val_array = explode('|',$terms);
				$field_value_opp = $field_value !== $val_array[1] ? $val_array[1] : $val_array[0];

				$tips_array = explode('|',$action_buttons_tips_array[$key]);
				$tips = $field_value == $val_array[1] ? $tips_array[1] : $tips_array[0];

				$icon_array = explode('|',$action_buttons_icon_array[$key]);
				$icon = $field_value == $val_array[1] ? $icon_array[1] : $icon_array[0];

				$clr_array = explode('|',$action_buttons_color_array[$key]);
				$clr = $field_value == $val_array[1] ? $clr_array[1] : $clr_array[0];

				$button_set .= '<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="' . $tips . '"><button title="' . $key . '" type="button" class="btn btn-' . $clr . ' btn-xs list-text text-nowrap action-item" id="' . $key . 'Item' . $field_value . '' . $user_ID . '" data-info="' . $$var_ID . '" data-idfield="' . $var_ID . '" data-table="' . $db_table . '" data-field="' . $field_key . '" data-value="' . $field_value_opp . '"><i class="fa-solid ' . $icon . '"></i></a> </button></span>&nbsp;';
			}

			$data_results_table .=   '<td class="list-text text-nowrap text-end" nowrap>' . $button_set . '</td>';
			/* actionButtons === actionButtons === actionButtons */

			$data_results_table .= '</tr>';
		}
	}
	else
	{
		$data_results_table .= '<tr><td colspan="' . $col_count . '">NO DATA</td></tr>';
	}
	$data_results_table .= '</tbody></table>';

$data_results .= $data_results_intro . ' ' . $data_results_table;


$data_results_excel = '';
$data_results_excel .= '<table>';
if ( $cnt > 0 )
{
	$data_results_excel .= '<tr>';
		foreach( $fields_array as $key => $value )
		{
			$data_results_excel .= '<td>';
			$data_results_excel .= $key;
			$data_results_excel .= '</td>';
		}
	$data_results_excel .= '</tr>';
	while( $row = mysqli_fetch_assoc($results) )
	{
		$data_results_excel .= '<tr>';
		foreach( $fields_array as $key => $value )
		{
			$data_results_excel .= '<td>';
			$data_results_excel .= $row[$value];
			$data_results_excel .= '</td>';
		}
		$data_results_excel .= '</tr>';
	}
}
$data_results_excel .= '</table>';
$data_results .=  '<textarea id="copyToClipboard" style="display:none;">' . htmlspecialchars($data_results_excel) . '</textarea>';

echo $data_results;
?>
