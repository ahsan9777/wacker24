<?php
ob_start();
include("../lib/openCon.php");
include("../lib/functions_mail.php");
session_start();
//print(md5("admin"));
//DIE();
$strMSG = "";
$strMSG1 = "";
if (isset($_POST['btnLogin'])) {

    if (!empty($_POST)) {


        $usernameError = null;
        $passwordError = null;
        $password = md5($_POST['user_password']);
        //$password=$_POST['mem_password'];
        $username = $_POST['user_name'];
        $valid = true;
        if (empty($username)) {
            $usernameError = 'Please enter user Name';
            $valid = false;
        }
        if ($valid) {
            $rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM users WHERE user_password='$password' AND user_name='$username' AND utype_id IN (2,3)") or die(mysqli_error($GLOBALS['conn']));
            if (mysqli_num_rows($rs) > 0) {
                $row = mysqli_fetch_object($rs);
                if ($row->utype_id == 1) {
                    $_SESSION["isAdmin"] = 1;
                } else {
                    $_SESSION["isAdmin"] = 0;
                }
                $_SESSION["UserID"] = $row->user_id;
                $_SESSION["UserName"] = $row->user_name;
                $_SESSION["UType"] = $row->utype_id;
                header("location:index.php");
            } else {
                $strMSG = '<div class="alert alert-danger" style="width:100%; ">Invalid Login / Password</div>';
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="de">

<head>
    <?php include("includes/html_header.php"); ?>
</head>

<body>
    <div class="container">

        <form class="login_form" role="form" method="post" action="<?php print($_SERVER['PHP_SELF']);?>">
            <div class="inner_form">
                <div class="logo"><img src="assets/images/logo.png" alt=""></div>
            </div>
            <div class="inner_form_area">
                <?php print($strMSG); ?>
                <h2 class="text_align_center">Admin Login Area</h2>
                <div class="login_input">
                    <input class="input_style" type="text" name="user_name" id="user_name">
                    <input class="input_style" type="password" name="user_password" id="user_password">
                </div>
                <div class="text_align_center">
                    <input type="submit" name="btnLogin" class="btn btn-success" value="Get Access">
                </div>
            </div>
        </form>
    </div>
    <script src="./assets/js/jquery.js"></script>
    <script src="./assets/js/main.js"></script>
</body>

</html>