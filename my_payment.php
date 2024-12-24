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
					<div class="form_popup_heading">Add payment card <div class="form_popup_close"><i class="fa fa-times"></i></div></div>
					<div class="form_popup_content_inner">
						<div class="gerenric_form">
							<ul>
								<li>
									<div class="form_label">Selection</div>
									<div class="form_field">
										<select class="gerenric_input">
											<option value="">Vise</option>
											<option value="">Mastercard</option>
										</select>
									</div>
								</li>
								<li>
									<div class="form_label">Account holder name</div>
									<div class="form_field"><input type="text" class="gerenric_input"></div>
								</li>
								<li>
									<div class="form_label">card number</div>
									<div class="form_field"><input type="number" class="gerenric_input"></div>
								</li>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_field"><input type="text" class="gerenric_input" placeholder="12"></div>
										</div>
										<div class="form_right">
											<div class="form_field"><input type="text" class="gerenric_input" placeholder="2030"></div>
										</div>
									</div>
								</li>
								<li>
									<div class="form_field"><input type="text" class="gerenric_input" placeholder="147"></div>
								</li>
								<li class="mt_30">
									<div class="form_two_button">
										<button class="gerenric_btn">Update</button>
										<button class="gerenric_btn gray_btn">Cancel</button>
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
						<li><a href="javascript:void(0)">payments</a></li>
					</ul>
				</div>
			</div>
		</div>
		<!--BREADCRUMB_SECTION_END-->
		
		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="payment_page gerenric_padding">
				<div class="page_width_1480">
					<h1>Payments</h1>
					<div class="gerenric_address">
						<div class="address_col">
							<div class="gerenric_add_box form_popup_trigger">
								<div>
									<div class="add_icon"><i class="fa fa-plus"></i></div>
									<div class="add_text">Add new payment methods</div>
								</div>
							</div>
						</div>
						<div class="address_col">
							<div class="address_card">
								<div class="address_detail">
									<h2>Payment Detail</h2>
									<ul>
										<li><span>Vise</span></li>
										<li>Sayed Kamal Hussaini</li>
										<li>3434 2343 3243 3243</li>
										<li>12</li>
										<li>2030</li>
										<li>147</li>
									</ul>
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
	$(window).load(function () {
		$(".form_popup_trigger").click(function(){ $('.form_popup').show(); $('.form_popup').resize(); $('body').css({'overflow':'hidden'});});
		$('.form_popup_close').click(function(){ $('.form_popup').hide(); $('body').css({'overflow':'inherit'}); });
	});
</script>
<?php include("includes/bottom_js.php"); ?>
</html>
