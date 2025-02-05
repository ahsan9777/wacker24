<?php
include("../lib/session_head.php");

if (isset($_REQUEST['btnAdd'])) {

    $group_id = "";
    if (isset($_REQUEST['group_id'])) {
        for ($i = 0; $i < count($_REQUEST['group_id']); $i++) {
            $group_id .= $_REQUEST['group_id'][$i] . ",";
        }
        $group_id = rtrim($group_id, ",");
    }

    $scat_id = getMaximum("special_category", "scat_id");
    mysqli_query($GLOBALS['conn'], "INSERT INTO special_category (scat_id, group_id, scat_title_de, scat_title_en, scat_params_de, scat_params_en) VALUES ('".$scat_id."', '".dbStr(trim($group_id))."', '" . dbStr(trim($_REQUEST['scat_title_de'])) . "', '" . dbStr(trim($_REQUEST['scat_title_en'])) . "', '".url_clean($_REQUEST['scat_title_de'])."', '".url_clean($_REQUEST['scat_title_en'])."')") or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
} elseif (isset($_REQUEST['btnUpdate'])) {
    $group_id = "";
    //print_r($_REQUEST['group_id']);die();
    if (isset($_REQUEST['group_id'])) {
        for ($i = 0; $i < count($_REQUEST['group_id']); $i++) {
            $group_id .= $_REQUEST['group_id'][$i] . ",";
        }
        $group_id = rtrim($group_id, ",");
    }
    mysqli_query($GLOBALS['conn'], "UPDATE special_category SET group_id = '".dbStr(trim($group_id))."', scat_title_de = '" . dbStr(trim($_REQUEST['scat_title_de'])) . "',  scat_title_en='" . dbStr(trim($_REQUEST['scat_title_en'])) . "', scat_params_de = '".url_clean($_REQUEST['scat_title_de'])."',scat_params_en = '".url_clean($_REQUEST['scat_title_en'])."' WHERE scat_id=" . $_REQUEST['scat_id']) or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
} elseif (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 2) {
        $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM special_category WHERE scat_id = " . $_REQUEST['scat_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $group_id = $rsMem->group_id;
            $scat_title_en = $rsMem->scat_title_en;
            $scat_title_de = $rsMem->scat_title_de;
            $formHead = "Update Info";
        }
    } else {
        $group_id = "";
        $scat_title_en = "";
        $scat_title_de = "";
        $formHead = "Add New";
    }
}

//--------------Button Active--------------------
if (isset($_REQUEST['btnActive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE special_category SET scat_status='1' WHERE scat_id = " . $_REQUEST['chkstatus'][$i]);
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
            mysqli_query($GLOBALS['conn'], "UPDATE special_category SET scat_status='0' WHERE scat_id = " . $_REQUEST['chkstatus'][$i]);
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
    if (isset($_REQUEST['scat_id'])) {
        for ($i = 0; $i < count($_REQUEST['scat_id']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE special_category SET scat_orderby='" . $_REQUEST['scat_orderby'][$i] . "' WHERE scat_id = " . $_REQUEST['scat_id'][$i]);
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
                            <?php print($formHead); ?> Special Category
                        </h2>
                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 col-12 mt-3">
                                        <label for="">Title DE</label>
                                        <input type="text" class="input_style" name="scat_title_de" id="scat_title_de" value="<?php print($scat_title_de); ?>" placeholder="Title DE">
                                    </div>
                                    <div class="col-md-6 col-12 mt-3">
                                        <label for="">Title EN</label>
                                        <input type="text" class="input_style" name="scat_title_en" id="scat_title_en" value="<?php print($scat_title_en); ?>" placeholder="Title EN">
                                    </div>
                                    <div class="col-md-12 col-12 mt-3">
                                        <label for="">Title</label>
                                        <select name="group_id[]" id="group_id" multiple class="input_style multiple_select">
                                            <?php
                                            if ($_REQUEST['action'] == 2) {
                                                FillMultiple2("category", "group_id", "cat_title_de AS cat_title", "cat_status > 0 AND parent_id IN (11,12,13,14,15,16,17,18,19)", "$group_id");
                                            } else{
                                                FillSelected2("category", "group_id", "cat_title_de AS cat_title", "", "cat_status > 0 AND parent_id IN (11,12,13,14,15,16,17,18,19)"); 
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="padding_top_bottom">
                                    <?php if ($_REQUEST['action'] == 2) { ?>
                                        <button class="btn btn-primary" type="submit" name="btnUpdate" id="btnImport">Update</button>
                                    <?php } else { ?>
                                        <button class="btn btn-primary" type="submit" name="btnAdd" id="btnImport">Upload</button>
                                        <?php } ?>
                                        <button type="button" name="btnBack" class="btn btn-light" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>';">Cancel</button>
                                        </div>
                                    </div>
                            </div>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="table-controls">
                        <h1 class="text-white">Special Category Management</h1>
                        <a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=1"); ?>" class="btn btn-primary d-flex gap-2"><span class="material-icons icon">add</span> <span class="text">Add New</span></a>
                    </div>
                    <div class="main_table_container">
                        <form class="table_responsive" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <table>
                                <thead>
                                    <tr>
                                        <th width="50"><input type="checkbox" name="chkAll" onClick="setAll();"></th>
                                        <th>Title </th>
                                        <th>Category </th>
                                        <th width="100">Order By </th>
                                        <th width="50">Status</th>
                                        <th width="90">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $Query = "SELECT sc.scat_id, sc.group_id, sc.scat_title_de AS scat_title, sc.scat_params_de AS scat_params, sc.scat_status, sc.scat_orderby, (SELECT GROUP_CONCAT(cat.cat_title_de separator '<br>') FROM category AS cat WHERE FIND_IN_SET(cat.group_id, sc.group_id)) AS cat_title FROM special_category AS sc ORDER BY sc.scat_orderby ASC";
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
                                                <td><input type="checkbox" name="chkstatus[]" value="<?php print($row->scat_id); ?>"></td>
                                                <td><?php print($row->scat_title); ?></td>
                                                <td><?php print($row->cat_title); ?></td>
                                                <td>
                                                    <input type="hidden" name="scat_id[]" id="scat_id" value="<?php print($row->scat_id); ?>">
                                                    <input type="number" class="input_style" name="scat_orderby[]" id="scat_orderby" value="<?php print($row->scat_orderby); ?>">
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($row->scat_status == 0) {
                                                        echo '<span class="btn btn-danger btn-style-light w-auto">Offline</span>';
                                                    } else {
                                                        echo '<span class="btn btn-success btn-style-light w-auto">Live</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-xs btn-primary btn-style-light w-auto" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "&scat_id=" . $row->scat_id); ?>';"><span class="material-icons icon material-xs">edit</span></button>
                                                    <button type="button" class="btn btn-xs btn-warning btn-style-light w-auto" title="Add Image" onClick="javascript: window.location = '<?php print("manage_special_category_images.php?action=1&scat_id=" . $row->scat_id); ?>';"><span class="material-icons icon material-xs">add_a_photo</span></button>
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
            var scat_id = $("#scat_id");
            var cat_title = $("#cat_title");
            $(scat_id).val(ui.item.scat_id);
            $(cat_title).val(ui.item.value);
            frm_search.submit();
            //return false;
        }
    });
</script>

</html>