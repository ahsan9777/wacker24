<?php
include("../lib/openCon.php");
include("../lib/functions.php");
include("../lib/functions_mail.php");

require_once("../lib/class.pager1.php"); 
$p = new Pager1;

session_start();
if(!isset($_SESSION['UserID'])) {
	header("location:index.php");
	
}
$class = ""; 
$strMSG = "";
$FormHead = "";
$qryStrURL = "";
?>