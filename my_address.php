<?php
include("includes/php_includes_top.php");
$page = 1;
?>
<!doctype html>
<html>

<head>
	<?php include("includes/html_header.php"); ?>
</head>

<body>
	<div id="container" align="center">

		<!--LOCATION_POPUP_START-->
		<?php include("includes/popup.php"); ?>

		<div class="form_popup">
			<div class="inner_popup">
				<div class="form_popup_content">
					<div class="form_popup_heading">Add new address <div class="form_popup_close"><i class="fa fa-times"></i></div>
					</div>
					<div class="form_popup_content_inner">
						<div class="gerenric_form">
							<ul>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">First name</div>
											<div class="form_field"><input type="text" class="gerenric_input"></div>
										</div>
										<div class="form_right">
											<div class="form_label">Last name</div>
											<div class="form_field"><input type="text" class="gerenric_input"></div>
										</div>
									</div>
								</li>
								<li>
									<div class="form_label">Addition</div>
									<div class="form_field"><input type="text" class="gerenric_input"></div>
								</li>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">Street</div>
											<div class="form_field"><input type="text" class="gerenric_input"></div>
										</div>
										<div class="form_right">
											<div class="form_label">house number</div>
											<div class="form_field"><input type="text" class="gerenric_input"></div>
										</div>
									</div>
								</li>
								<li>
									<div class="form_label">ZIP / City</div>
									<div class="form_field">
										<select class="gerenric_input">
											<option value="">Select Country</option>
											<option value="">Pakistan</option>
											<option value="">India</option>
										</select>
									</div>
								</li>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">Telefon</div>
											<div class="form_field"><input type="text" class="gerenric_input"></div>
										</div>
										<div class="form_right">
											<div class="form_label">Land</div>
											<div class="form_field">
												<select class="gerenric_input">
													<option value="">Select Land</option>
													<option value="">Land</option>
													<option value="">Land</option>
												</select>
											</div>
										</div>
									</div>
								</li>
								<li class="mt_30">
									<div class="form_two_button">
										<button class="gerenric_btn">Aktualisieren</button>
										<button class="gerenric_btn gray_btn">Abbrechen</button>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--LOCATION_POPUP_END-->

		<!--HEADER_SECTION_START-->
		<?php include("includes/navigation.php"); ?>
		<!--HEADER_SECTION_END-->

		<!--BREADCRUMB_SECTION_START-->
		<div class="gerenric_breadcrumb">
			<div class="page_width_1480">
				<div class="breadcrumb_inner">
					<ul>
						<li><a href="personal_data.php">My personal data</a></li>
						<li><a href="javascript:void(0)">Addresses</a></li>
					</ul>
				</div>
			</div>
		</div>
		<!--BREADCRUMB_SECTION_END-->

		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="my_address_page gerenric_padding">
				<div class="page_width_1480">
					<h1>My Addresses</h1>
					<div class="my_address_section1">
						<div class="gerenric_address">
							<div class="address_col">
								<div class="gerenric_add_box form_popup_trigger">
									<div>
										<div class="add_icon"><i class="fa fa-plus"></i></div>
										<div class="add_text">Add new address</div>
									</div>
								</div>
							</div>
							<div class="address_col">
								<div class="address_card">
									<div class="address_detail">
										<h2>Standard address</h2>
										<ul>
											<li><span>sayed Kamal</span></li>
											<li>67112</li>
											<li>blockfield, 12</li>
											<li>67112 Mutterstadt</li>
											<li>Additional information: <span>wacker</span></li>
											<li>Germany</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="address_col">
								<div class="address_card">
									<div class="address_detail">
										<h2>Standard address</h2>
										<ul>
											<li><span>sayed Kamal</span></li>
											<li>67112</li>
											<li>blockfield, 12</li>
											<li>67112 Mutterstadt</li>
											<li>Additional information: <span>wacker</span></li>
											<li>Germany</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="my_address_section2">
						<div class="gerenric_address full_column">
							<div class="address_col">
								<div class="address_card">
									<div class="address_detail">
										<h2>billing address</h2>
										<ul>
											<li><span>sayed Kamal</span></li>
											<li>67112</li>
											<li>blockfield, 12</li>
											<li>67112 Mutterstadt</li>
											<li>Additional information: <span>wacker</span></li>
											<li>Germany</li>
										</ul>
									</div>
									<div class="address_remove"><a href="javascript:void(0)">
											<div class="gerenric_btn">Remove</div>
										</a></div>
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
<script>
	$(window).load(function() {
		$(".form_popup_trigger").click(function() {
			$('.form_popup').show();
			$('.form_popup').resize();
			$('body').css({
				'overflow': 'hidden'
			});
		});
		$('.form_popup_close').click(function() {
			$('.form_popup').hide();
			$('body').css({
				'overflow': 'inherit'
			});
		});
	});
</script>
<?php include("includes/bottom_js.php"); ?>

</html>