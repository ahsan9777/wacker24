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
	if (isset($_REQUEST['usa_delivery_instructions_tab_active']) && $_REQUEST['usa_delivery_instructions_tab_active'] > 0) {
		$fields .= ", usa_delivery_instructions_tab_active";
		$values .= ", '" . dbStr(trim($_REQUEST['usa_delivery_instructions_tab_active'])) . "'";
	}
	if (isset($_REQUEST['usa_house_check']) && $_REQUEST['usa_house_check'] > 0) {
		$fields .= ", usa_house_check";
		$values .= ", '" . dbStr(trim($_REQUEST['usa_house_check'])) . "'";
	}
	if (isset($_REQUEST['usa_house_neighbor_name']) && !empty($_REQUEST['usa_house_neighbor_name'])) {
		$fields .= ", usa_house_neighbor_name";
		$values .= ", '" . dbStr(trim($_REQUEST['usa_house_neighbor_name'])) . "'";
	}
	if (isset($_REQUEST['usa_house_neighbor_address']) && !empty($_REQUEST['usa_house_neighbor_address'])) {
		$fields .= ", usa_house_neighbor_address";
		$values .= ", '" . dbStr(trim($_REQUEST['usa_house_neighbor_address'])) . "'";
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
	if (isset($_REQUEST['usa_business_mf_type'])) {
		$usa_business_mf_type = $_REQUEST['usa_business_mf_type'];
		$fields .= ", usa_business_mf_type";
		$values .= ", '" . dbStr(trim($_REQUEST['usa_business_mf_type'])) . "'";
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
	if (isset($_REQUEST['usa_business_ss_type'])) {
		$usa_business_ss_type = $_REQUEST['usa_business_ss_type'];
		$fields .= ", usa_business_ss_type";
		$values .= ", '" . dbStr(trim($_REQUEST['usa_business_ss_type'])) . "'";
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
	if ($usa_business_mf_type > 0) {
		for ($i = 0; $i < 5; $i++) {

			$sbugd_id = getMaximum("shipping_business_ungroup_days", "sbugd_id");
			mysqli_query($GLOBALS['conn'], " INSERT INTO shipping_business_ungroup_days (sbugd_id, usa_id, sbugd_day, sbugd_open, sbugd_close, sbugd_24hour_open, sbugd_orderby) VALUES ('" . $sbugd_id . "', '" . $usa_id . "', '" . dbStr(trim($_REQUEST['sbugd_day'][$i])) . "', '" . dbStr(trim($_REQUEST['sbugd_open'][$i])) . "', '" . dbStr(trim($_REQUEST['sbugd_close'][$i])) . "', '" . dbStr(trim($_REQUEST['sbugd_24hour_open'][$i])) . "', '" . $i . "') ") or die(mysqli_error($GLOBALS['conn']));
		}
	}

	if ($usa_business_ss_type > 0) {
		/*print("<pre>");
		print_r($_REQUEST);
		print("</pre>");die();*/
		for ($i = 5; $i < 7; $i++) {

			$sbugd_id = getMaximum("shipping_business_ungroup_days", "sbugd_id");
			mysqli_query($GLOBALS['conn'], " INSERT INTO shipping_business_ungroup_days (sbugd_id, usa_id, sbugd_day, sbugd_open, sbugd_close, sbugd_24hour_open, sbugd_close_delivery, sbugd_type, sbugd_orderby) VALUES ('" . $sbugd_id . "', '" . $usa_id . "', '" . dbStr(trim($_REQUEST['sbugd_day'][$i])) . "', '" . dbStr(trim($_REQUEST['sbugd_open'][$i])) . "', '" . dbStr(trim($_REQUEST['sbugd_close'][$i])) . "', '" . dbStr(trim($_REQUEST['sbugd_24hour_open'][$i])) . "', '" . dbStr(trim($_REQUEST['sbugd_close_delivery'][$i])) . "', '1', '" . $i . "') ") or die(mysqli_error($GLOBALS['conn']));
		}
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

	if (isset($_REQUEST['usa_delivery_instructions_tab_active'])) {
		$ufields .= ", usa_delivery_instructions_tab_active = '" . dbStr(trim($_REQUEST['usa_delivery_instructions_tab_active'])) . "'";
	}
	if (isset($_REQUEST['usa_house_check'])) {
		$ufields .= ", usa_house_check = '" . dbStr(trim($_REQUEST['usa_house_check'])) . "'";
	}
	if (isset($_REQUEST['usa_house_neighbor_name']) && !empty($_REQUEST['usa_house_neighbor_name'])) {
		$ufields .= ", usa_house_neighbor_name = '" . dbStr(trim($_REQUEST['usa_house_neighbor_name'])) . "'";
	}
	if (isset($_REQUEST['usa_house_neighbor_address']) && !empty($_REQUEST['usa_house_neighbor_address'])) {
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
	} else {
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
	} else {
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
	} else {
		$ufields .= ", usa_business_24h_check = '0'";
	}
	if (isset($_REQUEST['usa_business_close_check']) && $_REQUEST['usa_business_close_check'] == "on") {
		$ufields .= ", usa_business_close_check = '1'";
	} else {
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
		$usa_delivery_instructions_tab = $rsMem->usa_delivery_instructions_tab_active;
		$usa_delivery_instructions_tab_active = (($rsMem->usa_delivery_instructions_tab_active > 0) ? $rsMem->usa_delivery_instructions_tab_active : 1);
		$usa_house_check = $rsMem->usa_house_check;
		$apartment_security_code_display = 'style="display: none;"';
		if ($usa_house_check == "Bei einem Nachbarn") {
			$apartment_security_code_display = 'style="display: block;"';
		}
		$usa_house_neighbor_name = $rsMem->usa_house_neighbor_name;
		$usa_house_neighbor_address = $rsMem->usa_house_neighbor_address;
		$usa_apartment_security_code = $rsMem->usa_apartment_security_code;
		$usa_appartment_call_box = $rsMem->usa_appartment_call_box;
		$usa_appartment_check = $rsMem->usa_appartment_check;
		$usa_business_mf_type = $rsMem->usa_business_mf_type;
		$usa_business_mf_status = $rsMem->usa_business_mf_status;
		$usa_business_mf_uw_status = $rsMem->usa_business_mf_uw_status;
		$usa_business_mf_24h_check = $rsMem->usa_business_mf_24h_check;
		$usa_business_ss_type = $rsMem->usa_business_ss_type;
		$usa_business_ss_status = $rsMem->usa_business_ss_status;
		$usa_business_ss_uw_status = $rsMem->usa_business_ss_uw_status;
		$usa_business_24h_check = $rsMem->usa_business_24h_check;
		$usa_business_close_check = $rsMem->usa_business_close_check;
		$usa_other_check = $rsMem->usa_other_check;
		$usa_default = $rsMem->usa_defualt;
		$monday_to_friday_group_display = 'style="display: block;"';
		$monday_to_friday_ungroup_display = 'style="display: none;"';

		$saturday_to_sunday_group_display = 'style="display: block;"';
		$saturday_to_sunday_ungroup_display = 'style="display: none;"';
		if ($usa_business_mf_type > 0 || $usa_business_ss_type > 0) {
			if ($usa_business_mf_type > 0) {
				$monday_to_friday_group_display = 'style="display: none;"';
				$monday_to_friday_ungroup_display = 'style="display: block;"';
			}
			if ($usa_business_ss_type > 0) {
				$saturday_to_sunday_group_display = 'style="display: none;"';
				$saturday_to_sunday_ungroup_display = 'style="display: block;"';
			}
		}

		$shipping_business_ungroup_days = array();
		$Query = "SELECT * FROM shipping_business_ungroup_days WHERE usa_id = '" . $_REQUEST['usa_id'] . "' ORDER BY sbugd_orderby ASC";
		$rs = mysqli_query($GLOBALS['conn'], $Query);
		if (mysqli_num_rows($rs) > 0) {
			while ($rw = mysqli_fetch_object($rs)) {
				$shipping_business_ungroup_days[] = array(
					$rw->sbugd_orderby => array(
						"sbugd_open" => $rw->sbugd_open,
						"sbugd_close" => $rw->sbugd_close,
						"sbugd_24hour_open" => $rw->sbugd_24hour_open,
						"sbugd_close_delivery" => $rw->sbugd_close_delivery
					)
				);
			}
		}

		/*print("<pre>");
		print_r($shipping_business_ungroup_days);
		print("</pre>"); //die();
		print($shipping_business_ungroup_days[0][0]['sbugd_open']);*/
		//$property_type = array("Haus", "Wohnung", "Geschäft", "Andere");
		$short_detail = '<b>' . $usa_fname . ' ' . $usa_lname . '</b><br> ' . $usa_street . ' ' . $usa_house_no . ', ' . $usa_zipcode . '<br> Grundstückstyp';

		$formHead = "Adressdaten aktualisieren";
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
	$usa_delivery_instructions_tab = 0;
	$usa_delivery_instructions_tab_active = 1;
	$usa_house_check = "";
	$usa_house_neighbor_name = "";
	$usa_house_neighbor_address = "";
	$apartment_security_code_display = 'style="display: none;"';
	$usa_apartment_security_code = "";
	$usa_appartment_call_box = "";
	$usa_appartment_check = "";
	$usa_business_mf_type = 0;
	$usa_business_mf_status = "";
	$usa_business_mf_uw_status = "";
	$usa_business_mf_24h_check = "";
	$usa_business_ss_type = 0;
	$usa_business_ss_status = "";
	$usa_business_ss_uw_status = "";
	$usa_business_24h_check = "";
	$usa_business_close_check = "";
	$usa_other_check = "";
	$usa_default = "";
	$short_detail = "";
	$shipping_business_ungroup_days = array();
	$monday_to_friday_group_display = 'style="display: block;"';
	$monday_to_friday_ungroup_display = 'style="display: none;"';

	$saturday_to_sunday_group_display = 'style="display: block;"';
	$saturday_to_sunday_ungroup_display = 'style="display: none;"';
	$formHead = "Neue Adresse hinzufügen";
}

$start = new DateTime('00:00');
$end = new DateTime('24:00');
$interval = new DateInterval('PT30M'); // 30 minutes

$times = [];

for ($time = clone $start; $time <= $end; $time->add($interval)) {
	$times[] = $time->format('H:i');
}
include("includes/message.php");
?>
<!doctype html>
<html lang="de">

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
									<div class="form_field"><input type="checkbox" name="usa_defualt" id="usa_defualt" <?php print((($usa_default > 0) ? 'checked onclick="return false;"' : '')); ?>> Als Lieferadresse einstellen </div>
								</li>
							</ul>
							<div class="delivery_instructions">
								<input type="hidden" name="usa_delivery_instructions_tab_active" id="usa_delivery_instructions_tab_active" value="<?php print($usa_delivery_instructions_tab) ?>">
								<h3>Lieferungsanweisungen</h3>
								<div class="delivery_toggle"><a href="javascript:void(0)">Notizen, Einstellungen, Codes und mehr<i class="fa fa-angle-down"></i></a> </div>
								<div class="delivery_toggle_show">
									<div class="grnc_tabnav">
										<div class="black"><?php print($short_detail); ?></div>
										<ul class="grnc_tabnav_tabs">
											<li <?php print((($usa_delivery_instructions_tab_active == 1) ? "class='active'" : "")); ?>><a href="#tab1" class="delivery_instructions_tab" data-id="1">Haus</a></li>
											<li <?php print((($usa_delivery_instructions_tab_active == 2) ? "class='active'" : "")); ?>><a href="#tab2" class="delivery_instructions_tab" data-id="2">Wohnung</a></li>
											<li <?php print((($usa_delivery_instructions_tab_active == 3) ? "class='active'" : "")); ?>><a href="#tab3" class="delivery_instructions_tab" data-id="3">Unternehmen</a></li>
											<li <?php print((($usa_delivery_instructions_tab_active == 4) ? "class='active'" : "")); ?>><a href="#tab4" class="delivery_instructions_tab" data-id="4">Sonstiges</a></li>
										</ul>
										<p id="delivery_instructions_text">Einfamilienhaus oder Stadthaus</p>
									</div>
									<div class="grnc_tabnav_content <?php print((($usa_delivery_instructions_tab_active == 1) ? "active" : "hide")); ?>" id="tab1">
										<h4>Wo sollen wir Pakete ablegen, wenn sie nicht in Ihren Briefkasten passen?</h4>
										<ul>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_house_check" class="usa_house_check" id="usa_house_check" value="Terrasse" <?php print((($usa_house_check == "Terrasse") ? 'checked' : '')); ?>></span> <span>Terrasse</span></div>
											</li>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_house_check" class="usa_house_check" id="usa_house_check" value="Garage" <?php print((($usa_house_check == "Garage") ? 'checked' : '')); ?>></span> <span>Garage</span></div>
											</li>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_house_check" class="usa_house_check" id="usa_house_check" value="Vordertür" <?php print((($usa_house_check == "Vordertür") ? 'checked' : '')); ?>></span> <span>Vordertür</span></div>
											</li>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_house_check" class="usa_house_check" id="usa_house_check" value="Garten" <?php print((($usa_house_check == "Garten") ? 'checked' : '')); ?>></span> <span>Garten</span></div>
											</li>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_house_check" class="usa_house_check" id="usa_house_check" value="Schuppen" <?php print((($usa_house_check == "Schuppen") ? 'checked' : '')); ?>></span> <span>Schuppen</span></div>
											</li>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_house_check" class="usa_house_check" id="usa_house_check" value="Bei einem Nachbarn" <?php print((($usa_house_check == "Bei einem Nachbarn") ? 'checked' : '')); ?>></span> <span>Bei einem Nachbarn</span></div>
											</li>
											<li id="usa_apartment_security_code_fields" <?php print($apartment_security_code_display); ?>>
												<div class="form_row">
													<div class="form_left">
														<div class="form_label">Name des Nachbarn</div>
														<div class="form_field"><input type="text" class="gerenric_input" name="usa_house_neighbor_name" id="usa_house_neighbor_name" value="<?php print($usa_house_neighbor_name); ?>" placeholder="Name des Nachbarn"></div>
													</div>
													<div class="form_right">
														<div class="form_label">Adresse des Nachbarn/der Nachbarin</div>
														<div class="form_field"><input type="text" class="gerenric_input" name="usa_house_neighbor_address" id="usa_house_neighbor_address" value="<?php print($usa_house_neighbor_address); ?>" placeholder="Adresse des Nachbarn/der Nachbarin"></div>
													</div>
												</div>
											</li>
											<script>
												$(".usa_house_check").click(function() {
													var selectedValue = $('input[name="usa_house_check"]:checked').val();
													if (selectedValue == "Bei einem Nachbarn") {
														//console.log("Selected value:", selectedValue);
														$("#usa_apartment_security_code_fields").show();
													} else {
														//console.log("No option selected");
														$("#usa_apartment_security_code_fields").hide();
													}
												});
											</script>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_house_check" class="usa_house_check" id="usa_house_check" value="Keine Präferenz" <?php print((($usa_house_check == "Keine Präferenz") ? 'checked' : '')); ?>></span> <span>Keine Präferenz</span></div>
											</li>
										</ul>
									</div>
									<div class="grnc_tabnav_content <?php print((($usa_delivery_instructions_tab_active == 2) ? "active" : "hide")); ?>" id="tab2">
										<h4>Benötigen wir einen Sicherheitscode oder einen Schlüssel um das Gebäude zu betreten?</h4>
										<ul>
											<li>
												<div class="form_label">Sicherheitscode</div>
												<div class="form_field"><input type="text" class="gerenric_input" name="usa_apartment_security_code" id="usa_apartment_security_code" value="<?php print($usa_apartment_security_code); ?>" placeholder="Sicherheitscode für die Tür"></div>
											</li>
											<li>
												<div class="form_label">Gegensprechanlage</div>
												<div class="form_field"><input type="text" class="gerenric_input" name="usa_appartment_call_box" id="usa_appartment_call_box" value="<?php print($usa_appartment_call_box); ?>" placeholder="Nummer oder Name der Gegensprechanlage"></div>
											</li>
											<li>
												<div class="form_field"><input type="checkbox" name="usa_appartment_check" id="usa_appartment_check" <?php print((($usa_appartment_check > 0) ? 'checked' : '')); ?>> Schlüssel oder Token benötigt</div>
											</li>
										</ul>
									</div>
									<div class="grnc_tabnav_content <?php print((($usa_delivery_instructions_tab_active == 3) ? "active" : "hide")); ?>" id="tab3">
										<h4>Wann können an diese Adresse Lieferungen zugestellt werden? </h4>
										<input type="hidden" name="usa_business_mf_type" id="usa_business_mf_type" value="<?php print($usa_business_mf_type); ?>">
										<input type="hidden" name="usa_business_ss_type" id="usa_business_ss_type" value="<?php print($usa_business_ss_type); ?>">
										<ul>
											<li id="monday_to_friday_group_days" <?php print($monday_to_friday_group_display); ?>>
												<div class="form_row">
													<div class="form_left">
														<div class="form_label">Montag - Freitag</div>
														<div class="form_field">
															<select class="gerenric_input" name="usa_business_mf_status" id="usa_business_mf_status">
																<option value="Geöffnet" <?php print((($usa_business_mf_status == "Geöffnet") ? 'selected' : '')); ?>>Geöffnet</option>
																<?php foreach ($times as $t) { ?>
																	<option value="<?php print($t); ?>" <?php print((($usa_business_mf_status == $t) ? 'selected' : '')); ?>><?php print($t); ?></option>
																<?php } ?>
															</select>
														</div>
													</div>
													<div class="form_right">
														<div class="form_label"><a href="javascript: void(0);" id="ungroup_weekdays">Gruppierung aufheben</a></div>
														<div class="form_field">
															<select class="gerenric_input" name="usa_business_mf_uw_status" id="usa_business_mf_uw_status">
																<option value="Geschlossen" <?php print((($usa_business_mf_uw_status == "Geschlossen") ? 'selected' : '')); ?>>Geschlossen</option>
																<?php foreach ($times as $t) { ?>
																	<option value="<?php print($t); ?>" <?php print((($usa_business_mf_uw_status == $t) ? 'selected' : '')); ?>><?php print($t); ?></option>
																<?php } ?>
															</select>
														</div>
													</div>
													<div class="form_field margin_10"><input type="checkbox" name="usa_business_mf_24h_check" id="usa_business_mf_24h_check" <?php print((($usa_business_mf_24h_check > 0) ? 'checked' : '')); ?>> 24 Stunden geöffnet</div>
												</div>
											</li>
											<li id="monday_to_friday_ungroup_days" <?php print($monday_to_friday_ungroup_display); ?>>
												<?php
												$german_days = array("Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag", "Sonntag");
												for ($i = 0; $i < 5; $i++) {
												?>
													<div class="form_row">
														<input type="hidden" name="sbugd_day[]" id="sbugd_day" value="<?php print($german_days[$i]); ?>">
														<div class="form_left">
															<div class="form_label"><?php print($german_days[$i]); ?></div>
															<div class="form_field">
																<select class="gerenric_input" name="sbugd_open[]" id="sbugd_open">
																	<option value="Geöffnet" <?php print(((!empty($shipping_business_ungroup_days) && $shipping_business_ungroup_days[$i][$i]['sbugd_open'] == "Geöffnet") ? 'selected' : '')); ?>>Geöffnet</option>
																	<?php foreach ($times as $t) { ?>
																		<option value="<?php print($t); ?>" <?php print(((!empty($shipping_business_ungroup_days) && $shipping_business_ungroup_days[$i][$i]['sbugd_open'] == $t) ? 'selected' : '')); ?>><?php print($t); ?></option>
																	<?php } ?>
																</select>
															</div>
														</div>
														<div class="form_right">
															<div class="form_label"><?php print((($i == 0) ? '<a href="javascript: void(0);" id="group_weekdays" >Wochentage gruppieren</a>' : '&nbsp;')); ?></div>
															<div class="form_field">
																<select class="gerenric_input" name="sbugd_close[]" id="sbugd_close">
																	<option value="Geschlossen" <?php print(((!empty($shipping_business_ungroup_days) && $shipping_business_ungroup_days[$i][$i]['sbugd_close'] == "Geschlossen") ? 'selected' : '')); ?>>Geschlossen</option>
																	<?php foreach ($times as $t) { ?>
																		<option value="<?php print($t); ?>" <?php print(((!empty($shipping_business_ungroup_days) && $shipping_business_ungroup_days[$i][$i]['sbugd_close'] == $t) ? 'selected' : '')); ?>><?php print($t); ?></option>
																	<?php } ?>
																</select>
															</div>
														</div>
														<input type="hidden" name="sbugd_24hour_open[]" id="sbugd_24hour_open_<?php print($i); ?>" data-id="<?php print($i); ?>" value="<?php print(((!empty($shipping_business_ungroup_days)) ? $shipping_business_ungroup_days[$i][$i]['sbugd_24hour_open'] : 0)); ?>">
														<input type="hidden" name="sbugd_close_delivery[]" id="sbugd_close_delivery_<?php print($i); ?>" data-id="<?php print($i); ?>" value="<?php print(((!empty($shipping_business_ungroup_days)) ? $shipping_business_ungroup_days[$i][$i]['sbugd_close_delivery'] : 0)); ?>">
														<div class="form_field margin_10"><input type="checkbox" name="sbugd_24hour_open_check[]" class="sbugd_24hour_open_check" id="sbugd_24hour_open_check_<?php print($i); ?>" data-id="<?php print($i); ?>" <?php print(((!empty($shipping_business_ungroup_days) && $shipping_business_ungroup_days[$i][$i]['sbugd_24hour_open'] == 1) ? 'checked' : '')); ?>> 24 Stunden geöffnet</div>
													</div>
												<?php } ?>
											</li>
											<script>
												$("#ungroup_weekdays").on("click", function() {
													$("#monday_to_friday_group_days").hide();
													$("#monday_to_friday_ungroup_days").show();
													$("#usa_business_mf_type").val(1);
												});
												$("#group_weekdays").on("click", function() {
													$("#monday_to_friday_ungroup_days").hide();
													$("#monday_to_friday_group_days").show();
													$("#usa_business_mf_type").val(0);
												});
											</script>
											<li id="saturday_to_sunday_group_days" <?php print($saturday_to_sunday_group_display); ?>>
												<div class="form_row">
													<div class="form_left">
														<div class="form_label">Samstag - Sonntag</div>
														<div class="form_field">
															<select class="gerenric_input" name="usa_business_ss_status" id="usa_business_ss_status">
																<option value="Geöffnet" <?php print((($usa_business_ss_status == "Geöffnet") ? 'selected' : '')); ?>>Geöffnet</option>
																<?php foreach ($times as $t) { ?>
																	<option value="<?php print($t); ?>" <?php print((($usa_business_ss_status == $t) ? 'selected' : '')); ?>><?php print($t); ?></option>
																<?php } ?>
															</select>
														</div>
													</div>
													<div class="form_right">
														<div class="form_label"><a href="javascript: void(0);" id="ungroup_weekends">Gruppierung aufheben</a></div>
														<div class="form_field">
															<select class="gerenric_input" name="usa_business_ss_uw_status" id="usa_business_ss_uw_status">
																<option value="Geschlossen" <?php print((($usa_business_ss_uw_status == "Geschlossen") ? 'selected' : '')); ?>>Geschlossen</option>
																<?php foreach ($times as $t) { ?>
																	<option value="<?php print($t); ?>" <?php print((($usa_business_ss_uw_status == $t) ? 'selected' : '')); ?>><?php print($t); ?></option>
																<?php } ?>
															</select>
														</div>
													</div>
													<div class="form_field margin_10"><input type="checkbox" name="usa_business_24h_check" id="usa_business_24h_check" <?php print((($usa_business_24h_check > 0) ? 'checked' : '')); ?>> 24 Stunden geöffnet</div>
													<div class="form_field "><input type="checkbox" name="usa_business_close_check" id="usa_business_close_check" <?php print((($usa_business_close_check > 0) ? 'checked' : '')); ?>> Für Lieferungen geschlossen</div>
												</div>
											</li>
											<li id="saturday_to_sunday_ungroup_days" <?php print($saturday_to_sunday_ungroup_display); ?>>
												<?php
												for ($i = 5; $i < 7; $i++) {
												?>
													<div class="form_row">
														<input type="hidden" name="sbugd_day[]" id="sbugd_day" value="<?php print($german_days[$i]); ?>">
														<div class="form_left">
															<div class="form_label"><?php print($german_days[$i]); ?></div>
															<div class="form_field">
																<select class="gerenric_input" name="sbugd_open[]" id="sbugd_open">
																	<option value="Geöffnet" <?php print(((!empty($shipping_business_ungroup_days) && $shipping_business_ungroup_days[$i][$i]['sbugd_open'] == "Geöffnet") ? 'selected' : '')); ?>>Geöffnet</option>
																	<?php foreach ($times as $t) { ?>
																		<option value="<?php print($t); ?>" <?php print(((!empty($shipping_business_ungroup_days) && $shipping_business_ungroup_days[$i][$i]['sbugd_open'] == $t) ? 'selected' : '')); ?>><?php print($t); ?></option>
																	<?php } ?>
																</select>
															</div>
														</div>
														<div class="form_right">
															<div class="form_label"><?php print((($i == 5) ? '<a href="javascript: void(0);" id="group_weekends" >Gruppe wochenenden</a>' : '&nbsp;')); ?></div>
															<div class="form_field">
																<select class="gerenric_input" name="sbugd_close[]" id="sbugd_close">
																	<option value="Geschlossen" <?php print(((!empty($shipping_business_ungroup_days) && $shipping_business_ungroup_days[$i][$i]['sbugd_close'] ==  "Geschlossen") ? 'selected' : '')); ?>>Geschlossen</option>
																	<?php foreach ($times as $t) { ?>
																		<option value="<?php print($t); ?>" <?php print(((!empty($shipping_business_ungroup_days) && $shipping_business_ungroup_days[$i][$i]['sbugd_close'] == $t) ? 'selected' : '')); ?>><?php print($t); ?></option>
																	<?php } ?>
																</select>
															</div>
														</div>
														<input type="hidden" name="sbugd_24hour_open[]" id="sbugd_24hour_open_<?php print($i); ?>" data-id="<?php print($i); ?>" value="<?php print(((!empty($shipping_business_ungroup_days)) ? $shipping_business_ungroup_days[$i][$i]['sbugd_24hour_open'] : 0)); ?>">
														<input type="hidden" name="sbugd_close_delivery[]" id="sbugd_close_delivery_<?php print($i); ?>" data-id="<?php print($i); ?>" value="<?php print(((!empty($shipping_business_ungroup_days)) ? $shipping_business_ungroup_days[$i][$i]['sbugd_close_delivery'] : 0)); ?>">
														<div class="form_field margin_10"><input type="checkbox" name="sbugd_24hour_open_check[]" class="sbugd_24hour_open_check" id="sbugd_24hour_open_check_<?php print($i); ?>" data-id="<?php print($i); ?>" <?php print(((!empty($shipping_business_ungroup_days) && $shipping_business_ungroup_days[$i][$i]['sbugd_24hour_open'] == 1) ? 'checked' : '')); ?>> 24 Stunden geöffnet</div>
														<div class="form_field margin_10"><input type="checkbox" name="sbugd_close_delivery_check[]" class="sbugd_close_delivery_check" id="sbugd_close_delivery_check_<?php print($i); ?>" data-id="<?php print($i); ?>" <?php print(((!empty($shipping_business_ungroup_days) && $shipping_business_ungroup_days[$i][$i]['sbugd_close_delivery'] == 1) ? 'checked' : '')); ?>> Für Lieferungen geschlossen</div>
													</div>
												<?php } ?>
											</li>
											<script>
												$("#ungroup_weekends").on("click", function() {
													$("#saturday_to_sunday_group_days").hide();
													$("#saturday_to_sunday_ungroup_days").show();
													$("#usa_business_ss_type").val(1);
												});
												$("#group_weekends").on("click", function() {
													$("#saturday_to_sunday_ungroup_days").hide();
													$("#saturday_to_sunday_group_days").show();
													$("#usa_business_ss_type").val(0);
												});
												$(".sbugd_24hour_open_check").on("click", function() {
													//console.log("sbugd_24hour_open_check", $(this).is(':checked'));
													if ($(this).is(':checked') == true) {
														$("#sbugd_24hour_open_" + $(this).attr("data-id")).val(1);
													} else {
														$("#sbugd_24hour_open_" + $(this).attr("data-id")).val(0);
													}
												});
												$(".sbugd_close_delivery_check").on("click", function() {
													//console.log("sbugd_close_delivery_check", $(this).is(':checked'));
													if ($(this).is(':checked') == true) {
														$("#sbugd_close_delivery_" + $(this).attr("data-id")).val(1);
													} else {
														$("#sbugd_close_delivery_" + $(this).attr("data-id")).val(0);
													}
												});
											</script>
										</ul>
									</div>
									<div class="grnc_tabnav_content <?php print((($usa_delivery_instructions_tab_active == 4) ? "active" : "hide")); ?>" id="tab4">
										<h4>Benötigen wir zusätzliche Anweisungen, um an diese Adresse zu liefern?</h4>
										<ul>
											<li>
												<div class="form_label">Zustellungsanweisungen</div>
												<div class="form_field">
													<textarea class="gerenric_input gerenric_textarea" name="cu_message" id="cu_message" placeholder="Geben Sie Details wie Gebäudebeschreibung, einen nahe gelegenen Orientierungspunkt oder andere Navigationsanweisungen an."><?php print($usa_other_check); ?></textarea>
												</div>
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
		$("#usa_delivery_instructions_tab_active").val($(this).attr("data-id"));
	});
	$(".delivery_instructions_tab").on("click", function() {
		let delivery_instructions_tab = $(this).attr("data-id");
		//console.log("delivery_instructions_tab: " + $(this).attr("data-id"));
		let delivery_instructions_text = ["Einfamilienhaus oder Stadthaus", "Wohngebäude mit mehreren Einheiten", "Büro, Ladengeschäft, Hotel, Krankenhaus etc.", ""];
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