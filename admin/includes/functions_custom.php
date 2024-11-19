<?php
//! CUSTOM FOR COMMISSIONER COLLEGE.COM


function getCouncilFromID($id)
{
	$data = $id == '9999' ? 'ALL Council ' : $id;
	global $con_master;
	$sql = "
	SELECT `council_name`
	FROM `councils`
	WHERE `council_ID` = '" . $id . "'
	";
	$results = mysqli_query($con_master,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;
	if( $cnt > 0 )
	{
		while ($row = mysqli_fetch_assoc($results))
		{
			$data = $row['council_name'];
		}
	}
	return $data;
}


function getCouncilPatch($id)
{
	$data = $id == '9999' ? 'generic.png' : $id;
	global $con_master;
	$sql = "
	SELECT `council_patch`
	FROM `councils`
	WHERE `council_ID` = '" . $id . "'
	";
	$results = mysqli_query($con_master,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;
	if( $cnt > 0 )
	{
		while ($row = mysqli_fetch_assoc($results))
		{
			$data = $row['council_patch'];
		}
	}
	return $data;
}

function getAdminCouncilID($id)
{
	$data = '0';
	global $con;
	$sql = "
	SELECT `admin_council_ID`
	FROM `admin_users`
	WHERE `admin_user_ID` = '" . $id . "'
	";
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;
	if( $cnt > 0 )
	{
		while( $row = mysqli_fetch_assoc($results) )
		{
			$data = $row['admin_council_ID'];
		}
	}
	return $data;
}

function getAdminLevel($id)
{
	$data = '0';
	global $con;
	$sql = "
	SELECT `admin_level`
	FROM `admin_users`
	WHERE `admin_user_ID` = '" . $id . "'
	";
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;
	if( $cnt > 0 )
	{
		while( $row = mysqli_fetch_assoc($results) )
		{
			$data = $row['admin_level'];
		}
	}
	return $data;
}

function getAdminLevelName($id)
{
	$data = '';
	global $con;
	$sql = "
	SELECT `level_name`
	FROM `admin_levels`
	WHERE `level_code` = '" . $id . "'
	";
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;
	if( $cnt > 0 )
	{
		while( $row = mysqli_fetch_assoc($results) )
		{
			$data = $row['level_name'];
		}
	}
	return $data;
}

function getAdminLevelDesc($id)
{
	$data = '';
	global $con;
	$sql = "
	SELECT `level_desc`
	FROM `admin_levels`
	WHERE `level_code` = '" . $id . "'
	";
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;
	if( $cnt > 0 )
	{
		while( $row = mysqli_fetch_assoc($results) )
		{
			$data = $row['level_desc'];
		}
	}
	return $data;
}

function getAdminLevelIcon($id)
{
	$data = '';
	global $con;
	$sql = "
	SELECT `level_icon`
	FROM `admin_levels`
	WHERE `level_code` = '" . $id . "'
	";
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;
	if( $cnt > 0 )
	{
		while( $row = mysqli_fetch_assoc($results) )
		{
			$data = $row['level_icon'];
		}
	}
	return $data;
}

function getDistrictName($id)
{
	$data = '';
	global $con;
	$sql = "
	SELECT `district_name`
	FROM `districts`
	WHERE `district_ID` = '" . $id . "'
	";
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;
	if( $cnt > 0 )
	{
		while( $row = mysqli_fetch_assoc($results) )
		{
			$data = $row['district_name'];
		}
	}
	return $data;
}

function getUsersDistrictID($id)
{
	$data = '';
	global $con;
	$sql = "
	SELECT `user_district_ID`
	FROM `user`
	WHERE `user_ID` = '" . $id . "'
	";
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;
	if( $cnt > 0 )
	{
		while( $row = mysqli_fetch_assoc($results) )
		{
			$data = $row['user_district_ID'];
		}
	}
	return $data;
}

function getCourseName($id)
{
	$data = '';
	global $con;
	$sql = "
	SELECT `course_name`
	FROM `courses`
	WHERE `course_ID` = '" . $id . "'
	";
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;
	if( $cnt > 0 )
	{
		while( $row = mysqli_fetch_assoc($results) )
		{
			$data = $row['course_name'];
		}
	}
	return $data;
}

function getCourseNameFull($id)
{
	$data = '';
	global $con;
	$sql = "
	SELECT *
	FROM `courses`
	WHERE `course_ID` = '" . $id . "'
	";
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;
	if( $cnt > 0 )
	{
		while( $row = mysqli_fetch_assoc($results) )
		{
			$course_type = $row['course_type'];
			$course_number = $row['course_number'];
			$course_name = $row['course_name'];

			$data = $course_type . ' ' . $course_number . ' ' . $course_name;
		}
	}
	return $data;
}



function lastCCS($userID)
{
	$data = 'None';
	global $con;
	$sql = "
	SELECT MAX(`transcript_year`) AS `max_year`
	FROM `transcripts`
	WHERE 1=1
	AND `transcript_year` <> '9999'
	AND `transcript_user_ID` = '" . $userID . "'
	";
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;
	if( $cnt > 0 )
	{
		while( $row = mysqli_fetch_assoc($results) )
		{
			$max_year = $row['max_year'];
			$data = $max_year !== '9999' && $max_year > '1969' ? $max_year : 'none';
		}
	}
	return $data;
}


function lastDegree($userID)
{
	global $con, $con_shac;

	$data = '<em>none</em>';
	$sql = "
	SELECT *
	FROM `users`
	WHERE `user_ID` = '" . $userID . "'
	";
	$results = mysqli_query($con,$sql);
	$cnt=0; $cnt = mysqli_num_rows($results);
	if($cnt > 0 )
	{
		while ($row = mysqli_fetch_assoc($results))
		{
			$user_bcs = $row['user_bcs'];
			$user_mcs = $row['user_mcs'];
			$user_dcs = $row['user_dcs'];
		}
	}

	if( $user_dcs > 0 && strlen($user_dcs) > 3 )
	{
		$data = 'DCS: ' . $user_dcs;
	}
	elseif( $user_mcs > 0 && strlen($user_mcs) > 3 )
	{
		$data = 'MCS: ' . $user_mcs;
	}
	elseif( $user_bcs > 0 && strlen($user_bcs) > 3 )
	{
		$data = 'BCS: ' . $user_bcs;
	}

	return $data;
}

function nextPrereq($userID)
{
	global $con, $con_shac;

	$data = '';
	$sql = "
	SELECT *
	FROM `users`
	WHERE `user_ID` = '" . $userID . "'
	";
	$results = mysqli_query($con,$sql);
	$cnt=0; $cnt = mysqli_num_rows($results);
	if($cnt > 0 )
	{
		while ($row = mysqli_fetch_assoc($results))
		{
			$user_basic = $row['user_basic'];
			$user_arrowhead = $row['user_arrowhead'];
			$user_comm_key = $row['user_comm_key'];
			$user_distinguished = $row['user_distinguished'];
		}
	}

	if( $user_basic > 0 && strlen($user_basic) < 3 )
	{
		$data = 'Comm Basic';
	}
	elseif( $user_arrowhead > 0 && strlen($user_arrowhead) < 3 )
	{
		$data = 'Arrowhead';
	}
	elseif( $user_comm_key > 0 && strlen($user_comm_key) < 3 )
	{
		$data = 'Comm Key';
	}
	// elseif( strlen($user_distinguished) > 3 )
	// {
	// 	$data = 'Dist Comm';
	// }

	return $data;
}


function registeredCCS($userbsaID,$ln,$fn,$em)
{
	global $con, $con_shac;
	$thisYear = date('Y');
	// $data = '<span class="text-danger"> <i class="fa-solid fa-circle-xmark"></i> No </span>';
	$data = 'No';

	if( $userbsaID > 0 && strlen($userbsaID) > 4 )
	{
		$sql = "
		SELECT *
		FROM `attendees`
		WHERE `user_year` = '" . $thisYear . "'
		AND `user_bsaID` = '" . $userbsaID . "'
		";
		$results = mysqli_query($con,$sql);
		$cnt=0; $cnt = mysqli_num_rows($results);
		if($cnt < 1 )
		{
			$asql = "
			SELECT *
			FROM `attendees`
			WHERE `user_year` = '" . $thisYear . "'
			AND LOWER(`user_email`) LIKE '%" . strtolower($em) . "%'
			";
			$aresults = mysqli_query($con,$asql);
			$acnt=0; $acnt = mysqli_num_rows($aresults);
				if($acnt > 1 )
				{
					// $data = '<span class="text-success"><i class="fa-solid fa-badge-check"></i> Yes </span>';
					$data = 'yes';
				}
		}
		else
		{
			// $data = '<span class="text-success"><i class="fa-solid fa-badge-check"></i> Yes </span>';
			$data = 'yes';
		}
	}
	return $data;
}

?>
