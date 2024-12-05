<?php
include("includes/php_includes_top.php");
$page = 1;
?>
<!doctype html>
<html lang="de">

<head>
	<?php include("includes/html_header.php"); ?>
</head>

<body>
	<div id="container" align="center">

		<!--LOCATION_POPUP_START-->
		<?php include("includes/popup.php"); ?>
		<!--LOCATION_POPUP_END-->

		<!--HEADER_SECTION_START-->
		<?php include("includes/navigation.php"); ?>
		<!--HEADER_SECTION_END-->

		<!--BANNER_SECTION_START-->
		<?php include("includes/banner.php"); ?>
		<!--BANNER_SECTION_END-->

		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="home_page">
				<div class="page_width_1480">
					<div class="hm_section_1">
						<div class="gerenric_product_category">
							<?php 
							$Query1 = "SELECT cat_id, group_id, parent_id, cat_title_de AS cat_title FROM category WHERE cat_status = '1' AND cat_showhome = '1'";
							$rs1 = mysqli_query($GLOBALS['conn'], $Query1);
							if(mysqli_num_rows($rs1) > 0){
								while($row1 = mysqli_fetch_object($rs1)){
							?>
							<div class="pd_ctg_block">
								<div class="pd_ctg_heading"> <?php print($row1->cat_title); ?> </div>
								<div class="pd_ctg_row">
									<?php 
									$Query2 = "SELECT cm.cat_id, cm.supplier_id, c.cat_title_de AS cat_title, pg.pg_mime_source FROM category_map AS cm LEFT OUTER JOIN category AS c ON c.group_id = SUBSTRING(cm.cat_id, 1, 3) LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = cm.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1'  WHERE FIND_IN_SET(".$row1->group_id.", cm.sub_group_ids) ORDER BY  RAND() LIMIT 0,4";
									$rs2 = mysqli_query($GLOBALS['conn'], $Query2);
									if(mysqli_num_rows($rs2) > 0){
										while($row2 = mysqli_fetch_object($rs2)){
									?>
									<div class="pd_ctg_card">
										<a href="product_category.html">
											<div class="pd_ctg_image"><img src="getftpimage.php?img=<?php print($row2->pg_mime_source); ?>" alt=""></div>
											<div class="pd_ctg_title"> <?php print($row2->cat_title); ?> </div>
										</a>
									</div>
									<?php
										}
									}
									?>
								</div>
							</div>
							<?php
								}
							}
							?>
						</div>
					</div>
					<div class="hm_section_2">
						<div class="gerenric_white_box">
							<div class="gerenric_product full_column">
								<h2 class="pd_heading">Best-selling products</h2>
								<div class="gerenric_slider">
									<div>
										<div class="pd_card">
											<div class="pd_image"><a href="product_detail.html"><img
														src="images/product_img1.jpg" alt=""></a></div>
											<div class="pd_detail">
												<h5><a href="product_detail.html">BRÜDER MANNESMANN hand stapler M48410
														+500 staples/500 nails</a></h5>
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
												<div class="pd_prise">270.20€</div>
											</div>
										</div>
									</div>
									<div>
										<div class="pd_card">
											<div class="pd_image"><a href="product_detail.html"><img
														src="images/product_img2.jpg" alt=""></a></div>
											<div class="pd_detail">
												<h5><a href="product_detail.html">COOCAZOO pencil case 00211516 Cloudy
														Camou</a></h5>
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
												<div class="pd_prise">12.60€</div>
											</div>
										</div>
									</div>
									<div>
										<div class="pd_card">
											<div class="pd_image"><a href="product_detail.html"><img
														src="images/product_img1.jpg" alt=""></a></div>
											<div class="pd_detail">
												<h5><a href="product_detail.html">BRÜDER MANNESMANN hand stapler M48410
														+500 staples/500 nails</a></h5>
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
												<div class="pd_prise">270.20€</div>
											</div>
										</div>
									</div>
									<div>
										<div class="pd_card">
											<div class="pd_image"><a href="product_detail.html"><img
														src="images/product_img2.jpg" alt=""></a></div>
											<div class="pd_detail">
												<h5><a href="product_detail.html">COOCAZOO pencil case 00211516 Cloudy
														Camou</a></h5>
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
												<div class="pd_prise">12.60€</div>
											</div>
										</div>
									</div>
									<div>
										<div class="pd_card">
											<div class="pd_image"><a href="product_detail.html"><img
														src="images/product_img1.jpg" alt=""></a></div>
											<div class="pd_detail">
												<h5><a href="product_detail.html">BRÜDER MANNESMANN hand stapler M48410
														+500 staples/500 nails</a></h5>
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
												<div class="pd_prise">270.20€</div>
											</div>
										</div>
									</div>
									<div>
										<div class="pd_card">
											<div class="pd_image"><a href="product_detail.html"><img
														src="images/product_img2.jpg" alt=""></a></div>
											<div class="pd_detail">
												<h5><a href="product_detail.html">COOCAZOO pencil case 00211516 Cloudy
														Camou</a></h5>
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
												<div class="pd_prise">12.60€</div>
											</div>
										</div>
									</div>
									<div>
										<div class="pd_card">
											<div class="pd_image"><a href="product_detail.html"><img
														src="images/product_img1.jpg" alt=""></a></div>
											<div class="pd_detail">
												<h5><a href="product_detail.html">BRÜDER MANNESMANN hand stapler M48410
														+500 staples/500 nails</a></h5>
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
												<div class="pd_prise">270.20€</div>
											</div>
										</div>
									</div>
									<div>
										<div class="pd_card">
											<div class="pd_image"><a href="product_detail.html"><img
														src="images/product_img2.jpg" alt=""></a></div>
											<div class="pd_detail">
												<h5><a href="product_detail.html">COOCAZOO pencil case 00211516 Cloudy
														Camou</a></h5>
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
												<div class="pd_prise">12.60€</div>
											</div>
										</div>
									</div>
								</div>
								<div class="gerenric_show_All"><a href="javascript:void(0)">Show More</a></div>
							</div>
						</div>
						<div class="gerenric_white_box">
							<div class="gerenric_product full_column">
								<h2>Satchel</h2>
								<div class="gerenric_slider">
									<div>
										<div class="pd_card">
											<div class="pd_image"><a href="product_detail.html"><img
														src="images/product_img1.jpg" alt=""></a></div>
											<div class="pd_detail">
												<h5><a href="product_detail.html">BRÜDER MANNESMANN hand stapler M48410
														+500 staples/500 nails</a></h5>
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
												<div class="pd_prise">270.20€</div>
											</div>
										</div>
									</div>
									<div>
										<div class="pd_card">
											<div class="pd_image"><a href="product_detail.html"><img
														src="images/product_img2.jpg" alt=""></a></div>
											<div class="pd_detail">
												<h5><a href="product_detail.html">COOCAZOO pencil case 00211516 Cloudy
														Camou</a></h5>
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
												<div class="pd_prise">12.60€</div>
											</div>
										</div>
									</div>
									<div>
										<div class="pd_card">
											<div class="pd_image"><a href="product_detail.html"><img
														src="images/product_img1.jpg" alt=""></a></div>
											<div class="pd_detail">
												<h5><a href="product_detail.html">BRÜDER MANNESMANN hand stapler M48410
														+500 staples/500 nails</a></h5>
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
												<div class="pd_prise">270.20€</div>
											</div>
										</div>
									</div>
									<div>
										<div class="pd_card">
											<div class="pd_image"><a href="product_detail.html"><img
														src="images/product_img2.jpg" alt=""></a></div>
											<div class="pd_detail">
												<h5><a href="product_detail.html">COOCAZOO pencil case 00211516 Cloudy
														Camou</a></h5>
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
												<div class="pd_prise">12.60€</div>
											</div>
										</div>
									</div>
									<div>
										<div class="pd_card">
											<div class="pd_image"><a href="product_detail.html"><img
														src="images/product_img1.jpg" alt=""></a></div>
											<div class="pd_detail">
												<h5><a href="product_detail.html">BRÜDER MANNESMANN hand stapler M48410
														+500 staples/500 nails</a></h5>
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
												<div class="pd_prise">270.20€</div>
											</div>
										</div>
									</div>
									<div>
										<div class="pd_card">
											<div class="pd_image"><a href="product_detail.html"><img
														src="images/product_img2.jpg" alt=""></a></div>
											<div class="pd_detail">
												<h5><a href="product_detail.html">COOCAZOO pencil case 00211516 Cloudy
														Camou</a></h5>
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
												<div class="pd_prise">12.60€</div>
											</div>
										</div>
									</div>
									<div>
										<div class="pd_card">
											<div class="pd_image"><a href="product_detail.html"><img
														src="images/product_img1.jpg" alt=""></a></div>
											<div class="pd_detail">
												<h5><a href="product_detail.html">BRÜDER MANNESMANN hand stapler M48410
														+500 staples/500 nails</a></h5>
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
												<div class="pd_prise">270.20€</div>
											</div>
										</div>
									</div>
									<div>
										<div class="pd_card">
											<div class="pd_image"><a href="product_detail.html"><img
														src="images/product_img2.jpg" alt=""></a></div>
											<div class="pd_detail">
												<h5><a href="product_detail.html">COOCAZOO pencil case 00211516 Cloudy
														Camou</a></h5>
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
												<div class="pd_prise">12.60€</div>
											</div>
										</div>
									</div>
								</div>
								<div class="gerenric_show_All"><a href="javascript:void(0)">Show More</a></div>
							</div>
						</div>
						<?php 
							$Query1 = "SELECT cat_id, group_id, parent_id, cat_title_de AS cat_title FROM category WHERE cat_status = '1' AND cat_showhome_feature = '1'";
							$rs1 = mysqli_query($GLOBALS['conn'], $Query1);
							if(mysqli_num_rows($rs1) > 0){
								while($row1 = mysqli_fetch_object($rs1)){
							?>
						<div class="gerenric_white_box">
							<div class="gerenric_product full_column">
								<h2>Beliebte Produkte in <?php print($row1->cat_title); ?></h2>
								<div class="gerenric_slider">
								<?php 
									$Query2 = "SELECT cm.cat_id, cm.supplier_id, pro.pro_description_short, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount,  pbp.pbp_price_amount AS pbp_price_without_tax,  pg.pg_mime_source FROM category_map AS cm LEFT OUTER JOIN products AS pro ON pro.supplier_id = cm.supplier_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = cm.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = cm.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1'  WHERE FIND_IN_SET(".$row1->group_id.", cm.sub_group_ids) ORDER BY  RAND() LIMIT 0,12";
									//print($Query2);die();
									$rs2 = mysqli_query($GLOBALS['conn'], $Query2);
									if(mysqli_num_rows($rs2) > 0){
										while($row2 = mysqli_fetch_object($rs2)){
									?>
									<div>
										<div class="pd_card">
											<div class="pd_image"><a href="product_detail.php?supplier_id=<?php print($row2->supplier_id); ?>"><img src="getftpimage.php?img=<?php print($row2->pg_mime_source); ?>" alt=""></a></div>
											<div class="pd_detail">
												<h5><a href="product_detail.php?supplier_id=<?php print($row2->supplier_id); ?>"> <?php print($row2->pro_description_short); ?> </a></h5>
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
												<div class="pd_prise price_without_tex" ><?php print(str_replace(".", ",", $row2->pbp_price_without_tax)); ?> €</div>
												<div class="pd_prise pbp_price_with_tex"><?php print(str_replace(".", ",", $row2->pbp_price_amount)); ?> €</div>
											</div>
										</div>
									</div>
									<?php 
										}
									}
									?>
								</div>
							</div>
							<div class="gerenric_show_All"><a href="javascript:void(0)">Show More</a></div>
						</div>
						<?php 
								}
							}
						?>
						
						<div class="gerenric_white_box">
							<div class="hm_register">
								<div class="full_width txt_align_center"><a href="login_page.html">
										<div class="gerenric_btn">Login</div>
									</a></div>
								<p>New to Wacker24? <a href="register_page.html">Create here</a></p>
							</div>
						</div>
					</div>
				</div>
				<div class="hm_section_3">
					<div class="gerenric_white_box">
						<h2 class="txt_align_center">Top Manufacturers & Brands</h2>
						<div class="hm_brand_logo">
							<div class="brand_slider">
								<div>
									<div class="brand_col"><a href="javascript:void(0)">
											<div class="brand_item"><img src="images/brand/25282870117.jpg" alt="">
											</div>
										</a></div>
								</div>
								<div>
									<div class="brand_col"><a href="javascript:void(0)">
											<div class="brand_item"><img src="images/brand/28907443919.jpg" alt="">
											</div>
										</a></div>
								</div>
								<div>
									<div class="brand_col"><a href="javascript:void(0)">
											<div class="brand_item"><img src="images/brand/343640116.jpg" alt=""></div>
										</a></div>
								</div>
								<div>
									<div class="brand_col"><a href="javascript:void(0)">
											<div class="brand_item"><img src="images/brand/89018368172.jpg" alt="">
											</div>
										</a></div>
								</div>
								<div>
									<div class="brand_col"><a href="javascript:void(0)">
											<div class="brand_item"><img src="images/brand/25282870117.jpg" alt="">
											</div>
										</a></div>
								</div>
								<div>
									<div class="brand_col"><a href="javascript:void(0)">
											<div class="brand_item"><img src="images/brand/28907443919.jpg" alt="">
											</div>
										</a></div>
								</div>
								<div>
									<div class="brand_col"><a href="javascript:void(0)">
											<div class="brand_item"><img src="images/brand/343640116.jpg" alt=""></div>
										</a></div>
								</div>
								<div>
									<div class="brand_col"><a href="javascript:void(0)">
											<div class="brand_item"><img src="images/brand/89018368172.jpg" alt="">
											</div>
										</a></div>
								</div>
								<div>
									<div class="brand_col"><a href="javascript:void(0)">
											<div class="brand_item"><img src="images/brand/25282870117.jpg" alt="">
											</div>
										</a></div>
								</div>
								<div>
									<div class="brand_col"><a href="javascript:void(0)">
											<div class="brand_item"><img src="images/brand/28907443919.jpg" alt="">
											</div>
										</a></div>
								</div>
								<div>
									<div class="brand_col"><a href="javascript:void(0)">
											<div class="brand_item"><img src="images/brand/343640116.jpg" alt=""></div>
										</a></div>
								</div>
								<div>
									<div class="brand_col"><a href="javascript:void(0)">
											<div class="brand_item"><img src="images/brand/89018368172.jpg" alt="">
											</div>
										</a></div>
								</div>
							</div>
						</div>
						<div class="brand_btn"><a href="javascript:void(0)">
								<div class="gerenric_btn">Show All</div>
							</a></div>
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
		autoplay: true,
		autoplaySpeed: 3000,
		slidesToScroll: 1,
	});
	$(".gerenric_slider").slick({
		slidesToShow: 6,
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
	$(".brand_slider").slick({
		slidesToShow: 10,
		slidesToScroll: 1,
		autoplay: true,
		dots: false,
		autoplaySpeed: 2000,
		infinite: true,
		responsive: [
			{
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
<?php include("includes/bottom_js.php"); ?>

</html>