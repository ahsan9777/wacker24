<?php
include("includes/php_includes_top.php");

if (isset($_REQUEST['btn_checkout']) || (isset($_REQUEST['btn_checkout_value']) && $_REQUEST['btn_checkout_value'] > 0)) {
	//print_r($_REQUEST);die();
	$user_id = 0;
	$ord_id = 0;
	$entityId = "";
	$order_net_amount = 0;
	$user_id = $_SESSION['UID'];
	$usa_id = $_REQUEST['usa_id'];
	$_SESSION['delivery_instruction'] = $_REQUEST['delivery_instruction'];
	$pm_id = $_REQUEST['pm_id'];
	$_SESSION['ord_note'] = $_REQUEST['ord_note'];
	if ($pm_id == 1) {
		$usa_id_billing = returnName("usa_id", "user_shipping_address", "user_id", $user_id, "AND usa_type = '1'");
		if (empty($usa_id_billing)) {
			header("Location: adressen/16");
			die();
		}
	}
	//print($pm_id);
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
	$Query1 = "SELECT * FROM `cart` WHERE `cart_id` = '" . $_SESSION['cart_id'] . "'";
	$rs1 = mysqli_query($GLOBALS['conn'], $Query1);
	if (mysqli_num_rows($rs1) > 0) {
		$row1 = mysqli_fetch_object($rs1);
		$ord_id = getMaximum("orders", "ord_id");
		$ord_shipping_charges = 0;
		if ($row1->cart_amount <= config_condition_courier_amount) {
			$ord_shipping_charges = config_courier_fix_charges;
		}
		$order_net_amount = number_format(($row1->cart_amount + $ord_shipping_charges), "2", ".", "");
	}

	if (in_array($pm_id, array(1, 7))) {
		cart_to_order($user_id, $usa_id, $pm_id);
		mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_payment_status = '" . (($pm_id == 1) ? 1 : 0) . "' WHERE ord_id= '" . $ord_id . "' ") or die(mysqli_error($GLOBALS['conn']));
		if ($pm_id == 7) {
			header('Location: bestellungen/26/' . $ord_id . '');
		} else {
			header('Location: bestellungen/15');
		}
	} elseif ($pm_id == 2) {
		//$paypalresponseData = "";
		$entityId = returnName("pm_entity_id", "payment_method", "pm_id", $pm_id);
		//$order_net_amount = number_format(1, "2", ".", "");
		$paypalrequest = PaypalRequest($entityId, $ord_id, $order_net_amount, $usa_id, $pm_id);
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
			//$payment_status_request = check_payment_status($paypalresponseData['id'], $entityId);
			//$payment_status_responseData = json_decode($payment_status_request, true);
			//cart_to_order($user_id, $usa_id, $pm_id);
			//mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_payment_transaction_id = '" . dbStr(trim($ord_payment_transaction_id)) . "', ord_payment_short_id = '" . dbStr(trim($ord_payment_short_id)) . "', ord_payment_info_detail = '" . dbStr(trim($ord_payment_info_detail)) . "' WHERE ord_id= '" . $ord_id . "' ") or die(mysqli_error($GLOBALS['conn']));
			header('Location: ' . $paypalresponseData['redirect']['url'] . '?' . $parameters);
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
		$parameters = "";
		//$order_net_amount = number_format(1, "2", ".", "");
		$cardrequest = cardrequest($ord_id, $order_net_amount, $data, $usa_id, $pm_id);
		$cardresponsedata = json_decode($cardrequest, true);
		//print_r($cardresponsedata); print($cardresponsedata['result']['code']); die();
		if ($cardresponsedata['result']['code'] == "000.100.110") {
			cart_to_order($user_id, $usa_id, $pm_id);
			mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_payment_entity_id = '" . dbStr(trim($data['entityId'])) . "', ord_payment_transaction_id = '" . dbStr(trim($cardresponsedata['id'])) . "', ord_payment_short_id = '" . dbStr(trim($cardresponsedata['descriptor'])) . "', ord_payment_info_detail = '" . dbStr(trim($cardrequest)) . "', ord_payment_status = '0' WHERE ord_id= '" . $ord_id . "' ") or die(mysqli_error($GLOBALS['conn']));
			header('Location: bestellungen/15');
		} elseif ($cardresponsedata['result']['code'] == "000.200.000") {
			/*foreach ($cardresponsedata['redirect']['preconditions'][0]['parameters'] as $key => $value) {
				$parameters .=  $value['name'] . "=" . $value['value'] . "&";
			}
			//print($parameters);die();
			header('Location: ' . $cardresponsedata['redirect']['url'] . '?' . $parameters);*/
			header('Location: ' . $cardresponsedata['redirect']['url']);
		} else {
			$result_code = $cardresponsedata['result']['code'];

			switch ($result_code) {
				case '100.100.101':
					header('Location: einkaufswagen/17');
					break;
				case '100.100.303':
					header('Location: einkaufswagen/18');
					break;
				case '100.380.401':
					header('Location: einkaufswagen/19');
					break;
				case '100.396.101':
					header('Location: einkaufswagen/20');
					break;
				case '800.100.151':
					header('Location: einkaufswagen/21');
					break;
				case '800.900.300':
					header('Location: einkaufswagen/23');
					break;
				default:
					header('Location: einkaufswagen/22');
					break;
			}
			exit;
		}
	} elseif ($pm_id == 6) {
		//$paypalresponseData = "";
		$entityId = returnName("pm_entity_id", "payment_method", "pm_id", $pm_id);
		//$order_net_amount = number_format(1, "2", ".", "");
		$klarnarequest = KlarnaRequest($entityId, $ord_id, $order_net_amount, $usa_id, $pm_id);
		$klarnaresponseData = json_decode($klarnarequest, true);
		/*print("<pre>");
		print_r($klarnaresponseData);
		print("</pre>");
		die();*/
		$ord_payment_transaction_id = $klarnaresponseData['id'];
		$ord_payment_short_id = $klarnaresponseData['descriptor'];
		$ord_payment_info_detail = $klarnarequest;

		$parameters = "";
		if ($klarnaresponseData['result']['code'] === '000.200.000' && $klarnaresponseData['resultDetails']['AcquirerResponse'] === 'PENDING') {
			/*foreach ($klarnaresponseData['redirect']['parameters'] as $key => $value) {
				$parameters .=  $value['name'] . "=" . $value['value'] . "&";
			}*/
			//$payment_status_request = check_payment_status($paypalresponseData['id'], $entityId);
			//$payment_status_responseData = json_decode($payment_status_request, true);
			//cart_to_order($user_id, $usa_id, $pm_id);
			//mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_payment_transaction_id = '" . dbStr(trim($ord_payment_transaction_id)) . "', ord_payment_short_id = '" . dbStr(trim($ord_payment_short_id)) . "', ord_payment_info_detail = '" . dbStr(trim($ord_payment_info_detail)) . "' WHERE ord_id= '" . $ord_id . "' ") or die(mysqli_error($GLOBALS['conn']));
			header('Location: ' . $klarnaresponseData['redirect']['url']);
		}
	}
} elseif (isset($_REQUEST['ci_qty']) && !empty($_REQUEST['ci_qty'])) {
	//print_r($_REQUEST);die();
	for ($i = 0; $i < count($_REQUEST['ci_id']); $i++) {
		if (in_array($_REQUEST['ci_type'][$i], array(0, 1))) {
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
		} elseif ($_REQUEST['ci_type'][$i] == 2) {
			if ($_REQUEST['ci_qty'][$i] > 0) {
				$cart_id = $_SESSION['cart_id'];
				$ci_qty = $_REQUEST['ci_qty'][$i];
				$Query = "SELECT * FROM cart_items WHERE ci_id = '" . $_REQUEST['ci_id'][$i] . "' ";
				$rs = mysqli_query($GLOBALS['conn'], $Query);
				if (mysqli_num_rows($rs) > 0) {
					$row = mysqli_fetch_object($rs);
					mysqli_query($GLOBALS['conn'], "UPDATE cart_items SET ci_max_quentity = ci_max_quentity + ci_qty WHERE ci_id = '" . $row->ci_id . "' ") or die(mysqli_error($GLOBALS['conn']));
					mysqli_query($GLOBALS['conn'], "UPDATE cart_items SET ci_qty = '" . $ci_qty . "', ci_max_quentity = ci_max_quentity - '" . $ci_qty . "' WHERE ci_id = '" . $row->ci_id . "' ") or die(mysqli_error($GLOBALS['conn']));
					$_SESSION['header_quantity'] = $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], "SELECT * FROM `cart_items` WHERE `cart_id` = '" . $cart_id . "'"));
					header("Location: einkaufswagen/2");
				}
			}
		}
	}
}

if (isset($_REQUEST['product_remove'])) {
	//print_r($_REQUEST);die();
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
ci_max_quentity();
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
		<!--CREATE_LIST_POPUP_START-->
		<div class="popup versand_popup">
			<div class="popup_inner wd_30">
				<div class="popup_content">
					<div class="popup_heading">&nbsp;<div class="popup_close"><i class="fa fa-times"></i></div>
					</div>
					<div class="popup_content_inner">
						<div class="popup_inner_container">
							<p><strong>Dieses Produkt mit Freunden teilen</strong></p>
							<div class="share_icon">
								<a class="icon" id="email_href" href="" title="email">
									<i class="fa fa-envelope" aria-hidden="true"></i>
									<p>Email</p>
								</a>
							</div>
							<div class="link_copy">
								<div class="link_section">

								</div>
								<div class="btn_link_copy">
									Link kopieren
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="popup payment_method_popup">
			<div class="popup_inner wd_30">
				<div class="popup_content">
					<div class="popup_heading">&nbsp;<div class="popup_close"><i class="fa fa-times"></i></div>
					</div>
					<div class="popup_content_inner">
						<div class="popup_inner_container">
							<p><strong>Bitte wählen Sie die gewünschte Zahlungsart aus</strong></p>
						</div>
					</div>
				</div>
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
						<li><a href="javascript:void(0)" title="Meine Daten">Meine Daten</a></li>
						<li><a href="javascript:void(0)" title="Meine Einkaufswagen">Meine Einkaufswagen</a></li>
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
						<div class="<?php print($class); ?>"><?php print($strMSG); ?><a class="close" data-dismiss="alert" title="close">×</a></div>
					<?php } ?>
					<form class="product_cart_inner" name="frmCart" id="frmCart" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
						<div class="cart_left">
							<div class="gerenric_white_box">
								<h2>
									<div class="shopping_title">
										<h1>Einkaufswagen</h1>
									</div>
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
									$Query = "SELECT ci.*, c.cart_gross_total, c.cart_gst, c.cart_amount, pro.pro_description_short, pro.pro_udx_seo_internetbezeichung, pro.pro_type, pg.pg_mime_source_url FROM cart_items AS ci LEFT OUTER JOIN cart AS c ON c.cart_id = ci.cart_id LEFT OUTER JOIN products AS pro ON pro.supplier_id = ci.supplier_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pro.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_source_url ASC LIMIT 1) WHERE ci.ci_type IN (0,1) AND ci.cart_id = '" . $_SESSION['cart_id'] . "' ORDER BY ci.ci_type DESC";
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
											$cart_pd_title = "Lieferung";
											if ($row->ci_type > 0) {
												$cart_pd_title = "Abholung " . date('H:i', strtotime("+1 hour"));
											}
											$product_link = product_detail_url($row->supplier_id);
											if ($row->ci_type > 0) {
												//$product_link = "product/1/" . $row->supplier_id . "/" . product_detail_url($row->supplier_id, 1);
												$product_link = product_detail_url($row->supplier_id, 1);
											}
									?>
											<h3 class="cart_pd_title"> <?php print($cart_pd_title); ?> </h3>
											<div class="cart_pd_row">
												<div class="cart_pd_image"><a id="product_link_<?php print($row->ci_id); ?>" href="<?php print($product_link); ?>" title="<?php print($row->pro_udx_seo_internetbezeichung); ?>"><img src="<?php print(get_image_link(160, $row->pg_mime_source_url)); ?>" alt="<?php print($row->pro_udx_seo_internetbezeichung); ?>"></a></div>
												<div class="cart_pd_detail">
													<div class="cart_pd_col1">
														<div class="cart_pd_title"><a href="<?php print($product_link); ?>" id="product_title_<?php print($row->ci_id); ?>" title="<?php print($row->pro_udx_seo_internetbezeichung); ?>"><?php print($row->pro_description_short); ?></a></div>
														<?php
														$pq_quantity = 0;
														$Query1 = "SELECT * FROM products_quantity WHERE supplier_id = '" . dbStr(trim($row->supplier_id)) . "'";
														$rs1 = mysqli_query($GLOBALS['conn'], $Query1);
														if (mysqli_num_rows($rs1) > 0) {
															$row1 = mysqli_fetch_object($rs1);
															$pq_quantity = $row1->pq_quantity;
															$pq_upcomming_quantity = $row1->pq_upcomming_quantity;
															$pq_physical_quantity = $row1->pq_physical_quantity;
															$pq_status = $row1->pq_status;
															$quantity_txt = "Stück sofort verfügbar";
															$quantity_txt_color = "";
															if ($row->ci_type > 0) {
																$pq_quantity = $pq_physical_quantity - $row->ci_qty;
															} elseif (($pq_quantity == 0 || $pq_quantity < 0) && $pq_status == 'true') {
																$pq_quantity = $pq_upcomming_quantity - $row->ci_qty;
																$quantity_txt = "Stück Kurzfristig lieferbar";
																$quantity_txt_color = "style = 'color: green;'";
															} elseif ($pq_quantity > 0 && $pq_status == 'false') {
																$pq_quantity = $pq_quantity - $row->ci_qty;
															}
														}
														if ($pro_type == 0) {
														?>
															<div class="cart_pd_piece" <?php print($quantity_txt_color); ?>> <?php print($pq_quantity . " " . $quantity_txt); ?> </div>
														<?php } ?>
														<div class="cart_pd_option">
															<ul>
																<li>
																	<span>Menge:</span>
																	<span>
																		<input type="hidden" name="ci_type[]" id="ci_type" value="<?php print($row->ci_type); ?>">
																		<input type="hidden" name="ci_id[]" id="ci_id" value="<?php print($row->ci_id); ?>">
																		<?php if ($pro_type > 0) { ?>
																			<input type="number" class="qlt_number ci_qty" name="ci_qty[]" id="ci_qty" value="<?php print($row->ci_qty); ?>" onkeyup="if(this.value === '' || parseFloat(this.value) <= 0) {this.value = 0;} else if(parseFloat(this.value) > <?php print($pq_quantity + $row->ci_qty); ?> ){ this.value =<?php print($row->ci_qty); ?>; return false; } " min="1" max="1">
																		<?php } else { ?>
																			<input type="number" class="qlt_number ci_qty" name="ci_qty[]" id="ci_qty" value="<?php print($row->ci_qty); ?>" onkeyup="if(this.value === '' || parseFloat(this.value) <= 0) {this.value = 0;} else if(parseFloat(this.value) > <?php print($pq_quantity + $row->ci_qty); ?> ){ this.value =<?php print($pq_quantity + $row->ci_qty); ?>; return false; } " min="1" max="<?php print($pq_quantity); ?>">
																		<?php } ?>
																	</span>
																</li>
																<li><a href="<?php print($_SERVER['PHP_SELF'] . "?product_remove&ci_id=" . $row->ci_id); ?>" title="Löschen">Löschen</a></li>
																<li><a href="javascript:void(0)" class="versand_trigger" data-id="<?php print($row->ci_id); ?>" title="Teilen">Teilen</a></li>
																<li><a href="<?php print($smiller_product_url); ?>" title="Ähnliches Produkt">Ähnliches Produkt</a></li>
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
											$shipping_one = 7.99;
											$shipping_two = 8;
											$delivery_charges_shipping = $delivery_charges['shipping'];
											$delivery_charges_packing = $delivery_charges['packing'];
											$delivery_charges_total = $delivery_charges['total'];
											$delivery_charges_tex = $delivery_charges['tex'];
											$cart_amount = $cart_amount + $delivery_charges_total + $delivery_charges_tex;
											$schipping_cost_waived = config_condition_courier_amount - $ci_total;
										}
									}
									$Query = "SELECT ci.*, fp.fp_title_de AS fp_title, fp.fp_file FROM cart_items AS ci LEFT OUTER JOIN free_product AS fp ON fp.fp_id = ci.fp_id WHERE ci.ci_type = '2' AND ci.cart_id = '" . $_SESSION['cart_id'] . "'";
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										while ($row = mysqli_fetch_object($rs)) {
											$image_path = $GLOBALS['siteURL'] . "files/no_img_1.jpg";
											if (!empty($row->fp_file)) {
												$image_path = $GLOBALS['siteURL'] . "files/free_product/" . $row->fp_file;
											}
										?>
											<div class="cart_pd_row">
												<div class="cart_pd_image"><a id="product_link_<?php print($row->ci_id); ?>" href="javascript: void(0);" title="<?php print($row->fp_title); ?>"><img src="<?php print($image_path); ?>" alt="<?php print($row->fp_title); ?>"></a></div>
												<div class="cart_pd_detail">
													<div class="cart_pd_col1">
														<div class="cart_pd_title"><a href="javascript: void(0);" id="product_title_<?php print($row->ci_id); ?>" title="<?php print($row->fp_title); ?>"><?php print($row->fp_title); ?></a></div>
														<div class="cart_pd_option">
															<ul>
																<li>
																	<span>Menge:</span>
																	<span>
																		<input type="hidden" name="ci_type[]" id="ci_type" value="<?php print($row->ci_type); ?>">
																		<input type="hidden" name="ci_id[]" id="ci_id" value="<?php print($row->ci_id); ?>">
																		<input type="number" class="qlt_number ci_qty" name="ci_qty[]" id="ci_qty" value="<?php print($row->ci_qty); ?>" onkeyup="if(this.value === '' || parseFloat(this.value) <= 0) {this.value = 0;} else if(parseFloat(this.value) > <?php print($row->ci_max_quentity); ?> ){ this.value =<?php print($row->ci_max_quentity); ?>; return false; } " min="1" max="<?php print($row->ci_max_quentity); ?>">
																	</span>
																</li>
																<li><a href="<?php print($_SERVER['PHP_SELF'] . "?product_remove&ci_type=2&ci_id=" . $row->ci_id); ?>" title="Löschen">Löschen</a></li>
															</ul>
														</div>
													</div>
													<style>
														
													</style>
													<div class="cart_pd_col2">
														<div class="cart_price"> 0,00 €</div>
														<div class="cart_free_text"> <span>GRATIS</span> <span>fur Sie!</span></div>
													</div>
												</div>
											</div>
									<?php
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
											$property_type = array("Haus", "Wohnung", "Unternehmen", "Sonstiges");
											$pro_type_data = "";
											$delivery_instruction = delivery_instruction($row->usa_id, $row->usa_delivery_instructions_tab_active);
									?>
											<div class="cart_delivery_col">
												<div class="gerenric_white_box">
													<input type="hidden" name="usa_id" id="usa_id" value="<?php print($row->usa_id); ?>">
													<input type="hidden" name="delivery_instruction" id="delivery_instruction" value="<?php print($delivery_instruction); ?>">
													<h2>Lieferadresse</h2>
													<ul>
														<?php if (!empty($row->usa_additional_info)) { ?>
															<li><span> <?php print($row->usa_additional_info); ?> </span></li>
															<li> <?php print($row->usa_fname . " " . $row->usa_lname); ?> </li>
														<?php } else { ?>
															<li><span> <?php print($row->usa_fname . " " . $row->usa_lname); ?> </span></li>
														<?php } ?>
														<li> <?php print($row->usa_street . " " . $row->usa_house_no); ?> </li>
														<li><?php print($row->usa_zipcode); ?></li>
														<li> <?php print("Telefonnummer : " . $row->usa_contactno); ?> </li>
														<?php if (!empty($delivery_instruction)) { ?>
															<li><?php print($delivery_instruction); ?></li>
														<?php } ?>
														<?php if ($_SESSION["utype_id"] != 5) { ?>
															<li><a href="adressen" class="gerenric_btn mt_30" title="Lieferadresse ändern">Lieferadresse ändern</a></li>
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
														<li> <?php print($row->usa_fname . " " . $row->usa_lname); ?> </li>
													<?php } else { ?>
														<li><span> <?php print($row->usa_fname . " " . $row->usa_lname); ?> </span></li>
													<?php } ?>
													<li> <?php print($row->usa_street . " " . $row->usa_house_no); ?> </li>
													<li><?php print($row->usa_zipcode); ?></li>
													<li> <?php print("Telefonnummer : " . $row->usa_contactno); ?> </li>
													<li><a href="adressen" class="gerenric_btn mt_30" title="Rechnungsadresse ändern">Rechnungsadresse ändern</a></li>
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
											<a href="<?php print($checkout_click_href); ?>" class="gerenric_btn full_btn mt_30 <?php print($checkout_click); ?>" title="Zur Kasse">Zur Kasse</a>
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
										<div class="alert alert-danger payment_method_alert" style="display: none;">Bitte wählen Sie die gewünschte Zahlungsart aus<a title="close" class="close" data-dismiss="alert">×</a></div>
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
																<input type="radio" class="pm_id" id="pm_id" name="pm_id" value="<?php print($row->pm_id) ?>">
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
																<input type="radio" class="pm_id" id="pm_id" name="pm_id" value="<?php print($row->pm_id) ?>">
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
													<div class="cart_col"><input type="text" class="gerenric_input" name="cvv" id="cvv" placeholder="CVV"></div>
												</li>
											</ul>
										</div>
									</div>
									<div class="pay_btn">
										<input type="hidden" name="btn_checkout_value" id="btn_checkout_value" value="0">
										<button type="button" name="btn_checkout" class="gerenric_btn full_btn mt_30 btn_checkout">Pay <?php print(number_format($cart_amount, "2", ",", "")); ?> €</button>
									</div>
								</div>
							</div>
						</div>

					</form>

				</div>
			</div>
		</section>
		<!--CONTENT_SECTION_END-->

		<!--FOOTER_SECTION_START-->
		
		<?php include("includes/footer.php"); ?>
		<!--FOOTER_SECTION_END-->

	</div>

</body>
<?php include("includes/bottom_js.php"); ?>
<script>
	$(".pm_id").on("click", function() {
		//console.log("btn_checkout");
		let selectedPmId = $("input[name='pm_id']:checked").val();
		if (!selectedPmId) {
			$(".payment_method_alert").show();
			$('.payment_method_popup').show();
			$('.payment_method_popup').resize();
			$('body').css({
				'overflow': 'hidden'
			});
		}
	});
	$(".btn_checkout").on("click", function() {
		console.log("btn_checkout");
		let selectedPmId = $("input[name='pm_id']:checked").val();
		if (!selectedPmId) {
			$(".btn_checkout").attr("type", "button");
			$("#btn_checkout_value").val(0);
			$('.payment_method_popup').show();
			$('.payment_method_popup').resize();
			$('body').css({
				'overflow': 'hidden'
			});
		} else {
			$(".btn_checkout").attr("type", "submit");
			$("#btn_checkout_value").val(1);
			console.log("Selected payment method ID is: " + selectedPmId);
			document.getElementById('frmCart').submit();
		}
	});
	$(".ci_qty").on("change", function() {
		//console.log("ci_qty");
		$("#btn_checkout_value").val(0);
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

	$('.popup_close').click(function() {
		$('.popup').hide();
		$('body').css({
			'overflow': 'inherit'
		});
	});

	$(".versand_trigger").click(function() {
		$(".btn_link_copy").text("Link kopieren");
		let product_link = $("#product_link_" + $(this).attr("data-id")).attr("href");
		let email_href = "mailto:?subject=Check this out at wacker24&body=" + $("#product_title_" + $(this).attr("data-id")).text();
		//let email_href = encodeURIComponent("mailto:?subject=Check this out at wacker24&body="+$("#product_title_" + $(this).attr("data-id")).text());

		//console.log("product_link: "+product_link);
		$("#email_href").attr("href", email_href);
		$(".link_section").text(product_link);
		$('.versand_popup').show();
		$('.versand_popup').resize();
		$('body').css({
			'overflow': 'hidden'
		});
	});

	$(".btn_link_copy").on("click", function() {
		$(".btn_link_copy").text("Link Kopiert!");
		let link = $(".link_section").text();
		navigator.clipboard.writeText(link);
		setTimeout(function() {
			$('.popup').hide();
			$('body').css({
				'overflow': 'inherit'
			});
		}, 2000);
	});
</script>

</html>