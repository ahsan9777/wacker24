<?php
include("../lib/session_head.php");
$pro_id = 0;
$supplier_id = 0;
if (isset($_REQUEST['pro_id']) && $_REQUEST['pro_id'] > 0) {
    $pro_id = $_REQUEST['pro_id'];
    $qryStrURL .= "pro_id=" . $_REQUEST['pro_id'] . "&";
}
if (isset($_REQUEST['supplier_id']) && $_REQUEST['supplier_id'] > 0) {
    $supplier_id = $_REQUEST['supplier_id'];
    $qryStrURL .= "supplier_id=" . $_REQUEST['supplier_id'] . "&";
}
if (isset($_REQUEST['btnAdd'])) {
    //print_r($_REQUEST);die();
    $pg_id = getMaximum("products_gallery", "pg_id");
    if (!file_exists("../files/products_gallery/" . $_REQUEST['supplier_id'])) {
        mkdir("../files/products_gallery/" . $_REQUEST['supplier_id'], 0777, true);
        mkdir("../files/products_gallery/" . $_REQUEST['supplier_id'] . "/th/", 0777, true);
    }
    $mfileName = "";
    $dirName = "../files/products_gallery/" . $_REQUEST['supplier_id'] . "/";
    if (!empty($_FILES["mFile"]["name"])) {
        $mfileName = $pg_id . "_" . $_FILES["mFile"]["name"];
        $mfileName = str_replace(" ", "_", strtolower($mfileName));
        if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName)) {
            createThumbnail2($dirName, $mfileName, $dirName . "th/", "138", "80");
        }
    }
    mysqli_query($GLOBALS['conn'], "INSERT INTO products_gallery (pg_id, pro_id, supplier_id, pg_mime_source, pg_mime_source_url, pg_mime_description, pg_mime_alt) VALUES ('" . $pg_id . "', '" . $pro_id . "', '" . $supplier_id . "', '" . $mfileName . "', '" . ltrim($dirName . $mfileName, "../") . "', '" . dbStr(trim($_REQUEST['pg_mime_description'])) . "', '" . pathinfo($mfileName, PATHINFO_FILENAME) . "')") or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?action=1&" . $qryStrURL . "op=1");
} elseif (isset($_REQUEST['btnUpdate'])) {

    if (!file_exists("../files/products_gallery/" . $_REQUEST['supplier_id'])) {
        mkdir("../files/products_gallery/" . $_REQUEST['supplier_id'], 0777, true);
        mkdir("../files/products_gallery/" . $_REQUEST['supplier_id'] . "/th/", 0777, true);
    }

    $mfileName = $_REQUEST['mfileName'];
    $dirName = "../files/products_gallery/" . $_REQUEST['supplier_id'] . "/";
    if (!empty($_FILES["mFile"]["name"])) {
        @unlink($dirName . $_REQUEST['mfileName']);
        @unlink($dirName . "th/" . $_REQUEST['mfileName']);
        $mfileName = $_REQUEST['pg_id'] . "_" . $_FILES["mFile"]["name"];
        $mfileName = str_replace(" ", "_", strtolower($mfileName));
        if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . "/" . $mfileName)) {
            createThumbnail2($dirName, $mfileName, $dirName . "th/", "200", "200");
        }
    }
    mysqli_query($GLOBALS['conn'], "UPDATE products_gallery SET pg_mime_description = '" . dbStr(trim($_REQUEST['pg_mime_description'])) . "', pg_mime_source = '" . $mfileName . "', pg_mime_source_url = '" . ltrim($dirName . $mfileName, "../") . "', pg_mime_alt = '" . pathinfo($mfileName, PATHINFO_FILENAME) . "'  WHERE pg_id=" . $_REQUEST['pg_id']) or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?action=1&" . $qryStrURL . "op=2");
} elseif (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 2) {
        $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM products_gallery WHERE pg_id = " . $_REQUEST['pg_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $pg_mime_description = $rsMem->pg_mime_description;
            $mfileName = $rsMem->pg_mime_source;
            $formHead = "Update Info";
        }
    } else {
        $pg_mime_description = "";
        $mfileName = "";
        $formHead = "Add New";
    }
}

//--------------Button Orderby--------------------
if (isset($_REQUEST['btnOrderby'])) {
    if (isset($_REQUEST['pg_id'])) {
        for ($i = 0; $i < count($_REQUEST['pg_id']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE products_gallery SET pg_mime_order='" . $_REQUEST['pg_mime_order'][$i] . "' WHERE pg_id = " . $_REQUEST['pg_id'][$i]);
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

            mysqli_query($GLOBALS['conn'], "DELETE FROM products_gallery WHERE pg_id = " . $_REQUEST['chkstatus'][$i]) or die(mysqli_error($_REQUEST['conn']));
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
                        <?php print($formHead); ?> Products Feature
                    </h2>
                    <form name="frm_data" id="frm_data" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12 col-12 mt-3">
                                <label for="">Description</label>
                                <input type="text" class="input_style" name="pg_mime_description" id="pg_mime_description" value="<?php print($pg_mime_description); ?>" placeholder="Description">
                            </div>
                            <div class="col-md-12 col-12 mt-3">
                                <label for="">Image</label>
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
                                <button type="button" name="btnBack" class="btn btn-light" onClick="javascript: window.location = 'manage_custom_products.php';">Cancel</button>
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
                                    <th>Description</th>
                                    <th width="100">Order By </th>
                                    <th width="50">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $Query = "SELECT * FROM `products_gallery` WHERE supplier_id = '" . $_REQUEST['supplier_id'] . "' ORDER BY pg_mime_order ASC";
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
                                        if (!empty($row->pg_mime_source_url)) {
                                            $image_path = $GLOBALS['siteURL'] . $row->pg_mime_source_url;
                                        }

                                ?>
                                        <tr>
                                            <td><input type="checkbox" name="chkstatus[]" value="<?php print($row->pg_id); ?>"></td>
                                            <td>
                                                <div class="popup_container" style="width:100px">
                                                    <div class="container__img-holder">
                                                        <img src="<?php print($image_path); ?>">
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php print($row->pg_mime_description); ?></td>
                                            <td>
                                                <input type="hidden" name="pg_id[]" id="pg_id" value="<?php print($row->pg_id); ?>">
                                                <input type="number" class="input_style" name="pg_mime_order[]" id="pg_mime_order" value="<?php print($row->pg_mime_order); ?>">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-xs btn-primary btn-style-light w-auto" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "pg_id=" . $row->pg_id); ?>';"><span class="material-icons icon material-xs">edit</span></button>
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
                                <input type="submit" name="btnOrderby" value="Order Update" class="btn btn-success btn-style-light w-auto">
                            </div>
                            <div class=" col-md-1 col-12 mt-2">
                                <input type="submit" name="btnDelete" value="Delete" class="btn btn-danger btn-style-light w-100" onclick="return confirm('Are you sure you want to delete selected item(s)?');">
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
    $('input.pq_upcomming_quantity').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=pq_upcomming_quantity',
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
            var pg_id = $("#pg_id");
            var pq_upcomming_quantity = $("#pq_upcomming_quantity");
            $(pg_id).val(ui.item.pg_id);
            $(pq_upcomming_quantity).val(ui.item.value);
            frm_search.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });
</script>

</html>