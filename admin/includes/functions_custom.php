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
?>
