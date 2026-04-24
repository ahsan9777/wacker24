<?php


/*ini_set('session.save_path', '/var/cpanel/php/sessions/ea-php83');
//ini_set('session.gc_maxlifetime', 1440);
ini_set('session.cookie_lifetime', 0);

session_start();*/

//ini_set('display_errors', 1);
//error_reporting(E_ALL);
if ($_SERVER['HTTP_HOST'] == 'localhost:82') {
	$dbServer   = "localhost";
	$dbDatabase = "gac_2024_wacker_buerocenter";
	$dbUserName = "root";
	$dbPassword = "";
	$GLOBALS['siteName'] = "wacker24";
	$GLOBALS['siteURL'] = "http://localhost:82/wacker24/";
} elseif ($_SERVER['HTTP_HOST'] == 'localhost') {
	$dbServer   = "localhost";
	$dbDatabase = "gac_2024_wacker_buerocenter";
	$dbUserName = "root";
	$dbPassword = "";
	$GLOBALS['siteName'] = "Wacker Buerocenter";
	$GLOBALS['siteURL'] = "http://localhost/wacker24/";
	$GLOBALS['siteURL_main'] = "http://localhost/";
	$GLOBALS['vorkasse_email'] = "bestellung@wacker24.de";

} else {
	$dbServer   = "localhost";
	$dbDatabase = "wackerbuero_2026";
	$dbUserName = "wackerbuero_2026";

	$dbPassword = "T!1yNxP=gd!T";
	$GLOBALS['siteName'] = "Wacker Buerocenter";

	$GLOBALS['siteURL'] = "https://www.wacker-buerocenter.de/";
}

$GLOBALS['conn'] = new mysqli($dbServer, $dbUserName, $dbPassword, $dbDatabase);
/*$pdo = new PDO("mysql:host=$dbServer;dbname=$dbDatabase", $dbUserName, $dbPassword);
mysqli_set_charset($GLOBALS['conn'], 'utf8');*/
$pdo = new PDO(
    "mysql:host=$dbServer;dbname=$dbDatabase;charset=utf8mb4",
    $dbUserName,
    $dbPassword,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_general_ci"
    ]
);
mysqli_set_charset($GLOBALS['conn'], 'utf8mb4');

$Query = "SELECT * FROM site_config WHERE config_id=1";
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
		define('config_site_special_price', $row->config_site_special_price);
		define('config_site_quantity_source', $row->config_site_quantity_source);
		define('config_site_logo', $GLOBALS['siteURL'] . "files/" . $row->config_site_logo);
	}
}
//date_default_timezone_set("Asia/Karachi");
date_default_timezone_set("Europe/Berlin");
define('date_time', date('Y-m-d H:i:s'));
