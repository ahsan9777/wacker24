<?php
include("includes/php_includes_top.php");

if(isset($_REQUEST['btn_request_return'])){
	//print_r($_REQUEST);die();
	$rr_id = getMaximum("return_request", "rr_id");
	mysqli_query($GLOBALS['conn'], "INSERT INTO return_request (rr_id, ord_id, rr_email, rr_name, rr_address, rr_order_date, rr_delivery_date, rr_application, rr_cdate) VALUES ('".$rr_id."', '".dbStr(trim($_REQUEST['ord_id']))."', '".dbStr(trim($_REQUEST['rr_email']))."', '".dbStr(trim($_REQUEST['rr_name']))."', '".dbStr(trim($_REQUEST['rr_address']))."', '".dbStr(trim($_REQUEST['rr_order_date']))."', '".dbStr(trim($_REQUEST['rr_delivery_date']))."', '".dbStr(trim($_REQUEST['rr_application']))."', '".date_time."')") or die(mysqli_error($GLOBALS['conn']));
	header("Location: " . $GLOBALS['siteURL'] . "vertrag-widerrufen/27");
}
include("includes/message.php");
?>
<!doctype html>
<html lang="de">

<head>
	<meta charset="utf-8">
	<link rel="icon" type="image/x-icon" href="images/favicon.ico">
	<meta name="viewport" content="width=device-width; initial-scale=1.0">
	<link rel="canonical" href="<?php print($GLOBALS['siteURL']."anmelden"); ?>">
	<base href="<?php print($GLOBALS['siteURL']); ?>">
	<meta name="publisher" content="Wacker Systems">
	<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
	<?php
	header("X-Robots-Tag: index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1");
	?>
	<title>Vertrag widerrufen</title>
	<link href="css/styles.css" rel="stylesheet" type="text/css" />
	<link href="css/responsive.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="<?php print(get_font_link(config_fonts)); ?>" />
	<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
	<script src="js/jquery-2.2.0.min.js"></script>
	<?php include("includes/btn_color.php"); ?>
	<style>
		
	</style>
</head>

<body class="body-white">
	<div id="container" align="center">

		<!--LOGIN_PAGE_START-->
		<section class="login_page">
			<div class="page_width">
				<div class="login_inner_return">
					<div class="login_logo"><a href="<?php print($GLOBALS['siteURL']); ?>"><img src="images/register_logo.png" alt=""></a></div>
					<div class="revocation-info">
						<p>
							Hier können Sie einen mit <strong>www.wacker-buerocenter.de</strong> im Fernabsatz
							geschlossenen Vertrag elektronisch widerrufen. Nach dem Absenden erhalten Sie
							unverzüglich eine <strong>Eingangsbestätigung</strong> per E-Mail mit
							Datum und Uhrzeit des Eingangs.
						</p>

						<p>
							Die Widerrufsfrist beträgt 14 Tage ab Erhalt der Ware.
						</p>

						<p>
							<strong>Bitte beachten Sie:</strong> Die Eingangsbestätigung ist keine Bestätigung
							des Widerrufs selbst. Ob ein Widerrufsrecht besteht, hängt von der Art der Ware ab
							(siehe Ausschluss- bzw. Erlöschensgründe unten).
						</p>

						<p>
							Das Widerrufsrecht steht ausschließlich Verbrauchern zu. Verbraucher im Sinne der
							nachstehenden Regelungen ist jede natürliche Person, die ein Rechtsgeschäft zu
							Zwecken abschließt, die überwiegend weder ihrer gewerblichen noch ihrer
							selbständigen beruflichen Tätigkeit zugerechnet werden kann.
						</p>
					</div>
					<div class="login_box">
						<h2>Vertrag widerrufen</h2>
						<?php if ($class != "") { ?>
							<div class="<?php print($class); ?>"><?php print($strMSG); ?><a href="javascript:void(0);" class="close" data-dismiss="alert">×</a></div>
						<?php } ?>
						<form class="gerenric_form" name="frm" id="frm" method="post" action="vertrag-widerrufen" role="form" enctype="multipart/form-data">
							<ul>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">Bestellnummer  *</div>
											<div class="form_field"><input type="text" name="ord_id" id="ord_id" value="" class="gerenric_input" required></div>
										</div>
										<div class="form_right">
											<div class="form_label">E-Mail-Adresse *</div>
											<div class="form_field"><input type="text" name="rr_email" id="rr_email" value="" class="gerenric_input" required></div>
										</div>
									</div>
								</li>
								<li>
									<div class="form_label">Name *</div>
									<div class="form_field"><input type="text" class="gerenric_input" name="rr_name" id="rr_name" required></div>
								</li>
								<li>
									<div class="form_label">Anschrift (optional)</div>
									<div class="form_field"><textarea type="text" class="gerenric_input" name="rr_address" id="rr_address"></textarea></div>
								</li>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">Bestelldatum (optional)</div>
											<div class="form_field"><input type="date" name="rr_order_date" id="rr_order_date" value="" class="gerenric_input"></div>
										</div>
										<div class="form_right">
											<div class="form_label">Lieferdatum (Erhalt der Ware) (optional)</div>
											<div class="form_field"><input type="date" name="rr_delivery_date" id="rr_delivery_date" value="" class="gerenric_input"></div>
										</div>
									</div>
								</li>
								<li>
									<div class="form_label">Betrifft folgende Ware / Leistung (optional)</div>
									<div class="form_field"><textarea type="text" class="gerenric_input" name="rr_application" id="rr_application"></textarea></div>
								</li>
								<li><button type="submit" name="btn_request_return" class="gerenric_btn full_btn">Widerruf prüfen & fortfahren</button></li>
							</ul>
						</form>
					</div>

					<div class="revocation-section">

						<h2>Ausschluss- bzw. Erlöschensgründe</h2>

						<div class="revocation-box">

							<p class="revocation-title">
								Das Widerrufsrecht besteht nicht bei Verträgen
							</p>

							<ul>
								<li>
									zur Lieferung von Waren, die nicht vorgefertigt sind und für deren
									Herstellung eine individuelle Auswahl oder Bestimmung durch den
									Verbraucher maßgeblich ist oder die eindeutig auf die persönlichen
									Bedürfnisse des Verbrauchers zugeschnitten sind;
								</li>

								<li>
									zur Lieferung von Waren, die schnell verderben können oder deren
									Verfallsdatum schnell überschritten würde;
								</li>

								<li>
									zur Lieferung alkoholischer Getränke, deren Preis bei Vertragsabschluss
									vereinbart wurde, die aber frühestens 30 Tage nach Vertragsschluss
									geliefert werden können und deren aktueller Wert von Schwankungen auf
									dem Markt abhängt;
								</li>

								<li>
									zur Lieferung von Zeitungen, Zeitschriften oder Illustrierten mit
									Ausnahme von Abonnement-Verträgen.
								</li>
							</ul>

							<p class="revocation-title">
								Das Widerrufsrecht erlischt vorzeitig bei Verträgen
							</p>

							<ul>
								<li>
									zur Lieferung versiegelter Waren, die aus Gründen des Gesundheitsschutzes
									oder der Hygiene nicht zur Rückgabe geeignet sind, wenn ihre Versiegelung
									nach der Lieferung entfernt wurde;
								</li>

								<li>
									zur Lieferung von Waren, wenn diese nach der Lieferung aufgrund ihrer
									Beschaffenheit untrennbar mit anderen Gütern vermischt wurden;
								</li>

								<li>
									zur Lieferung von Ton- oder Videoaufnahmen oder Computersoftware in einer
									versiegelten Packung, wenn die Versiegelung nach der Lieferung entfernt wurde.
								</li>
							</ul>

						</div>

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
					<li><a href="kontakt">Kontakt</a></li>
				</ul>
			</div>
		</div>
		<!--FOOTER_SECTION_END-->

	</div>

</body>
<script>
	$(document).ready(function() {
		$('.toggle-password').on('click', function() {
			let input = $($(this).data('target'));

			if (input.attr('type') === 'password') {
				input.attr('type', 'text');
				$(this).removeClass('fa-eye').addClass('fa-eye-slash');
			} else {
				input.attr('type', 'password');
				$(this).removeClass('fa-eye-slash').addClass('fa-eye');
			}
		});
	});
</script>
</html>