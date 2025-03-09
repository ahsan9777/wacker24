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
    $pq_status = "false";
    if ($_REQUEST['pq_status'] > 0) {
        $pq_status = "true";
    }
    $pq_id = getMaximum("products_quantity", "pq_id");
    mysqli_query($GLOBALS['conn'], "INSERT INTO products_quantity (pq_id, supplier_id, pq_quantity, pq_upcomming_quantity, pq_status) VALUES ('" . $pq_id . "', '" . $supplier_id . "', '" . dbStr(trim($_REQUEST['pq_quantity'])) . "', '" . dbStr(trim($_REQUEST['pq_upcomming_quantity'])) . "', '" . $pq_status . "')") or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?action=1&" . $qryStrURL . "op=1");
} elseif (isset($_REQUEST['btnUpdate'])) {

    $pq_status = "false";
    if ($_REQUEST['pq_status'] > 0) {
        $pq_status = "true";
    }
    mysqli_query($GLOBALS['conn'], "UPDATE products_quantity SET pq_quantity = '" . dbStr(trim($_REQUEST['pq_quantity'])) . "', pq_upcomming_quantity = '" . dbStr(trim($_REQUEST['pq_upcomming_quantity'])) . "', pq_status = '" . $pq_status . "' WHERE pq_id=" . $_REQUEST['pq_id']) or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?action=1&" . $qryStrURL . "op=2");
} elseif (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 2) {
        $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM products_quantity WHERE pq_id = " . $_REQUEST['pq_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $pq_quantity = $rsMem->pq_quantity;
            $pq_upcomming_quantity = $rsMem->pq_upcomming_quantity;
            $pq_status = ($rsMem->pq_status == 'false') ? 0 : 1;
            $formHead = "Update Info";
        }
    } else {
        $pq_quantity = "";
        $pq_upcomming_quantity = "";
        $pq_status = 0;
        $formHead = "Add New";
    }
}

//--------------Button Orderby--------------------
if (isset($_REQUEST['btnOrderby'])) {
    if (isset($_REQUEST['pq_id'])) {
        for ($i = 0; $i < count($_REQUEST['pq_id']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE products_quantity SET pf_forder='" . $_REQUEST['pf_forder'][$i] . "' WHERE pq_id = " . $_REQUEST['pq_id'][$i]);
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

            mysqli_query($GLOBALS['conn'], "DELETE FROM products_quantity WHERE pq_id = " . $_REQUEST['chkstatus'][$i]) or die(mysqli_error($_REQUEST['conn']));
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
                        <?php print($formHead); ?> Products Quantity
                    </h2>
                    <form name="frm_data" id="frm_data" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-4 col-12 mt-3">
                                <label for="">Quantity</label>
                                <input type="number" class="input_style" name="pq_quantity" id="pq_quantity" value="<?php print($pq_quantity); ?>" placeholder="Quantity">
                            </div>
                            <div class="col-md-4 col-12 mt-3">
                                <label for="">Upcomming</label>
                                <input type="number" class="input_style" name="pq_upcomming_quantity" id="pq_upcomming_quantity" value="<?php print($pq_upcomming_quantity); ?>" placeholder="Upcomming Quantity">
                            </div>
                            <div class="col-md-4 col-12 mt-3">
                                <label for="">Type</label>
                                <select class="input_style pq_status" name="pq_status" id="pq_status">
                                    <option value="0" <?php print(($pq_status == 0) ? 'selected' : ''); ?> >false</option>
                                    <option value="1" <?php print(($pq_status == 1) ? 'selected' : ''); ?> >true</option>
                                </select>
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
                                    <th>Quantity</th>
                                    <th>Upcomming Quantity</th>
                                    <th>Status</th>
                                    <th width="50">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $Query = "SELECT * FROM `products_quantity` WHERE supplier_id = '" . $_REQUEST['supplier_id'] . "' ORDER BY pq_id ASC";
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
                                            <td><input type="checkbox" name="chkstatus[]" value="<?php print($row->pq_id); ?>"></td>
                                            <td><?php print($row->pq_quantity); ?></td>
                                            <td><?php print($row->pq_upcomming_quantity); ?></td>
                                            <td><?php print($row->pq_status); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-xs btn-primary btn-style-light w-auto" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "pq_id=" . $row->pq_id); ?>';"><span class="material-icons icon material-xs">edit</span></button>
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
            var pq_id = $("#pq_id");
            var pq_upcomming_quantity = $("#pq_upcomming_quantity");
            $(pq_id).val(ui.item.pq_id);
            $(pq_upcomming_quantity).val(ui.item.value);
            frm_search.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });
</script>

</html>