<?php
include("../lib/session_head.php");

$cnt_id = 0;
if (isset($_REQUEST['cnt_id']) && $_REQUEST['cnt_id'] > 0) {
    $cnt_id = $_REQUEST['cnt_id'];
    $qryStrURL = "cnt_id=" . $_REQUEST['cnt_id'] . "&";
}

if (isset($_REQUEST['btnAdd'])) {
    //print_r($_REQUEST);die();
    //print_r($_FILES);die();
    $table_field = "";
    $table_value = "";
    if (isset($_REQUEST['csec_year']) && !empty($_REQUEST['csec_year'])) {
        $table_field .= ", csec_year";
        $table_value .= ", '" . dbStr(trim($_REQUEST['csec_year'])) . "'";
    }
    
    if (isset($_REQUEST['csec_heading_one_de']) && !empty($_REQUEST['csec_heading_one_de'])) {
        $table_field .= ", csec_heading_one_de";
        $table_value .= ", '" . dbStr(trim($_REQUEST['csec_heading_one_de'])) . "'";
    }
    
    if (isset($_REQUEST['csec_heading_one_en']) && !empty($_REQUEST['csec_heading_one_en'])) {
        $table_field .= ", csec_heading_one_en";
        $table_value .= ", '" . dbStr(trim($_REQUEST['csec_heading_one_en'])) . "'";
    }
    
    if (isset($_REQUEST['csec_content_one_de']) && !empty($_REQUEST['csec_content_one_de'])) {
        $table_field .= ", csec_content_one_de";
        $table_value .= ", '" . dbStr(trim($_REQUEST['csec_content_one_de'])) . "'";
    }
    
    if (isset($_REQUEST['csec_content_one_en']) && !empty($_REQUEST['csec_content_one_en'])) {
        $table_field .= ", csec_content_one_en";
        $table_value .= ", '" . dbStr(trim($_REQUEST['csec_content_one_en'])) . "'";
    }
    
    if (isset($_REQUEST['csec_heading_two_de']) && !empty($_REQUEST['csec_heading_two_de'])) {
        $table_field .= ", csec_heading_two_de";
        $table_value .= ", '" . dbStr(trim($_REQUEST['csec_heading_two_de'])) . "'";
    }
    
    if (isset($_REQUEST['csec_heading_two_en']) && !empty($_REQUEST['csec_heading_two_en'])) {
        $table_field .= ", csec_heading_two_en";
        $table_value .= ", '" . dbStr(trim($_REQUEST['csec_heading_two_en'])) . "'";
    }
    
    if (isset($_REQUEST['csec_content_two_de']) && !empty($_REQUEST['csec_content_two_de'])) {
        $table_field .= ", csec_content_two_de";
        $table_value .= ", '" . dbStr(trim($_REQUEST['csec_content_two_de'])) . "'";
    }
    
    if (isset($_REQUEST['csec_content_two_en']) && !empty($_REQUEST['csec_content_two_en'])) {
        $table_field .= ", csec_content_two_en";
        $table_value .= ", '" . dbStr(trim($_REQUEST['csec_content_two_en'])) . "'";
    }
    
    $csec_id = getMaximum("content_sections", "csec_id");

    if (isset($_FILES["mFile_banner"]["name"]) && !empty($_FILES["mFile_banner"]["name"])) {
        if (!file_exists("../files/contents/" . $_REQUEST['cnt_id'])) {
            mkdir("../files/contents/" . $_REQUEST['cnt_id'], 0777, true);
            mkdir("../files/contents/" . $_REQUEST['cnt_id'] . "/th/", 0777, true);
        }

        $mFile_bannerName = "";
        $dirName = "../files/contents/" . $_REQUEST['cnt_id'] . "/";
        if (!empty($_FILES["mFile_banner"]["name"])) {
            $mFile_bannerName = $csec_id . "_" . $_FILES["mFile_banner"]["name"];
            $mFile_bannerName = str_replace(" ", "_", strtolower($mFile_bannerName));
            if (move_uploaded_file($_FILES['mFile_banner']['tmp_name'], $dirName . "/" . $mFile_bannerName)) {
                createThumbnail2($dirName, $mFile_bannerName, $dirName . "th/", "200", "200");
            }
        }

        $table_field .= ", csec_banner_image";
        $table_value .= ", '" . dbStr($mFile_bannerName) . "'";
    }

    if (isset($_FILES["mFile_one"]["name"]) && !empty($_FILES["mFile_one"]["name"])) {
        if (!file_exists("../files/contents/" . $_REQUEST['cnt_id'])) {
            mkdir("../files/contents/" . $_REQUEST['cnt_id'], 0777, true);
            mkdir("../files/contents/" . $_REQUEST['cnt_id'] . "/th/", 0777, true);
        }

        $mFile_oneName = "";
        $dirName = "../files/contents/" . $_REQUEST['cnt_id'] . "/";
        if (!empty($_FILES["mFile_one"]["name"])) {
            $mFile_oneName = $csec_id . "_" . $_FILES["mFile_one"]["name"];
            $mFile_oneName = str_replace(" ", "_", strtolower($mFile_oneName));
            if (move_uploaded_file($_FILES['mFile_one']['tmp_name'], $dirName . "/" . $mFile_oneName)) {
                createThumbnail2($dirName, $mFile_oneName, $dirName . "th/", "200", "200");
            }
        }

        $table_field .= ", csec_image_one";
        $table_value .= ", '" . dbStr($mFile_oneName) . "'";
    }
    
    if (isset($_FILES["mFile_two"]["name"]) && !empty($_FILES["mFile_two"]["name"])) {
        if (!file_exists("../files/contents/" . $_REQUEST['cnt_id'])) {
            mkdir("../files/contents/" . $_REQUEST['cnt_id'], 0777, true);
            mkdir("../files/contents/" . $_REQUEST['cnt_id'] . "/th/", 0777, true);
        }

        $mFile_twoName = "";
        $dirName = "../files/contents/" . $_REQUEST['cnt_id'] . "/";
        if (!empty($_FILES["mFile_two"]["name"])) {
            $mFile_twoName = $csec_id . "_" . $_FILES["mFile_two"]["name"];
            $mFile_twoName = str_replace(" ", "_", strtolower($mFile_twoName));
            if (move_uploaded_file($_FILES['mFile_two']['tmp_name'], $dirName . "/" . $mFile_twoName)) {
                createThumbnail2($dirName, $mFile_twoName, $dirName . "th/", "200", "200");
            }
        }

        $table_field .= ", csec_image_two";
        $table_value .= ", '" . dbStr($mFile_twoName) . "'";
    }
    //echo "INSERT INTO content_sections (csec_id, cnt_id, cst_id, " . $table_field . ") VALUES ('" . $csec_id . "', '" . dbStr(trim($_REQUEST['cnt_id'])) . "', '" . dbStr(trim($_REQUEST['cst_id'])) . "' " . $table_value . ")";die();
    mysqli_query($GLOBALS['conn'], "INSERT INTO content_sections (csec_id, cnt_id, cst_id " . $table_field . ") VALUES ('" . $csec_id . "', '" . dbStr(trim($_REQUEST['cnt_id'])) . "', '" . dbStr(trim($_REQUEST['cst_id'])) . "' " . $table_value . ")") or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
} elseif (isset($_REQUEST['btnUpdate'])) {

    $table_update_data = "";
    if (isset($_REQUEST['csec_year']) && !empty($_REQUEST['csec_year'])) {
        $table_update_data .= ", csec_year = '" . dbStr(trim($_REQUEST['csec_year'])) . "'";
    }
    if (isset($_REQUEST['csec_heading_one_de']) && !empty($_REQUEST['csec_heading_one_de'])) {
        $table_update_data .= ", csec_heading_one_de = '" . dbStr(trim($_REQUEST['csec_heading_one_de'])) . "'";
    }

    if (isset($_REQUEST['csec_heading_one_en']) && !empty($_REQUEST['csec_heading_one_en'])) {
        $table_update_data .= ", csec_heading_one_en = '" . dbStr(trim($_REQUEST['csec_heading_one_en'])) . "'";
    }

    if (isset($_REQUEST['csec_content_one_de']) && !empty($_REQUEST['csec_content_one_de'])) {
        $table_update_data .= ", csec_content_one_de = '" . dbStr(trim($_REQUEST['csec_content_one_de'])) . "'";
    }

    if (isset($_REQUEST['csec_content_one_en']) && !empty($_REQUEST['csec_content_one_en'])) {
        $table_update_data .= ", csec_content_one_en = '" . dbStr(trim($_REQUEST['csec_content_one_en'])) . "'";
    }

    if (isset($_REQUEST['csec_heading_two_de']) && !empty($_REQUEST['csec_heading_two_de'])) {
        $table_update_data .= ", csec_heading_two_de = '" . dbStr(trim($_REQUEST['csec_heading_two_de'])) . "'";
    }

    if (isset($_REQUEST['csec_heading_two_en']) && !empty($_REQUEST['csec_heading_two_en'])) {
        $table_update_data .= ", csec_heading_two_en = '" . dbStr(trim($_REQUEST['csec_heading_two_en'])) . "'";
    }

    if (isset($_REQUEST['csec_content_two_de']) && !empty($_REQUEST['csec_content_two_de'])) {
        $table_update_data .= ", csec_content_two_de = '" . dbStr(trim($_REQUEST['csec_content_two_de'])) . "'";
    }

    if (isset($_REQUEST['csec_content_two_en']) && !empty($_REQUEST['csec_content_two_en'])) {
        $table_update_data .= ", csec_content_two_en = '" . dbStr(trim($_REQUEST['csec_content_two_en'])) . "'";
    }
    
    $mFile_bannerName = $_REQUEST['mFile_bannerName'];
    if (isset($_FILES["mFile_banner"]["name"]) && !empty($_FILES["mFile_banner"]["name"])) {
        if (!file_exists("../files/contents/" . $_REQUEST['cnt_id'])) {
            mkdir("../files/contents/" . $_REQUEST['cnt_id'], 0777, true);
            mkdir("../files/contents/" . $_REQUEST['cnt_id'] . "/th/", 0777, true);
        }

        $mFile_bannerName = "";
        $dirName = "../files/contents/" . $_REQUEST['cnt_id'] . "/";
        if (!empty($_FILES["mFile_banner"]["name"])) {
            @unlink($dirName . $_REQUEST['mFile_bannerName']);
            @unlink($dirName . "th/" . $_REQUEST['mFile_bannerName']);
            $mFile_bannerName = $csec_id . "_" . $_FILES["mFile_banner"]["name"];
            $mFile_bannerName = str_replace(" ", "_", strtolower($mFile_bannerName));
            if (move_uploaded_file($_FILES['mFile_banner']['tmp_name'], $dirName . "/" . $mFile_bannerName)) {
                createThumbnail2($dirName, $mFile_bannerName, $dirName . "th/", "200", "200");
            }
        }
    }
    
    $mFile_oneName = $_REQUEST['mFile_oneName'];
    if (isset($_FILES["mFile_one"]["name"]) && !empty($_FILES["mFile_one"]["name"])) {
        if (!file_exists("../files/contents/" . $_REQUEST['cnt_id'])) {
            mkdir("../files/contents/" . $_REQUEST['cnt_id'], 0777, true);
            mkdir("../files/contents/" . $_REQUEST['cnt_id'] . "/th/", 0777, true);
        }

        $mFile_oneName = "";
        $dirName = "../files/contents/" . $_REQUEST['cnt_id'] . "/";
        if (!empty($_FILES["mFile_one"]["name"])) {
            @unlink($dirName . $_REQUEST['mFile_oneName']);
            @unlink($dirName . "th/" . $_REQUEST['mFile_oneName']);
            $mFile_oneName = $csec_id . "_" . $_FILES["mFile_one"]["name"];
            $mFile_oneName = str_replace(" ", "_", strtolower($mFile_oneName));
            if (move_uploaded_file($_FILES['mFile_one']['tmp_name'], $dirName . "/" . $mFile_oneName)) {
                createThumbnail2($dirName, $mFile_oneName, $dirName . "th/", "200", "200");
            }
        }
    }

    $mFile_twoName = $_REQUEST['mFile_twoName'];
    if (isset($_FILES["mFile_two"]["name"]) && !empty($_FILES["mFile_two"]["name"])) {
        if (!file_exists("../files/contents/" . $_REQUEST['cnt_id'])) {
            mkdir("../files/contents/" . $_REQUEST['cnt_id'], 0777, true);
            mkdir("../files/contents/" . $_REQUEST['cnt_id'] . "/th/", 0777, true);
        }

        $mFile_twoName = "";
        $dirName = "../files/contents/" . $_REQUEST['cnt_id'] . "/";
        if (!empty($_FILES["mFile_two"]["name"])) {
            @unlink($dirName . $_REQUEST['mFile_twoName']);
            @unlink($dirName . "th/" . $_REQUEST['mFile_twoName']);
            $mFile_twoName = $csec_id . "_" . $_FILES["mFile_two"]["name"];
            $mFile_twoName = str_replace(" ", "_", strtolower($mFile_twoName));
            if (move_uploaded_file($_FILES['mFile_two']['tmp_name'], $dirName . "/" . $mFile_twoName)) {
                createThumbnail2($dirName, $mFile_twoName, $dirName . "th/", "200", "200");
            }
        }
    }

    mysqli_query($GLOBALS['conn'], "UPDATE content_sections SET csec_banner_image = '" . dbStr(trim($mFile_bannerName)) . "', csec_image_one = '" . dbStr(trim($mFile_oneName)) . "', csec_image_two = '" . dbStr(trim($mFile_twoName)) . "' " . $table_update_data . " WHERE csec_id= '" . $_REQUEST['csec_id'] . "' ") or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
} elseif (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 2) {
        $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM content_sections WHERE csec_id = " . $_REQUEST['csec_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $cnt_id = $rsMem->cnt_id;
            $cst_id = $rsMem->cst_id;
            $csec_year = $rsMem->csec_year;
            $csec_heading_one_de = $rsMem->csec_heading_one_de;
            $csec_heading_one_en = $rsMem->csec_heading_one_en;
            $csec_content_one_de = $rsMem->csec_content_one_de;
            $csec_content_one_en = $rsMem->csec_content_one_en;
            $csec_heading_two_de = $rsMem->csec_heading_two_de;
            $csec_heading_two_en = $rsMem->csec_heading_two_en;
            $csec_content_two_de = $rsMem->csec_content_two_de;
            $csec_content_two_en = $rsMem->csec_content_two_en;
            $mFile_bannerName = $rsMem->csec_banner_image;
            $mfile_bannerpath = !empty($rsMem->csec_banner_image) ? $GLOBALS['siteURL'] . "files/contents/" . $rsMem->cnt_id . "/" . $rsMem->csec_banner_image : "";
            $mFile_oneName = $rsMem->csec_image_one;
            $mfile_onepath = !empty($rsMem->csec_image_one) ? $GLOBALS['siteURL'] . "files/contents/" . $rsMem->cnt_id . "/" . $rsMem->csec_image_one : "";
            $mFile_twoName = $rsMem->csec_image_two;
            $mfile_twopath = !empty($rsMem->csec_image_two) ? $GLOBALS['siteURL'] . "files/contents/" . $rsMem->cnt_id . "/" . $rsMem->csec_image_two : "";
            $formHead = "Update Info";
        }
    } else {
        $cnt_id = "";
        $cst_id = "";
        $csec_year = "";
        $csec_heading_one_de = "";
        $csec_heading_one_en = "";
        $csec_content_one_de = "";
        $csec_content_one_en = "";
        $csec_heading_two_de = "";
        $csec_heading_two_en = "";
        $csec_content_two_de = "";
        $csec_content_two_en = "";
        $mFile_bannerName = "";
        $mfile_bannerpath = "";
        $mFile_oneName = "";
        $mfile_onepath = "";
        $mFile_twoName = "";
        $mfile_twopath = "";

        $formHead = "Add New";
    }
}

//--------------Button Active--------------------
if (isset($_REQUEST['btnActive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE content_sections SET csec_status='1' WHERE csec_id = " . $_REQUEST['chkstatus'][$i]);
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
            mysqli_query($GLOBALS['conn'], "UPDATE content_sections SET csec_status='0' WHERE csec_id = " . $_REQUEST['chkstatus'][$i]);
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
    if (isset($_REQUEST['csec_id'])) {
        for ($i = 0; $i < count($_REQUEST['csec_id']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE content_sections SET cst_orderby='" . $_REQUEST['cst_orderby'][$i] . "' WHERE csec_id = " . $_REQUEST['csec_id'][$i]);
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

            DeleteFileWithThumb("csec_image_one", "content_sections", "csec_id ", $_REQUEST['chkstatus'][$i], "../files/contents/" . $_REQUEST['cnt_id'] . "/th/", "EMPTY");
            DeleteFileWithThumb("csec_image_one", "content_sections", "csec_id ", $_REQUEST['chkstatus'][$i], "../files/contents/" . $_REQUEST['cnt_id'] . "/", "EMPTY");
            
            DeleteFileWithThumb("csec_image_two", "content_sections", "csec_id ", $_REQUEST['chkstatus'][$i], "../files/contents/" . $_REQUEST['cnt_id'] . "/th/", "EMPTY");
            DeleteFileWithThumb("csec_image_two", "content_sections", "csec_id ", $_REQUEST['chkstatus'][$i], "../files/contents/" . $_REQUEST['cnt_id'] . "/", "EMPTY");

            DeleteFileWithThumb("csec_banner_image", "content_sections", "csec_id ", $_REQUEST['chkstatus'][$i], "../files/contents/" . $_REQUEST['cnt_id'] . "/th/", "EMPTY");
            DeleteFileWithThumb("csec_banner_image", "content_sections", "csec_id ", $_REQUEST['chkstatus'][$i], "../files/contents/" . $_REQUEST['cnt_id'] . "/", "EMPTY");
            mysqli_query($GLOBALS['conn'], "DELETE FROM content_sections WHERE csec_id = " . $_REQUEST['chkstatus'][$i]) or die(mysqli_error($_REQUEST['conn']));
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
                            <?php print($formHead); ?> Content Section
                        </h2>
                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <div class="row">
                                <?php if (!empty($mfile_bannerpath)) { ?>
                                    <div class="col-md-12 col-12 mt-3">
                                        <label for="">Banner Image</label>
                                        <img src="<?php print($mfile_bannerpath); ?>" width="100%" alt="">
                                    </div>
                                <?php }
                                if (!empty($mfile_onepath)) { ?>
                                    <div class="col-md-12 col-12 mt-3">
                                        <label for="">Right Image</label><br>
                                        <img src="<?php print($mfile_onepath); ?>" width="30%" alt="">
                                        <?php if (!empty($mfile_twopath)) { ?>
                                        <img src="<?php print($mfile_twopath); ?>" width="30%" alt="">
                                        <?php } ?>
                                    </div>
                                <?php }
                                if ($_REQUEST['action']  == 2) {
                                    if ($cst_id == 1) {
                                        include("includes/cst_one.php");
                                    } elseif ($cst_id == 2) {
                                        include("includes/cst_two.php");
                                    } elseif ($cst_id == 3) {
                                        include("includes/cst_three.php");
                                    } elseif ($cst_id == 4) {
                                        include("includes/cst_four.php");
                                    } elseif ($cst_id == 5) {
                                        include("includes/cst_five.php");
                                    } elseif ($cst_id == 6) {
                                        include("includes/cst_six.php");
                                    } elseif ($cst_id == 7) {
                                        include("includes/cst_seven.php");
                                    }
                                } else { ?>
                                    <div class="col-md-6 col-12 mt-3">
                                        <label for="">Footer Section</label>
                                        <select class="input_style cst_id" name="cst_id" id="cst_id">
                                            <?php FillSelected2("content_section_template", "cst_id", "cst_title", $cnt_id, "cst_status > 0"); ?>
                                        </select>
                                    </div>
                                    <div class="row" id="content_section_template">

                                    </div>
                                <?php } ?>
                                <div class="col-md-12 col-12 mt-3">
                                    <button class="btn btn-primary" type="submit" name="<?php print(($_REQUEST['action'] == 1) ? 'btnAdd' : 'btnUpdate'); ?>">Upload</button>
                                    <?php if ($_REQUEST['action'] == 2) { ?>
                                        <input type="hidden" name="mFile_bannerName" value="<?php print($mFile_bannerName); ?>" />
                                        <input type="hidden" name="mFile_oneName" value="<?php print($mFile_oneName); ?>" />
                                        <input type="hidden" name="mFile_twoName" value="<?php print($mFile_twoName); ?>" />
                                    <?php } ?>
                                    <button type="button" name="btnBack" class="btn btn-light" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>';">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="table-controls">
                        <h1 class="text-white">Content Section</h1>
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
                                        <th>Banner</th>
                                        <th>Image</th>
                                        <th>Page</th>
                                        <th>Heading</th>
                                        <!--<th>Content</th>-->
                                        <th width="100">Order By </th>
                                        <th width="50">Status</th>
                                        <th width="50">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $Query = "SELECT cs.csec_id, cnt.cnt_title_de AS cnt_title,  cs.cnt_id, cs.csec_heading_one_de AS csec_heading_one, cs.csec_content_one_de AS csec_content_one, cs.csec_banner_image, cs.csec_image_one, cs.csec_image_two, cs.cst_orderby, cs.csec_status FROM content_sections AS cs LEFT OUTER JOIN contents AS cnt ON cnt.cnt_id = cs.cnt_id WHERE cs.cnt_id = '" . $_REQUEST['cnt_id'] . "'  ORDER BY cs.cst_orderby ASC";
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
                                            $banner_image = "";
                                            if (!empty($row->csec_banner_image)) {
                                                $banner_image = '<div class="popup_container" style="width:200px"><div class="container__img-holder"><img src="' . $GLOBALS['siteURL'] . "files/contents/" . $row->cnt_id . "/" . $row->csec_banner_image . '"></div></div>';
                                            }
                                            $contents_image = "";
                                            if (!empty($row->csec_image_one)) {
                                                $contents_image .= '<div class="popup_container" style="width:100px"><div class="container__img-holder"><img src="' . $GLOBALS['siteURL'] . "files/contents/" . $row->cnt_id . "/" . $row->csec_image_one . '"></div></div>';
                                            }
                                            
                                            if (!empty($row->csec_image_two)) {
                                                $contents_image .= '<div class="popup_container" style="width:100px"><div class="container__img-holder"><img src="' . $GLOBALS['siteURL'] . "files/contents/" . $row->cnt_id . "/" . $row->csec_image_two . '"></div></div>';
                                            }
                                    ?>
                                            <tr>
                                                <td><input type="checkbox" name="chkstatus[]" value="<?php print($row->csec_id); ?>"></td>
                                                <td><?php print($banner_image); ?></td>
                                                <td><?php print($contents_image); ?></td>
                                                <td><?php print($row->cnt_title); ?></td>
                                                <td><?php print($row->csec_heading_one); ?></td>
                                                <!--<td><?php print($row->csec_content_one); ?></td>-->
                                                <td>
                                                    <input type="hidden" name="csec_id[]" id="csec_id" value="<?php print($row->csec_id); ?>">
                                                    <input type="number" class="input_style" name="cst_orderby[]" id="cst_orderby" value="<?php print($row->cst_orderby); ?>">
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($row->csec_status == 0) {
                                                        echo '<span class="btn btn-danger btn-style-light w-auto">Offline</span>';
                                                    } else {
                                                        echo '<span class="btn btn-success btn-style-light w-auto">Live</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-xs btn-primary btn-style-light w-auto" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "csec_id=" . $row->csec_id); ?>';"><span class="material-icons icon material-xs">edit</span></button>
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
                                <div class=" col-md-1 col-12 mt-2">
                                    <input type="submit" name="btnDelete" value="Delete" class="btn btn-danger btn-style-light w-auto" onclick="return confirm('Are you sure you want to delete selected item(s)?');">
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
    $(window).load(function() {
        $(".cst_id").trigger("click");
    });
    $(".cst_id").on("click", function() {
        //console.log("cst_id");
        let htmlContent = "";
        const targetElement = $('#content_section_template');
        targetElement.empty();
        let cst_id = $('#cst_id').val();
        console.log("cst_id: " + cst_id);
        if (cst_id == 1) {
            htmlContent = `
        <div class="col-md-12 col-12 mt-3">
            <label for="">Detail DE</label>
            <textarea rows="5" type="text" class="input_style ckeditor_one_de" name="csec_content_one_de" id="csec_content_one_de" placeholder="Detail DE"></textarea>
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">Detail EN</label>
            <textarea rows="5" type="text" class="input_style ckeditor_one_en" name="csec_contents_one_en" id="csec_contents_one_en" placeholder="Detail EN"></textarea>
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">File</label>
            <div class="">
                <label for="file-upload" class="upload-btn">
                    <span class="material-icons">cloud_upload</span>
                    <span>Upload Files</span>
                </label>
                <input id="file-upload" type="file" class="file-input" name="mFile_one">
            </div>
        </div>
    `;
        } else if (cst_id == 2) {
            htmlContent = `
        <div class="col-md-12 col-12 mt-3">
            <label for="">Year</label>
            <input type="number" onKeyPress="if(this.value.length==4) return false;" class="input_style" name="csec_year" id="csec_year" value="" placeholder="Year">
        </div>
        <div class="col-md-6 col-12 mt-3">
            <label for="">Heading DE</label>
            <input type="text" required class="input_style" name="csec_heading_one_de" id="csec_heading_one_de" value="" placeholder="Heading DE">
        </div>
        <div class="col-md-6 col-12 mt-3">
            <label for="">Heading EN</label>
            <input type="text" class="input_style" name="csec_heading_one_en" id="csec_heading_one_en" value="" placeholder="Heading EN">
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">Detail DE</label>
            <textarea rows="5" type="text" class="input_style ckeditor_one_de" name="csec_content_one_de" id="csec_content_one_de" placeholder="Detail DE"></textarea>
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">Detail EN</label>
            <textarea rows="5" type="text" class="input_style ckeditor_one_en" name="csec_contents_one_en" id="csec_contents_one_en" placeholder="Detail EN"></textarea>
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">File</label>
            <div class="">
                <label for="file-upload" class="upload-btn">
                    <span class="material-icons">cloud_upload</span>
                    <span>Upload Files</span>
                </label>
                <input id="file-upload" type="file" class="file-input" name="mFile_one">
            </div>
        </div>
    `;
        } else if (cst_id == 3) {
            htmlContent = `
        <div class="col-md-6 col-12 mt-3">
            <label for="">Heading DE</label>
            <input type="text" required class="input_style" name="csec_heading_one_de" id="csec_heading_one_de" value="" placeholder="Heading DE">
        </div>
        <div class="col-md-6 col-12 mt-3">
            <label for="">Heading EN</label>
            <input type="text" class="input_style" name="csec_heading_one_en" id="csec_heading_one_en" value="" placeholder="Heading EN">
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">Detail DE</label>
            <textarea rows="5" type="text" class="input_style ckeditor_one_de" name="csec_content_one_de" id="csec_content_one_de" placeholder="Detail DE"></textarea>
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">Detail EN</label>
            <textarea rows="5" type="text" class="input_style ckeditor_one_en" name="csec_contents_one_en" id="csec_contents_one_en" placeholder="Detail EN"></textarea>
        </div>
    `;
        } else if (cst_id == 4) {
            htmlContent = `
        <div class="col-md-6 col-12 mt-3">
            <label for="">Heading DE</label>
            <input type="text" required class="input_style" name="csec_heading_one_de" id="csec_heading_one_de" value="" placeholder="Heading DE">
        </div>
        <div class="col-md-6 col-12 mt-3">
            <label for="">Heading EN</label>
            <input type="text" class="input_style" name="csec_heading_one_en" id="csec_heading_one_en" value="" placeholder="Heading EN">
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">Detail DE</label>
            <textarea rows="5" type="text" class="input_style ckeditor_one_de" name="csec_content_one_de" id="csec_content_one_de" placeholder="Detail DE"></textarea>
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">Detail EN</label>
            <textarea rows="5" type="text" class="input_style ckeditor_one_en" name="csec_contents_one_en" id="csec_contents_one_en" placeholder="Detail EN"></textarea>
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">Banner</label>
            <div class="">
                <label for="banner-upload" class="upload-btn">
                    <span class="material-icons">cloud_upload</span>
                    <span>Upload Files</span>
                </label>
                <input id="banner-upload" type="file" class="file-input" name="mFile_banner">
            </div>
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">File</label>
            <div class="">
                <label for="file-upload" class="upload-btn">
                    <span class="material-icons">cloud_upload</span>
                    <span>Upload Files</span>
                </label>
                <input id="file-upload" type="file" class="file-input" name="mFile_one">
            </div>
        </div>
    `;
        } else if(cst_id == 5){
            htmlContent = `
        <div class="col-md-12 col-12 mt-3">
            <label for="">Year</label>
            <input type="number" onKeyPress="if(this.value.length==4) return false;" class="input_style" name="csec_year" id="csec_year" value="" placeholder="Year">
        </div>
        <div class="col-md-6 col-12 mt-3">
            <label for="">Heading DE</label>
            <input type="text" required class="input_style" name="csec_heading_one_de" id="csec_heading_one_de" value="" placeholder="Heading DE">
        </div>
        <div class="col-md-6 col-12 mt-3">
            <label for="">Heading EN</label>
            <input type="text" class="input_style" name="csec_heading_one_en" id="csec_heading_one_en" value="" placeholder="Heading EN">
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">Detail One DE</label>
            <textarea rows="5" type="text" class="input_style ckeditor_one_de" name="csec_content_one_de" id="csec_content_one_de" placeholder="Detail One DE"></textarea>
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">Detail One EN</label>
            <textarea rows="5" type="text" class="input_style ckeditor_one_en" name="csec_contents_one_en" id="csec_contents_one_en" placeholder="Detail One EN"></textarea>
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">Detail Two DE</label>
            <textarea rows="5" type="text" class="input_style ckeditor_two_de" name="csec_content_two_de" id="csec_content_two_de" placeholder="Detail Two DE"></textarea>
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">Detail Two EN</label>
            <textarea rows="5" type="text" class="input_style ckeditor_two_en" name="csec_content_two_en" id="csec_content_two_en" placeholder="Detail Two EN"></textarea>
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">File One</label>
            <div class="">
                <label for="file-upload-one" class="upload-btn">
                    <span class="material-icons">cloud_upload</span>
                    <span>Upload Files</span>
                </label>
                <input id="file-upload-one" type="file" class="file-input" name="mFile_one">
            </div>
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">File Two</label>
            <div class="">
                <label for="file-upload-two" class="upload-btn">
                    <span class="material-icons">cloud_upload</span>
                    <span>Upload Files</span>
                </label>
                <input id="file-upload-two" type="file" class="file-input" name="mFile_two">
            </div>
        </div>
    `;
        } else if (cst_id == 6) {
            htmlContent = `
        <div class="col-md-6 col-12 mt-3">
            <label for="">Heading DE</label>
            <input type="text" required class="input_style" name="csec_heading_one_de" id="csec_heading_one_de" value="" placeholder="Heading DE">
        </div>
        <div class="col-md-6 col-12 mt-3">
            <label for="">Heading EN</label>
            <input type="text" class="input_style" name="csec_heading_one_en" id="csec_heading_one_en" value="" placeholder="Heading EN">
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">Detail DE</label>
            <textarea rows="5" type="text" class="input_style ckeditor_one_de" name="csec_content_one_de" id="csec_content_one_de" placeholder="Detail DE"></textarea>
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">Detail EN</label>
            <textarea rows="5" type="text" class="input_style ckeditor_one_en" name="csec_contents_one_en" id="csec_contents_one_en" placeholder="Detail EN"></textarea>
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">File</label>
            <div class="">
                <label for="file-upload" class="upload-btn">
                    <span class="material-icons">cloud_upload</span>
                    <span>Upload Files</span>
                </label>
                <input id="file-upload" type="file" class="file-input" name="mFile_one">
            </div>
        </div>
    `;
        } else if (cst_id == 7) {
            htmlContent = `
        <div class="col-md-6 col-12 mt-3">
            <label for="">Heading DE</label>
            <input type="text" required class="input_style" name="csec_heading_one_de" id="csec_heading_one_de" value="" placeholder="Heading DE">
        </div>
        <div class="col-md-6 col-12 mt-3">
            <label for="">Heading EN</label>
            <input type="text" class="input_style" name="csec_heading_one_en" id="csec_heading_one_en" value="" placeholder="Heading EN">
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">Detail DE</label>
            <textarea rows="5" type="text" class="input_style ckeditor_one_de" name="csec_content_one_de" id="csec_content_one_de" placeholder="Detail DE"></textarea>
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">Detail EN</label>
            <textarea rows="5" type="text" class="input_style ckeditor_one_en" name="csec_contents_one_en" id="csec_contents_one_en" placeholder="Detail EN"></textarea>
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">File One</label>
            <div class="">
                <label for="file-upload-one" class="upload-btn">
                    <span class="material-icons">cloud_upload</span>
                    <span>Upload Files</span>
                </label>
                <input id="file-upload-one" type="file" class="file-input" name="mFile_one">
            </div>
        </div>
        <div class="col-md-12 col-12 mt-3">
            <label for="">File Two</label>
            <div class="">
                <label for="file-upload-two" class="upload-btn">
                    <span class="material-icons">cloud_upload</span>
                    <span>Upload Files</span>
                </label>
                <input id="file-upload-two" type="file" class="file-input" name="mFile_two">
            </div>
        </div>
    `;
        }
        targetElement.append(htmlContent);

        ClassicEditor
            .create(document.querySelector('.ckeditor_one_de'), {
                toolbar: {
                    items: [
                        'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'undo', 'redo' // Exclude 'imageUpload' or 'image'
                    ]
                },
                removePlugins: ['blockQuote', 'Image', 'ImageToolbar', 'ImageUpload', 'ImageCaption', 'MediaEmbed'],
            })
            .then(editor => {
                editor.ui.view.editable.element.style.height = '300px'; // Directly adjust height
                editor.ui.view.editable.element.style.color = 'black';
            })
            .catch(error => {
                console.error(error);
            });

        ClassicEditor
            .create(document.querySelector('.ckeditor_one_en'), {
                toolbar: {
                    items: [
                        'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'undo', 'redo' // Exclude 'imageUpload' or 'image'
                    ]
                },
                removePlugins: ['blockQuote', 'Image', 'ImageToolbar', 'ImageUpload', 'ImageCaption', 'MediaEmbed'],
            })
            .then(editor => {
                editor.ui.view.editable.element.style.height = '300px'; // Directly adjust height
                editor.ui.view.editable.element.style.color = 'black';
            })
            .catch(error => {
                console.error(error);
            });

        ClassicEditor
            .create(document.querySelector('.ckeditor_two_de'), {
                toolbar: {
                    items: [
                        'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'undo', 'redo' // Exclude 'imageUpload' or 'image'
                    ]
                },
                removePlugins: ['blockQuote', 'Image', 'ImageToolbar', 'ImageUpload', 'ImageCaption', 'MediaEmbed'],
            })
            .then(editor => {
                editor.ui.view.editable.element.style.height = '300px'; // Directly adjust height
                editor.ui.view.editable.element.style.color = 'black';
            })
            .catch(error => {
                console.error(error);
            });

        ClassicEditor
            .create(document.querySelector('.ckeditor_two_en'), {
                toolbar: {
                    items: [
                        'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'undo', 'redo' // Exclude 'imageUpload' or 'image'
                    ]
                },
                removePlugins: ['blockQuote', 'Image', 'ImageToolbar', 'ImageUpload', 'ImageCaption', 'MediaEmbed'],
            })
            .then(editor => {
                editor.ui.view.editable.element.style.height = '300px'; // Directly adjust height
                editor.ui.view.editable.element.style.color = 'black';
            })
            .catch(error => {
                console.error(error);
            });

    });
</script>

</html>