<?php
include("includes/php_includes_top.php");
//$page = 1;
?>
<!doctype html>
<html lang="de">

<head>
	<?php include("includes/html_header1.php"); ?>
</head>

<body>
	<div id="container" align="center">

		<!--LOCATION_POPUP_START-->
		<?php include("includes/popup.php"); ?>
		<!--LOCATION_POPUP_END-->

		<!--HEADER_SECTION_START-->
		<?php include("includes/navigation.php"); ?>
		<!--HEADER_SECTION_END-->

		<!--BANNER_SECTION_START-->
		<?php include("includes/banner.php"); ?>
		<!--BANNER_SECTION_END-->

		<!--CONTENT_SECTION_START-->
		<section id="content_section" style="min-height: 250px;">
			<div class="home_page">
				<div class="page_width_1480">
					<div class="hm_section_1" style="text-align: center;">
						<img loading="lazy" src="images/loading_128x128.gif" alt="Loading..." style="top: -50px;">
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
	<script defer>
		let contentLoaded = false; // Prevent multiple loads

		// Function to load PHP content
		function loadPHPContent() {
			if (contentLoaded) return; // Already loaded
			contentLoaded = true;

			requestIdleCallback(() => {

				fetch('https://www.wacker-buerocenter.de/includes/home_contents.php?d=<?php echo date("YmdHis"); ?>')
				.then(response => {
				if (!response.ok) throw new Error('Failed to fetch content');
					return response.text();
				})
				.then(dataSale => {
					document.getElementById('content_section').innerHTML = dataSale;
					// Wait for DOM to update, then initialize Slick
					setTimeout(() => {
						$(".gerenric_slider").slick({
							slidesToShow: 6,
							slidesToScroll: 1,
							autoplay: false,
							dots: false,
							autoplaySpeed: 2000,
							infinite: true,
							responsive: [

								{
									breakpoint: 1024,
									settings: {
										slidesToShow: 4,
										slidesToScroll: 1,
									}
								},
								{
									breakpoint: 650,
									settings: {
										slidesToShow: 3,
										slidesToScroll: 1
									}
								},
								{
									breakpoint: 480,
									settings: {
										slidesToShow: 2,
										slidesToScroll: 1
									}
								}
							]
						});
						$(".brand_slider").slick({
							slidesToShow: 10,
							slidesToScroll: 1,
							autoplay: false,
							dots: false,
							autoplaySpeed: 2000,
							infinite: true,
							responsive: [{
									breakpoint: 1200,
									settings: {
										slidesToShow: 6,
										slidesToScroll: 1
									}
								},
								{
									breakpoint: 1024,
									settings: {
										slidesToShow: 4,
										slidesToScroll: 1,
									}
								},
								{
									breakpoint: 650,
									settings: {
										slidesToShow: 3,
										slidesToScroll: 1
									}
								},
								{
									breakpoint: 480,
									settings: {
										slidesToShow: 2,
										slidesToScroll: 1
									}
								}
							]
						});
						$(".gerenric_slider_mostviewed").slick({
							slidesToShow: 10,
							slidesToScroll: 1,
							autoplay: false,
							dots: false,
							autoplaySpeed: 2000,
							infinite: true,
							responsive: [
								{
									breakpoint: 1024,
									settings: {
										slidesToShow: 4,
										slidesToScroll: 1,
									}
								},
								{
									breakpoint: 650,
									settings: {
										slidesToShow: 3,
										slidesToScroll: 1
									}
								},
								{
									breakpoint: 480,
									settings: {
										slidesToShow: 2,
										slidesToScroll: 1
									}
								}
							]
						});
					}, 200);
				})
				.catch(error => {
					console.error('Error loading PHP content:', error);
					document.getElementById('content_section').innerHTML = '<p style="color:red;">Failed to load content.</p>';
				});
			});
		}

		window.addEventListener('scroll', loadPHPContent, { once: true });
		window.addEventListener('click', loadPHPContent, { once: true });
		setTimeout(loadPHPContent, 3000);
	</script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
</body>
<style>
	.banner_slider .slick-slide:first-child {display: block !important;}
    #header_section .header_top, #navigation_section, #footer_section {background-color: <?php echo config_private_color_a; ?>;}
    #header_section .header_bottom {background-color: <?php echo config_private_color_b; ?>;}
    #header_section .header_bottom .header_search .search_icon, .gerenric_btn, #footer_section .subscribe_newsletter_btn {background-color: <?php echo config_btn_color; ?>;}
</style>
<script src="js/slick.js" defer></script>
<script type="text/javascript" defer>
	document.addEventListener("DOMContentLoaded", function() {
		$(".banner_slider").slick({
			dots: false,
			infinite: true,
			slidesToShow: 1,
			autoplay: true,
			autoplaySpeed: 10000,
			slidesToScroll: 1,
		});
	});
</script>
<!-- <link href="css/slick-theme.min.css" rel="stylesheet" type="text/css" /> -->
<link rel="preload" href="css/slick-theme.min.css" as="style" onload="this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="css/slick-theme.min.css"></noscript>
<?php include_once 'includes/bottom_js1.php'; ?>
</html>