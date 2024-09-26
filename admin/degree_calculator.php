<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	include 	 $config_form_elements;
	include 	 $config_arrays;
	include 	 $config_functions_custom;

	$data = file_get_contents("php://input");
	$mydata = json_decode($data,true);

		$this_type = trim($mydata['degreeType']);
		// $council_select = trim($mydata['councilSelect']);

		$council_select = '215';

$copy_contact_data = '';
$return_data = '';

function getMyCourseCount($course_type,$user_ID)
{
	global $con;
	$cnt=0;
	$sql = "
	SELECT DISTINCT(`transcript_course_ID`) AS `transcript_course_ID`
	FROM `transcripts`
		JOIN `courses`
		ON `transcript_course_ID` = `course_ID`
	WHERE 1=1
	AND `transcript_user_ID` = '" . $user_ID . "'
	AND LOWER(`course_type`) LIKE '" . $course_type . "'
	";
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results);
	return $cnt;
}

$ccs_min_year = date('Y',strtotime('-5 year'));
$basic_sql = "AND `user_basic` <> ''";
// $basic_sql = "";
$clr = '';

if ($this_type == 'bcs')
{
	$sql = "
		SELECT *
		FROM `users`
		WHERE 1=1
		" . $basic_sql . "
		AND ( `user_bcs` = '' OR `user_bcs` IS NULL )
		AND ( `user_mcs` = '' OR `user_mcs` IS NULL )
		AND ( `user_dcs` = '' OR `user_dcs` IS NULL )
		AND `user_council_ID` = '" . $council_select . "'
		AND `user_status` LIKE 'active'
		ORDER BY `user_last_name`, `user_first_name`
		";
	$msg = 'Bachelors';
	$clr = 'primary';
 }
elseif ($this_type == 'mcs')
{
	$sql = "
		SELECT *
		FROM `users`
		WHERE 1=1
		" . $basic_sql . "
		AND ( `user_bcs` <> '' OR `user_comm_key` <> '' )
		AND ( `user_mcs` = '' OR `user_mcs` IS NULL )
		AND ( `user_dcs` = '' OR `user_dcs` IS NULL )
		AND `user_council_ID` = '" . $council_select . "'
		AND `user_status` LIKE 'active'
		ORDER BY `user_last_name`, `user_first_name`
		";
	$msg = 'Masters';
	$clr = 'info';
}
elseif ($this_type == 'dcs')
{
	$sql = "
		SELECT *
		FROM `users`
		WHERE 1=1
		" . $basic_sql . "
		AND ( `user_bcs` <> '' OR `user_comm_key` <> '' )
		AND `user_mcs` <> ''
		AND ( `user_dcs` = '' OR `user_dcs` IS NULL )
		AND `user_comm_key` <> ''
		AND `user_council_ID` = '" . $council_select . "'
		AND `user_status` LIKE 'active'
		ORDER BY `user_last_name`, `user_first_name`
		";
	$msg = 'Doctorate';
	$clr = 'success';
 }
elseif ($this_type == 'xcs')
{
	$sql = "
		SELECT *
		FROM `users`
		WHERE 1=1
		AND ( `user_bcs` = '' OR `user_bcs` IS NULL )
		AND ( `user_mcs` = '' OR `user_mcs` IS NULL )
		AND ( `user_dcs` = '' OR `user_dcs` IS NULL )
		AND `user_council_ID` = '" . $council_select . "'
		AND `user_status` LIKE 'active'
		ORDER BY `user_last_name`, `user_first_name`
		";
	$msg = 'Almost';
	$clr = 'danger';
 }
// $return_data .= $sql;

$results = mysqli_query($con,$sql);
$degree_cnt=0;$degree_cnt = mysqli_num_rows($results);

$return_data .= '<h5 class="text-'. $clr . '">' . date('M d, Y g:i a') . '</h5>';
$return_data .= '<h4 class="text-'. $clr . '">Automatic ' . strtoupper($msg) . ' Transcript Review </h4>';

$copy_contact_data .= '<table><tr><td colspan="4">' . date('M d, Y g:i a') . '</td></tr></table>';
$copy_contact_data .= '<table><tr><td colspan="4">' . strtoupper($msg) . '</td></tr></table>';

if( $this_type !== 'dcs' )
{
	$return_data .= 'The following "registered" commissioners who have not been awarded the <strong>' . $msg . ' degree</strong> but who calculate 100% completion.
	<br/> Anyone who has not taken a course since <strong>~' . $ccs_min_year . '</strong> will be automatically hidden from this list.';
}
else
{
	$return_data .= 'The following "registered" commissioners who have not been awarded the <strong>' . $msg . ' degree</strong> but who calculate 100% CLASS ROOM completion.
	<strong>This DOES NOT include their thesis or project work.</strong>
	<br/> Anyone who has not taken a course since <strong>~' . $ccs_min_year . '</strong> will be automatically hidden from this list.';
}
$return_data .= ' <em>Please note: ACS courses do NOT count toward any degree.</em>

<table class="table table-striped table-hover border">
<thead>
	<tr class="bg-' . $clr . ' text-white">
		<th class="border"><small>#</small></th>
		<th class="border"><small>ID</small></th>
		<th class="border"><small>Name</small></th>
		<th class="border"><small>Email</small></th>

		<th class="border"><small>Last Course</small></th>

		<th class="border"><small>Basic</small></th>
		<th class="border"><small>Arrow</small></th>
		<th class="border"><small>CommKey</small></th>

		<th class="border"><small>BCS #</small></th>
		<th class="border"><small>MCS #</small></th>
		<th class="border"><small>DCS #</small></th>
		<th class="border"><small>CED #</small></th>
		<th class="border"><small>TOTAL #</small></th>

		<th class="border"><small>BCS Degree</small></th>
		<th class="border"><small>MCS Degree</small></th>
		<th class="border"><small>DCS Degree</small></th>

		<th class="border"><small>BCS Score</small></th>
		<th class="border"><small>MCS Score</small></th>
		<th class="border"><small>DCS Score</small></th>

		<th class="border"><small>ACTION</small></th>
	</tr>
</thead>
<tbody>
';

$copy_contact_data .= '<table border style="width:500px">';
$copy_contact_data .= '<tr><td border nowrap style="width:120px;">ID</td><td border nowrap width="120px">Name</td><td border nowrap width="120px">Email 1</td><td border style="width:100; height:100">Email 2</td></tr>';

$y=0;
if( $degree_cnt !== 0 )
{
	while( $row = mysqli_fetch_assoc($results) )
	{

		// BACHELORS = basic + 7completed : 5bcs
		// MASTERS = bachelors or comm_key + arrowhead + 14completed : 7mcs
		// DOCTOR = masters + comm_key + 24completed : 5mcs

		foreach($users_fields_array as $field_key => $field_value)
		{
			$$field_value = $row[$field_value];
		}
		$user_name = fullNameList($user_prefix,$user_first_name,$user_nick_name,$user_middle_name,$user_last_name,$user_suffix);

		$getMyLastCourse_sql = "
		SELECT MAX(`transcript_year`) AS `max_year`
		FROM `transcripts`
			JOIN `courses`
			ON `transcript_course_ID` = `course_ID`
		WHERE 1=1
		AND `transcript_user_ID` = '" . $user_ID . "'
		";
		$getMyLastCourse = mysqli_query($con,$getMyLastCourse_sql);
		while( $max = mysqli_fetch_assoc($getMyLastCourse) )
		{
			$max_year = $max['max_year'];
		}
		$display_data = ( $max_year <= $ccs_min_year ) ? 'no' : 'yes';
		if ( $display_data !== 'no' )
		{
			// $acs_count = getMyCourseCount('acs',$user_ID);
			$bcs_count = getMyCourseCount('bcs',$user_ID);
			$mcs_count = getMyCourseCount('mcs',$user_ID);
			$dcs_count = getMyCourseCount('dcs',$user_ID);
			$ced_count = getMyCourseCount('ced',$user_ID);

			// $everything_count = $acs_count + $bcs_count + $mcs_count + $dcs_count + $ced_count;
			$everything_count = $bcs_count + $mcs_count + $dcs_count + $ced_count;

			$basic_completed = ( strlen($user_basic) > 4 ) ? date('Y',strtotime($user_basic)) : ( (strlen($user_basic) >= 4 ) ? $user_basic : '?' );

			$comm_key_completed = ( strlen($user_comm_key) > 4 ) ? date('Y',strtotime($user_comm_key)) : ( (strlen($user_comm_key) >= 4 ) ? $user_comm_key : '' );
			$arrowhead_completed = ( strlen($user_arrowhead) > 4 ) ? date('Y',strtotime($user_arrowhead)) : ( (strlen($user_arrowhead) >= 4 ) ? $user_arrowhead : '' );
			$distinguished_completed = ( strlen($user_distinguished) > 4 ) ? date('Y',strtotime($user_distinguished)) : ( (strlen($user_distinguished) >= 4 ) ? $user_arrowhead : '' );

			$bcs_completed = ( strlen($user_bcs) > 4 ) ? date('Y',strtotime($user_bcs)) : ( (strlen($user_bcs) == 4 ) ? $user_bcs : '' );
			$mcs_completed = ( strlen($user_mcs) > 4 ) ? date('Y',strtotime($user_mcs)) : ( (strlen($user_mcs) == 4 ) ? $user_mcs : '' );
			$dcs_completed = ( strlen($user_dcs) > 4 ) ? date('Y',strtotime($user_dcs)) : ( (strlen($user_dcs) == 4 ) ? $user_dcs : '' );

			$am_i_alive = $am_i_active = 'yes';

			$bcs_score = ( strlen($bcs_completed) >= 4 ) ? 100 : 0;
			if( $bcs_score !== 100 )
			{
				$bcs_score = ( $am_i_alive == 'yes' ) ? $bcs_score + 25 : $bcs_score;  // Prereq 1: Be a commissioner
				$bcs_score = ( strlen($basic_completed) >= 4 ) ? $bcs_score + 25 : $bcs_score; // Prereq 2: Orientation (dupes Basic)
				$bcs_score = ( strlen($basic_completed) >= 4 ) ? $bcs_score + 25 : $bcs_score; // Prereq 3: Basic
				$bcs_score = ( $everything_count >= 7 && $bcs_count >= 5 ) ? $bcs_score + 25 : $bcs_score;
			}

			$mcs_score = ( strlen($mcs_completed) >= 4 ) ? 100 : 0;
			if( $mcs_score == 0 )
			{
				$mcs_score = ( $bcs_completed >= 4 || $comm_key_completed >= 4 ) ? $mcs_score + 25 : $mcs_score;  // Prereq 1: BCS or Distinguished
				$mcs_score = ( strlen($arrowhead_completed) >= 4 ) ? $mcs_score + 25 : $mcs_score; // Prereq 2: Arrowhead
				$mcs_score = ( $am_i_active == 'yes' ) ? $mcs_score + 25 : $mcs_score; // Prereq 3: Be a commissioner
				$mcs_score = ( $everything_count >= 14 && $mcs_count >= 7 ) ? $mcs_score + 25 : $mcs_score;
			}

			$dcs_score = ( strlen($dcs_completed) >= 4 ) ? 100 : 0;
			if( $dcs_score == 0 )
			{
				$dcs_score = ( $mcs_completed >= 4 ) ? $dcs_score + 25 : $dcs_score;  // Prereq 1: MCS
				$dcs_score = ( strlen($comm_key_completed) >= 4 ) ? $dcs_score + 25 : $dcs_score; // Prereq 2: Comm Key
				$dcs_score = ( $am_i_active == 'yes' ) ? $dcs_score + 25 : $dcs_score; // Prereq 3: Be a commissioner
				$dcs_score = ( $everything_count >= 24 && $dcs_count >= 5 ) ? $dcs_score + 25 : $dcs_score;
			}

				$user_email = trim($user_email);
				$user_email2 = trim($user_email2);
				$this_email = $user_email2;
			if( strlen($user_email) > 2 )
			{
				$this_email = $user_email;
				if( strlen($user_email2) > 2 && $user_email !== $user_email2 )
				{
					$this_email = $user_email . ', ' . $user_email2;
				}
			}

			$show = 'no';
			if ($this_type == 'xcs' ) { $show = 'yes'; }
			if ($this_type == 'bcs' && $bcs_score >= 100 && strlen($bcs_completed) < 2 ) { $show = 'yes'; }
			if ($this_type == 'mcs' && $mcs_score >= 100 && strlen($mcs_completed) < 2 ) { $show = 'yes'; }
			if ($this_type == 'dcs' && $dcs_score >= 100 && strlen($dcs_completed) < 2 ) { $show = 'yes'; }

			$action_data = urlencode(scramble('' . $user_ID . '|' . $this_type));
			$action_button = '<a href="https://cts.scoutcrest.org/admin/degree_accept?data=' . $action_data . '" target="_blank" class="btn btn-success btn-xs btn-email"> <br />' . strtoupper($this_type) . '</a>';

			$hilite = $this_type == 'bcs' && $bcs_score >= 100 ? 'background:yellow!imporant;' : ( $this_type == 'mcs' && $mcs_score >= 100 ? 'background:orange!imporant;' : ( $this_type == 'dcs' && $dcs_score >= 100 ? 'background:cyan!imporant;' : ( $this_type == 'xcs' && $dcs_score >= 100 ? 'background:pink!imporant;' : '' ) ) );
			// $hilite = ( $this_type == 'mcs' && $mcs_score >= 100 ? 'bg-yellow' : ( $this_type == 'dcs' && $dcs_score >= 100 ? 'bg-yellow' : '' ) )
			// $hilite = ( $this_type == 'dcs' && $dcs_score >= 100 ? 'bg-yellow' : '' );

			$basic_hilite = ( strlen($user_basic) < 4 ) ? 'bg-yellow' : '';
			$arrow_hilite = ( strlen($user_arrowhead) < 4 ) ? 'bg-yellow' : '';
			$commkey_hilite = ( strlen($user_comm_key) < 4 ) ? 'bg-yellow' : '';

			if ( $show !== 'no' )
			{
				$y++;
				$return_data .= '<tr>
				<td class="border list-text">' . $y . ' </td>
				<td class="border "><small>' . $user_bsa_ID . '</small></td>
				<td class="border " nowrap>' . $user_name . '</td>
				<td class="border "><small>' . $this_email . '</small></td>

				<td class="border text-center">' . $max_year . '</td>

				<td class="border text-center ' . $basic_hilite . '">' . $basic_completed . '</td>
				<td class="border text-center ' . $arrow_hilite . '">' . $arrowhead_completed . '</td>
				<td class="border text-center ' . $commkey_hilite . '">' . $comm_key_completed . '</td>

				<td class="border text-center">' . $bcs_count . '</td>
				<td class="border text-center">' . $mcs_count . '</td>
				<td class="border text-center">' . $dcs_count . '</td>
				<td class="border text-center">' . $ced_count . '</td>
				<td class="border text-center">' . $everything_count . '</td>

				<td class="border text-center">' . $user_bcs . '</td>
				<td class="border text-center">' . $user_mcs . '</td>
				<td class="border text-center">' . $user_dcs . '</td>

				<td class="border text-center" style="' . $hilite . '">' . $bcs_score . '%</td>
				<td class="border text-center" style="' . $hilite . '">' . $mcs_score . '%</td>
				<td class="border text-center" style="' . $hilite . '">' . $dcs_score . '%</td>

				<td class="border text-center">' . $action_button . '</td>
				</tr>';

				$copy_contact_data .= '<tr><td border>' . $user_bsa_ID . '</td><td border>' . $user_name . '</td><td border>' . $user_email2 . '</td></tr>';

			}

		} // display_data
	}
	if( $y == 0 )
	{
		$return_data .= '<tr><td colspan="24" class="text-danger"><em>No records.</em></td></tr>';
	}
}
$return_data .= '</tbody></table>';
$copy_contact_data .= '</table>';

$return_data .= '<textarea name="data' . $this_type . '" id="data' . $this_type . '" style="display:none;">' . $copy_contact_data . '</textarea>';

$return_data .= '
	<button id="copybtn_' . $this_type . '" onclick="copyToClipboard(\'#data' . $this_type . '\')" style="background:gray;color:white;" class="btn btn-secondary btn-xs" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="COPY ' . $msg . ' EMAILS">Paste ' . $msg . ' contact information into Excel&nbsp;<i class="far fa-copy"></i> </button>
';
$return_data .= '<hr />';

echo $return_data;
?>
