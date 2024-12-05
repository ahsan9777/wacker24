<?php
	$dbDatabase = "blaurer2_realestate";
	$dbServer = "localhost";
	$dbUserName = "blaurer2_admin";
	$dbPassword = "P@ssw0rd";
	$conn = mysql_connect("$dbServer","$dbUserName","$dbPassword") or die("Unable 2 Connect 2 Database Server"); 
	$db = mysql_select_db("$dbDatabase")  or die("Unable 2 Connect 2 Database"); 
?>
