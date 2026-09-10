<?php
include("includes/php_includes_top_user_dashboard.php");
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
	$ord_datetime = $row->ord_datetime;
	$ord_gross_total = $row->ord_gross_total;
	$ord_gst = $row->ord_gst;
	$ord_discount = $row->ord_discount;
	$ord_shipping_charges = $row->ord_shipping_charges;
	$ord_amount = $row->ord_amount + $row->ord_shipping_charges;

	$oi_net_total_cancellation = 0;
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
	<base href="<?php print($GLOBALS['siteURL']); ?>">
	<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>

<body style="background-color: #e3e3e6;">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style=" font-family: 'Inter', sans-serif; max-width: 1140px;background-color: #fff;border: 1px solid #e3e3e6; padding: 15px;">
		<tr>
			<td height="20"></td>
		</tr>
		<tr>
			<td style="width: 35%;"><img src="./backend/assets/images/pdf_logo.png" alt="" style="max-width: 100%;"></td>
			<td style="width: 65%; text-align: end;">Elektronischer Lieferschein</td>
		</tr>
		<tr>
			<td height="40"></td>
		</tr>
		<tr>
			<td style="width: 20%;font-size: 12px;line-height: 150%; color: #000;">
				<b>Bestelldatum:</b> <?php print(formatDateGerman($ord_datetime)); ?> <br>
				<b>Bestellnummer:</b> <?php print($ord_id); ?> <br>
				<b>Versanddatum:</b> <?php print(formatDateGerman( date("Y-m-d", strtotime($ord_datetime . " +7 days")) )); ?> <br>
			</td>
			<td style="width: 80%;font-size: 12px;line-height: 150%; color: #000;">
				<b>Lieferadresse:</b><br>
				<?php print($delivery_info); ?>
			</td>
		</tr>
		<tr>
			<td height="40"></td>
		</tr>
		<tr>
			<td colspan="2">
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td style="width: 7%; font-size: 12px; font-weight: bold;color: #000;">&nbsp;</td>
						<td style="width: 15%; font-size: 12px; font-weight: bold;color: #000;">Artikel-Nr</td>
						<td style="width: 68%; font-size: 12px; font-weight: bold;color: #000;">Artikelbeschreibung</td>
						<td style="width: 10%; font-size: 12px; font-weight: bold;color: #000;">Stückzahl</td>
					</tr>
					<tr>
						<td colspan="7" height="5"></td>
					</tr>
					<tr>
						<td colspan="7" style="width: 100%; border-bottom: 1px solid #000;height: 2px;"></td>
					</tr>
					<?php
					$counter = 0;
					$row_add = 7;
					$Query = "SELECT oi.*, pro.pro_description_short, pro.pro_description_long FROM order_items AS oi LEFT OUTER JOIN products AS pro ON pro.supplier_id = oi.supplier_id WHERE oi.ord_id =  '" . $_REQUEST['ord_id'] . "' ORDER BY oi.oi_id ASC";
					//print($Query);
					$rs = mysqli_query($GLOBALS['conn'], $Query);
					if (mysqli_num_rows($rs) > 0) {
						while ($row = mysqli_fetch_object($rs)) {
							$counter++;
							$row_add--;
							$supplier_id = $row->supplier_id;
							$pro_description_short = $row->pro_description_long;
							if($row->oi_type == 2) {
								$supplier_id = "GRATIS fur Sie!";
								$pro_description_short = returnName("fp_title_de AS fp_title", "free_product", "fp_id", $row->fp_id);
							}
							if($row->oi_status == 1){
								$oi_net_total_cancellation = $oi_net_total_cancellation + $row->oi_net_total;
								$pro_description_short = "<del>".$pro_description_short."</del>";
							}
					?>
							<tr>
								<td colspan="7" height="15"></td>
							</tr>
							<tr>
								<td style="width: 7%; font-size: 12px; font-weight: 400;color: #000;"> <?php print($counter); ?> </td>
								<td style="width: 15%; font-size: 12px; font-weight: 400;color: #000;"> <?php print($supplier_id); ?> </td>
								<td style="width: 68%; font-size: 12px; font-weight: 400;color: #000; padding-right: 5px"> <?php print($pro_description_short); ?> </td>
								<td style="width: 10%; font-size: 12px; font-weight: 400;color: #000;"> <?php print($row->oi_qty); ?> </td>
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
		<?php
		for($i = 0; $i <= $row_add; $i++){
			print('<tr><td height="50"></td></tr>');
		}
		?>
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