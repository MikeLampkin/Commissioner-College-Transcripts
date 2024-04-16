<?php
//! CUSTOM FOR COMMISSIONER COLLEGE.COM


function getCouncilFromBsa_ID($id)
{
	$council_name = $id;
	global $con_master;
	$sql = "
	SELECT `council_name`
	FROM `councils`
	WHERE `council_bsa_ID` = '" . $id . "'
	";
	$results = mysqli_query($con_master,$sql);
	while ($row = mysqli_fetch_assoc($results)) {
		$council_name = $row['council_name'];
	}
	return $council_name;

}


?>
