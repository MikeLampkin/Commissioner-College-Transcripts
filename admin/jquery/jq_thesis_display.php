<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	include 	 $config_form_elements;
	include 	 $config_arrays;
	include 	 $config_functions_custom;

	$db_table 		= 'thesis';
	$data_results 	= '';
	$var_ID 		= 'thesis_ID';
	$var_active 	= 'thesis_active';

	$fields_array = $db_table . '_fields_array';
	$display_array = array(
		// 'thesis_ID' => 'ID',
		'user_list_name' => 'Name',
		'thesis_title' => 'Title',
		'date_nice' => 'Date',
		'link_file' => 'Thesis',
		'file_size' => 'Size',
		);

	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$admin_user = $mydata['adminUser'];
		$admin_council_select = $mydata['adminCouncilSelect'];
		// $council = $mydata['councilSelect'];
		$search_terms = $mydata['searchTerms'];

		$active = 'yes';
		$active_sql = "AND LOWER(`" . $var_active . "`) LIKE '" . $active . "'";
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
	// $data_results_intro =  $total_sql . '<hr />';
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
			$active_msg = $active == 'yes' ? 'Live' : 'Archived';
			// $active_msg = ucfirst($active);
			// $data_results_intro .= '| <strong> Live Data:  <span class="text-info">' . $active_msg . '</span></strong> ';
		$data_results_intro .= '</div>';
		$data_results_intro .= '<div class="col-md-6 text-end">';
		$data_results_intro .= 'Page: <strong>1</strong> of ' . ceil($total_cnt);
		$data_results_intro .= '</div>';
	$data_results_intro .= '</div>';

	$sql = "
	SELECT *
	FROM `" . $db_table . "`
		JOIN `users`
		ON `thesis_user_ID` = `user_ID`
	WHERE 1=1
	" . $addl_sql . "
	" . $search_sql . "
	" . $active_sql . "

	" . $sort_sql . "
	" . $limit_sql . "
	" . $offset_sql . "
	ORDER BY `user_last_name`, `user_first_name`
	";
	// $data_results_intro .=  nl2br($sql) . '<br />';
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results);

	// //# ======= start output ======================================================================
	$col_cnt = count($display_array)+1;

	$center_fields = array('bsa_marker');
	$nowrap_fields = array('user_list_name','date_nice','link_file','file_size');
	$data_results_table = '';

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
			foreach( $$fields_array as $key => $value )
			{
				$$value = $row[$value];
			}


			//! =========== CUSTOM VARIABLES ==========
			$user_list_name = userFullnameListFromID($thesis_user_ID);
			$user_full_name = urlencode(userFullnameFromID($thesis_user_ID));
			$date_nice = strlen($thesis_date ?? '') > 4 ? date('M D, Y', strtotime($thesis_date)) : '';
			$link_file = file_exists('/var/www/html/thesis/' . $thesis_file) ? '<a href="../thesis/' . $thesis_file . '" download>' . $thesis_file . '</a>' : $thesis_file;

			$file_size = file_exists('/var/www/html/thesis/' . $thesis_file) ? '<small>'. sizeOfFile(filesize('/var/www/html/thesis/' . $thesis_file)) . '</small>' : '0';
			//! =========== CUSTOM VARIABLES ==========


			foreach( $display_array AS $display_key => $display_value )
			{
				$td_align = (in_array($display_key, $center_fields) !== false) ? 'text-center' : '';
				$nowrap =  (in_array($display_key, $nowrap_fields) !== false) ? 'nowrap' : '';
				$data_results_table .= '<td class="list-text ' . $td_align . ' " ' . $nowrap . '> ' . $$display_key . '</td>';
			}

			/* actionButtons === actionButtons === actionButtons */
			$button_set = '';

			$button_set .= '<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Upload"><button title="Upload" id="uploadItem' . $$var_ID . '" data-info="' . $$var_ID . '" data-msg="' . $user_full_name . '" class="btn btn-xs btn-info text-white m-1 list-text text-nowrap upload-item" Xdata-bs-toggle="modal" Xdata-bs-target="#modalAlert"><i class="fa-solid fa-arrow-up-from-square list-text text-nowrap" aria-hidden="true"></i> </button></span>&nbsp;';


			$button_set .= '<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Edit"><button title="Edit" id="editItem' . $$var_ID . '" data-info="' . $$var_ID . '" class="btn btn-xs btn-success m-1 list-text text-nowrap edit-item" data-bs-toggle="modal" data-bs-target="#modalAlert"><i class="fa-solid fa-edit list-text text-nowrap" aria-hidden="true"></i> Edit</button></span>&nbsp;';

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
		foreach( $$fields_array as $key => $value )
		{
			$data_results_excel .= '<td>';
			$data_results_excel .= $key;
			$data_results_excel .= '</td>';
		}
	$data_results_excel .= '</tr>';
	while( $row = mysqli_fetch_assoc($results) )
	{
		$data_results_excel .= '<tr>';
		foreach( $$fields_array as $key => $value )
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
