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
	$ord_datetime_iso = date('Y-m-d', strtotime($row->ord_datetime));
	$ord_gross_total = price_format($row->ord_gross_total);
	$ord_gst = price_format($row->ord_gst);
	$ord_discount = price_format($row->ord_discount);
	$ord_shipping_charges = price_format($row->ord_shipping_charges);
	$ord_amount = price_format($row->ord_amount + $row->ord_shipping_charges);

	// ZUGFeRD specific variables
	$invoice_id = $ord_id;
	$invoice_date = $ord_datetime_iso;
	$currency = 'EUR';
	$tax_percentage = config_gst * 100;
	$net_total = $row->ord_gross_total;
	$tax_total = $row->ord_gst;
	$grand_total = $row->ord_amount + $row->ord_shipping_charges;
	$payment_terms = 'Zahlbar innerhalb von 30 Tagen ohne Abzug';

	$delivery_info = "";
	$user_company_name = returnName("user_company_name", "users", "user_id", $row->user_id);
	if (!empty($row->dinfo_additional_info)) {
		$delivery_info .= $row->dinfo_additional_info . "<br>";
	} elseif (!empty($user_company_name)) {
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
		$country_name = returnName("countries_name", "countries", "countries_id", $row->dinfo_countries_id);
		$delivery_info .= $country_name . "<br>";
	}
	if (!empty($row->dinfo_address)) {
		$delivery_info .= $row->dinfo_address . "<br>";
	}

	// Get order items for ZUGFeRD
	$order_items = [];
	$item_counter = 0;
	$Query_items = "SELECT oi.*, pro.pro_description_short, pro.pro_ean FROM order_items AS oi LEFT OUTER JOIN products AS pro ON pro.supplier_id = oi.supplier_id WHERE oi.ord_id =  '" . $_REQUEST['ord_id'] . "' ORDER BY oi.oi_id ASC";
	$rs_items = mysqli_query($GLOBALS['conn'], $Query_items);
	if (mysqli_num_rows($rs_items) > 0) {
		while ($item_row = mysqli_fetch_object($rs_items)) {
			$item_counter++;
			$order_items[] = $item_row;
		}
	}
}
?>
<!doctype html>
<html lang="de" xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="urn:un:unece:uncefact:data:standard:ReusableAggregateBusinessInformationEntity:100 order-x_ReusableAggregateBusinessInformationEntity_100pD10.xsd urn:un:unece:uncefact:data:standard:CrossIndustryInvoice:100 CrossIndustryInvoice_100pD10.xsd">

<head>
	<meta charset="utf-8">
	<link rel="icon" type="image/x-icon" href="./assets/images/favicon.ico">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Rechnung <?php echo $ord_id; ?> - Wacker 24</title>
	<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
	<!-- ZUGFeRD Metadata -->
	<meta name="author" content="Wacker Bürocenter GmbH">
	<meta name="description" content="Elektronische Rechnung im ZUGFeRD-Format">
	<meta name="keywords" content="Rechnung, ZUGFeRD, elektronische Rechnung, eRechnung">
	<meta name="robots" content="noindex, nofollow">

	<!-- ZUGFeRD specific meta tags -->
	<meta name="zugferd.version" content="1.0">
	<meta name="zugferd.conformanceLevel" content="BASIC">
	<meta name="zugferd.documentType" content="INVOICE">
	<meta name="zugferd.documentFileName" content="rechnung_<?php echo $ord_id; ?>.pdf">
	<meta name="zugferd.creationDate" content="<?php echo $invoice_date; ?>">
</head>

<body style="background-color: #e3e3e6;">
	<!-- ZUGFeRD XML Data (embedded as comment for PDF extraction) -->
	<!-- ZUGFeRD-XML-START -->
	<?php
	// Generate ZUGFeRD XML
	$zugferd_xml = '<?xml version="1.0" encoding="UTF-8"?>
<rsm:CrossIndustryInvoice xmlns:rsm="urn:ferd:CrossIndustryInvoice:invoice:1p0" 
                          xmlns:ram="urn:un:unece:uncefact:data:standard:ReusableAggregateBusinessInformationEntity:12" 
                          xmlns:udt="urn:un:unece:uncefact:data:standard:UnqualifiedDataType:15">
  <rsm:ExchangedDocumentContext>
    <ram:GuidelineSpecifiedDocumentContextParameter>
      <ram:ID>urn:ferd:CrossIndustryInvoice:invoice:1p0:basic</ram:ID>
    </ram:GuidelineSpecifiedDocumentContextParameter>
  </rsm:ExchangedDocumentContext>
  <rsm:ExchangedDocument>
    <ram:ID>' . htmlspecialchars($invoice_id) . '</ram:ID>
    <ram:TypeCode>380</ram:TypeCode>
    <ram:IssueDateTime>
      <udt:DateTimeString format="102">' . $invoice_date . '</udt:DateTimeString>
    </ram:IssueDateTime>
  </rsm:ExchangedDocument>
  <rsm:SupplyChainTradeTransaction>
    <ram:IncludedSupplyChainTradeLineItem>';

	foreach ($order_items as $item) {
		$item_total = $item->oi_gross_total;
		$item_net = $item_total / (1 + config_gst);
		$item_tax = $item_total - $item_net;

		$zugferd_xml .= '
      <ram:AssociatedDocumentLineDocument>
        <ram:LineID>' . htmlspecialchars($item->oi_id) . '</ram:LineID>
        <ram:IncludedNote>
          <ram:Content>' . htmlspecialchars($item->pro_description_short) . '</ram:Content>
        </ram:IncludedNote>
      </ram:AssociatedDocumentLineDocument>
      <ram:SpecifiedTradeProduct>
        <ram:SellerAssignedID>' . htmlspecialchars($item->supplier_id) . '</ram:SellerAssignedID>';

		if (!empty($item->pro_ean)) {
			$zugferd_xml .= '
        <ram:GlobalID schemeID="0160">' . htmlspecialchars($item->pro_ean) . '</ram:GlobalID>';
		}

		$zugferd_xml .= '
        <ram:Name>' . htmlspecialchars($item->pro_description_short) . '</ram:Name>
      </ram:SpecifiedTradeProduct>
      <ram:SpecifiedLineTradeAgreement>
        <ram:NetPriceProductTradePrice>
          <ram:ChargeAmount currencyID="' . $currency . '">' . number_format($item_net / $item->oi_qty, 2, '.', '') . '</ram:ChargeAmount>
        </ram:NetPriceProductTradePrice>
      </ram:SpecifiedLineTradeAgreement>
      <ram:SpecifiedLineTradeDelivery>
        <ram:BilledQuantity unitCode="C62">' . number_format($item->oi_qty, 2, '.', '') . '</ram:BilledQuantity>
      </ram:SpecifiedLineTradeDelivery>
      <ram:SpecifiedLineTradeSettlement>
        <ram:ApplicableTradeTax>
          <ram:TypeCode>VAT</ram:TypeCode>
          <ram:CategoryCode>S</ram:CategoryCode>
          <ram:RateApplicablePercent>' . number_format($tax_percentage, 2, '.', '') . '</ram:RateApplicablePercent>
        </ram:ApplicableTradeTax>
        <ram:SpecifiedTradeSettlementLineMonetarySummation>
          <ram:LineTotalAmount currencyID="' . $currency . '">' . number_format($item_total, 2, '.', '') . '</ram:LineTotalAmount>
        </ram:SpecifiedTradeSettlementLineMonetarySummation>
      </ram:SpecifiedLineTradeSettlement>';
	}

	$zugferd_xml .= '
    </ram:IncludedSupplyChainTradeLineItem>
    <ram:ApplicableHeaderTradeAgreement>
      <ram:SellerTradeParty>
        <ram:Name>Wacker Bürocenter GmbH</ram:Name>
        <ram:PostalTradeAddress>
          <ram:PostcodeCode>67433</ram:PostcodeCode>
          <ram:LineOne>Chemnitzer Straße 1</ram:LineOne>
          <ram:CityName>Neustadt</ram:CityName>
          <ram:CountryID>DE</ram:CountryID>
        </ram:PostalTradeAddress>
        <ram:SpecifiedTaxRegistration>
          <ram:ID schemeID="VA">DE 149 390 904</ram:ID>
        </ram:SpecifiedTaxRegistration>
      </ram:SellerTradeParty>
      <ram:BuyerTradeParty>
        <ram:Name>' . htmlspecialchars($user_company_name ?: $row->dinfo_fname . ' ' . $row->dinfo_lname) . '</ram:Name>
        <ram:PostalTradeAddress>
          <ram:PostcodeCode>' . htmlspecialchars($row->dinfo_usa_zipcode) . '</ram:PostcodeCode>
          <ram:LineOne>' . htmlspecialchars($row->dinfo_street . ' ' . $row->dinfo_house_no) . '</ram:LineOne>
          <ram:CityName>' . htmlspecialchars($row->dinfo_address) . '</ram:CityName>
          <ram:CountryID>' . htmlspecialchars($country_name) . '</ram:CountryID>
        </ram:PostalTradeAddress>
      </ram:BuyerTradeParty>
    </ram:ApplicableHeaderTradeAgreement>
    <ram:ApplicableHeaderTradeDelivery>
      <ram:ShipToTradeParty>
        <ram:Name>' . htmlspecialchars($user_company_name ?: $row->dinfo_fname . ' ' . $row->dinfo_lname) . '</ram:Name>
        <ram:PostalTradeAddress>
          <ram:PostcodeCode>' . htmlspecialchars($row->dinfo_usa_zipcode) . '</ram:PostcodeCode>
          <ram:LineOne>' . htmlspecialchars($row->dinfo_street . ' ' . $row->dinfo_house_no) . '</ram:LineOne>
          <ram:CityName>' . htmlspecialchars($row->dinfo_address) . '</ram:CityName>
          <ram:CountryID>' . htmlspecialchars($country_name) . '</ram:CountryID>
        </ram:PostalTradeAddress>
      </ram:ShipToTradeParty>
    </ram:ApplicableHeaderTradeDelivery>
    <ram:ApplicableHeaderTradeSettlement>
      <ram:InvoiceCurrencyCode>' . $currency . '</ram:InvoiceCurrencyCode>
      <ram:SpecifiedTradeSettlementPaymentMeans>
        <ram:TypeCode>31</ram:TypeCode>
        <ram:Information>' . htmlspecialchars($payment_terms) . '</ram:Information>
      </ram:SpecifiedTradeSettlementPaymentMeans>
      <ram:ApplicableTradeTax>
        <ram:CalculatedAmount currencyID="' . $currency . '">' . number_format($tax_total, 2, '.', '') . '</ram:CalculatedAmount>
        <ram:TypeCode>VAT</ram:TypeCode>
        <ram:BasisAmount currencyID="' . $currency . '">' . number_format($net_total, 2, '.', '') . '</ram:BasisAmount>
        <ram:CategoryCode>S</ram:CategoryCode>
        <ram:RateApplicablePercent>' . number_format($tax_percentage, 2, '.', '') . '</ram:RateApplicablePercent>
      </ram:ApplicableTradeTax>
      <ram:SpecifiedTradePaymentTerms>
        <ram:Description>' . htmlspecialchars($payment_terms) . '</ram:Description>
        <ram:DueDateDateTime>
          <udt:DateTimeString format="102">' . date('Y-m-d', strtotime($row->ord_datetime . ' +30 days')) . '</udt:DateTimeString>
        </ram:DueDateDateTime>
      </ram:SpecifiedTradePaymentTerms>
      <ram:SpecifiedTradeSettlementHeaderMonetarySummation>
        <ram:LineTotalAmount currencyID="' . $currency . '">' . number_format($net_total, 2, '.', '') . '</ram:LineTotalAmount>
        <ram:ChargeTotalAmount currencyID="' . $currency . '">' . number_format($row->ord_shipping_charges, 2, '.', '') . '</ram:ChargeTotalAmount>
        <ram:AllowanceTotalAmount currencyID="' . $currency . '">' . number_format($row->ord_discount, 2, '.', '') . '</ram:AllowanceTotalAmount>
        <ram:TaxBasisTotalAmount currencyID="' . $currency . '">' . number_format($net_total, 2, '.', '') . '</ram:TaxBasisTotalAmount>
        <ram:TaxTotalAmount currencyID="' . $currency . '">' . number_format($tax_total, 2, '.', '') . '</ram:TaxTotalAmount>
        <ram:GrandTotalAmount currencyID="' . $currency . '">' . number_format($grand_total, 2, '.', '') . '</ram:GrandTotalAmount>
        <ram:DuePayableAmount currencyID="' . $currency . '">' . number_format($grand_total, 2, '.', '') . '</ram:DuePayableAmount>
      </ram:SpecifiedTradeSettlementHeaderMonetarySummation>
    </ram:ApplicableHeaderTradeSettlement>
  </rsm:SupplyChainTradeTransaction>
</rsm:CrossIndustryInvoice>';

	// Output XML as comment
	echo '<!--' . htmlspecialchars($zugferd_xml) . '-->';
	?>
	<!-- ZUGFeRD-XML-END -->

	<!-- ZUGFeRD PDF/A-3 Conformance Level Indicator -->
	<div style="position: absolute; left: -9999px;">
		<p>Dieses Dokument entspricht dem ZUGFeRD-Standard Version 1.0, Conformance Level BASIC</p>
		<p>Document conforms to ZUGFeRD Standard Version 1.0, Conformance Level BASIC</p>
	</div>

	<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style=" font-family: 'Inter', sans-serif; max-width: 1140px;background-color: #fff;border: 1px solid #e3e3e6; padding: 15px;">
		<tr>
			<td style="background-color: #BF0417; padding: 12px 20px; font-size: 12px;color: #FFF; ">
				IHR BÜROVERSORGER VON A BIS Z

			</td>
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
			<td colspan="2" style="font-size: 20px; font-weight: bold;color: #000;">Rechnung </td>
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
					$Query = "SELECT oi.*, pro.pro_description_short, pro.pro_ean FROM order_items AS oi LEFT OUTER JOIN products AS pro ON pro.supplier_id = oi.supplier_id WHERE oi.ord_id =  '" . $_REQUEST['ord_id'] . "' ORDER BY oi.oi_id ASC";
					//print($Query);
					$rs = mysqli_query($GLOBALS['conn'], $Query);
					if (mysqli_num_rows($rs) > 0) {
						while ($row = mysqli_fetch_object($rs)) {
							$counter++;
							$supplier_id = $row->supplier_id;
							$pro_description_short = $row->pro_description_short;
							$ean_code = $row->pro_ean;
							if ($row->oi_type == 2) {
								$supplier_id = "GRATIS fur Sie!";
								$pro_description_short = returnName("fp_title_de AS fp_title", "free_product", "fp_id", $row->fp_id);
								$ean_code = '';
							}
					?>
							<tr>
								<td colspan="7" height="15"></td>
							</tr>
							<tr>
								<td style="width: 7%; font-size: 12px; font-weight: 400;color: #000;"> <?php print($counter); ?> </td>
								<td style="width: 15%; font-size: 12px; font-weight: 400;color: #000;"> <?php print($supplier_id); ?> </td>
								<td style="width: 35%; font-size: 12px; font-weight: 400;color: #000; padding-right: 5px"> <?php print($pro_description_short); ?></td>
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
					<tr>
						<td colspan="4" height="10"></td>
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