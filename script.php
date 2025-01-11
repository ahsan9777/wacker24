<?php
include("includes/php_includes_top.php");
if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'brand':
            print("brand");
            die();
            $Query = "SELECT * FROM wacker_brand ORDER BY id ASC";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $brand_name = $row->brand_name;
                    $brand_logo = str_replace(" ", "-", strtolower(basename($row->brand_logo)));
                    $extension = pathinfo($brand_logo, PATHINFO_EXTENSION);
                    $brand_logo = str_replace(" ", "-", strtolower($row->brand_name) . "." . $extension);
                    $brand_category = $row->brand_category;
                    //$oldname = "files/brands/".basename($row->brand_logo);
                    //$newname = "files/brands/".str_replace(" ", "-", strtolower($row->brand_name).".".$extension;

                    /*if (rename($oldname, $newname) {
                        echo "File renamed successfully.";
                    } else {
                        echo "File renaming failed.";
                    }*/
                    $brand_id = getMaximum("brands", "brand_id");
                    mysqli_query($GLOBALS['conn'], "INSERT INTO brands (brand_id, cat_id, brand_name, brand_image) VALUES ('" . $brand_id . "', '" . $brand_category . "', '" . $brand_name . "', '" . $brand_logo . "')") or die(mysqli_error($GLOBALS['conn']));
                    print("extension: " . $extension . " brand_name: " . $brand_name . " brand_logo: " . $brand_logo . "<br>");
                }
            }
            break;

        case 'user_data':
            print("user_data");
            die();
            $counter = 0;
            $Query = "SELECT * FROM wacker_users ORDER BY id ASC";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $id = $row->id;
                    $customer_id = $row->customer_id;
                    $special_status = $row->special_status;
                    $user_invoice_payment = $row->invoice_payment;
                    $user_sepa_payment = $row->sepa_payment;
                    $user_name = $row->email;
                    $user_password = $row->password;
                    $gen_id = 0;
                    if ($row->gander == 'Herr') {
                        $gen_id = 1;
                    } elseif ($row->gander == 'Frau') {
                        $gen_id = 2;
                    }
                    $status_id = $row->status;
                    $countries_id = 162;
                    $user_datecreated = $row->created_date;
                    $utype_id = 3;;
                    if ($row->cusType == 1) {
                        $utype_id = 4;
                    }
                    $user_company_name = $row->company_name;
                    $user_tax_no = $row->tax_no;
                    $user_phone = $row->phone;
                    $user_fname = $row->fname;
                    $user_lname = $row->lname;
                    $user_id = getMaximum("users", "user_id");
                    mysqli_query($GLOBALS['conn'], "INSERT INTO users (user_id, id, customer_id, special_status, user_invoice_payment, user_sepa_payment, user_name, user_password, gen_id, status_id, user_datecreated, utype_id, user_company_name, user_tax_no, user_phone, user_fname, user_lname) VALUES ('" . $user_id . "', '" . $id . "', '" . $customer_id . "', '" . $special_status . "', '" . $user_invoice_payment . "', '" . $user_sepa_payment . "', '" . $user_name . "', '" . $user_password . "', '" . $gen_id . "', '" . $status_id . "', '" . $user_datecreated . "', '" . $utype_id . "', '" . $user_company_name . "', '" . $user_tax_no . "', '" . $user_phone . "', '" . $user_fname . "', '" . $user_lname . "')") or die(mysqli_error($GLOBALS['conn']));
                    $counter++;
                }
                print("Total no of record added: " . $counter);
            }
            break;

        case 'user_address_data':
            print("user_address_data");
            die();
            $counter = 0;
            $Query = "SELECT * FROM addressbook ORDER BY id ASC";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $id = $row->id;
                    $usa_street = $row->street;
                    $usa_house_no = $row->house;
                    $countries_id = 162;
                    $usa_contactno = $row->phone;
                    $old_user_id = $row->user_id;
                    $user_id = returnName("user_id", "users", "id", $old_user_id);
                    $usa_fname = $row->name;
                    $usa_defualt = $row->setaddress;
                    $usa_additional_info = $row->additional_info;

                    $usa_id = getMaximum("user_shipping_address", "usa_id");
                    mysqli_query($GLOBALS['conn'], "INSERT INTO user_shipping_address (usa_id, id, usa_street, usa_house_no, countries_id, usa_contactno, old_user_id, user_id, usa_fname, usa_defualt, usa_additional_info) VALUES ('" . $usa_id . "', '" . $id . "', '" . $usa_street . "', '" . $usa_house_no . "', '" . $countries_id . "', '" . $usa_contactno . "', '" . $old_user_id . "', '" . $user_id . "', '" . $usa_fname . "', '" . $usa_defualt . "', '" . $usa_additional_info . "')") or die(mysqli_error($GLOBALS['conn']));
                    $counter++;
                }
                print("Total no of record added: " . $counter);
            }
            break;

        case 'order_data':
            print("order_data");
            die();
            $counter = 0;
            $Query = "SELECT * FROM order_manager ORDER BY order_id ASC";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $old_order_id = $row->order_id;
                    $custome_order_id = $row->custome_order_id;
                    $ord_delivery_status = $row->seen;
                    $ord_conform_status = $row->seen;
                    $ord_datetime = $row->order_date;
                    $old_user_id = $row->user_id;
                    $user_id = returnName("user_id", "users", "id", $old_user_id);
                    $ord_payment_status = $row->payment_status;
                    $ord_payment_method = 1;
                    if ($row->payment_method == 'paypal') {
                        $ord_payment_method = 2;
                    } elseif ($row->payment_method == 'visa') {
                        $ord_payment_method = 5;
                    }
                    $old_ord_payment_method = $row->payment_method;
                    $ord_payment_transaction_id = $row->payment_transaction_id;
                    $ord_payment_short_id = $row->payment_short_id;
                    $ord_payment_info_detail = $row->payment_info_detail;
                    $ord_note = $row->order_note;
                    $ord_id = getMaximum("orders", "ord_id");
                    mysqli_query($GLOBALS['conn'], "INSERT INTO orders (ord_id, old_order_id, custome_order_id, ord_delivery_status, ord_conform_status, ord_datetime, old_user_id, user_id, ord_payment_status, ord_payment_method, old_ord_payment_method, ord_payment_transaction_id, ord_payment_short_id, ord_payment_info_detail, ord_note) VALUES ('" . $ord_id . "', '" . $old_order_id . "', '" . $custome_order_id . "', '" . $ord_delivery_status . "', '" . $ord_conform_status . "', '" . $ord_datetime . "', '" . $old_user_id . "', '" . $user_id . "', '" . $ord_payment_status . "', '" . $ord_payment_method . "', '" . $old_ord_payment_method . "', '" . $ord_payment_transaction_id . "', '" . $ord_payment_short_id . "', '" . $ord_payment_info_detail . "', '" . $ord_note . "')") or die(mysqli_error($GLOBALS['conn']));
                    $counter++;
                }
                print("Total no of record added: " . $counter);
            }
            break;

        case 'order_items_data':
            print("order_items_data");
            die();
            $counter = 0;
            $Query = "SELECT * FROM users_orders ORDER BY order_id ASC";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $old_order_id = $row->order_id;
                    $ord_id = returnName("ord_id", "orders", "old_order_id", $old_order_id);
                    $supplier_id = $row->supplier_aid;
                    $pro_id = returnName("pro_id", "products", "supplier_id", $supplier_id);
                    $invoice_id = $row->invoice_id;
                    $billing_address_id = $row->billing_address_id;
                    $oi_net_total = $row->price;
                    $oi_qty = $row->qty;
                    $oi_amount = ($oi_net_total / $oi_qty) / 1.19;
                    $oi_gross_total = $oi_amount * $oi_qty;
                    $oi_gst = $oi_gross_total * config_gst;

                    //print("old_order_id: ".$old_order_id." ord_id: ".$ord_id." oi_amount: ".$oi_amount." oi_qty: ".$oi_qty." oi_gross_total: ".$oi_gross_total." oi_gst: ".$oi_gst." oi_net_total: ".$oi_net_total);die();
                    $oi_id = getMaximum("order_items", "oi_id");
                    mysqli_query($GLOBALS['conn'], "INSERT INTO order_items (oi_id, ord_id, old_order_id, supplier_id, pro_id, oi_amount, oi_qty, oi_gross_total, oi_gst, oi_net_total) VALUES ('" . $oi_id . "', '" . $ord_id . "', '" . $old_order_id . "', '" . $supplier_id . "', '" . $pro_id . "', '" . $oi_amount . "', '" . $oi_qty . "', '" . $oi_gross_total . "', '" . $oi_gst . "', '" . $oi_net_total . "')") or die(mysqli_error($GLOBALS['conn']));
                    mysqli_query($GLOBALS['conn'], "UPDATE orders SET invoice_id = '" . $invoice_id . "', billing_address_id = '" . $billing_address_id . "', ord_gross_total=(SELECT SUM(oi_gross_total) FROM order_items WHERE ord_id= '" . $ord_id . "'), ord_gst=(SELECT SUM(oi_gst) FROM order_items WHERE ord_id= '" . $ord_id . "'), ord_amount=(SELECT SUM(oi_net_total) FROM order_items WHERE ord_id= '" . $ord_id . "') WHERE ord_id= '" . $ord_id . "' ") or die(mysqli_error($GLOBALS['conn']));
                    $counter++;
                }
                print("Total no of record added: " . $counter);
            }
            break;

        case 'delivery_info_data':
            print("delivery_info_data");die();
            $counter = 0;
            $Query = "SELECT * FROM orders WHERE billing_address_id > 0 ORDER BY ord_datetime ASC";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $ord_id = $row->ord_id;
                    $billing_address_id = $row->billing_address_id;
                    $ord_shipping_charges = 0;
                    if ($row->ord_gross_total <= config_condition_courier_amount) {
                        $ord_shipping_charges = config_courier_fix_charges;
                    }
                    $Query1 = "SELECT usa.*, u.user_name  FROM user_shipping_address AS usa LEFT OUTER JOIN users AS u ON u.user_id = usa.user_id WHERE usa.id = '".$billing_address_id."'";
                    $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
                    if (mysqli_num_rows($rs1) > 0) {
                        $rw = mysqli_fetch_object($rs1);
                        $usa_id = $rw->usa_id;
                        $user_id = $rw->user_id;
                        $dinfo_fname = $rw->usa_fname;
                        $dinfo_lname = $rw->usa_lname;
                        $dinfo_email = $rw->user_name;
                        $dinfo_phone = $rw->usa_contactno;
                        $dinfo_street = $rw->usa_street;
                        $dinfo_house_no = $rw->usa_house_no;
                        $dinfo_address = $rw->usa_address;
                        $dinfo_countries_id = $rw->countries_id;
                        $dinfo_usa_zipcode = $rw->usa_zipcode;
                        $dinfo_id = getMaximum("delivery_info", "dinfo_id");
                        mysqli_query($GLOBALS['conn'], "INSERT INTO delivery_info (dinfo_id, ord_id, user_id, usa_id, dinfo_fname, dinfo_lname, dinfo_phone, dinfo_email, dinfo_street, dinfo_house_no, dinfo_address, dinfo_countries_id, dinfo_usa_zipcode) VALUES ('" . $dinfo_id . "', '" . $ord_id . "', '" . $user_id . "', '" . $usa_id . "', '" .$dinfo_fname . "', '" .$dinfo_lname . "', '" .$dinfo_phone . "', '" .$dinfo_email . "', '" .$dinfo_street . "', '" .$dinfo_house_no . "', '" .$dinfo_address . "', '" .$dinfo_countries_id . "', '" . $dinfo_usa_zipcode . "')") or die(mysqli_error($GLOBALS['conn']));
                    }
                    mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_shipping_charges = '".$ord_shipping_charges."' WHERE ord_id= '" . $ord_id."' ") or die(mysqli_error($GLOBALS['conn']));
                    $counter++;
                }
                print("Total no of record added: " . $counter);
            }
            break;
    }
}
