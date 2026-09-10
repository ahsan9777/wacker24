<?php
include("includes/php_includes_top_user_dashboard.php");
include("includes/message.php");
$ord_id = $_REQUEST['ord_id'];
$oi_id = $_REQUEST['oi_id'];

$return_reasons = [
    1  => 'Irrtümlich bestellt',
    2  => 'Artikel wird nicht mehr benötigt',
    3  => 'Artikel ist für den vorgesehenen Einsatz ungeeignet',
    4  => 'Artikel entspricht nicht den Erwartungen',
    5  => 'Artikel ist fehlerhaft oder funktioniert nicht',
    6  => 'Artikel und Versandverpackung sind beschädigt',
    7  => 'Artikel ist beschädigt, die Versandverpackung ist jedoch unbeschädigt',
    8  => 'Falschen Artikel erhalten',
    9  => 'Teile oder Zubehör fehlen',
    10 => 'Gelieferte Menge weicht von der bestellten Menge ab',
    11 => 'Artikel entspricht nicht der Beschreibung auf der Website',
    12 => 'Artikel wurde zu spät geliefert',
    13 => 'Bestellung wurde nicht von mir oder uns aufgegeben'
];

if(isset($_REQUEST['btn_order_return'])){
	$ord_id = $_REQUEST['ord_id'];
    $oi_id = $_REQUEST['oi_id'];
	//print_r($_REQUEST);die();
	//print_r($_FILES);die();
	$or_id = 0;
	$or_id = getMaximum("order_return", "or_id");
	mysqli_query($GLOBALS['conn'], "INSERT INTO order_return (or_id, ord_id, or_createdby, or_cdate) VALUES ('".$or_id."', '".$ord_id."', '".$_SESSION['UID']."', '".date_time."')") or die(mysqli_error($GLOBALS['conn'])); 
	if($or_id > 0){
		$Query1 = "SELECT * FROM order_items WHERE oi_id = '".$oi_id."'";
		$rs1 = mysqli_query($GLOBALS['conn'], $Query1);
		if(mysqli_num_rows($rs1) > 0){
			$row1 = mysqli_fetch_object($rs1);
			$orid_files = "";
			if (isset($_FILES['orid_files']) && !empty($_FILES['orid_files']['name'][0])) {
				$dirName = "files/order_return/";
				$uploadedFiles = [];
				for ($i = 0; $i < count($_FILES['orid_files']['name']); $i++) {
					if ($_FILES['orid_files']['error'][$i] == 0) {
						$fileName = mt_rand(1000, 9999) . "_" . $_FILES['orid_files']['name'][$i];
						$fileName = str_replace(" ", "_", strtolower($fileName));
						move_uploaded_file($_FILES['orid_files']['tmp_name'][$i], $dirName . $fileName);
							$uploadedFiles[] = $fileName;
					}
				}
				// Store comma-separated filenames
				$orid_files = implode(",", $uploadedFiles);
			}
			mysqli_query($GLOBALS['conn'], "UPDATE order_return SET or_gross_total = ".$row1->oi_gross_total.", or_gst = ".$row1->oi_gst.", or_discount = ".$row1->oi_discount.", or_amount = ".$row1->oi_net_total." WHERE or_id = '".$or_id."'") or die(mysqli_error($GLOBALS['conn']));
			$orid_id = getMaximum("order_return_item_detail", "orid_id");
			mysqli_query($GLOBALS['conn'], "INSERT INTO order_return_item_detail (`orid_id`,`or_id`,`ord_id`,`oi_id`, supplier_id, pro_id, `orid_type`,orid_courier_type, `pbp_price_amount`,`orid_amount`,`orid_discounted_amount`,`orid_qty`,`orid_qty_type`,`orid_site_quantity_source`,`orid_gross_total`,`orid_gst_value`,`orid_gst`,`orid_discount_type`,`orid_discount_value`,`orid_discount`,`orid_net_total`, orid_files, orid_createdby, orid_cdate) VALUES ('".$orid_id."','".$or_id."','".$ord_id."','".$oi_id."', '".$row1->supplier_id."', '".$row1->pro_id."', '".$_REQUEST['orid_type']."', '".$_REQUEST['orid_courier_type']."','".$row1->pbp_price_amount."','".$row1->oi_amount."','".$row1->oi_discounted_amount."','".$row1->oi_qty."','".$row1->oi_qty_type."','".$row1->oi_site_quantity_source."','".$row1->oi_gross_total."','".$row1->oi_gst_value."','".$row1->oi_gst."','".$row1->oi_discount_type."','".$row1->oi_discount_value."','".$row1->oi_discount."','".$row1->oi_net_total."', '".mysqli_escape_string($GLOBALS['conn'], $orid_files)."',  '".$_SESSION['UID']."', '".date_time."')") or die(mysqli_error($GLOBALS['conn']));
			$return_method = "ecodirect – Lieferung mit eigenem Fuhrpark";
			if($_REQUEST['orid_courier_type'] == 2){
				$return_method = "DHL – versicherter Versand";
			}
			$mailer->order_item_return($oi_id, $return_reasons[$_REQUEST['orid_type']], $return_method);
		}
		header("Location: " . $GLOBALS['siteURL'] . "bestellungen/2");
	} else {
		header("Location: " . $_SERVER['PHP_SELF'] . "?ord_id=".$ord_id."&oi_id=".$oi_id."&op=10");
	}

}
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
						<li><a href="javascript:void(0)">Widerruf, Rückgabe oder Ersatz</a></li>
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
					<h1>Widerruf, Rückgabe oder Ersatz</h1>
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
								</style>
								<!-- action="<?php //print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>"-->
								<form class="my_order_box_inner" name="frmReturn" id="frmReturn" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
									<input type="hidden" name="ord_id" value="<?=  $ord_id ?>">
									<input type="hidden" name="oi_id" value="<?=  $oi_id ?>">
									<div class="order_image"><img src="<?php print($get_image_link); ?>" alt=""></div>
									<div class="order_detail">
										<h2><?php print($pro_udx_seo_internetbezeichung); ?></h2>
										<h2 class="black_text"><?php print($row->pro_description_short); ?></h2>
										<h4>Bitte wählen Sie den Grund der Rücksendung:</h4>
										<div class="order_button" style="gap: 10px;">
											<div class="generic_input">
												<label for="">Rücksendgrund</label>
												<select class="gerenric_input" name="orid_type" id="orid_type" required>
													<option value="">Wähle eine Anwort</option>
													<?php
													foreach ($return_reasons as $key => $reason) {
														echo '<option value="' . $key . '">' . htmlspecialchars($reason) . '</option>';
													}
													?>
												</select>
											</div>
											<div class="generic_input">
												<label for="">Foto / Nachweise (optional)</label>
												<input type="file" class="gerenric_input" name="orid_files[]" id="orid_files" multiple accept="image/*,video/*">
												<span>Hilfreich bei Schäde,Defekten order Falschlieferungen</span>
											</div>
											<div class="generic_input">
												<label for="">Rücksendweg</label>
												<select class="gerenric_input" name="orid_courier_type" id="orid_courier_type" required >
													<?php if($row->ord_shipping_type == 1){ ?>
													<option value="1" <?php print(($row->ord_shipping_type == 1) ? 'selected' : ''); ?> >ecodirect – Lieferung mit eigenem Fuhrpark</option>
													<?php } ?>
													<option value="2" <?php print(($row->ord_shipping_type == 2) ? 'selected' : ''); ?> >DHL – versicherter Versand</option>
												</select>
											</div>
											<div class="generic_input">
												<label for="">&nbsp;</label>
												<button type="submit" class="gerenric_btn gray_btn" name="btn_order_return">Rücksendung</button>
											</div>
										</div>
										<!--<div class="order_date">Order sent on Oct 21, 2024</div>-->
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
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.21.0/dist/jquery.validate.min.js"></script>
<script>
	$(document).ready(function () {

		$.validator.addMethod("fileType", function (value, element) {

			if (element.files.length === 0) {
				return true;
			}

			for (var i = 0; i < element.files.length; i++) {

				var type = element.files[i].type;

				if (!(type.startsWith("image/") || type.startsWith("video/"))) {
					return false;
				}
			}

			return true;

		}, "Es sind nur Bild- und Videodateien zulässig.");//Only image and video files are allowed.

		$.validator.addMethod("maxFiles", function (value, element, param) {

			return element.files.length <= param;

		}, "Sie können maximal {0}  Dateien hochladen.");

		$("#frmReturn").validate({
			ignore: [],

			rules: {
				"orid_files[]": {
					required: false,
					fileType: true,
					maxFiles: 5
				}
			},

			messages: {
				"orid_files[]": {
					required: "Bitte wählen Sie mindestens eine Datei aus",//Please select at least one file.
					maxFiles: "Sie können maximal 5 Dateien hochladen."//You can upload a maximum of 5 files.
				}
			},

			submitHandler: function (form) {
				form.submit();
			}
		});

	});
</script>
</html>