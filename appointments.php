<?php include("includes/php_includes_top.php"); ?>
<!doctype html>
<html lang="de">

<head>
	<?php include("includes/html_header.php"); ?>
</head>

<body style="background-color: #fff !important;">
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
					<div class="appointment_inner">
						<h3><?php print(config_appointment_heading_de); ?> </h3>
						<h4><?php print(config_appointment_detail_de); ?></h4>
						<div class="appointment_row">
							<?php
							$Query = "SELECT asch.as_id, asch.as_duration, asch.as_title_de AS as_title, asch.as_detail_de AS as_detail, asch.as_image FROM appointment_schedule AS asch WHERE asch.as_status = '1'";
							$rs = mysqli_query($GLOBALS['conn'], $Query);
							if (mysqli_num_rows($rs) > 0) {
								while ($row = mysqli_fetch_object($rs)) {
									$image_path = $GLOBALS['siteURL'] . "files/no_img_1.jpg";
									//if (!empty($row->ban_file) && pathinfo($row->ban_file, PATHINFO_EXTENSION) != "mp4") {
									if (!empty($row->as_image)) {
										$image_path = $GLOBALS['siteURL'] . "files/appointment_schedule/" . $row->as_image;
									}
							?>
								<div class="appointment_col">
									<div class="appointment_card">
										<div class="appintment_image"><img src="<?php print($image_path); ?>" alt=""></div>
										<div class="appintment_detail">
											<h2><?php print($row->as_title); ?></h2>
											<div class="appointment_time"><?php print($row->as_duration); ?> minutes</div>
											<p><?php print(limit_text($row->as_detail, 265)); ?></p>
											<div class="full_width txt_align_right">
												<a href="terminauswählen/<?php print($row->as_id); ?>">
													<div class="gerenric_btn">Termin auswählen</div>
												</a>
											</div>
										</div>
									</div>
								</div>
							<?php
								}
							}
							?>
						</div>
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