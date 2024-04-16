<?php  // ** Lampkin 2024 ** // ?>

<?php
	$page_is_protected = 'yes';
	$navshow = 'yes';

	$pg_title = "Welcome";
	$pg_keywords = "";
	$pg_description = "";

	$pg = "home";
	$db_table = 'transcripts';

	require "includes/header.php";

?>

<!-- // -- Lampkin 2010 - 2024 -- // -->
<h2 class="">Admin</h2>

<?php
echo 	$_SERVER['SERVER_NAME'];
echo '<br />';
echo $_SERVER['REQUEST_URI'];
echo '<br />';
echo basename($_SERVER['PHP_SELF']);
echo '<br />';
echo basename(__FILE__, '.php');
echo '<br />';



?>

<?php  // ** Lampkin 2024 ** // ?>
<?php require "includes/footer.php"; ?>
