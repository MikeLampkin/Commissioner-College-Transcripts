<?php  // ** Lampkin 2023 ** // ?>
<?php // ** v 5.00 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/query.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/form_elements.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/arrays.php';

	include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/functions_custom.php";

	$var_ID 		= 'user_ID';
	$var_active 	= 'user_active';
	$db_table 		= 'users';
	$return_data 	= '';

	$eol="\r\n";

	// $display_array = $db_table . '_display_array';
	$fields_array 	= $db_table . '_fields_array';
	$return_data 	= '';
	$data 			= file_get_contents("php://input");
	$mydata 		= json_decode($data, true);
		$my_council 	= $mydata['myCouncil'];
		// $table 		= $mydata['table'];
		$select_user 	= $mydata['selectUser'];
		$search_terms 	= $mydata['searchTerms'];

		// echo 'jq seeing: <br />';
		// echo 'my_council: ' . $my_council . '<br />';
		// echo 'select_user: ' . $select_user . '<br />';
		// echo 'search_terms: ' . $search_terms . '<br />';
		$active = 'yes';
		$active_sql = $active !== 'all' && strlen($active) > 0 ? "AND LOWER(`" . $var_active . "`) LIKE '" . $active . "'" : "";


		$addl_sql = '';

	//# =============== Search 2024 ======================
	$search_fields_array = array('user_bsa_ID','user_first_name','user_middle_name','user_last_name','user_nick_name','user_maiden_name');
	$search_sql = '';

	if ( strlen(trim($search_terms)) >= 2 )
	{
		//! Does the search_term have a comma or a space?
		$multi_search = strpos(',', trim($search_terms)) !== false || preg_match("/ /", $search_terms) !== false ? 'yes' : 'no';
		if( $multi_search == 'yes' )
		{
			if( strpos(',', trim($search_terms)) !== false )
			 {
				$search_terms_array = explode(',',$search_terms);
			 }
			 if( preg_match("/ /", $search_terms) !== false )
			 {
				$search_terms_array = explode(' ',$search_terms);
			 }
		} else {
			$search_terms_array = explode(',',$search_terms);
		}
		$xx=0;
		foreach($search_terms_array AS $search_item)
		{
			$search_sql .= $eol . "AND (";

			$xx++;
			$conjunction = strpos(trim($search_item), '-') !== false ? 'NOT LIKE' : 'LIKE';
			$search_item = str_replace('-','',trim($search_item));
			$search_item = cleanData(trim($search_item));
			$search_item = cleanName(trim($search_item));
			if ( strlen(trim($search_item)) >= 1 )
			{
				$x_eq=0;
				foreach ( $search_fields_array AS $key => $value )
				{
					$x_eq++;
					$search_sql .= "
					LOWER(`" . $value . "`) " . $conjunction . " '%" . strtolower(trim($search_item)) . "%'";
					if( $x_eq < count($search_fields_array) ) { $search_sql .= " OR "; }
				}
			}
			$search_sql .= $eol . ' )';
		}
	//# =============== Search 2024 ======================

	//! For additional query variations.
	$addl_sql .= '';

	$sql = "
	SELECT *
	FROM `users`
	WHERE 1=1
	AND `user_active` = 'yes'
	AND `user_council_ID` = '" . $my_council . "'
	" . $addl_sql . "
	" . $search_sql . "
	" . $active_sql . "
	ORDER BY `user_last_name`, `user_first_name`
	";
	// $return_data .= nl2br($sql) . '<br />';
	$return_data .= '<div class="h4 my-2 p-0 text-success"> Results: </div>';

	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results);
	if( $cnt > 0 )
	{
		$users_array = array();
		while( $row = mysqli_fetch_assoc($results) )
		{
			foreach($users_fields_array as $field_key => $field_value)
			{
				$$field_value = $row[$field_value];
			}
			$user_name = fullNameList($user_prefix,$user_first_name,$user_nick_name,$user_middle_name,$user_last_name,$user_suffix);
			$users_array[$user_ID] = $user_name;
		}
		asort($users_array);

		$return_data .= '<div class="strong mb-2 pl-3">Click on a name to view transcripts:</div>';
		$return_data .= '<div class="grid gap-3">';

		foreach( $users_array AS $key => $value )
		{
			$selectme = ( $select_user == $key ) ? 'active' : '';
			$return_data .= '
			<div id="select_user' . $key . '" data-info="' . $key . '" class="select-user btn btn-outline-success p-2 g-col-6 ' . $selectme . '">' . $value . '</div>
			';
		}
		$return_data .= '</div>';
	}
	else
	{
		$return_data .= 'Your search returned no data. Please try your search again.';
	}
}
else
{
	$return_data = '';
}
	echo $return_data;
?>
