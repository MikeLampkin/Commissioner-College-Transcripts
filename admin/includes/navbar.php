<?php  // ** Lampkin 2024 ** // ?>
<?php // ** COMMISSIONER PUBLIC NAVBVAR 2.0  ** // ?>
<?php
//# -------- MENU  -----------------------------================

$menu_master_array = array(
	// 'users_pg_array' 	=> '|100|',
	'transcripts_pg_array' 	=> 'Transcripts|100|<i class="fa-solid fa-list-ul"></i>',
	'core_pg_array' 	=> 'Core|100|<i class="fa-solid fa-poll-people"></i>',
	'degree_pg_array' 	=> 'Degree|100|<i class="fa-solid fa-file-user"></i>',
	'reports_pg_array' 	=> 'Reports|100|<i class="fa-solid fa-folder" aria-hidden="true"></i>',
	'admin_pg_array' 	=> 'Admin|200|<i class="fa-solid fa-user-shield" aria-hidden="true"></i>',
	'jedi_pg_array'		=> 'Jedi|999|<i class="fa-solid fa-jedi" aria-hidden="true"></i>',
);

$users_pg_array = array(

);

$transcripts_pg_array = array(
	'transcripts_users' 	=> 'Transcript: By User|100|<i class="fa-solid fa-file-user"></i>',
	'transcripts_courses' 	=> 'Transcript: By Course|100|<i class="fa-solid fa-file-check"></i>',
);

$core_pg_array = array(
	'participants' 			=> 'Participants|100|<i class="fa-solid fa-user-graduate"></i>',
	'courses' 				=> 'Courses|100|<i class="fa-solid fa-book-open-cover"></i>',
	'thesis' 				=> 'Thesis Library|100|<i class="fa-solid fa-book"></i>',
	'deans' 				=> 'Deans|100|<i class="fa-solid fa-chalkboard-user"></i>',
	'districts' 			=> 'Districts|100|<i class="fa-solid fa-school-flag"></i>',
);

$degree_pg_array = array(
	'degree_review' 		=> 'Degree: Review|100|<i class="fa-solid fa-diploma"></i>',
	'degree_team' 			=> 'Degree: Team|100|<i class="fa-solid fa-person-chalkboard"></i>',
);

$reports_pg_array = array(
	'report_dump' 				=> 'Report: Dump|200|<i class="fa-solid fa-file-alt"></i>',
	'report_users_per_course' 	=> 'Report: Users|200|<i class="fa-solid fa-file-alt"></i>',
	'report_attendees' 			=> 'Report: Attendees|200|<i class="fa-solid fa-file-alt"></i>',
	'report_years' 				=> 'Report: Degree by Year|200|<i class="fa-solid fa-file-alt"></i>',
	'report_districts' 			=> 'Report: District|200|<i class="fa-solid fa-file-alt"></i>',
	'report_rosters' 			=> 'Report: Rosters|200|<i class="fa-solid fa-file-alt"></i>',
);


$admin_pg_array = array(
	'admin_users'			=> 'Access|300|<i class="fa-solid fa-user-shield"></i>',
	'bulk_participants'		=> 'Bulk: Import Participants|300|<i class="fa-solid fa-users-between-lines"></i>',
	'bulk_transcripts' 		=> 'Bulk: Import Transcripts|300|<i class="fa-solid fa-file-import"></i>',
);

$jedi_pg_array = array(
	'waiting_list'			=> 'Waiting List|999|<i class="fa-solid fa-badger-honey"></i>',
	'tickets	'			=> 'Tickets|999|<i class="fa-solid fa-compass"></i>',
	'admin_msg'				=> 'Admin Message|999|<i class="fa-solid fa-alicorn"></i>',
	'colors'				=> 'Colors|999|<i class="fa-solid fa-burger-cheese"></i>',
	'stats'					=> 'Stats|999|<i class="fa-solid fa-cat-space"></i>',
	'blockade'				=> 'Blockade|999|<i class="fa-solid fa-face-disguise"></i>',
	'updates'				=> 'Updates|999|<i class="fa-solid fa-person-biking"></i>',
);

?>
<nav id="mainnav" class="navbar navbar-expand-md navbar-dark fixed-top">
	<div class="container-fluid">

		<a class="navbar-brand" href="https://commissionercollege.com/admin" data-toggle="tooltip" data-placement="bottom" title="Home">
			<i class="fa-solid fa-user-graduate fas-box"></i> ::CTS::
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
				<li class="nav-item nocap" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Trouble Ticket" id="ticketBtn"><a class="nav-link text-white" href="ticket"><i class="fa-solid fa-compass" id="ticketIcn"></i></a></li>
			</ul>
		</div>

		<!-- <div class="" id="navbarNavRight"> -->

			<div class="navbar-text text-center px-4"><span class="fw-bold" id="navLoc"></span></div>
			<div class="navbar-text text-center m-0 p-0" style="font-size:10px;line-height:1.25">
				<a class="mx-auto text-white" href="?logout"><?php echo $app_icon ;?> <?php echo ucwords($app_short_name); ?></a>
				<br/ >
				<a class="mx-auto text-white" href="?logout">
					<?php
						if( isset( $admin_user_first_name ) )
						{
							echo $admin_user_first_name . ' ' . $admin_user_last_name;
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

<script>
	let adminUser = $('#adminUser').val();
	localStorage.setItem("adminUser", adminUser);

	let adminCouncilID = $('#adminCouncilID').val();
	let adminCouncilSelect = typeof(localStorage.getItem('adminCouncilSelect')) != "undefined" && localStorage.getItem('adminCouncilSelect') != null ? localStorage.getItem('adminCouncilSelect') : adminCouncilID;
	localStorage.setItem("adminCouncilSelect", adminCouncilSelect);

	function navCouncilDisplay()
	{
		let adminCouncilSelect = localStorage.getItem('adminCouncilSelect');
		let marker = Math.floor(randomNumber(0, 255));

		let mydata = {
			adminUser:adminUser,
			adminCouncilSelect:adminCouncilSelect
		};

		$.ajax({
			url: "jquery/jq_nav_council_display.php?"+ marker,
			method: "POST",
			dataType: "text",
			data: JSON.stringify(mydata),
			success: function(response) {
				$('#navLoc').html(response + ' Admin');
			},
			error: function(response) {
				console.log('ERROR: ' + response);
			}
		});
	}

	function navCouncilSelect()
	{
		let adminCouncilSelect = localStorage.getItem('adminCouncilSelect');
		let marker = Math.floor(randomNumber(0, 255));

		let mydata = {
			adminUser:adminUser,
			adminCouncilSelect:adminCouncilSelect
		};

		$.ajax({
			url: "jquery/jq_nav_council_form.php?"+ marker,
			method: "POST",
			dataType: "text",
			data: JSON.stringify(mydata),
			success: function(response) {
				$('#modalLabel').html('Please Select a Council to Admin');
				$('#modalData').html(response);
				$('#modalFooter').hide();
				$('#modalAlert').modal('show');
			},
			error: function(response) {
				console.log('ERROR: ' + response);
			}
		});
	}

//? ===========>> document ready <<=============
$(document).ready(function()
{
	navCouncilDisplay();

	let councilMultiArray = adminCouncilID.split(',');
	if( councilMultiArray.length > 1 || adminCouncilID == 9999 )
	{
		$(document).on("click", '#navLoc', function(e) {
			e.preventDefault();
			navCouncilSelect();
		});
	}

	if( adminCouncilSelect == 9999 )
	{
		navCouncilSelect();
	}

	$(document).on("click", '#change_council_submit', function(e) {
		e.preventDefault();
		let newValue = $('#change_council_form').val();
		localStorage.removeItem("adminCouncilSelect");
		localStorage.setItem("adminCouncilSelect", newValue);
		$('#modalAlert').modal('hide');
		navCouncilDisplay();
	});
});
</script>
