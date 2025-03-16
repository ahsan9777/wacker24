<?php
include("includes/php_includes_top.php");
$AND = "";
$cat_params = "";
$group_id_check = 0;
//print_r($_REQUEST);die();
if(isset($_REQUEST['cat_params_two']) && isset($_REQUEST['cat_params_three'])){
	$AND = returnName("group_id", "category", "cat_params_de", $_REQUEST['cat_params_two']);
	$group_id = returnName("group_id", "category", "cat_params_de", $_REQUEST['cat_params_three'], " AND parent_id = '".$AND."'");
	$group_id_check = 1;
} else if(isset($_REQUEST['manf_name_params']) && isset($_REQUEST['cat_params_request'])){
	$cat_params_request = returnName("group_id", "category", "cat_params_de", $_REQUEST['cat_params_request'], "ORDER BY cat_id DESC");
	if($_REQUEST['level'] == 2){
		$group_id = $cat_params_request;
		$level_two_request_manf = $cat_params_request;
		$group_id_check = 1;
	} else if($_REQUEST['level'] == 3){
		$group_id = $cat_params_request;
		$level_three_request_manf = $cat_params_request;
		$group_id_check = 1;
	} else{
		$level_one_request = $cat_params_request;
	}
	$cat_params = $_REQUEST['cat_params_request'];
	$manf_params_id = returnName("manf_id", "manufacture", "manf_name_params", $_REQUEST['manf_name_params']);
} else if(isset($_REQUEST['cat_params_two'])){
	$group_id = returnName("group_id", "category", "cat_params_de", $_REQUEST['cat_params_two']);
	$group_id_check = 1;
}
//print($group_id);die();
if($group_id_check > 0){
	if(strlen($group_id) > 3){
		$level_three_request = $group_id;
	} else{
		$level_two_request = $group_id;
	}
}
if (isset($manf_params_id) && $manf_params_id > 0) {
	$whereclause = "WHERE cm.manf_id = '" . $manf_params_id . "' ";
	$qryStrURL .= "manf_id=" . $manf_params_id . "&";
	if (isset($level_one_request) && $level_one_request > 0) {

		$whereclause .= " AND FIND_IN_SET(" . $level_one_request . ", cm.sub_group_ids)";
		//$cat_params = returnName("cat_params_de AS cat_params", "category", "group_id", $level_one_request);
		$qryStrURL .= "level_one=" . $level_one_request . "&";
	} elseif (isset($level_two_request_manf) && $level_two_request_manf > 0) {

		$whereclause .= " AND FIND_IN_SET(" . $level_two_request . ", cm.sub_group_ids)";
		$qryStrURL .= "level_two=" . $level_two_request . "&";
	} elseif (isset($level_three_request_manf) && $level_three_request_manf > 0) {

		$whereclause = "WHERE cm.cat_id = '" . $level_three_request_manf . "' ";
		$qryStrURL .= "level_three=" . $level_three_request_manf . "&";
	}
	$heading_title = returnName("manf_name", "manufacture", "manf_id", $manf_params_id);
} else if (isset($level_three_request) && $level_three_request > 0) {
	$whereclause = "WHERE cm.cat_id = '" . $level_three_request . "' ";
	$qryStrURL .= "level_three=" . $level_three_request . "&";
	$heading_title = returnName("cat_title_de AS cat_title", "category", "group_id", $level_three_request);
	$cat_params = returnName("cat_params_de AS cat_params", "category", "group_id", $level_three_request);
} else {
	$whereclause = "WHERE FIND_IN_SET(" . $level_two_request . ", cm.sub_group_ids)";
	$qryStrURL .= "level_two=" . $level_two_request . "&";
	$heading_title = returnName("cat_title_de AS cat_title", "category", "group_id", $level_two_request);
	$cat_params = returnName("cat_params_de AS cat_params", "category", "group_id", $level_two_request);
	//if (isset($_SESSION["UID"]) && $_SESSION["UID"] > 0) {
	$cat_id_one = $cat_title_one = returnName("parent_id", "category", "group_id", $level_two_request);
	$special_price = user_special_price("level_two", $level_two_request);
	if (!$special_price) {
		$special_price = user_special_price("level_one", $cat_id_one);
	}
	//print_r($special_price);
	//}
}

$pro_type = 0;
$pro_typeURL = "";
if (isset($_REQUEST['pro_type']) && $_REQUEST['pro_type'] > 0) {
	$pro_type = $_REQUEST['pro_type'];
	$pro_typeURL .= "pro_type=" . $_REQUEST['pro_type'] . "&";
	$qryStrURL .= "pro_type=" . $_REQUEST['pro_type'] . "&";
}


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
							<div class="gerenric_product">
								<h2> <?php print($heading_title); ?> </h2>
								<div class="gerenric_product_inner">
									<?php


									$Query = "SELECT * FROM vu_category_map AS cm " . $whereclause . " AND cm.cm_type = '" . $pro_type . "' ";
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
													<div class="pd_btn">
														<a class="add_to_card" href="javascript:void(0)" data-id="<?php print($row->pro_id); ?>">
															<input type="hidden" id="pro_id_<?php print($row->pro_id); ?>" name="pro_id" value="<?php print($row->pro_id); ?>">
															<input type="hidden" id="supplier_id_<?php print($row->pro_id); ?>" name="supplier_id" value="<?php print($row->supplier_id); ?>">
															<input type="hidden" id="ci_qty_<?php print($row->pro_id); ?>" name="ci_qty" value="1">
															<input type="hidden" id="ci_discount_type_<?php print($row->pro_id); ?>" name="ci_discount_type" value="<?php print((!empty($special_price)) ? $special_price['usp_price_type'] : '0'); ?>">
															<input type="hidden" id="ci_discount_value_<?php print($row->pro_id); ?>" name="ci_discount_value" value="<?php print((!empty($special_price)) ? $special_price['usp_discounted_value'] : '0'); ?>">
															<div class="gerenric_btn">In den Einkaufswagen</div>
														</a>
													</div>
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
													$pageList = $p->pageList($_GET['page'], $pages, '&' . $qryStrURL);
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

</html>