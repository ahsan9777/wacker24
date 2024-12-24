<?php
include("includes/php_includes_top.php");
$page = 1;

if(isset($_REQUEST['btnAdd'])){
	//print_r($_REQUEST);die();
	$sl_id = getMaximum("shopping_list", "sl_id");
	mysqli_query($GLOBALS['conn'], "INSERT INTO shopping_list (sl_id, user_id, sl_title) VALUES (" . $sl_id . ", '".$_SESSION["UID"]."','" . dbStr(trim($_REQUEST['sl_title'])) . "')") or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
} elseif (isset($_REQUEST['btnUpdate'])) {
	
	mysqli_query($GLOBALS['conn'], "UPDATE shopping_list SET sl_title='" . dbStr(trim($_REQUEST['sl_title'])) . "' WHERE sl_id=" . $_REQUEST['sl_id']);
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
} elseif (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == 2) {
		$rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM shopping_list WHERE sl_id = " . $_REQUEST['sl_id']);
		if (mysqli_num_rows($rsM) > 0) {
			$rsMem = mysqli_fetch_object($rsM);
			$sl_title = $rsMem->sl_title;
			$formHead = "Update Info";
		}
	} else {
		$sl_title = "";
		$formHead = "Add New";
	}
} else {
	$sl_title = "";
	$formHead = "Add New";
}

if (isset($_REQUEST['btnDelete'])) {
	mysqli_query($GLOBALS['conn'], "DELETE FROM shopping_list WHERE sl_id = " . $_REQUEST['sl_id']) or die(mysqli_error($_REQUEST['conn']));
	header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=3");
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
						<li><a href="javascript:void(0)">My shopping lists</a></li>
					</ul>
				</div>
			</div>
		</div>
		<!--BREADCRUMB_SECTION_END-->

		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="shopping_list_page gerenric_padding">
				<div class="page_width_1480">
					<div class="shopping_list_section1">
						<div class="gerenric_white_box">
							<div class="shopping_list_section_inner">
								<?php if ($class != "") { ?>
									<div class="<?php print($class); ?>"><?php print($strMSG); ?><a href="javascript:void(0);" class="close" data-dismiss="alert">×</a></div>
								<?php } ?>
								<h3> <?php print($formHead); ?> shopping lists</h3>
								<form class="gerenric_form" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
									<ul>
										<li><input type="text" class="gerenric_input" name="sl_title" id="sl_title" required value="<?php print($sl_title); ?>" placeholder="Add new shopping lists"></li>
										<li>
											<?php if (isset($_REQUEST['action']) && $_REQUEST['action'] == 2) { ?>
												<button class="gerenric_btn" type="submit" name="btnUpdate">Update</button>
											<?php } else{?>
												<button class="gerenric_btn" type="submit" name="btnAdd">Add</button>
											<?php } ?>
										</li>
									</ul>
								</form>
							</div>
						</div>
					</div>
					<div class="shopping_list_section2">
						<div class="gerenric_white_box">
							<h3>Shopping Lists</h3>
							<div class="gerenric_table column_3">
								<ul>
									<li>No</li>
									<li>Shopping List</li>
									<li>Action</li>
								</ul>
								<?php
								$count = 0;
								$Query = "SELECT * FROM shopping_list WHERE user_id = '".$_SESSION["UID"]."' ORDER BY sl_id ASC";
								$rs = mysqli_query($GLOBALS['conn'], $Query);
								if(mysqli_num_rows($rs) > 0){
									while($row = mysqli_fetch_object($rs)){
										$count++;
								?>
								<ul>
									<li> <?php print($count); ?> </li>
									<li> <?php print($row->sl_title); ?> </li>
									<li>
										<div class="table_action">
											<a href="<?php print($_SERVER['PHP_SELF'] . "?action=2&sl_id=" . $row->sl_id); ?>" ><i class="fa fa-edit"></i></a>
											<a href="<?php print($_SERVER['PHP_SELF'] . "?btnDelete&sl_id=" . $row->sl_id); ?>" onclick="return confirm('Are you sure you want to delete selected item(s)?');" ><i class="fa fa-trash"></i></a>
										</div>
									</li>
								</ul>
								<?php
									}
								} else{
									print('<div colspan="100%" align="center" style = "padding-top: 15px" >No record found!</div>');
								}
								?>
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
