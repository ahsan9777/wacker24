<?php
 include("includes/php_includes_top.php");
	$cu_name = "";
	$cu_email = "";
	$cu_phone = "";
	$cu_subject = "";
	$cu_message = "";
	//print_r($_REQUEST);
 if(isset($_REQUEST['btn_contactus'])){

	if ($_REQUEST['confirm_code'] != $_REQUEST['reconfirm_code']) {

		$cu_name = $_REQUEST['cu_name'];
		$cu_email = $_REQUEST['cu_email'];
		$cu_phone = $_REQUEST['cu_phone'];
		$cu_subject = $_REQUEST['cu_subject'];
		$cu_message = $_REQUEST['cu_message'];

		$class = "alert alert-danger";
		$strMSG = "Dear Cuctomer, <br>
				confirmation does not match!";
	} else {
		$cu_id = getMaximum("contact_us_request", "cu_id");
		mysqli_query($GLOBALS['conn'], "INSERT INTO contact_us_request (cu_id, cu_name, cu_email, cu_phone, cu_subject, cu_message, cu_date) VALUES ('".$cu_id."', '".dbStr(trim($_REQUEST['cu_name']))."', '".dbStr(trim($_REQUEST['cu_email']))."', '".dbStr(trim($_REQUEST['cu_phone']))."', '".dbStr(trim($_REQUEST['cu_subject']))."', '".dbStr(trim($_REQUEST['cu_message']))."', '".date_time."')") or die(mysqli_error($GLOBALS['conn']));
		header("Location: kontakt/8");
	}
 }

 include("includes/message.php");
 ?>
<!doctype html>
<html lang="de">

<head>
	<?php include("includes/html_header.php"); ?>
</head>

<body>
	<div id="container" align="center">

		<!--LOCATION_POPUP_START-->
		<?php include("includes/popup.php"); ?>
		<!--LOCATION_POPUP_END-->

		<!--HEADER_SECTION_START-->
		<?php include("includes/navigation.php"); ?>
		<!--HEADER_SECTION_END-->

		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="contact_page gerenric_padding">
				<div class="page_width">
					<div class="contact_inner">
						<div class="contact_col">
							<div class="contact_map">
								<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d10397.416599793687!2d8.1595303!3d49.3454452!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xf891f6d9c1b2e882!2sWacker%20Office%20Center%20GmbH!5e0!3m2!1sen!2s!4v1654254885248!5m2!1sen!2s" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
								<div class="map_link"><a href="https://www.google.com/maps/place/Wacker+Office+Center+GmbH/@49.3454452,8.1595303,15z/data=!4m5!3m4!1s0x0:0xf891f6d9c1b2e882!8m2!3d49.3454539!4d8.1594747">Wacker Bürocenter GmbH, Chemnitzer Str. 1, 67433 Neustadt/Weinstraße</a></div>
							</div>
						</div>
						<div class="contact_col">
							<h2>Kontaktformular</h2>
							<?php if ($class != "") { ?>
								<div class="<?php print($class); ?>"><?php print($strMSG); ?><a href="javascript:void(0);" class="close" data-dismiss="alert">×</a></div>
							<?php } ?>
							<form class="gerenric_form" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
								<ul>
									<li>
										<div class="form_row">
											<div class="form_left">
												<div class="form_label">Name</div>
												<div class="form_field"><input type="text" class="gerenric_input" name="cu_name" id="cu_name" required value="<?php print($cu_name); ?>" required placeholder="Ihr Vorname / Nachname"></div>
											</div>
											<div class="form_right">
												<div class="form_label">E-Mail-Adresse</div>
												<div class="form_field"><input type="text" class="gerenric_input" name="cu_email" id="cu_email" required value="<?php print($cu_email); ?>" placeholder="Ihre E-Mail Addresse"></div>
											</div>
										</div>
									</li>
									<li>
										<div class="form_row">
											<div class="form_left">
												<div class="form_label">Telefonnummer</div>
												<div class="form_field"><input type="number" class="gerenric_input" name="cu_phone" id="cu_phone" value="<?php print($cu_phone); ?>" placeholder="Ihrer Telefonnummer"></div>
											</div>
											<div class="form_right">
												<div class="form_label">Thema</div>
												<div class="form_field">
													<select class="gerenric_input" name="cu_subject" id="cu_subject" required>
														<option value="Bitte auswählen" <?php print( (($cu_subject == 'Bitte auswählen')? 'selected' : '') ); ?> >Bitte auswählen</option>
														<option value="Bestellung" <?php print( (($cu_subject === 'Bestellung')? 'selected' : '') ); ?> >Bestellung</option>
														<option value="Fragen zu Lieferzeit/Produkt" <?php print( (($cu_subject == 'Fragen zu Lieferzeit/Produkt')? 'selected' : '') ); ?> >Fragen zu Lieferzeit/Produkt</option>
														<option value="Retouren/Rücknahme" <?php print( (($cu_subject === 'Retouren/Rücknahme')? 'selected' : '') ); ?> >Retouren/Rücknahme</option>
														<option value="Mein Benutzerkonto" <?php print( (($cu_subject == 'Mein Benutzerkonto')? 'selected' : '') ); ?> >Mein Benutzerkonto</option>
														<option value="Persönliche Daten anfordern" <?php print( (($cu_subject == 'Persönliche Daten anfordern')? 'selected' : '') ); ?> >Persönliche Daten anfordern</option>
														<option value="Löschung persönlicher Daten anfordern" <?php print( (($cu_subject == 'Löschung persönlicher Daten anfordern')? 'selected' : '') ); ?> >Löschung persönlicher Daten anfordern</option>
														<option value="Technische Störung" <?php print( (($cu_subject == 'Technische Störung')? 'selected' : '') ); ?> >Technische Störung</option>
														<option value="Sonstiges" <?php print( (($cu_subject == 'Sonstiges')? 'selected' : '') ); ?> >Sonstiges</option>
													</select>
												</div>
											</div>
										</div>
									</li>
									<li>
										<div class="form_label">Ihre Nachricht</div>
										<div class="form_field"><textarea class="gerenric_input gerenric_textarea" name="cu_message" id="cu_message" placeholder=""> <?php print($cu_message); ?> </textarea></div>
									</li>
									<li>
										<?php
											$digits = 4;
											$confirm_code = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
										?>
										<div class="form_label">Bitte Bestätigungscode eingeben: <?php print($confirm_code); ?></div>
										<input type="hidden" name="confirm_code" id="confirm_code" value="<?php print($confirm_code); ?>">
										<div class="form_field"><input type="text" class="gerenric_input" name="reconfirm_code" id="reconfirm_code" maxlength="4" required autocomplete="off" onKeyPress="if(this.value.length==4) return false;" ></div>
									</li>
									<li><button class="gerenric_btn full_btn mt_30" type="submit" name="btn_contactus">Absenden</button></li>
								</ul>
							</form>
						</div>
						<div class="contact_col">
							<div class="contact_block">
								<h3>Öffnungszeiten Fachmarkt</h3>
								<p>Mo.-Fr.: 09:00 - 18:00 Uhr</p>
								<p>Sa.: 09:00 - 13:00 Uhr</p>
							</div>
							<div class="contact_block">
								<h3>Telefonisch erreichbar</h3>
								<p>Mo.-Fr.: 08:00 - 18:00 Uhr</p>
								<p>Sa.: 09:00 - 13:00 Uhr</p>
							</div>
						</div>
						<div class="contact_col">
							<div class="contact_block">
								<h3>Kundenservice</h3>
								<p>Wir helfen Ihnen persönlich weiter und stehen Ihnen während unserer Servicezeiten gerne zur Verfügung:</p>
							</div>
							<div class="contact_block">
								<h3>Telefon</h3>
								<p>06321 - 9124 -</p>
							</div>
							<div class="contact_table">
								<ul>
									<li>Abteilung</li>
									<li>Email</li>
									<li>Telefon</li>
								</ul>
								<ul>
									<li>Zentrale</li>
									<li>mail@wacker24.de</li>
									<li>-0</li>
								</ul>
								<ul>
									<li>Buchhaltung</li>
									<li>buchhaltung@wacker24.de</li>
									<li>-10</li>
								</ul>
								<ul>
									<li>Technik Drucker/Scanner</li>
									<li>technik@wacker24.de</li>
									<li>-22</li>
								</ul>
								<ul>
									<li>Technik Kassensysteme</li>
									<li>kassen@wacker24.de</li>
									<li>-23</li>
								</ul>
								<ul>
									<li>Technik IT</li>
									<li>support@wacker24.de</li>
									<li>-24</li>
								</ul>
								<ul>
									<li>Vertrieb Bürobedarf</li>
									<li>vertrieb@wacker24.de</li>
									<li>-30</li>
								</ul>
								<ul>
									<li>Vertrieb Möbel</li>
									<li>vertrieb@wacker24.de</li>
									<li>-40</li>
								</ul>
								<ul>
									<li>Vertrieb Technik</li>
									<li>vertrieb@wacker24.de</li>
									<li>-50</li>
								</ul>
								<ul>
									<li>Copyshop</li>
									<li>copyshop@wacker24.de</li>
									<li>-60</li>
								</ul>
								<ul>
									<li>Fachmarkt</li>
									<li>fachmarkt@wacker24.de</li>
									<li>-70</li>
								</ul>
								<ul>
									<li>Bestellung/Logistik</li>
									<li>bestellung@wacker24.de</li>
									<li>-80</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!--CONTENT_SECTION_END-->

		<!--FOOTER_SECTION_START-->
		<div id="scroll_top">Zurück zum Seitenanfang</div>
		<?php include("includes/footer.php"); ?>
		<!--FOOTER_SECTION_END-->

	</div>

</body>
<?php include("includes/bottom_js.php"); ?>

</html>