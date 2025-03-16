<?php

//$sessTime = 24*60*60;
//ini_set('session.gc_maxlifetime', $sessTime);
//ini_set('session.gc_probability', 1);
//ini_set('session.gc_divisor', 1);
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
if ($_SERVER['HTTP_HOST'] == 'localhost:82') {
	$dbServer   = "localhost";
	$dbDatabase = "gac_2024_wacker24";
	$dbUserName = "root";
	$dbPassword = "";
	$GLOBALS['siteName'] = "wacker24";
	$GLOBALS['siteURL'] = "http://localhost:82/wacker24/";
} elseif ($_SERVER['HTTP_HOST'] == 'localhost') {
	$dbServer   = "localhost";
	$dbDatabase = "gac_2024_wacker24";
	$dbUserName = "root";
	$dbPassword = "";
	$GLOBALS['siteName'] = "wacker24";
	$GLOBALS['siteURL'] = "http://localhost/wacker24/";
} else {
	$dbServer   = "localhost";
	$dbDatabase = "wackersystems_wacker24demo";
	$dbUserName = "wackersystems_wacker24demo";
	/*$dbDatabase = "esoltech_wacker24";
	$dbUserName = "esoltech_wacker24";*/
	$dbPassword = "MU8s)gKIeQyD";
	$GLOBALS['siteName'] = "www.wackersystems.com";
	//$GLOBALS['siteURL'] = "https://esol-tech.com/wacker24/";
	$GLOBALS['siteURL'] = "https://wackersystems.com/";
}

$GLOBALS['conn'] = new mysqli($dbServer, $dbUserName, $dbPassword, $dbDatabase);
mysqli_set_charset($GLOBALS['conn'], 'utf8');

$Query = "SELECT * FROM site_config";
$rs = mysqli_query($GLOBALS['conn'], $Query);
if (mysqli_num_rows($rs) > 0) {
	while ($row = mysqli_fetch_object($rs)) {
		define('config_sitename', $row->config_sitename);
		define('config_sitetitle', $row->config_sitetitle);
		define('config_metakey', $row->config_metakey);
		define('config_metades', $row->config_metades);
		define('config_email', $row->config_email);
		define('config_phone', $row->config_phone);
		define('config_gst', ($row->config_gst / 100));
		define('config_payment_url', $row->config_payment_url);
		define('config_authorization_bearer', $row->config_authorization_bearer);
		define('config_condition_courier_amount', $row->config_condition_courier_amount);
		define('config_courier_fix_charges', $row->config_courier_fix_charges);
		define('config_ftp_img', $row->config_ftp_img);
		define('config_appointment_regular_opening', $row->config_appointment_regular_opening);
		define('config_appointment_regular_closing', $row->config_appointment_regular_closing);
		define('config_appointment_saturday_opening', $row->config_appointment_saturday_opening);
		define('config_appointment_saturday_closing', $row->config_appointment_saturday_closing);
		define('config_appointment_heading_de', $row->config_appointment_heading_de);
		define('config_appointment_heading_en', $row->config_appointment_heading_en);
		define('config_appointment_detail_de', $row->config_appointment_detail_de);
		define('config_appointment_detail_en', $row->config_appointment_detail_en);
		define('config_private_color_a', $row->config_private_color_a);
		define('config_private_color_b', $row->config_private_color_b);
		define('config_company_color_a', $row->config_company_color_a);
		define('config_company_color_b', $row->config_company_color_b);
		define('config_btn_color', $row->config_btn_color);
		define('config_fonts', $row->config_fonts);
		define('config_site_logo', $GLOBALS['siteURL'] . "files/" . $row->config_site_logo);
	}
}
//date_default_timezone_set("Asia/Karachi");
date_default_timezone_set("Europe/Berlin");
define('date_time', date('Y-m-d H:i:s'));
