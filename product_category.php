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
							<div class="gerenric_product">
								<div class="gerenric_product_inner">
									<?php


									$Query = "SELECT * FROM vu_category_map AS cm WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(" . $level_one . ", cm.sub_group_ids) ";
									//print($Query);//die();
									$counter = 0;
									$limit = 24;
									$start = $p->findStart($limit);
									$count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], $Query));
									$pages = $p->findPages($count, $limit);
									$rs = mysqli_query($GLOBALS['conn'], $Query . " LIMIT " . $start . ", " . $limit);
									//$rs = mysqli_query($GLOBALS['conn'], $Query);
									if (mysqli_num_rows($rs) > 0) {
										while ($row = mysqli_fetch_object($rs)) {
											$counter++;
											$special_price = array();
											$sub_group_ids = explode(",", $row->sub_group_ids);
												$cat_id_one = $sub_group_ids[1];
												$cat_id_two = $sub_group_ids[0];
												//if (isset($_SESSION["UID"]) && $_SESSION["UID"] > 0) {
												$special_price = user_special_price("supplier_id", $row->supplier_id);

												if (!$special_price) {
													$special_price = user_special_price("level_two", $cat_id_two);
												}

												if (!$special_price) {
													$special_price = user_special_price("level_one", $cat_id_one);
												}
									?>
											<div class="pd_card">
												<div class="pd_image"><a href="product/<?php print($row->supplier_id); ?>/<?php print(url_clean($row->pro_description_short)); ?>"><img src="<?php print(get_image_link(160, $row->pg_mime_source_url)); ?>" alt=""></a></div>
												<div class="pd_detail">
													<h5><a href="product/<?php print($row->supplier_id); ?>/<?php print(url_clean($row->pro_description_short)); ?>"> <?php print($row->pro_description_short); ?> </a></h5>
													<div class="pd_rating">
														<ul>
															<li>
																<div class="fa fa-star"></div>
																<div class="fa fa-star"></div>
																<div class="fa fa-star"></div>
																<div class="fa fa-star"></div>
																<div class="fa fa-star"></div>
															</li>
														</ul>
													</div>
													<?php if (!empty($special_price)) { ?>
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format($row->pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_without_tax, $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format($row->pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_amount, $special_price['usp_discounted_value'], $row->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
													<?php } else { ?>
														<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format($row->pbp_price_without_tax)); ?>€</div>
														<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format($row->pbp_price_amount)); ?>€</div>
													<?php } ?>
												</div>
											</div>
									<?php
										}
									} else {
										print("Leerer Eintrag!");
									}
									?>
								</div>
								<?php if ($counter > 0) { ?>
									<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin: 30px 0px;">
										<tr>
											<td align="center">
												<ul class="pagination" style="margin: 0px;">
													<?php
													//$pageList = $p->pageList($_GET['page'], $pages, '&' . $qryStrURL);
													$pageList = $p->pageList($_GET['page'], "unterkategorien", $pages, '/'.$cat_params);
													print($pageList);
													?>
												</ul>
											</td>
										</tr>
									</table>
								<?php } ?>
								<style>

								</style>
								<!--<div class="need_help">
									<h2>Do you need help?</h2>
									<div class="need_help_ref">
										<a href="javascript:void(0);">Visit the help section</a>
										<p>Or</p>
										<a href="javascript:void(0);">contact us</a>
									</div>
								</div>-->
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