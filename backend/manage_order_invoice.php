<?php
include("../lib/session_head.php");
$Query = "SELECT * FROM orders AS ord LEFT OUTER JOIN delivery_info AS di ON di.ord_id = ord.ord_id WHERE ord.ord_id = '" . $_REQUEST['ord_id'] . "'";
//print($Query);
$rs = mysqli_query($GLOBALS['conn'], $Query);
if (mysqli_num_rows($rs) > 0) {
	$row = mysqli_fetch_object($rs);
	$ord_id = $row->ord_id;
	$user_id = $row->user_id;
	$customer_id = returnName("customer_id", "users", "user_id", $user_id);
	if (!empty($customer_id)) {
		$user_id = $customer_id;
	}
	$ord_datetime = date('d/m/Y', strtotime($row->ord_datetime));
	$ord_gross_total = price_format($row->ord_gross_total);
	$ord_gst = price_format($row->ord_gst);
	$ord_discount = price_format($row->ord_discount);
	$ord_shipping_charges = price_format($row->ord_shipping_charges);
	$ord_amount = price_format($row->ord_amount + $row->ord_shipping_charges);

	$delivery_info = "";
	$user_company_name = returnName("user_company_name", "users", "user_id", $row->user_id);
	if (!empty($row->dinfo_additional_info)) {
		$delivery_info .= $row->dinfo_additional_info. "<br>";
	} elseif(!empty($user_company_name)) {
		$delivery_info .= $user_company_name . "<br>";
	}
	
	if (!empty($row->dinfo_fname)) {
		$delivery_info .= $row->dinfo_fname . " " . $row->dinfo_lname . "<br>";
	}
	if (!empty($row->dinfo_street)) {
		$delivery_info .= $row->dinfo_street . " " . $row->dinfo_house_no . "<br>";
	}
	if (!empty($row->dinfo_usa_zipcode)) {
		$delivery_info .= $row->dinfo_usa_zipcode . "<br>";
	}
	if (!empty($row->dinfo_countries_id)) {
		$delivery_info .= returnName("countries_name", "countries", "countries_id", $row->dinfo_countries_id) . "<br>";
	}
	if (!empty($row->dinfo_address)) {
		$delivery_info .= $row->dinfo_address . "<br>";
	}
}
?>
<!doctype html>
<html lang="de">

<head>
	<meta charset="utf-8">
	<link rel="icon" type="image/x-icon" href="./assets/images/favicon.ico">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Wacker 24 Backend Control Panel</title>
	<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>

<body style="background-color: #e3e3e6;">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style=" font-family: 'Inter', sans-serif; max-width: 1140px;background-color: #fff;border: 1px solid #e3e3e6; padding: 15px;">
		<tr>
			<td style="background-color: #BF0417; padding: 12px 20px; font-size: 12px;color: #FFF; ">IHR BÜROVERSORGER VON A BIS Z</td>
		</tr>
		<tr>
			<td height="30"></td>
		</tr>
		<tr>
			<td style="width: 65%;">&nbsp;</td>
			<td style="width: 35%;"><img src="./assets/images/pdf_logo.png" alt="" style="max-width: 100%;"></td>
		</tr>
		<tr>
			<td height="20"></td>
		</tr>
		<tr>
			<td style="width: 65%;">&nbsp;</td>
			<td style="width: 35%; font-size: 12px; line-height: 150%; color: #000; text-align: left; ">
				BÜROTECHNIK + SERVICE<br>
				BÜROBEDARF<br>
				BÜRO-EINRICHTUNG<br>
				TECHNISCHER KUNDENDIENST<br>
				COPYSHOP
			</td>
		</tr>
		<tr>
			<td height="40"></td>
		</tr>
		<tr>
			<td colspan="2" style="font-size: 12px;line-height: 150%; color: #000;">
				<b>Wacker Bürocenter GmbH</b> | Chemnitzer Straße 1 | 67433 Neustadt<br>
				<?php print($delivery_info); ?>
			</td>
		</tr>
		<tr>
			<td height="40"></td>
		</tr>
		<tr>
			<td colspan="2" style="font-size: 20px; font-weight: bold;color: #000;">Rechnung</td>
		</tr>
		<tr>
			<td height="40"></td>
		</tr>
		<tr>
			<td colspan="2">
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td style="width: 7%; font-size: 12px; font-weight: bold;color: #000;">&nbsp;</td>
						<td style="width: 30%; font-size: 12px; font-weight: bold;color: #000;">Steuer-Nr</td>
						<td style="width: 15%; font-size: 12px; font-weight: bold;color: #000;">Kunden-Nr</td>
						<td style="width: 19%; font-size: 12px; font-weight: bold;color: #000;">Rechnungs-Nr</td>
						<td style="width: 22%; font-size: 12px; font-weight: bold;color: #000;">Datum</td>
						<td style="width: 7%; font-size: 12px; font-weight: bold;color: #000;">Seite</td>
					</tr>
					<tr>
						<td colspan="6" height="15"></td>
					</tr>
					<tr>
						<td style="width: 7%; font-size: 12px; font-weight: 400;color: #000;">&nbsp;</td>
						<td style="width: 30%; font-size: 12px; font-weight: 400;color: #000;">&nbsp;</td>
						<td style="width: 15%; font-size: 12px; font-weight: 400;color: #000;"><?php print($user_id); ?></td>
						<td style="width: 19%; font-size: 12px; font-weight: 400;color: #000;"><?php print($ord_id); ?></td>
						<td style="width: 22%; font-size: 12px; font-weight: 400;color: #000;"><?php print($ord_datetime); ?></td>
						<td style="width: 7%; font-size: 12px; font-weight: 400;color: #000;">1</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="50"></td>
		</tr>
		<tr>
			<td colspan="2">
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td style="width: 7%; font-size: 12px; font-weight: bold;color: #000;">&nbsp;</td>
						<td style="width: 15%; font-size: 12px; font-weight: bold;color: #000;">Artikel-Nr</td>
						<td style="width: 35%; font-size: 12px; font-weight: bold;color: #000;">Bezeichnung</td>
						<td style="width: 10%; font-size: 12px; font-weight: bold;color: #000;">Preis</td>
						<td style="width: 14%; font-size: 12px; font-weight: bold;color: #000;">Menge Einh</td>
						<td style="width: 12%; font-size: 12px; font-weight: bold;color: #000;">PE Rab.</td>
						<td style="width: 7%; font-size: 12px; font-weight: bold;color: #000;">Betrag</td>
					</tr>
					<tr>
						<td colspan="7" height="5"></td>
					</tr>
					<tr>
						<td colspan="7" style="width: 100%; border-bottom: 1px solid #000;height: 2px;"></td>
					</tr>
					<?php
					$counter = 0;
					$Query = "SELECT oi.*, pro.pro_description_short FROM order_items AS oi LEFT OUTER JOIN products AS pro ON pro.supplier_id = oi.supplier_id WHERE oi.ord_id =  '" . $_REQUEST['ord_id'] . "' ORDER BY oi.oi_id ASC";
					//print($Query);
					$rs = mysqli_query($GLOBALS['conn'], $Query);
					if (mysqli_num_rows($rs) > 0) {
						while ($row = mysqli_fetch_object($rs)) {
							$counter++;
					?>
							<tr>
								<td colspan="7" height="15"></td>
							</tr>
							<tr>
								<td style="width: 7%; font-size: 12px; font-weight: 400;color: #000;"> <?php print($counter); ?> </td>
								<td style="width: 15%; font-size: 12px; font-weight: 400;color: #000;"> <?php print($row->supplier_id); ?> </td>
								<td style="width: 35%; font-size: 12px; font-weight: 400;color: #000; padding-right: 5px"> <?php print($row->pro_description_short); ?> </td>
								<td style="width: 10%; font-size: 12px; font-weight: 400;color: #000;"> <?php print(str_replace(".", ",", $row->oi_amount)); ?> </td>
								<td style="width: 14%; font-size: 12px; font-weight: 400;color: #000;"> <?php print($row->oi_qty); ?> </td>
								<td style="width: 12%; font-size: 12px; font-weight: 400;color: #000;">0</td>
								<td style="width: 7%; font-size: 12px; font-weight: 400;color: #000;"> <?php print(str_replace(".", ",", $row->oi_gross_total)); ?> </td>
							</tr>
					<?php
						}
					}
					?>
				</table>
			</td>
		</tr>
		<tr>
			<td height="50"></td>
		</tr>
		<tr>
			<td colspan="2">
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="5" style="width: 100%; border-bottom: 1px solid #000;height: 2px;"></td>
					</tr>
					<tr>
						<td colspan="5" height="15"></td>
					</tr>
					<tr>
						<td style="width: 35%; font-size: 12px; font-weight: bold;color: #000;">Zahlungsbedingungen</td>
						<td style="width: 15%; font-size: 12px; font-weight: bold;color: #000;">Nettobetrag</td>
						<td style="width: 20%; font-size: 12px; font-weight: bold;color: #000;">Mwstbetrag (<?php print(config_gst * 100); ?>%)</td>
						<td style="width: 15%; font-size: 12px; font-weight: bold;color: #000;">Versand</td>
						<td style="width: 15%; font-size: 12px; font-weight: bold;color: #000;">Rechnungsbetrag</td>
					</tr>
					<tr>
						<td colspan="5" height="15"></td>
					</tr>
					<tr>
						<td style="width: 35%; font-size: 12px; font-weight: 400;color: #000;">&nbsp;</td>
						<td style="width: 15%; font-size: 12px; font-weight: 400;color: #000;"><?php print(str_replace(".", ",", $ord_gross_total)); ?> EUR</td>
						<td style="width: 20%; font-size: 12px; font-weight: 400;color: #000;"><?php print(str_replace(".", ",", $ord_gst)); ?> EUR</td>
						<td style="width: 15%; font-size: 12px; font-weight: 400;color: #000;"><?php print(str_replace(".", ",", $ord_shipping_charges)); ?> EUR</td>
						<td style="width: 15%; font-size: 12px; font-weight: 400;color: #000;"><?php print(str_replace(".", ",", $ord_amount)); ?> EUR</td>
					</tr>
					<tr>
						<td colspan="5" height="15"></td>
					</tr>
					<tr>
						<td colspan="5" style="width: 100%; border-bottom: 1px solid #000; height: 2px;"></td>
					</tr>

				</table>
			</td>
		</tr>
		<tr>
			<td height="50"></td>
		</tr>
		<tr>
			<td colspan="2">
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td style="width: 25%; font-size: 9px; line-height: 150%; padding-right: 5px; color: #000;">
							Geschäftsführer: Christian Wacker und Ursula Wacker<br>
							Sitz der Gesellschaft: Neustadt/W.<br>
							Amtsgericht Ludwigshafen/Rh. HRB 41564<br>
							USt.-ID: DE 149 390 904
						</td>
						<td style="width: 25%; font-size: 9px; line-height: 150%;padding-right: 5px; color: #000;">
							VR-Bank Südpfalz:<br>
							BIC: GENODE61SUW<br>
							IBAN: DE95 5486 2500 0006 7025 70
						</td>
						<td style="width: 25%; font-size: 9px; line-height: 150%;padding-right: 5px; color: #000;">
							Sparkasse Rhein-Haardt:<br>
							BIC: MALADE51DKH<br>
							IBAN: DE67 5465 1240 1000 3079 24
						</td>
						<td style="width: 25%; font-size: 9px; line-height: 150%;color: #000;">
							Wacker Bürocenter GmbH<br>
							Chemnitzer Straße 1<br>
							67433 Neustadt<br>
							Telefon: 06321 9124-0<br>
							Telefax: 06321 9124-99
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="50"></td>
		</tr>
	</table>

</body>

</html>