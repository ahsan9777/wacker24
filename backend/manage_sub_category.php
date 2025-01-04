<?php
include("../lib/session_head.php");

if (isset($_REQUEST['btnUpdate'])) {

    if (!file_exists("../files/category/" . $_REQUEST['parent_id'])) {
        mkdir("../files/category/" . $_REQUEST['parent_id'], 0777, true);
        mkdir("../files/category/" . $_REQUEST['parent_id'] . "/th/", 0777, true);
    }
    $dirName = "../files/category/" . $_REQUEST['parent_id'] . "/";
    $mfileName = $_REQUEST['mfileName'];
    if (!empty($_FILES["mFile"]["name"])) {
        @unlink("../files/category/" . $_REQUEST['parent_id'] . "/" . $_REQUEST['mfileName']);
        @unlink("../files/category/" . $_REQUEST['parent_id'] . "/" . "/th/" . $_REQUEST['mfileName']);
        $mfileName = $_REQUEST['cat_id'] . "_" . $_FILES["mFile"]["name"];
        $mfileName = str_replace(" ", "_", strtolower($mfileName));
        if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName)) {
            createThumbnail2($dirName, $mfileName, $dirName . "th/", "138", "80");
        }
    }
    mysqli_query($GLOBALS['conn'], "UPDATE category SET cat_title_de = '" . dbStr(trim($_REQUEST['cat_title_de'])) . "',  cat_title_en='" . dbStr(trim($_REQUEST['cat_title_en'])) . "', cat_keyword = '" . dbStr(trim($_REQUEST['cat_keyword'])) . "', cat_description = '" . dbStr(trim($_REQUEST['cat_description'])) . "', cat_image='" . $mfileName . "' WHERE cat_id=" . $_REQUEST['cat_id']) or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
} elseif (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 2) {
        $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM category WHERE cat_id = " . $_REQUEST['cat_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $parent_id = $rsMem->parent_id;
            $cat_title_en = $rsMem->cat_title_en;
            $cat_title_de = $rsMem->cat_title_de;
            $mfileName = $rsMem->cat_image;
            $mfile_path = !empty($rsMem->cat_image) ? $GLOBALS['siteURL'] . "files/category/" . $parent_id . "/" . $rsMem->cat_image : "";
            $cat_keyword = $rsMem->cat_keyword;
            $cat_description = $rsMem->cat_description;
            $formHead = "Update Info";
        }
    } else {
        $cat_title_en = "";
        $cat_title_de = "";
        $mfileName = "";
        $cat_keyword = "";
        $cat_description = "";
        $formHead = "Add New";
    }
}

//--------------Button Active--------------------
if (isset($_REQUEST['btnActive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE category SET cat_status='1' WHERE cat_id = " . $_REQUEST['chkstatus'][$i]);
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
            mysqli_query($GLOBALS['conn'], "UPDATE category SET cat_status='0' WHERE cat_id = " . $_REQUEST['chkstatus'][$i]);
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
                            <?php print($formHead); ?> Sub Category
                        </h2>
                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <div class="row">
                                <?php if ($_REQUEST['action'] == 2) { ?>
                                    <div class="col-md-12 col-12 mt-3">
                                        <img src="<?php print($mfile_path); ?>" width="100%" alt="">
                                    </div>
                                    <div class="col-md-6 col-12 mt-3">
                                        <label for="">Title DE</label>
                                        <input type="text" class="input_style" name="cat_title_de" id="cat_title_de" value="<?php print($cat_title_de); ?>" placeholder="Title">
                                    </div>
                                    <div class="col-md-6 col-12 mt-3">
                                        <label for="">Title EN</label>
                                        <input type="text" class="input_style" name="cat_title_en" id="cat_title_en" value="<?php print($cat_title_en); ?>" placeholder="Title">
                                    </div>

                                    <div class="col-md-6 col-12 mt-3">
                                        <label for="">Keywords (Seprate Each Keyword With ',' (Car, Bus, Bike))</label>
                                        <input type="text" class="input_style" name="cat_keyword" id="cat_keyword" value="<?php print($cat_keyword); ?>" placeholder="Keywords (Seprate Each Keyword With ',' (Car, Bus, Bike))">
                                    </div>
                                    <div class="col-md-6 col-12 mt-3">
                                        <label for="">Meta Description</label>
                                        <input type="text" class="input_style" name="cat_description" id="cat_description" value="<?php print($cat_description); ?>" placeholder="Meta Description">
                                    </div>
                                    <div class="col-md-6 col-12 mt-3">
                                        <label for="">Image ( <span class="text-danger fw-bold">Banner Size must be 1200px x 300x</span> )</label>
                                        <div class="">
                                            <label for="file-upload" class="upload-btn">
                                                <span class="material-icons">cloud_upload</span>
                                                <span>Upload Files</span>
                                            </label>
                                            <input id="file-upload" type="file" class="file-input" name="mFile">
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if ($_REQUEST['action'] == 2) { ?>
                                    <div class="padding_top_bottom">
                                        <button class="btn btn-primary" type="submit" name="btnUpdate" id="btnImport">Update</button>
                                        <input type="hidden" name="mfileName" value="<?php print($mfileName); ?>" />
                                    <?php } else { ?>
                                        <div class="text_align_center padding_top_bottom">
                                            <button class="btn btn-primary" type="submit" name="btnImport" id="btnImport">Upload</button>
                                        <?php } ?>
                                        <button type="button" name="btnBack" class="btn btn-light" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>';">Cancel</button>
                                        </div>
                                    </div>
                            </div>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="table-controls">
                        <h1 class="text-white">Sub Category</h1>
                    </div>
                    <div class="main_table_container">
                        <?php

                        $cat_id = 0;
                        $cat_title = "";
                        $searchQuery = "";

                        if (isset($_REQUEST['cat_id']) && $_REQUEST['cat_id'] > 0) {
                            if (!empty($_REQUEST['cat_title'])) {
                                $cat_id = $_REQUEST['cat_id'];
                                $cat_title = $_REQUEST['cat_title'];
                                $searchQuery = " AND sub_cat.cat_id = '" . $_REQUEST['cat_id'] . "'";
                            }
                        }
                        ?>
                        <form class="row" name="frmCat" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>">
                            <div class=" col-md-2 col-12 mt-2">
                                <label for="" class="text-white">Title</label>
                                <input type="hidden" name="cat_id" id="cat_id" value="<?php print($cat_id); ?>">
                                <input type="text" class="input_style cat_title" name="cat_title" value="<?php print($cat_title); ?>" placeholder="Title:" autocomplete="off" onchange="javascript: frmCat.submit();">
                            </div>
                        </form>
                        <form class="table_responsive" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <table>
                                <thead>
                                    <tr>
                                        <th width="50"><input type="checkbox" name="chkAll" onClick="setAll();"></th>
                                        <th width="100">Banner</th>
                                        <th>Main Title</th>
                                        <th>Title </th>
                                        <th width="50">Status</th>
                                        <th width="50">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $Query = "SELECT sub_cat.cat_id, sub_cat.parent_id, sub_cat.cat_image, cat.cat_title_de AS cat_title, sub_cat.cat_title_de AS sub_cat_title, sub_cat.cat_image_show, sub_cat.cat_showhome, sub_cat.cat_showhome_feature, sub_cat.cat_status FROM category AS sub_cat LEFT OUTER JOIN category AS cat ON cat.group_id = sub_cat.parent_id WHERE sub_cat.parent_id IN ( SELECT main_cat.group_id FROM category AS main_cat WHERE main_cat.parent_id = '0' ORDER BY main_cat.group_id ASC) ".$searchQuery." ";
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
                                            if (!empty($row->cat_image)) {
                                                $image_path = $GLOBALS['siteURL'] . "files/category/" . $row->parent_id . "/" . $row->cat_image;
                                            }
                                    ?>
                                            <tr>
                                                <td><input type="checkbox" name="chkstatus[]" value="<?php print($row->cat_id); ?>"></td>
                                                <td><img src="<?php print($image_path); ?>" width=" <?php print(!empty($row->cat_image) ? 300 : 100); ?>"></td>
                                                <td><?php print($row->cat_title); ?></td>
                                                <td><?php print($row->sub_cat_title); ?></td>
                                                <td>
                                                    <?php
                                                    if ($row->cat_status == 0) {
                                                        echo '<span class="btn btn-danger btn-style-light w-auto">Offline</span>';
                                                    } else {
                                                        echo '<span class="btn btn-success btn-style-light w-auto">Live</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-xs btn-primary btn-style-light w-auto" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "parent_id=" . $row->parent_id . "&cat_id=" . $row->cat_id); ?>';"><span class="material-icons icon material-xs">edit</span></button>
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
    $('input.cat_title').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=cat_title&parent_id=1',
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
            var cat_id = $("#cat_id");
            var cat_title = $("#cat_title");
            $(cat_id).val(ui.item.cat_id);
            $(cat_title).val(ui.item.value);
            //frmCat.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });
</script>

</html>