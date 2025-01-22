<?php
include("../lib/session_head.php");

if (isset($_REQUEST['btnUpdate'])) {

    $dirName = "../files/";
    $mfileName = $_REQUEST['mfileName'];
    if (!empty($_FILES["mFile"]["name"])) {
        $mfileName = str_replace(" ", "_", strtolower($_FILES["mFile"]["name"]));
        move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName);
    }
    mysqli_query($GLOBALS['conn'], "UPDATE site_config SET 
    config_sitename = '" . dbStr(trim($_REQUEST['config_sitename'])) . "', 
    config_sitetitle = '" . dbStr(trim($_REQUEST['config_sitetitle'])) . "', 
    config_metakey = '" . dbStr(trim($_REQUEST['config_metakey'])) . "', 
    config_metades = '" . dbStr(trim($_REQUEST['config_metades'])) . "', 
    config_email = '" . dbStr(trim($_REQUEST['config_email'])) . "', 
    config_phone = '" . dbStr(trim($_REQUEST['config_phone'])) . "', 
    config_payment_url = '" . dbStr(trim($_REQUEST['config_payment_url'])) . "', 
    config_authorization_bearer = '" . dbStr(trim($_REQUEST['config_authorization_bearer'])) . "', 
    config_gst = '" . dbStr(trim($_REQUEST['config_gst'])) . "', 
    config_condition_courier_amount = '" . dbStr(trim($_REQUEST['config_condition_courier_amount'])) . "', 
    config_courier_fix_charges = '" . dbStr(trim($_REQUEST['config_courier_fix_charges'])) . "', 
    config_ftp_img = '" . dbStr(trim($_REQUEST['config_ftp_img'])) . "', 
    config_appointment_regular_opening = '" . dbStr(trim($_REQUEST['config_appointment_regular_opening'])) . "', 
    config_appointment_regular_closing = '" . dbStr(trim($_REQUEST['config_appointment_regular_closing'])) . "', 
    config_appointment_saturday_opening = '" . dbStr(trim($_REQUEST['config_appointment_saturday_opening'])) . "', 
    config_appointment_saturday_closing = '" . dbStr(trim($_REQUEST['config_appointment_saturday_closing'])) . "', 
    config_appointment_heading_de = '" . dbStr(trim($_REQUEST['config_appointment_heading_de'])) . "', 
    config_appointment_heading_en = '" . dbStr(trim($_REQUEST['config_appointment_heading_en'])) . "', 
    config_appointment_detail_de = '" . dbStr(trim($_REQUEST['config_appointment_detail_de'])) . "', 
    config_appointment_detail_en = '" . dbStr(trim($_REQUEST['config_appointment_detail_en'])) . "', 
    config_site_logo = '" . $mfileName . "'") 
or die(mysqli_error($GLOBALS['conn']));

    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
}


$rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM site_config");
if (mysqli_num_rows($rsM) > 0) {
    $rsMem = mysqli_fetch_object($rsM);
    $config_id = $rsMem->config_id;
    $config_sitename = $rsMem->config_sitename;
    $config_sitetitle = $rsMem->config_sitetitle;
    $config_metakey = $rsMem->config_metakey;
    $config_metades = $rsMem->config_metades;
    $config_email = $rsMem->config_email;
    $config_upload_limit = $rsMem->config_upload_limit;
    $status_id = $rsMem->status_id;
    $config_phone = $rsMem->config_phone;
    $config_mobile = $rsMem->config_mobile;
    $config_fax = $rsMem->config_fax;
    $config_payment_url = $rsMem->config_payment_url;
    $config_authorization_bearer = $rsMem->config_authorization_bearer;
    $config_gst = $rsMem->config_gst;
    $config_condition_courier_amount = $rsMem->config_condition_courier_amount;
    $config_courier_fix_charges = $rsMem->config_courier_fix_charges;
    $config_ftp_img = $rsMem->config_ftp_img;
    $config_appointment_regular_opening = $rsMem->config_appointment_regular_opening;
    $config_appointment_regular_closing = $rsMem->config_appointment_regular_closing;
    $config_appointment_saturday_opening = $rsMem->config_appointment_saturday_opening;
    $config_appointment_saturday_closing = $rsMem->config_appointment_saturday_closing;
    $config_appointment_heading_de = $rsMem->config_appointment_heading_de;
    $config_appointment_heading_en = $rsMem->config_appointment_heading_en;
    $config_appointment_detail_de = $rsMem->config_appointment_detail_de;
    $config_appointment_detail_en = $rsMem->config_appointment_detail_en;
    $mfileName = $rsMem->config_site_logo;
    $mfile_path = !empty($rsMem->config_site_logo) ? $GLOBALS['siteURL'] . "files/" . $rsMem->config_site_logo : "";
    $formHead = "Update Info";
}



include("includes/messages.php");

?>
<!DOCTYPE html>
<html lang="de">

<head>
    <?php include("includes/html_header.php"); ?>
</head>

<body>
    <div class="container_main">
        <!-- Sidebar -->
        <?php include("includes/sidebar.php"); ?>

        <!-- Main content -->
        <div class="main-content">
            <!-- Top bar -->
            <?php include("includes/topbar.php"); ?>

            <!-- Content -->
            <section class="content" id="main-content">
                <?php if ($class != "") { ?>
                    <div class="<?php print($class); ?>"><?php print($strMSG); ?><a class="close" data-dismiss="alert">×</a></div>
                <?php } ?>
                <div class="main_container">
                    <h2 class="text-white">
                        <?php print($formHead); ?> Site Setting
                    </h2>
                    <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12 col-12 mt-3">
                                <img src="<?php print($mfile_path); ?>" width="50%" style="border-radius: 10px;" alt="">
                            </div>
                            <div class="col-md-6 col-12 mt-3">
                                <label for="config_sitename">Site Name</label>
                                <input type="text" class="input_style" name="config_sitename" id="config_sitename" value="<?php print($config_sitename); ?>" placeholder="Site Name">
                            </div>

                            <div class="col-md-6 col-12 mt-3">
                                <label for="config_sitetitle">Site Title</label>
                                <input type="text" class="input_style" name="config_sitetitle" id="config_sitetitle" value="<?php print($config_sitetitle); ?>" placeholder="Site Title">
                            </div>
                            <div class="col-md-12 col-12 mt-3">
                                <label for="config_metakey">Meta Keyword (Seprate Each Keyword With ',' (Car, Bus, Bike))</label>
                                <textarea rows="6" type="text" class="input_style" name="config_metakey" id="config_metakey" placeholder="Meta Key"> <?php print($config_metakey); ?> </textarea>
                            </div>
                            <div class="col-md-12 col-12 mt-3">
                                <label for="config_metades">Meta Description (Seprate Each Description With ',' (Car, Bus, Bike))</label>
                                <textarea rows="6" type="text" class="input_style" name="config_metades" id="config_metades" placeholder="Meta Description"> <?php print($config_metades); ?> </textarea>
                            </div>
                            <div class="col-md-6 col-12 mt-3">
                                <label for="config_email">Email</label>
                                <input type="email" class="input_style" name="config_email" id="config_email" value="<?php print($config_email); ?>" placeholder="Email">
                            </div>
                            <div class="col-md-6 col-12 mt-3">
                                <label for="config_phone">Phone</label>
                                <input type="text" class="input_style" name="config_phone" id="config_phone" value="<?php print($config_phone); ?>" placeholder="Phone">
                            </div>
                            <div class="col-md-6 col-12 mt-3">
                                <label for="config_payment_url">Payment URL</label>
                                <input type="text" class="input_style" name="config_payment_url" id="config_payment_url" value="<?php print($config_payment_url); ?>" placeholder="Payment URL">
                            </div>
                            <div class="col-md-6 col-12 mt-3">
                                <label for="config_gst">VAT (%)</label>
                                <input type="text" class="input_style" name="config_gst" id="config_gst" value="<?php print($config_gst); ?>" placeholder="GST">
                            </div>

                            <div class="col-md-6 col-12 mt-3">
                                <label for="config_condition_courier_amount">Courier Condition Value</label>
                                <input type="number" class="input_style" name="config_condition_courier_amount" id="config_condition_courier_amount" value="<?php print($config_condition_courier_amount); ?>" placeholder="Courier Condition Value">
                            </div>

                            <div class="col-md-6 col-12 mt-3">
                                <label for="config_courier_fix_charges">Courier Fixed Charges</label>
                                <input type="number" class="input_style" name="config_courier_fix_charges" id="config_courier_fix_charges" value="<?php print($config_courier_fix_charges); ?>" placeholder="Courier Fixed Charges">
                            </div>
                            <div class="col-md-12 col-12 mt-3">
                                <label for="config_authorization_bearer">Authorization Bearer</label>
                                <input type="text" class="input_style" name="config_authorization_bearer" id="config_authorization_bearer" value="<?php print($config_authorization_bearer); ?>" placeholder="Authorization Bearer">
                            </div>
                            <div class="col-md-12 col-12 mt-3">
                                <label for="config_ftp_img">FTP Image URL</label>
                                <input type="text" class="input_style" name="config_ftp_img" id="config_ftp_img" value="<?php print($config_ftp_img); ?>" placeholder="FTP Image">
                            </div>
                            <div class="col-md-12 col-12 mt-3 border-bottom">
                                <h2 class="text-start text-white">Appointment</h2>
                            </div>
                            <div class="col-md-3 col-12 mt-3">
                                <label for="config_ftp_img">Regular opening hours</label>
                                <input type="time" class="input_style" name="config_appointment_regular_opening" id="config_appointment_regular_opening" value="<?php print($config_appointment_regular_opening); ?>">
                            </div>
                            <div class="col-md-3 col-12 mt-3">
                                <label for="config_ftp_img">Regular closing hours</label>
                                <input type="time" class="input_style" name="config_appointment_regular_closing" id="config_appointment_regular_closing" value="<?php print($config_appointment_regular_closing); ?>">
                            </div>
                            <div class="col-md-3 col-12 mt-3">
                                <label for="config_ftp_img">Saturday opening hours</label>
                                <input type="time" class="input_style" name="config_appointment_saturday_opening" id="config_appointment_saturday_opening" value="<?php print($config_appointment_saturday_opening); ?>">
                            </div>
                            <div class="col-md-3 col-12 mt-3">
                                <label for="config_ftp_img">Saturday closing hours</label>
                                <input type="time" class="input_style" name="config_appointment_saturday_closing" id="config_appointment_saturday_closing" value="<?php print($config_appointment_saturday_closing); ?>">
                            </div>
                            <div class="col-md-6 col-12 mt-3">
                                <label for="config_ftp_img">Heading DE</label>
                                <input type="text" class="input_style" name="config_appointment_heading_de" id="config_appointment_heading_de" value="<?php print($config_appointment_heading_de); ?>" placeholder="Heading DE">
                            </div>
                            <div class="col-md-6 col-12 mt-3">
                                <label for="config_ftp_img">Heading EN</label>
                                <input type="text" class="input_style" name="config_appointment_heading_en" id="config_appointment_heading_en" value="<?php print($config_appointment_heading_en); ?>" placeholder="Heading EN">
                            </div>
                            <div class="col-md-6 col-12 mt-3">
                                <label for="config_ftp_img">Detail DE</label>
                                <textarea rows="6" class="input_style" name="config_appointment_detail_de" id="config_appointment_detail_de" placeholder="Detail DE"><?php print($config_appointment_detail_de); ?></textarea>
                            </div>
                            <div class="col-md-6 col-12 mt-3">
                                <label for="config_ftp_img">Detail EN</label>
                                <textarea rows="6" class="input_style" name="config_appointment_detail_en" id="config_appointment_detail_en" placeholder="Detail EN"><?php print($config_appointment_detail_en); ?></textarea>
                            </div>
                            <div class="col-md-12 col-12 mt-3">
                                <label for="">Logo</label>
                                <div class="">
                                    <label for="file-upload" class="upload-btn">
                                        <span class="material-icons">cloud_upload</span>
                                        <span>Upload Files</span>
                                    </label>
                                    <input id="file-upload" type="file" class="file-input" name="mFile">
                                </div>
                            </div>
                            <div class="col-md-12 col-12 mt-3">
                                <button class="btn btn-primary" type="submit" name="btnUpdate">Upload</button>
                                <input type="hidden" name="mfileName" value="<?php print($mfileName); ?>" />
                                <button type="button" name="btnBack" class="btn btn-light" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>';">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
    <?php include("includes/bottom_js.php"); ?>
</body>
<script>
    $('input.brand_name').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=brand_name',
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data) {
                    response(data);

                }
            });
        },
        minLength: 1,
        select: function(event, ui) {
            var brand_id = $("#brand_id");
            var brand_name = $("#brand_name");
            $(brand_id).val(ui.item.brand_id);
            $(brand_name).val(ui.item.value);
            //frmCat.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });
</script>

</html>