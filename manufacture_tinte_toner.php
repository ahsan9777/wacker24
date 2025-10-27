<?php
include("includes/php_includes_top.php");
$Query = "SELECT manf_id, manf_name, manf_name_params FROM `manufacture` WHERE manf_id IN (SELECT DISTINCT(manf_id) FROM vu_category_map WHERE cat_id = '70100') ORDER BY manf_name ASC";
//print($Query . "<br>");
$count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], $Query));
$data_break_check = round($count / 4);
//print($data_break_check);die();
$i = 0;
$j = 0;
$rs = mysqli_query($GLOBALS['conn'], $Query);
if (mysqli_num_rows($rs) > 0) {
	while ($row = mysqli_fetch_object($rs)) {
		if($data_break_check == $j){
			$i++;
			$j = 0;
		}
		$j++;
		$manf_name = $row->manf_name;
		$manf_name_params = $row->manf_name_params;
		$manufacture_data[$i][] = array(
			"manf_title" => $manf_name,
			"manf_name_params" => $GLOBALS['siteURL'] . "tint-toner/" . $manf_name_params
		);
	}
}
/*print("<pre>");
print_r($manufacture_data);
print("</pre>");
/*foreach ($manufacture_data['0'] as $item) {
	echo $item['manf_title'] . "\n";
}*/
?>
<html lang="de">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width; initial-scale=1.0">
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
		<section id="content_section">
			<div class="marken-page">
				<div class="page_width_1480">
					<div class="marken-two-part ">
						<div class="marken-content-left">
							<div class="marken-heading">HP Toner Tinte Bander</div>
							<div class="marken-listing-content">
								<div class="marken-listing-block">
									<div class="marken-list-data">
										<?php for($i = 0; $i < 4; $i++){?>
										<div class="marken-list-col">
											<ul>
												<?php foreach ($manufacture_data[$i] as $item) { ?>
												<li><a href="<?php print($item['manf_name_params']); ?>"><?php print($item['manf_title']); ?></a></li>
												<?php } ?>
											</ul>
										</div>
										<?php } ?>
									</div>
								</div>
							</div>


						</div>

						<div class="marken-content-right" style="display: none;">
							<div class="marken-add-banner">
								<div class="marken-add-heading">
									<div><strong>GRTIS</strong> <span>for Sie</span></div>
								</div>
								<div class="marken-add-banner-inner">
									<img src="images/product_img1.jpg" alt="">
									<img src="images/register_logo.png" alt="">
									<img src="images/visa.jpg" alt="">
									<img src="images/pd_img1.jfif" alt="">
									<img src="images/pd_img2.jfif" alt="">
									<img src="images/brand/343640116.jpg" alt="">
									<img src="images/register_logo.png" alt="">
									<img src="images/visa.jpg" alt="">
									<img src="images/pd_img1.jfif" alt="">
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</section>
		<!--FOOTER_SECTION_START-->
		<div id="scroll_top">Zurück zum Seitenanfang</div>
		<?php include("includes/footer.php"); ?>
		<!--FOOTER_SECTION_END-->
	</div>

</body>
<script>
	$(document).ready(function() {

		// ✅ Smooth scroll on click
		$(".marken-nav-link a").on("click", function(e) {
			e.preventDefault();

			var target = $(this).attr("href");
			var navHeight = $(".marken-nav").outerHeight();

			$("html, body").animate({
				scrollTop: $(target).offset().top - navHeight
			}, 600);

			// Active class set on click
			$(".marken-nav-link a").removeClass("active");
			$(this).addClass("active");
		});

		// ✅ Active class update on scroll
		$(window).on("scroll", function() {
			var scrollPos = $(window).scrollTop();
			var navHeight = $(".marken-nav").outerHeight();

			$(".marken-listing-block").each(function() {
				var top = $(this).offset().top - navHeight - 10; // adjust
				var bottom = top + $(this).outerHeight();

				if (scrollPos >= top && scrollPos < bottom) {
					var id = $(this).attr("id");
					$(".marken-nav-link a").removeClass("active");
					$('.marken-nav-link a[href="#' + id + '"]').addClass("active");
				}
			});
		});

	});
</script>
<?php include("includes/bottom_js.php"); ?>

</html>