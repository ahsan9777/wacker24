<?php
include("includes/php_includes_top.php");

$ref = "";
$ref_check = array();
if (isset($_REQUEST['referer'])) {
	$ref = $_REQUEST['referer'];
} else {
	if (isset($_SERVER['HTTP_REFERER'])) {
		$ref = $_SERVER['HTTP_REFERER'];
	}
}
$ref_check = explode("?", $ref);
//echo $ref_check[0];
if (isset($_REQUEST['btn_login'])) {

	$usernameError = null;
	$passwordError = null;
	$password = trim($_REQUEST['user_password']);
	//echo password_hash($password, PASSWORD_BCRYPT);
	$username = dbStr(trim($_REQUEST['user_name']));
	$valid = true;
	if (empty($username)) {
		$valid = false;
		$class = "alert alert-danger";
		$strMSG = "Dear Cuctomer, <br>
					Please enter user Name";
	}
	if ($valid) {
		//echo "SELECT * FROM users WHERE utype_id IN (3,4) AND user_name='" . $username . "' AND status_id = '1'";die();
		//$rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM users WHERE utype_id = '" . $utype_id . "' AND user_password='" . $password . "' AND user_name='" . $username . "' AND status_id = '1' AND gen_id = '".$gen_id."'") or die(mysqli_error($GLOBALS['conn']));
		$rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM users WHERE utype_id IN (3,4) AND user_name='" . $username . "' AND status_id = '1'") or die(mysqli_error($GLOBALS['conn']));
		if (mysqli_num_rows($rs) > 0) {
			$row = mysqli_fetch_object($rs);
			if (password_verify($password, $row->user_password)) {
				$_SESSION["UID"] = $row->user_id;
				$_SESSION["UName"] = $row->user_name;
				$_SESSION["FirstName"] = $row->user_fname;
				$_SESSION["FullName"] = $row->user_fname . " " . $row->user_lname;
				$_SESSION["Utype"] = $row->utype_id;
				$_SESSION["utype_id"] = $row->utype_id;

				//echo $ref_check[0];die();
				if ($ref == $GLOBALS['siteURL'] . "login.php" || $ref == $GLOBALS['siteURL'] . "register.php" || $ref_check[0] == $GLOBALS['siteURL'] . "account_verification.php"  || $ref_check[0] == $GLOBALS['siteURL'] . "account_registration.php" ) {
					header("Location:" . $GLOBALS['siteURL'] . "index.php");
				} elseif (!empty($ref)) {
					header("Location:" . $ref);
				} else {
					header("location:" . $GLOBALS['siteURL'] . "index.php");
				}
			} else {

				$class = "alert alert-danger";
				$strMSG = "Dear Cuctomer, <br>
						Invalid login credential try again";
			}

			//header("location:".$GLOBALS['siteURL']."my_account.php");
		} else {

			$status_id = returnName("status_id", "users", "user_name", $username);
			if ($status_id == 0) {
				$rs1 = mysqli_query($GLOBALS['conn'], "SELECT * FROM users WHERE utype_id IN (3,4) AND user_name='" . $username . "' AND status_id = '0'") or die(mysqli_error($GLOBALS['conn']));
				if (mysqli_num_rows($rs1) > 0) {
					$rw = mysqli_fetch_object($rs1);
					$user_verification_code = md5($rw->user_id . date("Ymdhis"));
					mysqli_query($GLOBALS['conn'], "UPDATE users SET user_confirmation = '" . $user_verification_code . "' WHERE user_id = '" . $rw->user_id . "'") or die(mysqli_error($GLOBALS['conn']));
					$mailer->registration_account_verification($rw->user_fname . " " . $rw->user_lname, "verification@wackersystems.com", "7v6LjC{rEIct", $rw->user_name, "Account Verification", $user_verification_code);
					header('Location: account_registration.php');
				}
			} else {
				$class = "alert alert-danger";
				$strMSG = "Dear Cuctomer, <br>
						Invalid login credential try again";
			}
		}
	}
}

?>
<!doctype html>
<html lang="de">

<head>
	<meta charset="utf-8">
	<link rel="icon" type="image/x-icon" href="images/favicon.ico">
	<meta name="viewport" content="width=device-width; initial-scale=1.0">
	<title>Wacker 24</title>
	<link href="css/styles.css" rel="stylesheet" type="text/css" />
	<link href="css/responsive.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="<?php print(get_font_link(config_fonts));?>" />
	<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
	<script src="js/jquery-2.2.0.min.js"></script>
	<?php include("includes/btn_color.php"); ?>
</head>

<body class="body-white">
	<div id="container" align="center">

		<!--LOGIN_PAGE_START-->
		<section class="login_page">
			<div class="page_width">
				<div class="login_inner">
					<div class="login_logo"><a href="<?php print($GLOBALS['siteURL']); ?>"><img src="images/register_logo.png" alt=""></a></div>
					<div class="login_box">
						<h2>Anmelden</h2>
						<?php if ($class != "") { ?>
							<div class="<?php print($class); ?>"><?php print($strMSG); ?><a href="javascript:void(0);" class="close" data-dismiss="alert">×</a></div>
						<?php } ?>
						<form class="gerenric_form" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
							<ul>
								<li>
									<input type="hidden" name="referer" value="<?php print($ref); ?>">
									<div class="form_label">Email-Adresse</div>
									<div class="form_field"><input type="text" class="gerenric_input" name="user_name" id="user_name"></div>
								</li>
								<li>
									<div class="form_label">Passwort</div>
									<div class="form_field"><input type="password" class="gerenric_input" name="user_password" id="user_password"></div>
								</li>
								<li><button type="submit" name="btn_login" class="gerenric_btn full_btn">Einloggen</button></li>
								<li>
									<div class="forgot_password"><a href="forgot_password.php">Passwort vergessen?</a></div>
								</li>
								<li>
									<div class="form_term">Mit Ihrer Anmeldung stimmen Sie unseren <a href="term">Allgemeinen Geschäftsbedingungen</a> zu.Bitte lesen Sie unsere <a href="privacy">Datenschutzerklärung </a>, unser <a href="javascript:void(0)"> Hinweis auf Cookies und unser Hinweis auf interessenbezogener Werbung.</a></div>
								</li>
							</ul>
						</form>
					</div>
					<div class="or_div">
						<div class="or_div_inner">Neu bei Wacker24?</div>
					</div>
					<div class="new_account_btn"><a href="registration.php">
							<div class="gerenric_btn">Erstellen Sie hier Ihr Wacker24 Konto</div>
						</a>
					</div>
				</div>
			</div>
		</section>
		<!--LOGIN_PAGE_END-->

		<!--FOOTER_SECTION_START-->
		<div id="footer_register">
			<div class="page_width">
				<ul>
					<li><a href="javascript:void(0)">Cookie-Einstellungen </a></li>
					<li><a href="impressum">Impressum</a></li>
					<li><a href="privacy">Datenschutzerklärung</a></li>
					<li><a href="term">Allgemeinen Geschäftsbedingungen</a></li>
					<li><a href="contact_us">Kontakt</a></li>
				</ul>
			</div>
		</div>
		<!--FOOTER_SECTION_END-->

	</div>

</body>

</html>