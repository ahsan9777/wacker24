<?php
include("includes/php_includes_top.php");
$pro_type = 0;
//$qryStrURL = "";
$pro_typeURL = "";
$cat_params = "";
//$level_one = $_REQUEST['level_one'];
$level_one =  ($_REQUEST['cat_params_one'] != 'schulranzen') ? returnName("group_id", "category", "cat_params_de", $_REQUEST['cat_params_one']) : 19;
$level_one_request = ($_REQUEST['cat_params_one'] != 'schulranzen') ? returnName("group_id", "category", "cat_params_de", $_REQUEST['cat_params_one']) : 20;
$cat_params = $_REQUEST['cat_params_one'];
if ($level_one_request == 20) {
	$pro_type = $level_one_request;
	$level_one = 19;
	//$pro_typeURL = "pro_type=" . $level_one_request . "&";
	$pro_typeURL = "/" . $level_one_request;
	$qryStrURL = "pro_type=" . $level_one_request . "&";
}
//if (isset($_SESSION["UID"]) && $_SESSION["UID"] > 0) {
$special_price = user_special_price("level_one", $level_one);
//print_r($special_price);die();
//}
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

		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="product_page gerenric_padding">
				<div class="page_width">
					<div class="product_inner position_relative">
						<div class="filter_mobile">Filter <i class="fa fa-angle-down"></i></div>
						<?php include("includes/left_filter.php"); ?>
						<div class="pd_right">
							<div class="product_category_heading">
								<h1> <?php print(returnName("cat_title_de AS cat_title", "category", "group_id", $level_one)) ?> </h1>
							</div>
							<div class="product_category">
								<h2>Ausgewählte Kategorien</h2>
								<div class="product_category_inner">
									<?php
									$Query1 = "SELECT cat.cat_id, cat.group_id, cat.parent_id, cat.cat_title_de AS cat_title, cat.cat_params_de AS cat_params,  (SELECT GROUP_CONCAT(pg.pg_mime_source_url) FROM products_gallery AS pg WHERE  pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' AND pg.supplier_id = (SELECT cm.supplier_id FROM category_map AS cm WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(cat.group_id, cm.sub_group_ids) LIMIT 0,1)) AS pg_mime_source FROM category AS cat  WHERE cat.parent_id = '" . $level_one . "' ORDER BY cat.group_id ASC";
									//print($Query1);die();
									$rs1 = mysqli_query($GLOBALS['conn'], $Query1);
									if (mysqli_num_rows($rs1) > 0) {
										while ($row1 = mysqli_fetch_object($rs1)) {
											if ($level_one_request == 20 && empty($row1->pg_mime_source)) {
												continue;
											}
											$pg_mime_source_href = "files/no_img_1.jpg";
											if (!empty($row1->pg_mime_source)) {
												$pg_mime_source = explode(',', $row1->pg_mime_source);
												//$pg_mime_source_href = "getftpimage.php?img=" . $pg_mime_source[0];
												$pg_mime_source_href = $pg_mime_source[0];
											}
									?>
											<div class="pd_card">
												<div class="pd_image"><a href="artikelarten/<?php print($row1->cat_params . $pro_typeURL); ?>">
														<div class="pd_image_inner"><img loading="lazy" src="<?php print(get_image_link(160, $pg_mime_source_href)); ?>" alt=""></div>
													</a></div>
												<div class="pd_detail">
													<div class="pd_title"><a href="artikelarten/<?php print($row1->cat_params . $pro_typeURL); ?>"> <?php print($row1->cat_title); ?> </a></div>
													<ul>
														<?php
														//$Query2 = "SELECT cat.cat_id, cat.group_id, cat.parent_id, cat.cat_title_de AS cat_title, cat.cat_params_de AS cat_params, (SELECT COUNT(cm.cat_id) FROM category_map AS cm WHERE cm.cm_type = '".$pro_type."' AND FIND_IN_SET(cat.group_id, cm.cat_id) ) AS count_sub_group_ids FROM category AS cat WHERE cat.parent_id = '" . $row1->group_id . "' HAVING count_sub_group_ids > 0 ORDER BY  RAND() LIMIT 0,3";
														$Query2 = "SELECT cat.cat_id, cat.group_id, cat.parent_id, cat.cat_title_de AS cat_title, cat.cat_params_de AS cat_params FROM category AS cat WHERE cat.parent_id = '" . $row1->group_id . "' AND EXISTS (SELECT 1 FROM category_map AS cm WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(cat.group_id, cm.cat_id) ) ORDER BY  RAND() LIMIT 0,3";
														//print($Query2);//die();
														$rs2 = mysqli_query($GLOBALS['conn'], $Query2);
														if (mysqli_num_rows($rs2) > 0) {
															while ($row2 = mysqli_fetch_object($rs2)) {
														?>
																<li><a href="artikelarten/<?php print($row1->cat_params."/".$row2->cat_params . $pro_typeURL); ?>"> <?php print($row2->cat_title); ?> </a></li>
														<?php
															}
														}
														?>
													</ul>
												</div>
											</div>
									<?php
										}
									}
									?>
								</div>
							</div>
							<div class="gerenric_white_box">
								<div class="gerenric_product full_column">
									<h2>Neue Produkte</h2>
									<div class="gerenric_slider">
										<?php
										$Query = "SELECT * FROM vu_category_map AS cm WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(" . $level_one . ", cm.sub_group_ids) ORDER BY  RAND() LIMIT 0,12";
										//print($Query);die();
										$rs = mysqli_query($GLOBALS['conn'], $Query);
										if (mysqli_num_rows($rs) > 0) {
											while ($row = mysqli_fetch_object($rs)) {
										?>
												<div>
													<div class="pd_card">
														<!--<div class="pd_image"><a href="product_detail.php?supplier_id=<?php print($row->supplier_id); ?>"><img src="getftpimage.php?img=<?php print($row->pg_mime_source); ?>" alt=""></a></div>-->
														<div class="pd_image"><a href="product/<?php print($row->supplier_id); ?>/<?php print(url_clean($row->pro_description_short)); ?>"><img loading="lazy" src="<?php print(get_image_link(160, $row->pg_mime_source_url)); ?>" alt=""></a></div>
														<div class="pd_detail">
															<h5><a href="product/<?php print($row->supplier_id); ?>/<?php print(url_clean($row->pro_description_short)); ?>"> <?php print($row->pro_description_short); ?> </a></h5>
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
																<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format($row->pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_without_tax, $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
																<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format($row->pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_amount, $special_price['usp_discounted_value'], $row->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
															<?php } else { ?>
																<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format($row->pbp_price_without_tax)); ?>€</div>
																<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format($row->pbp_price_amount)); ?>€</div>
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
							<?php $Query = "SELECT DISTINCT oi.supplier_id, pro.pro_description_short, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pbp.pbp_price_amount AS pbp_price_without_tax, pg.pg_mime_source_url FROM order_items AS oi LEFT JOIN products AS pro ON pro.supplier_id = oi.supplier_id LEFT JOIN products_bundle_price AS pbp ON pbp.supplier_id = oi.supplier_id AND pbp.pbp_lower_bound = '1' LEFT JOIN products_gallery AS pg ON pg.supplier_id = oi.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' JOIN (SELECT supplier_id FROM order_items GROUP BY supplier_id HAVING COUNT(*) >= 1 ORDER BY RAND() LIMIT 12) AS random_suppliers ON random_suppliers.supplier_id = oi.supplier_id WHERE oi.supplier_id IN (SELECT cm.supplier_id FROM category_map AS cm WHERE FIND_IN_SET(" . $level_one . ", cm.sub_group_ids) AND cm.cm_type = '" . $pro_type . "') AND pg.pg_mime_source_url IS NOT NULL AND pg.pg_mime_source_url <> ''";
							//print($Query);die();
							$rs = mysqli_query($GLOBALS['conn'], $Query);
							if (mysqli_num_rows($rs) > 0) {
							?>
								<div class="gerenric_white_box">
									<div class="gerenric_product full_column">
										<h2>Meist verkaufte Produkte</h2>
										<div class="gerenric_slider">
											<?php
											while ($row = mysqli_fetch_object($rs)) {
											?>
												<div>
													<div class="pd_card">
														<div class="pd_image"><a href="product/<?php print($row->supplier_id); ?>/<?php print(url_clean($row->pro_description_short)); ?>"><img loading="lazy" src="<?php print(get_image_link(160, $row->pg_mime_source_url)); ?>" alt=""></a></div>
														<div class="pd_detail">
															<h5><a href="product/<?php print($row->supplier_id); ?>/<?php print(url_clean($row->pro_description_short)); ?>"> <?php print($row->pro_description_short); ?> </a></h5>
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
																<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format($row->pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_without_tax, $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
																<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format($row->pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_amount, $special_price['usp_discounted_value'], $row->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
															<?php } else { ?>
																<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format($row->pbp_price_without_tax)); ?>€</div>
																<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format($row->pbp_price_amount)); ?>€</div>
															<?php } ?>
														</div>
													</div>
												</div>
											<?php
											}
											?>
										</div>
									</div>
								</div>
							<?php } ?>
							<div class="gerenric_white_box">
								<div class="gerenric_product full_column">
									<h2>Ähnliche Produkte</h2>
									<div class="gerenric_slider">
										<?php
										$Query = "SELECT * FROM vu_category_map AS cm WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(" . $level_one . ", cm.sub_group_ids) ORDER BY  RAND() LIMIT 0,12";
										//print($Query);die();
										$rs = mysqli_query($GLOBALS['conn'], $Query);
										if (mysqli_num_rows($rs) > 0) {
											while ($row = mysqli_fetch_object($rs)) {
										?>
												<div>
													<div class="pd_card">
														<div class="pd_image"><a href="product/<?php print($row->supplier_id); ?>/<?php print(url_clean($row->pro_description_short)); ?>"><img loading="lazy" src="<?php print(get_image_link(160, $row->pg_mime_source_url)); ?>" alt=""></a></div>
														<div class="pd_detail">
															<h5><a href="product/<?php print($row->supplier_id); ?>/<?php print(url_clean($row->pro_description_short)); ?>"> <?php print($row->pro_description_short); ?> </a></h5>
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
																<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format($row->pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_without_tax, $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
																<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format($row->pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_amount, $special_price['usp_discounted_value'], $row->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
															<?php } else { ?>
																<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format($row->pbp_price_without_tax)); ?>€</div>
																<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format($row->pbp_price_amount)); ?>€</div>
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
								<div class="gerenric_product full_column">
									<h2>Referenzen der Produkte</h2>
									<div class="gerenric_slider">
										<?php
										$Query = "SELECT * FROM vu_category_map AS cm  WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(" . $level_one . ", cm.sub_group_ids) ORDER BY  RAND() LIMIT 0,12";
										//print($Query);die();
										$rs = mysqli_query($GLOBALS['conn'], $Query);
										if (mysqli_num_rows($rs) > 0) {
											while ($row = mysqli_fetch_object($rs)) {
										?>
												<div>
													<div class="pd_card">
														<div class="pd_image"><a href="product/<?php print($row->supplier_id); ?>/<?php print(url_clean($row->pro_description_short)); ?>"><img loading="lazy" src="<?php print(get_image_link(160, $row->pg_mime_source_url)); ?>" alt=""></a></div>
														<div class="pd_detail">
															<h5><a href="product/<?php print($row->supplier_id); ?>/<?php print(url_clean($row->pro_description_short)); ?>"> <?php print($row->pro_description_short); ?> </a></h5>
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
																<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format($row->pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_without_tax, $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
																<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format($row->pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_amount, $special_price['usp_discounted_value'], $row->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
															<?php } else { ?>
																<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format($row->pbp_price_without_tax)); ?>€</div>
																<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format($row->pbp_price_amount)); ?>€</div>
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
								<div class="gerenric_product full_column mostviewed">
									<h2>Kategorie zusammenhängen</h2>
									<div class="gerenric_slider_mostviewed">
										<?php
										//$special_price = "";
										$Query = "SELECT * FROM vu_category_map AS cm  WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(" . $level_one . ", cm.sub_group_ids) ORDER BY  RAND() LIMIT 0,12";
										//print($Query);die();
										$rs = mysqli_query($GLOBALS['conn'], $Query);
										if (mysqli_num_rows($rs) > 0) {
											while ($rw = mysqli_fetch_object($rs)) {
										?>
												<div>
													<div class="pd_card txt_align_left">
														<div class="pd_image"><a href="product_detail.php?supplier_id=<?php print($rw->supplier_id); ?>"><img loading="lazy" src="<?php print(get_image_link(160, $rw->pg_mime_source_url)); ?>" alt=""></a></div>
														<div class="pd_detail">
															<h5><a href="product_detail.php?supplier_id=<?php print($rw->supplier_id); ?>"> <?php print($rw->pro_description_short); ?> </a></h5>
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
							<div class="gerenric_white_box">
								<div class="gerenric_product full_column mostviewed">
									<div class="gerenric_slider_mostviewed">
										<?php
										//$special_price = "";
										$Query = "SELECT * FROM vu_category_map AS cm  WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(" . $level_one . ", cm.sub_group_ids) ORDER BY  RAND() LIMIT 0,12";
										//print($Query);die();
										$rs = mysqli_query($GLOBALS['conn'], $Query);
										if (mysqli_num_rows($rs) > 0) {
											while ($rw = mysqli_fetch_object($rs)) {
										?>
												<div>
													<div class="pd_card txt_align_left">
														<div class="pd_image"><a href="product_detail.php?supplier_id=<?php print($rw->supplier_id); ?>"><img loading="lazy" src="<?php print(get_image_link(160, $rw->pg_mime_source_url)); ?>" alt=""></a></div>
														<div class="pd_detail">
															<h5><a href="product_detail.php?supplier_id=<?php print($rw->supplier_id); ?>"> <?php print($rw->pro_description_short); ?> </a></h5>
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

</body>
<script src="js/slick.js"></script>
<script type="text/javascript">
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
	$(".gerenric_slider_mostviewed").slick({
		slidesToShow: 8,
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
<script>
	$(".show-more").click(function() {
		if ($(".category_show, .list_checkbox_hide").hasClass("category_show_height")) {
			$(this).text("(Weniger anzeigen)");
		} else {
			$(this).text("(Mehr anzeigen)");
		}

		$(".category_show, .list_checkbox_hide").toggleClass("category_show_height");
	});
</script>
<?php include("includes/bottom_js.php"); ?>

</html>