<?php
include("includes/php_includes_top.php");
//$page = 1;
?>
<!doctype html>
<html lang="de">

<head>
	<link rel="canonical" href="<?php print($GLOBALS['siteURL']); ?>">
	<?php include("includes/html_header.php"); ?>
	<style>
		img[data-src] { opacity: 0; }
		.gerenric_slider:not(.slick-initialized) > div:nth-child(n+7),
		.gerenric_slider_mostviewed:not(.slick-initialized) > div:nth-child(n+11),
		.brand_slider:not(.slick-initialized) > div:nth-child(n+11) { display: none; }
		@media (max-width: 1024px) {
			.gerenric_slider:not(.slick-initialized) > div:nth-child(n+5),
			.gerenric_slider_mostviewed:not(.slick-initialized) > div:nth-child(n+5),
			.brand_slider:not(.slick-initialized) > div:nth-child(n+5) { display: none; }
		}
		@media (max-width: 650px) {
			.gerenric_slider:not(.slick-initialized) > div:nth-child(n+4),
			.gerenric_slider_mostviewed:not(.slick-initialized) > div:nth-child(n+4),
			.brand_slider:not(.slick-initialized) > div:nth-child(n+4) { display: none; }
		}
		@media (max-width: 480px) {
			.gerenric_slider:not(.slick-initialized) > div:nth-child(n+3),
			.gerenric_slider_mostviewed:not(.slick-initialized) > div:nth-child(n+3),
			.brand_slider:not(.slick-initialized) > div:nth-child(n+3) { display: none; }
		}
		#banner_section .banner_inner { overflow: hidden; }
		#banner_section .banner_slider > div { position: relative; }
		#banner_section .banner_slider:not(.slick-initialized) > div:not(:first-child) {
			display: none !important;
		}
		.home_page .hm_section_1 { z-index: 10; }
		.gerenric_slider:not(.slick-initialized),
		.gerenric_slider_mostviewed:not(.slick-initialized),
		.brand_slider:not(.slick-initialized) {
			display: flex;
			overflow: hidden;
		}
		.gerenric_slider:not(.slick-initialized) > div {
			flex: 0 0 16.6666%;
			max-width: 16.6666%;
		}
		.gerenric_slider_mostviewed:not(.slick-initialized) > div,
		.brand_slider:not(.slick-initialized) > div {
			flex: 0 0 10%;
			max-width: 10%;
		}
		@media (max-width: 1024px) {
			.gerenric_slider:not(.slick-initialized) > div,
			.gerenric_slider_mostviewed:not(.slick-initialized) > div,
			.brand_slider:not(.slick-initialized) > div {
				flex: 0 0 25%;
				max-width: 25%;
			}
		}
		@media (max-width: 650px) {
			.gerenric_slider:not(.slick-initialized) > div,
			.gerenric_slider_mostviewed:not(.slick-initialized) > div,
			.brand_slider:not(.slick-initialized) > div {
				flex: 0 0 33.3333%;
				max-width: 33.3333%;
			}
		}
		@media (max-width: 480px) {
			.gerenric_slider:not(.slick-initialized) > div,
			.gerenric_slider_mostviewed:not(.slick-initialized) > div,
			.brand_slider:not(.slick-initialized) > div {
				flex: 0 0 50%;
				max-width: 50%;
			}
		}
	</style>
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
		<section id="content_section">
			<div class="home_page">
				<div class="page_width_1480">
					<div class="hm_section_1">
						<div class="gerenric_product_category">
							<?php
							$cat_block_index = 0;
							$Query1 = "SELECT usp.*, pro.pro_status FROM user_special_price AS usp LEFT OUTER JOIN products AS pro ON pro.supplier_id = usp.supplier_id WHERE usp.user_id = 0 AND usp.usp_status = '1' ORDER BY CASE WHEN usp.supplier_id IS NOT NULL AND pro.pro_status = '1' THEN 1 WHEN usp.level_two_id IS NOT NULL AND usp.supplier_id = 0 THEN 2 ELSE 3 END, RAND() LIMIT 1";
							$rs1 = mysqli_query($GLOBALS['conn'], $Query1);
							if (mysqli_num_rows($rs1) > 0) {
								$row1 = mysqli_fetch_object($rs1);
								$cat_block_index++;
							?>
									<div class="pd_ctg_block pd_ctg_special_sale">
										<a href="angebote-aktionen" title="Angebote & Aktionen" class="pd_ctg_heading">SALE <i class="fa fa-tag" ></i></a>
										<div class="pd_ctg_row">
											<?php
											$whereclause = "WHERE pro.pro_status = '1'";
											if ($row1->supplier_id > 0) {
												$retArray = retArray("SELECT supplier_id FROM user_special_price WHERE user_id = 0 AND usp_status = '1' AND supplier_id > 0");
												$supplier_id_data = "";
												for ($i = 0; $i < count($retArray); $i++) {
													$supplier_id_data .= "'" . $retArray[$i] . "',";
												}
												$whereclause .= " AND pro.supplier_id IN (" . rtrim($supplier_id_data, ',') . ")";
											} elseif ($row1->level_two_id > 0) {
												$special_price = user_special_price("level_two", $row1->level_two_id, 0, 1);
												$whereclause .= " AND FIND_IN_SET(" . $row1->level_two_id . ", cm.sub_group_ids)";
											} elseif ($row1->level_one_id > 0) {
												$special_price = user_special_price("level_one", $row1->level_one_id, 0, 1);
												$whereclause .= " AND FIND_IN_SET(" . $row1->level_one_id . ", cm.sub_group_ids)";
											}
											$Query2 = "SELECT pro.pro_id, pro.pro_url, pro.pro_status, pro.pro_type, pro.supplier_id, pro.pro_udx_seo_epag_title, pro.pro_udx_seo_internetbezeichung, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pbp.pbp_price_amount AS pbp_price_without_tax, pbp.pbp_tax, (SELECT pg.pg_mime_source_url FROM products_gallery AS pg WHERE pg.supplier_id = pro.supplier_id AND pg.pg_mime_purpose = 'normal' ORDER BY pg.pg_mime_order ASC LIMIT 1) AS pg_mime_source_url FROM products AS pro LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = pro.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN category_map AS cm ON cm.supplier_id = pro.supplier_id " . $whereclause . " ORDER BY RAND() LIMIT 0,4";
											$rs2 = mysqli_query($GLOBALS['conn'], $Query2);
											if (mysqli_num_rows($rs2) > 0) {
												while ($row2 = mysqli_fetch_object($rs2)) {
													if ($row1->supplier_id > 0) {
														$special_price = user_special_price("supplier_id", $row2->supplier_id, 0, 1);
													}
													$sale_pd_url = product_detail_url($row2->supplier_id, 0, $row2->pro_url);
													$sale_img_url = get_image_link(75, $row2->pg_mime_source_url, true, 65);
											?>
													<div class="pd_ctg_card">
														<a tabindex="-1" href="<?php print($sale_pd_url); ?>" aria-label="<?php print($row2->pro_udx_seo_internetbezeichung); ?>">
															<div class="pd_ctg_image">
																<img src="<?php print($sale_img_url); ?>" width="75" height="75" alt="<?php print($row2->pro_udx_seo_internetbezeichung); ?>">
																<span class="pd_tag"><b>-</b> <?php print((($special_price['usp_price_type'] > 0) ? price_format($special_price['usp_discounted_value']).'€' : $special_price['usp_discounted_value'].'%')); ?></span>
															</div>
															<div class="pd_ctg_title price_without_tex" <?php print($price_without_tex_display); ?>>
																<del><?php print(price_format($row2->pbp_price_without_tax)); ?>€</del> | <span class="pd_ctg_discount_price"><?php print(price_format(discounted_price($special_price['usp_price_type'], $row2->pbp_price_without_tax, $special_price['usp_discounted_value']))); ?>€ </span>
															</div>
															<div class="pd_ctg_title pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>>
																<del><?php print(price_format($row2->pbp_price_amount)); ?>€</del> | <span class="pd_ctg_discount_price"><?php print(price_format(discounted_price($special_price['usp_price_type'], $row2->pbp_price_amount, $special_price['usp_discounted_value'], $row2->pbp_tax))); ?>€</span>
															</div>
														</a>
													</div>
											<?php
												}
											}
											?>
										</div>
									</div>
								<?php
								}
							$Query1 = "SELECT cat_id, group_id, parent_id, cat_title_de AS cat_title, cat_params_de AS cat_params FROM category WHERE cat_status = '1' AND cat_showhome = '1'";
							$rs1 = mysqli_query($GLOBALS['conn'], $Query1);
							if (mysqli_num_rows($rs1) > 0) {
								while ($row1 = mysqli_fetch_object($rs1)) {
									$cat_block_index++;
								?>
									<div class="pd_ctg_block">
										<a class="pd_ctg_heading" href="<?php print($GLOBALS['siteURL'].'kategorie/'.$row1->cat_params); ?>"> <?php print($row1->cat_title); ?> </a>
										<div class="pd_ctg_row">
											<?php
											$Query2 = "SELECT c.group_id, c.cat_title_de AS cat_title, c.cat_params_de AS cat_params, (SELECT pg.pg_mime_source_url FROM category_map AS cm INNER JOIN products_gallery AS pg ON pg.supplier_id = cm.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' WHERE cm.cat_id_level_two = c.group_id LIMIT 1) AS pg_mime_source FROM (SELECT group_id, cat_title_de, cat_params_de FROM category AS c_inner WHERE c_inner.parent_id = '" . $row1->group_id . "' AND c_inner.cat_status = '1' AND EXISTS (SELECT 1 FROM category_map AS cm2 WHERE cm2.cat_id_level_two = c_inner.group_id) ORDER BY RAND() LIMIT 0, 4) AS c";
											$rs2 = mysqli_query($GLOBALS['conn'], $Query2);
											if (mysqli_num_rows($rs2) > 0) {
												while ($row2 = mysqli_fetch_object($rs2)) {
													$cat_thumb_url = get_image_link(75, $row2->pg_mime_source, ($cat_block_index <= 2), 65);
											?>
													<div class="pd_ctg_card">
														<a href="produkte/<?php print($row1->cat_params."/".$row2->cat_params); ?>" aria-label="produkte/<?php print($row1->cat_params."/".$row2->cat_params); ?>">
															<?php if ($cat_block_index <= 2) { ?>
															<div class="pd_ctg_image"><img src="<?php print($cat_thumb_url); ?>" width="75" height="75" alt="produkte/<?php print($row1->cat_params."/".$row2->cat_params); ?>"></div>
															<?php } else { ?>
															<div class="pd_ctg_image"><img class="js_lazy_img" data-src="<?php print($cat_thumb_url); ?>" width="75" height="75" alt="produkte/<?php print($row1->cat_params."/".$row2->cat_params); ?>"></div>
															<?php } ?>
															<div class="pd_ctg_title"> <?php print($row2->cat_title); ?> </div>
														</a>
													</div>
											<?php
												}
											}
											?>
										</div>
									</div>
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
									$Query = "SELECT oi.*, pro.pro_url, pro.pro_udx_seo_internetbezeichung, pro.pro_udx_seo_epag_title, (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = oi.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_order ASC LIMIT 1) AS pg_mime_source_url, pro.pro_custom_add, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pbp.pbp_price_amount AS pbp_price_without_tax, (pbp.pbp_special_price_amount + (pbp.pbp_special_price_amount * pbp.pbp_tax)) AS pbp_special_price_amount, pbp.pbp_special_price_amount AS pbp_special_price_without_tax, pbp.pbp_tax FROM (SELECT supplier_id, COUNT(supplier_id) AS sales_count FROM order_items WHERE supplier_id != '' GROUP BY supplier_id ORDER BY sales_count DESC LIMIT 0, 12) AS oi LEFT OUTER JOIN products AS pro ON pro.supplier_id = oi.supplier_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = oi.supplier_id AND pbp.pbp_lower_bound = '1' ORDER BY oi.sales_count DESC";
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										while ($rw = mysqli_fetch_object($rs)) {
											$special_price = user_special_price("supplier_id", $rw->supplier_id, 0, 1);
											$pd_url = product_detail_url($rw->supplier_id, 0, $rw->pro_url);
											$pd_img = get_image_link(160, $rw->pg_mime_source_url);
									?>
											<div>
												<div class="pd_card">
													<div class="pd_image">
														<a tabindex="-1" href="<?php print($pd_url); ?>" aria-label="<?php print($rw->pro_udx_seo_internetbezeichung); ?>">
															<img class="js_lazy_slider_img" data-src="<?php print($pd_img); ?>" width="160" height="160" alt="<?php print($rw->pro_udx_seo_internetbezeichung); ?>">
															<?php
															if($rw->sales_count > 45){
																print('<span class="pd_tag">Best Seller</span>');
															}
															?>
														</a>
													</div>
													<div class="pd_detail">
														<h5><a tabindex="-1" href="<?php print($pd_url); ?>" title="<?php print($rw->pro_udx_seo_internetbezeichung); ?>"> <?php print($rw->pro_udx_seo_epag_title); ?> </a></h5>
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
									$Query = "SELECT sub.cat_id, sub.sub_group_ids, sub.cat_id_level_two, sub.cat_id_level_one, sub.cm_type, sub.supplier_id, pro.pro_id, pro.pro_url, pro.pro_status, pro.pro_type, pro.manf_id, pro.pro_description_short, pro.pro_udx_seo_epag_title, pro.pro_udx_seo_internetbezeichung, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pro.pro_udx_seo_epag_id, pro.pro_udx_seo_selection_feature, pbp.pbp_price_amount AS pbp_price_without_tax, (pbp.pbp_special_price_amount + (pbp.pbp_special_price_amount * pbp.pbp_tax)) AS pbp_special_price_amount, pbp.pbp_special_price_amount AS pbp_special_price_without_tax, pbp.pbp_tax, (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pro.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_order ASC LIMIT 1) AS pg_mime_source_url FROM (SELECT cm.cat_id, cm.sub_group_ids, cm.cat_id_level_two, cm.cat_id_level_one, cm.cm_type, cm.supplier_id FROM category_map AS cm INNER JOIN products AS p_chk ON p_chk.supplier_id = cm.supplier_id AND p_chk.pro_status = '1' WHERE cm.cat_id_level_one = '20' AND cm.cm_type = '20' ORDER BY RAND() LIMIT 0, 12) AS sub INNER JOIN products AS pro ON pro.supplier_id = sub.supplier_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = sub.supplier_id AND pbp.pbp_lower_bound = '1'";
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										while ($rw = mysqli_fetch_object($rs)) {
											$pd_url = product_detail_url($rw->supplier_id, 0, $rw->pro_url);
											$pd_img = get_image_link(160, $rw->pg_mime_source_url);
									?>
											<div>
												<div class="pd_card">
													<div class="pd_image"><a tabindex="-1" href="<?php print($pd_url); ?>" title="<?php print($rw->pro_udx_seo_internetbezeichung); ?>"><img class="js_lazy_slider_img" data-src="<?php print($pd_img); ?>" width="160" height="160" alt="<?php print($rw->pro_udx_seo_internetbezeichung); ?>"></a></div>
													<div class="pd_detail">
														<h5><a tabindex="-1" href="<?php print($pd_url); ?>" aria-label="<?php print($rw->pro_udx_seo_internetbezeichung); ?>"> <?php print($rw->pro_udx_seo_epag_title); ?> </a></h5>
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
								<div class="gerenric_show_All"><a tabindex="-1" href="kategorie/<?php print(returnName("cat_params_de AS cat_params","category","group_id",20)); ?>" title="Schulranzen">Mehr anzeigen</a></div>
							</div>
						</div>
						<?php
						$Query1 = "SELECT cat_id, group_id, parent_id, cat_title_de AS cat_title, cat_params_de AS cat_params FROM category WHERE cat_status = '1' AND cat_showhome_feature = '1'";
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
											$Query2 = "SELECT sub.cat_id, sub.sub_group_ids, sub.cat_id_level_two, sub.cat_id_level_one, sub.cm_type, sub.supplier_id, pro.pro_id, pro.pro_url, pro.pro_status, pro.pro_type, pro.manf_id, pro.pro_description_short, pro.pro_udx_seo_epag_title, pro.pro_udx_seo_internetbezeichung, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pro.pro_udx_seo_epag_id, pro.pro_udx_seo_selection_feature, pbp.pbp_price_amount AS pbp_price_without_tax, (pbp.pbp_special_price_amount + (pbp.pbp_special_price_amount * pbp.pbp_tax)) AS pbp_special_price_amount, pbp.pbp_special_price_amount AS pbp_special_price_without_tax, pbp.pbp_tax, (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pro.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_order ASC LIMIT 1) AS pg_mime_source_url FROM (SELECT cm.cat_id, cm.sub_group_ids, cm.cat_id_level_two, cm.cat_id_level_one, cm.cm_type, cm.supplier_id FROM category_map AS cm WHERE cm.cat_id_level_one = '" . $row1->group_id . "' ORDER BY RAND() LIMIT 0, 36) AS sub INNER JOIN products AS pro ON pro.supplier_id = sub.supplier_id AND pro.pro_status = '1' LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = sub.supplier_id AND pbp.pbp_lower_bound = '1' LIMIT 0, 12";
											$rs2 = mysqli_query($GLOBALS['conn'], $Query2);
											if (mysqli_num_rows($rs2) > 0) {
												while ($row2 = mysqli_fetch_object($rs2)) {
													$pd_url2 = product_detail_url($row2->supplier_id, 0, $row2->pro_url);
													$pd_img2 = get_image_link(160, $row2->pg_mime_source_url);
											?>
													<div>
														<div class="pd_card">
															<div class="pd_image"><a tabindex="-1" href="<?php print($pd_url2); ?>" aria-label="<?php print($row2->pro_udx_seo_internetbezeichung); ?>"><img class="js_lazy_slider_img" data-src="<?php print($pd_img2); ?>" width="160" height="160" alt="<?php print($row2->pro_udx_seo_internetbezeichung); ?>"></a></div>
															<div class="pd_detail">
																<h5><a tabindex="-1" href="<?php print($pd_url2); ?>" title="<?php print($row2->pro_udx_seo_internetbezeichung); ?>"> <?php print($row2->pro_udx_seo_epag_title); ?> </a></h5>
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
																	<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format( ((config_site_special_price > 0 && $row2->pbp_special_price_without_tax > 0) ? $row2->pbp_special_price_without_tax : $row2->pbp_price_without_tax) ) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $row2->pbp_special_price_without_tax > 0) ? $row2->pbp_special_price_without_tax : $row2->pbp_price_without_tax), $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
																	<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format( ((config_site_special_price > 0 && $row2->pbp_special_price_amount > 0) ? $row2->pbp_special_price_amount : $row2->pbp_price_amount) ) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $row2->pbp_special_price_amount > 0) ? $row2->pbp_special_price_amount : $row2->pbp_price_amount), $special_price['usp_discounted_value'], $row2->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
																<?php } else { ?>
																	<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format( ((config_site_special_price > 0 && $row2->pbp_special_price_without_tax > 0) ? $row2->pbp_special_price_without_tax : $row2->pbp_price_without_tax) )); ?>€</div>
																	<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format( ((config_site_special_price > 0 && $row2->pbp_special_price_amount > 0) ? $row2->pbp_special_price_amount : $row2->pbp_price_amount) )); ?>€</div>
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
									<div class="gerenric_show_All"><a tabindex="-1" href="kategorie/<?php print($row1->cat_params); ?>" title="<?php print($row1->cat_title); ?>">Mehr anzeigen</a></div>
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
								$Query = "SELECT sub.cat_id, sub.sub_group_ids, sub.cat_id_level_two, sub.cat_id_level_one, sub.cm_type, sub.supplier_id, pro.pro_id, pro.pro_url, pro.pro_status, pro.pro_type, pro.manf_id, pro.pro_description_short, pro.pro_udx_seo_epag_title, pro.pro_udx_seo_internetbezeichung, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pro.pro_udx_seo_epag_id, pro.pro_udx_seo_selection_feature, pbp.pbp_price_amount AS pbp_price_without_tax, (pbp.pbp_special_price_amount + (pbp.pbp_special_price_amount * pbp.pbp_tax)) AS pbp_special_price_amount, pbp.pbp_special_price_amount AS pbp_special_price_without_tax, pbp.pbp_tax, (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pro.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_order ASC LIMIT 1) AS pg_mime_source_url FROM (SELECT cm.cat_id, cm.sub_group_ids, cm.cat_id_level_two, cm.cat_id_level_one, cm.cm_type, cm.supplier_id FROM category_map AS cm WHERE cm.cat_id = '91700' AND cm.cm_type = '0' ORDER BY RAND() LIMIT 0, 36) AS sub INNER JOIN products AS pro ON pro.supplier_id = sub.supplier_id AND pro.pro_status = '1' LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = sub.supplier_id AND pbp.pbp_lower_bound = '1' LIMIT 0, 12";
								$rs = mysqli_query($GLOBALS['conn'], $Query);
								if (mysqli_num_rows($rs) > 0) {
									while ($rw = mysqli_fetch_object($rs)) {
										$pd_url = product_detail_url($rw->supplier_id, 0, $rw->pro_url);
										$pd_img = get_image_link(160, $rw->pg_mime_source_url);
								?>
										<div>
											<div class="pd_card txt_align_left">
												<div class="pd_image"><a tabindex="-1" href="<?php print($pd_url); ?>" aria-label="<?php print($rw->pro_udx_seo_internetbezeichung); ?>"><img class="js_lazy_slider_img" data-src="<?php print($pd_img); ?>" width="160" height="160" alt="<?php print($rw->pro_udx_seo_internetbezeichung); ?>"></a></div>
												<div class="pd_detail">
													<h5><a tabindex="-1" href="<?php print($pd_url); ?>" aria-label="<?php print($rw->pro_udx_seo_internetbezeichung); ?>"> <?php print($rw->pro_udx_seo_epag_title); ?> </a></h5>
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
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format( ((config_site_special_price > 0 && $rw->pbp_special_price_without_tax > 0) ? $rw->pbp_special_price_without_tax : $rw->pbp_price_without_tax) ) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $rw->pbp_special_price_without_tax > 0) ? $rw->pbp_special_price_without_tax : $rw->pbp_price_without_tax) , $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format( ((config_site_special_price > 0 && $rw->pbp_special_price_amount > 0) ? $rw->pbp_special_price_amount : $rw->pbp_price_amount) ) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $rw->pbp_special_price_amount > 0) ? $rw->pbp_special_price_amount : $rw->pbp_price_amount) , $special_price['usp_discounted_value'], $rw->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
													<?php } else { ?>
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format( ((config_site_special_price > 0 && $rw->pbp_special_price_without_tax > 0) ? $rw->pbp_special_price_without_tax : $rw->pbp_price_without_tax) )); ?>€</div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format( ((config_site_special_price > 0 && $rw->pbp_special_price_amount > 0) ? $rw->pbp_special_price_amount : $rw->pbp_price_amount) )); ?>€</div>
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
								$Query = "SELECT sub.cat_id, sub.sub_group_ids, sub.cat_id_level_two, sub.cat_id_level_one, sub.cm_type, sub.supplier_id, pro.pro_id, pro.pro_url, pro.pro_status, pro.pro_type, pro.manf_id, pro.pro_description_short, pro.pro_udx_seo_epag_title, pro.pro_udx_seo_internetbezeichung, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pro.pro_udx_seo_epag_id, pro.pro_udx_seo_selection_feature, pbp.pbp_price_amount AS pbp_price_without_tax, (pbp.pbp_special_price_amount + (pbp.pbp_special_price_amount * pbp.pbp_tax)) AS pbp_special_price_amount, pbp.pbp_special_price_amount AS pbp_special_price_without_tax, pbp.pbp_tax, (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pro.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_order ASC LIMIT 1) AS pg_mime_source_url FROM (SELECT cm.cat_id, cm.sub_group_ids, cm.cat_id_level_two, cm.cat_id_level_one, cm.cm_type, cm.supplier_id FROM category_map AS cm WHERE cm.cat_id = '91700' AND cm.cm_type = '0' ORDER BY RAND() LIMIT 0, 36) AS sub INNER JOIN products AS pro ON pro.supplier_id = sub.supplier_id AND pro.pro_status = '1' LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = sub.supplier_id AND pbp.pbp_lower_bound = '1' LIMIT 0, 12";
								$rs = mysqli_query($GLOBALS['conn'], $Query);
								if (mysqli_num_rows($rs) > 0) {
									while ($rw = mysqli_fetch_object($rs)) {
										$pd_url = product_detail_url($rw->supplier_id, 0, $rw->pro_url);
										$pd_img = get_image_link(160, $rw->pg_mime_source_url);
								?>
										<div>
											<div class="pd_card txt_align_left">
												<div class="pd_image"><a tabindex="-1" href="<?php print($pd_url); ?>" aria-label="<?php print($rw->pro_udx_seo_internetbezeichung); ?>"><img class="js_lazy_slider_img" data-src="<?php print($pd_img); ?>" width="160" height="160" alt="<?php print($rw->pro_udx_seo_internetbezeichung); ?>"></a></div>
												<div class="pd_detail">
													<h5><a tabindex="-1" href="<?php print($pd_url); ?>" aria-label="<?php print($rw->pro_udx_seo_internetbezeichung); ?>" > <?php print($rw->pro_udx_seo_epag_title); ?> </a></h5>
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
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format( ((config_site_special_price > 0 && $rw->pbp_special_price_without_tax > 0) ? $rw->pbp_special_price_without_tax : $rw->pbp_price_without_tax) ) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $rw->pbp_special_price_without_tax > 0) ? $rw->pbp_special_price_without_tax : $rw->pbp_price_without_tax) , $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format( ((config_site_special_price > 0 && $rw->pbp_special_price_amount > 0) ? $rw->pbp_special_price_amount : $rw->pbp_price_amount) ) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $rw->pbp_special_price_amount > 0) ? $rw->pbp_special_price_amount : $rw->pbp_price_amount) , $special_price['usp_discounted_value'], $rw->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
													<?php } else { ?>
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format( ((config_site_special_price > 0 && $rw->pbp_special_price_without_tax > 0) ? $rw->pbp_special_price_without_tax : $rw->pbp_price_without_tax) )); ?>€</div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format( ((config_site_special_price > 0 && $rw->pbp_special_price_amount > 0) ? $rw->pbp_special_price_amount : $rw->pbp_price_amount) )); ?>€</div>
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
											$brand_image_href = "files/manufacture/" . $row->manf_file;
										}
										$brand_image_href = get_optimized_image($brand_image_href, 160, 82);
									?>
										<div>
											<div class="brand_col"><a tabindex="-1" href="<?php print($GLOBALS['siteURL'] . "marken/".$row->manf_name_params) ?>" aria-label="<?php print($row->manf_name) ?>">
													<div class="brand_item"><img class="js_lazy_slider_img" data-src="<?php print($brand_image_href) ?>" width="120" height="60" alt="<?php print($row->manf_name) ?>">
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

<?php include("includes/bottom_js.php"); ?>
<script src="js/slick.min.js"></script>
<script type="text/javascript">
	(function() {
		var $banner = $(".banner_slider");
		var bannerInitialized = false;
		function initBannerSlider() {
			if (bannerInitialized || !$banner.length || !$.fn.slick) return;
			bannerInitialized = true;
			$banner.on("beforeChange", function(event, slick, currentSlide, nextSlide) {
				var $slides = $(slick.$slides);
				var indices = [nextSlide, nextSlide - 1, nextSlide + 1];
				for (var i = 0; i < indices.length; i++) {
					var $s = $slides.eq(indices[i]);
					$s.find("img.lazy_banner_img").each(function() {
						if (window.loadLazyImg) window.loadLazyImg(this);
						this.classList.remove("lazy_banner_img");
					});
					$s.find("video.lazy_banner_video").each(function() {
						var vid = this;
						var $src = $(vid).find("source[data-src]");
						if ($src.length) {
							$src.attr("src", $src.attr("data-src")).removeAttr("data-src");
							vid.load();
							var p = vid.play();
							if (p && typeof p.catch === "function") p.catch(function(){});
						}
						vid.classList.remove("lazy_banner_video");
					});
				}
			});
			$banner.slick({
				dots: false,
				infinite: true,
				slidesToShow: 1,
				autoplay: true,
				autoplaySpeed: 10000,
				slidesToScroll: 1,
			});
		}
		var bannerEvents = ["touchstart", "mousedown", "mousemove", "keydown", "wheel", "scroll"];
		function onFirstBannerInteract() {
			for (var i = 0; i < bannerEvents.length; i++) {
				window.removeEventListener(bannerEvents[i], onFirstBannerInteract, { passive: true });
			}
			initBannerSlider();
			if (typeof sliderQueue !== "undefined" && sliderQueue.length > 0) initSingleSlider(sliderQueue[0]);
		}
		initBannerSlider();
		for (var b = 0; b < bannerEvents.length; b++) {
			window.addEventListener(bannerEvents[b], onFirstBannerInteract, { passive: true });
		}

		function loadVisibleSliderImages($sliderEl) {
			var $active = $sliderEl.find(".slick-slide.slick-active img.js_lazy_slider_img");
			if (!$active.length) {
				$active = $sliderEl.find("img.js_lazy_slider_img").slice(0, 6);
			}
			$active.each(function() {
				if (window.loadLazyImg) window.loadLazyImg(this);
				this.classList.remove("js_lazy_slider_img");
			});
		}

		function loadAllSliderImages($sliderEl) {
			$sliderEl.find("img.js_lazy_slider_img").each(function() {
				if (window.loadLazyImg) window.loadLazyImg(this);
				this.classList.remove("js_lazy_slider_img");
			});
		}

		var genericOpts = {
			slidesToShow: 6,
			slidesToScroll: 1,
			autoplay: false,
			dots: false,
			autoplaySpeed: 2000,
			infinite: true,
			responsive: [
				{ breakpoint: 1024, settings: { slidesToShow: 4, slidesToScroll: 1 } },
				{ breakpoint: 650, settings: { slidesToShow: 3, slidesToScroll: 1 } },
				{ breakpoint: 480, settings: { slidesToShow: 2, slidesToScroll: 1 } }
			]
		};

		var mostViewedOpts = {
			slidesToShow: 10,
			slidesToScroll: 1,
			autoplay: false,
			dots: false,
			autoplaySpeed: 2000,
			infinite: true,
			responsive: [
				{ breakpoint: 1024, settings: { slidesToShow: 4, slidesToScroll: 1 } },
				{ breakpoint: 650, settings: { slidesToShow: 3, slidesToScroll: 1 } },
				{ breakpoint: 480, settings: { slidesToShow: 2, slidesToScroll: 1 } }
			]
		};

		var brandOpts = {
			slidesToShow: 10,
			slidesToScroll: 1,
			autoplay: false,
			dots: false,
			autoplaySpeed: 2000,
			infinite: true,
			responsive: [
				{ breakpoint: 1200, settings: { slidesToShow: 6, slidesToScroll: 1 } },
				{ breakpoint: 1024, settings: { slidesToShow: 4, slidesToScroll: 1 } },
				{ breakpoint: 650, settings: { slidesToShow: 3, slidesToScroll: 1 } },
				{ breakpoint: 480, settings: { slidesToShow: 2, slidesToScroll: 1 } }
			]
		};

		var sliderQueue = [];
		$(".gerenric_slider").each(function() { sliderQueue.push({ el: $(this), opts: genericOpts }); });
		$(".gerenric_slider_mostviewed").each(function() { sliderQueue.push({ el: $(this), opts: mostViewedOpts }); });
		$(".brand_slider").each(function() { sliderQueue.push({ el: $(this), opts: brandOpts }); });

		function initSingleSlider(item) {
			if (!item || item.initialized) return;
			item.initialized = true;
			item.el.on("beforeChange", function() {
				loadAllSliderImages(item.el);
			});
			item.el.slick(item.opts);
			loadVisibleSliderImages(item.el);
		}

		if ("IntersectionObserver" in window) {
			var sliderObserver = new IntersectionObserver(function(entries, obs) {
				for (var i = 0; i < entries.length; i++) {
					if (entries[i].isIntersecting) {
						var target = entries[i].target;
						obs.unobserve(target);
						for (var j = 0; j < sliderQueue.length; j++) {
							if (sliderQueue[j].el[0] === target) {
								initSingleSlider(sliderQueue[j]);
								break;
							}
						}
					}
				}
			}, { rootMargin: "150px 0px" });

			for (var s = 0; s < sliderQueue.length; s++) {
				sliderObserver.observe(sliderQueue[s].el[0]);
			}
		} else {
			for (var k = 0; k < sliderQueue.length; k++) {
				initSingleSlider(sliderQueue[k]);
			}
		}
	})();
</script>

</html>
