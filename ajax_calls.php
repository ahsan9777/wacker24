<?php
include("includes/php_includes_top.php");
if(isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'switch_click':
            echo $_SESSION['utype_id'] = $_REQUEST['utype_id']; 
            break;

        case 'ci_qty':
            $retValue = array();
            $Query = "SELECT pbp.pbp_id, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pbp.pbp_price_amount AS pbp_price_without_tax FROM products_bundle_price AS pbp WHERE pbp.pro_id = '".dbStr(trim($_REQUEST['pro_id']))."' AND pbp.supplier_id = '".dbStr(trim($_REQUEST['supplier_id']))."' AND pbp.pbp_lower_bound BETWEEN 0 AND ".dbStr(trim($_REQUEST['ci_qty']))."";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if(mysqli_num_rows($rs) > 0){
                $retValue = array("status" => "1", "message" => "Get quantity data");
                while($rw = mysqli_fetch_object($rs)){
                    $ci_amount = $rw->pbp_price_amount;
                    if(isset($_SESSION['utype_id']) && $_SESSION['utype_id'] == 4){
                        $ci_amount = $rw->pbp_price_without_tax;
                    }
                    $retValue['data'] = array(
                        "pbp_id" => strval($rw->pbp_id),
                        "ci_amount" => strval($ci_amount)
                    );
                }
            } else {
                $retValue = array("status" => "0", "message" => "Record not found!");
            }
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'add_to_card':
            $retValue = array();
            $count = 0;
            $pro_id = $_REQUEST['pro_id'];
            $ps_id = $_REQUEST['ps_id'];
            $ps_size = $_REQUEST['ps_size'];
            $ps_purchase_price = $_REQUEST['ps_purchase_price'];
            $ci_amount = $_REQUEST['ps_price'];
            $ci_qty = $_REQUEST['pro_quantity'];
            $ci_weight = $ps_size * $ci_qty;
            $ci_gross_total = $ci_amount * $ci_qty;
            //$ci_gst = ($ci_gross_total * config_gst) / 100;
            $ci_gst = $ci_gross_total;
            $ci_discount = $_REQUEST['ps_discount_value'];
            $ci_discount = $ci_discount * $ci_qty;
            $ci_total = ($ci_gross_total + $ci_gst) - $ci_discount;
            date_default_timezone_set('Asia/Karachi');
            $cart_datetime = date("Y-m-d H:i:s");
            $sess_idd = session_id();
            if(isset($_SESSION['sess_id'])) {

                if ($_SESSION['sess_id'] == $sess_idd) {
                    $ci_id = $_SESSION['ci_id'];
                    $ci_id = getMaximum("cart_items", "ci_id");
                    $cart_id = $_SESSION['cart_id'];
                    $Query = "SELECT * FROM cart_items WHERE cart_id = '".$cart_id."' AND ps_id = '".$ps_id."' AND pro_id = '".$pro_id."'";
                    $rs = mysqli_query($GLOBALS['conn'], $Query);
                    if(mysqli_num_rows($rs) > 0){
                        $row = mysqli_fetch_object($rs);
                        $updated_cart_item = mysqli_query($GLOBALS['conn'],"UPDATE cart_items SET ci_qty = ci_qty + '$ci_qty', ci_weight = ci_weight + '$ci_weight', ci_gross_total = ci_gross_total + '$ci_gross_total' , ci_gst = ci_gst + '$ci_gst', ci_discount = ci_discount + '$ci_discount', ci_total = ci_total + '$ci_total' WHERE ci_id = '".$row->ci_id."'") or die(mysqli_error($GLOBALS['conn']));
                        $update_cart = mysqli_query($GLOBALS['conn'], "UPDATE cart SET cart_weight_total=(SELECT SUM(ci_weight) FROM cart_items WHERE cart_id=$cart_id), cart_gross_total=(SELECT SUM(ci_gross_total) FROM cart_items WHERE cart_id=$cart_id), cart_gst=(SELECT SUM(ci_gst) FROM cart_items WHERE cart_id=$cart_id), cart_discount=(SELECT SUM(ci_discount) FROM cart_items WHERE cart_id=$cart_id), cart_amount=(SELECT SUM(ci_total) FROM cart_items WHERE cart_id=$cart_id) WHERE cart_id=" . $cart_id) or die(mysqli_error($GLOBALS['conn']));
                        $_SESSION['header_quantity'] = $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], "SELECT * FROM `cart_items` WHERE `cart_id` = '".$cart_id."'"));
                    if($updated_cart_item == true && $update_cart == true)
                    {
                        //echo "success";
                        $retValue = array("status" => "1", "message" => "The recorded quantity has been updated to the bucket successfully", "count" => "$count");
                    }
                    else
                    {
                        $retValue = array("status" => "0", "message" => "Record added fail!");
                    }

                    } else {
                        $insert_cart_item = mysqli_query($GLOBALS['conn'], "INSERT INTO cart_items ( ci_id, cart_id, pro_id, ps_id, ps_purchase_price, ci_weight, ci_qty, ci_amount, ci_gross_total, ci_gst, ci_discount, ci_total) VALUES ('" . $ci_id . "','" . dbStr($cart_id) . "', '" . dbStr($pro_id) . "','".dbStr($ps_id)."','".dbStr(trim($ps_purchase_price))."','".dbStr(trim($ci_weight))."','" . dbStr($ci_qty) . "', '" . dbStr($ci_amount) . "', '".dbStr(trim($ci_gross_total))."', '".dbStr(trim($ci_gst))."', '".dbStr(trim($ci_discount))."','" . dbStr($ci_total) . "')") or die(mysqli_error($GLOBALS['conn']));

                    //$update_cart = mysqli_query($GLOBALS['conn'], "UPDATE cart SET cart_amount=(SELECT SUM(ci_total) FROM cart_items WHERE cart_id=$cart_id) WHERE cart_id=" . $cart_id) or die(mysqli_error($GLOBALS['conn']));
                    $update_cart = mysqli_query($GLOBALS['conn'], "UPDATE cart SET cart_weight_total=(SELECT SUM(ci_weight) FROM cart_items WHERE cart_id=$cart_id), cart_gross_total=(SELECT SUM(ci_gross_total) FROM cart_items WHERE cart_id=$cart_id), cart_gst=(SELECT SUM(ci_gst) FROM cart_items WHERE cart_id=$cart_id), cart_discount=(SELECT SUM(ci_discount) FROM cart_items WHERE cart_id=$cart_id), cart_amount=(SELECT SUM(ci_total) FROM cart_items WHERE cart_id=$cart_id) WHERE cart_id=" . $cart_id) or die(mysqli_error($GLOBALS['conn']));
                    $_SESSION['header_quantity'] = $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], "SELECT * FROM `cart_items` WHERE `cart_id` = '".$cart_id."'"));
                    if($insert_cart_item == true && $update_cart == true)
                    {
                        //echo "success";
                        $retValue = array("status" => "1", "message" => "The record has been added to the bucket successfully", "count" => "$count");
                    }
                    else
                    {
                        $retValue = array("status" => "0", "message" => "Record added fail!");
                    }
                    }
                }

            } else {

                $cart_id = getMaximum("cart", "cart_id");
                $_SESSION['cart_id']=$cart_id;
                $sess_id = session_id();
                $_SESSION['sess_id']=$sess_id;

                $insert_cart = mysqli_query($GLOBALS['conn'], "INSERT INTO cart ( cart_id, sess_id, cart_datetime) VALUES ('" . $cart_id . "','" . dbStr($sess_id) . "', '" . dbStr($cart_datetime) . "')") or die(mysqli_error($GLOBALS['conn']));

                $ci_id = getMaximum("cart_items", "ci_id");
                $_SESSION['ci_id'] = $ci_id;

                $insert_cart_item = mysqli_query($GLOBALS['conn'], "INSERT INTO cart_items ( ci_id, cart_id, pro_id, ps_id, ps_purchase_price, ci_weight, ci_qty, ci_amount, ci_gross_total, ci_gst, ci_discount, ci_total) VALUES ('" . $ci_id . "','" . dbStr($cart_id) . "', '" . dbStr($pro_id) . "','".dbStr($ps_id)."','".dbStr(trim($ps_purchase_price))."','".dbStr(trim($ci_weight))."','" . dbStr($ci_qty) . "', '" . dbStr($ci_amount) . "', '".dbStr(trim($ci_gross_total))."', '".dbStr(trim($ci_gst))."', '".dbStr($ci_discount)."', '" . dbStr($ci_total) . "')") or die(mysqli_error($GLOBALS['conn']));
                //$update_cart = mysqli_query($GLOBALS['conn'], "UPDATE cart SET cart_amount=(SELECT SUM(ci_total) FROM cart_items WHERE cart_id=$cart_id) WHERE cart_id=" . $cart_id) or die(mysqli_error($GLOBALS['conn']));
                $update_cart = mysqli_query($GLOBALS['conn'], "UPDATE cart SET cart_weight_total=(SELECT SUM(ci_weight) FROM cart_items WHERE cart_id=$cart_id), cart_gross_total=(SELECT SUM(ci_gross_total) FROM cart_items WHERE cart_id=$cart_id), cart_gst=(SELECT SUM(ci_gst) FROM cart_items WHERE cart_id=$cart_id), cart_discount=(SELECT SUM(ci_discount) FROM cart_items WHERE cart_id=$cart_id), cart_amount=(SELECT SUM(ci_total) FROM cart_items WHERE cart_id=$cart_id) WHERE cart_id=" . $cart_id) or die(mysqli_error($GLOBALS['conn']));
                $_SESSION['header_quantity'] = $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], "SELECT * FROM `cart_items` WHERE `cart_id` = '".$cart_id."'"));
                if($insert_cart == true && $insert_cart_item == true && $update_cart == true)
                {
                    $retValue = array("status" => "1", "message" => "The record has been added to the bucket successfully", "count" => "$count");
                    
                }
                else
                {
                    $retValue = array("status" => "0", "message" => "Record added fail!");
                }
                
            }
            //return $retValue;
            $jsonResults = json_encode($retValue);
            print($jsonResults);
        break;
        }
    }