<?php
include("includes/php_includes_top.php");
$pro_type = 0;
//$qryStrURL = "";
$pro_typeURL = "";
$cat_params = "";
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
							<div class="product_category">
								<h2>Ausgewählte Kategorien</h2>
								<div class="product_category_inner" id="feature_category">
									<div class="spinner" id="spinner_feature_category">
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
									<?php //include("includes/product_category/feature_category.php"); 
									?>
								</div>
							</div>
							<div class="gerenric_white_box">
								<div class="gerenric_product full_column" id="new_product">
									<div class="spinner" id="spinner_new_product">
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
									<?php //include("includes/product_category/new_product.php"); 
									?>
								</div>
							</div>
							<span id="most_sale_product">
								<div class="gerenric_white_box">
									<div class="gerenric_product full_column mostviewed">
										<div class="spinner" id="spinner_most_sale_product">
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
								<?php //include("includes/product_category/most_sale_product.php"); 
								?>
							</span>
							<div class="gerenric_white_box">
								<div class="gerenric_product full_column" id="related_product">
									<div class="spinner" id="spinner_related_product">
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
									<?php //include("includes/product_category/related_product.php"); 
									?>
								</div>
							</div>
							<div class="gerenric_white_box">
								<div class="gerenric_product full_column" id="reference_product">
									<div class="spinner" id="spinner_reference_product">
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
									<?php //include("includes/product_category/reference_product.php"); 
									?>
								</div>
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
		
		<?php include("includes/footer.php"); ?>
		<!--FOOTER_SECTION_END-->

	</div>

</body>
<script src="js/slick.js"></script>
<script>
	function new_product() {

		$(".gerenric_slider_new_product").slick({
			slidesToShow: 6,
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
	function most_sale_product() {

		$(".gerenric_slider_most_sale_product").slick({
			slidesToShow: 6,
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
	function related_product() {

		$(".gerenric_slider_related_product").slick({
			slidesToShow: 6,
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
	function reference_product() {

		$(".gerenric_slider_reference_product").slick({
			slidesToShow: 6,
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
		let level_one_request = <?php print($level_one_request) ?>;
		let pro_typeURL = '<?php print($pro_typeURL) ?>';
		//console.log("pro_type: "+pro_type+" level_one: "+level_one+" level_one_request: "+level_one_request+" pro_typeURL: "+pro_typeURL);
		$.ajax({
			url: 'ajax_calls_product_category.php?action=feature_category',
			method: 'POST',
			data: {
				pro_type: pro_type,
				level_one: level_one,
				level_one_request: level_one_request,
				pro_typeURL: pro_typeURL
			},
			success: function(response) {
				//console.log("response = "+response);
				const obj = JSON.parse(response);
				//console.log(obj);
				if (obj.status == 1) {
					$("#spinner_feature_category").hide();
					$("#feature_category").html(obj.feature_category_data);
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
			url: 'ajax_calls_product_category.php?action=new_product',
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
					$("#spinner_new_product").hide();
					$("#new_product").html(obj.new_product_data);
					new_product();
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
			url: 'ajax_calls_product_category.php?action=most_sale_product',
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
					$("#spinner_most_sale_product").hide();
					$("#most_sale_product").html(obj.most_sale_product);
					most_sale_product();
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
			url: 'ajax_calls_product_category.php?action=related_product',
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
					$("#spinner_related_product").hide();
					$("#related_product").html(obj.related_product_data);
					related_product();
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
			url: 'ajax_calls_product_category.php?action=reference_product',
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
					$("#spinner_reference_product").hide();
					$("#reference_product").html(obj.reference_product_data);
					reference_product();
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