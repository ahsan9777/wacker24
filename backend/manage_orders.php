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

//--------------Button Order Rejected --------------------
/*if (isset($_REQUEST['btnRejected'])) {
    //print_r($_REQUEST);die();
    $orders_table_data = returnMultiName("ord_payment_entity_id, ord_payment_transaction_id, ord_amount, ord_shipping_charges, ord_capture_status, ord_payment_method", "orders", "ord_id", $_REQUEST['ord_id'], 6);
    $ord_amount = number_format(($orders_table_data['data_3'] + $orders_table_data['data_4']), "2", ".", "");
    if ($orders_table_data['data_5'] == 1 && $orders_table_data['data_6'] != 1) {
        $payment_status_request = RefundPayment($orders_table_data['data_1'], $orders_table_data['data_2'], $ord_amount);
        $payment_status_responseData = json_decode($payment_status_request, true);
        /*print("<pre>");
        print_r($payment_status_responseData);
        print("</pre>");die();*/
/*if ($payment_status_responseData['result']['code'] == '000.100.110' || $payment_status_responseData['result']['code'] == '000.000.000' || $payment_status_responseData['result']['description'] == 'Transaction succeeded') {
            orderquantityUpdate($_REQUEST['ord_id']);
            mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_payment_status = '0', ord_capture_status = '1', ord_capture_id = '" . dbStr(trim($payment_status_responseData['id'])) . "', ord_capture_request_detail = '" . dbStr(trim($payment_status_request)) . "', ord_delivery_status='2', ord_conform_status = '1' WHERE ord_id = " . $_REQUEST['ord_id']);
            $mailer->order_cancelation($_REQUEST['ord_id']);
            header("Location: " . $_SERVER['PHP_SELF'] . "?op=2");
        }
    } elseif ($orders_table_data['data_6'] == 1) {
        mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_delivery_status='0', ord_delivery_status='2', ord_conform_status = '1' WHERE ord_id = " . $_REQUEST['ord_id']);
        orderquantityUpdate($_REQUEST['ord_id']);
        $mailer->order_cancelation($_REQUEST['ord_id']);
        header("Location: " . $_SERVER['PHP_SELF'] . "?op=2");
    } else {
        $class = "alert alert-info";
        $strMSG = "Please Select Alteast One Checkbox";
    }
}*/


//--------------Button delivery status update --------------------
if (isset($_REQUEST['d_status_id']) && gettype($_REQUEST['d_status_id']) == "array") {
    //print_r($_REQUEST);die();
    if (isset($_REQUEST['d_status_id'])) {
        for ($i = 0; $i < count($_REQUEST['d_status_id']); $i++) {
            if ($_REQUEST['d_status_id'][$i] == 1) {
                $orders_table_data = returnMultiName("ord_payment_entity_id, ord_payment_transaction_id, ord_amount, ord_shipping_charges, ord_capture_status, ord_payment_method", "orders", "ord_id", $_REQUEST['ord_id'][$i], 6);
                $ord_amount = number_format(($orders_table_data['data_3'] + $orders_table_data['data_4']), "2", ".", "");
                //print($orders_table_data['data_1']);
                //print_r($orders_table_data); die();
                /*$payment_status_request = check_payment_status($orders_table_data['data_2'], $orders_table_data['data_1']);
            $payment_status_responseData = json_decode($payment_status_request, true);
            print("<pre>");
            print_r($payment_status_responseData);
            print("</pre>");die();*/
                //$ord_amount = number_format(0.50, "2", ".", "");
                if ($orders_table_data['data_5'] == 0 && !in_array($orders_table_data['data_6'], array(1,7)) ) {
                    $payment_status_request = capturePayment($orders_table_data['data_1'], $orders_table_data['data_2'], $ord_amount);
                    $payment_status_responseData = json_decode($payment_status_request, true);
                    /*print("<pre>");
                print_r($payment_status_responseData);
                print("</pre>");die();*/
                    if ($payment_status_responseData['result']['code'] == '000.100.110' || $payment_status_responseData['result']['code'] == '000.000.000' || $payment_status_responseData['result']['description'] == 'Transaction succeeded') {
                        mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_payment_status = '1', ord_capture_status = '1', ord_capture_id = '" . dbStr(trim($payment_status_responseData['id'])) . "', ord_capture_request_detail = '" . dbStr(trim($payment_status_request)) . "', ord_delivery_status='1', ord_conform_status = '1' WHERE ord_id = " . $_REQUEST['ord_id'][$i]);
                        $mailer->order($_REQUEST['ord_id'][$i]);
                        header("Location: " . $_SERVER['PHP_SELF'] . "?op=2");
                    }
                } elseif (in_array($orders_table_data['data_6'], array(1,7))) {
                    mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_delivery_status='" . $_REQUEST['d_status_id'][$i] . "', ord_conform_status = '1' WHERE ord_id = " . $_REQUEST['ord_id'][$i]);
                    $mailer->order($_REQUEST['ord_id'][$i]);
                    header("Location: " . $_SERVER['PHP_SELF'] . "?op=2");
                } else {
                    $class = "alert alert-success";
                    $strMSG = "Record(s) already updated successfully";
                }
            } elseif ($_REQUEST['d_status_id'][$i] == 2) {
                mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_delivery_status = '2', ord_conform_status = '1' WHERE ord_id = " . $_REQUEST['ord_id'][$i]);
                orderquantityUpdate($_REQUEST['ord_id'][$i]);
                $mailer->order_cancelation($_REQUEST['ord_id'][$i]);
                header("Location: " . $_SERVER['PHP_SELF'] . "?op=2");
            }
        }
    } else {
        $class = "alert alert-info";
        $strMSG = "Please Select Alteast One Checkbox";
    }
} elseif (isset($_REQUEST['d_status_id'])) {
    //print_r($_REQUEST);die();
    if ($_REQUEST['d_status_id'] == 1) {
        $orders_table_data = returnMultiName("ord_payment_entity_id, ord_payment_transaction_id, ord_amount, ord_shipping_charges, ord_capture_status, ord_payment_method", "orders", "ord_id", $_REQUEST['ord_id'], 6);
        $ord_amount = number_format(($orders_table_data['data_3'] + $orders_table_data['data_4']), "2", ".", "");
        if ($orders_table_data['data_5'] == 0 && !in_array($orders_table_data['data_6'], array(1,7)) ) {
            $payment_status_request = capturePayment($orders_table_data['data_1'], $orders_table_data['data_2'], $ord_amount);
            $payment_status_responseData = json_decode($payment_status_request, true);
            /*print("<pre>");
        print_r($payment_status_responseData);
        print("</pre>");die();*/
            if ($payment_status_responseData['result']['code'] == '000.100.110' || $payment_status_responseData['result']['code'] == '000.000.000' || $payment_status_responseData['result']['description'] == 'Transaction succeeded') {
                mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_payment_status = '1', ord_capture_status = '1', ord_capture_id = '" . dbStr(trim($payment_status_responseData['id'])) . "', ord_capture_request_detail = '" . dbStr(trim($payment_status_request)) . "', ord_delivery_status='" . $_REQUEST['d_status_id'] . "', ord_conform_status = '1' WHERE ord_id = " . $_REQUEST['ord_id']);
                $mailer->order($_REQUEST['ord_id'][$i]);
            }
        } elseif (in_array($orders_table_data['data_6'], array(1,7))) {
            mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_delivery_status='" . $_REQUEST['d_status_id'] . "', ord_conform_status = '1' WHERE ord_id = " . $_REQUEST['ord_id']);
            $mailer->order($_REQUEST['ord_id'][$i]);
        }
    } elseif ($_REQUEST['d_status_id'] == 2) {
        mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_delivery_status = '2', ord_conform_status = '1' WHERE ord_id = " . $_REQUEST['ord_id']);
        orderquantityUpdate($_REQUEST['ord_id']);
        $mailer->order_cancelation($_REQUEST['ord_id']);
    }
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
                                        <!--<th width="100">Order ID</th>
                                        <th width="250">User Info </th>
                                        <th width="100">Shipping</th>
                                        <th width="250">Delivery</th>
                                        <th>Amount </th>
                                        <th>Payment Type</th>
                                        <th>Transaction ID</th>
                                        <th width="147">Date / Time</th>
                                        <th>Payment Status</th>
                                        <th width="166">Delivery Status</th>
                                        <th>Order Status</th>-->

                                        <th width="100">Auftrags-ID</th>
                                        <th width="250" >User Info </th>
                                        <th width="100">Rechnungsadresse</th>
                                        <th width="250">Lieferadresse</th>
                                        <th >Betrag</th>
                                        <th>Zahlungsart</th>
                                        <th>Transaktions-ID</th>
                                        <th width="147">Datum/Uhrzeit</th>
                                        <th>Zahlungsstatus</th>
                                        <th width="166">Lieferstatus</th>
                                        <th>Auftragsstatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ord_note = "";
                                    $delivery_instruction = "";
                                    $Query = "SELECT ord.*, di.dinfo_additional_info, di.delivery_instruction, CONCAT(di.dinfo_fname, ' ', di.dinfo_lname) AS deliver_full_name, di.dinfo_phone, di.dinfo_email, di.dinfo_street, di.dinfo_address, di.dinfo_house_no, di.dinfo_usa_zipcode, di.dinfo_countries_id, c.countries_name, pm.pm_title_de AS pm_title, ds.d_status_name,u.utype_id, (SELECT ut.utype_name FROM user_type AS ut WHERE ut.utype_id = u.utype_id) utype_name, usp.usa_additional_info, CONCAT(usp.usa_street, ' ', usp.usa_house_no) AS shipping_street_house, usp.usa_zipcode, usp.countries_id, usp.usa_address AS shipping_countrie_id FROM orders AS ord LEFT OUTER JOIN users AS u ON u.user_id = ord.user_id LEFT OUTER JOIN delivery_info AS di ON di.ord_id = ord.ord_id LEFT OUTER JOIN payment_method AS pm ON pm.pm_id = ord.ord_payment_method LEFT OUTER JOIN deli_status AS ds ON ds.d_status_id = ord.ord_delivery_status LEFT OUTER JOIN countries AS c ON c.countries_id = di.dinfo_countries_id LEFT OUTER JOIN user_shipping_address AS usp ON usp.user_id = ord.user_id AND usp.usa_type = '1' WHERE ord.ord_id = '" . $_REQUEST['ord_id'] . "' ORDER BY ord.ord_datetime DESC";
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
                                        if (!empty($row->dinfo_additional_info)) {
                                                $delivery_info .= $row->dinfo_additional_info . "<br>";
                                        } elseif (!empty($user_company_name)) {
                                            $delivery_info .= $user_company_name . "<br>";
                                        }
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
                                            if (!empty($row->usa_additional_info)) {
                                                    $shipping_info .= $row->usa_additional_info . "<br>";
                                                }
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
                                        $delivery_instruction = $row->delivery_instruction;
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
                                                    echo '<span class="btn btn-success btn-style-light w-auto">PA</span>';
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
                    <?php if (!empty($delivery_instruction)) { ?>
                        <div class="table-controls mt-3">
                            &nbsp;
                        </div>
                        <div class="main_table_container">
                            <?php print($delivery_instruction); ?>
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
                                    $Query = "SELECT oi.*, pro.pro_custom_add, pro.pro_description_short, pg.pg_mime_source_url, ord.ord_gross_total, ord.ord_gst, ord.ord_discount, ord.ord_amount, ord.ord_shipping_charges FROM order_items AS oi LEFT OUTER JOIN orders AS ord ON ord.ord_id = oi.ord_id LEFT OUTER JOIN products AS pro ON pro.supplier_id = oi.supplier_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pro.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_source_url ASC LIMIT 1) WHERE oi.ord_id =  '" . $_REQUEST['ord_id'] . "' ORDER BY oi.oi_type DESC";
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
                                            $order_type = "Lieferung";
                                            if ($row->oi_type > 0) {
                                                $order_type = '<span class="btn btn-primary btn-style-light w-auto mb-2">Abholung</span><br>';
                                            } else {
                                                $order_type = '<span class="btn btn-success btn-style-light w-auto mb-2">Lieferung</span><br>';
                                            }

                                            $pg_mime_source_url = $row->pg_mime_source_url;
                                            if($row->pro_custom_add > 0){
                                                $pg_mime_source_url = $GLOBALS['siteURL'].$row->pg_mime_source_url;
                                            }
                                    ?>
                                            <tr>
                                                <td>
                                                    <div class="popup_container">
                                                        <div class="container__img-holder">
                                                            <img src="<?php print(get_image_link(427, $pg_mime_source_url)); ?>">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php print($row->supplier_id); ?></td>
                                                <td><?php print($order_type . $row->pro_description_short); ?></td>
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
                                        <!--<th>Order ID</th>
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
                                        <th width="90">Action</th>-->
                                        <th >Auftrags-ID</th>
                                        <th>User Info </th>
                                        <th width="100">Rechnungsadresse</th>
                                        <th width="500">Lieferadresse</th>
                                        <th width="100">Betrag</th>
                                        <th>Zahlungsart</th>
                                        <th >Transaktions-ID</th>
                                        <th width="147">Datum/Uhrzeit</th>
                                        <th>Zahlungsstatus</th>
                                        <th width="170">Lieferstatus</th>
                                        <th>Auftragsstatus</th>
                                        <th width="90">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $Query = "SELECT ord.*, di.dinfo_additional_info, CONCAT(di.dinfo_fname, ' ', di.dinfo_lname) AS deliver_full_name, di.dinfo_phone, di.dinfo_email, di.dinfo_street, di.dinfo_address, di.dinfo_house_no, di.dinfo_usa_zipcode, di.dinfo_countries_id, c.countries_name, pm.pm_title_de AS pm_title, ds.d_status_name,u.utype_id, (SELECT ut.utype_name FROM user_type AS ut WHERE ut.utype_id = u.utype_id) utype_name, usp.usa_additional_info,  CONCAT(usp.usa_street, ' ', usp.usa_house_no) AS shipping_street_house, usp.usa_zipcode, usp.countries_id, usp.usa_address AS shipping_countrie_id FROM orders AS ord LEFT OUTER JOIN users AS u ON u.user_id = ord.user_id LEFT OUTER JOIN delivery_info AS di ON di.ord_id = ord.ord_id LEFT OUTER JOIN payment_method AS pm ON pm.pm_id = ord.ord_payment_method LEFT OUTER JOIN deli_status AS ds ON ds.d_status_id = ord.ord_delivery_status LEFT OUTER JOIN countries AS c ON c.countries_id = di.dinfo_countries_id LEFT OUTER JOIN user_shipping_address AS usp ON usp.user_id = ord.user_id AND usp.usa_type = '1'  " . $searchQuery . " ORDER BY ord.ord_datetime DESC";
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
                                            if (!empty($row->dinfo_additional_info)) {
                                                $delivery_info .= $row->dinfo_additional_info . "<br>";
                                            } elseif (!empty($user_company_name)) {
                                                $delivery_info .= $user_company_name . "<br>";
                                            }
                                            if (!empty($row->dinfo_street)) {
                                                $delivery_info .= $row->dinfo_street . " " . $row->dinfo_house_no . "<br>";
                                            }
                                            if (!empty($row->dinfo_usa_zipcode)) {
                                                $delivery_info .= $row->dinfo_usa_zipcode . "<br>";
                                            }
                                            if (!empty($row->countries_name)) {
                                                $delivery_info .= $row->countries_name . "<br>";
                                            }
                                            $shipping_info = "";
                                            if ($row->ord_payment_method == 1) {
                                                if (!empty($row->usa_additional_info)) {
                                                    $shipping_info .= $row->usa_additional_info . "<br>";
                                                }
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
                                                        echo '<span class="btn btn-success btn-style-light w-auto">PA</span>';
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
                                                    <?php } /*elseif ($row->ord_delivery_status == 0) { ?>
                                                        <div class="row">
                                                            <div class="col-md-12 col-12 mt-3">
                                                                <input type="hidden" name="ord_id[]" value="<?php print($row->ord_id); ?>">
                                                                <select name="d_status_id[]" class="input_style" id="d_status_id" onchange="javascript: frm.submit();">
                                                                    <?php FillSelected2("deli_status", "d_status_id", "d_status_name", "", "d_status_id IN (0,1)"); ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    <?php }*/ elseif ($row->ord_delivery_status == 1) { ?>
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
                                                    <?php /* if (!in_array($row->ord_delivery_status, array(0, 2)) && $row->ord_payment_method != 1) { ?>
                                                        <button type="button" class="btn btn-xs btn-danger btn-style-light w-auto mt-1" title="Order Reject" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?btnRejected&" . $qryStrURL . "ord_id=" . $row->ord_id); ?>';"><span class="material-icons icon material-xs">close</span></button>
                                                    <?php } */ ?>
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