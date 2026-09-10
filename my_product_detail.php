<?php
include("includes/php_includes_top_user_dashboard.php");
include("includes/message.php");
$pro_id = $_REQUEST['pro_id'];

$Query = "SELECT pro.*, pg.pg_mime_source_url FROM products AS pro  LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pro.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_source_url ASC LIMIT 1) WHERE pro.pro_id = '" . $pro_id . "' ";
$rs = mysqli_query($GLOBALS['conn'], $Query);
if (mysqli_num_rows($rs) > 0) {
	$row = mysqli_fetch_object($rs);
	$product_link = product_detail_url($row->supplier_id);
	$get_image_link = get_image_link(160, $row->pg_mime_source_url);
	$pro_description_short = $row->pro_description_short;
	$pro_udx_seo_internetbezeichung = $row->pro_udx_seo_internetbezeichung;
}

if(isset($_REQUEST['btn_submit'])){
	$pis_id = getMaximum("products_info_submit", "pis_id");
	mysqli_query($GLOBALS['conn'], "INSERT INTO products_info_submit (pis_id, user_id, pro_id, pis_info_detail, pis_cdate) VALUES ('".$pis_id."', '".$_SESSION["UID"]."', '".$pro_id."', '".dbStr(trim($_REQUEST['pis_info_detail']))."', '".date_time."')") or die(mysqli_error($GLOBALS['conn']));
	header("Location: " . $GLOBALS['siteURL'] . "bestellungen/1");
}

?>
<!doctype html>
<html lang="de">

<head>
	<?php include("includes/html_header.php"); ?>
	<style>
		.my_order_page .my_order_box .my_order_box_inner .order_detail{width: calc(95% - 110px);}
	</style>
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
						<li><a href="javascript:void(0)">Eine Frage zum  Produkt stellen</a></li>
					</ul>
				</div>
			</div>
		</div>
		<!--BREADCRUMB_SECTION_END-->

		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="my_order_page gerenric_padding">
				<div class="page_width_1480">
					<h1>Eine Frage zum  Produkt stellen</h1>
							<div class="my_order_box">
								<div class="order_place_bar">
									
								</div>
								<form class="my_order_box_inner" name="frmReturn" id="frmReturn" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
									<div class="order_image"><img src="<?php print($get_image_link); ?>" alt=""></div>
									<div class="order_detail">
										<h2><?php print($pro_udx_seo_internetbezeichung); ?></h2>
										<h2 class="black_text"><?php print($pro_description_short); ?></h2>
										<div class="order_button" style="gap: 10px; flex-direction: column;">
											<textarea class="gerenric_input" style="width: 92%; height: 150px;" name="pis_info_detail" id="pis_info_detail" placeholder="Bitte geben Sie hier Ihre Fragen oder Informationen ein, die Sie mitteilen möchten." ></textarea>
											<button type="submit" class="gerenric_btn gray_btn" style="width: 10%;" name="btn_submit">Absenden</button>
										</div>
									</div>
								</form>
							</div>
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
</html>