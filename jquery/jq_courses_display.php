<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	// include 	 $config_form_elements;
	include 	 $config_arrays;
	include 	 $config_functions_custom;

	$db_table 		= 'courses';
	$data_results 	= '';
	$var_ID 		= 'course_ID';
	$var_active 	= 'course_active';

	$display_array = array(
		'course_type'	=> 'Type',
		'course_no'		=> 'No.',
		'course_name'	=> 'Name',
		'council_name' 	=> 'Council',
	);

	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$my_council = $mydata['myCouncil'];

?>

<!-- // -- Lampkin 2010 - 2024 -- // -->
	<div class="row mb-3">
		<div class="col-4 text-start"><span id="changeCouncil" class="" data-bs-toggle="modal" data-bs-target="#modalAlert"><span data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Click to change your current council."><i class="fas fa-location-circle" aria-hidden="true"></i> Your current council: <strong><span id="showMyCouncil"></span></strong></span></div>
		<div class="col-4 text-center"><span id="alertMsg"></span></div>
		<div class="col-4 text-end"><span class="btn btn-primary btn-xs" id="reloadPage"><i class="fa-solid fa-arrows-rotate" id="reloadIcon"></i> Refresh Page</span></div>
	</div>


	<div class="alert alert-secondary">
		<h5>A list of courses which have been offered currently and in the past. For more information about courses, please visit the National BSA web site at <a href="https://www.scouting.org/commissioners" target="_blank">https://www.scouting.org/commissioners</a> </h5>
	</div>

	<div class="row">
		<div class="col-md-9">
			<table class="table table-striped table-hover">
				<thead>
					<tr class="">
						<!-- <th class="text-center bg-dark text-white fw-bold">ID</th> -->
						<!-- <th class="text-center bg-dark text-white fw-bold">Code</th> -->
						<th class="text-center bg-dark text-white fw-bold">Type</th>
						<th class="text-center bg-dark text-white fw-bold">No.</th>
						<th class="text-center bg-dark text-white fw-bold">Name</th>
						<th class="text-center bg-dark text-white fw-bold">Council</th>
					</tr>
				</thead>

				<tbody>
					<?php
					$fields_array = query('getColumns', 'courses', $db_name, '', '', '');

					$sql = "
					SELECT *
					FROM `courses`
					WHERE 1=1
					AND LOWER(`course_active`) LIKE 'yes'
					AND
					(
						`course_council_ID` LIKE '" . $my_council . "'
						OR
						`course_council_ID` LIKE '999'
					)
					ORDER BY `course_code`, `course_number`
					";
					$results = mysqli_query($con,$sql);
					while ($row = mysqli_fetch_assoc($results)) {
						foreach( $fields_array as $key => $value ) {
							$$value = $row[$value];
						}

						$council_name = getCouncilFromID($course_council_ID);
						$council = str_replace('Council','', str_replace('Area','', $council_name));

						echo '<tr>';
						// echo '<td class="col border"> ' . $course_ID . '</td>';
						// echo '<td class="col border"> ' . $course_code . '</td>';
						echo '<td class="col border"> ' . $course_type . '</td>';
						echo '<td class="col border"> ' . $course_number . '</td>';
						echo '<td class="col border"> ' . $course_name . '</td>';
						echo '<td class="col border"> ' . $council . '</td>';
						echo '</tr>';
					}

					?>
				</tbody>
			</table>
		</div>
		<div class="col-auto">

			<br> <br>

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
		</div>
	</div>
