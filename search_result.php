<?php
include("includes/php_includes_top.php");

//print_r($_REQUEST);die();
$heading_title = "";
$Sidefilter_where = "";

if (isset($_REQUEST['level_one']) && $_REQUEST['level_one'] > 0) {
	$whereclause = "pro.supplier_id IN (SELECT cm.supplier_id FROM category_map AS cm WHERE FIND_IN_SET(" . dbStr(trim($_REQUEST['level_one'])) . ", cm.sub_group_ids)) ";
	$heading_title .= "Category : " . returnName("cat_title_de AS cat_title", "category", "group_id", $_REQUEST['level_one']);
	$qryStrURL .= "level_one=" . $_REQUEST['level_one'] . "&";
	$cat_id = $_REQUEST['level_one'];
}

if ((isset($_REQUEST['search_keyword']) && !empty($_REQUEST['search_keyword'])) && (isset($_REQUEST['supplier_id'])) && $_REQUEST['supplier_id'] > 0) {
	//$whereclause = "pro.supplier_id = '".dbStr(trim($_REQUEST['search_keyword']))."' OR pro.pro_description_short LIKE '%".dbStr(trim($_REQUEST['search_keyword']))."%'";
	$whereclause = "pro.supplier_id = '" . dbStr(trim($_REQUEST['supplier_id'])) . "'";
	header("Location: " . $GLOBALS['siteURL'] . "product_detail.php?supplier_id=" . $_REQUEST['supplier_id']);
	/*$Sidefilter_where = "pf.supplier_id IN (SELECT pro.supplier_id FROM products AS pro WHERE pro.pro_description_short LIKE '%".dbStr(trim($_REQUEST['search_keyword']))."%')";
	$heading_title .= "<br>Keyword : ".$_REQUEST['search_keyword'];
	$qryStrURL .= "search_keyword=".$_REQUEST['search_keyword']."&";*/
	$search_keyword = $_REQUEST['search_keyword'];
} elseif ((isset($_REQUEST['search_keyword']) && !empty($_REQUEST['search_keyword']))) {
	//$whereclause = "pro.supplier_id = '".dbStr(trim($_REQUEST['search_keyword']))."' OR pro.pro_description_short LIKE '%".dbStr(trim($_REQUEST['search_keyword']))."%' ";
	$whereclause = " pro.pro_description_short LIKE '%" . dbStr(trim($_REQUEST['search_keyword'])) . "%' OR pro.supplier_id LIKE '%" . dbStr(trim($_REQUEST['search_keyword'])) . "%' OR pro.pro_manufacture_aid LIKE '%" . dbStr(trim($_REQUEST['search_keyword'])) . "%'  OR pro.pro_ean LIKE '%" . dbStr(trim($_REQUEST['search_keyword'])) . "%' OR pro.supplier_id IN (SELECT pf.supplier_id FROM products_feature AS pf WHERE pf.pf_forder = '6' AND pf.pf_fvalue LIKE '%" . dbStr(trim($_REQUEST['search_keyword'])) . "%' )";
	$Sidefilter_where = "IN (SELECT pro.supplier_id FROM products AS pro WHERE pro.pro_description_short LIKE '%" . dbStr(trim($_REQUEST['search_keyword'])) . "%')";
	$Sidefilter_brandSubQuery = "(SELECT COUNT(pro.manf_id) FROM products AS pro WHERE pro.manf_id = manf.manf_id AND pro.pro_description_short LIKE '%" . dbStr(trim($_REQUEST['search_keyword'])) . "%')";
	$Sidefilter_brandwhere = "IN (SELECT pro.manf_id FROM products AS pro WHERE pro.pro_description_short LIKE '%" . dbStr(trim($_REQUEST['search_keyword'])) . "%')";
	//$whereclause = "pro.pro_id = '".dbStr(trim($_REQUEST['pro_id']))."'";
	$heading_title .= "<br>Keyword : " . $_REQUEST['search_keyword'];
	$qryStrURL .= "search_keyword=" . $_REQUEST['search_keyword'] . "&";
	$search_keyword = $_REQUEST['search_keyword'];
}


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
					<div class="product_inner">
						<div class="filter_mobile">Filter <i class="fa fa-angle-down"></i></div>
						<?php include("includes/searchPage_left_filter.php"); ?>
						<div class="pd_right">
							<div class="gerenric_product">
								<h2> <?php print(rtrim($heading_title, ";")); ?> </h2>
								<div class="gerenric_product_inner">
									<?php


									$Query = "SELECT pro.pro_id, pro.supplier_id, pro.pro_description_short, cm.sub_group_ids, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount,  pbp.pbp_price_amount AS pbp_price_without_tax,  pg.pg_mime_source FROM products AS pro LEFT OUTER JOIN category_map AS cm ON cm.supplier_id = pro.supplier_id LEFT OUTER JOIN category AS c ON c.group_id = cm.cat_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = pro.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' WHERE " . $whereclause . " ";
									//print($Query);die();
									$counter = 0;
									$limit = 24;
									$start = $p->findStart($limit);
									$count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], $Query));
									$pages = $p->findPages($count, $limit);
									$rs = mysqli_query($GLOBALS['conn'], $Query . " LIMIT " . $start . ", " . $limit);
									//$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										while ($row = mysqli_fetch_object($rs)) {
											$counter++;
											$sub_group_ids = explode(",", $row->sub_group_ids);
											$cat_id_one = $sub_group_ids[1];
											$cat_id_two = $sub_group_ids[0];
											//if (isset($_SESSION["UID"]) && $_SESSION["UID"] > 0) {
											$special_price = user_special_price("supplier_id", $row->supplier_id);

											if (!$special_price) {
												$special_price = user_special_price("level_two", $cat_id_two);
											}

											if (!$special_price) {
												$special_price = user_special_price("level_one", $cat_id_one);
											}
											//print_r($special_price);
											//}
									?>
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
													<?php if(!empty($special_price)) { ?>
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print( "<del>".$row->pbp_price_without_tax."€</del> <span class='pd_prise_discount'>". discounted_price($special_price['usp_price_type'], $row->pbp_price_without_tax, $special_price['usp_discounted_value'])."€ <span class='pd_prise_discount_value'>".$special_price['usp_discounted_value'].(($special_price['usp_price_type'] > 0)? '€' : '%')."</span> </span>"); ?> </div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print( "<del>".$row->pbp_price_amount."€</del> <span class='pd_prise_discount'>". discounted_price($special_price['usp_price_type'], $row->pbp_price_amount, $special_price['usp_discounted_value'], 1)."€ <span class='pd_prise_discount_value'>".$special_price['usp_discounted_value'].(($special_price['usp_price_type'] > 0)? '€' : '%')."</span> </span>"); ?> </div>
													<?php } else { ?>
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(str_replace(".", ",", $row->pbp_price_without_tax)); ?>€</div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(str_replace(".", ",", $row->pbp_price_amount)); ?>€</div>
													<?php } ?>
													<div class="pd_btn">
														<a class="add_to_card" href="javascript:void(0)" data-id="<?php print($row->pro_id); ?>">
															<input type="hidden" id="pro_id_<?php print($row->pro_id); ?>" name="pro_id" value="<?php print($row->pro_id); ?>">
															<input type="hidden" id="supplier_id_<?php print($row->pro_id); ?>" name="supplier_id" value="<?php print($row->supplier_id); ?>">
															<input type="hidden" id="ci_qty_<?php print($row->pro_id); ?>" name="ci_qty" value="1">
															<input type="hidden" id="ci_discount_type_<?php print($row->pro_id); ?>" name="ci_discount_type" value="<?php print((!empty($special_price)) ? $special_price['usp_price_type'] : '0'); ?>">
															<input type="hidden" id="ci_discount_value_<?php print($row->pro_id); ?>" name="ci_discount_value" value="<?php print((!empty($special_price)) ? $special_price['usp_discounted_value'] : '0'); ?>">
															<div class="gerenric_btn">Add to Cart</div>
														</a>
													</div>
												</div>
											</div>
									<?php
										}
									} else {
										print("Record not found!");
									}
									?>
								</div>
								<?php if ($counter > 0) { ?>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
										<tr>
											<td><?php print("Page <b>" . $_GET['page'] . "</b> of " . $pages); ?></td>
											<td align="right">
												<ul class="pagination" style="margin: 0px;">
													<?php
													$pageList = $p->pageList($_GET['page'], $pages, '&' . $qryStrURL);
													print($pageList);
													?>
												</ul>
											</td>
										</tr>
									</table>
								<?php } ?>
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
		if ($("#category_show_" + $(this).attr('data-id') + ", #list_checkbox_hide_" + $(this).attr('data-id') + " ").hasClass("category_show_height")) {
			$(this).text("(Show Less)");
		} else {
			$(this).text("(Show More)");
		}

		$("#category_show_" + $(this).attr('data-id') + ", #list_checkbox_hide_" + $(this).attr('data-id') + "").toggleClass("category_show_height");
	});
</script>
<?php include("includes/bottom_js.php"); ?>

</html>