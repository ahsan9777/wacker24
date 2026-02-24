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
                    $countries_id = 81;
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
                    mysqli_query($GLOBALS['conn'], "INSERT INTO users (user_id, id, customer_id, special_status, user_invoice_payment, user_sepa_payment, user_name, user_password, gen_id, status_id, user_datecreated, utype_id, user_company_name, user_tax_no, user_phone, user_fname, user_lname, countries_id) VALUES ('" . $user_id . "', '" . $id . "', '" . $customer_id . "', '" . $special_status . "', '" . $user_invoice_payment . "', '" . $user_sepa_payment . "', '" . $user_name . "', '" . $user_password . "', '" . $gen_id . "', '" . $status_id . "', '" . $user_datecreated . "', '" . $utype_id . "', '" . $user_company_name . "', '" . $user_tax_no . "', '" . $user_phone . "', '" . $user_fname . "', '" . $user_lname . "', '" . $countries_id . "')") or die(mysqli_error($GLOBALS['conn']));
                    $counter++;
                }
                print("Total no of record added: " . $counter);
            }
            break;

        case 'user_shipping_address_data':
            print("user_shipping_address_data");
            die();
            $counter = 0;
            $Query = "SELECT * FROM invoice_address ORDER BY id ASC";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $id = $row->id;
                    $usa_street = $row->street;
                    $usa_house_no = $row->house;
                    $usa_zipcode = $row->location;
                    $countries_id = 81;
                    $usa_contactno = $row->phone;
                    $old_user_id = $row->user_id;
                    $user_id = returnName("user_id", "users", "id", $old_user_id);
                    $usa_fname = $row->name;
                    $usa_additional_info = $row->additional_info;

                    $usa_id = getMaximum("user_shipping_address", "usa_id");
                    mysqli_query($GLOBALS['conn'], "INSERT INTO user_shipping_address (usa_id, id, usa_type, usa_street, usa_house_no, usa_zipcode, countries_id, usa_contactno, old_user_id, user_id, usa_fname, usa_additional_info) VALUES ('" . $usa_id . "', '" . $id . "', '1', '" . $usa_street . "', '" . $usa_house_no . "', '" . $usa_zipcode . "', '" . $countries_id . "', '" . $usa_contactno . "', '" . $old_user_id . "', '" . $user_id . "', '" . $usa_fname . "', '" . $usa_additional_info . "')") or die(mysqli_error($GLOBALS['conn']));
                    $counter++;
                }
                print("Total no of record added: " . $counter);
            }
            break;

        case 'user_delivery_address_data':
            print("user_delivery_address_data");
            die();
            $counter = 0;
            $Query = "SELECT * FROM addressbook ORDER BY id ASC";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $id = $row->id;
                    $usa_street = $row->street;
                    $usa_house_no = $row->house;
                    $usa_zipcode = $row->location;
                    $countries_id = 81;
                    $usa_contactno = $row->phone;
                    $old_user_id = $row->user_id;
                    $user_id = returnName("user_id", "users", "id", $old_user_id);
                    $usa_fname = $row->name;
                    $usa_defualt = $row->setaddress;
                    $usa_additional_info = $row->additional_info;

                    $usa_id = getMaximum("user_shipping_address", "usa_id");
                    mysqli_query($GLOBALS['conn'], "INSERT INTO user_shipping_address (usa_id, id, usa_street, usa_house_no, usa_zipcode, countries_id, usa_contactno, old_user_id, user_id, usa_fname, usa_defualt, usa_additional_info) VALUES ('" . $usa_id . "', '" . $id . "', '" . $usa_street . "', '" . $usa_house_no . "', '" . $usa_zipcode . "', '" . $countries_id . "', '" . $usa_contactno . "', '" . $old_user_id . "', '" . $user_id . "', '" . $usa_fname . "', '" . $usa_defualt . "', '" . $usa_additional_info . "')") or die(mysqli_error($GLOBALS['conn']));
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
            print("delivery_info_data");
            die();
            $counter = 0;
            $Query = "SELECT * FROM orders WHERE billing_address_id > 0 ORDER BY ord_datetime ASC";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $ord_id = $row->ord_id;
                    $billing_address_id = $row->billing_address_id;
                    $ord_shipping_charges = 0;
                    if ($row->ord_amount <= config_condition_courier_amount) {
                        $ord_shipping_charges = config_courier_fix_charges;
                    }
                    $Query1 = "SELECT usa.*, u.user_name  FROM user_shipping_address AS usa LEFT OUTER JOIN users AS u ON u.user_id = usa.user_id WHERE usa.id = '" . $billing_address_id . "' AND usa_type = '0'";
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
                        mysqli_query($GLOBALS['conn'], "INSERT INTO delivery_info (dinfo_id, ord_id, user_id, usa_id, dinfo_fname, dinfo_lname, dinfo_phone, dinfo_email, dinfo_street, dinfo_house_no, dinfo_address, dinfo_countries_id, dinfo_usa_zipcode) VALUES ('" . $dinfo_id . "', '" . $ord_id . "', '" . $user_id . "', '" . $usa_id . "', '" . $dinfo_fname . "', '" . $dinfo_lname . "', '" . $dinfo_phone . "', '" . $dinfo_email . "', '" . $dinfo_street . "', '" . $dinfo_house_no . "', '" . $dinfo_address . "', '" . $dinfo_countries_id . "', '" . $dinfo_usa_zipcode . "')") or die(mysqli_error($GLOBALS['conn']));
                    }
                    mysqli_query($GLOBALS['conn'], "UPDATE orders SET ord_shipping_charges = '" . $ord_shipping_charges . "' WHERE ord_id= '" . $ord_id . "' ") or die(mysqli_error($GLOBALS['conn']));
                    $counter++;
                }
                print("Total no of record added: " . $counter);
            }
            break;

        case 'shopping_list':
            print("shopping_list");
            die();
            $counter = 0;
            $Query = "SELECT * FROM list_section ORDER BY id ASC";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $old_id = $row->id;
                    $old_user_id = $row->user_id;
                    $user_id = returnName("user_id", "users", "id", $old_user_id);
                    $sl_title = $row->name;

                    $sl_id = getMaximum("shopping_list", "sl_id");
                    mysqli_query($GLOBALS['conn'], "INSERT INTO shopping_list (sl_id, old_id, old_user_id, user_id, sl_title) VALUES ('" . $sl_id . "', '" . $old_id . "', '" . $old_user_id . "', '" . $user_id . "', '" . $sl_title . "')") or die(mysqli_error($GLOBALS['conn']));
                    $counter++;
                }
                print("Total no of record added: " . $counter);
            }
            break;

        case 'wishlist':
            print("wishlist");
            die();
            $counter = 0;
            $Query = "SELECT * FROM mylist ORDER BY id ASC";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $old_id = $row->id;
                    $old_user_id = $row->user_id;
                    $old_section_id = $row->section_id;
                    $user_id = returnName("user_id", "users", "id", $old_user_id);
                    $sl_id = returnName("sl_id", "shopping_list", "old_id", $old_section_id);
                    $supplier_id = $row->supplier_aid;

                    $wl_id = getMaximum("wishlist", "wl_id");
                    mysqli_query($GLOBALS['conn'], "INSERT INTO wishlist (wl_id, old_id, old_user_id, user_id, old_section_id, sl_id, supplier_id) VALUES ('" . $wl_id . "', '" . $old_id . "', '" . $old_user_id . "', '" . $user_id . "', '" . $old_section_id . "', '" . $sl_id . "', '" . $supplier_id . "')") or die(mysqli_error($GLOBALS['conn']));
                    $counter++;
                }
                print("Total no of record added: " . $counter);
            }
            break;

        case 'user_special_price':
            print("user_special_price");
            die();
            $counter = 0;
            $Query = "SELECT * FROM special_price ORDER BY id ASC";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $old_id = $row->id;
                    $old_user_id = $row->user_id;
                    $user_id = returnName("user_id", "users", "id", $old_user_id);
                    $supplier_id = $row->supplier_aid;
                    $level_one_id = $row->cat_id;
                    $level_two_id = $row->sub_cat_id;
                    if ($row->fixed > 0) {
                        $usp_price_type = 1;
                        $usp_discounted_value = $row->fixed;
                    } else {
                        $usp_price_type = 0;
                        $usp_discounted_value = $row->percentage;
                    }
                    $usp_addedby = 2;
                    $usp_cdate = $row->date;

                    $usp_id = getMaximum("user_special_price", "usp_id");
                    mysqli_query($GLOBALS['conn'], "INSERT INTO user_special_price (usp_id, old_id, old_user_id, user_id, supplier_id, level_one_id, level_two_id, usp_price_type, usp_discounted_value, usp_addedby, usp_cdate) VALUES ('" . $usp_id . "', '" . $old_id . "', '" . $old_user_id . "', '" . $user_id . "', '" . $supplier_id . "', '" . $level_one_id . "', '" . $level_two_id . "', '" . $usp_price_type . "', '" . $usp_discounted_value . "', '" . $usp_addedby . "', '" . $usp_cdate . "')") or die(mysqli_error($GLOBALS['conn']));
                    $counter++;
                }
                print("Total no of record added: " . $counter);
            }
            break;

        case 'manufacture_paramer':
            print("manufacture_paramer");die();
            $counter = 0;
            $Query = "SELECT * FROM manufacture ORDER BY manf_id ASC";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $manf_id = $row->manf_id;
                    $manf_name_params = convertGermanChars($row->manf_name);
                    $manf_name_params = url_clean($manf_name_params);

                    mysqli_query($GLOBALS['conn'], "UPDATE manufacture SET manf_name_params = '" . dbStr(trim($manf_name_params)) . "' WHERE manf_id = '" . $manf_id . "' ") or die(mysqli_error($GLOBALS['conn']));
                    $counter++;
                }
                print("Total no of record added: " . $counter);
            }
            break;

        case 'schulranzen_price_list':
            print("schulranzen_price_list");die();
            $counter = 0;
            $counter1 = 0;
            mysqli_query($GLOBALS['conn'], "UPDATE products SET  pro_status = '0' WHERE pro_type = '20' ") or die(mysqli_error($GLOBALS['conn']));
            $Query = "SELECT * FROM schulranzen_price_list ORDER BY spl_id ASC";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $checkrecord = 0;
                    $supplier_id = $row->supplier_id;
                    $pbp_price_amount = $row->pbp_price_amount_without_tax;
                    //$manf_name = $row->manf_name;

                    $checkrecord = checkrecord("pro_id", "products", "supplier_id = '" . $supplier_id . "'");
                    if ($checkrecord > 0) {
                        $counter1++;
                        $manf_id = 0;
                        /*$Query1 = "SELECT * FROM manufacture WHERE  manf_name = '" . dbStr(trim($manf_name)) . "'";
                        $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
                        if (mysqli_num_rows($rs1) > 0) {
                            $row1 = mysqli_fetch_object($rs1);
                            $manf_id = $row1->manf_id;
                        } else {
                            $manf_id = getMaximum("manufacture", "manf_id");
                            mysqli_query($GLOBALS['conn'], "INSERT INTO manufacture (manf_id, manf_name, manf_name_params) VALUES ('" . $manf_id . "', '" . dbStr(trim($manf_name)) . "', '" . dbStr(trim(url_clean($manf_name))) . "')") or die(mysqli_error($GLOBALS['conn']));
                        }
                        mysqli_query($GLOBALS['conn'], "UPDATE products SET manf_id = '".$manf_id."', pro_status = '1' WHERE supplier_id = '" . $supplier_id . "' ") or die(mysqli_error($GLOBALS['conn']));*/
                        mysqli_query($GLOBALS['conn'], "UPDATE products SET  pro_status = '1' WHERE supplier_id = '" . $supplier_id . "' ") or die(mysqli_error($GLOBALS['conn']));
                        mysqli_query($GLOBALS['conn'], "UPDATE products_bundle_price SET pbp_price_amount = '" . $pbp_price_amount . "' WHERE pbp_lower_bound = '1' AND supplier_id = '" . $supplier_id . "' ") or die(mysqli_error($GLOBALS['conn']));
                        mysqli_query($GLOBALS['conn'], "UPDATE schulranzen_price_list SET spl_status = '1' WHERE supplier_id = '" . $supplier_id . "' ") or die(mysqli_error($GLOBALS['conn']));
                    }

                    $counter++;
                }
                print("Total no of record added: " . $counter1 . "<br>");
                print("Total no of record added: " . $counter);
            }
            break;

        case 'lov_side_filter_paramer':
            print("lov_side_filter_paramer");die();
            $counter = 0;
            $Query = "SELECT * FROM lov_side_filter ORDER BY lov_sf_id ASC";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $lov_sf_id = $row->lov_sf_id;
                    $lov_sf_params_de = url_clean($row->lov_sf_title);

                    mysqli_query($GLOBALS['conn'], "UPDATE lov_side_filter SET lov_sf_params_de = '" . dbStr(trim($lov_sf_params_de)) . "' WHERE lov_sf_id = '" . $lov_sf_id . "' ") or die(mysqli_error($GLOBALS['conn']));
                    $counter++;
                }
                print("Total no of record updated: " . $counter);
            }
            break;

        case 'products_feature_paramer':
            print("products_feature_paramer");die();
            $counter = 0;
            $Query = "SELECT * FROM products_feature ORDER BY pf_id ASC";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $pf_id = $row->pf_id;
                    $pf_fname_params_de = url_clean($row->pf_fname);
                    $pf_fvalue_params_de = url_clean($row->pf_fvalue);
                    
                    mysqli_query($GLOBALS['conn'], "UPDATE products_feature SET pf_fname_params_de = '" . dbStr(trim($pf_fname_params_de)) . "', pf_fvalue_params_de = '".dbStr(trim($pf_fvalue_params_de))."' WHERE pf_id = '" . $pf_id . "' ") or die(mysqli_error($GLOBALS['conn']));
                    $counter++;
                }
                print("Total no of record updated: " . $counter);
            }
            break;

        case 'category_map_subgroups':
            print("category_map_subgroups");die();
            $counter = 0;
            $rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM category_map");
            if (mysqli_num_rows($rs) > 0) {
                while($row = mysqli_fetch_object($rs)) {
                 $subgroups = explode(',', $row->sub_group_ids);
                    foreach ($subgroups as $sub_id) {
                        mysqli_Query($GLOBALS['conn'], "INSERT IGNORE INTO category_map_subgroups (supplier_id, subgroup_id) VALUES ('".$row->supplier_id."', '".$sub_id."')")  or die(mysqli_error($GLOBALS['conn']));
                         $counter++;
                    }
                }
            }
            print("Total no of record added: " . $counter);
            break;

        case 'category_map_level_update':
            print("category_map_level_update");die();
            $counter = 0;
            $rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM category_map ORDER BY supplier_id ASC");
            if (mysqli_num_rows($rs) > 0) {
                while($row = mysqli_fetch_object($rs)) {
                 $subgroups = explode(',', $row->sub_group_ids);
                    mysqli_Query($GLOBALS['conn'], "UPDATE category_map SET cat_id_level_two = '".$subgroups[0]."', cat_id_level_one = '".$subgroups[1]."' WHERE supplier_id = '".$row->supplier_id."' AND cat_id = '".$row->cat_id."' ")  or die(mysqli_error($GLOBALS['conn']));
                    $counter++;
                }
            }
            print("Total no of record added: " . $counter);
            break;

        case 'pro_udx_seo_epag_title_params_de':
            print("pro_udx_seo_epag_title_params_de");die();
            $counter = 0;
            $Query = "SELECT * FROM products WHERE pro_status = '1' AND pro_id != '20841' ORDER BY pro_id ASC";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $pro_id = $row->pro_id;
                    $pro_udx_seo_epag_title_params_de = url_clean(convertGermanChars(trim($row->pro_udx_seo_epag_title)));
                    $update_query = "UPDATE products SET pro_udx_seo_epag_title_params_de = '" . dbStr($pro_udx_seo_epag_title_params_de) . "' WHERE pro_id = '" . $pro_id . "' ";
                    //print($update_query."<br>");
                    mysqli_query($GLOBALS['conn'], $update_query) or die(mysqli_error($GLOBALS['conn']).$update_query);
                    $counter++;
                }
                print("Total no of record updated: " . $counter);
            }
            break;

        case 'pro_custom_add_pro_udx_seo_epag_title_params_de':
            print("pro_custom_add_pro_udx_seo_epag_title_params_de");die();
            $counter = 0;
            $Query = "SELECT * FROM products WHERE pro_custom_add = '1' ORDER BY pro_id ASC";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $pro_id = $row->pro_id;
                    $pro_udx_seo_epag_title_params_de = url_clean(convertGermanChars(trim($row->pro_description_short)));
                    $update_query = "UPDATE products SET pro_udx_seo_epag_title = '".dbStr(trim($row->pro_description_short))."', pro_udx_seo_epag_title_params_de = '" . dbStr($pro_udx_seo_epag_title_params_de) . "' WHERE pro_id = '" . $pro_id . "' ";
                    //print($update_query."<br>");
                    mysqli_query($GLOBALS['conn'], $update_query) or die(mysqli_error($GLOBALS['conn']).$update_query);
                    $counter++;
                }
                print("Total no of record updated: " . $counter);
            }
            break;

        case 'schulranzen_pro_udx_seo_epag_title_params_de':
            print("schulranzen_pro_udx_seo_epag_title_params_de");die();
            $counter = 0;
            $Query = "SELECT * FROM products WHERE pro_type = '20' ORDER BY pro_id ASC";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $pro_id = $row->pro_id;
                    $pro_udx_seo_epag_title_params_de = url_clean(convertGermanChars(trim($row->pro_description_short)));
                    
                    mysqli_query($GLOBALS['conn'], "UPDATE products SET pro_udx_seo_epag_title = '".dbStr(trim($row->pro_description_short))."', pro_udx_seo_epag_title_params_de = '" . dbStr($pro_udx_seo_epag_title_params_de) . "' WHERE pro_id = '" . $pro_id . "' ") or die(mysqli_error($GLOBALS['conn']));
                    $counter++;
                }
                print("Total no of record updated: " . $counter);
            }
            break;

        case 'category_paramer':
            print("category_paramer");die();
            $counter = 0;
            $Query = "SELECT * FROM category ORDER BY cat_id ASC";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $cat_id = $row->cat_id;
                    $cat_params_de = convertGermanChars($row->cat_title_de);
                    $cat_params_de = url_clean($cat_params_de);

                    mysqli_query($GLOBALS['conn'], "UPDATE category SET cat_params_de = '" . dbStr(trim($cat_params_de)) . "' WHERE cat_id = '" . $cat_id . "' ") or die(mysqli_error($GLOBALS['conn']));
                    $counter++;
                }
                print("Total no of record added: " . $counter);
            }
            break;

        case 'pro_url':
            print("pro_url");die();
            $counter = 0;
            $Query = "SELECT pro.*, pf.pf_fvalue_params_de FROM products AS pro LEFT OUTER JOIN products_feature AS pf ON pf.supplier_id = pro.supplier_id AND pf.pf_fname = pro.pro_udx_seo_selection_feature WHERE pro.pro_id != '20841' ORDER BY pro.pro_id ASC";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $pro_id = $row->pro_id;
                    if(!empty($row->pf_fvalue_params_de)){
                        $pro_url = $row->pro_udx_seo_epag_title_params_de.'-'.$row->pf_fvalue_params_de;
                    } else{
                        $pro_url = $row->pro_udx_seo_epag_title_params_de;
                    }
                    $update_query = "UPDATE products SET pro_url = '" . dbStr($pro_url) . "' WHERE pro_id = '" . $pro_id . "' ";
                    //print($update_query."<br>");
                    mysqli_query($GLOBALS['conn'], $update_query) or die(mysqli_error($GLOBALS['conn']).$update_query);
                    $counter++;
                }
                print("Total no of record updated: " . $counter);
            }
            break;
    }
}
