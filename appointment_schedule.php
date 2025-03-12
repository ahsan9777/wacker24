<?php
include("includes/php_includes_top.php");
if (isset($_REQUEST['as_id']) && $_REQUEST['as_id'] > 0) {
	$qryStrURL = "as_id=" . $_REQUEST['as_id'] . "&";
}
$Query = "SELECT asch.as_id, asch.as_duration, asch.as_delay, asch.as_title_de AS as_title, asch.as_detail_de AS as_detail, asch.as_image FROM appointment_schedule AS asch WHERE asch.as_status = '1' AND asch.as_id = '" . $_REQUEST['as_id'] . "'";
$rs = mysqli_query($GLOBALS['conn'], $Query);
if (mysqli_num_rows($rs) > 0) {
	$row = mysqli_fetch_object($rs);
	$as_id = $row->as_id;
	$as_duration = $row->as_duration;
	$as_delay = $row->as_delay;
	$as_title = $row->as_title;
	$as_image = $GLOBALS['siteURL'] . "files/appointment_schedule/" . $row->as_image;
}

if (isset($_REQUEST['btn_appointmentBook'])) {
	if ($_REQUEST['confirm_code'] != $_REQUEST['reconfirm_code']) {
		header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=13");
	} else {
		$app_id = getMaximum("appointments", "app_id");
		mysqli_query($GLOBALS['conn'], "INSERT INTO appointments (app_id, as_id, app_time, app_date, app_gender, app_fname, app_lname, app_street, app_zipcode, app_place, app_contactno, app_email, app_remarks, app_cdate)  VALUES ('" . $app_id . "', '" . dbStr(trim($_REQUEST['as_id'])) . "', '" . dbStr(trim($_REQUEST['app_time'])) . "', '" . dbStr(trim($_REQUEST['app_date'])) . "', '" . dbStr(trim($_REQUEST['app_gender'])) . "', '" . dbStr(trim($_REQUEST['app_fname'])) . "', '" . dbStr(trim($_REQUEST['app_lname'])) . "', '" . dbStr(trim($_REQUEST['app_street'])) . "', '" . dbStr(trim($_REQUEST['app_zipcode'])) . "', '" . dbStr(trim($_REQUEST['app_place'])) . "', '" . dbStr(trim($_REQUEST['app_contactno'])) . "', '" . dbStr(trim($_REQUEST['app_email'])) . "', '" . dbStr(trim($_REQUEST['app_remarks'])) . "', '" . date_time . "')") or die(mysqli_error($GLOBALS['conn']));
		header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
	}
}

include("includes/message.php");
?>
<!doctype html>
<html lang="de">

<head>
	<?php include("includes/html_header.php"); ?>
	<style>
		#calendar .ui-datepicker {
			font-family: <?php print(config_fonts); ?>;
		}
	</style>
</head>

<body style="background-color: #fff !important;">
	<div id="container" align="center">

		<!--LOCATION_POPUP_START-->
		<?php include("includes/popup.php"); ?>
		<!--LOCATION_POPUP_END-->

		<!--HEADER_SECTION_START-->
		<?php include("includes/navigation.php"); ?>
		<!--HEADER_SECTION_END-->

		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="appointment_date_booking gerenric_padding">
				<div class="page_width">
					<?php if ($class != "") { ?>
						<div class="<?php print($class); ?>"><?php print($strMSG); ?><a href="javascript:void(0);" class="close" data-dismiss="alert">×</a></div>
					<?php } ?>
					<h2>Tag & Uhrzeit wählen</h2>
					<div class="appointment_section1">
						<div class="appointment_col">
							<div class="appointment_card">
								<div id="calendar"></div>
							</div>
						</div>
						<div class="appointment_col">
							<div class="appointment_card">
								<ul id="appointment_schedule">

								</ul>
								<p>Die Zeitfenster werden angezeigt, nachdem das Termindatum ausgewählt wurde.</p>
							</div>
						</div>
						<div class="appointment_col">
							<div class="appointment_card">
								<div class="appointment_image"><img src="<?php print($as_image); ?>" alt=""></div>
								<p><?php print($as_title); ?></p>
								<div class="full_width txt_align_center">
									<div class="appointment_time"><?php print($as_duration); ?> minutes</div>
								</div>
								<div class="full_width txt_align_center"><a href="appointments.php">
										<div class="gerenric_btn">Ändern</div>
									</a></div>
							</div>
						</div>
					</div>

					<div class="appointment_section2" id="appointment_form" style="display: none;">
						<h2 class="text_center">Ihre Daten</h2>
						<form class="gerenric_form" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
							<ul>
								<li>
									<div class="form_row">
										<div class="form_input_three">
											<div class="form_label">Anrede</div>
											<div class="form_field">
												<select class="gerenric_input" name="app_gender" id="app_gender">
													<option value="1">Herr</option>
													<option value="2">Frau</option>
													<option value="3">Keine</option>
												</select>
											</div>
										</div>
										<div class="form_input_three">
											<div class="form_label">Vorname *</div>
											<div class="form_field"><input type="text" class="gerenric_input" required name="app_fname" id="app_fname"></div>
										</div>
										<div class="form_input_three">
											<div class="form_label">Nachname *</div>
											<div class="form_field"><input type="text" class="gerenric_input" name="app_lname" id="app_lname"></div>
										</div>
									</div>
								</li>
								<li>
									<div class="form_row">
										<div class="form_input_three">
											<div class="form_label">Straße *</div>
											<div class="form_field"><input type="text" class="gerenric_input" required name="app_street" id="app_street"></div>
										</div>
										<div class="form_input_three">
											<div class="form_label">PLZ *</div>
											<div class="form_field"><input type="text" class="gerenric_input" required name="app_zipcode" id="app_zipcode"></div>
										</div>
										<div class="form_input_three">
											<div class="form_label">Ort *</div>
											<div class="form_field"><input type="text" class="gerenric_input" required name="app_place" id="app_place"></div>
										</div>
									</div>
								</li>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">E-Mail *</div>
											<div class="form_field"><input type="email" class="gerenric_input" required name="app_email" id="app_email"></div>
										</div>
										<div class="form_right">
											<div class="form_label">Telefon *</div>
											<div class="form_field"><input type="text" class="gerenric_input" name="app_contactno" id="app_contactno"></div>
										</div>
									</div>
								</li>
								<li>
									<div class="form_label">Bemerkungen *</div>
									<div class="form_field"><textarea type="text" class="gerenric_input gerenric_textarea" name="app_remarks" id="app_remarks"> </textarea></div>
								</li>
								<li>
									<div class="form_row">
										<div class="form_left">
											<div class="form_label">Zeit *</div>
											<div class="form_field"><input type="text" class="gerenric_input" readonly name="app_time" id="app_time"></div>
										</div>
										<div class="form_right">
											<div class="form_label">Datum *</div>
											<div class="form_field"><input type="text" class="gerenric_input" readonly name="app_date" id="app_date"></div>
										</div>
									</div>
								</li>
								<li>
									<div class="form_row">
										<?php
										$digits = 4;
										$confirm_code = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
										?>
										<div class="form_left">
											<div class="form_label">Bitte Bestätigungscode eingeben: <span class="code_text"><?php print($confirm_code); ?></span></div>
											<input type="hidden" name="confirm_code" id="confirm_code" value="<?php print($confirm_code); ?>">
											<div class="form_field"><input type="number" class="gerenric_input" name="reconfirm_code" id="reconfirm_code" maxlength="4" required autocomplete="off" onKeyPress="if(this.value.length==4) return false;"></div>
										</div>
									</div>
								</li>
								<li>
									<div class="full_width txt_align_center"><button class="gerenric_btn" type="submit" name="btn_appointmentBook">Termin Buchen</button></div>
								</li>
							</ul>
						</form>
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
<?php include("includes/bottom_js.php"); ?>
<script>
	$('#calendar').datepicker({
		inline: true,
		firstDay: 1,
		showOtherMonths: true,
		dayNamesMin: ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'],
		monthNames: [
			"Januar", "Februar", "März", "April", "Mai", "Juni",
			"Juli", "August", "September", "Oktober", "November", "Dezember"
		],
		monthNamesShort: [
			"Jan", "Feb", "Mär", "Apr", "Mai", "Jun",
			"Jul", "Aug", "Sep", "Okt", "Nov", "Dez"
		],
		minDate: 0, // Prevent previous dates
		beforeShowDay: function(date) {
			const day = date.getDay();
			if (day === 0) {
				return [false, '', 'Disabled']; // Disable Sundays
			}
			return [true, '', '']; // All other days enabled
		},
		onSelect: function(dateText, inst) {
			let selected_date = $.datepicker.formatDate('yy-mm-dd', new Date(dateText));
			//console.log('Selected Date:', selected_date);
			$("#appointment_form").hide();
			$("#app_date").val(selected_date);
			get_appointment_schedule(selected_date);
		},
	});

	function get_appointment_schedule(selected_date) {
		$.ajax({
			url: 'ajax_calls.php?action=appointment_schedule',
			method: 'POST',
			data: {
				selected_date: selected_date,
				as_id: <?php print($as_id); ?>,
				as_duration: <?php print($as_duration); ?>,
				as_delay: <?php print($as_delay); ?>
			},
			success: function(response) {
				//console.log(response)
				const obj = JSON.parse(response);
				//console.log(obj);
				if (obj.status == 1) {
					$("#appointment_schedule").html(obj.appointment_schedule);
				} else {
					$("#appointment_schedule").empty();
				}
			},
			error: function(xhr, status, error) {
				console.error('AJAX Error:', status, error);
			}
		});
	}

	$(document).ready(function() {
		const currentDate = new Date();
		const formattedDate = $.datepicker.formatDate('yy-mm-dd', currentDate);
		//console.log('Triggering AJAX for current date:', formattedDate);

		$('#calendar').datepicker('setDate', currentDate);
		$("#app_date").val(formattedDate);
		get_appointment_schedule(formattedDate);
	});

	$(".time_slote").on("click", function() {
		//console.log("time_slote");
		let appointment_schedule_time = $("#appointment_schedule_time_" + $(this).attr("data-id")).val();
		$("#app_time").val(appointment_schedule_time);
		$("#appointment_form").show();
	});
</script>

</html>