<?php
include("includes/php_includes_top.php");
$pro_type = 0;
//$qryStrURL = "";
$pro_typeURL = "";
$cat_params = "";
//print_r($_REQUEST);
//$level_one = $_REQUEST['level_one'];
$level_one =  ($_REQUEST['cat_params_one'] != 'schulranzen') ? returnName("group_id", "category", "cat_params_de", $_REQUEST['cat_params_one']) : 19;
$level_one_request = ($_REQUEST['cat_params_one'] != 'schulranzen') ? returnName("group_id", "category", "cat_params_de", $_REQUEST['cat_params_one']) : 20;
$cat_params = $_REQUEST['cat_params_one'];
if ($level_one_request == 20) {
	$pro_type = $level_one_request;
	$level_one = 19;
	//$pro_typeURL = "pro_type=" . $level_one_request . "&";
	$pro_typeURL = "/" . $level_one_request;
	$qryStrURL = "pro_type=" . $level_one_request . "&";
}
//if (isset($_SESSION["UID"]) && $_SESSION["UID"] > 0) {
$special_price = user_special_price("level_one", $level_one);
//print_r($special_price);die();
//}
$meta_keywords = returnName("cat_keyword", "category", "cat_params_de", $_REQUEST['cat_params_one']);
$meta_description = returnName("cat_description", "category", "cat_params_de", $_REQUEST['cat_params_one']);
?>
<!doctype html>
<html lang="de">

<head>
	<?php include("includes/html_header.php"); ?>
</head>

<body style="background-color: #fff;">
	<div id="container" align="center">

		<!--LOCATION_POPUP_START-->
		<?php include("includes/popup.php"); ?>
		<!--LOCATION_POPUP_END-->

		<!--HEADER_SECTION_START-->
		<?php include("includes/navigation.php"); ?>
		<!--HEADER_SECTION_END-->

		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="product_page gerenric_padding">
				<div class="page_width">
					<div class="product_inner position_relative">
						<div class="filter_mobile">Filter <i class="fa fa-angle-down"></i></div>
						<?php include("includes/left_filter.php"); ?>
						<div class="pd_right">
							<div class="product_category_heading">
								<h1> <?php print(returnName("cat_title_de AS cat_title", "category", "group_id", $level_one)) ?> </h1>
							</div>
							<div class="category_type_product">
								<div class="category_type_inner">
									<?php


									//$Query = "SELECT * FROM vu_category_map AS cm WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(" . $level_one . ", cm.sub_group_ids) ";
									//$Query = "SELECT three_cat.*, (SELECT GROUP_CONCAT(pg.pg_mime_source_url) FROM products_gallery AS pg WHERE pg.pg_mime_purpose = 'normal' AND pg.supplier_id = (SELECT cm.supplier_id FROM category_map AS cm WHERE cm.cm_type = '".$pro_type."' AND FIND_IN_SET(three_cat.group_id, cm.cat_id) LIMIT 0,1)) AS pg_mime_source_url FROM category AS three_cat WHERE three_cat.parent_id IN (SELECT two_cat.group_id FROM category AS two_cat WHERE two_cat.parent_id = '".$level_one."') AND EXISTS (SELECT 1 FROM category_map AS cm WHERE cm.cm_type = '".$pro_type."' AND FIND_IN_SET(three_cat.group_id, cm.cat_id) )";
									$Query = "SELECT c.*, GROUP_CONCAT(pg.pg_mime_source_url) AS pg_mime_source_url,  MIN(CASE WHEN pbp.pbp_price_amount > 0 THEN pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)ELSE NULL END) AS pbp_price_amount, MIN(CASE WHEN pbp.pbp_price_amount > 0 THEN pbp.pbp_price_amount ELSE NULL END) AS pbp_price_without_tax  FROM category c JOIN category second_level ON c.parent_id = second_level.group_id AND second_level.parent_id = '" . $level_one . "' JOIN category_map cm ON cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(c.group_id, cm.cat_id) LEFT JOIN products_gallery pg ON pg.pg_mime_purpose = 'normal' AND pg.supplier_id = cm.supplier_id LEFT JOIN products_bundle_price AS pbp ON pbp.supplier_id = cm.supplier_id AND pbp.pbp_lower_bound = '1' GROUP BY c.group_id ORDER BY c.cat_orderby DESC";
									//print($Query);//die();
									$counter = 0;
									$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										while ($row = mysqli_fetch_object($rs)) {
											$counter++;
											$pg_mime_source_url_href = "files/no_img_1.jpg";
											if (!empty($row->pg_mime_source_url)) {
												$pg_mime_source_url = explode(',', $row->pg_mime_source_url);
												//$pg_mime_source_href = "getftpimage.php?img=" . $pg_mime_source[0];
												$pg_mime_source_url_href = $pg_mime_source_url[0];
											}
											if($_REQUEST['cat_params_one'] == 'schulranzen'){
												$cat_two_params_de = returnName("cat_params_de", "category", "group_id", $row->parent_id);
												$cat_link = "artikelarten/".$cat_two_params_de."/".$row->cat_params_de."/20";
											} else {
												$cat_two_params_de = returnName("cat_params_de", "category", "group_id", $row->parent_id);
												$cat_link = "artikelarten/".$cat_two_params_de."/".$row->cat_params_de;
											}
									?>
											<div class="ctg_type_col">
												<a href="<?php print($cat_link); ?>">
													<div class="ctg_type_card">
														<div class="ctg_type_image"><img src="<?php print(get_image_link(160, $pg_mime_source_url_href)); ?>" alt=""></div>
														<div class="ctg_type_detail">
															<div class="ctg_type_title"><?php print($row->cat_title_de); ?></div>
															<div class="ctg_type_price price_without_tex" <?php print($price_without_tex_display); ?> > ab <?php print(price_format( ($row->pbp_price_without_tax > 0) ? $row->pbp_price_without_tax : 0.00 )); ?> €</div>
															<div class="ctg_type_price pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?> >ab <?php print(price_format( ($row->pbp_price_amount) ? $row->pbp_price_amount : 0.00 )); ?> €</div>
														</div>
													</div>
												</a>
											</div>
									<?php
										}
									} else {
										print("Leerer Eintrag!");
									}
									?>
								</div>
								<?php if($counter > 10) { ?>
								<div class="txt_align_center">
									<div class="load-more-button">Weitere anzeigen &nbsp;<i class="fa fa-angle-down" aria-hidden="true"></i></div>
									<div class="load-less-button" style="display:none">Ansicht schließen &nbsp;<i class="fa fa-angle-up" aria-hidden="true"></i></div>
								</div>
								<?php } ?>
							</div>
							<div class="gerenric_white_box">
								<div class="gerenric_product full_column mostviewed" id="related_category_one">
									<div class="spinner" id="spinner_related_category_one">
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
									<?php //include("includes/product_category/related_category_one.php"); 
									?>
								</div>
							</div>
							<div class="gerenric_white_box">
								<div class="gerenric_product full_column mostviewed" id="related_category_two">
									<div class="spinner" id="spinner_related_category_two">
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
									<?php //include("includes/product_category/related_category_two.php"); 
									?>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</section>
		<!--CONTENT_SECTION_END-->

		<!--FOOTER_SECTION_START-->
		<div id="scroll_top">Zurück zum Seitenanfang</div>
		<?php include("includes/footer.php"); ?>
		<!--FOOTER_SECTION_END-->

	</div>

</body>
<script src="js/slick.js"></script>
<script>
	function related_category_one() {
		$(".gerenric_slider_mostviewed_related_category_one").slick({
			slidesToShow: 8,
			slidesToScroll: 1,
			autoplay: true,
			dots: false,
			autoplaySpeed: 2000,
			infinite: true,
			responsive: [{
					breakpoint: 1024,
					settings: {
						slidesToShow: 4,
						slidesToScroll: 1
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
	}

	function related_category_two() {
		$(".gerenric_slider_mostviewed_related_category_two").slick({
			slidesToShow: 8,
			slidesToScroll: 1,
			autoplay: true,
			dots: false,
			autoplaySpeed: 2000,
			infinite: true,
			responsive: [{
					breakpoint: 1024,
					settings: {
						slidesToShow: 4,
						slidesToScroll: 1
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
	}
</script>
<script>
	$(".show-more").click(function() {
		if ($(".category_show, .list_checkbox_hide").hasClass("category_show_height")) {
			$(this).text("(Weniger anzeigen)");
		} else {
			$(this).text("(Mehr anzeigen)");
		}

		$(".category_show, .list_checkbox_hide").toggleClass("category_show_height");
	});
</script>
<?php include("includes/bottom_js.php"); ?>
<script>
	$(function() {
		$(".category_type_product .ctg_type_col").slice(0, 10).show();
		$("body").on('click touchstart', '.load-more-button', function(e) {
			e.preventDefault();
			$(".category_type_product .ctg_type_col:hidden").slice(0, 10).slideDown();
			if ($(".category_type_product .ctg_type_col:hidden").length == 0) {
				$(".load-more-button").hide();
				$(".load-less-button").show();
			}
		});

		// Load less button
		$("body").on('click touchstart', '.load-less-button', function(e) {
        e.preventDefault();

        // Hide all and show only the first 10
        $(".category_type_product .ctg_type_col").hide().slice(0, 10).show();

        // Scroll to the container
        $('html, body').animate({
            scrollTop: $("#container").offset().top
        }, 500); // 500ms for smooth scroll

        // Toggle button visibility
        $(".load-more-button").show();
        $(".load-less-button").hide();
    });
	});
</script>
<script defer>
	$(window).on('load', function() {
		let pro_type = <?php print($pro_type) ?>;
		let level_one = <?php print($level_one) ?>;
		let special_price = <?php echo json_encode($special_price); ?>;
		let price_without_tex_display = '<?php print($price_without_tex_display) ?>';
		let pbp_price_with_tex_display = '<?php print($pbp_price_with_tex_display) ?>';
		//console.log("pro_type: "+pro_type+" level_one: "+level_one+" price_without_tex_display: "+price_without_tex_display+" pbp_price_with_tex_display: "+pbp_price_with_tex_display);
		$.ajax({
			url: 'ajax_calls_product_category.php?action=related_category_one',
			method: 'POST',
			data: {
				pro_type: pro_type,
				level_one: level_one,
				special_price: special_price,
				price_without_tex_display: price_without_tex_display,
				pbp_price_with_tex_display: pbp_price_with_tex_display
			},
			success: function(response) {
				//console.log("response = "+response);
				const obj = JSON.parse(response);
				//console.log(obj);
				if (obj.status == 1) {
					$("#spinner_related_category_one").hide();
					$("#related_category_one").html(obj.related_category_one_data);
					related_category_one();
				}
			}
		});
	});

	$(window).on('load', function() {
		let pro_type = <?php print($pro_type) ?>;
		let level_one = <?php print($level_one) ?>;
		let special_price = <?php echo json_encode($special_price); ?>;
		let price_without_tex_display = '<?php print($price_without_tex_display) ?>';
		let pbp_price_with_tex_display = '<?php print($pbp_price_with_tex_display) ?>';
		//console.log("pro_type: "+pro_type+" level_one: "+level_one+" price_without_tex_display: "+price_without_tex_display+" pbp_price_with_tex_display: "+pbp_price_with_tex_display);
		$.ajax({
			url: 'ajax_calls_product_category.php?action=related_category_two',
			method: 'POST',
			data: {
				pro_type: pro_type,
				level_one: level_one,
				special_price: special_price,
				price_without_tex_display: price_without_tex_display,
				pbp_price_with_tex_display: pbp_price_with_tex_display
			},
			success: function(response) {
				//console.log("response = "+response);
				const obj = JSON.parse(response);
				//console.log(obj);
				if (obj.status == 1) {
					$("#spinner_related_category_two").hide();
					$("#related_category_two").html(obj.related_category_two_data);
					related_category_two();
				}
			}
		});
	});
</script>

</html>