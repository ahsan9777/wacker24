<?php
include("includes/php_includes_top.php");
$page = 1;
?>
<!doctype html>
<html>

<head>
	<link rel="canonical" href="<?php print($GLOBALS['siteURL_main'] . "verkäufe-angebote"); ?>">
	<?php include("includes/html_header.php"); ?>
	<style>
		body {
			background-color: #111;
		}

		.gerenric_breadcrumb ul li a {
			color: #fff;
		}

		.bf-header {
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			gap: 20px;
			padding: 20px 0px;
		}

		.bf-header h1 {
			text-align: center;
			color: #FE4921;
			font-size: 85px;
			font-weight: bolder;
			text-shadow: 0 0 18px rgba(255, 57, 33, 0.4);
		}

		.bf-header p {
			color: #ffc107;
			font-size: 50px;
			font-weight: 400;
			text-shadow: 0 0 18px rgba(255, 240, 33, 0.4);
		}

		.gerenric_white_box {
			background-color: #111;
		}
		.gerenric_product h2{color: #fff !important;}
		.pd_card {
			background: #111;
			border: 1px solid #222;
			border-radius: 14px;
			padding: 18px;
			width: 250px;
			transition: all 0.3s ease;
			position: relative;
			overflow: hidden;
		}

		.pd_card:hover {
			transform: translateY(-6px);
			box-shadow: 0 0 18px rgba(255, 57, 33, 0.4);
			border-color: #ff3a20;
		}

		.pd_card::before {
			content: "BLACK FRIDAY";
			position: absolute;
			top: 20px;
			left: -55px;
			width: 180px;
			background: #ff1c0a;
			color: #fff;
			padding: 6px 0;
			text-align: center;
			font-size: 12px;
			font-weight: 700;
			text-transform: uppercase;
			transform: rotate(-45deg);
			box-shadow: 0 0 10px rgba(255, 28, 10, 0.6);
			z-index: 9;
		}

		.pd_image {
			position: relative;
			width: 100%;
			height: 180px;
			display: flex;
			justify-content: center;
			align-items: center;
			overflow: hidden;
			border-radius: 10px;
			background: #111 !important;
		}

		.pd_image img {
			width: 100%;
			height: 100%;
			object-fit: contain;
			mix-blend-mode: lighten !important;
		}

		.pd_tag {
			position: absolute;
			top: 10px;
			left: 10px;
			background: #ff1c0a;
			color: #fff;
			padding: 4px 10px;
			font-size: 12px;
			border-radius: 4px;
			text-transform: uppercase;
			font-weight: 700;
		}

		.pd_detail h5 {
			margin-top: 15px;
			font-size: 15px;
			line-height: 20px;
		}

		.pd_detail h5 a {
			color: #fff;
			text-decoration: none;
			font-weight: 600;
		}

		.pd_detail h5 a:hover {
			color: #ff3a20;
		}

		.pd_rating ul {
			padding: 0;
			margin: 6px 0;
		}

		.pd_rating ul li {
			list-style: none;
		}

		.pd_rating .fa-star {
			color: #ffb400;
			font-size: 14px;
			margin-right: 3px;
		}

		.pd_prise {
			margin-top: 6px;
			font-size: 15px;
			font-weight: 600;
			color: #fff;
		}

		.pd_prise del {
			color: #888;
			margin-right: 6px;
		}

		.pd_prise_discount {
			color: #35ff94;
			font-weight: 700;
		}

		.pd_prise_discount_value {
			background: #ff1c0a;
			color: #fff;
			padding: 2px 6px;
			border-radius: 5px;
			font-size: 11px;
			margin-left: 5px;
		}

		@media screen and (max-width:1024px) and (min-width:240px) {
			.bf-header h1{
				font-size: 45px;
			}
			.bf-header p{
				font-size: 20px;
			}
		}
	</style>
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
						<li><a href="<?php print($GLOBALS['siteURL']); ?>">Wacker24</a></li>
						<li><a href="javascript:void(0)">Verkäufe & Angebote</a></li>
					</ul>
				</div>
			</div>
		</div>
		<!--BREADCRUMB_SECTION_END-->
		<div class="bf-header">
			<h1>BLACK FRIDAY <br>MEGA SALE</h1>
			<p>Bis zu  57% Angebote! Nur für kurze Zeit!</p>
		</div>
		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="special_price_page gerenric_padding">
				<div class="page_width_1480">
					<div class="gerenric_white_box">
						<div class="gerenric_product">
							<h2>Verkäufe & Angebote</h2>
							<div class="gerenric_product_inner">
								<?php
								$supplier_id = array();
								$level_two_id = 0;
								$level_one_id = 0;
								$whereclause = "";
								$Query1 = "SELECT * FROM user_special_price WHERE user_id = '0' AND usp_status = '1'  ORDER BY supplier_id DESC";
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
											$special_price = array();
											$sub_group_ids = explode(",", $row2->sub_group_ids);
											//print_r($sub_group_ids);//die();
											//print($level_two_id);die();
											if (!empty($supplier_id) > 0 && in_array($row2->supplier_id, $supplier_id)) {
												$special_price = user_special_price("supplier_id", $row2->supplier_id, 0, 1);
												//print_r($special_price);
											} elseif ($level_two_id > 0 && ($level_two_id == $sub_group_ids[0])) {
												$special_price = user_special_price("level_two", $level_two_id, 0, 1);
											} elseif ($level_one_id > 0 && ($level_one_id == $sub_group_ids[1])) {
												$special_price = user_special_price("level_one", $level_one_id, 0, 1);
											}
								?>
											<div class="pd_card">
												<div class="pd_image"><a href="<?php print(product_detail_url($row2->supplier_id)); ?>" title="<?php print($row2->pro_udx_seo_internetbezeichung); ?>"><img loading="lazy" src="<?php print(get_image_link(160, $row2->pg_mime_source_url)); ?>" alt="<?php print($row2->pro_udx_seo_internetbezeichung); ?>"></a></div>
												<div class="pd_detail">
													<h5><a href="<?php print(product_detail_url($row2->supplier_id)); ?>" title="<?php print($row2->pro_udx_seo_internetbezeichung); ?>"> <?php print($row2->pro_udx_seo_epag_title); ?> </a></h5>
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
												//$pageList = $p->pageList($_GET['page'], $pages, '&' . $qryStrURL);
												$pageList = $p->pageList($_GET['page'], "verkaeufe-angebote", $pages, '');
												print($pageList);
												?>
											</ul>
										</td>
									</tr>
								</table>
						<?php }
								} ?>
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