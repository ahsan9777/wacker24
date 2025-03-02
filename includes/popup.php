<div class="location_popup">
	<div class="inner_popup">
		<form class="location_content" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
			<div class="location_heading">Choose your location <div class="location_close"><i
						class="fa fa-times"></i></div>
			</div>
			<div class="location_content_inner" >
				<p>Delivery options and delivery speeds may vary for different locations</p>
				<div class="popup_btn"><a href="login.php">
						<div class="gerenric_btn full_btn">Sign in to see your addresses</div>
					</a></div>
				<div class="or_text">
					<div class="or_text_inner">or enter a postal code in Germany</div>
				</div>
				<div class="popup_apply_div">
					<input type="text" class="input_apply" name="plz" maxlength="5" minlength="5" required>
					<!--<input type="button" class="gerenric_btn" name="btn_plz" value="Apply">-->
				</div>
				<div class="or_text">
					<div class="or_text_inner">or deliver outside Germany</div>
				</div>
				<div class="popup_select_box">
					<select class="select_input">
						<option value="">Germany</option>
					</select>
				</div>
				<div class="popup_btn_done">
					<input class="gerenric_btn" type="submit" name="btn_plz" value="Done">
				</div>
			</div>
		</form>
	</div>
</div>