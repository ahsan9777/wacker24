<?php

include("includes/php_includes_top.php");
$Query1 = "SELECT cnt.cnt_id, cnt.cnt_slug, cnt.cnt_section, cnt.cnt_title_de AS cnt_title, cnt.cnt_heading_de AS cnt_heading, cnt.cnt_details_de AS cnt_details, cnt.cnt_image, cnt.cnt_banner_image  FROM contents AS cnt WHERE cnt.cnt_slug = '" .$_REQUEST['product_params']. "'";
//print($Query1);die();
$rs1 = mysqli_query($GLOBALS['conn'], $Query1);
if (mysqli_num_rows($rs1) > 0) {
    require_once 'page.php';
    exit;
}
//print_r($_REQUEST);die();
$pro_id = 0;
$supplier_id = 0;
$pro_custom_add = 0;
$cat_id_one = 0;
$cat_id_two = 0;
$pbp_price_amount = 0;
$quantity_status = "";
$pro_udx_seo_internetbezeichung = "";
$pg_mime_source_url = "";
$pro_description_long_schema = "";
$pro_ean = "";
$manf_name = "";
$pro_type = 0;
$cat_one_params = "";
$cat_title_one = "";
$cat_two_params = "";
$cat_title_two = "";
$cat_three_params = "";
$cat_title_three = "";
$pg_mime_description = "";
$pg_mime_description_pdf = "";
$pg_mime_source_url_logo = "";
$pg_mime_source_url_pdf = "";
$gallery_images = array();
$bundle_prices = array();
$cat_title_heading = "";
$cat_params_heading = "";
$pro_udx_seo_epag_id = "";
$pro_description_short = "";
$pro_description_long = "";
$pro_manufacture_aid = "";
$pbp_price_without_tax = 0;
$pbp_price_amount = 0;
$pq_physical_quantity = 0;
$pq_upcomming_quantity = 0;
$pq_quantity = 0;
$pq_status = 'false';
$cat_id_three = 0;
$ci_type = 0;
if (isset($_REQUEST['ci_type']) && $_REQUEST['ci_type'] > 0) {
	$ci_type = 1;
}
$product_params = explode("-", $_REQUEST['product_params']);
//$params_supplier_id =  end($product_params);
$params_supplier_id = returnName("supplier_id", "products", "pro_id", end($product_params));
//$params_supplier_id = returnName("supplier_id", "products", "pro_url", $_REQUEST['product_params'], "AND pro_status = '1'");
$Query = "SELECT pro.*, pbp.pbp_id, pro.pro_ean, manf.manf_name, manf.manf_name_params, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pbp.pbp_price_amount AS pbp_price_without_tax, (pbp.pbp_special_price_amount + (pbp.pbp_special_price_amount * pbp.pbp_tax)) AS pbp_special_price_amount, pbp.pbp_special_price_amount AS pbp_special_price_without_tax, pbp.pbp_tax, cm.cat_id AS cat_id_three, cm.sub_group_ids, c.cat_title_de AS cat_title_three, c.cat_params_de AS cat_three_params FROM products AS pro LEFT OUTER JOIN manufacture AS manf ON manf.manf_id = pro.manf_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = pro.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN category_map AS cm ON cm.supplier_id = pro.supplier_id LEFT OUTER JOIN category AS c ON c.group_id = cm.cat_id WHERE pro.pro_status = '1' AND pro.supplier_id = '" . $params_supplier_id . "'";
//print($Query);//die();
$rs = mysqli_query($GLOBALS['conn'], $Query);
if (mysqli_num_rows($rs) > 0) {
	$row = mysqli_fetch_object($rs);

	$pro_id = $row->pro_id;
	$manf_name = $row->manf_name;
	$manf_name_params = $row->manf_name_params;
	$pro_type = $row->pro_type;
	if ($pro_type > 0) {
		$qryStrURL = "/" . $pro_type;
	}
	$supplier_id = $row->supplier_id;
	$pro_custom_add = $row->pro_custom_add;
	$pro_udx_seo_internetbezeichung = $row->pro_udx_seo_internetbezeichung;
	$pro_udx_seo_internetbezeichung_params_de = $row->pro_udx_seo_internetbezeichung_params_de;
	$pro_udx_seo_epag_title = $row->pro_udx_seo_epag_title;
	$pro_udx_seo_epag_title_params_de = $row->pro_udx_seo_epag_title_params_de;
	$page_title = $pro_udx_seo_epag_title.' | Wacker Buerocenter';
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
	$pro_udx_seo_pk = $row->pro_udx_seo_pk;
	$pro_udx_seo_selection_feature = $row->pro_udx_seo_selection_feature;
	$pro_price_quantity = $row->pro_price_quantity;
	$pro_quantity_min = $row->pro_quantity_min;
	$pro_quantity_interval = $row->pro_quantity_interval;
	$pbp_price_amount = $row->pbp_price_amount;
	$pbp_price_without_tax = $row->pbp_price_without_tax;
	$pro_url = $row->pro_url;
	if (config_site_special_price > 0 && $row->pbp_special_price_amount > 0) {
		$pbp_price_amount = $row->pbp_special_price_amount;
		$pbp_price_without_tax = $row->pbp_special_price_without_tax;
	}
	$pbp_id = $row->pbp_id;
	$pbp_tax = $row->pbp_tax;
	$ci_amount = $pbp_price_without_tax;

	$Query_pg = "SELECT pro_id, pg_mime_source_url, pg_mime_description, pg_mime_purpose, pg_mime_type FROM products_gallery WHERE supplier_id = '" . dbStr(trim($supplier_id)) . "' ORDER BY CASE WHEN pg_mime_purpose = 'normal' THEN 1 ELSE 2 END, pg_mime_order ASC";
	$rs_pg = mysqli_query($GLOBALS['conn'], $Query_pg);
	if ($rs_pg && mysqli_num_rows($rs_pg) > 0) {
		while ($row_pg = mysqli_fetch_object($rs_pg)) {
			if ($row_pg->pg_mime_purpose == 'normal' && $pg_mime_source_url === "") {
				$pg_mime_source_url = $row_pg->pg_mime_source_url;
				$pg_mime_description = $row_pg->pg_mime_description;
			}
			if ($row_pg->pg_mime_purpose == 'logo' && $pg_mime_source_url_logo === "") {
				$pg_mime_source_url_logo = $row_pg->pg_mime_source_url;
			}
			if ($row_pg->pg_mime_type == 'application/pdf' && $pg_mime_source_url_pdf === "") {
				$pg_mime_source_url_pdf = $row_pg->pg_mime_source_url;
				$pg_mime_description_pdf = $row_pg->pg_mime_description;
			}
			if ($row_pg->pro_id == $pro_id && $row_pg->pg_mime_type == 'image/jpeg') {
				$gallery_images[] = $row_pg;
			}
		}
	}
	if ($pg_mime_source_url === "" && !empty($gallery_images)) {
		$pg_mime_source_url = $gallery_images[0]->pg_mime_source_url;
		$pg_mime_description = $gallery_images[0]->pg_mime_description;
	}

	$sub_group_ids = explode(",", $row->sub_group_ids);
	//print_r($sub_group_ids);
	$cat_id_one = isset($sub_group_ids[1]) ? $sub_group_ids[1] : 0;
	$cat_id_two = isset($sub_group_ids[0]) ? $sub_group_ids[0] : 0;
	$meta_keywords = implode(", ", returnNameArray("pk_title", "products_keyword", "supplier_id", $supplier_id));
	$meta_description = $pro_udx_seo_internetbezeichung;
	$cat_lookup_ids = array_unique(array_filter(array(intval($cat_id_one), intval($cat_id_two), 20)));
	if (!empty($cat_lookup_ids)) {
		$rs_cats = mysqli_query($GLOBALS['conn'], "SELECT group_id, cat_title_de, cat_params_de FROM category WHERE group_id IN (" . implode(",", $cat_lookup_ids) . ")");
		if ($rs_cats) {
			while ($row_cat = mysqli_fetch_object($rs_cats)) {
				if ($row_cat->group_id == $cat_id_one) {
					$cat_title_one = $row_cat->cat_title_de;
					$cat_one_params = $row_cat->cat_params_de;
				}
				if ($row_cat->group_id == $cat_id_two) {
					$cat_title_two = $row_cat->cat_title_de;
					$cat_two_params = $row_cat->cat_params_de;
				}
				if ($row_cat->group_id == 20) {
					$cat_title_heading = $row_cat->cat_title_de;
					$cat_params_heading = $row_cat->cat_params_de;
				}
			}
		}
	}
	$cat_id_three = $row->cat_id_three;
	$cat_title_three = $row->cat_title_three;
	$cat_three_params = $row->cat_three_params;
	$pro_udx_manufacturer_address = $row->pro_udx_manufacturer_address;
	$pro_udx_manufacturer_mail = $row->pro_udx_manufacturer_mail;
	//$pro_udate = date("Y-m-d", strtotime($row->pro_udate));

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

	$Query_pbp = "SELECT pbp_lower_bound, (pbp_price_amount + (pbp_price_amount * pbp_tax)) AS pbp_price_amount, pbp_price_amount AS pbp_price_without_tax, (pbp_special_price_amount + (pbp_special_price_amount * pbp_tax)) AS pbp_special_price_amount, pbp_special_price_amount AS pbp_special_price_without_tax, pbp_tax FROM products_bundle_price WHERE pro_id = '" . $pro_id . "' AND supplier_id = '" . $params_supplier_id . "' ORDER BY pbp_lower_bound ASC";
	$rs_pbp = mysqli_query($GLOBALS['conn'], $Query_pbp);
	if ($rs_pbp && mysqli_num_rows($rs_pbp) > 0) {
		while ($row_pbp = mysqli_fetch_object($rs_pbp)) {
			$bundle_prices[] = $row_pbp;
		}
	}

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
if (!empty($special_price) && !empty($bundle_prices)) {
	$row_special_price = end($bundle_prices);
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

/*$result = getStockData($supplier_id, 1);
echo "<pre>";
print_r($result);
echo "</pre>";
$getQuantity = getQuantity($supplier_id, $pro_custom_add);//820123781

echo "<pre>";
print_r($getQuantity);
echo "</pre>";*/
//print('quantity: '.$result['availability']['quantity']);

include("includes/message.php");
?>
<!doctype html>
<html lang="de">

<head>
	<link rel="canonical" href="<?php print($GLOBALS['siteURL'] . $_REQUEST['product_params']); ?>">
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@graph": [

			{
				"@type": "WebPage",
				"@id": "<?php print($GLOBALS['siteURL'] . $pro_url); ?>#webpage",
				"url": "<?php print($GLOBALS['siteURL'] . $pro_url); ?>",
				"name": "<?php print(str_replace('"', '', $pro_udx_seo_internetbezeichung)); ?>",
				"description": "<?php print($pro_description_long_schema); ?>",
				"inLanguage": "de-DE",
				"breadcrumb": {
					"@id": "<?php print($GLOBALS['siteURL'] . $pro_url); ?>#breadcrumb"
				},
				"mainEntity": {
					"@id": "<?php print($GLOBALS['siteURL'] . $pro_url); ?>#product"
				}
			},

			{
				"@type": "BreadcrumbList",
				"@id": "<?php print($GLOBALS['siteURL'] . $pro_url); ?>#breadcrumb",
				"itemListElement": [
					{
						"@type": "ListItem",
						"position": 1,
						"name": "Startseite",
						"item": "<?php print($GLOBALS['siteURL']); ?>"
					},
					{
						"@type": "ListItem",
						"position": 2,
						"name": "<?php print($cat_title_three); ?>",
						"item": "<?php print($GLOBALS['siteURL'] . "produkte/".$cat_one_params . "/" . $cat_two_params . "/" . $cat_three_params); ?>"
					},
					{
						"@type": "ListItem",
						"position": 3,
						"name": "<?php print(str_replace('"', '', $pro_udx_seo_internetbezeichung)); ?>",
						"item": "<?php print($GLOBALS['siteURL'] . $pro_url); ?>"
					}
				]
			},

			{
				"@type": "Product",
				"@id": "<?php print($GLOBALS['siteURL'] . $pro_url); ?>#product",

				"name": "<?php print(str_replace('"', '', $pro_udx_seo_internetbezeichung)); ?>",

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

					"url": "<?php print($GLOBALS['siteURL'] . $pro_url); ?>",

					"priceCurrency": "EUR",

					<?php print($price_schema); ?>

					"shippingDetails": [
						{
							"@type": "OfferShippingDetails",

							<?php
							print(
								($shipping_price_free > 0) ? '"name": "Free shipping",' : ''
							);
							?>

							<?php
							print(
								($shipping_price_free > 0) ? '"shippingLabel": "Free delivery",' : ''
							);
							?>

							"shippingRate": {
								"@type": "MonetaryAmount",
								"value": "<?php
									print(
										($shipping_price_free > 0)
										? 0.00
										: config_courier_fix_charges
									);
								?>",
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
						}
					],

					"hasMerchantReturnPolicy": {
						"@type": "MerchantReturnPolicy",

						"returnPolicyCategory":
							"https://schema.org/MerchantReturnFiniteReturnWindow",

						"applicableCountry": "DE",

						"merchantReturnDays": 14,

						"returnMethod": "ReturnByMail",

						"returnFees":
							<?php print( ($shipping_price_free > 0) ? '"FreeReturn"' : '"ReturnShippingFees"' ); ?>,

							<?php print($returnShippingFeesAmount); ?>

						"merchantReturnLink": "<?php print($GLOBALS['siteURL'] . 'widerrufsbelehrung'); ?>"
					}
				}
			}

		]
	}
	</script>
	<link rel="preconnect" href="https://bilddaten.privatepilot.de" crossorigin>
	<link rel="dns-prefetch" href="//bilddaten.privatepilot.de">
	<?php if (!empty($pg_mime_source_url)) { ?>
	<link rel="preload" as="image" href="<?php print(get_image_link(427, $pg_mime_source_url)); ?>" fetchpriority="high">
	<?php } ?>
	<link rel="stylesheet" type="text/css" href="css/jquery.simpleLens.min.css">
	<link rel="stylesheet" type="text/css" href="css/jquery.simpleGallery.min.css">
	<?php include("includes/html_header.php"); ?>
	<style>
		.product_detail_page .product_detail_section1 .product_left {
			width: 50%;
		}

		/* Prevent CLS before lazy Slick initialization */
		.gerenric_slider:not(.slick-initialized),
		.gerenric_slider_mostviewed:not(.slick-initialized) {
			display: flex;
			overflow: hidden;
			gap: 12px;
			width: 100%;
			box-sizing: border-box;
		}
		.gerenric_slider:not(.slick-initialized) > div {
			flex: 0 0 calc(16.666% - 10px);
			max-width: calc(16.666% - 10px);
			min-width: 0;
			box-sizing: border-box;
		}
		.gerenric_slider_mostviewed:not(.slick-initialized) > div {
			flex: 0 0 calc(10% - 11px);
			max-width: calc(10% - 11px);
			min-width: 0;
			box-sizing: border-box;
		}
		.gerenric_slider:not(.slick-initialized) .pd_card,
		.gerenric_slider_mostviewed:not(.slick-initialized) .pd_card {
			width: 100% !important;
			min-width: 0;
			box-sizing: border-box;
		}

		.product_detail_page .product_detail_section1 .product_right {
			width: 50%;
		}

		.product_detail_page .product_detail_section1 .product_left .product_main_image .simpleLens-lens-image img {
			object-fit: contain;
			height: auto;
			max-height: 450px;
		}

		
		/* full width grey background */
		.special-price-ad {
			width: 100%;
			border-top: 1px solid #c7c7c7;
			border-bottom: 1px solid #c7c7c7;
			background: linear-gradient(90deg, #e5e5e5, #f3f3f3, #e5e5e5);
		}

		/* centered content */
		.special-price-ad-inner {
			max-width: 75%;
			margin: auto;
			display: flex;
			align-items: center;
			justify-content: space-between;
			padding: 0px 12%;
			background-color: #fff;
			border-radius: 100px 100px 35px 35px;
			cursor: pointer;
		}

		/* left section */
		.special-price-ad-inner-left {
			display: flex;
			align-items: center;
			gap: 35px;
		}

		.brand {
			height: 60px;
		}

		.product {
			height: 60px;
		}

		/* text */
		.special-price-ad-inner-text {
			display: flex;
			flex-direction: column;
			align-items: flex-start;
		}

		.special-price-ad-inner-text .small {
			display: block;
			font-size: 14px;
			color: #273445;
			font-weight: 500;
		}

		.special-price-ad-inner-text p {
			font-size: 14px;
			color: #007185;
			text-decoration: none;
			border-top: 1px solid #000;
		}

		.special-price-ad-inner-text a:hover {
			text-decoration: underline;
		}

		/* right section */
		.special-price-ad-inner-right {
			display: flex;
			align-items: center;
			gap: 15px;
		}

		/* price */
		.price {
			text-align: right;
		}

		.price strong:first-child, .price p:nth-of-type(1){
			display: none;
		}

		.price strong {
			font-size: 16px;
			font-weight: 700;
			color: #212529;
		}

		.price p {
			display: block;
			font-size: 11px;
			color: #666;
			margin: 0;
		}
		

		/* coupon */
		.text-green {
			background: #ff1c0a;
			padding: 3px 7px;
			border-radius: 4px;
			font-size: 12px;
			font-weight: bold;
			color: #fff;
		}

		.coupon-text {
			font-size: 12px;
			color: #444;
		}

		/* button */
		.buy-btn {
			background: #ffca44;
			border: 1px solid #ffca44;
			padding: 5px 20px;
			border-radius: 5px;
			font-size: 14px;
			cursor: pointer;
		}

		.buy-btn:hover {
			background: #eeb31e;
		}

		@media screen and (max-width:1024px) and (min-width:240px) {
			.product_detail_page .product_detail_section1 .product_left {
				width: 100% !important;
				margin-bottom: 30px;
			}
			.product_detail_page .product_detail_section1 .product_right {
				width: 100% !important;
				padding-left: 0;
			}
			.gerenric_slider:not(.slick-initialized) > div,
			.gerenric_slider_mostviewed:not(.slick-initialized) > div {
				flex: 0 0 calc(25% - 9px);
				max-width: calc(25% - 9px);
			}
			.special-price-ad-inner{
				flex-direction: column;
				border-radius: 0;
				gap: 10px;
				width: 100%;
				max-width: 100%;
				padding: 12px 15px;
				box-sizing: border-box;
			}
			.special-price-ad-inner-text{
				align-items: center;
				text-align: center;
			}
			.special-price-ad-inner-left{
				gap: 15px;
				flex-wrap: wrap;
				justify-content: center;
			}
			.special-price-ad-inner-right{
				flex-wrap: wrap;
				justify-content: center;
			}
		}
		@media screen and (max-width:650px) and (min-width:240px) {
			.gerenric_slider:not(.slick-initialized) > div,
			.gerenric_slider_mostviewed:not(.slick-initialized) > div {
				flex: 0 0 calc(33.333% - 8px);
				max-width: calc(33.333% - 8px);
			}
		}
		@media screen and (max-width:480px) and (min-width:240px) {
			.gerenric_slider:not(.slick-initialized) > div,
			.gerenric_slider_mostviewed:not(.slick-initialized) > div {
				flex: 0 0 calc(50% - 6px);
				max-width: calc(50% - 6px);
			}
		}
	</style>
</head>

<body style="background-color: #fff;">
	<div id="container" align="center">
		<!--HEADER_SECTION_START-->
		<?php include("includes/navigation.php"); ?>
		<!--HEADER_SECTION_END-->
		<?php
		$Query_special = "SELECT usp.*, pro.pro_type, pro.pro_url, pro.pro_udx_seo_epag_title, pro.pro_description_long, (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pro.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_order ASC LIMIT 1) AS pg_mime_source_url, (SELECT pg_logo.pg_mime_source_url FROM products_gallery AS pg_logo WHERE pg_logo.supplier_id = pro.supplier_id AND pg_logo.pg_mime_purpose = 'logo' LIMIT 1) AS manf_file, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pbp.pbp_price_amount AS pbp_price_without_tax, pbp.pbp_tax, pro.manf_id, m.manf_name, m.manf_name_params FROM (SELECT * FROM user_special_price WHERE usp_featured = '1' AND supplier_id != '" . $supplier_id . "' ORDER BY RAND() LIMIT 0,1) AS usp INNER JOIN products AS pro ON pro.supplier_id = usp.supplier_id AND pro.pro_status = '1' LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = pro.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN manufacture AS m ON m.manf_id = pro.manf_id";
		$rs_special = mysqli_query($GLOBALS['conn'], $Query_special);
		if($rs_special && mysqli_num_rows($rs_special) > 0){
			$rws = mysqli_fetch_object($rs_special);
			$manf_file = $rws->manf_file;
		?>
		<div class="special-price-ad">
			<a href="<?php print(product_detail_url($rws->supplier_id, 0, $rws->pro_url)); ?>">
				<div class="special-price-ad-inner">

					<div class="special-price-ad-inner-left">
						<img src="<?php print(get_image_link(75,$manf_file));?>" class="brand" alt="brand" decoding="async">
						<img src="<?php print(get_image_link(75,$rws->pg_mime_source_url));?>" class="product" alt="product" decoding="async">
					</div>
					<div class="special-price-ad-inner-text">
						<span class="small"><?php print(limit_text($rws->pro_udx_seo_epag_title, 70));?></span>
						<p>
							<?php print(limit_text($rws->pro_description_long, 70));?>
						</p>
					</div>
					<div class="special-price-ad-inner-right">
						<div class="price">
							<strong class="special_price_tag price_without_tex" <?php print($price_without_tex_display); ?> ><?php print(price_format(discounted_price($rws->usp_price_type, $rws->pbp_price_without_tax, $rws->usp_discounted_value))); ?>€</strong>
							<strong class="special_price_tag pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?> ><?php print(price_format(discounted_price($rws->usp_price_type, $rws->pbp_price_amount, $rws->usp_discounted_value, $rws->pbp_tax)) ); ?>€</strong>
							<p class="price_without_tex" <?php print($price_without_tex_display); ?> >exkl. MwSt.</p>
							<p class="pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?> >inkl. MwSt.</p>
						</div>
						<div class="coupon">
							<span class="text-green">Sparen <?php print($rws->usp_discounted_value.(($rws->usp_price_type > 0) ? '€' : '%')) ?></span>
						</div>
						<button class="buy-btn">Jetzt kaufen</button>
					</div>

				</div>
			</a>
		</div>
		<?php } ?>
		<!--BREADCRUMB_SECTION_START-->
		<div class="gerenric_breadcrumb">
			<div class="page_width_1480">
				<div class="breadcrumb_inner">
					<ul>
						<?php
						if ($pro_type > 0) {
						?>
							<li><a href="kategorie/<?php print($cat_params_heading); ?>" title="<?php print($cat_title_heading); ?>"> <?php print($cat_title_heading); ?> </a></li>
						<?php } else { ?>
							<li><a href="kategorie/<?php print($cat_one_params); ?>" title="<?php print($cat_title_one); ?>"> <?php print($cat_title_one); ?> </a></li>
						<?php } ?>
						<li><a href="produkte/<?php print($cat_one_params . "/" . $cat_two_params . $qryStrURL); ?>" title="<?php print($cat_title_two); ?>"> <?php print($cat_title_two); ?> </a></li>
						<li><a href="produkte/<?php print($cat_one_params . "/" . $cat_two_params . "/" . $cat_three_params . $qryStrURL); ?>" title="<?php print($cat_title_three); ?>"> <?php print($cat_title_three); ?> </a></li>
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
							<div class="product_main_image">
								<article>
									<div class="simpleLens-gallery-container active" id="demo-1" align="center">
										<div class="thum_images">
											<div class="simpleLens-thumbnails-container">
												<?php
												if (!empty($gallery_images)) {
													foreach ($gallery_images as $row) {
												?>
														<a href="#" role="button" onclick="return false;" class="simpleLens-thumbnail-wrapper" data-lens-image="<?php print(get_image_link(2000, $row->pg_mime_source_url)); ?>" data-big-image="<?php print(get_image_link(427, $row->pg_mime_source_url)); ?>" title="<?php print($row->pg_mime_description); ?>"> <img src="<?php print(get_image_link(75, $row->pg_mime_source_url)); ?>" width="80" height="80" decoding="async" alt="<?php print($row->pg_mime_description); ?>"> </a>
												<?php
													}
												}
												?>
											</div>
										</div>
										<div class="large_image">
											<div class="simpleLens-container">
												<div class="simpleLens-big-image-container"> <a href="#" role="button" onclick="return false;" class="simpleLens-lens-image" data-lens-image="<?php print(get_image_link(2000, $pg_mime_source_url)); ?>" title="<?php print($pg_mime_description); ?>"> <img src="<?php print(get_image_link(427, $pg_mime_source_url)); ?>" fetchpriority="high" decoding="async" class="simpleLens-big-image" alt="<?php print($pg_mime_description); ?>"> </a> </div>
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
									$pro_udx_seo_selection_feature_check = array('Farbe', 'Farbe der Rückenlehne', 'Schreibfarbe', 'Schreibfarbe des Stempels', 'Farbe des Griffes', 'Farbe der Sitzfläche', 'Farbe des Fußkreuzes', 'Farbe des Korpus', 'Farbe des Gestells', 'Farbe der Front', 'Farbe des Deckels');
									$is_image_feature = in_array($pro_udx_seo_selection_feature, $pro_udx_seo_selection_feature_check);
									$pg_feature_select = $is_image_feature ? "(SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pf.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_order ASC LIMIT 1) AS pg_mime_source_url," : "'' AS pg_mime_source_url,";
									$Query = "SELECT pf.*, pro.pro_udx_seo_epag_title_params_de, pro.pro_ean, " . $pg_feature_select . " pro.pro_url FROM products_feature AS pf INNER JOIN products AS pro ON pro.supplier_id = pf.supplier_id WHERE pro.pro_status = '1' AND pf.pro_udx_seo_epag_id = '" . $pro_udx_seo_epag_id . "' AND pf.pf_fname = '" . $pro_udx_seo_selection_feature . "'";
									//print($Query);
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									$count = $rs ? mysqli_num_rows($rs) : 0;
									if ($count > 1) {
										$feature_rows = array();
										$active_feature_value = "";
										while ($row_f = mysqli_fetch_object($rs)) {
											$feature_rows[] = $row_f;
											if ($row_f->supplier_id == $supplier_id) {
												$active_feature_value = $row_f->pf_fvalue;
											}
										}
										if ($active_feature_value === "" && !empty($feature_rows)) {
											$active_feature_value = $feature_rows[0]->pf_fvalue;
										}
								?>
											<div class="pd_detail_shirt">
												<h2><?php print($pro_udx_seo_selection_feature); ?>: <span id="color_title"><?php print($active_feature_value); ?></span> </h2>
												<ul>
													<?php foreach ($feature_rows as $f_idx => $row) {
														$is_hidden_feature = ($f_idx >= 14);
													?>
														<li style="display: <?php print($is_hidden_feature ? 'none' : 'inline-block'); ?>;">
															<input type="radio" class="color" id="color_<?php print($row->supplier_id); ?>" name="color_radio" value="<?php print($row->supplier_id); ?>" <?php print(($row->supplier_id == $supplier_id) ? 'checked' : ''); ?>>
															<label for="color_<?php print($row->supplier_id); ?>">
																<span style="<?php print(($is_image_feature ? 'height: 90px; width: 90px;' : 'height: 40px;min-width: 50px;border-radius: 5px;')); ?>" class="color_tab" id="color_tab_<?php print($row->supplier_id); ?>" data-id="<?php print($row->pro_ean); ?>" pro_udx_seo_epag_title_params_de="<?php print($row->pro_udx_seo_epag_title_params_de); ?>" pro_url="<?php print($row->pro_url); ?>" data-title="<?php print($row->pf_fvalue); ?>">
																	<?php if ($is_image_feature) {
																		$swatch_img_url = get_image_link(75, $row->pg_mime_source_url);
																		if ($is_hidden_feature) { ?>
																			<img class="js_lazy_feature_img" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?php print($swatch_img_url); ?>" width="80" height="80" decoding="async" title="<?php print($row->pf_fvalue); ?>" alt="<?php print($row->pf_fvalue); ?>">
																		<?php } else { ?>
																			<img src="<?php print($swatch_img_url); ?>" width="80" height="80" loading="lazy" decoding="async" title="<?php print($row->pf_fvalue); ?>" alt="<?php print($row->pf_fvalue); ?>">
																		<?php } ?>
																	<?php } else { ?>
																		<label for="" title="<?php print($row->pf_fvalue.' '.$row->pf_funit); ?>"><?php print($row->pf_fvalue.' '.$row->pf_funit); ?></label>
																	<?php } ?>
																</span>
															</label>
														</li>
													<?php } ?>
												</ul>
											</div>
											<?php if ($count > 14) { ?>
											<div class="btn_show feature-show-more">Mehr Produktdetails</div>
											<div class="btn_show feature-show-less" style="display: none;">Weniger Produktdetails</div>
											<?php } ?>
								<?php
									}
								} ?>
								<h4> <?php print($pro_description_short); ?> </h4>
								<?php
								if (!empty($pg_mime_source_url_logo)) {
									print('<a href = "'.$GLOBALS['siteURL'].'marken/'.$manf_name_params.'"><img src="' . get_image_link(75, $pg_mime_source_url_logo) . '" alt="logo"></a>');
								}
								?>
								<ul>
									<li style="display: none;">Bestellnummer: <?php print($supplier_id); ?> </li>
									<li style="display: none;">Herstellernummer: <?php print($pro_manufacture_aid); ?></li>
									<li style="display: none;">GTIN: <?php print($pro_ean); ?> </li>
								</ul>
								<style>
									.product_attribute {
										width: 100%;
										margin-bottom: 15px;
									}

									.product_attribute_title {
										font-size: 16px;
										font-weight: 400;
										line-height: 1.4;
										margin: 0 0 8px 0;
										color: #111;
									}

									.product_attribute_options {
										list-style: none;
										padding: 0 !important;
										margin: 0 !important;
										border: none !important;
									}

									.product_attribute_options li {
										display: inline-block;
										margin: 0;
									}

									/* Hide original radio button */
									.product_attribute_radio {
										position: absolute;
										opacity: 0;
										pointer-events: none;
									}

									/* Value box */
									.product_attribute_label {
										display: inline-block;
										cursor: pointer;
									}

									.product_attribute_value {
										display: inline-flex;
										align-items: center;
										justify-content: center;

										min-width: 100px;
										min-height: 40px;
										padding: 6px 12px;

										border: 2px solid #8b1111;
										border-radius: 5px;

										background: #fff;
										color: #111;

										font-size: 14px;
										font-weight: 600;
										line-height: 1.2;

										box-sizing: border-box;
										transition: all 0.2s ease;
									}

									/* Selected option */
									.product_attribute_radio:checked + .product_attribute_label .product_attribute_value {
										border-color: #8b1111;
										background: #fff;
									}

									/* Hover */
									.product_attribute_label:hover .product_attribute_value {
										background: #f8f8f8;
									}
								</style>
								<?php 
								$Query = "SELECT * FROM `products_feature` WHERE pf_fvalue_details = 'FILTER' AND supplier_id = '".$supplier_id."'";
								$rs = mysqli_query($GLOBALS['conn'], $Query);
								if(mysqli_num_rows($rs) > 0){
								?>
								<div class="pd_detail_shirts">
									<?php 
									while($row = mysqli_fetch_object($rs)){
									?>
									
									<div class="product_attribute">
										<h2 class="product_attribute_title">
											<?php print($row->pf_fname); ?>
										</h2>

										<ul class="product_attribute_options">
											<li>
												<input 
													type="radio" 
													class="product_attribute_radio product_filter" 
													id="product_attribute_<?php print($row->pf_id); ?>" 
													name="product_filter_radio" 
													value="<?php print($row->pf_id); ?>" 
													checked
												>

												<label 
													for="product_attribute_<?php print($row->pf_id); ?>" 
													class="product_attribute_label"
												>
													<span 
														class="product_attribute_value product_filter_option"
														id="product_attribute_value_<?php print($row->pf_id); ?>"
														title="<?php print($row->pf_fvalue.' '.$row->pf_funit); ?>"
													>
														<?php print($row->pf_fvalue.' '.$row->pf_funit); ?>
													</span>
												</label>
											</li>
										</ul>
									</div>

									<?php } ?>
								</div>
								<?php
								}
								?>
							</div>
							<div class="product_col2">
								<?php if ($pq_physical_quantity > 0) { ?>
									<div class="tab_radio_button">
										<div class="tab_radio_col">
											<input type="radio" class="cart_type_online" id="cart_type_online_<?php print($pro_id); ?>" data-id="<?php print($pro_id); ?>" data-supplier-id="<?php print($supplier_id); ?>" pro_udx_seo_epag_title_params_de="<?php print($pro_udx_seo_epag_title_params_de); ?>" pro_url="<?php print($pro_url); ?>" name="cart_type_option_<?php print($pro_id); ?>" value="0" <?php print((($ci_type == 0) ? 'checked' : '')); ?>>
											<label for="cart_type_online_<?php print($pro_id); ?>">
												<?php if (isset($_SESSION['plz']) && !empty($_SESSION['plz'])) {
													print(getShippingTiming($_SESSION['plz']));
												} else {
													print("Lieferung " . date('d-m-Y', strtotime("+7 day", strtotime(date_time))));
												} ?>
											</label>
										</div>
										<div class="tab_radio_col">
											<input type="radio" class="cart_type_physical" id="cart_type_physical_<?php print($pro_id); ?>" data-id="<?php print($pro_id); ?>" data-supplier-id="<?php print($supplier_id); ?>" pro_udx_seo_epag_title_params_de="<?php print($pro_udx_seo_epag_title_params_de); ?>" pro_url="<?php print($pro_url); ?>" name="cart_type_option_<?php print($pro_id); ?>" value="1" <?php print((($ci_type == 1) ? 'checked' : '')); ?>>
											<label for="cart_type_physical_<?php print($pro_id); ?>">Marktabholung heute ab <?php print(date('H:i', strtotime("+1 hour"))); ?> Uhr</label>
										</div>
									</div>
								<?php } ?>
								<div class="sticky">
									<?php
									if (!empty($bundle_prices)) {
										$row = end($bundle_prices);
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
										foreach ($bundle_prices as $row) {
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
									$ci_qty_type = 0;
									if($pro_type == 0) {
										if (config_site_quantity_source > 0 && $pro_custom_add == 0) {
											$getQuantity = getQuantity($supplier_id, $pro_custom_add);
											$pq_quantity = $getQuantity['pq_quantity'];
											$pq_upcomming_quantity = $getQuantity['pq_upcomming_quantity'];
											$pq_physical_quantity = $getQuantity['pq_physical_quantity'];
											$pq_status = $getQuantity['pq_status'];
										}
										if ($pq_status == 'true') {
											$ci_qty_type = 1;
										}
										if ($ci_type > 0) {
											$quantity_lenght = $pq_physical_quantity;
											print('<div class="product_order_title green"> ' . $pq_physical_quantity . ' Stück sofort verfügbar</div>');
										} elseif (($pq_quantity == 0 || $pq_quantity < 0) && $pq_status == 'true') {
											$quantity_lenght = $pq_upcomming_quantity;
											print('<div class="product_order_title green"> ' . $pq_upcomming_quantity . ' Stück Kurzfristig lieferbar</div>');
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
									if ($quantity_lenght > 500) {
										$quantity_lenght = 500;
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
											<a href="#" title="Keine Geschenke möglich"><i class="fa fa-info-circle"></i> Keine Geschenke möglich </a>
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
											<a target="_blank" href="<?php print($pg_mime_source_url_pdf); ?>" title="<?php print($pg_mime_description_pdf); ?>"><i class="fa fa-info-circle"></i> <?php print($pg_mime_description_pdf); ?></a>
										<?php }
										if (!empty($pro_udx_manufacturer_address)) { ?>
											<div class="manufacturer_detail">
												<div class="popup_manufacturer_detail">
													<p><?php print($pro_udx_manufacturer_address); ?></p>
													<p><?php print($pro_udx_manufacturer_mail); ?></p>
												</div>
												<a href="#" title="Herstellerinformationen"><i class="fa fa-info-circle"></i> Herstellerinformationen </a>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
						<style>
							.product_specifications {
								width: 100%;
								margin: 0;
								padding: 0;
							}

							.product_specification_row {
								display: grid;
								grid-template-columns: 55% 45%;
								min-height: 40px;
								border-bottom: 1px solid #bfc4c8;
								box-sizing: border-box;
							}

							/* Alternate row background */
							.product_specification_row:nth-child(even) {
								background: #eeeeee;
							}

							.product_specification_row:nth-child(odd) {
								background: #ffffff;
							}

							.product_specification_name,
							.product_specification_value {
								padding: 8px 4px;
								box-sizing: border-box;
								font-size: 16px;
								line-height: 1.45;
								min-width: 0;
								overflow-wrap: break-word;
								word-break: break-word;
							}

							/* Left side */
							.product_specification_name {
								color: #555;
								font-weight: 600;
							}

							/* Right side */
							.product_specification_value {
								color: #777;
								font-weight: 400;
							}

							/* Mobile */
							@media (max-width: 767px) {
								.product_specification_row {
									grid-template-columns: 45% 55%;
								}

								.product_specification_name,
								.product_specification_value {
									font-size: 14px;
									padding: 7px 4px;
								}
							}

							.product_details_info {
								width: 100%;
								max-width: 100%;
								color: #777;
								font-family: Arial, sans-serif;
							}

							/* Heading */
							.product_details_heading {
								margin: 0 0 12px 0;
								padding: 0;
								font-size: 28px;
								line-height: 1.2;
								font-weight: 300;
								color: #8a8a8a;
							}

							.product_details_heading strong {
								color: #40505b;
								font-weight: 700;
							}

							.product_details_heading span {
								color: #9a9a9a;
								font-weight: 300;
							}

							/* Bullet list */
							.product_details_list {
								margin: 0 0 22px 0;
								padding-left: 24px;
							}

							.product_details_list li {
								margin: 0;
								padding: 2px 0;
								color: #777;
								font-size: 16px;
								line-height: 1.45;
								font-weight: 400;
							}

							/* Description */
							.product_details_description {
								margin-top: 20px;
							}

							.product_details_description p {
								margin: 0;
								color: #777;
								font-size: 16px;
								line-height: 1.65;
							}

							/* Contact */
							.product_details_contact {
								margin-top: 28px;
							}

							.product_details_contact p {
								margin: 0 0 3px 0;
								color: #777;
								font-size: 16px;
								line-height: 1.6;
							}
							.product_detail_container{width: 100%; box-sizing: border-box; margin-top: 20px; display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 20px;}
							.product_detail_container > .product_col1{width: 100% !important; padding-right: 0 !important; min-width: 0; box-sizing: border-box;}
							.product_details_description p, .product_details_contact p{overflow-wrap: break-word; word-break: break-word;}

							/* Mobile */
							@media (max-width: 767px) {
								.product_detail_container{display: flex; flex-direction: column; gap: 15px; width: 100%;}
								.product_details_heading {
									font-size: 22px;
								}

								.product_details_list {
									padding-left: 20px;
								}

								.product_details_list li,
								.product_details_description p,
								.product_details_contact p {
									font-size: 14px;
								}
							}
							
						</style>
						<div class="product_detail_container">
							<div class="product_col1">
								<div class="product_details_info">
									<h2 class="product_details_heading">
										<strong>PRODUKTDETAILS</strong>
										<span>/ MEHR INFORMATIONEN</span>
									</h2>

									<!-- <ul class="product_details_list">
										<?php 
										//while($row = mysqli_fetch_object($rs)){
										?>
											<li><?php //print($row->pf_fvalue); ?></li>
										<?php //} ?>
									</ul>-->

									<div class="product_details_description">
										<p>
											<?php print($pro_description_long); ?>
										</p>
									</div>

									<div class="product_details_contact">
										<p><?php print($pro_udx_manufacturer_address); ?></p>
										<p><?php print($pro_udx_manufacturer_mail); ?></p>
									</div>

								</div>
							</div>
							<div class="product_col1">
								<div class="product_specifications">
									<div class="product_specification_row">
											
										<div class="product_specification_name">
											Produktkategorie
										</div>

										<div class="product_specification_value">
											<?php print($pro_udx_seo_pk); ?>
										</div>

									</div>
									<?php 
									$Query = "SELECT pf_fname, pf_fvalue, pf_funit FROM `products_feature` WHERE pro_id = '" . $pro_id . "' AND supplier_id = '" . $params_supplier_id . "' ORDER BY pf_forder ASC";
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
									while($row = mysqli_fetch_object($rs)){
									?>
										<div class="product_specification_row">
											
											<div class="product_specification_name">
												<?php print($row->pf_fname); ?>
											</div>

											<div class="product_specification_value">
												<?php print($row->pf_fvalue.' '.$row->pf_funit); ?>
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
					<?php
					// Pre-fetch candidate products for all 3 "Ähnliche Produkte" sliders in a single fast query
					$sim_cat_id_two = substr($cat_id_three, 0, 3);
					$sim_cat_id_one = ($sim_cat_id_two == $cat_id_two && $cat_id_one > 0) ? $cat_id_one : returnName("parent_id", "category", "group_id", $sim_cat_id_two);
					$sim_special_level_two = user_special_price("level_two", $sim_cat_id_two);
					$sim_special_level_one = (!$sim_special_level_two) ? user_special_price("level_one", $sim_cat_id_one) : false;

					$sim_slider_1 = array();
					$sim_slider_2 = array();
					$sim_slider_3 = array();

					if ($cat_id_three > 0) {
						$rs_sim_ids = mysqli_query($GLOBALS['conn'], "SELECT supplier_id FROM category_map IGNORE INDEX (cat_id) WHERE cat_id = '" . intval($cat_id_three) . "' AND supplier_id != '" . dbStr(trim($supplier_id)) . "'");
						$sim_cand_ids = array();
						if ($rs_sim_ids) {
							while ($r_sid = mysqli_fetch_row($rs_sim_ids)) {
								$sim_cand_ids[] = intval($r_sid[0]);
							}
						}
						if (!empty($sim_cand_ids)) {
							shuffle($sim_cand_ids);
							$sim_pick_ids = array_slice($sim_cand_ids, 0, 48);
							$Query_sim = "SELECT pro.pro_id, pro.supplier_id, pro.pro_url, pro.pro_udx_seo_internetbezeichung, pro.pro_udx_seo_epag_title, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pbp.pbp_price_amount AS pbp_price_without_tax, (pbp.pbp_special_price_amount + (pbp.pbp_special_price_amount * pbp.pbp_tax)) AS pbp_special_price_amount, pbp.pbp_special_price_amount AS pbp_special_price_without_tax, pbp.pbp_tax, (SELECT pg.pg_mime_source_url FROM products_gallery AS pg WHERE pg.supplier_id = pro.supplier_id AND pg.pg_mime_purpose = 'normal' ORDER BY pg.pg_mime_order ASC LIMIT 1) AS pg_mime_source_url FROM products AS pro INNER JOIN products_bundle_price AS pbp ON pbp.supplier_id = pro.supplier_id AND pbp.pbp_lower_bound = '1' WHERE pro.pro_status = '1' AND pro.supplier_id IN (" . implode(',', $sim_pick_ids) . ") LIMIT 36";
							$rs_sim = mysqli_query($GLOBALS['conn'], $Query_sim);
							$sim_all_rows = array();
							if ($rs_sim) {
								while ($row_s = mysqli_fetch_object($rs_sim)) {
									$sim_all_rows[] = $row_s;
								}
							}
							if (!empty($sim_all_rows)) {
								shuffle($sim_all_rows);
								$sim_slider_1 = array_slice($sim_all_rows, 0, 12);
								$sim_slider_2 = count($sim_all_rows) > 12 ? array_slice($sim_all_rows, 12, 12) : $sim_slider_1;
								$sim_slider_3 = count($sim_all_rows) > 24 ? array_slice($sim_all_rows, 24, 12) : $sim_slider_2;
								if (count($sim_slider_2) < 12 && count($sim_all_rows) >= 12) {
									$sim_slider_2 = array_slice($sim_all_rows, 0, 12);
								}
								if (count($sim_slider_3) < 12 && count($sim_all_rows) >= 12) {
									$sim_slider_3 = array_slice(array_reverse($sim_all_rows), 0, 12);
								}
							}
						}
					}
					$lazy_placeholder_gif = "data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==";
					?>
					<div class="product_detail_section2">
						<div class="gerenric_white_box gray_bg">
							<div class="gerenric_product full_column">
								<h2>Ähnliche Produkte</h2>
								<div class="gerenric_slider">
									<?php
									if (!empty($sim_slider_1)) {
										foreach ($sim_slider_1 as $row) {
											$special_price = user_special_price("supplier_id", $row->supplier_id);
											if (!$special_price) {
												$special_price = $sim_special_level_two ? $sim_special_level_two : $sim_special_level_one;
											}
											$sim_url = product_detail_url($row->supplier_id, 0, $row->pro_url);
											$sim_img = get_image_link(160, $row->pg_mime_source_url);
									?>
											<div>
												<div class="pd_card">
													<div class="pd_image"><a href="<?php print($sim_url); ?>" title="<?php print($row->pro_udx_seo_internetbezeichung); ?>"><img class="js_lazy_slider_img" src="<?php print($lazy_placeholder_gif); ?>" data-src="<?php print($sim_img); ?>" width="160" height="160" decoding="async" alt="<?php print($row->pro_udx_seo_internetbezeichung); ?>"></a></div>
													<div class="pd_detail">
														<h5><a href="<?php print($sim_url); ?>" title="<?php print($row->pro_udx_seo_internetbezeichung); ?>"> <?php print($row->pro_udx_seo_epag_title); ?> </a></h5>
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
								if (!empty($sim_slider_2)) {
									foreach ($sim_slider_2 as $row) {
										$special_price = user_special_price("supplier_id", $row->supplier_id);
										if (!$special_price) {
											$special_price = $sim_special_level_two ? $sim_special_level_two : $sim_special_level_one;
										}
										$sim_url = product_detail_url($row->supplier_id, 0, $row->pro_url);
										$sim_img = get_image_link(160, $row->pg_mime_source_url);
								?>
										<div>
											<div class="pd_card txt_align_left">
												<div class="pd_image"><a href="<?php print($sim_url); ?>" title="<?php print($row->pro_udx_seo_internetbezeichung); ?>"><img class="js_lazy_slider_img" src="<?php print($lazy_placeholder_gif); ?>" data-src="<?php print($sim_img); ?>" width="160" height="160" decoding="async" alt="<?php print($row->pro_udx_seo_internetbezeichung); ?>"></a></div>
												<div class="pd_detail">
													<h5><a href="<?php print($sim_url); ?>" title="<?php print($row->pro_udx_seo_internetbezeichung); ?>"> <?php print($row->pro_udx_seo_epag_title); ?> </a></h5>
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
								if (!empty($sim_slider_3)) {
									foreach ($sim_slider_3 as $row) {
										$special_price = user_special_price("supplier_id", $row->supplier_id);
										if (!$special_price) {
											$special_price = $sim_special_level_two ? $sim_special_level_two : $sim_special_level_one;
										}
										$sim_url = product_detail_url($row->supplier_id, 0, $row->pro_url);
										$sim_img = get_image_link(160, $row->pg_mime_source_url);
								?>
										<div>
											<div class="pd_card txt_align_left">
												<div class="pd_image"><a href="<?php print($sim_url); ?>" title="<?php print($row->pro_udx_seo_internetbezeichung); ?>"><img class="js_lazy_slider_img" src="<?php print($lazy_placeholder_gif); ?>" data-src="<?php print($sim_img); ?>" width="160" height="160" decoding="async" alt="<?php print($row->pro_udx_seo_internetbezeichung); ?>"></a></div>
												<div class="pd_detail">
													<h5><a href="<?php print($sim_url); ?>" title="<?php print($row->pro_udx_seo_internetbezeichung); ?>"> <?php print($row->pro_udx_seo_epag_title); ?> </a></h5>
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
						<p>unter 66,39 € (79,00 € inkl. MwSt.) Warenwert: <?php print(price_format(config_courier_packing)); ?> € Verpackungspauschale + <?php print(price_format(config_courier_shipping)); ?> € Versandkosten (je Auftrag)</p>
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
<script defer type="text/javascript" src="js/jquery.simpleGallery.min.js"></script>
<script defer type="text/javascript" src="js/jquery.simpleLens.min.js"></script>
<script>
	$(document).ready(function() {
		$('#demo-1 .simpleLens-thumbnails-container img').simpleGallery();
		$('#demo-1 .simpleLens-big-image').simpleLens();
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
		let pro_url = $(this).attr("pro_url");
		window.location.href = pro_url;
	});
	$(".cart_type_physical").on("click", function() { //ci_type
		$("#ci_type_" + $(this).attr("data-id")).val(1);
		let supplier_id = $(this).attr("data-supplier-id");
		let pro_udx_seo_epag_title_params_de = $(this).attr("pro_udx_seo_epag_title_params_de");
		let pro_url = $(this).attr("pro_url");
		//window.location.href = "product/1/" + supplier_id + "/" + pro_description;
		//window.location.href = "1/" + pro_udx_seo_epag_title_params_de + '-' + supplier_id;
		window.location.href = "1/" + pro_url;
	});
</script>
<script src="js/slick.min.js"></script>
<script type="text/javascript">
	(function() {
		function hydrateSliderImages(sliderEl) {
			$(sliderEl).find('img.js_lazy_slider_img[data-src]').each(function() {
				var src = this.getAttribute('data-src');
				if (src) {
					this.setAttribute('src', src);
					this.removeAttribute('data-src');
				}
			});
		}

		function initSliderElement(el) {
			var $el = $(el);
			if ($el.hasClass('slick-initialized')) return;
			hydrateSliderImages(el);
			if ($el.hasClass('gerenric_slider')) {
				$el.slick({
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
				});
			} else if ($el.hasClass('gerenric_slider_mostviewed')) {
				$el.slick({
					slidesToShow: 10,
					slidesToScroll: 1,
					autoplay: true,
					dots: false,
					autoplaySpeed: 2000,
					infinite: true,
					responsive: [
						{ breakpoint: 1024, settings: { slidesToShow: 4, slidesToScroll: 1 } },
						{ breakpoint: 650, settings: { slidesToShow: 3, slidesToScroll: 1 } },
						{ breakpoint: 480, settings: { slidesToShow: 2, slidesToScroll: 1 } }
					]
				});
			}
		}

		$(function() {
			var sliders = document.querySelectorAll('.gerenric_slider, .gerenric_slider_mostviewed');
			if ('IntersectionObserver' in window) {
				var observer = new IntersectionObserver(function(entries, obs) {
					entries.forEach(function(entry) {
						if (entry.isIntersecting) {
							initSliderElement(entry.target);
							obs.unobserve(entry.target);
						}
					});
				}, { rootMargin: '250px 0px' });
				sliders.forEach(function(s) { observer.observe(s); });
			} else {
				sliders.forEach(function(s) { initSliderElement(s); });
			}
		});
	})();
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
		$(".product_col1 .pd_detail_shirt ul li").slice(show_record).hide();
		if ($(".product_col1 .pd_detail_shirt ul li:hidden").length == 0) {
			$(".feature-show-more").hide();
		}
		$("body").on('click touchstart', '.feature-show-more', function(e) {
			e.preventDefault();
			$(".product_col1 .pd_detail_shirt ul li img.js_lazy_feature_img[data-src]").each(function() {
				this.setAttribute('src', this.getAttribute('data-src'));
				this.removeAttribute('data-src');
			});
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
		let pro_url = $(this).attr("pro_url");
		//console.log("pro_description: "+pro_udx_seo_epag_title_params_de+'-'+supplier_id);
		//window.location.href = pro_udx_seo_epag_title_params_de + '-' + supplier_id;
		window.location.href = pro_url;
	});
	$(".quantity").on("click", function() {
		//let quantity = $(this).attr("data-id");
		//console.log("quantity: "+quantity);
		//console.log("quantity:");
		$("#ci_qty_" + <?php print($pro_id); ?>).val($(this).attr("data-id"));
	});
</script>

</html>