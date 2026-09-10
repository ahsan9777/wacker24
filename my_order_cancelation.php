<?php
include("includes/php_includes_top_user_dashboard.php");
include("includes/message.php");
$ord_id = $_REQUEST['ord_id'];
$oi_id = $_REQUEST['oi_id'];

if(isset($_REQUEST['btn_order_cancelation'])){
	//print_r($_REQUEST);die();
	mysqli_query($GLOBALS['conn'], "UPDATE order_items SET oi_status = '1', oi_cancelation_reason = '".$_REQUEST['oi_cancelation_reason']."' WHERE ord_id = '".$_REQUEST['ord_id']."' AND oi_id = '".$_REQUEST['oi_id']."'") or die(mysqli_error($GLOBALS['conn']));
	$mailer->order_item_cancelation($_REQUEST['oi_id']);
	$TotalRecords = TotalRecords("oi_id", "order_items", " WHERE ord_id = '".$_REQUEST['ord_id']."' AND oi_status = '0'");
	$return_total = 0;
	$Query = "SELECT ord.ord_amount, SUM(oi.oi_net_total) AS return_total FROM order_items AS oi LEFT OUTER JOIN orders AS ord ON ord.ord_id = oi.ord_id WHERE oi.oi_status = '1' AND oi.ord_id = '".$_REQUEST['ord_id']."'";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if(mysqli_num_rows($rs) > 0){
		$row = mysqli_fetch_object($rs);
		$ord_amount = $row->ord_amount;
		$return_total = $row->return_total;
		if($ord_amount == $return_total){
			$return_total = $return_total;
		} else {
			$return_total = $ord_amount - $return_total;
		}

		$ord_shipping_charges = 0;
		if ($return_total <= config_condition_courier_amount) {
			$ord_shipping_charges = config_courier_fix_charges;
			mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_shipping_charges = '".$ord_shipping_charges."' WHERE ord_id = '".$_REQUEST['ord_id']."'") or die(mysqli_error($GLOBALS['conn']));
		} else {
			mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_shipping_charges = '".$ord_shipping_charges."' WHERE ord_id = '".$_REQUEST['ord_id']."'") or die(mysqli_error($GLOBALS['conn']));
		}
	}
	//echo $TotalRecords; die();
	if($TotalRecords == 0){
		mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_delivery_status = '2' WHERE ord_id = '".$_REQUEST['ord_id']."'") or die(mysqli_error($GLOBALS['conn']));
		orderquantityUpdate($_REQUEST['ord_id']);
        $mailer->order_cancelation($_REQUEST['ord_id']);
	}
	header("Location: " . $GLOBALS['siteURL'] . "bestellungen/27");

}

$oi_cancelation_reason_reasons = [
    1 => 'Irrtümlich bestellt',
    2 => 'Falschen Artikel oder falsche Menge bestellt',
    3 => 'Artikel ist für den vorgesehenen Einsatz ungeeignet',
    4 => 'Bestell-, Liefer- oder Zahlungsdaten sollen geändert werden',
    5 => 'Voraussichtliche Lieferzeit ist zu lang',
    6 => 'Bestellung wird nicht mehr benötigt',
    7 => 'Bestellung wurde nicht von mir oder uns aufgegeben',
    8 => 'Sonstiger Grund',
    9 => 'Keine Angabe'
];
?>
<!doctype html>
<html lang="de">

<head>
	<?php include("includes/html_header.php"); ?>
</head>

<body>
	<div id="container" align="center">

		<!--LOCATION_POPUP_START-->
		<?php include("includes/popup.php"); ?>
		<div class="popup payment_method_popup">
			<div class="popup_inner wd_30">
				<div class="popup_content">
					<div class="popup_heading">Stornierung<div class="popup_close"><i class="fa fa-times"></i></div>
					</div>
					<div class="popup_content_inner">
						<div class="popup_inner_container">
							<p><strong>Möchten Sie diese Bestellposition wirklich stornieren?</strong></p>
							<div class="create_button">
								<button class="gerenric_btn btn_cancelation_confirm" type="button" name="btn_cancelation_confirm">Ja</button>
								<div class="gerenric_btn gray_btn popup_close">Nein</div>
							</div>
						</div>
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
						<li><a href="javascript:void(0)">Bestellung stornieren</a></li>
					</ul>
				</div>
			</div>
		</div>
		<!--BREADCRUMB_SECTION_END-->

		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="my_order_page gerenric_padding">
				<div class="page_width_1480">
					<?php if ($class != "") { ?>
						<div class="<?php print($class); ?>"><a href="javascript:void(0);" class="close" data-dismiss="alert">×</a> <?php print($strMSG); ?></div>
					<?php } ?>
					<h1>Bestellung stornieren</h1>
					<?php
					$Query = "SELECT oi.*, ord.user_id, ord.ord_shipping_type, ord.ord_datetime, ord.ord_udate, di.dinfo_countries_id, c.countries_name, di.dinfo_fname, di.dinfo_house_no, di.dinfo_street, di.dinfo_phone, di.dinfo_usa_zipcode, di.dinfo_additional_info, pro.pro_description_short, pro.pro_udx_seo_internetbezeichung, pg.pg_mime_source_url FROM order_items AS oi LEFT OUTER JOIN orders AS ord ON ord.ord_id = oi.ord_id LEFT OUTER JOIN delivery_info AS di ON di.ord_id = oi.ord_id LEFT OUTER JOIN countries AS c ON c.countries_id = di.dinfo_countries_id LEFT OUTER JOIN products AS pro ON pro.supplier_id = oi.supplier_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pro.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_source_url ASC LIMIT 1) WHERE oi.oi_id = '" . $oi_id . "' ORDER BY ord.ord_datetime DESC, oi.oi_type ASC";
					$rs = mysqli_query($GLOBALS['conn'], $Query);
					if (mysqli_num_rows($rs) > 0) {
						while ($row = mysqli_fetch_object($rs)) {
							$oi_gst_value = 1;
							$gst = 0;
							if ($_SESSION["Utype"] == 3) {
								$oi_gst_value = 1 + $row->oi_gst_value;
								$gst = $row->oi_amount * $row->oi_gst_value;
							}
							$product_link = product_detail_url($row->supplier_id);
							$get_image_link = get_image_link(160, $row->pg_mime_source_url);
							$pro_udx_seo_internetbezeichung = $row->pro_udx_seo_internetbezeichung;
							if( $row->oi_type == 1){
								$product_link = product_detail_url($row->supplier_id, 1);
							} elseif($row->oi_type == 2) {
								$get_image_link = $GLOBALS['siteURL'] . "files/free_product/" .returnName("fp_file", "free_product", "fp_id", $row->fp_id);
								$pro_udx_seo_internetbezeichung = returnName("fp_title_de AS fp_title", "free_product", "fp_id", $row->fp_id);
							}
							
					?>
							<div class="my_order_box">
								<div class="order_place_bar">
									<div class="place_col">
										<div class="place_div">Bestellung aufgegeben</div>
										<div class="place_div"><?php print(formatDateGerman($row->ord_datetime)); ?></div>
									</div>
									<div class="place_col">
										<div class="place_div">Artikel Preis</div>
										<div class="place_div">
											<?php
											if ($row->oi_discount_value > 0) {
												print("<del class = 'orignal_price'>" . price_format($row->pbp_price_amount * ($oi_gst_value)) . "€</del><br> <span class = 'pd_prise_discount'>" . price_format($row->oi_amount + ($gst)) . "€ " . $row->oi_discount_value . (($row->oi_discount_type > 0) ? '€' : '%') . "</span>");
											} else {
												print(price_format($row->oi_amount * ($oi_gst_value)) . "€");
											}
											?>
										</div>
									</div>
									<div class="place_col">
										<div class="place_div">Versenden an</div>
										<div class="place_div">
											<div class="placeser_name"> <?php print($row->dinfo_fname); ?> <i class="fa fa-caret-down"></i>
												<div class="placeser_info">
													<ul>
														<?php if (!empty($row->dinfo_additional_info)) { ?>
															<li><span> <?php print($row->dinfo_additional_info); ?> </span></li>
														<?php } ?>
														<li> <?php print($row->dinfo_house_no); ?> </li>
														<li> <?php print($row->dinfo_street); ?> </li>
														<li> <?php print($row->dinfo_phone); ?> </li>
														<li> <?php print($row->dinfo_usa_zipcode); ?> </li>
														<li> <?php print($row->countries_name); ?> </li>
													</ul>
												</div>
											</div>
										</div>
									</div>
									<div class="place_col">
										<div class="place_div">Menge</div>
										<div class="place_div"> <?php print($row->oi_qty); ?> </div>
									</div>
									<div class="place_col">
										<div class="place_div">Bestellnummer - <?php print($row->ord_id); ?></div>
									</div>
								</div>
								<style>
									.generic_input{display: flex; flex-direction: column; gap: 10px;}
									.generic_input label{color: #000; font-weight: 600;}
									.my_order_page .my_order_box .my_order_box_inner .order_detail{width: calc(97% - 110px);}
								</style>
								<!-- action="<?php //print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>"-->
								<form class="my_order_box_inner" name="frmCancelation" id="frmCancelation" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
									<input type="hidden" name="ord_id" value="<?=  $ord_id ?>">
									<input type="hidden" name="oi_id" value="<?=  $oi_id ?>">
									<input type="hidden" name="btn_order_cancelation" value="1">
									<div class="order_image"><img src="<?php print($get_image_link); ?>" alt=""></div>
									<div class="order_detail">
										<h2><?php print($pro_udx_seo_internetbezeichung); ?></h2>
										<h2 class="black_text"><?php print($row->pro_description_short); ?></h2>
										<h4>Gesamte Artikel wirklich stornieren?</h4>
										<p>Sie stornieren sämtliche Artikel der Bestellung Nr. <?php print($row->ord_id); ?> vom <?php print(formatDateGerman($row->ord_datetime)); ?>. Die Bestellung wird anschließend nicht mehr geprüft, bearbeitet oder versendet.</p>
										<p>Für diese Bestellung wird kein Betrag eingezogen. Eine eventuell vorgemerkte oder autorisierte Zahlung wird aufgehoben. Falls Sie Vorkasse gewählt haben, überweisen Sie den Betrag bitte nicht.</p>
										<div class="order_button" style="gap: 10px;">
											<div class="generic_input">
												<label for="">Warum möchten Sie die Bestellung stornieren? (optional)</label>
												<select class="gerenric_input" name="oi_cancelation_reason" id="oi_cancelation_reason">
													<option value="0">Bitte auswählen</option>
													<?php
													foreach ($oi_cancelation_reason_reasons as $key => $reason) {
														echo '<option value="' . $key . '">' . htmlspecialchars($reason) . '</option>';
													}
													?>
												</select>
											</div>
											<div class="generic_input">
												<label for="">&nbsp;</label>
												<button type="button" class="gerenric_btn gray_btn btn_order_cancelation" name="btn_order_cancelation">Stornieren</button>
											</div>
										</div>
									</div>
								</form>
							</div>
					<?php
						}
					}
					?>
				</div>
			</div>
		</section>
		<!--CONTENT_SECTION_END-->

		<!--FOOTER_SECTION_START-->
		
		<?php include("includes/footer.php"); ?>
		<!--FOOTER_SECTION_END-->

	</div>

</body>
<?php include("includes/bottom_js.php"); ?>
<script>
	$(".btn_order_cancelation").on("click", function(e) {
		//console.log("btn_checkout");
		var reason = $("#oi_cancelation_reason").val();

		if (reason == "0") {
			e.preventDefault();

			$("#oi_cancelation_reason").after(
				'<label class="error">Bitte wählen Sie einen Stornierungsgrund aus.</label>'
			);

			return false;
		}
		
			$(".payment_method_alert").show();
			$('.payment_method_popup').show();
			$('.payment_method_popup').resize();
			$('body').css({
				'overflow': 'hidden'
			});
	});

	$(".btn_cancelation_confirm").on("click", function() {
		//console.log("btn_cancelation_confirm");
		$('#frmCancelation')[0].submit();
	});
</script>
</html>