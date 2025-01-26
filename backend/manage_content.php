<?php
include("../lib/session_head.php");

if (isset($_REQUEST['btnAdd'])) {
    //print_r($_REQUEST);die();
    $Query = "SELECT * FROM contents WHERE cnt_slug ='" . dbStr(trim($_REQUEST['cnt_slug'])) . "'";
    $rs = mysqli_query($GLOBALS['conn'], $Query);
    if (mysqli_num_rows($rs) > 0) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=4");
    } else {
        $cnt_id = getMaximum("contents", "cnt_id");
        $mfileName = "";
        $mfile_mFile_bannerName = "";
        //$dirName = "images/contents/";
        $dirName = "../files/contents/";
        if (!empty($_FILES["mFile"]["name"])) {
            $mfileName = $cnt_id . "_" . $_FILES["mFile"]["name"];
            $mfileName = str_replace(" ", "_", strtolower($mfileName));
            if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName)) {
                createThumbnail2($dirName, $mfileName, $dirName . "th/", "138", "80");
            }
        }
        if (!empty($_FILES["mFile_banner"]["name"])) {
            $mFile_bannerName = $cnt_id . "_" . $_FILES["mFile_banner"]["name"];
            $mfile_bannerName = str_replace(" ", "_", strtolower($mfile_bannerName));
            if (move_uploaded_file($_FILES['mFile_banner']['tmp_name'], $dirName . $mFile_bannerName)) {
                createThumbnail2($dirName, $mFile_bannerName, $dirName . "th/", "138", "80");
            }
        }
        mysqli_query($GLOBALS['conn'], "INSERT INTO contents (cnt_id, footer_id, cnt_section, cnt_slug, cnt_heading_de, cnt_title_de, cnt_details_de, cnt_keywords, cnt_meta_description, cnt_addedby, cnt_cdate, cnt_image, cnt_banner_image) VALUES ('" . $cnt_id . "', '" . dbStr(trim($_REQUEST['footer_id'])) . "', '" . dbStr(trim($_REQUEST['cnt_section'])) . "', '" . dbStr(trim($_REQUEST['cnt_slug'])) . "', '" . dbStr(trim($_REQUEST['cnt_heading_de'])) . "', '" . dbStr(trim($_REQUEST['cnt_title_de'])) . "', '" . dbStr(trim($_REQUEST['cnt_details_de'])) . "', '" . dbStr(trim($_REQUEST['cnt_keywords'])) . "', '" . dbStr(trim($_REQUEST['cnt_meta_description'])) . "', '" . $_SESSION["UserID"] . "', '" . date_time . "', '" . $mfileName . "', '".$mFile_bannerName."')") or die(mysqli_error($GLOBALS['conn']));
        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
    }
} elseif (isset($_REQUEST['btnUpdate'])) {

    $Query = "SELECT * FROM contents WHERE cnt_slug ='" . dbStr(trim($_REQUEST['cnt_slug'])) . "' AND cnt_id != '" . dbStr(trim($_REQUEST['cnt_id'])) . "'";
    //print($Query);die();
    $rs = mysqli_query($GLOBALS['conn'], $Query);
    if (mysqli_num_rows($rs) > 0) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?action=2&cnt_id=" . $_REQUEST['cnt_id'] . "&op=4");
    } else {
        $dirName = "../files/contents/";
        $mfileName = $_REQUEST['mfileName'];
        if (!empty($_FILES["mFile"]["name"])) {
            @unlink("../files/contents/" . $_REQUEST['mfileName']);
            @unlink("../files/contents/th/" . $_REQUEST['mfileName']);
            $mfileName = $_REQUEST['cnt_id'] . "_" . $_FILES["mFile"]["name"];
            $mfileName = str_replace(" ", "_", strtolower($mfileName));
            if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName)) {
                createThumbnail2($dirName, $mfileName, $dirName . "th/", "138", "80");
            }
        }
        $mFile_bannerName = $_REQUEST['mFile_bannerName'];
        if (!empty($_FILES["mFile_banner"]["name"])) {
            @unlink("../files/contents/" . $_REQUEST['mFile_bannerName']);
            @unlink("../files/contents/th/" . $_REQUEST['mFile_bannerName']);
            $mFile_bannerName = $_REQUEST['cnt_id'] . "_" . $_FILES["mFile_banner"]["name"];
            $mFile_bannerName = str_replace(" ", "_", strtolower($mFile_bannerName));
            if (move_uploaded_file($_FILES['mFile_banner']['tmp_name'], $dirName . $mFile_bannerName)) {
                createThumbnail2($dirName, $mFile_bannerName, $dirName . "th/", "138", "80");
            }
        }
        mysqli_query($GLOBALS['conn'], "UPDATE contents SET footer_id = '" . dbStr(trim($_REQUEST['footer_id'])) . "',  cnt_section = '" . dbStr(trim($_REQUEST['cnt_section'])) . "', cnt_slug = '" . dbStr(trim($_REQUEST['cnt_slug'])) . "', cnt_heading_de = '" . dbStr(trim($_REQUEST['cnt_heading_de'])) . "', cnt_title_de = '" . dbStr(trim($_REQUEST['cnt_title_de'])) . "', cnt_details_de = '" . dbStr(trim($_REQUEST['cnt_details_de'])) . "', cnt_keywords = '" . dbStr(trim($_REQUEST['cnt_keywords'])) . "', cnt_meta_description = '" . dbStr(trim($_REQUEST['cnt_meta_description'])) . "', cnt_updatedby = '" . $_SESSION["UserID"] . "', cnt_udate = '" . date_time . "', cnt_image = '" . $mfileName . "', cnt_banner_image = '".$mFile_bannerName."' WHERE cnt_id= '" . $_REQUEST['cnt_id'] . "' ") or die(mysqli_error($GLOBALS['conn']));
        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
    }
} elseif (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 2) {
        $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM contents WHERE cnt_id = " . $_REQUEST['cnt_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $footer_id = $rsMem->footer_id;
            $cnt_section = $rsMem->cnt_section;
            $cnt_slug = $rsMem->cnt_slug;
            $cnt_title_de = $rsMem->cnt_title_de;
            $cnt_heading_de = $rsMem->cnt_heading_de;
            $cnt_details_de = $rsMem->cnt_details_de;
            $cnt_keywords = $rsMem->cnt_keywords;
            $cnt_meta_description = $rsMem->cnt_meta_description;
            $mfileName = $rsMem->cnt_image;
            $mfile_path = !empty($rsMem->cnt_image) ? $GLOBALS['siteURL'] . "files/contents/" . $rsMem->cnt_image : "";
            $mfile_bannerName = $rsMem->cnt_banner_image;
            $mfile_bannerName_path = !empty($rsMem->cnt_banner_image) ? $GLOBALS['siteURL'] . "files/contents/" . $rsMem->cnt_banner_image : "";
            $formHead = "Update Info";
        }
    } else {
        $footer_id = 0;
        $cnt_section = 0;
        $cnt_slug = "";
        $cnt_title_de = "";
        $cnt_heading_de = "";
        $cnt_details_de = "";
        $cnt_keywords = "";
        $cnt_meta_description = "";
        $mfileName = "";
        $mfile_path = "";
        $mfile_bannerName = "";
        $mfile_bannerName_path = "";
        $formHead = "Add New";
    }
}

//--------------Button Active--------------------
if (isset($_REQUEST['btnActive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE contents SET cnt_status='1' WHERE cnt_id = " . $_REQUEST['chkstatus'][$i]);
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
            mysqli_query($GLOBALS['conn'], "UPDATE contents SET cnt_status='0' WHERE cnt_id = " . $_REQUEST['chkstatus'][$i]);
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

            mysqli_query($GLOBALS['conn'], "DELETE FROM contents WHERE cnt_id = " . $_REQUEST['chkstatus'][$i]) or die(mysqli_error($_REQUEST['conn']));
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
                            <?php print($formHead); ?> Content
                        </h2>
                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <div class="row">
                                <?php if(!empty($mfile_bannerName_path)) { ?>
                                <div class="col-md-12 col-12 mt-3">
                                    <label for="">Banner Image</label>
                                    <img src="<?php print($mfile_bannerName_path); ?>" width="100%" alt="">
                                </div>
                                <?php } ?>
                                <?php if(!empty($mfile_path)) { ?>
                                <div class="col-md-12 col-12 mt-3">
                                    <label for="">Image</label>
                                    <img src="<?php print($mfile_path); ?>" width="30%" alt="">
                                </div>
                                <?php } ?>
                                <div class="col-md-3 col-12 mt-3">
                                    <label for="">Footer Section</label>
                                    <select class="input_style" name="footer_id" id="footer_id">
                                        <option value="0">N/A</option>
                                        <?php FillSelected2("footer", "footer_id", "footer_title_de AS footer_title", $footer_id, "footer_status > 0"); ?>
                                    </select>
                                </div>
                                <div class="col-md-3 col-12 mt-3">
                                    <label for="">Section</label>
                                    <select class="input_style" name="cnt_section" id="cnt_section">
                                        <option value="0" <?php print(($cnt_section == 0) ? 'selected' : ''); ?>>No</option>
                                        <option value="1" <?php print(($cnt_section == 1) ? 'selected' : ''); ?>>Yes</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Slug (Like: abc or abc_abc)</label>
                                    <input type="text" required class="input_style" name="cnt_slug" id="cnt_slug" value="<?php print($cnt_slug); ?>" placeholder="Slug (Like: abc or abc_abc)">
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Title</label>
                                    <input type="text" required class="input_style" name="cnt_title_de" id="cnt_title_de" value="<?php print($cnt_title_de); ?>" placeholder="Title">
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Heading</label>
                                    <input type="text" class="input_style" name="cnt_heading_de" id="cnt_heading_de" value="<?php print($cnt_heading_de); ?>" placeholder="Heading">
                                </div>
                                <div class="col-md-12 col-12 mt-3">
                                    <label for="">Meta Keyword (Seprate Each Keyword With ',' (Car, Bus, Bike))</label>
                                    <textarea rows="5" type="text" class="input_style" name="cnt_keywords" id="cnt_keywords" placeholder="Meta Keyword"> <?php print($cnt_keywords); ?> </textarea>
                                </div>
                                <div class="col-md-12 col-12 mt-3">
                                    <label for="">Meta Description (Seprate Each Description With ',' (Car, Bus, Bike))</label>
                                    <textarea rows="5" type="text" class="input_style" name="cnt_meta_description" id="cnt_meta_description" placeholder="Meta Description"> <?php print($cnt_meta_description); ?> </textarea>
                                </div>
                                <div class="col-md-12 col-12 mt-3">
                                    <label for="">Detail</label>
                                    <textarea rows="5" type="text" class="input_style ckeditor_one" name="cnt_details_de" id="cnt_details_de" placeholder="Meta Keyword"> <?php print($cnt_details_de); ?> </textarea>
                                </div>
                                <div class="col-md-12 col-12 mt-3">
                                    <label for="">Banner</label>
                                    <div class="">
                                        <label for="file-upload" class="upload-btn">
                                            <span class="material-icons">cloud_upload</span>
                                            <span>Upload Files</span>
                                        </label>
                                        <input id="file-upload" type="file" class="file-input" name="mFile_banner">
                                    </div>
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
                                        <input type="hidden" name="mfile_bannerName" value="<?php print($mfile_bannerName); ?>" />
                                        <input type="hidden" name="mfileName" value="<?php print($mfileName); ?>" />
                                    <?php } ?>
                                    <button type="button" name="btnBack" class="btn btn-light" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>';">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="table-controls">
                        <h1 class="text-white">Content Management</h1>
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
                                        <th>Footer Section</th>
                                        <th>Slug</th>
                                        <th>Title</th>
                                        <th>Meta Keyword</th>
                                        <th>Meta Description</th>
                                        <th width="50">Status</th>
                                        <th width="120">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $Query = "SELECT cnt.cnt_id, fot.footer_title_de AS footer_title, cnt.cnt_slug, cnt.cnt_title_de AS cnt_title, cnt.cnt_keywords, cnt.cnt_meta_description, cnt.cnt_status, cnt.cnt_section  FROM contents AS cnt LEFT OUTER JOIN footer AS fot ON fot.footer_id = cnt.footer_id";
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
                                    ?>
                                            <tr>
                                                <td><input type="checkbox" name="chkstatus[]" value="<?php print($row->cnt_id); ?>"></td>
                                                <td><?php print($row->footer_title); ?></td>
                                                <td><?php print($row->cnt_slug); ?></td>
                                                <td><?php print($row->cnt_title); ?></td>
                                                <td><?php print($row->cnt_keywords); ?></td>
                                                <td><?php print($row->cnt_meta_description); ?></td>
                                                <td>
                                                    <?php
                                                    if ($row->cnt_status == 0) {
                                                        echo '<span class="btn btn-danger btn-style-light w-auto">Offline</span>';
                                                    } else {
                                                        echo '<span class="btn btn-success btn-style-light w-auto">Live</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-xs btn-primary btn-style-light w-auto" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "cnt_id=" . $row->cnt_id); ?>';"><span class="material-icons icon material-xs">edit</span></button>
                                                    <?php if ($row->cnt_section == 1) {?>
                                                    <button type="button" class="btn btn-xs btn-warning btn-style-light w-auto" title="Section" onClick="javascript: window.location = '<?php print("manage_content_section.php?cnt_id=" . $row->cnt_id); ?>';"><span class="material-icons icon material-xs">segment</span></button>
                                                    <?php } ?>
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
                                    <input type="submit" name="btnActive" value="Active" class="btn btn-primary btn-style-light w-auto">
                                </div>
                                <div class=" col-md-1 col-12 mt-2">
                                    <input type="submit" name="btnInactive" value="In Active" class="btn btn-warning btn-style-light w-auto">
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

</html>