<?php //** Copyright © 2010-2023 Michael H Lampkin - mike@lampkin.net ** //
?>
<?php // ** COLORS public 13.0  ** //
?>

<?php
$page_is_protected = 'yes';
	$navshow = 'yes';
$navshow = 'yes';

$pg_title = "Color Reference";
$pg_keywords = "";
$pg_description = "";

$pg_stats = $pg_title;
$pg = "colors";
$db_table = 'colors';

$var_ID 	= 'color_ID';
$var_active = 'color_active';

include "includes/header.php";

// For shades, multiply each component by 1/4, 1/2, 3/4, etc., of its previous value. The smaller the factor, the darker the shade.

// For tints, calculate (255 - previous value), multiply that by 1/4, 1/2, 3/4, etc. (the greater the factor, the lighter the tint), and add that to the previous value (assuming each.component is a 8-bit integer).

// Original (r,g,b);
// Shade (rs,gs,bs): rs = r * 0.25, gs = g * 0.25, bs = b * 0.25;
// Tint (rt,gt,bt): rt = r + (0.25 * (255 - r)), gt = g + (0.25 * (255 - g)), bt = b + (0.25 * (255 - b))
function oneStepColor($color)
{
	$this_color 		= explode(',', $color);
	$color_r 		= $this_color[0];
	$color_g 		= $this_color[1];
	$color_b 		= $this_color[2];
	$rs = $color_r * 0.75;
	$gs = $color_g * 0.75;
	$bs = $color_b * 0.75;

	$new_rgb = $rs . ',' . $gs . ',' . $bs;
	return $new_rgb;
}



$brightness = '730'; // red + green + blue = 765white
$bright_percent = '30'; // %;
function findScreen($color, $brightness)
{
	// $max_r = 331.245;
	// $max_g = 284.07;
	// $max_b = 404.685;
	// $max_all = 1020;
	$this_color 	= explode(',', $color);
	$color_r 	= $this_color[0];
	$color_g 	= $this_color[1];
	$color_b 	= $this_color[2];

	$color_sum 		= $color_r + $color_g + $color_b;
	$color_ratio 	= $color_sum / $brightness;

	$gray_r  	= round((($color_r * 1.299) * $color_ratio), 0);
	$gray_g 	= round((($color_g * 1.114) * $color_ratio), 0);
	$gray_b 	= round((($color_b * 1.587) * $color_ratio), 0);

	$new_rgb		= $gray_r . ',' . $gray_g . ',' . $gray_b;

	return $new_rgb;
}

function findAlpha($color, $brightness)
{
	$this_color 	= explode(',', $color);
	$color_r 	= $this_color[0];
	$color_g 	= $this_color[1];
	$color_b 	= $this_color[2];

	$color_sum 		= $color_r + $color_g + $color_b;
	if ($color_sum < 30) {
		$screen = 0.21;
	} else {
		$screen 		= round(1 / ($brightness / $color_sum), 2);
	}

	$new_rgba		= $color_r . ',' . $color_g . ',' . $color_b . ',' . $screen;

	return $new_rgba;
}

function findLite($color, $brightness)
{
	$this_color 	= explode(',', $color);
	$color_r 	= $this_color[0];
	$color_g 	= $this_color[1];
	$color_b 	= $this_color[2];

	$color_sum 		= $color_r + $color_g + $color_b;
	$color_ratio 	= round(($color_sum / $brightness), 2);

	$gray_r  	= round(($color_r * $color_ratio), 0);
	$gray_g 	= round(($color_g * $color_ratio), 0);
	$gray_b 	= round(($color_b * $color_ratio), 0);

	$new_rgba		= $color_r . ',' . $color_g . ',' . $color_b . ',' . $color_ratio;

	return $new_rgba;
}

$colorgroup_array = array(
	"Red",
	"Pink",
	"Orange",
	"Yellow",
	"Brown",
	"Green",
	"Cyan",
	"Blue",
	"Purple",
	"White",
	"Gray"
);

?>

<table class="table table-sm table-striped table-hover">
	<thead>
		<tr>
			<th scope="col" class="main-clr font-weight-bold text-uppercase font-small border-right" nowrap> ID </th>
			<th scope="col" class="main-clr font-weight-bold text-uppercase font-small border-right" nowrap> Color Name </th>
			<th scope="col" class="main-clr font-weight-bold text-uppercase font-small border-right" nowrap> Copy/Paste Name </th>
			<th scope="col" class="main-clr font-weight-bold text-uppercase font-small border-right" nowrap> Text </th>
			<th scope="col" class="main-clr font-weight-bold text-uppercase font-small border-right" nowrap> HEX </th>
			<th scope="col" class="main-clr font-weight-bold text-uppercase font-small border-right" nowrap> RGB </th>
			<th scope="col" class="main-clr font-weight-bold text-uppercase font-small border-right" nowrap> Overlay </th>
			<th scope="col" class="main-clr font-weight-bold text-uppercase font-small border-right" nowrap> Soft A </th>
			<th scope="col" class="main-clr font-weight-bold text-uppercase font-small border-right" nowrap> Soft B </th>
			<th scope="col" class="main-clr font-weight-bold text-uppercase font-small border-right" nowrap> Step 1 </th>
			<th scope="col" class="main-clr font-weight-bold text-uppercase font-small border-right" nowrap> Step 2 </th>
			<th scope="col" class="main-clr font-weight-bold text-uppercase font-small border-right" nowrap> Step 3 </th>
		</tr>
	</thead>
	<tbody>

		<?php


		foreach ($colorgroup_array as $group) {
			echo '
			<tr>
				<td colspan="12"><div class="h6">Group: ' . $group . '</div></td>
			</tr>
			';

			$sql = "
			SELECT *
			FROM `colors`
			WHERE `color_group` = '" . $group . "'
			ORDER BY `color_ID`
			";
			// echo nl2br($sql) . '<br />';
			$results = mysqli_query($con, $sql);
			$results_cnt = mysqli_num_rows($results);
			$cnt = mysqli_num_rows($results);

			while ($row = mysqli_fetch_assoc($results)) {
				$color_ID = $row['color_ID'];
				$color_name = $row['color_name'];

				$color_rgb = $row['color_rgb'];
				$color_hex = $row['color_hex'];
				$color_group = $row['color_group'];

				$color_overlay = $row['color_overlay'];
				$text_clr = "color:#000;";
				if ($color_overlay == 'Light') {
					$text_clr = "color:#fff;";
				}
				$softA_rgb = findAlpha($color_rgb, $brightness);
				$softB_rgb = findScreen($color_rgb, $brightness);
				$softC_rgb = findLite($color_rgb, $brightness);

				$step1 = oneStepColor($color_rgb);
				$step2 = oneStepColor($step1);


				echo '
		<tr>
			<td class="border-right">' . $color_ID . ' </td>
			<td class="border-right">' . $color_name . ' </td>
			<td class="border-right">' . strtolower($color_name) . ' </td>
			<td class="border-right"><span style="color:' . $color_hex . ';">Sample <strong>Sample</strong></span> </td>
			<td class="border-right" style="background-color:' . $color_hex . ';' . $text_clr . '"> ' . $color_hex . ' </td>
			<td class="border-right" style="background-color:rgb(' . $color_rgb . ');' . $text_clr . '"> ' . $color_rgb . ' </td>
			<td class="border-right" style="background-color:' . $color_hex . ';' . $text_clr . '"> ' . $color_overlay . ' </td>
			<td class="border-right" style="background-color:rgba(' . $softA_rgb . ');' . $text_clr . '"> ' . $softA_rgb . ' </td>
			<td class="border-right" style="background-color:rgba(' . $softC_rgb . ');' . $text_clr . '"> ' . $softC_rgb . ' </td>
			<td class="border-right" style="background-color:rgb(' . $softB_rgb . ');' . $text_clr . '"> ' . $softB_rgb . ' </td>
			<td class="border-right" style="background-color:rgb(' . $step1 . ');' . $text_clr . '"> ' . $step1 . ' </td>
			<td class="border-right" style="background-color:rgb(' . $step2 . ');' . $text_clr . '"> ' . $step2 . ' </td>
		</tr>
	';
			}
		}
		$text_clr = "color:#000;";
		$group = '';
		?>
	</tbody>
</table>

<div class="alert alert-info">
	<div class="h3"> CSS </div>
	<div class="alert alert-warning">
		<div class="h4"> Copy and Paste into colors.css </div>

		<table class="table table-sm table-borderless">
			<tbody>
				<?php

				foreach ($colorgroup_array as $group) {
					$sql = "
						SELECT *
						FROM `colors`
						WHERE `color_group` = '" . $group . "'
						ORDER BY `color_ID`
						";

					echo '
			<tr>
				<td colspan="2"> /* ' . $group . ' Group  */ </td>
			</tr>
		';

					$results = mysqli_query($con, $sql);
					while ($row = mysqli_fetch_assoc($results)) {
						$color_ID = $row['color_ID'];
						$color_name = $row['color_name'];

						$color_rgb = $row['color_rgb'];
						$color_hex = $row['color_hex'];

						$color_overlay = $row['color_overlay'];
						$text_clr = "color:#000;";
						if ($color_overlay == 'Light') {
							$text_clr = "color:#fff;";
						}
						$color_group = $row['color_group'];
						$active_clr = "color:#000;";
						if ($color_overlay == 'Light') {
							$text_clr = "color:#fff;";
						}

						$softA_rgb = findAlpha($color_rgb, $brightness);
						$softB_rgb = findScreen($color_rgb, $brightness);
						$softC_rgb = findLite($color_rgb, $brightness);

						echo '
			<tr>
				<td colspan="2"> /*  ' . $color_name . ' // ' . $color_hex . ' // ' . $color_rgb . '  */ </td>
			</tr>
			<tr>
				<td> </td>
				<td> .bg-' . strtolower($color_name) . ' { background-color: rgba(' . strtolower($color_rgb) . ',1.0); } </td>
			</tr>

			<tr>
				<td> </td>
				<td> .bg-' . strtolower($color_name) . '-soft { background-color: rgba(' . strtolower($softA_rgb) . '); } </td>
			</tr>

			<tr>
				<td> </td>
				<td> .text-' . strtolower($color_name) . ' { color: rgba(' . strtolower($color_rgb) . ',1.0); } </td>
			</tr>

			<tr>
				<td> </td>
				<td> .btn-' . strtolower($color_name) . ' { background-color: rgba(' . strtolower($color_rgb) . ',0.7); border-color: rgba(' . strtolower($color_rgb) . ',1.0); } </td>
			</tr>
			<tr>
				<td> </td>
				<td> .btn-' . strtolower($color_name) . ':hover { background-color: rgba(' . strtolower($color_rgb) . ',1.0); } </td>
			</tr>
			<tr>
				<td> </td>
				<td> .btn-' . strtolower($color_name) . ':active { background-color: rgba(' . strtolower($color_rgb) . ',1.0); } </td>
			</tr>
			<tr>
				<td> </td>
				<td> .btn-' . strtolower($color_name) . ':visited { background-color: rgba(' . strtolower($color_rgb) . ',1.0); border-color: rgba(' . strtolower($color_rgb) . ',0.7);  } </td>
			</tr>

		';
					}
				}

				// <tr>
				// 	<td> .btn-' . strtolower($color_name). ' { background-color: var(--' . strtolower($color_name) . '); border-color: rgba(' . strtolower($clr_visited) . ');} </td>
				// </tr>
				// <tr>
				// 	<td> .btn-' . strtolower($color_name). ':hover { background-color: var(--' . strtolower($clr_hover) . ');} </td>
				// </tr>
				// <tr>
				// 	<td> .btn-' . strtolower($color_name). ':active { background-color: var(--' . strtolower($clr_hover) . ');} </td>
				// </tr>
				// <tr>
				// 	<td> .btn-' . strtolower($color_name). ':visited { background-color: var(--' . strtolower($clr_visited) . ');} </td>
				// </tr>
				?>
			</tbody>
		</table>

	</div>


	<?php  // ** Lampkin 2023 ** //
	?>
	<?php require "includes/footer.php"; ?>
