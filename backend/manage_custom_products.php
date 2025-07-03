<?php
include("../lib/session_head.php");

if (isset($_REQUEST['btnAdd'])) {
    //print_r($_REQUEST);die();
    $Query = "SELECT * FROM `products` WHERE supplier_id ='" . dbStr(trim($_REQUEST['supplier_id'])) . "'";
    $rs = mysqli_query($GLOBALS['conn'], $Query);
    if (mysqli_num_rows($rs) > 0) {

        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=4");
    } else {
        $pro_id = getMaximum("products", "pro_id");
        mysqli_query($GLOBALS['conn'], "INSERT INTO category_map (cat_id, supplier_id, sub_group_ids) VALUES ('" . dbStr(trim($_REQUEST['level_three_id'])) . "', '" . dbStr(trim($_REQUEST['supplier_id'])) . "', '" . dbStr(trim($_REQUEST['level_two_id'] . "," . $_REQUEST['level_one_id'])) . "')") or die(mysqli_error($GLOBALS['conn']));
        mysqli_query($GLOBALS['conn'], "INSERT INTO products (pro_id, pro_custom_add, pro_status, manf_id, supplier_id, pro_ean, pro_manufacture_aid, pro_description_short, pro_description_long, pro_udx_seo_internetbezeichung, pro_addedby, pro_cdate) VALUES ('" . $pro_id . "', '1', '0', '" . dbStr(trim($_REQUEST['manf_id'])) . "', '" . dbStr(trim($_REQUEST['supplier_id'])) . "', '" . dbStr(trim($_REQUEST['pro_ean'])) . "', '".dbStr(trim($_REQUEST['pro_manufacture_aid']))."', '" . dbStr(trim($_REQUEST['pro_description_short'])) . "', '" . dbStr(trim($_REQUEST['pro_description_long'])) . "', '" . dbStr(trim($_REQUEST['pro_udx_seo_internetbezeichung'])) . "', '" . $_SESSION["UserID"] . "', '" . date_time . "')") or die(mysqli_error($GLOBALS['conn']));
        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
    }
} elseif (isset($_REQUEST['btnUpdate'])) {
    //print_r($_REQUEST);die();
    $Query = "SELECT * FROM `products` WHERE supplier_id ='" . dbStr(trim($_REQUEST['supplier_id'])) . "' AND pro_id != '".$_REQUEST['pro_id']."' ";
    $rs = mysqli_query($GLOBALS['conn'], $Query);
    if (mysqli_num_rows($rs) > 0) {

        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=4");
    } else {
        $old_supplier_id = returnName("supplier_id", "products", "pro_id", $_REQUEST['pro_id']);
        mysqli_query($GLOBALS['conn'], "UPDATE category_map SET cat_id = '" . dbStr(trim($_REQUEST['level_three_id'])) . "', supplier_id = '" . dbStr(trim($_REQUEST['supplier_id'])) . "', sub_group_ids = '" . dbStr(trim($_REQUEST['level_two_id'] . "," . $_REQUEST['level_one_id'])) . "'  WHERE supplier_id='" . $old_supplier_id."'") or die(mysqli_error($GLOBALS['conn']));
        mysqli_query($GLOBALS['conn'], "UPDATE products SET manf_id = '" . dbStr(trim($_REQUEST['manf_id'])) . "', supplier_id = '" . dbStr(trim($_REQUEST['supplier_id'])) . "', pro_ean = '" . dbStr(trim($_REQUEST['pro_ean'])) . "', pro_manufacture_aid = '".dbStr(trim($_REQUEST['pro_manufacture_aid']))."', pro_description_short = '" . dbStr(trim($_REQUEST['pro_description_short'])) . "', pro_description_long = '" . dbStr(trim($_REQUEST['pro_description_long'])) . "', pro_udx_seo_internetbezeichung = '" . dbStr(trim($_REQUEST['pro_udx_seo_internetbezeichung'])) . "', pro_updatedby = '" . $_SESSION["UserID"] . "', pro_udate = '" . date_time . "'  WHERE pro_id=" . $_REQUEST['pro_id']) or die(mysqli_error($GLOBALS['conn']));
        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
    }
} elseif (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 2) {
        $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM products WHERE pro_id = " . $_REQUEST['pro_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $supplier_id = $rsMem->supplier_id;
            $pro_ean = $rsMem->pro_ean;
            $manf_id = $rsMem->manf_id;
            $pro_manufacture_aid = $rsMem->pro_manufacture_aid;
            $pro_description_short = $rsMem->pro_description_short;
            $pro_description_long = $rsMem->pro_description_long;
            $pro_udx_seo_internetbezeichung = $rsMem->pro_udx_seo_internetbezeichung;
            $level_three_id = returnName("cat_id", "category_map", "supplier_id", $supplier_id);
            $sub_group_ids = explode(",", returnName("sub_group_ids", "category_map", "supplier_id", $supplier_id));
            $level_two_id = $sub_group_ids[0];
            $level_one_id = $sub_group_ids[1];
            $readonly = "readonly";
            $formHead = "Update Info";
        }
    } else {
        $supplier_id = "";
        $manf_id = "";
        $pro_ean = "";
        $pro_manufacture_aid = "";
        $pro_description_short = "";
        $pro_description_long = "";
        $pro_udx_seo_internetbezeichung = "";
        $level_three_id = 0;
        $level_two_id = 0;
        $level_one_id = 0;
        $readonly = "";
        $formHead = "Add New";
    }
}

//--------------Button Active--------------------
if (isset($_REQUEST['btnActive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE products SET pro_status='1' WHERE pro_id = " . $_REQUEST['chkstatus'][$i]);
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
            mysqli_query($GLOBALS['conn'], "UPDATE products SET pro_status='0' WHERE pro_id = " . $_REQUEST['chkstatus'][$i]);
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
                            <?php print($formHead); ?> Custom Product
                        </h2>
                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-4 col-12 mt-3">
                                    <label for="">Category (Level One)</label>
                                    <select class="input_style" name="level_one_id" id="level_one_id">
                                        <?php FillSelected2("category", "group_id", "cat_title_de AS cat_title ", $level_one_id, "cat_status > 0 AND parent_id = '0'"); ?>
                                    </select>
                                </div>
                                <div class="col-md-4 col-12 mt-3">
                                    <label for="">Sub Category (Level Two)</label>
                                    <select class="input_style" name="level_two_id" id="level_two_id">
                                        <?php
                                        if ($_REQUEST['action'] == 2) {
                                            FillSelected2("category", "group_id", "cat_title_de AS cat_title ", $level_two_id, "cat_status > 0 AND parent_id = '" . $level_one_id . "'");
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-4 col-12 mt-3">
                                    <label for="">Sub Category (Level Three)</label>
                                    <select class="input_style" name="level_three_id" id="level_three_id" required>
                                        <?php
                                        if ($_REQUEST['action'] == 2) {
                                            FillSelected2("category", "group_id", "cat_title_de AS cat_title ", $level_three_id, "cat_status > 0 AND parent_id = '" . $level_two_id . "'");
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3 col-12 mt-3">
                                    <label for="">Manufacture</label>
                                    <select class="input_style" name="manf_id" id="manf_id">
                                        <option value="0">N/A</option>
                                        <?php FillSelected2("manufacture", "manf_id", "manf_name", $manf_id, "manf_status > 0"); ?>
                                    </select>
                                </div>
                                <div class="col-md-3 col-12 mt-3">
                                    <label for="">Supplier ID</label>
                                    <input type="text" <?php print($readonly); ?> class="input_style" name="supplier_id" id="supplier_id" value="<?php print($supplier_id); ?>" placeholder="Supplier ID" required>
                                </div>
                                <div class="col-md-3 col-12 mt-3">
                                    <label for="">EAN</label>
                                    <input type="text" class="input_style" name="pro_ean" id="pro_ean" value="<?php print($pro_ean); ?>" placeholder="EAN">
                                </div>
                                <div class="col-md-3 col-12 mt-3">
                                    <label for="">Manufacture AID</label>
                                    <input type="text" class="input_style" name="pro_manufacture_aid" id="pro_manufacture_aid" value="<?php print($pro_manufacture_aid); ?>" placeholder="Manufacture AID">
                                </div>
                                <div class="col-md-12 col-12 mt-3">
                                    <label for="">Short Description</label>
                                    <input type="text" class="input_style" name="pro_description_short" id="pro_description_short" value="<?php print($pro_description_short); ?>" placeholder="Short Description" required>
                                </div>
                                <div class="col-md-12 col-12 mt-3">
                                    <label for="">Long Description</label>
                                    <textarea type="text" class="input_style" name="pro_description_long" id="pro_description_long" placeholder="Long Description" required> <?php print($pro_description_long); ?> </textarea>
                                </div>
                                <div class="col-md-12 col-12 mt-3">
                                    <label for="">UDX SEO Internetbezeichung</label>
                                    <textarea type="text" class="input_style" name="pro_udx_seo_internetbezeichung" id="pro_udx_seo_internetbezeichung" placeholder="UDX SEO Internetbezeichung" required> <?php print($pro_udx_seo_internetbezeichung); ?> </textarea>
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
                        <h1 class="text-white">Custom Product Management</h1>
                        <div class="d-flex gap-1">
                            <a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=1"); ?>" class="add-new"><span class="material-icons icon">add</span> <span class="text">Add New</span></a>
                        </div>
                    </div>
                    <div class="main_table_container">
                        <?php
                        $pro_id = 0;
                        $pro_description_short = "";
                        $searchQuery = "";

                        if (isset($_REQUEST['pro_id']) && $_REQUEST['pro_id'] > 0) {
                            if (!empty($_REQUEST['pro_description_short'])) {
                                $pro_id = $_REQUEST['pro_id'];
                                $pro_description_short = $_REQUEST['pro_description_short'];
                                $searchQuery .= " AND pro.pro_id = '" . $_REQUEST['pro_id'] . "'";
                            }
                        }
                        ?>
                        <form class="row" name="frm_search" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>">
                            <div class=" col-md-4 col-12 mt-2">
                                <label for="" class="text-white">Title</label>
                                <input type="hidden" name="pro_id" id="pro_id" value="<?php print($pro_id); ?>">
                                <input type="text" class="input_style pro_description_short" name="pro_description_short" id="pro_description_short" value="<?php print($pro_description_short); ?>" placeholder="Title:" autocomplete="off" onchange="javascript: frm_search.submit();">
                            </div>
                        </form>
                        <form class="table_responsive" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <table>
                                <thead>
                                    <tr>
                                        <th width="10"><input type="checkbox" name="chkAll" onClick="setAll();"></th>
                                        <th width="100">Image</th>
                                        <th width="100">Artical Id</th>
                                        <th>Title </th>
                                        <th style="text-align: right; width: 256px">Stock</th>
                                        <th style="text-align: right; width: 185px">Price</th>
                                        <th width="50">Status</th>
                                        <th width="125">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $Query = "SELECT pro.*, pg.pg_mime_source_url, pq.pq_id, pq.pq_quantity, pq.pq_upcomming_quantity, pq.pq_status FROM products AS pro LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' LEFT OUTER JOIN products_quantity AS pq ON pq.supplier_id = pro.supplier_id WHERE pro.pro_custom_add = '1' " . $searchQuery . " ORDER BY pro.pro_id ASC";
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
                                            if (!empty($row->pg_mime_source_url)) {
                                                $image_path = $GLOBALS['siteURL'] . $row->pg_mime_source_url;
                                            }
                                    ?>
                                            <tr>
                                                <td><input type="checkbox" name="chkstatus[]" value="<?php print($row->pro_id); ?>"></td>
                                                <td>
                                                    <div class="popup_container">
                                                        <div class="container__img-holder">
                                                            <img src="<?php print($image_path); ?>">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php print($row->supplier_id); ?></td>
                                                <td><?php print($row->pro_description_short); ?></td>
                                                <td>
                                                    <div class="table-box-body">
                                                        <input type="hidden" name="pro_id" id="pro_id_<?php print($counter); ?>" value="<?php print($row->pro_id); ?>">
                                                        <input type="hidden" name="supplier_id" id="supplier_id_<?php print($counter); ?>" value="<?php print($row->supplier_id); ?>">
                                                        <input type="hidden" name="pq_id" id="pq_id_<?php print($counter); ?>" value="<?php print($row->pq_id); ?>">
                                                        <div class="table-form-group">
                                                            <label for="">Auf Lager</label>
                                                            <input type="number" name="pq_quantity" id="pq_quantity_<?php print($counter); ?>" value="<?php print($row->pq_quantity); ?>" onkeyup="if(this.value === '' || parseFloat(this.value) <= 0) {this.value = 0;} " min="0">
                                                        </div>
                                                        <div class="table-form-group">
                                                            <label for="">Online verfügbar</label>
                                                            <input type="number" readonly name="pq_upcomming_quantity" id="pq_upcomming_quantity_<?php print($counter); ?>" value="<?php print($row->pq_upcomming_quantity); ?>" onkeyup="if(this.value === '' || parseFloat(this.value) <= 0) {this.value = 0;} " min="0">
                                                        </div>
                                                        <div class="table-form-group">
                                                            <label for="">&nbsp;</label>
                                                            <input type="button" name="pro_update_quantity" data-id="<?php print($counter); ?>" class="btn btn-success btn-style-light w-auto pro_update_quantity" value="Update (<?php print(($row->pq_status == "true") ? 'T' : 'F'); ?>)">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="table-box-body">
                                                        <?php
                                                        $counter1 = 0;
                                                        $Query1 = "SELECT * FROM `products_bundle_price` WHERE pro_id = '" . $row->pro_id . "' AND supplier_id = '" . $row->supplier_id . "' ORDER BY pbp_lower_bound ASC";
                                                        $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
                                                        if (mysqli_num_rows($rs1) > 0) {
                                                            while ($row1 = mysqli_fetch_object($rs1)) {
                                                                $counter1++;
                                                        ?>
                                                                <div class="table-form-group">
                                                                    <input type="hidden" name="pbp_id" id="pbp_id_<?php print($counter); ?>_<?php print($counter1); ?>" value="<?php print($row1->pbp_id); ?>">
                                                                    <label for="">LB <?php print($row1->pbp_lower_bound) ?> </label>
                                                                    <input type="number" step="any" name="pbp_price_amount[]" id="pbp_price_amount_<?php print($counter); ?>_<?php print($counter1); ?>" onkeyup="if(this.value === '' || parseFloat(this.value) <= 0) {this.value = 0;} " min="0" value="<?php print($row1->pbp_price_amount) ?>">
                                                                </div>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                        <div class="table-form-group">
                                                            <label for="">&nbsp;</label>
                                                            <input type="hidden" name="pro_update_price_lenght" id="pro_update_price_lenght_<?php print($counter); ?>" value="<?php print($counter1); ?>">
                                                            <input type="button" name="btnUpdatePrice" data-id="<?php print($counter); ?>" class="btn btn-success btn-style-light pro_update_price" value="Update">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($row->pro_status == 0) {
                                                        echo '<span class="btn btn-danger w-auto">Offline</span>';
                                                    } else {
                                                        echo '<span class="btn btn-success w-auto">Live</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-xs btn-success btn-style-light w-auto" target="_blank" title="View" onClick="javascript: window.open ('<?php print($GLOBALS['siteURL'] . "product_detail.php?supplier_id=" . $row->supplier_id); ?>');"><span class="material-icons icon material-xs">visibility</span></button>
                                                    <button type="button" class="btn btn-xs btn-primary btn-style-light w-auto" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "pro_id=" . $row->pro_id . "&supplier_id=" . $row->supplier_id); ?>';"><span class="material-icons icon material-xs">edit</span></button>
                                                    <button type="button" class="btn btn-xs btn-warning btn-style-light w-auto" title="Add Product Bundle Price" onClick="javascript: window.location = '<?php print("manage_products_bundle_price.php?action=1&" . $qryStrURL . "pro_id=" . $row->pro_id . "&supplier_id=" . $row->supplier_id); ?>';"><span class="material-icons icon material-xs">payments</span></button>
                                                    <button type="button" class="btn btn-xs btn-primary btn-style-light w-auto mt-2" title="Add Product Feature" onClick="javascript: window.location = '<?php print("manage_products_feature.php?action=1&" . $qryStrURL . "pro_id=" . $row->pro_id . "&supplier_id=" . $row->supplier_id); ?>';"><span class="material-icons icon material-xs">featured_play_list</span></button>
                                                    <button type="button" class="btn btn-xs btn-warning btn-style-light w-auto mt-2" title="Add Product Keyword" onClick="javascript: window.location = '<?php print("manage_products_keyword.php?action=1&" . $qryStrURL . "pro_id=" . $row->pro_id . "&supplier_id=" . $row->supplier_id); ?>';"><span class="material-icons icon material-xs">tag</span></button>
                                                    <button type="button" class="btn btn-xs btn-success btn-style-light w-auto mt-2" title="Add Product Quantity" onClick="javascript: window.location = '<?php print("manage_products_quantity.php?action=1&" . $qryStrURL . "pro_id=" . $row->pro_id . "&supplier_id=" . $row->supplier_id); ?>';"><span class="material-icons icon material-xs">production_quantity_limits</span></button>
                                                    <button type="button" class="btn btn-xs btn-warning btn-style-light w-auto mt-2" title="Add Product Gallery" onClick="javascript: window.location = '<?php print("manage_products_gallery.php?action=1&" . $qryStrURL . "pro_id=" . $row->pro_id . "&supplier_id=" . $row->supplier_id); ?>';"><span class="material-icons icon material-xs">image</span></button>
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
    $('input.pro_description_short').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=pro_description_short&pro_custom_add=1',
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
            var pro_id = $("#pro_id");
            var pro_description_short = $("#pro_description_short");
            $(pro_id).val(ui.item.pro_id);
            $(pro_description_short).val(ui.item.value);
            frm_search.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });
    $(".pro_update_quantity").on("click", function() {
        //console.log("btnUpdateQuantity");
        let pro_id = $("#pro_id_" + $(this).attr("data-id")).val();
        let supplier_id = $("#supplier_id_" + $(this).attr("data-id")).val();
        let pq_id = $("#pq_id_" + $(this).attr("data-id")).val();
        let pq_quantity = $("#pq_quantity_" + $(this).attr("data-id")).val();
        let pq_upcomming_quantity = $("#pq_upcomming_quantity_" + $(this).attr("data-id")).val();
        //console.log("pro_id: "+pro_id+" supplier_id: "+supplier_id+" pq_id: "+pq_id);
        $.ajax({
            url: 'ajax_calls.php?action=pro_update_quantity',
            method: 'POST',
            data: {
                pro_id: pro_id,
                supplier_id: supplier_id,
                pq_id: pq_id,
                pq_quantity: pq_quantity,
                pq_upcomming_quantity: pq_upcomming_quantity
            },
            success: function(response) {
                //console.log("response = "+response);
                const obj = JSON.parse(response);
                //console.log(obj);
                if (obj.status == 1) {
                    $("#pq_quantity_" + $(this).attr("data-id")).val(obj.data[0].pq_quantity);
                    $("#pq_upcomming_quantity_" + $(this).attr("data-id")).val(obj.data[0].pq_upcomming_quantity);
                    $.toast({
                        heading: 'Success',
                        text: 'Stock updated successfully',
                        icon: 'success',
                        position: 'top-right'
                    });
                }
            }
        });
    });
    $(".pro_update_price").on("click", function() {
        //console.log("btnUpdateQuantity");
        let priceData = [];
        let pro_id = $("#pro_id_" + $(this).attr("data-id")).val();
        let supplier_id = $("#supplier_id_" + $(this).attr("data-id")).val();
        let pro_update_price_lenght = $("#pro_update_price_lenght_" + $(this).attr("data-id")).val();
        for (let i = 1; i <= pro_update_price_lenght; i++) {
            //console.log("i: "+i);
            let pbp_id = $("#pbp_id_" + $(this).attr("data-id") + "_" + i).val();
            let pbp_price_amount = $("#pbp_price_amount_" + $(this).attr("data-id") + "_" + i).val();
            priceData.push({
                pbp_id: pbp_id,
                pbp_price_amount: pbp_price_amount
            });
        }
        //let pbp_id = $("#pbp_id_" + $(this).attr("data-id")).val();
        //let pbp_price_amount = $("#pbp_price_amount_" + $(this).attr("data-id")).val();
        //console.log("pro_id: "+pro_id+" supplier_id: "+supplier_id+" pbp_id: "+pbp_id+" pbp_price_amount: "+pbp_price_amount);
        $.ajax({
            url: 'ajax_calls.php?action=pro_update_price',
            method: 'POST',
            data: {
                pro_id: pro_id,
                supplier_id: supplier_id,
                priceData: priceData
            },
            success: function(response) {
                //console.log("response = "+response);
                const obj = JSON.parse(response);
                console.log(obj);
                if (obj.status == 1) {
                    $.toast({
                        heading: 'Success',
                        text: 'Price updated successfully',
                        icon: 'success',
                        position: 'top-right'
                    });
                }
            }
        });
    });
</script>
<script>
    <?php if (isset($_REQUEST['action']) && $_REQUEST['action'] == 1) { ?>
        $(window).load(function() {
            $("#level_one_id").trigger("click");
        });
    <?php } ?>
    $("#level_one_id").on("click", function() {
        let level_one_id = $("#level_one_id").val();
        $.ajax({
            type: "POST",
            url: "ajax_calls.php?action=level_one",
            data: {
                level_one_id: level_one_id
            },
            success: function(data) {
                //console.log(data);
                const obj = JSON.parse(data);
                //console.log(obj);
                if (obj.status == 1) {
                    $("#level_two_id").html(obj.level_one_data);
                }
            }
        });

    });
    $("#level_two_id").on("click", function() {
        let level_two_id = $("#level_two_id").val();
        $.ajax({
            type: "POST",
            url: "ajax_calls.php?action=level_two",
            data: {
                level_two_id: level_two_id
            },
            success: function(data) {
                //console.log(data);
                const obj = JSON.parse(data);
                //console.log(obj);
                if (obj.status == 1) {
                    $("#level_three_id").html(obj.level_two_data);
                }
            }
        });

    });
</script>

</html>