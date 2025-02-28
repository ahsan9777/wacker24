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
$products_feature = "";
if ((isset($_REQUEST['search_keyword']) && !empty($_REQUEST['search_keyword'])) && (isset($_REQUEST['supplier_id'])) && $_REQUEST['supplier_id'] > 0) {
	$whereclause = "pro.supplier_id = '" . dbStr(trim($_REQUEST['supplier_id'])) . "'";
	header("Location: " . $GLOBALS['siteURL'] . "product_detail.php?supplier_id=" . $_REQUEST['supplier_id']);

	$search_keyword = $_REQUEST['search_keyword'];
} elseif ((isset($_REQUEST['search_keyword']) && !empty($_REQUEST['search_keyword']))) {
	$pf_fvalue_keyword = explode(" ", trim($_REQUEST['search_keyword']));
	//print_r($pro_description_short_keyword);
	$pf_fname_array = array('Werbliche Produkttypbezeichnung', 'Papierformat', 'Grammatur', 'Farbe', 'Anzahl der Blätter je Packung');
	for ($j = 0; $j < 2; $j++) {
		$pf_fvalue = "";
		$products_feature .= " OR pro.supplier_id IN (SELECT pf.supplier_id FROM products_feature AS pf WHERE pf.pf_fname = '" . $pf_fname_array[$j] . "' ";
		for ($i = 0; $i < count($pf_fvalue_keyword); $i++) {
			//print("i: ".$i." j: ".$j."<br>");
			if (!empty($pf_fvalue_keyword[$i])) {
				if ($j == 0) {
					$pf_fvalue .= "pf.pf_fvalue LIKE '%" . dbStr(trim($pf_fvalue_keyword[$i])) . "%' OR ";
				} else {
					$pf_fvalue .= "pf.pf_fvalue LIKE '" . dbStr(trim($pf_fvalue_keyword[$i])) . "%' OR ";
				}
			}
		}
		$products_feature .= " AND ( " . rtrim($pf_fvalue, " OR ") . "  ) ) ";
	}
	//$whereclause = " ( pro.pro_description_short LIKE '%" . dbStr(trim($_REQUEST['search_keyword'])) . "%' OR pro.supplier_id LIKE '%" . dbStr(trim($_REQUEST['search_keyword'])) . "%' OR pro.pro_manufacture_aid LIKE '%" . dbStr(trim($_REQUEST['search_keyword'])) . "%'  OR pro.pro_ean LIKE '%" . dbStr(trim($_REQUEST['search_keyword'])) . "%' OR pro.supplier_id IN (SELECT pf.supplier_id FROM products_feature AS pf WHERE pf.pf_forder IN (2,3,5,24,26) AND ( ".rtrim($pf_fvalue, " OR ")." ) ) )";
	//$whereclause = " ( pro.pro_description_short LIKE '%" . dbStr(trim($_REQUEST['search_keyword'])) . "%' OR pro.supplier_id LIKE '%" . dbStr(trim($_REQUEST['search_keyword'])) . "%' OR pro.pro_manufacture_aid LIKE '%" . dbStr(trim($_REQUEST['search_keyword'])) . "%'  OR pro.pro_ean LIKE '%" . dbStr(trim($_REQUEST['search_keyword'])) . "%' OR pro.supplier_id IN (SELECT pf.supplier_id FROM products_feature AS pf WHERE pf.pf_fname IN ('Werbliche Produkttypbezeichnung', 'Papierformat', 'Grammatur', 'Farbe', 'Anzahl der Blätter je Packung') AND ( ".rtrim($pf_fvalue, " OR ")."  ) ) )";
	$whereclause = " (pro.pro_description_short LIKE '%" . dbStr(trim($_REQUEST['search_keyword'])) . "%' OR pro.pro_description_short LIKE '%" . dbStr(str_replace(array("-", " "), '', trim($_REQUEST['search_keyword']))) . "%'  " . rtrim($products_feature, " OR ") . "  )";
	$Sidefilter_where = "IN (SELECT pro.supplier_id FROM products AS pro WHERE pro.pro_description_short LIKE '%" . dbStr(trim($_REQUEST['search_keyword'])) . "%') ".str_replace("pro.supplier_id", "cm.supplier_id", $products_feature)."";
	//$Sidefilter_featurewhere = "IN (SELECT pro.supplier_id FROM products AS pro WHERE pro.pro_description_short LIKE '%" . dbStr(trim($_REQUEST['search_keyword'])) . "%') ";
	$Sidefilter_brandwith = "WITH filtered_products AS ( SELECT pro.manf_id, pro.supplier_id FROM products AS pro WHERE pro.pro_description_short LIKE '%" . dbStr(trim($_REQUEST['search_keyword'])) . "%' ".$products_feature.")";

	$heading_title .= "Keyword : " . $_REQUEST['search_keyword'];
	$qryStrURL .= "search_keyword=" . $_REQUEST['search_keyword'] . "&";
	$search_keyword = $_REQUEST['search_keyword'];
}
$search_group_id_check = array();
if (isset($_REQUEST['search_group_id']) && $_REQUEST['search_group_id'] > 0) {
	$whereclause = "";
	$search_group_id_where = "";
	//print_r($_REQUEST['search_group_id']);//die();
	for ($i = 0; $i < count($_REQUEST['search_group_id']); $i++) {
		$search_group_id_check[] = $_REQUEST['search_group_id'][$i];
		if (!empty($search_group_id_where)) {
			$search_group_id_where .= " OR ";
		}
		$search_group_id_where .= "FIND_IN_SET (" . dbStr(trim($_REQUEST['search_group_id'][$i])) . ", cm.sub_group_ids)";
		$qryStrURL .= "search_group_id[]=" . $_REQUEST['search_group_id'][$i] . "&";
	}
	if(!empty($products_feature)){
		$Sidefilter_brandwith = "WITH relevant_suppliers AS ( SELECT DISTINCT cm.supplier_id FROM category_map AS cm WHERE " . $search_group_id_where . " ), filtered_products AS ( SELECT pro.manf_id, pro.supplier_id FROM products AS pro WHERE (".ltrim($products_feature, " OR ").") AND pro.supplier_id IN (SELECT supplier_id FROM relevant_suppliers) )";
	} else {
		$Sidefilter_brandwith = "WITH relevant_suppliers AS ( SELECT DISTINCT cm.supplier_id FROM category_map AS cm WHERE " . $search_group_id_where . " ), filtered_products AS ( SELECT pro.manf_id, pro.supplier_id FROM products AS pro WHERE pro.pro_description_short LIKE '%" . dbStr(trim($_REQUEST['search_keyword'])) . "%' AND pro.supplier_id IN (SELECT supplier_id FROM relevant_suppliers) )";
	}
	//$Sidefilter_featurewhere = "IN (SELECT pro.supplier_id FROM products AS pro WHERE pro.pro_description_short LIKE '%" . dbStr(trim($_REQUEST['search_keyword'])) . "%' AND pro.supplier_id IN ( SELECT cm.supplier_id FROM category_map AS cm WHERE (" . $search_group_id_where . ")) )";
	//$whereclause .= "pro.supplier_id IN (SELECT cm.supplier_id FROM category_map AS cm WHERE (" . $search_group_id_where . ") AND cm.supplier_id " . $Sidefilter_where . ") ";
	$whereclause .= "pro.supplier_id IN (SELECT cm.supplier_id FROM category_map AS cm WHERE (" . $search_group_id_where . ") AND ( " . ltrim($products_feature, " OR ") . ") ) ";
}
$search_manf_id_check = array();
if (isset($_REQUEST['search_manf_id']) && $_REQUEST['search_manf_id'] > 0) {
	//print_r($_REQUEST['search_manf_id']);//die();
	$search_manf_id = "";
	for ($i = 0; $i < count($_REQUEST['search_manf_id']); $i++) {
		$search_manf_id_check[] = $_REQUEST['search_manf_id'][$i];
		$search_manf_id .= $_REQUEST['search_manf_id'][$i] . ",";
		$qryStrURL .= "search_manf_id[]=" . $_REQUEST['search_manf_id'][$i] . "&";
	}
	$whereclause .= " AND pro.manf_id IN (" . rtrim($search_manf_id, ",") . ")";
}

$search_pf_fvalue_check = "";
if (isset($_REQUEST['search_pf_fvalue']) && $_REQUEST['search_pf_fvalue'] > 0) {
	//print_r($_REQUEST['search_pf_fvalue']);die();
	$search_pf_fvalue_array = explode(";", $_REQUEST['search_pf_fvalue']);
	$search_pf_fvalue = "";
	$search_pf_fvalue_check = $_REQUEST['search_pf_fvalue'];
	$search_pf_fvalue .= "'" . $search_pf_fvalue_array[0] . "',";
	$whereclause .= " AND pro.supplier_id IN (SELECT pf.supplier_id FROM products_feature AS pf WHERE pf.pf_fvalue = '" . $search_pf_fvalue_array[0] . "' AND pf.pf_forder = '" . $search_pf_fvalue_array[1] . "' )";
}

$order_by = "";
$sortby = 0;
$sortby_array = array("Sort by: N/A", "Price high to low", "Price low to high", "Sort by a to z", "Sort by z to a");
if(isset($_REQUEST['sortby'])){
	$sortby = $_REQUEST['sortby'];
	switch($sortby){
		case 1:
			$order_by = "ORDER BY pro.pbp_price_amount DESC";
			break;
		case 2:
			$order_by = "ORDER BY pro.pbp_price_amount ASC";
			break;
		case 3:
			$order_by = "ORDER BY pro.pro_description_short ASC";
			break;
		case 4:
			$order_by = "ORDER BY pro.pro_description_short DESC";
			break;
		default:
			$order_by = "";
	}
}


?>
<!doctype html>
<html lang="de">

<head>
	<?php include("includes/html_header.php"); ?>
	<script>
		$(document).ready(function() {
			$(".click_list").click(function() {
				$(".list_porduct").addClass('list_class');
			});
			$(".click_th").click(function() {
				$(".list_porduct").removeClass('list_class');
			});
		});
	</script>
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
						<?php
						$Query_search = "SELECT * FROM vu_products AS pro WHERE " . $whereclause . " ".$order_by."";
						$counter = 0;
						$limit = 30;
						$start = $p->findStart($limit);
						$rs1 = mysqli_query($GLOBALS['conn'], $Query_search);
						$suppliers = "";
						while ($row22 = mysqli_fetch_object($rs1)) {
							$suppliers .= "'".$row22->supplier_id . "',";
						}
						//echo $suppliers;
						include("includes/searchPage_left_filter.php");
						?>
						<div class="pd_right">

							<?php
							//print($Query_search);
							$count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], $Query_search));
							$pages = $p->findPages($count, $limit);
							$rs = mysqli_query($GLOBALS['conn'], $Query_search . " LIMIT " . $start . ", " . $limit);
							$row = mysqli_fetch_object($rs);
							?>
							<div class="pd_row_heading">
								<div class="list_type_row">
									<h2> <?php print(rtrim($heading_title, ";") . " ( " . $count . " )"); ?> </h2>
									<ul>
										<li class="click_th"><i class="fa fa-th"></i></li>
										<li class="click_list"><i class="fa fa-list"></i></li>
										<li>
											<div class="drop-down_2">
												<div class="selected">
													<a href="javascript:void(0)"><span> <?php print($sortby_array[$sortby]); ?> </span></a>
												</div>
												<div class="options">
													<ul>
														<?php for($i = 0; $i < count($sortby_array); $i++) { if($i != $sortby){ ?>
															<li><a href="<?php print($_SERVER['PHP_SELF'] . "?sortby=".$i."&" . $qryStrURL); ?>"><?php print($sortby_array[$i]); ?></a></li>
														<?php } } ?>
													</ul>
												</div>
											</div>
										</li>
									</ul>
								</div>
							</div>
							<div class="list_porduct">
								<div class="gerenric_product">
									<div class="gerenric_product_inner">
										<?php
										if (mysqli_num_rows($rs) > 0) {
											do {
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
												<div class="pd_card pd_card_five">
													<div class="pd_image"><a href="product_detail.php?supplier_id=<?php print($row->supplier_id); ?>"><img loading="lazy" src="<?php print(get_image_link(160, $row->pg_mime_source_url)); ?>" alt=""></a></div>
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
														<?php if (!empty($special_price)) { ?>
															<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . $row->pbp_price_without_tax . "€</del> <span class='pd_prise_discount'>" . discounted_price($special_price['usp_price_type'], $row->pbp_price_without_tax, $special_price['usp_discounted_value']) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
															<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . $row->pbp_price_amount . "€</del> <span class='pd_prise_discount'>" . discounted_price($special_price['usp_price_type'], $row->pbp_price_amount, $special_price['usp_discounted_value'], 1) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
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
											} while ($row = mysqli_fetch_object($rs));
										} else {
											print("Record not found!");
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
									<?php } ?>
								</div>
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
	//TOGGLING NESTED ul
	$(".drop-down_2 .selected a").click(function() {
		$(".drop-down_2 .options ul").toggle();
	});

	//SELECT OPTIONS AND HIDE OPTION AFTER SELECTION
	$(".drop-down_2 .options ul li a").click(function() {
		var text = $(this).html();
		$(".drop-down_2 .selected a span").html(text);
		$(".drop-down_2 .options ul").hide();
	});


	//HIDE OPTIONS IF CLICKED ANYWHERE ELSE ON PAGE
	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (!$clicked.parents().hasClass("drop-down_2"))
			$(".drop-down_2 .options ul").hide();
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