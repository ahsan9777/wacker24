<?php
ob_start();
include("../lib/openCon.php");
include("../lib/functions.php");
session_start();
//print(md5("admin"));
//DIE();
$strMSG = "";
if (isset($_POST['btnLogin'])) {

    if (!empty($_POST)) {


        $usernameError = null;
        $passwordError = null;
        $password = trim($_POST['user_password']);
        //$password=$_POST['mem_password'];
        $username = dbStr(trim($_POST['user_name']));
        $valid = true;
        if (empty($username)) {
            $usernameError = 'Please enter user Name';
            $valid = false;
        }
        if ($valid) {
            $rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM users WHERE user_name='$username' AND utype_id IN (1,2)") or die(mysqli_error($GLOBALS['conn']));
            if (mysqli_num_rows($rs) > 0) {
                $row = mysqli_fetch_object($rs);
                if (password_verify($password, $row->user_password)) {
                    if ($row->utype_id == 1) {
                        $_SESSION["isAdmin"] = 1;
                    } else {
                        $_SESSION["isAdmin"] = 0;
                    }
                    $_SESSION["UserID"] = $row->user_id;
                    $_SESSION["UserName"] = $row->user_name;
                    $_SESSION["UserType"] = $row->utype_id;
                    header("location:index.php");
                } else {
                    $strMSG = '<div class="alert alert-danger" style="width:100%; ">Invalid Login / Password <a class="close" data-dismiss="alert">×</a></div>';
                }
            } else {
                $strMSG = '<div class="alert alert-danger" style="width:100%; ">Invalid Login / Password <a class="close" data-dismiss="alert">×</a></div>';
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="./assets/images/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wacker 24 Backend Control Panel</title>
    <link rel="stylesheet" href="./assets/style/styles.css">
    <link rel="stylesheet" href="./assets/style/scrollbar.css">
    <link rel="stylesheet" href="./assets/style/responsive.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>

    <div class="login-container">
        <div class="">
            <img src="./assets/images/logo.png" class="logo" style="padding: 15px 100px;"></img>
            <div class="login-box">
                <h2>Admin Login Area</h2>
                <?php print($strMSG); ?>
                <form id="loginForm" role="form" method="post" action="<?php print($_SERVER['PHP_SELF']); ?>">
                    <input class="input_style" type="text" name="user_name" id="user_name" required>
                    <input class="input_style" type="password" name="user_password" id="user_password" required>
                    <button type="submit" name="btnLogin">Get Access</button>
                </form>
            </div>
        </div>
    </div>
    <script src="./assets/js/jquery.js"></script>
    <script src="./assets/js/main.js"></script>
    <script>
        $('.close').on('click', function() {
            $('.alert').hide();
        });
    </script>
</body>

</html>