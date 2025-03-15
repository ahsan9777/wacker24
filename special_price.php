<?php
include("includes/php_includes_top.php");
$page = 1;
?>
<!doctype html>
<html>

<head>
	<?php include("includes/html_header.php"); ?>
</head>

<body>
	<div id="container" align="center">

		<!--LOCATION_POPUP_START-->
		<?php include("includes/popup.php"); ?>

		<!--HEADER_SECTION_START-->
		<?php include("includes/navigation.php"); ?>
		<!--HEADER_SECTION_END-->

		<!--BREADCRUMB_SECTION_START-->
		<div class="gerenric_breadcrumb">
			<div class="page_width_1480">
				<div class="breadcrumb_inner">
					<ul>
						<li><a href="personal_data.php">Meine Daten</a></li>
						<li><a href="javascript:void(0)">Sonderpreise</a></li>
					</ul>
				</div>
			</div>
		</div>
		<!--BREADCRUMB_SECTION_END-->

		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="special_price_page gerenric_padding">
				<div class="page_width_1480">
					<div class="gerenric_white_box">
						<div class="gerenric_product">
							<h2>Meine Sonderpreise</h2>
							<div class="gerenric_product_inner">
								<?php
								$supplier_id = array();
								$level_two_id = 0;
								$level_one_id = 0;
								$whereclause = "";
								$Query1 = "SELECT * FROM user_special_price WHERE user_id = '" . $_SESSION["UID"] . "' AND usp_status = '1'  ";
								//print($Query1);
								$rs1 = mysqli_query($GLOBALS['conn'], $Query1);
								if (mysqli_num_rows($rs1) > 0) {
									while ($row1 = mysqli_fetch_object($rs1)) {
										if ($row1->supplier_id > 0) {
											$supplier_id[] = $row1->supplier_id;
											$whereclause .= " OR supplier_id = '" . $row1->supplier_id . "'";
										} elseif ($row1->level_two_id > 0) {
											$level_two_id = $row1->level_two_id;
											$whereclause .= " OR FIND_IN_SET(" . $row1->level_two_id . ", pro.sub_group_ids)";
										} elseif ($row1->level_one_id > 0) {
											$level_one_id = $row1->level_one_id;
											$whereclause .= " OR FIND_IN_SET(" . $row1->level_one_id . ", pro.sub_group_ids)";
										}
									}
								}
								if (!empty($whereclause)) {
									$Query2 = "SELECT * FROM vu_products AS pro WHERE 1=1 AND ( " . ltrim($whereclause, ' OR ') . " ) ";
									//print($Query2);
									$counter = 0;
									$limit = 30;
									$start = $p->findStart($limit);
									$count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], $Query2));
									$pages = $p->findPages($count, $limit);
									$rs2 = mysqli_query($GLOBALS['conn'], $Query2 . " LIMIT " . $start . ", " . $limit);
									if (mysqli_num_rows($rs2) > 0) {
										while ($row2 = mysqli_fetch_object($rs2)) {
											$counter++;

											$sub_group_ids = explode(",", $row2->sub_group_ids);
											//print_r($sub_group_ids);//die();
											//print($level_two_id);die();
											if (!empty($supplier_id) > 0 && in_array($row2->supplier_id, $supplier_id)) {
												$special_price = user_special_price("supplier_id", $row2->supplier_id);
												//print_r($special_price);
											} elseif ($level_two_id > 0 && ($level_two_id == $sub_group_ids[0])) {
												$special_price = user_special_price("level_two", $level_two_id);
											} elseif ($level_one_id > 0 && ($level_one_id == $sub_group_ids[1])) {
												$special_price = user_special_price("level_one", $level_one_id);
											}
								?>
											<div class="pd_card pd_card_five">
												<div class="pd_image"><a href="product_detail.php?supplier_id=<?php print($row2->supplier_id); ?>"><img loading="lazy" src="<?php print(get_image_link(160, $row2->pg_mime_source_url)); ?>" alt=""></a></div>
												<div class="pd_detail">
													<h5><a href="product_detail.php?supplier_id=<?php print($row2->supplier_id); ?>"> <?php print($row2->pro_description_short); ?> </a></h5>
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
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format($row2->pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row2->pbp_price_amount, $special_price['usp_discounted_value'], 1)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
													<?php } else { ?>
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format($row2->pbp_price_without_tax)); ?>€</div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format($row2->pbp_price_amount)); ?>€</div>
													<?php } ?>
												</div>
											</div>
								<?php
										}
									} else {
										print("Leerer Eintrag!");
									}
								?>
							</div>
							<?php if ($counter > 0) { ?>
								<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin: 30px 0px;">
									<tr>
										<td align="center">
											<ul class="pagination" style="margin: 0px;">
												<?php
												$pageList = $p->pageList($_GET['page'], $pages, '&' . $qryStrURL);
												print($pageList);
												?>
											</ul>
										</td>
									</tr>
								</table>
							<?php }  }?>
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
<?php include("includes/bottom_js.php"); ?>
<script src="js/slick.js"></script>
<script>
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
</script>

</html>