<?php
include("../lib/session_head.php");
if (isset($_REQUEST['btnAdd'])) {
    $cat_id = getMaximum("category", "cat_id");
    $group_id = getMaximumWhere("category", "group_id", "WHERE parent_id = '0'");

    $mfileName = "";
    //$dirName = "images/banners/";
    $dirName = "../files/category/";
    if (!empty($_FILES["mFile"]["name"])) {
        $mfileName = $cat_id . "_" . $_FILES["mFile"]["name"];
        $mfileName = str_replace(" ", "_", strtolower($mfileName));
        if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName)) {
            createThumbnail2($dirName, $mfileName, $dirName . "th/", "138", "80");
        }
    }

    mysqli_query($GLOBALS['conn'], "INSERT INTO category (cat_id, group_id, parent_id, cat_title_de, cat_params_de, cat_title_en, cat_params_en, cat_keyword, cat_description, cat_image) VALUES ('" . $cat_id . "', '" . $group_id . "', '0', '" . dbStr(trim($_REQUEST['cat_title_de'])) . "', '" . dbStr(url_clean(trim($_REQUEST['cat_title_de']))) . "', '".dbStr(trim($_REQUEST['cat_title_en']))."','" . dbStr(url_clean(trim($_REQUEST['cat_title_en']))) . "', '".dbStr(trim($_REQUEST['cat_keyword']))."', '".dbStr(trim($_REQUEST['cat_description']))."', '".$mfileName."')") or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");

} elseif (isset($_REQUEST['btnImport'])) {
    //print_r($_REQUEST);die();
    $xml = simplexml_load_file("lagersortiment_standard.xml") or die("Error: Cannot create object");
    /*print('<pre>');
    print_r($xml->T_NEW_CATALOG->CATALOG_GROUP_SYSTEM);
    print('</pre>');
      $i = 0;
    foreach ($xml->T_NEW_CATALOG->CATALOG_GROUP_SYSTEM as $rl) {
            echo $i++." ".$rl->CATALOG_STRUCTURE->GROUP_NAME.PHP_EOL;
    }
    echo count($xml->T_NEW_CATALOG->CATALOG_GROUP_SYSTEM->CATALOG_STRUCTURE);*/

    for ($i = 1; $i < count($xml->T_NEW_CATALOG->CATALOG_GROUP_SYSTEM->CATALOG_STRUCTURE); $i++) {
        $cat_id = getMaximum("category", "cat_id");
        $group_id = $xml->T_NEW_CATALOG->CATALOG_GROUP_SYSTEM->CATALOG_STRUCTURE[$i]->GROUP_ID;
        $group_name = $xml->T_NEW_CATALOG->CATALOG_GROUP_SYSTEM->CATALOG_STRUCTURE[$i]->GROUP_NAME;
        $parent_id = $xml->T_NEW_CATALOG->CATALOG_GROUP_SYSTEM->CATALOG_STRUCTURE[$i]->PARENT_ID;
        if ($parent_id == 1) {
            $parent_id = 0;
        }
        //print("group_id = ".$group_id."<br>group_name = ".$group_name."<br> parent_id = ".$parent_id."<br><br>");
        $Query = "SELECT * FROM category WHERE  cat_title_de = '" . dbStr(trim($group_name)) . "' AND group_id = '" . dbStr(trim($group_id)) . "' AND parent_id = '" . dbStr(trim($parent_id)) . "'";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            $row = mysqli_fetch_object($rs);
            $cat_id = $row->cat_id;
            mysqli_query($GLOBALS['conn'], "UPDATE category SET cat_title_de = '" . dbStr(trim($group_name)) . "', cat_params_de = '" . url_clean($group_name) . "' WHERE cat_id = '" . $cat_id . "'") or die(mysqli_error($GLOBALS['conn']));
        } else {
            mysqli_query($GLOBALS['conn'], "INSERT INTO category (cat_id, group_id, parent_id, cat_title_de, cat_params_de) VALUES ('" . $cat_id . "', '" . $group_id . "', '" . $parent_id . "', '" . dbStr(trim($group_name)) . "', '" . dbStr(url_clean(trim($group_name))) . "')") or die(mysqli_error($GLOBALS['conn']));
        }
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
} elseif (isset($_REQUEST['btnUpdate'])) {
    $dirName = "../files/category/";
    $mfileName = $_REQUEST['mfileName'];
    if (!empty($_FILES["mFile"]["name"])) {
        @unlink("../files/category/" . $_REQUEST['mfileName']);
        @unlink("../files/category/th/" . $_REQUEST['mfileName']);
        $mfileName = $_REQUEST['cat_id'] . "_" . $_FILES["mFile"]["name"];
        $mfileName = str_replace(" ", "_", strtolower($mfileName));
        if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName)) {
            createThumbnail2($dirName, $mfileName, $dirName . "th/", "138", "80");
        }
    }
    mysqli_query($GLOBALS['conn'], "UPDATE category SET cat_title_de = '" . dbStr(trim($_REQUEST['cat_title_de'])) . "',  cat_title_en='" . dbStr(trim($_REQUEST['cat_title_en'])) . "', cat_params_de = '".dbStr(url_clean(trim($_REQUEST['cat_title_de'])))."', cat_params_en = '".dbStr(url_clean(trim($_REQUEST['cat_title_en'])))."', cat_keyword = '" . dbStr(trim($_REQUEST['cat_keyword'])) . "', cat_description = '" . dbStr(trim($_REQUEST['cat_description'])) . "', cat_image='" . $mfileName . "' WHERE cat_id=" . $_REQUEST['cat_id']) or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
} elseif (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 2) {
        $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM category WHERE cat_id = " . $_REQUEST['cat_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $cat_title_en = $rsMem->cat_title_en;
            $cat_title_de = $rsMem->cat_title_de;
            $mfileName = $rsMem->cat_image;
            $mfile_path = !empty($rsMem->cat_image) ? $GLOBALS['siteURL'] . "files/category/" . $rsMem->cat_image : "";
            $cat_keyword = $rsMem->cat_keyword;
            $cat_description = $rsMem->cat_description;
            $formHead = "Update Info";
        }
    } elseif ($_REQUEST['action'] == 3) {
        $rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM category_map");
        if (mysqli_num_rows($rs) > 0) {
            while($row = mysqli_fetch_object($rs)) {
                $subgroups = explode(',', $row->sub_group_ids);
                foreach ($subgroups as $sub_id) {
                    mysqli_Query($GLOBALS['conn'], "INSERT IGNORE INTO category_map_subgroups (supplier_id, subgroup_id) VALUES ('".$row->supplier_id."', '".$sub_id."')")  or die(mysqli_error($GLOBALS['conn']));
                        $counter++;
                }
            }
        }
        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
    } else {
        $cat_title_en = "";
        $cat_title_de = "";
        $mfileName = "";
        $mfile_path = "";
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
                            <?php print($formHead); ?> Category
                        </h2>
                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <div class="row">
                                <?php if ($_REQUEST['action'] == 2 || $_REQUEST['action'] == 4) { ?>
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
                                        <label for="">Image ( <span class="label_span">Banner Size must be 1200px x 300x</span> )</label>
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
                                            <button class="btn btn-primary" type="submit" name="<?php print( ($_REQUEST['action'] == 4) ? 'btnAdd' : 'btnImport'); ?>" id="btnImport">Upload</button>
                                        <?php } ?>
                                        <button type="button" name="btnBack" class="btn btn-light" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>';">Cancel</button>
                                        </div>
                                    </div>
                            </div>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="table-controls">
                        <h1 class="text-white">Category Management</h1>
                        <div class="d-flex gap-1">
                            <a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=3"); ?>" class="btn btn-primary d-flex gap-2"><span class="material-icons icon">update</span> <span class="text">Update Category Map</span></a>
                            <a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=1"); ?>" class="btn btn-primary d-flex gap-2"><span class="material-icons icon">upload</span> <span class="text">Import Add New</span></a>
                            <a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=4"); ?>" class="btn btn-primary d-flex gap-2"><span class="material-icons icon">add</span> <span class="text">Add New</span></a>
                        </div>
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
                                $searchQuery = " AND cat.cat_id = '" . $_REQUEST['cat_id']."'";
                            }
                        }
                        ?>
                        <form class="row" name="frm_search" id="frm_search" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>">
                            <div class=" col-md-2 col-12 mt-2">
                                <label for="" class="text-white">Title</label>
                                <input type="hidden" name="cat_id" id="cat_id" value="<?php print($cat_id); ?>">
                                <input type="text" class="input_style cat_title" name="cat_title" id="cat_title" value="<?php print($cat_title); ?>" placeholder="Title:" autocomplete="off" onchange="javascript: frm_search.submit();">
                            </div>
                        </form>
                        <form class="table_responsive" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <table>
                                <thead>
                                    <tr>
                                        <th width="50"><input type="checkbox" name="chkAll" onClick="setAll();"></th>
                                        <th width="100">Banner</th>
                                        <th>Title </th>
                                        <th width="150">Show Banner</th>
                                        <th width="150">Show On Home</th>
                                        <th width="150">Home Featured</th>
                                        <th width="50">Status</th>
                                        <th width="90">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $Query = "SELECT cat.cat_id, cat.group_id, cat.cat_image, cat.cat_title_de AS cat_title, cat.cat_image_show, cat.cat_showhome, cat.cat_showhome_feature, cat.cat_status FROM category AS cat WHERE cat.parent_id = '0' ".$searchQuery." ";
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
                                            if (!empty($row->cat_image)) {
                                                $image_path = $GLOBALS['siteURL'] . "files/category/" . $row->cat_image;
                                            }
                                    ?>
                                            <tr>
                                                <td><input type="checkbox" name="chkstatus[]" value="<?php print($row->cat_id); ?>"></td>
                                                <td>
                                                    <div class="popup_container" style="width: <?php print(!empty($row->cat_image) ? '300px' : '100px'); ?>">
                                                        <div class="container__img-holder">
                                                            <img src="<?php print($image_path); ?>" >
                                                        </div>
                                                    </div>    
                                                </td>
                                                <td><?php print($row->cat_title); ?></td>
                                                <td> <input type="checkbox" class="cat_image_show" id="cat_image_show" data-id="<?php print($row->cat_id); ?>" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="sm" <?php print(($row->cat_image_show == 1) ? 'checked' : ''); ?>> </td>
                                                <td> <input type="checkbox" class="cat_showhome" id="cat_showhome" data-id="<?php print($row->cat_id); ?>" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="sm" <?php print(($row->cat_showhome == 1) ? 'checked' : ''); ?>> </td>
                                                <td> <input type="checkbox" class="cat_showhome_feature" id="cat_showhome_feature" data-id="<?php print($row->cat_id); ?>" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="sm" <?php print(($row->cat_showhome_feature == 1) ? 'checked' : ''); ?>> </td>
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
                                                    <button type="button" class="btn btn-xs btn-warning btn-style-light w-auto" title="Side Filter" onClick="javascript: window.location = '<?php print("manage_category_sidefilters.php?group_id=" . $row->group_id); ?>';"><span class="material-icons icon material-xs">filter_list</span></button>
                                                    <button type="button" class="btn btn-xs btn-primary btn-style-light w-auto" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "cat_id=" . $row->cat_id); ?>';"><span class="material-icons icon material-xs">edit</span></button>
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
                            </div>
                            <!--<input type="submit" name="btnDelete" onclick="return confirm('Are you sure you want to delete selected item(s)?');" value="Delete" class="btn btn-danger btn-style-light">-->
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
                url: 'ajax_calls.php?action=cat_title&parent_id=0',
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
            frm_search.submit();
            //return false;
        }
    });
    $(document).ready(function() {
        // Listen for toggle changes
        $('.cat_image_show').change(function() {
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
                    table: "category",
                    set_field: "cat_image_show",
                    set_field_data: set_field_data,
                    where_field: "cat_id",
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
        $('.cat_showhome').change(function() {
            let id = $(this).attr('data-id');
            let set_field_data = 0;
            //console.log("cat_id: "+id)
            if ($(this).prop('checked')) {
                set_field_data = 1;
            }
            //console.log("set_field_data: "+set_field_data);
            $.ajax({
                url: 'ajax_calls.php?action=btn_toggle',
                method: 'POST',
                data: {
                    table: "category",
                    set_field: "cat_showhome",
                    set_field_data: set_field_data,
                    where_field: "cat_id",
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
        $('.cat_showhome_feature').change(function() {
            let id = $(this).attr('data-id');
            let set_field_data = 0;
            //console.log("cat_id: "+id)
            if ($(this).prop('checked')) {
                set_field_data = 1;
            }
            //console.log("set_field_data: "+set_field_data);
            $.ajax({
                url: 'ajax_calls.php?action=btn_toggle',
                method: 'POST',
                data: {
                    table: "category",
                    set_field: "cat_showhome_feature",
                    set_field_data: set_field_data,
                    where_field: "cat_id",
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