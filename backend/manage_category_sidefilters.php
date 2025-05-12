<?php
include("../lib/session_head.php");

$group_id = 0;
$cat_title = "";
if (isset($_REQUEST['group_id']) && $_REQUEST['group_id'] > 0) {
    $group_id = $_REQUEST['group_id'];
    $cat_title = ": ".returnName("cat_title_de AS cat_title", "category", "group_id", $group_id);
    $qryStrURL = "group_id=" . $group_id . "&";
}
if (isset($_REQUEST['btnAdd'])) {
    //print_r($_REQUEST);die();
    $lov_sf_id = 0;
    for ($i = 0; $i < count($_REQUEST['lov_sf_id']); $i++) {
        $lov_sf_id = dbStr(trim($_REQUEST['lov_sf_id'][$i]));
        $Query = "SELECT * FROM `category_side_filter` WHERE group_id = '" . $group_id . "' AND lov_sf_id = '" . $lov_sf_id . "' ";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) == 0) {
            $csf_id = getMaximum("category_side_filter", "csf_id");
            mysqli_query($GLOBALS['conn'], "INSERT INTO category_side_filter (csf_id, group_id, lov_sf_id) VALUES ('" . $csf_id . "', '" . $group_id . "', '" . $lov_sf_id . "') ") or die(mysqli_error($GLOBALS['conn']));
        }
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
} elseif (isset($_REQUEST['btnUpdate'])) {

    $lov_sf_id = dbStr(trim($_REQUEST['lov_sf_id'][0]));
    $Query = "SELECT * FROM `category_side_filter` WHERE group_id = '" . $group_id . "' AND lov_sf_id = '" . $lov_sf_id . "' ";
    $rs = mysqli_query($GLOBALS['conn'], $Query);
    if (mysqli_num_rows($rs) > 0) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=4");
    } else {
        mysqli_query($GLOBALS['conn'], "UPDATE category_side_filter SET lov_sf_id = '".$lov_sf_id."' WHERE csf_id=" . $_REQUEST['csf_id']) or die(mysqli_error($GLOBALS['conn']));
        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
    }
} elseif (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 2) {
        $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM category_side_filter WHERE csf_id = " . $_REQUEST['csf_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $group_id = $rsMem->group_id;
            $lov_sf_id = $rsMem->lov_sf_id;
            $formHead = "Update Info";
            $multiple = "";
        }
    } else {
        $group_id = 0;
        $lov_sf_id = "";
        $formHead = "Add New";
        $multiple = "multiple";
    }
}

//--------------Button Active--------------------
if (isset($_REQUEST['btnActive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE category_side_filter SET csf_status='1' WHERE csf_id = " . $_REQUEST['chkstatus'][$i]);
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
            mysqli_query($GLOBALS['conn'], "UPDATE category_side_filter SET csf_status='0' WHERE csf_id = " . $_REQUEST['chkstatus'][$i]);
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

            mysqli_query($GLOBALS['conn'], "DELETE FROM category_side_filter WHERE csf_id = " . $_REQUEST['chkstatus'][$i]) or die(mysqli_error($_REQUEST['conn']));
        }
        $class = "alert alert-success";
        $strMSG = "Record(s) deleted successfully";
    } else {
        $class = " alert alert-info ";
        $strMSG = "Please check atleast one checkbox";
    }
}

//--------------Button Orderby--------------------
if (isset($_REQUEST['btnOrderby'])) {
    if (isset($_REQUEST['csf_id'])) {
        for ($i = 0; $i < count($_REQUEST['csf_id']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE category_side_filter SET csf_orderby='" . $_REQUEST['csf_orderby'][$i] . "' WHERE csf_id = " . $_REQUEST['csf_id'][$i]);
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
                            <?php print($formHead); ?> Category Side Filter
                        </h2>
                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12 col-12 mt-3">
                                    <label for="">Side Filter Title</label>
                                    <select name="lov_sf_id[]" id="lov_sf_id" <?php print($multiple); ?> class="input_style multiple_select">
                                        <?php
                                        FillSelected2("lov_side_filter", "lov_sf_id", "lov_sf_title", $lov_sf_id, "lov_sf_status > 0");
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-12 col-12 mt-3">
                                    <button class="btn btn-primary" type="submit" name="<?php print(($_REQUEST['action'] == 1) ? 'btnAdd' : 'btnUpdate'); ?>">Upload</button>
                                    <button type="button" name="btnBack" class="btn btn-light" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>';">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="table-controls">
                        <h1 class="text-white">Category <?php print($cat_title);?> Side Filter Management</h1>
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
                                        <th>Title</th>
                                        <th width="100">Order By </th>
                                        <th width="50">Status</th>
                                        <th width="50">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $Query = "SELECT csf.*, sf.lov_sf_title FROM category_side_filter AS csf LEFT OUTER JOIN lov_side_filter AS sf ON csf.lov_sf_id = sf.lov_sf_id WHERE csf.group_id = '" . $group_id . "' ORDER BY csf.csf_orderby ASC ";
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
                                                <td><input type="checkbox" name="chkstatus[]" value="<?php print($row->csf_id); ?>"></td>
                                                <td><?php print($row->lov_sf_title); ?></td>
                                                <td>
                                                    <input type="hidden" name="csf_id[]" id="csf_id" value="<?php print($row->csf_id); ?>">
                                                    <input type="number" class="input_style" name="csf_orderby[]" id="csf_orderby" value="<?php print($row->csf_orderby); ?>">
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($row->csf_status == 0) {
                                                        echo '<span class="btn btn-danger btn-style-light w-auto">Offline</span>';
                                                    } else {
                                                        echo '<span class="btn btn-success btn-style-light w-auto">Live</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-xs btn-primary btn-style-light w-auto" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "csf_id=" . $row->csf_id); ?>';"><span class="material-icons icon material-xs">edit</span></button>
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
    $('input.brand_name').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=brand_name',
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
            var csf_id = $("#csf_id");
            var brand_name = $("#brand_name");
            $(csf_id).val(ui.item.csf_id);
            $(brand_name).val(ui.item.value);
            frm_search.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });
</script>

</html>