<?php
//$sessTime = 24*60*60;
//ini_set('session.gc_maxlifetime', $sessTime);
//ini_set('session.gc_probability', 1);
//ini_set('session.gc_divisor', 1);
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
if($_SERVER['HTTP_HOST']=='localhost:82'){
	$dbServer   = "localhost";
	$dbDatabase = "gac_2024_wacker24";
	$dbUserName = "root";
	$dbPassword = "";
    $GLOBALS['siteURL'] = "http://localhost:82/wacker24/";
}
elseif($_SERVER['HTTP_HOST']=='localhost'){
	$dbServer   = "localhost";
	$dbDatabase = "gac_2024_wacker24";
	$dbUserName = "root";
	$dbPassword = "";
    $GLOBALS['siteURL'] = "http://localhost/wacker24/";
}
else{
    $dbServer   = "localhost";
    $dbDatabase = "wackersystems_wacker24demo";
	$dbUserName = "wackersystems_wacker24demo";
    $dbPassword = "MU8s)gKIeQyD";
    $GLOBALS['siteURL'] = "https://wackersystems.com/wacker24-version2024/";
}
$GLOBALS['conn'] = new mysqli($dbServer, $dbUserName, $dbPassword, $dbDatabase);
mysqli_set_charset($GLOBALS['conn'], 'utf8');

$Query = "SELECT * FROM site_config";
$rs = mysqli_query($GLOBALS['conn'], $Query);
if(mysqli_num_rows($rs) > 0){
	while ($row = mysqli_fetch_object($rs)) {
		define('config_sitetitle', $row->config_sitetitle);
		define('config_metakey', $row->config_metakey);
		define('config_metades', $row->config_metades);
		define('config_gst', $row->config_gst);
		define('config_payment_url', $row->config_payment_url);
		define('config_authorization_bearer', $row->config_authorization_bearer);
		define('config_condition_courier_amount', $row->config_condition_courier_amount);
		define('config_courier_fix_charges', $row->config_courier_fix_charges);
		define('config_ftp_img', $row->config_ftp_img);
	}
}

date_default_timezone_set("Asia/Karachi");
define('date_time', date('Y-m-d H:i:s'));
?>
