<?php  // ** Lampkin 2024 ** // ?>

<?php
	$page_is_protected = 'no';
	$navshow = 'yes';

	$pg_title 		= "Course List";
    $pg_keywords 	= "course list, boy scout, commissioner, bsa, transcripts, commissioner college";
    $pg_description = "Course List for Commissioner College, BSA";

	$pg 		= 'courses';
	$db_table 	= 'transcripts';

	require "includes/header.php";

?>

	<div class="alert alert-secondary">
		<h5>A list of courses which have been offered currently and in the past. For more information about courses, please visit the National BSA web site at <a href="https://www.scouting.org/commissioners" target="_blank">https://www.scouting.org/commissioners</a> </h5>
	</div>

<table class="table table-striped table-hover">
	<thead>
		<tr class="">
			<th class="text-center bg-dark text-white fw-bold">ID</th>
			<th class="text-center bg-dark text-white fw-bold">Code</th>
			<th class="text-center bg-dark text-white fw-bold">CodeID</th>
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
WHERE `course_active` = 'yes'
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
	echo '<td class="col border"> ' . $course_ID . '</td>';
	echo '<td class="col border"> ' . $course_code . '</td>';
	echo '<td class="col border"> ' . $course_code_ID . '</td>';
	echo '<td class="col border"> ' . $course_type . '</td>';
	echo '<td class="col border"> ' . $course_number . '</td>';
	echo '<td class="col border"> ' . $course_name . '</td>';
	echo '<td class="col border"> ' . $council . '</td>';
	echo '</tr>';
}

?>
	</tbody>
</table>

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



<?php  // ** Lampkin 2024 ** // ?>
<?php require "includes/footer.php"; ?>
