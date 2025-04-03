<?php
include("includes/php_includes_top.php");

if (isset($_REQUEST['btn_checkout'])) {
	//print_r($_REQUEST);die();
	$user_id = 0;
	$ord_id = 0;
	$entityId = "";
	$order_net_amount = 0;
	$user_id = $_SESSION['UID'];
	$usa_id = $_REQUEST['usa_id'];
	$pm_id = $_REQUEST['pm_id'];
	if ($pm_id == 1) {
		$usa_id_billing = returnName("usa_id", "user_shipping_address", "user_id", $user_id, "AND usa_type = '1'");
		if (empty($usa_id_billing)) {
			header("Location: adressen/16");
			die();
		}
	}
	print($pm_id);
	//print_r($usa_id);die();
	/*$order_net_amount = number_format(1.5, "2", ".", "");
	$entityId = returnName("pm_entity_id", "payment_method", "pm_id", $pm_id);
			//$paypalrequest = PaypalRequest($entityId, $ord_id, $order_net_amount);
			$paypalrequest = PaypalRequest($entityId, $ord_id, $order_net_amount);
			$paypalresponseData = json_decode($paypalrequest, true);
			echo $ord_payment_transaction_id = $paypalresponseData['id'];
			echo "<br>".$paypalresponseData['resultDetails']['AcquirerResponse']."<br>";
			echo "<br>".$paypalresponseData['redirect']['url']."<br>";
			print_r($paypalresponseData['redirect']['parameters']);
			$parameters = "";
			foreach ($paypalresponseData['redirect']['parameters'] as $key => $value) {
					$parameters .=  $value['name'] . "=" . $value['value'] . "&";
				}
				print($parameters);
			print("<pre>");
				print_r($paypalresponseData);
				print("</pre>");die();*/
	$Query = "SELECT usa.*, u.user_name  FROM user_shipping_address AS usa LEFT OUTER JOIN users AS u ON u.user_id = usa.user_id WHERE usa.user_id = '" . $user_id . "' AND usa.usa_id ='" . $usa_id . "'";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) > 0) {
		$rw = mysqli_fetch_object($rs);
		$usa_id = $rw->usa_id;
		$dinfo_fname = $rw->usa_fname;
		$dinfo_lname = $rw->usa_lname;
		$dinfo_email = $rw->user_name;
		$dinfo_phone = $rw->usa_contactno;
		$dinfo_street = $rw->usa_street;
		$dinfo_house_no = $rw->usa_house_no;
		$dinfo_address = $rw->usa_address;
		$dinfo_countries_id = $rw->countries_id;
		$dinfo_usa_zipcode = $rw->usa_zipcode;
		$dinfo_additional_info = !empty($rw->usa_additional_info) ? $rw->usa_additional_info : ' ';
	}

	$orders_table_check = 0;
	$order_items_table_check = 0;
	$Query1 = "SELECT * FROM `cart` WHERE `cart_id` = '" . $_SESSION['cart_id'] . "'";
	$rs1 = mysqli_query($GLOBALS['conn'], $Query1);
	if (mysqli_num_rows($rs1) > 0) {
		$row1 = mysqli_fetch_object($rs1);
		$ord_id = getMaximum("orders", "ord_id");
		$dinfo_id = getMaximum("delivery_info", "dinfo_id");
		$ord_shipping_charges = 0;
		if ($row1->cart_gross_total <= config_condition_courier_amount) {
			$ord_shipping_charges = config_courier_fix_charges;
		}
		$order_net_amount = number_format(($row1->cart_amount + $ord_shipping_charges), "2", ".", "");
		$ord_note = "";
		if (isset($_REQUEST['ord_note']) && !empty($_REQUEST['ord_note'])) {
			$ord_note = dbStr(trim($_REQUEST['ord_note']));
		}
		mysqli_query($GLOBALS['conn'], "INSERT INTO orders (ord_id, user_id, guest_id, ord_gross_total, ord_gst, ord_discount, ord_amount, ord_shipping_charges, ord_payment_method, ord_note, ord_datetime) VALUES ('" . $ord_id . "', '" . $user_id . "', '" . $_SESSION['sess_id'] . "', '" . $row1->cart_gross_total . "',  '" . $row1->cart_gst . "',  '" . $row1->cart_discount . "', '" . $row1->cart_amount . "', '" . $ord_shipping_charges . "', '" . $pm_id . "', '" . $ord_note . "', '" . date_time . "')") or die(mysqli_error($GLOBALS['conn']));
		mysqli_query($GLOBALS['conn'], "INSERT INTO delivery_info (dinfo_id, ord_id, user_id, usa_id, guest_id, dinfo_fname, dinfo_lname, dinfo_phone, dinfo_email, dinfo_street, dinfo_house_no, dinfo_address, dinfo_countries_id, dinfo_usa_zipcode, dinfo_additional_info) VALUES ('" . $dinfo_id . "', '" . $ord_id . "', '" . $user_id . "', '" . $usa_id . "', '" . $_SESSION['sess_id'] . "', '" . $dinfo_fname . "', '" . $dinfo_lname . "', '" . $dinfo_phone . "', '" . $dinfo_email . "', '" . $dinfo_street . "', '" . $dinfo_house_no . "', '" . $dinfo_address . "', '" . $dinfo_countries_id . "', '" . $dinfo_usa_zipcode . "', '" . $dinfo_additional_info . "')") or die(mysqli_error($GLOBALS['conn']));
		$orders_table_check = 1;
	}

	$Query2 = "SELECT * FROM `cart_items` WHERE `cart_id` = '" . $_SESSION['cart_id'] . "' ORDER BY `ci_id` ASC";
	$rs2 = mysqli_query($GLOBALS['conn'], $Query2);
	if (mysqli_num_rows($rs2) > 0) {
		while ($row2 = mysqli_fetch_object($rs2)) {
			$ci_id = $row2->ci_id;
			$oi_id = getMaximum("order_items", "oi_id");
			mysqli_query($GLOBALS['conn'], "INSERT INTO order_items (oi_id, ord_id, supplier_id, pro_id, pbp_id, pbp_price_amount, oi_amount, oi_discounted_amount, oi_qty, oi_gross_total, oi_gst_value, oi_gst, oi_discount_type, oi_discount_value, oi_discount, oi_net_total) VALUES ('" . $oi_id . "', '" . $ord_id . "', '" . $row2->supplier_id . "', '" . $row2->pro_id . "', '" . $row2->pbp_id . "', '" . $row2->pbp_price_amount . "', '" . $row2->ci_amount . "', '" . $row2->ci_discounted_amount . "','" . $row2->ci_qty . "', '" . $row2->ci_gross_total . "','" . $row2->ci_gst_value . "', '" . $row2->ci_gst . "', '" . $row2->ci_discount_type . "', '" . $row2->ci_discount_value . "', '" . $row2->ci_discount . "', '" . $row2->ci_total . "')") or die(mysqli_error($GLOBALS['conn']));
			$order_items_table_check = 1;
		}
	}

	if ($orders_table_check == 1 && $order_items_table_check == 1) {
		mysqli_query($GLOBALS['conn'], "DELETE FROM cart WHERE cart_id = '" . $_SESSION['cart_id'] . "'") or die(mysqli_error($GLOBALS['conn']));
		mysqli_query($GLOBALS['conn'], "DELETE FROM cart_items WHERE cart_id = '" . $_SESSION['cart_id'] . "'") or die(mysqli_error($GLOBALS['conn']));
		unset($_SESSION['cart_id']);
		unset($_SESSION['sess_id']);
		unset($_SESSION['ci_id']);
		unset($_SESSION['header_quantity']);
		if (isset($_SESSION["cart_check"])) {
			unset($_SESSION["cart_check"]);
		}
		if ($pm_id == 1) {
			mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_payment_status = '1' WHERE ord_id= '" . $ord_id . "' ") or die(mysqli_error($GLOBALS['conn']));
			header('Location: bestellungen/15');
		} elseif ($pm_id == 2) {
			//$paypalresponseData = "";
			$entityId = returnName("pm_entity_id", "payment_method", "pm_id", $pm_id);
			//$order_net_amount = number_format(0.5, "2", ".", "");
			$paypalrequest = PaypalRequest($entityId, $ord_id, $order_net_amount);
			$paypalresponseData = json_decode($paypalrequest, true);
			/*print("<pre>");
				print_r($paypalresponseData);
				print("</pre>");die();*/
			$ord_payment_transaction_id = $paypalresponseData['id'];
			$ord_payment_short_id = $paypalresponseData['descriptor'];
			$ord_payment_info_detail = $paypalrequest;

			$parameters = "";
			if ($paypalresponseData['resultDetails']['AcquirerResponse'] == 'Success') {
				foreach ($paypalresponseData['redirect']['parameters'] as $key => $value) {
					$parameters .=  $value['name'] . "=" . $value['value'] . "&";
				}
				mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_payment_transaction_id = '" . dbStr(trim($ord_payment_transaction_id)) . "', ord_payment_short_id = '" . dbStr(trim($ord_payment_short_id)) . "', ord_payment_info_detail = '" . dbStr(trim($ord_payment_info_detail)) . "' WHERE ord_id= '" . $ord_id . "' ") or die(mysqli_error($GLOBALS['conn']));
				header('Location: ' . $paypalresponseData['redirect']['url'] . '?' . $parameters . "/15");
			}
		} elseif (in_array($pm_id, array(4, 5))) {
			$data['cardnumber'] = $_REQUEST['cardnumber'];
			$data['cardholder'] = $_REQUEST['cardholder'];
			$data['cardmonth'] = $_REQUEST['cardmonth'];
			$data['cardyear'] = $_REQUEST['cardyear'];
			$data['cvv'] = $_REQUEST['cvv'];
			$Query3 = "SELECT pm_id, pm_currency, pm_brand_name, pm_entity_id FROM `payment_method` WHERE pm_id = '" . $pm_id . "' ";
			$rs3 = mysqli_query($GLOBALS['conn'], $Query3);
			if (mysqli_num_rows($rs3) > 0) {
				$row3 = mysqli_fetch_object($rs3);
				$data['brand'] = $row3->pm_brand_name;
				$data['currency'] = $row3->pm_currency;
				$data['entityId'] = $row3->pm_entity_id;
			}
			$cardrequest = cardrequest($ord_id, $order_net_amount, $data);
			$cardresponsedata = json_decode($cardrequest, true);
			/*print("<pre>");
				print_r($cardresponsedata);
				print("</pre>");die();*/
			if ($cardresponsedata['result']['code'] == "000.100.110") {
				mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_payment_transaction_id = '" . dbStr(trim($cardresponsedata['id'])) . "', ord_payment_short_id = '" . dbStr(trim($cardresponsedata['descriptor'])) . "', ord_payment_info_detail = '" . dbStr(trim($cardrequest)) . "', ord_payment_status = '1' WHERE ord_id= '" . $ord_id . "' ") or die(mysqli_error($GLOBALS['conn']));
				header('Location: bestellungen/12');
			}
		}
	}
} elseif (isset($_REQUEST['ci_qty']) && !empty($_REQUEST['ci_qty'])) {
	//print_r($_REQUEST);die();
	for ($i = 0; $i < count($_REQUEST['ci_id']); $i++) {
		if ($_REQUEST['ci_qty'][$i] > 0) {
			$cart_id = $_SESSION['cart_id'];
			$Query = "SELECT * FROM cart_items WHERE ci_id = '" . $_REQUEST['ci_id'][$i] . "' ";
			$rs = mysqli_query($GLOBALS['conn'], $Query);
			if (mysqli_num_rows($rs) > 0) {
				$row = mysqli_fetch_object($rs);

				//$cart_quantity = returnName("ci_qty","cart_items", "ci_id", $row->ci_id);
				$get_pro_price = get_pro_price($row->pro_id, $row->supplier_id, $_REQUEST['ci_qty'][$i]);
				//print_r($get_pro_price);
				$pbp_id = $get_pro_price['pbp_id'];
				$pbp_price_amount = $get_pro_price['ci_amount'];
				$ci_amount = $get_pro_price['ci_amount'];
				$ci_gst_value = $get_pro_price['ci_gst_value'];
				$ci_discount_type = $row->ci_discount_type;
				$ci_discount_value = $row->ci_discount_value;
				$ci_discounted_amount = 0;
				$ci_discount = 0;
				if ($ci_discount_value > 0) {
					$ci_discounted_amount_gross = 0;
					$ci_amount = discounted_price($ci_discount_type, $ci_amount, $ci_discount_value);
					$ci_discounted_amount = $pbp_price_amount - $ci_amount;

					$ci_discounted_amount_gross = $ci_discounted_amount * ($_REQUEST['ci_qty'][$i]);
					$ci_discount = $ci_discounted_amount_gross + ($ci_discounted_amount_gross * $ci_gst_value);
				}
				$ci_gross_total = $ci_amount * ($_REQUEST['ci_qty'][$i]);
				$ci_gst = $ci_gross_total * $ci_gst_value;
				$ci_total = $ci_gross_total + $ci_gst;

				$updated_cart_item = mysqli_query($GLOBALS['conn'], "UPDATE cart_items SET pbp_id = '" . $pbp_id . "', pbp_price_amount = '" . $pbp_price_amount . "', ci_amount = '" . $ci_amount . "', ci_discounted_amount = '" . $ci_discounted_amount . "', ci_qty = '" . $_REQUEST['ci_qty'][$i] . "',  ci_gross_total =  '$ci_gross_total', ci_gst_value = '" . $ci_gst_value . "', ci_gst =  '$ci_gst', ci_discount =  '$ci_discount', ci_total =  '$ci_total' WHERE ci_id = '" . $row->ci_id . "'") or die(mysqli_error($GLOBALS['conn']));
				$update_cart = mysqli_query($GLOBALS['conn'], "UPDATE cart SET cart_gross_total=(SELECT SUM(ci_gross_total) FROM cart_items WHERE cart_id=$cart_id), cart_gst=(SELECT SUM(ci_gst) FROM cart_items WHERE cart_id=$cart_id), cart_discount=(SELECT SUM(ci_discount) FROM cart_items WHERE cart_id=$cart_id), cart_amount=(SELECT SUM(ci_total) FROM cart_items WHERE cart_id=$cart_id) WHERE cart_id=" . $cart_id) or die(mysqli_error($GLOBALS['conn']));
				$_SESSION['header_quantity'] = $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], "SELECT * FROM `cart_items` WHERE `cart_id` = '" . $cart_id . "'"));
				if ($updated_cart_item == true && $update_cart == true) {
					//echo "success";
					header("Location: einkaufswagen/2");
				} else {
					//header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=10");
					header("Location: einkaufswagen/10");
				}
			}
		}
	}
}

if (isset($_REQUEST['product_remove'])) {
	echo "product_remove";
	$Query = "SELECT * FROM `cart_items` WHERE `ci_id` = '" . $_REQUEST['ci_id'] . "'";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) > 0) {
		$row = mysqli_fetch_object($rs);
		mysqli_query($GLOBALS['conn'], "UPDATE cart SET  cart_gross_total = cart_gross_total - '" . $row->ci_gross_total . "', cart_gst = cart_gst - '" . $row->ci_gst . "', cart_discount = cart_discount - '" . $row->ci_discount . "', cart_amount = cart_amount - '" . $row->ci_total . "' WHERE cart_id = '" . $row->cart_id . "'") or die(mysqli_error($GLOBALS['conn']));
		mysqli_query($GLOBALS['conn'], "DELETE FROM cart_items WHERE `ci_id` = '" . $_REQUEST['ci_id'] . "'") or die(mysqli_error($GLOBALS['conn']));
		$_SESSION['header_quantity'] = $_SESSION['header_quantity'] - 1;
		//header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=3");
		header("Location: einkaufswagen/3");
	}
}
$checkout_click = "";
$checkout_click_href = "anmelden";

if (isset($_SESSION['UID']) && $_SESSION['UID'] > 0) {
	$checkout_click = "checkout_click";
	$checkout_click_href = "javascript:void(0);";
}

include("includes/message.php");
?>
<!doctype html>
<html lang="de">

<head>
	<?php include("includes/html_header.php"); ?>
	<script>
		$(function() {
			$(".checknote_click").click(function() {
				if ($(this).is(":checked")) {
					$(".product_cart .cart_note_section").show();
				} else {
					$(".product_cart .cart_note_section").hide();
				}
			});
		});
	</script>
	<script>
		$(document).ready(function() {
			$(".checkout_click").click(function() {
				$(".product_cart .cart_payment_method").show();
			});
		});
	</script>
	<script>
		$(document).ready(function() {
			$(".card_click_show").click(function() {
				$("#cardnumber").attr("required", true);
				$("#cardholder").attr("required", true);
				$("#cardmonth").attr("required", true);
				$("#cardyear").attr("required", true);
				$("#cvv").attr("required", true);
				$(".product_cart .cart_payment_method .cart_py_field").show();
			});
			$(".card_click_hide").click(function() {
				$("#cardnumber").attr("required", false);
				$("#cardholder").attr("required", false);
				$("#cardmonth").attr("required", false);
				$("#cardyear").attr("required", false);
				$("#cvv").attr("required", false);
				$(".product_cart .cart_payment_method .cart_py_field").hide();
			});
		});
	</script>
</head>

<body>
	<div id="container" align="center">

		<!--LOCATION_POPUP_START-->
		<?php include("includes/popup.php"); ?>
		<!--LOCATION_POPUP_END-->

		<!--HEADER_SECTION_START-->
		<?php include("includes/navigation.php"); ?>
		<!--HEADER_SECTION_END-->

		<!--BREADCRUMB_SECTION_START-->
		<div class="gerenric_breadcrumb">
			<div class="page_width_1480">
				<div class="breadcrumb_inner">
					<ul>
						<li><a href="javascript:void(0)">Meine Daten</a></li>
						<li><a href="javascript:void(0)">Meine Einkaufswagen</a></li>
					</ul>
				</div>
			</div>
		</div>
		<!--BREADCRUMB_SECTION_END-->

		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="product_cart">
				<div class="page_width_1480">
					<?php if ($class != "") { ?>
						<div class="<?php print($class); ?>"><?php print($strMSG); ?><a class="close" data-dismiss="alert">×</a></div>
					<?php } ?>
					<form class="product_cart_inner" name="frmCart" id="frmCart" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
						<div class="cart_left">
							<div class="gerenric_white_box">
								<h2>
									<div class="shopping_title">Einkaufswagen</div>
									<div class="cart_prise_label_row">
										<div class="cart_prise_label">Einzelpreis</div>
										<div class="cart_prise_label">Gesamtpreis</div>
									</div>
								</h2>
								<div class="cart_pd_section">
									<?php
									$cart_gross_total = 0;
									$cart_gst = 0;
									$cart_amount = 0;
									$ci_total = 0;
									$delivery_charges = 0;
									$schipping_cost_waived = 0;
									$display = 'style = "display:none;"';
									$count = 0;
									$Query = "SELECT ci.*, c.cart_gross_total, c.cart_gst, c.cart_amount, pro.pro_description_short, pro.pro_type, pg.pg_mime_source_url FROM cart_items AS ci LEFT OUTER JOIN cart AS c ON c.cart_id = ci.cart_id LEFT OUTER JOIN products AS pro ON pro.supplier_id = ci.supplier_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pro.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_source_url ASC LIMIT 1) WHERE ci.cart_id = '" . $_SESSION['cart_id'] . "' ORDER BY ci.ci_id ASC";
									//print($Query);
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										while ($row = mysqli_fetch_object($rs)) {
											$count++;
											$cart_gross_total = $row->cart_gross_total;
											$cart_gst = $row->cart_gst;
											$cart_amount = $row->cart_amount;
											$ci_gst_value = $row->ci_gst_value;
											$ci_total = $ci_total + $row->ci_total;
											$gst = $row->ci_amount * $ci_gst_value;
											$gst_orignal = $row->pbp_price_amount * $ci_gst_value;
											$delivery_charges = get_delivery_charges($cart_amount);
											$pro_description_short = explode(' ', $row->pro_description_short);
											$pro_type = $row->pro_type;

											$smiller_product_url = $GLOBALS['siteURL'] . "search_result.php?search_keyword=" . implode(' ', array_slice($pro_description_short, 0, 2));
									?>
											<div class="cart_pd_row">
												<div class="cart_pd_image"><a href="product/<?php print($row->supplier_id); ?>/<?php print(url_clean($row->pro_description_short)); ?>"><img src="<?php print(get_image_link(160, $row->pg_mime_source_url)); ?>" alt=""></a></div>
												<div class="cart_pd_detail">
													<div class="cart_pd_col1">
														<div class="cart_pd_title"><a href="product/<?php print($row->supplier_id); ?>/<?php print(url_clean($row->pro_description_short)); ?>"><?php print($row->pro_description_short); ?></a></div>
														<?php
														$pq_quantity = 0;
														$Query1 = "SELECT * FROM products_quantity WHERE supplier_id = '" . dbStr(trim($row->supplier_id)) . "'";
														$rs1 = mysqli_query($GLOBALS['conn'], $Query1);
														if (mysqli_num_rows($rs1) > 0) {
															$row1 = mysqli_fetch_object($rs1);
															$pq_quantity = $row1->pq_quantity;
															$pq_upcomming_quantity = $row1->pq_upcomming_quantity;
															$pq_status = $row1->pq_status;
															$quantity_txt = "pieces immediately available";
															$quantity_txt_color = "";
															if ($pq_quantity == 0 && ($pq_status == 'true' || $pq_status == 'false' )) {
																$pq_quantity = $pq_upcomming_quantity - $row->ci_qty;
																$quantity_txt = "Stück bestellt";
																$quantity_txt_color = "style = 'color: orange;'";
															} elseif ($pq_quantity > 0 && $pq_status == 'false') {
																$pq_quantity = $pq_quantity + $pq_upcomming_quantity - $row->ci_qty;
															}
														}
														if ($pro_type == 0) {
														?>
															<div class="cart_pd_piece" <?php print($quantity_txt_color);?> > <?php print($pq_quantity." ".$quantity_txt); ?> </div>
														<?php } ?>
														<div class="cart_pd_option">
															<ul>
																<li>
																	<span>Quantity:</span>
																	<span>
																		<input type="hidden" name="ci_id[]" id="ci_id" value="<?php print($row->ci_id); ?>">
																		<?php if ($pro_type > 0) { ?>
																			<input type="number" class="qlt_number ci_qty" name="ci_qty[]" id="ci_qty" value="<?php print($row->ci_qty); ?>" onkeyup="if(this.value === '' || parseFloat(this.value) <= 0) {this.value = 0;} else if(parseFloat(this.value) > <?php print($pq_quantity + $row->ci_qty); ?> ){ this.value =<?php print($row->ci_qty); ?>; return false; } " min="1" max="1">
																		<?php } else { ?>
																			<input type="number" class="qlt_number ci_qty" name="ci_qty[]" id="ci_qty" value="<?php print($row->ci_qty); ?>" onkeyup="if(this.value === '' || parseFloat(this.value) <= 0) {this.value = 0;} else if(parseFloat(this.value) > <?php print($pq_quantity + $row->ci_qty); ?> ){ this.value =<?php print($pq_quantity + $row->ci_qty); ?>; return false; } " min="1" max="<?php print($pq_quantity); ?>">
																		<?php } ?>
																	</span>
																</li>
																<li><a href="<?php print($_SERVER['PHP_SELF'] . "?product_remove&ci_id=" . $row->ci_id); ?>" onclick="return confirm('Are you sure you want to delete selected item(s)?');">Delete</a></li>
																<li><a href="javascript:void(0)">Share</a></li>
																<li><a href="<?php print($smiller_product_url); ?>">Similar Product</a></li>
															</ul>
														</div>
													</div>
													<div class="cart_pd_col2">
														<?php if ($row->ci_discount_value > 0) { ?>
															<div class="cart_price price_without_tex" <?php print($price_without_tex_display); ?>> <del class="orignal_price"><?php print(price_format($row->pbp_price_amount)); ?> €</del> <br> <span class="pd_prise_discount"> <?php print(str_replace(".", ",", $row->ci_amount) . "€ " . $row->ci_discount_value . (($row->ci_discount_type > 0) ? '€' : '%')); ?> </span></div>
															<div class="cart_price pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <del class="orignal_price"><?php print(price_format($row->pbp_price_amount + $gst_orignal)); ?> €</del> <br> <span class="pd_prise_discount"> <?php print(number_format($row->ci_amount + $gst, "2", ",", "") . "€ " . $row->ci_discount_value . (($row->ci_discount_type > 0) ? '€' : '%')); ?> </span> </div>
														<?php } else { ?>
															<div class="cart_price price_without_tex" <?php print($price_without_tex_display); ?>> <?php print(price_format($row->ci_amount)); ?> €</div>
															<div class="cart_price pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print(price_format($row->ci_amount + $gst)); ?> €</div>
														<?php } ?>

														<div class="cart_price price_without_tex" <?php print($price_without_tex_display); ?>> <?php print(price_format($row->ci_gross_total)); ?> €</div>
														<div class="cart_price pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print(price_format($row->ci_total)); ?> €</div>
													</div>
												</div>
											</div>
									<?php
										}
									}
									$shipping_one = 0;
									$shipping_two = 0;
									$delivery_charges_packing = 0;
									$delivery_charges_shipping = 0;
									$delivery_charges_total = 0;
									$delivery_charges_tex = 0;

									if ($count > 0) {
										if ($delivery_charges['total'] > 0) {
											$display = "";
											$shipping_one = 6.99;
											$shipping_two = 7;
											$delivery_charges_shipping = $delivery_charges['shipping'];
											$delivery_charges_packing = $delivery_charges['packing'];
											$delivery_charges_total = $delivery_charges['total'];
											$delivery_charges_tex = $delivery_charges['tex'];
											$cart_amount = $cart_amount + $delivery_charges_total + $delivery_charges_tex;
											$schipping_cost_waived = config_condition_courier_amount - $ci_total;
										}
									}
									?>
									<div class="cart_pd_total">
										<div class="cart_note"><input type="checkbox" class="checknote_click"> Notiz zur Bestellung hinzufügen</div>
										<div class="total_prise_text"><span>Gesamtbetrag (<?php print($count); ?> Artikel):</span> <?php print(price_format($cart_amount)); ?> €</div>
									</div>
								</div>
							</div>
							<div class="cart_note_section">
								<div class="gerenric_white_box">
									<h2>Ihre Nachricht zur Bestellung an uns</h2>
									<div><textarea class="gerenric_input gerenric_textarea" name="ord_note" id="ord_note"></textarea></div>
								</div>
							</div>
							<?php if (isset($_SESSION["UID"]) && $_SESSION["UID"] > 0) { ?>
								<div class="cart_delivery">
									<?php
									$guest_user = 0;
									if ($_SESSION["utype_id"] == 5) {
										$guest_user = 1;
									}
									$Query = "SELECT usa.*, c.countries_name FROM user_shipping_address AS usa LEFT OUTER JOIN countries AS c ON c.countries_id = usa.countries_id WHERE usa.usa_type = '" . $guest_user . "' AND usa.usa_defualt = '1' AND usa.user_id = '" . $_SESSION["UID"] . "' ";
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										while ($row = mysqli_fetch_object($rs)) {
									?>
											<div class="cart_delivery_col">
												<div class="gerenric_white_box">
													<input type="hidden" name="usa_id" id="usa_id" value="<?php print($row->usa_id); ?>">
													<h2>Lieferadresse</h2>
													<ul>
														<?php if (!empty($row->usa_additional_info)) { ?>
															<li><span> <?php print($row->usa_additional_info); ?> </span></li>
														<?php } ?>
														<li><span> <?php print($row->usa_fname . " " . $row->usa_lname); ?> </span></li>
														<li> <?php print($row->usa_street); ?> </li>
														<li> <?php print($row->usa_house_no); ?> </li>
														<li> <?php print($row->usa_contactno); ?> </li>
														<li><?php print($row->usa_zipcode); ?></li>
														<li><?php print($row->countries_name); ?></li>
														<li><?php print($row->usa_address); ?></li>
														<?php if ($_SESSION["utype_id"] != 5) { ?>
														<li><a href="adressen" class="gerenric_btn mt_30">Lieferadresse ändern</a></li>
														<?php } ?>
													</ul>
												</div>
											</div>
										<?php
										}
									} else {
										$checkout_click = "";
										$checkout_click_href = "adressen";
									}

									$Query = "SELECT usa.*, c.countries_name FROM user_shipping_address AS usa LEFT OUTER JOIN countries AS c ON c.countries_id = usa.countries_id LEFT OUTER JOIN users AS u ON u.user_id = usa.user_id WHERE u.user_invoice_payment = '1' AND usa.usa_type = '1' AND usa.user_id = '" . $_SESSION["UID"] . "'";
									//print($Query);
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										$row = mysqli_fetch_object($rs);
										?>
										<div class="cart_delivery_col">
											<div class="gerenric_white_box">
												<h2>Rechnungsadresse</h2>
												<ul>
													<?php if (!empty($row->usa_additional_info)) { ?>
														<li><span> <?php print($row->usa_additional_info); ?> </span></li>
													<?php } ?>
													<li><span> <?php print($row->usa_fname . " " . $row->usa_lname); ?> </span></li>
													<li> <?php print($row->usa_street); ?> </li>
													<li> <?php print($row->usa_house_no); ?> </li>
													<li> <?php print($row->usa_contactno); ?> </li>
													<li><?php print($row->usa_zipcode); ?></li>
													<li><?php print($row->countries_name); ?></li>
													<li><?php print($row->usa_address); ?></li>
													<li><a href="adressen" class="gerenric_btn mt_30">Rechnungsadresse ändern</a></li>
												</ul>
											</div>
										</div>
									<?php
									}
									?>
								</div>
							<?php } ?>
						</div>
						<div class="cart_right">
							<div class="cart_orderview">
								<h3>Bestellübersicht</h3>
								<div class="cart_prise_orderview">
									<ul>
										<li>
											<div class="cart_prise_lb"><span>Warenwert </span></div>
											<input type="hidden" class="get_delivery_charges" name="ci_total" id="ci_total" value="<?php print($ci_total); ?>">
											<div class="cart_prise_vl price_without_tex" <?php print($price_without_tex_display); ?>><span> <?php print(price_format($cart_gross_total)); ?> €</span></div>
											<div class="cart_prise_vl pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><span> <?php print(price_format($ci_total)); ?> €</span></div>
										</li>
										<li>
											<div class="cart_prise_lb">
												<div class="packing_cost" id="packing" <?php print($display); ?>>Verpackungspauschale (<?php print(price_format($delivery_charges_packing)); ?> €)</div>
												<div class="packing_cost" id="shipping" <?php print($display); ?>>Versandkosten (<?php print(price_format($delivery_charges_shipping)); ?> €)</div>
												<div>Versand & Verpackung gesamt:</div>
											</div>
											<div class="cart_prise_vl" id="total"> <?php print(price_format($delivery_charges_total)); ?> €</div>
										</li>
										<li id="cart_subtotal" <?php print($display_check); ?>>
											<div class="cart_prise_lb">
												<div class="packing_cost">Zwischensumme</div>
											</div>
											<div class="cart_prise_vl"><?php print(price_format($cart_gross_total + $shipping_one)); ?> €</div>
										</li>
										<!--<li id="cart_vat" <?php print($display_check); ?>>
											<div class="cart_prise_lb">
												<div class="packing_cost">zzgl. MwSt. <?php print(config_gst * 100); ?>%</div>
											</div>
											<div class="cart_prise_vl"><?php print(price_format(($cart_gross_total + $shipping_two) * config_gst, "2", ",", "")); ?> €</div>
										</li>-->
										<li id="cart_vat" <?php print($display_check); ?>>
											<div class="cart_prise_lb">
												<div class="packing_cost">zzgl. MwSt.</div>
											</div>
											<div class="cart_prise_vl"><?php print(price_format($cart_gst + $delivery_charges_tex +  ((isset($_SESSION['utype_id']) && $_SESSION['utype_id'] == 4) ? 0 : 1.33))); ?> €</div>
										</li>
										<li>
											<div class="cart_prise_lb"><span>Gesamtbetrag:</span></div>
											<div class="cart_prise_vl"><span><?php print(price_format($cart_amount)); ?> €</span></div>
										</li>
										<li <?php print($display); ?>>
											<div class="success_message">Kaufen Sie nur noch für <b><?php print(price_format($schipping_cost_waived)); ?> €</b> ein und die <b>Kosten der Verpackungspauschale und Versandkosten entfallen.</b></div>
										</li>
										<li>
											<a href="<?php print($checkout_click_href); ?>" class="gerenric_btn full_btn mt_30 <?php print($checkout_click); ?>">Zur Kasse</a>
										</li>
										<?php if (!isset($_SESSION["UID"])) { ?>
											<!--<li>
												<a href="registration_as_gast.php" class="gerenric_btn full_btn mt_30 <?php print($checkout_click); ?>">Checkout As Guest</a>
											</li>-->
										<?php } ?>
									</ul>
								</div>
								<div class="cart_payment_method">
									<div class="cart_box">
										<h4>Bitte klicken Sie Ihre Zahlungsart an</h4>
										<ul>
											<?php
											$Query = "SELECT pm.pm_id, pm.pm_show_detail, pm_title_de AS pm_title, pm.pm_image FROM payment_method AS pm WHERE pm.pm_status = '1' ORDER BY pm.pm_orderby ASC";
											$rs = mysqli_query($GLOBALS['conn'], $Query);
											if (mysqli_num_rows($rs) > 0) {
												while ($row = mysqli_fetch_object($rs)) {
													$pm_image_href = "files/no_img_1.jpg";
													if (!empty($row->pm_image)) {
														$pm_image_href = $GLOBALS['siteURL'] . "files/payment_method/" . $row->pm_image;
													}
													$user_invoice_payment = returnName("user_invoice_payment", "users", "user_id", $_SESSION["UID"]);
													if ($user_invoice_payment > 0 && $row->pm_id == 1) {
											?>
														<li>
															<label class="cart_pyment_radio <?php print(($row->pm_show_detail > 0) ? 'card_click_show' :  'card_click_hide') ?>">
																<input type="radio" class="pm_id" id="pm_id" name="pm_id" value="<?php print($row->pm_id) ?>" <?php print(($row->pm_id == 1) ? 'checked' :  '') ?>>
																<span class="checkmark">
																	<div class="payment_card">
																		<div class="payment_card_image"><img src="<?php print($pm_image_href); ?>" alt="<?php print($row->pm_title) ?>" title="<?php print($row->pm_title) ?>"></div>
																		<div class="payment_card_title"><?php print($row->pm_title) ?></div>
																	</div>
																</span>
															</label>
														</li>
													<?php } elseif ($row->pm_id != 1) { ?>
														<li>
															<label class="cart_pyment_radio <?php print(($row->pm_show_detail > 0) ? 'card_click_show' :  'card_click_hide') ?>">
																<input type="radio" class="pm_id" id="pm_id" name="pm_id" value="<?php print($row->pm_id) ?>" <?php print(($row->pm_id == 2 && $user_invoice_payment == 0) ? 'checked' :  '') ?>>
																<span class="checkmark">
																	<div class="payment_card">
																		<div class="payment_card_image"><img src="<?php print($pm_image_href); ?>" alt="<?php print($row->pm_title) ?>" title="<?php print($row->pm_title) ?>"></div>
																		<div class="payment_card_title"><?php print($row->pm_title) ?></div>
																	</div>
																</span>
															</label>
														</li>
											<?php }
												}
											}
											?>
										</ul>
									</div>
									<div class="cart_py_field">
										<h4>Zahlungsdetails</h4>
										<div class="gerenric_form">
											<ul>
												<li>
													<div class="form_field"><input type="text" class="gerenric_input" name="cardnumber" id="cardnumber" placeholder="Card Number"></div>
												</li>
												<li>
													<div class="form_field"><input type="text" class="gerenric_input" name="cardholder" id="cardholder" placeholder="Card HolderName"></div>
												</li>
												<li class="dlpy_flex">
													<div class="cart_col"><input type="text" class="gerenric_input" name="cardmonth" id="cardmonth" placeholder="12"></div>
													<div class="cart_col"><input type="text" class="gerenric_input" name="cardyear" id="cardyear" placeholder="2028"></div>
													<div class="cart_col"><input type="text" class="gerenric_input" name="cvv" id="cvv" placeholder="CCV"></div>
												</li>
											</ul>
										</div>
									</div>
									<div class="pay_btn"><button type="submit" name="btn_checkout" class="gerenric_btn full_btn mt_30">Pay <?php print(number_format($cart_amount, "2", ",", "")); ?> €</button></div>
								</div>
							</div>
						</div>

					</form>

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
<script>
	$(".ci_qty").on("change", function() {
		//console.log("ci_qty");
		$('#frmCart')[0].submit();
	});

	// Set max length for input fields
	document.getElementById("cardnumber").setAttribute("maxlength", "16");
	document.getElementById("cardmonth").setAttribute("maxlength", "2");
	document.getElementById("cardyear").setAttribute("maxlength", "4");
	document.getElementById("cvv").setAttribute("maxlength", "4");

	// Card Number Validation
	document.getElementById("cardnumber").addEventListener("keyup", function() {
		if (!validateCardNumber(this.value)) {
			this.style.borderColor = "red";
		} else {
			this.style.borderColor = "green";
		}
	});

	// Expiry Fields Validation
	document.getElementById("cardmonth").addEventListener("keyup", validateExpiryFields);
	document.getElementById("cardyear").addEventListener("keyup", validateExpiryFields);

	// CVV Validation
	document.getElementById("cvv").addEventListener("keyup", function() {
		const cardNumber = document.getElementById("cardnumber").value;
		if (!validateCVV(cardNumber, this.value)) {
			this.style.borderColor = "red";
		} else {
			this.style.borderColor = "green";
		}
	});

	function validateCardNumber(number) {
		const visaRegEx = /^4[0-9]{12}(?:[0-9]{3})?$/;
		const masterCardRegEx = /^5[1-5][0-9]{14}$|^2(22[1-9]|2[3-9][0-9]|[3-6][0-9]{2}|7[01][0-9]|720)[0-9]{12}$/;
		const amexRegEx = /^3[47][0-9]{13}$/;

		return visaRegEx.test(number) || masterCardRegEx.test(number) || amexRegEx.test(number);
	}

	function validateExpiryFields() {
		const month = document.getElementById("cardmonth").value;
		const year = document.getElementById("cardyear").value;

		// Ensure month value is valid (01-12) and expiry date is in the future
		if (/^(0[1-9]|1[0-2])$/.test(month) && validateExpiry(month, year)) {
			this.style.borderColor = "green";
		} else {
			this.style.borderColor = "red";
		}
	}

	function validateExpiry(month, year) {
		const now = new Date();
		const currentMonth = now.getMonth() + 1;
		const currentYear = now.getFullYear();

		// Convert to numbers for comparison
		const monthNum = parseInt(month, 10);
		const yearNum = parseInt(year, 10);

		// Ensure valid future date
		if (yearNum < currentYear) return false;
		if (yearNum === currentYear && monthNum < currentMonth) return false;

		return true;
	}

	function validateCVV(cardNumber, cvv) {
		const visaOrMasterCardCVVRegEx = /^[0-9]{3}$/;
		const amexCVVRegEx = /^[0-9]{4}$/;

		const cardType = validateCardNumber(cardNumber);

		if (cardType.includes("Amex")) {
			return amexCVVRegEx.test(cvv);
		}
		return visaOrMasterCardCVVRegEx.test(cvv);
	}
</script>

</html>