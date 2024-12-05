<?php
	$dbDatabase = "excellpp_db";
	$dbServer = "localhost";
	$dbUserName = "excellpp_admin";
	$dbPassword = "C!?ew7=XH*Mu";
	$conn = mysql_connect("$dbServer","$dbUserName","$dbPassword") or die("<h1>Unable 2 Connect 2 Database Server</h1>"); 
	$db = mysql_select_db("$dbDatabase")  or die("Unable 2 Connect 2 Database"); 
?>
