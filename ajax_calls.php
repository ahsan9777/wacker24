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
            $Query = "SELECT pro.pro_id, pro.supplier_id, pro.pro_description_short FROM products AS pro " . $where . " ORDER BY pro.pro_id  LIMIT 0,20";
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
            if ($ci_discount_value > 0) {
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
                        $insert_cart_item = mysqli_query($GLOBALS['conn'], "INSERT INTO cart_items ( ci_id, cart_id, pro_id, supplier_id, pbp_id, pbp_price_amount, ci_amount, ci_discounted_amount, ci_qty, ci_gross_total, ci_gst, ci_discount_type, ci_discount_value, ci_discount, ci_total) VALUES ('" . $ci_id . "','" . dbStr($cart_id) . "', '" . dbStr($pro_id) . "','" . dbStr($supplier_id) . "', '" . dbStr(trim($pbp_id)) . "', '" . dbStr($pbp_price_amount) . "', '" . dbStr($ci_amount) . "', '" . dbStr($ci_discounted_amount) . "', '" . dbStr($ci_qty) . "', '" . dbStr(trim($ci_gross_total)) . "', '" . dbStr(trim($ci_gst)) . "', '" . dbStr(trim($ci_discount_type)) . "', '" . dbStr(trim($ci_discount_value)) . "', '" . dbStr($ci_discount) . "', '" . dbStr($ci_total) . "')") or die(mysqli_error($GLOBALS['conn']));
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
                $Query = "SELECT ci.*, pg.pg_mime_source FROM cart_items AS ci LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = ci.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' WHERE ci.cart_id = '" . $_SESSION['cart_id'] . "' ORDER BY ci.ci_id ASC";
                $rs = mysqli_query($GLOBALS['conn'], $Query);
                if (mysqli_num_rows($rs) > 0) {
                    $_SESSION['header_quantity'] = $count = TotalRecords("ci_id", "cart_items", "WHERE cart_id=" . $_SESSION['cart_id']);
                    while ($row = mysqli_fetch_object($rs)) {
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
                                    <div class="side_cart_pd_image"><a href="product_detail.php?supplier_id=' . $row->supplier_id . '"><img src="getftpimage.php?img=' . $row->pg_mime_source . '" alt=""></a></div>
                                    ' . $cart_price_data . '
                                    <div class="side_cart_pd_qty">
                                        <div class="side_pd_qty">
                                            <input type="number" class="qlt_number" value="' . $row->ci_qty . '">
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
                    'value' => strip_tags(html_entity_decode($row->zc_zipcode." ".$row->zc_town, ENT_QUOTES, 'UTF-8'))
                );
            }
            $jsonResults = json_encode($json);
            print($jsonResults);
            break;
    }
}
