<?php
ob_start();
session_start();
if (isset($_SESSION["cart_id"]) && $_SESSION["cart_id"] > 0) {
    $_SESSION["cart_check"] = true;
} else {
    /*session_unset();
    session_destroy();*/
    unset($_SESSION['cart_id']);
    unset($_SESSION['sess_id']);
    unset($_SESSION['ci_id']);
    unset($_SESSION['header_quantity']);
}
unset($_SESSION['UID']);
unset($_SESSION['UName']);
unset($_SESSION['FirstName']);
unset($_SESSION['FullName']);
unset($_SESSION['Utype']);
header("Location: index.php");
ob_end_flush();
?>
