<?php
include("includes/php_includes_top_user_dashboard.php");
$page = 1;
if (isset($_REQUEST['btn_Addbilling'])) {
	$usa_type = 0;
	if (isset($_REQUEST['btn_Addbilling'])) {
		$usa_type = 1;
	}
	$usa_id = getMaximum("user_shipping_address", "usa_id");
	$usa_defualt = returnName("usa_id", "user_shipping_address", "user_id", $_SESSION["UID"], "AND usa_defualt = '1' AND usa_type = '0'");
	//print($usa_defualt);die();
	if (empty($usa_defualt) && $usa_type == 0) {
		$usa_defualt = 1;
	} else {
		$usa_defualt = 0;
	}
	mysqli_query($GLOBALS['conn'], "INSERT INTO user_shipping_address (usa_id, user_id, usa_type, usa_fname, usa_lname, usa_additional_info, usa_street, usa_house_no, usa_zipcode, usa_contactno, countries_id, usa_defualt) VALUES ('" . $usa_id . "', '" . $_SESSION["UID"] . "', '" . $usa_type . "', '" . dbStr(trim($_REQUEST['usa_fname'])) . "',  '" . dbStr(trim($_REQUEST['usa_lname'])) . "', '" . dbStr(trim($_REQUEST['usa_additional_info'])) . "', '" . dbStr(trim($_REQUEST['usa_street'])) . "', '" . dbStr(trim($_REQUEST['usa_house_no'])) . "', '" . dbStr(trim($_REQUEST['usa_zipcode'])) . "', '" . dbStr(trim($_REQUEST['usa_contactno'])) . "', '" . dbStr(trim($_REQUEST['countries_id'])) . "', '" . $usa_defualt . "')") or die(mysqli_error($GLOBALS['conn']));
	header("Location: adressen/1");
} elseif (isset($_REQUEST['btnUpdate'])) {

	$ufields = "";
	
	if (isset($_REQUEST['usa_delivery_instructions_tab_active'])) {
		$ufields .= ", usa_delivery_instructions_tab_active = '" . dbStr(trim($_REQUEST['usa_delivery_instructions_tab_active'])) . "'";
	}
	if (isset($_REQUEST['usa_house_check'])) {
		$ufields .= ", usa_house_check = '" . dbStr(trim($_REQUEST['usa_house_check'])) . "'";
	}
	if (isset($_REQUEST['usa_house_neighbor_name'])) {
		$ufields .= ", usa_house_neighbor_name = '" . dbStr(trim($_REQUEST['usa_house_neighbor_name'])) . "'";
	}
	if (isset($_REQUEST['usa_house_neighbor_address'])) {
		$ufields .= ", usa_house_neighbor_address = '" . dbStr(trim($_REQUEST['usa_house_neighbor_address'])) . "'";
	}
	if (isset($_REQUEST['usa_apartment_security_code']) && !empty($_REQUEST['usa_apartment_security_code'])) {
		$ufields .= ", usa_apartment_security_code = '" . dbStr(trim($_REQUEST['usa_apartment_security_code'])) . "'";
	}
	if (isset($_REQUEST['usa_appartment_call_box'])) {
		$ufields .= ", usa_appartment_call_box = '" . dbStr(trim($_REQUEST['usa_appartment_call_box'])) . "'";
	}
	if (isset($_REQUEST['usa_appartment_check']) && $_REQUEST['usa_appartment_check'] == "on") {
		$ufields .= ", usa_appartment_check = '1'";
	} else{
		$ufields .= ", usa_appartment_check = '0'";
	}
	if (isset($_REQUEST['usa_business_mf_type'])) {
		$usa_business_mf_type = $_REQUEST['usa_business_mf_type'];
		$ufields .= ", usa_business_mf_type = '" . dbStr(trim($_REQUEST['usa_business_mf_type'])) . "'";
	}
	if (isset($_REQUEST['usa_business_mf_status'])) {
		$ufields .= ", usa_business_mf_status = '" . dbStr(trim($_REQUEST['usa_business_mf_status'])) . "'";
	}
	if (isset($_REQUEST['usa_business_mf_uw_status'])) {
		$ufields .= ", usa_business_mf_uw_status = '" . dbStr(trim($_REQUEST['usa_business_mf_uw_status'])) . "'";
	}
	if (isset($_REQUEST['usa_business_mf_24h_check']) && $_REQUEST['usa_business_mf_24h_check'] == "on") {
		$ufields .= ", usa_business_mf_24h_check = '1'";
	} else{
		$ufields .= ", usa_business_mf_24h_check = '0'";
	}
	if (isset($_REQUEST['usa_business_ss_type'])) {
		$usa_business_ss_type = $_REQUEST['usa_business_ss_type'];
		$ufields .= ", usa_business_ss_type = '" . dbStr(trim($_REQUEST['usa_business_ss_type'])) . "'";
	}
	if (isset($_REQUEST['usa_business_ss_status'])) {
		$ufields .= ", usa_business_ss_status = '" . dbStr(trim($_REQUEST['usa_business_ss_status'])) . "'";
	}
	if (isset($_REQUEST['usa_business_ss_uw_status'])) {
		$ufields .= ", usa_business_ss_uw_status = '" . dbStr(trim($_REQUEST['usa_business_ss_uw_status'])) . "'";
	}
	if (isset($_REQUEST['usa_business_24h_check']) && $_REQUEST['usa_business_24h_check'] == "on") {
		$ufields .= ", usa_business_24h_check = '1'";
	} else{
		$ufields .= ", usa_business_24h_check = '0'";
	}
	if (isset($_REQUEST['usa_business_close_check']) && $_REQUEST['usa_business_close_check'] == "on") {
		$ufields .= ", usa_business_close_check = '1'";
	} else{
		$ufields .= ", usa_business_close_check = '0'";
	}
	if (isset($_REQUEST['usa_other_check'])) {
		$ufields .= ", usa_other_check = '" . dbStr(trim($_REQUEST['usa_other_check'])) . "'";
	}

	mysqli_query($GLOBALS['conn'], "UPDATE user_shipping_address SET " . ltrim($ufields, ", ") . " WHERE usa_id = '" . $_REQUEST['usa_id'] . "' ") or die(mysqli_error($GLOBALS['conn']));
	if ($usa_business_mf_type > 0) {
		for ($i = 0; $i < 5; $i++) {
			$Query = "SELECT * FROM shipping_business_ungroup_days WHERE usa_id = '" . $_REQUEST['usa_id'] . "' AND sbugd_day = '" . $_REQUEST['sbugd_day'][$i] . "'";
			$rs = mysqli_query($GLOBALS['conn'], $Query);
			if (mysqli_num_rows($rs) > 0) {
				$rw = mysqli_fetch_object($rs);

				mysqli_query($GLOBALS['conn'], "UPDATE shipping_business_ungroup_days SET sbugd_open = '" . dbStr(trim($_REQUEST['sbugd_open'][$i])) . "', sbugd_close = '" . dbStr(trim($_REQUEST['sbugd_close'][$i])) . "', sbugd_24hour_open = '" . dbStr(trim($_REQUEST['sbugd_24hour_open'][$i])) . "', sbugd_orderby = '" . $i . "' WHERE sbugd_id = '" . $rw->sbugd_id . "'") or die(mysqli_error($GLOBALS['conn']));
			} else {
				$sbugd_id = getMaximum("shipping_business_ungroup_days", "sbugd_id");
				mysqli_query($GLOBALS['conn'], " INSERT INTO shipping_business_ungroup_days (sbugd_id, usa_id, sbugd_day, sbugd_open, sbugd_close, sbugd_24hour_open, sbugd_orderby) VALUES ('" . $sbugd_id . "', '" . $_REQUEST['usa_id'] . "', '" . dbStr(trim($_REQUEST['sbugd_day'][$i])) . "', '" . dbStr(trim($_REQUEST['sbugd_open'][$i])) . "', '" . dbStr(trim($_REQUEST['sbugd_close'][$i])) . "', '" . dbStr(trim($_REQUEST['sbugd_24hour_open'][$i])) . "', '" . $i . "') ") or die(mysqli_error($GLOBALS['conn']));
			}
		}
	}

	if ($usa_business_ss_type > 0) {
		/*print("<pre>");
		print_r($_REQUEST);
		print("</pre>");die();*/
		for ($i = 5; $i < 7; $i++) {
			$Query = "SELECT * FROM shipping_business_ungroup_days WHERE usa_id = '" . $_REQUEST['usa_id'] . "' AND sbugd_day = '" . $_REQUEST['sbugd_day'][$i] . "'";
			$rs = mysqli_query($GLOBALS['conn'], $Query);
			if (mysqli_num_rows($rs) > 0) {
				$rw = mysqli_fetch_object($rs);
				mysqli_query($GLOBALS['conn'], "UPDATE shipping_business_ungroup_days SET sbugd_open = '" . dbStr(trim($_REQUEST['sbugd_open'][$i])) . "', sbugd_close = '" . dbStr(trim($_REQUEST['sbugd_close'][$i])) . "', sbugd_24hour_open = '" . dbStr(trim($_REQUEST['sbugd_24hour_open'][$i])) . "', sbugd_close_delivery = '" . dbStr(trim($_REQUEST['sbugd_close_delivery'][$i])) . "', sbugd_orderby = '" . $i . "' WHERE sbugd_id = '" . $rw->sbugd_id . "'") or die(mysqli_error($GLOBALS['conn']));
			} else {
				$sbugd_id = getMaximum("shipping_business_ungroup_days", "sbugd_id");
				mysqli_query($GLOBALS['conn'], " INSERT INTO shipping_business_ungroup_days (sbugd_id, usa_id, sbugd_day, sbugd_open, sbugd_close, sbugd_24hour_open, sbugd_close_delivery, sbugd_type, sbugd_orderby) VALUES ('" . $sbugd_id . "', '" . $_REQUEST['usa_id'] . "', '" . dbStr(trim($_REQUEST['sbugd_day'][$i])) . "', '" . dbStr(trim($_REQUEST['sbugd_open'][$i])) . "', '" . dbStr(trim($_REQUEST['sbugd_close'][$i])) . "', '" . dbStr(trim($_REQUEST['sbugd_24hour_open'][$i])) . "', '" . dbStr(trim($_REQUEST['sbugd_close_delivery'][$i])) . "', '1', '" . $i . "') ") or die(mysqli_error($GLOBALS['conn']));
			}
		}
	}

	header("Location: adressen/2");
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
	mysqli_query($GLOBALS['conn'], "DELETE FROM shipping_business_ungroup_days WHERE usa_id = '" . $_REQUEST['usa_id'] . "'") or die(mysqli_error($GLOBALS['conn']));
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

		<!--FORM_POPUP_START-->
		<div class="address_form_popup">
			<div class="inner_popup">
				<div class="form_popup_content">
					<div class="form_popup_heading" > <span id="form_popup_heading_txt"></span> <div class="address_form_popup_close"><i class="fa fa-times"></i></div>
					</div>
					<div class="form_popup_content_inner">
						<form class="gerenric_form" name="frm_delivery_instructions" id="frm_delivery_instructions" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
							<div class="delivery_instructions" id="delivery_instructions">

							</div>
							<div class="form_two_button margin_top_30">
								<button class="gerenric_btn" type="submit" name="btnUpdate">Aktualisieren</button>
								<button type="button" class="gerenric_btn gray_btn address_form_popup_close">Abbrechen</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!--FORM_POPUP_END-->

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
								<a class="gerenric_add_box form_popup_trigger" href="addupdateaddress">
									<div>
										<div class="add_icon"><i class="fa fa-plus"></i></div>
										<div class="add_text">Neue Adresse hinzufügen</div>
									</div>
								</a>
							</div>
							<?php
							$Query = "SELECT usa.*, c.countries_name FROM user_shipping_address AS usa LEFT OUTER JOIN countries AS c ON c.countries_id = usa.countries_id WHERE usa_type = '0' AND user_id = '" . $_SESSION["UID"] . "' ORDER BY usa_defualt DESC";
							$rs = mysqli_query($GLOBALS['conn'], $Query);
							if (mysqli_num_rows($rs) > 0) {
								while ($row = mysqli_fetch_object($rs)) {
									
									if ($row->usa_defualt == 1) {
							?>
										<div class="address_col">
											<div class="address_card" style="border: 4px solid #fffc04;">
												<div class="address_detail">
													<h2> Standard: Lieferadresse</h2>
													<ul>
														<?php if (!empty($row->usa_additional_info)) { ?>
															<li><span> <?php print($row->usa_additional_info); ?> </span></li>
															<li> <?php print($row->usa_fname . " " . $row->usa_lname); ?> </li>
														<?php } else { ?>
															<li><span> <?php print($row->usa_fname . " " . $row->usa_lname); ?> </span></li>
														<?php }?>
														<li> <?php print($row->usa_street." ".$row->usa_house_no); ?> </li>
														<li><?php print($row->usa_zipcode); ?></li>
														<li><?php print($row->countries_name); ?></li>
														<li> <?php print("Telefonnummer : ".$row->usa_contactno); ?> </li>
														<li><a href="javasript:void(0);" class="address_form_popup_trigger" data-id="<?php print($row->usa_id); ?>">Lieferanweisungen hinzufügen</a></li>
													</ul>
													<div class="btn_address">
														<a href="<?php print("addupdateaddress/" . $row->usa_id); ?>">Bearbeiten</a> |
														<a href="<?php print($_SERVER['PHP_SELF'] . "?deleted&usa_id=" . $row->usa_id); ?>" >Entfernen</a>
													</div>
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
															<li> <?php print($row->usa_fname . " " . $row->usa_lname); ?> </li>
														<?php } else { ?>
															<li><span> <?php print($row->usa_fname . " " . $row->usa_lname); ?> </span></li>
														<?php }?>
														<li> <?php print($row->usa_street." ".$row->usa_house_no); ?> </li>
														<li><?php print($row->usa_zipcode); ?></li>
														<li><?php print($row->countries_name); ?></li>
														<li> <?php print("Telefonnummer : ".$row->usa_contactno); ?> </li>
														<li><a href="javasript:void(0);" class="address_form_popup_trigger" data-id="<?php print($row->usa_id); ?>">Lieferanweisungen hinzufügen</a></li>
													</ul>
													<div class="btn_address">
														<a href="<?php print("addupdateaddress/" . $row->usa_id); ?>">Bearbeiten</a> |
														<a href="<?php print($_SERVER['PHP_SELF'] . "?deleted&usa_id=" . $row->usa_id); ?>" >Entfernen</a> |
														<a href="<?php print($_SERVER['PHP_SELF'] . "?set_defualt&usa_id=" . $row->usa_id); ?>">Als Lieferadresse einstellen</a>
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
											<div class="address_detail" style="height: auto;">
												<h2>Rechnungsadresse</h2>
												<ul>
													<?php if (!empty($row->usa_additional_info)) { ?>
														<li><span> <?php print($row->usa_additional_info); ?> </span></li>
														<li> <?php print($row->usa_fname . " " . $row->usa_lname); ?> </li>
													<?php } else { ?>
														<li><span> <?php print($row->usa_fname . " " . $row->usa_lname); ?> </span></li>
													<?php }?>
													<li> <?php print($row->usa_street." ".$row->usa_house_no); ?> </li>
													<li><?php print($row->usa_zipcode); ?></li>
													<li><?php print($row->countries_name); ?></li>
													<li> <?php print("Telefonnummer : ".$row->usa_contactno); ?> </li>
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
												<div class="pd_image"><a href="<?php print(product_detail_url($rw->supplier_id)); ?>"><img src="<?php print(get_image_link(160, $rw->pg_mime_source_url)); ?>" alt=""></a></div>
												<div class="pd_detail">
													<h5><a href="<?php print(product_detail_url($rw->supplier_id)); ?>"> <?php print($rw->pro_udx_seo_epag_title); ?> </a></h5>
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
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format(((config_site_special_price > 0 && $rw->pbp_special_price_without_tax > 0) ? $rw->pbp_special_price_without_tax : $rw->pbp_price_without_tax)) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $rw->pbp_special_price_without_tax > 0) ? $rw->pbp_special_price_without_tax : $rw->pbp_price_without_tax), $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format(((config_site_special_price > 0 && $rw->pbp_special_price_amount > 0) ? $rw->pbp_special_price_amount : $rw->pbp_price_amount)) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $rw->pbp_special_price_amount > 0) ? $rw->pbp_special_price_amount : $rw->pbp_price_amount), $special_price['usp_discounted_value'], $rw->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
													<?php } else { ?>
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format(((config_site_special_price > 0 && $rw->pbp_special_price_without_tax > 0) ? $rw->pbp_special_price_without_tax : $rw->pbp_price_without_tax))); ?>€</div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format(((config_site_special_price > 0 && $rw->pbp_special_price_amount > 0) ? $rw->pbp_special_price_amount : $rw->pbp_price_amount))); ?>€</div>
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
												<div class="pd_image"><a href="<?php print(product_detail_url($rw->supplier_id)); ?>"><img src="<?php print(get_image_link(160, $rw->pg_mime_source_url)); ?>" alt=""></a></div>
												<div class="pd_detail">
													<h5><a href="<?php print(product_detail_url($rw->supplier_id)); ?>"> <?php print($rw->pro_udx_seo_epag_title); ?> </a></h5>
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
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format(((config_site_special_price > 0 && $rw->pbp_special_price_without_tax > 0) ? $rw->pbp_special_price_without_tax : $rw->pbp_price_without_tax)) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $rw->pbp_special_price_without_tax > 0) ? $rw->pbp_special_price_without_tax : $rw->pbp_price_without_tax), $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format(((config_site_special_price > 0 && $rw->pbp_special_price_amount > 0) ? $rw->pbp_special_price_amount : $rw->pbp_price_amount)) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $rw->pbp_special_price_amount > 0) ? $rw->pbp_special_price_amount : $rw->pbp_price_amount), $special_price['usp_discounted_value'], $rw->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
													<?php } else { ?>
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format(((config_site_special_price > 0 && $rw->pbp_special_price_without_tax > 0) ? $rw->pbp_special_price_without_tax : $rw->pbp_price_without_tax))); ?>€</div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format(((config_site_special_price > 0 && $rw->pbp_special_price_amount > 0) ? $rw->pbp_special_price_amount : $rw->pbp_price_amount))); ?>€</div>
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
	$(window).load(function() {
		$(".address_form_popup_trigger").click(function() {
			let usa_id = $(this).attr("data-id");
			//console.log("address_form_popup_trigger: "+usa_id);
			$.ajax({
				url: 'ajax_calls.php?action=delivery_instructions',
				method: 'POST',
				data: {
					usa_id: usa_id
				},
				success: function(response) {

					//console.log("response = "+response);
					const obj = JSON.parse(response);
					//console.log(obj);
					if (obj.status == 1) {
						$("#delivery_instructions").html(obj.delivery_instructions);
						$("#form_popup_heading_txt").text(obj.form_popup_heading_txt);
						tabnav_script();
						$('.address_form_popup').show();
						$('.address_form_popup').resize();
						$('body').css({
							'overflow': 'hidden'
						});
					}
				}
			});
		});
		$('.address_form_popup_close').click(function() {
			$('.address_form_popup').hide();
			$('body').css({
				'overflow': 'inherit'
			});
		});

		function tabnav_script() {
			$(document).ready(function() {
				$('.grnc_tabnav_tabs > li > a').click(function(event) {
					event.preventDefault();
					var active_tab_selector = $('.grnc_tabnav_tabs > li.active > a').attr('href');
					var actived_nav = $('.grnc_tabnav_tabs > li.active');
					actived_nav.removeClass('active');
					$(this).parents('li').addClass('active');
					$(active_tab_selector).removeClass('active');
					$(active_tab_selector).addClass('hide');
					var target_tab_selector = $(this).attr('href');
					$(target_tab_selector).removeClass('hide');
					$(target_tab_selector).addClass('active');
				});
			});

			$(".delivery_instructions_tab").on("click", function() {
				let delivery_instructions_tab = $(this).attr("data-id");
				//console.log("delivery_instructions_tab: " + $(this).attr("data-id"));
				let delivery_instructions_text = ["Einfamilienhaus oder Stadthaus", "Wohngebäude mit mehreren Einheiten", "Büro, Ladengeschäft, Hotel, Krankenhaus etc.", ""];
				$("#delivery_instructions_text").text(delivery_instructions_text[delivery_instructions_tab - 1]);
			});
		}
	});
</script>
<?php include("includes/bottom_js.php"); ?>
<script>
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

</html>