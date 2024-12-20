<?php include("includes/php_includes_top.php"); ?>
<!doctype html>
<html>
<head>
<?php include("includes/html_header.php"); ?>
<script>
$(function () {
        $(".checknote_click").click(function () {
            if ($(this).is(":checked")) {
                $(".product_cart .cart_note_section").show();
            } else {
                $(".product_cart .cart_note_section").hide();
            }
        });
    });
</script>
<script>
	$(document).ready(function(){
	  $(".checkout_click").click(function(){
		$(".product_cart .cart_payment_method").show();
	  });
	});
</script>
<script>
	$(document).ready(function(){
	  $("#card_click_show").click(function(){
		$(".product_cart .cart_payment_method .cart_py_field").show();
	  });
	  $("#card_click_hide").click(function(){
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
					<div class="product_cart_inner">
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
												<div class="cart_pd_piece">47 pieces immediately available</div>
												<div class="cart_pd_option">
													<ul>
														<li><span>Quantity:</span> <span><input type="number" class="qlt_number" value="<?php print($row->ci_qty); ?>"></span></li>
														<li><a href="javascript:void(0)">Delete</a></li>
														<li><a href="javascript:void(0)">Share</a></li>
														<li><a href="javascript:void(0)">Similar Product</a></li>
													</ul>
												</div>
											</div>
											<div class="cart_pd_col2">
												<div class="cart_price price_without_tex" <?php print($price_without_tex_display); ?> ><?php print(str_replace(".", ",", $row->ci_amount)); ?> €</div>
												<div class="cart_price pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?> ><?php print(number_format($row->ci_amount + $gst, "2", ",", "")); ?> €</div>

												<div class="cart_price price_without_tex" <?php print($price_without_tex_display); ?> ><?php print(str_replace(".", ",", $row->ci_gross_total)); ?> €</div>
												<div class="cart_price pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?> ><?php print(str_replace(".", ",", $row->ci_total)); ?> €</div>
											</div>
										</div>
									</div>
									<?php 
										}
									}

									if($delivery_charges['total'] > 0 ){
										$display = "";
										$cart_amount = $cart_amount + $delivery_charges['total'] + $delivery_charges['tex'];
										$schipping_cost_waived = config_condition_courier_amount - $ci_total;
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
									<div><textarea class="gerenric_input gerenric_textarea"></textarea></div>
								</div>	
							</div>
							<div class="cart_delivery">
								<div class="cart_delivery_col">
									<div class="gerenric_white_box">
										<h2>delivery address</h2>
										<ul>
											<li><span>wacker</span></li>
											<li>Name: <span>Sayed</span></li>
											<li>blockfield,26-30</li>
											<li>67112 Mutterstadt</li>
											<li>Germany</li>
											<li>Phone: 015219435061</li>
										</ul>
									</div>	
								</div>
								<div class="cart_delivery_col">
									<div class="gerenric_white_box">
										<h2>billing address</h2>
										<ul>
											<li><span>sayed Kamal</span></li>
											<li>67112</li>
											<li>blockfield, 12</li>
											<li>67112 Mutterstadt</li>
											<li>Additional information: <span>wacker</span></li>
											<li>Germany</li>
										</ul>
									</div>	
								</div>
							</div>
						</div>
						<div class="cart_right">
							<div class="cart_orderview">
								<h3>Order Overview</h3>
								<div class="cart_prise_orderview">
									<ul>
										<li>
											<div class="cart_prise_lb"><span>value of goods	</span></div>
											<input type="hidden" class="get_delivery_charges" name="ci_total" id="ci_total" value="<?php print($ci_total); ?>">
											<div class="cart_prise_vl price_without_tex" <?php print($price_without_tex_display); ?> ><span> <?php print(number_format($cart_gross_total, "2", ",", "")); ?> €</span></div>
											<div class="cart_prise_vl pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?> ><span> <?php print(number_format($ci_total, "2", ",", "")); ?> €</span></div>
										</li>
										<li>
											<div class="cart_prise_lb">
												<div class="packing_cost" id="packing" <?php print($display); ?> >Packaging fee (<?php print(number_format($delivery_charges['packing'], "2", ",", "")); ?> €)</div>
												<div class="packing_cost" id="shipping" <?php print($display); ?> >Shipping costs (<?php print(number_format($delivery_charges['shipping'], "2", ",", "")); ?> €)</div>
												<div>Shipping & Packaging total:</div>
											</div>
											<div class="cart_prise_vl" id="total"> <?php print(number_format($delivery_charges['total'], "2", ",", "")); ?> €</div>
										</li>
										<li id="cart_subtotal" <?php print($display_check); ?> >
											<div class="cart_prise_lb"><div class="packing_cost">Subtotal</div></div>
											<div class="cart_prise_vl"><?php print(number_format($cart_gross_total + 6.99, "2", ",", "")); ?> €</div>
										</li>
										<li id="cart_vat" <?php print($display_check); ?> >
											<div class="cart_prise_lb"><div class="packing_cost">plus VAT <?php print(config_gst*100); ?>%</div></div>
											<div class="cart_prise_vl"><?php print(number_format( ($cart_gross_total + 7) * config_gst, "2", ",", "")); ?> €</div>
										</li>
										<li>
											<div class="cart_prise_lb"><span>Total Amount</span></div>
											<div class="cart_prise_vl"><span><?php print(number_format($cart_amount, "2", ",", "")); ?> €</span></div>
										</li>
										<li <?php print($display); ?> ><div class="success_message">Buy for only <?php print(number_format($schipping_cost_waived, "2", ",", "")); ?> € and the packaging and shipping costs are waived.</div></li>
										<li><div class="gerenric_btn full_btn mt_30 checkout_click">Checkout</div></li>
									</ul>
								</div>
								<div class="cart_payment_method">
									<div class="cart_box">
										<h4>Please click on your payment method</h4>
										<ul>
											<li>
												<label class="cart_pyment_radio" id="card_click_show">
													<input type="radio" name="radio">
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
													<input type="radio" name="radio">
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
													<input type="radio" name="radio">
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
													<input type="radio" name="radio">
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
													<input type="radio" name="radio">
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
									<div class="pay_btn"><div class="gerenric_btn full_btn mt_30">Pay 145.16€</div></div>
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
<?php include("includes/bottom_js.php"); ?>
</html>
