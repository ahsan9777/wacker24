<?php
include("includes/php_includes_top.php");
$account_verification = 0;
if (isset($_REQUEST['verification_code'])) {
	$user_id = returnName("user_id", "users", "user_confirmation", $_REQUEST['verification_code']);
	if ($user_id > 0) {
		mysqli_query($GLOBALS['conn'], "UPDATE users SET status_id = '1' WHERE user_id = '" . $user_id . "'") or die(mysqli_error($GLOBALS['conn']));
		$account_verification = 1;
	}
}
?>
<!doctype html>
<html lang="de">

<head>
	<?php include("includes/html_header.php"); ?>
	<script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>
</head>

<body class="body-white">
	<div id="container" align="center">

		<!--LOCATION_POPUP_START-->
		<?php include("includes/popup.php"); ?>
		<!--LOCATION_POPUP_END-->

		<!--HEADER_SECTION_START-->
		<?php include("includes/navigation.php"); ?>
		<!--HEADER_SECTION_END-->

		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="appointment_booking gerenric_padding">
				<div class="page_width">
					<h2 class="txt_align_center" >Konto-Verifizierung</h2>

					<div class="txt_align_center">
						<?php if ($account_verification > 0) { ?>
							<h4>Ihr Konto wurde erfolgreich verifiziert</h4>
							<dotlottie-player src="https://lottie.host/c255556c-8b33-4000-a5e3-69a68271fbdc/5lxOCkhCdm.json" background="transparent" speed="0.5" style="width: 300px; height: 300px; margin: auto;" autoplay></dotlottie-player>
							<script>
								setTimeout(
									function() {
										window.location.href = "anmelden";
									}, 10000);
							</script>
						<?php } else { ?>
							<h4>Ihr Konto wurde nicht verifiziert. Bitte kontaktieren Sie unser Team</h4>
							<dotlottie-player src="https://lottie.host/c87563de-d0b0-4260-b4e0-171b1047cb1a/RrwIU1dos6.json" background="transparent" speed="0.5" style="width: 300px; height: 300px; margin: auto;" autoplay></dotlottie-player>
						<?php } ?>
					</div>
				</div>
			</div>
		</section>
		<!--CONTENT_SECTION_END-->

		<!--FOOTER_SECTION_START-->
		
		<?php include("includes/footer.php"); ?>
		<!--FOOTER_SECTION_END-->

	</div>

</body>
<?php include("includes/bottom_js.php"); ?>

</html>