<?php
include("includes/php_includes_top.php");
$page = 1;
?>
<!doctype html>
<html>
<head>
	<?php include("includes/html_header.php"); ?>
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
						<li><a href="javascript:void(0)">My personal data</a></li>
						<li><a href="javascript:void(0)">Private customer account</a></li>
					</ul>
				</div>
			</div>
		</div>
		<!--BREADCRUMB_SECTION_END-->
			
		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="my_personal_page gerenric_padding">
				<div class="page_width_1480">
					<h1>Private Customer Account</h1>
					<div class="my_personal_inner">
						<div class="my_personal_card">
							<a href="my_data.php">
								<div class="my_personal_card_inner">
									<div class="personal_icon"><img src="images/PersonalData.svg" alt=""></div>
									<div class="personal_detail">
										<div class="personal_title">My data</div>
										<p>Change name, password, phone number</p>
									</div>
								</div>
							</a>
						</div>
						<div class="my_personal_card">
							<a href="my_address.php">
								<div class="my_personal_card_inner">
									<div class="personal_icon"><img src="images/Address.svg" alt=""></div>
									<div class="personal_detail">
										<div class="personal_title">My addresses</div>
										<p>Add, edit, remove address</p>
									</div>
								</div>
							</a>
						</div>
						<div class="my_personal_card">
							<a href="my_order.php">
								<div class="my_personal_card_inner">
									<div class="personal_icon"><img src="images/Orders.svg" alt=""></div>
									<div class="personal_detail">
										<div class="personal_title">My Orders</div>
										<p>shipment tracking, return</p>
									</div>
								</div>
							</a>
						</div>
						<div class="my_personal_card">
							<a href="my_payment.php">
								<div class="my_personal_card_inner">
									<div class="personal_icon"><img src="images/Payments.svg" alt=""></div>
									<div class="personal_detail">
										<div class="personal_title">My Payments</div>
										<p>Manage payments, add new payment methods</p>
									</div>
								</div>
							</a>
						</div>
						<div class="my_personal_card">
							<a href="<?php print( isset($_SESSION['cart_id'])? "cart.php": "javascript:void(0);"); ?>" >
								<div class="my_personal_card_inner">
									<div class="personal_icon"><img src="images/Cart.svg" alt=""></div>
									<div class="personal_detail">
										<div class="personal_title">shopping cart</div>
										<p>My Cart Checkout, Add New</p>
									</div>
								</div>
							</a>
						</div>
						<div class="my_personal_card">
							<a href="shopping_list.php">
								<div class="my_personal_card_inner">
									<div class="personal_icon"><img src="images/shoppingList.svg" alt=""></div>
									<div class="personal_detail">
										<div class="personal_title">My shopping lists</div>
										<p>list of items to purchase</p>
									</div>
								</div>
							</a>
						</div>
						<div class="my_personal_card">
							<a href="special_price.php">
								<div class="my_personal_card_inner">
									<div class="personal_icon"><img src="images/user.png" alt=""></div>
									<div class="personal_detail">
										<div class="personal_title">special prices</div>
										<p>List of special prices</p>
									</div>
								</div>
							</a>
						</div>
						<div class="my_personal_card">
							<a href="contact_page.html">
								<div class="my_personal_card_inner">
									<div class="personal_icon"><img src="images/Contact.svg" alt=""></div>
									<div class="personal_detail">
										<div class="personal_title">make contact</div>
										<p>Contact 24/7 support</p>
									</div>
								</div>
							</a>
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
