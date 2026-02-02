<?php
ob_start();
//session_save_path('/tmp');
session_start();
	/*session_start();
	session_unset();
	session_destroy();*/
	unset($_SESSION['isAdmin']);
    unset($_SESSION['UserID']);
    unset($_SESSION['UserName']);
    unset($_SESSION['UserType']);
	header("Location: login.php");
ob_end_flush();
?>