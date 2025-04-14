<?php
include("includes/php_includes_top_user_dashboard.php");
$page = 1;

$Query = "SELECT u.user_id, u.user_name, u.user_fname, u.user_lname, u.user_phone, ut.utype_name FROM users AS u LEFT OUTER JOIN user_type AS ut ON ut.utype_id = u.utype_id WHERE u.user_id = '".$_SESSION["UID"]."'";
$rs = mysqli_query($GLOBALS['conn'], $Query);
if(mysqli_num_rows($rs) > 0){
	$row = mysqli_fetch_object($rs);
		
	$user_id = $row->user_id;
	$user_name = $row->user_name;
	$user_fname = $row->user_fname;
	$user_lname = $row->user_lname;
	$user_phone = $row->user_phone;
	$utype_name = $row->utype_name;
}

if(isset($_REQUEST['btnUpdate'])){
	
		$qryUdt = "";
		if(isset($_REQUEST['user_fname'])){
			$qryUdt .= " user_fname='".dbStr(trim($_REQUEST['user_fname']))."',";
		}
		if(isset($_REQUEST['user_lname'])){
			$qryUdt .= " user_lname='".dbStr(trim($_REQUEST['user_lname']))."',";
		}
		if(isset($_REQUEST['user_phone'])){
			$qryUdt .= " user_phone='".dbStr(trim($_REQUEST['user_phone']))."',";
		}
		if(isset($_REQUEST['old_password'])){
			$old_password = trim($_REQUEST['old_password']);
			$check_old_password = returnName("user_password", "users", "user_id", $_SESSION["UID"]);

			if(password_verify($old_password, $check_old_password)){
				$new_password = $_REQUEST['new_password'];
				$confirm_password = $_REQUEST['confirm_password'];

				if($confirm_password == $new_password){
					$qryUdt .= " user_password='".dbStr(password_hash(trim($new_password), PASSWORD_BCRYPT))."',";
				} else{
					header("Location: persoenliche-angaben/11");
				}
			} else{
				header("Location: persoenliche-angaben/7");
			}
		}
		$qryUdt = rtrim($qryUdt,",");
		//echo "UPDATE users SET ".$qryUdt." WHERE user_id = '".$_SESSION["UID"]."'";
		if(!empty($qryUdt)){
			mysqli_query($GLOBALS['conn'], "UPDATE users SET ".$qryUdt." WHERE user_id = '".$_SESSION["UID"]."'") or die(mysqli_error($GLOBALS['conn']));
			header("Location: persoenliche-angaben/2");
		}
}

include("includes/message.php");
?>
<!doctype html>
<html>

<head>
	<?php include("includes/html_header.php"); ?>
</head>

<body>
	<div id="container" align="center">

		<!--LOCATION_POPUP_START-->
		<?php include("includes/popup.php"); ?>
		<div class="form_popup form_firstname_popup">
			<div class="inner_popup">
				<div class="form_popup_content">
					<div class="form_popup_heading">Name ändern <div class="form_popup_close"><i class="fa fa-times"></i></div>
					</div>
					<div class="form_popup_content_inner">
						<form class="gerenric_form"  name="frmname" id="frmname" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data" >
							<ul>
								<li>
									<div class="form_label">Vorname</div>
									<div class="form_field"><input type="text" id="user_fname" name="user_fname" class="gerenric_input"></div>
								</li>
								<li>
									<div class="form_label">Nachname</div>
									<div class="form_field"><input type="text" id="user_lname" name="user_lname" class="gerenric_input"></div>
								</li>
								<li class="mt_30">
									<div class="form_two_button">
										<button class="gerenric_btn" type="submit" name="btnUpdate">Aktualisieren</button>
										<button class="gerenric_btn gray_btn form_popup_close">Abbrechen</button>
									</div>
								</li>
							</ul>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="form_popup form_phoneno_popup">
			<div class="inner_popup">
				<div class="form_popup_content">
					<div class="form_popup_heading">Telefonnummer ändern <div class="form_popup_close"><i class="fa fa-times"></i></div>
					</div>
					<div class="form_popup_content_inner">
						<form class="gerenric_form" name="frmphone" id="frmphone" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
							<ul>
								<li>
									<div class="form_label">Telefonnummer</div>
									<div class="form_field"><input type="text" id="user_phone" name="user_phone" class="gerenric_input"></div>
								</li>
								<li class="mt_30">
									<div class="form_two_button">
										<button class="gerenric_btn" type="submit" name="btnUpdate">Aktualisieren</button>
										<button class="gerenric_btn gray_btn form_popup_close">Abbrechen</button>
									</div>
								</li>
							</ul>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="form_popup form_password_popup">
			<div class="inner_popup">
				<div class="form_popup_content">
					<div class="form_popup_heading">Passwort ändern <div class="form_popup_close"><i class="fa fa-times"></i></div>
					</div>
					<div class="form_popup_content_inner">
						<form class="gerenric_form" name="frmphone" id="frmphone" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
							<ul>
								<li>
									<div class="form_label">Altes Passwort eingeben</div>
									<div class="form_field"><input type="password" id="old_password" name="old_password" class="gerenric_input"></div>
								</li>
								<li>
									<div class="form_label">Neues Passwort eingeben</div>
									<div class="form_field"><input type="password" id="new_password" name="new_password" class="gerenric_input"></div>
								</li>
								<li>
									<div class="form_label">Neues Passwort nochmals eingeben</div>
									<div class="form_field"><input type="password" id="confirm_password" name="confirm_password" class="gerenric_input"></div>
								</li>
								<li class="mt_30">
									<div class="form_two_button">
										<button class="gerenric_btn" type="submit" name="btnUpdate">Aktualisieren</button>
										<button class="gerenric_btn gray_btn form_popup_close">Abbrechen</button>
									</div>
								</li>
							</ul>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!--LOCATION_POPUP_END-->

		<!--HEADER_SECTION_START-->
		<?php include("includes/navigation.php"); ?>
		<!--HEADER_SECTION_END-->

		<!--BREADCRUMB_SECTION_START-->
		<div class="gerenric_breadcrumb">
			<div class="page_width_1480">
				<div class="breadcrumb_inner">
					<ul>
						<li><a href="benutzerprofile">Meine Daten</a></li>
						<li><a href="javascript:void(0)">Persönliche Details</a></li>
					</ul>
				</div>
			</div>
		</div>
		<!--BREADCRUMB_SECTION_END-->

		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="my_data_page gerenric_padding">
				<div class="page_width_1480">
					<h1>Meine Daten</h1>
					<div class="my_data_box">
						<?php if ($class != "") { ?>
							<div class="<?php print($class); ?>"><?php print($strMSG); ?><a href="javascript:void(0);" class="close" data-dismiss="alert">×</a></div>
						<?php } ?>
						<div class="my_data_block">
							<div class="data_detail">
								<h3>Name (Vorname / Nachname)</h3>
								<p> <?php print($user_fname." ".$user_lname); ?> </p>
							</div>
							<div class="data_button fisrtname_popup_trigger"><i class="fa fa-edit"></i></div>
						</div>
						<div class="my_data_block">
							<div class="data_detail">
								<h3>E-Mail-Adresse</h3>
								<p><?php print($user_name); ?></p>
							</div>
						</div>
						<div class="my_data_block">
							<div class="data_detail">
								<h3>Telefonnummer</h3>
								<p><?php print($user_phone); ?></p>
							</div>
							<div class="data_button phoneno_popup_trigger"><i class="fa fa-edit"></i></div>
						</div>
						<div class="my_data_block">
							<div class="data_detail">
								<h3>Kundengruppe</h3>
								<p> <?php print($utype_name); ?> </p>
							</div>
						</div>
						<div class="my_data_block">
							<div class="data_detail">
								<h3>Passwort</h3>
								<p>**********</p>
							</div>
							<div class="data_button password_popup_trigger"><i class="fa fa-edit"></i></div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!--CONTENT_SECTION_END-->

		<!--FOOTER_SECTION_START-->
		<div id="scroll_top">Meine Sonderpreise</div>
		<?php include("includes/footer.php"); ?>
		<!--FOOTER_SECTION_END-->

	</div>

</body>
<script>
	$(window).load(function() {
		$(".fisrtname_popup_trigger").click(function() {

			$.ajax({
				url: 'ajax_calls.php?action=mydata_popup_trigger',
				method: 'POST',
				success: function(response) {
					//console.log("response = "+response);
					const obj = JSON.parse(response);
					//console.log(obj);
					//console.log(obj.data[0].user_fname);
					if (obj.status == 1) {
						$('#user_fname').val(obj.data[0].user_fname);
						$('#user_lname').val(obj.data[0].user_lname);
						$('.form_firstname_popup').show();
						$('.form_firstname_popup').resize();
						$('body').css({'overflow':'hidden'});
					}
				}
			});
		});
		$(".phoneno_popup_trigger").click(function() {

			$.ajax({
				url: 'ajax_calls.php?action=mydata_popup_trigger',
				method: 'POST',
				success: function(response) {
					//console.log("response = "+response);
					const obj = JSON.parse(response);
					//console.log(obj);
					//console.log(obj.data[0].user_fname);
					if (obj.status == 1) {
						$('#user_phone').val(obj.data[0].user_phone);
						$('.form_phoneno_popup').show();
						$('.form_phoneno_popup').resize();
						$('body').css({ 'overflow': 'hidden'});
					}
				}
			});
		});
		$(".password_popup_trigger").click(function() {
			$('.form_password_popup').show();
			$('.form_password_popup').resize();
			$('body').css({
				'overflow': 'hidden'
			});
		});
		$('.form_popup_close').click(function() {
			$('.form_popup').hide();
			$('body').css({
				'overflow': 'inherit'
			});
		});
	});
</script>
<?php include("includes/bottom_js.php"); ?>

</html>