<?php include("includes/php_includes_top.php"); ?>
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
		
		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="contact_page gerenric_padding">
				<div class="page_width">
					<div class="contact_inner">
						<div class="contact_col">
							<div class="contact_map">
								<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d10397.416599793687!2d8.1595303!3d49.3454452!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xf891f6d9c1b2e882!2sWacker%20Office%20Center%20GmbH!5e0!3m2!1sen!2s!4v1654254885248!5m2!1sen!2s" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
								<div class="map_link"><a href="https://www.google.com/maps/place/Wacker+Office+Center+GmbH/@49.3454452,8.1595303,15z/data=!4m5!3m4!1s0x0:0xf891f6d9c1b2e882!8m2!3d49.3454539!4d8.1594747">Wacker Bürocenter GmbH, Chemnitzer Str. 1, 67433 Neustadt/Weinstraße</a></div>
							</div>
						</div>
						<div class="contact_col">
							<h2>Contact Form</h2>
							<div class="gerenric_form">
								<ul>
									<li>
										<div class="form_row">
											<div class="form_left">
												<div class="form_label">Name</div>
												<div class="form_field"><input type="text" class="gerenric_input" placeholder="Your First Name"></div>
											</div>
											<div class="form_right">
												<div class="form_label">E-mail address</div>
												<div class="form_field"><input type="text" class="gerenric_input" placeholder="Your Email Address"></div>
											</div>
										</div>
									</li>
									<li>
										<div class="form_row">
											<div class="form_left">
												<div class="form_label">phone number</div>
												<div class="form_field"><input type="number" class="gerenric_input" placeholder="Your Phone Number"></div>
											</div>
											<div class="form_right">
												<div class="form_label">Theme</div>
												<div class="form_field">
													<select class="gerenric_input">
														<option>Please select</option>
														<option>Order</option>
														<option>Questions about delivery time/product</option>
														<option>Returns/Returns</option>
														<option>My user account</option>
														<option>Request personal data</option>
														<option>Request deletion of personal data</option>
														<option>technical malfunction</option>
														<option>Miscellaneous</option>
													  </select>
												</div>
											</div>
										</div>
									</li>
									<li>
										<div class="form_label">Your Message</div>
										<div class="form_field"><textarea class="gerenric_input gerenric_textarea" placeholder=""></textarea></div>
									</li>
									<li>
										<div class="form_label">Please enter confirmation code: 1983</div>
										<div class="form_field"><input type="text" class="gerenric_input"></div>
									</li>
									<li><button class="gerenric_btn full_btn mt_30">Submit</button></li>
								</ul>
							</div>
						</div>
						<div class="contact_col">
							<div class="contact_block">
								<h3>opening hours of the specialist store</h3>
								<p>Mon.-Fri.: 09:00 - 18:00</p>
								<p>Sat.: 09:00 - 13:00</p>
							</div>
							<div class="contact_block">
								<h3>Reachable by phone</h3>
								<p>Mon.-Fri.: 08:00 - 18:00</p>
								<p>Sat.: 09:00 - 13:00</p>
							</div>
						</div>
						<div class="contact_col">
							<div class="contact_block">
								<h3>Customer Service</h3>
								<p>We will help you personally and are available during our service hours:</p>
							</div>
							<div class="contact_block">
								<h3>Phone</h3>
								<p>06321 - 9124 -</p>
							</div>
							<div class="contact_table">
								<ul>
									<li>Department</li>
									<li>E-mail</li>
									<li>phone</li>
								</ul>
								<ul>
									<li>headquarters</li>
									<li>mail@wacker24.de</li>
									<li>-0</li>
								</ul>
								<ul>
									<li>accounting</li>
									<li>buchhaltung@wacker24.de</li>
									<li>-10</li>
								</ul>
								<ul>
									<li>Technology Printer/Scanner</li>
									<li>technik@wacker24.de</li>
									<li>-22</li>
								</ul>
								<ul>
									<li>headquarters</li>
									<li>mail@wacker24.de</li>
									<li>-0</li>
								</ul>
								<ul>
									<li>accounting</li>
									<li>buchhaltung@wacker24.de</li>
									<li>-10</li>
								</ul>
								<ul>
									<li>Technology Printer/Scanner</li>
									<li>technik@wacker24.de</li>
									<li>-22</li>
								</ul>
								<ul>
									<li>headquarters</li>
									<li>mail@wacker24.de</li>
									<li>-0</li>
								</ul>
								<ul>
									<li>accounting</li>
									<li>buchhaltung@wacker24.de</li>
									<li>-10</li>
								</ul>
								<ul>
									<li>Technology Printer/Scanner</li>
									<li>technik@wacker24.de</li>
									<li>-22</li>
								</ul>
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
</html>
