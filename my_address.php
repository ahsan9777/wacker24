<?php
include("includes/php_includes_top.php");
$page = 1;
if(isset($_REQUEST['btnAdd'])){

	$usa_id = getMaximum("user_shipping_address", "usa_id");
	mysqli_query($GLOBALS['conn'], "INSERT INTO user_shipping_address (usa_id, user_id, usa_fname, usa_lname, usa_address, usa_street, usa_house_no, usa_zipcode, usa_contactno, countries_id) VALUES ('".$usa_id."', '".$_SESSION["UID"]."', '".dbStr(trim($_REQUEST['usa_fname']))."',  '".dbStr(trim($_REQUEST['usa_lname']))."', '".dbStr(trim($_REQUEST['usa_address']))."', '".dbStr(trim($_REQUEST['usa_street']))."', '".dbStr(trim($_REQUEST['usa_house_no']))."', '".dbStr(trim($_REQUEST['usa_zipcode']))."', '".dbStr(trim($_REQUEST['usa_contactno']))."', '".dbStr(trim($_REQUEST['countries_id']))."')") or die(mysqli_error($GLOBALS['conn']));
	header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
}

include("includes/message.php");
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
						<form class="gerenric_form" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
							<ul>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">First name</div>
											<div class="form_field"><input type="text" name="usa_fname" id="usa_fname" class="gerenric_input" required ></div>
										</div>
										<div class="form_right">
											<div class="form_label">Last name</div>
											<div class="form_field"><input type="text" name="usa_lname" id="usa_lname" class="gerenric_input"></div>
										</div>
									</div>
								</li>
								<li>
									<div class="form_label">Addition</div>
									<div class="form_field"><input type="text" name="usa_address" id="usa_address" class="gerenric_input"></div>
								</li>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">Street</div>
											<div class="form_field"><input type="text" name="usa_street" id="usa_street" class="gerenric_input" required ></div>
										</div>
										<div class="form_right">
											<div class="form_label">House number</div>
											<div class="form_field"><input type="text" name="usa_house_no" id="usa_house_no" class="gerenric_input" required ></div>
										</div>
									</div>
								</li>
								<li>
									<div class="form_label">ZIP / City</div>
									<div class="form_field">
										<input type="text" name="usa_zipcode" id="usa_zipcode" class="gerenric_input" required >
										<!--<select class="gerenric_input" name="zc_id" id="zc_id">
											<?php FillSelected2("zip_code", "zc_id", "zc_zipcode", "", "zc_id > 0"); ?>
										</select>-->
									</div>
								</li>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">Telefon</div>
											<div class="form_field"><input type="text" name="usa_contactno" id="usa_contactno" class="gerenric_input" required></div>
										</div>
										<div class="form_right">
											<div class="form_label">Land</div>
											<div class="form_field">
												<select class="gerenric_input" name="countries_id" id="countries_id" >
													<?php FillSelected2("countries", "countries_id", "countries_name", 81, "countries_id > 0"); ?>
												</select>
											</div>
										</div>
									</div>
								</li>
								<li class="mt_30">
									<div class="form_two_button">
										<button class="gerenric_btn" type="submit" name="btnAdd">Add</button>
										<button class="gerenric_btn gray_btn form_popup_close">Close</button>
									</div>
								</li>
							</ul>
						</form>
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
					<?php if ($class != "") { ?>
						<div class="<?php print($class); ?>"><?php print($strMSG); ?><a href="javascript:void(0);" class="close" data-dismiss="alert">×</a></div>
					<?php } ?>
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
							<?php
							$Query = "SELECT usa.*, c.countries_name FROM user_shipping_address AS usa LEFT OUTER JOIN countries AS c ON c.countries_id = usa.countries_id WHERE user_id = '".$_SESSION["UID"]."' ";
							$rs = mysqli_query($GLOBALS['conn'], $Query);
							if(mysqli_num_rows($rs) > 0){
								while($row = mysqli_fetch_object($rs)){
							?>
							<div class="address_col">
								<div class="address_card">
									<div class="address_detail">
										<h2>Standard address</h2>
										<ul>
											<li><span> <?php print($row->usa_fname." ".$row->usa_lname); ?> </span></li>
											<li> <?php print($row->usa_street); ?> </li>
											<li> <?php print($row->usa_house_no); ?> </li>
											<li> <?php print($row->usa_contactno); ?> </li>
											<li><?php print($row->usa_zipcode); ?></li>
											<li><?php print($row->countries_name); ?></li>
											<li><?php print($row->usa_address); ?></li>
										</ul>
									</div>
								</div>
							</div>
							<?php
								}
							}
							?>
						</div>
					</div>
					<div class="my_address_section2">
						<div class="gerenric_address full_column">
							<?php
							$Query = "SELECT u.*, c.countries_name FROM users AS u LEFT OUTER JOIN countries AS c ON c.countries_id = u.countries_id WHERE u.user_id = '".$_SESSION["UID"]."'";
							$rs = mysqli_query($GLOBALS['conn'], $Query);
							if(mysqli_num_rows($rs) > 0){
								$row = mysqli_fetch_object($rs);
							?>
							<div class="address_col">
								<div class="address_card">
									<div class="address_detail">
										<h2>Billing address</h2>
										<ul>
										<li><span> <?php print($row->user_fname." ".$row->user_lname); ?> </span></li>
											<li> <?php print($row->user_phone); ?> </li>
											<li> <?php print($row->user_name); ?> </li>
											<li> <?php print($row->countries_name); ?> </li>
										</ul>
									</div>
									<div class="address_remove"><a href="javascript:void(0)">
											<div class="gerenric_btn">Remove</div>
										</a></div>
								</div>
							</div>
							<?php
							}
							?>
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