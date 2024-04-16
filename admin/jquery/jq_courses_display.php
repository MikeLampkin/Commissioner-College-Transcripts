<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/functions.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/query.php';
	// include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/form_elements.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/arrays.php';

	include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/functions_custom.php';

	$db_table 		= 'courses';
	$data_results 	= '';
	$var_ID 		= 'course_ID';
	$var_active 	= 'course_active';

	$display_array = array(
		'course_code'	=> 'Code',
		'course_type'	=> 'Type',
		'course_name'	=> 'Name',
		'course_number'	=> 'Number',
		'course_number_ext'	=> 'Ext',
		'council_name' 	=> 'Council',
	);

	$col_count = count($display_array);
	$col_count_all = count($display_array) + 1;

	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$admin_user = $mydata['adminUser'];
		$council = $mydata['councilSelect'];
		$pg_active = $mydata['pgActive'];
		$this_id = $mydata['thisID'];

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

	$data_results .= '<div class="row">';
	$data_results .= '<div class="col-md-9">';

	for( $loop=0; $loop<2; $loop++ )
	{
		$council = $loop == 0 ? $council : 999;
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
			FROM `courses`
			WHERE 1=1
			AND `course_council_ID` = '" . $council . "'
			AND `course_active` = '" . $pg_active . "'
			ORDER BY `course_code_ID`
			";
			// $data_results .= $sql;
			$results = mysqli_query($con,$sql);
			$cnt = mysqli_num_rows($results) ?? 0;

			if( $cnt > 0 )
			{
				while( $row = mysqli_fetch_assoc($results) )
				{
					$data_results .= '<tr>';
					foreach( $courses_fields_array as $key => $value )
					{
						$$value = $row[$value];
					}
				// 	//! =========== CUSTOM VARIABLES ==========

					$council_name = getCouncilFromID($course_council_ID);
					// $council_name = $course_council_ID !== '999' ? getCouncilFromID($course_council_ID) : getCouncilFromID($council);


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

					if( $course_council_ID !== '999' )
					{
						$button_set .= '<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Edit"><button id="editItem' . $$var_ID . '" data-info="' . $$var_ID . '" class="btn btn-xs btn-success m-1 list-text text-nowrap edit-item" data-bs-toggle="modal" data-bs-target="#modalAlert"><i class="fas fa-edit list-text text-nowrap" aria-hidden="true"></i> Edit</a></button></span>&nbsp;';

						if( strtolower($$var_active) !== 'no' )
						{
						$button_set .= '<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Deactivate"><button type="button" class="btn btn-danger btn-xs list-text text-nowrap deactivate-item" id="deactivateItem' . $$var_ID . '" data-info="' . $$var_ID . '" data-idfield="' . $var_ID . '" data-table="' . $db_table . '" data-field="' . $var_active . '" data-value="no"  data-bs-toggle="modal" data-bs-target="#modalAlert"><i class="fa-solid fa-trash-can list-text text-nowrap"></i></a></button></span>&nbsp;';
						}
						else
						{
							$button_set .= '<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Reactivate"><button type="button" class="btn btn-primary btn-xs list-text text-nowrap reactivate-item" id="reactivateItem' . $$var_ID . '" data-info="' . $$var_ID . '" data-idfield="' . $var_ID . '" data-table="' . $db_table . '" data-field="' . $var_active . '" data-value="yes"  data-bs-toggle="modal" data-bs-target="#modalAlert"><i class="fa-solid fa-recycle list-text text-nowrap"></i></a></button></span>&nbsp;';
						}
					}
					else
					{
						$button_set .= '<em>No edit.</em>';
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
	}

	$data_results .= '</div>';
	$data_results .= '<div class="col-md-3">';
	$data_results .= '
<h5> Associate Courses (prefix: 0xxx)</h5>
<ul>
	<li> 101—199 National courses </li>
	<li> 201—299 Local council courses </li>
</ul>

<h5> Bachelors Courses (prefix: 1xxx)</h5>
<ul>
	<li> 101—199 National courses </li>
	<li> 201—299 Local council courses </li>
</ul>

<h5> Masters Courses (prefix: 2xxx) </h5>
<ul>
	<li> 301—399 National courses </li>
	<li> 401—499 Local council courses </li>
</ul>

<h5> Doctorate Courses (prefix: 3xxx) </h5>
<ul>
	<li> 501—599 National courses </li>
	<li> 601—699 Local council courses </li>
</ul>

<h5> Continuing Education (prefix: 9xxx) </h5>
<ul>
	<li> 701—799 National courses </li>
	<li> 801—899 Local council courses </li>
</ul>
	';
	$data_results .= '</div>';
	$data_results .= '</div>';

	echo $data_results;
?>
