<?php
include("includes/php_includes_top_user_dashboard.php");
$page = 1;
if (isset($_REQUEST['btnAdd']) || isset($_REQUEST['btn_Addbilling'])) {
	$usa_type = 0;
	if (isset($_REQUEST['btn_Addbilling'])) {
		$usa_type = 1;
	}
	$usa_id = getMaximum("user_shipping_address", "usa_id");
	$usa_defualt = returnName("usa_id", "user_shipping_address", "user_id", $_SESSION["UID"], "AND usa_defualt = '1' AND usa_type = '0'");
	//print($usa_defualt);die();
	if(empty($usa_defualt) && $usa_type == 0){ $usa_defualt = 1; } else { $usa_defualt = 0; }
	mysqli_query($GLOBALS['conn'], "INSERT INTO user_shipping_address (usa_id, user_id, usa_type, usa_fname, usa_lname, usa_additional_info, usa_street, usa_house_no, usa_zipcode, usa_contactno, countries_id, usa_defualt) VALUES ('" . $usa_id . "', '" . $_SESSION["UID"] . "', '" . $usa_type . "', '" . dbStr(trim($_REQUEST['usa_fname'])) . "',  '" . dbStr(trim($_REQUEST['usa_lname'])) . "', '" . dbStr(trim($_REQUEST['usa_additional_info'])) . "', '" . dbStr(trim($_REQUEST['usa_street'])) . "', '" . dbStr(trim($_REQUEST['usa_house_no'])) . "', '" . dbStr(trim($_REQUEST['usa_zipcode'])) . "', '" . dbStr(trim($_REQUEST['usa_contactno'])) . "', '" . dbStr(trim($_REQUEST['countries_id'])) . "', '".$usa_defualt."')") or die(mysqli_error($GLOBALS['conn']));
	header("Location: adressen/1");
}

if (isset($_REQUEST['set_defualt'])) {
	//print_r($_REQUEST);die();
	mysqli_query($GLOBALS['conn'], "UPDATE user_shipping_address SET usa_defualt = '0' WHERE user_id = '" . $_SESSION["UID"] . "'") or die(mysqli_error($GLOBALS['conn']));
	mysqli_query($GLOBALS['conn'], "UPDATE user_shipping_address SET usa_defualt = '1' WHERE usa_id = '" . $_REQUEST['usa_id'] . "' AND user_id = '" . $_SESSION["UID"] . "'") or die(mysqli_error($GLOBALS['conn']));
	$plz = explode(" ", returnName("usa_zipcode", "user_shipping_address", "user_id", $_SESSION["UID"], "AND usa_defualt = '1' AND usa_type = '0'"));
	$_SESSION['plz'] = $plz[0];
	getShippingTiming($plz[0]);
	header("Location: adressen/2");
}
if (isset($_REQUEST['deleted'])) {
	//print_r($_REQUEST);die();
	mysqli_query($GLOBALS['conn'], "DELETE FROM user_shipping_address WHERE usa_id = '" . $_REQUEST['usa_id'] . "' AND user_id = '" . $_SESSION["UID"] . "'") or die(mysqli_error($GLOBALS['conn']));
	header("Location: adressen/3");
}

include("includes/message.php");
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

		<div class="form_popup" id="form_shippingAddress_popup">
			<div class="inner_popup">
				<div class="form_popup_content">
					<div class="form_popup_heading">Neue Adresse hinzufügen <div class="form_popup_close"><i class="fa fa-times"></i></div>
					</div>
					<div class="form_popup_content_inner">
						<form class="gerenric_form" name="frm" id="frmaddress" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
							<ul>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">Vorname</div>
											<div class="form_field"><input type="text" name="usa_fname" id="usa_fname" class="gerenric_input" required></div>
										</div>
										<div class="form_right">
											<div class="form_label">Nachname</div>
											<div class="form_field"><input type="text" name="usa_lname" id="usa_lname" class="gerenric_input"></div>
										</div>
									</div>
								</li>
								<?php if ($_SESSION["Utype"] == 4) { ?>
									<li>
										<div class="form_label">Zusatz</div>
										<div class="form_field"><input type="text" name="usa_additional_info" id="usa_additional_info" class="gerenric_input"></div>
									</li>
								<?php } ?>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">Straße</div>
											<div class="form_field"><input type="text" name="usa_street" id="usa_street" class="gerenric_input" required></div>
										</div>
										<div class="form_right">
											<div class="form_label">Hausnr</div>
											<div class="form_field"><input type="text" name="usa_house_no" id="usa_house_no" class="gerenric_input" required></div>
										</div>
									</div>
								</li>
								<li>
									<div class="form_label">PLZ / Ort</div>
									<div class="form_field">
										<input type="text" name="usa_zipcode" id="usa_zipcode" class="gerenric_input usa_zipcode" required>
									</div>
								</li>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">Telefon</div>
											<div class="form_field"><input type="text" name="usa_contactno" id="usa_contactno" class="gerenric_input" required></div>
										</div>
										<div class="form_right">
											<div class="form_label">Land</div>
											<div class="form_field">
												<select class="gerenric_input" name="countries_id" id="countries_id">
													<?php FillSelected2("countries", "countries_id", "countries_name", 81, "countries_id > 0"); ?>
												</select>
											</div>
										</div>
									</div>
								</li>
								<li class="mt_30">
									<div class="form_two_button">
										<button class="gerenric_btn" type="submit" name="btnAdd">Aktualisieren</button>
										<button class="gerenric_btn gray_btn form_popup_close">Abbrechen</button>
									</div>
								</li>
							</ul>
						</form>
					</div>
				</div>
			</div>
		</div>

		<div class="form_popup" id="form_billingAddress_popup">
			<div class="inner_popup">
				<div class="form_popup_content">
					<div class="form_popup_heading">Rechnungsadresse hinzufügen <div class="form_popup_close"><i class="fa fa-times"></i></div>
					</div>
					<div class="form_popup_content_inner">
						<form class="gerenric_form" name="frm" id="frmaddress" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
							<ul>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">Vorname</div>
											<div class="form_field"><input type="text" name="usa_fname" id="usa_fname" class="gerenric_input" required></div>
										</div>
										<div class="form_right">
											<div class="form_label">Nachname</div>
											<div class="form_field"><input type="text" name="usa_lname" id="usa_lname" class="gerenric_input"></div>
										</div>
									</div>
								</li>
								<?php if ($_SESSION["Utype"] == 4) { ?>
									<li>
										<div class="form_label">Zusatz</div>
										<div class="form_field"><input type="text" name="usa_additional_info" id="usa_additional_info" class="gerenric_input"></div>
									</li>
								<?php } ?>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">Straße</div>
											<div class="form_field"><input type="text" name="usa_street" id="usa_street" class="gerenric_input" required></div>
										</div>
										<div class="form_right">
											<div class="form_label">Hausnr</div>
											<div class="form_field"><input type="text" name="usa_house_no" id="usa_house_no" class="gerenric_input" required></div>
										</div>
									</div>
								</li>
								<li>
									<div class="form_label">PLZ / Ort</div>
									<div class="form_field">
										<input type="text" name="usa_zipcode" id="usa_zipcode" class="gerenric_input usa_zipcode" required>
									</div>
								</li>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">Telefon</div>
											<div class="form_field"><input type="text" name="usa_contactno" id="usa_contactno" class="gerenric_input" required></div>
										</div>
										<div class="form_right">
											<div class="form_label">Land</div>
											<div class="form_field">
												<select class="gerenric_input" name="countries_id" id="countries_id">
													<?php FillSelected2("countries", "countries_id", "countries_name", 81, "countries_id > 0"); ?>
												</select>
											</div>
										</div>
									</div>
								</li>
								<li class="mt_30">
									<div class="form_two_button">
										<button class="gerenric_btn" type="submit" name="btn_Addbilling">Aktualisieren</button>
										<button class="gerenric_btn gray_btn form_popup_close">Abbrechen</button>
									</div>
								</li>
							</ul>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!--LOCATION_POPUP_END-->

		<!--HEADER_SECTION_START-->
		<?php include("includes/navigation.php"); ?>
		<!--HEADER_SECTION_END-->

		<!--BREADCRUMB_SECTION_START-->
		<div class="gerenric_breadcrumb">
			<div class="page_width_1480">
				<div class="breadcrumb_inner">
					<ul>
						<li><a href="benutzerprofile">Meine Daten</a></li>
						<li><a href="javascript:void(0)">Adressen</a></li>
					</ul>
				</div>
			</div>
		</div>
		<!--BREADCRUMB_SECTION_END-->

		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="my_address_page gerenric_padding">
				<div class="page_width_1480">
					<?php if ($class != "") { ?>
						<div class="<?php print($class); ?>"><?php print($strMSG); ?><a href="javascript:void(0);" class="close" data-dismiss="alert">×</a></div>
					<?php } ?>
					<h1>Meine Adressen</h1>
					<div class="my_address_section1">
						<div class="gerenric_address">
							<div class="address_col">
								<div class="gerenric_add_box form_popup_trigger">
									<div>
										<div class="add_icon"><i class="fa fa-plus"></i></div>
										<div class="add_text">Neue Adresse hinzufügen</div>
									</div>
								</div>
							</div>
							<?php
							$Query = "SELECT usa.*, c.countries_name FROM user_shipping_address AS usa LEFT OUTER JOIN countries AS c ON c.countries_id = usa.countries_id WHERE usa_type = '0' AND user_id = '" . $_SESSION["UID"] . "' ORDER BY usa_defualt DESC";
							$rs = mysqli_query($GLOBALS['conn'], $Query);
							if (mysqli_num_rows($rs) > 0) {
								while ($row = mysqli_fetch_object($rs)) {
									if ($row->usa_defualt == 1) {
							?>
										<div class="address_col">
											<div class="address_card">
												<div class="address_detail">
													<h2> Lieferadresse</h2>
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
													</ul>
												</div>
											</div>
										</div>
									<?php } else { ?>
										<div class="address_col">
											<div class="address_card">
												<div class="address_detail">
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
														<li><?php print($row->usa_additional_info); ?></li>
													</ul>
													<div class="btn_address">
														<a href="<?php print($_SERVER['PHP_SELF'] . "?set_defualt&usa_id=" . $row->usa_id); ?>">Als Lieferadresse einstellen</a> |
														<a href="<?php print($_SERVER['PHP_SELF'] . "?deleted&usa_id=" . $row->usa_id); ?>" onclick="return confirm('Are you sure you want to delete selected item(s)?');">Entfernen</a>
													</div>
												</div>
											</div>
										</div>
							<?php
									}
								}
							}
							?>
						</div>
					</div>
					<?php
					$user_invoice_payment = returnName("user_invoice_payment", "users", "user_id", $_SESSION["UID"]);
					if ($user_invoice_payment > 0) {
					?>
						<div class="my_address_section2">
							<div class="gerenric_address full_column">
								<div class="address_col">
									<div class="address_card">
										<?php
										$Query = "SELECT usa.*, c.countries_name FROM user_shipping_address AS usa LEFT OUTER JOIN countries AS c ON c.countries_id = usa.countries_id WHERE usa_type = '1' AND user_id = '" . $_SESSION["UID"] . "' ORDER BY usa_defualt DESC";
										$rs = mysqli_query($GLOBALS['conn'], $Query);
										if (mysqli_num_rows($rs) > 0) {
											$row = mysqli_fetch_object($rs);
										?>
											<div class="address_detail">
												<h2>Rechnungsadresse</h2>
												<ul>
													<li><span> <?php print($row->usa_fname . " " . $row->usa_lname); ?> </span></li>
													<li> <?php print($row->usa_street); ?> </li>
													<li> <?php print($row->usa_house_no); ?> </li>
													<li> <?php print($row->usa_contactno); ?> </li>
													<li><?php print($row->usa_zipcode); ?></li>
													<li><?php print($row->countries_name); ?></li>
													<li><?php print($row->usa_additional_info); ?></li>
												</ul>
											</div>
											<div class="address_remove"><a href="<?php print($_SERVER['PHP_SELF'] . "?deleted&usa_id=" . $row->usa_id); ?>" onclick="return confirm('Are you sure you want to delete selected item(s)?');">
													<div class="gerenric_btn">Entfernen</div>
												</a>
											</div>
										<?php
										} else {
										?>
											<div class="txt_align_center">
												<button class="gerenric_btn form_billingAddress_trigger" type="button" name="btnAdd">Rechnungsadresse hinzufügen</button>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
				<div class="hm_section_3 margin_top_30">
					<div class="gerenric_white_box">
						<div class="gerenric_product full_column mostviewed padding_left_right_10">
							<h2>Kategorie Kopierpapiere</h2>
							<div class="gerenric_slider_mostviewed">
								<?php
								$special_price = "";
								$Query = "SELECT * FROM vu_category_map AS cm  WHERE cm.cat_id = '20500' AND cm.cm_type = '0' ORDER BY  RAND() LIMIT 0,12";
								//print($Query);die();
								$rs = mysqli_query($GLOBALS['conn'], $Query);
								if (mysqli_num_rows($rs) > 0) {
									while ($rw = mysqli_fetch_object($rs)) {
								?>
										<div>
											<div class="pd_card txt_align_left">
												<div class="pd_image"><a href="product/<?php print($rw->supplier_id); ?>/<?php print(url_clean($rw->pro_description_short)); ?>"><img src="<?php print(get_image_link(160, $rw->pg_mime_source_url)); ?>" alt=""></a></div>
												<div class="pd_detail">
													<h5><a href="product/<?php print($rw->supplier_id); ?>/<?php print(url_clean($rw->pro_description_short)); ?>"> <?php print($rw->pro_description_short); ?> </a></h5>
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
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format($rw->pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $rw->pbp_price_without_tax, $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format($rw->pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $rw->pbp_price_amount, $special_price['usp_discounted_value'], $rw->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
													<?php } else { ?>
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format($rw->pbp_price_without_tax)); ?>€</div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format($rw->pbp_price_amount)); ?>€</div>
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
								$Query = "SELECT * FROM vu_category_map AS cm  WHERE cm.cat_id = '20500' AND cm.cm_type = '0' ORDER BY  RAND() LIMIT 0,12";
								//print($Query);die();
								$rs = mysqli_query($GLOBALS['conn'], $Query);
								if (mysqli_num_rows($rs) > 0) {
									while ($rw = mysqli_fetch_object($rs)) {
								?>
										<div>
											<div class="pd_card txt_align_left">
												<div class="pd_image"><a href="product/<?php print($rw->supplier_id); ?>/<?php print(url_clean($rw->pro_description_short)); ?>"><img src="<?php print(get_image_link(160, $rw->pg_mime_source_url)); ?>" alt=""></a></div>
												<div class="pd_detail">
													<h5><a href="product/<?php print($rw->supplier_id); ?>/<?php print(url_clean($rw->pro_description_short)); ?>"> <?php print($rw->pro_description_short); ?> </a></h5>
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
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format($rw->pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $rw->pbp_price_without_tax, $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format($rw->pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $rw->pbp_price_amount, $special_price['usp_discounted_value'], $rw->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
													<?php } else { ?>
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format($rw->pbp_price_without_tax)); ?>€</div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format($rw->pbp_price_amount)); ?>€</div>
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
<script>
	$(window).load(function() {
		$(".form_popup_trigger").click(function() {
			$('#form_shippingAddress_popup').show();
			$('#form_shippingAddress_popup').resize();
			$('body').css({
				'overflow': 'hidden'
			});
		});

		$(".form_billingAddress_trigger").click(function() {
			$('#form_billingAddress_popup').show();
			$('#form_billingAddress_popup').resize();
			$('body').css({
				'overflow': 'hidden'
			});
		});
		$('.form_popup_close').click(function() {
			$('#form_shippingAddress_popup, #form_billingAddress_popup').hide();
			$('body').css({
				'overflow': 'inherit'
			});
		});
	});

	$('input.usa_zipcode').autocomplete({
		source: function(request, response) {
			let level_one = $("#level_one").val();
			$.ajax({
				url: 'ajax_calls.php?action=usa_zipcode',
				dataType: "json",
				data: {
					term: request.term
				},
				success: function(data) {
					response(data);

				}
			});
		},
		minLength: 1,
		select: function(event, ui) {
			var usa_zipcode = $("#usa_zipcode");
			$(usa_zipcode).val(ui.item.value);
			//frm_search.submit();
			//return false;
		}
	});
</script>
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