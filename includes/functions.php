<?php //! Copyright © 2010-2024 Michael H Lampkin - mike@lampkin.net ?>
<?php //! Feb 2024 ?>

<?php /* FUNCTIONS 10.1.0 --

timeLength
timeSpan
prettyMoney
prettyMoneyCents
removePunctuation
getUserIP

dehexMe
swapColor
getRange
getRangeTime
dateDifference
in_array_r
introText


resizeThisImage
cropThisImage

howOld

rowlen

fullNameAge
fullName
fullNameShort
fullNameAll
fullNameList


userInitialsFromID
userFullnameFromID
userFullnameListFromID
userEmailFromID

cleanPhone
prettyPhone
fullAddress
fullFormAddress

showFileVersion
sizeOfFile
prettyFilesizeNumber
prettyFilesize


ordinal

cleanCode
cleanData
cleanName
cleanApostrophes
cleanSpaces
cleanEmail
cleanMoney

randomNumber
randomWord -- No I or l
generatePassword
generateStrongPassword
scramble
unscramble

searchPrep
gmapAddress
gmapLink
geoAddress
geoLatLon

browserName


*/



function timeLength($start, $end)
{
	$response_data = '';
	$sec = strtotime($end) - strtotime($start);
	$days = $sec / 86400 . ' days';
	$hours = $sec / 3600 . ' hours';
	$mins = $sec / 60 . ' minutes';
	$response_data = $hours;

	return $response_data;
}

function timeSpan($start, $end)
{
	$response_data = '';
	$raw_start = strtotime($start);
	$raw_end = (strlen($end) > 4) ? strtotime($end) : '';

	if ($raw_end !== '' && $raw_start !== $raw_end && $raw_end > $raw_start) {
		$response_data = (date('a', $raw_start) !== date('a', $raw_end))
			?
			date('g:i a', $raw_start) . ' - ' . date('g:i a', $raw_end)
			:
			date('g:i', $raw_start) . ' - ' . date('g:i a', $raw_end);
	} else {
		$response_data = date('g:i a', $raw_start);
	}

	return $response_data;
}


function getUserIP()
{
	$response_data = '';
	if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
		$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
		$_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
	}
	$client  = @$_SERVER['HTTP_CLIENT_IP'];
	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$remote  = $_SERVER['REMOTE_ADDR'];

	if (filter_var($client, FILTER_VALIDATE_IP)) {
		$response_data = $client;
	} elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
		$response_data = $forward;
	} else {
		$response_data = $remote;
	}

	return $response_data;
}


function dehexMe($val)
{
	$response_data = '';
	$new_value = dechex($val);
	$response_data = strlen($new_value) < 2 ? '0' . $new_value :  $new_value;
	return $response_data;
}

function swapColor($val)
{
	$response_data = '';
	$cleaners = array('rgba', 'rgb', '#', '(', ')');
	$value_cleaned = str_replace($cleaners, '', strtolower($val));
	if (strpos($value_cleaned, ',') !== false) {
		$partsArray = explode(',', $value_cleaned);
		$partR = $partsArray[0];
		$partG = $partsArray[1];
		$partB = $partsArray[2];
		$response_data = dehexMe($partR) . '' . dehexMe($partG) . '' . dehexMe($partB);
	} else {
		if (strlen($value_cleaned) < 6) {
			$partR = substr($value_cleaned, 0, 1);
			$partG = substr($value_cleaned, 1, 1);
			$partB = substr($value_cleaned, 2, 1);
		} else {
			$partR = substr($value_cleaned, 0, 2);
			$partG = substr($value_cleaned, 2, 2);
			$partB = substr($value_cleaned, 4, 2);
		}
		$response_data = hexdec($partR) . ',' . hexdec($partG) . ',' . hexdec($partB);
	}
	return $response_data;
}




function getRange($start, $end = '', $span = '5')
{
	$response_data = '';
	if (strlen($start) == 4) {
		$start_date = date('Y-m-d', strtotime($start . '-01-01'));
	} else {
		$start_date = date('Y-m-d', strtotime($start));
	}

	if (strlen($end) <= 0) {
		$end_date = date('Y-m-d', strtotime($start . '+5 days'));
	} else {
		$end_date = date('Y-m-d', strtotime($end));
	}

	$start_date 	= strtotime($start_date);
	$end_date 		= strtotime($end_date);

	$start_year 	= date('Y', $start_date);
	$start_month 	= date('m', $start_date);
	$start_day 		= date('d', $start_date);
	$end_year 		= date('Y', $end_date);
	$end_month 		= date('m', $end_date);
	$end_day 		= date('d', $end_date);

	if ($end_date >= $start_date) {
		if ($start_year <> $end_year) {
			$response_data = date('M j, Y', $start_date) . ' - ' . date('M j, Y', $end_date);
		} elseif ($start_month <> $end_month) {
			$response_data = date('M j', $start_date) . ' - ' . date('M j, Y', $end_date);
		} elseif ($start_day <> $end_day) {
			$response_data = date('M j', $start_date) . ' - ' . date('j, Y', $end_date);
		}
	}
	return $response_data;
}

function getRangeTime($start_d, $end_d, $start_t, $end_t)
{
	$response_data = '';
	if (strlen($start_d) == 4) {
		$start_date = date('Y-m-d', strtotime($start_d . '-01-01'));
	} else {
		$start_date = date('Y-m-d', strtotime($start_d));
	}

	if (strlen($end_d) <= 0) {
		$end_date = date('Y-m-d', strtotime($start_d . '+5 days'));
	}
	// elseif( strlen($end) == 6 )
	// {
	// 	$end_date = date('Y-m-d',strtotime($end . '-01'));
	// 	$end_date = date('Y-m-d',strtotime($end_date . '+5 days'));
	// }
	else {
		$end_date = date('Y-m-d', strtotime($end_d));
	}

	$start_date 	= strtotime($start_date);
	$end_date 		= strtotime($end_date);

	$start_year 	= date('Y', $start_date);
	$start_month 	= date('m', $start_date);
	$start_day 		= date('d', $start_date);
	$end_year 		= date('Y', $end_date);
	$end_month 		= date('m', $end_date);
	$end_day 		= date('d', $end_date);

	if ($end_date >= $start_date) {
		if ($start_year <> $end_year) {
			$response_data = date('M j, Y', $start_date) . ' - ' . date('M j, Y', $end_date);
		} elseif ($start_month <> $end_month) {
			$response_data = date('M j', $start_date) . ' - ' . date('M j, Y', $end_date);
		} elseif ($start_day <> $end_day) {
			$response_data = date('M j', $start_date) . ' - ' . date('j, Y', $end_date);
		}
	}
	return $response_data;
}


//////////////////////////////////////////////////////////////////////
//PARA: Date Should In date(Y-m-d) Format
//RESULT FORMAT:
// '%y Year %m Month %d Day %h Hours %i Minute %s Seconds'      =>  1 Year 3 Month 14 Day 11 Hours 49 Minute 36 Seconds
// '%y Year %m Month %d Day'                                    =>  1 Year 3 Month 14 Days
// '%m Month %d Day'                                            =>  3 Month 14 Day
// '%d Day %h Hours'                                            =>  14 Day 11 Hours
// '%d Day'                                                     =>  14 Days
// '%h Hours %i Minute %s Seconds'                              =>  11 Hours 49 Minute 36 Seconds
// '%i Minute %s Seconds'                                       =>  49 Minute 36 Seconds
// '%h Hours                                                    =>  11 Hours
// '%a Days                                                     =>  468 Days
//////////////////////////////////////////////////////////////////////
function dateDifference($date_1, $date_2, $differenceFormat = '%a')
{
	$response_data = '';
	$datetime1 = date_create($date_1);
	$datetime2 = date_create($date_2);

	$interval = date_diff($datetime1, $datetime2);
	$response_data = $interval->format($differenceFormat);

	return $response_data;
}

/* in_array_r() - Is a multidimensional version of PHP's in_array() */
function in_array_r($needle, $haystack, $strict = false)
{
	$response_data = false;
	foreach ($haystack as $item) {
		if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
			$response_data = true;
		}
	}
	return $response_data;
}

function introText($string, $length = '100')
{
	$intro_value = $length;
	$string_cnt = $string;
	$string_cnt = preg_replace("/\r|\n/", "", $string_cnt);

	$response_data = '';
	if (strlen($string_cnt) > $intro_value) {
		// truncate string
		$stringCut = substr($string, 0, $intro_value);
		$endPoint = strrpos($stringCut, ' ');
		//if the string doesn't contain any space then it will cut without word basis.
		$response_data = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
		// $string .= '...';
	}
	return $response_data;
}


//! REQUIRED IMAGEMAGICK TO BE INSTALLED
function resizeThisImage($image, $width, $height, $newname)
{
	$return_data = '';
	// image w /: Exact image location from where this is called.
	// width & height: Size in pixels; can't be zero.
	// overwrite: yes or no; will replace image if yes.
	$im = new Imagick($image);
	$thisPhoto = basename($image);
	$thisPath = dirname($image);
	$imageFileType = strtolower(pathinfo($image, PATHINFO_EXTENSION)); // EXT
	$imageprops = $im->getImageGeometry();
	$blur = 0.9;
	$bestFit = true;

	$cur_width = $imageprops['width'];
	$cur_height = $imageprops['height'];
	if ($cur_width > $cur_height) {
		$newHeight = $height;
		$newWidth = ($height / $cur_height) * $cur_width;
	} else {
		$newWidth = $width;
		$newHeight = ($width / $cur_width) * $cur_height;
	}
	$im->resizeImage($newWidth, $newHeight, Imagick::FILTER_LANCZOS, $blur, $bestFit);
	if (strlen($newname) >= 4) {
		// $return_data = $newname . '.' . $imageFileType;
		$return_data = $newname;
	} else {
		$return_data = $thisPhoto;
	}
	$im->writeImages($thisPath . '/' . $return_data, true);
	// return $thisPath . '/' . $return_data;
	return $return_data;
}

//! REQUIRED IMAGEMAGICK TO BE INSTALLED
function cropThisImage($image, $width, $height, $newname)
{
	$return_data = '';
	$im = new Imagick($image);
	$thisPhoto = basename($image);
	$thisPath = dirname($image);
	$imageFileType = strtolower(pathinfo($image, PATHINFO_EXTENSION)); // EXT
	$imageprops = $im->getImageGeometry();
	$blur = 0.9;
	$bestFit = true;

	$cur_width = $imageprops['width'];
	$cur_height = $imageprops['height'];
	if (empty($width)) {  //! Leave width empty to crop height
		$newHeight = $height;
		$newWidth = $cur_width; //! Same
	} elseif (empty($height)) {  //! Leave height empty to crop width
		$newHeight = $cur_height; //! Same
		$newWidth = $width;
	} else {
		$newHeight = $height;
		$newWidth = $width;
	}
	$startX = '0';
	$startY = '0';
	$im->cropImage($newWidth, $newHeight, $startX, $startY);
	if (strlen($newname) >= 4) {
		$return_data = $newname;
	} else {
		$return_data = $thisPhoto;
	}
	$im->writeImages($thisPath . '/' . $return_data, true);
	return $return_data;
}


//! $ret = Return format. Y or YMD etc.
function howOld($date,$ret='Y')
{
	$response_data = '';
	$itemDate = date('m/d/Y', strtotime($date));
	//explode the date to get month, day and year
	$itemDate = explode("/", $itemDate);
	//get age from date or itemDate
	$response_data = (date("md", date("U", mktime(0, 0, 0, $itemDate[0], $itemDate[1], $itemDate[2]))) > date("md")
		? ((date("Y") - $itemDate[2]) - 1)
		: (date("Y") - $itemDate[2]));
	return $response_data;
}

/* rowlen used in form text areas
$str = text;
$val = number of characters per line;
*/
function rowlen($str, $val)
{
	$response_data = '';
	// if the str only has raw text
	$txt_rowlen = ceil(strlen($str) / $val);
	if ($txt_rowlen < 1) {
		$txt_rowlen = 1;
	}
	// if the str has line breaks
	$rowlen_arr = preg_split('/\n|\r/', $str);
	$arr_rowlen = count($rowlen_arr) - 1;

	// return the greater number
	if ($txt_rowlen > $arr_rowlen) {
		$response_data = $txt_rowlen;
	} else {
		$response_data = $arr_rowlen;
	}

	return $response_data;
}

//! If fullName = youth, name won't be shown.
function fullNameAge($prefix, $first, $nick, $mid, $last, $suffix, $dob = '1950-01-01')
{
	$response_data = '';
	$age_marker = date('Y-m-d', strtotime('-18 year'));

	$prefix = trim($prefix);
	$first = trim($first);
	$nick = trim($nick);
	$mid = trim($mid);
	$last = trim($last);
	$suffix = trim($suffix);

	$dob = date('Y-m-d', strtotime($dob));
	$first = (strtotime($dob) >= strtotime($age_marker)) ? $first[0] . "." : $first;
	$nick = (strtotime($dob) >= strtotime($age_marker) && strlen($nick) > 1) ? $nick[0] . "." : $nick;

	$full_name = '';
	if (isset($prefix) && strlen($prefix) >= 2) {
		$full_name = $prefix . ' ' . $full_name;
	}
	if (isset($first) && strlen($first) >= 1) {
		$full_name = $full_name . ' ' . $first;
	}
	if (isset($mid) && strlen($mid) >= 1) {
		if (strtolower($mid) !== strtolower($first)) {
			$mid = $mid[0];
			$full_name = $full_name . ' ' . $mid;
		}
	}
	if (isset($nick) && strlen($nick) >= 1) {
		if (strtolower($nick) !== strtolower($first)) {
			$full_name = $full_name . ' "' . $nick . '" ';
		}
	}
	if (isset($last) && strlen($last) > 1) {
		$full_name = $full_name . ' ' . $last;
	}
	if (isset($suffix) && strlen($suffix) >= 2) {
		$full_name = $full_name . ', ' . $suffix;
	}

	if (strlen($full_name) < 4) {
		$full_name = 'Unknown';
	}

	$response_data = $full_name;
	return $response_data;
}

function fullName($prefix, $first, $nick, $mid, $last, $suffix)
{
	$response_data = '';
	$prefix = trim($prefix);
	$first = trim($first);
	$nick = trim($nick);
	$mid = trim($mid);
	$last = trim($last);
	$suffix = trim($suffix);
	$full_name = '';
	if (isset($prefix) && strlen($prefix) >= 2) {
		$full_name = $prefix . ' ' . $full_name;
	}
	if (isset($first) && strlen($first) >= 1) {
		$full_name = $full_name . ' ' . $first;
	}
	if (isset($mid) && strlen($mid) >= 1) {
		if (strtolower($mid) !== strtolower($first)) {
			$mid = $mid[0];
			$full_name = $full_name . ' ' . $mid;
		}
	}
	if (isset($nick) && strlen($nick) >= 1) {
		if (strtolower($nick) !== strtolower($first)) {
			$full_name = $full_name . ' "' . $nick . '" ';
		}
	}
	if (isset($last) && strlen($last) > 1) {
		$full_name = $full_name . ' ' . $last;
	}
	if (isset($suffix) && strlen($suffix) >= 2) {
		$full_name = $full_name . ', ' . $suffix;
	}

	if (strlen($full_name) < 4) {
		$full_name = 'Unknown';
	}

	$response_data = $full_name;
	return $response_data;
}

function fullNameShort($prefix, $first, $nick, $mid, $last, $suffix)
{
	$response_data = '';
	$prefix = trim($prefix);
	$first = trim($first);
	$nick = trim($nick);
	$mid = trim($mid);
	$last = trim($last);
	$suffix = trim($suffix);
	$full_name = '';

	if (isset($first) && strlen($first) > 1) {
		$full_name = $first;
	}
	if (isset($nick) && strlen($nick) > 1) {
		if (strtolower($nick) !== strtolower($first)) {
			$full_name = $nick;
		}
	}
	if (isset($last) && strlen($last) > 1) {
		$full_name = $full_name . ' ' . $last;
	}
	if (isset($suffix) && strlen($suffix) >= 2) {
		$full_name = $full_name . ', ' . $suffix;
	}

	if (strlen($full_name) < 4) {
		$full_name = 'Unknown';
	}

	$response_data = $full_name;
	return $response_data;
}

function fullNameAll($prefix, $first, $nick, $mid, $last, $suffix)
{
	$response_data = '';
	$prefix = trim($prefix);
	$first = trim($first);
	$nick = trim($nick);
	$mid = trim($mid);
	$last = trim($last);
	$suffix = trim($suffix);
	$full_name = '';
	if (isset($prefix) && strlen($prefix) >= 2) {
		$full_name = $prefix . ' ' . $full_name;
	}
	if (isset($first) && strlen($first) >= 1) {
		$full_name = $full_name . ' ' . $first;
	}
	if (isset($mid) && strlen($mid) >= 1) {
		if (strtolower($mid) !== strtolower($first)) {
			$full_name = $full_name . ' ' . $mid;
		}
	}
	if (isset($nick) && strlen($nick) >= 1) {
		if (strtolower($nick) !== strtolower($first)) {
			$full_name = $full_name . ' "' . $nick . '" ';
		}
	}
	if (isset($last) && strlen($last) > 1) {
		$full_name = $full_name . ' ' . $last;
	}
	if (isset($suffix) && strlen($suffix) >= 2) {
		$full_name = $full_name . ', ' . $suffix;
	}

	if (strlen($full_name) < 4) {
		$full_name = 'Unknown';
	}
	$response_data = $full_name;
	return $response_data;
}

function fullNameList($prefix, $first, $nick, $mid, $last, $suffix)
{
	$response_data = '';
	$prefix = trim($prefix);
	$first = trim($first);
	$nick = trim($nick);
	$mid = trim($mid);
	$last = trim($last);
	$suffix = trim($suffix);
	$full_name = '';
	if (isset($prefix) && strlen($prefix) >= 2) {
		$full_name = $prefix . ' ' . $full_name;
	}
	if (isset($first) && strlen($first) >= 1) {
		$full_name = $full_name . ' ' . $first;
	}
	if (isset($mid) && strlen($mid) >= 1) {
		if (strtolower($mid) !== strtolower($first)) {
			$mid = $mid[0];
			$full_name = $full_name . ' ' . $mid;
		}
	}
	if (isset($nick) && strlen($nick) >= 1) {
		if (strtolower($nick) !== strtolower($first)) {
			$full_name = $full_name . ' "' . $nick . '" ';
		}
	}
	if (isset($last) && strlen($last) > 1) {
		if (isset($suffix) && strlen($suffix) >= 2) {
			$last = $last . ', ' . $suffix;
		}
		$full_name = $last . ', ' . $full_name;
	}

	if (strlen($full_name) < 4) {
		$full_name = 'Unknown';
	}

	$response_data = $full_name;
	return $response_data;
}


function userInitialsFromID($var)
{
	global $con, $users_fields_array;
	$response_data = '';
	if ($var >= '1') {
		$sql = "
		SELECT *
		FROM `users`
		WHERE `user_ID` = '" . $var . "'
		";
		$results = mysqli_query($con, $sql);
		$cnt = mysqli_num_rows($results);
			while ($row = mysqli_fetch_assoc($results)) {
				foreach ($users_fields_array as $key => $value) {
					$$value = $row[$value];
				}
				$f_initial = $user_first_name[0];
				$l_initial = $user_last_name[0];
				$response_data = $f_initial . $l_initial;
			}
	}
	return $response_data;
}

function userFullnameFromID($var)
{
	global $con, $users_fields_array;
	$response_data = '';
	if ($var >= '1') {
		$sql = "
		SELECT *
		FROM `users`
		WHERE `user_ID` = '" . trim($var) . "'
		";
		$results = mysqli_query($con, $sql);
		$cnt = mysqli_num_rows($results);
			while ($row = mysqli_fetch_assoc($results)) {
				foreach ($users_fields_array as $key => $value) {
					$$value = $row[$value];
				}
				$response_data = fullName($user_prefix, $user_first_name, $user_nick_name, $user_middle_name, $user_last_name, $user_suffix);
			}
	}
	return $response_data;
}

function userFullnameListFromID($var)
{
	global $con, $users_fields_array;
	$response_data = '';
	if ($var >= '1') {
		$sql = "
		SELECT *
		FROM `users`
		WHERE `user_ID` = '" . trim($var) . "'
		";
		$results = mysqli_query($con, $sql);
		$cnt = mysqli_num_rows($results);
			while ($row = mysqli_fetch_assoc($results)) {
				foreach ($users_fields_array as $key => $value) {
					$$value = $row[$value];
				}
				$response_data = fullNameList($user_prefix, $user_first_name, $user_nick_name, $user_middle_name, $user_last_name, $user_suffix);
			}
	}
	return $response_data;
}

function userEmailFromID($id)
{
	$response_data = '';
	global $con, $users_fields_array;
	$sql = "
SELECT *
FROM `users`
WHERE `user_ID` = '" . $id . "'
";
	$results = mysqli_query($con, $sql);
	while ($row = mysqli_fetch_assoc($results)) {
		$response_data = $row['user_email'];
	}
	return $response_data;
}

function cleanPhone($var)
{
	$data = cleanData(cleanName($var));
	$data = stripslashes($data);
	$data = strip_tags($data);
		$bad = array(' ', "(')", ')', '-', '.', '+1', '+');
	$data = str_replace($bad, '', $data);
	$data = ltrim($data, '1');
	$data = ltrim($data, '0');
	$data = strtolower($data);
	$data = str_replace('ext', ' ext', $data);
	return $data;
}

function prettyPhone($var)
{
	$data = cleanPhone(trim($var));
	$response_data = $data; // Just return the number if it fails.
	if (strlen($data) == 10) {
		$response_data = '(' . $data[0] . $data[1] . $data[2] . ') ' . $data[3] . $data[4] . $data[5] . '-' . $data[6] . $data[7] . $data[8] . $data[9];
	}
	return $response_data;
}

function fullAddress($address1, $address2, $city, $state, $zip, $phone = '', $phone2 = '', $email = '')
{
	$response_data = $full_address = '';
	$full_address .= $address1;
	if (isset($address2) && strlen($address2) > 1) {
		$full_address = $full_address . '<br />' . $address2;
	}

	if( strlen($full_address) > 2 )
	{
		$city_state_zip = '<br />' . $city;
		if (isset($state) && strlen($state) > 1) {
			$city_state_zip = $city_state_zip . ', ' . $state;
		}
		if (isset($zip) && strlen($zip) > 1) {
			$city_state_zip = $city_state_zip . ' ' . $zip;
		}
		$full_address .= $city_state_zip;
	}

	if (isset($phone) && strlen($phone) > 1) {
		$full_address = $full_address . '<br />Phone: ' . prettyPhone($phone);
	}

	if (isset($phone2) && strlen($phone2) > 1) {
		$full_address = $full_address . '<br />Phone 2: ' . prettyPhone($phone2);
	}

	if (isset($email) && strlen($email) > 1) {
		$full_address = $full_address . '<br />Email: <a href="mailto:' . $email . '">' . $email . '</a>';
	}

	$response_data = $full_address;
	return $response_data;
}

function fullFormAddress($address1, $address2, $city, $state, $zip)
{
	$response_data = $full_address = '';
	$full_address .= $address1;
	if (isset($address2) && strlen($address2) > 1) {
		$full_address = $full_address . ', ' . $address2;
	}

	if( strlen($full_address) > 2 )
	{
		$city_state_zip = ', ' . $city;
		if (isset($state) && strlen($state) > 1) {
			$city_state_zip = $city_state_zip . ', ' . $state;
		}
		if (isset($zip) && strlen($zip) > 1) {
			$city_state_zip = $city_state_zip . ' ' . $zip;
		}
		$full_address .= $city_state_zip;
	}

	$response_data = $full_address;
	return $response_data;
}

function showFileVersion($filename)
{
	$file_location = realpath($filename);
	$chunk = '---';
	if (file_exists($file_location)) {
		$chunk = $filename . " was last modified: " . date("F d Y H:i:s.", filemtime($file_location));
	}
	return $chunk;
}

// Adds pretty filesize from NUMBER
function sizeOfFile($int)
{
	$response_data = '';
	if ($int < 1024) {
		$response_data = $int . " <small>B</small>";
	} elseif (($int < 1048576) && ($int > 1023)) {
		$response_data = round($int / 1024, 1) . " <small>KB</small>";
	} elseif (($int < 1073741824) && ($int > 1048575)) {
		$response_data = round($int / 1048576, 1) . " <small>MB</small>";
	} else {
		$response_data = round($int / 1073741824, 1) . " <small>GB</small>";
	}
	return $response_data;
}

// Adds pretty filesize from FILE location
function prettyFilesize($str)
{
	$response_data = '';
	$size = filesize($str);
	if ($size < 1024) {
		$response_data = $size . " <small>B</small>";
	} elseif (($size < 1048576) && ($size > 1023)) {
		$response_data = round($size / 1024, 1) . " <small>KB</small>";
	} elseif (($size < 1073741824) && ($size > 1048575)) {
		$response_data = round($size / 1048576, 1) . " <small>MB</small>";
	} else {
		$response_data = round($size / 1073741824, 1) . " <small>GB</small>";
	}
	return $response_data;
}

// Ordinal number
function ordinal($int)
{
	$response_data = '';
	$ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
	if ((($int % 100) >= 11) && (($int % 100) <= 13))
	{
		$response_data =  $int . 'th';
	} else {
		$response_data =  $int . $ends[$int % 10];
	}

	return $response_data;
}

function cleanCode($data)
{
	$response_data = '';
	$response_data = htmlentities(strip_tags(stripslashes($data)));
	return $response_data;
}

function cleanData($data)
{
	$response_data = '';
	$response_data = trim(htmlspecialchars($data));
	return $response_data;
}

function cleanName($data)
{
	$response_data = '';
	$data = stripslashes(strip_tags($data));
	$bad = array('"', '~', '`', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '?', ';', ':', '<', '>', ',', '/', '?', '{', '}', '[', ']', '!');
	$response_data = str_replace($bad, '', $data);
	return $response_data;
}

function cleanApostrophes($data)
{
	$response_data = '';
	$data = stripslashes(strip_tags($data));
	$bad = array("'");
	$response_data = str_replace($bad, '', $data);
	return $response_data;
}

function cleanSpaces($data)
{
	$response_data = '';
	$data = stripslashes(strip_tags($data));
	$bad = array(' ');
	$response_data = str_replace($bad, '', $data);
	return $response_data;
}

function cleanEmail($data)
{
	$response_data = '';
	$data = stripslashes(strip_tags($data));
	$bad = array('"', '~', '`', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '?', ';', ':', '<', '>', ',', '/', '?', '{', '}', '[', ']', '!');
	$data = str_replace($bad, '', $data);
	$response_data = strtolower($data);
	return $response_data;
}

function cleanMoney($data)
{
	$response_data = '';
	$data = stripslashes(strip_tags($data));
	$bad = array('"', '~', '`', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '?', ';', ':', '<', '>', ',', '/', '?', '{', '}', '[', ']', '!');
	$data = str_replace($bad, '', $data);
	$data = number_format($data, 2, '.', '');
	$response_data = strtolower($data);
	return $response_data;
}

function prettyMoney($str)
{
	$response_data = '';
	$data = number_format($str, 0, '.', ',');
	$data = '$' . $data;
	$response_data = strtolower($data);
	return $response_data;
}

function prettyMoneyCents($str)
{
	$response_data = '';
	$data = number_format($str, 2, '.', ' ');
	$data = '$' . $data;
	$response_data = strtolower($data);
	return $response_data;
}

function removePunctuation($str)
{
	$response_data = '';
	$data = str_replace('.', '', $str);
	$response_data = str_replace(',', '', $data);
	return $response_data;
}


function randomNumber($len = 10)
{
	$response_data = '';
	$number_array = array();
	for ($x = 0; $x < $len; $x++) {
		$number_array[] =  rand(0, 9);
	}
	$response_data = implode('', $number_array);
	return $response_data;
}

function randomWord($len = 10)
{
	$response_data = '';
	$word = array_merge(range('a', 'k'), range('m', 'z'), range('A', 'H'), range('J', 'Z'));
	shuffle($word);

	$response_data = substr(implode($word), 0, $len);
	return $response_data;
}


function generatePassword()
{
	$response_data = '';
	$word1_array = array('trustworthy','loyal','helpful','friendly','courteous','kind','obedient','cheerful','thrifty','brave','clean','reverent');
	$word2_array = array('red', 'blue', 'green', 'brown', 'purple', 'white', 'black');
	$word3_array = array('moon', 'toy', 'truck', 'horse', 'banjo', 'drum', 'ball', 'basket');
	$symbol_array = array('@', '#', '$', '%', '&', '!', '*', '-');

	$word1 = $word1_array[rand(1, count($word1_array) - 1)];
	$word2 = $word2_array[rand(1, count($word2_array) - 1)];
	$word3 = $word3_array[rand(1, count($word3_array) - 1)];
	$symbol = $symbol_array[rand(1, count($symbol_array) - 1)];

	$response_data = $word1 . ucfirst($word2) . ucfirst($word3) . ucfirst($symbol) . rand(0, 9) . rand(1, 9);
	return $response_data;
}

function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds')
{
	$response_data = '';
	$all = '';
	$password = '';

	$sets = array();
	if (strpos($available_sets, 'l') !== false)
		$sets[] = 'abcdefghjkmnpqrstuvwxyz';
	if (strpos($available_sets, 'u') !== false)
		$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
	if (strpos($available_sets, 'd') !== false)
		$sets[] = '23456789';
	if (strpos($available_sets, 's') !== false)
		$sets[] = '!@#$%&*?';

	foreach ($sets as $set) {
		$password .= $set[array_rand(str_split($set))];
		$all .= $set;
	}

	$all = str_split($all);
	for ($i = 0; $i < $length - count($sets); $i++) {
		$password .= $all[array_rand($all)];
	}
	$password = str_shuffle($password);

	if ($add_dashes)
	{
		$dash_len = floor(sqrt($length));
		while (strlen($password) > $dash_len) {
			$response_data .= substr($password, 0, $dash_len) . '-';
			$password = substr($password, $dash_len);
		}
	}
	$response_data .= $password;
	return $response_data;
}


function scramble($data)
{
	$response_data = '';
	$ciphering = "AES-128-CTR";
	// $iv_length = openssl_cipher_iv_length($ciphering);
	$options = 0;
	$encryption_iv = '1234567898765432';
	$encryption_key = 'trustworthyloyalhelpfulfriendlycourteouskindobedientthriftybravecleanreverend';
	$response_data = openssl_encrypt(
		$data,
		$ciphering,
		$encryption_key,
		$options,
		$encryption_iv
	);

	return $response_data;
}

function unscramble($data)
{
	$response_data = '';
	$ciphering = "AES-128-CTR";
	// $iv_length = openssl_cipher_iv_length($ciphering);
	$options = 0;
	$encryption_iv = '1234567898765432';
	$encryption_key = 'trustworthyloyalhelpfulfriendlycourteouskindobedientthriftybravecleanreverend';
	$response_data = openssl_decrypt(
		$data,
		$ciphering,
		$encryption_key,
		$options,
		$encryption_iv
	);

	return $response_data;
}


function searchPrep($var)
{
	$response_data = '';
	$response_data = str_replace(' ', '+', removePunctuation(cleanName(cleanData($var))));
	return $response_data;
}

function gmapAddress($address1, $address2, $city, $state, $zip)
{
	$full_address = '';
	$full_address = $address1;
	$full_address = (isset($address2) && strlen($address2) > 3) ? $full_address . '+' . $address2 : $full_address;
	$full_address = (isset($city) && strlen($city) > 3) ? $full_address . '+' . $city : $full_address;
	$full_address = (isset($state) && strlen($state) > 1) ? $full_address . '+' . $state : $full_address;
	$full_address = (isset($zip) && strlen($zip) > 1) ? $full_address . '+' . $zip : $full_address;
	$full_address = removePunctuation($full_address);
	$full_address = str_replace(' ', '+', $full_address);
	return $full_address;
}

function gmapLink($address1, $address2, $city, $state, $zip)
{
	$response_data = '';
	$clean_address = gmapAddress($address1, $address2, $city, $state, $zip);
	$gmap_url = "https://www.google.com/maps/search/?api=1&query=";
	$full_address_ready = rawurlencode($clean_address);
	$response_data = $gmap_url . $full_address_ready;
	return $response_data;
}


function geoAddress($address1, $address2, $city, $state, $zip)
{
	$response_data = '';
	$street = '';
	$street = $address1;
	$street = (isset($address2) && strlen($address2) > 3) ? $street . '+' . $address2 : $street;
	$street = removePunctuation($street);
	$street = str_replace(' ', '+', $street);
	$street = "street=" . $street;

	$city = removePunctuation($city);
	$city = str_replace(' ', '+', $city);
	$city = "city=" . $city;

	$state = removePunctuation($state);
	$state = str_replace(' ', '+', $state);
	$state = "state=" . $state;

	$zip = removePunctuation($zip);
	$zip = str_replace(' ', '+', $zip);
	$zip = "zip=" . $zip;

	$response_data = $street . '&' . $city . '&' . $state . '&' . $zip;

	return $response_data;
}

function geoLatLon($address)
{
	$response_data = 'nodata';
	// https://geocoding.geo.census.gov/geocoder/locations/searchtype?parameters&benchmark=Public_AR_ACS2021
	// ! / CURL
	$url = "https://geocoding.geo.census.gov/geocoder/locations/address?" . $address . "&benchmark=Public_AR_ACS2021&format=json";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, 'mike@lampkin.net');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
	$response = curl_exec($ch);
	curl_close($ch);
	// ! / CURL
	$response_array = json_decode($response, TRUE);

	if (count($response_array) >= 1) {
		// if( is_array($response_array['result']['addressMatches']) )
		if (count($response_array['result']['addressMatches']) > 0) {
			$coord_x = $response_array['result']['addressMatches'][0]['coordinates']['x'];
			$coord_y = $response_array['result']['addressMatches'][0]['coordinates']['y'];

			$coord_lon = number_format((float)$coord_x, 4, '.', '');
			$coord_lat = number_format((float)$coord_y, 4, '.', '');

			$response_data = $coord_lat . '|' . $coord_lon;
		}
	}
	return $response_data;
}

function browserName($user_agent)
{
	// Make case insensitive.
	$t = strtolower($user_agent);

	// If the string *starts* with the string, strpos returns 0 (i.e., FALSE). Do a ghetto hack and start with a space.
	// "[strpos()] may return Boolean FALSE, but may also return a non-Boolean value which evaluates to FALSE."
	//	http://php.net/manual/en/function.strpos.php
	$t = " " . $t;

	// Humans / Regular Users
	if (strpos($t, 'opera') || strpos($t, 'opr/')) return 'Opera';
	elseif (strpos($t, 'edge')) return 'Edge';
	elseif (strpos($t, 'chrome') && strpos($t, 'linux')) return 'Chrome Linux';
	elseif (strpos($t, 'chrome') && strpos($t, 'ipad')) return 'Chrome Ipad';
	elseif (strpos($t, 'chrome') && strpos($t, 'iphone')) return 'Chrome iPhone';
	elseif (strpos($t, 'chrome') && strpos($t, 'windows nt 10.0')) return 'Chrome Windows 10';
	elseif (strpos($t, 'chrome') && strpos($t, 'windows')) return 'Chrome Windows';
	elseif (strpos($t, 'chrome') && strpos($t, 'macintosh')) return 'Chrome Macintosh';

	elseif (strpos($t, 'chrome')) return 'Chrome';

	elseif (strpos($t, 'safari')) return 'Safari';
	elseif (strpos($t, 'firefox')) return 'Firefox';
	elseif (strpos($t, 'msie') || strpos($t, 'trident/7')) return 'Internet Explorer';

	// Search Engines
	elseif (strpos($t, 'google')) return '[Bot] Googlebot';
	elseif (strpos($t, 'bing')) return '[Bot] Bingbot';
	elseif (strpos($t, 'slurp')) return '[Bot] Yahoo! Slurp';
	elseif (strpos($t, 'duckduckgo')) return '[Bot] DuckDuckBot';
	elseif (strpos($t, 'baidu')) return '[Bot] Baidu';
	elseif (strpos($t, 'yandex')) return '[Bot] Yandex';
	elseif (strpos($t, 'sogou')) return '[Bot] Sogou';
	elseif (strpos($t, 'exabot')) return '[Bot] Exabot';
	elseif (strpos($t, 'msn')) return '[Bot] MSN';

	// Common Tools and Bots
	elseif (strpos($t, 'mj12bot')) return '[Bot] Majestic';
	elseif (strpos($t, 'ahrefs')) return '[Bot] Ahrefs';
	elseif (strpos($t, 'semrush')) return '[Bot] SEMRush';
	elseif (strpos($t, 'rogerbot') || strpos($t, 'dotbot')) return '[Bot] Moz or OpenSiteExplorer';
	elseif (strpos($t, 'frog') || strpos($t, 'screaming')) return '[Bot] Screaming Frog';
	elseif (strpos($t, 'blex')) return '[Bot] BLEXBot';

	// Miscellaneous
	elseif (strpos($t, 'facebook')) return '[Bot] Facebook';
	elseif (strpos($t, 'pinterest')) return '[Bot] Pinterest';

	// Check for strings commonly used in bot user agents
	elseif (
		strpos($t, 'crawler') || strpos($t, 'api') ||
		strpos($t, 'spider') || strpos($t, 'http') ||
		strpos($t, 'bot') || strpos($t, 'archive') ||
		strpos($t, 'info') || strpos($t, 'data')
	) return '[Bot] Other';

	return 'Other (Unknown)';
}


?>
