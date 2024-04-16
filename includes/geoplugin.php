<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/geoplugin.class.php';

	$geoplugin = new geoPlugin();
	$date_of_expiry =   time() + (10 * 365 * 24 * 60 * 60) ;


$geoplugin->locate();

$your_ipaddress		= $geoplugin->ip;

$your_lat		= $geoplugin->latitude;
$your_lon		= $geoplugin->longitude;
$your_city 		= $geoplugin->city;
$your_region	= $geoplugin->region;
$your_regionCode = $geoplugin->regionCode;
$your_regionName = $geoplugin->regionName;
$your_dmaCode		= $geoplugin->dmaCode;
$your_countryName 	= $geoplugin->countryName;
$your_countryCode 	= $geoplugin->countryCode;
$your_radius	= $geoplugin->locationAccuracyRadius;
$your_year		= date('Y');
$your_month		= date('m');
$your_day		= date('d');
$your_date		= date("Y-m-d");
$your_time		= date("h:i:s");

	$your_lon_max = $your_lon + .69;
	$your_lon_min = $your_lon - .69;

	$your_lat_max = $your_lat + .5;
	$your_lat_min = $your_lat - .5;


	$request_geodata = $your_lat . '|' . $your_lon . '|' . $your_city . '|' . $your_regionCode . '|' . $your_regionName . '|' . $your_dmaCode . '|' . $your_countryName . '|' . $your_countryCode;
?>
