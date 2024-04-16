<?php  // ** Lampkin 2024 ** // ?>
<?php // ** COMMISSIONER PUBLIC NAVBVAR 2.0  ** // ?>

<nav id="mainnav" class="navbar navbar-expand-md navbar-dark fixed-top">
	<div class="container-fluid">

		<a class="navbar-brand" href="https://cts.scoutcrest.org/" data-toggle="tooltip" data-placement="bottom" title="Home">
			<i class="fas fa-user-graduate fas-box"></i> ::CTS::
		</a>

		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">

				<li class="nav-item <?php if(strpos($pg,'transcripts') !== false) { echo 'active';} ?>">
					<a class="nav-link" role="button" href="transcripts"> Transcripts </a>
				</li>

				<li class="nav-item <?php if(strpos($pg,'courses') !== false) { echo 'active';} ?>">
					<a class="nav-link" role="button" href="courses"> Course List </a>
				</li>

				<!-- <li class="nav-item <?php if(strpos($pg,'degrees') !== false) { echo 'active';} ?>">
					<a class="nav-link" role="button" href="degrees"> Degree List </a>
				</li> -->

				<!-- <li class="nav-item <?php if(strpos($pg,'thesis') !== false) { echo 'active';} ?>">
					<a class="nav-link" role="button" href="thesis"> Thesis Library </a>
				</li> -->

				<li class="nav-item <?php if(strpos($pg,'info') !== false) { echo 'active';} ?>">
					<a class="nav-link" role="button" href="info"> Info </a>
				</li>

			</ul>
		</div>

		<div class="" id="navbarNavRight">
			<div class="navbar-text text-center px-4"><span class="h6"><?php echo $app_full_name; ?></span> <span class="" id="navLoc"></span></div>

			<!-- <div class="navbar-brand"> Commissioner Transcript System </div> -->
		</div>

	</div>
</nav>


<?php
	$pg_icon = $app_icon;
	if( !isset($pg_icon) || strlen($pg_icon) < 1 )
	{
		foreach($menu_master_array AS $menu_key => $menu_value)
		{
			if( array_key_exists($pg,$$menu_key) !== false )
			{
				$menu_value_array = explode('|',$menu_value);
				$menu_name = $menu_value_array[0];
				$menu_level = $menu_value_array[1];
				$pg_icon = $menu_value_array[2];
			}
		}
	}
?>
