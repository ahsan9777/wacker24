<?php
include("../lib/session_head.php");


$ref = "manage_orders.php";
if (isset($_SERVER['HTTP_REFERER'])) {
    $ref = $_SERVER['HTTP_REFERER'];
}
$searchQuery = "WHERE 1 = 1";
if (isset($user_id) && $user_id > 0) {
    $user_id = $user_id;
    $searchQuery .= " AND ord.user_id = '" . $user_id . "'";
} else {
    $user_id = 0;
    $pHead = "Order Management";
}

if (isset($_REQUEST['show'])) {
    $qryStrURL .= "show&";
}
if (isset($_REQUEST['ord_id']) && gettype($_REQUEST['ord_id']) != "array" && $_REQUEST['ord_id'] > 0) {
    $qryStrURL .= "ord_id=" . $_REQUEST['ord_id'] . "&";
}

if (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] > 0) {

    $qryStrURL .= "user_id=" . $_REQUEST['user_id'] . "&";
}


//--------------Button Order Pending --------------------
if (isset($_REQUEST['btnPending'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_delivery_status = '0', ord_conform_status = '0' WHERE ord_id = " . $_REQUEST['chkstatus'][$i]);
        }
        $class = "alert alert-success";
        $strMSG = "Record(s) updated successfully";
    } else {
        $class = "alert alert-info";
        $strMSG = "Please Select Alteast One Checkbox";
    }
}

//--------------Button Order Completed --------------------
if (isset($_REQUEST['btnCompleted'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_delivery_status = '1', ord_conform_status = '1' WHERE ord_id = " . $_REQUEST['chkstatus'][$i]);
            $mailer->order($_REQUEST['chkstatus'][$i]);
        }
        $class = "alert alert-success";
        $strMSG = "Record(s) updated successfully";
    } else {
        $class = "alert alert-info";
        $strMSG = "Please Select Alteast One Checkbox";
    }
}
//--------------Button Order Rejected --------------------
if (isset($_REQUEST['btnRejected'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_delivery_status = '2', ord_conform_status = '1' WHERE ord_id = " . $_REQUEST['chkstatus'][$i]);
            $mailer->order_cancelation($_REQUEST['chkstatus'][$i]);
        }
        $class = "alert alert-success";
        $strMSG = "Record(s) updated successfully";
    } else {
        $class = "alert alert-info";
        $strMSG = "Please Select Alteast One Checkbox";
    }
}

//--------------Button delivery status update --------------------
if (isset($_REQUEST['d_status_id']) && gettype($_REQUEST['d_status_id']) == "array") {
    if (isset($_REQUEST['d_status_id'])) {
        for ($i = 0; $i < count($_REQUEST['d_status_id']); $i++) {
            if ($_REQUEST['d_status_id'][$i] > 0) {
                mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_delivery_status='" . $_REQUEST['d_status_id'][$i] . "', ord_conform_status = '1' WHERE ord_id = " . $_REQUEST['ord_id'][$i]);
                if ($_REQUEST['d_status_id'][$i] == 1) {
                    $mailer->order($_REQUEST['ord_id'][$i]);
                } else {
                    $mailer->order_cancelation($_REQUEST['ord_id'][$i]);
                }
            }
        }
        $class = "alert alert-success";
        $strMSG = "Record(s) updated successfully";
    } else {
        $class = "alert alert-info";
        $strMSG = "Please Select Alteast One Checkbox";
    }
} elseif (isset($_REQUEST['d_status_id'])) {
    mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_delivery_status='" . $_REQUEST['d_status_id'] . "', ord_conform_status = '1' WHERE ord_id = " . $_REQUEST['ord_id']);
    $mailer->order($_REQUEST['ord_id']);
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
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
                <?php if (isset($_REQUEST['show'])) { ?>
                    <div class="table-controls">
                        <h1 class="text-white">Order</h1>
                    </div>
                    <div class="main_table_container">
                        <form class="table_responsive" name="frm_table_order" id="frm_table_order" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <table>
                                <thead>
                                    <tr>
                                        <th width="100">Order ID</th>
                                        <th width="250">User Info </th>
                                        <th width="100">Shipping</th>
                                        <th width="250">Delivery</th>
                                        <th>Amount </th>
                                        <th>Payment Type</th>
                                        <th>Transaction ID</th>
                                        <th width="147">Date / Time</th>
                                        <th>Payment Status</th>
                                        <th width="166">Delivery Status</th>
                                        <th>Order Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ord_note = "";
                                    $Query = "SELECT ord.*, CONCAT(di.dinfo_fname, ' ', di.dinfo_lname) AS deliver_full_name, di.dinfo_phone, di.dinfo_email, di.dinfo_street, di.dinfo_address, di.dinfo_house_no, di.dinfo_usa_zipcode, di.dinfo_countries_id, c.countries_name, pm.pm_title_de AS pm_title, ds.d_status_name,u.utype_id, (SELECT ut.utype_name FROM user_type AS ut WHERE ut.utype_id = u.utype_id) utype_name, CONCAT(usp.usa_street, ' ', usp.usa_house_no) AS shipping_street_house, usp.usa_zipcode, usp.countries_id, usp.usa_address AS shipping_countrie_id FROM orders AS ord LEFT OUTER JOIN users AS u ON u.user_id = ord.user_id LEFT OUTER JOIN delivery_info AS di ON di.ord_id = ord.ord_id LEFT OUTER JOIN payment_method AS pm ON pm.pm_id = ord.ord_payment_method LEFT OUTER JOIN deli_status AS ds ON ds.d_status_id = ord.ord_delivery_status LEFT OUTER JOIN countries AS c ON c.countries_id = di.dinfo_countries_id LEFT OUTER JOIN user_shipping_address AS usp ON usp.user_id = ord.user_id AND usp.usa_type = '1' WHERE ord.ord_id = '" . $_REQUEST['ord_id'] . "' ORDER BY ord.ord_datetime DESC";
                                    //print($Query);
                                    $rs = mysqli_query($GLOBALS['conn'], $Query);
                                    if (mysqli_num_rows($rs) > 0) {
                                        $row = mysqli_fetch_object($rs);
                                        $strClass = 'label  label-danger';
                                        $user_info = "";
                                        $user_guest = "";
                                        $utype_id_as_guest = returnName("utype_id_as_guest", "users", "user_id", $row->user_id);
                                        if ($utype_id_as_guest > 0) {
                                            $user_guest = "Guest";
                                        }
                                        if ($row->utype_id == 3) {
                                            $user_info .= '<span class="btn btn-primary btn-style-light w-auto mb-2">' . rtrim($user_guest." ".$row->utype_name, "Customer") . '</span><br>';
                                        } else {
                                            $user_info .= '<span class="btn btn-success btn-style-light w-auto mb-2">' . rtrim($user_guest." ".$row->utype_name, "Customer") . '</span><br>';
                                        }
                                        if (!empty($user_company_name)) {
                                            $user_info .= $user_company_name . "<br>";
                                        }
                                        if (!empty($row->deliver_full_name)) {
                                            $user_info .= $row->deliver_full_name . "<br>";
                                        }
                                        if (!empty($row->dinfo_phone)) {
                                            $user_info .= $row->dinfo_phone . "<br>";
                                        }
                                        if (!empty($row->dinfo_email)) {
                                            $user_info .= $row->dinfo_email . "<br>";
                                        }

                                        $delivery_info = "";
                                        if (!empty($row->dinfo_street)) {
                                            $delivery_info .= $row->dinfo_street . " " . $row->dinfo_house_no . "<br>";
                                        }
                                        if (!empty($row->dinfo_usa_zipcode)) {
                                            $delivery_info .= $row->dinfo_usa_zipcode . "<br>";
                                        }
                                        if (!empty($row->countries_name)) {
                                            $delivery_info .= $row->countries_name . "<br>";
                                        }
                                        if (!empty($row->dinfo_address)) {
                                            $delivery_info .= $row->dinfo_address . "<br>";
                                        }
                                        $shipping_info = "";
                                        if ($row->ord_payment_method == 1) {
                                            if (!empty($row->shipping_street_house)) {
                                                $shipping_info .= $row->shipping_street_house . "<br>";
                                            }
                                            if (!empty($row->usa_zipcode)) {
                                                $shipping_info .= $row->usa_zipcode . "<br>";
                                            }
                                            if (!empty($row->shipping_countrie_id)) {
                                                $shipping_info .= returnName("countries_name", "countries", "countries_id", $row->shipping_countrie_id) . "<br>";
                                            }
                                            if (!empty($row->usa_address)) {
                                                $shipping_info .= $row->usa_address . "<br>";
                                            }
                                        }
                                        $ord_note = $row->ord_note;
                                    ?>
                                        <tr <?php print(($row->ord_delivery_status == 0) ? 'style="background: #ff572229;"' : ''); ?>>
                                            <td><?php print($row->ord_id); ?></td>
                                            <td><?php print($user_info); ?></td>
                                            <td><?php print($shipping_info); ?></td>
                                            <td><?php print($delivery_info); ?></td>
                                            <td><?php print(price_format($row->ord_amount + $row->ord_shipping_charges)); ?> €</td>
                                            <td><?php print($row->pm_title); ?></td>
                                            <td><?php print($row->ord_payment_transaction_id); ?></td>
                                            <td><?php print($row->ord_datetime); ?></td>
                                            <td>
                                                <?php
                                                if ($row->ord_payment_status == 0) {
                                                    echo '<span class="btn btn-warning btn-style-light w-auto">Pending</span>';
                                                } else {
                                                    echo '<span class="btn btn-success btn-style-light w-auto">Success</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($row->ord_delivery_status == 0) { ?>
                                                    <div class="row">
                                                        <div class="col-md-12 col-12 mt-3">
                                                            <input type="hidden" name="ord_id" value="<?php print($row->ord_id); ?>">
                                                            <select name="d_status_id" class="input_style" id="d_status_id" onchange="javascript: frm_table_order.submit();">
                                                                <?php FillSelected2("deli_status", "d_status_id", "d_status_name", "", "d_status_id >= 0"); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                <?php } elseif ($row->ord_delivery_status == 1) { ?>
                                                    <span class="btn btn-success btn-style-light w-auto"> <?php print($row->d_status_name); ?> </span>
                                                <?php } elseif ($row->ord_delivery_status == 2) { ?>
                                                    <span class="btn btn-danger btn-style-light w-auto"> <?php print($row->d_status_name); ?> </span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($row->ord_conform_status == 0) {
                                                    echo '<span class="btn btn-warning btn-style-light w-auto">Open</span>';
                                                } else {
                                                    echo '<span class="btn btn-success btn-style-light w-auto">Close</span>';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php
                                    } else {
                                        print('<tr><td colspan="100%" class="text-center" >No record found!</td></tr>');
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </form>

                    </div>
                    <?php if (!empty($ord_note)) { ?>
                        <div class="table-controls mt-3">
                            <h1 class="text-white">Bestellhinweis</h1>
                        </div>
                        <div class="main_table_container">
                            <?php print($ord_note); ?>
                        </div>
                    <?php } ?>
                    <div class="table-controls mt-3">
                        <h1 class="text-white">Order Detail</h1>
                        <!--<a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=1"); ?>" class="btn btn-primary d-flex gap-2"><span class="material-icons icon">visibility</span> <span class="text">View Invoice</span></a>-->
                        <a target="_blank" href="manage_order_invoice.php?ord_id=<?php print($_REQUEST['ord_id']); ?>" class="btn btn-primary d-flex gap-2"><span class="material-icons icon">visibility</span> <span class="text">View Invoice</span></a>

                    </div>
                    <div class="main_table_container">
                        <form class="table_responsive" name="frm_table_detail" id="frm_table_detail" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <table>
                                <thead>
                                    <tr>
                                        <th width="100">Image</th>
                                        <th>Supplier Id </th>
                                        <th>Title </th>
                                        <th>Amount</th>
                                        <th>Quentity</th>
                                        <th>Gross Amount</th>
                                        <th>VAT</th>
                                        <th>Total Amount</th>
                                        <!--<th width="50">Action</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ord_gross_total = 0;
                                    $ord_gst = 0;
                                    $ord_discount = 0;
                                    $ord_shipping_charges = 0;
                                    $ord_amount = 0;
                                    $Query = "SELECT oi.*, pro.pro_description_short, pg.pg_mime_source_url, ord.ord_gross_total, ord.ord_gst, ord.ord_discount, ord.ord_amount, ord.ord_shipping_charges FROM order_items AS oi LEFT OUTER JOIN orders AS ord ON ord.ord_id = oi.ord_id LEFT OUTER JOIN products AS pro ON pro.supplier_id = oi.supplier_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pro.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_source_url ASC LIMIT 1) WHERE oi.ord_id =  '" . $_REQUEST['ord_id'] . "' ORDER BY oi.oi_id ASC";
                                    //print($Query);
                                    $rs = mysqli_query($GLOBALS['conn'], $Query);
                                    if (mysqli_num_rows($rs) > 0) {
                                        while ($row = mysqli_fetch_object($rs)) {
                                            $strClass = 'label  label-danger';
                                            $ord_gross_total = price_format($row->ord_gross_total);
                                            $ord_gst = price_format($row->ord_gst);
                                            $ord_discount = price_format($row->ord_discount);
                                            $ord_shipping_charges = price_format($row->ord_shipping_charges);
                                            $ord_amount = price_format($row->ord_amount + $row->ord_shipping_charges);
                                    ?>
                                            <tr>
                                                <td>
                                                    <div class="popup_container">
                                                        <div class="container__img-holder">
                                                            <img src="<?php print(get_image_link(427, $row->pg_mime_source_url)); ?>">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php print($row->supplier_id); ?></td>
                                                <td><?php print($row->pro_description_short); ?></td>
                                                <td>
                                                    <?php
                                                    if ($row->oi_discount_value > 0) {
                                                        print("<del class = 'text-danger fs-6'>" . price_format($row->pbp_price_amount * (1 + config_gst)) . "€</del><br> <span class = 'text-success'>" . str_replace(".", ",", $row->oi_amount) . "€ " . $row->oi_discount_value . (($row->oi_discount_type > 0) ? '€' : '%') . "</span>");
                                                    } else {
                                                        print(str_replace(".", ",", $row->oi_amount) . "€");
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php print($row->oi_qty); ?></td>
                                                <td><?php print(str_replace(".", ",", $row->oi_gross_total)); ?>€</td>
                                                <td><?php print(str_replace(".", ",", $row->oi_gst)); ?>€</td>
                                                <td><?php print(str_replace(".", ",", $row->oi_net_total)); ?>€</td>
                                                <!--<td>
                                                    <button type="button" class="btn btn-xs btn-success btn-style-light w-auto" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?show&" . $qryStrURL . "ord_id=" . $row->ord_id); ?>';"><span class="material-icons icon material-xs">visibility</span></button>
                                                </td>-->
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <th colspan="7" class="text-end text-white fs-6">Nettobetrag:</th>
                                            <td class="text-white fs-6"><?php print($ord_gross_total); ?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="7" class="text-end text-white fs-6">Mwstbetrag:</th>
                                            <td class="text-white fs-6"><?php print($ord_gst); ?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="7" class="text-end text-white fs-6">Versand:</th>
                                            <td class="text-white fs-6"><?php print($ord_shipping_charges); ?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="7" class="text-end text-white fs-6">Rechnungsbetrag:</th>
                                            <td class="text-white fs-6"><?php print($ord_amount); ?></td>
                                        </tr>
                                    <?php } else {
                                        print('<tr><td colspan="100%" class="text-center">No record found!</td></tr>');
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </form>

                    </div>
                <?php } else { ?>
                    <div class="table-controls">
                        <h1 class="text-white"><?php print($pHead); ?></h1>
                        <a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=1"); ?>" class="btn btn-primary d-flex gap-2"><span class="material-icons icon">add</span> <span class="text">Add New</span></a>

                    </div>
                    <div class="main_table_container">
                        <?php

                        $ord_id = "";
                        $order_user_id = "";
                        $order_user_title = "";
                        if (isset($_REQUEST['ord_id']) && in_array(gettype($_REQUEST['ord_id']), array("integer", "double", "string")) && $_REQUEST['ord_id'] > 0) {

                            $ord_id = $_REQUEST['ord_id'];
                            $searchQuery .= " AND ord.ord_id = '" . $_REQUEST['ord_id'] . "'";
                        }
                        if (isset($_REQUEST['order_user_id']) && $_REQUEST['order_user_id'] > 0) {
                            if (!empty($_REQUEST['order_user_title'])) {
                                $order_user_id = $_REQUEST['order_user_id'];
                                $order_user_title = $_REQUEST['order_user_title'];
                                $searchQuery .= " AND ord.user_id = '" . $_REQUEST['order_user_id'] . "'";
                            }
                        }
                        ?>
                        <form class="row flex-row" name="frmCat" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>">
                            <div class=" col-md-2 col-12 mt-2">
                                <label for="" class="text-white">Order ID</label>
                                <input type="number" class="input_style ord_id" name="ord_id" value="<?php print($ord_id); ?>" placeholder="Order ID:" autocomplete="off" onchange="javascript: frmCat.submit();">
                            </div>
                            <?php if (!isset($_REQUEST['user_id'])) { ?>
                                <div class=" col-md-3 col-12 mt-2">
                                    <label for="" class="text-white">Title</label>
                                    <input type="hidden" name="order_user_id" id="order_user_id" value="<?php print($order_user_id); ?>">
                                    <input type="text" class="input_style order_user_title" name="order_user_title" value="<?php print($order_user_title); ?>" placeholder="Title:" autocomplete="off" onchange="javascript: frmCat.submit();">
                                </div>
                            <?php } ?>
                        </form>
                        <form class="table_responsive" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <table>
                                <thead>
                                    <tr>
                                        <th width="50"><input type="checkbox" name="chkAll" onClick="setAll();"></th>
                                        <th>Order ID</th>
                                        <th>User Info </th>
                                        <th width="100">Shipping</th>
                                        <th width="100">Delivery</th>
                                        <th width="100">Amount </th>
                                        <th>Payment Type</th>
                                        <th>Transaction ID</th>
                                        <th width="147">Date / Time</th>
                                        <th>Payment Status</th>
                                        <th width="170">Delivery Status</th>
                                        <th>Order Status</th>
                                        <th width="50">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $Query = "SELECT ord.*, CONCAT(di.dinfo_fname, ' ', di.dinfo_lname) AS deliver_full_name, di.dinfo_phone, di.dinfo_email, di.dinfo_street, di.dinfo_address, di.dinfo_house_no, di.dinfo_usa_zipcode, di.dinfo_countries_id, c.countries_name, pm.pm_title_de AS pm_title, ds.d_status_name,u.utype_id, (SELECT ut.utype_name FROM user_type AS ut WHERE ut.utype_id = u.utype_id) utype_name, CONCAT(usp.usa_street, ' ', usp.usa_house_no) AS shipping_street_house, usp.usa_zipcode, usp.countries_id, usp.usa_address AS shipping_countrie_id FROM orders AS ord LEFT OUTER JOIN users AS u ON u.user_id = ord.user_id LEFT OUTER JOIN delivery_info AS di ON di.ord_id = ord.ord_id LEFT OUTER JOIN payment_method AS pm ON pm.pm_id = ord.ord_payment_method LEFT OUTER JOIN deli_status AS ds ON ds.d_status_id = ord.ord_delivery_status LEFT OUTER JOIN countries AS c ON c.countries_id = di.dinfo_countries_id LEFT OUTER JOIN user_shipping_address AS usp ON usp.user_id = ord.user_id AND usp.usa_type = '1'  " . $searchQuery . " ORDER BY ord.ord_datetime DESC";
                                    //print($Query);
                                    $counter = 0;
                                    $limit = 50;
                                    $start = $p->findStart($limit);
                                    $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], $Query));
                                    $pages = $p->findPages($count, $limit);
                                    $rs = mysqli_query($GLOBALS['conn'], $Query . " LIMIT " . $start . ", " . $limit);
                                    if (mysqli_num_rows($rs) > 0) {
                                        while ($row = mysqli_fetch_object($rs)) {
                                            $counter++;
                                            $strClass = 'label  label-danger';
                                            $user_info = "";
                                            $user_guest = "";
                                            $utype_id_as_guest = returnName("utype_id_as_guest", "users", "user_id", $row->user_id);
                                            if ($utype_id_as_guest > 0) {
                                                $user_guest = "Guest";
                                            }
                                            if ($row->utype_id == 3) {
                                                $user_info .= '<span class="btn btn-primary btn-style-light w-auto mb-2">' . rtrim($user_guest . " " . $row->utype_name, "Customer") . '</span><br>';
                                            } else {
                                                $user_info .= '<span class="btn btn-success btn-style-light w-auto mb-2">' . rtrim($user_guest . " " . $row->utype_name, "Customer") . '</span><br>';
                                            }
                                            $user_company_name = returnName("user_company_name", "users", "user_id", $row->user_id);
                                            if (!empty($user_company_name)) {
                                                $user_info .= $user_company_name . "<br>";
                                            }
                                            if (!empty($row->deliver_full_name)) {
                                                $user_info .= $row->deliver_full_name . "<br>";
                                            }
                                            if (!empty($row->dinfo_phone)) {
                                                $user_info .= $row->dinfo_phone . "<br>";
                                            }
                                            if (!empty($row->dinfo_email)) {
                                                $user_info .= $row->dinfo_email . "<br>";
                                            }
                                            $delivery_info = "";
                                            if (!empty($row->dinfo_street)) {
                                                $delivery_info .= $row->dinfo_street . " " . $row->dinfo_house_no . "<br>";
                                            }
                                            if (!empty($row->dinfo_usa_zipcode)) {
                                                $delivery_info .= $row->dinfo_usa_zipcode . "<br>";
                                            }
                                            if (!empty($row->countries_name)) {
                                                $delivery_info .= $row->countries_name . "<br>";
                                            }
                                            if (!empty($row->dinfo_address)) {
                                                $delivery_info .= $row->dinfo_address . "<br>";
                                            }
                                            $shipping_info = "";
                                            if ($row->ord_payment_method == 1) {
                                                if (!empty($row->shipping_street_house)) {
                                                    $shipping_info .= $row->shipping_street_house . "<br>";
                                                }
                                                if (!empty($row->usa_zipcode)) {
                                                    $shipping_info .= $row->usa_zipcode . "<br>";
                                                }
                                                if (!empty($row->shipping_countrie_id)) {
                                                    $shipping_info .= returnName("countries_name", "countries", "countrie_id", $row->shipping_countrie_id) . "<br>";
                                                }
                                                if (!empty($row->usa_address)) {
                                                    $shipping_info .= $row->usa_address . "<br>";
                                                }
                                            }
                                    ?>
                                            <tr <?php print(($row->ord_delivery_status == 0) ? 'style="background: #ff572229;"' : ''); ?>>
                                                <td><input type="checkbox" name="chkstatus[]" value="<?php print($row->ord_id); ?>"></td>
                                                <td><?php print($row->ord_id); ?></td>
                                                <td><?php print($user_info); ?></td>
                                                <td><?php print($shipping_info); ?></td>
                                                <td><?php print($delivery_info); ?></td>
                                                <td><?php print(((!empty($row->ord_note)) ? '<span class="btn btn-success btn-style-light w-auto">Benachrichtigung</span><br><br>' : '') . number_format($row->ord_amount + $row->ord_shipping_charges, "2", ",", ".")); ?> €</td>
                                                <td><?php print($row->pm_title); ?></td>
                                                <td><?php print($row->ord_payment_transaction_id); ?></td>
                                                <td><?php print($row->ord_datetime); ?></td>
                                                <td>
                                                    <?php
                                                    if ($row->ord_payment_status == 0) {
                                                        echo '<span class="btn btn-warning btn-style-light w-auto">Pending</span>';
                                                    } else {
                                                        echo '<span class="btn btn-success btn-style-light w-auto">Success</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if ($row->ord_delivery_status == 0) { ?>
                                                        <div class="row">
                                                            <div class="col-md-12 col-12 mt-3">
                                                                <input type="hidden" name="ord_id[]" value="<?php print($row->ord_id); ?>">
                                                                <select name="d_status_id[]" class="input_style" id="d_status_id" onchange="javascript: frm.submit();">
                                                                    <?php FillSelected2("deli_status", "d_status_id", "d_status_name", "", "d_status_id >= 0"); ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    <?php } elseif ($row->ord_delivery_status == 1) { ?>
                                                        <span class="btn btn-success btn-style-light w-auto"> <?php print($row->d_status_name); ?> </span>
                                                    <?php } elseif ($row->ord_delivery_status == 2) { ?>
                                                        <span class="btn btn-danger btn-style-light w-auto"> <?php print($row->d_status_name); ?> </span>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($row->ord_conform_status == 0) {
                                                        echo '<span class="btn btn-warning btn-style-light w-auto">Open</span>';
                                                    } else {
                                                        echo '<span class="btn btn-success btn-style-light w-auto">Close</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-xs btn-success btn-style-light w-auto" title="View Order" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?show&" . $qryStrURL . "ord_id=" . $row->ord_id); ?>';"><span class="material-icons icon material-xs">visibility</span></button>
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
                                    <input type="submit" name="btnPending" value="Pending" class="btn btn-warning btn-style-light w-100">
                                </div>
                                <div class=" col-md-1 col-12 mt-2">
                                    <input type="submit" name="btnCompleted" value="Completed" class="btn btn-success btn-style-light w-100">
                                </div>
                                <div class=" col-md-1 col-12 mt-2">
                                    <input type="submit" name="btnRejected" value="Rejected" class="btn btn-danger btn-style-light w-100" onclick="return confirm('Are you sure you want to delete selected item(s)?');">
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
    $('input.ord_id').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=ord_id&user_id=<?php print($user_id); ?>',
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
            var ord_id = $("#ord_id");
            $(ord_id).val(ui.item.value);
            //frmCat.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });

    $('input.order_user_title').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=order_user_title',
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
            var order_user_id = $("#order_user_id");
            var order_user_title = $("#order_user_title");
            $(order_user_id).val(ui.item.order_user_id);
            $(order_user_title).val(ui.item.value);
            //frmCat.submit();
            //return false;
        }
    });
</script>

</html>