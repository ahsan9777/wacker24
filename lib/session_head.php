<?php
ob_start();
session_start();
include("../lib/openCon.php");
include("../lib/functions.php");

require_once("../lib/class.pager1.php"); 
$p = new Pager1;


if(!isset($_SESSION['UserID'])) {
    header("Location: login.php");

}

$class = ""; 
$strMSG = ""; 
$qryStrURL = ""; 
ob_end_flush();
?>
