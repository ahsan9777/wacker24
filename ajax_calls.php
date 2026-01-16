<?php
include("includes/php_includes_top.php");
if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'switch_click':
            $retValue = array();
            //print_r($_REQUEST);die();
            $_SESSION['utype_id'] = $_REQUEST['utype_id'];

            if (isset($_REQUEST['ci_total']) && $_REQUEST['ci_total'] > 0) {
                $delivery_charges = get_delivery_charges($_REQUEST['ci_total']);
                $retValue = array("status" => "1", "message" => "Record get successfully", "delivery_charges" => $delivery_charges, "utype_id" => $_SESSION['utype_id']);
            } else {
                $retValue = array("status" => "0", "message" => "Record not found!", "utype_id" => $_SESSION['utype_id']);
            }
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'search_keyword':
            $json = array();
            $where = "WHERE 1 = 1";
            if (isset($_REQUEST['term']) && $_REQUEST['term'] != '') {
                if ($_REQUEST['level_one'] > 0) {
                    $where .= " AND pro.supplier_id IN (SELECT cm.supplier_id FROM category_map AS cm WHERE FIND_IN_SET(" . dbStr(trim($_REQUEST['level_one'])) . ", cm.sub_group_ids)) ";
                }
                $where .= " AND ( pro.pro_description_short LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' OR pro.supplier_id LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' OR pro.pro_manufacture_aid LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%'  OR pro.pro_ean LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' OR pro.supplier_id IN (SELECT pf.supplier_id FROM products_feature AS pf WHERE pf.pf_forder = '3' AND pf.pf_fvalue LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' ) )";
            }
            $Query = "SELECT pro.pro_id, pro.supplier_id, pro.pro_description_short, pro_udx_seo_internetbezeichung FROM products AS pro " . $where . " AND pro.pro_status = '1' ORDER BY pro.pro_id  LIMIT 0,10";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            while ($row = mysqli_fetch_object($rs)) {
                $json[] = array(
                    'pro_id' => strip_tags(html_entity_decode($row->pro_id, ENT_QUOTES, 'UTF-8')),
                    'supplier_id' => strip_tags(html_entity_decode($row->supplier_id, ENT_QUOTES, 'UTF-8')),
                    'value' => strip_tags(html_entity_decode($row->pro_udx_seo_internetbezeichung, ENT_QUOTES, 'UTF-8'))
                );
            }
            $jsonResults = json_encode($json);
            print($jsonResults);
            break;

        case 'add_to_card':
            $retValue = array();
            $count = 0;
            //print_r($_REQUEST);die();
            $pro_id = $_REQUEST['pro_id'];
            $pro_type = $_REQUEST['pro_type'];
            $supplier_id = $_REQUEST['supplier_id'];
            $ci_type = $_REQUEST['ci_type'];
            $ci_qty = $_REQUEST['ci_qty'];
            $ci_qty_type = $_REQUEST['ci_qty_type'];
            $get_pro_price = get_pro_price($pro_id, $supplier_id, $ci_qty);
            $pbp_id = $get_pro_price['pbp_id'];
            $pbp_price_amount = $get_pro_price['ci_amount'];
            $ci_amount = $get_pro_price['ci_amount'];
            $ci_gst_value = $get_pro_price['ci_gst_value'];
            $ci_discount_type = $_REQUEST['ci_discount_type'];
            $ci_discount_value = $_REQUEST['ci_discount_value'];
            $ci_discounted_amount = 0;
            $ci_discount = 0;
            $checkquantity = checkquantity($supplier_id, $ci_qty, 0, $ci_qty_type, $ci_type);
            //print($ci_amount);die();
            if ($ci_discount_value > 0) {
                $ci_discounted_amount_gross = 0;
                $ci_amount = discounted_price($ci_discount_type, $ci_amount, $ci_discount_value, $ci_gst_value, 1);
                $ci_discounted_amount = $pbp_price_amount - $ci_amount;

                $ci_discounted_amount_gross = $ci_discounted_amount * $checkquantity;
                $ci_discount = $ci_discounted_amount_gross + ($ci_discounted_amount_gross * $ci_gst_value);
                //print($ci_amount);die();
            }
            if ($pro_type > 0) {
                $checkquantity = 1;
            }
            $ci_gross_total = $ci_amount * $checkquantity;
            $ci_gst = $ci_gross_total * $ci_gst_value;
            $ci_total = $ci_gross_total + $ci_gst;
            $cart_datetime = date_time;

            $sess_idd = session_id();
            if (isset($_SESSION['sess_id'])) {
                if ($_SESSION['sess_id'] == $sess_idd) {
                    $ci_id = $_SESSION['ci_id'];
                    $ci_id = getMaximum("cart_items", "ci_id");
                    $cart_id = $_SESSION['cart_id'];
                    $Query = "SELECT * FROM cart_items WHERE cart_id = '" . $cart_id . "' AND pro_id = '" . $pro_id . "' AND supplier_id = '" . $supplier_id . "' ";
                    $rs = mysqli_query($GLOBALS['conn'], $Query);
                    if (mysqli_num_rows($rs) > 0) {
                        //print("found 1:sess_id");die();
                        $row = mysqli_fetch_object($rs);

                        $cart_quantity = returnName("ci_qty", "cart_items", "ci_id", $row->ci_id);
                        $checkquantity = checkquantity($supplier_id, $ci_qty, $cart_quantity, $ci_qty_type, $ci_type);
                        if ($pro_type > 0) {
                            $checkquantity = $ci_qty + $cart_quantity;
                            $get_pro_price = get_pro_price($pro_id, $supplier_id, 1);
                        } else {
                            //$get_pro_price = get_pro_price($pro_id, $supplier_id, $ci_qty + $cart_quantity);
                            $get_pro_price = get_pro_price($pro_id, $supplier_id, $checkquantity);
                        }
                        //print_r($get_pro_price);
                        $pbp_id = $get_pro_price['pbp_id'];
                        $pbp_price_amount = $get_pro_price['ci_amount'];
                        $ci_amount = $get_pro_price['ci_amount'];
                        $ci_gst_value = $get_pro_price['ci_gst_value'];
                        $ci_discount_type = $row->ci_discount_type;
                        $ci_discount_value = $row->ci_discount_value;
                        $ci_discounted_amount = 0;
                        $ci_discount = 0;
                        //print($ci_discount_value);die();
                        if ($ci_discount_value > 0) {
                            $ci_discounted_amount_gross = 0;

                            $ci_amount = discounted_price($ci_discount_type, $ci_amount, $ci_discount_value, $ci_gst_value, 1);
                            $ci_discounted_amount = $pbp_price_amount - $ci_amount;

                            if ($pro_type > 0) {
                                $ci_qty = 0;
                                $ci_discounted_amount_gross = $ci_discounted_amount * 1;
                            } else {
                                //$ci_discounted_amount_gross = $ci_discounted_amount * ($ci_qty + $cart_quantity);
                                $ci_discounted_amount_gross = $ci_discounted_amount * $checkquantity;
                            }
                            $ci_discount = $ci_discounted_amount_gross + ($ci_discounted_amount_gross * $ci_gst_value);
                            //print($ci_amount);die();
                        }
                        if ($pro_type > 0) {
                            $ci_qty = 0;
                        }
                        //$ci_gross_total = $ci_amount * ($ci_qty + $cart_quantity);
                        $ci_gross_total = $ci_amount * $checkquantity;
                        $ci_gst = $ci_gross_total * $ci_gst_value;
                        $ci_total = $ci_gross_total + $ci_gst;

                        //print("UPDATE cart_items SET pbp_id = '" . $pbp_id . "', pbp_price_amount = '" . $pbp_price_amount . "', ci_amount = '" . $ci_amount . "', ci_discounted_amount = '" . $ci_discounted_amount . "', ci_gst_value = '".$ci_gst_value."', ci_qty = ci_qty + '$ci_qty',  ci_gross_total = '$ci_gross_total' , ci_gst = '$ci_gst',  ci_discount =  '$ci_discount', ci_total =  '$ci_total' WHERE ci_id = '" . $row->ci_id . "'");die();
                        //$updated_cart_item = mysqli_query($GLOBALS['conn'], "UPDATE cart_items SET ci_type = '".$ci_type."', pbp_id = '" . $pbp_id . "', pbp_price_amount = '" . $pbp_price_amount . "', ci_amount = '" . $ci_amount . "', ci_discounted_amount = '" . $ci_discounted_amount . "', ci_gst_value = '" . $ci_gst_value . "', ci_qty = ci_qty + '$ci_qty',  ci_gross_total = '$ci_gross_total' , ci_gst = '$ci_gst',  ci_discount =  '$ci_discount', ci_total =  '$ci_total' WHERE ci_id = '" . $row->ci_id . "'") or die(mysqli_error($GLOBALS['conn']));
                        $updated_cart_item = mysqli_query($GLOBALS['conn'], "UPDATE cart_items SET ci_type = '" . $ci_type . "', pbp_id = '" . $pbp_id . "', pbp_price_amount = '" . $pbp_price_amount . "', ci_amount = '" . $ci_amount . "', ci_discounted_amount = '" . $ci_discounted_amount . "', ci_gst_value = '" . $ci_gst_value . "', ci_qty =  '$checkquantity',  ci_gross_total = '$ci_gross_total' , ci_gst = '$ci_gst',  ci_discount =  '$ci_discount', ci_total =  '$ci_total' WHERE ci_id = '" . $row->ci_id . "'") or die(mysqli_error($GLOBALS['conn']));
                        $update_cart = mysqli_query($GLOBALS['conn'], "UPDATE cart SET cart_gross_total=(SELECT SUM(ci_gross_total) FROM cart_items WHERE cart_id=$cart_id), cart_gst=(SELECT SUM(ci_gst) FROM cart_items WHERE cart_id=$cart_id), cart_discount=(SELECT SUM(ci_discount) FROM cart_items WHERE cart_id=$cart_id), cart_amount=(SELECT SUM(ci_total) FROM cart_items WHERE cart_id=$cart_id) WHERE cart_id=" . $cart_id) or die(mysqli_error($GLOBALS['conn']));
                        $_SESSION['header_quantity'] = $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], "SELECT * FROM `cart_items` WHERE `cart_id` = '" . $cart_id . "'"));
                        if ($updated_cart_item == true && $update_cart == true) {
                            //echo "success";
                            $retValue = array("status" => "1", "message" => "The recorded quantity has been updated to the bucket successfully", "count" => "$count");
                        } else {
                            $retValue = array("status" => "0", "message" => "Record added fail!");
                        }
                    } else {
                        //print("else 1: sess_id");die();
                        //print($ci_amount);die();
                        //print("INSERT INTO cart_items ( ci_id, cart_id, pro_id, supplier_id, pbp_id, pbp_price_amount, ci_amount, ci_discounted_amount, ci_qty, ci_gross_total, ci_gst, ci_discount_type, ci_discount_value, ci_discount, ci_total) VALUES ('" . $ci_id . "','" . dbStr($cart_id) . "', '" . dbStr($pro_id) . "','" . dbStr($supplier_id) . "', '" . dbStr(trim($pbp_id)) . "', '" . dbStr($pbp_price_amount) . "', '" . dbStr($ci_amount) . "', '" . dbStr($ci_discounted_amount) . "', '" . dbStr($ci_qty) . "', '" . dbStr(trim($ci_gross_total)) . "', '" . dbStr(trim($ci_gst)) . "', '" . dbStr(trim($ci_discount_type)) . "', '" . dbStr(trim($ci_discount_value)) . "', '" . dbStr($ci_discount) . "', '" . dbStr($ci_total) . "')");die();
                        $ci_discounted_price_see = 0;
                        if (isset($_SESSION["UID"]) && $_SESSION["UID"] > 0) {
                            $ci_discounted_price_see = 1;
                        }
                        $insert_cart_item = mysqli_query($GLOBALS['conn'], "INSERT INTO cart_items ( ci_id, cart_id, pro_id, supplier_id, ci_type, pbp_id, pbp_price_amount, ci_amount, ci_discounted_amount, ci_qty, ci_qty_type, ci_gross_total, ci_gst_value, ci_gst, ci_discount_type, ci_discount_value, ci_discount, ci_total, ci_discounted_price_see) VALUES ('" . $ci_id . "','" . dbStr($cart_id) . "', '" . dbStr($pro_id) . "','" . dbStr($supplier_id) . "', '" . dbStr($ci_type) . "', '" . dbStr(trim($pbp_id)) . "', '" . dbStr($pbp_price_amount) . "', '" . dbStr($ci_amount) . "', '" . dbStr($ci_discounted_amount) . "', '" . dbStr($checkquantity) . "', '" . dbStr($ci_qty_type) . "', '" . dbStr(trim($ci_gross_total)) . "', '" . dbStr(trim($ci_gst_value)) . "', '" . dbStr(trim($ci_gst)) . "', '" . dbStr(trim($ci_discount_type)) . "', '" . dbStr(trim($ci_discount_value)) . "', '" . dbStr($ci_discount) . "', '" . dbStr($ci_total) . "', '" . $ci_discounted_price_see . "')") or die(mysqli_error($GLOBALS['conn']));
                        $update_cart = mysqli_query($GLOBALS['conn'], "UPDATE cart SET cart_gross_total=(SELECT SUM(ci_gross_total) FROM cart_items WHERE cart_id=$cart_id), cart_gst=(SELECT SUM(ci_gst) FROM cart_items WHERE cart_id=$cart_id), cart_discount=(SELECT SUM(ci_discount) FROM cart_items WHERE cart_id=$cart_id), cart_amount=(SELECT SUM(ci_total) FROM cart_items WHERE cart_id=$cart_id) WHERE cart_id=" . $cart_id) or die(mysqli_error($GLOBALS['conn']));
                        $_SESSION['header_quantity'] = $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], "SELECT * FROM `cart_items` WHERE `cart_id` = '" . $cart_id . "'"));
                        if ($insert_cart_item == true && $update_cart == true) {
                            //echo "success";
                            $retValue = array("status" => "1", "message" => "The record has been added to the bucket successfully", "count" => "$count");
                        } else {
                            $retValue = array("status" => "0", "message" => "Record added fail!");
                        }
                    }
                }
            } else {
                //print("else 2: sess_id");die();
                $cart_id = getMaximum("cart", "cart_id");
                $_SESSION['cart_id'] = $cart_id;
                $sess_id = session_id();
                $_SESSION['sess_id'] = $sess_id;

                $insert_cart = mysqli_query($GLOBALS['conn'], "INSERT INTO cart ( cart_id, sess_id, cart_datetime) VALUES ('" . $cart_id . "','" . dbStr($sess_id) . "', '" . dbStr($cart_datetime) . "')") or die(mysqli_error($GLOBALS['conn']));

                $ci_id = getMaximum("cart_items", "ci_id");
                $_SESSION['ci_id'] = $ci_id;

                $insert_cart_item = mysqli_query($GLOBALS['conn'], "INSERT INTO cart_items ( ci_id, cart_id, pro_id, supplier_id, ci_type, pbp_id, pbp_price_amount, ci_amount, ci_discounted_amount, ci_qty, ci_qty_type, ci_gross_total, ci_gst_value, ci_gst, ci_discount_type, ci_discount_value, ci_discount, ci_total) VALUES ('" . $ci_id . "','" . dbStr($cart_id) . "', '" . dbStr($pro_id) . "','" . dbStr($supplier_id) . "', '" . dbStr($ci_type) . "', '" . dbStr(trim($pbp_id)) . "', '" . dbStr($pbp_price_amount) . "', '" . dbStr($ci_amount) . "', '" . dbStr($ci_discounted_amount) . "', '" . dbStr($ci_qty) . "', '" . dbStr($ci_qty_type) . "', '" . dbStr(trim($ci_gross_total)) . "', '" . dbStr(trim($ci_gst_value)) . "', '" . dbStr(trim($ci_gst)) . "', '" . dbStr(trim($ci_discount_type)) . "', '" . dbStr(trim($ci_discount_value)) . "', '" . dbStr($ci_discount) . "', '" . dbStr($ci_total) . "')") or die(mysqli_error($GLOBALS['conn']));
                $update_cart = mysqli_query($GLOBALS['conn'], "UPDATE cart SET cart_gross_total=(SELECT SUM(ci_gross_total) FROM cart_items WHERE cart_id=$cart_id), cart_gst=(SELECT SUM(ci_gst) FROM cart_items WHERE cart_id=$cart_id), cart_discount=(SELECT SUM(ci_discount) FROM cart_items WHERE cart_id=$cart_id), cart_amount=(SELECT SUM(ci_total) FROM cart_items WHERE cart_id=$cart_id) WHERE cart_id=" . $cart_id) or die(mysqli_error($GLOBALS['conn']));
                $_SESSION['header_quantity'] = $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], "SELECT * FROM `cart_items` WHERE `cart_id` = '" . $cart_id . "'"));
                if ($insert_cart == true && $insert_cart_item == true && $update_cart == true) {
                    $retValue = array("status" => "1", "message" => "The record has been added to the bucket successfully", "count" => "$count");
                } else {
                    $retValue = array("status" => "0", "message" => "Record added fail!");
                }
            }
            //return $retValue;
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'show_side_cart_data':
            if (isset($_SESSION['cart_id'])) {
                $show_card_body = "";
                $count = 0;
                $cart_amount = 0;
                //$Query = "SELECT ci.*, pg.pg_mime_source_url FROM cart_items AS ci LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = ci.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' WHERE ci.cart_id = '" . $_SESSION['cart_id'] . "' ORDER BY ci.ci_id ASC";
                $Query = "WITH ranked_gallery AS (SELECT pg.*, ROW_NUMBER() OVER (PARTITION BY supplier_id ORDER BY pg_mime_source_url ASC) AS rn FROM products_gallery AS pg WHERE pg.pg_mime_purpose = 'normal') SELECT ci.*, pro.pro_description_short, pro.pro_udx_seo_internetbezeichung, rg.pg_mime_source_url FROM cart_items AS ci LEFT OUTER JOIN products AS pro ON pro.supplier_id = ci.supplier_id LEFT JOIN ranked_gallery AS rg ON rg.supplier_id = ci.supplier_id AND rg.rn = 1 WHERE ci.cart_id = '" . $_SESSION['cart_id'] . "' ORDER BY ci.ci_id ASC";
                //print($Query);die();
                $rs = mysqli_query($GLOBALS['conn'], $Query);
                if (mysqli_num_rows($rs) > 0) {
                    $_SESSION['header_quantity'] = $count = TotalRecords("ci_id", "cart_items", "WHERE cart_id=" . $_SESSION['cart_id']);
                    while ($row = mysqli_fetch_object($rs)) {

                        $pq_quantity = 0;
                        if (!empty($row->supplier_id)) {
                            $Query1 = "SELECT * FROM products_quantity WHERE supplier_id = '" . dbStr(trim($row->supplier_id)) . "'";
                            $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
                            if (mysqli_num_rows($rs1) > 0) {
                                $row1 = mysqli_fetch_object($rs1);
                                $pq_quantity = $row1->pq_quantity;
                                $pq_upcomming_quantity = $row1->pq_upcomming_quantity;
                                $pq_status = $row1->pq_status;
                                if ($pq_quantity == 0 && ($pq_status == 'true' || $pq_status == 'false')) {
                                    $pq_quantity = $pq_upcomming_quantity - $row->ci_qty;
                                } elseif ($pq_quantity > 0 && $pq_status == 'false') {
                                    $pq_quantity = $pq_quantity + $pq_upcomming_quantity - $row->ci_qty;
                                }
                            }
                        }
                        $ci_gst_value = $row->ci_gst_value;
                        $cart_amount = $cart_amount + $row->ci_total;
                        $gst = $row->ci_amount * $ci_gst_value;
                        $gst_orignal = $row->pbp_price_amount * $ci_gst_value;
                        $display_one = "";
                        $display_two = "";
                        if (isset($_SESSION['utype_id']) && $_SESSION['utype_id'] == 4) {
                            $display_one = "style = 'display: block;'";
                            $display_two = "style = 'display: none;'";
                        }
                        $cart_price_data = "";
                        if ($row->ci_discount_value > 0) {
                            $cart_price_data .= '<div class="side_cart_pd_prise price_without_tex" ' . $display_one . ' > <del class="orignal_price"> ' . price_format($row->pbp_price_amount) . ' €</del> <br> <span class="pd_prise_discount"> ' . price_format($row->ci_amount) . "€ " . $row->ci_discount_value . (($row->ci_discount_type > 0) ? "€" : "%") . ' </span></div>';
                            $cart_price_data .= '<div class="side_cart_pd_prise pbp_price_with_tex" ' . $display_two . ' > <del class="orignal_price"> ' . price_format($row->pbp_price_amount + $gst_orignal) . ' €</del> <br> <span class="pd_prise_discount"> ' . price_format($row->ci_amount + $gst) . "€ " . $row->ci_discount_value . (($row->ci_discount_type > 0) ? '€' : '%') . ' </span> </div>';
                        } else {
                            $cart_price_data .= '<div class="side_cart_pd_prise price_without_tex" ' . $display_one . ' >' . price_format($row->ci_amount) . ' €</div>';
                            $cart_price_data .= '<div class="side_cart_pd_prise pbp_price_with_tex" ' . $display_two . ' >' . price_format($row->ci_amount + $gst) . ' €</div>';
                        }
                        $product_link = product_detail_url($row->supplier_id);
                        $get_image_link = get_image_link(160, $row->pg_mime_source_url); 
                        if ($row->ci_type == 1) {
                            $product_link = product_detail_url($row->supplier_id, 1);
                        } elseif ($row->ci_type == 2) {
                            $product_link = "javascript:void(0);";
                            $get_image_link = $GLOBALS['siteURL'] . "files/free_product/" . returnName("fp_file", "free_product", "fp_id", $row->fp_id);
                        }
                        $show_card_body .= '
                                <div class="side_cart_pd_row">
                                    <div class="side_cart_pd_image"><a href="' . $product_link . '" title = "' . $row->pro_udx_seo_internetbezeichung . '" ><img src="' .$get_image_link. '" alt="' . $row->pro_udx_seo_internetbezeichung . '"></a></div>
                                    ' . $cart_price_data . '
                                    <div class="side_cart_pd_qty">
                                        <div class="side_pd_qty">
                                            <input type="number" class="qlt_number" id = "ci_qty_' . $row->ci_id . '" data-id = "' . $row->ci_id . '" value="' . $row->ci_qty . '" onkeyup="if(this.value === \'\' || parseFloat(this.value) <= 0) { this.value = 0; } else if (parseFloat(this.value) > ' . ($pq_quantity + $row->ci_qty) . ') { this.value = ' . ($pq_quantity + $row->ci_qty) . '; return false; }" min="1" max="' . $pq_quantity . '">
                                        </div>

                                        <div class="side_pd_delete"><a  class = "item_deleted" data-id = "' . $row->ci_id . '" href="javascript:void(0)" title = "delete"><i class="fa fa-trash"></i></a></div>
                                    </div>
                                </div>
                                <script>
                                $(".item_deleted").on("click", function(){
                                    //console.log("item_deleted");
                                    let ci_id = $(this).attr("data-id");
                                    //console.log("ci_id:"+ci_id);
                                    $.ajax({
                                        url: "ajax_calls.php?action=item_deleted",
                                        method: "POST",
                                        data:{
                                            ci_id: ci_id
                                        },
                                        success: function(response) {
                                            //console.log("response = "+response);
                                            const obj = JSON.parse(response);
                                            //console.log(obj);
                                            if(obj.status == 1){
                                                $("#header_quantity").text(obj.count+" items");
                                                $(".side_cart_click").trigger("click");
                                            }
                                        }
                                    });
                                });
                                $(".qlt_number").on("change", function(){
                                    //console.log("ci_qty");
                                    let ci_id = $(this).attr("data-id");
                                    let ci_qty = $("#ci_qty_"+$(this).attr("data-id")).val();
                                    //console.log("ci_qty: "+ci_qty);
                                    $.ajax({
                                        url: "ajax_calls.php?action=item_ci_qty",
                                        method: "POST",
                                        data:{
                                            ci_id: ci_id,
                                            ci_qty: ci_qty
                                        },
                                        success: function(response) {
                                            //console.log("response = "+response);
                                            const obj = JSON.parse(response);
                                            //console.log(obj);
                                            if(obj.status == 1){
                                                $(".side_cart_click").trigger("click");
                                            } else{
                                             $(".side_cart_click").trigger("click");
                                            }
                                        }
                                    });
                                });
                                </script>
                        ';
                    }

                    $retValue = array("status" => "1", "message" => "Record found successfully!", "count" => $count, "cart_amount" => str_replace(".", ",", $cart_amount), "show_card_body" => $show_card_body);
                } else {
                    $retValue = array("status" => "0", "message" => "Record not found!", "count" => $count, "cart_amount" => str_replace(".", ",", $cart_amount), "show_card_body" => $show_card_body);
                }
            } else {
                $retValue = array("status" => "0", "message" => "Record not found!", "count" => 0, "cart_amount" => 0, "show_card_body" => "");
            }
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'item_ci_qty':
            //print_r($_REQUEST['ci_qty']);die();
            $retValue = array();
            if (isset($_REQUEST['ci_id']) && $_REQUEST['ci_id'] > 0) {
                if (isset($_REQUEST['ci_qty']) && $_REQUEST['ci_qty'] > 0) {
                    $cart_id = $_SESSION['cart_id'];
                    $Query = "SELECT * FROM cart_items WHERE ci_id = '" . $_REQUEST['ci_id'] . "' ";
                    $rs = mysqli_query($GLOBALS['conn'], $Query);
                    if (mysqli_num_rows($rs) > 0) {
                        $row = mysqli_fetch_object($rs);

                        //$cart_quantity = returnName("ci_qty","cart_items", "ci_id", $row->ci_id);
                        $get_pro_price = get_pro_price($row->pro_id, $row->supplier_id, $_REQUEST['ci_qty']);
                        //print_r($get_pro_price);
                        $pbp_id = $get_pro_price['pbp_id'];
                        $pbp_price_amount = $get_pro_price['ci_amount'];
                        $ci_amount = $get_pro_price['ci_amount'];
                        $ci_gst_value = $get_pro_price['ci_gst_value'];
                        $ci_discount_type = $row->ci_discount_type;
                        $ci_discount_value = $row->ci_discount_value;
                        $ci_discounted_amount = 0;
                        $ci_discount = 0;
                        if ($ci_discount_value > 0) {
                            $ci_discounted_amount_gross = 0;
                            $ci_amount = discounted_price($ci_discount_type, $ci_amount, $ci_discount_value);
                            $ci_discounted_amount = $pbp_price_amount - $ci_amount;

                            $ci_discounted_amount_gross = $ci_discounted_amount * ($_REQUEST['ci_qty']);
                            $ci_discount = $ci_discounted_amount_gross + ($ci_discounted_amount_gross * $ci_gst_value);
                        }
                        $ci_gross_total = $ci_amount * ($_REQUEST['ci_qty']);
                        $ci_gst = $ci_gross_total * $ci_gst_value;
                        $ci_total = $ci_gross_total + $ci_gst;

                        $updated_cart_item = mysqli_query($GLOBALS['conn'], "UPDATE cart_items SET pbp_id = '" . $pbp_id . "', pbp_price_amount = '" . $pbp_price_amount . "', ci_amount = '" . $ci_amount . "', ci_discounted_amount = '" . $ci_discounted_amount . "', ci_qty = '" . $_REQUEST['ci_qty'] . "',  ci_gross_total =  '$ci_gross_total' , ci_gst_value = '" . $ci_gst_value . "', ci_gst =  '$ci_gst', ci_discount =  '$ci_discount', ci_total =  '$ci_total' WHERE ci_id = '" . $row->ci_id . "'") or die(mysqli_error($GLOBALS['conn']));
                        $update_cart = mysqli_query($GLOBALS['conn'], "UPDATE cart SET cart_gross_total=(SELECT SUM(ci_gross_total) FROM cart_items WHERE cart_id=$cart_id), cart_gst=(SELECT SUM(ci_gst) FROM cart_items WHERE cart_id=$cart_id), cart_discount=(SELECT SUM(ci_discount) FROM cart_items WHERE cart_id=$cart_id), cart_amount=(SELECT SUM(ci_total) FROM cart_items WHERE cart_id=$cart_id) WHERE cart_id=" . $cart_id) or die(mysqli_error($GLOBALS['conn']));
                        $_SESSION['header_quantity'] = $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], "SELECT * FROM `cart_items` WHERE `cart_id` = '" . $cart_id . "'"));
                        if ($updated_cart_item == true && $update_cart == true) {
                            //echo "success";
                            $retValue = array("status" => "1", "message" => "Record of cart quantity updated successfully!");
                        } else {
                            $retValue = array("status" => "0", "message" => "Record of cart quantity not updated!");
                        }
                    }
                }
            } else {
                $retValue = array("status" => "0", "message" => "parameter not selected");
            }
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;
        case 'item_deleted':
            //echo "DELETE FROM cart_items WHERE ci_id='".$_REQUEST['ci_id']."'";
            //print_r($_REQUEST);die();
            $count = 0;
            $retValue = array();
            if (isset($_REQUEST['ci_id'])) {

                mysqli_query($GLOBALS['conn'], "DELETE FROM cart_items WHERE ci_id='" . $_REQUEST['ci_id'] . "' ") or die(mysqli_error($GLOBALS['conn']));

                $_SESSION['header_quantity'] = $count = TotalRecords("ci_id", "cart_items", "WHERE cart_id=" . $_SESSION['cart_id']);
                if ($count == 0) {
                    $_SESSION['header_quantity'] = 0;
                    mysqli_query($GLOBALS['conn'], "UPDATE cart SET  cart_gross_total= '0', cart_gst= '0', cart_discount= '0', cart_amount= '0' WHERE cart_id='" . $_SESSION['cart_id'] . "'") or die(mysqli_error($GLOBALS['conn']));
                } else {
                    mysqli_query($GLOBALS['conn'], "UPDATE cart SET cart_gross_total=(SELECT SUM(ci_gross_total) FROM cart_items WHERE cart_id= '" . $_SESSION['cart_id'] . "'), cart_gst=(SELECT SUM(ci_gst) FROM cart_items WHERE cart_id= '" . $_SESSION['cart_id'] . "'), cart_discount=(SELECT SUM(ci_discount) FROM cart_items WHERE cart_id= '" . $_SESSION['cart_id'] . "'), cart_amount=(SELECT SUM(ci_total) FROM cart_items WHERE cart_id= '" . $_SESSION['cart_id'] . "') WHERE cart_id=" . $_SESSION['cart_id']) or die(mysqli_error($GLOBALS['conn']));
                }

                $retValue = array("status" => "1", "message" => "Record deleted successfully!", "count" => $count);
            } else {
                $_SESSION['header_quantity'] = $count = TotalRecords("ci_id", "cart_items", "WHERE cart_id=" . $_SESSION['cart_id']);
                $retValue = array("status" => "0", "message" => "Please select the required parameter", "count" => $count);
            }

            $jsonResults = json_encode($retValue);
            print($jsonResults);

            break;

        case 'mydata_popup_trigger':

            $retValue = array();
            $Query = "SELECT user_id, user_fname, user_lname, user_phone FROM users WHERE user_id = '" . $_SESSION["UID"] . "'";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                $retValue = array("status" => "1", "message" => "Get my data");
                $row = mysqli_fetch_object($rs);
                $retValue['data'][] = array(
                    "user_id" => strval($row->user_id),
                    "user_fname" => strval($row->user_fname),
                    "user_lname" => strval($row->user_lname),
                    "user_phone" => strval($row->user_phone)
                );
            } else {
                $retValue = array("status" => "0", "message" => "Record not found!");
            }

            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;
        case 'appointment_schedule':
            //print_r($_REQUEST);
            $retValue = array();
            $selected_date = strtotime($_REQUEST['selected_date']);
            $day = date('D', $selected_date);
            //print_r($day);die();
            $start_time = 0;
            $end_time = 0;
            if ($day == 'Sat') {
                $start_time = config_appointment_saturday_opening;
                $end_time = config_appointment_saturday_closing;
            } else {
                $start_time = config_appointment_regular_opening;
                $end_time = config_appointment_regular_closing;
            }
            $start_time = strtotime($start_time);
            $end_time = strtotime($end_time);

            $appointment_schedule = "";
            $count = 0;
            while ($start_time < $end_time) {
                $count++;
                $next_time = strtotime("+" . $_REQUEST['as_duration'] . " minutes", $start_time);
                if (time() > $start_time && $_REQUEST['selected_date'] == date('Y-m-d')) {
                    $start_time = $next_time; // Skip this time slot
                    continue;
                }
                $appointment_schedule_time = date("H:i", $start_time) . " - " . date("H:i", $next_time);
                $checkrecord = checkrecord("app_time", "appointments", "as_id = '" . $_REQUEST['as_id'] . "' AND app_time = '" . $appointment_schedule_time . "' AND app_date = '" . $_REQUEST['selected_date'] . "' ");
                if ($checkrecord > 0) {
                    $appointment_schedule .= '
                                    <li class = "active_time">
										<label >' . $appointment_schedule_time . '</label>
									</li>
                 ';
                } else {
                    $appointment_schedule .= '
                                    <li>
										<input type="radio" class = "time_slote" id="appointment_schedule_time_' . $count . '" data-id = "' . $count . '" name="time_slote" value = "' . $appointment_schedule_time . '">
										<label for="appointment_schedule_time_' . $count . '">' . $appointment_schedule_time . '</label>
									</li>
                 ';
                }

                if ($_REQUEST['as_delay'] > 0) {
                    $start_time = strtotime("+" . $_REQUEST['as_delay'] . " minutes", $next_time);
                } else {
                    $start_time = $next_time;
                }
            }
            if (!empty($appointment_schedule)) {
                $appointment_schedule .= '
                <script>
                    $(".time_slote").on("click", function(){
                    let appointment_schedule_time = $("#appointment_schedule_time_"+$(this).attr("data-id")).val();
                        //console.log("time_slote: "+appointment_schedule_time);
                        $("#app_time").val(appointment_schedule_time);
                        $("#appointment_form").show();
                    });
                </script>
                ';
                $retValue = array("status" => "1", "message" => "Get appointment schedule", "appointment_schedule" => $appointment_schedule);
            } else {
                $retValue = array("status" => "0", "message" => "Record not found!");
            }

            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'usa_zipcode':
            $json = array();
            $where = "";
            if (isset($_REQUEST['term']) && $_REQUEST['term'] != '') {
                $where .= " WHERE zc_zipcode LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' OR zc_town LIKE '%" . dbStr($_REQUEST['term']) . "%'";
            }
            $Query = "SELECT * FROM `zip_code` " . $where . " ORDER BY zc_id LIMIT 0,13";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            while ($row = mysqli_fetch_object($rs)) {
                $json[] = array(
                    'value' => strip_tags(html_entity_decode($row->zc_zipcode . " " . $row->zc_town, ENT_QUOTES, 'UTF-8'))
                );
            }
            $jsonResults = json_encode($json);
            print($jsonResults);
            break;

        case 'addwhishlist':
            //print_r($_REQUEST);die();
            $retValue = array();

            $Query = "SELECT * FROM `wishlist` WHERE user_id = '" . $_SESSION['UID'] . "' AND sl_id = '" . $_REQUEST['sl_id'] . "' AND supplier_id = '" . $_REQUEST['supplier_id'] . "'";
            //print($Query);die();
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                //$retValue = array("status" => "1", "message" => "Record already exists!");
                $retValue = array("status" => "1", "class" => "alert alert-danger", "message" => "Eintrag bereits vorhanden");
            } else {
                $wl_id = getMaximum("wishlist", "wl_id");
                mysqli_query($GLOBALS['conn'], "INSERT INTO wishlist (wl_id, user_id, sl_id, supplier_id) VALUES ('" . $wl_id . "', '" . $_SESSION['UID'] . "', '" . $_REQUEST['sl_id'] . "', '" . $_REQUEST['supplier_id'] . "')") or die(mysqli_error($GLOBALS['conn']));
                //$retValue = array("status" => "1", "message" => "Add into list");
                $retValue = array("status" => "1", "class" => "alert alert-success", "message" => "Zur Liste hinzufügen");
            }

            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'category_show':
            $search_manf_id_check = array();
            $Sidefilter_where = $_REQUEST['Sidefilter_where'];
            $search_group_id_check = (!empty($_REQUEST['search_group_id_check'])) ? $_REQUEST['search_group_id_check'] : [];
            $category_show = "";
            $TotalRecCount = "";
            $Query = "SELECT cm.*, COUNT(*) OVER() AS TotalRecCount, COUNT(cat.group_id) AS total_count, cat.group_id, cat.cat_title_de AS cat_title FROM category_map AS cm LEFT OUTER JOIN category AS cat ON FIND_IN_SET(cat.group_id, cm.sub_group_ids) > 1 WHERE cm.supplier_id " . $Sidefilter_where . " GROUP BY cat.group_id ORDER BY cat.group_id ASC ";
            //print($Query);
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            $row = mysqli_fetch_object($rs);
            $TotalRecCount = !empty($row->TotalRecCount) ? $row->TotalRecCount : "";
            //$search_group_id = explode(",", $row->sub_group_ids);
            $category_show .= '<ul class="category_show ' . (($TotalRecCount > 5) ? 'category_show_height' : '') . '" id="list_checkbox_hide_1">';
            if (mysqli_num_rows($rs) > 0) {
                do {
                    $category_show .= '<li>
                            <label class="gerenric_checkbox">
                                ' . $row->cat_title . " (" . $row->total_count . ")" . '
                                <input type="checkbox" name="search_group_id[]" class="search_group_id" id="search_group_id" value="' . $row->group_id . '" ' . ((in_array($row->group_id, $search_group_id_check)) ? 'checked' : '') . '>
                                <span class="checkmark"></span>
                            </label>
                        </li>';
                } while ($row = mysqli_fetch_object($rs));
            }
            $category_show .= '</ul>';
            if ($TotalRecCount > 5) {
                $category_show .= '<div class="show-more" data-id="1">(Mehr anzeigen)</div>';
            }
            $category_show .= '
            <script>
                $(".search_group_id").on("click", function() {
                    $(".search_pf_fvalue").attr("checked", false)
                    $("#frm_left_search").submit();
                });
            </script>
            ';

            $retValue = array("status" => "1", "message" => "Record found", "category_show" => $category_show);
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'brand_show':
            $search_manf_id_check = array();
            $Sidefilter_brandwith = $_REQUEST['Sidefilter_brandwith'];
            $search_manf_id_check = (!empty($_REQUEST['search_manf_id_check'])) ? $_REQUEST['search_manf_id_check'] : [];
            $brand_show = '<h3>Marke</h3>';
            $TotalRecCount = 0;
            $Query = "SELECT manf.*, COUNT(*) OVER() AS TotalRecCount, (SELECT COUNT(pro.manf_id) FROM products AS pro WHERE pro.manf_id = manf.manf_id AND pro.supplier_id " . $Sidefilter_brandwith . ") AS total_count FROM manufacture AS manf WHERE manf.manf_id IN (SELECT pro.manf_id FROM products AS pro WHERE pro.supplier_id " . $Sidefilter_brandwith . ") AND manf.manf_status = '1' ORDER BY manf.manf_id ASC";
            //print($Query);
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            $row = mysqli_fetch_object($rs);
            $TotalRecCount = !empty($row->TotalRecCount) ? $row->TotalRecCount : "";
            $brand_show .= '<ul class="category_show ' . (($TotalRecCount > 5) ? 'category_show_height' : '') . '" id="list_checkbox_hide_2">';
            if (mysqli_num_rows($rs) > 0) {
                do {
                    $brand_show .= '<li>
                            <label class="gerenric_checkbox">
                                ' . $row->manf_name . " (" . $row->total_count . ")" . '
                                <input type="checkbox" name="search_manf_id[]" class="search_manf_id" id="search_manf_id" value="' . $row->manf_id . '" ' . ((in_array($row->manf_id, $search_manf_id_check)) ? 'checked' : '') . '>
                                <span class="checkmark"></span>
                            </label>
                        </li>';
                } while ($row = mysqli_fetch_object($rs));
            }
            $brand_show .= '</ul>';
            if ($TotalRecCount > 5) {
                $brand_show .= '<div class="show-more" data-id="2">(Mehr anzeigen)</div>';
            }
            $brand_show .= '
            <script>
                $(".search_manf_id").on("click", function() {
                    $(".search_pf_fvalue").attr("checked", false)
                    $("#frm_left_search").submit();
                });
            </script>
            ';

            $retValue = array("status" => "1", "message" => "Record found", "brand_show" => $brand_show);
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'feature_show':
            $search_pf_fvalue_check = array();
            $search_pf_fname_check = array();
            $Sidefilter_featurewhere = $_REQUEST['Sidefilter_featurewhere'];
            $search_pf_fvalue_check = (!empty($_REQUEST['search_pf_fvalue_check'])) ? $_REQUEST['search_pf_fvalue_check'] : [];
            $search_pf_fname_check = (!empty($_REQUEST['search_pf_fname_check'])) ? $_REQUEST['search_pf_fname_check'] : [];
            //print_r($_REQUEST);die();
            $retValue = array();
            $feature_show = "";
            $count = 3;
            //$Query1 = "SELECT * FROM products_feature AS pf WHERE pf.pf_fvalue_details = 'FILTER' AND pf.pf_fname NOT IN ('Made in Germany','Material der Sitzfläche', 'Packungsmenge', 'Material der Schreibfläche', 'Farbe des Rückens', 'Oberflächenbeschaffenheit', 'Fadenverstärkung vorhanden', 'Ausführung der Oberflächenbeschaffenheit', 'Farbe der Vorderseite', 'Material der Rückseite', 'Gehäusefarbe', 'Material des Rahmens', 'Trägermaterial', 'Deckel vorhanden', 'Material', 'Ausführung der Oberseite', 'Material des Papierhandtuches', 'Material des Tisches', 'Ablageschale vorhanden', 'max. Anzahl der Erweiterungshüllen', 'Motiv', 'Werkstoff', 'Zertifikat/Zulassung', 'Verwendung für Druck- oder Schreibgerät', '3 Klappen (Jurisklappen) am Unterdeckel vorhanden', 'feucht abwischbar', 'Anordnung der Lage (Öffnungsseite)', 'Ausführung der Tür', 'Material des Hygienebeutels', 'stapelbar', 'selbstklebend', 'Verschluss', 'Ausführung der Höhenverstellung', 'Boden vorhanden', 'max. Auflösung', 'Tafel beschreibbar', 'beidseitig beschreibbar', 'Weißgrad (ISO)', 'Verschlusstechnik', 'Weißgrad (CIE)', 'Lichtleistung', 'Breite des Sitzes', 'Kalenderaufteilung', 'Fenster vorhanden', 'Haftungsintensität', 'Volumen', 'Körnung', 'Heftleistung', 'Art des Auftragungshilfsmittels', 'Ausführung der vorderseitigen Lineatur', 'Rückenbreite', 'Typbezeichnung des Duftes', 'Fassungsvermögen', 'Taben', 'Grammatur', 'Dicke der Folie', 'Heftungsart', 'Auffangvolumen', 'Ausführung der Landkarte', 'Sterilität', 'Lochung', 'Arbeitsbreite', 'Kerndurchmesser', 'Anzahl der Teile', 'max. Aufbewahrungsmenge', 'Format der Folie', 'Maße der Oberfläche', 'Art des Laminierverfahrens', 'Innenmaße', 'Heftklammertyp', 'Einsatzbereich', 'max. Tragfähigkeit', 'Abmessung des Rahmens', 'Typbezeichnung') AND pf.supplier_id " . $Sidefilter_featurewhere . " GROUP BY pf.pf_fname ORDER BY pf.pf_forder ASC";
            $Query1 = "SELECT * FROM products_feature AS pf WHERE pf.pf_fvalue_details = 'FILTER' AND pf.pf_fname IN ('Papierformat', 'Verwendung für Produkt', 'max. Gewicht des Nutzers', 'Farbe der Rückenlehne', 'Farbe der Sitzfläche') AND pf.supplier_id " . $Sidefilter_featurewhere . " GROUP BY pf.pf_fname ORDER BY pf.pf_forder ASC";
            //print($Query1);die();
            $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
            if (mysqli_num_rows($rs) > 0) {
                while ($row1 = mysqli_fetch_object($rs1)) {
                    $count++;
                    $feature_show .= '
                    <div class="categroy_block">
                        <h3>' . $row1->pf_fname . '</h3>';
                    $TotalRecCount = 0;
                    $Query2 = "SELECT pf.*, COUNT(*) OVER() AS TotalRecCount, COUNT(pf.pf_fvalue) AS total_count FROM products_feature AS pf WHERE pf.pf_fname = '" . $row1->pf_fname . "' AND pf.supplier_id " . $Sidefilter_featurewhere . " GROUP BY pf.pf_fvalue ORDER BY pf.pf_forder ASC";
                    //print($Query2);die();
                    $rs2 = mysqli_query($GLOBALS['conn'], $Query2);
                    $row2 = mysqli_fetch_object($rs2);
                    $TotalRecCount = $row2->TotalRecCount;
                    $feature_show .= '<ul class="category_show ' . (($TotalRecCount > 5) ? 'category_show_height' : '') . '" id="category_show_' . $count . '">';
                    if (mysqli_num_rows($rs2) > 0) {
                        do {
                            $index = uniqid();
                            $feature_show .= '<li>
                                        <label class="gerenric_checkbox">
                                            ' . $row2->pf_fvalue . " (" . $row2->total_count . ")" . '
                                            <input type="hidden" name="search_pf_fname[' . $index . ']" value="' . $row2->pf_fname . '">
                                            <input type="checkbox" name="search_pf_fvalue[' . $index . ']" id="search_pf_fvalue" class="search_pf_fvalue" value="' . $row2->pf_fvalue . '" ' . ((in_array($row2->pf_fvalue, $search_pf_fvalue_check) && in_array($row1->pf_fname, $search_pf_fname_check)) ? 'checked' : '') . '>
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>';
                            //print($feature_show);die();
                        } while ($row2 = mysqli_fetch_object($rs2));
                    }
                    $feature_show .= '</ul>';
                    if ($TotalRecCount > 5) {
                        $feature_show .= '<div class="show-more" data-id="' . $count . '">(Mehr anzeigen)</div>';
                    }
                    $feature_show .= '</div>';
                }
                $feature_show .= '
                    <script>
                        $(".search_pf_fvalue").on("click", function() {
                            $("#frm_left_search").submit();
                        });
                    </script>
                    <script>
                        $(".show-more").click(function() {
                            if ($("#category_show_" + $(this).attr("data-id") + ", #list_checkbox_hide_" + $(this).attr("data-id") + " ").hasClass("category_show_height")) {
                                $(this).text("(Weniger anzeigen)");
                            } else {
                                $(this).text("(Mehr anzeigen)");
                            }

                            $("#category_show_" + $(this).attr("data-id") + ", #list_checkbox_hide_" + $(this).attr("data-id") + "").toggleClass("category_show_height");
                        });
                    </script>
            ';
            }
            //print($feature_show);die();
            $retValue = array("status" => "1", "message" => "Record found", "feature_show" => $feature_show);
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'category_type_inner':
            $retValue = array();

            $limit = 15;
            $start = $_REQUEST['start'] * $limit;
            $last_record = $start;
            $pro_type = $_REQUEST['pro_type'];
            $level_one = $_REQUEST['level_one'];
            $price_without_tex_display = $_REQUEST['price_without_tex_display'];
            $pbp_price_with_tex_display = $_REQUEST['pbp_price_with_tex_display'];

            $category_type_inner = "";
            if ($level_one == 20) {
                $Query = "SELECT sub_cat.cat_id, sub_cat.group_id, sub_cat.parent_id, cat.cat_title_de AS cat_title, sub_cat.cat_title_de AS sub_cat_title, cat.cat_params_de AS cat_params, sub_cat.cat_params_de AS sub_cat_params, sub_cat.cat_orderby, sub_cat.cat_status FROM category AS sub_cat LEFT OUTER JOIN category AS cat ON cat.group_id = sub_cat.parent_id  WHERE sub_cat.parent_id = '" . $level_one . "' AND sub_cat.cat_status = '1' AND EXISTS (SELECT 1 FROM category_map AS cm WHERE cm.cm_type = '" . $pro_type . "' AND cm.cat_id_level_two = sub_cat.group_id ) ORDER BY sub_cat.cat_orderby ASC, sub_cat.group_id ASC";
            } else {
                //$Query = "SELECT sub_cat.cat_id, sub_cat.group_id, sub_cat.parent_id, cat.cat_title_de AS cat_title, sub_cat.cat_title_de AS sub_cat_title, cat.cat_params_de AS cat_params, sub_cat.cat_params_de AS sub_cat_params, sub_cat.cat_orderby, sub_cat.cat_status FROM category AS sub_cat LEFT OUTER JOIN category AS cat ON cat.group_id = sub_cat.parent_id  WHERE sub_cat.parent_id IN ( SELECT main_cat.group_id FROM category AS main_cat WHERE main_cat.parent_id = '" . $level_one . "' ORDER BY main_cat.group_id ASC) AND sub_cat.cat_status = '1' AND EXISTS (SELECT 1 FROM category_map AS cm WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(sub_cat.group_id, cm.cat_id) ) ORDER BY sub_cat.cat_orderby ASC, sub_cat.group_id ASC";
                $Query = "SELECT DISTINCT sub_cat.cat_id, sub_cat.group_id, sub_cat.parent_id, cat.cat_title_de AS cat_title, sub_cat.cat_title_de AS sub_cat_title, cat.cat_params_de AS cat_params, sub_cat.cat_params_de AS sub_cat_params, sub_cat.cat_orderby, sub_cat.cat_status FROM category AS sub_cat INNER JOIN category AS main_cat ON main_cat.group_id = sub_cat.parent_id LEFT JOIN category AS cat ON cat.group_id = sub_cat.parent_id INNER JOIN category_map AS cm ON cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(sub_cat.group_id, cm.cat_id) WHERE main_cat.parent_id = '" . $level_one . "' AND sub_cat.cat_status = 1 ORDER BY sub_cat.cat_orderby, sub_cat.group_id";
            }
            //print($Query);die();
            $counter = mysqli_num_rows(mysqli_query($GLOBALS['conn'], $Query));
            $rs = mysqli_query($GLOBALS['conn'], $Query . " LIMIT " . $start . ", " . $limit);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $last_record++;
                    $pg_mime_source_url_href = "files/no_img_1.jpg";
                    if ($level_one == 20) {
                        $category_data = returnMultiName("pg_mime_source_url, MIN(pbp_price_without_tax), MIN(pbp_price_amount)", "vu_category_map", "cat_id_level_two",  $row->group_id, 3, "AND cm_type = '" . $pro_type . "' GROUP BY cat_id_level_two");
                    } else {
                        $category_data = returnMultiName("pg_mime_source_url, MIN(pbp_price_without_tax), MIN(pbp_price_amount)", "vu_category_map", "cat_id",  $row->group_id, 3, "AND cm_type = '" . $pro_type . "' GROUP BY cat_id");
                    }
                    //print_r($category_data);die();
                    if (empty($category_data)) {
                        continue;
                    }
                    $pg_mime_source_url_href = $category_data['data_1'];
                    $pbp_price_without_tax = $category_data['data_2'];
                    $pbp_price_amount = $category_data['data_3'];
                    if ($level_one == 20) {
                        //$cat_two_params_de = returnName("cat_params_de", "category", "group_id", $row->parent_id);
                        $cat_link = "artikelarten/" . $row->sub_cat_params . "/20";
                    } else {
                        //$cat_two_params_de = returnName("cat_params_de", "category", "group_id", $row->parent_id);
                        $cat_link = "artikelarten/" . $row->cat_params . "/" . $row->sub_cat_params;
                    }
                    $category_type_inner .= '<div class="ctg_type_col">
												<a href="' . $cat_link . '" title = "' . $row->sub_cat_title . '">
													<div class="ctg_type_card">
														<div class="ctg_type_image"><img loading="lazy" src="' . get_image_link(75, $pg_mime_source_url_href) . '" alt="' . $row->sub_cat_title . '"></div>
														<div class="ctg_type_detail">
															<div class="ctg_type_title">' . $row->sub_cat_title . '</div>
															<div class="ctg_type_price price_without_tex" ' . $price_without_tex_display . ' > ab ' . price_format(($pbp_price_without_tax > 0) ? $pbp_price_without_tax : 0.00) . ' €</div>
															<div class="ctg_type_price pbp_price_with_tex" ' . $pbp_price_with_tex_display . ' >ab ' . price_format(($pbp_price_amount) ? $pbp_price_amount : 0.00) . ' €</div>
														</div>
													</div>
												</a>
											</div>';
                }
            }

            $retValue = array("status" => "1", "message" => "Record found", "counter" => $counter, "last_record" => $last_record, "category_type_inner_page" => ($_REQUEST['start'] + 1), "Query" => $Query,  "category_type_inner" => $category_type_inner);
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'lf_group_id_inner':
            //print_r($_REQUEST);die();
            $retValue = array();
            $lf_group_id_inner = "";
            $lf_action_type = $_REQUEST['lf_action_type'];
            $pro_type = isset($_REQUEST['pro_type']) ? $_REQUEST['pro_type'] : 0;
            $leve_id = $_REQUEST['leve_id'];
            $level_check = $_REQUEST['level_check'];
            $manf_id = isset($_REQUEST['manf_id']) ? $_REQUEST['manf_id'] : 0;
            $left_filter_cat_WhereQuery = $_REQUEST['left_filter_cat_WhereQuery'];
            $input_type = "checkbox";
            if ($manf_id > 0) {
                if ($leve_id > 0) {
                    $input_type = "radio";
                    $Query = "SELECT cat.cat_id, cat.group_id, cat.parent_id, cat.cat_title_de AS cat_title, cat.cat_params_de AS cat_params, cat_level_two.cat_params_de AS cat_level_params, cat.cat_orderby FROM category AS cat LEFT OUTER JOIN category AS cat_level_two ON cat_level_two.group_id = cat.parent_id WHERE  cat.parent_id = '" . $leve_id . "'  ORDER BY cat.cat_orderby ASC";
                } else {
                    $Query = "SELECT cat.cat_id, cat.group_id, cat.parent_id, cat.cat_title_de AS cat_title, cat.cat_params_de AS cat_params, cat_level_two.cat_params_de AS cat_level_params, cat.cat_orderby FROM category AS cat LEFT OUTER JOIN category AS cat_level_two ON cat_level_two.group_id = cat.parent_id WHERE  cat.group_id IN (SELECT cm.cat_id FROM category_map AS cm WHERE cm.supplier_id IN (SELECT pro.supplier_id FROM products AS pro WHERE pro.manf_id = '" . $manf_id . "') GROUP BY cm.cat_id ) ORDER BY cat.cat_orderby ASC";
                }
            } elseif ($pro_type == 20) {
                $level_check = $leve_id;
                $Query = "SELECT cat.cat_id, cat.group_id, cat.parent_id, cat.cat_title_de AS cat_title, cat.cat_params_de AS cat_params, cat_level_two.cat_params_de AS cat_level_params FROM category AS cat LEFT OUTER JOIN category AS cat_level_two ON cat_level_two.group_id = cat.parent_id WHERE cat.parent_id = '20' AND EXISTS (SELECT 1 FROM vu_category_map AS cm WHERE " . $left_filter_cat_WhereQuery . " ) ORDER BY cat.cat_orderby ASC ";
            } else {
                $Query = "SELECT cat.cat_id, cat.group_id, cat.parent_id, cat.cat_title_de AS cat_title, cat.cat_params_de AS cat_params, cat_level_two.cat_params_de AS cat_level_params FROM category AS cat LEFT OUTER JOIN category AS cat_level_two ON cat_level_two.group_id = cat.parent_id WHERE cat.parent_id = '" . $leve_id . "' AND EXISTS (SELECT 1 FROM vu_category_map AS cm WHERE " . $left_filter_cat_WhereQuery . " ) ORDER BY cat.cat_orderby ASC ";
            }
            //print($Query);die();
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $lf_group_id_inner .= '<li>
                        <label class="gerenric_checkbox">
                           ' . $row->cat_title . '
                            <input type="' . $input_type . '" name="lf_group_id[]" class="lf_group_id" id="lf_group_id" value="' . $row->group_id . '" ' . (($level_check == $row->group_id) ? "checked" : "") . '>
                            <span class="checkmark"></span>
                        </label>
                    </li>';
                }
            }
            if ($manf_id > 0) {
                $lf_group_id_inner .= '
                <script>
                    $(".lf_group_id").on("click", function() {
                        $("#gerenric_product_inner_page").val(0);
                        $("#gerenric_product_inner").html("");
                        $(".lf_manf_id").attr("checked", false);
                        $(".lf_pf_fvalue").attr("checked", false);
                        var lf_group_id = [];
                        $(".lf_group_id:checked").each(function() {
                            lf_group_id.push($(this).val());
                        });
                        //console.log("Selected values: " + lf_group_id.join(", "));
                        lf_pf_fvalue_inner(lf_group_id.join(", "));
                        gerenric_product_inner(lf_group_id.join(", "));
                    });
                </script>
                ';
            } elseif ($lf_action_type == 1 && strlen($leve_id) > 2) {
                $lf_group_id_inner .= '
                <script>
                    $(".lf_group_id").on("click", function() {
                        $("#gerenric_product_inner_page").val(0);
                        $("#gerenric_product_inner").html("");
                        $(".lf_manf_id").attr("checked", false);
                        $(".lf_pf_fvalue").attr("checked", false);
                        var lf_group_id = [];
                        $(".lf_group_id:checked").each(function() {
                            lf_group_id.push($(this).val());
                        });
                        //console.log("Selected values: " + lf_group_id.join(", "));
                        lf_manf_id_inner(lf_group_id.join(", "));
                        lf_pf_fvalue_inner(lf_group_id.join(", "));
                        gerenric_product_inner(lf_group_id.join(", "));
                    });
                </script>
                ';
            } else {
                $lf_group_id_inner .= '
                <script>
                    $(".lf_group_id").on("click", function() {
                        $(".lf_manf_id").attr("checked", false);
                        $(".lf_pf_fvalue").attr("checked", false);
                        $("#frm_left_search_cat").submit();
                    });
                </script>
                ';
            }
            $retValue = array("status" => "1", "message" => "Record found", "lf_group_id_inner" => $lf_group_id_inner);
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;
        case 'lf_manf_id_inner':

            $retValue = array();
            $manf_check = array();
            $lf_manf_id_inner = "";
            $lf_action_type = $_REQUEST['lf_action_type'];
            $pro_type = isset($_REQUEST['pro_type']) ? $_REQUEST['pro_type'] : 0;
            $leve_id = $_REQUEST['leve_id'];
            $Sidefilter_brandwith = $_REQUEST['Sidefilter_brandwith'];
            $manf_check = (!empty($_REQUEST['manf_check'])) ? $_REQUEST['manf_check'] : [];
            if (isset($_REQUEST['lf_group_id']) && !empty($_REQUEST['lf_group_id'])) {
                //$whereclause = "WHERE cm.pro_type = '".$pro_type."'";
                if ($pro_type == 20) {
                    $Sidefilter_brandwith = " cm.cm_type = '" . $pro_type . "' AND cm.cat_id_level_two IN (" . $_REQUEST['lf_group_id'] . ")";
                } else {
                    $Sidefilter_brandwith .= " AND cm.cat_id IN (" . $_REQUEST['lf_group_id'] . ")";
                }
                $lf_group_id = explode(",", $_REQUEST['lf_group_id']);

                //$heading_title = returnName("cat_title_de AS cat_title", "category", "group_id", $_REQUEST['lf_group_id'][0]);

            }
            $Query = "SELECT * FROM manufacture AS manf WHERE manf.manf_id IN (SELECT cm.manf_id FROM vu_category_map AS cm WHERE " . $Sidefilter_brandwith . ") AND manf.manf_status = '1' ORDER BY manf.manf_id ASC";
            //print($Query);die();
            //$count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], $Query));
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            $count = mysqli_num_rows($rs);
            if (mysqli_num_rows($rs) > 0) {
                $lf_manf_id_inner .= '<h3>Marke</h3>
                        <ul class="list_checkbox_hide ' . (($count > 4) ? 'category_show_height' : '') . ' " id="list_checkbox_hide_0">';
                while ($row = mysqli_fetch_object($rs)) {

                    $lf_manf_id_inner .= '<li>
                            <label class="gerenric_checkbox">
                                ' . $row->manf_name . '
                                <input type="checkbox" name="lf_manf_id[]" class="lf_manf_id" id="lf_manf_id" value="' . $row->manf_id . '" ' . (in_array($row->manf_id, $manf_check) ? 'checked' : '') . '>
                                <span class="checkmark"></span>
                            </label>
                        </li>';
                }
                if ($count > 4) {
                    $lf_manf_id_inner .= '</ul><div class="show-more" data-id="0">(Mehr anzeigen)</div>';
                }
                if ($lf_action_type == 1) {
                    $lf_manf_id_inner .= '
                    <script>
                        $(".lf_manf_id").on("click", function() {
                            $("#gerenric_product_inner_page").val(0);
                            $("#gerenric_product_inner").html("");
                            $(".lf_pf_fvalue").attr("checked", false);
                            var lf_group_id = [];
                            $(".lf_group_id:checked").each(function() {
                                lf_group_id.push($(this).val());
                            });
                            var lf_manf_id = [];
                            $(".lf_manf_id:checked").each(function() {
                                lf_manf_id.push($(this).val());
                            });
                            //console.log("Selected values: " + lf_group_id.join(", "));
                            //console.log("Selected values: " + lf_manf_id.join(", "));
                            
                            lf_pf_fvalue_inner(lf_group_id.join(", "), lf_manf_id.join(", "));
                            gerenric_product_inner(lf_group_id.join(", "), lf_manf_id.join(", ")); 
                           
                        });
                    </script>
                    ';
                } else {
                    $lf_manf_id_inner .= '
                    <script>
                        $(".lf_manf_id").on("click", function() {
                            $(".lf_pf_fvalue").attr("checked", false);
                            $("#frm_left_search_cat").submit();
                        });
                    </script>
                    ';
                }
            }


            $retValue = array("status" => "1", "message" => "Record found", "lf_manf_id_inner" => $lf_manf_id_inner);
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'lf_pf_fvalue_inner':
            //print_r($_REQUEST);
            $retValue = array();
            $pf_fvalue_check = array();
            $lf_pf_fvalue_inner = "";
            $lf_action_type = $_REQUEST['lf_action_type'];
            $leve_id =  ((isset($_REQUEST['level_check']) && $_REQUEST['level_check'] > 0) ? $_REQUEST['level_check'] : $_REQUEST['leve_id']);
            $pf_fvalue_check = (!empty($_REQUEST['pf_fvalue_check'])) ? $_REQUEST['pf_fvalue_check'] : [];
            if (isset($_REQUEST['lf_group_id']) && !empty($_REQUEST['lf_group_id'])) {
                $leve_id = $_REQUEST['lf_group_id'];
            }
            $manf_id = isset($_REQUEST['manf_id']) ? $_REQUEST['manf_id'] : 0;
            $counter = 0;
            if ($manf_id > 0) {
                if ($leve_id == 701) {
                    $Query1 = "SELECT csf.*, sf.lov_sf_title, sf.lov_sf_params_de AS lov_sf_params FROM category_side_filter AS csf LEFT OUTER JOIN lov_side_filter AS sf ON sf.lov_sf_id = csf.lov_sf_id WHERE csf.group_id = '70100' ORDER BY csf.csf_orderby ASC";
                } else {
                    $Query1 = "SELECT csf.*, sf.lov_sf_title, sf.lov_sf_params_de AS lov_sf_params FROM category_side_filter AS csf LEFT OUTER JOIN lov_side_filter AS sf ON sf.lov_sf_id = csf.lov_sf_id WHERE csf.group_id IN (SELECT cm.cat_id FROM category_map AS cm WHERE cm.supplier_id IN (SELECT pro.supplier_id FROM products AS pro WHERE pro.manf_id = '" . $manf_id . "') GROUP BY cm.cat_id ) ORDER BY csf.csf_orderby ASC";
                }
            } else {
                $Query1 = "SELECT csf.*, sf.lov_sf_title, sf.lov_sf_params_de AS lov_sf_params FROM category_side_filter AS csf LEFT OUTER JOIN lov_side_filter AS sf ON sf.lov_sf_id = csf.lov_sf_id WHERE csf.group_id IN (" . $leve_id . ") ORDER BY csf.csf_orderby ASC";
            }
            //print_r($Query1);die();
            $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
            if (mysqli_num_rows($rs) > 0) {
                while ($rw1 = mysqli_fetch_object($rs1)) {
                    $counter++;
                    //$Query2 = "";
                    if ($manf_id > 0) {
                        $Query2 = "SELECT * FROM products_feature AS pf WHERE pf.pf_fname = '" . $rw1->lov_sf_title . "' AND pf.supplier_id IN (SELECT cm.supplier_id FROM vu_category_map AS cm  WHERE cm.manf_id = '" . $manf_id . "') GROUP BY pf.pf_fvalue ORDER BY pf.pf_forder ASC";
                    } elseif ((isset($_REQUEST['lf_group_id']) && !empty($_REQUEST['lf_group_id'])) || (isset($_REQUEST['lf_manf_id']) && !empty($_REQUEST['lf_manf_id']))) {
                        $products_featureWhere = "";
                        $products_featureWhere .= " WHERE cm.cat_id IN (" . $leve_id . ")";

                        if (isset($_REQUEST['lf_manf_id']) && !empty($_REQUEST['lf_manf_id'])) {
                            $products_featureWhere .= " AND cm.manf_id IN (" . $_REQUEST['lf_manf_id'] . ")";
                        }
                        $Query2 = "SELECT * FROM products_feature AS pf WHERE pf.pf_fname = '" . $rw1->lov_sf_title . "' AND pf.supplier_id IN (SELECT cm.supplier_id FROM vu_category_map AS cm " . $products_featureWhere . ") GROUP BY pf.pf_fvalue ORDER BY pf.pf_forder ASC";
                    } else {
                        //$Query2 = "SELECT * FROM products_feature AS pf WHERE pf.pf_fname = '" . $rw1->lov_sf_title . "' AND pf.supplier_id IN (SELECT cm.supplier_id FROM vu_category_map AS cm WHERE FIND_IN_SET('" . $leve_id . "', cm.sub_group_ids)) GROUP BY pf.pf_fvalue ORDER BY pf.pf_forder ASC";
                        $Query2 = "SELECT * FROM products_feature AS pf WHERE pf.pf_fname = '" . $rw1->lov_sf_title . "' AND pf.supplier_id IN (SELECT cm.supplier_id FROM category_map_subgroups AS cm WHERE cm.subgroup_id='" . $leve_id . "') GROUP BY pf.pf_fvalue ORDER BY pf.pf_forder ASC";
                    }
                    //print($Query2);die();
                    $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], $Query2));
                    $rs2 = mysqli_query($GLOBALS['conn'], $Query2);
                    $rw2 = mysqli_fetch_object($rs2);
                    if (mysqli_num_rows($rs2) > 0) {
                        $lf_pf_fvalue_inner .= '<div class="categroy_block">
                            <h3>' . $rw1->lov_sf_title . '</h3>
                            <ul class="list_checkbox_hide ' . (($count > 4) ? 'category_show_height' : '') . ' " id="list_checkbox_hide_' . $counter . '">';
                        do {

                            $lf_pf_fvalue_inner .= '<li>
                                    <label class="gerenric_checkbox">
                                        ' . $rw2->pf_fvalue . '
                                        <input type="checkbox" name="lf_pf_fvalue[]" id="lf_pf_fvalue" class="lf_pf_fvalue" value="' . $rw2->pf_fvalue_params_de . '" ' . (in_array($rw2->pf_fvalue_params_de, $pf_fvalue_check) ? 'checked' : '') . '>
                                        <span class="checkmark"></span>
                                    </label>
                                </li>';
                        } while ($rw2 = mysqli_fetch_object($rs2));
                        $lf_pf_fvalue_inner .= '</ul>';
                        if ($count > 4) {
                            $lf_pf_fvalue_inner .= '<div class="show-more" data-id="' . $counter . '">(Mehr anzeigen)</div>';
                        }
                        $lf_pf_fvalue_inner .= '</div>';
                    }
                }
                if ($lf_action_type == 1) {
                    $lf_pf_fvalue_inner .= '
                    <script>
                        $(".lf_pf_fvalue").on("click", function() {
                            $("#gerenric_product_inner_page").val(0);
                            $("#gerenric_product_inner").html("");
                            var lf_group_id = [];
                            $(".lf_group_id:checked").each(function() {
                                lf_group_id.push($(this).val());
                            });
                            var lf_manf_id = [];
                            $(".lf_manf_id:checked").each(function() {
                                lf_manf_id.push($(this).val());
                            });
                            var lf_pf_fvalue = [];
                            $(".lf_pf_fvalue:checked").each(function() {
                                lf_pf_fvalue.push($(this).val());
                            });
                            //console.log("Selected values of lf_group_id: " + lf_group_id.join(", "));
                            //console.log("Selected values of lf_manf_id: " + lf_manf_id.join(", "));
                            //console.log("Selected values of lf_pf_fvalue: " + lf_pf_fvalue.join(", "));
                            gerenric_product_inner(lf_group_id.join(", "), lf_manf_id.join(", "), lf_pf_fvalue.join(", ")); 
                        });
                    </script>
                    ';
                } else {
                    $lf_pf_fvalue_inner .= '
                    <script>
                        $(".lf_pf_fvalue").on("click", function() {
                            $("#frm_left_search_cat").submit();
                        });
                    </script>
                    ';
                }
            }

            $retValue = array("status" => "1", "message" => "Record found", "lf_pf_fvalue_inner" => $lf_pf_fvalue_inner);
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'gerenric_product_inner':
            //print_r($_REQUEST);die();
            $retValue = array();
            $gerenric_product_inner  = "";
            $pro_type = $_REQUEST['pro_type'];
            $level_two = isset($_REQUEST['level_two']) ? $_REQUEST['level_two'] : 0;
            $manf_id = isset($_REQUEST['manf_id']) ? $_REQUEST['manf_id'] : 0;
            $whereclause = $_REQUEST['whereclause'];
            $price_without_tex_display = $_REQUEST['price_without_tex_display'];
            $pbp_price_with_tex_display = $_REQUEST['pbp_price_with_tex_display'];
            $limit = 24;
            $start = $_REQUEST['start'] * $limit;
            $last_record = $start;
            if (isset($_REQUEST['lf_group_id']) && !empty($_REQUEST['lf_group_id'])) {
                //$whereclause = "WHERE cm.pro_type = '".$pro_type."'";
                if ($pro_type == 20) {
                    $whereclause .= " AND cm.cat_id_level_two IN  (" . $_REQUEST['lf_group_id'] . ")";
                } else {
                    $whereclause .= " AND cm.cat_id IN (" . $_REQUEST['lf_group_id'] . ")";
                }
                //$lf_group_id = explode(",", $_REQUEST['lf_group_id']);

                //$heading_title = returnName("cat_title_de AS cat_title", "category", "group_id", $_REQUEST['lf_group_id'][0]);

            } elseif ($level_two > 0 && $pro_type == 20) {
                $whereclause .= " AND cm.cat_id_level_two IN  (" . $level_two . ")";
            }
            if (isset($_REQUEST['lf_manf_id']) && !empty($_REQUEST['lf_manf_id'])) {

                $whereclause .= " AND cm.manf_id IN (" . $_REQUEST['lf_manf_id'] . ")";
            }
            if (isset($_REQUEST['lf_pf_fvalue']) && !empty($_REQUEST['lf_pf_fvalue'])) {
                $input = $_REQUEST['lf_pf_fvalue'];
                $items = explode(',', $input);
                $items = array_map('trim', $items);
                $items = array_map(function ($item) {
                    return "'$item'";
                }, $items);
                $lf_pf_fvalue = implode(', ', $items);
                $whereclause .= " AND cm.supplier_id IN (SELECT pf.supplier_id FROM products_feature AS pf WHERE  pf.pf_fvalue_params_de IN (" . $lf_pf_fvalue . ") )";
                //$heading_title = returnName("cat_title_de AS cat_title", "category", "group_id", $_REQUEST['lf_parent_id']);
            }

            $order_by = "";
            if (isset($_REQUEST['sortby'])) {
                $sortby = $_REQUEST['sortby'];
                switch ($sortby) {
                    case 1:
                        $order_by = "ORDER BY cm.pbp_price_amount DESC";
                        break;
                    case 2:
                        $order_by = "ORDER BY cm.pbp_price_amount ASC";
                        break;
                    case 3:
                        $order_by = "ORDER BY cm.pro_description_short ASC";
                        break;
                    case 4:
                        $order_by = "ORDER BY cm.pro_description_short DESC";
                        break;
                    default:
                        $order_by = "";
                }
            }
            if (isset($_REQUEST['pq_quantity_selected']) && $_REQUEST['pq_quantity_selected'] > 0) {
                $pq_quantity_selected = $_REQUEST['pq_quantity_selected'];
                switch ($pq_quantity_selected) {
                    case 1:
                        $whereclause .= " AND cm.pq_quantity <= '0' AND pq_status = 'true'";
                        break;
                    case 2:
                        $whereclause .= " AND cm.pq_quantity > '0' AND pq_status = 'false'";
                        break;
                }
            }
            $total_count = 0;
            if ($manf_id > 0) {
                $Query = "SELECT * FROM vu_category_map AS cm WHERE cm.manf_id = '" . $manf_id . "' " . $whereclause . " " . $order_by . "";
            } else {
                $Query = "SELECT * FROM vu_category_map AS cm " . $whereclause . " AND cm.cm_type = '" . $pro_type . "' " . $order_by . "";
            }
            //print($Query);die();
            $counter = $start;

            $total_count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], $Query));
            $rs = mysqli_query($GLOBALS['conn'], $Query . " LIMIT " . $start . ", " . $limit);
            //$rs = mysqli_query($GLOBALS['conn'], $Query);
            //print(mysqli_num_rows($rs));die();
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $counter++;
                    $last_record++;
                    $special_price = array();
                    $sub_group_ids = explode(",", $row->sub_group_ids);
                    $cat_id_one = $sub_group_ids[1];
                    $cat_id_two = $sub_group_ids[0];
                    //if (isset($_SESSION["UID"]) && $_SESSION["UID"] > 0) {
                    $special_price = user_special_price("supplier_id", $row->supplier_id);

                    if (!$special_price) {
                        $special_price = user_special_price("level_two", $cat_id_two);
                    }

                    if (!$special_price) {
                        $special_price = user_special_price("level_one", $cat_id_one);
                    }
                    //print_r($special_price);die();
                    $gerenric_product_inner .= '<div class="pd_card">
                        <div class="pd_image"><a href="' . product_detail_url($row->supplier_id) . '" title = "' . $row->pro_udx_seo_internetbezeichung . '"><img src="' . get_image_link(75, $row->pg_mime_source_url) . '" alt="' . $row->pro_udx_seo_internetbezeichung . '"></a></div>
                        <div class="pd_detail">
                            <h5><a href="' . product_detail_url($row->supplier_id) . '" title = "' . $row->pro_udx_seo_internetbezeichung . '" > ' . $row->pro_udx_seo_epag_title . ' </a></h5>';
                    $count = 0;
                    if ($row->pro_udx_seo_epag_id > 0) {
                        $Query1 = "SELECT pf.*, pro.pro_description_short, pro.pro_udx_seo_epag_title, pro.pro_udx_seo_epag_title_params_de, pg.pg_mime_source_url FROM products_feature AS pf LEFT OUTER JOIN products AS pro ON pro.supplier_id = pf.supplier_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pf.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pf.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_order ASC LIMIT 1) WHERE pf.pro_udx_seo_epag_id = '" . $row->pro_udx_seo_epag_id . "' AND pf.pf_fname = '" . $row->pro_udx_seo_selection_feature . "'";
                        $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
                        $count = mysqli_num_rows($rs1);
                        if ($count > 1) {
                            if (mysqli_num_rows($rs1) > 0) {
                                $gerenric_product_inner .= '<div class="pd_detail_shirt detail_data_show">
                                            <h2>' . $row->pro_udx_seo_selection_feature . ': <span id="color_title_' . $counter . '"> ' . returnName("pf_fvalue", "products_feature", "supplier_id", $row->supplier_id, "AND pf_fname = '" . $row->pro_udx_seo_selection_feature . "'") . ' </span> </h2>
                                            <ul>';
                                while ($row1 = mysqli_fetch_object($rs1)) {
                                    $gerenric_product_inner .= '<li>
                                                        <input type="radio" class="color" id="color_' . $counter . '" name="color_radio_' . $counter . '" data-id="' . $counter . '" value="' . $row1->supplier_id . '" ' . (($row1->supplier_id == $row->supplier_id) ? "checked" : "") . '>
                                                        <label for="color_' . $counter . '">
                                                            <span style="' . ((in_array($row->pro_udx_seo_selection_feature, array('Farbe', 'Schreibfarbe'))) ? 'height: 60px;' : 'height: 30px;') . '">';
                                    if (in_array($row->pro_udx_seo_selection_feature, array('Farbe', 'Schreibfarbe'))) {
                                        $gerenric_product_inner .= '<img class="color_tab" id="color_tab_' . $row1->supplier_id . '" data-id="' . $counter . '" data-supplier-id="' . $row1->supplier_id . '" data-pro-description="' . $row1->pro_udx_seo_epag_title_params_de . '" src="' . get_image_link(160, $row1->pg_mime_source_url) . '" title="' . $row1->pf_fvalue . '" alt="' . $row1->pf_fvalue . '">';
                                    } else {
                                        $gerenric_product_inner .= '<label for="" class="color_tab" id="color_tab_' . $row1->supplier_id . '" data-id="' . $counter . '" data-supplier-id="' . $row1->supplier_id . '" data-pro-description="' . $row1->pro_udx_seo_epag_title_params_de . '" title="' . $row1->pf_fvalue . '">' . $row1->pf_fvalue . '</label>';
                                    }
                                    $gerenric_product_inner .= '</span>
                                                        </label>
                                                    </li>';
                                }
                                $gerenric_product_inner .= '</ul>
                                        </div>';
                            }
                        }
                    }
                    $quantity_lenght = 0;
                    $Query2 = "SELECT * FROM products_quantity WHERE supplier_id = '" . dbStr(trim($row->supplier_id)) . "'";
                    //print();
                    $rs2 = mysqli_query($GLOBALS['conn'], $Query2);
                    if (mysqli_num_rows($rs2) > 0) {
                        $row2 = mysqli_fetch_object($rs2);
                        $pq_quantity = $row2->pq_quantity;
                        $pq_upcomming_quantity = $row2->pq_upcomming_quantity;
                        $pq_status = $row2->pq_status;
                        $ci_qty_type = 0;
                        if ($pq_status == 'true') {
                            $ci_qty_type = 1;
                        }
                        /*if ($pq_quantity == 0 && $pq_status == 'true') {
																$quantity_lenght = $pq_upcomming_quantity;
																print('<div class="product_order_title"> ' . $pq_upcomming_quantity . ' Stück bestellt</div>');
															} elseif ($pq_quantity > 0 && $pq_status == 'false') {
																$quantity_lenght = $pq_quantity + $pq_upcomming_quantity;
																print('<div class="product_order_title green"> ' . $pq_quantity . ' Stück sofort verfügbar</div>');
															}*/
                        if (($pq_quantity == 0 || $pq_quantity < 0) && $pq_status == 'true') {
                            $quantity_lenght = $pq_upcomming_quantity;
                            $gerenric_product_inner .= '<div class="product_order_title green"> ' . $pq_upcomming_quantity . ' Stück Kurzfristig lieferbar</div>';
                        } elseif ($pq_quantity > 0 && $pq_status == 'false') {
                            $quantity_lenght = $pq_quantity;
                            $gerenric_product_inner .= '<div class="product_order_title green"> ' . $pq_quantity . ' Stück sofort verfügbar</div>';
                        } elseif (($pq_quantity == 0 || $pq_quantity < 0) && $pq_status == 'false') {
                            $gerenric_product_inner .= '<div class="product_order_title">Auf Anfrage</div>';
                        }
                    } else {
                        if ($pro_type > 0) {
                            $quantity_lenght = 1;
                            $ci_qty_type = 0;
                        } else {
                            $gerenric_product_inner .= '<div class="product_order_title">Auf Anfrage</div>';
                        }
                    }
                    $gerenric_product_inner .= '<div class="pd_rating">
                                <ul>
                                    <li>
                                        <div class="fa fa-star"></div>
                                        <div class="fa fa-star"></div>
                                        <div class="fa fa-star"></div>
                                        <div class="fa fa-star"></div>
                                        <div class="fa fa-star"></div>
                                    </li>
                                </ul>
                            </div>';
                    if (!empty($special_price)) {
                        $gerenric_product_inner .= '<div class="pd_prise price_without_tex" ' . $price_without_tex_display . '> ' . "<del>" . price_format(((config_site_special_price > 0 && $row->pbp_special_price_without_tax > 0) ? $row->pbp_special_price_without_tax : $row->pbp_price_without_tax)) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $row->pbp_special_price_without_tax > 0) ? $row->pbp_special_price_without_tax : $row->pbp_price_without_tax), $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>" . ' </div>
                                <div class="pd_prise pbp_price_with_tex" ' . $pbp_price_with_tex_display . '> ' . "<del>" . price_format(((config_site_special_price > 0 && $row->pbp_special_price_amount > 0) ? $row->pbp_special_price_amount : $row->pbp_price_amount)) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $row->pbp_special_price_amount > 0) ? $row->pbp_special_price_amount : $row->pbp_price_amount), $special_price['usp_discounted_value'], $row->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>" . ' </div>';
                    } else {
                        $gerenric_product_inner .= '<div class="pd_prise price_without_tex" ' . $price_without_tex_display . '>' . price_format(((config_site_special_price > 0 && $row->pbp_special_price_without_tax > 0) ? $row->pbp_special_price_without_tax : $row->pbp_price_without_tax)) . '€</div>
                                <div class="pd_prise pbp_price_with_tex" ' . $pbp_price_with_tex_display . '>' . price_format(((config_site_special_price > 0 && $row->pbp_special_price_amount > 0) ? $row->pbp_special_price_amount : $row->pbp_price_amount)) . '€</div>';
                    }
                    $gerenric_product_inner .= '<div class="pd_btn">
                                <a title="In den Warenkorb" class="' . (($quantity_lenght > 0) ? 'add_to_card' : '') . '" href="javascript:void(0)" data-id="' . $row->pro_id . '">
                                    <input type="hidden" id="pro_id_' . $row->pro_id . '" name="pro_id" value="' . $row->pro_id . '">
                                    <input type="hidden" id="pro_type_' . $row->pro_id . '" name="pro_type" value="' . $row->pro_type . '">
                                    <input type="hidden" id="supplier_id_' . $row->pro_id . '" name="supplier_id" value="' . $row->supplier_id . '">
                                    <input type="hidden" id="ci_type_' . $row->pro_id . '" name="ci_type" value="0">
                                    <input type="hidden" id="ci_qty_' . $row->pro_id . '" name="ci_qty" value="1">
                                    <input type="hidden" id="ci_qty_type_' . $row->pro_id . '" name="ci_qty_type" value="' . $ci_qty_type . '">
                                    <input type="hidden" id="ci_discount_type_' . $row->pro_id . '" name="ci_discount_type" value="' . ((!empty($special_price)) ? $special_price['usp_price_type'] : '0') . '">
                                    <input type="hidden" id="ci_discount_value_' . $row->pro_id . '" name="ci_discount_value" value="' . ((!empty($special_price)) ? $special_price['usp_discounted_value'] : '0') . '">
                                    <div class="gerenric_btn">In den Einkaufswagen</div>
                                </a>
                            </div>
                        </div>
                    </div>';
                }
                /*$gerenric_product_inner .= '<div class="txt_align_center" id="btn_load" style="display: none;">
										<input type="hidden" name="gerenric_product_inner_page" id="gerenric_product_inner_page" value="'.($_REQUEST['start'] + 1).'">
										<div class="load-more-button">Weitere anzeigen &nbsp;<i class="fa fa-angle-down" ></i></div>
										<div class="load-less-button" style="display:none">Ansicht schließen &nbsp;<i class="fa fa-angle-up" ></i></div>
									</div>';*/
                $gerenric_product_inner .= '
                <script>
                    $(document).ready(function() {
                        $(".click_list").click(function() {
                            $(".list_porduct").addClass("list_class");
                            $(".detail_data_show").show();
                            $(".pd_image").css("height", "100%");
                        });
                        $(".click_th").click(function() {
                            $(".list_porduct").removeClass("list_class");
                            $(".detail_data_show").hide();
                            $(".pd_image").css("height", "");
                        });
                    });
                    $(".add_to_card").on("click", function(){
                        //console.log("add_to_card");
                        let pro_id = $("#pro_id_"+$(this).attr("data-id")).val();
                        let pro_type = $("#pro_type_"+$(this).attr("data-id")).val();
                        let supplier_id = $("#supplier_id_"+$(this).attr("data-id")).val();
                        let ci_type = $("#ci_type_"+$(this).attr("data-id")).val();
                        let ci_discount_type = $("#ci_discount_type_"+$(this).attr("data-id")).val();
                        let ci_discount_value = $("#ci_discount_value_"+$(this).attr("data-id")).val();
                        let ci_qty = $("#ci_qty_"+$(this).attr("data-id")).val();
                        let ci_qty_type = $("#ci_qty_type_"+$(this).attr("data-id")).val();

                        /*console.log("pro_type: "+pro_type);
                        console.log("supplier_id: "+supplier_id);
                        console.log("ci_qty: "+ci_qty);*/

                        $.ajax({
                            url: "ajax_calls.php?action=add_to_card",
                            method: "POST",
                            data: {
                                pro_id: pro_id,
                                pro_type: pro_type,
                                supplier_id: supplier_id,
                                ci_type: ci_type,
                                ci_discount_type: ci_discount_type,
                                ci_discount_value: ci_discount_value,
                                ci_qty: ci_qty,
                                ci_qty_type: ci_qty_type
                            },
                            success: function(response) {
                                //console.log("response = "+response);
                                const obj = JSON.parse(response);
                                //console.log(obj);
                                if(obj.status == 1){
                                    $("#header_quantity").text(obj.count+" items");
                                    $(".side_cart_click").trigger("click");
                                }
                            }
                        });
                    });
                    
                </script>';
            }
            //print($gerenric_product_inner);

            //$retValue = array("status" => "1", "message" => "Record found", "Query" => $Query, "total_count" => $total_count, "last_record" => $last_record,  "gerenric_product_inner_page" => ($_REQUEST['start'] + 1), "gerenric_product_inner" => $gerenric_product_inner);
            $retValue = array("status" => "1", "message" => "Record found", "total_count" => $total_count, "count" => $count, "last_record" => $last_record,  "gerenric_product_inner_page" => ($_REQUEST['start'] + 1), "gerenric_product_inner" => $gerenric_product_inner);
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;
        case 'delivery_instructions':
            $retValue = array();

            $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM user_shipping_address WHERE usa_id = " . $_REQUEST['usa_id']);

            if (mysqli_num_rows($rsM) > 0) {
                $rsMem = mysqli_fetch_object($rsM);

                $user_id = $rsMem->user_id;
                $old_user_id = $rsMem->old_user_id;
                $usa_type = $rsMem->usa_type;
                $usa_fname = $rsMem->usa_fname;
                $usa_lname = $rsMem->usa_lname;
                $usa_address = $rsMem->usa_address;
                $usa_additional_info = $rsMem->usa_additional_info;
                $usa_street = $rsMem->usa_street;
                $usa_house_no = $rsMem->usa_house_no;
                $usa_zipcode = $rsMem->usa_zipcode;
                $usa_contactno = $rsMem->usa_contactno;
                $countries_id = $rsMem->countries_id;
                $usa_delivery_instructions_tab_active = (($rsMem->usa_delivery_instructions_tab_active > 0) ? $rsMem->usa_delivery_instructions_tab_active : 1);
                $usa_house_check = $rsMem->usa_house_check;
                $apartment_security_code_display = 'style="display: none;"';
                if ($usa_house_check == "Bei einem Nachbarn") {
                    $apartment_security_code_display = 'style="display: block;"';
                }
                $usa_house_neighbor_name = $rsMem->usa_house_neighbor_name;
                $usa_house_neighbor_address = $rsMem->usa_house_neighbor_address;
                $usa_apartment_security_code = $rsMem->usa_apartment_security_code;
                $usa_appartment_call_box = $rsMem->usa_appartment_call_box;
                $usa_appartment_check = $rsMem->usa_appartment_check;
                $usa_business_mf_type = $rsMem->usa_business_mf_type;
                $usa_business_mf_status = $rsMem->usa_business_mf_status;
                $usa_business_mf_uw_status = $rsMem->usa_business_mf_uw_status;
                $usa_business_mf_24h_check = $rsMem->usa_business_mf_24h_check;
                $usa_business_ss_type = $rsMem->usa_business_ss_type;
                $usa_business_ss_status = $rsMem->usa_business_ss_status;
                $usa_business_ss_uw_status = $rsMem->usa_business_ss_uw_status;
                $usa_business_24h_check = $rsMem->usa_business_24h_check;
                $usa_business_close_check = $rsMem->usa_business_close_check;
                $usa_other_check = $rsMem->usa_other_check;
                $usa_default = $rsMem->usa_defualt;

                $monday_to_friday_group_display = 'style="display: block;"';
                $monday_to_friday_ungroup_display = 'style="display: none;"';

                $saturday_to_sunday_group_display = 'style="display: block;"';
                $saturday_to_sunday_ungroup_display = 'style="display: none;"';
                if ($usa_business_mf_type > 0 || $usa_business_ss_type > 0) {
                    if ($usa_business_mf_type > 0) {
                        $monday_to_friday_group_display = 'style="display: none;"';
                        $monday_to_friday_ungroup_display = 'style="display: block;"';
                    }
                    if ($usa_business_ss_type > 0) {
                        $saturday_to_sunday_group_display = 'style="display: none;"';
                        $saturday_to_sunday_ungroup_display = 'style="display: block;"';
                    }
                }

                $shipping_business_ungroup_days = array();
                $Query = "SELECT * FROM shipping_business_ungroup_days WHERE usa_id = '" . $_REQUEST['usa_id'] . "' ORDER BY sbugd_orderby ASC";
                $rs = mysqli_query($GLOBALS['conn'], $Query);
                if (mysqli_num_rows($rs) > 0) {
                    while ($rw = mysqli_fetch_object($rs)) {
                        $shipping_business_ungroup_days[] = array(
                            $rw->sbugd_orderby => array(
                                "sbugd_open" => $rw->sbugd_open,
                                "sbugd_close" => $rw->sbugd_close,
                                "sbugd_24hour_open" => $rw->sbugd_24hour_open,
                                "sbugd_close_delivery" => $rw->sbugd_close_delivery
                            )
                        );
                    }
                }
                //$property_type = array("Haus", "Wohnung", "Geschäft", "Andere");
                $short_detail = '<b>' . $usa_fname . ' ' . $usa_lname . '</b><br> ' . $usa_street . ' ' . $usa_house_no . ', ' . $usa_zipcode . '<br> Grundstückstyp';
                $formHead = "Lieferanweisungen hinzufügen";
            } else {
                $user_id = "";
                $old_user_id = "";
                $usa_type = "";
                $usa_fname = "";
                $usa_lname = "";
                $usa_address = "";
                $usa_additional_info = "";
                $usa_street = "";
                $usa_house_no = "";
                $usa_zipcode = "";
                $usa_contactno = "";
                $countries_id = 81;
                $usa_delivery_instructions_tab_active = 1;
                $usa_house_check = "";
                $usa_house_neighbor_name = "";
                $usa_house_neighbor_address = "";
                $apartment_security_code_display = 'style="display: none;"';
                $usa_apartment_security_code = "";
                $usa_appartment_call_box = "";
                $usa_appartment_check = "";
                $usa_business_mf_type = 0;
                $usa_business_mf_status = "";
                $usa_business_mf_uw_status = "";
                $usa_business_mf_24h_check = "";
                $usa_business_ss_type = 0;
                $usa_business_ss_status = "";
                $usa_business_ss_uw_status = "";
                $usa_business_24h_check = "";
                $usa_business_close_check = "";
                $usa_other_check = "";
                $usa_default = "";
                $short_detail = "";
                $shipping_business_ungroup_days = array();
                $monday_to_friday_group_display = 'style="display: block;"';
                $monday_to_friday_ungroup_display = 'style="display: none;"';

                $saturday_to_sunday_group_display = 'style="display: block;"';
                $saturday_to_sunday_ungroup_display = 'style="display: none;"';

                $formHead = "Neue Adresse hinzufügen";
            }

            $start = new DateTime('00:00');
            $end = new DateTime('24:00');
            $interval = new DateInterval('PT30M'); // 30 minutes

            $times = [];

            for ($time = clone $start; $time <= $end; $time->add($interval)) {
                $times[] = $time->format('H:i');
            }
            $usa_business_mf_status_html = "";
            foreach ($times as $t) {
                $usa_business_mf_status_html .= '<option value="' . $t . '" ' . (($usa_business_mf_status == $t) ? 'selected' : '') . ' >' . $t . '</option>';
            }

            $usa_business_mf_uw_status_html = "";
            foreach ($times as $t) {
                $usa_business_mf_uw_status_html .= '<option value="' . $t . '" ' . (($usa_business_mf_uw_status == $t) ? 'selected' : '') . ' >' . $t . '</option>';
            }

            $usa_business_ss_status_html = "";
            foreach ($times as $t) {
                $usa_business_ss_status_html .= '<option value="' . $t . '" ' . (($usa_business_ss_status == $t) ? 'selected' : '') . ' >' . $t . '</option>';
            }
            $usa_business_ss_uw_status_html = "";
            foreach ($times as $t) {
                $usa_business_ss_uw_status_html .= '<option value="' . $t . '" ' . (($usa_business_ss_uw_status == $t) ? 'selected' : '') . ' >' . $t . '</option>';
            }
            $german_days = array("Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag", "Sonntag");
            $monday_to_friday_ungroup_days_html = "";
            for ($i = 0; $i < 5; $i++) {
                $sbugd_24hour_open = ((!empty($shipping_business_ungroup_days)) ? $shipping_business_ungroup_days[$i][$i]['sbugd_24hour_open'] : 0);
                $sbugd_close_delivery = ((!empty($shipping_business_ungroup_days)) ? $shipping_business_ungroup_days[$i][$i]['sbugd_close_delivery'] : 0);
                $sbugd_24hour_open_check = ((!empty($shipping_business_ungroup_days) && $shipping_business_ungroup_days[$i][$i]['sbugd_24hour_open'] == 1) ? 'checked' : '');

                $usa_business_mf_status_ungroup_html = "<option value='Geöffnet' " . ((!empty($shipping_business_ungroup_days) && $shipping_business_ungroup_days[$i][$i]['sbugd_open'] == 'Geöffnet') ? 'selected' : '') . ">Geöffnet</option>";
                foreach ($times as $t) {
                    $usa_business_mf_status_ungroup_html .= '<option value="' . $t . '" ' . ((!empty($shipping_business_ungroup_days) && $shipping_business_ungroup_days[$i][$i]['sbugd_open'] == $t) ? 'selected' : '') . ' >' . $t . '</option>';
                }
                $usa_business_mf_uw_status_ungroup_html = "<option value='Geschlossen' " . ((!empty($shipping_business_ungroup_days) && $shipping_business_ungroup_days[$i][$i]['sbugd_close'] == 'Geschlossen') ? 'selected' : '') . ">Geschlossen</option>";
                foreach ($times as $t) {
                    $usa_business_mf_uw_status_ungroup_html .= '<option value="' . $t . '" ' . ((!empty($shipping_business_ungroup_days) && $shipping_business_ungroup_days[$i][$i]['sbugd_close'] == $t) ? 'selected' : '') . ' >' . $t . '</option>';
                }
                $monday_to_friday_ungroup_days_html .= '<div class="form_row">
                <input type="hidden" name="sbugd_day[]" id="sbugd_day" value="' . $german_days[$i] . '">
                <div class="form_left">
                    <div class="form_label">' . $german_days[$i] . '</div>
                    <div class="form_field">
                        <select class="gerenric_input" name="sbugd_open[]" id="sbugd_open">
                            
                            ' . $usa_business_mf_status_ungroup_html . '
                        </select>
                    </div>
                </div>
                <div class="form_right">
                    <div class="form_label">' . (($i == 0) ? "<a href='javascript: void(0);' id='group_weekdays' >Wochentage gruppieren</a>" : "&nbsp;") . '</div>
                    <div class="form_field">
                        <select class="gerenric_input" name="sbugd_close[]" id="sbugd_close">
                            ' . $usa_business_mf_uw_status_ungroup_html . '
                        </select>
                    </div>
                </div>
                <input type="hidden" name="sbugd_24hour_open[]" id="sbugd_24hour_open_' . $i . '" data-id="' . $i . '" value="' . $sbugd_24hour_open . '">
                <input type="hidden" name="sbugd_close_delivery[]" id="sbugd_close_delivery_' . $i . '" data-id="' . $i . '" value="' . $sbugd_close_delivery . '">
                <div class="form_field margin_10"><input type="checkbox" name="sbugd_24hour_open_check[]" class="sbugd_24hour_open_check" id="sbugd_24hour_open_check_' . $i . '" data-id="' . $i . '" ' . $sbugd_24hour_open_check . '> 24 Stunden geöffnet</div>
            </div>';
            }

            $saturday_to_sunday_ungroup_days_html = "";
            for ($i = 5; $i < 7; $i++) {
                $sbugd_24hour_open = ((!empty($shipping_business_ungroup_days)) ? $shipping_business_ungroup_days[$i][$i]['sbugd_24hour_open'] : 0);
                $sbugd_close_delivery = ((!empty($shipping_business_ungroup_days)) ? $shipping_business_ungroup_days[$i][$i]['sbugd_close_delivery'] : 0);
                $sbugd_24hour_open_check = ((!empty($shipping_business_ungroup_days) && $shipping_business_ungroup_days[$i][$i]['sbugd_24hour_open'] == 1) ? 'checked' : '');
                $sbugd_close_delivery_check = ((!empty($shipping_business_ungroup_days) && $shipping_business_ungroup_days[$i][$i]['sbugd_close_delivery'] == 1) ? 'checked' : '');

                $usa_business_mf_status_ungroup_html = "<option value='Geöffnet' " . ((!empty($shipping_business_ungroup_days) && $shipping_business_ungroup_days[$i][$i]['sbugd_open'] == 'Geöffnet') ? 'selected' : '') . ">Geöffnet</option>";
                foreach ($times as $t) {
                    $usa_business_mf_status_ungroup_html .= '<option value="' . $t . '" ' . ((!empty($shipping_business_ungroup_days) && $shipping_business_ungroup_days[$i][$i]['sbugd_open'] == $t) ? 'selected' : '') . ' >' . $t . '</option>';
                }
                $usa_business_mf_uw_status_ungroup_html = "<option value='Geschlossen' " . ((!empty($shipping_business_ungroup_days) && $shipping_business_ungroup_days[$i][$i]['sbugd_close'] == 'Geschlossen') ? 'selected' : '') . ">Geschlossen</option>";
                foreach ($times as $t) {
                    $usa_business_mf_uw_status_ungroup_html .= '<option value="' . $t . '" ' . ((!empty($shipping_business_ungroup_days) && $shipping_business_ungroup_days[$i][$i]['sbugd_close'] == $t) ? 'selected' : '') . ' >' . $t . '</option>';
                }
                $saturday_to_sunday_ungroup_days_html .= '<div class="form_row">
                <input type="hidden" name="sbugd_day[]" id="sbugd_day" value="' . $german_days[$i] . '">
                <div class="form_left">
                    <div class="form_label">' . $german_days[$i] . '</div>
                    <div class="form_field">
                        <select class="gerenric_input" name="sbugd_open[]" id="sbugd_open">
                            
                            ' . $usa_business_mf_status_ungroup_html . '
                        </select>
                    </div>
                </div>
                <div class="form_right">
                    <div class="form_label">' . (($i == 5) ? "<a href='javascript: void(0);' id='group_weekends' >Gruppe wochenenden</a>" : "&nbsp;") . '</div>
                    <div class="form_field">
                        <select class="gerenric_input" name="sbugd_close[]" id="sbugd_close">
                            ' . $usa_business_mf_uw_status_ungroup_html . '
                        </select>
                    </div>
                </div>
                <input type="hidden" name="sbugd_24hour_open[]" id="sbugd_24hour_open_' . $i . '" data-id="' . $i . '" value="' . $sbugd_24hour_open . '">
                <input type="hidden" name="sbugd_close_delivery[]" id="sbugd_close_delivery_' . $i . '" data-id="' . $i . '" value="' . $sbugd_close_delivery . '">
                <div class="form_field margin_10"><input type="checkbox" name="sbugd_24hour_open_check[]" class="sbugd_24hour_open_check" id="sbugd_24hour_open_check_' . $i . '" data-id="' . $i . '" ' . $sbugd_24hour_open_check . '> 24 Stunden geöffnet</div>
                <div class="form_field margin_10"><input type="checkbox" name="sbugd_close_delivery_check[]" class="sbugd_close_delivery_check" id="sbugd_close_delivery_check_' . $i . '" data-id="' . $i . '" ' . $sbugd_close_delivery_check . '> 24 Stunden geöffnet</div>
            </div>';
            }

            $delivery_instructions = "";
            $delivery_instructions = '
            <input type = "hidden" name = "usa_id" id = "usa_id" value = "' . $_REQUEST['usa_id'] . '">
            <input type = "hidden" name = "usa_delivery_instructions_tab_active" id = "usa_delivery_instructions_tab_active" value = "' . $usa_delivery_instructions_tab_active . '">
                                <div class="grnc_tabnav">
                                        <div class = "black">' . $short_detail . '</div>
										<ul class="grnc_tabnav_tabs">
											<li ' . (($usa_delivery_instructions_tab_active == 1) ? "class='active'" : "") . ' ><a href="#tab1" class="delivery_instructions_tab" data-id="1">Haus</a></li>
											<li ' . (($usa_delivery_instructions_tab_active == 2) ? "class='active'" : "") . ' ><a href="#tab2" class="delivery_instructions_tab" data-id="2">Wohnung</a></li>
											<li ' . (($usa_delivery_instructions_tab_active == 3) ? "class='active'" : "") . ' ><a href="#tab3" class="delivery_instructions_tab" data-id="3">Unternehmen</a></li>
											<li ' . (($usa_delivery_instructions_tab_active == 4) ? "class='active'" : "") . ' ><a href="#tab4" class="delivery_instructions_tab" data-id="4">Sonstiges</a></li>
										</ul>
										<p id="delivery_instructions_text">Einfamilienhaus oder Stadthaus</p>
									</div>
                                    <div class="grnc_tabnav_content ' . (($usa_delivery_instructions_tab_active == 1) ? "active" : "hide") . ' " id="tab1">
										<h4>Wo sollen wir Pakete ablegen, wenn sie nicht in Ihren Briefkasten passen? </h4>
										<ul>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_house_check" class="usa_house_check" id="usa_house_check" value="Terrasse" ' . (($usa_house_check == "Terrasse") ? 'checked' : '') . '></span> <span>Terrasse</span></div>
											</li>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_house_check" class="usa_house_check" id="usa_house_check" value="Garage" ' . (($usa_house_check == "Garage") ? 'checked' : '') . '></span> <span>Garage</span></div>
											</li>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_house_check" class="usa_house_check" id="usa_house_check" value="Vordertür" ' . (($usa_house_check == "Vordertür") ? 'checked' : '') . '></span> <span>Vordertür</span></div>
											</li>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_house_check" class="usa_house_check" id="usa_house_check" value="Garten" ' . (($usa_house_check == "Garten") ? 'checked' : '') . '></span> <span>Garten</span></div>
											</li>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_house_check" class="usa_house_check" id="usa_house_check" value="Schuppen" ' . (($usa_house_check == "Schuppen") ? 'checked' : '') . '></span> <span>Schuppen</span></div>
											</li>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_house_check" class="usa_house_check" id="usa_house_check" value="Bei einem Nachbarn" ' . (($usa_house_check == "Bei einem Nachbarn") ? 'checked' : '') . '></span> <span>Bei einem Nachbarn</span></div>
											</li>
                                            <li id="usa_apartment_security_code_fields" ' . $apartment_security_code_display . '>
												<div class="form_row">
													<div class="form_left">
														<div class="form_label">Name des Nachbarn</div>
														<div class="form_field"><input type="text" class="gerenric_input" name="usa_house_neighbor_name" id="usa_house_neighbor_name" value="' . $usa_house_neighbor_name . '" placeholder="Name des Nachbarn"></div>
													</div>
													<div class="form_right">
														<div class="form_label">Adresse des Nachbarn</div>
														<div class="form_field"><input type="text" class="gerenric_input" name="usa_house_neighbor_address" id="usa_house_neighbor_address" value="' . $usa_house_neighbor_address . '" placeholder="Adresse des Nachbarn/der Nachbarin"></div>
													</div>
												</div>
											</li>
											<script>
												$(".usa_house_check").click(function() {
                                                    var selectedValue = $(".usa_house_check:checked").val();
                                                    if (selectedValue == "Bei einem Nachbarn") {
														//console.log("Selected value:", selectedValue);
														$("#usa_apartment_security_code_fields").show();
													} else {
														//console.log("No option selected");
														$("#usa_apartment_security_code_fields").hide();
													}
												});
											</script>
											<li>
												<div class="radio_button"><span><input type="radio" name="usa_house_check" class="usa_house_check" id="usa_house_check" value="Keine Präferenz" ' . (($usa_house_check == "Keine Präferenz") ? 'checked' : '') . '></span> <span>Keine Präferenz</span></div>
											</li>
										</ul>
									</div>
									<div class="grnc_tabnav_content ' . (($usa_delivery_instructions_tab_active == 2) ? "active" : "hide") . ' " id="tab2">
										<h4>Benötigen wir einen Sicherheitscode oder einen Schlüssel um das Gebäude zu betreten?</h4>
										<ul>
											<li>
												<div class="form_label">Sicherheitscode</div>
												<div class="form_field"><input type="text" class="gerenric_input" name="usa_apartment_security_code" id="usa_apartment_security_code" value="' . $usa_apartment_security_code . '" placeholder="Sicherheitscode für die Tür"></div>
											</li>
											<li>
												<div class="form_label">Gegensprechanlage</div>
												<div class="form_field"><input type="text" class="gerenric_input" name="usa_appartment_call_box" id="usa_appartment_call_box" value="' . $usa_appartment_call_box . '" placeholder="Nummer oder Name der Gegensprechanlage"></div>
											</li>
											<li>
												<div class="form_field"><input type="checkbox" name="usa_appartment_check" id="usa_appartment_check" ' . (($usa_appartment_check > 0) ? 'checked' : '') . '> Schlüssel oder Token benötigt</div>
											</li>
										</ul>
									</div>
									<div class="grnc_tabnav_content ' . (($usa_delivery_instructions_tab_active == 3) ? "active" : "hide") . '" id="tab3">
										<h4>Wann können an diese Adresse Lieferungen zugestellt werden? </h4>
                                        <input type="hidden" name="usa_business_mf_type" id="usa_business_mf_type" value="' . $usa_business_mf_type . '">
										<input type="hidden" name="usa_business_ss_type" id="usa_business_ss_type" value="' . $usa_business_ss_type . '">
										<ul>
											<li id="monday_to_friday_group_days" ' . $monday_to_friday_group_display . ' >
												<div class="form_row">
													<div class="form_left">
														<div class="form_label">Montag - Freitag</div>
														<div class="form_field">
															<select class="gerenric_input" name="usa_business_mf_status" id="usa_business_mf_status">
																<option value="Geöffnet" ' . (($usa_business_mf_status == "Geöffnet") ? 'selected' : '') . '>Geöffnet</option>
                                                                ' . $usa_business_mf_status_html . '
															</select>
														</div>
													</div>
													<div class="form_right">
														<div class="form_label"><a href="javascript: void(0);" id="ungroup_weekdays">Gruppierung aufheben</a></div>
														<div class="form_field">
															<select class="gerenric_input" name="usa_business_mf_uw_status" id="usa_business_mf_uw_status">
																<option value="Geschlossen" ' . (($usa_business_mf_uw_status == "Geschlossen") ? 'selected' : '') . '>Geschlossen</option>
																' . $usa_business_mf_uw_status_html . '
															</select>
														</div>
													</div>
												</div>
                                                <div class="form_field margin_10"><input type="checkbox" name="usa_business_mf_24h_check" id="usa_business_mf_24h_check" ' . (($usa_business_mf_24h_check > 0) ? 'checked' : '') . '> 24 Stunden geöffnet</div>
											</li>
                                            <li id="monday_to_friday_ungroup_days" ' . $monday_to_friday_ungroup_display . '>
												' . $monday_to_friday_ungroup_days_html . '
											</li>
                                            <script>
												$("#ungroup_weekdays").on("click", function() {
													$("#monday_to_friday_group_days").hide();
													$("#monday_to_friday_ungroup_days").show();
													$("#usa_business_mf_type").val(1);
												});
												$("#group_weekdays").on("click", function() {
													$("#monday_to_friday_ungroup_days").hide();
													$("#monday_to_friday_group_days").show();
													$("#usa_business_mf_type").val(0);
												});
											</script>
											<li id="saturday_to_sunday_group_days" ' . $saturday_to_sunday_group_display . ' >
												<div class="form_row">
													<div class="form_left">
														<div class="form_label">Samstag - Sonntag</div>
														<div class="form_field">
															<select class="gerenric_input" name="usa_business_ss_status" id="usa_business_ss_status">
																<option value="Geöffnet" ' . (($usa_business_ss_status == "Geöffnet") ? 'selected' : '') . '>Geöffnet</option>
                                                                ' . $usa_business_ss_status_html . '
															</select>
														</div>
													</div>
													<div class="form_right">
														<div class="form_label"><a href="javascript: void(0);" id="ungroup_weekends">Gruppierung aufheben</a></div>
														<div class="form_field">
															<select class="gerenric_input" name="usa_business_ss_uw_status" id="usa_business_ss_uw_status">
																<option value="Geschlossen" ' . (($usa_business_ss_uw_status == "Geschlossen") ? 'selected' : '') . '>Geschlossen</option>
																' . $usa_business_ss_uw_status_html . '
															</select>
														</div>
													</div>
												</div>
                                                <div class="form_field margin_10"><input type="checkbox" name="usa_business_24h_check" id="usa_business_24h_check" ' . (($usa_business_24h_check > 0) ? 'checked' : '') . '> 24 Stunden geöffnet</div>
                                                <div class="form_field"><input type="checkbox" name="usa_business_close_check" id="usa_business_close_check" ' . (($usa_business_close_check > 0) ? 'checked' : '') . '> Für Lieferungen geschlossen</div>
											</li>
                                            <li id="saturday_to_sunday_ungroup_days" ' . $saturday_to_sunday_ungroup_display . '>
												' . $saturday_to_sunday_ungroup_days_html . '
											</li>
                                            <script>
												$("#ungroup_weekends").on("click", function() {
													$("#saturday_to_sunday_group_days").hide();
													$("#saturday_to_sunday_ungroup_days").show();
													$("#usa_business_ss_type").val(1);
												});
												$("#group_weekends").on("click", function() {
													$("#saturday_to_sunday_ungroup_days").hide();
													$("#saturday_to_sunday_group_days").show();
													$("#usa_business_ss_type").val(0);
												});
												$(".sbugd_24hour_open_check").on("click", function() {
													//console.log("sbugd_24hour_open_check", $(this).is(":checked"));
													if ($(this).is(":checked") == true) {
														$("#sbugd_24hour_open_" + $(this).attr("data-id")).val(1);
													} else {
														$("#sbugd_24hour_open_" + $(this).attr("data-id")).val(0);
													}
												});
												$(".sbugd_close_delivery_check").on("click", function() {
													//console.log("sbugd_close_delivery_check", $(this).is(":checked"));
													if ($(this).is(":checked") == true) {
														$("#sbugd_close_delivery_" + $(this).attr("data-id")).val(1);
													} else {
														$("#sbugd_close_delivery_" + $(this).attr("data-id")).val(0);
													}
												});
											</script>
										</ul>
									</div>
									<div class="grnc_tabnav_content ' . (($usa_delivery_instructions_tab_active == 4) ? "active" : "hide") . '" id="tab4">
										<h4>Benötigen wir zusätzliche Anweisungen, um an diese Adresse zu liefern?</h4>
										<ul>
											<li>
												<div class="form_label">Zustellungsanweisungen</div>
												<div class="form_field">
													<textarea class="gerenric_input gerenric_textarea" name="cu_message" id="cu_message" placeholder="Geben Sie Details wie Gebäudebeschreibung, einen nahe gelegenen Orientierungspunkt oder andere Navigationsanweisungen an.">' . $usa_other_check . '</textarea>
												</div>
											</li>
										</ul>
									</div>
                                    <script>
                                    $(".delivery_instructions_tab").on("click", function(){
                                        $("#usa_delivery_instructions_tab_active").val($(this).attr("data-id"));
                                    });
                                    </script>
            ';
            $retValue = array("status" => "1", "message" => "Record found", "form_popup_heading_txt" => $formHead, "delivery_instructions" => $delivery_instructions);
            //$retValue = array("status" => "1", "message" => "Record found", "count" => $count, "last_record" => $last_record,  "gerenric_product_inner_page" => ($_REQUEST['start'] + 1), "gerenric_product_inner" => $gerenric_product_inner);
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;
        case 'products_quantity':
            $quantity_lenght = 0;
            $Query = "SELECT * FROM products_quantity WHERE supplier_id = '" . dbStr(trim($supplier_id)) . "'";
            //print($Query);
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            $ci_qty_type = 0;
            if (mysqli_num_rows($rs) > 0) {
                $row = mysqli_fetch_object($rs);
                $pq_quantity = $row->pq_quantity;
                $pq_upcomming_quantity = $row->pq_upcomming_quantity;
                $pq_status = $row->pq_status;
                if ($pq_status == 'true') {
                    $ci_qty_type = 1;
                }
                if (($pq_quantity == 0 || $pq_quantity < 0) && $pq_status == 'true') {
                    $quantity_lenght = $pq_upcomming_quantity;
                    print('<div class="product_order_title"> ' . $pq_upcomming_quantity . ' Stück bestellt</div>');
                } elseif ($pq_quantity > 0 && $pq_status == 'false') {
                    $quantity_lenght = $pq_quantity;
                    print('<div class="product_order_title green"> ' . $pq_quantity . ' Stück sofort verfügbar</div>');
                } elseif (($pq_quantity == 0 || $pq_quantity < 0) && $pq_status == 'false') {
                    print('<div class="product_order_title red">Auf Anfrage</div>');
                }
            } else {
                if ($pro_type > 0) {
                    $quantity_lenght = 1;
                } else {
                    print('<div class="product_order_title red">Auf Anfrage</div>');
                }
            }
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'gratis_products_inner':
            //print_r($_REQUEST);
            $retValue = array();
            $fp_price = 0;
            $cart_amount = 0;
            $cart_amount_total = 0;
            $ci_total_free = 0;
            if (isset($_SESSION['cart_id'])) {
                $fp_price = returnSum("pbp_price_amount * ci_qty", "cart_items", "cart_id", $_SESSION['cart_id'], " AND ci_type = '2'");
                $cart_amount = $cart_amount_total = returnName("cart_amount", "cart", "cart_id", $_SESSION['cart_id']);
                $ci_total_free = returnSum("ci_total", "cart_items", "cart_id", $_SESSION['cart_id'], " AND ci_discount_value > 0");
                if ($ci_total_free > 0) {
                    $cart_amount = $cart_amount - $ci_total_free;
                }
            }
            $cart_amount = $cart_amount - $fp_price;
            $fpc_id = $_REQUEST['fpc_id'];
            $whereclause = "";
            if (!empty($fpc_id)) {
                $whereclause .= " AND fpc_id IN (" . $fpc_id . ")";
            }
            $gratis_products_inner = "";
            $Query1 = "SELECT fp_id, fpc_id, fp_file, fp_price, fp_title_de AS fp_title FROM `free_product` WHERE fp_status = '1' AND fp_price > 0 " . $whereclause . " ORDER BY fpc_id ASC";
            //print($Query1);die();
            $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
            if (mysqli_num_rows($rs1) > 0) {
                while ($rw1 = mysqli_fetch_object($rs1)) {
                    $image_path = $GLOBALS['siteURL'] . "files/no_img_1.jpg";
                    if (!empty($rw1->fp_file)) {
                        $image_path = $GLOBALS['siteURL'] . "files/free_product/" . $rw1->fp_file;
                    }
                    if ($cart_amount >= $rw1->fp_price) {
                        $max_quentity = floor($cart_amount / $rw1->fp_price);
                        mysqli_query($GLOBALS['conn'], "UPDATE cart_items SET ci_max_quentity = '" . $max_quentity . "' WHERE fp_id = '" . $rw1->fp_id . "' ") or die(mysqli_error($GLOBALS['conn']));
                        $ci_qty = returnName("ci_qty", "cart_items", "fp_id", $rw1->fp_id, " AND cart_id = '" . $_SESSION['cart_id'] . "' AND ci_type = '2'");
                        $quantity_text = "";
                        if ($ci_qty > 0) {
                            $quantity_text = '<div class="pd-add-quantity-text">' . $ci_qty . '</div>';
                        }
                        $free_add_to_cart = '
                                <div class="gratis-pd-add">
									<div class="gratis-pd-add-value">
										<div class="pd-add-plus">
											<div class="quantity-container" data-max="' . $max_quentity . '">
												<button class="gratis_minus">-</button>
												<input type="text" class="gratis_quantity" id = "free_quantity_' . $rw1->fp_id . '" value="1" readonly>
												<button class="gratis_plus">+</button>
											</div>
										</div>
										<div class="pd-add-button">
											<div class="pd-add-button-inner add_to_cart_free_product" data-id = "' . $rw1->fp_id . '" data-max-quentity="' . $max_quentity . '">In den Einkaufswagen</div>
                                            ' . $quantity_text . '
										</div>
									</div>
									<div class="pd-max-text">Max: ' . $max_quentity . '</div>
								</div>
                        ';
                    } else {
                        $free_add_to_cart = '<div class="gratis-prise-green">Es fehlen noch ' . price_format($rw1->fp_price - $cart_amount) . ' €</div>';
                    }
                    $gratis_products_inner .= '
                    <div class="gratis-card">
							<div class="gratis-box">
								<div class="gratis-prise"><span>Pro</span>' . price_format($rw1->fp_price) . ' €</div>
								<div class="gratis-image"><img loading="lazy" src="' . $image_path . '" alt="' . $rw1->fp_title . '"></div>
								<div class="gratis-title">' . $rw1->fp_title . '</div>
								' . $free_add_to_cart . '
							</div>
						</div>
                    ';
                }

                $retValue = array("status" => "1", "message" => "Record found", "gratis_products_inner" => $gratis_products_inner, "cart_amount_total" => price_format($cart_amount_total), "ci_total_free" => (($ci_total_free > 0) ? "-" : "") . price_format($ci_total_free), "cart_remaning_amount" => price_format($cart_amount_total - $ci_total_free));
            } else {
                $retValue = array("status" => "0", "message" => "Record not found!", "gratis_products_inner" => "<p class = 'txt_align_center'>Record not found!</p>");
            }
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'add_to_cart_free_product':
            //print_r($_REQUEST);die();
            $retValue = array();
            $cart_id = $_SESSION['cart_id'];
            $fp_id = $_REQUEST['fp_id'];
            $ci_qty = $_REQUEST['free_quantity'];
            //$ci_max_quentity = $_REQUEST['ci_max_quentity'] - $ci_qty;
            $pbp_price_amount = returnName("fp_price", "free_product", "fp_id", $fp_id);
            $Query = "SELECT * FROM `cart_items` WHERE cart_id = '" . $cart_id . "' AND fp_id = '" . $fp_id . "'";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                $row = mysqli_fetch_object($rs);

                mysqli_query($GLOBALS['conn'], "UPDATE cart_items SET ci_qty = ci_qty + '" . $ci_qty . "' WHERE ci_id = '" . $row->ci_id . "' ") or die(mysqli_error($GLOBALS['conn']));
                $_SESSION['header_quantity'] = $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], "SELECT * FROM `cart_items` WHERE `cart_id` = '" . $cart_id . "'"));
                $retValue = array("status" => "1", "message" => "The recorded quantity has been updated to the bucket successfully", "count" => "$count");
            } else {
                $ci_id = getMaximum("cart_items", "ci_id");
                mysqli_query($GLOBALS['conn'], "INSERT INTO cart_items (ci_id, ci_type, cart_id, fp_id, pbp_price_amount, ci_qty) VALUES ('" . $ci_id . "', '2', '" . $cart_id . "', '" . $fp_id . "', '" . $pbp_price_amount . "', '" . $ci_qty . "')") or die(mysqli_error($GLOBALS['conn']));
                $_SESSION['header_quantity'] = $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], "SELECT * FROM cart_items WHERE cart_id = '" . $cart_id . "'"));
                $retValue = array("status" => "1", "message" => "The record has been added to the bucket successfully", "count" => "$count");
            }
            ci_max_quentity();
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;
    }
}
