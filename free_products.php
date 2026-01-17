<?php
include("includes/php_includes_top.php");
$cart_amount_total = 0;
$cart_amount = 0;
$ci_total_free = 0;
$free_shipment_txt = '<span>Es fehlen noch ' . price_format(config_courier_fix_charges) . ' €</span>';
if (isset($_SESSION['cart_id'])) {
	//$cart_amount = returnName("cart_amount", "cart", "cart_id", $_SESSION['cart_id']);
	$cart_amount_total = returnName("cart_amount", "cart", "cart_id", $_SESSION['cart_id']);
	$ci_total_free = returnSum("ci_total", "cart_items", "cart_id", $_SESSION['cart_id'], " AND ci_discount_value > 0");
	if($ci_total_free > 0){
		$cart_amount = $cart_amount_total - $ci_total_free;
	} else {
		$cart_amount = $cart_amount_total;
	}
	$free_shipment_txt = "";
	if (config_condition_courier_amount >= $cart_amount) {
		$free_shipment_txt = '<span>Es fehlen noch ' . price_format(config_condition_courier_amount - $cart_amount) . ' €</span>';
	}
}
$fp_price = price_format(getMinimum("free_product", "fp_price"));
?>

<head>
	<link rel="canonical" href="">
	<?php include("includes/html_header.php"); ?>
</head>

<body style="background-color: #fff;">
	<!-- Preloader -->
	<div id="preloader" style="display:none;">
		<div class="loader"></div>
	</div>

	<div id="container" align="center">

		<!--LOCATION_POPUP_START-->
		<?php include("includes/popup.php"); ?>
		<!--LOCATION_POPUP_END-->

		<!--HEADER_SECTION_START-->
		<?php include("includes/navigation.php"); ?>
		<!--HEADER_SECTION_END-->

		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="gratis-products gerenric_padding">
				<div class="page_width_1480">
					<div class="gratis-calculate-total">
						<div class="calculate-total-left">
							<div class="calculate-gratis-text">GRATIS <span>fur Sie!</span></div>
							<div class="calculate-gratis-subtext">Ab <?php print($fp_price); ?> € Warenwert Ihrer Bestellung (alle Warenwerte inkl. MwSt.)</div>
						</div>
						<div class="calculate-total-right">
							<div class="calculate-total-row">
								<div class="calculate-total-label">Ihr Warenwert gesamt</div>
								<div class="calculate-total-value" id="cart_amount_total"><?php print(price_format($cart_amount_total)); ?> €</div>
							</div>
							<div class="calculate-total-row">
								<div class="calculate-total-label">Abzüglich Warenwert aus Aktionsartikeln:</div>
								<div class="calculate-total-value" id = "ci_total_free"><?php print(( ($ci_total_free > 0) ? "-" : "" ).price_format($ci_total_free)); ?> €</div>
							</div>
							<div class="calculate-total-row">
								<div class="calculate-total-label"><strong>Warenwert für Ihre Geschenkeauswahl:</strong></div>
								<div class="calculate-total-value"><strong id = "cart_remaning_amount"><?php print(price_format($cart_amount)); ?> €</strong><br> <span>Inkl. MwSt.</span></div>
							</div>
						</div>
					</div>
					<div class="gratis-total-prise">
						<div class="gratis-total-prise-big">Versandkostenfrei für <?php print(price_format(config_condition_courier_amount)); ?> € <?php print($free_shipment_txt); ?></div>
					</div>
					<div class="gratis-checkbox-section">
						<h3>Geschenke filtern:</h3>
						<div class="gratis-checkbox-inner">
							<?php
							$Query = "SELECT fpc_id, fpc_title_de AS fpc_title FROM free_product_category WHERE fpc_status = '1'";
							$rs = mysqli_query($GLOBALS['conn'], $Query);
							if (mysqli_num_rows($rs) > 0) {
								while ($row = mysqli_fetch_object($rs)) {
							?>
									<div class="checkbox-col"><input type="checkbox" name="fpc_id" id="fpc_id" class="fpc_id" value="<?php print($row->fpc_id); ?>"> <span><?php print($row->fpc_title); ?></span></div>
							<?php
								}
							}
							?>
						</div>

					</div>
					<div class="gratis-products-inner" id="gratis_products_inner">

					</div>
				</div>
			</div>
		</section>
		<!--CONTENT_SECTION_END-->

		<!--FOOTER_SECTION_START-->
		
		<?php include("includes/footer.php"); ?>
		<!--FOOTER_SECTION_END-->

	</div>

</body>
<script>

</script>
<script>
	$(window).on("load", function() {
		$(".fpc_id").prop("checked", false);
		$("#preloader").fadeIn(200);
		gratis_products_inner();
	});

	// Separate function to check and log checkbox statuses
	function gratis_products_inner() {
		let cart_amount = <?php print($cart_amount); ?>;
		let fpc_id = [];
		$(".fpc_id:checked").each(function() {
			fpc_id.push($(this).val());
		});
		//console.log("fpc_id", fpc_id);
		$.ajax({
			url: 'ajax_calls.php?action=gratis_products_inner',
			method: 'POST',
			data: {
				cart_amount: cart_amount,
				fpc_id: fpc_id.join(",")
			},
			success: function(response) {
				//console.log("response = "+response);
				const obj = JSON.parse(response);
				//console.log(obj);
				if (obj.status == 1) {
					$("#cart_amount_total").text(obj.cart_amount_total + " €");
					$("#ci_total_free").text(obj.ci_total_free + " €");
					$("#cart_remaning_amount").text(obj.cart_remaning_amount + " €");
					$("#gratis_products_inner").html(obj.gratis_products_inner);
					genaric_script();
					$("#preloader").fadeOut(2000);

				} else {
					$("#gratis_products_inner").html(obj.gratis_products_inner);
					$("#preloader").fadeOut(2000);
				}
			}
		});
	}

	$(".fpc_id").on("click", function() {
		$("#preloader").fadeIn(200);
		gratis_products_inner();
	});

	function genaric_script() {
		document.querySelectorAll(".quantity-container").forEach(function(container) {
			const input = container.querySelector(".gratis_quantity");
			const plus = container.querySelector(".gratis_plus");
			const minus = container.querySelector(".gratis_minus");

			// Get max quantity (default 3 if not set)
			const maxQuantity = parseInt(container.getAttribute("data-max")) || 3;

			// Function to update button states
			function updateButtons() {
				const current = parseInt(input.value);
				minus.disabled = current <= 1;
				plus.disabled = current >= maxQuantity;
			}

			// Initial state
			updateButtons();

			plus.addEventListener("click", function() {
				let current = parseInt(input.value);
				if (current < maxQuantity) {
					input.value = current + 1;
					updateButtons();
				}
			});

			minus.addEventListener("click", function() {
				let current = parseInt(input.value);
				if (current > 1) {
					input.value = current - 1;
					updateButtons();
				}
			});
		});

		$(".add_to_cart_free_product").on("click", function() {
			let fp_id = $(this).attr("data-id");
			let ci_max_quentity = $(this).attr("data-max-quentity");
			let free_quantity = $("#free_quantity_" + fp_id).val();
			//console.log("add_to_cart_free_product: ", fp_id, "free_quantity: ", free_quantity);
			$.ajax({
				url: 'ajax_calls.php?action=add_to_cart_free_product',
				method: 'POST',
				data: {
					fp_id: fp_id,
					ci_max_quentity: ci_max_quentity,
					free_quantity: free_quantity
				},
				success: function(response) {
					//console.log("response = "+response);
					const obj = JSON.parse(response);
					//console.log(obj);
					if (obj.status == 1) {
						$("#header_quantity").text(obj.count + " items");
						$("#preloader").fadeIn(200);
						gratis_products_inner();
					}
				}
			});
		});
	}
</script>
<?php include("includes/bottom_js.php"); ?>

</html>