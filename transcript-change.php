<?php  // ** Lampkin 2024 ** // ?>

<?php
	$page_is_protected = 'no';
	$navshow = 'yes';

	$pg_title = "Change Transcript";
	$pg_keywords = "";
	$pg_description = "";

	$pg = "home";
	$db_table = 'transcripts';

	require "includes/header.php";

?>

<h2> Change Transcript </h2>

<?php
// $this_data = isset( $_GET )
print_r($_GET);

?>
<br />

<button id="closeWindow" class="btn btn-info" onClick="javascript:window.close('','_parent','');">Close Tab</button>


<?php  // ** Lampkin 2024 ** // ?>
<?php require "includes/footer.php"; ?>
