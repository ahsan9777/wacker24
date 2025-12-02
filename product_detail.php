<?php

include("includes/php_includes_top.php");
//print_r($_REQUEST);die();
$ci_type = 0;
if (isset($_REQUEST['ci_type']) && $_REQUEST['ci_type'] > 0) {
	$ci_type = 1;
}
$product_params = explode("-", $_REQUEST['product_params']);
$params_supplier_id = end($product_params);
$Query = "SELECT pro.*, pbp.pbp_id, pro.pro_ean, manf.manf_name, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pbp.pbp_price_amount AS pbp_price_without_tax, (pbp.pbp_special_price_amount + (pbp.pbp_special_price_amount * pbp.pbp_tax)) AS pbp_special_price_amount, pbp.pbp_special_price_amount AS pbp_special_price_without_tax, pbp.pbp_tax, pg.pg_mime_source_url, pg.pg_mime_description, cm.cat_id AS cat_id_three, cm.sub_group_ids, c.cat_title_de AS cat_title_three, c.cat_params_de AS cat_three_params FROM products AS pro LEFT OUTER JOIN manufacture AS manf ON manf.manf_id = pro.manf_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = pro.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pro.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_order ASC LIMIT 1) LEFT OUTER JOIN category_map AS cm ON cm.supplier_id = pro.supplier_id LEFT OUTER JOIN category AS c ON c.group_id = cm.cat_id WHERE pro.pro_status = '1' AND pro.supplier_id = '" . $params_supplier_id . "'";
//print($Query);//die();
$rs = mysqli_query($GLOBALS['conn'], $Query);
if (mysqli_num_rows($rs) > 0) {
	$row = mysqli_fetch_object($rs);

	$pro_id = $row->pro_id;
	$manf_name = $row->manf_name;
	$pro_type = $row->pro_type;
	if ($pro_type > 0) {
		$qryStrURL = "/" . $pro_type;
	}
	$supplier_id = $row->supplier_id;
	$pro_udx_seo_internetbezeichung = $row->pro_udx_seo_internetbezeichung;
	$page_title = $pro_udx_seo_internetbezeichung;
	$pro_udx_seo_internetbezeichung_params_de = $row->pro_udx_seo_internetbezeichung_params_de;
	$pro_udx_seo_epag_title = $row->pro_udx_seo_epag_title;
	$pro_udx_seo_epag_title_params_de = $row->pro_udx_seo_epag_title_params_de;
	$pro_description_short = $row->pro_description_short;
	$pro_description_long = $row->pro_description_long;
	$pro_description_long_schema = addslashes(str_replace(array("-", '"'), "", strip_tags($pro_description_long)));
	$pro_description_long_schema = trim(preg_replace('/\s+/', ' ', $pro_description_long_schema));
	$pro_ean = $row->pro_ean;
	$pro_buyer_id = $row->pro_buyer_id;
	$pro_manufacture_aid = $row->pro_manufacture_aid;
	$pro_delivery_time = $row->pro_delivery_time;
	$pro_order_unit = $row->pro_order_unit;
	$pro_count_unit = $row->pro_count_unit;
	$pro_no_cu_per_ou = $row->pro_no_cu_per_ou;
	$pro_udx_seo_epag_id = $row->pro_udx_seo_epag_id;
	$pro_udx_seo_selection_feature = $row->pro_udx_seo_selection_feature;
	$pro_price_quantity = $row->pro_price_quantity;
	$pro_quantity_min = $row->pro_quantity_min;
	$pro_quantity_interval = $row->pro_quantity_interval;
	$pbp_price_amount = $row->pbp_price_amount;
	$pbp_price_without_tax = $row->pbp_price_without_tax;
	if (config_site_special_price > 0 && $row->pbp_special_price_amount > 0) {
		$pbp_price_amount = $row->pbp_special_price_amount;
		$pbp_price_without_tax = $row->pbp_special_price_without_tax;
	}
	$pbp_id = $row->pbp_id;
	$pbp_tax = $row->pbp_tax;
	$ci_amount = $pbp_price_without_tax;

	$pg_mime_source_url = $row->pg_mime_source_url;
	$pg_mime_description = $row->pg_mime_description;
	$sub_group_ids = explode(",", $row->sub_group_ids);
	//print_r($sub_group_ids);
	$cat_id_one = $sub_group_ids[1];
	$meta_keywords = implode(", ", returnNameArray("pk_title", "products_keyword", "supplier_id", $supplier_id));
	$meta_description = $pro_udx_seo_internetbezeichung;
	$pq_physical_quantity = returnName("pq_physical_quantity", "products_quantity", "supplier_id", $supplier_id);
	$cat_title_one = returnName("cat_title_de AS cat_title", "category", "group_id", $cat_id_one);
	$cat_one_params = returnName("cat_params_de AS cat_params", "category", "group_id", $cat_id_one);
	$cat_id_two = $sub_group_ids[0];
	$cat_title_two = returnName("cat_title_de AS cat_title", "category", "group_id", $cat_id_two);
	$cat_two_params = returnName("cat_params_de AS cat_params", "category", "group_id", $cat_id_two);
	$cat_id_three = $row->cat_id_three;
	$cat_title_three = $row->cat_title_three;
	$cat_three_params = $row->cat_three_params;
	$pro_udx_manufacturer_address = $row->pro_udx_manufacturer_address;
	$pro_udx_manufacturer_mail = $row->pro_udx_manufacturer_mail;
	$pro_udate = date("Y-m-d", strtotime($row->pro_udate));

	$quantity_status = "";
	$Query = "SELECT * FROM products_quantity WHERE supplier_id = '" . dbStr(trim($supplier_id)) . "'";
	//print($Query);
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	$ci_qty_type = 0;
	if (mysqli_num_rows($rs) > 0) {
		$row = mysqli_fetch_object($rs);
		$pq_quantity = $row->pq_quantity;
		$pq_status = $row->pq_status;
		if ($pq_status == 'true') {
			$ci_qty_type = 1;
		}
		if ($ci_type > 0) {
			$quantity_status = "https://schema.org/InStock";
		} elseif (($pq_quantity == 0 || $pq_quantity < 0) && $pq_status == 'true') {
			$quantity_status = "https://schema.org/PreOrder";
		} elseif ($pq_quantity > 0 && $pq_status == 'false') {
			$quantity_status = "https://schema.org/InStock";
		} elseif (($pq_quantity == 0 || $pq_quantity < 0) && $pq_status == 'false') {
			$quantity_status = "https://schema.org/OutOfStock";
		}
	} else {
		if ($pro_type > 0) {
			$quantity_status = "https://schema.org/InStock";
		} else {
			$quantity_status = "https://schema.org/OutOfStock";
		}
	}


	//$street = ""; $postal_city = ""; $country = "";
	//$parts = preg_split('/\bGmbH\b\s*/', $pro_udx_manufacturer_address, 2);
	/*$company = $parts[0] . 'GmbH';
	if (preg_match('/^(.*?)(\b\d{5}\b.*)$/', $parts[1], $matches)) {
		$firstPart = trim($matches[1]);
    	$secondPart = trim($matches[2]);

		$words = explode(' ', $secondPart);
		$lastWord = end($words);

		$street = $firstPart;
		$postal_city = rtrim($secondPart, $lastWord);
		$country = $lastWord;
	}
	$pro_udx_manufacturer_address =*/
	//print_r($parts);

	$pg_mime_source_url_logo = returnName("pg_mime_source_url", "products_gallery", "supplier_id", $supplier_id, " AND pg_mime_purpose = 'logo'");
	$pg_mime_source_url_pdf = returnName("pg_mime_source_url", "products_gallery", "supplier_id", $supplier_id, " AND pg_mime_type = 'application/pdf'");
	$pg_mime_description = returnName("pg_mime_description", "products_gallery", "supplier_id", $supplier_id, " AND pg_mime_type = 'application/pdf'");
	mysqli_query($GLOBALS['conn'], "UPDATE products SET pro_view = pro_view + '1' WHERE supplier_id = '" . dbStr(trim($supplier_id)) . "'") or die(mysqli_error($GLOBALS['conn']));
} else {
	/*$product_params = explode("/", $_REQUEST['product_params']);
	$pro_status = returnName("pro_status", "products", "pro_udx_seo_internetbezeichung_params_de", $product_params[0]);
	$product_params_supplier_id = returnName("supplier_id", "products", "pro_udx_seo_internetbezeichung_params_de", $product_params[0]);
	if ($pro_status > 0) {
		$pro_udx_seo_epag_title_params_de = returnName("pro_udx_seo_epag_title_params_de", "products", "supplier_id", $product_params_supplier_id);
		header("Location: " . $GLOBALS['siteURL'] . $pro_udx_seo_epag_title_params_de . "-" . $product_params_supplier_id);
	} else {*/
		header("Location: " . $GLOBALS['siteURL'] . "nicht-verfuegbar");
	//}
}

//if (isset($_SESSION["UID"]) && $_SESSION["UID"] > 0) {
$special_price = user_special_price("supplier_id", $supplier_id);

if (!$special_price) {
	$special_price = user_special_price("level_two", $cat_id_two);
}

if (!$special_price) {
	$special_price = user_special_price("level_one", $cat_id_one);
}
//print_r($special_price);
//}
$price_schema = '
	"price": "' . $pbp_price_amount . '", 
    "priceValidUntil": "2099-12-31",
    "itemCondition": "https://schema.org/NewCondition",
    "availability": "' . $quantity_status . '",
';
$versand_txt_color = "";
$versand_txt = "Versand";
$discounted_price = 0;
$shipping_price_free = 0;
$returnShippingFeesAmount = '"returnShippingFeesAmount": {
						"@type": "MonetaryAmount",
						"value": "' . config_courier_fix_charges . '",
						"currency": "EUR"
					},';
if (!empty($special_price)) {
	$Query_special_price = "SELECT pbp_lower_bound, (pbp_price_amount + (pbp_price_amount * pbp_tax)) AS pbp_price_amount,  pbp_price_amount AS pbp_price_without_tax, (pbp_special_price_amount + (pbp_special_price_amount * pbp_tax)) AS pbp_special_price_amount, pbp_special_price_amount AS pbp_special_price_without_tax, pbp_tax FROM products_bundle_price WHERE pro_id = '" . $pro_id . "' AND supplier_id = '" . $params_supplier_id . "' ORDER BY pbp_lower_bound DESC LIMIT 1";
	$rs_special_price = mysqli_query($GLOBALS['conn'], $Query_special_price);
	if (mysqli_num_rows($rs_special_price) > 0) {
		$row_special_price = mysqli_fetch_object($rs_special_price);
		$discounted_price = discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $row_special_price->pbp_special_price_amount > 0) ? $row_special_price->pbp_special_price_amount : $row_special_price->pbp_price_amount), $special_price['usp_discounted_value'], $row_special_price->pbp_tax);

		$price_schema = '
			"price": "' . $discounted_price . '", 
			"priceValidUntil": "2099-12-31",
			"itemCondition": "https://schema.org/NewCondition",
			"availability": "' . $quantity_status . '",

			"priceSpecification": {
			"@type": "PriceSpecification",
			"priceCurrency": "EUR",
			"price": "' . $pbp_price_amount . '",
			"name": "Original price",
			"eligibleQuantity": {
				"@type": "QuantitativeValue",
				"value": 1
			}
			},
		';
	}
}

if ($discounted_price > 0) {
	//echo "if: ";
	if ($discounted_price >= config_condition_courier_amount) {
		$shipping_price_free = 1;
		$returnShippingFeesAmount = "";
		$versand_txt_color = "green";
		$versand_txt = "Versandkostenfrei";
	}
} else {
	//echo "else: ";
	if ($pbp_price_amount >= config_condition_courier_amount) {
		$shipping_price_free = 1;
		$returnShippingFeesAmount = "";
		$versand_txt_color = "green";
		$versand_txt = "Versandkostenfrei";
	}
}
//echo $shipping_price_free;

if (isset($_REQUEST['btnAdd_to_list'])) {
	//print_r($_REQUEST);die();
	$Query = "SELECT * FROM shopping_list WHERE user_id = '" . $_SESSION["UID"] . "' AND sl_title = '" . $_REQUEST['sl_title'] . "'";
	//print($Query);die();
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) > 0) {
		$row = mysqli_fetch_object($rs);
		//header("Location: " . $_SERVER['PHP_SELF'] . "?supplier_id=" . $_REQUEST['supplier_id'] . "&op=14");
		header("Location: " . product_detail_url($supplier_id) . "/14");
	} else {
		$sl_id = getMaximum("shopping_list", "sl_id");
		mysqli_query($GLOBALS['conn'], "INSERT INTO shopping_list (sl_id, user_id, sl_title) VALUES (" . $sl_id . ", '" . $_SESSION["UID"] . "','" . dbStr(trim($_REQUEST['sl_title'])) . "')") or die(mysqli_error($GLOBALS['conn']));
		header("Location: " . product_detail_url($supplier_id) . "/1");
	}
}

include("includes/message.php");
?>
<!doctype html>
<html lang="de">

<head>
	<link rel="canonical" href="<?php print($GLOBALS['siteURL'] . $_REQUEST['product_params']); ?>">
	<script type="application/ld+json">
		{
			"@context": "https://schema.org/",
			"@type": "Product",
			"name": "<?php print(str_replace(array('"'), "", $pro_udx_seo_internetbezeichung)); ?>",
			"image": "<?php print($pg_mime_source_url); ?>",
			"description": "<?php print($pro_description_long_schema); ?>",
			"sku": "<?php print($pro_ean); ?>",
			"mpn": "<?php print($pro_ean); ?>",
			"brand": {
				"@type": "Brand",
				"name": "<?php print($manf_name); ?>"
			},
			"offers": {
				"@type": "Offer",
				"url": "<?php print($GLOBALS['siteURL'] . $_REQUEST['product_params']); ?>",
				"priceCurrency": "EUR",
				<?php print($price_schema); ?> "shippingDetails": [{
					"@type": "OfferShippingDetails",
					<?php print(($shipping_price_free > 0) ? '"name": "Free shipping",' : '') ?>
					<?php print(($shipping_price_free > 0) ? '"shippingLabel": "Free delivery",' : '') ?> "shippingRate": {
						"@type": "MonetaryAmount",
						"value": "<?php print(($shipping_price_free > 0) ? 0.00 : config_courier_fix_charges); ?>",
						"currency": "EUR"
					},
					"shippingDestination": {
						"@type": "DefinedRegion",
						"addressCountry": "DE"
					},
					"deliveryTime": {
						"@type": "ShippingDeliveryTime",
						"handlingTime": {
							"@type": "QuantitativeValue",
							"minValue": 1,
							"maxValue": 5,
							"unitCode": "d"
						},
						"transitTime": {
							"@type": "QuantitativeValue",
							"minValue": 3,
							"maxValue": 5,
							"unitCode": "d"
						}
					}
				}],
				"hasMerchantReturnPolicy": {
					"@type": "MerchantReturnPolicy",
					"returnPolicyCategory": "https://schema.org/MerchantReturnFiniteReturnWindow",
					"applicableCountry": "DE",
					"merchantReturnDays": 14,
					"returnMethod": "ReturnByMail",
					"returnFees": <?php print(($shipping_price_free > 0) ? '"FreeReturn"' : '"ReturnShippingFees"') ?>,
					<?php print($returnShippingFeesAmount); ?> "merchantReturnLink": "<?php print($GLOBALS['siteURL'] . "widerrufsbelehrung"); ?>"
				}
			}
		}
	</script>
	<?php include("includes/html_header.php"); ?>
</head>

<body style="background-color: #fff;">
	<div id="container" align="center">
		<!--HEADER_SECTION_START-->
		<?php include("includes/navigation.php"); ?>
		<!--HEADER_SECTION_END-->

		<!--BREADCRUMB_SECTION_START-->
		<div class="gerenric_breadcrumb">
			<div class="page_width_1480">
				<div class="breadcrumb_inner">
					<ul>
						<?php
						if ($pro_type > 0) {
							$cat_title_heading = returnName("cat_title_de AS cat_title", "category", "group_id", 20);
							$cat_params_heading = returnName("cat_params_de AS cat_params", "category", "group_id", 20);
						?>
							<li><a href="unterkategorien/<?php print($cat_params_heading); ?>" title="<?php print($cat_title_heading); ?>"> <?php print($cat_title_heading); ?> </a></li>
						<?php } else { ?>
							<li><a href="unterkategorien/<?php print($cat_one_params); ?>" title="<?php print($cat_title_one); ?>"> <?php print($cat_title_one); ?> </a></li>
						<?php } ?>
						<li><a href="artikelarten/<?php print($cat_two_params . $qryStrURL); ?>" title="<?php print($cat_title_two); ?>"> <?php print($cat_title_two); ?> </a></li>
						<li><a href="artikelarten/<?php print($cat_two_params . "/" . $cat_three_params . $qryStrURL); ?>" title="<?php print($cat_title_three); ?>"> <?php print($cat_title_three); ?> </a></li>
					</ul>
				</div>
			</div>
		</div>
		<!--BREADCRUMB_SECTION_END-->

		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="product_detail_page gerenric_padding">
				<div class="page_width_1480">
					<?php if ($class != "") { ?>
						<div class="<?php print($class); ?>"><?php print($strMSG); ?><a title="close" href="javascript:void(0);" class="close" data-dismiss="alert">×</a></div>
					<?php } ?>
					<div class="product_detail_section1">
						<div class="product_left">
							<style>



							</style>
							<div class="product_main_image">
								<article>
									<div class="simpleLens-gallery-container active" id="demo-1" align="center">
										<div class="thum_images">
											<div class="simpleLens-thumbnails-container">
												<?php
												//$Query = "SELECT pg_mime_source_url FROM `products_gallery` WHERE pro_id = '" . $pro_id . "' AND supplier_id = '" . $_REQUEST['supplier_id'] . "' AND pg_mime_purpose != 'data_sheet' AND pg_mime_purpose != 'others' ORDER BY CASE WHEN pg_mime_purpose = 'normal' THEN 1 ELSE 2 END";
												$Query = "SELECT pg_mime_source_url, pg_mime_description FROM `products_gallery` WHERE pro_id = '" . $pro_id . "' AND supplier_id = '" . $params_supplier_id . "' AND pg_mime_type = 'image/jpeg' ORDER BY CASE WHEN pg_mime_purpose = 'normal' THEN 1 ELSE 2 END";
												$rs = mysqli_query($GLOBALS['conn'], $Query);
												if (mysqli_num_rows($rs) > 0) {
													while ($row = mysqli_fetch_object($rs)) {
												?>
														<a href="#" role="button" onclick="return false;" class="simpleLens-thumbnail-wrapper" data-lens-image="<?php print(get_image_link(427, $row->pg_mime_source_url)); ?>" data-big-image="<?php print(get_image_link(427, $row->pg_mime_source_url)); ?>" title="<?php print($row->pg_mime_description); ?>"> <img src="<?php print(get_image_link(75, $row->pg_mime_source_url)); ?>" alt="<?php print($row->pg_mime_description); ?>"> </a>
												<?php
													}
												}
												?>
											</div>
										</div>
										<div class="large_image">
											<div class="simpleLens-container">
												<div class="simpleLens-big-image-container"> <a href="#" role="button" onclick="return false;" class="simpleLens-lens-image" data-lens-image="<?php print(get_image_link(427, $pg_mime_source_url)); ?>" title="<?php print($pg_mime_description); ?>"> <img src="<?php print(get_image_link(427, $pg_mime_source_url)); ?>" class="simpleLens-big-image" alt="<?php print($pg_mime_description); ?>"> </a> </div>
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
								<?php
								$count = 0;
								if ($pro_udx_seo_epag_id > 0) {
									$Query = "SELECT pf.*, pro.pro_udx_seo_epag_title_params_de, pg.pg_mime_source_url FROM products_feature AS pf LEFT OUTER JOIN products AS pro ON pro.supplier_id = pf.supplier_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pf.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pf.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_order ASC LIMIT 1) WHERE pf.pro_udx_seo_epag_id = '" . $pro_udx_seo_epag_id . "' AND pf.pf_fname = '" . $pro_udx_seo_selection_feature . "'";
									//print($Query);
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									$count = mysqli_num_rows($rs);
									if ($count > 1) {
										if (mysqli_num_rows($rs) > 0) {
											$pro_udx_seo_selection_feature_check = array('Farbe', 'Farbe der Rückenlehne', 'Schreibfarbe');
								?>
											<div class="pd_detail_shirt">
												<h2><?php print($pro_udx_seo_selection_feature); ?>: <span id="color_title"><?php print(returnName("pf_fvalue", "products_feature", "supplier_id", $supplier_id, "AND pf_fname = '" . $pro_udx_seo_selection_feature . "'")); ?></span> </h2>
												<ul>
													<?php while ($row = mysqli_fetch_object($rs)) { ?>
														<li>
															<input type="radio" class="color" id="color_<?php print($row->supplier_id); ?>" name="color_radio" value="<?php print($row->supplier_id); ?>" <?php print(($row->supplier_id == $supplier_id) ? 'checked' : ''); ?>>
															<label for="color_<?php print($row->supplier_id); ?>">
																<span style="<?php print(((in_array($pro_udx_seo_selection_feature, $pro_udx_seo_selection_feature_check)) ? 'height: 60px; width: 60px;' : 'height: 40px;min-width: 50px;border-radius: 5px;')); ?>" class="color_tab" id="color_tab_<?php print($row->supplier_id); ?>" data-id="<?php print($row->supplier_id); ?>" pro_udx_seo_epag_title_params_de="<?php print($row->pro_udx_seo_epag_title_params_de); ?>" data-title="<?php print($row->pf_fvalue); ?>">
																	<?php if (in_array($pro_udx_seo_selection_feature, $pro_udx_seo_selection_feature_check)) { ?>
																		<img src="<?php print(get_image_link(160, $row->pg_mime_source_url)); ?>" title="<?php print($row->pf_fvalue); ?>" alt="<?php print($row->pf_fvalue); ?>">
																	<?php } else { ?>
																		<label for="" title="<?php print($row->pf_fvalue); ?>"><?php print($row->pf_fvalue); ?></label>
																	<?php } ?>
																</span>
															</label>
														</li>
													<?php } ?>
												</ul>
											</div>
											<div class="btn_show feature-show-more">Mehr Produktdetails</div>
											<div class="btn_show feature-show-less" style="display: none;">Weniger Produktdetails</div>
								<?php
										}
									}
								} ?>
								<h4> <?php print($pro_description_short); ?> </h4>
								<?php 
								if(!empty($pg_mime_source_url_logo)){
									print('<img src="'.get_image_link(75, $pg_mime_source_url_logo).'" alt="logo">');
								}
								?>
								<ul>
									<li style="display: none;">Bestellnummer: <?php print($supplier_id); ?> </li>
									<li style="display: none;">Herstellernummer: <?php print($pro_manufacture_aid); ?></li>
									<li style="display: none;">GTIN: <?php print($pro_ean); ?> </li>
								</ul>
								<?php if (!empty($special_price)) { ?>
									<!-- <div class="product_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format($pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $pbp_price_without_tax, $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'><b>-</b> " . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> Pro St. 1 exkl. MwSt. </span>"); ?> </div>
									<div class="product_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format($pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $pbp_price_amount, $special_price['usp_discounted_value'], $pbp_tax)) . "€ <span class='pd_prise_discount_value'><b>-</b> " . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> <span>Pro St. 1 inkl. MwSt.</span> </span>"); ?> </div>-->
								<?php } else { ?>
									<!--<div class="product_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format($pbp_price_without_tax)); ?>€ <span>Pro St. 1 exkl. MwSt</span></div>
									<div class="product_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format($pbp_price_amount)); ?>€ <span>Pro St. 1 inkl. MwSt.</span></div>-->
								<?php } ?>
								<ul class="product_type">
									<?php
									$Query = "SELECT pf_fname, pf_fvalue FROM `products_feature` WHERE pro_id = '" . $pro_id . "' AND supplier_id = '" . $params_supplier_id . "' ORDER BY pf_forder ASC";
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
									<li>
										<p> <?php print($pro_description_long); ?> </p> <!-- product_info -->
									</li>
								</ul>
								<div class="btn_show product-type-show-more">Mehr Produktdetails</div>
								<div class="btn_show product-type-show-less" style="display: none;">Weniger Produktdetails</div>
							</div>
							<div class="product_col2">
								<?php if ($pq_physical_quantity > 0) { ?>
									<div class="tab_radio_button">
										<div class="tab_radio_col">
											<input type="radio" class="cart_type_online" id="cart_type_online_<?php print($pro_id); ?>" data-id="<?php print($pro_id); ?>" data-supplier-id="<?php print($supplier_id); ?>" pro_udx_seo_epag_title_params_de="<?php print($pro_udx_seo_epag_title_params_de); ?>" name="cart_type_option_<?php print($pro_id); ?>" value="0" <?php print((($ci_type == 0) ? 'checked' : '')); ?>>
											<label for="cart_type_online_<?php print($pro_id); ?>">
												<?php if (isset($_SESSION['plz']) && !empty($_SESSION['plz'])) {
													print(getShippingTiming($_SESSION['plz']));
												} else {
													print("Lieferung " . date('d-m-Y', strtotime("+7 day", strtotime(date_time))));
												} ?>
											</label>
										</div>
										<div class="tab_radio_col">
											<input type="radio" class="cart_type_physical" id="cart_type_physical_<?php print($pro_id); ?>" data-id="<?php print($pro_id); ?>" data-supplier-id="<?php print($supplier_id); ?>" pro_udx_seo_epag_title_params_de="<?php print($pro_udx_seo_epag_title_params_de); ?>" name="cart_type_option_<?php print($pro_id); ?>" value="1" <?php print((($ci_type == 1) ? 'checked' : '')); ?>>
											<label for="cart_type_physical_<?php print($pro_id); ?>">Marktabholung heute ab <?php print(date('H:i', strtotime("+1 hour"))); ?> Uhr</label>
										</div>
									</div>
								<?php } ?>
								<div class="sticky">
									<?php
									$Query = "SELECT pbp_lower_bound, (pbp_price_amount + (pbp_price_amount * pbp_tax)) AS pbp_price_amount,  pbp_price_amount AS pbp_price_without_tax, (pbp_special_price_amount + (pbp_special_price_amount * pbp_tax)) AS pbp_special_price_amount, pbp_special_price_amount AS pbp_special_price_without_tax, pbp_tax FROM products_bundle_price WHERE pro_id = '" . $pro_id . "' AND supplier_id = '" . $params_supplier_id . "' ORDER BY pbp_lower_bound DESC LIMIT 1";
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										$row = mysqli_fetch_object($rs);
										if (!empty($special_price)) {
									?>
											<div class="main_staffel_price_discount price_without_tex"> <del><?php print(price_format(((config_site_special_price > 0 && $row->pbp_special_price_without_tax > 0) ? $row->pbp_special_price_without_tax : $row->pbp_price_without_tax))); ?> €</del> <span>- <?php print($special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%')); ?></span></div>
											<div class="main_staffel_price_discount pbp_price_with_tex"> <del><?php print(price_format(((config_site_special_price > 0 && $row->pbp_special_price_amount > 0) ? $row->pbp_special_price_amount : $row->pbp_price_amount))); ?></del> <span>- <?php print($special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%')); ?></span></div>
											<div class="main_staffel_price price_without_tex" <?php print($price_without_tex_display); ?>><span><?php print(price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $row->pbp_special_price_without_tax > 0) ? $row->pbp_special_price_without_tax : $row->pbp_price_without_tax), $special_price['usp_discounted_value']))); ?> €</span></div>
											<div class="main_staffel_price pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><span><?php print(price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $row->pbp_special_price_amount > 0) ? $row->pbp_special_price_amount : $row->pbp_price_amount), $special_price['usp_discounted_value'], $row->pbp_tax))); ?> €</span></div>
										<?php } else { ?>
											<div class="main_staffel_price price_without_tex" <?php print($price_without_tex_display); ?>><span><?php print(price_format(((config_site_special_price > 0 && $row->pbp_special_price_without_tax > 0) ? $row->pbp_special_price_without_tax : $row->pbp_price_without_tax))); ?> €</span></div>
											<div class="main_staffel_price pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><span><?php print(price_format(((config_site_special_price > 0 && $row->pbp_special_price_amount > 0) ? $row->pbp_special_price_amount : $row->pbp_price_amount))); ?> €</span></div>
										<?php } ?>
										<div class="main_staffel_price_quantity"><?php print(($row->pbp_lower_bound > 1) ? 'Staffelpreis (ab ' . $row->pbp_lower_bound . ' Stück)' : "Sonderpreis"); ?> </div>
										<div class="product_vat txt_align_right price_without_tex" <?php print($price_without_tex_display); ?>>exkl. MwSt + <span class="versand_trigger <?php print($versand_txt_color); ?>"><?php print($versand_txt); ?></span></div>
										<div class="product_vat txt_align_right pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>>inkl. MwSt + <span class="versand_trigger <?php print($versand_txt_color); ?>"><?php print($versand_txt); ?></span></div>
										<?php
									}
									$Query = "SELECT pbp_lower_bound, (pbp_price_amount + (pbp_price_amount * pbp_tax)) AS pbp_price_amount,  pbp_price_amount AS pbp_price_without_tax, (pbp_special_price_amount + (pbp_special_price_amount * pbp_tax)) AS pbp_special_price_amount, pbp_special_price_amount AS pbp_special_price_without_tax, pbp_tax FROM `products_bundle_price` WHERE pro_id = '" . $pro_id . "' AND supplier_id = '" . $params_supplier_id . "' ORDER BY pbp_lower_bound ASC";
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										while ($row = mysqli_fetch_object($rs)) {
											if (!empty($special_price)) {
										?>
												<div class="piece_prise txt_align_right price_without_tex" <?php print($price_without_tex_display); ?>>Ab <?php print($row->pbp_lower_bound); ?> Stück <?php print("<del class='orignal_price'>" . price_format(((config_site_special_price > 0 && $row->pbp_special_price_without_tax > 0) ? $row->pbp_special_price_without_tax : $row->pbp_price_without_tax)) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $row->pbp_special_price_without_tax > 0) ? $row->pbp_special_price_without_tax : $row->pbp_price_without_tax), $special_price['usp_discounted_value'])) . "€  </span>"); ?> </div>
												<div class="piece_prise txt_align_right pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>>Ab <?php print($row->pbp_lower_bound); ?> Stück <?php print("<del class='orignal_price'>" . price_format(((config_site_special_price > 0 && $row->pbp_special_price_amount > 0) ? $row->pbp_special_price_amount : $row->pbp_price_amount)) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $row->pbp_special_price_amount > 0) ? $row->pbp_special_price_amount : $row->pbp_price_amount), $special_price['usp_discounted_value'], $row->pbp_tax)) . "€ </span> "); ?> </div>
											<?php } else { ?>
												<div class="piece_prise txt_align_right price_without_tex" <?php print($price_without_tex_display); ?>>Ab <?php print($row->pbp_lower_bound); ?> Stück <span><?php print(price_format(((config_site_special_price > 0 && $row->pbp_special_price_without_tax > 0) ? $row->pbp_special_price_without_tax : $row->pbp_price_without_tax))); ?>€</span></div>
												<div class="piece_prise txt_align_right pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>>Ab <?php print($row->pbp_lower_bound); ?> Stück <span><?php print(price_format(((config_site_special_price > 0 && $row->pbp_special_price_amount > 0) ? $row->pbp_special_price_amount : $row->pbp_price_amount))); ?>€</span></div>
									<?php
											}
										}
									}
									$quantity_lenght = 0;
									$Query = "SELECT * FROM products_quantity WHERE supplier_id = '" . dbStr(trim($supplier_id)) . "'";
									//print($Query);
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									$ci_qty_type = 0;
									if (mysqli_num_rows($rs) > 0) {
										$row = mysqli_fetch_object($rs);
										$pq_quantity = $row->pq_quantity;
										$pq_upcomming_quantity = $row->pq_upcomming_quantity;
										$pq_physical_quantity = $row->pq_physical_quantity;
										$pq_status = $row->pq_status;
										if ($pq_status == 'true') {
											$ci_qty_type = 1;
										}
										if ($ci_type > 0) {
											$quantity_lenght = $pq_physical_quantity;
											print('<div class="product_order_title green"> ' . $pq_physical_quantity . ' Stück sofort verfügbar</div>');
										} elseif (($pq_quantity == 0 || $pq_quantity < 0) && $pq_status == 'true') {
											$quantity_lenght = $pq_upcomming_quantity;
											print('<div class="product_order_title green"> ' . $pq_upcomming_quantity . ' Stück kurfristig lieferbar</div>');
										} elseif ($pq_quantity > 0 && $pq_status == 'false') {
											$quantity_lenght = $pq_quantity;
											print('<div class="product_order_title green"> ' . $pq_quantity . ' Stück sofort verfügbar</div>');
										} elseif (($pq_quantity == 0 || $pq_quantity < 0) && $pq_status == 'false') {
											print('<div class="product_order_title">Auf Anfrage</div>');
										}
									} else {
										if ($pro_type > 0) {
											$quantity_lenght = 1;
										} else {
											print('<div class="product_order_title red">Auf Anfrage</div>');
										}
									}
									?>
									<div class="product_order_row">
										<div class="product_order_row_inner">
											<div class="order_select">
												<div class="drop-down_2">
													<div class="selected">
														<div class="qtu_slt">Menge:</div>
														<a title="1" href="#" role="button" onclick="return false;"><span>1</span></a>
													</div>
													<div class="options">
														<ul>
															<?php for ($i = 1; $i <= $quantity_lenght; $i++) { ?>
																<li><a href="#" role="button" onclick="return false;" class="quantity" id="quantity_<?php print($i); ?>" data-id="<?php print($i); ?>" title="<?php print($i); ?>"><?php print($i); ?></a></li>
															<?php } ?>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="order_btn">
										<input type="hidden" id="pro_id_<?php print($pro_id); ?>" name="pro_id" value="<?php print($pro_id); ?>">
										<input type="hidden" id="pro_type_<?php print($pro_id); ?>" name="pro_type" value="<?php print($pro_type); ?>">
										<input type="hidden" id="supplier_id_<?php print($pro_id); ?>" name="supplier_id" value="<?php print($supplier_id); ?>">
										<input type="hidden" id="ci_type_<?php print($pro_id); ?>" name="ci_type" value="<?php print($ci_type); ?>">
										<input type="hidden" id="ci_discount_type_<?php print($pro_id); ?>" name="ci_discount_type" value="<?php print((!empty($special_price)) ? $special_price['usp_price_type'] : '0'); ?>">
										<input type="hidden" id="ci_discount_value_<?php print($pro_id); ?>" name="ci_discount_value" value="<?php print((!empty($special_price)) ? $special_price['usp_discounted_value'] : '0'); ?>">
										<input type="hidden" id="ci_qty_<?php print($pro_id); ?>" name="ci_qty" value="1">
										<input type="hidden" id="ci_qty_type_<?php print($pro_id); ?>" name="ci_qty_type" value="<?php print($ci_qty_type); ?>">
										<a title="In den Einkaufswagen" class="<?php print(($quantity_lenght > 0) ? 'add_to_card' : ''); ?>" href="#" role="button" onclick="return false;" data-id="<?php print($pro_id); ?>">
											<div class="gerenric_btn">In den Einkaufswagen</div>
										</a>
									</div>
									<?php if ($discounted_price > 0) { ?>
										<div class="manufacturer_detail margin_10">
											<div class="popup_manufacturer_detail">
												<p>Da wir Ihnen diesen Artikel zu einem überaus günstigen Preis anbieten, ist eine Zugabe von Gratis-Artikeln nicht möglich.</p>
											</div>
											<a href="javascript:void(0);" title="Keine Geschenke möglich"><i class="fa fa-info-circle"></i> Keine Geschenke möglich </a>
										</div>
									<?php } ?>
									<div class="best_nr">Best.-Nr.: <?php print($pro_manufacture_aid); ?></div>
									<div class="product_shippment">
										<div class="shippment_text"><span>Versand</span> Wacker 24</div>
										<div class="shippment_text">
											<a title="Lieferung an Standort aktualisieren" href="#" role="button" onclick="return false;"><i class="fa fa-map-marker"></i>
												<div class="location_text location_trigger"> Lieferung an Standort aktualisieren</div>
											</a>
											<?php if (isset($_SESSION['plz']) && !empty($_SESSION['plz'])) { ?>
												<!--<div class="location_text location_trigger">Lieferung PLZ: <?php print($_SESSION['plz']); ?></div>-->
												<div class="location_text location_trigger"><?php print(getShippingTiming($_SESSION['plz'])); ?></div>
											<?php } ?>
										</div>
									</div>
									<div class="product_create_liste">
										<div id="alert_wishlist" style="display: none;"><span id="alert_wishlist_txt"></span><a title="close" href="#" role="button" onclick="return false;" class="close" data-dismiss="alert">×</a></div>
										<div class="drop-down">
											<div class="selected <?php print(isset($_SESSION["UID"]) ? 'show' : ''); ?>">
												<a title="Auf die Liste" href=" <?php print(isset($_SESSION["UID"]) ? 'javascript:void(0)' : 'login.php'); ?>"><span>Auf die Liste</span></a>
											</div>
											<?php if (isset($_SESSION["UID"])) { ?>
												<div class="options">
													<ul>
														<?php
														$count = 0;
														$Query = "SELECT * FROM shopping_list WHERE user_id = '" . $_SESSION["UID"] . "' ORDER BY sl_id ASC";
														$rs = mysqli_query($GLOBALS['conn'], $Query);
														if (mysqli_num_rows($rs) > 0) {
															while ($row = mysqli_fetch_object($rs)) {
																$count++;
														?>
																<li><a href="#" role="button" onclick="return false;" class="addwhishlist" data-id="<?php print($row->sl_id); ?>" title="<?php print($row->sl_title); ?>"><?php print($row->sl_title); ?></a></li>
														<?php
															}
														}
														?>
														<li>
															<div class="create_other_list create_list_trigger">Eine neue Liste erstellen</div>
														</li>
													</ul>
												</div>
											<?php } ?>
										</div>
									</div>
									<style>

									</style>
									<div class="info_link_detailpage">
										<?php if (!empty($pg_mime_source_url_pdf)) { ?>
											<a target="_blank" href="<?php print($pg_mime_source_url_pdf); ?>" title="<?php print($pg_mime_description); ?>"><i class="fa fa-info-circle"></i> <?php print($pg_mime_description); ?></a>
										<?php }
										if (!empty($pro_udx_manufacturer_address)) { ?>
											<div class="manufacturer_detail">
												<div class="popup_manufacturer_detail">
													<p><?php print($pro_udx_manufacturer_address); ?></p>
													<p><?php print($pro_udx_manufacturer_mail); ?></p>
												</div>
												<a href="javascript:void(0);" title="Herstellerinformationen"><i class="fa fa-info-circle"></i> Herstellerinformationen </a>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="product_detail_section2">
						<div class="gerenric_white_box gray_bg">
							<div class="gerenric_product full_column">
								<h2>Ähnliche Produkte</h2>
								<div class="gerenric_slider">
									<?php
									$Query = "SELECT * FROM vu_category_map AS cm  WHERE cm.cat_id = '" . $cat_id_three . "' ORDER BY  RAND() LIMIT 0,12";
									//print($Query);die();
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										while ($row = mysqli_fetch_object($rs)) {
											//if (isset($_SESSION["UID"]) && $_SESSION["UID"] > 0) {
											$cat_id_two = substr($cat_id_three, 0, 3);
											$cat_id_one = returnName("parent_id", "category", "group_id", $cat_id_two);
											/*$special_price = user_special_price("level_two", $cat_id_two);
											if (!$special_price) {
												$special_price = user_special_price("level_one", $cat_id_one);
											}*/

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
											<div>
												<div class="pd_card">
													<div class="pd_image"><a href="<?php print(product_detail_url($row->supplier_id)); ?>" title="<?php print($row->pro_udx_seo_internetbezeichung); ?>"><img loading="lazy" src="<?php print(get_image_link(75, $row->pg_mime_source_url)); ?>" alt="<?php print($row->pro_udx_seo_internetbezeichung); ?>"></a></div>
													<div class="pd_detail">
														<h5><a href="<?php print(product_detail_url($row->supplier_id)); ?>" title="<?php print($row->pro_udx_seo_internetbezeichung); ?>"> <?php print($row->pro_udx_seo_epag_title); ?> </a></h5>
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
															<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format(((config_site_special_price > 0 && $row->pbp_special_price_amount > 0) ? $row->pbp_special_price_amount : $row->pbp_price_amount)) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $row->pbp_special_price_amount) ? $row->pbp_special_price_amount : $row->pbp_price_amount), $special_price['usp_discounted_value'], $row->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
														<?php } else { ?>
															<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format(((config_site_special_price > 0 && $row->pbp_special_price_without_tax > 0) ? $row->pbp_special_price_without_tax : $row->pbp_price_without_tax))); ?>€</div>
															<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format(((config_site_special_price > 0 && $row->pbp_special_price_amount > 0) ? $row->pbp_special_price_amount : $row->pbp_price_amount))); ?>€</div>
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
				<div class="product_detail_section2 margin_top_30">
					<div class="gerenric_white_box padding_left_right_10">
						<div class="gerenric_product mostviewed full_column">
							<h2>Ähnliche Produkte</h2>
							<div class="gerenric_slider_mostviewed">
								<?php
								$Query = "SELECT * FROM vu_category_map AS cm WHERE cm.supplier_id != '" . $supplier_id . "' AND cm.cat_id = '" . $cat_id_three . "' ORDER BY  RAND() LIMIT 0,12";
								//print($Query);die();
								$rs = mysqli_query($GLOBALS['conn'], $Query);
								if (mysqli_num_rows($rs) > 0) {
									while ($row = mysqli_fetch_object($rs)) {
										//if (isset($_SESSION["UID"]) && $_SESSION["UID"] > 0) {
										$cat_id_two = substr($cat_id_three, 0, 3);
										$cat_id_one = returnName("parent_id", "category", "group_id", $cat_id_two);
										/*$special_price = user_special_price("level_two", $cat_id_two);
										if (!$special_price) {
											$special_price = user_special_price("level_one", $cat_id_one);
										}*/
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
										<div>
											<div class="pd_card txt_align_left">
												<div class="pd_image"><a href="<?php print(product_detail_url($row->supplier_id)); ?>" title="<?php print($row->pro_udx_seo_internetbezeichung); ?>"><img loading="lazy" src="<?php print(get_image_link(75, $row->pg_mime_source_url)); ?>" alt="<?php print($row->pro_udx_seo_internetbezeichung); ?>"></a></div>
												<div class="pd_detail">
													<h5><a href="<?php print(product_detail_url($row->supplier_id)); ?>" title="<?php print($row->pro_udx_seo_internetbezeichung); ?>"> <?php print($row->pro_udx_seo_epag_title); ?> </a></h5>
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
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format(((config_site_special_price > 0 && $row->pbp_special_price_without_tax > 0) ? $row->pbp_special_price_without_tax : $row->pbp_price_without_tax)) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $row->pbp_special_price_without_tax) ? $row->pbp_special_price_without_tax : $row->pbp_price_without_tax), $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format(((config_site_special_price > 0 && $row->pbp_special_price_amount > 0) ? $row->pbp_special_price_amount : $row->pbp_price_amount)) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $row->pbp_special_price_amount) ? $row->pbp_special_price_amount : $row->pbp_price_amount), $special_price['usp_discounted_value'], $row->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
													<?php } else { ?>
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format(((config_site_special_price > 0 && $row->pbp_special_price_without_tax > 0) ? $row->pbp_special_price_without_tax : $row->pbp_price_without_tax))); ?>€</div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format(((config_site_special_price > 0 && $row->pbp_special_price_amount > 0) ? $row->pbp_special_price_amount : $row->pbp_price_amount))); ?>€</div>
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
				<div class="product_detail_section2">
					<div class="gerenric_white_box padding_left_right_10">
						<div class="gerenric_product mostviewed full_column">
							<h2>Ähnliche Produkte</h2>
							<div class="gerenric_slider_mostviewed">
								<?php
								$Query = "SELECT * FROM vu_category_map AS cm WHERE cm.supplier_id != '" . $supplier_id . "' AND cm.cat_id = '" . $cat_id_three . "' ORDER BY  RAND() LIMIT 0,12";
								//print($Query);die();
								$rs = mysqli_query($GLOBALS['conn'], $Query);
								if (mysqli_num_rows($rs) > 0) {
									while ($row = mysqli_fetch_object($rs)) {
										//if (isset($_SESSION["UID"]) && $_SESSION["UID"] > 0) {
										$cat_id_two = substr($cat_id_three, 0, 3);
										$cat_id_one = returnName("parent_id", "category", "group_id", $cat_id_two);
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
										<div>
											<div class="pd_card txt_align_left">
												<div class="pd_image"><a href="<?php print(product_detail_url($row->supplier_id)); ?>" title="<?php print($row->pro_udx_seo_internetbezeichung); ?>"><img loading="lazy" src="<?php print(get_image_link(75, $row->pg_mime_source_url)); ?>" alt="<?php print($row->pro_udx_seo_internetbezeichung); ?>"></a></div>
												<div class="pd_detail">
													<h5><a href="<?php print(product_detail_url($row->supplier_id)); ?>" title="<?php print($row->pro_udx_seo_internetbezeichung); ?>"> <?php print($row->pro_udx_seo_epag_title); ?> </a></h5>
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
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . ((config_site_special_price > 0 && $row->pbp_special_price_without_tax > 0) ? $row->pbp_special_price_without_tax : $row->pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $row->pbp_special_price_without_tax) ? $row->pbp_special_price_without_tax : $row->pbp_price_without_tax), $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . ((config_site_special_price > 0 && $row->pbp_special_price_amount > 0) ? $row->pbp_special_price_amount : $row->pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $row->pbp_special_price_amount) ? $row->pbp_special_price_amount : $row->pbp_price_amount), $special_price['usp_discounted_value'], $row->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
													<?php } else { ?>
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format(((config_site_special_price > 0 && $row->pbp_special_price_without_tax > 0) ? $row->pbp_special_price_without_tax : $row->pbp_price_without_tax))); ?>€</div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format(((config_site_special_price > 0 && $row->pbp_special_price_amount > 0) ? $row->pbp_special_price_amount : $row->pbp_price_amount))); ?>€</div>
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
		</section>
		<!--CONTENT_SECTION_END-->

		<!--FOOTER_SECTION_START-->
		<div id="scroll_top">Zurück zum Seitenanfang</div>
		<?php include("includes/footer.php"); ?>
		<!--FOOTER_SECTION_END-->

	</div>

	<!--LOCATION_POPUP_START-->
	<?php include("includes/popup.php"); ?>
	<!--LOCATION_POPUP_END-->
	<!--CREATE_LIST_POPUP_START-->
	<div class="create_list_popup list_popup">
		<div class="inner_popup">
			<form class="create_list_content" name="frm" id="frmaddress" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
				<div class="create_list_heading">Liste erstellen <div class="create_list_close"><i class="fa fa-times"></i></div>
				</div>
				<div class="create_list_content_inner">
					<p>Listenname (erforderlich)</p>
					<input type="text" class="input_list" required name="sl_title" id="sl_title">
					<div class="create_button">
						<button class="gerenric_btn" type="submit" name="btnAdd_to_list">hinzufügen</button>
						<div class="gerenric_btn popup_close">Abbrechen</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<!--CREATE_LIST_POPUP_END-->
	<!--CREATE_LIST_POPUP_START-->
	<div class="popup versand_popup">
		<div class="popup_inner wd_60">
			<div class="popup_content">
				<div class="popup_heading">Versand & Verpackung <div class="popup_close"><i class="fa fa-times"></i></div>
				</div>
				<div class="popup_content_inner">
					<div class="popup_inner_container">
						<p>Lieferland: <strong>Deutschland</strong></p>
						<h2 class="green">Versandkostenfrei <span>ab 66,39 € zzgl. MwSt. (79,00 € inkl. MwSt.) Warenwert*</span></h2>
						<p>unter 66,39 € (79,00 € inkl. MwSt.) Warenwert: 4,76 € Verpackungspauschale + 4,75 € Versandkosten (je Auftrag)</p>
						<div class="underline"></div>
						<p>Folgende Kosten können optional je nach Auftrag anfallen:</p>
						<p>Maximalgewicht:</p>
						<p>Bis zu 31 kg: Sendungen, die das maximale Gewicht oder die maximale Größe unserer Versanddienstleister überschreiten, müssen gegebenenfalls per Spedition oder als Sperrgut verschickt werden. Die Lieferzeit kann sich bei schweren oder sperrigen Sendungen verlängern.</p>
						<p>Inselzuschlag:</p>
						<p>Für die Lieferung auf deutsche Inseln fallen 15,70 € pro Paket (max. 31 kg) an.</p>
						<p>Dies gilt für folgende PLZ: 18565, 25849, 25859, 25863, 25869, 25938, 25939, 25946, 25980, 25981, 25982, 25983, 25984, 25985, 25986, 25987, 25988, 25989, 25990, 25992, 25991, 25993, 25994, 25995, 25996, 25997, 25998, 25999, 26465, 26474, 26486, 26548, 26571, 26579, 26757, 27498, 27499</p>
						<p>Versanddienstleister:</p>
						<p>Ihre Bestellung wird im Standardversand mit DHL versendet. </p>
						<p>* Wenn Sie über unsere Gratis Geschenke "Versandkostenfrei" in den Warenkorb legen. Gilt abzüglich Warenwert aus Aktionsartikeln.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--CREATE_LIST_POPUP_END-->
</body>
<link rel="stylesheet" type="text/css" href="css/jquery.simpleLens.min.css">
<link rel="stylesheet" type="text/css" href="css/jquery.simpleGallery.min.css">
<script defer type="text/javascript" src="js/jquery.simpleGallery.min.js"></script>
<script defer type="text/javascript" src="js/jquery.simpleLens.min.js"></script>
<script>
	$(document).ready(function() {
		$('#demo-1 .simpleLens-thumbnails-container img').simpleGallery({
			loading_image: 'demo/images/loading.gif'
		});

		$('#demo-1 .simpleLens-big-image').simpleLens({
			loading_image: 'demo/images/loading.gif'
		});
	});
	$(window).on('resize', function() {
		if (window.innerWidth <= 1024 && window.innerWidth >= 240) {
			// Code for responsive action
			$(".popup_inner").css('width', '90%');
		}
	});
	$(".cart_type_online").on("click", function() {
		//console.log("cart_type_online");
		$("#ci_type_" + $(this).attr("data-id")).val(0);
		let supplier_id = $(this).attr("data-supplier-id");
		let pro_udx_seo_epag_title_params_de = $(this).attr("pro_udx_seo_epag_title_params_de");
		window.location.href = pro_udx_seo_epag_title_params_de + '-' + supplier_id;
	});
	$(".cart_type_physical").on("click", function() { //ci_type
		$("#ci_type_" + $(this).attr("data-id")).val(1);
		let supplier_id = $(this).attr("data-supplier-id");
		let pro_udx_seo_epag_title_params_de = $(this).attr("pro_udx_seo_epag_title_params_de");
		//window.location.href = "product/1/" + supplier_id + "/" + pro_description;
		window.location.href = "1/" + pro_udx_seo_epag_title_params_de + '-' + supplier_id;
	});
</script>
<script src="js/slick.js"></script>
<script type="text/javascript">
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
<script>
	/*$(".product-type-show-more").click(function() {
		$(".product_info, .product_type").slideToggle(1500);

		if ($(this).text() === "Mehr Produktdetails") {
			$(this).text("Weniger Produktdetails");
		} else {
			$(this).text("Mehr Produktdetails");
		}
	});*/
	$(function() {
		let show_record = 14;
		$(".product_col1 .pd_detail_shirt ul li").slice(0, show_record).show();
		let product_col1 = $(".product_col1 .pd_detail_shirt ul li:hidden").length;
		if (product_col1 == 0) {
			$(".feature-show-more").hide();
		}
		$(".product_col1 .pd_detail_shirt ul li").slice(show_record).hide();
		$("body").on('click touchstart', '.feature-show-more', function(e) {
			e.preventDefault();

			//$(".product_col1 .pd_detail_shirt ul li:hidden").slice(0, show_record).slideDown("slow");
			$(".product_col1 .pd_detail_shirt ul li:hidden").slideDown("slow");
			if ($(".product_col1 .pd_detail_shirt ul li:hidden").length == 0) {
				$(".feature-show-more").hide();
				$(".feature-show-less").show();
			}
		});
		$("body").on('click touchstart', '.feature-show-less', function(e) {
			e.preventDefault();
			$(".product_col1 .pd_detail_shirt ul li").slice(show_record).slideUp("slow", function() {
				$(".feature-show-more").show();
				$(".feature-show-less").hide();
			});
		});

	});

	$(function() {
		let show_record = 3;

		$(".product_col1 .product_type li").hide().slice(0, show_record).show();

		if ($(".product_col1 .product_type li:hidden").length === 0) {
			$(".product-type-show-more").hide();
		}

		$("body").on('click touchstart', '.product-type-show-more', function(e) {
			e.preventDefault();

			//$(".product_col1 .product_type li:hidden").slice(0, show_record).slideDown("slow");
			$(".product_col1 .product_type li:hidden").slideDown("slow");

			if ($(".product_col1 .product_type li:hidden").length === 0) {
				$(".product-type-show-more").hide();
				$(".product-type-show-less").show();
			}
		});

		$("body").on('click touchstart', '.product-type-show-less', function(e) {
			e.preventDefault();

			$(".product_col1 .product_type li").slice(show_record).slideUp("slow", function() {
				// Scroll back to the top of the product list
				$('html, body').animate({
					scrollTop: $(".product_col1").offset().top - 100 // Adjust offset as needed
				}, 600);
			});

			$(".product-type-show-more").show();
			$(".product-type-show-less").hide();
		});
	});
</script>
<script>
	$(window).load(function() {
		/*2 popup 1 hide 1 show*/
		$(".create_list_trigger").click(function() {
			$('.list_popup').show();
			$('.list_popup').resize();
			$('body').css({
				'overflow': 'hidden'
			});
		});
		$('.create_list_close').click(function() {
			$('.list_popup').hide();
			$('body').css({
				'overflow': 'inherit'
			});
		});
		$('.popup_close').click(function() {
			$('.popup').hide();
			$('body').css({
				'overflow': 'inherit'
			});
		});

		$(".versand_trigger").click(function() {
			$('.versand_popup').show();
			$('.versand_popup').resize();
			$('body').css({
				'overflow': 'hidden'
			});
		});

	});
</script>
<script>
	//TOGGLING NESTED ul
	$(".drop-down .show a").click(function() {
		$(".drop-down .options ul").toggle();
	});

	//SELECT OPTIONS AND HIDE OPTION AFTER SELECTION
	$(".drop-down .options ul li a").click(function() {
		var text = $(this).html();
		$(".drop-down .show a span").html(text);
		$(".drop-down .options ul").hide();
	});


	//HIDE OPTIONS IF CLICKED ANYWHERE ELSE ON PAGE
	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (!$clicked.parents().hasClass("drop-down"))
			$(".drop-down .options ul").hide();
	});
</script>

<script>
	$(".addwhishlist").on("click", function() {
		let supplier_id = '<?php print($supplier_id); ?>';
		let sl_id = $(this).attr("data-id");
		//console.log("supplier_id:"+supplier_id+" sl_id: "+sl_id);
		$.ajax({
			url: 'ajax_calls.php?action=addwhishlist',
			method: 'POST',
			data: {
				supplier_id: supplier_id,
				sl_id: sl_id
			},
			success: function(response) {
				//console.log("response = "+response);
				const obj = JSON.parse(response);
				//console.log(obj);
				if (obj.status == 1) {
					$("#alert_wishlist_txt").text(obj.message);
					$("#alert_wishlist").removeClass("alert alert-danger");
					$("#alert_wishlist").removeClass("alert alert-success");
					$("#alert_wishlist").addClass(obj.class);
					$("#alert_wishlist").show();
				}
			}
		});
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

	$(".color_tab").on("mouseover", function() {
		let color_title = $(this).attr('data-title');
		//console.log("color_tab: "+color_title);
		$("#color_title").text(color_title);
	});
	$(".color_tab").on("mouseout", function() {
		let color_radio = $('input[name="color_radio"]:checked').val();;
		let color_title = $("#color_tab_" + color_radio).attr('data-title');
		//console.log("mouseout: "+color_title);
		$("#color_title").text(color_title);
	});

	$(".color_tab").on("click", function() {
		let supplier_id = $(this).attr("data-id");
		let pro_udx_seo_epag_title_params_de = $(this).attr("pro_udx_seo_epag_title_params_de");
		//console.log("pro_description: "+pro_udx_seo_epag_title_params_de+'-'+supplier_id);
		window.location.href = pro_udx_seo_epag_title_params_de + '-' + supplier_id;
	});
	$(".quantity").on("click", function() {
		//let quantity = $(this).attr("data-id");
		//console.log("quantity: "+quantity);
		//console.log("quantity:");
		$("#ci_qty_" + <?php print($pro_id); ?>).val($(this).attr("data-id"));
	});
</script>

</html>