<?php
include("includes/php_includes_top.php");
if(!isset($_SESSION["UID"])) {
    header("Location:".$GLOBALS['siteURL']);
}
?>