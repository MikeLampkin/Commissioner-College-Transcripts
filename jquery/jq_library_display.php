<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	// include 	 $config_form_elements;
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
		$my_council = $mydata['myCouncil'];

		// $search_terms = $mydata['searchTerms'];

		$active = 'yes';
		$active_sql = "AND LOWER(`" . $var_active . "`) LIKE '" . $active . "'";
		$search_sql= '';

		$sort_sql = '';

		$limit = '';
		$limit_sql =  '';

		$offset_sql = '';
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
	// $data_results .=  $total_sql . '<hr />';
	$total_results = mysqli_query($con,$total_sql);
	$total_cnt = mysqli_num_rows($total_results) ?? 0;

	$data_results .= '<input type="hidden" id="totalCnt" value="' . $total_cnt . '">';
	$data_results .= '<div class="row">';
		$data_results .= '<div class="col-md-6">';
			$plural = $total_cnt > 1 ? 's' : '';
			$data_results .= 'There are <strong>' . $total_cnt . '</strong> item' . $plural . ' on this report. <br />';
		$data_results .= '</div>';
	$data_results .= '</div>';

	$sql = "
	SELECT *
	FROM `" . $db_table . "`
		JOIN `users`
		ON `thesis_user_ID` = `user_ID`
	WHERE 1=1
	" . $addl_sql . "
	" . $search_sql . "
	" . $active_sql . "

	ORDER BY `user_last_name`, `user_first_name`
	";
	// $data_results .=  nl2br($sql) . '<br />';
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results);

	//# ======= start output ======================================================================
	$col_count = count($display_array)+1;

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
							// $col_percent = $x == 1 ? '5' : 90/$col_count;
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
				$data_results .= '<td class="list-text ' . $td_align . ' " ' . $nowrap . '> ' . $$display_key . '</td>';
			}

			$data_results .= '</tr>';
		}
	}
	else
	{
		$data_results .= '<tr><td colspan="' . $col_count . '">NO DATA</td></tr>';
	}
	$data_results .= '</tbody></table>';

echo $data_results;

?>
