<?php
include("includes/php_includes_top.php");

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

		<!--BREADCRUMB_SECTION_START-->
		<div class="gerenric_breadcrumb">
			<div class="page_width_1480">
				<div class="breadcrumb_inner">
					<ul>
						<li><a href="personal_data.php">My personal data</a></li>
						<li><a href="javascript:void(0)">My Orders</a></li>
					</ul>
				</div>
			</div>
		</div>
		<!--BREADCRUMB_SECTION_END-->

		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="my_order_page gerenric_padding">
				<div class="page_width_1480">
					<h1>My Orders</h1>
					<?php
					$Query = "SELECT oi.*, ord.user_id, ord.ord_datetime, ord.ord_udate, di.dinfo_countries_id, c.countries_name, di.dinfo_fname, di.dinfo_house_no, di.dinfo_street, di.dinfo_phone, di.dinfo_usa_zipcode, di.dinfo_additional_info, pro.pro_description_short, pro.pro_udx_seo_internetbezeichung, pg.pg_mime_source_url FROM order_items AS oi LEFT OUTER JOIN orders AS ord ON ord.ord_id = oi.ord_id LEFT OUTER JOIN delivery_info AS di ON di.ord_id = oi.ord_id LEFT OUTER JOIN countries AS c ON c.countries_id = di.dinfo_countries_id LEFT OUTER JOIN products AS pro ON pro.supplier_id = oi.supplier_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = oi.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' WHERE ord.user_id = '" . $_SESSION['UID'] . "' ORDER BY ord.ord_datetime DESC";
					$rs = mysqli_query($GLOBALS['conn'], $Query);
					if (mysqli_num_rows($rs) > 0) {
						while ($row = mysqli_fetch_object($rs)) {
					?>
							<div class="my_order_box">
								<div class="order_place_bar">
									<div class="place_col">
										<div class="place_div">Order placed</div>
										<div class="place_div"><?php print(date('D F j, Y', strtotime($row->ord_datetime))); ?></div>
									</div>
									<div class="place_col">
										<div class="place_div">Article Price</div>
										<div class="place_div">
											<?php
											if ($row->oi_discount_value > 0) {
												print("<del class = 'orignal_price'>" . number_format($row->pbp_price_amount * (1 + config_gst), "2", ",", "") . "€</del><br> <span class = 'pd_prise_discount'>" . str_replace(".", ",", $row->oi_amount) . "€ " . $row->oi_discount_value . (($row->oi_discount_type > 0) ? '€' : '%') . "</span>");
											} else {
												print(str_replace(".", ",", $row->oi_amount) . "€");
											}
											?>
										</div>
									</div>
									<div class="place_col">
										<div class="place_div">Send to</div>
										<div class="place_div">
											<div class="placeser_name"> <?php print($row->dinfo_fname); ?> <i class="fa fa-caret-down"></i>
												<div class="placeser_info">
													<ul>
													<?php if(!empty($row->dinfo_additional_info)){ ?>
														<li><span> <?php print($row->dinfo_additional_info); ?> </span></li>
														<?php } ?>
														<li> <?php print($row->dinfo_house_no); ?> </li>
														<li> <?php print($row->dinfo_street); ?> </li>
														<li> <?php print($row->dinfo_phone); ?> </li>
														<li> <?php print($row->dinfo_usa_zipcode); ?> </li>
														<li> <?php print($row->countries_name); ?> </li>
													</ul>
												</div>
											</div>
										</div>
									</div>
									<div class="place_col">
										<div class="place_div">Quantity</div>
										<div class="place_div"> <?php print($row->oi_qty); ?> </div>
									</div>
									<div class="place_col">
										<div class="place_div">Order number - <?php print($row->ord_id); ?></div>
									</div>
								</div>
								<div class="my_order_box_inner">
									<div class="order_image"><img src="<?php print(get_image_link(160, $row->pg_mime_source_url)); ?>" alt=""></div>
									<div class="order_detail">
										<h2><?php print($row->pro_udx_seo_internetbezeichung); ?></h2>
										<h2 class="black_text"><?php print($row->pro_description_short); ?></h2>
										<div class="order_button">
											<a href="product_detail.php?supplier_id=<?php print($row->supplier_id); ?>">
												<div class="gerenric_btn">View Your Article</div>
											</a>
											<a href="javascript:void(0)">
												<div class="gerenric_btn gray_btn">Buy Again</div>
											</a>
										</div>
										<div class="return_date">Return window closes on Dec 22, 2024</div>
										<div class="order_date">Order sent on Oct 21, 2024</div>
									</div>
								</div>
							</div>
					<?php
						}
					}
					?>
				</div>
			</div>
		</section>
		<!--CONTENT_SECTION_END-->

		<!--FOOTER_SECTION_START-->
		<div id="scroll_top">Back to top</div>
		<?php include("includes/footer.php"); ?>
		<!--FOOTER_SECTION_END-->

	</div>

</body>
<?php include("includes/bottom_js.php"); ?>

</html>