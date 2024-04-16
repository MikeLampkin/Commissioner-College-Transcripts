
<?php
	$page_is_protected = 'no';
	$navshow = 'yes';

	$pg_title = "Welcome";
	$pg_keywords = "";
	$pg_description = "";

	$pg = "home";
	$db_table = 'welcome';

	require "includes/header.php";

?>

<script>
	setTimeout(function(){ window.location.href = "transcripts"; }, 100);
</script>

<div class="card">
  <div class="card-header">
    Welcome
  </div>
  <div class="card-body">
    <h5 class="card-title">Where would you like to go?</h5>

		<ul>
			<li><a href="transcripts" class="btn btn-primary">transcripts</a></li>
			<!-- <li><a href="" class="btn btn-primary">Location</a></li> -->
			<!-- <li><a href="" class="btn btn-primary">Location</a></li> -->
			<!-- <li><a href="" class="btn btn-primary">Location</a></li> -->
		</ul>
  </div>
</div>

<form>

</form>


<?php  // ** Lampkin 2023 ** // ?>
<?php require "includes/footer.php"; ?>
