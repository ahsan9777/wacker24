<?php
$QryConfig = "SELECT * FROM site_config WHERE config_id = 1";
$RsConfig = mysqli_query($GLOBALS['conn'], $QryConfig) or die(mysqli_error($GLOBALS['conn']));
if (mysqli_num_rows($RsConfig)>=1){
	$rowConfig=mysqli_fetch_object($RsConfig);
	define("SITE_NAME", $rowConfig->config_sitename);
	define("SITE_TITLE", $rowConfig->config_sitetitle);
	define("SITE_META_KEYWORDS", $rowConfig->config_metakey);
	define("SITE_META_DESCRIPTION", $rowConfig->config_metades);
	define("SITE_PHONE", $rowConfig->config_phone);
	define("SITE_MOBILE", $rowConfig->config_mobile);
	define("SITE_FAX", $rowConfig->config_fax);
	define("SITE_EMAIL", $rowConfig->config_email);
}
else{
	define("SITE_NAME", "Excellent Printing Press");
	define("SITE_TITLE", "Excellent Printing Press");
	define("SITE_META_KEYWORDS", "Excellent Printing Press");
	define("SITE_META_DESCRIPTION", "Excellent Printing Press");
	define("SITE_PHONE", "");
	define("SITE_MOBILE", "");
	define("SITE_FAX", "");
	define("SITE_EMAIL", "");
}
if($rowConfig->status_id == 0){
	include("not_available.php");
	die();
}
$_SESSION['sessID'] = session_id();
?>