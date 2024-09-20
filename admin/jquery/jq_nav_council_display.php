<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	// include 	 $config_form_elements;
	include 	 $config_arrays;
	// include 	 $config_functions_custom;

	$data = file_get_contents("php://input");
	$mydata = json_decode($data, true);
		$admin_user = $mydata['adminUser'];
		$this_id = $mydata['adminCouncilSelect'];

	$patch = $council_name = $image = '';

	$sql = "
	SELECT *
	FROM `councils`
	WHERE 1=1
	AND `council_ID` = '" . $this_id . "'
	";
	$results = mysqli_query($con_master,$sql);

	$cnt = mysqli_num_rows($results) ?? 0;
	while( $row = mysqli_fetch_assoc($results) )
	{
		$council_ID = $row['council_ID'];
		$council_bsa_ID = $row['council_bsa_ID'];
		$council_name = $row['council_name'];
		$council_patch = $row['council_patch'];

		$image = strlen($council_patch ?? '') > 0 && file_exists('/var/www/html/img/img_councils/' . $council_patch . '') !== false ? '<img src="../img/img_councils/' . $council_patch . '" id="council_strip" title="' . $council_name . ' Council Strip" class="council-strip">' : '';

	}
	echo $image . ' ' . $council_name;

?>
