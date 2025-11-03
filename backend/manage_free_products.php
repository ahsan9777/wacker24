<?php
include("../lib/session_head.php");

if (isset($_REQUEST['btnAdd'])) {
    //print_r($_REQUEST);die();
    $Query = "SELECT * FROM `free_product` WHERE fpc_id = '".$_REQUEST['fpc_id']."' AND fp_title_de ='" . dbStr(trim($_REQUEST['fp_title_de'])) . "'";
    $rs = mysqli_query($GLOBALS['conn'], $Query);
    if (mysqli_num_rows($rs) > 0) {

        $fp_title_de = $_REQUEST['fp_title_de'];
        $mfile_path = "";
        $class = "alert alert-danger";
        $strMSG = "Dear Admin, This free product already exists against the free product name!";
    } else {
        $fp_id = getMaximum("free_product", "fp_id");
        $fp_title_de_params = url_clean(convertGermanChars(trim($_REQUEST['fp_title_de'])));
        $mfileName = "";
        $dirName = "../files/free_product/";
        if (!empty($_FILES["mFile"]["name"])) {
            $mfileName = $fp_id . "_" . $_FILES["mFile"]["name"];
            $mfileName = str_replace(" ", "_", strtolower($mfileName));
            if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName)) {
                createThumbnail2($dirName, $mfileName, $dirName . "th/", "138", "80");
            }
        }
        mysqli_query($GLOBALS['conn'], "INSERT INTO free_product (fp_id, fpc_id, fp_title_de, fp_price, fp_file) VALUES ('" . $fp_id . "', '".$_REQUEST['fpc_id']."', '" . dbStr(trim($_REQUEST['fp_title_de'])) . "', '".trim($_REQUEST['fp_price'])."', '".$mfileName."')") or die(mysqli_error($GLOBALS['conn']));
        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
    }
} elseif (isset($_REQUEST['btnUpdate'])) {

    
    $dirName = "../files/free_product/";
    $mfileName = $_REQUEST['mfileName'];
    if (!empty($_FILES["mFile"]["name"])) {
        @unlink("../files/free_product/" . $_REQUEST['mfileName']);
        @unlink("../files/free_product/th/" . $_REQUEST['mfileName']);
        $mfileName = $_REQUEST['fp_id'] . "_" . $_FILES["mFile"]["name"];
        $mfileName = str_replace(" ", "_", strtolower($mfileName));
        if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName)) {
            createThumbnail2($dirName, $mfileName, $dirName . "th/", "138", "80");
        }
    }
    mysqli_query($GLOBALS['conn'], "UPDATE free_product SET fpc_id = '".trim($_REQUEST['fpc_id'])."', fp_title_de = '" . dbStr(trim($_REQUEST['fp_title_de'])) . "', fp_price = '".trim($_REQUEST['fp_price'])."', fp_file = '".$mfileName."' WHERE fp_id=" . $_REQUEST['fp_id']) or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
} elseif (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 2) {
        $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM free_product WHERE fp_id = " . $_REQUEST['fp_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $fp_title_de = $rsMem->fp_title_de;
            $fp_price = $rsMem->fp_price;
            $mfileName = $rsMem->fp_file;
            $formHead = "Update Info";
        }
    } else {
        $fpc_id = 0;
        $fp_title_de = "";
        $fp_price = 0;
        $mfileName = "";
        $formHead = "Add New";
    }
}

//--------------Button Active--------------------
if (isset($_REQUEST['btnActive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE free_product SET fp_status='1' WHERE fp_id = " . $_REQUEST['chkstatus'][$i]);
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
            mysqli_query($GLOBALS['conn'], "UPDATE free_product SET fp_status='0' WHERE fp_id = " . $_REQUEST['chkstatus'][$i]);
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
                            <?php print($formHead); ?> Free Product
                        </h2>
                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-3 col-12 mt-3">
                                    <label for="">Category</label>
                                    <select class="input_style" name="fpc_id" id="fpc_id">
                                        <?php FillSelected2("free_product_category", "fpc_id", "fpc_title_de AS fpc_title ", $fpc_id, "fpc_status = '1'"); ?>
                                    </select>
                                </div>
                                <div class="col-md-9 col-12 mt-3">
                                    <label for="">Title</label>
                                    <input type="text" class="input_style" name="fp_title_de" id="fp_title_de" value="<?php print($fp_title_de); ?>" placeholder="Title">
                                </div>
                                <div class="col-md-3 col-12 mt-3">
                                    <label for="">Price</label>
                                    <input type="number" step="any" class="input_style" name="fp_price" id="fp_price" value="<?php print($fp_price); ?>" placeholder="Price">
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
                                    <?php if ($_REQUEST['action'] == 2) { ?>
                                        <input type="hidden" name="mfileName" value="<?php print($mfileName); ?>" />
                                    <?php } ?>
                                    <button class="btn btn-primary" type="submit" name="<?php print(($_REQUEST['action'] == 1) ? 'btnAdd' : 'btnUpdate'); ?>">Upload</button>
                                    <button type="button" name="btnBack" class="btn btn-light" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>';">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="table-controls">
                        <h1 class="text-white">Free Product Management</h1>
                        <div class="d-flex gap-1">
                            <a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=1"); ?>" class="add-new"><span class="material-icons icon">add</span> <span class="text">Add New</span></a>
                        </div>
                    </div>
                    <div class="main_table_container">
                        <?php

                        $fp_id = 0;
                        $fp_title_de = "";
                        $searchQuery = "WHERE 1 = 1";

                        if (isset($_REQUEST['fp_id']) && $_REQUEST['fp_id'] > 0) {
                            if (!empty($_REQUEST['fp_title_de'])) {
                                $fp_id = $_REQUEST['fp_id'];
                                $fp_title_de = $_REQUEST['fp_title_de'];
                                $searchQuery .= " AND fp_id = '" . $_REQUEST['fp_id'] . "'";
                            }
                        }
                        ?>
                        <form class="row" name="frm_search" id="frm_search" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>">
                            <div class=" col-md-2 col-12 mt-2">
                                <label for="" class="text-white">Title</label>
                                <input type="hidden" name="fp_id" id="fp_id" value="<?php print($fp_id); ?>">
                                <input type="text" class="input_style fp_title_de" name="fp_title_de" id="fp_title_de" value="<?php print($fp_title_de); ?>" placeholder="Title:" autocomplete="off" onchange="javascript: frm_search.submit();">
                            </div>
                        </form>
                        <form class="table_responsive" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <table>
                                <thead>
                                    <tr>
                                        <th width="50"><input type="checkbox" name="chkAll" onClick="setAll();"></th>
                                        <th width="100">Logo</th>
                                        <th>Title</th>
                                        <th width="100">Price</th>
                                        <th width="50">Status</th>
                                        <th width="50">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $Query = "SELECT * FROM free_product " . $searchQuery . " ";
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
                                            if (!empty($row->fp_file)) {
                                                $image_path = $GLOBALS['siteURL'] . "files/free_product/" . $row->fp_file;
                                            }
                                    ?>
                                            <tr>
                                                <td><input type="checkbox" name="chkstatus[]" value="<?php print($row->fp_id); ?>"></td>
                                                <td>
                                                    <div class="popup_container" style="width:100px">
                                                        <div class="container__img-holder">
                                                            <img src="<?php print($image_path); ?>">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php print($row->fp_title_de); ?></td>
                                                <td><?php print($row->fp_price); ?></td>
                                                <td>
                                                    <?php
                                                    if ($row->fp_status == 0) {
                                                        echo '<span class="btn btn-danger btn-style-light w-auto">Offline</span>';
                                                    } else {
                                                        echo '<span class="btn btn-success btn-style-light w-auto">Live</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-xs btn-primary btn-style-light w-auto" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "fp_id=" . $row->fp_id); ?>';"><span class="material-icons icon material-xs">edit</span></button>
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
    $('input.fp_title_de').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=fp_title_de',
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
            var fp_id = $("#fp_id");
            var fp_title_de = $("#fp_title_de");
            $(fp_id).val(ui.item.fp_id);
            $(fp_title_de).val(ui.item.value);
            frm_search.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " fp_id " + ui.item.fp_id );
        }
    });
    $(document).ready(function() {
        // Listen for toggle changes
        $('.manf_showhome').change(function() {
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
                    table: "free_product",
                    set_field: "manf_showhome",
                    set_field_data: set_field_data,
                    where_field: "fp_id",
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
    });
</script>

</html>