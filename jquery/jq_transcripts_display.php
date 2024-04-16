<?php  // ** Lampkin 2023 ** // ?>
<?php // ** v 5.00 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/query.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/form_elements.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/arrays.php';

	include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/functions_custom.php";

	$var_ID = 'transcript_ID';
	$var_active = 'transcript_active';
	$db_table = 'transcripts';
	$return_data = '';

	// $display_array = $db_table . '_display_array';
	$fields_array = $db_table . '_fields_array';
	$return_data = '';
	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$my_council = $mydata['myCouncil'];
		$select_user = $mydata['selectUser'];

		$view_empty = $mydata['viewEmpty'];
		$view_req = $mydata['viewReq'];

	// echo 'jq seeing: <br />';
	// echo 'my_council: ' . $my_council . '<br />';
	// echo 'select_user: ' . $select_user . '<br />';
	// echo 'view_empty: ' . $view_empty . '<br />';
	// echo 'view_req: ' . $view_req . '<br />';

	if( $my_council == '' || strlen($my_council) < 1 )
	{
		echo 'No council has been selected.';
		exit();
	}

	if( $select_user == 0 || strlen($select_user) < 1 )
	{
		echo '';
		exit();
	}

	//! COURSE CODE ID structure : '1' + 'course code' + 'course code ext' + 'council id'

	function getMyCourseCount($course_type,$userID)
	{
		global $con;
		$sql = "
		SELECT DISTINCT(`transcript_course_code_ID`) AS `transcript_course_code_ID`
		FROM `transcripts`
			JOIN `courses`
			ON `transcript_course_code_ID` = `course_code_ID`
		WHERE `transcript_user_ID` = '" . $userID . "'
		AND `course_type` LIKE '" . strtoupper($course_type) . "'
		";
		$results = mysqli_query($con,$sql);
		$cnt = mysqli_num_rows($results);
		return $cnt;
	}

if ($select_user > '0')
{
	$arrowhead_completed = "no";

	$acs_score = "0";
	$bcs_score = "0";
	$mcs_score = "0";
	$dcs_score = "0";
	$everything_count = "0";

	$acs_count = getMyCourseCount('acs',$select_user);
	$bcs_count = getMyCourseCount('bcs',$select_user);
	$mcs_count = getMyCourseCount('mcs',$select_user);
	$dcs_count = getMyCourseCount('dcs',$select_user);
	$ced_count = getMyCourseCount('ced',$select_user);
	// $everything_count = $acs_count + $bcs_count + $mcs_count + $dcs_count + $ced_count;
	$everything_count = $bcs_count + $mcs_count + $dcs_count + $ced_count;

	$arrowhead_completed_icon =
	$bach_step1_icon =
	$bach_step2_icon =
	$bach_step3_icon =
	$doct_step1_icon =
	$doct_step2_icon =
	$doct_step3_icon =
	$doct_step4_icon =
	$doct_step5_icon =
	$doct_step6_icon =
	$doct_step7_icon =
	$doct_step8_icon =
	$doct_step9_icon =
	$doct_step10_icon =
	'<span class="fa-li"><i class="far fa-square"></i></span>';

	$get_user_sql = "
	SELECT *
	FROM `users`
	WHERE `user_ID` = '" . $select_user . "'
	and `user_active` = 'yes'
	";
	$get_user_results = mysqli_query($con, $get_user_sql);
	while( $row = mysqli_fetch_assoc($get_user_results) )
	{
		foreach($users_fields_array as $field_key => $field_value)
		{
			$$field_value = $row[$field_value];
		}
		$user_name = fullNameList($user_prefix,$user_first_name,$user_nick_name,$user_middle_name,$user_last_name,$user_suffix);

		$basic_completed = ( strlen($user_basic) > 4 ) ? date('Y',strtotime($user_basic)) : ( (strlen($user_basic) >= 4 ) ? $user_basic : '' );

		$comm_key_completed = ( strlen($user_comm_key) > 4 ) ? date('Y',strtotime($user_comm_key)) : ( (strlen($user_comm_key) >= 4 ) ? $user_comm_key : '' );
		$arrowhead_completed = ( strlen($user_arrowhead) > 4 ) ? date('Y',strtotime($user_arrowhead)) : ( (strlen($user_arrowhead) >= 4 ) ? $user_arrowhead : '' );
		$distinguished_completed = ( strlen($user_distinguished) > 4 ) ? date('Y',strtotime($user_distinguished)) : ( (strlen($user_distinguished) >= 4 ) ? $user_distinguished : '' );

		$staff_completed = ( strlen($user_staff_years) >= 4 ) ? 'yes' : 'no';

		$bcs_completed = ( strlen($user_bcs) > 4 ) ? date('Y',strtotime($user_bcs)) : ( (strlen($user_bcs) >= 4 ) ? $user_bcs : '' );
		$mcs_completed = ( strlen($user_mcs) > 4 ) ? date('Y',strtotime($user_mcs)) : ( (strlen($user_mcs) >= 4 ) ? $user_mcs : '' );
		$dcs_completed = ( strlen($user_dcs) > 4 ) ? date('Y',strtotime($user_dcs)) : ( (strlen($user_dcs) >= 4 ) ? $user_dcs : '' );
	}

//! AM I ACTIVE?
	$am_i_alive = ( strlen($basic_completed) > 0 || strlen($comm_key_completed) > 0 || strlen($arrowhead_completed) > 0 || strlen($distinguished_completed) > 0 ||  strlen($bcs_completed) > 0 ||  strlen($mcs_completed) > 0 ||  strlen($dcs_completed) > 0 ||  strlen($everything_count) > 0 ) ? 'yes' : 'no';


	$limit_year = date("Y", strtotime("-7 years")); //Attended in the last 7 years??
	$am_i_active = '';
	if( !empty($basic_completed) && $basic_completed >= $limit_year ) { $am_i_active = 'yes'; }
	if( !empty($comm_key_completed) && $comm_key_completed >= $limit_year ) { $am_i_active = 'yes'; }
	if( !empty($arrowhead_completed) && $arrowhead_completed >= $limit_year ) { $am_i_active = 'yes'; }
	if( !empty($distinguished_completed) && $distinguished_completed >= $limit_year ) { $am_i_active = 'yes'; }
	if( !empty($bcs_completed) && $bcs_completed >= $limit_year ) { $am_i_active = 'yes'; }
	if( !empty($mcs_completed) && $mcs_completed >= $limit_year ) { $am_i_active = 'yes'; }
	if( !empty($dcs_completed) && $dcs_completed >= $limit_year ) { $am_i_active = 'yes'; }


	$sql = "SELECT MAX(`transcript_year`) AS `max_year` FROM `transcripts` WHERE `transcript_user_ID` = '" . $select_user . "' ";
	$results = mysqli_query($con,$sql);
	while( $row = mysqli_fetch_assoc($results) ) { $max_year = $row['max_year']; }
	$am_i_active = ( $max_year <= $limit_year ) ? $am_i_active : 'yes';

	//! BACHELORS = basic + 7completed : 5bcs
	//! MASTERS = bachelors or commkey + arrowhead + 14completed : 7mcs
	//! DOCTOR = masters + commkey + 24completed : 5mcs

	//! START DEGREE CALCULATIONS
	//! START DEGREE CALCULATIONS
	//! START DEGREE CALCULATIONS
	$icon_off = '<span class="fa-li"><i class="far fa-square"></i></span>';
	$icon_on = '<span class="fa-li"><i class="fas fa-check-square text-success"></i></span>';

	//!===
	$bcs_score = ( strlen($bcs_completed) >= 4 ) ? '100' : '0';
		$bcs_req1 = $bcs_req2 = $bcs_req3 = $bcs_req4a = $bcs_req4b = $bcs_req5 = ( strlen($bcs_completed) >= 4 ) ? $icon_on : $icon_off;

	$bcs_score = ( $am_i_alive == 'yes' ) ? $bcs_score + 25 : $bcs_score;  // Prereq 1: Be a commissioner
		$bcs_req1 =  ( $am_i_alive == 'yes' ) ? $icon_on : $bcs_req1;

	$bcs_score = ( strlen($basic_completed) >= 4 ) ? $bcs_score + 25 : $bcs_score; // Prereq 2: Orientation (dupes Basic)
		$bcs_req2 =  ( strlen($basic_completed) >= 4 ) ? $icon_on : $bcs_req2;
	$bcs_score = ( strlen($basic_completed) >= 4 ) ? $bcs_score + 25 : $bcs_score; // Prereq 3: Basic
		$bcs_req3 =  ( strlen($basic_completed) >= 4 ) ? $icon_on : $bcs_req3;

	$bcs_score = ( $everything_count >= 7 && $bcs_count >= 5 ) ? $bcs_score + 25 : $bcs_score;
		$bcs_req4a =  ( $everything_count >= 7 ) ? $icon_on : $bcs_req4a;
		$bcs_req4b =  ( $bcs_count >= 5 ) ? $icon_on : $bcs_req4b;

		$bcs_req5 = ( ($bcs_completed) >= 4 ) ? $icon_on : $bcs_req5;
	//!===
	$mcs_score = ( strlen($mcs_completed) >= 4 ) ? '100' : '0';
	$mcs_req1 = $mcs_req2 = $mcs_req3 = $mcs_req4a = $mcs_req4b = $mcs_req5 = ( strlen($mcs_completed) >= 4 ) ? $icon_on : $icon_off;

	$mcs_score = ( $bcs_completed > 0 || $comm_key_completed > 0 ) ? $mcs_score + 25 : $mcs_score;  // Prereq 1: BCS or Comm Key
		$mcs_req1 =  ( $bcs_completed > 0 || $comm_key_completed > 0 ) ? $icon_on : $mcs_req1;

	$mcs_score = ( strlen($arrowhead_completed) >= 4 ) ? $mcs_score + 25 : $mcs_score; // Prereq 2: Arrowhead
		$mcs_req2 =  ( strlen($arrowhead_completed) >= 4 ) ? $icon_on : $mcs_req2;
	$mcs_score = ( $am_i_active == 'yes' ) ? $mcs_score + 25 : $mcs_score; // Prereq 3: Be a commissioner
		$mcs_req3 =  ( $am_i_active == 'yes' ) ? $icon_on : $mcs_req3;

	$mcs_score = ( $everything_count >= 14 && $mcs_count >= 7 ) ? $mcs_score + 25 : $mcs_score;
		$mcs_req4a =  ( $everything_count >= 14 ) ? $icon_on : $mcs_req4a;
		$mcs_req4b =  ( $mcs_count >= 7 ) ? $icon_on : $mcs_req4b;

		$mcs_req5 = ( ($mcs_completed) >= 4 ) ? $icon_on : $mcs_req5;
	//!===
	$dcs_score = ( strlen($dcs_completed) >= 4 ) ? '100' : '0';
		$dcs_req1 = $dcs_req2 = $dcs_req3 = $dcs_req4 = $dcs_req5 = $dcs_req6 = $dcs_req7 = $dcs_req8 = $dcs_req9 = $dcs_req10 = $dcs_req11 = ( strlen($dcs_completed) >= 4 ) ? $icon_on : $icon_off;

	$dcs_score = ( strlen($mcs_completed) > 0 ) ? $dcs_score + 10 : $dcs_score;  // Prereq 1: MCS
		$dcs_req1 =  ( $mcs_completed > 0 ) ? $icon_on : $dcs_req1;
	$dcs_score = ( strlen($comm_key_completed) >= 4 ) ? $dcs_score + 10 : $dcs_score; // Prereq 2: Comm Key
		$dcs_req2 =  ( strlen($comm_key_completed) >= 4 ) ? $icon_on : $dcs_req2;
	$dcs_score = ( $am_i_active == 'yes' ) ? $dcs_score + 10 : $dcs_score; // Prereq 3: Be a commissioner
		$dcs_req3 =  ( $am_i_active == 'yes' ) ? $icon_on : $dcs_req3;

	$dcs_score = ( $everything_count >= 24  ) ? $dcs_score + 10 : $dcs_score;
		$dcs_req4 =  ( $everything_count >= 14 ) ? $icon_on : $dcs_req4;
	$dcs_score = ( $dcs_count >= 5 ) ? $dcs_score + 10 : $dcs_score;
		$dcs_req5 =  ( $dcs_count >= 5 ) ? $icon_on : $dcs_req5;

	$dcs_score = ( $staff_completed == 'yes' ) ? $dcs_score + 10 : $dcs_score;
		$dcs_req8 =  ( $staff_completed == 'yes' ) ? $icon_on : $dcs_req5;


	//!===
	$bcs_score = ( $bcs_score > 100 ) ? 100 : $bcs_score;
	$mcs_score = ( $mcs_score > 100 ) ? 100 : $mcs_score;
	$dcs_score = ( $dcs_score > 100 ) ? 100 : $dcs_score;

	$course_type_array = array(
		'acs' => 'Associate',
		'bcs' => 'Bachelor',
		'mcs' => 'Master',
		'dcs' => 'Doctorate',
		'ced' => 'Continuing Education',
	);

	?>

<style>
.form-check-input:checked
{
    background-color: darkgreen;
    border-color: darkgreen;
}

.progress, .progress-bar
{
	height: 2em;
}
.progress
{
	font-weight: bold;
	font-size: 1.3em;
}

.have, .need {
	font-weight: bold;
}
.have {
	color: green;
}
.need {
	color: blue;
}
</style>
<div class="col-12 mx-auto my-2 p-4 border border-dark rounded-lg">

	<div class="row m-0 p-0">
		<div class="col">
			<h3 class="mx-auto mb-2">Current Transcripts for:  <span class="text-success"><?php echo $user_name; ?> </span></h3>
		</div>
		<div class="col-md-auto text-end text-end" id="view_empty_box">
			<div class="form-check form-switch">
				<input class="form-check-input" type="checkbox" role="switch" id="viewEmpty" <?php if($view_empty == 'yes') {echo' checked';} ?> >
				<label class="form-check-label" for="viewEmpty">View empty courses.</label>
			</div>
		</div>
	</div>

	<style>
		.progress-group {
			position: relative;
			width: 100%;
			height: 100%;
		}
		.progress-message {
			font-weight: bold;
			z-index: 10;
			width: 100%;
			height: 100%;
			position: absolute;
			top: 0;
			left: 0;
			padding: 10px 0;
		}
	</style>
	<?php
	$degree_array = array('bcs'=>'Bachelor','mcs'=>'Master','dcs'=>'Doctorate');
	foreach( $degree_array AS $key => $value )
	{
		$degree_name = $key;
		$degree_progress_var = $key . '_score';
		$degree_progress = $$degree_progress_var;
		$degree_progress_clr = $degree_progress < 100 ? 'primary' : 'success';

		$progress_msg = $value . ' Degree ' . $degree_progress . '% ';
		$progress_msg_clr = $degree_progress < 75 ?  'text-dark' : 'text-light';

		echo '
		<div class="progress-group">
			<div class="progress mb-3" role="progressbar" aria-label="Success example" aria-valuenow="' . $degree_progress . '" aria-valuemin="0" aria-valuemax="100">
				<div class="progress-bar progress-' . $key . ' mb-1  text-bg-' . $degree_progress_clr . '" style="width: ' . $degree_progress . '%;"></div>
			</div>
			<div class="progress-message col-12 text-center ' . $progress_msg_clr . '">' . $progress_msg . '</div>
		</div>
		';
	}
	?>

		<div class="row m-0 p-0">
			<div class="col-md-6 col-sm-12">
				<h4 class=""> Transcript Data </h4>

				<table class="table table-sm table-striped table-bordered table-hover mx-auto my-2 px-10 py-1 col-md-8">
					<thead>
						<tr class="alert alert-dark fw-bold border">
							<th scope="col" class="fw-bold border main-clr"> Item </th>
							<th scope="col" class="fw-bold border main-clr"> Year </th>
						</tr>
					</thead>
					<tbody>
						<?php
							$display_array = array(
								'basic_completed' => 'Commissioner Basic',
								'arrowhead_completed' => 'Commissioner Arrowhead',
								'comm_key_completed' => 'Commissioner Key',
								'distinguished_completed' => 'Distinguished Commissioner',
								'bcs_completed' => 'Bachelor Degree',
								'mcs_completed' => 'Master Degree',
								'dcs_completed' => 'Doctorate Degree',
							);
							foreach( $display_array AS $key => $value )
							{
								$hilite = ( !empty($$key) ) ? 'bg-lemonchiffon fw-bold' : '';
								echo '
								<tr class="border ' . $hilite . '">
									<td class="border ' . $hilite . ' text-end">' . $value . ':</td>
									<td class="border ' . $hilite . '"> ' . $$key . '</td>
								</tr>
								';
							}

						?>
						<tr class="border">
							<td colspan="12" class="border bg-dark">  </td>
						</tr>
					</tbody>
				</table>
			</div>

	<?php
		$degree_icon = '<i class="fa-light fa-award text-muted"></i>';
		$degree_icon_active = '<i class="fa-regular fa-award text-primary"></i>';
		$degree_icon_success = '<i class="fa-solid fa-award text-success"></i> ';
	?>

			<div class="col-md-6 col-sm-12">

				<?php
					$bcs_icon = $mcs_icon = $dcs_icon = $degree_icon;
					if( strlen($user_bcs) > 1 ) { $bcs_icon = $degree_icon_success; }
					if( strlen($user_mcs) > 1 ) { $mcs_icon = $degree_icon_success; }
					if( strlen($user_dcs) > 1 ) { $dcs_icon = $degree_icon_success; }
				?>
				<style>
					#degreenav .nav-item .nav-link
					{
						color:black!important;
						font-weight:400;
					}
					#degreenav .nav-item .active
					{
						font-weight: 900;
						background-color:#e7e7e7;
					}
				</style>
				<ul class="nav nav-tabs" id="degreenav">
					<?php
					foreach( $degree_array AS $key => $value )
					{
						$deg_active = $view_req == $key ? 'active' : '';
						$deg_icon_var = $key . '_icon';
						$deg_icon = $view_req == $key ? $degree_icon_active : $$deg_icon_var;

						$degree_progress_clr = $degree_progress < 100 ? 'primary' : 'success';

						echo '
						<li class="nav-item">
							<a class="nav-link view_req ' . $deg_active . '" id="view_' . $key . '" data-view="' . $key . '">' . $deg_icon . ' ' . $value . '</a>
						</li>
						';
					}
					?>
					<li class="nav-item">
						<a class="nav-link view_req <?php if($view_req == 'xxx') { echo 'active';} ?>" id="view_none" data-view="none"><small> Close </small></a>
					</li>
				</ul>
				<div class="card">
					<?php
					$download_sql = "
					SELECT *
					FROM `download_links`
					WHERE `dl_code` = '" . $view_req . "'
					";
					$download_results = mysqli_query($con, $download_sql);
					while( $row = mysqli_fetch_assoc($download_results) )
					{
						$dl_name = $row['dl_name'];
						$dl_link = $row['dl_link'];
					}
					?>
					<?php
					if ( $view_req == 'bcs' )
					{
					?>
					<div class="card-header">
						<span class="h5"><?php echo $dl_name; ?></span>
						<span class="float-end download-req"><small><a href=<?php echo $dl_link; ?> target="_blank">[download requirements]</a></small></span>
					</div>
					<div class="card-body" id="requirements-<?php echo $view_req; ?>">

						<p class="h6"> Prerequisites </p>
							<ul class="fa-ul">
								<li class=""><?php echo $bcs_req1; ?> Maintain registration in any capacity as a commissioner during the entire training program listed below.</li>
								<li class=""><?php echo $bcs_req2; ?> Completion of commissioner orientation.</li>
								<li class=""><?php echo $bcs_req3; ?> Completion of Commissioner Basic Training. </li>
							</ul>

						<p class="h6"> Course Requirements </p>
							<ul class="fa-ul">
								<li class=""><?php echo $bcs_req4a; ?> Completion of <strong>seven</strong> total courses of instruction.  <span class=""> <em>(You have <span class="have"><?php echo $everything_count; ?></span>, you need <span class="need"><?php echo max(7 - $everything_count,0); ?></span> more.)</em> </span></li>
								<li class=""><?php echo $bcs_req4a; ?> At least <strong>five</strong> of the courses at the bachelor's program level. <span class=">"> <em>(You have <span class="have"><?php echo $bcs_count; ?></span>, you need <span class="need"><?php echo max(5 - $bcs_count,0); ?></span> more.)</em> </span></li>
							</ul>

						<p class="h6"> Performance </p>
							<ul class="fa-ul">
								<li class=""><?php echo $bcs_req5; ?>  Approval of council or assigned assistant council commissioner.</li>
								<li class=""><?php echo $bcs_req5; ?>  Approval of Scout executive or adviser to commissioner service.</li>
							</ul>

						<p>* Please note: ACS Courses do not count toward degree progress.</p>
					</div>
					<?php
					}
					elseif( $view_req == 'mcs' )
					{
					?>
					<div class="card-header">
						<span class="h5"><?php echo $dl_name; ?></span>
						<span class="float-end download-req"><small><a href=<?php echo $dl_link; ?> target="_blank">[download requirements]</a></small></span>
					</div>
					<div class="card-body" id="requirements-<?php echo $view_req; ?>">

						<p class="h6">Prerequisites </p>
							<ul class="fa-ul">
								<li class=">"><?php echo $mcs_req1; ?> Completion of <?php if(strlen($bcs_completed) >= 4) { echo '<u>';} ?>bachelor's degree</u> or have been awarded the <?php if( strlen($distinguished_completed) >= 4 ) { echo '<u>';} ?>Commissioner's Key</u>.</li>
								<li class=""><?php echo $mcs_req2; ?> Earned Arrowhead Honor.</li>
								<li class=""><?php echo $mcs_req3; ?> Current registration as a commissioner.</li>
							</ul>

						<p class="h6">Course Requirements </p>
							<ul class="fa-ul">
								<li class=""><?php echo $mcs_req4a; ?> Completion of <strong>seven</strong> additional courses of instruction (total of 14).  <span class=""> <em>(You have <span class="have"><?php echo $everything_count; ?></span>, you need <span class="need"><?php echo max(14 - $everything_count,0); ?></span> more.)</em> </span></li>
								<li class=""><?php echo $mcs_req4b; ?> At least <strong>seven</strong> of the courses at the master's program level. <span class=""> <em>(You have <span class="have"><?php echo $mcs_count; ?></span>, you need <span class="need"><?php echo max(7 - $mcs_count,0); ?></span> more.)</em> </span></li>
							</ul>

						<p class="h6">Performance </p>
							<ul class="fa-ul">
								<li class=""><?php echo $mcs_req5; ?> Approval of council or assigned assistant council commissioner.</li>
								<li class=""><?php echo $mcs_req5; ?> Approval of Scout executive or adviser to commissioner service.</li>
							</ul>
						<p>* Please note: ACS Courses do not count toward degree progress.</p>
					</div>
					<?php
					}
					elseif( $view_req == 'dcs' )
					{

					?>
					<div class="card-header">
						<span class="h5"><?php echo $dl_name; ?></span>
						<span class="float-end download-req"><small><a href=<?php echo $dl_link; ?> target="_blank">[download requirements]</a></small></span>
					</div>
					<div class="card-body" id="requirements-<?php echo $view_req; ?>">
						<p class="h6"> Prerequisites </p>
							<ul class="fa-ul">
								<li class=""><?php echo $dcs_req1; ?> Completion of master's degree.</li>
								<li class=""><?php echo $dcs_req2; ?> Have been awarded the Commissioner's Key.</li>
								<li class=""><?php echo $dcs_req3; ?> Current registration as a commissioner.</li>
							</ul>

						<p class="h6"> Course Requirements </p>
							<ul class="fa-ul">
								<li class=""><?php echo $dcs_req4; ?> Completion of <strong>10</strong> additional courses of instruction not used to qualify for other college awards (total of 24).  <span class=""> <em>(You have <span class="have"><?php echo $everything_count; ?></span>, you need <span class="need"><?php echo max(24 - $everything_count,0); ?></span> more.)</em> </span> </li>
								<li class=""><?php echo $dcs_req5; ?> At least <strong>five</strong> of the courses at the doctor's program level. <span class=""> <em>(You have <span class="have"><?php echo $dcs_count; ?></span>, you need <span class="need"><?php echo max(5 - $dcs_count,0); ?></span> more.) </span></em> </li>
							</ul>

						<p class="h6"> Thesis or Project </p>
							<ul class="fa-ul">
								<li class=""><?php echo $dcs_req6; ?> Completion of a thesis or project on any topic. It is recommended that the topic of a project or thesis be directly related to unit service. There may be specific circumstances under which a topic related to another area of Scouting would be appropriate.</li>
								<li class=""><?php echo $dcs_req7; ?> The topic and final paper or project must be approved by the dean of the doctor's program and the staff adviser.</li>
							</ul>

						<p class="h6"> Performance </p>
							<ul class="fa-ul">
								<li class=""><?php echo $dcs_req8; ?> Serve on the College of Commissioner Science faculty (instructor or support staff) for at least one year.</li>
								<!-- <li class=""><?php echo $dcs_req9; ?> Recruit at least three new commissioners.</li> -->
								<li class=""><?php echo $dcs_req10; ?> Approval of council or assigned assistant council commissioner.</li>
								<li class=""><?php echo $dcs_req11; ?> Approval of Scout executive or adviser to commissioner service.</li>
							</ul>

						<p>* Please note: ACS Courses do not count toward degree progress.</p>
						<p>* Please note: Earning the Doctor of Commissioner Science Degree does not automatically qualify you to
							receive the Doctorate of Commissioner Service Knot Award. For more information, visit the national BSA web site.</p>
					</div>
					<?php
					}
					?>

				</div>

			</div><?php // col-6 right ?>
		</div><?php // row ?>

		<div class="row" style="display:none;">
			<div class="col-6 my-2 mx-auto text-center">
				<?php
					$get_user_results = mysqli_query($con, $get_user_sql);
					while( $row = mysqli_fetch_assoc($get_user_results) )
					{
						$uid 		= $row['user_ID'];
						$send_phrase  	= scramble($uid . "|||" . $search_user . "|||");
						echo '
						<a class="btn btn-sm btn-success mx-auto" href="transcript-send?'. urlencode($send_phrase) .'" target="_blank">
						<i class="fas fa-paper-plane"></i> Email This Transcript </a>
						';
					}
				?>
			</div>

			<?php
				$change_request_email = '';
				$getDeans_sql = "
				SELECT *
				FROM `deans`
				WHERE 1=1
				AND `dean_council_ID` = '" . $my_council . "'
				";
				$getDeans = mysqli_query($con, $getDeans_sql);
				while( $row = mysqli_fetch_assoc($getDeans) )
				{
					$dean_bcs = $row['dean_bcs'];
					$dean_mcs = $row['dean_mcs'];
					$dean_dcs = $row['dean_dcs'];
					$dean_registrar1 = $row['dean_registrar1'];
				}

				$get_change_sql = "
				SELECT `dean_change_request`
				FROM `deans`
				WHERE 1=1
				AND `dean_council_ID` = '" . $my_council . "'
				";
				$get_change = mysqli_query($con, $get_change_sql);
				while( $row = mysqli_fetch_assoc($get_change) )
				{
					$dean_change_request = $row['dean_change_request'];
					$sql = "
					SELECT *
					FROM `users`
					WHERE `user_ID` = '" . $dean_change_request . "'
					";
					$results = mysqli_query($con, $sql);
					$data_chunk = '';
					while( $data = mysqli_fetch_assoc($results) )
					{
						$change_request_email = $data['user_email'];
					}
				}

			?>
			<div class="col-6 my-2 mx-auto text-center">
				<?php
					$get_user_results = mysqli_query($con, $get_user_sql);
					while( $row = mysqli_fetch_assoc($get_user_results) )
					{
						$uid 		= $row['user_ID'];
						$send_phrase  	= scramble($uid . "|||" . $search_user . "|||");
						echo '
						<a class="btn btn-sm btn-primary mx-auto" href="transcript-change?'. urlencode($send_phrase) .'" target="_blank">
						<i class="fas fa-user-edit"></i> Submit a Change Request </a>
						';
					}
				?>
			</div>
		</div>

		<table class="table table-sm table-striped table-bordered table-hover mx-auto my-2 px-10 py-1">
			<thead>
				<tr class="alert-dark fw-bold border">
					<th scope="col" class="fw-bold border main-clr text-center"> <b>&radic;</b> </th>
					<th scope="col" class="fw-bold border main-clr"> Course </th>
					<th scope="col" class="fw-bold border main-clr"> Description </th>
					<th scope="col" class="fw-bold border main-clr"> Type </th>
					<th scope="col" class="fw-bold border main-clr"> Year </th>

				</tr>
			</thead>
			<tbody>

				<?php
				//! Get all national courses, myCouncil courses, PLUS any other courses I've completed.
				$my_course_list_array = array();
				$get_my_courses_sql = "
				SELECT DISTINCT(`transcript_course_code_ID`) AS `transcript_course_code_ID`
				FROM `transcripts`
				WHERE `transcript_user_ID` = '" . $select_user . "'
				";
				$get_my_courses = mysqli_query($con,$get_my_courses_sql);
				$get_my_courses_cnt=0;$get_my_courses_cnt = @mysqli_num_rows($get_my_courses);
				while($row 	= mysqli_fetch_assoc($get_my_courses))
				{
					$transcript_course_code_ID = $row['transcript_course_code_ID'];
					$my_course_list_array[] = $transcript_course_code_ID;
				}
				$my_course_list = implode("','", $my_course_list_array);

				$get_all_courses_sql = "
				SELECT *
				FROM `courses`
				WHERE 1=1
				AND
				(`course_council_ID` = '999'
				OR `course_council_ID` = '" . $my_council . "'
				OR `course_code_ID` IN ('" . $my_course_list . "')
				)
				AND `course_active` = 'yes'
				ORDER BY `course_code`
				";

				$get_all_courses = mysqli_query($con, $get_all_courses_sql);

				while($courses 	= mysqli_fetch_assoc($get_all_courses))
				{
					$course_code_ID 	= $courses['course_code_ID'];
					$course_code 	= $courses['course_code'];
					$course_type 	= $courses['course_type'];
					$course_number 	= $courses['course_number'];
					$course_name 	= $courses['course_name'];
					$course_council_ID 	= $courses['course_council_ID'];

						$council_name = getCouncilFromID($course_council_ID);
						$council = str_replace('Council','', str_replace('Area','', $council_name));

					$transcript_year = '';

					$getMyTranscripts_sql = "
					SELECT *
					FROM `transcripts`
					WHERE `transcript_user_ID` = '" . $select_user . "'
					AND `transcript_course_code_ID` = '" . $course_code_ID . "'
					AND `transcript_active` = 'yes'
					";

					$getMyTranscripts = mysqli_query($con, $getMyTranscripts_sql);
					$transcript_cnt = mysqli_num_rows($getMyTranscripts);

					$checker = '';
					$checked = '';

					while( $row = mysqli_fetch_assoc($getMyTranscripts) )
					{
						$transcript_year = $row['transcript_year'];
					}

					if($transcript_year <> '')
					{
						$checker = 'fw-bold bg-lemonchiffon ';
						$checked = '<b>&radic;</b>';
					}

					$view_listing = ( $transcript_year == '' && $view_empty == 'no' ) ? 'no' : 'yes';

					if($transcript_year <> '1969') // This shows get_all_courses after 1969
					{
						if( $view_listing == 'yes' )
						{
							echo '
							<tr class=" '.$checker.'">
								<td class="'.$checker.' text-center"> '. $checked . '</td>
								<td class="border '.$checker.'"> '. $course_type .' '. $course_number .' </td>
								<td class="border '.$checker.'"> '. $course_name .'</td>
								<td class="border '.$checker.'"> <small>'. $council .'</small></td>
								<td class="border '.$checker.' text-center">'. $transcript_year .'</td>
							</tr>
							';
						}

					} //empty
				} //get_all_courses
				?>
				<tr class="border">
					<td colspan="12" class="border bg-dark"> </td>
				</tr>
			</tbody>
		</table>
<?php
} // if uid has data
?>
<script>
	$(document).ready(function()
	{
			// alert('ready');

		$('.progress-bar').each(function(i, obj)
		{
			let progress = $(this).data('value');
			// console.log(progress);
			if(progress < 75)
			{

			}
			else if( progress < 50)
			{

			}
		});


	});
</script>
