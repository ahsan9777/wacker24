<?php
$cnt_id = 0;
include("includes/php_includes_top.php");


$strMSG = "";
$class = "";
$lotti_player_popup = "";

$user_fname = "";
$user_lname = "";
$gen_id = 1;
$user_phone = "";
$user_name = "";
$user_password = "";
$user_confirm_password = "";
$countries_id = 81;

if (isset($_REQUEST['btn_registration'])) {
	//print_r($_REQUEST);die();
	$Query = "SELECT * FROM `users` WHERE user_name ='" . dbStr(trim($_REQUEST['user_name'])) . "'";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) > 0) {
		$user_fname = $_REQUEST['user_fname'];
		$user_lname = $_REQUEST['user_lname'];
		$gen_id = $_REQUEST['gen_id'];
		$user_phone = $_REQUEST['user_phone'];
		$user_name = $_REQUEST['user_name'];
		$user_password = $_REQUEST['user_password'];
		$user_confirm_password = $_REQUEST['user_confirm_password'];
		$countries_id = $_REQUEST['countries_id'];
		$class = "alert alert-danger";
		$strMSG = "Dear Cuctomer, <br>
                    This account already exists against the user name!";
	} else {
		if ($_REQUEST['user_password'] != $_REQUEST['user_confirm_password']) {
			$user_fname = $_REQUEST['user_fname'];
			$user_lname = $_REQUEST['user_lname'];
			$gen_id = $_REQUEST['gen_id'];
			$user_phone = $_REQUEST['user_phone'];
			$user_name = $_REQUEST['user_name'];
			$user_password = $_REQUEST['user_password'];
			$user_confirm_password = $_REQUEST['user_confirm_password'];
			$countries_id = $_REQUEST['countries_id'];
			
			$class = "alert alert-danger";
			$strMSG = "Dear Cuctomer, <br>
                    Passwords does not match!";
		} else {

			if($_REQUEST['confirm_code'] != $_REQUEST['reconfirm_code']){

				$user_fname = $_REQUEST['user_fname'];
				$user_lname = $_REQUEST['user_lname'];
				$gen_id = $_REQUEST['gen_id'];
				$user_phone = $_REQUEST['user_phone'];
				$user_name = $_REQUEST['user_name'];
				$user_password = $_REQUEST['user_password'];
				$user_confirm_password = $_REQUEST['user_confirm_password'];
				$countries_id = $_REQUEST['countries_id'];

				$class = "alert alert-danger";
				$strMSG = "Dear Cuctomer, <br>
                    	confirmation does not match!";
			} else{
				$user_id = getMaximum("users", "user_id");
				$user_verification_code = md5($user_id.date("Ymdhis"));
				mysqli_query($GLOBALS['conn'], "INSERT INTO users (user_id, user_fname, user_lname, gen_id, user_phone, user_name, user_password, countries_id, user_confirmation) VALUES ('" . $user_id . "','" . dbStr(trim($_REQUEST['user_fname'])) . "','" . dbStr(trim($_REQUEST['user_lname'])) . "','" . $_REQUEST['gen_id'] . "','" . dbStr(trim($_REQUEST['user_phone'])) . "','" . dbStr(trim($_REQUEST['user_name'])) . "','" . dbStr(password_hash(trim($_REQUEST['user_password']), PASSWORD_BCRYPT)) . "','" . dbStr(trim($_REQUEST['countries_id'])) . "', '".$user_verification_code."')") or die(mysqli_error($GLOBALS['conn']));
				$class = "alert alert-success";
				$strMSG = "Dear Cuctomer, <br>
				your account has been created successfully. Please <a href='login.php'>log in</a> to your account  and enjoy our services";
			}
		}
	}
}

include("includes/message.php");
?>
<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<link rel="icon" type="image/x-icon" href="images/favicon.ico">
	<meta name="viewport" content="width=device-width; initial-scale=1.0">
	<title>Wacker 24</title>
	<link href="css/styles.css" rel="stylesheet" type="text/css" />
	<link href="css/responsive.css" rel="stylesheet" type="text/css" />
	<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
	<script src="js/jquery-2.2.0.min.js"></script>


</head>

<body class="body-white">
	<div id="container" align="center">

		<!--REGISTER_PAGE_START-->
		<section class="login_page register_page">
			<div class="page_width">
				<div class="login_inner">
					<div class="login_logo"><a href="index.php"><img src="images/register_logo.png" alt=""></a></div>
					<form class="login_box" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
						<h2>Register</h2>
						<?php if ($class != "") { ?>
							<div class="<?php print($class); ?>"><?php print($strMSG); ?><a href="javascript:void(0);" class="close" data-dismiss="alert">×</a></div>
						<?php } ?>
						<div class="gerenric_form">
							<ul>
								<li>
									<div class="tab_radio_button">
										<div class="tab_radio_col">
											<input type="radio" id="private_customer" name="utype_id" value="3" checked>
											<label for="private_customer">Private customer</label>
										</div>
										<div class="tab_radio_col">
											<input type="radio" id="business_customer" name="utype_id" value="4">
											<label for="business_customer">Business customer</label>
										</div>
									</div>
								</li>
								<li>
									<div class="form_label">Salutation</div>
									<div class="form_field">
										<select class="gerenric_input" name="gen_id" id="gen_id">
											<option value="1" <?php print( ($gen_id == 1)? 'checked' : '' ); ?> >Herr</option>
											<option value="2" <?php print( ($gen_id == 2)? 'checked' : '' ); ?> >Frau</option>
											<option value="3" <?php print( ($gen_id == 3)? 'checked' : '' ); ?> >Keine</option>
										</select>
									</div>
								</li>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">First name *</div>
											<div class="form_field"><input type="text" name="user_fname" id="user_fname" value="<?php print($user_fname); ?>" class="gerenric_input" required ></div>
										</div>
										<div class="form_right">
											<div class="form_label">Last name *</div>
											<div class="form_field"><input type="text" name="user_lname" id="user_lname" value="<?php print($user_lname); ?>" class="gerenric_input" required ></div>
										</div>
									</div>
								</li>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">Password *</div>
											<div class="form_field"><input type="password" name="user_password" id="user_password" value="<?php print($user_password); ?>" class="gerenric_input" required ></div>
										</div>
										<div class="form_right">
											<div class="form_label">Repeat your password *</div>
											<div class="form_field"><input type="password" name="user_confirm_password" id="user_confirm_password" value="<?php print($user_confirm_password); ?>" class="gerenric_input" required ></div>
										</div>
									</div>
								</li>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">E-mail address *</div>
											<div class="form_field"><input type="text" name="user_name" id="user_name" value="<?php print($user_name); ?>" class="gerenric_input" required></div>
										</div>
										<div class="form_right">
											<div class="form_label">Phone *</div>
											<div class="form_field"><input type="text" name="user_phone" id="user_phone" value="<?php print($user_phone); ?>" class="gerenric_input"></div>
										</div>
									</div>
								</li>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">Country</div>
											<div class="form_field">
												<select class="gerenric_input" name="countries_id" id="countries_id" >
												<?php FillSelected2("countries", "countries_id", "countries_name ", $countries_id, "countries_id > 0"); ?>
												</select>
											</div>
										</div>
									</div>
								</li>
								<li>
									<?php
									$digits = 4;
									$confirm_code = rand(pow(10, $digits-1), pow(10, $digits)-1);
									?>
									<div class="form_label">Please enter confirmation code: <span class="code_text"><?php print($confirm_code); ?></span></div>
									<input type="hidden" name="confirm_code" id="confirm_code" value="<?php print($confirm_code); ?>">
									<div class="form_field"><input type="text" class="gerenric_input" name="reconfirm_code" id="reconfirm_code" maxlength="4" required autocomplete="off" ></div>
								</li>
								<li class="mt_30"><input type="checkbox" required> I have read the privacy policy | <a href="javascript:void(0)">Privacy Policy</a></li>
								<li><button type="submit" name="btn_registration" class="gerenric_btn full_btn">Register Now</button></li>
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
					<li><a href="javascript:void(0)">Cookie Settings </a></li>
					<li><a href="javascript:void(0)">Imprint</a></li>
					<li><a href="javascript:void(0)">Privacy & Security</a></li>
					<li><a href="javascript:void(0)">Terms & Conditions</a></li>
					<li><a href="contact_page.html">Contact</a></li>
				</ul>
			</div>
		</div>
		<!--FOOTER_SECTION_END-->

	</div>

</body>
<script>
	$('.close').on('click', function(){
        $('.alert').hide();
    });
</script>
</html>