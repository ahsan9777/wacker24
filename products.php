<?php
include("includes/php_includes_top.php");

//print_r($_REQUEST);die();
$lf_action_type = 1;
$pro_type = (isset($_REQUEST['pro_type']) ? $_REQUEST['pro_type'] : 0);
$whereclause = "WHERE 1 = 1";
if(isset($_REQUEST['lf_group_id']) && !empty($_REQUEST['lf_group_id'])){
	if(strlen($_REQUEST['lf_group_id'][0]) > 3){
		$whereclause .= " AND cm.pro_type = '".$pro_type."' AND FIND_IN_SET(".$_REQUEST['lf_group_id'][0].", cm.cat_id)";
	} else{
		$whereclause .= " AND cm.pro_type = '".$pro_type."' AND FIND_IN_SET(".$_REQUEST['lf_group_id'][0].", cm.sub_group_ids)";
	}
	$heading_title = returnName("cat_title_de AS cat_title", "category", "group_id", $_REQUEST['lf_group_id'][0]);

} elseif(isset($_REQUEST['lf_manf_id']) && !empty($_REQUEST['lf_manf_id'])){
	$whereclause .= " AND cm.pro_type = '".$pro_type."' AND FIND_IN_SET(".$_REQUEST['lf_parent_id'].", cm.sub_group_ids) AND cm.manf_id = '".$_REQUEST['lf_manf_id'][0]."'";
	$heading_title = returnName("manf_name", "manufacture", "manf_id", $_REQUEST['lf_manf_id'][0]);

} elseif(isset($_REQUEST['lf_pf_fvalue']) && !empty($_REQUEST['lf_pf_fvalue'])){

	$whereclause .= " AND cm.pro_type = '".$pro_type."' AND FIND_IN_SET(".$_REQUEST['lf_parent_id'].", cm.sub_group_ids) AND cm.supplier_id IN (SELECT pf.supplier_id FROM products_feature AS pf WHERE  pf.pf_fvalue_params_de = '".$_REQUEST['lf_pf_fvalue'][0]."')";
	$heading_title = returnName("cat_title_de AS cat_title", "category", "group_id", $_REQUEST['lf_parent_id']);
}

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
							<div class="pd_row_heading">
								<div class="list_type_row">
									<h2> <?php print($heading_title) ?> </h2>
									<ul>
										<li>Ansichten: </li>
										<li class="click_th"><i class="fa fa-th"></i></li>
										<li class="click_list"><i class="fa fa-list"></i></li>
									</ul>
								</div>
							</div>
							<!--<div class="list_porduct list_class">-->
							<div class="list_porduct">
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

	$(window).load(function(){
		gerenric_product_inner();
	});

	function gerenric_product_inner(lf_group_id_data = "", lf_manf_id_data = "", lf_pf_fvalue_data = "", add_more = 0){
			$("#btn_load").hide();
			$("#btn_load_spinner").show();
			let start = $("#gerenric_product_inner_page").val();
			let lf_parent_id = "<?php print($_REQUEST['lf_parent_id']); ?>";
			let pro_type = "<?php print($pro_type); ?>";
            let whereclause = "<?php print($whereclause); ?>";
			let price_without_tex_display = "<?php print($price_without_tex_display); ?>";
            let pbp_price_with_tex_display = "<?php print($pbp_price_with_tex_display); ?>";
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
                    lf_parent_id: lf_parent_id,
                    pro_type: pro_type,
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
					if(obj.counter == obj.last_record){
						$(".load-more-button").hide();
						$(".load-less-button").show();
					} else{
						$(".load-more-button").show();
						$(".load-less-button").hide();
					}
                    if (obj.status == 1) {
                        $("#gerenric_product_inner_spinner").hide();
						$("#btn_load").show();
						$("#gerenric_product_inner_page").val(obj.gerenric_product_inner_page);
                        $("#gerenric_product_inner").append(obj.gerenric_product_inner);
                    }
					
                }
            //}, 5000);
        });
	}
	
	$(".load-more-button").on("click", function(){
		
		var lf_group_id_data = [];
		$(".lf_group_id:checked").each(function() {
			lf_group_id_data.push($(this).val());
		});

		var lf_manf_id_data = [];
		$(".lf_manf_id:checked").each(function() {
			lf_manf_id_data.push($(this).val());
		});
		
		var lf_pf_fvalue_data = [];
		$(".lf_manf_id:checked").each(function() {
			lf_pf_fvalue_data.push($(this).val());
		});
		//console.log("Selected values: " + lf_group_id_data.join(", "));
		//console.log("Selected values: " + lf_manf_id_data.join(", "));
		gerenric_product_inner(lf_group_id_data.join(", "), lf_manf_id_data.join(", "), lf_pf_fvalue_data.join(", "), 1);
		
		
	});
	$(".load-less-button").on("click", function(){
		$("#gerenric_product_inner").html("");
		$("#gerenric_product_inner_page").val(0);
		gerenric_product_inner();
	});
</script>


<?php include("includes/bottom_js.php"); ?>

</html>