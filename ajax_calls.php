<?php
include("includes/php_includes_top.php");
if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'switch_click':
            $retValue = array();
            //print_r($_REQUEST);die();
            $_SESSION['utype_id'] = $_REQUEST['utype_id'];

            if(isset($_REQUEST['ci_total']) && $_REQUEST['ci_total'] > 0){
                $delivery_charges = get_delivery_charges($_REQUEST['ci_total']);
                $retValue = array("status" => "1", "message" => "Record get successfully", "delivery_charges" => $delivery_charges, "utype_id" => $_SESSION['utype_id']);
            } else{
                $retValue = array("status" => "0", "message" => "Record not found!", "utype_id" => $_SESSION['utype_id']);
            }
            $jsonResults = json_encode($retValue);
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
            if($ci_discount_value > 0){
                $ci_discounted_amount_gross = 0;
                $ci_amount = discounted_price($ci_discount_type, $ci_amount, $ci_discount_value);
                $ci_discounted_amount = $pbp_price_amount - $ci_amount;
                
                $ci_discounted_amount_gross = $ci_discounted_amount * $ci_qty; 
                $ci_discount = $ci_discounted_amount_gross + ($ci_discounted_amount_gross * config_gst); 
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
                        $row = mysqli_fetch_object($rs);

                        $cart_quantity = returnName("ci_qty","cart_items", "ci_id", $row->ci_id);
                        $get_pro_price = get_pro_price($pro_id, $supplier_id, $ci_qty + $cart_quantity);
                        //print_r($get_pro_price);
                        $pbp_id = $get_pro_price['pbp_id'];
                        $pbp_price_amount = $get_pro_price['ci_amount'];
                        $ci_amount = $get_pro_price['ci_amount'];
                        $ci_discount_type = $row->ci_discount_type;
				        $ci_discount_value = $row->ci_discount_value;
                        $ci_discounted_amount = 0;
                        $ci_discount = 0;
                        if($ci_discount_value > 0){
                            $ci_discounted_amount_gross = 0;
                            $ci_amount = discounted_price($ci_discount_type, $ci_amount, $ci_discount_value);
                            $ci_discounted_amount = $pbp_price_amount - $ci_amount;
                            
                            $ci_discounted_amount_gross = $ci_discounted_amount * ($ci_qty + $cart_quantity); 
                            $ci_discount = $ci_discounted_amount_gross + ($ci_discounted_amount_gross * config_gst); 
                        }
                        $ci_gross_total = $ci_amount * ($ci_qty + $cart_quantity);
                        $ci_gst = $ci_gross_total * config_gst;
                        $ci_total = $ci_gross_total + $ci_gst;

                        //$updated_cart_item = mysqli_query($GLOBALS['conn'], "UPDATE cart_items SET pbp_id = '".$pbp_id."', ci_amount = '".$ci_amount."', ci_qty = ci_qty + '$ci_qty',  ci_gross_total = ci_gross_total + '$ci_gross_total' , ci_gst = ci_gst + '$ci_gst', ci_discount = ci_discount + '$ci_discount', ci_total = ci_total + '$ci_total' WHERE ci_id = '" . $row->ci_id . "'") or die(mysqli_error($GLOBALS['conn']));
                        $updated_cart_item = mysqli_query($GLOBALS['conn'], "UPDATE cart_items SET pbp_id = '".$pbp_id."', pbp_price_amount = '".$pbp_price_amount."', ci_amount = '".$ci_amount."', ci_discounted_amount = '".$ci_discounted_amount."', ci_qty = ci_qty + '$ci_qty',  ci_gross_total = '$ci_gross_total' , ci_gst = '$ci_gst',  ci_discount =  '$ci_discount', ci_total =  '$ci_total' WHERE ci_id = '" . $row->ci_id . "'") or die(mysqli_error($GLOBALS['conn']));
                        $update_cart = mysqli_query($GLOBALS['conn'], "UPDATE cart SET cart_gross_total=(SELECT SUM(ci_gross_total) FROM cart_items WHERE cart_id=$cart_id), cart_gst=(SELECT SUM(ci_gst) FROM cart_items WHERE cart_id=$cart_id), cart_discount=(SELECT SUM(ci_discount) FROM cart_items WHERE cart_id=$cart_id), cart_amount=(SELECT SUM(ci_total) FROM cart_items WHERE cart_id=$cart_id) WHERE cart_id=" . $cart_id) or die(mysqli_error($GLOBALS['conn']));
                        $_SESSION['header_quantity'] = $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], "SELECT * FROM `cart_items` WHERE `cart_id` = '" . $cart_id . "'"));
                        if ($updated_cart_item == true && $update_cart == true) {
                            //echo "success";
                            $retValue = array("status" => "1", "message" => "The recorded quantity has been updated to the bucket successfully", "count" => "$count");
                        } else {
                            $retValue = array("status" => "0", "message" => "Record added fail!");
                        }
                    } else {
                        $insert_cart_item = mysqli_query($GLOBALS['conn'], "INSERT INTO cart_items ( ci_id, cart_id, pro_id, supplier_id, pbp_id, pbp_price_amount, ci_amount, ci_discounted_amount, ci_qty, ci_gross_total, ci_gst, ci_discount_type, ci_discount_value, ci_discount, ci_total) VALUES ('" . $ci_id . "','" . dbStr($cart_id) . "', '" . dbStr($pro_id) . "','" . dbStr($supplier_id) . "', '" . dbStr(trim($pbp_id)) . "', '".dbStr($pbp_price_amount)."', '" . dbStr($ci_amount) . "', '".dbStr($ci_discounted_amount)."', '" . dbStr($ci_qty) . "', '" . dbStr(trim($ci_gross_total)) . "', '" . dbStr(trim($ci_gst)) . "', '".dbStr(trim($ci_discount_type))."', '".dbStr(trim($ci_discount_value))."', '" . dbStr($ci_discount) . "', '" . dbStr($ci_total) . "')") or die(mysqli_error($GLOBALS['conn']));
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

                $cart_id = getMaximum("cart", "cart_id");
                $_SESSION['cart_id'] = $cart_id;
                $sess_id = session_id();
                $_SESSION['sess_id'] = $sess_id;

                $insert_cart = mysqli_query($GLOBALS['conn'], "INSERT INTO cart ( cart_id, sess_id, cart_datetime) VALUES ('" . $cart_id . "','" . dbStr($sess_id) . "', '" . dbStr($cart_datetime) . "')") or die(mysqli_error($GLOBALS['conn']));

                $ci_id = getMaximum("cart_items", "ci_id");
                $_SESSION['ci_id'] = $ci_id;

                $insert_cart_item = mysqli_query($GLOBALS['conn'], "INSERT INTO cart_items ( ci_id, cart_id, pro_id, supplier_id, pbp_id, pbp_price_amount, ci_amount, ci_discounted_amount, ci_qty, ci_gross_total, ci_gst, ci_discount_type, ci_discount_value, ci_discount, ci_total) VALUES ('" . $ci_id . "','" . dbStr($cart_id) . "', '" . dbStr($pro_id) . "','" . dbStr($supplier_id) . "', '" . dbStr(trim($pbp_id)) . "', '".dbStr($pbp_price_amount)."', '" . dbStr($ci_amount) . "', '".dbStr($ci_discounted_amount)."', '" . dbStr($ci_qty) . "', '" . dbStr(trim($ci_gross_total)) . "', '" . dbStr(trim($ci_gst)) . "', '".dbStr(trim($ci_discount_type))."', '".dbStr(trim($ci_discount_value))."', '" . dbStr($ci_discount) . "', '" . dbStr($ci_total) . "')") or die(mysqli_error($GLOBALS['conn']));
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
                $cart_amount = 0;
                $Query = "SELECT ci.*, pg.pg_mime_source FROM cart_items AS ci LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = ci.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' WHERE ci.cart_id = '" . $_SESSION['cart_id'] . "' ORDER BY ci.ci_id ASC";
                $rs = mysqli_query($GLOBALS['conn'], $Query);
                if (mysqli_num_rows($rs) > 0) {
                    while ($row = mysqli_fetch_object($rs)) {
                        $cart_amount = $cart_amount + $row->ci_total;
                        $gst = $row->ci_amount * config_gst;
                        $gst_orignal = $row->pbp_price_amount * config_gst;
                        $display_one = "";
                        $display_two = "";
                        if(isset($_SESSION['utype_id']) && $_SESSION['utype_id'] == 4){
                            $display_one = "style = 'display: block;'";
                            $display_two = "style = 'display: none;'";
                        }
                        $cart_price_data = "";
                        if ($row->ci_discount_value > 0) {
                            $cart_price_data .= '<div class="side_cart_pd_prise price_without_tex" '.$display_one.' > <del class="orignal_price"> '.str_replace(".", ",", $row->pbp_price_amount).' €</del> <br> <span class="pd_prise_discount"> '.str_replace(".", ",", $row->ci_amount) . "€ " . $row->ci_discount_value . (($row->ci_discount_type > 0) ? "€" : "%").' </span></div>';
                            $cart_price_data .= '<div class="side_cart_pd_prise pbp_price_with_tex" '.$display_two.' > <del class="orignal_price"> '.number_format($row->pbp_price_amount + $gst_orignal, "2", ",", "").' €</del> <br> <span class="pd_prise_discount"> '.number_format($row->ci_amount + $gst, "2", ",", "") . "€ " . $row->ci_discount_value . (($row->ci_discount_type > 0) ? '€' : '%').' </span> </div>';
                        } else {
                            $cart_price_data .= '<div class="side_cart_pd_prise price_without_tex" '.$display_one.' >'.number_format($row->ci_amount, "2", ",", "").' €</div>';
                            $cart_price_data .= '<div class="side_cart_pd_prise pbp_price_with_tex" '.$display_two.' >'.number_format($row->ci_amount + $gst, "2", ",", "").' €</div>';
                        }
                        $show_card_body .= '
                                <div class="side_cart_pd_row">
                                    <div class="side_cart_pd_image"><a href="product_detail.php?supplier_id='.$row->supplier_id.'"><img src="getftpimage.php?img='.$row->pg_mime_source.'" alt=""></a></div>
                                    '.$cart_price_data.'
                                    <div class="side_cart_pd_qty">
                                        <div class="side_pd_qty">
                                            <input type="number" class="qlt_number" value="'.$row->ci_qty.'">
                                        </div>
                                        <div class="side_pd_delete"><a  class = "item_deleted" data-id = "'.$row->ci_id.'" href="javascript:void(0)"><i class="fa fa-trash"></i></a></div>
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
                                </script>
                        ';
                    }

                    $retValue = array("status" => "1", "message" => "Record found successfully!", "cart_amount" => str_replace(".", ",", $cart_amount), "show_card_body" => $show_card_body);
                } else {
                    $retValue = array("status" => "0", "message" => "Record not found!", "cart_amount" => str_replace(".", ",", $cart_amount), "show_card_body" => $show_card_body);
                }
            } else {
                $retValue = array("status" => "0", "message" => "Record not found!");
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
                    mysqli_query($GLOBALS['conn'], "UPDATE cart SET cart_gross_total=(SELECT SUM(ci_gross_total) FROM cart_items WHERE cart_id= '".$_SESSION['cart_id']."'), cart_gst=(SELECT SUM(ci_gst) FROM cart_items WHERE cart_id= '".$_SESSION['cart_id']."'), cart_discount=(SELECT SUM(ci_discount) FROM cart_items WHERE cart_id= '".$_SESSION['cart_id']."'), cart_amount=(SELECT SUM(ci_total) FROM cart_items WHERE cart_id= '".$_SESSION['cart_id']."') WHERE cart_id=" . $_SESSION['cart_id']) or die(mysqli_error($GLOBALS['conn']));
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

            $Query = "SELECT user_id, user_fname, user_lname, user_phone FROM users WHERE user_id = '".$_SESSION["UID"]."'";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if(mysqli_num_rows($rs) > 0){
                $retValue = array("status" => "1", "message" => "Get my data");
                $row = mysqli_fetch_object($rs);
                    $retValue['data'][] = array(
                        "user_id" => strval($row->user_id),
                        "user_fname" => strval($row->user_fname),
                        "user_lname" => strval($row->user_lname),
                        "user_phone" => strval($row->user_phone)
                    );
            } else{
                $retValue = array("status" => "0", "message" => "Record not found!");
            }

            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;
    }
}
