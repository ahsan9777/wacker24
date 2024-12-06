<?php 
include("includes/php_includes_top.php");
if(isset($_REQUEST['manf_id']) && $_REQUEST['manf_id'] > 0){

	$whereclause = "WHERE pro.manf_id = '".$_REQUEST['manf_id']."' ";
	$qryStrURL .= "manf_id=".$_REQUEST['manf_id']."&";
	$heading_title = returnName("manf_name", "manufacture", "manf_id", $_REQUEST['manf_id']);

}elseif(isset($_REQUEST['level_three']) && $_REQUEST['level_three'] > 0){
	$whereclause = "WHERE cm.cat_id = '".$_REQUEST['level_three']."' ";
	$qryStrURL .= "level_three=".$_REQUEST['level_three']."&";
	$heading_title = returnName("cat_title_de AS cat_title", "category", "group_id", $_REQUEST['level_three']);
} else{
	$whereclause = "WHERE FIND_IN_SET(" . $_REQUEST['level_two'] . ", cm.sub_group_ids)";
	$qryStrURL .= "level_two=".$_REQUEST['level_two']."&";
	$heading_title = returnName("cat_title_de AS cat_title", "category", "group_id", $_REQUEST['level_two']);
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

									
									$Query = "SELECT cm.cat_id, cm.supplier_id, pro.pro_description_short, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount,  pbp.pbp_price_amount AS pbp_price_without_tax,  pg.pg_mime_source FROM category_map AS cm LEFT OUTER JOIN products AS pro ON pro.supplier_id = cm.supplier_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = cm.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = cm.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' ".$whereclause." ";
									//print($Query);die();
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
												<div class="pd_image"><a href="product_detail.php?supplier_id=<?php print($row->supplier_id); ?>"><img src="getftpimage.php?img=<?php print($row->pg_mime_source); ?>" alt=""></a></div>
												<div class="pd_detail">
													<h5><a href="product_detail.php?supplier_id=<?php print($row->supplier_id); ?>"> <?php print($row->pro_description_short); ?> </a></h5>
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
													<div class="pd_prise price_without_tex"><?php print(str_replace(".", ",", $row->pbp_price_without_tax)); ?> €</div>
													<div class="pd_prise pbp_price_with_tex"><?php print(str_replace(".", ",", $row->pbp_price_amount)); ?> €</div>
													<div class="pd_btn"><a href="product_detail.html">
															<div class="gerenric_btn">Add to Cart</div>
														</a></div>
												</div>
											</div>
									<?php
										}
									} else{
										print("Record not found!");
									}
									?>
								</div>
								<?php if ($counter > 0) { ?>
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td><?php print("Page <b>" . $_GET['page'] . "</b> of " . $pages); ?></td>
										<td align="right">
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
<script src="js/slick.js"></script>
<script type="text/javascript">
	$(".banner_slider").slick({
		dots: false,
		infinite: true,
		slidesToShow: 1,
		autoplay: false,
		autoplaySpeed: 3000,
		slidesToScroll: 1,
	});
	$(".gerenric_slider").slick({
		slidesToShow: 6,
		slidesToScroll: 1,
		autoplay: false,
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
	$(".brand_slider").slick({
		slidesToShow: 10,
		slidesToScroll: 1,
		autoplay: false,
		dots: false,
		autoplaySpeed: 2000,
		infinite: true,
		responsive: [{
				breakpoint: 1200,
				settings: {
					slidesToShow: 6,
					slidesToScroll: 1
				}
			},
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
<script>
	$(".show-more").click(function() {
		if ($(".category_show, .list_checkbox_hide").hasClass("category_show_height")) {
			$(this).text("(Show Less)");
		} else {
			$(this).text("(Show More)");
		}

		$(".category_show, .list_checkbox_hide").toggleClass("category_show_height");
	});
</script>
<?php include("includes/bottom_js.php"); ?>

</html>