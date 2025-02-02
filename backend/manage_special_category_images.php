<?php
include("../lib/session_head.php");

$scat_id = 0;
if (isset($_REQUEST['scat_id']) && $_REQUEST['scat_id'] > 0) {
    $scat_id = $_REQUEST['scat_id'];
    $qryStrURL .= "scat_id=" . $scat_id . "&";
}
if (isset($_REQUEST['btnAdd'])) {
    //print_r($_REQUEST);die();


    $gimg_id = getMaximum("gallery_images", "gimg_id");
    $mfileName = "";
    $dirName = "../files/gallery_images/special_category/" . $scat_id . "/";
    if (!empty($_FILES["mFile"]["name"])) {
        if (!file_exists("../files/gallery_images/special_category/" . $scat_id)) {
            mkdir("../files/gallery_images/special_category/" . $scat_id, 0777, true);
            mkdir("../files/gallery_images/special_category/" . $scat_id . "/th/", 0777, true);
        }
        $mfileName = $gimg_id . "_" . $_FILES["mFile"]["name"];
        $mfileName = str_replace(" ", "_", strtolower($mfileName));
        if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName)) {
            createThumbnail2($dirName, $mfileName, $dirName . "th/", "138", "80");
        }
    }
    mysqli_query($GLOBALS['conn'], "INSERT INTO gallery_images (gimg_id, gimg_title_de, gimg_title_en, gimg_file, scat_id) VALUES ('" . $gimg_id . "', '" . dbStr(trim($_REQUEST['gimg_title_de'])) . "', '" . dbStr(trim($_REQUEST['gimg_title_en'])) . "', '" . $mfileName . "', '" . $scat_id . "')") or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?action=1&" . $qryStrURL . "op=1");
} elseif (isset($_REQUEST['btnUpdate'])) {

    $mfileName = $_REQUEST['mfileName'];
    $dirName = "../files/gallery_images/special_category/" . $scat_id . "/";
    if (!empty($_FILES["mFile"]["name"])) {
        if (!file_exists("../files/gallery_images/special_category/" . $scat_id)) {
            mkdir("../files/gallery_images/special_category/" . $scat_id, 0777, true);
            mkdir("../files/gallery_images/special_category/" . $scat_id . "/th/", 0777, true);
        }
        @unlink("../files/gallery_images/special_category/" . $scat_id . "/" . $_REQUEST['mfileName']);
        @unlink("../files/gallery_images/special_category/" . $scat_id . "/" . "/th/" . $_REQUEST['mfileName']);
        $mfileName = $_REQUEST['gimg_id'] . "_" . $_FILES["mFile"]["name"];
        $mfileName = str_replace(" ", "_", strtolower($mfileName));
        if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName)) {
            createThumbnail2($dirName, $mfileName, $dirName . "th/", "138", "80");
        }
    }
    mysqli_query($GLOBALS['conn'], "UPDATE gallery_images SET gimg_title_de = '" . dbStr(trim($_REQUEST['gimg_title_de'])) . "', gimg_title_en = '" . dbStr(trim($_REQUEST['gimg_title_en'])) . "', gimg_file = '" . $mfileName . "' WHERE gimg_id= '" . $_REQUEST['gimg_id'] . "'") or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?action=1&" . $qryStrURL . "op=2");
} elseif (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 2) {
        $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM gallery_images WHERE gimg_id = " . $_REQUEST['gimg_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $gimg_title_de = $rsMem->gimg_title_de;
            $gimg_title_en = $rsMem->gimg_title_en;
            $mfileName = $rsMem->gimg_file;
            $formHead = "Update Info";
        }
    } else {
        $gimg_title_de = "";
        $gimg_title_en = "";
        $mfileName = "";
        $formHead = "Add New";
    }
}

//--------------Button Active--------------------
if (isset($_REQUEST['btnActive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE gallery_images SET gimg_status='1' WHERE gimg_id = " . $_REQUEST['chkstatus'][$i]);
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
            mysqli_query($GLOBALS['conn'], "UPDATE gallery_images SET gimg_status='0' WHERE gimg_id = " . $_REQUEST['chkstatus'][$i]);
        }
        $class = "alert alert-success";
        $strMSG = "Record(s) updated successfully";
    } else {
        $class = "alert alert-info";
        $strMSG = "Please Select Alteast One Checkbox";
    }
}
//--------------Button Orderby--------------------
if (isset($_REQUEST['btnOrderby'])) {
    if (isset($_REQUEST['gimg_id'])) {
        for ($i = 0; $i < count($_REQUEST['gimg_id']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE gallery_images SET gimg_orderby='" . $_REQUEST['gimg_orderby'][$i] . "' WHERE gimg_id = " . $_REQUEST['gimg_id'][$i]);
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

            DeleteFileWithThumb("gimg_file", "gallery_images", "gimg_id ", $_REQUEST['chkstatus'][$i], "../files/gallery_images/special_category/" . $scat_id . "/th/", "EMPTY");
            DeleteFileWithThumb("gimg_file", "gallery_images", "gimg_id ", $_REQUEST['chkstatus'][$i], "../files/gallery_images/special_category/" . $scat_id . "/", "EMPTY");
            mysqli_query($GLOBALS['conn'], "DELETE FROM gallery_images WHERE gimg_id = " . $_REQUEST['chkstatus'][$i]) or die(mysqli_error($_REQUEST['conn']));
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
                <div class="main_container">
                    <h2 class="text-white">
                        <?php print($formHead." ".returnName("scat_title_de AS scat_title", "special_category", "scat_id", $scat_id)." Gallery"); ?>
                    </h2>
                    <form name="frm_add" id="frm_add" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 col-12 mt-3">
                                <label for="">Heading EN</label>
                                <input type="text" class="input_style" name="gimg_title_de" id="gimg_title_de" value="<?php print($gimg_title_de); ?>" placeholder="Heading EN">
                            </div>
                            <div class="col-md-6 col-12 mt-3">
                                <label for="">Heading DE</label>
                                <input type="text" class="input_style" name="gimg_title_en" id="gimg_title_en" value="<?php print($gimg_title_en); ?>" placeholder="Heading DE">
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
                                <button type="button" name="btnBack" class="btn btn-light" onClick="javascript: window.location = '<?php print("manage_special_category.php"); ?>';">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="main_table_container mt-5">
                    <form class="table_responsive" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                        <table>
                            <thead>
                                <tr>
                                    <th width="50"><input type="checkbox" name="chkAll" onClick="setAll();"></th>
                                    <th width="100">Image</th>
                                    <th>Heading</th>
                                    <th width="100">Order By </th>
                                    <th width="50">Status</th>
                                    <th width="50">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $Query = "SELECT gi.gimg_id, gi.gimg_title_de AS gimg_title, gimg_file, gi.scat_id, gi.gimg_orderby, gi.gimg_status FROM gallery_images AS gi WHERE gi.scat_id = '".$scat_id."' ORDER BY gi.gimg_orderby ASC";
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
                                        //if (!empty($row->gimg_file) && pathinfo($row->gimg_file, PATHINFO_EXTENSION) != "mp4") {
                                        if (!empty($row->gimg_file)) {
                                            $image_path = $GLOBALS['siteURL'] . "files/gallery_images/special_category/" . $_REQUEST['scat_id'] . "/" . $row->gimg_file;
                                        }
                                ?>
                                        <tr>
                                            <td><input type="checkbox" name="chkstatus[]" value="<?php print($row->gimg_id); ?>"></td>
                                            <td>
                                                <div class="popup_container" style="width:100px">
                                                    <div class="container__img-holder">
                                                        <img src="<?php print($image_path); ?>">
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php print($row->gimg_title); ?></td>
                                            <td>
                                                <input type="hidden" name="gimg_id[]" id="gimg_id" value="<?php print($row->gimg_id); ?>">
                                                <input type="number" class="input_style" name="gimg_orderby[]" id="gimg_orderby" value="<?php print($row->gimg_orderby); ?>">
                                            </td>
                                            <td>
                                                <?php
                                                if ($row->gimg_status == 0) {
                                                    echo '<span class="btn btn-danger btn-style-light w-auto">Offline</span>';
                                                } else {
                                                    echo '<span class="btn btn-success btn-style-light w-auto">Live</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-xs btn-primary btn-style-light w-auto" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "gimg_id=" . $row->gimg_id); ?>';"><span class="material-icons icon material-xs">edit</span></button>
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
                            <div class=" col-md-1 col-12 mt-2 me-2">
                                <input type="submit" name="btnOrderby" value="Order Update" class="btn btn-success btn-style-light w-auto">
                            </div>
                            <div class=" col-md-1 col-12 mt-2">
                                <input type="submit" name="btnDelete" value="Delete" class="btn btn-danger btn-style-light w-auto" onclick="return confirm('Are you sure you want to delete selected item(s)?');">
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
    $('.ban_button_show').change(function() {
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
                table: "gallery_images",
                set_field: "ban_button_show",
                set_field_data: set_field_data,
                where_field: "gimg_id",
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
    $('input.ban_details_en').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=ban_details_en',
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
            var gimg_id = $("#gimg_id");
            var ban_details_en = $("#ban_details_en");
            $(gimg_id).val(ui.item.gimg_id);
            $(ban_details_en).val(ui.item.value);
            //frmCat.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });
</script>

</html>