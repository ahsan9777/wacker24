<?php
ob_start();
session_start();
if (isset($_SESSION["cart_id"]) && $_SESSION["cart_id"] > 0) {
    unset($_SESSION['UID']);
    unset($_SESSION['UName']);
    unset($_SESSION['FirstName']);
    unset($_SESSION['FullName']);
    unset($_SESSION['Utype']);
} else {
    session_unset();
    session_destroy();
}
header("Location: index.php");
ob_end_flush();
