<?php
include("../lib/session_head.php");

if (isset($_REQUEST['btnAdd'])) {
    //print_r($_REQUEST);die();


    $ban_id = getMaximum("banners", "ban_id");
    $mfileName = "";
    //$dirName = "images/banners/";
    $dirName = "../files/banners/";
    if (!empty($_FILES["mFile"]["name"])) {
        $mfileName = $ban_id . "_" . $_FILES["mFile"]["name"];
        $mfileName = str_replace(" ", "_", strtolower($mfileName));
        if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName)) {
            createThumbnail2($dirName, $mfileName, $dirName . "th/", "138", "80");
        }
    }
    mysqli_query($GLOBALS['conn'], "INSERT INTO banners (ban_id, ban_heading_color, ban_detail_color, ban_link, ban_background_color, ban_text_color, ban_button_show, ban_heading_en, ban_heading_de, ban_details_en, ban_details_de, ban_file) VALUES ('" . $ban_id . "', '" . dbStr(trim($_REQUEST['ban_heading_color'])) . "', '" . dbStr(trim($_REQUEST['ban_detail_color'])) . "', '" . dbStr(trim($_REQUEST['ban_link'])) . "', '" . dbStr(trim($_REQUEST['ban_background_color'])) . "', '" . dbStr(trim($_REQUEST['ban_text_color'])) . "', '" . dbStr(trim($_REQUEST['ban_button_show'])) . "', '" . dbStr(trim($_REQUEST['ban_heading_en'])) . "', '" . dbStr(trim($_REQUEST['ban_heading_de'])) . "', '" . dbStr(trim($_REQUEST['ban_details_en'])) . "', '" . dbStr(trim($_REQUEST['ban_details_de'])) . "', '" . $mfileName . "')") or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
} elseif (isset($_REQUEST['btnUpdate'])) {

    $dirName = "../files/banners/";
    $mfileName = $_REQUEST['mfileName'];
    if (!empty($_FILES["mFile"]["name"])) {
        @unlink("../files/banners/" . $_REQUEST['mfileName']);
        @unlink("../files/banners/th/" . $_REQUEST['mfileName']);
        $mfileName = $_REQUEST['ban_id'] . "_" . $_FILES["mFile"]["name"];
        $mfileName = str_replace(" ", "_", strtolower($mfileName));
        if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName)) {
            createThumbnail2($dirName, $mfileName, $dirName . "th/", "138", "80");
        }
    }
    mysqli_query($GLOBALS['conn'], "UPDATE banners SET ban_heading_color = '" . dbStr(trim($_REQUEST['ban_heading_color'])) . "', ban_detail_color = '" . dbStr(trim($_REQUEST['ban_detail_color'])) . "', ban_link = '" . dbStr(trim($_REQUEST['ban_link'])) . "', ban_background_color = '" . dbStr(trim($_REQUEST['ban_background_color'])) . "', ban_text_color = '" . dbStr(trim($_REQUEST['ban_text_color'])) . "', ban_button_show = '" . dbStr(trim($_REQUEST['ban_button_show'])) . "', ban_heading_en = '" . dbStr(trim($_REQUEST['ban_heading_en'])) . "', ban_heading_de = '" . dbStr(trim($_REQUEST['ban_heading_de'])) . "', ban_details_en = '" . dbStr(trim($_REQUEST['ban_details_en'])) . "', ban_details_de = '" . dbStr(trim($_REQUEST['ban_details_de'])) . "', ban_file = '" . $mfileName . "' WHERE ban_id= '" . $_REQUEST['ban_id'] . "'") or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
} elseif (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 2) {
        $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM banners WHERE ban_id = " . $_REQUEST['ban_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $ban_heading_color = $rsMem->ban_heading_color;
            $ban_detail_color = $rsMem->ban_detail_color;
            $ban_button_show = $rsMem->ban_button_show;
            $ban_link = $rsMem->ban_link;
            $ban_background_color = $rsMem->ban_background_color;
            $ban_text_color = $rsMem->ban_text_color;
            $ban_heading_en = $rsMem->ban_heading_en;
            $ban_heading_de = $rsMem->ban_heading_de;
            $ban_details_en = $rsMem->ban_details_en;
            $ban_details_de = $rsMem->ban_details_de;
            $mfileName = $rsMem->ban_file;
            $mfile_path = !empty($rsMem->ban_file) ? $GLOBALS['siteURL'] . "files/banners/" . $rsMem->ban_file : "";
            $ext = pathinfo($mfile_path, PATHINFO_EXTENSION);
            $formHead = "Update Info";
        }
    } else {
        $ban_heading_color = "";
        $ban_detail_color = "";
        $ban_button_show = "";
        $ban_link = "";
        $ban_background_color = "";
        $ban_text_color = "";
        $ban_heading_en = "";
        $ban_heading_de = "";
        $ban_details_en = "";
        $ban_details_de = "";
        $mfileName = "";
        $mfile_path = "";
        $formHead = "Add New";
    }
}

//--------------Button Active--------------------
if (isset($_REQUEST['btnActive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE banners SET ban_status='1' WHERE ban_id = " . $_REQUEST['chkstatus'][$i]);
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
            mysqli_query($GLOBALS['conn'], "UPDATE banners SET ban_status='0' WHERE ban_id = " . $_REQUEST['chkstatus'][$i]);
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
    if (isset($_REQUEST['ban_id'])) {
        for ($i = 0; $i < count($_REQUEST['ban_id']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE banners SET ban_order='" . $_REQUEST['ban_order'][$i] . "' WHERE ban_id = " . $_REQUEST['ban_id'][$i]);
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

            DeleteFileWithThumb("ban_file", "banners", "ban_id ", $_REQUEST['chkstatus'][$i], "../files/banners/th/", "EMPTY");
            DeleteFileWithThumb("ban_file", "banners", "ban_id ", $_REQUEST['chkstatus'][$i], "../files/banners/", "EMPTY");
            mysqli_query($GLOBALS['conn'], "DELETE FROM banners WHERE ban_id = " . $_REQUEST['chkstatus'][$i]) or die(mysqli_error($_REQUEST['conn']));
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
                            <?php print($formHead); ?> Banner
                        </h2>
                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12 col-12 mt-3">
                                    <?php if ($ext == "mp4") { ?>
                                        <video src="<?php print($mfile_path); ?>" width="100%" autoplay loop></video>
                                    <?php } else { ?>
                                        <img src="<?php print($mfile_path); ?>" width="100%" style="border-radius: 10px;" alt="">
                                    <?php } ?>
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Heading Color (Like: #fff)</label>
                                    <input type="text" class="input_style" name="ban_heading_color" id="ban_heading_color" value="<?php print($ban_heading_color); ?>" placeholder="Heading Color (Like: #fff)">
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Detail Color (Like: #fff)</label>
                                    <input type="text" class="input_style" name="ban_detail_color" id="ban_detail_color" value="<?php print($ban_detail_color); ?>" placeholder="Detail Color (Like: #fff)">
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Button Link</label>
                                    <input type="text" class="input_style ban_link" name="ban_link" id="ban_link" value="<?php print($ban_link); ?>" placeholder="Button Link">
                                </div>
                                <div class="col-md-3 col-12 mt-3">
                                    <label for="">Button Background Color (Like: #fff)</label>
                                    <input type="text" required class="input_style ban_background_color" name="ban_background_color" id="ban_background_color" value="<?php print($ban_background_color); ?>" placeholder="Button Background Color (Like: #fff)">
                                </div>
                                <div class="col-md-3 col-12 mt-3">
                                    <label for="">Button Text Color (Like: #fff)</label>
                                    <input type="text" required class="input_style ban_text_color" name="ban_text_color" id="ban_text_color" value="<?php print($ban_text_color); ?>" placeholder="Button Text Color (Like: #fff)">
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Heading EN</label>
                                    <input type="text" class="input_style" name="ban_heading_en" id="ban_heading_en" value="<?php print($ban_heading_en); ?>" placeholder="Heading EN">
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Heading DE</label>
                                    <input type="text" class="input_style" name="ban_heading_de" id="ban_heading_de" value="<?php print($ban_heading_de); ?>" placeholder="Heading DE">
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Detail EN</label>
                                    <textarea rows="3" type="text" class="input_style" name="ban_details_en" id="ban_details_en" placeholder="Detail EN"> <?php print($ban_details_en); ?> </textarea>
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Detail DE</label>
                                    <textarea rows="3" type="text" class="input_style" name="ban_details_de" id="ban_details_de" placeholder="Detail DE"> <?php print($ban_details_de); ?> </textarea>
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
                        <h1 class="text-white">Banner Management</h1>
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
                                        <th width="100">Logo</th>
                                        <th>Heading</th>
                                        <th>Detail</th>
                                        <th width="100">Order By </th>
                                        <th width="120">Button Show</th>
                                        <th width="50">Status</th>
                                        <th width="50">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $Query = "SELECT * FROM banners ORDER BY ban_order ASC";
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
                                            if (!empty($row->ban_file)) {
                                                $image_path = $GLOBALS['siteURL'] . "files/banners/" . $row->ban_file;
                                            }
                                            $ext = pathinfo($image_path, PATHINFO_EXTENSION);
                                    ?>
                                            <tr>
                                                <td><input type="checkbox" name="chkstatus[]" value="<?php print($row->ban_id); ?>"></td>
                                                <td>
                                                    <?php if ($ext == "mp4") { ?>
                                                        <video src="<?php print($image_path); ?>" width="250" autoplay loop></video>
                                                    <?php } else { ?>
                                                        <div class="popup_container" style="width:250px">
                                                            <div class="container__img-holder">
                                                                <img src="<?php print($image_path); ?>">
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </td>
                                                <td><?php print($row->ban_heading_en); ?></td>
                                                <td><?php print($row->ban_details_en); ?></td>
                                                <td>
                                                    <input type="hidden" name="ban_id[]" id="ban_id" value="<?php print($row->ban_id); ?>">
                                                    <input type="number" class="input_style" name="ban_order[]" id="ban_order" value="<?php print($row->ban_order); ?>">
                                                </td>
                                                <td> <input type="checkbox" class="ban_button_show" id="ban_button_show" data-id="<?php print($row->ban_id); ?>" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="sm" <?php print(($row->ban_button_show == 1) ? 'checked' : ''); ?>> </td>
                                                <td>
                                                    <?php
                                                    if ($row->ban_status == 0) {
                                                        echo '<span class="btn btn-danger btn-style-light w-auto">Offline</span>';
                                                    } else {
                                                        echo '<span class="btn btn-success btn-style-light w-auto">Live</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-xs btn-primary btn-style-light w-auto" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "ban_id=" . $row->ban_id); ?>';"><span class="material-icons icon material-xs">edit</span></button>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        print('<tr><td colspan="100%" align="center">No record found!</td></tr>');
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
                                <div class=" col-md-1 col-12 mt-2">
                                    <input type="submit" name="btnOrderby" value="Order Update" class="btn btn-success btn-style-light w-auto">
                                </div>
                                <div class=" col-md-1 col-12 mt-2">
                                    <input type="submit" name="btnDelete" value="Delete" class="btn btn-danger btn-style-light w-100" onclick="return confirm('Are you sure you want to delete selected item(s)?');">
                                </div>
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
                table: "banners",
                set_field: "ban_button_show",
                set_field_data: set_field_data,
                where_field: "ban_id",
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
            var ban_id = $("#ban_id");
            var ban_details_en = $("#ban_details_en");
            $(ban_id).val(ui.item.ban_id);
            $(ban_details_en).val(ui.item.value);
            //frmCat.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });
</script>

</html>