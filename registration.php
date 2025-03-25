<?php
$cnt_id = 0;
include("includes/php_includes_top.php");


$strMSG = "";
$class = "";
$lotti_player_popup = "";

$utype_id = 3;
$user_company_name = "";
$user_tax_no = "";
$user_fname = "";
$user_lname = "";
$gen_id = 1;
$user_phone = "";
$user_name = "";
$user_password = "";
$user_confirm_password = "";
$countries_id = 81;
$field = "";
$value = "";
$input_display = 'style="display: none;"';

if (isset($_REQUEST['btn_registration'])) {
	//print_r($_REQUEST);die();
	if (isset($_REQUEST['user_company_name']) && !empty($_REQUEST['user_company_name'])) {
		$user_company_name = $_REQUEST['user_company_name'];
		$field .= ", user_company_name";
		$value .= ", '" . dbStr(trim($_REQUEST['user_company_name'])) . "'";
		$input_display = "";
	}
	if (isset($_REQUEST['user_tax_no']) && !empty($_REQUEST['user_tax_no'])) {
		$user_tax_no = $_REQUEST['user_tax_no'];
		$field .= ", user_tax_no";
		$value .= ", '" . dbStr(trim($_REQUEST['user_tax_no'])) . "'";
		$input_display = "";
	}
	//$Query = "SELECT * FROM `users` WHERE user_name ='" . dbStr(trim($_REQUEST['user_name'])) . "' AND utype_id ='" . dbStr(trim($_REQUEST['utype_id'])) . "'";
	$Query = "SELECT * FROM `users` WHERE user_name ='" . dbStr(trim($_REQUEST['user_name'])) . "'";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) > 0) {
		$utype_id = $_REQUEST['utype_id'];
		$user_fname = $_REQUEST['user_fname'];
		$user_lname = $_REQUEST['user_lname'];
		$gen_id = $_REQUEST['gen_id'];
		$user_phone = $_REQUEST['user_phone'];
		$user_name = $_REQUEST['user_name'];
		$user_password = $_REQUEST['user_password'];
		$user_confirm_password = $_REQUEST['user_confirm_password'];
		$countries_id = $_REQUEST['countries_id'];
		$class = "alert alert-danger";
		$strMSG = "Sehr geehrter Kunde, <br>Dieses Konto existiert bereits unter dem Benutzernamen!";
	} else {
		if ($_REQUEST['user_password'] != $_REQUEST['user_confirm_password']) {
			$utype_id = $_REQUEST['utype_id'];
			$user_fname = $_REQUEST['user_fname'];
			$user_lname = $_REQUEST['user_lname'];
			$gen_id = $_REQUEST['gen_id'];
			$user_phone = $_REQUEST['user_phone'];
			$user_name = $_REQUEST['user_name'];
			$user_password = $_REQUEST['user_password'];
			$user_confirm_password = $_REQUEST['user_confirm_password'];
			$countries_id = $_REQUEST['countries_id'];

			$class = "alert alert-danger";
			$strMSG = "Sehr geehrter Kunde, <br>Die Kennwörter stimmen nicht überein!";
		} else {

			if ($_REQUEST['confirm_code'] != $_REQUEST['reconfirm_code']) {

				$utype_id = $_REQUEST['utype_id'];
				$user_fname = $_REQUEST['user_fname'];
				$user_lname = $_REQUEST['user_lname'];
				$gen_id = $_REQUEST['gen_id'];
				$user_phone = $_REQUEST['user_phone'];
				$user_name = $_REQUEST['user_name'];
				$user_password = $_REQUEST['user_password'];
				$user_confirm_password = $_REQUEST['user_confirm_password'];
				$countries_id = $_REQUEST['countries_id'];

				$class = "alert alert-danger";
				$strMSG = "Sehr geehrter Kunde, <br> Der Bestätigungscode passt nicht!";
			} else {
				$user_id = getMaximum("users", "user_id");
				$user_verification_code = md5($user_id . date("Ymdhis"));
				mysqli_query($GLOBALS['conn'], "INSERT INTO users (user_id, utype_id, user_fname, user_lname, gen_id, user_phone, user_name, user_password, countries_id, user_confirmation " . $field . ") VALUES ('" . $user_id . "', '" . dbStr(trim($_REQUEST['utype_id'])) . "', '" . dbStr(trim($_REQUEST['user_fname'])) . "','" . dbStr(trim($_REQUEST['user_lname'])) . "','" . $_REQUEST['gen_id'] . "','" . dbStr(trim($_REQUEST['user_phone'])) . "','" . dbStr(trim($_REQUEST['user_name'])) . "','" . dbStr(password_hash(trim($_REQUEST['user_password']), PASSWORD_BCRYPT)) . "','" . dbStr(trim($_REQUEST['countries_id'])) . "', '" . $user_verification_code . "' " . $value . ")") or die(mysqli_error($GLOBALS['conn']));
				$utype_id = 3;
				$user_company_name = "";
				$user_tax_no = "";
				$field = "";
				$value = "";
				$input_display = 'style="display: none;"';
				$mailer->registration_account_verification(dbStr(trim($_REQUEST['user_fname']))." ".dbStr(trim($_REQUEST['user_lname'])), "verification@wackersystems.com", "7v6LjC{rEIct", $_REQUEST['user_name'], "Account Verification", $user_verification_code);
				header('Location: konto-registrierung');
			}
		}
	}
}

include("includes/message.php");
?>
<!doctype html>
<html lang="de">

<head>
	<meta charset="utf-8">
	<link rel="icon" type="image/x-icon" href="images/favicon.ico">
	<meta name="viewport" content="width=device-width; initial-scale=1.0">
	<base href="<?php print($GLOBALS['siteURL']); ?>">
	<title>Registrierung</title>
	<link href="css/styles.css" rel="stylesheet" type="text/css" />
	<link href="css/responsive.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="<?php print(get_font_link(config_fonts));?>" />
	<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
	<script src="js/jquery-2.2.0.min.js"></script>
	<?php include("includes/btn_color.php"); ?>
</head>

<body class="body-white">
	<div id="container" align="center">

		<!--REGISTER_PAGE_START-->
		<section class="login_page register_page">
			<div class="page_width">
				<div class="login_inner">
					<div class="login_logo"><a href="<?php print($GLOBALS['siteURL']); ?>"><img src="images/register_logo.png" alt=""></a></div>
					<form class="login_box" name="frm" id="frm" method="post" action="registrierung" role="form" enctype="multipart/form-data">
						<h2>Registrierung</h2>
						<?php if ($class != "") { ?>
							<div class="<?php print($class); ?>"><?php print($strMSG); ?><a href="javascript:void(0);" class="close" data-dismiss="alert">×</a></div>
						<?php } ?>
						<div class="gerenric_form">
							<ul>
								<li>
									<div class="tab_radio_button">
										<div class="tab_radio_col">
											<input type="radio" class="utype_id" id="private_customer" name="utype_id" value="3" <?php print(($utype_id == 3) ? "checked" : ""); ?>>
											<label for="private_customer">Privatkunde</label>
										</div>
										<div class="tab_radio_col">
											<input type="radio" class="utype_id" id="business_customer" name="utype_id" value="4" <?php print(($utype_id == 4) ? "checked" : ""); ?>>
											<label for="business_customer">Geschäftskunde</label>
										</div>
									</div>
								</li>
								<li id="user_company_input" <?php print($input_display); ?>>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">Firma *</div>
											<div class="form_field"><input type="text" name="user_company_name" id="user_company_name" value="<?php print($user_company_name); ?>" class="gerenric_input"></div>
										</div>
										<div class="form_right">
											<div class="form_label">USt-IdNr *</div>
											<div class="form_field"><input type="text" name="user_tax_no" id="user_tax_no" value="<?php print($user_tax_no); ?>" class="gerenric_input"></div>
										</div>
									</div>
								</li>
								<li>
									<div class="form_label">Anrede</div>
									<div class="form_field">
										<select class="gerenric_input" name="gen_id" id="gen_id">
											<option value="1" <?php print(($gen_id == 1) ? 'checked' : ''); ?>>Herr</option>
											<option value="2" <?php print(($gen_id == 2) ? 'checked' : ''); ?>>Frau</option>
											<option value="3" <?php print(($gen_id == 3) ? 'checked' : ''); ?>>Keine</option>
										</select>
									</div>
								</li>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">Vorname *</div>
											<div class="form_field"><input type="text" name="user_fname" id="user_fname" value="<?php print($user_fname); ?>" class="gerenric_input" required></div>
										</div>
										<div class="form_right">
											<div class="form_label">Nachname *</div>
											<div class="form_field"><input type="text" name="user_lname" id="user_lname" value="<?php print($user_lname); ?>" class="gerenric_input" required></div>
										</div>
									</div>
								</li>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">Passwort *</div>
											<div class="form_field"><input type="password" name="user_password" id="user_password" value="<?php print($user_password); ?>" class="gerenric_input" required></div>
										</div>
										<div class="form_right">
											<div class="form_label">Wiederholen Sie Ihr Passwort *</div>
											<div class="form_field"><input type="password" name="user_confirm_password" id="user_confirm_password" value="<?php print($user_confirm_password); ?>" class="gerenric_input" required></div>
										</div>
									</div>
								</li>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">Email Adresse *</div>
											<div class="form_field"><input type="text" name="user_name" id="user_name" value="<?php print($user_name); ?>" class="gerenric_input" required></div>
										</div>
										<div class="form_right">
											<div class="form_label">Telefon *</div>
											<div class="form_field"><input type="text" name="user_phone" id="user_phone" value="<?php print($user_phone); ?>" class="gerenric_input"></div>
										</div>
									</div>
								</li>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">Land</div>
											<div class="form_field">
												<select class="gerenric_input" name="countries_id" id="countries_id">
													<?php FillSelected2("countries", "countries_id", "countries_name ", $countries_id, "countries_id > 0"); ?>
												</select>
											</div>
										</div>
									</div>
								</li>
								<li>
									<?php
									$digits = 4;
									$confirm_code = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
									?>
									<div class="form_label">Bitte Bestätigungscode eingeben: <span class="code_text"><?php print($confirm_code); ?></span></div>
									<input type="hidden" name="confirm_code" id="confirm_code" value="<?php print($confirm_code); ?>">
									<div class="form_field"><input type="text" class="gerenric_input" name="reconfirm_code" id="reconfirm_code" maxlength="4" required autocomplete="off" onKeyPress="if(this.value.length==4) return false;" ></div>
								</li>
								<li class="mt_30"><input type="checkbox" required> Ich habe die Datenschutzbestimmungen zur Kenntnis genommen | <a href="privacy">Datenschutzerklärung </a></li>
								<li><button type="submit" name="btn_registration" class="gerenric_btn full_btn">Jetzt registrieren</button></li>
							</ul>
						</div>
					</form>

				</div>
			</div>
		</section>
		<!--REGISTER_PAGE_END-->

		<!--FOOTER_SECTION_START-->
		<div id="footer_register">
			<div class="page_width">
				<ul>
					<li><a href="javascript:void(0)">Cookie-Einstellungen </a></li>
					<li><a href="impressum">Impressum</a></li>
					<li><a href="privacy">Datenschutzerklärung</a></li>
					<li><a href="term">Allgemeinen Geschäftsbedingungen</a></li>
					<li><a href="kontakt">Kontakt</a></li>
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
<script>
	$(".utype_id").on("click", function() {
		let utype_id = $("input[name='utype_id']:checked").val();
		//console.log("utype_id: "+utype_id);
		if (utype_id == 4) {
			$("#user_company_name").attr("required", true);
			$("#user_tax_no").attr("required", true);
			$('#user_company_input').show();
		} else {
			$("#user_company_name").attr("required", false);
			$("#user_tax_no").attr("required", false);
			$('#user_company_input').hide();
		}
	});
</script>

</html>