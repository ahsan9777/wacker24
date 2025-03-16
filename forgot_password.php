<?php
include("includes/php_includes_top.php");


if (isset($_REQUEST['btn_forgotpassword'])) {

	$Query = "SELECT * FROM users WHERE user_name = '" . $_REQUEST['user_name'] . "' "; // $2y$10$vTJ8Y1ulWNW8upkatUlxQuh.cF6LzHx6GK820Ngxo/sCdfOw4JO9W
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) > 0) {
		$row = mysqli_fetch_object($rs);
		//$user_verification_code = md5($row->user_id . date("Ymdhis"));
		$user_password = create_password(8);
		mysqli_query($GLOBALS['conn'], "UPDATE users SET user_password = '" . dbStr(password_hash(trim($user_password), PASSWORD_BCRYPT)) . "' WHERE user_id = '" . $row->user_id . "'") or die(mysqli_error($GLOBALS['conn']));
		$mailer->forgotPass($row->user_fname . " " . $row->user_lname, $row->user_name, $user_password);
		header('konto-vergessen-kennwort');
	} else {
		$class = "alert alert-danger";
		$strMSG = "Dear Cuctomer, <br>
				Invalid email address try again";
	}
}

?>
<!doctype html>
<html lang="de">

<head>
	<meta charset="utf-8">
	<link rel="icon" type="image/x-icon" href="images/favicon.ico">
	<meta name="viewport" content="width=device-width; initial-scale=1.0">
	<title>Passwort vergessen</title>
	<link href="css/styles.css" rel="stylesheet" type="text/css" />
	<link href="css/responsive.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="<?php print(get_font_link(config_fonts)); ?>" />
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
						<h2>Passwort vergessen</h2>
						<?php if ($class != "") { ?>
							<div class="<?php print($class); ?>"><?php print($strMSG); ?><a href="javascript:void(0);" class="close" data-dismiss="alert">×</a></div>
						<?php } ?>
						<form class="gerenric_form" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
							<ul>
								<li>
									<div class="form_label">E-mail address</div>
									<div class="form_field"><input type="text" class="gerenric_input" name="user_name" id="user_name"></div>
								</li>
								<li><button type="submit" name="btn_forgotpassword" class="gerenric_btn full_btn">Passwort vergessen</button></li>
							</ul>
						</form>
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
<script>
	$('.close').on('click', function() {
		$('.alert').hide();
	});
</script>

</html>