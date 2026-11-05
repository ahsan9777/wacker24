<?php
http_response_code(404);
include("includes/php_includes_top.php");
$page = 1;
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
		<!--LOCATION_POPUP_END-->

		<!--HEADER_SECTION_START-->
		<?php include("includes/navigation.php"); ?>
		<!--HEADER_SECTION_END-->
		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="page_width_1480">
				<div class="not_available">
					<div class="not_available_txt">
						<h1>👋 Hallo!</h1>
						<p>🛍️ Alle unsere Produkte sind vorrätig.</p>
						<p>⚙️ Aufgrund einiger Systemaktualisierungen werden sie momentan möglicherweise als „nicht verfügbar“ angezeigt.</p>
						<p> <strong>🔎 Bitte suchen Sie die Artikel direkt im Shop</strong> – vielen Dank für Ihre verständnis! 💛</p>
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