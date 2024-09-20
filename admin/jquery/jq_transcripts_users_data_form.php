<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	include 	 $config_form_elements;
	include 	 $config_arrays;
	include 	 $config_functions_custom;

	$db_table 		= 'users';
	$data_results 	= '';
	$var_ID 		= 'user_ID';
	$var_active 	= 'user_active';

	$fields_array = $db_table . '_fields_array';

	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$admin_user = $mydata['adminUser'];
		$admin_council_select = $mydata['adminCouncilSelect'];
		$this_id = $mydata['transcriptsUser'];

		$status = $mydata['statusSelect'];
		$deceased = $mydata['deceasedSelect'];
		$active = $mydata['activeSelect'];

		$disabled = '';

		// transcriptsUser:transcriptsUser,
		// activeSelect:activeSelect,
		// statusSelect:statusSelect,
		// deceasedSelect:deceasedSelect,
		// limitNum:limitNum,
		// pgNum:pgNum,
		// pgSort:pgSort,
		// thisPage:thisPage,

	foreach( $$fields_array as $key => $value )
	{
		$$value = '';
	}

	//! CHECK ADMIN LEVEL ACCESS  ==========================
	$my_admin_level = 100;
	$my_admin_level = getAdminLevel($admin_user);
	//! CHECK ADMIN LEVEL ACCESS  ==========================

	//# Build queries ==========================
	$addl_sql = $status_sql .
	' ' . $deceased_sql;

	$sql = "
		SELECT  *
		FROM `users`
		WHERE 1=1
		AND `user_ID` = '" . $this_id . "'
	";
	// echo nl2br($sql) . '<br />';
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;

	$msg = '<i class="far fa-plus-square"></i> Add User';
	$bg_clr = "alert-info";
	$form_action = 'insert';

	if ($this_id > 0) {
		while ($row = mysqli_fetch_assoc($results)) {
			foreach( $$fields_array as $key => $value ) {
				$$value = $row[$value];
			}
		}
		$msg = '<i class="fas fa-edit"></i> Edit User';
		$bg_clr = "alert-warning";
		$form_action = 'update';
	}



	$last_update_sql = "
	SELECT MAX(`transcript_last_update`) AS `transcript_last_update`
	FROM `transcripts` t
		JOIN `courses` c
		ON t.transcript_course_ID = c.course_ID
	WHERE 1=1
	AND `transcript_user_ID` = '" . $user_ID . "'
	";
	$last_update = mysqli_query($con,$last_update_sql);
	$last_update_cnt = mysqli_num_rows($last_update);

	if ( $last_update_cnt > 0 )
	{
		while( $max = mysqli_fetch_assoc($last_update) )
		{
			$transcript_last_update = $max['transcript_last_update'];
			$transcript_last_update = date('M d, Y G:i a', strtotime($transcript_last_update ?? ''));
		}
	}
	else
	{
		$transcript_last_update = "No data.";
	}
// echo '<h4>' . $council . '</h4>';

	if( strlen($this_id ?? '') > 0 )
	{
?>

		<hr />
		<form class="nice-form col-sm-12 col-md-9 mx-auto" id="transaction_data"  method="post" enctype="application/x-www-form-urlencoded">
			<input type="hidden" class="form-control" name="process" value="yes">

			<input type="hidden" class="form-control" name="id_field" id="id_field" value="user_ID">
			<input type="hidden" class="form-control" name="db_table" id="db_table" value="users">
			<input type="hidden" class="form-control" name="user_ID" id="user_ID" value="<?php echo $user_ID; ?>">
			<input type="hidden" class="form-control" name="action" id="action" value="<?php echo $form_action;?>">
			<input type="hidden" class="form-control" name="user_council_ID" id="user_council_ID" value="<?php echo $admin_council_select;?>">

			<div class="row px-0 py-0 mx-1 mb-3">
				<div class="h4 col-md-6 m-0 p-0">
					<?php
						$patch = getCouncilPatch($admin_council_select);
						$council_name = getCouncilFromID($admin_council_select);
						$image = file_exists('/var/www/html/img/img_councils/' . $patch . '') !== false ? '<img src="../img/img_councils/' . $patch . '" id="council_strip" title="' . $council_name . ' Council Strip" class="council-strip">' : '<img src="../img/img_councils/generic.png" id="council_strip" title="Council Strip" class="council-strip">';
						$full_name = ($user_ID >= '1') ? fullName($user_prefix,$user_first_name,$user_nick_name,'',$user_last_name,$user_suffix) : 'ERROR: No user selected.';
						echo $full_name;
						echo ' ';
						echo $image;
					?>
				</div>
				<div class="col-md-6 m-0 p-0 text-end">
					<?php
						$user_last_update_test = strtotime($user_last_update ?? '');
						$transcript_last_update_test = strtotime($transcript_last_update ?? '');
						$last_update = ( $user_last_update_test > $transcript_last_update_test ) ? $user_last_update : $transcript_last_update;
						$last_update = date('M d, Y,  g:i a', strtotime($last_update ?? ''));
					?>
					Last update: <?php echo $last_update; ?>
				</div>
			</div>

			<div class="d-grid gap-2 m-2">
				<button class="btn btn-success submit-transcript" type="submit"> <i class="fa-solid fa-floppy-disk"></i> S A V E  </button>
			</div>

			<div class="row px-0 py-0 mx-1">
				<div class="h5 col-md-6 m-0 p-0">Awards Section</div>
				<div class="col-md-6 m-0 p-0 text-end">Highlighted rows are simply colored to ease visual search.</div>

				<?php if(!empty($user_basic)) { $bold = "fw-bold active";} else { $bold = "";} ?>
				<div class="mb-3 col-md-3 py-2 <?php echo $bold; ?>">
					<label for="user_basic" class="form-label">Commissioner Basic</label>
					<input type="text" class="form-control" name="user_basic" id="user_basic" value="<?php echo $user_basic; ?>">
				</div>

				<?php if(!empty($user_arrowhead)) { $bold = "fw-bold active";} else { $bold = "";} ?>
				<div class="mb-3 col-md-3 py-2 <?php echo $bold; ?>">
					<label for="user_arrowhead" class="form-label">Commissioner Arrowhead</label>
					<input type="text" class="form-control" name="user_arrowhead" id="user_arrowhead" value="<?php echo $user_arrowhead; ?>">
				</div>

				<?php if(!empty($user_comm_key)) { $bold = "fw-bold active";} else { $bold = "";} ?>
				<div class="mb-3 col-md-3 py-2 <?php echo $bold; ?>">
					<label for="user_comm_key" class="form-label">Commissioner Key</label>
					<input type="text" class="form-control" name="user_comm_key" id="user_comm_key" value="<?php echo $user_comm_key; ?>">
				</div>

				<?php if(!empty($user_distinguished)) { $bold = "fw-bold active";} else { $bold = "";} ?>
				<div class="mb-3 col-md-3 py-2 <?php echo $bold; ?>">
					<label for="user_distinguished" class="form-label">Distinguished Commissioner</label>
					<input type="text" class="form-control" name="user_distinguished" id="user_distinguished" value="<?php echo $user_distinguished; ?>">
				</div>

				<?php if(!empty($user_excellence)) { $bold = "fw-bold active";} else { $bold = "";} ?>
				<div class="mb-3 col-md-3 py-2 <?php echo $bold; ?>">
					<label for="user_excellence" class="form-label">Commissioner Award of Excellece</label>
					<input type="text" class="form-control" name="user_excellence" id="user_excellence" value="<?php echo $user_excellence; ?>">
				</div>

				<?php if(!empty($user_bcs)) { $bold = "fw-bold active";} else { $bold = "";} ?>
				<div class="mb-3 col-md-3 py-2 <?php echo $bold; ?>">
					<label for="user_bcs" class="form-label">Bachelors Degree </label>
					<input type="text" class="form-control" name="user_bcs" id="user_bcs" value="<?php echo $user_bcs; ?>">
				</div>

				<?php if(!empty($user_mcs)) { $bold = "fw-bold active";} else { $bold = "";} ?>
				<div class="mb-3 col-md-3 py-2 <?php echo $bold; ?>">
					<label for="user_mcs" class="form-label">Masters Degree </b></label>
					<input type="text" class="form-control" name="user_mcs" id="user_mcs" value="<?php echo $user_mcs; ?>">
				</div>

				<?php if(!empty($user_dcs)) { $bold = "fw-bold active";} else { $bold = "";} ?>
				<div class="mb-3 col-md-3 py-2 <?php echo $bold; ?>">
					<label for="user_dcs" class="form-label">Doctorate Degree </label>
					<input type="text" class="form-control" name="user_dcs" id="user_dcs" value="<?php echo $user_dcs; ?>">
				</div>

			</div>

				<?php

				$active_course = 'yes';
				$active_course_sql = $active_course !== 'no' ? "AND LOWER(`active_course`) = 'yes'" : "AND LOWER(`active_course`) = 'no'";

				$ooc_transcript_courses_array = array();
				$ooc_course_array = array();
				$ooc_transcript_courses_sql = "
					SELECT *
					FROM `transcripts`
					WHERE 1=1
					AND `transcript_user_ID` = '" . $user_ID . "'
					AND LOWER(`transcript_active`) = '" . $active_course . "'
					AND (
						`transcript_council_ID` <> '" . $admin_council_select . "'
						OR
						`transcript_council_ID` <> '999'
					)
					";
					// echo $ooc_transcript_courses_sql;
				$ooc_transcript_courses = mysqli_query($con,$ooc_transcript_courses_sql);
				$ooc_transcript_courses_cnt = mysqli_num_rows($ooc_transcript_courses) ?? 0;
				if( $ooc_transcript_courses_cnt > 0 )
				{
					while($data = mysqli_fetch_assoc($ooc_transcript_courses))
					{
						$transcript_course_ID = $data['transcript_course_ID'];
						$transcript_year= $data['transcript_year'];
						$ooc_transcript_courses_array[$transcript_course_ID] = $transcript_year;
						$ooc_course_array[] = $transcript_course_ID;
					}
				}
				?>
			<div class="mx-auto">
				<div class="row px-0 py-0 mx-1">
					<div class="h5 col-md-6 m-0 p-0">Course Section </div>
					<div class="col-md-6 m-0 p-0 text-end">
						<a id="scrollToBottom" href class="scroll" onclick="scrollToAnchor('bottom')"><i class="fa-solid fa-circle-caret-down"></i> Out-of-Council Courses </a>
					</div>

					<table id="transcriptsTable" class="table table-striped table-hover border">
						<thead>
							<tr>
								<th scope="col" class="main-clrfw-bold text-uppercase border-right"> Course </th>
								<th scope="col" class="main-clrfw-bold text-uppercase border-right"> Name </th>
								<th scope="col" class="main-clrfw-bold text-uppercase border-right"> Year </th>
							</tr>
						</thead>
						<tbody>
						<?php

							$all_courses_sql = "
							SELECT *
							FROM `courses`
							WHERE 1=1
							AND LOWER(`course_active`) = '" . $active_course . "'
							AND (
								`course_council_ID` = '" . $admin_council_select . "'
								OR
								`course_council_ID` = '999'
							)
							ORDER BY `course_code`
							";
							// echo $all_courses_sql . '<br />';
							$all_courses = mysqli_query($con,$all_courses_sql);

							while($courses 	= mysqli_fetch_assoc($all_courses))
							{
								$course_ID 	= $courses['course_ID'];
								$course_code 	= $courses['course_code'];
								$course_type 	= $courses['course_type'];
								$course_number 	= $courses['course_number'];
								$course_name 	= $courses['course_name'];
								$course_council_ID 	= $courses['course_council_ID'];
									$council_name	= getCouncilFromID($course_council_ID);
									$council_name 	= str_replace('Area', '', $council_name);
									$council_name 	= str_replace('Council', '', $council_name);

									$transcript_year = '';
									$data_marker = '';

								$this_transcripts_sql = "
								SELECT *
								FROM `transcripts`
								WHERE 1=1
								AND `transcript_user_ID` = '" . $user_ID . "'
								AND `transcript_course_ID` = '" . $course_ID . "'
								AND LOWER(`transcript_active`) = 'yes'
								";
								$this_transcripts = mysqli_query($con,$this_transcripts_sql);
								$transcript_cnt = mysqli_num_rows($this_transcripts) ?? 0;

								if( $transcript_cnt > 0 )
								{
									while($transcripts = mysqli_fetch_assoc($this_transcripts))
									{
										$transcript_year = $transcripts['transcript_year'];
									}

									$data_marker = "fw-bolder table-active" ;
								}

							echo '
									<input type="hidden" name="course_ID[]"  value="' . $course_ID . '">
									<input type="hidden" name="course_type[]"  value="' . $course_type . '">
									<input type="hidden" name="course_number[]"  value="' . $course_number . '">
									<input type="hidden" name="course_council_ID[]" value="' . $course_council_ID . '">
								<tr class="form-group my-0">
									<td class="border ' . $data_marker . '"> ' . $course_type . ' ' . $course_number . ' [' . trim($council_name) . ']</td>
									<td class="border ' . $data_marker . '"> ' . $course_name . '</td>
									<td class="border ' . $data_marker . '">
										<input type="text" class="form-control form-control-sm" name="course_year[]" value="' . $transcript_year . '">
									</td>
								</tr>';
							}


							$ooc_courses_sql = "
							SELECT *
							FROM `courses`
							WHERE 1=1
							AND LOWER(`course_active`) = '" . $active_course . "'
							AND `course_ID` IN ('" . $ooc_course_list . "')
							ORDER BY `course_code`
							";
							// echo $ooc_courses_sql . '<br />';
							$ooc_courses = mysqli_query($con,$ooc_courses_sql);

							while($courses 	= mysqli_fetch_assoc($ooc_courses))
							{
								$course_ID 	= $courses['course_ID'];
								$course_code 	= $courses['course_code'];
								$course_type 	= $courses['course_type'];
								$course_number 	= $courses['course_number'];
								$course_name 	= $courses['course_name'];
								$course_council_ID 	= $courses['course_council_ID'];
									$council_name	= getCouncilFromID($course_council_ID);
									$council_name 	= str_replace('Area', '', $council_name);
									$council_name 	= str_replace('Council', '', $council_name);

									$transcript_year = '';
									$data_marker = '';

								$this_transcripts_sql = "
								SELECT *
								FROM `transcripts`
								WHERE 1=1
								AND `transcript_user_ID` = '" . $user_ID . "'
								AND `transcript_course_ID` = '" . $course_ID . "'
								AND LOWER(`transcript_active`) = 'yes'
								";
								$this_transcripts = mysqli_query($con,$this_transcripts_sql);
								$transcript_cnt = mysqli_num_rows($this_transcripts) ?? 0;

								if( $transcript_cnt > 0 )
								{
									while($transcripts = mysqli_fetch_assoc($this_transcripts))
									{
										$transcript_year = $transcripts['transcript_year'];
									}

									$data_marker = "fw-bolder table-active" ;
								}

							echo '
									<input type="hidden" name="course_ID[]"  value="' . $course_ID . '">
									<input type="hidden" name="course_type[]"  value="' . $course_type . '">
									<input type="hidden" name="course_number[]"  value="' . $course_number . '">
									<input type="hidden" name="course_council_ID[]" value="' . $course_council_ID . '">
								<tr class="form-group my-0">
									<td class="border ' . $data_marker . '"> ' . $course_type . ' ' . $course_number . ' [' . trim($council_name) . ']</td>
									<td class="border ' . $data_marker . '"> ' . $course_name . '</td>
									<td class="border ' . $data_marker . '">
										<input type="text" class="form-control form-control-sm" name="course_year[]" value="' . $transcript_year . '">
									</td>
								</tr>';
							}

						?>
						</tbody>
					</table>

					<div class="col-md-6 m-0 p-0 mb-3">
						<span id="addNewFormItem" class="btn btn-primary col-md-6"><i class="fa-solid fa-circle-plus"></i> Out-of-council item</span>
					</div>
					<div class="col-md-6 m-0 p-0 mb-3 text-end">
						<a id="scrollToTop" href="" class="scroll" onclick="scrollToAnchor('top')"><i class="fa-solid fa-circle-caret-up"></i> Back to Top </a>
					</div>
					<a id="bottom"></a>
					<hr />
					<button class="btn btn-success submit-transcript" type="submit"> <i class="fa-solid fa-floppy-disk"></i> S A V E  </button>
				</div>

			</div>
		</form>

<?php
	}
?>
