<?php

include("includes/php_includes_top.php");
$Query = "SELECT pro.*, pbp.pbp_id, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pbp.pbp_price_amount AS pbp_price_without_tax, pg.pg_mime_source, cm.cat_id AS cat_id_three, cm.sub_group_ids, c.cat_title_de AS cat_title_three FROM products AS pro LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = pro.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' LEFT OUTER JOIN category_map AS cm ON cm.supplier_id = pro.supplier_id LEFT OUTER JOIN category AS c ON c.group_id = cm.cat_id WHERE pro.supplier_id = '" . $_REQUEST['supplier_id'] . "'";
//print($Query);die();
$rs = mysqli_query($GLOBALS['conn'], $Query);
if (mysqli_num_rows($rs) > 0) {
	$row = mysqli_fetch_object($rs);

	$pro_id = $row->pro_id;
	$supplier_id = $row->supplier_id;
	$pro_udx_seo_internetbezeichung = $row->pro_udx_seo_internetbezeichung;
	$pro_description_short = $row->pro_description_short;
	$pro_description_long = $row->pro_description_long;
	$pro_ean = $row->pro_ean;
	$pro_buyer_id = $row->pro_buyer_id;
	$pro_manufacture_aid = $row->pro_manufacture_aid;
	$pro_delivery_time = $row->pro_delivery_time;
	$pro_order_unit = $row->pro_order_unit;
	$pro_count_unit = $row->pro_count_unit;
	$pro_no_cu_per_ou = $row->pro_no_cu_per_ou;
	$pro_price_quantity = $row->pro_price_quantity;
	$pro_quantity_min = $row->pro_quantity_min;
	$pro_quantity_interval = $row->pro_quantity_interval;
	$pbp_price_amount = $row->pbp_price_amount;
	$pbp_price_without_tax = $row->pbp_price_without_tax;
	$pbp_id = $row->pbp_id;
	$ci_amount = $pbp_price_without_tax;

	$pg_mime_source = $row->pg_mime_source;
	$sub_group_ids = explode(",", $row->sub_group_ids);
	//print_r($sub_group_ids);
	$cat_id_one = $sub_group_ids[1];
	$cat_title_one = returnName("cat_title_de AS cat_title", "category", "group_id", $cat_id_one);
	$cat_id_two = $sub_group_ids[0];
	$cat_title_two = returnName("cat_title_de AS cat_title", "category", "group_id", $cat_id_two);
	$cat_id_three = $row->cat_id_three;
	$cat_title_three = $row->cat_title_three;
}

if (isset($_SESSION["UID"]) && $_SESSION["UID"] > 0) {
	$special_price = user_special_price("supplier_id", $supplier_id);

	if (!$special_price) {
		$special_price = user_special_price("level_two", $cat_id_two);
	}

	if (!$special_price) {
		$special_price = user_special_price("level_one", $cat_id_one);
	}
}
//print_r($special_price);
?>
<!doctype html>
<html lang="de">

<head>
	<?php include("includes/html_header.php"); ?>
</head>

<body style="background-color: #fff;">
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
						<li><a href="product_category.php?level_one=<?php print($cat_id_one); ?>"> <?php print($cat_title_one); ?> </a></li>
						<li><a href="products.php?level_two=<?php print($cat_id_two); ?>"> <?php print($cat_title_two); ?> </a></li>
						<li><a href="products.php?level_three=<?php print($cat_id_three); ?>"> <?php print($cat_title_three); ?> </a></li>
					</ul>
				</div>
			</div>
		</div>
		<!--BREADCRUMB_SECTION_END-->

		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="product_detail_page gerenric_padding">
				<div class="page_width_1480">
					<div class="product_detail_section1">
						<div class="product_left">
							<div class="product_main_image">
								<article>
									<div class="simpleLens-gallery-container" id="demo-1" align="center">
										<div class="large_image">
											<div class="simpleLens-container">
												<div class="simpleLens-big-image-container"> <a class="simpleLens-lens-image" data-lens-image="getftpimage.php?img=<?php print($pg_mime_source); ?>"> <img src="getftpimage.php?img=<?php print($pg_mime_source); ?>" class="simpleLens-big-image"> </a> </div>
											</div>
										</div>
										<div class="thum_images">
											<div class="simpleLens-thumbnails-container">
												<?php
												$Query = "SELECT pg_mime_source FROM `products_gallery` WHERE pro_id = '" . $pro_id . "' AND supplier_id = '" . $_REQUEST['supplier_id'] . "' AND pg_mime_purpose != 'data_sheet' ORDER BY CASE WHEN pg_mime_purpose = 'normal' THEN 1 ELSE 2 END";
												$rs = mysqli_query($GLOBALS['conn'], $Query);
												if (mysqli_num_rows($rs) > 0) {
													while ($row = mysqli_fetch_object($rs)) {
												?>
														<a href="javascript:voild(0)" class="simpleLens-thumbnail-wrapper" data-lens-image="getftpimage.php?img=<?php print($row->pg_mime_source); ?>" data-big-image="getftpimage.php?img=<?php print($row->pg_mime_source); ?>"> <img src="getftpimage.php?img=<?php print($row->pg_mime_source); ?>"> </a>
												<?php
													}
												}
												?>
											</div>
										</div>

										<div class="clearfix"></div>
									</div>
								</article>
							</div>
						</div>
						<div class="product_right">
							<div class="product_col1">
								<h1> <?php print($pro_udx_seo_internetbezeichung); ?> </h1>
								<h4> <?php print($pro_description_short); ?> </h4>
								<ul>
									<li>Bestellnummer: <?php print($supplier_id); ?> </li>
									<li>Herstellernummer: <?php print($pro_manufacture_aid); ?></li>
									<li>GTIN: <?php print($pro_ean); ?> </li>
								</ul>
								<div class="product_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print(str_replace(".", ",", $pbp_price_without_tax)); ?> € </div>
								<div class="product_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print(str_replace(".", ",", $pbp_price_amount)); ?> € <span>Each ST 1/ incl. VAT</span> </div>
								<ul class="product_type">
									<?php
									$Query = "SELECT pf_fname, pf_fvalue FROM `products_feature` WHERE pro_id = '" . $pro_id . "' AND supplier_id = '" . $_REQUEST['supplier_id'] . "' ORDER BY pf_forder ASC";
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										while ($row = mysqli_fetch_object($rs)) {
									?>
											<li>
												<div class="product_label"><?php print($row->pf_fname); ?>:</div>
												<div class="product_value"><?php print($row->pf_fvalue); ?></div>
											</li>
									<?php
										}
									}
									?>
								</ul>
								<div class="product_info">
									<p> <?php print($pro_description_long); ?> </p>
								</div>
							</div>
							<div class="product_col2">
								<div class="sticky">
									<?php
									$Query = "SELECT pbp_lower_bound, (pbp_price_amount + (pbp_price_amount * pbp_tax)) AS pbp_price_amount,  pbp_price_amount AS pbp_price_without_tax FROM `products_bundle_price` WHERE pro_id = '" . $pro_id . "' AND supplier_id = '" . $_REQUEST['supplier_id'] . "' ORDER BY pbp_lower_bound ASC";
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										while ($row = mysqli_fetch_object($rs)) {
									?>
											<div class="piece_prise price_without_tex" <?php print($price_without_tex_display); ?>>From <?php print($row->pbp_lower_bound); ?> piece <br><span><?php print(str_replace(".", ",", $row->pbp_price_without_tax)); ?>€</span></div>
											<div class="piece_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>>From <?php print($row->pbp_lower_bound); ?> piece <br><span><?php print(str_replace(".", ",", $row->pbp_price_amount)); ?>€</span></div>
									<?php
										}
									}
									?>
									<div class="product_vat">VAT included</div>
									<?php
									$quantity_lenght = 0;
									$Query = "SELECT * FROM products_quantity WHERE supplier_id = '" . dbStr(trim($supplier_id)) . "'";
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										$row = mysqli_fetch_object($rs);
										$pq_quantity = $row->pq_quantity;
										$pq_upcomming_quantity = $row->pq_upcomming_quantity;
										$pq_status = $row->pq_status;
										if ($pq_quantity == 0 && $pq_status == 'true') {
											$quantity_lenght = $pq_upcomming_quantity;
											print('<div class="product_order_title"> ' . $pq_upcomming_quantity . ' Stück bestellt</div>');
										} elseif ($pq_quantity > 0 && $pq_status == 'false') {
											$quantity_lenght = $pq_quantity + $pq_upcomming_quantity;
											if ($quantity_lenght > 500) {
												$quantity_lenght = 500;
											}
											print('<div class="product_order_title green"> ' . $pq_quantity . ' Stück sofort verfügbar</div>');
										}
									}
									?>
									<!--<div class="product_order_title"> 100 pieces ordered</div>-->
									<div class="product_order_row">
										<div class="product_order_row_inner">
											<div class="order_text">Quantity:</div>
											<div class="order_select">
												<select class="order_select_input" id="ci_qty_<?php print($pro_id); ?>" name="ci_qty">
													<?php for ($i = 1; $i <= $quantity_lenght; $i++) { ?>
														<option value="<?php print($i); ?>"> <?php print($i); ?> </option>
													<?php } ?>
												</select>
											</div>
										</div>
									</div>
									<div class="order_btn">
										<input type="hidden" id="pro_id_<?php print($pro_id); ?>" name="pro_id" value="<?php print($pro_id); ?>">
										<input type="hidden" id="supplier_id_<?php print($pro_id); ?>" name="supplier_id" value="<?php print($supplier_id); ?>">
										<a class="add_to_card" href="javascript:void(0)" data-id="<?php print($pro_id); ?>">
											<div class="gerenric_btn">Add to Cart</div>
										</a>
									</div>
									<div class="order_btn"><a href="javascript:void(0)">
											<div class="gerenric_btn">Buy Now</div>
										</a></div>
									<div class="product_shippment">
										<div class="shippment_text"><span>Shipment</span> Wacker 24</div>
										<div class="shippment_text"><a href="javascript:void(0)"><i class="fa fa-map-marker" aria-hidden="true"></i>
												<div class="location_text location_trigger">Update Delivery to Location</div>
											</a></div>
										<div class="shippment_btn"><a href="javascript:void(0)">
												<div class="gerenric_btn">In the shopping lists</div>
											</a></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="product_detail_section2">
						<div class="gerenric_white_box gray_bg">
							<div class="gerenric_product full_column">
								<h2>Similar products</h2>
								<div class="gerenric_slider">
									<?php
									$Query = "SELECT cm.cat_id, cm.supplier_id, pro.pro_description_short, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount,  pbp.pbp_price_amount AS pbp_price_without_tax,  pg.pg_mime_source FROM category_map AS cm LEFT OUTER JOIN products AS pro ON pro.supplier_id = cm.supplier_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = cm.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = cm.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1'  WHERE cat_id = '" . $cat_id_three . "' ORDER BY  RAND() LIMIT 0,12";
									//print($Query);die();
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										while ($row = mysqli_fetch_object($rs)) {
									?>
											<div>
												<div class="pd_card">
													<div class="pd_image"><a href="product_detail.php?supplier_id=<?php print($row->supplier_id); ?>"><img src="getftpimage.php?img=<?php print($row->pg_mime_source); ?>" alt=""></a></div>
													<div class="pd_detail">
														<h5><a href="product_detail.php?supplier_id=<?php print($row->supplier_id); ?>"> <?php print($row->pro_description_short); ?> </a></h5>
														<div class="pd_rating">
															<ul>
																<li>
																	<div class="fa fa-star"></div>
																	<div class="fa fa-star"></div>
																	<div class="fa fa-star"></div>
																	<div class="fa fa-star"></div>
																	<div class="fa fa-star"></div>
																</li>
															</ul>
														</div>
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(str_replace(".", ",", $row->pbp_price_without_tax)); ?> €</div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(str_replace(".", ",", $row->pbp_price_amount)); ?> €</div>
													</div>
												</div>
											</div>
									<?php
										}
									}
									?>
								</div>
								<div class="gerenric_show_All"><a href="javascript:void(0)">Show More</a></div>
							</div>
						</div>
					</div>
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
<script src="js/slick.js"></script>
<script type="text/javascript">
	$(".banner_slider").slick({
		dots: false,
		infinite: true,
		slidesToShow: 1,
		autoplay: false,
		autoplaySpeed: 3000,
		slidesToScroll: 1,
	});
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
</script>
<script>
	$(".show-more").click(function() {
		if ($(".category_show").hasClass("category_show_height")) {
			$(this).text("(Show Less)");
		} else {
			$(this).text("(Show More)");
		}

		$(".category_show").toggleClass("category_show_height");
	});
</script>
<?php include("includes/bottom_js.php"); ?>

</html>