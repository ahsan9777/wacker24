<?php
include("../lib/session_head.php");

if (isset($_REQUEST['btnAdd'])) {
    //print_r($_REQUEST);die();

    $as_id = getMaximum("appointment_schedule", "as_id");
    $mfileName = "";
    $dirName = "../files/appointment_schedule/";
    if (!empty($_FILES["mFile"]["name"])) {
        $mfileName = $as_id . "_" . $_FILES["mFile"]["name"];
        $mfileName = str_replace(" ", "_", strtolower($mfileName));
        if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName)) {
            createThumbnail2($dirName, $mfileName, $dirName . "th/", "138", "80");
        }
    }
    mysqli_query($GLOBALS['conn'], "INSERT INTO appointment_schedule (as_id, ac_id, as_duration, as_delay, as_remote, as_title_de, as_title_en, as_detail_de, as_detail_en, as_image) VALUES ('" . $as_id . "', '" . dbStr(trim($_REQUEST['ac_id'])) . "',  '" . dbStr(trim($_REQUEST['as_duration'])) . "', '" . dbStr(trim($_REQUEST['as_delay'])) . "', '" . dbStr(trim($_REQUEST['as_remote'])) . "', '" . dbStr(trim($_REQUEST['as_title_de'])) . "', '" . dbStr(trim($_REQUEST['as_title_en'])) . "', '" . dbStr(trim($_REQUEST['as_detail_de'])) . "', '" . dbStr(trim($_REQUEST['as_detail_en'])) . "', '" . dbStr(trim($mfileName)) . "')") or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
} elseif (isset($_REQUEST['btnUpdate'])) {

    $dirName = "../files/appointment_schedule/";
    $mfileName = $_REQUEST['mfileName'];
    if (!empty($_FILES["mFile"]["name"])) {
        @unlink("../files/appointment_schedule/" . $_REQUEST['mfileName']);
        @unlink("../files/appointment_schedule/th/" . $_REQUEST['mfileName']);
        $mfileName = $_REQUEST['as_id'] . "_" . $_FILES["mFile"]["name"];
        $mfileName = str_replace(" ", "_", strtolower($mfileName));
        if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName)) {
            createThumbnail2($dirName, $mfileName, $dirName . "th/", "138", "80");
        }
    }
    mysqli_query($GLOBALS['conn'], "UPDATE appointment_schedule SET  ac_id = '" . dbStr(trim($_REQUEST['ac_id'])) . "',  as_duration = '" . dbStr(trim($_REQUEST['as_duration'])) . "',  as_delay = '" . dbStr(trim($_REQUEST['as_delay'])) . "',  as_remote = '" . dbStr(trim($_REQUEST['as_remote'])) . "', as_title_de = '" . dbStr(trim($_REQUEST['as_title_de'])) . "', as_title_en = '" . dbStr(trim($_REQUEST['as_title_en'])) . "', as_detail_de = '" . dbStr(trim($_REQUEST['as_detail_de'])) . "', as_detail_en = '" . dbStr(trim($_REQUEST['as_detail_en'])) . "', as_image = '" . dbStr(trim($mfileName)) . "' WHERE as_id=" . $_REQUEST['as_id']) or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
} elseif (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 2) {
        $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM appointment_schedule WHERE as_id = " . $_REQUEST['as_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $ac_id = $rsMem->ac_id;
            $as_duration = $rsMem->as_duration;
            $as_delay = $rsMem->as_delay;
            $as_remote = $rsMem->as_remote;
            $as_title_en = $rsMem->as_title_en;
            $as_title_de = $rsMem->as_title_de;
            $as_detail_de = $rsMem->as_detail_de;
            $as_detail_en = $rsMem->as_detail_en;
            $mfileName = $rsMem->as_image;
            $mfile_path = !empty($rsMem->as_image) ? $GLOBALS['siteURL'] . "files/appointment_schedule/" . $rsMem->as_image : "";
            $formHead = "Update Info";
        }
    } else {
        $ac_id = 0;
        $as_duration = 30;
        $as_delay = 0;
        $as_remote = 0;
        $as_title_en = "";
        $as_title_de = "";
        $as_detail_de = "";
        $as_detail_en = "";
        $mfileName = "";
        $mfile_path = "";
        $formHead = "Add New";
    }
}

//--------------Button Active--------------------
if (isset($_REQUEST['btnActive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE appointment_schedule SET as_status='1' WHERE as_id = " . $_REQUEST['chkstatus'][$i]);
        }
        $class = "alert alert-success";
        $strMSG = "Record(s) updated successfully";
    } else {
        $class = "alert alert-info";
        $strMSG = "Please Select Alteast One Checkbox";
    }
}
//--------------Button InActive--------------------
if (isset($_REQUEST['btnInactive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE appointment_schedule SET as_status='0' WHERE as_id = " . $_REQUEST['chkstatus'][$i]);
        }
        $class = "alert alert-success";
        $strMSG = "Record(s) updated successfully";
    } else {
        $class = "alert alert-info";
        $strMSG = "Please Select Alteast One Checkbox";
    }
}

//--------------Button Delete--------------------
if (isset($_REQUEST['btnDelete'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {

            mysqli_query($GLOBALS['conn'], "DELETE FROM appointment_schedule WHERE as_id = " . $_REQUEST['chkstatus'][$i]) or die(mysqli_error($_REQUEST['conn']));
        }
        $class = "alert alert-success";
        $strMSG = "Record(s) deleted successfully";
    } else {
        $class = " alert alert-info ";
        $strMSG = "Please check atleast one checkbox";
    }
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
                <?php if (isset($_REQUEST['action'])) { ?>
                    <div class="main_container">
                        <h2 class="text-white">
                            <?php print($formHead); ?> Schedule
                        </h2>
                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12 col-12 mt-3">
                                    <img src="<?php print($mfile_path); ?>" width="20%" alt="">
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Category</label>
                                    <select class="input_style" name="ac_id" id="ac_id">
                                        <?php FillSelected2("appointment_category", "ac_id", "ac_title_de AS ac_title ", $ac_id, "ac_status > 0"); ?>
                                    </select>
                                </div>
                                <div class="col-md-2 col-12 mt-3">
                                    <label for="">Duration</label>
                                    <select class="input_style" name="as_duration" id="as_duration">
                                        <?php
                                        $duration = 30;
                                        $duration_value = 0;
                                        for ($i = 0; $i < 5; $i++) {
                                            $duration_value = $duration * $i;
                                        ?>
                                            <option value="<?php print($duration_value); ?>" <?php print(($as_duration == $duration_value) ? 'selected' : ''); ?>><?php print($duration_value . " minutes"); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-2 col-12 mt-3">
                                    <label for="">Delay</label>
                                    <select class="input_style" name="as_delay" id="as_delay">
                                        <?php
                                        $delay = 30;
                                        $delay_value = 0;
                                        for ($i = 0; $i < 5; $i++) {
                                            $delay_value = $delay * $i;
                                        ?>
                                            <option value="<?php print($delay_value); ?>" <?php print(($as_delay == $delay_value) ? 'selected' : ''); ?>><?php print($delay_value . " minutes"); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-2 col-12 mt-3">
                                    <label for="">Remote</label>
                                    <select class="input_style" name="as_remote" id="as_remote">
                                        <option value="0" <?php print(($as_remote == 0) ? 'selected' : ''); ?>>No</option>
                                        <option value="1" <?php print(($as_remote == 1) ? 'selected' : ''); ?>>Yes</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Title DE</label>
                                    <input type="text" required class="input_style" name="as_title_de" id="as_title_de" value="<?php print($as_title_de); ?>" placeholder="Title DE">
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Title EN</label>
                                    <input type="text" class="input_style" name="as_title_en" id="as_title_en" value="<?php print($as_title_en); ?>" placeholder="Title EN">
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Detail DE</label>
                                    <textarea rows="12" required type="text" class="input_style" name="as_detail_de" id="as_detail_de" placeholder="Detail DE"> <?php print($as_detail_de); ?> </textarea>
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Detail EN</label>
                                    <textarea rows="12" type="text" class="input_style" name="as_detail_en" id="as_detail_en" placeholder="Detail DE"> <?php print($as_detail_en); ?> </textarea>
                                </div>
                                <div class="col-md-12 col-12 mt-3">
                                    <label for="">File</label>
                                    <div class="">
                                        <label for="file-upload" class="upload-btn">
                                            <span class="material-icons">cloud_upload</span>
                                            <span>Upload Files</span>
                                        </label>
                                        <input id="file-upload" type="file" class="file-input" name="mFile">
                                    </div>
                                </div>
                                <div class="col-md-12 col-12 mt-3">
                                    <button class="btn btn-primary" type="submit" name="<?php print(($_REQUEST['action'] == 1) ? 'btnAdd' : 'btnUpdate'); ?>">Upload</button>
                                    <?php if ($_REQUEST['action'] == 2) { ?>
                                        <input type="hidden" name="mfileName" value="<?php print($mfileName); ?>" />
                                    <?php } ?>
                                    <button type="button" name="btnBack" class="btn btn-light" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>';">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="table-controls">
                        <h1 class="text-white">Schedule Management</h1>
                        <div class="d-flex gap-1">
                            <a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=1"); ?>" class="add-new"><span class="material-icons icon">add</span> <span class="text">Add New</span></a>
                        </div>
                    </div>
                    <div class="main_table_container">
                        <form class="table_responsive" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <table>
                                <thead>
                                    <tr>
                                        <th width="50"><input type="checkbox" name="chkAll" onClick="setAll();"></th>
                                        <th>Image</th>
                                        <th>Category</th>
                                        <th>Title</th>
                                        <th>Detail</th>
                                        <th>Duration<br> minutes</th>
                                        <th>Delay<br> minutes</th>
                                        <th width="120">Remote</th>
                                        <th width="50">Status</th>
                                        <th width="50">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $Query = "SELECT ac.ac_title_de AS ac_title, asch.as_id,  asch.as_image, asch.as_title_de AS as_title, asch.as_detail_de AS as_detail, asch.as_duration, asch.as_delay, asch.as_status , asch.as_remote FROM appointment_schedule AS asch LEFT OUTER JOIN appointment_category ac ON ac.ac_id = asch.ac_id ORDER BY asch.as_id ASC";
                                    //print($Query);
                                    $counter = 0;
                                    $limit = 25;
                                    $start = $p->findStart($limit);
                                    $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], $Query));
                                    $pages = $p->findPages($count, $limit);
                                    $rs = mysqli_query($GLOBALS['conn'], $Query . " LIMIT " . $start . ", " . $limit);
                                    if (mysqli_num_rows($rs) > 0) {
                                        while ($row = mysqli_fetch_object($rs)) {
                                            $counter++;
                                            $strClass = 'label  label-danger';
                                            $image_path = $GLOBALS['siteURL'] . "files/no_img_1.jpg";
                                            //if (!empty($row->ban_file) && pathinfo($row->ban_file, PATHINFO_EXTENSION) != "mp4") {
                                            if (!empty($row->as_image)) {
                                                $image_path = $GLOBALS['siteURL'] . "files/appointment_schedule/" . $row->as_image;
                                            }
                                    ?>
                                            <tr>
                                                <td><input type="checkbox" name="chkstatus[]" value="<?php print($row->as_id); ?>"></td>
                                                <td>
                                                    <div class="popup_container" style="width:150px">
                                                        <div class="container__img-holder">
                                                            <img src="<?php print($image_path); ?>">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php print($row->ac_title); ?></td>
                                                <td><?php print($row->as_title); ?></td>
                                                <td><?php print($row->as_detail); ?></td>
                                                <td><?php print($row->as_duration); ?></td>
                                                <td><?php print($row->as_delay); ?></td>
                                                <td> <input type="checkbox" class="as_remote" id="as_remote" data-id="<?php print($row->as_id); ?>" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="sm" <?php print(($row->as_remote == 1) ? 'checked' : ''); ?>> </td>
                                                <td>
                                                    <?php
                                                    if ($row->as_status == 0) {
                                                        echo '<span class="btn btn-danger btn-style-light w-auto">Offline</span>';
                                                    } else {
                                                        echo '<span class="btn btn-success btn-style-light w-auto">Live</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-xs btn-primary btn-style-light w-auto" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "as_id=" . $row->as_id); ?>';"><span class="material-icons icon material-xs">edit</span></button>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        print('<tr><td colspan="100%" class="text-center">No record found!</td></tr>');
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php if ($counter > 0) { ?>
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td><?php print("Page <b>" . $_GET['page'] . "</b> of " . $pages); ?></td>
                                        <td style="float: right;">
                                            <ul class="pagination" style="margin: 0px;">
                                                <?php
                                                $pageList = $p->pageList($_GET['page'], $pages, '&' . $qryStrURL);
                                                print($pageList);
                                                ?>
                                            </ul>
                                        </td>
                                    </tr>
                                </table>
                            <?php } ?>

                            <div class="row">
                                <div class=" col-md-1 col-12 mt-2">
                                    <input type="submit" name="btnActive" value="Active" class="btn btn-primary btn-style-light w-100">
                                </div>
                                <div class=" col-md-1 col-12 mt-2">
                                    <input type="submit" name="btnInactive" value="In Active" class="btn btn-warning btn-style-light w-100">
                                </div>
                                <!--<div class=" col-md-1 col-12 mt-2">
                                    <input type="submit" name="btnDelete" value="Delete" class="btn btn-danger btn-style-light w-100" onclick="return confirm('Are you sure you want to delete selected item(s)?');">
                                </div>-->
                            </div>
                        </form>

                    </div>
                <?php } ?>
            </section>
        </div>
    </div>
    <?php include("includes/bottom_js.php"); ?>
</body>
<script>
    $('.as_remote').change(function() {
        let id = $(this).attr('data-id');
        let set_field_data = 0;
        //console.log("cat_id: "+cat_id)
        if ($(this).prop('checked')) {
            set_field_data = 1;
        }
        //console.log("set_field_data: "+set_field_data);
        $.ajax({
            url: 'ajax_calls.php?action=btn_toggle',
            method: 'POST',
            data: {
                table: "appointment_schedule",
                set_field: "as_remote",
                set_field_data: set_field_data,
                where_field: "as_id",
                id: id
            },
            success: function(response) {
                //console.log("response = "+response);
                const obj = JSON.parse(response);
                console.log(obj);
                if (obj.status == 1 && set_field_data == 1) {
                    $.toast({
                        heading: 'Success',
                        text: 'Toggle is ON',
                        icon: 'success',
                        position: 'top-right'
                    });
                } else if (obj.status == 1 && set_field_data == 0) {
                    $.toast({
                        heading: 'Warning',
                        text: 'Toggle is OFF',
                        icon: 'warning',
                        position: 'top-right'
                    });
                }
            }
        });
    });
</script>

</html>