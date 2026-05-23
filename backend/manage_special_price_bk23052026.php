<?php
include("../lib/session_head.php");

$searchQuery = "";
//$searchQuery = "WHERE 1 = 1";
if (isset($user_id) && $user_id == 0) {
    $user_id = $user_id;
    $qryStrURL .= "user_id=0&";
} else {
    $user_id = $_REQUEST['user_id'];
    $pHead = "User Special Price Management";
    $qryStrURL .= "user_id=" . $_REQUEST['user_id'] . "&";
}

/*if (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] > 0) {
    $qryStrURL .= "user_id=" . $_REQUEST['user_id'] . "&";
}*/
if (isset($_REQUEST['btnAdd'])) {

    //print_r($_REQUEST);die();
    //$Query = "SELECT * FROM user_special_price WHERE user_id = '" . dbStr(trim($_REQUEST['user_id'])) . "' AND level_one_id ='" . dbStr(trim($_REQUEST['level_one_id'])) . "' AND level_two_id ='" . dbStr(trim($_REQUEST['level_two_id'])) . "' AND supplier_id ='" . dbStr(trim($_REQUEST['supplier_id'])) . "'";
    $Query = "SELECT * FROM user_special_price WHERE user_id = '" . dbStr(trim($user_id)) . "' AND level_one_id ='" . dbStr(trim($_REQUEST['level_one_id'])) . "' AND level_two_id ='" . dbStr(trim($_REQUEST['level_two_id'])) . "' AND supplier_id ='" . dbStr(trim($_REQUEST['supplier_id'])) . "'";
    $rs = mysqli_query($GLOBALS['conn'], $Query);
    if (mysqli_num_rows($rs) > 0) {
        $row = mysqli_fetch_object($rs);
        mysqli_query($GLOBALS['conn'], "UPDATE user_special_price SET usp_price_type = '" . dbStr(trim($_REQUEST['usp_price_type'])) . "', usp_discounted_value = '" . dbStr(trim($_REQUEST['usp_discounted_value'])) . "', usp_updatedby = '" . $_SESSION["UserID"] . "', usp_udate = '" . date_time . "' WHERE usp_id= '" . $row->usp_id . "' ") or die(mysqli_error($GLOBALS['conn']));
        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=4");
    } else {

        $usp_id = getMaximum("user_special_price", "usp_id");
        //mysqli_query($GLOBALS['conn'], "INSERT INTO user_special_price (usp_id, user_id, level_one_id, level_two_id, supplier_id, usp_price_type, usp_discounted_value, usp_addedby, usp_cdate) VALUES ('" . $usp_id . "', '" . dbStr(trim($user_id)) . "', '" . dbStr(trim($_REQUEST['level_one_id'])) . "', '" . dbStr(trim($_REQUEST['level_two_id'])) . "', '" . dbStr(trim($_REQUEST['supplier_id'])) . "', '" . dbStr(trim($_REQUEST['usp_price_type'])) . "', '" . dbStr(trim($_REQUEST['usp_discounted_value'])) . "', '" . $_SESSION["UserID"] . "', '" . date_time . "')") or die(mysqli_error($GLOBALS['conn']));
        mysqli_query($GLOBALS['conn'], "INSERT INTO user_special_price (usp_id, user_id, level_one_id, level_two_id, supplier_id, usp_price_type, usp_discounted_value, usp_addedby, usp_cdate) VALUES ('" . $usp_id . "', '" . dbStr(trim($user_id)) . "', '" . dbStr(trim($_REQUEST['level_one_id'])) . "', '" . dbStr(trim($_REQUEST['level_two_id'])) . "', '" . dbStr(trim($_REQUEST['supplier_id'])) . "', '" . dbStr(trim($_REQUEST['usp_price_type'])) . "', '" . dbStr(trim($_REQUEST['usp_discounted_value'])) . "', '" . $_SESSION["UserID"] . "', '" . date_time . "')") or die(mysqli_error($GLOBALS['conn']));
        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
    }
} elseif (isset($_REQUEST['btnUpdate'])) {

    mysqli_query($GLOBALS['conn'], "UPDATE user_special_price SET usp_price_type = '" . dbStr(trim($_REQUEST['usp_price_type'])) . "', usp_discounted_value = '" . dbStr(trim($_REQUEST['usp_discounted_value'])) . "', usp_updatedby = '" . $_SESSION["UserID"] . "', usp_udate = '" . date_time . "' WHERE usp_id=" . $_REQUEST['usp_id']) or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
} elseif (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 2) {
        $rsM = mysqli_query($GLOBALS['conn'], "SELECT usp.*, pbp.pbp_price_amount, pg.pg_mime_source_url FROM user_special_price AS usp LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = usp.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = usp.supplier_id AND pbp.pbp_lower_bound = '1' WHERE usp_id = " . $_REQUEST['usp_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $level_one_id = $rsMem->level_one_id;
            $level_two_id = $rsMem->level_two_id;
            $supplier_id = $rsMem->supplier_id;
            $usp_price_type = $rsMem->usp_price_type;
            $usp_discounted_value = $rsMem->usp_discounted_value;
            $pg_mime_source_url = $rsMem->pg_mime_source_url;
            $pbp_price_amount = $rsMem->pbp_price_amount;

            if (empty($supplier_id)) {
                if ($level_two_id > 0) {
                    $pbp_price_amount = cat_min_pbp_price_amount($level_two_id);
                } elseif ($level_one_id) {
                    $pbp_price_amount = cat_min_pbp_price_amount($level_one_id);
                }
            }
            $usp_discounted_price = 0;
            if ($usp_price_type > 0) {
                $usp_discounted_price = number_format(($pbp_price_amount - $usp_discounted_value), "2", ".", "");
            } else {
                $percentage_value = ($pbp_price_amount * $usp_discounted_value) / 100;
                $usp_discounted_price = number_format(($pbp_price_amount - $percentage_value), "2", ".", "");
            }
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
                                <?php if ($_REQUEST['action'] == 2) {
                                    if (empty($supplier_id)) { ?>
                                        <div class="col-md-6 col-12 mt-3">
                                            <div class="d-flex gap-2 mt-3">
                                                <div class="d-flex gap-2">
                                                    <label for="">Percentage: </label>
                                                    <input type="radio" class="usp_price_type" name="usp_price_type" id="usp_price_type" value="0" <?php print(($usp_price_type == 0) ? 'checked' : ''); ?>>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <label for="">Fix: </label>
                                                    <input type="radio" class="usp_price_type" name="usp_price_type" id="usp_price_typ" value="1" <?php print(($_REQUEST['action'] == 2 && $usp_price_type == 1) ? 'checked' : ''); ?>>
                                                </div>
                                            </div>
                                            <label for="">Value</label>
                                            <input type="number" step="any" class="input_style usp_discounted_value" name="usp_discounted_value" id="usp_discounted_value" value="<?php print($usp_discounted_value); ?>" required placeholder="Value">
                                        </div>
                                        <div class="col-md-3 col-12 mt-3 d-flex flex-column justify-content-end">
                                            <label for="">Price</label>
                                            <input type="number" readonly class="input_style pbp_price_amount" name="pbp_price_amount" id="pbp_price_amount" value="<?php print($pbp_price_amount); ?>" placeholder="Price">
                                        </div>
                                        <div class="col-md-3 col-12 mt-3 d-flex flex-column justify-content-end">
                                            <label for="">Discounted Price</label>
                                            <input type="number" readonly class="input_style usp_discounted_price" name="usp_discounted_price" id="usp_discounted_price" value="<?php print($usp_discounted_price); ?>" placeholder="Discounted Price">
                                        </div>
                                    <?php } else { ?>
                                        <div class="row">
                                            <div class="col-md-2 col-12 mt-3">
                                                <div class="popup_container" style="width: 110px;">
                                                    <div class="container__img-holder">
                                                        <img src="<?php print($pg_mime_source_url); ?>" alt="" width="110">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12 mt-3">
                                                <div class="d-flex gap-2 mt-3">
                                                    <div class="d-flex gap-2">
                                                        <label for="">Percentage: </label>
                                                        <input type="radio" class="usp_price_type" name="usp_price_type" id="usp_price_type" value="0" <?php print(($usp_price_type == 0) ? 'checked' : ''); ?>>
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <label for="">Fix: </label>
                                                        <input type="radio" class="usp_price_type" name="usp_price_type" id="usp_price_type" value="1" <?php print(($usp_price_type == 1) ? 'checked' : ''); ?>>
                                                    </div>
                                                </div>
                                                <label for="">Value</label>
                                                <input type="number" step="any" class="input_style usp_discounted_value" name="usp_discounted_value" id="usp_discounted_value" value="<?php print($usp_discounted_value); ?>" required placeholder="Value">
                                            </div>
                                            <div class="col-md-3 col-12 mt-3 d-flex flex-column justify-content-end">
                                                <label for="">Price</label>
                                                <input type="number" readonly class="input_style pbp_price_amount" name="pbp_price_amount" id="pbp_price_amount" value="<?php print($pbp_price_amount); ?>" placeholder="Price">
                                            </div>
                                            <div class="col-md-3 col-12 mt-3 d-flex flex-column justify-content-end">
                                                <label for="">Discounted Price</label>
                                                <input type="number" readonly class="input_style usp_discounted_price" name="usp_discounted_price" id="usp_discounted_price" value="<?php print($usp_discounted_price); ?>" placeholder="Discounted Price">
                                            </div>
                                        </div>
                                    <?php }
                                } else { ?>
                                    <div class="col-md-6 col-12 mt-3">
                                        <label for="">Category</label>
                                        <select class="input_style" name="level_one_id" id="level_one_id">
                                            <?php FillSelected2("category", "group_id", "cat_title_de AS cat_title ", $level_one_id, "cat_status > 0 AND parent_id = '0'"); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-12 mt-3">
                                        <label for="">Sub Category</label>
                                        <select class="input_style" name="level_two_id" id="level_two_id">

                                        </select>
                                    </div>
                                    <div class="col-md-12 col-12 mt-3">
                                        <label for="">Title</label>
                                        <input type="hidden" name="supplier_id" id="supplier_id" value="0">
                                        <input type="text" class="input_style special_price_pro_title" name="special_price_pro_title" id="special_price_pro_title" value="" placeholder="Title:" autocomplete="off">
                                    </div>
                                    <div class="row" id="special_price_product_data">

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
                        <h1 class="text-white"> <?php print($pHead); ?> </h1>
                        <div class="d-flex gap-1">
                            <a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=1"); ?>" class="add-new"><span class="material-icons icon">add</span> <span class="text">Add New</span></a>
                        </div>
                    </div>
                    <div class="main_table_container">
                        <?php
                        $level_one_ids = 0;
                        $level_two_ids = 0;
                        $usp_supplier_id = 0;
                        $usp_pro_description_short = "";
                        $searchQuery = "";
                        $level_one_id_where = "";

                        if (isset($_REQUEST['level_one_ids']) && $_REQUEST['level_one_ids'] > 0) {
                            $level_one_ids = $_REQUEST['level_one_ids'];
                            $searchQuery .= "AND usp.level_one_id = '" . $_REQUEST['level_one_ids'] . "'";
                            $level_one_id_where = "AND usp.level_one_id = '" . $_REQUEST['level_one_ids'] . "'";
                        }
                        if (isset($_REQUEST['level_two_ids']) && $_REQUEST['level_two_ids'] > 0) {
                            $level_two_ids = $_REQUEST['level_two_ids'];
                            $searchQuery .= "AND usp.level_two_id = '" . $_REQUEST['level_two_ids'] . "'";
                        }
                        if (isset($_REQUEST['usp_supplier_id']) && $_REQUEST['usp_supplier_id'] > 0) {
                            if (!empty($_REQUEST['usp_pro_description_short'])) {
                                $usp_supplier_id = $_REQUEST['usp_supplier_id'];
                                $usp_pro_description_short = $_REQUEST['usp_pro_description_short'];
                                $searchQuery .= " AND usp.supplier_id = '" . $_REQUEST['usp_supplier_id'] . "'";
                            }
                        }

                        ?>
                        <form class="row flex-row" name="frm_search" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>">
                            <div class="col-md-2 col-12 mt-2">
                                <label for="" class="text-white">Category Level One</label>
                                <select class="input_style" name="level_one_ids" id="level_one_ids" onchange="javascript: frm_search.submit();">
                                    <option value="">N/A</option>
                                    <?php FillSelectedJoin2("user_special_price AS usp", "usp.level_one_id", "c.cat_title_de AS cat_title", $level_one_ids, "LEFT OUTER JOIN category AS c ON c.group_id = usp.level_one_id WHERE usp.user_id = '0'"); ?>
                                </select>
                            </div>
                            <div class="col-md-2 col-12 mt-2">
                                <label for="" class="text-white">Category Level Two</label>
                                <select class="input_style" name="level_two_ids" id="level_two_ids" onchange="javascript: frm_search.submit();">
                                    <option value="">N/A</option>
                                    <?php FillSelectedJoin2("user_special_price AS usp", "usp.level_two_id", "c.cat_title_de AS cat_title", $level_two_ids, "LEFT OUTER JOIN category AS c ON c.group_id = usp.level_two_id WHERE usp.user_id = '0' AND usp.level_two_id > 0 " . $level_one_id_where . ""); ?>
                                </select>
                            </div>
                            <div class=" col-md-4 col-12 mt-2">
                                <label for="" class="text-white">Title</label>
                                <input type="hidden" name="usp_supplier_id" id="usp_supplier_id" value="<?php print($usp_supplier_id); ?>">
                                <input type="text" class="input_style usp_pro_description_short" name="usp_pro_description_short" id="usp_pro_description_short" value="<?php print($usp_pro_description_short); ?>" placeholder="Title:" autocomplete="off" onchange="javascript: frm_search.submit();">
                            </div>
                        </form>
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
                                    //$Query = "SELECT usp.*, cat.cat_title_de AS cat_title, sub_cat.cat_title_de AS sub_cat_title, (SELECT GROUP_CONCAT(pro.pro_description_short) FROM products AS pro WHERE FIND_IN_SET(pro.supplier_id, usp.supplier_id)) AS pro_title, ( SELECT GROUP_CONCAT(pbp.pbp_price_amount) FROM products_bundle_price AS pbp WHERE FIND_IN_SET(pbp.supplier_id, usp.supplier_id) AND pbp.pbp_lower_bound = '1') AS pro_actual_price FROM user_special_price AS usp LEFT OUTER JOIN category AS cat ON cat.group_id = usp.level_one_id LEFT OUTER JOIN category AS sub_cat ON sub_cat.group_id = usp.level_two_id WHERE usp.user_id = '" . $_REQUEST['user_id'] . "' ORDER BY usp.usp_id ASC ";
                                    $Query = "SELECT usp.*, cat.cat_title_de AS cat_title, sub_cat.cat_title_de AS sub_cat_title, pro.pro_description_short AS pro_title, pbp.pbp_price_amount AS pro_actual_price   FROM user_special_price AS usp LEFT OUTER JOIN category AS cat ON cat.group_id = usp.level_one_id LEFT OUTER JOIN category AS sub_cat ON sub_cat.group_id = usp.level_two_id LEFT OUTER JOIN products AS pro ON pro.supplier_id = usp.supplier_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = usp.supplier_id AND pbp.pbp_lower_bound = '1' WHERE usp.user_id = '" . $user_id . "' " . $searchQuery . " ORDER BY usp.usp_id ASC ";
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

                                                $title .= "<strong class = 'text-white'>Title : </strong> " . $row->pro_title . "<br>";
                                            }
                                            $pro_price = "";
                                            $pro_price_after_discount = "";
                                            if (!empty($row->pro_actual_price)) {
                                                $pro_actual_price = $row->pro_actual_price;

                                                $pro_price .=  str_replace(".", ",", $pro_actual_price) . "<br>";
                                                $price_after_discount = 0;
                                                if ($row->usp_price_type == 0) {
                                                    $discount = 0;
                                                    $discount = ($pro_actual_price * $row->usp_discounted_value) / 100;
                                                    $price_after_discount = number_format(($pro_actual_price - $discount), "2", ",", "");
                                                } else {
                                                    $price_after_discount = number_format(($pro_actual_price - $row->usp_discounted_value), "2", ",", "");
                                                }
                                                $pro_price_after_discount .= $price_after_discount . "<br>";
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
                                        print('<tr><td class = "text-center" colspan="100%" align="center">No record found!</td></tr>');
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
    $('input.usp_pro_description_short').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=usp_pro_description_short&level_one_id=<?php print($level_one_ids); ?>&level_two_id=<?php print($level_two_ids); ?>',
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
            var usp_supplier_id = $("#usp_supplier_id");
            var pro_description_short = $("#usp_pro_description_short");
            $(usp_supplier_id).val(ui.item.supplier_id);
            $(pro_description_short).val(ui.item.value);
            frm_search.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });
    $(window).load(function() {
        $("#level_one_id").trigger("click");
    });


    $("#level_one_id").on("click", function() {
        let level_one_id = $("#level_one_id").val();

        //console.log("level_one_id: "+level_one_id+" level_two_id: "+level_two_id);
        $.ajax({
            type: "POST",
            url: "ajax_calls.php?action=level_one",
            data: {
                level_one_id: level_one_id
            },
            success: function(data) {
                //console.log(data);
                $("#supplier_id").val(0);
                $("#special_price_pro_title").val("");
                //$("#special_price_product_data").empty();

                const obj = JSON.parse(data);
                //console.log(obj);
                if (obj.status == 1) {
                    category_price_data();
                    $("#level_two_id").html(obj.level_one_data);
                    cat_min_pbp_price_amount();
                }
            }
        });

    });

    $("#level_two_id").on("click", function() {
        $("#supplier_id").val(0);
        $("#special_price_pro_title").val("");
        $("#special_price_pro_title").val("");
        category_price_data();
        cat_min_pbp_price_amount();
    });

    function cat_min_pbp_price_amount() {
        let level_one_id = $("#level_one_id").val();
        let level_two_id = $("#level_two_id").val();
        //console.log("level_one_id: " + level_one_id+" level_two_id: "+level_two_id);
        $.ajax({
            type: "POST",
            url: "ajax_calls.php?action=cat_min_pbp_price_amount",
            data: {
                level_one_id: level_one_id,
                level_two_id: level_two_id
            },
            success: function(data) {
                //console.log(data);
                const obj = JSON.parse(data);
                //console.log(obj);
                if (obj.status == 1) {
                    $("#pbp_price_amount").val(obj.data[0].pbp_price_amount);
                }
            }
        });

    };

    $('input.special_price_pro_title').autocomplete({
        source: function(request, response) {
            let level_one_id = $("#level_one_id").val();
            let level_two_id = $("#level_two_id").val();
            //console.log("level_one_id: "+level_one_id+" level_two_id: "+level_two_id);
            $.ajax({
                url: 'ajax_calls.php?action=special_price_pro_title&level_one_id=' + level_one_id + '&level_two_id=' + level_two_id + '',
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
            $("#special_price_product_data").html("");
            var supplier_id = $("#supplier_id");
            var special_price_pro_title = $("#special_price_pro_title");
            $(supplier_id).val(ui.item.supplier_id);
            $(special_price_pro_title).val(ui.item.value);
            special_price_product_data(ui.item.supplier_id);
            //frmCat.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });

    function special_price_product_data(supplier_id) {
        //console.log("supplier_id: " + supplier_id);
        $.ajax({
            type: "POST",
            url: "ajax_calls.php?action=special_price_product_data",
            data: {
                supplier_id: supplier_id
            },
            success: function(data) {
                //console.log(data);
                const obj = JSON.parse(data);
                //console.log(obj);
                if (obj.status == 1) {
                    $("#special_price_product_data").empty();
                    $("#special_price_product_data").html(obj.special_price_product_data);
                }
            }
        });

    }


    $(".usp_price_type").on("click", function() {
        //console.log("usp_price_type");
        $(".usp_discounted_value").trigger("keyup");
    });
    $(".usp_discounted_value").on("keyup", function() {

        let usp_price_type = $("input[name='usp_price_type']:checked").val();
        let usp_discounted_value = $("#usp_discounted_value").val();
        let pbp_price_amount = $("#pbp_price_amount").val();
        let usp_discounted_price = 0;
        let percentage = 0;
        //console.log("usp_discounted_value: " + usp_discounted_value + " usp_price_type: " + usp_price_type + " pbp_price_amount: " + pbp_price_amount);
        if (usp_discounted_value > 0) {
            if (usp_price_type == 1) {
                if (parseFloat(usp_discounted_value) <= parseFloat(pbp_price_amount)) {
                    usp_discounted_price = (pbp_price_amount - usp_discounted_value).toFixed(2);
                } else {
                    $("#usp_discounted_value").val(0);
                    usp_discounted_price = 0;
                }
            } else {
                percentage = (pbp_price_amount * usp_discounted_value) / 100;
                usp_discounted_price = (pbp_price_amount - percentage).toFixed(2);
            }
        }
        $("#usp_discounted_price").val(usp_discounted_price);
    });

    function category_price_data() {
        let category_price_data = '<div class="col-md-6 col-12 mt-3" id="category_price">';
        category_price_data += '<div class="d-flex gap-2 mt-3">';
        category_price_data += '<div class="d-flex gap-2">';
        category_price_data += '<label for="">Percentage: </label>';
        category_price_data += '<input type="radio" class="usp_price_type" name="usp_price_type" id="usp_price_type" value="0" checked >';
        category_price_data += '</div>';
        category_price_data += '<div class="d-flex gap-2">';
        category_price_data += '<label for="">Fix: </label>';
        category_price_data += '<input type="radio" class="usp_price_type" name="usp_price_type" id="usp_price_typ" value="1">';
        category_price_data += '</div>';
        category_price_data += '</div>';
        category_price_data += '<label for="">Value</label>';
        category_price_data += '<input type="number" step="any" class="input_style usp_discounted_value" name="usp_discounted_value" id="usp_discounted_value" value="0" required placeholder="Value">';
        category_price_data += '</div>';
        category_price_data += '<div class="col-md-3 col-12 mt-3 d-flex flex-column justify-content-end">';
        category_price_data += '<label for="">Price</label>';
        category_price_data += '<input type="number" readonly class="input_style pbp_price_amount" name="pbp_price_amount" id="pbp_price_amount" value="" placeholder="Price">';
        category_price_data += '</div>';
        category_price_data += '<div class="col-md-3 col-12 mt-3 d-flex flex-column justify-content-end">';
        category_price_data += '<label for="">Discounted Price</label>';
        category_price_data += '<input type="number" readonly class="input_style usp_discounted_price" name="usp_discounted_price" id="usp_discounted_price" value="0" placeholder="Discounted Price">';
        category_price_data += '</div>';

        $("#special_price_product_data").empty();
        $("#special_price_product_data").html(category_price_data);

        $(".usp_price_type").on("click", function() {
            //console.log("usp_price_type");
            $(".usp_discounted_value").trigger("keyup");
        });
        $(".usp_discounted_value").on("keyup", function() {

            let usp_price_type = $("input[name='usp_price_type']:checked").val();
            let usp_discounted_value = $("#usp_discounted_value").val();
            let pbp_price_amount = $("#pbp_price_amount").val();
            let usp_discounted_price = 0;
            let percentage = 0;
            //console.log("usp_discounted_value: " + usp_discounted_value + " usp_price_type: " + usp_price_type + " pbp_price_amount: " + pbp_price_amount);
            if (usp_discounted_value > 0) {
                if (usp_price_type == 1) {
                    if (parseFloat(usp_discounted_value) <= parseFloat(pbp_price_amount)) {
                        usp_discounted_price = (pbp_price_amount - usp_discounted_value).toFixed(2);
                    } else {
                        $("#usp_discounted_value").val(0);
                        usp_discounted_price = 0;
                    }
                } else {
                    percentage = (pbp_price_amount * usp_discounted_value) / 100;
                    usp_discounted_price = (pbp_price_amount - percentage).toFixed(2);
                }
            }
            $("#usp_discounted_price").val(usp_discounted_price);
        });
    }
</script>

</html>