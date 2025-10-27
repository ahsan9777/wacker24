<?php
include("includes/php_includes_top.php");
//$page = 1;
$page_bottom_js = 0;
$firstLetter_check = "";
$manufacture_title = array("0 - 9", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "Ä Ö Ü");
for ($i = 0; $i < count($manufacture_title); $i++) {
	//print($manufacture_title[$i]."<br>");
	$whereclause = "";
	if ($i == 0) {
		$whereclause = " AND manf_name BETWEEN '0' AND '9'";
	} elseif (($i + 1) == count($manufacture_title)) {
		$specialChars = explode(" ", $manufacture_title[$i]);
		$whereclauseParts = [];

		foreach ($specialChars as $char) {
			$upper = $char;
			$lower = mb_strtolower($char, 'UTF-8'); // handle special chars safely
			$whereclauseParts[] = "( BINARY manf_name LIKE '{$upper}%' OR BINARY manf_name LIKE '{$lower}%')";
		}

		$whereclause = ' AND (' . implode(' OR ', $whereclauseParts) . ')';
	} else {
		$whereclause = " AND (manf_name LIKE '" . $manufacture_title[$i] . "%' OR manf_name LIKE '" . strtolower($manufacture_title[$i]) . "%')";
	}
	$Query = "SELECT * FROM manufacture WHERE manf_name != '' AND manf_status = '1' " . $whereclause . " ORDER BY manf_name ASC";
	//print($Query . "<br>");
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) > 0) {
		while ($row = mysqli_fetch_object($rs)) {
			$manf_name = $row->manf_name;
			$manf_name_params = $row->manf_name_params;
			$manufacture_data[$manufacture_title[$i]][] = array(
				"manf_title" => $manf_name,
				"manf_name_params" => $GLOBALS['siteURL']."marken/".$manf_name_params
			);
		}
	} else {
		//echo $manufacture_title[$i];
		$key = array_search($manufacture_title[$i], $manufacture_title);
		if ($key !== false) {
			unset($manufacture_title[$key]);
			$manufacture_title = array_values($manufacture_title);
		}
		$i = $i - 1;
	}
}
/*print("<pre>");
print_r($manufacture_data);
print("</pre>");/*
foreach ($manufacture_data['0 - 9'] as $item) {
	echo $item['manf_title'] . "\n";
}*/
?>
<!doctype html>
<html lang="de">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width; initial-scale=1.0">
	<?php include("includes/html_header.php"); ?>
</head>
<script>
	$(function() {
		$(".category_type_product .ctg_type_col").slice(0, 5).show();
		$("body").on('click touchstart', '.load-more-button', function(e) {
			e.preventDefault();
			$(".category_type_product .ctg_type_col:hidden").slice(0, 5).slideDown();
			if ($(".category_type_product .ctg_type_col:hidden").length == 0) {
				$(".load-more-button").css('visibility', 'hidden');
			}
			$('html,body').animate({
				scrollTop: $(this).offset().top
			}, 1000);
		});
	});
</script>

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
			<div class="marken-page">
				<div class="page_width_1480">
					<div class="marken-heading">Marken A-Z</div>
					<div class="marken-logo"><img src="<?php print(config_site_logo) ?>" alt=""></div>
					<div class="marken-nav">
						<?php for ($i = 0; $i < count($manufacture_title); $i++) { ?>
							<div class="marken-nav-link"><a href="#m_<?php print($i); ?>"><?php print($manufacture_title[$i]); ?></a>
								<div class="marken-listing-content">
									<div class="marken-listing-block">
										<div class="marken-letter-head"><?php print($manufacture_title[$i]); ?></div>
										<div class="marken-list-data">
											<ul>
												<?php foreach ($manufacture_data[$manufacture_title[$i]] as $item) { ?>
													<li><a href="<?php print($item['manf_name_params']); ?>"><?php print($item['manf_title']); ?></a></li>
												<?php } ?>
											</ul>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>
					<div class="marken-listing-content">
						<?php for ($i = 0; $i < count($manufacture_title); $i++) { ?>
							<div class="marken-listing-block" id="m_<?php print($i); ?>">
								<div class="marken-letter-head"><?php print($manufacture_title[$i]); ?></div>
								<div class="marken-list-data">
									<ul>
										<?php foreach ($manufacture_data[$manufacture_title[$i]] as $item) { ?>
											<li><a href="<?php print($item['manf_name_params']); ?>"><?php print($item['manf_title']); ?></a></li>
										<?php } ?>
									</ul>
								</div>
							</div>
						<?php } ?>
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
	$(document).ready(function() {

		// ✅ Smooth scroll on click
		$(".marken-nav-link a").on("click", function(e) {
			e.preventDefault();

			var target = $(this).attr("href");
			var navHeight = $(".marken-nav").outerHeight();

			$("html, body").animate({
				scrollTop: $(target).offset().top - navHeight
			}, 600);

			// Active class set on click
			$(".marken-nav-link a").removeClass("active");
			$(this).addClass("active");
		});

		// ✅ Active class update on scroll
		$(window).on("scroll", function() {
			var scrollPos = $(window).scrollTop();
			var navHeight = $(".marken-nav").outerHeight();

			$(".marken-listing-block").each(function() {
				var top = $(this).offset().top - navHeight - 10; // adjust
				var bottom = top + $(this).outerHeight();

				if (scrollPos >= top && scrollPos < bottom) {
					var id = $(this).attr("id");
					$(".marken-nav-link a").removeClass("active");
					$('.marken-nav-link a[href="#' + id + '"]').addClass("active");
				}
			});
		});

	});
</script>
<?php include("includes/bottom_js.php"); ?>

</html>