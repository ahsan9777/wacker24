<?php
include("includes/php_includes_top_user_dashboard.php");
$page = 1;
if (isset($_REQUEST['btnAdd'])) {
	//print_r($_REQUEST);die();
	$usa_type = 0;
	$usa_id = getMaximum("user_shipping_address", "usa_id");
	//$usa_defualt = returnName("usa_id", "user_shipping_address", "user_id", $_SESSION["UID"], "AND usa_defualt = '1' AND usa_type = '0'");
	//print($usa_defualt);die();
	$usa_defualt = 0;
	$fields = "";
	$values = "";
	if (isset($_REQUEST['usa_defualt']) && $_REQUEST['usa_defualt'] == "on") {
		$usa_defualt = 1;
	}
	if (isset($_REQUEST['usa_additional_info']) && !empty($_REQUEST['usa_additional_info']) && $_SESSION["Utype"] == 4) {
		$fields .= ", usa_additional_info";
		$values .= ", '" . dbStr(trim($_REQUEST['usa_additional_info'])) . "'";
	}
	if (isset($_REQUEST['usa_house_check']) && $_REQUEST['usa_house_check'] > 0) {
		$fields .= ", usa_house_check";
		$values .= ", '" . dbStr(trim($_REQUEST['usa_house_check'])) . "'";
	}
	if (isset($_REQUEST['usa_apartment_security_code']) && !empty($_REQUEST['usa_apartment_security_code'])) {
		$fields .= ", usa_apartment_security_code";
		$values .= ", '" . dbStr(trim($_REQUEST['usa_apartment_security_code'])) . "'";
	}
	if (isset($_REQUEST['usa_appartment_call_box']) && !empty($_REQUEST['usa_appartment_call_box'])) {
		$fields .= ", usa_appartment_call_box";
		$values .= ", '" . dbStr(trim($_REQUEST['usa_appartment_call_box'])) . "'";
	}
	if (isset($_REQUEST['usa_appartment_check']) && $_REQUEST['usa_appartment_check'] == "on") {
		$fields .= ", usa_appartment_check";
		$values .= ", '1'";
	}
	if (isset($_REQUEST['usa_business_mf_status']) && $_REQUEST['usa_business_mf_status'] > 0) {
		$fields .= ", usa_business_mf_status";
		$values .= ", '" . dbStr(trim($_REQUEST['usa_business_mf_status'])) . "'";
	}
	if (isset($_REQUEST['usa_business_mf_uw_status']) && $_REQUEST['usa_business_mf_uw_status'] > 0) {
		$fields .= ", usa_business_mf_uw_status";
		$values .= ", '" . dbStr(trim($_REQUEST['usa_business_mf_uw_status'])) . "'";
	}
	if (isset($_REQUEST['usa_business_mf_24h_check']) && $_REQUEST['usa_business_mf_24h_check'] == "on") {
		$fields .= ", usa_business_mf_24h_check";
		$values .= ", '1'";
	}
	if (isset($_REQUEST['usa_business_ss_status']) && $_REQUEST['usa_business_ss_status'] > 0) {
		$fields .= ", usa_business_ss_status";
		$values .= ", '" . dbStr(trim($_REQUEST['usa_business_ss_status'])) . "'";
	}
	if (isset($_REQUEST['usa_business_ss_uw_status']) && $_REQUEST['usa_business_ss_uw_status'] > 0) {
		$fields .= ", usa_business_ss_uw_status";
		$values .= ", '" . dbStr(trim($_REQUEST['usa_business_ss_uw_status'])) . "'";
	}
	if (isset($_REQUEST['usa_business_24h_check']) && $_REQUEST['usa_business_24h_check'] == "on") {
		$fields .= ", usa_business_24h_check";
		$values .= ", '1'";
	}
	if (isset($_REQUEST['usa_business_close_check']) && $_REQUEST['usa_business_close_check'] == "on") {
		$fields .= ", usa_business_close_check";
		$values .= ", '1'";
	}
	if (isset($_REQUEST['usa_other_check']) && $_REQUEST['usa_other_check'] > 0) {
		$fields .= ", usa_other_check";
		$values .= ", '" . dbStr(trim($_REQUEST['usa_other_check'])) . "'";
	}
	mysqli_query($GLOBALS['conn'], "INSERT INTO user_shipping_address (usa_id, user_id, usa_type, usa_fname, usa_lname, usa_street, usa_house_no, usa_zipcode, usa_contactno, countries_id, usa_defualt " . $fields . ") VALUES ('" . $usa_id . "', '" . $_SESSION["UID"] . "', '" . $usa_type . "', '" . dbStr(trim($_REQUEST['usa_fname'])) . "',  '" . dbStr(trim($_REQUEST['usa_lname'])) . "', '" . dbStr(trim($_REQUEST['usa_street'])) . "', '" . dbStr(trim($_REQUEST['usa_house_no'])) . "', '" . dbStr(trim($_REQUEST['usa_zipcode'])) . "', '" . dbStr(trim($_REQUEST['usa_contactno'])) . "', '" . dbStr(trim($_REQUEST['countries_id'])) . "', '" . $usa_defualt . "' " .	$values . ")") or die(mysqli_error($GLOBALS['conn']));
	if ($usa_defualt > 0) {
		mysqli_query($GLOBALS['conn'], "UPDATE user_shipping_address SET usa_defualt = '0' WHERE usa_type = '0' AND user_id = '" . $_SESSION["UID"] . "' AND usa_id != '" . $usa_id . "'") or die(mysqli_error($GLOBALS['conn']));
	}
	header("Location: adressen/1");
} elseif (isset($_REQUEST['btnUpdate'])) {

	$ufields = "";
	if (isset($_REQUEST['usa_fname'])) {
		$ufields .= ", usa_fname = '" . dbStr(trim($_REQUEST['usa_fname'])) . "'";
	}
	if (isset($_REQUEST['usa_lname'])) {
		$ufields .= ", usa_lname = '" . dbStr(trim($_REQUEST['usa_lname'])) . "'";
	}
	if (isset($_REQUEST['usa_street'])) {
		$ufields .= ", usa_street = '" . dbStr(trim($_REQUEST['usa_street'])) . "'";
	}
	if (isset($_REQUEST['usa_house_no'])) {
		$ufields .= ", usa_house_no = '" . dbStr(trim($_REQUEST['usa_house_no'])) . "'";
	}
	if (isset($_REQUEST['usa_zipcode'])) {
		$ufields .= ", usa_zipcode = '" . dbStr(trim($_REQUEST['usa_zipcode'])) . "'";
	}
	if (isset($_REQUEST['usa_contactno'])) {
		$ufields .= ", usa_contactno = '" . dbStr(trim($_REQUEST['usa_contactno'])) . "'";
	}
	if (isset($_REQUEST['countries_id'])) {
		$ufields .= ", countries_id = '" . dbStr(trim($_REQUEST['countries_id'])) . "'";
	}
	if (isset($_REQUEST['usa_additional_info']) && $_SESSION["Utype"] == 4) {
		$ufields .= ", usa_additional_info = '" . dbStr(trim($_REQUEST['usa_additional_info'])) . "'";
	}
	if (isset($_REQUEST['usa_defualt']) && $_REQUEST['usa_defualt'] == "on") {
		$ufields .= ", usa_defualt = '1'";
		mysqli_query($GLOBALS['conn'], "UPDATE user_shipping_address SET usa_defualt = '0' WHERE usa_type = '0' AND user_id = '" . $_SESSION["UID"] . "' AND usa_id != '" . $_REQUEST['usa_id'] . "'") or die(mysqli_error($GLOBALS['conn']));
	}

	if (isset($_REQUEST['usa_house_check'])) {
		$ufields .= ", usa_house_check = '" . dbStr(trim($_REQUEST['usa_house_check'])) . "'";
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

	header("Location: adressen/2");
}

if (isset($_REQUEST['usa_id'])) {

	$rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM user_shipping_address WHERE usa_id = " . $_REQUEST['usa_id']);

	if (mysqli_num_rows($rsM) > 0) {
		$rsMem = mysqli_fetch_object($rsM);

		$user_id = $rsMem->user_id;
		$old_user_id = $rsMem->old_user_id;
		$usa_type = $rsMem->usa_type;
		$usa_fname = $rsMem->usa_fname;
		$usa_lname = $rsMem->usa_lname;
		$usa_address = $rsMem->usa_address;
		$usa_additional_info = $rsMem->usa_additional_info;
		$usa_street = $rsMem->usa_street;
		$usa_house_no = $rsMem->usa_house_no;
		$usa_zipcode = $rsMem->usa_zipcode;
		$usa_contactno = $rsMem->usa_contactno;
		$countries_id = $rsMem->countries_id;
		$usa_house_check = $rsMem->usa_house_check;
		$usa_apartment_security_code = $rsMem->usa_apartment_security_code;
		$usa_appartment_call_box = $rsMem->usa_appartment_call_box;
		$usa_appartment_check = $rsMem->usa_appartment_check;
		$usa_business_mf_status = $rsMem->usa_business_mf_status;
		$usa_business_mf_uw_status = $rsMem->usa_business_mf_uw_status;
		$usa_business_mf_24h_check = $rsMem->usa_business_mf_24h_check;
		$usa_business_ss_status = $rsMem->usa_business_ss_status;
		$usa_business_ss_uw_status = $rsMem->usa_business_ss_uw_status;
		$usa_business_24h_check = $rsMem->usa_business_24h_check;
		$usa_business_close_check = $rsMem->usa_business_close_check;
		$usa_other_check = $rsMem->usa_other_check;
		$usa_default = $rsMem->usa_defualt; // Assuming it's a typo

		$formHead = "Update Address Info";
	}
} else {
	$user_id = "";
	$old_user_id = "";
	$usa_type = "";
	$usa_fname = "";
	$usa_lname = "";
	$usa_address = "";
	$usa_additional_info = "";
	$usa_street = "";
	$usa_house_no = "";
	$usa_zipcode = "";
	$usa_contactno = "";
	$countries_id = 81;
	$usa_house_check = "";
	$usa_apartment_security_code = "";
	$usa_appartment_call_box = "";
	$usa_appartment_check = "";
	$usa_business_mf_status = "";
	$usa_business_mf_uw_status = "";
	$usa_business_mf_24h_check = "";
	$usa_business_ss_status = "";
	$usa_business_ss_uw_status = "";
	$usa_business_24h_check = "";
	$usa_business_close_check = "";
	$usa_other_check = "";
	$usa_default = "";

	$formHead = "Add New Address";
}
include("includes/message.php");
?>
<!doctype html>
<html>

<head>
	<?php include("includes/html_header.php"); ?>
	<script>
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
	</script>
	<script>
		$(document).ready(function() {
			$(".delivery_toggle").click(function() {
				$(".delivery_toggle_show").toggle();
			});
		});
	</script>
</head>

<body class="body-white">
	<div id="container" align="center">

		<!--HEADER_SECTION_START-->
		<?php include("includes/navigation.php"); ?>
		<!--HEADER_SECTION_END-->

		<!--BREADCRUMB_SECTION_START-->
		<div class="gerenric_breadcrumb">
			<div class="page_width_1480">
				<div class="breadcrumb_inner">
					<ul>
						<li><a href="adressen">Meine Daten</a></li>
						<li><a href="javascript:void(0)">Adressen</a></li>
					</ul>
				</div>
			</div>
		</div>
		<!--BREADCRUMB_SECTION_END-->

		<!--REGISTER_PAGE_START-->
		<section class="login_page register_page">
			<div class="page_width">
				<div class="login_inner">
					<div class="login_box">
						<h2><?php print($formHead); ?></h2>
						<form class="gerenric_form" name="frm" id="frmaddress" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
							<ul>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">Vorname</div>
											<div class="form_field"><input type="text" name="usa_fname" id="usa_fname" value="<?php print($usa_fname); ?>" class="gerenric_input" required></div>
										</div>
										<div class="form_right">
											<div class="form_label">Nachname</div>
											<div class="form_field"><input type="text" name="usa_lname" id="usa_lname" value="<?php print($usa_lname); ?>" class="gerenric_input"></div>
										</div>
									</div>
								</li>
								<?php if ($_SESSION["Utype"] == 4) { ?>
									<li>
										<div class="form_label">Zusatz</div>
										<div class="form_field"><input type="text" name="usa_additional_info" id="usa_additional_info" value="<?php print($usa_additional_info); ?>" class="gerenric_input"></div>
									</li>
								<?php } ?>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">Straße</div>
											<div class="form_field"><input type="text" name="usa_street" id="usa_street" value="<?php print($usa_street); ?>" class="gerenric_input" required></div>
										</div>
										<div class="form_right">
											<div class="form_label">Hausnr</div>
											<div class="form_field"><input type="text" name="usa_house_no" id="usa_house_no" value="<?php print($usa_house_no); ?>" class="gerenric_input" required></div>
										</div>
									</div>
								</li>
								<li>
									<div class="form_label">PLZ / Ort</div>
									<div class="form_field">
										<input type="text" name="usa_zipcode" id="usa_zipcode" class="gerenric_input usa_zipcode" value="<?php print($usa_zipcode); ?>" required>
									</div>
								</li>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">Telefon</div>
											<div class="form_field"><input type="text" name="usa_contactno" id="usa_contactno" value="<?php print($usa_contactno); ?>" class="gerenric_input" required></div>
										</div>
										<div class="form_right">
											<div class="form_label">Land</div>
											<div class="form_field">
												<select class="gerenric_input" name="countries_id" id="countries_id">
													<?php FillSelected2("countries", "countries_id", "countries_name", $countries_id, "countries_id > 0"); ?>
												</select>
											</div>
										</div>
									</div>
								</li>
								<li>
									<div class="form_field"><input type="checkbox" name="usa_defualt" id="usa_defualt" <?php print((($usa_default > 0) ? 'checked onclick="return false;"' : '')); ?>> Make this my defualt address </div>
								</li>
							</ul>
							<div class="delivery_instructions">
								<h3>Delivery Instructions</h3>
								<div class="delivery_toggle"><a href="javascript:void(0)">Add Preference, notes, access
										codes and more <i class="fa fa-angle-down"></i></a> </div>
								<div class="delivery_toggle_show">
									<div class="grnc_tabnav">
										<ul class="grnc_tabnav_tabs">
											<li class="active"><a href="#tab1" class="delivery_instructions_tab" data-id="1">House</a></li>
											<li><a href="#tab2" class="delivery_instructions_tab" data-id="2">Apartment</a></li>
											<li><a href="#tab3" class="delivery_instructions_tab" data-id="3">Business</a></li>
											<li><a href="#tab4" class="delivery_instructions_tab" data-id="4">Other</a></li>
										</ul>
										<p id="delivery_instructions_text">Single Family home or terraced house</p>
									</div>
									<div class="grnc_tabnav_content active" id="tab1">
										<h4>Where should we leave packages when they dono't fit in your letter box? </h4>
										<ul>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_house_check" id="usa_house_check" value="1" <?php print((($usa_house_check == 1) ? 'checked' : '')); ?>></span> <span>Tarrace</span></div>
											</li>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_house_check" id="usa_house_check" value="2" <?php print((($usa_house_check == 2) ? 'checked' : '')); ?>></span> <span>Garage</span></div>
											</li>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_house_check" id="usa_house_check" value="3" <?php print((($usa_house_check == 3) ? 'checked' : '')); ?>></span> <span>Front Door</span></div>
											</li>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_house_check" id="usa_house_check" value="4" <?php print((($usa_house_check == 4) ? 'checked' : '')); ?>></span> <span>Garden</span></div>
											</li>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_house_check" id="usa_house_check" value="5" <?php print((($usa_house_check == 5) ? 'checked' : '')); ?>></span> <span>Shed</span></div>
											</li>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_house_check" id="usa_house_check" value="6" <?php print((($usa_house_check == 6) ? 'checked' : '')); ?>></span> <span>With a neighbour</span></div>
											</li>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_house_check" id="usa_house_check" value="7" <?php print((($usa_house_check == 7) ? 'checked' : '')); ?>></span> <span>None of the above</span></div>
											</li>
										</ul>
									</div>
									<div class="grnc_tabnav_content hide" id="tab2">
										<h4>Do we need a security code, call box number, or key to access this building?</h4>
										<ul>
											<li>
												<div class="form_label">Security code</div>
												<div class="form_field"><input type="text" class="gerenric_input" name="usa_apartment_security_code" id="usa_apartment_security_code" value="<?php print($usa_apartment_security_code); ?>" placeholder="Security code for the door"></div>
											</li>
											<li>
												<div class="form_label">Call Box</div>
												<div class="form_field"><input type="text" class="gerenric_input" name="usa_appartment_call_box" id="usa_appartment_call_box" value="<?php print($usa_appartment_call_box); ?>" placeholder="Call box number or name"></div>
											</li>
											<li>
												<div class="form_field"><input type="checkbox" name="usa_appartment_check" id="usa_appartment_check" <?php print((($usa_appartment_check > 0) ? 'checked' : '')); ?>> key or fob required for delivery</div>
											</li>
										</ul>
									</div>
									<div class="grnc_tabnav_content hide" id="tab3">
										<h4>When is this address open for deliveies? </h4>
										<ul>
											<li>
												<div class="form_row">
													<div class="form_left">
														<div class="form_label">Monday - Firdya</div>
														<div class="form_field">
															<select class="gerenric_input" name="usa_business_mf_status" id="usa_business_mf_status">
																<option value="0" <?php print((($usa_business_mf_status == 0) ? 'selected' : '')); ?>>Closed</option>
																<option value="1" <?php print((($usa_business_mf_status == 1) ? 'selected' : '')); ?>>Open</option>
															</select>
														</div>
													</div>
													<div class="form_right">
														<div class="form_label">Ungroup weekends</div>
														<div class="form_field">
															<select class="gerenric_input" name="usa_business_mf_uw_status" id="usa_business_mf_uw_status">
																<option value="0" <?php print((($usa_business_mf_uw_status == 0) ? 'selected' : '')); ?>>Closed</option>
																<option value="1" <?php print((($usa_business_mf_uw_status == 1) ? 'selected' : '')); ?>>Open</option>
															</select>
														</div>
													</div>
												</div>
											</li>
											<li>
												<div class="form_field"><input type="checkbox" name="usa_business_mf_24h_check" id="usa_business_mf_24h_check" <?php print((($usa_business_mf_24h_check > 0) ? 'checked' : '')); ?>> Open 24 Hours</div>
											</li>
											<li>
												<div class="form_row">
													<div class="form_left">
														<div class="form_label">Saturday - Sunday</div>
														<div class="form_field">
															<select class="gerenric_input" name="usa_business_ss_status" id="usa_business_ss_status">
																<option value="0" <?php print((($usa_business_ss_status == 0) ? 'selected' : '')); ?>>Closed</option>
																<option value="1" <?php print((($usa_business_ss_status == 1) ? 'selected' : '')); ?>>Open</option>
															</select>
														</div>
													</div>
													<div class="form_right">
														<div class="form_label">Ungroup weekends</div>
														<div class="form_field">
															<select class="gerenric_input" name="usa_business_ss_uw_status" id="usa_business_ss_uw_status">
																<option value="0" <?php print((($usa_business_ss_uw_status == 0) ? 'selected' : '')); ?>>Closed</option>
																<option value="1" <?php print((($usa_business_ss_uw_status == 1) ? 'selected' : '')); ?>>Open</option>
															</select>
														</div>
													</div>
												</div>
											</li>
											<li>
												<div class="form_field"><input type="checkbox" name="usa_business_24h_check" id="usa_business_24h_check" <?php print((($usa_business_24h_check > 0) ? 'checked' : '')); ?>> Open 24 Hours</div>
											</li>
											<li>
												<div class="form_field"><input type="checkbox" name="usa_business_close_check" id="usa_business_close_check" <?php print((($usa_business_close_check > 0) ? 'checked' : '')); ?>> Closed for deliveries
												</div>
											</li>
										</ul>
									</div>
									<div class="grnc_tabnav_content hide" id="tab4">
										<h4>Where should we leave packages when they dono't fit in your letter box? </h4>
										<ul>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_other_check" id="usa_other_check" value="1" <?php print((($usa_other_check == 1) ? 'checked' : '')); ?>></span> <span>Tarrace</span></div>
											</li>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_other_check" id="usa_other_check" value="2" <?php print((($usa_other_check == 2) ? 'checked' : '')); ?>></span> <span>Garage</span></div>
											</li>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_other_check" id="usa_other_check" value="3" <?php print((($usa_other_check == 3) ? 'checked' : '')); ?>></span> <span>Front Door</span></div>
											</li>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_other_check" id="usa_other_check" value="4" <?php print((($usa_other_check == 4) ? 'checked' : '')); ?>></span> <span>Garden</span></div>
											</li>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_other_check" id="usa_other_check" value="5" <?php print((($usa_other_check == 5) ? 'checked' : '')); ?>></span> <span>Shed</span></div>
											</li>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_other_check" id="usa_other_check" value="6" <?php print((($usa_other_check == 6) ? 'checked' : '')); ?>></span> <span>With a neighbour</span></div>
											</li>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_other_check" id="usa_other_check" value="7" <?php print((($usa_other_check == 7) ? 'checked' : '')); ?>></span> <span>None of the above</span></div>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="form_two_button margin_top_30">
								<?php if (isset($_REQUEST['usa_id'])) { ?>
									<button class="gerenric_btn" type="submit" name="btnUpdate">Aktualisieren</button>
								<?php } else { ?>
									<button class="gerenric_btn" type="submit" name="btnAdd">Aktualisieren</button>
								<?php } ?>
								<a href="adressen" class="gerenric_btn gray_btn form_popup_close">Abbrechen</a>
							</div>
						</form>
					</div>

				</div>
			</div>
		</section>
		<!--REGISTER_PAGE_END-->

		<!--FOOTER_SECTION_START-->
		<div id="scroll_top">Zurück zum Seitenanfang</div>
		<?php include("includes/footer.php"); ?>
		<!--FOOTER_SECTION_END-->

	</div>

</body>
<?php include("includes/bottom_js.php"); ?>
<script>
	$(".delivery_instructions_tab").on("click", function() {
		let delivery_instructions_tab = $(this).attr("data-id");
		//console.log("delivery_instructions_tab: " + $(this).attr("data-id"));
		let delivery_instructions_text = ["Single Family home or terraced house", "Multi-unit residential building", "Office, retail srore, hotel, hospital etc.", "Single Family home or terraced house"];
		$("#delivery_instructions_text").text(delivery_instructions_text[delivery_instructions_tab - 1]);
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

</html>