<?php
include("includes/php_includes_top.php");
$page = 1;
$whereclause_page = "";
$pageHeading = "Verkäufe & Angebote";
if (isset($_REQUEST['cat_params']) && !empty($_REQUEST['cat_params'])) {
	$category_data = returnMultiName("cat_title_de AS cat_title, group_id", "category", "cat_params_de", $_REQUEST['cat_params'], 2);
	//print_r($category_data);die();
	$pageHeading = $category_data['data_1'];
	$whereclause_page = " AND level_one_id = '" . $category_data['data_2'] . "'";
}
?>
<!doctype html>
<html>

<head>
	<link rel="canonical" href="<?php print($GLOBALS['siteURL_main'] . "verkäufe-angebote"); ?>">
	<?php include("includes/html_header.php"); ?>
	<style>
		.pd_card{
			overflow: hidden;
		}
		.pd_card::before {
			content: attr(data-label);
			position: absolute;
			top: 20px;
			left: -55px;
			width: 180px;
			background: #ff1c0a;
			color: #fff;
			padding: 6px 0;
			text-align: center;
			font-size: 18px;
			font-weight: 700;
			text-transform: uppercase;
			transform: rotate(-45deg);
			box-shadow: 0 0 10px rgba(255, 28, 10, 0.6);
			z-index: 9;
		}

		.pd_card:hover {
			transform: translateY(-6px);
			box-shadow: 0 0 18px rgba(144, 255, 33, 0.4);
			border-color: #20ff20d1;
		}
	</style>
</head>

<body>
	<div id="container" align="center">

		<!--LOCATION_POPUP_START-->
		<?php include("includes/popup.php"); ?>

		<!--HEADER_SECTION_START-->
		<?php include("includes/navigation.php"); ?>
		<!--HEADER_SECTION_END-->

		<!--BREADCRUMB_SECTION_START-->
		<div class="gerenric_breadcrumb">
			<div class="page_width">
				<div class="breadcrumb_inner">
					<ul>
						<li><a href="<?php print($GLOBALS['siteURL']); ?>">Wacker24</a></li>
						<li><a href="javascript:void(0)">Verkäufe & Angebote</a></li>
					</ul>
				</div>
			</div>
		</div>
		<!--BREADCRUMB_SECTION_END-->

		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="special_price_page gerenric_padding">
				<div class="page_width">
					<div class="gerenric_white_box">
						<div class="category_type_product">
							<div class="category_type_inner full_column">
								<div class="category-slider">
									<?php
									//$Query = "SELECT sub_cat.cat_id, sub_cat.group_id, sub_cat.parent_id, cat.cat_title_de AS cat_title, sub_cat.cat_title_de AS sub_cat_title, cat.cat_params_de AS cat_params, sub_cat.cat_params_de AS sub_cat_params, sub_cat.cat_orderby, sub_cat.cat_status FROM category AS sub_cat LEFT OUTER JOIN category AS cat ON cat.group_id = sub_cat.parent_id  WHERE sub_cat.parent_id IN ( SELECT main_cat.group_id FROM category AS main_cat WHERE main_cat.parent_id = '" . $lf_parent_id . "' ORDER BY main_cat.group_id ASC) AND sub_cat.cat_status = '1' AND EXISTS (SELECT 1 FROM category_map AS cm WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(sub_cat.group_id, cm.cat_id) ) ORDER BY sub_cat.cat_orderby ASC, sub_cat.group_id ASC";
									$Query = "SELECT usp.*, c.cat_title_de AS cat_title, c.cat_params_de AS cat_params FROM user_special_price AS usp LEFT OUTER JOIN category AS c ON c.group_id = usp.level_one_id AND c.parent_id = '0' WHERE usp.user_id = '0' AND usp.level_two_id > 0 AND usp.supplier_id > 0 GROUP BY usp.level_one_id ORDER BY usp.level_one_id ASC";
									//print($Query);die();
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										while ($row = mysqli_fetch_object($rs)) {
											$pg_mime_source_url_href = "files/no_img_1.jpg";
											$pg_mime_source_url_href = returnName("pg_mime_source_url", "vu_category_map", "supplier_id",  $row->supplier_id, "AND cm_type = '0' GROUP BY cat_id_level_two");


											print('<div>
													<div class="ctg_type_col">
													<a href="' . $GLOBALS['siteURL'] . 'verkaeufe-angebote/' . $row->cat_params . '" title = "' . $row->cat_title . '">
														<div class="ctg_type_card">
															<div class="ctg_type_image"><img loading="lazy" src="' . get_image_link(427, $pg_mime_source_url_href) . '" alt="' . $row->cat_title . '"></div>
															<div class="ctg_type_detail">
																<div class="ctg_type_title">' . $row->cat_title . '</div>
															</div>
														</div>
													</a>
												</div>
											</div>');
										}
									}
									?>
								</div>
							</div>
						</div>
						<div class="gerenric_product">
							<h2><?php print($pageHeading); ?></h2>
							<div class="gerenric_product_inner" id="site_special_price_product_inner">
								<div class="txt_align_center spinner" id="site_special_price_product_inner_spinner">
									<!--<input type="hidden" name="site_special_price_product_inner_page" id="site_special_price_product_inner_page" value="0">-->
									<div></div>
									<div></div>
									<div></div>
									<div></div>
									<div></div>
									<div></div>
									<div></div>
									<div></div>
									<div></div>
									<div></div>
									<div></div>
									<div></div>
								</div>
							</div>
							<div class="txt_align_center" id="btn_load" style="display: none;">
								<input type="hidden" name="site_special_price_product_inner_page" id="site_special_price_product_inner_page" value="0">
								<div class="load-more-button">Weitere anzeigen &nbsp;<i class="fa fa-angle-down"></i></div>
								<div class="load-less-button" style="display:none">Ansicht schließen &nbsp;<i class="fa fa-angle-up"></i></div>
							</div>
							<div class="txt_align_center spinner" id="btn_load_spinner" style="display: none;">
								<div></div>
								<div></div>
								<div></div>
								<div></div>
								<div></div>
								<div></div>
								<div></div>
								<div></div>
								<div></div>
								<div></div>
								<div></div>
								<div></div>
							</div>
						</div>

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
<script src="js/slick.js"></script>
<script>
	$(".category-slider").slick({
		slidesToShow: 5,
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

	$(window).on('load', function() {
		$("#site_special_price_product_inner").trigger("click");
	});
	$("#site_special_price_product_inner").on("click", function() {
		$("#btn_load").hide();
		$("#btn_load_spinner").show();
		let start = $("#site_special_price_product_inner_page").val();
		let sortby = $("#sort_by_selected").val();
		let whereclause_page = "<?php print($whereclause_page); ?>";
		let price_without_tex_display = '<?php print($price_without_tex_display); ?>';
		let pbp_price_with_tex_display = '<?php print($pbp_price_with_tex_display); ?>';

		$.ajax({
			url: 'ajax_calls.php?action=site_special_price_product_inner',
			method: 'POST',
			data: {
				start: start,
				sortby: sortby,
				whereclause_page: whereclause_page,
				price_without_tex_display: price_without_tex_display,
				pbp_price_with_tex_display: pbp_price_with_tex_display
			},
			success: function(response) {
				//console.log("raw response = "+response);
				const obj = JSON.parse(response);
				console.log(obj);
				$("#site_special_price_product_inner_spinner").hide();
				$("#btn_load_spinner").hide();
				if (obj.counter == obj.last_record) {
					$(".load-more-button").hide();
					$(".load-less-button").show();
				} else {
					$(".load-more-button").show();
					$(".load-less-button").hide();
				}
				if (obj.status == 1) {
					$("#site_special_price_product_inner_spinner").hide();
					if (obj.counter > 30) {
						$("#btn_load").show();
					}
					$("#site_special_price_product_inner_page").val(obj.site_special_price_product_inner_page);
					$("#site_special_price_product_inner").append(obj.site_special_price_product_inner);
				}

			}
			//}, 5000);
		});
	});

	$(".load-more-button").on("click", function() {

		$("#site_special_price_product_inner").trigger("click");
	});
	$(".load-less-button").on("click", function() {
		$("#site_special_price_product_inner").html("");
		$("#site_special_price_product_inner_page").val(0);

		$("#site_special_price_product_inner").trigger("click");
	});
</script>

</html>