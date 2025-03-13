<?php

include("includes/php_includes_top.php");

$Query = "SELECT pro.*, pbp.pbp_id, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pbp.pbp_price_amount AS pbp_price_without_tax, pg.pg_mime_source_url, cm.cat_id AS cat_id_three, cm.sub_group_ids, c.cat_title_de AS cat_title_three FROM products AS pro LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = pro.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pro.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_source_url ASC LIMIT 1) LEFT OUTER JOIN category_map AS cm ON cm.supplier_id = pro.supplier_id LEFT OUTER JOIN category AS c ON c.group_id = cm.cat_id WHERE pro.pro_status = '1' AND pro.supplier_id = '" . $_REQUEST['supplier_id'] . "'";
//print($Query);die();
$rs = mysqli_query($GLOBALS['conn'], $Query);
if (mysqli_num_rows($rs) > 0) {
	$row = mysqli_fetch_object($rs);

	$pro_id = $row->pro_id;
	$pro_type = $row->pro_type;
	if ($pro_type > 0) {
		$qryStrURL = "pro_type=" . $pro_type . "&";
	}
	$supplier_id = $row->supplier_id;
	$pro_udx_seo_internetbezeichung = $row->pro_udx_seo_internetbezeichung;
	$pro_description_short = $row->pro_description_short;
	$pro_description_long = $row->pro_description_long;
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
	$pbp_id = $row->pbp_id;
	$ci_amount = $pbp_price_without_tax;

	$pg_mime_source_url = $row->pg_mime_source_url;
	$sub_group_ids = explode(",", $row->sub_group_ids);
	//print_r($sub_group_ids);
	$cat_id_one = $sub_group_ids[1];
	$cat_title_one = returnName("cat_title_de AS cat_title", "category", "group_id", $cat_id_one);
	$cat_id_two = $sub_group_ids[0];
	$cat_title_two = returnName("cat_title_de AS cat_title", "category", "group_id", $cat_id_two);
	$cat_id_three = $row->cat_id_three;
	$cat_title_three = $row->cat_title_three;
} else{
	header("Location: not_available.php");
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

if (isset($_REQUEST['btnAdd_to_list'])) {
	//print_r($_REQUEST);die();
	$Query = "SELECT * FROM shopping_list WHERE user_id = '" . $_SESSION["UID"] . "' AND sl_title = '" . $_REQUEST['sl_title'] . "'";
	//print($Query);die();
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) > 0) {
		$row = mysqli_fetch_object($rs);
		header("Location: " . $_SERVER['PHP_SELF'] . "?supplier_id=" . $_REQUEST['supplier_id'] . "&op=14");
	} else {
		$sl_id = getMaximum("shopping_list", "sl_id");
		mysqli_query($GLOBALS['conn'], "INSERT INTO shopping_list (sl_id, user_id, sl_title) VALUES (" . $sl_id . ", '" . $_SESSION["UID"] . "','" . dbStr(trim($_REQUEST['sl_title'])) . "')") or die(mysqli_error($GLOBALS['conn']));
		header("Location: " . $_SERVER['PHP_SELF'] . "?supplier_id=" . $_REQUEST['supplier_id'] . "&op=1");
	}
}

include("includes/message.php");
?>
<!doctype html>
<html lang="de">

<head>
	<?php include("includes/html_header.php"); ?>
	<script>
		$(window).load(function() {
			/*2 popup 1 hide 1 show*/
			$(".create_list_trigger").click(function() {
				$('.create_list_popup').show();
				$('.create_list_popup').resize();
				$('body').css({
					'overflow': 'hidden'
				});
			});
			$('.create_list_close').click(function() {
				$('.create_list_popup').hide();
				$('body').css({
					'overflow': 'inherit'
				});
			});

		});
	</script>
</head>

<body style="background-color: #fff;">
	<div id="container" align="center">

		<!--LOCATION_POPUP_START-->
		<?php include("includes/popup.php"); ?>
		<!--LOCATION_POPUP_END-->
		<!--CREATE_LIST_POPUP_START-->
		<div class="create_list_popup">
			<div class="inner_popup">
				<form class="create_list_content" name="frm" id="frmaddress" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
					<div class="create_list_heading">Liste erstellen <div class="create_list_close"><i class="fa fa-times"></i></div>
					</div>
					<div class="create_list_content_inner">
						<p>Listenname (erforderlich)</p>
						<input type="text" class="input_list" required name="sl_title" id="sl_title">
						<div class="create_button">
							<button class="gerenric_btn" type="submit" name="btnAdd_to_list">hinzufügen</button>
							<div class="gerenric_btn create_list_close">Abbrechen</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<!--CREATE_LIST_POPUP_END-->

		<!--HEADER_SECTION_START-->
		<?php include("includes/navigation.php"); ?>
		<!--HEADER_SECTION_END-->

		<!--BREADCRUMB_SECTION_START-->
		<div class="gerenric_breadcrumb">
			<div class="page_width_1480">
				<div class="breadcrumb_inner">
					<ul>
						<?php
						if ($pro_type > 0) { ?>
							<li><a href="product_category.php?level_one=20"> Schulranzen </a></li>
						<?php } else { ?>
							<li><a href="product_category.php?level_one=<?php print($cat_id_one); ?>"> <?php print($cat_title_one); ?> </a></li>
						<?php } ?>
						<li><a href="products.php?level_two=<?php print($cat_id_two . "&" . $qryStrURL); ?>"> <?php print($cat_title_two); ?> </a></li>
						<li><a href="products.php?level_three=<?php print($cat_id_three . "&" . $qryStrURL); ?>"> <?php print($cat_title_three); ?> </a></li>
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
						<div class="<?php print($class); ?>"><?php print($strMSG); ?><a href="javascript:void(0);" class="close" data-dismiss="alert">×</a></div>
					<?php } ?>
					<div class="product_detail_section1">
						<div class="product_left">
							<div class="product_main_image">
								<article>
									<div class="simpleLens-gallery-container" id="demo-1" align="center">
										<div class="large_image">
											<div class="simpleLens-container">
												<div class="simpleLens-big-image-container"> <a class="simpleLens-lens-image" data-lens-image="<?php print($pg_mime_source_url); ?>"> <img src="<?php print($pg_mime_source_url); ?>" class="simpleLens-big-image"> </a> </div>
											</div>
										</div>
										<div class="thum_images">
											<div class="simpleLens-thumbnails-container">
												<?php
												$Query = "SELECT pg_mime_source_url FROM `products_gallery` WHERE pro_id = '" . $pro_id . "' AND supplier_id = '" . $_REQUEST['supplier_id'] . "' AND pg_mime_purpose != 'data_sheet' AND pg_mime_purpose != 'others' ORDER BY CASE WHEN pg_mime_purpose = 'normal' THEN 1 ELSE 2 END";
												$rs = mysqli_query($GLOBALS['conn'], $Query);
												if (mysqli_num_rows($rs) > 0) {
													while ($row = mysqli_fetch_object($rs)) {
												?>
														<a href="javascript:voild(0)" class="simpleLens-thumbnail-wrapper" data-lens-image="<?php print(get_image_link(427, $row->pg_mime_source_url)); ?>" data-big-image="<?php print(get_image_link(427, $row->pg_mime_source_url)); ?>"> <img src="<?php print(get_image_link(160, $row->pg_mime_source_url)); ?>"> </a>
												<?php
													}
												}
												?>
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
								<h4> <?php print($pro_description_short); ?> </h4>
								<ul>
									<li>Bestellnummer: <?php print($supplier_id); ?> </li>
									<li>Herstellernummer: <?php print($pro_manufacture_aid); ?></li>
									<li>GTIN: <?php print($pro_ean); ?> </li>
								</ul>
								<?php if (!empty($special_price)) { ?>
									<div class="product_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format($pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $pbp_price_without_tax, $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
									<div class="product_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format($pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $pbp_price_amount, $special_price['usp_discounted_value'], 1)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> <span>Each ST 1/ incl. VAT</span> </span>"); ?> </div>
								<?php } else { ?>
									<div class="product_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format($pbp_price_without_tax)); ?>€</div>
									<div class="product_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format($pbp_price_amount)); ?>€ <span>Each ST 1/ incl. VAT</span></div>
									<?php }
								$count = 0;
								if ($pro_udx_seo_epag_id > 0) {
									$Query = "SELECT pf.*, pg.pg_mime_source_url FROM products_feature AS pf LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pf.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pf.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_source_url ASC LIMIT 1) WHERE pf.pro_udx_seo_epag_id = '" . $pro_udx_seo_epag_id . "' AND pf.pf_fname = '".$pro_udx_seo_selection_feature."'";
									//print($Query);
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									$count = mysqli_num_rows($rs);
									if ($count > 1) {
										if (mysqli_num_rows($rs) > 0) {
									?>
											<div class="pd_detail_shirt">
												<h2>Farbvariante: <span id="color_title"><?php print($pro_udx_seo_selection_feature); ?></span> </h2>
												<ul>
													<?php while ($row = mysqli_fetch_object($rs)) { ?>
														<li>
															<input type="radio" class="color" id="color_<?php print($row->supplier_id); ?>" name="color_radio" value="<?php print($row->supplier_id); ?>" <?php print(($row->supplier_id == $supplier_id) ? 'checked' : ''); ?>>
															<label for="color_<?php print($row->supplier_id); ?>">
																<span style="<?php print( (($pro_udx_seo_selection_feature == 'Farbe') ? 'height: 60px; width: 60px;' : 'height: 30px; width: auto;') ); ?>">
																	<?php if($pro_udx_seo_selection_feature == 'Farbe'){?>
																		<img class="color_tab" id="color_tab_<?php print($row->supplier_id); ?>" data-id="<?php print($row->supplier_id); ?>" src="<?php print(get_image_link(160, $row->pg_mime_source_url)); ?>" title="<?php print($row->pf_fvalue); ?>" alt="<?php print($row->pf_fvalue); ?>">
																	<?php } else { ?>
																		<label for="" class="color_tab" id="color_tab_<?php print($row->supplier_id); ?>" data-id="<?php print($row->supplier_id); ?>" title="<?php print($row->pf_fvalue); ?>" ><?php print($row->pf_fvalue); ?></label>
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
								<ul class="product_type">
									<?php
									$Query = "SELECT pf_fname, pf_fvalue FROM `products_feature` WHERE pro_id = '" . $pro_id . "' AND supplier_id = '" . $_REQUEST['supplier_id'] . "' ORDER BY pf_forder ASC";
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
								</ul>
								<div class="product_info">
									<p> <?php print($pro_description_long); ?> </p>
								</div>
							</div>
							<div class="product_col2">
								<div class="sticky">
									<?php
									$Query = "SELECT pbp_lower_bound, (pbp_price_amount + (pbp_price_amount * pbp_tax)) AS pbp_price_amount,  pbp_price_amount AS pbp_price_without_tax FROM `products_bundle_price` WHERE pro_id = '" . $pro_id . "' AND supplier_id = '" . $_REQUEST['supplier_id'] . "' ORDER BY pbp_lower_bound ASC";
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										while ($row = mysqli_fetch_object($rs)) {
											if (!empty($special_price)) {
									?>
												<div class="piece_prise price_without_tex" <?php print($price_without_tex_display); ?>>Ab <?php print($row->pbp_lower_bound); ?>: <?php print("<del class='orignal_price'>" . price_format($row->pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_without_tax, $special_price['usp_discounted_value'])) . "€  </span>"); ?> </div>
												<div class="piece_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>>Ab <?php print($row->pbp_lower_bound); ?>: <?php print("<del class='orignal_price'>" . price_format($row->pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_amount, $special_price['usp_discounted_value'], 1)) . "€ </span> "); ?> </div>
											<?php } else { ?>
												<div class="piece_prise price_without_tex" <?php print($price_without_tex_display); ?>>Ab <?php print($row->pbp_lower_bound); ?>: <span><?php print(price_format($row->pbp_price_without_tax)); ?>€</span></div>
												<div class="piece_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>>Ab <?php print($row->pbp_lower_bound); ?>: <span><?php print(price_format($row->pbp_price_amount)); ?>€</span></div>
									<?php
											}
										}
									}
									?>
									<div class="product_vat price_without_tex">exkl. MwSt</div>
									<div class="product_vat pbp_price_with_tex">inkl. MwSt</div>
									<?php
									$quantity_lenght = 0;
									$Query = "SELECT * FROM products_quantity WHERE supplier_id = '" . dbStr(trim($supplier_id)) . "'";
									//print($Query);
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										$row = mysqli_fetch_object($rs);
										$pq_quantity = $row->pq_quantity;
										$pq_upcomming_quantity = $row->pq_upcomming_quantity;
										$pq_status = $row->pq_status;
										if ($pq_quantity == 0 && $pq_status == 'true') {
											$quantity_lenght = $pq_upcomming_quantity;
											print('<div class="product_order_title"> ' . $pq_upcomming_quantity . ' Stück bestellt</div>');
										} elseif ($pq_quantity > 0 && $pq_status == 'false') {
											$quantity_lenght = $pq_quantity + $pq_upcomming_quantity;
											if ($quantity_lenght > 500) {
												$quantity_lenght = 500;
											}
											print('<div class="product_order_title green"> ' . $pq_quantity . ' Stück sofort verfügbar</div>');
										} elseif ($pq_quantity == 0 && $pq_status == 'false') {
											print('<div class="product_order_title red">Auf Anfrage</div>');
										}
									}
									?>
									<!--<div class="product_order_title"> 100 pieces ordered</div>-->
									<!--<div class="product_order_row">
										<div class="product_order_row_inner">
											<div class="order_text">Quantity:</div>
											<div class="order_select">
												<select class="order_select_input" id="ci_qty_<?php print($pro_id); ?>" name="ci_qty">
													<?php for ($i = 1; $i <= $quantity_lenght; $i++) { ?>
														<option value="<?php print($i); ?>"> <?php print($i); ?> </option>
													<?php } ?>
												</select>
											</div>
										</div>
									</div>-->
									<div class="product_order_row">
										<div class="product_order_row_inner">
											<div class="order_select">
												<div class="drop-down_2">
													<div class="selected">
														<div class="qtu_slt">Menge:</div>
														<a href="javascript:void(0)"><span>1</span></a>
													</div>
													<div class="options">
														<ul>
															<?php for ($i = 1; $i <= $quantity_lenght; $i++) { ?>
																<li><a href="javascript:void(0)" class="quantity" id="quantity_<?php print($i); ?>" data-id="<?php print($i); ?>"><?php print($i); ?></a></li>
															<?php } ?>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="order_btn">
										<input type="hidden" id="pro_id_<?php print($pro_id); ?>" name="pro_id" value="<?php print($pro_id); ?>">
										<input type="hidden" id="supplier_id_<?php print($pro_id); ?>" name="supplier_id" value="<?php print($supplier_id); ?>">
										<input type="hidden" id="ci_discount_type_<?php print($pro_id); ?>" name="ci_discount_type" value="<?php print((!empty($special_price)) ? $special_price['usp_price_type'] : '0'); ?>">
										<input type="hidden" id="ci_discount_value_<?php print($pro_id); ?>" name="ci_discount_value" value="<?php print((!empty($special_price)) ? $special_price['usp_discounted_value'] : '0'); ?>">
										<input type="hidden" id="ci_qty_<?php print($pro_id); ?>" name="ci_qty" value="1">
										<a class="<?php print(($quantity_lenght > 0) ? 'add_to_card' : ''); ?>" href="javascript:void(0)" data-id="<?php print($pro_id); ?>">
											<div class="gerenric_btn">In den Einkaufswagen</div>
										</a>
									</div>
									<div class="product_shippment">
										<div class="shippment_text"><span>Versand</span> Wacker 24</div>
										<div class="shippment_text">
											<a href="javascript:void(0)"><i class="fa fa-map-marker" aria-hidden="true"></i>
												<div class="location_text location_trigger"> Lieferung an Standort aktualisieren</div>
											</a>
											<?php if (isset($_SESSION['plz']) && !empty($_SESSION['plz'])) { ?>
												<div class="location_text location_trigger">Lieferung PLZ: <?php print($_SESSION['plz']); ?></div>
												<div class="location_text location_trigger"><?php print(getShippingTiming($_SESSION['plz'])); ?></div>
											<?php } ?>
										</div>
									</div>
									<div class="product_create_liste">
										<div id="alert_wishlist" class="alert alert-success" style="display: none;"><span id="alert_wishlist_txt"></span><a href="javascript:void(0);" class="close" data-dismiss="alert">×</a></div>
										<div class="drop-down">
											<div class="selected <?php print(isset($_SESSION["UID"]) ? 'show' : ''); ?>">
												<a href=" <?php print(isset($_SESSION["UID"]) ? 'javascript:void(0)' : 'login.php'); ?>"><span>Auf die Liste</span></a>
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
																<li><a href="javascript:void(0)" class="addwhishlist" data-id="<?php print($row->sl_id); ?>"><?php print($row->sl_title); ?></a></li>
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
												$special_price = user_special_price("level_two", $cat_id_two);
												if (!$special_price) {
													$special_price = user_special_price("level_one", $cat_id_one);
												}
												//print_r($special_price);
											//}
									?>
											<div>
												<div class="pd_card">
													<div class="pd_image"><a href="product_detail.php?supplier_id=<?php print($row->supplier_id); ?>"><img src="<?php print(get_image_link(160, $row->pg_mime_source_url)); ?>" alt=""></a></div>
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
															<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format($row->pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_without_tax, $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
															<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format($row->pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_amount, $special_price['usp_discounted_value'], 1)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
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
					</div>
				</div>
				<div class="product_detail_section2 margin_top_30">
					<div class="gerenric_white_box padding_left_right_10">
						<div class="gerenric_product mostviewed full_column">
							<h2>Ähnliche Produkte</h2>
							<div class="gerenric_slider_mostviewed">
								<?php
								$Query = "SELECT * FROM vu_category_map AS cm WHERE cm.cat_id = '" . $cat_id_three . "' ORDER BY  RAND() LIMIT 0,12";
								//print($Query);die();
								$rs = mysqli_query($GLOBALS['conn'], $Query);
								if (mysqli_num_rows($rs) > 0) {
									while ($row = mysqli_fetch_object($rs)) {
										//if (isset($_SESSION["UID"]) && $_SESSION["UID"] > 0) {
											$cat_id_two = substr($cat_id_three, 0, 3);
											$cat_id_one = returnName("parent_id", "category", "group_id", $cat_id_two);
											$special_price = user_special_price("level_two", $cat_id_two);
											if (!$special_price) {
												$special_price = user_special_price("level_one", $cat_id_one);
											}
											//print_r($special_price);
										//}
								?>
										<div>
											<div class="pd_card txt_align_left">
												<div class="pd_image"><a href="product_detail.php?supplier_id=<?php print($row->supplier_id); ?>"><img src="<?php print(get_image_link(160, $row->pg_mime_source_url)); ?>" alt=""></a></div>
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
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format($row->pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_without_tax, $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format($row->pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_amount, $special_price['usp_discounted_value'], 1)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
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
				</div>
				<div class="product_detail_section2">
					<div class="gerenric_white_box padding_left_right_10">
						<div class="gerenric_product mostviewed full_column">
							<h2>Ähnliche Produkte</h2>
							<div class="gerenric_slider_mostviewed">
								<?php
								$Query = "SELECT * FROM vu_category_map AS cm WHERE cm.cat_id = '" . $cat_id_three . "' ORDER BY  RAND() LIMIT 0,12";
								//print($Query);die();
								$rs = mysqli_query($GLOBALS['conn'], $Query);
								if (mysqli_num_rows($rs) > 0) {
									while ($row = mysqli_fetch_object($rs)) {
										if (isset($_SESSION["UID"]) && $_SESSION["UID"] > 0) {
											$cat_id_two = substr($cat_id_three, 0, 3);
											$cat_id_one = returnName("parent_id", "category", "group_id", $cat_id_two);
											$special_price = user_special_price("level_two", $cat_id_two);
											if (!$special_price) {
												$special_price = user_special_price("level_one", $cat_id_one);
											}
											//print_r($special_price);
										}
								?>
										<div>
											<div class="pd_card txt_align_left">
												<div class="pd_image"><a href="product_detail.php?supplier_id=<?php print($row->supplier_id); ?>"><img src="<?php print(get_image_link(160, $row->pg_mime_source_url)); ?>" alt=""></a></div>
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
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . $row->pbp_price_amount . "€</del> <span class='pd_prise_discount'>" . discounted_price($special_price['usp_price_type'], $row->pbp_price_amount, $special_price['usp_discounted_value']) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
													<?php } else { ?>
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(str_replace(".", ",", $row->pbp_price_without_tax)); ?>€</div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(str_replace(".", ",", $row->pbp_price_amount)); ?>€</div>
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

</body>
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
		let supplier_id = <?php print($supplier_id); ?>;
		let sl_id = $(this).attr("data-id");
		//console.log("sl_id: "+sl_id);
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
				console.log(obj);
				if (obj.status == 1) {
					$("#alert_wishlist_txt").text(obj.message);
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
	$(".color_tab").on("click", function() {
		let supplier_id = $(this).attr("data-id");
		//console.log("color_tab: "+supplier_id);
		window.location.href = "<?php print($_SERVER['PHP_SELF'] . "?supplier_id="); ?>" + supplier_id;
		//$("#ci_qty_" + <?php print($pro_id); ?>).val($(this).attr("data-id"));
	});
	$(".quantity").on("click", function() {
		//let quantity = $(this).attr("data-id");
		//console.log("quantity: "+quantity);
		$("#ci_qty_" + <?php print($pro_id); ?>).val($(this).attr("data-id"));
	});
</script>

</html>