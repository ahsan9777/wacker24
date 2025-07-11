<?php
include("includes/php_includes_top.php");

/*$key = "1234567890abcdef1234567890abcdef"; // Must match the encryption key

if (isset($_GET['data'])) {
    $data = base64_decode($_GET['data']);
    $iv = substr($data, 0, 16);                  // Extract IV
    $encrypted = substr($data, 16);              // Extract encrypted data

    $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
    parse_str($decrypted, $params);              // Convert string to array

    // Use parameters
    echo "lf_parent_id: " . $params['lf_parent_id'] . "<br>";
    echo "pro_type: " . $params['pro_type'] . "<br>";
    echo "lf_group_id: " . $params['lf_group_id'][0] . "<br>";
}*/
//print_r($_REQUEST);die();
$lf_action_type = 1;
$pro_type = (isset($_REQUEST['pro_type']) ? $_REQUEST['pro_type'] : 0);
$whereclause = "WHERE 1 = 1";
$whereclause_top_category = "";

$level_three = 0;
$level_two = 0;
$lf_parent_id = 0;
$level_two_link = 1;
if (isset($_REQUEST['level_three'])) {
	$AND = returnName("group_id", "category", "cat_params_de", $_REQUEST['level_two'], " AND parent_id > 0");
	$level_three = returnName("group_id", "category", "cat_params_de", $_REQUEST['level_three'], " AND parent_id = '" . $AND . "'");
	$lf_parent_id = returnName("parent_id", "category", "cat_params_de", $_REQUEST['level_three'], " AND parent_id = '" . $AND . "'");
	if ($pro_type == 20) {
		$lf_parent_id = 19;
	}
} elseif (isset($_REQUEST['level_two'])) {
	$level_two = returnName("group_id", "category", "cat_params_de", $_REQUEST['level_two']);
	$lf_parent_id = returnName("parent_id", "category", "cat_params_de", $_REQUEST['level_two']);
} //die();
if ((isset($_REQUEST['lf_group_id']) && !empty($_REQUEST['lf_group_id'])) || $level_three > 0 || $level_two > 0) {
	$level_two_link = 0;
	if ($level_three > 0) {
		$lf_group_id = $lf_parent_id;
	} elseif ($level_two > 0) {
		$lf_group_id = $level_two;
	} else {
		$lf_group_id = $_REQUEST['lf_group_id'][0];
		$lf_parent_id = $_REQUEST['lf_parent_id'];
	}
	if (strlen($lf_group_id) > 3) {
		$whereclause .= " AND cm.pro_type = '" . $pro_type . "' AND (" . $lf_group_id . ", cm.cat_id)";
	} else {
		if($pro_type == 20){
			$whereclause .= " AND cm.pro_type = '" . $pro_type . "' ";
		} else {
			$whereclause .= " AND cm.pro_type = '" . $pro_type . "' AND FIND_IN_SET(" . $lf_group_id . ", cm.sub_group_ids)";
		}
	}
	$whereclause_top_category = " WHERE sub_cat.parent_id  = '" . $lf_group_id . "' AND sub_cat.cat_status = '1' AND EXISTS (SELECT 1 FROM category_map AS cm WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(sub_cat.group_id, cm.cat_id) )";
	$heading_title = returnName("cat_title_de AS cat_title", "category", "group_id", $lf_group_id);
} elseif (isset($_REQUEST['lf_manf_id']) && !empty($_REQUEST['lf_manf_id'])) {
	$whereclause .= " AND cm.pro_type = '" . $pro_type . "' AND FIND_IN_SET(" . $_REQUEST['lf_parent_id'] . ", cm.sub_group_ids) AND cm.manf_id = '" . $_REQUEST['lf_manf_id'][0] . "'";
	$whereclause_top_category = " WHERE sub_cat.parent_id = '" . $_REQUEST['lf_parent_id'] . "' AND sub_cat.cat_status = '1' AND EXISTS (SELECT 1 FROM category_map AS cm WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(sub_cat.group_id, cm.sub_group_ids) )";
	$heading_title = returnName("manf_name", "manufacture", "manf_id", $_REQUEST['lf_manf_id'][0]);
} elseif (isset($_REQUEST['lf_pf_fvalue']) && !empty($_REQUEST['lf_pf_fvalue'])) {

	$whereclause .= " AND cm.pro_type = '" . $pro_type . "' AND FIND_IN_SET(" . $_REQUEST['lf_parent_id'] . ", cm.sub_group_ids) AND cm.supplier_id IN (SELECT pf.supplier_id FROM products_feature AS pf WHERE  pf.pf_fvalue_params_de = '" . $_REQUEST['lf_pf_fvalue'][0] . "')";
	$whereclause_top_category = " WHERE sub_cat.parent_id = '" . $_REQUEST['lf_parent_id'] . "' AND sub_cat.cat_status = '1' AND EXISTS (SELECT 1 FROM category_map AS cm WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(sub_cat.group_id, cm.sub_group_ids) )";
	$heading_title = returnName("cat_title_de AS cat_title", "category", "group_id", $_REQUEST['lf_parent_id']);
}

if ($pro_type == 20) {
	$whereclause_top_category = " WHERE sub_cat.parent_id = '20' AND sub_cat.cat_status = '1' AND EXISTS (SELECT 1 FROM category_map AS cm WHERE cm.cm_type = '" . $pro_type . "' AND cm.cat_id_level_two = sub_cat.group_id )";
}

$sortby = 0;
$sortby_array = array("Sortieren nach", "Preis absteigend", "Preis aufsteigend", "Name A-Z", "Name Z-A");

//print($whereclause);


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
					<div class="product_inner">
						<div class="filter_mobile">Filter <i class="fa fa-angle-down"></i></div>
						<?php include("includes/left_filter.php"); ?>
						<div class="pd_right">
							<div class="category_type_product">
								<div class="category_type_inner full_column">
									<div class="category-slider">
										<?php
										//$Query = "SELECT sub_cat.cat_id, sub_cat.group_id, sub_cat.parent_id, cat.cat_title_de AS cat_title, sub_cat.cat_title_de AS sub_cat_title, cat.cat_params_de AS cat_params, sub_cat.cat_params_de AS sub_cat_params, sub_cat.cat_orderby, sub_cat.cat_status FROM category AS sub_cat LEFT OUTER JOIN category AS cat ON cat.group_id = sub_cat.parent_id  WHERE sub_cat.parent_id IN ( SELECT main_cat.group_id FROM category AS main_cat WHERE main_cat.parent_id = '" . $lf_parent_id . "' ORDER BY main_cat.group_id ASC) AND sub_cat.cat_status = '1' AND EXISTS (SELECT 1 FROM category_map AS cm WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(sub_cat.group_id, cm.cat_id) ) ORDER BY sub_cat.cat_orderby ASC, sub_cat.group_id ASC";
										$Query = "SELECT sub_cat.cat_id, sub_cat.group_id, sub_cat.parent_id, cat.cat_title_de AS cat_title, sub_cat.cat_title_de AS sub_cat_title, cat.cat_params_de AS cat_params, sub_cat.cat_params_de AS sub_cat_params, sub_cat.cat_orderby, sub_cat.cat_status FROM category AS sub_cat LEFT OUTER JOIN category AS cat ON cat.group_id = sub_cat.parent_id  " . $whereclause_top_category . "  ORDER BY sub_cat.cat_orderby ASC, sub_cat.group_id ASC";
										//print($Query);die();
										$rs = mysqli_query($GLOBALS['conn'], $Query);
										if (mysqli_num_rows($rs) > 0) {
											while ($row = mysqli_fetch_object($rs)) {
												$pg_mime_source_url_href = "files/no_img_1.jpg";
												if (strlen($row->group_id) < 4) {
													if ($pro_type == 20) {
														$category_data = returnMultiName("pg_mime_source_url, MIN(pbp_price_without_tax), MIN(pbp_price_amount)", "vu_category_map", "cat_id_level_two",  $row->group_id, 3, "AND cm_type = '" . $pro_type . "' GROUP BY cat_id_level_two");
													} else {
														$category_data = returnMultiName("pg_mime_source_url, MIN(pbp_price_without_tax), MIN(pbp_price_amount)", "vu_category_map", "cat_id_level_two",  $row->group_id, 3, "AND cm_type = '" . $pro_type . "' GROUP BY sub_group_ids");
													}
												} else {
													$category_data = returnMultiName("pg_mime_source_url, MIN(pbp_price_without_tax), MIN(pbp_price_amount)", "vu_category_map", "cat_id",  $row->group_id, 3, "AND cm_type = '" . $pro_type . "' GROUP BY cat_id");
												}
												//print_r($category_data);die();
												if (empty($category_data)) {
													continue;
												}
												$pg_mime_source_url_href = $category_data['data_1'];
												$pbp_price_without_tax = $category_data['data_2'];
												$pbp_price_amount = $category_data['data_3'];
												if ($level_two_link > 0) {
													$cat_link = "products.php?lf_parent_id=" . $row->parent_id . "&pro_type=" . $pro_type . "&lf_group_id[]=" . $row->group_id;
												} else {
													$cat_link = "artikelarten/" . $row->cat_params . "/" . $row->sub_cat_params;
													if ($pro_type == 20) {
														$cat_link = "artikelarten/" . $row->sub_cat_params."/" . $pro_type;
													}
												}

												print('<div>
													<div class="ctg_type_col">
													<a href="' . $cat_link . '">
														<div class="ctg_type_card">
															<div class="ctg_type_image"><img loading="lazy" src="' . get_image_link(160, $pg_mime_source_url_href) . '" alt=""></div>
															<div class="ctg_type_detail">
																<div class="ctg_type_title">' . $row->sub_cat_title . '</div>
																<div class="ctg_type_price price_without_tex" ' . $price_without_tex_display . ' > ab ' . price_format(($pbp_price_without_tax > 0) ? $pbp_price_without_tax : 0.00) . ' €</div>
																<div class="ctg_type_price pbp_price_with_tex" ' . $pbp_price_with_tex_display . ' >ab ' . price_format(($pbp_price_amount) ? $pbp_price_amount : 0.00) . ' €</div>
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
							<div class="pd_row_heading">
								<div class="list_type_row">
									<h2> <?php print($heading_title) ?> </h2>
									<ul>
										<li>Ansicht </li>
										<li class="click_th"><i class="fa fa-th"></i></li>
										<li class="click_list"><i class="fa fa-list"></i></li>
										<li style="margin-right: 0px">
											<div class="drop-down_2">
												<div class="selected">
													<input type="hidden" name="sort_by_selected" id="sort_by_selected" value="<?php print($sortby); ?>">
													<a href="javascript:void(0)"><span> <?php print($sortby_array[$sortby]); ?> </span></a>
												</div>
												<div class="options">
													<ul>
														<?php for ($i = 0; $i < count($sortby_array); $i++) {
															if ($i != $sortby) { ?>
																<li><a href="javascript:void(0)" class="sort_by" id="sort_by" data-id="<?php print($i); ?>"><?php print($sortby_array[$i]); ?></a></li>
														<?php }
														} ?>
													</ul>
												</div>
											</div>
										</li>
									</ul>
								</div>
							</div>
							<!--<div class="list_porduct list_class">-->
							<div class="list_porduct" id="list_porduct">
								<div class="gerenric_product">
									<div class="gerenric_product_inner" id="gerenric_product_inner">
										<div class="txt_align_center spinner" id="gerenric_product_inner_spinner">
											<!--<input type="hidden" name="gerenric_product_inner_page" id="gerenric_product_inner_page" value="0">-->
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
										<input type="hidden" name="gerenric_product_inner_page" id="gerenric_product_inner_page" value="0">
										<div class="load-more-button">Weitere anzeigen &nbsp;<i class="fa fa-angle-down" aria-hidden="true"></i></div>
										<div class="load-less-button" style="display:none">Ansicht schließen &nbsp;<i class="fa fa-angle-up" aria-hidden="true"></i></div>
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
		<div id="scroll_top">Zurück zum Seitenanfang</div>
		<?php include("includes/footer.php"); ?>
		<!--FOOTER_SECTION_END-->

	</div>

</body>
<script>
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

	<?php if (!isset($_REQUEST['level_three'])) { ?>
		$(window).load(function() {
			gerenric_product_inner();
		});
	<?php } ?>

	function gerenric_product_inner(lf_group_id_data = "", lf_manf_id_data = "", lf_pf_fvalue_data = "", add_more = 0) {
		$("#btn_load").hide();
		$("#btn_load_spinner").show();
		let start = $("#gerenric_product_inner_page").val();
		let sortby = $("#sort_by_selected").val();
		let lf_parent_id = "<?php print($lf_parent_id); ?>";
		let pro_type = "<?php print($pro_type); ?>";
		let level_two = "<?php print($level_two); ?>";
		let whereclause = "<?php print($whereclause); ?>";
		let price_without_tex_display = '<?php print($price_without_tex_display); ?>';
		let pbp_price_with_tex_display = '<?php print($pbp_price_with_tex_display); ?>';
		let lf_group_id = "";
		if (typeof lf_group_id_data !== 'undefined' && lf_group_id_data !== null && lf_group_id_data != "") {
			if (add_more == 0) {
				$("#gerenric_product_inner").html("");
				$("#gerenric_product_inner_page").val(0);
			}
			$("#btn_load").hide();
			$("#btn_load_spinner").show();
			lf_group_id = lf_group_id_data;
		}
		let lf_manf_id = "";
		if (typeof lf_manf_id_data !== 'undefined' && lf_manf_id_data !== null && lf_manf_id_data != "") {
			if (add_more == 0) {
				$("#gerenric_product_inner").html("");
				$("#gerenric_product_inner_page").val(0);
			}
			$("#btn_load").hide();
			$("#btn_load_spinner").show();
			lf_manf_id = lf_manf_id_data;
		}
		let lf_pf_fvalue = "";
		if (typeof lf_pf_fvalue_data !== 'undefined' && lf_pf_fvalue_data !== null && lf_pf_fvalue_data != "") {
			if (add_more == 0) {
				$("#gerenric_product_inner").html("");
				$("#gerenric_product_inner_page").val(0);
			}
			$("#btn_load").hide();
			$("#btn_load_spinner").show();
			lf_pf_fvalue = lf_pf_fvalue_data;
		}
		$.ajax({
			url: 'ajax_calls.php?action=gerenric_product_inner',
			method: 'POST',
			data: {
				start: start,
				sortby: sortby,
				lf_parent_id: lf_parent_id,
				pro_type: pro_type,
				level_two: level_two,
				whereclause: whereclause,
				price_without_tex_display: price_without_tex_display,
				pbp_price_with_tex_display: pbp_price_with_tex_display,
				lf_group_id: lf_group_id,
				lf_manf_id: lf_manf_id,
				lf_pf_fvalue: lf_pf_fvalue
			},
			success: function(response) {
				//console.log("raw response = "+response);
				const obj = JSON.parse(response);
				//console.log(obj);
				$("#spinner_category_type_inner").hide();
				$("#btn_load_spinner").hide();
				if (obj.total_count == obj.last_record) {
					$(".load-more-button").hide();
					$(".load-less-button").show();
				} else {
					$(".load-more-button").show();
					$(".load-less-button").hide();
				}
				if (obj.status == 1) {
					$("#gerenric_product_inner_spinner").hide();
					if (obj.total_count > 24) {
						$("#btn_load").show();
					}
					$("#gerenric_product_inner_page").val(obj.gerenric_product_inner_page);
					$("#gerenric_product_inner").append(obj.gerenric_product_inner);
					var className = $('#list_porduct').attr('class');
					if (className == 'list_porduct list_class') {
						$(".click_list").trigger("click");
					}
					genaric_script();
				}

			}
			//}, 5000);
		});
	}

	$(".load-more-button").on("click", function() {

		var lf_group_id_data = [];
		$(".lf_group_id:checked").each(function() {
			lf_group_id_data.push($(this).val());
		});

		var lf_manf_id_data = [];
		$(".lf_manf_id:checked").each(function() {
			lf_manf_id_data.push($(this).val());
		});

		var lf_pf_fvalue_data = [];
		$(".lf_pf_fvalue:checked").each(function() {
			lf_pf_fvalue_data.push($(this).val());
		});
		//console.log("Selected values: " + lf_group_id_data.join(", "));
		//console.log("Selected values: " + lf_manf_id_data.join(", "));
		//console.log("Selected values: " + lf_pf_fvalue_data.join(", "));
		gerenric_product_inner(lf_group_id_data.join(", "), lf_manf_id_data.join(", "), lf_pf_fvalue_data.join(", "), 1);
	});
	$(".load-less-button").on("click", function() {
		$("#gerenric_product_inner").html("");
		$("#gerenric_product_inner_page").val(0);
		var lf_group_id_data = [];
		$(".lf_group_id:checked").each(function() {
			lf_group_id_data.push($(this).val());
		});

		var lf_manf_id_data = [];
		$(".lf_manf_id:checked").each(function() {
			lf_manf_id_data.push($(this).val());
		});

		var lf_pf_fvalue_data = [];
		$(".lf_pf_fvalue:checked").each(function() {
			lf_pf_fvalue_data.push($(this).val());
		});
		//console.log("Selected values: " + lf_group_id_data.join(", "));
		//console.log("Selected values: " + lf_manf_id_data.join(", "));
		//console.log("Selected values: " + lf_pf_fvalue_data.join(", "));
		gerenric_product_inner(lf_group_id_data.join(", "), lf_manf_id_data.join(", "), lf_pf_fvalue_data.join(", "), 1);
	});

	$(".sort_by").on("click", function() {
		let sort_by = $(this).attr("data-id");
		$("#sort_by_selected").val(sort_by);

		$("#gerenric_product_inner").html("");
		$("#gerenric_product_inner_page").val(0);
		var lf_group_id_data = [];
		$(".lf_group_id:checked").each(function() {
			lf_group_id_data.push($(this).val());
		});

		var lf_manf_id_data = [];
		$(".lf_manf_id:checked").each(function() {
			lf_manf_id_data.push($(this).val());
		});

		var lf_pf_fvalue_data = [];
		$(".lf_pf_fvalue:checked").each(function() {
			lf_pf_fvalue_data.push($(this).val());
		});
		//console.log("Selected values: " + lf_group_id_data.join(", "));
		//console.log("Selected values: " + lf_manf_id_data.join(", "));
		//console.log("Selected values: " + lf_pf_fvalue_data.join(", "));
		gerenric_product_inner(lf_group_id_data.join(", "), lf_manf_id_data.join(", "), lf_pf_fvalue_data.join(", "));
		//console.log("sort_by: "+sort_by);
	});

	function genaric_script() {
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
			window.location.href = "product/" + supplier_id + "/" + pro_description;
		});
	}
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
		let pro_type = "<?php print($pro_type); ?>";
		let leve_id = "<?php print($leve_id); ?>";
		let left_filter_cat_WhereQuery = "<?php print($left_filter_cat_WhereQuery); ?>";
		let level_check = "<?php print($level_three); ?>";

		$.ajax({
			url: 'ajax_calls.php?action=lf_group_id_inner',
			method: 'POST',
			data: {
				lf_action_type: lf_action_type,
				pro_type: pro_type,
				leve_id: leve_id,
				left_filter_cat_WhereQuery: left_filter_cat_WhereQuery,
				level_check: level_check
			},
			success: function(response) {
				//console.log("response = "+response);
				const obj = JSON.parse(response);
				//console.log(obj);
				if (obj.status == 1) {
					$("#lf_group_id_loading").hide();
					$("#lf_group_id_inner").html(obj.lf_group_id_inner);

					if (level_check > 0 && !hasTriggeredClick) {
						//setTimeout(function() {
						var lf_group_id = [];
						$(".lf_group_id:checked").each(function() {
							lf_group_id.push($(this).val());
						});
						lf_manf_id_inner(lf_group_id.join(", "));
						lf_pf_fvalue_inner(lf_group_id.join(", "));
						gerenric_product_inner(lf_group_id.join(", "));
						hasTriggeredClick = true; // Mark as triggered
						//}, 100); // Slight delay to ensure DOM is updated
					}
				}
			}
			//}, 5000);
		});
	}

	function lf_manf_id_inner(lf_group_id_data) {
		//setTimeout(function() {
		let lf_action_type = "<?php print($lf_action_type); ?>";
		let pro_type = "<?php print($pro_type); ?>";
		let leve_id = "<?php print($leve_id); ?>";
		let Sidefilter_brandwith = "<?php print($Sidefilter_brandwith); ?>";
		let manf_check = <?php echo json_encode($manf_check); ?>;
		let lf_group_id = "";
		if (typeof lf_group_id_data !== 'undefined' && lf_group_id_data !== null && lf_group_id_data != "") {
			lf_group_id = lf_group_id_data;
		}
		$.ajax({
			url: 'ajax_calls.php?action=lf_manf_id_inner',
			method: 'POST',
			data: {
				lf_action_type: lf_action_type,
				lf_group_id: lf_group_id,
				pro_type: pro_type,
				leve_id: leve_id,
				Sidefilter_brandwith: Sidefilter_brandwith,
				manf_check: manf_check
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
		$.ajax({
			url: 'ajax_calls.php?action=lf_pf_fvalue_inner',
			method: 'POST',
			data: {
				lf_group_id: lf_group_id,
				lf_manf_id: lf_manf_id,
				lf_action_type: lf_action_type,
				leve_id: leve_id,
				pf_fvalue_check: pf_fvalue_check
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

			}
			//}, 5000);
		});
	}

	function lf_manf_id_inner_script() {
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
			if ($(this).attr("data-id") > 0) {
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
</script>

</html>