<?php
include("../lib/session_head.php");

if (isset($_REQUEST['btnAdd'])) {
    //print_r($_REQUEST);die();
    $Query = "SELECT * FROM social_links WHERE pm_title_en ='" . dbStr(trim($_REQUEST['pm_title_en'])) . "'";
    $rs = mysqli_query($GLOBALS['conn'], $Query);
    if (mysqli_num_rows($rs) > 0) {

        $sl_title = $_REQUEST['sl_title'];
        $pm_title_en = $_REQUEST['pm_title_en'];
        $mfile_path = "";
        $class = "alert alert-danger";
        $strMSG = "Dear Admin, This brand already exists against the brand name!";
    } else {

        $sl_id = getMaximum("social_links", "sl_id");
        $mfileName = "";
        //$dirName = "images/banners/";
        $dirName = "../files/social_media_icons/";
        if (!empty($_FILES["mFile"]["name"])) {
            $mfileName = $sl_id . "_" . $_FILES["mFile"]["name"];
            $mfileName = str_replace(" ", "_", strtolower($mfileName));
            if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName)) {
                createThumbnail2($dirName, $mfileName, $dirName . "th/", "138", "80");
            }
        }
        $pm_show_detail = 0;
        if (isset($_REQUEST['pm_show_detail']) && !empty($_REQUEST['pm_show_detail'])) {
            $pm_show_detail = $_REQUEST['pm_show_detail'];
        }
        mysqli_query($GLOBALS['conn'], "INSERT INTO social_links (sl_id, sl_title, pm_title_en, pm_currency, pm_brand_name, sl_url, pm_show_detail, sl_icon) VALUES ('" . $sl_id . "', '" . dbStr(trim($_REQUEST['sl_title'])) . "', '" . dbStr(trim($_REQUEST['pm_title_en'])) . "', '" . dbStr(trim($_REQUEST['pm_currency'])) . "', '" . dbStr(trim($_REQUEST['pm_brand_name'])) . "', '" . dbStr(trim($_REQUEST['sl_url'])) . "', '" . dbStr(trim($pm_show_detail)) . "', '" . $mfileName . "')") or die(mysqli_error($GLOBALS['conn']));
        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
    }
} elseif (isset($_REQUEST['btnUpdate'])) {
    //print_r($_REQUEST);
    $dirName = "../files/social_media_icons/";
    $mfileName = $_REQUEST['mfileName'];
    if (!empty($_FILES["mFile"]["name"])) {
        @unlink("../files/social_media_icons/" . $_REQUEST['mfileName']);
        @unlink("../files/social_media_icons/th/" . $_REQUEST['mfileName']);
        $mfileName = $_REQUEST['sl_id'] . "_" . $_FILES["mFile"]["name"];
        $mfileName = str_replace(" ", "_", strtolower($mfileName));
        if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName)) {
            createThumbnail2($dirName, $mfileName, $dirName . "th/", "138", "80");
        }
    }
    mysqli_query($GLOBALS['conn'], "UPDATE social_links SET sl_title = '" . dbStr(trim($_REQUEST['sl_title'])) . "', sl_url = '" . dbStr(trim($_REQUEST['sl_url'])) . "', sl_icon = '" . $mfileName . "' WHERE sl_id= '" . $_REQUEST['sl_id'] . "'") or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
} elseif (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 2) {
        $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM social_links WHERE sl_id = " . $_REQUEST['sl_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $sl_title = $rsMem->sl_title;
            $sl_url = $rsMem->sl_url;
            $mfileName = $rsMem->sl_icon;
            $mfile_path = !empty($rsMem->sl_icon) ? $GLOBALS['siteURL'] . "files/social_media_icons/" . $rsMem->sl_icon : "";
            $formHead = "Update Info";
        }
    } else {
        $sl_title = "";
        $sl_url = "";
        $mfileName = "";
        $mfile_path = "";
        $formHead = "Add New";
    }
}

//--------------Button Active--------------------
if (isset($_REQUEST['btnActive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE social_links SET sl_status='1' WHERE sl_id = " . $_REQUEST['chkstatus'][$i]);
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
            mysqli_query($GLOBALS['conn'], "UPDATE social_links SET sl_status='0' WHERE sl_id = " . $_REQUEST['chkstatus'][$i]);
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
    if (isset($_REQUEST['sl_id'])) {
        for ($i = 0; $i < count($_REQUEST['sl_id']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE social_links SET sl_orderby='" . $_REQUEST['sl_orderby'][$i] . "' WHERE sl_id = " . $_REQUEST['sl_id'][$i]);
        }
        $class = "alert alert-success";
        $strMSG = "Record(s) updated successfully";
    } else {
        $class = "alert alert-info";
        $strMSG = "Please Select Alteast One Checkbox";
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
                            <?php print($formHead); ?> Payment Method
                        </h2>
                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12 col-12 mt-3">
                                    <img src="<?php print($mfile_path); ?>" width="100px" style="border-radius: 10px;" alt="">
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Title</label>
                                    <input type="text" class="input_style" name="sl_title" id="sl_title" value="<?php print($sl_title); ?>" placeholder="Title DE">
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Link</label>
                                    <input type="text" class="input_style" name="sl_url" id="sl_url" value="<?php print($sl_url); ?>" placeholder="Entity ID">
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
                        <h1 class="text-white">Social Media Links Management</h1>
                        <div class="d-flex gap-1">
                            <!--<a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=1"); ?>" class="add-new"><span class="material-icons icon">add</span> <span class="text">Add New</span></a>-->
                        </div>
                    </div>
                    <div class="main_table_container">
                        <form class="table_responsive" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <table>
                                <thead>
                                    <tr>
                                        <th width="50"><input type="checkbox" name="chkAll" onClick="setAll();"></th>
                                        <th width="100">Icon</th>
                                        <th width="150">Title</th>
                                        <th>Link </th>
                                        <th width="100">Order By </th>
                                        <th width="50">Status</th>
                                        <th width="50">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $Query = "SELECT * FROM social_links ORDER BY sl_orderby ASC";
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
                                            if (!empty($row->sl_icon)) {
                                                $image_path = $GLOBALS['siteURL'] . "files/social_media_icons/" . $row->sl_icon;
                                            }
                                            
                                    ?>
                                            <tr>
                                                <td><input type="checkbox" name="chkstatus[]" value="<?php print($row->sl_id); ?>"></td>
                                                <td><img src="<?php print($image_path); ?>" width="100" style="border-radius: 10px;"></td>
                                                <td><?php print($row->sl_title); ?></td>
                                                <td><a class="text-white text-decoration-none" href="<?php print($row->sl_url); ?>" target="_blank" rel="noopener noreferrer"><?php print($row->sl_url); ?></a></td>
                                                <td>
                                                    <input type="hidden" name="sl_id[]" id="sl_id" value="<?php print($row->sl_id); ?>">
                                                    <input type="number" class="input_style" name="sl_orderby[]" id="sl_orderby" value="<?php print($row->sl_orderby); ?>">
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($row->sl_status == 0) {
                                                        echo '<span class="btn btn-danger btn-style-light w-auto">Offline</span>';
                                                    } else {
                                                        echo '<span class="btn btn-success btn-style-light w-auto">Live</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-xs btn-primary btn-style-light w-auto" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "sl_id=" . $row->sl_id); ?>';"><span class="material-icons icon material-xs">edit</span></button>
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
                                <div class=" col-md-1 col-12 mt-2">
                                    <input type="submit" name="btnOrderby" value="Order Update" class="btn btn-success btn-style-light w-auto">
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
    $('input.pm_title_en').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=pm_title_en',
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
            var sl_id = $("#sl_id");
            var pm_title_en = $("#pm_title_en");
            $(sl_id).val(ui.item.sl_id);
            $(pm_title_en).val(ui.item.value);
            //frmCat.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });
</script>

</html>