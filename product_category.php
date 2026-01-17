<?php
include("includes/php_includes_top.php");
$pro_type = 0;
//$qryStrURL = "";
$pro_typeURL = "";
$cat_params = "";
//print_r($_REQUEST);
//$level_one = $_REQUEST['level_one'];
$lf_action_type = 0;
$Query = "SELECT group_id, cat_keyword, cat_description FROM category WHERE cat_params_de = '".$_REQUEST['cat_params_one']."'";
$rs = mysqli_query($GLOBALS['conn'], $Query);
if(mysqli_num_rows($rs) > 0){
	$row = mysqli_fetch_object($rs);
	$level_one = $row->group_id;
	$level_one_request = $row->group_id;
	$meta_keywords = $row->cat_keyword;
	$meta_description = $row->cat_description;
}
/*$level_one =  returnName("group_id", "category", "cat_params_de", $_REQUEST['cat_params_one']);
$level_one_request = returnName("group_id", "category", "cat_params_de", $_REQUEST['cat_params_one']);*/
$cat_params = $_REQUEST['cat_params_one'];

//print("level_one: ".$level_one." level_one_request: ".$level_one_request." cat_params: ".$cat_params);
if ($level_one_request == 20) {
	$pro_type = $level_one_request;
	$pro_typeURL = "/" . $level_one_request;
	$qryStrURL = "pro_type=" . $level_one_request . "&";
}
$special_price = user_special_price("level_one", $level_one);

/*$meta_keywords = returnName("cat_keyword", "category", "cat_params_de", $_REQUEST['cat_params_one']);
$meta_description = returnName("cat_description", "category", "cat_params_de", $_REQUEST['cat_params_one']);*/
?>
<!doctype html>
<html lang="de">

<head>
	<link rel="canonical" href="<?php print($GLOBALS['siteURL'] ."unterkategorien/".$_REQUEST['cat_params_one']); ?>">
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
								<div class="category_type_inner" id="category_type_inner">
									<div class="spinner" id="spinner_category_type_inner">
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
									<input type="hidden" name="category_type_inner_page" id="category_type_inner_page" value="0">
									<div class="load-more-button">Weitere anzeigen &nbsp;<i class="fa fa-angle-down" ></i></div>
									<div class="load-less-button" style="display:none">Ansicht schließen &nbsp;<i class="fa fa-angle-up" ></i></div>
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
							<!--<div class="gerenric_white_box">
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
							</div>-->
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
	/*$(".show-more").click(function() {
		if ($(".category_show, .list_checkbox_hide").hasClass("category_show_height")) {
			$(this).text("(Weniger anzeigen)");
		} else {
			$(this).text("(Mehr anzeigen)");
		}

		$(".category_show, .list_checkbox_hide").toggleClass("category_show_height");
	});*/
</script>
<script>
	$(".show-more").click(function() {
		if ($("#category_show_" + $(this).attr("data-id") + ", #list_checkbox_hide_" + $(this).attr("data-id") + " ").hasClass("category_show_height")) {
			$(this).text("(Weniger anzeigen)");
		} else {
			$(this).text("(Mehr anzeigen)");
		}

		$("#category_show_" + $(this).attr("data-id") + ", #list_checkbox_hide_" + $(this).attr("data-id") + "").toggleClass("category_show_height");
	});
</script>
<?php include("includes/bottom_js.php"); ?>
<script>
	let ajaxRequests = [];
	$(window).on('load', function() {
		$("#category_type_inner").trigger("click");
	});
	$(document).on('click', 'a', function(e) {
		cancelAllAjaxCalls();
		// Optionally let the navigation happen, or preventDefault() if needed
	});
	$(window).on('beforeunload', function() {
		//cancelAllAjaxCalls();
		ajaxRequests.forEach(req => req.abort());
	});

	function cancelAllAjaxCalls() {
		ajaxRequests.forEach(req => req.abort());
		ajaxRequests = [];
	}
	$("#category_type_inner").on("click", function() {
		$("#btn_load").hide();
		$("#btn_load_spinner").show();
		let start = $("#category_type_inner_page").val();
		let pro_type = <?php print($pro_type) ?>;
		let level_one = <?php print($level_one) ?>;
		let cat_params_one = '<?php print($_REQUEST['cat_params_one']) ?>';
		let price_without_tex_display = '<?php print($price_without_tex_display) ?>';
		let pbp_price_with_tex_display = '<?php print($pbp_price_with_tex_display) ?>';
		//console.log("pro_type: "+pro_type+" level_one: "+level_one+" price_without_tex_display: "+price_without_tex_display+" pbp_price_with_tex_display: "+pbp_price_with_tex_display);
		const ajaxCall = $.ajax({
			url: 'ajax_calls.php?action=category_type_inner',
			method: 'POST',
			data: {
				start: start,
				pro_type: pro_type,
				level_one: level_one,
				cat_params_one: cat_params_one,
				price_without_tex_display: price_without_tex_display,
				pbp_price_with_tex_display: pbp_price_with_tex_display
			},
			beforeSend: function(jqXHR) {
				ajaxRequests.push(jqXHR);
			},
			success: function(response) {
				//console.log("response = "+response);
				const obj = JSON.parse(response);
				//console.log(obj);
				if (obj.status == 1) {
					$("#spinner_category_type_inner").hide();
					$("#btn_load_spinner").hide();
					if (obj.counter > 30) {
						$("#btn_load").show();
					}
					if (obj.counter == obj.last_record) {
						$(".load-more-button").hide();
						$(".load-less-button").show();
					} else {
						$(".load-more-button").show();
						$(".load-less-button").hide();
					}
					$("#category_type_inner_page").val(obj.category_type_inner_page);
					$("#category_type_inner").append(obj.category_type_inner);
				}
			},
			error: function(xhr, status, error) {
				if (status !== 'abort') {
					console.error("AJAX error:", error);
				}
			},
			complete: function(jqXHR) {
				ajaxRequests = ajaxRequests.filter(req => req !== jqXHR);
			}
		});
	});

	$(".load-more-button").on("click", function() {
		$("#category_type_inner").trigger("click");
	});
	$(".load-less-button").on("click", function() {
		$("#category_type_inner").html("");
		$("#category_type_inner_page").val(0);
		$("#category_type_inner").trigger("click");
	});
</script>
<script>
	$(window).load(function() {
		lf_group_id_inner();
		lf_manf_id_inner();
		lf_pf_fvalue_inner();
	});
	let hasTriggeredClick = false;

	function lf_group_id_inner() {
		//setTimeout(function() {
		let lf_action_type = "<?php print($lf_action_type); ?>";
		let leve_id = "<?php print($leve_id); ?>";
		let left_filter_cat_WhereQuery = "<?php print($left_filter_cat_WhereQuery); ?>";
		let level_check = "<?php print($level_three); ?>";

		const ajaxCall = $.ajax({
			url: 'ajax_calls.php?action=lf_group_id_inner',
			method: 'POST',
			data: {
				lf_action_type: lf_action_type,
				leve_id: leve_id,
				left_filter_cat_WhereQuery: left_filter_cat_WhereQuery,
				level_check: level_check
			},
			beforeSend: function(jqXHR) {
				ajaxRequests.push(jqXHR);
			},
			success: function(response) {
				//console.log("response = "+response);
				const obj = JSON.parse(response);
				//console.log(obj);
				if (obj.status == 1) {
					$("#lf_group_id_loading").hide();
					$("#lf_group_id_inner").html(obj.lf_group_id_inner);
				}
			},
			error: function(xhr, status, error) {
				if (status !== 'abort') {
					console.error("AJAX error:", error);
				}
			},
			complete: function(jqXHR) {
				ajaxRequests = ajaxRequests.filter(req => req !== jqXHR);
			}
			//}, 5000);
		});
	}

	function lf_manf_id_inner(lf_group_id_data) {
		//setTimeout(function() {
		let lf_action_type = "<?php print($lf_action_type); ?>";
		let leve_id = "<?php print($leve_id); ?>";
		let Sidefilter_brandwith = "<?php print($Sidefilter_brandwith); ?>";
		let manf_check = <?php echo json_encode($manf_check); ?>;
		let lf_group_id = "";
		if (typeof lf_group_id_data !== 'undefined' && lf_group_id_data !== null && lf_group_id_data != "") {
			lf_group_id = lf_group_id_data;
		}
		const ajaxCall = $.ajax({
			url: 'ajax_calls.php?action=lf_manf_id_inner',
			method: 'POST',
			data: {
				lf_action_type: lf_action_type,
				lf_group_id: lf_group_id,
				leve_id: leve_id,
				Sidefilter_brandwith: Sidefilter_brandwith,
				manf_check: manf_check
			},
			beforeSend: function(jqXHR) {
				ajaxRequests.push(jqXHR);
			},
			success: function(response) {
				//console.log("response = "+response);
				const obj = JSON.parse(response);
				//console.log(obj);
				if (obj.status == 1) {
					$("#lf_manf_id_inner_loading").hide();
					$("#lf_manf_id_inner").html(obj.lf_manf_id_inner);
				}
				lf_manf_id_inner_script();
			},
			error: function(xhr, status, error) {
				if (status !== 'abort') {
					console.error("AJAX error:", error);
				}
			},
			complete: function(jqXHR) {
				ajaxRequests = ajaxRequests.filter(req => req !== jqXHR);
			}
			// }, 5000);
		});
	}

	function lf_pf_fvalue_inner(lf_group_id_data = "", lf_manf_id_data = "") {
		//setTimeout(function() {
		let lf_action_type = "<?php print($lf_action_type); ?>";
		let leve_id = "<?php print($leve_id); ?>";
		let pf_fvalue_check = <?php echo json_encode($pf_fvalue_check); ?>;
		let lf_group_id = "";
		if (typeof lf_group_id_data !== 'undefined' && lf_group_id_data !== null && lf_group_id_data != "") {
			$("#lf_pf_fvalue_inner_loading").show();
			lf_group_id = lf_group_id_data;
		}
		let lf_manf_id = "";
		if (typeof lf_manf_id_data !== 'undefined' && lf_manf_id_data !== null && lf_manf_id_data != "") {
			$("#lf_pf_fvalue_inner_loading").show();
			lf_manf_id = lf_manf_id_data;
		}
		const ajaxCall = $.ajax({
			url: 'ajax_calls.php?action=lf_pf_fvalue_inner',
			method: 'POST',
			data: {
				lf_group_id: lf_group_id,
				lf_manf_id: lf_manf_id,
				lf_action_type: lf_action_type,
				leve_id: leve_id,
				pf_fvalue_check: pf_fvalue_check
			},
			beforeSend: function(jqXHR) {
				ajaxRequests.push(jqXHR);
			},
			success: function(response) {
				//console.log("response = "+response);
				const obj = JSON.parse(response);
				//console.log(obj);
				if (obj.status == 1) {
					$("#lf_pf_fvalue_inner_loading").hide();
					$("#lf_pf_fvalue_inner").html(obj.lf_pf_fvalue_inner);
				}
				genaric_javascript_file();

			},
			error: function(xhr, status, error) {
				if (status !== 'abort') {
					console.error("AJAX error:", error);
				}
			},
			complete: function(jqXHR) {
				ajaxRequests = ajaxRequests.filter(req => req !== jqXHR);
			}
			//}, 5000);
		});
	}

	 function lf_manf_id_inner_script(){
		 $(".show-more").click(function() {
            if ($("#category_show_0, #list_checkbox_hide_0").hasClass("category_show_height")) {
                $(this).text("(Weniger anzeigen)");
            } else {
                $(this).text("(Mehr anzeigen)");
            }

            $("#category_show_0, #list_checkbox_hide_0").toggleClass("category_show_height");
        });
	}
    function genaric_javascript_file() {
       
        $(".show-more").click(function() {
			if($(this).attr("data-id") > 0){
				if ($("#category_show_" + $(this).attr("data-id") + ", #list_checkbox_hide_" + $(this).attr("data-id") + " ").hasClass("category_show_height")) {
                $(this).text("(Weniger anzeigen)");
            } else {
                $(this).text("(Mehr anzeigen)");
            }

            $("#category_show_" + $(this).attr("data-id") + ", #list_checkbox_hide_" + $(this).attr("data-id") + "").toggleClass("category_show_height");
			}
        });
    }
</script>
<script defer>
	/*$(window).on('load', function() {
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
	});*/
</script>

</html>