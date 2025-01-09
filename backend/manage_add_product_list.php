<?php
include("../lib/session_head.php");

if (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] > 0) {
    $qryStrURL .= "user_id=" . $_REQUEST['user_id'] . "&";
}
if (isset($_REQUEST['btnAdd_shoppinglist'])) {
    //print_r($_REQUEST);die();
    $Query = "SELECT * FROM shopping_list WHERE user_id = '" . dbStr(trim($_REQUEST['user_id'])) . "' AND sl_title LIKE '%" . dbStr(trim($_REQUEST['sl_title'])) . "%'";
    $rs = mysqli_query($GLOBALS['conn'], $Query);
    if (mysqli_num_rows($rs) > 0) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?action=1&" . $qryStrURL . "op=4");
    } else {

        $sl_id = getMaximum("shopping_list", "sl_id");
        mysqli_query($GLOBALS['conn'], "INSERT INTO shopping_list (sl_id, user_id, sl_title) VALUES ('" . $sl_id . "', '" . dbStr(trim($_REQUEST['user_id'])) . "', '" . dbStr(trim($_REQUEST['sl_title'])) . "')") or die(mysqli_error($GLOBALS['conn']));
        header("Location: " . $_SERVER['PHP_SELF'] . "?action=1&" . $qryStrURL . "op=1");
    }

} elseif (isset($_REQUEST['btnAdd'])) {
    $supplier_id = "";
    if (isset($_REQUEST['supplier_id'])) {
        for ($i = 0; $i < count($_REQUEST['supplier_id']); $i++) {
            $supplier_id .= $_REQUEST['supplier_id'][$i] . ",";
        }
        $supplier_id = rtrim($supplier_id, ",");
    }
    //print_r($_REQUEST);die();
    $Query = "SELECT * FROM user_special_price WHERE user_id = '" . dbStr(trim($_REQUEST['user_id'])) . "' AND level_one_id ='" . dbStr(trim($_REQUEST['level_one_id'])) . "' AND level_two_id ='" . dbStr(trim($_REQUEST['level_two_id'])) . "' AND supplier_id ='" . dbStr(trim($supplier_id)) . "'";
    $rs = mysqli_query($GLOBALS['conn'], $Query);
    if (mysqli_num_rows($rs) > 0) {

        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=4");
    } else {

        $usp_id = getMaximum("user_special_price", "usp_id");
        mysqli_query($GLOBALS['conn'], "INSERT INTO user_special_price (usp_id, user_id, level_one_id, level_two_id, supplier_id, usp_price_type, usp_discounted_value, usp_addedby, usp_cdate) VALUES ('" . $usp_id . "', '" . dbStr(trim($_REQUEST['user_id'])) . "', '" . dbStr(trim($_REQUEST['level_one_id'])) . "', '" . dbStr(trim($_REQUEST['level_two_id'])) . "', '" . dbStr(trim($supplier_id)) . "', '" . dbStr(trim($_REQUEST['usp_price_type'])) . "', '" . dbStr(trim($_REQUEST['usp_discounted_value'])) . "', '" . $_SESSION["UserID"] . "', '" . date_time . "')") or die(mysqli_error($GLOBALS['conn']));
        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
    }
} elseif (isset($_REQUEST['btnUpdate'])) {

    mysqli_query($GLOBALS['conn'], "UPDATE user_special_price SET usp_price_type = '" . dbStr(trim($_REQUEST['usp_price_type'])) . "', usp_discounted_value = '" . dbStr(trim($_REQUEST['usp_discounted_value'])) . "', usp_updatedby = '" . $_SESSION["UserID"] . "', usp_udate = '" . date_time . "' WHERE usp_id=" . $_REQUEST['usp_id']) or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
} elseif (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 2) {
        $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM user_special_price WHERE usp_id = " . $_REQUEST['usp_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $level_one_id = $rsMem->level_one_id;
            $level_two_id = $rsMem->level_two_id;
            $supplier_id = $rsMem->supplier_id;
            $usp_price_type = $rsMem->usp_price_type;
            $usp_discounted_value = $rsMem->usp_discounted_value;
            $formHead = "Update Info";
        }
    } else {
        $level_one_id = 0;
        $level_two_id = 0;
        $supplier_id = 0;
        $usp_price_type = 0;
        $usp_discounted_value = "";
        $formHead = "Add New";
    }
}

//--------------Button Active--------------------
if (isset($_REQUEST['btnActive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE user_special_price SET usp_status='1' WHERE usp_id = " . $_REQUEST['chkstatus'][$i]);
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
            mysqli_query($GLOBALS['conn'], "UPDATE user_special_price SET usp_status='0' WHERE usp_id = " . $_REQUEST['chkstatus'][$i]);
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

            mysqli_query($GLOBALS['conn'], "DELETE FROM user_special_price WHERE usp_id = " . $_REQUEST['chkstatus'][$i]) or die(mysqli_error($_REQUEST['conn']));
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

            <!-- Button trigger modal -->
            <!--<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Launch demo modal
            </button>-->

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form class="modal-content" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5 text-black" id="exampleModalLabel">Add Shopping List</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="col-md-12 col-12 mt-3">
                                <label for="" class="text-black">Title</label>
                                <input type="text" class="form-control" name="sl_title" id="sl_title" value="" required placeholder="Title">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="btnAdd_shoppinglist" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Content -->
            <section class="content" id="main-content">
                <?php if ($class != "") { ?>
                    <div class="<?php print($class); ?>"><?php print($strMSG); ?><a class="close" data-dismiss="alert">×</a></div>
                <?php } ?>
                <?php if (isset($_REQUEST['action'])) { ?>
                    <div class="main_container">
                        <h2 class="text-white">
                            <?php print($formHead); ?> Special Price
                        </h2>
                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <div class="row">
                                <?php if ($_REQUEST['action'] == 1) { ?>
                                    <div class="col-md-6 col-12 mt-3">
                                        <label for="">Shoping List</label>
                                        <select class="input_style" name="sl_id" id="sl_id">
                                            <?php FillSelected2("shopping_list", "sl_id", "sl_title", $level_one_id, "sl_id > 0"); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-12 mt-3">
                                        <label for=""> &nbsp;</label>
                                        <a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=1"); ?>" class="add-new" style="width: 41%;" data-bs-toggle="modal" data-bs-target="#exampleModal"><span class="material-icons icon">add</span> <span class="text">Add New Shopping LIst</span></a>
                                    </div>
                                    <div class="col-md-12 col-12 mt-3">
                                        <label for="">Product</label>
                                        <select name="supplier_id[]" id="supplier_id" multiple class="input_style multiple_select">
                                            <?php
                                            if ($_REQUEST['action'] == 2) {
                                                FillMultiple2("products", "supplier_id", "pro_description_short", "1=1", "$supplier_id");
                                            } else {
                                                //FillSelected2("products", "pro_id", "pro_description_short", "", "pro_status > 0");
                                            }
                                            ?>
                                        </select>
                                    </div>
                                <?php } ?>

                                <div class="col-md-12 col-12 mt-3">
                                    <button class="btn btn-primary" type="submit" name="<?php print(($_REQUEST['action'] == 1) ? 'btnAdd' : 'btnUpdate'); ?>">Upload</button>
                                    <button type="button" name="btnBack" class="btn btn-light" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>';">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="table-controls">
                        <h1 class="text-white">Special Price</h1>
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
                                        <th>Type</th>
                                        <th>Value</th>
                                        <th>Actual Price</th>
                                        <th>After Discount Price</th>
                                        <th width="50">Status</th>
                                        <th width="50">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $Query = "SELECT usp.*, cat.cat_title_de AS cat_title, sub_cat.cat_title_de AS sub_cat_title, (SELECT GROUP_CONCAT(pro.pro_description_short) FROM products AS pro WHERE FIND_IN_SET(pro.supplier_id, usp.supplier_id)) AS pro_title, ( SELECT GROUP_CONCAT(pbp.pbp_price_amount) FROM products_bundle_price AS pbp WHERE FIND_IN_SET(pbp.supplier_id, usp.supplier_id) AND pbp.pbp_lower_bound = '1') AS pro_actual_price FROM user_special_price AS usp LEFT OUTER JOIN category AS cat ON cat.group_id = usp.level_one_id LEFT OUTER JOIN category AS sub_cat ON sub_cat.group_id = usp.level_two_id WHERE usp.user_id = '" . $_REQUEST['user_id'] . "' ORDER BY usp.usp_id ASC ";
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
                                            $title = "";
                                            if (!empty($row->cat_title)) {
                                                $title .= "<strong class = 'text-white'>Category : </strong> " . $row->cat_title . "<br>";
                                            }
                                            if (!empty($row->sub_cat_title)) {
                                                $title .= "<strong class = 'text-white'>Sub Category : </strong> " . $row->sub_cat_title . "<br>";
                                            }
                                            if (!empty($row->pro_title)) {
                                                $pro_title = explode(",", $row->pro_title);
                                                for ($i = 0; $i < count($pro_title); $i++) {
                                                    $title .= "<strong class = 'text-white'>Title " . ($i + 1) . " : </strong> " . $pro_title[$i] . "<br>";
                                                }
                                            }
                                            $pro_price = "";
                                            $pro_price_after_discount = "";
                                            if (!empty($row->pro_actual_price)) {
                                                $pro_actual_price = explode(",", $row->pro_actual_price);
                                                for ($i = 0; $i < count($pro_actual_price); $i++) {
                                                    $pro_price .= "<strong class = 'text-white'>" . ($i + 1) . " : </strong> " . str_replace(".", ",", $pro_actual_price[$i]) . "<br>";
                                                    $price_after_discount = 0;
                                                    if ($row->usp_price_type == 0) {
                                                        $discount = 0;
                                                        $discount = ($pro_actual_price[$i] * $row->usp_discounted_value) / 100;
                                                        $price_after_discount = number_format(($pro_actual_price[$i] - $discount), "2", ",", "");
                                                    } else {
                                                        $price_after_discount = number_format(($pro_actual_price[$i] - $row->usp_discounted_value), "2", ",", "");
                                                    }
                                                    $pro_price_after_discount .= "<strong class = 'text-white'>" . ($i + 1) . " : </strong> " . $price_after_discount . "<br>";
                                                }
                                            }
                                    ?>
                                            <tr>
                                                <td><input type="checkbox" name="chkstatus[]" value="<?php print($row->usp_id); ?>"></td>
                                                <td><?php print($title); ?></td>
                                                <td><?php print(($row->usp_price_type == 0) ? 'Percentage Price' : 'Fixed Price'); ?></td>
                                                <td><?php print($row->usp_discounted_value); ?></td>
                                                <td><?php print($pro_price); ?></td>
                                                <td><?php print($pro_price_after_discount); ?></td>
                                                <td>
                                                    <?php
                                                    if ($row->usp_status == 0) {
                                                        echo '<span class="btn btn-danger btn-style-light w-auto">Offline</span>';
                                                    } else {
                                                        echo '<span class="btn btn-success btn-style-light w-auto">Live</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-xs btn-primary btn-style-light w-auto" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "usp_id=" . $row->usp_id); ?>';"><span class="material-icons icon material-xs">edit</span></button>
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
            var usp_id = $("#usp_id");
            var brand_name = $("#brand_name");
            $(usp_id).val(ui.item.usp_id);
            $(brand_name).val(ui.item.value);
            //frmCat.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });

    $(window).load(function () {
        $("#sl_id").trigger("click");
    });

    $("#sl_id").on("click", function() {
        $("#supplier_id option:selected").removeAttr("selected");
        $(".select2-search-choice").remove();
        $.ajax({
            type: "POST",
            url: "ajax_calls.php?action=get_product_list",
            success: function(data) {
                //console.log(data);
                $("#supplier_id").html(data);
                //$("#level_two_id").trigger("click");
            }
        });
    });

</script>

</html>