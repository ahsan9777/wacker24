<?php
ob_start();
session_start();
include("../lib/openCon.php");
include("../lib/functions.php");

require_once("../lib/class.pager1.php"); 
$p = new Pager1;
setcookie("PHPSESSID", "", time() - 3600, "/");
session_destroy();
if(!isset($_SESSION['UserID'])) {
    //header("location:index.php");

}

$class = ""; 
$strMSG = ""; 
$qryStrURL = ""; 
ob_end_flush();
?>
