<?php
ob_start();

include("lib/openCon.php");
include("lib/functions.php");
require_once("lib/class.pager1.php"); 
$p = new Pager1;
//require_once($dPth."lib/mailer.php");
//$mailer = new Mailer();

session_start();
//$_SESSION['utype_id'] = 3;

$page = 0;

$class = ""; 
$strMSG = ""; 
$qryStrURL = ""; 
$search_keyword = "";
$cat_id = "";
$special_price = array();
?>