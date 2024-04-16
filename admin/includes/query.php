<?php  // ** Lampkin 2023 ** // ?>
<?php // ** QUERY 9.02 :: $thisCon | test | getArrayMulti | searchDAta getCntDistinct  ** // ?>

<?php

function query($qName,$dbTable,$dbName,$var1,$var2,$orderVar,$searchData='',$test='')
{
	// include "/var/www/html/alerts/config/config.php";

	global $con, $con_master, $active, $default_active, $thisID;

	$thisCon = ($dbName !== 'master') ? $con : $con_master;

	// $thisCon = mysqli_connect($db_host,$db_user,$db_pass,$dbName,$db_port);
	if (mysqli_connect_errno()) { printf("Connect failed: %s\n", mysqli_connect_error()); exit(); }

	$orderBy = (!empty($orderVar)) ? "ORDER BY " . $orderVar : '';

	$test = ( !isset($test) || $test == '' || empty($test) ) ? 'No' : $test;

	switch ($qName)
	{
		case "getList":

			if ( !empty($var1) )
			{
				$sql = "
				SELECT  *
				FROM `" . $dbTable . "`
				WHERE " . $var1 . " = '" . $var2 . "'
				" . $searchData . "
				" . $orderBy . "
				";
			}
			elseif ( empty($var1) && !empty($var2) )
			{
				$sql = "
				SELECT  *
				FROM `" . $dbTable . "`
				WHERE 1=1
				AND " . $var2 . "
				" . $orderBy . "
				";
			}
			else
			{
				$sql = "
				SELECT  *
				FROM `" . $dbTable . "`
				WHERE 1 = 1
				" . $searchData . "
				" . $orderBy . "
				";
			}
				if($test=='yes'){ echo nl2br($sql).'<br />';}
			$results = mysqli_query($thisCon,$sql);
			$cnt = mysqli_num_rows($results);

			if ($cnt >0) { $query_data = $results; }
			else { $query_data = ''; }

			$query_data = $results;

		break;

		case "getCnt":

			if ( !empty($var2) )
			{
				$sql = "
				SELECT  *
				FROM `" . $dbTable . "`
				WHERE " . $var1 . " = '" . $var2 . "'
				" . $searchData . "
				";
			}
			else
			{
				$sql = "
				SELECT  " . $var1 . "
				FROM `" . $dbTable . "`
				";
			}
				if($test=='yes'){ echo nl2br($sql).'<br />';}
			$results = mysqli_query($thisCon,$sql);
			$cnt = mysqli_num_rows($results);

			$query_data = $cnt;

		break;

		case "getCntDistinct":

				$sql = "
				SELECT  DISTINCT(" . $var1 . ")
				FROM `" . $dbTable . "`
				";
				if($test=='yes'){ echo nl2br($sql).'<br />';}
			$results = mysqli_query($thisCon,$sql);
			$cnt = mysqli_num_rows($results);

			$query_data = $cnt;

		break;

		case "getThisItem":

			$sql = "
			SELECT  *
			FROM `" . $dbTable . "`
			WHERE `" . $var1 . "` = '" . $var2 . "'
			";
				if($test=='yes'){ echo nl2br($sql).'<br />';}
			$results = mysqli_query($thisCon,$sql);
			$cnt = mysqli_num_rows($results);

			if ($cnt > 0) { $query_data = $results; }
			else { $query_data = ''; }

			$query_data = $results;

		break;

		case "getColumns":

			$sql = "
			SHOW COLUMNS
			FROM `" . $dbTable . "`
			";

			if(!empty($searchData)) { $sql = $sql . $searchData; }

				if($test=='yes'){ echo nl2br($sql).'<br />';}
			$results = mysqli_query($thisCon,$sql);
			$cnt = mysqli_num_rows($results);

			$input_fields_list =  '';
			$x=0;
			while( $row = mysqli_fetch_assoc($results) )
			{
				$input_fields_list .=  $row['Field'];
				$x++;
				if ($x < $cnt)
				{
					$input_fields_list .=  ',';
				}
			}

			$query_data = explode(',', $input_fields_list);

		break;

		case "getArrayMulti":

			$sql = "
			SELECT `" . $var1 . "`,`" . $var2 . "`
			FROM `" . $dbTable . "`
			ORDER BY `" . $var1 . "`
			";

				if($test=='yes'){ echo nl2br($sql).'<br />';}
			$results = mysqli_query($thisCon,$sql);
			$cnt = mysqli_num_rows($results);

			$array = [];
			while( $row = mysqli_fetch_assoc($results) )
			{
				$$var1 		= trim($row[$var1]);
				$$var2 		= trim($row[$var2]);
				$array[$$var1] = $$var2;
			}
			$query_data = $array;
		break;


	default:
		$query_data = "ERROR";
	}

return $query_data;

}

?>
