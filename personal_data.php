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
						<li><a href="javascript:void(0)">Meine persönlichen Daten</a></li>
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
										<div class="personal_title">Meine Daten</div>
										<p>Änderung Name, Passwort, Telefonnummer</p>
									</div>
								</div>
							</a>
						</div>
						<div class="my_personal_card">
							<a href="my_address.php">
								<div class="my_personal_card_inner">
									<div class="personal_icon"><img src="images/Address.svg" alt=""></div>
									<div class="personal_detail">
										<div class="personal_title">Meine Adressen </div>
										<p>Adresse hinzufügen, bearbeiten, entfernen</p>
									</div>
								</div>
							</a>
						</div>
						<div class="my_personal_card">
							<a href="my_order.php">
								<div class="my_personal_card_inner">
									<div class="personal_icon"><img src="images/Orders.svg" alt=""></div>
									<div class="personal_detail">
										<div class="personal_title">Meine Bestellungen</div>
										<p>Sendungsverfolgung, Rücksendung</p>
									</div>
								</div>
							</a>
						</div>
						<div class="my_personal_card">
							<!--<a href="my_payment.php">-->
							<a href="javascript:void(0);">
								<div class="my_personal_card_inner">
									<div class="personal_icon"><img src="images/Payments.svg" alt=""></div>
									<div class="personal_detail">
										<div class="personal_title">Meine Zahlungen</div>
										<p>Verwaltet Zahlungen,Neue Zahlungsarten hinzufügen</p>
									</div>
								</div>
							</a>
						</div>
						<div class="my_personal_card">
							<a href="<?php print( isset($_SESSION['cart_id'])? "cart.php": "javascript:void(0);"); ?>" >
								<div class="my_personal_card_inner">
									<div class="personal_icon"><img src="images/Cart.svg" alt=""></div>
									<div class="personal_detail">
										<div class="personal_title">Einkaufswagen</div>
										<p>Meine Einkaufswagen Zur Kasse, Neu hinzufügen</p>
									</div>
								</div>
							</a>
						</div>
						<div class="my_personal_card">
							<a href="shopping_list.php">
								<div class="my_personal_card_inner">
									<div class="personal_icon"><img src="images/shoppingList.svg" alt=""></div>
									<div class="personal_detail">
										<div class="personal_title">Meine Einkaufslisten</div>
										<p>Liste der zu kaufenden Artikel</p>
									</div>
								</div>
							</a>
						</div>
						<?php $user_special_price_count = TotalRecords("usp_id", "user_special_price", "WHERE user_id  = '".$_SESSION["UID"]."' "); ?>
						<div class="my_personal_card">
							<a href="<?php print( ($user_special_price_count > 0) ? 'special_price.php' : 'javascript:void(0);' ); ?>">
								<div class="my_personal_card_inner">
									<div class="personal_icon"><img src="images/user.png" alt=""></div>
									<div class="personal_detail">
										<div class="personal_title">Sonderpreise</div>
										<p>Liste der zu Sonderpreise</p>
									</div>
								</div>
							</a>
						</div>
						<div class="my_personal_card">
							<a href="contact_us.php">
								<div class="my_personal_card_inner">
									<div class="personal_icon"><img src="images/Contact.svg" alt=""></div>
									<div class="personal_detail">
										<div class="personal_title">Kontakt aufnehmen</div>
										<p>Kontakt 24/7 support</p>
									</div>
								</div>
							</a>
						</div>

					</div>	
				</div>
				<div class="hm_section_3 margin_top_30">
					<div class="gerenric_white_box">
						<div class="gerenric_product full_column mostviewed padding_left_right_10">
							<h2>Kategorie Multifunktion Druck</h2>
							<div class="gerenric_slider_mostviewed">
								<?php
								$special_price = "";
								$Query = "SELECT * FROM vu_category_map AS cm  WHERE FIND_IN_SET('608', cm.sub_group_ids) AND cm.cm_type = '0' ORDER BY  RAND() LIMIT 0,12";
								//print($Query);die();
								$rs = mysqli_query($GLOBALS['conn'], $Query);
								if (mysqli_num_rows($rs) > 0) {
									while ($rw = mysqli_fetch_object($rs)) {
								?>
										<div>
											<div class="pd_card txt_align_left">
												<div class="pd_image"><a href="product_detail.php?supplier_id=<?php print($rw->supplier_id); ?>"><img src="<?php print(get_image_link(160, $rw->pg_mime_source_url)); ?>" alt=""></a></div>
												<div class="pd_detail">
													<h5><a href="product_detail.php?supplier_id=<?php print($rw->supplier_id); ?>"> <?php print($rw->pro_description_short); ?> </a></h5>
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
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . $rw->pbp_price_without_tax . "€</del> <span class='pd_prise_discount'>" . discounted_price($special_price['usp_price_type'], $rw->pbp_price_without_tax, $special_price['usp_discounted_value']) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . $rw->pbp_price_amount . "€</del> <span class='pd_prise_discount'>" . discounted_price($special_price['usp_price_type'], $rw->pbp_price_amount, $special_price['usp_discounted_value'], 1) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
													<?php } else { ?>
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(str_replace(".", ",", $rw->pbp_price_without_tax)); ?>€</div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(str_replace(".", ",", $rw->pbp_price_amount)); ?>€</div>
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
				<div class="hm_section_3">
					<div class="gerenric_white_box">
						<div class="gerenric_product full_column mostviewed padding_left_right_10">
							<div class="gerenric_slider_mostviewed">
								<?php
								$special_price = "";
								$Query = "SELECT * FROM vu_category_map AS cm  WHERE FIND_IN_SET('608', cm.sub_group_ids) AND cm.cm_type = '0' ORDER BY  RAND() LIMIT 0,12";
								//print($Query);die();
								$rs = mysqli_query($GLOBALS['conn'], $Query);
								if (mysqli_num_rows($rs) > 0) {
									while ($rw = mysqli_fetch_object($rs)) {
								?>
										<div>
											<div class="pd_card txt_align_left">
												<div class="pd_image"><a href="product_detail.php?supplier_id=<?php print($rw->supplier_id); ?>"><img src="<?php print(get_image_link(160, $rw->pg_mime_source_url)); ?>" alt=""></a></div>
												<div class="pd_detail">
													<h5><a href="product_detail.php?supplier_id=<?php print($rw->supplier_id); ?>"> <?php print($rw->pro_description_short); ?> </a></h5>
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
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . $rw->pbp_price_without_tax . "€</del> <span class='pd_prise_discount'>" . discounted_price($special_price['usp_price_type'], $rw->pbp_price_without_tax, $special_price['usp_discounted_value']) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . $rw->pbp_price_amount . "€</del> <span class='pd_prise_discount'>" . discounted_price($special_price['usp_price_type'], $rw->pbp_price_amount, $special_price['usp_discounted_value'], 1) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
													<?php } else { ?>
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(str_replace(".", ",", $rw->pbp_price_without_tax)); ?>€</div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(str_replace(".", ",", $rw->pbp_price_amount)); ?>€</div>
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
<script>
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
</html>
