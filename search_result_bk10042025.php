<?php
include("includes/php_includes_top.php");
require_once("lib/class.pager1.php");
$p = new Pager1;
//$requestUri = rtrim($GLOBALS['siteURL'], "/") . $_SERVER['REQUEST_URI'];
$requestUri = $GLOBALS['siteURL'] . ltrim($_SERVER['REQUEST_URI'], "/demo");
//print(ltrim($_SERVER['REQUEST_URI'], "/wacker24")."<br>".$requestUri);
//print($requestUri);
//print_r($_SERVER['PHP_SELF']);die();
//print_r($_SERVER['REQUEST_URI']);die();
$user_ip = $_SERVER['REMOTE_ADDR'];
$user_id = 0;
if (isset($_SESSION["UID"])) {
	$user_id = $_SESSION["UID"];
}
mysqli_query($GLOBALS['conn'], "INSERT INTO search_keyword (user_id, sk_user_ip, sk_data, sk_cdate) VALUES ('" . $user_id . "', '" . $user_ip . "', '" . dbStr(trim($_REQUEST['search_keyword'])) . "', '" . date_time . "')") or die(mysqli_error($GLOBALS['conn']));
$heading_title = "";
$search_whereclause = "";
$Sidefilter_where = "";

if (isset($_REQUEST['level_one']) && $_REQUEST['level_one'] > 0) {
	$search_whereclause = "pro.supplier_id IN (SELECT cm.supplier_id FROM category_map AS cm WHERE FIND_IN_SET(" . dbStr(trim($_REQUEST['level_one'])) . ", cm.sub_group_ids)) ";
	$heading_title .= "Category : " . returnName("cat_title_de AS cat_title", "category", "group_id", $_REQUEST['level_one']);
	$qryStrURL .= "level_one=" . $_REQUEST['level_one'] . "&";
	$cat_id = $_REQUEST['level_one'];
}
$search_keyword_where = "";
if ((isset($_REQUEST['search_keyword']) && !empty($_REQUEST['search_keyword'])) && (isset($_REQUEST['supplier_id'])) && $_REQUEST['supplier_id'] > 0) {
	/*$pro_udx_seo_epag_title_params_de = returnName("pro_udx_seo_epag_title_params_de", "vu_products", "supplier_id", $_REQUEST['supplier_id']);
	$pro_ean = returnName("pro_ean", "vu_products", "supplier_id", $_REQUEST['supplier_id']);
	header("Location: " . $GLOBALS['siteURL'].$pro_udx_seo_epag_title_params_de."-".$pro_ean);*/
	$pro_url = returnName("pro_url", "products", "supplier_id", $_REQUEST['supplier_id']);
	header("Location: " . $GLOBALS['siteURL'] . $pro_url);
	$search_keyword = $_REQUEST['search_keyword'];
} elseif ((isset($_REQUEST['search_keyword']) && !empty($_REQUEST['search_keyword']))) {

	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$result = autocorrectQueryUsingProductTerms($_REQUEST['search_keyword'], $pdo);
	$search_keyword_array = explode(" ", trim(str_replace("-", " ", $result['corrected'])));
	//$search_keyword_array = explode(" ", trim(str_replace("-", " ", "Papier 80g weiß a4 kopierpapier")));
	//print_r($pro_description_short_keyword);
	$search_keyword_where = "";
	$search_keyword_pk_title_where = "";
	if (count($search_keyword_array) > 1) {
		$search_keyword_case = "(CASE WHEN  pro.pro_udx_seo_epag_title LIKE '" . dbStr(trim($result['corrected'])) . "%' OR EXISTS ( SELECT 1 FROM products_feature AS pf WHERE pf.supplier_id = pro.supplier_id AND pf.pf_fname = 'Verwendung für Druckertyp' AND pf.pf_fvalue LIKE '" . dbStr(trim($result['corrected'])) . "%') THEN 1 ELSE 0 END) + ";
		for ($i = 0; $i < count($search_keyword_array); $i++) {
			$search_keyword_array_data = "";
			if (!empty($search_keyword_array[$i])) {
				$search_keyword_case .= "(CASE WHEN pro.supplier_id = '" . dbStr(trim($search_keyword_array[$i])) . "' OR pro.pro_manufacture_aid LIKE '%" . dbStr(trim($search_keyword_array[$i])) . "%' OR pro.pro_ean = '" . dbStr(trim($search_keyword_array[$i])) . "' OR pro.pro_udx_seo_epag_title LIKE '%" . dbStr(trim($search_keyword_array[$i])) . "%' OR EXISTS ( SELECT 1 FROM products_feature AS pf WHERE pf.supplier_id = pro.supplier_id AND pf.pf_fname = 'Verwendung für Druckertyp' AND pf.pf_fvalue LIKE '%" . dbStr(trim($search_keyword_array[$i])) . "%')  THEN 1 ELSE 0 END) + ";
				$search_keyword_where .= " OR pro.pro_udx_seo_epag_title LIKE '%" . dbStr(trim($search_keyword_array[$i])) . "%'";
				$search_keyword_pk_title_where .= "pf.pf_fvalue LIKE '%" . dbStr(trim($search_keyword_array[$i])) . "%' OR ";
			}
		}
	} else {
		$search_keyword_case = "(CASE WHEN  pro.pro_udx_seo_epag_title LIKE '" . dbStr(trim($result['corrected'])) . "%' OR EXISTS ( SELECT 1 FROM products_feature AS pf WHERE pf.supplier_id = pro.supplier_id AND pf.pf_fname = 'Verwendung für Druckertyp' AND pf.pf_fvalue LIKE '" . dbStr(trim($result['corrected'])) . "%') THEN 1 ELSE 0 END) + ";
		$search_keyword_case .= " ( CASE WHEN pro.supplier_id = '" . dbStr(trim($_REQUEST['search_keyword'])) . "' OR pro.pro_manufacture_aid LIKE '%" . dbStr(trim($result['corrected'])) . "%' OR pro.pro_ean = '" . dbStr(trim($_REQUEST['search_keyword'])) . "' OR pro.pro_udx_seo_epag_title LIKE '" . dbStr(trim($result['corrected'])) . "%' OR EXISTS ( SELECT 1 FROM products_feature AS pf WHERE pf.supplier_id = pro.supplier_id AND pf.pf_fname = 'Verwendung für Druckertyp' AND pf.pf_fvalue LIKE '" . dbStr(trim($result['corrected'])) . "%') THEN 1 ELSE 0 END) ";
		$search_keyword_where = " OR pro.pro_udx_seo_epag_title LIKE '%" . dbStr(trim($result['corrected'])) . "%' ";
		$search_keyword_pk_title_where = "pf.pf_fvalue LIKE '%" . dbStr(trim($result['corrected'])) . "%' OR ";
	}


	$Sidefilter_where = $Sidefilter_brandwith = $Sidefilter_featurewhere = "IN (SELECT pro.supplier_id FROM vu_products AS pro WHERE " . ltrim($search_keyword_where, " OR ") . ")";

	$heading_title .= "Schlagwort : " . $_REQUEST['search_keyword'];
	$qryStrURL .= "search_keyword=" . $_REQUEST['search_keyword'] . "&";
	$search_keyword = $_REQUEST['search_keyword'];
}
$search_group_id_check = array();
$search_group_id_where = "";
if (isset($_REQUEST['search_group_id']) && $_REQUEST['search_group_id'] > 0) {
	$whereclause = "";
	//print_r($_REQUEST['search_group_id']);//die();
	for ($i = 0; $i < count($_REQUEST['search_group_id']); $i++) {
		$search_group_id_check[] = $_REQUEST['search_group_id'][$i];
		if (!empty($search_group_id_where)) {
			$search_group_id_where .= " OR ";
		}
		$search_group_id_where .= "FIND_IN_SET (" . dbStr(trim($_REQUEST['search_group_id'][$i])) . ", cm.sub_group_ids)";
		$qryStrURL .= "search_group_id[]=" . $_REQUEST['search_group_id'][$i] . "&";
	}
	$Sidefilter_featurewhere = "IN (SELECT pro.supplier_id FROM vu_products AS pro WHERE pro.supplier_id IN ( SELECT cm.supplier_id FROM category_map AS cm WHERE (" . $search_group_id_where . ")) AND (" . ltrim($search_keyword_where, " OR ") . ") )";
	//$search_whereclause = " AND pro.supplier_id IN (SELECT cm.supplier_id FROM category_map AS cm WHERE (" . $search_group_id_where . ")) ";
	$search_whereclause = " AND pro.supplier_id IN (SELECT cm.supplier_id FROM category_map AS cm WHERE " . $search_group_id_where . ") ";
	$Sidefilter_brandwith = "IN (SELECT cm.supplier_id FROM category_map AS cm WHERE (" . $search_group_id_where . ") AND cm.supplier_id " . rtrim($Sidefilter_where, " OR ") . " ) ";
}
$search_manf_id_check = array();
if (isset($_REQUEST['search_manf_id']) && $_REQUEST['search_manf_id'] > 0) {
	//print_r($_REQUEST['search_manf_id']);//die();
	$search_manf_id = "";
	for ($i = 0; $i < count($_REQUEST['search_manf_id']); $i++) {
		$search_manf_id_check[] = $_REQUEST['search_manf_id'][$i];
		$search_manf_id .= $_REQUEST['search_manf_id'][$i] . ",";
		$qryStrURL .= "search_manf_id[]=" . $_REQUEST['search_manf_id'][$i] . "&";
	}
	$search_whereclause .= " AND pro.manf_id IN (" . rtrim($search_manf_id, ",") . ")";
	if (empty($search_group_id_where)) {
		$Sidefilter_featurewhere = "IN (SELECT pro.supplier_id FROM vu_products AS pro WHERE ( " . ltrim($search_keyword_where, " OR ") . ") AND pro.manf_id IN (" . rtrim($search_manf_id, ",") . ")  ) ";
	} else {
		$Sidefilter_featurewhere = "IN (SELECT pro.supplier_id FROM vu_products AS pro WHERE pro.supplier_id IN ( SELECT cm.supplier_id FROM category_map AS cm WHERE (" . $search_group_id_where . ")) AND (" . ltrim($search_keyword_where, " OR ") . ")  AND pro.manf_id IN (" . rtrim($search_manf_id, ",") . ")  ) ";
	}
}


$search_pf_fname_check = array();
$search_pf_fvalue_check = array();
if (isset($_REQUEST['search_pf_fvalue']) && $_REQUEST['search_pf_fvalue'] > 0) {
	//print_r($_REQUEST);//die();
	//print_r($_REQUEST['search_pf_forder']);die();
	//print_r($_REQUEST['search_pf_fvalue']);die();
	$search_pf_fname = "";
	$search_pf_fvalue = "";
	foreach ($_POST['search_pf_fvalue'] as $index => $selected_value) {
		$search_pf_fname_check[] = $_REQUEST['search_pf_fname'][$index];
		$search_pf_fvalue_check[] = $_REQUEST['search_pf_fvalue'][$index];
		$search_pf_fname .= "'" . dbStr(trim($_REQUEST['search_pf_fname'][$index])) . "',";
		$search_pf_fvalue .= "'" . dbStr(trim($_REQUEST['search_pf_fvalue'][$index])) . "',";
		$qryStrURL .= "search_pf_fvalue[]=" . $_REQUEST['search_pf_fvalue'][$index] . "&";
	}
	$search_whereclause .= " AND pro.supplier_id IN (SELECT pf.supplier_id FROM products_feature AS pf WHERE pf.pf_fname IN (" . rtrim($search_pf_fname, ",") . ") AND pf.pf_fvalue IN (" . rtrim($search_pf_fvalue, ",") . ") AND  pf.supplier_id IN (SELECT pro.supplier_id FROM vu_products AS pro WHERE " . ltrim($search_keyword_where, " OR ") . ") )";
	//$search_whereclause .= " AND pro.supplier_id IN (SELECT pf.supplier_id FROM products_feature AS pf WHERE pf.pf_fvalue IN (" . rtrim($search_pf_fvalue, ",") . ") AND  pf.supplier_id IN (SELECT pro.supplier_id FROM vu_products AS pro WHERE " . rtrim($search_keyword_where, " OR ") . ") )";
}
$sortby = 0;
$sortby_array = array("Sortieren nach", "Preis absteigend", "Preis aufsteigend", "Name A-Z", "Name Z-A");
?>
<!doctype html>
<html lang="de">

<head>
	<?php include("includes/html_header.php"); ?>
	<script>
		$(document).ready(function() {
			$(".click_list").click(function() {
				$(".list_porduct").addClass('list_class');
				$(".detail_data_show").show();
				$(".pd_image").css("height", "100%");
			});
			$(".click_th").click(function() {
				$(".list_porduct").removeClass('list_class');
				$(".detail_data_show").hide();
				$(".pd_image").css("height", "");
			});
		});
		/*$(document).ready(function() {
			if (window.innerWidth >= 1024) {
				$(".click_list").trigger('click');
			}
		});*/
	</script>
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
					<div class="product_inner">
						<div class="filter_mobile">Filter <i class="fa fa-angle-down"></i></div>
						<?php include("includes/searchPage_left_filter.php"); ?>
						<div class="pd_right">
							<div class="pd_row_heading">
								<div class="list_type_row">
									<h2> <?php print(rtrim($heading_title, ";")); ?> ( <span id="search_product_counter">0</span> ) </h2>
									<ul>
										<li>Ansicht </li>
										<li class="click_th"><i class="fa fa-th"></i></li>
										<li class="click_list"><i class="fa fa-list"></i></li>
										<li>
											<div class="drop-down_2">
												<div class="selected">
													<input type="hidden" name="sort_by_selected" id="sort_by_selected" value="<?php print($sortby); ?>">
													<a href="javascript:void(0)" title="<?php print($sortby_array[$sortby]); ?>"><span> <?php print($sortby_array[$sortby]); ?> </span></a>
												</div>
												<div class="options">
													<ul>
														<?php for ($i = 0; $i < count($sortby_array); $i++) {
															if ($i != $sortby) { ?>
																<li><a href="javascript:void(0)" class="sort_by" id="sort_by" data-id="<?php print($i); ?>" title="<?php print($sortby_array[$i]); ?>"><?php print($sortby_array[$i]); ?></a></li>
														<?php }
														} ?>
													</ul>
												</div>
											</div>
										</li>
									</ul>
								</div>
							</div>
							<div class="list_porduct">
								<div class="gerenric_product">
									<input type="hidden" name="search_product_inner_page" id="search_product_inner_page" value="0">
									<div class="gerenric_product_inner" id="search_product_inner">
										<div class="txt_align_center spinner" id="search_product_inner_spinner">
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

				</div>
			</div>
		</section>
		<!--CONTENT_SECTION_END-->

		<!--FOOTER_SECTION_START-->

		<?php include("includes/footer.php"); ?>
		<!--FOOTER_SECTION_END-->

	</div>

</body>
<script>
	$(".color_tab").on("mouseover", function() {
		let color_title = $(this).attr('title');
		let data_id = $(this).attr('data-id');
		//let supplier_id = $(this).attr('data-supplier-id');
		//console.log("color_tab: "+data_id+" supplier_id: "+supplier_id);
		$("#color_title_" + data_id).text(color_title);
	});
	$(".color_tab").on("mouseout", function() {
		let data_id = $(this).attr('data-id');
		let color_radio = $('input[name="color_radio_' + data_id + '"]:checked').val();
		let color_title = $("#color_tab_" + color_radio).attr('title');
		//console.log("mouseout: "+color_radio);
		$("#color_title_" + data_id).text(color_title);
	});
	$(".color_tab").on("click", function() {
		let supplier_id = $(this).attr("data-supplier-id");
		let pro_description = $(this).attr("data-pro-description");
		//console.log("color_tab: "+supplier_id);
		window.location.href = pro_description + "-" + supplier_id;
	});
	//TOGGLING NESTED ul
	$(".drop-down_2 .selected a").click(function() {
		$(".drop-down_2 .options ul").toggle();
	});

	//SELECT OPTIONS AND HIDE OPTION AFTER SELECTION
	$(".drop-down_2 .options ul li a").click(function() {
		var text = $(this).html();
		$(".drop-down_2 .selected a span").html(text);
		$(".drop-down_2 .options ul").hide();
	});


	//HIDE OPTIONS IF CLICKED ANYWHERE ELSE ON PAGE
	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (!$clicked.parents().hasClass("drop-down_2"))
			$(".drop-down_2 .options ul").hide();
	});
</script>
<script>
	$(".show-more").click(function() {
		if ($("#category_show_" + $(this).attr('data-id') + ", #list_checkbox_hide_" + $(this).attr('data-id') + " ").hasClass("category_show_height")) {
			$(this).text("(Weniger anzeigen)");
		} else {
			$(this).text("(Mehr anzeigen)");
		}

		$("#category_show_" + $(this).attr('data-id') + ", #list_checkbox_hide_" + $(this).attr('data-id') + "").toggleClass("category_show_height");
	});

	let loading = false;

	$(window).scroll(function () {

		let scrollTop = $(window).scrollTop();
		let windowHeight = $(window).height();
		let documentHeight = $(document).height();

		// Trigger when 150px near bottom
		if (scrollTop + windowHeight >= documentHeight - 250 && !loading) {

			loading = true;

			//console.log("Near bottom - load more");

			search_product_inner();
		}

	});
	$(window).on("load", function () {
		search_product_inner();
	});

	function search_product_inner() {
		$("#btn_load_spinner").show();
		let start = $("#search_product_inner_page").val();
		let sortby = $("#sort_by_selected").val();
		let search_keyword = "<?php print($_REQUEST['search_keyword']); ?>";
		let search_keyword_case = "<?php print($search_keyword_case); ?>";
		let search_keyword_where = "<?php print($search_keyword_where); ?>";
		let search_keyword_pk_title_where = "<?php print($search_keyword_pk_title_where); ?>";
		let search_whereclause = "<?php print($search_whereclause); ?>";
		let price_without_tex_display = '<?php print($price_without_tex_display); ?>';
		let pbp_price_with_tex_display = '<?php print($pbp_price_with_tex_display); ?>';
		$.ajax({
			url: 'ajax_calls.php?action=search_product_inner',
			method: 'POST',
			data: {
				start: start,
				sortby: sortby,
				search_keyword: search_keyword,
				search_keyword_case: search_keyword_case,
				search_keyword_where: search_keyword_where,
				search_keyword_pk_title_where: search_keyword_pk_title_where,
				search_whereclause: search_whereclause,
				price_without_tex_display: price_without_tex_display,
				pbp_price_with_tex_display: pbp_price_with_tex_display
			},
			success: function(response) {
				//console.log("response = "+response);
				const obj = JSON.parse(response);
				//console.log(obj);
				if (obj.status == 1) {
					$("#btn_load_spinner").hide();
					$("#search_product_inner_spinner").hide();
				    $("#search_product_counter").text(obj.counter);
				    $("#search_product_inner_page").val(obj.search_product_inner_page);
					$("#search_product_inner").append(obj.search_product_inner);
				}
				//genaric_javascript();
			},
			complete:function(){
				loading = false;
			}
		});
	}

	$(".sort_by").on("click", function() {
		let sort_by = $(this).attr("data-id");
		$("#sort_by_selected").val(sort_by);
		$("#search_product_inner").html("");
		$("#search_product_inner_page").val(0);
		search_product_inner();
	});
</script>
<?php include("includes/bottom_js.php"); ?>

</html>