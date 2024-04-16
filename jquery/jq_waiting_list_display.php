<?php  // ** Lampkin 2023 ** // ?>
<?php // ** v 5.00 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/query.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/form_elements.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/arrays.php';

	include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/functions_custom.php";

	$waiting_list_fields_array = query('getColumns', 'waiting_list', $db_name, '', '', '');

	foreach( $waiting_list_fields_array as $key => $value )
	{
		$$value = '';
	}
?>
<ol>
<?php
	$sql = "
	SELECT *
	FROM `waiting_list`
	WHERE 1=1
	AND `wl_active` LIKE 'yes'
	";
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;
	if ($cnt > 0)
	{
		while ($row = mysqli_fetch_assoc($results))
		{
			foreach( $waiting_list_fields_array as $key => $value )
			{
				$$value = $row[$value];
			}
			$council_name = getCouncilFromID($wl_council_ID);
			echo '<li>' . $council_name . '</li>';
		}
	}
?>
</ol>

<?php
	$sql = "
	SELECT *
	FROM `waiting_list`
	WHERE 1=1
	AND `wl_active` = 'uap'
	";
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;
	$verb = $cnt == 1 ? 'is' : 'are';
	echo '<br /><small><em>There ' . $verb . ' ' . $cnt . ' awaiting review.</em></small>';
	?>
