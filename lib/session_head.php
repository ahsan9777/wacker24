<?php
ob_start();
session_start();
include("../lib/openCon.php");
include("../lib/functions.php");

require_once("../lib/class.pager_admin.php"); 
$p = new Pager1;
require_once("../lib/mailer.php");
$mailer = new Mailer();


if(!isset($_SESSION['UserID'])) {
    header("Location: login.php");

}

$formHead = ""; 
$class = ""; 
$strMSG = ""; 
$qryStrURL = ""; 
?>
