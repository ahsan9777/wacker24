<?php
include("includes/php_includes_top_user_dashboard.php");
if ($_SESSION["utype_id"] == 5) {
	header("Location: " . $GLOBALS['siteURL'] . "bestätigung-der-gastbestellung");
}
$order_net_amount = 0;
$ord_delivery_status = "";
$searchQuery = "";
if(isset($_REQUEST['ord_delivery_status'])){
	$ord_delivery_status = $_REQUEST['ord_delivery_status'];
	$searchQuery = " AND ord.ord_delivery_status = '".$ord_delivery_status."'";
}
if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) { //16.70
	//print_r($_REQUEST);die();
	$payment_status_request = check_payment_status($_REQUEST['id'], $_REQUEST['entityId']);
	$payment_status_responseData = json_decode($payment_status_request, true);
	/*$payment_status_request = capturePayment($_REQUEST['entityId'], $_REQUEST['id'], number_format(16.70, "2", ".", ""));
	$payment_status_responseData = json_decode($payment_status_request, true);*/

	/*print("<pre>");
	print_r($payment_status_responseData);
	print("</pre>");die();*/
	if ($payment_status_responseData['result']['code'] == '000.100.110' || $payment_status_responseData['result']['code'] == '000.000.000' || $payment_status_responseData['result']['description'] == 'Transaction succeeded') {
		cart_to_order($_SESSION['UID'], $_REQUEST['usa_id'], $_REQUEST['pm_id'], $_REQUEST['entityId'], $_REQUEST['id']);
		//mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_payment_status = '1' WHERE ord_payment_transaction_id = '" .$_REQUEST['id']. "' ") or die(mysqli_error($GLOBALS['conn']));
		//mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_payment_status = '0' WHERE ord_payment_transaction_id = '" .$_REQUEST['id']. "' ") or die(mysqli_error($GLOBALS['conn']));
		if(in_array($payment_status_responseData['paymentBrand'], array('PAYPAL', 'KLARNA_PAYMENTS_PAYLATER'))){
			mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_payment_short_id = '".dbStr(trim($payment_status_responseData['descriptor']))."', ord_payment_info_detail = '" . dbStr(trim($payment_status_request)) . "' WHERE ord_payment_transaction_id = '" .$_REQUEST['id']. "' ") or die(mysqli_error($GLOBALS['conn']));
		}
		header("Location: " . $GLOBALS['siteURL'] . "bestellungen/15");
	} else {
		header("Location: " . $GLOBALS['siteURL'] . "einkaufswagen");
	}
}
if(isset($_REQUEST['op']) && $_REQUEST['op'] == 26){
	$Query1 = "SELECT * FROM `orders` WHERE `ord_id` = '" . $_REQUEST['ord_id'] . "'";
	$rs1 = mysqli_query($GLOBALS['conn'], $Query1);
	if (mysqli_num_rows($rs1) > 0) {
		$row1 = mysqli_fetch_object($rs1);

		$order_net_amount = number_format(($row1->ord_amount + $row1->ord_shipping_charges), "2", ".", "");
	}
}


$currentPath = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

function isActiveOrderTab($path)
{

    global $currentPath;

    return $currentPath === rtrim($path, '/') ? 'active' : '';
}
include("includes/message.php");
?>
<!doctype html>
<html lang="de">

<head>
	<?php include("includes/html_header.php"); ?>
	<style>
		.tracking_title {
			display: flex;
			align-items: center;
			gap: 8px;
			margin-bottom: 20px;
			font-size: 16px;
		}

		.tracking_title i {
			font-size: 18px;
		}

		.tracking_details {
			width: 100%;
		}

		.tracking_item {
			display: flex;
			align-items: flex-start;
			gap: 12px;
			padding: 12px 0;
			border-bottom: 1px solid #eeeeee;
		}

		.tracking_item:last-child {
			border-bottom: 0;
		}

		.tracking_icon {
			width: 38px;
			height: 38px;
			min-width: 38px;
			border-radius: 50%;
			background: #f5f5f5;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.tracking_icon i {
			font-size: 16px;
		}

		.tracking_info {
			flex: 1;
			min-width: 0;
		}

		.tracking_label {
			font-size: 12px;
			color: #000;
			margin-bottom: 4px;
			text-align: left;
			font-weight: 600;
		}

		.tracking_value {
			font-size: 14px;
			line-height: 1.5;
			color: #333;
			word-break: break-word;
			text-align: left;
		}

		.tracking_number {
			font-weight: 600;
			letter-spacing: 0.5px;
		}
	</style>
</head>

<body>
	<div id="container" align="center">

		<!--LOCATION_POPUP_START-->
		<?php include("includes/popup.php"); ?>
		<!--LOCATION_POPUP_END-->
		<div class="popup qr_lable">
			<div class="popup_inner wd_30">
				<div class="popup_content">
					<div class="popup_heading">&nbsp;<div class="popup_close"><i class="fa fa-times"></i></div>
					</div>
					<div class="popup_content_inner">
						<div class="popup_inner_container">
							<p><strong>QR Code</strong></p>
							<div class="share_icon">
								<img id="qrImage" src="" alt="">
							</div>
							<div class="link_copy" style="justify-content:center">
								<div class="btn_link_copy" id="downloadQR">
									Download
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="popup delivery_tracking_history">
			<div class="popup_inner wd_30">
				<div class="popup_content">

					<div class="popup_heading">
						Lieferung verfolgen

						<div class="popup_close">
							<i class="fa fa-times"></i>
						</div>
					</div>

					<div class="popup_content_inner">

						<div class="popup_inner_container">

							<p class="tracking_title">
								<i class="fa fa-truck"></i>
								<strong>Lieferung verfolgen</strong>
							</p>

							<div class="tracking_details">

								<!-- Tracking Number -->
								<div class="tracking_item">
									<div class="tracking_icon">
										<i class="fa fa-barcode"></i>
									</div>

									<div class="tracking_info">
										<div class="tracking_label">
											Tracking number
										</div>

										<div class="tracking_value tracking_number" id="delivery_tracking_number">
											610377600213
										</div>
									</div>
								</div>


								<!-- Status -->
								<div class="tracking_item">
									<div class="tracking_icon">
										<i class="fa fa-info-circle"></i>
									</div>

									<div class="tracking_info">
										<div class="tracking_label">
											Status
										</div>

										<div class="tracking_value" id="delivery_status">
											The instruction data for this shipment have been provided by the sender to DHL electronically
										</div>
									</div>
								</div>


								<!-- Short Status -->
								<div class="tracking_item">
									<div class="tracking_icon">
										<i class="fa fa-check-circle"></i>
									</div>

									<div class="tracking_info">
										<div class="tracking_label">
											Short status
										</div>

										<div class="tracking_value" id="delivery_short_status">
											Order data provided electronically
										</div>
									</div>
								</div>


								<!-- Timestamp -->
								<div class="tracking_item">
									<div class="tracking_icon">
										<i class="fa fa-clock-o"></i>
									</div>

									<div class="tracking_info">
										<div class="tracking_label">
											Status timestamp
										</div>

										<div class="tracking_value" id="delivery_status_time">
											22.07.2026 13:35
										</div>
									</div>
								</div>

							</div>

						</div>

					</div>
				</div>
			</div>
		</div>

		<!--HEADER_SECTION_START-->
		<?php include("includes/navigation.php"); ?>
		<!--HEADER_SECTION_END-->

		<!--BREADCRUMB_SECTION_START-->
		<div class="gerenric_breadcrumb">
			<div class="page_width_1480">
				<div class="breadcrumb_inner">
					<ul>
						<li><a href="benutzerprofile">Meine Daten</a></li>
						<li><a href="javascript:void(0)">Meine Bestellungen</a></li>
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
					<div class="order-tabs">
						<a href="<?php print($GLOBALS['siteURL']."bestellungen"); ?>" class="order-tab <?php echo isActiveOrderTab('/deskup/bestellungen'); ?>">Bestellungen</a>
						<a href="<?php print($GLOBALS['siteURL']."bestellungen/0/0/0/0"); ?>" class="order-tab <?php echo isActiveOrderTab('/deskup/bestellungen/0/0/0/0'); ?>">Noch nicht versandt</a>
						<a href="<?php print($GLOBALS['siteURL']."bestellungen/0/0/0/2"); ?>" class="order-tab <?php echo isActiveOrderTab('/deskup/bestellungen/0/0/0/2'); ?>">Stornierte Bestellungen</a>
					</div>
					<h1>Meine Bestellungen</h1>
					<?php
					$Query = "SELECT oi.*, ord.user_id, ord.ord_datetime, ord.ord_udate, ord.ord_delivery_status, di.dinfo_countries_id, c.countries_name, di.dinfo_fname, di.dinfo_house_no, di.dinfo_street, di.dinfo_phone, di.dinfo_usa_zipcode, di.dinfo_additional_info, pro.pro_description_short, pro.pro_udx_seo_internetbezeichung, pg.pg_mime_source_url, orid.orid_id, orid.orid_status, orid.or_shipmentNo, orid.orid_lable, orid.orid_qr_lable, orid.orid_remarks, orid.orid_courier_type, orid.orid_return_item_received_status FROM order_items AS oi LEFT OUTER JOIN orders AS ord ON ord.ord_id = oi.ord_id LEFT OUTER JOIN order_return_item_detail AS orid  ON orid.oi_id = oi.oi_id LEFT OUTER JOIN delivery_info AS di ON di.ord_id = oi.ord_id LEFT OUTER JOIN countries AS c ON c.countries_id = di.dinfo_countries_id LEFT OUTER JOIN products AS pro ON pro.supplier_id = oi.supplier_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pro.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_source_url ASC LIMIT 1) WHERE ord.user_id = '" . $_SESSION['UID'] . "' ".$searchQuery." ORDER BY ord.ord_datetime DESC, oi.oi_type ASC";
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

							$orid_cdate = returnName("orid_cdate", "order_return_item_detail", "oi_id", $row->oi_id);
							$ord_datetime = $row->ord_datetime;
							$returnorderDate = date("Y-m-d", strtotime($row->ord_datetime . " +15 days"));
							$orderEstimationDate = date("Y-m-d", strtotime($row->ord_datetime . " +7 days"));
							
							$currentDate = date('Y-m-d');

							$diff = (new DateTime($currentDate))->diff(new DateTime($returnorderDate));

							if ($currentDate <= $returnorderDate && empty($orid_cdate) && ($row->ord_delivery_status == 1 && $row->oi_status == 0)) {
								//echo $diff->days . " days remaining";
								$product_return_link = $GLOBALS['siteURL'].'rueckgabe-oder-ersatz/'.$row->ord_id.'/'.$row->oi_id;
							} else {
								//echo "Deadline expired " . $diff->days . " days ago";
								$product_return_link = "javascript: void(0);";
							}
							
					?>
							<div class="my_order_box">
								<div class="order_place_bar">
									<div class="place_col">
										<div class="place_div">Bestellung aufgegeben</div>
										<div class="place_div"><b><?php print(formatDateGerman($ord_datetime)); ?></b></div>
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
									<?php if($row->orid_courier_type == 1 && $row->orid_status == 1) { ?>
									<div class="place_col">
										<div class="place_div"> <a class="gerenric_btn" style="color: #fff;"  href="<?php print($GLOBALS['siteURL']."own_delivery_lable.php?oi_id=".$row->oi_id."&orid_id=".$row->orid_id); ?>" target="_blank" rel="noopener noreferrer"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> &nbsp;Return Lable File</a>  </div>
									</div>
									<?php } elseif(!empty($row->orid_lable) && $row->orid_status == 1) { ?>
									<div class="place_col">
										<div class="place_div"> <a class="gerenric_btn" style="color: #fff;"  href="<?php print($GLOBALS['siteURL']."files/return_labels/".$row->orid_lable); ?>" target="_blank" rel="noopener noreferrer"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> &nbsp;Return Lable File</a>  </div>
										<div class="place_div"> <a class = "gerenric_btn qr_lable_trigger" style="color: #fff;" href="javascript:void(0);" data-id = "<?php print($row->orid_id); ?>" rel="noopener noreferrer"><i class="fa fa-qrcode" aria-hidden="true"></i> &nbsp;Return QR Code</a>  </div>
									</div>
									<?php }
									if(!empty($row->orid_remarks) && $row->orid_status == 2) { ?>
									<div class="place_col">
										<div class="place_div">
											<div class="placeser_name"> Hinweise zur Stornierung von Bestellungenrückgabe <i class="fa fa-caret-down"></i>
												<div class="placeser_info">
													<ul>
														<li> <?php print($row->orid_remarks); ?> </li>
													</ul>
												</div>
											</div>
										</div>
									</div>
									<?php } ?>
									<div class="place_col" style="text-align: start;">
										<div class="place_div">Bestellnummer - <?php print($row->ord_id); ?></div>
									</div>
								</div>
								<div class="order_status">
									<?php if($row->oi_status == 1 || $row->ord_delivery_status == 2){ ?>
									<h3 >Storniert</h3>
									<p>Deine Bestellung wurde storniert.</p>
									<?php } elseif($row->orid_id > 0 && $row->orid_status == 1 && $row->orid_return_item_received_status){ ?>
									<h3 >Rücksendeanfrage genehmigt</h3>
									<p>Der Verkaufer hat deine Rückgabe genehmigt.</p>
									<?php } elseif($row->orid_id > 0 && $row->orid_status == 1){ ?>
									<h3 >Rücksendeanfrage eingeleitet</h3>
									<p>Der Verkaufer hat deine Rückgabe eingeleitet.</p>
									<?php } elseif($row->orid_id > 0 && $row->orid_status == 2){ ?>
									<h3 >Rücksendung nicht möglich</h3>
									<?php } elseif(!empty($orid_cdate)){ ?>
									<h3>Rücksendung begonnen: <?php print(formatDateGerman($orid_cdate)); ?></h3>
									<?php } elseif($row->ord_delivery_status == 1){ ?>
									<h3 > <?php print( (($currentDate <= $orderEstimationDate) ? "Zustellung:" : "Zugestellt:")." ".formatDateGerman(date("Y-m-d", strtotime($row->ord_datetime . " +7 days")))) ?></h3>
									<?php } else { ?>
									<h3>Alle Artikel erhalten <?php print(formatDateGerman(date("Y-m-d", strtotime($row->ord_datetime . " +7 days")))) ?> </h3>
									<?php } ?>
								</div>
								<div class="my_order_box_inner">
									<div class="order_image"><img src="<?php print($get_image_link); ?>" alt=""></div>
									<div class="order_detail">
										<a class="order_detail_heading" href="<?php print($product_link); ?>"><h2><?php print($row->pro_description_short); ?></h2></a>

										<?php if($currentDate <= $returnorderDate && $row->ord_delivery_status < 2 && $row->oi_status == 0 && $row->orid_status == 0){ ?>
										<div class="return_date">Rückgabe, Ersatz oder Widerruf: Berechtigt bis zum <?php print(formatDateGerman($returnorderDate)); ?></div>
										<?php } ?>
										<div class="order_button">
											<a href="<?php print($product_link); ?>">
												<div class="gerenric_btn gray_btn">Nochmals kaufen</div>
											</a>
										</div>
										<!--<div class="order_date">Order sent on Oct 21, 2024</div>-->
									</div>
									<?php if($row->oi_status == 0 && $row->ord_delivery_status < 2){ ?>
									<div class="order_btn_container">
										<a target="_blank" href="lieferschein-drucken/<?php print($row->ord_id); ?>">
											<div class="gerenric_btn" style="background-image: none; width: 100%; color: #fff;">Lieferschein drucken</div>
										</a>
										<?php //if(!empty($row->or_shipmentNo)){ ?>
										<!--<a href="javascript:void(0);">
											<div class="gerenric_btn delivery_tracking_trigger" style="background-image: none; width: 100%; color: #fff;" data-id="<?php //print($row->or_shipmentNo); ?>">Lieferung verfolgen</div>
										</a>-->
										<?php /*}*/ if($row->ord_delivery_status == 0 && $row->oi_status == 0){ ?>
										<a href="<?php print($GLOBALS['siteURL'].'bestellung-stornieren/'.$row->ord_id.'/'.$row->oi_id); ?>">
											<div class="gerenric_btn" style="background-image: none; width: 100%; color: #fff;">Bestellung stornieren</div>
										</a>
										<?php } ?>
										<a href="<?php print($product_return_link); ?>">
											<div class="gerenric_btn" style="background-image: none; width: 100%; color: #fff;">Rückgabe oder Reklamation</div>
										</a>
										<a href="kundenservice-kontaktieren/<?php print($row->pro_id); ?>">
											<div class="gerenric_btn" style="background-image: none; width: 100%; color: #fff;">Kundenservice kontaktieren</div>
										</a>
									</div>
									<?php } ?>
								</div>
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
	$(".qr_lable_trigger").click(function() {
		let orid_id = $(this).attr("data-id");
		//console.log("orid_id", orid_id);
		$.ajax({
			url: "ajax_calls.php?action=qr_lable",
			type: "POST",
			data: {
				orid_id: orid_id
			},
			success: function(response) {
				//console.log("response = "+response);
				const obj = JSON.parse(response);
				//console.log(obj.delivery_charges.total);
				if (obj.status == 1) {
					$("#qrImage").attr("src", "data:image/png;base64," + obj.orid_qr_lable);
					$('.qr_lable').show();
					$('.qr_lable').resize();
					$('body').css({
						'overflow': 'hidden'
					});
				}
			}
		});
	});

	$(".delivery_tracking_trigger").click(function() {
		let tracking_id = $(this).attr("data-id");
		console.log("tracking_id", tracking_id);
		$.ajax({
			url: "ajax_calls.php?action=delivery_status",
			type: "POST",
			data: {
				tracking_id: tracking_id
			},
			success: function(response) {
				//console.log("response = "+response);
				const obj = JSON.parse(response);
				console.log(obj);
				if (obj.status == 1) {
					$('#delivery_tracking_number').text(obj.delivery_tracking_number);
					$('#delivery_status').text(obj.delivery_status);
					$('#delivery_short_status').text(obj.delivery_short_status);
					$('#delivery_status_time').text(obj.delivery_status_time);
					$('.delivery_tracking_history').show();
					$('.delivery_tracking_history').resize();
					$('body').css({
						'overflow': 'hidden'
					});
				}
			}
		});
	});

	$(document).on("click", "#downloadQR", function () {

		var img = $("#qrImage").attr("src");

		var a = document.createElement("a");
		a.href = img;
		a.download = "DHL_Return_QR.png";

		document.body.appendChild(a);
		a.click();
		document.body.removeChild(a);

	});
</script>
</html>