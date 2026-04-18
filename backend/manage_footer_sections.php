<?php
include("../lib/session_head.php");

if (isset($_REQUEST['btnAdd'])) {
    //print_r($_REQUEST);die();
    $Query = "SELECT * FROM `footer` WHERE footer_title_de ='" . dbStr(trim($_REQUEST['footer_title_de'])) . "'";
    $rs = mysqli_query($GLOBALS['conn'], $Query);
    if (mysqli_num_rows($rs) > 0) {

        $footer_title_de = $_REQUEST['footer_title_de'];
        $class = "alert alert-danger";
        $strMSG = "Dear Admin, This footer already exists against the footer name!";
    } else {
        $footer_id = getMaximum("footer", "footer_id");
        
        mysqli_query($GLOBALS['conn'], "INSERT INTO footer (footer_id, footer_title_de, footer_title_en) VALUES ('" . $footer_id . "', '" . dbStr(trim($_REQUEST['footer_title_de'])) . "', '" .dbStr(trim($_REQUEST['footer_title_en'])). "')") or die(mysqli_error($GLOBALS['conn']));
        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
    }
} elseif (isset($_REQUEST['btnUpdate'])) {

    $Query = "SELECT * FROM `footer` WHERE footer_id != '".$_REQUEST['footer_id']."' AND footer_title_de ='" . dbStr(trim($_REQUEST['footer_title_de'])) . "'";
    $rs = mysqli_query($GLOBALS['conn'], $Query);
    if (mysqli_num_rows($rs) > 0) {

        $footer_title_de = $_REQUEST['footer_title_de'];
        $class = "alert alert-danger";
        $strMSG = "Dear Admin, This footer already exists against the footer name!";
    } else {
    mysqli_query($GLOBALS['conn'], "UPDATE footer SET footer_title_de = '" . dbStr(trim($_REQUEST['footer_title_de'])) . "', footer_title_en = '" .dbStr(trim($_REQUEST['footer_title_en'])). "' WHERE footer_id=" . $_REQUEST['footer_id']) or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
    }
} elseif (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 2) {
        $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM footer WHERE footer_id = " . $_REQUEST['footer_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $footer_title_de = $rsMem->footer_title_de;
            $footer_title_en = $rsMem->footer_title_en;
            $formHead = "Update Info";
        }
    } else {
        $footer_title_de = "";
        $formHead = "Add New";
    }
}

//--------------Button Active--------------------
if (isset($_REQUEST['btnActive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE footer SET footer_status='1' WHERE footer_id = " . $_REQUEST['chkstatus'][$i]);
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
            mysqli_query($GLOBALS['conn'], "UPDATE footer SET footer_status='0' WHERE footer_id = " . $_REQUEST['chkstatus'][$i]);
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
    if (isset($_REQUEST['footer_id'])) {
        for ($i = 0; $i < count($_REQUEST['footer_id']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE footer SET footer_orderby='" . $_REQUEST['footer_orderby'][$i] . "' WHERE footer_id = " . $_REQUEST['footer_id'][$i]);
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
                            <?php print($formHead); ?> footer
                        </h2>
                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Title DE</label>
                                    <input type="text" class="input_style" name="footer_title_de" id="footer_title_de" value="<?php print($footer_title_de); ?>" placeholder="Title DE">
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Title EN</label>
                                    <input type="text" class="input_style" name="footer_title_en" id="footer_title_en" value="<?php print($footer_title_en); ?>" placeholder="Title EN">
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
                        <h1 class="text-white">Footer Section Management</h1>
                        <div class="d-flex gap-1">
                            <a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=1"); ?>" class="add-new"><span class="material-icons icon">add</span> <span class="text">Add New</span></a>
                        </div>
                    </div>
                    <div class="main_table_container">
                        <?php

                        $footer_id = 0;
                        $footer_title_de = "";
                        $searchQuery = "WHERE 1 = 1";

                        if (isset($_REQUEST['footer_id']) && $_REQUEST['footer_id'] > 0) {
                            if (!empty($_REQUEST['footer_title_de'])) {
                                $footer_id = $_REQUEST['footer_id'];
                                $footer_title_de = $_REQUEST['footer_title_de'];
                                $searchQuery .= " AND footer_id = '" . $_REQUEST['footer_id'] . "'";
                            }
                        }
                        ?>
                        <form class="row" name="frm_search" id="frm_search" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>">
                            <div class=" col-md-2 col-12 mt-2">
                                <label for="" class="text-white">Title</label>
                                <input type="hidden" name="footer_id" id="footer_id" value="<?php print($footer_id); ?>">
                                <input type="text" class="input_style footer_title_de" name="footer_title_de" id="footer_title_de" value="<?php print($footer_title_de); ?>" placeholder="Title:" autocomplete="off" onchange="javascript: frm_search.submit();">
                            </div>
                        </form>
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
                                    $Query = "SELECT * FROM footer " . $searchQuery . " ORDER BY footer_orderby ASC ";
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
                                                <td><input type="checkbox" name="chkstatus[]" value="<?php print($row->footer_id); ?>"></td>
                                                <td><?php print($row->footer_title_de); ?></td>
                                                <td>
                                                    <input type="hidden" name="footer_id[]" id="footer_id" value="<?php print($row->footer_id); ?>">
                                                    <input type="number" class="input_style" name="footer_orderby[]" id="footer_orderby" value="<?php print($row->footer_orderby); ?>">
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($row->footer_status == 0) {
                                                        echo '<span class="btn btn-danger btn-style-light w-auto">Offline</span>';
                                                    } else {
                                                        echo '<span class="btn btn-success btn-style-light w-auto">Live</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-xs btn-primary btn-style-light w-auto" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "footer_id=" . $row->footer_id); ?>';"><span class="material-icons icon material-xs">edit</span></button>
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
    $('input.footer_title_de').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=footer_title_de',
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
            var footer_id = $("#footer_id");
            var footer_title_de = $("#footer_title_de");
            $(footer_id).val(ui.item.footer_id);
            $(footer_title_de).val(ui.item.value);
            frm_search.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " footer_id " + ui.item.footer_id );
        }
    });
</script>

</html>