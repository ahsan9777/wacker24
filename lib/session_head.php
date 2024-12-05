<?php
include("../lib/openCon.php");
include("../lib/functions.php");
include("../lib/functions_mail.php");

require_once("../lib/class.pager1.php"); 
$p = new Pager1;

session_start();
if(!isset($_SESSION['UserID'])) {
	if($_SERVER['HTTP_HOST']=='localhost:82' || $_SERVER['HTTP_HOST']=='localhost' ){
		header("location:login.php");
	} else{
		header("location: https://paywizelimited.co.uk/admin/login.php");
	}
}

$strMSG = "";
$FormHead = "";
?>