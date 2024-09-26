<?php  // ** Lampkin 2024 ** // ?>
<?php // ** ADMIN NAVBVAR 2.0  ** // ?>
<?php
//# -------- MENU  -----------------------------================

$menu_master_array = array(
	// 'users_pg_array' 	=> '|100|',
	// 'core_pg_array' 	=> 'Core|100|<i class="fa-solid fa-poll-people"></i>',
	// 'batch_pg_array' 	=> 'Batch|100|<i class="fa-solid fa-chart-network" aria-hidden="true"></i>',
	'admin_pg_array' 	=> 'Admin|300|<i class="fa-solid fa-user-shield" aria-hidden="true"></i>',
	'jedi_pg_array'		=> 'Jedi|999|<i class="fa-solid fa-jedi" aria-hidden="true"></i>',
);

$users_pg_array = array (
	'projects' 			=> 'Projects|100|<i class="fa-solid fa-people-roof"></i>',
	'intro' 			=> 'Intro|100|<i class="fa-solid fa-flag-checkered"></i>',
	// 'carousel' 			=> 'Carousel|100|<i class="fa-solid fa-images" aria-hidden="true"></i>',

);

$core_pg_array = array(

);

$batch_pg_array = array(
	'batch_ingest' 			=> 'Data Ingest|100|<i class="fa-solid fa-truck" aria-hidden="true"></i>',
);


$admin_pg_array = array(
	'admin_users'			=> 'Access|200|<i class="fa-solid fa-user-shield" aria-hidden="true"></i>',
);

$jedi_pg_array = array(
	// 'admin_msg'				=> 'Admin Message|999|<i class="fa-solid fa-file-alt" aria-hidden="true"></i>',
	'colors'				=> 'Colors|999|<i class="fa-solid fa-file-alt" aria-hidden="true"></i>',
	// 'blockade'				=> 'Blockade|999|<i class="fa-solid fa-file-alt" aria-hidden="true"></i>',
	// 'updates'			=> 'Updates|999|<i class="fa-solid fa-compass"></i>',
);

?>
<nav id="mainnav" class="navbar navbar-expand-md navbar-dark fixed-top">
	<div class="container-fluid">

		<a class="navbar-brand" >
			<img src="<?php echo $config_nav_logo ?>" alt="Houston Public Library" class="logo p-0 m-0">
		</a>

		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">

			<?php
			if( $_SESSION['loggedin'] == true )
			{
			?>

				<?php
					foreach( $users_pg_array AS $key => $value )
					{
						if( $admin_level >= 100 )
						{
							$value_array = explode('|',$value);
								$nav_pg_name = $value_array[0];
								$nav_pg_level = $value_array[1];
								$nav_pg_icon = $value_array[2];

							$selectme = ( $pg == $key ) ? 'active' : '';
							echo '
							<li class="nav-item ' . $selectme . '">
								<a class="nav-link " aria-current="page" href="' . $key . '">
									' . $nav_pg_icon . ' ' . $nav_pg_name . '
								</a>
							</li>
							';
						}
					}
				?>

				<?php
					foreach( $menu_master_array AS $menu_key => $menu_value )
					{
						$menu_value_array = explode('|',$menu_value);
							$menu_name = $menu_value_array[0];
							$menu_level = $menu_value_array[1];
							$menu_icon = $menu_value_array[2];

						if( $admin_level >= $menu_level )
						{
					?>
						<li class="nav-item dropdown <?php echo (array_key_exists($pg,$$menu_key) !== false) ? 'active' : ''; ?>">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_<?php echo $menu_key; ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								<?php echo $menu_icon; ?> <?php echo $menu_name; ?>
							</a>
							<ul class="dropdown-menu" aria-labelledby="navbarDropdown_<?php echo $menu_key; ?>">
								<?php
									foreach( $$menu_key AS $key => $value )
									{
										$value_array = explode('|',$value);
										$nav_pg_name = $value_array[0];
										$nav_pg_level = $value_array[1];
										$nav_pg_icon = $value_array[2];

										$selectme = ( $pg == $key ) ? 'active' : '';
										echo '
										<li>
											<a class="dropdown-item ' . $selectme . '" href="' . $key . '">
												' . $nav_pg_icon . ' ' . $nav_pg_name . '
											</a>
										</li>
										';
									}
								?>
							</ul>
						</li>
					<?php
						}
					}
				?>

			<?php
			} ?>
				<!-- <li class="nav-item nocap" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Trouble Ticket" id="ticketBtn"><a class="nav-link text-white" href="ticket"><i class="fa-solid fa-compass" id="ticketIcn"></i></a></li> -->
			</ul>
		</div>

		<!-- <div class="" id="navbarNavRight"> -->

			<div class="navbar-text text-center px-4"><span class="h6"><?php echo $app_short_name; ?> Admin</span> <span class="" id="navLoc"></span></div>
			<div class="navbar-text text-center m-0 p-0" style="font-size:10px;line-height:1.25">
				<a class="mx-auto text-white" href="?logout"><?php echo $app_icon ;?> <?php echo ucwords($app_short_name); ?></a>
				<br/ >
				<a class="mx-auto text-white" href="?logout">
					<?php
						if( isset( $admin_first_name ) )
						{
							echo $admin_first_name . ' ' . $admin_last_name;
							echo '<br>';
							if ( isset($level_icon) )
							{
								echo $level_icon . ' <em>' . ucfirst($level_name) . '</em>';
							}
						}
					?>
				</a>
			</div>

		<!-- </div> -->
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
