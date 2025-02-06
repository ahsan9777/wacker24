<?php
include("includes/php_includes_top.php");
$page = 1;

if (isset($_REQUEST['btnAdd'])) {
	//print_r($_REQUEST);die();
	$Query = "SELECT * FROM shopping_list WHERE user_id = '" . $_SESSION["UID"] . "' AND sl_title = '" . $_REQUEST['sl_title'] . "'";
	//print($Query);die();
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) > 0) {
		$row = mysqli_fetch_object($rs);
		header("Location: " . $_SERVER['PHP_SELF'] . "?supplier_id=" . $_REQUEST['supplier_id'] . "&op=14");
	} else {
		$sl_id = getMaximum("shopping_list", "sl_id");
		mysqli_query($GLOBALS['conn'], "INSERT INTO shopping_list (sl_id, user_id, sl_title) VALUES (" . $sl_id . ", '" . $_SESSION["UID"] . "','" . dbStr(trim($_REQUEST['sl_title'])) . "')") or die(mysqli_error($GLOBALS['conn']));
		header("Location: " . $_SERVER['PHP_SELF'] . "?supplier_id=" . $_REQUEST['supplier_id'] . "&op=1");
	}
}

if (isset($_REQUEST['updatewishlist'])) {
	mysqli_query($GLOBALS['conn'], "UPDATE wishlist SET sl_id = '".$_REQUEST['sl_id']."' WHERE wl_id = " . $_REQUEST['wl_id']) or die(mysqli_error($_REQUEST['conn']));
	header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
}
if (isset($_REQUEST['deletewishlist'])) {
	mysqli_query($GLOBALS['conn'], "DELETE FROM wishlist WHERE wl_id = " . $_REQUEST['wl_id']) or die(mysqli_error($_REQUEST['conn']));
	header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=3");
}
include("includes/message.php");
?>
<!doctype html>
<html>

<head>
	<?php include("includes/html_header.php"); ?>

	<script>
		$(document).ready(function() {
			$('.grnc_tabnav_tabs > li > a').click(function(event) {
				event.preventDefault();
				var active_tab_selector = $('.grnc_tabnav_tabs > li.active > a').attr('href');
				var actived_nav = $('.grnc_tabnav_tabs > li.active');
				actived_nav.removeClass('active');
				$(this).parents('li').addClass('active');
				$(active_tab_selector).removeClass('active');
				$(active_tab_selector).addClass('hide');
				var target_tab_selector = $(this).attr('href');
				$(target_tab_selector).removeClass('hide');
				$(target_tab_selector).addClass('active');
			});
		});
	</script>
	<script>
		$(document).ready(function() {
			$(".click_list").click(function() {
				$(".list_porduct").addClass('list_class');
			});
			$(".click_th").click(function() {
				$(".list_porduct").removeClass('list_class');
			});
		});
	</script>
</head>

<body>
	<div id="container" align="center">

		<!--LOCATION_POPUP_START-->
		<?php include("includes/popup.php"); ?>
		<!--LOCATION_POPUP_END-->

		<!--CREATE_LIST_POPUP_START-->
		<div class="create_list_popup">
			<div class="inner_popup">
				<form class="create_list_content" name="frm" id="frmaddress" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
					<div class="create_list_heading">Create List <div class="create_list_close"><i class="fa fa-times"></i></div>
					</div>
					<div class="create_list_content_inner">
						<p>List Name (Required)</p>
						<input type="text" class="input_list" required name="sl_title" id="sl_title">
						<div class="create_button">
							<button class="gerenric_btn" type="submit" name="btnAdd">ADD</button>
							<div class="gerenric_btn create_list_close">Cancel</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<!--CREATE_LIST_POPUP_END-->

		<!--HEADER_SECTION_START-->
		<?php include("includes/navigation.php"); ?>
		<!--HEADER_SECTION_END-->

		<!--BREADCRUMB_SECTION_START-->
		<div class="gerenric_breadcrumb">
			<div class="page_width_1480">
				<div class="breadcrumb_inner">
					<ul>
						<li><a href="personal_data.php">My personal data</a></li>
						<li><a href="javascript:void(0)">My shopping lists</a></li>
					</ul>
				</div>
			</div>
		</div>
		<!--BREADCRUMB_SECTION_END-->

		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="new_shopping_list_page gerenric_padding">
				<div class="page_width_1480">
					<?php if ($class != "") { ?>
						<div class="<?php print($class); ?>"><?php print($strMSG); ?><a href="javascript:void(0);" class="close" data-dismiss="alert">×</a></div>
					<?php } ?>
					<div class="shopping_list_inner">
						<div class="shopping_list_left">
							<div class="grnc_tabnav">
								<ul class="grnc_tabnav_tabs ">
									<?php
									$count = 0;
									$Query1 = "SELECT * FROM shopping_list WHERE user_id = '" . $_SESSION["UID"] . "' ORDER BY sl_id ASC";
									$rs1 = mysqli_query($GLOBALS['conn'], $Query1);
									if (mysqli_num_rows($rs1) > 0) {
										while ($row1 = mysqli_fetch_object($rs1)) {
											$count++;
									?>
											<li <?php print(($count == 1) ? 'class="active"' : ''); ?>>
												<a href="#tab<?php print($row1->sl_id); ?>">
													<div class="tab_title"><?php print($row1->sl_title); ?></div>
													<div class="tab_private">Private</div>
												</a>
											</li>
									<?php
										}
									}
									?>
								</ul>
							</div>
						</div>

						<div class="shopping_list_right">
							<div class="grnc_tabnav_content">
								<div class="shopping_list_detail">
									<div class="shopping_list_title">
										<div class="shopping_list_profile">
											<div class="list_name">Ahsan <span>Private</span></div>
										</div>
										<div class="shopping_list_create create_list_trigger">Create a List</div>
									</div>
									<div class="list_type_row">
										<ul>
											<li class="click_th"><i class="fa fa-th"></i></li>
											<li class="click_list"><i class="fa fa-list"></i></li>
										</ul>
										<div class="list_type_search">
											<input type="text" class="list_search_input">
											<button class="search_button"><i class="fa fa-search"></i></button>
										</div>
									</div>
									<?php
									$count = 0;
									$Query2 = "SELECT * FROM shopping_list WHERE user_id = '" . $_SESSION["UID"] . "' ORDER BY sl_id ASC";
									$rs2 = mysqli_query($GLOBALS['conn'], $Query2);
									if (mysqli_num_rows($rs2) > 0) {
										while ($row2 = mysqli_fetch_object($rs2)) {
											$count++;
									?>
											<div class="list_porduct <?php print(($count == 1) ? 'active' : 'hide'); ?>" id="tab<?php print($row2->sl_id); ?>">
												<div class="gerenric_product">
													<div class="gerenric_product_inner">
														<?php
														$special_price = "";
														$Query3 = "SELECT wl.*, cm.cat_id, cm.sub_group_ids, cm.cm_type, pro.pro_id, pro.pro_description_short, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount,  pbp.pbp_price_amount AS pbp_price_without_tax,  pg.pg_mime_source_url FROM wishlist AS wl LEFT OUTER JOIN category_map AS cm ON cm.supplier_id = wl.supplier_id LEFT OUTER JOIN products AS pro ON pro.supplier_id = wl.supplier_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = wl.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = wl.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' WHERE wl.sl_id = '" . $row2->sl_id . "' ORDER BY wl.wl_id ASC";
														//print($Query);die();
														$rs3 = mysqli_query($GLOBALS['conn'], $Query3);
														if (mysqli_num_rows($rs3) > 0) {
															while ($row3 = mysqli_fetch_object($rs3)) {
																$sub_group_ids = explode(",", $row3->sub_group_ids);
																$cat_id_one = $sub_group_ids[1];
																$cat_id_two = $sub_group_ids[0];
																//if (isset($_SESSION["UID"]) && $_SESSION["UID"] > 0) {
																$special_price = user_special_price("supplier_id", $row3->supplier_id);

																if (!$special_price) {
																	$special_price = user_special_price("level_two", $cat_id_two);
																}

																if (!$special_price) {
																	$special_price = user_special_price("level_one", $cat_id_one);
																}
														?>
																<div class="pd_card">
																	<div class="pd_image"><a href="product_detail.php?supplier_id=<?php print($row3->supplier_id); ?>"><img src="<?php print($row3->pg_mime_source_url); ?>" alt=""></a></div>
																	<div class="pd_detail">
																		<h5><a href="product_detail.php?supplier_id=<?php print($row3->supplier_id); ?>"> <?php print($row3->pro_description_short); ?> </a></h5>
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
																			<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . $row3->pbp_price_without_tax . "€</del> <span class='pd_prise_discount'>" . discounted_price($special_price['usp_price_type'], $row3->pbp_price_without_tax, $special_price['usp_discounted_value']) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
																			<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . $row3->pbp_price_amount . "€</del> <span class='pd_prise_discount'>" . discounted_price($special_price['usp_price_type'], $row3->pbp_price_amount, $special_price['usp_discounted_value'], 1) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
																		<?php } else { ?>
																			<div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(str_replace(".", ",", $row3->pbp_price_without_tax)); ?>€</div>
																			<div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(str_replace(".", ",", $row3->pbp_price_amount)); ?>€</div>
																		<?php } ?>
																		<div class="pd_action">
																			<ul>
																				<!--<li><a href="javascript:void(0)"><i class="fa fa-eye"></i></a></li>
																				<li><a href="javascript:void(0)"><i class="fa fa-edit"></i></a></li>-->
																				<li><a href="<?php print($_SERVER['PHP_SELF'] . "?deletewishlist&wl_id=" . $row3->wl_id); ?>" onclick="return confirm('Are you sure you want to delete selected item(s)?');"><i class="fa fa-trash"></i></a></li>
																				<li>
																					<select class="pd_slt sl_id" name="sl_id" id="sl_id_<?php print($row3->wl_id); ?>" data-id="<?php print($row3->wl_id); ?>">
																						<option value="0">Move</option>
																						<?php
																						$count = 0;
																						$Query4 = "SELECT * FROM shopping_list WHERE user_id = '" . $_SESSION["UID"] . "' AND sl_id != '".$row3->sl_id."' ORDER BY sl_id ASC";
																						$rs4 = mysqli_query($GLOBALS['conn'], $Query4);
																						if (mysqli_num_rows($rs4) > 0) {
																							while ($row4 = mysqli_fetch_object($rs4)) {
																								$count++;
																						?>
																								<option value="<?php print($row4->sl_id); ?>"><?php print($row4->sl_title); ?></option>
																						<?php
																							}
																						}
																						?>
																					</select>
																				</li>
																			</ul>
																		</div>
																		<div class="pd_btn">
																			<a class="add_to_card" href="javascript:void(0)" data-id="<?php print($row3->pro_id); ?>">
																				<input type="hidden" id="pro_id_<?php print($row3->pro_id); ?>" name="pro_id" value="<?php print($row3->pro_id); ?>">
																				<input type="hidden" id="supplier_id_<?php print($row3->pro_id); ?>" name="supplier_id" value="<?php print($row3->supplier_id); ?>">
																				<input type="hidden" id="ci_qty_<?php print($row3->pro_id); ?>" name="ci_qty" value="1">
																				<input type="hidden" id="ci_discount_type_<?php print($row3->pro_id); ?>" name="ci_discount_type" value="<?php print((!empty($special_price)) ? $special_price['usp_price_type'] : '0'); ?>">
																				<input type="hidden" id="ci_discount_value_<?php print($row3->pro_id); ?>" name="ci_discount_value" value="<?php print((!empty($special_price)) ? $special_price['usp_discounted_value'] : '0'); ?>">
																				<div class="gerenric_btn">Add to Cart</div>
																			</a>
																		</div>
																	</div>
																</div>
														<?php
															}
														} else {
															print('<div class="margin_top_30 txt_align_center wd_100">Record not found!</div>');
														}
														?>
													</div>
												</div>
											</div>
									<?php

										}
									}
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
		<div id="scroll_top">Back to top</div>
		<?php include("includes/footer.php"); ?>
		<!--FOOTER_SECTION_END-->

	</div>

</body>
<?php include("includes/bottom_js.php"); ?>
<script>
	$(window).load(function() {
		/*2 popup 1 hide 1 show*/
		$(".create_list_trigger").click(function() {
			$('.create_list_popup').show();
			$('.create_list_popup').resize();
			$('body').css({
				'overflow': 'hidden'
			});
		});
		$('.create_list_close').click(function() {
			$('.create_list_popup').hide();
			$('body').css({
				'overflow': 'inherit'
			});
		});

	});
</script>
<script>
	$('.gerenric_product .pd_detail .pd_action ul li .pd_slt').change(function() {
		var text = $(this).find('option:selected').text()
		var $aux = $('<select/>').append($('<option/>').text(text))
		$(this).after($aux)
		$(this).width($aux.width())
		$aux.remove()
	}).change()
</script>
<script>
	$(".sl_id").on("change", function(){
		let wl_id = $(this).attr("data-id");
		let sl_id = $("#sl_id_"+$(this).attr("data-id")).val();
		//console.log("wl_id: "+wl_id+" sl_id: "+sl_id);
		window.location = "<?php print($_SERVER['PHP_SELF']) ?>?updatewishlist&wl_id="+wl_id+"&sl_id="+sl_id;
	});
</script>

</html>