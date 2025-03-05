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
                $where .= " AND ( pro.pro_description_short LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' OR pro.supplier_id LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' OR pro.pro_manufacture_aid LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%'  OR pro.pro_ean LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' OR pro.supplier_id IN (SELECT pf.supplier_id FROM products_feature AS pf WHERE pf.pf_forder = '6' AND pf.pf_fvalue LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' ) )";
            }
            $Query = "SELECT pro.pro_id, pro.supplier_id, pro.pro_description_short FROM products AS pro " . $where . " ORDER BY pro.pro_id  LIMIT 0,10";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            while ($row = mysqli_fetch_object($rs)) {
                $json[] = array(
                    'pro_id' => strip_tags(html_entity_decode($row->pro_id, ENT_QUOTES, 'UTF-8')),
                    'supplier_id' => strip_tags(html_entity_decode($row->supplier_id, ENT_QUOTES, 'UTF-8')),
                    'value' => strip_tags(html_entity_decode($row->pro_description_short, ENT_QUOTES, 'UTF-8'))
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
            $supplier_id = $_REQUEST['supplier_id'];
            $ci_qty = $_REQUEST['ci_qty'];
            $get_pro_price = get_pro_price($pro_id, $supplier_id, $ci_qty);
            $pbp_id = $get_pro_price['pbp_id'];
            $pbp_price_amount = $get_pro_price['ci_amount'];
            $ci_amount = $get_pro_price['ci_amount'];
            $ci_discount_type = $_REQUEST['ci_discount_type'];
            $ci_discount_value = $_REQUEST['ci_discount_value'];
            $ci_discounted_amount = 0;
            $ci_discount = 0;
            //print($ci_amount);die();
            if ($ci_discount_value > 0) {
                $ci_discounted_amount_gross = 0;
                $ci_amount = discounted_price($ci_discount_type, $ci_amount, $ci_discount_value);
                $ci_discounted_amount = $pbp_price_amount - $ci_amount;

                $ci_discounted_amount_gross = $ci_discounted_amount * $ci_qty;
                $ci_discount = $ci_discounted_amount_gross + ($ci_discounted_amount_gross * config_gst);
                //print($ci_amount);die();
            }
            $ci_gross_total = $ci_amount * $ci_qty;
            $ci_gst = $ci_gross_total * config_gst;
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
                        $get_pro_price = get_pro_price($pro_id, $supplier_id, $ci_qty + $cart_quantity);
                        //print_r($get_pro_price);
                        $pbp_id = $get_pro_price['pbp_id'];
                        $pbp_price_amount = $get_pro_price['ci_amount'];
                        $ci_amount = $get_pro_price['ci_amount'];
                        $ci_discount_type = $row->ci_discount_type;
                        $ci_discount_value = $row->ci_discount_value;
                        $ci_discounted_amount = 0;
                        $ci_discount = 0;
                        if ($ci_discount_value > 0) {
                            $ci_discounted_amount_gross = 0;
                            $ci_amount = discounted_price($ci_discount_type, $ci_amount, $ci_discount_value);
                            $ci_discounted_amount = $pbp_price_amount - $ci_amount;

                            $ci_discounted_amount_gross = $ci_discounted_amount * ($ci_qty + $cart_quantity);
                            $ci_discount = $ci_discounted_amount_gross + ($ci_discounted_amount_gross * config_gst);
                            //print($ci_amount);die();
                        }
                        $ci_gross_total = $ci_amount * ($ci_qty + $cart_quantity);
                        $ci_gst = $ci_gross_total * config_gst;
                        $ci_total = $ci_gross_total + $ci_gst;

                        //$updated_cart_item = mysqli_query($GLOBALS['conn'], "UPDATE cart_items SET pbp_id = '".$pbp_id."', ci_amount = '".$ci_amount."', ci_qty = ci_qty + '$ci_qty',  ci_gross_total = ci_gross_total + '$ci_gross_total' , ci_gst = ci_gst + '$ci_gst', ci_discount = ci_discount + '$ci_discount', ci_total = ci_total + '$ci_total' WHERE ci_id = '" . $row->ci_id . "'") or die(mysqli_error($GLOBALS['conn']));
                        $updated_cart_item = mysqli_query($GLOBALS['conn'], "UPDATE cart_items SET pbp_id = '" . $pbp_id . "', pbp_price_amount = '" . $pbp_price_amount . "', ci_amount = '" . $ci_amount . "', ci_discounted_amount = '" . $ci_discounted_amount . "', ci_qty = ci_qty + '$ci_qty',  ci_gross_total = '$ci_gross_total' , ci_gst = '$ci_gst',  ci_discount =  '$ci_discount', ci_total =  '$ci_total' WHERE ci_id = '" . $row->ci_id . "'") or die(mysqli_error($GLOBALS['conn']));
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
                        if(isset($_SESSION["UID"]) && $_SESSION["UID"] > 0){
                            $ci_discounted_price_see = 1;
                        }
                        $insert_cart_item = mysqli_query($GLOBALS['conn'], "INSERT INTO cart_items ( ci_id, cart_id, pro_id, supplier_id, pbp_id, pbp_price_amount, ci_amount, ci_discounted_amount, ci_qty, ci_gross_total, ci_gst, ci_discount_type, ci_discount_value, ci_discount, ci_total, ci_discounted_price_see) VALUES ('" . $ci_id . "','" . dbStr($cart_id) . "', '" . dbStr($pro_id) . "','" . dbStr($supplier_id) . "', '" . dbStr(trim($pbp_id)) . "', '" . dbStr($pbp_price_amount) . "', '" . dbStr($ci_amount) . "', '" . dbStr($ci_discounted_amount) . "', '" . dbStr($ci_qty) . "', '" . dbStr(trim($ci_gross_total)) . "', '" . dbStr(trim($ci_gst)) . "', '" . dbStr(trim($ci_discount_type)) . "', '" . dbStr(trim($ci_discount_value)) . "', '" . dbStr($ci_discount) . "', '" . dbStr($ci_total) . "', '".$ci_discounted_price_see."')") or die(mysqli_error($GLOBALS['conn']));
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

                $insert_cart_item = mysqli_query($GLOBALS['conn'], "INSERT INTO cart_items ( ci_id, cart_id, pro_id, supplier_id, pbp_id, pbp_price_amount, ci_amount, ci_discounted_amount, ci_qty, ci_gross_total, ci_gst, ci_discount_type, ci_discount_value, ci_discount, ci_total) VALUES ('" . $ci_id . "','" . dbStr($cart_id) . "', '" . dbStr($pro_id) . "','" . dbStr($supplier_id) . "', '" . dbStr(trim($pbp_id)) . "', '" . dbStr($pbp_price_amount) . "', '" . dbStr($ci_amount) . "', '" . dbStr($ci_discounted_amount) . "', '" . dbStr($ci_qty) . "', '" . dbStr(trim($ci_gross_total)) . "', '" . dbStr(trim($ci_gst)) . "', '" . dbStr(trim($ci_discount_type)) . "', '" . dbStr(trim($ci_discount_value)) . "', '" . dbStr($ci_discount) . "', '" . dbStr($ci_total) . "')") or die(mysqli_error($GLOBALS['conn']));
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
                $Query = "SELECT ci.*, pg.pg_mime_source_url FROM cart_items AS ci LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = ci.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' WHERE ci.cart_id = '" . $_SESSION['cart_id'] . "' ORDER BY ci.ci_id ASC";
                //print($Query);
                $rs = mysqli_query($GLOBALS['conn'], $Query);
                if (mysqli_num_rows($rs) > 0) {
                    $_SESSION['header_quantity'] = $count = TotalRecords("ci_id", "cart_items", "WHERE cart_id=" . $_SESSION['cart_id']);
                    while ($row = mysqli_fetch_object($rs)) {

                        $pq_quantity = 0;
                        $Query1 = "SELECT * FROM products_quantity WHERE supplier_id = '" . dbStr(trim($row->supplier_id)) . "'";
                        $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
                        if (mysqli_num_rows($rs1) > 0) {
                            $row1 = mysqli_fetch_object($rs1);
                            $pq_quantity = $row1->pq_quantity;
                            $pq_upcomming_quantity = $row1->pq_upcomming_quantity;
                            $pq_status = $row1->pq_status;
                            if ($pq_quantity == 0 && $pq_status == 'true') {
                                $pq_quantity = $pq_upcomming_quantity - $row->ci_qty;
                            } elseif ($pq_quantity > 0 && $pq_status == 'false') {
                                $pq_quantity = $pq_quantity + $pq_upcomming_quantity - $row->ci_qty;
                            }
                        }

                        $cart_amount = $cart_amount + $row->ci_total;
                        $gst = $row->ci_amount * config_gst;
                        $gst_orignal = $row->pbp_price_amount * config_gst;
                        $display_one = "";
                        $display_two = "";
                        if (isset($_SESSION['utype_id']) && $_SESSION['utype_id'] == 4) {
                            $display_one = "style = 'display: block;'";
                            $display_two = "style = 'display: none;'";
                        }
                        $cart_price_data = "";
                        if ($row->ci_discount_value > 0) {
                            $cart_price_data .= '<div class="side_cart_pd_prise price_without_tex" ' . $display_one . ' > <del class="orignal_price"> ' . str_replace(".", ",", $row->pbp_price_amount) . ' €</del> <br> <span class="pd_prise_discount"> ' . str_replace(".", ",", $row->ci_amount) . "€ " . $row->ci_discount_value . (($row->ci_discount_type > 0) ? "€" : "%") . ' </span></div>';
                            $cart_price_data .= '<div class="side_cart_pd_prise pbp_price_with_tex" ' . $display_two . ' > <del class="orignal_price"> ' . number_format($row->pbp_price_amount + $gst_orignal, "2", ",", "") . ' €</del> <br> <span class="pd_prise_discount"> ' . number_format($row->ci_amount + $gst, "2", ",", "") . "€ " . $row->ci_discount_value . (($row->ci_discount_type > 0) ? '€' : '%') . ' </span> </div>';
                        } else {
                            $cart_price_data .= '<div class="side_cart_pd_prise price_without_tex" ' . $display_one . ' >' . number_format($row->ci_amount, "2", ",", "") . ' €</div>';
                            $cart_price_data .= '<div class="side_cart_pd_prise pbp_price_with_tex" ' . $display_two . ' >' . number_format($row->ci_amount + $gst, "2", ",", "") . ' €</div>';
                        }
                        $show_card_body .= '
                                <div class="side_cart_pd_row">
                                    <div class="side_cart_pd_image"><a href="product_detail.php?supplier_id=' . $row->supplier_id . '"><img src="' . get_image_link(160, $row->pg_mime_source_url) . '" alt=""></a></div>
                                    ' . $cart_price_data . '
                                    <div class="side_cart_pd_qty">
                                        <div class="side_pd_qty">
                                            <input type="number" class="qlt_number" id = "ci_qty_' . $row->ci_id . '" data-id = "' . $row->ci_id . '" value="' . $row->ci_qty . '" onkeyup="if(this.value === \'\' || parseFloat(this.value) <= 0) { this.value = 0; } else if (parseFloat(this.value) > ' . ($pq_quantity + $row->ci_qty) . ') { this.value = ' . ($pq_quantity + $row->ci_qty) . '; return false; }" min="1" max="' . $pq_quantity . '">
                                        </div>

                                        <div class="side_pd_delete"><a  class = "item_deleted" data-id = "' . $row->ci_id . '" href="javascript:void(0)"><i class="fa fa-trash"></i></a></div>
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
                $retValue = array("status" => "0", "message" => "Record not found!");
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
                        $ci_discount_type = $row->ci_discount_type;
                        $ci_discount_value = $row->ci_discount_value;
                        $ci_discounted_amount = 0;
                        $ci_discount = 0;
                        if ($ci_discount_value > 0) {
                            $ci_discounted_amount_gross = 0;
                            $ci_amount = discounted_price($ci_discount_type, $ci_amount, $ci_discount_value);
                            $ci_discounted_amount = $pbp_price_amount - $ci_amount;

                            $ci_discounted_amount_gross = $ci_discounted_amount * ($_REQUEST['ci_qty']);
                            $ci_discount = $ci_discounted_amount_gross + ($ci_discounted_amount_gross * config_gst);
                        }
                        $ci_gross_total = $ci_amount * ($_REQUEST['ci_qty']);
                        $ci_gst = $ci_gross_total * config_gst;
                        $ci_total = $ci_gross_total + $ci_gst;

                        $updated_cart_item = mysqli_query($GLOBALS['conn'], "UPDATE cart_items SET pbp_id = '" . $pbp_id . "', pbp_price_amount = '" . $pbp_price_amount . "', ci_amount = '" . $ci_amount . "', ci_discounted_amount = '" . $ci_discounted_amount . "', ci_qty = '" . $_REQUEST['ci_qty'] . "',  ci_gross_total =  '$ci_gross_total' , ci_gst =  '$ci_gst', ci_discount =  '$ci_discount', ci_total =  '$ci_total' WHERE ci_id = '" . $row->ci_id . "'") or die(mysqli_error($GLOBALS['conn']));
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

                $count = TotalRecords("ci_id", "cart_items", "WHERE cart_id=" . $_SESSION['cart_id']);
                if ($count == 0) {
                    mysqli_query($GLOBALS['conn'], "UPDATE cart SET  cart_gross_total= '0', cart_gst= '0', cart_discount= '0', cart_amount= '0' WHERE cart_id='" . $_SESSION['cart_id'] . "'") or die(mysqli_error($GLOBALS['conn']));
                } else {
                    mysqli_query($GLOBALS['conn'], "UPDATE cart SET cart_gross_total=(SELECT SUM(ci_gross_total) FROM cart_items WHERE cart_id= '" . $_SESSION['cart_id'] . "'), cart_gst=(SELECT SUM(ci_gst) FROM cart_items WHERE cart_id= '" . $_SESSION['cart_id'] . "'), cart_discount=(SELECT SUM(ci_discount) FROM cart_items WHERE cart_id= '" . $_SESSION['cart_id'] . "'), cart_amount=(SELECT SUM(ci_total) FROM cart_items WHERE cart_id= '" . $_SESSION['cart_id'] . "') WHERE cart_id=" . $_SESSION['cart_id']) or die(mysqli_error($GLOBALS['conn']));
                }

                $retValue = array("status" => "1", "message" => "Record deleted successfully!", "count" => $count);
            } else {
                $count = TotalRecords("ci_id", "cart_items", "WHERE cart_id=" . $_SESSION['cart_id']);
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
                $retValue = array("status" => "1", "message" => "Record already exists!");
            } else {
                $wl_id = getMaximum("wishlist", "wl_id");
                mysqli_query($GLOBALS['conn'], "INSERT INTO wishlist (wl_id, user_id, sl_id, supplier_id) VALUES ('" . $wl_id . "', '" . $_SESSION['UID'] . "', '" . $_REQUEST['sl_id'] . "', '" . $_REQUEST['supplier_id'] . "')") or die(mysqli_error($GLOBALS['conn']));
                $retValue = array("status" => "1", "message" => "Add into list");
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
            $category_show .= '<ul class="category_show '.(($TotalRecCount > 5) ? 'category_show_height' : '').'" id="list_checkbox_hide_1">';
                if (mysqli_num_rows($rs) > 0) {
                    do {
                        $category_show .= '<li>
                            <label class="gerenric_checkbox">
                                '.$row->cat_title . " (" . $row->total_count . ")".'
                                <input type="checkbox" name="search_group_id[]" class="search_group_id" id="search_group_id" value="'.$row->group_id.'" '.((in_array($row->group_id, $search_group_id_check)) ? 'checked' : '').'>
                                <span class="checkmark"></span>
                            </label>
                        </li>';
                } while ($row = mysqli_fetch_object($rs));
                }
            $category_show .= '</ul>';
            if ($TotalRecCount > 5) {
                $category_show .= '<div class="show-more" data-id="1">(Show More)</div>';
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
            $brand_show = '<h3>Brands</h3>';
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
                $brand_show .= '<div class="show-more" data-id="2">(Show More)</div>';
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
                        do { $index = uniqid();
                            $feature_show .= '<li>
                                        <label class="gerenric_checkbox">
                                            ' . $row2->pf_fvalue . " (" . $row2->total_count . ")" . '
                                            <input type="hidden" name="search_pf_fname[' . $index . ']" value="' . $row2->pf_fname . '">
                                            <input type="checkbox" name="search_pf_fvalue[' . $index . ']" id="search_pf_fvalue" class="search_pf_fvalue" value="' . $row2->pf_fvalue . '" ' . ((in_array($row2->pf_fvalue, $search_pf_fvalue_check) && in_array($row1->pf_fname, $search_pf_fname_check) ) ? 'checked' : '') . '>
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>';
                            //print($feature_show);die();
                        } while ($row2 = mysqli_fetch_object($rs2));
                    }
                    $feature_show .= '</ul>';
                    if ($TotalRecCount > 5) {
                        $feature_show .= '<div class="show-more" data-id="' . $count . '">(Show More)</div>';
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
                                $(this).text("(Show Less)");
                            } else {
                                $(this).text("(Show More)");
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
    }
}
