<?php
include("../lib/session_head.php");

if (isset($_REQUEST['btnAdd'])) {
    //print_r($_REQUEST);die();
    $Query = "SELECT * FROM payment_method WHERE pm_title_en ='" . dbStr(trim($_REQUEST['pm_title_en'])) . "'";
    $rs = mysqli_query($GLOBALS['conn'], $Query);
    if (mysqli_num_rows($rs) > 0) {

        $pm_title_de = $_REQUEST['pm_title_de'];
        $pm_title_en = $_REQUEST['pm_title_en'];
        $mfile_path = "";
        $class = "alert alert-danger";
        $strMSG = "Dear Admin, This brand already exists against the brand name!";
    } else {

        $pm_id = getMaximum("payment_method", "pm_id");
        $mfileName = "";
        //$dirName = "images/banners/";
        $dirName = "../files/payment_method/";
        if (!empty($_FILES["mFile"]["name"])) {
            $mfileName = $pm_id . "_" . $_FILES["mFile"]["name"];
            $mfileName = str_replace(" ", "_", strtolower($mfileName));
            if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName)) {
                createThumbnail2($dirName, $mfileName, $dirName . "th/", "138", "80");
            }
        }
        mysqli_query($GLOBALS['conn'], "INSERT INTO payment_method (pm_id, pm_title_de, pm_title_en, pm_entity_id, pm_image) VALUES ('" . $pm_id . "', '" . dbStr(trim($_REQUEST['pm_title_de'])) . "', '" . dbStr(trim($_REQUEST['pm_title_en'])) . "', '".dbStr(trim($_REQUEST['pm_entity_id']))."', '" . $mfileName . "')") or die(mysqli_error($GLOBALS['conn']));
        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
    }
} elseif (isset($_REQUEST['btnUpdate'])) {

    $dirName = "../files/payment_method/";
    $mfileName = $_REQUEST['mfileName'];
    if (!empty($_FILES["mFile"]["name"])) {
        @unlink("../files/payment_method/" . $_REQUEST['mfileName']);
        @unlink("../files/payment_method/th/" . $_REQUEST['mfileName']);
        $mfileName = $_REQUEST['pm_id'] . "_" . $_FILES["mFile"]["name"];
        $mfileName = str_replace(" ", "_", strtolower($mfileName));
        if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName)) {
            createThumbnail2($dirName, $mfileName, $dirName . "th/", "138", "80");
        }
    }
    mysqli_query($GLOBALS['conn'], "UPDATE payment_method SET pm_title_de = '" . dbStr(trim($_REQUEST['pm_title_de'])) . "', pm_title_en = '" . dbStr(trim($_REQUEST['pm_title_en'])) . "', pm_entity_id = '".dbStr(trim($_REQUEST['pm_entity_id']))."', pm_image = '" . $mfileName . "' WHERE pm_id= '" . $_REQUEST['pm_id']."'") or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
} elseif (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 2) {
        $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM payment_method WHERE pm_id = " . $_REQUEST['pm_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $pm_title_de = $rsMem->pm_title_de;
            $pm_title_de = $rsMem->pm_title_de;
            $pm_title_en = $rsMem->pm_title_en;
            $pm_entity_id = $rsMem->pm_entity_id;
            $mfileName = $rsMem->pm_image;
            $mfile_path = !empty($rsMem->pm_image) ? $GLOBALS['siteURL'] . "files/payment_method/" . $rsMem->pm_image : "";
            $formHead = "Update Info";
        }
    } else {
        $pm_title_de = 0;
        $pm_title_de = "";
        $pm_title_en = "";
        $pm_entity_id = "";
        $mfileName = "";
        $mfile_path = "";
        $formHead = "Add New";
    }
}

//--------------Button Active--------------------
if (isset($_REQUEST['btnActive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE payment_method SET pm_status='1' WHERE pm_id = " . $_REQUEST['chkstatus'][$i]);
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
            mysqli_query($GLOBALS['conn'], "UPDATE payment_method SET pm_status='0' WHERE pm_id = " . $_REQUEST['chkstatus'][$i]);
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
    if (isset($_REQUEST['pm_id'])) {
        for ($i = 0; $i < count($_REQUEST['pm_id']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE payment_method SET pm_orderby='".$_REQUEST['pm_orderby'][$i]."' WHERE pm_id = " . $_REQUEST['pm_id'][$i]);
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
            
            DeleteFileWithThumb("pm_image", "payment_method", "pm_id ", $_REQUEST['chkstatus'][$i], "../files/payment_method/th/", "EMPTY");
            DeleteFileWithThumb("pm_image", "payment_method", "pm_id ", $_REQUEST['chkstatus'][$i], "../files/payment_method/", "EMPTY");
            mysqli_query($GLOBALS['conn'], "DELETE FROM payment_method WHERE pm_id = " . $_REQUEST['chkstatus'][$i]) or die(mysqli_error($_REQUEST['conn']));
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
                            <?php print($formHead); ?> Payment Method
                        </h2>
                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12 col-12 mt-3">
                                    <img src="<?php print($mfile_path); ?>" width="100px" style="border-radius: 10px;" alt="">
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Title DE</label>
                                    <input type="text" class="input_style" name="pm_title_de" id="pm_title_de" value="<?php print($pm_title_de); ?>" placeholder="Title DE">
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Title EN</label>
                                    <input type="text" class="input_style" name="pm_title_en" id="pm_title_en" value="<?php print($pm_title_en); ?>" placeholder="Title EN">
                                </div>
                                <div class="col-md-12 col-12 mt-3">
                                    <label for="">Entity ID</label>
                                    <input type="text" class="input_style" name="pm_entity_id" id="pm_entity_id" value="<?php print($pm_entity_id); ?>" placeholder="Entity ID">
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
                        <h1 class="text-white">Payment Method Management</h1>
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
                                        <th width="150">Title</th>
                                        <th>Entity ID </th>
                                        <th width = "100">Order By </th>
                                        <th width="50">Status</th>
                                        <th width="50">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $Query = "SELECT * FROM payment_method ORDER BY pm_orderby ASC";
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
                                            if (!empty($row->pm_image)) {
                                                $image_path = $GLOBALS['siteURL'] . "files/payment_method/" . $row->pm_image;
                                            }
                                    ?>
                                            <tr>
                                                <td><input type="checkbox" name="chkstatus[]" value="<?php print($row->pm_id); ?>"></td>
                                                <td><img src="<?php print($image_path); ?>" width="100" style="border-radius: 10px;"></td>
                                                <td><?php print("<strong>DE: </strong>".$row->pm_title_de."<br> <strong>EN: </strong>".$row->pm_title_en); ?></td>
                                                <td><?php print($row->pm_entity_id); ?></td>
                                                <td>
                                                    <input type="hidden" name="pm_id[]" id="pm_id" value="<?php print($row->pm_id); ?>">
                                                    <input type="number" class="input_style" name="pm_orderby[]" id="pm_orderby" value="<?php print($row->pm_orderby); ?>">
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($row->pm_status == 0) {
                                                        echo '<span class="btn btn-danger btn-style-light w-auto">Offline</span>';
                                                    } else {
                                                        echo '<span class="btn btn-success btn-style-light w-auto">Live</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-xs btn-primary btn-style-light w-auto" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "pm_id=" . $row->pm_id); ?>';"><span class="material-icons icon material-xs">edit</span></button>
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
                                    <input type="submit" name="btnDelete" value="Delete" class="btn btn-danger btn-style-light w-100" onclick="return confirm('Are you sure you want to delete selected item(s)?');" >
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
            var pm_id = $("#pm_id");
            var pm_title_en = $("#pm_title_en");
            $(pm_id).val(ui.item.pm_id);
            $(pm_title_en).val(ui.item.value);
            //frmCat.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });
</script>

</html>