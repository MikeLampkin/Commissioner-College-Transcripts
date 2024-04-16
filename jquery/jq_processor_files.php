<?php
//!  PROCESSOR 2023 :: 2.1
//*  [================ FILE UPLOAD ================]
// echo '<h3>files ' . $attachment_field_name . ' </h3> <br />';
// echo '<span class="text-danger">Image processing: <strong>' . $attachment_field_name . ' </strong></span><br />';

$attachment_field_upload 	= ( isset($_FILES['attachment_field_upload']) ) ? $_FILES['attachment_field_upload'] : 'uploads';
$attachment_directory 	= ( isset($_POST['attachment_directory']) ) ? $_POST['attachment_directory'] : 'uploads';
$attachment_action 		= ( isset($_POST['attachment_action']) ) ? $_POST['attachment_action'] : 'overwrite' ;
$attachment_rename 		= ( isset($_POST['attachment_rename']) ) ? $_POST['attachment_rename'] : '' ;
$attachment_prefix 		= ( isset($_POST['attachment_prefix']) ) ? $_POST['attachment_prefix'] : '' ;
$attachment_suffix 		= ( isset($_POST['attachment_suffix']) ) ? $_POST['attachment_suffix'] : '' ;
$attachment_type 		= ( isset($_POST['attachment_type']) ) ? $_POST['attachment_type'] : 'pdf' ;
$attachment_sizelimit 	= ( isset($_POST['attachment_sizelimit']) ) ? $_POST['attachment_sizelimit'] : '3000000' ; // 3,000,000 = 3MB
$attachment_resize 		= ( isset($_POST['attachment_resize']) ) ? $_POST['attachment_resize'] : '' ; // resize the file
$attachment_crop		= ( isset($_POST['attachment_crop']) ) ? $_POST['attachment_crop'] : '' ; // resize the file

//* If the upload is part of a list of images.

	unset($_POST['attachment_directory']);
	unset($_POST['attachment_action']);
	unset($_POST['attachment_rename']);
	unset($_POST['attachment_prefix']);
	unset($_POST['attachment_suffix']);
	unset($_POST['attachment_type']);
	unset($_POST['attachment_sizelimit']);

	unset($_POST['attachment_resize']);
	unset($_POST['attachment_crop']);

//*  [ UPLOAD ]
$uploadOk = 'yes';
// if( !empty($_FILES['userfile']) && count($_FILES['userfile']) == 1 )
if( !empty($_FILES['userfile']) && !empty(trim($_FILES['userfile']['tmp_name'])) )
{
	$uploadTempName = $_FILES['userfile']['tmp_name'];

	//* Allow certain file formats
	// if ( $attachment_type == 'image' )
	// {
	// 	$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
	// 	$detectedType = exif_imagetype($_FILES['userfile']['tmp_name']);
	// 	if( !in_array($detectedType, $allowedTypes) )
	// 	{
	// 		$error_msg = "Sorry, not an allowed file.";
	// 		$uploadOk = 'no';
	// 	}
	// }

	//* Check file size
	// if ( isset($attachment_sizelimit) )
	// {
	// 	if ( $_FILES['userfile']['size'] > $attachment_sizelimit )
	// 	{
	// 		$error_msg = "Sorry, your file is too large.";
	// 		$uploadOk = 'no';
	// 	}
	// }

	//* Check if file already exists
	// if ( $attachment_action == 'attach' )
	// {
	// 	if ( file_exists($attachment_directory . '/' . $_FILES['userfile']['name']) )
	// 	{
	// 		$error_msg = "Sorry, file already exists.";
	// 		$uploadOk = 'no';
	// 	}
	// }

	//* START NAME THE FILE
	$uploadFile = basename($_FILES['userfile']['name']); // START
	$uploadFileType = strtolower(pathinfo($uploadFile,PATHINFO_EXTENSION)); // EXT

	//* REPLACE THE NAME?
	if ( !empty($attachment_rename) )
	{
		if( $uploadFileType == 'jpeg' ) { $uploadFileType = 'jpg'; }
		$uploadFile = $attachment_rename . '.' . $uploadFileType;
	}

	//* ADD A PREFIX?
	if ( !empty($attachment_prefix) )
	{
		$uploadFile = $attachment_prefix . '_' . $uploadFile;
	}

	//* ADD A SUFFIX?
	if ( !empty($attachment_suffix) )
	{
		$uploadFile = $uploadFile . '_' . $attachment_suffix;
	}

	//* FINISH NAMING THE FILE
	// $uploadFilenew = $uploadFile . '.' . $uploadFileType;
	$uploadFilenew = cleanName($uploadFile);
	$uploadFilenew_loc = getcwd() . '/' . $attachment_directory . '/' . $uploadFilenew;

	//* Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 'no')
	{


	}
	//* If everything is ok, try to upload file
	if ($uploadOk == 'yes')
	{
		if ( move_uploaded_file($uploadTempName, $uploadFilenew_loc) )
		{
			$_POST[$attachment_field_name] =  $uploadFilenew;

			chmod($uploadFilenew_loc, 0777);

			// if(!file_exists($uploadFilenew_loc)) {
			// 	die("File not found with path: " .$uploadFilenew_loc);
			//   }
		}
	}
	else
	{
		$post_response_msg .= 'Error: Here is some more debugging info:<code>' . print_r($_FILES) .'</code><br />';

		$phpFileUploadErrors = array(
			0 => 'There is no error, the file uploaded with success',
			1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
			2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
			3 => 'The uploaded file was only partially uploaded',
			4 => 'File was not uploaded',
			6 => 'Missing a temporary folder',
			7 => 'Failed to write file to disk.',
			8 => 'A PHP extension stopped the file upload.',
		);

			$post_response_msg .= 'Error code key for above listed error:<br />';
			$error_num = $_FILES['userfile']['error'];
			$post_response_msg .= $phpFileUploadErrors[$error_num];

			// $_SESSION['response'] = 'error|Error: ' . $post_response_msg;
echo 'error|Error: ' . $post_response_msg;
	}
}
//*  [================ end FILE UPLOAD ================]
?>
