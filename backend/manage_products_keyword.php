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
    
    $pk_id = getMaximum("products_keyword", "pk_id");
    mysqli_query($GLOBALS['conn'], "INSERT INTO products_keyword (pk_id, pro_id, supplier_id, pk_title) VALUES ('" . $pk_id . "', '" . $pro_id . "', '" . $supplier_id . "', '" . dbStr(trim($_REQUEST['pk_title'])) . "')") or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?action=1&" . $qryStrURL . "op=1");
} elseif (isset($_REQUEST['btnUpdate'])) {

    
    mysqli_query($GLOBALS['conn'], "UPDATE products_keyword SET pk_title = '" . dbStr(trim($_REQUEST['pk_title'])) . "' WHERE pk_id=" . $_REQUEST['pk_id']) or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?action=1&" . $qryStrURL . "op=2");
} elseif (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 2) {
        $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM products_keyword WHERE pk_id = " . $_REQUEST['pk_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $pk_title = $rsMem->pk_title;
            $formHead = "Update Info";
        }
    } else {
        $pk_title = "";
        $formHead = "Add New";
    }
}


//--------------Button Delete--------------------
if (isset($_REQUEST['btnDelete'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {

            mysqli_query($GLOBALS['conn'], "DELETE FROM products_keyword WHERE pk_id = " . $_REQUEST['chkstatus'][$i]) or die(mysqli_error($_REQUEST['conn']));
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
                            <div class="col-md-6 col-12 mt-3">
                                <label for="">Title</label>
                                <input type="text" class="input_style" name="pk_title" id="pk_title" value="<?php print($pk_title); ?>" placeholder="Title">
                            </div>

                            <div class="col-md-12 col-12 mt-3">
                                <button class="btn btn-primary" type="submit" name="<?php print(($_REQUEST['action'] == 1) ? 'btnAdd' : 'btnUpdate'); ?>">Upload</button>
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
                                    <th>Title</th>
                                    <th width="50">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $Query = "SELECT * FROM `products_keyword` WHERE pro_id = '" . $_REQUEST['pro_id'] . "' AND supplier_id = '" . $_REQUEST['supplier_id'] . "' ORDER BY pk_id ASC";
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
                                            <td><input type="checkbox" name="chkstatus[]" value="<?php print($row->pk_id); ?>"></td>
                                            <td><?php print($row->pk_title); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-xs btn-primary btn-style-light w-auto" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "pk_id=" . $row->pk_id); ?>';"><span class="material-icons icon material-xs">edit</span></button>
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
    $('input.pf_fvalue').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=pf_fvalue',
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
            var pk_id = $("#pk_id");
            var pf_fvalue = $("#pf_fvalue");
            $(pk_id).val(ui.item.pk_id);
            $(pf_fvalue).val(ui.item.value);
            frm_search.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });
</script>

</html>