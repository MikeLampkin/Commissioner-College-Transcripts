<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	// include 	 $config_form_elements;
	include 	 $config_arrays;
	include 	 $config_functions_custom;

	$db_table 	= 'reports';
	$pg_name 	= 'reports';
	$var_ID 	= 'file_ID';
	$var_active = 'file_active';
	$default_sort = 'file_email';
	$data_results = '';

	$display_array = array(
		'file_name' => 'File|file_name',
		'file_date' => 'Date|file_date',
	);

	$type_array = array(
		'users' => 'Users',
		'transcripts' => 'Transcripts',
	);

	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$admin_user = $mydata['adminUser'];
		$admin_council_select = $mydata['adminCouncilSelect'];


	$full_path_dir = '/var/www/html/admin/reports';
	$clean_dir = scandir($full_path_dir,1);

	$clean_dir = array_diff(scandir($full_path_dir,1), array('HOLD','..', '.','.DS_Store','index.php'));
	$clean_dir_cnt = count($clean_dir);

	$display_results .= '<div class="row mt-3">';


	foreach( $type_array AS $type_key => $type_value )
	{
		$display_results .= '<div class="col-md-6">';
		$display_results .= '<h4>' . $type_value . '</h4>';
		$display_results .= '<table class="table table-striped table-bordered table-hover table-sm">';
			$display_results .= '<thead class="table-dark">';
			$display_results .= '<tr>';
			$display_results .= '<th scope="col" width="" class="border text-uppercase " style="font-size:10px;" nowrap> Name </th>';
			$display_results .= '<th scope="col" width="15%" class="border text-uppercase " style="font-size:10px;" nowrap> Size </th>';
			$display_results .= '<th scope="col" width="20%" class="border text-uppercase " style="font-size:10px;" nowrap> Date </th>';
			$display_results .= '<th scope="col" width="5%" class="border text-uppercase" style="font-size:10px;" nowrap> Action </th>';
			$display_results .= '</tr>';
			$display_results .= '</thead>';
			$display_results .= '<tbody>';

			$x=0;
			foreach ($clean_dir as $key => $file_name)
			{
				if( strpos($file_name,$type_key) )
				{
					$x++;
					$file_name_array = explode('_',$file_name);
						$file_council_id = trim($file_name_array[0]);
						// $file_size = prettyFilesize($full_path_file);

						$file_link = file_exists('/var/www/html/admin/reports/' . $file_name) ? '<a href="reports/' . $file_name . '" download>' . $file_name . '</a>' : $file_name;

						$file_size = file_exists('/var/www/html/admin/reports/' . $file_name) ? '<small>'. sizeOfFile(filesize('/var/www/html/admin/reports/' . $file_name)) . '</small>' : '0';

						$file_date = file_exists('/var/www/html/admin/reports/' . $file_name) ? '<small>'. date('M d, Y - g:i a',filemtime('/var/www/html/admin/reports/' . $file_name)) . '</small>' : '0';

						$display_results .= '<tr>';
						$display_results .= '<td class="">' . $file_link . '</td>';
						$display_results .= '<td class="">' . $file_size . '</td>';
						$display_results .= '<td class="">' . $file_date . '</td>';
						$display_results .= '<td class=""><button type="button" class="btn btn-danger btn-xs delete-file-button" data-file="' . $file_name . '" ><i class="fas fa-trash-alt"></i></button></td>';
						$display_results .= ' </tr>';
				}
			}
			$display_results .= '</tbody>';
			$display_results .= '</table>';

		$display_results .= '</div>';
	}
	$display_results .= '</div>';

	echo $display_results;

?>
