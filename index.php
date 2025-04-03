<?php
include("includes/php_includes_top.php");
$page = 1;
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
							//$Query1 = "SELECT * FROM user_special_price WHERE user_id = 0 AND usp_status = '1'  ORDER BY CASE WHEN supplier_id IS NOT NULL THEN 1 WHEN level_two_id IS NOT NULL AND supplier_id = 0 THEN 2 ELSE 3 END, RAND() LIMIT 1";
							$Query1 = "SELECT usp.*, pro.pro_status FROM user_special_price AS usp LEFT OUTER JOIN products AS pro ON pro.supplier_id = usp.supplier_id WHERE usp.user_id = 0 AND usp.usp_status = '1'   ORDER BY CASE WHEN usp.supplier_id IS NOT NULL AND pro.pro_status = '1' THEN 1 WHEN usp.level_two_id IS NOT NULL AND usp.supplier_id = 0 THEN 2 ELSE 3 END, RAND() LIMIT 1";
							//print($Query1);
							$rs1 = mysqli_query($GLOBALS['conn'], $Query1);
							if (mysqli_num_rows($rs1) > 0) {
								$row1 = mysqli_fetch_object($rs1);
							?>
									<div class="pd_ctg_block pd_ctg_special_sale">
										<div class="pd_ctg_heading">SALE <i class="fa fa-tag" aria-hidden="true"></i></div>
										<div class="pd_ctg_row">
											<?php
											$whereclause = "WHERE 1=1";
											if($row1->supplier_id > 0){
												$retArray = retArray("SELECT supplier_id FROM user_special_price WHERE user_id = 0 AND usp_status = '1' AND supplier_id > 0");
												//print_r($retArray);
												$supplier_id_data = "";
												for($i = 0; $i < count($retArray); $i++){
													$supplier_id_data .= "'".$retArray[$i]."',";
												}
												//$special_price = user_special_price("supplier_id", $row1->supplier_id);
												$whereclause .= " AND supplier_id IN (".rtrim($supplier_id_data, ',').")";
											} elseif($row1->level_two_id > 0){
												$special_price = user_special_price("level_two", $row1->level_two_id, 0, 1);
												$whereclause .= " AND FIND_IN_SET(".$row1->level_two_id.", pro.sub_group_ids)";
											} elseif($row1->level_one_id > 0){
												$special_price = user_special_price("level_one", $row1->level_one_id, 0, 1);
												$whereclause .= " AND FIND_IN_SET(".$row1->level_one_id.", pro.sub_group_ids)";
											}
											$Query2 = "SELECT * FROM vu_products AS pro ".$whereclause."  ORDER BY  RAND() LIMIT 0,4";
											//print($Query2);
											$rs2 = mysqli_query($GLOBALS['conn'], $Query2);
											if (mysqli_num_rows($rs2) > 0) {
												while ($row2 = mysqli_fetch_object($rs2)) {
													if($row1->supplier_id > 0){
														//$special_price = array();
														$special_price = user_special_price("supplier_id", $row2->supplier_id, 0, 1);
														//print_r($special_price);
													}
											?>
													<div class="pd_ctg_card">
														<a href="product/<?php print($row2->supplier_id); ?>/<?php print(url_clean($row2->pro_description_short)); ?>">
															<div class="pd_ctg_image">
																<img src="<?php print(get_image_link(160, $row2->pg_mime_source_url)); ?>" alt="">
																<span class="pd_tag"><?php print((($special_price['usp_price_type'] > 0) ? price_format($special_price['usp_discounted_value']).'€' : $special_price['usp_discounted_value'].'%')); ?> <b>-</b></span>
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
								?>
									<div class="pd_ctg_block">
										<div class="pd_ctg_heading"> <?php print($row1->cat_title); ?> </div>
										<div class="pd_ctg_row">
											<?php
											//$Query2 = "SELECT cm.cat_id, cm.supplier_id, c.group_id, c.cat_title_de AS cat_title, pg.pg_mime_source FROM category_map AS cm LEFT OUTER JOIN category AS c ON c.group_id = SUBSTRING(cm.cat_id, 1, 3) LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = cm.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1'  WHERE FIND_IN_SET(".$row1->group_id.", cm.sub_group_ids) GROUP BY c.group_id ORDER BY  RAND() LIMIT 0,4";
											$Query2 = "SELECT * FROM ( SELECT MIN(cm.cat_id) AS cat_id, MIN(cm.supplier_id) AS supplier_id, c.group_id, MAX(c.cat_title_de) AS cat_title, MAX(c.cat_params_de) AS cat_params, MIN(pg.pg_mime_source_url) AS pg_mime_source, RAND() AS rand_col FROM category_map AS cm LEFT OUTER JOIN category AS c ON c.group_id = SUBSTRING(cm.cat_id, 1, 3) LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = cm.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' WHERE FIND_IN_SET(" . $row1->group_id . ", cm.sub_group_ids) GROUP BY c.group_id) AS subquery ORDER BY rand_col LIMIT 0,4";
											$rs2 = mysqli_query($GLOBALS['conn'], $Query2);
											if (mysqli_num_rows($rs2) > 0) {
												while ($row2 = mysqli_fetch_object($rs2)) {
											?>
													<div class="pd_ctg_card">
														<a href="artikelarten/<?php print($row2->cat_params); ?>">
															<div class="pd_ctg_image"><img loading="lazy" src="<?php print(get_image_link(160, $row2->pg_mime_source)); ?>" alt=""></div>
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
								<h2 class="pd_heading">Meist verkaufte Produkte</h2>
								<div class="gerenric_slider">
									<?php
									$special_price = "";
									$Query = "SELECT DISTINCT oi.supplier_id, oi.ord_id, pro.pro_description_short, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pbp.pbp_price_amount AS pbp_price_without_tax, pbp.pbp_tax, pg.pg_mime_source_url FROM order_items AS oi LEFT JOIN products AS pro ON pro.supplier_id = oi.supplier_id LEFT JOIN products_bundle_price AS pbp ON pbp.supplier_id = oi.supplier_id AND pbp.pbp_lower_bound = '1' LEFT JOIN products_gallery AS pg ON pg.supplier_id = oi.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' JOIN (SELECT supplier_id FROM order_items GROUP BY supplier_id HAVING COUNT(*) >= 1 ORDER BY RAND() LIMIT 12) AS random_suppliers ON random_suppliers.supplier_id = oi.supplier_id WHERE pg.pg_mime_source_url IS NOT NULL AND pg.pg_mime_source_url <> '' GROUP BY oi.supplier_id";
									print($Query);//die();
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										while ($rw = mysqli_fetch_object($rs)) {
											$TotalRecords = TotalRecords("ord_id", "order_items", "WHERE ord_id = '".$rw->ord_id."'");
									?>
											<div>
												<div class="pd_card">
													<div class="pd_image">
														<a href="product/<?php print($rw->supplier_id); ?>/<?php print(url_clean($rw->pro_description_short)); ?>">
															<img loading="lazy" src="<?php print(get_image_link(160, $rw->pg_mime_source_url)); ?>" alt="">
															<?php
															if($TotalRecords > 80){
																print('<span class="pd_tag">Best Seller</span>');
															}
															?>
														</a>
													</div>
													<div class="pd_detail">
														<h5><a href="product/<?php print($rw->supplier_id); ?>/<?php print(url_clean($rw->pro_description_short)); ?>"> <?php print($rw->pro_description_short); ?> </a></h5>
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
									$Query = "SELECT * FROM vu_category_map AS cm WHERE cm.pro_status = '1' AND FIND_IN_SET('19', cm.sub_group_ids) AND cm.cm_type = '20' ORDER BY  RAND() LIMIT 0,12";
									//print($Query2);die();
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										while ($rw = mysqli_fetch_object($rs)) {
									?>
											<div>
												<div class="pd_card">
													<div class="pd_image"><a href="product/<?php print($rw->supplier_id); ?>/<?php print(url_clean($rw->pro_description_short)); ?>"><img loading="lazy" src="<?php print(get_image_link(160, $rw->pg_mime_source_url)); ?>" alt=""></a></div>
													<div class="pd_detail">
														<h5><a href="product/<?php print($rw->supplier_id); ?>/<?php print(url_clean($rw->pro_description_short)); ?>"> <?php print($rw->pro_description_short); ?> </a></h5>
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
								<div class="gerenric_show_All"><a href="product_category.php?level_one=20">Mehr anzeigen</a></div>
							</div>
						</div>
						<?php
						$Query1 = "SELECT cat_id, group_id, parent_id, cat_title_de AS cat_title FROM category WHERE cat_status = '1' AND cat_showhome_feature = '1'";
						$rs1 = mysqli_query($GLOBALS['conn'], $Query1);
						if (mysqli_num_rows($rs1) > 0) {
							while ($row1 = mysqli_fetch_object($rs1)) {
								//if (isset($_SESSION["UID"]) && $_SESSION["UID"] > 0) {
								$special_price = user_special_price("level_one", $row1->group_id);
								//print_r($special_price);//die();
								//}
						?>
								<div class="gerenric_white_box">
									<div class="gerenric_product full_column">
										<h2>Beliebte Produkte in <?php print($row1->cat_title); ?></h2>
										<div class="gerenric_slider">
											<?php
											//$Query2 = "SELECT cm.cat_id, cm.sub_group_ids, cm.supplier_id, pro.pro_description_short, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount,  pbp.pbp_price_amount AS pbp_price_without_tax,  pg.pg_mime_source_url FROM category_map AS cm LEFT OUTER JOIN products AS pro ON pro.supplier_id = cm.supplier_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = cm.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = cm.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1'  WHERE FIND_IN_SET(".$row1->group_id.", cm.sub_group_ids) ORDER BY  RAND() LIMIT 0,12";
											$Query2 = "SELECT * FROM vu_category_map AS cm  WHERE FIND_IN_SET(" . $row1->group_id . ", cm.sub_group_ids) ORDER BY  RAND() LIMIT 0,12";
											//print($Query2);die();
											$rs2 = mysqli_query($GLOBALS['conn'], $Query2);
											if (mysqli_num_rows($rs2) > 0) {
												while ($row2 = mysqli_fetch_object($rs2)) {
											?>
													<div>
														<div class="pd_card">
															<div class="pd_image"><a href="product/<?php print($row2->supplier_id); ?>/<?php print(url_clean($row2->pro_description_short)); ?>"><img loading="lazy" src="<?php print(get_image_link(160, $row2->pg_mime_source_url)); ?>" alt=""></a></div>
															<div class="pd_detail">
																<h5><a href="product/<?php print($row2->supplier_id); ?>/<?php print(url_clean($row2->pro_description_short)); ?>"> <?php print($row2->pro_description_short); ?> </a></h5>
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
																	<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format($row2->pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row2->pbp_price_without_tax, $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
																	<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format($row2->pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row2->pbp_price_amount, $special_price['usp_discounted_value'], $row2->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
																<?php } else { ?>
																	<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format($row2->pbp_price_without_tax)); ?>€</div>
																	<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format($row2->pbp_price_amount)); ?>€</div>
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
									<div class="gerenric_show_All"><a href="unterkategorien/<?php print(returnName("cat_params_de AS cat_params","category","group_id",$row1->group_id)); ?>">Mehr anzeigen</a></div>
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
												<div class="pd_image"><a href="product/<?php print($rw->supplier_id); ?>/<?php print(url_clean($rw->pro_description_short)); ?>"><img loading="lazy" src="<?php print(get_image_link(160, $rw->pg_mime_source_url)); ?>" alt=""></a></div>
												<div class="pd_detail">
													<h5><a href="product/<?php print($rw->supplier_id); ?>/<?php print(url_clean($rw->pro_description_short)); ?>"> <?php print($rw->pro_description_short); ?> </a></h5>
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
												<div class="pd_image"><a href="product/<?php print($rw->supplier_id); ?>/<?php print(url_clean($rw->pro_description_short)); ?>"><img loading="lazy" src="<?php print(get_image_link(160, $rw->pg_mime_source_url)); ?>" alt=""></a></div>
												<div class="pd_detail">
													<h5><a href="product/<?php print($rw->supplier_id); ?>/<?php print(url_clean($rw->pro_description_short)); ?>"> <?php print($rw->pro_description_short); ?> </a></h5>
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
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . $rw->pbp_price_without_tax . "€</del> <span class='pd_prise_discount'>" . discounted_price($special_price['usp_price_type'], $rw->pbp_price_without_tax, $special_price['usp_discounted_value']) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . $rw->pbp_price_amount . "€</del> <span class='pd_prise_discount'>" . discounted_price($special_price['usp_price_type'], $rw->pbp_price_amount, $special_price['usp_discounted_value'], 1) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
													<?php } else { ?>
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(str_replace(".", ",", $rw->pbp_price_without_tax)); ?>€</div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(str_replace(".", ",", $rw->pbp_price_amount)); ?>€</div>
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
							<div class="full_width txt_align_center"><a href="anmelden">
									<div class="gerenric_btn">Anmelden</div>
								</a></div>
							<p>Neues Konto? <a href="registrierung">Erstellen Sie hier.</a></p>
						</div>
					</div>
				</div>
				<?php
				$Query = "SELECT * FROM brands WHERE brand_status = '1'";
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
										if (!empty($row->brand_image)) {
											$brand_image_href = $GLOBALS['siteURL'] . "files/brands/" . $row->brand_image;
										}
									?>
										<div>
											<div class="brand_col"><a href="javascript:void(0)">
													<div class="brand_item"><img loading="lazy" src="<?php print($brand_image_href) ?>" alt="<?php print($row->brand_name) ?>" title="<?php print($row->brand_name) ?>">
													</div>
												</a></div>
										</div>
									<?php } ?>
								</div>
							</div>
							<div class="brand_btn"><a href="javascript:void(0)">
									<div class="gerenric_btn">Alle anzeigen</div>
								</a></div>
						</div>
					</div>
				<?php } ?>
			</div>
		</section>
		<!--CONTENT_SECTION_END-->

		<!--FOOTER_SECTION_START-->
		<div id="scroll_top">Zurück zum Seitenanfang</div>
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
		autoplay: true,
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
		autoplay: true,
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
		autoplay: true,
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