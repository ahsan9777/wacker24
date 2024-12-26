<?php
include("includes/php_includes_top.php");

if (isset($_REQUEST['btn_checkout'])) {
	//print_r($_REQUEST);die();
	$user_id = 0;
	$ord_id = 0;
	$order_net_amount = 0;
	$user_id = $_SESSION['UID'];
	$usa_id = $_REQUEST['usa_id'];
	$pm_id = $_REQUEST['pm_id'];

	if (isset($_REQUEST['usa_id']) && $_REQUEST['usa_id'] > 0) {

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
			mysqli_query($GLOBALS['conn'], "INSERT INTO orders (ord_id, user_id, guest_id, ord_gross_total, ord_gst, ord_discount, ord_amount, ord_shipping_charges, ord_payment_method, ord_note, ord_datetime) VALUES ('" . $ord_id . "', '" . $user_id . "', '" . $_SESSION['sess_id'] . "', '" . $row1->cart_gross_total . "',  '" . $row1->cart_gst . "',  '" . $row1->cart_discount . "', '" . $row1->cart_amount . "', '" . $ord_shipping_charges . "', '" . $pm_id . "', '" . trim(dbStr($_REQUEST['ord_note'])) . "', '" . dbStr(trim(date_time)) . "')") or die(mysqli_error($GLOBALS['conn']));
			mysqli_query($GLOBALS['conn'], "INSERT INTO delivery_info (dinfo_id, ord_id, user_id, usa_id, guest_id, dinfo_fname, dinfo_lname, dinfo_phone, dinfo_email, dinfo_street, dinfo_house_no, dinfo_address, dinfo_countries_id, dinfo_usa_zipcode) VALUES ('" . $dinfo_id . "', '" . $ord_id . "', '" . $user_id . "', '" . $usa_id . "', '" . $_SESSION['sess_id'] . "', '" . dbStr(trim($dinfo_fname)) . "', '" . dbStr(trim($dinfo_lname)) . "', '" . dbStr(trim($dinfo_phone)) . "', '" . dbStr(trim($dinfo_email)) . "', '" . dbStr(trim($dinfo_street)) . "', '" . dbStr(trim($dinfo_house_no)) . "', '" . dbStr(trim($dinfo_address)) . "', '" . dbStr(trim($dinfo_countries_id)) . "', '" . $dinfo_usa_zipcode . "')") or die(mysqli_error($GLOBALS['conn']));
			$orders_table_check = 1;
		}

		$Query2 = "SELECT * FROM `cart_items` WHERE `cart_id` = '" . $_SESSION['cart_id'] . "' ORDER BY `ci_id` ASC";
		$rs2 = mysqli_query($GLOBALS['conn'], $Query2);
		if (mysqli_num_rows($rs2) > 0) {
			while ($row2 = mysqli_fetch_object($rs2)) {
				$ci_id = $row2->ci_id;
				$oi_id = getMaximum("order_items", "oi_id");
				mysqli_query($GLOBALS['conn'], "INSERT INTO order_items (oi_id, ord_id, supplier_id, pro_id, pbp_id, oi_amount, oi_qty, oi_gross_total, oi_gst, oi_discount, oi_net_total) VALUES ('" . $oi_id . "', '" . $ord_id . "', '" . $row2->supplier_id . "', '" . $row2->pro_id . "', '" . $row2->pbp_id . "', '" . $row2->ci_amount . "','" . $row2->ci_qty . "', '" . $row2->ci_gross_total . "','" . $row2->ci_gst . "', '" . $row2->ci_discount . "', '" . $row2->ci_total . "')") or die(mysqli_error($GLOBALS['conn']));
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
			if ($pm_id == 3) {
				header('Location: my_order.php?op=2');
			}
		}
	} else {
		header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=12");
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
				$ci_amount = $get_pro_price['ci_amount'];
				$ci_gross_total = $ci_amount * ($_REQUEST['ci_qty'][$i]);
				$ci_gst = $ci_gross_total * config_gst;
				$ci_discount = 0;
				$ci_total = $ci_gross_total + $ci_gst;

				$updated_cart_item = mysqli_query($GLOBALS['conn'], "UPDATE cart_items SET pbp_id = '" . $pbp_id . "', ci_amount = '" . $ci_amount . "', ci_qty = '" . $_REQUEST['ci_qty'][$i] . "',  ci_gross_total =  '$ci_gross_total' , ci_gst =  '$ci_gst', ci_discount =  '$ci_discount', ci_total =  '$ci_total' WHERE ci_id = '" . $row->ci_id . "'") or die(mysqli_error($GLOBALS['conn']));
				$update_cart = mysqli_query($GLOBALS['conn'], "UPDATE cart SET cart_gross_total=(SELECT SUM(ci_gross_total) FROM cart_items WHERE cart_id=$cart_id), cart_gst=(SELECT SUM(ci_gst) FROM cart_items WHERE cart_id=$cart_id), cart_discount=(SELECT SUM(ci_discount) FROM cart_items WHERE cart_id=$cart_id), cart_amount=(SELECT SUM(ci_total) FROM cart_items WHERE cart_id=$cart_id) WHERE cart_id=" . $cart_id) or die(mysqli_error($GLOBALS['conn']));
				$_SESSION['header_quantity'] = $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], "SELECT * FROM `cart_items` WHERE `cart_id` = '" . $cart_id . "'"));
				if ($updated_cart_item == true && $update_cart == true) {
					//echo "success";
					header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
				} else {
					header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=10");
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
		header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=3");
	}
}
$checkout_click = "";
$checkout_click_href = "login.php";

if (isset($_SESSION['UID']) && $_SESSION['UID'] > 0) {
	$checkout_click = "checkout_click";
	$checkout_click_href = "javascript:void(0);";
}

include("includes/message.php");
?>
<!doctype html>
<html>

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
			$("#card_click_show").click(function() {
				$(".product_cart .cart_payment_method .cart_py_field").show();
			});
			$("#card_click_hide").click(function() {
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
						<li><a href="javascript:void(0)">My data</a></li>
						<li><a href="javascript:void(0)">My shopping carts</a></li>
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
									<div class="shopping_title">Shopping Cart</div>
									<div class="cart_prise_label_row">
										<div class="cart_prise_label">Unit Price</div>
										<div class="cart_prise_label">Total Price</div>
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
									$Query = "SELECT ci.*, c.cart_gross_total, c.cart_gst, c.cart_amount, pro.pro_description_short, pg.pg_mime_source FROM cart_items AS ci LEFT OUTER JOIN cart AS c ON c.cart_id = ci.cart_id LEFT OUTER JOIN products AS pro ON pro.supplier_id = ci.supplier_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = ci.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' WHERE ci.cart_id = '" . $_SESSION['cart_id'] . "' ORDER BY ci.ci_id ASC";
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										while ($row = mysqli_fetch_object($rs)) {
											$count++;
											$cart_gross_total = $row->cart_gross_total;
											$cart_gst = $row->cart_gst;
											$cart_amount = $row->cart_amount;
											$ci_total = $ci_total + $row->ci_total;
											$gst = $row->ci_amount * config_gst;
											$delivery_charges = get_delivery_charges($cart_amount);
									?>
											<div class="cart_pd_row">
												<div class="cart_pd_image"><a href="product_detail.php?supplier_id=<?php print($row->supplier_id); ?>"><img src="getftpimage.php?img=<?php print($row->pg_mime_source); ?>" alt=""></a></div>
												<div class="cart_pd_detail">
													<div class="cart_pd_col1">
														<div class="cart_pd_title"><a href="product_detail.php?supplier_id=<?php print($row->supplier_id); ?>"><?php print($row->pro_description_short); ?></a></div>
														<?php
														$pq_quantity = 0;
														$Query1 = "SELECT * FROM products_quantity WHERE supplier_id = '" . dbStr(trim($row->supplier_id)) . "'";
														$rs1 = mysqli_query($GLOBALS['conn'], $Query1);
														if (mysqli_num_rows($rs1) > 0) {
															$row1 = mysqli_fetch_object($rs1);
															$pq_quantity = $row1->pq_quantity;
															$pq_upcomming_quantity = $row1->pq_upcomming_quantity;
															$pq_status = $row1->pq_status;
															if ($pq_quantity == 0 && $pq_status == 'true') {
																$pq_quantity = $pq_upcomming_quantity - $row->ci_qty;
															} elseif ($pq_quantity > 0 && $pq_status == 'false') {
																$pq_quantity = $pq_quantity + $pq_upcomming_quantity - $row->ci_qty;
															}
														}
														?>
														<div class="cart_pd_piece"> <?php print($pq_quantity); ?> pieces immediately available</div>
														<div class="cart_pd_option">
															<ul>
																<li>
																	<span>Quantity:</span>
																	<span>
																		<input type="hidden" name="ci_id[]" id="ci_id" value="<?php print($row->ci_id); ?>">
																		<input type="number" class="qlt_number ci_qty" name="ci_qty[]" id="ci_qty" value="<?php print($row->ci_qty); ?>" onkeyup="if(this.value === '' || parseFloat(this.value) <= 0) {this.value = 0;} else if(parseFloat(this.value) > <?php print($pq_quantity + $row->ci_qty); ?> ){ this.value =<?php print($pq_quantity + $row->ci_qty); ?>; return false; } " min="1" max="<?php print($pq_quantity); ?>">
																	</span>
																</li>
																<li><a href="<?php print($_SERVER['PHP_SELF'] . "?product_remove&ci_id=" . $row->ci_id); ?>" onclick="return confirm('Are you sure you want to delete selected item(s)?');">Delete</a></li>
																<li><a href="javascript:void(0)">Share</a></li>
																<li><a href="javascript:void(0)">Similar Product</a></li>
															</ul>
														</div>
													</div>
													<div class="cart_pd_col2">
														<div class="cart_price price_without_tex" <?php print($price_without_tex_display); ?>><?php print(str_replace(".", ",", $row->ci_amount)); ?> €</div>
														<div class="cart_price pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(number_format($row->ci_amount + $gst, "2", ",", "")); ?> €</div>

														<div class="cart_price price_without_tex" <?php print($price_without_tex_display); ?>><?php print(str_replace(".", ",", $row->ci_gross_total)); ?> €</div>
														<div class="cart_price pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(str_replace(".", ",", $row->ci_total)); ?> €</div>
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
										<div class="cart_note"><input type="checkbox" class="checknote_click"> Add note to order</div>
										<div class="total_prise_text"><span>Total amount (<?php print($count); ?> items):</span> <?php print(str_replace(".", ",", $cart_amount)); ?> €</div>
									</div>
								</div>
							</div>
							<div class="cart_note_section">
								<div class="gerenric_white_box">
									<h2>Your message about the order to us</h2>
									<div><textarea class="gerenric_input gerenric_textarea" name="ord_note" id="ord_note"></textarea></div>
								</div>
							</div>
							<div class="cart_delivery">
								<?php
								$Query = "SELECT usa.*, c.countries_name FROM user_shipping_address AS usa LEFT OUTER JOIN countries AS c ON c.countries_id = usa.countries_id WHERE usa_defualt = '1' AND user_id = '" . $_SESSION["UID"] . "' ";
								$rs = mysqli_query($GLOBALS['conn'], $Query);
								if (mysqli_num_rows($rs) > 0) {
									while ($row = mysqli_fetch_object($rs)) {
								?>
										<div class="cart_delivery_col">
											<div class="gerenric_white_box">
												<input type="hidden" name="usa_id" id="usa_id" value="<?php print($row->usa_id); ?>">
												<h2>Delivery address</h2>
												<ul>
													<li><span> <?php print($row->usa_fname . " " . $row->usa_lname); ?> </span></li>
													<li> <?php print($row->usa_street); ?> </li>
													<li> <?php print($row->usa_house_no); ?> </li>
													<li> <?php print($row->usa_contactno); ?> </li>
													<li><?php print($row->usa_zipcode); ?></li>
													<li><?php print($row->countries_name); ?></li>
													<li><?php print($row->usa_address); ?></li>
												</ul>
											</div>
										</div>
									<?php
									}
								}

								$Query = "SELECT u.*, c.countries_name FROM users AS u LEFT OUTER JOIN countries AS c ON c.countries_id = u.countries_id WHERE u.user_id = '" . $_SESSION["UID"] . "'";
								$rs = mysqli_query($GLOBALS['conn'], $Query);
								if (mysqli_num_rows($rs) > 0) {
									$row = mysqli_fetch_object($rs);
									?>
									<div class="cart_delivery_col">
										<div class="gerenric_white_box">
											<h2>Billing address</h2>
											<ul>
												<li><span> <?php print($row->user_fname . " " . $row->user_lname); ?> </span></li>
												<li> <?php print($row->user_phone); ?> </li>
												<li> <?php print($row->user_name); ?> </li>
												<li> <?php print($row->countries_name); ?> </li>
											</ul>
										</div>
									</div>
								<?php
								}
								?>
							</div>
						</div>
						<div class="cart_right">
							<div class="cart_orderview">
								<h3>Order Overview</h3>
								<div class="cart_prise_orderview">
									<ul>
										<li>
											<div class="cart_prise_lb"><span>value of goods </span></div>
											<input type="hidden" class="get_delivery_charges" name="ci_total" id="ci_total" value="<?php print($ci_total); ?>">
											<div class="cart_prise_vl price_without_tex" <?php print($price_without_tex_display); ?>><span> <?php print(number_format($cart_gross_total, "2", ",", "")); ?> €</span></div>
											<div class="cart_prise_vl pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><span> <?php print(number_format($ci_total, "2", ",", "")); ?> €</span></div>
										</li>
										<li>
											<div class="cart_prise_lb">
												<div class="packing_cost" id="packing" <?php print($display); ?>>Packaging fee (<?php print(number_format($delivery_charges_packing, "2", ",", "")); ?> €)</div>
												<div class="packing_cost" id="shipping" <?php print($display); ?>>Shipping costs (<?php print(number_format($delivery_charges_shipping, "2", ",", "")); ?> €)</div>
												<div>Shipping & Packaging total:</div>
											</div>
											<div class="cart_prise_vl" id="total"> <?php print(number_format($delivery_charges_total, "2", ",", "")); ?> €</div>
										</li>
										<li id="cart_subtotal" <?php print($display_check); ?>>
											<div class="cart_prise_lb">
												<div class="packing_cost">Subtotal</div>
											</div>
											<div class="cart_prise_vl"><?php print(number_format($cart_gross_total + $shipping_one, "2", ",", "")); ?> €</div>
										</li>
										<li id="cart_vat" <?php print($display_check); ?>>
											<div class="cart_prise_lb">
												<div class="packing_cost">plus VAT <?php print(config_gst * 100); ?>%</div>
											</div>
											<div class="cart_prise_vl"><?php print(number_format(($cart_gross_total + $shipping_two) * config_gst, "2", ",", "")); ?> €</div>
										</li>
										<li>
											<div class="cart_prise_lb"><span>Total Amount</span></div>
											<div class="cart_prise_vl"><span><?php print(number_format($cart_amount, "2", ",", "")); ?> €</span></div>
										</li>
										<li <?php print($display); ?>>
											<div class="success_message">Buy for only <?php print(number_format($schipping_cost_waived, "2", ",", "")); ?> € and the packaging and shipping costs are waived.</div>
										</li>
										<li>
											<a href="<?php print($checkout_click_href); ?>" class="gerenric_btn full_btn mt_30 <?php print($checkout_click); ?>">Checkout</a>
										</li>
									</ul>
								</div>
								<div class="cart_payment_method">
									<div class="cart_box">
										<h4>Please click on your payment method</h4>
										<ul>
											<li>
												<label class="cart_pyment_radio" id="card_click_show">
													<input type="radio" class="pm_id" id="pm_id" name="pm_id" value="2">
													<span class="checkmark">
														<div class="payment_card">
															<div class="payment_card_image"><img src="images/teba.jpg" alt=""></div>
															<div class="payment_card_title">Teba</div>
														</div>
													</span>
												</label>
											</li>
											<li>
												<label class="cart_pyment_radio" id="card_click_hide">
													<input type="radio" class="pm_id" id="pm_id" name="pm_id" value="2">
													<span class="checkmark">
														<div class="payment_card">
															<div class="payment_card_image"><img src="images/mastercard.jpg" alt=""></div>
															<div class="payment_card_title">Mastercard</div>
														</div>
													</span>
												</label>
											</li>
											<li>
												<label class="cart_pyment_radio">
													<input type="radio" class="pm_id" id="pm_id" name="pm_id" value="1">
													<span class="checkmark">
														<div class="payment_card">
															<div class="payment_card_image"><img src="images/payPal.jpg" alt=""></div>
															<div class="payment_card_title">PayPal</div>
														</div>
													</span>
												</label>
											</li>
											<li>
												<label class="cart_pyment_radio">
													<input type="radio" class="pm_id" id="pm_id" name="pm_id" value="2">
													<span class="checkmark">
														<div class="payment_card">
															<div class="payment_card_image"><img src="images/visa.jpg" alt=""></div>
															<div class="payment_card_title">Visa</div>
														</div>
													</span>
												</label>
											</li>
											<li>
												<label class="cart_pyment_radio">
													<input type="radio" class="pm_id" id="pm_id" name="pm_id" value="3" checked>
													<span class="checkmark">
														<div class="payment_card">
															<div class="payment_card_image"><img src="images/invoice_payment_icon.png" alt=""></div>
															<div class="payment_card_title">invoice</div>
														</div>
													</span>
												</label>
											</li>
										</ul>
									</div>
									<div class="cart_py_field">
										<h4>Payment Details</h4>
										<div class="gerenric_form">
											<ul>
												<li>
													<div class="form_field"><input type="text" class="gerenric_input" placeholder="Card Number"></div>
												</li>
												<li>
													<div class="form_field"><input type="text" class="gerenric_input" placeholder="Card HolderName"></div>
												</li>
												<li class="dlpy_flex">
													<div class="cart_col"><input type="text" class="gerenric_input" placeholder="12"></div>
													<div class="cart_col"><input type="text" class="gerenric_input" placeholder="2028"></div>
													<div class="cart_col"><input type="text" class="gerenric_input" placeholder="CCV"></div>
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
		<div id="scroll_top">Back to top</div>
		<?php include("includes/footer.php"); ?>
		<!--FOOTER_SECTION_END-->

	</div>

</body>
<?php include("includes/bottom_js.php"); ?>
<script>
	$(".ci_qty").on("change", function() {
		console.log("ci_qty");
		$('#frmCart')[0].submit();
	});
</script>

</html>