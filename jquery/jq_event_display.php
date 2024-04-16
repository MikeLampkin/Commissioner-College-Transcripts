<?php  // ** Lampkin 2023 ** // ?>
<?php // ** v 5.00 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/query.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/form_elements.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/arrays.php';

	include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/functions_custom.php";

	$fields_array = $events_fields_array;

	foreach( $fields_array as $key => $value )
	{
		$$value = '';
	}
?>

<table class="table table-striped table-hover">
	<thead>
		<tr class=" small bg-darkblue">
			<th class="text-center text-white fw-bold small bg-darkblue">Date</th>
			<th class="text-center text-white fw-bold small bg-darkblue">Council</th>
			<th class="text-center text-white fw-bold small bg-darkblue">More</th>
		</tr>
	</thead>

	<tbody>
	<?php

	$sql = "
	SELECT *
	FROM `events`
	WHERE 1=1
	AND `e_active` = 'yes'
	AND `e_date` >= '" . $today_d . "'
	ORDER BY `e_date` DESC
	";
	// echo $sql . '<br />';
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;
	if ($cnt > 0) {
		while ($row = mysqli_fetch_assoc($results)) {
			foreach( $fields_array as $key => $value ) {
				$$value = $row[$value];
			}
			$e_date = date('M d, Y', strtotime($e_date));
			$council_name = getCouncilFromBsa_ID($e_council_ID);

			echo '<tr>';
			echo '<td class="">' . $e_date . ' </td>';
			echo '<td class="">' . $council_name . ' </td>';
			echo '<td class=""> <a href="' . $e_url . '" target="_blank"><i class="fa-solid fa-right-to-bracket"></i></a> </td>';
			echo '</tr>';

		}
	}
	?>
	</tbody>
</table>

<?php
	$sql = "
	SELECT *
	FROM `events`
	WHERE 1=1
	AND `e_active` = 'uap'
	AND `e_date` >= '" . $today_d . "'
	";
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;
	$verb = $cnt == 1 ? 'is' : 'are';
	echo '<br /><small><em>There ' . $verb . ' ' . $cnt . ' awaiting review.</em></small>';
	?>

</ul>
