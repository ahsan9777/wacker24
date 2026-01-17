<?php
include("includes/php_includes_top.php");
$Query1 = "SELECT cnt.cnt_id, cnt.cnt_slug, cnt.cnt_section, cnt.cnt_title_de AS cnt_title, cnt.cnt_heading_de AS cnt_heading, cnt.cnt_details_de AS cnt_details, cnt.cnt_image, cnt.cnt_banner_image  FROM contents AS cnt WHERE cnt.cnt_slug = '" . $_REQUEST['cnt_slug'] . "'";
//print($Query1);
$rs1 = mysqli_query($GLOBALS['conn'], $Query1);
if (mysqli_num_rows($rs1) > 0) {
	$row1 = mysqli_fetch_object($rs1);
	$cnt_id = $row1->cnt_id;
	$cnt_slug = $row1->cnt_slug;
	$cnt_section = $row1->cnt_section;
	$cnt_title = $row1->cnt_title;
	$cnt_heading = $row1->cnt_heading;
	$cnt_details = $row1->cnt_details;
	$cnt_image = !empty($row1->cnt_image) ? $GLOBALS['siteURL'] . "files/contents/" . $row1->cnt_image : "";
	$cnt_banner_image = !empty($row1->cnt_banner_image) ? $GLOBALS['siteURL'] . "files/contents/" . $row1->cnt_banner_image : "";
}
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
			<div class="about_page gerenric_padding">
				<div class="page_width_1480">
					<div class="about_inner">
						<?php if (!empty($cnt_banner_image)) { ?>
							<div class="about_banner"><img src="<?php print($cnt_banner_image); ?>" alt=""></div>
						<?php } ?>
						<?php
						if ($cnt_section > 0) {
							$Query2 = "SELECT cs.csec_id, cs.cst_id, cs.cnt_id, cs.csec_year, cs.csec_heading_one_de AS csec_heading_one, cs.csec_content_one_de AS csec_content_one, cs.csec_heading_two_de AS csec_heading_two, cs.csec_content_two_de AS csec_content_two, cs.csec_banner_image, cs.csec_image_one, cs.csec_image_two FROM content_sections AS cs WHERE cs.csec_status = '1' AND cs.cnt_id = '" . $cnt_id . "' ORDER BY cs.cst_orderby ASC";
							$rs2 = mysqli_query($GLOBALS['conn'], $Query2);
							if (mysqli_num_rows($rs2) > 0) {
								while ($row2 = mysqli_fetch_object($rs2)) {
									$cst_id = $row2->cst_id;
									$csec_year = $row2->csec_year;
									$csec_heading_one = $row2->csec_heading_one;
									$csec_content_one = $row2->csec_content_one;
									$csec_heading_two = $row2->csec_heading_two;
									$csec_content_two = $row2->csec_content_two;
									$csec_banner_image = !empty($row2->csec_banner_image) ? $GLOBALS['siteURL'] . "files/contents/" . $row2->cnt_id . "/" . $row2->csec_banner_image : "";
									$csec_image_one = !empty($row2->csec_image_one) ? $GLOBALS['siteURL'] . "files/contents/" . $row2->cnt_id . "/" . $row2->csec_image_one : "";
									$csec_image_two = !empty($row2->csec_image_two) ? $GLOBALS['siteURL'] . "files/contents/" . $row2->cnt_id . "/" . $row2->csec_image_two : "";
									if ($cst_id == 1) {
										include("includes/cst_one.php");
									} elseif ($cst_id == 2) {
										include("includes/cst_two.php");
									} elseif ($cst_id == 3) {
										include("includes/cst_three.php");
									} elseif ($cst_id == 4) {
										include("includes/cst_four.php");
									} elseif ($cst_id == 5) {
										include("includes/cst_five.php");
									} elseif ($cst_id == 6) {
										include("includes/cst_six.php");
									} elseif ($cst_id == 7) {
										include("includes/cst_seven.php");
									}
						?>

						<?php
								}
							}
						} else {
							include("includes/cst_genaric_template.php");
						} ?>
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