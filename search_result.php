<?php
include("includes/php_includes_top.php");
require_once("lib/class.pager1.php");
$p = new Pager1;
//print_r($_REQUEST);die();
$heading_title = "";
$search_whereclause = "";
$Sidefilter_where = "";

if (isset($_REQUEST['level_one']) && $_REQUEST['level_one'] > 0) {
	$search_whereclause = "pro.supplier_id IN (SELECT cm.supplier_id FROM category_map AS cm WHERE FIND_IN_SET(" . dbStr(trim($_REQUEST['level_one'])) . ", cm.sub_group_ids)) ";
	$heading_title .= "Category : " . returnName("cat_title_de AS cat_title", "category", "group_id", $_REQUEST['level_one']);
	$qryStrURL .= "level_one=" . $_REQUEST['level_one'] . "&";
	$cat_id = $_REQUEST['level_one'];
}
$search_keyword_where = "";
if ((isset($_REQUEST['search_keyword']) && !empty($_REQUEST['search_keyword'])) && (isset($_REQUEST['supplier_id'])) && $_REQUEST['supplier_id'] > 0) {
	$pro_description_short = returnName("pro_description_short", "vu_products", "supplier_id", $_REQUEST['supplier_id']);
	header("Location: " . $GLOBALS['siteURL'] . "product/" . $_REQUEST['supplier_id'] . "/" . url_clean($pro_description_short));
	$search_keyword = $_REQUEST['search_keyword'];
} elseif ((isset($_REQUEST['search_keyword']) && !empty($_REQUEST['search_keyword']))) {

	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$result = autocorrectQueryUsingProductTerms($_REQUEST['search_keyword'], $pdo);
	//$search_keyword_array = explode(" ", trim(str_replace("-", " ", $_REQUEST['search_keyword'])));
	$search_keyword_array = explode(" ", trim(str_replace("-", " ", $result['corrected'])));
	//print_r($pro_description_short_keyword);
	$search_keyword_where = "";
	if (count($search_keyword_array) > 1) {
		$search_keyword_case = "(CASE WHEN  pro.pro_description_short LIKE '" . dbStr(trim($result['corrected'])) . " %' THEN 1 ELSE 0 END) + ";
		for ($i = 0; $i < count($search_keyword_array); $i++) {
			$search_keyword_array_data = "";
			if (!empty($search_keyword_array[$i])) {
				$search_keyword_array_data = "pro.pro_description_short LIKE '%" . dbStr(trim($search_keyword_array[$i])) . "%' OR ";
				$search_keyword_case .= "(CASE WHEN (pro.supplier_id = '" . dbStr(trim($_REQUEST['search_keyword'])) . "' OR pro.pro_manufacture_aid = '" . dbStr(trim($_REQUEST['search_keyword'])) . "' OR pro.pro_ean = '" . dbStr(trim($_REQUEST['search_keyword'])) . "') OR " . rtrim($search_keyword_array_data, " OR ") . " THEN 1 ELSE 0 END) + ";
				$search_keyword_where .= $search_keyword_array_data;
			}
		}
	} else {
		$search_keyword_array_data = "pro.pro_description_short LIKE '%" . dbStr(trim($result['corrected'])) . "%' OR ";
		$search_keyword_case = "CASE WHEN  pro.pro_description_short LIKE '" . dbStr(trim($result['corrected'])) . " %' THEN 10 ";
		$search_keyword_case .= " WHEN (pro.supplier_id = '" . dbStr(trim($_REQUEST['search_keyword'])) . "' OR pro.pro_manufacture_aid = '" . dbStr(trim($_REQUEST['search_keyword'])) . "' OR pro.pro_ean = '" . dbStr(trim($_REQUEST['search_keyword'])) . "') OR " . rtrim($search_keyword_array_data, " OR ") . " THEN 1 ELSE 0 END";
		$search_keyword_where .= $search_keyword_array_data;
	}


	$Sidefilter_where = $Sidefilter_brandwith = $Sidefilter_featurewhere = "IN (SELECT pro.supplier_id FROM vu_products AS pro WHERE " . rtrim($search_keyword_where, " OR ") . ")";

	$heading_title .= "Schlagwort : " . $_REQUEST['search_keyword'];
	$qryStrURL .= "search_keyword=" . $_REQUEST['search_keyword'] . "&";
	$search_keyword = $_REQUEST['search_keyword'];
}
$search_group_id_check = array();
$search_group_id_where = "";
if (isset($_REQUEST['search_group_id']) && $_REQUEST['search_group_id'] > 0) {
	$whereclause = "";
	//print_r($_REQUEST['search_group_id']);//die();
	for ($i = 0; $i < count($_REQUEST['search_group_id']); $i++) {
		$search_group_id_check[] = $_REQUEST['search_group_id'][$i];
		if (!empty($search_group_id_where)) {
			$search_group_id_where .= " OR ";
		}
		$search_group_id_where .= "FIND_IN_SET (" . dbStr(trim($_REQUEST['search_group_id'][$i])) . ", cm.sub_group_ids)";
		$qryStrURL .= "search_group_id[]=" . $_REQUEST['search_group_id'][$i] . "&";
	}
	$Sidefilter_featurewhere = "IN (SELECT pro.supplier_id FROM vu_products AS pro WHERE pro.supplier_id IN ( SELECT cm.supplier_id FROM category_map AS cm WHERE (" . $search_group_id_where . ")) AND (" . rtrim($search_keyword_where, " OR ") . ") )";
	$search_whereclause = " AND pro.supplier_id IN (SELECT cm.supplier_id FROM category_map AS cm WHERE (" . $search_group_id_where . ")) ";
	$Sidefilter_brandwith = "IN (SELECT cm.supplier_id FROM category_map AS cm WHERE (" . $search_group_id_where . ") AND cm.supplier_id " . rtrim($Sidefilter_where, " OR ") . " ) ";
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
	$search_whereclause .= " AND pro.manf_id IN (" . rtrim($search_manf_id, ",") . ")";
	if (empty($search_group_id_where)) {
		$Sidefilter_featurewhere = "IN (SELECT pro.supplier_id FROM vu_products AS pro WHERE ( " . rtrim($search_keyword_where, " OR ") . ") AND pro.manf_id IN (" . rtrim($search_manf_id, ",") . ")  ) ";
	} else {
		$Sidefilter_featurewhere = "IN (SELECT pro.supplier_id FROM vu_products AS pro WHERE pro.supplier_id IN ( SELECT cm.supplier_id FROM category_map AS cm WHERE (" . $search_group_id_where . ")) AND (" . rtrim($search_keyword_where, " OR ") . ")  AND pro.manf_id IN (" . rtrim($search_manf_id, ",") . ")  ) ";
	}
}


$search_pf_fname_check = array();
$search_pf_fvalue_check = array();
if (isset($_REQUEST['search_pf_fvalue']) && $_REQUEST['search_pf_fvalue'] > 0) {
	//print_r($_REQUEST);//die();
	//print_r($_REQUEST['search_pf_forder']);die();
	//print_r($_REQUEST['search_pf_fvalue']);die();
	$search_pf_fname = "";
	$search_pf_fvalue = "";
	foreach ($_POST['search_pf_fvalue'] as $index => $selected_value) {
		$search_pf_fname_check[] = $_REQUEST['search_pf_fname'][$index];
		$search_pf_fvalue_check[] = $_REQUEST['search_pf_fvalue'][$index];
		$search_pf_fname .= "'" . dbStr(trim($_REQUEST['search_pf_fname'][$index])) . "',";
		$search_pf_fvalue .= "'" . dbStr(trim($_REQUEST['search_pf_fvalue'][$index])) . "',";
		$qryStrURL .= "search_pf_fvalue[]=" . $_REQUEST['search_pf_fvalue'][$index] . "&";
	}
	$search_whereclause .= " AND pro.supplier_id IN (SELECT pf.supplier_id FROM products_feature AS pf WHERE pf.pf_fname IN (" . rtrim($search_pf_fname, ",") . ") AND pf.pf_fvalue IN (" . rtrim($search_pf_fvalue, ",") . ") AND  pf.supplier_id IN (SELECT pro.supplier_id FROM vu_products AS pro WHERE " . rtrim($search_keyword_where, " OR ") . ") )";
	//$search_whereclause .= " AND pro.supplier_id IN (SELECT pf.supplier_id FROM products_feature AS pf WHERE pf.pf_fvalue IN (" . rtrim($search_pf_fvalue, ",") . ") AND  pf.supplier_id IN (SELECT pro.supplier_id FROM vu_products AS pro WHERE " . rtrim($search_keyword_where, " OR ") . ") )";
}

$order_by = "ORDER BY match_count DESC";
$sortby = 0;
$sortby_array = array("Sortieren nach", "Preis absteigend", "Preis aufsteigend", "Name A-Z", "Name Z-A");
if (isset($_REQUEST['sortby'])) {
	$sortby = $_REQUEST['sortby'];
	switch ($sortby) {
		case 1:
			$order_by = "ORDER BY match_count, pro.pbp_price_amount DESC";
			break;
		case 2:
			$order_by = "ORDER BY match_count DESC, pro.pbp_price_amount ASC";
			break;
		case 3:
			$order_by = "ORDER BY match_count DESC, pro.pro_description_short ASC";
			break;
		case 4:
			$order_by = "ORDER BY match_count, pro.pro_description_short DESC";
			break;
		default:
			$order_by = "ORDER BY match_count DESC";
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
				$(".detail_data_show").show();
				$(".pd_image").css("height", "100%");
			});
			$(".click_th").click(function() {
				console.log("click_th")
				$(".list_porduct").removeClass('list_class');
				$(".detail_data_show").hide();
				$(".pd_image").css("height", "");
			});
		});
		$(document).ready(function() {
			if (window.innerWidth >= 1024) {
				$(".click_list").trigger('click');
			}
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
						<?php include("includes/searchPage_left_filter.php"); ?>
						<div class="pd_right">

							<?php
							$Query_search = "SELECT pro.*, (" . rtrim($search_keyword_case, " + ") . ") AS match_count FROM vu_products AS pro WHERE ( (pro.supplier_id = '" . dbStr(trim($_REQUEST['search_keyword'])) . "' OR pro.pro_manufacture_aid = '" . dbStr(trim($_REQUEST['search_keyword'])) . "' OR pro.pro_ean = '" . dbStr(trim($_REQUEST['search_keyword'])) . "') OR " . rtrim($search_keyword_where, " OR ") . ") " . $search_whereclause . " " . $order_by . "";
							//print($Query_search);
							$counter = 0;
							$limit = 28;
							$start = $p->findStart($limit);
							$count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], $Query_search));
							$pages = $p->findPages($count, $limit);
							$rs = mysqli_query($GLOBALS['conn'], $Query_search . " LIMIT " . $start . ", " . $limit);
							$row = mysqli_fetch_object($rs);
							?>
							<div class="pd_row_heading">
								<div class="list_type_row">
									<h2> <?php print(rtrim($heading_title, ";") . " ( " . $count . " )"); ?> </h2>
									<ul>
										<li>Ansicht </li>
										<li class="click_th"><i class="fa fa-th"></i></li>
										<li class="click_list"><i class="fa fa-list"></i></li>
										<li>
											<div class="drop-down_2">
												<div class="selected">
													<a href="javascript:void(0)"><span> <?php print($sortby_array[$sortby]); ?> </span></a>
												</div>
												<div class="options">
													<ul>
														<?php for ($i = 0; $i < count($sortby_array); $i++) {
															if ($i != $sortby) { ?>
																<li><a href="<?php print($_SERVER['PHP_SELF'] . "?sortby=" . $i . "&" . $qryStrURL); ?>"><?php print($sortby_array[$i]); ?></a></li>
														<?php }
														} ?>
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
												<div class="pd_card">
													<div class="pd_image"><a href="product/<?php print($row->supplier_id); ?>/<?php print(url_clean($row->pro_description_short)); ?>"><img loading="lazy" src="<?php print(get_image_link(160, $row->pg_mime_source_url)); ?>" alt=""></a></div>
													<div class="pd_detail">
														<h3 class="detail_data_show"><a href="product/<?php print($row->supplier_id); ?>/<?php print(url_clean($row->pro_description_short)); ?>" style="display:block"> <?php print($row->pro_udx_seo_internetbezeichung); ?> </a></h3>
														<h5><a href="product/<?php print($row->supplier_id); ?>/<?php print(url_clean($row->pro_description_short)); ?>"> <?php print($row->pro_description_short); ?> </a></h5>
														<?php
														$count = 0;
														if ($row->pro_udx_seo_epag_id > 0) {
															$Query1 = "SELECT pf.*, pro.pro_description_short, pg.pg_mime_source_url FROM products_feature AS pf LEFT OUTER JOIN products AS pro ON pro.supplier_id = pf.supplier_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pf.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pf.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_order ASC LIMIT 1) WHERE pf.pro_udx_seo_epag_id = '" . $row->pro_udx_seo_epag_id . "' AND pf.pf_fname = '" . $row->pro_udx_seo_selection_feature . "'";
															$rs1 = mysqli_query($GLOBALS['conn'], $Query1);
															$count = mysqli_num_rows($rs1);
															if ($count > 1) {
																if (mysqli_num_rows($rs1) > 0) {
														?>
																	<div class="pd_detail_shirt detail_data_show">
																		<h2><?php print($row->pro_udx_seo_selection_feature); ?>: <span id="color_title_<?php print($counter); ?>"> <?php print(returnName("pf_fvalue", "products_feature", "supplier_id", $row->supplier_id, "AND pf_fname = '" . $row->pro_udx_seo_selection_feature . "'")); ?> </span> </h2>
																		<ul>
																			<?php while ($row1 = mysqli_fetch_object($rs1)) { ?>
																				<li>
																					<input type="radio" class="color" id="color_<?php print($counter); ?>" name="color_radio_<?php print($counter) ?>" data-id="<?php print($counter); ?>" value="<?php print($row1->supplier_id); ?>" <?php print(($row1->supplier_id == $row->supplier_id) ? 'checked' : ''); ?>>
																					<label for="color_<?php print($counter); ?>">
																						<span style="<?php print(((in_array($row->pro_udx_seo_selection_feature, array('Farbe', 'Schreibfarbe'))) ? 'height: 60px;' : 'height: 30px;')); ?>">
																							<?php if (in_array($row->pro_udx_seo_selection_feature, array('Farbe', 'Schreibfarbe'))) { ?>
																								<img class="color_tab" id="color_tab_<?php print($row1->supplier_id); ?>" data-id="<?php print($counter); ?>" data-supplier-id="<?php print($row1->supplier_id); ?>" data-pro-description="<?php print(url_clean($row1->pro_description_short)); ?>" src="<?php print(get_image_link(160, $row1->pg_mime_source_url)); ?>" title="<?php print($row1->pf_fvalue); ?>" alt="<?php print($row1->pf_fvalue); ?>">
																							<?php } else { ?>
																								<label for="" class="color_tab" id="color_tab_<?php print($row1->supplier_id); ?>" data-id="<?php print($counter); ?>" data-supplier-id="<?php print($row1->supplier_id); ?>" data-pro-description="<?php print(url_clean($row1->pro_description_short)); ?>" title="<?php print($row1->pf_fvalue); ?>"><?php print($row1->pf_fvalue); ?></label>
																							<?php } ?>
																						</span>
																					</label>
																				</li>
																			<?php } ?>
																		</ul>
																	</div>
														<?php
																}
															}
														}
														?>
														<?php
														$quantity_lenght = 0;
														$Query1 = "SELECT * FROM products_quantity WHERE supplier_id = '" . dbStr(trim($row->supplier_id)) . "'";
														//print();
														$rs1 = mysqli_query($GLOBALS['conn'], $Query1);
														if (mysqli_num_rows($rs1) > 0) {
															$row1 = mysqli_fetch_object($rs1);
															$pq_quantity = $row1->pq_quantity;
															$pq_upcomming_quantity = $row1->pq_upcomming_quantity;
															$pq_status = $row1->pq_status;
															$ci_qty_type = 0;
															if($pq_status == 'true'){
																$ci_qty_type = 1;
															}
															/*if ($pq_quantity == 0 && $pq_status == 'true') {
																$quantity_lenght = $pq_upcomming_quantity;
																print('<div class="product_order_title"> ' . $pq_upcomming_quantity . ' Stück bestellt</div>');
															} elseif ($pq_quantity > 0 && $pq_status == 'false') {
																$quantity_lenght = $pq_quantity + $pq_upcomming_quantity;
																print('<div class="product_order_title green"> ' . $pq_quantity . ' Stück sofort verfügbar</div>');
															}*/
															if ($pq_quantity == 0 && $pq_status == 'true') {
																$quantity_lenght = $pq_upcomming_quantity;
																print('<div class="product_order_title"> ' . $pq_upcomming_quantity . ' Stück bestellt</div>');
															} elseif ($pq_quantity > 0 && $pq_status == 'false') {
																$quantity_lenght = $pq_quantity;
																print('<div class="product_order_title green"> ' . $pq_quantity . ' Stück sofort verfügbar</div>');
															} elseif (($pq_quantity == 0 || $pq_quantity < 0) && $pq_status == 'false') {
																print('<div class="product_order_title red">Auf Anfrage</div>');
															}
														} else {
															if ($row->pro_type > 0) {
																$quantity_lenght = 1;
															} else {
																print('<div class="product_order_title red">Auf Anfrage</div>');
															}
														}
														?>
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
															<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format(((config_site_special_price > 0 && $row->pbp_special_price_without_tax > 0) ? $row->pbp_special_price_without_tax : $row->pbp_price_without_tax)) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $row->pbp_special_price_without_tax > 0) ? $row->pbp_special_price_without_tax : $row->pbp_price_without_tax), $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
															<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format(((config_site_special_price > 0 && $row->pbp_special_price_amount > 0) ? $row->pbp_special_price_amount : $row->pbp_price_amount)) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $row->pbp_special_price_amount > 0) ? $row->pbp_special_price_amount : $row->pbp_price_amount), $special_price['usp_discounted_value'], $row->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
														<?php } else { ?>
															<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format(((config_site_special_price > 0 && $row->pbp_special_price_without_tax > 0) ? $row->pbp_special_price_without_tax : $row->pbp_price_without_tax))); ?>€</div>
															<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format(((config_site_special_price > 0 && $row->pbp_special_price_amount > 0) ? $row->pbp_special_price_amount : $row->pbp_price_amount))); ?>€</div>
														<?php } ?>
														<div class="pd_btn">
															<a class="<?php print(($quantity_lenght > 0) ? 'add_to_card' : ''); ?>" href="javascript:void(0)" data-id="<?php print($row->pro_id); ?>">
																<input type="hidden" id="pro_id_<?php print($row->pro_id); ?>" name="pro_id" value="<?php print($row->pro_id); ?>">
																<input type="hidden" id="pro_type_<?php print($row->pro_id); ?>" name="pro_type" value="<?php print($row->pro_type); ?>">
																<input type="hidden" id="supplier_id_<?php print($row->pro_id); ?>" name="supplier_id" value="<?php print($row->supplier_id); ?>">
																<input type="hidden" id="ci_qty_<?php print($row->pro_id); ?>" name="ci_qty" value="1">
																<input type="hidden" id="ci_qty_type_<?php print($row->pro_id); ?>" name="ci_qty_type" value="<?php print($ci_qty_type); ?>">
																<input type="hidden" id="ci_discount_type_<?php print($row->pro_id); ?>" name="ci_discount_type" value="<?php print((!empty($special_price)) ? $special_price['usp_price_type'] : '0'); ?>">
																<input type="hidden" id="ci_discount_value_<?php print($row->pro_id); ?>" name="ci_discount_value" value="<?php print((!empty($special_price)) ? $special_price['usp_discounted_value'] : '0'); ?>">
																<div class="gerenric_btn">In den Warenkorb</div>
															</a>
														</div>
													</div>
												</div>
										<?php
											} while ($row = mysqli_fetch_object($rs));
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
		<div id="scroll_top">Zurück zum Seitenanfang</div>
		<?php include("includes/footer.php"); ?>
		<!--FOOTER_SECTION_END-->

	</div>

</body>
<script>
	$(".color_tab").on("mouseover", function() {
		let color_title = $(this).attr('title');
		let data_id = $(this).attr('data-id');
		//let supplier_id = $(this).attr('data-supplier-id');
		//console.log("color_tab: "+data_id+" supplier_id: "+supplier_id);
		$("#color_title_" + data_id).text(color_title);
	});
	$(".color_tab").on("mouseout", function() {
		let data_id = $(this).attr('data-id');
		let color_radio = $('input[name="color_radio_' + data_id + '"]:checked').val();
		let color_title = $("#color_tab_" + color_radio).attr('title');
		//console.log("mouseout: "+color_radio);
		$("#color_title_" + data_id).text(color_title);
	});
	$(".color_tab").on("click", function() {
		let supplier_id = $(this).attr("data-supplier-id");
		let pro_description = $(this).attr("data-pro-description");
		//console.log("color_tab: "+supplier_id);
		window.location.href = "product/" + supplier_id + "/" + pro_description;
	});
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
			$(this).text("(Weniger anzeigen)");
		} else {
			$(this).text("(Mehr anzeigen)");
		}

		$("#category_show_" + $(this).attr('data-id') + ", #list_checkbox_hide_" + $(this).attr('data-id') + "").toggleClass("category_show_height");
	});
</script>
<?php include("includes/bottom_js.php"); ?>

</html>