<?php
include("includes/php_includes_top.php");
//$page = 1;
?>
<!doctype html>
<html lang="de">

<head>
	<link rel="canonical" href="<?php print($GLOBALS['siteURL']); ?>">
	<?php include("includes/html_header.php"); ?>
	<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css">
</head>
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
		<style>
			.product_category{padding: 0px 30px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;}
			.product_category_inner{padding: 15px; display: flex; flex-direction: column; gap: 10px; border-radius: 3px; background-color: #fff; box-shadow: 0 0 5px rgba(0, 0, 0, .05); border-radius: 20px; border: 1px solid rgba(0, 0, 0, .04);}
			.product_category_inner.pd_ctg_special_sale{border: 5px solid transparent; border-image: linear-gradient(to right, #ff0000bf, #ffc106d4); border-image-slice: 1;}
			.product_category_heading{font-size: 21px; color: #232f3e;font-weight: 700;}
			.icon_bg_red{background-color: #CC0F19;}
			.icon_bg_yellow{background-color: #FEC509;}
			.product_category_heading i{color: #fff; padding: 10px; border-radius: 5px;}
			.product_category_inner:hover {
			transform: translateY(-6px);
			box-shadow: 0 0 18px rgba(144, 255, 33, 0.4);
			border-color: #20ff20d1;
		}
		</style>
		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="home_page">
				<div class="page_width_1480">
					<div class="hm_section_1">
						<div class="product_category">
							<?php 
							$Query = "SELECT * FROM user_special_price WHERE user_id = '0' GROUP BY user_id";
							$rs = mysqli_query($GLOBALS['conn'], $Query);
							if(mysqli_num_rows($rs) > 0){
							?>
							<a href="verkaeufe-angebote" title="verkäufe-angebote" class="product_category_inner pd_ctg_special_sale">
								<div class="product_category_heading"><i class="fa fa-tag icon_bg_red"></i> SALE</div>
								<div class="product_category_image"><img src="images/homepage_category/sale.png" alt="" srcset=""></div>
								<div class="bottom_heading">
									<h2>SALE</h2>
								</div>
							</a>
							<?php
							}

							$Query = "SELECT group_id, cat_title_de AS cat_title, cat_params_de AS cat_params, cat_image, cat_icon, cat_icon_color FROM category WHERE parent_id = '0' AND cat_showhome = '1' ORDER BY group_id ASC";
							$rs = mysqli_query($GLOBALS['conn'], $Query);
							if(mysqli_num_rows($rs) > 0){
								while($row = mysqli_fetch_object($rs)){
							?>
							<a href="<?php print($GLOBALS['siteURL']."kategorie/".$row->cat_params); ?>" title="<?php print($row->cat_params); ?>" class="product_category_inner">
								<div class="product_category_heading"><i class="<?php print($row->cat_icon); ?>" style="background-color: <?php print($row->cat_icon_color); ?>;"></i> <?php print($row->cat_title); ?></div>
								<div class="product_category_image"><img src="<?php print($GLOBALS['siteURL']."files/category/".$row->cat_image); ?>" title="<?php print($row->cat_title); ?>" alt="<?php print($row->cat_title); ?>" srcset=""></div>
								<div class="bottom_heading">
									<h2><?php print($row->cat_title); ?></h2>
								</div>
							</a>
							<?php
								}
							}
							?>
						</div>
					</div>
					<div class="hm_section_2">
						<div class="gerenric_white_box">
							<div class="gerenric_product full_column">
								<h1 class="pd_heading">Meist verkaufte Produkte</h1>
								<div class="gerenric_slider">
									<?php
									$special_price = "";
									$Query = "SELECT oi.*, pro.pro_udx_seo_internetbezeichung, pro.pro_udx_seo_epag_title, pg.pg_mime_source_url, pro.pro_custom_add, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount,  pbp.pbp_price_amount AS pbp_price_without_tax, (pbp.pbp_special_price_amount + (pbp.pbp_special_price_amount * pbp.pbp_tax)) AS pbp_special_price_amount, pbp.pbp_special_price_amount AS pbp_special_price_without_tax, pbp.pbp_tax, COUNT(oi.supplier_id) AS sales_count FROM order_items AS oi LEFT OUTER JOIN products AS pro ON pro.supplier_id = oi.supplier_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = oi.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = oi.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = oi.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_order ASC LIMIT 1) WHERE oi.supplier_id != '' GROUP BY oi.supplier_id ORDER BY sales_count DESC LIMIT 0,12";
									//print($Query);//die();
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										while ($rw = mysqli_fetch_object($rs)) {
											$special_price = user_special_price("supplier_id", $rw->supplier_id, 0, 1);
											//$pbp_price_amount = TotalRecords("pbp_price_amount", "products_bundle_price", "WHERE ord_id = '".$rw->ord_id."'");
									?>
											<div>
												<div class="pd_card">
													<div class="pd_image">
														<a tabindex="-1" href="<?php print(product_detail_url($rw->supplier_id)); ?>" title="<?php print($rw->pro_udx_seo_internetbezeichung); ?>">
															<img loading="lazy" src="<?php print(get_image_link(427, $rw->pg_mime_source_url)); ?>" alt="<?php print($rw->pro_udx_seo_internetbezeichung); ?>">
															<?php
															if ($rw->sales_count > 45) {
																print('<span class="pd_tag">Best Seller</span>');
															}
															?>
														</a>
													</div>
													<div class="pd_detail">
														<h5><a tabindex="-1" href="<?php print(product_detail_url($rw->supplier_id)); ?>" title="<?php print($rw->pro_udx_seo_internetbezeichung); ?>"> <?php print($rw->pro_udx_seo_epag_title); ?> </a></h5>
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
														<?php if (!empty($special_price)) { ?>
															<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format($rw->pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $rw->pbp_price_without_tax, $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
															<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format($rw->pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $rw->pbp_price_amount, $special_price['usp_discounted_value'], $rw->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
														<?php } else { ?>
															<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format($rw->pbp_price_without_tax)); ?>€</div>
															<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format($rw->pbp_price_amount)); ?>€</div>
														<?php } ?>
													</div>
												</div>
											</div>
									<?php
										}
									}
									?>
								</div>
								<!--<div class="gerenric_show_All"><a href="javascript:void(0)">Mehr anzeigen</a></div>-->
							</div>
						</div>
						<div class="gerenric_white_box">
							<div class="gerenric_product full_column">
								<h2>Schulranzen</h2>
								<div class="gerenric_slider">
									<?php
									$special_price = "";
									$Query = "SELECT * FROM vu_category_map AS cm WHERE cm.pro_status = '1' AND cm.cat_id_level_one = '20' AND cm.cm_type = '20' ORDER BY  RAND() LIMIT 0,12";
									//print($Query2);die();
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										while ($rw = mysqli_fetch_object($rs)) {
									?>
											<div>
												<div class="pd_card">
													<div class="pd_image"><a tabindex="-1" href="<?php print(product_detail_url($rw->supplier_id)); ?>" title="<?php print($rw->pro_udx_seo_internetbezeichung); ?>"><img loading="lazy" src="<?php print(get_image_link(427, $rw->pg_mime_source_url)); ?>" alt="<?php print($rw->pro_udx_seo_internetbezeichung); ?>"></a></div>
													<div class="pd_detail">
														<h5><a tabindex="-1" href="<?php print(product_detail_url($rw->supplier_id)); ?>" title="<?php print($rw->pro_udx_seo_internetbezeichung); ?>"> <?php print($rw->pro_udx_seo_epag_title); ?> </a></h5>
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
														<?php if (!empty($special_price)) { ?>
															<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format($rw->pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $rw->pbp_price_without_tax, $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
															<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format($rw->pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $rw->pbp_price_amount, $special_price['usp_discounted_value'], $rw->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
														<?php } else { ?>
															<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format($rw->pbp_price_without_tax)); ?>€</div>
															<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format($rw->pbp_price_amount)); ?>€</div>
														<?php } ?>
													</div>
												</div>
											</div>
									<?php
										}
									}
									?>
								</div>
								<div class="gerenric_show_All"><a tabindex="-1" href="kategorie/schulranzen" title="Schulranzen">Mehr anzeigen</a></div>
							</div>
						</div>
						<?php
						$Query1 = "SELECT cat_id, group_id, parent_id, cat_title_de AS cat_title FROM category WHERE cat_status = '1' AND cat_showhome_feature = '1'";
						$rs1 = mysqli_query($GLOBALS['conn'], $Query1);
						if (mysqli_num_rows($rs1) > 0) {
							while ($row1 = mysqli_fetch_object($rs1)) {
								$special_price = user_special_price("level_one", $row1->group_id);

						?>
								<div class="gerenric_white_box">
									<div class="gerenric_product full_column">
										<h2>Beliebte Produkte in <?php print($row1->cat_title); ?></h2>
										<div class="gerenric_slider">
											<?php
											//$Query2 = "SELECT cm.cat_id, cm.sub_group_ids, cm.supplier_id, pro.pro_udx_seo_epag_title, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount,  pbp.pbp_price_amount AS pbp_price_without_tax,  pg.pg_mime_source_url FROM category_map AS cm LEFT OUTER JOIN products AS pro ON pro.supplier_id = cm.supplier_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = cm.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = cm.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1'  WHERE FIND_IN_SET(".$row1->group_id.", cm.sub_group_ids) ORDER BY  RAND() LIMIT 0,12";
											$Query2 = "SELECT * FROM vu_category_map AS cm  WHERE cm.cat_id_level_one = '" . $row1->group_id . "' ORDER BY  RAND() LIMIT 0,12";
											//print($Query2);die();
											$rs2 = mysqli_query($GLOBALS['conn'], $Query2);
											if (mysqli_num_rows($rs2) > 0) {
												while ($row2 = mysqli_fetch_object($rs2)) {
											?>
													<div>
														<div class="pd_card">
															<div class="pd_image"><a tabindex="-1" href="<?php print(product_detail_url($row2->supplier_id)); ?>" title="<?php print($row2->pro_udx_seo_internetbezeichung); ?>"><img loading="lazy" src="<?php print(get_image_link(427, $row2->pg_mime_source_url)); ?>" alt="<?php print($row2->pro_udx_seo_internetbezeichung); ?>"></a></div>
															<div class="pd_detail">
																<h5><a tabindex="-1" href="<?php print(product_detail_url($row2->supplier_id)); ?>" title="<?php print($row2->pro_udx_seo_internetbezeichung); ?>"> <?php print($row2->pro_udx_seo_epag_title); ?> </a></h5>
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
																<?php if (!empty($special_price)) { ?>
																	<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format(((config_site_special_price > 0 && $row2->pbp_special_price_without_tax > 0) ? $row2->pbp_special_price_without_tax : $row2->pbp_price_without_tax)) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $row2->pbp_special_price_without_tax > 0) ? $row2->pbp_special_price_without_tax : $row2->pbp_price_without_tax), $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
																	<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format(((config_site_special_price > 0 && $row2->pbp_special_price_amount > 0) ? $row2->pbp_special_price_amount : $row2->pbp_price_amount)) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $row2->pbp_special_price_amount > 0) ? $row2->pbp_special_price_amount : $row2->pbp_price_amount), $special_price['usp_discounted_value'], $row2->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
																<?php } else { ?>
																	<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format(((config_site_special_price > 0 && $row2->pbp_special_price_without_tax > 0) ? $row2->pbp_special_price_without_tax : $row2->pbp_price_without_tax))); ?>€</div>
																	<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format(((config_site_special_price > 0 && $row2->pbp_special_price_amount > 0) ? $row2->pbp_special_price_amount : $row2->pbp_price_amount))); ?>€</div>
																<?php } ?>
															</div>
														</div>
													</div>
											<?php
												}
											}
											?>
										</div>
									</div>
									<div class="gerenric_show_All"><a tabindex="-1" href="kategorie/<?php print(returnName("cat_params_de AS cat_params", "category", "group_id", $row1->group_id)); ?>" title="<?php print($row1->cat_title); ?>">Mehr anzeigen</a></div>
								</div>
						<?php
							}
						}
						?>


					</div>
				</div>
				<div class="hm_section_3 margin_top_30">
					<div class="gerenric_white_box">
						<div class="gerenric_product full_column mostviewed padding_left_right_10">
							<h2>Baumarkt</h2>
							<div class="gerenric_slider_mostviewed">
								<?php
								$special_price = "";
								$Query = "SELECT * FROM vu_category_map AS cm  WHERE cm.cat_id = '91700' AND cm.cm_type = '0' ORDER BY  RAND() LIMIT 0,12";
								//print($Query);die();
								$rs = mysqli_query($GLOBALS['conn'], $Query);
								if (mysqli_num_rows($rs) > 0) {
									while ($rw = mysqli_fetch_object($rs)) {
								?>
										<div>
											<div class="pd_card txt_align_left">
												<div class="pd_image"><a tabindex="-1" href="<?php print(product_detail_url($rw->supplier_id)); ?>" title="<?php print($rw->pro_udx_seo_internetbezeichung); ?>"><img loading="lazy" src="<?php print(get_image_link(427, $rw->pg_mime_source_url)); ?>" alt="<?php print($rw->pro_udx_seo_internetbezeichung); ?>"></a></div>
												<div class="pd_detail">
													<h5><a tabindex="-1" href="<?php print(product_detail_url($rw->supplier_id)); ?>" title="<?php print($rw->pro_udx_seo_internetbezeichung); ?>"> <?php print($rw->pro_udx_seo_epag_title); ?> </a></h5>
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
													<?php if (!empty($special_price)) { ?>
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format(((config_site_special_price > 0 && $rw->pbp_special_price_without_tax > 0) ? $rw->pbp_special_price_without_tax : $rw->pbp_price_without_tax)) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $rw->pbp_special_price_without_tax > 0) ? $rw->pbp_special_price_without_tax : $rw->pbp_price_without_tax), $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format(((config_site_special_price > 0 && $rw->pbp_special_price_amount > 0) ? $rw->pbp_special_price_amount : $rw->pbp_price_amount)) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $rw->pbp_special_price_amount > 0) ? $rw->pbp_special_price_amount : $rw->pbp_price_amount), $special_price['usp_discounted_value'], $rw->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
													<?php } else { ?>
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format(((config_site_special_price > 0 && $rw->pbp_special_price_without_tax > 0) ? $rw->pbp_special_price_without_tax : $rw->pbp_price_without_tax))); ?>€</div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format(((config_site_special_price > 0 && $rw->pbp_special_price_amount > 0) ? $rw->pbp_special_price_amount : $rw->pbp_price_amount))); ?>€</div>
													<?php } ?>
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
				<div class="hm_section_3">
					<div class="gerenric_white_box">
						<div class="gerenric_product full_column mostviewed padding_left_right_10">
							<div class="gerenric_slider_mostviewed">
								<?php
								$special_price = "";
								$Query = "SELECT * FROM vu_category_map AS cm  WHERE cm.cat_id = '91700' AND cm.cm_type = '0' ORDER BY  RAND() LIMIT 0,12";
								//print($Query);die();
								$rs = mysqli_query($GLOBALS['conn'], $Query);
								if (mysqli_num_rows($rs) > 0) {
									while ($rw = mysqli_fetch_object($rs)) {
								?>
										<div>
											<div class="pd_card txt_align_left">
												<div class="pd_image"><a tabindex="-1" href="<?php print(product_detail_url($rw->supplier_id)); ?>"><img loading="lazy" src="<?php print(get_image_link(427, $rw->pg_mime_source_url)); ?>" alt=""></a></div>
												<div class="pd_detail">
													<h5><a tabindex="-1" href="<?php print(product_detail_url($rw->supplier_id)); ?>"> <?php print($rw->pro_udx_seo_epag_title); ?> </a></h5>
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
													<?php if (!empty($special_price)) { ?>
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format(((config_site_special_price > 0 && $rw->pbp_special_price_without_tax > 0) ? $rw->pbp_special_price_without_tax : $rw->pbp_price_without_tax)) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $rw->pbp_special_price_without_tax > 0) ? $rw->pbp_special_price_without_tax : $rw->pbp_price_without_tax), $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format(((config_site_special_price > 0 && $rw->pbp_special_price_amount > 0) ? $rw->pbp_special_price_amount : $rw->pbp_price_amount)) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $rw->pbp_special_price_amount > 0) ? $rw->pbp_special_price_amount : $rw->pbp_price_amount), $special_price['usp_discounted_value'], $rw->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
													<?php } else { ?>
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format(((config_site_special_price > 0 && $rw->pbp_special_price_without_tax > 0) ? $rw->pbp_special_price_without_tax : $rw->pbp_price_without_tax))); ?>€</div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format(((config_site_special_price > 0 && $rw->pbp_special_price_amount > 0) ? $rw->pbp_special_price_amount : $rw->pbp_price_amount))); ?>€</div>
													<?php } ?>
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
					<div class="gerenric_white_box">
						<div class="hm_register">
							<div class="full_width txt_align_center"><a tabindex="-1" href="anmelden" title="Anmelden">
									<div class="gerenric_btn">Anmelden</div>
								</a></div>
							<p>Neues Konto? <a tabindex="-1" href="registrierung" title="Erstellen Sie hier">Erstellen Sie hier.</a></p>
						</div>
					</div>
				</div>
				<?php
				$Query = "SELECT * FROM manufacture WHERE manf_status = '1' AND manf_showhome = '1'";
				$rs = mysqli_query($GLOBALS['conn'], $Query);
				if (mysqli_num_rows($rs) > 0) {
				?>
					<div class="hm_section_3">
						<div class="gerenric_white_box">
							<h2 class="txt_align_center">Top Hersteller & Marken</h2>
							<div class="hm_brand_logo">
								<div class="brand_slider">
									<?php while ($row = mysqli_fetch_object($rs)) {
										$brand_image_href = "files/no_img_1.jpg";
										if (!empty($row->manf_file)) {
											$brand_image_href = $GLOBALS['siteURL'] . "files/manufacture/" . $row->manf_file;
										}
									?>
										<div>
											<div class="brand_col"><a tabindex="-1" href="<?php print($GLOBALS['siteURL'] . "marken/" . $row->manf_name_params) ?>" title="<?php print($row->manf_name) ?>">
													<div class="brand_item"><img loading="lazy" src="<?php print($brand_image_href) ?>" alt="<?php print($row->manf_name) ?>">
													</div>
												</a></div>
										</div>
									<?php } ?>
								</div>
							</div>
							<div class="brand_btn"><a tabindex="-1" href="marken" title="Alle anzeigen">
									<div class="gerenric_btn">Alle anzeigen</div>
								</a></div>
						</div>
					</div>
				<?php } ?>
			</div>
		</section>
		<!--CONTENT_SECTION_END-->

		<!--FOOTER_SECTION_START-->
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
		autoplay: true,
		autoplaySpeed: 10000,
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
</script>
<?php include("includes/bottom_js.php"); ?>

</html>