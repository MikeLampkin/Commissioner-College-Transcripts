<?php  // ** Lampkin 2024 ** // ?>

<?php
	$page_is_protected = 'no';
	$navshow = 'yes';

	$pg_title = "Welcome";
	$pg_keywords = "";
	$pg_description = "";

	$pg = "home";
	$db_table = 'transcripts';

	require "includes/header.php";

?>


<?php

$fields_array = query('getColumns', 'councils', 'master', '', '', '');


$sql = "
SELECT *
FROM `councils`
WHERE `council_active` = 'yes'
ORDER BY `council_name`
";
$results = mysqli_query($con_master,$sql);
while ($row = mysqli_fetch_assoc($results)) {
	foreach( $fields_array as $key => $value ) {
		$$value = $row[$value];
	}

	echo '<div class="row">';
	echo '<div class="col border"> ' . $council_ID . '</div>';
	echo '<div class="col border"> ' . $council_name . '</div>';
	echo '<div class="col border"> ' . $council_city . '</div>';
	echo '<div class="col border"> ' . $council_state . '</div>';
	echo '<div class="col border"> ' . $council_bsa_ID . '</div>';
	echo '<div class="col border"> ' . $council_last_update . '</div>';
	echo '</div>';
}

?>


<?php  // ** Lampkin 2024 ** // ?>
<?php require "includes/footer.php"; ?>
